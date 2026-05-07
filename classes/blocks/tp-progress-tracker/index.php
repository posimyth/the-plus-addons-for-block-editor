<?php
/**
 * Progress Tracker.
 *
 * @package ThePluginAddonsForBlockEditor
 */

defined( 'ABSPATH' ) || exit;

/**
 * Tpgb progress tracker render callback.
 *
 * @param mixed $attributes The attributes.
 * @param mixed $content The content.
 * @return mixed The result.
 */
function tpgb_progress_tracker_render_callback( $attributes, $content ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
	$block_id         = ( ! empty( $attributes['block_id'] ) ) ? $attributes['block_id'] : uniqid( 'title' );
	$progress_type    = ( ! empty( $attributes['progressType'] ) ) ? $attributes['progressType'] : 'horizontal';
	$horizontal_pos   = ( ! empty( $attributes['horizontalPos'] ) ) ? $attributes['horizontalPos'] : 'top';
	$hz_direction     = ( ! empty( $attributes['hzDirection'] ) ) ? $attributes['hzDirection'] : 'ltr';
	$vertical_pos     = ( ! empty( $attributes['verticalPos'] ) ) ? $attributes['verticalPos'] : 'left';
	$circular_pos     = ( ! empty( $attributes['circularPos'] ) ) ? $attributes['circularPos'] : 'top-left';
	$percentage_text  = ( ! empty( $attributes['percentageText'] ) ) ? $attributes['percentageText'] : false;
	$percentage_style = ( ! empty( $attributes['percentageStyle'] ) ) ? $attributes['percentageStyle'] : 'style-1';
	$circle_size      = ( ! empty( $attributes['circleSize'] ) ) ? $attributes['circleSize'] : '50';
	$apply_to         = ( ! empty( $attributes['applyTo'] ) ) ? $attributes['applyTo'] : 'entire';
	$unq_selector     = ( ! empty( $attributes['unqSelector'] ) ) ? $attributes['unqSelector'] : '';
	$pin_point        = ( ! empty( $attributes['pinPoint'] ) ) ? $attributes['pinPoint'] : false;
	$pin_p_style      = ( ! empty( $attributes['pinPStyle'] ) ) ? $attributes['pinPStyle'] : 'style-1';
	$pin_point_rep    = ( ! empty( $attributes['pinPointRep'] ) ) ? $attributes['pinPointRep'] : array();

	$block_class = Tp_Blocks_Helper::block_wrapper_classes( $attributes );

	$rel_tselector = ( ! empty( $attributes['relTselector'] ) && ! empty( $unq_selector ) && 'selector' === $apply_to ) ? 'tracker-rel-sel' : '';

	$position_class = '';
	$pos_class      = '';
	$position_class = 'tpgb-fixed-block';
	if ( 'horizontal' === $progress_type ) {
		$pos_class = 'pos-' . $horizontal_pos . ' direction-' . $hz_direction;
	} elseif ( 'vertical' === $progress_type ) {
		$pos_class = 'pos-' . $vertical_pos;
	} else {
		$pos_class = 'pos-' . $circular_pos;
	}

	$pin_point_enable = '';
	if ( ! empty( $pin_point ) ) {
		$pin_point_enable = 'container-pinpoint-yes';
	}

	$data_attr             = array();
	$data_attr['apply_to'] = $apply_to;
	if ( 'selector' === $apply_to && ! empty( $unq_selector ) ) {
		$data_attr['selector'] = $unq_selector;
	}
	$data_attr = 'data-attr="' . htmlspecialchars( wp_json_encode( $data_attr, true ), ENT_QUOTES, 'UTF-8' ) . '"';

	$output       = '';
	$get_pin_item = '';
	$output      .= '<div class="tpgb-progress-tracker tpgb-relative-block type-' . esc_attr( $progress_type ) . ' ' . esc_attr( $rel_tselector ) . ' tpgb-block-' . esc_attr( $block_id ) . ' ' . esc_attr( $block_class ) . ' ' . esc_attr( $pin_point_enable ) . '" ' . $data_attr . '>';
		$output  .= '<div class="tpgb-progress-track ' . esc_attr( $position_class ) . ' ' . esc_attr( $pos_class ) . '">';
	if ( 'circular' !== $progress_type ) {
		$output .= '<div class="progress-track-fill">';
		if ( ! empty( $percentage_text ) ) {
				$output .= '<div class="progress-track-percentage ' . esc_attr( $percentage_style ) . '"></div>';
		}
				$output .= '</div>';

		if ( ! empty( $pin_point ) && 'entire' === $apply_to && 'circular' !== $progress_type ) {
			if ( ! empty( $pin_point_rep ) ) {
				$get_pin_item .= '<div class="tracker-pin-point-wrap pin-' . esc_attr( $pin_p_style ) . '">';
				foreach ( $pin_point_rep as $index => $item ) :
					if ( ! empty( $item['conID'] ) && ! empty( $item['Title'] ) ) {
						$get_pin_item     .= '<div class="tracker-pin" data-id="' . esc_attr( $item['conID'] ) . '">';
							$get_pin_item .= '<span class="tracker-pin-text">' . wp_kses_post( $item['Title'] ) . '</span>';
						$get_pin_item     .= '</div>';
					}
				endforeach;
				$get_pin_item .= '</div>';
			}
		}
				$output .= $get_pin_item;
	} else {
		$output .= '<svg class="tpgb-pt-svg-circle" width="200" height="200" viewport="0 0 100 100" xmlns="https://www.w3.org/2000/svg">
				<circle class="tpgb-pt-circle-st" cx="100" cy="100" r="' . esc_attr( $circle_size ) . '"></circle>
				<circle class="tpgb-pt-circle-st1" cx="100" cy="100" r="' . esc_attr( $circle_size ) . '"></circle>
				<circle class="tpgb-pt-circle-st2" cx="100" cy="100" r="' . esc_attr( $circle_size ) . '"></circle></svg>';
		if ( ! empty( $percentage_text ) ) {
			$output .= '<div class="progress-track-percentage"></div>';
		}
	}
		$output .= '</div>';
	$output     .= '</div>';

	$output = Tpgb_Blocks_Global_Options::block_Wrap_Render( $attributes, $output );

	return $output;
}

/**
 * Render for the server-side
 */
function tpgb_progress_tracker() {
	$block_data = Tpgb_Blocks_Global_Options::merge_options_json( __DIR__, 'tpgb_progress_tracker_render_callback' );
	register_block_type( $block_data['name'], $block_data );
}
add_action( 'init', 'tpgb_progress_tracker' );
