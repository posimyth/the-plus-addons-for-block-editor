<?php
/**
 * Get Excerpt.
 *
 * @package ThePluginAddonsForBlockEditor
 */

defined( 'ABSPATH' ) || exit;

$excerpt = get_the_excerpt();
global $post;

if ( 'words' === $excerpt_by_limit ) {

	$limit_words = explode( ' ', get_the_excerpt(), $excerpt_limit );
	if ( count( $limit_words ) >= $excerpt_limit ) {
		array_pop( $limit_words );
		$excerpt = implode( ' ', $limit_words ) . '...';
	} else {
		$excerpt = implode( ' ', $limit_words );
	}
} elseif ( 'letters' === $excerpt_by_limit ) {

	$limit_words = substr( get_the_excerpt(), 0, $excerpt_limit );
	if ( strlen( $excerpt ) > $excerpt_limit ) {
		$excerpt = $limit_words . '...';
	} else {
		$excerpt = $limit_words;
	}
}
?>
<?php if ( ! empty( $excerpt ) ) { ?>
	<div class="tpgb-post-excerpt tpgb-d-block tpgb-dynamic-tran"><p><?php echo $excerpt; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Output is escaped inside $excerpt. ?></p></div>
<?php } ?>
