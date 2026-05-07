<?php
/**
 * Tp Block : Post Title.
 *
 * @package ThePluginAddonsForBlockEditor
 */

defined( 'ABSPATH' ) || exit;

/**
 * Tpgb tp post title render callback.
 *
 * @param mixed $attr The attr.
 * @param mixed $content The content.
 * @return mixed The result.
 */
function tpgb_tp_post_title_render_callback( $attr, $content ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
	$output  = '';
	$post_id = get_the_ID();
	$post    = get_queried_object();

	$block_id         = ( ! empty( $attr['block_id'] ) ) ? $attr['block_id'] : uniqid( 'title' );
	$types            = ( ! empty( $attr['types'] ) ) ? $attr['types'] : 'singular';
	$title_prefix     = ( ! empty( $attr['titlePrefix'] ) ) ? $attr['titlePrefix'] : '';
	$title_postfix    = ( ! empty( $attr['titlePostfix'] ) ) ? $attr['titlePostfix'] : false;
	$post_link        = ( ! empty( $attr['postLink'] ) ) ? $attr['postLink'] : false;
	$title_tag        = ( ! empty( $attr['titleTag'] ) ) ? $attr['titleTag'] : 'h1';
	$limit_count_type = ( ! empty( $attr['limitCountType'] ) ) ? $attr['limitCountType'] : '';
	$title_limit      = ( ! empty( $attr['titleLimit'] ) ) ? $attr['titleLimit'] : '';
	$hide_dots        = ( ! empty( $attr['hideDots'] ) ) ? $attr['hideDots'] : false;

	$block_class = Tp_Blocks_Helper::block_wrapper_classes( $attr );

	if ( 'archive' === $types ) {

		$is_archive = is_archive();
		if ( ! $is_archive && ! is_search() ) {
			return '';
		}

		$title = '';
		if ( $is_archive || is_search() ) {
			add_filter(
				'get_the_archive_title',
				function ( $title ) {
					if ( is_category() ) {
						$title = single_cat_title( '', false );
					} elseif ( is_tag() ) {
						$title = single_tag_title( '', false );
					} elseif ( is_author() ) {
						$title = '<span class="vcard">' . get_the_author() . '</span>';
					} elseif ( is_tax() ) {
						$title = single_term_title( '', false );
					} elseif ( is_post_type_archive() ) {
						$title = post_type_archive_title( '', false );
					} elseif ( is_search() ) {
						$title = get_search_query();
					}
					return $title;
				}
			);

			$title = get_the_archive_title();
		}

		if ( 'words' === $limit_count_type && ! empty( $title ) ) {
			$title = wp_trim_words( $title, (int) $title_limit );
		} elseif ( 'letters' === $limit_count_type && ! empty( $title ) ) {
			$title = substr( wp_trim_words( $title ), 0, (int) $title_limit ) . ( ! empty( $hide_dots ) ? '' : '...' );
		}
	} elseif ( 'singular' === $types ) {
		$title = get_the_title( $post_id );
		if ( 'words' === $limit_count_type && ! empty( $title ) ) {
			$title = wp_trim_words( $title, (int) $title_limit );
		} elseif ( 'letters' === $limit_count_type && ! empty( $title ) ) {
			$title = substr( wp_trim_words( $title ), 0, (int) $title_limit ) . ( ! empty( $hide_dots ) ? '' : '...' );
		}
	}

	$prefix_output = '';
	if ( ! empty( $title_prefix ) ) {
		$prefix_output = '<span class="tp-prepost-title tp-prefix-title">' . esc_html( $title_prefix ) . '</span>';
	}
	$postfix_output = '';
	if ( ! empty( $title_postfix ) ) {
		$postfix_output = '<span class="tp-prepost-title tp-postfix-title">' . esc_html( $title_postfix ) . '</span>';
	}

	$output .= '<div class="tpgb-post-title tpgb-block-' . esc_attr( $block_id ) . ' ' . esc_attr( $block_class ) . '" >';
	if ( ! empty( $post_link ) ) {
		$output .= '<a href="' . esc_url( get_the_permalink() ) . '" >';
	}
			$output     .= '<' . Tp_Blocks_Helper::validate_html_tag( $title_tag ) . ' class="tpgb-entry-title" >';
				$output .= $prefix_output . wp_kses_post( $title ) . $postfix_output;
			$output     .= '</' . Tp_Blocks_Helper::validate_html_tag( $title_tag ) . '>';
	if ( ! empty( $post_link ) ) {
		$output .= '</a>';
	}
	$output .= '</div>';

	$output = Tpgb_Blocks_Global_Options::block_Wrap_Render( $attr, $output );

	return $output;
}

/**
 * Render for the server-side
 */
function tpgb_post_title_content() {

	if ( method_exists( 'Tpgb_Blocks_Global_Options', 'merge_options_json' ) ) {
		$block_data = Tpgb_Blocks_Global_Options::merge_options_json( __DIR__, 'tpgb_tp_post_title_render_callback' );
		register_block_type( $block_data['name'], $block_data );
	}
}
add_action( 'init', 'tpgb_post_title_content' );
