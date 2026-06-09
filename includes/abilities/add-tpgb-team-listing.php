<?php
/**
 * Ability: Add Nexter Blocks Team Member (tpgb/tp-team-listing) to a Gutenberg page.
 *
 * @package The_Plus_Addons_For_Block_Editor
 * @since   1.3.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_register_ability(
	'nexter-blocks/add-tpgb-team-listing',
	array(
		'label'               => __( 'Add Nexter Blocks Team Member', 'the-plus-addons-for-block-editor' ),
		'description'         => __( 'Adds the Nexter Blocks Team Member block (tpgb/tp-team-listing) — grid/carousel team showcase. Each member has photo, name, designation, category, custom URL, and 7 social channels (website, FB, IG, Twitter, LinkedIn, Mail, Phone). Supports grid/messy/slider layouts, columns per breakpoint, alignment, title tag, name typography & colors (normal+hover), designation typography & colors, social icon size/bg-size/colors (normal+hover), image margin/padding/radius/bg/filter/shadow (normal+hover), card box padding/border/radius/bg/shadow (normal+hover), content padding. This is a dynamic block.', 'the-plus-addons-for-block-editor' ),
		'category'            => 'nexter-blocks',

		'input_schema'        => array(
			'type'                 => 'object',
			'properties'           => array(

				/* ── Core ─────────────────────────────────────────────────── */
				'post_id'               => array( 'type' => 'integer' ),
				'position'              => array(
					'type'    => 'integer',
					'default' => -1,
				),
				'parent_block_id'       => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Members ──────────────────────────────────────────────── */
				'members'               => array(
					'type'        => 'array',
					'description' => 'Team members list. Each: { name, image{url,id}, designation, category, customUrl, websiteUrl, facebookUrl, mailUrl, instagramUrl, twitterUrl, linkedinUrl, phone }.',
					'items'       => array(
						'type'       => 'object',
						'properties' => array(
							'name'         => array( 'type' => 'string' ),
							'imageUrl'     => array( 'type' => 'string' ),
							'imageId'      => array( 'type' => 'integer' ),
							'designation'  => array( 'type' => 'string' ),
							'category'     => array( 'type' => 'string' ),
							'customUrl'    => array( 'type' => 'string' ),
							'websiteUrl'   => array( 'type' => 'string' ),
							'facebookUrl'  => array( 'type' => 'string' ),
							'mailUrl'      => array( 'type' => 'string' ),
							'instagramUrl' => array( 'type' => 'string' ),
							'twitterUrl'   => array( 'type' => 'string' ),
							'linkedinUrl'  => array( 'type' => 'string' ),
							'phone'        => array( 'type' => 'string' ),
						),
					),
				),

				/* ── Layout ───────────────────────────────────────────────── */
				'style'                 => array(
					'type'    => 'string',
					'enum'    => array( 'style-1', 'style-2', 'style-3', 'style-4', 'style-5' ),
					'default' => 'style-1',
				),
				'layout'                => array(
					'type'    => 'string',
					'enum'    => array( 'grid', 'messy', 'slider' ),
					'default' => 'grid',
				),
				'columnsDesktop'        => array(
					'type'    => 'integer',
					'default' => 3,
				),
				'columnsTablet'         => array(
					'type'    => 'integer',
					'default' => 4,
				),
				'columnsMobile'         => array(
					'type'    => 'integer',
					'default' => 6,
				),
				'slideColumnsDesktop'   => array(
					'type'    => 'integer',
					'default' => 4,
				),
				'slideColumnsTablet'    => array(
					'type'    => 'integer',
					'default' => 3,
				),
				'slideColumnsMobile'    => array(
					'type'    => 'integer',
					'default' => 2,
				),
				'columnSpace'           => array(
					'type'        => 'object',
					'description' => 'Padding around each grid item.',
				),
				'messyColumns'          => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'alignment'             => array(
					'type'    => 'string',
					'enum'    => array( 'left', 'center', 'right' ),
					'default' => 'left',
				),
				'titleTag'              => array(
					'type'    => 'string',
					'enum'    => array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'p', 'span' ),
					'default' => 'h3',
				),

				/* ── Toggles ──────────────────────────────────────────────── */
				'showDesignation'       => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'showSocial'            => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'showImage'             => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'imageSize'             => array(
					'type'    => 'string',
					'default' => 'full',
				),
				'cardLink'              => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Wrap whole card in custom URL link.',
				),
				'enableCategory'        => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'showBlockContent'      => array(
					'type'    => 'boolean',
					'default' => true,
				),

				/* ── Title style ──────────────────────────────────────────── */
				'titleTypoSize'         => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'titleColor'            => array(
					'type'    => 'string',
					'default' => '',
				),
				'titleColorHover'       => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Designation style ────────────────────────────────────── */
				'designationTypoSize'   => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'designationColor'      => array(
					'type'    => 'string',
					'default' => '',
				),
				'designationColorHover' => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Social icons ─────────────────────────────────────────── */
				'iconSize'              => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'iconBgSize'            => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Icon button width/height (px).',
				),
				'iconColor'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'iconBgColor'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'iconColorHover'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'iconBgColorHover'      => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Image area ───────────────────────────────────────────── */
				'imageMargin'           => array( 'type' => 'object' ),
				'imagePadding'          => array( 'type' => 'object' ),
				'imageRadius'           => array( 'type' => 'object' ),
				'imageBgColor'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'imageFilter'           => array( 'type' => 'object' ),
				'imageBorder'           => array( 'type' => 'object' ),
				'imageFilterHover'      => array( 'type' => 'object' ),
				'imageBorderHover'      => array( 'type' => 'object' ),

				/* ── Box / card ───────────────────────────────────────────── */
				'boxPadding'            => array( 'type' => 'object' ),
				'enableBoxBorder'       => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'boxBorder'             => array( 'type' => 'object' ),
				'boxBorderHover'        => array( 'type' => 'object' ),
				'boxRadius'             => array( 'type' => 'object' ),
				'boxRadiusHover'        => array( 'type' => 'object' ),
				'boxBgColor'            => array(
					'type'    => 'string',
					'default' => '',
				),
				'boxBgColorHover'       => array(
					'type'    => 'string',
					'default' => '',
				),
				'boxShadow'             => array( 'type' => 'object' ),
				'boxShadowHover'        => array( 'type' => 'object' ),

				/* ── Content area ─────────────────────────────────────────── */
				'contentPadding'        => array( 'type' => 'object' ),

				/* ── Visibility / Globals ─────────────────────────────────── */
				'hideDesktop'           => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'hideTablet'            => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'hideMobile'            => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'globalClasses'         => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalId'              => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalCustomCss'       => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalWidth'           => array(
					'type'    => 'string',
					'enum'    => array( '', 'inline', 'full', 'custom' ),
					'default' => '',
				),
				'globalZindex'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalPosition'        => array(
					'type'    => 'string',
					'enum'    => array( '', 'relative', 'absolute', 'fixed', 'sticky' ),
					'default' => '',
				),
				'globalMargin'          => array( 'type' => 'object' ),
				'globalPadding'         => array( 'type' => 'object' ),
				'globalBgColor'         => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalBgHoverColor'    => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalBorder'          => array( 'type' => 'object' ),
				'globalBorderHover'     => array( 'type' => 'object' ),
				'globalBRadius'         => array( 'type' => 'object' ),
				'globalBRadiusHover'    => array( 'type' => 'object' ),
				'globalBShadow'         => array( 'type' => 'object' ),
				'globalBShadowHover'    => array( 'type' => 'object' ),
				'scrollAnimation'       => array(
					'type'    => 'string',
					'default' => '',
				),
				'animDuration'          => array(
					'type'    => 'string',
					'enum'    => array( '', 'slow', 'normal', 'fast' ),
					'default' => '',
				),
				'animDelay'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'animEasing'            => array(
					'type'    => 'string',
					'enum'    => array( '', 'linear', 'ease', 'ease-in', 'ease-out', 'ease-in-out' ),
					'default' => '',
				),

				'settings'              => array(
					'type'        => 'object',
					'description' => 'Raw attribute overrides.',
				),
				'fontFamily'            => array(
					'type'        => 'string',
					'description' => 'Font family name (e.g. "Inter", "Roboto", "Playfair Display"). When inspecting a URL via nexter-blocks/inspect-page, pass the returned fonts[].family value verbatim.',
					'default'     => '',
				),
				'fontType'              => array(
					'type'        => 'string',
					'description' => 'Font category — "sans-serif", "serif", "display", "handwriting", or "monospace".',
					'default'     => '',
				),
				'customFont'            => array(
					'type'        => 'string',
					'description' => 'Custom (non-Google) font name. Overrides fontFamily.',
					'default'     => '',
				),
				'fontWeight'            => array(
					'type'        => 'string',
					'description' => 'Font weight as a string ("100"..."900"). Embedded inside every typography group\'s fontFamily.fontWeight on this block. For per-group control, use the settings raw override or sprout/update-element.',
					'default'     => '',
				),
				'textDecoration'        => array(
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

		'execute_callback'    => 'tpgb_mcp_add_team_listing_ability',
		'permission_callback' => 'tpgb_mcp_add_team_listing_permission',
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
 * Permission callback for the add-team-listing ability.
 *
 * @param array|null $input Ability input arguments.
 * @return bool True when the current user may insert the block.
 */
function tpgb_mcp_add_team_listing_permission( ?array $input = null ): bool {
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
function tpgb_mcp_tm_spacing( array $v ): array {
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
function tpgb_mcp_tm_border( array $b ): array {
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
function tpgb_mcp_tm_radius( array $r ): array {
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
function tpgb_mcp_tm_bshadow( array $s ): array {
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
function tpgb_mcp_tm_bg( string $color ): array {
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
function tpgb_mcp_tm_typo( int $size ): array {
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
 * Build a Nexter Blocks URL attribute structure.
 *
 * @param string $url URL value to wrap.
 * @return array URL attribute structured for the block.
 */
function tpgb_mcp_tm_url( string $url ): array {
	return array(
		'url'      => esc_url_raw( $url ),
		'target'   => '',
		'nofollow' => '',
	);
}
/**
 * Determine whether the block needs Nexter's wrapper rule for global styling.
 *
 * @param array $attrs Block attributes already gathered.
 * @return bool True if any wrapper-affecting attribute is present.
 */
function tpgb_mcp_tm_needs_wrapper( array $attrs ): bool {
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
 * Execute callback for the add-team-listing ability.
 *
 * @param array $input Ability input arguments.
 * @return array|WP_Error Result payload or WP_Error on failure.
 */
function tpgb_mcp_add_team_listing_ability( array $input ) {

	if ( ! defined( 'TPGB_VERSION' ) && ! defined( 'TPGBP_VERSION' ) ) {
		return new WP_Error( 'nexter_blocks_missing', __( 'Nexter Blocks must be active.', 'the-plus-addons-for-block-editor' ) );
	}

	$block_name = 'tpgb/tp-team-listing';
	if ( ! tpgb_mcp_has_registered_block( $block_name ) ) {
		return new WP_Error( 'block_missing', __( 'tpgb/tp-team-listing is not registered.', 'the-plus-addons-for-block-editor' ) );
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

	/* ── Members ──────────────────────────────────────────────────────── */
	if ( ! empty( $input['members'] ) && is_array( $input['members'] ) ) {
		$rows = array();
		foreach ( $input['members'] as $i => $m ) {
			$rows[] = array(
				'_key'    => (string) $i,
				'TName'   => sanitize_text_field( $m['name'] ?? 'Team Member' ),
				'TImage'  => array(
					'url' => esc_url_raw( $m['imageUrl'] ?? '' ),
					'Id'  => (string) absint( $m['imageId'] ?? 0 ),
					'id'  => absint( $m['imageId'] ?? 0 ),
				),
				'TDesig'  => sanitize_text_field( $m['designation'] ?? '' ),
				'TCateg'  => sanitize_text_field( $m['category'] ?? '' ),
				'CusUrl'  => tpgb_mcp_tm_url( $m['customUrl'] ?? '' ),
				'WsUrl'   => tpgb_mcp_tm_url( $m['websiteUrl'] ?? '' ),
				'FbUrl'   => tpgb_mcp_tm_url( $m['facebookUrl'] ?? '' ),
				'MailUrl' => tpgb_mcp_tm_url( $m['mailUrl'] ?? '' ),
				'IGUrl'   => tpgb_mcp_tm_url( $m['instagramUrl'] ?? '' ),
				'TwUrl'   => tpgb_mcp_tm_url( $m['twitterUrl'] ?? '' ),
				'ldUrl'   => tpgb_mcp_tm_url( $m['linkedinUrl'] ?? '' ),
				'TelNum'  => sanitize_text_field( $m['phone'] ?? '' ),
			);
		}
		$attrs['TeamMemberR'] = $rows;
	}

	/* ── Layout ───────────────────────────────────────────────────────── */
	if ( ! empty( $input['style'] ) && 'style-1' !== $input['style'] ) {
		$attrs['Style'] = sanitize_text_field( $input['style'] ); }
	if ( ! empty( $input['layout'] ) && 'grid' !== $input['layout'] ) {
		$attrs['layout'] = sanitize_key( $input['layout'] ); }
	if ( ! empty( $input['alignment'] ) && 'left' !== $input['alignment'] ) {
		$attrs['Alignment'] = array(
			'md' => sanitize_text_field( $input['alignment'] ),
			'sm' => '',
			'xs' => '',
		);
	}
	if ( ! empty( $input['titleTag'] ) && 'h3' !== $input['titleTag'] ) {
		$attrs['TitleTag'] = sanitize_text_field( $input['titleTag'] ); }

	/* columns */
	$cd = isset( $input['columnsDesktop'] ) ? intval( $input['columnsDesktop'] ) : 3;
	$ct = isset( $input['columnsTablet'] ) ? intval( $input['columnsTablet'] ) : 4;
	$cm = isset( $input['columnsMobile'] ) ? intval( $input['columnsMobile'] ) : 6;
	if ( 3 !== $cd || 4 !== $ct || 6 !== $cm ) {
		$attrs['columns'] = array(
			'md' => (string) $cd,
			'sm' => (string) $ct,
			'xs' => (string) $cm,
		);
	}
	$sd = isset( $input['slideColumnsDesktop'] ) ? intval( $input['slideColumnsDesktop'] ) : 4;
	$st = isset( $input['slideColumnsTablet'] ) ? intval( $input['slideColumnsTablet'] ) : 3;
	$sm = isset( $input['slideColumnsMobile'] ) ? intval( $input['slideColumnsMobile'] ) : 2;
	if ( 4 !== $sd || 3 !== $st || 2 !== $sm ) {
		$attrs['slideColumns'] = array(
			'md' => (string) $sd,
			'sm' => (string) $st,
			'xs' => (string) $sm,
		);
	}
	if ( ! empty( $input['columnSpace'] ) ) {
		$attrs['columnSpace'] = tpgb_mcp_tm_spacing( $input['columnSpace'] ); }
	if ( ! empty( $input['messyColumns'] ) ) {
		$attrs['MessyCol'] = true; }

	/* ── Toggles ──────────────────────────────────────────────────────── */
	if ( isset( $input['showDesignation'] ) && false === $input['showDesignation'] ) {
		$attrs['DesignDis'] = false; }
	if ( isset( $input['showSocial'] ) && false === $input['showSocial'] ) {
		$attrs['SocialIcon'] = false; }
	if ( isset( $input['showImage'] ) && false === $input['showImage'] ) {
		$attrs['DImgS'] = false; }
	if ( ! empty( $input['imageSize'] ) && 'full' !== $input['imageSize'] ) {
		$attrs['ImgSize'] = sanitize_text_field( $input['imageSize'] ); }
	if ( ! empty( $input['cardLink'] ) ) {
		$attrs['DisLink'] = true; }
	if ( ! empty( $input['enableCategory'] ) ) {
		$attrs['CategoryWF'] = true; }
	if ( isset( $input['showBlockContent'] ) && false === $input['showBlockContent'] ) {
		$attrs['showBlockContent'] = false; }

	/* ── Title style ──────────────────────────────────────────────────── */
	if ( ! empty( $input['titleTypoSize'] ) ) {
		$attrs['TitleTypo'] = tpgb_mcp_tm_typo( (int) $input['titleTypoSize'] ); }
	if ( ! empty( $input['titleColor'] ) ) {
		$attrs['TNcolor'] = sanitize_text_field( $input['titleColor'] ); }
	if ( ! empty( $input['titleColorHover'] ) ) {
		$attrs['THcolor'] = sanitize_text_field( $input['titleColorHover'] ); }

	/* ── Designation style ────────────────────────────────────────────── */
	if ( ! empty( $input['designationTypoSize'] ) ) {
		$attrs['TextTypo'] = tpgb_mcp_tm_typo( (int) $input['designationTypoSize'] ); }
	if ( ! empty( $input['designationColor'] ) ) {
		$attrs['TextNCr'] = sanitize_text_field( $input['designationColor'] ); }
	if ( ! empty( $input['designationColorHover'] ) ) {
		$attrs['TextHCr'] = sanitize_text_field( $input['designationColorHover'] ); }

	/* ── Social icons ─────────────────────────────────────────────────── */
	if ( ! empty( $input['iconSize'] ) ) {
		$attrs['Iconsize'] = array(
			'md'   => (string) absint( $input['iconSize'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['iconBgSize'] ) ) {
		$attrs['IconBgsize'] = array(
			'md'   => (string) absint( $input['iconBgSize'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['iconColor'] ) ) {
		$attrs['IconNCr'] = sanitize_text_field( $input['iconColor'] ); }
	if ( ! empty( $input['iconBgColor'] ) ) {
		$attrs['IconNBgCr'] = sanitize_text_field( $input['iconBgColor'] ); }
	if ( ! empty( $input['iconColorHover'] ) ) {
		$attrs['IconHCr'] = sanitize_text_field( $input['iconColorHover'] ); }
	if ( ! empty( $input['iconBgColorHover'] ) ) {
		$attrs['IconHBgCr'] = sanitize_text_field( $input['iconBgColorHover'] ); }

	/* ── Image area ───────────────────────────────────────────────────── */
	if ( ! empty( $input['imageMargin'] ) ) {
		$attrs['FIMargin'] = tpgb_mcp_tm_spacing( $input['imageMargin'] ); }
	if ( ! empty( $input['imagePadding'] ) ) {
		$attrs['FIPadding'] = tpgb_mcp_tm_spacing( $input['imagePadding'] ); }
	if ( ! empty( $input['imageRadius'] ) ) {
		$attrs['FImgBs'] = tpgb_mcp_tm_radius( $input['imageRadius'] ); }
	if ( ! empty( $input['imageBgColor'] ) ) {
		$attrs['InnerBgCr'] = sanitize_text_field( $input['imageBgColor'] ); }
	if ( ! empty( $input['imageFilter'] ) ) {
		$attrs['NFilter'] = $input['imageFilter']; }
	if ( ! empty( $input['imageBorder'] ) ) {
		$attrs['NBoxSd'] = tpgb_mcp_tm_border( $input['imageBorder'] ); }
	if ( ! empty( $input['imageFilterHover'] ) ) {
		$attrs['HFilter'] = $input['imageFilterHover']; }
	if ( ! empty( $input['imageBorderHover'] ) ) {
		$attrs['HBoxSd'] = tpgb_mcp_tm_border( $input['imageBorderHover'] ); }

	/* ── Box / card ───────────────────────────────────────────────────── */
	if ( ! empty( $input['boxPadding'] ) ) {
		$attrs['BoxPadding'] = tpgb_mcp_tm_spacing( $input['boxPadding'] ); }
	if ( ! empty( $input['enableBoxBorder'] ) ) {
		$attrs['BoxTborder'] = true; }
	if ( ! empty( $input['boxBorder'] ) ) {
		$attrs['Boxborder'] = tpgb_mcp_tm_border( $input['boxBorder'] ); }
	if ( ! empty( $input['boxBorderHover'] ) ) {
		$attrs['BoxHBor'] = tpgb_mcp_tm_border( $input['boxBorderHover'] ); }
	if ( ! empty( $input['boxRadius'] ) ) {
		$attrs['BoxNBrs'] = tpgb_mcp_tm_radius( $input['boxRadius'] ); }
	if ( ! empty( $input['boxRadiusHover'] ) ) {
		$attrs['BoxHBrs'] = tpgb_mcp_tm_radius( $input['boxRadiusHover'] ); }
	if ( ! empty( $input['boxBgColor'] ) ) {
		$attrs['BoxNBg'] = tpgb_mcp_tm_bg( $input['boxBgColor'] ); }
	if ( ! empty( $input['boxBgColorHover'] ) ) {
		$attrs['BoxHBg'] = tpgb_mcp_tm_bg( $input['boxBgColorHover'] ); }
	if ( ! empty( $input['boxShadow'] ) ) {
		$attrs['BoxNSd'] = tpgb_mcp_tm_bshadow( $input['boxShadow'] ); }
	if ( ! empty( $input['boxShadowHover'] ) ) {
		$attrs['BoxHSd'] = tpgb_mcp_tm_bshadow( $input['boxShadowHover'] ); }

	/* ── Content area ─────────────────────────────────────────────────── */
	if ( ! empty( $input['contentPadding'] ) ) {
		$attrs['conPadding'] = tpgb_mcp_tm_spacing( $input['contentPadding'] ); }

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
		$attrs['globalMargin'] = tpgb_mcp_tm_spacing( $input['globalMargin'] ); }
	if ( ! empty( $input['globalPadding'] ) ) {
		$attrs['globalPadding'] = tpgb_mcp_tm_spacing( $input['globalPadding'] ); }
	if ( ! empty( $input['globalBgColor'] ) ) {
		$attrs['globalBg'] = tpgb_mcp_tm_bg( $input['globalBgColor'] ); }
	if ( ! empty( $input['globalBgHoverColor'] ) ) {
		$attrs['globalBgHover'] = tpgb_mcp_tm_bg( $input['globalBgHoverColor'] ); }
	if ( ! empty( $input['globalBorder'] ) ) {
		$attrs['globalBorder'] = tpgb_mcp_tm_border( $input['globalBorder'] ); }
	if ( ! empty( $input['globalBorderHover'] ) ) {
		$attrs['globalBorderHover'] = tpgb_mcp_tm_border( $input['globalBorderHover'] ); }
	if ( ! empty( $input['globalBRadius'] ) ) {
		$attrs['globalBRadius'] = tpgb_mcp_tm_radius( $input['globalBRadius'] ); }
	if ( ! empty( $input['globalBRadiusHover'] ) ) {
		$attrs['globalBRadiusHover'] = tpgb_mcp_tm_radius( $input['globalBRadiusHover'] ); }
	if ( ! empty( $input['globalBShadow'] ) ) {
		$attrs['globalBShadow'] = tpgb_mcp_tm_bshadow( $input['globalBShadow'] ); }
	if ( ! empty( $input['globalBShadowHover'] ) ) {
		$attrs['globalBShadowHover'] = tpgb_mcp_tm_bshadow( $input['globalBShadowHover'] ); }
	if ( ! empty( $input['scrollAnimation'] ) ) {
		$attrs['globalAnim'] = array( 'md' => sanitize_text_field( $input['scrollAnimation'] ) ); }
	if ( ! empty( $input['animDuration'] ) ) {
		$attrs['globalAnimDuration'] = sanitize_text_field( $input['animDuration'] ); }
	if ( ! empty( $input['animDelay'] ) ) {
		$attrs['globalAnimDelay'] = array( 'md' => sanitize_text_field( $input['animDelay'] ) ); }
	if ( ! empty( $input['animEasing'] ) ) {
		$attrs['globalAnimEasing'] = sanitize_text_field( $input['animEasing'] ); }

	if ( tpgb_mcp_tm_needs_wrapper( $attrs ) ) {
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
