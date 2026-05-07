<?php
/**
 * Price List.
 *
 * @package ThePluginAddonsForBlockEditor
 */

defined( 'ABSPATH' ) || exit;

/**
 * Tpgb pricing list.
 *
 * @param mixed $attributes The attributes.
 * @param mixed $content The content.
 * @return mixed The result.
 */
function tpgb_pricing_list( $attributes, $content ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
	$block_id          = isset( $attributes['block_id'] ) ? $attributes['block_id'] : '';
	$style             = ( ! empty( $attributes['style'] ) ) ? $attributes['style'] : 'style-1';
	$box_align         = ( ! empty( $attributes['boxAlign'] ) ) ? $attributes['boxAlign'] : 'top-left';
	$hover_effect_type = ( ! empty( $attributes['hoverEffect'] ) ) ? $attributes['hoverEffect'] : 'horizontal';
	$tag_field         = ( ! empty( $attributes['tagField'] ) ) ? $attributes['tagField'] : '';
	$title             = ( ! empty( $attributes['title'] ) ) ? $attributes['title'] : '';
	$description       = ( ! empty( $attributes['description'] ) ) ? $attributes['description'] : '';
	$price             = ( ! empty( $attributes['price'] ) ) ? $attributes['price'] : '';
	$image_field       = ( ! empty( $attributes['imageField'] ) ) ? $attributes['imageField'] : '';
	$img_shape         = ( ! empty( $attributes['imgShape'] ) ) ? $attributes['imgShape'] : 'none';
	$mask_img          = ( ! empty( $attributes['maskImg'] ) ) ? $attributes['maskImg'] : '';
	$image_size        = ( ! empty( $attributes['imageSize'] ) ) ? $attributes['imageSize'] : 'thumbnail';

	$block_class = Tp_Blocks_Helper::block_wrapper_classes( $attributes );

	$img_src  = '';
	$alt_text = ( isset( $image_field['alt'] ) && ! empty( $image_field['alt'] ) ) ? esc_attr( $image_field['alt'] ) : ( ( ! empty( $image_field['title'] ) ) ? esc_attr( $image_field['title'] ) : esc_attr__( 'Food Item', 'the-plus-addons-for-block-editor' ) );

	if ( ! empty( $image_field ) && ! empty( $image_field['id'] ) ) {
		$img_src = wp_get_attachment_image( $image_field['id'], $image_size, false, array( 'alt' => $alt_text ) );
	} elseif ( ! empty( $image_field['url'] ) ) {
		$img_url = ( isset( $image_field['dynamic'] ) && class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_repeat_url( $image_field ) : $image_field['url'];
		$img_src = '<img src="' . esc_url( $img_url ) . '" alt="' . $alt_text . '" />';
	}

	$get_menu_tag = '';

	if ( class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) {
		global $repeater_index;
		$rep_index = $repeater_index ?? 0;

		if ( strpos( $tag_field, 'acf|' ) !== false || strpos( $tag_field, 'jetengine|' ) !== false ) {
			if ( preg_match( '/<span[^>]*data-tpgb-dynamic=(["\'])([^"\']+)\1[^>]*><\/span>/', $tag_field, $matches ) && ! empty( $matches    [2] ) ) {
				$data_array = json_decode( html_entity_decode( $matches[2], ENT_QUOTES | ENT_HTML5 ), true );

				if ( json_last_error() === JSON_ERROR_NONE && ! empty( $data_array['dynamicField'] ) ) {
					$parts = explode( '|', $data_array['dynamicField'] );

					if ( count( $parts ) === 5 || count( $parts ) === 7 ) {
						$field_name = $parts[1] ?? 'Unknown Field';
						$rep_data   = apply_filters( 'tp_get_repeater_data', $parts );
						if ( is_wp_error( $rep_data ) ) {
							$replacement = '';
						} else {
							$replacement = $rep_data['repeater_data'][ $rep_index ][ $field_name ] ?? '';
						}

						$tag_field = preg_replace(
							'/<span[^>]+data-tpgb-dynamic=(["\'])(.*?)\1[^>]*><\/span>/',
							esc_html( $replacement ),
							$tag_field
						);
					}
				}
			}
		} else {
			$tag_field = Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_val( $tag_field );
		}
	} else {
		$tag_field = $tag_field;
	}

	$array = explode( '|', $tag_field );
	if ( ! empty( $array[1] ) ) {
		foreach ( $array as $value ) {
			$get_menu_tag .= '<h5 class="food-menu-tag">' . esc_html( $value ) . '</h5>';
		}
	} else {
		$get_menu_tag .= '<h5 class="food-menu-tag">' . esc_html( $tag_field ) . '</h5>';
	}

	$get_title = '';
	if ( ! empty( $title ) ) {
		$get_title .= '<h3 class="food-menu-title">' . wp_kses_post( $title ) . '</h3>';
	}
	$get_desc = '';
	if ( ! empty( $description ) ) {
		$get_desc .= '<div class="food-desc">' . wp_kses_post( $description ) . '</div>';
	}
	$get_price = '';
	if ( ! empty( $price ) ) {
		$get_price .= '<h4 class="food-menu-price">' . wp_kses_post( $price ) . '</h4>';
	}
	$box_align    = '';
	$hover_effect = '';
	if ( 'style-2' === $style ) {
		$box_align    = $box_align;
		$hover_effect = $hover_effect_type;
	}

	$output          = '';
		$output     .= '<div class="tpgb-pricing-list tpgb-relative-block tpgb-block-' . esc_attr( $block_id ) . ' food-menu-' . esc_attr( $style ) . ' ' . esc_attr( $block_class ) . '">';
			$output .= '<div class="food-menu-box ' . esc_attr( $box_align ) . '">';
	if ( 'style-1' === $style ) {
		if ( ! empty( $tag_field ) ) {
			$output .= $get_menu_tag;
		}
		$output .= $get_title;
		$output .= $get_desc;
		$output .= $get_price;
	}
	if ( 'style-2' === $style ) {
		$output             .= '<div class="food-flipbox flip-' . esc_attr( $hover_effect ) . ' height-full">';
			$output         .= '<div class="food-flipbox-holder height-full perspective bezier-1">';
				$output     .= '<div class="food-flipbox-front bezier-1 no-backface origin-center">';
					$output .= '<div class="food-flipbox-content width-full">';
		if ( ! empty( $tag_field ) ) {
			$output .= '<div class="food-menu-block">' . $get_menu_tag . '</div>';
		}
						$output .= '<div class="food-menu-block">' . $get_title . '</div>';
						$output .= $get_price;
					$output     .= '</div>';
				$output         .= '</div>';
				$output         .= '<div class="food-flipbox-back fold-back-' . esc_attr( $hover_effect ) . ' no-backface bezier-1 origin-center">';
					$output     .= '<div class="food-flipbox-content width-full ">';
						$output .= '<div class="text-center">' . $get_desc . '</div>';
					$output     .= '</div></div></div></div>';
	}
	if ( 'style-3' === $style ) {
		$output     .= '<div class="food-menu-flex tpgb-relative-block">';
			$output .= '<div class="food-flex-line ">';
		if ( ! empty( $img_src ) ) {
					$output         .= '<div class="food-flex-imgs food-flex-img tpgb-relative-block">';
						$output     .= '<div class="food-img img-' . esc_attr( $img_shape ) . '">';
							$output .= $img_src;
						$output     .= '</div>';
					$output         .= '</div>';
		}
				$output .= '<div class="food-flex-content">';
		if ( ! empty( $tag_field ) ) {
						$output .= '<div class="food-menu-block">' . $get_menu_tag . '</div>';
		}
								$output .= '<div class="food-title-price">';
						$output         .= $get_title;
						$output         .= '<div class="food-menu-divider"><div class="menu-divider no"></div></div>';
						$output         .= $get_price;
								$output .= '</div>';
						$output         .= $get_desc;
								$output .= '</div></div></div>';
	}
			$output .= '</div>';
		$output     .= '</div>';

		$output = Tpgb_Blocks_Global_Options::block_Wrap_Render( $attributes, $output );

	return $output;
}
/**
 * Render for the server-side
 */
function tpgb_tp_pricing_list() {
	$block_data = Tpgb_Blocks_Global_Options::merge_options_json( __DIR__, 'tpgb_pricing_list' );
	register_block_type( $block_data['name'], $block_data );
}
add_action( 'init', 'tpgb_tp_pricing_list' );
