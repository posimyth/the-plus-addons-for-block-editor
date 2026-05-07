<?php
/**
 * Blog Style 1.
 *
 * @package ThePluginAddonsForBlockEditor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$bg_attr = '';
if ( ! empty( $layout ) && 'metro' === $layout ) {
	$thumbnail_id   = get_post_thumbnail_id( get_the_ID() );
	$featured_image = wp_get_attachment_url( $thumbnail_id );

	if ( ! empty( $featured_image ) ) {
		$bg_attr = 'style="background: url(' . esc_url( $featured_image ) . '); background-size: cover; background-repeat: no-repeat;"';
	}
}
?>
<div class="dynamic-list-content tpgb-dynamic-tran">

	<?php if ( 'metro' === $layout ) { ?>
		<div class="tpgb-post-featured-img tpgb-dynamic-tran <?php echo esc_attr( $image_hover_style ); ?>">
			<a href="<?php echo esc_url( get_the_permalink() ); ?>" aria-label="<?php echo esc_attr( get_the_title() ); ?>">
				<?php echo '<div class="tpgb-blog-image-metro" ' . $bg_attr . ' ></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Output is escaped inside $bg_attr. ?>
			</a>
		</div>
	<?php } ?>

	<?php require TPGB_INCLUDES_URL . 'blog/format-image.php'; ?>

	<div class="tpgb-content-bottom">
		<?php if ( ! empty( $show_post_meta ) && 'yes' === $show_post_meta ) { ?>
			<?php include TPGB_INCLUDES_URL . 'blog/' . sanitize_file_name( 'post-meta-' . $post_meta_style . '.php' ); ?>
		<?php } ?>

		<?php
		if ( ! empty( $show_title ) && 'yes' === $show_title ) {
			include TPGB_INCLUDES_URL . 'blog/post-title.php';
		}
		?>

		<div class="tpgb-post-hover-content">
			<?php
			if ( ! empty( $show_excerpt ) && 'yes' === $show_excerpt && get_the_excerpt() ) {
				include TPGB_INCLUDES_URL . 'blog/get-excerpt.php';
			}
			?>
		</div>
	</div>
</div>
