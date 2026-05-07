<?php
/**
 * Meta Date.
 *
 * @package ThePluginAddonsForBlockEditor
 */

defined( 'ABSPATH' ) || exit;
if ( ! empty( $show_date ) && 'yes' === $show_date ) { ?>
	<span class="post-meta-date"><a href="<?php echo esc_url( get_the_permalink() ); ?>" class="tpgb-dynamic-tran post-entry-date"><?php echo get_the_date(); ?></a></span>
<?php } ?>
