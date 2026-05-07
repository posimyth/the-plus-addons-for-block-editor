<?php
/**
 * Tp Block : Post Meta.
 *
 * @package ThePluginAddonsForBlockEditor
 */

// phpcs:disable Squiz.PHP.CommentedOutCode.Found
defined( 'ABSPATH' ) || exit;

/**
 * Tpgb tp post meta render callback.
 *
 * @param mixed $attr The attr.
 * @param mixed $content The content.
 * @return mixed The result.
 */
function tpgb_tp_post_meta_render_callback( $attr, $content ) {
	$output  = '';
	$post_id = '';

	if ( is_archive() ) {
		$post_id = get_queried_object_id();
	} else {
		$post_id = get_the_ID();
	}

	$block_id      = ( ! empty( $attr['block_id'] ) ) ? $attr['block_id'] : uniqid( 'title' );
	$show_date     = ( ! empty( $attr['showDate'] ) ) ? $attr['showDate'] : false;
	$show_category = ( ! empty( $attr['showCategory'] ) ) ? $attr['showCategory'] : false;
	$show_author   = ( ! empty( $attr['showAuthor'] ) ) ? $attr['showAuthor'] : false;
	$show_comment  = ( ! empty( $attr['showComment'] ) ) ? $attr['showComment'] : false;
	$meta_sort     = ( ! empty( $attr['metaSort'] ) ) ? (array) $attr['metaSort'] : '';
	$meta_layout   = ( ! empty( $attr['metaLayout'] ) ) ? $attr['metaLayout'] : '';
	$taxonomy_slug = ( ! empty( $attr['taxonomySlug'] ) ) ? $attr['taxonomySlug'] : 'category';
	$metafield_rep = ( ! empty( $attr['metafieldRep'] ) ) ? $attr['metafieldRep'] : array();
	$read_prefix   = ( ! empty( $attr['readPrefix'] ) ) ? $attr['readPrefix'] : '';
	$showread_time = ( ! empty( $attr['showreadTime'] ) ) ? $attr['showreadTime'] : false;
	$date_type     = ( ! empty( $attr['dateType'] ) ) ? $attr['dateType'] : '';
	$block_class   = Tp_Blocks_Helper::block_wrapper_classes( $attr );

	$output_date = '';
	if ( $show_date ) {
		$date_prefix = ( ! empty( $attr['datePrefix'] ) ) ? '<span class="tpgb-meta-date-label">' . wp_kses_post( $attr['datePrefix'] ) . '</span>' : '';
		$date_icon   = ( ! empty( $attr['dateIcon'] ) ) ? '<i class="meta-date-icon ' . esc_attr( $attr['dateIcon'] ) . '"></i>' : '';

		if ( 'modified' === $date_type ) {
			$output_date .= '<span class="tpgb-meta-date">' . $date_prefix . '<a href="' . esc_url( get_the_permalink() ) . '">' . $date_icon . esc_html( get_the_modified_date() ) . '</a></span>';
		} else {
			$output_date .= '<span class="tpgb-meta-date">' . $date_prefix . '<a href="' . esc_url( get_the_permalink() ) . '">' . $date_icon . esc_html( get_the_date() ) . '</a></span>';
		}
	}

	$output_category = '';
	if ( $show_category ) {  // && !empty(get_the_category($post_id)) // phpcs:ignore Squiz.PHP.CommentedOutCode.Found
		$cate_prefix     = ( ! empty( $attr['catePrefix'] ) ) ? '<span class="tpgb-meta-category-label">' . wp_kses_post( $attr['catePrefix'] ) . '</span>' : '';
		$cate_display_no = ( ! empty( $attr['cateDisplayNo'] ) ) ? $attr['cateDisplayNo'] : 0;
		$cate_style      = ( ! empty( $attr['cateStyle'] ) ) ? $attr['cateStyle'] : 'style-1';

		$terms = get_the_terms( $post_id, $taxonomy_slug, array( 'hide_empty' => true ) );
		if ( is_archive() && empty( $terms ) ) {
			$post_id = get_the_ID();
			$terms   = get_the_terms( $post_id, $taxonomy_slug, array( 'hide_empty' => true ) );
		}

		$category_list = '';
		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			$i              = 1;
			$category_list .= '<span class="tpgb-meta-category-list">';
			foreach ( $terms as $term ) {
				if ( $cate_display_no >= $i ) {
					$category_list .= '<a href="' . esc_url( get_term_link( $term ) ) . '" alt="' . esc_attr( $term->name ) . '">' . esc_html( $term->name ) . '</a>';
				}
				++$i;
			}
			$category_list .= '</span>';
		}
		$output_category .= '<span class="tpgb-meta-category ' . esc_attr( $cate_style ) . '" >' . $cate_prefix . $category_list . '</span>';
	}

	$output_author = '';
	if ( $show_author ) {
		global $post;
		$author_id     = ( ! empty( $post ) && isset( $post->post_author ) ) ? $post->post_author : '';
		$author_prefix = ( ! empty( $attr['authorPrefix'] ) ) ? '<span class="tpgb-meta-author-label">' . wp_kses_post( $attr['authorPrefix'] ) . '</span>' : '';
		$author_icon   = ( ! empty( $attr['authorIcon'] ) ) ? $attr['authorIcon'] : '';
		$iconauthor    = '';
		if ( ! empty( $author_icon ) && 'profile' === $author_icon ) {
			$iconauthor = '<span>' . get_avatar( get_the_author_meta( 'ID' ), 200 ) . '</span>';
		} elseif ( ! empty( $author_icon ) ) {
			$iconauthor = '<i class="meta-author-icon ' . esc_attr( $author_icon ) . '"></i>';
		}
		$output_author .= '<span class="tpgb-meta-author" >' . $author_prefix . '<a href="' . esc_url( get_author_posts_url( $author_id ) ) . '" rel="' . esc_attr__( 'author', 'the-plus-addons-for-block-editor' ) . '">' . $iconauthor . '' . get_the_author_meta( 'display_name', $author_id ) . '</a></span>';
	}

	$output_comment = '';
	if ( $show_comment ) {
		$comment_icon   = ( ! empty( $attr['commentIcon'] ) ) ? '<i class="meta-comment-icon ' . esc_attr( $attr['commentIcon'] ) . '"></i>' : '';
		$comments_count = wp_count_comments( $post_id );
		$count          = 0;
		if ( ! empty( $comments_count ) ) {
			$count = $comments_count->total_comments;
		}
		if ( 0 === $count ) {
			$comment_text = esc_html__( 'No Comments', 'the-plus-addons-for-block-editor' );
		} elseif ( $count > 0 ) {
			$comment_text = 'Comments(' . $count . ')';
		}
		$comment_prefix  = ( ! empty( $attr['commentPrefix'] ) ) ? '<span class="tpgb-meta-comment-label">' . wp_kses_post( $attr['commentPrefix'] ) . '</span>' : '';
		$output_comment .= '<span class="tpgb-meta-comment" >' . $comment_prefix . '<a href="' . esc_url( get_the_permalink() ) . '#respond" rel="' . esc_attr__( 'comment', 'the-plus-addons-for-block-editor' ) . '">' . $comment_icon . $comment_text . '</a></span>';
	}

	$meta_extra = '';
	// Extra Field.
	if ( ! empty( $metafield_rep ) ) {
		foreach ( $metafield_rep as $item ) {
			if ( isset( $item['metaDynamic'] ) && ! empty( $item['metaDynamic'] ) ) {
				$meta_extra .= '<span class="tpgb-meta-extra" >';
				if ( isset( $item['metaLabel'] ) && ! empty( $item['metaLabel'] ) ) {
					$meta_extra .= '<span class="tpgb-meta-extra-label">' . wp_kses_post( $item['metaLabel'] ) . '</span>';
				}

					$meta_extra .= '<span class="tpgb-meta-value">' . wp_kses_post( $item['metaDynamic'] ) . '</span>';

				if ( isset( $item['metapostfix'] ) && ! empty( $item['metapostfix'] ) ) {
					$meta_extra .= '<span class="tpgb-meta-epostfix">' . wp_kses_post( $item['metapostfix'] ) . '</span>';
				}
					$meta_extra .= '';
				$meta_extra     .= '</span>';
			}
		}
	}

	$post_read = '';
	if ( $showread_time ) {
		$content              = get_the_content();
		$average_reading_rate = 189;
		$word_count_type      = tpgb_get_word_count_type();
		$minutes_to_read      = max( 1, (int) round( tpgb_word_count( $content, $word_count_type ) / $average_reading_rate ) );

		$minutes_to_read_string = sprintf(
			/* translators: %s: the number of minutes to read the post */
			_n( '%s minute', '%s minutes', $minutes_to_read, 'the-plus-addons-for-block-editor' ),
			$minutes_to_read
		);

		$post_read .= '<span class="tpgb-meta-read" >';
		if ( ! empty( $read_prefix ) ) {
			$post_read     .= '<span class="tpgb-meta-read-label">';
				$post_read .= $read_prefix;
			$post_read     .= '</span>';
		}
			$post_read .= $minutes_to_read_string;
		$post_read     .= '</span>';
	}

	$output     .= '<div class="tpgb-post-meta tpgb-block-' . esc_attr( $block_id ) . ' ' . esc_attr( $block_class ) . '" >';
		$output .= '<div class="tpgb-meta-info ' . esc_attr( $meta_layout ) . '">';
	foreach ( $meta_sort['sort'] as $item => $value ) {
		if ( 'Date' === $value ) {
			$output .= $output_date;  }
		if ( 'Category' === $value ) {
			$output .= $output_category;  }
		if ( 'Author' === $value ) {
			$output .= $output_author;  }
		if ( 'Comments' === $value ) {
			$output .= $output_comment;  }
		if ( 'Post Reading Time' === $value ) {
			$output .= $post_read;  }
	}
		$output .= $meta_extra;
		$output .= '</div>';
	$output     .= '</div>';

	$output = Tpgb_Blocks_Global_Options::block_Wrap_Render( $attr, $output );

	return $output;
}

/**
 * Render for the server-side
 */
function tpgb_post_meta_content() {

	if ( method_exists( 'Tpgb_Blocks_Global_Options', 'merge_options_json' ) ) {
		$block_data = Tpgb_Blocks_Global_Options::merge_options_json( __DIR__, 'tpgb_tp_post_meta_render_callback' );
		register_block_type( $block_data['name'], $block_data );
	}
}
add_action( 'init', 'tpgb_post_meta_content' );

if ( ! function_exists( 'tpgb_get_word_count_type' ) ) {
	/**
	 * Tpgb get word count type.
	 *
	 * @return mixed The result.
	 */
	function tpgb_get_word_count_type() {
		$word_count_type = _x( 'words', 'Word count type. Do not translate!', 'the-plus-addons-for-block-editor' );

		if ( 'characters_excluding_spaces' !== $word_count_type && 'characters_including_spaces' !== $word_count_type ) {
			$word_count_type = 'words';
		}
		return $word_count_type;
	}
}

if ( ! function_exists( 'tpgb_word_count' ) ) {
	/**
	 * Tpgb word count.
	 *
	 * @param mixed $text The text.
	 * @param mixed $type The type.
	 * @param array $settings The settings.
	 * @return mixed The result.
	 */
	function tpgb_word_count( $text, $type, $settings = array() ) {
		$defaults = array(
			'html_regexp'                        => '/<\/?[a-z][^>]*?>/i',
			'html_comment_regexp'                => '/<!--[\s\S]*?-->/',
			'space_regexp'                       => '/&nbsp;|&#160;/i',
			'html_entity_regexp'                 => '/&\S+?;/',
			'connector_regexp'                   => "/--|\x{2014}/u",
			'remove_regexp'                      => "/[\x{0021}-\x{0040}\x{005B}-\x{0060}\x{007B}-\x{007E}\x{0080}-\x{00BF}\x{00D7}\x{00F7}\x{2000}-\x{2BFF}\x{2E00}-\x{2E7F}]/u",
			'astral_regexp'                      => "/[\x{010000}-\x{10FFFF}]/u",
			'words_regexp'                       => '/\S\s+/u',
			'characters_excluding_spaces_regexp' => '/\S/u',
			'characters_including_spaces_regexp' => "/[^\f\n\r\t\v\x{00AD}\x{2028}\x{2029}]/u",
			'shortcodes'                         => array(),
		);

		$count = 0;
		if ( ! $text ) {
			return $count;
		}

		$settings = wp_parse_args( $settings, $defaults );

		// If there are any shortcodes, add this as a shortcode regular expression.
		if ( is_array( $settings['shortcodes'] ) && ! empty( $settings['shortcodes'] ) ) {
			$settings['shortcodes_regexp'] = '/\\[\\/?(?:' . implode( '|', $settings['shortcodes'] ) . ')[^\\]]*?\\]/';
		}

		// Sanitize type to one of three possibilities: 'words', 'characters_excluding_spaces' or 'characters_including_spaces'.
		if ( 'characters_excluding_spaces' !== $type && 'characters_including_spaces' !== $type ) {
			$type = 'words';
		}

		$text .= "\n";

		// Replace all HTML with a new-line.
		$text = preg_replace( $settings['html_regexp'], "\n", $text );

		// Remove all HTML comments.
		$text = preg_replace( $settings['html_comment_regexp'], '', $text );

		// If a shortcode regular expression has been provided use it to remove shortcodes.
		if ( ! empty( $settings['shortcodes_regexp'] ) ) {
			$text = preg_replace( $settings['shortcodes_regexp'], "\n", $text );
		}

		// Normalize non-breaking space to a normal space.
		$text = preg_replace( $settings['space_regexp'], ' ', $text );

		if ( 'words' === $type ) {
			// Remove HTML Entities.
			$text = preg_replace( $settings['html_entity_regexp'], '', $text );

			// Convert connectors to spaces to count attached text as words.
			$text = preg_replace( $settings['connector_regexp'], ' ', $text );

			// Remove unwanted characters.
			$text = preg_replace( $settings['remove_regexp'], '', $text );
		} else {
			// Convert HTML Entities to "a".
			$text = preg_replace( $settings['html_entity_regexp'], 'a', $text );

			// Remove surrogate points.
			$text = preg_replace( $settings['astral_regexp'], 'a', $text );
		}

		// Match with the selected type regular expression to count the items.
		preg_match_all( $settings[ $type . '_regexp' ], $text, $matches );

		if ( $matches ) {
			return count( $matches[0] );
		}

		return $count;
	}
}
