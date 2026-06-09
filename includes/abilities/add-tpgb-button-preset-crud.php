<?php
/**
 * Abilities: CRUD for Nexter Blocks Button Presets.
 *
 * Registers five abilities under the `nexter-blocks` category that let an
 * MCP-connected client list, read, create, update and delete entries in the
 * shared button-preset store backed by `tpgb_global_options.buttonPresets`.
 *
 * Writes go through `update_option`, so the existing
 * `update_option_tpgb_global_options` hook on `Tpgb_Button_Preset_Vars`
 * regenerates `plus-button-presets.css` automatically — no extra rebuild
 * step is needed in any of these callbacks.
 *
 * Storage shape is preserved on round-trip: when the option uses the
 * `presets[<active>].buttonPresets` shape (PRO preset switcher), writes go
 * back there; otherwise the flat `buttonPresets` map is used (matches what
 * the JS picker writes via `updateButtonPresetsSettings`).
 *
 * @package The_Plus_Addons_For_Block_Editor
 * @since   1.3.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// -------------------------------------------------------------------------
// SHARED INPUT SCHEMA — fields that describe a preset's visual style.
// -------------------------------------------------------------------------

/**
 * The 11 fields below mirror the visual-style inputs accepted by
 * add-tpgb-button-core.php. Extracted into a function so create + update
 * stay in lockstep without copy-paste drift.
 *
 * @return array Property map keyed by JSON-schema field name.
 */
function tpgb_mcp_btnpreset_style_input_schema(): array {
	return array(
		'textColor'        => array(
			'type'        => 'string',
			'description' => 'Button text colour (normal).',
			'default'     => '',
		),
		'textHoverColor'   => array(
			'type'        => 'string',
			'description' => 'Button text colour (hover).',
			'default'     => '',
		),
		'borderHoverColor' => array(
			'type'        => 'string',
			'description' => 'Border colour on hover.',
			'default'     => '',
		),

		'bgColor'          => array(
			'type'        => 'string',
			'description' => 'Button background colour (normal). Solid colour only — for gradients use the `settings` raw override.',
			'default'     => '',
		),
		'bgHoverColor'     => array(
			'type'        => 'string',
			'description' => 'Button background colour (hover).',
			'default'     => '',
		),

		'border'           => array(
			'type'        => 'object',
			'description' => 'Button border (normal) {type,color,width:{top,right,bottom,left,unit}}.',
		),
		'borderRadius'     => array(
			'type'        => 'object',
			'description' => 'Button border radius {top,bottom,left,right,unit}.',
		),
		'padding'          => array(
			'type'        => 'object',
			'description' => 'Button inner padding {top,bottom,left,right,unit}.',
		),

		'enableTypography' => array(
			'type'        => 'boolean',
			'description' => 'Enable custom typography on the preset.',
			'default'     => false,
		),
		'typoSize'         => array(
			'type'        => 'integer',
			'description' => 'Font size in px.',
			'default'     => 0,
		),
		'typoGlobalPreset' => array(
			'type'        => 'string',
			'description' => 'Global typography preset ID (numeric).',
			'default'     => '',
		),

		'enableTextShadow' => array(
			'type'        => 'boolean',
			'description' => 'Enable text shadow.',
			'default'     => false,
		),
		'textShadowH'      => array(
			'type'    => 'integer',
			'default' => 2,
		),
		'textShadowV'      => array(
			'type'    => 'integer',
			'default' => 3,
		),
		'textShadowBlur'   => array(
			'type'    => 'integer',
			'default' => 2,
		),
		'textShadowColor'  => array(
			'type'    => 'string',
			'default' => 'rgba(0,0,0,0.5)',
		),

		'enableBoxShadow'  => array(
			'type'        => 'boolean',
			'description' => 'Enable box shadow.',
			'default'     => false,
		),
		'boxShadowH'       => array(
			'type'    => 'integer',
			'default' => 0,
		),
		'boxShadowV'       => array(
			'type'    => 'integer',
			'default' => 4,
		),
		'boxShadowBlur'    => array(
			'type'    => 'integer',
			'default' => 8,
		),
		'boxShadowSpread'  => array(
			'type'    => 'integer',
			'default' => 0,
		),
		'boxShadowColor'   => array(
			'type'    => 'string',
			'default' => 'rgba(0,0,0,0.40)',
		),

		'settings'         => array(
			'type'        => 'object',
			'description' => 'Raw attribute overrides merged directly into the preset object after the typed fields are applied. Keys must use internal attribute names (btColor, bthColor, btBg, bthBg, bBord, bthBColor, brad, btPad, bTypo, btshadow, tShadow).',
		),
	);
}

// -------------------------------------------------------------------------
// ABILITY: list-tpgb-button-presets.
// -------------------------------------------------------------------------
wp_register_ability(
	'nexter-blocks/list-tpgb-button-presets',
	array(
		'label'               => __( 'List Nexter Blocks Button Presets', 'the-plus-addons-for-block-editor' ),
		'description'         => __( 'Lists every saved button preset in the active store. Returns the preset key, human name, and a small visual summary (text colour, background colour, border radius) for each entry.', 'the-plus-addons-for-block-editor' ),
		'category'            => 'nexter-blocks',

		'input_schema'        => array(
			'type'                 => 'object',
			'properties'           => new stdClass(),
			'additionalProperties' => false,
		),

		'output_schema'       => array(
			'type'       => 'object',
			'properties' => array(
				'count'   => array( 'type' => 'integer' ),
				'presets' => array( 'type' => 'array' ),
			),
		),

		'execute_callback'    => 'tpgb_mcp_btnpreset_list_ability',
		'permission_callback' => 'tpgb_mcp_btnpreset_permission',
		'meta'                => array(
			'show_in_rest' => true,
			'mcp'          => array(
				'public' => true,
				'type'   => 'tool',
			),
		),
	)
);

// -------------------------------------------------------------------------
// ABILITY: get-tpgb-button-preset.
// -------------------------------------------------------------------------
wp_register_ability(
	'nexter-blocks/get-tpgb-button-preset',
	array(
		'label'               => __( 'Get Nexter Blocks Button Preset', 'the-plus-addons-for-block-editor' ),
		'description'         => __( 'Returns the full attribute object for one button preset, identified by its preset_key (e.g. "btnpreset_12345678").', 'the-plus-addons-for-block-editor' ),
		'category'            => 'nexter-blocks',

		'input_schema'        => array(
			'type'                 => 'object',
			'properties'           => array(
				'preset_key' => array(
					'type'        => 'string',
					'description' => 'Preset key, e.g. "btnpreset_12345678". Use list-tpgb-button-presets to discover keys.',
				),
			),
			'required'             => array( 'preset_key' ),
			'additionalProperties' => false,
		),

		'output_schema'       => array(
			'type'       => 'object',
			'properties' => array(
				'preset_key' => array( 'type' => 'string' ),
				'name'       => array( 'type' => 'string' ),
				'preset'     => array( 'type' => 'object' ),
			),
		),

		'execute_callback'    => 'tpgb_mcp_btnpreset_get_ability',
		'permission_callback' => 'tpgb_mcp_btnpreset_permission',
		'meta'                => array(
			'show_in_rest' => true,
			'mcp'          => array(
				'public' => true,
				'type'   => 'tool',
			),
		),
	)
);

// -------------------------------------------------------------------------
// ABILITY: create-tpgb-button-preset.
// -------------------------------------------------------------------------
wp_register_ability(
	'nexter-blocks/create-tpgb-button-preset',
	array(
		'label'               => __( 'Create Nexter Blocks Button Preset', 'the-plus-addons-for-block-editor' ),
		'description'         => __( 'Creates a new button preset in the global preset store. The preset becomes immediately available in the inspector picker on every button block (tp-button-core, tp-button, tp-post-comment, etc.) and the plus-button-presets.css :root variables refresh on the next page load.', 'the-plus-addons-for-block-editor' ),
		'category'            => 'nexter-blocks',

		'input_schema'        => array(
			'type'                 => 'object',
			'properties'           => array_merge(
				array(
					'name' => array(
						'type'        => 'string',
						'description' => 'Human-readable preset name. 2–60 characters, no special characters. Must be unique across the existing preset store.',
					),
				),
				tpgb_mcp_btnpreset_style_input_schema()
			),
			'required'             => array( 'name' ),
			'additionalProperties' => false,
		),

		'output_schema'       => array(
			'type'       => 'object',
			'properties' => array(
				'preset_key' => array( 'type' => 'string' ),
				'name'       => array( 'type' => 'string' ),
				'preset'     => array( 'type' => 'object' ),
			),
		),

		'execute_callback'    => 'tpgb_mcp_btnpreset_create_ability',
		'permission_callback' => 'tpgb_mcp_btnpreset_permission',
		'meta'                => array(
			'show_in_rest' => true,
			'mcp'          => array(
				'public' => true,
				'type'   => 'tool',
			),
		),
	)
);

// -------------------------------------------------------------------------
// ABILITY: update-tpgb-button-preset.
// -------------------------------------------------------------------------
wp_register_ability(
	'nexter-blocks/update-tpgb-button-preset',
	array(
		'label'               => __( 'Update Nexter Blocks Button Preset', 'the-plus-addons-for-block-editor' ),
		'description'         => __( 'Updates an existing button preset. Only the fields you pass are changed — anything you omit is preserved. Pass `name` to rename. Pass any visual-style field (bgColor, padding, …) to overwrite that one attribute on the preset.', 'the-plus-addons-for-block-editor' ),
		'category'            => 'nexter-blocks',

		'input_schema'        => array(
			'type'                 => 'object',
			'properties'           => array_merge(
				array(
					'preset_key' => array(
						'type'        => 'string',
						'description' => 'Preset key to update. Use list-tpgb-button-presets to discover keys.',
					),
					'name'       => array(
						'type'        => 'string',
						'description' => 'New name. 2–60 characters, no special characters, unique across the store. Omit to keep the current name.',
					),
				),
				tpgb_mcp_btnpreset_style_input_schema()
			),
			'required'             => array( 'preset_key' ),
			'additionalProperties' => false,
		),

		'output_schema'       => array(
			'type'       => 'object',
			'properties' => array(
				'preset_key' => array( 'type' => 'string' ),
				'name'       => array( 'type' => 'string' ),
				'preset'     => array( 'type' => 'object' ),
			),
		),

		'execute_callback'    => 'tpgb_mcp_btnpreset_update_ability',
		'permission_callback' => 'tpgb_mcp_btnpreset_permission',
		'meta'                => array(
			'show_in_rest' => true,
			'mcp'          => array(
				'public' => true,
				'type'   => 'tool',
			),
		),
	)
);

// -------------------------------------------------------------------------
// ABILITY: delete-tpgb-button-preset.
// -------------------------------------------------------------------------
wp_register_ability(
	'nexter-blocks/delete-tpgb-button-preset',
	array(
		'label'               => __( 'Delete Nexter Blocks Button Preset', 'the-plus-addons-for-block-editor' ),
		'description'         => __( 'Removes a button preset from the global preset store. Existing button blocks that referenced this preset will keep their stamped var() refs in the post HTML — those refs simply stop resolving, so the block falls back to its default values until edited.', 'the-plus-addons-for-block-editor' ),
		'category'            => 'nexter-blocks',

		'input_schema'        => array(
			'type'                 => 'object',
			'properties'           => array(
				'preset_key' => array(
					'type'        => 'string',
					'description' => 'Preset key to delete.',
				),
			),
			'required'             => array( 'preset_key' ),
			'additionalProperties' => false,
		),

		'output_schema'       => array(
			'type'       => 'object',
			'properties' => array(
				'preset_key' => array( 'type' => 'string' ),
				'deleted'    => array( 'type' => 'boolean' ),
			),
		),

		'execute_callback'    => 'tpgb_mcp_btnpreset_delete_ability',
		'permission_callback' => 'tpgb_mcp_btnpreset_permission',
		'meta'                => array(
			'show_in_rest' => true,
			'mcp'          => array(
				'public' => true,
				'type'   => 'tool',
			),
		),
	)
);

// -------------------------------------------------------------------------
// PERMISSION CALLBACK
// -------------------------------------------------------------------------

/**
 * Mirrors the permission used by the existing
 * `/wp-json/tpgb/v1/theplus_global_settings` REST route that the JS picker
 * writes to (`edit_posts`). Keeping CRUD permissions aligned with the UI
 * means an MCP client can do whatever the editor UI can do — no more, no
 * less.
 *
 * @param array|null $input Ability input arguments (unused).
 * @return bool True when the current user may edit posts.
 */
function tpgb_mcp_btnpreset_permission( ?array $input = null ): bool {
	return current_user_can( 'edit_posts' );
}

// -------------------------------------------------------------------------
// HELPERS — store load / save.
// -------------------------------------------------------------------------

/**
 * Load `tpgb_global_options` and report which shape currently holds the
 * preset map. Returns:
 *   - `raw`        : decoded option (associative array, possibly empty)
 *   - `was_string` : whether the option was JSON-encoded on disk
 *   - `shape`      : 'active' (presets[active].buttonPresets) | 'flat' (buttonPresets) | 'empty'
 *   - `active`     : active key when shape='active', empty otherwise
 *   - `presets`    : current preset map (possibly empty)
 *
 * Matches the same fallback chain `Tpgb_Button_Preset_Vars::get_active_presets`
 * uses for reads, so write-then-read is consistent.
 */
function tpgb_mcp_btnpreset_load_store(): array {
	$raw        = get_option( 'tpgb_global_options', '' );
	$was_string = is_string( $raw ) && '' !== $raw;
	if ( $was_string ) {
		$dec = json_decode( $raw, true );
		if ( ! is_array( $dec ) ) {
			$dec = array();
		}
	} else {
		$dec = is_array( $raw ) ? $raw : array();
	}

	if ( isset( $dec['active'], $dec['presets'][ $dec['active'] ]['buttonPresets'] )
		&& is_array( $dec['presets'][ $dec['active'] ]['buttonPresets'] ) ) {
		return array(
			'raw'        => $dec,
			'was_string' => $was_string,
			'shape'      => 'active',
			'active'     => (string) $dec['active'],
			'presets'    => $dec['presets'][ $dec['active'] ]['buttonPresets'],
		);
	}

	if ( ! empty( $dec['buttonPresets'] ) && is_array( $dec['buttonPresets'] ) ) {
		return array(
			'raw'        => $dec,
			'was_string' => $was_string,
			'shape'      => 'flat',
			'active'     => '',
			'presets'    => $dec['buttonPresets'],
		);
	}

	return array(
		'raw'        => $dec,
		'was_string' => $was_string,
		'shape'      => 'empty',
		'active'     => '',
		'presets'    => array(),
	);
}

/**
 * Persist the updated preset map back into `tpgb_global_options`, writing
 * to whichever shape the store was in. The encoding (json string vs array)
 * is preserved so we don't surprise other readers that special-case one.
 *
 * The `update_option_tpgb_global_options` hook takes care of
 * `plus-button-presets.css` regeneration; we additionally clear the
 * in-process cache so any later read in this same request sees the new map.
 *
 * @param array $store   Result of {@see tpgb_mcp_btnpreset_load_store()}.
 * @param array $presets Updated preset map to persist.
 * @return bool Always true — failures bubble up via WP_Error in callers.
 */
function tpgb_mcp_btnpreset_save_store( array $store, array $presets ): bool {
	$dec = $store['raw'];

	if ( 'active' === $store['shape'] && '' !== $store['active'] ) {
		if ( ! isset( $dec['presets'] ) || ! is_array( $dec['presets'] ) ) {
			$dec['presets'] = array();
		}
		if ( ! isset( $dec['presets'][ $store['active'] ] ) || ! is_array( $dec['presets'][ $store['active'] ] ) ) {
			$dec['presets'][ $store['active'] ] = array();
		}
		$dec['presets'][ $store['active'] ]['buttonPresets'] = $presets;
	} else {
		$dec['buttonPresets'] = $presets;
	}

	$write = $store['was_string'] ? wp_json_encode( $dec ) : $dec;
	update_option( 'tpgb_global_options', $write );

	if ( class_exists( 'Tpgb_Button_Preset_Vars' ) ) {
		Tpgb_Button_Preset_Vars::invalidate_cache();
	}
	return true;
}

// -------------------------------------------------------------------------
// HELPERS — name validation & key generation.
// -------------------------------------------------------------------------

/**
 * Mirrors the JS rules from `handleSave()` in save-global-style.js so the
 * CRUD path can't accept a name the editor UI would reject. Returns the
 * sanitized name on success, WP_Error on failure.
 *
 * @param string      $name        Raw name from the request.
 * @param array       $presets     Existing preset map.
 * @param string|null $exclude_key Set when updating — the preset being
 *                                 renamed is excluded from the uniqueness
 *                                 check so it can keep its own name.
 */
function tpgb_mcp_btnpreset_validate_name( string $name, array $presets, ?string $exclude_key = null ) {
	$name = trim( $name );
	if ( '' === $name ) {
		return new WP_Error( 'invalid_name', __( 'Preset name is required.', 'the-plus-addons-for-block-editor' ) );
	}
	$len = function_exists( 'mb_strlen' ) ? mb_strlen( $name ) : strlen( $name );
	if ( $len < 2 || $len > 60 ) {
		return new WP_Error( 'invalid_name', __( 'Preset name must be between 2 and 60 characters.', 'the-plus-addons-for-block-editor' ) );
	}
	// Same character set the JS rejects in handleSave.
	if ( preg_match( '/[~`!@#\$%\^&\*\(\)\-\+\=\[\]\{\}\|\\\\:;"\'<>,\.\?\/_₹]/u', $name ) ) {
		return new WP_Error( 'invalid_name', __( 'Preset name should not contain special characters.', 'the-plus-addons-for-block-editor' ) );
	}
	foreach ( $presets as $key => $preset ) {
		if ( null !== $exclude_key && (string) $key === $exclude_key ) {
			continue;
		}
		$existing_name = is_array( $preset ) && isset( $preset['name'] ) ? (string) $preset['name'] : '';
		if ( '' !== $existing_name && 0 === strcasecmp( $existing_name, $name ) ) {
			return new WP_Error( 'name_exists', __( 'Preset name already exists.', 'the-plus-addons-for-block-editor' ) );
		}
	}
	return $name;
}

/**
 * Mirrors the JS pattern `'btnpreset_' + Date.now().toString().slice(-8)`.
 * On the unlikely chance of a collision (two creates inside one millisecond)
 * we bump until the key is free.
 *
 * @param array $presets Existing preset map keyed by preset key.
 * @return string A new unused preset key.
 */
function tpgb_mcp_btnpreset_generate_key( array $presets ): string {
	$base = (int) round( microtime( true ) * 1000 );
	for ( $i = 0; $i < 100; $i++ ) {
		$candidate = 'btnpreset_' . substr( (string) ( $base + $i ), -8 );
		if ( ! isset( $presets[ $candidate ] ) ) {
			return $candidate;
		}
	}
	// Pathological fallback — shouldn't be reachable in practice.
	return 'btnpreset_' . substr( bin2hex( random_bytes( 4 ) ), 0, 8 );
}

// -------------------------------------------------------------------------
// HELPERS — per-attribute shape builders.
// -------------------------------------------------------------------------

/**
 * The shape builders below produce the same attribute shapes that the
 * editor's `getChangedAttributes()` captures from a button block. Keeping
 * them in lockstep with the values stored by the JS picker is what lets
 * frontend rendering (Tpgb_Button_Preset_Vars::build_css) work without
 * any special-casing for MCP-created presets.
 *
 * @param array $v Raw spacing values {top,bottom,left,right,unit}.
 * @return array Spacing attribute structured for the preset.
 */
function tpgb_mcp_btnpreset_spacing( array $v ): array {
	return array(
		'md'   => array(
			'top'    => isset( $v['top'] ) ? sanitize_text_field( (string) $v['top'] ) : '0',
			'bottom' => isset( $v['bottom'] ) ? sanitize_text_field( (string) $v['bottom'] ) : '0',
			'left'   => isset( $v['left'] ) ? sanitize_text_field( (string) $v['left'] ) : '0',
			'right'  => isset( $v['right'] ) ? sanitize_text_field( (string) $v['right'] ) : '0',
			'unit'   => sanitize_text_field( (string) ( $v['unit'] ?? 'px' ) ),
		),
		'unit' => sanitize_text_field( (string) ( $v['unit'] ?? 'px' ) ),
	);
}

/**
 * Build a Nexter Blocks border-radius attribute from {top,bottom,left,right,unit}.
 *
 * @param array $r Raw radius values.
 * @return array Border-radius attribute structured for the preset.
 */
function tpgb_mcp_btnpreset_radius( array $r ): array {
	return array(
		'md'   => array(
			'top'    => isset( $r['top'] ) ? sanitize_text_field( (string) $r['top'] ) : '0',
			'bottom' => isset( $r['bottom'] ) ? sanitize_text_field( (string) $r['bottom'] ) : '0',
			'left'   => isset( $r['left'] ) ? sanitize_text_field( (string) $r['left'] ) : '0',
			'right'  => isset( $r['right'] ) ? sanitize_text_field( (string) $r['right'] ) : '0',
			'unit'   => sanitize_text_field( (string) ( $r['unit'] ?? 'px' ) ),
		),
		'unit' => sanitize_text_field( (string) ( $r['unit'] ?? 'px' ) ),
	);
}

/**
 * Build a Nexter Blocks border attribute from {type,color,width}.
 *
 * @param array $b Raw border values.
 * @return array Border attribute structured for the preset.
 */
function tpgb_mcp_btnpreset_border( array $b ): array {
	$w = is_array( $b['width'] ?? null ) ? $b['width'] : array();
	return array(
		'openBorder' => 1,
		'type'       => sanitize_text_field( (string) ( $b['type'] ?? 'solid' ) ),
		'color'      => sanitize_text_field( (string) ( $b['color'] ?? '' ) ),
		'width'      => array(
			'md'   => array(
				'top'    => sanitize_text_field( (string) ( $w['top'] ?? '1' ) ),
				'right'  => sanitize_text_field( (string) ( $w['right'] ?? '1' ) ),
				'bottom' => sanitize_text_field( (string) ( $w['bottom'] ?? '1' ) ),
				'left'   => sanitize_text_field( (string) ( $w['left'] ?? '1' ) ),
				'unit'   => sanitize_text_field( (string) ( $w['unit'] ?? 'px' ) ),
			),
			'unit' => sanitize_text_field( (string) ( $w['unit'] ?? 'px' ) ),
		),
	);
}

/**
 * Build a Nexter Blocks solid-colour background attribute.
 *
 * @param string $color Background colour value.
 * @return array Background attribute structured for the preset.
 */
function tpgb_mcp_btnpreset_bg( string $color ): array {
	return array(
		'openBg'         => 1,
		'bgType'         => 'color',
		'videoSource'    => 'local',
		'bgDefaultColor' => sanitize_text_field( $color ),
		'bgGradient'     => '',
		'isCustom'       => 'fpp',
	);
}

/**
 * Build a Nexter Blocks box-shadow attribute from typed input fields.
 *
 * @param array $input Ability input containing boxShadow* keys.
 * @return array Box-shadow attribute structured for the preset.
 */
function tpgb_mcp_btnpreset_box_shadow( array $input ): array {
	return array(
		'openShadow' => true,
		'typeShadow' => 'box-shadow',
		'inset'      => 0,
		'horizontal' => (string) intval( $input['boxShadowH'] ?? 0 ),
		'vertical'   => (string) intval( $input['boxShadowV'] ?? 4 ),
		'blur'       => (string) absint( $input['boxShadowBlur'] ?? 8 ),
		'spread'     => (string) intval( $input['boxShadowSpread'] ?? 0 ),
		'color'      => sanitize_text_field( (string) ( $input['boxShadowColor'] ?? 'rgba(0,0,0,0.40)' ) ),
	);
}

/**
 * Build a Nexter Blocks text-shadow attribute from typed input fields.
 *
 * @param array $input Ability input containing textShadow* keys.
 * @return array Text-shadow attribute structured for the preset.
 */
function tpgb_mcp_btnpreset_text_shadow( array $input ): array {
	return array(
		'openShadow' => true,
		'typeShadow' => 'text-shadow',
		'horizontal' => intval( $input['textShadowH'] ?? 2 ),
		'vertical'   => intval( $input['textShadowV'] ?? 3 ),
		'blur'       => absint( $input['textShadowBlur'] ?? 2 ),
		'color'      => sanitize_text_field( (string) ( $input['textShadowColor'] ?? 'rgba(0,0,0,0.5)' ) ),
	);
}

/**
 * Build a Nexter Blocks typography attribute from typed input fields.
 *
 * @param array $input Ability input containing typoSize, typoGlobalPreset, font fields.
 * @return array Typography attribute structured for the preset.
 */
function tpgb_mcp_btnpreset_typography( array $input ): array {
	$typo = array(
		'openTypography' => 1,
		'size'           => array(
			'md'   => ! empty( $input['typoSize'] ) ? (string) absint( $input['typoSize'] ) : '',
			'unit' => 'px',
		),
		'height'         => '',
		'spacing'        => '',
		'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
	);
	// Only emit globalTypo when a preset is set; an empty string makes the
	// CSS generator emit `var(--tpgb-T-font-family)` which is undefined.
	if ( ! empty( $input['typoGlobalPreset'] ) ) {
		$typo['globalTypo'] = sanitize_text_field( (string) $input['typoGlobalPreset'] );
	}
	return $typo;
}

/**
 * Build (or merge into) a preset attribute object from the typed inputs.
 * For updates, pass the existing preset as $base so unspecified fields are
 * preserved. The keys under $set track which fields were explicitly
 * supplied — anything not in $set is left alone.
 *
 * @param array $input Ability input arguments.
 * @param array $base  Optional existing preset to merge fields into.
 * @return array Updated preset attribute object.
 */
function tpgb_mcp_btnpreset_build_attrs( array $input, array $base = array() ): array {
	$out = $base;
	$has = static function ( array $in, string $key ): bool {
		return array_key_exists( $key, $in );
	};

	/* Scalars ---------------------------------------------------------- */
	if ( $has( $input, 'textColor' ) ) {
		$out['btColor'] = sanitize_text_field( (string) $input['textColor'] ); }
	if ( $has( $input, 'textHoverColor' ) ) {
		$out['bthColor'] = sanitize_text_field( (string) $input['textHoverColor'] ); }
	if ( $has( $input, 'borderHoverColor' ) ) {
		$out['bthBColor'] = sanitize_text_field( (string) $input['borderHoverColor'] ); }

	/* Backgrounds ------------------------------------------------------ */
	if ( $has( $input, 'bgColor' ) ) {
		$out['btBg'] = '' === $input['bgColor']
			? array( 'openBg' => 0 )
			: tpgb_mcp_btnpreset_bg( (string) $input['bgColor'] );
	}
	if ( $has( $input, 'bgHoverColor' ) ) {
		$out['bthBg'] = '' === $input['bgHoverColor']
			? array( 'openBg' => 0 )
			: tpgb_mcp_btnpreset_bg( (string) $input['bgHoverColor'] );
	}

	/* Border / radius / padding --------------------------------------- */
	if ( $has( $input, 'border' ) ) {
		$out['bBord'] = is_array( $input['border'] ) && ! empty( $input['border'] )
			? tpgb_mcp_btnpreset_border( $input['border'] )
			: array( 'openBorder' => 0 );
	}
	if ( $has( $input, 'borderRadius' ) && is_array( $input['borderRadius'] ) ) {
		$out['brad'] = tpgb_mcp_btnpreset_radius( $input['borderRadius'] );
	}
	if ( $has( $input, 'padding' ) && is_array( $input['padding'] ) ) {
		$out['btPad'] = tpgb_mcp_btnpreset_spacing( $input['padding'] );
	}

	/* Typography (toggle + numeric/global) ---------------------------- */
	if ( $has( $input, 'enableTypography' ) ) {
		$out['bTypo'] = ! empty( $input['enableTypography'] )
			? tpgb_mcp_btnpreset_typography( $input )
			: array( 'openTypography' => 0 );
	}

	/* Shadows --------------------------------------------------------- */
	if ( $has( $input, 'enableTextShadow' ) ) {
		$out['tShadow'] = ! empty( $input['enableTextShadow'] )
			? tpgb_mcp_btnpreset_text_shadow( $input )
			: array( 'openShadow' => false );
	}
	if ( $has( $input, 'enableBoxShadow' ) ) {
		$out['btshadow'] = ! empty( $input['enableBoxShadow'] )
			? tpgb_mcp_btnpreset_box_shadow( $input )
			: array( 'openShadow' => false );
	}

	/*
	 * Raw override pass — allows callers to set fields the typed schema
	 * doesn't expose (e.g. responsive sm/xs values, gradient backgrounds,
	 * globalBorder pointers). Applied last so it wins.
	 */
	if ( ! empty( $input['settings'] ) && is_array( $input['settings'] ) ) {
		$out = array_replace_recursive( $out, $input['settings'] );
	}

	return $out;
}

/**
 * Tiny visual summary used by the list ability — gives an MCP client just
 * enough to label / disambiguate presets without dumping the whole shape.
 *
 * @param array $preset Stored preset attribute object.
 * @return array Summary with textColor, bgColor and borderRadius.
 */
function tpgb_mcp_btnpreset_summarize( array $preset ): array {
	$bg      = is_array( $preset['btBg'] ?? null ) ? $preset['btBg'] : array();
	$brad    = is_array( $preset['brad'] ?? null ) ? $preset['brad'] : array();
	$brad_md = is_array( $brad['md'] ?? null ) ? $brad['md'] : array();
	return array(
		'textColor'    => isset( $preset['btColor'] ) ? (string) $preset['btColor'] : '',
		'bgColor'      => ! empty( $bg['openBg'] ) ? (string) ( $bg['bgDefaultColor'] ?? '' ) : '',
		'borderRadius' => isset( $brad_md['top'] ) ? (string) $brad_md['top'] : '',
	);
}

// -------------------------------------------------------------------------
// EXECUTE — list.
// -------------------------------------------------------------------------

/**
 * Execute callback for list-tpgb-button-presets.
 *
 * @param array $input Ability input arguments (unused).
 * @return array Count + summary rows for every saved preset.
 */
function tpgb_mcp_btnpreset_list_ability( array $input ) {
	$store = tpgb_mcp_btnpreset_load_store();
	$rows  = array();
	foreach ( $store['presets'] as $key => $preset ) {
		if ( ! is_array( $preset ) ) {
			continue;
		}
		$rows[] = array(
			'preset_key' => (string) $key,
			'name'       => isset( $preset['name'] ) ? (string) $preset['name'] : (string) $key,
			'summary'    => tpgb_mcp_btnpreset_summarize( $preset ),
		);
	}
	return array(
		'count'   => count( $rows ),
		'presets' => $rows,
	);
}

// -------------------------------------------------------------------------
// EXECUTE — get.
// -------------------------------------------------------------------------

/**
 * Execute callback for get-tpgb-button-preset.
 *
 * @param array $input Ability input arguments.
 * @return array|WP_Error Preset payload or error on failure.
 */
function tpgb_mcp_btnpreset_get_ability( array $input ) {
	$key = sanitize_text_field( (string) ( $input['preset_key'] ?? '' ) );
	if ( '' === $key ) {
		return new WP_Error( 'missing_params', __( 'preset_key is required.', 'the-plus-addons-for-block-editor' ) );
	}
	$store = tpgb_mcp_btnpreset_load_store();
	if ( ! isset( $store['presets'][ $key ] ) || ! is_array( $store['presets'][ $key ] ) ) {
		return new WP_Error(
			'preset_not_found',
			sprintf(
				/* translators: %s: button preset key. */
				__( 'Button preset "%s" was not found.', 'the-plus-addons-for-block-editor' ),
				$key
			)
		);
	}
	$preset = $store['presets'][ $key ];
	return array(
		'preset_key' => $key,
		'name'       => isset( $preset['name'] ) ? (string) $preset['name'] : $key,
		'preset'     => $preset,
	);
}

// -------------------------------------------------------------------------
// EXECUTE — create.
// -------------------------------------------------------------------------

/**
 * Execute callback for create-tpgb-button-preset.
 *
 * @param array $input Ability input arguments.
 * @return array|WP_Error New preset payload or error on failure.
 */
function tpgb_mcp_btnpreset_create_ability( array $input ) {
	$store = tpgb_mcp_btnpreset_load_store();

	$name = tpgb_mcp_btnpreset_validate_name( (string) ( $input['name'] ?? '' ), $store['presets'] );
	if ( is_wp_error( $name ) ) {
		return $name;
	}

	$key = tpgb_mcp_btnpreset_generate_key( $store['presets'] );

	// Build the visual-style block, then stamp identity. `key` and `name`
	// sit alongside the attrs because that's what the JS picker stores
	// (see save-global-style.js#handleSave) and what the global-button
	// helpers read when rendering option labels.
	$preset         = tpgb_mcp_btnpreset_build_attrs( $input );
	$preset['key']  = $key;
	$preset['name'] = $name;

	$store['presets'][ $key ] = $preset;
	tpgb_mcp_btnpreset_save_store( $store, $store['presets'] );

	return array(
		'preset_key' => $key,
		'name'       => $name,
		'preset'     => $preset,
	);
}

// -------------------------------------------------------------------------
// EXECUTE — update.
// -------------------------------------------------------------------------

/**
 * Execute callback for update-tpgb-button-preset.
 *
 * @param array $input Ability input arguments.
 * @return array|WP_Error Updated preset payload or error on failure.
 */
function tpgb_mcp_btnpreset_update_ability( array $input ) {
	$key = sanitize_text_field( (string) ( $input['preset_key'] ?? '' ) );
	if ( '' === $key ) {
		return new WP_Error( 'missing_params', __( 'preset_key is required.', 'the-plus-addons-for-block-editor' ) );
	}

	$store = tpgb_mcp_btnpreset_load_store();
	if ( ! isset( $store['presets'][ $key ] ) || ! is_array( $store['presets'][ $key ] ) ) {
		return new WP_Error(
			'preset_not_found',
			sprintf(
				/* translators: %s: button preset key. */
				__( 'Button preset "%s" was not found.', 'the-plus-addons-for-block-editor' ),
				$key
			)
		);
	}

	$preset = $store['presets'][ $key ];

	// Optional rename — validated against the rest of the store with this
	// key excluded so a no-op rename to the existing name passes through.
	if ( array_key_exists( 'name', $input ) ) {
		$new_name = tpgb_mcp_btnpreset_validate_name( (string) $input['name'], $store['presets'], $key );
		if ( is_wp_error( $new_name ) ) {
			return $new_name;
		}
		$preset['name'] = $new_name;
	}

	// Patch any visual fields the caller supplied — others are preserved.
	$preset = tpgb_mcp_btnpreset_build_attrs( $input, $preset );

	// `key` is treated as immutable here — keeping it stable means existing
	// button blocks that reference this preset don't break. To "rename a
	// key" the caller should create a new preset and delete the old one.
	$preset['key'] = $key;

	$store['presets'][ $key ] = $preset;
	tpgb_mcp_btnpreset_save_store( $store, $store['presets'] );

	return array(
		'preset_key' => $key,
		'name'       => isset( $preset['name'] ) ? (string) $preset['name'] : $key,
		'preset'     => $preset,
	);
}

// -------------------------------------------------------------------------
// EXECUTE — delete.
// -------------------------------------------------------------------------

/**
 * Execute callback for delete-tpgb-button-preset.
 *
 * @param array $input Ability input arguments.
 * @return array|WP_Error Delete confirmation or error on failure.
 */
function tpgb_mcp_btnpreset_delete_ability( array $input ) {
	$key = sanitize_text_field( (string) ( $input['preset_key'] ?? '' ) );
	if ( '' === $key ) {
		return new WP_Error( 'missing_params', __( 'preset_key is required.', 'the-plus-addons-for-block-editor' ) );
	}

	$store = tpgb_mcp_btnpreset_load_store();
	if ( ! isset( $store['presets'][ $key ] ) ) {
		return new WP_Error(
			'preset_not_found',
			sprintf(
				/* translators: %s: button preset key. */
				__( 'Button preset "%s" was not found.', 'the-plus-addons-for-block-editor' ),
				$key
			)
		);
	}

	unset( $store['presets'][ $key ] );
	tpgb_mcp_btnpreset_save_store( $store, $store['presets'] );

	return array(
		'preset_key' => $key,
		'deleted'    => true,
	);
}
