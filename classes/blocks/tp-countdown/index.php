<?php
/**
 * Countdown.
 *
 * @package ThePluginAddonsForBlockEditor
 */

defined( 'ABSPATH' ) || exit;

/**
 * Tpgb tp countdown callback.
 *
 * @param mixed $attributes The attributes.
 * @param mixed $content The content.
 * @return mixed The result.
 */
function tpgb_tp_countdown_callback( $attributes, $content ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
	$output      = '';
	$block_id    = ( ! empty( $attributes['block_id'] ) ) ? $attributes['block_id'] : uniqid( 'title' );
	$block_class = Tp_Blocks_Helper::block_wrapper_classes( $attributes );

	$style               = $attributes['style'];
	$countdown_selection = $attributes['countdownSelection'];
	$offset_time         = get_option( 'gmt_offset' );
	$offset_tz           = wp_timezone_string();
	$now                 = new DateTime( 'NOW', new DateTimeZone( $offset_tz ) );
	$flip_theme          = ( ! empty( $attributes['flipTheme'] ) ) ? $attributes['flipTheme'] : 'dark';

	$future = '';
	if ( ! empty( $attributes['datetime'] ) && 'Invalid date' !== $attributes['datetime'] ) {
		$future = new DateTime( $attributes['datetime'], new DateTimeZone( $offset_tz ) );
	}
	$now = $now->modify( '+1 second' );

	if ( ! empty( $attributes['datetime'] ) ) {
		$datetime = $attributes['datetime'];
		$datetime = gmdate( 'm/d/Y H:i:s', strtotime( $datetime ) );
	} else {
		$curr_date = gmdate( 'm/d/Y H:i:s' );
		$datetime  = gmdate( 'm/d/Y H:i:s', strtotime( $curr_date . ' +1 month' ) );
	}

	$count_data            = array();
	$count_data['style']   = $style;
	$count_data['blockId'] = 'tpgb-block-' . esc_attr( $block_id ) . '';
	$count_data['type']    = $countdown_selection;
	$count_data['expiry']  = $attributes['countdownExpiry'];

	if ( 'redirect' === $attributes['countdownExpiry'] ) {
		$encoded_url            = $attributes['expiryRedirect']['url'];
		$count_data['redirect'] = $encoded_url;
	}

	if ( 'showmsg' === $attributes['countdownExpiry'] ) {
		$count_data['expiryMsg'] = wp_kses_post( $attributes['expiryMsg'] );
	}

	$data_attr    = '';
	$show_labels  = ( ! empty( $attributes['showLabels'] ) ) ? $attributes['showLabels'] : '';
	$days_text    = ( ! empty( $attributes['daysText'] ) ) ? $attributes['daysText'] : esc_html__( 'Days', 'the-plus-addons-for-block-editor' );
	$hours_text   = ( ! empty( $attributes['hoursText'] ) ) ? $attributes['hoursText'] : esc_html__( 'Hours', 'the-plus-addons-for-block-editor' );
	$minutes_text = ( ! empty( $attributes['minutesText'] ) ) ? $attributes['minutesText'] : esc_html__( 'Minutes', 'the-plus-addons-for-block-editor' );
	$seconds_text = ( ! empty( $attributes['secondsText'] ) ) ? $attributes['secondsText'] : esc_html__( 'Seconds', 'the-plus-addons-for-block-editor' );

	if ( 'normal' === $countdown_selection && ( ! empty( $show_labels ) && true === $show_labels ) ) {
		$data_attr .= ' data-day="' . wp_kses_post( $days_text ) . '" data-hour="' . wp_kses_post( $hours_text ) . '" data-min="' . wp_kses_post( $minutes_text ) . '" data-sec="' . wp_kses_post( $seconds_text ) . '"';
	}

	if ( 'style-2' === $style ) {
		$data_attr .= ' data-filptheme = "' . esc_attr( $flip_theme ) . '"';
	}
	$data_attr .= ' data-countdata= \'' . esc_attr( wp_json_encode( $count_data ) ) . '\'';

	$output .= '<div class="tp-countdown tpgb-relative-block tpgb-block-' . esc_attr( $block_id ) . ' ' . esc_attr( $block_class ) . ' countdown-' . esc_attr( $style ) . '"  data-offset="' . esc_attr( $offset_time ) . '"  ' . $data_attr . '>';
	if ( 'normal' === $countdown_selection ) {
		if ( $future >= $now && isset( $future ) ) {
			if ( 'style-1' === $style ) {
				$inline_style = ( ! empty( $attributes['inlineStyle'] ) ) ? 'count-inline-style' : '';

				$output         .= '<div class="tpgb-countdown-counter ' . esc_attr( $inline_style ) . '" data-time = "' . esc_attr( $datetime ) . '">';
					$output     .= '<div class="count_1">';
						$output .= '<span class="days">' . esc_html__( '00', 'the-plus-addons-for-block-editor' ) . '</span>';
				if ( ! empty( $show_labels ) && true === $show_labels ) {
					$output .= '<h6 class="days_ref">' . esc_html( $days_text ) . '</h6>';
				}
					$output     .= '</div>';
					$output     .= '<div class="count_2">';
						$output .= '<span class="hours">' . esc_html__( '00', 'the-plus-addons-for-block-editor' ) . '</span>';
				if ( ! empty( $show_labels ) && true === $show_labels ) {
					$output .= '<h6 class="hours_ref">' . esc_html( $hours_text ) . '</h6>';
				}
						$output .= '</div>';
						$output .= '<div class="count_3">';
						$output .= '<span class="minutes">' . esc_html__( '00', 'the-plus-addons-for-block-editor' ) . '</span>';
				if ( ! empty( $show_labels ) && true === $show_labels ) {
					$output .= '<h6 class="minutes_ref">' . esc_html( $minutes_text ) . '</h6>';
				}
						$output .= '</div>';
						$output .= '<div class="count_4">';
						$output .= '<span class="seconds last">' . esc_html__( '00', 'the-plus-addons-for-block-editor' ) . '</span>';
				if ( ! empty( $show_labels ) && true === $show_labels ) {
					$output .= '<h6 class="seconds_ref">' . esc_html( $seconds_text ) . '</h6>';
				}
						$output .= '</div>';
						$output .= '</div>';
			} elseif ( 'style-2' === $style ) {
				$output .= '<div id="tpgb-block-' . esc_attr( $block_id ) . '" class="flipdown tpgb-countdown-counter" data-time=' . esc_attr( strtotime( $datetime ) ) . '></div>';
			} elseif ( 'style-3' === $style ) {
				if ( ! empty( $attributes['datetime'] ) ) {
					$datetime = $future->format( 'c' );
				} else {
					$datetime = '2020-08-10T16:42:00';
				}

				$output     .= '<div class="tpgb-countdown-counter" data-time="' . esc_attr( $datetime ) . '">';
					$output .= '<div class="counter-part"><div class="day-' . esc_attr( $block_id ) . ' day" id="dtpgb-block-' . esc_attr( $block_id ) . '"></div></div>';
					$output .= '<div class="counter-part"><div class="hour-' . esc_attr( $block_id ) . ' hour" id="htpgb-block-' . esc_attr( $block_id ) . '"></div></div>';
					$output .= '<div class="counter-part"><div class="min-' . esc_attr( $block_id ) . ' min" id="mtpgb-block-' . esc_attr( $block_id ) . '"></div></div>';
					$output .= '<div class="counter-part"><div class="sec-' . esc_attr( $block_id ) . ' sec" id="stpgb-block-' . esc_attr( $block_id ) . '"></div></div>';
				$output     .= '</div>';
			}
		} elseif ( 'showmsg' === $attributes['countdownExpiry'] ) {
				$output .= '<div class="tpgb-countdown-expiry">' . esc_html( $attributes['expiryMsg'] ) . '</div>';
		}
	}
	$output .= '</div>';

	$output = Tpgb_Blocks_Global_Options::block_Wrap_Render( $attributes, $output );

	return $output;
}

/**
 * Tpgb tp countdown render.
 */
function tpgb_tp_countdown_render() {

	$block_data = Tpgb_Blocks_Global_Options::merge_options_json( __DIR__, 'tpgb_tp_countdown_callback' );
	register_block_type( $block_data['name'], $block_data );
}
add_action( 'init', 'tpgb_tp_countdown_render' );
