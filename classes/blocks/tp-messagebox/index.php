<?php
/**
 * Message Box.
 *
 * @package ThePluginAddonsForBlockEditor
 */

defined( 'ABSPATH' ) || exit;

/**
 * Tpgb tp messagebox render callback.
 *
 * @param mixed $attributes The attributes.
 * @param mixed $content The content.
 * @return mixed The result.
 */
function tpgb_tp_messagebox_render_callback( $attributes, $content ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
	$output       = '';
	$block_id     = ( ! empty( $attributes['block_id'] ) ) ? $attributes['block_id'] : uniqid( 'title' );
	$icon         = ( ! empty( $attributes['icon'] ) ) ? $attributes['icon'] : false;
	$icn_position = ( ! empty( $attributes['icnPosition'] ) ) ? $attributes['icnPosition'] : 'prefix';
	$msg_arrow    = ( ! empty( $attributes['msgArrow'] ) ) ? $attributes['msgArrow'] : false;
	$icon_name    = ( ! empty( $attributes['IconName'] ) ) ? $attributes['IconName'] : '';
	$dismiss      = ( ! empty( $attributes['dismiss'] ) ) ? $attributes['dismiss'] : false;
	$description  = ( ! empty( $attributes['Description'] ) ) ? $attributes['Description'] : false;
	$disms_icon   = ( ! empty( $attributes['dismsIcon'] ) ) ? $attributes['dismsIcon'] : '';
	$title        = ( ! empty( $attributes['Title'] ) ) ? $attributes['Title'] : '';
	$desc_text    = ( ! empty( $attributes['descText'] ) ) ? $attributes['descText'] : '';
	$ext_btnshow  = ( ! empty( $attributes['extBtnshow'] ) ) ? $attributes['extBtnshow'] : false;

	$block_class = Tp_Blocks_Helper::block_wrapper_classes( $attributes );

	$tnslin     = 'tpgb-trans-linear';
	$tnslinaftr = 'tpgb-trans-linear-after';
	$disflex    = 'tpgb-rel-flex';
	$arrow      = '';
	if ( ! empty( $msg_arrow ) ) {
		$arrow .= 'msg-arrow-' . esc_attr( $icn_position );
	}
	$with_btn_css = '';
	if ( ! empty( $ext_btnshow ) ) {
		$with_btn_css .= 'extra-btn-enable';
	}
	$icon_postfix_css = '';
	if ( ! empty( $icn_position ) && 'postfix' === $icn_position ) {
		$icon_postfix_css .= 'main-icon-postfix';
	}
	$get_icon              = '';
		$get_icon         .= '<div class="msg-icon-content ' . esc_attr( $icon_postfix_css ) . ' ' . esc_attr( $tnslin ) . '">';
			$get_icon     .= '<span class="msg-icon ' . esc_attr( $disflex ) . ' ' . esc_attr( $arrow ) . ' ' . esc_attr( $tnslin ) . ' ' . esc_attr( $tnslinaftr ) . '">';
				$get_icon .= '<i class="' . esc_attr( $icon_name ) . '"></i>';
			$get_icon     .= '</span>';
		$get_icon         .= '</div>';

	$get_dismiss              = '';
		$get_dismiss         .= '<div class="msg-dismiss-content ' . esc_attr( $tnslin ) . '">';
			$get_dismiss     .= '<span class="dismiss-icon ' . esc_attr( $disflex ) . ' ' . esc_attr( $tnslin ) . '">';
				$get_dismiss .= '<i class="' . esc_attr( $disms_icon ) . '"></i>';
			$get_dismiss     .= '</span>';
		$get_dismiss         .= '</div>';

	$get_title = '';
	if ( ! empty( $title ) ) {
		$get_title .= '<div class="msg-title ' . esc_attr( $tnslin ) . '">' . wp_kses_post( $title ) . '</div>';
	}

	$get_desc = '';
	if ( ! empty( $description ) && ! empty( $desc_text ) ) {
		$get_desc .= '<div class="msg-desc ' . esc_attr( $tnslin ) . '">' . wp_kses_post( $desc_text ) . '</div>';
	}

	$getbutton  = '';
	$getbutton .= Tpgb_Blocks_Global_Options::load_plusButton_saves( $attributes );

	$output         .= '<div class="tpgb-messagebox tpgb-relative-block tpgb-block-' . esc_attr( $block_id ) . ' ' . esc_attr( $block_class ) . '">';
		$output     .= '<div class="messagebox-bg-box tpgb-relative-block ' . esc_attr( $tnslin ) . '">';
			$output .= '<div class="message-media ' . esc_attr( $tnslin ) . '">';
	if ( ! empty( $icon ) && 'prefix' === $icn_position ) {
		$output .= $get_icon;
	}
				$output         .= '<div class="msg-content ' . esc_attr( $tnslin ) . '">';
					$output     .= '<div class="msg-inner-body ' . esc_attr( $with_btn_css ) . '">';
						$output .= $get_title;
	if ( ! empty( $ext_btnshow ) ) {
		$output .= $getbutton;
	}
					$output .= '</div>';
					$output .= $get_desc;
				$output     .= '</div>';
	if ( ! empty( $dismiss ) ) {
		$output .= $get_dismiss;
	}
	if ( ! empty( $icon ) && 'postfix' === $icn_position ) {
		$output .= $get_icon;
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
function tpgb_tp_messagebox() {
	$block_data = Tpgb_Blocks_Global_Options::merge_options_json( __DIR__, '', true, false, true );
	register_block_type( $block_data['name'], $block_data );
}
add_action( 'init', 'tpgb_tp_messagebox' );
