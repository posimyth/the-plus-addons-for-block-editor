<?php
/**
 * Posts Search Bar.
 *
 * @package ThePluginAddonsForBlockEditor
 */

// phpcs:disable Squiz.Commenting.InlineComment.InvalidEndChar
// phpcs:disable Squiz.PHP.CommentedOutCode.Found

defined( 'ABSPATH' ) || exit;

/**
 * Tpgb tp search bar render callback.
 *
 * @param mixed $attr The attr.
 * @param mixed $content The content.
 * @return mixed The result.
 */
function tpgb_tp_search_bar_render_callback( $attr, $content ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
	$output          = '';
	$block_id        = ( ! empty( $attr['block_id'] ) ) ? $attr['block_id'] : uniqid( 'title' );
	$placeholder     = ( ! empty( $attr['placeholder'] ) ) ? $attr['placeholder'] : '';
	$show_butn       = ( ! empty( $attr['showButn'] ) ) ? $attr['showButn'] : '';
	$search_field    = ( ! empty( $attr['searchField'] ) ) ? $attr['searchField'] : array();
	$search_type     = ( ! empty( $attr['searchType'] ) ) ? $attr['searchType'] : 'otheroption';
	$search_btn      = ( ! empty( $attr['searchBtn'] ) ) ? $attr['searchBtn'] : array();
	$icon_type       = ( ! empty( $attr['iconType'] ) ) ? $attr['iconType'] : 'fontAwesome';
	$search_icon     = ( ! empty( $attr['searchIcon'] ) ) ? $attr['searchIcon'] : '';
	$result_style    = ( ! empty( $attr['resultStyle'] ) ) ? $attr['resultStyle'] : 'style-1';
	$result_vis_set  = ( ! empty( $attr['resultVisSet'] ) ) ? $attr['resultVisSet'] : array();
	$res_area_link   = ( ! empty( $attr['resAreaLink'] ) ) ? $attr['resAreaLink'] : array();
	$text_limit      = ( ! empty( $attr['textLimit'] ) ) ? $attr['textLimit'] : array();
	$acf_filter      = ( ! empty( $attr['acfFilter'] ) ) ? $attr['acfFilter'] : array();
	$generic_filter  = ( ! empty( $attr['genericFilter'] ) ) ? $attr['genericFilter'] : array();
	$search_label    = ( ! empty( $attr['searchLabel'] ) ) ? $attr['searchLabel'] : '';
	$input_dis       = ( ! empty( $attr['inputDis'] ) ) ? $attr['inputDis'] : false;
	$post_n_fmessage = ( ! empty( $attr['postNFmessage'] ) ) ? $attr['postNFmessage'] : '';
	$pre_suggest     = ( ! empty( $attr['preSuggest'] ) ) ? $attr['preSuggest'] : false;
	$suggest_text    = ( ! empty( $attr['suggestText'] ) ) ? $attr['suggestText'] : '';
	$overlay_tgl     = ( ! empty( $attr['overlayTgl'] ) ) ? $attr['overlayTgl'] : false;

	$ttl_res_text = ( ! empty( $result_vis_set['enTcount'] ) && ! empty( $result_vis_set['tResText'] ) ) ? $result_vis_set['tResText'] : '';

	$post_count     = ( ! empty( $attr['postCount'] ) ) ? (int) $attr['postCount'] : 3;
	$block_template = ( ! empty( $attr['blockTemplate'] ) ) ? $attr['blockTemplate'] : '';

	$include_terms = ( ! empty( $attr['includeTerms'] ) ) ? json_decode( $attr['includeTerms'] ) : '';
	$exclude_terms = ( ! empty( $attr['excludeTerms'] ) ) ? json_decode( $attr['excludeTerms'] ) : '';
	$taxonomy_slug = ( ! empty( $attr['taxonomySlug'] ) ) ? $attr['taxonomySlug'] : '';

	$exclude_array = json_decode( wp_json_encode( $exclude_terms ), true );
	$include_array = json_decode( wp_json_encode( $include_terms ), true );

	$exclude_term_array = array();
	foreach ( $exclude_array as $index => $exterm ) {
		$exclude_term_array[] = $exterm['value'];
	}
	$include_term_array = array();
	foreach ( $include_array as $index => $interm ) {
		$include_term_array[] = $interm['value'];
	}

	$block_class = Tp_Blocks_Helper::block_wrapper_classes( $attr );

	$dis_input_class = ( ! empty( $input_dis ) ) ? 'tpgb-ser-input-dis' : '';

	$all_result_load = false;
	$on_load_attr    = array();
	$lsearch_data    = array();

	$extra_inc_exc = true;
	$s_cpt         = true;

	// Set Field In Filter Area.
	$filter_field = '';
	if ( ! empty( $search_field ) ) {
		foreach ( $search_field as $index => $item ) {
			$field_value   = '';
			$source_type   = ! empty( $item['sourceType'] ) ? $item['sourceType'] : '';
			$post_data     = ! empty( $item['postType'] ) ? $item['postType'] : array( 'post' );
			$taxonomy_data = ! empty( $item['taxonomy'] ) ? $item['taxonomy'] : '';
			$showsubcat    = ! empty( $item['showSubCat'] ) ? $item['showSubCat'] : '';
			$ph_all_result = ! empty( $item['phAllResult'] ) ? $item['phAllResult'] : false;
			$data_array    = array();

			if ( ( 'post' === $source_type ) && ! empty( $item['postType'] ) && ( ! empty( $post_data ) && is_array( $post_data ) || is_object( $post_data ) ) ) { // phpcs:ignore Generic.CodeAnalysis.RequireExplicitBooleanOperatorPrecedence.MissingParentheses
				$s_cpt = false;
				foreach ( $post_data as $value ) {
					$count                         = wp_count_posts( $value['value'] );
					$count_num                     = ! empty( $count->publish ) ? $count->publish : 0;
					$data_array[ $value['value'] ] = array(
						'name'  => ucfirst( $value['value'] ),
						'count' => $count_num,
					);
				}
				if ( ! empty( $data_array ) ) {
					// $tDataA = count($data_array); // phpcs:ignore Squiz.PHP.CommentedOutCode.Found
					// if($tDataA > 1){
						$field_value .= tpgb_search_drop_down( $data_array, 'post', $block_id, $taxonomy = '', $item, $input_dis, $exclude_term_array, $include_term_array );
					// }
				}
			} elseif ( 'taxonomy' === $source_type && ! empty( $taxonomy_data ) ) {
				$extra_inc_exc = false;
				$cat_args      = array(
					'taxonomy'   => $taxonomy_data,
					'parent'     => 0,
					'hide_empty' => false,
				);
				$tax_terms     = get_categories( $cat_args );

				if ( ! empty( $ph_all_result ) ) {
					$all_result_load = true;
					$lsearch_data    = array(
						's'                          => '',
						'taxonomy_' . $taxonomy_data => 'all',
					);
				}

				foreach ( $tax_terms as $index => $value ) {
					$name    = ! empty( $value->name ) ? $value->name : '';
					$number  = ! empty( $value->category_count ) ? $value->category_count : 0;
					$term_id = ! empty( $value->term_id ) ? $value->term_id : '';

					$data_array[ $term_id ] = array(
						'name'   => $name,
						'count'  => $number,
						'parent' => '',
					);
					if ( 'category' === $taxonomy_data && 'yes' === $showsubcat ) {
						$args2      = array(
							'taxonomy'     => $taxonomy_data,
							'child_of'     => 0,
							'parent'       => $term_id,
							'orderby'      => 'name',
							'show_count'   => 1,
							'pad_counts'   => 0,
							'hierarchical' => 1,
							'title_li'     => '',
							'hide_empty'   => 0,
						);
						$tax_terms2 = get_categories( $args2 );
						foreach ( $tax_terms2 as $one ) {
							$oname                       = ! empty( $one->name ) ? $one->name : '';
							$ocount                      = ! empty( $one->count ) ? $one->count : '';
							$data_array[ $one->term_id ] = array(
								'name'   => ' - ' . ucwords( $oname ),
								'count'  => $ocount,
								'parent' => $name,
							);
						}
					}
				}
				if ( ! empty( $data_array ) ) {

					$field_value .= tpgb_search_drop_down( $data_array, 'category', $block_id, $taxonomy = $taxonomy_data, $item, $input_dis, $exclude_term_array, $include_term_array );
				}
			}
			if ( ! empty( $field_value ) ) {
				$filter_field .= '<div class="tpgb-post-dropdown">' . $field_value . '</div>';
			}
		}
	}

	$extra_include_exclude = '';
	if ( ! empty( $extra_inc_exc ) ) {
		$ex_terms      = '';
		$dat_name      = 'cat';
		$negated_array = array();
		if ( ! empty( $exclude_term_array ) ) {
			$negated_array = array_map(
				function ( $value ) {
					return '-' . $value;
				},
				$exclude_term_array
			);
		}
		if ( ! empty( $negated_array ) && ! empty( $include_term_array ) ) {
			$array_merge_ex_in = array_merge( $negated_array, $include_term_array );
			$ex_terms          = implode( ',', $array_merge_ex_in );
		} elseif ( ! empty( $include_term_array ) ) {
			$ex_terms = implode( ',', $include_term_array );
		}

		$extra_include_exclude = '<input type="hidden" name="cat" id="cat" value="' . esc_attr( $ex_terms ) . '">';
	}

	// Result Attributes.
	$result_on_off = array();
	if ( 'custom' !== $result_style ) {
		$result_on_off = array(
			'ONTitle'          => ! empty( $result_vis_set['enTitle'] ) ? 1 : 0,
			'ONContent'        => ! empty( $result_vis_set['enContent'] ) ? 1 : 0,
			'ONThumb'          => ! empty( $result_vis_set['enThumb'] ) ? 1 : 0,
			'ONPrice'          => ! empty( $result_vis_set['enPrice'] ) ? 1 : 0,
			'ONShortDesc'      => ! empty( $result_vis_set['enSdesc'] ) ? 1 : 0,
			'TotalResult'      => ! empty( $result_vis_set['enTcount'] ) ? 1 : 0,
			'TotalResultTxt'   => $ttl_res_text,

			'ResultlinkOn'     => ! empty( $res_area_link['resLinkEn'] ) ? 1 : 0,
			'Resultlinktarget' => ! empty( $res_area_link['resLinkTarget'] ) ? $res_area_link['resLinkTarget'] : '',

			'TxtTitle'         => ! empty( $text_limit['titleLimit'] ) ? 1 : 0,
			'texttype'         => ! empty( $text_limit['limitOnTitle'] ) ? $text_limit['limitOnTitle'] : 'char',
			'textcount'        => ! empty( $text_limit['titleLmtCnt'] ) ? $text_limit['titleLmtCnt'] : 100,
			'textdots'         => ! empty( $text_limit['titleDisplayDot'] ) ? $text_limit['titleDisplayDot'] : '',
			'Txtcont'          => ! empty( $text_limit['contentLimit'] ) ? 1 : 0,
			'ContType'         => ! empty( $text_limit['limitOnContent'] ) ? $text_limit['limitOnContent'] : 'char',
			'ContCount'        => ! empty( $text_limit['contentLmtCnt'] ) ? $text_limit['contentLmtCnt'] : 100,
			'ContDots'         => ! empty( $text_limit['contentDisplayDot'] ) ? $text_limit['contentDisplayDot'] : '',
		);
	}

	$error_message = ! empty( $post_n_fmessage ) ? $post_n_fmessage : 'Sorry, But Nothing Matched Your Search Terms.';

	$lresult_setting = $result_on_off;
	if ( ! empty( $result_on_off ) ) {
		$result_on_off = htmlspecialchars( wp_json_encode( $result_on_off ), ENT_QUOTES, 'UTF-8' );
	}

	$acf_data  = array(
		'ACFEnable' => ! empty( $acf_filter ) ? 1 : 0,
		'ACFkey'    => ! empty( $acf_filter['acfKey'] ) ? $acf_filter['acfKey'] : '',
	);
	$lacf_data = $acf_data;
	$acf_data  = wp_json_encode( $acf_data, true );

	$page_style = isset( $attr['loadOptions'] ) ? $attr['loadOptions'] : 'none';
	$load_page  = ! empty( $attr['loadMoreCounter'] ) ? 1 : 0;
	$page_data  = array();
	if ( 'pagination' === $page_style ) {
		$page_data = array(
			'Pagestyle'     => $page_style,
			'Pcounter'      => ! empty( $attr['counterEnable'] ) ? 1 : 0,
			'PClimit'       => ! empty( $attr['counterLimit'] ) ? $attr['counterLimit'] : 5,
			'PNavigation'   => ! empty( $attr['arrowNav'] ) ? 1 : 0,
			'PNxttxt'       => ! empty( $attr['cNextText'] ) ? $attr['cNextText'] : '',
			'PPrevtxt'      => ! empty( $attr['cPrevText'] ) ? $attr['cPrevText'] : '',
			'PNxticonType'  => ! empty( $attr['cNextIconType'] ) ? $attr['cNextIconType'] : 'none',
			'PNxticon'      => ! empty( $attr['cNextIcon'] ) ? $attr['cNextIcon'] : '',
			'PPreviconType' => ! empty( $attr['cPrevIconType'] ) ? $attr['cPrevIconType'] : 'none',
			'PPrevicon'     => ! empty( $attr['cPrevIcon'] ) ? $attr['cPrevIcon'] : '',
			'Pstyle'        => ! empty( $attr['counterStyle'] ) ? $attr['counterStyle'] : 'center',
		);
	} else {
		$page_data = array(
			'Pagestyle'   => $page_style,
			'loadbtntxt'  => ! empty( $attr['loadbtnText'] ) ? $attr['loadbtnText'] : '',
			'loadingtxt'  => ! empty( $attr['loadingtxt'] ) ? $attr['loadingtxt'] : '',
			'loadedtxt'   => ! empty( $attr['allposttext'] ) ? $attr['allposttext'] : '',
			'loadnumber'  => ! empty( $attr['postview'] ) ? $attr['postview'] : '',
			'loadpage'    => $load_page,
			'loadPagetxt' => ! empty( $attr['counterText'] ) ? $attr['counterText'] : '',
		);
	}
	$lpagesetting = $page_data;
	$page_json    = wp_json_encode( $page_data, true );

	$g_filter = array();
	if ( ! empty( $generic_filter ) ) {
		$g_filter = array(
			'GFEnable'   => 1,
			'GFSType'    => $search_type,
			'GFTitle'    => ! empty( $generic_filter['searchTitle'] ) ? 1 : 0,
			'GFContent'  => ! empty( $generic_filter['searchContent'] ) ? 1 : 0,
			'GFName'     => ! empty( $generic_filter['searchPermalink'] ) ? 1 : 0,
			'GFExcerpt'  => ! empty( $generic_filter['searchExcerpt'] ) ? 1 : 0,
			'GFCategory' => ! empty( $generic_filter['searchCategory'] ) ? 1 : 0,
			'GFTags'     => ! empty( $generic_filter['searchTags'] ) ? 1 : 0,
		);
	} else {
		$g_filter = array(
			'GFEnable' => 0,
			'GFSType'  => $search_type,
		);
	}
	$l_g_filter = $g_filter;
	$g_farray   = wp_json_encode( $g_filter, true );

	$special_ctp      = ! empty( $attr['specificCTP'] ) ? 1 : 0;
	$special_ctp_type = ! empty( $attr['ctpType'] ) ? $attr['ctpType'] : 'post';

	$defa_postype = array();
	$defa_tex     = array();
	$temp         = ! empty( $attr['searchField'] ) ? $attr['searchField'] : array();
	if ( ! empty( $temp ) ) {
		foreach ( $temp as $idx => $item ) {
			$sty = ! empty( $item['sourceType'] ) ? $item['sourceType'] : array( 'post' );
			if ( 'post' === $sty && ! empty( $item['postType'] ) ) {
				foreach ( $item['postType'] as $item1 ) {
					// $defa_postype[] = $item1; // phpcs:ignore Squiz.PHP.CommentedOutCode.Found
					$defa_postype[] = $item1['value'];
				}
			}
		}
	}

	$default_settingg = array(
		'Def_Post'       => $defa_postype,
		'Def_Tex'        => '',
		'SpecialCTP'     => $special_ctp,
		'SpecialCTPType' => $special_ctp_type,
		'excludeTerms'   => $exclude_terms,
		'includeTerms'   => $include_terms,
		'taxonomySlug'   => $taxonomy_slug,
	);
	$l_default_data   = $default_settingg;
	$default_setting  = wp_json_encode( $default_settingg, true );

	$suggest     = '';
	$suggestlist = '';
	if ( ! empty( $pre_suggest ) ) {
		$suggestlist = 'list="tpgb-input-suggestions"';
		$sug_explod  = explode( '|', $suggest_text );
		$suggest    .= '<datalist id="tpgb-input-suggestions">';
		foreach ( $sug_explod as $two ) {
			$suggest .= '<option value="' . ltrim( rtrim( $two ) ) . '">';
		}
		$suggest .= '</datalist>';
	}

	$scrollclass = ! empty( $attr['scrollBar'] ) ? 'tpgb-search-scrollbar' : '';
	$rcolumn     = '';
	if ( 'style-2' === $result_style || 'custom' === $result_style ) {
		$rcolumn  = 'tpgb-col-12 ';
		$rcolumn .= isset( $attr['columns']['md'] ) ? ' tpgb-col-lg-' . $attr['columns']['md'] : ' tpgb-col-lg-3';
		$rcolumn .= isset( $attr['columns']['sm'] ) ? ' tpgb-col-md-' . $attr['columns']['sm'] : ' tpgb-col-md-4';
		$rcolumn .= isset( $attr['columns']['xs'] ) ? ' tpgb-col-sm-' . $attr['columns']['xs'] : ' tpgb-col-sm-6';

	} else {
		$rcolumn = 'tpgb-col-12 tpgb-col-lg-12 tpgb-col-md-12 tpgb-col-sm-12 tpgb-col-12';
	}

	// Set parameter.
	$dataattr                        = array();
	$dataattr['ajax']                = ! empty( $attr['ajaxsearch'] ) ? 'yes' : 'no';
	$dataattr['ajaxsearchCharLimit'] = ! empty( $attr['searchClimit'] ) ? $attr['searchClimit'] : 2;
	$dataattr['nonce']               = wp_create_nonce( 'tpgb-searchbar' );
	$dataattr['style']               = $result_style;
	$dataattr['tempid']              = $block_template;
	$dataattr['styleColumn']         = $rcolumn;
	$dataattr['post_page']           = $post_count;
	$dataattr['Postype_Def']         = $defa_postype;
	$dataattr                        = htmlspecialchars( wp_json_encode( $dataattr ), ENT_QUOTES, 'UTF-8' );

	if ( ! empty( $result_style ) && 'custom' === $result_style && isset( $attr['blockTemplate'] ) && ! empty( $attr['blockTemplate'] ) ) {
		Tpgb_Library()->plus_do_block( $attr['blockTemplate'] );
	}

	$output .= '<div class="tpgb-search-bar tpgb-relative-block tpgb-block-' . esc_attr( $block_id ) . ' ' . esc_attr( $block_class ) . ' ' . esc_attr( $dis_input_class ) . '" data-id="' . esc_attr( $block_id ) . '" data-ajax_search= \'' . $dataattr . '\' data-result-setting= \'' . $result_on_off . '\' data-genericfilter=' . $g_farray . ' data-pagination-data= \'' . $page_json . '\' data-acfdata=' . esc_attr( $acf_data ) . ' data-default-data= \'' . $default_setting . '\' data-errormsg="' . htmlspecialchars( $error_message ) . '">';

	if ( ! empty( $overlay_tgl ) ) {
		$output .= '<div class="tpgb-rental-overlay"></div>';
	}

		$output     .= '<form class="tpgb-search-form" method="get" action="' . esc_url( site_url() ) . '">';
			$output .= '<div class="tpgb-form-field tpgb-row">';
	if ( empty( $input_dis ) ) {
		$output     .= '<div class="tpgb-input-field">';
			$output .= '<div class="tpgb-input-label-field">';
		if ( ! empty( $search_label ) ) {
				$output .= '<label class="tpgb-search-label tpgb-trans-linear">' . esc_html( $search_label ) . '</label>';
		}
			$output     .= '</div>';
			$output     .= '<div class="tpgb-input-inner-field">';
				$output .= '<input name="s" ' . $suggestlist . ' id="seatxt-' . esc_attr( $block_id ) . '" class="tpgb-search-input" type="text" name="search" placeholder="' . esc_attr( $placeholder ) . '" autocomplete="off" />';
				$output .= $suggest;
		if ( 'fontAwesome' === $icon_type && ! empty( $search_icon ) ) {
						$output .= '<span class="tpgb-search-input-icon"><i class="' . esc_attr( $search_icon ) . '"></i></span>';
		}
								$output .= '<div class="tpgb-ajx-loading"><div class="tpgb-spinner-loader"></div></div>';
								$output .= '<span class="tpgb-close-btn"><i class="fas fa-times-circle"></i></span>';
								$output .= '</div>';
								$output .= '</div>';
	}

				$output .= $filter_field;
				$output .= $extra_include_exclude;

	if ( ! empty( $search_btn ) ) {
		$get_media = '';
		if ( ! empty( $search_btn['searchBtnTgl'] ) ) {
			if ( 'fontAwesome' === $search_btn['sBtnIconType'] && ! empty( $search_btn['sBtnIcon'] ) ) {
				$get_media .= '<span class="tpgb-button-icon"><i class="' . esc_attr( $search_btn['sBtnIcon'] ) . '"></i></span>';
			} elseif ( 'image' === $search_btn['sBtnIconType'] && ! empty( $search_btn['imgField']['url'] ) ) {
				$alt_text = ( isset( $search_btn['imgField']['alt'] ) && ! empty( $search_btn['imgField']['alt'] ) ) ? esc_attr( $search_btn['imgField']['alt'] ) : ( ( ! empty( $search_btn['imgField']['title'] ) ) ? esc_attr( $search_btn['imgField']['title'] ) : esc_attr__( 'Button Image', 'the-plus-addons-for-block-editor' ) );

				$get_media .= '<span class="tpgb-button-Image"><img src="' . esc_url( $search_btn['imgField']['url'] ) . '" class="tpgb-button-ImageTag" alt="' . $alt_text . '"></span>';
			}
			$output         .= '<div class="tpgb-btn-wrap">';
				$output     .= '<button class="tpgb-search-btn" name="submit" >';
					$output .= ( 'before' === $search_btn['sIconPos'] ) ? $get_media : '';
			if ( ! empty( $search_btn['sBtnText'] ) ) {
					$output .= '<span class="tpgb-search-btn-txt ' . esc_attr( $search_btn['sIconPos'] ) . '">' . esc_html( $search_btn['sBtnText'] ) . '</span>';
			}
					$output     .= ( 'after' === $search_btn['sIconPos'] ) ? $get_media : '';
				$output         .= '</button>';
						$output .= '</div>';
		}
	}
	if ( ! empty( $special_ctp ) && ! empty( $s_cpt ) ) {
		$output .= '<input type="hidden" name="post_type" value="' . esc_attr( $special_ctp_type ) . '" />';
	}
			$output .= '</div>';
		$output     .= '</form>';

		$on_load_data   = '';
		$l_search_res   = '';
		$l_pagnation    = '';
		$page_column    = '';
		$lloadmore      = '';
		$lloadmorepage  = '';
		$llazyload      = '';
		$ttl_post_count = '';
		$l_style        = '';
	if ( ! empty( $all_result_load ) ) {
		$on_load_attr = array(
			'searchData'    => $lsearch_data,
			'text'          => '',
			'postper'       => $post_count,
			'GFilter'       => $l_g_filter,
			'ACFilter'      => $lacf_data,
			'styleColumn'   => $rcolumn,
			'style'         => $result_style,
			'tempId'        => $block_template,
			'ResultData'    => $lpagesetting,
			'DefaultData'   => $l_default_data,
			'resultSetting' => $lresult_setting,
		);
		$on_load_data = tpgb_search( $on_load_attr );
		if ( ! empty( $on_load_data ) && ! empty( $on_load_data['posts'] ) ) {
			$l_style   = 'style="display: block"';
			$item_post = '';
			foreach ( $on_load_data['posts'] as $index => $post ) :
				$item_post .= $post;
				endforeach;
			$l_search_res .= '<div class="tpgb-search-slider tpgb-row">' . $item_post . '</div>';

			if ( isset( $on_load_data['pagination'] ) && ! empty( $on_load_data['pagination'] ) ) {
				$l_pagnation = $on_load_data['pagination'];
				$page_column = 'data-pageColumn="' . esc_attr( $on_load_data['columns'] ) . '"';

			}
			if ( isset( $on_load_data['lazymore'] ) && ! empty( $on_load_data['lazymore'] ) ) {
				$llazyload = $on_load_data['lazymore'];
			}
			if ( isset( $on_load_data['loadmore'] ) && ! empty( $on_load_data['loadmore'] ) ) {
				$lloadmore = $on_load_data['loadmore'];
			}
			if ( isset( $on_load_data['loadmore_page'] ) && ! empty( $on_load_data['loadmore_page'] ) ) {
				$lloadmorepage = $on_load_data['loadmore_page'];
			}

			if ( isset( $on_load_data['post_count'] ) && ! empty( $on_load_data['post_count'] ) ) {
				$ttl_post_count = $on_load_data['post_count'] . ' ' . $ttl_res_text;
			}
		}
	}

		$output     .= '<div class="tpgb-search-area ' . esc_attr( $result_style ) . '" ' . $l_style . '>';
			$output .= '<div class="tpgb-search-error"></div>';
			$output .= '<div class="tpgb-search-header tpgb-trans-linear">';
	if ( ! empty( $result_vis_set['enTcount'] ) ) {
		$output .= '<div class="tpgb-search-resultcount">' . wp_kses_post( $ttl_post_count ) . '</div>';
	}
	if ( ( 'pagination' === $page_style ) || ( 'load_more' === $page_style && ! empty( $load_page ) ) ) {
		$output .= '<div class="tpgb-search-pagina" ' . $page_column . '>' . wp_kses_post( $lloadmorepage ) . wp_kses_post( $l_pagnation ) . '</div>';
	}
			$output     .= '</div>';
			$output     .= '<div class="tpgb-search-list ' . esc_attr( $scrollclass ) . '">';
				$output .= '<div class="tpgb-search-list-inner">' . wp_kses_post( $l_search_res ) . '</div>';
			$output     .= '</div>';
	if ( 'load_more' === $page_style ) {
		$output .= '<div class="tpgb-load-more">' . wp_kses_post( $lloadmore ) . '</div>';
	} elseif ( 'lazy_load' === $page_style ) {
		$output .= '<div class="tpgb-lazy-load">' . wp_kses_post( $llazyload ) . '</div>';
	}
		$output .= '</div>';

	$output .= '</div>';

	$output = Tpgb_Blocks_Global_Options::block_Wrap_Render( $attr, $output );

	return $output;
}

/**
 * Render for the server-side
 */
function tpgb_search_bar() {

	if ( method_exists( 'Tpgb_Blocks_Global_Options', 'merge_options_json' ) ) {
		$block_data = Tpgb_Blocks_Global_Options::merge_options_json( __DIR__, 'tpgb_tp_search_bar_render_callback' );
		register_block_type( $block_data['name'], $block_data );
	}
}
add_action( 'init', 'tpgb_search_bar' );

// Get Html For Select Drop Down.
/**
 * Tpgb search.
 *
 * @param array $on_load_attr The on load attr.
 */
function tpgb_search( $on_load_attr = array() ) {

	$new_post = map_deep( wp_unslash( $_POST ), 'sanitize_text_field' );

	$search_data = array();
	if ( ! empty( $on_load_attr ) ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIF, Generic.CodeAnalysis.EmptyStatement.DetectedIf
		// $search_data = $new_post['searchData']; // phpcs:ignore Squiz.PHP.CommentedOutCode.Found
	} elseif ( ! isset( $new_post['nonce'] ) || empty( $new_post['nonce'] ) || ! wp_verify_nonce( $new_post['nonce'], 'tpgb-searchbar' ) ) {

			die( 'Security checked!' );
	}

	$result_setting = array();
	$default_data   = array();
	$g_filter       = array();
	$result_data    = array();
	$ac_filter      = array();

	if ( isset( $new_post['resultSetting'] ) ) {
		$result_setting = ! empty( $new_post['resultSetting'] ) ? $new_post['resultSetting'] : array();
	} else {
		$json_string    = stripslashes( $new_post['data'] );
		$json_string    = json_decode( $json_string, true );
		$result_setting = $json_string['resultSetting'];
	}
	if ( isset( $new_post['DefaultData'] ) ) {
		$default_data = ! empty( $new_post['DefaultData'] ) ? $new_post['DefaultData'] : array();
	} else {
		$json_string  = stripslashes( $new_post['data'] );
		$json_string  = json_decode( $json_string, true );
		$default_data = $json_string['DefaultData'];
	}
	if ( isset( $new_post['GFilter'] ) ) {
		$g_filter = ! empty( $new_post['GFilter'] ) ? $new_post['GFilter'] : array();
	} else {
		$json_string = stripslashes( $new_post['data'] );
		$json_string = json_decode( $json_string, true );
		$g_filter    = $json_string['GFilter'];
	}
	if ( isset( $new_post['ResultData'] ) ) {
		$result_data = ! empty( $new_post['ResultData'] ) ? $new_post['ResultData'] : array();
	} else {
		$json_string = stripslashes( $new_post['data'] );
		$json_string = json_decode( $json_string, true );
		$result_data = $json_string['ResultData'];
	}
	if ( isset( $new_post['ACFilter'] ) ) {
		$ac_filter = ! empty( $new_post['ACFilter'] ) ? $new_post['ACFilter'] : array();
	} else {
		$json_string = stripslashes( $new_post['data'] );
		$json_string = json_decode( $json_string, true );
		$ac_filter   = $json_string['ACFilter'];
	}
	if ( isset( $new_post['searchData'] ) ) {
		$search_data = ! empty( $new_post['searchData'] ) ? $new_post['searchData'] : array();
	} else {
		$json_string = stripslashes( $new_post['data'] );
		$json_string = json_decode( $json_string, true );
		$search_data = $json_string['searchData'];
	}

	$style        = ! empty( $new_post['style'] ) ? $new_post['style'] : 'style-1';
	$temp_id      = ! empty( $new_post['tempId'] ) ? $new_post['tempId'] : '';
	$style_column = ! empty( $new_post['styleColumn'] ) ? $new_post['styleColumn'] : 'tpgb-col-12 tpgb-col-lg-12 tpgb-col-md-12 tpgb-col-sm-12 tpgb-col-12';
	// $default_data = !empty($new_post['DefaultData']) ? $new_post['DefaultData'] : ''; // phpcs:ignore Squiz.PHP.CommentedOutCode.Found

	$special_ctp = ( ! empty( $default_data ) && ! empty( $default_data['specificCTP'] ) ) ? 1 : 0;
	if ( ! empty( $default_data ) && ! empty( $default_data['Def_Post'] ) ) {
		$def_post = $default_data['Def_Post'];
	} elseif ( ! empty( $default_data ) && ! empty( $special_ctp ) ) {
		$def_post = ( ! empty( $default_data ) && ! empty( $default_data['ctpType'] ) ) ? $default_data['ctpType'] : 'post';
	} else {
		$def_post = 'any';
	}

	$enable_default_stxt = 0;
	$post_type           = '';
	if ( ! empty( $search_data ) && ! empty( $search_data['post_type'] ) ) {
		$post_type = sanitize_text_field( $search_data['post_type'] );
	} else {
		$enable_default_stxt = 1;
		$post_type           = $def_post;
	}

	// $post_type = (!empty($search_data) && !empty($search_data['post_type'])) ? sanitize_text_field($search_data['post_type']) : $def_post; // phpcs:ignore Squiz.PHP.CommentedOutCode.Found
	$postper = ! empty( $new_post['postper'] ) ? intval( $new_post['postper'] ) : 3;

	// $g_filter = !empty($new_post['GFilter']) ? $new_post['GFilter'] : []; // phpcs:ignore Squiz.PHP.CommentedOutCode.Found
	$gfs_type = ! empty( $g_filter['GFSType'] ) ? sanitize_text_field( $g_filter['GFSType'] ) : 'otheroption';

	$acf_enable = ! empty( $ac_filter['ACFEnable'] ) ? $ac_filter['ACFEnable'] : 0;
	$acf_key    = ! empty( $ac_filter['ACFkey'] ) ? $ac_filter['ACFkey'] : '';

	if ( 'product' === $post_type && ! class_exists( 'woocommerce' ) ) {
		$response['error']   = 1;
		$response['message'] = 'woocommerce checked!';
		wp_send_json_success( $response );
		die();
	}
	// $result_setting = !empty($new_post['resultSetting']) ? $new_post['resultSetting'] : []; // phpcs:ignore Squiz.PHP.CommentedOutCode.Found
	// $result_data = !empty($new_post['ResultData']) ? $new_post['ResultData'] : [];
	$pagestyle = ! empty( $result_data['Pagestyle'] ) ? $result_data['Pagestyle'] : 'none';

	$response = array(
		'error'      => false,
		'post_count' => 0,
		'message'    => '',
		'posts'      => null,
	);

	if ( ! empty( $search_data ) ) {
		foreach ( $search_data as $key => $value ) {
			if ( strpos( $key, 'taxonomy_' ) !== false ) {
				// Key contains 'taxonomy'.
				$taxonomy_name = str_replace( 'taxonomy_', '', $key );

				$taxonomy = get_taxonomy( $taxonomy_name );
				if ( $taxonomy && ! empty( $taxonomy->object_type ) ) {
					$post_types = $taxonomy->object_type;
					$post_type  = $post_types[0];
				} else {
					$post_type = 'any';
				}
			}
		}
	}

	$query_args = array(
		'post_type'           => $post_type,
		'suppress_filters'    => false,
		'ignore_sticky_posts' => true,
		'orderby'             => 'relevance',
		'posts_per_page'      => -1,
		'post_status'         => 'publish',
	);

	$seaposts = array();
	if ( ! empty( $new_post['text'] ) ) {
		global $wpdb;
		$sql_content = $new_post['text'];
		if ( ! empty( $acf_enable ) || ( ! empty( $g_filter['GFEnable'] ) ) ) {
			$all_data  = array();
			$g_title   = array();
			$g_excerpt = array();
			$gcontent  = array();
			$g_name    = array();
			$p_cat     = array();
			$p_tag     = array();
			$acf_data  = array();

			$result = ( 'fullMatch' === $gfs_type ) ? "{$wpdb->esc_like($sql_content)}" : "%{$wpdb->esc_like($sql_content)}%";

			$publish = $wpdb->prepare( " AND {$wpdb->posts}.post_status= %s ", 'publish' );

			$d_type = '';
			if ( ! empty( $post_type ) ) {
				if ( ! empty( $enable_default_stxt ) ) {
					$d_type = '';
				} else {
					$d_type = $wpdb->prepare( ' AND post_type = %s', $post_type );
				}
			} else {
				$d_type = $wpdb->prepare( " AND post_type IN ('post','page','product')" ); // phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.UnnecessaryPrepare
			}

			if ( ! empty( $g_filter['GFEnable'] ) ) {
				if ( ! empty( $g_filter['GFTitle'] ) ) {
					$g_title = $wpdb->get_results( $wpdb->prepare( "SELECT {$wpdb->posts}.ID FROM {$wpdb->posts} WHERE {$wpdb->posts}.post_title LIKE %s {$publish} {$d_type}", $result ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery,WordPress.DB.DirectDatabaseQuery.NoCaching,WordPress.DB.PreparedSQL.InterpolatedNotPrepared,WordPress.DB.PreparedSQL.NotPrepared,WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				}
				if ( ! empty( $g_filter['GFExcerpt'] ) ) {
					$g_excerpt = $wpdb->get_results( $wpdb->prepare( "SELECT {$wpdb->posts}.ID FROM {$wpdb->posts} WHERE {$wpdb->posts}.post_excerpt LIKE %s {$publish} {$d_type}", $result ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery,WordPress.DB.DirectDatabaseQuery.NoCaching,WordPress.DB.PreparedSQL.InterpolatedNotPrepared,WordPress.DB.PreparedSQL.NotPrepared,WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				}
				if ( ! empty( $g_filter['GFContent'] ) ) {
					$gcontent = $wpdb->get_results( $wpdb->prepare( "SELECT {$wpdb->posts}.ID FROM {$wpdb->posts} WHERE {$wpdb->posts}.post_content LIKE %s {$publish} {$d_type}", $result ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery,WordPress.DB.DirectDatabaseQuery.NoCaching,WordPress.DB.PreparedSQL.InterpolatedNotPrepared,WordPress.DB.PreparedSQL.NotPrepared,WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				}
				if ( ! empty( $g_filter['GFName'] ) ) {
					$g_name = $wpdb->get_results( $wpdb->prepare( "SELECT {$wpdb->posts}.ID FROM {$wpdb->posts} WHERE {$wpdb->posts}.post_name LIKE %s {$publish} {$d_type}", $result ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery,WordPress.DB.DirectDatabaseQuery.NoCaching,WordPress.DB.PreparedSQL.InterpolatedNotPrepared,WordPress.DB.PreparedSQL.NotPrepared,WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				}
				if ( ! empty( $g_filter['GFCategory'] ) && 'page' !== $post_type ) {
					$cat_taxonomy = '';
					$cat_pt       = $post_type;
					$cat_type     = 'category_name';
					if ( 'post' === $post_type ) {
						$cat_taxonomy = 'category';
					} elseif ( 'product' === $post_type ) {
						$cat_taxonomy = 'product_cat';
						$cat_type     = 'product_cat';
					} else {
						$cat_taxonomy = 'any';
						$cat_pt       = 'post';
					}

					$p_cat = query_posts( // phpcs:ignore WordPress.WP.DiscouragedFunctions.query_posts_query_posts
						array(
							'taxonomy'       => $cat_taxonomy,
							'post_type'      => $cat_pt,
							$cat_type        => $sql_content,
							'post_status'    => 'publish',
							'posts_per_page' => -1,
							'orderby'        => 'name',
							'order'          => 'ASC',
							'hide_empty'     => 0,
						)
					);
				}

				if ( ! empty( $g_filter['GFTags'] ) && 'page' !== $post_type ) {
					$tag_taxonomy = '';
					$tag_type     = '';
					$tag_pt       = $post_type;
					if ( 'post' === $post_type ) {
						$tag_taxonomy = 'post_tag';
						$tag_type     = 'tag';
					} elseif ( 'product' === $post_type ) {
						$tag_taxonomy = 'product_tag';
						$tag_type     = 'product_tag';
					}

					$p_tag = query_posts( // phpcs:ignore WordPress.WP.DiscouragedFunctions.query_posts_query_posts
						array(
							'taxonomy'       => $tag_taxonomy,
							'post_type'      => $tag_pt,
							$tag_type        => $sql_content,
							'post_status'    => 'publish',
							'posts_per_page' => -1,
							'orderby'        => 'name',
							'order'          => 'ASC',
							'hide_empty'     => 0,
						)
					);
				}
			}

			if ( class_exists( 'acf' ) && ! empty( $acf_enable ) && ! empty( $acf_key ) ) {
				// Use %1s placeholder for the dynamic $publish value (it's a SQL fragment, not a simple value).
				$acf_prepare = $wpdb->prepare(
					"SELECT {$wpdb->posts}.ID FROM {$wpdb->posts} WHERE {$wpdb->posts}.ID %1s", // phpcs:ignore WordPress.DB.PreparedSQLPlaceholders.UnquotedComplexPlaceholder
					$publish
				);
				$acf_post    = $wpdb->get_results( $acf_prepare ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery,WordPress.DB.DirectDatabaseQuery.NoCaching,WordPress.DB.PreparedSQL.InterpolatedNotPrepared,WordPress.DB.PreparedSQL.NotPrepared,WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				foreach ( $acf_post as $key => $one ) {
					$post_id  = ! empty( $one->ID ) ? $one->ID : '';
					$get_data = acf_get_field( $acf_key )['key'];
					$ac_fone  = get_field( $get_data, $post_id );
					if ( ! empty( $ac_fone ) ) {
						$acf_array = explode( '|', $ac_fone );
						foreach ( $acf_array as $two ) {
							$ac_ftxt = ltrim( rtrim( $two ) );
							if ( ( 'otheroption' === $gfs_type ) && str_contains( strtolower( $ac_ftxt ), strtolower( $sql_content ) ) ) {
								$acf_data[] = $one->ID;
							} elseif ( ( 'fullMatch' === $gfs_type ) && ( strtolower( $ac_ftxt ) === strtolower( $sql_content ) ) ) {
								$acf_data[] = $one->ID;
							}
						}
					}
				}
			}

			array_push( $all_data, $g_title, $g_excerpt, $gcontent, $g_name, $p_cat, $p_tag, $acf_data );

			$tmp_post_id = array();
			if ( ! empty( $all_data ) ) {
				foreach ( $all_data as $one ) {
					if ( ! empty( $one ) ) {
						foreach ( $one as $two ) {
							if ( ! empty( $g_filter['GFEnable'] ) && ! empty( $two->ID ) ) {
								$tmp_post_id[] = $two->ID;
							} elseif ( ! empty( $acf_enable ) && ! empty( $two ) ) {
								$tmp_post_id[] = $two;
							}
						}
					}
				}
			}

			if ( ! empty( $tmp_post_id ) ) {
				$query_args['post__in'] = $tmp_post_id;
			} else {
				$query_args['post__in'] = array( 0 );
			}
		} else {
			$query_args['s'] = $sql_content;

		}
	}
	$tax_query = array();
	if ( 'product' === $post_type ) {
		$tax_query = array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'product_visibility',
				'field'    => 'name',
				'terms'    => array( 'exclude-from-search', 'exclude-from-catalog' ),
				'operator' => 'NOT IN',
			),
		);
	}

	if ( ! empty( $search_data ) ) {
		foreach ( $search_data as $key => $value ) {
			if ( strpos( $key, 'taxonomy_' ) !== false ) {
				// Key contains 'taxonomy'.
				$modified_key = str_replace( 'taxonomy_', '', $key );
				if ( isset( $value ) && ! empty( $value ) && 'all' !== $value ) {
					$tax_query[] = array(
						'taxonomy' => $modified_key,
						'field'    => 'term_id',
						'terms'    => $value,
					);
				}
			}
		}
	}

	if ( ! empty( $default_data['includeTerms'] ) && ! empty( $default_data['taxonomySlug'] ) ) {
		$cat_arr = array();
		if ( is_array( $default_data['includeTerms'] ) || is_object( $default_data['includeTerms'] ) ) {
			foreach ( $default_data['includeTerms'] as $in_value ) {
				if ( is_object( $in_value ) ) {
					$in_value = (array) $in_value;
				}
				$cat_arr[] = $in_value['value'];
			}
		}

		if ( ! empty( $cat_arr ) ) {
			$tax_query[] = array(
				'taxonomy'         => $default_data['taxonomySlug'],
				'field'            => 'term_id',
				'terms'            => $cat_arr,
				'include_children' => true,
				'operator'         => 'IN',
			);
		}
	}
	if ( ! empty( $default_data['excludeTerms'] ) && ! empty( $default_data['taxonomySlug'] ) ) {
		$excat_arr = array();
		if ( is_array( $default_data['excludeTerms'] ) || is_object( $default_data['excludeTerms'] ) ) {
			foreach ( $default_data['excludeTerms'] as $in_value ) {
				$excat_arr[] = $in_value['value'];
			}
		}

		if ( ! empty( $excat_arr ) ) {
			$tax_query[] = array(
				'taxonomy'         => $default_data['taxonomySlug'],
				'field'            => 'term_id',
				'terms'            => $excat_arr,
				'include_children' => true,
				'operator'         => 'NOT IN',
			);
		}
	}

	if ( ! empty( $tax_query ) ) {
		$query_args['tax_query'] = array( // phpcs:ignore WordPress.DB.DirectDatabaseQuery,WordPress.DB.DirectDatabaseQuery.NoCaching,WordPress.DB.PreparedSQL.InterpolatedNotPrepared,WordPress.DB.PreparedSQL.NotPrepared,WordPress.DB.SlowDBQuery.slow_db_query_tax_query
			'relation' => 'AND',
			$tax_query,
		);
	}
	if ( 'none' !== $pagestyle ) {
		$offset        = ! empty( $new_post['offset'] ) ? $new_post['offset'] : '';
		$loadmore_post = ! empty( $new_post['loadNumpost'] ) ? $new_post['loadNumpost'] : $postper;

		$query_args['offset'] = $offset;
		if ( 'pagination' === $pagestyle ) {
			$query_args['posts_per_page'] = $postper;
		} elseif ( 'load_more' === $pagestyle ) {
			$query_args['posts_per_page'] = $loadmore_post;
		} elseif ( 'lazy_load' === $pagestyle ) {
			$query_args['posts_per_page'] = $loadmore_post;
		}
	} else {
		$query_args['posts_per_page'] = $postper;
	}

	$seaposts = new WP_Query( $query_args );

	$total_find = $seaposts->found_posts;

	$response['posts']       = array();
	$response['limit_query'] = $postper;

	$response['columns']     = ceil( $total_find / $postper );
	$response['post_count']  = $total_find;
	$response['total_count'] = $total_find;

	if ( 'pagination' === $pagestyle && $response['limit_query'] < $response['post_count'] ) {
		$response['pagination'] = '';
		$pcounter               = ! empty( $result_data['Pcounter'] ) ? $result_data['Pcounter'] : 0;
		$p_climit               = ! empty( $result_data['PClimit'] ) ? $result_data['PClimit'] : 5;
		$p_navigation           = ! empty( $result_data['PNavigation'] ) ? $result_data['PNavigation'] : 0;
		$p_nxttxt               = ! empty( $result_data['PNxttxt'] ) ? $result_data['PNxttxt'] : '';
		$p_prevtxt              = ! empty( $result_data['PPrevtxt'] ) ? $result_data['PPrevtxt'] : '';
		$p_nxticon              = ! empty( $result_data['PNxticon'] ) ? $result_data['PNxticon'] : '';
		$p_nxticon_type         = ! empty( $result_data['PNxticonType'] ) ? $result_data['PNxticonType'] : 'none';
		$p_previcon             = ! empty( $result_data['PPrevicon'] ) ? $result_data['PPrevicon'] : '';
		$p_previcon_type        = ! empty( $result_data['PPreviconType'] ) ? $result_data['PPreviconType'] : 'none';
		$pstyle                 = ! empty( $result_data['Pstyle'] ) ? $result_data['Pstyle'] : 'center';
		$next                   = '';
		$prev                   = '';
		$btn_num                = '';
		if ( ! empty( $p_navigation ) ) {
			$next     .= '<button class="tpgb-pagelink prev" data-prev="1" >';
				$next .= ( 'fontAwesome' === $p_previcon_type && ! empty( $p_previcon ) ) ? '<span class="tpgb-prev-icon"> <i class="' . esc_attr( $p_previcon ) . ' tpgb-title-icon"></i> </span>' : '';
				$next .= ( ! empty( $p_prevtxt ) ) ? '<span class="tpgb-prev-txt">' . esc_html( $p_prevtxt ) . '</span>' : '';
			$next     .= '</button>';
		}
		if ( ! empty( $pcounter ) ) {
			if ( $response['columns'] <= $p_climit ) {
				for ( $i = 0; $i < $p_climit; $i++ ) {
					if ( $i < $response['columns'] ) {
						$active   = ( ( $i + 1 ) === 1 ) ? 'active' : '';
						$btn_num .= '<button class="tpgb-pagelink tpgb-ajax-page ' . esc_attr( $active ) . '" data-page="' . esc_attr( $i + 1 ) . '" >' . esc_html( $i + 1 ) . '</button>';
					}
				}
			} else {
				for ( $i = 0; $i < $response['columns']; $i++ ) {
					if ( $i < $p_climit ) {
						$active   = ( ( $i + 1 ) === 1 ) ? 'active' : '';
						$btn_num .= '<button class="tpgb-pagelink tpgb-ajax-page ' . esc_attr( $active ) . '" data-page="' . esc_attr( $i + 1 ) . '" >' . esc_html( $i + 1 ) . '</button>';
					} else {
						$active   = ( ( $i + 1 ) === 1 ) ? 'active' : '';
						$btn_num .= '<button class="tpgb-pagelink tpgb-ajax-page tp-hide ' . esc_attr( $active ) . '" data-page="' . esc_attr( $i + 1 ) . '" >' . esc_html( $i + 1 ) . '</button>';
					}
				}
			}
		} else {
			for ( $i = 0; $i < $response['columns']; $i++ ) {
				$active   = ( ( $i + 1 ) === 1 ) ? 'active' : '';
				$btn_num .= '<button class="tpgb-pagelink tpgb-ajax-page tp-hide ' . esc_attr( $active ) . '" data-page="' . esc_attr( $i + 1 ) . '" >' . esc_html( $i + 1 ) . '</button>';
			}
		}
		if ( ! empty( $p_navigation ) ) {
			$prev     .= '<button class="tpgb-pagelink next" data-next="1">';
				$prev .= ! empty( $p_nxttxt ) ? '<span class="tpgb-next-txt">' . esc_html( $p_nxttxt ) . '</span>' : '';
				$prev .= ( 'fontAwesome' === $p_nxticon_type && ! empty( $p_nxticon ) ) ? '<span class="tpgb-next-icon"> <i class="' . esc_attr( $p_nxticon ) . ' tpgb-title-icon"></i> </span>' : '';
				$prev .= '</button>';
		}
		if ( 'after' === $pstyle ) {
			$response['pagination'] .= $next . $prev . $btn_num;
		} elseif ( 'center' === $pstyle ) {
			$response['pagination'] .= $next . $btn_num . $prev;
		} elseif ( 'before' === $pstyle ) {
			$response['pagination'] .= $btn_num . $next . $prev;
		}
	} elseif ( 'load_more' === $pagestyle ) {
		$btn_txt              = ! empty( $result_data['loadbtntxt'] ) ? $result_data['loadbtntxt'] : 0;
		$response['loadmore'] = ( $total_find > $postper ) ? '<a class="post-load-more" data-page="1" >' . esc_html( $btn_txt ) . '</a>' : '';
		$load_page            = ! empty( $result_data['loadpage'] ) ? $result_data['loadpage'] : 0;
		if ( ! empty( $load_page ) ) {
			$page_html  = '';
			$pagetxt    = ! empty( $result_data['loadPagetxt'] ) ? $result_data['loadPagetxt'] : '';
			$loadnumber = ! empty( $result_data['loadnumber'] ) ? $result_data['loadnumber'] : $postper;
			// $numbcount = ceil($total_find / $loadnumber); // phpcs:ignore Squiz.PHP.CommentedOutCode.Found
			if ( 1 === $total_find ) {
				$numbcount = 1;
			} else {
				$numbcount = ceil( ( $total_find - $postper ) / $loadnumber ) + 1;
			}

			$page_html .= '<span class="tpgb-page-link" >' . esc_html( $pagetxt ) . '</span>';
			$page_html .= '<button class="tpgb-pagelink tpgb-load-page" data-page="1" ><span class="tpgb-load-number" > 1 </span> / ' . esc_html( abs( $numbcount ) ) . ' </button>';

			$response['loadmore_page'] = $page_html;
		}
	} elseif ( 'lazy_load' === $pagestyle ) {
		$response['lazymore'] = '<a class="post-lazy-load" data-page="1"><div class="tpgb-spin-ring"><div></div><div></div><div></div><div></div></div></a>';
	}

	$ci = 0;
	if ( 'custom' === $style ) {
		if ( ! empty( $temp_id ) ) {
			if ( $seaposts->have_posts() ) {
				while ( $seaposts->have_posts() ) {
					ob_start();
					$seaposts->the_post();
					echo '<div class="tpgb-ser-item tpgb-trans-linear ' . esc_attr( $style_column ) . '">';
                        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Output is escaped inside plus_do_block().
						echo Tpgb_Library()->plus_do_block( $temp_id );
					echo '</div>';

					$search_post_op = ob_get_contents();
					ob_end_clean();
					$response['posts'][ $ci ] = $search_post_op;
					++$ci;
				}
			}
		} else {
			$search_reus_error      = '<div class="tpgb-ser-item tpgb-trans-linear ' . esc_attr( $style_column ) . '">';
				$search_reus_error .= 'You have ' . esc_html( $total_find ) . ' result(s) but select reusable block for layout';
			$search_reus_error     .= '</div>';

			$response['posts'][ $ci ] = $search_reus_error;
		}
	} else {
		foreach ( $seaposts->posts as $key => $post ) {
			$product = '';
			if ( 'product' === $post_type ) {
				$product = wc_get_product( $post->ID );
			}

			$url = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ), 'thumbnail' );

			$post_title         = ! empty( $post ) ? $post->post_title : '';
			$post_link          = ! empty( $post ) ? get_permalink( $post ) : '';
			$post_content       = ! empty( $post ) ? $post->post_excerpt : '';
			$post_thumb         = $url;
			$post_type          = $post_type;
			$post_wo_price      = ! empty( $product ) ? $product->get_price_html() : '';
			$post_wo_short_desc = ! empty( $product ) ? $product->get_short_description() : '';

			$link_enale       = ( $result_setting && $result_setting['ResultlinkOn'] ) ? $result_setting['ResultlinkOn'] : '';
			$resultlinktarget = ( $link_enale && $result_setting && $result_setting['Resultlinktarget'] ) ? 'target="' . esc_attr( $result_setting['Resultlinktarget'] ) . '"' : '';
			$resultlink       = ( $link_enale && $post_link ) ? 'href="' . esc_url( $post_link ) . '"' : '';

			if ( ! empty( $result_setting['TxtTitle'] ) ) {
				$txt_count = ( ! empty( $result_setting['textcount'] ) ) ? $result_setting['textcount'] : 100;
				$txtdot    = ( ! empty( $result_setting['textdots'] ) ) ? $result_setting['textdots'] : '';

				if ( 'char' === $result_setting['texttype'] ) {
					$ttl_dots = '';
					if ( strlen( $post_title ) > $txt_count ) {
						$ttl_dots = '...';
					}
					$post_title = substr( $post_title, 0, $txt_count ) . $ttl_dots;
				} elseif ( 'word' === $result_setting['texttype'] ) {
					$ttl_dots = '';
					if ( str_word_count( $post_title ) > $txt_count ) {
						$ttl_dots = '...';
					}
					$words      = explode( ' ', $post_title );
					$post_title = implode( ' ', array_splice( $words, 0, $txt_count ) ) . $ttl_dots;
				}
			}

			if ( ! empty( $result_setting['Txtcont'] ) ) {
				$contcount = ( ! empty( $result_setting['ContCount'] ) ) ? $result_setting['ContCount'] : 100;
				$txtdotc   = ( ! empty( $result_setting['ContDots'] ) ) ? $result_setting['ContDots'] : '';
				if ( 'char' === $result_setting['ContType'] ) {
					$cnt_dots = '';
					if ( str_word_count( $post_content ) > $contcount ) {
						$cnt_dots = '...';
					}
					$post_content = substr( $post_content, 0, $contcount ) . $cnt_dots;
				} elseif ( 'word' === $result_setting['ContType'] ) {
					$cnt_dots = '';
					if ( str_word_count( $post_content ) > $contcount ) {
						$cnt_dots = '...';
					}
					$words        = explode( ' ', $post_content );
					$post_content = implode( ' ', array_splice( $words, 0, $contcount ) ) . $cnt_dots;
				}
			}

			$search_post_op      = '<div class="tpgb-ser-item tpgb-trans-linear ' . esc_attr( $style_column ) . '">';
				$search_post_op .= '<a class="tpgb-serpost-link tpgb-trans-easeinout" ' . $resultlink . ' ' . $resultlinktarget . ' >';
			if ( ! empty( $result_setting['ONThumb'] ) && ! empty( $post_thumb ) ) {
				$search_post_op     .= '<div class="tpgb-serpost-thumb">';
					$search_post_op .= '<img class="tpgb-item-image" src=' . esc_url( $post_thumb ) . ' alt="' . esc_attr__( 'Thumb Image', 'the-plus-addons-for-block-editor' ) . '">';
				$search_post_op     .= '</div>';
			}
					$search_post_op .= '<div class="tpgb-serpost-wrap">';
			if ( ( ! empty( $result_setting['ONTitle'] ) && ! empty( $post_title ) ) || ( ! empty( $result_setting['ONPrice'] ) && ! empty( $post_wo_price ) ) ) {
				$search_post_op .= '<div class="tpgb-serpost-inner-wrap">';
				if ( ! empty( $result_setting['ONTitle'] ) && ! empty( $post_title ) ) {
							$search_post_op .= '<div class="tpgb-serpost-title">' . wp_kses_post( $post_title ) . '</div>';
				}
				if ( ! empty( $result_setting['ONPrice'] ) && ! empty( $post_wo_price ) ) {
								$search_post_op .= '<div class="tpgb-serpost-price">' . wp_kses_post( $post_wo_price ) . '</div>';
				}
										$search_post_op .= '</div>';
			}
			if ( ! empty( $result_setting['ONContent'] ) && ! empty( $post_content ) ) {
				$search_post_op .= '<div class="tpgb-serpost-excerpt">' . wp_kses_post( $post_content ) . '</div>';
			}
			if ( ! empty( $result_setting['ONShortDesc'] ) && ! empty( $post_wo_short_desc ) ) {
				$search_post_op .= '<div class="tpgb-serpost-shortDesc">' . wp_kses_post( $post_wo_short_desc ) . '</div>';
			}
					$search_post_op .= '</div>';
				$search_post_op     .= '</a>';
			$search_post_op         .= '</div>';

			$response['posts'][ $key ] = $search_post_op;
		}
	}

	if ( ! empty( $on_load_attr ) ) {
		return $response;
	} else {
		wp_reset_postdata();
		wp_send_json_success( $response );
	}
}
add_action( 'wp_ajax_tpgb_search', 'tpgb_search' );
add_action( 'wp_ajax_nopriv_tpgb_search', 'tpgb_search' );

// Dynamic Select Down.
/**
 * Tpgb search drop down.
 *
 * @param array $data The data.
 * @param mixed $name The name.
 * @param int   $id The id.
 * @param mixed $taxo The taxo.
 * @param mixed $repeater The repeater.
 * @param mixed $input_dis The input dis.
 * @param array $exclude_term_array The exclude term array.
 * @param array $include_term_array The include term array.
 * @return mixed The result.
 */
function tpgb_search_drop_down( $data, $name, $id, $taxo, $repeater, $input_dis, $exclude_term_array = array(), $include_term_array = array() ) {
	$select        = '';
	$show_cnt      = ! empty( $repeater['showCount'] ) ? 'yes' : 'no';
	$label         = ! empty( $repeater['fieldLabel'] ) ? $repeater['fieldLabel'] : '';
	$place_h       = ! empty( $repeater['fieldPlaceH'] ) ? $repeater['fieldPlaceH'] : '';
	$ph_all_result = ! empty( $repeater['phAllResult'] ) ? $repeater['phAllResult'] : false;
	$source_type   = ! empty( $repeater['sourceType'] ) ? $repeater['sourceType'] : '';

	// if($taxo != ''){ // phpcs:ignore Squiz.PHP.CommentedOutCode.Found
	// $select .= '<input name="taxonomy" type="hidden" value="'.esc_attr($taxo).'">';.
	// } // phpcs:ignore Squiz.Commenting.InlineComment.InvalidEndChar
	if ( ! empty( $label ) ) {
		$select .= '<label class="tpgb-search-label tpgb-trans-linear">' . esc_html( $label ) . '</label>';
	}

	$dat_name = '';
	$ex_terms = '';
	if ( 'post' === $name ) {
		$dat_name = 'post_type';
	} elseif ( 'category' === $name ) {
		$dat_name      = 'taxonomy_' . esc_attr( $taxo );
		$negated_array = array();
		if ( ! empty( $exclude_term_array ) ) {
			$negated_array = array_map(
				function ( $value ) {
					return '-' . $value;
				},
				$exclude_term_array
			);
		}
		if ( ! empty( $negated_array ) && ! empty( $include_term_array ) ) {
			$array_merge_ex_in = array_merge( $negated_array, $include_term_array );
			$ex_terms          = implode( ',', $array_merge_ex_in );
		} elseif ( ! empty( $include_term_array ) ) {
			$ex_terms = implode( ',', $include_term_array );
		}
	}

	$select_loader = '';
	if ( ! empty( $input_dis ) ) {
		$select_loader = '<div class="tpgb-ajx-loading"><div class="tpgb-spinner-loader"></div></div><span class="tpgb-close-btn"><i class="fas fa-times-circle" aria-hidden="true"></i></span>';
	}
	$all_res_id = ( ! empty( $ph_all_result ) && 'taxonomy' === $source_type ) ? 'all' : '';

	$select         .= '<div class="tpgb-sbar-dropdown">';
		$select     .= '<div class="tpgb-select">';
			$select .= '<span class="search-selected-text">' . esc_html( $place_h ) . '</span><span class="tpgb-dd-icon tpgb-trans-easeinout"><i class="fas fa-chevron-down"></i></span>';
			$select .= $select_loader;
		$select     .= '</div>';
		$select     .= '<input type="hidden" name="' . esc_attr( $dat_name ) . '" id="' . esc_attr( $dat_name ) . '" value="' . esc_attr( $ex_terms ) . '" data-extra="' . esc_attr( $ex_terms ) . '">';
		$select     .= '<ul class="tpgb-sbar-dropdown-menu">';
			$select .= '<li id="' . esc_attr( $all_res_id ) . '" class="tpgb-searchbar-li">' . esc_html( $place_h ) . '</li>';
	foreach ( $data as $key => $label ) {
		$l_name = ! empty( $label['name'] ) ? $label['name'] : '';
		$lcount = ! empty( $label['count'] ) ? $label['count'] : 0;

		if ( ! in_array( $key, $exclude_term_array, true ) ) {
			$select .= '<li id="' . esc_attr( $key ) . '" class="tpgb-searchbar-li" >';

			if ( 'yes' === $show_cnt ) {
					$select .= esc_html( $l_name ) . ' (' . esc_html( $lcount ) . ')';
			} else {
						$select .= esc_html( $l_name );
			}

							$select .= '</li>';
		}
	}
		$select .= '</ul>';
	$select     .= '</div>';

	return $select;
}


/**
 * Tpgb custom search filter.
 *
 * @param mixed $query The query.
 * @return mixed The result.
 */
function tpgb_custom_search_filter( $query ) {
	if ( $query->is_search && ! is_admin() && isset( $_GET ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$tax_query = array();
		$post_type = '';
		if ( isset( $_GET['post_type'] ) && ! empty( $_GET['post_type'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$post_type = sanitize_text_field( $_GET['post_type'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput,WordPress.Security.NonceVerification.Missing,WordPress.Security.NonceVerification.Recommended
		}
		foreach ( $_GET as $key => $value ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$sanitized_key = sanitize_text_field( $key );
			if ( strpos( $sanitized_key, 'taxonomy_' ) !== false ) {
				$modified_key = str_replace( 'taxonomy_', '', $sanitized_key );
				if ( isset( $value ) && ! empty( $value ) ) {
					$sanitized_value = is_array( $value ) ? array_map( 'sanitize_text_field', $value ) : sanitize_text_field( $value );
					$tax_query[]     = array(
						'taxonomy' => $modified_key,
						'field'    => 'term_id',
						'terms'    => $sanitized_value,
					);
				}
				$taxonomy = get_taxonomy( $modified_key );
				if ( $taxonomy && ! empty( $taxonomy->object_type ) ) {
					$post_types = $taxonomy->object_type;
					$post_type  = $post_types[0];
				} elseif ( ! empty( $_GET['post_type'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
					$post_type = sanitize_text_field( $_GET['post_type'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput,WordPress.Security.NonceVerification.Missing,WordPress.Security.NonceVerification.Recommended
				} else {
					$post_type = 'any';
				}
			}
		}

		if ( ! empty( $tax_query ) ) {
			$query->set(
				'tax_query',
				array(
					'relation' => 'AND',
					$tax_query,
				)
			);
		}
		if ( ! empty( $post_type ) ) {
			$query->set( 'post_type', $post_type );
		}
	}
	return $query;
}
if ( ! is_admin() ) {
	add_action( 'pre_get_posts', 'tpgb_custom_search_filter' );
}
