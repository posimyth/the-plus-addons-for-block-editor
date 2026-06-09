<?php
/**
 * Ability: Add Nexter Blocks Pricing List (tpgb/tp-pricing-list) to a Gutenberg page.
 *
 * @package The_Plus_Addons_For_Block_Editor
 * @since   1.3.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_register_ability(
	'nexter-blocks/add-tpgb-pricing-list',
	array(
		'label'               => __( 'Add Nexter Blocks Pricing List', 'the-plus-addons-for-block-editor' ),
		'description'         => __( 'Adds the Nexter Blocks Pricing List block (tpgb/tp-pricing-list) — a single pricing row/menu item with title, description, price tag, feature tags (size/options), and optional image. Ideal for menus (food/drink), service lists, product catalogs. Supports 3 style presets, dotted line between title and price, multiple hover effects, image shapes/masks, and comprehensive styling. This is a dynamic block.', 'the-plus-addons-for-block-editor' ),
		'category'            => 'nexter-blocks',

		'input_schema'        => array(
			'type'                 => 'object',
			'properties'           => array(

				/* ── Core ─────────────────────────────────────────────────── */
				'post_id'              => array( 'type' => 'integer' ),
				'position'             => array(
					'type'    => 'integer',
					'default' => -1,
				),
				'parent_block_id'      => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Style & Layout ───────────────────────────────────────── */
				'style'                => array(
					'type'    => 'string',
					'enum'    => array( 'style-1', 'style-2', 'style-3' ),
					'default' => 'style-1',
				),
				'alignment'            => array(
					'type'    => 'string',
					'enum'    => array( 'left', 'center', 'right' ),
					'default' => 'left',
				),
				'imagePosition'        => array(
					'type'        => 'string',
					'enum'        => array( 'top-left', 'top-center', 'top-right', 'middle-left', 'middle-right', 'bottom-left' ),
					'description' => 'Image alignment within the box.',
					'default'     => 'top-left',
				),
				'hoverEffect'          => array(
					'type'        => 'string',
					'enum'        => array( 'horizontal', 'vertical', 'fade', 'zoom', 'none' ),
					'description' => 'Hover animation effect.',
					'default'     => 'horizontal',
				),

				/* ── Content ──────────────────────────────────────────────── */
				'title'                => array(
					'type'        => 'string',
					'default'     => 'Delicious Cup Cake',
					'description' => 'Item title/name.',
				),
				'tags'                 => array(
					'type'        => 'string',
					'default'     => 'Small|Medium|Large',
					'description' => 'Tags/size options separated by | (pipe).',
				),
				'price'                => array(
					'type'        => 'string',
					'default'     => '$4.99',
					'description' => 'Price text (include currency symbol).',
				),
				'description'          => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Item description/details.',
				),

				/* ── Image ────────────────────────────────────────────────── */
				'imageUrl'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'imageId'              => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'imageSize'            => array(
					'type'    => 'string',
					'default' => 'full',
				),
				'imageShape'           => array(
					'type'    => 'string',
					'enum'    => array( 'none', 'circle', 'rounded', 'custom-mask' ),
					'default' => 'none',
				),
				'maskImageUrl'         => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Mask SVG URL when imageShape is "custom-mask".',
				),
				'imageMinWidth'        => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'imageMaxWidth'        => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'imageRightSpace'      => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'imageBorder'          => array( 'type' => 'object' ),
				'imageBorderRadius'    => array( 'type' => 'object' ),
				'enableImageShadow'    => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'imgShadowH'           => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'imgShadowV'           => array(
					'type'    => 'integer',
					'default' => 4,
				),
				'imgShadowBlur'        => array(
					'type'    => 'integer',
					'default' => 8,
				),
				'imgShadowSpread'      => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'imgShadowColor'       => array(
					'type'    => 'string',
					'default' => 'rgba(0,0,0,0.40)',
				),

				/* ── Title styling ────────────────────────────────────────── */
				'enableTitleTypo'      => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'titleTypoSize'        => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'titleColor'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'titleBgColor'         => array(
					'type'    => 'string',
					'default' => '',
				),
				'titlePadding'         => array( 'type' => 'object' ),

				/* ── Dotted line style ────────────────────────────────────── */
				'enableLine'           => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Enable dotted line between title and price.',
				),
				'lineType'             => array(
					'type'    => 'string',
					'enum'    => array( 'solid', 'dotted', 'dashed', 'double' ),
					'default' => 'solid',
				),
				'lineColor'            => array(
					'type'    => 'string',
					'default' => '#888',
				),
				'lineWidth'            => array(
					'type'        => 'integer',
					'default'     => 1,
					'description' => 'Line width in px.',
				),

				/* ── Tag styling ──────────────────────────────────────────── */
				'enableTagTypo'        => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'tagTypoSize'          => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'tagSpace'             => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Space between tags in px.',
				),
				'tagColor'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'tagBgColor'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'tagBorderRadius'      => array( 'type' => 'object' ),
				'tagPadding'           => array( 'type' => 'object' ),

				/* ── Price styling ────────────────────────────────────────── */
				'enablePriceTypo'      => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'priceTypoSize'        => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'priceColor'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'priceBgColor'         => array(
					'type'    => 'string',
					'default' => '',
				),
				'priceBorderRadius'    => array( 'type' => 'object' ),
				'pricePadding'         => array( 'type' => 'object' ),

				/* ── Description styling ──────────────────────────────────── */
				'enableDescTypo'       => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'descTypoSize'         => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'descColor'            => array(
					'type'    => 'string',
					'default' => '',
				),
				'descBgColor'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'descBorderRadius'     => array( 'type' => 'object' ),
				'descPadding'          => array( 'type' => 'object' ),

				/* ── Box styling ──────────────────────────────────────────── */
				'boxPadding'           => array( 'type' => 'object' ),
				'boxBgColor'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'boxBgHoverColor'      => array(
					'type'    => 'string',
					'default' => '',
				),
				'boxBorder'            => array( 'type' => 'object' ),
				'boxBorderHover'       => array( 'type' => 'object' ),
				'boxBorderRadius'      => array( 'type' => 'object' ),
				'boxBorderRadiusHover' => array( 'type' => 'object' ),
				'enableBoxShadow'      => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'boxShadowH'           => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'boxShadowV'           => array(
					'type'    => 'integer',
					'default' => 4,
				),
				'boxShadowBlur'        => array(
					'type'    => 'integer',
					'default' => 8,
				),
				'boxShadowSpread'      => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'boxShadowColor'       => array(
					'type'    => 'string',
					'default' => 'rgba(0,0,0,0.40)',
				),
				'enableBoxShadowHover' => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'boxShadowHoverH'      => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'boxShadowHoverV'      => array(
					'type'    => 'integer',
					'default' => 4,
				),
				'boxShadowHoverBlur'   => array(
					'type'    => 'integer',
					'default' => 8,
				),
				'boxShadowHoverSpread' => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'boxShadowHoverColor'  => array(
					'type'    => 'string',
					'default' => 'rgba(0,0,0,0.40)',
				),

				/* ── Scroll animation ────────────────────────────────────── */
				'scrollAnimation'      => array(
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
				'animDuration'         => array(
					'type'    => 'string',
					'enum'    => array( '', 'slow', 'normal', 'fast' ),
					'default' => '',
				),
				'animDelay'            => array(
					'type'    => 'string',
					'default' => '',
				),
				'animEasing'           => array(
					'type'    => 'string',
					'enum'    => array( '', 'linear', 'ease', 'ease-in', 'ease-out', 'ease-in-out' ),
					'default' => '',
				),

				/* ── Advanced ─────────────────────────────────────────────── */
				'hideDesktop'          => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'hideTablet'           => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'hideMobile'           => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'globalClasses'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalId'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalCustomCss'      => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalWidth'          => array(
					'type'    => 'string',
					'enum'    => array( '', 'inline', 'full', 'custom' ),
					'default' => '',
				),
				'globalZindex'         => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalPosition'       => array(
					'type'    => 'string',
					'enum'    => array( '', 'relative', 'absolute', 'fixed', 'sticky' ),
					'default' => '',
				),
				'transitionDuration'   => array(
					'type'    => 'string',
					'default' => '',
				),
				'transitionFunction'   => array(
					'type'    => 'string',
					'enum'    => array( '', 'ease', 'ease-in', 'ease-out', 'ease-in-out', 'linear' ),
					'default' => '',
				),
				'transitionOrigin'     => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalMargin'         => array( 'type' => 'object' ),
				'globalPadding'        => array( 'type' => 'object' ),
				'globalBgColor'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalBgHoverColor'   => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalBorder'         => array( 'type' => 'object' ),
				'globalBorderHover'    => array( 'type' => 'object' ),
				'globalBRadius'        => array( 'type' => 'object' ),
				'globalBRadiusHover'   => array( 'type' => 'object' ),
				'globalBShadow'        => array( 'type' => 'object' ),
				'globalBShadowHover'   => array( 'type' => 'object' ),

				'rotateDeg'            => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotateX'              => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotateY'              => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotatePerspective'    => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotateDegHover'       => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotateXHover'         => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotateYHover'         => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotatePersHover'      => array(
					'type'    => 'string',
					'default' => '',
				),
				'offsetX'              => array(
					'type'    => 'string',
					'default' => '',
				),
				'offsetY'              => array(
					'type'    => 'string',
					'default' => '',
				),
				'offsetZ'              => array(
					'type'    => 'string',
					'default' => '',
				),
				'offsetXHover'         => array(
					'type'    => 'string',
					'default' => '',
				),
				'offsetYHover'         => array(
					'type'    => 'string',
					'default' => '',
				),
				'offsetZHover'         => array(
					'type'    => 'string',
					'default' => '',
				),
				'scaleValue'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'scaleKeepRatio'       => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'scaleValueHover'      => array(
					'type'    => 'string',
					'default' => '',
				),
				'skewX'                => array(
					'type'    => 'string',
					'default' => '',
				),
				'skewY'                => array(
					'type'    => 'string',
					'default' => '',
				),
				'skewXHover'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'skewYHover'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'flipHorizontal'       => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'flipVertical'         => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'flipHorizontalHover'  => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'flipVerticalHover'    => array(
					'type'    => 'boolean',
					'default' => false,
				),

				'settings'             => array( 'type' => 'object' ),
				'fontFamily'           => array(
					'type'        => 'string',
					'description' => 'Font family name (e.g. "Inter", "Roboto", "Playfair Display"). When inspecting a URL via nexter-blocks/inspect-page, pass the returned fonts[].family value verbatim.',
					'default'     => '',
				),
				'fontType'             => array(
					'type'        => 'string',
					'description' => 'Font category — "sans-serif", "serif", "display", "handwriting", or "monospace".',
					'default'     => '',
				),
				'customFont'           => array(
					'type'        => 'string',
					'description' => 'Custom (non-Google) font name. Overrides fontFamily.',
					'default'     => '',
				),
				'fontWeight'           => array(
					'type'        => 'string',
					'description' => 'Font weight as a string ("100"..."900"). Embedded inside every typography group\'s fontFamily.fontWeight on this block. For per-group control, use the settings raw override or sprout/update-element.',
					'default'     => '',
				),
				'textDecoration'       => array(
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

		'execute_callback'    => 'tpgb_mcp_add_pricing_list_ability',
		'permission_callback' => 'tpgb_mcp_add_pricing_list_permission',
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
 * Permission callback for the add-pricing-list ability.
 *
 * @param array|null $input Ability input arguments.
 * @return bool True when the current user may insert the block.
 */
function tpgb_mcp_add_pricing_list_permission( ?array $input = null ): bool {
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
function tpgb_mcp_plst_spacing( array $v ): array {
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
function tpgb_mcp_plst_border( array $b ): array {
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
function tpgb_mcp_plst_radius( array $r ): array {
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
function tpgb_mcp_plst_bshadow( array $s ): array {
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
function tpgb_mcp_plst_bg( string $color ): array {
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
function tpgb_mcp_plst_needs_wrapper( array $attrs ): bool {
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
 * Execute callback: insert a tpgb/tp-pricing-list block into a post.
 *
 * @param array $input Ability input arguments.
 * @return array|WP_Error Ability result or error on failure.
 */
function tpgb_mcp_add_pricing_list_ability( array $input ) {

	if ( ! defined( 'TPGB_VERSION' ) && ! defined( 'TPGBP_VERSION' ) ) {
		return new WP_Error( 'nexter_blocks_missing', __( 'Nexter Blocks must be active.', 'the-plus-addons-for-block-editor' ) );
	}

	$block_name = 'tpgb/tp-pricing-list';
	if ( ! tpgb_mcp_has_registered_block( $block_name ) ) {
		return new WP_Error( 'block_missing', __( 'tpgb/tp-pricing-list is not registered.', 'the-plus-addons-for-block-editor' ) );
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

	/* ── Style & Layout ───────────────────────────────────────────────── */
	if ( ! empty( $input['style'] ) && 'style-1' !== $input['style'] ) {
		$attrs['style'] = sanitize_text_field( $input['style'] ); }
	if ( ! empty( $input['alignment'] ) && 'left' !== $input['alignment'] ) {
		$attrs['Alignment'] = array(
			'md' => sanitize_key( $input['alignment'] ),
			'sm' => '',
			'xs' => '',
		);
	}
	if ( ! empty( $input['imagePosition'] ) && 'top-left' !== $input['imagePosition'] ) {
		$attrs['boxAlign'] = sanitize_text_field( $input['imagePosition'] ); }
	if ( ! empty( $input['hoverEffect'] ) && 'horizontal' !== $input['hoverEffect'] ) {
		$attrs['hoverEffect'] = sanitize_key( $input['hoverEffect'] ); }

	/* ── Content ──────────────────────────────────────────────────────── */
	if ( ! empty( $input['title'] ) && 'Delicious Cup Cake' !== $input['title'] ) {
		$attrs['title'] = tpgb_mcp_clean_text( $input['title'] ); }
	if ( isset( $input['tags'] ) && 'Small|Medium|Large' !== $input['tags'] ) {
		$attrs['tagField'] = sanitize_text_field( $input['tags'] ); }
	if ( ! empty( $input['price'] ) && '$4.99' !== $input['price'] ) {
		$attrs['price'] = sanitize_text_field( $input['price'] ); }
	$default_desc = 'Cupcake ipsum dolor. Sit amet marshmallow topping cheesecake muffin. Halvah croissant candy canes bonbon candy. Apple pie jelly beans topping carrot cake danish tart cake cheesecake. Muffin danish chocolate soufflé pastry icing bonbon oat cake. Powder cake jujubes oat cake. Lemon drops tootsie roll marshmallow halvah carrot cake.';
	if ( isset( $input['description'] ) && $default_desc !== $input['description'] ) {
		$attrs['description'] = tpgb_mcp_clean_text( $input['description'] );
	}

	/* ── Image ────────────────────────────────────────────────────────── */
	if ( ! empty( $input['imageUrl'] ) || ! empty( $input['imageId'] ) ) {
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
			$attrs['imageField'] = $img; }
	}
	if ( ! empty( $input['imageSize'] ) && 'full' !== $input['imageSize'] ) {
		$attrs['imageSize'] = sanitize_key( $input['imageSize'] ); }
	if ( ! empty( $input['imageShape'] ) && 'none' !== $input['imageShape'] ) {
		$attrs['imgShape'] = sanitize_key( $input['imageShape'] ); }
	if ( ! empty( $input['maskImageUrl'] ) ) {
		$attrs['maskImg'] = array( 'url' => esc_url_raw( $input['maskImageUrl'] ) ); }
	if ( ! empty( $input['imageMinWidth'] ) ) {
		$attrs['imgMinWidth'] = array(
			'md'   => (string) absint( $input['imageMinWidth'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['imageMaxWidth'] ) ) {
		$attrs['imgMaxWidth'] = array(
			'md'   => (string) absint( $input['imageMaxWidth'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['imageRightSpace'] ) ) {
		$attrs['imgRightSpace'] = array(
			'md'   => (string) absint( $input['imageRightSpace'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['imageBorder'] ) ) {
		$attrs['imgBorder'] = tpgb_mcp_plst_border( $input['imageBorder'] ); }
	if ( ! empty( $input['imageBorderRadius'] ) ) {
		$attrs['imgBRadius'] = tpgb_mcp_plst_radius( $input['imageBorderRadius'] ); }
	if ( ! empty( $input['enableImageShadow'] ) ) {
		$attrs['imgShadow'] = array(
			'openShadow' => true,
			'inset'      => 0,
			'horizontal' => (string) intval( $input['imgShadowH'] ?? 0 ),
			'vertical'   => (string) intval( $input['imgShadowV'] ?? 4 ),
			'blur'       => (string) absint( $input['imgShadowBlur'] ?? 8 ),
			'spread'     => (string) intval( $input['imgShadowSpread'] ?? 0 ),
			'color'      => sanitize_text_field( $input['imgShadowColor'] ?? 'rgba(0,0,0,0.40)' ),
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
	if ( ! empty( $input['titleBgColor'] ) ) {
		$attrs['titleBG'] = tpgb_mcp_plst_bg( $input['titleBgColor'] ); }
	if ( ! empty( $input['titlePadding'] ) ) {
		$attrs['titlePadding'] = tpgb_mcp_plst_spacing( $input['titlePadding'] ); }

	/* ── Line style ───────────────────────────────────────────────────── */
	if ( ! empty( $input['enableLine'] ) ) {
		$attrs['lineStyle'] = array(
			'openBorder' => 1,
			'type'       => sanitize_key( $input['lineType'] ?? 'solid' ),
			'color'      => sanitize_text_field( $input['lineColor'] ?? '#888' ),
			'width'      => array(
				'md'   => array(
					'top'    => (string) absint( $input['lineWidth'] ?? 1 ),
					'right'  => '',
					'bottom' => '',
					'left'   => '',
					'unit'   => 'px',
				),
				'unit' => 'px',
			),
		);
	}

	/* ── Tag styling ──────────────────────────────────────────────────── */
	if ( ! empty( $input['enableTagTypo'] ) ) {
		$attrs['tagTypo'] = array(
			'openTypography' => 1,
			'size'           => array(
				'md'   => ! empty( $input['tagTypoSize'] ) ? (string) absint( $input['tagTypoSize'] ) : '',
				'unit' => 'px',
			),
			'height'         => '',
			'spacing'        => '',
			'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
		);
	}
	if ( ! empty( $input['tagSpace'] ) ) {
		$attrs['tagSpace'] = array(
			'md'   => (string) absint( $input['tagSpace'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['tagColor'] ) ) {
		$attrs['tagColor'] = sanitize_text_field( $input['tagColor'] ); }
	if ( ! empty( $input['tagBgColor'] ) ) {
		$attrs['tagBG'] = tpgb_mcp_plst_bg( $input['tagBgColor'] ); }
	if ( ! empty( $input['tagBorderRadius'] ) ) {
		$attrs['tagBRadius'] = tpgb_mcp_plst_radius( $input['tagBorderRadius'] ); }
	if ( ! empty( $input['tagPadding'] ) ) {
		$attrs['tagPadding'] = tpgb_mcp_plst_spacing( $input['tagPadding'] ); }

	/* ── Price styling ────────────────────────────────────────────────── */
	if ( ! empty( $input['enablePriceTypo'] ) ) {
		$attrs['priceTypo'] = array(
			'openTypography' => 1,
			'size'           => array(
				'md'   => ! empty( $input['priceTypoSize'] ) ? (string) absint( $input['priceTypoSize'] ) : '',
				'unit' => 'px',
			),
			'height'         => '',
			'spacing'        => '',
			'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
		);
	}
	if ( ! empty( $input['priceColor'] ) ) {
		$attrs['priceColor'] = sanitize_text_field( $input['priceColor'] ); }
	if ( ! empty( $input['priceBgColor'] ) ) {
		$attrs['priceBG'] = tpgb_mcp_plst_bg( $input['priceBgColor'] ); }
	if ( ! empty( $input['priceBorderRadius'] ) ) {
		$attrs['priceBRadius'] = tpgb_mcp_plst_radius( $input['priceBorderRadius'] ); }
	if ( ! empty( $input['pricePadding'] ) ) {
		$attrs['pricePadding'] = tpgb_mcp_plst_spacing( $input['pricePadding'] ); }

	/* ── Description styling ──────────────────────────────────────────── */
	if ( ! empty( $input['enableDescTypo'] ) ) {
		$attrs['descTypo'] = array(
			'openTypography' => 1,
			'size'           => array(
				'md'   => ! empty( $input['descTypoSize'] ) ? (string) absint( $input['descTypoSize'] ) : '',
				'unit' => 'px',
			),
			'height'         => '',
			'spacing'        => '',
			'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
		);
	}
	if ( ! empty( $input['descColor'] ) ) {
		$attrs['descColor'] = sanitize_text_field( $input['descColor'] ); }
	if ( ! empty( $input['descBgColor'] ) ) {
		$attrs['descBG'] = tpgb_mcp_plst_bg( $input['descBgColor'] ); }
	if ( ! empty( $input['descBorderRadius'] ) ) {
		$attrs['descBRadius'] = tpgb_mcp_plst_radius( $input['descBorderRadius'] ); }
	if ( ! empty( $input['descPadding'] ) ) {
		$attrs['descPadding'] = tpgb_mcp_plst_spacing( $input['descPadding'] ); }

	/* ── Box styling ──────────────────────────────────────────────────── */
	if ( ! empty( $input['boxPadding'] ) ) {
		$attrs['bgPadding'] = tpgb_mcp_plst_spacing( $input['boxPadding'] ); }
	if ( ! empty( $input['boxBgColor'] ) ) {
		$attrs['normalBG'] = tpgb_mcp_plst_bg( $input['boxBgColor'] ); }
	if ( ! empty( $input['boxBgHoverColor'] ) ) {
		$attrs['hoverBG'] = tpgb_mcp_plst_bg( $input['boxBgHoverColor'] ); }
	if ( ! empty( $input['boxBorder'] ) ) {
		$attrs['bgNormalB'] = tpgb_mcp_plst_border( $input['boxBorder'] ); }
	if ( ! empty( $input['boxBorderHover'] ) ) {
		$attrs['bgHoverB'] = tpgb_mcp_plst_border( $input['boxBorderHover'] ); }
	if ( ! empty( $input['boxBorderRadius'] ) ) {
		$attrs['bgNmlBRadius'] = tpgb_mcp_plst_radius( $input['boxBorderRadius'] ); }
	if ( ! empty( $input['boxBorderRadiusHover'] ) ) {
		$attrs['bgHvrBRadius'] = tpgb_mcp_plst_radius( $input['boxBorderRadiusHover'] ); }
	if ( ! empty( $input['enableBoxShadow'] ) ) {
		$attrs['normalBGShadow'] = array(
			'openShadow' => true,
			'inset'      => 0,
			'horizontal' => (string) intval( $input['boxShadowH'] ?? 0 ),
			'vertical'   => (string) intval( $input['boxShadowV'] ?? 4 ),
			'blur'       => (string) absint( $input['boxShadowBlur'] ?? 8 ),
			'spread'     => (string) intval( $input['boxShadowSpread'] ?? 0 ),
			'color'      => sanitize_text_field( $input['boxShadowColor'] ?? 'rgba(0,0,0,0.40)' ),
		);
	}
	if ( ! empty( $input['enableBoxShadowHover'] ) ) {
		$attrs['hoverBGShadow'] = array(
			'openShadow' => true,
			'inset'      => 0,
			'horizontal' => (string) intval( $input['boxShadowHoverH'] ?? 0 ),
			'vertical'   => (string) intval( $input['boxShadowHoverV'] ?? 4 ),
			'blur'       => (string) absint( $input['boxShadowHoverBlur'] ?? 8 ),
			'spread'     => (string) intval( $input['boxShadowHoverSpread'] ?? 0 ),
			'color'      => sanitize_text_field( $input['boxShadowHoverColor'] ?? 'rgba(0,0,0,0.40)' ),
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

	/* ── Transition ───────────────────────────────────────────────────── */
	if ( ! empty( $input['transitionDuration'] ) ) {
		$attrs['gTraDur'] = sanitize_text_field( $input['transitionDuration'] ); }
	if ( ! empty( $input['transitionFunction'] ) ) {
		$attrs['gTraFunc'] = sanitize_text_field( $input['transitionFunction'] ); }
	if ( ! empty( $input['transitionOrigin'] ) ) {
		$attrs['gTraOrigin'] = sanitize_text_field( $input['transitionOrigin'] );   }

	/* ── Global: Spacing/Bg/Border/Shadow ─────────────────────────────── */
	if ( ! empty( $input['globalMargin'] ) ) {
		$attrs['globalMargin'] = tpgb_mcp_plst_spacing( $input['globalMargin'] );  }
	if ( ! empty( $input['globalPadding'] ) ) {
		$attrs['globalPadding'] = tpgb_mcp_plst_spacing( $input['globalPadding'] ); }
	if ( ! empty( $input['globalBgColor'] ) ) {
		$attrs['globalBg'] = tpgb_mcp_plst_bg( $input['globalBgColor'] );      }
	if ( ! empty( $input['globalBgHoverColor'] ) ) {
		$attrs['globalBgHover'] = tpgb_mcp_plst_bg( $input['globalBgHoverColor'] ); }
	if ( ! empty( $input['globalBorder'] ) ) {
		$attrs['globalBorder'] = tpgb_mcp_plst_border( $input['globalBorder'] );       }
	if ( ! empty( $input['globalBorderHover'] ) ) {
		$attrs['globalBorderHover'] = tpgb_mcp_plst_border( $input['globalBorderHover'] );  }
	if ( ! empty( $input['globalBRadius'] ) ) {
		$attrs['globalBRadius'] = tpgb_mcp_plst_radius( $input['globalBRadius'] );      }
	if ( ! empty( $input['globalBRadiusHover'] ) ) {
		$attrs['globalBRadiusHover'] = tpgb_mcp_plst_radius( $input['globalBRadiusHover'] ); }
	if ( ! empty( $input['globalBShadow'] ) ) {
		$attrs['globalBShadow'] = tpgb_mcp_plst_bshadow( $input['globalBShadow'] );      }
	if ( ! empty( $input['globalBShadowHover'] ) ) {
		$attrs['globalBShadowHover'] = tpgb_mcp_plst_bshadow( $input['globalBShadowHover'] ); }

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
	if ( tpgb_mcp_plst_needs_wrapper( $attrs ) ) {
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
