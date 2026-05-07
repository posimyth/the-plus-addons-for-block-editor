<?php
/**
 * Block : Code highlighter
 *
 * @since 1.3.0
 * @package ThePluginAddonsForBlockEditor
 */

defined( 'ABSPATH' ) || exit;

/**
 * Tpgb code highlighter render callback.
 *
 * @param mixed $attributes The attributes.
 * @param mixed $content The content.
 * @return mixed The result.
 */
function tpgb_code_highlighter_render_callback( $attributes, $content ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
	$block_id      = ( ! empty( $attributes['block_id'] ) ) ? $attributes['block_id'] : uniqid( 'title' );
	$language_type = ( ! empty( $attributes['languageType'] ) ) ? $attributes['languageType'] : 'markup';
	$theme_type    = ( ! empty( $attributes['themeType'] ) ) ? $attributes['themeType'] : 'prism-default';

	if ( class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) {
		$source_code   = ( ! empty( $attributes['sourceCode'] ) ) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_val( $attributes['sourceCode'], array( 'blockName' => 'tpgb/tp-code-highlighter' ) ) : '';
		$language_text = ( ! empty( $attributes['languageText'] ) ) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_val( $attributes['languageText'] ) : '';
		$copy_text     = ( ! empty( $attributes['copyText'] ) ) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_val( $attributes['copyText'] ) : '';
	} else {
		$source_code   = ( ! empty( $attributes['sourceCode'] ) ) ? $attributes['sourceCode'] : '';
		$language_text = ( ! empty( $attributes['languageText'] ) ) ? $attributes['languageText'] : '';
		$copy_text     = ( ! empty( $attributes['copyText'] ) ) ? $attributes['copyText'] : '';
	}

	$copy_icn_type   = ( ! empty( $attributes['copyIcnType'] ) ) ? $attributes['copyIcnType'] : 'none';
	$copy_icon_store = ( ! empty( $attributes['copyIconStore'] ) ) ? $attributes['copyIconStore'] : '';

	if ( class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) {
		$copied_text     = ( ! empty( $attributes['copiedText'] ) ) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_val( $attributes['copiedText'] ) : '';
		$copy_error_text = ( ! empty( $attributes['copyErrorText'] ) ) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_val( $attributes['copyErrorText'] ) : '';
	} else {
		$copied_text     = ( ! empty( $attributes['copiedText'] ) ) ? $attributes['copiedText'] : '';
		$copy_error_text = ( ! empty( $attributes['copyErrorText'] ) ) ? $attributes['copyErrorText'] : '';
	}

	$copied_icn_type   = ( ! empty( $attributes['copiedIcnType'] ) ) ? $attributes['copiedIcnType'] : 'none';
	$copied_icon_store = ( ! empty( $attributes['copiedIconStore'] ) ) ? $attributes['copiedIconStore'] : '';
	$line_number       = ( ! empty( $attributes['lineNumber'] ) ) ? $attributes['lineNumber'] : false;

	if ( class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) {
		$line_highlight = ( ! empty( $attributes['lineHighlight'] ) ) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_val( $attributes['lineHighlight'] ) : '';
		$dwnld_btn_text = ( ! empty( $attributes['dwnldBtnText'] ) ) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_val( $attributes['dwnldBtnText'] ) : '';
	} else {
		$line_highlight = ( ! empty( $attributes['lineHighlight'] ) ) ? $attributes['lineHighlight'] : '';
		$dwnld_btn_text = ( ! empty( $attributes['dwnldBtnText'] ) ) ? $attributes['dwnldBtnText'] : '';
	}
	$dnload_btn = ( ! empty( $attributes['dnloadBtn'] ) ) ? $attributes['dnloadBtn'] : false;

	$dwnld_icn_type   = ( ! empty( $attributes['dwnldIcnType'] ) ) ? $attributes['dwnldIcnType'] : 'none';
	$dwnld_icon_store = ( ! empty( $attributes['dwnldIconStore'] ) ) ? $attributes['dwnldIconStore'] : '';

	if ( class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) {
		$file_link = ( isset( $attributes['fileLink']['dynamic'] ) ) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_repeat_url( $attributes['fileLink'] ) : ( ! empty( $attributes['fileLink']['url'] ) ? $attributes['fileLink']['url'] : '' );
	} else {
		$file_link = ( ! empty( $attributes['fileLink']['url'] ) ) ? $attributes['fileLink']['url'] : '';
	}

	$block_class = Tp_Blocks_Helper::block_wrapper_classes( $attributes );

	$langtext        = '';
	$line_num_class  = '';
	$dwnld_btn_class = '';
	$cpybtnicon      = '';
	$copiedbtnicon   = '';
	$dwndicon        = '';
	if ( ! empty( $language_text ) ) {
		$langtext = 'data-label="' . esc_html( $language_text ) . '"';
	}
	if ( ! empty( $line_number ) ) {
		$line_num_class = 'line-numbers';
	}
	if ( ! empty( $dnload_btn ) && ! empty( $file_link ) ) {
		$dwnld_btn_class = 'data-src="' . esc_url( $file_link ) . '" data-download-link="' . esc_url( $file_link ) . '" data-download-link-label="' . esc_attr( $dwnld_btn_text ) . '"';
		if ( 'icon' === $dwnld_icn_type ) {
			$dwndicon = $dwnld_icon_store;
		}
	}
	if ( 'icon' === $copy_icn_type ) {
		$cpybtnicon = $copy_icon_store;
	}
	if ( 'icon' === $copied_icn_type ) {
		$copiedbtnicon = $copied_icon_store;
	}

	// Set Dataattr For Circle Menu.
	$code_attr = array(
		'id'           => $block_id,
		'copytext'     => $copy_text,
		'copyicon'     => $cpybtnicon,
		'copiedText'   => $copied_text,
		'copiedicon'   => $copiedbtnicon,
		'downloadtext' => $dwnld_btn_text,
		'downloadicon' => $dwndicon,
	);
	$code_attr = htmlspecialchars( wp_json_encode( $code_attr ), ENT_QUOTES, 'UTF-8' );

	$output              = '';
	$output             .= '<div class="tpgb-code-highlighter tpgb-relative-block code-' . esc_attr( $theme_type ) . ' tpgb-block-' . esc_attr( $block_id ) . ' ' . esc_attr( $block_class ) . '" data-code-atr= \'' . $code_attr . '\'>';
		$output         .= '<pre class="language-' . esc_attr( $language_type ) . ' ' . esc_attr( $line_num_class ) . '" data-line="' . esc_attr( $line_highlight ) . '" ' . $dwnld_btn_class . ' ' . $langtext . '>';
			$output     .= '<code class="language-' . esc_attr( $language_type ) . '" data-prismjs-copy="' . esc_attr( $copy_text ) . '" data-prismjs-copy-error="' . esc_attr( $copy_error_text ) . '" data-prismjs-copy-success="' . esc_attr( $copied_text ) . '">';
				$output .= esc_html( $source_code );
			$output     .= '</code>';
		$output         .= '</pre>';
	$output             .= '</div>';

	$output = Tpgb_Blocks_Global_Options::block_Wrap_Render( $attributes, $output );

	return $output;
}

/**
 * Render for the server-side
 */
function tpgb_code_highlighter() {

	$block_data = Tpgb_Blocks_Global_Options::merge_options_json( __DIR__, 'tpgb_code_highlighter_render_callback' );
	register_block_type( $block_data['name'], $block_data );
}
add_action( 'init', 'tpgb_code_highlighter' );
