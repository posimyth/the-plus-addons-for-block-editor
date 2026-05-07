<?php
/**
 * Fancybox Feed Post.
 *
 * @package ThePluginAddonsForBlockEditor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( 'Facebook' === $select_feed ) {
	if ( 'Alb' === $embed_url && ! empty( $fb_album ) ) {
		$ij         = 0;
		$album_size = ( is_array( $video_url ) ? count( $video_url ) : 1 );
		$uniq_id    = uniqid( 'f-' );
		if ( $album_size > 1 ) {
			foreach ( $video_url as $index => $fdata ) {
				$a_img = ( ! empty( $fdata['images'] ) ) ? $fdata['images'][0]['source'] : array();
				if ( 0 === $ij ) {
					echo '<a href="' . esc_url( $a_img ) . '" data-fancybox="' . esc_attr( $uniq_id ) . '" aria-label="' . esc_attr__( 'Facebook Post', 'the-plus-addons-for-block-editor' ) . '">
                                <img class="reference-thumb tpgb-post-thumb" src="' . esc_url( $image_url ) . '" alt="' . esc_attr__( 'Facebook Image', 'the-plus-addons-for-block-editor' ) . '"/>
                            </a>';
				} else {
					echo '<a href="' . esc_url( $a_img ) . '" data-fancybox="' . esc_attr( $uniq_id ) . '" aria-label="' . esc_attr__( 'Facebook Post', 'the-plus-addons-for-block-editor' ) . '">
                                <img class="hidden-image" src="' . esc_url( $a_img ) . '" alt="' . esc_attr__( 'Facebook Image', 'the-plus-addons-for-block-editor' ) . '"/>
                            </a>';
				}
				++$ij;
			}
		} else {
			echo '<img class="tpgb-post-thumb" src="' . esc_url( $image_url ) . '" alt="' . esc_attr__( 'Facebook Image', 'the-plus-addons-for-block-editor' ) . '"/>';
		}
	} elseif ( 'video' === $embed_type && empty( $fb_album ) ) {
		echo '<div class="tpgb-fcb-container">
                    <iframe class="responsive-iframe" src="' . esc_url( $video_url ) . '" title="' . esc_attr__( 'Social Feed', 'the-plus-addons-for-block-editor' ) . '"></iframe>
                </div>';
	} else {
		echo '<img class="tpgb-post-thumb" src="' . esc_url( $image_url ) . '" alt="' . esc_attr__( 'Facebook Image', 'the-plus-addons-for-block-editor' ) . '"/>';
	}
} elseif ( ! empty( $image_url ) ) {
	echo '<img class="tpgb-fcb-thumb" src="' . esc_url( $image_url ) . '" alt="' . esc_attr__( 'Social Media Image', 'the-plus-addons-for-block-editor' ) . '"/>';
}
