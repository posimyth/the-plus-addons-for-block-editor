<?php
/**
 * Ability: Add Nexter Blocks Post Title (tpgb/tp-post-title) to a Gutenberg page.
 *
 * @package The_Plus_Addons_For_Block_Editor
 * @since   1.3.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_register_ability(
	'nexter-blocks/add-tpgb-post-title',
	array(
		'label'               => __( 'Add Nexter Blocks Post Title', 'the-plus-addons-for-block-editor' ),
		'description'         => __( 'Adds the Nexter Blocks Post Title block (tpgb/tp-post-title) — displays the current post title with optional prefix/suffix, link wrapper, custom HTML tag, character/word limit, and comprehensive styling for title and prefix/suffix text. Use in post templates or at the top of articles. This is a dynamic block.', 'the-plus-addons-for-block-editor' ),
		'category'            => 'nexter-blocks',

		'input_schema'        => array(
			'type'                 => 'object',
			'properties'           => array(

				/* ── Core ─────────────────────────────────────────────────── */
				'post_id'                => array( 'type' => 'integer' ),
				'position'               => array(
					'type'    => 'integer',
					'default' => -1,
				),
				'parent_block_id'        => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Source & Content ─────────────────────────────────────── */
				'sourceType'             => array(
					'type'    => 'string',
					'enum'    => array( 'singular', 'archive' ),
					'default' => 'singular',
				),
				'titlePrefix'            => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Text before title.',
				),
				'titleSuffix'            => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Text after title.',
				),
				'postLink'               => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Link title to the post permalink.',
				),
				'titleTag'               => array(
					'type'    => 'string',
					'enum'    => array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p' ),
					'default' => 'h1',
				),

				/* ── Limit ────────────────────────────────────────────────── */
				'limitType'              => array(
					'type'    => 'string',
					'enum'    => array( 'default', 'char', 'word' ),
					'default' => 'default',
				),
				'limitCount'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'hideDots'               => array(
					'type'    => 'boolean',
					'default' => false,
				),

				/* ── Alignment ────────────────────────────────────────────── */
				'alignment'              => array(
					'type'    => 'string',
					'enum'    => array( 'left', 'center', 'right' ),
					'default' => 'center',
				),

				/* ── Title styling ────────────────────────────────────────── */
				'enableTitleTypo'        => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'titleTypoSize'          => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'titleColor'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'titleHoverColor'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'titlePadding'           => array( 'type' => 'object' ),
				'titleBgColor'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'titleBgHoverColor'      => array(
					'type'    => 'string',
					'default' => '',
				),
				'titleBorder'            => array( 'type' => 'object' ),
				'titleBorderHover'       => array( 'type' => 'object' ),
				'titleBorderRadius'      => array( 'type' => 'object' ),
				'titleBorderRadiusHover' => array( 'type' => 'object' ),
				'enableTitleShadow'      => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'titleShadowColor'       => array(
					'type'    => 'string',
					'default' => 'rgba(0,0,0,0.40)',
				),

				/* ── Prefix/Suffix styling ────────────────────────────────── */
				'prePostPadding'         => array( 'type' => 'object' ),
				'prefixOffset'           => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Prefix horizontal offset in px.',
				),
				'suffixOffset'           => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Suffix horizontal offset in px.',
				),
				'enablePrePostTypo'      => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'prePostTypoSize'        => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'prePostColor'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'prePostBgColor'         => array(
					'type'    => 'string',
					'default' => '',
				),
				'prePostBorder'          => array( 'type' => 'object' ),
				'prePostBorderRadius'    => array( 'type' => 'object' ),
				'enablePrePostShadow'    => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'prePostShadowColor'     => array(
					'type'    => 'string',
					'default' => 'rgba(0,0,0,0.40)',
				),

				/* ── Scroll animation ────────────────────────────────────── */
				'scrollAnimation'        => array(
					'type'    => 'string',
					'enum'    => array(
						'',
						'fadeIn',
						'fadeInUp',
						'fadeInDown',
						'fadeInLeft',
						'fadeInRight',
						'zoomIn',
						'zoomInUp',
						'zoomInDown',
						'slideInUp',
						'slideInDown',
						'slideInLeft',
						'slideInRight',
						'bounceIn',
						'rotateIn',
						'flipInX',
						'flipInY',
					),
					'default' => '',
				),
				'animDuration'           => array(
					'type'    => 'string',
					'enum'    => array( '', 'slow', 'normal', 'fast' ),
					'default' => '',
				),
				'animDelay'              => array(
					'type'    => 'string',
					'default' => '',
				),
				'animEasing'             => array(
					'type'    => 'string',
					'enum'    => array( '', 'linear', 'ease', 'ease-in', 'ease-out', 'ease-in-out' ),
					'default' => '',
				),

				/* ── Advanced ─────────────────────────────────────────────── */
				'hideDesktop'            => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'hideTablet'             => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'hideMobile'             => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'globalClasses'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalId'               => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalCustomCss'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalWidth'            => array(
					'type'    => 'string',
					'enum'    => array( '', 'inline', 'full', 'custom' ),
					'default' => '',
				),
				'globalZindex'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalPosition'         => array(
					'type'    => 'string',
					'enum'    => array( '', 'relative', 'absolute', 'fixed', 'sticky' ),
					'default' => '',
				),
				'globalMargin'           => array( 'type' => 'object' ),
				'globalPadding'          => array( 'type' => 'object' ),

				'settings'               => array( 'type' => 'object' ),
				'fontFamily'             => array(
					'type'        => 'string',
					'description' => 'Font family name (e.g. "Inter", "Roboto", "Playfair Display"). When inspecting a URL via nexter-blocks/inspect-page, pass the returned fonts[].family value verbatim.',
					'default'     => '',
				),
				'fontType'               => array(
					'type'        => 'string',
					'description' => 'Font category — "sans-serif", "serif", "display", "handwriting", or "monospace".',
					'default'     => '',
				),
				'customFont'             => array(
					'type'        => 'string',
					'description' => 'Custom (non-Google) font name. Overrides fontFamily.',
					'default'     => '',
				),
				'fontWeight'             => array(
					'type'        => 'string',
					'description' => 'Font weight as a string ("100"..."900"). Embedded inside every typography group\'s fontFamily.fontWeight on this block. For per-group control, use the settings raw override or sprout/update-element.',
					'default'     => '',
				),
				'textDecoration'         => array(
					'type'        => 'string',
					'enum'        => array( '', 'none', 'underline', 'overline', 'line-through' ),
					'description' => 'Text decoration applied to every typography group on this block. Stamped as a sibling of fontFamily inside each typo array. For per-group control, use the settings raw override or sprout/update-element.',
					'default'     => '',
				),
			),
			'required'             => array( 'post_id' ),
			'additionalProperties' => false,
		),

		'output_schema'       => array(
			'type'       => 'object',
			'properties' => array(
				'block_id'   => array( 'type' => 'string' ),
				'block_name' => array( 'type' => 'string' ),
				'post_id'    => array( 'type' => 'integer' ),
			),
		),

		'execute_callback'    => 'tpgb_mcp_add_post_title_ability',
		'permission_callback' => 'tpgb_mcp_add_post_title_permission',
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
// PERMISSION / HELPERS
// -------------------------------------------------------------------------

/**
 * Permission callback for the add-post-title ability.
 *
 * @param array|null $input Ability input arguments.
 * @return bool True when the current user may insert the block.
 */
function tpgb_mcp_add_post_title_permission( ?array $input = null ): bool {
	if ( ! current_user_can( 'edit_posts' ) ) {
		return false; }
	$post_id = absint( $input['post_id'] ?? 0 );
	if ( $post_id > 0 && ! current_user_can( 'edit_post', $post_id ) ) {
		return false; }
	return true;
}

/**
 * Build a Nexter Blocks spacing attribute from {top,bottom,left,right,unit}.
 *
 * @param array $v Raw spacing values.
 * @return array Spacing attribute structured for the block.
 */
function tpgb_mcp_ptitle_spacing( array $v ): array {
	return array(
		'md'   => array(
			'top'    => sanitize_text_field( $v['top'] ?? '0' ),
			'bottom' => sanitize_text_field( $v['bottom'] ?? '0' ),
			'left'   => sanitize_text_field( $v['left'] ?? '0' ),
			'right'  => sanitize_text_field( $v['right'] ?? '0' ),
			'unit'   => sanitize_text_field( $v['unit'] ?? 'px' ),
		),
		'unit' => sanitize_text_field( $v['unit'] ?? 'px' ),
	);
}
/**
 * Build a Nexter Blocks border attribute from {type,color,width}.
 *
 * @param array $b Raw border values.
 * @return array Border attribute structured for the block.
 */
function tpgb_mcp_ptitle_border( array $b ): array {
	$w = $b['width'] ?? array();
	return array(
		'openBorder' => 1,
		'type'       => sanitize_text_field( $b['type'] ?? 'solid' ),
		'color'      => sanitize_text_field( $b['color'] ?? '' ),
		'width'      => array(
			'md'   => array(
				'top'    => sanitize_text_field( $w['top'] ?? '1' ),
				'right'  => sanitize_text_field( $w['right'] ?? '1' ),
				'bottom' => sanitize_text_field( $w['bottom'] ?? '1' ),
				'left'   => sanitize_text_field( $w['left'] ?? '1' ),
				'unit'   => sanitize_text_field( $w['unit'] ?? 'px' ),
			),
			'unit' => sanitize_text_field( $w['unit'] ?? 'px' ),
		),
	);
}
/**
 * Build a Nexter Blocks border-radius attribute from {top,bottom,left,right,unit}.
 *
 * @param array $r Raw radius values.
 * @return array Border-radius attribute structured for the block.
 */
function tpgb_mcp_ptitle_radius( array $r ): array {
	return array(
		'md'   => array(
			'top'    => sanitize_text_field( $r['top'] ?? '0' ),
			'bottom' => sanitize_text_field( $r['bottom'] ?? '0' ),
			'left'   => sanitize_text_field( $r['left'] ?? '0' ),
			'right'  => sanitize_text_field( $r['right'] ?? '0' ),
			'unit'   => sanitize_text_field( $r['unit'] ?? 'px' ),
		),
		'unit' => sanitize_text_field( $r['unit'] ?? 'px' ),
	);
}
/**
 * Build a Nexter Blocks solid-colour background attribute.
 *
 * @param string $color Background colour value.
 * @return array Background attribute structured for the block.
 */
function tpgb_mcp_ptitle_bg( string $color ): array {
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
 * Determine whether any wrapper-level (global/transform) attributes are set.
 *
 * @param array $attrs Block attributes built so far.
 * @return bool True when the block needs the tpgbDisrule wrapper enabled.
 */
function tpgb_mcp_ptitle_needs_wrapper( array $attrs ): bool {
	$keys = array(
		'globalMargin',
		'globalPadding',
		'globalBg',
		'globalBgHover',
		'globalBorder',
		'globalBorderHover',
		'globalBRadius',
		'globalBRadiusHover',
		'globalBShadow',
		'globalBShadowHover',
		'globalAnim',
		'globalWidth',
		'globalZindex',
		'globalPosition',
		'globalClasses',
		'globalId',
		'globalCustomCss',
		'globalHideDesktop',
		'globalHideTablet',
		'globalHideMobile',
		'tpgbDisrule',
	);
	foreach ( $keys as $k ) {
		if ( ! empty( $attrs[ $k ] ) ) {
			return true;
		}
	}
	return false;
}

// -------------------------------------------------------------------------
// EXECUTE CALLBACK
// -------------------------------------------------------------------------

/**
 * Execute callback: insert a tpgb/tp-post-title block into a post.
 *
 * @param array $input Ability input arguments.
 * @return array|WP_Error Ability result or error on failure.
 */
function tpgb_mcp_add_post_title_ability( array $input ) {

	if ( ! defined( 'TPGB_VERSION' ) && ! defined( 'TPGBP_VERSION' ) ) {
		return new WP_Error( 'nexter_blocks_missing', __( 'Nexter Blocks must be active.', 'the-plus-addons-for-block-editor' ) );
	}

	$block_name = 'tpgb/tp-post-title';
	if ( ! tpgb_mcp_has_registered_block( $block_name ) ) {
		return new WP_Error( 'block_missing', __( 'tpgb/tp-post-title is not registered.', 'the-plus-addons-for-block-editor' ) );
	}

	$post_id  = absint( $input['post_id'] ?? 0 );
	$position = intval( $input['position'] ?? -1 );
	if ( $post_id <= 0 ) {
		return new WP_Error( 'missing_params', __( 'post_id is required.', 'the-plus-addons-for-block-editor' ) ); }

	$post = get_post( $post_id );
	if ( ! $post instanceof WP_Post ) {
		return new WP_Error( 'invalid_post', __( 'Target post not found.', 'the-plus-addons-for-block-editor' ) ); }

	$blocks = tpgb_mcp_get_blocks( $post_id );
	if ( is_wp_error( $blocks ) ) {
		return $blocks; }

	// ---------------------------------------------------------------------
	// Build attributes.
	// ---------------------------------------------------------------------
	$block_id = tpgb_mcp_generate_block_id();
	$attrs    = array( 'block_id' => $block_id );

	/* ── Source & Content ─────────────────────────────────────────────── */
	if ( ! empty( $input['sourceType'] ) && 'singular' !== $input['sourceType'] ) {
		$attrs['types'] = sanitize_key( $input['sourceType'] ); }
	if ( ! empty( $input['titlePrefix'] ) ) {
		$attrs['titlePrefix'] = sanitize_text_field( $input['titlePrefix'] ); }
	if ( ! empty( $input['titleSuffix'] ) ) {
		$attrs['titlePostfix'] = sanitize_text_field( $input['titleSuffix'] ); }
	if ( ! empty( $input['postLink'] ) ) {
		$attrs['postLink'] = true; }
	if ( ! empty( $input['titleTag'] ) && 'h1' !== $input['titleTag'] ) {
		$attrs['titleTag'] = sanitize_key( $input['titleTag'] ); }

	/* ── Limit ────────────────────────────────────────────────────────── */
	if ( ! empty( $input['limitType'] ) && 'default' !== $input['limitType'] ) {
		$attrs['limitCountType'] = sanitize_key( $input['limitType'] ); }
	if ( ! empty( $input['limitCount'] ) ) {
		$attrs['titleLimit'] = sanitize_text_field( $input['limitCount'] ); }
	if ( ! empty( $input['hideDots'] ) ) {
		$attrs['hideDots'] = true; }

	/* ── Alignment ────────────────────────────────────────────────────── */
	if ( ! empty( $input['alignment'] ) && 'center' !== $input['alignment'] ) {
		$attrs['titleAlign'] = array(
			'md' => sanitize_key( $input['alignment'] ),
			'sm' => '',
			'xs' => '',
		);
	}

	/* ── Title styling ────────────────────────────────────────────────── */
	if ( ! empty( $input['enableTitleTypo'] ) ) {
		$attrs['titleTypo'] = array(
			'openTypography' => 1,
			'size'           => array(
				'md'   => ! empty( $input['titleTypoSize'] ) ? (string) absint( $input['titleTypoSize'] ) : '',
				'unit' => 'px',
			),
			'height'         => '',
			'spacing'        => '',
			'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
		);
	}
	if ( ! empty( $input['titleColor'] ) ) {
		$attrs['titleColor'] = sanitize_text_field( $input['titleColor'] ); }
	if ( ! empty( $input['titleHoverColor'] ) ) {
		$attrs['titleHvrColor'] = sanitize_text_field( $input['titleHoverColor'] ); }
	if ( ! empty( $input['titlePadding'] ) ) {
		$attrs['padding'] = tpgb_mcp_ptitle_spacing( $input['titlePadding'] ); }
	if ( ! empty( $input['titleBgColor'] ) ) {
		$attrs['titleBg'] = tpgb_mcp_ptitle_bg( $input['titleBgColor'] ); }
	if ( ! empty( $input['titleBgHoverColor'] ) ) {
		$attrs['titleHvrbg'] = tpgb_mcp_ptitle_bg( $input['titleBgHoverColor'] ); }
	if ( ! empty( $input['titleBorder'] ) ) {
		$attrs['titleBorder'] = tpgb_mcp_ptitle_border( $input['titleBorder'] ); }
	if ( ! empty( $input['titleBorderHover'] ) ) {
		$attrs['titleHvrBorder'] = tpgb_mcp_ptitle_border( $input['titleBorderHover'] ); }
	if ( ! empty( $input['titleBorderRadius'] ) ) {
		$attrs['titleBRadius'] = tpgb_mcp_ptitle_radius( $input['titleBorderRadius'] ); }
	if ( ! empty( $input['titleBorderRadiusHover'] ) ) {
		$attrs['titleHvrBra'] = tpgb_mcp_ptitle_radius( $input['titleBorderRadiusHover'] ); }
	if ( ! empty( $input['enableTitleShadow'] ) ) {
		$attrs['titleBshadow'] = array(
			'openShadow' => true,
			'inset'      => 0,
			'horizontal' => '0',
			'vertical'   => '4',
			'blur'       => '8',
			'color'      => sanitize_text_field( $input['titleShadowColor'] ?? 'rgba(0,0,0,0.40)' ),
		);
	}

	/* ── Prefix/Suffix styling ────────────────────────────────────────── */
	if ( ! empty( $input['prePostPadding'] ) ) {
		$attrs['prePostPadding'] = tpgb_mcp_ptitle_spacing( $input['prePostPadding'] ); }
	if ( ! empty( $input['prefixOffset'] ) ) {
		$attrs['prefixOffset'] = array(
			'md'   => (string) intval( $input['prefixOffset'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['suffixOffset'] ) ) {
		$attrs['postfixOffset'] = array(
			'md'   => (string) intval( $input['suffixOffset'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['enablePrePostTypo'] ) ) {
		$attrs['prePostTypo'] = array(
			'openTypography' => 1,
			'size'           => array(
				'md'   => ! empty( $input['prePostTypoSize'] ) ? (string) absint( $input['prePostTypoSize'] ) : '',
				'unit' => 'px',
			),
			'height'         => '',
			'spacing'        => '',
			'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
		);
	}
	if ( ! empty( $input['prePostColor'] ) ) {
		$attrs['prePostColor'] = sanitize_text_field( $input['prePostColor'] ); }
	if ( ! empty( $input['prePostBgColor'] ) ) {
		$attrs['prePostBg'] = tpgb_mcp_ptitle_bg( $input['prePostBgColor'] ); }
	if ( ! empty( $input['prePostBorder'] ) ) {
		$attrs['prePostBorder'] = tpgb_mcp_ptitle_border( $input['prePostBorder'] ); }
	if ( ! empty( $input['prePostBorderRadius'] ) ) {
		$attrs['prePostBRadius'] = tpgb_mcp_ptitle_radius( $input['prePostBorderRadius'] ); }
	if ( ! empty( $input['enablePrePostShadow'] ) ) {
		$attrs['prePostBshadow'] = array(
			'openShadow' => true,
			'inset'      => 0,
			'horizontal' => '0',
			'vertical'   => '4',
			'blur'       => '8',
			'color'      => sanitize_text_field( $input['prePostShadowColor'] ?? 'rgba(0,0,0,0.40)' ),
		);
	}

	/* ── Scroll animation ─────────────────────────────────────────────── */
	if ( ! empty( $input['scrollAnimation'] ) ) {
		$attrs['globalAnim'] = array( 'md' => sanitize_text_field( $input['scrollAnimation'] ) ); }
	if ( ! empty( $input['animDuration'] ) ) {
		$attrs['globalAnimDuration'] = sanitize_text_field( $input['animDuration'] ); }
	if ( ! empty( $input['animDelay'] ) ) {
		$attrs['globalAnimDelay'] = array( 'md' => sanitize_text_field( $input['animDelay'] ) ); }
	if ( ! empty( $input['animEasing'] ) ) {
		$attrs['globalAnimEasing'] = sanitize_text_field( $input['animEasing'] ); }

	/* ── Visibility ───────────────────────────────────────────────────── */
	if ( ! empty( $input['hideDesktop'] ) ) {
		$attrs['globalHideDesktop'] = true; }
	if ( ! empty( $input['hideTablet'] ) ) {
		$attrs['globalHideTablet'] = true; }
	if ( ! empty( $input['hideMobile'] ) ) {
		$attrs['globalHideMobile'] = true; }

	/* ── Identity ─────────────────────────────────────────────────────── */
	if ( ! empty( $input['globalClasses'] ) ) {
		$attrs['globalClasses'] = sanitize_text_field( $input['globalClasses'] ); }
	if ( ! empty( $input['globalId'] ) ) {
		$attrs['globalId'] = sanitize_text_field( $input['globalId'] ); }
	if ( ! empty( $input['globalCustomCss'] ) ) {
		$attrs['globalCustomCss'] = wp_strip_all_tags( $input['globalCustomCss'] ); }

	/* ── Layout ───────────────────────────────────────────────────────── */
	if ( ! empty( $input['globalWidth'] ) ) {
		$attrs['globalWidth'] = sanitize_text_field( $input['globalWidth'] ); }
	if ( ! empty( $input['globalZindex'] ) ) {
		$attrs['globalZindex'] = sanitize_text_field( $input['globalZindex'] ); }
	if ( ! empty( $input['globalPosition'] ) ) {
		$attrs['globalPosition'] = array(
			'md' => sanitize_text_field( $input['globalPosition'] ),
			'sm' => '',
			'xs' => '',
		); }

	if ( ! empty( $input['globalMargin'] ) ) {
		$attrs['globalMargin'] = tpgb_mcp_ptitle_spacing( $input['globalMargin'] );  }
	if ( ! empty( $input['globalPadding'] ) ) {
		$attrs['globalPadding'] = tpgb_mcp_ptitle_spacing( $input['globalPadding'] ); }

	/* ── tpgbDisrule ──────────────────────────────────────────────────── */
	if ( tpgb_mcp_ptitle_needs_wrapper( $attrs ) ) {
		$attrs['tpgbDisrule'] = true; }

	/* ── Raw settings override ────────────────────────────────────────── */
	tpgb_mcp_apply_typo_decoration( $attrs, $input );
	$attrs = tpgb_mcp_merge_block_settings( $attrs, $input['settings'] ?? array() );

	/* ── Build, insert, save ──────────────────────────────────────────── */
	$block = tpgb_mcp_build_block( $block_name, $attrs );

	$parent_id = ! empty( $input['parent_block_id'] ) ? sanitize_text_field( $input['parent_block_id'] ) : '';
	if ( '' !== $parent_id ) {
		if ( ! tpgb_mcp_insert_inner_block( $blocks, $parent_id, $block, $position ) ) {
			return new WP_Error( 'parent_not_found', __( 'Parent block not found.', 'the-plus-addons-for-block-editor' ) );
		}
	} else {
		tpgb_mcp_insert_block( $blocks, $block, $position );
	}

	$save_result = tpgb_mcp_save_blocks( $post_id, $blocks );
	if ( is_wp_error( $save_result ) ) {
		return $save_result; }

	return array(
		'block_id'   => $block_id,
		'block_name' => $block_name,
		'post_id'    => $post_id,
	);
}
