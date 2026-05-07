<?php
/**
 * Block : Social Embed
 *
 * @since 1.3.0
 * @package ThePluginAddonsForBlockEditor
 */

defined( 'ABSPATH' ) || exit;

/**
 * Tpgb social embed render callback.
 *
 * @param mixed $attributes The attributes.
 * @param mixed $content The content.
 */
function tpgb_social_embed_render_callback( $attributes, $content ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
	$output     = '';
	$block_id   = ( ! empty( $attributes['block_id'] ) ) ? $attributes['block_id'] : uniqid( 'title' );
	$embed_type = ( ! empty( $attributes['embedType'] ) ) ? $attributes['embedType'] : 'facebook';

	$block_class = Tp_Blocks_Helper::block_wrapper_classes( $attributes );
	$output     .= '<div class="tpgb-block-' . esc_attr( $block_id ) . ' tpgb-social-embed ' . esc_attr( $block_class ) . '">';

	if ( 'vimeo' === $embed_type || 'youtube' === $embed_type ) {
		$ex_width  = ( ! empty( $attributes['exWidth'] ) ) ? $attributes['exWidth'] : 640;
		$ex_height = ( ! empty( $attributes['exHeight'] ) ) ? $attributes['exHeight'] : 360;
	}

	if ( 'facebook' === $embed_type ) {
		$type     = ( ! empty( $attributes['type'] ) ) ? $attributes['type'] : '';
		$size_btn = ( ! empty( $attributes['sizeLB'] ) ) ? $attributes['sizeLB'] : '';

		if ( 'comments' === $type ) {
			$fb_comment_add = ( ! empty( $attributes['commentAddURL'] ) && ! empty( $attributes['commentAddURL']['url'] ) ) ? $attributes['commentAddURL']['url'] : '';
			$target_c       = ( ! empty( $attributes['targetC'] ) ) ? $attributes['targetC'] : 'custom';

			if ( 'currentpage' === $target_c ) {
				$url_fc  = ( ! empty( $attributes['urlFC'] ) ) ? $attributes['urlFC'] : 'plain';
				$post_id = get_the_ID();

				if ( 'plain' === $url_fc ) {
					$plain_url = get_permalink( $post_id );
					$output   .= '<div class="fb-comments tpgb-fb-iframe" data-href="' . esc_url( $plain_url ) . '" data-width="" data-numposts="' . esc_attr( $attributes['countC'] ) . '" data-order-by="' . esc_attr( $attributes['orderByC'] ) . '" ></div>';
				} elseif ( 'pretty' === $url_fc ) {
					$pretty_url = add_query_arg( 'p', $post_id, home_url() );
					$output    .= '<div class="fb-comments tpgb-fb-iframe" data-href="' . esc_url( $pretty_url ) . '" data-width="" data-numposts="' . esc_attr( $attributes['countC'] ) . '" data-order-by="' . esc_attr( $attributes['orderByC'] ) . '" ></div>';
				}
			} else {
				$output .= '<div class="fb-comments tpgb-fb-iframe" data-href="' . esc_url( $fb_comment_add ) . '" data-width="" data-numposts="' . esc_attr( $attributes['countC'] ) . '" data-order-by="' . esc_attr( $attributes['orderByC'] ) . '" ></div>';
			}
			$output .= '<script async defer src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.2"></script>'; // phpcs:ignore WordPress.WP.EnqueuedResourcesNonEnqueuedScript.NonEnqueuedScript,WordPress.WP.EnqueuedResources.NonEnqueuedScript,WordPress.WP.EnqueuedResources
		}
		if ( 'posts' === $type ) {
			$post_url     = ( ! empty( $attributes['postURL'] ) && ! empty( $attributes['postURL']['url'] ) ) ? $attributes['postURL']['url'] : '';
			$wd_post      = ( ! empty( $attributes['wdPost'] ) ) ? $attributes['wdPost'] : 500;
			$hg_post      = ( ! empty( $attributes['hgPost'] ) ) ? $attributes['hgPost'] : 560;
			$iframe_title = ( ! empty( $attributes['iframeTitle'] ) ) ? esc_attr( $attributes['iframeTitle'] ) : esc_attr__( 'Social Facebook Embed', 'the-plus-addons-for-block-editor' );

			$output .= '<iframe class="tpgb-fb-iframe" src="https://www.facebook.com/plugins/post.php?href=' . esc_url( $post_url ) . '&show_text=' . esc_attr( $attributes['fullPT'] ) . '&width=' . esc_attr( $wd_post ) . '&height=' . esc_attr( $hg_post ) . '&appId=" width="' . esc_attr( $wd_post ) . '" height="' . esc_attr( $hg_post ) . '" scrolling="no" frameborder="0" allowTransparency="true" allow="encrypted-media" title="' . $iframe_title . '"></iframe>';
		}
		if ( 'videos' === $type ) {
			$videos_url   = ( ! empty( $attributes['videosURL'] ) && ! empty( $attributes['videosURL']['url'] ) ) ? $attributes['videosURL']['url'] : '';
			$full_video   = ( ! empty( $attributes['fullVT'] ) ) ? 'allowFullScreen="' . esc_attr( $attributes['wdVideo'] . '"' ) : '';
			$wd_video     = ( ! empty( $attributes['wdVideo'] ) ) ? $attributes['wdVideo'] : 500;
			$hg_video     = ( ! empty( $attributes['hgVideo'] ) ) ? $attributes['hgVideo'] : 560;
			$iframe_title = ( ! empty( $attributes['iframeTitle'] ) ) ? esc_attr( $attributes['iframeTitle'] ) : esc_attr__( 'Social Facebook Embed', 'the-plus-addons-for-block-editor' );

			$output .= '<iframe class="tpgb-fb-iframe" src="https://www.facebook.com/plugins/video.php?href=' . esc_url( $videos_url ) . '&show_text=' . esc_attr( $attributes['captionVT'] ) . '&width=' . esc_attr( $wd_video ) . '&height=' . esc_attr( $hg_video ) . '&autoplay=' . esc_attr( $attributes['autoplayVT'] ) . '&appId=" width="' . esc_attr( $wd_video ) . '" height="' . esc_attr( $hg_video ) . '" scrolling="no" frameborder="0" allowTransparency="true" allow="encrypted-media" ' . $full_video . ' title="' . $iframe_title . '"></iframe>';
		}
		if ( 'likebutton' === $type ) {
			$fb_like_btn  = ( ! empty( $attributes['likeBtnUrl'] ) && ! empty( $attributes['likeBtnUrl']['url'] ) ) ? $attributes['likeBtnUrl']['url'] : '';
			$faces_lbt    = ( ! empty( $attributes['facesLBT'] ) ) ? $attributes['facesLBT'] : false;
			$fb_hg_like   = ( ! empty( $attributes['hgLikeBtn'] ) ) ? $attributes['hgLikeBtn'] : 30;
			$f_bwd_like   = ( ! empty( $attributes['wdLikeBtn'] ) ) ? $attributes['wdLikeBtn'] : 350;
			$iframe_title = ( ! empty( $attributes['iframeTitle'] ) ) ? esc_attr( $attributes['iframeTitle'] ) : esc_attr__( 'Social Facebook Embed', 'the-plus-addons-for-block-editor' );

			if ( 'currentpage' === $attributes['targetLike'] ) {
				$fmt_ur_llb = ( ! empty( $attributes['fmtURLlb'] ) ) ? $attributes['fmtURLlb'] : 'plain';
				$post_id    = get_the_ID();
				if ( 'plain' === $fmt_ur_llb ) {
					$plain_lurl = get_permalink( $post_id );
					$output    .= '<iframe class="tpgb-fb-iframe" src="https://www.facebook.com/plugins/like.php?href=' . esc_url( $plain_lurl ) . '&layout=' . esc_attr( $attributes['btnStyleLB'] ) . '&action=' . esc_attr( $attributes['typeLB'] ) . '&size=' . esc_attr( $size_btn ) . '&share=' . esc_attr( $attributes['sBtnLB'] ) . '&height=' . esc_attr( $fb_hg_like ) . '&show_faces=' . esc_attr( $faces_lbt ) . '&colorscheme=' . esc_attr( $attributes['colorSLB'] ) . '&width=' . esc_attr( $f_bwd_like ) . '&appId=" width="' . esc_attr( $f_bwd_like ) . '" height="' . esc_attr( $fb_hg_like ) . '" scrolling="no" frameborder="0" allowTransparency="true" allow="encrypted-media" title="' . $iframe_title . '"></iframe>';
				} elseif ( 'pretty' === $fmt_ur_llb ) {
					$pretty_lurl = add_query_arg( 'p', $post_id, home_url() );
					$output     .= '<iframe class="tpgb-fb-iframe" src="https://www.facebook.com/plugins/like.php?href=' . esc_url( $pretty_lurl ) . '&layout=' . esc_attr( $attributes['btnStyleLB'] ) . '&action=' . esc_attr( $attributes['typeLB'] ) . '&size=' . esc_attr( $size_btn ) . '&share=' . esc_attr( $attributes['sBtnLB'] ) . '&height=' . esc_attr( $fb_hg_like ) . '&show_faces=' . esc_attr( $faces_lbt ) . '&colorscheme=' . esc_attr( $attributes['colorSLB'] ) . '&width=' . esc_attr( $f_bwd_like ) . '&appId=" width="' . esc_attr( $f_bwd_like ) . '" height="' . esc_attr( $fb_hg_like ) . '" scrolling="no" frameborder="0" allowTransparency="true" allow="encrypted-media" title="' . $iframe_title . '"></iframe>';
				}
			} else {
				$output .= '<iframe class="tpgb-fb-iframe" src="https://www.facebook.com/plugins/like.php?href=' . esc_url( $fb_like_btn ) . '&layout=' . esc_attr( $attributes['btnStyleLB'] ) . '&action=' . esc_attr( $attributes['typeLB'] ) . '&size=' . esc_attr( $size_btn ) . '&share=' . esc_attr( $attributes['sBtnLB'] ) . '&height=' . esc_attr( $fb_hg_like ) . '&show_faces=' . esc_attr( $faces_lbt ) . '&colorscheme=' . esc_attr( $attributes['colorSLB'] ) . '&width=' . esc_attr( $f_bwd_like ) . '&appId=" width="' . esc_attr( $f_bwd_like ) . '" height="' . esc_attr( $fb_hg_like ) . '" scrolling="no" frameborder="0" allowTransparency="true" allow="encrypted-media" title="' . $iframe_title . '"></iframe>';
			}
		}
		if ( 'page' === $type ) {
			$u_rlp        = ( ! empty( $attributes['uRLP'] ) && ! empty( $attributes['uRLP']['url'] ) ) ? $attributes['uRLP']['url'] : '';
			$wd_page      = ( ! empty( $attributes['wdPage'] ) ) ? $attributes['wdPage'] : 340;
			$hg_page      = ( ! empty( $attributes['hgPage'] ) ) ? $attributes['hgPage'] : 500;
			$iframe_title = ( ! empty( $attributes['iframeTitle'] ) ) ? esc_attr( $attributes['iframeTitle'] ) : esc_attr__( 'Social Facebook Embed', 'the-plus-addons-for-block-editor' );

			$output .= '<iframe class="tpgb-fb-iframe" src="https://www.facebook.com/plugins/page.php?href=' . esc_url( $u_rlp ) . '&tabs=' . esc_attr( $attributes['layoutP'] ) . '&width=' . esc_attr( $wd_page ) . '&height=' . esc_attr( $hg_page ) . '&small_header=' . esc_attr( $attributes['smallHP'] ) . '&hide_cover=' . esc_attr( $attributes['coverP'] ) . '&show_facepile=' . esc_attr( $attributes['profileP'] ) . '&hide_cta=' . esc_attr( $attributes['ctaBtn'] ) . '&lazy=true&adapt_container_width=true&appId=" width="' . esc_attr( $wd_page ) . '" height="' . esc_attr( $hg_page ) . '" scrolling="no" frameborder="0" allowTransparency="true" allow="encrypted-media" title="' . $iframe_title . '"></iframe>';
		}
		if ( 'save' === $type ) {
			$save_url = ( ! empty( $attributes['saveURL'] ) && ! empty( $attributes['saveURL']['url'] ) ) ? $attributes['saveURL']['url'] : '';

			$output .= '<div class="fb-save" data-uri="' . esc_url( $save_url ) . '" data-size="' . esc_attr( $size_btn ) . '"></div>';
			$output .= '<script async defer src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.2"></script>'; // phpcs:ignore WordPress.WP.EnqueuedResourcesNonEnqueuedScript.NonEnqueuedScript,WordPress.WP.EnqueuedResources.NonEnqueuedScript,WordPress.WP.EnqueuedResources
		}
		if ( 'share' === $type ) {
			$share_url    = ( ! empty( $attributes['shareURL'] ) && ! empty( $attributes['shareURL']['url'] ) ) ? $attributes['shareURL']['url'] : '';
			$share_w      = ( ! empty( $attributes['wdShare'] ) && ! empty( $attributes['wdShare'] ) ) ? $attributes['wdShare'] : 100;
			$share_h      = ( ! empty( $attributes['hgShare'] ) && ! empty( $attributes['hgShare'] ) ) ? $attributes['hgShare'] : 40;
			$iframe_title = ( ! empty( $attributes['iframeTitle'] ) ) ? esc_attr( $attributes['iframeTitle'] ) : esc_attr__( 'Facebook Share', 'the-plus-addons-for-block-editor' );

			$output .= '<iframe src="https://www.facebook.com/plugins/share_button.php?href=' . esc_url( $share_url ) . '&layout=' . esc_attr( $attributes['shareBtn'] ) . '&size=' . esc_attr( $size_btn ) . '&width=' . esc_attr( $share_w ) . '&height=' . esc_attr( $share_h ) . '&appId=" width="' . esc_attr( $share_w ) . '" height="' . esc_attr( $share_h ) . '" scrolling="no" frameborder="0" allowTransparency="true" allow="encrypted-media" title="' . $iframe_title . '"></iframe>';
		}
	} elseif ( 'twitter' === $embed_type ) {
		$tweet_type = ( ! empty( $attributes['tweetType'] ) ) ? $attributes['tweetType'] : 'timelines';
		$twname     = ( ! empty( $attributes['twname'] ) ) ? $attributes['twname'] : 'twitter';
		$tw_color   = ( ! empty( $attributes['twColor'] ) ) ? 'dark' : 'light';
		$twwidth    = ( ! empty( $attributes['twwidth'] ) ) ? $attributes['twwidth'] : '';
		$twconver   = ( ! empty( $attributes['twconver'] ) ) ? 'none' : '';
		$tw_msg     = ( ! empty( $attributes['twMsg'] ) ) ? $attributes['twMsg'] : '';

		if ( 'tweets' === $tweet_type ) {
			$tw_repeater = ( ! empty( $attributes['twRepeater'] ) ) ? $attributes['twRepeater'] : array();
			$tw_cards    = ( ! empty( $attributes['twCards'] ) ) ? 'hidden' : '';
			$tw_align    = ( ! empty( $attributes['twalign'] ) ) ? $attributes['twalign'] : 'center';

			foreach ( $tw_repeater as $index => $tweet ) {
				$tw_u_rl    = ( ! empty( $tweet['tweetURl'] ) && ! empty( $tweet['tweetURl']['url'] ) ) ? $tweet['tweetURl']['url'] : '';
				$tw_massage = ( ! empty( $tweet['twMassage'] ) ? $tweet['twMassage'] : '' );

				$output     .= '<blockquote class="twitter-tweet" data-theme="' . esc_attr( $tw_color ) . '" data-width="' . esc_attr( $twwidth ) . '" data-cards="' . esc_attr( $tw_cards ) . '" data-align="' . esc_attr( $tw_align ) . '" data-conversation="' . esc_attr( $twconver ) . '" >';
					$output .= '<p lang="en" dir="ltr">' . wp_kses_post( $tw_massage ) . '</p>';
					$output .= '<a href="' . esc_attr( $tw_u_rl ) . '"></a>';
				$output     .= '</blockquote>';
			}
		}
		if ( 'timelines' === $tweet_type ) {
			$tw_u_rl   = '';
			$twclass   = 'twitter-timeline';
			$tw_guides = ( ! empty( $attributes['twGuides'] ) ) ? $attributes['twGuides'] : 'profile';
			$tw_br_cr  = ( ! empty( $attributes['twBrCr'] ) ) ? $attributes['twBrCr'] : '';
			$twlimit   = ( ! empty( $attributes['twlimit'] ) ) ? $attributes['twlimit'] : '';
			$twstyle   = ( ! empty( $attributes['twstyle'] ) ) ? $attributes['twstyle'] : 'linear';
			$tw_design = ( ! empty( $attributes['twDesign'] ) ) ? json_decode( $attributes['twDesign'] ) : array();
			$twheight  = ( 'linear' === $twstyle ) ? $attributes['twheight'] : '';

			$design_btn = array();
			if ( is_array( $tw_design ) || is_object( $tw_design ) ) {
				foreach ( $tw_design as $value ) {
					$design_btn[] = $value->value;
				}
			}
			$tw_design = wp_json_encode( $design_btn );

			if ( 'profile' === $tw_guides ) {
				$tw_u_rl = 'https://twitter.com/' . esc_attr( $twname );
			} elseif ( 'list' === $tw_guides ) {
				$tw_u_rl = ( ! empty( $attributes['twlisturl'] ) && ! empty( $attributes['twlisturl']['url'] ) ) ? $attributes['twlisturl']['url'] : '';
			} elseif ( 'likes' === $tw_guides ) {
				$tw_u_rl = 'https://twitter.com/' . esc_attr( $twname ) . '/likes';
			} elseif ( 'collection' === $tw_guides ) {
				$twclass = 'twitter-grid';
				$tw_u_rl = ( ! empty( $attributes['twCollection'] ) && ! empty( $attributes['twCollection']['url'] ) ) ? $attributes['twCollection']['url'] : '';
			}
			$output .= '<a class="' . esc_attr( $twclass ) . '" href="' . esc_url( $tw_u_rl ) . '" data-width="' . esc_attr( $twwidth ) . '" data-height="' . esc_attr( $twheight ) . '" data-theme="' . esc_attr( $tw_color ) . '" data-chrome="' . esc_attr( $tw_design ) . '" data-border-color="' . esc_attr( $tw_br_cr ) . '" data-tweet-limit="' . esc_attr( $twlimit ) . '" data-aria-polite="" >' . wp_kses_post( $tw_msg ) . '</a>';
		}
		if ( 'buttons' === $tweet_type ) {
			$twbutton    = ( ! empty( $attributes['twbutton'] ) ) ? $attributes['twbutton'] : 'follow';
			$tw_btn_size = ( ! empty( $attributes['twBtnSize'] ) ) ? $attributes['twBtnSize'] : '';
			$tw_tweet_id = ( ! empty( $attributes['twTweetId'] ) ) ? $attributes['twTweetId'] : '';
			$twicon      = ( ! empty( $attributes['twIcon'] ) ) ? '' : '<i class="fab fa-twitter"></i>';

			if ( 'tweets' === $twbutton ) {
				$tw_via       = ( ! empty( $attributes['twVia'] ) ) ? $attributes['twVia'] : '';
				$tw_text_btn  = ( ! empty( $attributes['twTextBtn'] ) ) ? $attributes['twTextBtn'] : '';
				$tw_hashtags  = ( ! empty( $attributes['twHashtags'] ) ) ? $attributes['twHashtags'] : '';
				$tw_tweet_url = ( ! empty( $attributes['twTweetUrl'] ) && ! empty( $attributes['twTweetUrl']['url'] ) ) ? $attributes['twTweetUrl']['url'] : '';

				$output .= '<a class="twitter-share-button" href="https://twitter.com/intent/tweet" data-size="' . esc_attr( $tw_btn_size ) . '" data-text="' . esc_attr( $tw_text_btn ) . '" data-url="' . esc_url( $tw_tweet_url ) . '" data-via="' . esc_attr( $tw_via ) . '" data-hashtags="' . esc_attr( $tw_hashtags ) . '" >' . wp_kses_post( $tw_msg ) . '</a></br>';

			} elseif ( 'follow' === $twbutton ) {
				$tw_count      = ( ! empty( $attributes['twCount'] ) ) ? $attributes['twCount'] : 'false';
				$tw_hide_uname = ( ! empty( $attributes['twHideUname'] ) ) ? 'false' : $attributes['twHideUname'];

				$output .= '<a class="twitter-follow-button" href="https://twitter.com/' . esc_attr( $twname ) . '" data-size="' . esc_attr( $tw_btn_size ) . '" data-show-screen-name="' . esc_attr( $tw_hide_uname ) . '" data-show-count="' . esc_attr( $tw_count ) . '" >' . wp_kses_post( $tw_msg ) . '</a></br>';

			} elseif ( 'message' === $twbutton ) {
				$tw_r_id       = ( ! empty( $attributes['twRId'] ) ) ? $attributes['twRId'] : '';
				$tw_message    = ( ! empty( $attributes['twMessage'] ) ) ? $attributes['twMessage'] : '';
				$tw_hide_uname = ( ! empty( $attributes['twHideUname'] ) ) ? '@' : '';

				$output .= '<a class="twitter-dm-button" href="https://twitter.com/messages/compose?recipient_id=' . esc_attr( $tw_r_id ) . '" data-text="' . esc_attr( $tw_message ) . '" data-size="' . esc_attr( $tw_btn_size ) . '" data-screen-name="' . esc_attr( $tw_hide_uname . $twname ) . '">' . wp_kses_post( $tw_msg ) . '</a>';
			} elseif ( 'like' === $twbutton ) {
				$output .= '<a class="tw-button" href="https://twitter.com/intent/like?tweet_id=' . esc_attr( $tw_tweet_id ) . '" >' . wp_kses_post( $twicon . ' ' . $attributes['likeBtn'] ) . '</a>';
			} elseif ( 'reply' === $twbutton ) {
				$output .= '<a class="tw-button" href="https://twitter.com/intent/tweet?in_reply_to=' . esc_attr( $tw_tweet_id ) . '">' . wp_kses_post( $twicon . ' ' . $attributes['replyBtn'] ) . '</a>';
			} elseif ( 'reTweet' === $twbutton ) {
				$output .= '<a class="tw-button" href="https://twitter.com/intent/retweet?tweet_id=' . esc_attr( $tw_tweet_id ) . '">' . wp_kses_post( $twicon . ' ' . $attributes['reTweetBtn'] ) . '</a>';
			}
		}

		$output .= '<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>'; // phpcs:ignore WordPress.WP.EnqueuedResourcesNonEnqueuedScript.NonEnqueuedScript,WordPress.WP.EnqueuedResources.NonEnqueuedScript,WordPress.WP.EnqueuedResources
	} elseif ( 'vimeo' === $embed_type ) {
		$vm_id     = ( ! empty( $attributes['viId'] ) ) ? $attributes['viId'] : '';
		$vm_stime  = ( ! empty( $attributes['vmStime'] ) ) ? $attributes['vmStime'] : '';
		$vm_color  = ( ! empty( $attributes['vmColor'] ) ) ? ltrim( $attributes['vmColor'], '#' ) : 'ffffff';
		$vm_select = json_decode( $attributes['viOption'], true );

		$vm_all = array();
		foreach ( $vm_select as $v ) {
			$vm_all[] = $v['value'];
		}

		$vm_full_screen  = ( ( in_array( 'fullscreen', $vm_all, true ) ) ? 'webkitallowfullscreen="true" mozallowfullscreen="true" allowfullscreen="true"' : '' );
		$vm_auto_play    = ( in_array( 'autoplay', $vm_all, true ) ) ? 1 : 0;
		$vm_loop         = ( in_array( 'loop', $vm_all, true ) ) ? 1 : 0;
		$vm_muted        = ( in_array( 'muted', $vm_all, true ) ) ? 1 : 0;
		$vm_auto_pause   = ( in_array( 'autopause', $vm_all, true ) ) ? 1 : 0;
		$vm_back_ground  = ( in_array( 'background', $vm_all, true ) ) ? 1 : 0;
		$vm_byline       = ( in_array( 'byline', $vm_all, true ) ) ? 1 : 0;
		$vm_speed        = ( in_array( 'speed', $vm_all, true ) ) ? 1 : 0;
		$vm_title        = ( in_array( 'title', $vm_all, true ) ) ? 1 : 0;
		$vm_portrait     = ( in_array( 'portrait', $vm_all, true ) ) ? 1 : 0;
		$vm_play_sinline = ( in_array( 'playsinline', $vm_all, true ) ) ? 1 : 0;
		$vm_dnt          = ( in_array( 'dnt', $vm_all, true ) ) ? 1 : 0;
		$vm_pi_p         = ( in_array( 'pip', $vm_all, true ) ) ? 1 : 0;
		$vm_transparent  = ( in_array( 'transparent', $vm_all, true ) ) ? 1 : 0;
		$iframe_title    = ( ! empty( $attributes['iframeTitle'] ) ) ? esc_attr( $attributes['iframeTitle'] ) : esc_attr__( 'Social Vimeo', 'the-plus-addons-for-block-editor' );

		$output .= '<iframe class="tpgb-social-vimeo" src="https://player.vimeo.com/video/' . esc_attr( $vm_id ) . '?html5=1&amp;title=' . esc_attr( $vm_title ) . '&amp;byline=' . esc_attr( $vm_byline ) . '&amp;portrait=' . $vm_portrait . '&amp;autoplay=' . esc_attr( $vm_auto_play ) . '&amp;loop=' . esc_attr( $vm_loop ) . '&amp;muted=' . esc_attr( $vm_muted ) . '&amp;autopause=' . esc_attr( $vm_auto_pause ) . '&amp;background=' . esc_attr( $vm_back_ground ) . '&amp;playsinline=' . esc_attr( $vm_play_sinline ) . '&amp;speed=' . esc_attr( $vm_speed ) . '&amp;dnt=' . esc_attr( $vm_dnt ) . '&amp;pip=' . esc_attr( $vm_pi_p ) . '&amp;transparent=' . esc_attr( $vm_transparent ) . '&amp;color=' . esc_attr( $vm_color ) . '&amp;#t=' . esc_attr( $vm_stime ) . '" width="' . esc_attr( $ex_width ) . '" height="' . esc_attr( $ex_height ) . '" frameborder="0" ' . esc_attr( $vm_full_screen ) . ' title="' . $iframe_title . '"></iframe>';

	} elseif ( 'instagram' === $embed_type ) {
		$i_g_type = ( ! empty( $attributes['iGType'] ) ) ? $attributes['iGType'] : 'posts';
		$i_g_id   = ( ! empty( $attributes['iGId'] ) ) ? $attributes['iGId'] : 'CGAvnLcA3zb';
		$ig_cap   = ( empty( $attributes['iGCaptione'] ) ) ? 'data-instgrm-captioned' : '';

		if ( 'posts' === $i_g_type ) {
			$ig_id = 'p/' . esc_attr( $i_g_id );
		} elseif ( 'reels' === $i_g_type ) {
			$ig_id = 'reel/' . esc_attr( $i_g_id );
		} elseif ( 'igtv' === $i_g_type ) {
			$ig_id = 'tv/' . esc_attr( $i_g_id );
		}

		$output .= '<blockquote class="instagram-media" data-instgrm-version="13" data-instgrm-permalink="https://www.instagram.com/' . esc_attr( $ig_id ) . '/?utm_source=ig_embed" ' . esc_attr( $ig_cap ) . '></blockquote><script async src="//www.instagram.com/embed.js"></script>'; // phpcs:ignore WordPress.WP.EnqueuedResourcesNonEnqueuedScript.NonEnqueuedScript,WordPress.WP.EnqueuedResources.NonEnqueuedScript,WordPress.WP.EnqueuedResources

	} elseif ( 'youtube' === $embed_type ) {
		$yt_type    = ( ! empty( $attributes['ytType'] ) ) ? $attributes['ytType'] : 'ytSV';
		$yt_option  = json_decode( $attributes['ytOption'], true );
		$yt_s_time  = ( ! empty( $attributes['ytSTime'] ) ) ? $attributes['ytSTime'] : '';
		$yt_e_time  = ( ! empty( $attributes['ytETime'] ) ) ? $attributes['ytETime'] : '';
		$ytlanguage = ( ! empty( $attributes['ytlanguage'] ) ) ? $attributes['ytlanguage'] : '';

		$yt_select = array();
		foreach ( $yt_option as $v ) {
			$yt_select[] = ! empty( $v['value'] ) ? $v['value'] : '';
		}

		$yt_loop           = ( in_array( 'loop', $yt_select, true ) ) ? 1 : 0;
		$yt_fs             = ( in_array( 'fs', $yt_select, true ) ) ? 1 : 0;
		$yt_autoplay       = ( in_array( 'autoplay', $yt_select, true ) ) ? 1 : 0;
		$yt_muted          = ( in_array( 'mute', $yt_select, true ) ) ? 1 : 0;
		$yt_controls       = ( in_array( 'controls', $yt_select, true ) ) ? 1 : 0;
		$yt_disablekb      = ( in_array( 'disablekb', $yt_select, true ) ) ? 1 : 0;
		$yt_modestbranding = ( in_array( 'modestbranding', $yt_select, true ) ) ? 1 : 0;
		$yt_playsinline    = ( in_array( 'playsinline', $yt_select, true ) ) ? 1 : 0;
		$yt_rel            = ( in_array( 'rel', $yt_select, true ) ) ? 1 : 0;
		$iframe_title      = ( ! empty( $attributes['iframeTitle'] ) ) ? esc_attr( $attributes['iframeTitle'] ) : esc_attr__( 'Social Youtube', 'the-plus-addons-for-block-editor' );

		$yt_parameters = 'autoplay=' . esc_attr( $yt_autoplay ) . '&mute=' . esc_attr( $yt_muted ) . '&controls=' . esc_attr( $yt_controls ) . '&disablekb=' . esc_attr( $yt_disablekb ) . '&fs=' . esc_attr( $yt_fs ) . '&modestbranding=' . esc_attr( $yt_modestbranding ) . '&loop=' . esc_attr( $yt_loop ) . '&rel=' . esc_attr( $yt_rel ) . '&playsinline=' . esc_attr( $yt_playsinline ) . '&start=' . esc_attr( $yt_s_time ) . '&end=' . esc_attr( $yt_e_time ) . '&hl=' . esc_attr( $ytlanguage );

		if ( 'ytSV' === $yt_type ) {
			$yt_video_id = ( ! empty( $attributes['ytVideoId'] ) ) ? $attributes['ytVideoId'] : '';
			$video_id    = $yt_video_id;
			if ( strpos( $yt_video_id, 'https://www.youtube.com/watch' ) === 0 ) {
				$url_parts = wp_parse_url( $yt_video_id );

				if ( ! empty( $url_parts['query'] ) ) {
					parse_str( $url_parts['query'], $query_params );
					if ( ! empty( $query_params['v'] ) && preg_match( '/^[a-zA-Z0-9_-]{11}$/', $query_params['v'] ) ) {
						$video_id = sanitize_text_field( $query_params['v'] );
					}
				}
			}

			$yt_src = 'https://www.youtube-nocookie.com/embed/' . esc_attr( $video_id ) . '?' . esc_attr( $yt_parameters );
		} elseif ( 'ytPlayV' === $yt_type ) {
			$yt_playlist_id = ( ! empty( $attributes['ytPlaylistId'] ) ) ? $attributes['ytPlaylistId'] : '';
			$yt_src         = 'https://www.youtube-nocookie.com/embed?listType=playlist&list=' . esc_attr( $yt_playlist_id ) . '&' . esc_attr( $yt_parameters );
		} elseif ( 'ytUserV' === $yt_type ) {
			$yt_username = ( ! empty( $attributes['ytUsername'] ) ) ? $attributes['ytUsername'] : '';
			$yt_src      = 'https://www.youtube-nocookie.com/embed?listType=user_uploads&list=' . esc_attr( $yt_username ) . '&' . esc_attr( $yt_parameters );
		}

		$output .= '<iframe width="' . esc_attr( $ex_width ) . '" height="' . esc_attr( $ex_height ) . '" src=' . esc_attr( $yt_src ) . ' frameborder="0" allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen title="' . $iframe_title . '"></iframe>';

	} elseif ( 'googlemap' === $embed_type ) {
		$mapaccesstoken = ( ! empty( $attributes['mapaccesstoken'] ) ) ? $attributes['mapaccesstoken'] : 'default';
		$g_search_text  = ( ! empty( $attributes['gSearchText'] ) ) ? $attributes['gSearchText'] : 'Goa+India';
		$map_zoom       = ( ! empty( $attributes['mapZoom'] ) ) ? (int) $attributes['mapZoom'] : 1;
		$g_m_height     = ( ! empty( $attributes['gMHeight'] ) ) ? (int) $attributes['gMHeight'] : 450;
		$iframe_title   = ( ! empty( $attributes['iframeTitle'] ) ) ? esc_attr( $attributes['iframeTitle'] ) : esc_attr__( 'Google Map', 'the-plus-addons-for-block-editor' );

		if ( 'default' === $mapaccesstoken ) {
			$output .= '<iframe class="tpgb-gmap-embed" src="https://maps.google.com/maps?q=' . esc_attr( $g_search_text ) . '&z=' . esc_attr( $map_zoom ) . '&output=embed" height="' . esc_attr( $g_m_height ) . '" loading="lazy" allowfullscreen frameborder="0" scrolling="no" title="' . $iframe_title . '"></iframe>';
		} elseif ( 'accesstoken' === $mapaccesstoken ) {
			$g_accesstoken = ( ! empty( $attributes['gAccesstoken'] ) ) ? $attributes['gAccesstoken'] : '';
			if ( ! empty( $g_accesstoken ) ) {
				$g_map_modes = ( ! empty( $attributes['gMapModes'] ) ) ? $attributes['gMapModes'] : 'search';
				$map_views   = ( ! empty( $attributes['mapViews'] ) ) ? $attributes['mapViews'] : 'roadmap';

				if ( 'place' === $g_map_modes ) {
					$output .= '<iframe class="tpgb-gmap-embed" src="https://www.google.com/maps/embed/v1/place?key=' . esc_attr( $g_accesstoken ) . '&q=' . esc_attr( $g_search_text ) . '&zoom=' . esc_attr( $map_zoom ) . '&maptype=' . esc_attr( $map_views ) . '&language=En" height="' . esc_attr( $g_m_height ) . '" loading="lazy" allowfullscreen title="' . $iframe_title . '"></iframe>';
				} elseif ( 'direction' === $g_map_modes ) {
					$g_origin      = ( ! empty( $attributes['gOrigin'] ) ) ? '&origin=' . $attributes['gOrigin'] : '&origin=""';
					$g_destination = ( ! empty( $attributes['gDestination'] ) ) ? '&destination=' . $attributes['gDestination'] : '&destination=""';
					$g_waypoints   = ( ! empty( $attributes['gWaypoints'] ) ) ? '&waypoints=' . $attributes['gWaypoints'] : '';
					$g_travel_mode = ( ! empty( $attributes['gTravelMode'] ) ) ? $attributes['gTravelMode'] : 'gTravelMode';
					$gavoid        = ( ! empty( $attributes['Gavoid'] ) ) ? '&avoid=' . implode( '|', $attributes['Gavoid'] ) : '';

					$output .= '<iframe class="tpgb-gmap-embed" src="https://www.google.com/maps/embed/v1/directions?key=' . esc_attr( $g_accesstoken ) . esc_attr( $g_origin ) . esc_attr( $g_destination ) . esc_attr( $g_waypoints ) . esc_attr( $gavoid ) . '&mode=' . esc_attr( $g_travel_mode ) . '&zoom=' . esc_attr( $map_zoom ) . '&maptype=' . esc_attr( $map_views ) . '&language=En" height="' . esc_attr( $g_m_height ) . '" loading="lazy" allowfullscreen title="' . $iframe_title . '"></iframe>';
				} elseif ( 'streetview' === $g_map_modes ) {
					$gstreetview_text = ( ! empty( $attributes['gstreetviewText'] ) ) ? $attributes['gstreetviewText'] : '';

					$output .= '<iframe class="tpgb-gmap-embed" src="https://www.google.com/maps/embed/v1/streetview?key=' . esc_attr( $g_accesstoken ) . '&location=' . esc_attr( $gstreetview_text ) . '&heading=210&pitch=10&fov=90" height="' . esc_attr( $g_m_height ) . '" loading="lazy" allowfullscreen title="' . $iframe_title . '"></iframe>';
				} elseif ( 'search' === $g_map_modes ) {
					$output .= '<iframe class="tpgb-gmap-embed" src="https://www.google.com/maps/embed/v1/search?key=' . esc_attr( $g_accesstoken ) . '&q=' . esc_attr( $g_search_text ) . '&zoom=' . esc_attr( $map_zoom ) . '&maptype=' . esc_attr( $map_views ) . '&language=En" height="' . esc_attr( $g_m_height ) . '" loading="lazy" allowfullscreen title="' . $iframe_title . '"></iframe>';
				}
			} else {
				$output .= 'Enter Access Token';
			}
		}
	}

	$output .= '</div>';

	$output = Tpgb_Blocks_Global_Options::block_Wrap_Render( $attributes, $output );

	return $output;
}

/**
 * Tpgb tp social embed.
 */
function tpgb_tp_social_embed() {
	if ( method_exists( 'Tpgb_Blocks_Global_Options', 'merge_options_json' ) ) {
		$block_data = Tpgb_Blocks_Global_Options::merge_options_json( __DIR__, 'tpgb_social_embed_render_callback', true, false, true );
		register_block_type( $block_data['name'], $block_data );
	}
}
add_action( 'init', 'tpgb_tp_social_embed' );
