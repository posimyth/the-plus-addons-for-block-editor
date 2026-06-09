<?php
/**
 * Shared helpers for Nexter Blocks MCP abilities (block lookup, insertion,
 * sanitisation, font-family schema, and button-preset wiring).
 *
 * @package The_Plus_Addons_For_Block_Editor
 * @since   1.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check whether a Gutenberg block type is registered.
 *
 * @param string $block_name Fully-qualified block name (e.g. "tpgb/tp-heading").
 * @return bool True if registered with WP_Block_Type_Registry.
 */
function tpgb_mcp_has_registered_block( string $block_name ): bool {
	$registry = WP_Block_Type_Registry::get_instance();
	return $registry->is_registered( $block_name );
}

/**
 * Parse and return the blocks of a post by ID.
 *
 * @param int $post_id Target post ID.
 * @return array|WP_Error Parsed blocks array or WP_Error if the post is missing.
 */
function tpgb_mcp_get_blocks( int $post_id ) {
	$post = get_post( $post_id );
	if ( ! $post instanceof WP_Post ) {
		return new WP_Error( 'invalid_post', 'Post not found.' );
	}
	return parse_blocks( $post->post_content ?? '' );
}

/**
 * Serialise blocks back to a post's post_content.
 *
 * @param int   $post_id Target post ID.
 * @param array $blocks  Block tree to serialise.
 * @return true|WP_Error True on success, WP_Error on failure.
 */
function tpgb_mcp_save_blocks( int $post_id, array $blocks ) {
	// Defense in depth: each ability's permission_callback already gates access,
	// but save_blocks is the single chokepoint that mutates post_content — so
	// re-check per-post edit capability here too. Catches future callers that
	// forget to gate, and also catches the case where the user can `edit_posts`
	// in general but not this specific post (other-author drafts, locked posts).
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return new WP_Error( 'forbidden', __( 'You are not allowed to edit this post.', 'the-plus-addons-for-block-editor' ) );
	}
	$result = wp_update_post(
		array(
			'ID'           => $post_id,
			'post_content' => serialize_blocks( $blocks ),
		),
		true
	);
	return is_wp_error( $result ) ? $result : true;
}

/**
 * Generate a unique block_id matching Nexter Blocks' format (12 hex chars).
 *
 * @return string Hex block ID.
 */
function tpgb_mcp_generate_block_id(): string {
	return substr( bin2hex( random_bytes( 8 ) ), 0, 12 );
}

/**
 * Plain-text sanitizer for block content fields (titles, descriptions, button
 * labels, table cells, etc.). MCP clients sometimes try to embed HTML for
 * inline styling — this strips it. Block-level styling should always come from
 * the block's typography/color attributes, never from inline markup.
 *
 * Behavior: decodes HTML entities first (so `&lt;b&gt;` does not survive a tag
 * strip), then removes all tags, then trims. Newlines are preserved.
 *
 * @param mixed $value Raw input value.
 * @return string Cleaned plain-text string.
 */
function tpgb_mcp_clean_text( $value ): string {
	if ( ! is_string( $value ) ) {
		return '';
	}
	$value = html_entity_decode( $value, ENT_QUOTES | ENT_HTML5, 'UTF-8' );
	$value = wp_strip_all_tags( $value );
	return trim( $value );
}

/**
 * Build the fontFamily sub-attribute used inside every typography array.
 * When neither family nor customFont is provided, returns (object) [] so
 * the saved attrs still match the editor's "no font" shape (an empty
 * object, not an empty array).
 *
 * Inputs read from $input:
 *   fontFamily  — Google Fonts family name (e.g. "Inter", "Playfair Display")
 *   fontType    — category: "sans-serif", "serif", "display", "handwriting", "monospace"
 *   customFont  — custom (non-Google) font name; takes precedence over fontFamily
 *
 * @param array $input Ability input arguments.
 * @return array|object Font-family attribute structured for the block.
 */
function tpgb_mcp_font_family_attr( array $input ) {
	$family   = isset( $input['fontFamily'] ) ? sanitize_text_field( (string) $input['fontFamily'] ) : '';
	$type     = isset( $input['fontType'] ) ? sanitize_text_field( (string) $input['fontType'] ) : '';
	$custom_f = isset( $input['customFont'] ) ? sanitize_text_field( (string) $input['customFont'] ) : '';
	$weight   = isset( $input['fontWeight'] ) ? sanitize_text_field( (string) $input['fontWeight'] ) : '';
	if ( '' === $family && '' === $custom_f && '' === $weight ) {
		return (object) array();
	}
	$attr = array(
		'family'     => $family,
		'type'       => $type,
		'customFont' => $custom_f,
	);
	if ( '' !== $weight ) {
		$attr['fontWeight'] = $weight;
	}
	return $attr;
}

/**
 * Reusable schema fragment for the font-family inputs every typography-bearing
 * ability should expose. Spread into a schema's `properties` map with the
 * spread operator: `'properties' => [ ...tpgb_mcp_font_family_schema(), ... ]`.
 *
 * @return array Schema properties for fontFamily, fontType, customFont, fontWeight, textDecoration.
 */
function tpgb_mcp_font_family_schema(): array {
	return array(
		'fontFamily'     => array(
			'type'        => 'string',
			'description' => 'Font family name (e.g. "Inter", "Roboto", "Playfair Display"). Used as a Google Font when available. When inspecting a URL with nexter-blocks/inspect-page, pass the returned fonts[].family value verbatim.',
			'default'     => '',
		),
		'fontType'       => array(
			'type'        => 'string',
			'description' => 'Font category — "sans-serif", "serif", "display", "handwriting", or "monospace". Optional but improves Google-Fonts URL accuracy.',
			'default'     => '',
		),
		'customFont'     => array(
			'type'        => 'string',
			'description' => 'Custom (non-Google) font name. When set, this overrides fontFamily.',
			'default'     => '',
		),
		'fontWeight'     => array(
			'type'        => 'string',
			'description' => 'Font weight as a string: "100" Thin, "200" Extra Light, "300" Light, "400" Regular (default), "500" Medium, "600" Semi Bold, "700" Bold, "800" Extra Bold, "900" Black. Pass alongside fontFamily — it is embedded inside the tTypo.fontFamily.fontWeight slot.',
			'default'     => '',
		),
		'textDecoration' => array(
			'type'        => 'string',
			'enum'        => array( '', 'none', 'underline', 'overline', 'line-through' ),
			'description' => 'Text decoration applied to the block typography. Stored as tTypo.textDecoration (sibling of fontFamily). Leave empty to inherit.',
			'default'     => '',
		),
	);
}

/**
 * Apply optional typography extras (textDecoration) onto a built typo array.
 *
 * Each ability builds its own typo attribute (tTypo / titleTypo / texTyp / …)
 * with fontFamily already resolved via tpgb_mcp_font_family_attr(). Call this
 * right after assembling that array to attach textDecoration when the caller
 * provided it. Sibling of fontFamily — not nested inside it.
 *
 * @param array $typo  Built typo attribute array (modified in place).
 * @param array $input Ability input arguments — only textDecoration is read.
 * @return array The typo array (returned for chained assignment convenience).
 */
function tpgb_mcp_apply_typo_extras( array $typo, array $input ): array {
	$decoration = isset( $input['textDecoration'] ) ? sanitize_text_field( (string) $input['textDecoration'] ) : '';
	if ( '' !== $decoration ) {
		$typo['textDecoration'] = $decoration;
	}
	return $typo;
}

/**
 * Apply textDecoration to every typo-shaped attribute already in $attrs.
 *
 * Multi-typo-group abilities (infobox, pricing-table, accordion, …) build
 * several typo arrays from the same shared $input. Rather than wrap each
 * assignment with tpgb_mcp_apply_typo_extras() one by one, the ability
 * builds all its typos first and then calls this helper once before
 * merging the raw-settings override. Auto-detection: anything in $attrs
 * that looks like a typo array (has both openTypography and fontFamily
 * keys) gets textDecoration attached. Top-level shared input — for
 * per-group control, callers must use the settings raw override or
 * sprout/update-element.
 *
 * No-op when the caller did not pass textDecoration.
 *
 * @param array $attrs Block attributes built so far (passed by reference).
 * @param array $input Ability input arguments — only textDecoration is read.
 * @return void
 */
function tpgb_mcp_apply_typo_decoration( array &$attrs, array $input ): void {
	$decoration = isset( $input['textDecoration'] ) ? sanitize_text_field( (string) $input['textDecoration'] ) : '';
	if ( '' === $decoration ) {
		return;
	}
	foreach ( $attrs as &$value ) {
		if ( is_array( $value ) && array_key_exists( 'openTypography', $value ) && array_key_exists( 'fontFamily', $value ) ) {
			$value['textDecoration'] = $decoration;
		}
	}
	unset( $value );
}

/**
 * Schema fragment for the two shared typography extras (fontWeight,
 * textDecoration) that every multi-typo-group block should expose at the
 * top level. Spread into a schema's `properties` map alongside the existing
 * fontFamily/fontType/customFont entries:
 *
 *   'properties' => array_merge( $schema, tpgb_mcp_typo_extras_schema() ).
 *
 * fontWeight is read by tpgb_mcp_font_family_attr() and embedded inside
 * fontFamily.fontWeight. textDecoration is read by
 * tpgb_mcp_apply_typo_decoration() and stamped as a sibling of fontFamily
 * onto every typo array in the block.
 *
 * @return array Schema properties for fontWeight + textDecoration.
 */
function tpgb_mcp_typo_extras_schema(): array {
	return array(
		'fontWeight'     => array(
			'type'        => 'string',
			'description' => 'Font weight applied to every typography group on this block (e.g. "300", "400", "700", "900"). Embedded inside each typo group\'s fontFamily.fontWeight. For per-group control use the settings raw override or sprout/update-element.',
			'default'     => '',
		),
		'textDecoration' => array(
			'type'        => 'string',
			'enum'        => array( '', 'none', 'underline', 'overline', 'line-through' ),
			'description' => 'Text decoration applied to every typography group on this block. Stamped as a sibling of fontFamily inside each typo array. For per-group control use the settings raw override or sprout/update-element.',
			'default'     => '',
		),
	);
}

/**
 * Build a block array ready for insertion into a block tree.
 *
 * @param string $block_name Fully-qualified block name.
 * @param array  $attrs      Block attributes.
 * @return array Block array suitable for serialize_blocks().
 */
function tpgb_mcp_build_block( string $block_name, array $attrs = array() ): array {
	return array(
		'blockName'    => $block_name,
		'attrs'        => $attrs,
		'innerBlocks'  => array(),
		'innerHTML'    => '',
		'innerContent' => array(),
	);
}

/**
 * Insert a block at a position (-1 = append).
 *
 * @param array $blocks   Block tree (passed by reference).
 * @param array $block    Block to insert.
 * @param int   $position Zero-based index, or -1 to append.
 * @return void
 */
function tpgb_mcp_insert_block( array &$blocks, array $block, int $position = -1 ): void {
	// Filter out empty spacer blocks.
	$blocks = array_filter(
		$blocks,
		function ( $b ) {
			return ! empty( $b['blockName'] ) || ( isset( $b['innerHTML'] ) && trim( $b['innerHTML'] ) !== '' );
		}
	);
	$blocks = array_values( $blocks );

	if ( $position < 0 || $position >= count( $blocks ) ) {
		$blocks[] = $block;
	} else {
		array_splice( $blocks, $position, 0, array( $block ) );
	}
}

/**
 * Find a block by its block_id attribute (recursive search).
 * Returns a reference to the block array, or null if not found.
 *
 * @param array  $blocks   Block tree to search (passed by reference).
 * @param string $block_id Target block_id attribute value.
 * @return array|null Reference to the matching block, or null.
 */
function &tpgb_mcp_find_block_by_id( array &$blocks, string $block_id ) {
	// Static sentinel: a single, stable "not-found" reference. A plain local
	// $null would go out of scope after return and leave the caller holding a
	// dangling reference; the static keeps the address live for the lifetime
	// of the request.
	static $null = null;
	foreach ( $blocks as &$b ) {
		if ( isset( $b['attrs']['block_id'] ) && $b['attrs']['block_id'] === $block_id ) {
			return $b;
		}
		if ( ! empty( $b['innerBlocks'] ) ) {
			$found = &tpgb_mcp_find_block_by_id( $b['innerBlocks'], $block_id );
			if ( null !== $found ) {
				return $found;
			}
		}
	}
	return $null;
}

/**
 * Insert a child block inside a parent block's innerBlocks.
 * Finds the parent by block_id and appends/inserts the child.
 * Returns true on success, false if parent not found.
 *
 * @param array  $blocks          Block tree (passed by reference).
 * @param string $parent_block_id block_id attribute of the parent block.
 * @param array  $child_block     Child block to insert.
 * @param int    $position        Zero-based index inside innerBlocks, or -1 to append.
 * @return bool True if inserted, false if the parent could not be found.
 */
function tpgb_mcp_insert_inner_block( array &$blocks, string $parent_block_id, array $child_block, int $position = -1 ): bool {
	$parent = &tpgb_mcp_find_block_by_id( $blocks, $parent_block_id );
	if ( null === $parent ) {
		return false;
	}

	if ( ! isset( $parent['innerBlocks'] ) ) {
		$parent['innerBlocks'] = array();
	}

	if ( $position < 0 || $position >= count( $parent['innerBlocks'] ) ) {
		$parent['innerBlocks'][] = $child_block;
	} else {
		array_splice( $parent['innerBlocks'], $position, 0, array( $child_block ) );
	}

	// Update innerContent to reflect the new inner blocks.
	// Each inner block is represented by null in innerContent.
	$parent['innerContent'] = array();
	foreach ( $parent['innerBlocks'] as $ib ) {
		$parent['innerContent'][] = null;
	}

	return true;
}

/**
 * Validate a button preset key against the active preset store.
 *
 * @param string $preset_key The preset key to validate (e.g. 'btnpreset1').
 * @return string|WP_Error The sanitized key on success, WP_Error if the preset does not exist.
 */
function tpgb_mcp_validate_button_preset( string $preset_key ) {
	if ( empty( $preset_key ) ) {
		return new WP_Error( 'invalid_preset', __( 'Button preset key is required.', 'the-plus-addons-for-block-editor' ) );
	}
	if ( ! class_exists( 'Tpgb_Button_Preset_Vars' ) || ! method_exists( 'Tpgb_Button_Preset_Vars', 'get_active_presets' ) ) {
		return new WP_Error( 'preset_system_missing', __( 'Button preset system is not available.', 'the-plus-addons-for-block-editor' ) );
	}
	$presets = Tpgb_Button_Preset_Vars::get_active_presets();
	if ( empty( $presets[ $preset_key ] ) ) {
		return new WP_Error(
			'preset_not_found',
			sprintf(
				/* translators: %s: button preset key requested by the caller. */
				__( 'Button preset "%s" was not found in the active preset set.', 'the-plus-addons-for-block-editor' ),
				$preset_key
			)
		);
	}
	return $preset_key;
}

/**
 * Apply preset pointer attributes to a block attribute array.
 *
 * This does NOT stamp var-ref shapes into $attrs — it only sets the
 * pointer/toggle attributes that the PHP CSS generator and the editor
 * heal logic use to materialise the preset.  Keeping $attrs minimal
 * lets the editor's selfHealLegacyPresetBlock / useGlobalButtonLivePreset
 * hooks run cleanly on first open.
 *
 * @param array  $attrs      Block attributes (passed by reference).
 * @param string $preset_key Sanitised preset key.
 * @param string $mode       'direct'   useGlobalButtonSettings + selectedButtonPreset.
 *                           'back'     useGlobalBackButtonSettings + selectedBackButtonPreset.
 *                           'external' extBtnGlobalMode + extBtnPresetKey + extBtnStyle='style-8'.
 * @return void
 */
function tpgb_mcp_apply_button_preset( array &$attrs, string $preset_key, string $mode = 'direct' ): void {
	switch ( $mode ) {
		case 'direct':
			$attrs['useGlobalButtonSettings'] = true;
			$attrs['selectedButtonPreset']    = $preset_key;
			$attrs['buttonPresetBackup']      = (object) array();
			break;

		case 'back':
			$attrs['useGlobalBackButtonSettings'] = true;
			$attrs['selectedBackButtonPreset']    = $preset_key;
			break;

		case 'external':
			$attrs['extBtnGlobalMode'] = true;
			$attrs['extBtnPresetKey']  = $preset_key;
			$attrs['extBtnStyle']      = 'style-8';
			break;

		default:
			// Unknown mode — leave $attrs untouched rather than guessing. The
			// caller picks the mode, so an unrecognised one is a programmer
			// error, not user input.
			break;
	}
}
