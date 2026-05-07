<?php
/**
 * Post Title.
 *
 * @package ThePluginAddonsForBlockEditor
 */

defined( 'ABSPATH' ) || exit;
?>
<<?php echo Tp_Blocks_Helper::validate_html_tag( $title_tag ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Output is escaped inside $title_tag. ?> class="tpgb-post-title tpgb-dynamic-tran">
	<a href="<?php echo esc_url( get_the_permalink() ); ?>"><?php echo wp_kses_post( get_the_title() ); ?></a>
</<?php echo Tp_Blocks_Helper::validate_html_tag( $title_tag ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Output is escaped inside $title_tag. ?>>
