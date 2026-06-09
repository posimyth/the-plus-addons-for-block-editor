<?php
/**
 * Ability: Add Nexter Blocks Heading Title (tpgb/tp-heading-title) to a Gutenberg page.
 *
 * @package The_Plus_Addons_For_Block_Editor
 * @since   1.3.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_register_ability(
	'nexter-blocks/add-tpgb-heading-title',
	array(
		'label'               => __( 'Add Nexter Blocks Heading Title', 'the-plus-addons-for-block-editor' ),
		'description'         => __(
			'Adds the Nexter Blocks Heading Title block (tpgb/tp-heading-title) — a COMPOSITE heading that combines a main title, optional sub-title, and extra title (pre-title) with separators, animations (letter/word split), and rich styling. Different from tpgb/tp-heading which is a simple single heading. Use this for hero section titles, section headers with sub-text, decorative headings with icon separators, or animated text reveals.

Supports 15+ style variants, character/word limits with ellipsis, split-text animations (words/chars/lines), separator customisation (line/icon/image), per-element typography & colours (title/subTitle/extraTitle), gradient text, text stroke, backgrounds, borders, shadows, and full animation/transform/advanced controls. This is a dynamic block.',
			'the-plus-addons-for-block-editor'
		),
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

				/* ── Content ──────────────────────────────────────────────── */
				'title'                 => array(
					'type'        => 'string',
					'description' => 'Main heading text.',
					'default'     => 'Main Heading',
				),
				'subTitle'              => array(
					'type'        => 'string',
					'description' => 'Secondary/sub-heading text shown below or above main title.',
					'default'     => 'It\'s Sub Heading',
				),
				'extraTitle'            => array(
					'type'        => 'string',
					'description' => 'Decorative extra/pre-title text.',
					'default'     => 'I am Extra',
				),
				'extraTitlePosition'    => array(
					'type'        => 'string',
					'enum'        => array( 'beforeTitle', 'afterTitle', 'insideTitle' ),
					'description' => 'Position of the extra title relative to main title.',
					'default'     => 'afterTitle',
				),
				'subTitlePosition'      => array(
					'type'        => 'string',
					'enum'        => array( 'onTopTitle', 'onBottonTitle' ),
					'description' => 'Sub-title position relative to main title.',
					'default'     => 'onBottonTitle',
				),

				/* ── Style & Layout ───────────────────────────────────────── */
				'style'                 => array(
					'type'        => 'string',
					'description' => 'Visual style preset e.g. "style-1" through "style-15". Each has different layout/separator treatment.',
					'default'     => 'style-1',
				),
				'headingType'           => array(
					'type'        => 'string',
					'enum'        => array( 'default', 'split', 'animated' ),
					'description' => 'Heading presentation type.',
					'default'     => 'default',
				),
				'splitType'             => array(
					'type'        => 'string',
					'enum'        => array( 'chars', 'words', 'lines' ),
					'description' => 'Text split mode for animations (when headingType is "split").',
					'default'     => 'words',
				),
				'alignment'             => array(
					'type'    => 'string',
					'enum'    => array( 'left', 'center', 'right' ),
					'default' => 'center',
				),

				/* ── Link ─────────────────────────────────────────────────── */
				'linkUrl'               => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'URL to wrap the heading in a link.',
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
				'anchor'                => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'HTML anchor id for in-page links.',
				),

				/* ── Character limits ─────────────────────────────────────── */
				'enableLimits'          => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Enable character/word limits with ellipsis.',
				),
				'enableTitleLimit'      => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'titleLimitOn'          => array(
					'type'    => 'string',
					'enum'    => array( 'char', 'word' ),
					'default' => 'char',
				),
				'titleCount'            => array(
					'type'    => 'string',
					'default' => '3',
				),
				'titleDots'             => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Append "..." when truncated.',
				),
				'enableSubTitleLimit'   => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'subTitleLimitOn'       => array(
					'type'    => 'string',
					'enum'    => array( 'char', 'word' ),
					'default' => 'char',
				),
				'subTitleCount'         => array(
					'type'    => 'string',
					'default' => '3',
				),
				'subTitleDots'          => array(
					'type'    => 'boolean',
					'default' => false,
				),

				/* ── Title typography & colour ────────────────────────────── */
				'titleTag'              => array(
					'type'        => 'string',
					'enum'        => array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p' ),
					'description' => 'HTML tag for main title.',
					'default'     => 'h3',
				),
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
				'titleGradient'         => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Enable gradient text effect on title.',
				),
				'titleStrokeColor'      => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Text stroke colour for title.',
				),

				/* ── Title spacing & background ───────────────────────────── */
				'titleMargin'           => array(
					'type'        => 'object',
					'description' => 'Title margin.',
				),
				'titlePadding'          => array(
					'type'        => 'object',
					'description' => 'Title padding.',
				),
				'titleBorder'           => array(
					'type'        => 'object',
					'description' => 'Title border.',
				),
				'titleBorderRadius'     => array(
					'type'        => 'object',
					'description' => 'Title border radius.',
				),
				'titleBgColor'          => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Title background colour.',
				),
				'enableTitleShadow'     => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'titleShadowH'          => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'titleShadowV'          => array(
					'type'    => 'integer',
					'default' => 4,
				),
				'titleShadowBlur'       => array(
					'type'    => 'integer',
					'default' => 8,
				),
				'titleShadowSpread'     => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'titleShadowColor'      => array(
					'type'    => 'string',
					'default' => 'rgba(0,0,0,0.40)',
				),

				/* ── Sub-title typography & colour ────────────────────────── */
				'subTitleTag'           => array(
					'type'    => 'string',
					'enum'    => array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p' ),
					'default' => 'h3',
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
				'subTitleMargin'        => array(
					'type'        => 'object',
					'description' => 'Sub-title margin.',
				),
				'subTitleGradient'      => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'subTitleStrokeColor'   => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Extra title typography ───────────────────────────────── */
				'enableExtraTitleTypo'  => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'extraTitleTypoSize'    => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'extraTitleColor'       => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Separator ────────────────────────────────────────────── */
				'separatorImageUrl'     => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Image URL for decorative separator (style-dependent).',
				),
				'separatorColor'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'separatorDotColor'     => array(
					'type'    => 'string',
					'default' => '',
				),
				'separatorWidth'        => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Separator width in %.',
				),
				'separatorHeight'       => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Separator height in px.',
				),
				'separatorTopHeight'    => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'separatorBottomHeight' => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'separatorTopSpacing'   => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Animation options (reveal effects) ────────────────────── */
				'animEffect'            => array(
					'type'        => 'string',
					'description' => 'Text reveal effect name e.g. "default", "slideUp", "fadeIn", etc.',
					'default'     => 'default',
				),
				'animPositionX'         => array(
					'type'    => 'string',
					'default' => '',
				),
				'animPositionY'         => array(
					'type'    => 'string',
					'default' => '',
				),
				'animScaleX'            => array(
					'type'    => 'string',
					'default' => '',
				),
				'animScaleY'            => array(
					'type'    => 'string',
					'default' => '',
				),
				'animScaleZ'            => array(
					'type'    => 'string',
					'default' => '',
				),
				'animRotateX'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'animRotateY'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'animRotateZ'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'animOpacity'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'animSpeed'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'animDelayExtra'        => array(
					'type'    => 'string',
					'default' => '',
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

		'execute_callback'    => 'tpgb_mcp_add_heading_title_ability',
		'permission_callback' => 'tpgb_mcp_add_heading_title_permission',
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
 * Permission callback for the add-heading-title ability.
 *
 * @param array|null $input Ability input arguments.
 * @return bool True when the current user may insert the block.
 */
function tpgb_mcp_add_heading_title_permission( ?array $input = null ): bool {
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
function tpgb_mcp_htitle_spacing( array $v ): array {
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
function tpgb_mcp_htitle_border( array $b ): array {
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
function tpgb_mcp_htitle_radius( array $r ): array {
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
function tpgb_mcp_htitle_bshadow( array $s ): array {
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
function tpgb_mcp_htitle_bg( string $color ): array {
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
function tpgb_mcp_htitle_needs_wrapper( array $attrs ): bool {
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
 * Execute callback: insert a tpgb/tp-heading-title block into a post.
 *
 * @param array $input Ability input arguments.
 * @return array|WP_Error Ability result or error on failure.
 */
function tpgb_mcp_add_heading_title_ability( array $input ) {

	if ( ! defined( 'TPGB_VERSION' ) && ! defined( 'TPGBP_VERSION' ) ) {
		return new WP_Error( 'nexter_blocks_missing', __( 'Nexter Blocks must be active.', 'the-plus-addons-for-block-editor' ) );
	}

	$block_name = 'tpgb/tp-heading-title';
	if ( ! tpgb_mcp_has_registered_block( $block_name ) ) {
		return new WP_Error( 'block_missing', __( 'tpgb/tp-heading-title is not registered.', 'the-plus-addons-for-block-editor' ) );
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

	/* ── Content ──────────────────────────────────────────────────────── */
	if ( ! empty( $input['title'] ) && 'Main Heading' !== $input['title'] ) {
		$attrs['Title'] = tpgb_mcp_clean_text( $input['title'] ); }
	if ( ! empty( $input['subTitle'] ) && 'It\'s Sub Heading' !== $input['subTitle'] ) {
		$attrs['subTitle'] = tpgb_mcp_clean_text( $input['subTitle'] ); }
	if ( ! empty( $input['extraTitle'] ) && 'I am Extra' !== $input['extraTitle'] ) {
		$attrs['extraTitle'] = tpgb_mcp_clean_text( $input['extraTitle'] ); }

	if ( ! empty( $input['extraTitlePosition'] ) && 'afterTitle' !== $input['extraTitlePosition'] ) {
		$attrs['ETPosition'] = sanitize_text_field( $input['extraTitlePosition'] );
	}
	if ( ! empty( $input['subTitlePosition'] ) && 'onBottonTitle' !== $input['subTitlePosition'] ) {
		$attrs['subTitlePosition'] = sanitize_text_field( $input['subTitlePosition'] );
	}

	/* ── Style & Layout ───────────────────────────────────────────────── */
	if ( ! empty( $input['style'] ) && 'style-1' !== $input['style'] ) {
		$attrs['style'] = sanitize_text_field( $input['style'] ); }
	if ( ! empty( $input['headingType'] ) && 'default' !== $input['headingType'] ) {
		$attrs['headingType'] = sanitize_key( $input['headingType'] ); }
	if ( ! empty( $input['splitType'] ) && 'words' !== $input['splitType'] ) {
		$attrs['splitType'] = sanitize_key( $input['splitType'] ); }
	if ( ! empty( $input['alignment'] ) && 'center' !== $input['alignment'] ) {
		$attrs['Alignment'] = array(
			'md' => sanitize_key( $input['alignment'] ),
			'sm' => '',
			'xs' => '',
		);
	}

	/* ── Link ─────────────────────────────────────────────────────────── */
	if ( ! empty( $input['linkUrl'] ) ) {
		$attrs['advHeadingLink'] = array(
			'url'      => esc_url_raw( $input['linkUrl'] ),
			'target'   => '_blank' === ( $input['linkTarget'] ?? '' ) ? '_blank' : '',
			'nofollow' => ! empty( $input['linkNofollow'] ) ? 'on' : '',
		);
	}
	if ( ! empty( $input['anchor'] ) ) {
		$attrs['anchor'] = sanitize_title( $input['anchor'] ); }

	/* ── Limits ───────────────────────────────────────────────────────── */
	if ( ! empty( $input['enableLimits'] ) ) {
		$attrs['limitTgl'] = true; }
	if ( ! empty( $input['enableTitleLimit'] ) ) {
		$attrs['titleLimit'] = true;
		if ( ! empty( $input['titleLimitOn'] ) && 'char' !== $input['titleLimitOn'] ) {
			$attrs['titleLimitOn'] = sanitize_key( $input['titleLimitOn'] ); }
		if ( ! empty( $input['titleCount'] ) && '3' !== $input['titleCount'] ) {
			$attrs['titleCount'] = sanitize_text_field( $input['titleCount'] ); }
		if ( ! empty( $input['titleDots'] ) ) {
			$attrs['titleDots'] = true; }
	}
	if ( ! empty( $input['enableSubTitleLimit'] ) ) {
		$attrs['subTitleLimit'] = true;
		if ( ! empty( $input['subTitleLimitOn'] ) && 'char' !== $input['subTitleLimitOn'] ) {
			$attrs['subTitleLimitOn'] = sanitize_key( $input['subTitleLimitOn'] ); }
		if ( ! empty( $input['subTitleCount'] ) && '3' !== $input['subTitleCount'] ) {
			$attrs['subTitleCount'] = sanitize_text_field( $input['subTitleCount'] ); }
		if ( ! empty( $input['subTitleDots'] ) ) {
			$attrs['subTitleDots'] = true; }
	}

	/* ── Title typography & colour ────────────────────────────────────── */
	if ( ! empty( $input['titleTag'] ) && 'h3' !== $input['titleTag'] ) {
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
		$attrs['titleColor'] = sanitize_text_field( $input['titleColor'] ); }
	if ( ! empty( $input['titleGradient'] ) ) {
		$attrs['titleGrad'] = true; }
	if ( ! empty( $input['titleStrokeColor'] ) ) {
		$attrs['titleStroke'] = sanitize_text_field( $input['titleStrokeColor'] ); }

	/* ── Title spacing & background ───────────────────────────────────── */
	if ( ! empty( $input['titleMargin'] ) ) {
		$attrs['titleMargin'] = tpgb_mcp_htitle_spacing( $input['titleMargin'] );  }
	if ( ! empty( $input['titlePadding'] ) ) {
		$attrs['titlePadd'] = tpgb_mcp_htitle_spacing( $input['titlePadding'] ); }
	if ( ! empty( $input['titleBorder'] ) ) {
		$attrs['titleB'] = tpgb_mcp_htitle_border( $input['titleBorder'] ); }
	if ( ! empty( $input['titleBorderRadius'] ) ) {
		$attrs['titleBRadius'] = tpgb_mcp_htitle_radius( $input['titleBorderRadius'] ); }
	if ( ! empty( $input['titleBgColor'] ) ) {
		$attrs['titleBg'] = tpgb_mcp_htitle_bg( $input['titleBgColor'] ); }
	if ( ! empty( $input['enableTitleShadow'] ) ) {
		$attrs['titleShadow'] = array(
			'openShadow' => true,
			'horizontal' => (string) intval( $input['titleShadowH'] ?? 0 ),
			'vertical'   => (string) intval( $input['titleShadowV'] ?? 4 ),
			'blur'       => (string) absint( $input['titleShadowBlur'] ?? 8 ),
			'spread'     => (string) intval( $input['titleShadowSpread'] ?? 0 ),
			'color'      => sanitize_text_field( $input['titleShadowColor'] ?? 'rgba(0,0,0,0.40)' ),
		);
	}

	/* ── Sub-title typography & colour ────────────────────────────────── */
	if ( ! empty( $input['subTitleTag'] ) && 'h3' !== $input['subTitleTag'] ) {
		$attrs['subTitleType'] = sanitize_key( $input['subTitleTag'] ); }
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
	if ( ! empty( $input['subTitleMargin'] ) ) {
		$attrs['subTitleMargin'] = tpgb_mcp_htitle_spacing( $input['subTitleMargin'] ); }
	if ( ! empty( $input['subTitleGradient'] ) ) {
		$attrs['subTitleGrad'] = true; }
	if ( ! empty( $input['subTitleStrokeColor'] ) ) {
		$attrs['subTitleStroke'] = sanitize_text_field( $input['subTitleStrokeColor'] ); }

	/* ── Extra title ──────────────────────────────────────────────────── */
	if ( ! empty( $input['enableExtraTitleTypo'] ) ) {
		$attrs['extraTitleTypo'] = array(
			'openTypography' => 1,
			'size'           => array(
				'md'   => ! empty( $input['extraTitleTypoSize'] ) ? (string) absint( $input['extraTitleTypoSize'] ) : '',
				'unit' => 'px',
			),
			'height'         => '',
			'spacing'        => '',
			'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
		);
	}
	if ( ! empty( $input['extraTitleColor'] ) ) {
		$attrs['extraTitleColor'] = sanitize_text_field( $input['extraTitleColor'] ); }

	/* ── Separator ────────────────────────────────────────────────────── */
	if ( ! empty( $input['separatorImageUrl'] ) ) {
		$attrs['imgName'] = array(
			'url' => esc_url_raw( $input['separatorImageUrl'] ),
			'Id'  => '',
		); }
	if ( ! empty( $input['separatorColor'] ) ) {
		$attrs['sepColor'] = sanitize_text_field( $input['separatorColor'] ); }
	if ( ! empty( $input['separatorDotColor'] ) ) {
		$attrs['sepDotColor'] = sanitize_text_field( $input['separatorDotColor'] ); }
	if ( ! empty( $input['separatorWidth'] ) ) {
		$attrs['sepWidth'] = array(
			'md'   => (string) absint( $input['separatorWidth'] ),
			'unit' => '%',
		); }
	if ( ! empty( $input['separatorHeight'] ) ) {
		$attrs['sepHeight'] = array(
			'md'   => (string) absint( $input['separatorHeight'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['separatorTopHeight'] ) ) {
		$attrs['topSepHeight'] = array(
			'md'   => (string) absint( $input['separatorTopHeight'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['separatorBottomHeight'] ) ) {
		$attrs['bottomSepHeight'] = array(
			'md'   => (string) absint( $input['separatorBottomHeight'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['separatorTopSpacing'] ) ) {
		$attrs['septopspa'] = sanitize_text_field( $input['separatorTopSpacing'] ); }

	/* ── Reveal animation ─────────────────────────────────────────────── */
	if ( ! empty( $input['animEffect'] ) && 'default' !== $input['animEffect'] ) {
		$attrs['aniEffect'] = sanitize_text_field( $input['animEffect'] );
	}
	if ( ! empty( $input['animPositionX'] ) || ! empty( $input['animPositionY'] ) ) {
		$attrs['aniPosition'] = array(
			'aniPositionX' => sanitize_text_field( $input['animPositionX'] ?? '' ),
			'aniPositionY' => sanitize_text_field( $input['animPositionY'] ?? '' ),
		);
	}
	if ( ! empty( $input['animScaleX'] ) || ! empty( $input['animScaleY'] ) || ! empty( $input['animScaleZ'] ) ) {
		$attrs['animationScale'] = array(
			'animationScaleX' => sanitize_text_field( $input['animScaleX'] ?? '' ),
			'animationScaleY' => sanitize_text_field( $input['animScaleY'] ?? '' ),
			'animationScaleZ' => sanitize_text_field( $input['animScaleZ'] ?? '' ),
		);
	}
	if ( ! empty( $input['animRotateX'] ) || ! empty( $input['animRotateY'] ) || ! empty( $input['animRotateZ'] ) ) {
		$attrs['animationRotate'] = array(
			'animationRotateX' => sanitize_text_field( $input['animRotateX'] ?? '' ),
			'animationRotateY' => sanitize_text_field( $input['animRotateY'] ?? '' ),
			'animationRotateZ' => sanitize_text_field( $input['animRotateZ'] ?? '' ),
		);
	}
	if ( ! empty( $input['animOpacity'] ) || ! empty( $input['animSpeed'] ) || ! empty( $input['animDelayExtra'] ) ) {
		$attrs['extrOpt'] = array(
			'animationOpacity' => sanitize_text_field( $input['animOpacity'] ?? '' ),
			'animationSpeed'   => sanitize_text_field( $input['animSpeed'] ?? '' ),
			'animationDelay'   => sanitize_text_field( $input['animDelayExtra'] ?? '' ),
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
		$attrs['globalMargin'] = tpgb_mcp_htitle_spacing( $input['globalMargin'] );  }
	if ( ! empty( $input['globalPadding'] ) ) {
		$attrs['globalPadding'] = tpgb_mcp_htitle_spacing( $input['globalPadding'] ); }
	if ( ! empty( $input['globalBgColor'] ) ) {
		$attrs['globalBg'] = tpgb_mcp_htitle_bg( $input['globalBgColor'] );      }
	if ( ! empty( $input['globalBgHoverColor'] ) ) {
		$attrs['globalBgHover'] = tpgb_mcp_htitle_bg( $input['globalBgHoverColor'] ); }
	if ( ! empty( $input['globalBorder'] ) ) {
		$attrs['globalBorder'] = tpgb_mcp_htitle_border( $input['globalBorder'] );       }
	if ( ! empty( $input['globalBorderHover'] ) ) {
		$attrs['globalBorderHover'] = tpgb_mcp_htitle_border( $input['globalBorderHover'] );  }
	if ( ! empty( $input['globalBRadius'] ) ) {
		$attrs['globalBRadius'] = tpgb_mcp_htitle_radius( $input['globalBRadius'] );      }
	if ( ! empty( $input['globalBRadiusHover'] ) ) {
		$attrs['globalBRadiusHover'] = tpgb_mcp_htitle_radius( $input['globalBRadiusHover'] ); }
	if ( ! empty( $input['globalBShadow'] ) ) {
		$attrs['globalBShadow'] = tpgb_mcp_htitle_bshadow( $input['globalBShadow'] );      }
	if ( ! empty( $input['globalBShadowHover'] ) ) {
		$attrs['globalBShadowHover'] = tpgb_mcp_htitle_bshadow( $input['globalBShadowHover'] ); }

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
	if ( tpgb_mcp_htitle_needs_wrapper( $attrs ) ) {
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
