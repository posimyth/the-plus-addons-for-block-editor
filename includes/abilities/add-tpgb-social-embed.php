<?php
/**
 * Ability: Add Nexter Blocks Social Embed (tpgb/tp-social-embed) to a Gutenberg page.
 *
 * @package The_Plus_Addons_For_Block_Editor
 * @since   1.3.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_register_ability(
	'nexter-blocks/add-tpgb-social-embed',
	array(
		'label'               => __( 'Add Nexter Blocks Social Embed', 'the-plus-addons-for-block-editor' ),
		'description'         => __( 'Adds the Nexter Blocks Social Embed block (tpgb/tp-social-embed) — embeds posts/videos/pages/buttons from Facebook (posts/videos/comments/like-button/page/share-button), Twitter (timeline/tweet/buttons/collection/list), Instagram (posts/profile), YouTube (single video/playlist/username), Vimeo (video), and Google Maps (place/directions/streetview/search/view). Each network has its own dedicated parameter set; only fill in the ones for the chosen embedType. This is a dynamic block.', 'the-plus-addons-for-block-editor' ),
		'category'            => 'nexter-blocks',

		'input_schema'        => array(
			'type'                 => 'object',
			'properties'           => array(

				/* ── Core ─────────────────────────────────────────────────── */
				'post_id'              => array(
					'type'        => 'integer',
					'description' => 'WordPress page/post ID to insert the block into.',
				),
				'position'             => array(
					'type'        => 'integer',
					'default'     => -1,
					'description' => 'Zero-based insert position. Use -1 to append.',
				),
				'parent_block_id'      => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Optional parent block_id to nest this block.',
				),

				/* ── Network ─────────────────────────────────────────────── */
				'embedType'            => array(
					'type'        => 'string',
					'enum'        => array( 'facebook', 'twitter', 'instagram', 'youtube', 'vimeo', 'googlemap' ),
					'description' => 'Social network / map provider.',
					'default'     => 'facebook',
				),

				/* ── FACEBOOK ─────────────────────────────────────────────── */
				'fbType'               => array(
					'type'        => 'string',
					'enum'        => array( 'posts', 'videos', 'comments', 'likebtn', 'page', 'sharebtn' ),
					'description' => 'Facebook embed sub-type.',
					'default'     => 'videos',
				),
				'fbAppId'              => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Facebook App ID (optional).',
				),
				'fbPostUrl'            => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Facebook post URL (when fbType = posts).',
				),
				'fbVideoUrl'           => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Facebook video URL (when fbType = videos).',
				),
				'fbCommentUrl'         => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'URL to attach the comment widget to.',
				),
				'fbCommentCount'       => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Number of comments to show.',
				),
				'fbCommentOrder'       => array(
					'type'    => 'string',
					'enum'    => array( '', 'social', 'reverse_time', 'time' ),
					'default' => 'social',
				),
				'fbLikeUrl'            => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'URL for the like button.',
				),
				'fbLikeType'           => array(
					'type'    => 'string',
					'enum'    => array( 'like', 'recommend' ),
					'default' => 'like',
				),
				'fbLikeStyle'          => array(
					'type'    => 'string',
					'enum'    => array( 'button', 'button_count', 'box_count', 'standard' ),
					'default' => 'button',
				),
				'fbLikeSize'           => array(
					'type'    => 'string',
					'enum'    => array( 'small', 'large' ),
					'default' => 'small',
				),
				'fbLikeColor'          => array(
					'type'    => 'string',
					'enum'    => array( 'light', 'dark' ),
					'default' => 'light',
				),
				'fbLikeShare'          => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Show share button alongside like.',
				),
				'fbLikeFaces'          => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Show friends\' faces.',
				),
				'fbPageUrl'            => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Facebook page URL (when fbType = page).',
				),
				'fbPageLayout'         => array(
					'type'    => 'string',
					'enum'    => array( 'timeline', 'events', 'messages' ),
					'default' => 'timeline',
				),
				'fbPageSmallHeader'    => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'fbPageHideCover'      => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Set true to HIDE cover photo.',
				),
				'fbPageHideProfile'    => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'fbPageHideCta'        => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'fbShareUrl'           => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'URL to share (fbType = sharebtn).',
				),
				'fbShareStyle'         => array(
					'type'    => 'string',
					'enum'    => array( 'box_count', 'button_count', 'button', 'icon_link', 'icon', 'link' ),
					'default' => 'button',
				),

				/* sizes (works for posts/videos/page/likebtn/share) */
				'fbWidth'              => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Width in px.',
				),
				'fbHeight'             => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Height in px.',
				),

				/* video extras */
				'fbVideoAutoplay'      => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'fbVideoCaption'       => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'fbVideoFullText'      => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Include full post text.',
				),
				'fbPostFullText'       => array(
					'type'    => 'boolean',
					'default' => false,
				),

				/* ── TWITTER ──────────────────────────────────────────────── */
				'twType'               => array(
					'type'        => 'string',
					'enum'        => array( 'timelines', 'tweets', 'buttons', 'collection', 'list' ),
					'description' => 'Twitter embed sub-type.',
					'default'     => 'timelines',
				),
				'twTimelineMode'       => array(
					'type'    => 'string',
					'enum'    => array( 'profile', 'likes' ),
					'default' => 'profile',
				),
				'twStyle'              => array(
					'type'    => 'string',
					'enum'    => array( 'linear', 'classic' ),
					'default' => 'linear',
				),
				'twUsername'           => array(
					'type'        => 'string',
					'default'     => 'TwitterDev',
					'description' => 'Twitter @username (without @).',
				),
				'twUserId'             => array(
					'type'    => 'string',
					'default' => '3805104374',
				),
				'twTheme'              => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'true = dark theme.',
				),
				'twCards'              => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Show media cards.',
				),
				'twAlignment'          => array(
					'type'    => 'string',
					'enum'    => array( 'left', 'center', 'right' ),
					'default' => 'center',
				),
				'twConversation'       => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'twWidth'              => array(
					'type'    => 'string',
					'default' => '',
				),
				'twHeight'             => array(
					'type'    => 'string',
					'default' => '500',
				),
				'twLimit'              => array(
					'type'    => 'string',
					'default' => '',
				),
				'twBorderColor'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'twCollectionUrl'      => array(
					'type'    => 'string',
					'default' => '',
				),
				'twListUrl'            => array(
					'type'    => 'string',
					'default' => '',
				),
				'twTweetId'            => array(
					'type'    => 'string',
					'default' => '463440424141459456',
				),
				'twTweetUrl'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'twButtonType'         => array(
					'type'    => 'string',
					'enum'    => array( 'follow', 'mention', 'hashtag', 'share' ),
					'default' => 'follow',
				),
				'twButtonSize'         => array(
					'type'    => 'string',
					'enum'    => array( '', 'large' ),
					'default' => '',
				),
				'twButtonText'         => array(
					'type'    => 'string',
					'default' => 'Hello',
				),
				'twHashtags'           => array(
					'type'    => 'string',
					'default' => 'Twitter',
				),
				'twVia'                => array(
					'type'    => 'string',
					'default' => 'Twitter',
				),
				'twMessage'            => array(
					'type'    => 'string',
					'default' => 'Hello',
				),
				'twShowCount'          => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'twHideUsername'       => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'twShowIcon'           => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'twLikeBtnText'        => array(
					'type'    => 'string',
					'default' => 'Like',
				),
				'twReplyBtnText'       => array(
					'type'    => 'string',
					'default' => 'Reply',
				),
				'twRetweetBtnText'     => array(
					'type'    => 'string',
					'default' => 'Retweet',
				),
				'twLoadingMsg'         => array(
					'type'    => 'string',
					'default' => 'Loading',
				),

				/* ── INSTAGRAM ────────────────────────────────────────────── */
				'igType'               => array(
					'type'    => 'string',
					'enum'    => array( 'posts', 'profile' ),
					'default' => 'posts',
				),
				'igPostId'             => array(
					'type'        => 'string',
					'default'     => 'CGAvnLcA3zb',
					'description' => 'Instagram post short-code/ID.',
				),
				'igCaption'            => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Show caption.',
				),

				/* ── YOUTUBE ──────────────────────────────────────────────── */
				'ytType'               => array(
					'type'        => 'string',
					'enum'        => array( 'ytSV', 'ytPL', 'ytU' ),
					'description' => 'ytSV = single video, ytPL = playlist, ytU = username.',
					'default'     => 'ytSV',
				),
				'ytVideoId'            => array(
					'type'    => 'string',
					'default' => 'XmtXC_n6X6Q',
				),
				'ytPlaylistId'         => array(
					'type'    => 'string',
					'default' => 'PLivjPDlt6ApQgylktXlL2AhuPvRtDiN1S',
				),
				'ytUsername'           => array(
					'type'    => 'string',
					'default' => 'NationalGeographic',
				),
				'ytStartTime'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'ytEndTime'            => array(
					'type'    => 'string',
					'default' => '',
				),
				'ytLanguage'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'ytWidth'              => array(
					'type'    => 'string',
					'default' => '640',
				),
				'ytHeight'             => array(
					'type'    => 'string',
					'default' => '360',
				),

				/* ── VIMEO ────────────────────────────────────────────────── */
				'vimeoId'              => array(
					'type'    => 'string',
					'default' => '288344114',
				),
				'vimeoStart'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'vimeoColor'           => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── GOOGLE MAP ───────────────────────────────────────────── */
				'gmapMode'             => array(
					'type'        => 'string',
					'enum'        => array( 'place', 'directions', 'streetview', 'search', 'view' ),
					'description' => 'Google Maps embed mode.',
					'default'     => 'place',
				),
				'gmapAccessTokenSrc'   => array(
					'type'    => 'string',
					'enum'    => array( 'default', 'custom' ),
					'default' => 'default',
				),
				'gmapApiKey'           => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Google Maps API key (when accessTokenSrc = custom).',
				),
				'gmapSearch'           => array(
					'type'    => 'string',
					'default' => 'Goa+India',
				),
				'gmapOrigin'           => array(
					'type'    => 'string',
					'default' => 'LosAngeles+California+USA',
				),
				'gmapDestination'      => array(
					'type'    => 'string',
					'default' => 'Corona+California+USA',
				),
				'gmapWaypoints'        => array(
					'type'    => 'string',
					'default' => 'Huntington+Beach+California+US | Santa Ana+California+USA',
				),
				'gmapTravelMode'       => array(
					'type'    => 'string',
					'enum'    => array( 'driving', 'walking', 'bicycling', 'transit', 'flying' ),
					'default' => 'driving',
				),
				'gmapAvoidTolls'       => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'gmapAvoidHighways'    => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'gmapStreetviewCoords' => array(
					'type'    => 'string',
					'default' => '23.0489,72.5160',
				),
				'gmapView'             => array(
					'type'    => 'string',
					'enum'    => array( 'roadmap', 'satellite' ),
					'default' => 'roadmap',
				),
				'gmapZoom'             => array(
					'type'    => 'string',
					'default' => '5',
				),
				'gmapHeight'           => array(
					'type'    => 'string',
					'default' => '350',
				),
				'iframeTitle'          => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'iframe title attribute (a11y).',
				),

				/* ── Style ────────────────────────────────────────────────── */
				'alignment'            => array(
					'type'    => 'string',
					'enum'    => array( '', 'left', 'center', 'right' ),
					'default' => '',
				),
				'twButtonColor'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'twButtonColorHover'   => array(
					'type'    => 'string',
					'default' => '',
				),
				'borderPost'           => array( 'type' => 'object' ),
				'borderRadius'         => array( 'type' => 'object' ),
				'boxShadow'            => array( 'type' => 'object' ),
				'borderPostHover'      => array( 'type' => 'object' ),
				'borderRadiusHover'    => array( 'type' => 'object' ),
				'boxShadowHover'       => array( 'type' => 'object' ),
				'socialBgColor'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'embedBorder'          => array( 'type' => 'object' ),
				'embedShadow'          => array( 'type' => 'object' ),

				/* ── Visibility / identity / global ───────────────────────── */
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
					'description' => 'Raw attribute overrides merged directly into the block.',
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

		'execute_callback'    => 'tpgb_mcp_add_social_embed_ability',
		'permission_callback' => 'tpgb_mcp_add_social_embed_permission',
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
 * Permission callback for the add-social-embed ability.
 *
 * @param array|null $input Ability input arguments.
 * @return bool True when the current user may insert the block.
 */
function tpgb_mcp_add_social_embed_permission( ?array $input = null ): bool {
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
function tpgb_mcp_se_spacing( array $v ): array {
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
function tpgb_mcp_se_border( array $b ): array {
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
function tpgb_mcp_se_radius( array $r ): array {
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
function tpgb_mcp_se_bshadow( array $s ): array {
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
function tpgb_mcp_se_bg( string $color ): array {
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
 * Build a Nexter Blocks URL attribute (url + target + nofollow).
 *
 * @param string $url Raw URL value.
 * @return array URL attribute structured for the block.
 */
function tpgb_mcp_se_url( string $url ): array {
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
function tpgb_mcp_se_needs_wrapper( array $attrs ): bool {
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
 * Execute callback for the add-social-embed ability.
 *
 * @param array $input Ability input arguments.
 * @return array|WP_Error Result payload or WP_Error on failure.
 */
function tpgb_mcp_add_social_embed_ability( array $input ) {

	if ( ! defined( 'TPGB_VERSION' ) && ! defined( 'TPGBP_VERSION' ) ) {
		return new WP_Error( 'nexter_blocks_missing', __( 'Nexter Blocks must be active.', 'the-plus-addons-for-block-editor' ) );
	}

	$block_name = 'tpgb/tp-social-embed';
	if ( ! tpgb_mcp_has_registered_block( $block_name ) ) {
		return new WP_Error( 'block_missing', __( 'tpgb/tp-social-embed is not registered.', 'the-plus-addons-for-block-editor' ) );
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

	/* ── Network ──────────────────────────────────────────────────────── */
	$network = sanitize_key( $input['embedType'] ?? 'facebook' );
	if ( 'facebook' !== $network ) {
		$attrs['embedType'] = $network; }

	/* ── FACEBOOK ─────────────────────────────────────────────────────── */
	if ( 'facebook' === $network ) {
		$fb_type = sanitize_key( $input['fbType'] ?? 'videos' );
		if ( 'videos' !== $fb_type ) {
			$attrs['type'] = $fb_type; }
		if ( ! empty( $input['fbAppId'] ) ) {
			$attrs['appID'] = sanitize_text_field( $input['fbAppId'] ); }
		if ( ! empty( $input['fbPostUrl'] ) ) {
			$attrs['postURL'] = tpgb_mcp_se_url( $input['fbPostUrl'] ); }
		if ( ! empty( $input['fbVideoUrl'] ) ) {
			$attrs['videosURL'] = tpgb_mcp_se_url( $input['fbVideoUrl'] ); }
		if ( ! empty( $input['fbCommentUrl'] ) ) {
			$attrs['commentAddURL'] = tpgb_mcp_se_url( $input['fbCommentUrl'] ); }
		if ( ! empty( $input['fbCommentCount'] ) ) {
			$attrs['countC'] = sanitize_text_field( $input['fbCommentCount'] ); }
		if ( ! empty( $input['fbCommentOrder'] ) && 'social' !== $input['fbCommentOrder'] ) {
			$attrs['orderByC'] = sanitize_text_field( $input['fbCommentOrder'] ); }
		if ( ! empty( $input['fbLikeUrl'] ) ) {
			$attrs['likeBtnUrl'] = tpgb_mcp_se_url( $input['fbLikeUrl'] ); }
		if ( ! empty( $input['fbLikeType'] ) && 'like' !== $input['fbLikeType'] ) {
			$attrs['typeLB'] = sanitize_text_field( $input['fbLikeType'] ); }
		if ( ! empty( $input['fbLikeStyle'] ) && 'button' !== $input['fbLikeStyle'] ) {
			$attrs['btnStyleLB'] = sanitize_text_field( $input['fbLikeStyle'] ); }
		if ( ! empty( $input['fbLikeSize'] ) && 'small' !== $input['fbLikeSize'] ) {
			$attrs['sizeLB'] = sanitize_text_field( $input['fbLikeSize'] ); }
		if ( ! empty( $input['fbLikeColor'] ) && 'light' !== $input['fbLikeColor'] ) {
			$attrs['colorSLB'] = sanitize_text_field( $input['fbLikeColor'] ); }
		if ( ! empty( $input['fbLikeShare'] ) ) {
			$attrs['sBtnLB'] = true; }
		if ( ! empty( $input['fbLikeFaces'] ) ) {
			$attrs['facesLBT'] = true; }
		if ( ! empty( $input['fbPageUrl'] ) ) {
			$attrs['uRLP'] = tpgb_mcp_se_url( $input['fbPageUrl'] ); }
		if ( ! empty( $input['fbPageLayout'] ) && 'timeline' !== $input['fbPageLayout'] ) {
			$attrs['layoutP'] = sanitize_text_field( $input['fbPageLayout'] ); }
		if ( ! empty( $input['fbPageSmallHeader'] ) ) {
			$attrs['smallHP'] = true; }
		if ( ! empty( $input['fbPageHideCover'] ) ) {
			$attrs['coverP'] = false; }
		if ( ! empty( $input['fbPageHideProfile'] ) ) {
			$attrs['profileP'] = false; }
		if ( ! empty( $input['fbPageHideCta'] ) ) {
			$attrs['ctaBtn'] = false; }
		if ( ! empty( $input['fbShareUrl'] ) ) {
			$attrs['shareURL'] = tpgb_mcp_se_url( $input['fbShareUrl'] ); }
		if ( ! empty( $input['fbShareStyle'] ) && 'button' !== $input['fbShareStyle'] ) {
			$attrs['shareBtn'] = sanitize_text_field( $input['fbShareStyle'] ); }

		/* sizes — disambiguate per fbType */
		if ( ! empty( $input['fbWidth'] ) || ! empty( $input['fbHeight'] ) ) {
			$w = ! empty( $input['fbWidth'] ) ? sanitize_text_field( (string) $input['fbWidth'] ) : '';
			$h = ! empty( $input['fbHeight'] ) ? sanitize_text_field( (string) $input['fbHeight'] ) : '';
			if ( 'videos' === $fb_type ) {
				$attrs['wdVideo'] = $w;
				$attrs['hgVideo'] = $h; } elseif ( 'posts' === $fb_type ) {
				$attrs['wdPost'] = $w;
				$attrs['hgPost'] = $h; } elseif ( 'page' === $fb_type ) {
					$attrs['wdPage'] = $w;
					$attrs['hgPage'] = $h; } elseif ( 'likebtn' === $fb_type ) {
					$attrs['wdLikeBtn'] = $w;
					$attrs['hgLikeBtn'] = $h; } elseif ( 'sharebtn' === $fb_type ) {
							$attrs['wdShare'] = $w;
							$attrs['hgShare'] = $h; }
		}

		if ( ! empty( $input['fbVideoFullText'] ) ) {
			$attrs['fullVT'] = true; }
		if ( ! empty( $input['fbVideoAutoplay'] ) ) {
			$attrs['autoplayVT'] = true; }
		if ( ! empty( $input['fbVideoCaption'] ) ) {
			$attrs['captionVT'] = true; }
		if ( ! empty( $input['fbPostFullText'] ) ) {
			$attrs['fullPT'] = true; }
	}

	/* ── TWITTER ──────────────────────────────────────────────────────── */
	if ( 'twitter' === $network ) {
		$tw_type = sanitize_key( $input['twType'] ?? 'timelines' );
		if ( 'timelines' !== $tw_type ) {
			$attrs['tweetType'] = $tw_type; }
		if ( ! empty( $input['twTimelineMode'] ) && 'profile' !== $input['twTimelineMode'] ) {
			$attrs['twGuides'] = sanitize_text_field( $input['twTimelineMode'] ); }
		if ( ! empty( $input['twStyle'] ) && 'linear' !== $input['twStyle'] ) {
			$attrs['twstyle'] = sanitize_text_field( $input['twStyle'] ); }
		if ( ! empty( $input['twUsername'] ) && 'TwitterDev' !== $input['twUsername'] ) {
			$attrs['twname'] = sanitize_text_field( $input['twUsername'] ); }
		if ( ! empty( $input['twUserId'] ) && '3805104374' !== $input['twUserId'] ) {
			$attrs['twRId'] = sanitize_text_field( $input['twUserId'] ); }
		if ( ! empty( $input['twTheme'] ) ) {
			$attrs['twColor'] = true; }
		if ( ! empty( $input['twCards'] ) ) {
			$attrs['twCards'] = true; }
		if ( ! empty( $input['twAlignment'] ) && 'center' !== $input['twAlignment'] ) {
			$attrs['twalign'] = sanitize_text_field( $input['twAlignment'] ); }
		if ( ! empty( $input['twConversation'] ) ) {
			$attrs['twconver'] = true; }
		if ( ! empty( $input['twWidth'] ) ) {
			$attrs['twwidth'] = sanitize_text_field( (string) $input['twWidth'] ); }
		if ( ! empty( $input['twHeight'] ) && '500' !== (string) $input['twHeight'] ) {
			$attrs['twheight'] = sanitize_text_field( (string) $input['twHeight'] ); }
		if ( ! empty( $input['twLimit'] ) ) {
			$attrs['twlimit'] = sanitize_text_field( (string) $input['twLimit'] ); }
		if ( ! empty( $input['twBorderColor'] ) ) {
			$attrs['twBrCr'] = sanitize_text_field( $input['twBorderColor'] ); }
		if ( ! empty( $input['twCollectionUrl'] ) ) {
			$attrs['twCollection'] = tpgb_mcp_se_url( $input['twCollectionUrl'] ); }
		if ( ! empty( $input['twListUrl'] ) ) {
			$attrs['twlisturl'] = tpgb_mcp_se_url( $input['twListUrl'] ); }
		if ( ! empty( $input['twTweetId'] ) && '463440424141459456' !== $input['twTweetId'] ) {
			$attrs['twTweetId'] = sanitize_text_field( $input['twTweetId'] ); }
		if ( ! empty( $input['twTweetUrl'] ) ) {
			$attrs['twTweetUrl'] = tpgb_mcp_se_url( $input['twTweetUrl'] ); }
		if ( ! empty( $input['twButtonType'] ) && 'follow' !== $input['twButtonType'] ) {
			$attrs['twbutton'] = sanitize_text_field( $input['twButtonType'] ); }
		if ( ! empty( $input['twButtonSize'] ) ) {
			$attrs['twBtnSize'] = sanitize_text_field( $input['twButtonSize'] ); }
		if ( ! empty( $input['twButtonText'] ) && 'Hello' !== $input['twButtonText'] ) {
			$attrs['twTextBtn'] = sanitize_text_field( $input['twButtonText'] ); }
		if ( ! empty( $input['twHashtags'] ) && 'Twitter' !== $input['twHashtags'] ) {
			$attrs['twHashtags'] = sanitize_text_field( $input['twHashtags'] ); }
		if ( ! empty( $input['twVia'] ) && 'Twitter' !== $input['twVia'] ) {
			$attrs['twVia'] = sanitize_text_field( $input['twVia'] ); }
		if ( ! empty( $input['twMessage'] ) && 'Hello' !== $input['twMessage'] ) {
			$attrs['twMessage'] = sanitize_text_field( $input['twMessage'] ); }
		if ( isset( $input['twShowCount'] ) && false === $input['twShowCount'] ) {
			$attrs['twCount'] = false; }
		if ( ! empty( $input['twHideUsername'] ) ) {
			$attrs['twHideUname'] = true; }
		if ( ! empty( $input['twShowIcon'] ) ) {
			$attrs['twIcon'] = true; }
		if ( ! empty( $input['twLikeBtnText'] ) && 'Like' !== $input['twLikeBtnText'] ) {
			$attrs['likeBtn'] = sanitize_text_field( $input['twLikeBtnText'] ); }
		if ( ! empty( $input['twReplyBtnText'] ) && 'Reply' !== $input['twReplyBtnText'] ) {
			$attrs['replyBtn'] = sanitize_text_field( $input['twReplyBtnText'] ); }
		if ( ! empty( $input['twRetweetBtnText'] ) && 'Retweet' !== $input['twRetweetBtnText'] ) {
			$attrs['reTweetBtn'] = sanitize_text_field( $input['twRetweetBtnText'] ); }
		if ( ! empty( $input['twLoadingMsg'] ) && 'Loading' !== $input['twLoadingMsg'] ) {
			$attrs['twMsg'] = sanitize_text_field( $input['twLoadingMsg'] ); }

		if ( ! empty( $input['twButtonColor'] ) ) {
			$attrs['twBtnCr'] = sanitize_text_field( $input['twButtonColor'] ); }
		if ( ! empty( $input['twButtonColorHover'] ) ) {
			$attrs['twBtnCrH'] = sanitize_text_field( $input['twButtonColorHover'] ); }
	}

	/* ── INSTAGRAM ────────────────────────────────────────────────────── */
	if ( 'instagram' === $network ) {
		if ( ! empty( $input['igType'] ) && 'posts' !== $input['igType'] ) {
			$attrs['iGType'] = sanitize_key( $input['igType'] ); }
		if ( ! empty( $input['igPostId'] ) && 'CGAvnLcA3zb' !== $input['igPostId'] ) {
			$attrs['iGId'] = sanitize_text_field( $input['igPostId'] ); }
		if ( ! empty( $input['igCaption'] ) ) {
			$attrs['iGCaptione'] = true; }
	}

	/* ── YOUTUBE ──────────────────────────────────────────────────────── */
	if ( 'youtube' === $network ) {
		if ( ! empty( $input['ytType'] ) && 'ytSV' !== $input['ytType'] ) {
			$attrs['ytType'] = sanitize_key( $input['ytType'] ); }
		if ( ! empty( $input['ytVideoId'] ) && 'XmtXC_n6X6Q' !== $input['ytVideoId'] ) {
			$attrs['ytVideoId'] = sanitize_text_field( $input['ytVideoId'] ); }
		if ( ! empty( $input['ytPlaylistId'] ) && 'PLivjPDlt6ApQgylktXlL2AhuPvRtDiN1S' !== $input['ytPlaylistId'] ) {
			$attrs['ytPlaylistId'] = sanitize_text_field( $input['ytPlaylistId'] ); }
		if ( ! empty( $input['ytUsername'] ) && 'NationalGeographic' !== $input['ytUsername'] ) {
			$attrs['ytUsername'] = sanitize_text_field( $input['ytUsername'] ); }
		if ( ! empty( $input['ytStartTime'] ) ) {
			$attrs['ytSTime'] = sanitize_text_field( (string) $input['ytStartTime'] ); }
		if ( ! empty( $input['ytEndTime'] ) ) {
			$attrs['ytETime'] = sanitize_text_field( (string) $input['ytEndTime'] ); }
		if ( ! empty( $input['ytLanguage'] ) ) {
			$attrs['ytlanguage'] = sanitize_text_field( $input['ytLanguage'] ); }
		if ( ! empty( $input['ytWidth'] ) && '640' !== (string) $input['ytWidth'] ) {
			$attrs['exWidth'] = sanitize_text_field( (string) $input['ytWidth'] ); }
		if ( ! empty( $input['ytHeight'] ) && '360' !== (string) $input['ytHeight'] ) {
			$attrs['exHeight'] = sanitize_text_field( (string) $input['ytHeight'] ); }
	}

	/* ── VIMEO ────────────────────────────────────────────────────────── */
	if ( 'vimeo' === $network ) {
		if ( ! empty( $input['vimeoId'] ) && '288344114' !== $input['vimeoId'] ) {
			$attrs['viId'] = sanitize_text_field( $input['vimeoId'] ); }
		if ( ! empty( $input['vimeoStart'] ) ) {
			$attrs['vmStime'] = sanitize_text_field( (string) $input['vimeoStart'] ); }
		if ( ! empty( $input['vimeoColor'] ) ) {
			$attrs['vmColor'] = sanitize_text_field( $input['vimeoColor'] ); }
	}

	/* ── GOOGLE MAP ───────────────────────────────────────────────────── */
	if ( 'googlemap' === $network ) {
		if ( ! empty( $input['gmapMode'] ) && 'place' !== $input['gmapMode'] ) {
			$attrs['gMapModes'] = sanitize_key( $input['gmapMode'] ); }
		if ( ! empty( $input['gmapAccessTokenSrc'] ) && 'default' !== $input['gmapAccessTokenSrc'] ) {
			$attrs['mapaccesstoken'] = sanitize_key( $input['gmapAccessTokenSrc'] ); }
		if ( ! empty( $input['gmapApiKey'] ) ) {
			$attrs['gAccesstoken'] = sanitize_text_field( $input['gmapApiKey'] ); }
		if ( ! empty( $input['gmapSearch'] ) && 'Goa+India' !== $input['gmapSearch'] ) {
			$attrs['gSearchText'] = sanitize_text_field( $input['gmapSearch'] ); }
		if ( ! empty( $input['gmapOrigin'] ) && 'LosAngeles+California+USA' !== $input['gmapOrigin'] ) {
			$attrs['gOrigin'] = sanitize_text_field( $input['gmapOrigin'] ); }
		if ( ! empty( $input['gmapDestination'] ) && 'Corona+California+USA' !== $input['gmapDestination'] ) {
			$attrs['gDestination'] = sanitize_text_field( $input['gmapDestination'] ); }
		if ( ! empty( $input['gmapWaypoints'] ) && 'Huntington+Beach+California+US | Santa Ana+California+USA' !== $input['gmapWaypoints'] ) {
			$attrs['gWaypoints'] = sanitize_text_field( $input['gmapWaypoints'] ); }
		if ( ! empty( $input['gmapTravelMode'] ) && 'driving' !== $input['gmapTravelMode'] ) {
			$attrs['gTravelMode'] = sanitize_text_field( $input['gmapTravelMode'] ); }
		if ( ! empty( $input['gmapAvoidTolls'] ) ) {
			$attrs['gavoidtolls'] = true; }
		if ( ! empty( $input['gmapAvoidHighways'] ) ) {
			$attrs['gavoidhighways'] = true; }
		if ( ! empty( $input['gmapStreetviewCoords'] ) && '23.0489,72.5160' !== $input['gmapStreetviewCoords'] ) {
			$attrs['gstreetviewText'] = sanitize_text_field( $input['gmapStreetviewCoords'] ); }
		if ( ! empty( $input['gmapView'] ) && 'roadmap' !== $input['gmapView'] ) {
			$attrs['mapViews'] = sanitize_key( $input['gmapView'] ); }
		if ( ! empty( $input['gmapZoom'] ) && '5' !== (string) $input['gmapZoom'] ) {
			$attrs['mapZoom'] = sanitize_text_field( (string) $input['gmapZoom'] ); }
		if ( ! empty( $input['gmapHeight'] ) && '350' !== (string) $input['gmapHeight'] ) {
			$attrs['gMHeight'] = sanitize_text_field( (string) $input['gmapHeight'] ); }
	}

	if ( ! empty( $input['iframeTitle'] ) ) {
		$attrs['iframeTitle'] = sanitize_text_field( $input['iframeTitle'] ); }

	/* ── Style ────────────────────────────────────────────────────────── */
	if ( ! empty( $input['alignment'] ) ) {
		$attrs['alignmentBG'] = array(
			'md' => sanitize_text_field( $input['alignment'] ),
			'sm' => '',
			'xs' => '',
		);
	}
	if ( ! empty( $input['borderPost'] ) ) {
		$attrs['borderPost'] = tpgb_mcp_se_border( $input['borderPost'] ); }
	if ( ! empty( $input['borderRadius'] ) ) {
		$attrs['borderRs'] = tpgb_mcp_se_radius( $input['borderRadius'] ); }
	if ( ! empty( $input['boxShadow'] ) ) {
		$attrs['boxS'] = tpgb_mcp_se_bshadow( $input['boxShadow'] ); }
	if ( ! empty( $input['borderPostHover'] ) ) {
		$attrs['borderPostHr'] = tpgb_mcp_se_border( $input['borderPostHover'] ); }
	if ( ! empty( $input['borderRadiusHover'] ) ) {
		$attrs['borderHRs'] = tpgb_mcp_se_radius( $input['borderRadiusHover'] ); }
	if ( ! empty( $input['boxShadowHover'] ) ) {
		$attrs['boxSHr'] = tpgb_mcp_se_bshadow( $input['boxShadowHover'] ); }
	if ( ! empty( $input['socialBgColor'] ) ) {
		$attrs['socialBg'] = tpgb_mcp_se_bg( $input['socialBgColor'] ); }
	if ( ! empty( $input['embedBorder'] ) ) {
		$attrs['embedBr'] = tpgb_mcp_se_border( $input['embedBorder'] ); }
	if ( ! empty( $input['embedShadow'] ) ) {
		$attrs['embedBsd'] = tpgb_mcp_se_bshadow( $input['embedShadow'] ); }

	/* ── Visibility ───────────────────────────────────────────────────── */
	if ( ! empty( $input['hideDesktop'] ) ) {
		$attrs['globalHideDesktop'] = true; }
	if ( ! empty( $input['hideTablet'] ) ) {
		$attrs['globalHideTablet'] = true; }
	if ( ! empty( $input['hideMobile'] ) ) {
		$attrs['globalHideMobile'] = true; }

	/* ── Identity / Layout / Globals ──────────────────────────────────── */
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
		$attrs['globalMargin'] = tpgb_mcp_se_spacing( $input['globalMargin'] ); }
	if ( ! empty( $input['globalPadding'] ) ) {
		$attrs['globalPadding'] = tpgb_mcp_se_spacing( $input['globalPadding'] ); }
	if ( ! empty( $input['globalBgColor'] ) ) {
		$attrs['globalBg'] = tpgb_mcp_se_bg( $input['globalBgColor'] ); }
	if ( ! empty( $input['globalBgHoverColor'] ) ) {
		$attrs['globalBgHover'] = tpgb_mcp_se_bg( $input['globalBgHoverColor'] ); }
	if ( ! empty( $input['globalBorder'] ) ) {
		$attrs['globalBorder'] = tpgb_mcp_se_border( $input['globalBorder'] ); }
	if ( ! empty( $input['globalBorderHover'] ) ) {
		$attrs['globalBorderHover'] = tpgb_mcp_se_border( $input['globalBorderHover'] ); }
	if ( ! empty( $input['globalBRadius'] ) ) {
		$attrs['globalBRadius'] = tpgb_mcp_se_radius( $input['globalBRadius'] ); }
	if ( ! empty( $input['globalBRadiusHover'] ) ) {
		$attrs['globalBRadiusHover'] = tpgb_mcp_se_radius( $input['globalBRadiusHover'] ); }
	if ( ! empty( $input['globalBShadow'] ) ) {
		$attrs['globalBShadow'] = tpgb_mcp_se_bshadow( $input['globalBShadow'] ); }
	if ( ! empty( $input['globalBShadowHover'] ) ) {
		$attrs['globalBShadowHover'] = tpgb_mcp_se_bshadow( $input['globalBShadowHover'] ); }

	if ( ! empty( $input['scrollAnimation'] ) ) {
		$attrs['globalAnim'] = array( 'md' => sanitize_text_field( $input['scrollAnimation'] ) ); }
	if ( ! empty( $input['animDuration'] ) ) {
		$attrs['globalAnimDuration'] = sanitize_text_field( $input['animDuration'] ); }
	if ( ! empty( $input['animDelay'] ) ) {
		$attrs['globalAnimDelay'] = array( 'md' => sanitize_text_field( $input['animDelay'] ) ); }
	if ( ! empty( $input['animEasing'] ) ) {
		$attrs['globalAnimEasing'] = sanitize_text_field( $input['animEasing'] ); }

	if ( tpgb_mcp_se_needs_wrapper( $attrs ) ) {
		$attrs['tpgbDisrule'] = true; }

	/* ── Raw override ─────────────────────────────────────────────────── */
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
