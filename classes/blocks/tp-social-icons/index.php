<?php
/**
 * Social Icons.
 *
 * @package ThePluginAddonsForBlockEditor
 */

defined( 'ABSPATH' ) || exit;

/**
 * Tpgb tp social icons render callback.
 *
 * @param mixed $attributes The attributes.
 * @param mixed $content The content.
 * @return mixed The result.
 */
function tpgb_tp_social_icons_render_callback( $attributes, $content ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
	$block_id    = ( ! empty( $attributes['block_id'] ) ) ? $attributes['block_id'] : uniqid( 'title' );
	$style       = ( ! empty( $attributes['style'] ) ) ? $attributes['style'] : 'style-1';
	$hover_style = ( ! empty( $attributes['hoverStyle'] ) ) ? $attributes['hoverStyle'] : 'faded';
	$social_icon = ( ! empty( $attributes['socialIcon'] ) ) ? $attributes['socialIcon'] : array();
	$alignment   = ( ! empty( $attributes['Alignment'] ) ) ? $attributes['Alignment'] : 'text-center';

	$alignattr = '';
	if ( '' !== $alignment ) {
		$alignattr .= ( ! empty( $alignment['md'] ) ) ? ' text-' . esc_attr( $alignment['md'] ) : ' text-center';
		$alignattr .= ( ! empty( $alignment['sm'] ) ) ? ' tsocialtext-' . esc_attr( $alignment['sm'] ) : '';
		$alignattr .= ( ! empty( $alignment['xs'] ) ) ? ' msocialtext-' . esc_attr( $alignment['xs'] ) : '';
	}

	$social_animation = '';
	if ( 'style-14' === $style || 'style-15' === $style ) {
		if ( 'faded' === $hover_style ) {
			$social_animation = 'social-faded';
		} elseif ( 'chaffal' === $hover_style ) {
			$social_animation = 'social-chaffal';
		}
	}

	$block_class = Tp_Blocks_Helper::block_wrapper_classes( $attributes );

	$i       = 0;
	$output  = '';
	$output .= '<div class="tpgb-social-icons ' . esc_attr( $style ) . ' ' . esc_attr( $alignattr ) . ' tpgb-block-' . esc_attr( $block_id ) . ' ' . esc_attr( $block_class ) . '">';
	if ( ! empty( $social_icon ) ) {
		$output .= '<div class="tpgb-social-list ' . esc_attr( $social_animation ) . '">';

		foreach ( $social_icon as $index => $network ) :
			// Tooltip.
			++$i;
				$itemtooltip     = '';
				$tooltip_trigger = '';
				$tooltipdata     = '';
				$content_item    = array();
				$uniqid          = uniqid( 'tooltip' );
			if ( ! empty( $network['itemTooltip'] ) ) {
				$itemtooltip .= ' data-tippy=""';
				$itemtooltip .= ' data-tippy-interactive="' . ( ! empty( $attributes['tipInteractive'] ) ? 'true' : 'false' ) . '"';
				$itemtooltip .= ' data-tippy-placement="' . ( ! empty( $attributes['tipPlacement'] ) ? $attributes['tipPlacement'] : 'top' ) . '"';
				$itemtooltip .= ' data-tippy-theme="' . esc_attr( $attributes['tipTheme'] ) . '"';
				$itemtooltip .= ' data-tippy-arrow="' . ( ! empty( $attributes['tipArrow'] ) ? 'true' : 'false' ) . '"';

				$itemtooltip .= ' data-tippy-animation="' . ( ! empty( $attributes['tipAnimation'] ) ? $attributes['tipAnimation'] : 'fade' ) . '"';
				$itemtooltip .= ' data-tippy-offset="[' . ( ! empty( $attributes['tipOffset'] ) ? (int) $attributes['tipOffset'] : 0 ) . ',' . ( ! empty( $attributes['tipDistance'] ) ? (int) $attributes['tipDistance'] : 0 ) . ']"';
				$itemtooltip .= ' data-tippy-duration="[' . ( ! empty( $attributes['tipDurationIn'] ) ? (int) $attributes['tipDurationIn'] : '1' ) . ',' . ( ! empty( $attributes['tipDurationOut'] ) ? (int) $attributes['tipDurationOut'] : '1' ) . ']"';

				if ( class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) {
						$content_item['content'] = ( ! empty( $network['tooltipText'] ) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_val( $network['tooltipText'] ) : '' );
				} else {
							$content_item['content'] = ( ! empty( $network['tooltipText'] ) ? $network['tooltipText'] : '' );
				}

						$content_item['trigger']  = ( ! empty( $attributes['tipTriggers'] ) ? $attributes['tipTriggers'] : 'mouseenter' );
						$content_item['MaxWidth'] = ( ! empty( $attributes['tipMaxWidth'] ) ? (int) $attributes['tipMaxWidth'] : 'none' );
						$content_item             = htmlspecialchars( wp_json_encode( $content_item ), ENT_QUOTES, 'UTF-8' );
						$tooltipdata              = 'data-tooltip-opt= \'' . $content_item . '\'';
			}

				$output .= '<div id="' . esc_attr( $uniqid ) . '" class=" social-icon-tooltip tp-repeater-item-' . ( isset( $network['_key'] ) ? esc_attr( $network['_key'] ) : '' ) . ' ' . esc_attr( $style ) . ' ' . $itemtooltip . '" ' . $tooltipdata . ' >';
			if ( ! empty( $network['linkUrl']['url'] ) && ! empty( $network['socialNtwk'] ) ) {
				$social_url  = ( class_exists( 'Tpgbp_Pro_Blocks_Helper' ) && isset( $network['linkUrl']['dynamic'] ) ) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_repeat_url( $network['linkUrl'] ) : ( ! empty( $network['linkUrl']['url'] ) ? $network['linkUrl']['url'] : '' );
				$target      = ( ! empty( $network['linkUrl']['target'] ) ) ? ' target="_blank" ' : '';
				$nofollow    = ( ! empty( $network['linkUrl']['nofollow'] ) ) ? ' rel="nofollow" ' : '';
				$link_attr   = Tp_Blocks_Helper::add_link_attributes( $network['linkUrl'] );
				$output     .= '<div class="tpgb-social-loop-inner ' . ( 'style-14' === $style ? 'tpgb-rel-flex' : '' ) . '">';
					$output .= '<a class="tpgb-icon-link ' . ( ( 'style-14' === $style || 'style-15' === $style ) ? 'tpgb-rel-flex' : '' ) . '" href="' . esc_url( $social_url ) . '" aria-label="' . esc_attr( $network['title'] ) . '" ' . $target . ' ' . $nofollow . ' ' . $link_attr . '>';
				if ( 'custom' === $network['socialNtwk'] && 'icon' === $network['customType'] && ! empty( $network['customIcons'] ) ) {
					$output .= '<span class="tpgb-social-icn ' . ( 'style-12' === $style ? 'tpgb-abs-flex' : '' ) . '">';
					$output .= '<i class="' . esc_attr( $network['customIcons'] ) . '"></i>';
					$output .= '</span>';
				} elseif ( 'custom' === $network['socialNtwk'] && 'image' === $network['customType'] && ! empty( $network['imgField'] ) && ! empty( $network['imgField']['url'] ) ) {
					$img_src  = '';
					$alt_text = ( isset( $network['imgField']['alt'] ) && ! empty( $network['imgField']['alt'] ) ) ? esc_attr( $network['imgField']['alt'] ) : ( ( ! empty( $network['imgField']['title'] ) ) ? esc_attr( $network['imgField']['title'] ) : esc_attr__( 'Custom Icon', 'the-plus-addons-for-block-editor' ) );
					if ( ! empty( $network['imgField'] ) && ! empty( $network['imgField']['id'] ) ) {
								$img_src = wp_get_attachment_image( $network['imgField']['id'], 'full', false, array( 'alt' => $alt_text ) );
					} elseif ( ! empty( $network['imgField']['url'] ) ) {
						if ( class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) {
							$img_url = Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_repeat_url( $network['imgField'] );
						} else {
							$img_url = $network['imgField']['url'];
						}

							$img_src = '<img src="' . esc_url( $img_url ) . '" alt="' . $alt_text . '" />';
					}
							$output .= '<span class="tpgb-social-icn social-img ' . ( 'style-7' === $style ? 'tpgb-rel-flex' : '' ) . ' ' . ( 'style-12' === $style ? 'tpgb-abs-flex' : '' ) . '">';
							$output .= $img_src;
							$output .= '</span>';
				} elseif ( 'custom' !== $network['socialNtwk'] ) {
					$output     .= '<span class="tpgb-social-icn ' . ( 'style-12' === $style ? 'tpgb-abs-flex' : '' ) . '">';
						$output .= '<i class="' . esc_attr( $network['socialNtwk'] ) . '"></i>';
					$output     .= '</span>';
				}
				if ( 'style-6' === $style ) {
					$output .= '<i class="social-hover-style"></i>';
				}
				if ( ! empty( $network['title'] ) && 'style-1' === $style || 'style-2' === $style || 'style-4' === $style || 'style-10' === $style || 'style-12' === $style || 'style-14' === $style || 'style-15' === $style ) { // phpcs:ignore Generic.CodeAnalysis.RequireExplicitBooleanOperatorPrecedence.MissingParentheses
					$output .= '<span class="tpgb-social-title ' . ( ( 'style-10' === $style || 'style-12' === $style ) ? 'tpgb-abs-flex' : '' ) . '" data-lang="en">' . wp_kses_post( $network['title'] ) . '</span>';
				}
				if ( 'style-9' === $style ) {
					$output .= '<span class="tpgb-line-blink line-top-left "></span>';
					$output .= '<span class="tpgb-line-blink line-top-center "></span>';
					$output .= '<span class="tpgb-line-blink line-top-right "></span>';
					$output .= '<span class="tpgb-line-blink line-bottom-left "></span>';
					$output .= '<span class="tpgb-line-blink line-bottom-center "></span>';
					$output .= '<span class="tpgb-line-blink line-bottom-right "></span>';
				}
							$output .= '</a>';
							$output .= '</div>';
			}
				$output .= '</div>';

					endforeach;
			$output .= '</div>';
	}

	$output .= '</div>';

	$output = Tpgb_Blocks_Global_Options::block_Wrap_Render( $attributes, $output );

	return $output;
}
/**
 * Render for the server-side
 */
function tpgb_social_icons() {
	$block_data = Tpgb_Blocks_Global_Options::merge_options_json( __DIR__, 'tpgb_tp_social_icons_render_callback' );
	register_block_type( $block_data['name'], $block_data );
}
add_action( 'init', 'tpgb_social_icons' );
