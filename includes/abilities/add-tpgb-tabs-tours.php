<?php
/**
 * Ability: Add Nexter Blocks Tabs Tours (tpgb/tp-tabs-tours) to a Gutenberg page.
 *
 * @package The_Plus_Addons_For_Block_Editor
 * @since   1.3.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_register_ability(
	'nexter-blocks/add-tpgb-tabs-tours',
	array(
		'label'               => __( 'Add Nexter Blocks Tabs Tours', 'the-plus-addons-for-block-editor' ),
		'description'         => __( 'Adds the Nexter Blocks Tabs Tours block (tpgb/tp-tabs-tours) — multi-tab/tour navigation panel where each tab has a title, optional inline content or saved Gutenberg template, optional inner & outer FA icon (or image). Supports horizontal/vertical layout, nav position (top/bottom/left/right), swiper effect, equal-width navs, columned vertical nav, mobile-accordion fallback, active-tab default, full styling for nav buttons (normal+active), nav bar wrapper (normal+hover), tab content area, underline indicator, and per-icon controls. This is a dynamic block.', 'the-plus-addons-for-block-editor' ),
		'category'            => 'nexter-blocks',

		'input_schema'        => array(
			'type'                 => 'object',
			'properties'           => array(

				/* ── Core ─────────────────────────────────────────────────── */
				'post_id'                 => array( 'type' => 'integer' ),
				'position'                => array(
					'type'    => 'integer',
					'default' => -1,
				),
				'parent_block_id'         => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Tabs array ───────────────────────────────────────────── */
				'tabs'                    => array(
					'type'        => 'array',
					'description' => 'List of tabs. Each: { title, contentType (content|editor|template), description, blockTemplate, innerIcon, outerIcon, iconType (font_awesome|image), faIcon, outerFaIcon, imageUrl, imageId, imageSize, backendVisi }.',
					'items'       => array(
						'type'       => 'object',
						'properties' => array(
							'title'         => array( 'type' => 'string' ),
							'contentType'   => array(
								'type' => 'string',
								'enum' => array( 'content', 'editor', 'template' ),
							),
							'description'   => array( 'type' => 'string' ),
							'blockTemplate' => array(
								'type'        => 'string',
								'description' => 'Saved template ID (when contentType = template).',
							),
							'innerIcon'     => array( 'type' => 'boolean' ),
							'outerIcon'     => array( 'type' => 'boolean' ),
							'iconType'      => array(
								'type' => 'string',
								'enum' => array( 'font_awesome', 'image' ),
							),
							'faIcon'        => array(
								'type'        => 'string',
								'description' => 'Inner FA class.',
							),
							'outerFaIcon'   => array(
								'type'        => 'string',
								'description' => 'Outer FA class.',
							),
							'imageUrl'      => array( 'type' => 'string' ),
							'imageId'       => array( 'type' => 'integer' ),
							'imageSize'     => array( 'type' => 'string' ),
							'backendVisi'   => array( 'type' => 'boolean' ),
						),
					),
				),

				/* ── Layout ───────────────────────────────────────────────── */
				'tabType'                 => array(
					'type'        => 'string',
					'enum'        => array( '', 'modern' ),
					'default'     => '',
					'description' => 'Set "modern" for modern variant.',
				),
				'tabLayout'               => array(
					'type'    => 'string',
					'enum'    => array( 'horizontal', 'vertical' ),
					'default' => 'horizontal',
				),
				'navPosition'             => array(
					'type'    => 'string',
					'enum'    => array( 'top', 'bottom', 'left', 'right' ),
					'default' => 'top',
				),
				'swiperEffect'            => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'navAlign'                => array(
					'type'    => 'string',
					'enum'    => array( 'text-left', 'text-center', 'text-right' ),
					'default' => 'text-center',
				),
				'verticalAlign'           => array(
					'type'    => 'string',
					'enum'    => array( 'flex-start', 'center', 'flex-end' ),
					'default' => 'center',
				),
				'verticalNavWidth'        => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Vertical nav width in px.',
				),
				'navWidth'                => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Stretch nav full width.',
				),
				'navEqualWidth'           => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'titleShow'               => array(
					'type'        => 'boolean',
					'default'     => true,
					'description' => 'Show tab title (set false to keep icons only).',
				),
				'tabCol'                  => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Enable column grid in vertical nav.',
				),
				'colSize'                 => array(
					'type'        => 'integer',
					'default'     => 1,
					'description' => 'Number of columns in vertical nav (when tabCol=true).',
				),
				'colPad'                  => array(
					'type'        => 'object',
					'description' => 'Padding inside each column item.',
				),
				'tabnavResp'              => array(
					'type'        => 'string',
					'enum'        => array( '', 'desktop', 'tablet', 'mobile' ),
					'default'     => '',
					'description' => 'Switch to mobile-accordion at this breakpoint.',
				),
				'activeTab'               => array(
					'type'        => 'string',
					'default'     => '1',
					'description' => 'Index of initially active tab (1-based).',
				),
				'showBlockContent'        => array(
					'type'    => 'boolean',
					'default' => true,
				),

				/* ── Icons ────────────────────────────────────────────────── */
				'iconSize'                => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Tab icon size (px).',
				),
				'iconColor'               => array(
					'type'    => 'string',
					'default' => '',
				),
				'iconColorActive'         => array(
					'type'    => 'string',
					'default' => '',
				),
				'iconSpacing'             => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Space between icon and label (px).',
				),
				'fullwidthIcon'           => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Stack icon above label (full-width).',
				),

				/* ── Title typography & colours ───────────────────────────── */
				'titleTypoSize'           => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'titleColor'              => array(
					'type'    => 'string',
					'default' => '',
				),
				'titleColorActive'        => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Underline indicator ──────────────────────────────────── */
				'underline'               => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'ulineColor'              => array(
					'type'    => 'string',
					'default' => '',
				),
				'lineMargin'              => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Underline top margin (px).',
				),
				'lineWidth'               => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Underline width (px).',
				),
				'lineHeight'              => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Underline thickness (px).',
				),

				/* ── Tab button (per-item) ────────────────────────────────── */
				'tabMargin'               => array( 'type' => 'object' ),
				'tabPadding'              => array( 'type' => 'object' ),
				'navSpace'                => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Space between adjacent tab buttons (px).',
				),
				'navBtnSpace'             => array(
					'type'        => 'integer',
					'default'     => 1,
					'description' => 'Vertical row gap when nav-one-by-one (px).',
				),
				'tabBorder'               => array( 'type' => 'object' ),
				'tabBorderRadius'         => array( 'type' => 'object' ),
				'tabBorderActive'         => array( 'type' => 'object' ),
				'tabBorderRadiusActive'   => array( 'type' => 'object' ),
				'tabBgColor'              => array(
					'type'    => 'string',
					'default' => '',
				),
				'tabBgColorActive'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'tabShadow'               => array( 'type' => 'object' ),
				'tabShadowActive'         => array( 'type' => 'object' ),

				/* ── Nav bar wrapper ──────────────────────────────────────── */
				'navbarMargin'            => array( 'type' => 'object' ),
				'navbarPadding'           => array( 'type' => 'object' ),
				'navBorder'               => array( 'type' => 'object' ),
				'navBorderRadius'         => array( 'type' => 'object' ),
				'navBorderHover'          => array( 'type' => 'object' ),
				'navBorderRadiusHover'    => array( 'type' => 'object' ),
				'navBgColor'              => array(
					'type'    => 'string',
					'default' => '',
				),
				'navBgColorHover'         => array(
					'type'    => 'string',
					'default' => '',
				),
				'navShadow'               => array( 'type' => 'object' ),
				'navShadowHover'          => array( 'type' => 'object' ),

				/* ── Content area ─────────────────────────────────────────── */
				'descTypoSize'            => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'descColor'               => array(
					'type'    => 'string',
					'default' => '',
				),
				'descMargin'              => array( 'type' => 'object' ),
				'descPadding'             => array( 'type' => 'object' ),
				'descBorder'              => array( 'type' => 'object' ),
				'descBorderRadius'        => array( 'type' => 'object' ),
				'descBgColor'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'descShadow'              => array( 'type' => 'object' ),

				/* ── Mobile accordion ─────────────────────────────────────── */
				'accorBorder'             => array( 'type' => 'object' ),
				'accorBorderRadius'       => array( 'type' => 'object' ),
				'accorBgColor'            => array(
					'type'    => 'string',
					'default' => '',
				),
				'accorShadow'             => array( 'type' => 'object' ),
				'accorBorderActive'       => array( 'type' => 'object' ),
				'accorBorderRadiusActive' => array( 'type' => 'object' ),
				'accorBgColorActive'      => array(
					'type'    => 'string',
					'default' => '',
				),
				'accorShadowActive'       => array( 'type' => 'object' ),

				/* ── Visibility / Globals ─────────────────────────────────── */
				'hideDesktop'             => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'hideTablet'              => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'hideMobile'              => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'globalClasses'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalId'                => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalCustomCss'         => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalWidth'             => array(
					'type'    => 'string',
					'enum'    => array( '', 'inline', 'full', 'custom' ),
					'default' => '',
				),
				'globalZindex'            => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalPosition'          => array(
					'type'    => 'string',
					'enum'    => array( '', 'relative', 'absolute', 'fixed', 'sticky' ),
					'default' => '',
				),
				'globalMargin'            => array( 'type' => 'object' ),
				'globalPadding'           => array( 'type' => 'object' ),
				'globalBgColor'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalBgHoverColor'      => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalBorder'            => array( 'type' => 'object' ),
				'globalBorderHover'       => array( 'type' => 'object' ),
				'globalBRadius'           => array( 'type' => 'object' ),
				'globalBRadiusHover'      => array( 'type' => 'object' ),
				'globalBShadow'           => array( 'type' => 'object' ),
				'globalBShadowHover'      => array( 'type' => 'object' ),
				'scrollAnimation'         => array(
					'type'    => 'string',
					'default' => '',
				),
				'animDuration'            => array(
					'type'    => 'string',
					'enum'    => array( '', 'slow', 'normal', 'fast' ),
					'default' => '',
				),
				'animDelay'               => array(
					'type'    => 'string',
					'default' => '',
				),
				'animEasing'              => array(
					'type'    => 'string',
					'enum'    => array( '', 'linear', 'ease', 'ease-in', 'ease-out', 'ease-in-out' ),
					'default' => '',
				),

				'settings'                => array(
					'type'        => 'object',
					'description' => 'Raw attribute overrides.',
				),
				'fontFamily'              => array(
					'type'        => 'string',
					'description' => 'Font family name (e.g. "Inter", "Roboto", "Playfair Display"). When inspecting a URL via nexter-blocks/inspect-page, pass the returned fonts[].family value verbatim.',
					'default'     => '',
				),
				'fontType'                => array(
					'type'        => 'string',
					'description' => 'Font category — "sans-serif", "serif", "display", "handwriting", or "monospace".',
					'default'     => '',
				),
				'customFont'              => array(
					'type'        => 'string',
					'description' => 'Custom (non-Google) font name. Overrides fontFamily.',
					'default'     => '',
				),
				'fontWeight'              => array(
					'type'        => 'string',
					'description' => 'Font weight as a string ("100"..."900"). Embedded inside every typography group\'s fontFamily.fontWeight on this block. For per-group control, use the settings raw override or sprout/update-element.',
					'default'     => '',
				),
				'textDecoration'          => array(
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

		'execute_callback'    => 'tpgb_mcp_add_tabs_tours_ability',
		'permission_callback' => 'tpgb_mcp_add_tabs_tours_permission',
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
 * Permission callback for the add-tabs-tours ability.
 *
 * @param array|null $input Ability input arguments.
 * @return bool True when the current user may insert the block.
 */
function tpgb_mcp_add_tabs_tours_permission( ?array $input = null ): bool {
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
function tpgb_mcp_tt_spacing( array $v ): array {
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
function tpgb_mcp_tt_border( array $b ): array {
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
function tpgb_mcp_tt_radius( array $r ): array {
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
function tpgb_mcp_tt_bshadow( array $s ): array {
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
function tpgb_mcp_tt_bg( string $color ): array {
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
function tpgb_mcp_tt_typo( int $size ): array {
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
function tpgb_mcp_tt_needs_wrapper( array $attrs ): bool {
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
 * Execute callback for the add-tabs-tours ability.
 *
 * @param array $input Ability input arguments.
 * @return array|WP_Error Result payload or WP_Error on failure.
 */
function tpgb_mcp_add_tabs_tours_ability( array $input ) {

	if ( ! defined( 'TPGB_VERSION' ) && ! defined( 'TPGBP_VERSION' ) ) {
		return new WP_Error( 'nexter_blocks_missing', __( 'Nexter Blocks must be active.', 'the-plus-addons-for-block-editor' ) );
	}

	$block_name = 'tpgb/tp-tabs-tours';
	if ( ! tpgb_mcp_has_registered_block( $block_name ) ) {
		return new WP_Error( 'block_missing', __( 'tpgb/tp-tabs-tours is not registered.', 'the-plus-addons-for-block-editor' ) );
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

	/* ── Tabs ─────────────────────────────────────────────────────────── */
	if ( ! empty( $input['tabs'] ) && is_array( $input['tabs'] ) ) {
		$rows = array();
		foreach ( $input['tabs'] as $i => $t ) {
			$rows[] = array(
				'_key'           => (string) $i,
				'tabTitle'       => sanitize_text_field( $t['title'] ?? ( 'Tab ' . ( $i + 1 ) ) ),
				'contentType'    => sanitize_key( $t['contentType'] ?? 'content' ),
				'tabDescription' => tpgb_mcp_clean_text( $t['description'] ?? '' ),
				'blockTemp'      => sanitize_text_field( (string) ( $t['blockTemplate'] ?? '' ) ),
				'backendVisi'    => isset( $t['backendVisi'] ) ? (bool) $t['backendVisi'] : true,
				'innerIcon'      => ! empty( $t['innerIcon'] ),
				'outerIcon'      => ! empty( $t['outerIcon'] ),
				'iconFonts'      => sanitize_key( $t['iconType'] ?? 'font_awesome' ),
				'innericonName'  => sanitize_text_field( $t['faIcon'] ?? 'fas fa-home' ),
				'outericonName'  => sanitize_text_field( $t['outerFaIcon'] ?? 'fas fa-home' ),
				'iconImage'      => array(
					'url' => esc_url_raw( $t['imageUrl'] ?? '' ),
					'id'  => absint( $t['imageId'] ?? 0 ),
				),
				'iconimageSize'  => sanitize_text_field( $t['imageSize'] ?? 'full' ),
				'uniqueId'       => '',
			);
		}
		$attrs['tablistRepeater'] = $rows;
	}

	/* ── Layout ───────────────────────────────────────────────────────── */
	if ( ! empty( $input['tabType'] ) ) {
		$attrs['tabType'] = sanitize_text_field( $input['tabType'] ); }
	if ( ! empty( $input['tabLayout'] ) && 'horizontal' !== $input['tabLayout'] ) {
		$attrs['tabLayout'] = sanitize_key( $input['tabLayout'] ); }
	if ( ! empty( $input['navPosition'] ) && 'top' !== $input['navPosition'] ) {
		$attrs['navPosition'] = sanitize_key( $input['navPosition'] ); }
	if ( ! empty( $input['swiperEffect'] ) ) {
		$attrs['swiperEffect'] = true; }
	if ( ! empty( $input['navAlign'] ) && 'text-center' !== $input['navAlign'] ) {
		$attrs['navAlign'] = sanitize_text_field( $input['navAlign'] ); }
	if ( ! empty( $input['verticalAlign'] ) && 'center' !== $input['verticalAlign'] ) {
		$attrs['VerticalAlign'] = sanitize_text_field( $input['verticalAlign'] ); }
	if ( ! empty( $input['verticalNavWidth'] ) ) {
		$attrs['vernavWidth'] = array(
			'md'   => (string) absint( $input['verticalNavWidth'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['navWidth'] ) ) {
		$attrs['navWidth'] = true; }
	if ( ! empty( $input['navEqualWidth'] ) ) {
		$attrs['navequalwidth'] = true; }
	if ( isset( $input['titleShow'] ) && false === $input['titleShow'] ) {
		$attrs['titleShow'] = false; }
	if ( ! empty( $input['tabCol'] ) ) {
		$attrs['tabCol'] = true;
		if ( ! empty( $input['colSize'] ) ) {
			$attrs['colSize'] = array( 'md' => (string) absint( $input['colSize'] ) ); }
		if ( ! empty( $input['colPad'] ) ) {
			$attrs['colPad'] = tpgb_mcp_tt_spacing( $input['colPad'] ); }
	}
	if ( ! empty( $input['tabnavResp'] ) ) {
		$attrs['tabnavResp'] = sanitize_text_field( $input['tabnavResp'] ); }
	if ( ! empty( $input['activeTab'] ) && '1' !== (string) $input['activeTab'] ) {
		$attrs['activeTab'] = sanitize_text_field( (string) $input['activeTab'] ); }
	if ( isset( $input['showBlockContent'] ) && false === $input['showBlockContent'] ) {
		$attrs['showBlockContent'] = false; }

	/* ── Icons ────────────────────────────────────────────────────────── */
	if ( ! empty( $input['iconSize'] ) ) {
		$attrs['iconSize'] = array(
			'md'   => (string) absint( $input['iconSize'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['iconColor'] ) ) {
		$attrs['iconColor'] = sanitize_text_field( $input['iconColor'] ); }
	if ( ! empty( $input['iconColorActive'] ) ) {
		$attrs['iconActcolor'] = sanitize_text_field( $input['iconColorActive'] ); }
	if ( ! empty( $input['iconSpacing'] ) ) {
		$attrs['iconSpacing'] = array(
			'md'   => (string) absint( $input['iconSpacing'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['fullwidthIcon'] ) ) {
		$attrs['fullwidthIcon'] = true; }

	/* ── Title typography ─────────────────────────────────────────────── */
	if ( ! empty( $input['titleTypoSize'] ) ) {
		$attrs['titleTypo'] = tpgb_mcp_tt_typo( (int) $input['titleTypoSize'] ); }
	if ( ! empty( $input['titleColor'] ) ) {
		$attrs['titleColor'] = sanitize_text_field( $input['titleColor'] ); }
	if ( ! empty( $input['titleColorActive'] ) ) {
		$attrs['titleActcolor'] = sanitize_text_field( $input['titleColorActive'] ); }

	/* ── Underline ────────────────────────────────────────────────────── */
	if ( ! empty( $input['underline'] ) ) {
		$attrs['underline'] = true;
		if ( ! empty( $input['ulineColor'] ) ) {
			$attrs['ulineColor'] = sanitize_text_field( $input['ulineColor'] ); }
		if ( ! empty( $input['lineMargin'] ) ) {
			$attrs['lineMargin'] = array(
				'md'   => (string) absint( $input['lineMargin'] ),
				'unit' => 'px',
			); }
		if ( ! empty( $input['lineWidth'] ) ) {
			$attrs['lineWidth'] = array(
				'md'   => (string) absint( $input['lineWidth'] ),
				'unit' => 'px',
			); }
		if ( ! empty( $input['lineHeight'] ) ) {
			$attrs['lineHeight'] = array(
				'md'   => (string) absint( $input['lineHeight'] ),
				'unit' => 'px',
			); }
	}

	/* ── Tab buttons ──────────────────────────────────────────────────── */
	if ( ! empty( $input['tabMargin'] ) ) {
		$attrs['tabMargin'] = tpgb_mcp_tt_spacing( $input['tabMargin'] ); }
	if ( ! empty( $input['tabPadding'] ) ) {
		$attrs['tabPadding'] = tpgb_mcp_tt_spacing( $input['tabPadding'] ); }
	if ( ! empty( $input['navSpace'] ) ) {
		$attrs['navSpace'] = array(
			'md'   => (string) absint( $input['navSpace'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['navBtnSpace'] ) && intval( $input['navBtnSpace'] ) !== 1 ) {
		$attrs['navBtnSpace'] = array(
			'md'   => (string) absint( $input['navBtnSpace'] ),
			'unit' => 'px',
		);
	}
	if ( ! empty( $input['tabBorder'] ) ) {
		$attrs['tabBorder'] = tpgb_mcp_tt_border( $input['tabBorder'] ); }
	if ( ! empty( $input['tabBorderRadius'] ) ) {
		$attrs['normalBradius'] = tpgb_mcp_tt_radius( $input['tabBorderRadius'] ); }
	if ( ! empty( $input['tabBorderActive'] ) ) {
		$attrs['tabActborder'] = tpgb_mcp_tt_border( $input['tabBorderActive'] ); }
	if ( ! empty( $input['tabBorderRadiusActive'] ) ) {
		$attrs['actBradius'] = tpgb_mcp_tt_radius( $input['tabBorderRadiusActive'] ); }
	if ( ! empty( $input['tabBgColor'] ) ) {
		$attrs['tabbgType'] = tpgb_mcp_tt_bg( $input['tabBgColor'] ); }
	if ( ! empty( $input['tabBgColorActive'] ) ) {
		$attrs['acttabBgtype'] = tpgb_mcp_tt_bg( $input['tabBgColorActive'] ); }
	if ( ! empty( $input['tabShadow'] ) ) {
		$attrs['tabNBshadow'] = tpgb_mcp_tt_bshadow( $input['tabShadow'] ); }
	if ( ! empty( $input['tabShadowActive'] ) ) {
		$attrs['tabActBshadow'] = tpgb_mcp_tt_bshadow( $input['tabShadowActive'] ); }

	/* ── Nav bar ──────────────────────────────────────────────────────── */
	if ( ! empty( $input['navbarMargin'] ) ) {
		$attrs['navbarMargin'] = tpgb_mcp_tt_spacing( $input['navbarMargin'] ); }
	if ( ! empty( $input['navbarPadding'] ) ) {
		$attrs['navbarPadding'] = tpgb_mcp_tt_spacing( $input['navbarPadding'] ); }
	if ( ! empty( $input['navBorder'] ) ) {
		$attrs['navBoder'] = tpgb_mcp_tt_border( $input['navBorder'] ); }
	if ( ! empty( $input['navBorderRadius'] ) ) {
		$attrs['navNBradius'] = tpgb_mcp_tt_radius( $input['navBorderRadius'] ); }
	if ( ! empty( $input['navBorderHover'] ) ) {
		$attrs['navhvrBorder'] = tpgb_mcp_tt_border( $input['navBorderHover'] ); }
	if ( ! empty( $input['navBorderRadiusHover'] ) ) {
		$attrs['navhvrBradius'] = tpgb_mcp_tt_radius( $input['navBorderRadiusHover'] ); }
	if ( ! empty( $input['navBgColor'] ) ) {
		$attrs['navbgType'] = tpgb_mcp_tt_bg( $input['navBgColor'] ); }
	if ( ! empty( $input['navBgColorHover'] ) ) {
		$attrs['navhvrBgtype'] = tpgb_mcp_tt_bg( $input['navBgColorHover'] ); }
	if ( ! empty( $input['navShadow'] ) ) {
		$attrs['navNBshadow'] = tpgb_mcp_tt_bshadow( $input['navShadow'] ); }
	if ( ! empty( $input['navShadowHover'] ) ) {
		$attrs['navhvrBshadow'] = tpgb_mcp_tt_bshadow( $input['navShadowHover'] ); }

	/* ── Content area ─────────────────────────────────────────────────── */
	if ( ! empty( $input['descTypoSize'] ) ) {
		$attrs['descTypo'] = tpgb_mcp_tt_typo( (int) $input['descTypoSize'] ); }
	if ( ! empty( $input['descColor'] ) ) {
		$attrs['descColor'] = sanitize_text_field( $input['descColor'] ); }
	if ( ! empty( $input['descMargin'] ) ) {
		$attrs['descMargin'] = tpgb_mcp_tt_spacing( $input['descMargin'] ); }
	if ( ! empty( $input['descPadding'] ) ) {
		$attrs['descPadding'] = tpgb_mcp_tt_spacing( $input['descPadding'] ); }
	if ( ! empty( $input['descBorder'] ) ) {
		$attrs['descBorder'] = tpgb_mcp_tt_border( $input['descBorder'] ); }
	if ( ! empty( $input['descBorderRadius'] ) ) {
		$attrs['descBRedius'] = tpgb_mcp_tt_radius( $input['descBorderRadius'] ); }
	if ( ! empty( $input['descBgColor'] ) ) {
		$attrs['descbgType'] = tpgb_mcp_tt_bg( $input['descBgColor'] ); }
	if ( ! empty( $input['descShadow'] ) ) {
		$attrs['descboxShadow'] = tpgb_mcp_tt_bshadow( $input['descShadow'] ); }

	/* ── Mobile accordion ─────────────────────────────────────────────── */
	if ( ! empty( $input['accorBorder'] ) ) {
		$attrs['accorBorder'] = tpgb_mcp_tt_border( $input['accorBorder'] ); }
	if ( ! empty( $input['accorBorderRadius'] ) ) {
		$attrs['accorBredius'] = tpgb_mcp_tt_radius( $input['accorBorderRadius'] ); }
	if ( ! empty( $input['accorBgColor'] ) ) {
		$attrs['accorbgType'] = tpgb_mcp_tt_bg( $input['accorBgColor'] ); }
	if ( ! empty( $input['accorShadow'] ) ) {
		$attrs['accorboxShadow'] = tpgb_mcp_tt_bshadow( $input['accorShadow'] ); }
	if ( ! empty( $input['accorBorderActive'] ) ) {
		$attrs['ActaccorBorder'] = tpgb_mcp_tt_border( $input['accorBorderActive'] ); }
	if ( ! empty( $input['accorBorderRadiusActive'] ) ) {
		$attrs['accorBActredius'] = tpgb_mcp_tt_radius( $input['accorBorderRadiusActive'] ); }
	if ( ! empty( $input['accorBgColorActive'] ) ) {
		$attrs['ActaccorBgtype'] = tpgb_mcp_tt_bg( $input['accorBgColorActive'] ); }
	if ( ! empty( $input['accorShadowActive'] ) ) {
		$attrs['ActaccorBshadow'] = tpgb_mcp_tt_bshadow( $input['accorShadowActive'] ); }

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
		$attrs['globalMargin'] = tpgb_mcp_tt_spacing( $input['globalMargin'] ); }
	if ( ! empty( $input['globalPadding'] ) ) {
		$attrs['globalPadding'] = tpgb_mcp_tt_spacing( $input['globalPadding'] ); }
	if ( ! empty( $input['globalBgColor'] ) ) {
		$attrs['globalBg'] = tpgb_mcp_tt_bg( $input['globalBgColor'] ); }
	if ( ! empty( $input['globalBgHoverColor'] ) ) {
		$attrs['globalBgHover'] = tpgb_mcp_tt_bg( $input['globalBgHoverColor'] ); }
	if ( ! empty( $input['globalBorder'] ) ) {
		$attrs['globalBorder'] = tpgb_mcp_tt_border( $input['globalBorder'] ); }
	if ( ! empty( $input['globalBorderHover'] ) ) {
		$attrs['globalBorderHover'] = tpgb_mcp_tt_border( $input['globalBorderHover'] ); }
	if ( ! empty( $input['globalBRadius'] ) ) {
		$attrs['globalBRadius'] = tpgb_mcp_tt_radius( $input['globalBRadius'] ); }
	if ( ! empty( $input['globalBRadiusHover'] ) ) {
		$attrs['globalBRadiusHover'] = tpgb_mcp_tt_radius( $input['globalBRadiusHover'] ); }
	if ( ! empty( $input['globalBShadow'] ) ) {
		$attrs['globalBShadow'] = tpgb_mcp_tt_bshadow( $input['globalBShadow'] ); }
	if ( ! empty( $input['globalBShadowHover'] ) ) {
		$attrs['globalBShadowHover'] = tpgb_mcp_tt_bshadow( $input['globalBShadowHover'] ); }
	if ( ! empty( $input['scrollAnimation'] ) ) {
		$attrs['globalAnim'] = array( 'md' => sanitize_text_field( $input['scrollAnimation'] ) ); }
	if ( ! empty( $input['animDuration'] ) ) {
		$attrs['globalAnimDuration'] = sanitize_text_field( $input['animDuration'] ); }
	if ( ! empty( $input['animDelay'] ) ) {
		$attrs['globalAnimDelay'] = array( 'md' => sanitize_text_field( $input['animDelay'] ) ); }
	if ( ! empty( $input['animEasing'] ) ) {
		$attrs['globalAnimEasing'] = sanitize_text_field( $input['animEasing'] ); }

	if ( tpgb_mcp_tt_needs_wrapper( $attrs ) ) {
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
