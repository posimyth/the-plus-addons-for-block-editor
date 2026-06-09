<?php
/**
 * Ability: Add Nexter Blocks Pro Paragraph (tpgb/tp-pro-paragraph) to a Gutenberg page.
 *
 * @package The_Plus_Addons_For_Block_Editor
 * @since   1.3.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_register_ability(
	'nexter-blocks/add-tpgb-pro-paragraph',
	array(
		'label'               => __( 'Add Nexter Blocks Pro Paragraph', 'the-plus-addons-for-block-editor' ),
		'description'         => __( 'Adds the Nexter Blocks Pro Paragraph block (tpgb/tp-pro-paragraph) — an advanced text/paragraph block with optional title, multi-column text, drop cap styling (letter-style or boxed), link colours, text shadows, typography controls, and custom HTML tags. Use for magazine-style articles, blog intros, or rich text sections. This is a dynamic block.', 'the-plus-addons-for-block-editor' ),
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

				/* ── Title ────────────────────────────────────────────────── */
				'showTitle'              => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'title'                  => array(
					'type'    => 'string',
					'default' => 'Save the Earth for future Generations.',
				),
				'titleTag'               => array(
					'type'    => 'string',
					'enum'    => array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p' ),
					'default' => 'h3',
				),
				'extraTitle'             => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Highlighted span inside title.',
				),
				'extraContent'           => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Highlighted span inside content.',
				),

				/* ── Description ──────────────────────────────────────────── */
				'descTag'                => array(
					'type'    => 'string',
					'enum'    => array( 'p', 'div', 'span' ),
					'default' => 'p',
				),
				'content'                => array(
					'type'        => 'string',
					'description' => 'Paragraph body text.',
					'default'     => '',
				),
				'alignment'              => array(
					'type'    => 'string',
					'enum'    => array( 'left', 'center', 'right', 'justify' ),
					'default' => '',
				),

				/* ── Text styling ─────────────────────────────────────────── */
				'enableTextTypo'         => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'textTypoSize'           => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'textColor'              => array(
					'type'    => 'string',
					'default' => '',
				),
				'linkColor'              => array(
					'type'    => 'string',
					'default' => '',
				),
				'linkHoverColor'         => array(
					'type'    => 'string',
					'default' => '',
				),
				'enableTextShadow'       => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'textShadowH'            => array(
					'type'    => 'integer',
					'default' => 2,
				),
				'textShadowV'            => array(
					'type'    => 'integer',
					'default' => 3,
				),
				'textShadowBlur'         => array(
					'type'    => 'integer',
					'default' => 8,
				),
				'textShadowColor'        => array(
					'type'    => 'string',
					'default' => 'rgba(0,0,0,0.5)',
				),
				'enableTextShadowHover'  => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'hoverTextShadowH'       => array(
					'type'    => 'integer',
					'default' => 2,
				),
				'hoverTextShadowV'       => array(
					'type'    => 'integer',
					'default' => 3,
				),
				'hoverTextShadowBlur'    => array(
					'type'    => 'integer',
					'default' => 8,
				),
				'hoverTextShadowColor'   => array(
					'type'    => 'string',
					'default' => 'rgba(0,0,0,0.5)',
				),

				/* ── Title styling ────────────────────────────────────────── */
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
				'titleBottomSpace'       => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'enableTitleShadow'      => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'titleShadowH'           => array(
					'type'    => 'integer',
					'default' => 2,
				),
				'titleShadowV'           => array(
					'type'    => 'integer',
					'default' => 3,
				),
				'titleShadowBlur'        => array(
					'type'    => 'integer',
					'default' => 8,
				),
				'titleShadowColor'       => array(
					'type'    => 'string',
					'default' => 'rgba(0,0,0,0.5)',
				),
				'enableTitleShadowHover' => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'hoverTitleShadowH'      => array(
					'type'    => 'integer',
					'default' => 2,
				),
				'hoverTitleShadowV'      => array(
					'type'    => 'integer',
					'default' => 3,
				),
				'hoverTitleShadowBlur'   => array(
					'type'    => 'integer',
					'default' => 8,
				),
				'hoverTitleShadowColor'  => array(
					'type'    => 'string',
					'default' => 'rgba(0,0,0,0.5)',
				),

				/* ── List (UL) styling ────────────────────────────────────── */
				'listMargin'             => array( 'type' => 'object' ),
				'listPadding'            => array( 'type' => 'object' ),

				/* ── Multi-column ─────────────────────────────────────────── */
				'columnCount'            => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Number of columns to split the paragraph into.',
				),
				'columnSpacing'          => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Gap between columns in px.',
				),

				/* ── Drop Cap ─────────────────────────────────────────────── */
				'enableDropCap'          => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'dropCapView'            => array(
					'type'        => 'string',
					'enum'        => array( '', 'letter', 'boxed', 'framed', 'none' ),
					'description' => 'Drop cap display style.',
					'default'     => '',
				),
				'dropCapColor'           => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Drop cap text colour.',
				),
				'dropCapSecondaryColor'  => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Drop cap bg/border colour.',
				),
				'dropCapSpacing'         => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Space around drop cap in px.',
				),
				'dropCapSize'            => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Drop cap padding in px.',
				),
				'dropCapBorderRadius'    => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Drop cap border radius in px.',
				),
				'dropCapBorderWidth'     => array(
					'type'        => 'object',
					'description' => 'Drop cap border width.',
				),
				'enableDropCapTypo'      => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'dropCapTypoSize'        => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'enableDropCapShadow'    => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'dropCapShadowH'         => array(
					'type'    => 'integer',
					'default' => 2,
				),
				'dropCapShadowV'         => array(
					'type'    => 'integer',
					'default' => 3,
				),
				'dropCapShadowBlur'      => array(
					'type'    => 'integer',
					'default' => 8,
				),
				'dropCapShadowColor'     => array(
					'type'    => 'string',
					'default' => 'rgba(0,0,0,0.5)',
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

				'settings'               => array( 'type' => 'object' ),
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

		'execute_callback'    => 'tpgb_mcp_add_pro_paragraph_ability',
		'permission_callback' => 'tpgb_mcp_add_pro_paragraph_permission',
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
 * Permission callback for the add-pro-paragraph ability.
 *
 * @param array|null $input Ability input arguments.
 * @return bool True when the current user may insert the block.
 */
function tpgb_mcp_add_pro_paragraph_permission( ?array $input = null ): bool {
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
function tpgb_mcp_ppar_spacing( array $v ): array {
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
function tpgb_mcp_ppar_border( array $b ): array {
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
function tpgb_mcp_ppar_radius( array $r ): array {
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
function tpgb_mcp_ppar_bshadow( array $s ): array {
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
function tpgb_mcp_ppar_bg( string $color ): array {
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
function tpgb_mcp_ppar_needs_wrapper( array $attrs ): bool {
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

/**
 * Render the static save.js markup in PHP so the saved post_content matches
 * what the editor's save() would produce. Without this, innerHTML stays empty
 * and the frontend renders nothing for this block.
 *
 * @param array $attrs Block attributes built so far.
 * @return string Inner HTML for the block.
 */
function tpgb_mcp_ppar_render_html( array $attrs ): string {
	$block_id   = (string) ( $attrs['block_id'] ?? '' );
	$show_title = ! isset( $attrs['Showtitle'] ) || false !== $attrs['Showtitle'];
	$title_tag  = ! empty( $attrs['titleTag'] ) ? $attrs['titleTag'] : 'h3';
	$desc_tag   = ! empty( $attrs['descTag'] ) ? $attrs['descTag'] : 'p';
	$title      = array_key_exists( 'title', $attrs )
		? (string) $attrs['title']
		: 'Save the Earth for future Generations.';
	$content    = array_key_exists( 'content', $attrs )
		? (string) $attrs['content']
		: 'No human technology can replace `nature`s technology`, perfected over hundreds of millions of years to sustain life on Earth. For those in power, the questions are straightforward. Are they prepared to jeopardize their careers – or their profits – for our children’s children? Are they ready to put short-term politicking aside and help deliver a sustainable plan for the future? Are they willing to take difficult decisions on behalf of voters they’ll never meet?';
	$drop_cap   = ! empty( $attrs['dropCap'] );
	$dcap_view  = (string) ( $attrs['dcapView'] ?? '' );

	$title_html = '';
	if ( $show_title && '' !== $title ) {
		$title_html = '<' . $title_tag . ' class="pro-heading-inner">' . $title . '</' . $title_tag . '>';
	}

	$content_html = '';
	if ( '' !== $content ) {
		$inner_class  = 'pro-paragraph-inner'
			. ( $drop_cap ? ' tpgb-drop-cap' : '' )
			. ( ( $drop_cap && $dcap_view ) ? ' tpgb-drop-' . $dcap_view : '' );
		$content_html = '<div class="' . esc_attr( $inner_class ) . '"><' . $desc_tag . '>' . $content . '</' . $desc_tag . '></div>';
	}

	$block_class = 'tpgb-pro-paragraph tpgb-block-' . $block_id;
	$inner       = '<div class="' . esc_attr( $block_class ) . '">' . $title_html . $content_html . '</div>';

	// Replicate Pmgc_ConditionalWrap: outer .tpgb-wrap-{id} only when global triggers are set.
	$needs_wrap = false;
	if ( ! empty( $attrs['globalAnim'] ) && is_array( $attrs['globalAnim'] ) ) {
		foreach ( array( 'md', 'sm', 'xs' ) as $d ) {
			if ( ! empty( $attrs['globalAnim'][ $d ] ) && 'none' !== $attrs['globalAnim'][ $d ] ) {
				$needs_wrap = true;
				break; }
		}
	}
	if ( ! $needs_wrap && ( ! empty( $attrs['globalClasses'] ) || ! empty( $attrs['globalId'] ) || ! empty( $attrs['globalCustomCss'] ) ) ) {
		$needs_wrap = true;
	}
	if ( ! $needs_wrap && ! empty( $attrs['globalPosition'] ) && is_array( $attrs['globalPosition'] ) ) {
		if ( ! empty( $attrs['globalPosition']['md'] ) || ! empty( $attrs['globalPosition']['sm'] ) || ! empty( $attrs['globalPosition']['xs'] ) ) {
			$needs_wrap = true;
		}
	}

	if ( ! $needs_wrap ) {
		return $inner; }

	$wrap_class = 'tpgb-wrap-' . $block_id;
	if ( ! empty( $attrs['globalPosition'] ) && is_array( $attrs['globalPosition'] ) ) {
		$md = (string) ( $attrs['globalPosition']['md'] ?? '' );
		$sm = (string) ( $attrs['globalPosition']['sm'] ?? '' );
		$xs = (string) ( $attrs['globalPosition']['xs'] ?? '' );
		if ( '' !== $md ) {
			$wrap_class .= ' tpgb-position-' . $md; }
		if ( '' !== $sm ) {
			$wrap_class .= ' tpgb-tab-position-' . $sm; } elseif ( '' !== $md ) {
			$wrap_class .= ' tpgb-tab-position-' . $md; }
			if ( '' !== $xs ) {
				$wrap_class .= ' tpgb-mobile-position-' . $xs; } elseif ( '' !== $sm ) {
						$wrap_class .= ' tpgb-mobile-position-' . $sm; } elseif ( '' !== $md ) {
					$wrap_class .= ' tpgb-mobile-position-' . $md; }
	}
	if ( ! empty( $attrs['globalClasses'] ) ) {
		$wrap_class .= ' ' . $attrs['globalClasses']; }

	$wrap_id = ! empty( $attrs['globalId'] ) ? ' id="' . esc_attr( $attrs['globalId'] ) . '"' : '';
	return '<div' . $wrap_id . ' class="' . esc_attr( $wrap_class ) . '">' . $inner . '</div>';
}

// -------------------------------------------------------------------------
// EXECUTE CALLBACK
// -------------------------------------------------------------------------

/**
 * Execute callback: insert a tpgb/tp-pro-paragraph block into a post.
 *
 * @param array $input Ability input arguments.
 * @return array|WP_Error Ability result or error on failure.
 */
function tpgb_mcp_add_pro_paragraph_ability( array $input ) {

	if ( ! defined( 'TPGB_VERSION' ) && ! defined( 'TPGBP_VERSION' ) ) {
		return new WP_Error( 'nexter_blocks_missing', __( 'Nexter Blocks must be active.', 'the-plus-addons-for-block-editor' ) );
	}

	$block_name = 'tpgb/tp-pro-paragraph';
	if ( ! tpgb_mcp_has_registered_block( $block_name ) ) {
		return new WP_Error( 'block_missing', __( 'tpgb/tp-pro-paragraph is not registered.', 'the-plus-addons-for-block-editor' ) );
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

	/* ── Title ────────────────────────────────────────────────────────── */
	if ( isset( $input['showTitle'] ) && ! $input['showTitle'] ) {
		$attrs['Showtitle'] = false; }
	if ( ! empty( $input['title'] ) && 'Save the Earth for future Generations.' !== $input['title'] ) {
		$attrs['title'] = tpgb_mcp_clean_text( $input['title'] );
	}
	if ( ! empty( $input['titleTag'] ) && 'h3' !== $input['titleTag'] ) {
		$attrs['titleTag'] = sanitize_key( $input['titleTag'] ); }
	if ( ! empty( $input['extraTitle'] ) ) {
		$attrs['exTitle'] = sanitize_text_field( $input['extraTitle'] ); }
	if ( ! empty( $input['extraContent'] ) ) {
		$attrs['exproCnt'] = sanitize_text_field( $input['extraContent'] ); }

	/* ── Description ──────────────────────────────────────────────────── */
	if ( ! empty( $input['descTag'] ) && 'p' !== $input['descTag'] ) {
		$attrs['descTag'] = sanitize_key( $input['descTag'] ); }
	if ( isset( $input['content'] ) && '' !== $input['content'] ) {
		$attrs['content'] = tpgb_mcp_clean_text( $input['content'] );
	}
	if ( ! empty( $input['alignment'] ) ) {
		$attrs['alignment'] = array( 'md' => sanitize_key( $input['alignment'] ) );
	}

	/* ── Text styling ─────────────────────────────────────────────────── */
	if ( ! empty( $input['enableTextTypo'] ) ) {
		$attrs['textTypo'] = array(
			'openTypography' => 1,
			'size'           => array(
				'md'   => ! empty( $input['textTypoSize'] ) ? (string) absint( $input['textTypoSize'] ) : '',
				'unit' => 'px',
			),
			'height'         => '',
			'spacing'        => '',
			'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
		);
	}
	if ( ! empty( $input['textColor'] ) ) {
		$attrs['textColor'] = sanitize_text_field( $input['textColor'] ); }
	if ( ! empty( $input['linkColor'] ) ) {
		$attrs['linkColor'] = sanitize_text_field( $input['linkColor'] ); }
	if ( ! empty( $input['linkHoverColor'] ) ) {
		$attrs['linkHoverColor'] = sanitize_text_field( $input['linkHoverColor'] ); }
	if ( ! empty( $input['enableTextShadow'] ) ) {
		$attrs['textShadow'] = array(
			'openShadow' => true,
			'typeShadow' => 'text-shadow',
			'horizontal' => intval( $input['textShadowH'] ?? 2 ),
			'vertical'   => intval( $input['textShadowV'] ?? 3 ),
			'blur'       => absint( $input['textShadowBlur'] ?? 8 ),
			'color'      => sanitize_text_field( $input['textShadowColor'] ?? 'rgba(0,0,0,0.5)' ),
		);
	}
	if ( ! empty( $input['enableTextShadowHover'] ) ) {
		$attrs['HovertextShadow'] = array(
			'openShadow' => true,
			'typeShadow' => 'text-shadow',
			'horizontal' => intval( $input['hoverTextShadowH'] ?? 2 ),
			'vertical'   => intval( $input['hoverTextShadowV'] ?? 3 ),
			'blur'       => absint( $input['hoverTextShadowBlur'] ?? 8 ),
			'color'      => sanitize_text_field( $input['hoverTextShadowColor'] ?? 'rgba(0,0,0,0.5)' ),
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
	if ( ! empty( $input['titleBottomSpace'] ) ) {
		$attrs['titleBtmSpace'] = array(
			'md'   => (string) absint( $input['titleBottomSpace'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['enableTitleShadow'] ) ) {
		$attrs['titleShadow'] = array(
			'openShadow' => true,
			'typeShadow' => 'text-shadow',
			'horizontal' => intval( $input['titleShadowH'] ?? 2 ),
			'vertical'   => intval( $input['titleShadowV'] ?? 3 ),
			'blur'       => absint( $input['titleShadowBlur'] ?? 8 ),
			'color'      => sanitize_text_field( $input['titleShadowColor'] ?? 'rgba(0,0,0,0.5)' ),
		);
	}
	if ( ! empty( $input['enableTitleShadowHover'] ) ) {
		$attrs['HovertitleShadow'] = array(
			'openShadow' => true,
			'typeShadow' => 'text-shadow',
			'horizontal' => intval( $input['hoverTitleShadowH'] ?? 2 ),
			'vertical'   => intval( $input['hoverTitleShadowV'] ?? 3 ),
			'blur'       => absint( $input['hoverTitleShadowBlur'] ?? 8 ),
			'color'      => sanitize_text_field( $input['hoverTitleShadowColor'] ?? 'rgba(0,0,0,0.5)' ),
		);
	}

	/* ── List styling ─────────────────────────────────────────────────── */
	if ( ! empty( $input['listMargin'] ) ) {
		$attrs['ulMargin'] = tpgb_mcp_ppar_spacing( $input['listMargin'] ); }
	if ( ! empty( $input['listPadding'] ) ) {
		$attrs['ulPadding'] = tpgb_mcp_ppar_spacing( $input['listPadding'] ); }

	/* ── Multi-column ─────────────────────────────────────────────────── */
	if ( ! empty( $input['columnCount'] ) ) {
		$attrs['pCol'] = array( 'md' => (string) absint( $input['columnCount'] ) ); }
	if ( ! empty( $input['columnSpacing'] ) ) {
		$attrs['pcolspac'] = array(
			'md'   => (string) absint( $input['columnSpacing'] ),
			'unit' => 'px',
		); }

	/* ── Drop Cap ─────────────────────────────────────────────────────── */
	if ( ! empty( $input['enableDropCap'] ) ) {
		$attrs['dropCap'] = true;
		if ( ! empty( $input['dropCapView'] ) ) {
			$attrs['dcapView'] = sanitize_key( $input['dropCapView'] ); }
		if ( ! empty( $input['dropCapColor'] ) ) {
			$attrs['dcapCol'] = sanitize_text_field( $input['dropCapColor'] ); }
		if ( ! empty( $input['dropCapSecondaryColor'] ) ) {
			$attrs['dcapseCol'] = sanitize_text_field( $input['dropCapSecondaryColor'] ); }
		if ( ! empty( $input['dropCapSpacing'] ) ) {
			$attrs['tcapspac'] = sanitize_text_field( $input['dropCapSpacing'] ); }
		if ( ! empty( $input['dropCapSize'] ) ) {
			$attrs['tcapSize'] = sanitize_text_field( $input['dropCapSize'] ); }
		if ( ! empty( $input['dropCapBorderRadius'] ) ) {
			$attrs['tcapbrad'] = sanitize_text_field( $input['dropCapBorderRadius'] ); }
		if ( ! empty( $input['dropCapBorderWidth'] ) ) {
			$attrs['boWidth'] = tpgb_mcp_ppar_spacing( $input['dropCapBorderWidth'] ); }
		if ( ! empty( $input['enableDropCapTypo'] ) ) {
			$attrs['dcapTypo'] = array(
				'openTypography' => 1,
				'size'           => array(
					'md'   => ! empty( $input['dropCapTypoSize'] ) ? (string) absint( $input['dropCapTypoSize'] ) : '',
					'unit' => 'px',
				),
				'height'         => '',
				'spacing'        => '',
				'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
			);
		}
		if ( ! empty( $input['enableDropCapShadow'] ) ) {
			$attrs['captShadow'] = array(
				'openShadow' => true,
				'typeShadow' => 'text-shadow',
				'horizontal' => intval( $input['dropCapShadowH'] ?? 2 ),
				'vertical'   => intval( $input['dropCapShadowV'] ?? 3 ),
				'blur'       => absint( $input['dropCapShadowBlur'] ?? 8 ),
				'color'      => sanitize_text_field( $input['dropCapShadowColor'] ?? 'rgba(0,0,0,0.5)' ),
			);
		}
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
		$attrs['globalMargin'] = tpgb_mcp_ppar_spacing( $input['globalMargin'] );  }
	if ( ! empty( $input['globalPadding'] ) ) {
		$attrs['globalPadding'] = tpgb_mcp_ppar_spacing( $input['globalPadding'] ); }
	if ( ! empty( $input['globalBgColor'] ) ) {
		$attrs['globalBg'] = tpgb_mcp_ppar_bg( $input['globalBgColor'] );      }
	if ( ! empty( $input['globalBgHoverColor'] ) ) {
		$attrs['globalBgHover'] = tpgb_mcp_ppar_bg( $input['globalBgHoverColor'] ); }
	if ( ! empty( $input['globalBorder'] ) ) {
		$attrs['globalBorder'] = tpgb_mcp_ppar_border( $input['globalBorder'] );       }
	if ( ! empty( $input['globalBorderHover'] ) ) {
		$attrs['globalBorderHover'] = tpgb_mcp_ppar_border( $input['globalBorderHover'] );  }
	if ( ! empty( $input['globalBRadius'] ) ) {
		$attrs['globalBRadius'] = tpgb_mcp_ppar_radius( $input['globalBRadius'] );      }
	if ( ! empty( $input['globalBRadiusHover'] ) ) {
		$attrs['globalBRadiusHover'] = tpgb_mcp_ppar_radius( $input['globalBRadiusHover'] ); }
	if ( ! empty( $input['globalBShadow'] ) ) {
		$attrs['globalBShadow'] = tpgb_mcp_ppar_bshadow( $input['globalBShadow'] );      }
	if ( ! empty( $input['globalBShadowHover'] ) ) {
		$attrs['globalBShadowHover'] = tpgb_mcp_ppar_bshadow( $input['globalBShadowHover'] ); }

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
	if ( tpgb_mcp_ppar_needs_wrapper( $attrs ) ) {
		$attrs['tpgbDisrule'] = true; }

	/* ── Raw settings override ────────────────────────────────────────── */
	tpgb_mcp_apply_typo_decoration( $attrs, $input );
	$attrs = tpgb_mcp_merge_block_settings( $attrs, $input['settings'] ?? array() );

	/* ── Build, insert, save ──────────────────────────────────────────── */
	$block                 = tpgb_mcp_build_block( $block_name, $attrs );
	$block['innerHTML']    = tpgb_mcp_ppar_render_html( $attrs );
	$block['innerContent'] = array( $block['innerHTML'] );

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
