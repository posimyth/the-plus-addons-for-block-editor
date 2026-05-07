<?php
/**
 * Form Block.
 *
 * @package ThePluginAddonsForBlockEditor
 */

defined( 'ABSPATH' ) || exit;


/**
 * Nxt form block callback.
 *
 * @param mixed $attr The attr.
 * @param mixed $content The content.
 * @return mixed The result.
 */
function nxt_form_block_callback( $attr, $content ) {
	$pattern = '/\btpgb-wrap-/';
	$output  = '';
	if ( preg_match( $pattern, $content ) ) {
		if ( class_exists( 'Tpgb_Blocks_Global_Options' ) ) {
			$global_blocks = Tpgb_Blocks_Global_Options::get_instance();
			$content       = $global_blocks::block_row_conditional_render( $attr, $content );
		}
		return $content;
	}
	$block_id        = ( isset( $attr['block_id'] ) && ! empty( $attr['block_id'] ) ) ? $attr['block_id'] : '';
	$layout_type     = ( isset( $attr['layoutType'] ) && ! empty( $attr['layoutType'] ) ) ? $attr['layoutType'] : '';
	$action_option   = ( isset( $attr['actionOption'] ) && ! empty( $attr['actionOption'] ) ) ? $attr['actionOption'] : '';
	$subject1        = ( isset( $attr['subject1'] ) && ! empty( $attr['subject1'] ) ) ? $attr['subject1'] : '';
	$email_to1       = ( isset( $attr['emailTo1'] ) && ! empty( $attr['emailTo1'] ) ) ? $attr['emailTo1'] : '';
	$selected_layout = ( isset( $attr['selectedLayout'] ) && ! empty( $attr['selectedLayout'] ) ) ? $attr['selectedLayout'] : '';
	$auto_resp_msg   = ( isset( $attr['autoRespMsg'] ) && ! empty( $attr['autoRespMsg'] ) ) ? $attr['autoRespMsg'] : '';
	$cc_email1       = ( isset( $attr['ccEmail1'] ) && ! empty( $attr['ccEmail1'] ) ) ? $attr['ccEmail1'] : '';
	$bcc_email1      = ( isset( $attr['bccEmail1'] ) && ! empty( $attr['bccEmail1'] ) ) ? $attr['bccEmail1'] : '';
	$email_hdg       = ( isset( $attr['emailHdg'] ) && ! empty( $attr['emailHdg'] ) ) ? $attr['emailHdg'] : '';
	$frm_email       = ( isset( $attr['frmEmail'] ) && ! empty( $attr['frmEmail'] ) ) ? $attr['frmEmail'] : '';
	$frm_nme         = ( isset( $attr['frmNme'] ) && ! empty( $attr['frmNme'] ) ) ? $attr['frmNme'] : '';
	$reply_to        = ( isset( $attr['replyTo'] ) && ! empty( $attr['replyTo'] ) ) ? $attr['replyTo'] : '';
	$redirect        = ( isset( $attr['redirect'] ) && ! empty( $attr['redirect'] ) ? $attr['redirect'] : '' );
	$action_option   = ( isset( $attr['actionOption'] ) && ! empty( $attr['actionOption'] ) ? $attr['actionOption'] : '' );
	$meta_data_opt   = ( isset( $attr['metaDataOpt'] ) && ! empty( $attr['metaDataOpt'] ) ? $attr['metaDataOpt'] : '' );
	$form_id         = ( isset( $attr['formId'] ) && ! empty( $attr['formId'] ) ? $attr['formId'] : '' );
	$fail_msg        = ( isset( $attr['failMsg'] ) && ! empty( $attr['failMsg'] ) ? $attr['failMsg'] : '' );
	$val_err_msg     = ( isset( $attr['valErrMsg'] ) && ! empty( $attr['valErrMsg'] ) ? $attr['valErrMsg'] : '' );

	$block_class = Tp_Blocks_Helper::block_wrapper_classes( $attr );

	$action_option_string = '';

	if ( is_string( $action_option ) ) {
		$action_option_string = $action_option;
	} else {
		$action_option_string = wp_json_encode( $action_option );
	}
	$form_data_attributes          = array(
		'emailTo1'     => $email_to1,
		'subject1'     => $subject1,
		'actionOption' => 'email',
		'ccEmail1'     => $cc_email1,
		'bccEmail1'    => $bcc_email1,
		'emailHdg'     => $email_hdg,
		'frmEmail'     => $frm_email,
		'frmNme'       => $frm_nme,
		'replyTo'      => $reply_to,
		'block_id'     => $block_id,
		'metaDataOpt'  => $meta_data_opt,
		'failMsg'      => $fail_msg,
		'valErrMsg'    => $val_err_msg,
	);
	$filtered_form_data_attributes = array_filter(
		$form_data_attributes,
		function ( $value ) {
			return ! empty( $value );
		}
	);

	$encrypted_form_data = Tp_Blocks_Helper::tpgb_simple_decrypt( wp_json_encode( $filtered_form_data_attributes ), 'ey' );

	$redirect_url      = is_array( $redirect ) && isset( $redirect['url'] ) ? $redirect['url'] : '';
	$redirect_target   = is_array( $redirect ) && isset( $redirect['target'] ) ? $redirect['target'] : '';
	$redirect_nofollow = is_array( $redirect ) && isset( $redirect['nofollow'] ) ? $redirect['nofollow'] : '';

	$data_redirect = '';
	if ( ! empty( $redirect_url ) ) {
		$data_redirect .= 'data-redirect="' . esc_url( $redirect_url ) . '"';
	}
	if ( 1 === $redirect_target ) {
		$data_redirect .= ' data-link-blank="1"';
	}
	if ( 1 === $redirect_nofollow ) {
		$data_redirect .= ' data-link-nofollow="1"';
	}
	$data_action = 'data-actionOption="' . esc_attr( $action_option_string ) . '"';

	$output          = '<div class="tp-form-block ' . esc_attr( $layout_type ) . ' tpgb-relative-block tpgb-block-' . esc_attr( $block_id ) . ' ' . esc_attr( $block_class ) . '" data-block-id="' . esc_attr( $block_id ) . '">';
		$output     .= '<form id="' . esc_attr( $form_id ) . '" class="nxt-form" data-formdata=' . $encrypted_form_data . ' ' . $data_redirect . ' ' . $data_action . '>';
			$output .= $content;
		$output     .= ' </form>';
		$output     .= '<span class="nxt-success-message" data-success-message="' . esc_attr( $auto_resp_msg ) . '"></span>';
	$output         .= '</div>';

	return Tpgb_Blocks_Global_Options::block_Wrap_Render( $attr, $output );
}

/**
 * Nxt form block render.
 */
function nxt_form_block_render() {
	$block_data                    = Tpgb_Blocks_Global_Options::merge_options_json( __DIR__, 'nxt_form_block_callback' );
	$block_data['render_callback'] = 'nxt_form_block_callback';
	register_block_type( $block_data['name'], $block_data );
}
add_action( 'init', 'nxt_form_block_render' );
