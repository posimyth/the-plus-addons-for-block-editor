<?php
/**
 * Blog Style 6.
 *
 * @package ThePluginAddonsForBlockEditor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$bg_attr        = '';
$thumbnail_id   = get_post_thumbnail_id( get_the_ID() );
$featured_image = wp_get_attachment_url( $thumbnail_id );

if ( ! empty( $featured_image ) && ! empty( $layout ) && 'metro' === $layout ) {
	$bg_attr = 'style="background: url(' . esc_url( $featured_image ) . '); background-size: cover; background-repeat: no-repeat; background-position: center;"';
}
?>
<div class="dynamic-list-content tpgb-dynamic-tran">

	<div class="tpgb-post-featured-img tpgb-dynamic-tran <?php echo esc_attr( $image_hover_style ); ?>">
		<a href="<?php echo esc_url( get_the_permalink() ); ?>" aria-label="<?php echo esc_attr( get_the_title() ); ?>">

			<?php if ( ! empty( $layout ) && 'metro' === $layout ) { ?>
				<div class="tpgb-blog-image-metro" <?php echo $bg_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Output is escaped inside $bg_attr. ?>></div>
			<?php } else { ?>
				<?php if ( ! empty( $featured_image ) ) { ?>
					<img class="tpgb-blog-image" src="<?php echo esc_url( $featured_image ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" loading="lazy" />
				<?php } ?>
			<?php } ?>

		</a>
	</div>

	<!-- Category Section -->
	<?php if ( 'yes' === $show_post_category ) { ?>
		<?php include TPGB_INCLUDES_URL . 'blog/' . sanitize_file_name( 'category-' . $post_category_style . '.php' ); ?>
	<?php } ?>

	<!-- Content Bottom -->
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

		<?php if ( ! empty( $show_button ) && 'yes' === $show_button ) { ?>
			<div class="tpgb-adv-button button-<?php echo esc_attr( $post_btnsty ); ?>"> 
				<a class="button-link-wrap" href="<?php echo esc_url( get_the_permalink() ); ?>" > 
					<?php
					if ( 'style-8' === $post_btnsty ) {
						if ( 'before' === $btn_icon_posi ) {
							?>
							<span class="btn-icon  button-<?php echo esc_attr( $btn_icon_posi ); ?>"> 
								<i class="<?php echo esc_attr( $pobtn_icon_name ); ?>" > </i>
							</span>
							<?php echo esc_html( $postbtntext ); ?>
							<?php
						} else {
							?>
							<?php echo esc_html( $postbtntext ); ?>
							<span class="btn-icon  button-<?php echo esc_attr( $btn_icon_posi ); ?>"> 
								<i class="<?php echo esc_attr( $pobtn_icon_name ); ?>"> </i>
							</span>
							<?php
						}
					} elseif ( 'style-7' === $post_btnsty || 'style-9' === $post_btnsty ) {
						echo esc_html( $postbtntext );
						?>
						<span class='button-arrow'> 
						<?php if ( 'style-7' === $post_btnsty ) { ?> 
								<span class='btn-right-arrow'><i class="fas fa-chevron-right"></i></span>  
							<?php }  if ( 'style-9' === $post_btnsty ) { ?>
								<i class="btn-show fas fa-chevron-right"></i>
								<i class="btn-hide fas fa-chevron-right"></i>
							<?php } ?>
						</span>
						<?php
					}
					?>
				</a>
			</div>
		<?php } ?>
	</div>
</div>
