<?php
/**
 * Core Button.
 *
 * @package ThePluginAddonsForBlockEditor
 */

defined( 'ABSPATH' ) || exit;


/**
 * Tpgb tp button core render.
 *
 * @param mixed $attr The attr.
 * @param mixed $content The content.
 * @return mixed The result.
 */
function tpgb_tp_button_core_render( $attr, $content ) {
	$pattern = '/\btpgb-wrap-/';

	if ( preg_match( $pattern, $content ) ) {
		if ( class_exists( 'Tpgb_Blocks_Global_Options' ) ) {
			$global_blocks = Tpgb_Blocks_Global_Options::get_instance();
			$content       = $global_blocks::block_row_conditional_render( $attr, $content );
		}
		return $content;
	}

	return Tpgb_Blocks_Global_Options::block_Wrap_Render( $attr, $content );
}

/**
 * Tpgb tp button core.
 */
function tpgb_tp_button_core() {
	$block_data = Tpgb_Blocks_Global_Options::merge_options_json( __DIR__, 'tpgb_tp_button_core_render' );
	register_block_type( $block_data['name'], $block_data );
}
add_action( 'init', 'tpgb_tp_button_core' );
