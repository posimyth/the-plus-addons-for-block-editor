<?php
/**
 * Flip Box.
 *
 * @package ThePluginAddonsForBlockEditor
 */

defined( 'ABSPATH' ) || exit;

/**
 * Tpgb tp flipbox render callback.
 *
 * @param mixed $attributes The attributes.
 * @param mixed $content The content.
 * @return mixed The result.
 */
function tpgb_tp_flipbox_render_callback( $attributes, $content ) {
	$block_id = ( ! empty( $attributes['block_id'] ) ) ? $attributes['block_id'] : uniqid( 'title' );

	$pattern = '/\btpgb-block-' . esc_attr( $block_id ) . '/';
	if ( preg_match( $pattern, $content ) ) {
		if ( class_exists( 'Tpgb_Blocks_Global_Options' ) ) {
			$global_blocks = Tpgb_Blocks_Global_Options::get_instance();
			$content       = $global_blocks::block_row_conditional_render( $attributes, $content );
		}
		return $content;
	}

	$layout_type = ( ! empty( $attributes['layoutType'] ) ) ? $attributes['layoutType'] : 'listing';
	$flip_type   = ( ! empty( $attributes['flipType'] ) ) ? $attributes['flipType'] : 'horizontal';
	$icon_type   = ( ! empty( $attributes['iconType'] ) ) ? $attributes['iconType'] : 'icon';
	$icon_store  = ( ! empty( $attributes['iconStore'] ) ) ? $attributes['iconStore'] : '';
	$icon_style  = ( ! empty( $attributes['iconStyle'] ) ) ? $attributes['iconStyle'] : 'none';
	$svg_icon    = ( ! empty( $attributes['svgIcon'] ) ) ? $attributes['svgIcon'] : '';
	$imagestore  = ( ! empty( $attributes['imagestore'] ) ) ? $attributes['imagestore'] : '';
	$image_size  = ( ! empty( $attributes['imageSize'] ) ) ? $attributes['imageSize'] : 'thumbnail';
	$title_tag   = ( ! empty( $attributes['titleTag'] ) ) ? $attributes['titleTag'] : 'div';
	$title       = ( ! empty( $attributes['title'] ) ) ? $attributes['title'] : '';
	$description = ( ! empty( $attributes['description'] ) ) ? $attributes['description'] : '';

	$back_btn          = ( ! empty( $attributes['backBtn'] ) ) ? $attributes['backBtn'] : false;
	$back_carousel_btn = ( ! empty( $attributes['backCarouselBtn'] ) ) ? $attributes['backCarouselBtn'] : false;

	$flipcarousel = ( ! empty( $attributes['flipcarousel'] ) ) ? $attributes['flipcarousel'] : array();
	$back_align   = ( ! empty( $attributes['backAlign'] ) ) ? $attributes['backAlign'] : 'center';

	$svg_draw      = ( ! empty( $attributes['svgDraw'] ) ) ? $attributes['svgDraw'] : 'delayed';
	$svgstro_color = ( ! empty( $attributes['svgstroColor'] ) ) ? $attributes['svgstroColor'] : '';
	$svgfill_color = ( ! empty( $attributes['svgfillColor'] ) ) ? $attributes['svgfillColor'] : 'none';
	$svgstro_hov   = ( ! empty( $attributes['svgstroHov'] ) ) ? $attributes['svgstroHov'] : '';
	$svgfill_hov   = ( ! empty( $attributes['svgfillHov'] ) ) ? $attributes['svgfillHov'] : '';
	$svg_dura      = ( ! empty( $attributes['svgDura'] ) ) ? $attributes['svgDura'] : 90;

	$block_class = Tp_Blocks_Helper::block_wrapper_classes( $attributes );

	$count             = '';
	$sliderclass       = '';
	$arrow_css         = '';
	$carousel_settings = array();
	if ( 'carousel' === $layout_type ) {

		$carousel_settings = Tp_Blocks_Helper::carousel_settings( $attributes );

		$sliderclass .= 'tpgb-carousel splide';

		$show_dots  = ( ! empty( $attributes['showDots'] ) ) ? $attributes['showDots'] : array( 'md' => false );
		$dots_style = ( ! empty( $attributes['dotsStyle'] ) ) ? $attributes['dotsStyle'] : false;
		if ( ( isset( $show_dots['md'] ) && ! empty( $show_dots['md'] ) ) || ( isset( $show_dots['sm'] ) && ! empty( $show_dots['sm'] ) ) || ( isset( $show_dots['xs'] ) && ! empty( $show_dots['xs'] ) ) ) {
			$sliderclass .= ' dots-' . esc_attr( $dots_style );
		}

		$show_arrows  = ( ! empty( $attributes['showArrows'] ) ) ? $attributes['showArrows'] : array( 'md' => false );
		$arrows_style = ( ! empty( $attributes['arrowsStyle'] ) ) ? $attributes['arrowsStyle'] : 'style-1';

		$arrow_css = Tp_Blocks_Helper::tpgb_carousel_arrow_css( $show_arrows, $block_id );
	}

	// img src.
	$img_src  = '';
	$alt_text = ( isset( $imagestore['alt'] ) && ! empty( $imagestore['alt'] ) ) ? esc_attr( $imagestore['alt'] ) : ( ( ! empty( $imagestore['title'] ) ) ? esc_attr( $imagestore['title'] ) : esc_attr__( 'FlipBox', 'the-plus-addons-for-block-editor' ) );
	if ( ! empty( $imagestore ) && ! empty( $imagestore['id'] ) ) {
		$counter_img = $imagestore['id'];
		$img_src     = wp_get_attachment_image(
			$counter_img,
			$image_size,
			false,
			array(
				'class' => 'service-img',
				'alt'   => $alt_text,
			)
		);
	} elseif ( ! empty( $imagestore['url'] ) ) {
		$img_src = '<img src="' . esc_url( $imagestore['url'] ) . '" class="service-img" alt="' . $alt_text . '"/>';
	}

	$output  = '';
	$output .= '<div class="tpgb-flipbox tpgb-relative-block ' . esc_attr( $sliderclass ) . ' list-' . esc_attr( $layout_type ) . ' flip-box-style-1 tpgb-block-' . esc_attr( $block_id ) . ' ' . esc_attr( $block_class ) . '" data-splide=\'' . wp_json_encode( $carousel_settings ) . '\'>';
	if ( 'listing' === $layout_type ) {
		$output                     .= '<div class="flip-box-inner content_hover_effect ">';
			$output                 .= '<div class="flip-box-bg-box">';
				$output             .= '<div class="service-flipbox flip-' . esc_attr( $flip_type ) . ' height-full">';
					$output         .= '<div class="service-flipbox-holder height-full text-center perspective bezier-1">';
						$output     .= '<div class="service-flipbox-front bezier-1 no-backface origin-center">';
							$output .= '<div class="service-flipbox-content width-full">';
		if ( 'icon' === $icon_type ) {
			$output .= '<span class="service-icon tpgb-trans-linear icon-' . esc_attr( $icon_style ) . '">';
			$output .= '<i class="' . esc_attr( $icon_store ) . '"></i>';
			$output .= '</span>';
		}
		if ( 'img' === $icon_type && ! empty( $imagestore ) ) {
			$output .= $img_src;
		}
		if ( 'svg' === $icon_type && ! empty( $svg_icon ) && ! empty( $svg_icon['url'] ) ) {
			$output .= '<div class="tpgb-draw-svg" data-id="service-svg-' . esc_attr( $block_id ) . '" data-type="' . esc_attr( $svg_draw ) . '" data-duration="' . esc_attr( $svg_dura ) . '" data-stroke="' . esc_attr( $svgstro_color ) . '" data-fillColor="' . esc_attr( $svgfill_color ) . '" data-strokeColorHover="' . esc_attr( $svgstro_hov ) . '" data-fillColorHover="' . esc_attr( $svgfill_hov ) . '" data-fillEnable="yes">';
			$output .= '<object id="service-svg-' . esc_attr( $block_id ) . '" type="image/svg+xml" data="' . esc_url( $svg_icon['url'] ) . '" aria-label="' . esc_attr__( 'icon', 'the-plus-addons-for-block-editor' ) . '"></object>';
			$output .= '</div>';
		}
								$output     .= '<div class="service-content">';
									$output .= '<' . Tp_Blocks_Helper::validate_html_tag( $title_tag ) . ' class="service-title tpgb-trans-linear">' . wp_kses_post( $title ) . '</' . Tp_Blocks_Helper::validate_html_tag( $title_tag ) . '>';
								$output     .= '</div>';
							$output         .= '</div>';
							$output         .= '<div class="flipbox-front-overlay tpgb-trans-linear"></div>';
						$output             .= '</div>';
						$output             .= '<div class="service-flipbox-back fold-back-' . esc_attr( $flip_type ) . ' no-backface bezier-1 origin-center text-' . esc_attr( $back_align ) . '">';
							$output         .= '<div class="service-flipbox-content width-full">';
								$output     .= '<div class="service-desc tpgb-trans-linear">' . wp_kses_post( $description ) . '</div>';
		if ( ! empty( $back_btn ) ) {
			$output .= tpgb_getButtonRender( $attributes );
		}
							$output .= '</div>';
							$output .= '<div class="flipbox-back-overlay tpgb-trans-linear"></div>';
						$output     .= '</div>';
						$output     .= '</div>';
						$output     .= '</div>';
						$output     .= '</div>';
						$output     .= '</div>';
	}
	if ( 'carousel' === $layout_type ) {
		if ( ( isset( $show_arrows['md'] ) && ! empty( $show_arrows['md'] ) ) || ( isset( $show_arrows['sm'] ) && ! empty( $show_arrows['sm'] ) ) || ( isset( $show_arrows['xs'] ) && ! empty( $show_arrows['xs'] ) ) ) {
			$output .= Tp_Blocks_Helper::tpgb_carousel_arrow( $arrows_style, '' );
		}
		$output     .= '<div class="post-loop-inner splide__track">';
			$output .= '<div class="splide__list">';
		if ( ! empty( $flipcarousel ) ) {
			foreach ( $flipcarousel as $index => $item ) {
				++$count;
				$output                     .= '<div class="splide__slide flip-box-inner content_hover_effect tp-repeater-item-' . esc_attr( $item['_key'] ) . '" data-index="' . esc_attr( $count ) . '">';
					$output                 .= '<div class="flip-box-bg-box">';
						$output             .= '<div class="service-flipbox flip-' . esc_attr( $flip_type ) . ' height-full">';
							$output         .= '<div class="service-flipbox-holder height-full text-center perspective bezier-1">';
								$output     .= '<div class="service-flipbox-front bezier-1 no-backface origin-center">';
									$output .= '<div class="service-flipbox-content width-full">';
				if ( 'icon' === $item['iconType'] ) {
					$output .= '<span class="service-icon tpgb-trans-linear icon-' . esc_attr( $icon_style ) . '"></i>';
					$output .= '<i class="' . esc_attr( $item['iconStore'] ) . '"></i>';
					$output .= '</span>';
				}
				if ( 'image' === $item['iconType'] && ! empty( $item['imagestore'] ) ) {
					$image_size = ( ! empty( $item['imageSize'] ) ) ? $item['imageSize'] : 'thumbnail';
					$alt_text   = ( isset( $item['imagestore']['alt'] ) && ! empty( $item['imagestore']['alt'] ) ) ? esc_attr( $item['imagestore']['alt'] ) : ( ( ! empty( $item['imagestore']['title'] ) ) ? esc_attr( $item['imagestore']['title'] ) : esc_attr__( 'FlipBox', 'the-plus-addons-for-block-editor' ) );

					if ( ! empty( $item['imagestore']['id'] ) ) {
													$img_src = wp_get_attachment_image(
														$item['imagestore']['id'],
														$image_size,
														false,
														array(
															'class' => 'service-img',
															'alt' => $alt_text,
														)
													);
					} elseif ( ! empty( $item['imagestore']['url'] ) ) {
								$img_src = $item['imagestore']['url'];
								$img_src = '<img src="' . esc_url( $img_src ) . '" class="service-img" alt="' . $alt_text . '"/>';
					}
							$output .= $img_src;
				}
				if ( 'svg' === $item['iconType'] && isset( $item['svgFIcon'] ) && isset( $item['svgFIcon']['url'] ) ) {
					$output .= '<div class="tpgb-draw-svg" data-id="service-svg-' . esc_attr( $item['_key'] ) . '" data-type="' . esc_attr( $svg_draw ) . '" data-duration="' . esc_attr( $svg_dura ) . '" data-stroke="' . esc_attr( $svgstro_color ) . '" data-fillColor="' . esc_attr( $svgfill_color ) . '" data-strokeColorHover="' . esc_attr( $svgstro_hov ) . '" data-fillColorHover="' . esc_attr( $svgfill_hov ) . '" data-fillEnable="yes">';
					$output .= '<object id="service-svg-' . esc_attr( $item['_key'] ) . '" type="image/svg+xml" data="' . esc_url( $item['svgFIcon']['url'] ) . '" aria-label="' . esc_attr__( 'icon', 'the-plus-addons-for-block-editor' ) . '"></object>';
					$output .= '</div>';
				}
										$output     .= '<div class="service-content">';
											$output .= '<' . Tp_Blocks_Helper::validate_html_tag( $title_tag ) . ' class="service-title tpgb-trans-linear">' . wp_kses_post( $item['title'] ) . '</' . Tp_Blocks_Helper::validate_html_tag( $title_tag ) . '>';
										$output     .= '</div>';
									$output         .= '</div>';
									$output         .= '<div class="flipbox-front-overlay tpgb-trans-linear"></div>';
								$output             .= '</div>';
								$output             .= '<div class="service-flipbox-back fold-back-' . esc_attr( $flip_type ) . ' no-backface bezier-1 origin-center text-' . esc_attr( $back_align ) . '">';
									$output         .= '<div class="service-flipbox-content width-full">';
										$output     .= '<div class="service-desc tpgb-trans-linear">' . wp_kses_post( $item['description'] ) . '</div>';
				if ( ! empty( $back_carousel_btn ) ) {
					$output .= tpgb_getButtonRender( $attributes, $item['btnUrl'], $item['btnText'] );
				}
									$output .= '</div>';
									$output .= '<div class="flipbox-back-overlay tpgb-trans-linear"></div>';
								$output     .= '</div>';
								$output     .= '</div>';
								$output     .= '</div>';
								$output     .= '</div>';
								$output     .= '</div>';
			}
		}
			$output .= '</div>';
			$output .= '</div>';
	}
	$output .= '</div>';

	$output = Tpgb_Blocks_Global_Options::block_Wrap_Render( $attributes, $output );
	if ( 'carousel' === $layout_type && ! empty( $arrow_css ) ) {
		$output .= $arrow_css;
	}
	return $output;
}

/**
 * Tpgb get button render.
 *
 * @param mixed  $attributes The attributes.
 * @param string $item_btn_url The item btn url.
 * @param string $item_btn_text The item btn text.
 * @return mixed The result.
 */
function tpgb_getButtonRender( $attributes, $item_btn_url = '', $item_btn_text = '' ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid,WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
	$layout_type                = ( ! empty( $attributes['layoutType'] ) ) ? $attributes['layoutType'] : 'listing';
	$btn_style                  = ( ! empty( $attributes['btnStyle'] ) ) ? $attributes['btnStyle'] : 'style-7';
	$btn_carousel_style         = ( ! empty( $attributes['btnCarouselStyle'] ) ) ? $attributes['btnCarouselStyle'] : 'style-7';
	$btn_icon_type              = ( ! empty( $attributes['btnIconType'] ) ) ? $attributes['btnIconType'] : 'none';
	$btn_carousel_icon_type     = ( ! empty( $attributes['btnCarouselIconType'] ) ) ? $attributes['btnCarouselIconType'] : 'none';
	$btn_icon_name              = ( ! empty( $attributes['btnIconName'] ) ) ? $attributes['btnIconName'] : '';
	$btn_carousel_icon_name     = ( ! empty( $attributes['btnCarouselIconName'] ) ) ? $attributes['btnCarouselIconName'] : '';
	$btn_icon_position          = ( ! empty( $attributes['btnIconPosition'] ) ) ? $attributes['btnIconPosition'] : 'after';
	$btn_carousel_icon_position = ( ! empty( $attributes['btnCarouselIconPosition'] ) ) ? $attributes['btnCarouselIconPosition'] : 'after';
	$btn_text                   = ( ! empty( $attributes['btnText'] ) ) ? $attributes['btnText'] : '';
	$btn_url                    = ( ! empty( $attributes['btnUrl'] ) ) ? $attributes['btnUrl'] : '';

	$new_btn_text = ( 'carousel' === $layout_type ) ? $item_btn_text : $btn_text;
	$get_btn_text = '<div class="btn-text">' . wp_kses_post( $new_btn_text ) . '</div>';

	$getbutton = '';

	$new_btn_style         = ( 'carousel' === $layout_type ) ? $btn_carousel_style : $btn_style;
	$new_btn_type          = ( 'carousel' === $layout_type ) ? $btn_carousel_icon_type : $btn_icon_type;
	$new_btn_icon_position = ( 'carousel' === $layout_type ) ? $btn_carousel_icon_position : $btn_icon_position;
	$new_btn_icon_name     = ( 'carousel' === $layout_type ) ? $btn_carousel_icon_name : $btn_icon_name;
	$new_btn_url           = ( 'carousel' === $layout_type ) ? $item_btn_url : $btn_url;
	$target                = ( ! empty( $new_btn_url['target'] ) ? ' target="_blank" ' : '' );
	$nofollow              = ( ! empty( $new_btn_url['nofollow'] ) ) ? ' rel="nofollow" ' : '';
	$btn_attr              = Tp_Blocks_Helper::add_link_attributes( $new_btn_url );
	$getbutton            .= '<div class="tpgb-adv-button button-' . esc_attr( $new_btn_style ) . '">';
		$getbutton        .= '<a href="' . esc_url( $new_btn_url['url'] ) . '" class="button-link-wrap" role="button" ' . $target . ' ' . $nofollow . ' ' . $btn_attr . '>';
	if ( 'style-8' === $new_btn_style ) {
		if ( 'before' === $new_btn_icon_position ) {
			if ( 'icon' === $new_btn_type ) {
				$getbutton     .= '<span class="btn-icon  button-' . esc_attr( $new_btn_icon_position ) . '">';
					$getbutton .= '<i class="' . esc_attr( $new_btn_icon_name ) . '"></i>';
				$getbutton     .= '</span>';
			}
			$getbutton .= $get_btn_text;
		}
		if ( 'after' === $new_btn_icon_position ) {
			$getbutton .= $get_btn_text;
			if ( 'icon' === $new_btn_type ) {
				$getbutton     .= '<span class="btn-icon  button-' . esc_attr( $new_btn_icon_position ) . '">';
					$getbutton .= '<i class="' . esc_attr( $new_btn_icon_name ) . '"></i>';
				$getbutton     .= '</span>';
			}
		}
	}
	if ( 'style-7' === $new_btn_style || 'style-9' === $new_btn_style ) {
		$getbutton .= $get_btn_text;

		$getbutton .= '<span class="button-arrow">';
		if ( 'style-7' === $new_btn_style ) {
			$getbutton .= '<span class="btn-right-arrow"><i class="fas fa-chevron-right"></i></span>';
		}
		if ( 'style-9' === $new_btn_style ) {
			$getbutton .= '<i class="btn-show fas fa-chevron-right"></i>';
			$getbutton .= '<i class="btn-hide fas fa-chevron-right"></i>';
		}
		$getbutton .= '</span>';
	}
		$getbutton .= '</a>';
	$getbutton     .= '</div>';
	return $getbutton;
}

/**
 * Render for the server-side
 */
function tpgb_flipbox() {
	$block_data = Tpgb_Blocks_Global_Options::merge_options_json( __DIR__, 'tpgb_tp_flipbox_render_callback', true, true );
	register_block_type( $block_data['name'], $block_data );
}
add_action( 'init', 'tpgb_flipbox' );
