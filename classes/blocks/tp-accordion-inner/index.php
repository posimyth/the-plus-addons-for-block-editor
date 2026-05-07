<?php
/**
 * TP Accordion Inner.
 *
 * @package ThePluginAddonsForBlockEditor
 */

defined( 'ABSPATH' ) || exit;

/**
 * Tpgb tp accr inner render callback.
 *
 * @param mixed $attributes The attributes.
 * @param mixed $content The content.
 * @return mixed The result.
 */
function tpgb_tp_accr_inner_render_callback( $attributes, $content ) {
	$pattern = '/\btpgb-accor-item/';

	if ( preg_match( $pattern, $content ) ) {
		if ( class_exists( 'Tpgb_Blocks_Global_Options' ) ) {
			$global_blocks = Tpgb_Blocks_Global_Options::get_instance();
			$content       = $global_blocks::block_row_conditional_render( $attributes, $content );
		}
		return $content;
	}
	$output         = '';
	$block_id       = ( ! empty( $attributes['block_id'] ) ) ? $attributes['block_id'] : uniqid( 'title' );
	$title          = ( ! empty( $attributes['title'] ) ) ? $attributes['title'] : '';
	$toggle_icon    = ( ! empty( $attributes['toggleIcon'] ) ) ? $attributes['toggleIcon'] : false;
	$inner_icon     = ( ! empty( $attributes['innerIcon'] ) ) ? $attributes['innerIcon'] : false;
	$inicon_fonts   = ( ! empty( $attributes['iniconFonts'] ) ) ? $attributes['iniconFonts'] : '';
	$innericon_name = ( ! empty( $attributes['innericonName'] ) ) ? $attributes['innericonName'] : '';
	$icon_font      = ( ! empty( $attributes['iconFont'] ) ) ? $attributes['iconFont'] : 'font_awesome';
	$icon_name      = ( ! empty( $attributes['iconName'] ) ) ? $attributes['iconName'] : 'fas fa-plus';
	$acticon_name   = ( ! empty( $attributes['ActiconName'] ) ) ? $attributes['ActiconName'] : 'fas fa-minus';
	$icon_align     = ( ! empty( $attributes['iconAlign'] ) ) ? $attributes['iconAlign'] : 'end';
	$title_tag      = ( ! empty( $attributes['titleTag'] ) ) ? $attributes['titleTag'] : 'h3';
	$title_align    = ( ! empty( $attributes['titleAlign'] ) ) ? $attributes['titleAlign'] : '';
	$href_link      = ( ! empty( $attributes['hrefLink'] ) ) ? $attributes['hrefLink'] : '';
	$index          = ( ! empty( $attributes['index'] ) ) ? $attributes['index'] : '';
	$block_class    = Tp_Blocks_Helper::block_wrapper_classes( $attributes );
	// Get Toogle icon.
	$tgicon = '';
	if ( ! empty( $toggle_icon ) ) {
		$tgicon     .= '<div class="accordion-toggle-icon">';
			$tgicon .= '<span class="close-toggle-icon  toggle-icon">';
		if ( 'font_awesome' === $icon_font ) {
			$tgicon .= '<i class="' . esc_attr( $icon_name ) . '"> </i>';
		}
			$tgicon .= '</span>';
			$tgicon .= '<span class="open-toggle-icon  toggle-icon">';
		if ( 'font_awesome' === $icon_font ) {
			$tgicon .= '<i class="' . esc_attr( $acticon_name ) . '"> </i>';
		}
			$tgicon .= '</span>';
		$tgicon     .= '</div>';
	}

	$output     .= '<div class="tpgb-accor-item tpgb-relative-block ' . esc_attr( $block_class ) . '">';
		$output .= '<div id="' . esc_attr( $href_link ) . '" class="tpgb-accordion-header tpgb-trans-linear-before ' . esc_attr( $title_align ) . '" role="tab" data-tab="' . esc_attr( $index ) . '" >';
	if ( 'start' === $icon_align ) {
		$output .= $tgicon;
	}
			$output .= '<span class="accordion-title-icon-wrap">';
	if ( ! empty( $inner_icon ) ) {
		$output .= '<span class="accordion-tab-icon">';
		if ( 'font_awesome' === $inicon_fonts ) {
					$output .= '<i class="' . esc_attr( $innericon_name ) . '"></i>';
		}
					$output .= '</span>';
	}
				$output .= '<' . Tp_Blocks_Helper::validate_html_tag( $title_tag ) . ' class="accordion-title">' . wp_kses_post( $title ) . '</' . Tp_Blocks_Helper::validate_html_tag( $title_tag ) . ' > ';
			$output     .= '</span>';
	if ( 'end' === $icon_align ) {
		$output .= $tgicon;
	}
		$output         .= '</div>';
		$output         .= '<div class="tpgb-accordion-content" role="tabpanel" data-tab="' . esc_attr( $index ) . '">';
			$output     .= '<div class="tpgb-content-editor">';
				$output .= $content;
			$output     .= '</div>';
		$output         .= '</div>';
	$output             .= '</div>';

	return $output;
}

/**
 * Render for the server-side
 */
function tpgb_tp_accr_inner() {
	$block_data = Tpgb_Blocks_Global_Options::merge_options_json( __DIR__, 'tpgb_tp_accr_inner_render_callback' );
	register_block_type( $block_data['name'], $block_data );
}
add_action( 'init', 'tpgb_tp_accr_inner' );
