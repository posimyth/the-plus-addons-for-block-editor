<?php
/**
 * Tp Block : Post Content.
 *
 * @package ThePluginAddonsForBlockEditor
 */

defined( 'ABSPATH' ) || exit;

/**
 * Common function to limit content by words or letters, with optional dots.
 *
 * @param mixed $content The content.
 * @param int   $limit_count_type The limit count type.
 * @param mixed $title_limit The title limit.
 * @param bool  $chide_dots The chide dots.
 */
function tpgb_limit_content( $content, $limit_count_type, $title_limit, $chide_dots = false ) {
	if ( 'words' === $limit_count_type && ! empty( $content ) ) {
		return wp_trim_words( $content, $title_limit );
	} elseif ( 'letters' === $limit_count_type && ! empty( $content ) ) {
		return substr( wp_trim_words( $content ), 0, $title_limit ) . ( ! empty( $chide_dots ) ? '' : '...' );
	} else {
		return $content;
	}
}

/**
 * Tpgb tp post content render callback.
 *
 * @param mixed $attr The attr.
 * @param mixed $content The content.
 * @param mixed $block The block.
 * @return mixed The result.
 */
function tpgb_tp_post_content_render_callback( $attr, $content, $block ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
	$output           = '';
	$post_id          = get_the_ID();
	$post             = get_queried_object();
	$block_id         = ( ! empty( $attr['block_id'] ) ) ? $attr['block_id'] : uniqid( 'title' );
	$types            = ( ! empty( $attr['types'] ) ) ? $attr['types'] : 'singular';
	$content_type     = ( ! empty( $attr['contentType'] ) ) ? $attr['contentType'] : '';
	$limit_count_type = ( ! empty( $attr['limitCountType'] ) ) ? $attr['limitCountType'] : '';
	$title_limit      = ( ! empty( $attr['titleLimit'] ) ) ? $attr['titleLimit'] : '';
	$chide_dots       = ( ! empty( $attr['chideDots'] ) ) ? $attr['chideDots'] : false;
	$archive_cnt      = ( ! empty( $attr['archiveCnt'] ) ) ? $attr['archiveCnt'] : '';
	$block_class      = Tp_Blocks_Helper::block_wrapper_classes( $attr );

	$content = '';
	if ( 'archive' === $types ) {

		if ( is_category() || is_tag() || is_tax() || is_archive() ) {
			if ( 'postExcerpt' === $archive_cnt ) {
				$excerpt = get_post_field( 'post_excerpt', $post_id, 'display' );
				if ( ! empty( $excerpt ) ) {
					$content = tpgb_limit_content( $excerpt, $limit_count_type, $title_limit, $chide_dots );
				} else {
					$content = get_the_excerpt();
					$content = tpgb_limit_content( $content, $limit_count_type, $title_limit, $chide_dots );
				}
			} else {
				$content = term_description();
			}
		}
	} elseif ( 'postContent' === $content_type ) {

			static $views_ids = array();
		if ( ! isset( $post_id ) ) {
			return '';
		}
		if ( isset( $views_ids[ $post_id ] ) ) {
			$is_debug = defined( 'WP_DEBUG' ) && WP_DEBUG &&
				defined( 'WP_DEBUG_DISPLAY' ) && WP_DEBUG_DISPLAY;

			return $is_debug ?
				esc_html__( 'Block Re-rendering halted', 'the-plus-addons-for-block-editor' ) :
				'';
		}

			$views_ids[ $post_id ] = true;

			global $current_screen;
		if ( isset( $current_screen ) && method_exists( $current_screen, 'is_block_editor' ) && $current_screen->is_block_editor() ) {
			$content = wp_strip_all_tags( get_the_content( '', true, $post ) );
		} else {
			$post = get_post( $post_id );
			if ( ! $post || 'nxt_builder' === $post->post_type ) {
				return '';
			}

			if ( ( 'publish' !== $post->post_status && 'draft' !== $post->post_status && 'private' !== $post->post_status ) || ! empty( $post->post_password ) ) {
				return '';
			}

			$content = apply_filters( 'the_content', $post->post_content );
		}
			unset( $views_ids[ $post_id ] );
	} else {
		$excerpt = get_post_field( 'post_excerpt', $post_id, 'display' );
		if ( ! empty( $excerpt ) ) {
			$content = tpgb_limit_content( $excerpt, $limit_count_type, $title_limit, $chide_dots );
		} else {
			$content = get_the_excerpt();
			$content = tpgb_limit_content( $content, $limit_count_type, $title_limit, $chide_dots );
		}
	}

	$output         .= '<div class="tpgb-post-content tpgb-block-' . esc_attr( $block_id ) . ' ' . esc_attr( $block_class ) . '" >';
		$output     .= '<div class="tpgb-entry-content tpgb-trans-linear">';
			$output .= $content;
		$output     .= '</div>';
	$output         .= '</div>';

	$output = Tpgb_Blocks_Global_Options::block_Wrap_Render( $attr, $output );

	return $output;
}

/**
 * Render for the server-side
 */
function tpgb_post_content() {

	if ( method_exists( 'Tpgb_Blocks_Global_Options', 'merge_options_json' ) ) {
		$block_data = Tpgb_Blocks_Global_Options::merge_options_json( __DIR__, 'tpgb_tp_post_content_render_callback' );
		register_block_type( $block_data['name'], $block_data );
	}
}
add_action( 'init', 'tpgb_post_content' );
