<?php
/**
 * TP Column.
 *
 * @package ThePluginAddonsForBlockEditor
 */

defined( 'ABSPATH' ) || exit;

/**
 * Render for the server-side
 */
function tpgb_tp_grid() {

	$block_data = Tpgb_Blocks_Global_Options::merge_options_json( __DIR__, '' );
	register_block_type_from_metadata( __DIR__, array( 'attributes' => $block_data['attributes'] ) );
}
add_action( 'init', 'tpgb_tp_grid' );
