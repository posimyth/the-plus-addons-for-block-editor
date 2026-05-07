<?php
/**
 * Feed Description.
 *
 * @package ThePluginAddonsForBlockEditor
 */

// phpcs:disable WordPress.Files.FileName

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$url = '/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/';
if ( ! empty( $txt_limt ) ) {
	$ltn = '';
	if ( 'char' === $text_type ) {
		$ltn       = strlen( $description );
		$firstdesc = mb_substr( $description, 0, $text_count );
		$totaldesc = mb_substr( $description, $text_count, $ltn );
	} elseif ( 'word' === $text_type ) {
		$words     = explode( ' ', $description );
		$ltn       = count( $words );
		$firstdesc = implode( ' ', array_splice( $words, 0, $text_count ) );
		$totaldesc = implode( ' ', array_splice( $words, 0 ) );
	}
	// First text.
		$mantion   = preg_replace( '/(^|\s)@([\w.]+)/', '$1<span class="tpgb-mantion" >@$2</span>', $firstdesc );
		$hash_tag  = preg_replace( '/#(\\S+)/', '<span class="tpgb-hashtag" >$0 </span>', $mantion );
		$firstdesc = preg_replace( $url, '<a href="$0" target="_blank" rel="noopener noreferrer" class="tpgb-feedurl" title="" aria-label="' . esc_attr__( 'Hashtag', 'the-plus-addons-for-block-editor' ) . '">$0</a>', $hash_tag );
	// Total text.
		$mantion   = preg_replace( '/(^|\s)@([\w.]+)/', '$1<span class="tpgb-mantion" >@$2</span>', $totaldesc );
		$hash_tag  = preg_replace( '/#(\\S+)/', '<span class="tpgb-hashtag" >$0 </span>', $mantion );
		$totaldesc = preg_replace( $url, '<a href="$0" target="_blank" rel="noopener noreferrer" class="tpgb-feedurl" title="" aria-label="' . esc_attr__( 'Hashtag', 'the-plus-addons-for-block-editor' ) . '">$0</a>', $hash_tag );
	?> 
		<div class="tpgb-message">
			<div class="showtext"><?php echo wp_kses_post( $firstdesc ); ?>
			<?php
			if ( ( 'char' === $text_type && ( $ltn > strlen( $firstdesc ) ) ) || ( 'word' === $text_type && ( $ltn > count( explode( ' ', $firstdesc ) ) ) ) ) {
				?>
				<span class="sf-dots"><?php echo wp_kses_post( $text_dots ); ?></span><div class="moreText" ><?php echo wp_kses_post( $totaldesc ); ?></div>
					<a class="readbtn" aria-label="<?php echo esc_attr( $text_more ); ?>"><?php echo wp_kses_post( $text_more ); ?> </a>
				<?php } ?>
			</div>
		</div>
	<?php
} else {
	$feedurl  = preg_replace( $url, '<a href="$0" target="_blank" rel="noopener noreferrer" class="tpgb-feedurl" title="" aria-label="' . esc_attr__( 'Description', 'the-plus-addons-for-block-editor' ) . '">$0</a>', $description );
	$hash_tag = preg_replace( '/#(\\S+)/', '<span class="tpgb-hashtag" >$0 </span>', $feedurl );
	$mantion  = preg_replace( '/(^|\s)@([\w.]+)/', '$1<span class="tpgb-mantion" >@$2</span>', $hash_tag );

	?>
	<div class="tpgb-message"><?php echo wp_kses_post( $mantion ); ?></div> <?php
}
?>
