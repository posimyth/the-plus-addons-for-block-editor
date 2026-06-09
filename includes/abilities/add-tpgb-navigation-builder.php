<?php
/**
 * Ability: Add Nexter Blocks Navigation Builder (tpgb/tp-navigation-builder) to a Gutenberg page.
 *
 * @package The_Plus_Addons_For_Block_Editor
 * @since   1.3.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_register_ability(
	'nexter-blocks/add-tpgb-navigation-builder',
	array(
		'label'               => __( 'Add Nexter Blocks Navigation Builder', 'the-plus-addons-for-block-editor' ),
		'description'         => __( 'Adds the Nexter Blocks Navigation Builder block (tpgb/tp-navigation-builder) — a complete menu builder with horizontal/vertical/side layouts, multi-level menu items (with submenu depth), icons per item, hover/click activation, sticky menu, responsive hamburger/dropdown, custom toggle styles, and comprehensive styling for menu items (normal/hover/active), submenus, and responsive views. Can pull from existing WordPress menu by name or build a custom menu via items array. This is a dynamic block.', 'the-plus-addons-for-block-editor' ),
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

				/* ── Source ──────────────────────────────────────────────── */
				'menuSource'             => array(
					'type'        => 'string',
					'enum'        => array( 'custom', 'wp-menu' ),
					'description' => '"custom" = use menuItems array; "wp-menu" = use WordPress menu by name.',
					'default'     => 'custom',
				),
				'wpMenuName'             => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'WordPress menu name (when menuSource is "wp-menu").',
				),

				/* ── Menu items (custom) ──────────────────────────────────── */
				'menuItems'              => array(
					'type'        => 'array',
					'description' => 'Array of menu items. Each item: {label, url, target, nofollow, depth (0=top, 1=submenu, 2=sub-submenu), icon, iconType}.',
					'items'       => array(
						'type'       => 'object',
						'properties' => array(
							'label'    => array( 'type' => 'string' ),
							'url'      => array( 'type' => 'string' ),
							'target'   => array(
								'type'    => 'string',
								'enum'    => array( '_self', '_blank' ),
								'default' => '_self',
							),
							'nofollow' => array(
								'type'    => 'boolean',
								'default' => false,
							),
							'depth'    => array(
								'type'        => 'integer',
								'default'     => 0,
								'description' => 'Nesting depth. 0 = top-level; 1+ = submenu.',
							),
							'icon'     => array(
								'type'        => 'string',
								'description' => 'Font Awesome class (prefix icon).',
							),
						),
					),
				),

				/* ── Layout & Effect ──────────────────────────────────────── */
				'menuLayout'             => array(
					'type'        => 'string',
					'enum'        => array( 'horizontal', 'vertical', 'side' ),
					'description' => 'Menu layout direction.',
					'default'     => 'horizontal',
				),
				'menuEffect'             => array(
					'type'        => 'string',
					'default'     => 'style-1',
					'description' => 'Menu hover effect preset e.g. style-1 through style-10+.',
				),
				'hoverOrClick'           => array(
					'type'    => 'string',
					'enum'    => array( 'hover', 'click' ),
					'default' => 'hover',
				),
				'menuAlignment'          => array(
					'type'    => 'string',
					'enum'    => array( 'text-left', 'text-center', 'text-right' ),
					'default' => 'text-center',
				),
				'menuWidth'              => array(
					'type'    => 'string',
					'enum'    => array( 'full', 'auto' ),
					'default' => 'full',
				),

				/* ── Side menu settings ───────────────────────────────────── */
				'showSideTitle'          => array(
					'type'        => 'boolean',
					'default'     => true,
					'description' => 'Show navigation title bar (side layout).',
				),
				'sideEvent'              => array(
					'type'    => 'string',
					'enum'    => array( 'normal', 'onclick' ),
					'default' => 'normal',
				),
				'navTitle'               => array(
					'type'    => 'string',
					'default' => 'Navigation Menu',
				),
				'navTitleLinkUrl'        => array(
					'type'    => 'string',
					'default' => '#',
				),
				'sideNavWidth'           => array(
					'type'        => 'string',
					'default'     => '240',
					'description' => 'Side nav width in px.',
				),

				/* ── Sticky ───────────────────────────────────────────────── */
				'stickyMenu'             => array(
					'type'    => 'boolean',
					'default' => false,
				),

				/* ── Responsive ───────────────────────────────────────────── */
				'enableResponsive'       => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'responsiveMenuType'     => array(
					'type'    => 'string',
					'enum'    => array( 'toggle', 'dropdown' ),
					'default' => 'toggle',
				),
				'mobileMenuType'         => array(
					'type'    => 'string',
					'enum'    => array( 'standard', 'offcanvas' ),
					'default' => 'standard',
				),
				'closeOnClick'           => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'responsiveBreakpoint'   => array(
					'type'    => 'string',
					'default' => '991',
				),
				'toggleStyle'            => array(
					'type'    => 'string',
					'default' => 'style-1',
				),
				'toggleAlignment'        => array(
					'type'    => 'string',
					'enum'    => array( 'text-left', 'text-center', 'text-right' ),
					'default' => 'text-left',
				),
				'openIcon'               => array(
					'type'    => 'string',
					'default' => 'fas fa-bars',
				),
				'closeIcon'              => array(
					'type'    => 'string',
					'default' => 'fas fa-times',
				),

				/* ── Typography ───────────────────────────────────────────── */
				'enableMenuTypo'         => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'menuTypoSize'           => array(
					'type'    => 'integer',
					'default' => 0,
				),

				/* ── Item spacing ─────────────────────────────────────────── */
				'itemOuterPadding'       => array(
					'type'        => 'object',
					'description' => 'Outer padding between items.',
				),
				'itemInnerPadding'       => array(
					'type'        => 'object',
					'description' => 'Inner padding of each item.',
				),

				/* ── Item icon ────────────────────────────────────────────── */
				'iconStyle'              => array(
					'type'    => 'string',
					'enum'    => array( 'none', 'left', 'right', 'top' ),
					'default' => 'none',
				),
				'iconSize'               => array(
					'type'    => 'integer',
					'default' => 0,
				),

				/* ── Menu item colours (3 states) ─────────────────────────── */
				'menuColor'              => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Menu text colour (normal).',
				),
				'menuHoverColor'         => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Menu text colour (hover).',
				),
				'menuActiveColor'        => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Menu text colour (active).',
				),
				'menuIconColor'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'menuIconHoverColor'     => array(
					'type'    => 'string',
					'default' => '',
				),
				'menuIconActiveColor'    => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Menu item backgrounds (3 states) ─────────────────────── */
				'menuBgColor'            => array(
					'type'    => 'string',
					'default' => '',
				),
				'menuBgHoverColor'       => array(
					'type'    => 'string',
					'default' => '',
				),
				'menuBgActiveColor'      => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Menu item borders (3 states) ─────────────────────────── */
				'menuBorder'             => array( 'type' => 'object' ),
				'menuBorderHover'        => array( 'type' => 'object' ),
				'menuBorderActive'       => array( 'type' => 'object' ),
				'menuBorderRadius'       => array( 'type' => 'object' ),
				'menuBorderRadiusHover'  => array( 'type' => 'object' ),
				'menuBorderRadiusActive' => array( 'type' => 'object' ),

				/* ── Submenu styling ──────────────────────────────────────── */
				'enableSubmenuTypo'      => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'submenuTypoSize'        => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'submenuInnerPadding'    => array( 'type' => 'object' ),
				'submenuIndicator'       => array(
					'type'    => 'string',
					'enum'    => array( 'none', 'left', 'right', 'top', 'bottom' ),
					'default' => 'none',
				),
				'submenuBorder'          => array( 'type' => 'object' ),
				'submenuBorderRadius'    => array( 'type' => 'object' ),
				'submenuBgColor'         => array(
					'type'    => 'string',
					'default' => '',
				),
				'submenuColor'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'submenuHoverColor'      => array(
					'type'    => 'string',
					'default' => '',
				),
				'submenuActiveColor'     => array(
					'type'    => 'string',
					'default' => '',
				),
				'submenuAlignment'       => array(
					'type'    => 'string',
					'enum'    => array( 'left', 'center', 'right' ),
					'default' => 'left',
				),

				/* ── Accessibility ────────────────────────────────────────── */
				'enableAccessibility'    => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Enable keyboard focus styles.',
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

				'settings'               => array(
					'type'        => 'object',
					'description' => 'Raw attribute overrides for any of the 181 internal attributes.',
				),
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

		'execute_callback'    => 'tpgb_mcp_add_navbuilder_ability',
		'permission_callback' => 'tpgb_mcp_add_navbuilder_permission',
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
 * Permission callback for the add-navigation-builder ability.
 *
 * @param array|null $input Ability input arguments.
 * @return bool True when the current user may insert the block.
 */
function tpgb_mcp_add_navbuilder_permission( ?array $input = null ): bool {
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
function tpgb_mcp_nav_spacing( array $v ): array {
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
function tpgb_mcp_nav_border( array $b ): array {
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
function tpgb_mcp_nav_radius( array $r ): array {
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
function tpgb_mcp_nav_bshadow( array $s ): array {
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
 * @param string $color     Background colour value.
 * @param bool   $with_open Whether to include the full openBg/videoSource shape.
 * @return array Background attribute structured for the block.
 */
function tpgb_mcp_nav_bg( string $color, bool $with_open = true ): array {
	if ( $with_open ) {
		return array(
			'openBg'         => 1,
			'bgType'         => 'color',
			'videoSource'    => 'local',
			'bgDefaultColor' => sanitize_text_field( $color ),
			'bgGradient'     => '',
			'isCustom'       => 'fpp',
		);
	}
	return array(
		'openBg'         => 1,
		'bgType'         => 'color',
		'bgDefaultColor' => sanitize_text_field( $color ),
	);
}
/**
 * Determine whether any wrapper-level (global/transform) attributes are set.
 *
 * @param array $attrs Block attributes built so far.
 * @return bool True when the block needs the tpgbDisrule wrapper enabled.
 */
function tpgb_mcp_nav_needs_wrapper( array $attrs ): bool {
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
 * Execute callback: insert a tpgb/tp-navigation-builder block into a post.
 *
 * @param array $input Ability input arguments.
 * @return array|WP_Error Ability result or error on failure.
 */
function tpgb_mcp_add_navbuilder_ability( array $input ) {

	if ( ! defined( 'TPGB_VERSION' ) && ! defined( 'TPGBP_VERSION' ) ) {
		return new WP_Error( 'nexter_blocks_missing', __( 'Nexter Blocks must be active.', 'the-plus-addons-for-block-editor' ) );
	}

	$block_name = 'tpgb/tp-navigation-builder';
	if ( ! tpgb_mcp_has_registered_block( $block_name ) ) {
		return new WP_Error( 'block_missing', __( 'tpgb/tp-navigation-builder is not registered.', 'the-plus-addons-for-block-editor' ) );
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

	/* ── Source ───────────────────────────────────────────────────────── */
	$source = sanitize_key( $input['menuSource'] ?? 'custom' );
	if ( 'wp-menu' === $source ) {
		$attrs['TypeMenu'] = 'menu';
		if ( ! empty( $input['wpMenuName'] ) ) {
			$attrs['menuName'] = sanitize_text_field( $input['wpMenuName'] ); }
	}

	/* ── Menu items (custom) ──────────────────────────────────────────── */
	if ( 'custom' === $source && ! empty( $input['menuItems'] ) && is_array( $input['menuItems'] ) ) {
		$items = array();
		foreach ( $input['menuItems'] as $i => $it ) {
			if ( ! is_array( $it ) ) {
				continue; }
			$items[] = array(
				'_key'       => substr( md5( (string) $i . uniqid() ), 0, 8 ),
				'depth'      => (string) intval( $it['depth'] ?? 0 ),
				'LinkType'   => 'custom',
				'LinkFilter' => array( 'filter' => array( 'label' => '' ) ),
				'preicon'    => sanitize_text_field( $it['icon'] ?? '' ),
				'menuiconTy' => ! empty( $it['icon'] ) ? 'icon' : '',
				'SmenuType'  => 'link',
				'title'      => sanitize_text_field( $it['label'] ?? '' ),
				'link'       => array(
					'url'      => esc_url_raw( $it['url'] ?? '#' ),
					'target'   => '_blank' === ( $it['target'] ?? '' ) ? '_blank' : '',
					'nofollow' => ! empty( $it['nofollow'] ) ? 'on' : '',
				),
			);
		}
		if ( ! empty( $items ) ) {
			$attrs['ItemMenu'] = $items; }
	}

	/* ── Layout & Effect ──────────────────────────────────────────────── */
	if ( ! empty( $input['menuLayout'] ) && 'horizontal' !== $input['menuLayout'] ) {
		$attrs['menuLayout'] = sanitize_key( $input['menuLayout'] ); }
	if ( ! empty( $input['menuEffect'] ) && 'style-1' !== $input['menuEffect'] ) {
		$attrs['menuEffect'] = sanitize_text_field( $input['menuEffect'] ); }
	if ( ! empty( $input['hoverOrClick'] ) && 'hover' !== $input['hoverOrClick'] ) {
		$attrs['HvrClick'] = sanitize_key( $input['hoverOrClick'] ); }
	if ( ! empty( $input['menuAlignment'] ) && 'text-center' !== $input['menuAlignment'] ) {
		$attrs['menuAlign'] = sanitize_text_field( $input['menuAlignment'] ); }
	if ( ! empty( $input['menuWidth'] ) && 'full' !== $input['menuWidth'] ) {
		$attrs['navwidth'] = sanitize_key( $input['menuWidth'] ); }

	/* ── Side menu ────────────────────────────────────────────────────── */
	if ( isset( $input['showSideTitle'] ) && ! $input['showSideTitle'] ) {
		$attrs['VtitleBar'] = false; }
	if ( ! empty( $input['sideEvent'] ) && 'normal' !== $input['sideEvent'] ) {
		$attrs['vSideevent'] = sanitize_key( $input['sideEvent'] ); }
	if ( ! empty( $input['navTitle'] ) && 'Navigation Menu' !== $input['navTitle'] ) {
		$attrs['navTitle'] = sanitize_text_field( $input['navTitle'] ); }
	if ( ! empty( $input['navTitleLinkUrl'] ) && '#' !== $input['navTitleLinkUrl'] ) {
		$attrs['titleLink'] = array(
			'url'      => esc_url_raw( $input['navTitleLinkUrl'] ),
			'target'   => '',
			'nofollow' => '',
		);
	}
	if ( ! empty( $input['sideNavWidth'] ) && '240' !== $input['sideNavWidth'] ) {
		$attrs['sidenavWidth'] = sanitize_text_field( $input['sideNavWidth'] ); }

	/* ── Sticky ───────────────────────────────────────────────────────── */
	if ( ! empty( $input['stickyMenu'] ) ) {
		$attrs['stickyMenu'] = true; }

	/* ── Responsive ───────────────────────────────────────────────────── */
	if ( ! empty( $input['enableResponsive'] ) ) {
		$attrs['respoMenu'] = true; }
	if ( ! empty( $input['responsiveMenuType'] ) && 'toggle' !== $input['responsiveMenuType'] ) {
		$attrs['resmenuType'] = sanitize_key( $input['responsiveMenuType'] ); }
	if ( ! empty( $input['mobileMenuType'] ) && 'standard' !== $input['mobileMenuType'] ) {
		$attrs['momenuType'] = sanitize_key( $input['mobileMenuType'] ); }
	if ( isset( $input['closeOnClick'] ) && ! $input['closeOnClick'] ) {
		$attrs['closeMenu'] = false; }
	if ( ! empty( $input['responsiveBreakpoint'] ) && '991' !== $input['responsiveBreakpoint'] ) {
		$attrs['menuSWidth'] = sanitize_text_field( $input['responsiveBreakpoint'] ); }
	if ( ! empty( $input['toggleStyle'] ) && 'style-1' !== $input['toggleStyle'] ) {
		$attrs['toggleStyle'] = sanitize_text_field( $input['toggleStyle'] ); }
	if ( ! empty( $input['toggleAlignment'] ) && 'text-left' !== $input['toggleAlignment'] ) {
		$attrs['toggleAlign'] = sanitize_text_field( $input['toggleAlignment'] ); }
	if ( ! empty( $input['openIcon'] ) && 'fas fa-bars' !== $input['openIcon'] ) {
		$attrs['openIcon'] = sanitize_text_field( $input['openIcon'] ); }
	if ( ! empty( $input['closeIcon'] ) && 'fas fa-times' !== $input['closeIcon'] ) {
		$attrs['closeIcon'] = sanitize_text_field( $input['closeIcon'] ); }

	/* ── Typography ───────────────────────────────────────────────────── */
	if ( ! empty( $input['enableMenuTypo'] ) ) {
		$attrs['menuTypo'] = array(
			'openTypography' => 1,
			'size'           => array(
				'md'   => ! empty( $input['menuTypoSize'] ) ? (string) absint( $input['menuTypoSize'] ) : '',
				'unit' => 'px',
			),
			'height'         => '',
			'spacing'        => '',
			'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
		);
	}

	/* ── Item spacing ─────────────────────────────────────────────────── */
	if ( ! empty( $input['itemOuterPadding'] ) ) {
		$attrs['outPadding'] = tpgb_mcp_nav_spacing( $input['itemOuterPadding'] ); }
	if ( ! empty( $input['itemInnerPadding'] ) ) {
		$attrs['inPadding'] = tpgb_mcp_nav_spacing( $input['itemInnerPadding'] ); }

	/* ── Item icon ────────────────────────────────────────────────────── */
	if ( ! empty( $input['iconStyle'] ) && 'none' !== $input['iconStyle'] ) {
		$attrs['iconStyle'] = sanitize_key( $input['iconStyle'] ); }
	if ( ! empty( $input['iconSize'] ) ) {
		$attrs['iconSize'] = array(
			'md'   => (string) absint( $input['iconSize'] ),
			'unit' => 'px',
		); }

	/* ── Menu item colours (3 states) ─────────────────────────────────── */
	if ( ! empty( $input['menuColor'] ) ) {
		$attrs['menuColor'] = sanitize_text_field( $input['menuColor'] ); }
	if ( ! empty( $input['menuHoverColor'] ) ) {
		$attrs['hvrColor'] = sanitize_text_field( $input['menuHoverColor'] ); }
	if ( ! empty( $input['menuActiveColor'] ) ) {
		$attrs['Actcolor'] = sanitize_text_field( $input['menuActiveColor'] ); }
	if ( ! empty( $input['menuIconColor'] ) ) {
		$attrs['indiColor'] = sanitize_text_field( $input['menuIconColor'] ); }
	if ( ! empty( $input['menuIconHoverColor'] ) ) {
		$attrs['hvrindiColor'] = sanitize_text_field( $input['menuIconHoverColor'] ); }
	if ( ! empty( $input['menuIconActiveColor'] ) ) {
		$attrs['actindiColor'] = sanitize_text_field( $input['menuIconActiveColor'] ); }

	/* ── Menu backgrounds (3 states) ──────────────────────────────────── */
	if ( ! empty( $input['menuBgColor'] ) ) {
		$attrs['normalBgtype'] = tpgb_mcp_nav_bg( $input['menuBgColor'], false ); }
	if ( ! empty( $input['menuBgHoverColor'] ) ) {
		$attrs['HvrBgtype'] = tpgb_mcp_nav_bg( $input['menuBgHoverColor'], false ); }
	if ( ! empty( $input['menuBgActiveColor'] ) ) {
		$attrs['actBgtype'] = tpgb_mcp_nav_bg( $input['menuBgActiveColor'], false ); }

	/* ── Menu borders (3 states) ──────────────────────────────────────── */
	if ( ! empty( $input['menuBorder'] ) ) {
		$attrs['menuBorder'] = tpgb_mcp_nav_border( $input['menuBorder'] ); }
	if ( ! empty( $input['menuBorderHover'] ) ) {
		$attrs['hvrBorder'] = tpgb_mcp_nav_border( $input['menuBorderHover'] ); }
	if ( ! empty( $input['menuBorderActive'] ) ) {
		$attrs['actBorder'] = tpgb_mcp_nav_border( $input['menuBorderActive'] ); }
	if ( ! empty( $input['menuBorderRadius'] ) ) {
		$attrs['norBradius'] = tpgb_mcp_nav_radius( $input['menuBorderRadius'] ); }
	if ( ! empty( $input['menuBorderRadiusHover'] ) ) {
		$attrs['HvrBradius'] = tpgb_mcp_nav_radius( $input['menuBorderRadiusHover'] ); }
	if ( ! empty( $input['menuBorderRadiusActive'] ) ) {
		$attrs['actBradius'] = tpgb_mcp_nav_radius( $input['menuBorderRadiusActive'] ); }

	/* ── Submenu styling ──────────────────────────────────────────────── */
	if ( ! empty( $input['enableSubmenuTypo'] ) ) {
		$attrs['submenuTypo'] = array(
			'openTypography' => 1,
			'size'           => array(
				'md'   => ! empty( $input['submenuTypoSize'] ) ? (string) absint( $input['submenuTypoSize'] ) : '',
				'unit' => 'px',
			),
			'height'         => '',
			'spacing'        => '',
			'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
		);
	}
	if ( ! empty( $input['submenuInnerPadding'] ) ) {
		$attrs['subinPadding'] = tpgb_mcp_nav_spacing( $input['submenuInnerPadding'] ); }
	if ( ! empty( $input['submenuIndicator'] ) && 'none' !== $input['submenuIndicator'] ) {
		$attrs['subMenuindi'] = sanitize_key( $input['submenuIndicator'] ); }
	if ( ! empty( $input['submenuBorder'] ) ) {
		$attrs['SmenuBorder'] = tpgb_mcp_nav_border( $input['submenuBorder'] ); }
	if ( ! empty( $input['submenuBorderRadius'] ) ) {
		$attrs['subBradius'] = tpgb_mcp_nav_radius( $input['submenuBorderRadius'] ); }
	if ( ! empty( $input['submenuBgColor'] ) ) {
		$attrs['subBgtype'] = tpgb_mcp_nav_bg( $input['submenuBgColor'], false ); }
	if ( ! empty( $input['submenuColor'] ) ) {
		$attrs['submenuColor'] = sanitize_text_field( $input['submenuColor'] ); }
	if ( ! empty( $input['submenuHoverColor'] ) ) {
		$attrs['shvrColor'] = sanitize_text_field( $input['submenuHoverColor'] ); }
	if ( ! empty( $input['submenuActiveColor'] ) ) {
		$attrs['sActcolor'] = sanitize_text_field( $input['submenuActiveColor'] ); }
	if ( ! empty( $input['submenuAlignment'] ) && 'left' !== $input['submenuAlignment'] ) {
		$attrs['submenuAlign'] = sanitize_key( $input['submenuAlignment'] ); }

	/* ── Accessibility ────────────────────────────────────────────────── */
	if ( ! empty( $input['enableAccessibility'] ) ) {
		$attrs['accessWeb'] = true; }

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
		$attrs['globalMargin'] = tpgb_mcp_nav_spacing( $input['globalMargin'] );  }
	if ( ! empty( $input['globalPadding'] ) ) {
		$attrs['globalPadding'] = tpgb_mcp_nav_spacing( $input['globalPadding'] ); }
	if ( ! empty( $input['globalBgColor'] ) ) {
		$attrs['globalBg'] = tpgb_mcp_nav_bg( $input['globalBgColor'] );      }
	if ( ! empty( $input['globalBgHoverColor'] ) ) {
		$attrs['globalBgHover'] = tpgb_mcp_nav_bg( $input['globalBgHoverColor'] ); }
	if ( ! empty( $input['globalBorder'] ) ) {
		$attrs['globalBorder'] = tpgb_mcp_nav_border( $input['globalBorder'] );       }
	if ( ! empty( $input['globalBorderHover'] ) ) {
		$attrs['globalBorderHover'] = tpgb_mcp_nav_border( $input['globalBorderHover'] );  }
	if ( ! empty( $input['globalBRadius'] ) ) {
		$attrs['globalBRadius'] = tpgb_mcp_nav_radius( $input['globalBRadius'] );      }
	if ( ! empty( $input['globalBRadiusHover'] ) ) {
		$attrs['globalBRadiusHover'] = tpgb_mcp_nav_radius( $input['globalBRadiusHover'] ); }
	if ( ! empty( $input['globalBShadow'] ) ) {
		$attrs['globalBShadow'] = tpgb_mcp_nav_bshadow( $input['globalBShadow'] );      }
	if ( ! empty( $input['globalBShadowHover'] ) ) {
		$attrs['globalBShadowHover'] = tpgb_mcp_nav_bshadow( $input['globalBShadowHover'] ); }

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
	if ( tpgb_mcp_nav_needs_wrapper( $attrs ) ) {
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
