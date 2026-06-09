<?php
/**
 * Ability: Add Nexter Blocks Pricing Table (tpgb/tp-pricing-table) to a Gutenberg page.
 *
 * @package The_Plus_Addons_For_Block_Editor
 * @since   1.3.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_register_ability(
	'nexter-blocks/add-tpgb-pricing-table',
	array(
		'label'               => __( 'Add Nexter Blocks Pricing Table', 'the-plus-addons-for-block-editor' ),
		'description'         => __( 'Adds the Nexter Blocks Pricing Table block (tpgb/tp-pricing-table) — a full pricing plan card with title/subtitle, icon, price with pre/post text, strike-through previous price, features list (WYSIWYG content or stylish list), CTA button, ribbon/badge, and extensive styling for each element. Use for SaaS pricing, membership plans, service packages. This is a dynamic block.', 'the-plus-addons-for-block-editor' ),
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

				/* ── Style presets ────────────────────────────────────────── */
				'style'                 => array(
					'type'        => 'string',
					'default'     => 'style-1',
					'description' => 'Overall style preset (style-1+).',
				),
				'titleStyle'            => array(
					'type'    => 'string',
					'default' => 'style-1',
				),
				'priceStyle'            => array(
					'type'    => 'string',
					'default' => 'style-1',
				),
				'contentStyle'          => array(
					'type'        => 'string',
					'enum'        => array( 'wysiwyg', 'list' ),
					'default'     => 'wysiwyg',
					'description' => 'Feature list content mode.',
				),
				'listStyle'             => array(
					'type'    => 'string',
					'default' => 'style-1',
				),
				'wysiwygStyle'          => array(
					'type'    => 'string',
					'default' => 'style-1',
				),
				'hoverStyle'            => array(
					'type'    => 'string',
					'enum'    => array( 'hover_normal', 'hover_reverse' ),
					'default' => 'hover_normal',
				),

				/* ── Header content ───────────────────────────────────────── */
				'title'                 => array(
					'type'    => 'string',
					'default' => 'Professional',
				),
				'subTitle'              => array(
					'type'    => 'string',
					'default' => 'Designed for Agency',
				),

				/* ── Icon ─────────────────────────────────────────────────── */
				'iconType'              => array(
					'type'    => 'string',
					'enum'    => array( 'none', 'icon', 'image', 'svg' ),
					'default' => 'none',
				),
				'iconClass'             => array(
					'type'    => 'string',
					'default' => 'fas fa-home',
				),
				'imageUrl'              => array(
					'type'    => 'string',
					'default' => '',
				),
				'imageId'               => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'svgUrl'                => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Price ────────────────────────────────────────────────── */
				'preText'               => array(
					'type'        => 'string',
					'default'     => '$',
					'description' => 'Currency/text before price.',
				),
				'price'                 => array(
					'type'    => 'string',
					'default' => '99.99',
				),
				'postText'              => array(
					'type'        => 'string',
					'default'     => 'Per Year',
					'description' => 'Text after price (e.g. "Per Year", "Monthly").',
				),
				'showPreviousPrice'     => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Show strikethrough previous price.',
				),
				'previousPreText'       => array(
					'type'    => 'string',
					'default' => '$',
				),
				'previousPrice'         => array(
					'type'    => 'string',
					'default' => '199.99',
				),
				'previousPostText'      => array(
					'type'    => 'string',
					'default' => '',
				),
				'previousPriceAlign'    => array(
					'type'    => 'string',
					'enum'    => array( 'top', 'bottom' ),
					'default' => 'top',
				),

				/* ── Content / Features list ──────────────────────────────── */
				'wysiwygContent'        => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Feature list HTML (when contentStyle is "wysiwyg").',
				),
				'listItems'             => array(
					'type'        => 'array',
					'description' => 'Features (when contentStyle is "list"). Array of {text, icon, tooltip} objects.',
					'items'       => array(
						'type'       => 'object',
						'properties' => array(
							'text'    => array( 'type' => 'string' ),
							'icon'    => array(
								'type'        => 'string',
								'description' => 'Font Awesome class.',
							),
							'tooltip' => array( 'type' => 'string' ),
						),
					),
				),
				'readMoreToggle'        => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'showListCount'         => array(
					'type'        => 'string',
					'default'     => '3',
					'description' => 'Number of items to show before "Show more".',
				),
				'readMoreText'          => array(
					'type'    => 'string',
					'default' => '+ Show all options',
				),
				'readLessText'          => array(
					'type'    => 'string',
					'default' => '- Less options',
				),

				/* ── Ribbon/Badge ─────────────────────────────────────────── */
				'showRibbon'            => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'ribbonStyle'           => array(
					'type'    => 'string',
					'default' => 'style-1',
				),
				'ribbonText'            => array(
					'type'    => 'string',
					'default' => 'Recommended',
				),

				/* ── CTA Button ───────────────────────────────────────────── */
				'ctaText'               => array(
					'type'    => 'string',
					'default' => '',
				),
				'enableCtaTypo'         => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'ctaTypoSize'           => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'ctaColor'              => array(
					'type'    => 'string',
					'default' => '',
				),
				'ctaButtonPosition'     => array(
					'type'    => 'string',
					'enum'    => array( 'top', 'bottom' ),
					'default' => 'top',
				),

				/* ── Icon styling ─────────────────────────────────────────── */
				'iconDisplayStyle'      => array(
					'type'    => 'string',
					'enum'    => array( 'square', 'circle', 'rounded', 'none' ),
					'default' => 'square',
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

				/* ── Title/Subtitle styling ───────────────────────────────── */
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
				'enableSubTitleTypo'    => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'subTitleTypoSize'      => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'subTitleColor'         => array(
					'type'    => 'string',
					'default' => '',
				),
				'subTitleHoverColor'    => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Price styling ────────────────────────────────────────── */
				'enablePriceTypo'       => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'priceTypoSize'         => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'priceColor'            => array(
					'type'    => 'string',
					'default' => '',
				),
				'priceHoverColor'       => array(
					'type'    => 'string',
					'default' => '',
				),
				'enablePostfixTypo'     => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'postfixTypoSize'       => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'postfixColor'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'postfixHoverColor'     => array(
					'type'    => 'string',
					'default' => '',
				),
				'enablePrevPriceTypo'   => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'prevPriceTypoSize'     => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'prevPriceColor'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'prevPriceHoverColor'   => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Content/list styling ─────────────────────────────────── */
				'enableWysiwygTypo'     => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'wysiwygTypoSize'       => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'wysiwygTextColor'      => array(
					'type'    => 'string',
					'default' => '',
				),
				'wysiwygTextHoverColor' => array(
					'type'    => 'string',
					'default' => '',
				),
				'wysiwygBorderWidth'    => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Border between items (width %).',
				),
				'wysiwygBorderColor'    => array(
					'type'    => 'string',
					'default' => '',
				),
				'wysiwygAlignment'      => array(
					'type'    => 'string',
					'enum'    => array( 'left', 'center', 'right' ),
					'default' => 'center',
				),

				/* ── Stylish list styling ─────────────────────────────────── */
				'listIconSize'          => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'listIconColor'         => array(
					'type'    => 'string',
					'default' => '',
				),
				'listIconHoverColor'    => array(
					'type'    => 'string',
					'default' => '',
				),
				'listTextColor'         => array(
					'type'    => 'string',
					'default' => '',
				),
				'listTextHoverColor'    => array(
					'type'    => 'string',
					'default' => '',
				),
				'listBorderColor'       => array(
					'type'    => 'string',
					'default' => '',
				),
				'listSpacing'           => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'listAlignment'         => array(
					'type'    => 'string',
					'enum'    => array( 'flex-start', 'center', 'flex-end' ),
					'default' => 'flex-start',
				),
				'enableListContentTypo' => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'listContentTypoSize'   => array(
					'type'    => 'integer',
					'default' => 0,
				),

				/* ── Tooltip/Pin styling ──────────────────────────────────── */
				'pinColor'              => array(
					'type'    => 'string',
					'default' => '',
				),
				'pinBgColor'            => array(
					'type'    => 'string',
					'default' => '',
				),
				'pinBorderRadius'       => array( 'type' => 'object' ),

				/* ── Box styling ──────────────────────────────────────────── */
				'innerPadding'          => array( 'type' => 'object' ),
				'areaPadding'           => array( 'type' => 'object' ),
				'areaBorder'            => array( 'type' => 'object' ),
				'boxBgColor'            => array(
					'type'    => 'string',
					'default' => '',
				),
				'boxBgHoverColor'       => array(
					'type'    => 'string',
					'default' => '',
				),
				'boxOverlayColor'       => array(
					'type'    => 'string',
					'default' => '',
				),
				'boxOverlayHoverColor'  => array(
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
				'enableBoxShadowHover'  => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'boxShadowHoverH'       => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'boxShadowHoverV'       => array(
					'type'    => 'integer',
					'default' => 4,
				),
				'boxShadowHoverBlur'    => array(
					'type'    => 'integer',
					'default' => 8,
				),
				'boxShadowHoverSpread'  => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'boxShadowHoverColor'   => array(
					'type'    => 'string',
					'default' => 'rgba(0,0,0,0.40)',
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

				'settings'              => array(
					'type'        => 'object',
					'description' => 'Raw overrides for any of the 117 internal attributes.',
				),
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

		'execute_callback'    => 'tpgb_mcp_add_pricing_table_ability',
		'permission_callback' => 'tpgb_mcp_add_pricing_table_permission',
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
 * Permission callback for the add-pricing-table ability.
 *
 * @param array|null $input Ability input arguments.
 * @return bool True when the current user may insert the block.
 */
function tpgb_mcp_add_pricing_table_permission( ?array $input = null ): bool {
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
function tpgb_mcp_ptbl_spacing( array $v ): array {
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
function tpgb_mcp_ptbl_border( array $b ): array {
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
function tpgb_mcp_ptbl_radius( array $r ): array {
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
function tpgb_mcp_ptbl_bshadow( array $s ): array {
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
function tpgb_mcp_ptbl_bg( string $color ): array {
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
function tpgb_mcp_ptbl_needs_wrapper( array $attrs ): bool {
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
 * Execute callback: insert a tpgb/tp-pricing-table block into a post.
 *
 * @param array $input Ability input arguments.
 * @return array|WP_Error Ability result or error on failure.
 */
function tpgb_mcp_add_pricing_table_ability( array $input ) {

	if ( ! defined( 'TPGB_VERSION' ) && ! defined( 'TPGBP_VERSION' ) ) {
		return new WP_Error( 'nexter_blocks_missing', __( 'Nexter Blocks must be active.', 'the-plus-addons-for-block-editor' ) );
	}

	$block_name = 'tpgb/tp-pricing-table';
	if ( ! tpgb_mcp_has_registered_block( $block_name ) ) {
		return new WP_Error( 'block_missing', __( 'tpgb/tp-pricing-table is not registered.', 'the-plus-addons-for-block-editor' ) );
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

	/* ── Styles ───────────────────────────────────────────────────────── */
	if ( ! empty( $input['style'] ) && 'style-1' !== $input['style'] ) {
		$attrs['style'] = sanitize_text_field( $input['style'] ); }
	if ( ! empty( $input['titleStyle'] ) && 'style-1' !== $input['titleStyle'] ) {
		$attrs['titleStyle'] = sanitize_text_field( $input['titleStyle'] ); }
	if ( ! empty( $input['priceStyle'] ) && 'style-1' !== $input['priceStyle'] ) {
		$attrs['priceStyle'] = sanitize_text_field( $input['priceStyle'] ); }
	if ( ! empty( $input['contentStyle'] ) && 'wysiwyg' !== $input['contentStyle'] ) {
		$attrs['contentStyle'] = sanitize_key( $input['contentStyle'] ); }
	if ( ! empty( $input['listStyle'] ) && 'style-1' !== $input['listStyle'] ) {
		$attrs['conListStyle'] = sanitize_text_field( $input['listStyle'] ); }
	if ( ! empty( $input['wysiwygStyle'] ) && 'style-1' !== $input['wysiwygStyle'] ) {
		$attrs['wyStyle'] = sanitize_text_field( $input['wysiwygStyle'] ); }
	if ( ! empty( $input['hoverStyle'] ) && 'hover_normal' !== $input['hoverStyle'] ) {
		$attrs['hoverStyle'] = sanitize_key( $input['hoverStyle'] ); }

	/* ── Header content ───────────────────────────────────────────────── */
	if ( ! empty( $input['title'] ) && 'Professional' !== $input['title'] ) {
		$attrs['title'] = tpgb_mcp_clean_text( $input['title'] ); }
	if ( ! empty( $input['subTitle'] ) && 'Designed for Agency' !== $input['subTitle'] ) {
		$attrs['subTitle'] = tpgb_mcp_clean_text( $input['subTitle'] ); }

	/* ── Icon ─────────────────────────────────────────────────────────── */
	$icon_type = sanitize_key( $input['iconType'] ?? 'none' );
	if ( 'none' !== $icon_type ) {
		$attrs['iconType'] = $icon_type;
		if ( 'icon' === $icon_type && ! empty( $input['iconClass'] ) && 'fas fa-home' !== $input['iconClass'] ) {
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
				$attrs['imgStore'] = $img; }
		}
		if ( 'svg' === $icon_type && ! empty( $input['svgUrl'] ) ) {
			$attrs['svgIcon'] = array( 'url' => esc_url_raw( $input['svgUrl'] ) );
		}
	}

	/* ── Price ────────────────────────────────────────────────────────── */
	if ( ! empty( $input['preText'] ) && '$' !== $input['preText'] ) {
		$attrs['preText'] = sanitize_text_field( $input['preText'] ); }
	if ( ! empty( $input['price'] ) && '99.99' !== $input['price'] ) {
		$attrs['priceValue'] = sanitize_text_field( $input['price'] ); }
	if ( ! empty( $input['postText'] ) && 'Per Year' !== $input['postText'] ) {
		$attrs['postText'] = sanitize_text_field( $input['postText'] ); }

	if ( ! empty( $input['showPreviousPrice'] ) ) {
		$attrs['disPrePrice'] = true;
		if ( ! empty( $input['previousPreText'] ) && '$' !== $input['previousPreText'] ) {
			$attrs['prevPreText'] = sanitize_text_field( $input['previousPreText'] ); }
		if ( ! empty( $input['previousPrice'] ) && '199.99' !== $input['previousPrice'] ) {
			$attrs['prevPriceValue'] = sanitize_text_field( $input['previousPrice'] ); }
		if ( ! empty( $input['previousPostText'] ) ) {
			$attrs['prevPostText'] = sanitize_text_field( $input['previousPostText'] ); }
		if ( ! empty( $input['previousPriceAlign'] ) && 'top' !== $input['previousPriceAlign'] ) {
			$attrs['prevPriceAlign'] = sanitize_key( $input['previousPriceAlign'] ); }
	}

	/* ── Features list ────────────────────────────────────────────────── */
	$default_wy = 'All features of plan will be available here.</br></br>- Feature 1</br>- Feature 2</br>- Feature 3';
	if ( isset( $input['wysiwygContent'] ) && $default_wy !== $input['wysiwygContent'] && '' !== $input['wysiwygContent'] ) {
		$attrs['wyContent'] = tpgb_mcp_clean_text( $input['wysiwygContent'] );
	}

	if ( ! empty( $input['listItems'] ) && is_array( $input['listItems'] ) ) {
		$items = array();
		foreach ( $input['listItems'] as $i => $it ) {
			if ( ! is_array( $it ) ) {
				continue; }
			$items[] = array(
				'_key'        => (string) $i,
				'listText'    => tpgb_mcp_clean_text( $it['text'] ?? '' ),
				'listIcon'    => sanitize_text_field( $it['icon'] ?? '' ),
				'listTooltip' => sanitize_text_field( $it['tooltip'] ?? '' ),
			);
		}
		if ( ! empty( $items ) ) {
			$attrs['stylishList'] = $items; }
	}

	if ( ! empty( $input['readMoreToggle'] ) ) {
		$attrs['readMoreToggle'] = true;
		if ( ! empty( $input['showListCount'] ) && '3' !== $input['showListCount'] ) {
			$attrs['showListToggle'] = sanitize_text_field( $input['showListCount'] ); }
		if ( ! empty( $input['readMoreText'] ) && '+ Show all options' !== $input['readMoreText'] ) {
			$attrs['readMoreText'] = sanitize_text_field( $input['readMoreText'] ); }
		if ( ! empty( $input['readLessText'] ) && '- Less options' !== $input['readLessText'] ) {
			$attrs['readLessText'] = sanitize_text_field( $input['readLessText'] ); }
	}

	/* ── Ribbon ───────────────────────────────────────────────────────── */
	if ( ! empty( $input['showRibbon'] ) ) {
		$attrs['disRibbon'] = true;
		if ( ! empty( $input['ribbonStyle'] ) && 'style-1' !== $input['ribbonStyle'] ) {
			$attrs['ribbonStyle'] = sanitize_text_field( $input['ribbonStyle'] ); }
		if ( ! empty( $input['ribbonText'] ) && 'Recommended' !== $input['ribbonText'] ) {
			$attrs['ribbonText'] = sanitize_text_field( $input['ribbonText'] ); }
	}

	/* ── CTA ──────────────────────────────────────────────────────────── */
	if ( ! empty( $input['ctaText'] ) ) {
		$attrs['ctaText'] = sanitize_text_field( $input['ctaText'] ); }
	if ( ! empty( $input['enableCtaTypo'] ) ) {
		$attrs['ctaTypo'] = array(
			'openTypography' => 1,
			'size'           => array(
				'md'   => ! empty( $input['ctaTypoSize'] ) ? (string) absint( $input['ctaTypoSize'] ) : '',
				'unit' => 'px',
			),
			'height'         => '',
			'spacing'        => '',
			'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
		);
	}
	if ( ! empty( $input['ctaColor'] ) ) {
		$attrs['ctaColor'] = sanitize_text_field( $input['ctaColor'] ); }
	if ( ! empty( $input['ctaButtonPosition'] ) && 'top' !== $input['ctaButtonPosition'] ) {
		$attrs['extbtnPosition'] = sanitize_key( $input['ctaButtonPosition'] ); }

	/* ── Icon styling ─────────────────────────────────────────────────── */
	if ( ! empty( $input['iconDisplayStyle'] ) && 'square' !== $input['iconDisplayStyle'] ) {
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
		$attrs['icnNormalBG'] = tpgb_mcp_ptbl_bg( $input['iconBgColor'] ); }
	if ( ! empty( $input['iconBgHoverColor'] ) ) {
		$attrs['icnHoverBG'] = tpgb_mcp_ptbl_bg( $input['iconBgHoverColor'] ); }
	if ( ! empty( $input['iconBorderColor'] ) ) {
		$attrs['nmlBColor'] = sanitize_text_field( $input['iconBorderColor'] ); }
	if ( ! empty( $input['iconBorderHoverColor'] ) ) {
		$attrs['hvrBColor'] = sanitize_text_field( $input['iconBorderHoverColor'] ); }
	if ( ! empty( $input['iconBorderRadius'] ) ) {
		$attrs['nmlIcnBRadius'] = tpgb_mcp_ptbl_radius( $input['iconBorderRadius'] ); }
	if ( ! empty( $input['iconBorderRadiusHover'] ) ) {
		$attrs['hvrIcnBRadius'] = tpgb_mcp_ptbl_radius( $input['iconBorderRadiusHover'] ); }

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

	/* ── Title/Subtitle styling ───────────────────────────────────────── */
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
		$attrs['subTitleNmlColor'] = sanitize_text_field( $input['subTitleColor'] ); }
	if ( ! empty( $input['subTitleHoverColor'] ) ) {
		$attrs['subTitleHvrColor'] = sanitize_text_field( $input['subTitleHoverColor'] ); }

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
		$attrs['priceNmlColor'] = sanitize_text_field( $input['priceColor'] ); }
	if ( ! empty( $input['priceHoverColor'] ) ) {
		$attrs['priceHvrColor'] = sanitize_text_field( $input['priceHoverColor'] ); }
	if ( ! empty( $input['enablePostfixTypo'] ) ) {
		$attrs['postfixTypo'] = array(
			'openTypography' => 1,
			'size'           => array(
				'md'   => ! empty( $input['postfixTypoSize'] ) ? (string) absint( $input['postfixTypoSize'] ) : '',
				'unit' => 'px',
			),
			'height'         => '',
			'spacing'        => '',
			'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
		);
	}
	if ( ! empty( $input['postfixColor'] ) ) {
		$attrs['postfixNmlColor'] = sanitize_text_field( $input['postfixColor'] ); }
	if ( ! empty( $input['postfixHoverColor'] ) ) {
		$attrs['postfixHvrColor'] = sanitize_text_field( $input['postfixHoverColor'] ); }
	if ( ! empty( $input['enablePrevPriceTypo'] ) ) {
		$attrs['prevPriceTypo'] = array(
			'openTypography' => 1,
			'size'           => array(
				'md'   => ! empty( $input['prevPriceTypoSize'] ) ? (string) absint( $input['prevPriceTypoSize'] ) : '',
				'unit' => 'px',
			),
			'height'         => '',
			'spacing'        => '',
			'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
		);
	}
	if ( ! empty( $input['prevPriceColor'] ) ) {
		$attrs['prevPriceNmlColor'] = sanitize_text_field( $input['prevPriceColor'] ); }
	if ( ! empty( $input['prevPriceHoverColor'] ) ) {
		$attrs['prevPriceHvrColor'] = sanitize_text_field( $input['prevPriceHoverColor'] ); }

	/* ── Content / WYSIWYG styling ────────────────────────────────────── */
	if ( ! empty( $input['enableWysiwygTypo'] ) ) {
		$attrs['wysiwygTypo'] = array(
			'openTypography' => 1,
			'size'           => array(
				'md'   => ! empty( $input['wysiwygTypoSize'] ) ? (string) absint( $input['wysiwygTypoSize'] ) : '',
				'unit' => 'px',
			),
			'height'         => '',
			'spacing'        => '',
			'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
		);
	}
	if ( ! empty( $input['wysiwygTextColor'] ) ) {
		$attrs['wysiwygTextColor'] = sanitize_text_field( $input['wysiwygTextColor'] ); }
	if ( ! empty( $input['wysiwygTextHoverColor'] ) ) {
		$attrs['wysiwygTextHvrColor'] = sanitize_text_field( $input['wysiwygTextHoverColor'] ); }
	if ( ! empty( $input['wysiwygBorderWidth'] ) ) {
		$attrs['wyBorderWidth'] = array(
			'md'   => (string) absint( $input['wysiwygBorderWidth'] ),
			'unit' => '%',
		); }
	if ( ! empty( $input['wysiwygBorderColor'] ) ) {
		$attrs['wysiwygBColor'] = sanitize_text_field( $input['wysiwygBorderColor'] ); }
	if ( ! empty( $input['wysiwygAlignment'] ) && 'center' !== $input['wysiwygAlignment'] ) {
		$attrs['wysiwygAlign'] = sanitize_key( $input['wysiwygAlignment'] ); }

	/* ── Stylish list styling ─────────────────────────────────────────── */
	if ( ! empty( $input['listIconSize'] ) ) {
		$attrs['listIconSize'] = array(
			'md'   => (string) absint( $input['listIconSize'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['listIconColor'] ) ) {
		$attrs['listIcnNmlColor'] = sanitize_text_field( $input['listIconColor'] ); }
	if ( ! empty( $input['listIconHoverColor'] ) ) {
		$attrs['listIcnHvrColor'] = sanitize_text_field( $input['listIconHoverColor'] ); }
	if ( ! empty( $input['listTextColor'] ) ) {
		$attrs['listTextNmlColor'] = sanitize_text_field( $input['listTextColor'] ); }
	if ( ! empty( $input['listTextHoverColor'] ) ) {
		$attrs['listTextHvrColor'] = sanitize_text_field( $input['listTextHoverColor'] ); }
	if ( ! empty( $input['listBorderColor'] ) ) {
		$attrs['listBColor'] = sanitize_text_field( $input['listBorderColor'] ); }
	if ( ! empty( $input['listSpacing'] ) ) {
		$attrs['listSpace'] = array(
			'md'   => (string) absint( $input['listSpacing'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['listAlignment'] ) && 'flex-start' !== $input['listAlignment'] ) {
		$attrs['listAlign'] = sanitize_key( $input['listAlignment'] ); }
	if ( ! empty( $input['enableListContentTypo'] ) ) {
		$attrs['listContentTypo'] = array(
			'openTypography' => 1,
			'size'           => array(
				'md'   => ! empty( $input['listContentTypoSize'] ) ? (string) absint( $input['listContentTypoSize'] ) : '',
				'unit' => 'px',
			),
			'height'         => '',
			'spacing'        => '',
			'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
		);
	}

	/* ── Pin/Tooltip styling ──────────────────────────────────────────── */
	if ( ! empty( $input['pinColor'] ) ) {
		$attrs['pinColor'] = sanitize_text_field( $input['pinColor'] ); }
	if ( ! empty( $input['pinBgColor'] ) ) {
		$attrs['pinBG'] = array(
			'openBg'         => 1,
			'bgType'         => 'color',
			'bgDefaultColor' => sanitize_text_field( $input['pinBgColor'] ),
		); }
	if ( ! empty( $input['pinBorderRadius'] ) ) {
		$attrs['pinBRadius'] = tpgb_mcp_ptbl_radius( $input['pinBorderRadius'] ); }

	/* ── Box styling ──────────────────────────────────────────────────── */
	if ( ! empty( $input['innerPadding'] ) ) {
		$attrs['innerPadding'] = tpgb_mcp_ptbl_spacing( $input['innerPadding'] ); }
	if ( ! empty( $input['areaPadding'] ) ) {
		$attrs['areaPadding'] = tpgb_mcp_ptbl_spacing( $input['areaPadding'] ); }
	if ( ! empty( $input['areaBorder'] ) ) {
		$attrs['areaBorder'] = tpgb_mcp_ptbl_border( $input['areaBorder'] ); }
	if ( ! empty( $input['boxBgColor'] ) ) {
		$attrs['normalBG'] = tpgb_mcp_ptbl_bg( $input['boxBgColor'] ); }
	if ( ! empty( $input['boxBgHoverColor'] ) ) {
		$attrs['hoverBG'] = tpgb_mcp_ptbl_bg( $input['boxBgHoverColor'] ); }
	if ( ! empty( $input['boxOverlayColor'] ) ) {
		$attrs['nmlOverlay'] = sanitize_text_field( $input['boxOverlayColor'] ); }
	if ( ! empty( $input['boxOverlayHoverColor'] ) ) {
		$attrs['hvrOverlay'] = sanitize_text_field( $input['boxOverlayHoverColor'] ); }
	if ( ! empty( $input['boxBorder'] ) ) {
		$attrs['bgNmlBorder'] = tpgb_mcp_ptbl_border( $input['boxBorder'] ); }
	if ( ! empty( $input['boxBorderHover'] ) ) {
		$attrs['bgHvrBorder'] = tpgb_mcp_ptbl_border( $input['boxBorderHover'] ); }
	if ( ! empty( $input['boxBorderRadius'] ) ) {
		$attrs['bgNmlBRadius'] = tpgb_mcp_ptbl_radius( $input['boxBorderRadius'] ); }
	if ( ! empty( $input['boxBorderRadiusHover'] ) ) {
		$attrs['bgHvrBRadius'] = tpgb_mcp_ptbl_radius( $input['boxBorderRadiusHover'] ); }
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
	if ( ! empty( $input['enableBoxShadowHover'] ) ) {
		$attrs['bgHvrShadow'] = array(
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
		$attrs['globalMargin'] = tpgb_mcp_ptbl_spacing( $input['globalMargin'] );  }
	if ( ! empty( $input['globalPadding'] ) ) {
		$attrs['globalPadding'] = tpgb_mcp_ptbl_spacing( $input['globalPadding'] ); }
	if ( ! empty( $input['globalBgColor'] ) ) {
		$attrs['globalBg'] = tpgb_mcp_ptbl_bg( $input['globalBgColor'] );      }
	if ( ! empty( $input['globalBgHoverColor'] ) ) {
		$attrs['globalBgHover'] = tpgb_mcp_ptbl_bg( $input['globalBgHoverColor'] ); }
	if ( ! empty( $input['globalBorder'] ) ) {
		$attrs['globalBorder'] = tpgb_mcp_ptbl_border( $input['globalBorder'] );       }
	if ( ! empty( $input['globalBorderHover'] ) ) {
		$attrs['globalBorderHover'] = tpgb_mcp_ptbl_border( $input['globalBorderHover'] );  }
	if ( ! empty( $input['globalBRadius'] ) ) {
		$attrs['globalBRadius'] = tpgb_mcp_ptbl_radius( $input['globalBRadius'] );      }
	if ( ! empty( $input['globalBRadiusHover'] ) ) {
		$attrs['globalBRadiusHover'] = tpgb_mcp_ptbl_radius( $input['globalBRadiusHover'] ); }
	if ( ! empty( $input['globalBShadow'] ) ) {
		$attrs['globalBShadow'] = tpgb_mcp_ptbl_bshadow( $input['globalBShadow'] );      }
	if ( ! empty( $input['globalBShadowHover'] ) ) {
		$attrs['globalBShadowHover'] = tpgb_mcp_ptbl_bshadow( $input['globalBShadowHover'] ); }

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
	if ( tpgb_mcp_ptbl_needs_wrapper( $attrs ) ) {
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
