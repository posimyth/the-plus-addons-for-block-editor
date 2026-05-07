<?php
/**
 * Fancybox Header.
 *
 * @package ThePluginAddonsForBlockEditor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

echo '<div class="tpgb-fcb-header">';
if ( ! empty( $user_image ) ) {
	echo '<img class="tpgb-fcb-profile" src="' . esc_url( $user_image ) . '"/>';
}
	echo '<div class="tpgb-fcb-usercontact">';
if ( ! empty( $user_name ) ) {
	echo '<div class="tpgb-fcb-username">
                    <a href="' . esc_url( $user_link ) . '" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr( $user_name ) . '">' . wp_kses_post( $user_name ) . '</a>
                    </div>';
}
if ( ! empty( $created_time ) ) {
	echo '<div class="tpgb-fcb-time">
                    <a href="' . esc_url( $post_link ) . '" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr( $created_time ) . '">' . wp_kses_post( $created_time ) . '</a>
                    </div>';
}
	echo '</div>';
if ( ! empty( $social_icon ) ) {
	echo '<div class="tpgb-fcb-logo">
                <i class="' . esc_attr( $social_icon ) . '"></i>
                </div>';
}
echo '</div>';
