<?php
/**
 * Social Feed Ob Style.
 *
 * @package ThePluginAddonsForBlockEditor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
if ( ! empty( $video_url ) ) {
	$iconlogo = '<div class="tpgb-sf-logo">
            <a href="' . ( ( is_array( $video_url ) && isset( $video_url[0]['link'] ) && ! empty( $video_url[0]['link'] ) ) ? esc_url( $video_url[0]['link'] ) : esc_url( $video_url ) ) . '" 
               class="tpgb-sf-logo-link" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr__( 'Post URL', 'the-plus-addons-for-block-editor' ) . '">
                <i class="' . esc_attr( $social_icon ) . '"></i>
            </a>
        </div>';
}

if ( ( ( 'style-1' === $style || 'style-2' === $style ) && 'Facebook' === $select_feed && 'GoWebsite' === $popup_option && ! empty( $image_url ) ) ) {
	echo '<a href="' . ( ( is_array( $video_url ) && isset( $video_url[0]['link'] ) && ! empty( $video_url[0]['link'] ) ) ? esc_url( $video_url[0]['link'] ) : esc_url( $video_url ) ) . '" class="tpgb-sf-logo-link" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr__( 'Post URL', 'the-plus-addons-for-block-editor' ) . '"> <img class="tpgb-post-thumb" src="' . esc_url( $image_url ) . '"> </a>';
}

	ob_start();
		echo '<div class="tpgb-sf-header">';
if ( ! empty( $user_image ) ) {
	echo '<div class="tpgb-sf-profile"><img class="tpgb-sf-logo" src="' . esc_url( $user_image ) . '" alt="' . esc_attr__( 'User Profile', 'the-plus-addons-for-block-editor' ) . '"/></div>';
}
			echo '<div class="tpgb-sf-usercontact">';
if ( ! empty( $user_name ) ) {
	echo '<div class="tpgb-sf-username">
							<a href="' . esc_url( $user_link ) . '" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr( $user_name ) . '">' . wp_kses_post( $user_name ) . '</a></div>';
}
if ( ! empty( $created_time ) ) {
	echo '<div class="tpgb-sf-time">
							<a href="' . ( ( is_array( $video_url ) && isset( $video_url[0]['link'] ) && ! empty( $video_url[0]['link'] ) ) ? esc_url( $video_url[0]['link'] ) : esc_url( $video_url ) ) . '"  target="_blank" rel="noopener noreferrer" alt="' . esc_attr__( 'Post URL', 'the-plus-addons-for-block-editor' ) . '">' . wp_kses_post( $created_time ) . '</a></div>';
}
			echo '</div>';
if ( isset( $iconlogo ) && ( ! empty( $social_icon ) && 'style-3' !== $style ) || ( empty( $image_url ) && 'style-3' === $style ) ) { // phpcs:ignore Generic.CodeAnalysis.RequireExplicitBooleanOperatorPrecedence.MissingParentheses
	echo wp_kses_post( $iconlogo );
}
		echo '</div>';
	$header_html = ob_get_clean();

	// Title.
	$massage_html = '';
if ( ! empty( $show_title ) ) {
	ob_start();
		echo '<div class="tpgb-title">' . wp_kses_post( $massage ) . '</div>';
	$massage_html = ob_get_clean();
}
