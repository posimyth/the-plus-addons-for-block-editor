<?php
/**
 * Social Feed Style 3.
 *
 * @package ThePluginAddonsForBlockEditor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<div class="tpgb-sf-feed tpgb-trans-linear tpgb-d-flex tpgb-flex-row">
	<?php
		$imghideclass = '';
	if ( empty( $image_url ) ) {
		$imghideclass = 'tpgb-soc-image-not-found';
	}

		echo '<div class="tpgb-sf-contant ' . esc_attr( $imghideclass ) . '">';
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

	if ( ! empty( $image_url ) ) {
		?>

		<div class="tpgb-sf-contant-img" style="background-image: url('<?php echo esc_url( $image_url ); ?>');">
			<?php
				echo wp_kses_post( $iconlogo );
				include TPGB_INCLUDES_URL . 'social-feed/fancybox-feed.php';
			?>
		</div>
		<?php
	}
	?>
</div>
