<?php
/**
 * Info Box.
 *
 * @package ThePluginAddonsForBlockEditor
 */

// phpcs:disable Squiz.PHP.CommentedOutCode.Found
defined( 'ABSPATH' ) || exit;

/**
 * Tpgb tp infobox render callback.
 *
 * @param mixed $attributes The attributes.
 * @param mixed $content The content.
 * @return mixed The result.
 */
function tpgb_tp_infobox_render_callback( $attributes, $content ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
	$output   = '';
	$block_id = ( ! empty( $attributes['block_id'] ) ) ? $attributes['block_id'] : uniqid( 'title' );

	// $pattern = '/\btpgb-block-'.esc_attr($block_id).'/'; // phpcs:ignore Squiz.PHP.CommentedOutCode.Found
	// if (preg_match($pattern, $content)) {
	// if( class_exists('Tpgb_Blocks_Global_Options') ){
	// $global_blocks = Tpgb_Blocks_Global_Options::get_instance();
	// $content = $global_blocks::block_row_conditional_render($attributes,$content);
	// }
	// return $content;
	// }

	$layout_type         = ( ! empty( $attributes['layoutType'] ) ) ? $attributes['layoutType'] : 'listing';
	$style_type          = ( ! empty( $attributes['styleType'] ) ) ? $attributes['styleType'] : 'style-1';
	$ext_btnshow         = ( ! empty( $attributes['extBtnshow'] ) ) ? $attributes['extBtnshow'] : false;
	$vertical_center     = ( ! empty( $attributes['verticalCenter'] ) ) ? $attributes['verticalCenter'] : false;
	$side_img_border     = ( ! empty( $attributes['sideImgBorder'] ) ) ? $attributes['sideImgBorder'] : false;
	$display_border      = ( ! empty( $attributes['displayBorder'] ) ) ? $attributes['displayBorder'] : false;
	$disp_pin_text       = ( ! empty( $attributes['dispPinText'] ) ) ? $attributes['dispPinText'] : false;
	$pin_text            = ( ! empty( $attributes['pinText'] ) ) ? $attributes['pinText'] : 'New';
	$i_box_link_tgl      = ( ! empty( $attributes['IBoxLinkTgl'] ) ) ? $attributes['IBoxLinkTgl'] : false;
	$i_box_link          = ( ! empty( $attributes['IBoxLink']['url'] ) ) ? $attributes['IBoxLink']['url'] : '';
	$target              = ( ! empty( $attributes['IBoxLink']['target'] ) ) ? ' target="_blank" ' : '';
	$nofollow            = ( ! empty( $attributes['IBoxLink']['nofollow'] ) ) ? ' rel="nofollow" ' : '';
	$icon_type           = ( ! empty( $attributes['iconType'] ) ) ? $attributes['iconType'] : 'icon';
	$icon_overlay        = ( ! empty( $attributes['iconOverlay'] ) ) ? $attributes['iconOverlay'] : false;
	$img_overlay         = ( ! empty( $attributes['imgOverlay'] ) ) ? $attributes['imgOverlay'] : false;
	$icon_shine          = ( ! empty( $attributes['iconShine'] ) ) ? $attributes['iconShine'] : false;
	$icon_name           = ( ! empty( $attributes['IconName'] ) ) ? $attributes['IconName'] : '';
	$text_icon           = ( ! empty( $attributes['textIcon'] ) ) ? $attributes['textIcon'] : '';
	$image_name          = ( ! empty( $attributes['imageName']['url'] ) ) ? $attributes['imageName'] : '';
	$image_size          = ( ! empty( $attributes['imageSize'] ) ) ? $attributes['imageSize'] : 'full';
	$title               = ( ! empty( $attributes['Title'] ) ) ? $attributes['Title'] : '';
	$description         = ( ! empty( $attributes['Description'] ) ) ? $attributes['Description'] : '';
	$iconstyle_type      = ( ! empty( $attributes['iconstyleType'] ) ) ? $attributes['iconstyleType'] : 'none';
	$contenthover_effect = ( ! empty( $attributes['contenthoverEffect'] ) ) ? $attributes['contenthoverEffect'] : '';
	$carousel_id         = ( ! empty( $attributes['carouselId'] ) ) ? $attributes['carouselId'] : '';

	$svg_icon            = ( ! empty( $attributes['svgIcon'] ) ) ? $attributes['svgIcon'] : '';
	$svg_draw            = ( ! empty( $attributes['svgDraw'] ) ) ? $attributes['svgDraw'] : 'delayed';
	$svgstro_color       = ( ! empty( $attributes['svgstroColor'] ) ) ? $attributes['svgstroColor'] : '';
	$svgfill_color       = ( ! empty( $attributes['svgfillColor'] ) ) ? $attributes['svgfillColor'] : 'none';
	$svgstro_hover_color = ( ! empty( $attributes['svgstroHoverColor'] ) ) ? $attributes['svgstroHoverColor'] : '';
	$svgfill_hover_color = ( ! empty( $attributes['svgfillHoverColor'] ) ) ? $attributes['svgfillHoverColor'] : 'none';
	$svg_dura            = ( ! empty( $attributes['svgDura'] ) ) ? $attributes['svgDura'] : 90;

	$title_type = ( ! empty( $attributes['titleType'] ) ) ? $attributes['titleType'] : 'div';
	$desc_type  = ( ! empty( $attributes['descType'] ) ) ? $attributes['descType'] : 'div';

	$show_arrows     = ( ! empty( $attributes['showArrows'] ) ) ? $attributes['showArrows'] : array( 'md' => false );
	$arrows_style    = ( ! empty( $attributes['arrowsStyle'] ) ) ? $attributes['arrowsStyle'] : 'style-1';
	$arrows_position = ( ! empty( $attributes['arrowsPosition'] ) ) ? $attributes['arrowsPosition'] : 'top-right';

	$block_class = Tp_Blocks_Helper::block_wrapper_classes( $attributes );

	$count             = 0;
	$sliderclass       = '';
	$arrow_css         = '';
	$carousel_settings = '';
	if ( 'carousel' === $layout_type ) {
		$carousel_settings = Tp_Blocks_Helper::carousel_settings( $attributes );
		$carousel_settings = 'data-splide=\'' . wp_json_encode( $carousel_settings ) . '\'';

		$sliderclass .= 'tpgb-carousel splide';
		$sliderclass .= Tp_Blocks_Helper::tpgb_carousel_arrowdot_class( $attributes );
		$arrow_css    = Tp_Blocks_Helper::tpgb_carousel_arrow_css( $show_arrows, $block_id );

		if ( ! empty( $carousel_id ) ) {
			$carousel_settings .= ' id="tpca-' . esc_attr( $carousel_id ) . '"';
			$carousel_settings .= ' data-id="tpca-' . esc_attr( $carousel_id ) . '"';
			$carousel_settings .= ' data-connection="tptab_' . esc_attr( $carousel_id ) . '"';
		}
	}

	$img_src  = '';
	$alt_text = ( isset( $image_name['alt'] ) && ! empty( $image_name['alt'] ) ) ? esc_attr( $image_name['alt'] ) : ( ( ! empty( $image_name['title'] ) ) ? esc_attr( $image_name['title'] ) : esc_attr__( 'Info Box', 'the-plus-addons-for-block-editor' ) );
	if ( ! empty( $image_name ) && ! empty( $image_name['id'] ) ) {
		$img_src = wp_get_attachment_image(
			$image_name['id'],
			$image_size,
			false,
			array(
				'class' => 'service-icon tpgb-trans-linear',
				'alt'   => $alt_text,
			)
		);
	} elseif ( ! empty( $image_name['url'] ) ) {
		$img_src = '<img src="' . esc_url( $image_name['url'] ) . '" class="service-icon tpgb-trans-linear" alt=' . $alt_text . ' />';
	}
	if ( class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) {
		$i_box_link = ( isset( $attributes['IBoxLink']['dynamic'] ) ) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_repeat_url( $attributes['IBoxLink'] ) : ( ! empty( $attributes['IBoxLink']['url'] ) ? $attributes['IBoxLink']['url'] : '' );
	}

	$vcenter = '';
	if ( ! empty( $vertical_center ) ) {
		$vcenter = 'vertical-center';
	}

	$sib = '';
	if ( 'style-1' === $style_type || 'style-2' === $style_type ) {
		if ( 'none' !== $icon_type && ! empty( $side_img_border ) ) {
			$sib = 'service-img-border';
		}
	}

	$icn_ovrlay = '';
	if ( ( 'style-1' === $style_type || 'style-2' === $style_type || 'style-3' === $style_type ) && ( ! empty( $icon_overlay ) || ! empty( $img_overlay ) ) ) {
		$icn_ovrlay = 'icon-overlay';
	}

	$icon_shine_show = '';
	if ( ! empty( $icon_shine ) ) {
		$icon_shine_show = 'icon-shine-show';
	}

	$mlr16 = '';
	if ( 'style-1' === $style_type && 'none' !== $icon_type ) {
			$mlr16 = 'm-r-16 style-1 ';
	} elseif ( 'style-2' === $style_type && 'none' !== $icon_type ) {
			$mlr16 = 'm-l-16 style-2 ';
	} elseif ( 'style-4' === $style_type && 'none' !== $icon_type ) {
			$mlr16 = 'm-r-16';
	} elseif ( 'style-5' === $style_type && 'none' !== $icon_type ) {
			$mlr16 = 'service-bg-5';
	} elseif ( 'style-6' === $style_type && 'none' !== $icon_type ) {
			$mlr16 = '';
	}

	$get_icon = '';
	if ( ! empty( $icon_type ) ) {
			$get_icon .= '<div class="info-icon-content">';
		if ( 'none' !== $icon_type && ! empty( $disp_pin_text ) ) {
			$get_icon .= '<div class="info-pin-text tpgb-trans-easeinout">' . wp_kses_post( $pin_text ) . '</div>';
		}
				$get_icon .= '<div class="service-icon-wrap tpgb-trans-linear">';
		if ( 'icon' === $icon_type ) {
			$get_icon .= '<span class="service-icon tpgb-trans-linear ' . esc_attr( $icon_shine_show ) . ' icon-' . esc_attr( $iconstyle_type ) . '">';
			$get_icon .= '<i class="' . esc_attr( $icon_name ) . '"></i>';
			$get_icon .= '</span>';
		} elseif ( 'image' === $icon_type ) {
			$get_icon .= $img_src;
		} elseif ( 'svg' === $icon_type && ! empty( $svg_icon ) && ! empty( $svg_icon['url'] ) ) {
			$get_icon     .= '<div class="tpgb-draw-svg tpgb-trans-linear" data-id="service-svg-' . esc_attr( $block_id ) . '" data-type="' . esc_attr( $svg_draw ) . '" data-duration="' . esc_attr( $svg_dura ) . '" data-stroke="' . esc_attr( $svgstro_color ) . '" data-fillColor="' . esc_attr( $svgfill_color ) . '" data-strokeColorHover="' . esc_attr( $svgstro_hover_color ) . '" data-fillColorHover="' . esc_attr( $svgfill_hover_color ) . '" data-fillEnable="yes">';
				$get_icon .= '<object class="info-box-svg" id="service-svg-' . esc_attr( $block_id ) . '" type="image/svg+xml" data="' . esc_url( $svg_icon['url'] ) . '" aria-label="' . esc_attr__( 'icon', 'the-plus-addons-for-block-editor' ) . '"></object>';
			$get_icon     .= '</div>';
		} elseif ( 'text' === $icon_type && ! empty( $text_icon ) ) {
			$get_icon .= '<span class="tpgb-icon-wrap-text">' . esc_attr( $text_icon ) . '</span>';
		}
				$get_icon .= '</div>';
			$get_icon     .= '</div>';
	}

	$get_title = '';
	if ( ! empty( $title ) ) {
		if ( ! $i_box_link_tgl && ! empty( $i_box_link ) ) {
			$link_attr  = Tp_Blocks_Helper::add_link_attributes( $attributes['IBoxLink'] );
			$get_title .= '<a href="' . esc_url( $i_box_link ) . '" class="service-title tpgb-trans-linear" ' . $target . ' ' . $nofollow . ' ' . $link_attr . '>' . wp_kses_post( $title ) . '</a>';
		} else {
			$get_title     .= '<' . Tp_Blocks_Helper::validate_html_tag( $title_type ) . ' class="service-title tpgb-trans-linear">';
				$get_title .= wp_kses_post( $title );
			$get_title     .= '</' . Tp_Blocks_Helper::validate_html_tag( $title_type ) . '>';
		}
	}

	$get_desc      = '';
	$get_desc     .= '<' . Tp_Blocks_Helper::validate_html_tag( $desc_type ) . ' class="service-desc tpgb-trans-linear">';
		$get_desc .= wp_kses_post( $description );
	$get_desc     .= '</' . Tp_Blocks_Helper::validate_html_tag( $desc_type ) . '>';

	$get_border  = '';
	$get_border .= '<div class="service-border"></div>';

	$getbutton  = '';
	$getbutton .= Tpgb_Blocks_Global_Options::load_plusButton_saves( $attributes );

	$cnt_hvr_class = $contenthover_effect;

	if ( 'bounce_in' === $contenthover_effect ) {
		$cnt_hvr_class = 'bounce-in';
	}
	if ( 'radial' === $contenthover_effect ) {
		$cnt_hvr_class = 'shadow_radial';
	}

	$get_info_box  = '';
	$get_info_box .= '<div class="info-box-inner tpgb-trans-linear tpgb_cnt_hvr_effect tpgb-relative-block tp-info-nc cnt_hvr_' . esc_attr( $cnt_hvr_class ) . '">';
	if ( ! empty( $i_box_link_tgl ) && ! empty( $i_box_link ) ) {
		$link_attr     = Tp_Blocks_Helper::add_link_attributes( $attributes['IBoxLink'] );
		$get_info_box .= '<a href="' . esc_url( $i_box_link ) . '" class="info-box-bg-box tpgb-trans-linear ' . esc_attr( $icn_ovrlay ) . '" ' . $target . ' ' . $nofollow . ' ' . $link_attr . '>';
	} else {
		$get_info_box .= '<div class="info-box-bg-box tpgb-trans-linear ' . esc_attr( $icn_ovrlay ) . '">';
	}
	if ( 'style-1' === $style_type ) {
		$get_info_box .= '<div class="service-media text-left ' . esc_attr( $vcenter ) . '">';
		if ( 'none' !== $icon_type ) {
						$get_info_box     .= '<div class="' . esc_attr( $mlr16 ) . ' ' . esc_attr( $sib ) . '">';
							$get_info_box .= $get_icon;
						$get_info_box     .= '</div>';

		}
			$get_info_box     .= '<div class="service-content">';
				$get_info_box .= $get_title;
		if ( ! empty( $display_border ) ) {
							$get_info_box .= $get_border;
		}
										$get_info_box .= $get_desc;
		if ( ! empty( $ext_btnshow ) ) {
			$get_info_box .= '<div class="infobox-btn-block ">' . $getbutton . '</div>';
		}
										$get_info_box .= '</div>';
										$get_info_box .= '</div>';
	}
	if ( 'style-2' === $style_type ) {
		$get_info_box         .= '<div class="service-media text-right ' . esc_attr( $vcenter ) . '">';
			$get_info_box     .= '<div class="service-content">';
				$get_info_box .= $get_title;
		if ( ! empty( $display_border ) ) {
				$get_info_box .= $get_border;
		}
				$get_info_box .= $get_desc;
		if ( ! empty( $ext_btnshow ) ) {
							$get_info_box .= '<div class="infobox-btn-block ">' . $getbutton . '</div>';
		}
										$get_info_box .= '</div>';
		if ( 'none' !== $icon_type ) {
			$get_info_box     .= '<div class="' . esc_attr( $mlr16 ) . ' ' . esc_attr( $sib ) . '">';
				$get_info_box .= $get_icon;
			$get_info_box     .= '</div>';
		}
										$get_info_box .= '</div>';
	}
	if ( 'style-3' === $style_type ) {
		$get_info_box     .= '<div class="text-alignment">';
			$get_info_box .= '<div class="style-3">';
		if ( 'none' !== $icon_type ) {
					$get_info_box .= $get_icon;
		}
				$get_info_box .= $get_title;
		if ( ! empty( $display_border ) ) {
							$get_info_box .= $get_border;
		}
										$get_info_box .= $get_desc;
		if ( ! empty( $ext_btnshow ) ) {
			$get_info_box .= '<div class="infobox-btn-block ">' . $getbutton . '</div>';
		}
										$get_info_box .= '</div>';
										$get_info_box .= '</div>';
	}
	if ( 'style-4' === $style_type ) {
		$get_info_box .= '<div class="service-media text-left ' . esc_attr( $vcenter ) . '">';
		if ( 'none' !== $icon_type ) {
						$get_info_box     .= '<div class="' . esc_attr( $mlr16 ) . ' ' . esc_attr( $sib ) . '">';
							$get_info_box .= $get_icon;
						$get_info_box     .= '</div>';
		}
			$get_info_box             .= '<div class="service-content">' . $get_title . '</div>';
						$get_info_box .= '</div>';
		if ( ! empty( $display_border ) ) {
							$get_info_box .= $get_border;
		}
										$get_info_box .= $get_desc;
		if ( ! empty( $ext_btnshow ) ) {
			$get_info_box .= '<div class="infobox-btn-block ">' . $getbutton . '</div>';
		}
	}
	if ( 'style-5' === $style_type ) {
		$get_info_box .= '<div class="service-media  text-left">';
		if ( 'none' !== $icon_type ) {
						$get_info_box     .= '<div class="' . esc_attr( $mlr16 ) . ' ' . esc_attr( $sib ) . '">';
							$get_info_box .= $get_icon;
						$get_info_box     .= '</div>';
		}
			$get_info_box     .= '<div class="style-5-service-content">';
				$get_info_box .= $get_title;
		if ( ! empty( $display_border ) ) {
							$get_info_box .= $get_border;
		}
										$get_info_box .= $get_desc;
		if ( ! empty( $ext_btnshow ) ) {
			$get_info_box .= '<div class="infobox-btn-block ">' . $getbutton . '</div>';
		}
										$get_info_box .= '</div>';
										$get_info_box .= '</div>';
	}
	if ( 'style-6' === $style_type ) {
		$get_info_box                 .= '<div class="style-6 text-center">';
			$get_info_box             .= '<div class="info-box-all">';
				$get_info_box         .= '<div class="info-box-wrapper">';
					$get_info_box     .= '<div class="info-box-content">';
						$get_info_box .= '<div class="info-box-icon-img">';
		if ( 'none' !== $icon_type ) {
			$get_info_box     .= '<div class="' . esc_attr( $mlr16 ) . ' ' . esc_attr( $sib ) . '">';
				$get_info_box .= $get_icon;
			$get_info_box     .= '</div>';
		}
						$get_info_box .= '</div>';
						$get_info_box .= $get_title;
						$get_info_box .= '<div class="info-box-title-hide">' . wp_kses_post( $title ) . '</div>';
		if ( ! empty( $display_border ) ) {
						$get_info_box .= $get_border;
		}
							$get_info_box .= $get_desc;
		if ( ! empty( $ext_btnshow ) ) {
							$get_info_box .= '<div class="infobox-btn-block ">' . $getbutton . '</div>';
		}
														$get_info_box .= '</div>';
														$get_info_box .= '</div>';
														$get_info_box .= '</div>';
														$get_info_box .= '</div>';
	}

	if ( ! empty( $i_box_link_tgl ) && ! empty( $i_box_link ) ) {
		$get_info_box .= '</a>';
	} else {
		$get_info_box .= '</div>';
	}

				$get_info_box .= '<div class="infobox-overlay-color tpgb-trans-linear"></div>';

			$get_info_box .= '</div>';

	$output .= '<div class="tpgb-infobox tpgb-relative-block tpgb-trans-linear tpgb-block-' . esc_attr( $block_id ) . ' ' . esc_attr( $sliderclass ) . ' info-box-' . esc_attr( $style_type ) . ' ' . esc_attr( $block_class ) . '" ' . $carousel_settings . '>';
	if ( 'carousel' === $layout_type ) {
		if ( ( isset( $show_arrows['md'] ) && ! empty( $show_arrows['md'] ) ) || ( isset( $show_arrows['sm'] ) && ! empty( $show_arrows['sm'] ) ) || ( isset( $show_arrows['xs'] ) && ! empty( $show_arrows['xs'] ) ) ) {
			$output .= Tp_Blocks_Helper::tpgb_carousel_arrow( $arrows_style, $arrows_position );
		}
		$output         .= '<div class="post-loop-inner splide__track">';
			$output     .= '<div class="splide__list">';
				$output .= tpgb_getCInfobox( $attributes );
			$output     .= '</div>';
		$output         .= '</div>';
	} else {
		$output     .= '<div class="post-inner-loop ">';
			$output .= $get_info_box;
		$output     .= '</div>';
	}
	$output .= '</div>';
	$output  = Tpgb_Blocks_Global_Options::block_Wrap_Render( $attributes, $output );
	if ( 'carousel' === $layout_type && ! empty( $arrow_css ) ) {
		$output .= $arrow_css;
	}
	return $output;
}

/**
 * Tpgb get cinfobox.
 *
 * @param mixed $attributes The attributes.
 */
function tpgb_getCInfobox( $attributes ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid,WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
	$style_type            = ( ! empty( $attributes['styleType'] ) ) ? $attributes['styleType'] : 'style-1';
	$iboxcarousel          = ( ! empty( $attributes['iboxcarousel'] ) ) ? $attributes['iboxcarousel'] : array();
	$carousel_btn          = ( ! empty( $attributes['carouselBtn'] ) ) ? $attributes['carouselBtn'] : false;
	$car_btn_style         = ( ! empty( $attributes['carBtnStyle'] ) ) ? $attributes['carBtnStyle'] : 'style-7';
	$car_btn_icon_type     = ( ! empty( $attributes['carBtnIconType'] ) ) ? $attributes['carBtnIconType'] : 'none';
	$car_btn_icon_name     = ( ! empty( $attributes['carBtnIconName'] ) ) ? $attributes['carBtnIconName'] : '';
	$car_btn_icon_position = ( ! empty( $attributes['carBtnIconPosition'] ) ) ? $attributes['carBtnIconPosition'] : 'after';

	$vertical_center = ( ! empty( $attributes['verticalCenter'] ) ) ? $attributes['verticalCenter'] : false;
	$side_img_border = ( ! empty( $attributes['sideImgBorder'] ) ) ? $attributes['sideImgBorder'] : false;
	$display_border  = ( ! empty( $attributes['displayBorder'] ) ) ? $attributes['displayBorder'] : false;

	$icon_overlay = ( ! empty( $attributes['iconOverlay'] ) ) ? $attributes['iconOverlay'] : false;
	$img_overlay  = ( ! empty( $attributes['imgOverlay'] ) ) ? $attributes['imgOverlay'] : false;
	$icon_shine   = ( ! empty( $attributes['iconShine'] ) ) ? $attributes['iconShine'] : false;

	$svg_draw      = ( ! empty( $attributes['svgDraw'] ) ) ? $attributes['svgDraw'] : 'delayed';
	$svgstro_color = ( ! empty( $attributes['svgstroColor'] ) ) ? $attributes['svgstroColor'] : '';
	$svgfill_color = ( ! empty( $attributes['svgfillColor'] ) ) ? $attributes['svgfillColor'] : 'none';
	$svg_dura      = ( ! empty( $attributes['svgDura'] ) ) ? $attributes['svgDura'] : 90;

	$title_type = ( ! empty( $attributes['titleType'] ) ) ? $attributes['titleType'] : 'div';
	$desc_type  = ( ! empty( $attributes['descType'] ) ) ? $attributes['descType'] : 'div';

	$iconstyle_type      = ( ! empty( $attributes['iconstyleType'] ) ) ? $attributes['iconstyleType'] : 'none';
	$contenthover_effect = ( ! empty( $attributes['contenthoverEffect'] ) ) ? $attributes['contenthoverEffect'] : '';

	$vcenter = '';
	if ( ! empty( $vertical_center ) ) {
		$vcenter = 'vertical-center';
	}

	$icn_ovrlay = '';
	if ( ( 'style-1' === $style_type || 'style-2' === $style_type || 'style-3' === $style_type ) && ( ! empty( $icon_overlay ) || ! empty( $img_overlay ) ) ) {
		$icn_ovrlay = 'icon-overlay';
	}

	$icon_shine_show = '';
	if ( ! empty( $icon_shine ) ) {
		$icon_shine_show = 'icon-shine-show';
	}

	$cnt_hvr_class = $contenthover_effect;

	if ( 'bounce_in' === $contenthover_effect ) {
		$cnt_hvr_class = 'bounce-in';
	}
	if ( 'radial' === $contenthover_effect ) {
		$cnt_hvr_class = 'shadow_radial';
	}
	$count = 0;

	$get_c_infobox = '';
	if ( ! empty( $iboxcarousel ) ) {
		foreach ( $iboxcarousel as $index => $item ) :

			++$count;

			$mlr16 = '';
			if ( 'style-1' === $style_type && 'none' !== $item['iconType'] ) {
				$mlr16 = 'm-r-16 style-1 ';
			} elseif ( 'style-2' === $style_type && 'none' !== $item['iconType'] ) {
				$mlr16 = 'm-l-16 style-2 ';
			} elseif ( 'style-4' === $style_type && 'none' !== $item['iconType'] ) {
				$mlr16 = 'm-r-16';
			} elseif ( 'style-5' === $style_type && 'none' !== $item['iconType'] ) {
				$mlr16 = 'service-bg-5';
			} elseif ( 'style-6' === $style_type && 'none' !== $item['iconType'] ) {
				$mlr16 = '';
			}

			$sib = '';
			if ( 'style-1' === $style_type || 'style-2' === $style_type ) {
				if ( 'none' !== $item['iconType'] && ! empty( $side_img_border ) ) {
					$sib = 'service-img-border';
				}
			}

			$get_c_title = '';
			$gtt_title   = ( ! empty( $item['Title'] ) ) ? $item['Title'] : '';
			if ( ! empty( $item['Title'] ) ) {
				$get_c_title     .= '<' . Tp_Blocks_Helper::validate_html_tag( $title_type ) . ' class="service-title tpgb-trans-linear">';
					$get_c_title .= wp_kses_post( $item['Title'] );
				$get_c_title     .= '</' . Tp_Blocks_Helper::validate_html_tag( $title_type ) . '>';
			}

			$get_c_desc = '';
			if ( ! empty( $item['Description'] ) ) {
				$get_c_desc     .= '<' . Tp_Blocks_Helper::validate_html_tag( $desc_type ) . ' class="service-desc tpgb-trans-linear">';
					$get_c_desc .= wp_kses_post( $item['Description'] );
				$get_c_desc     .= '</' . Tp_Blocks_Helper::validate_html_tag( $desc_type ) . '>';
			}

			$get_c_border  = '';
			$get_c_border .= '<div class="service-border"></div>';

			$img_c_src  = '';
			$image_name = ( ! empty( $item['imageName']['url'] ) ) ? $item['imageName'] : '';
			$image_size = ( ! empty( $item['imageSize'] ) ) ? $item['imageSize'] : 'full';
			$alt_text   = ( isset( $image_name['alt'] ) && ! empty( $image_name['alt'] ) ) ? esc_attr( $image_name['alt'] ) : ( ( ! empty( $image_name['title'] ) ) ? esc_attr( $image_name['title'] ) : esc_attr__( 'Info Box', 'the-plus-addons-for-block-editor' ) );
			if ( ! empty( $image_name ) && ! empty( $image_name['id'] ) ) {
				$img_c_src = wp_get_attachment_image(
					$image_name['id'],
					$image_size,
					false,
					array(
						'class' => 'service-icon tpgb-trans-linear',
						'alt'   => $alt_text,
					)
				);
			} elseif ( ! empty( $image_name['url'] ) ) {
				$img_c_src = '<img src="' . esc_url( $image_name['url'] ) . '" class="service-icon tpgb-trans-linear" alt="' . $alt_text . '"/>';
			}
			$get_c_icon = '';
			if ( ! empty( $item['iconType'] ) ) {
				$get_c_icon .= '<div class="info-icon-content">';
				if ( 'none' !== $item['iconType'] && ! empty( $item['dispPinText'] ) ) {
					$get_c_icon .= '<div class="info-pin-text tpgb-trans-easeinout">' . wp_kses_post( $item['pinText'] ) . '</div>';
				}
					$get_c_icon .= '<div class="service-icon-wrap tpgb-trans-linear">';
				if ( 'icon' === $item['iconType'] ) {
					$get_c_icon .= '<span class="service-icon tpgb-trans-linear ' . esc_attr( $icon_shine_show ) . ' icon-' . esc_attr( $iconstyle_type ) . '">';
					$get_c_icon .= '<i class="' . esc_attr( $item['IconName'] ) . '"></i>';
					$get_c_icon .= '</span>';
				} elseif ( 'image' === $item['iconType'] ) {
					$get_c_icon .= $img_c_src;
				} elseif ( 'svg' === $item['iconType'] && ! empty( $item['svgIcon'] ) && ! empty( $item['svgIcon']['url'] ) ) {
					$get_c_icon     .= '<div class="tpgb-draw-svg tpgb-trans-linear" data-id="service-svg-' . esc_attr( $item['_key'] ) . '" data-type="' . esc_attr( $svg_draw ) . '" data-duration="' . esc_attr( $svg_dura ) . '" data-stroke="' . esc_attr( $svgstro_color ) . '" data-fillColor="' . esc_attr( $svgfill_color ) . '" data-strokeColorHover="' . esc_attr( $svgstro_hover_color ) . '" data-fillColorHover="' . esc_attr( $svgfill_hover_color ) . '" data-fillEnable="yes">';
						$get_c_icon .= '<object id="service-svg-' . esc_attr( $item['_key'] ) . '" class="info-box-svg" type="image/svg+xml" data="' . esc_url( $item['svgIcon']['url'] ) . '" aria-label="' . esc_attr__( 'icon', 'the-plus-addons-for-block-editor' ) . '"></object>';
					$get_c_icon     .= '</div>';
				} elseif ( 'text' === $item['iconType'] && ! empty( $item['textIcon'] ) ) {
					$get_c_icon .= '<span class="tpgb-icon-wrap-text">' . esc_attr( $item['textIcon'] ) . '</span>';
				}
					$get_c_icon .= '</div>';
				$get_c_icon     .= '</div>';
			}

			$get_cbutton = '';
			if ( ! empty( $carousel_btn ) ) {
				$btn_attr = Tp_Blocks_Helper::add_link_attributes( $item['btnUrl'] );
				$btn_text = ( ! empty( $item['btnText'] ) ) ? $item['btnText'] : '';

				$btn_url  = ( ! empty( $item['btnUrl'] ) ) ? $item['btnUrl'] : '';
				$target   = ( ! empty( $btn_url['target'] ) ) ? ' target="_blank" ' : '';
				$nofollow = ( ! empty( $btn_url['nofollow'] ) ) ? ' rel="nofollow" ' : '';

				$get_btn_text = '<div class="btn-text">' . wp_kses_post( $btn_text ) . '</div>';

				$get_cbutton     .= '<div class="tpgb-adv-button button-' . esc_attr( $car_btn_style ) . '">';
					$get_cbutton .= '<a href="' . esc_url( $btn_url['url'] ) . '" class="button-link-wrap" role="button" ' . $target . ' ' . $nofollow . ' ' . $btn_attr . '>';
				if ( 'style-8' === $car_btn_style ) {
					if ( 'before' === $car_btn_icon_position ) {
						if ( 'icon' === $car_btn_icon_type ) {
							$get_cbutton     .= '<span class="btn-icon  button-' . esc_attr( $car_btn_icon_position ) . '">';
								$get_cbutton .= '<i class="' . esc_attr( $car_btn_icon_name ) . '"></i>';
							$get_cbutton     .= '</span>';
						}
						$get_cbutton .= $get_btn_text;
					}
					if ( 'after' === $car_btn_icon_position ) {
						$get_cbutton .= $get_btn_text;
						if ( 'icon' === $car_btn_icon_type ) {
							$get_cbutton     .= '<span class="btn-icon  button-' . esc_attr( $car_btn_icon_position ) . '">';
								$get_cbutton .= '<i class="' . esc_attr( $car_btn_icon_name ) . '"></i>';
							$get_cbutton     .= '</span>';
						}
					}
				}
				if ( 'style-7' === $car_btn_style || 'style-9' === $car_btn_style ) {
					$get_cbutton .= $get_btn_text;

					$get_cbutton .= '<span class="button-arrow">';
					if ( 'style-7' === $car_btn_style ) {
						$get_cbutton .= '<span class="btn-right-arrow"><i class="fas fa-chevron-right"></i></span>';
					}
					if ( 'style-9' === $car_btn_style ) {
						$get_cbutton .= '<i class="btn-show fas fa-chevron-right"></i>';
						$get_cbutton .= '<i class="btn-hide fas fa-chevron-right"></i>';
					}
					$get_cbutton .= '</span>';
				}
					$get_cbutton .= '</a>';
				$get_cbutton     .= '</div>';
			}

			$get_c_infobox .= '<div class="splide__slide info-box-inner tpgb-trans-linear tpgb_cnt_hvr_effect tpgb-relative-block tp-info-nc cnt_hvr_' . esc_attr( $cnt_hvr_class ) . ' tp-repeater-item-' . esc_attr( $item['_key'] ) . '" data-index="' . esc_attr( $count ) . '">';

				$get_c_infobox .= '<div class="info-box-bg-box tpgb-trans-linear ' . esc_attr( $icn_ovrlay ) . '">';
			if ( 'style-1' === $style_type ) {
				$get_c_infobox .= '<div class="service-media text-left ' . esc_attr( $vcenter ) . '">';
				if ( 'none' !== $item['iconType'] ) {
						$get_c_infobox     .= '<div class="' . esc_attr( $mlr16 ) . ' ' . esc_attr( $sib ) . '">';
							$get_c_infobox .= $get_c_icon;
						$get_c_infobox     .= '</div>';

				}
					$get_c_infobox     .= '<div class="service-content">';
						$get_c_infobox .= $get_c_title;
				if ( ! empty( $display_border ) ) {
						$get_c_infobox .= $get_c_border;
				}
								$get_c_infobox .= $get_c_desc;
				if ( ! empty( $carousel_btn ) ) {
							$get_c_infobox .= '<div class="infobox-btn-block ">' . $get_cbutton . '</div>';
				}
										$get_c_infobox .= '</div>';
										$get_c_infobox .= '</div>';
			}
			if ( 'style-2' === $style_type ) {
				$get_c_infobox         .= '<div class="service-media text-right ' . esc_attr( $vcenter ) . '">';
					$get_c_infobox     .= '<div class="service-content">';
						$get_c_infobox .= $get_c_title;
				if ( ! empty( $display_border ) ) {
					$get_c_infobox .= $get_c_border;
				}
						$get_c_infobox .= $get_c_desc;
				if ( ! empty( $carousel_btn ) ) {
						$get_c_infobox .= '<div class="infobox-btn-block ">' . $get_cbutton . '</div>';
				}
								$get_c_infobox .= '</div>';
				if ( 'none' !== $item['iconType'] ) {
					$get_c_infobox .= '<div class="' . esc_attr( $mlr16 ) . ' ' . esc_attr( $sib ) . '">';
					$get_c_infobox .= $get_c_icon;
					$get_c_infobox .= '</div>';
				}
								$get_c_infobox .= '</div>';
			}
			if ( 'style-3' === $style_type ) {
				$get_c_infobox     .= '<div class="text-alignment">';
					$get_c_infobox .= '<div class="style-3">';
				if ( 'none' !== $item['iconType'] ) {
					$get_c_infobox .= $get_c_icon;
				}
						$get_c_infobox .= $get_c_title;
				if ( ! empty( $display_border ) ) {
					$get_c_infobox .= $get_c_border;
				}
						$get_c_infobox .= $get_c_desc;
				if ( ! empty( $carousel_btn ) ) {
					$get_c_infobox .= '<div class="infobox-btn-block ">' . $get_cbutton . '</div>';
				}
					$get_c_infobox     .= '</div>';
						$get_c_infobox .= '</div>';
			}
			if ( 'style-4' === $style_type ) {
				$get_c_infobox .= '<div class="service-media text-left ' . esc_attr( $vcenter ) . '">';
				if ( 'none' !== $item['iconType'] ) {
						$get_c_infobox     .= '<div class="' . esc_attr( $mlr16 ) . ' ' . esc_attr( $sib ) . '">';
							$get_c_infobox .= $get_c_icon;
						$get_c_infobox     .= '</div>';
				}
					$get_c_infobox     .= '<div class="service-content">' . $get_c_title . '</div>';
						$get_c_infobox .= '</div>';
				if ( ! empty( $display_border ) ) {
							$get_c_infobox .= $get_c_border;
				}
								$get_c_infobox .= $get_c_desc;
				if ( ! empty( $carousel_btn ) ) {
					$get_c_infobox .= '<div class="infobox-btn-block ">' . $get_cbutton . '</div>';
				}
			}
			if ( 'style-5' === $style_type ) {
				$get_c_infobox .= '<div class="service-media  text-left">';
				if ( 'none' !== $item['iconType'] ) {
						$get_c_infobox     .= '<div class="' . esc_attr( $mlr16 ) . ' ' . esc_attr( $sib ) . '">';
							$get_c_infobox .= $get_c_icon;
						$get_c_infobox     .= '</div>';
				}
					$get_c_infobox     .= '<div class="style-5-service-content">';
						$get_c_infobox .= $get_c_title;
				if ( ! empty( $display_border ) ) {
					$get_c_infobox .= $get_c_border;
				}
						$get_c_infobox .= $get_c_desc;
				if ( ! empty( $carousel_btn ) ) {
					$get_c_infobox .= '<div class="infobox-btn-block ">' . $get_cbutton . '</div>';
				}
					$get_c_infobox     .= '</div>';
						$get_c_infobox .= '</div>';
			}
			if ( 'style-6' === $style_type ) {
				$get_c_infobox                 .= '<div class="style-6 text-center">';
					$get_c_infobox             .= '<div class="info-box-all">';
						$get_c_infobox         .= '<div class="info-box-wrapper">';
							$get_c_infobox     .= '<div class="info-box-content">';
								$get_c_infobox .= '<div class="info-box-icon-img">';
				if ( 'none' !== $item['iconType'] ) {
					$get_c_infobox .= '<div class="' . esc_attr( $mlr16 ) . ' ' . esc_attr( $sib ) . '">';
					$get_c_infobox .= $get_c_icon;
					$get_c_infobox .= '</div>';
				}
								$get_c_infobox .= '</div>';
								$get_c_infobox .= $get_c_title;
								$get_c_infobox .= '<div class="info-box-title-hide">' . wp_kses_post( $gtt_title ) . '</div>';
				if ( ! empty( $display_border ) ) {
					$get_c_infobox .= $get_c_border;
				}
									$get_c_infobox .= $get_c_desc;
				if ( ! empty( $carousel_btn ) ) {
						$get_c_infobox .= '<div class="infobox-btn-block ">' . $get_cbutton . '</div>';
				}
										$get_c_infobox .= '</div>';
										$get_c_infobox .= '</div>';
										$get_c_infobox .= '</div>';
										$get_c_infobox .= '</div>';
			}

				$get_c_infobox .= '</div>';

				$get_c_infobox .= '<div class="infobox-overlay-color tpgb-trans-linear"></div>';

			$get_c_infobox .= '</div>';

		endforeach;
	}

	return $get_c_infobox;
}

/**
 * Render for the server-side
 */
function tpgb_tp_infobox() {
	$block_data = Tpgb_Blocks_Global_Options::merge_options_json( __DIR__, 'tpgb_tp_infobox_render_callback', true, true, true );
	register_block_type( $block_data['name'], $block_data );
}
add_action( 'init', 'tpgb_tp_infobox' );
