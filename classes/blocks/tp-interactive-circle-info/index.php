<?php
/**
 * Interactive Circle Info.
 *
 * @package ThePluginAddonsForBlockEditor
 */

defined( 'ABSPATH' ) || exit;

/**
 * Tpgb tp interactive circle info render callback.
 *
 * @param mixed $attributes The attributes.
 * @param mixed $content The content.
 * @return mixed The result.
 */
function tpgb_tp_interactive_circle_info_render_callback( $attributes, $content ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
	$block_id        = ( ! empty( $attributes['block_id'] ) ) ? $attributes['block_id'] : uniqid( 'title' );
	$style_type      = ( ! empty( $attributes['styleType'] ) ) ? $attributes['styleType'] : 'style-1';
	$int_circle      = ( ! empty( $attributes['intCircle'] ) ) ? $attributes['intCircle'] : array();
	$mouse_trigger   = ( ! empty( $attributes['mouseTrigger'] ) ) ? $attributes['mouseTrigger'] : 'hover';
	$auto_time       = ( ! empty( $attributes['autoTime'] ) ) ? $attributes['autoTime'] : 1000;
	$default_active  = ( ! empty( $attributes['defaultActive'] ) ) ? $attributes['defaultActive'] : 1;
	$out_animation   = ( ! empty( $attributes['outAnimation'] ) ) ? $attributes['outAnimation'] : false;
	$sel_animation   = ( ! empty( $attributes['selAnimation'] ) ) ? $attributes['selAnimation'] : 'bounce';
	$carousel_toggle = ( ! empty( $attributes['carouselToggle'] ) ) ? $attributes['carouselToggle'] : false;
	$ext_indicator   = ( ! empty( $attributes['extIndicator'] ) ) ? $attributes['extIndicator'] : array();
	$conti_rotate    = ( ! empty( $attributes['contiRotate'] ) ) ? $attributes['contiRotate'] : array();
	$carousel_id     = ( ! empty( $attributes['carouselID'] ) ) ? $attributes['carouselID'] : '';

	$dis_btn           = ( ! empty( $attributes['disBtn'] ) ) ? $attributes['disBtn'] : false;
	$btn_style         = ( ! empty( $attributes['btnStyle'] ) ) ? $attributes['btnStyle'] : 'style-7';
	$btn_icon_type     = ( ! empty( $attributes['btnIconType'] ) ) ? $attributes['btnIconType'] : 'none';
	$btn_icon_position = ( ! empty( $attributes['btnIconPosition'] ) ) ? $attributes['btnIconPosition'] : 'after';
	$btn_icon_store    = ( ! empty( $attributes['btnIconStore'] ) ) ? $attributes['btnIconStore'] : '';

	$block_class = Tp_Blocks_Helper::block_wrapper_classes( $attributes );

	$total_items        = '';
	$animation_class    = '';
	$indicat_class      = '';
	$conti_rotate_class = '';
	foreach ( $int_circle as $index => $item ) :
		$total_items = 1 + (int) $index;
	endforeach;

	if ( ! empty( $out_animation ) ) {
		$animation_class = 'ia-circle-animation-' . $sel_animation;
	}
	if ( ! empty( $ext_indicator ) && ! empty( $ext_indicator['tpgbReset'] ) ) {
		$indicat_class = 'indicator-' . $ext_indicator['indiStyle'];
	}
	if ( ! empty( $conti_rotate ) && ! empty( $conti_rotate['tpgbReset'] ) && 'bounce' === $sel_animation ) {
		if ( 'clock-wise' === $conti_rotate['animDirection'] ) {
			$conti_rotate_class = 'circle-continue-rotate';
		} else {
			$conti_rotate_class = 'circle-continue-rotate direction-reverse';
		}
	}
	$d_auto_time_attr = '';
	if ( 'auto' === $mouse_trigger ) {
		$d_auto_time_attr = 'data-auto-time="' . esc_attr( $auto_time ) . '"';
	}
	$connect_carousel       = '';
	$connection_hover_click = '';
	$connect_id             = '';
	if ( ! empty( $carousel_toggle ) && ! empty( $carousel_id ) && 'auto' !== $mouse_trigger ) {
		$connect_carousel       = 'tpca-' . $carousel_id;
		$connect_id             = 'tptab_' . $carousel_id;
		$connection_hover_click = $mouse_trigger;
	}

	$output              = '';
	$output             .= '<div class="tpgb-ia-circle-info tpgb-relative-block circle-' . esc_attr( $style_type ) . ' ' . esc_attr( $animation_class ) . ' ' . esc_attr( $indicat_class ) . ' ' . esc_attr( $conti_rotate_class ) . ' tpgb-block-' . esc_attr( $block_id ) . ' ' . esc_attr( $block_class ) . '" data-trigger="' . esc_attr( $mouse_trigger ) . '" ' . $d_auto_time_attr . ' id="' . esc_attr( $connect_id ) . '" data-connection="' . esc_attr( $connect_carousel ) . '" data-eventtype="' . esc_attr( $connection_hover_click ) . '">';
		$output         .= '<div class="ia-circle-wrap tpgb-rel-flex">';
			$output     .= '<div class="ia-circle-inner-wrap" data-total="' . esc_attr( $total_items ) . '">';
				$output .= '<div class="ia-circle-inner tpgb-trans-linear">';
	foreach ( $int_circle as $index => $item ) :
		$item_count = '';
		$def_active = '';
		if ( 1 + (int) $index === (int) $default_active ) {
			$def_active = 'active';
		}
		$img_src    = '';
		$item_count = 1 + (int) $index;

		$getbutton = '';
		$btn_url   = ( isset( $item['btnUrl'] ) && ! empty( $item['btnUrl']['url'] ) ) ? $item['btnUrl']['url'] : '';
		if ( class_exists( 'Tpgbp_Pro_Blocks_Helper' ) && isset( $item['btnUrl'] ) ) {
			$btn_url = ( isset( $item['btnUrl']['dynamic'] ) ) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_repeat_url( $item['btnUrl'] ) : ( ! empty( $item['btnUrl']['url'] ) ? $item['btnUrl']['url'] : '' );
		}
		$link_attr = '';
		if ( isset( $item['btnUrl'] ) ) {
			$link_attr = Tp_Blocks_Helper::add_link_attributes( $item['btnUrl'] );
		}
		$target       = ( ! empty( $item['btnUrl']['target'] ) ) ? 'target="_blank"' : '';
		$nofollow     = ( ! empty( $item['btnUrl']['nofollow'] ) ) ? 'rel="nofollow"' : '';
		$aria_label_t = ( ! empty( $item['btnText'] ) ) ? esc_attr( $item['btnText'] ) : esc_attr__( 'Button', 'the-plus-addons-for-block-editor' );
		if ( ! empty( $item['btnText'] ) ) {
			$getbutton     .= '<div class="tpgb-adv-button button-' . esc_attr( $btn_style ) . '">';
				$getbutton .= '<a href="' . esc_url( $btn_url ) . '" class="button-link-wrap" role="button" ' . $target . ' ' . $nofollow . ' ' . $link_attr . ' aria-label="' . $aria_label_t . '">';
			if ( 'style-8' === $btn_style ) {
				if ( 'before' === $btn_icon_position ) {
					if ( 'icon' === $btn_icon_type ) {
										$getbutton     .= '<span class="btn-icon button-' . esc_attr( $btn_icon_position ) . '">';
											$getbutton .= '<i class="' . esc_attr( $btn_icon_store ) . '"></i>';
										$getbutton     .= '</span>';
					}
					$getbutton .= wp_kses_post( $item['btnText'] );
				}
				if ( 'after' === $btn_icon_position ) {
					$getbutton .= wp_kses_post( $item['btnText'] );
					if ( 'icon' === $btn_icon_type ) {
										$getbutton     .= '<span class="btn-icon button-' . esc_attr( $btn_icon_position ) . '">';
											$getbutton .= '<i class="' . esc_attr( $btn_icon_store ) . '"></i>';
										$getbutton     .= '</span>';
					}
				}
			}
			if ( 'style-7' === $btn_style || 'style-9' === $btn_style ) {
								$getbutton .= wp_kses_post( $item['btnText'] );
								$getbutton .= '<span class="button-arrow">';
				if ( 'style-7' === $btn_style ) {
					$getbutton .= '<span class="btn-right-arrow"><i class="fas fa-chevron-right"></i></span>';
				}
				if ( 'style-9' === $btn_style ) {
					$getbutton .= '<i class="btn-show fas fa-chevron-right"></i>';
					$getbutton .= '<i class="btn-hide fas fa-chevron-right"></i>';
				}
								$getbutton .= '</span>';
			}
											$getbutton .= '</a>';
											$getbutton .= '</div>';
		}

		$output         .= '<div class="tpgb-ia-circle-item tpgb-circle-item-' . esc_attr( $item_count ) . ' tp-repeater-item-' . esc_attr( $item['_key'] ) . ' ' . esc_attr( $def_active ) . '" data-index="' . esc_attr( $item_count ) . '">';
			$output     .= '<div class="tpgb-circle-icon-wrap tpgb-trans-linear">';
				$output .= '<div class="circle-icon-inner tpgb-rel-flex tpgb-trans-linear">';
		if ( 'icon' === $item['iconType'] && ! empty( $item['iconStore'] ) ) {
				$output .= '<i class="' . esc_attr( $item['iconStore'] ) . ' tpgb-in-circle-icon" aria-hidden="true"></i>';
		} elseif ( 'image' === $item['iconType'] && ! empty( $item['imageName'] ) ) {
							$image_size = ( ! empty( $item['imageSize'] ) ) ? $item['imageSize'] : 'full';
							$alt_text   = ( isset( $item['imageName']['alt'] ) && ! empty( $item['imageName']['alt'] ) ) ? esc_attr( $item['imageName']['alt'] ) : ( ( ! empty( $item['imageName']['title'] ) ) ? esc_attr( $item['imageName']['title'] ) : esc_attr__( 'Circle Info', 'the-plus-addons-for-block-editor' ) );

			if ( ! empty( $item['imageName'] ) && ! empty( $item['imageName']['id'] ) ) {
						$img_src = wp_get_attachment_image(
							$item['imageName']['id'],
							$image_size,
							false,
							array(
								'class' => 'tpgb-in-circle-image',
								'alt'   => $alt_text,
							)
						);
			} elseif ( ! empty( $item['imageName']['url'] ) ) {
							$img_src = '<img src="' . esc_url( $item['imageName']['url'] ) . '" class="tpgb-in-circle-image " alt="' . $alt_text . '"/>';
			}
							$output .= $img_src;
		}
		if ( ! empty( $item['iconTitle'] ) ) {
			$output .= '<div class="circle-icon-title">' . wp_kses_post( $item['iconTitle'] ) . '</div>';
		}
										$output .= '</div>';

		if ( ! empty( $ext_indicator ) && ! empty( $ext_indicator['tpgbReset'] ) ) {
			$output     .= '<div class="tpgb-circle-ext-indicator">';
				$output .= '<div class="tpgb-circle-shape-wrap"><div class="tpgb-circle-shape-inner"></div></div>';
			$output     .= '</div>';
		}

										$output .= '</div>';
										$output .= '<div class="tpgb-circle-content-wrap tpgb-abs-flex">';
										$output .= '<div class="circle-content-inner tpgb-rel-flex">';
		if ( ! empty( $item['conTitle'] ) ) {
			$output .= '<div class="circle-content-title">' . wp_kses_post( $item['conTitle'] ) . '</div>';
		}
		if ( ! empty( $item['conDesc'] ) ) {
			$output .= '<div class="circle-content-desc">' . wp_kses_post( $item['conDesc'] ) . '</div>';
		}
		if ( ! empty( $dis_btn ) ) {
			$output .= $getbutton;
		}
										$output .= '</div>';
										$output .= '</div>';
										$output .= '</div>';

					endforeach;
				$output .= '</div>';
			$output     .= '</div>';
		$output         .= '</div>';
	$output             .= '</div>';

	$output = Tpgb_Blocks_Global_Options::block_Wrap_Render( $attributes, $output );

	return $output;
}
/**
 * Render for the server-side
 */
function tpgb_tp_interactive_circle_info() {

	if ( method_exists( 'Tpgb_Blocks_Global_Options', 'merge_options_json' ) ) {
		$block_data = Tpgb_Blocks_Global_Options::merge_options_json( __DIR__, 'tpgb_tp_interactive_circle_info_render_callback' );
		register_block_type( $block_data['name'], $block_data );
	}
}
add_action( 'init', 'tpgb_tp_interactive_circle_info' );
