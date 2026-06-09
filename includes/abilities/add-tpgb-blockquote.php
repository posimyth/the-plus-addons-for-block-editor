<?php
/**
 * Ability: Add Nexter Blocks Blockquote (tpgb/tp-blockquote) to a Gutenberg page.
 *
 * @package The_Plus_Addons_For_Block_Editor
 * @since   1.3.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_register_ability(
	'nexter-blocks/add-tpgb-blockquote',
	array(
		'label'               => __( 'Add Nexter Blocks Blockquote', 'the-plus-addons-for-block-editor' ),
		'description'         => __(
			'Adds the Nexter Blocks Blockquote block (tpgb/tp-blockquote) with full support for all style, typography, spacing, border, background, shadow, animation, transform, and advanced settings.

    BEHAVIOR WHEN USER REQUESTS ALL SETTINGS:
    When the user says "apply all settings", "all available settings", "with all options", or similar — you MUST actively populate EVERY parameter with contextually appropriate values. Do not leave any field empty or at its default. Invent values that make visual sense for the content topic:
    - Choose colors that complement the quote content and topic
    - For transforms: use subtle but visible values that suit the mood (slight rotation, gentle scale, small offset, mild skew)
    - For animation: pick an entrance animation fitting the content tone
    - For spacing: use consistent, balanced padding and margin
    - For borders and shadows: choose styles that frame the quote elegantly
    - For typography: enable it and choose a preset or size that fits the quote importance
    - Always populate: textNormalColor, textHoverColor, authorNormalColor, authorHoverColor, quoteColor, quoteIcon, style, contentAlignment, enableTypography, catBgColor, catBgHoverColor, enableCatShadow, enableCatShadowHover, borderNormal, borderHover, borderRadius, HvrborderRadius, boxPadding, boxMargin, scrollAnimation, animDuration, animDelay, animEasing, globalBgColor, globalBgHoverColor, globalBorder, globalBorderHover, globalBRadius, globalBRadiusHover, globalBShadow, globalBShadowHover, globalMargin, globalPadding, globalPosition, globalClasses, globalId, globalZindex, transitionDuration, transitionFunction, transitionOrigin, rotateDeg, rotateDegHover, offsetX, offsetXHover, scaleValue, scaleValueHover, skewX, skewXHover, flipHorizontal, flipVertical, flipHorizontalHover, flipVerticalHover',
			'the-plus-addons-for-block-editor'
		),
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

				/* ── Content ─────────────────────────────────────────────── */
				'content'              => array(
					'type'        => 'string',
					'description' => 'The quote text / body of the blockquote. ALWAYS pass the exact text the user specified.',
					'default'     => 'You can\'t connect the dots looking forward; you can only connect them looking backwards.',
				),
				'authorName'           => array(
					'type'        => 'string',
					'description' => 'Name of the author or source of the quote. ALWAYS pass this when the user specifies an author.',
					'default'     => 'Steve Jobs',
				),
				'style'                => array(
					'type'        => 'string',
					'enum'        => array( 'style-1', 'style-2' ),
					'description' => 'Visual style preset. style-1 = classic blockquote; style-2 = large decorative quote icon with author attribution.',
					'default'     => 'style-1',
				),
				'contentAlignment'     => array(
					'type'        => 'string',
					'enum'        => array( 'left', 'center', 'right', '' ),
					'description' => 'Text alignment inside the blockquote.',
					'default'     => '',
				),

				/* ── Quote text colours ──────────────────────────────────── */
				'textNormalColor'      => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Quote body text colour (normal).',
				),
				'textHoverColor'       => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Quote body text colour on hover.',
				),

				/* ── Typography ──────────────────────────────────────────── */
				'enableTypography'     => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Enable custom quote text typography.',
				),
				'typoSize'             => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Font size in px.',
				),
				'typoGlobalPreset'     => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Global typography preset ID e.g. "2".',
				),
				'enableAuthorTypo'     => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Enable custom author typography (style-2 only).',
				),
				'authorTypoSize'       => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Author font size in px.',
				),
				'authorTypoPreset'     => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Author global typography preset ID.',
				),

				/* ── Quote icon (style-2) ────────────────────────────────── */
				'quoteIcon'            => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Font Awesome icon class e.g. "fas fa-quote-left". Only rendered in style-2.',
				),
				'quoteColor'           => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Colour of the decorative quote icon.',
				),
				'qiconSize'            => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Font size of the decorative quote icon in px.',
				),
				'iconLoffset'          => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Left offset of the decorative quote icon in px.',
				),
				'iconToffset'          => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Top offset of the decorative quote icon in px.',
				),

				/* ── Author colours (style-2) ────────────────────────────── */
				'authorNormalColor'    => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Author name text colour (normal).',
				),
				'authorHoverColor'     => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Author name text colour on hover.',
				),

				/* ── Block-level spacing ─────────────────────────────────── */
				'boxPadding'           => array(
					'type'        => 'object',
					'description' => 'Inner padding of the blockquote box {top,right,bottom,left,unit}.',
				),
				'boxMargin'            => array(
					'type'        => 'object',
					'description' => 'Outer margin of the blockquote box {top,right,bottom,left,unit}.',
				),

				/* ── Block-level border ──────────────────────────────────── */
				'borderNormal'         => array(
					'type'        => 'object',
					'description' => 'Normal state border {type,color,width:{top,right,bottom,left,unit}}.',
				),
				'borderHover'          => array(
					'type'        => 'object',
					'description' => 'Hover state border.',
				),
				'borderRadius'         => array(
					'type'        => 'object',
					'description' => 'Normal border radius {top,right,bottom,left,unit}.',
				),
				'HvrborderRadius'      => array(
					'type'        => 'object',
					'description' => 'Hover border radius.',
				),

				/* ── Block-level background ──────────────────────────────── */
				'catBgColor'           => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Block normal background colour.',
				),
				'catBgHoverColor'      => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Block hover background colour.',
				),

				/* ── Block-level box shadow ──────────────────────────────── */
				'enableCatShadow'      => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Enable block-level box shadow (normal).',
				),
				'catShadowHorizontal'  => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'catShadowVertical'    => array(
					'type'    => 'integer',
					'default' => 4,
				),
				'catShadowBlur'        => array(
					'type'    => 'integer',
					'default' => 8,
				),
				'catShadowSpread'      => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'catShadowColor'       => array(
					'type'    => 'string',
					'default' => 'rgba(0,0,0,0.40)',
				),
				'enableCatShadowHover' => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Enable block-level box shadow (hover).',
				),
				'catShadowHHorizontal' => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'catShadowHVertical'   => array(
					'type'    => 'integer',
					'default' => 4,
				),
				'catShadowHBlur'       => array(
					'type'    => 'integer',
					'default' => 8,
				),
				'catShadowHSpread'     => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'catShadowHColor'      => array(
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
					'type'        => 'string',
					'enum'        => array( '', 'slow', 'normal', 'fast' ),
					'default'     => '',
					'description' => 'Animation duration.',
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
					'description' => 'Rotation degrees e.g. "10". Setting any rotate value enables the transform.',
				),
				'rotateX'              => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Rotate X axis degrees.',
				),
				'rotateY'              => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Rotate Y axis degrees.',
				),
				'rotatePerspective'    => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Perspective for 3D rotation.',
				),
				'rotateDegHover'       => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Hover rotation degrees. Setting any hover rotate value enables hover transform.',
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
					'type'        => 'string',
					'default'     => '',
					'description' => 'Translate X in px e.g. "10". Setting any offset value enables the transform.',
				),
				'offsetY'              => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Translate Y in px.',
				),
				'offsetZ'              => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Translate Z in px.',
				),
				'offsetXHover'         => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Hover translate X. Setting any hover offset value enables hover transform.',
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
					'type'        => 'string',
					'default'     => '',
					'description' => 'Scale value e.g. "1.5". Values other than "1" enable the transform.',
				),
				'scaleKeepRatio'       => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'scaleValueHover'      => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Hover scale value. Values other than "1" enable hover transform.',
				),

				/* ── Transform: Skew ─────────────────────────────────────── */
				'skewX'                => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Skew X in degrees e.g. "10". Setting any skew value enables the transform.',
				),
				'skewY'                => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Skew Y in degrees.',
				),
				'skewXHover'           => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Hover skew X. Setting any hover skew value enables hover transform.',
				),
				'skewYHover'           => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Transform: Flip ─────────────────────────────────────── */
				'flipHorizontal'       => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Flip block horizontally.',
				),
				'flipVertical'         => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Flip block vertically.',
				),
				'flipHorizontalHover'  => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Flip horizontally on hover.',
				),
				'flipVerticalHover'    => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Flip vertically on hover.',
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

		'execute_callback'    => 'tpgb_mcp_add_blockquote_ability',
		'permission_callback' => 'tpgb_mcp_add_blockquote_permission',
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
 * Permission callback for the add-blockquote ability.
 *
 * @param array|null $input Ability input arguments.
 * @return bool True when the current user may insert the block.
 */
function tpgb_mcp_add_blockquote_permission( ?array $input = null ): bool {
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
function tpgb_mcp_blockquote_spacing( array $v ): array {
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
function tpgb_mcp_blockquote_border( array $b ): array {
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
function tpgb_mcp_blockquote_radius( array $r ): array {
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
function tpgb_mcp_blockquote_bshadow( array $s ): array {
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
function tpgb_mcp_blockquote_bg( string $color ): array {
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
 * Build a Nexter Blocks typography attribute.
 *
 * @param bool              $enable           Whether custom typography is enabled.
 * @param int               $size             Font size in px (0 to skip).
 * @param string            $preset           Global typography preset ID, or '' for none.
 * @param object|array|null $font_family_attr Font-family attribute structured for the block.
 * @return array Typography attribute structured for the block.
 */
function tpgb_mcp_blockquote_typo( bool $enable, int $size, string $preset, $font_family_attr = null ): array {
	$typo = array(
		'openTypography' => $enable ? 1 : 0,
		'size'           => array(
			'md'   => $size > 0 ? (string) $size : '',
			'unit' => 'px',
		),
		'height'         => '',
		'spacing'        => '',
		'fontFamily'     => $font_family_attr ?? (object) array(),
	);
	// Only emit globalTypo when a preset is set; an empty string makes the
	// CSS generator emit `var(--tpgb-T-font-family)` which is undefined.
	if ( '' !== $preset ) {
		$typo['globalTypo'] = sanitize_text_field( $preset );
	}
	return $typo;
}

/**
 * Determine whether any wrapper-level (global/transform) attributes are set.
 *
 * @param array $attrs Block attributes built so far.
 * @return bool True when the block needs the tpgbDisrule wrapper enabled.
 */
function tpgb_mcp_blockquote_needs_wrapper( array $attrs ): bool {
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
 * Execute callback: insert a tpgb/tp-blockquote block into a post.
 *
 * @param array $input Ability input arguments.
 * @return array|WP_Error Ability result or error on failure.
 */
function tpgb_mcp_add_blockquote_ability( array $input ) {

	if ( ! defined( 'TPGB_VERSION' ) && ! defined( 'TPGBP_VERSION' ) ) {
		return new WP_Error( 'nexter_blocks_missing', __( 'Nexter Blocks must be active.', 'the-plus-addons-for-block-editor' ) );
	}

	$block_name = 'tpgb/tp-blockquote';

	if ( ! tpgb_mcp_has_registered_block( $block_name ) ) {
		return new WP_Error( 'block_missing', __( 'tpgb/tp-blockquote is not registered.', 'the-plus-addons-for-block-editor' ) );
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

	/* ── Content ──────────────────────────────────────────────────────── */
	$quote_text = isset( $input['content'] ) && '' !== $input['content']
		? tpgb_mcp_clean_text( (string) $input['content'] )
		: 'You can\'t connect the dots looking forward; you can only connect them looking backwards.';

	$author_text = isset( $input['authorName'] ) && '' !== $input['authorName']
		? sanitize_text_field( (string) $input['authorName'] )
		: 'Steve Jobs';

	$attrs['content']    = $quote_text;
	$attrs['authorName'] = $author_text;

	$style = ! empty( $input['style'] ) ? sanitize_text_field( $input['style'] ) : 'style-1';
	if ( ! in_array( $style, array( 'style-1', 'style-2' ), true ) ) {
		$style = 'style-1';
	}
	$attrs['style'] = $style;

	if ( isset( $input['contentAlignment'] ) && '' !== $input['contentAlignment'] ) {
		$c_align = sanitize_text_field( (string) $input['contentAlignment'] );
		if ( in_array( $c_align, array( 'left', 'center', 'right' ), true ) ) {
			$attrs['contentAlignment'] = $c_align;
		}
	}

	/* ── Text colours ─────────────────────────────────────────────────── */
	if ( ! empty( $input['textNormalColor'] ) ) {
		$attrs['textNormalColor'] = sanitize_text_field( $input['textNormalColor'] ); }
	if ( ! empty( $input['textHoverColor'] ) ) {
		$attrs['textHoverColor'] = sanitize_text_field( $input['textHoverColor'] );  }

	/* ── Typography ───────────────────────────────────────────────────── */
	if ( ! empty( $input['enableTypography'] ) ) {
		$attrs['typography'] = tpgb_mcp_blockquote_typo( true, absint( $input['typoSize'] ?? 0 ), $input['typoGlobalPreset'] ?? '', tpgb_mcp_font_family_attr( $input ) );
	}
	if ( ! empty( $input['enableAuthorTypo'] ) ) {
		$attrs['authorTypo'] = tpgb_mcp_blockquote_typo( true, absint( $input['authorTypoSize'] ?? 0 ), $input['authorTypoPreset'] ?? '', tpgb_mcp_font_family_attr( $input ) );
	}

	/* ── Quote icon (style-2) ─────────────────────────────────────────── */
	if ( ! empty( $input['quoteIcon'] ) ) {
		$attrs['quoteIcon'] = sanitize_text_field( $input['quoteIcon'] );  }
	if ( ! empty( $input['quoteColor'] ) ) {
		$attrs['quoteColor'] = sanitize_text_field( $input['quoteColor'] ); }
	if ( ! empty( $input['qiconSize'] ) ) {
		$attrs['qiconSize'] = array(
			'md'   => (string) absint( $input['qiconSize'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['iconLoffset'] ) ) {
		$attrs['iconLoffset'] = array(
			'md'   => (string) intval( $input['iconLoffset'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['iconToffset'] ) ) {
		$attrs['iconToffset'] = array(
			'md'   => (string) intval( $input['iconToffset'] ),
			'unit' => 'px',
		); }

	/* ── Author colours (style-2) ─────────────────────────────────────── */
	if ( ! empty( $input['authorNormalColor'] ) ) {
		$attrs['authorNormalColor'] = sanitize_text_field( $input['authorNormalColor'] ); }
	if ( ! empty( $input['authorHoverColor'] ) ) {
		$attrs['authorHoverColor'] = sanitize_text_field( $input['authorHoverColor'] );  }

	/* ── Block-level spacing ──────────────────────────────────────────── */
	if ( ! empty( $input['boxPadding'] ) ) {
		$attrs['boxPadding'] = tpgb_mcp_blockquote_spacing( $input['boxPadding'] ); }
	if ( ! empty( $input['boxMargin'] ) ) {
		$attrs['boxMargin'] = tpgb_mcp_blockquote_spacing( $input['boxMargin'] );  }

	/* ── Block-level border ───────────────────────────────────────────── */
	if ( ! empty( $input['borderNormal'] ) ) {
		$attrs['borderNormal'] = tpgb_mcp_blockquote_border( $input['borderNormal'] ); }
	if ( ! empty( $input['borderHover'] ) ) {
		$attrs['borderHover'] = tpgb_mcp_blockquote_border( $input['borderHover'] );  }
	if ( ! empty( $input['borderRadius'] ) ) {
		$attrs['borderRadius'] = tpgb_mcp_blockquote_radius( $input['borderRadius'] ); }
	if ( ! empty( $input['HvrborderRadius'] ) ) {
		$attrs['HvrborderRadius'] = tpgb_mcp_blockquote_radius( $input['HvrborderRadius'] ); }

	/* ── Block-level background ───────────────────────────────────────── */
	if ( ! empty( $input['catBgColor'] ) ) {
		$attrs['catBg'] = tpgb_mcp_blockquote_bg( $input['catBgColor'] );      }
	if ( ! empty( $input['catBgHoverColor'] ) ) {
		$attrs['catBgHover'] = tpgb_mcp_blockquote_bg( $input['catBgHoverColor'] ); }

	/* ── Block-level box shadow (normal) ──────────────────────────────── */
	if ( ! empty( $input['enableCatShadow'] ) ) {
		$attrs['catBoxShadow'] = array(
			'openShadow' => true,
			'inset'      => 0,
			'horizontal' => (string) intval( $input['catShadowHorizontal'] ?? 0 ),
			'vertical'   => (string) intval( $input['catShadowVertical'] ?? 4 ),
			'blur'       => (string) absint( $input['catShadowBlur'] ?? 8 ),
			'spread'     => (string) intval( $input['catShadowSpread'] ?? 0 ),
			'color'      => sanitize_text_field( $input['catShadowColor'] ?? 'rgba(0,0,0,0.40)' ),
		);
	}

	/* ── Block-level box shadow (hover) ───────────────────────────────── */
	if ( ! empty( $input['enableCatShadowHover'] ) ) {
		$attrs['catBoxShadowHover'] = array(
			'openShadow' => true,
			'inset'      => 0,
			'horizontal' => (string) intval( $input['catShadowHHorizontal'] ?? 0 ),
			'vertical'   => (string) intval( $input['catShadowHVertical'] ?? 4 ),
			'blur'       => (string) absint( $input['catShadowHBlur'] ?? 8 ),
			'spread'     => (string) intval( $input['catShadowHSpread'] ?? 0 ),
			'color'      => sanitize_text_field( $input['catShadowHColor'] ?? 'rgba(0,0,0,0.40)' ),
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
		$attrs['globalMargin'] = tpgb_mcp_blockquote_spacing( $input['globalMargin'] );  }
	if ( ! empty( $input['globalPadding'] ) ) {
		$attrs['globalPadding'] = tpgb_mcp_blockquote_spacing( $input['globalPadding'] ); }

	/* ── Global: Background ───────────────────────────────────────────── */
	if ( ! empty( $input['globalBgColor'] ) ) {
		$attrs['globalBg'] = tpgb_mcp_blockquote_bg( $input['globalBgColor'] );      }
	if ( ! empty( $input['globalBgHoverColor'] ) ) {
		$attrs['globalBgHover'] = tpgb_mcp_blockquote_bg( $input['globalBgHoverColor'] ); }

	/* ── Global: Border ───────────────────────────────────────────────── */
	if ( ! empty( $input['globalBorder'] ) ) {
		$attrs['globalBorder'] = tpgb_mcp_blockquote_border( $input['globalBorder'] );      }
	if ( ! empty( $input['globalBorderHover'] ) ) {
		$attrs['globalBorderHover'] = tpgb_mcp_blockquote_border( $input['globalBorderHover'] ); }

	/* ── Global: Border radius ────────────────────────────────────────── */
	if ( ! empty( $input['globalBRadius'] ) ) {
		$attrs['globalBRadius'] = tpgb_mcp_blockquote_radius( $input['globalBRadius'] );      }
	if ( ! empty( $input['globalBRadiusHover'] ) ) {
		$attrs['globalBRadiusHover'] = tpgb_mcp_blockquote_radius( $input['globalBRadiusHover'] ); }

	/* ── Global: Box shadow ───────────────────────────────────────────── */
	if ( ! empty( $input['globalBShadow'] ) ) {
		$attrs['globalBShadow'] = tpgb_mcp_blockquote_bshadow( $input['globalBShadow'] );      }
	if ( ! empty( $input['globalBShadowHover'] ) ) {
		$attrs['globalBShadowHover'] = tpgb_mcp_blockquote_bshadow( $input['globalBShadowHover'] ); }

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
	if ( tpgb_mcp_blockquote_needs_wrapper( $attrs ) ) {
		$attrs['tpgbDisrule'] = true;
	}

	/* ── Raw settings override ────────────────────────────────────────── */
	tpgb_mcp_apply_typo_decoration( $attrs, $input );
	$attrs = tpgb_mcp_merge_block_settings( $attrs, $input['settings'] ?? array() );

	// ---------------------------------------------------------------------
	// Build inner HTML.
	// ---------------------------------------------------------------------
	$alignment   = ! empty( $attrs['contentAlignment'] ) ? ' ' . esc_attr( $attrs['contentAlignment'] ) : '';
	$block_class = "tp-blockquote tpgb-relative-block tpgb-block-{$block_id}";
	$inner_class = 'tpgb-blockquote-inner tpgb-quote-' . esc_attr( $style );

	if ( 'style-2' === $style ) {
		$icon        = ! empty( $attrs['quoteIcon'] ) ? $attrs['quoteIcon'] : 'fas fa-quote-left';
		$inner_block = sprintf(
			'<div class="%1$s"><span class="tpgb-quote-left"><i class="%2$s"></i></span><blockquote class="tpgb-quote-text%3$s"><div class="quote-text-wrap"><span class="quote-text">%4$s</span></div><div class="tpgb-quote-author">%5$s</div></blockquote></div>',
			esc_attr( $inner_class ),
			esc_attr( $icon ),
			$alignment,
			tpgb_mcp_clean_text( $quote_text ),
			esc_html( $author_text )
		);
	} else {
		$inner_block = sprintf(
			'<div class="%1$s"><blockquote class="tpgb-quote-text%2$s"><div class="quote-text-wrap"><span class="quote-text">%3$s</span></div></blockquote></div>',
			esc_attr( $inner_class ),
			$alignment,
			tpgb_mcp_clean_text( $quote_text )
		);
	}

	$inner_html = sprintf(
		'<div class="%1$s">%2$s</div>',
		esc_attr( $block_class ),
		$inner_block
	);

	// ---------------------------------------------------------------------
	// Build, insert, save.
	// ---------------------------------------------------------------------
	$block                 = tpgb_mcp_build_block( $block_name, $attrs );
	$block['innerHTML']    = $inner_html;
	$block['innerContent'] = array( $inner_html );

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
