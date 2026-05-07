<?php
/**
 * Progress Bar.
 *
 * @package ThePluginAddonsForBlockEditor
 */

defined( 'ABSPATH' ) || exit;

/**
 * Tpgb tp progress bar render callback.
 *
 * @param mixed $attributes The attributes.
 * @param mixed $content The content.
 */
function tpgb_tp_progress_bar_render_callback( $attributes, $content ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
	$output            = '';
	$block_id          = ( ! empty( $attributes['block_id'] ) ) ? $attributes['block_id'] : uniqid( 'title' );
	$layout_type       = ( ! empty( $attributes['layoutType'] ) ) ? $attributes['layoutType'] : 'progressbar';
	$style_type        = ( ! empty( $attributes['styleType'] ) ) ? $attributes['styleType'] : 'style-1';
	$pie_style_type    = ( ! empty( $attributes['pieStyleType'] ) ) ? $attributes['pieStyleType'] : 'pieStyle-1';
	$circle_style      = ( ! empty( $attributes['circleStyle'] ) ) ? $attributes['circleStyle'] : 'style-1';
	$height_type       = ( ! empty( $attributes['heightType'] ) ) ? $attributes['heightType'] : 'small-height';
	$icon_type         = ( ! empty( $attributes['iconType'] ) ) ? $attributes['iconType'] : 'iconIcon';
	$icon_library      = ( ! empty( $attributes['iconLibrary'] ) ) ? $attributes['iconLibrary'] : 'fontawesome';
	$title             = ( ! empty( $attributes['Title'] ) ) ? $attributes['Title'] : '';
	$sub_title         = ( ! empty( $attributes['subTitle'] ) ) ? $attributes['subTitle'] : '';
	$icon_name         = ( ! empty( $attributes['IconName'] ) ) ? $attributes['IconName'] : '';
	$image_name        = ( ! empty( $attributes['imageName']['url'] ) ) ? $attributes['imageName'] : '';
	$image_size        = ( ! empty( $attributes['imageSize'] ) ) ? $attributes['imageSize'] : 'thumbnail';
	$prepost_symbol    = ( ! empty( $attributes['prepostSymbol'] ) ) ? $attributes['prepostSymbol'] : '';
	$s_position        = ( ! empty( $attributes['sPosition'] ) ) ? $attributes['sPosition'] : 'afterNumber';
	$dynamic_value     = ( ! empty( $attributes['dynamicValue'] ) ) ? $attributes['dynamicValue'] : '';
	$dynamic_pie_value = ( ! empty( $attributes['dynamicPieValue'] ) ) ? $attributes['dynamicPieValue'] : '';
	$disp_number       = ( ! empty( $attributes['dispNumber'] ) ) ? $attributes['dispNumber'] : false;
	$img_position      = ( ! empty( $attributes['imgPosition'] ) ) ? $attributes['imgPosition'] : 'beforeTitle';
	$empty_color       = ( ! empty( $attributes['emptyColor'] ) ) ? $attributes['emptyColor'] : 'transparent';
	$pie_circle_size   = ( ! empty( $attributes['pieCircleSize'] ) ) ? $attributes['pieCircleSize'] : '200';
	$pie_thickness     = ( ! empty( $attributes['pieThickness'] ) ) ? $attributes['pieThickness'] : '5';
	$pie_fill_color    = ( ! empty( $attributes['pieFillColor'] ) ) ? $attributes['pieFillColor'] : 'normal';
	$pie_color1        = ( ! empty( $attributes['pieColor1'] ) ) ? $attributes['pieColor1'] : '#FFA500';
	$pie_color2        = ( ! empty( $attributes['pieColor2'] ) ) ? $attributes['pieColor2'] : '#008000';
	$fill_reverse      = ( ! empty( $attributes['fillReverse'] ) ) ? $attributes['fillReverse'] : false;

	$block_class = Tp_Blocks_Helper::block_wrapper_classes( $attributes );
	// image size.
	$img_src  = '';
	$alt_text = ( isset( $image_name['alt'] ) && ! empty( $image_name['alt'] ) ) ? esc_attr( $image_name['alt'] ) : ( ( ! empty( $image_name['title'] ) ) ? esc_attr( $image_name['title'] ) : esc_attr__( 'Progress Bar', 'the-plus-addons-for-block-editor' ) );

	if ( ! empty( $image_name ) && ! empty( $image_name['id'] ) ) {
		$img_src = wp_get_attachment_image(
			$image_name['id'],
			$image_size,
			false,
			array(
				'class' => 'progress-bar-img',
				'alt'   => $alt_text,
			)
		);
	} elseif ( ! empty( $image_name['url'] ) ) {
		$img_url = ( isset( $image_name['dynamic'] ) && class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_repeat_url( $image_name ) : $image_name['url'];
		$img_src = '<img src="' . esc_url( $img_url ) . '" class="progress-bar-img" alt="' . $alt_text . '"/>';
	}

	$data_fill_color = '';
	if ( 'gradient' === $pie_fill_color ) {
		$data_fill_color = ' data-fill="{&quot;gradient&quot;: [&quot;' . esc_attr( $pie_color1 ) . '&quot;,&quot;' . esc_attr( $pie_color2 ) . '&quot;]}" ';
	} else {
		$data_fill_color = ' data-fill="{&quot;color&quot;: &quot;' . esc_attr( $pie_color1 ) . '&quot;}" ';
	}

	$piechart_class = '';
	$piechant_attr  = '';
	if ( 'piechart' === $layout_type ) {
		$piechart_class = 'tpgb-piechart';
		$pieval         = ( ! empty( $dynamic_pie_value ) && preg_match( '/data-tpgb-dynamic=(.*?)\}/', $dynamic_pie_value, $match ) && class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_val( $dynamic_pie_value ) : ( ! empty( $dynamic_pie_value ) ? $dynamic_pie_value : '' );
		$piechant_attr  = $data_fill_color . ' data-emptyfill="' . esc_attr( $empty_color ) . '" data-value="' . esc_attr( $pieval ) . '"  data-size="' . esc_attr( $pie_circle_size ) . '" data-thickness="' . esc_attr( $pie_thickness ) . '" data-animation-start-value="0"  data-reverse="' . esc_attr( $fill_reverse ) . '"';
	}

	$get_title = '';
	if ( ! empty( $title ) ) {
		$before_after = ( 'beforeTitle' === $img_position ) ? ' before-icon' : ' after-icon';
		$get_title   .= '<span class="progress-bar-title ' . ( 'iconNone' !== $icon_type ? $before_after : '' ) . '">' . wp_kses_post( $title ) . '</span>';
	}
	$get_icon = '';
	if ( ! empty( $icon_name ) ) {
		$get_icon .= '<span class="progres-ims">';
		if ( 'iconIcon' === $icon_type && 'fontawesome' === $icon_library ) {
			$get_icon .= '<i class="' . esc_attr( $icon_name ) . '"></i>';
		} elseif ( 'iconImage' === $icon_type ) {
			$get_icon .= $img_src;
		}
		$get_icon .= '</span>';

	}
	$get_sub_title = '';
	if ( ! empty( $sub_title ) ) {
		$get_sub_title .= '<div class="progress-bar-sub-title">' . wp_kses_post( $sub_title ) . '</div>';
	}

	$get_counter_no = '';
	$symbol_get     = '';
	$number_get     = '';
	if ( ! empty( $prepost_symbol ) ) {
		$symbol_get .= '<span class="theserivce-milestone-symbol">' . wp_kses_post( $prepost_symbol ) . '</span>';
	}
	if ( ! empty( $dynamic_value ) || ! empty( $dynamic_pie_value ) ) {
		$number = '';
		if ( 'progressbar' === $layout_type ) {
			$number = ( ! empty( $dynamic_value ) && preg_match( '/data-tpgb-dynamic=(.*?)\}/', $dynamic_value, $match ) && class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_val( $dynamic_value ) : ( ! empty( $dynamic_value ) ? (float) $dynamic_value : '' );
		} elseif ( 'piechart' === $layout_type ) {
			$number = ( ! empty( $dynamic_pie_value ) && preg_match( '/data-tpgb-dynamic=(.*?)\}/', $dynamic_pie_value, $match ) && class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_val( $dynamic_pie_value ) : ( ! empty( $dynamic_pie_value ) ? $dynamic_pie_value : '' );
			$number = (float) $number * 100;
		}
		if ( ! empty( $disp_number ) ) {
			$number_get .= '<span class="theserivce-milestone-number icon-milestone">' . wp_kses_post( $number ) . '</span>';
		}
	}

	$get_counter_no .= '<h5 class="counter-number">';
	if ( ! empty( 'afterNumber' === $s_position ) ) {
		$get_counter_no .= $number_get . $symbol_get;
	}
	if ( ! empty( 'beforeNumber' === $s_position ) ) {
		$get_counter_no .= $symbol_get . $number_get;
	}
	$get_counter_no .= '</h5>';

	$htype = '';
	$sml   = '';
	if ( 'small-height' === $height_type ) {
		$htype = 'small';
		$sml   = 'prog-title prog-icon';
	} elseif ( 'medium-height' === $height_type ) {
		$htype = 'medium';
		$sml   = 'prog-title prog-icon';
	} elseif ( 'large-height' === $height_type ) {
		$htype = 'large';
		$sml   = 'prog-title prog-icon large';
	}
	$circle_border = '';
	if ( 'style-2' === $circle_style ) {
		$circle_border = 'pie-circle-border';
	}

	$output .= '<div class="tpgb-progress-bar tpgb-relative-block tpgb-block-' . esc_attr( $block_id ) . ' ' . esc_attr( $piechart_class ) . ' ' . esc_attr( $block_class ) . '" ' . $piechant_attr . '>';
		// Progrssbar.
	if ( 'progressbar' === $layout_type ) {
		if ( 'style-1' === $style_type ) {
			if ( 'small-height' === $height_type ) {
				$output .= '<div class="progress-bar-media"><div class="' . esc_attr( $sml ) . '">';
				if ( 'beforeTitle' === $img_position ) {
					$output .= $get_icon;
					$output .= $get_title;
				} elseif ( 'afterTitle' === $img_position ) {
					$output .= $get_title;
					$output .= $get_icon;
				}
						$output .= $get_sub_title;
						$output .= '</div>' . $get_counter_no . '</div><div class="progress-bar-skill skill-fill ' . esc_attr( $htype ) . '"><div class="progress-bar-skill-bar-filled" data-width="' . esc_attr( $number ) . '%"></div></div>';
			}
			if ( 'medium-height' === $height_type ) {
				$output .= '<div class="progress-bar-media"><div class="' . esc_attr( $sml ) . '">';
				if ( 'beforeTitle' === $img_position ) {
					$output .= $get_icon;
					$output .= $get_title;
				} elseif ( 'afterTitle' === $img_position ) {
					$output .= $get_title;
					$output .= $get_icon;
				}
						$output .= $get_sub_title;
						$output .= '</div>' . $get_counter_no . '</div><div class="progress-bar-skill skill-fill ' . esc_attr( $htype ) . '"><div class="progress-bar-skill-bar-filled " data-width="' . esc_attr( $number ) . '%"></div></div>';
			}
			if ( 'large-height' === $height_type ) {
				$output .= '<div class="progress-bar-skill skill-fill ' . esc_attr( $htype ) . '"><div class="progress-bar-skill-bar-filled " data-width="' . esc_attr( $number ) . '%"></div><div class="progress-bar-media large" data-width="' . esc_attr( $number ) . '%"><div class="' . esc_attr( $sml ) . '">';
				if ( 'beforeTitle' === $img_position ) {
					$output .= $get_icon;
					$output .= $get_title;
				} elseif ( 'afterTitle' === $img_position ) {
					$output .= $get_title;
					$output .= $get_icon;
				}
						$output .= '</div>' . $get_counter_no . '</div></div>';
			}
		}
		if ( 'style-2' === $style_type ) {
			$output .= '<div class="progress-bar-media"><div class="' . esc_attr( $sml ) . '">';
			if ( 'beforeTitle' === $img_position ) {
				$output .= $get_icon;
				$output .= $get_title;
			} elseif ( 'afterTitle' === $img_position ) {
				$output .= $get_title;
				$output .= $get_icon;
			}
					$output .= $get_sub_title;
					$output .= '</div>' . $get_counter_no . '</div><div class="progress-bar-skill skill-fill progress-style-2"><div class="progress-bar-skill-bar-filled " data-width="' . esc_attr( $number ) . '%"></div></div>';
		}
	}
	if ( 'piechart' === $layout_type ) {
		if ( 'pieStyle-1' === $pie_style_type ) {
			$output     .= '<div class = "tpgb-piechart tpgb-relative-block ' . esc_attr( $circle_border ) . '"><div class="tp-pie-circle"><div class="pie-numbers">' . $get_counter_no . '</div></div></div>';
			$output     .= '<div class = "tpgb-pie-chart">';
				$output .= $get_title;
				$output .= $get_sub_title;
			$output     .= '</div>';
		}
		if ( 'pieStyle-2' === $pie_style_type ) {
			$output         .= '<div class = "tpgb-piechart tpgb-relative-block ' . esc_attr( $circle_border ) . '"><div class="tp-pie-circle"><div class="pie-numbers">' . $get_counter_no . '</div></div></div>';
				$output     .= '<div class = "tpgb-pie-chart style-2"><div class = "pie-chart">' . $get_icon . '</div>';
				$output     .= '<div class = "pie-chart-style2">';
					$output .= $get_title;
					$output .= $get_sub_title;
				$output     .= '</div></div>';
		}
		if ( 'pieStyle-3' === $pie_style_type ) {
			$output         .= '<div class = "tpgb-piechart tpgb-relative-block ' . esc_attr( $circle_border ) . '"><div class="tp-pie-circle"><div class="pie-numbers">' . $get_icon . '</div></div></div>';
			$output         .= '<div class = "tpgb-pie-chart style-3"><div class = "pie-chart">' . $get_counter_no . '</div>';
				$output     .= '<div class = "pie-chart-style3">';
					$output .= $get_title;
					$output .= $get_sub_title;
			$output         .= '</div></div>';
		}
	}
	$output .= '</div>';

	$output = Tpgb_Blocks_Global_Options::block_Wrap_Render( $attributes, $output );

	return $output;
}


/**
 * Render for the server-side
 */
function tpgb_tp_progress_bar() {
	$block_data = Tpgb_Blocks_Global_Options::merge_options_json( __DIR__, 'tpgb_tp_progress_bar_render_callback' );
	register_block_type( $block_data['name'], $block_data );
}
add_action( 'init', 'tpgb_tp_progress_bar' );
