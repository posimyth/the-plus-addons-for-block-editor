<?php
/**
 * Block : TP Social Feed
 *
 * @since 1.3.0.1
 * @package ThePluginAddonsForBlockEditor
 */

// phpcs:disable Squiz.PHP.CommentedOutCode.Found
// phpcs:disable Squiz.Commenting.InlineComment.InvalidEndChar

defined( 'ABSPATH' ) || exit;

/**
 * Tpgb tp social feed.
 */
function tpgb_tp_social_feed() {

	if ( method_exists( 'Tpgb_Blocks_Global_Options', 'merge_options_json' ) ) {
		$block_data = Tpgb_Blocks_Global_Options::merge_options_json( __DIR__, 'tpgb_tp_social_feed_render_callback', true, false, true );
		register_block_type( $block_data['name'], $block_data );
	}
}
add_action( 'init', 'tpgb_tp_social_feed' );

/**
 * Tpgb tp social feed render callback.
 *
 * @param mixed $attributes The attributes.
 * @param mixed $content The content.
 * @return mixed The result.
 */
function tpgb_tp_social_feed_render_callback( $attributes, $content ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
	$social_feed  = '';
	$block_id     = ( ! empty( $attributes['block_id'] ) ) ? $attributes['block_id'] : uniqid( 'title' );
	$feed_id      = ( ! empty( $attributes['feed_id'] ) ) ? $attributes['feed_id'] : uniqid( 'feed' );
	$layout       = ( ! empty( $attributes['layout'] ) ) ? $attributes['layout'] : 'grid';
	$style        = ( ! empty( $attributes['style'] ) ) ? $attributes['style'] : 'style-1';
	$rsocialfeed  = ( ! empty( $attributes['AllReapeter'] ) ) ? $attributes['AllReapeter'] : array();
	$columns      = ( ! empty( $attributes['columns'] ) ) ? $attributes['columns'] : 'tpgb-col-12';
	$refresh_time = ! empty( $attributes['TimeFrq'] ) ? $attributes['TimeFrq'] : '3600';
	$time_frq     = array( 'TimeFrq' => $attributes['TimeFrq'] );
	$total_post   = ( ! empty( $attributes['TotalPost'] ) ) ? $attributes['TotalPost'] : 1000;

	$feed_id        = ( ! empty( $attributes['FeedId'] ) ) ? preg_split( '/\,/', $attributes['FeedId'] ) : array();
	$show_title     = ! empty( $attributes['ShowTitle'] ) ? $attributes['ShowTitle'] : false;
	$show_footer_in = ( ! empty( $attributes['showFooterIn'] ) ) ? true : false;

	$postdisplay  = ( ! empty( $attributes['Postdisplay'] ) ? (int) $attributes['Postdisplay'] : 6 );
	$post_lodop   = ( ! empty( $attributes['postLodop'] ) ? $attributes['postLodop'] : '' );
	$postview     = ( ! empty( $attributes['postview'] ) ? $attributes['postview'] : 1 );
	$loadbtn_text = ( ! empty( $attributes['loadbtnText'] ) ? $attributes['loadbtnText'] : '' );
	$loadingtxt   = ( ! empty( $attributes['loadingtxt'] ) ? $attributes['loadingtxt'] : '' );
	$allposttext  = ( ! empty( $attributes['allposttext'] ) ? $attributes['allposttext'] : '' );

	$txt_limt   = ( ! empty( $attributes['TextLimit'] ) ? $attributes['TextLimit'] : false );
	$text_count = ( ! empty( $attributes['TextCount'] ) ? $attributes['TextCount'] : 100 );
	$text_type  = ( ! empty( $attributes['TextType'] ) ? $attributes['TextType'] : 'char' );
	$text_more  = ( ! empty( $attributes['TextMore'] ) ? $attributes['TextMore'] : 'Show More' );
	$text_dots  = ( ! empty( $attributes['TextDots'] ) ? '...' : '' );

	$fancy_style  = ( ! empty( $attributes['FancyStyle'] ) ? $attributes['FancyStyle'] : 'default' );
	$descrip_btm  = ( ! empty( $attributes['DescripBTM'] ) ? $attributes['DescripBTM'] : false );
	$media_filter = ( ! empty( $attributes['MediaFilter'] ) ? $attributes['MediaFilter'] : 'default' );

	$show_feed_id = ! empty( $attributes['ShowFeedId'] ) ? $attributes['ShowFeedId'] : false;
	$popup_option = ! empty( $attributes['OnPopup'] ) ? $attributes['OnPopup'] : 'Donothing';
	$performance  = ! empty( $attributes['perf_manage'] ) ? $attributes['perf_manage'] : false;

	$normal_scroll  = '';
	$scroll_on      = ! empty( $attributes['ScrollOn'] ) ? $attributes['ScrollOn'] : false;
	$fcy_scrolll_on = ! empty( $attributes['FcySclOn'] ) ? $attributes['FcySclOn'] : false;
	$offset_post    = ! empty( $feed_id ) ? $postdisplay - count( $feed_id ) : '';

	if ( ! empty( $scroll_on ) || ! empty( $fcy_scrolll_on ) ) {
		$scroll_data   = array(
			'className'   => 'tpgb-normal-scroll',
			'ScrollOn'    => $scroll_on,
			'Height'      => ! empty( $attributes['ScrollHgt'] ) ? (int) $attributes['ScrollHgt'] : 150,
			'TextLimit'   => $txt_limt,

			'Fancyclass'  => 'tpgb-fancy-scroll',
			'FancyScroll' => $fcy_scrolll_on,
			'FancyHeight' => ! empty( $attributes['FcySclHgt'] ) ? (int) $attributes['FcySclHgt'] : 150,
		);
		$normal_scroll = wp_json_encode( $scroll_data, true );
	}

	$block_class = Tp_Blocks_Helper::block_wrapper_classes( $attributes );

	$list_layout = '';
	if ( 'grid' === $layout || 'masonry' === $layout ) {
		$list_layout = 'tpgb-isotope';
	} else {
		$list_layout = 'tpgb-isotope';
	}

	$desktop_class = '';
	if ( $columns ) {
		$desktop_class .= 'tpgb-col-' . esc_attr( $columns['xs'] );
		$desktop_class .= ' tpgb-col-lg-' . esc_attr( $columns['md'] );
		$desktop_class .= ' tpgb-col-md-' . esc_attr( $columns['sm'] );
		$desktop_class .= ' tpgb-col-sm-' . esc_attr( $columns['xs'] );
	}

	$fancybox_settings = '';
	if ( 'OnFancyBox' === $popup_option ) {
		$fancybox_settings = tpgb_social_feed_fancybox( $attributes );
		$fancybox_settings = wp_json_encode( $fancybox_settings );
	}

	$social_feed .= '<div id="' . esc_attr( $block_id ) . '" class="tpgb-block-' . esc_attr( $block_id ) . ' ' . esc_attr( $block_class ) . ' tpgb-social-feed tpgb-relative-block ' . esc_attr( $list_layout ) . '" data-style="' . esc_attr( $style ) . '" data-layout="' . esc_attr( $layout ) . '" data-id="' . esc_attr( $block_id ) . '" data-fid="' . esc_attr( $feed_id ) . '" data-fancy-option=\'' . $fancybox_settings . '\' data-scroll-normal=\'' . esc_attr( $normal_scroll ) . '\' >';

		$fancy_box_js = '';
	if ( 'OnFancyBox' === $popup_option ) {
		$fancy_box_js = 'data-fancybox="' . esc_attr( $block_id ) . '"';
	}

		$final_data = array();

	if ( false === $performance ) { // || (true === $performance && false === $Perfo_transient) // phpcs:ignore Squiz.PHP.CommentedOutCode.Found
		$all_data = array();
		foreach ( $rsocialfeed as $index => $social ) {
			$r_feed = ( ! empty( $social['selectFeed'] ) ) ? $social['selectFeed'] : 'Facebook';
			$social = array_merge( $time_frq, $social );

			if ( 'Facebook' === $r_feed ) {
				$all_data[] = tpgb_FacebookFeed( $social, $attributes );
			}
		}

		if ( ! empty( $all_data ) ) {
			foreach ( $all_data as $key => $val ) {
				foreach ( $val as $key => $vall ) {
					$final_data[] = $vall;
				}
			}
		}
		$feed_index = array_column( $final_data, 'Feed_Index' );
		array_multisort( $feed_index, SORT_ASC, $final_data );
		set_transient( "SF-Performance-$feed_id", $final_data, $refresh_time );
		set_transient( "SF-Performance-$block_id", $final_data, $refresh_time );
		set_transient( "SF-free-backup-$block_id", $final_data, 0 );
	} else {
		$final_data = get_transient( "SF-Performance-$feed_id" );

		if ( false === $final_data || empty( $final_data ) ) {
			$final_data = get_transient( "SF-Performance-$block_id" );
		}

		if ( false === $final_data || empty( $final_data ) ) {
			$final_data = get_transient( "SF-free-backup-$block_id" );
		}
	}

	if ( ! empty( $final_data ) ) {
		foreach ( $final_data as $index => $data ) {
			$post_id = ! empty( $data['PostId'] ) ? $data['PostId'] : array();
			if ( in_array( $post_id, $feed_id, true ) ) {
				unset( $final_data[ $index ] );
			}
		}

		if ( ! empty( $final_data ) ) {

			$social_feed .= '<div class="post-loop-inner social-feed-' . esc_attr( $style ) . '" >';
			foreach ( $final_data as $f_index => $all_vm_data ) {
				$uniq_each     = uniqid();
				$popup_syl_num = $block_id . '-' . $f_index . '-' . $uniq_each;
				$r_key         = ( ! empty( $all_vm_data['RKey'] ) ) ? $all_vm_data['RKey'] : '';
				$post_id       = ( ! empty( $all_vm_data['PostId'] ) ) ? $all_vm_data['PostId'] : '';
				$u_name        = ( ! empty( $all_vm_data['UName'] ) ) ? $all_vm_data['UName'] : '';
				$select_feed   = ( ! empty( $all_vm_data['selectFeed'] ) ) ? $all_vm_data['selectFeed'] : '';
				$massage       = ( ! empty( $all_vm_data['Massage'] ) ) ? $all_vm_data['Massage'] : '';
				$description   = ( ! empty( $all_vm_data['Description'] ) ) ? $all_vm_data['Description'] : '';
				$type          = ( ! empty( $all_vm_data['Type'] ) ) ? $all_vm_data['Type'] : '';
				$post_link     = ( ! empty( $all_vm_data['PostLink'] ) ) ? $all_vm_data['PostLink'] : '';
				$created_time  = ( ! empty( $all_vm_data['CreatedTime'] ) ) ? $all_vm_data['CreatedTime'] : '';
				$post_image    = ( ! empty( $all_vm_data['PostImage'] ) ) ? $all_vm_data['PostImage'] : '';
				$user_name     = ( ! empty( $all_vm_data['UserName'] ) ) ? $all_vm_data['UserName'] : '';
				$user_image    = ( ! empty( $all_vm_data['UserImage'] ) ) ? $all_vm_data['UserImage'] : '';
				$user_link     = ( ! empty( $all_vm_data['UserLink'] ) ) ? $all_vm_data['UserLink'] : '';
				$social_icon   = ( ! empty( $all_vm_data['socialIcon'] ) ) ? $all_vm_data['socialIcon'] : '';
				$error_class   = ( ! empty( $all_vm_data['ErrorClass'] ) ) ? $all_vm_data['ErrorClass'] : '';

				$embed_url  = ( ! empty( $all_vm_data['Embed'] ) ) ? $all_vm_data['Embed'] : '';
				$embed_type = ( ! empty( $all_vm_data['EmbedType'] ) ) ? $all_vm_data['EmbedType'] : '';

				if ( 'Facebook' === $select_feed ) {
					$fblikes      = ( ! empty( $all_vm_data['FbLikes'] ) ) ? $all_vm_data['FbLikes'] : 0;
					$comment      = ( ! empty( $all_vm_data['comment'] ) ) ? $all_vm_data['comment'] : 0;
					$share        = ( ! empty( $all_vm_data['share'] ) ) ? $all_vm_data['share'] : 0;
					$like_img     = TPGB_ASSETS_URL . 'assets/images/social-feed/like.png';
					$reaction_img = TPGB_ASSETS_URL . 'assets/images/social-feed/love.png';

					$fb_album = ( ! empty( $all_vm_data['FbAlbum'] ) ) ? $all_vm_data['FbAlbum'] : false;
					// if(!empty($fb_album)){ // phpcs:ignore Squiz.PHP.CommentedOutCode.Found
					// $fancy_box_js = 'data-fancybox="album-Facebook'.esc_attr($f_index).'-'.esc_attr($block_id).'"';.
					// } // phpcs:ignore Squiz.Commenting.InlineComment.InvalidEndChar
				}
				$image_url = '';
				$video_url = '';
				if ( 'video' === $type || 'photo' === $type ) {
					$sep_post_id = explode( '_', $post_id );
					$new_p_id    = ( ! empty( $sep_post_id[1] ) ) ? $sep_post_id[1] : '';
					$fb_post_rd  = 'https://www.facebook.com/' . esc_attr( $u_name ) . '/posts/' . esc_attr( $new_p_id );
					$video_url   = ( 'Facebook' === $select_feed && 'GoWebsite' === $popup_option ) ? ( ! empty( $post_link[0]['link'] ) ) ? $post_link[0]['link'] : $fb_post_rd : $post_link;
					$image_url   = $post_image;
				}
				if ( ! empty( $fb_album ) ) {
					$post_link = ( ! empty( $post_link[0]['link'] ) ) ? $post_link[0]['link'] : 0;
				}

				if ( ( ! in_array( $post_id, $feed_id, true ) && $f_index < $total_post ) && ( ( 'default' === $media_filter ) || ( 'ompost' === $media_filter && ! empty( $post_link ) && ! empty( $post_image ) ) || ( 'hmcontent' === $media_filter && empty( $post_link ) && empty( $post_image ) ) ) ) {
					$social_feed .= '<div class="grid-item splide__slide ' . esc_attr( 'feed-' . $select_feed . ' ' . $desktop_class . ' ' . $r_key . ' ' ) . '" data-index="' . esc_attr( $select_feed ) . esc_attr( $f_index ) . '" >';
						ob_start();
							include TPGB_INCLUDES_URL . 'social-feed/social-feed-' . sanitize_file_name( $style ) . '.php';
							$social_feed .= ob_get_contents();
						ob_end_clean();
					$social_feed .= '</div>';
				}
			}
			$social_feed .= '</div>';
		} else {
			$social_feed .= '<div class="error-handal">' . esc_html__( 'All Social Feed', 'the-plus-addons-for-block-editor' ) . '</div>';
		}
	} else {
		$social_feed .= '<div class="error-handal">' . esc_html__( 'All Social Feed', 'the-plus-addons-for-block-editor' ) . '</div>';
	}

	$social_feed .= '</div>';

	return $social_feed;
}

/**
 * Tpgb facebook feed.
 *
 * @param mixed $social The social.
 * @param mixed $attr The attr.
 * @return mixed The result.
 */
function tpgb_FacebookFeed( $social, $attr ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid,WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
	$base_url      = 'https://graph.facebook.com/v20.0';
	$fb_key        = ( ! empty( $social['_key'] ) ) ? $social['_key'] : '';
	$fb_ac_t       = ( ! empty( $social['RAToken'] ) ) ? $social['RAToken'] : '';
	$fb_p_type     = ( ! empty( $social['ProfileType'] ) ) ? $social['ProfileType'] : 'post';
	$fb_pageid     = ( ! empty( $social['Pageid'] ) ) ? $social['Pageid'] : '';
	$fb_album      = ( ! empty( $social['fbAlbum'] ) ) ? $social['fbAlbum'] : false;
	$fb_limit      = ( ! empty( $social['MaxR'] ) ) ? $social['MaxR'] : 6;
	$fb_a_limit    = ( ! empty( $social['AlbumMaxR'] ) ) ? $social['AlbumMaxR'] : 6;
	$fbcontent     = ( ! empty( $social['content'] ) ) ? $social['content'] : array();
	$fb_time       = ( ! empty( $social['TimeFrq'] ) ) ? $social['TimeFrq'] : '3600';
	$fbselect_feed = ! empty( $social['selectFeed'] ) ? $social['selectFeed'] : '';
	$fb_icon       = 'fab fa-facebook social-logo-fb';
	$ssl_ver       = $attr['CURLOPT_SSL_VERIFYPEER'];
	$content       = array();
	if ( ! empty( $fbcontent ) && ( is_array( $fbcontent ) || is_object( $fbcontent ) ) ) {
		foreach ( $fbcontent as $data ) {
			$filter = ( ! empty( $data['value'] ) ) ? $data['value'] : 'photo';
			array_push( $content, $filter );
		}
	} else {
		array_push( $content, 'photo', 'video', 'status' );
	}

	$url         = '';
	$fb_all_data = '';
	$fb_arr      = array();
	if ( ! empty( $fb_ac_t ) && 'post' === $fb_p_type ) {
		$url = "{$base_url}/me?fields=id,name,first_name,last_name,link,email,birthday,picture,posts.limit($fb_limit){type,message,story,caption,description,shares,picture,full_picture,source,created_time,reactions.summary(true),comments.summary(true).filter(toplevel)},albums.limit($fb_a_limit){id,type,link,picture,created_time,name,count,photos.limit($fb_limit){id,link,created_time,likes,images,name,comments.summary(true).filter(toplevel)}}&access_token={$fb_ac_t}";
	} elseif ( ! empty( $fb_ac_t ) && ! empty( $fb_pageid ) && 'page' === $fb_p_type ) {
		$url = "{$base_url}/{$fb_pageid}?fields=id,name,username,link,fan_count,new_like_count,phone,emails,about,birthday,category,picture,posts.limit($fb_limit){id,full_picture,created_time,message,attachments{media,media_type,title,url},picture,story,status_type,shares,reactions.summary(true),likes.summary(true),comments.summary(true).filter(toplevel)},albums.limit($fb_a_limit){id,type,link,picture,created_time,name,count,photos.limit($fb_limit){id,link,created_time,images,name}}&access_token={$fb_ac_t}";
	}

	if ( ! empty( $url ) ) {
		$get_fb_rl   = get_transient( "Fb-Url-$fb_key" );
		$get_fb_time = get_transient( "Fb-Time-$fb_key" );

		if ( $get_fb_rl !== $url || $get_fb_time !== $fb_time ) {
			$fb_all_data = tpgb_api_call( $url, $ssl_ver );
				set_transient( "Fb-Url-$fb_key", $url, $fb_time );
				set_transient( "Data-Fb-$fb_key", $fb_all_data, $fb_time );
				set_transient( "Fb-Time-$fb_key", $fb_time, $fb_time );
		} else {
			$fb_all_data = get_transient( "Data-Fb-$fb_key" );
		}

		$status = ( ! empty( $fb_all_data['HTTP_CODE'] ) ? $fb_all_data['HTTP_CODE'] : '' );
		if ( 200 === $status ) {
			$fb_post = '';
			if ( ! empty( $fb_album ) ) {
				$fb_post = ( ! empty( $fb_all_data['albums']['data'] ) ) ? $fb_all_data['albums']['data'] : array();
			} else {
				$fb_post = ! empty( $fb_all_data['posts']['data'] ) ? $fb_all_data['posts']['data'] : ( ! empty( $fb_all_data['albums']['data'] ) ? $fb_all_data['albums']['data'] : array() );
			}

			foreach ( $fb_post as $index => $fb_data ) {

				$link         = ( ! empty( $fb_all_data['link'] ) ? $fb_all_data['link'] : '' );
				$name         = ( ! empty( $fb_all_data['name'] ) ? $fb_all_data['name'] : '' );
				$u_name       = ( ! empty( $fb_all_data['username'] ) ? $fb_all_data['username'] : '' );
				$id           = ( ! empty( $fb_data['id'] ) ? $fb_data['id'] : '' );
				$type         = ( ! empty( $fb_data['type'] ) ? $fb_data['type'] : '' );
				$fb_message   = ( ! empty( $fb_data['message'] ) ? $fb_data['message'] : '' );
				$fb_picture   = ( ! empty( $fb_data['full_picture'] ) ? $fb_data['full_picture'] : '' );
				$fb_source    = ( ! empty( $fb_data['full_picture'] ) ? $fb_data['full_picture'] : '' );
				$created_time = ( ! empty( $fb_data['created_time'] ) ) ? tpgb_feed_Post_time( $fb_data['created_time'] ) : '';
				$fb_reactions = ( ! empty( $fb_data['reactions']['summary']['total_count'] ) ? tpgb_number_short( $fb_data['reactions']['summary']['total_count'] ) : 0 );
				$fb_comments  = ( ! empty( $fb_data['comments']['summary']['total_count'] ) ? tpgb_number_short( $fb_data['comments']['summary']['total_count'] ) : 0 );
				$fbshares     = ( ! empty( $fb_data['shares']['count'] ) ? tpgb_number_short( $fb_data['shares']['count'] ) : '' );

				if ( 'video' === $type ) {
					$fb_source = ( ! empty( $fb_data['source'] ) ? $fb_data['source'] : '' );
				}
				$fb_caption     = ( ! empty( $fb_data['caption'] ) ? $fb_data['caption'] : '' );
				$fb_description = ( ! empty( $fb_data['description'] ) ) ? $fb_data['description'] : '';

				if ( 'page' === $fb_p_type ) {
					$type = ( ! empty( $fb_data['attachments']['data'][0]['media_type'] ) ? $fb_data['attachments']['data'][0]['media_type'] : '' );
					if ( 'album' === $type ) {
						$type = 'photo';
					}
					if ( 'video' === $type ) {
						$fb_source = ( ! empty( $fb_data['attachments']['data'][0]['media']['source'] ) ? $fb_data['attachments']['data'][0]['media']['source'] : '' );
					}
				}

				if ( ! empty( $fb_album ) ) {
					$type       = 'video';
					$link       = ( ! empty( $fb_data['link'] ) ? $fb_data['link'] : '' );
					$fb_message = ( ! empty( $fb_data['name'] ) ? $fb_data['name'] : '' );
					$fbcount    = ( ! empty( $fb_data['count'] ) ? $fb_data['count'] : '' );
					$fb_picture = ( ! empty( $fb_data['picture']['data']['url'] ) ? $fb_data['picture']['data']['url'] : '' );
					$fb_source  = ( ! empty( $fb_data['photos']['data'] ) ? $fb_data['photos']['data'] : array() );
				}

				if ( ( in_array( 'photo', $content, true ) ) || ( in_array( 'video', $content, true ) ) || ( in_array( 'status', $content, true ) ) ) {

					$fb_arr[] = array(
						'Feed_Index'  => $index,
						'PostId'      => $id,
						'Massage'     => $fb_caption . $fb_description,
						'Description' => $fb_message,
						'Type'        => 'video',
						'PostLink'    => $fb_source,
						'CreatedTime' => $created_time,
						'PostImage'   => $fb_picture,
						'UserName'    => $name,
						'UName'       => $u_name,
						'UserImage'   => ( ! empty( $fb_all_data['picture']['data']['url'] ) ? $fb_all_data['picture']['data']['url'] : '' ),
						'UserLink'    => $link,
						'share'       => $fbshares,
						'comment'     => $fb_comments,
						'FbLikes'     => $fb_reactions,
						'Embed'       => 'Alb',
						'EmbedType'   => $type,
						'FbAlbum'     => $fb_album,
						'socialIcon'  => $fb_icon,
						'selectFeed'  => $fbselect_feed,
						'RKey'        => "tp-repeater-item-$fb_key",
					);
				}
			}
		} else {
			$fb_arr[] = tpgb_SF_Error_handler( $fb_all_data, $fb_key, $fbselect_feed, $fb_icon );
		}
	} else {
		$msg = '';
		if ( empty( $fb_ac_t ) ) {
			$msg .= 'Empty Access Token </br>';
		}
		if ( 'page' === $fb_p_type && empty( $fb_pageid ) ) {
			$msg .= 'Empty Page ID';
		}
		$error_data['error']['message'] = $msg;
		$fb_arr[]                       = tpgb_SF_Error_handler( $error_data, $fb_key, $fbselect_feed, $fb_icon );
	}

	return $fb_arr;
}

/**
 * Tpgb api call.
 *
 * @param mixed $api The api.
 * @param bool  $ssl The ssl.
 * @return mixed The result.
 */
function tpgb_api_call( $api, $ssl = true ) {
	$final = array();

	$args = array(
		'method'    => 'GET',
		'timeout'   => 30,
		'sslverify' => $ssl,
	);

	$url = wp_remote_get( $api, $args );

	$status_code = wp_remote_retrieve_response_code( $url );
	$body        = wp_remote_retrieve_body( $url );
	$status_code = array( 'HTTP_CODE' => $status_code );

	$response = json_decode( $body, true );
	if ( is_array( $status_code ) && is_array( $response ) ) {
		$final = array_merge( $status_code, $response );
	}
	return $final;
}

/**
 * Tpgb feed post time.
 *
 * @param mixed $datetime The datetime.
 * @param bool  $full The full.
 * @return mixed The result.
 */
function tpgb_feed_Post_time( $datetime, $full = false ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid,WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
	$now  = new DateTime();
	$ago  = new DateTime( $datetime );
	$diff = $now->diff( $ago );

	$diff->w  = floor( $diff->d / 7 );
	$diff->d -= $diff->w * 7;

	$string = array(
		'y' => 'year',
		'm' => 'month',
		'w' => 'week',
		'd' => 'day',
		'h' => 'hour',
		'i' => 'minute',
		's' => 'second',
	);
	foreach ( $string as $k => &$v ) {
		if ( $diff->$k ) {
			$v = $diff->$k . ' ' . $v . ( $diff->$k > 1 ? 's' : '' );
		} else {
			unset( $string[ $k ] );
		}
	}

	if ( ! $full ) {
		$string = array_slice( $string, 0, 1 );
	}
	return $string ? implode( ', ', $string ) . ' ago' : 'just now';
}

/**
 * Tpgb number short.
 *
 * @param mixed $n The n.
 * @param int   $precision The precision.
 * @return mixed The result.
 */
function tpgb_number_short( $n, $precision = 1 ) {
	if ( $n < 900 ) {
		$n_format = number_format( $n, $precision );
		$suffix   = '';
	} elseif ( $n < 900000 ) {
		$n_format = number_format( $n / 1000, $precision );
		$suffix   = 'K';
	} elseif ( $n < 900000000 ) {
		$n_format = number_format( $n / 1000000, $precision );
		$suffix   = 'M';
	} elseif ( $n < 900000000000 ) {
		$n_format = number_format( $n / 1000000000, $precision );
		$suffix   = 'B';
	} else {
		$n_format = number_format( $n / 1000000000000, $precision );
		$suffix   = 'T';
	}

	if ( $precision > 0 ) {
		$dotzero  = '.' . str_repeat( '0', $precision );
		$n_format = str_replace( $dotzero, '', $n_format );
	}
	return $n_format . $suffix;
}

/**
 * Tpgb social feed fancybox.
 *
 * @param mixed $attr The attr.
 * @return mixed The result.
 */
function tpgb_social_feed_fancybox( $attr ) {
	$button   = array();
	$button[] = 'close';

	$fancybox                     = array();
	$fancybox['loop']             = $attr['LoopFancy'];
	$fancybox['arrows']           = $attr['ArrowsFancy'];
	$fancybox['clickContent']     = $attr['ClickContent'];
	$fancybox['transitionEffect'] = $attr['TransitionFancy'];
	$fancybox['button']           = $button;

	return $fancybox;
}

/**
 * Tpgb sf error handler.
 *
 * @param array  $error_data The error data.
 * @param string $rkey The rkey.
 * @param string $select_feed The select feed.
 * @param string $icon The icon.
 * @return mixed The result.
 */
function tpgb_SF_Error_handler( $error_data, $rkey = '', $select_feed = '', $icon = '' ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid,WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
	$error = ! empty( $error_data['error'] ) ? $error_data['error'] : array();
	return array(
		'Feed_Index'  => 0,
		'ErrorClass'  => 'error-class',
		'socialIcon'  => $icon,
		'CreatedTime' => "<b>{$select_feed}</b>",
		'Description' => ! empty( $error['message'] ) ? $error['message'] : 'Something Wrong',
		'UserName'    => ! empty( $error['HTTP_CODE'] ) ? 'Error Code : ' . $error['HTTP_CODE'] : 400,
		'UserImage'   => TPGB_ASSETS_URL . 'assets/images/tpgb-placeholder.jpg',
		'selectType'  => $select_feed,
		'RKey'        => "tp-repeater-item-$rkey",
	);
}
