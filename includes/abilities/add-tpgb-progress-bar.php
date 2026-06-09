<?php
/**
 * Ability: Add Nexter Blocks Progress Bar (tpgb/tp-progress-bar) to a Gutenberg page.
 *
 * @package The_Plus_Addons_For_Block_Editor
 * @since   1.3.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_register_ability(
	'nexter-blocks/add-tpgb-progress-bar',
	array(
		'label'               => __( 'Add Nexter Blocks Progress Bar', 'the-plus-addons-for-block-editor' ),
		'description'         => __( 'Adds the Nexter Blocks Progress Bar block (tpgb/tp-progress-bar) — an animated skill/progress indicator with 3 layout types: horizontal progress bar, circle/pie chart, and radial progress. Supports title, subtitle, number display, icon/image, customizable colours (fill/empty/separator), height variants, and full animation/transform/advanced settings. This is a dynamic block.', 'the-plus-addons-for-block-editor' ),
		'category'            => 'nexter-blocks',

		'input_schema'        => array(
			'type'                 => 'object',
			'properties'           => array(

				/* ── Core ─────────────────────────────────────────────────── */
				'post_id'             => array( 'type' => 'integer' ),
				'position'            => array(
					'type'    => 'integer',
					'default' => -1,
				),
				'parent_block_id'     => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Layout ───────────────────────────────────────────────── */
				'layoutType'          => array(
					'type'        => 'string',
					'enum'        => array( 'progressbar', 'piechart', 'circle' ),
					'description' => 'Type of progress display.',
					'default'     => 'progressbar',
				),
				'style'               => array(
					'type'        => 'string',
					'description' => 'Style preset for the bar layout e.g. "style-1" through "style-10".',
					'default'     => 'style-1',
				),
				'heightType'          => array(
					'type'        => 'string',
					'enum'        => array( 'small-height', 'medium-height', 'large-height' ),
					'description' => 'Bar height variant (progressbar only).',
					'default'     => 'small-height',
				),
				'pieStyle'            => array(
					'type'        => 'string',
					'default'     => 'pieStyle-1',
					'description' => 'Pie chart style (piechart layout).',
				),
				'circleStyle'         => array(
					'type'        => 'string',
					'default'     => 'style-1',
					'description' => 'Circle progress style (circle layout).',
				),

				/* ── Value ────────────────────────────────────────────────── */
				'value'               => array(
					'type'        => 'string',
					'description' => 'Progress value. For bar = 0-100; for circle = 0-100; for pie = 0-1 (e.g. 0.7 = 70%).',
					'default'     => '69',
				),
				'pieValue'            => array(
					'type'        => 'string',
					'default'     => '0.7',
					'description' => 'Pie chart value 0-1.',
				),
				'showNumber'          => array(
					'type'        => 'boolean',
					'default'     => true,
					'description' => 'Show numeric value.',
				),

				/* ── Pie chart specific ───────────────────────────────────── */
				'pieCircleSize'       => array(
					'type'        => 'string',
					'default'     => '200',
					'description' => 'Pie chart diameter in px.',
				),
				'pieThickness'        => array(
					'type'        => 'string',
					'default'     => '5',
					'description' => 'Pie chart stroke width.',
				),
				'pieFillStyle'        => array(
					'type'    => 'string',
					'enum'    => array( 'normal', 'gradient' ),
					'default' => 'normal',
				),
				'pieColor1'           => array(
					'type'        => 'string',
					'default'     => '#FFA500',
					'description' => 'Pie primary colour.',
				),
				'pieColor2'           => array(
					'type'        => 'string',
					'default'     => '#008000',
					'description' => 'Pie secondary colour (for gradient).',
				),
				'fillReverse'         => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Reverse fill direction.',
				),

				/* ── Content ──────────────────────────────────────────────── */
				'title'               => array(
					'type'    => 'string',
					'default' => 'Web Design',
				),
				'subTitle'            => array(
					'type'    => 'string',
					'default' => 'HTML, CSS and WordPress',
				),
				'prePostSymbol'       => array(
					'type'        => 'string',
					'default'     => '%',
					'description' => 'Symbol shown before/after number.',
				),
				'symbolPosition'      => array(
					'type'    => 'string',
					'enum'    => array( 'beforeNumber', 'afterNumber' ),
					'default' => 'afterNumber',
				),

				/* ── Icon / Image ─────────────────────────────────────────── */
				'iconType'            => array(
					'type'    => 'string',
					'enum'    => array( 'none', 'iconIcon', 'iconImage' ),
					'default' => 'iconIcon',
				),
				'iconLibrary'         => array(
					'type'    => 'string',
					'enum'    => array( 'fontawesome', 'lineawesome' ),
					'default' => 'fontawesome',
				),
				'iconClass'           => array(
					'type'    => 'string',
					'default' => 'fas fa-code',
				),
				'imageUrl'            => array(
					'type'    => 'string',
					'default' => '',
				),
				'imageId'             => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'imageSize'           => array(
					'type'    => 'string',
					'default' => 'thumbnail',
				),
				'imagePosition'       => array(
					'type'    => 'string',
					'enum'    => array( 'beforeTitle', 'afterTitle', 'onTop' ),
					'default' => 'beforeTitle',
				),

				/* ── Bar styling ──────────────────────────────────────────── */
				'topMargin'           => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Progress bar top margin in px.',
				),
				'fillColor'           => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Progress bar fill colour.',
				),
				'emptyColor'          => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Progress bar empty/track colour.',
				),
				'separatorColor'      => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Separator colour (style-dependent).',
				),

				/* ── Title styling ────────────────────────────────────────── */
				'enableTitleTypo'     => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'titleTypoSize'       => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'titleColor'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'titleSpace'          => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Space between title and bar in px.',
				),

				/* ── Subtitle styling ─────────────────────────────────────── */
				'enableSubTitleTypo'  => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'subTitleTypoSize'    => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'subTitleColor'       => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Number styling ───────────────────────────────────────── */
				'enableNumberTypo'    => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'numberTypoSize'      => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'numberColor'         => array(
					'type'    => 'string',
					'default' => '',
				),
				'enablePrePostTypo'   => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'prePostTypoSize'     => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'prePostColor'        => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Icon / Image styling ─────────────────────────────────── */
				'iconColor'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'iconSize'            => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'imageWidth'          => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'imageBorderRadius'   => array(
					'type'        => 'object',
					'description' => 'Image border radius.',
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

				'settings'            => array( 'type' => 'object' ),
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

		'execute_callback'    => 'tpgb_mcp_add_progress_bar_ability',
		'permission_callback' => 'tpgb_mcp_add_progress_bar_permission',
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
 * Permission callback for the add-progress-bar ability.
 *
 * @param array|null $input Ability input arguments.
 * @return bool True when the current user may insert the block.
 */
function tpgb_mcp_add_progress_bar_permission( ?array $input = null ): bool {
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
function tpgb_mcp_pbar_spacing( array $v ): array {
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
function tpgb_mcp_pbar_border( array $b ): array {
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
function tpgb_mcp_pbar_radius( array $r ): array {
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
function tpgb_mcp_pbar_bshadow( array $s ): array {
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
function tpgb_mcp_pbar_bg( string $color ): array {
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
function tpgb_mcp_pbar_needs_wrapper( array $attrs ): bool {
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
 * Execute callback: insert a tpgb/tp-progress-bar block into a post.
 *
 * @param array $input Ability input arguments.
 * @return array|WP_Error Ability result or error on failure.
 */
function tpgb_mcp_add_progress_bar_ability( array $input ) {

	if ( ! defined( 'TPGB_VERSION' ) && ! defined( 'TPGBP_VERSION' ) ) {
		return new WP_Error( 'nexter_blocks_missing', __( 'Nexter Blocks must be active.', 'the-plus-addons-for-block-editor' ) );
	}

	$block_name = 'tpgb/tp-progress-bar';
	if ( ! tpgb_mcp_has_registered_block( $block_name ) ) {
		return new WP_Error( 'block_missing', __( 'tpgb/tp-progress-bar is not registered.', 'the-plus-addons-for-block-editor' ) );
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

	/* ── Layout ───────────────────────────────────────────────────────── */
	if ( ! empty( $input['layoutType'] ) && 'progressbar' !== $input['layoutType'] ) {
		$attrs['layoutType'] = sanitize_key( $input['layoutType'] ); }
	if ( ! empty( $input['style'] ) && 'style-1' !== $input['style'] ) {
		$attrs['styleType'] = sanitize_text_field( $input['style'] ); }
	if ( ! empty( $input['heightType'] ) && 'small-height' !== $input['heightType'] ) {
		$attrs['heightType'] = sanitize_text_field( $input['heightType'] ); }
	if ( ! empty( $input['pieStyle'] ) && 'pieStyle-1' !== $input['pieStyle'] ) {
		$attrs['pieStyleType'] = sanitize_text_field( $input['pieStyle'] ); }
	if ( ! empty( $input['circleStyle'] ) && 'style-1' !== $input['circleStyle'] ) {
		$attrs['circleStyle'] = sanitize_text_field( $input['circleStyle'] ); }

	/* ── Value ────────────────────────────────────────────────────────── */
	if ( ! empty( $input['value'] ) && '69' !== $input['value'] ) {
		$attrs['dynamicValue'] = sanitize_text_field( $input['value'] ); }
	if ( ! empty( $input['pieValue'] ) && '0.7' !== $input['pieValue'] ) {
		$attrs['dynamicPieValue'] = sanitize_text_field( $input['pieValue'] ); }
	if ( isset( $input['showNumber'] ) && ! $input['showNumber'] ) {
		$attrs['dispNumber'] = false; }

	/* ── Pie chart specific ───────────────────────────────────────────── */
	if ( ! empty( $input['pieCircleSize'] ) && '200' !== $input['pieCircleSize'] ) {
		$attrs['pieCircleSize'] = sanitize_text_field( $input['pieCircleSize'] ); }
	if ( ! empty( $input['pieThickness'] ) && '5' !== $input['pieThickness'] ) {
		$attrs['pieThickness'] = sanitize_text_field( $input['pieThickness'] ); }
	if ( ! empty( $input['pieFillStyle'] ) && 'normal' !== $input['pieFillStyle'] ) {
		$attrs['pieFillColor'] = sanitize_key( $input['pieFillStyle'] ); }
	if ( ! empty( $input['pieColor1'] ) && '#FFA500' !== $input['pieColor1'] ) {
		$attrs['pieColor1'] = sanitize_text_field( $input['pieColor1'] ); }
	if ( ! empty( $input['pieColor2'] ) && '#008000' !== $input['pieColor2'] ) {
		$attrs['pieColor2'] = sanitize_text_field( $input['pieColor2'] ); }
	if ( ! empty( $input['fillReverse'] ) ) {
		$attrs['fillReverse'] = true; }

	/* ── Content ──────────────────────────────────────────────────────── */
	if ( ! empty( $input['title'] ) && 'Web Design' !== $input['title'] ) {
		$attrs['Title'] = tpgb_mcp_clean_text( $input['title'] ); }
	if ( ! empty( $input['subTitle'] ) && 'HTML, CSS and WordPress' !== $input['subTitle'] ) {
		$attrs['subTitle'] = tpgb_mcp_clean_text( $input['subTitle'] ); }
	if ( isset( $input['prePostSymbol'] ) && '%' !== $input['prePostSymbol'] ) {
		$attrs['prepostSymbol'] = sanitize_text_field( $input['prePostSymbol'] ); }
	if ( ! empty( $input['symbolPosition'] ) && 'afterNumber' !== $input['symbolPosition'] ) {
		$attrs['sPosition'] = sanitize_text_field( $input['symbolPosition'] ); }

	/* ── Icon / Image ─────────────────────────────────────────────────── */
	$icon_type = sanitize_key( $input['iconType'] ?? 'iconIcon' );
	if ( 'iconIcon' !== $icon_type ) {
		$attrs['iconType'] = $icon_type; }

	if ( 'iconIcon' === $icon_type ) {
		if ( ! empty( $input['iconLibrary'] ) && 'fontawesome' !== $input['iconLibrary'] ) {
			$attrs['iconLibrary'] = sanitize_key( $input['iconLibrary'] ); }
		if ( ! empty( $input['iconClass'] ) && 'fas fa-code' !== $input['iconClass'] ) {
			$attrs['IconName'] = sanitize_text_field( $input['iconClass'] ); }
	}
	if ( 'iconImage' === $icon_type ) {
		$img = array(
			'url' => '',
			'Id'  => '',
		);
		if ( ! empty( $input['imageId'] ) ) {
			$img['Id'] = absint( $input['imageId'] );
			$src       = wp_get_attachment_image_url( $img['Id'], 'full' );
			if ( $src ) {
				$img['url'] = $src; }
		} elseif ( ! empty( $input['imageUrl'] ) ) {
			$img['url'] = esc_url_raw( $input['imageUrl'] );
		}
		if ( ! empty( $img['url'] ) ) {
			$attrs['imageName'] = $img; }
		if ( ! empty( $input['imageSize'] ) && 'thumbnail' !== $input['imageSize'] ) {
			$attrs['imageSize'] = sanitize_key( $input['imageSize'] ); }
	}
	if ( ! empty( $input['imagePosition'] ) && 'beforeTitle' !== $input['imagePosition'] ) {
		$attrs['imgPosition'] = sanitize_text_field( $input['imagePosition'] ); }

	/* ── Bar styling ──────────────────────────────────────────────────── */
	if ( ! empty( $input['topMargin'] ) ) {
		$attrs['pbTopMargin'] = array(
			'md'   => (string) absint( $input['topMargin'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['fillColor'] ) ) {
		$attrs['bgColor'] = tpgb_mcp_pbar_bg( $input['fillColor'] ); }
	if ( ! empty( $input['emptyColor'] ) ) {
		$attrs['emptyColor'] = sanitize_text_field( $input['emptyColor'] ); }
	if ( ! empty( $input['separatorColor'] ) ) {
		$attrs['sepColor'] = sanitize_text_field( $input['separatorColor'] ); }

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
	if ( ! empty( $input['titleSpace'] ) ) {
		$attrs['titleSpace'] = array(
			'md'   => (string) absint( $input['titleSpace'] ),
			'unit' => 'px',
		); }

	/* ── Subtitle styling ─────────────────────────────────────────────── */
	if ( ! empty( $input['enableSubTitleTypo'] ) ) {
		$attrs['subTitleTypo'] = array(
			'openTypography' => 1,
			'size'           => array(
				'md'   => ! empty( $input['subTitleTypoSize'] ) ? (string) absint( $input['subTitleTypoSize'] ) : '',
				'unit' => 'px',
			),
			'height'         => '',
			'spacing'        => '',
			'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
		);
	}
	if ( ! empty( $input['subTitleColor'] ) ) {
		$attrs['subTitleColor'] = sanitize_text_field( $input['subTitleColor'] ); }

	/* ── Number styling ───────────────────────────────────────────────── */
	if ( ! empty( $input['enableNumberTypo'] ) ) {
		$attrs['numTypo'] = array(
			'openTypography' => 1,
			'size'           => array(
				'md'   => ! empty( $input['numberTypoSize'] ) ? (string) absint( $input['numberTypoSize'] ) : '',
				'unit' => 'px',
			),
			'height'         => '',
			'spacing'        => '',
			'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
		);
	}
	if ( ! empty( $input['numberColor'] ) ) {
		$attrs['numberColor'] = sanitize_text_field( $input['numberColor'] ); }
	if ( ! empty( $input['enablePrePostTypo'] ) ) {
		$attrs['numPrePostTypo'] = array(
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
		$attrs['numPrePostColor'] = sanitize_text_field( $input['prePostColor'] ); }

	/* ── Icon / Image styling ─────────────────────────────────────────── */
	if ( ! empty( $input['iconColor'] ) ) {
		$attrs['iconColor'] = sanitize_text_field( $input['iconColor'] ); }
	if ( ! empty( $input['iconSize'] ) ) {
		$attrs['iconSize'] = array(
			'md'   => (string) absint( $input['iconSize'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['imageWidth'] ) ) {
		$attrs['imgSize'] = array(
			'md'   => (string) absint( $input['imageWidth'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['imageBorderRadius'] ) ) {
		$attrs['imgBRadius'] = tpgb_mcp_pbar_radius( $input['imageBorderRadius'] ); }

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
		$attrs['globalMargin'] = tpgb_mcp_pbar_spacing( $input['globalMargin'] );  }
	if ( ! empty( $input['globalPadding'] ) ) {
		$attrs['globalPadding'] = tpgb_mcp_pbar_spacing( $input['globalPadding'] ); }
	if ( ! empty( $input['globalBgColor'] ) ) {
		$attrs['globalBg'] = tpgb_mcp_pbar_bg( $input['globalBgColor'] );      }
	if ( ! empty( $input['globalBgHoverColor'] ) ) {
		$attrs['globalBgHover'] = tpgb_mcp_pbar_bg( $input['globalBgHoverColor'] ); }
	if ( ! empty( $input['globalBorder'] ) ) {
		$attrs['globalBorder'] = tpgb_mcp_pbar_border( $input['globalBorder'] );       }
	if ( ! empty( $input['globalBorderHover'] ) ) {
		$attrs['globalBorderHover'] = tpgb_mcp_pbar_border( $input['globalBorderHover'] );  }
	if ( ! empty( $input['globalBRadius'] ) ) {
		$attrs['globalBRadius'] = tpgb_mcp_pbar_radius( $input['globalBRadius'] );      }
	if ( ! empty( $input['globalBRadiusHover'] ) ) {
		$attrs['globalBRadiusHover'] = tpgb_mcp_pbar_radius( $input['globalBRadiusHover'] ); }
	if ( ! empty( $input['globalBShadow'] ) ) {
		$attrs['globalBShadow'] = tpgb_mcp_pbar_bshadow( $input['globalBShadow'] );      }
	if ( ! empty( $input['globalBShadowHover'] ) ) {
		$attrs['globalBShadowHover'] = tpgb_mcp_pbar_bshadow( $input['globalBShadowHover'] ); }

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
	if ( tpgb_mcp_pbar_needs_wrapper( $attrs ) ) {
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
