<?php
/**
 * Tp Block : Smooth Scroll.
 *
 * @package ThePluginAddonsForBlockEditor
 */

// phpcs:disable Squiz.PHP.CommentedOutCode.Found
defined( 'ABSPATH' ) || exit;

/**
 * Tpgb tp smooth scroll render callback.
 *
 * @param mixed $attributes The attributes.
 * @return mixed The result.
 */
function tpgb_tp_smooth_scroll_render_callback( $attributes ) {
	$output   = '';
	$block_id = ( ! empty( $attributes['block_id'] ) ) ? $attributes['block_id'] : uniqid( 'title' );
	// $frameRate = (!empty($attributes['frameRate'])) ? (int)$attributes['frameRate'] : 150; // phpcs:ignore Squiz.PHP.CommentedOutCode.Found
	$ani_time   = ( ! empty( $attributes['aniTime'] ) ) ? (int) $attributes['aniTime'] : 400;
	$step_size  = ( ! empty( $attributes['stepSize'] ) ) ? (int) $attributes['stepSize'] : 100;
	$touch_supp = ( ! empty( $attributes['touchSupp'] ) ) ? 1 : 0;
	$t_mult     = ( ! empty( $attributes['tMult'] ) ) ? (int) $attributes['tMult'] : 2;
	$easing     = ( ! empty( $attributes['easing'] ) ) ? $attributes['easing'] : '(t) => 1 - Math.pow(1 - t, 3)';
	$infinite   = ( ! empty( $attributes['infinite'] ) ) ? $attributes['infinite'] : false;
	$sm_nav     = ( ! empty( $attributes['smNav'] ) ) ? $attributes['smNav'] : false;
	$cust_ease  = ( ! empty( $attributes['custEase'] ) ) ? $attributes['custEase'] : '';
	$viewport   = ( ! empty( $attributes['viewport'] ) ) ? $attributes['viewport'] : '80';

	$block_class = Tp_Blocks_Helper::block_wrapper_classes( $attributes );

	// Set Data Attr For Js.
	$encoded_ease = htmlspecialchars( wp_json_encode( $cust_ease ), ENT_QUOTES, 'UTF-8' );
	$data_attr    = array(
		// 'frameRate' => $frameRate, // phpcs:ignore Squiz.PHP.CommentedOutCode.Found
		'animationTime'    => $ani_time,
		'stepSize'         => $step_size,
		'touchMultiplier'  => $t_mult,
		'easing'           => $easing,
		'infiniteScroll'   => $infinite,
		'orientation'      => 'vertical',
		'smoothNavigation' => $sm_nav,
		'viewport'         => $viewport,
	);
	if ( 'custom' === $easing ) {
		$data_attr['customEasing'] = $cust_ease;
	}

	$data_attr = wp_json_encode( $data_attr );

	$output .= '<div class="tpgb-smooth-scroll tpgb-relative-block tpgb-block-' . esc_attr( $block_id ) . ' ' . esc_attr( $block_class ) . ' " data-scrollAttr= \'' . $data_attr . '\' >';

	$output .= '</div>';

	$output = Tpgb_Blocks_Global_Options::block_Wrap_Render( $attributes, $output );

	return $output;
}

/**
 * Render for the server-side
 */
function tpgb_tp_smooth_scroll() {
	$block_data = Tpgb_Blocks_Global_Options::merge_options_json( __DIR__, 'tpgb_tp_smooth_scroll_render_callback' );
	register_block_type( $block_data['name'], $block_data );
}
add_action( 'init', 'tpgb_tp_smooth_scroll' );
