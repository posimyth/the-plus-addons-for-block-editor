<?php
/**
 * Ability: Add Nexter Blocks Social Icons (tpgb/tp-social-icons) to a Gutenberg page.
 *
 * @package The_Plus_Addons_For_Block_Editor
 * @since   1.3.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_register_ability(
	'nexter-blocks/add-tpgb-social-icons',
	array(
		'label'               => __( 'Add Nexter Blocks Social Icons', 'the-plus-addons-for-block-editor' ),
		'description'         => __( 'Adds the Nexter Blocks Social Icons block (tpgb/tp-social-icons) — a list of social-media icons with 16 visual styles, per-icon network/icon/image/URL/title/colors/tooltip, hover-style presets (faded/etc), alignment, gap, padding, sizing, border, radius, shadow, title typography, and Tippy tooltip controls. Provide an array of `socialIcons` to fully control each item; otherwise the default Facebook/YouTube/Twitter set is used. This is a dynamic block.', 'the-plus-addons-for-block-editor' ),
		'category'            => 'nexter-blocks',

		'input_schema'        => array(
			'type'                 => 'object',
			'properties'           => array(

				/* ── Core ─────────────────────────────────────────────────── */
				'post_id'            => array(
					'type'        => 'integer',
					'description' => 'WordPress page/post ID to insert the block into.',
				),
				'position'           => array(
					'type'        => 'integer',
					'default'     => -1,
					'description' => 'Zero-based insert position. Use -1 to append.',
				),
				'parent_block_id'    => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Optional parent block_id to nest this block.',
				),

				/* ── Style ────────────────────────────────────────────────── */
				'style'              => array(
					'type'        => 'string',
					'enum'        => array( 'style-1', 'style-2', 'style-3', 'style-4', 'style-5', 'style-6', 'style-7', 'style-8', 'style-9', 'style-10', 'style-11', 'style-12', 'style-13', 'style-14', 'style-15', 'style-16' ),
					'description' => 'Visual style preset (16 layouts).',
					'default'     => 'style-1',
				),
				'hoverStyle'         => array(
					'type'        => 'string',
					'enum'        => array( 'faded', 'outlined', 'reveal', 'blink', 'flat', 'swing', 'wobble' ),
					'description' => 'Hover effect preset.',
					'default'     => 'faded',
				),
				'alignment'          => array(
					'type'    => 'string',
					'enum'    => array( '', 'left', 'center', 'right' ),
					'default' => 'center',
				),

				/* ── Icons array ──────────────────────────────────────────── */
				'socialIcons'        => array(
					'type'        => 'array',
					'description' => 'List of social icon items. Each item: { icon (FA class), title, url, newTab, nofollow, type (icon|img|customIcon), customIcon, imageUrl, imageId, iconColor, iconHoverColor, bgColor, bgHoverColor, borderColor, borderHoverColor, tooltip, tooltipText, tooltipColor }. Omit to use default Facebook/YouTube/Twitter set.',
					'items'       => array(
						'type'       => 'object',
						'properties' => array(
							'icon'             => array( 'type' => 'string' ),
							'title'            => array( 'type' => 'string' ),
							'url'              => array( 'type' => 'string' ),
							'newTab'           => array( 'type' => 'boolean' ),
							'nofollow'         => array( 'type' => 'boolean' ),
							'type'             => array(
								'type' => 'string',
								'enum' => array( 'icon', 'img', 'customIcon' ),
							),
							'customIcon'       => array( 'type' => 'string' ),
							'imageUrl'         => array( 'type' => 'string' ),
							'imageId'          => array( 'type' => 'integer' ),
							'iconColor'        => array( 'type' => 'string' ),
							'iconHoverColor'   => array( 'type' => 'string' ),
							'bgColor'          => array( 'type' => 'string' ),
							'bgHoverColor'     => array( 'type' => 'string' ),
							'borderColor'      => array( 'type' => 'string' ),
							'borderHoverColor' => array( 'type' => 'string' ),
							'tooltip'          => array( 'type' => 'boolean' ),
							'tooltipText'      => array( 'type' => 'string' ),
							'tooltipColor'     => array( 'type' => 'string' ),
						),
					),
				),

				/* ── Layout / sizing ──────────────────────────────────────── */
				'iconPadding'        => array(
					'type'        => 'object',
					'description' => 'Inner icon padding (style-1/2/14).',
				),
				'iconGap'            => array(
					'type'        => 'object',
					'description' => 'Margin between icons.',
				),
				'iconSize'           => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Icon font-size in px.',
				),
				'imgWidth'           => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Image max-width (when type=img).',
				),
				's3CircleWidth'      => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Circle size for style-3 (px).',
				),
				'iconHgt'            => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Icon height for style-15 (px).',
				),
				'iconWidth'          => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Icon box width for style-16 (px).',
				),
				'iconHeight'         => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Icon box height for style-16 (px).',
				),

				/* ── Border / Radius / Shadow ─────────────────────────────── */
				'borderStyle'        => array(
					'type'        => 'string',
					'enum'        => array( 'solid', 'dashed', 'dotted', 'double', 'none' ),
					'default'     => 'solid',
					'description' => 'Border style (style-16).',
				),
				'borderWidth'        => array(
					'type'        => 'object',
					'description' => 'Border width (style-16).',
				),
				'iconBRadius'        => array(
					'type'        => 'object',
					'description' => 'Icon border-radius.',
				),
				'iconShadow'         => array(
					'type'        => 'object',
					'description' => 'Box-shadow (style-7/16).',
				),
				'iconShadowHover'    => array( 'type' => 'object' ),

				/* ── Typography ───────────────────────────────────────────── */
				'titleTypoSize'      => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Title font-size in px.',
				),

				/* ── Tooltip (Tippy) ──────────────────────────────────────── */
				'tipInteractive'     => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'tipPlacement'       => array(
					'type'    => 'string',
					'enum'    => array( 'top', 'bottom', 'left', 'right', 'top-start', 'top-end', 'bottom-start', 'bottom-end', 'left-start', 'left-end', 'right-start', 'right-end' ),
					'default' => 'top',
				),
				'tipTheme'           => array(
					'type'    => 'string',
					'enum'    => array( '', 'light', 'light-border', 'translucent', 'material' ),
					'default' => '',
				),
				'tipMaxWidth'        => array(
					'type'        => 'integer',
					'default'     => 100,
					'description' => 'Tooltip max-width in px.',
				),
				'tipOffset'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'tipDistance'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'tipArrow'           => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'tipTriggers'        => array(
					'type'    => 'string',
					'enum'    => array( 'mouseenter', 'click', 'focus', 'manual', 'mouseenter focus', 'mouseenter click' ),
					'default' => 'mouseenter',
				),
				'tipAnimation'       => array(
					'type'    => 'string',
					'enum'    => array( '', 'fade', 'shift-away', 'shift-toward', 'scale', 'perspective' ),
					'default' => '',
				),
				'tipDurationIn'      => array(
					'type'    => 'string',
					'default' => '',
				),
				'tipDurationOut'     => array(
					'type'    => 'string',
					'default' => '',
				),
				'tipArrowColor'      => array(
					'type'    => 'string',
					'default' => '',
				),
				'tipPadding'         => array( 'type' => 'object' ),
				'tipBorder'          => array( 'type' => 'object' ),
				'tipBorderRadius'    => array( 'type' => 'object' ),
				'tipBgColor'         => array(
					'type'    => 'string',
					'default' => '',
				),
				'tipBoxShadow'       => array( 'type' => 'object' ),

				/* ── Visibility / Globals ─────────────────────────────────── */
				'hideDesktop'        => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'hideTablet'         => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'hideMobile'         => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'globalClasses'      => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalId'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalCustomCss'    => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalWidth'        => array(
					'type'    => 'string',
					'enum'    => array( '', 'inline', 'full', 'custom' ),
					'default' => '',
				),
				'globalZindex'       => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalPosition'     => array(
					'type'    => 'string',
					'enum'    => array( '', 'relative', 'absolute', 'fixed', 'sticky' ),
					'default' => '',
				),
				'globalMargin'       => array( 'type' => 'object' ),
				'globalPadding'      => array( 'type' => 'object' ),
				'globalBgColor'      => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalBgHoverColor' => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalBorder'       => array( 'type' => 'object' ),
				'globalBorderHover'  => array( 'type' => 'object' ),
				'globalBRadius'      => array( 'type' => 'object' ),
				'globalBRadiusHover' => array( 'type' => 'object' ),
				'globalBShadow'      => array( 'type' => 'object' ),
				'globalBShadowHover' => array( 'type' => 'object' ),
				'scrollAnimation'    => array(
					'type'    => 'string',
					'default' => '',
				),
				'animDuration'       => array(
					'type'    => 'string',
					'enum'    => array( '', 'slow', 'normal', 'fast' ),
					'default' => '',
				),
				'animDelay'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'animEasing'         => array(
					'type'    => 'string',
					'enum'    => array( '', 'linear', 'ease', 'ease-in', 'ease-out', 'ease-in-out' ),
					'default' => '',
				),

				'settings'           => array(
					'type'        => 'object',
					'description' => 'Raw attribute overrides.',
				),
				'fontFamily'         => array(
					'type'        => 'string',
					'description' => 'Font family name (e.g. "Inter", "Roboto", "Playfair Display"). When inspecting a URL via nexter-blocks/inspect-page, pass the returned fonts[].family value verbatim.',
					'default'     => '',
				),
				'fontType'           => array(
					'type'        => 'string',
					'description' => 'Font category — "sans-serif", "serif", "display", "handwriting", or "monospace".',
					'default'     => '',
				),
				'customFont'         => array(
					'type'        => 'string',
					'description' => 'Custom (non-Google) font name. Overrides fontFamily.',
					'default'     => '',
				),
				'fontWeight'         => array(
					'type'        => 'string',
					'description' => 'Font weight as a string ("100"..."900"). Embedded inside every typography group\'s fontFamily.fontWeight on this block. For per-group control, use the settings raw override or sprout/update-element.',
					'default'     => '',
				),
				'textDecoration'     => array(
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

		'execute_callback'    => 'tpgb_mcp_add_social_icons_ability',
		'permission_callback' => 'tpgb_mcp_add_social_icons_permission',
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
 * Permission callback for the add-social-icons ability.
 *
 * @param array|null $input Ability input arguments.
 * @return bool True when the current user may insert the block.
 */
function tpgb_mcp_add_social_icons_permission( ?array $input = null ): bool {
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
function tpgb_mcp_si_spacing( array $v ): array {
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
function tpgb_mcp_si_border( array $b ): array {
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
function tpgb_mcp_si_radius( array $r ): array {
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
function tpgb_mcp_si_bshadow( array $s ): array {
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
function tpgb_mcp_si_bg( string $color ): array {
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
 * Determine whether the block needs Nexter's wrapper rule for global styling.
 *
 * @param array $attrs Block attributes already gathered.
 * @return bool True if any wrapper-affecting attribute is present.
 */
function tpgb_mcp_si_needs_wrapper( array $attrs ): bool {
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
 * Execute callback for the add-social-icons ability.
 *
 * @param array $input Ability input arguments.
 * @return array|WP_Error Result payload or WP_Error on failure.
 */
function tpgb_mcp_add_social_icons_ability( array $input ) {

	if ( ! defined( 'TPGB_VERSION' ) && ! defined( 'TPGBP_VERSION' ) ) {
		return new WP_Error( 'nexter_blocks_missing', __( 'Nexter Blocks must be active.', 'the-plus-addons-for-block-editor' ) );
	}

	$block_name = 'tpgb/tp-social-icons';
	if ( ! tpgb_mcp_has_registered_block( $block_name ) ) {
		return new WP_Error( 'block_missing', __( 'tpgb/tp-social-icons is not registered.', 'the-plus-addons-for-block-editor' ) );
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

	/* ── Style ────────────────────────────────────────────────────────── */
	if ( ! empty( $input['style'] ) && 'style-1' !== $input['style'] ) {
		$attrs['style'] = sanitize_text_field( $input['style'] ); }
	if ( ! empty( $input['hoverStyle'] ) && 'faded' !== $input['hoverStyle'] ) {
		$attrs['hoverStyle'] = sanitize_text_field( $input['hoverStyle'] ); }
	if ( ! empty( $input['alignment'] ) && 'center' !== $input['alignment'] ) {
		$attrs['Alignment'] = array(
			'md' => sanitize_text_field( $input['alignment'] ),
			'sm' => '',
			'xs' => '',
		);
	}

	/* ── Icons array ──────────────────────────────────────────────────── */
	if ( ! empty( $input['socialIcons'] ) && is_array( $input['socialIcons'] ) ) {
		$items = array();
		foreach ( $input['socialIcons'] as $i => $it ) {
			$type    = sanitize_key( $it['type'] ?? 'icon' );
			$row     = array(
				'_key'         => (string) $i,
				'socialNtwk'   => sanitize_text_field( $it['icon'] ?? 'fab fa-facebook-f' ),
				'title'        => sanitize_text_field( $it['title'] ?? 'Network' ),
				'linkUrl'      => array(
					'url'      => esc_url_raw( $it['url'] ?? '#' ),
					'target'   => ! empty( $it['newTab'] ) ? '_blank' : '',
					'nofollow' => ! empty( $it['nofollow'] ) ? 'nofollow' : '',
				),
				'customType'   => $type,
				'customIcons'  => sanitize_text_field( $it['customIcon'] ?? 'fab fa-whatsapp' ),
				'imgField'     => array(
					'url' => esc_url_raw( $it['imageUrl'] ?? '' ),
					'id'  => absint( $it['imageId'] ?? 0 ),
				),
				'iconNmlColor' => sanitize_text_field( $it['iconColor'] ?? '' ),
				'iconHvrColor' => sanitize_text_field( $it['iconHoverColor'] ?? '' ),
				'nmlBG'        => sanitize_text_field( $it['bgColor'] ?? '' ),
				'hvrBG'        => sanitize_text_field( $it['bgHoverColor'] ?? '' ),
				'nmlBColor'    => sanitize_text_field( $it['borderColor'] ?? '' ),
				'hvrBColor'    => sanitize_text_field( $it['borderHoverColor'] ?? '' ),
				'itemTooltip'  => ! empty( $it['tooltip'] ),
				'tooltipText'  => sanitize_text_field( $it['tooltipText'] ?? '' ),
				'tooltipColor' => sanitize_text_field( $it['tooltipColor'] ?? '' ),
				'tooltipTypo'  => array( 'openTypography' => 0 ),
			);
			$items[] = $row;
		}
		$attrs['socialIcon'] = $items;
	}

	/* ── Layout / sizing ──────────────────────────────────────────────── */
	if ( ! empty( $input['iconPadding'] ) ) {
		$attrs['iconPadd'] = tpgb_mcp_si_spacing( $input['iconPadding'] ); }
	if ( ! empty( $input['iconGap'] ) ) {
		$attrs['iconGap'] = tpgb_mcp_si_spacing( $input['iconGap'] ); }
	if ( ! empty( $input['iconSize'] ) ) {
		$attrs['iconSize'] = array(
			'md'   => (string) absint( $input['iconSize'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['imgWidth'] ) ) {
		$attrs['imgWidth'] = array(
			'md'   => (string) absint( $input['imgWidth'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['s3CircleWidth'] ) ) {
		$attrs['s3CircleWidth'] = array(
			'md'   => (string) absint( $input['s3CircleWidth'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['iconHgt'] ) ) {
		$attrs['iconHgt'] = array(
			'md'   => (string) absint( $input['iconHgt'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['iconWidth'] ) ) {
		$attrs['iconWidth'] = array(
			'md'   => (string) absint( $input['iconWidth'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['iconHeight'] ) ) {
		$attrs['iconHeight'] = array(
			'md'   => (string) absint( $input['iconHeight'] ),
			'unit' => 'px',
		); }

	/* ── Border / Radius / Shadow ─────────────────────────────────────── */
	if ( ! empty( $input['borderStyle'] ) && 'solid' !== $input['borderStyle'] ) {
		$attrs['borderStyle'] = sanitize_text_field( $input['borderStyle'] ); }
	if ( ! empty( $input['borderWidth'] ) ) {
		$attrs['borderWidth'] = tpgb_mcp_si_spacing( $input['borderWidth'] ); }
	if ( ! empty( $input['iconBRadius'] ) ) {
		$attrs['iconBRadius'] = tpgb_mcp_si_radius( $input['iconBRadius'] ); }
	if ( ! empty( $input['iconShadow'] ) ) {
		$attrs['nmlIcnShadow'] = tpgb_mcp_si_bshadow( $input['iconShadow'] ); }
	if ( ! empty( $input['iconShadowHover'] ) ) {
		$attrs['hvrIcnShadow'] = tpgb_mcp_si_bshadow( $input['iconShadowHover'] ); }

	/* ── Typography ───────────────────────────────────────────────────── */
	if ( ! empty( $input['titleTypoSize'] ) ) {
		$attrs['titleTypo'] = array(
			'openTypography' => 1,
			'size'           => array(
				'md'   => (string) absint( $input['titleTypoSize'] ),
				'unit' => 'px',
			),
			'height'         => '',
			'spacing'        => '',
			'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
		);
	}

	/* ── Tooltip ──────────────────────────────────────────────────────── */
	if ( ! empty( $input['tipInteractive'] ) ) {
		$attrs['tipInteractive'] = true; }
	if ( ! empty( $input['tipPlacement'] ) && 'top' !== $input['tipPlacement'] ) {
		$attrs['tipPlacement'] = sanitize_text_field( $input['tipPlacement'] ); }
	if ( ! empty( $input['tipTheme'] ) ) {
		$attrs['tipTheme'] = sanitize_text_field( $input['tipTheme'] ); }
	if ( ! empty( $input['tipMaxWidth'] ) && 100 !== intval( $input['tipMaxWidth'] ) ) {
		$attrs['tipMaxWidth'] = (string) absint( $input['tipMaxWidth'] ); }
	if ( ! empty( $input['tipOffset'] ) ) {
		$attrs['tipOffset'] = sanitize_text_field( (string) $input['tipOffset'] ); }
	if ( ! empty( $input['tipDistance'] ) ) {
		$attrs['tipDistance'] = sanitize_text_field( (string) $input['tipDistance'] ); }
	if ( isset( $input['tipArrow'] ) && false === $input['tipArrow'] ) {
		$attrs['tipArrow'] = false; }
	if ( ! empty( $input['tipTriggers'] ) && 'mouseenter' !== $input['tipTriggers'] ) {
		$attrs['tipTriggers'] = sanitize_text_field( $input['tipTriggers'] ); }
	if ( ! empty( $input['tipAnimation'] ) ) {
		$attrs['tipAnimation'] = sanitize_text_field( $input['tipAnimation'] ); }
	if ( ! empty( $input['tipDurationIn'] ) ) {
		$attrs['tipDurationIn'] = sanitize_text_field( (string) $input['tipDurationIn'] ); }
	if ( ! empty( $input['tipDurationOut'] ) ) {
		$attrs['tipDurationOut'] = sanitize_text_field( (string) $input['tipDurationOut'] ); }
	if ( ! empty( $input['tipArrowColor'] ) ) {
		$attrs['tipArrowColor'] = sanitize_text_field( $input['tipArrowColor'] ); }
	if ( ! empty( $input['tipPadding'] ) ) {
		$attrs['tipPadding'] = tpgb_mcp_si_spacing( $input['tipPadding'] ); }
	if ( ! empty( $input['tipBorder'] ) ) {
		$attrs['tipBorder'] = tpgb_mcp_si_border( $input['tipBorder'] ); }
	if ( ! empty( $input['tipBorderRadius'] ) ) {
		$attrs['tipBorderRadius'] = tpgb_mcp_si_radius( $input['tipBorderRadius'] ); }
	if ( ! empty( $input['tipBgColor'] ) ) {
		$attrs['tipBg'] = tpgb_mcp_si_bg( $input['tipBgColor'] ); }
	if ( ! empty( $input['tipBoxShadow'] ) ) {
		$attrs['tipBoxShadow'] = tpgb_mcp_si_bshadow( $input['tipBoxShadow'] ); }

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
		$attrs['globalMargin'] = tpgb_mcp_si_spacing( $input['globalMargin'] ); }
	if ( ! empty( $input['globalPadding'] ) ) {
		$attrs['globalPadding'] = tpgb_mcp_si_spacing( $input['globalPadding'] ); }
	if ( ! empty( $input['globalBgColor'] ) ) {
		$attrs['globalBg'] = tpgb_mcp_si_bg( $input['globalBgColor'] ); }
	if ( ! empty( $input['globalBgHoverColor'] ) ) {
		$attrs['globalBgHover'] = tpgb_mcp_si_bg( $input['globalBgHoverColor'] ); }
	if ( ! empty( $input['globalBorder'] ) ) {
		$attrs['globalBorder'] = tpgb_mcp_si_border( $input['globalBorder'] ); }
	if ( ! empty( $input['globalBorderHover'] ) ) {
		$attrs['globalBorderHover'] = tpgb_mcp_si_border( $input['globalBorderHover'] ); }
	if ( ! empty( $input['globalBRadius'] ) ) {
		$attrs['globalBRadius'] = tpgb_mcp_si_radius( $input['globalBRadius'] ); }
	if ( ! empty( $input['globalBRadiusHover'] ) ) {
		$attrs['globalBRadiusHover'] = tpgb_mcp_si_radius( $input['globalBRadiusHover'] ); }
	if ( ! empty( $input['globalBShadow'] ) ) {
		$attrs['globalBShadow'] = tpgb_mcp_si_bshadow( $input['globalBShadow'] ); }
	if ( ! empty( $input['globalBShadowHover'] ) ) {
		$attrs['globalBShadowHover'] = tpgb_mcp_si_bshadow( $input['globalBShadowHover'] ); }
	if ( ! empty( $input['scrollAnimation'] ) ) {
		$attrs['globalAnim'] = array( 'md' => sanitize_text_field( $input['scrollAnimation'] ) ); }
	if ( ! empty( $input['animDuration'] ) ) {
		$attrs['globalAnimDuration'] = sanitize_text_field( $input['animDuration'] ); }
	if ( ! empty( $input['animDelay'] ) ) {
		$attrs['globalAnimDelay'] = array( 'md' => sanitize_text_field( $input['animDelay'] ) ); }
	if ( ! empty( $input['animEasing'] ) ) {
		$attrs['globalAnimEasing'] = sanitize_text_field( $input['animEasing'] ); }

	if ( tpgb_mcp_si_needs_wrapper( $attrs ) ) {
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
