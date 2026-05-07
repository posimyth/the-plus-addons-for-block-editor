<?php
/**
 * Core Heading.
 *
 * @package ThePluginAddonsForBlockEditor
 */

defined( 'ABSPATH' ) || exit;

/**
 * Nxt form radio callback.
 *
 * @param mixed $attr The attr.
 * @param mixed $content The content.
 * @return mixed The result.
 */
function nxt_form_radio_callback( $attr, $content ) {
	$pattern = '/\btpgb-wrap-/';

	if ( preg_match( $pattern, $content ) ) {
		return $content;
	}
	return Tpgb_Blocks_Global_Options::block_Wrap_Render( $attr, $content );
}

/**
 * Nxt form radio render.
 */
function nxt_form_radio_render() {

	$block_data = Tpgb_Blocks_Global_Options::merge_options_json( __DIR__, 'nxt_form_radio_callback' );
	register_block_type( $block_data['name'], $block_data );
}
add_action( 'init', 'nxt_form_radio_render' );
