<?php
/**
 * External Form Styler.
 *
 * @package ThePluginAddonsForBlockEditor
 */

defined( 'ABSPATH' ) || exit;

/**
 * Tpgb external form styler render callback.
 *
 * @param mixed $attributes The attributes.
 * @param mixed $content The content.
 * @return mixed The result.
 */
function tpgb_external_form_styler_render_callback( $attributes, $content ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
	$block_id        = ( ! empty( $attributes['block_id'] ) ) ? $attributes['block_id'] : uniqid( 'title' );
	$contact_form    = ( ! empty( $attributes['contactForm'] ) ) ? $attributes['contactForm'] : '';
	$form_type       = ( ! empty( $attributes['formType'] ) ) ? $attributes['formType'] : 'contact-form-7';
	$title_show      = ( ! empty( $attributes['titleShow'] ) ) ? $attributes['titleShow'] : false;
	$outer_sec_style = ( ! empty( $attributes['outerSecStyle'] ) ) ? $attributes['outerSecStyle'] : 'tpgb-cf7-label';

	$block_class = Tp_Blocks_Helper::block_wrapper_classes( $attributes );

	$title_show_line = '';
	if ( 'gravity-form' === $form_type && ! empty( $title_show ) ) {
		$title_show_line .= 'title=true description=true';
	} elseif ( 'gravity-form' === $form_type && empty( $title_show ) ) {
		$title_show_line .= 'title=false description=false';
	}
	$cf7class = '';
	if ( 'contact-form-7' === $form_type ) {
		$cf7class = $outer_sec_style;
	}
	$output  = '';
	$output .= '<div class="tpgb-external-form-styler tpgb-relative-block tpgb-block-' . esc_attr( $block_id ) . ' ' . esc_attr( $block_class ) . '">';
	if ( '' === $contact_form ) {
		$output .= '<div class="tpgb-select-form-alert">' . esc_html__( 'Please select Form', 'the-plus-addons-for-block-editor' ) . '</div>';
	} else {
		$sc        = 'id="' . esc_attr( $contact_form ) . '"';
		$shortcode = array();
		if ( 'contact-form-7' === $form_type ) {
			$shortcode[] = sprintf( '[contact-form-7 %s]', $sc );
		} elseif ( 'everest-form' === $form_type ) {
			$shortcode[] = sprintf( '[everest_form %s]', $sc );
		} elseif ( 'gravity-form' === $form_type ) {
			$shortcode[] = sprintf( '[gravityform %s ' . $title_show_line . ']', $sc );
		} elseif ( 'ninja-form' === $form_type ) {
			$shortcode[] = sprintf( '[ninja_form %s]', $sc );
		} elseif ( 'wp-form' === $form_type ) {
			$shortcode[] = sprintf( '[wpforms %s]', $sc );
		}

		$shortcode_str = implode( '', $shortcode );

		$output     .= '<div class="tpgb-' . esc_attr( $form_type ) . ' ' . esc_attr( $cf7class ) . '">';
			$output .= do_shortcode( $shortcode_str );
		$output     .= '</div>';
	}
	$output .= '</div>';

	$output = Tpgb_Blocks_Global_Options::block_Wrap_Render( $attributes, $output );

	return $output;
}
/**
 * Tpgb get form rendered.
 */
function tpgb_get_form_rendered() {
	if ( ! current_user_can( 'edit_posts' ) ) {
		wp_die( 'You can not Permission.' );
	}
	if ( ! isset( $_POST['tpgb_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['tpgb_nonce'] ) ), 'tpgb-addons' ) ) {
		wp_die( 'Security check failed.' );
	}
	$form_id   = isset( $_POST['form_id'] ) ? sanitize_text_field( wp_unslash( $_POST['form_id'] ) ) : '';
	$form_type = isset( $_POST['form_type'] ) ? sanitize_text_field( wp_unslash( $_POST['form_type'] ) ) : '';

	if ( ! empty( $form_id ) && 'contact-form-7' === $form_type ) {
		echo do_shortcode( '[contact-form-7 id=' . esc_attr( $form_id ) . ']' );
	} elseif ( ! empty( $form_id ) && 'everest-form' === $form_type ) {
		echo do_shortcode( '[everest_form id=' . esc_attr( $form_id ) . ']' );
	} elseif ( ! empty( $form_id ) && 'gravity-form' === $form_type ) {
		echo do_shortcode( '[gravityform id=' . esc_attr( $form_id ) . ' title=false description=false]' );
	} elseif ( ! empty( $form_id ) && 'ninja-form' === $form_type ) {
		echo do_shortcode( '[ninja_form id=' . esc_attr( $form_id ) . ']' );
	} elseif ( ! empty( $form_id ) && 'wp-form' === $form_type ) {
		echo do_shortcode( '[wpforms id=' . esc_attr( $form_id ) . ']' );
	}
	exit();
}
add_action( 'wp_ajax_tpgb_external_form_ajax', 'tpgb_get_form_rendered' );
/**
 * Render for the server-side
 */
function tpgb_external_form_styler() {

	if ( method_exists( 'Tpgb_Blocks_Global_Options', 'merge_options_json' ) ) {
		$block_data = Tpgb_Blocks_Global_Options::merge_options_json( __DIR__, 'tpgb_external_form_styler_render_callback' );
		register_block_type( $block_data['name'], $block_data );
	}
}
add_action( 'init', 'tpgb_external_form_styler' );
