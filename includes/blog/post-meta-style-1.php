<?php
/**
 * Post Meta Style 1.
 *
 * @package ThePluginAddonsForBlockEditor
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="post-meta-info post-info-style-1">
	<?php
	require TPGB_INCLUDES_URL . 'blog/meta-date.php';
	if ( ! empty( $show_date ) && 'yes' === $show_date && ! empty( $show_author ) && 'yes' === $show_author ) {
		?>
		<span class="tpgb-dynamic-tran">|</span> 
		<?php
	}
	if ( ! empty( $show_author ) && 'yes' === $show_author ) {
		?>
		<span class="post-meta-author tpgb-dynamic-tran"><?php echo wp_kses_post( $author_txt ); ?> <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author" class="tpgb-dynamic-tran"><?php echo get_the_author(); ?></a></span>
	<?php } ?>
</div>

