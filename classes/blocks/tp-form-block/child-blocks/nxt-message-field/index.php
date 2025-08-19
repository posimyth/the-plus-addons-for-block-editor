<?php
/* Block : Core Heading
 * @since : 2.0.0
 */
defined( 'ABSPATH' ) || exit;

function nxt_form_message_callback($attr, $content) {
	$pattern = '/\btpgb-wrap-/';
    
    if (preg_match($pattern, $content)) {
       return $content;
    }
	return Tpgb_Blocks_Global_Options::block_Wrap_Render($attr, $content);
}

function nxt_form_message_render() {
   
    $block_data = Tpgb_Blocks_Global_Options::merge_options_json(__DIR__, 'nxt_form_message_callback');
	register_block_type( $block_data['name'], $block_data );
}
add_action( 'init', 'nxt_form_message_render' );