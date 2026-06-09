<?php
/**
 * Ability: Add Nexter Blocks Message Box (tpgb/tp-messagebox) to a Gutenberg page.
 *
 * @package The_Plus_Addons_For_Block_Editor
 * @since   1.3.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_register_ability(
	'nexter-blocks/add-tpgb-messagebox',
	array(
		'label'               => __( 'Add Nexter Blocks Message Box', 'the-plus-addons-for-block-editor' ),
		'description'         => __( 'Adds the Nexter Blocks Message Box block (tpgb/tp-messagebox) — an alert/notification/callout box with title, optional description, optional icon (prefix/suffix), dismiss button, arrow pointer, and comprehensive styling for icon, dismiss button, title/description, and box. Use for alerts, warnings, success messages, info callouts, or announcements. This is a dynamic block.', 'the-plus-addons-for-block-editor' ),
		'category'            => 'nexter-blocks',

		'input_schema'        => array(
			'type'                 => 'object',
			'properties'           => array(

				/* ── Core ─────────────────────────────────────────────────── */
				'post_id'                  => array( 'type' => 'integer' ),
				'position'                 => array(
					'type'    => 'integer',
					'default' => -1,
				),
				'parent_block_id'          => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Content ──────────────────────────────────────────────── */
				'title'                    => array(
					'type'        => 'string',
					'description' => 'Main message title/alert text.',
					'default'     => 'Special Alert message for you. Got it?',
				),
				'enableDescription'        => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Show description text below title.',
				),
				'description'              => array(
					'type'        => 'string',
					'description' => 'Additional description text.',
					'default'     => '',
				),

				/* ── Icon ─────────────────────────────────────────────────── */
				'showIcon'                 => array(
					'type'        => 'boolean',
					'default'     => true,
					'description' => 'Show icon on the left/right.',
				),
				'iconPosition'             => array(
					'type'        => 'string',
					'enum'        => array( 'prefix', 'suffix' ),
					'default'     => 'prefix',
					'description' => '"prefix" = left of title; "suffix" = right of title.',
				),
				'iconClass'                => array(
					'type'        => 'string',
					'default'     => 'fas fa-exclamation',
					'description' => 'Font Awesome icon class.',
				),

				/* ── Dismiss button ───────────────────────────────────────── */
				'showDismiss'              => array(
					'type'        => 'boolean',
					'default'     => true,
					'description' => 'Show dismiss/close button.',
				),
				'dismissIcon'              => array(
					'type'        => 'string',
					'default'     => 'far fa-times-circle',
					'description' => 'Dismiss button icon class.',
				),

				/* ── Arrow pointer ────────────────────────────────────────── */
				'showArrow'                => array(
					'type'        => 'boolean',
					'default'     => true,
					'description' => 'Show arrow/pointer below the message box.',
				),

				/* ── Title styling ────────────────────────────────────────── */
				'enableTitleTypo'          => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'titleTypoSize'            => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'titleColor'               => array(
					'type'    => 'string',
					'default' => '',
				),
				'titleHoverColor'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'titleAdjust'              => array(
					'type'        => 'object',
					'description' => 'Title position adjust (padding).',
				),
				'titleMargin'              => array(
					'type'        => 'object',
					'description' => 'Title margin.',
				),
				'titleBgColor'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'titleBgHoverColor'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'enableTitleShadow'        => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'titleShadowH'             => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'titleShadowV'             => array(
					'type'    => 'integer',
					'default' => 4,
				),
				'titleShadowBlur'          => array(
					'type'    => 'integer',
					'default' => 8,
				),
				'titleShadowSpread'        => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'titleShadowColor'         => array(
					'type'    => 'string',
					'default' => 'rgba(0,0,0,0.40)',
				),

				/* ── Description styling ──────────────────────────────────── */
				'enableDescTypo'           => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'descTypoSize'             => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'descColor'                => array(
					'type'    => 'string',
					'default' => '',
				),
				'descHoverColor'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'descAdjust'               => array( 'type' => 'object' ),
				'descMargin'               => array( 'type' => 'object' ),
				'descBgColor'              => array(
					'type'    => 'string',
					'default' => '',
				),
				'descBgHoverColor'         => array(
					'type'    => 'string',
					'default' => '',
				),
				'descBorderRadius'         => array( 'type' => 'object' ),
				'descBorderRadiusHover'    => array( 'type' => 'object' ),

				/* ── Icon styling ─────────────────────────────────────────── */
				'iconSize'                 => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'iconWidth'                => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'iconColor'                => array(
					'type'    => 'string',
					'default' => '',
				),
				'iconHoverColor'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'iconBgColor'              => array(
					'type'    => 'string',
					'default' => '',
				),
				'iconBgHoverColor'         => array(
					'type'    => 'string',
					'default' => '',
				),
				'arrowColor'               => array(
					'type'    => 'string',
					'default' => '',
				),
				'arrowHoverColor'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'iconBorder'               => array( 'type' => 'object' ),
				'iconBorderHover'          => array( 'type' => 'object' ),
				'iconBorderRadius'         => array( 'type' => 'object' ),
				'iconBorderRadiusHover'    => array( 'type' => 'object' ),
				'enableIconShadow'         => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'iconShadowH'              => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'iconShadowV'              => array(
					'type'    => 'integer',
					'default' => 4,
				),
				'iconShadowBlur'           => array(
					'type'    => 'integer',
					'default' => 8,
				),
				'iconShadowSpread'         => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'iconShadowColor'          => array(
					'type'    => 'string',
					'default' => 'rgba(0,0,0,0.40)',
				),

				/* ── Dismiss icon styling ─────────────────────────────────── */
				'dismissSize'              => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'dismissWidth'             => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'dismissColor'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'dismissHoverColor'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'dismissBgColor'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'dismissBgHoverColor'      => array(
					'type'    => 'string',
					'default' => '',
				),
				'dismissBorderRadius'      => array( 'type' => 'object' ),
				'dismissBorderRadiusHover' => array( 'type' => 'object' ),
				'dismissMargin'            => array( 'type' => 'object' ),

				/* ── Box styling ──────────────────────────────────────────── */
				'boxPadding'               => array( 'type' => 'object' ),
				'boxBgColor'               => array(
					'type'    => 'string',
					'default' => '',
				),
				'boxBgHoverColor'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'boxBorder'                => array( 'type' => 'object' ),
				'boxBorderHover'           => array( 'type' => 'object' ),
				'boxBorderRadius'          => array( 'type' => 'object' ),
				'boxBorderRadiusHover'     => array( 'type' => 'object' ),
				'enableBoxShadow'          => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'boxShadowH'               => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'boxShadowV'               => array(
					'type'    => 'integer',
					'default' => 4,
				),
				'boxShadowBlur'            => array(
					'type'    => 'integer',
					'default' => 8,
				),
				'boxShadowSpread'          => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'boxShadowColor'           => array(
					'type'    => 'string',
					'default' => 'rgba(0,0,0,0.40)',
				),

				/* ── Scroll animation ────────────────────────────────────── */
				'scrollAnimation'          => array(
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
				'animDuration'             => array(
					'type'    => 'string',
					'enum'    => array( '', 'slow', 'normal', 'fast' ),
					'default' => '',
				),
				'animDelay'                => array(
					'type'    => 'string',
					'default' => '',
				),
				'animEasing'               => array(
					'type'    => 'string',
					'enum'    => array( '', 'linear', 'ease', 'ease-in', 'ease-out', 'ease-in-out' ),
					'default' => '',
				),

				/* ── Advanced ─────────────────────────────────────────────── */
				'hideDesktop'              => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'hideTablet'               => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'hideMobile'               => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'globalClasses'            => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalId'                 => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalCustomCss'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalWidth'              => array(
					'type'    => 'string',
					'enum'    => array( '', 'inline', 'full', 'custom' ),
					'default' => '',
				),
				'globalZindex'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalPosition'           => array(
					'type'    => 'string',
					'enum'    => array( '', 'relative', 'absolute', 'fixed', 'sticky' ),
					'default' => '',
				),
				'transitionDuration'       => array(
					'type'    => 'string',
					'default' => '',
				),
				'transitionFunction'       => array(
					'type'    => 'string',
					'enum'    => array( '', 'ease', 'ease-in', 'ease-out', 'ease-in-out', 'linear' ),
					'default' => '',
				),
				'transitionOrigin'         => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalMargin'             => array( 'type' => 'object' ),
				'globalPadding'            => array( 'type' => 'object' ),
				'globalBgColor'            => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalBgHoverColor'       => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalBorder'             => array( 'type' => 'object' ),
				'globalBorderHover'        => array( 'type' => 'object' ),
				'globalBRadius'            => array( 'type' => 'object' ),
				'globalBRadiusHover'       => array( 'type' => 'object' ),
				'globalBShadow'            => array( 'type' => 'object' ),
				'globalBShadowHover'       => array( 'type' => 'object' ),

				'rotateDeg'                => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotateX'                  => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotateY'                  => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotatePerspective'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotateDegHover'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotateXHover'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotateYHover'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotatePersHover'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'offsetX'                  => array(
					'type'    => 'string',
					'default' => '',
				),
				'offsetY'                  => array(
					'type'    => 'string',
					'default' => '',
				),
				'offsetZ'                  => array(
					'type'    => 'string',
					'default' => '',
				),
				'offsetXHover'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'offsetYHover'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'offsetZHover'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'scaleValue'               => array(
					'type'    => 'string',
					'default' => '',
				),
				'scaleKeepRatio'           => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'scaleValueHover'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'skewX'                    => array(
					'type'    => 'string',
					'default' => '',
				),
				'skewY'                    => array(
					'type'    => 'string',
					'default' => '',
				),
				'skewXHover'               => array(
					'type'    => 'string',
					'default' => '',
				),
				'skewYHover'               => array(
					'type'    => 'string',
					'default' => '',
				),
				'flipHorizontal'           => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'flipVertical'             => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'flipHorizontalHover'      => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'flipVerticalHover'        => array(
					'type'    => 'boolean',
					'default' => false,
				),

				'settings'                 => array( 'type' => 'object' ),
				'fontFamily'               => array(
					'type'        => 'string',
					'description' => 'Font family name (e.g. "Inter", "Roboto", "Playfair Display"). When inspecting a URL via nexter-blocks/inspect-page, pass the returned fonts[].family value verbatim.',
					'default'     => '',
				),
				'fontType'                 => array(
					'type'        => 'string',
					'description' => 'Font category — "sans-serif", "serif", "display", "handwriting", or "monospace".',
					'default'     => '',
				),
				'customFont'               => array(
					'type'        => 'string',
					'description' => 'Custom (non-Google) font name. Overrides fontFamily.',
					'default'     => '',
				),
				'fontWeight'               => array(
					'type'        => 'string',
					'description' => 'Font weight as a string ("100"..."900"). Embedded inside every typography group\'s fontFamily.fontWeight on this block. For per-group control, use the settings raw override or sprout/update-element.',
					'default'     => '',
				),
				'textDecoration'           => array(
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

		'execute_callback'    => 'tpgb_mcp_add_messagebox_ability',
		'permission_callback' => 'tpgb_mcp_add_messagebox_permission',
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
 * Permission callback for the add-messagebox ability.
 *
 * @param array|null $input Ability input arguments.
 * @return bool True when the current user may insert the block.
 */
function tpgb_mcp_add_messagebox_permission( ?array $input = null ): bool {
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
function tpgb_mcp_mbx_spacing( array $v ): array {
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
function tpgb_mcp_mbx_border( array $b ): array {
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
function tpgb_mcp_mbx_radius( array $r ): array {
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
function tpgb_mcp_mbx_bshadow( array $s ): array {
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
function tpgb_mcp_mbx_bg( string $color ): array {
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
function tpgb_mcp_mbx_bg_simple( string $color ): array {
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
function tpgb_mcp_mbx_needs_wrapper( array $attrs ): bool {
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
 * Execute callback: insert a tpgb/tp-messagebox block into a post.
 *
 * @param array $input Ability input arguments.
 * @return array|WP_Error Ability result or error on failure.
 */
function tpgb_mcp_add_messagebox_ability( array $input ) {

	if ( ! defined( 'TPGB_VERSION' ) && ! defined( 'TPGBP_VERSION' ) ) {
		return new WP_Error( 'nexter_blocks_missing', __( 'Nexter Blocks must be active.', 'the-plus-addons-for-block-editor' ) );
	}

	$block_name = 'tpgb/tp-messagebox';
	if ( ! tpgb_mcp_has_registered_block( $block_name ) ) {
		return new WP_Error( 'block_missing', __( 'tpgb/tp-messagebox is not registered.', 'the-plus-addons-for-block-editor' ) );
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
	if ( ! empty( $input['title'] ) && 'Special Alert message for you. Got it?' !== $input['title'] ) {
		$attrs['Title'] = tpgb_mcp_clean_text( $input['title'] );
	}
	if ( ! empty( $input['enableDescription'] ) ) {
		$attrs['Description'] = true;
		$default_desc         = 'I Am Text Block. Click Edit Button To Change This Text. Lorem Ipsum Dolor Sit Amet, Consectetur Adipiscing Elit. Ut Elit Tellus, Luctus Nec Ullamcorper Mattis, Pulvinar Dapibus Leo.';
		if ( isset( $input['description'] ) && $default_desc !== $input['description'] ) {
			$attrs['descText'] = tpgb_mcp_clean_text( $input['description'] );
		}
	}

	/* ── Icon ─────────────────────────────────────────────────────────── */
	if ( isset( $input['showIcon'] ) && ! $input['showIcon'] ) {
		$attrs['icon'] = false; }
	if ( ! empty( $input['iconPosition'] ) && 'prefix' !== $input['iconPosition'] ) {
		$attrs['icnPosition'] = sanitize_key( $input['iconPosition'] ); }
	if ( ! empty( $input['iconClass'] ) && 'fas fa-exclamation' !== $input['iconClass'] ) {
		$attrs['IconName'] = sanitize_text_field( $input['iconClass'] );
	}

	/* ── Dismiss ──────────────────────────────────────────────────────── */
	if ( isset( $input['showDismiss'] ) && ! $input['showDismiss'] ) {
		$attrs['dismiss'] = false; }
	if ( ! empty( $input['dismissIcon'] ) && 'far fa-times-circle' !== $input['dismissIcon'] ) {
		$attrs['dismsIcon'] = sanitize_text_field( $input['dismissIcon'] );
	}

	/* ── Arrow ────────────────────────────────────────────────────────── */
	if ( isset( $input['showArrow'] ) && ! $input['showArrow'] ) {
		$attrs['msgArrow'] = false; }

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
		$attrs['titleNmlColor'] = sanitize_text_field( $input['titleColor'] ); }
	if ( ! empty( $input['titleHoverColor'] ) ) {
		$attrs['titleHvrColor'] = sanitize_text_field( $input['titleHoverColor'] ); }
	if ( ! empty( $input['titleAdjust'] ) ) {
		$attrs['titleAdjust'] = tpgb_mcp_mbx_spacing( $input['titleAdjust'] ); }
	if ( ! empty( $input['titleMargin'] ) ) {
		$attrs['titleMargin'] = tpgb_mcp_mbx_spacing( $input['titleMargin'] ); }
	if ( ! empty( $input['titleBgColor'] ) ) {
		$attrs['titleNmlBG'] = tpgb_mcp_mbx_bg( $input['titleBgColor'] ); }
	if ( ! empty( $input['titleBgHoverColor'] ) ) {
		$attrs['titleHvrBG'] = tpgb_mcp_mbx_bg( $input['titleBgHoverColor'] ); }
	if ( ! empty( $input['enableTitleShadow'] ) ) {
		$attrs['titleNmlShadow'] = array(
			'openShadow' => true,
			'inset'      => 0,
			'horizontal' => (string) intval( $input['titleShadowH'] ?? 0 ),
			'vertical'   => (string) intval( $input['titleShadowV'] ?? 4 ),
			'blur'       => (string) absint( $input['titleShadowBlur'] ?? 8 ),
			'spread'     => (string) intval( $input['titleShadowSpread'] ?? 0 ),
			'color'      => sanitize_text_field( $input['titleShadowColor'] ?? 'rgba(0,0,0,0.40)' ),
		);
	}

	/* ── Description styling ──────────────────────────────────────────── */
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
		$attrs['descNmlColor'] = sanitize_text_field( $input['descColor'] ); }
	if ( ! empty( $input['descHoverColor'] ) ) {
		$attrs['descHvrColor'] = sanitize_text_field( $input['descHoverColor'] ); }
	if ( ! empty( $input['descAdjust'] ) ) {
		$attrs['descAdjust'] = tpgb_mcp_mbx_spacing( $input['descAdjust'] ); }
	if ( ! empty( $input['descMargin'] ) ) {
		$attrs['descMargin'] = tpgb_mcp_mbx_spacing( $input['descMargin'] ); }
	if ( ! empty( $input['descBgColor'] ) ) {
		$attrs['descNmlBG'] = tpgb_mcp_mbx_bg( $input['descBgColor'] ); }
	if ( ! empty( $input['descBgHoverColor'] ) ) {
		$attrs['descHvrBG'] = tpgb_mcp_mbx_bg( $input['descBgHoverColor'] ); }
	if ( ! empty( $input['descBorderRadius'] ) ) {
		$attrs['descNmlBRadius'] = tpgb_mcp_mbx_radius( $input['descBorderRadius'] ); }
	if ( ! empty( $input['descBorderRadiusHover'] ) ) {
		$attrs['descHvrBRadius'] = tpgb_mcp_mbx_radius( $input['descBorderRadiusHover'] ); }

	/* ── Icon styling ─────────────────────────────────────────────────── */
	if ( ! empty( $input['iconSize'] ) ) {
		$attrs['iconSize'] = array( 'md' => (string) absint( $input['iconSize'] ) ); }
	if ( ! empty( $input['iconWidth'] ) ) {
		$attrs['iconWidth'] = array( 'md' => (string) absint( $input['iconWidth'] ) ); }
	if ( ! empty( $input['iconColor'] ) ) {
		$attrs['iconNormalColor'] = sanitize_text_field( $input['iconColor'] ); }
	if ( ! empty( $input['iconHoverColor'] ) ) {
		$attrs['iconHoverColor'] = sanitize_text_field( $input['iconHoverColor'] ); }
	if ( ! empty( $input['iconBgColor'] ) ) {
		$attrs['bgNormalColor'] = sanitize_text_field( $input['iconBgColor'] ); }
	if ( ! empty( $input['iconBgHoverColor'] ) ) {
		$attrs['bgHoverColor'] = sanitize_text_field( $input['iconBgHoverColor'] ); }
	if ( ! empty( $input['arrowColor'] ) ) {
		$attrs['arrowNormalColor'] = sanitize_text_field( $input['arrowColor'] ); }
	if ( ! empty( $input['arrowHoverColor'] ) ) {
		$attrs['arrowHoverColor'] = sanitize_text_field( $input['arrowHoverColor'] ); }
	if ( ! empty( $input['iconBorder'] ) ) {
		$attrs['iconNmlBorder'] = tpgb_mcp_mbx_border( $input['iconBorder'] ); }
	if ( ! empty( $input['iconBorderHover'] ) ) {
		$attrs['iconHvrBorder'] = tpgb_mcp_mbx_border( $input['iconBorderHover'] ); }
	if ( ! empty( $input['iconBorderRadius'] ) ) {
		$attrs['iconBdrNmlRadius'] = tpgb_mcp_mbx_radius( $input['iconBorderRadius'] ); }
	if ( ! empty( $input['iconBorderRadiusHover'] ) ) {
		$attrs['iconBdrHvrRadius'] = tpgb_mcp_mbx_radius( $input['iconBorderRadiusHover'] ); }
	if ( ! empty( $input['enableIconShadow'] ) ) {
		$attrs['nmlIconShadow'] = array(
			'openShadow' => true,
			'inset'      => 0,
			'horizontal' => (string) intval( $input['iconShadowH'] ?? 0 ),
			'vertical'   => (string) intval( $input['iconShadowV'] ?? 4 ),
			'blur'       => (string) absint( $input['iconShadowBlur'] ?? 8 ),
			'spread'     => (string) intval( $input['iconShadowSpread'] ?? 0 ),
			'color'      => sanitize_text_field( $input['iconShadowColor'] ?? 'rgba(0,0,0,0.40)' ),
		);
	}

	/* ── Dismiss icon styling ─────────────────────────────────────────── */
	if ( ! empty( $input['dismissSize'] ) ) {
		$attrs['dIconSize'] = array( 'md' => (string) absint( $input['dismissSize'] ) ); }
	if ( ! empty( $input['dismissWidth'] ) ) {
		$attrs['dIconWidth'] = array( 'md' => (string) absint( $input['dismissWidth'] ) ); }
	if ( ! empty( $input['dismissColor'] ) ) {
		$attrs['dIconNmlColor'] = sanitize_text_field( $input['dismissColor'] ); }
	if ( ! empty( $input['dismissHoverColor'] ) ) {
		$attrs['dIconHvrColor'] = sanitize_text_field( $input['dismissHoverColor'] ); }
	if ( ! empty( $input['dismissBgColor'] ) ) {
		$attrs['dIconNmlBG'] = sanitize_text_field( $input['dismissBgColor'] ); }
	if ( ! empty( $input['dismissBgHoverColor'] ) ) {
		$attrs['dIconHvrBG'] = sanitize_text_field( $input['dismissBgHoverColor'] ); }
	if ( ! empty( $input['dismissBorderRadius'] ) ) {
		$attrs['dIconNmlBRadius'] = tpgb_mcp_mbx_radius( $input['dismissBorderRadius'] ); }
	if ( ! empty( $input['dismissBorderRadiusHover'] ) ) {
		$attrs['dIconHvrBRadius'] = tpgb_mcp_mbx_radius( $input['dismissBorderRadiusHover'] ); }
	if ( ! empty( $input['dismissMargin'] ) ) {
		$attrs['dIconMargin'] = tpgb_mcp_mbx_spacing( $input['dismissMargin'] ); }

	/* ── Box styling ──────────────────────────────────────────────────── */
	if ( ! empty( $input['boxPadding'] ) ) {
		$attrs['bgPadding'] = tpgb_mcp_mbx_spacing( $input['boxPadding'] ); }
	if ( ! empty( $input['boxBgColor'] ) ) {
		$attrs['normalBG'] = tpgb_mcp_mbx_bg( $input['boxBgColor'] ); }
	if ( ! empty( $input['boxBgHoverColor'] ) ) {
		$attrs['HoverBG'] = tpgb_mcp_mbx_bg( $input['boxBgHoverColor'] ); }
	if ( ! empty( $input['boxBorder'] ) ) {
		$attrs['bgNmlBorder'] = tpgb_mcp_mbx_border( $input['boxBorder'] ); }
	if ( ! empty( $input['boxBorderHover'] ) ) {
		$attrs['bgHvrBorder'] = tpgb_mcp_mbx_border( $input['boxBorderHover'] ); }
	if ( ! empty( $input['boxBorderRadius'] ) ) {
		$attrs['boxBdrNmlRadius'] = tpgb_mcp_mbx_radius( $input['boxBorderRadius'] ); }
	if ( ! empty( $input['boxBorderRadiusHover'] ) ) {
		$attrs['boxBdrHvrRadius'] = tpgb_mcp_mbx_radius( $input['boxBorderRadiusHover'] ); }
	if ( ! empty( $input['enableBoxShadow'] ) ) {
		$attrs['nmlboxShadow'] = array(
			'openShadow' => true,
			'inset'      => 0,
			'horizontal' => (string) intval( $input['boxShadowH'] ?? 0 ),
			'vertical'   => (string) intval( $input['boxShadowV'] ?? 4 ),
			'blur'       => (string) absint( $input['boxShadowBlur'] ?? 8 ),
			'spread'     => (string) intval( $input['boxShadowSpread'] ?? 0 ),
			'color'      => sanitize_text_field( $input['boxShadowColor'] ?? 'rgba(0,0,0,0.40)' ),
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
		$attrs['globalMargin'] = tpgb_mcp_mbx_spacing( $input['globalMargin'] );  }
	if ( ! empty( $input['globalPadding'] ) ) {
		$attrs['globalPadding'] = tpgb_mcp_mbx_spacing( $input['globalPadding'] ); }
	if ( ! empty( $input['globalBgColor'] ) ) {
		$attrs['globalBg'] = tpgb_mcp_mbx_bg_simple( $input['globalBgColor'] );      }
	if ( ! empty( $input['globalBgHoverColor'] ) ) {
		$attrs['globalBgHover'] = tpgb_mcp_mbx_bg_simple( $input['globalBgHoverColor'] ); }
	if ( ! empty( $input['globalBorder'] ) ) {
		$attrs['globalBorder'] = tpgb_mcp_mbx_border( $input['globalBorder'] );       }
	if ( ! empty( $input['globalBorderHover'] ) ) {
		$attrs['globalBorderHover'] = tpgb_mcp_mbx_border( $input['globalBorderHover'] );  }
	if ( ! empty( $input['globalBRadius'] ) ) {
		$attrs['globalBRadius'] = tpgb_mcp_mbx_radius( $input['globalBRadius'] );      }
	if ( ! empty( $input['globalBRadiusHover'] ) ) {
		$attrs['globalBRadiusHover'] = tpgb_mcp_mbx_radius( $input['globalBRadiusHover'] ); }
	if ( ! empty( $input['globalBShadow'] ) ) {
		$attrs['globalBShadow'] = tpgb_mcp_mbx_bshadow( $input['globalBShadow'] );      }
	if ( ! empty( $input['globalBShadowHover'] ) ) {
		$attrs['globalBShadowHover'] = tpgb_mcp_mbx_bshadow( $input['globalBShadowHover'] ); }

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
	if ( tpgb_mcp_mbx_needs_wrapper( $attrs ) ) {
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
