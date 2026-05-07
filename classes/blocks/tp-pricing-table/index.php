<?php
/**
 * Pricing Table.
 *
 * @package ThePluginAddonsForBlockEditor
 */

defined( 'ABSPATH' ) || exit;

/**
 * Tpgb tp pricing table render callback.
 *
 * @param mixed $attributes The attributes.
 * @param mixed $content The content.
 */
function tpgb_tp_pricing_table_render_callback( $attributes, $content ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
	$block_id       = ( ! empty( $attributes['block_id'] ) ) ? $attributes['block_id'] : uniqid( 'title' );
	$style          = ( ! empty( $attributes['style'] ) ) ? $attributes['style'] : 'style-1';
	$content_style  = ( ! empty( $attributes['contentStyle'] ) ) ? $attributes['contentStyle'] : 'wysiwyg';
	$con_list_style = ( ! empty( $attributes['conListStyle'] ) ) ? $attributes['conListStyle'] : 'style-1';
	$wy_style       = ( ! empty( $attributes['wyStyle'] ) ) ? $attributes['wyStyle'] : 'style-1';
	$wy_content     = ( ! empty( $attributes['wyContent'] ) ) ? $attributes['wyContent'] : '';

	$dis_ribbon = ( ! empty( $attributes['disRibbon'] ) ) ? $attributes['disRibbon'] : false;

	$title_style = ( ! empty( $attributes['titleStyle'] ) ) ? $attributes['titleStyle'] : 'style-1';
	$icon_type   = ( ! empty( $attributes['iconType'] ) ) ? $attributes['iconType'] : 'none';
	$icon_style  = ( ! empty( $attributes['iconStyle'] ) ) ? $attributes['iconStyle'] : 'square';
	$icon_store  = ( ! empty( $attributes['iconStore'] ) ) ? $attributes['iconStore'] : 'fas fa-home';
	$img_store   = ( ! empty( $attributes['imgStore'] ) ) ? $attributes['imgStore'] : '';
	$title       = ( ! empty( $attributes['title'] ) ) ? $attributes['title'] : '';
	$sub_title   = ( ! empty( $attributes['subTitle'] ) ) ? $attributes['subTitle'] : '';

	$price_style      = ( ! empty( $attributes['priceStyle'] ) ) ? $attributes['priceStyle'] : 'style-1';
	$dis_pre_price    = ( ! empty( $attributes['disPrePrice'] ) ) ? $attributes['disPrePrice'] : false;
	$prev_pre_text    = ( ! empty( $attributes['prevPreText'] ) ) ? $attributes['prevPreText'] : '';
	$prev_price_value = ( ! empty( $attributes['prevPriceValue'] ) ) ? $attributes['prevPriceValue'] : '';
	$prev_post_text   = ( ! empty( $attributes['prevPostText'] ) ) ? $attributes['prevPostText'] : '';
	$pre_text         = ( ! empty( $attributes['preText'] ) ) ? $attributes['preText'] : '';
	$price_value      = ( isset( $attributes['priceValue'] ) ) ? $attributes['priceValue'] : '';
	$post_text        = ( ! empty( $attributes['postText'] ) ) ? $attributes['postText'] : '';

	$block_class = Tp_Blocks_Helper::block_wrapper_classes( $attributes );

	$ext_btnshow = ( ! empty( $attributes['extBtnshow'] ) ) ? $attributes['extBtnshow'] : false;

	$hover_style = ( ! empty( $attributes['hoverStyle'] ) ) ? $attributes['hoverStyle'] : 'hover_normal';

	$svg_icon         = ( ! empty( $attributes['svgIcon'] ) ) ? $attributes['svgIcon'] : '';
	$svg_draw         = ( ! empty( $attributes['svgDraw'] ) ) ? $attributes['svgDraw'] : 'delayed';
	$svgstro_color    = ( ! empty( $attributes['svgstroColor'] ) ) ? $attributes['svgstroColor'] : '';
	$svgfill_color    = ( ! empty( $attributes['svgfillColor'] ) ) ? $attributes['svgfillColor'] : 'none';
	$svgstro_hov      = ( ! empty( $attributes['svgstroHov'] ) ) ? $attributes['svgstroHov'] : '';
	$svgfill_hov      = ( ! empty( $attributes['svgfillHov'] ) ) ? $attributes['svgfillHov'] : '';
	$svg_dura         = ( ! empty( $attributes['svgDura'] ) ) ? $attributes['svgDura'] : 90;
	$cta_text         = ( ! empty( $attributes['ctaText'] ) ) ? $attributes['ctaText'] : '';
	$stylish_list     = ( ! empty( $attributes['stylishList'] ) ) ? $attributes['stylishList'] : array();
	$read_more_toggle = ( ! empty( $attributes['readMoreToggle'] ) ) ? $attributes['readMoreToggle'] : false;
	$show_list_toggle = ( ! empty( $attributes['showListToggle'] ) ) ? (int) $attributes['showListToggle'] : 3;
	$read_more_text   = ( ! empty( $attributes['readMoreText'] ) ) ? $attributes['readMoreText'] : '';
	$read_less_text   = ( ! empty( $attributes['readLessText'] ) ) ? $attributes['readLessText'] : '';
	$dis_ribbon       = ( ! empty( $attributes['disRibbon'] ) ) ? $attributes['disRibbon'] : false;
	$ribbon_style     = ( ! empty( $attributes['ribbonStyle'] ) ) ? $attributes['ribbonStyle'] : 'style-1';
	$ribbon_text      = ( ! empty( $attributes['ribbonText'] ) ) ? $attributes['ribbonText'] : '';
	$extbtn_position  = ( ! empty( $attributes['extbtnPosition'] ) ) ? $attributes['extbtnPosition'] : '';

	$i                = 0;
	$content_overlay  = '';
	$content_overlay .= '<div class="content-overlay-bg-color tpgb-trans-easeinout"></div>';

	// Get Icon.
	$get_price_icon = '';
	$icon_style     = '';
	$img_src        = '';
	$trlinr         = 'tpgb-trans-linear';
	if ( 'icon' === $icon_type ) {
		$icon_style = $icon_style;
	}
	$get_price_icon .= '<div class=" ' . ( 'svg' === $icon_type ? ' tpgb-draw-svg' : ' pricing-icon ' . esc_attr( $trlinr ) ) . ' icon-' . esc_attr( $icon_style ) . '" ' . ( 'svg' === $icon_type ? 'data-id="service-svg-' . esc_attr( $block_id ) . '" data-type="' . esc_attr( $svg_draw ) . '" data-duration="' . esc_attr( $svg_dura ) . '" data-stroke="' . esc_attr( $svgstro_color ) . '" data-fillColor="' . esc_attr( $svgfill_color ) . '" data-strokeColorHover="' . esc_attr( $svgstro_hov ) . '" data-fillColorHover="' . esc_attr( $svgfill_hov ) . '" data-fillEnable="yes"' : '' ) . ' >';
	if ( 'icon' === $icon_type ) {
		$get_price_icon .= '<i class="' . esc_attr( $icon_store ) . '"></i>';
	}
	if ( 'img' === $icon_type && ! empty( $img_store ) ) {
		$alt_text = ( isset( $img_store['alt'] ) && ! empty( $img_store['alt'] ) ) ? esc_attr( $img_store['alt'] ) : ( ( ! empty( $img_store['title'] ) ) ? esc_attr( $img_store['title'] ) : esc_attr__( 'Price Icon', 'the-plus-addons-for-block-editor' ) );

		if ( ! empty( $img_store['id'] ) ) {
			$img_src = wp_get_attachment_image(
				$img_store['id'],
				'full',
				false,
				array(
					'class' => 'pricing-icon-img',
					'alt'   => $alt_text,
				)
			);
		} elseif ( ! empty( $img_store['url'] ) ) {
			$img_src = '<img src=' . esc_url( $img_store['url'] ) . ' class="pricing-icon-img" alt="' . $alt_text . '"/>';
		}
		$get_price_icon .= $img_src;
	}
	if ( 'svg' === $icon_type && ! empty( $svg_icon ) && ! empty( $svg_icon['url'] ) ) {
		$get_price_icon .= '<object id="service-svg-' . esc_attr( $block_id ) . '" type="image/svg+xml" data="' . esc_url( $svg_icon['url'] ) . '" aria-label="' . esc_attr__( 'icon', 'the-plus-addons-for-block-editor' ) . '"></object>';
	}
	$get_price_icon .= '</div>';

	// Get Title.
	$get_price_title = '';
	if ( ! empty( $title ) ) {
		$get_price_title     .= '<div class="pricing-title-wrap">';
			$get_price_title .= '<div class="pricing-title ' . esc_attr( $trlinr ) . '">' . wp_kses_post( $title ) . '</div>';
		$get_price_title     .= '</div>';
	}

	// Get Sub Title.
	$get_price_sub_title = '';
	if ( ! empty( $sub_title ) ) {
		$get_price_sub_title     .= '<div class="pricing-subtitle-wrap">';
			$get_price_sub_title .= '<div class="pricing-subtitle ' . esc_attr( $trlinr ) . '">' . wp_kses_post( $sub_title ) . '</div>';
		$get_price_sub_title     .= '</div>';
	}

	$get_ribbon = '';
	if ( ! empty( $dis_ribbon ) && ! empty( $ribbon_text ) ) {
		$get_ribbon     .= '<div class="pricing-ribbon-pin tpgb-relative-block ' . esc_attr( $ribbon_style ) . '">';
			$get_ribbon .= '<div class="ribbon-pin-inner ' . esc_attr( $trlinr ) . '">' . wp_kses_post( $ribbon_text ) . '</div>';
		$get_ribbon     .= '</div>';
	}

	// Get Title-SubTitle Content.
	$get_title_content  = '';
	$get_title_content .= '<div class="pricing-title-content tpgb-relative-block ' . esc_attr( $title_style ) . '">';
	if ( 'none' !== $icon_type ) {
		$get_title_content .= $get_price_icon;
	}
		$get_title_content .= $get_price_title;
		$get_title_content .= $get_price_sub_title;
	$get_title_content     .= '</div>';

	// Get Price Content.
	$get_price_content  = '';
	$get_price_content .= '<div class="pricing-price-wrap ' . esc_attr( $price_style ) . '">';
	if ( ! empty( $dis_pre_price ) ) {
		$get_price_content     .= '<span class="pricing-previous-price-wrap ' . esc_attr( $trlinr ) . '">';
			$get_price_content .= wp_kses_post( $prev_pre_text );
			$get_price_content .= wp_kses_post( $prev_price_value );
			$get_price_content .= wp_kses_post( $prev_post_text );
		$get_price_content     .= '</span>';
	}
	if ( ! empty( $pre_text ) ) {
		$get_price_content .= '<span class="price-prefix-text ' . esc_attr( $trlinr ) . '">' . wp_kses_post( $pre_text ) . '</span>';
	}
	if ( isset( $price_value ) && '' !== $price_value ) {
		$get_price_content .= '<span class="pricing-price ' . esc_attr( $trlinr ) . '">' . wp_kses_post( $price_value ) . '</span>';
	}
	if ( ! empty( $post_text ) ) {
		$get_price_content .= '<span class="price-postfix-text ' . esc_attr( $trlinr ) . '">' . wp_kses_post( $post_text ) . '</span>';
	}
	$get_price_content .= '</div>';

	// Get Button & CTA Text.
	$get_btn_cta = '';
	if ( ! empty( $ext_btnshow ) ) {
		$get_btn_cta     .= '<div class="pricing-table-button">';
			$get_btn_cta .= Tpgb_Blocks_Global_Options::load_plusButton_saves( $attributes );
		$get_btn_cta     .= '</div>';
	}

	if ( ! empty( $cta_text ) ) {
		$get_btn_cta .= '<div class="pricing-cta-text">' . wp_kses_post( $cta_text ) . '</div>';
	}

	$get_stylish_content = '';
	if ( 'stylish' === $content_style ) {
		$get_stylish_content .= '<div class="pricing-content-wrap listing-content tpgb-relative-block ' . esc_attr( $con_list_style ) . '">';
		if ( ! empty( $stylish_list ) ) {
			$get_stylish_content .= '<div class="tpgb-icon-list-items ' . esc_attr( $trlinr ) . '">';
			foreach ( $stylish_list as $index => $item ) :

				++$i;

				$content_item = array();
				// Item Content.
				$get_stylish_content .= '<div class="tpgb-icon-list-item ' . esc_attr( $trlinr ) . ' tp-repeater-item-' . esc_attr( $item['_key'] ) . '">';

					// Get Item Icon.
					$get_item_icon      = '';
					$get_item_icon     .= '<span class="tpgb-icon-list-icon ' . esc_attr( $trlinr ) . '">';
						$get_item_icon .= '<i class="' . esc_attr( $item['iconStore'] ) . '" aria-hidden="true"></i>';
					$get_item_icon     .= '</span>';

						// Get Item Extra Icon.
						$get_item_ex_icon = '';
				if ( ! empty( $item['eIcnToggle'] ) && ! empty( $item['eIconStore'] ) ) {
					$get_item_ex_icon .= '<span class="tpgb-extra-list-icon ' . esc_attr( $trlinr ) . '">';
					$get_item_ex_icon .= '<i class="' . esc_attr( $item['eIconStore'] ) . '" aria-hidden="true"></i>';
					$get_item_ex_icon .= '</span>';
				}

						// Get Item Description.
						$get_item_desc = '';
				if ( ! empty( $item['listDesc'] ) ) {
					$get_item_desc .= '<span class="tpgb-icon-list-text ' . esc_attr( $trlinr ) . '">' . wp_kses_post( $item['listDesc'] ) . '</span>';
				}
						$get_stylish_content .= $get_item_icon;
						$get_stylish_content .= $get_item_desc;
						$get_stylish_content .= $get_item_ex_icon;
						$get_stylish_content .= '</div>';

					endforeach;
				$get_stylish_content .= '</div>';

			if ( 'style-2' !== $con_list_style && ! empty( $read_more_toggle ) && $i > $show_list_toggle ) {
				$get_stylish_content .= '<a href="#" class="read-more-options tpgb-relative-block ' . esc_attr( $trlinr ) . ' more" data-default-load="' . (int) $show_list_toggle . '" data-more-text="' . esc_attr( $read_more_text ) . '" data-less-text="' . esc_attr( $read_less_text ) . '">' . wp_kses_post( $read_more_text ) . '</a>';
			}
			if ( 'style-1' === $con_list_style ) {
				$get_stylish_content .= $content_overlay;
			}
		}
		$get_stylish_content .= '</div>';
	}

	// Get wysiwyg Content.
	$get_wysiwyg_content = '';
	if ( 'wysiwyg' === $content_style ) {
		$get_wysiwyg_content .= '<div class="pricing-content-wrap content-desc ' . esc_attr( $wy_style ) . '">';
		if ( 'style-2' === $wy_style ) {
			$get_wysiwyg_content .= '<hr class="border-line"/>';
		}
			$get_wysiwyg_content .= '<div class="pricing-content">' . wp_kses_post( $wy_content ) . '</div>';
			$get_wysiwyg_content .= '<div class="content-overlay-bg-color tpgb-trans-easeinout"></div>';
		$get_wysiwyg_content     .= '</div>';
	}

	$output      = '';
	$output     .= '<div class="tpgb-pricing-table tpgb-relative-block ' . esc_attr( $trlinr ) . ' pricing-' . esc_attr( $style ) . ' ' . esc_attr( $hover_style ) . ' tpgb-block-' . esc_attr( $block_id ) . ' ' . esc_attr( $block_class ) . '">';
		$output .= '<div class="pricing-table-inner ' . esc_attr( $trlinr ) . '">';
	if ( 'style-1' === $style ) {
		if ( 'bottom' === $extbtn_position ) {
			$output .= $get_ribbon;
			$output .= $get_title_content;
			$output .= $get_price_content;
			$output .= $get_stylish_content;
			$output .= $get_wysiwyg_content;
			$output .= $get_btn_cta;
			$output .= '<div class="pricing-overlay-color tpgb-trans-easeinout"></div>';
		} else {
			$output .= $get_ribbon;
			$output .= $get_title_content;
			$output .= $get_price_content;
			$output .= $get_btn_cta;
			$output .= $get_stylish_content;
			$output .= $get_wysiwyg_content;
			$output .= '<div class="pricing-overlay-color tpgb-trans-easeinout"></div>';
		}
	}

		$output .= '</div>';
	$output     .= '</div>';

	$output = Tpgb_Blocks_Global_Options::block_Wrap_Render( $attributes, $output );

	return $output;
}

/**
 * Render for the server-side
 */
function tpgb_pricing_table() {
	$block_data = Tpgb_Blocks_Global_Options::merge_options_json( __DIR__, 'tpgb_tp_pricing_table_render_callback', true, false, true );
	register_block_type( $block_data['name'], $block_data );
}
add_action( 'init', 'tpgb_pricing_table' );
