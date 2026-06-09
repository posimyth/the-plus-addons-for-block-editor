<?php
/**
 * Ability: Add Nexter Blocks Stylish List (tpgb/tp-stylist-list) to a Gutenberg page.
 *
 * @package The_Plus_Addons_For_Block_Editor
 * @since   1.3.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_register_ability(
	'nexter-blocks/add-tpgb-stylist-list',
	array(
		'label'               => __( 'Add Nexter Blocks Stylish List', 'the-plus-addons-for-block-editor' ),
		'description'         => __( 'Adds the Nexter Blocks Stylish List block (tpgb/tp-stylist-list) — bullet/icon list with per-item icon (FA, image, SVG), URL, hint/pin badge, and tooltip; supports vertical/horizontal layout, read-more toggle (collapse N items + show-all/less), separator, advanced icon styling (size/border/radius/bg/shadow normal+hover), text styling (typo, padding, border/radius/bg/shadow normal+hover), pin/hint badge styling (placement, typo, bg, padding, dimensions), Tippy tooltip controls, hover-inverse fade effect. This is a dynamic block.', 'the-plus-addons-for-block-editor' ),
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

				/* ── List items ───────────────────────────────────────────── */
				'items'               => array(
					'type'        => 'array',
					'description' => 'List items. Each: { text, iconType (fontawesome|image|svg), icon (FA class), imageUrl, imageId, svgUrl, svgId, url, newTab, nofollow, hintText, hintColor, hintBgColor, tooltipText, tooltipColor }.',
					'items'       => array(
						'type'       => 'object',
						'properties' => array(
							'text'         => array( 'type' => 'string' ),
							'iconType'     => array(
								'type' => 'string',
								'enum' => array( 'fontawesome', 'image', 'svg' ),
							),
							'icon'         => array( 'type' => 'string' ),
							'imageUrl'     => array( 'type' => 'string' ),
							'imageId'      => array( 'type' => 'integer' ),
							'svgUrl'       => array( 'type' => 'string' ),
							'svgId'        => array( 'type' => 'integer' ),
							'url'          => array( 'type' => 'string' ),
							'newTab'       => array( 'type' => 'boolean' ),
							'nofollow'     => array( 'type' => 'boolean' ),
							'hintText'     => array( 'type' => 'string' ),
							'hintColor'    => array( 'type' => 'string' ),
							'hintBgColor'  => array( 'type' => 'string' ),
							'tooltipText'  => array( 'type' => 'string' ),
							'tooltipColor' => array( 'type' => 'string' ),
						),
					),
				),

				/* ── Layout ───────────────────────────────────────────────── */
				'listType'            => array(
					'type'    => 'string',
					'enum'    => array( 'vertical', 'horizontal' ),
					'default' => 'vertical',
				),
				'alignment'           => array(
					'type'    => 'string',
					'enum'    => array( 'left', 'center', 'right', 'justify' ),
					'default' => 'left',
				),
				'verticalSpace'       => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Spacing between items (vertical) px.',
				),
				'horizontalSpace'     => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Spacing between items (horizontal) px.',
				),
				'separatorColor'      => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Read more toggle ─────────────────────────────────────── */
				'readMoreToggle'      => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'showListCount'       => array(
					'type'        => 'string',
					'default'     => '3',
					'description' => 'How many items to show before collapse.',
				),
				'readMoreText'        => array(
					'type'    => 'string',
					'default' => '+ Show all options',
				),
				'readLessText'        => array(
					'type'    => 'string',
					'default' => '- Less options',
				),
				'toggleColor'         => array(
					'type'    => 'string',
					'default' => '',
				),
				'toggleColorHover'    => array(
					'type'    => 'string',
					'default' => '',
				),
				'toggleIndent'        => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Toggle margin-top in px.',
				),
				'toggleTypoSize'      => array(
					'type'    => 'integer',
					'default' => 0,
				),

				/* ── Icon style ───────────────────────────────────────────── */
				'iconColor'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'iconColorHover'      => array(
					'type'    => 'string',
					'default' => '',
				),
				'iconSize'            => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'FA icon font-size (px).',
				),
				'iconImgSize'         => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Image icon max-width (px).',
				),
				'iconSvgSize'         => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'SVG icon max-width (px).',
				),
				'iconAlignment'       => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'iconAdvanced'        => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Enable advanced icon box styling.',
				),
				'iconWidth'           => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Icon box width/height (px).',
				),
				'iconBorder'          => array( 'type' => 'object' ),
				'iconBorderHover'     => array( 'type' => 'object' ),
				'iconBRadius'         => array( 'type' => 'object' ),
				'iconBRadiusHover'    => array( 'type' => 'object' ),
				'iconBgColor'         => array(
					'type'    => 'string',
					'default' => '',
				),
				'iconBgColorHover'    => array(
					'type'    => 'string',
					'default' => '',
				),
				'iconShadow'          => array( 'type' => 'object' ),
				'iconShadowHover'     => array( 'type' => 'object' ),
				'svgStrokeColor'      => array(
					'type'    => 'string',
					'default' => '',
				),
				'svgFillColor'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'svgStrokeColorHover' => array(
					'type'    => 'string',
					'default' => '',
				),
				'svgFillColorHover'   => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Text style ───────────────────────────────────────────── */
				'textTypoSize'        => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'textColor'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'textColorHover'      => array(
					'type'    => 'string',
					'default' => '',
				),
				'textIndent'          => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Padding-left between icon and text (px).',
				),
				'textPadding'         => array( 'type' => 'object' ),
				'textBorder'          => array( 'type' => 'object' ),
				'textBRadius'         => array( 'type' => 'object' ),
				'textBgColor'         => array(
					'type'    => 'string',
					'default' => '',
				),
				'textShadow'          => array( 'type' => 'object' ),
				'textBorderHover'     => array( 'type' => 'object' ),
				'textBRadiusHover'    => array( 'type' => 'object' ),
				'textBgColorHover'    => array(
					'type'    => 'string',
					'default' => '',
				),
				'textShadowHover'     => array( 'type' => 'object' ),

				/* ── Pin / hint badge ─────────────────────────────────────── */
				'pinAlignment'        => array(
					'type'    => 'string',
					'enum'    => array( 'left', 'right' ),
					'default' => 'right',
				),
				'pinTypoSize'         => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'pinShadow'           => array( 'type' => 'object' ),
				'pinBRadius'          => array( 'type' => 'object' ),
				'pinPadding'          => array( 'type' => 'object' ),
				'pinHorizontalAdjust' => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'pinVerticalAdjust'   => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'pinLeftMinWidth'     => array(
					'type'    => 'integer',
					'default' => 60,
				),
				'pinRightMinWidth'    => array(
					'type'    => 'integer',
					'default' => 0,
				),

				/* ── Tooltip ──────────────────────────────────────────────── */
				'tipInteractive'      => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'tipPlacement'        => array(
					'type'    => 'string',
					'enum'    => array( 'top', 'bottom', 'left', 'right', 'top-start', 'top-end', 'bottom-start', 'bottom-end', 'left-start', 'left-end', 'right-start', 'right-end' ),
					'default' => 'top',
				),
				'tipTheme'            => array(
					'type'    => 'string',
					'enum'    => array( '', 'light', 'light-border', 'translucent', 'material' ),
					'default' => '',
				),
				'tipMaxWidth'         => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'tipOffset'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'followCursor'        => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'tipDistance'         => array(
					'type'    => 'string',
					'default' => '',
				),
				'tipArrow'            => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'tipTriggers'         => array(
					'type'    => 'string',
					'enum'    => array( 'mouseenter', 'click', 'focus', 'manual', 'mouseenter focus', 'mouseenter click' ),
					'default' => 'mouseenter',
				),
				'tipAnimation'        => array(
					'type'    => 'string',
					'enum'    => array( '', 'fade', 'shift-away', 'shift-toward', 'scale', 'perspective' ),
					'default' => '',
				),
				'tipDurationIn'       => array(
					'type'    => 'string',
					'default' => '',
				),
				'tipDurationOut'      => array(
					'type'    => 'string',
					'default' => '',
				),
				'tipArrowColor'       => array(
					'type'    => 'string',
					'default' => '',
				),
				'tipPadding'          => array( 'type' => 'object' ),
				'tipBorder'           => array( 'type' => 'object' ),
				'tipBorderRadius'     => array( 'type' => 'object' ),
				'tipBgColor'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'tipBoxShadow'        => array( 'type' => 'object' ),

				/* ── Hover inverse ────────────────────────────────────────── */
				'hoverBgStyle'        => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'hoverInverseEffect'  => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'unhoverOpacity'      => array(
					'type'    => 'number',
					'default' => 0.6,
				),
				'effectArea'          => array(
					'type'    => 'string',
					'enum'    => array( 'individual', 'global' ),
					'default' => 'individual',
				),

				/* ── Visibility / Globals ─────────────────────────────────── */
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
				'scrollAnimation'     => array(
					'type'    => 'string',
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

				'settings'            => array(
					'type'        => 'object',
					'description' => 'Raw attribute overrides.',
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

		'execute_callback'    => 'tpgb_mcp_add_stylist_list_ability',
		'permission_callback' => 'tpgb_mcp_add_stylist_list_permission',
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
 * Permission callback for the add-stylist-list ability.
 *
 * @param array|null $input Ability input arguments.
 * @return bool True when the current user may insert the block.
 */
function tpgb_mcp_add_stylist_list_permission( ?array $input = null ): bool {
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
function tpgb_mcp_sl2_spacing( array $v ): array {
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
function tpgb_mcp_sl2_border( array $b ): array {
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
function tpgb_mcp_sl2_radius( array $r ): array {
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
function tpgb_mcp_sl2_bshadow( array $s ): array {
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
function tpgb_mcp_sl2_bg( string $color ): array {
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
 * Build a Nexter Blocks typography attribute (font size only).
 *
 * @param int $size Font size in px.
 * @return array Typography attribute structured for the block.
 */
function tpgb_mcp_sl2_typo( int $size ): array {
	return array(
		'openTypography' => 1,
		'size'           => array(
			'md'   => (string) absint( $size ),
			'unit' => 'px',
		),
		'height'         => '',
		'spacing'        => '',
		'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
	);
}
/**
 * Determine whether the block needs Nexter's wrapper rule for global styling.
 *
 * @param array $attrs Block attributes already gathered.
 * @return bool True if any wrapper-affecting attribute is present.
 */
function tpgb_mcp_sl2_needs_wrapper( array $attrs ): bool {
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
 * Execute callback for the add-stylist-list ability.
 *
 * @param array $input Ability input arguments.
 * @return array|WP_Error Result payload or WP_Error on failure.
 */
function tpgb_mcp_add_stylist_list_ability( array $input ) {

	if ( ! defined( 'TPGB_VERSION' ) && ! defined( 'TPGBP_VERSION' ) ) {
		return new WP_Error( 'nexter_blocks_missing', __( 'Nexter Blocks must be active.', 'the-plus-addons-for-block-editor' ) );
	}

	$block_name = 'tpgb/tp-stylist-list';
	if ( ! tpgb_mcp_has_registered_block( $block_name ) ) {
		return new WP_Error( 'block_missing', __( 'tpgb/tp-stylist-list is not registered.', 'the-plus-addons-for-block-editor' ) );
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

	$block_id = tpgb_mcp_generate_block_id();
	$attrs    = array( 'block_id' => $block_id );

	/* ── List items ───────────────────────────────────────────────────── */
	if ( ! empty( $input['items'] ) && is_array( $input['items'] ) ) {
		$rows = array();
		foreach ( $input['items'] as $i => $it ) {
			$icon_type = sanitize_key( $it['iconType'] ?? 'fontawesome' );
			$rows[]   = array(
				'_key'            => (string) $i,
				'description'     => sanitize_text_field( $it['text'] ?? 'List item' ),
				'selectIcon'      => $icon_type,
				'iconFontawesome' => sanitize_text_field( $it['icon'] ?? 'fas fa-check-circle' ),
				'iconImg'         => array(
					'url' => esc_url_raw( $it['imageUrl'] ?? '' ),
					'id'  => absint( $it['imageId'] ?? 0 ),
				),
				'svgIcon'         => array(
					'url' => esc_url_raw( $it['svgUrl'] ?? '' ),
					'Id'  => (string) absint( $it['svgId'] ?? 0 ),
				),
				'descurl'         => array(
					'url'      => esc_url_raw( $it['url'] ?? '' ),
					'target'   => ! empty( $it['newTab'] ) ? '_blank' : '',
					'nofollow' => ! empty( $it['nofollow'] ) ? 'nofollow' : '',
				),
				'hintText'        => sanitize_text_field( $it['hintText'] ?? '' ),
				'hintColor'       => sanitize_text_field( $it['hintColor'] ?? '' ),
				'hintBgColor'     => sanitize_text_field( $it['hintBgColor'] ?? '' ),
				'tooltipText'     => sanitize_text_field( $it['tooltipText'] ?? '' ),
				'tooltipColor'    => sanitize_text_field( $it['tooltipColor'] ?? '' ),
				'tooltipTypo'     => array( 'openTypography' => 0 ),
			);
		}
		$attrs['listsRepeater'] = $rows;
	}

	/* ── Layout ───────────────────────────────────────────────────────── */
	if ( ! empty( $input['listType'] ) && 'vertical' !== $input['listType'] ) {
		$attrs['listType'] = sanitize_text_field( $input['listType'] ); }
	if ( ! empty( $input['alignment'] ) && 'left' !== $input['alignment'] ) {
		$attrs['alignment'] = array(
			'md' => sanitize_text_field( $input['alignment'] ),
			'sm' => '',
			'xs' => '',
		);
	}
	if ( ! empty( $input['verticalSpace'] ) ) {
		$attrs['listSpaceBetween'] = array(
			'md'   => (string) absint( $input['verticalSpace'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['horizontalSpace'] ) ) {
		$attrs['horizontalSpaceBetween'] = array(
			'md'   => (string) absint( $input['horizontalSpace'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['separatorColor'] ) ) {
		$attrs['separatorColor'] = sanitize_text_field( $input['separatorColor'] ); }

	/* ── Read more toggle ─────────────────────────────────────────────── */
	if ( ! empty( $input['readMoreToggle'] ) ) {
		$attrs['readMoreToggle'] = true;
		if ( ! empty( $input['showListCount'] ) && '3' !== (string) $input['showListCount'] ) {
			$attrs['showListToggle'] = sanitize_text_field( (string) $input['showListCount'] ); }
		if ( ! empty( $input['readMoreText'] ) && '+ Show all options' !== $input['readMoreText'] ) {
			$attrs['readMoreText'] = sanitize_text_field( $input['readMoreText'] ); }
		if ( ! empty( $input['readLessText'] ) && '- Less options' !== $input['readLessText'] ) {
			$attrs['readLessText'] = sanitize_text_field( $input['readLessText'] ); }
		if ( ! empty( $input['toggleColor'] ) ) {
			$attrs['toggleNormalColor'] = sanitize_text_field( $input['toggleColor'] ); }
		if ( ! empty( $input['toggleColorHover'] ) ) {
			$attrs['toggleHoverColor'] = sanitize_text_field( $input['toggleColorHover'] ); }
		if ( ! empty( $input['toggleIndent'] ) ) {
			$attrs['toggleIndent'] = array(
				'md'   => (string) absint( $input['toggleIndent'] ),
				'unit' => 'px',
			); }
		if ( ! empty( $input['toggleTypoSize'] ) ) {
			$attrs['toggleTypo'] = tpgb_mcp_sl2_typo( (int) $input['toggleTypoSize'] ); }
	}

	/* ── Icon style ───────────────────────────────────────────────────── */
	if ( ! empty( $input['iconColor'] ) ) {
		$attrs['iconNormalColor'] = sanitize_text_field( $input['iconColor'] ); }
	if ( ! empty( $input['iconColorHover'] ) ) {
		$attrs['iconHoverColor'] = sanitize_text_field( $input['iconColorHover'] ); }
	if ( ! empty( $input['iconSize'] ) ) {
		$attrs['iconSize'] = array(
			'md'   => (string) absint( $input['iconSize'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['iconImgSize'] ) ) {
		$attrs['iconImgSize'] = array(
			'md'   => (string) absint( $input['iconImgSize'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['iconSvgSize'] ) ) {
		$attrs['iconSvgSize'] = array(
			'md'   => (string) absint( $input['iconSvgSize'] ),
			'unit' => 'px',
		); }
	if ( isset( $input['iconAlignment'] ) && false === $input['iconAlignment'] ) {
		$attrs['iconAlignment'] = false; }

	if ( ! empty( $input['svgStrokeColor'] ) ) {
		$attrs['headSvgColor'] = sanitize_text_field( $input['svgStrokeColor'] ); }
	if ( ! empty( $input['svgFillColor'] ) ) {
		$attrs['headSvgfill'] = sanitize_text_field( $input['svgFillColor'] ); }
	if ( ! empty( $input['svgStrokeColorHover'] ) ) {
		$attrs['svgstroHov'] = sanitize_text_field( $input['svgStrokeColorHover'] ); }
	if ( ! empty( $input['svgFillColorHover'] ) ) {
		$attrs['svgfillHov'] = sanitize_text_field( $input['svgFillColorHover'] ); }

	if ( ! empty( $input['iconAdvanced'] ) ) {
		$attrs['iconAdvancedStyle'] = true;
		if ( ! empty( $input['iconWidth'] ) ) {
			$attrs['iconWidth'] = array(
				'md'   => (string) absint( $input['iconWidth'] ),
				'unit' => 'px',
			); }
		if ( ! empty( $input['iconBorder'] ) ) {
			$attrs['iconBorder'] = tpgb_mcp_sl2_border( $input['iconBorder'] ); }
		if ( ! empty( $input['iconBorderHover'] ) ) {
			$attrs['iconBorderHover'] = tpgb_mcp_sl2_border( $input['iconBorderHover'] ); }
		if ( ! empty( $input['iconBRadius'] ) ) {
			$attrs['iconBorderRadius'] = tpgb_mcp_sl2_radius( $input['iconBRadius'] ); }
		if ( ! empty( $input['iconBRadiusHover'] ) ) {
			$attrs['iconBorderRadiusHover'] = tpgb_mcp_sl2_radius( $input['iconBRadiusHover'] ); }
		if ( ! empty( $input['iconBgColor'] ) ) {
			$attrs['iconBg'] = tpgb_mcp_sl2_bg( $input['iconBgColor'] ); }
		if ( ! empty( $input['iconBgColorHover'] ) ) {
			$attrs['iconBgHover'] = tpgb_mcp_sl2_bg( $input['iconBgColorHover'] ); }
		if ( ! empty( $input['iconShadow'] ) ) {
			$attrs['iconBoxShadow'] = tpgb_mcp_sl2_bshadow( $input['iconShadow'] ); }
		if ( ! empty( $input['iconShadowHover'] ) ) {
			$attrs['iconBoxShadowHover'] = tpgb_mcp_sl2_bshadow( $input['iconShadowHover'] ); }
	}

	/* ── Text style ───────────────────────────────────────────────────── */
	if ( ! empty( $input['textTypoSize'] ) ) {
		$attrs['textTypo'] = tpgb_mcp_sl2_typo( (int) $input['textTypoSize'] ); }
	if ( ! empty( $input['textColor'] ) ) {
		$attrs['textNormalColor'] = sanitize_text_field( $input['textColor'] ); }
	if ( ! empty( $input['textColorHover'] ) ) {
		$attrs['textHoverColor'] = sanitize_text_field( $input['textColorHover'] ); }
	if ( ! empty( $input['textIndent'] ) ) {
		$attrs['textIndent'] = array(
			'md'   => (string) absint( $input['textIndent'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['textPadding'] ) ) {
		$attrs['textPadding'] = tpgb_mcp_sl2_spacing( $input['textPadding'] ); }
	if ( ! empty( $input['textBorder'] ) ) {
		$attrs['textBorder'] = tpgb_mcp_sl2_border( $input['textBorder'] ); }
	if ( ! empty( $input['textBRadius'] ) ) {
		$attrs['textBRadius'] = tpgb_mcp_sl2_radius( $input['textBRadius'] ); }
	if ( ! empty( $input['textBgColor'] ) ) {
		$attrs['textBg'] = tpgb_mcp_sl2_bg( $input['textBgColor'] ); }
	if ( ! empty( $input['textShadow'] ) ) {
		$attrs['titleBShadow'] = tpgb_mcp_sl2_bshadow( $input['textShadow'] ); }
	if ( ! empty( $input['textBorderHover'] ) ) {
		$attrs['textHBorder'] = tpgb_mcp_sl2_border( $input['textBorderHover'] ); }
	if ( ! empty( $input['textBRadiusHover'] ) ) {
		$attrs['textHBRadius'] = tpgb_mcp_sl2_radius( $input['textBRadiusHover'] ); }
	if ( ! empty( $input['textBgColorHover'] ) ) {
		$attrs['textBgHover'] = tpgb_mcp_sl2_bg( $input['textBgColorHover'] ); }
	if ( ! empty( $input['textShadowHover'] ) ) {
		$attrs['titleHBShadow'] = tpgb_mcp_sl2_bshadow( $input['textShadowHover'] ); }

	/* ── Pin / hint ───────────────────────────────────────────────────── */
	if ( ! empty( $input['pinAlignment'] ) && 'right' !== $input['pinAlignment'] ) {
		$attrs['pinAlignment'] = sanitize_text_field( $input['pinAlignment'] ); }
	if ( ! empty( $input['pinTypoSize'] ) ) {
		$attrs['pinTypo'] = tpgb_mcp_sl2_typo( (int) $input['pinTypoSize'] ); }
	if ( ! empty( $input['pinShadow'] ) ) {
		$attrs['pinBoxShadow'] = tpgb_mcp_sl2_bshadow( $input['pinShadow'] ); }
	if ( ! empty( $input['pinBRadius'] ) ) {
		$attrs['pinBRadius'] = tpgb_mcp_sl2_radius( $input['pinBRadius'] ); }
	if ( ! empty( $input['pinPadding'] ) ) {
		$attrs['pinPadding'] = tpgb_mcp_sl2_spacing( $input['pinPadding'] ); }
	if ( ! empty( $input['pinHorizontalAdjust'] ) ) {
		$attrs['pinHorizontalAdjust'] = array(
			'md'   => (string) intval( $input['pinHorizontalAdjust'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['pinVerticalAdjust'] ) ) {
		$attrs['pinVerticalAdjust'] = array(
			'md'   => (string) intval( $input['pinVerticalAdjust'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['pinLeftMinWidth'] ) && 60 !== intval( $input['pinLeftMinWidth'] ) ) {
		$attrs['pinLeftWidth'] = array(
			'md'   => (string) absint( $input['pinLeftMinWidth'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['pinRightMinWidth'] ) ) {
		$attrs['pinRightWidth'] = array(
			'md'   => (string) absint( $input['pinRightMinWidth'] ),
			'unit' => 'px',
		); }

	/* ── Tooltip ──────────────────────────────────────────────────────── */
	if ( ! empty( $input['tipInteractive'] ) ) {
		$attrs['tipInteractive'] = true; }
	if ( ! empty( $input['tipPlacement'] ) && 'top' !== $input['tipPlacement'] ) {
		$attrs['tipPlacement'] = sanitize_text_field( $input['tipPlacement'] ); }
	if ( ! empty( $input['tipTheme'] ) ) {
		$attrs['tipTheme'] = sanitize_text_field( $input['tipTheme'] ); }
	if ( ! empty( $input['tipMaxWidth'] ) ) {
		$attrs['tipMaxWidth'] = (string) absint( $input['tipMaxWidth'] ); }
	if ( ! empty( $input['tipOffset'] ) ) {
		$attrs['tipOffset'] = sanitize_text_field( (string) $input['tipOffset'] ); }
	if ( ! empty( $input['followCursor'] ) ) {
		$attrs['followCursor'] = true; }
	if ( ! empty( $input['tipDistance'] ) ) {
		$attrs['tipDistance'] = sanitize_text_field( (string) $input['tipDistance'] ); }
	if ( isset( $input['tipArrow'] ) && false === $input['tipArrow'] ) {
		$attrs['tipArrow'] = false; }
	if ( ! empty( $input['tipTriggers'] ) && 'mouseenter' !== $input['tipTriggers'] ) {
		$attrs['tipTriggers'] = sanitize_text_field( $input['tipTriggers'] ); }
	if ( ! empty( $input['tipAnimation'] ) ) {
		$attrs['tipAnimation'] = sanitize_text_field( $input['tipAnimation'] ); }
	if ( ! empty( $input['tipDurationIn'] ) ) {
		$attrs['tipDurationIn'] = sanitize_text_field( (string) $input['tipDurationIn'] ); }
	if ( ! empty( $input['tipDurationOut'] ) ) {
		$attrs['tipDurationOut'] = sanitize_text_field( (string) $input['tipDurationOut'] ); }
	if ( ! empty( $input['tipArrowColor'] ) ) {
		$attrs['tipArrowColor'] = sanitize_text_field( $input['tipArrowColor'] ); }
	if ( ! empty( $input['tipPadding'] ) ) {
		$attrs['tipPadding'] = tpgb_mcp_sl2_spacing( $input['tipPadding'] ); }
	if ( ! empty( $input['tipBorder'] ) ) {
		$attrs['tipBorder'] = tpgb_mcp_sl2_border( $input['tipBorder'] ); }
	if ( ! empty( $input['tipBorderRadius'] ) ) {
		$attrs['tipBorderRadius'] = tpgb_mcp_sl2_radius( $input['tipBorderRadius'] ); }
	if ( ! empty( $input['tipBgColor'] ) ) {
		$attrs['tipBg'] = tpgb_mcp_sl2_bg( $input['tipBgColor'] ); }
	if ( ! empty( $input['tipBoxShadow'] ) ) {
		$attrs['tipBoxShadow'] = tpgb_mcp_sl2_bshadow( $input['tipBoxShadow'] ); }

	/* ── Hover inverse ────────────────────────────────────────────────── */
	if ( ! empty( $input['hoverBgStyle'] ) ) {
		$attrs['hover_bg_style'] = true; }
	if ( ! empty( $input['hoverInverseEffect'] ) ) {
		$attrs['hoverInverseEffect'] = true;
		if ( isset( $input['unhoverOpacity'] ) && 0.6 !== (float) $input['unhoverOpacity'] ) {
			$attrs['unhoverItemOpacity'] = (string) $input['unhoverOpacity'];
		}
	}
	if ( ! empty( $input['effectArea'] ) && 'individual' !== $input['effectArea'] ) {
		$attrs['effectArea'] = sanitize_key( $input['effectArea'] ); }

	/* ── Visibility / Globals ─────────────────────────────────────────── */
	if ( ! empty( $input['hideDesktop'] ) ) {
		$attrs['globalHideDesktop'] = true; }
	if ( ! empty( $input['hideTablet'] ) ) {
		$attrs['globalHideTablet'] = true; }
	if ( ! empty( $input['hideMobile'] ) ) {
		$attrs['globalHideMobile'] = true; }
	if ( ! empty( $input['globalClasses'] ) ) {
		$attrs['globalClasses'] = sanitize_text_field( $input['globalClasses'] ); }
	if ( ! empty( $input['globalId'] ) ) {
		$attrs['globalId'] = sanitize_text_field( $input['globalId'] ); }
	if ( ! empty( $input['globalCustomCss'] ) ) {
		$attrs['globalCustomCss'] = wp_strip_all_tags( $input['globalCustomCss'] ); }
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
		$attrs['globalMargin'] = tpgb_mcp_sl2_spacing( $input['globalMargin'] ); }
	if ( ! empty( $input['globalPadding'] ) ) {
		$attrs['globalPadding'] = tpgb_mcp_sl2_spacing( $input['globalPadding'] ); }
	if ( ! empty( $input['globalBgColor'] ) ) {
		$attrs['globalBg'] = tpgb_mcp_sl2_bg( $input['globalBgColor'] ); }
	if ( ! empty( $input['globalBgHoverColor'] ) ) {
		$attrs['globalBgHover'] = tpgb_mcp_sl2_bg( $input['globalBgHoverColor'] ); }
	if ( ! empty( $input['globalBorder'] ) ) {
		$attrs['globalBorder'] = tpgb_mcp_sl2_border( $input['globalBorder'] ); }
	if ( ! empty( $input['globalBorderHover'] ) ) {
		$attrs['globalBorderHover'] = tpgb_mcp_sl2_border( $input['globalBorderHover'] ); }
	if ( ! empty( $input['globalBRadius'] ) ) {
		$attrs['globalBRadius'] = tpgb_mcp_sl2_radius( $input['globalBRadius'] ); }
	if ( ! empty( $input['globalBRadiusHover'] ) ) {
		$attrs['globalBRadiusHover'] = tpgb_mcp_sl2_radius( $input['globalBRadiusHover'] ); }
	if ( ! empty( $input['globalBShadow'] ) ) {
		$attrs['globalBShadow'] = tpgb_mcp_sl2_bshadow( $input['globalBShadow'] ); }
	if ( ! empty( $input['globalBShadowHover'] ) ) {
		$attrs['globalBShadowHover'] = tpgb_mcp_sl2_bshadow( $input['globalBShadowHover'] ); }
	if ( ! empty( $input['scrollAnimation'] ) ) {
		$attrs['globalAnim'] = array( 'md' => sanitize_text_field( $input['scrollAnimation'] ) ); }
	if ( ! empty( $input['animDuration'] ) ) {
		$attrs['globalAnimDuration'] = sanitize_text_field( $input['animDuration'] ); }
	if ( ! empty( $input['animDelay'] ) ) {
		$attrs['globalAnimDelay'] = array( 'md' => sanitize_text_field( $input['animDelay'] ) ); }
	if ( ! empty( $input['animEasing'] ) ) {
		$attrs['globalAnimEasing'] = sanitize_text_field( $input['animEasing'] ); }

	if ( tpgb_mcp_sl2_needs_wrapper( $attrs ) ) {
		$attrs['tpgbDisrule'] = true; }

	tpgb_mcp_apply_typo_decoration( $attrs, $input );
	$attrs = tpgb_mcp_merge_block_settings( $attrs, $input['settings'] ?? array() );

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
