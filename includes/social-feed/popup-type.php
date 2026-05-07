<?php
/**
 * Popup Type.
 *
 * @package ThePluginAddonsForBlockEditor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

	$popup_link_tar = '';
if ( 'GoWebsite' === $popup_option ) {
	$popup_link_tar .= 'href="' . esc_url( $video_url ) . '"';
	$popup_link_tar .= ' target=_blank" rel="noopener noreferrer"';

}

if ( ! empty( $image_url ) ) {
	if ( 'style-1' === $style || 'style-2' === $style ) {
		if ( 'Donothing' === $popup_option || 'GoWebsite' === $popup_option ) {
			echo '<a ' . $popup_link_tar . ' class="tpgb-soc-img-cls tpgb-relative-block" aria-label="' . esc_attr__( 'Post URL', 'the-plus-addons-for-block-editor' ) . '">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Output is escaped inside $popup_link_tar.
			if ( preg_match_all( '/https:\/\/video.(.*)/', $image_url, $matches ) ) {
				if ( ! empty( $matches ) && ! empty( $matches[0] ) ) {
					echo '<video class="tpgb-post-thumb tpgb-feed-video">';
						echo '<source src="' . esc_url( $image_url ) . '" type="video/mp4">';
					echo '</video>';
				} else {
					echo '<img class="tpgb-post-thumb" src="' . esc_url( $image_url ) . '" alt="' . esc_attr__( 'Post Thumbnail', 'the-plus-addons-for-block-editor' ) . '"/>';
				}
			} else {
				echo '<img class="tpgb-post-thumb" src="' . esc_url( $image_url ) . '" alt="' . esc_attr__( 'Post Thumbnail', 'the-plus-addons-for-block-editor' ) . '"/>';
			}

				echo '</a>';
		} elseif ( 'OnFancyBox' === $popup_option ) {
			echo '<a href="javascript:;" ' . $fancy_box_js . ' class="tpgb-soc-img-cls tpgb-relative-block" data-src="#Fancy-' . esc_attr( $popup_syl_num ) . '" aria-label="' . esc_attr__( 'Popup Gallery', 'the-plus-addons-for-block-editor' ) . '">'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Output is escaped inside $fancy_box_js.
			if ( preg_match_all( '/https:\/\/video.(.*)/', $image_url, $matches ) ) {
				if ( ! empty( $matches ) && ! empty( $matches[0] ) ) {
					echo '<video class="tpgb-post-thumb tpgb-feed-video">';
						echo '<source src="' . esc_url( $image_url ) . '" type="video/mp4">';
					echo '</video>';
				} else {
					echo '<img class="tpgb-post-thumb" src="' . esc_url( $image_url ) . '" alt="' . esc_attr__( 'Post Thumbnail', 'the-plus-addons-for-block-editor' ) . '"/>';
				}
			} else {
				echo '<img class="tpgb-post-thumb" src="' . esc_url( $image_url ) . '" alt="' . esc_attr__( 'Post Thumbnail', 'the-plus-addons-for-block-editor' ) . '"/>';
			}
				echo '</a>';
		}
	} elseif ( 'style-3' === $style || 'style-4' === $style ) {
		if ( 'Donothing' === $popup_option || 'GoWebsite' === $popup_option ) {
			echo '<a ' . $popup_link_tar . ' class="tpgb-image-link tpgb-soc-img-cls tpgb-relative-block" ' . $fancy_box_js . ' aria-label="' . esc_attr__( 'Post URL', 'the-plus-addons-for-block-editor' ) . '"></a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Output is escaped inside $fancy_box_js , $popup_link_tar.
		} elseif ( 'OnFancyBox' === $popup_option ) {
			echo '<a href="javascript:;" class="tpgb-image-link tpgb-soc-img-cls tpgb-relative-block" ' . $fancy_box_js . ' data-src="#Fancy-' . esc_attr( $popup_syl_num ) . '" aria-label="' . esc_attr__( 'Popup Gallery', 'the-plus-addons-for-block-editor' ) . '"></a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Output is escaped inside $fancy_box_js.
		}
	}
}
