<?php
/**
 * Tp Video.
 *
 * @package ThePluginAddonsForBlockEditor
 */

defined( 'ABSPATH' ) || exit;

/**
 * Tpgb tp video callback.
 *
 * @param mixed $settings The settings.
 * @param mixed $content The content.
 */
function tpgb_tp_video_callback( $settings, $content ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter

	$block_id    = isset( $settings['block_id'] ) ? $settings['block_id'] : '';
	$anim_styles = isset( $settings['style'] ) ? $settings['style'] : 'style-1';

	$block_class    = Tp_Blocks_Helper::block_wrapper_classes( $settings );
	$sec_vid        = isset( $settings['secVid']['url'] ) ? $settings['secVid']['url'] : '';
	$fallback_image = isset( $settings['fallbackImage']['url'] ) ? $settings['fallbackImage']['url'] : '';

	// Google Schema Attributes.
	$mainsch  = '';
	$thumbsch = '';
	$titlesch = '';
	$descsch  = '';
	if ( ! empty( $settings['markupSch'] ) ) {
		$mainsch   = 'itemscope="" itemprop="VideoObject" itemtype="http://schema.org/VideoObject"';
		$thumbsch  = 'itemprop="thumbnailUrl"';
		$titlesch  = 'itemprop="name"';
		$descsch   = 'itemprop="description"';
		$uploadate = gmdate( 'j F Y' ); // Convert to UTC timezone.
	}

	$video_type = $settings['VideoType'];
	$youtube_id = '';
	$vimeo_id   = '';
	$mp4_url    = '';
	if ( ! empty( $settings['YoutubeID'] ) ) {
		$youtube_id = ( class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_val( $settings['YoutubeID'] ) : $settings['YoutubeID'];

	}
	if ( ! empty( $settings['VimeoID'] ) ) {
		$vimeo_id = ( class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_val( $settings['VimeoID'] ) : $settings['VimeoID'];
	}

	if ( ! empty( $settings['mp4Url']['url'] ) ) {
		$mp4_url = ( isset( $settings['mp4Url']['dynamic'] ) && class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_repeat_url( $settings['mp4Url'] ) : $settings['mp4Url']['url'];
	}
	$icon_effect = '';
	if ( ! empty( $settings['ContinueAnim'] ) && true === $settings['ContinueAnim'] ) {
		if ( true === $settings['ContinueAnimHover'] ) {
			$animation_class = 'tpgb-hover-';
		} else {
			$animation_class = 'tpgb-';
		}
		$icon_effect = $animation_class . $settings['ContinueAnimEffect'];
	}
	$video_content        = '';
	$banner_url           = '';
	$video_space          = '';
	$overlay_icon_img_url = '';
	$overlay_icon_img     = '';
	$image_alt            = '';
	$only_image           = '';
	$title                = '';
	$banner_img           = '';

	$icon_align_video = '';
	if ( ! empty( $settings['VideoTitle'] ) || ! empty( $settings['VideoDesc'] ) ) {
		$title     .= '<div class="ts-video-caption-text">';
			$title .= wp_kses_post( $settings['VideoTitle'] );
		if ( ! empty( $settings['VideoDesc'] ) ) {
			$title     .= '<div class="tpgb-video-desc" ' . $descsch . ' >';
				$title .= wp_kses_post( $settings['VideoDesc'] );
			$title     .= '</div>';
		}
		$title .= '</div>';
	}

	if ( ! empty( $settings['VideoIcon']['url'] ) ) {
		if ( isset( $settings['VideoIcon']['id'] ) ) {
			$video_icon      = $settings['VideoIcon']['id'];
			$img             = wp_get_attachment_image_src( $video_icon, $settings['VideoIconSize'] );
			$img             = ( isset( $img[0] ) && ! empty( $img[0] ) ) ? $img[0] : TPGB_ASSETS_URL . 'assets/images/tpgb-placeholder.jpg';
			$video_icon_icon = ( isset( $settings['VideoIcon']['dynamic'] ) && class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_repeat_url( $settings['VideoIcon'] ) : $img;
		} else {
			$video_icon_icon = ( isset( $settings['BannerImg']['dynamic'] ) && class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_repeat_url( $settings['BannerImg'] ) : ( isset( $settings['BannerImg']['url'] ) ? $settings['BannerImg']['url'] : '' );
		}
		$only_image .= '<img class="ts-video-only-icon" src="' . esc_url( $video_icon_icon ) . '" alt="' . esc_attr__( 'play-icon', 'the-plus-addons-for-block-editor' ) . '" />';
	}

	if ( ! empty( $settings['OverlayIconImg']['url'] ) ) {
		$overlay_icon_img_src = $settings['OverlayIconImg']['id'];
		$img                  = wp_get_attachment_image_src( $overlay_icon_img_src, $settings['OverlayIconImgSize'] );
		$img                  = ( isset( $img[0] ) && ! empty( $img[0] ) ) ? $img[0] : TPGB_ASSETS_URL . 'assets/images/tpgb-placeholder.jpg';
		$overlay_icon_img     = ( isset( $settings['OverlayIconImg']['dynamic'] ) && class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_repeat_url( $settings['OverlayIconImg'] ) : $img;

		$image_id  = $settings['OverlayIconImg']['id'];
		$image_alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
		if ( ! $image_alt ) {
			$image_alt = get_the_title( $image_id );
		} elseif ( ! $image_alt ) {
			$image_alt = 'Plus video thumb';
		}
		$overlay_icon_img_url .= '<div class="tp-video-icon-inner ' . esc_attr( $icon_effect ) . '"><img class="ts-video-icon" src="' . esc_url( $overlay_icon_img ) . '"  alt="' . esc_attr( $image_alt ) . '" /></div>';
	}
	if ( ! empty( $settings['BannerImg']['url'] ) ) {
		if ( ! empty( $settings['BannerImg']['id'] ) && isset( $settings['BannerImg']['id'] ) ) {
			$banner_img = $settings['BannerImg']['id'];
			$img        = wp_get_attachment_image_src( $banner_img, $settings['BannerImgSize'] );
			$banner_img = ( ! empty( $img ) && isset( $img[0] ) ) ? $img[0] : TPGB_ASSETS_URL . 'assets/images/tpgb-placeholder.jpg';
		} else {
			$banner_img = ( isset( $settings['BannerImg']['dynamic'] ) && class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_repeat_url( $settings['BannerImg'] ) : $settings['BannerImg']['url'];
		}
		$banner_url .= '<img class="ts-video-image-zoom set-image" src="' . esc_url( $banner_img ) . '" alt="' . esc_attr__( 'video', 'the-plus-addons-for-block-editor' ) . '" /><div class="tp-video-popup-icon"> <div class="tp-video-icon ' . esc_attr( $icon_effect ) . '"><img class="ts-video-caption" src="' . esc_url( $overlay_icon_img ) . '" alt="' . esc_attr( $image_alt ) . '" /></div></div>' . $title;
	}

	$youtube_attr       = '';
	$youtube_frame_attr = '';
	$video_touchable    = '';
	$self_video_attr    = '';
	$vimeo_frame_attr   = '';
	if ( ! empty( $settings['autoPlay'] ) ) {
		if ( 'youtube' === $video_type ) {
			$youtube_frame_attr .= '&amp;autoplay=1&amp;version=3';
			$youtube_attr        = ' allow="autoplay; encrypted-media"  ';
		}
		if ( 'vimeo' === $video_type ) {
			$vimeo_frame_attr .= '&amp;autoplay=1';
		}
		if ( 'self-hosted' === $video_type ) {
			$self_video_attr .= ' autoplay playsinline';
		}
	}

	if ( ! empty( $settings['loop'] ) ) {
		if ( 'youtube' === $video_type ) {
			$youtube_frame_attr .= '&amp;loop=1&amp;playlist=' . esc_attr( $settings['YoutubeID'] );
		}
		if ( 'vimeo' === $video_type ) {
			$vimeo_frame_attr .= '&amp;loop=1';
		}
		if ( 'self-hosted' === $video_type ) {
			$self_video_attr .= ' loop ';
		}
	}

	if ( ! empty( $settings['controls'] ) ) {
		if ( 'youtube' === $video_type ) {
			$youtube_frame_attr .= '&amp;controls=1';
		}
		if ( 'self-hosted' === $video_type ) {
			$self_video_attr .= ' controls ';
		}
	} elseif ( 'youtube' === $video_type ) {
			$youtube_frame_attr .= '&amp;controls=0';
	}
	if ( ! empty( $settings['showinfo'] ) ) {
		if ( 'youtube' === $video_type ) {
			$youtube_frame_attr .= '&amp;showinfo=1';
		}
	} elseif ( 'youtube' === $video_type ) {
			$youtube_frame_attr .= '&amp;showinfo=0';
	}
	if ( ! empty( $settings['ModestBranding'] ) ) {
		if ( 'youtube' === $video_type ) {
			$youtube_frame_attr .= '&amp;modestbranding=1';
		}
	} elseif ( 'youtube' === $video_type ) {
			$youtube_frame_attr .= '&amp;modestbranding=0';
	}
	if ( ! empty( $settings['rel'] ) ) {
		if ( 'youtube' === $video_type ) {
			$youtube_frame_attr .= '&amp;rel=1';
		}
	} elseif ( 'youtube' === $video_type ) {
			$youtube_frame_attr .= '&amp;rel=0';
	}
	$youtube_privacy = '';
	if ( ! empty( $settings['yt_privacy'] ) ) {
		if ( 'youtube' === $video_type ) {
			$youtube_privacy .= '-nocookie';
		}
	} elseif ( 'youtube' === $video_type ) {
			$youtube_privacy .= '';
	}
	if ( ! empty( $settings['muted'] ) ) {
		if ( 'youtube' === $video_type ) {
			$youtube_frame_attr .= '&amp;mute=1';
		}
		if ( 'vimeo' === $video_type ) {
			$vimeo_frame_attr .= '&amp;muted=1';
		}
		if ( 'self-hosted' === $video_type ) {
			$self_video_attr .= ' muted ';
		}
	}
	if ( ! empty( $settings['VideoColor'] ) ) {
		if ( 'vimeo' === $video_type ) {
			$video_color       = str_replace( '#', '', $settings['VideoColor'] );
			$vimeo_frame_attr .= '&amp;color=' . $video_color . '';
		}
	}
	if ( ! empty( $settings['VimeoTitle'] ) ) {
		if ( 'vimeo' === $video_type ) {
			$vimeo_frame_attr .= '&amp;title=1;';
		}
	} else {
		$vimeo_frame_attr .= '&amp;title=0;';
	}
	if ( ! empty( $settings['VimeoPortrait'] ) ) {
		if ( 'vimeo' === $video_type ) {
			$vimeo_frame_attr .= '&amp;portrait=1;';
		}
	} else {
		$vimeo_frame_attr .= '&amp;portrait=0;';
	}
	if ( ! empty( $settings['VimeoByline'] ) ) {
		if ( 'vimeo' === $video_type ) {
			$vimeo_frame_attr .= '&amp;byline=1;';
		}
	} else {
		$vimeo_frame_attr .= '&amp;byline=0;';
	}
	if ( ! empty( $settings['touchDisable'] ) ) {
		$video_touchable = ' not-touch ';
	}
	$image_banner    = $settings['image_banner'];
	$show_banner_img = $settings['ShowBannerImg'];
	if ( 'banner_img' === $image_banner ) {
		if ( true === $show_banner_img ) {
			if ( ! empty( $settings['VideoPopup'] ) ) {
				if ( 'youtube' === $video_type ) {
					$video_content .= '<a href="https://www.youtube' . $youtube_privacy . '.com/embed/' . esc_attr( $youtube_id ) . '" data-fancybox="' . esc_attr( $block_id ) . '">' . $banner_url . '</a>';
				} elseif ( 'vimeo' === $video_type ) {
					$vim_autoplay = '';
					if ( ! empty( $settings['autoPlay'] ) ) {
						$vim_autoplay = '?autoplay=1';
					}
					$video_content .= '<a data-type="iframe" href="https://player.vimeo.com/video/' . esc_attr( $vimeo_id ) . esc_attr( $vim_autoplay ) . '" data-fancybox="' . esc_attr( $block_id ) . '">' . $banner_url . '</a>';
				} elseif ( 'self-hosted' === $video_type ) {
					$video_content .= '<a href="' . esc_url( $mp4_url ) . '" data-fancybox="' . esc_attr( $block_id ) . '" type="video/mp4">' . $banner_url . '</a>';
				}
				$video_space = '';
			} elseif ( 'youtube' === $video_type ) {
					$video_content .= '<div class="ts-video-wrapper ts-video-hover-effect-zoom ts-type-' . esc_attr( $video_type ) . '" data-mode="lazyload" data-provider="' . esc_attr( $video_type ) . '" id="ts-video-video-6" ' . $mainsch . ' data-grow=""><div class="tpgb-video-embed-wrap" ><img class="tpgb-video-thumb" data-object-fit="" ' . $thumbsch . ' content="' . esc_url( $banner_img ) . '" src="' . esc_url( $banner_img ) . '" alt="' . esc_attr( 'Video Thumbnail' ) . '"><h5 ' . $titlesch . ' class="tpgb-video-title">' . $title . '</h5><span class="ts-video-lazyload" data-allowfullscreen="" data-class="pt-plus-video-frame fitvidsignore" data-frameborder="0" data-scrolling="no" data-src="https://www.youtube' . $youtube_privacy . '.com/embed/' . esc_attr( $youtube_id ) . '?html5=1&amp;title=0&amp;byline=0&amp;portrait=0&amp;autoplay=1' . $youtube_frame_attr . '"  data-sandbox="allow-scripts allow-same-origin allow-presentation allow-forms" data-width="480" data-height="270"></span><button class="tpgb-video-play-btn ts-video-blay-btn-youtube" type="button">' . $overlay_icon_img_url . '</button>';
				if ( ! empty( $settings['markupSch'] ) ) {
					$video_content .= '<div class="tpgb-video-upload" itemprop="uploadDate" content="' . $uploadate . '"></div><div class="tpgb-video-upload" itemprop="contentUrl" content="https://www.youtube' . $youtube_privacy . '.com/embed/' . esc_attr( $youtube_id ) . '?html5=1&amp;title=0&amp;byline=0&amp;portrait=0&amp;autoplay=1' . $youtube_frame_attr . '"></div>';
				}
					$video_content .= '</div></div>';
			} elseif ( 'vimeo' === $video_type ) {
				$video_content .= '<div class="ts-video-wrapper ts-video-hover-effect-zoom ts-type-' . esc_attr( $video_type ) . '" data-mode="lazyload" data-provider="' . esc_attr( $video_type ) . '" id="ts-video-video-6" ' . $mainsch . ' data-grow=""><div class="tpgb-video-embed-wrap" ><img class="tpgb-video-thumb" data-object-fit="" ' . $thumbsch . ' content="' . esc_url( $banner_img ) . '" src="' . esc_url( $banner_img ) . '" alt="' . esc_attr( 'Video Thumbnail' ) . '"><h5 ' . $titlesch . ' class="tpgb-video-title">' . $title . '</h5><span class="ts-video-lazyload" data-allowfullscreen="" data-class="pt-plus-video-frame fitvidsignore" data-frameborder="0" data-scrolling="no" data-src="https://player.vimeo.com/video/' . esc_attr( $vimeo_id ) . '?html5=1&amp;title=0&amp;byline=0&amp;portrait=0&amp;autoplay=1" data-sandbox="allow-scripts allow-same-origin allow-presentation allow-forms" data-width="480" data-height="270"></span><button class="tpgb-video-play-btn ts-video-blay-btn-youtube" type="button">' . $overlay_icon_img_url . '</button>';
				if ( ! empty( $settings['markupSch'] ) ) {
					$video_content .= '<div class="tpgb-video-upload" itemprop="uploadDate" content="' . $uploadate . '"></div><div class="tpgb-video-upload" itemprop="contentUrl" content="https://player.vimeo.com/video/' . esc_attr( $vimeo_id ) . '?html5=1&amp;title=0&amp;byline=0&amp;portrait=0&amp;autoplay=1"></div>';
				}
				$video_content .= '</div></div>';
			} elseif ( 'self-hosted' === $video_type ) {
				$video_content .= '<div class="ts-video-wrapper ts-video-hover-effect-zoom ts-type-' . esc_attr( $video_type ) . '" data-mode="lazyload" data-provider="' . esc_attr( $video_type ) . '" id="ts-video-video-6" ' . $mainsch . ' data-grow=""><div class="tpgb-video-embed-wrap"><img class="tpgb-video-thumb" data-object-fit="" ' . $thumbsch . ' content="' . esc_url( $banner_img ) . '" src="' . esc_url( $banner_img ) . '" alt="' . esc_attr__( 'Video Thumbnail', 'the-plus-addons-for-block-editor' ) . '"><h5 ' . $titlesch . ' class="tpgb-video-title">' . $title . '</h5><div class="video_container">';

				$video_content .= ( ! empty( $mp4_url ) || ! empty( $sec_vid ) || ! empty( $fallback_image ) ) ? '<video class="tpgb-video-poster" width="100%" poster="' . esc_url( $banner_img ) . '" controls data-fallback-ready="false">' . ( ! empty( $mp4_url ) ? '<source src="' . esc_url( $mp4_url ) . '" type="video/' . strtolower( pathinfo( $mp4_url, PATHINFO_EXTENSION ) ) . '" />' : '' ) . ( ! empty( $sec_vid ) ? '<source src="' . esc_url( $sec_vid ) . '" type="video/' . strtolower( pathinfo( $sec_vid, PATHINFO_EXTENSION ) ) . '" />' : '' ) .
				'</video>' . ( ! empty( $fallback_image ) ? '<img class="tpgb-video-poster nxt-fallback-img" width="100%" src="' . esc_url( $fallback_image ) . '" alt="Fallback image" style="display:none;" />' : '' ) : '';
				$video_content .= '</div></span><button class="tpgb-video-play-btn ts-video-blay-btn-youtube" type="button">' . $overlay_icon_img_url . '</button>';

				if ( ! empty( $settings['markupSch'] ) ) {
					$video_content .= '<div class="tpgb-video-upload" itemprop="uploadDate" content="' . $uploadate . '"></ div><div                 class="tpgb-video-upload" itemprop="contentUrl" content="' . esc_url( $mp4_url ) . '"></   div>';
				}

				$video_content .= '</div></div>';
			}
		} else {
			$iframe_title = ( ! empty( $settings['iframeTitle'] ) ) ? esc_attr( $settings['iframeTitle'] ) : esc_attr__( 'My Video', 'the-plus-addons-for-block-editor' );
			if ( 'youtube' === $video_type ) {
				$video_content .= '<div class="ts-video-wrapper embed-container  ts-type-' . esc_attr( $video_type ) . '"><iframe width="100%"  src="https://www.youtube' . $youtube_privacy . '.com/embed/' . esc_attr( $youtube_id ) . '?&amp;autohide=1&amp;showtitle=0' . $youtube_frame_attr . '" ' . $youtube_attr . ' frameborder="0" allowfullscreen title="' . $iframe_title . '"></iframe></div>';
			} elseif ( 'vimeo' === $video_type ) {
				$video_content .= '<div class="ts-video-wrapper embed-container  ts-type-' . esc_attr( $video_type ) . '"><iframe src="https://player.vimeo.com/video/' . $vimeo_id . '?html5=1&amp;title=0&amp;byline=0&amp;portrait=0&amp;' . $vimeo_frame_attr . '" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen title="' . $iframe_title . '"></iframe></div>';
			} elseif ( 'self-hosted' === $video_type ) {
				$video_content     .= '<div class="ts-video-wrapper ts-type-' . esc_attr( $video_type ) . '">';
					$video_content .= ( ! empty( $mp4_url ) || ! empty( $sec_vid ) || ! empty( $fallback_image ) ) ? '<video ' . esc_attr( $self_video_attr ) . ' data-fallback-ready="false">' . ( ! empty( $mp4_url ) ? '<source src="' . esc_url( $mp4_url ) . '" type="video/' . strtolower( pathinfo( $mp4_url, PATHINFO_EXTENSION ) ) . '" />' : '' ) . ( ! empty( $sec_vid ) ? '<source src="' . esc_url( $sec_vid ) . '" type="video/' . strtolower( pathinfo( $sec_vid, PATHINFO_EXTENSION ) ) . '" />' : '' ) . '</video>' . ( ! empty( $fallback_image ) ? '<img class="nxt-fallback-img" width="100%" src="' . esc_url( $fallback_image ) . '" alt="Fallback image" style="display:none;" />' : '' ) : '';
				$video_content     .= '</div>';
			}
		}
	} elseif ( 'only_icon' === $image_banner ) {
		if ( 'youtube' === $video_type ) {
			$video_content .= '<a href="https://www.youtube.com/embed/' . esc_attr( $youtube_id ) . '" class="tp-video-popup ' . esc_attr( $icon_effect ) . '" data-fancybox="' . esc_attr( $block_id ) . '">' . $only_image . '</a>';
		} elseif ( 'vimeo' === $video_type ) {
			$video_content .= '<a href="https://player.vimeo.com/video/' . esc_attr( $vimeo_id ) . '" class="tp-video-popup ' . esc_attr( $icon_effect ) . '" data-fancybox="' . esc_attr( $block_id ) . '">' . $only_image . '</a>';
		} elseif ( 'self-hosted' === $video_type ) {
			$video_content .= '<a href="' . esc_url( $mp4_url ) . '" class="tp-video-popup ' . esc_attr( $icon_effect ) . '" data-fancybox="' . esc_attr( $block_id ) . '" type="video/mp4">' . $only_image . '</a>';
		}
		$icon_align_video = $settings['IconAlign'];
	}

	$uid                   = 'video_player' . esc_attr( $block_id );
	$video_player          = '<div class="tp-video tpgb-video-box tpgb-block-' . esc_attr( $block_id ) . ' ' . esc_attr( $uid ) . ' ' . esc_attr( $block_class ) . '" data-id="' . esc_attr( $block_id ) . '">';
		$video_player     .= '<div class="tpgb_video_player tpgb-relative-block ' . esc_attr( $video_touchable ) . ' ' . esc_attr( $video_space ) . ' text-' . esc_attr( $icon_align_video ) . '">';
			$video_player .= $video_content;
		$video_player     .= '</div>';
	$video_player         .= '</div>';

	$video_player = Tpgb_Blocks_Global_Options::block_Wrap_Render( $settings, $video_player );

	return $video_player;
}

/**
 * Tpgb tp video render.
 */
function tpgb_tp_video_render() {
	$block_data = Tpgb_Blocks_Global_Options::merge_options_json( __DIR__, 'tpgb_tp_video_callback' );
	register_block_type( $block_data['name'], $block_data );
}
add_action( 'init', 'tpgb_tp_video_render' );
