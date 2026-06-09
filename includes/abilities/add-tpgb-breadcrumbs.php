<?php
/**
 * Ability: Add Nexter Blocks Breadcrumbs (tpgb/tp-breadcrumbs) to a Gutenberg page.
 *
 * @package The_Plus_Addons_For_Block_Editor
 * @since   1.3.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_register_ability(
	'nexter-blocks/add-tpgb-breadcrumbs',
	array(
		'label'               => __( 'Add Nexter Blocks Breadcrumbs', 'the-plus-addons-for-block-editor' ),
		'description'         => __( 'Adds the Nexter Blocks Breadcrumbs block (tpgb/tp-breadcrumbs) with full support for style presets, home icon, separator icon, visibility toggles, taxonomy terms, text styling, spacing, backgrounds, borders, shadows, schema markup, animations, transforms, and advanced settings. This is a dynamic block — breadcrumb links are generated automatically based on the current page context.', 'the-plus-addons-for-block-editor' ),
		'category'            => 'nexter-blocks',

		'input_schema'        => array(
			'type'                 => 'object',
			'properties'           => array(

				/* ── Core ─────────────────────────────────────────────────── */
				'post_id'              => array(
					'type'        => 'integer',
					'description' => 'WordPress page/post ID to insert the block into.',
				),
				'position'             => array(
					'type'        => 'integer',
					'description' => 'Zero-based insert position. Use -1 to append.',
					'default'     => -1,
				),
				'parent_block_id'      => array(
					'type'        => 'string',
					'description' => 'Optional block_id of a parent container block. When provided, this block is inserted INSIDE the parent as an inner block instead of at the page top level.',
					'default'     => '',
				),

				/* ── Style ────────────────────────────────────────────────── */
				'style'                => array(
					'type'        => 'string',
					'enum'        => array( 'style-1', 'style-2' ),
					'description' => 'Visual style preset. style-1 = Simple with optional full-width bar; style-2 = Modern with individual item spacing.',
					'default'     => 'style-1',
				),
				'bredWidth'            => array(
					'type'        => 'boolean',
					'description' => 'Full width toggle (style-1 only).',
					'default'     => false,
				),
				'bredAlign'            => array(
					'type'        => 'string',
					'enum'        => array( 'left', 'center', 'right', '' ),
					'description' => 'Text alignment for desktop.',
					'default'     => '',
				),

				/* ── Home Title / Icon ────────────────────────────────────── */
				'homeTitle'            => array(
					'type'        => 'string',
					'description' => 'Label text for the home breadcrumb.',
					'default'     => 'Home',
				),
				'homeIcon'             => array(
					'type'        => 'string',
					'enum'        => array( 'icon', '' ),
					'description' => 'Show an icon before the home label. "icon" = show, "" = none.',
					'default'     => 'icon',
				),
				'iconFontStyle'        => array(
					'type'        => 'string',
					'enum'        => array( 'font_awesome', 'icon_image' ),
					'description' => 'Icon source type for the home icon.',
					'default'     => 'font_awesome',
				),
				'iconFawesome'         => array(
					'type'        => 'string',
					'description' => 'Font Awesome class for the home icon e.g. "fas fa-home".',
					'default'     => 'fas fa-home',
				),

				/* ── Separator Icon ───────────────────────────────────────── */
				'sepIcon'              => array(
					'type'        => 'string',
					'enum'        => array( 'sep_icon', '' ),
					'description' => 'Show a separator icon between breadcrumbs. "sep_icon" = show, "" = none.',
					'default'     => 'sep_icon',
				),
				'sepIconFontStyle'     => array(
					'type'        => 'string',
					'enum'        => array( 'sep_font_awesome', 'sep_icon_image' ),
					'description' => 'Icon source type for the separator.',
					'default'     => 'sep_font_awesome',
				),
				'sepIconFawesome'      => array(
					'type'        => 'string',
					'description' => 'Font Awesome class for the separator e.g. "fas fa-angle-right".',
					'default'     => 'fas fa-angle-right',
				),

				/* ── Visibility toggles ───────────────────────────────────── */
				'bdToggleHome'         => array(
					'type'        => 'boolean',
					'default'     => true,
					'description' => 'Show the home breadcrumb.',
				),
				'bdToggleParent'       => array(
					'type'        => 'boolean',
					'default'     => true,
					'description' => 'Show the parent page breadcrumb.',
				),
				'bdToggleCurrent'      => array(
					'type'        => 'boolean',
					'default'     => true,
					'description' => 'Show the current page breadcrumb.',
				),
				'letterLimitParent'    => array(
					'type'        => 'integer',
					'default'     => 20,
					'description' => 'Character limit for parent title (0 = no limit).',
				),
				'letterLimitCurrent'   => array(
					'type'        => 'integer',
					'default'     => 20,
					'description' => 'Character limit for current page title (0 = no limit).',
				),

				/* ── Taxonomy terms ────────────────────────────────────────── */
				'showTerms'            => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Show taxonomy terms in the breadcrumb trail.',
				),
				'taxonomySlug'         => array(
					'type'        => 'string',
					'default'     => 'category',
					'description' => 'Taxonomy slug to display e.g. "category", "post_tag".',
				),
				'showpartTerms'        => array(
					'type'        => 'boolean',
					'default'     => true,
					'description' => 'Show parent category in taxonomy trail.',
				),
				'showchildTerms'       => array(
					'type'        => 'boolean',
					'default'     => true,
					'description' => 'Show child category in taxonomy trail.',
				),

				/* ── SEO ──────────────────────────────────────────────────── */
				'markupSch'            => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Enable Google Schema markup for breadcrumbs.',
				),

				/* ── Text styling ─────────────────────────────────────────── */
				'enableTypo'           => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Enable custom breadcrumb typography.',
				),
				'typoSize'             => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Font size in px.',
				),
				'typoGlobalPreset'     => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Global typography preset ID.',
				),
				'textColor'            => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Text colour for all breadcrumbs (normal).',
				),
				'textHColor'           => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Text colour for all breadcrumbs (hover).',
				),
				'activeColorCurrent'   => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Apply a distinct active colour to the current page title.',
				),
				'textBorder'           => array(
					'type'        => 'object',
					'description' => 'Text border (normal) {type,color,width:{top,right,bottom,left,unit}}.',
				),
				'textBorderHover'      => array(
					'type'        => 'object',
					'description' => 'Text border (hover).',
				),

				/* ── Item spacing (style-2) ───────────────────────────────── */
				'bredMargin'           => array(
					'type'        => 'object',
					'description' => 'Gap between breadcrumb items (style-2) {top,right,bottom,left,unit}.',
				),
				'bredPadding'          => array(
					'type'        => 'object',
					'description' => 'Padding for breadcrumb items (style-2) {top,right,bottom,left,unit}.',
				),

				/* ── Home icon styling ────────────────────────────────────── */
				'iconPadding'          => array(
					'type'        => 'object',
					'description' => 'Padding around home icon {top,right,bottom,left,unit}.',
				),
				'iconSize'             => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Home icon font size in px.',
				),
				'iconColor'            => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Home icon colour (normal).',
				),
				'iconColorHover'       => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Home icon colour (hover).',
				),

				/* ── Separator styling ────────────────────────────────────── */
				'sepPadding'           => array(
					'type'        => 'object',
					'description' => 'Separator padding {top,right,bottom,left,unit}.',
				),
				'sepSize'              => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Separator icon font size in px.',
				),
				'sepColor'             => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Separator icon colour (normal).',
				),
				'sepColorHover'        => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Separator icon colour (hover).',
				),

				/* ── Individual item backgrounds ──────────────────────────── */
				'bredAll'              => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Background colour for all items (normal).',
				),
				'bredHome'             => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Background colour for home item (normal).',
				),
				'bredCurrent'          => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Background colour for current/active item (normal).',
				),
				'bredAllHover'         => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Background colour for all items (hover).',
				),
				'bredHomeHover'        => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Background colour for home item (hover).',
				),
				'bredCurrentHover'     => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Background colour for current/active item (hover).',
				),

				/* ── Per-item spacing (style-1) ───────────────────────────── */
				'sepBgPadding'         => array(
					'type'        => 'object',
					'description' => 'Padding for each breadcrumb item (style-1) {top,right,bottom,left,unit}.',
				),
				'sepBgMargin'          => array(
					'type'        => 'object',
					'description' => 'Margin between breadcrumb items {top,right,bottom,left,unit}.',
				),
				'sepBorderRadius'      => array(
					'type'        => 'object',
					'description' => 'Border radius for each item (style-1) {top,right,bottom,left,unit}.',
				),

				/* ── Content background (style-1) ─────────────────────────── */
				'contentBgColor'       => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Container background colour (normal, style-1).',
				),
				'contentBgHoverColor'  => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Container background colour (hover, style-1).',
				),
				'contentBgPadding'     => array(
					'type'        => 'object',
					'description' => 'Container padding (style-1) {top,right,bottom,left,unit}.',
				),
				'contentBorder'        => array(
					'type'        => 'object',
					'description' => 'Container border (normal, style-1) {type,color,width}.',
				),
				'contentBorderHover'   => array(
					'type'        => 'object',
					'description' => 'Container border (hover, style-1).',
				),
				'contentBorderRad'     => array(
					'type'        => 'object',
					'description' => 'Container border radius (normal, style-1) {top,right,bottom,left,unit}.',
				),
				'contentBorderRadH'    => array(
					'type'        => 'object',
					'description' => 'Container border radius (hover, style-1).',
				),
				'enableBoxShadow'      => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Enable container box shadow (normal, style-1).',
				),
				'boxShadowHorizontal'  => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'boxShadowVertical'    => array(
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
				'enableBoxShadowH'     => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Enable container box shadow (hover, style-1).',
				),
				'boxShadowHHorizontal' => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'boxShadowHVertical'   => array(
					'type'    => 'integer',
					'default' => 4,
				),
				'boxShadowHBlur'       => array(
					'type'    => 'integer',
					'default' => 8,
				),
				'boxShadowHSpread'     => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'boxShadowHColor'      => array(
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
					'type'        => 'string',
					'default'     => '',
					'description' => 'Animation delay in seconds e.g. "0.2".',
				),
				'animEasing'           => array(
					'type'    => 'string',
					'enum'    => array( '', 'linear', 'ease', 'ease-in', 'ease-out', 'ease-in-out' ),
					'default' => '',
				),

				/* ── Advanced: Visibility ────────────────────────────────── */
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

				/* ── Advanced: Identity ──────────────────────────────────── */
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

				/* ── Advanced: Layout ────────────────────────────────────── */
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

				/* ── Advanced: Transition ────────────────────────────────── */
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

				/* ── Global: Spacing ─────────────────────────────────────── */
				'globalMargin'         => array(
					'type'        => 'object',
					'description' => 'Outer margin {top,bottom,left,right,unit}.',
				),
				'globalPadding'        => array(
					'type'        => 'object',
					'description' => 'Inner padding {top,bottom,left,right,unit}.',
				),

				/* ── Global: Background ──────────────────────────────────── */
				'globalBgColor'        => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Normal background colour.',
				),
				'globalBgHoverColor'   => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Hover background colour.',
				),

				/* ── Global: Border ──────────────────────────────────────── */
				'globalBorder'         => array(
					'type'        => 'object',
					'description' => 'Normal border {type,color,width}.',
				),
				'globalBorderHover'    => array(
					'type'        => 'object',
					'description' => 'Hover border.',
				),

				/* ── Global: Border radius ───────────────────────────────── */
				'globalBRadius'        => array(
					'type'        => 'object',
					'description' => 'Normal border radius {top,bottom,left,right,unit}.',
				),
				'globalBRadiusHover'   => array(
					'type'        => 'object',
					'description' => 'Hover border radius.',
				),

				/* ── Global: Box shadow ──────────────────────────────────── */
				'globalBShadow'        => array(
					'type'        => 'object',
					'description' => 'Normal box shadow {horizontal,vertical,blur,spread,color}.',
				),
				'globalBShadowHover'   => array(
					'type'        => 'object',
					'description' => 'Hover box shadow.',
				),

				/* ── Transform: Rotate ───────────────────────────────────── */
				'rotateDeg'            => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Rotation degrees. Setting any value enables the transform.',
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

				/* ── Transform: Offset ───────────────────────────────────── */
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

				/* ── Transform: Scale ────────────────────────────────────── */
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

				/* ── Transform: Skew ─────────────────────────────────────── */
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

				/* ── Transform: Flip ─────────────────────────────────────── */
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

				/* ── Raw override ───────────────────────────────────────── */
				'settings'             => array(
					'type'        => 'object',
					'description' => 'Raw attribute overrides merged directly into the block.',
				),
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

		'execute_callback'    => 'tpgb_mcp_add_breadcrumbs_ability',
		'permission_callback' => 'tpgb_mcp_add_breadcrumbs_permission',
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
 * Permission callback for the add-breadcrumbs ability.
 *
 * @param array|null $input Ability input arguments.
 * @return bool True when the current user may insert the block.
 */
function tpgb_mcp_add_breadcrumbs_permission( ?array $input = null ): bool {
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
function tpgb_mcp_breadcrumbs_spacing( array $v ): array {
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
function tpgb_mcp_breadcrumbs_border( array $b ): array {
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
function tpgb_mcp_breadcrumbs_radius( array $r ): array {
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
function tpgb_mcp_breadcrumbs_bshadow( array $s ): array {
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
function tpgb_mcp_breadcrumbs_bg( string $color ): array {
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
function tpgb_mcp_breadcrumbs_needs_wrapper( array $attrs ): bool {
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
 * Execute callback: insert a tpgb/tp-breadcrumbs block into a post.
 *
 * @param array $input Ability input arguments.
 * @return array|WP_Error Ability result or error on failure.
 */
function tpgb_mcp_add_breadcrumbs_ability( array $input ) {

	if ( ! defined( 'TPGB_VERSION' ) && ! defined( 'TPGBP_VERSION' ) ) {
		return new WP_Error( 'nexter_blocks_missing', __( 'Nexter Blocks must be active.', 'the-plus-addons-for-block-editor' ) );
	}

	$block_name = 'tpgb/tp-breadcrumbs';

	if ( ! tpgb_mcp_has_registered_block( $block_name ) ) {
		return new WP_Error( 'block_missing', __( 'tpgb/tp-breadcrumbs is not registered.', 'the-plus-addons-for-block-editor' ) );
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

	/* ── Style ────────────────────────────────────────────────────────── */
	$style          = sanitize_key( $input['style'] ?? 'style-1' );
	$attrs['style'] = in_array( $style, array( 'style-1', 'style-2' ), true ) ? $style : 'style-1';

	if ( ! empty( $input['bredWidth'] ) ) {
		$attrs['bredWidth'] = true; }

	if ( ! empty( $input['bredAlign'] ) ) {
		$align = sanitize_text_field( $input['bredAlign'] );
		if ( in_array( $align, array( 'left', 'center', 'right' ), true ) ) {
			$attrs['bredAlign'] = array(
				'md' => $align,
				'sm' => '',
				'xs' => '',
			);
		}
	}

	/* ── Home Title / Icon ────────────────────────────────────────────── */
	if ( isset( $input['homeTitle'] ) && 'Home' !== $input['homeTitle'] ) {
		$attrs['homeTitle'] = sanitize_text_field( $input['homeTitle'] );
	}

	$home_icon         = sanitize_key( $input['homeIcon'] ?? 'icon' );
	$attrs['homeIcon'] = in_array( $home_icon, array( 'icon', '' ), true ) ? $home_icon : 'icon';

	if ( 'icon' === $attrs['homeIcon'] ) {
		$icon_font_style        = sanitize_key( $input['iconFontStyle'] ?? 'font_awesome' );
		$attrs['iconFontStyle'] = in_array( $icon_font_style, array( 'font_awesome', 'icon_image' ), true ) ? $icon_font_style : 'font_awesome';

		if ( 'font_awesome' === $attrs['iconFontStyle'] && ! empty( $input['iconFawesome'] ) ) {
			$attrs['iconFawesome'] = sanitize_text_field( $input['iconFawesome'] );
		}
	}

	/* ── Separator Icon ───────────────────────────────────────────────── */
	$sep_icon         = sanitize_key( $input['sepIcon'] ?? 'sep_icon' );
	$attrs['sepIcon'] = in_array( $sep_icon, array( 'sep_icon', '' ), true ) ? $sep_icon : 'sep_icon';

	if ( 'sep_icon' === $attrs['sepIcon'] ) {
		$sep_font_style            = sanitize_key( $input['sepIconFontStyle'] ?? 'sep_font_awesome' );
		$attrs['sepIconFontStyle'] = in_array( $sep_font_style, array( 'sep_font_awesome', 'sep_icon_image' ), true ) ? $sep_font_style : 'sep_font_awesome';

		if ( 'sep_font_awesome' === $attrs['sepIconFontStyle'] && ! empty( $input['sepIconFawesome'] ) ) {
			$attrs['sepIconFawesome'] = sanitize_text_field( $input['sepIconFawesome'] );
		}
	}

	// ── Visibility toggles ──────────────────────────────────────────────
	// Only set when explicitly false (defaults are true in block.json — omitting keeps the default).
	if ( isset( $input['bdToggleHome'] ) && ! $input['bdToggleHome'] ) {
		$attrs['bdToggleHome'] = false; }
	if ( isset( $input['bdToggleParent'] ) && ! $input['bdToggleParent'] ) {
		$attrs['bdToggleParent'] = false; }
	if ( isset( $input['bdToggleCurrent'] ) && ! $input['bdToggleCurrent'] ) {
		$attrs['bdToggleCurrent'] = false; }

	if ( isset( $input['letterLimitParent'] ) && 20 !== (int) $input['letterLimitParent'] ) {
		$attrs['letterLimitParent'] = (string) absint( $input['letterLimitParent'] );  }
	if ( isset( $input['letterLimitCurrent'] ) && 20 !== (int) $input['letterLimitCurrent'] ) {
		$attrs['letterLimitCurrent'] = (string) absint( $input['letterLimitCurrent'] ); }

	/* ── Taxonomy terms ───────────────────────────────────────────────── */
	if ( ! empty( $input['showTerms'] ) ) {
		$attrs['showTerms'] = true;
		if ( ! empty( $input['taxonomySlug'] ) && 'category' !== $input['taxonomySlug'] ) {
			$attrs['taxonomySlug'] = sanitize_key( $input['taxonomySlug'] );
		}
		// Only set when false (defaults are true).
		if ( isset( $input['showpartTerms'] ) && ! $input['showpartTerms'] ) {
			$attrs['showpartTerms'] = false; }
		if ( isset( $input['showchildTerms'] ) && ! $input['showchildTerms'] ) {
			$attrs['showchildTerms'] = false; }
	}

	/* ── SEO ──────────────────────────────────────────────────────────── */
	if ( ! empty( $input['markupSch'] ) ) {
		$attrs['markupSch'] = true; }

	/* ── Text styling ─────────────────────────────────────────────────── */
	if ( ! empty( $input['enableTypo'] ) ) {
		$bred_typo = array(
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
			$bred_typo['globalTypo'] = sanitize_text_field( $input['typoGlobalPreset'] );
		}
		$attrs['bredTypo'] = $bred_typo;
	}

	if ( ! empty( $input['textColor'] ) ) {
		$attrs['textColor'] = sanitize_text_field( $input['textColor'] );  }
	if ( ! empty( $input['textHColor'] ) ) {
		$attrs['textHColor'] = sanitize_text_field( $input['textHColor'] ); }
	if ( ! empty( $input['activeColorCurrent'] ) ) {
		$attrs['activeColorCurrent'] = true; }
	if ( ! empty( $input['textBorder'] ) ) {
		$attrs['textBorder'] = tpgb_mcp_breadcrumbs_border( $input['textBorder'] );      }
	if ( ! empty( $input['textBorderHover'] ) ) {
		$attrs['textBorderHover'] = tpgb_mcp_breadcrumbs_border( $input['textBorderHover'] ); }

	/* ── Item spacing (style-2) ───────────────────────────────────────── */
	if ( ! empty( $input['bredMargin'] ) ) {
		$attrs['bredMargin'] = tpgb_mcp_breadcrumbs_spacing( $input['bredMargin'] );  }
	if ( ! empty( $input['bredPadding'] ) ) {
		$attrs['bredPadding'] = tpgb_mcp_breadcrumbs_spacing( $input['bredPadding'] ); }

	/* ── Home icon styling ────────────────────────────────────────────── */
	if ( ! empty( $input['iconPadding'] ) ) {
		$attrs['iconPadding'] = tpgb_mcp_breadcrumbs_spacing( $input['iconPadding'] ); }
	if ( ! empty( $input['iconSize'] ) ) {
		$attrs['iconSize'] = array(
			'md'   => (string) absint( $input['iconSize'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['iconColor'] ) ) {
		$attrs['iconColor'] = sanitize_text_field( $input['iconColor'] );     }
	if ( ! empty( $input['iconColorHover'] ) ) {
		$attrs['iconColorHover'] = sanitize_text_field( $input['iconColorHover'] ); }

	/* ── Separator styling ────────────────────────────────────────────── */
	if ( ! empty( $input['sepPadding'] ) ) {
		$attrs['sepPadding'] = tpgb_mcp_breadcrumbs_spacing( $input['sepPadding'] ); }
	if ( ! empty( $input['sepSize'] ) ) {
		$attrs['sepSize'] = array(
			'md'   => (string) absint( $input['sepSize'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['sepColor'] ) ) {
		$attrs['sepColor'] = sanitize_text_field( $input['sepColor'] );      }
	if ( ! empty( $input['sepColorHover'] ) ) {
		$attrs['sepColorHover'] = sanitize_text_field( $input['sepColorHover'] ); }

	/* ── Individual item backgrounds ──────────────────────────────────── */
	if ( ! empty( $input['bredAll'] ) ) {
		$attrs['bredAll'] = sanitize_text_field( $input['bredAll'] );          }
	if ( ! empty( $input['bredHome'] ) ) {
		$attrs['bredHome'] = sanitize_text_field( $input['bredHome'] );         }
	if ( ! empty( $input['bredCurrent'] ) ) {
		$attrs['bredCurrent'] = sanitize_text_field( $input['bredCurrent'] );      }
	if ( ! empty( $input['bredAllHover'] ) ) {
		$attrs['bredAllHover'] = sanitize_text_field( $input['bredAllHover'] );     }
	if ( ! empty( $input['bredHomeHover'] ) ) {
		$attrs['bredHomeHover'] = sanitize_text_field( $input['bredHomeHover'] );    }
	if ( ! empty( $input['bredCurrentHover'] ) ) {
		$attrs['bredCurrentHover'] = sanitize_text_field( $input['bredCurrentHover'] ); }

	/* ── Per-item spacing ─────────────────────────────────────────────── */
	if ( ! empty( $input['sepBgPadding'] ) ) {
		$attrs['sepBgPadding'] = tpgb_mcp_breadcrumbs_spacing( $input['sepBgPadding'] );  }
	if ( ! empty( $input['sepBgMargin'] ) ) {
		$attrs['sepBgMargin'] = tpgb_mcp_breadcrumbs_spacing( $input['sepBgMargin'] );   }
	if ( ! empty( $input['sepBorderRadius'] ) ) {
		$attrs['sepBorderRadius'] = tpgb_mcp_breadcrumbs_radius( $input['sepBorderRadius'] ); }

	/* ── Content background (style-1) ─────────────────────────────────── */
	if ( ! empty( $input['contentBgPadding'] ) ) {
		$attrs['contentBgPadding'] = tpgb_mcp_breadcrumbs_spacing( $input['contentBgPadding'] ); }
	if ( ! empty( $input['contentBgColor'] ) ) {
		$attrs['contentBg'] = tpgb_mcp_breadcrumbs_bg( $input['contentBgColor'] );      }
	if ( ! empty( $input['contentBgHoverColor'] ) ) {
		$attrs['contentBgH'] = tpgb_mcp_breadcrumbs_bg( $input['contentBgHoverColor'] ); }
	if ( ! empty( $input['contentBorder'] ) ) {
		$attrs['contentBorder'] = tpgb_mcp_breadcrumbs_border( $input['contentBorder'] );       }
	if ( ! empty( $input['contentBorderHover'] ) ) {
		$attrs['contentBorderH'] = tpgb_mcp_breadcrumbs_border( $input['contentBorderHover'] );  }
	if ( ! empty( $input['contentBorderRad'] ) ) {
		$attrs['contentBorderRad'] = tpgb_mcp_breadcrumbs_radius( $input['contentBorderRad'] );    }
	if ( ! empty( $input['contentBorderRadH'] ) ) {
		$attrs['contentBorderRadH'] = tpgb_mcp_breadcrumbs_radius( $input['contentBorderRadH'] );   }

	if ( ! empty( $input['enableBoxShadow'] ) ) {
		$attrs['boxShadow'] = array(
			'openShadow' => true,
			'inset'      => 0,
			'horizontal' => (string) intval( $input['boxShadowHorizontal'] ?? 0 ),
			'vertical'   => (string) intval( $input['boxShadowVertical'] ?? 4 ),
			'blur'       => (string) absint( $input['boxShadowBlur'] ?? 8 ),
			'spread'     => (string) intval( $input['boxShadowSpread'] ?? 0 ),
			'color'      => sanitize_text_field( $input['boxShadowColor'] ?? 'rgba(0,0,0,0.40)' ),
		);
	}
	if ( ! empty( $input['enableBoxShadowH'] ) ) {
		$attrs['boxShadowH'] = array(
			'openShadow' => true,
			'inset'      => 0,
			'horizontal' => (string) intval( $input['boxShadowHHorizontal'] ?? 0 ),
			'vertical'   => (string) intval( $input['boxShadowHVertical'] ?? 4 ),
			'blur'       => (string) absint( $input['boxShadowHBlur'] ?? 8 ),
			'spread'     => (string) intval( $input['boxShadowHSpread'] ?? 0 ),
			'color'      => sanitize_text_field( $input['boxShadowHColor'] ?? 'rgba(0,0,0,0.40)' ),
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

	/* ── Global: Spacing ──────────────────────────────────────────────── */
	if ( ! empty( $input['globalMargin'] ) ) {
		$attrs['globalMargin'] = tpgb_mcp_breadcrumbs_spacing( $input['globalMargin'] );  }
	if ( ! empty( $input['globalPadding'] ) ) {
		$attrs['globalPadding'] = tpgb_mcp_breadcrumbs_spacing( $input['globalPadding'] ); }

	/* ── Global: Background ───────────────────────────────────────────── */
	if ( ! empty( $input['globalBgColor'] ) ) {
		$attrs['globalBg'] = tpgb_mcp_breadcrumbs_bg( $input['globalBgColor'] );      }
	if ( ! empty( $input['globalBgHoverColor'] ) ) {
		$attrs['globalBgHover'] = tpgb_mcp_breadcrumbs_bg( $input['globalBgHoverColor'] ); }

	/* ── Global: Border ───────────────────────────────────────────────── */
	if ( ! empty( $input['globalBorder'] ) ) {
		$attrs['globalBorder'] = tpgb_mcp_breadcrumbs_border( $input['globalBorder'] );      }
	if ( ! empty( $input['globalBorderHover'] ) ) {
		$attrs['globalBorderHover'] = tpgb_mcp_breadcrumbs_border( $input['globalBorderHover'] ); }

	/* ── Global: Border radius ────────────────────────────────────────── */
	if ( ! empty( $input['globalBRadius'] ) ) {
		$attrs['globalBRadius'] = tpgb_mcp_breadcrumbs_radius( $input['globalBRadius'] );      }
	if ( ! empty( $input['globalBRadiusHover'] ) ) {
		$attrs['globalBRadiusHover'] = tpgb_mcp_breadcrumbs_radius( $input['globalBRadiusHover'] ); }

	/* ── Global: Box shadow ───────────────────────────────────────────── */
	if ( ! empty( $input['globalBShadow'] ) ) {
		$attrs['globalBShadow'] = tpgb_mcp_breadcrumbs_bshadow( $input['globalBShadow'] );      }
	if ( ! empty( $input['globalBShadowHover'] ) ) {
		$attrs['globalBShadowHover'] = tpgb_mcp_breadcrumbs_bshadow( $input['globalBShadowHover'] ); }

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
	if ( tpgb_mcp_breadcrumbs_needs_wrapper( $attrs ) ) {
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
