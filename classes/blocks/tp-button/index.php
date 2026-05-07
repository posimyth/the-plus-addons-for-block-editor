<?php
/**
 * TP Button.
 *
 * @package ThePluginAddonsForBlockEditor
 */

defined( 'ABSPATH' ) || exit;

/**
 * Tpgb button render callback.
 *
 * @param mixed $attributes The attributes.
 * @param mixed $content The content.
 */
function tpgb_button_render_callback( $attributes, $content ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
	$output            = '';
	$block_id          = ( ! empty( $attributes['block_id'] ) ) ? $attributes['block_id'] : uniqid( 'title' );
	$style_type        = ( ! empty( $attributes['styleType'] ) ) ? $attributes['styleType'] : 'style-1';
	$btn_hvr_type      = ( ! empty( $attributes['btnHvrType'] ) ) ? $attributes['btnHvrType'] : 'hover-left';
	$icon_hvr_type     = ( ! empty( $attributes['iconHvrType'] ) ) ? $attributes['iconHvrType'] : 'hover-top';
	$icon_position     = ( ! empty( $attributes['iconPosition'] ) ) ? $attributes['iconPosition'] : 'iconAfter';
	$icn_vrtcal        = ( ! empty( $attributes['icnVrtcal'] ) ) ? $attributes['icnVrtcal'] : 'icon-top';
	$icon_type         = ( ! empty( $attributes['iconType'] ) ) ? $attributes['iconType'] : 'fontAwesome';
	$font_awesome_icon = ( ! empty( $attributes['fontAwesomeIcon'] ) ) ? $attributes['fontAwesomeIcon'] : '';
	$image_name        = ( ! empty( $attributes['imageName']['url'] ) ) ? $attributes['imageName'] : '';
	$image_size        = ( ! empty( $attributes['imageSize'] ) ) ? $attributes['imageSize'] : 'full';
	$btn_text          = ( ! empty( $attributes['btnText'] ) ) ? $attributes['btnText'] : '';
	$btn_tag_text      = ( ! empty( $attributes['btnTagText'] ) ) ? $attributes['btnTagText'] : '';
	$hover_text        = ( ! empty( $attributes['hoverText'] ) ) ? $attributes['hoverText'] : '';
	$btn_link          = ( ! empty( $attributes['btnLink']['url'] ) ) ? $attributes['btnLink']['url'] : '';
	$target            = ( ! empty( $attributes['btnLink']['target'] ) ) ? ' target="_blank" ' : '';
	$nofollow          = ( ! empty( $attributes['btnLink']['nofollow'] ) ) ? ' rel="nofollow" ' : '';
	$link_attr         = Tp_Blocks_Helper::add_link_attributes( $attributes['btnLink'] );
	$aria_label        = ( ! empty( $attributes['ariaLabel'] ) ) ? $attributes['ariaLabel'] : '';
	$shake_animate     = ( ! empty( $attributes['shakeAnimate'] ) ) ? $attributes['shakeAnimate'] : false;
	$btn_hvr_cnt       = ( ! empty( $attributes['btnHvrCnt'] ) ) ? $attributes['btnHvrCnt'] : false;
	$select_hvr_cnt    = ( ! empty( $attributes['selectHvrCnt'] ) ) ? $attributes['selectHvrCnt'] : '';
	$fancy_box         = ( ! empty( $attributes['fancyBox'] ) ) ? $attributes['fancyBox'] : '';
	$svg_icon          = ( ! empty( $attributes['svgIcon'] ) ) ? $attributes['svgIcon'] : '';

	$head_svg_color       = ( ! empty( $attributes['headSvgColor'] ) ) ? $attributes['headSvgColor'] : '#000000';
	$head_svgfill         = ( ! empty( $attributes['headSvgfill'] ) ) ? $attributes['headSvgfill'] : '';
	$head_svg_hover_color = ( ! empty( $attributes['SvgStrHover'] ) ) ? $attributes['SvgStrHover'] : '';
	$head_svg_hover_fill  = ( ! empty( $attributes['SvgFillHover'] ) ) ? $attributes['SvgFillHover'] : '';
	$block_class          = Tp_Blocks_Helper::block_wrapper_classes( $attributes );

	if ( class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) {
		$btn_link = ( isset( $attributes['btnLink']['dynamic'] ) ) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_repeat_url( $attributes['btnLink'] ) : ( ! empty( $attributes['btnLink']['url'] ) ? $attributes['btnLink']['url'] : '' );
	}

	$i_shake_animate = '';
	if ( ! empty( $shake_animate ) ) {
		$i_shake_animate = 'shake_animate';
	}
	$icon_hover = '';
	if ( 'style-11' === $style_type || 'style-13' === $style_type ) {
		$icon_hover .= $btn_hvr_type;
	}
	if ( 'style-17' === $style_type ) {
		$icon_hover .= $icon_hvr_type;
	}
	$s23_vrtcl_cntr = '';
	if ( 'style-23' === $style_type ) {
		$s23_vrtcl_cntr .= $icn_vrtcal;
	}
	$translin = '';
	if ( 'style-10' !== $style_type && 'style-13' !== $style_type ) {
		$translin = 'tpgb-trans-linear';
	}
	$get_bfr_icon  = '';
	$get_bfr_icon .= '<span class="btn-icon ' . esc_attr( $translin ) . ' ' . ( 'style-17' !== $style_type ? ' button-before' : ' tpgb-rel-flex' ) . '">';
	$get_bfr_icon .= '<i class="' . esc_attr( $font_awesome_icon ) . '"></i>';
	$get_bfr_icon .= '</span>';

	$get_aftr_icon  = '';
	$get_aftr_icon .= '<span class="btn-icon ' . esc_attr( $translin ) . ' ' . ( 'style-17' !== $style_type ? ' button-after' : ' tpgb-rel-flex' ) . '">';
	$get_aftr_icon .= '<i class="' . esc_attr( $font_awesome_icon ) . '"></i>';
	$get_aftr_icon .= '</span>';

	$img_src   = '';
	$img_bf_af = '';
	$alt_text  = ( isset( $image_name['alt'] ) && ! empty( $image_name['alt'] ) ) ? esc_attr( $image_name['alt'] ) : ( ( ! empty( $image_name['title'] ) ) ? esc_attr( $image_name['title'] ) : esc_attr__( 'Button', 'the-plus-addons-for-block-editor' ) );

	if ( ! empty( $image_name ) && ! empty( $image_name['id'] ) ) {
		if ( 'style-17' !== $style_type ) {
			if ( 'iconBefore' === $icon_position ) {
				$img_bf_af = 'button-before';
			} elseif ( 'iconAfter' === $icon_position ) {
				$img_bf_af = 'button-after';
			}
		} else {
			$img_bf_af = 'tpgb-rel-flex';
		}
		$img_src = wp_get_attachment_image(
			$image_name['id'],
			$image_size,
			false,
			array(
				'class' => 'btn-icon ' . esc_attr( $translin ) . ' ' . $img_bf_af,
				'alt'   => $alt_text,
			)
		);
	} elseif ( ! empty( $image_name['url'] ) ) {
		$img_src = '<img src="' . esc_url( $image_name['url'] ) . '" class="btn-icon ' . esc_attr( $translin ) . ' ' . esc_attr( $img_bf_af ) . '" alt="' . $alt_text . '"/>';
	}

	$svg_src   = '';
	$svg_bf_af = '';

	$svgalt_text = ( isset( $svg_icon['alt'] ) && ! empty( $svg_icon['alt'] ) )
		? esc_attr( $svg_icon['alt'] )
		: ( ! empty( $svg_icon['title'] )
			? esc_attr( $svg_icon['title'] )
			: esc_attr__( 'Button', 'the-plus-addons-for-block-editor' )
		);

	/** Icon position class */
	if ( ! empty( $svg_icon ) ) {
		if ( 'style-17' !== $style_type ) {
			if ( 'iconBefore' === $icon_position ) {
				$svg_bf_af = 'button-before';
			} elseif ( 'iconAfter' === $icon_position ) {
				$svg_bf_af = 'button-after';
			}
		} else {
			$svg_bf_af = 'tpgb-rel-flex';
		}
	}

	/* ALWAYS render SVG using <object> */
	if ( ! empty( $svg_icon['id'] ) ) {

		$svg_url = wp_get_attachment_url( $svg_icon['id'] );

		if ( $svg_url ) {
			$svg_src = '<div class="tpgb-draw-svg btn-icon ' . esc_attr( $translin ) . ' ' . esc_attr( $svg_bf_af ) . '"
            data-id="service-svg-' . esc_attr( $block_id ) . '"
            data-type="delayed"
            data-duration="1"
            data-stroke="' . esc_attr( $head_svg_color ) . '"
            data-fillColor="' . esc_attr( $head_svgfill ) . '"
            data-strokeColorHover="' . esc_attr( $head_svg_hover_color ) . '"                              
            data-fillColorHover="' . esc_attr( $head_svg_hover_fill ) . '"
            data-fillEnable="yes">';

			$svg_src .= '<object
                id="service-svg-' . esc_attr( $block_id ) . '"
                type="image/svg+xml"
                data="' . esc_url( $svg_url ) . '"
                aria-label="' . esc_attr( $svgalt_text ) . '">
            </object>';

			$svg_src .= '</div>';

		}
	} elseif ( ! empty( $svg_icon['url'] ) ) {

		$svg_src = '<div class="tpgb-draw-svg btn-icon ' . esc_attr( $translin ) . ' ' . esc_attr( $svg_bf_af ) . '"
            data-id="service-svg-' . esc_attr( $block_id ) . '"
            data-type="delayed"
            data-duration="1"
            data-stroke="' . esc_attr( $head_svg_color ) . '"
            data-fillColor="' . esc_attr( $head_svgfill ) . '"   
            data-strokeColorHover="' . esc_attr( $head_svg_hover_color ) . '"                              
            data-fillColorHover="' . esc_attr( $head_svg_hover_fill ) . '"
            data-fillEnable="yes">';

		$svg_src .= '<object
            id="service-svg-' . esc_attr( $block_id ) . '"
            type="image/svg+xml"
            data="' . esc_url( $svg_icon['url'] ) . '"
            aria-label="' . esc_attr( $svgalt_text ) . '">
        </object>';

		$svg_src .= '</div>';

	}

	$get_button_source = '';

	if ( 'style-3' !== $style_type && 'style-6' !== $style_type && 'style-7' !== $style_type && 'style-9' !== $style_type && 'style-23' !== $style_type && 'iconBefore' === $icon_position ) {
		if ( 'fontAwesome' === $icon_type ) {
			$get_button_source .= $get_bfr_icon;
		} elseif ( 'image' === $icon_type ) {
			$get_button_source .= $img_src;
		} elseif ( 'svg' === $icon_type ) {
			$get_button_source .= $svg_src;
		}
	}
	if ( 'style-6' === $style_type ) {
		$get_button_source .= '<span class="btn-left-arrow"><i class="fas fa-chevron-right"></i></span>';
	}
	if ( 'style-17' === $style_type ) {
		$get_button_source .= '<span class="tpgb-rel-flex">' . wp_kses_post( $btn_text ) . '</span>';
	}
	if ( 'style-17' !== $style_type && 'style-23' !== $style_type ) {
		$get_button_source .= wp_kses_post( $btn_text );
	}
	if ( 'style-23' === $style_type ) {
		if ( 'icon-top' === $icn_vrtcal ) {
			$get_button_source .= '<span class="button-tag-hint">';
			if ( 'iconBefore' === $icon_position ) {
				if ( 'fontAwesome' === $icon_type ) {
					$get_button_source .= $get_bfr_icon;
				} elseif ( 'image' === $icon_type ) {
					$get_button_source .= $img_src;
				} elseif ( 'svg' === $icon_type ) {
					$get_button_source .= $svg_src;
				}
			}
					$get_button_source .= wp_kses_post( $btn_tag_text );
			if ( 'iconAfter' === $icon_position ) {
				if ( 'fontAwesome' === $icon_type ) {
					$get_button_source .= $get_aftr_icon;
				} elseif ( 'image' === $icon_type ) {
					$get_button_source .= $img_src;
				} elseif ( 'svg' === $icon_type ) {
					$get_button_source .= $svg_src;
				}
			}
			$get_button_source .= '</span>';
			$get_button_source .= '<span>' . wp_kses_post( $btn_text ) . '</span>';
		}
		if ( 'icon-middle' === $icn_vrtcal ) {
			if ( 'iconBefore' === $icon_position ) {
				if ( 'fontAwesome' === $icon_type ) {
					$get_button_source .= $get_bfr_icon;
				} elseif ( 'image' === $icon_type ) {
					$get_button_source .= $img_src;
				} elseif ( 'svg' === $icon_type ) {
					$get_button_source .= $svg_src;
				}
			}
			$get_button_source     .= '<span>';
				$get_button_source .= '<span class="button-tag-hint">' . wp_kses_post( $btn_tag_text ) . '</span>';
				$get_button_source .= wp_kses_post( $btn_text );
			$get_button_source     .= '</span>';
			if ( 'iconAfter' === $icon_position ) {
				if ( 'fontAwesome' === $icon_type ) {
					$get_button_source .= $get_aftr_icon;
				} elseif ( 'image' === $icon_type ) {
					$get_button_source .= $img_src;
				} elseif ( 'svg' === $icon_type ) {
					$get_button_source .= $svg_src;
				}
			}
		}
		if ( 'icon-bottom' === $icn_vrtcal ) {
			$get_button_source     .= '<span class="button-tag-hint">';
				$get_button_source .= wp_kses_post( $btn_tag_text );
			$get_button_source     .= '</span>';
			$get_button_source     .= '<span>';
			if ( 'iconBefore' === $icon_position ) {
				if ( 'fontAwesome' === $icon_type ) {
					$get_button_source .= $get_bfr_icon;
				} elseif ( 'image' === $icon_type ) {
					$get_button_source .= $img_src;
				} elseif ( 'svg' === $icon_type ) {
					$get_button_source .= $svg_src;
				}
			}
			$get_button_source .= wp_kses_post( $btn_text );
			if ( 'iconAfter' === $icon_position ) {
				if ( 'fontAwesome' === $icon_type ) {
					$get_button_source .= $get_aftr_icon;
				} elseif ( 'image' === $icon_type ) {
					$get_button_source .= $img_src;
				} elseif ( 'svg' === $icon_type ) {
					$get_button_source .= $svg_src;
				}
			}
			$get_button_source .= '</span>';
		}
	}
	if ( 'style-3' === $style_type ) {
		$get_button_source     .= '<svg class="arrow" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid" width="48" height="9" viewBox="0 0 48 9">';
			$get_button_source .= '<path d="M48.000,4.243 L43.757,8.485 L43.757,5.000 L0.000,5.000 L0.000,4.000 L43.757,4.000 L43.757,0.000 L48.000,4.243 Z" class="cls-1"></path>';
		$get_button_source     .= '</svg>';
		$get_button_source     .= '<svg class="arrow-1" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid" width="48" height="9" viewBox="0 0 48 9">';
			$get_button_source .= '<path d="M48.000,4.243 L43.757,8.485 L43.757,5.000 L0.000,5.000 L0.000,4.000 L43.757,4.000 L43.757,0.000 L48.000,4.243 Z" class="cls-1"></path>';
		$get_button_source     .= '</svg>';
	}
	if ( 'style-3' !== $style_type && 'style-6' !== $style_type && 'style-7' !== $style_type && 'style-9' !== $style_type && 'style-23' !== $style_type && 'iconAfter' === $icon_position ) {
		if ( 'fontAwesome' === $icon_type ) {
			$get_button_source .= $get_aftr_icon;
		} elseif ( 'image' === $icon_type ) {
			$get_button_source .= $img_src;
		} elseif ( 'svg' === $icon_type ) {
			$get_button_source .= $svg_src;
		}
	}
	if ( 'style-7' === $style_type ) {
		$get_button_source .= '<span class="btn-arrow ' . esc_attr( $translin ) . '"><span class="btn-right-arrow"><i class="fas fa-chevron-right"></i></span></span>';
	}
	if ( 'style-9' === $style_type ) {
		$get_button_source     .= '<span class="btn-arrow ' . esc_attr( $translin ) . '">';
			$get_button_source .= '<i class="btn-show fa fa-chevron-right" aria-hidden="true"></i>';
			$get_button_source .= '<i class="btn-hide fa fa-chevron-right" aria-hidden="true"></i>';
		$get_button_source     .= '</span>';
	}
	if ( 'style-12' === $style_type ) {
		$get_button_source .= '<div class="button_line"></div>';
	}

	$content_hvr_class = '';
	if ( ! empty( $btn_hvr_cnt ) && ! empty( $select_hvr_cnt ) ) {
		$content_hvr_class = ' tpgb_cnt_hvr_effect cnt_hvr_' . esc_attr( $select_hvr_cnt );
	}

	$extr_attr  = '';
	$fancy_data = array();

	global $post;
	$post_id = isset( $post->ID ) ? $post->ID : 0;
	if ( ! empty( $fancy_box ) ) {
		$extr_attr .= 'data-src="#tpgb-query-' . esc_attr( $block_id ) . '-' . esc_attr( $post_id ) . '" data-touch="false" href="javascript:;" ';

		$auto_dimen = ( ! empty( $attributes['autoDimen'] ) ) ? $attributes['autoDimen'] : false;

		$fancy_data['autoDimensions'] = (int) $auto_dimen;
		$fancy_data                   = htmlspecialchars( wp_json_encode( $fancy_data ), ENT_QUOTES, 'UTF-8' );

		$extr_attr .= ' data-fancy-opt= \'' . $fancy_data . '\' ';

	} else {
		$extr_attr = 'href="' . esc_url( $btn_link ) . '" ' . $target . ' ' . $nofollow;
	}

	$aria_label_t    = ( ! empty( $aria_label ) ) ? esc_attr( $aria_label ) : ( ( ! empty( $btn_text ) ) ? esc_attr( $btn_text ) : esc_attr__( 'Button', 'the-plus-addons-for-block-editor' ) );
	$output         .= '<div class="tpgb-plus-button tpgb-relative-block tpgb-block-' . esc_attr( $block_id ) . ' button-' . esc_attr( $style_type ) . ' ' . esc_attr( $icon_hover ) . ' ' . esc_attr( $block_class ) . ' ">';
		$output     .= '<div class="animted-content-inner' . esc_attr( $content_hvr_class ) . '">';
			$output .= '<a ' . $extr_attr . ' class="button-link-wrap ' . esc_attr( $translin ) . ' ' . esc_attr( $i_shake_animate ) . ' ' . esc_attr( $s23_vrtcl_cntr ) . ' ' . ( ! empty( $fancy_box ) ? ' tpgb-fancy-popup' : '' ) . ' " role="button" aria-label="' . $aria_label_t . '" data-hover="' . wp_kses_post( $hover_text ) . '" ' . $link_attr . '>';
	if ( 'style-17' !== $style_type && 'style-23' !== $style_type ) {
		$output .= '<span>' . $get_button_source . '</span>';
	}
	if ( 'style-17' === $style_type || 'style-23' === $style_type ) {
		$output .= $get_button_source;
	}
			$output .= '</a>';
		$output     .= '</div>';

		// Load Fancy Box Content.
	if ( ! empty( $fancy_box ) ) {
		$output .= '<div class="tpgb-btn-fpopup" id="tpgb-query-' . esc_attr( $block_id ) . '-' . esc_attr( $post_id ) . '" >';
			ob_start();
		if ( ! empty( $attributes['templates'] ) && 'none' !== $attributes['templates'] ) {
			echo Tpgb_Library()->plus_do_block( $attributes['templates'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
			$output .= ob_get_contents();
			ob_end_clean();
			$output .= '</div>';
	}
	$output .= '</div>';

	$output = Tpgb_Blocks_Global_Options::block_Wrap_Render( $attributes, $output );

	return $output;
}

/**
 * Render for the server-side
 */
function tpgb_tp_button() {
	$block_data = Tpgb_Blocks_Global_Options::merge_options_json( __DIR__, 'tpgb_button_render_callback' );
	register_block_type( $block_data['name'], $block_data );
}
add_action( 'init', 'tpgb_tp_button' );
