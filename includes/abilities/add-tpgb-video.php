<?php
/**
 * Ability: Add Nexter Blocks Video (tpgb/tp-video) to a Gutenberg page.
 *
 * @package The_Plus_Addons_For_Block_Editor
 * @since   1.3.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_register_ability(
	'nexter-blocks/add-tpgb-video',
	array(
		'label'               => __( 'Add Nexter Blocks Video', 'the-plus-addons-for-block-editor' ),
		'description'         => __( 'Adds the Nexter Blocks Video block (tpgb/tp-video) — embeds a YouTube, Vimeo, or self-hosted MP4 video with custom thumbnail/banner mode, optional popup (lightbox) playback, custom play-icon image, video title & description overlay (with typography & colour), continuous animations (pulse/rotate/drop_waves) for the play icon, video container border/radius/shadow + hover, transform, schema markup, and provider-specific options (autoplay, mute, loop, controls, modest branding, privacy mode, Vimeo title/portrait/byline, etc.). This is a dynamic block.', 'the-plus-addons-for-block-editor' ),
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

				/* ── Video source ─────────────────────────────────────────── */
				'videoType'            => array(
					'type'    => 'string',
					'enum'    => array( 'youtube', 'vimeo', 'self_hosted' ),
					'default' => 'youtube',
				),
				'youtubeId'            => array(
					'type'    => 'string',
					'default' => 'TJ1SDXbij8Y',
				),
				'vimeoId'              => array(
					'type'    => 'string',
					'default' => '27246366',
				),
				'mp4Url'               => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Self-hosted MP4 URL.',
				),
				'mp4Id'                => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'fallbackImageUrl'     => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Fallback poster image for self-hosted video.',
				),
				'fallbackImageId'      => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'secVidUrl'            => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Secondary self-hosted video URL.',
				),
				'secVidId'             => array(
					'type'    => 'integer',
					'default' => 0,
				),

				/* ── Player options ───────────────────────────────────────── */
				'autoPlay'             => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'muted'                => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'loop'                 => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'controls'             => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'showInfo'             => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'touchDisable'         => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'modestBranding'       => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'YouTube modest branding.',
				),
				'rel'                  => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'YouTube show related videos.',
				),
				'ytPrivacy'            => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'YouTube privacy-enhanced mode.',
				),
				'videoColor'           => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'YouTube/Vimeo accent color.',
				),
				'vimeoTitle'           => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'vimeoPortrait'        => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'vimeoByline'          => array(
					'type'    => 'boolean',
					'default' => true,
				),

				/* ── Popup ────────────────────────────────────────────────── */
				'videoPopup'           => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Open in lightbox popup instead of inline.',
				),

				/* ── Banner / thumbnail / play icon ──────────────────────── */
				'imageBanner'          => array(
					'type'    => 'string',
					'enum'    => array( 'banner_img', 'video_thumb' ),
					'default' => 'banner_img',
				),
				'showBannerImg'        => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'bannerImgUrl'         => array(
					'type'    => 'string',
					'default' => '',
				),
				'bannerImgId'          => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'bannerImgSize'        => array(
					'type'    => 'string',
					'default' => 'full',
				),
				'videoIconUrl'         => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Custom play-icon image.',
				),
				'videoIconId'          => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'videoIconSize'        => array(
					'type'    => 'string',
					'default' => 'full',
				),
				'iconAlign'            => array(
					'type'    => 'string',
					'enum'    => array( 'left', 'center', 'right' ),
					'default' => 'center',
				),
				'overlayIconUrl'       => array(
					'type'    => 'string',
					'default' => '',
				),
				'overlayIconId'        => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'overlayIconSize'      => array(
					'type'    => 'string',
					'default' => 'full',
				),
				'playIconSize'         => array(
					'type'    => 'integer',
					'default' => 100,
				),
				'iconRadius'           => array( 'type' => 'object' ),

				/* ── Title / description ─────────────────────────────────── */
				'videoTitle'           => array(
					'type'    => 'string',
					'default' => 'Earth from the Moon',
				),
				'videoTitleTypoSize'   => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'titleColor'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'titleBgColor'         => array(
					'type'    => 'string',
					'default' => '',
				),
				'videoDesc'            => array(
					'type'    => 'string',
					'default' => '',
				),
				'videoDescTypoSize'    => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'descColor'            => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Schema / a11y ────────────────────────────────────────── */
				'markupSchema'         => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'iframeTitle'          => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Video container styling ──────────────────────────────── */
				'videoBorder'          => array( 'type' => 'object' ),
				'videoRadius'          => array( 'type' => 'object' ),
				'videoShadow'          => array( 'type' => 'object' ),
				'transform'            => array(
					'type'    => 'string',
					'default' => '',
				),
				'videoBorderHover'     => array( 'type' => 'object' ),
				'videoRadiusHover'     => array( 'type' => 'object' ),
				'videoShadowHover'     => array( 'type' => 'object' ),
				'transformHover'       => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Continuous animation ─────────────────────────────────── */
				'continueAnim'         => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'continueAnimHover'    => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Animate only on hover.',
				),
				'continueAnimEffect'   => array(
					'type'    => 'string',
					'enum'    => array( 'pulse', 'rotating', 'drop_waves', 'wobble', 'bounce', 'heartbeat' ),
					'default' => 'pulse',
				),
				'continueAnimDuration' => array(
					'type'    => 'string',
					'default' => '2.5',
				),
				'continueTransRotate'  => array(
					'type'    => 'string',
					'default' => 'center center',
				),
				'dropWaveColor'        => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Visibility / Globals ─────────────────────────────────── */
				'showBlockContent'     => array(
					'type'    => 'boolean',
					'default' => true,
				),
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
				'globalBgColor'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalBgHoverColor'   => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalBorder'         => array( 'type' => 'object' ),
				'globalBorderHover'    => array( 'type' => 'object' ),
				'globalBRadius'        => array( 'type' => 'object' ),
				'globalBRadiusHover'   => array( 'type' => 'object' ),
				'globalBShadow'        => array( 'type' => 'object' ),
				'globalBShadowHover'   => array( 'type' => 'object' ),
				'scrollAnimation'      => array(
					'type'    => 'string',
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

				'settings'             => array(
					'type'        => 'object',
					'description' => 'Raw attribute overrides.',
				),
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

		'execute_callback'    => 'tpgb_mcp_add_video_ability',
		'permission_callback' => 'tpgb_mcp_add_video_permission',
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
 * Permission callback for the add-video ability.
 *
 * @param array|null $input Ability input arguments.
 * @return bool True when the current user may insert the block.
 */
function tpgb_mcp_add_video_permission( ?array $input = null ): bool {
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
function tpgb_mcp_vd_spacing( array $v ): array {
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
function tpgb_mcp_vd_border( array $b ): array {
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
function tpgb_mcp_vd_radius( array $r ): array {
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
function tpgb_mcp_vd_bshadow( array $s ): array {
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
function tpgb_mcp_vd_bg( string $color ): array {
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
function tpgb_mcp_vd_typo( int $size ): array {
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
function tpgb_mcp_vd_needs_wrapper( array $attrs ): bool {
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
 * Execute callback for the add-video ability.
 *
 * @param array $input Ability input arguments.
 * @return array|WP_Error Result payload or WP_Error on failure.
 */
function tpgb_mcp_add_video_ability( array $input ) {

	if ( ! defined( 'TPGB_VERSION' ) && ! defined( 'TPGBP_VERSION' ) ) {
		return new WP_Error( 'nexter_blocks_missing', __( 'Nexter Blocks must be active.', 'the-plus-addons-for-block-editor' ) );
	}

	$block_name = 'tpgb/tp-video';
	if ( ! tpgb_mcp_has_registered_block( $block_name ) ) {
		return new WP_Error( 'block_missing', __( 'tpgb/tp-video is not registered.', 'the-plus-addons-for-block-editor' ) );
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

	/* ── Source ──────────────────────────────────────────────────────── */
	if ( ! empty( $input['videoType'] ) && 'youtube' !== $input['videoType'] ) {
		$attrs['VideoType'] = sanitize_key( $input['videoType'] ); }
	if ( ! empty( $input['youtubeId'] ) && 'TJ1SDXbij8Y' !== $input['youtubeId'] ) {
		$attrs['YoutubeID'] = sanitize_text_field( $input['youtubeId'] ); }
	if ( ! empty( $input['vimeoId'] ) && '27246366' !== $input['vimeoId'] ) {
		$attrs['VimeoID'] = sanitize_text_field( $input['vimeoId'] ); }
	if ( ! empty( $input['mp4Url'] ) || ! empty( $input['mp4Id'] ) ) {
		$attrs['mp4Url'] = array(
			'url' => esc_url_raw( $input['mp4Url'] ?? '' ),
			'id'  => absint( $input['mp4Id'] ?? 0 ),
		);
	}
	if ( ! empty( $input['fallbackImageUrl'] ) || ! empty( $input['fallbackImageId'] ) ) {
		$attrs['fallbackImage'] = array(
			'url' => esc_url_raw( $input['fallbackImageUrl'] ?? '' ),
			'id'  => absint( $input['fallbackImageId'] ?? 0 ),
		);
	}
	if ( ! empty( $input['secVidUrl'] ) || ! empty( $input['secVidId'] ) ) {
		$attrs['secVid'] = array(
			'url' => esc_url_raw( $input['secVidUrl'] ?? '' ),
			'id'  => absint( $input['secVidId'] ?? 0 ),
		);
	}

	/* ── Player options ──────────────────────────────────────────────── */
	if ( ! empty( $input['autoPlay'] ) ) {
		$attrs['autoPlay'] = true; }
	if ( ! empty( $input['muted'] ) ) {
		$attrs['muted'] = true; }
	if ( ! empty( $input['loop'] ) ) {
		$attrs['loop'] = true; }
	if ( isset( $input['controls'] ) && false === $input['controls'] ) {
		$attrs['controls'] = false; }
	if ( isset( $input['showInfo'] ) && false === $input['showInfo'] ) {
		$attrs['showinfo'] = false; }
	if ( ! empty( $input['touchDisable'] ) ) {
		$attrs['touchDisable'] = true; }
	if ( ! empty( $input['modestBranding'] ) ) {
		$attrs['ModestBranding'] = true; }
	if ( ! empty( $input['rel'] ) ) {
		$attrs['rel'] = true; }
	if ( ! empty( $input['ytPrivacy'] ) ) {
		$attrs['yt_privacy'] = true; }
	if ( ! empty( $input['videoColor'] ) ) {
		$attrs['VideoColor'] = sanitize_text_field( $input['videoColor'] ); }
	if ( isset( $input['vimeoTitle'] ) && false === $input['vimeoTitle'] ) {
		$attrs['VimeoTitle'] = false; }
	if ( isset( $input['vimeoPortrait'] ) && false === $input['vimeoPortrait'] ) {
		$attrs['VimeoPortrait'] = false; }
	if ( isset( $input['vimeoByline'] ) && false === $input['vimeoByline'] ) {
		$attrs['VimeoByline'] = false; }

	/* ── Popup ───────────────────────────────────────────────────────── */
	if ( ! empty( $input['videoPopup'] ) ) {
		$attrs['VideoPopup'] = true; }

	/* ── Banner / icon ───────────────────────────────────────────────── */
	if ( ! empty( $input['imageBanner'] ) && 'banner_img' !== $input['imageBanner'] ) {
		$attrs['image_banner'] = sanitize_key( $input['imageBanner'] ); }
	if ( ! empty( $input['showBannerImg'] ) ) {
		$attrs['ShowBannerImg'] = true; }
	if ( ! empty( $input['bannerImgUrl'] ) || ! empty( $input['bannerImgId'] ) ) {
		$attrs['BannerImg'] = array(
			'url' => esc_url_raw( $input['bannerImgUrl'] ?? '' ),
			'id'  => absint( $input['bannerImgId'] ?? 0 ),
		);
	}
	if ( ! empty( $input['bannerImgSize'] ) && 'full' !== $input['bannerImgSize'] ) {
		$attrs['BannerImgSize'] = sanitize_text_field( $input['bannerImgSize'] ); }
	if ( ! empty( $input['videoIconUrl'] ) || ! empty( $input['videoIconId'] ) ) {
		$attrs['VideoIcon'] = array(
			'url' => esc_url_raw( $input['videoIconUrl'] ?? '' ),
			'id'  => absint( $input['videoIconId'] ?? 0 ),
		);
	}
	if ( ! empty( $input['videoIconSize'] ) && 'full' !== $input['videoIconSize'] ) {
		$attrs['VideoIconSize'] = sanitize_text_field( $input['videoIconSize'] ); }
	if ( ! empty( $input['iconAlign'] ) && 'center' !== $input['iconAlign'] ) {
		$attrs['IconAlign'] = sanitize_text_field( $input['iconAlign'] ); }
	if ( ! empty( $input['overlayIconUrl'] ) || ! empty( $input['overlayIconId'] ) ) {
		$attrs['OverlayIconImg'] = array(
			'url' => esc_url_raw( $input['overlayIconUrl'] ?? '' ),
			'id'  => absint( $input['overlayIconId'] ?? 0 ),
		);
	}
	if ( ! empty( $input['overlayIconSize'] ) && 'full' !== $input['overlayIconSize'] ) {
		$attrs['OverlayIconImgSize'] = sanitize_text_field( $input['overlayIconSize'] ); }
	if ( ! empty( $input['playIconSize'] ) && 100 !== intval( $input['playIconSize'] ) ) {
		$attrs['PlayIconSize'] = (string) absint( $input['playIconSize'] ); }
	if ( ! empty( $input['iconRadius'] ) ) {
		$attrs['IconRadius'] = tpgb_mcp_vd_radius( $input['iconRadius'] ); }

	/* ── Title / description ─────────────────────────────────────────── */
	if ( ! empty( $input['videoTitle'] ) && 'Earth from the Moon' !== $input['videoTitle'] ) {
		$attrs['VideoTitle'] = sanitize_text_field( $input['videoTitle'] ); }
	if ( ! empty( $input['videoTitleTypoSize'] ) ) {
		$attrs['VideoTitleTypo'] = tpgb_mcp_vd_typo( (int) $input['videoTitleTypoSize'] ); }
	if ( ! empty( $input['titleColor'] ) && '#313131' !== $input['titleColor'] ) {
		$attrs['TitleColor'] = sanitize_text_field( $input['titleColor'] ); }
	if ( ! empty( $input['titleBgColor'] ) && '#ffffff' !== $input['titleBgColor'] ) {
		$attrs['TitleBgColor'] = sanitize_text_field( $input['titleBgColor'] ); }
	if ( ! empty( $input['videoDesc'] ) ) {
		$attrs['VideoDesc'] = tpgb_mcp_clean_text( $input['videoDesc'] ); }
	if ( ! empty( $input['videoDescTypoSize'] ) ) {
		$attrs['VideoDescTypo'] = tpgb_mcp_vd_typo( (int) $input['videoDescTypoSize'] ); }
	if ( ! empty( $input['descColor'] ) ) {
		$attrs['DescColor'] = sanitize_text_field( $input['descColor'] ); }

	/* ── Schema / a11y ───────────────────────────────────────────────── */
	if ( ! empty( $input['markupSchema'] ) ) {
		$attrs['markupSch'] = true; }
	if ( ! empty( $input['iframeTitle'] ) ) {
		$attrs['iframeTitle'] = sanitize_text_field( $input['iframeTitle'] ); }

	/* ── Video container ─────────────────────────────────────────────── */
	if ( ! empty( $input['videoBorder'] ) ) {
		$attrs['VideoBorder'] = tpgb_mcp_vd_border( $input['videoBorder'] ); }
	if ( ! empty( $input['videoRadius'] ) ) {
		$attrs['VideoBRadius'] = tpgb_mcp_vd_radius( $input['videoRadius'] ); }
	if ( ! empty( $input['videoShadow'] ) ) {
		$attrs['BoxShadow'] = tpgb_mcp_vd_bshadow( $input['videoShadow'] ); }
	if ( ! empty( $input['transform'] ) ) {
		$attrs['Transform'] = sanitize_text_field( $input['transform'] ); }
	if ( ! empty( $input['videoBorderHover'] ) ) {
		$attrs['VideoBorderH'] = tpgb_mcp_vd_border( $input['videoBorderHover'] ); }
	if ( ! empty( $input['videoRadiusHover'] ) ) {
		$attrs['VideoBRadiusH'] = tpgb_mcp_vd_radius( $input['videoRadiusHover'] ); }
	if ( ! empty( $input['videoShadowHover'] ) ) {
		$attrs['BoxShadowH'] = tpgb_mcp_vd_bshadow( $input['videoShadowHover'] ); }
	if ( ! empty( $input['transformHover'] ) ) {
		$attrs['TransformH'] = sanitize_text_field( $input['transformHover'] ); }

	/* ── Continuous animation ────────────────────────────────────────── */
	if ( ! empty( $input['continueAnim'] ) ) {
		$attrs['ContinueAnim'] = true;
		if ( ! empty( $input['continueAnimHover'] ) ) {
			$attrs['ContinueAnimHover'] = true; }
		if ( ! empty( $input['continueAnimEffect'] ) && 'pulse' !== $input['continueAnimEffect'] ) {
			$attrs['ContinueAnimEffect'] = sanitize_key( $input['continueAnimEffect'] ); }
		if ( ! empty( $input['continueAnimDuration'] ) && '2.5' !== (string) $input['continueAnimDuration'] ) {
			$attrs['ContinueAnimDur'] = sanitize_text_field( (string) $input['continueAnimDuration'] ); }
		if ( ! empty( $input['continueTransRotate'] ) && 'center center' !== $input['continueTransRotate'] ) {
			$attrs['ContinueTransRotate'] = sanitize_text_field( $input['continueTransRotate'] ); }
		if ( ! empty( $input['dropWaveColor'] ) ) {
			$attrs['DropWaveColor'] = sanitize_text_field( $input['dropWaveColor'] ); }
	}

	/* ── Visibility / Globals ────────────────────────────────────────── */
	if ( isset( $input['showBlockContent'] ) && false === $input['showBlockContent'] ) {
		$attrs['showBlockContent'] = false; }
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
		$attrs['globalMargin'] = tpgb_mcp_vd_spacing( $input['globalMargin'] ); }
	if ( ! empty( $input['globalPadding'] ) ) {
		$attrs['globalPadding'] = tpgb_mcp_vd_spacing( $input['globalPadding'] ); }
	if ( ! empty( $input['globalBgColor'] ) ) {
		$attrs['globalBg'] = tpgb_mcp_vd_bg( $input['globalBgColor'] ); }
	if ( ! empty( $input['globalBgHoverColor'] ) ) {
		$attrs['globalBgHover'] = tpgb_mcp_vd_bg( $input['globalBgHoverColor'] ); }
	if ( ! empty( $input['globalBorder'] ) ) {
		$attrs['globalBorder'] = tpgb_mcp_vd_border( $input['globalBorder'] ); }
	if ( ! empty( $input['globalBorderHover'] ) ) {
		$attrs['globalBorderHover'] = tpgb_mcp_vd_border( $input['globalBorderHover'] ); }
	if ( ! empty( $input['globalBRadius'] ) ) {
		$attrs['globalBRadius'] = tpgb_mcp_vd_radius( $input['globalBRadius'] ); }
	if ( ! empty( $input['globalBRadiusHover'] ) ) {
		$attrs['globalBRadiusHover'] = tpgb_mcp_vd_radius( $input['globalBRadiusHover'] ); }
	if ( ! empty( $input['globalBShadow'] ) ) {
		$attrs['globalBShadow'] = tpgb_mcp_vd_bshadow( $input['globalBShadow'] ); }
	if ( ! empty( $input['globalBShadowHover'] ) ) {
		$attrs['globalBShadowHover'] = tpgb_mcp_vd_bshadow( $input['globalBShadowHover'] ); }
	if ( ! empty( $input['scrollAnimation'] ) ) {
		$attrs['globalAnim'] = array( 'md' => sanitize_text_field( $input['scrollAnimation'] ) ); }
	if ( ! empty( $input['animDuration'] ) ) {
		$attrs['globalAnimDuration'] = sanitize_text_field( $input['animDuration'] ); }
	if ( ! empty( $input['animDelay'] ) ) {
		$attrs['globalAnimDelay'] = array( 'md' => sanitize_text_field( $input['animDelay'] ) ); }
	if ( ! empty( $input['animEasing'] ) ) {
		$attrs['globalAnimEasing'] = sanitize_text_field( $input['animEasing'] ); }

	if ( tpgb_mcp_vd_needs_wrapper( $attrs ) ) {
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
