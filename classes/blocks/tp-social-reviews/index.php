<?php
/**
 * Block : Social Reviews
 *
 * @since 2.0.2
 * @package ThePluginAddonsForBlockEditor
 */

defined( 'ABSPATH' ) || exit;

/**
 * Tpgb social reviews callback.
 *
 * @param mixed $attributes The attributes.
 * @param mixed $content The content.
 * @return mixed The result.
 */
function tpgb_social_reviews_callback( $attributes, $content ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
	$reviews   = '';
	$block_id  = ( ! empty( $attributes['block_id'] ) ) ? $attributes['block_id'] : uniqid( 'title' );
	$review_id = ( ! empty( $attributes['review_id'] ) ) ? $attributes['review_id'] : uniqid( 'review' );

	$layout   = ( ! empty( $attributes['layout'] ) ) ? $attributes['layout'] : 'grid';
	$r_type   = ( ! empty( $attributes['RType'] ) ) ? $attributes['RType'] : 'review';
	$style    = ( ! empty( $attributes['style'] ) ) ? $attributes['style'] : 'style-1';
	$columns  = ( ! empty( $attributes['columns'] ) ) ? $attributes['columns'] : 'tpgb-col-12';
	$rowclass = ( 'carousel' !== $layout ) ? 'tpgb-row' : '';

	$repeater      = ( ! empty( $attributes['Rreviews'] ) ) ? $attributes['Rreviews'] : array();
	$refresh_time  = ( ! empty( $attributes['TimeFrq'] ) ) ? $attributes['TimeFrq'] : '3600';
	$time_frq      = array( 'TimeFrq' => $refresh_time );
	$overlay_image = ( ! empty( $attributes['OverlayImage'] ) ) ? 'overlayimage' : '';

	$feed_id      = ( ! empty( $attributes['FeedId'] ) ) ? preg_split( '/\,/', $attributes['FeedId'] ) : array();
	$show_feed_id = ( ! empty( $attributes['ShowFeedId'] ) ) ? $attributes['ShowFeedId'] : false;

	$txt_limt    = ( ! empty( $attributes['TextLimit'] ) ? $attributes['TextLimit'] : false );
	$text_count  = ( ! empty( $attributes['TextCount'] ) ? $attributes['TextCount'] : 100 );
	$text_type   = ( ! empty( $attributes['TextType'] ) ? $attributes['TextType'] : 'char' );
	$text_more   = ( ! empty( $attributes['TextMore'] ) ? $attributes['TextMore'] : 'Show More' );
	$text_less   = ( ! empty( $attributes['TextLess'] ) ? $attributes['TextLess'] : 'Show Less' );
	$text_dots   = ( ! empty( $attributes['TextDots'] ) ? '...' : '' );
	$user_footer = ( ! empty( $attributes['s2Layout'] ) ? $attributes['s2Layout'] : 'layout-1' );

	$performance      = ! empty( $attributes['perf_manage'] ) ? $attributes['perf_manage'] : false;
	$dis_social_icon  = ! empty( $attributes['disSocialIcon'] ) ? $attributes['disSocialIcon'] : false;
	$dis_profile_icon = ! empty( $attributes['disProfileIcon'] ) ? $attributes['disProfileIcon'] : false;

	$block_class = Tp_Blocks_Helper::block_wrapper_classes( $attributes );

	$list_layout = '';
	if ( 'grid' === $layout || 'masonry' === $layout ) {
		$list_layout = 'tpgb-isotope';
	} else {
		$list_layout = 'tpgb-isotope';
	}
	$desktop_class = '';
	$tablet_class  = '';
	$mobile_class  = '';
	if ( 'carousel' !== $layout && $columns ) {
		$desktop_class .= ' tpgb-col-' . esc_attr( $columns['xs'] );
		$desktop_class .= ' tpgb-col-lg-' . esc_attr( $columns['md'] );
		$tablet_class  .= ' tpgb-col-md-' . esc_attr( $columns['sm'] );
		$mobile_class  .= ' tpgb-col-sm-' . esc_attr( $columns['xs'] );
	}

	$n_feed_id = array();
	if ( ! empty( $show_feed_id ) ) {
		$n_feed_id = $feed_id;
	}

	$normal_scroll = '';
	$cnt_sc_br     = ! empty( $attributes['cntScBr'] ) ? true : false;
	$sbheight      = ! empty( $attributes['scrlHeight'] ) ? $attributes['scrlHeight'] : 100;
	if ( ! empty( $cnt_sc_br ) ) {
		$scroll_data   = array(
			'className' => 'tpgb-normal-scroll',
			'ScrollOn'  => $cnt_sc_br,
			'Height'    => (int) $sbheight,
			'TextLimit' => $txt_limt,
		);
		$normal_scroll = wp_json_encode( $scroll_data, true );
	}
	$txtlimit_data = '';
	if ( ! empty( $txt_limt ) ) {
		$txtlimit_dataa = array(
			'showmoretxt' => $text_more,
			'showlesstxt' => $text_less,
		);
		$txtlimit_data  = wp_json_encode( $txtlimit_dataa, true );
	}

	$reviews .= '<div class="tpgb-block-' . esc_attr( $block_id ) . ' ' . esc_attr( $block_class ) . ' tpgb-social-reviews tpgb-relative-block ' . esc_attr( $list_layout ) . '" id="' . esc_attr( $block_id ) . '" data-style="' . esc_attr( $style ) . '" data-layout="' . esc_attr( $layout ) . '" data-id="' . esc_attr( $block_id ) . '" data-rid="' . esc_attr( $review_id ) . '" data-scroll-normal="' . esc_attr( $normal_scroll ) . '" data-textlimit="' . esc_attr( $txtlimit_data ) . '">';

	if ( 'carousel' === $layout && ( ( isset( $show_arrows['md'] ) && ! empty( $show_arrows['md'] ) ) || ( isset( $show_arrows['sm'] ) && ! empty( $show_arrows['sm'] ) ) || ( isset( $show_arrows['xs'] ) && ! empty( $show_arrows['xs'] ) ) ) ) {
		if ( isset( $show_arrows ) && ! empty( $show_arrows ) ) {
			$reviews .= Tp_Blocks_Helper::tpgb_carousel_arrow( $arrows_style, $arrows_position );
		}
	}

	if ( 'review' === $r_type ) {
		$final_data      = array();
		$perfo_transient = get_transient( 'SR-Performance-' . $review_id );
		if ( ( false === $performance ) || ( true === $performance && false === $perfo_transient ) ) {
			$all_data = array();
			foreach ( $repeater as $index => $r ) {
				$rrt = ( ! empty( $r['ReviewsType'] ) ) ? $r['ReviewsType'] : 'facebook';
				$r   = array_merge( $time_frq, $r );

				if ( 'facebook' === $rrt ) {
					$all_data[] = tpgb_Facebook_Reviews( $r, $attributes );
				}
			}
			if ( ! empty( $all_data ) ) {
				foreach ( $all_data as $key => $val ) {
					foreach ( $val as $key => $vall ) {
						$final_data[] = $vall;
					}
				}
			}
			$reviews_index = array_column( $final_data, 'Reviews_Index' );
			array_multisort( $reviews_index, SORT_ASC, $final_data );
			set_transient( "SR-Performance-$review_id", $final_data, $refresh_time );
		} else {
			$final_data = get_transient( 'SR-Performance-' . $review_id );
		}

		if ( ! empty( $final_data ) ) {

			foreach ( $final_data as $index => $data ) {
				$post_id = ! empty( $data['PostId'] ) ? $data['PostId'] : array();
				if ( in_array( $post_id, $n_feed_id, true ) ) {
					unset( $final_data[ $index ] );
				}
			}

			$reviews .= '<div class="' . esc_attr( $rowclass ) . ' post-loop-inner social-reviews-' . esc_attr( $style ) . ' ' . esc_attr( $overlay_image ) . '" >';
			foreach ( $final_data as $f_index => $review ) {
				$r_key         = ( ! empty( $review['RKey'] ) ) ? $review['RKey'] : '';
				$r_index       = ( ! empty( $review['Reviews_Index'] ) ) ? $review['Reviews_Index'] : '';
				$post_id       = ( ! empty( $review['PostId'] ) ) ? $review['PostId'] : '';
				$type          = ( ! empty( $review['Type'] ) ) ? $review['Type'] : '';
				$time          = ( ! empty( $review['CreatedTime'] ) ) ? $review['CreatedTime'] : '';
				$u_name        = ( ! empty( $review['UserName'] ) ) ? $review['UserName'] : '';
				$u_image       = ( ! empty( $review['UserImage'] ) ) ? $review['UserImage'] : '';
				$u_link        = ( ! empty( $review['UserLink'] ) ) ? $review['UserLink'] : '';
				$page_link     = ( ! empty( $review['PageLink'] ) ) ? $review['PageLink'] : '';
				$massage       = ( ! empty( $review['Massage'] ) ) ? $review['Massage'] : '';
				$icon          = ( ! empty( $review['Icon'] ) ) ? $review['Icon'] : 'fas fa-star';
				$logo          = ( ! empty( $review['Logo'] ) ) ? $review['Logo'] : '';
				$rating        = ( ! empty( $review['rating'] ) ) ? $review['rating'] : '';
				$category_text = ( ! empty( $review['FilterCategory'] ) ) ? $review['FilterCategory'] : '';
				$review_class  = ( ! empty( $review['selectType'] ) ) ? ' ' . esc_attr( $review['selectType'] ) : '';
				$err_class     = ( ! empty( $review['ErrorClass'] ) ? $review['ErrorClass'] : '' );
				$platform_name = ( ! empty( $review['selectType'] ) ) ? ucwords( str_replace( 'custom', '', $review['selectType'] ) ) : '';

				if ( ! in_array( $post_id, $n_feed_id, true ) ) {
					ob_start();
					include TPGB_PATH . 'includes/social-reviews/' . sanitize_file_name( 'social-review-' . $style . '.php' );
						$reviews .= ob_get_contents();
					ob_end_clean();
				}
			}

				$reviews .= '</div>';
		} else {
			$reviews .= '<div class="error-handal">' . esc_html__( 'All Social Feed', 'the-plus-addons-for-block-editor' ) . '</div>';
		}
	}

	$reviews .= '</div>';

	return $reviews;
}

/**
 * Tpgb facebook reviews.
 *
 * @param array $r_data The r data.
 * @param mixed $attr The attr.
 * @return mixed The result.
 */
function tpgb_Facebook_Reviews( $r_data, $attr ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid,WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
	$key          = ( ! empty( $r_data['_key'] ) ? $r_data['_key'] : '' );
	$token        = ( ! empty( $r_data['Token'] ) ? $r_data['Token'] : '' );
	$page_id      = ( ! empty( $r_data['FbPageId'] ) ? $r_data['FbPageId'] : '' );
	$fb_r_type    = ( ! empty( $r_data['FbRType'] ) ? $r_data['FbRType'] : '' );
	$max_r        = ( ! empty( $r_data['MaxR'] ) ? $r_data['MaxR'] : 6 );
	$ricon        = ( ! empty( $r_data['icons'] ) ? $r_data['icons'] : 'fas fa-star' );
	$time_frq     = ( ! empty( $r_data['TimeFrq'] ) ? $r_data['TimeFrq'] : '' );
	$r_category   = ! empty( $r_data['RCategory'] ) ? $r_data['RCategory'] : '';
	$reviews_type = ! empty( $r_data['ReviewsType'] ) ? $r_data['ReviewsType'] : '';
	$fb_icon      = TPGB_ASSETS_URL . 'assets/images/social-review/facebook.svg';
	$fb_nagative  = ! empty( $attr['FBNagative'] ) ? $attr['FBNagative'] : 1;

	$api = '';
	if ( ! empty( $page_id ) && ! empty( $token ) ) {
		$api = "https://graph.facebook.com/v20.0/{$page_id}?access_token={$token}&fields=ratings.fields(reviewer{id,name,picture.width(120).height(120)},created_time,rating,recommendation_type,review_text,open_graph_story{id}).limit($max_r),overall_star_rating,rating_count";
	}

	$fbdata = array();
	$fb_arr = array();

	if ( ! empty( $api ) ) {
		$get_api  = get_transient( "Fb-R-Url-$key" );
		$get_time = get_transient( "Fb-R-Time-$key" );
		if ( $get_api !== $api || $get_time !== $time_frq ) {
			$fbdata = tpgb_Review_Api( $api );
			$fbdata = wp_json_encode( $fbdata );
			set_transient( "Fb-R-Url-$key", $api, $time_frq );
			set_transient( "Fb-R-Data-$key", $fbdata, $time_frq );
			set_transient( "Fb-R-Time-$key", $time_frq, $time_frq );
		} else {
			$fbdata = get_transient( "Fb-R-Data-$key" );
		}

		if ( ! is_array( $fbdata ) ) {
			$fbdata = json_decode( $fbdata, true );
		}

		$fb_status = ( ! empty( $fbdata['HTTP_CODE'] ) ? $fbdata['HTTP_CODE'] : 400 );
		if ( 200 === $fb_status ) {
			$rating = ( ! empty( $fbdata['ratings'] ) && ! empty( $fbdata['ratings']['data'] ) ? $fbdata['ratings']['data'] : array() );
			foreach ( $rating as $index => $data ) {
				$fb       = ( ! empty( $data['reviewer'] ) ? $data['reviewer'] : '' );
				$rt       = ( ! empty( $data['recommendation_type'] ) ? $data['recommendation_type'] : '' );
				$userlink = ( ! empty( $data['open_graph_story'] ) && ! empty( $data['open_graph_story']['id'] ) ? $data['open_graph_story']['id'] : '' );
				$f_type   = ( ( 'default' === $fb_r_type ) ? $rt : $fb_r_type );
				$rating   = 5;
				if ( 'negative' === $rt ) {
					$rating = $fb_nagative;
				}

				if ( $f_type === $rt ) {
					$fb_arr[] = array(
						'Reviews_Index'  => $index,
						'PostId'         => ( ! empty( $fb['id'] ) ? $fb['id'] : '' ),
						'Type'           => $rt,
						'CreatedTime'    => ( ! empty( $data['created_time'] ) ? tpgb_Review_Time( $data['created_time'] ) : '' ),
						'UserName'       => ( ! empty( $fb['name'] ) ? $fb['name'] : '' ),
						'UserImage'      => ( ! empty( $fb['picture'] ) && ! empty( $fb['picture']['data']['url'] ) ? $fb['picture']['data']['url'] : TPGB_ASSETS_URL . 'assets/images/tpgb-placeholder.jpg' ),
						'UserLink'       => "https://www.facebook.com/$userlink",
						'PageLink'       => "https://www.facebook.com/{$page_id}/reviews",
						'Massage'        => ( ! empty( $data['review_text'] ) ? $data['review_text'] : '' ),
						'Icon'           => $ricon,
						'rating'         => $rating,
						'Logo'           => $fb_icon,
						'selectType'     => $reviews_type,
						'FilterCategory' => $r_category,
						'RKey'           => "tp-repeater-item-$key",
					);
				}
			}
		} else {
			$fb_arr[] = tpgb_Review_Error_array( $fbdata, $key, $fb_icon, $reviews_type, $r_category );
		}
	} else {
		$msg        = '';
		$error_data = array();
		if ( empty( $token ) ) {
			$msg .= 'Empty Access Token </br>';
		}
		if ( empty( $page_id ) ) {
			$msg .= 'Empty Page ID';
		}
		$error_data['error']['message'] = $msg;
		$fb_arr[]                       = tpgb_Review_Error_array( $error_data, $key, $fb_icon, $reviews_type, $r_category );
	}
	return $fb_arr;
}

/**
 * Tpgb review time.
 *
 * @param mixed $datetime The datetime.
 * @param bool  $full The full.
 * @return mixed The result.
 */
function tpgb_Review_Time( $datetime, $full = false ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid,WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
	$now  = new DateTime();
	$ago  = new DateTime( $datetime );
	$diff = $now->diff( $ago );

	// Calculate weeks separately (no dynamic property).
	$weeks = floor( $diff->d / 7 );
	$days  = $diff->d - ( $weeks * 7 );

	$string = array(
		'y' => 'year',
		'm' => 'month',
		'w' => 'week',
		'd' => 'day',
		'h' => 'hour',
		'i' => 'minute',
		's' => 'second',
	);

	$values = array(
		'y' => $diff->y,
		'm' => $diff->m,
		'w' => $weeks,
		'd' => $days,
		'h' => $diff->h,
		'i' => $diff->i,
		's' => $diff->s,
	);

	foreach ( $string as $k => &$v ) {
		if ( $values[ $k ] ) {
			$v = $values[ $k ] . ' ' . $v . ( $values[ $k ] > 1 ? 's' : '' );
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
 * Tpgb review api.
 *
 * @param mixed $api The api.
 * @return mixed The result.
 */
function tpgb_Review_Api( $api ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid,WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
	$final = array();

	$url          = wp_remote_get( $api );
	$status_code  = wp_remote_retrieve_response_code( $url );
	$get_data_one = wp_remote_retrieve_body( $url );
	$statuscode   = array( 'HTTP_CODE' => $status_code );

	$response = json_decode( $get_data_one, true );
	if ( is_array( $statuscode ) && is_array( $response ) ) {
		$final = array_merge( $statuscode, $response );
	}
	return $final;
}

/**
 * Tpgb review error array.
 *
 * @param array $data The data.
 * @param mixed $r_key The r key.
 * @param mixed $icon The icon.
 * @param mixed $reviews_type The reviews type.
 * @param mixed $r_category The r category.
 * @return mixed The result.
 */
function tpgb_Review_Error_array( $data, $r_key, $icon, $reviews_type, $r_category ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid,WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
	$message = '';
	if ( ! empty( $data ) && ! empty( $data['error_message'] ) ) {
		$message = $data['error_message'];
	} elseif ( ! empty( $data ) && ! empty( $data['error'] ) && ! empty( $data['error']['Message_Errorcurl'] ) ) {
		$message = $data['error']['Message_Errorcurl'];
	} elseif ( ! empty( $data ) && ! empty( $data['error'] ) ) {    /** new */ // phpcs:ignore Generic.Commenting.DocComment.ShortNotCapital,Generic.Commenting.DocComment.LongNotCapital,Generic.Commenting.DocComment.MissingShort
		$message = $data['error']['message'];
	} elseif ( ! empty( $data ) && ! empty( $data['status'] ) ) {   /* new */
		$message = $data['status'];
	} else {
		$message = 'Something Wrong';
	}

	return array(
		'Reviews_Index'  => 1,
		'ErrorClass'     => 'danger-error',
		'CreatedTime'    => ! empty( $data['status'] ) ? $data['status'] : '',
		'Massage'        => $message,
		'UserName'       => ! empty( $data['HTTP_CODE'] ) ? 'Error No : ' . $data['HTTP_CODE'] : '',
		'UserImage'      => $icon,
		'Logo'           => $icon,
		'selectType'     => $reviews_type,
		'FilterCategory' => $r_category,
		'RKey'           => "tp-repeater-item-{$r_key}",
	);
}

/**
 * Tpgb social reviews.
 */
function tpgb_social_reviews() {

	if ( method_exists( 'Tpgb_Blocks_Global_Options', 'merge_options_json' ) ) {
		$block_data = Tpgb_Blocks_Global_Options::merge_options_json( __DIR__, 'tpgb_social_reviews_callback' );
		register_block_type( $block_data['name'], $block_data );
	}
}
add_action( 'init', 'tpgb_social_reviews' );
