<?php
/**
 * Container(Section).
 *
 * @package ThePluginAddonsForBlockEditor
 */

defined( 'ABSPATH' ) || exit;

$output       = '';
$block_id     = ( ! empty( $attributes['block_id'] ) ) ? $attributes['block_id'] : uniqid( 'title' );
$height       = ( ! empty( $attributes['height'] ) ) ? $attributes['height'] : '';
$custom_class = ( ! empty( $attributes['customClass'] ) ) ? $attributes['customClass'] : '';

$custom_id = ( ! empty( $attributes['customId'] ) ) ? 'id="' . esc_attr( $attributes['customId'] ) . '"' : ( isset( $attributes['anchor'] ) && ! empty( $attributes['anchor'] ) ? 'id="' . esc_attr( $attributes['anchor'] ) . '"' : '' );

$wrap_link = ( ! empty( $attributes['wrapLink'] ) ) ? $attributes['wrapLink'] : false;

$showchild     = ( ! empty( $attributes['showchild'] ) ) ? $attributes['showchild'] : false;
$content_width = ( ! empty( $attributes['contentWidth'] ) ) ? $attributes['contentWidth'] : 'wide';
$col_dir       = ( ! empty( $attributes['colDir'] ) ) ? $attributes['colDir'] : '';
$tag_name      = ( ! empty( $attributes['tagName'] ) ) ? $attributes['tagName'] : '';
$block_class   = Tp_Blocks_Helper::block_wrapper_classes( $attributes );

$selected_layout = ( ! empty( $attributes['selectedLayout'] ) ) ? $attributes['selectedLayout'] : '';
$nxtcont_type    = ( ! empty( $attributes['nxtcontType'] ) ) ? $attributes['nxtcontType'] : false;
$contwid_full    = ( ! empty( $attributes['contwidFull'] ) ) ? $attributes['contwidFull'] : '';

// Equal Height.
$equal_height_attr = Tp_Blocks_Helper::global_equal_height( $attributes );

$section_class = '';
if ( ! empty( $height ) ) {
	$section_class .= ' tpgb-section-height-' . esc_attr( $height );
}

if ( defined( 'NXT_VERSION' ) && 'full' !== $content_width && ! empty( $nxtcont_type ) ) {
	$section_class .= ' tpgb-nxtcont-type';
}

if ( ! empty( $equal_height_attr ) ) {
	$section_class .= ' tpgb-equal-height';
}
// Toogle Class For wrapper Link.

$linkdata = '';
if ( ! empty( $wrap_link ) ) {
	$row_url        = ( ! empty( $attributes['rowUrl'] ) ) ? $attributes['rowUrl'] : '';
	$section_class .= ' tpgb-row-link';

	if ( ! empty( $row_url ) && isset( $row_url['url'] ) && ! empty( $row_url['url'] ) ) {
		$linkdata .= 'data-tpgb-row-link="' . esc_url( $row_url['url'] ) . '" ';
	}
	if ( ! empty( $row_url ) && isset( $row_url['target'] ) && ! empty( $row_url['target'] ) ) {
		$linkdata .= 'data-target="_blank" ';
	} else {
		$linkdata .= 'data-target="_self" ';
	}
	$linkdata .= Tp_Blocks_Helper::add_link_attributes( $attributes['rowUrl'] );
}

$output .= '<' . Tp_Blocks_Helper::validate_html_tag( $tag_name ) . ' ' . $custom_id . ' class="tpgb-container-row tpgb-block-' . esc_attr( $block_id ) . ' ' . esc_attr( $section_class ) . ' ' . esc_attr( $custom_class ) . ' ' . esc_attr( $block_class ) . ' ' . ( 'c100' === $col_dir || 'r100' === $col_dir ? ' tpgb-container-inline' : '' ) . '  tpgb-container-' . esc_attr( $content_width ) . ' ' . ( 'grid' === $selected_layout ? 'tpgb-grid' : '' ) . '" data-id="' . esc_attr( $block_id ) . ' " ' . $linkdata . ' ' . $equal_height_attr . ' >';

// top layer Div.
if ( isset( $attributes['topOption'] ) && '' !== $attributes['topOption'] ) {
	$output .= '<div class="tpgb-top-layer"></div>';
}

if ( 'wide' === $content_width ) {
	$output .= '<div class="tpgb-cont-in">';
}
	$output .= $content;

if ( 'wide' === $content_width ) {
	$output .= '</div>';
}

$output .= '</' . Tp_Blocks_Helper::validate_html_tag( $tag_name ) . '>';


if ( class_exists( 'Tpgb_Blocks_Global_Options' ) ) {
	$global_block = Tpgb_Blocks_Global_Options::get_instance();
	if ( ! empty( $global_block ) && is_callable( array( $global_block, 'block_row_conditional_render' ) ) ) {
		$output = Tpgb_Blocks_Global_Options::block_row_conditional_render( $attributes, $output );
	}
}

echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- assembled from individually escaped parts above.
