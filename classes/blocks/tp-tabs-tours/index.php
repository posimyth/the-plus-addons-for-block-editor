<?php
/**
 * Tabs And Tours.
 *
 * @package ThePluginAddonsForBlockEditor
 */

defined( 'ABSPATH' ) || exit;

/**
 * Tpgb tp tabs tours render callback.
 *
 * @param mixed $attributes The attributes.
 * @param mixed $content The content.
 * @return mixed The result.
 */
function tpgb_tp_tabs_tours_render_callback( $attributes, $content ) {

	$block_id = ( ! empty( $attributes['block_id'] ) ) ? $attributes['block_id'] : '';
	$pattern  = '/\btpgb-block-' . esc_attr( $block_id ) . '/';

	if ( preg_match( $pattern, $content ) ) {
		if ( class_exists( 'Tpgb_Blocks_Global_Options' ) ) {
			$global_blocks = Tpgb_Blocks_Global_Options::get_instance();
			$content       = $global_blocks::block_row_conditional_render( $attributes, $content );
		}
		return $content;
	}
	$tab_layout       = ( ! empty( $attributes['tabLayout'] ) ) ? $attributes['tabLayout'] : 'horizontal';
	$nav_align        = ( ! empty( $attributes['navAlign'] ) ) ? $attributes['navAlign'] : 'text-center';
	$fullwidth_icon   = ( ! empty( $attributes['fullwidthIcon'] ) ) ? $attributes['fullwidthIcon'] : false;
	$nav_width        = ( ! empty( $attributes['navWidth'] ) ) ? $attributes['navWidth'] : false;
	$underline        = ( ! empty( $attributes['underline'] ) ) ? $attributes['underline'] : false;
	$tablist_repeater = ( ! empty( $attributes['tablistRepeater'] ) ) ? $attributes['tablistRepeater'] : array();
	$title_show       = ( ! empty( $attributes['titleShow'] ) ) ? $attributes['titleShow'] : false;
	$nav_position     = ( ! empty( $attributes['navPosition'] ) ) ? $attributes['navPosition'] : 'top';
	$vertical_align   = ( ! empty( $attributes['VerticalAlign'] ) ) ? $attributes['VerticalAlign'] : '';
	$tab_type         = ( ! empty( $attributes['tabType'] ) ) ? $attributes['tabType'] : '';
	$tabnav_resp      = ( ! empty( $attributes['tabnavResp'] ) ) ? $attributes['tabnavResp'] : '';
	$active_tab       = ( ! empty( $attributes['activeTab'] ) ) ? $attributes['activeTab'] : '1';
	$block_class      = Tp_Blocks_Helper::block_wrapper_classes( $attributes );

	$output      = '';
	$tab_nav     = '';
	$tab_content = '';

	// Set Full Width Icon Class.
	$full_icon_class = '';
	if ( true === $fullwidth_icon ) {
		$full_icon_class = 'full-width-icon';
	} else {
		$full_icon_class = 'normal-width-icon';
	}

	// Set class For full width Nav bar.
	$full_width_nav = '';
	if ( true === $nav_width ) {
		$full_width_nav = 'full-width';
	}

	// set class For UnderLine.
	$underline_class = '';
	if ( true === $underline ) {
		$underline_class = 'tab-underline';
	}

	// Set responsive class.
	$responsive_class = '';
	if ( 'nav_full' === $tabnav_resp ) {
		$responsive_class = 'nav-full-width';
	} elseif ( 'nav_one' === $tabnav_resp ) {
		$responsive_class = 'nav-one-by-one';
	} elseif ( 'tab_accordion' === $tabnav_resp ) {
		$responsive_class = 'mobile-accordion';
	}

	// Set Vertival TabAlign class.
	$alignclass = '';
	if ( 'top' === $vertical_align ) {
		$alignclass = 'align-top';
	} elseif ( 'center' === $vertical_align ) {
		$alignclass = 'align-center';
	} elseif ( 'bottom' === $vertical_align ) {
		$alignclass = 'align-bottom';
	}
	$i = 0;
	$j = 0;

	// Output for Tab Navigation.
	$nav_loop = '';
	if ( ! empty( $tablist_repeater ) ) {
		foreach ( $tablist_repeater as $index => $item ) :
			++$j;
			// Set active class.
			$active = '';
			if ( $j === $active_tab ) {
				$active = ' active';
			}

			$nav_loop     .= '<div class="tpgb-tab-li">';
				$nav_loop .= '<div id="' . ( ! empty( $item['uniqueId'] ) ? esc_attr( $item['uniqueId'] ) : 'tpag-tab-title-' . esc_attr( $block_id ) . esc_attr( $j ) ) . '" class="tpgb-tab-header tpgb-trans-linear ' . esc_attr( $active ) . '" data-tab="' . esc_attr( $j ) . '" role="tab" aria-controls="' . ( ! empty( $item['uniqueId'] ) ? esc_attr( $item['uniqueId'] ) : 'tpag-tab-title-' . esc_attr( $block_id ) . esc_attr( $j ) ) . '">';
			if ( ! empty( $item['innerIcon'] ) ) {
				$nav_loop .= '<span class="tab-icon-wrap">';
				if ( 'font_awesome' === $item['iconFonts'] ) {
						$nav_loop .= '<i class="tab-icon tpgb-trans-linear ' . esc_attr( $item['innericonName'] ) . '"> </i>';
				} elseif ( 'image' === $item['iconFonts'] ) {
					if ( ! empty( $item['iconImage']['id'] ) ) {
						$nav_loop .= wp_get_attachment_image( $item['iconImage']['id'], $item['iconimageSize'] );
					}
				}
								$nav_loop .= '</span>';
			}
			if ( ! empty( $title_show ) ) {
				$nav_loop .= '<span>' . wp_kses_post( $item['tabTitle'] ) . '</span>';
			}

				$nav_loop .= '</div>';
			$nav_loop     .= '</div>';

		endforeach;
	}
	$tab_nav         .= '<div class="tpgb-tabs-nav-wrapper ' . esc_attr( $nav_align ) . ' ' . ( 'vertical' === $tab_layout ? esc_attr( $alignclass ) : '' ) . ' ">';
		$tab_nav     .= '<div class="tpgb-tabs-nav tpgb-trans-linear ' . esc_attr( $full_icon_class ) . '  ' . esc_attr( $full_width_nav ) . ' ' . esc_attr( $underline_class ) . ' " role="tablist">';
			$tab_nav .= $nav_loop;
		$tab_nav     .= '</div>';
	$tab_nav         .= '</div>';

	// Output tab content.
	$content_loop = '';
	if ( ! empty( $tablist_repeater ) ) {
		if ( 'editor' === $tab_type ) {
			$content_loop .= $content;
		} else {
			foreach ( $tablist_repeater as $index => $item ) :
				++$i;

				// Set active class.
				$active = '';
				if ( $i === $active_tab ) {
					$active = ' active';
				}

				// Set Tab Title For responsive accordian.
				$content_loop .= '<div class="tab-mobile-title ' . esc_attr( $active ) . ' ' . esc_attr( $nav_align ) . '" data-tab="' . esc_attr( $i ) . '">';
				if ( ! empty( $item['innerIcon'] ) ) {
					$content_loop .= '<span class="tab-icon-wrap">';
					if ( 'font_awesome' === $item['iconFonts'] ) {
							$content_loop .= '<i class="tab-icon tpgb-trans-linear ' . esc_attr( $item['innericonName'] ) . '"> </i>';
					} elseif ( 'image' === $item['iconFonts'] ) {
						if ( ! empty( $item['iconImage']['id'] ) ) {
							$content_loop .= wp_get_attachment_image( $item['iconImage']['id'], $item['iconimageSize'] );
						}
					}
						$content_loop .= '</span>';
				}
					$content_loop .= '<span>' . wp_kses_post( $item['tabTitle'] ) . '</span>';
				$content_loop     .= '</div>';

				$content_loop     .= '<div id="tpag-tab-content-' . esc_attr( $block_id ) . esc_attr( $i ) . '" class="tpgb-tab-content ' . esc_attr( $active ) . '" data-tab="' . esc_attr( $i ) . '"  role="tabpanel" aria-labelledby="' . ( ! empty( $item['UniqueId'] ) ? esc_attr( $item['UniqueId'] ) : 'tpag-tab-title-' . esc_attr( $block_id ) . esc_attr( $i ) ) . '">';
					$content_loop .= '<div class ="tpgb-content-editor" >';
				if ( ! empty( $item['contentType'] ) && 'content' === $item['contentType'] ) {
					$content_loop .= wp_kses_post( $item['tabDescription'] );
				}
					$content_loop .= '</div>';
				$content_loop     .= '</div>';

			endforeach;
		}
	}

	$tab_content .= '<div class="tpgb-tabs-content-wrapper tpgb-trans-linear" >' . $content_loop . '</div>';

	$output     .= '<div class="tpgb-tabs-tours tpgb-block-' . esc_attr( $block_id ) . '  tab-view-' . esc_attr( $tab_layout ) . ' ' . esc_attr( $block_class ) . ' ' . esc_attr( $responsive_class ) . '">';
		$output .= '<div class="tpgb-tabs-wrapper tpgb-relative-block ' . esc_attr( $responsive_class ) . ' "    data-tab-default="1" data-tab-hover="no" >';
	if ( 'top' === $nav_position || 'left' === $nav_position ) {
		$output .= $tab_nav . $tab_content;
	} else {
		$output .= $tab_content . $tab_nav;
	}
		$output .= '</div>';
	$output     .= '</div>';

	$output = Tpgb_Blocks_Global_Options::block_Wrap_Render( $attributes, $output );

	return $output;
}

/**
 * Render for the server-side
 */
function tpgb_tp_tabs_tours() {
	$block_data = Tpgb_Blocks_Global_Options::merge_options_json( __DIR__, 'tpgb_tp_tabs_tours_render_callback' );
	register_block_type( $block_data['name'], $block_data );
}
add_action( 'init', 'tpgb_tp_tabs_tours' );
