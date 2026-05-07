<?php
/**
 * Social Review Ob Style.
 *
 * @package ThePluginAddonsForBlockEditor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

	$description_html = '';
	ob_start();
		echo '<div class="tpgb-sr-content tpgb-trans-linear">';
			require TPGB_INCLUDES_URL . 'social-reviews/social-review-showmore.php';
		echo '</div>';
	$description_html .= ob_get_clean();

	// Start Icon.
		$star_html  = '';
		$star_html .= '<div class="tpgb-sr-star">';
for ( $i = 0; $i < $rating; $i++ ) {
	$star_html .= '<i star-rating="' . esc_attr( $i ) . '" class="' . esc_attr( $icon ) . ' sr-star"></i>';
}
		$star_html .= '</div>';

	// Username.
		$user_name_html      = '';
		$user_name_html     .= '<div class="tpgb-sr-username tpgb-trans-linear">';
			$user_name_html .= '<a class="tpgb-trans-linear" href="' . esc_url( $u_link ) . '" target="_blank" aria-label="' . esc_attr__( 'Review URL', 'the-plus-addons-for-block-editor' ) . '">' . esc_html( $u_name ) . '</a>';
		$user_name_html     .= '</div>';

	// logo Image.
		$logo_html = '<a href="' . esc_url( $page_link ) . '" target="_blank" aria-label="' . esc_attr__( 'Page URL', 'the-plus-addons-for-block-editor' ) . '"><img class="tpgb-sr-logo" src="' . esc_url( $logo ) . '" alt="' . esc_html__( 'Social Logo', 'the-plus-addons-for-block-editor' ) . '" /></a>';

	// Time.
		$time_html = '<div class="tpgb-sr-time tpgb-trans-linear">' . esc_html( $time ) . '</div>';

	// Profile.
		$profile_html = '<img class="tpgb-sr-profile" src="' . esc_url( $u_image ) . '" alt="' . esc_attr__( 'User Profile', 'the-plus-addons-for-block-editor' ) . '"/>';
