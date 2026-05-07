<?php
/**
 * Tp Block : Dark Mode.
 *
 * @package ThePluginAddonsForBlockEditor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Tpgb tp dark mode render callback.
 *
 * @param mixed $attributes The attributes.
 * @param mixed $content The content.
 * @return mixed The result.
 */
function tpgb_tp_dark_mode_render_callback( $attributes, $content ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
	$block_id       = ( ! empty( $attributes['block_id'] ) ) ? $attributes['block_id'] : uniqid( 'title' );
	$dm_style       = ( ! empty( $attributes['dmStyle'] ) ) ? $attributes['dmStyle'] : 'style-1';
	$dm_position    = ( ! empty( $attributes['dmPosition'] ) ) ? $attributes['dmPosition'] : 'relative';
	$fixed_pos      = ( ! empty( $attributes['fixedPos'] ) ) ? $attributes['fixedPos'] : 'left-top';
	$s2_icon_type   = ( ! empty( $attributes['S2IconType'] ) ) ? $attributes['S2IconType'] : 'icon';
	$icon_name      = ( ! empty( $attributes['IconName'] ) ) ? $attributes['IconName'] : '';
	$dark_icon_en   = ( ! empty( $attributes['darkIconEn'] ) ) ? $attributes['darkIconEn'] : false;
	$dark_icon      = ( ! empty( $attributes['darkIcon'] ) ) ? $attributes['darkIcon'] : '';
	$save_cookies   = ( ! empty( $attributes['saveCookies'] ) ) ? $attributes['saveCookies'] : false;
	$match_os_theme = ( ! empty( $attributes['matchOsTheme'] ) ) ? $attributes['matchOsTheme'] : false;

	$block_class = Tp_Blocks_Helper::block_wrapper_classes( $attributes );

	$output        = '';
	$hide_nml_icon = '';
	$fix_pos_class = '';

	if ( ! empty( $dark_icon_en ) ) {
		$hide_nml_icon = ' hide-normal-icon';
	}
	if ( 'fixed' === $dm_position ) {
		$fix_pos_class = 'fix-' . $fixed_pos;
	}

	$output     .= '<div class="tpgb-dark-mode tpgb-relative-block dark-pos-' . esc_attr( $dm_position ) . ' darkmode-' . esc_attr( $dm_style ) . ' tpgb-block-' . esc_attr( $block_id ) . ' ' . esc_attr( $block_class ) . '" data-id="tpgb-block-' . esc_attr( $block_id ) . '" data-save-cookies="' . esc_attr( $save_cookies ) . '" data-match-os="' . esc_attr( $match_os_theme ) . '">';
		$output .= '<div class="tpgb-dark-mode-wrap">';

			$output .= '<div class="tpgb-darkmode-toggle ' . esc_attr( $fix_pos_class ) . '">';
	if ( 'style-1' === $dm_style || 'style-2' === $dm_style ) {
		$output .= '<span class="tpgb-dark-mode-slider"></span>';
	} elseif ( 'icon' === $s2_icon_type ) {
			$output     .= '<span class="tpgb-normal-icon' . esc_attr( $hide_nml_icon ) . '">';
				$output .= '<i class="' . esc_attr( $icon_name ) . '"></i>';
			$output     .= '</span>';
		if ( ! empty( $dark_icon_en ) ) {
			$output     .= '<span class="tpgb-dark-icon">';
				$output .= '<i class="' . esc_attr( $dark_icon ) . '"></i>';
			$output     .= '</span>';
		}
	}
			$output .= '</div>';
		$output     .= '</div>';
	$output         .= '</div>';

	$output = Tpgb_Blocks_Global_Options::block_Wrap_Render( $attributes, $output );

	return $output;
}

/**
 * Render for the server-side
 */
function tpgb_dark_mod() {
	$block_data = Tpgb_Blocks_Global_Options::merge_options_json( __DIR__, 'tpgb_tp_dark_mode_render_callback' );
	register_block_type( $block_data['name'], $block_data );
}
add_action( 'init', 'tpgb_dark_mod' );
