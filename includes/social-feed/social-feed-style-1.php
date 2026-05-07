<?php
/**
 * Social Feed Style 1.
 *
 * @package ThePluginAddonsForBlockEditor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<div class="tpgb-sf-feed tpgb-trans-linear <?php echo esc_attr( $error_class ); ?>">
	
	<?php
		require TPGB_INCLUDES_URL . 'social-feed/social-feed-ob-style.php';

	if ( 'default' === $media_filter || 'ompost' === $media_filter ) {
		include TPGB_INCLUDES_URL . 'social-feed/fancybox-feed.php';
	}

	if ( ! empty( $massage ) ) {
		echo wp_kses_post( $massage_html );
	}

	if ( ! empty( $description ) ) {
		include TPGB_INCLUDES_URL . 'social-feed/feed-Description.php';
	}
			echo wp_kses_post( $header_html );

			require TPGB_INCLUDES_URL . 'social-feed/feed-footer.php';
	?>
</div>
