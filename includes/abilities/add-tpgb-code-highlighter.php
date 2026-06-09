<?php
/**
 * Ability: Add Nexter Blocks Code Highlighter (tpgb/tp-code-highlighter) to a Gutenberg page.
 *
 * @package The_Plus_Addons_For_Block_Editor
 * @since   1.3.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_register_ability(
	'nexter-blocks/add-tpgb-code-highlighter',
	array(
		'label'               => __( 'Add Nexter Blocks Code Highlighter', 'the-plus-addons-for-block-editor' ),
		'description'         => __( 'Adds the Nexter Blocks Code Highlighter block (tpgb/tp-code-highlighter) with PrismJS syntax highlighting, 40+ languages, 8 themes, copy/download buttons, line numbers, line highlighting, scrollbar customisation, and full style/animation/transform controls. This is a dynamic block.', 'the-plus-addons-for-block-editor' ),
		'category'            => 'nexter-blocks',

		'input_schema'        => array(
			'type'                 => 'object',
			'properties'           => array(

				/* ── Core ─────────────────────────────────────────────────── */
				'post_id'             => array(
					'type'        => 'integer',
					'description' => 'WordPress page/post ID to insert the block into.',
				),
				'position'            => array(
					'type'        => 'integer',
					'default'     => -1,
					'description' => 'Zero-based insert position. Use -1 to append.',
				),
				'parent_block_id'     => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Optional block_id of a parent container block. When provided, this block is inserted INSIDE the parent as an inner block instead of at the page top level.',
				),

				/* ── Source Code ──────────────────────────────────────────── */
				'languageType'        => array(
					'type'        => 'string',
					'description' => 'Programming language. Common values: markup (HTML), css, javascript, php, python, java, ruby, bash, json, sql, typescript, go, rust, c, cpp, csharp, swift, kotlin, dart, yaml, markdown, scss, less, jsx, tsx.',
					'default'     => 'markup',
				),
				'themeType'           => array(
					'type'        => 'string',
					'enum'        => array( 'prism-default', 'prism-coy', 'prism-dark', 'prism-funky', 'prism-okaidia', 'prism-solarizedlight', 'prism-tomorrownight', 'prism-twilight' ),
					'description' => 'Syntax highlighting colour theme.',
					'default'     => 'prism-default',
				),
				'sourceCode'          => array(
					'type'        => 'string',
					'description' => 'The raw source code to display. ALWAYS pass the exact code the user provides.',
					'default'     => '<h1>Welcome To Posimyth Innovation</h1>',
				),
				'alignment'           => array(
					'type'        => 'string',
					'enum'        => array( 'left', 'center', 'right', '' ),
					'description' => 'Code text alignment. Not available for prism-coy theme.',
					'default'     => 'left',
				),

				/* ── Options ──────────────────────────────────────────────── */
				'languageText'        => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Display label for the language (e.g. "HTML", "JavaScript"). Shown in toolbar.',
				),
				'copyText'            => array(
					'type'        => 'string',
					'default'     => 'Copy',
					'description' => 'Text on the copy button.',
				),
				'copyIconType'        => array(
					'type'        => 'string',
					'enum'        => array( 'none', 'icon' ),
					'default'     => 'none',
					'description' => 'Show icon on copy button.',
				),
				'copyIcon'            => array(
					'type'        => 'string',
					'default'     => 'far fa-copy',
					'description' => 'Font Awesome class for copy icon.',
				),
				'copiedText'          => array(
					'type'        => 'string',
					'default'     => 'Copied!',
					'description' => 'Text after successful copy.',
				),
				'copiedIconType'      => array(
					'type'    => 'string',
					'enum'    => array( 'none', 'icon' ),
					'default' => 'none',
				),
				'copiedIcon'          => array(
					'type'        => 'string',
					'default'     => 'far fa-copy',
					'description' => 'Font Awesome class for copied state icon.',
				),
				'copyErrorText'       => array(
					'type'        => 'string',
					'default'     => 'Error',
					'description' => 'Error message text.',
				),
				'lineNumber'          => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Show line numbers.',
				),
				'lineHighlight'       => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Lines to highlight. Format: "1,3,4-5".',
				),
				'downloadBtn'         => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Show download button.',
				),
				'downloadBtnText'     => array(
					'type'        => 'string',
					'default'     => 'Download',
					'description' => 'Download button text.',
				),
				'downloadIconType'    => array(
					'type'    => 'string',
					'enum'    => array( 'none', 'icon' ),
					'default' => 'none',
				),
				'downloadIcon'        => array(
					'type'        => 'string',
					'default'     => 'fas fa-download',
					'description' => 'Font Awesome class for download icon.',
				),
				'fileUrl'             => array(
					'type'        => 'string',
					'default'     => '#',
					'description' => 'File URL for download link.',
				),

				/* ── Source Code Styling ──────────────────────────────────── */
				'codePadding'         => array(
					'type'        => 'object',
					'description' => 'Code block padding {top,right,bottom,left,unit}.',
				),
				'codeMargin'          => array(
					'type'        => 'object',
					'description' => 'Code block margin {top,right,bottom,left,unit}.',
				),
				'codeMaxHeight'       => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Max height of code block in px.',
				),

				/* ── Scrollbar ────────────────────────────────────────────── */
				'scrollBarToggle'     => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Enable custom scrollbar styling.',
				),
				'scrollBarHeight'     => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Scrollbar height in px.',
				),
				'thumbBgColor'        => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Scrollbar thumb background colour.',
				),
				'trackBgColor'        => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Scrollbar track background colour.',
				),

				/* ── Line Number Styling ──────────────────────────────────── */
				'numberColor'         => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Line number text colour.',
				),
				'numberBdrColor'      => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Line number border colour.',
				),

				/* ── Language Text Styling ─────────────────────────────────── */
				'langTextColor'       => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Language label text colour (normal).',
				),
				'langTextHoverColor'  => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Language label text colour (hover).',
				),
				'langTextBgColor'     => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Language label background colour (normal).',
				),

				/* ── Copy/Download Button Styling ──────────────────────────── */
				'btnTextColor'        => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Copy/Download button text colour (normal).',
				),
				'btnTextHoverColor'   => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Copy/Download button text colour (hover).',
				),
				'btnIconColor'        => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Copy/Download button icon colour (normal).',
				),
				'btnIconHoverColor'   => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Copy/Download button icon colour (hover).',
				),
				'btnBgColor'          => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Copy/Download button background colour (normal).',
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

				/* ── Advanced: Visibility ────────────────────────────────── */
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

				/* ── Advanced: Identity ──────────────────────────────────── */
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

				/* ── Advanced: Layout ────────────────────────────────────── */
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

				/* ── Advanced: Transition ────────────────────────────────── */
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

				/* ── Global: Spacing ─────────────────────────────────────── */
				'globalMargin'        => array(
					'type'        => 'object',
					'description' => 'Outer margin {top,bottom,left,right,unit}.',
				),
				'globalPadding'       => array(
					'type'        => 'object',
					'description' => 'Inner padding {top,bottom,left,right,unit}.',
				),

				/* ── Global: Background ──────────────────────────────────── */
				'globalBgColor'       => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalBgHoverColor'  => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Global: Border ──────────────────────────────────────── */
				'globalBorder'        => array( 'type' => 'object' ),
				'globalBorderHover'   => array( 'type' => 'object' ),
				'globalBRadius'       => array( 'type' => 'object' ),
				'globalBRadiusHover'  => array( 'type' => 'object' ),
				'globalBShadow'       => array( 'type' => 'object' ),
				'globalBShadowHover'  => array( 'type' => 'object' ),

				/* ── Transform: Rotate ───────────────────────────────────── */
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

				/* ── Transform: Offset ───────────────────────────────────── */
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

				/* ── Transform: Scale ────────────────────────────────────── */
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

				/* ── Transform: Skew ─────────────────────────────────────── */
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

				/* ── Transform: Flip ─────────────────────────────────────── */
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

				/* ── Raw override ───────────────────────────────────────── */
				'settings'            => array(
					'type'        => 'object',
					'description' => 'Raw attribute overrides merged directly into the block.',
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

		'execute_callback'    => 'tpgb_mcp_add_code_highlighter_ability',
		'permission_callback' => 'tpgb_mcp_add_code_highlighter_permission',
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
 * Permission callback for the add-code-highlighter ability.
 *
 * @param array|null $input Ability input arguments.
 * @return bool True when the current user may insert the block.
 */
function tpgb_mcp_add_code_highlighter_permission( ?array $input = null ): bool {
	if ( ! current_user_can( 'edit_posts' ) ) {
		return false; }
	$post_id = absint( $input['post_id'] ?? 0 );
	if ( $post_id > 0 && ! current_user_can( 'edit_post', $post_id ) ) {
		return false; }
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
function tpgb_mcp_codeh_spacing( array $v ): array {
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
function tpgb_mcp_codeh_border( array $b ): array {
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
function tpgb_mcp_codeh_radius( array $r ): array {
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
function tpgb_mcp_codeh_bshadow( array $s ): array {
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
function tpgb_mcp_codeh_bg( string $color ): array {
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
function tpgb_mcp_codeh_needs_wrapper( array $attrs ): bool {
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
 * Execute callback: insert a tpgb/tp-code-highlighter block into a post.
 *
 * @param array $input Ability input arguments.
 * @return array|WP_Error Ability result or error on failure.
 */
function tpgb_mcp_add_code_highlighter_ability( array $input ) {

	if ( ! defined( 'TPGB_VERSION' ) && ! defined( 'TPGBP_VERSION' ) ) {
		return new WP_Error( 'nexter_blocks_missing', __( 'Nexter Blocks must be active.', 'the-plus-addons-for-block-editor' ) );
	}

	$block_name = 'tpgb/tp-code-highlighter';

	if ( ! tpgb_mcp_has_registered_block( $block_name ) ) {
		return new WP_Error( 'block_missing', __( 'tpgb/tp-code-highlighter is not registered.', 'the-plus-addons-for-block-editor' ) );
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
	// Build attributes (only non-defaults).
	// ---------------------------------------------------------------------
	$block_id = tpgb_mcp_generate_block_id();
	$attrs    = array( 'block_id' => $block_id );

	/* ── Source Code ──────────────────────────────────────────────────── */
	if ( ! empty( $input['languageType'] ) && 'markup' !== $input['languageType'] ) {
		$attrs['languageType'] = sanitize_text_field( $input['languageType'] );
	}
	if ( ! empty( $input['themeType'] ) && 'prism-default' !== $input['themeType'] ) {
		$attrs['themeType'] = sanitize_text_field( $input['themeType'] );
	}
	if ( isset( $input['sourceCode'] ) && '<h1>Welcome To Posimyth Innovation</h1>' !== $input['sourceCode'] ) {
		$attrs['sourceCode'] = $input['sourceCode']; // Raw code, not sanitized to preserve syntax.
	}
	if ( ! empty( $input['alignment'] ) && 'left' !== $input['alignment'] ) {
		$attrs['Alignment'] = array(
			'md' => sanitize_text_field( $input['alignment'] ),
			'sm' => '',
			'xs' => '',
		);
	}

	/* ── Options ──────────────────────────────────────────────────────── */
	if ( ! empty( $input['languageText'] ) ) {
		$attrs['languageText'] = sanitize_text_field( $input['languageText'] ); }
	if ( isset( $input['copyText'] ) && 'Copy' !== $input['copyText'] ) {
		$attrs['copyText'] = sanitize_text_field( $input['copyText'] ); }
	if ( ! empty( $input['copyIconType'] ) && 'none' !== $input['copyIconType'] ) {
		$attrs['copyIcnType'] = 'icon';
		if ( ! empty( $input['copyIcon'] ) && 'far fa-copy' !== $input['copyIcon'] ) {
			$attrs['copyIconStore'] = sanitize_text_field( $input['copyIcon'] ); }
	}
	if ( isset( $input['copiedText'] ) && 'Copied!' !== $input['copiedText'] ) {
		$attrs['copiedText'] = sanitize_text_field( $input['copiedText'] ); }
	if ( ! empty( $input['copiedIconType'] ) && 'none' !== $input['copiedIconType'] ) {
		$attrs['copiedIcnType'] = 'icon';
		if ( ! empty( $input['copiedIcon'] ) && 'far fa-copy' !== $input['copiedIcon'] ) {
			$attrs['copiedIconStore'] = sanitize_text_field( $input['copiedIcon'] ); }
	}
	if ( isset( $input['copyErrorText'] ) && 'Error' !== $input['copyErrorText'] ) {
		$attrs['copyErrorText'] = sanitize_text_field( $input['copyErrorText'] ); }
	if ( ! empty( $input['lineNumber'] ) ) {
		$attrs['lineNumber'] = true; }
	if ( ! empty( $input['lineHighlight'] ) ) {
		$attrs['lineHighlight'] = sanitize_text_field( $input['lineHighlight'] ); }
	if ( ! empty( $input['downloadBtn'] ) ) {
		$attrs['dnloadBtn'] = true;
		if ( isset( $input['downloadBtnText'] ) && 'Download' !== $input['downloadBtnText'] ) {
			$attrs['dwnldBtnText'] = sanitize_text_field( $input['downloadBtnText'] ); }
		if ( ! empty( $input['downloadIconType'] ) && 'none' !== $input['downloadIconType'] ) {
			$attrs['dwnldIcnType'] = 'icon';
			if ( ! empty( $input['downloadIcon'] ) && 'fas fa-download' !== $input['downloadIcon'] ) {
				$attrs['dwnldIconStore'] = sanitize_text_field( $input['downloadIcon'] ); }
		}
		if ( ! empty( $input['fileUrl'] ) && '#' !== $input['fileUrl'] ) {
			$attrs['fileLink'] = array(
				'url'      => esc_url_raw( $input['fileUrl'] ),
				'target'   => '',
				'nofollow' => '',
			);
		}
	}

	/* ── Source Code Styling ───────────────────────────────────────────── */
	if ( ! empty( $input['codePadding'] ) ) {
		$attrs['scodePadding'] = tpgb_mcp_codeh_spacing( $input['codePadding'] ); }
	if ( ! empty( $input['codeMargin'] ) ) {
		$attrs['scodeMargin'] = tpgb_mcp_codeh_spacing( $input['codeMargin'] );  }
	if ( ! empty( $input['codeMaxHeight'] ) ) {
		$attrs['scodeHeight'] = array(
			'md'   => (string) absint( $input['codeMaxHeight'] ),
			'unit' => 'px',
		); }

	/* ── Scrollbar ────────────────────────────────────────────────────── */
	if ( ! empty( $input['scrollBarToggle'] ) ) {
		$attrs['scrollBarTgl'] = true;
		if ( ! empty( $input['scrollBarHeight'] ) ) {
			$attrs['scrollBarHeight'] = array(
				'md'   => (string) absint( $input['scrollBarHeight'] ),
				'unit' => 'px',
			); }
		if ( ! empty( $input['thumbBgColor'] ) ) {
			$attrs['thumbBG'] = tpgb_mcp_codeh_bg( $input['thumbBgColor'] ); }
		if ( ! empty( $input['trackBgColor'] ) ) {
			$attrs['trackBG'] = tpgb_mcp_codeh_bg( $input['trackBgColor'] ); }
	}

	/* ── Line Number Styling ──────────────────────────────────────────── */
	if ( ! empty( $input['numberColor'] ) ) {
		$attrs['numberColor'] = sanitize_text_field( $input['numberColor'] ); }
	if ( ! empty( $input['numberBdrColor'] ) ) {
		$attrs['bdrColor'] = sanitize_text_field( $input['numberBdrColor'] ); }

	/* ── Language Text Styling ─────────────────────────────────────────── */
	if ( ! empty( $input['langTextColor'] ) ) {
		$attrs['langTextNColor'] = sanitize_text_field( $input['langTextColor'] ); }
	if ( ! empty( $input['langTextHoverColor'] ) ) {
		$attrs['langTextHColor'] = sanitize_text_field( $input['langTextHoverColor'] ); }
	if ( ! empty( $input['langTextBgColor'] ) ) {
		$attrs['langTextNBG'] = tpgb_mcp_codeh_bg( $input['langTextBgColor'] ); }

	/* ── Copy/Download Button Styling ──────────────────────────────────── */
	if ( ! empty( $input['btnTextColor'] ) ) {
		$attrs['copyDwlBtnNColor'] = sanitize_text_field( $input['btnTextColor'] ); }
	if ( ! empty( $input['btnTextHoverColor'] ) ) {
		$attrs['copyDwlBtnHColor'] = sanitize_text_field( $input['btnTextHoverColor'] ); }
	if ( ! empty( $input['btnIconColor'] ) ) {
		$attrs['copyDwlIconNColor'] = sanitize_text_field( $input['btnIconColor'] ); }
	if ( ! empty( $input['btnIconHoverColor'] ) ) {
		$attrs['copyDwlIconHColor'] = sanitize_text_field( $input['btnIconHoverColor'] ); }
	if ( ! empty( $input['btnBgColor'] ) ) {
		$attrs['copyDwlBtnNmlBG'] = tpgb_mcp_codeh_bg( $input['btnBgColor'] ); }

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
		$attrs['globalMargin'] = tpgb_mcp_codeh_spacing( $input['globalMargin'] );  }
	if ( ! empty( $input['globalPadding'] ) ) {
		$attrs['globalPadding'] = tpgb_mcp_codeh_spacing( $input['globalPadding'] ); }

	/* ── Global: Background ───────────────────────────────────────────── */
	if ( ! empty( $input['globalBgColor'] ) ) {
		$attrs['globalBg'] = tpgb_mcp_codeh_bg( $input['globalBgColor'] );      }
	if ( ! empty( $input['globalBgHoverColor'] ) ) {
		$attrs['globalBgHover'] = tpgb_mcp_codeh_bg( $input['globalBgHoverColor'] ); }

	/* ── Global: Border ───────────────────────────────────────────────── */
	if ( ! empty( $input['globalBorder'] ) ) {
		$attrs['globalBorder'] = tpgb_mcp_codeh_border( $input['globalBorder'] );      }
	if ( ! empty( $input['globalBorderHover'] ) ) {
		$attrs['globalBorderHover'] = tpgb_mcp_codeh_border( $input['globalBorderHover'] ); }
	if ( ! empty( $input['globalBRadius'] ) ) {
		$attrs['globalBRadius'] = tpgb_mcp_codeh_radius( $input['globalBRadius'] );      }
	if ( ! empty( $input['globalBRadiusHover'] ) ) {
		$attrs['globalBRadiusHover'] = tpgb_mcp_codeh_radius( $input['globalBRadiusHover'] ); }
	if ( ! empty( $input['globalBShadow'] ) ) {
		$attrs['globalBShadow'] = tpgb_mcp_codeh_bshadow( $input['globalBShadow'] );      }
	if ( ! empty( $input['globalBShadowHover'] ) ) {
		$attrs['globalBShadowHover'] = tpgb_mcp_codeh_bshadow( $input['globalBShadowHover'] ); }

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
	if ( tpgb_mcp_codeh_needs_wrapper( $attrs ) ) {
		$attrs['tpgbDisrule'] = true; }

	/* ── Raw settings override ────────────────────────────────────────── */
	$attrs = tpgb_mcp_merge_block_settings( $attrs, $input['settings'] ?? array() );

	/* ── Build, insert, save (dynamic block) ──────────────────────────── */
	$block     = tpgb_mcp_build_block( $block_name, $attrs );
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
