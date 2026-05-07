<?php
/**
 * Form Submit Button.
 *
 * @package ThePluginAddonsForBlockEditor
 */

defined( 'ABSPATH' ) || exit;

/**
 * Nxt form submit callback.
 *
 * @param mixed $attr The attr.
 * @param mixed $content The content.
 * @return mixed The result.
 */
function nxt_form_submit_callback( $attr, $content ) {
	$pattern = '/\btpgb-wrap-/';

	$form_id = ( isset( $attr['parentFormId'] ) && ! empty( $attr['parentFormId'] ) ? $attr['parentFormId'] : '' );
	ob_start();
	do_action( 'nexter_form_integrate' . esc_attr( $form_id ) );
	$output = ob_get_clean();

	if ( preg_match( $pattern, $content ) ) {
		$output .= $content;
		return $output;
	}

	$output .= $content;
	return Tpgb_Blocks_Global_Options::block_Wrap_Render( $attr, $output );
}

/**
 * Nxt form submit render.
 */
function nxt_form_submit_render() {

	$block_data = Tpgb_Blocks_Global_Options::merge_options_json( __DIR__, 'nxt_form_submit_callback' );
	register_block_type( $block_data['name'], $block_data );
}
add_action( 'init', 'nxt_form_submit_render' );
