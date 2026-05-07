<?php
/**
 * Social Feed Style 4.
 *
 * @package ThePluginAddonsForBlockEditor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<div class="tpgb-sf-feed tpgb-trans-linear">
	<div class="tpgb-sf-contant-img tpgb-trans-easeinout-before" style="background-image: url('<?php echo esc_url( $image_url ); ?>');">
		<?php
			echo '<div class="tpgb-sf-contant tpgb-relative-block tpgb-trans-easeinout">';
			require TPGB_INCLUDES_URL . 'social-feed/social-feed-ob-style.php';

		if ( ! empty( $massage ) ) {
			echo wp_kses_post( $massage_html );
		}
		if ( ! empty( $description ) ) {
			include TPGB_INCLUDES_URL . 'social-feed/feed-Description.php';
		}
				echo wp_kses_post( $header_html );
				require TPGB_INCLUDES_URL . 'social-feed/feed-footer.php';
			echo '</div>';

			require TPGB_INCLUDES_URL . 'social-feed/fancybox-feed.php';
		?>
	</div>
</div>
