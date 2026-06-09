<?php
/**
 * Ability: Add Nexter Blocks Countdown (tpgb/tp-countdown) to a Gutenberg page.
 *
 * @package The_Plus_Addons_For_Block_Editor
 * @since   1.3.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_register_ability(
	'nexter-blocks/add-tpgb-countdown',
	array(
		'label'               => __( 'Add Nexter Blocks Countdown', 'the-plus-addons-for-block-editor' ),
		'description'         => __( 'Adds the Nexter Blocks Countdown block (tpgb/tp-countdown) with 3 style presets (Simple, Flipdown, Progressbar), target date/time, expiry actions, custom labels, per-unit colours/backgrounds/borders, counter/label typography, progress bar stroke/trail settings, and full animation/transform/advanced controls. This is a dynamic block.', 'the-plus-addons-for-block-editor' ),
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
					'description' => 'Visual style. style-1 = Simple number countdown; style-2 = Flipdown (animated flip cards); style-3 = Progressbar (circular progress rings).',
					'default'     => 'style-1',
				),

				/* ── Countdown Options ────────────────────────────────────── */
				'countdownSelection'  => array(
					'type'        => 'string',
					'enum'        => array( 'normal', 'scarcity' ),
					'description' => 'Countdown type. "normal" = fixed target date; "scarcity" = evergreen/recurring timer (Pro).',
					'default'     => 'normal',
				),
				'datetime'            => array(
					'type'        => 'string',
					'description' => 'Target date/time in "YYYY-MM-DD HH:MM:SS" format e.g. "2026-12-31 23:59:59".',
					'default'     => '2026-12-31 23:59:59',
				),
				'inlineStyle'         => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Display countdown units in a single horizontal row (inline).',
				),

				/* ── Expiry action ─────────────────────────────────────────── */
				'countdownExpiry'     => array(
					'type'        => 'string',
					'enum'        => array( 'none', 'message', 'redirect' ),
					'description' => 'Action when countdown ends. "none" = hide, "message" = show text, "redirect" = go to URL.',
					'default'     => 'none',
				),
				'expiryMsg'           => array(
					'type'        => 'string',
					'default'     => 'Countdown Has Ended !',
					'description' => 'Message shown when countdown expires (when countdownExpiry is "message").',
				),
				'expiryRedirectUrl'   => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'URL to redirect to when countdown expires (when countdownExpiry is "redirect").',
				),

				/* ── Labels ───────────────────────────────────────────────── */
				'showLabels'          => array(
					'type'        => 'boolean',
					'default'     => true,
					'description' => 'Show text labels below each counter unit.',
				),
				'daysText'            => array(
					'type'        => 'string',
					'default'     => 'Days',
					'description' => 'Label text for days.',
				),
				'hoursText'           => array(
					'type'        => 'string',
					'default'     => 'Hours',
					'description' => 'Label text for hours.',
				),
				'minutesText'         => array(
					'type'        => 'string',
					'default'     => 'Minutes',
					'description' => 'Label text for minutes.',
				),
				'secondsText'         => array(
					'type'        => 'string',
					'default'     => 'Seconds',
					'description' => 'Label text for seconds.',
				),

				/* ── Flip theme (style-2) ─────────────────────────────────── */
				'flipTheme'           => array(
					'type'        => 'string',
					'enum'        => array( 'dark', 'light' ),
					'description' => 'Flip card theme (style-2 only). "dark" or "light".',
					'default'     => 'dark',
				),

				/* ── Counter styling ──────────────────────────────────────── */
				'counterFontColor'    => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Counter number text colour.',
				),
				'enableCounterTypo'   => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Enable custom counter typography.',
				),
				'counterTypoSize'     => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Counter font size in px.',
				),
				'counterMaxWidth'     => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Max width of each counter unit in px.',
				),

				/* ── Per-unit colours ──────────────────────────────────────── */
				'daysTextColor'       => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Days label colour.',
				),
				'hourTextColor'       => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Hours label colour.',
				),
				'minTextColor'        => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Minutes label colour.',
				),
				'secTextColor'        => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Seconds label colour.',
				),

				/* ── Per-unit border colours ───────────────────────────────── */
				'daysBorderColor'     => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Days unit border colour.',
				),
				'hourBorderColor'     => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Hours unit border colour.',
				),
				'minBorderColor'      => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Minutes unit border colour.',
				),
				'secBorderColor'      => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Seconds unit border colour.',
				),

				/* ── Per-unit background colours ───────────────────────────── */
				'daysBgColor'         => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Days unit background colour.',
				),
				'hourBgColor'         => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Hours unit background colour.',
				),
				'minBgColor'          => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Minutes unit background colour.',
				),
				'secBgColor'          => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Seconds unit background colour.',
				),

				/* ── Counter unit spacing/border ───────────────────────────── */
				'unitPadding'         => array(
					'type'        => 'object',
					'description' => 'Counter unit padding {top,right,bottom,left,unit}.',
				),
				'unitMargin'          => array(
					'type'        => 'object',
					'description' => 'Counter unit margin {top,right,bottom,left,unit}.',
				),
				'unitBorder'          => array(
					'type'        => 'object',
					'description' => 'Counter unit border {type,color,width}.',
				),
				'unitBorderRadius'    => array(
					'type'        => 'object',
					'description' => 'Counter unit border radius {top,bottom,left,right,unit}.',
				),

				/* ── Progressbar settings (style-3) ───────────────────────── */
				'strokeWidth'         => array(
					'type'        => 'string',
					'default'     => '5',
					'description' => 'Progress ring stroke width (style-3 only).',
				),
				'trailWidth'          => array(
					'type'        => 'string',
					'default'     => '3',
					'description' => 'Progress ring trail width (style-3 only).',
				),
				'daysTrailColor'      => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Days progress trail colour (style-3 only).',
				),
				'hourTrailColor'      => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Hours progress trail colour (style-3 only).',
				),
				'minTrailColor'       => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Minutes progress trail colour (style-3 only).',
				),
				'secTrailColor'       => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Seconds progress trail colour (style-3 only).',
				),

				/* ── Label styling ─────────────────────────────────────────── */
				'enableLabelTypo'     => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Enable custom label typography.',
				),
				'labelTypoSize'       => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Label font size in px.',
				),
				'labelPadding'        => array(
					'type'        => 'object',
					'description' => 'Label padding {top,right,bottom,left,unit}.',
				),
				'labelMargin'         => array(
					'type'        => 'object',
					'description' => 'Label margin {top,right,bottom,left,unit}.',
				),

				/* ── Expiry message styling ────────────────────────────────── */
				'expiryFontColor'     => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Expiry message text colour.',
				),
				'enableExpiryTypo'    => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'expiryTypoSize'      => array(
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

				/* ── Global: Spacing/Bg/Border/Shadow ─────────────────────── */
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

		'execute_callback'    => 'tpgb_mcp_add_countdown_ability',
		'permission_callback' => 'tpgb_mcp_add_countdown_permission',
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
 * Permission callback for the add-countdown ability.
 *
 * @param array|null $input Ability input arguments.
 * @return bool True when the current user may insert the block.
 */
function tpgb_mcp_add_countdown_permission( ?array $input = null ): bool {
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
function tpgb_mcp_countdown_spacing( array $v ): array {
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
function tpgb_mcp_countdown_border( array $b ): array {
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
function tpgb_mcp_countdown_radius( array $r ): array {
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
function tpgb_mcp_countdown_bshadow( array $s ): array {
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
function tpgb_mcp_countdown_bg( string $color ): array {
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
function tpgb_mcp_countdown_needs_wrapper( array $attrs ): bool {
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
 * Execute callback: insert a tpgb/tp-countdown block into a post.
 *
 * @param array $input Ability input arguments.
 * @return array|WP_Error Ability result or error on failure.
 */
function tpgb_mcp_add_countdown_ability( array $input ) {

	if ( ! defined( 'TPGB_VERSION' ) && ! defined( 'TPGBP_VERSION' ) ) {
		return new WP_Error( 'nexter_blocks_missing', __( 'Nexter Blocks must be active.', 'the-plus-addons-for-block-editor' ) );
	}

	$block_name = 'tpgb/tp-countdown';
	if ( ! tpgb_mcp_has_registered_block( $block_name ) ) {
		return new WP_Error( 'block_missing', __( 'tpgb/tp-countdown is not registered.', 'the-plus-addons-for-block-editor' ) );
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
		$attrs['style'] = $style; }

	/* ── Countdown options ────────────────────────────────────────────── */
	if ( ! empty( $input['countdownSelection'] ) && 'normal' !== $input['countdownSelection'] ) {
		$attrs['countdownSelection'] = sanitize_key( $input['countdownSelection'] );
	}
	if ( ! empty( $input['datetime'] ) && '2026-12-31 23:59:59' !== $input['datetime'] ) {
		$attrs['datetime'] = sanitize_text_field( $input['datetime'] );
	}
	if ( ! empty( $input['inlineStyle'] ) ) {
		$attrs['inlineStyle'] = true; }

	/* ── Expiry action ────────────────────────────────────────────────── */
	if ( ! empty( $input['countdownExpiry'] ) && 'none' !== $input['countdownExpiry'] ) {
		$attrs['countdownExpiry'] = sanitize_key( $input['countdownExpiry'] );
		if ( 'message' === $input['countdownExpiry'] && isset( $input['expiryMsg'] ) && 'Countdown Has Ended !' !== $input['expiryMsg'] ) {
			$attrs['expiryMsg'] = sanitize_text_field( $input['expiryMsg'] );
		}
		if ( 'redirect' === $input['countdownExpiry'] && ! empty( $input['expiryRedirectUrl'] ) ) {
			$attrs['expiryRedirect'] = array(
				'url'      => esc_url_raw( $input['expiryRedirectUrl'] ),
				'target'   => '',
				'nofollow' => '',
			);
		}
	}

	/* ── Labels ───────────────────────────────────────────────────────── */
	if ( isset( $input['showLabels'] ) && ! $input['showLabels'] ) {
		$attrs['showLabels'] = false; }
	if ( ! empty( $input['daysText'] ) && 'Days' !== $input['daysText'] ) {
		$attrs['daysText'] = sanitize_text_field( $input['daysText'] ); }
	if ( ! empty( $input['hoursText'] ) && 'Hours' !== $input['hoursText'] ) {
		$attrs['hoursText'] = sanitize_text_field( $input['hoursText'] ); }
	if ( ! empty( $input['minutesText'] ) && 'Minutes' !== $input['minutesText'] ) {
		$attrs['minutesText'] = sanitize_text_field( $input['minutesText'] ); }
	if ( ! empty( $input['secondsText'] ) && 'Seconds' !== $input['secondsText'] ) {
		$attrs['secondsText'] = sanitize_text_field( $input['secondsText'] ); }

	/* ── Flip theme (style-2) ─────────────────────────────────────────── */
	if ( ! empty( $input['flipTheme'] ) && 'dark' !== $input['flipTheme'] ) {
		$attrs['flipTheme'] = sanitize_key( $input['flipTheme'] ); }

	/* ── Counter styling ──────────────────────────────────────────────── */
	if ( ! empty( $input['counterFontColor'] ) ) {
		$attrs['counterFontColor'] = sanitize_text_field( $input['counterFontColor'] ); }
	if ( ! empty( $input['enableCounterTypo'] ) ) {
		$attrs['counterTypo'] = array(
			'openTypography' => 1,
			'size'           => array(
				'md'   => ! empty( $input['counterTypoSize'] ) ? (string) absint( $input['counterTypoSize'] ) : '',
				'unit' => 'px',
			),
			'height'         => '',
			'spacing'        => '',
			'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
		);
	}
	if ( ! empty( $input['counterMaxWidth'] ) ) {
		$attrs['counterMaxWidth'] = array(
			'md'   => (string) absint( $input['counterMaxWidth'] ),
			'unit' => 'px',
		); }

	/* ── Per-unit colours ──────────────────────────────────────────────── */
	if ( ! empty( $input['daysTextColor'] ) ) {
		$attrs['daysTextColor'] = sanitize_text_field( $input['daysTextColor'] ); }
	if ( ! empty( $input['hourTextColor'] ) ) {
		$attrs['hourTextColor'] = sanitize_text_field( $input['hourTextColor'] ); }
	if ( ! empty( $input['minTextColor'] ) ) {
		$attrs['minTextColor'] = sanitize_text_field( $input['minTextColor'] ); }
	if ( ! empty( $input['secTextColor'] ) ) {
		$attrs['secTextColor'] = sanitize_text_field( $input['secTextColor'] ); }

	/* ── Per-unit border colours ──────────────────────────────────────── */
	if ( ! empty( $input['daysBorderColor'] ) ) {
		$attrs['daysBorderColor'] = sanitize_text_field( $input['daysBorderColor'] ); }
	if ( ! empty( $input['hourBorderColor'] ) ) {
		$attrs['hourBorderColor'] = sanitize_text_field( $input['hourBorderColor'] ); }
	if ( ! empty( $input['minBorderColor'] ) ) {
		$attrs['minBorderColor'] = sanitize_text_field( $input['minBorderColor'] ); }
	if ( ! empty( $input['secBorderColor'] ) ) {
		$attrs['secBorderColor'] = sanitize_text_field( $input['secBorderColor'] ); }

	/* ── Per-unit backgrounds ─────────────────────────────────────────── */
	if ( ! empty( $input['daysBgColor'] ) ) {
		$attrs['daysBg'] = tpgb_mcp_countdown_bg( $input['daysBgColor'] ); }
	if ( ! empty( $input['hourBgColor'] ) ) {
		$attrs['hourBg'] = tpgb_mcp_countdown_bg( $input['hourBgColor'] ); }
	if ( ! empty( $input['minBgColor'] ) ) {
		$attrs['minBg'] = tpgb_mcp_countdown_bg( $input['minBgColor'] );  }
	if ( ! empty( $input['secBgColor'] ) ) {
		$attrs['secBg'] = tpgb_mcp_countdown_bg( $input['secBgColor'] );  }

	/* ── Counter unit spacing/border ──────────────────────────────────── */
	if ( ! empty( $input['unitPadding'] ) ) {
		$attrs['padding'] = tpgb_mcp_countdown_spacing( $input['unitPadding'] ); }
	if ( ! empty( $input['unitMargin'] ) ) {
		$attrs['margin'] = tpgb_mcp_countdown_spacing( $input['unitMargin'] );  }
	if ( ! empty( $input['unitBorder'] ) ) {
		$attrs['border'] = tpgb_mcp_countdown_border( $input['unitBorder'] );   }
	if ( ! empty( $input['unitBorderRadius'] ) ) {
		$attrs['borderR'] = tpgb_mcp_countdown_radius( $input['unitBorderRadius'] ); }

	/* ── Progressbar (style-3) ────────────────────────────────────────── */
	if ( ! empty( $input['strokeWidth'] ) && '5' !== $input['strokeWidth'] ) {
		$attrs['strokeWidth'] = sanitize_text_field( $input['strokeWidth'] ); }
	if ( ! empty( $input['trailWidth'] ) && '3' !== $input['trailWidth'] ) {
		$attrs['trailWidth'] = sanitize_text_field( $input['trailWidth'] );  }
	if ( ! empty( $input['daysTrailColor'] ) ) {
		$attrs['daysTrailColor'] = sanitize_text_field( $input['daysTrailColor'] ); }
	if ( ! empty( $input['hourTrailColor'] ) ) {
		$attrs['hourTrailColor'] = sanitize_text_field( $input['hourTrailColor'] ); }
	if ( ! empty( $input['minTrailColor'] ) ) {
		$attrs['minTrailColor'] = sanitize_text_field( $input['minTrailColor'] ); }
	if ( ! empty( $input['secTrailColor'] ) ) {
		$attrs['secTrailColor'] = sanitize_text_field( $input['secTrailColor'] ); }

	/* ── Label styling ────────────────────────────────────────────────── */
	if ( ! empty( $input['enableLabelTypo'] ) ) {
		$attrs['labelTypo'] = array(
			'openTypography' => 1,
			'size'           => array(
				'md'   => ! empty( $input['labelTypoSize'] ) ? (string) absint( $input['labelTypoSize'] ) : '',
				'unit' => 'px',
			),
			'height'         => '',
			'spacing'        => '',
			'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
		);
	}
	if ( ! empty( $input['labelPadding'] ) ) {
		$attrs['labelpadding'] = tpgb_mcp_countdown_spacing( $input['labelPadding'] ); }
	if ( ! empty( $input['labelMargin'] ) ) {
		$attrs['labelMargin'] = tpgb_mcp_countdown_spacing( $input['labelMargin'] );  }

	/* ── Expiry message styling ───────────────────────────────────────── */
	if ( ! empty( $input['expiryFontColor'] ) ) {
		$attrs['expiryFontColor'] = sanitize_text_field( $input['expiryFontColor'] ); }
	if ( ! empty( $input['enableExpiryTypo'] ) ) {
		$attrs['expiryMsgTypo'] = array(
			'openTypography' => 1,
			'size'           => array(
				'md'   => ! empty( $input['expiryTypoSize'] ) ? (string) absint( $input['expiryTypoSize'] ) : '',
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
		$attrs['globalMargin'] = tpgb_mcp_countdown_spacing( $input['globalMargin'] );  }
	if ( ! empty( $input['globalPadding'] ) ) {
		$attrs['globalPadding'] = tpgb_mcp_countdown_spacing( $input['globalPadding'] ); }
	if ( ! empty( $input['globalBgColor'] ) ) {
		$attrs['globalBg'] = tpgb_mcp_countdown_bg( $input['globalBgColor'] );      }
	if ( ! empty( $input['globalBgHoverColor'] ) ) {
		$attrs['globalBgHover'] = tpgb_mcp_countdown_bg( $input['globalBgHoverColor'] ); }
	if ( ! empty( $input['globalBorder'] ) ) {
		$attrs['globalBorder'] = tpgb_mcp_countdown_border( $input['globalBorder'] );       }
	if ( ! empty( $input['globalBorderHover'] ) ) {
		$attrs['globalBorderHover'] = tpgb_mcp_countdown_border( $input['globalBorderHover'] );  }
	if ( ! empty( $input['globalBRadius'] ) ) {
		$attrs['globalBRadius'] = tpgb_mcp_countdown_radius( $input['globalBRadius'] );      }
	if ( ! empty( $input['globalBRadiusHover'] ) ) {
		$attrs['globalBRadiusHover'] = tpgb_mcp_countdown_radius( $input['globalBRadiusHover'] ); }
	if ( ! empty( $input['globalBShadow'] ) ) {
		$attrs['globalBShadow'] = tpgb_mcp_countdown_bshadow( $input['globalBShadow'] );      }
	if ( ! empty( $input['globalBShadowHover'] ) ) {
		$attrs['globalBShadowHover'] = tpgb_mcp_countdown_bshadow( $input['globalBShadowHover'] ); }

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
	if ( tpgb_mcp_countdown_needs_wrapper( $attrs ) ) {
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
