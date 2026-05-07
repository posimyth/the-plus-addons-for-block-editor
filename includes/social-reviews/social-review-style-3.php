<?php
/**
 * Social Review Style 3.
 *
 * @package ThePluginAddonsForBlockEditor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
	<div class="grid-item <?php echo esc_attr( $desktop_class ) . esc_attr( $tablet_class ) . esc_attr( $mobile_class ) . ' ' . esc_attr( $r_key ) . ' ' . esc_attr( $review_class ); ?>">
	<?php
		require TPGB_INCLUDES_URL . 'social-reviews/social-review-ob-style.php';
		echo '<div class="review-s3-wrap">';
			echo '<div class="tpgb-review tpgb-trans-linear ' . esc_attr( $err_class ) . '">';
				echo '<div class="review-top-area">';
					echo wp_kses_post( $star_html );
	if ( empty( $dis_social_icon ) ) {
		echo wp_kses_post( $logo_html );
	}
				echo '</div>';
				echo wp_kses_post( $description_html );
			echo '</div>';
			echo '<div class="tpgb-sr-header tpgb-trans-linear">';
	if ( empty( $dis_profile_icon ) ) {
		echo wp_kses_post( $profile_html );
	}
				echo '<div class="tpgb-sr-separator">';
					echo wp_kses_post( $user_name_html );
					echo wp_kses_post( $time_html );
				echo '</div>';
			echo '</div>';
		echo '</div>';
	?>
</div>
