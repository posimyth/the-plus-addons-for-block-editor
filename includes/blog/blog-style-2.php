<?php
/**
 * Blog Style 2.
 *
 * @package ThePluginAddonsForBlockEditor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// condition uncommented as it was making issue for metro style.
	$bg_attr = '';
if ( ! empty( $layout ) && 'metro' === $layout ) {
	$thumbnail_id   = get_post_thumbnail_id( get_the_ID() );
	$featured_image = wp_get_attachment_url( $thumbnail_id );

	if ( ! empty( $featured_image ) ) {
		$bg_attr = 'style="background:url(' . $featured_image . ');"';
	}
}
	// end.
?>

<div class="dynamic-list-content tpgb-dynamic-tran">
	
	<?php if ( 'metro' !== $layout ) { ?> 
	<div class="post-content-image">
		<?php include TPGB_INCLUDES_URL . 'blog/format-image.php'; ?>
		<?php if ( 'yes' === $show_post_category && 'style-2' === $style_layout ) { ?>
			<?php include TPGB_INCLUDES_URL . 'blog/' . sanitize_file_name( 'category-' . $post_category_style . '.php' ); ?>
		<?php } ?>
	</div>
	<?php } ?>
	
	<div class="tpgb-content-bottom <?php echo ( 'center' === $style2_alignment ) ? 'text-center' : 'text-left'; ?>">
		<?php
		if ( 'yes' === $show_post_category && 'style-1' === $style_layout || ( ( 'style-2' === $style_layout && 'metro' === $layout ) ) ) { // phpcs:ignore Generic.CodeAnalysis.RequireExplicitBooleanOperatorPrecedence.MissingParentheses
			?>
			// phpcs:ignore Generic.CodeAnalysis.RequireExplicitBooleanOperatorPrecedence.MissingParentheses
			<div class="tpgb-post-metro-category-top <?php echo ( 'center' === $style2_alignment ) ? 'text-center' : 'text-left'; ?>">
			<?php include TPGB_INCLUDES_URL . 'blog/' . sanitize_file_name( 'category-' . $post_category_style . '.php' ); ?>
			</div>
		<?php } ?>
		<?php if ( 'metro' === $layout ) { ?>
			<div class="tpgb-post-metro-content">
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

			if ( ! empty( $show_post_meta ) && 'yes' === $show_post_meta ) {
				include TPGB_INCLUDES_URL . 'blog/' . sanitize_file_name( 'post-meta-' . $post_meta_style . '.php' );
			}
			?>
		</div>
		<?php if ( 'metro' === $layout ) { ?>
			</div>
		<?php } ?>
	</div>
	<?php if ( 'metro' === $layout ) { ?>
		<div class="tpgb-post-featured-img tpgb-dynamic-tran <?php echo esc_attr( $image_hover_style ); ?>">
			<a href="<?php echo esc_url( get_the_permalink() ); ?>" aria-label="<?php echo esc_attr( get_the_title() ); ?>" <?php echo $new_tab_post_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Output is escaped inside $new_tab_post_attr. ?>>
				<?php echo '<div class="tpgb-blog-image-metro"  ' . $bg_attr . ' ></div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Output is escaped inside $bg_attr. ?>
			</a>
		</div>
	<?php } ?>
	<?php
	if ( 'searchList' === $post_listing || 'search_list' === $post_listing ) {
		include TPGB_INCLUDES_URL . 'blog/blog-skeleton.php';
	}
	?>
</div>
