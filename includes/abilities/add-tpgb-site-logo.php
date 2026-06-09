<?php
/**
 * Ability: Add Nexter Blocks Site Logo (tpgb/tp-site-logo) to a Gutenberg page.
 *
 * @package The_Plus_Addons_For_Block_Editor
 * @since   1.3.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_register_ability(
	'nexter-blocks/add-tpgb-site-logo',
	array(
		'label'               => __( 'Add Nexter Blocks Site Logo', 'the-plus-addons-for-block-editor' ),
		'description'         => __( 'Adds the Nexter Blocks Site Logo block (tpgb/tp-site-logo) — dynamic site-wide logo with single or double (normal + hover) display modes, image or SVG source, sticky-header logo variant, width control per breakpoint, alignment, link target (home/custom/none), schema markup, and ARIA label. This is a dynamic block.', 'the-plus-addons-for-block-editor' ),
		'category'            => 'nexter-blocks',

		'input_schema'        => array(
			'type'                 => 'object',
			'properties'           => array(

				/* ── Core ─────────────────────────────────────────────────── */
				'post_id'            => array(
					'type'        => 'integer',
					'description' => 'WordPress page/post ID to insert the block into.',
				),
				'position'           => array(
					'type'        => 'integer',
					'default'     => -1,
					'description' => 'Zero-based insert position. Use -1 to append.',
				),
				'parent_block_id'    => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Optional parent block_id to nest this block inside a container.',
				),

				/* ── Mode ─────────────────────────────────────────────────── */
				'displayMode'        => array(
					'type'        => 'string',
					'enum'        => array( 'normal', 'double' ),
					'description' => 'normal = single logo; double = normal + hover swap logo.',
					'default'     => 'normal',
				),
				'logoType'           => array(
					'type'        => 'string',
					'enum'        => array( 'img', 'svg' ),
					'description' => 'Source type: img (media library image) or svg (inline SVG upload).',
					'default'     => 'img',
				),

				/* ── Normal/Main logo ─────────────────────────────────────── */
				'imageUrl'           => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'URL of the main logo image.',
				),
				'imageId'            => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Attachment ID of the main logo image.',
				),
				'imageSize'          => array(
					'type'        => 'string',
					'default'     => 'thumbnail',
					'description' => 'WP image size (thumbnail, medium, large, full, etc.).',
				),
				'svgUrl'             => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'URL of the SVG file (when logoType = svg).',
				),
				'svgId'              => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Attachment ID of the SVG file.',
				),
				'logoWidth'          => array(
					'type'        => 'integer',
					'default'     => 100,
					'description' => 'Main logo max-width in px.',
				),

				/* ── Hover logo (double mode) ─────────────────────────────── */
				'hoverImageUrl'      => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'URL of the hover logo image.',
				),
				'hoverImageId'       => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'hoverImageSize'     => array(
					'type'    => 'string',
					'default' => 'thumbnail',
				),
				'hoverSvgUrl'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'hoverSvgId'         => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'hoverLogoWidth'     => array(
					'type'        => 'integer',
					'default'     => 100,
					'description' => 'Hover logo max-width in px.',
				),
				'logoSpeed'          => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Hover transition duration in seconds (e.g. "0.3").',
				),

				/* ── Link ─────────────────────────────────────────────────── */
				'urlType'            => array(
					'type'        => 'string',
					'enum'        => array( 'home', 'custom', 'none' ),
					'description' => 'Link target: home (site home URL), custom (custom URL), or none (no link).',
					'default'     => 'home',
				),
				'customUrl'          => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Custom URL (when urlType = custom).',
				),
				'urlNewTab'          => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Open link in new tab.',
				),
				'urlNofollow'        => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Add rel="nofollow" to link.',
				),

				/* ── Alignment ────────────────────────────────────────────── */
				'alignment'          => array(
					'type'        => 'string',
					'enum'        => array( 'left', 'center', 'right', '' ),
					'description' => 'Horizontal alignment.',
					'default'     => 'left',
				),

				/* ── Sticky logo ──────────────────────────────────────────── */
				'stickyLogo'         => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Show a different logo on sticky headers.',
				),
				'stickyImageUrl'     => array(
					'type'    => 'string',
					'default' => '',
				),
				'stickyImageId'      => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'stickyImageSize'    => array(
					'type'    => 'string',
					'default' => 'thumbnail',
				),
				'stickySvgUrl'       => array(
					'type'    => 'string',
					'default' => '',
				),
				'stickySvgId'        => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'stickyWidth'        => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Sticky logo max-width in px.',
				),

				/* ── Accessibility / SEO ──────────────────────────────────── */
				'schemaMarkup'       => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Add Organization schema.org microdata.',
				),
				'ariaLabel'          => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'aria-label for the logo link.',
				),

				/* ── Visibility ───────────────────────────────────────────── */
				'hideDesktop'        => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'hideTablet'         => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'hideMobile'         => array(
					'type'    => 'boolean',
					'default' => false,
				),

				/* ── Identity ─────────────────────────────────────────────── */
				'globalClasses'      => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalId'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalCustomCss'    => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Layout ───────────────────────────────────────────────── */
				'globalWidth'        => array(
					'type'    => 'string',
					'enum'    => array( '', 'inline', 'full', 'custom' ),
					'default' => '',
				),
				'globalZindex'       => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalPosition'     => array(
					'type'    => 'string',
					'enum'    => array( '', 'relative', 'absolute', 'fixed', 'sticky' ),
					'default' => '',
				),

				/* ── Spacing / BG / Border / Shadow ───────────────────────── */
				'globalMargin'       => array( 'type' => 'object' ),
				'globalPadding'      => array( 'type' => 'object' ),
				'globalBgColor'      => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalBgHoverColor' => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalBorder'       => array( 'type' => 'object' ),
				'globalBorderHover'  => array( 'type' => 'object' ),
				'globalBRadius'      => array( 'type' => 'object' ),
				'globalBRadiusHover' => array( 'type' => 'object' ),
				'globalBShadow'      => array( 'type' => 'object' ),
				'globalBShadowHover' => array( 'type' => 'object' ),

				/* ── Scroll animation ─────────────────────────────────────── */
				'scrollAnimation'    => array(
					'type'    => 'string',
					'default' => '',
				),
				'animDuration'       => array(
					'type'    => 'string',
					'enum'    => array( '', 'slow', 'normal', 'fast' ),
					'default' => '',
				),
				'animDelay'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'animEasing'         => array(
					'type'    => 'string',
					'enum'    => array( '', 'linear', 'ease', 'ease-in', 'ease-out', 'ease-in-out' ),
					'default' => '',
				),

				'settings'           => array(
					'type'        => 'object',
					'description' => 'Raw attribute overrides merged directly into the block.',
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

		'execute_callback'    => 'tpgb_mcp_add_site_logo_ability',
		'permission_callback' => 'tpgb_mcp_add_site_logo_permission',
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
 * Permission callback for the add-site-logo ability.
 *
 * @param array|null $input Ability input arguments.
 * @return bool True when the current user may insert the block.
 */
function tpgb_mcp_add_site_logo_permission( ?array $input = null ): bool {
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
function tpgb_mcp_sl_spacing( array $v ): array {
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
function tpgb_mcp_sl_border( array $b ): array {
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
function tpgb_mcp_sl_radius( array $r ): array {
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
 * Build a Nexter Blocks box-shadow attribute.
 *
 * @param array $s Raw shadow values {horizontal,vertical,blur,spread,color,inset}.
 * @return array Box-shadow attribute structured for the block.
 */
function tpgb_mcp_sl_bshadow( array $s ): array {
	return array(
		'openShadow' => true,
		'inset'      => $s['inset'] ?? 0,
		'horizontal' => (string) intval( $s['horizontal'] ?? 0 ),
		'vertical'   => (string) intval( $s['vertical'] ?? 4 ),
		'blur'       => (string) absint( $s['blur'] ?? 8 ),
		'spread'     => (string) intval( $s['spread'] ?? 0 ),
		'color'      => sanitize_text_field( $s['color'] ?? 'rgba(0,0,0,0.40)' ),
	);
}
/**
 * Build a Nexter Blocks background-color attribute.
 *
 * @param string $color CSS color value.
 * @return array Background attribute structured for the block.
 */
function tpgb_mcp_sl_bg( string $color ): array {
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
 * Determine whether the block needs Nexter's wrapper rule for global styling.
 *
 * @param array $attrs Block attributes already gathered.
 * @return bool True if any wrapper-affecting attribute is present.
 */
function tpgb_mcp_sl_needs_wrapper( array $attrs ): bool {
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
 * Execute callback for the add-site-logo ability.
 *
 * @param array $input Ability input arguments.
 * @return array|WP_Error Result payload or WP_Error on failure.
 */
function tpgb_mcp_add_site_logo_ability( array $input ) {

	if ( ! defined( 'TPGB_VERSION' ) && ! defined( 'TPGBP_VERSION' ) ) {
		return new WP_Error( 'nexter_blocks_missing', __( 'Nexter Blocks must be active.', 'the-plus-addons-for-block-editor' ) );
	}

	$block_name = 'tpgb/tp-site-logo';
	if ( ! tpgb_mcp_has_registered_block( $block_name ) ) {
		return new WP_Error( 'block_missing', __( 'tpgb/tp-site-logo is not registered.', 'the-plus-addons-for-block-editor' ) );
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

	/* ------------------------------------------------------------------ */
	$block_id = tpgb_mcp_generate_block_id();
	$attrs    = array( 'block_id' => $block_id );

	/* ── Mode ─────────────────────────────────────────────────────────── */
	$mode = sanitize_key( $input['displayMode'] ?? 'normal' );
	if ( 'normal' !== $mode ) {
		$attrs['logoNmlDbl'] = $mode; }

	$type = sanitize_key( $input['logoType'] ?? 'img' );
	if ( 'img' !== $type ) {
		$attrs['logoType'] = $type; }

	/* ── Main image / svg ─────────────────────────────────────────────── */
	if ( ! empty( $input['imageUrl'] ) || ! empty( $input['imageId'] ) ) {
		$attrs['imageStore'] = array(
			'url' => esc_url_raw( $input['imageUrl'] ?? '' ),
			'id'  => absint( $input['imageId'] ?? 0 ),
		);
	}
	if ( ! empty( $input['imageSize'] ) && 'thumbnail' !== $input['imageSize'] ) {
		$attrs['imageSize'] = sanitize_text_field( $input['imageSize'] );
	}
	if ( ! empty( $input['svgUrl'] ) || ! empty( $input['svgId'] ) ) {
		$attrs['svgStore'] = array(
			'url' => esc_url_raw( $input['svgUrl'] ?? '' ),
			'id'  => absint( $input['svgId'] ?? 0 ),
		);
	}
	if ( isset( $input['logoWidth'] ) && 100 !== intval( $input['logoWidth'] ) ) {
		$attrs['logoWidth'] = array(
			'md'   => (string) absint( $input['logoWidth'] ),
			'unit' => 'px',
		);
	}

	/* ── Hover image / svg ────────────────────────────────────────────── */
	if ( ! empty( $input['hoverImageUrl'] ) || ! empty( $input['hoverImageId'] ) ) {
		$attrs['hvrImageStore'] = array(
			'url' => esc_url_raw( $input['hoverImageUrl'] ?? '' ),
			'id'  => absint( $input['hoverImageId'] ?? 0 ),
		);
	}
	if ( ! empty( $input['hoverImageSize'] ) && 'thumbnail' !== $input['hoverImageSize'] ) {
		$attrs['hvrImageSize'] = sanitize_text_field( $input['hoverImageSize'] );
	}
	if ( ! empty( $input['hoverSvgUrl'] ) || ! empty( $input['hoverSvgId'] ) ) {
		$attrs['hvrSvgStore'] = array(
			'url' => esc_url_raw( $input['hoverSvgUrl'] ?? '' ),
			'id'  => absint( $input['hoverSvgId'] ?? 0 ),
		);
	}
	if ( isset( $input['hoverLogoWidth'] ) && 100 !== intval( $input['hoverLogoWidth'] ) ) {
		$attrs['hvrLogoWidth'] = array(
			'md'   => (string) absint( $input['hoverLogoWidth'] ),
			'unit' => 'px',
		);
	}
	if ( ! empty( $input['logoSpeed'] ) ) {
		$attrs['logoSpeed'] = sanitize_text_field( $input['logoSpeed'] ); }

	/* ── Link ─────────────────────────────────────────────────────────── */
	$url_type = sanitize_key( $input['urlType'] ?? 'home' );
	if ( 'home' !== $url_type ) {
		$attrs['urlType'] = $url_type; }
	if ( 'custom' === $url_type || ! empty( $input['customUrl'] ) ) {
		$attrs['customURL'] = array(
			'url'      => esc_url_raw( $input['customUrl'] ?? '#' ),
			'target'   => ! empty( $input['urlNewTab'] ) ? '_blank' : '',
			'nofollow' => ! empty( $input['urlNofollow'] ) ? 'nofollow' : '',
		);
	}

	/* ── Alignment ────────────────────────────────────────────────────── */
	if ( ! empty( $input['alignment'] ) && 'left' !== $input['alignment'] ) {
		$attrs['Alignment'] = array(
			'md' => sanitize_text_field( $input['alignment'] ),
			'sm' => '',
			'xs' => '',
		);
	}

	/* ── Sticky ───────────────────────────────────────────────────────── */
	if ( ! empty( $input['stickyLogo'] ) ) {
		$attrs['stickyLogo'] = true;
		if ( ! empty( $input['stickyImageUrl'] ) || ! empty( $input['stickyImageId'] ) ) {
			$attrs['stickyImg'] = array(
				'url' => esc_url_raw( $input['stickyImageUrl'] ?? '' ),
				'id'  => absint( $input['stickyImageId'] ?? 0 ),
			);
		}
		if ( ! empty( $input['stickyImageSize'] ) && 'thumbnail' !== $input['stickyImageSize'] ) {
			$attrs['sImgSize'] = sanitize_text_field( $input['stickyImageSize'] );
		}
		if ( ! empty( $input['stickySvgUrl'] ) || ! empty( $input['stickySvgId'] ) ) {
			$attrs['stickySvg'] = array(
				'url' => esc_url_raw( $input['stickySvgUrl'] ?? '' ),
				'id'  => absint( $input['stickySvgId'] ?? 0 ),
			);
		}
		if ( ! empty( $input['stickyWidth'] ) ) {
			$attrs['stickyWidth'] = array(
				'md'   => (string) absint( $input['stickyWidth'] ),
				'unit' => 'px',
			);
		}
	}

	/* ── SEO / Accessibility ──────────────────────────────────────────── */
	if ( ! empty( $input['schemaMarkup'] ) ) {
		$attrs['markupSch'] = true; }
	if ( ! empty( $input['ariaLabel'] ) ) {
		$attrs['ariaLabel'] = sanitize_text_field( $input['ariaLabel'] ); }

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

	/* ── Spacing / BG / Border / Shadow ───────────────────────────────── */
	if ( ! empty( $input['globalMargin'] ) ) {
		$attrs['globalMargin'] = tpgb_mcp_sl_spacing( $input['globalMargin'] ); }
	if ( ! empty( $input['globalPadding'] ) ) {
		$attrs['globalPadding'] = tpgb_mcp_sl_spacing( $input['globalPadding'] ); }
	if ( ! empty( $input['globalBgColor'] ) ) {
		$attrs['globalBg'] = tpgb_mcp_sl_bg( $input['globalBgColor'] ); }
	if ( ! empty( $input['globalBgHoverColor'] ) ) {
		$attrs['globalBgHover'] = tpgb_mcp_sl_bg( $input['globalBgHoverColor'] ); }
	if ( ! empty( $input['globalBorder'] ) ) {
		$attrs['globalBorder'] = tpgb_mcp_sl_border( $input['globalBorder'] ); }
	if ( ! empty( $input['globalBorderHover'] ) ) {
		$attrs['globalBorderHover'] = tpgb_mcp_sl_border( $input['globalBorderHover'] ); }
	if ( ! empty( $input['globalBRadius'] ) ) {
		$attrs['globalBRadius'] = tpgb_mcp_sl_radius( $input['globalBRadius'] ); }
	if ( ! empty( $input['globalBRadiusHover'] ) ) {
		$attrs['globalBRadiusHover'] = tpgb_mcp_sl_radius( $input['globalBRadiusHover'] ); }
	if ( ! empty( $input['globalBShadow'] ) ) {
		$attrs['globalBShadow'] = tpgb_mcp_sl_bshadow( $input['globalBShadow'] ); }
	if ( ! empty( $input['globalBShadowHover'] ) ) {
		$attrs['globalBShadowHover'] = tpgb_mcp_sl_bshadow( $input['globalBShadowHover'] ); }

	/* ── Scroll animation ─────────────────────────────────────────────── */
	if ( ! empty( $input['scrollAnimation'] ) ) {
		$attrs['globalAnim'] = array( 'md' => sanitize_text_field( $input['scrollAnimation'] ) ); }
	if ( ! empty( $input['animDuration'] ) ) {
		$attrs['globalAnimDuration'] = sanitize_text_field( $input['animDuration'] ); }
	if ( ! empty( $input['animDelay'] ) ) {
		$attrs['globalAnimDelay'] = array( 'md' => sanitize_text_field( $input['animDelay'] ) ); }
	if ( ! empty( $input['animEasing'] ) ) {
		$attrs['globalAnimEasing'] = sanitize_text_field( $input['animEasing'] ); }

	/* ── Wrapper flag ─────────────────────────────────────────────────── */
	if ( tpgb_mcp_sl_needs_wrapper( $attrs ) ) {
		$attrs['tpgbDisrule'] = true; }

	/* ── Raw override ─────────────────────────────────────────────────── */
	$attrs = tpgb_mcp_merge_block_settings( $attrs, $input['settings'] ?? array() );

	/* ── Build / insert / save (dynamic) ──────────────────────────────── */
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
