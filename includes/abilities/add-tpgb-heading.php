<?php
/**
 * Ability: Add Nexter Blocks Heading (tpgb/tp-heading) to a Gutenberg page.
 *
 * @package The_Plus_Addons_For_Block_Editor
 * @since   1.3.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_register_ability(
	'nexter-blocks/add-tpgb-heading',
	array(
		'label'               => __( 'Add Nexter Blocks Heading', 'the-plus-addons-for-block-editor' ),
		'description'         => __( 'Adds a NEXTER BLOCKS Heading block (tpgb/tp-heading). Use this — NOT the WordPress core heading block — whenever the user asks to add a heading on a Nexter Blocks site. Typography is set via top-level params (enableTypography, fontFamily, fontType, fontWeight, textDecoration, lineHeight, letterSpacing, typoSize) — pass them directly here, do not route them through settings.tTypo. Also supports colours, stroke, shadow, blend mode, animations, transforms, spacing, background, border, and advanced position/visibility controls.', 'the-plus-addons-for-block-editor' ),
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
					'description' => 'Insert position among top-level blocks (0-based). Use -1 to append.',
					'default'     => -1,
				),
				'parent_block_id'     => array(
					'type'        => 'string',
					'description' => 'Optional block_id of a parent container block. When provided, this block is inserted INSIDE the parent as an inner block instead of at the page top level.',
					'default'     => '',
				),

				/* ── Content ─────────────────────────────────────────────── */
				'title'               => array(
					'type'        => 'string',
					'description' => 'Main heading text. ALWAYS pass the exact text the user specified. Accepts inline HTML for span highlights. Stored as exTitle internally.',
					'default'     => 'Add Your Heading Text Here',
				),

				/* ── Tag & alignment ─────────────────────────────────────── */
				'tTag'                => array(
					'type'        => 'string',
					'enum'        => array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'p', 'span' ),
					'description' => 'Semantic HTML tag.',
					'default'     => 'h3',
				),
				'tAlign'              => array(
					'type'        => 'string',
					'enum'        => array( 'left', 'center', 'right', '' ),
					'description' => 'Desktop text alignment. Leave empty for block default.',
					'default'     => '',
				),
				'tAlignTablet'        => array(
					'type'        => 'string',
					'enum'        => array( 'left', 'center', 'right', '' ),
					'description' => 'Tablet text alignment.',
					'default'     => '',
				),
				'tAlignMobile'        => array(
					'type'        => 'string',
					'enum'        => array( 'left', 'center', 'right', '' ),
					'description' => 'Mobile text alignment.',
					'default'     => '',
				),

				/* ── Link ────────────────────────────────────────────────── */
				'linkUrl'             => array(
					'type'        => 'string',
					'description' => 'URL to wrap the heading in an anchor tag.',
					'default'     => '',
				),
				'linkTarget'          => array(
					'type'        => 'string',
					'enum'        => array( '_self', '_blank' ),
					'description' => 'Link target.',
					'default'     => '_self',
				),
				'linkNofollow'        => array(
					'type'        => 'boolean',
					'description' => 'Add rel="nofollow" to the link.',
					'default'     => false,
				),

				/* ── Anchor ──────────────────────────────────────────────── */
				'anchor'              => array(
					'type'        => 'string',
					'description' => 'HTML id for in-page anchor links. No # prefix.',
					'default'     => '',
				),

				/* ── Text colour ─────────────────────────────────────────── */
				'textColor'           => array(
					'type'        => 'string',
					'description' => 'Heading text colour (hex/rgb/CSS var). Always pass when user specifies a colour.',
					'default'     => '',
				),

				/* ── Typography ──────────────────────────────────────────── */
				'enableTypography'    => array(
					'type'        => 'boolean',
					'description' => 'Enable custom typography.',
					'default'     => false,
				),
				'typoSize'            => array(
					'type'        => 'integer',
					'description' => 'Font size in px.',
					'default'     => 0,
				),
				'typoGlobalPreset'    => array(
					'type'        => 'string',
					'description' => 'Global typography preset ID (e.g. "1").',
					'default'     => '',
				),
				'fontFamily'          => array(
					'type'        => 'string',
					'description' => 'Font family name (e.g. "Aclonica", "Roboto", "Inter"). Maps to Google Fonts when available.',
					'default'     => '',
				),
				'fontType'            => array(
					'type'        => 'string',
					'description' => 'Font category. Use the Google-Fonts category for the family — typical values: "sans-serif", "serif", "display", "handwriting", "monospace". Leave empty if unsure.',
					'default'     => '',
				),
				'customFont'          => array(
					'type'        => 'string',
					'description' => 'Custom (non-Google) font name. When set, this overrides fontFamily and is used as-is in CSS font-family.',
					'default'     => '',
				),
				'fontWeight'          => array(
					'type'        => 'string',
					'description' => 'Font weight as a string: "100" Thin, "200" Extra Light, "300" Light, "400" Regular (default), "500" Medium, "600" Semi Bold, "700" Bold, "800" Extra Bold, "900" Black. Embedded inside tTypo.fontFamily.fontWeight. Requires enableTypography=true.',
					'default'     => '',
				),
				'textDecoration'      => array(
					'type'        => 'string',
					'enum'        => array( '', 'none', 'underline', 'overline', 'line-through' ),
					'description' => 'Text decoration. Stored as tTypo.textDecoration (sibling of fontFamily). Requires enableTypography=true.',
					'default'     => '',
				),
				'lineHeight'          => array(
					'type'        => 'number',
					'description' => 'Line-height (unitless, e.g. 1.2). Leave 0 to inherit.',
					'default'     => 0,
				),
				'letterSpacing'       => array(
					'type'        => 'number',
					'description' => 'Letter-spacing in px. Leave 0 to inherit.',
					'default'     => 0,
				),

				/* ── Text stroke ─────────────────────────────────────────── */
				'strokeWidth'         => array(
					'type'        => 'integer',
					'description' => 'Text stroke width in px.',
					'default'     => 0,
				),
				'strokeColor'         => array(
					'type'        => 'string',
					'description' => 'Text stroke colour.',
					'default'     => '',
				),

				/* ── Text shadow ─────────────────────────────────────────── */
				'enableShadow'        => array(
					'type'        => 'boolean',
					'description' => 'Enable text shadow.',
					'default'     => false,
				),
				'shadowHorizontal'    => array(
					'type'    => 'integer',
					'default' => 2,
				),
				'shadowVertical'      => array(
					'type'    => 'integer',
					'default' => 3,
				),
				'shadowBlur'          => array(
					'type'    => 'integer',
					'default' => 8,
				),
				'shadowSpread'        => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'shadowColor'         => array(
					'type'    => 'string',
					'default' => 'rgba(0,0,0,0.5)',
				),

				/* ── Blend mode ──────────────────────────────────────────── */
				'blendMode'           => array(
					'type'    => 'string',
					'enum'    => array(
						'',
						'normal',
						'multiply',
						'screen',
						'overlay',
						'darken',
						'lighten',
						'color-dodge',
						'color-burn',
						'hard-light',
						'soft-light',
						'difference',
						'exclusion',
						'hue',
						'saturation',
						'color',
						'luminosity',
					),
					'default' => '',
				),

				/* ── Scroll animation ────────────────────────────────────── */
				'scrollAnimation'     => array(
					'type'        => 'string',
					'enum'        => array(
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
					'description' => 'Scroll-triggered entrance animation.',
					'default'     => '',
				),
				'animDelay'           => array(
					'type'        => 'string',
					'description' => 'Animation delay in seconds e.g. "0.2".',
					'default'     => '',
				),
				'animEasing'          => array(
					'type'    => 'string',
					'enum'    => array( '', 'linear', 'ease', 'ease-in', 'ease-out', 'ease-in-out' ),
					'default' => '',
				),

				/* ── Advanced: Visibility ────────────────────────────────── */
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

				/* ── Advanced: Identity ──────────────────────────────────── */
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

				/* ── Advanced: Layout ────────────────────────────────────── */
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

				/* ── Advanced: Transition ────────────────────────────────── */
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

				/* ── Global: Spacing ─────────────────────────────────────── */
				'globalMargin'        => array(
					'type'        => 'object',
					'description' => 'Outer margin {top,bottom,left,right,unit}.',
				),
				'globalPadding'       => array(
					'type'        => 'object',
					'description' => 'Inner padding {top,bottom,left,right,unit}.',
				),

				/* ── Global: Background ──────────────────────────────────── */
				'globalBgColor'       => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Normal background colour.',
				),
				'globalBgHoverColor'  => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Hover background colour.',
				),

				/* ── Global: Border ──────────────────────────────────────── */
				'globalBorder'        => array(
					'type'        => 'object',
					'description' => 'Normal border {type,color,width:{top,right,bottom,left,unit}}.',
				),
				'globalBorderHover'   => array(
					'type'        => 'object',
					'description' => 'Hover border.',
				),

				/* ── Global: Border radius ───────────────────────────────── */
				'globalBRadius'       => array(
					'type'        => 'object',
					'description' => 'Normal border radius {top,bottom,left,right,unit}.',
				),
				'globalBRadiusHover'  => array(
					'type'        => 'object',
					'description' => 'Hover border radius.',
				),

				/* ── Global: Box shadow ──────────────────────────────────── */
				'globalBShadow'       => array(
					'type'        => 'object',
					'description' => 'Normal box shadow {horizontal,vertical,blur,spread,color}.',
				),
				'globalBShadowHover'  => array(
					'type'        => 'object',
					'description' => 'Hover box shadow.',
				),

				/* ── Transform: Rotate ───────────────────────────────────── */
				'rotateEnable'        => array(
					'type'        => 'boolean',
					'description' => 'Enable rotation transform.',
					'default'     => false,
				),
				'rotateDeg'           => array(
					'type'    => 'string',
					'default' => '0',
				),
				'rotateX'             => array(
					'type'    => 'string',
					'default' => '0',
				),
				'rotateY'             => array(
					'type'    => 'string',
					'default' => '0',
				),
				'rotatePerspective'   => array(
					'type'    => 'string',
					'default' => '0',
				),

				/* ── Transform: Rotate Hover ─────────────────────────────── */
				'rotateHoverEnable'   => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'rotateDegHover'      => array(
					'type'    => 'string',
					'default' => '0',
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

				/* ── Transform: Offset ───────────────────────────────────── */
				'offsetEnable'        => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'offsetX'             => array(
					'type'    => 'string',
					'default' => '0',
				),
				'offsetY'             => array(
					'type'    => 'string',
					'default' => '0',
				),
				'offsetZ'             => array(
					'type'    => 'string',
					'default' => '0',
				),

				/* ── Transform: Offset Hover ─────────────────────────────── */
				'offsetHoverEnable'   => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'offsetXHover'        => array(
					'type'    => 'string',
					'default' => '0',
				),
				'offsetYHover'        => array(
					'type'    => 'string',
					'default' => '0',
				),
				'offsetZHover'        => array(
					'type'    => 'string',
					'default' => '0',
				),

				/* ── Transform: Scale ────────────────────────────────────── */
				'scaleEnable'         => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'scaleValue'          => array(
					'type'    => 'string',
					'default' => '1',
				),
				'scaleKeepRatio'      => array(
					'type'    => 'boolean',
					'default' => true,
				),

				/* ── Transform: Scale Hover ──────────────────────────────── */
				'scaleHoverEnable'    => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'scaleValueHover'     => array(
					'type'    => 'string',
					'default' => '1',
				),

				/* ── Transform: Skew ─────────────────────────────────────── */
				'skewEnable'          => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'skewX'               => array(
					'type'    => 'string',
					'default' => '0',
				),
				'skewY'               => array(
					'type'    => 'string',
					'default' => '0',
				),

				/* ── Transform: Skew Hover ───────────────────────────────── */
				'skewHoverEnable'     => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'skewXHover'          => array(
					'type'    => 'string',
					'default' => '0',
				),
				'skewYHover'          => array(
					'type'    => 'string',
					'default' => '0',
				),

				/* ── Transform: Flip ─────────────────────────────────────── */
				'flipHorizontal'      => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Flip block horizontally.',
				),
				'flipVertical'        => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Flip block vertically.',
				),
				'flipHorizontalHover' => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Flip horizontally on hover.',
				),
				'flipVerticalHover'   => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Flip vertically on hover.',
				),

				/* ── Raw override ───────────────────────────────────────── */
				'settings'            => array(
					'type'        => 'object',
					'description' => 'Raw attribute overrides merged directly. Keys must match block.json names.',
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

		'execute_callback'    => 'tpgb_mcp_add_heading_ability',
		'permission_callback' => 'tpgb_mcp_add_heading_permission',
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
 * Permission callback for the add-heading ability.
 *
 * @param array|null $input Ability input arguments.
 * @return bool True when the current user may insert the block.
 */
function tpgb_mcp_add_heading_permission( ?array $input = null ): bool {
	if ( ! current_user_can( 'edit_posts' ) ) {
		return false;
	}
	$post_id = absint( $input['post_id'] ?? 0 );
	if ( $post_id > 0 && ! current_user_can( 'edit_post', $post_id ) ) {
		return false;
	}
	return true;
}

// -------------------------------------------------------------------------
// HELPERS
// -------------------------------------------------------------------------

/**
 * Determine whether any wrapper-level (global/transform) attributes are set.
 *
 * @param array $attrs Block attributes built so far.
 * @return bool True when the block needs the tpgbDisrule wrapper enabled.
 */
function tpgb_mcp_heading_needs_wrapper( array $attrs ): bool {
	$wrapper_keys = array(
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
		'PlusGlassMorphism',
		'globalWidth',
		'globalZindex',
		'globalPosition',
		'globalClasses',
		'globalId',
		'globalCustomCss',
		'globalHideDesktop',
		'globalHideTablet',
		'globalHideMobile',
		'globalflexCss',
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
	foreach ( $wrapper_keys as $key ) {
		if ( ! empty( $attrs[ $key ] ) ) {
			return true;
		}
	}
	return false;
}

/**
 * Build a Nexter Blocks spacing attribute from {top,bottom,left,right,unit}.
 *
 * @param array $v Raw spacing values.
 * @return array Spacing attribute structured for the block.
 */
function tpgb_mcp_heading_spacing( array $v ): array {
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
function tpgb_mcp_heading_border( array $b ): array {
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
function tpgb_mcp_heading_radius( array $r ): array {
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
function tpgb_mcp_heading_bshadow( array $s ): array {
	return array(
		'openShadow' => true,
		'inset'      => $s['inset'] ?? 0,
		'horizontal' => intval( $s['horizontal'] ?? 0 ),
		'vertical'   => intval( $s['vertical'] ?? 4 ),
		'blur'       => absint( $s['blur'] ?? 8 ),
		'spread'     => intval( $s['spread'] ?? 0 ),
		'color'      => sanitize_text_field( $s['color'] ?? 'rgba(0,0,0,0.40)' ),
	);
}

/**
 * Build a Nexter Blocks solid-colour background attribute.
 *
 * @param string $color Background colour value.
 * @return array Background attribute structured for the block.
 */
function tpgb_mcp_heading_bg( string $color ): array {
	return array(
		'openBg'         => 1,
		'bgType'         => 'color',
		'videoSource'    => 'local',
		'bgDefaultColor' => sanitize_text_field( $color ),
		'bgGradient'     => '',
		'isCustom'       => 'fpp',
	);
}

// -------------------------------------------------------------------------
// EXECUTE CALLBACK
// -------------------------------------------------------------------------

/**
 * Execute callback: insert a tpgb/tp-heading block into a post.
 *
 * @param array $input Ability input arguments.
 * @return array|WP_Error Ability result or error on failure.
 */
function tpgb_mcp_add_heading_ability( array $input ) {

	if ( ! defined( 'TPGB_VERSION' ) && ! defined( 'TPGBP_VERSION' ) ) {
		return new WP_Error( 'nexter_blocks_missing', __( 'Nexter Blocks must be active.', 'the-plus-addons-for-block-editor' ) );
	}

	$block_name = 'tpgb/tp-heading';

	if ( ! tpgb_mcp_has_registered_block( $block_name ) ) {
		return new WP_Error( 'block_missing', __( 'tpgb/tp-heading is not registered.', 'the-plus-addons-for-block-editor' ) );
	}

	$post_id  = absint( $input['post_id'] ?? 0 );
	$position = intval( $input['position'] ?? -1 );

	if ( $post_id <= 0 ) {
		return new WP_Error( 'missing_params', __( 'post_id is required.', 'the-plus-addons-for-block-editor' ) );
	}

	$post = get_post( $post_id );
	if ( ! $post instanceof WP_Post ) {
		return new WP_Error( 'invalid_post', __( 'Target post not found.', 'the-plus-addons-for-block-editor' ) );
	}

	$blocks = tpgb_mcp_get_blocks( $post_id );
	if ( is_wp_error( $blocks ) ) {
		return $blocks;
	}

	// ---------------------------------------------------------------------
	// Build attributes.
	// ---------------------------------------------------------------------
	$block_id = tpgb_mcp_generate_block_id();
	$attrs    = array( 'block_id' => $block_id );

	/* ── Content ──────────────────────────────────────────────────────── */
	$title_text = isset( $input['title'] ) && '' !== $input['title']
		? tpgb_mcp_clean_text( $input['title'] )
		: 'Add Your Heading Text Here';

	$attrs['exTitle'] = $title_text;
	$attrs['title']   = $title_text;

	/* ── Tag ──────────────────────────────────────────────────────────── */
	$allowed_tags  = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'p', 'span' );
	$tag           = ! empty( $input['tTag'] ) ? sanitize_text_field( $input['tTag'] ) : 'h3';
	$attrs['tTag'] = in_array( $tag, $allowed_tags, true ) ? $tag : 'h3';

	/* ── className ────────────────────────────────────────────────────── */
	$base_class         = "wp-block-tpgb-tp-heading tp-core-heading tpgb-block-{$block_id}";
	$attrs['className'] = $base_class;

	/* ── Alignment ────────────────────────────────────────────────────── */
	$align_desk   = sanitize_text_field( $input['tAlign'] ?? '' );
	$align_tablet = sanitize_text_field( $input['tAlignTablet'] ?? '' );
	$align_mobile = sanitize_text_field( $input['tAlignMobile'] ?? '' );

	if ( '' !== $align_desk || '' !== $align_tablet || '' !== $align_mobile ) {
		$attrs['tAlign'] = array(
			'md' => $align_desk,
			'sm' => $align_tablet,
			'xs' => $align_mobile,
		);
	}

	/* ── Link ─────────────────────────────────────────────────────────── */
	$has_link = ! empty( $input['linkUrl'] );
	if ( $has_link ) {
		$attrs['tLink'] = array(
			'url'      => esc_url_raw( $input['linkUrl'] ),
			'target'   => '_blank' === ( $input['linkTarget'] ?? '' ) ? '_blank' : '',
			'nofollow' => ! empty( $input['linkNofollow'] ) ? 'on' : '',
		);
	}

	/* ── Anchor ───────────────────────────────────────────────────────── */
	$anchor = '';
	if ( ! empty( $input['anchor'] ) ) {
		$anchor          = sanitize_title( $input['anchor'] );
		$attrs['anchor'] = $anchor;
	}

	/* ── Text colour ──────────────────────────────────────────────────── */
	if ( ! empty( $input['textColor'] ) ) {
		$attrs['tColor'] = sanitize_text_field( $input['textColor'] );
	}

	/* ── Typography ───────────────────────────────────────────────────── */
	if ( ! empty( $input['enableTypography'] ) ) {
		// fontFamily sub-object — delegate to the shared helper so the
		// fontWeight slot is populated consistently across every block.
		// The helper returns (object) [] when nothing was supplied, which
		// preserves the editor's "no font" shape.
		$font_family_attr = tpgb_mcp_font_family_attr( $input );

		// Optional line-height / letter-spacing — block stores them as
		// {md, unit} pairs; height is unitless, spacing is in px.
		$height_attr = '';
		if ( ! empty( $input['lineHeight'] ) ) {
			$height_attr = array(
				'md'   => (string) (float) $input['lineHeight'],
				'unit' => '',
			);
		}
		$spacing_attr = '';
		if ( ! empty( $input['letterSpacing'] ) ) {
			$spacing_attr = array(
				'md'   => (string) (float) $input['letterSpacing'],
				'unit' => 'px',
			);
		}

		$t_typo = array(
			'openTypography' => 1,
			'size'           => array(
				'md'   => ! empty( $input['typoSize'] ) ? (string) absint( $input['typoSize'] ) : '',
				'unit' => 'px',
			),
			'height'         => $height_attr,
			'spacing'        => $spacing_attr,
			'fontFamily'     => $font_family_attr,
		);
		// Only include globalTypo when a preset is actually selected. Emitting
		// it as "" makes tp-generate-block-css.php emit
		// `font-family:var(--tpgb-T-font-family)` (with no preset number),
		// which is undefined on the frontend.
		if ( ! empty( $input['typoGlobalPreset'] ) ) {
			$t_typo['globalTypo'] = sanitize_text_field( $input['typoGlobalPreset'] );
		}
		// Attach textDecoration as a sibling of fontFamily inside tTypo.
		$t_typo         = tpgb_mcp_apply_typo_extras( $t_typo, $input );
		$attrs['tTypo'] = $t_typo;
	}

	/* ── Text stroke ──────────────────────────────────────────────────── */
	if ( ! empty( $input['strokeWidth'] ) || ! empty( $input['strokeColor'] ) ) {
		$attrs['tStroke'] = array(
			'tpgbReset' => 1,
			'tstWidth'  => array(
				'md'   => (string) absint( $input['strokeWidth'] ?? 0 ),
				'unit' => 'px',
			),
			'tstColor'  => sanitize_text_field( $input['strokeColor'] ?? '' ),
		);
	}

	/* ── Text shadow ──────────────────────────────────────────────────── */
	if ( ! empty( $input['enableShadow'] ) ) {
		$attrs['tShadow'] = array(
			'openShadow' => true,
			'inset'      => '',
			'horizontal' => intval( $input['shadowHorizontal'] ?? 2 ),
			'vertical'   => intval( $input['shadowVertical'] ?? 3 ),
			'blur'       => absint( $input['shadowBlur'] ?? 8 ),
			'spread'     => intval( $input['shadowSpread'] ?? 0 ),
			'color'      => sanitize_text_field( $input['shadowColor'] ?? 'rgba(0,0,0,0.5)' ),
			'typeShadow' => 'text-shadow',
		);
	}

	/* ── Blend mode ───────────────────────────────────────────────────── */
	if ( ! empty( $input['blendMode'] ) ) {
		$attrs['tblendm'] = sanitize_text_field( $input['blendMode'] );
	}

	/* ── Scroll animation ─────────────────────────────────────────────── */
	if ( ! empty( $input['scrollAnimation'] ) ) {
		$attrs['globalAnim'] = array( 'md' => sanitize_text_field( $input['scrollAnimation'] ) ); }
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

	/* ── Global: Spacing ──────────────────────────────────────────────── */
	if ( ! empty( $input['globalMargin'] ) ) {
		$attrs['globalMargin'] = tpgb_mcp_heading_spacing( $input['globalMargin'] );  }
	if ( ! empty( $input['globalPadding'] ) ) {
		$attrs['globalPadding'] = tpgb_mcp_heading_spacing( $input['globalPadding'] ); }

	/* ── Global: Background ───────────────────────────────────────────── */
	if ( ! empty( $input['globalBgColor'] ) ) {
		$attrs['globalBg'] = tpgb_mcp_heading_bg( $input['globalBgColor'] );      }
	if ( ! empty( $input['globalBgHoverColor'] ) ) {
		$attrs['globalBgHover'] = tpgb_mcp_heading_bg( $input['globalBgHoverColor'] ); }

	/* ── Global: Border ───────────────────────────────────────────────── */
	if ( ! empty( $input['globalBorder'] ) ) {
		$attrs['globalBorder'] = tpgb_mcp_heading_border( $input['globalBorder'] );      }
	if ( ! empty( $input['globalBorderHover'] ) ) {
		$attrs['globalBorderHover'] = tpgb_mcp_heading_border( $input['globalBorderHover'] ); }

	/* ── Global: Border radius ────────────────────────────────────────── */
	if ( ! empty( $input['globalBRadius'] ) ) {
		$attrs['globalBRadius'] = tpgb_mcp_heading_radius( $input['globalBRadius'] );      }
	if ( ! empty( $input['globalBRadiusHover'] ) ) {
		$attrs['globalBRadiusHover'] = tpgb_mcp_heading_radius( $input['globalBRadiusHover'] ); }

	/* ── Global: Box shadow ───────────────────────────────────────────── */
	if ( ! empty( $input['globalBShadow'] ) ) {
		$attrs['globalBShadow'] = tpgb_mcp_heading_bshadow( $input['globalBShadow'] );      }
	if ( ! empty( $input['globalBShadowHover'] ) ) {
		$attrs['globalBShadowHover'] = tpgb_mcp_heading_bshadow( $input['globalBShadowHover'] ); }

	/* ── Transform: Rotate ────────────────────────────────────────────── */
	if ( ! empty( $input['rotateEnable'] ) ) {
		$attrs['gRotte'] = array(
			'tpgbReset'         => 1,
			'rotateToogle'      => true,
			'gRotteDeg'         => array( 'md' => sanitize_text_field( $input['rotateDeg'] ?? '0' ) ),
			'gRotteX'           => array( 'md' => sanitize_text_field( $input['rotateX'] ?? '0' ) ),
			'gRotteY'           => array( 'md' => sanitize_text_field( $input['rotateY'] ?? '0' ) ),
			'globalPerspective' => array( 'md' => sanitize_text_field( $input['rotatePerspective'] ?? '0' ) ),
		);
	}
	if ( ! empty( $input['rotateHoverEnable'] ) ) {
		$attrs['gRotteHov'] = array(
			'tpgbReset'    => 1,
			'rToggleHov'   => true,
			'gRotteDegHov' => array( 'md' => sanitize_text_field( $input['rotateDegHover'] ?? '0' ) ),
			'gRotteXHov'   => array( 'md' => sanitize_text_field( $input['rotateXHover'] ?? '' ) ),
			'gRotteYHov'   => array( 'md' => sanitize_text_field( $input['rotateYHover'] ?? '' ) ),
			'gPersHov'     => array( 'md' => sanitize_text_field( $input['rotatePersHover'] ?? '' ) ),
		);
	}

	/* ── Transform: Offset ────────────────────────────────────────────── */
	if ( ! empty( $input['offsetEnable'] ) ) {
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
				'md'   => sanitize_text_field( $input['offsetZ'] ?? '0' ),
				'unit' => 'px',
			),
		);
	}
	if ( ! empty( $input['offsetHoverEnable'] ) ) {
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

	/* ── Transform: Scale ─────────────────────────────────────────────── */
	if ( ! empty( $input['scaleEnable'] ) ) {
		$attrs['gScle'] = array(
			'tpgbReset'       => 1,
			'keepProportions' => ! empty( $input['scaleKeepRatio'] ),
			'gScleValue'      => array( 'md' => sanitize_text_field( $input['scaleValue'] ?? '1' ) ),
			'gScleX'          => array( 'md' => '' ),
			'gScleY'          => array( 'md' => '' ),
		);
	}
	if ( ! empty( $input['scaleHoverEnable'] ) ) {
		$attrs['gScleHov'] = array(
			'tpgbReset'     => 1,
			'keepPropHov'   => true,
			'gScleValueHov' => array( 'md' => sanitize_text_field( $input['scaleValueHover'] ?? '1' ) ),
			'gScleXHov'     => array( 'md' => '' ),
			'gScleYHov'     => array( 'md' => '' ),
		);
	}

	/* ── Transform: Skew ──────────────────────────────────────────────── */
	if ( ! empty( $input['skewEnable'] ) ) {
		$attrs['gSkew'] = array(
			'tpgbReset' => 1,
			'gSkewX'    => array( 'md' => sanitize_text_field( $input['skewX'] ?? '0' ) ),
			'gSkewY'    => array( 'md' => sanitize_text_field( $input['skewY'] ?? '0' ) ),
		);
	}
	if ( ! empty( $input['skewHoverEnable'] ) ) {
		$attrs['gSkewHov'] = array(
			'tpgbReset' => 1,
			'gSkewXHov' => array( 'md' => sanitize_text_field( $input['skewXHover'] ?? '0' ) ),
			'gSkewYHov' => array( 'md' => sanitize_text_field( $input['skewYHover'] ?? '0' ) ),
		);
	}

	/* ── Transform: Flip ──────────────────────────────────────────────── */
	if ( ! empty( $input['flipHorizontal'] ) ) {
		$attrs['gFHori'] = true; }
	if ( ! empty( $input['flipVertical'] ) ) {
		$attrs['gFVert'] = true; }
	if ( ! empty( $input['flipHorizontalHover'] ) ) {
		$attrs['gFHoriHov'] = true; }
	if ( ! empty( $input['flipVerticalHover'] ) ) {
		$attrs['gFVertHov'] = true; }

	/* ── tpgbDisrule ──────────────────────────────────────────────────── */
	if ( tpgb_mcp_heading_needs_wrapper( $attrs ) ) {
		$attrs['tpgbDisrule'] = true;
	}

	/* ── Raw settings override ────────────────────────────────────────── */
	$attrs = tpgb_mcp_merge_block_settings( $attrs, $input['settings'] ?? array() );

	// ---------------------------------------------------------------------
	// Build inner HTML.
	// ---------------------------------------------------------------------
	$needs_wrapper = tpgb_mcp_heading_needs_wrapper( $attrs );

	/* Heading element */
	$heading_html = sprintf(
		'<%1$s class="%2$s"%3$s>%4$s</%1$s>',
		esc_attr( $tag ),
		$needs_wrapper
			? esc_attr( "wp-block-tpgb-tp-heading tp-core-heading tpgb-block-{$block_id}" )
			: esc_attr( "{$base_class}  {$base_class}" ),
		'' !== $anchor ? ' id="' . esc_attr( $anchor ) . '"' : '',
		$title_text
	);

	/* Wrap in <a> if link set */
	if ( $has_link ) {
		$link_data    = $attrs['tLink'];
		$rel          = 'on' === $link_data['nofollow'] ? 'nofollow noopener' : 'follow noopener';
		$target_attr  = '_blank' === $link_data['target'] ? ' target="_blank"' : ' target="_self"';
		$heading_html = sprintf(
			'<a href="%1$s"%2$s rel="%3$s">%4$s</a>',
			esc_url( $link_data['url'] ),
			$target_attr,
			esc_attr( $rel ),
			$heading_html
		);
	}

	/* Outer wrapper div — present when any advanced option is active */
	if ( $needs_wrapper ) {
		$wrapper_classes = "tpgb-wrap-{$block_id}";

		if ( ! empty( $attrs['globalPosition'] ) ) {
			$wrapper_classes .= ' tpgb-position-relative tpgb-tab-position-relative tpgb-mobile-position-relative';
		}
		if ( ! empty( $attrs['globalClasses'] ) ) {
			$wrapper_classes .= ' ' . esc_attr( $attrs['globalClasses'] );
		}

		$anim_data_attr = '';
		if ( ! empty( $attrs['globalAnim'] ) ) {
			$anim_name        = esc_attr( $attrs['globalAnim']['md'] ?? '' );
			$wrapper_classes .= ' tpgb-view-animation tpgb-anim-dur-normal';
			$anim_data_attr   = sprintf(
				' data-animationsetting="%s"',
				esc_attr( wp_json_encode( array( 'anime' => array( 'md' => $anim_name ) ) ) )
			);
		}

		$id_attr = ! empty( $attrs['globalId'] ) ? ' id="' . esc_attr( $attrs['globalId'] ) . '"' : '';

		$inner_html = sprintf(
			'<div%1$s class="%2$s"%3$s>%4$s</div>',
			$id_attr,
			esc_attr( $wrapper_classes ),
			$anim_data_attr,
			$heading_html
		);
	} else {
		$inner_html = $heading_html;
	}

	/* ── Build, insert, save ──────────────────────────────────────────── */
	$block                 = tpgb_mcp_build_block( $block_name, $attrs );
	$block['innerHTML']    = $inner_html;
	$block['innerContent'] = array( $inner_html );

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
		return $save_result;
	}

	return array(
		'block_id'   => $block_id,
		'block_name' => $block_name,
		'post_id'    => $post_id,
	);
}
