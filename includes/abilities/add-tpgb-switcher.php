<?php
/**
 * Ability: Add Nexter Blocks Switcher (tpgb/tp-switcher) to a Gutenberg page.
 *
 * @package The_Plus_Addons_For_Block_Editor
 * @since   1.3.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_register_ability(
	'nexter-blocks/add-tpgb-switcher',
	array(
		'label'               => __( 'Add Nexter Blocks Switcher', 'the-plus-addons-for-block-editor' ),
		'description'         => __( 'Adds the Nexter Blocks Switcher block (tpgb/tp-switcher) — a 2-state toggle that swaps between two content panels. Each panel can be plain text/HTML (`source = content`) or a saved Gutenberg template (`source = editor`). Comes with 2 visual styles, optional FA icons on each label, alignment, label spacing, toggle size, switch-track and indicator colours (active/inactive), label typography & colours per state, description typography & colours per state, and shadow on the indicator. This is a dynamic block.', 'the-plus-addons-for-block-editor' ),
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

				/* ── Switch labels & content ──────────────────────────────── */
				'title1'              => array(
					'type'        => 'string',
					'default'     => 'Switch 1',
					'description' => 'Label for the first toggle state.',
				),
				'source1'             => array(
					'type'        => 'string',
					'enum'        => array( 'content', 'editor' ),
					'default'     => 'content',
					'description' => 'content = inline text in `desc1`; editor = use `editor` content area (rendered when block has inner blocks).',
				),
				'desc1'               => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Description text for state 1 (when source1 = content).',
				),
				'title2'              => array(
					'type'    => 'string',
					'default' => 'Switch 2',
				),
				'source2'             => array(
					'type'    => 'string',
					'enum'    => array( 'content', 'editor' ),
					'default' => 'content',
				),
				'desc2'               => array(
					'type'    => 'string',
					'default' => '',
				),
				'showButton'          => array(
					'type'        => 'boolean',
					'default'     => true,
					'description' => 'Show the toggle/switch button.',
				),

				/* ── Style ────────────────────────────────────────────────── */
				'style'               => array(
					'type'        => 'string',
					'enum'        => array( 'style-1', 'style-2', 'style-3' ),
					'description' => 'Visual style preset.',
					'default'     => 'style-1',
				),
				'alignment'           => array(
					'type'    => 'string',
					'enum'    => array( 'text-left', 'text-center', 'text-right' ),
					'default' => 'text-left',
				),

				/* ── Switch sizing ────────────────────────────────────────── */
				'labelSpacing'        => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Spacing between labels and switch (px).',
				),
				'toggleSize'          => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Switch button font-size (px).',
				),

				/* ── Switch colours ───────────────────────────────────────── */
				'switchHandleColor'   => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Switch indicator (handle) colour.',
				),
				'switchBgColor'       => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Switch track background colour (state 1).',
				),
				'switchBgColorActive' => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Switch track background colour (state 2 / active).',
				),
				'switchShadow'        => array(
					'type'        => 'object',
					'description' => 'Switch indicator shadow.',
				),

				/* ── Label colours ────────────────────────────────────────── */
				'labelColor'          => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Inactive label colour.',
				),
				'labelColorActive'    => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Active label colour.',
				),

				/* ── Label typography ─────────────────────────────────────── */
				'label1TypoSize'      => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'label2TypoSize'      => array(
					'type'    => 'integer',
					'default' => 0,
				),

				/* ── Description ──────────────────────────────────────────── */
				'desc1Color'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'desc1TypoSize'       => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'desc2Color'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'desc2TypoSize'       => array(
					'type'    => 'integer',
					'default' => 0,
				),

				/* ── Label icons ──────────────────────────────────────────── */
				'enableIcons'         => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'icon1'               => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'FA class for label 1 icon (only used by raw template; surfaces via `settings`).',
				),
				'icon2'               => array(
					'type'    => 'string',
					'default' => '',
				),
				'iconSize'            => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'iconSpacing'         => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Margin-right between icon and label (px).',
				),
				'iconColorInactive'   => array(
					'type'    => 'string',
					'default' => '',
				),
				'iconColorActive'     => array(
					'type'    => 'string',
					'default' => '',
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

		'execute_callback'    => 'tpgb_mcp_add_switcher_ability',
		'permission_callback' => 'tpgb_mcp_add_switcher_permission',
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
 * Permission callback for the add-switcher ability.
 *
 * @param array|null $input Ability input arguments.
 * @return bool True when the current user may insert the block.
 */
function tpgb_mcp_add_switcher_permission( ?array $input = null ): bool {
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
function tpgb_mcp_swt_spacing( array $v ): array {
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
function tpgb_mcp_swt_border( array $b ): array {
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
function tpgb_mcp_swt_radius( array $r ): array {
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
function tpgb_mcp_swt_bshadow( array $s ): array {
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
function tpgb_mcp_swt_bg( string $color ): array {
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
function tpgb_mcp_swt_typo( int $size ): array {
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
function tpgb_mcp_swt_needs_wrapper( array $attrs ): bool {
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
 * Execute callback for the add-switcher ability.
 *
 * @param array $input Ability input arguments.
 * @return array|WP_Error Result payload or WP_Error on failure.
 */
function tpgb_mcp_add_switcher_ability( array $input ) {

	if ( ! defined( 'TPGB_VERSION' ) && ! defined( 'TPGBP_VERSION' ) ) {
		return new WP_Error( 'nexter_blocks_missing', __( 'Nexter Blocks must be active.', 'the-plus-addons-for-block-editor' ) );
	}

	$block_name = 'tpgb/tp-switcher';
	if ( ! tpgb_mcp_has_registered_block( $block_name ) ) {
		return new WP_Error( 'block_missing', __( 'tpgb/tp-switcher is not registered.', 'the-plus-addons-for-block-editor' ) );
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

	/* ── Labels & content ─────────────────────────────────────────────── */
	if ( ! empty( $input['title1'] ) && 'Switch 1' !== $input['title1'] ) {
		$attrs['title1'] = sanitize_text_field( $input['title1'] ); }
	if ( ! empty( $input['title2'] ) && 'Switch 2' !== $input['title2'] ) {
		$attrs['title2'] = sanitize_text_field( $input['title2'] ); }
	if ( ! empty( $input['source1'] ) && 'content' !== $input['source1'] ) {
		$attrs['source1'] = sanitize_key( $input['source1'] ); }
	if ( ! empty( $input['source2'] ) && 'content' !== $input['source2'] ) {
		$attrs['source2'] = sanitize_key( $input['source2'] ); }
	if ( isset( $input['desc1'] ) ) {
		$attrs['desc1'] = tpgb_mcp_clean_text( $input['desc1'] ); }
	if ( isset( $input['desc2'] ) ) {
		$attrs['desc2'] = tpgb_mcp_clean_text( $input['desc2'] ); }
	if ( isset( $input['showButton'] ) && false === $input['showButton'] ) {
		$attrs['showBtn'] = false; }

	/* ── Style ────────────────────────────────────────────────────────── */
	if ( ! empty( $input['style'] ) && 'style-1' !== $input['style'] ) {
		$attrs['switchStyle'] = sanitize_text_field( $input['style'] ); }
	if ( ! empty( $input['alignment'] ) && 'text-left' !== $input['alignment'] ) {
		$attrs['switchalign'] = sanitize_text_field( $input['alignment'] ); }

	/* ── Sizing ───────────────────────────────────────────────────────── */
	if ( ! empty( $input['labelSpacing'] ) ) {
		$attrs['labSpacebet'] = array(
			'md'   => (string) absint( $input['labelSpacing'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['toggleSize'] ) ) {
		$attrs['toggleSize'] = array(
			'md'   => (string) absint( $input['toggleSize'] ),
			'unit' => 'px',
		); }

	/* ── Switch colours ───────────────────────────────────────────────── */
	if ( ! empty( $input['switchHandleColor'] ) ) {
		$attrs['switchColor'] = sanitize_text_field( $input['switchHandleColor'] ); }
	if ( ! empty( $input['switchBgColor'] ) ) {
		$attrs['swichBgcolor'] = sanitize_text_field( $input['switchBgColor'] ); }
	if ( ! empty( $input['switchBgColorActive'] ) ) {
		$attrs['ActswichBgcolor'] = sanitize_text_field( $input['switchBgColorActive'] ); }
	if ( ! empty( $input['switchShadow'] ) ) {
		$attrs['switchBshadow'] = tpgb_mcp_swt_bshadow( $input['switchShadow'] ); }

	/* ── Label colours ────────────────────────────────────────────────── */
	if ( ! empty( $input['labelColor'] ) ) {
		$attrs['labelColor'] = sanitize_text_field( $input['labelColor'] ); }
	if ( ! empty( $input['labelColorActive'] ) ) {
		$attrs['ActlabelColor'] = sanitize_text_field( $input['labelColorActive'] ); }

	/* ── Label typography ─────────────────────────────────────────────── */
	if ( ! empty( $input['label1TypoSize'] ) ) {
		$attrs['label1Typo'] = tpgb_mcp_swt_typo( (int) $input['label1TypoSize'] ); }
	if ( ! empty( $input['label2TypoSize'] ) ) {
		$attrs['label2Typo'] = tpgb_mcp_swt_typo( (int) $input['label2TypoSize'] ); }

	/* ── Description ──────────────────────────────────────────────────── */
	if ( ! empty( $input['desc1Color'] ) ) {
		$attrs['desc1Color'] = sanitize_text_field( $input['desc1Color'] ); }
	if ( ! empty( $input['desc1TypoSize'] ) ) {
		$attrs['desc1Typo'] = tpgb_mcp_swt_typo( (int) $input['desc1TypoSize'] ); }
	if ( ! empty( $input['desc2Color'] ) ) {
		$attrs['desc2Color'] = sanitize_text_field( $input['desc2Color'] ); }
	if ( ! empty( $input['desc2TypoSize'] ) ) {
		$attrs['desc2Typo'] = tpgb_mcp_swt_typo( (int) $input['desc2TypoSize'] ); }

	/* ── Icons ────────────────────────────────────────────────────────── */
	if ( ! empty( $input['enableIcons'] ) ) {
		$attrs['lblIcon'] = true;
		if ( ! empty( $input['icon1'] ) ) {
			$attrs['icon1'] = sanitize_text_field( $input['icon1'] ); }
		if ( ! empty( $input['icon2'] ) ) {
			$attrs['icon2'] = sanitize_text_field( $input['icon2'] ); }
		if ( ! empty( $input['iconSize'] ) ) {
			$attrs['swiIconSize'] = array(
				'md'   => (string) absint( $input['iconSize'] ),
				'unit' => 'px',
			); }
		if ( ! empty( $input['iconSpacing'] ) ) {
			$attrs['swiIconSpac'] = array(
				'md'   => (string) absint( $input['iconSpacing'] ),
				'unit' => 'px',
			); }
		if ( ! empty( $input['iconColorInactive'] ) ) {
			$attrs['iconNcolor'] = sanitize_text_field( $input['iconColorInactive'] ); }
		if ( ! empty( $input['iconColorActive'] ) ) {
			$attrs['iconHvrcolor'] = sanitize_text_field( $input['iconColorActive'] ); }
	}

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
		$attrs['globalMargin'] = tpgb_mcp_swt_spacing( $input['globalMargin'] ); }
	if ( ! empty( $input['globalPadding'] ) ) {
		$attrs['globalPadding'] = tpgb_mcp_swt_spacing( $input['globalPadding'] ); }
	if ( ! empty( $input['globalBgColor'] ) ) {
		$attrs['globalBg'] = tpgb_mcp_swt_bg( $input['globalBgColor'] ); }
	if ( ! empty( $input['globalBgHoverColor'] ) ) {
		$attrs['globalBgHover'] = tpgb_mcp_swt_bg( $input['globalBgHoverColor'] ); }
	if ( ! empty( $input['globalBorder'] ) ) {
		$attrs['globalBorder'] = tpgb_mcp_swt_border( $input['globalBorder'] ); }
	if ( ! empty( $input['globalBorderHover'] ) ) {
		$attrs['globalBorderHover'] = tpgb_mcp_swt_border( $input['globalBorderHover'] ); }
	if ( ! empty( $input['globalBRadius'] ) ) {
		$attrs['globalBRadius'] = tpgb_mcp_swt_radius( $input['globalBRadius'] ); }
	if ( ! empty( $input['globalBRadiusHover'] ) ) {
		$attrs['globalBRadiusHover'] = tpgb_mcp_swt_radius( $input['globalBRadiusHover'] ); }
	if ( ! empty( $input['globalBShadow'] ) ) {
		$attrs['globalBShadow'] = tpgb_mcp_swt_bshadow( $input['globalBShadow'] ); }
	if ( ! empty( $input['globalBShadowHover'] ) ) {
		$attrs['globalBShadowHover'] = tpgb_mcp_swt_bshadow( $input['globalBShadowHover'] ); }
	if ( ! empty( $input['scrollAnimation'] ) ) {
		$attrs['globalAnim'] = array( 'md' => sanitize_text_field( $input['scrollAnimation'] ) ); }
	if ( ! empty( $input['animDuration'] ) ) {
		$attrs['globalAnimDuration'] = sanitize_text_field( $input['animDuration'] ); }
	if ( ! empty( $input['animDelay'] ) ) {
		$attrs['globalAnimDelay'] = array( 'md' => sanitize_text_field( $input['animDelay'] ) ); }
	if ( ! empty( $input['animEasing'] ) ) {
		$attrs['globalAnimEasing'] = sanitize_text_field( $input['animEasing'] ); }

	if ( tpgb_mcp_swt_needs_wrapper( $attrs ) ) {
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
