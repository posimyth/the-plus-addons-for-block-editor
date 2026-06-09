<?php
/**
 * Ability: Add Nexter Blocks Info Box (tpgb/tp-infobox) to a Gutenberg page.
 *
 * @package The_Plus_Addons_For_Block_Editor
 * @since   1.3.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_register_ability(
	'nexter-blocks/add-tpgb-infobox',
	array(
		'label'               => __( 'Add Nexter Blocks Info Box', 'the-plus-addons-for-block-editor' ),
		'description'         => __( 'Adds the Nexter Blocks Info Box block (tpgb/tp-infobox) — a versatile feature/service card with icon/image/SVG/text icon, title, description, pin badge, optional CTA button, link wrapper, and extensive styling. Supports 10+ style variants (style-1 through style-10+), listing/carousel layouts, icon display styles (none/stacked/framed), gradient text/icons, overlays, separator lines, full colour/typography/spacing/border/shadow control for all elements, and full animation/transform/advanced settings. This is a dynamic block.', 'the-plus-addons-for-block-editor' ),
		'category'            => 'nexter-blocks',

		'input_schema'        => array(
			'type'                 => 'object',
			'properties'           => array(

				/* ── Core ─────────────────────────────────────────────────── */
				'post_id'                => array( 'type' => 'integer' ),
				'position'               => array(
					'type'    => 'integer',
					'default' => -1,
				),
				'parent_block_id'        => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Layout & Style ───────────────────────────────────────── */
				'layoutType'             => array(
					'type'    => 'string',
					'enum'    => array( 'listing', 'carousel' ),
					'default' => 'listing',
				),
				'style'                  => array(
					'type'        => 'string',
					'description' => 'Style preset e.g. "style-1" through "style-10+".',
					'default'     => 'style-1',
				),
				'alignment'              => array(
					'type'    => 'string',
					'enum'    => array( 'left', 'center', 'right' ),
					'default' => 'center',
				),

				/* ── Content ──────────────────────────────────────────────── */
				'title'                  => array(
					'type'        => 'string',
					'description' => 'Main title text.',
					'default'     => 'Amazing Feature',
				),
				'description'            => array(
					'type'        => 'string',
					'description' => 'Description/body text.',
					'default'     => '',
				),

				/* ── Icon type ────────────────────────────────────────────── */
				'iconType'               => array(
					'type'        => 'string',
					'enum'        => array( 'none', 'icon', 'text', 'image', 'svg' ),
					'description' => 'Type of icon/image to display.',
					'default'     => 'icon',
				),
				'iconClass'              => array(
					'type'        => 'string',
					'default'     => 'fab fa-angellist',
					'description' => 'Font Awesome class (when iconType is "icon").',
				),
				'textIcon'               => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Text character/number (when iconType is "text").',
				),
				'imageUrl'               => array(
					'type'    => 'string',
					'default' => '',
				),
				'imageId'                => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'imageSize'              => array(
					'type'    => 'string',
					'default' => 'full',
				),
				'svgUrl'                 => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Pin/Badge ────────────────────────────────────────────── */
				'showPin'                => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Show a pin badge (label/tag).',
				),
				'pinText'                => array(
					'type'        => 'string',
					'default'     => 'New',
					'description' => 'Pin text.',
				),

				/* ── Link (whole info box) ────────────────────────────────── */
				'enableBoxLink'          => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Make the whole info box clickable.',
				),
				'boxLinkUrl'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'boxLinkTarget'          => array(
					'type'    => 'string',
					'enum'    => array( '_self', '_blank' ),
					'default' => '_self',
				),
				'boxLinkNofollow'        => array(
					'type'    => 'boolean',
					'default' => false,
				),

				/* ── CTA Button ───────────────────────────────────────────── */
				'enableCtaButton'        => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'ctaButtonStyle'         => array(
					'type'    => 'string',
					'default' => 'style-7',
				),
				'ctaButtonIconType'      => array(
					'type'    => 'string',
					'enum'    => array( 'none', 'icon' ),
					'default' => 'none',
				),
				'ctaButtonIcon'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'ctaButtonIconPosition'  => array(
					'type'    => 'string',
					'enum'    => array( 'before', 'after' ),
					'default' => 'after',
				),
				'ctaButtonPreset'        => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Global button preset key (e.g. "btnpreset1"). When provided, the CTA button uses the preset styles and raw colour/border/padding values are ignored. The button is forced to style-8.',
				),

				/* ── Title styling ────────────────────────────────────────── */
				'titleTag'               => array(
					'type'    => 'string',
					'enum'    => array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p' ),
					'default' => 'div',
				),
				'enableTitleTypo'        => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'titleTypoSize'          => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'titleColor'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'titleHoverColor'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'titleTopSpace'          => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'titleBottomSpace'       => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'titlePadding'           => array( 'type' => 'object' ),
				'enableTitleSeparator'   => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'titleSeparatorWidth'    => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Separator width in %.',
				),
				'titleSeparatorHeight'   => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Separator height in px.',
				),
				'titleSeparatorColor'    => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Description styling ──────────────────────────────────── */
				'descTag'                => array(
					'type'    => 'string',
					'enum'    => array( 'div', 'span', 'p' ),
					'default' => 'div',
				),
				'enableDescTypo'         => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'descTypoSize'           => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'descColor'              => array(
					'type'    => 'string',
					'default' => '',
				),
				'descHoverColor'         => array(
					'type'    => 'string',
					'default' => '',
				),
				'descMargin'             => array( 'type' => 'object' ),
				'descPadding'            => array( 'type' => 'object' ),

				/* ── Box styling ──────────────────────────────────────────── */
				'boxPadding'             => array( 'type' => 'object' ),
				'boxBgColor'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'boxBgHoverColor'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'boxOverlayColor'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'boxOverlayHoverColor'   => array(
					'type'    => 'string',
					'default' => '',
				),
				'boxBorder'              => array( 'type' => 'object' ),
				'boxBorderHover'         => array( 'type' => 'object' ),
				'boxBorderRadius'        => array( 'type' => 'object' ),
				'boxBorderRadiusHover'   => array( 'type' => 'object' ),
				'enableBoxShadow'        => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'boxShadowH'             => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'boxShadowV'             => array(
					'type'    => 'integer',
					'default' => 4,
				),
				'boxShadowBlur'          => array(
					'type'    => 'integer',
					'default' => 8,
				),
				'boxShadowSpread'        => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'boxShadowColor'         => array(
					'type'    => 'string',
					'default' => 'rgba(0,0,0,0.40)',
				),
				'enableBoxShadowHover'   => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'boxShadowHoverH'        => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'boxShadowHoverV'        => array(
					'type'    => 'integer',
					'default' => 4,
				),
				'boxShadowHoverBlur'     => array(
					'type'    => 'integer',
					'default' => 8,
				),
				'boxShadowHoverSpread'   => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'boxShadowHoverColor'    => array(
					'type'    => 'string',
					'default' => 'rgba(0,0,0,0.40)',
				),
				'enableMinHeight'        => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'minHeight'              => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'verticalCenter'         => array(
					'type'    => 'boolean',
					'default' => false,
				),

				/* ── Icon styling ─────────────────────────────────────────── */
				'iconDisplayStyle'       => array(
					'type'        => 'string',
					'enum'        => array( 'none', 'stacked', 'framed' ),
					'description' => '"stacked" = solid background; "framed" = bordered container.',
					'default'     => 'none',
				),
				'iconSize'               => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'iconWidth'              => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'iconColor'              => array(
					'type'    => 'string',
					'default' => '',
				),
				'iconHoverColor'         => array(
					'type'    => 'string',
					'default' => '',
				),
				'iconBgColor'            => array(
					'type'    => 'string',
					'default' => '',
				),
				'iconBgHoverColor'       => array(
					'type'    => 'string',
					'default' => '',
				),
				'iconBorder'             => array( 'type' => 'object' ),
				'iconBorderHover'        => array( 'type' => 'object' ),
				'iconBorderRadius'       => array( 'type' => 'object' ),
				'iconBorderRadiusHover'  => array( 'type' => 'object' ),
				'iconShineEffect'        => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'iconGradient'           => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'iconGradientColor'      => array(
					'type'    => 'string',
					'default' => '',
				),
				'iconGradientHover'      => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'iconGradientHoverColor' => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Text icon styling ────────────────────────────────────── */
				'enableTextIconTypo'     => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'textIconTypoSize'       => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'textIconColor'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'textIconHoverColor'     => array(
					'type'    => 'string',
					'default' => '',
				),
				'textIconBgColor'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'textIconBgHoverColor'   => array(
					'type'    => 'string',
					'default' => '',
				),
				'textIconPadding'        => array( 'type' => 'object' ),

				/* ── Image styling ────────────────────────────────────────── */
				'imageWidth'             => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'imageBorder'            => array( 'type' => 'object' ),
				'imageBorderHover'       => array( 'type' => 'object' ),
				'imageBorderRadius'      => array( 'type' => 'object' ),
				'imageBorderRadiusHover' => array( 'type' => 'object' ),
				'imageOverlay'           => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Enable overlay effect on image.',
				),

				/* ── SVG styling ──────────────────────────────────────────── */
				'svgDrawType'            => array(
					'type'    => 'string',
					'enum'    => array( 'delayed', 'sync', 'oneByOne' ),
					'default' => 'delayed',
				),
				'svgDuration'            => array(
					'type'    => 'string',
					'default' => '90',
				),
				'svgMaxWidth'            => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'svgStrokeColor'         => array(
					'type'    => 'string',
					'default' => '#000000',
				),
				'svgFillColor'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'svgStrokeHoverColor'    => array(
					'type'    => 'string',
					'default' => '',
				),
				'svgFillHoverColor'      => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Pin/Badge styling ────────────────────────────────────── */
				'enablePinTypo'          => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'pinTypoSize'            => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'pinTextColor'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'pinBgColor'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'pinTextHoverColor'      => array(
					'type'    => 'string',
					'default' => '',
				),
				'pinBgHoverColor'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'pinPadding'             => array( 'type' => 'object' ),
				'pinBorder'              => array( 'type' => 'object' ),
				'pinBorderRadius'        => array( 'type' => 'object' ),
				'pinHorizontalAdjust'    => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'pinVerticalAdjust'      => array(
					'type'    => 'integer',
					'default' => 0,
				),

				/* ── CTA button styling ───────────────────────────────────── */
				'enableCtaBtnTypo'       => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'ctaBtnTypoSize'         => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'ctaBtnTextColor'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'ctaBtnTextHoverColor'   => array(
					'type'    => 'string',
					'default' => '',
				),
				'ctaBtnTopSpace'         => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'ctaBtnBottomSpace'      => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'ctaBtnIconSpacing'      => array(
					'type'    => 'integer',
					'default' => 5,
				),
				'ctaBtnIconSize'         => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'ctaBtnPadding'          => array( 'type' => 'object' ),
				'ctaBtnBorder'           => array( 'type' => 'object' ),
				'ctaBtnBRadius'          => array( 'type' => 'object' ),
				'ctaBtnBgColor'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'ctaBtnHoverBorder'      => array( 'type' => 'object' ),
				'ctaBtnHoverBRadius'     => array( 'type' => 'object' ),
				'ctaBtnHoverBgColor'     => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Scroll animation ────────────────────────────────────── */
				'scrollAnimation'        => array(
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
				'animDuration'           => array(
					'type'    => 'string',
					'enum'    => array( '', 'slow', 'normal', 'fast' ),
					'default' => '',
				),
				'animDelay'              => array(
					'type'    => 'string',
					'default' => '',
				),
				'animEasing'             => array(
					'type'    => 'string',
					'enum'    => array( '', 'linear', 'ease', 'ease-in', 'ease-out', 'ease-in-out' ),
					'default' => '',
				),

				/* ── Advanced ─────────────────────────────────────────────── */
				'hideDesktop'            => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'hideTablet'             => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'hideMobile'             => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'globalClasses'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalId'               => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalCustomCss'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalWidth'            => array(
					'type'    => 'string',
					'enum'    => array( '', 'inline', 'full', 'custom' ),
					'default' => '',
				),
				'globalZindex'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalPosition'         => array(
					'type'    => 'string',
					'enum'    => array( '', 'relative', 'absolute', 'fixed', 'sticky' ),
					'default' => '',
				),
				'transitionDuration'     => array(
					'type'    => 'string',
					'default' => '',
				),
				'transitionFunction'     => array(
					'type'    => 'string',
					'enum'    => array( '', 'ease', 'ease-in', 'ease-out', 'ease-in-out', 'linear' ),
					'default' => '',
				),
				'transitionOrigin'       => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalMargin'           => array( 'type' => 'object' ),
				'globalPadding'          => array( 'type' => 'object' ),
				'globalBgColor'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalBgHoverColor'     => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalBorder'           => array( 'type' => 'object' ),
				'globalBorderHover'      => array( 'type' => 'object' ),
				'globalBRadius'          => array( 'type' => 'object' ),
				'globalBRadiusHover'     => array( 'type' => 'object' ),
				'globalBShadow'          => array( 'type' => 'object' ),
				'globalBShadowHover'     => array( 'type' => 'object' ),

				'rotateDeg'              => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotateX'                => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotateY'                => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotatePerspective'      => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotateDegHover'         => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotateXHover'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotateYHover'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotatePersHover'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'offsetX'                => array(
					'type'    => 'string',
					'default' => '',
				),
				'offsetY'                => array(
					'type'    => 'string',
					'default' => '',
				),
				'offsetZ'                => array(
					'type'    => 'string',
					'default' => '',
				),
				'offsetXHover'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'offsetYHover'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'offsetZHover'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'scaleValue'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'scaleKeepRatio'         => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'scaleValueHover'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'skewX'                  => array(
					'type'    => 'string',
					'default' => '',
				),
				'skewY'                  => array(
					'type'    => 'string',
					'default' => '',
				),
				'skewXHover'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'skewYHover'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'flipHorizontal'         => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'flipVertical'           => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'flipHorizontalHover'    => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'flipVerticalHover'      => array(
					'type'    => 'boolean',
					'default' => false,
				),

				'settings'               => array(
					'type'        => 'object',
					'description' => 'Raw attribute overrides for any of the 164 internal attributes.',
				),
				'fontFamily'             => array(
					'type'        => 'string',
					'description' => 'Font family name (e.g. "Inter", "Roboto", "Playfair Display"). When inspecting a URL via nexter-blocks/inspect-page, pass the returned fonts[].family value verbatim.',
					'default'     => '',
				),
				'fontType'               => array(
					'type'        => 'string',
					'description' => 'Font category — "sans-serif", "serif", "display", "handwriting", or "monospace".',
					'default'     => '',
				),
				'customFont'             => array(
					'type'        => 'string',
					'description' => 'Custom (non-Google) font name. Overrides fontFamily.',
					'default'     => '',
				),
				'fontWeight'             => array(
					'type'        => 'string',
					'description' => 'Font weight as a string ("100"..."900"). Embedded inside every typography group\'s fontFamily.fontWeight on this block. For per-group control, use the settings raw override or sprout/update-element.',
					'default'     => '',
				),
				'textDecoration'         => array(
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

		'execute_callback'    => 'tpgb_mcp_add_infobox_ability',
		'permission_callback' => 'tpgb_mcp_add_infobox_permission',
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
 * Permission callback for the add-infobox ability.
 *
 * @param array|null $input Ability input arguments.
 * @return bool True when the current user may insert the block.
 */
function tpgb_mcp_add_infobox_permission( ?array $input = null ): bool {
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
function tpgb_mcp_ibx_spacing( array $v ): array {
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
function tpgb_mcp_ibx_border( array $b ): array {
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
function tpgb_mcp_ibx_radius( array $r ): array {
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
function tpgb_mcp_ibx_bshadow( array $s ): array {
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
function tpgb_mcp_ibx_bg( string $color ): array {
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
function tpgb_mcp_ibx_needs_wrapper( array $attrs ): bool {
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
 * Execute callback: insert a tpgb/tp-infobox block into a post.
 *
 * @param array $input Ability input arguments.
 * @return array|WP_Error Ability result or error on failure.
 */
function tpgb_mcp_add_infobox_ability( array $input ) {

	if ( ! defined( 'TPGB_VERSION' ) && ! defined( 'TPGBP_VERSION' ) ) {
		return new WP_Error( 'nexter_blocks_missing', __( 'Nexter Blocks must be active.', 'the-plus-addons-for-block-editor' ) );
	}

	$block_name = 'tpgb/tp-infobox';
	if ( ! tpgb_mcp_has_registered_block( $block_name ) ) {
		return new WP_Error( 'block_missing', __( 'tpgb/tp-infobox is not registered.', 'the-plus-addons-for-block-editor' ) );
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

	/* ── CTA button preset ────────────────────────────────────────────── */
	$has_preset = false;
	$preset_key = sanitize_text_field( $input['ctaButtonPreset'] ?? '' );
	if ( '' !== $preset_key ) {
		$validated = tpgb_mcp_validate_button_preset( $preset_key );
		if ( is_wp_error( $validated ) ) {
			return $validated; }

		$presets = Tpgb_Button_Preset_Vars::get_active_presets();
		$preset  = $presets[ $validated ] ?? null;
		if ( empty( $preset ) ) {
			return new WP_Error(
				'preset_not_found',
				sprintf(
					/* translators: %s: button preset key. */
					__( 'Button preset "%s" was not found.', 'the-plus-addons-for-block-editor' ),
					$validated
				)
			);
		}
		if ( is_object( $preset ) ) {
			$preset = json_decode( wp_json_encode( $preset ), true );
		}

		$attrs['carouselBtn'] = true;
		$attrs['carBtnStyle'] = 'style-8';

		if ( array_key_exists( 'bTypo', $preset ) ) {
			$attrs['cBtnTypo'] = $preset['bTypo']; }
		if ( array_key_exists( 'btColor', $preset ) ) {
			$attrs['cBtnTextColor'] = $preset['btColor']; }
		if ( array_key_exists( 'bthColor', $preset ) ) {
			$attrs['cBThoverColor'] = $preset['bthColor']; }
		if ( array_key_exists( 'btBg', $preset ) ) {
			$attrs['cBtnBG'] = $preset['btBg']; }
		if ( array_key_exists( 'bthBg', $preset ) ) {
			$attrs['cBtnHvrBG'] = $preset['bthBg']; }
		if ( array_key_exists( 'bBord', $preset ) ) {
			$attrs['cBtnNormalB'] = $preset['bBord']; }
		if ( array_key_exists( 'brad', $preset ) ) {
			$attrs['cBtnBRadius'] = $preset['brad']; }
		if ( array_key_exists( 'btPad', $preset ) ) {
			$attrs['cBtnPadding'] = $preset['btPad']; }

		if ( array_key_exists( 'bthBColor', $preset ) && '' !== $preset['bthBColor'] && null !== $preset['bthBColor'] ) {
			$hvr               = ! empty( $preset['bBord'] ) && is_array( $preset['bBord'] ) ? $preset['bBord'] : array();
			$hvr['color']      = $preset['bthBColor'];
			$hvr['openBorder'] = ! empty( $hvr['openBorder'] ) ? $hvr['openBorder'] : 1;
			$attrs['cBtnHvrB'] = $hvr;
		}
		if ( array_key_exists( 'brad', $preset ) ) {
			$attrs['cBtnHvrBRadius'] = $preset['brad'];
		}

		$has_preset = true;
	}

	/* ── Layout & Style ───────────────────────────────────────────────── */
	if ( ! empty( $input['layoutType'] ) && 'listing' !== $input['layoutType'] ) {
		$attrs['layoutType'] = sanitize_key( $input['layoutType'] ); }
	if ( ! empty( $input['style'] ) && 'style-1' !== $input['style'] ) {
		$attrs['styleType'] = sanitize_text_field( $input['style'] ); }
	if ( ! empty( $input['alignment'] ) && 'center' !== $input['alignment'] ) {
		$attrs['Alignment'] = array( 'md' => sanitize_key( $input['alignment'] ) );
	}

	/* ── Content ──────────────────────────────────────────────────────── */
	if ( ! empty( $input['title'] ) && 'Amazing Feature' !== $input['title'] ) {
		$attrs['Title'] = tpgb_mcp_clean_text( $input['title'] ); }
	$default_desc = 'Disrupt inspire and think tank, social entrepreneur but preliminary thinking think tank compelling. Inspiring, invest synergy capacity building, white paper; silo, unprecedented challenge B-corp problem-solvers.';
	if ( isset( $input['description'] ) && $default_desc !== $input['description'] ) {
		$attrs['Description'] = tpgb_mcp_clean_text( $input['description'] );
	}

	/* ── Icon/Image/SVG/Text ──────────────────────────────────────────── */
	$icon_type = sanitize_key( $input['iconType'] ?? 'icon' );
	if ( 'icon' !== $icon_type ) {
		$attrs['iconType'] = $icon_type; }

	if ( 'icon' === $icon_type && ! empty( $input['iconClass'] ) && 'fab fa-angellist' !== $input['iconClass'] ) {
		$attrs['IconName'] = sanitize_text_field( $input['iconClass'] );
	}
	if ( 'text' === $icon_type && ! empty( $input['textIcon'] ) ) {
		$attrs['textIcon'] = sanitize_text_field( $input['textIcon'] );
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
			$attrs['imageName'] = $img; }
		if ( ! empty( $input['imageSize'] ) && 'full' !== $input['imageSize'] ) {
			$attrs['imageSize'] = sanitize_key( $input['imageSize'] ); }
	}
	if ( 'svg' === $icon_type && ! empty( $input['svgUrl'] ) ) {
		$attrs['svgIcon'] = array( 'url' => esc_url_raw( $input['svgUrl'] ) );
	}

	/* ── Pin/Badge ────────────────────────────────────────────────────── */
	if ( ! empty( $input['showPin'] ) ) {
		$attrs['dispPinText'] = true;
		if ( ! empty( $input['pinText'] ) && 'New' !== $input['pinText'] ) {
			$attrs['pinText'] = sanitize_text_field( $input['pinText'] );
		}
	}

	/* ── Box link ─────────────────────────────────────────────────────── */
	if ( ! empty( $input['enableBoxLink'] ) ) {
		$attrs['IBoxLinkTgl'] = true;
		if ( ! empty( $input['boxLinkUrl'] ) ) {
			$attrs['IBoxLink'] = array(
				'url'      => esc_url_raw( $input['boxLinkUrl'] ),
				'target'   => '_blank' === ( $input['boxLinkTarget'] ?? '' ) ? '_blank' : '',
				'nofollow' => ! empty( $input['boxLinkNofollow'] ) ? 'on' : '',
			);
		}
	}

	/* ── CTA button ───────────────────────────────────────────────────── */
	$cta_enabled = ! empty( $input['enableCtaButton'] ) || $has_preset;
	if ( $cta_enabled ) {
		$attrs['carouselBtn'] = true;
		if ( $has_preset ) {
			$attrs['carBtnStyle'] = 'style-8';
		} elseif ( ! empty( $input['ctaButtonStyle'] ) && 'style-7' !== $input['ctaButtonStyle'] ) {
			$attrs['carBtnStyle'] = sanitize_text_field( $input['ctaButtonStyle'] );
		}
		if ( ! empty( $input['ctaButtonIconType'] ) && 'none' !== $input['ctaButtonIconType'] ) {
			$attrs['carBtnIconType'] = sanitize_key( $input['ctaButtonIconType'] );
			if ( ! empty( $input['ctaButtonIcon'] ) ) {
				$attrs['carBtnIconName'] = sanitize_text_field( $input['ctaButtonIcon'] ); }
			if ( ! empty( $input['ctaButtonIconPosition'] ) && 'after' !== $input['ctaButtonIconPosition'] ) {
				$attrs['carBtnIconPosition'] = sanitize_key( $input['ctaButtonIconPosition'] );
			}
		}
	}

	/* ── Title styling ────────────────────────────────────────────────── */
	if ( ! empty( $input['titleTag'] ) && 'div' !== $input['titleTag'] ) {
		$attrs['titleType'] = sanitize_key( $input['titleTag'] ); }
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
	if ( ! empty( $input['titlePadding'] ) ) {
		$attrs['titlePadding'] = tpgb_mcp_ibx_spacing( $input['titlePadding'] ); }

	if ( ! empty( $input['enableTitleSeparator'] ) ) {
		$attrs['displayBorder'] = true;
		if ( ! empty( $input['titleSeparatorWidth'] ) ) {
			$attrs['displayBdrWidth'] = array(
				'md'   => (string) absint( $input['titleSeparatorWidth'] ),
				'unit' => '%',
			); }
		if ( ! empty( $input['titleSeparatorHeight'] ) ) {
			$attrs['displayBdrHeight'] = array(
				'md'   => (string) absint( $input['titleSeparatorHeight'] ),
				'unit' => 'px',
			); }
		if ( ! empty( $input['titleSeparatorColor'] ) ) {
			$attrs['borderColor'] = sanitize_text_field( $input['titleSeparatorColor'] ); }
	}

	/* ── Description styling ──────────────────────────────────────────── */
	if ( ! empty( $input['descTag'] ) && 'div' !== $input['descTag'] ) {
		$attrs['descType'] = sanitize_key( $input['descTag'] ); }
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
		$attrs['descNmlColor'] = sanitize_text_field( $input['descColor'] ); }
	if ( ! empty( $input['descHoverColor'] ) ) {
		$attrs['descHvrColor'] = sanitize_text_field( $input['descHoverColor'] ); }
	if ( ! empty( $input['descMargin'] ) ) {
		$attrs['descMargin'] = tpgb_mcp_ibx_spacing( $input['descMargin'] );  }
	if ( ! empty( $input['descPadding'] ) ) {
		$attrs['descPadding'] = tpgb_mcp_ibx_spacing( $input['descPadding'] ); }

	/* ── Box styling ──────────────────────────────────────────────────── */
	if ( ! empty( $input['boxPadding'] ) ) {
		$attrs['boxPadding'] = tpgb_mcp_ibx_spacing( $input['boxPadding'] ); }
	if ( ! empty( $input['boxBgColor'] ) ) {
		$attrs['normalBG'] = tpgb_mcp_ibx_bg( $input['boxBgColor'] ); }
	if ( ! empty( $input['boxBgHoverColor'] ) ) {
		$attrs['HoverBG'] = tpgb_mcp_ibx_bg( $input['boxBgHoverColor'] ); }
	if ( ! empty( $input['boxOverlayColor'] ) ) {
		$attrs['overlayNmlBG'] = sanitize_text_field( $input['boxOverlayColor'] ); }
	if ( ! empty( $input['boxOverlayHoverColor'] ) ) {
		$attrs['overlayHvrBG'] = sanitize_text_field( $input['boxOverlayHoverColor'] ); }
	if ( ! empty( $input['boxBorder'] ) ) {
		$attrs['bgNmlBorder'] = tpgb_mcp_ibx_border( $input['boxBorder'] ); }
	if ( ! empty( $input['boxBorderHover'] ) ) {
		$attrs['bgHvrBorder'] = tpgb_mcp_ibx_border( $input['boxBorderHover'] ); }
	if ( ! empty( $input['boxBorderRadius'] ) ) {
		$attrs['boxBdrNmlRadius'] = tpgb_mcp_ibx_radius( $input['boxBorderRadius'] ); }
	if ( ! empty( $input['boxBorderRadiusHover'] ) ) {
		$attrs['boxBdrHvrRadius'] = tpgb_mcp_ibx_radius( $input['boxBorderRadiusHover'] ); }
	if ( ! empty( $input['enableBoxShadow'] ) ) {
		$attrs['nmlboxShadow'] = array(
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
		$attrs['hvrboxShadow'] = array(
			'openShadow' => true,
			'inset'      => 0,
			'horizontal' => (string) intval( $input['boxShadowHoverH'] ?? 0 ),
			'vertical'   => (string) intval( $input['boxShadowHoverV'] ?? 4 ),
			'blur'       => (string) absint( $input['boxShadowHoverBlur'] ?? 8 ),
			'spread'     => (string) intval( $input['boxShadowHoverSpread'] ?? 0 ),
			'color'      => sanitize_text_field( $input['boxShadowHoverColor'] ?? 'rgba(0,0,0,0.40)' ),
		);
	}
	if ( ! empty( $input['enableMinHeight'] ) ) {
		$attrs['minHeightTgl'] = true;
		if ( ! empty( $input['minHeight'] ) ) {
			$attrs['minHeight'] = array(
				'md'   => (string) absint( $input['minHeight'] ),
				'unit' => 'px',
			); }
	}
	if ( ! empty( $input['verticalCenter'] ) ) {
		$attrs['verticalCenter'] = true; }

	/* ── Icon styling ─────────────────────────────────────────────────── */
	if ( ! empty( $input['iconDisplayStyle'] ) && 'none' !== $input['iconDisplayStyle'] ) {
		$attrs['iconstyleType'] = sanitize_key( $input['iconDisplayStyle'] ); }
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
		$attrs['iconNormalColor'] = sanitize_text_field( $input['iconColor'] ); }
	if ( ! empty( $input['iconHoverColor'] ) ) {
		$attrs['iconHoverColor'] = sanitize_text_field( $input['iconHoverColor'] ); }
	if ( ! empty( $input['iconBgColor'] ) ) {
		$attrs['bgNormalColor'] = tpgb_mcp_ibx_bg( $input['iconBgColor'] ); }
	if ( ! empty( $input['iconBgHoverColor'] ) ) {
		$attrs['bgHoverColor'] = tpgb_mcp_ibx_bg( $input['iconBgHoverColor'] ); }
	if ( ! empty( $input['iconBorder'] ) ) {
		$attrs['iconBdrNmlType'] = tpgb_mcp_ibx_border( $input['iconBorder'] ); }
	if ( ! empty( $input['iconBorderRadius'] ) ) {
		$attrs['iconBdrNmlRadius'] = tpgb_mcp_ibx_radius( $input['iconBorderRadius'] ); }
	if ( ! empty( $input['iconBorderRadiusHover'] ) ) {
		$attrs['iconBdrHvrRadius'] = tpgb_mcp_ibx_radius( $input['iconBorderRadiusHover'] ); }
	if ( ! empty( $input['iconShineEffect'] ) ) {
		$attrs['iconShine'] = true; }
	if ( ! empty( $input['iconGradient'] ) ) {
		$attrs['iconGrNToggle'] = true;
		if ( ! empty( $input['iconGradientColor'] ) ) {
			$attrs['iconGrNClr'] = sanitize_text_field( $input['iconGradientColor'] ); }
	}
	if ( ! empty( $input['iconGradientHover'] ) ) {
		$attrs['iconGrHToggle'] = true;
		if ( ! empty( $input['iconGradientHoverColor'] ) ) {
			$attrs['iconGrHClr'] = sanitize_text_field( $input['iconGradientHoverColor'] ); }
	}

	/* ── Text icon styling ────────────────────────────────────────────── */
	if ( ! empty( $input['enableTextIconTypo'] ) ) {
		$attrs['textIconTypo'] = array(
			'openTypography' => 1,
			'size'           => array(
				'md'   => ! empty( $input['textIconTypoSize'] ) ? (string) absint( $input['textIconTypoSize'] ) : '',
				'unit' => 'px',
			),
			'height'         => '',
			'spacing'        => '',
			'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
		);
	}
	if ( ! empty( $input['textIconColor'] ) ) {
		$attrs['txtNormalColor'] = sanitize_text_field( $input['textIconColor'] ); }
	if ( ! empty( $input['textIconHoverColor'] ) ) {
		$attrs['txtHoverColor'] = sanitize_text_field( $input['textIconHoverColor'] ); }
	if ( ! empty( $input['textIconBgColor'] ) ) {
		$attrs['txtbgNormalColor'] = tpgb_mcp_ibx_bg( $input['textIconBgColor'] ); }
	if ( ! empty( $input['textIconBgHoverColor'] ) ) {
		$attrs['txtbgHoverColor'] = tpgb_mcp_ibx_bg( $input['textIconBgHoverColor'] ); }
	if ( ! empty( $input['textIconPadding'] ) ) {
		$attrs['textPadding'] = tpgb_mcp_ibx_spacing( $input['textIconPadding'] ); }

	/* ── Image styling ────────────────────────────────────────────────── */
	if ( ! empty( $input['imageWidth'] ) ) {
		$attrs['imageWidth'] = array(
			'md'   => (string) absint( $input['imageWidth'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['imageBorder'] ) ) {
		$attrs['imgNmlBdr'] = tpgb_mcp_ibx_border( $input['imageBorder'] ); }
	if ( ! empty( $input['imageBorderHover'] ) ) {
		$attrs['imgHvrBdr'] = tpgb_mcp_ibx_border( $input['imageBorderHover'] ); }
	if ( ! empty( $input['imageBorderRadius'] ) ) {
		$attrs['imgBdrNmlRadius'] = tpgb_mcp_ibx_radius( $input['imageBorderRadius'] ); }
	if ( ! empty( $input['imageBorderRadiusHover'] ) ) {
		$attrs['imgBdrHvrRadius'] = tpgb_mcp_ibx_radius( $input['imageBorderRadiusHover'] ); }
	if ( ! empty( $input['imageOverlay'] ) ) {
		$attrs['imgOverlay'] = true; }

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
		$attrs['svgstroHoverColor'] = sanitize_text_field( $input['svgStrokeHoverColor'] ); }
	if ( ! empty( $input['svgFillHoverColor'] ) ) {
		$attrs['svgfillHoverColor'] = sanitize_text_field( $input['svgFillHoverColor'] ); }

	/* ── Pin/Badge styling ────────────────────────────────────────────── */
	if ( ! empty( $input['enablePinTypo'] ) ) {
		$attrs['pinTextTypo'] = array(
			'openTypography' => 1,
			'size'           => array(
				'md'   => ! empty( $input['pinTypoSize'] ) ? (string) absint( $input['pinTypoSize'] ) : '',
				'unit' => 'px',
			),
			'height'         => '',
			'spacing'        => '',
			'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
		);
	}
	if ( ! empty( $input['pinTextColor'] ) ) {
		$attrs['pinTextNmlColor'] = sanitize_text_field( $input['pinTextColor'] ); }
	if ( ! empty( $input['pinBgColor'] ) ) {
		$attrs['pinNmlBG'] = tpgb_mcp_ibx_bg( $input['pinBgColor'] ); }
	if ( ! empty( $input['pinTextHoverColor'] ) ) {
		$attrs['pinTextHvrColor'] = sanitize_text_field( $input['pinTextHoverColor'] ); }
	if ( ! empty( $input['pinBgHoverColor'] ) ) {
		$attrs['pinHvrBG'] = tpgb_mcp_ibx_bg( $input['pinBgHoverColor'] ); }
	if ( ! empty( $input['pinPadding'] ) ) {
		$attrs['pinSize'] = tpgb_mcp_ibx_spacing( $input['pinPadding'] ); }
	if ( ! empty( $input['pinBorder'] ) ) {
		$attrs['pinNmlBorder'] = tpgb_mcp_ibx_border( $input['pinBorder'] ); }
	if ( ! empty( $input['pinBorderRadius'] ) ) {
		$attrs['pinTextNmlRadius'] = tpgb_mcp_ibx_radius( $input['pinBorderRadius'] ); }
	if ( ! empty( $input['pinHorizontalAdjust'] ) ) {
		$attrs['pinHrztlAdj'] = array(
			'md'   => (string) intval( $input['pinHorizontalAdjust'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['pinVerticalAdjust'] ) ) {
		$attrs['pinVrtclAdj'] = array(
			'md'   => (string) intval( $input['pinVerticalAdjust'] ),
			'unit' => 'px',
		); }

	/* ── CTA button styling ───────────────────────────────────────────── */
	if ( ! $has_preset ) {
		if ( ! empty( $input['enableCtaBtnTypo'] ) ) {
			$attrs['cBtnTypo'] = array(
				'openTypography' => 1,
				'size'           => array(
					'md'   => ! empty( $input['ctaBtnTypoSize'] ) ? (string) absint( $input['ctaBtnTypoSize'] ) : '',
					'unit' => 'px',
				),
				'height'         => '',
				'spacing'        => '',
				'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
			);
		}
		if ( ! empty( $input['ctaBtnTextColor'] ) ) {
			$attrs['cBtnTextColor'] = sanitize_text_field( $input['ctaBtnTextColor'] ); }
		if ( ! empty( $input['ctaBtnTextHoverColor'] ) ) {
			$attrs['cBThoverColor'] = sanitize_text_field( $input['ctaBtnTextHoverColor'] ); }
		if ( ! empty( $input['ctaBtnTopSpace'] ) ) {
			$attrs['cBtnSpace'] = array(
				'md'   => (string) absint( $input['ctaBtnTopSpace'] ),
				'unit' => 'px',
			); }
		if ( ! empty( $input['ctaBtnBottomSpace'] ) ) {
			$attrs['cBtnbottomSpace'] = array(
				'md'   => (string) absint( $input['ctaBtnBottomSpace'] ),
				'unit' => 'px',
			); }
		if ( isset( $input['ctaBtnIconSpacing'] ) && 5 !== (int) $input['ctaBtnIconSpacing'] ) {
			$attrs['cIconSpacing'] = array(
				'md'   => (string) absint( $input['ctaBtnIconSpacing'] ),
				'unit' => 'px',
			); }
		if ( ! empty( $input['ctaBtnIconSize'] ) ) {
			$attrs['cBtnIconSize'] = array(
				'md'   => (string) absint( $input['ctaBtnIconSize'] ),
				'unit' => 'px',
			); }
		if ( ! empty( $input['ctaBtnPadding'] ) ) {
			$attrs['cBtnPadding'] = tpgb_mcp_ibx_spacing( $input['ctaBtnPadding'] ); }
		if ( ! empty( $input['ctaBtnBorder'] ) ) {
			$attrs['cBtnNormalB'] = tpgb_mcp_ibx_border( $input['ctaBtnBorder'] ); }
		if ( ! empty( $input['ctaBtnBRadius'] ) ) {
			$attrs['cBtnBRadius'] = tpgb_mcp_ibx_radius( $input['ctaBtnBRadius'] ); }
		if ( ! empty( $input['ctaBtnBgColor'] ) ) {
			$attrs['cBtnBG'] = tpgb_mcp_ibx_bg( $input['ctaBtnBgColor'] ); }
		if ( ! empty( $input['ctaBtnHoverBorder'] ) ) {
			$attrs['cBtnHvrB'] = tpgb_mcp_ibx_border( $input['ctaBtnHoverBorder'] ); }
		if ( ! empty( $input['ctaBtnHoverBRadius'] ) ) {
			$attrs['cBtnHvrBRadius'] = tpgb_mcp_ibx_radius( $input['ctaBtnHoverBRadius'] ); }
		if ( ! empty( $input['ctaBtnHoverBgColor'] ) ) {
			$attrs['cBtnHvrBG'] = tpgb_mcp_ibx_bg( $input['ctaBtnHoverBgColor'] ); }
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
		$attrs['globalMargin'] = tpgb_mcp_ibx_spacing( $input['globalMargin'] );  }
	if ( ! empty( $input['globalPadding'] ) ) {
		$attrs['globalPadding'] = tpgb_mcp_ibx_spacing( $input['globalPadding'] ); }
	if ( ! empty( $input['globalBgColor'] ) ) {
		$attrs['globalBg'] = tpgb_mcp_ibx_bg( $input['globalBgColor'] );      }
	if ( ! empty( $input['globalBgHoverColor'] ) ) {
		$attrs['globalBgHover'] = tpgb_mcp_ibx_bg( $input['globalBgHoverColor'] ); }
	if ( ! empty( $input['globalBorder'] ) ) {
		$attrs['globalBorder'] = tpgb_mcp_ibx_border( $input['globalBorder'] );       }
	if ( ! empty( $input['globalBorderHover'] ) ) {
		$attrs['globalBorderHover'] = tpgb_mcp_ibx_border( $input['globalBorderHover'] );  }
	if ( ! empty( $input['globalBRadius'] ) ) {
		$attrs['globalBRadius'] = tpgb_mcp_ibx_radius( $input['globalBRadius'] );      }
	if ( ! empty( $input['globalBRadiusHover'] ) ) {
		$attrs['globalBRadiusHover'] = tpgb_mcp_ibx_radius( $input['globalBRadiusHover'] ); }
	if ( ! empty( $input['globalBShadow'] ) ) {
		$attrs['globalBShadow'] = tpgb_mcp_ibx_bshadow( $input['globalBShadow'] );      }
	if ( ! empty( $input['globalBShadowHover'] ) ) {
		$attrs['globalBShadowHover'] = tpgb_mcp_ibx_bshadow( $input['globalBShadowHover'] ); }

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
	if ( tpgb_mcp_ibx_needs_wrapper( $attrs ) ) {
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
