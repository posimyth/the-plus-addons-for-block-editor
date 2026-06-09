<?php
/**
 * Ability: Add Nexter Blocks Post Comment (tpgb/tp-post-comment) to a Gutenberg page.
 *
 * @package The_Plus_Addons_For_Block_Editor
 * @since   1.3.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_register_ability(
	'nexter-blocks/add-tpgb-post-comment',
	array(
		'label'               => __( 'Add Nexter Blocks Post Comment', 'the-plus-addons-for-block-editor' ),
		'description'         => __( 'Adds the Nexter Blocks Post Comment block (tpgb/tp-post-comment) — displays the WordPress comments list with nested replies AND a comment submission form. Automatically pulls comments for the current post. Supports custom labels, comprehensive styling for user avatars, names, metadata (date), reply buttons, input fields, and submit button. Use at the bottom of blog posts. This is a dynamic block.', 'the-plus-addons-for-block-editor' ),
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

				/* ── Text labels ──────────────────────────────────────────── */
				'commentTitle'           => array(
					'type'    => 'string',
					'default' => 'Comment',
				),
				'commentFormTitle'       => array(
					'type'    => 'string',
					'default' => 'Leave Your Comment',
				),
				'loggedInAsText'         => array(
					'type'    => 'string',
					'default' => 'Logged in as',
				),
				'logOutText'             => array(
					'type'    => 'string',
					'default' => 'Log out?',
				),
				'cancelReplyText'        => array(
					'type'    => 'string',
					'default' => 'Cancel Reply',
				),
				'commentFieldLabel'      => array(
					'type'    => 'string',
					'default' => 'Comment',
				),
				'submitBtnText'          => array(
					'type'    => 'string',
					'default' => 'Submit Now',
				),

				/* ── Comment title styling ────────────────────────────────── */
				'enableCommentTypo'      => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'commentTypoSize'        => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'commentColor'           => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Avatar styling ───────────────────────────────────────── */
				'avatarPadding'          => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Padding around avatar in px.',
				),
				'avatarBorderRadius'     => array( 'type' => 'object' ),
				'enableAvatarShadow'     => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'avatarShadowH'          => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'avatarShadowV'          => array(
					'type'    => 'integer',
					'default' => 4,
				),
				'avatarShadowBlur'       => array(
					'type'    => 'integer',
					'default' => 8,
				),
				'avatarShadowColor'      => array(
					'type'    => 'string',
					'default' => 'rgba(0,0,0,0.40)',
				),

				/* ── User name styling ────────────────────────────────────── */
				'enableUserTypo'         => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'userTypoSize'           => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'userColor'              => array(
					'type'    => 'string',
					'default' => '',
				),
				'userHoverColor'         => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Meta (date) styling ──────────────────────────────────── */
				'enableMetaTypo'         => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'metaTypoSize'           => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'metaColor'              => array(
					'type'    => 'string',
					'default' => '',
				),
				'metaHoverColor'         => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Reply button styling ─────────────────────────────────── */
				'replyPadding'           => array( 'type' => 'object' ),
				'enableReplyTypo'        => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'replyTypoSize'          => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'replyColor'             => array(
					'type'    => 'string',
					'default' => '#f18248',
				),
				'replyHoverColor'        => array(
					'type'    => 'string',
					'default' => '#f18248',
				),
				'replyBorder'            => array( 'type' => 'object' ),
				'replyBorderHover'       => array( 'type' => 'object' ),
				'replyBorderRadius'      => array( 'type' => 'object' ),
				'replyBorderRadiusHover' => array( 'type' => 'object' ),
				'replyBgColor'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'replyBgHoverColor'      => array(
					'type'    => 'string',
					'default' => '',
				),
				'enableReplyShadow'      => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'replyShadowH'           => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'replyShadowV'           => array(
					'type'    => 'integer',
					'default' => 4,
				),
				'replyShadowBlur'        => array(
					'type'    => 'integer',
					'default' => 8,
				),
				'replyShadowColor'       => array(
					'type'    => 'string',
					'default' => 'rgba(0,0,0,0.40)',
				),
				'enableReplyShadowHover' => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'replyShadowHoverH'      => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'replyShadowHoverV'      => array(
					'type'    => 'integer',
					'default' => 4,
				),
				'replyShadowHoverBlur'   => array(
					'type'    => 'integer',
					'default' => 8,
				),
				'replyShadowHoverColor'  => array(
					'type'    => 'string',
					'default' => 'rgba(0,0,0,0.40)',
				),

				/* ── Input field styling ──────────────────────────────────── */
				'enableFieldTypo'        => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'fieldTypoSize'          => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'fieldColor'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'fieldHoverColor'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'fieldPadding'           => array( 'type' => 'object' ),
				'fieldBorder'            => array( 'type' => 'object' ),
				'fieldBorderHover'       => array( 'type' => 'object' ),
				'fieldBorderRadius'      => array( 'type' => 'object' ),
				'fieldBorderRadiusHover' => array( 'type' => 'object' ),
				'fieldBgColor'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'fieldBgHoverColor'      => array(
					'type'    => 'string',
					'default' => '',
				),
				'enableFieldShadow'      => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'fieldShadowH'           => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'fieldShadowV'           => array(
					'type'    => 'integer',
					'default' => 4,
				),
				'fieldShadowBlur'        => array(
					'type'    => 'integer',
					'default' => 8,
				),
				'fieldShadowColor'       => array(
					'type'    => 'string',
					'default' => 'rgba(0,0,0,0.40)',
				),

				/* ── Submit button styling ────────────────────────────────── */
				'enableBtnTypo'          => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'btnTypoSize'            => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'btnColor'               => array(
					'type'    => 'string',
					'default' => '',
				),
				'btnHoverColor'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'btnPadding'             => array( 'type' => 'object' ),
				'btnBorder'              => array( 'type' => 'object' ),
				'btnBorderHover'         => array( 'type' => 'object' ),
				'btnBorderRadius'        => array( 'type' => 'object' ),
				'btnBorderRadiusHover'   => array( 'type' => 'object' ),
				'btnBgColor'             => array(
					'type'    => 'string',
					'default' => '#6f14f1',
				),
				'btnBgHoverColor'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'enableBtnShadow'        => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'btnShadowH'             => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'btnShadowV'             => array(
					'type'    => 'integer',
					'default' => 4,
				),
				'btnShadowBlur'          => array(
					'type'    => 'integer',
					'default' => 8,
				),
				'btnShadowColor'         => array(
					'type'    => 'string',
					'default' => 'rgba(0,0,0,0.40)',
				),
				'enableBtnShadowHover'   => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'btnShadowHoverH'        => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'btnShadowHoverV'        => array(
					'type'    => 'integer',
					'default' => 4,
				),
				'btnShadowHoverBlur'     => array(
					'type'    => 'integer',
					'default' => 8,
				),
				'btnShadowHoverColor'    => array(
					'type'    => 'string',
					'default' => 'rgba(0,0,0,0.40)',
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

		'execute_callback'    => 'tpgb_mcp_add_post_comment_ability',
		'permission_callback' => 'tpgb_mcp_add_post_comment_permission',
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
 * Permission callback for the add-post-comment ability.
 *
 * @param array|null $input Ability input arguments.
 * @return bool True when the current user may insert the block.
 */
function tpgb_mcp_add_post_comment_permission( ?array $input = null ): bool {
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
function tpgb_mcp_pcmt_spacing( array $v ): array {
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
function tpgb_mcp_pcmt_border( array $b ): array {
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
function tpgb_mcp_pcmt_radius( array $r ): array {
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
function tpgb_mcp_pcmt_bshadow( array $s ): array {
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
 * Build a Nexter Blocks colour-only background attribute (no flags).
 *
 * @param string $color Background colour value.
 * @return array Background attribute structured for the block.
 */
function tpgb_mcp_pcmt_bg_simple( string $color ): array {
	return array(
		'bgType'         => 'color',
		'bgDefaultColor' => sanitize_text_field( $color ),
		'bgGradient'     => (object) array(),
	);
}
/**
 * Build a Nexter Blocks solid-colour background attribute.
 *
 * @param string $color Background colour value.
 * @return array Background attribute structured for the block.
 */
function tpgb_mcp_pcmt_bg( string $color ): array {
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
function tpgb_mcp_pcmt_needs_wrapper( array $attrs ): bool {
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
 * Execute callback: insert a tpgb/tp-post-comment block into a post.
 *
 * @param array $input Ability input arguments.
 * @return array|WP_Error Ability result or error on failure.
 */
function tpgb_mcp_add_post_comment_ability( array $input ) {

	if ( ! defined( 'TPGB_VERSION' ) && ! defined( 'TPGBP_VERSION' ) ) {
		return new WP_Error( 'nexter_blocks_missing', __( 'Nexter Blocks must be active.', 'the-plus-addons-for-block-editor' ) );
	}

	$block_name = 'tpgb/tp-post-comment';
	if ( ! tpgb_mcp_has_registered_block( $block_name ) ) {
		return new WP_Error( 'block_missing', __( 'tpgb/tp-post-comment is not registered.', 'the-plus-addons-for-block-editor' ) );
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

	/* ── Labels ───────────────────────────────────────────────────────── */
	if ( ! empty( $input['commentTitle'] ) && 'Comment' !== $input['commentTitle'] ) {
		$attrs['commentTitle'] = sanitize_text_field( $input['commentTitle'] ); }
	if ( ! empty( $input['commentFormTitle'] ) && 'Leave Your Comment' !== $input['commentFormTitle'] ) {
		$attrs['commentFormTitle'] = sanitize_text_field( $input['commentFormTitle'] ); }
	if ( ! empty( $input['loggedInAsText'] ) && 'Logged in as' !== $input['loggedInAsText'] ) {
		$attrs['loggedInAsText'] = sanitize_text_field( $input['loggedInAsText'] ); }
	if ( ! empty( $input['logOutText'] ) && 'Log out?' !== $input['logOutText'] ) {
		$attrs['logOutText'] = sanitize_text_field( $input['logOutText'] ); }
	if ( ! empty( $input['cancelReplyText'] ) && 'Cancel Reply' !== $input['cancelReplyText'] ) {
		$attrs['cancelReplyText'] = sanitize_text_field( $input['cancelReplyText'] ); }
	if ( ! empty( $input['commentFieldLabel'] ) && 'Comment' !== $input['commentFieldLabel'] ) {
		$attrs['commentField'] = sanitize_text_field( $input['commentFieldLabel'] ); }
	if ( ! empty( $input['submitBtnText'] ) && 'Submit Now' !== $input['submitBtnText'] ) {
		$attrs['submitBtnText'] = sanitize_text_field( $input['submitBtnText'] ); }

	/* ── Comment title styling ────────────────────────────────────────── */
	if ( ! empty( $input['enableCommentTypo'] ) ) {
		$attrs['commTypo'] = array(
			'openTypography' => 1,
			'size'           => array(
				'md'   => ! empty( $input['commentTypoSize'] ) ? (string) absint( $input['commentTypoSize'] ) : '',
				'unit' => 'px',
			),
			'height'         => '',
			'spacing'        => '',
			'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
		);
	}
	if ( ! empty( $input['commentColor'] ) ) {
		$attrs['commColor'] = sanitize_text_field( $input['commentColor'] ); }

	/* ── Avatar styling ───────────────────────────────────────────────── */
	if ( ! empty( $input['avatarPadding'] ) ) {
		$attrs['profilePadding'] = array(
			'md'   => (string) absint( $input['avatarPadding'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['avatarBorderRadius'] ) ) {
		$attrs['profileBorderRadius'] = tpgb_mcp_pcmt_radius( $input['avatarBorderRadius'] ); }
	if ( ! empty( $input['enableAvatarShadow'] ) ) {
		$attrs['profileBoxShadow'] = array(
			'openShadow' => true,
			'horizontal' => (string) intval( $input['avatarShadowH'] ?? 0 ),
			'vertical'   => (string) intval( $input['avatarShadowV'] ?? 4 ),
			'blur'       => (string) absint( $input['avatarShadowBlur'] ?? 8 ),
			'color'      => sanitize_text_field( $input['avatarShadowColor'] ?? 'rgba(0,0,0,0.40)' ),
		);
	}

	/* ── User name styling ────────────────────────────────────────────── */
	if ( ! empty( $input['enableUserTypo'] ) ) {
		$attrs['userTypo'] = array(
			'openTypography' => 1,
			'size'           => array(
				'md'   => ! empty( $input['userTypoSize'] ) ? (string) absint( $input['userTypoSize'] ) : '',
				'unit' => 'px',
			),
			'height'         => '',
			'spacing'        => '',
			'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
		);
	}
	if ( ! empty( $input['userColor'] ) ) {
		$attrs['userColor'] = sanitize_text_field( $input['userColor'] ); }
	if ( ! empty( $input['userHoverColor'] ) ) {
		$attrs['userHoverColor'] = sanitize_text_field( $input['userHoverColor'] ); }

	/* ── Meta styling ─────────────────────────────────────────────────── */
	if ( ! empty( $input['enableMetaTypo'] ) ) {
		$attrs['metaTypo'] = array(
			'openTypography' => 1,
			'size'           => array(
				'md'   => ! empty( $input['metaTypoSize'] ) ? (string) absint( $input['metaTypoSize'] ) : '',
				'unit' => 'px',
			),
			'height'         => '',
			'spacing'        => '',
			'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
		);
	}
	if ( ! empty( $input['metaColor'] ) ) {
		$attrs['metaColor'] = sanitize_text_field( $input['metaColor'] ); }
	if ( ! empty( $input['metaHoverColor'] ) ) {
		$attrs['metaHoverColor'] = sanitize_text_field( $input['metaHoverColor'] ); }

	/* ── Reply button styling ─────────────────────────────────────────── */
	if ( ! empty( $input['replyPadding'] ) ) {
		$attrs['replypadding'] = tpgb_mcp_pcmt_spacing( $input['replyPadding'] ); }
	if ( ! empty( $input['enableReplyTypo'] ) ) {
		$attrs['replyTypo'] = array(
			'openTypography' => 1,
			'size'           => array(
				'md'   => ! empty( $input['replyTypoSize'] ) ? (string) absint( $input['replyTypoSize'] ) : '',
				'unit' => 'px',
			),
			'height'         => '',
			'spacing'        => '',
			'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
		);
	}
	if ( ! empty( $input['replyColor'] ) && '#f18248' !== $input['replyColor'] ) {
		$attrs['replyColor'] = sanitize_text_field( $input['replyColor'] ); }
	if ( ! empty( $input['replyHoverColor'] ) && '#f18248' !== $input['replyHoverColor'] ) {
		$attrs['replyHoverColor'] = sanitize_text_field( $input['replyHoverColor'] ); }
	if ( ! empty( $input['replyBorder'] ) ) {
		$attrs['replyBorder'] = tpgb_mcp_pcmt_border( $input['replyBorder'] ); }
	if ( ! empty( $input['replyBorderHover'] ) ) {
		$attrs['replyBorderHover'] = tpgb_mcp_pcmt_border( $input['replyBorderHover'] ); }
	if ( ! empty( $input['replyBorderRadius'] ) ) {
		$attrs['replyBorderRadius'] = tpgb_mcp_pcmt_radius( $input['replyBorderRadius'] ); }
	if ( ! empty( $input['replyBorderRadiusHover'] ) ) {
		$attrs['replyBorderRadiusHover'] = tpgb_mcp_pcmt_radius( $input['replyBorderRadiusHover'] ); }
	if ( ! empty( $input['replyBgColor'] ) ) {
		$attrs['replyBg'] = tpgb_mcp_pcmt_bg_simple( $input['replyBgColor'] ); }
	if ( ! empty( $input['replyBgHoverColor'] ) ) {
		$attrs['replyBgHover'] = tpgb_mcp_pcmt_bg_simple( $input['replyBgHoverColor'] ); }
	if ( ! empty( $input['enableReplyShadow'] ) ) {
		$attrs['replyBoxShadow'] = array(
			'openShadow' => true,
			'horizontal' => (string) intval( $input['replyShadowH'] ?? 0 ),
			'vertical'   => (string) intval( $input['replyShadowV'] ?? 4 ),
			'blur'       => (string) absint( $input['replyShadowBlur'] ?? 8 ),
			'color'      => sanitize_text_field( $input['replyShadowColor'] ?? 'rgba(0,0,0,0.40)' ),
		);
	}
	if ( ! empty( $input['enableReplyShadowHover'] ) ) {
		$attrs['replyBoxShadowHover'] = array(
			'openShadow' => true,
			'horizontal' => (string) intval( $input['replyShadowHoverH'] ?? 0 ),
			'vertical'   => (string) intval( $input['replyShadowHoverV'] ?? 4 ),
			'blur'       => (string) absint( $input['replyShadowHoverBlur'] ?? 8 ),
			'color'      => sanitize_text_field( $input['replyShadowHoverColor'] ?? 'rgba(0,0,0,0.40)' ),
		);
	}

	/* ── Field styling ────────────────────────────────────────────────── */
	if ( ! empty( $input['enableFieldTypo'] ) ) {
		$attrs['fieldTypo'] = array(
			'openTypography' => 1,
			'size'           => array(
				'md'   => ! empty( $input['fieldTypoSize'] ) ? (string) absint( $input['fieldTypoSize'] ) : '',
				'unit' => 'px',
			),
			'height'         => '',
			'spacing'        => '',
			'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
		);
	}
	if ( ! empty( $input['fieldColor'] ) ) {
		$attrs['fieldColor'] = sanitize_text_field( $input['fieldColor'] ); }
	if ( ! empty( $input['fieldHoverColor'] ) ) {
		$attrs['fieldHoverColor'] = sanitize_text_field( $input['fieldHoverColor'] ); }
	if ( ! empty( $input['fieldPadding'] ) ) {
		$attrs['fieldpadding'] = tpgb_mcp_pcmt_spacing( $input['fieldPadding'] ); }
	if ( ! empty( $input['fieldBorder'] ) ) {
		$attrs['fieldBorder'] = tpgb_mcp_pcmt_border( $input['fieldBorder'] ); }
	if ( ! empty( $input['fieldBorderHover'] ) ) {
		$attrs['fieldBorderHover'] = tpgb_mcp_pcmt_border( $input['fieldBorderHover'] ); }
	if ( ! empty( $input['fieldBorderRadius'] ) ) {
		$attrs['fieldBorderRadius'] = tpgb_mcp_pcmt_radius( $input['fieldBorderRadius'] ); }
	if ( ! empty( $input['fieldBorderRadiusHover'] ) ) {
		$attrs['fieldBorderRadiusHover'] = tpgb_mcp_pcmt_radius( $input['fieldBorderRadiusHover'] ); }
	if ( ! empty( $input['fieldBgColor'] ) ) {
		$attrs['fieldBg'] = tpgb_mcp_pcmt_bg_simple( $input['fieldBgColor'] ); }
	if ( ! empty( $input['fieldBgHoverColor'] ) ) {
		$attrs['fieldBgHover'] = tpgb_mcp_pcmt_bg_simple( $input['fieldBgHoverColor'] ); }
	if ( ! empty( $input['enableFieldShadow'] ) ) {
		$attrs['fieldBoxShadow'] = array(
			'openShadow' => true,
			'horizontal' => (string) intval( $input['fieldShadowH'] ?? 0 ),
			'vertical'   => (string) intval( $input['fieldShadowV'] ?? 4 ),
			'blur'       => (string) absint( $input['fieldShadowBlur'] ?? 8 ),
			'color'      => sanitize_text_field( $input['fieldShadowColor'] ?? 'rgba(0,0,0,0.40)' ),
		);
	}

	/* ── Submit button styling ────────────────────────────────────────── */
	if ( ! empty( $input['enableBtnTypo'] ) ) {
		$attrs['btnTypo'] = array(
			'openTypography' => 1,
			'size'           => array(
				'md'   => ! empty( $input['btnTypoSize'] ) ? (string) absint( $input['btnTypoSize'] ) : '',
				'unit' => 'px',
			),
			'height'         => '',
			'spacing'        => '',
			'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
		);
	}
	if ( ! empty( $input['btnColor'] ) ) {
		$attrs['btnColor'] = sanitize_text_field( $input['btnColor'] ); }
	if ( ! empty( $input['btnHoverColor'] ) ) {
		$attrs['btnHoverColor'] = sanitize_text_field( $input['btnHoverColor'] ); }
	if ( ! empty( $input['btnPadding'] ) ) {
		$attrs['btnpadding'] = tpgb_mcp_pcmt_spacing( $input['btnPadding'] ); }
	if ( ! empty( $input['btnBorder'] ) ) {
		$attrs['btnBorder'] = tpgb_mcp_pcmt_border( $input['btnBorder'] ); }
	if ( ! empty( $input['btnBorderHover'] ) ) {
		$attrs['btnBorderHover'] = tpgb_mcp_pcmt_border( $input['btnBorderHover'] ); }
	if ( ! empty( $input['btnBorderRadius'] ) ) {
		$attrs['btnBorderRadius'] = tpgb_mcp_pcmt_radius( $input['btnBorderRadius'] ); }
	if ( ! empty( $input['btnBorderRadiusHover'] ) ) {
		$attrs['btnBorderRadiusHover'] = tpgb_mcp_pcmt_radius( $input['btnBorderRadiusHover'] ); }
	if ( ! empty( $input['btnBgColor'] ) && '#6f14f1' !== $input['btnBgColor'] ) {
		$attrs['btnBg'] = tpgb_mcp_pcmt_bg( $input['btnBgColor'] ); }
	if ( ! empty( $input['btnBgHoverColor'] ) ) {
		$attrs['btnBgHover'] = tpgb_mcp_pcmt_bg_simple( $input['btnBgHoverColor'] ); }
	if ( ! empty( $input['enableBtnShadow'] ) ) {
		$attrs['btnBoxShadow'] = array(
			'openShadow' => true,
			'horizontal' => (string) intval( $input['btnShadowH'] ?? 0 ),
			'vertical'   => (string) intval( $input['btnShadowV'] ?? 4 ),
			'blur'       => (string) absint( $input['btnShadowBlur'] ?? 8 ),
			'color'      => sanitize_text_field( $input['btnShadowColor'] ?? 'rgba(0,0,0,0.40)' ),
		);
	}
	if ( ! empty( $input['enableBtnShadowHover'] ) ) {
		$attrs['btnBoxShadowHover'] = array(
			'openShadow' => true,
			'horizontal' => (string) intval( $input['btnShadowHoverH'] ?? 0 ),
			'vertical'   => (string) intval( $input['btnShadowHoverV'] ?? 4 ),
			'blur'       => (string) absint( $input['btnShadowHoverBlur'] ?? 8 ),
			'color'      => sanitize_text_field( $input['btnShadowHoverColor'] ?? 'rgba(0,0,0,0.40)' ),
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
		$attrs['globalMargin'] = tpgb_mcp_pcmt_spacing( $input['globalMargin'] );  }
	if ( ! empty( $input['globalPadding'] ) ) {
		$attrs['globalPadding'] = tpgb_mcp_pcmt_spacing( $input['globalPadding'] ); }
	if ( ! empty( $input['globalBgColor'] ) ) {
		$attrs['globalBg'] = tpgb_mcp_pcmt_bg( $input['globalBgColor'] );      }
	if ( ! empty( $input['globalBgHoverColor'] ) ) {
		$attrs['globalBgHover'] = tpgb_mcp_pcmt_bg( $input['globalBgHoverColor'] ); }
	if ( ! empty( $input['globalBorder'] ) ) {
		$attrs['globalBorder'] = tpgb_mcp_pcmt_border( $input['globalBorder'] );       }
	if ( ! empty( $input['globalBorderHover'] ) ) {
		$attrs['globalBorderHover'] = tpgb_mcp_pcmt_border( $input['globalBorderHover'] );  }
	if ( ! empty( $input['globalBRadius'] ) ) {
		$attrs['globalBRadius'] = tpgb_mcp_pcmt_radius( $input['globalBRadius'] );      }
	if ( ! empty( $input['globalBRadiusHover'] ) ) {
		$attrs['globalBRadiusHover'] = tpgb_mcp_pcmt_radius( $input['globalBRadiusHover'] ); }
	if ( ! empty( $input['globalBShadow'] ) ) {
		$attrs['globalBShadow'] = tpgb_mcp_pcmt_bshadow( $input['globalBShadow'] );      }
	if ( ! empty( $input['globalBShadowHover'] ) ) {
		$attrs['globalBShadowHover'] = tpgb_mcp_pcmt_bshadow( $input['globalBShadowHover'] ); }

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
	if ( tpgb_mcp_pcmt_needs_wrapper( $attrs ) ) {
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
