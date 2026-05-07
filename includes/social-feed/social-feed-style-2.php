<?php
/**
 * Social Feed Style 2.
 *
 * @package ThePluginAddonsForBlockEditor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<div class="tpgb-sf-feed tpgb-trans-linear">

	<?php
		require TPGB_INCLUDES_URL . 'social-feed/social-feed-ob-style.php';
			echo wp_kses_post( $header_html );

	if ( ! empty( $massage ) ) {
		echo wp_kses_post( $massage_html );
	}

	if ( ! empty( $description ) && empty( $descrip_btm ) ) {
		include TPGB_INCLUDES_URL . 'social-feed/feed-Description.php';
	}

	if ( 'default' === $media_filter || 'ompost' === $media_filter ) {
		include TPGB_INCLUDES_URL . 'social-feed/fancybox-feed.php';
	}

	if ( ! empty( $description ) && ! empty( $descrip_btm ) ) {
		include TPGB_INCLUDES_URL . 'social-feed/feed-Description.php';
	}
			require TPGB_INCLUDES_URL . 'social-feed/feed-footer.php';
	?>

</div>
