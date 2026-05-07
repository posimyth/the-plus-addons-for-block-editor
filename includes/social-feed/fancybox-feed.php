<?php
/**
 * Fancybox Feed.
 *
 * @package ThePluginAddonsForBlockEditor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( 'style-1' === $fancy_style ) {
	include TPGB_INCLUDES_URL . 'social-feed/fancybox-feed-style-1.php';
} elseif ( 'style-2' === $fancy_style ) {
	include TPGB_INCLUDES_URL . 'social-feed/fancybox-feed-style-2.php';
} else {
	if ( empty( $fb_album ) ) {
		$popup_target = '';
		$popup_link   = '';
		if ( 'Donothing' === $popup_option ) {
			$video_url = '';
		} elseif ( 'GoWebsite' === $popup_option ) {
			$popup_target = 'target=_blank rel="noopener noreferrer"';
			$popup_link   = 'href="' . esc_url( $video_url ) . '"';
		} elseif ( 'OnFancyBox' === $popup_option ) {
			$popup_link = 'href="' . esc_url( $video_url ) . '"';
		}
	}

	if ( 'Facebook' === $select_feed && ! empty( $fb_album ) ) {
		$ij = 0;

		if ( ! empty( $video_url ) && is_array( $video_url ) ) {
			foreach ( $video_url as $fdata ) {
				$a_img = ( ! empty( $fdata['images'] ) && ! empty( $fdata['images'][0]['source'] ) ) ? $fdata['images'][0]['source'] : '';
				if ( 0 === $ij ) { ?>
						<a href="<?php echo esc_url( $a_img ); ?>" <?php echo $fancy_box_js; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Output is escaped inside $fancy_box_js. ?> aria-label="<?php echo esc_attr__( 'Facebook Post', 'the-plus-addons-for-block-editor' ); ?>">
							<?php
							if ( 'style-4' !== $style ) {
								?>
								<img class="reference-thumb tpgb-post-thumb" src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr__( 'Facebook Image', 'the-plus-addons-for-block-editor' ); ?>"/><?php } ?>
						</a>
					<?php } else { ?>
						<a href="<?php echo esc_url( $a_img ); ?>" <?php echo $fancy_box_js; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Output is escaped inside $fancy_box_js. ?> aria-label="<?php echo esc_attr__( 'Facebook Post', 'the-plus-addons-for-block-editor' ); ?>">
							<img class="hidden-image" src="<?php echo esc_url( $a_img ); ?>" alt="<?php echo esc_attr__( 'Facebook Image', 'the-plus-addons-for-block-editor' ); ?>"/>
						</a>
					<?php
					}
					++$ij;
			}
		}
	} elseif ( ( 'video' === $type || 'photo' === $type ) && ( ! empty( $image_url ) ) ) {
		if ( 'style-1' === $style || 'style-2' === $style ) {
			?>
							<a <?php echo $popup_link . $popup_target . $fancy_box_js; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Output is escaped inside $popup_link, $popup_target, and $fancy_box_js. ?> class="tpgb-soc-img-cls tpgb-relative-block" aria-label="<?php echo esc_attr__( 'Social Media Post', 'the-plus-addons-for-block-editor' ); ?>">
				<?php
				if ( 'GoWebsite' !== $popup_option ) {
					?>
					<img class="tpgb-post-thumb" src="<?php echo esc_url( $image_url ); ?>"  alt="<?php echo esc_attr__( 'Social Media Image', 'the-plus-addons-for-block-editor' ); ?>"/> <?php } ?>
				</a>
		<?php } elseif ( 'style-3' === $style || 'style-4' === $style ) {
				echo '<a ' . $popup_link . $popup_target . $fancy_box_js . ' class="tpgb-image-link" aria-label="' . esc_attr__( 'Social Media Post', 'the-plus-addons-for-block-editor' ) . '"></a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Output is escaped inside $popup_link, $popup_target, and $fancy_box_js.
		}
	}
}
?>
