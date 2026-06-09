<?php
/**
 * Ability: Add Nexter Blocks Number Counter (tpgb/tp-number-counter) to a Gutenberg page.
 *
 * @package The_Plus_Addons_For_Block_Editor
 * @since   1.3.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_register_ability(
	'nexter-blocks/add-tpgb-number-counter',
	array(
		'label'               => __( 'Add Nexter Blocks Number Counter', 'the-plus-addons-for-block-editor' ),
		'description'         => __( 'Adds the Nexter Blocks Number Counter block (tpgb/tp-number-counter) — an animated counter that counts up from a start value to a target value when scrolled into view. Perfect for showcasing statistics, achievements, or metrics. Supports 2 style presets (icon+counter side-by-side), numeration format, pre/post symbols (like $ or %), icon/image/SVG, link wrapper, and comprehensive styling. This is a dynamic block.', 'the-plus-addons-for-block-editor' ),
		'category'            => 'nexter-blocks',

		'input_schema'        => array(
			'type'                 => 'object',
			'properties'           => array(

				/* ── Core ─────────────────────────────────────────────────── */
				'post_id'               => array( 'type' => 'integer' ),
				'position'              => array(
					'type'    => 'integer',
					'default' => -1,
				),
				'parent_block_id'       => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Style ────────────────────────────────────────────────── */
				'style'                 => array(
					'type'    => 'string',
					'enum'    => array( 'style-1', 'style-2' ),
					'default' => 'style-1',
				),
				'alignmentStyle1'       => array(
					'type'        => 'string',
					'enum'        => array( 'text-left', 'text-center', 'text-right' ),
					'default'     => 'text-center',
					'description' => 'Alignment for style-1.',
				),
				'alignmentStyle2'       => array(
					'type'        => 'string',
					'enum'        => array( 'text-left', 'text-center', 'text-right' ),
					'default'     => 'text-left',
					'description' => 'Alignment for style-2.',
				),

				/* ── Content ──────────────────────────────────────────────── */
				'title'                 => array(
					'type'        => 'string',
					'default'     => 'Awards Won',
					'description' => 'Counter title/label.',
				),
				'startValue'            => array(
					'type'        => 'string',
					'default'     => '0',
					'description' => 'Starting number.',
				),
				'targetValue'           => array(
					'type'        => 'string',
					'default'     => '999',
					'description' => 'Final/target number to count up to.',
				),

				/* ── Animation settings ───────────────────────────────────── */
				'numberGap'             => array(
					'type'        => 'string',
					'default'     => '5',
					'description' => 'Counter increment step.',
				),
				'timeDelay'             => array(
					'type'        => 'string',
					'default'     => '5',
					'description' => 'Counter animation duration/delay.',
				),
				'numeration'            => array(
					'type'        => 'string',
					'enum'        => array( 'default', 'comma', 'separator', 'decimal', 'no-format' ),
					'description' => 'Number format style.',
					'default'     => 'default',
				),

				/* ── Symbols ──────────────────────────────────────────────── */
				'postSymbol'            => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Text/symbol after number (e.g. "%", "+", "K").',
				),
				'preSymbol'             => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Text/symbol before number (e.g. "$", "€").',
				),
				'symbolPosition'        => array(
					'type'    => 'string',
					'enum'    => array( 'before', 'after' ),
					'default' => 'after',
				),

				/* ── Link ─────────────────────────────────────────────────── */
				'linkUrl'               => array(
					'type'    => 'string',
					'default' => '#',
				),
				'linkTarget'            => array(
					'type'    => 'string',
					'enum'    => array( '_self', '_blank' ),
					'default' => '_self',
				),
				'linkNofollow'          => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'ariaLabel'             => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Icon/Image/SVG ───────────────────────────────────────── */
				'iconType'              => array(
					'type'    => 'string',
					'enum'    => array( 'none', 'icon', 'image', 'svg' ),
					'default' => 'icon',
				),
				'iconClass'             => array(
					'type'    => 'string',
					'default' => 'fas fa-award',
				),
				'imageUrl'              => array(
					'type'    => 'string',
					'default' => '',
				),
				'imageId'               => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'imageSize'             => array(
					'type'    => 'string',
					'default' => 'thumbnail',
				),
				'svgUrl'                => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Title styling ────────────────────────────────────────── */
				'enableTitleTypo'       => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'titleTypoSize'         => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'titleColor'            => array(
					'type'    => 'string',
					'default' => '',
				),
				'titleHoverColor'       => array(
					'type'    => 'string',
					'default' => '',
				),
				'titleTopSpace'         => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'titleBottomSpace'      => array(
					'type'    => 'integer',
					'default' => 0,
				),

				/* ── Digit (counter number) styling ───────────────────────── */
				'enableDigitTypo'       => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'digitTypoSize'         => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'digitColor'            => array(
					'type'    => 'string',
					'default' => '',
				),
				'digitHoverColor'       => array(
					'type'    => 'string',
					'default' => '',
				),
				'digitTopSpace'         => array(
					'type'    => 'integer',
					'default' => 0,
				),

				/* ── Symbol styling ───────────────────────────────────────── */
				'enableSymbolTypo'      => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'symbolTypoSize'        => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'symbolColor'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'symbolHoverColor'      => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Icon styling ─────────────────────────────────────────── */
				'iconDisplayStyle'      => array(
					'type'    => 'string',
					'enum'    => array( 'none', 'stacked', 'framed' ),
					'default' => 'none',
				),
				'iconSize'              => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'iconWidth'             => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'iconColor'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'iconHoverColor'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'iconBgColor'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'iconBgHoverColor'      => array(
					'type'    => 'string',
					'default' => '',
				),
				'iconBorderColor'       => array(
					'type'    => 'string',
					'default' => '',
				),
				'iconBorderHoverColor'  => array(
					'type'    => 'string',
					'default' => '',
				),
				'iconBorderRadius'      => array( 'type' => 'object' ),
				'iconBorderRadiusHover' => array( 'type' => 'object' ),
				'enableIconShadow'      => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'iconShadowH'           => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'iconShadowV'           => array(
					'type'    => 'integer',
					'default' => 4,
				),
				'iconShadowBlur'        => array(
					'type'    => 'integer',
					'default' => 8,
				),
				'iconShadowSpread'      => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'iconShadowColor'       => array(
					'type'    => 'string',
					'default' => 'rgba(0,0,0,0.40)',
				),

				/* ── Image width ──────────────────────────────────────────── */
				'imageWidth'            => array(
					'type'    => 'integer',
					'default' => 0,
				),

				/* ── SVG styling ──────────────────────────────────────────── */
				'svgDrawType'           => array(
					'type'    => 'string',
					'enum'    => array( 'delayed', 'sync', 'oneByOne' ),
					'default' => 'delayed',
				),
				'svgDuration'           => array(
					'type'    => 'string',
					'default' => '90',
				),
				'svgMaxWidth'           => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'svgStrokeColor'        => array(
					'type'    => 'string',
					'default' => '#000000',
				),
				'svgFillColor'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'svgStrokeHoverColor'   => array(
					'type'    => 'string',
					'default' => '',
				),
				'svgFillHoverColor'     => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Box styling ──────────────────────────────────────────── */
				'boxPadding'            => array( 'type' => 'object' ),
				'boxBgColor'            => array(
					'type'    => 'string',
					'default' => '',
				),
				'boxBgHoverColor'       => array(
					'type'    => 'string',
					'default' => '',
				),
				'boxBorder'             => array( 'type' => 'object' ),
				'boxBorderHover'        => array( 'type' => 'object' ),
				'boxBorderRadius'       => array( 'type' => 'object' ),
				'boxBorderRadiusHover'  => array( 'type' => 'object' ),
				'enableBoxShadow'       => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'boxShadowH'            => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'boxShadowV'            => array(
					'type'    => 'integer',
					'default' => 4,
				),
				'boxShadowBlur'         => array(
					'type'    => 'integer',
					'default' => 8,
				),
				'boxShadowSpread'       => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'boxShadowColor'        => array(
					'type'    => 'string',
					'default' => 'rgba(0,0,0,0.40)',
				),
				'verticalCenter'        => array(
					'type'    => 'boolean',
					'default' => false,
				),

				/* ── Scroll animation ────────────────────────────────────── */
				'scrollAnimation'       => array(
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
				'animDuration'          => array(
					'type'    => 'string',
					'enum'    => array( '', 'slow', 'normal', 'fast' ),
					'default' => '',
				),
				'animDelay'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'animEasing'            => array(
					'type'    => 'string',
					'enum'    => array( '', 'linear', 'ease', 'ease-in', 'ease-out', 'ease-in-out' ),
					'default' => '',
				),

				/* ── Advanced ─────────────────────────────────────────────── */
				'hideDesktop'           => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'hideTablet'            => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'hideMobile'            => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'globalClasses'         => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalId'              => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalCustomCss'       => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalWidth'           => array(
					'type'    => 'string',
					'enum'    => array( '', 'inline', 'full', 'custom' ),
					'default' => '',
				),
				'globalZindex'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalPosition'        => array(
					'type'    => 'string',
					'enum'    => array( '', 'relative', 'absolute', 'fixed', 'sticky' ),
					'default' => '',
				),
				'transitionDuration'    => array(
					'type'    => 'string',
					'default' => '',
				),
				'transitionFunction'    => array(
					'type'    => 'string',
					'enum'    => array( '', 'ease', 'ease-in', 'ease-out', 'ease-in-out', 'linear' ),
					'default' => '',
				),
				'transitionOrigin'      => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalMargin'          => array( 'type' => 'object' ),
				'globalPadding'         => array( 'type' => 'object' ),
				'globalBgColor'         => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalBgHoverColor'    => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalBorder'          => array( 'type' => 'object' ),
				'globalBorderHover'     => array( 'type' => 'object' ),
				'globalBRadius'         => array( 'type' => 'object' ),
				'globalBRadiusHover'    => array( 'type' => 'object' ),
				'globalBShadow'         => array( 'type' => 'object' ),
				'globalBShadowHover'    => array( 'type' => 'object' ),

				'rotateDeg'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotateX'               => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotateY'               => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotatePerspective'     => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotateDegHover'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotateXHover'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotateYHover'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotatePersHover'       => array(
					'type'    => 'string',
					'default' => '',
				),
				'offsetX'               => array(
					'type'    => 'string',
					'default' => '',
				),
				'offsetY'               => array(
					'type'    => 'string',
					'default' => '',
				),
				'offsetZ'               => array(
					'type'    => 'string',
					'default' => '',
				),
				'offsetXHover'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'offsetYHover'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'offsetZHover'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'scaleValue'            => array(
					'type'    => 'string',
					'default' => '',
				),
				'scaleKeepRatio'        => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'scaleValueHover'       => array(
					'type'    => 'string',
					'default' => '',
				),
				'skewX'                 => array(
					'type'    => 'string',
					'default' => '',
				),
				'skewY'                 => array(
					'type'    => 'string',
					'default' => '',
				),
				'skewXHover'            => array(
					'type'    => 'string',
					'default' => '',
				),
				'skewYHover'            => array(
					'type'    => 'string',
					'default' => '',
				),
				'flipHorizontal'        => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'flipVertical'          => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'flipHorizontalHover'   => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'flipVerticalHover'     => array(
					'type'    => 'boolean',
					'default' => false,
				),

				'settings'              => array( 'type' => 'object' ),
				'fontFamily'            => array(
					'type'        => 'string',
					'description' => 'Font family name (e.g. "Inter", "Roboto", "Playfair Display"). When inspecting a URL via nexter-blocks/inspect-page, pass the returned fonts[].family value verbatim.',
					'default'     => '',
				),
				'fontType'              => array(
					'type'        => 'string',
					'description' => 'Font category — "sans-serif", "serif", "display", "handwriting", or "monospace".',
					'default'     => '',
				),
				'customFont'            => array(
					'type'        => 'string',
					'description' => 'Custom (non-Google) font name. Overrides fontFamily.',
					'default'     => '',
				),
				'fontWeight'            => array(
					'type'        => 'string',
					'description' => 'Font weight as a string ("100"..."900"). Embedded inside every typography group\'s fontFamily.fontWeight on this block. For per-group control, use the settings raw override or sprout/update-element.',
					'default'     => '',
				),
				'textDecoration'        => array(
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

		'execute_callback'    => 'tpgb_mcp_add_numcounter_ability',
		'permission_callback' => 'tpgb_mcp_add_numcounter_permission',
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
 * Permission callback for the add-number-counter ability.
 *
 * @param array|null $input Ability input arguments.
 * @return bool True when the current user may insert the block.
 */
function tpgb_mcp_add_numcounter_permission( ?array $input = null ): bool {
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
function tpgb_mcp_ncnt_spacing( array $v ): array {
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
function tpgb_mcp_ncnt_border( array $b ): array {
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
function tpgb_mcp_ncnt_radius( array $r ): array {
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
function tpgb_mcp_ncnt_bshadow( array $s ): array {
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
function tpgb_mcp_ncnt_bg( string $color ): array {
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
function tpgb_mcp_ncnt_needs_wrapper( array $attrs ): bool {
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
 * Execute callback: insert a tpgb/tp-number-counter block into a post.
 *
 * @param array $input Ability input arguments.
 * @return array|WP_Error Ability result or error on failure.
 */
function tpgb_mcp_add_numcounter_ability( array $input ) {

	if ( ! defined( 'TPGB_VERSION' ) && ! defined( 'TPGBP_VERSION' ) ) {
		return new WP_Error( 'nexter_blocks_missing', __( 'Nexter Blocks must be active.', 'the-plus-addons-for-block-editor' ) );
	}

	$block_name = 'tpgb/tp-number-counter';
	if ( ! tpgb_mcp_has_registered_block( $block_name ) ) {
		return new WP_Error( 'block_missing', __( 'tpgb/tp-number-counter is not registered.', 'the-plus-addons-for-block-editor' ) );
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

	/* ── Style ────────────────────────────────────────────────────────── */
	$style = sanitize_key( $input['style'] ?? 'style-1' );
	if ( 'style-1' !== $style ) {
		$attrs['style'] = $style; }

	if ( ! empty( $input['alignmentStyle1'] ) && 'text-center' !== $input['alignmentStyle1'] ) {
		$attrs['style1Align'] = sanitize_text_field( $input['alignmentStyle1'] );
	}
	if ( ! empty( $input['alignmentStyle2'] ) && 'text-left' !== $input['alignmentStyle2'] ) {
		$attrs['style2Align'] = sanitize_text_field( $input['alignmentStyle2'] );
	}

	/* ── Content ──────────────────────────────────────────────────────── */
	if ( ! empty( $input['title'] ) && 'Awards Won' !== $input['title'] ) {
		$attrs['title'] = tpgb_mcp_clean_text( $input['title'] ); }
	if ( ! empty( $input['startValue'] ) && '0' !== $input['startValue'] ) {
		$attrs['startValue'] = sanitize_text_field( $input['startValue'] ); }
	if ( ! empty( $input['targetValue'] ) && '999' !== $input['targetValue'] ) {
		$attrs['numValue'] = sanitize_text_field( $input['targetValue'] ); }

	/* ── Animation ────────────────────────────────────────────────────── */
	if ( ! empty( $input['numberGap'] ) && '5' !== $input['numberGap'] ) {
		$attrs['numGap'] = sanitize_text_field( $input['numberGap'] ); }
	if ( ! empty( $input['timeDelay'] ) && '5' !== $input['timeDelay'] ) {
		$attrs['timeDelay'] = sanitize_text_field( $input['timeDelay'] ); }
	if ( ! empty( $input['numeration'] ) && 'default' !== $input['numeration'] ) {
		$attrs['numeration'] = sanitize_key( $input['numeration'] ); }

	/* ── Symbols ──────────────────────────────────────────────────────── */
	if ( ! empty( $input['postSymbol'] ) ) {
		$attrs['symbol'] = sanitize_text_field( $input['postSymbol'] ); }
	if ( ! empty( $input['preSymbol'] ) ) {
		$attrs['preSymbol'] = sanitize_text_field( $input['preSymbol'] ); }
	if ( ! empty( $input['symbolPosition'] ) && 'after' !== $input['symbolPosition'] ) {
		$attrs['symbolPos'] = sanitize_key( $input['symbolPosition'] );
	}

	/* ── Link ─────────────────────────────────────────────────────────── */
	if ( ! empty( $input['linkUrl'] ) && '#' !== $input['linkUrl'] ) {
		$attrs['linkURL'] = array(
			'url'      => esc_url_raw( $input['linkUrl'] ),
			'target'   => '_blank' === ( $input['linkTarget'] ?? '' ) ? '_blank' : '',
			'nofollow' => ! empty( $input['linkNofollow'] ) ? 'on' : '',
		);
	}
	if ( ! empty( $input['ariaLabel'] ) ) {
		$attrs['ariaLabel'] = sanitize_text_field( $input['ariaLabel'] ); }

	/* ── Icon/Image/SVG ───────────────────────────────────────────────── */
	$icon_type = sanitize_key( $input['iconType'] ?? 'icon' );
	if ( 'icon' !== $icon_type ) {
		$attrs['iconType'] = $icon_type; }

	if ( 'icon' === $icon_type && ! empty( $input['iconClass'] ) && 'fas fa-award' !== $input['iconClass'] ) {
		$attrs['iconStore'] = sanitize_text_field( $input['iconClass'] );
	}
	if ( 'image' === $icon_type ) {
		$img = array( 'url' => '' );
		if ( ! empty( $input['imageId'] ) ) {
			$img['id'] = absint( $input['imageId'] );
			$src       = wp_get_attachment_image_url( $img['id'], 'full' );
			if ( $src ) {
				$img['url'] = $src; }
		} elseif ( ! empty( $input['imageUrl'] ) ) {
			$img['url'] = esc_url_raw( $input['imageUrl'] );
		}
		if ( ! empty( $img['url'] ) ) {
			$attrs['imagestore'] = $img; }
		if ( ! empty( $input['imageSize'] ) && 'thumbnail' !== $input['imageSize'] ) {
			$attrs['imageSize'] = sanitize_key( $input['imageSize'] ); }
	}
	if ( 'svg' === $icon_type && ! empty( $input['svgUrl'] ) ) {
		$attrs['svgIcon'] = array( 'url' => esc_url_raw( $input['svgUrl'] ) );
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
		$attrs['titleNmlColor'] = sanitize_text_field( $input['titleColor'] ); }
	if ( ! empty( $input['titleHoverColor'] ) ) {
		$attrs['titleHvrColor'] = sanitize_text_field( $input['titleHoverColor'] ); }
	if ( ! empty( $input['titleTopSpace'] ) ) {
		$attrs['titleTopSpace'] = array(
			'md'   => (string) absint( $input['titleTopSpace'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['titleBottomSpace'] ) ) {
		$attrs['titleBottomSpace'] = array(
			'md'   => (string) absint( $input['titleBottomSpace'] ),
			'unit' => 'px',
		); }

	/* ── Digit styling ────────────────────────────────────────────────── */
	if ( ! empty( $input['enableDigitTypo'] ) ) {
		$attrs['digitTypo'] = array(
			'openTypography' => 1,
			'size'           => array(
				'md'   => ! empty( $input['digitTypoSize'] ) ? (string) absint( $input['digitTypoSize'] ) : '',
				'unit' => 'px',
			),
			'height'         => '',
			'spacing'        => '',
			'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
		);
	}
	if ( ! empty( $input['digitColor'] ) ) {
		$attrs['digitNmlColor'] = sanitize_text_field( $input['digitColor'] ); }
	if ( ! empty( $input['digitHoverColor'] ) ) {
		$attrs['digitHvrColor'] = sanitize_text_field( $input['digitHoverColor'] ); }
	if ( ! empty( $input['digitTopSpace'] ) ) {
		$attrs['digitTopSpace'] = array(
			'md'   => (string) absint( $input['digitTopSpace'] ),
			'unit' => 'px',
		); }

	/* ── Symbol styling ───────────────────────────────────────────────── */
	if ( ! empty( $input['enableSymbolTypo'] ) ) {
		$attrs['symbolTypo'] = array(
			'openTypography' => 1,
			'size'           => array(
				'md'   => ! empty( $input['symbolTypoSize'] ) ? (string) absint( $input['symbolTypoSize'] ) : '',
				'unit' => 'px',
			),
			'height'         => '',
			'spacing'        => '',
			'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
		);
	}
	if ( ! empty( $input['symbolColor'] ) ) {
		$attrs['symbolNmlColor'] = sanitize_text_field( $input['symbolColor'] ); }
	if ( ! empty( $input['symbolHoverColor'] ) ) {
		$attrs['symbolHvrColor'] = sanitize_text_field( $input['symbolHoverColor'] ); }

	/* ── Icon styling ─────────────────────────────────────────────────── */
	if ( ! empty( $input['iconDisplayStyle'] ) && 'none' !== $input['iconDisplayStyle'] ) {
		$attrs['iconStyle'] = sanitize_key( $input['iconDisplayStyle'] ); }
	if ( ! empty( $input['iconSize'] ) ) {
		$attrs['iconSize'] = array(
			'md'   => (string) absint( $input['iconSize'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['iconWidth'] ) ) {
		$attrs['iconWidth'] = array(
			'md'   => (string) absint( $input['iconWidth'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['iconColor'] ) ) {
		$attrs['icnNmlColor'] = sanitize_text_field( $input['iconColor'] ); }
	if ( ! empty( $input['iconHoverColor'] ) ) {
		$attrs['icnHvrColor'] = sanitize_text_field( $input['iconHoverColor'] ); }
	if ( ! empty( $input['iconBgColor'] ) ) {
		$attrs['icnNormalBG'] = tpgb_mcp_ncnt_bg( $input['iconBgColor'] ); }
	if ( ! empty( $input['iconBgHoverColor'] ) ) {
		$attrs['icnHoverBG'] = tpgb_mcp_ncnt_bg( $input['iconBgHoverColor'] ); }
	if ( ! empty( $input['iconBorderColor'] ) ) {
		$attrs['nmlBColor'] = sanitize_text_field( $input['iconBorderColor'] ); }
	if ( ! empty( $input['iconBorderHoverColor'] ) ) {
		$attrs['hvrBColor'] = sanitize_text_field( $input['iconBorderHoverColor'] ); }
	if ( ! empty( $input['iconBorderRadius'] ) ) {
		$attrs['nmlIcnBRadius'] = tpgb_mcp_ncnt_radius( $input['iconBorderRadius'] ); }
	if ( ! empty( $input['iconBorderRadiusHover'] ) ) {
		$attrs['hvrIcnBRadius'] = tpgb_mcp_ncnt_radius( $input['iconBorderRadiusHover'] ); }
	if ( ! empty( $input['enableIconShadow'] ) ) {
		$attrs['nmlIcnShadow'] = array(
			'openShadow' => true,
			'inset'      => 0,
			'horizontal' => (string) intval( $input['iconShadowH'] ?? 0 ),
			'vertical'   => (string) intval( $input['iconShadowV'] ?? 4 ),
			'blur'       => (string) absint( $input['iconShadowBlur'] ?? 8 ),
			'spread'     => (string) intval( $input['iconShadowSpread'] ?? 0 ),
			'color'      => sanitize_text_field( $input['iconShadowColor'] ?? 'rgba(0,0,0,0.40)' ),
		);
	}

	/* ── Image width ──────────────────────────────────────────────────── */
	if ( ! empty( $input['imageWidth'] ) ) {
		$attrs['imgWidth'] = array(
			'md'   => (string) absint( $input['imageWidth'] ),
			'unit' => 'px',
		); }

	/* ── SVG styling ──────────────────────────────────────────────────── */
	if ( ! empty( $input['svgDrawType'] ) && 'delayed' !== $input['svgDrawType'] ) {
		$attrs['svgDraw'] = sanitize_key( $input['svgDrawType'] ); }
	if ( ! empty( $input['svgDuration'] ) && '90' !== $input['svgDuration'] ) {
		$attrs['svgDura'] = sanitize_text_field( $input['svgDuration'] ); }
	if ( ! empty( $input['svgMaxWidth'] ) ) {
		$attrs['svgmaxWidth'] = array(
			'md'   => (string) absint( $input['svgMaxWidth'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['svgStrokeColor'] ) && '#000000' !== $input['svgStrokeColor'] ) {
		$attrs['svgstroColor'] = sanitize_text_field( $input['svgStrokeColor'] ); }
	if ( ! empty( $input['svgFillColor'] ) ) {
		$attrs['svgfillColor'] = sanitize_text_field( $input['svgFillColor'] ); }
	if ( ! empty( $input['svgStrokeHoverColor'] ) ) {
		$attrs['svgstroHov'] = sanitize_text_field( $input['svgStrokeHoverColor'] ); }
	if ( ! empty( $input['svgFillHoverColor'] ) ) {
		$attrs['svgfillHov'] = sanitize_text_field( $input['svgFillHoverColor'] ); }

	/* ── Box styling ──────────────────────────────────────────────────── */
	if ( ! empty( $input['boxPadding'] ) ) {
		$attrs['bgPadding'] = tpgb_mcp_ncnt_spacing( $input['boxPadding'] ); }
	if ( ! empty( $input['boxBgColor'] ) ) {
		$attrs['normalBG'] = tpgb_mcp_ncnt_bg( $input['boxBgColor'] ); }
	if ( ! empty( $input['boxBgHoverColor'] ) ) {
		$attrs['hoverBG'] = tpgb_mcp_ncnt_bg( $input['boxBgHoverColor'] ); }
	if ( ! empty( $input['boxBorder'] ) ) {
		$attrs['bgNmlBorder'] = tpgb_mcp_ncnt_border( $input['boxBorder'] ); }
	if ( ! empty( $input['boxBorderHover'] ) ) {
		$attrs['bgHvrBorder'] = tpgb_mcp_ncnt_border( $input['boxBorderHover'] ); }
	if ( ! empty( $input['boxBorderRadius'] ) ) {
		$attrs['bgNmlBRadius'] = tpgb_mcp_ncnt_radius( $input['boxBorderRadius'] ); }
	if ( ! empty( $input['boxBorderRadiusHover'] ) ) {
		$attrs['bgHvrBRadius'] = tpgb_mcp_ncnt_radius( $input['boxBorderRadiusHover'] ); }
	if ( ! empty( $input['enableBoxShadow'] ) ) {
		$attrs['bgNmlShadow'] = array(
			'openShadow' => true,
			'inset'      => 0,
			'horizontal' => (string) intval( $input['boxShadowH'] ?? 0 ),
			'vertical'   => (string) intval( $input['boxShadowV'] ?? 4 ),
			'blur'       => (string) absint( $input['boxShadowBlur'] ?? 8 ),
			'spread'     => (string) intval( $input['boxShadowSpread'] ?? 0 ),
			'color'      => sanitize_text_field( $input['boxShadowColor'] ?? 'rgba(0,0,0,0.40)' ),
		);
	}
	if ( ! empty( $input['verticalCenter'] ) ) {
		$attrs['verticalCenter'] = true; }

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
		$attrs['globalMargin'] = tpgb_mcp_ncnt_spacing( $input['globalMargin'] );  }
	if ( ! empty( $input['globalPadding'] ) ) {
		$attrs['globalPadding'] = tpgb_mcp_ncnt_spacing( $input['globalPadding'] ); }
	if ( ! empty( $input['globalBgColor'] ) ) {
		$attrs['globalBg'] = tpgb_mcp_ncnt_bg( $input['globalBgColor'] );      }
	if ( ! empty( $input['globalBgHoverColor'] ) ) {
		$attrs['globalBgHover'] = tpgb_mcp_ncnt_bg( $input['globalBgHoverColor'] ); }
	if ( ! empty( $input['globalBorder'] ) ) {
		$attrs['globalBorder'] = tpgb_mcp_ncnt_border( $input['globalBorder'] );       }
	if ( ! empty( $input['globalBorderHover'] ) ) {
		$attrs['globalBorderHover'] = tpgb_mcp_ncnt_border( $input['globalBorderHover'] );  }
	if ( ! empty( $input['globalBRadius'] ) ) {
		$attrs['globalBRadius'] = tpgb_mcp_ncnt_radius( $input['globalBRadius'] );      }
	if ( ! empty( $input['globalBRadiusHover'] ) ) {
		$attrs['globalBRadiusHover'] = tpgb_mcp_ncnt_radius( $input['globalBRadiusHover'] ); }
	if ( ! empty( $input['globalBShadow'] ) ) {
		$attrs['globalBShadow'] = tpgb_mcp_ncnt_bshadow( $input['globalBShadow'] );      }
	if ( ! empty( $input['globalBShadowHover'] ) ) {
		$attrs['globalBShadowHover'] = tpgb_mcp_ncnt_bshadow( $input['globalBShadowHover'] ); }

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
	if ( tpgb_mcp_ncnt_needs_wrapper( $attrs ) ) {
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
