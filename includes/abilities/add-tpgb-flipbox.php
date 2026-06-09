<?php
/**
 * Ability: Add Nexter Blocks Flipbox (tpgb/tp-flipbox) to a Gutenberg page.
 *
 * @package The_Plus_Addons_For_Block_Editor
 * @since   1.3.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_register_ability(
	'nexter-blocks/add-tpgb-flipbox',
	array(
		'label'               => __( 'Add Nexter Blocks Flipbox', 'the-plus-addons-for-block-editor' ),
		'description'         => __( 'Adds the Nexter Blocks Flipbox block (tpgb/tp-flipbox) — a card that flips horizontally or vertically on hover to reveal back content. Supports icon/image/SVG front display, title with custom tag, description, back-side button with link, carousel back content, comprehensive styling for front/back states (typography, colours, backgrounds, borders, shadows, overlays), icon styling (size, colours, shadows), and full animation/transform/advanced controls. This is a dynamic block.', 'the-plus-addons-for-block-editor' ),
		'category'            => 'nexter-blocks',

		'input_schema'        => array(
			'type'                 => 'object',
			'properties'           => array(

				/* ── Core ─────────────────────────────────────────────────── */
				'post_id'               => array(
					'type'        => 'integer',
					'description' => 'WordPress page/post ID to insert the block into.',
				),
				'position'              => array(
					'type'        => 'integer',
					'default'     => -1,
					'description' => 'Zero-based insert position. Use -1 to append.',
				),
				'parent_block_id'       => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Optional parent container block_id for nesting.',
				),

				/* ── Layout ───────────────────────────────────────────────── */
				'layoutType'            => array(
					'type'        => 'string',
					'enum'        => array( 'listing', 'carousel' ),
					'description' => 'Back-side content mode. "listing" = simple back content; "carousel" = multiple slides.',
					'default'     => 'listing',
				),
				'flipType'              => array(
					'type'        => 'string',
					'enum'        => array( 'horizontal', 'vertical' ),
					'description' => 'Flip animation direction.',
					'default'     => 'horizontal',
				),
				'boxHeight'             => array(
					'type'        => 'integer',
					'description' => 'Fixed height of the flipbox in px.',
					'default'     => 0,
				),

				/* ── Front: Icon/Image/SVG ────────────────────────────────── */
				'iconType'              => array(
					'type'        => 'string',
					'enum'        => array( 'icon', 'image', 'svg', 'none' ),
					'description' => 'Front-side icon type.',
					'default'     => 'icon',
				),
				'iconClass'             => array(
					'type'        => 'string',
					'description' => 'Font Awesome class (when iconType is "icon").',
					'default'     => 'fas fa-box-open',
				),
				'imageUrl'              => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Image URL (when iconType is "image").',
				),
				'imageId'               => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'WordPress media attachment ID.',
				),
				'imageSize'             => array(
					'type'        => 'string',
					'default'     => 'thumbnail',
					'description' => 'WordPress image size slug.',
				),
				'imageWidth'            => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Image width in px.',
				),
				'svgUrl'                => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'SVG URL (when iconType is "svg").',
				),

				/* ── Front: Title & Description ──────────────────────────── */
				'title'                 => array(
					'type'        => 'string',
					'description' => 'Front title text. ALWAYS pass the exact text the user specified.',
					'default'     => 'Special Feature',
				),
				'titleTag'              => array(
					'type'        => 'string',
					'enum'        => array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p' ),
					'description' => 'HTML tag for the title.',
					'default'     => 'div',
				),
				'description'           => array(
					'type'        => 'string',
					'description' => 'Front description/body text.',
					'default'     => '',
				),

				/* ── Back: Alignment ──────────────────────────────────────── */
				'backAlign'             => array(
					'type'        => 'string',
					'enum'        => array( 'left', 'center', 'right' ),
					'description' => 'Back-side content alignment.',
					'default'     => 'center',
				),
				'backPadding'           => array(
					'type'        => 'object',
					'description' => 'Back-side content padding {top,right,bottom,left,unit}.',
				),

				/* ── Back: Button ─────────────────────────────────────────── */
				'enableBackBtn'         => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Show a button on the back side.',
				),
				'backBtnStyle'          => array(
					'type'        => 'string',
					'description' => 'Back button style preset e.g. "style-7".',
					'default'     => 'style-7',
				),
				'backBtnText'           => array(
					'type'        => 'string',
					'default'     => 'Read more',
					'description' => 'Back button text.',
				),
				'backBtnLinkUrl'        => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Back button URL.',
				),
				'backBtnLinkTarget'     => array(
					'type'    => 'string',
					'enum'    => array( '_self', '_blank' ),
					'default' => '_self',
				),
				'backBtnLinkNofollow'   => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'backBtnIconType'       => array(
					'type'    => 'string',
					'enum'    => array( 'none', 'icon' ),
					'default' => 'none',
				),
				'backBtnIcon'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'backBtnIconPosition'   => array(
					'type'    => 'string',
					'enum'    => array( 'before', 'after' ),
					'default' => 'after',
				),

				/* ── Global back button preset ────────────────────────────── */
				'backButtonPreset'      => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Global button preset key for the back-side button (e.g. "btnpreset1"). When provided, raw back-button colour/border/padding/typography values are ignored and the preset is applied instead.',
				),

				/* ── Icon Styling ─────────────────────────────────────────── */
				'iconDisplayStyle'      => array(
					'type'        => 'string',
					'enum'        => array( 'none', 'stacked', 'framed' ),
					'description' => 'Icon container style. "stacked" = solid background, "framed" = bordered.',
					'default'     => 'none',
				),
				'iconSize'              => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Icon font size in px.',
				),
				'iconWidth'             => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Icon container width in px.',
				),
				'iconNormalColor'       => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Icon colour (normal).',
				),
				'iconHoverColor'        => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Icon colour (hover).',
				),
				'iconNormalBg'          => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Icon background colour (normal).',
				),
				'iconHoverBg'           => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Icon background colour (hover).',
				),
				'iconNormalBorderColor' => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Icon border colour (normal, for framed style).',
				),
				'iconHoverBorderColor'  => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Icon border colour (hover).',
				),
				'iconNormalBRadius'     => array(
					'type'        => 'object',
					'description' => 'Icon border radius (normal).',
				),
				'iconHoverBRadius'      => array(
					'type'        => 'object',
					'description' => 'Icon border radius (hover).',
				),

				/* ── SVG-specific styling ─────────────────────────────────── */
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

				/* ── Title Styling ────────────────────────────────────────── */
				'enableTitleTypo'       => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'titleTypoSize'         => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'titleNormalColor'      => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Title colour (normal). Default #313131.',
				),
				'titleHoverColor'       => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Title colour (hover).',
				),
				'titleTopSpace'         => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Space above title in px.',
				),
				'titleBottomSpace'      => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Space below title in px.',
				),

				/* ── Description Styling ──────────────────────────────────── */
				'enableDescTypo'        => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'descTypoSize'          => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'descColor'             => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Description colour.',
				),

				/* ── Back Button Styling ──────────────────────────────────── */
				'enableBackBtnTypo'     => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'backBtnTypoSize'       => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'backBtnTextColor'      => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Back button text colour (normal).',
				),
				'backBtnHoverTextColor' => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Back button text colour (hover).',
				),
				'backBtnTopSpace'       => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Space above back button.',
				),
				'backBtnBottomSpace'    => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Space below back button.',
				),
				'backBtnIconSpacing'    => array(
					'type'        => 'integer',
					'default'     => 5,
					'description' => 'Gap between back button icon and text.',
				),
				'backBtnIconSize'       => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Back button icon size.',
				),
				'backBtnPadding'        => array(
					'type'        => 'object',
					'description' => 'Back button padding.',
				),
				'backBtnBorder'         => array(
					'type'        => 'object',
					'description' => 'Back button border (normal).',
				),
				'backBtnBRadius'        => array(
					'type'        => 'object',
					'description' => 'Back button border radius (normal).',
				),
				'backBtnBgColor'        => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Back button background colour (normal).',
				),
				'backBtnHoverBorder'    => array(
					'type'        => 'object',
					'description' => 'Back button border (hover).',
				),
				'backBtnHoverBRadius'   => array(
					'type'        => 'object',
					'description' => 'Back button border radius (hover).',
				),
				'backBtnHoverBgColor'   => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Back button background colour (hover).',
				),

				/* ── Box Styling (Front/Back card) ────────────────────────── */
				'boxBorder'             => array(
					'type'        => 'object',
					'description' => 'Card border.',
				),
				'boxBorderRadius'       => array(
					'type'        => 'object',
					'description' => 'Card border radius.',
				),
				'frontBgColor'          => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Front side background colour.',
				),
				'backBgColor'           => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Back side background colour.',
				),
				'frontOverlayColor'     => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Front overlay colour.',
				),
				'backOverlayColor'      => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Back overlay colour.',
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

				/* ── Transforms ──────────────────────────────────────────── */
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
					'description' => 'Raw attribute overrides merged directly into the block.',
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

		'execute_callback'    => 'tpgb_mcp_add_flipbox_ability',
		'permission_callback' => 'tpgb_mcp_add_flipbox_permission',
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
 * Permission callback for the add-flipbox ability.
 *
 * @param array|null $input Ability input arguments.
 * @return bool True when the current user may insert the block.
 */
function tpgb_mcp_add_flipbox_permission( ?array $input = null ): bool {
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
function tpgb_mcp_fbox_spacing( array $v ): array {
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
function tpgb_mcp_fbox_border( array $b ): array {
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
function tpgb_mcp_fbox_radius( array $r ): array {
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
function tpgb_mcp_fbox_bshadow( array $s ): array {
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
function tpgb_mcp_fbox_bg( string $color ): array {
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
function tpgb_mcp_fbox_needs_wrapper( array $attrs ): bool {
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
 * Execute callback: insert a tpgb/tp-flipbox block into a post.
 *
 * @param array $input Ability input arguments.
 * @return array|WP_Error Ability result or error on failure.
 */
function tpgb_mcp_add_flipbox_ability( array $input ) {

	if ( ! defined( 'TPGB_VERSION' ) && ! defined( 'TPGBP_VERSION' ) ) {
		return new WP_Error( 'nexter_blocks_missing', __( 'Nexter Blocks must be active.', 'the-plus-addons-for-block-editor' ) );
	}

	$block_name = 'tpgb/tp-flipbox';
	if ( ! tpgb_mcp_has_registered_block( $block_name ) ) {
		return new WP_Error( 'block_missing', __( 'tpgb/tp-flipbox is not registered.', 'the-plus-addons-for-block-editor' ) );
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

	/* ── Preset ───────────────────────────────────────────────────────── */
	$has_preset = false;
	$preset_key = sanitize_text_field( $input['backButtonPreset'] ?? '' );
	if ( '' !== $preset_key ) {
		$validated = tpgb_mcp_validate_button_preset( $preset_key );
		if ( is_wp_error( $validated ) ) {
			return $validated;
		}
		tpgb_mcp_apply_button_preset( $attrs, $validated, 'back' );
		$has_preset = true;
	}

	/* ── Layout ───────────────────────────────────────────────────────── */
	if ( ! empty( $input['layoutType'] ) && 'listing' !== $input['layoutType'] ) {
		$attrs['layoutType'] = sanitize_key( $input['layoutType'] ); }
	if ( ! empty( $input['flipType'] ) && 'horizontal' !== $input['flipType'] ) {
		$attrs['flipType'] = sanitize_key( $input['flipType'] ); }
	if ( ! empty( $input['boxHeight'] ) ) {
		$attrs['boxHeight'] = array(
			'md'   => (string) absint( $input['boxHeight'] ),
			'unit' => 'px',
		); }

	/* ── Front: Icon/Image/SVG ────────────────────────────────────────── */
	$icon_type = sanitize_key( $input['iconType'] ?? 'icon' );
	if ( 'icon' !== $icon_type ) {
		$attrs['iconType'] = $icon_type; }

	if ( 'icon' === $icon_type && ! empty( $input['iconClass'] ) && 'fas fa-box-open' !== $input['iconClass'] ) {
		$attrs['iconStore'] = sanitize_text_field( $input['iconClass'] );
	}

	if ( 'image' === $icon_type ) {
		$img_obj = array( 'url' => '' );
		if ( ! empty( $input['imageId'] ) ) {
			$img_obj['id'] = absint( $input['imageId'] );
			$src           = wp_get_attachment_image_url( $img_obj['id'], 'full' );
			if ( $src ) {
				$img_obj['url'] = $src; }
		} elseif ( ! empty( $input['imageUrl'] ) ) {
			$img_obj['url'] = esc_url_raw( $input['imageUrl'] );
		}
		if ( ! empty( $img_obj['url'] ) ) {
			$attrs['imagestore'] = $img_obj; }
		if ( ! empty( $input['imageSize'] ) && 'thumbnail' !== $input['imageSize'] ) {
			$attrs['imageSize'] = sanitize_key( $input['imageSize'] ); }
		if ( ! empty( $input['imageWidth'] ) ) {
			$attrs['imgWidth'] = array(
				'md'   => (string) absint( $input['imageWidth'] ),
				'unit' => 'px',
			); }
	}

	if ( 'svg' === $icon_type && ! empty( $input['svgUrl'] ) ) {
		$attrs['svgIcon'] = array( 'url' => esc_url_raw( $input['svgUrl'] ) );
	}

	/* ── Front: Title & Description ───────────────────────────────────── */
	if ( ! empty( $input['title'] ) && 'Special Feature' !== $input['title'] ) {
		$attrs['title'] = tpgb_mcp_clean_text( $input['title'] );
	}
	if ( ! empty( $input['titleTag'] ) && 'div' !== $input['titleTag'] ) {
		$attrs['titleTag'] = sanitize_key( $input['titleTag'] );
	}
	$default_desc = 'Lookout flogging bilge rat main sheet bilge water nipper fluke to go on account heave down clap of thunder. Reef sails six pounders skysail code of conduct sloop cog Yellow Jack gunwalls grog blossom starboard.';
	if ( isset( $input['description'] ) && $default_desc !== $input['description'] ) {
		$attrs['description'] = tpgb_mcp_clean_text( $input['description'] );
	}

	/* ── Back: Alignment & Padding ────────────────────────────────────── */
	if ( ! empty( $input['backAlign'] ) && 'center' !== $input['backAlign'] ) {
		$attrs['backAlign'] = sanitize_key( $input['backAlign'] ); }
	if ( ! empty( $input['backPadding'] ) ) {
		$attrs['backPad'] = tpgb_mcp_fbox_spacing( $input['backPadding'] ); }

	/* ── Back: Button ─────────────────────────────────────────────────── */
	if ( ! empty( $input['enableBackBtn'] ) ) {
		$attrs['backBtn'] = true;
		if ( ! empty( $input['backBtnStyle'] ) && 'style-7' !== $input['backBtnStyle'] ) {
			$attrs['btnStyle'] = sanitize_text_field( $input['backBtnStyle'] );
		}
		if ( ! empty( $input['backBtnText'] ) && 'Read more' !== $input['backBtnText'] ) {
			$attrs['btnText'] = sanitize_text_field( $input['backBtnText'] );
		}
		if ( ! empty( $input['backBtnLinkUrl'] ) ) {
			$attrs['btnUrl'] = array(
				'url'      => esc_url_raw( $input['backBtnLinkUrl'] ),
				'target'   => '_blank' === ( $input['backBtnLinkTarget'] ?? '' ) ? '_blank' : '',
				'nofollow' => ! empty( $input['backBtnLinkNofollow'] ) ? 'on' : '',
			);
		}
		if ( ! empty( $input['backBtnIconType'] ) && 'none' !== $input['backBtnIconType'] ) {
			$attrs['btnIconType'] = sanitize_key( $input['backBtnIconType'] );
			if ( ! empty( $input['backBtnIcon'] ) ) {
				$attrs['btnIconName'] = sanitize_text_field( $input['backBtnIcon'] ); }
			if ( ! empty( $input['backBtnIconPosition'] ) && 'after' !== $input['backBtnIconPosition'] ) {
				$attrs['btnIconPosition'] = sanitize_key( $input['backBtnIconPosition'] );
			}
		}
	}

	/* ── Icon Styling ─────────────────────────────────────────────────── */
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
	if ( ! empty( $input['iconNormalColor'] ) ) {
		$attrs['icnNmlColor'] = sanitize_text_field( $input['iconNormalColor'] ); }
	if ( ! empty( $input['iconHoverColor'] ) ) {
		$attrs['icnHvrColor'] = sanitize_text_field( $input['iconHoverColor'] ); }
	if ( ! empty( $input['iconNormalBg'] ) ) {
		$attrs['icnNormalBG'] = tpgb_mcp_fbox_bg( $input['iconNormalBg'] ); }
	if ( ! empty( $input['iconHoverBg'] ) ) {
		$attrs['icnHoverBG'] = tpgb_mcp_fbox_bg( $input['iconHoverBg'] ); }
	if ( ! empty( $input['iconNormalBorderColor'] ) ) {
		$attrs['nmlBColor'] = sanitize_text_field( $input['iconNormalBorderColor'] ); }
	if ( ! empty( $input['iconHoverBorderColor'] ) ) {
		$attrs['hvrBColor'] = sanitize_text_field( $input['iconHoverBorderColor'] ); }
	if ( ! empty( $input['iconNormalBRadius'] ) ) {
		$attrs['nmlIcnBRadius'] = tpgb_mcp_fbox_radius( $input['iconNormalBRadius'] ); }
	if ( ! empty( $input['iconHoverBRadius'] ) ) {
		$attrs['hvrIcnBRadius'] = tpgb_mcp_fbox_radius( $input['iconHoverBRadius'] ); }

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

	/* ── Title Styling ────────────────────────────────────────────────── */
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
	if ( ! empty( $input['titleNormalColor'] ) && '#313131' !== $input['titleNormalColor'] ) {
		$attrs['titleNmlColor'] = sanitize_text_field( $input['titleNormalColor'] );
	}
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

	/* ── Description Styling ──────────────────────────────────────────── */
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

	/* ── Back Button Styling ──────────────────────────────────────────── */
	if ( ! $has_preset && ! empty( $input['enableBackBtnTypo'] ) ) {
		$attrs['backBtnTypo'] = array(
			'openTypography' => 1,
			'size'           => array(
				'md'   => ! empty( $input['backBtnTypoSize'] ) ? (string) absint( $input['backBtnTypoSize'] ) : '',
				'unit' => 'px',
			),
			'height'         => '',
			'spacing'        => '',
			'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
		);
	}
	if ( ! $has_preset && ! empty( $input['backBtnTextColor'] ) ) {
		$attrs['backBtnTextColor'] = sanitize_text_field( $input['backBtnTextColor'] ); }
	if ( ! $has_preset && ! empty( $input['backBtnHoverTextColor'] ) ) {
		$attrs['backBThoverColor'] = sanitize_text_field( $input['backBtnHoverTextColor'] ); }
	if ( ! $has_preset && ! empty( $input['backBtnTopSpace'] ) ) {
		$attrs['backBtnSpace'] = array(
			'md'   => (string) absint( $input['backBtnTopSpace'] ),
			'unit' => 'px',
		); }
	if ( ! $has_preset && ! empty( $input['backBtnBottomSpace'] ) ) {
		$attrs['backBtnbottomSpace'] = array(
			'md'   => (string) absint( $input['backBtnBottomSpace'] ),
			'unit' => 'px',
		); }
	if ( ! $has_preset && isset( $input['backBtnIconSpacing'] ) && 5 !== (int) $input['backBtnIconSpacing'] ) {
		$attrs['btnIconSpacing'] = array(
			'md'   => (string) absint( $input['backBtnIconSpacing'] ),
			'unit' => 'px',
		); }
	if ( ! $has_preset && ! empty( $input['backBtnIconSize'] ) ) {
		$attrs['btnIconSize'] = array(
			'md'   => (string) absint( $input['backBtnIconSize'] ),
			'unit' => 'px',
		); }
	if ( ! $has_preset && ! empty( $input['backBtnPadding'] ) ) {
		$attrs['backBtnPadding'] = tpgb_mcp_fbox_spacing( $input['backBtnPadding'] ); }
	if ( ! $has_preset && ! empty( $input['backBtnBorder'] ) ) {
		$attrs['backBtnNormalB'] = tpgb_mcp_fbox_border( $input['backBtnBorder'] ); }
	if ( ! $has_preset && ! empty( $input['backBtnBRadius'] ) ) {
		$attrs['backBtnBRadius'] = tpgb_mcp_fbox_radius( $input['backBtnBRadius'] ); }
	if ( ! $has_preset && ! empty( $input['backBtnBgColor'] ) ) {
		$attrs['backBtnBG'] = tpgb_mcp_fbox_bg( $input['backBtnBgColor'] ); }
	if ( ! $has_preset && ! empty( $input['backBtnHoverBorder'] ) ) {
		$attrs['backBtnHvrB'] = tpgb_mcp_fbox_border( $input['backBtnHoverBorder'] ); }
	if ( ! $has_preset && ! empty( $input['backBtnHoverBRadius'] ) ) {
		$attrs['backBtnHvrBRadius'] = tpgb_mcp_fbox_radius( $input['backBtnHoverBRadius'] ); }
	if ( ! $has_preset && ! empty( $input['backBtnHoverBgColor'] ) ) {
		$attrs['backBtnHvrBG'] = tpgb_mcp_fbox_bg( $input['backBtnHoverBgColor'] ); }

	/* ── Box Styling ──────────────────────────────────────────────────── */
	if ( ! empty( $input['boxBorder'] ) ) {
		$attrs['bgBorder'] = tpgb_mcp_fbox_border( $input['boxBorder'] ); }
	if ( ! empty( $input['boxBorderRadius'] ) ) {
		$attrs['bgBRadius'] = tpgb_mcp_fbox_radius( $input['boxBorderRadius'] ); }
	if ( ! empty( $input['frontBgColor'] ) ) {
		$attrs['normalBG'] = tpgb_mcp_fbox_bg( $input['frontBgColor'] ); }
	if ( ! empty( $input['backBgColor'] ) ) {
		$attrs['hoverBG'] = tpgb_mcp_fbox_bg( $input['backBgColor'] ); }
	if ( ! empty( $input['frontOverlayColor'] ) ) {
		$attrs['overlayNmlBG'] = sanitize_text_field( $input['frontOverlayColor'] ); }
	if ( ! empty( $input['backOverlayColor'] ) ) {
		$attrs['overlayHvrBG'] = sanitize_text_field( $input['backOverlayColor'] ); }

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
		$attrs['globalMargin'] = tpgb_mcp_fbox_spacing( $input['globalMargin'] );  }
	if ( ! empty( $input['globalPadding'] ) ) {
		$attrs['globalPadding'] = tpgb_mcp_fbox_spacing( $input['globalPadding'] ); }
	if ( ! empty( $input['globalBgColor'] ) ) {
		$attrs['globalBg'] = tpgb_mcp_fbox_bg( $input['globalBgColor'] );      }
	if ( ! empty( $input['globalBgHoverColor'] ) ) {
		$attrs['globalBgHover'] = tpgb_mcp_fbox_bg( $input['globalBgHoverColor'] ); }
	if ( ! empty( $input['globalBorder'] ) ) {
		$attrs['globalBorder'] = tpgb_mcp_fbox_border( $input['globalBorder'] );       }
	if ( ! empty( $input['globalBorderHover'] ) ) {
		$attrs['globalBorderHover'] = tpgb_mcp_fbox_border( $input['globalBorderHover'] );  }
	if ( ! empty( $input['globalBRadius'] ) ) {
		$attrs['globalBRadius'] = tpgb_mcp_fbox_radius( $input['globalBRadius'] );      }
	if ( ! empty( $input['globalBRadiusHover'] ) ) {
		$attrs['globalBRadiusHover'] = tpgb_mcp_fbox_radius( $input['globalBRadiusHover'] ); }
	if ( ! empty( $input['globalBShadow'] ) ) {
		$attrs['globalBShadow'] = tpgb_mcp_fbox_bshadow( $input['globalBShadow'] );      }
	if ( ! empty( $input['globalBShadowHover'] ) ) {
		$attrs['globalBShadowHover'] = tpgb_mcp_fbox_bshadow( $input['globalBShadowHover'] ); }

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
	if ( tpgb_mcp_fbox_needs_wrapper( $attrs ) ) {
		$attrs['tpgbDisrule'] = true; }

	/* ── Raw settings override ────────────────────────────────────────── */
	tpgb_mcp_apply_typo_decoration( $attrs, $input );
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
