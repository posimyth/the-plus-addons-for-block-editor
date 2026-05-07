<?php
/**
 * Draw Svg.
 *
 * @package ThePluginAddonsForBlockEditor
 */

defined( 'ABSPATH' ) || exit;

/**
 * Tpgb tp draw svg render callback.
 *
 * @param mixed $attributes The attributes.
 * @param mixed $content The content.
 * @return mixed The result.
 */
function tpgb_tp_draw_svg_render_callback( $attributes, $content ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
	$block_id     = ( ! empty( $attributes['block_id'] ) ) ? $attributes['block_id'] : uniqid( 'title' );
	$duration     = ( ! empty( $attributes['duration'] ) ) ? $attributes['duration'] : 90;
	$draw_type    = ( ! empty( $attributes['drawType'] ) ) ? $attributes['drawType'] : 'delayed';
	$select_svg   = ( ! empty( $attributes['selectSvg'] ) ) ? $attributes['selectSvg'] : 'preBuild';
	$svg_list     = ( ! empty( $attributes['svgList'] ) ) ? $attributes['svgList'] : 'app';
	$hover_draw   = ( ! empty( $attributes['hoverDraw'] ) ) ? $attributes['hoverDraw'] : 'onScroll';
	$stroke_color = ( ! empty( $attributes['strokeColor'] ) ) ? $attributes['strokeColor'] : '';
	$fill_toggle  = ( ! empty( $attributes['fillToggle'] ) ) ? $attributes['fillToggle'] : false;
	$fiill_color  = ( ! empty( $attributes['fillColor'] ) ) ? $attributes['fillColor'] : '';
	$svg_link     = ( ! empty( $attributes['svgLink']['url'] ) ) ? $attributes['svgLink']['url'] : '';
	$target       = ( ! empty( $attributes['svgLink']['target'] ) ) ? ' target="_blank" ' : '';
	$nofollow     = ( ! empty( $attributes['svgLink']['nofollow'] ) ) ? ' rel="nofollow" ' : '';
	$link_attr    = Tp_Blocks_Helper::add_link_attributes( $attributes['svgLink'] );
	$block_class  = Tp_Blocks_Helper::block_wrapper_classes( $attributes );

	if ( class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) {
		$svg_link = ( isset( $attributes['svgLink']['dynamic'] ) ) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_repeat_url( $attributes['svgLink'] ) : ( ! empty( $attributes['svgLink']['url'] ) ? $attributes['svgLink']['url'] : '' );
	}

	$fill_enable = '';
	$fill_color  = '';
	if ( ! empty( $fill_toggle ) ) {
		$fill_enable = 'yes';
		$fill_color  = $fiill_color;
	} else {
		$fill_enable = 'no';
		$fill_color  = 'none';
	}

	$draw_hover = '';
	if ( 'onHover' === $hover_draw ) {
		$draw_hover = 'tpgb-hover-draw-svg';
	}
	$svgsrc = '';
	if ( 'custom' === $select_svg ) {
		$svgsrc = ( isset( $attributes['customSVG']['dynamic'] ) && class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_repeat_url( $attributes['customSVG'] ) : ( ! empty( $attributes['customSVG']['url'] ) ? $attributes['customSVG']['url'] : '' );
	} else {
		$svgsrc = TPGB_URL . 'assets/images/svg/' . esc_attr( $svg_list ) . '.svg';
	}
	$output  = '';
	$output .= '<div class="tpgb-draw-svg tpgb-block-' . esc_attr( $block_id ) . ' ' . esc_attr( $block_class ) . ' ' . esc_attr( $draw_hover ) . '" data-id="tpgb-block-' . esc_attr( $block_id ) . '" data-type="' . esc_attr( $draw_type ) . '" data-duration="' . esc_attr( $duration ) . '" data-stroke="' . esc_attr( $stroke_color ) . '" data-fillcolor="' . esc_attr( $fill_color ) . '" data-fillenable="' . esc_attr( $fill_enable ) . '">';
	if ( $svg_link && ( '' !== $svg_link ) ) {
		$output         .= '<a ' . $link_attr . ' href="' . esc_url( $svg_link ) . '" ' . $target . ' ' . $nofollow . '>';
			$output     .= '<div class="svg-inner-block">';
				$output .= '<object id="tpgb-block-' . esc_attr( $block_id ) . '" type="image/svg+xml" data="' . esc_url( $svgsrc ) . '" aria-label="' . esc_attr__( 'icon', 'the-plus-addons-for-block-editor' ) . '"></object>';
			$output     .= '</div>';
		$output         .= '</a>';
	} else {
		$output     .= '<div class="svg-inner-block">';
			$output .= '<object id="tpgb-block-' . esc_attr( $block_id ) . '" type="image/svg+xml" data="' . esc_url( $svgsrc ) . '" aria-label="' . esc_attr__( 'icon', 'the-plus-addons-for-block-editor' ) . '"></object>';
		$output     .= '</div>';
	}
	$output .= '</div>';

	$output = Tpgb_Blocks_Global_Options::block_Wrap_Render( $attributes, $output );

	return $output;
}

/**
 * Render for the server-side
 */
function tpgb_draw_svg() {
	$block_data = Tpgb_Blocks_Global_Options::merge_options_json( __DIR__, 'tpgb_tp_draw_svg_render_callback' );
	register_block_type( $block_data['name'], $block_data );
}
add_action( 'init', 'tpgb_draw_svg' );
