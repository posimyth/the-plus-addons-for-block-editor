<?php
/**
 * Ability: Add Nexter Blocks Draw SVG (tpgb/tp-draw-svg) to a Gutenberg page.
 *
 * @package The_Plus_Addons_For_Block_Editor
 * @since   1.3.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_register_ability(
	'nexter-blocks/add-tpgb-draw-svg',
	array(
		'label'               => __( 'Add Nexter Blocks Draw SVG', 'the-plus-addons-for-block-editor' ),
		'description'         => __( 'Adds the Nexter Blocks Draw SVG block (tpgb/tp-draw-svg) — an animated SVG drawing block with pre-built icons or custom SVG upload, stroke/fill colour control, draw animation types (delayed, sync, oneByOne), duration, hover/scroll triggers, alignment, max width, and link wrapper. This is a dynamic block.', 'the-plus-addons-for-block-editor' ),
		'category'            => 'nexter-blocks',

		'input_schema'        => array(
			'type'                 => 'object',
			'properties'           => array(

				/* ── Core ─────────────────────────────────────────────────── */
				'post_id'             => array(
					'type'        => 'integer',
					'description' => 'WordPress page/post ID to insert the block into.',
				),
				'position'            => array(
					'type'        => 'integer',
					'default'     => -1,
					'description' => 'Zero-based insert position. Use -1 to append.',
				),
				'parent_block_id'     => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Optional parent container block_id for nesting.',
				),

				/* ── SVG Selection ────────────────────────────────────────── */
				'svgSource'           => array(
					'type'        => 'string',
					'enum'        => array( 'preBuild', 'custom' ),
					'description' => 'SVG source. "preBuild" = choose from pre-built icon set; "custom" = upload your own SVG.',
					'default'     => 'preBuild',
				),
				'svgName'             => array(
					'type'        => 'string',
					'description' => 'Name of pre-built SVG icon (when svgSource is "preBuild"). Options include: app, chat, star, heart, home, user, settings, search, etc.',
					'default'     => 'app',
				),
				'customSvgUrl'        => array(
					'type'        => 'string',
					'description' => 'URL of custom SVG file (when svgSource is "custom").',
					'default'     => '',
				),
				'customSvgId'         => array(
					'type'        => 'integer',
					'description' => 'WordPress media attachment ID of custom SVG (optional, preferred over customSvgUrl).',
					'default'     => 0,
				),

				/* ── Layout ───────────────────────────────────────────────── */
				'alignment'           => array(
					'type'        => 'string',
					'enum'        => array( 'left', 'center', 'right' ),
					'description' => 'SVG horizontal alignment.',
					'default'     => 'center',
				),
				'maxWidth'            => array(
					'type'        => 'integer',
					'description' => 'Maximum SVG width in px. 0 = auto.',
					'default'     => 0,
				),

				/* ── Link ─────────────────────────────────────────────────── */
				'linkUrl'             => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'URL to wrap the SVG in a link.',
				),
				'linkTarget'          => array(
					'type'    => 'string',
					'enum'    => array( '_self', '_blank' ),
					'default' => '_self',
				),
				'linkNofollow'        => array(
					'type'    => 'boolean',
					'default' => false,
				),

				/* ── Colours ──────────────────────────────────────────────── */
				'strokeColor'         => array(
					'type'        => 'string',
					'default'     => '#8072fc',
					'description' => 'Stroke colour for SVG paths.',
				),
				'enableFill'          => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Enable fill colour (fills the SVG after drawing).',
				),
				'fillColor'           => array(
					'type'        => 'string',
					'default'     => '#000000',
					'description' => 'Fill colour for SVG.',
				),

				/* ── Animation ────────────────────────────────────────────── */
				'drawType'            => array(
					'type'        => 'string',
					'enum'        => array( 'delayed', 'sync', 'oneByOne' ),
					'description' => 'Draw animation type. "delayed" = each path draws with delay; "sync" = all paths draw simultaneously; "oneByOne" = paths draw sequentially.',
					'default'     => 'delayed',
				),
				'duration'            => array(
					'type'        => 'string',
					'description' => 'Animation duration in 1/10ths of seconds e.g. "90" = 9 seconds.',
					'default'     => '90',
				),
				'animationTrigger'    => array(
					'type'        => 'string',
					'enum'        => array( 'onScroll', 'onHover' ),
					'description' => 'When the draw animation triggers.',
					'default'     => 'onScroll',
				),

				/* ── Scroll animation ────────────────────────────────────── */
				'scrollAnimation'     => array(
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
				'animDuration'        => array(
					'type'    => 'string',
					'enum'    => array( '', 'slow', 'normal', 'fast' ),
					'default' => '',
				),
				'animDelay'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'animEasing'          => array(
					'type'    => 'string',
					'enum'    => array( '', 'linear', 'ease', 'ease-in', 'ease-out', 'ease-in-out' ),
					'default' => '',
				),

				/* ── Advanced ─────────────────────────────────────────────── */
				'hideDesktop'         => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'hideTablet'          => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'hideMobile'          => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'globalClasses'       => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalId'            => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalCustomCss'     => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalWidth'         => array(
					'type'    => 'string',
					'enum'    => array( '', 'inline', 'full', 'custom' ),
					'default' => '',
				),
				'globalZindex'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalPosition'      => array(
					'type'    => 'string',
					'enum'    => array( '', 'relative', 'absolute', 'fixed', 'sticky' ),
					'default' => '',
				),
				'transitionDuration'  => array(
					'type'    => 'string',
					'default' => '',
				),
				'transitionFunction'  => array(
					'type'    => 'string',
					'enum'    => array( '', 'ease', 'ease-in', 'ease-out', 'ease-in-out', 'linear' ),
					'default' => '',
				),
				'transitionOrigin'    => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalMargin'        => array( 'type' => 'object' ),
				'globalPadding'       => array( 'type' => 'object' ),
				'globalBgColor'       => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalBgHoverColor'  => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalBorder'        => array( 'type' => 'object' ),
				'globalBorderHover'   => array( 'type' => 'object' ),
				'globalBRadius'       => array( 'type' => 'object' ),
				'globalBRadiusHover'  => array( 'type' => 'object' ),
				'globalBShadow'       => array( 'type' => 'object' ),
				'globalBShadowHover'  => array( 'type' => 'object' ),

				/* ── Transforms ──────────────────────────────────────────── */
				'rotateDeg'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotateX'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotateY'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotatePerspective'   => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotateDegHover'      => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotateXHover'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotateYHover'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotatePersHover'     => array(
					'type'    => 'string',
					'default' => '',
				),
				'offsetX'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'offsetY'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'offsetZ'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'offsetXHover'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'offsetYHover'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'offsetZHover'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'scaleValue'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'scaleKeepRatio'      => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'scaleValueHover'     => array(
					'type'    => 'string',
					'default' => '',
				),
				'skewX'               => array(
					'type'    => 'string',
					'default' => '',
				),
				'skewY'               => array(
					'type'    => 'string',
					'default' => '',
				),
				'skewXHover'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'skewYHover'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'flipHorizontal'      => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'flipVertical'        => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'flipHorizontalHover' => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'flipVerticalHover'   => array(
					'type'    => 'boolean',
					'default' => false,
				),

				'settings'            => array(
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

		'execute_callback'    => 'tpgb_mcp_add_draw_svg_ability',
		'permission_callback' => 'tpgb_mcp_add_draw_svg_permission',
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
 * Permission callback for the add-draw-svg ability.
 *
 * @param array|null $input Ability input arguments.
 * @return bool True when the current user may insert the block.
 */
function tpgb_mcp_add_draw_svg_permission( ?array $input = null ): bool {
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
function tpgb_mcp_dsvg_spacing( array $v ): array {
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
function tpgb_mcp_dsvg_border( array $b ): array {
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
function tpgb_mcp_dsvg_radius( array $r ): array {
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
function tpgb_mcp_dsvg_bshadow( array $s ): array {
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
 * Build a Nexter Blocks solid-colour background attribute.
 *
 * @param string $color Background colour value.
 * @return array Background attribute structured for the block.
 */
function tpgb_mcp_dsvg_bg( string $color ): array {
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
function tpgb_mcp_dsvg_needs_wrapper( array $attrs ): bool {
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
		'gRotte',
		'gRotteHov',
		'gOfset',
		'gOfsetHov',
		'gScle',
		'gScleHov',
		'gSkew',
		'gSkewHov',
		'gFHori',
		'gFVert',
		'gFHoriHov',
		'gFVertHov',
		'gTraDur',
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
 * Execute callback: insert a tpgb/tp-draw-svg block into a post.
 *
 * @param array $input Ability input arguments.
 * @return array|WP_Error Ability result or error on failure.
 */
function tpgb_mcp_add_draw_svg_ability( array $input ) {

	if ( ! defined( 'TPGB_VERSION' ) && ! defined( 'TPGBP_VERSION' ) ) {
		return new WP_Error( 'nexter_blocks_missing', __( 'Nexter Blocks must be active.', 'the-plus-addons-for-block-editor' ) );
	}

	$block_name = 'tpgb/tp-draw-svg';
	if ( ! tpgb_mcp_has_registered_block( $block_name ) ) {
		return new WP_Error( 'block_missing', __( 'tpgb/tp-draw-svg is not registered.', 'the-plus-addons-for-block-editor' ) );
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
	// Build attributes (only non-defaults).
	// ---------------------------------------------------------------------
	$block_id = tpgb_mcp_generate_block_id();
	$attrs    = array( 'block_id' => $block_id );

	/* ── SVG Selection ────────────────────────────────────────────────── */
	$svg_source = sanitize_key( $input['svgSource'] ?? 'preBuild' );
	if ( 'preBuild' !== $svg_source ) {
		$attrs['selectSvg'] = $svg_source; }

	if ( 'preBuild' === $svg_source ) {
		if ( ! empty( $input['svgName'] ) && 'app' !== $input['svgName'] ) {
			$attrs['svgList'] = sanitize_text_field( $input['svgName'] );
		}
	} else { // Custom.
		$svg_obj = array( 'url' => '' );
		if ( ! empty( $input['customSvgId'] ) ) {
			$svg_obj['id'] = absint( $input['customSvgId'] );
			$src           = wp_get_attachment_url( $svg_obj['id'] );
			if ( $src ) {
				$svg_obj['url'] = $src; }
		} elseif ( ! empty( $input['customSvgUrl'] ) ) {
			$svg_obj['url'] = esc_url_raw( $input['customSvgUrl'] );
		}
		if ( ! empty( $svg_obj['url'] ) ) {
			$attrs['customSVG'] = $svg_obj; }
	}

	/* ── Layout ───────────────────────────────────────────────────────── */
	if ( ! empty( $input['alignment'] ) && 'center' !== $input['alignment'] ) {
		$attrs['alignment'] = sanitize_key( $input['alignment'] );
	}
	if ( ! empty( $input['maxWidth'] ) ) {
		$attrs['maxWidth'] = array(
			'md'   => (string) absint( $input['maxWidth'] ),
			'unit' => 'px',
		);
	}

	/* ── Link ─────────────────────────────────────────────────────────── */
	if ( ! empty( $input['linkUrl'] ) ) {
		$attrs['svgLink'] = array(
			'url'      => esc_url_raw( $input['linkUrl'] ),
			'target'   => '_blank' === ( $input['linkTarget'] ?? '' ) ? '_blank' : '',
			'nofollow' => ! empty( $input['linkNofollow'] ) ? 'on' : '',
		);
	}

	/* ── Colours ──────────────────────────────────────────────────────── */
	if ( ! empty( $input['strokeColor'] ) && '#8072fc' !== $input['strokeColor'] ) {
		$attrs['strokeColor'] = sanitize_text_field( $input['strokeColor'] );
	}
	if ( ! empty( $input['enableFill'] ) ) {
		$attrs['fillToggle'] = true;
		if ( ! empty( $input['fillColor'] ) && '#000000' !== $input['fillColor'] ) {
			$attrs['fillColor'] = sanitize_text_field( $input['fillColor'] );
		}
	}

	/* ── Animation ────────────────────────────────────────────────────── */
	if ( ! empty( $input['drawType'] ) && 'delayed' !== $input['drawType'] ) {
		$attrs['drawType'] = sanitize_key( $input['drawType'] );
	}
	if ( ! empty( $input['duration'] ) && '90' !== $input['duration'] ) {
		$attrs['duration'] = sanitize_text_field( $input['duration'] );
	}
	if ( ! empty( $input['animationTrigger'] ) && 'onScroll' !== $input['animationTrigger'] ) {
		$attrs['hoverDraw'] = sanitize_key( $input['animationTrigger'] );
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

	/* ── Transition ───────────────────────────────────────────────────── */
	if ( ! empty( $input['transitionDuration'] ) ) {
		$attrs['gTraDur'] = sanitize_text_field( $input['transitionDuration'] ); }
	if ( ! empty( $input['transitionFunction'] ) ) {
		$attrs['gTraFunc'] = sanitize_text_field( $input['transitionFunction'] ); }
	if ( ! empty( $input['transitionOrigin'] ) ) {
		$attrs['gTraOrigin'] = sanitize_text_field( $input['transitionOrigin'] );   }

	/* ── Global: Spacing/Bg/Border/Shadow ─────────────────────────────── */
	if ( ! empty( $input['globalMargin'] ) ) {
		$attrs['globalMargin'] = tpgb_mcp_dsvg_spacing( $input['globalMargin'] );  }
	if ( ! empty( $input['globalPadding'] ) ) {
		$attrs['globalPadding'] = tpgb_mcp_dsvg_spacing( $input['globalPadding'] ); }
	if ( ! empty( $input['globalBgColor'] ) ) {
		$attrs['globalBg'] = tpgb_mcp_dsvg_bg( $input['globalBgColor'] );      }
	if ( ! empty( $input['globalBgHoverColor'] ) ) {
		$attrs['globalBgHover'] = tpgb_mcp_dsvg_bg( $input['globalBgHoverColor'] ); }
	if ( ! empty( $input['globalBorder'] ) ) {
		$attrs['globalBorder'] = tpgb_mcp_dsvg_border( $input['globalBorder'] );       }
	if ( ! empty( $input['globalBorderHover'] ) ) {
		$attrs['globalBorderHover'] = tpgb_mcp_dsvg_border( $input['globalBorderHover'] );  }
	if ( ! empty( $input['globalBRadius'] ) ) {
		$attrs['globalBRadius'] = tpgb_mcp_dsvg_radius( $input['globalBRadius'] );      }
	if ( ! empty( $input['globalBRadiusHover'] ) ) {
		$attrs['globalBRadiusHover'] = tpgb_mcp_dsvg_radius( $input['globalBRadiusHover'] ); }
	if ( ! empty( $input['globalBShadow'] ) ) {
		$attrs['globalBShadow'] = tpgb_mcp_dsvg_bshadow( $input['globalBShadow'] );      }
	if ( ! empty( $input['globalBShadowHover'] ) ) {
		$attrs['globalBShadowHover'] = tpgb_mcp_dsvg_bshadow( $input['globalBShadowHover'] ); }

	/* ── Transforms ───────────────────────────────────────────────────── */
	if ( ! empty( $input['rotateDeg'] ) || ! empty( $input['rotateX'] ) || ! empty( $input['rotateY'] ) || ! empty( $input['rotatePerspective'] ) ) {
		$attrs['gRotte'] = array(
			'tpgbReset'         => 1,
			'rotateToogle'      => false,
			'gRotteDeg'         => array( 'md' => sanitize_text_field( $input['rotateDeg'] ?? '0' ) ),
			'gRotteX'           => array( 'md' => sanitize_text_field( $input['rotateX'] ?? '' ) ),
			'gRotteY'           => array( 'md' => sanitize_text_field( $input['rotateY'] ?? '' ) ),
			'globalPerspective' => array( 'md' => sanitize_text_field( $input['rotatePerspective'] ?? '' ) ),
		);
	}
	if ( ! empty( $input['rotateDegHover'] ) || ! empty( $input['rotateXHover'] ) || ! empty( $input['rotateYHover'] ) || ! empty( $input['rotatePersHover'] ) ) {
		$attrs['gRotteHov'] = array(
			'tpgbReset'    => 1,
			'rToggleHov'   => true,
			'gRotteDegHov' => array( 'md' => sanitize_text_field( $input['rotateDegHover'] ?? '0' ) ),
			'gRotteXHov'   => array( 'md' => sanitize_text_field( $input['rotateXHover'] ?? '' ) ),
			'gRotteYHov'   => array( 'md' => sanitize_text_field( $input['rotateYHover'] ?? '' ) ),
			'gPersHov'     => array( 'md' => sanitize_text_field( $input['rotatePersHover'] ?? '' ) ),
		);
	}
	if ( ! empty( $input['offsetX'] ) || ! empty( $input['offsetY'] ) || ! empty( $input['offsetZ'] ) ) {
		$attrs['gOfset'] = array(
			'tpgbReset' => 1,
			'gOfsetX'   => array(
				'md'   => sanitize_text_field( $input['offsetX'] ?? '0' ),
				'unit' => 'px',
			),
			'gOfsetY'   => array(
				'md'   => sanitize_text_field( $input['offsetY'] ?? '0' ),
				'unit' => 'px',
			),
			'gOfsetZ'   => array(
				'md'   => sanitize_text_field( $input['offsetZ'] ?? '' ),
				'unit' => 'px',
			),
		);
	}
	if ( ! empty( $input['offsetXHover'] ) || ! empty( $input['offsetYHover'] ) || ! empty( $input['offsetZHover'] ) ) {
		$attrs['gOfsetHov'] = array(
			'tpgbReset'  => 1,
			'gOfsetXHov' => array(
				'md'   => sanitize_text_field( $input['offsetXHover'] ?? '0' ),
				'unit' => 'px',
			),
			'gOfsetYHov' => array(
				'md'   => sanitize_text_field( $input['offsetYHover'] ?? '0' ),
				'unit' => 'px',
			),
			'gOfsetZHov' => array(
				'md'   => sanitize_text_field( $input['offsetZHover'] ?? '0' ),
				'unit' => 'px',
			),
		);
	}
	if ( ! empty( $input['scaleValue'] ) && '1' !== $input['scaleValue'] ) {
		$attrs['gScle'] = array(
			'tpgbReset'       => 1,
			'keepProportions' => $input['scaleKeepRatio'] ?? true,
			'gScleValue'      => array( 'md' => sanitize_text_field( $input['scaleValue'] ) ),
			'gScleX'          => array( 'md' => '' ),
			'gScleY'          => array( 'md' => '' ),
		);
	}
	if ( ! empty( $input['scaleValueHover'] ) && '1' !== $input['scaleValueHover'] ) {
		$attrs['gScleHov'] = array(
			'tpgbReset'     => 1,
			'keepPropHov'   => true,
			'gScleValueHov' => array( 'md' => sanitize_text_field( $input['scaleValueHover'] ) ),
			'gScleXHov'     => array( 'md' => '' ),
			'gScleYHov'     => array( 'md' => '' ),
		);
	}
	if ( ! empty( $input['skewX'] ) || ! empty( $input['skewY'] ) ) {
		$attrs['gSkew'] = array(
			'tpgbReset' => 1,
			'gSkewX'    => array( 'md' => sanitize_text_field( $input['skewX'] ?? '0' ) ),
			'gSkewY'    => array( 'md' => sanitize_text_field( $input['skewY'] ?? '0' ) ),
		);
	}
	if ( ! empty( $input['skewXHover'] ) || ! empty( $input['skewYHover'] ) ) {
		$attrs['gSkewHov'] = array(
			'tpgbReset' => 1,
			'gSkewXHov' => array( 'md' => sanitize_text_field( $input['skewXHover'] ?? '0' ) ),
			'gSkewYHov' => array( 'md' => sanitize_text_field( $input['skewYHover'] ?? '0' ) ),
		);
	}
	if ( ! empty( $input['flipHorizontal'] ) ) {
		$attrs['gFHori'] = true; }
	if ( ! empty( $input['flipVertical'] ) ) {
		$attrs['gFVert'] = true; }
	if ( ! empty( $input['flipHorizontalHover'] ) ) {
		$attrs['gFHoriHov'] = true; }
	if ( ! empty( $input['flipVerticalHover'] ) ) {
		$attrs['gFVertHov'] = true; }

	/* ── tpgbDisrule ──────────────────────────────────────────────────── */
	if ( tpgb_mcp_dsvg_needs_wrapper( $attrs ) ) {
		$attrs['tpgbDisrule'] = true; }

	/* ── Raw settings override ────────────────────────────────────────── */
	$attrs = tpgb_mcp_merge_block_settings( $attrs, $input['settings'] ?? array() );

	/* ── Build, insert, save (dynamic block) ──────────────────────────── */
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
