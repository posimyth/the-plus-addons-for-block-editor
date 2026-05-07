<?php
/**
 * Format Image.
 *
 * @package ThePluginAddonsForBlockEditor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
	$image_url = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) );

if ( ! empty( $image_url ) ) {
	if ( ! empty( $layout ) && 'grid' === $layout ) {
		$featured_image = get_the_post_thumbnail( get_the_ID(), 'tp-image-grid', array( 'class' => 'tpgb-d-block tpgb-post-img tpgb-dynamic-tran' ) );
	} elseif ( ! empty( $layout ) && 'masonry' === $layout ) {
		$featured_image = get_the_post_thumbnail( get_the_ID(), 'full', array( 'class' => 'tpgb-d-block tpgb-post-img tpgb-dynamic-tran' ) );
	} elseif ( ! empty( $layout ) && 'carousel' === $layout ) {
		$featured_image = get_the_post_thumbnail( get_the_ID(), 'tp-image-grid', array( 'class' => 'tpgb-d-block tpgb-post-img tpgb-dynamic-tran' ) );
	} else {
		$featured_image = get_the_post_thumbnail( get_the_ID(), 'full', array( 'class' => 'tpgb-d-block tpgb-post-img tpgb-dynamic-tran' ) );
	}
} else {
	$featured_image = Tp_Blocks_Helper::get_default_thumb();
	$featured_image = '<img src="' . esc_url( $featured_image ) . '" alt="' . esc_attr( get_the_title() ) . '"  class="tpgb-d-block tpgb-post-img tpgb-dynamic-tran">';
}
?>
	<div class="tpgb-post-featured-img tpgb-dynamic-tran <?php echo esc_attr( $image_hover_style ); ?>">
		<a href="<?php echo esc_url( get_the_permalink() ); ?>" aria-label="<?php echo esc_attr( get_the_title() ); ?>">
			<?php echo $featured_image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Output is escaped inside $featured_image. ?>
		</a>
	</div>
