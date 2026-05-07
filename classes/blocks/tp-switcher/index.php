<?php
/**
 * Switcher.
 *
 * @package ThePluginAddonsForBlockEditor
 */

defined( 'ABSPATH' ) || exit;

/**
 * Tpgb switcher render callback.
 *
 * @param mixed $attributes The attributes.
 * @param mixed $content The content.
 * @return mixed The result.
 */
function tpgb_switcher_render_callback( $attributes, $content ) {
	$output   = '';
	$block_id = ( ! empty( $attributes['block_id'] ) ) ? $attributes['block_id'] : uniqid( 'title' );
	$pattern  = '/\btpgb-block-' . esc_attr( $block_id ) . '/';

	if ( preg_match( $pattern, $content ) ) {
		if ( class_exists( 'Tpgb_Blocks_Global_Options' ) ) {
			$global_blocks = Tpgb_Blocks_Global_Options::get_instance();
			$content       = $global_blocks::block_row_conditional_render( $attributes, $content );
		}
		return $content;
	}
	$switch_style = ( ! empty( $attributes['switchStyle'] ) ) ? $attributes['switchStyle'] : 'style-1';
	$switchalign  = ( ! empty( $attributes['switchalign'] ) ) ? $attributes['switchalign'] : 'text-left';
	$title1       = ( ! empty( $attributes['title1'] ) ) ? $attributes['title1'] : '';
	$title2       = ( ! empty( $attributes['title2'] ) ) ? $attributes['title2'] : '';
	$show_btn     = ( ! empty( $attributes['showBtn'] ) ) ? $attributes['showBtn'] : false;
	$desc1        = ( ! empty( $attributes['desc1'] ) ) ? $attributes['desc1'] : '';
	$desc2        = ( ! empty( $attributes['desc2'] ) ) ? $attributes['desc2'] : '';
	$source1      = ( ! empty( $attributes['source1'] ) ) ? $attributes['source1'] : '';
	$source2      = ( ! empty( $attributes['source2'] ) ) ? $attributes['source2'] : '';

	$lbl_icon = ( ! empty( $attributes['lblIcon'] ) ) ? $attributes['lblIcon'] : false;

	$block_class = Tp_Blocks_Helper::block_wrapper_classes( $attributes );

	$sotriclass = '';
	$sttriclass = '';
	$temp1class = '';
	$temp2class = '';
	$viewclass  = '';

	$output                     .= '<div class="tpgb-switcher tpgb-block-' . esc_attr( $block_id ) . ' ' . esc_attr( $block_class ) . '">';
		$output                 .= '<div class="tpgb-switch-wrap" data-id="' . esc_attr( $block_id ) . '">';
			$output             .= '<div class="switch-toggle-wrap switch-' . esc_attr( $switch_style ) . ' ' . esc_attr( $switchalign ) . ' inactive">';
				$output         .= '<div class="switch-1 ' . esc_attr( $sotriclass ) . '">';
					$output     .= '<div class="switch-label">';
						$output .= wp_kses_post( $title1 );
					$output     .= '</div>';
				$output         .= '</div>';
	if ( ! empty( $show_btn ) ) {
		$output         .= '<div class="switcher-button">';
			$output     .= '<label class="switch-btn-label">';
				$output .= '<input type="checkbox" class="switch-toggle ' . esc_attr( $switch_style ) . '" />';
				$output .= '<span class="switch-slider switch-round ' . esc_attr( $switch_style ) . '"></span>';
			$output     .= '</label>';
		$output         .= '</div>';
	}
				$output         .= '<div class="switch-2 ' . esc_attr( $sttriclass ) . '">';
					$output     .= '<div class="switch-label">';
						$output .= wp_kses_post( $title2 );
					$output     .= '</div>';
				$output         .= '</div>';
			$output             .= '</div>';
			$output             .= '<div class="switch-toggle-content ' . esc_attr( $viewclass ) . '">';
	if ( 'editor' === $source1 || 'editor' === $source2 ) {
		$output .= $content;
	}
			$output .= '</div>';
		$output     .= '</div>';
	$output         .= '</div>';

	$output = Tpgb_Blocks_Global_Options::block_Wrap_Render( $attributes, $output );

	return $output;
}

/**
 * Render for the server-side
 */
function tpgb_tp_switcher() {
	if ( method_exists( 'Tpgb_Blocks_Global_Options', 'merge_options_json' ) ) {
		$block_data = Tpgb_Blocks_Global_Options::merge_options_json( __DIR__, 'tpgb_switcher_render_callback' );
		register_block_type( $block_data['name'], $block_data );
	}
}
add_action( 'init', 'tpgb_tp_switcher' );
