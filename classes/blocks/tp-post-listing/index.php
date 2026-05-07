<?php
/**
 * Tp Block : Post Listing.
 *
 * @package ThePluginAddonsForBlockEditor
 */

defined( 'ABSPATH' ) || exit;

/**
 * Tpgbp metro class.
 *
 * @param mixed $col The col.
 * @param mixed $metro_col The metro col.
 * @param mixed $metrosty The metrosty.
 * @return mixed The result.
 */
function tpgbp_metro_class( $col = '1', $metro_col = '3', $metrosty = 'style-1' ) {
	return ( ! empty( $metro_col ) && '3' === $metro_col && 'style-1' === $metrosty && $col > 10 ) ? ( $col % 10 ) : $col;
}

/**
 * Tpgb tp post listing render callback.
 *
 * @param mixed $attributes The attributes.
 */
function tpgb_tp_post_listing_render_callback( $attributes ) {
	$output     = '';
	$query_args = tpgb_post_query( $attributes );
	$query      = new \WP_Query( $query_args );

	$block_id         = isset( $attributes['block_id'] ) ? $attributes['block_id'] : '';
	$post_type        = isset( $attributes['postType'] ) ? $attributes['postType'] : '';
	$style            = isset( $attributes['style'] ) ? $attributes['style'] : 'style-1';
	$layout           = isset( $attributes['layout'] ) ? $attributes['layout'] : 'grid';
	$style2_alignment = isset( $attributes['style2Alignment'] ) ? $attributes['style2Alignment'] : 'center';
	$style_layout     = isset( $attributes['styleLayout'] ) ? $attributes['styleLayout'] : 'style-1';

	$image_hover_style = isset( $attributes['imageHoverStyle'] ) ? 'hover-image-' . esc_attr( $attributes['imageHoverStyle'] ) : 'hover-image-style-1';
	// Title.
	$show_title     = ! empty( $attributes['ShowTitle'] ) ? 'yes' : '';
	$title_tag      = isset( $attributes['titleTag'] ) ? $attributes['titleTag'] : 'h3';
	$title_by_limit = isset( $attributes['titleByLimit'] ) ? $attributes['titleByLimit'] : 'default';

	// Excerpt.
	$show_excerpt     = ! empty( $attributes['ShowExcerpt'] ) ? 'yes' : '';
	$excerpt_by_limit = isset( $attributes['excerptByLimit'] ) ? $attributes['excerptByLimit'] : 'default';
	$excerpt_limit    = isset( $attributes['excerptLimit'] ) ? $attributes['excerptLimit'] : 30;

	$show_post_meta  = ! empty( $attributes['ShowPostMeta'] ) ? 'yes' : '';
	$post_meta_style = isset( $attributes['postMetaStyle'] ) ? $attributes['postMetaStyle'] : 'style-1';
	$show_date       = ! empty( $attributes['ShowDate'] ) ? 'yes' : '';
	$show_author     = ! empty( $attributes['ShowAuthor'] ) ? 'yes' : '';
	$show_author_img = ! empty( $attributes['ShowAuthorImg'] ) ? 'yes' : '';
	$taxonomy_slug   = ! empty( $attributes['taxonomySlug'] ) ? $attributes['taxonomySlug'] : 'category';

	$post_listing = isset( $attributes['postListing'] ) ? $attributes['postListing'] : '';

	$show_post_category  = ! empty( $attributes['showPostCategory'] ) ? 'yes' : '';
	$post_category_style = isset( $attributes['postCategoryStyle'] ) ? $attributes['postCategoryStyle'] : 'style-1';
	$post_category       = isset( $attributes['postCategory'] ) ? $attributes['postCategory'] : '';
	$post_tag            = isset( $attributes['postTag'] ) ? $attributes['postTag'] : '';
	$exclude_category    = isset( $attributes['excludeCategory'] ) ? $attributes['excludeCategory'] : '';
	$exclude_tag         = isset( $attributes['excludeTag'] ) ? $attributes['excludeTag'] : '';

	$display_posts = isset( $attributes['displayPosts'] ) ? $attributes['displayPosts'] : 6;
	$offset_posts  = isset( $attributes['offsetPosts'] ) ? $attributes['offsetPosts'] : 0;
	$order_by      = isset( $attributes['orderBy'] ) ? $attributes['orderBy'] : 'date';
	$order         = isset( $attributes['order'] ) ? $attributes['order'] : 'desc';
	$post_lodop    = isset( $attributes['postLodop'] ) ? $attributes['postLodop'] : '';
	$author_txt    = ! empty( $attributes['authorTxt'] ) ? $attributes['authorTxt'] : '';
	$metrocolumns  = isset( $attributes['metrocolumns'] ) ? $attributes['metrocolumns'] : array( 'md' => '3' );
	$metro_style   = isset( $attributes['metroStyle'] ) ? $attributes['metroStyle'] : '';

	$block_class     = Tp_Blocks_Helper::block_wrapper_classes( $attributes );
	$show_button     = ! empty( $attributes['ShowButton'] ) ? 'yes' : '';
	$post_btnsty     = isset( $attributes['postBtnsty'] ) ? $attributes['postBtnsty'] : 'style-7';
	$postbtntext     = isset( $attributes['postbtntext'] ) ? $attributes['postbtntext'] : '';
	$pobtn_icon_type = isset( $attributes['pobtnIconType'] ) ? $attributes['pobtnIconType'] : '';
	$pobtn_icon_name = isset( $attributes['pobtnIconName'] ) ? $attributes['pobtnIconName'] : '';
	$btn_icon_posi   = isset( $attributes['btnIconPosi'] ) ? $attributes['btnIconPosi'] : '';

	// Columns.
	$column_class = '';
	if ( 'carousel' !== $layout && ! empty( $attributes['columns'] ) && is_array( $attributes['columns'] ) ) {
		$column_class .= isset( $attributes['columns']['md'] ) ? ' tpgb-col-lg-' . $attributes['columns']['md'] : ' tpgb-col-lg-3';
		$column_class .= isset( $attributes['columns']['sm'] ) ? ' tpgb-col-md-' . $attributes['columns']['sm'] : ' tpgb-col-md-4';
		$column_class .= isset( $attributes['columns']['xs'] ) ? ' tpgb-col-sm-' . $attributes['columns']['xs'] : ' tpgb-col-sm-6';
		$column_class .= isset( $attributes['columns']['xs'] ) ? ' tpgb-col-' . $attributes['columns']['xs'] : ' tpgb-col-6';
	}

	// Classes.
	$list_style = ( $style ) ? 'dynamic-' . esc_attr( $style ) : 'dynamic-style-1';

	$list_layout = '';
	if ( 'grid' === $layout || 'masonry' === $layout ) {
		$list_layout = 'tpgb-isotope';
	} elseif ( 'metro' === $layout ) {
		$list_layout = 'tpgb-metro';
	} else {
		$list_layout = 'tpgb-isotope';
	}

	$style_layoutclass = '';
	if ( ( 'style-2' === $style ) && $style_layout ) {
		$style_layoutclass .= 'layout-' . $style_layout;
	}

	$classattr  = '';
	$classattr .= ' tpgb-block-' . $block_id;
	$classattr .= ' ' . $list_style;
	$classattr .= ' ' . $list_layout;
	$classattr .= ' ' . $style_layoutclass;

	// Equal Height.
	$equal_height_attr = Tp_Blocks_Helper::global_equal_height( $attributes );

	if ( ! empty( $equal_height_attr ) ) {
		$classattr .= ' tpgb-equal-height';
	}

	if ( 'metro' === $layout ) {
		// Desktop columns.
		if ( isset( $metrocolumns['md'] ) && ! empty( $metrocolumns['md'] ) ) {
			$metro_attr['metro_col'] = (int) $metrocolumns['md'];
		}

		// Tablet columns.
		if ( isset( $metrocolumns['sm'] ) && ! empty( $metrocolumns['sm'] ) ) {
			$metro_attr['tab_metro_col'] = (int) $metrocolumns['sm'];
		} elseif ( isset( $metrocolumns['md'] ) && ! empty( $metrocolumns['md'] ) ) {
			$metro_attr['tab_metro_col'] = (int) $metrocolumns['md'];
		}

		// Mobile columns.
		if ( isset( $metrocolumns['xs'] ) && ! empty( $metrocolumns['xs'] ) ) {
			$metro_attr['mobile_metro_col'] = (int) $metrocolumns['xs'];
		} elseif ( isset( $metrocolumns['sm'] ) && ! empty( $metrocolumns['sm'] ) ) {
			$metro_attr['mobile_metro_col'] = (int) $metrocolumns['sm'];
		} elseif ( isset( $metrocolumns['md'] ) && ! empty( $metrocolumns['md'] ) ) {
			$metro_attr['mobile_metro_col'] = (int) $metrocolumns['md'];
		}

		// Desktop style.
		if ( isset( $metro_style['md'] ) && ! empty( $metro_style['md'] ) ) {
			$metro_attr['metro_style'] = (string) $metro_style['md'];
		}

		// Tablet style.
		if ( isset( $metro_style['sm'] ) && ! empty( $metro_style['sm'] ) ) {
			$metro_attr['tab_metro_style'] = (string) $metro_style['sm'];
		} elseif ( isset( $metro_style['md'] ) && ! empty( $metro_style['md'] ) ) {
			$metro_attr['tab_metro_style'] = (string) $metro_style['md'];
		}

		// Mobile style.
		if ( isset( $metro_style['xs'] ) && ! empty( $metro_style['xs'] ) ) {
			$metro_attr['mobile_metro_style'] = (string) $metro_style['xs'];
		} elseif ( isset( $metro_style['sm'] ) && ! empty( $metro_style['sm'] ) ) {
			$metro_attr['mobile_metro_style'] = (string) $metro_style['sm'];
		} elseif ( isset( $metro_style['md'] ) && ! empty( $metro_style['md'] ) ) {
			$metro_attr['mobile_metro_style'] = (string) $metro_style['md'];
		}

		// Properly encode the JSON and create the data attribute.
		$metro_attr_json = htmlspecialchars( wp_json_encode( $metro_attr ), ENT_QUOTES, 'UTF-8' );
		$metro_data_attr = 'data-metroAttr="' . $metro_attr_json . '"';
	} else {
		$metro_data_attr = '';
	}

	if ( '' !== $query->found_posts ) {
		$total_posts   = $query->found_posts;
		$post_offset   = ( isset( $offset_posts ) ) ? $offset_posts : 0;
		$display_posts = ( isset( $display_posts ) ) ? $display_posts : 0;
		$offset_posts  = intval( (int) $display_posts + (int) $post_offset );
		$total_posts   = intval( $total_posts - $offset_posts );

		$load_page = 1;

		$load_page = $load_page + 1; // phpcs:ignore Squiz.Operators.IncrementDecrementUsage.Found
	} else {
		$load_page = 1;
	}
	$ji      = 1;
	$col     = '';
	$tab_col = '';
	$mo_col  = '';
	if ( ! $query->have_posts() ) {
		$output .= '<h3 class="tpgb-no-posts-found">' . esc_html__( 'No Posts found', 'the-plus-addons-for-block-editor' ) . '</h3>';
	} else {
		$output .= '<div id="' . esc_attr( $block_id ) . '" class="tpgb-post-listing tpgb-relative-block  ' . esc_attr( $block_class ) . ' ' . esc_attr( $classattr ) . ' " data-id="' . esc_attr( $block_id ) . '" data-style="' . esc_attr( $list_style ) . '" ' . ( 'metro' === $layout ? $metro_data_attr : '' ) . '  data-layout="' . esc_attr( $layout ) . '"  data-connection="tpgb_search"  ' . $equal_height_attr . ' >';

			$output .= '<div class="tpgb-row post-loop-inner" >';
		while ( $query->have_posts() ) {

			$query->the_post();
			$post = $query->post;

			if ( 'metro' === $layout ) {
				if ( ( isset( $metrocolumns['md'] ) && ! empty( $metrocolumns['md'] ) ) && ( isset( $metro_style['md'] ) && ! empty( $metro_style['md'] ) ) ) {
					$col = tpgbp_metro_class( $ji, $metrocolumns['md'], $metro_style['md'] );
				}
				if ( ( isset( $metrocolumns['sm'] ) && ! empty( $metrocolumns['sm'] ) ) && ( isset( $metro_style['sm'] ) && ! empty( $metro_style['sm'] ) ) ) {
					$tab_col = tpgbp_metro_class( $ji, $metrocolumns['sm'], $metro_style['sm'] );
				}
				if ( ( isset( $metrocolumns['xs'] ) && ! empty( $metrocolumns['xs'] ) ) && ( isset( $metro_style['xs'] ) && ! empty( $metro_style['xs'] ) ) ) {
					$mo_col = tpgbp_metro_class( $ji, $metrocolumns['xs'], $metro_style['xs'] );
				}
			}

			$output .= '<div class="grid-item tpgb-col ' . esc_attr( $column_class ) . ( 'metro' === $layout ? ' tpgb-metro-' . esc_attr( $col ) . ' ' . ( ! empty( $tab_col ) ? ' tpgb-tab-metro-' . esc_attr( $tab_col ) . '' : '' ) . ' ' . ( ! empty( $mo_col ) ? ' tpgb-mobile-metro-' . esc_attr( $mo_col ) . '' : '' ) . ' ' : '' ) . '">';
			if ( ! empty( $style ) && 'custom' !== $style ) {
				ob_start();
				if ( file_exists( TPGB_PATH . 'includes/blog/' . sanitize_file_name( 'blog-' . $style . '.php' ) ) ) {
					include TPGB_PATH . 'includes/blog/' . sanitize_file_name( 'blog-' . $style . '.php' );
				}
				$output .= ob_get_contents();
				ob_end_clean();
			} elseif ( 'custom' === $style && '' !== $attributes['blockTemplate'] ) {
				ob_start();
					// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Output is escaped inside plus_do_block().
					echo Tpgb_Library()->plus_do_block( $attributes['blockTemplate'] );
				$output .= ob_get_contents();
				ob_end_clean();
			}
			$output .= '</div>';
			++$ji;
		}
			$output .= '</div>';

		if ( 'pagination' === $post_lodop && 'carousel' !== $layout ) {
			$output .= tpgb_pagination( $query->max_num_pages, '2' );
		}
		$output .= '</div>';
	}

	$output = Tpgb_Blocks_Global_Options::block_Wrap_Render( $attributes, $output );
	wp_reset_postdata();
	return $output;
}

/**
 * Render for the server-side
 */
function tpgb_tp_post_listing() {

	if ( method_exists( 'Tpgb_Blocks_Global_Options', 'merge_options_json' ) ) {
		$block_data = Tpgb_Blocks_Global_Options::merge_options_json( __DIR__, 'tpgb_tp_post_listing_render_callback', true, false, true, true );
		register_block_type( $block_data['name'], $block_data );
	}
}
add_action( 'init', 'tpgb_tp_post_listing' );

/**
 * Tpgb post query.
 *
 * @param mixed $attr The attr.
 * @return mixed The result.
 */
function tpgb_post_query( $attr ) {

	$include_posts = ( $attr['includePosts'] ) ? explode( ',', $attr['includePosts'] ) : '';
	$exclude_posts = ( $attr['excludePosts'] ) ? explode( ',', $attr['excludePosts'] ) : '';

	$query_args = array(
		'post_type'           => $attr['postType'],
		'post_status'         => 'publish',
		'ignore_sticky_posts' => true,
		'posts_per_page'      => ( $attr['displayPosts'] ) ? intval( $attr['displayPosts'] ) : -1,
		'orderby'             => ( $attr['orderBy'] ) ? $attr['orderBy'] : 'date',
		'order'               => ( $attr['order'] ) ? $attr['order'] : 'desc',
		'post__not_in'        => $exclude_posts,
		'post__in'            => $include_posts,
	);

	global $paged;
	if ( get_query_var( 'paged' ) ) {
		$paged = get_query_var( 'paged' ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
	} elseif ( get_query_var( 'page' ) ) {
		$paged = get_query_var( 'page' ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
	} else {
		$paged = 1; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
	}
	$query_args['paged'] = $paged;

	$offset = ! empty( $attr['offsetPosts'] ) ? absint( $attr['offsetPosts'] ) : 0;
	if ( $offset && 'pagination' !== $attr['postLodop'] ) {
		$query_args['offset'] = $offset;
	} elseif ( $offset && 'pagination' === $attr['postLodop'] ) {
		$page                 = max( 1, $paged );
		$offset               = ( $page - 1 ) * intval( $attr['displayPosts'] ) + $offset;
		$query_args['offset'] = $offset;
	}

	if ( '' !== $attr['postCategory'] ) {
		$cat_arr = array();
		if ( is_string( $attr['postCategory'] ) ) {
			$attr['postCategory'] = json_decode( $attr['postCategory'] );
			if ( is_array( $attr['postCategory'] ) || is_object( $attr['postCategory'] ) ) {
				foreach ( $attr['postCategory'] as $value ) {
					$cat_arr[] = $value->value;
				}
			}
		}
		if ( 'post' === $attr['postType'] ) {
			$query_args['category__in'] = $cat_arr;
		} elseif ( ! empty( $attr['taxonomySlug'] ) && ! empty( $cat_arr ) ) {
			$query_args['tax_query'] = array( // phpcs:ignore WordPress.DB.DirectDatabaseQuery,WordPress.DB.DirectDatabaseQuery.NoCaching,WordPress.DB.PreparedSQL.InterpolatedNotPrepared,WordPress.DB.PreparedSQL.NotPrepared,WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				array(
					'taxonomy' => $attr['taxonomySlug'],
					'field'    => 'term_id',
					'terms'    => $cat_arr,
				),
			);
		}
	}
	if ( '' !== $attr['postTag'] ) {
		$tag_arr = array();
		if ( is_string( $attr['postTag'] ) ) {
			$attr['postTag'] = json_decode( $attr['postTag'] );
			if ( is_array( $attr['postTag'] ) || is_object( $attr['postTag'] ) ) {
				foreach ( $attr['postTag'] as $value ) {
					$tag_arr[] = $value->value;
				}
			}
		}
		if ( 'post' === $attr['postType'] ) {
			$query_args['tag__in'] = $tag_arr;
		}
	}

	// Archive Posts.
	if ( ! empty( $attr['postListing'] ) && 'archive_listing' === $attr['postListing'] ) {
		global $wp_query;
		$query_var = $wp_query->query_vars;
		if ( isset( $query_var['cat'] ) ) {
			$query_args['category__in'] = $query_var['cat'];
		}
		if ( isset( $query_var[ $attr['taxonomySlug'] ] ) && 'post' !== $attr['postType'] ) {

			$query_args['tax_query'] = array( // phpcs:ignore WordPress.DB.DirectDatabaseQuery,WordPress.DB.DirectDatabaseQuery.NoCaching,WordPress.DB.PreparedSQL.InterpolatedNotPrepared,WordPress.DB.PreparedSQL.NotPrepared,WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				array(
					'taxonomy' => $attr['taxonomySlug'],
					'field'    => 'slug',
					'terms'    => $query_var[ $attr['taxonomySlug'] ],
				),
			);
		} elseif ( 'post' === $attr['postType'] ) {
			if ( isset( $query_var['taxonomy'] ) && ! empty( $query_var['taxonomy'] ) ) {
				$query_args['tax_query'] = array( // phpcs:ignore WordPress.DB.DirectDatabaseQuery,WordPress.DB.DirectDatabaseQuery.NoCaching,WordPress.DB.PreparedSQL.InterpolatedNotPrepared,WordPress.DB.PreparedSQL.NotPrepared,WordPress.DB.SlowDBQuery.slow_db_query_tax_query
					array(
						'taxonomy' => $query_var['taxonomy'],
						'field'    => 'slug',
						'terms'    => $query_var[ $query_var['taxonomy'] ],
					),
				);
			}
		}

		if ( isset( $query_var['tag_id'] ) ) {
			$query_args['tag__in'] = $query_var['tag_id'];
		}
		if ( isset( $query_var['author'] ) ) {
			$query_args['author'] = $query_var['author'];
		}
		if ( is_search() ) {
			$search              = get_query_var( 's' );
			$query_args['s']     = $search;
			$query_args['exact'] = false;
		}
	}

	// Related Posts.
	if ( ! empty( $attr['postListing'] ) && 'related_post' === $attr['postListing'] ) {
		global $post;

		if ( isset( $post->post_type ) && 'post' === $post->post_type ) {
			$tag_slug = 'term_id';
			$tags     = wp_get_post_tags( $post->ID );
		} else {
			$tag_slug = 'slug';
			$tags     = isset( $post->ID ) ? wp_get_post_terms( $post->ID, $attr['taxonomySlug'] ) : array();
		}
		if ( $tags && ! empty( $attr['postListing'] ) && ( 'both' === $attr['relatedPost'] || 'tags' === $attr['relatedPost'] ) ) {
			$tag_ids = array();

			foreach ( $tags as $individual_tag ) {
				$tag_ids[] = $individual_tag->$tag_slug;
			}

			$query_args['post__not_in'] = array( $post->ID );
			if ( isset( $post->post_type ) && 'post' === $post->post_type ) {
				$query_args['tag__in'] = $tag_ids;
			} else {
				$query_args['tax_query'] = array( // phpcs:ignore WordPress.DB.DirectDatabaseQuery,WordPress.DB.DirectDatabaseQuery.NoCaching,WordPress.DB.PreparedSQL.InterpolatedNotPrepared,WordPress.DB.PreparedSQL.NotPrepared,WordPress.DB.SlowDBQuery.slow_db_query_tax_query
					array(
						'taxonomy' => $attr['taxonomySlug'],
						'field'    => 'slug',
						'terms'    => $tag_ids,
					),
				);
			}
		}
		if ( isset( $post->post_type ) && 'post' === $post->post_type ) {
			$categories_slug = 'cat_ID';
			$categories      = get_the_category( $post->ID );
		} else {
			$categories_slug = 'slug';
			$categories      = isset( $post->ID ) ? wp_get_post_terms( $post->ID, $attr['taxonomySlug'] ) : array();
		}

		if ( $categories && ! empty( $attr['relatedPost'] ) && ( 'both' === $attr['relatedPost'] || 'category' === $attr['relatedPost'] ) ) {
			$category_ids = array();
			foreach ( $categories as $category ) {
				$category_ids[] = $category->$categories_slug;
			}

			$query_args['post__not_in'] = array( $post->ID );

			if ( isset( $post->post_type ) && 'post' === $post->post_type ) {
				$query_args['category__in'] = $category_ids;
			} else {
				$query_args['tax_query'] = array( // phpcs:ignore WordPress.DB.DirectDatabaseQuery,WordPress.DB.DirectDatabaseQuery.NoCaching,WordPress.DB.PreparedSQL.InterpolatedNotPrepared,WordPress.DB.PreparedSQL.NotPrepared,WordPress.DB.SlowDBQuery.slow_db_query_tax_query
					array(
						'taxonomy' => $attr['taxonomySlug'],
						'field'    => 'slug',
						'terms'    => $category_ids,
					),
				);
			}
		}
	}

	return $query_args;
}

/**
 * Tpgb pagination.
 *
 * @param string $pages The pages.
 * @param int    $range The range.
 * @return mixed The result.
 */
function tpgb_pagination( $pages = '', $range = 2 ) {
	$showitems = ( $range * 2 ) + 1;

	global $paged;
	if ( empty( $paged ) ) {
		$paged = 1; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
	}

	if ( '' === $pages ) {
		global $wp_query;
		if ( $wp_query->max_num_pages <= 1 ) {
			return;
		}

		$pages = $wp_query->max_num_pages;
		/* // phpcs:ignore Squiz.PHP.CommentedOutCode.Found
		if(!$pages)
		{
			$pages = 1;
		}*/
		$pages = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
	}

	if ( 1 !== $pages ) {
		$paginate = '<div class="tpgb-pagination">';
		if ( get_previous_posts_link() ) {
			$paginate .= '<div class="paginate-prev">' . get_previous_posts_link( '<i class="fa fa-long-arrow-left" aria-hidden="true"></i> PREV' ) . '</div>';
		}

		for ( $i = 1; $i <= $pages; $i++ ) {
			if ( 1 !== $pages && ( ! ( $i >= $paged + $range + 1 || $i <= $paged - $range - 1 ) || $pages <= $showitems ) ) {
				$paginate .= ( $paged === $i ) ? '<span class="current">' . esc_html( $i ) . '</span>' : "<a href='" . get_pagenum_link( $i ) . "' class=\"inactive\">" . esc_html( $i ) . '</a>';
			}
		}
		if ( get_next_posts_link() ) {
			$paginate .= '<div class="paginate-next">' . get_next_posts_link( 'NEXT <i class="fa fa-long-arrow-right" aria-hidden="true"></i>', 1 ) . '</div>';
		}

		$paginate .= "</div>\n";
		return $paginate;
	}
}
