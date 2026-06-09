<?php
/**
 * Ability: Add Nexter Blocks Progress Tracker (tpgb/tp-progress-tracker) to a Gutenberg page.
 *
 * @package The_Plus_Addons_For_Block_Editor
 * @since   1.3.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_register_ability(
	'nexter-blocks/add-tpgb-progress-tracker',
	array(
		'label'               => __( 'Add Nexter Blocks Progress Tracker', 'the-plus-addons-for-block-editor' ),
		'description'         => __( 'Adds the Nexter Blocks Progress Tracker block (tpgb/tp-progress-tracker) — a scroll-based progress indicator showing how much of the page/content has been viewed. Supports 3 display types (horizontal bar at top/bottom, vertical bar on left/right, circular indicator in corner), optional pin points for chapter/section markers, percentage text, and comprehensive styling. This is a dynamic block.', 'the-plus-addons-for-block-editor' ),
		'category'            => 'nexter-blocks',

		'input_schema'        => array(
			'type'                 => 'object',
			'properties'           => array(

				/* ── Core ─────────────────────────────────────────────────── */
				'post_id'              => array( 'type' => 'integer' ),
				'position'             => array(
					'type'    => 'integer',
					'default' => -1,
				),
				'parent_block_id'      => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Layout type ──────────────────────────────────────────── */
				'progressType'         => array(
					'type'        => 'string',
					'enum'        => array( 'horizontal', 'vertical', 'circular' ),
					'description' => 'Progress display type.',
					'default'     => 'horizontal',
				),
				'horizontalPosition'   => array(
					'type'    => 'string',
					'enum'    => array( 'top', 'bottom' ),
					'default' => 'top',
				),
				'horizontalDirection'  => array(
					'type'    => 'string',
					'enum'    => array( 'ltr', 'rtl' ),
					'default' => 'ltr',
				),
				'verticalPosition'     => array(
					'type'    => 'string',
					'enum'    => array( 'left', 'right' ),
					'default' => 'left',
				),
				'circularPosition'     => array(
					'type'    => 'string',
					'enum'    => array( 'top-left', 'top-right', 'bottom-left', 'bottom-right' ),
					'default' => 'top-left',
				),

				/* ── Circular offsets ─────────────────────────────────────── */
				'circularTopOffset'    => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'circularBottomOffset' => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'circularLeftOffset'   => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'circularRightOffset'  => array(
					'type'    => 'integer',
					'default' => 0,
				),

				/* ── Target ───────────────────────────────────────────────── */
				'applyTo'              => array(
					'type'        => 'string',
					'enum'        => array( 'entire', 'element' ),
					'description' => '"entire" = track whole page; "element" = track specific element.',
					'default'     => 'entire',
				),
				'uniqueSelector'       => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'CSS selector to track (when applyTo is "element").',
				),
				'relativeToSelector'   => array(
					'type'    => 'boolean',
					'default' => false,
				),

				/* ── Percentage text ──────────────────────────────────────── */
				'showPercentage'       => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'percentageStyle'      => array(
					'type'    => 'string',
					'default' => 'style-1',
				),

				/* ── Track size ───────────────────────────────────────────── */
				'circleSize'           => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Circular size in px (circular only).',
				),
				'trackSize'            => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Track thickness in px.',
				),
				'trackOffset'          => array(
					'type'    => 'integer',
					'default' => 0,
				),

				/* ── Track styling ────────────────────────────────────────── */
				'circleBgColor'        => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Circular background colour.',
				),
				'trackBgColor'         => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Track background colour.',
				),
				'trackBorder'          => array( 'type' => 'object' ),
				'trackBorderRadius'    => array( 'type' => 'object' ),
				'trackShadowColor'     => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Fill styling ─────────────────────────────────────────── */
				'fillBgColor'          => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Fill/progress colour.',
				),
				'circularTrackColor'   => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Circular track colour.',
				),
				'circularFillColor'    => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Circular fill colour.',
				),

				/* ── Percentage text styling ──────────────────────────────── */
				'enablePercentageTypo' => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'percentageTypoSize'   => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'percentageTextColor'  => array(
					'type'    => 'string',
					'default' => '',
				),
				'percentageBgColor'    => array(
					'type'    => 'string',
					'default' => '',
				),
				'percentagePadding'    => array( 'type' => 'object' ),

				/* ── Pin points ───────────────────────────────────────────── */
				'enablePinPoint'       => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'pinPointStyle'        => array(
					'type'    => 'string',
					'default' => 'style-1',
				),
				'pinPoints'            => array(
					'type'        => 'array',
					'description' => 'Array of pin point objects with {label, selector, icon}.',
					'items'       => array(
						'type'       => 'object',
						'properties' => array(
							'label'    => array( 'type' => 'string' ),
							'selector' => array(
								'type'        => 'string',
								'description' => 'Target selector.',
							),
							'icon'     => array(
								'type'        => 'string',
								'description' => 'Font Awesome icon.',
							),
						),
					),
				),

				/* ── Pin label styling ────────────────────────────────────── */
				'enablePinTypo'        => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'pinTypoSize'          => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'pinPadding'           => array( 'type' => 'object' ),
				'pinOffset'            => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'pinColor'             => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Pin text colour (normal).',
				),
				'pinHoverColor'        => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Pin text colour (hover).',
				),
				'pinActiveColor'       => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Pin text colour (active).',
				),
				'pinBgColor'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'pinBgHoverColor'      => array(
					'type'    => 'string',
					'default' => '',
				),
				'pinBgActiveColor'     => array(
					'type'    => 'string',
					'default' => '',
				),
				'pinBorder'            => array( 'type' => 'object' ),
				'pinBorderHover'       => array( 'type' => 'object' ),
				'pinBorderActive'      => array( 'type' => 'object' ),
				'pinRadius'            => array( 'type' => 'object' ),
				'pinRadiusHover'       => array( 'type' => 'object' ),
				'pinRadiusActive'      => array( 'type' => 'object' ),

				/* ── Pin dot styling ──────────────────────────────────────── */
				'pinDotSize'           => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Pin dot size in px.',
				),
				'pinDotBgColor'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'pinDotBgHoverColor'   => array(
					'type'    => 'string',
					'default' => '',
				),
				'pinDotBgActiveColor'  => array(
					'type'    => 'string',
					'default' => '',
				),
				'pinDotBorder'         => array( 'type' => 'object' ),
				'pinDotBorderHover'    => array( 'type' => 'object' ),
				'pinDotBorderActive'   => array( 'type' => 'object' ),
				'pinDotRadius'         => array( 'type' => 'object' ),
				'pinDotRadiusHover'    => array( 'type' => 'object' ),
				'pinDotRadiusActive'   => array( 'type' => 'object' ),

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
					'type'    => 'string',
					'default' => '',
				),
				'animEasing'           => array(
					'type'    => 'string',
					'enum'    => array( '', 'linear', 'ease', 'ease-in', 'ease-out', 'ease-in-out' ),
					'default' => '',
				),

				/* ── Advanced ─────────────────────────────────────────────── */
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
				'globalMargin'         => array( 'type' => 'object' ),
				'globalPadding'        => array( 'type' => 'object' ),

				'settings'             => array( 'type' => 'object' ),
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

		'execute_callback'    => 'tpgb_mcp_add_progress_tracker_ability',
		'permission_callback' => 'tpgb_mcp_add_progress_tracker_permission',
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
 * Permission callback for the add-progress-tracker ability.
 *
 * @param array|null $input Ability input arguments.
 * @return bool True when the current user may insert the block.
 */
function tpgb_mcp_add_progress_tracker_permission( ?array $input = null ): bool {
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
function tpgb_mcp_ptrk_spacing( array $v ): array {
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
function tpgb_mcp_ptrk_border( array $b ): array {
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
function tpgb_mcp_ptrk_radius( array $r ): array {
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
 * Build a Nexter Blocks solid-colour background attribute.
 *
 * @param string $color Background colour value.
 * @return array Background attribute structured for the block.
 */
function tpgb_mcp_ptrk_bg( string $color ): array {
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
function tpgb_mcp_ptrk_needs_wrapper( array $attrs ): bool {
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
 * Execute callback: insert a tpgb/tp-progress-tracker block into a post.
 *
 * @param array $input Ability input arguments.
 * @return array|WP_Error Ability result or error on failure.
 */
function tpgb_mcp_add_progress_tracker_ability( array $input ) {

	if ( ! defined( 'TPGB_VERSION' ) && ! defined( 'TPGBP_VERSION' ) ) {
		return new WP_Error( 'nexter_blocks_missing', __( 'Nexter Blocks must be active.', 'the-plus-addons-for-block-editor' ) );
	}

	$block_name = 'tpgb/tp-progress-tracker';
	if ( ! tpgb_mcp_has_registered_block( $block_name ) ) {
		return new WP_Error( 'block_missing', __( 'tpgb/tp-progress-tracker is not registered.', 'the-plus-addons-for-block-editor' ) );
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

	/* ── Layout type ──────────────────────────────────────────────────── */
	if ( ! empty( $input['progressType'] ) && 'horizontal' !== $input['progressType'] ) {
		$attrs['progressType'] = sanitize_key( $input['progressType'] ); }
	if ( ! empty( $input['horizontalPosition'] ) && 'top' !== $input['horizontalPosition'] ) {
		$attrs['horizontalPos'] = sanitize_key( $input['horizontalPosition'] ); }
	if ( ! empty( $input['horizontalDirection'] ) && 'ltr' !== $input['horizontalDirection'] ) {
		$attrs['hzDirection'] = sanitize_key( $input['horizontalDirection'] ); }
	if ( ! empty( $input['verticalPosition'] ) && 'left' !== $input['verticalPosition'] ) {
		$attrs['verticalPos'] = sanitize_key( $input['verticalPosition'] ); }
	if ( ! empty( $input['circularPosition'] ) && 'top-left' !== $input['circularPosition'] ) {
		$attrs['circularPos'] = sanitize_text_field( $input['circularPosition'] ); }

	/* ── Circular offsets ─────────────────────────────────────────────── */
	if ( ! empty( $input['circularTopOffset'] ) ) {
		$attrs['cPosTopOff'] = array(
			'md'   => (string) intval( $input['circularTopOffset'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['circularBottomOffset'] ) ) {
		$attrs['cPosBottomOff'] = array(
			'md'   => (string) intval( $input['circularBottomOffset'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['circularLeftOffset'] ) ) {
		$attrs['cPosLeftOff'] = array(
			'md'   => (string) intval( $input['circularLeftOffset'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['circularRightOffset'] ) ) {
		$attrs['cPosRightOff'] = array(
			'md'   => (string) intval( $input['circularRightOffset'] ),
			'unit' => 'px',
		); }

	/* ── Target ───────────────────────────────────────────────────────── */
	if ( ! empty( $input['applyTo'] ) && 'entire' !== $input['applyTo'] ) {
		$attrs['applyTo'] = sanitize_key( $input['applyTo'] ); }
	if ( ! empty( $input['uniqueSelector'] ) ) {
		$attrs['unqSelector'] = sanitize_text_field( $input['uniqueSelector'] ); }
	if ( ! empty( $input['relativeToSelector'] ) ) {
		$attrs['relTselector'] = true; }

	/* ── Percentage text ──────────────────────────────────────────────── */
	if ( ! empty( $input['showPercentage'] ) ) {
		$attrs['percentageText'] = true; }
	if ( ! empty( $input['percentageStyle'] ) && 'style-1' !== $input['percentageStyle'] ) {
		$attrs['percentageStyle'] = sanitize_text_field( $input['percentageStyle'] ); }

	/* ── Track size ───────────────────────────────────────────────────── */
	if ( ! empty( $input['circleSize'] ) ) {
		$attrs['circleSize'] = sanitize_text_field( $input['circleSize'] ); }
	if ( ! empty( $input['trackSize'] ) ) {
		$attrs['trackSize'] = array(
			'md'   => (string) absint( $input['trackSize'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['trackOffset'] ) ) {
		$attrs['trackOffset'] = array(
			'md'   => (string) absint( $input['trackOffset'] ),
			'unit' => 'px',
		); }

	/* ── Track styling ────────────────────────────────────────────────── */
	if ( ! empty( $input['circleBgColor'] ) ) {
		$attrs['circleBGColor'] = sanitize_text_field( $input['circleBgColor'] ); }
	if ( ! empty( $input['trackBgColor'] ) ) {
		$attrs['trackBG'] = tpgb_mcp_ptrk_bg( $input['trackBgColor'] ); }
	if ( ! empty( $input['trackBorder'] ) ) {
		$attrs['trackBdr'] = tpgb_mcp_ptrk_border( $input['trackBorder'] ); }
	if ( ! empty( $input['trackBorderRadius'] ) ) {
		$attrs['trackBRadius'] = tpgb_mcp_ptrk_radius( $input['trackBorderRadius'] ); }
	if ( ! empty( $input['trackShadowColor'] ) ) {
		$attrs['trackBShadow'] = array(
			'horizontal' => 0,
			'vertical'   => 8,
			'blur'       => 20,
			'spread'     => 1,
			'color'      => sanitize_text_field( $input['trackShadowColor'] ),
		);
	}

	/* ── Fill styling ─────────────────────────────────────────────────── */
	if ( ! empty( $input['fillBgColor'] ) ) {
		$attrs['fillBG'] = tpgb_mcp_ptrk_bg( $input['fillBgColor'] ); }
	if ( ! empty( $input['circularTrackColor'] ) ) {
		$attrs['cTrackColor'] = sanitize_text_field( $input['circularTrackColor'] ); }
	if ( ! empty( $input['circularFillColor'] ) ) {
		$attrs['cFillColor'] = sanitize_text_field( $input['circularFillColor'] ); }

	/* ── Percentage text styling ──────────────────────────────────────── */
	if ( ! empty( $input['enablePercentageTypo'] ) ) {
		$attrs['texTypo'] = array(
			'openTypography' => 1,
			'size'           => array(
				'md'   => ! empty( $input['percentageTypoSize'] ) ? (string) absint( $input['percentageTypoSize'] ) : '',
				'unit' => 'px',
			),
			'height'         => '',
			'spacing'        => '',
			'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
		);
	}
	if ( ! empty( $input['percentageTextColor'] ) ) {
		$attrs['textColor'] = sanitize_text_field( $input['percentageTextColor'] ); }
	if ( ! empty( $input['percentageBgColor'] ) ) {
		$attrs['ttBGColor'] = sanitize_text_field( $input['percentageBgColor'] ); }
	if ( ! empty( $input['percentagePadding'] ) ) {
		$attrs['ttPadding'] = tpgb_mcp_ptrk_spacing( $input['percentagePadding'] ); }

	/* ── Pin points ───────────────────────────────────────────────────── */
	if ( ! empty( $input['enablePinPoint'] ) ) {
		$attrs['pinPoint'] = true;
		if ( ! empty( $input['pinPointStyle'] ) && 'style-1' !== $input['pinPointStyle'] ) {
			$attrs['pinPStyle'] = sanitize_text_field( $input['pinPointStyle'] ); }
	}

	if ( ! empty( $input['pinPoints'] ) && is_array( $input['pinPoints'] ) ) {
		$pins = array();
		foreach ( $input['pinPoints'] as $i => $p ) {
			if ( ! is_array( $p ) ) {
				continue; }
			$pins[] = array(
				'_key'        => (string) $i,
				'pinLabel'    => tpgb_mcp_clean_text( $p['label'] ?? '' ),
				'pinSelector' => sanitize_text_field( $p['selector'] ?? '' ),
				'pinIcon'     => sanitize_text_field( $p['icon'] ?? '' ),
			);
		}
		if ( ! empty( $pins ) ) {
			$attrs['pinPointRep'] = $pins; }
	}

	/* ── Pin label styling ────────────────────────────────────────────── */
	if ( ! empty( $input['enablePinTypo'] ) ) {
		$attrs['pinTypo'] = array(
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
	if ( ! empty( $input['pinPadding'] ) ) {
		$attrs['pinPadding'] = tpgb_mcp_ptrk_spacing( $input['pinPadding'] ); }
	if ( ! empty( $input['pinOffset'] ) ) {
		$attrs['pinOffset'] = array(
			'md'   => (string) absint( $input['pinOffset'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['pinColor'] ) ) {
		$attrs['pinColor'] = sanitize_text_field( $input['pinColor'] ); }
	if ( ! empty( $input['pinHoverColor'] ) ) {
		$attrs['pinHColor'] = sanitize_text_field( $input['pinHoverColor'] ); }
	if ( ! empty( $input['pinActiveColor'] ) ) {
		$attrs['pinAColor'] = sanitize_text_field( $input['pinActiveColor'] ); }
	if ( ! empty( $input['pinBgColor'] ) ) {
		$attrs['pinBG'] = tpgb_mcp_ptrk_bg( $input['pinBgColor'] ); }
	if ( ! empty( $input['pinBgHoverColor'] ) ) {
		$attrs['pinHBG'] = tpgb_mcp_ptrk_bg( $input['pinBgHoverColor'] ); }
	if ( ! empty( $input['pinBgActiveColor'] ) ) {
		$attrs['pinABG'] = tpgb_mcp_ptrk_bg( $input['pinBgActiveColor'] ); }
	if ( ! empty( $input['pinBorder'] ) ) {
		$attrs['pinBdr'] = tpgb_mcp_ptrk_border( $input['pinBorder'] ); }
	if ( ! empty( $input['pinBorderHover'] ) ) {
		$attrs['pinHBdr'] = tpgb_mcp_ptrk_border( $input['pinBorderHover'] ); }
	if ( ! empty( $input['pinBorderActive'] ) ) {
		$attrs['pinABdr'] = tpgb_mcp_ptrk_border( $input['pinBorderActive'] ); }
	if ( ! empty( $input['pinRadius'] ) ) {
		$attrs['pinRadius'] = tpgb_mcp_ptrk_radius( $input['pinRadius'] ); }
	if ( ! empty( $input['pinRadiusHover'] ) ) {
		$attrs['pinHRadius'] = tpgb_mcp_ptrk_radius( $input['pinRadiusHover'] ); }
	if ( ! empty( $input['pinRadiusActive'] ) ) {
		$attrs['pinARadius'] = tpgb_mcp_ptrk_radius( $input['pinRadiusActive'] ); }

	/* ── Pin dot styling ──────────────────────────────────────────────── */
	if ( ! empty( $input['pinDotSize'] ) ) {
		$attrs['pinDotSize'] = array(
			'md'   => (string) absint( $input['pinDotSize'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['pinDotBgColor'] ) ) {
		$attrs['pDotBG'] = tpgb_mcp_ptrk_bg( $input['pinDotBgColor'] ); }
	if ( ! empty( $input['pinDotBgHoverColor'] ) ) {
		$attrs['pDotHBG'] = tpgb_mcp_ptrk_bg( $input['pinDotBgHoverColor'] ); }
	if ( ! empty( $input['pinDotBgActiveColor'] ) ) {
		$attrs['pDotABG'] = tpgb_mcp_ptrk_bg( $input['pinDotBgActiveColor'] ); }
	if ( ! empty( $input['pinDotBorder'] ) ) {
		$attrs['pDotBdr'] = tpgb_mcp_ptrk_border( $input['pinDotBorder'] ); }
	if ( ! empty( $input['pinDotBorderHover'] ) ) {
		$attrs['pDotHBdr'] = tpgb_mcp_ptrk_border( $input['pinDotBorderHover'] ); }
	if ( ! empty( $input['pinDotBorderActive'] ) ) {
		$attrs['pDotABdr'] = tpgb_mcp_ptrk_border( $input['pinDotBorderActive'] ); }
	if ( ! empty( $input['pinDotRadius'] ) ) {
		$attrs['pDotRadius'] = tpgb_mcp_ptrk_radius( $input['pinDotRadius'] ); }
	if ( ! empty( $input['pinDotRadiusHover'] ) ) {
		$attrs['pDotHRadius'] = tpgb_mcp_ptrk_radius( $input['pinDotRadiusHover'] ); }
	if ( ! empty( $input['pinDotRadiusActive'] ) ) {
		$attrs['pDotARadius'] = tpgb_mcp_ptrk_radius( $input['pinDotRadiusActive'] ); }

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

	if ( ! empty( $input['globalMargin'] ) ) {
		$attrs['globalMargin'] = tpgb_mcp_ptrk_spacing( $input['globalMargin'] );  }
	if ( ! empty( $input['globalPadding'] ) ) {
		$attrs['globalPadding'] = tpgb_mcp_ptrk_spacing( $input['globalPadding'] ); }

	/* ── tpgbDisrule ──────────────────────────────────────────────────── */
	if ( tpgb_mcp_ptrk_needs_wrapper( $attrs ) ) {
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
