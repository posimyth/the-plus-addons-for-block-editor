<?php
/**
 * TP Column.
 *
 * @package ThePluginAddonsForBlockEditor
 */

defined( 'ABSPATH' ) || exit;

$output         = '';
$block_id       = ( ! empty( $attributes['block_id'] ) ) ? $attributes['block_id'] : uniqid( 'title' );
$custom_classes = ( ! empty( $attributes['customClasses'] ) ) ? $attributes['customClasses'] : '';
$wrap_link      = ( ! empty( $attributes['wrapLink'] ) ) ? $attributes['wrapLink'] : false;
$showchild      = ( ! empty( $attributes['showchild'] ) ) ? $attributes['showchild'] : false;
$block_class    = Tp_Blocks_Helper::block_wrapper_classes( $attributes );

if ( ! empty( $custom_classes ) ) {
	$block_class .= ' ' . esc_attr( $custom_classes );
}

// Set Link Data.
$col_link = '';
if ( ! empty( $wrap_link ) ) {
	$col_url      = ( ! empty( $attributes['colUrl'] ) ) ? $attributes['colUrl'] : '';
	$block_class .= ' tpgb-col-link';

	if ( ! empty( $col_url ) && isset( $col_url['url'] ) && ! empty( $col_url['url'] ) ) {
		$col_link .= ' data-tpgb-col-link= "' . esc_url( $col_url['url'] ) . '" ';
	}
	if ( ! empty( $col_url ) && isset( $col_url['target'] ) && ! empty( $col_url['target'] ) ) {
		$col_link .= ' data-target="_blank"';
	} else {
		$col_link .= ' data-target="_self"';
	}
	$col_link .= Tp_Blocks_Helper::add_link_attributes( $attributes['colUrl'] );
}

$output     .= '<div class="tpgb-container-col tpgb-block-' . esc_attr( $block_id ) . ' ' . esc_attr( $block_class ) . '" data-id="' . esc_attr( $block_id ) . '" ' . $col_link . ' >';
	$output .= $content;
$output     .= '</div>';

echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- assembled from individually escaped parts above.
