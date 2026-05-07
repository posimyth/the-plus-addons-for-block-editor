<?php
/**
 * Accordion.
 *
 * @package ThePluginAddonsForBlockEditor
 */

defined( 'ABSPATH' ) || exit;

/**
 * Tpgb tp accordion render callback.
 *
 * @param mixed $attributes The attributes.
 * @param mixed $content The content.
 * @return mixed The result.
 */
function tpgb_tp_accordion_render_callback( $attributes, $content ) {
	$output   = '';
	$block_id = ( ! empty( $attributes['block_id'] ) ) ? $attributes['block_id'] : uniqid( 'title' );
	$pattern  = '/\btpgb-block-' . esc_attr( $block_id ) . '/';

	if ( preg_match( $pattern, $content ) ) {
		if ( class_exists( 'Tpgb_Blocks_Global_Options' ) ) {
			$global_blocks = Tpgb_Blocks_Global_Options::get_instance();
			$content       = $global_blocks::block_row_conditional_render( $attributes, $content );
		}
		return $content;
	}
	$accordian_list = ( ! empty( $attributes['accordianList'] ) ) ? $attributes['accordianList'] : array();
	$title_align    = ( ! empty( $attributes['titleAlign'] ) ) ? $attributes['titleAlign'] : 'text-left';
	$toggle_icon    = ( ! empty( $attributes['toggleIcon'] ) ) ? $attributes['toggleIcon'] : false;
	$icon_font      = ( ! empty( $attributes['iconFont'] ) ) ? $attributes['iconFont'] : 'font_awesome';
	$icon_name      = ( ! empty( $attributes['iconName'] ) ) ? $attributes['iconName'] : 'fas fa-plus';
	$acticon_name   = ( ! empty( $attributes['ActiconName'] ) ) ? $attributes['ActiconName'] : 'fas fa-minus';
	$icon_align     = ( ! empty( $attributes['iconAlign'] ) ) ? $attributes['iconAlign'] : 'end';
	$title_tag      = ( ! empty( $attributes['titleTag'] ) ) ? $attributes['titleTag'] : 'h3';
	$accor_type     = ( ! empty( $attributes['accorType'] ) ) ? $attributes['accorType'] : '';
	$desc_align     = ( ! empty( $attributes['descAlign'] ) ) ? $attributes['descAlign'] : '';

	$i           = 0;
	$block_class = Tp_Blocks_Helper::block_wrapper_classes( $attributes );

	// Get Toogle icon.
	$tgicon = '';
	if ( ! empty( $toggle_icon ) ) {
		$tgicon     .= '<div class="accordion-toggle-icon">';
			$tgicon .= '<span class="close-toggle-icon toggle-icon">';
		if ( 'font_awesome' === $icon_font ) {
			$tgicon .= '<i class="' . esc_attr( $icon_name ) . '"> </i>';
		}
			$tgicon .= '</span>';
			$tgicon .= '<span class="open-toggle-icon toggle-icon">';
		if ( 'font_awesome' === $icon_font ) {
			$tgicon .= '<i class="' . esc_attr( $acticon_name ) . '"> </i>';
		}
			$tgicon .= '</span>';
		$tgicon     .= '</div>';
	}

	$loop_content = '';
	if ( ! empty( $accordian_list ) ) {
		foreach ( $accordian_list as $index => $item ) :
			++$i;

			// set active class.
			$active = '';
			if ( 0 === $i ) {
				$active = 'active';
			}

			$loop_content     .= '<div class="tpgb-accor-item tpgb-relative-block ' . esc_attr( $active ) . '">';
				$loop_content .= '<div id="' . ( ! empty( $item['UniqueId'] ) ? esc_attr( $item['UniqueId'] ) : 'tpag-tab-title-' . esc_attr( $block_id ) . esc_attr( $i ) ) . '" class="tpgb-accordion-header tpgb-trans-linear-before ' . esc_attr( $title_align ) . ' ' . esc_attr( $active ) . '" role="tab" data-tab="' . esc_attr( $i ) . '" aria-controls="' . ( ! empty( $item['UniqueId'] ) ? esc_attr( $item['UniqueId'] ) : 'tpag-tab-title-' . esc_attr( $block_id ) . esc_attr( $i ) ) . '">';
			if ( 'start' === $icon_align ) {
				$loop_content .= $tgicon;
			}
					$loop_content .= '<span class="accordion-title-icon-wrap">';
			if ( ! empty( $item['innerIcon'] ) ) {
				$loop_content .= '<span class="accordion-tab-icon">';
				if ( 'font_awesome' === $item['iconFonts'] ) {
							$loop_content .= '<i class="' . esc_attr( $item['innericonName'] ) . '"></i>';
				}
							$loop_content .= '</span>';
			}
						$loop_content .= '<' . Tp_Blocks_Helper::validate_html_tag( $title_tag ) . ' class="accordion-title"> ' . wp_kses_post( $item['title'] ) . '</' . Tp_Blocks_Helper::validate_html_tag( $title_tag ) . '>';
					$loop_content     .= '</span>';

			if ( 'end' === $icon_align ) {
				$loop_content .= $tgicon;
			}
				$loop_content .= '</div>';

				$loop_content     .= '<div id="tpag-tab-content-' . esc_attr( $block_id ) . esc_attr( $i ) . '" class="tpgb-accordion-content ' . esc_attr( $active ) . '" role="tabpanel" data-tab="' . esc_attr( $i ) . '" aria-labelledby="' . ( ! empty( $item['UniqueId'] ) ? esc_attr( $item['UniqueId'] ) : 'tpag-tab-title-' . esc_attr( $block_id ) . esc_attr( $i ) ) . '">';
					$loop_content .= '<div class="tpgb-content-editor ' . esc_attr( $desc_align ) . '">';
			if ( ! empty( $item['contentType'] ) && 'content' === $item['contentType'] ) {
				$loop_content .= wp_kses_post( $item['desc'] );
			}
					$loop_content .= '</div>';
				$loop_content     .= '</div>';
			$loop_content         .= '</div>';
		endforeach;
	}

	$output     .= '<div class="tpgb-accordion tpgb-block-' . esc_attr( $block_id ) . ' ' . esc_attr( $block_class ) . '">';
		$output .= '<div class="tpgb-accor-wrap" data-type="accordion" role="tablist">';
	if ( 'editor' === $accor_type ) {
		$output .= $content;
	} else {
		$output .= $loop_content;
	}
		$output .= '</div>';
	$output     .= '</div>';

	$output = Tpgb_Blocks_Global_Options::block_Wrap_Render( $attributes, $output );

	return $output;
}

/**
 * Render for the server-side
 */
function tpgb_tp_accordion() {
	$block_data = Tpgb_Blocks_Global_Options::merge_options_json( __DIR__, 'tpgb_tp_accordion_render_callback' );
	register_block_type( $block_data['name'], $block_data );
}


add_action( 'init', 'tpgb_tp_accordion' );
