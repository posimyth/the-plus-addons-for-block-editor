<?php
/**
 * BlockQuote.
 *
 * @package ThePluginAddonsForBlockEditor
 */

defined( 'ABSPATH' ) || exit;

/**
 * Tpgb tp blockquote callback.
 *
 * @param mixed $attributes The attributes.
 * @param mixed $content The content.
 * @return mixed The result.
 */
function tpgb_tp_blockquote_callback( $attributes, $content ) {
	$output   = '';
	$block_id = ( ! empty( $attributes['block_id'] ) ) ? $attributes['block_id'] : uniqid( 'title' );

	$pattern = '/\btpgb-block-' . esc_attr( $block_id ) . '/';

	if ( preg_match( $pattern, $content ) ) {
		if ( class_exists( 'Tpgb_Blocks_Global_Options' ) ) {
			$global_blocks = Tpgb_Blocks_Global_Options::get_instance();
			$content       = $global_blocks::block_row_conditional_render( $attributes, $content );
		}
		return $content;
	}

	$quote_icon = ( ! empty( $attributes['quoteIcon'] ) ) ? $attributes['quoteIcon'] : '';
	$quotecnt   = ( ! empty( $attributes['content'] ) ) ? $attributes['content'] : '';

	if ( class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) {
		$quotecnt = ( ! empty( $attributes['content'] ) ) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_val( $attributes['content'], array( 'blockName' => 'tpgb/tp-blockquote' ) ) : '';
	}

	$block_class = Tp_Blocks_Helper::block_wrapper_classes( $attributes );
	$output      = '<div class="tp-blockquote tpgb-relative-block tpgb-block-' . esc_attr( $block_id ) . ' ' . esc_attr( $block_class ) . '">';
		$output .= '<div class="tpgb-blockquote-inner tpgb-quote-' . esc_attr( $attributes['style'] ) . '">';
	if ( 'style-2' === $attributes['style'] ) {
		$output .= '<span class="tpgb-quote-left"><i class=" ' . ( ! empty( $quote_icon ) ? $quote_icon : 'fa fa-quote-left' ) . ' " aria-hidden="true"></i></span>';
	}
			$output .= '<blockquote class="tpgb-quote-text">';
			$output .= '<div class="quote-text-wrap">' . wp_kses_post( $quotecnt ) . '</div>';
	if ( 'style-2' === $attributes['style'] && ! empty( $attributes['authorName'] ) ) {
		$output .= '<div class="tpgb-quote-author">' . wp_kses_post( $attributes['authorName'] ) . '</div>';
	}
			$output .= '</blockquote>';
		$output     .= '</div>';
	$output         .= '</div>';

	$output = Tpgb_Blocks_Global_Options::block_Wrap_Render( $attributes, $output );

	return $output;
}

/**
 * Tpgb tp blockquote render.
 */
function tpgb_tp_blockquote_render() {
	$block_data = Tpgb_Blocks_Global_Options::merge_options_json( __DIR__, 'tpgb_tp_blockquote_callback' );
	register_block_type( $block_data['name'], $block_data );
}
add_action( 'init', 'tpgb_tp_blockquote_render' );
