<?php
/**
 * Social Review Style 1.
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
	?>
	<div class="tpgb-review tpgb-trans-linear <?php echo esc_attr( $err_class ); ?>">
		<?php
			echo '<div class="tpgb-sr-header tpgb-trans-linear">';
		if ( empty( $dis_profile_icon ) ) {
			echo wp_kses_post( $profile_html );
		}
				echo '<div class="header-inner-content">';
					echo wp_kses_post( $user_name_html );
					echo wp_kses_post( $star_html );
				echo '</div>';
			echo '</div>';
			echo wp_kses_post( $description_html );
		?>

		<div class="tpgb-sr-bottom tpgb-trans-linear">
			<div class="bottom-left-content">
				<?php
				if ( empty( $dis_social_icon ) ) {
					echo wp_kses_post( $logo_html );
				}
				?>
				<div class="tpgb-sr-logotext tpgb-trans-linear">
					<span class="tpgb-newline tpgb-trans-linear"><?php echo esc_html__( 'Posted On ', 'the-plus-addons-for-block-editor' ); ?></span>
					<span class="tpgb-newline tpgb-trans-linear"><?php echo esc_html( $platform_name ); ?></span>
				</div>
			</div>
			<?php echo wp_kses_post( $time_html ); ?>
		</div>
	</div>
</div>
