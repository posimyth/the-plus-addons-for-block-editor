<?php
/**
 * Ability: Add Nexter Blocks Core Button (tpgb/tp-button-core) to a Gutenberg page.
 *
 * @package The_Plus_Addons_For_Block_Editor
 * @since   1.3.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_register_ability(
	'nexter-blocks/add-tpgb-button-core',
	array(
		'label'               => __( 'Add Nexter Blocks Core Button', 'the-plus-addons-for-block-editor' ),
		'description'         => __( 'Adds the Nexter Blocks Core Button block (tpgb/tp-button-core). A streamlined, modern button block with icon support (Font Awesome or image), typography, text shadow, colour, background (solid/gradient), border, border radius, box shadow, padding, alignment, animations, transforms, and advanced settings. This is the simplified counterpart to tpgb/tp-button — use this for clean, modern buttons.', 'the-plus-addons-for-block-editor' ),
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
					'description' => 'Zero-based insert position. Use -1 to append.',
					'default'     => -1,
				),
				'parent_block_id'     => array(
					'type'        => 'string',
					'description' => 'Optional block_id of a parent container block. When provided, this block is inserted INSIDE the parent as an inner block instead of at the page top level.',
					'default'     => '',
				),

				/* ── Content ─────────────────────────────────────────────── */
				'btnText'             => array(
					'type'        => 'string',
					'description' => 'Button text. ALWAYS pass the exact text the user specified.',
					'default'     => 'Click Here',
				),
				'linkUrl'             => array(
					'type'        => 'string',
					'description' => 'Button destination URL.',
					'default'     => '#',
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

				/* ── Alignment ────────────────────────────────────────────── */
				'alignment'           => array(
					'type'        => 'string',
					'enum'        => array( 'left', 'center', 'right', '' ),
					'description' => 'Button alignment on desktop.',
					'default'     => 'left',
				),

				/* ── Icon ─────────────────────────────────────────────────── */
				'iconType'            => array(
					'type'        => 'string',
					'enum'        => array( 'none', 'fontAwesome', 'image' ),
					'description' => 'Icon type. "none" hides icon, "fontAwesome" shows FA icon, "image" uses an uploaded image.',
					'default'     => 'none',
				),
				'iconClass'           => array(
					'type'        => 'string',
					'description' => 'Font Awesome icon class e.g. "fas fa-arrow-right". Only used when iconType is "fontAwesome".',
					'default'     => '',
				),
				'iconPosition'        => array(
					'type'        => 'string',
					'enum'        => array( 'before', 'after' ),
					'description' => 'Icon placement relative to text. "before" = left of text, "after" = right of text.',
					'default'     => 'after',
				),
				'iconSpacing'         => array(
					'type'        => 'integer',
					'description' => 'Gap between icon and text in px.',
					'default'     => 0,
				),
				'iconSize'            => array(
					'type'        => 'integer',
					'description' => 'Icon or image size in px.',
					'default'     => 0,
				),

				/* ── Typography ──────────────────────────────────────────── */
				'enableTypography'    => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Enable custom button text typography.',
				),
				'typoSize'            => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Font size in px.',
				),
				'typoGlobalPreset'    => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Global typography preset ID.',
				),

				/* ── Global button preset ─────────────────────────────────── */
				'buttonPreset'        => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Global button preset key (e.g. "btnpreset1"). When provided, raw colour/border/padding/typography/shadow values are ignored and the preset is applied instead.',
				),

				/* ── Text shadow ──────────────────────────────────────────── */
				'enableTextShadow'    => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Enable text shadow on button text.',
				),
				'textShadowH'         => array(
					'type'    => 'integer',
					'default' => 2,
				),
				'textShadowV'         => array(
					'type'    => 'integer',
					'default' => 3,
				),
				'textShadowBlur'      => array(
					'type'    => 'integer',
					'default' => 2,
				),
				'textShadowColor'     => array(
					'type'    => 'string',
					'default' => 'rgba(0,0,0,0.5)',
				),

				/* ── Colours ──────────────────────────────────────────────── */
				'textColor'           => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Button text colour (normal). Default is #fff.',
				),
				'textHoverColor'      => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Button text colour (hover).',
				),

				/* ── Background ───────────────────────────────────────────── */
				'bgColor'             => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Button background colour (normal). Default is #6f14f1 (purple).',
				),
				'bgHoverColor'        => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Button background colour (hover).',
				),

				/* ── Border ───────────────────────────────────────────────── */
				'border'              => array(
					'type'        => 'object',
					'description' => 'Button border (normal) {type,color,width:{top,right,bottom,left,unit}}.',
				),
				'borderHoverColor'    => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Border colour on hover (overrides border colour).',
				),

				/* ── Border radius ────────────────────────────────────────── */
				'borderRadius'        => array(
					'type'        => 'object',
					'description' => 'Button border radius {top,bottom,left,right,unit}.',
				),

				/* ── Box shadow ───────────────────────────────────────────── */
				'enableBoxShadow'     => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Enable button box shadow.',
				),
				'boxShadowH'          => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'boxShadowV'          => array(
					'type'    => 'integer',
					'default' => 4,
				),
				'boxShadowBlur'       => array(
					'type'    => 'integer',
					'default' => 8,
				),
				'boxShadowSpread'     => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'boxShadowColor'      => array(
					'type'    => 'string',
					'default' => 'rgba(0,0,0,0.40)',
				),

				/* ── Padding ──────────────────────────────────────────────── */
				'padding'             => array(
					'type'        => 'object',
					'description' => 'Button inner padding {top,bottom,left,right,unit}.',
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
					'type'    => 'string',
					'default' => '',
				),
				'globalBgHoverColor'  => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Global: Border ──────────────────────────────────────── */
				'globalBorder'        => array( 'type' => 'object' ),
				'globalBorderHover'   => array( 'type' => 'object' ),

				/* ── Global: Border radius ───────────────────────────────── */
				'globalBRadius'       => array( 'type' => 'object' ),
				'globalBRadiusHover'  => array( 'type' => 'object' ),

				/* ── Global: Box shadow ──────────────────────────────────── */
				'globalBShadow'       => array( 'type' => 'object' ),
				'globalBShadowHover'  => array( 'type' => 'object' ),

				/* ── Transform: Rotate ───────────────────────────────────── */
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

				/* ── Transform: Offset ───────────────────────────────────── */
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

				/* ── Transform: Scale ────────────────────────────────────── */
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

				/* ── Transform: Skew ─────────────────────────────────────── */
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

				/* ── Transform: Flip ─────────────────────────────────────── */
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

				/* ── Raw override ───────────────────────────────────────── */
				'settings'            => array(
					'type'        => 'object',
					'description' => 'Raw attribute overrides merged directly into the block. Keys must match internal attribute names (e.g. btxt, bLink, btColor, btBg, bBord, brad, btPad, biSize, btshadow).',
				),
				'fontFamily'          => array(
					'type'        => 'string',
					'description' => 'Font family name (e.g. "Inter", "Roboto", "Playfair Display"). When inspecting a URL via nexter-blocks/inspect-page, pass the returned fonts[].family value verbatim.',
					'default'     => '',
				),
				'fontType'            => array(
					'type'        => 'string',
					'description' => 'Font category — "sans-serif", "serif", "display", "handwriting", or "monospace".',
					'default'     => '',
				),
				'customFont'          => array(
					'type'        => 'string',
					'description' => 'Custom (non-Google) font name. Overrides fontFamily.',
					'default'     => '',
				),
				'fontWeight'          => array(
					'type'        => 'string',
					'description' => 'Font weight as a string ("100"..."900"). Embedded inside every typography group\'s fontFamily.fontWeight on this block. For per-group control, use the settings raw override or sprout/update-element.',
					'default'     => '',
				),
				'textDecoration'      => array(
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

		'execute_callback'    => 'tpgb_mcp_add_button_core_ability',
		'permission_callback' => 'tpgb_mcp_add_button_core_permission',
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
 * Permission callback for the add-button-core ability.
 *
 * @param array|null $input Ability input arguments.
 * @return bool True when the current user may insert the block.
 */
function tpgb_mcp_add_button_core_permission( ?array $input = null ): bool {
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
 * Build a Nexter Blocks spacing attribute from {top,bottom,left,right,unit}.
 *
 * @param array $v Raw spacing values.
 * @return array Spacing attribute structured for the block.
 */
function tpgb_mcp_btncore_spacing( array $v ): array {
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
function tpgb_mcp_btncore_border( array $b ): array {
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
function tpgb_mcp_btncore_radius( array $r ): array {
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
function tpgb_mcp_btncore_bshadow( array $s ): array {
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
function tpgb_mcp_btncore_bg( string $color ): array {
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
function tpgb_mcp_btncore_needs_wrapper( array $attrs ): bool {
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

// -------------------------------------------------------------------------
// EXECUTE CALLBACK
// -------------------------------------------------------------------------

/**
 * Execute callback: insert a tpgb/tp-button-core block into a post.
 *
 * @param array $input Ability input arguments.
 * @return array|WP_Error Ability result or error on failure.
 */
function tpgb_mcp_add_button_core_ability( array $input ) {

	if ( ! defined( 'TPGB_VERSION' ) && ! defined( 'TPGBP_VERSION' ) ) {
		return new WP_Error( 'nexter_blocks_missing', __( 'Nexter Blocks must be active.', 'the-plus-addons-for-block-editor' ) );
	}

	$block_name = 'tpgb/tp-button-core';

	if ( ! tpgb_mcp_has_registered_block( $block_name ) ) {
		return new WP_Error( 'block_missing', __( 'tpgb/tp-button-core is not registered.', 'the-plus-addons-for-block-editor' ) );
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
	// Build attributes (internal short names).
	// ---------------------------------------------------------------------
	$block_id = tpgb_mcp_generate_block_id();
	$attrs    = array( 'block_id' => $block_id );

	/* ── Preset ───────────────────────────────────────────────────────── */
	$has_preset = false;
	$preset_key = sanitize_text_field( $input['buttonPreset'] ?? '' );
	if ( '' !== $preset_key ) {
		$validated = tpgb_mcp_validate_button_preset( $preset_key );
		if ( is_wp_error( $validated ) ) {
			return $validated;
		}
		tpgb_mcp_apply_button_preset( $attrs, $validated, 'direct' );
		$has_preset = true;
	}

	/* ── Content ──────────────────────────────────────────────────────── */
	$btn_text = isset( $input['btnText'] ) && '' !== $input['btnText']
		? tpgb_mcp_clean_text( $input['btnText'] )
		: 'Click Here';

	if ( 'Click Here' !== $btn_text ) {
		$attrs['btxt']   = $btn_text;
		$attrs['exbtxt'] = wp_strip_all_tags( $btn_text );
	}

	/* ── Link ─────────────────────────────────────────────────────────── */
	$link_url = $input['linkUrl'] ?? '#';
	if ( '#' !== $link_url || ! empty( $input['linkTarget'] ) || ! empty( $input['linkNofollow'] ) ) {
		$attrs['bLink'] = array(
			'url'      => esc_url_raw( $link_url ),
			'target'   => '_blank' === ( $input['linkTarget'] ?? '' ) ? '_blank' : '',
			'nofollow' => ! empty( $input['linkNofollow'] ) ? 'on' : '',
		);
	}

	/* ── Alignment ────────────────────────────────────────────────────── */
	if ( ! empty( $input['alignment'] ) && 'left' !== $input['alignment'] ) {
		$attrs['bAlign'] = array(
			'md' => sanitize_text_field( $input['alignment'] ),
			'sm' => '',
			'xs' => '',
		);
	}

	/* ── Icon ─────────────────────────────────────────────────────────── */
	$icon_type = sanitize_key( $input['iconType'] ?? 'none' );
	if ( 'none' !== $icon_type ) {
		$attrs['biType'] = $icon_type;

		if ( 'fontAwesome' === $icon_type && ! empty( $input['iconClass'] ) ) {
			$attrs['bIcon'] = sanitize_text_field( $input['iconClass'] );
		}

		// Icon position: 0 = before, 1 = after (internal mapping).
		if ( ! empty( $input['iconPosition'] ) ) {
			$pos = 'before' === $input['iconPosition'] ? '0' : '1';
			if ( '1' !== $pos ) { // Default is after (1), so only set when before.
				$attrs['bipos'] = $pos;
			}
		}

		if ( ! empty( $input['iconSpacing'] ) ) {
			$attrs['bispac'] = array(
				'md'   => (string) absint( $input['iconSpacing'] ),
				'unit' => 'px',
			); }
		if ( ! empty( $input['iconSize'] ) ) {
			$attrs['biSize'] = array(
				'md'   => (string) absint( $input['iconSize'] ),
				'unit' => 'px',
			); }
	}

	/* ── Typography ───────────────────────────────────────────────────── */
	if ( ! $has_preset && ! empty( $input['enableTypography'] ) ) {
		$b_typo = array(
			'openTypography' => 1,
			'size'           => array(
				'md'   => ! empty( $input['typoSize'] ) ? (string) absint( $input['typoSize'] ) : '',
				'unit' => 'px',
			),
			'height'         => '',
			'spacing'        => '',
			'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
		);
		if ( ! empty( $input['typoGlobalPreset'] ) ) {
			$b_typo['globalTypo'] = sanitize_text_field( $input['typoGlobalPreset'] );
		}
		$attrs['bTypo'] = $b_typo;
	}

	/* ── Text shadow ──────────────────────────────────────────────────── */
	if ( ! $has_preset && ! empty( $input['enableTextShadow'] ) ) {
		$attrs['tShadow'] = array(
			'openShadow' => true,
			'typeShadow' => 'text-shadow',
			'horizontal' => intval( $input['textShadowH'] ?? 2 ),
			'vertical'   => intval( $input['textShadowV'] ?? 3 ),
			'blur'       => absint( $input['textShadowBlur'] ?? 2 ),
			'color'      => sanitize_text_field( $input['textShadowColor'] ?? 'rgba(0,0,0,0.5)' ),
		);
	}

	/* ── Colours (only set when non-default) ──────────────────────────── */
	if ( ! $has_preset && ! empty( $input['textColor'] ) && '#fff' !== $input['textColor'] ) {
		$attrs['btColor'] = sanitize_text_field( $input['textColor'] );
	}
	if ( ! $has_preset && ! empty( $input['textHoverColor'] ) ) {
		$attrs['bthColor'] = sanitize_text_field( $input['textHoverColor'] );
	}

	/* ── Background ───────────────────────────────────────────────────── */
	if ( ! $has_preset && ! empty( $input['bgColor'] ) ) {
		$attrs['btBg'] = tpgb_mcp_btncore_bg( $input['bgColor'] );
	}
	if ( ! $has_preset && ! empty( $input['bgHoverColor'] ) ) {
		$attrs['bthBg'] = tpgb_mcp_btncore_bg( $input['bgHoverColor'] );
	}

	/* ── Border ───────────────────────────────────────────────────────── */
	if ( ! $has_preset && ! empty( $input['border'] ) ) {
		$attrs['bBord'] = tpgb_mcp_btncore_border( $input['border'] ); }
	if ( ! $has_preset && ! empty( $input['borderHoverColor'] ) ) {
		$attrs['bthBColor'] = sanitize_text_field( $input['borderHoverColor'] ); }

	/* ── Border radius ────────────────────────────────────────────────── */
	if ( ! $has_preset && ! empty( $input['borderRadius'] ) ) {
		$attrs['brad'] = tpgb_mcp_btncore_radius( $input['borderRadius'] ); }

	/* ── Box shadow ───────────────────────────────────────────────────── */
	if ( ! $has_preset && ! empty( $input['enableBoxShadow'] ) ) {
		$attrs['btshadow'] = array(
			'openShadow' => true,
			'inset'      => 0,
			'horizontal' => (string) intval( $input['boxShadowH'] ?? 0 ),
			'vertical'   => (string) intval( $input['boxShadowV'] ?? 4 ),
			'blur'       => (string) absint( $input['boxShadowBlur'] ?? 8 ),
			'spread'     => (string) intval( $input['boxShadowSpread'] ?? 0 ),
			'color'      => sanitize_text_field( $input['boxShadowColor'] ?? 'rgba(0,0,0,0.40)' ),
		);
	}

	/* ── Padding ──────────────────────────────────────────────────────── */
	if ( ! $has_preset && ! empty( $input['padding'] ) ) {
		$attrs['btPad'] = tpgb_mcp_btncore_spacing( $input['padding'] ); }

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

	/* ── Global: Spacing ──────────────────────────────────────────────── */
	if ( ! empty( $input['globalMargin'] ) ) {
		$attrs['globalMargin'] = tpgb_mcp_btncore_spacing( $input['globalMargin'] );  }
	if ( ! empty( $input['globalPadding'] ) ) {
		$attrs['globalPadding'] = tpgb_mcp_btncore_spacing( $input['globalPadding'] ); }

	/* ── Global: Background ───────────────────────────────────────────── */
	if ( ! empty( $input['globalBgColor'] ) ) {
		$attrs['globalBg'] = tpgb_mcp_btncore_bg( $input['globalBgColor'] );      }
	if ( ! empty( $input['globalBgHoverColor'] ) ) {
		$attrs['globalBgHover'] = tpgb_mcp_btncore_bg( $input['globalBgHoverColor'] ); }

	/* ── Global: Border ───────────────────────────────────────────────── */
	if ( ! empty( $input['globalBorder'] ) ) {
		$attrs['globalBorder'] = tpgb_mcp_btncore_border( $input['globalBorder'] );      }
	if ( ! empty( $input['globalBorderHover'] ) ) {
		$attrs['globalBorderHover'] = tpgb_mcp_btncore_border( $input['globalBorderHover'] ); }

	/* ── Global: Border radius ────────────────────────────────────────── */
	if ( ! empty( $input['globalBRadius'] ) ) {
		$attrs['globalBRadius'] = tpgb_mcp_btncore_radius( $input['globalBRadius'] );      }
	if ( ! empty( $input['globalBRadiusHover'] ) ) {
		$attrs['globalBRadiusHover'] = tpgb_mcp_btncore_radius( $input['globalBRadiusHover'] ); }

	/* ── Global: Box shadow ───────────────────────────────────────────── */
	if ( ! empty( $input['globalBShadow'] ) ) {
		$attrs['globalBShadow'] = tpgb_mcp_btncore_bshadow( $input['globalBShadow'] );      }
	if ( ! empty( $input['globalBShadowHover'] ) ) {
		$attrs['globalBShadowHover'] = tpgb_mcp_btncore_bshadow( $input['globalBShadowHover'] ); }

	/* ── Transform: Rotate ────────────────────────────────────────────── */
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

	/* ── Transform: Offset ────────────────────────────────────────────── */
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

	/* ── Transform: Scale ─────────────────────────────────────────────── */
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

	/* ── Transform: Skew ──────────────────────────────────────────────── */
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
	if ( tpgb_mcp_btncore_needs_wrapper( $attrs ) ) {
		$attrs['tpgbDisrule'] = true;
	}

	/* ── Raw settings override ────────────────────────────────────────── */
	tpgb_mcp_apply_typo_decoration( $attrs, $input );
	$attrs = tpgb_mcp_merge_block_settings( $attrs, $input['settings'] ?? array() );

	// ---------------------------------------------------------------------
	// Build, insert, save (dynamic block — no innerHTML needed).
	// ---------------------------------------------------------------------
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
		return $save_result;
	}

	return array(
		'block_id'   => $block_id,
		'block_name' => $block_name,
		'post_id'    => $post_id,
	);
}
