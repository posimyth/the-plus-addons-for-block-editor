<?php
/**
 * TP Column.
 *
 * @package ThePluginAddonsForBlockEditor
 */

defined( 'ABSPATH' ) || exit;

/**
 * Tpgb tp tab item render callback.
 *
 * @param mixed $attributes The attributes.
 * @param mixed $content The content.
 * @return mixed The result.
 */
function tpgb_tp_tab_item_render_callback( $attributes, $content ) {
	$pattern = '/\btpgb-tab-content/';

	if ( preg_match( $pattern, $content ) ) {
		if ( class_exists( 'Tpgb_Blocks_Global_Options' ) ) {
			$global_blocks = Tpgb_Blocks_Global_Options::get_instance();
			$content       = $global_blocks::block_row_conditional_render( $attributes, $content );
		}
		return $content;
	}
	$output      = '';
	$block_id    = ( ! empty( $attributes['block_id'] ) ) ? $attributes['block_id'] : uniqid( 'title' );
	$unique_key  = ( ! empty( $attributes['uniqueKey'] ) ) ? $attributes['uniqueKey'] : '';
	$tabto_index = ( ! empty( $attributes['tabtoIndex'] ) ) ? $attributes['tabtoIndex'] : '';

	$active = '';
	if ( 1 === $tabto_index ) {
		$active = ' active';
	}

	$output     .= '<div class="tpgb-tab-content ' . esc_attr( $active ) . '" data-tab="' . esc_attr( $tabto_index ) . '" role="tabpanel">';
		$output .= $content;
	$output     .= '</div>';

	return $output;
}

/**
 * Render for the server-side
 */
function tpgb_tp_tab_item() {
	$block_data = Tpgb_Blocks_Global_Options::merge_options_json( __DIR__, 'tpgb_tp_tab_item_render_callback' );
	register_block_type( $block_data['name'], $block_data );
}
add_action( 'init', 'tpgb_tp_tab_item' );
