<?php
/**
 * Ability: Add Nexter Blocks Dark Mode (tpgb/tp-dark-mode) to a Gutenberg page.
 *
 * @package The_Plus_Addons_For_Block_Editor
 * @since   1.3.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_register_ability(
	'nexter-blocks/add-tpgb-dark-mode',
	array(
		'label'               => __( 'Add Nexter Blocks Dark Mode', 'the-plus-addons-for-block-editor' ),
		'description'         => __( 'Adds the Nexter Blocks Dark Mode block (tpgb/tp-dark-mode) — a light/dark theme toggle switcher with 3 style presets, icon customisation (light/dark icons), cookie persistence, OS theme matching, position control (relative/absolute/fixed), and full styling controls for switch track, dot, and before/after text labels. This is a dynamic block.', 'the-plus-addons-for-block-editor' ),
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

				/* ── Style ────────────────────────────────────────────────── */
				'style'               => array(
					'type'        => 'string',
					'enum'        => array( 'style-1', 'style-2', 'style-3' ),
					'description' => 'Visual style. style-1 = switch with icons; style-2 = icon-only toggle; style-3 = switch with text labels.',
					'default'     => 'style-1',
				),

				/* ── Icons ────────────────────────────────────────────────── */
				'iconType'            => array(
					'type'        => 'string',
					'enum'        => array( 'icon', 'none' ),
					'description' => 'Icon display mode for style-2.',
					'default'     => 'icon',
				),
				'lightIcon'           => array(
					'type'        => 'string',
					'default'     => 'fas fa-sun',
					'description' => 'Font Awesome class for light mode icon.',
				),
				'darkIconEnable'      => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Enable a different icon for dark mode.',
				),
				'darkIcon'            => array(
					'type'        => 'string',
					'default'     => 'fas fa-moon',
					'description' => 'Font Awesome class for dark mode icon.',
				),

				/* ── Behaviour ────────────────────────────────────────────── */
				'saveCookies'         => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Persist the user\'s theme choice in a cookie.',
				),
				'matchOsTheme'        => array(
					'type'        => 'boolean',
					'default'     => true,
					'description' => 'Auto-match the user\'s OS preference (prefers-color-scheme).',
				),

				/* ── Position ─────────────────────────────────────────────── */
				'displayPosition'     => array(
					'type'        => 'string',
					'enum'        => array( 'relative', 'absolute', 'fixed' ),
					'description' => 'CSS position of the toggle.',
					'default'     => 'relative',
				),
				'alignment'           => array(
					'type'        => 'string',
					'enum'        => array( 'left', 'center', 'right', '' ),
					'description' => 'Horizontal alignment (when position is relative).',
					'default'     => 'center',
				),
				'absoluteOffset'      => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Absolute position offset in %.',
				),
				'fixedPos'            => array(
					'type'        => 'string',
					'enum'        => array( 'left-top', 'right-top', 'left-bottom', 'right-bottom' ),
					'description' => 'Fixed position corner.',
					'default'     => 'left-top',
				),
				'rightOffset'         => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Right offset in % (for fixed/absolute).',
				),
				'bottomOffset'        => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Bottom offset in % (for fixed/absolute).',
				),

				/* ── Size ─────────────────────────────────────────────────── */
				'switchSize'          => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Switch track size in px (style-1, style-3).',
				),
				'iconSize'            => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Icon size in px (style-2).',
				),
				'bgSize'              => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Background size in px (style-3).',
				),

				/* ── Icon colours ─────────────────────────────────────────── */
				'iconLightColor'      => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Icon colour in light mode.',
				),
				'iconDarkColor'       => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Icon colour in dark mode.',
				),

				/* ── Dot (toggle handle) styling ─────────────────────────── */
				'dotLightBgColor'     => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Dot/handle background colour (light mode).',
				),
				'dotDarkBgColor'      => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Dot/handle background colour (dark mode).',
				),

				/* ── Switch (track) styling ──────────────────────────────── */
				'switchLightBgColor'  => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Switch track background colour (light mode).',
				),
				'switchDarkBgColor'   => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Switch track background colour (dark mode).',
				),

				/* ── Before/After text labels (style-3) ───────────────────── */
				'beforeText'          => array(
					'type'        => 'string',
					'default'     => 'Normal',
					'description' => 'Label text shown before the switch (style-3).',
				),
				'beforeColor'         => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Before text colour.',
				),
				'beforeOffset'        => array(
					'type'        => 'integer',
					'default'     => -63,
					'description' => 'Before text offset in px.',
				),
				'afterText'           => array(
					'type'        => 'string',
					'default'     => 'Dark',
					'description' => 'Label text shown after the switch (style-3).',
				),
				'afterColor'          => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'After text colour.',
				),
				'afterOffset'         => array(
					'type'        => 'integer',
					'default'     => -45,
					'description' => 'After text offset in px.',
				),

				/* ── Typography ───────────────────────────────────────────── */
				'enableBeforeTypo'    => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'beforeTypoSize'      => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'enableAfterTypo'     => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'afterTypoSize'       => array(
					'type'    => 'integer',
					'default' => 0,
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

				/* ── Advanced ─────────────────────────────────────────────── */
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

				/* ── Transforms ──────────────────────────────────────────── */
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

				'settings'            => array(
					'type'        => 'object',
					'description' => 'Raw attribute overrides merged directly into the block.',
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

		'execute_callback'    => 'tpgb_mcp_add_dark_mode_ability',
		'permission_callback' => 'tpgb_mcp_add_dark_mode_permission',
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
 * Permission callback for the add-dark-mode ability.
 *
 * @param array|null $input Ability input arguments.
 * @return bool True when the current user may insert the block.
 */
function tpgb_mcp_add_dark_mode_permission( ?array $input = null ): bool {
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
function tpgb_mcp_dm_spacing( array $v ): array {
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
function tpgb_mcp_dm_border( array $b ): array {
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
function tpgb_mcp_dm_radius( array $r ): array {
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
function tpgb_mcp_dm_bshadow( array $s ): array {
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
function tpgb_mcp_dm_bg( string $color ): array {
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
function tpgb_mcp_dm_needs_wrapper( array $attrs ): bool {
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
 * Execute callback: insert a tpgb/tp-dark-mode block into a post.
 *
 * @param array $input Ability input arguments.
 * @return array|WP_Error Ability result or error on failure.
 */
function tpgb_mcp_add_dark_mode_ability( array $input ) {

	if ( ! defined( 'TPGB_VERSION' ) && ! defined( 'TPGBP_VERSION' ) ) {
		return new WP_Error( 'nexter_blocks_missing', __( 'Nexter Blocks must be active.', 'the-plus-addons-for-block-editor' ) );
	}

	$block_name = 'tpgb/tp-dark-mode';
	if ( ! tpgb_mcp_has_registered_block( $block_name ) ) {
		return new WP_Error( 'block_missing', __( 'tpgb/tp-dark-mode is not registered.', 'the-plus-addons-for-block-editor' ) );
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

	/* ── Style ────────────────────────────────────────────────────────── */
	$style = sanitize_key( $input['style'] ?? 'style-1' );
	if ( 'style-1' !== $style ) {
		$attrs['dmStyle'] = $style; }

	/* ── Icons ────────────────────────────────────────────────────────── */
	if ( ! empty( $input['iconType'] ) && 'icon' !== $input['iconType'] ) {
		$attrs['S2IconType'] = sanitize_key( $input['iconType'] ); }
	if ( ! empty( $input['lightIcon'] ) && 'fas fa-sun' !== $input['lightIcon'] ) {
		$attrs['IconName'] = sanitize_text_field( $input['lightIcon'] );
	}
	if ( ! empty( $input['darkIconEnable'] ) ) {
		$attrs['darkIconEn'] = true;
		if ( ! empty( $input['darkIcon'] ) && 'fas fa-moon' !== $input['darkIcon'] ) {
			$attrs['darkIcon'] = sanitize_text_field( $input['darkIcon'] );
		}
	}

	/* ── Behaviour ────────────────────────────────────────────────────── */
	if ( ! empty( $input['saveCookies'] ) ) {
		$attrs['saveCookies'] = true; }
	if ( isset( $input['matchOsTheme'] ) && ! $input['matchOsTheme'] ) {
		$attrs['matchOsTheme'] = false; }

	/* ── Position ─────────────────────────────────────────────────────── */
	if ( ! empty( $input['displayPosition'] ) && 'relative' !== $input['displayPosition'] ) {
		$attrs['dmPosition'] = sanitize_key( $input['displayPosition'] );
	}
	if ( ! empty( $input['alignment'] ) && 'center' !== $input['alignment'] ) {
		$attrs['Alignment'] = array(
			'md' => sanitize_text_field( $input['alignment'] ),
			'sm' => '',
			'xs' => '',
		);
	}
	if ( ! empty( $input['absoluteOffset'] ) ) {
		$attrs['absoluteOff'] = array(
			'md'   => (string) intval( $input['absoluteOffset'] ),
			'unit' => '%',
		); }
	if ( ! empty( $input['fixedPos'] ) && 'left-top' !== $input['fixedPos'] ) {
		$attrs['fixedPos'] = sanitize_text_field( $input['fixedPos'] ); }
	if ( ! empty( $input['rightOffset'] ) ) {
		$attrs['dmRightOf'] = array(
			'md'   => (string) intval( $input['rightOffset'] ),
			'unit' => '%',
		); }
	if ( ! empty( $input['bottomOffset'] ) ) {
		$attrs['dmBottomOf'] = array(
			'md'   => (string) intval( $input['bottomOffset'] ),
			'unit' => '%',
		); }

	/* ── Size ─────────────────────────────────────────────────────────── */
	if ( ! empty( $input['switchSize'] ) ) {
		$attrs['switchSize'] = array(
			'md'   => (string) absint( $input['switchSize'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['iconSize'] ) ) {
		$attrs['icons2Size'] = array(
			'md'   => (string) absint( $input['iconSize'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['bgSize'] ) ) {
		$attrs['bgs3Size'] = array(
			'md'   => (string) absint( $input['bgSize'] ),
			'unit' => 'px',
		); }

	/* ── Icon colours ─────────────────────────────────────────────────── */
	if ( ! empty( $input['iconLightColor'] ) ) {
		$attrs['iconLgtColor'] = sanitize_text_field( $input['iconLightColor'] ); }
	if ( ! empty( $input['iconDarkColor'] ) ) {
		$attrs['iconDarkColor'] = sanitize_text_field( $input['iconDarkColor'] );  }

	/* ── Dot / Switch backgrounds ─────────────────────────────────────── */
	if ( ! empty( $input['dotLightBgColor'] ) ) {
		$attrs['dotLgtBG'] = tpgb_mcp_dm_bg( $input['dotLightBgColor'] );    }
	if ( ! empty( $input['dotDarkBgColor'] ) ) {
		$attrs['dotDarkBG'] = tpgb_mcp_dm_bg( $input['dotDarkBgColor'] );     }
	if ( ! empty( $input['switchLightBgColor'] ) ) {
		$attrs['switchLgtBG'] = tpgb_mcp_dm_bg( $input['switchLightBgColor'] ); }
	if ( ! empty( $input['switchDarkBgColor'] ) ) {
		$attrs['switchDarkBG'] = tpgb_mcp_dm_bg( $input['switchDarkBgColor'] ); }

	/* ── Before/After text (style-3) ──────────────────────────────────── */
	if ( ! empty( $input['beforeText'] ) && 'Normal' !== $input['beforeText'] ) {
		$attrs['beforeText'] = sanitize_text_field( $input['beforeText'] ); }
	if ( ! empty( $input['beforeColor'] ) ) {
		$attrs['beforeColor'] = sanitize_text_field( $input['beforeColor'] ); }
	if ( isset( $input['beforeOffset'] ) && -63 !== (int) $input['beforeOffset'] ) {
		$attrs['beforeOffset'] = array(
			'md'   => (string) intval( $input['beforeOffset'] ),
			'unit' => 'px',
		);
	}
	if ( ! empty( $input['afterText'] ) && 'Dark' !== $input['afterText'] ) {
		$attrs['afterText'] = sanitize_text_field( $input['afterText'] ); }
	if ( ! empty( $input['afterColor'] ) ) {
		$attrs['afterColor'] = sanitize_text_field( $input['afterColor'] ); }
	if ( isset( $input['afterOffset'] ) && -45 !== (int) $input['afterOffset'] ) {
		$attrs['afterOffset'] = array(
			'md'   => (string) intval( $input['afterOffset'] ),
			'unit' => 'px',
		);
	}

	/* ── Typography ───────────────────────────────────────────────────── */
	if ( ! empty( $input['enableBeforeTypo'] ) ) {
		$attrs['beforeTypo'] = array(
			'openTypography' => 1,
			'size'           => array(
				'md'   => ! empty( $input['beforeTypoSize'] ) ? (string) absint( $input['beforeTypoSize'] ) : '',
				'unit' => 'px',
			),
			'height'         => '',
			'spacing'        => '',
			'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
		);
	}
	if ( ! empty( $input['enableAfterTypo'] ) ) {
		$attrs['afterTypo'] = array(
			'openTypography' => 1,
			'size'           => array(
				'md'   => ! empty( $input['afterTypoSize'] ) ? (string) absint( $input['afterTypoSize'] ) : '',
				'unit' => 'px',
			),
			'height'         => '',
			'spacing'        => '',
			'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
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
		$attrs['globalMargin'] = tpgb_mcp_dm_spacing( $input['globalMargin'] );  }
	if ( ! empty( $input['globalPadding'] ) ) {
		$attrs['globalPadding'] = tpgb_mcp_dm_spacing( $input['globalPadding'] ); }
	if ( ! empty( $input['globalBgColor'] ) ) {
		$attrs['globalBg'] = tpgb_mcp_dm_bg( $input['globalBgColor'] );      }
	if ( ! empty( $input['globalBgHoverColor'] ) ) {
		$attrs['globalBgHover'] = tpgb_mcp_dm_bg( $input['globalBgHoverColor'] ); }
	if ( ! empty( $input['globalBorder'] ) ) {
		$attrs['globalBorder'] = tpgb_mcp_dm_border( $input['globalBorder'] );       }
	if ( ! empty( $input['globalBorderHover'] ) ) {
		$attrs['globalBorderHover'] = tpgb_mcp_dm_border( $input['globalBorderHover'] );  }
	if ( ! empty( $input['globalBRadius'] ) ) {
		$attrs['globalBRadius'] = tpgb_mcp_dm_radius( $input['globalBRadius'] );      }
	if ( ! empty( $input['globalBRadiusHover'] ) ) {
		$attrs['globalBRadiusHover'] = tpgb_mcp_dm_radius( $input['globalBRadiusHover'] ); }
	if ( ! empty( $input['globalBShadow'] ) ) {
		$attrs['globalBShadow'] = tpgb_mcp_dm_bshadow( $input['globalBShadow'] );      }
	if ( ! empty( $input['globalBShadowHover'] ) ) {
		$attrs['globalBShadowHover'] = tpgb_mcp_dm_bshadow( $input['globalBShadowHover'] ); }

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
	if ( tpgb_mcp_dm_needs_wrapper( $attrs ) ) {
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
