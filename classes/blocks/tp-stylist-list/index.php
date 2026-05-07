<?php
/**
 * Stylist List.
 *
 * @package ThePluginAddonsForBlockEditor
 */

defined( 'ABSPATH' ) || exit;

/**
 * Tpgb tp stylist list render callback.
 *
 * @param mixed $attributes The attributes.
 * @param mixed $content The content.
 * @return mixed The result.
 */
function tpgb_tp_stylist_list_render_callback( $attributes, $content ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
	$output               = '';
	$block_id             = ( ! empty( $attributes['block_id'] ) ) ? $attributes['block_id'] : uniqid( 'title' );
	$alignment            = ( ! empty( $attributes['alignment'] ) ) ? $attributes['alignment'] : 'align-left';
	$icon_alignment       = ( ! empty( $attributes['iconAlignment'] ) ) ? $attributes['iconAlignment'] : false;
	$lists_repeater       = ( ! empty( $attributes['listsRepeater'] ) ) ? $attributes['listsRepeater'] : array();
	$hover_bg_style       = ( ! empty( $attributes['hover_bg_style'] ) ) ? $attributes['hover_bg_style'] : false;
	$pin_alignment        = ( ! empty( $attributes['pinAlignment'] ) ) ? $attributes['pinAlignment'] : 'right';
	$hover_inverse_effect = ( ! empty( $attributes['hoverInverseEffect'] ) ) ? $attributes['hoverInverseEffect'] : false;

	$read_more_toggle = ( ! empty( $attributes['readMoreToggle'] ) ) ? $attributes['readMoreToggle'] : false;
	$show_list_toggle = ( ! empty( $attributes['showListToggle'] ) ) ? (int) $attributes['showListToggle'] : 3;
	$read_more_text   = ( ! empty( $attributes['readMoreText'] ) ) ? $attributes['readMoreText'] : '';
	$read_less_text   = ( ! empty( $attributes['readLessText'] ) ) ? $attributes['readLessText'] : '';
	$effect_area      = ( ! empty( $attributes['effectArea'] ) ) ? $attributes['effectArea'] : 'individual';
	$global_id        = ( ! empty( $attributes['globalId'] ) ) ? $attributes['globalId'] : '';

	$head_svg_color       = ( ! empty( $attributes['headSvgColor'] ) ) ? $attributes['headSvgColor'] : '';
	$head_svgfill         = ( ! empty( $attributes['headSvgfill'] ) ) ? $attributes['headSvgfill'] : '';
	$head_svg_hover_color = ( ! empty( $attributes['svgstroHov'] ) ) ? $attributes['svgstroHov'] : '';
	$head_svg_hover_fill  = ( ! empty( $attributes['svgfillHov'] ) ) ? $attributes['svgfillHov'] : '';

	$block_class = Tp_Blocks_Helper::block_wrapper_classes( $attributes );

	$alignattr = '';
	if ( '' !== $alignment ) {
		$alignattr .= ( ! empty( $alignment['md'] ) ) ? ' align-' . $alignment['md'] : ' align-left';
		$alignattr .= ( ! empty( $alignment['sm'] ) ) ? ' tablet-align-' . $alignment['sm'] : '';
		$alignattr .= ( ! empty( $alignment['xs'] ) ) ? ' mobile-align-' . $alignment['xs'] : '';
	}

	$iconalignattr = ( ! empty( $icon_alignment ) ) ? ' d-flex-center' : ' d-flex-top';

	$hover_invert_class = '';
	$inver_attr         = '';
	if ( $hover_inverse_effect ) {
		$hover_invert_class .= ( 'global' === $effect_area ) ? ' hover-inverse-effect-global' : ' hover-inverse-effect';
		$hover_invert_class .= ( 'global' === $effect_area && ! empty( $global_id ) ) ? ' hover-' . $global_id : '';
		$inver_attr         .= ( 'global' === $effect_area && ! empty( $global_id ) ) ? 'data-hover-inverse = hover-' . esc_attr( $global_id ) . '' : '';
	}

	$i = 0;
	$j = 0;

	$output .= '<div class="tpgb-stylist-list tpgb-relative-block tpgb-block-' . esc_attr( $block_id ) . ' ' . esc_attr( $alignattr ) . ' ' . esc_attr( $block_class ) . ' ' . esc_attr( $hover_invert_class ) . '" ' . $inver_attr . '>';
	if ( ! empty( $lists_repeater ) ) {

		if ( $hover_bg_style ) {

			$output .= '<div class="tpgb-bg-hover-effect">';
			foreach ( $lists_repeater as $index => $item ) :
				$active = '';
				if ( 0 === $j ) {
					$active = ' active';
				}
				$output .= '<div class="hover-item-content tp-repeater-item-' . esc_attr( $item['_key'] ) . esc_attr( $active ) . '"></div>';
				++$j;
					endforeach;
				$output .= '</div>';
		}

		$output .= '<div class="tpgb-icon-list-items' . esc_attr( $iconalignattr ) . '">';
		foreach ( $lists_repeater as $index => $item ) :

			++$i;
			$active_class  = '';
			$descurl_open  = '';
			$descurl_close = '';
			if ( 1 === $i ) {
				$active_class = 'active';
			}
			// Url.
			if ( ! empty( $item['descurl'] ) && ! empty( $item['descurl']['url'] ) ) {
				$descurl       = ( isset( $item['descurl']['dynamic'] ) && class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_repeat_url( $item['descurl'] ) : ( ! empty( $item['descurl']['url'] ) ? $item['descurl']['url'] : '' );
				$target        = ( '' !== $item['descurl']['target'] ) ? 'target="_blank"' : '';
				$nofollow      = ( '' !== $item['descurl']['nofollow'] ) ? 'rel="nofollow"' : '';
				$link_attr     = Tp_Blocks_Helper::add_link_attributes( $item['descurl'] );
				$descurl_open  = '<a href="' . esc_url( $descurl ) . '" ' . $target . ' ' . $nofollow . ' ' . $link_attr . '>';
				$descurl_close = '</a>';
			}

			// Icon.
			$icons = '';
			if ( ! empty( $item['selectIcon'] ) ) {
				$icons .= '<div class="tpgb-icon-list-icon">';
				if ( 'fontawesome' === $item['selectIcon'] && ! empty( $item['iconFontawesome'] ) ) {
					$icons .= '<i class="list-icon ' . esc_attr( $item['iconFontawesome'] ) . '" aria-hidden="true"></i>';
				} elseif ( 'img' === $item['selectIcon'] && ! empty( $item['iconImg']['url'] ) ) {
					$img_src  = '';
					$alt_text = ( isset( $item['iconImg']['alt'] ) && ! empty( $item['iconImg']['alt'] ) ) ? esc_attr( $item['iconImg']['alt'] ) : ( ( ! empty( $item['iconImg']['title'] ) ) ? esc_attr( $item['iconImg']['title'] ) : esc_attr__( 'Icon Image', 'the-plus-addons-for-block-editor' ) );
					if ( ! empty( $item['iconImg'] ) && ! empty( $item['iconImg']['id'] ) ) {
						$img_src = wp_get_attachment_image( $item['iconImg']['id'], 'full', false, array( 'alt' => $alt_text ) );
					} elseif ( ! empty( $item['iconImg']['url'] ) ) {
						$imgurl = ( class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_repeat_url( $item['iconImg'] ) : '';

						if ( empty( $imgurl ) ) {
							$imgurl = $item['iconImg']['url'];
						}

						$img_src = '<img src="' . esc_url( $imgurl ) . '"  alt="' . $alt_text . '" />';
					}
					$icons .= $img_src;
				} elseif ( 'svg' === $item['selectIcon'] && ! empty( $item['svgIcon']['url'] ) ) {
					$svg_src  = '';
					$alt_text = ( isset( $item['svgIcon']['alt'] ) && ! empty( $item['svgIcon']['alt'] ) ) ? esc_attr( $item['svgIcon']['alt'] ) : ( ( ! empty( $item['svgIcon']['title'] ) ) ? esc_attr( $item['svgIcon']['title'] ) : esc_attr__( 'Icon Image', 'the-plus-addons-for-block-editor' ) );

					$svg_attachment_id = ! empty( $item['svgIcon']['id'] ) ? $item['svgIcon']['id'] : '';
					$item_key          = ! empty( $item['_key'] ) ? $item['_key'] : $index;
					$unique_svg_id     = 'wp-svg-' . $svg_attachment_id . '-' . $item_key;

					if ( ! empty( $item['svgIcon'] ) && ! empty( $item['svgIcon']['id'] ) ) {
						$svgurl = wp_get_attachment_url( $item['svgIcon']['id'] );

						$svg_src = '<object id="' . esc_attr( $unique_svg_id ) . '" class="tpgb-stylist-list-svg" type="image/svg+xml" data="' . esc_url( $svgurl ) . '" aria-label="' . esc_attr( $alt_text ) . '"></object>';

					} elseif ( ! empty( $item['svgIcon']['url'] ) ) {
						$svgurl = ( class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_repeat_url( $item['svgIcon'] ) : '';

							$svg_src = '<object id="' . esc_attr( $unique_svg_id ) . '" class="tpgb-stylist-list-svg" type="image/svg+xml" data="' . esc_url( $svgurl ) . '" aria-label="' . esc_attr( $alt_text ) . '"></object>';
					}
						$icons     .= '<div class="tpgb-draw-svg" data-id="' . esc_attr( $unique_svg_id ) . '" data-type="delayed" data-duration="1" data-stroke="' . esc_attr( $head_svg_color ) . '" data-fillColor="' . esc_attr( $head_svgfill ) . '" data-strokeColorHover="' . esc_attr( $head_svg_hover_color ) . '" data-fillColorHover="' . esc_attr( $head_svg_hover_fill ) . '" data-fillEnable="yes">';
							$icons .= $svg_src;
						$icons     .= '</div>';
				}
						$icons .= '</div>';
			}

				// Description and Pin.
				$itemdesc = '';
			if ( ! empty( $item['description'] ) ) {
				$pin_hint  = ( ! empty( $item['pinHint'] ) && ! empty( $item['hintText'] ) ) ? ' pin-hint-inline' : '';
				$itemdesc .= '<div class="tpgb-icon-list-text' . esc_attr( $pin_hint ) . '"><p>' . wp_kses_post( $item['description'] ) . '</p>';
				if ( ! empty( $item['pinHint'] ) && ! empty( $item['hintText'] ) ) {
					$itemdesc .= '<span class="tpgb-hint-text ' . esc_attr( $pin_alignment ) . '">' . wp_kses_post( $item['hintText'] ) . '</span>';
				}
				$itemdesc .= '</div>';
			}

				$tooltipdata  = '';
				$content_item = array();
			if ( ! empty( $item['itemTooltip'] ) ) {

				if ( class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) {
					$content_item['content'] = ( ! empty( $item['tooltipText'] ) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_val( $item['tooltipText'] ) : '' );
				} else {
					$content_item['content'] = ( ! empty( $item['tooltipText'] ) ? $item['tooltipText'] : '' );
				}

				$content_item['trigger']  = ( ! empty( $attributes['tipTriggers'] ) ? $attributes['tipTriggers'] : 'mouseenter' );
				$content_item['MaxWidth'] = ( ! empty( $attributes['tipMaxWidth'] ) ? (int) $attributes['tipMaxWidth'] : 'none' );
				$content_item             = htmlspecialchars( wp_json_encode( $content_item ), ENT_QUOTES, 'UTF-8' );
				$tooltipdata              = 'data-tooltip-opt= \'' . $content_item . '\'';
			}

				// Tooltip.
				$itemtooltip     = '';
				$tooltip_trigger = '';
				$uniqid          = uniqid( 'tooltip' );
			if ( ! empty( $item['itemTooltip'] ) ) {
				$itemtooltip .= ' data-tippy=""';
				$itemtooltip .= ' data-tippy-interactive="' . ( ! empty( $attributes['tipInteractive'] ) ? 'true' : 'false' ) . '"';
				$itemtooltip .= ' data-tippy-placement="' . ( ! empty( $attributes['tipPlacement'] ) ? $attributes['tipPlacement'] : 'top' ) . '"';
				$itemtooltip .= ' data-tippy-theme="' . $attributes['tipTheme'] . '"';
				$itemtooltip .= ' data-tippy-arrow="' . ( ! empty( $attributes['tipArrow'] ) ? 'true' : 'false' ) . '"';
				$itemtooltip .= ' data-tippy-followCursor="' . ( ! empty( $attributes['followCursor'] ) ? 'true' : 'false' ) . '" ';
				$itemtooltip .= ' data-tippy-animation="' . ( ! empty( $attributes['tipAnimation'] ) ? $attributes['tipAnimation'] : 'fade' ) . '"';
				$itemtooltip .= ' data-tippy-offset="[' . ( ! empty( $attributes['tipOffset'] ) ? (int) $attributes['tipOffset'] : 0 ) . ',' . ( ! empty( $attributes['tipDistance'] ) ? (int) $attributes['tipDistance'] : 0 ) . ']"';
				$itemtooltip .= ' data-tippy-duration="[' . ( ! empty( $attributes['tipDurationIn'] ) ? (int) $attributes['tipDurationIn'] : '1' ) . ',' . ( ! empty( $attributes['tipDurationOut'] ) ? (int) $attributes['tipDurationOut'] : '1' ) . ']"';
			}
				// Item Content.
				$output     .= '<div id="' . $uniqid . '" class="tpgb-icon-list-item tp-repeater-item-' . esc_attr( $item['_key'] ) . ' ' . esc_attr( $active_class ) . '" ' . $itemtooltip . ' ' . $tooltipdata . '>';
					$output .= $descurl_open;
					$output .= $icons;
					$output .= $itemdesc;
					$output .= $descurl_close;
				$output     .= '</div>';
				endforeach;
			$output .= '</div>';

		if ( ! empty( $read_more_toggle ) && $i > $show_list_toggle ) {
			$output .= '<a href="#" class="read-more-options more" data-default-load="' . (int) $show_list_toggle . '" data-more-text="' . esc_attr( $read_more_text ) . '" data-less-text="' . esc_attr( $read_less_text ) . '">' . wp_kses_post( $read_more_text ) . '</a>';
		}
	}
	$output .= '</div>';

	$output = Tpgb_Blocks_Global_Options::block_Wrap_Render( $attributes, $output );

	return $output;
}

/**
 * Render for the server-side
 */
function tpgb_tp_stylist_list() {
	if ( method_exists( 'Tpgb_Blocks_Global_Options', 'merge_options_json' ) ) {
		$block_data = Tpgb_Blocks_Global_Options::merge_options_json( __DIR__, 'tpgb_tp_stylist_list_render_callback' );
		register_block_type( $block_data['name'], $block_data );
	}
}
add_action( 'init', 'tpgb_tp_stylist_list' );
