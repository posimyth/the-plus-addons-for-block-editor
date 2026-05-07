<?php
/**
 * Number Counter.
 *
 * @package ThePluginAddonsForBlockEditor
 */

defined( 'ABSPATH' ) || exit;

/**
 * Tpgb tp number counter render callback.
 *
 * @param mixed $attributes The attributes.
 * @param mixed $content The content.
 * @return mixed The result.
 */
function tpgb_tp_number_counter_render_callback( $attributes, $content ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
	$block_id        = ( ! empty( $attributes['block_id'] ) ) ? $attributes['block_id'] : uniqid( 'title' );
	$style           = ( ! empty( $attributes['style'] ) ) ? $attributes['style'] : 'style-1';
	$title           = ( ! empty( $attributes['title'] ) ) ? $attributes['title'] : '';
	$style1_align    = ( ! empty( $attributes['style1Align'] ) ) ? $attributes['style1Align'] : 'text-center';
	$style2_align    = ( ! empty( $attributes['style2Align'] ) ) ? $attributes['style2Align'] : 'text-left';
	$num_value       = ( ! empty( $attributes['numValue'] ) ) ? $attributes['numValue'] : '1000';
	$start_value     = ( ! empty( $attributes['startValue'] ) ) ? $attributes['startValue'] : '0';
	$time_delay      = ( ! empty( $attributes['timeDelay'] ) ) ? $attributes['timeDelay'] : '5';
	$numeration      = ( ! empty( $attributes['numeration'] ) ) ? $attributes['numeration'] : 'default';
	$num_gap         = ( ! empty( $attributes['numGap'] ) ) ? $attributes['numGap'] : '5';
	$symbol          = ( ! empty( $attributes['symbol'] ) ) ? $attributes['symbol'] : '';
	$symbol_pos      = ( ! empty( $attributes['symbolPos'] ) ) ? $attributes['symbolPos'] : 'after';
	$icon_type       = ( ! empty( $attributes['iconType'] ) ) ? $attributes['iconType'] : 'icon';
	$icon_style      = ( ! empty( $attributes['iconStyle'] ) ) ? $attributes['iconStyle'] : 'square';
	$icon_store      = ( ! empty( $attributes['iconStore'] ) ) ? $attributes['iconStore'] : '';
	$link_url        = ( ! empty( $attributes['linkURL']['url'] ) ) ? $attributes['linkURL']['url'] : '';
	$imagestore      = ( ! empty( $attributes['imagestore'] ) ) ? $attributes['imagestore'] : TPGB_ASSETS_URL . 'assets/images/tpgb-placeholder.jpg';
	$image_size      = ( ! empty( $attributes['imageSize'] ) ) ? $attributes['imageSize'] : 'thumbnail';
	$target          = ( ! empty( $attributes['linkURL']['target'] ) ) ? ' target="_blank" ' : '';
	$nofollow        = ( ! empty( $attributes['linkURL']['nofollow'] ) ) ? ' rel="nofollow" ' : '';
	$vertical_center = ( ! empty( $attributes['verticalCenter'] ) ) ? $attributes['verticalCenter'] : false;
	$pre_symbol      = ( ! empty( $attributes['preSymbol'] ) ) ? $attributes['preSymbol'] : '';

	$svg_icon      = ( ! empty( $attributes['svgIcon'] ) ) ? $attributes['svgIcon'] : '';
	$svg_draw      = ( ! empty( $attributes['svgDraw'] ) ) ? $attributes['svgDraw'] : 'delayed';
	$svgstro_color = ( ! empty( $attributes['svgstroColor'] ) ) ? $attributes['svgstroColor'] : '';
	$svgfill_color = ( ! empty( $attributes['svgfillColor'] ) ) ? $attributes['svgfillColor'] : 'none';
	$svgstro_hov   = ( ! empty( $attributes['svgstroHov'] ) ) ? $attributes['svgstroHov'] : '';
	$svgfill_hov   = ( ! empty( $attributes['svgfillHov'] ) ) ? $attributes['svgfillHov'] : '';
	$svg_dura      = ( ! empty( $attributes['svgDura'] ) ) ? $attributes['svgDura'] : 90;

	$block_class = Tp_Blocks_Helper::block_wrapper_classes( $attributes );

	$alt_text = ( isset( $imagestore['alt'] ) && ! empty( $imagestore['alt'] ) ) ? esc_attr( $imagestore['alt'] ) : ( ( ! empty( $imagestore['title'] ) ) ? esc_attr( $imagestore['title'] ) : esc_attr__( 'Counter Number', 'the-plus-addons-for-block-editor' ) );

	if ( ! empty( $imagestore ) && ! empty( $imagestore['id'] ) ) {
		$img_src = wp_get_attachment_image(
			$imagestore['id'],
			$image_size,
			false,
			array(
				'class' => 'counter-icon-image',
				'alt'   => $alt_text,
			)
		);
	} elseif ( ! empty( $imagestore['url'] ) ) {

		$img_url = ( isset( $imagestore['dynamic'] ) && class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_repeat_url( $imagestore ) : $imagestore['url'];
		$img_src = '<img class="counter-icon-image" src=' . esc_url( $img_url ) . ' alt="' . $alt_text . '"/>';
	} elseif ( ! is_array( $imagestore ) ) {
		$img_src = '<img class="counter-icon-image" src=' . esc_url( $imagestore ) . ' alt="' . $alt_text . '"/>';
	} else {
		$img_src = '<img class="counter-icon-image" src=' . esc_url( TPGB_ASSETS_URL . 'assets/images/tpgb-placeholder.jpg' ) . ' alt="' . $alt_text . '"/>';
	}

	$v_center = '';
	if ( ! empty( $vertical_center ) ) {
		$v_center = 'vertical-center';
	}

	$alignment = '';
	if ( 'style-1' === $style ) {
		$alignment = $style1_align;
	}
	if ( 'style-2' === $style ) {
		$alignment = $style2_align;
	}
	$tranease = 'tpgb-trans-ease';

	$get_counter_no  = '';
	$get_counter_no .= '<h5 class="nc-counter-number ' . esc_attr( $tranease ) . '">';
	if ( ( ! empty( $symbol ) && 'before' === $symbol_pos ) || ( ! empty( $pre_symbol ) && 'both' === $symbol_pos ) ) {
		$get_counter_no .= '<span class="counter-symbol-text">' . ( ( ! empty( $pre_symbol ) && 'both' === $symbol_pos ) ? wp_kses_post( $pre_symbol ) : wp_kses_post( $symbol ) ) . '</span>';
	}

		// Get Dynamic Value.
		$num_value   = ( ! empty( $num_value ) && class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_val( $num_value ) : $num_value;
		$start_value = ( ! empty( $start_value ) && class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_val( $start_value ) : $start_value;

		$get_counter_no     .= '<span class="counter-number-inner numscroller" data-min="' . esc_attr( $start_value ) . '" data-max="' . esc_attr( $num_value ) . '" data-delay="' . esc_attr( $time_delay ) . '" data-increment="' . esc_attr( $num_gap ) . '" data-numeration="' . esc_attr( $numeration ) . '">';
			$get_counter_no .= tpgb_formatNumber( $start_value, $numeration );
		$get_counter_no     .= '</span>';
	if ( ( ! empty( $symbol ) && 'after' === $symbol_pos ) || 'both' === $symbol_pos ) {
		$get_counter_no .= '<span class="counter-symbol-text">' . wp_kses_post( $symbol ) . '</span>';
	}
	$get_counter_no .= '</h5>';

	// Set Dynamic URL For Title Link.
	if ( class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) {
		$link_url = ( isset( $attributes['linkURL']['dynamic'] ) ) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_repeat_url( $attributes['linkURL'] ) : ( ! empty( $attributes['linkURL']['url'] ) ? $attributes['linkURL']['url'] : '' );
	}

	$get_title  = '';
	$link_attr  = Tp_Blocks_Helper::add_link_attributes( $attributes['linkURL'] );
	$aria_label = ( ! empty( $attributes['ariaLabel'] ) ) ? esc_attr( $attributes['ariaLabel'] ) : ( ( ! empty( $title ) ) ? esc_attr( $title ) : esc_attr__( 'Number Counter', 'the-plus-addons-for-block-editor' ) );
	if ( ! empty( $link_url ) ) {
		$get_title .= '<a href="' . esc_url( $link_url ) . '" ' . $target . ' ' . $nofollow . ' ' . $link_attr . ' aria-label="' . esc_attr( $title ) . '">';
	}
	$get_title .= '<h6 class="counter-title ' . esc_attr( $tranease ) . '">' . wp_kses_post( $title ) . '</h6>';
	if ( ! empty( $link_url ) ) {
		$get_title .= '</a>';
	}

	$get_icon = '';
	if ( ! empty( $link_url ) ) {
		$get_icon .= '<a href="' . esc_url( $link_url ) . '" ' . $target . ' ' . $nofollow . ' ' . $link_attr . ' aria-label="' . $aria_label . '">';
	}
			$get_icon         .= '<div class="counter-icon-inner shape-icon-' . esc_attr( $icon_style ) . ' ' . esc_attr( $tranease ) . '">';
				$get_icon     .= '<span class="counter-icon ' . esc_attr( $tranease ) . '">';
					$get_icon .= '<i class="' . esc_attr( $icon_store ) . '"></i>';
				$get_icon     .= '</span>';
			$get_icon         .= '</div>';
	if ( ! empty( $link_url ) ) {
		$get_icon .= '</a>';
	}

	$get_img = '';
	if ( ! empty( $link_url ) ) {
		$get_img .= '<a href="' . esc_url( $link_url ) . '" ' . $target . ' ' . $nofollow . ' ' . $link_attr . ' aria-label="' . $aria_label . '">';
	}
			$get_img     .= '<div class="counter-image-inner ' . esc_attr( $tranease ) . '">';
				$get_img .= $img_src;
			$get_img     .= '</div>';
	if ( ! empty( $link_url ) ) {
		$get_img .= '</a>';
	}

	$getsvg  = '';
	$getsvg .= '<div class="tpgb-draw-svg" data-id="service-svg-' . esc_attr( $block_id ) . '" data-type="' . esc_attr( $svg_draw ) . '" data-duration="' . esc_attr( $svg_dura ) . '" data-stroke="' . esc_attr( $svgstro_color ) . '" data-fillColor="' . esc_attr( $svgfill_color ) . '" data-strokeColorHover="' . esc_attr( $svgstro_hov ) . '" data-fillColorHover="' . esc_attr( $svgfill_hov ) . '" data-fillEnable="yes">';
	if ( ! empty( $link_url ) ) {
		$getsvg .= '<a href="' . esc_url( $link_url ) . '" ' . $target . ' ' . $nofollow . ' ' . $link_attr . ' aria-label="' . $aria_label . '">';
	}

		$getsvg .= '<object id="service-svg-' . esc_attr( $block_id ) . '" type="image/svg+xml" data="' . esc_url( $svg_icon['url'] ) . '" aria-label="' . esc_attr__( 'icon', 'the-plus-addons-for-block-editor' ) . '"></object>';

	if ( ! empty( $link_url ) ) {
		$getsvg .= '</a>';
	}
	$getsvg     .= '</div>';
	$output      = '';
	$output     .= '<div class="tpgb-number-counter tpgb-relative-block counter-' . esc_attr( $style ) . ' ' . esc_attr( $alignment ) . ' tpgb-block-' . esc_attr( $block_id ) . ' ' . esc_attr( $block_class ) . '">';
		$output .= '<div class="number-counter-inner tpgb-relative-block ' . esc_attr( $tranease ) . ' ' . esc_attr( $v_center ) . '">';
	if ( 'style-1' === $style ) {
		$output .= '<div class="counter-wrap-content">';
		if ( 'icon' === $icon_type ) {
				$output .= $get_icon;
		}
		if ( 'img' === $icon_type ) {
					$output .= $get_img;
		}
		if ( 'svg' === $icon_type ) {
			$output .= $getsvg;
		}
						$output .= $get_counter_no;
		if ( ! empty( $title ) ) {
			$output .= $get_title;
		}
						$output .= '</div>';
	}
	if ( 'style-2' === $style ) {
		$output .= '<div class="icn-header">';
		if ( 'icon' === $icon_type ) {
				$output .= $get_icon;
		}
		if ( 'img' === $icon_type ) {
					$output .= $get_img;
		}
		if ( 'svg' === $icon_type ) {
			$output .= $getsvg;
		}
						$output .= '</div>';
						$output .= '<div class="counter-content">';
						$output .= $get_counter_no;
		if ( ! empty( $title ) ) {
			$output .= $get_title;
		}
						$output .= '</div>';
	}
		$output .= '</div>';
	$output     .= '</div>';

	$output = Tpgb_Blocks_Global_Options::block_Wrap_Render( $attributes, $output );

	return $output;
}

/**
 * Tpgb format number.
 *
 * @param int $number The number.
 * @param int $numeration The numeration.
 * @return mixed The result.
 */
function tpgb_formatNumber( $number, $numeration ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid,WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
	if ( 'indian' === $numeration ) {
		$x             = strval( $number );
		$last_three    = substr( $x, -3 );
		$other_numbers = substr( $x, 0, -3 );
		if ( '' !== $other_numbers ) {
			$last_three = ',' . $last_three;
		}
		$res = preg_replace( '/\B(?=(\d{2})+(?!\d))/', '-', $other_numbers ) . $last_three;
		return $res;
	} elseif ( 'international' === $numeration ) {
		return number_format( $number );
	} else {
		return $number;
	}
}

/**
 * Render for the server-side
 */
function tpgb_number_counter() {
	$block_data = Tpgb_Blocks_Global_Options::merge_options_json( __DIR__, 'tpgb_tp_number_counter_render_callback' );
	register_block_type( $block_data['name'], $block_data );
}
add_action( 'init', 'tpgb_number_counter' );
