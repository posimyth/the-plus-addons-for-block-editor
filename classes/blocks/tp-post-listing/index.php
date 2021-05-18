<?php
/* Tp Block : Post Listing
 * @since	: 1.1.0
 */
function tpgb_tp_post_listing_render_callback( $attributes ) {
	$output = '';
	$query_args = tpgb_post_query($attributes);
	$query = new \WP_Query( $query_args );

	$block_id	= isset($attributes['block_id']) ? $attributes['block_id'] : '';
	$postType = isset($attributes['postType']) ? $attributes['postType'] : '';
	$style		= isset($attributes['style']) ? $attributes['style'] : 'style-1';
	$layout		= isset($attributes['layout']) ? $attributes['layout'] : 'grid';
	$style2Alignment	= isset($attributes['style2Alignment']) ? $attributes['style2Alignment'] : 'center';
	$styleLayout		= isset($attributes['styleLayout']) ? $attributes['styleLayout'] : 'style-1';
	
	$imageHoverStyle	= isset($attributes['imageHoverStyle']) ? 'hover-image-'.$attributes['imageHoverStyle'] : 'hover-image-style-1';
	//Title
	$ShowTitle	= !empty($attributes['ShowTitle']) ? 'yes' : '';
	$titleTag	= isset($attributes['titleTag']) ? $attributes['titleTag'] : 'h3';
	$titleByLimit = isset($attributes['titleByLimit']) ? $attributes['titleByLimit'] : 'default';
	
	//Excerpt
	$showExcerpt	= !empty($attributes['ShowExcerpt']) ? 'yes' : '';
	$excerptByLimit	= isset($attributes['excerptByLimit']) ? $attributes['excerptByLimit'] : 'default';
	$excerptLimit	= isset($attributes['excerptLimit']) ? $attributes['excerptLimit'] : 30;
	
	$showPostMeta	= !empty($attributes['ShowPostMeta']) ? 'yes' : '';
	$postMetaStyle	= isset($attributes['postMetaStyle']) ? $attributes['postMetaStyle'] : 'style-1';
	$ShowDate = !empty($attributes['ShowDate']) ? 'yes' : '';
	$ShowAuthor = !empty($attributes['ShowAuthor']) ? 'yes' : '';
	$ShowAuthorImg = !empty($attributes['ShowAuthorImg']) ? 'yes' : '';
	$taxonomySlug	= !empty($attributes['taxonomySlug']) ? $attributes['taxonomySlug'] : 'category';

	$postListing = isset($attributes['postListing']) ? $attributes['postListing'] : '';

	$showPostCategory	= !empty($attributes['showPostCategory']) ? 'yes' : '';
	$postCategoryStyle	= isset($attributes['postCategoryStyle']) ? $attributes['postCategoryStyle'] : 'style-1';
	$postCategory =  isset($attributes['postCategory']) ? $attributes['postCategory'] : '';
	$postTag =  isset($attributes['postTag']) ? $attributes['postTag'] : '';
	$excludeCategory =  isset($attributes['excludeCategory']) ? $attributes['excludeCategory'] : '';
	$excludeTag =  isset($attributes['excludeTag']) ? $attributes['excludeTag'] : '';
	
	$displayPosts		= isset($attributes['displayPosts']) ? $attributes['displayPosts'] : 8;
	$offsetPosts		= isset($attributes['offsetPosts']) ? $attributes['offsetPosts'] : 0;
	$orderBy		= isset($attributes['orderBy']) ? $attributes['orderBy'] : 'date';
	$order		= isset($attributes['order']) ? $attributes['order'] : 'desc';
	$postLodop = isset($attributes['postLodop']) ? $attributes['postLodop'] : '';
	$className = (!empty($attributes['className'])) ? $attributes['className'] :'';
	$align = (!empty($attributes['align'])) ? $attributes['align'] :'';
	
	$blockClass = '';
	if(!empty($className)){
		$blockClass .= $className;
	}
	if(!empty($align)){
		$blockClass .= ' align'.$align;
	}

	//Columns
	$column_class = '';
	if($style!='carousel' && !empty($attributes['columns']) && is_array($attributes['columns'])){
		$column_class .= ' tpgb-col-12';
		$column_class .= isset($attributes['columns']['md']) ? " tpgb-col-lg-".$attributes['columns']['md'] : 'tpgb-col-lg-3';
		$column_class .= isset($attributes['columns']['sm']) ? " tpgb-col-md-".$attributes['columns']['sm'] : 'tpgb-col-md-4';
		$column_class .= isset($attributes['columns']['xs']) ? " tpgb-col-sm-".$attributes['columns']['xs'] : 'tpgb-col-sm-6';
		$column_class .= isset($attributes['columns']['xs']) ? " tpgb-col-xs-".$attributes['columns']['xs'] : 'tpgb-col-xs-6';
	}
	
	//Classes
	$list_style = ($style) ? 'dynamic-'.esc_attr($style) : 'dynamic-style-1';
	
	$list_layout = '';
	if($layout=='grid' || $layout=='masonry'){
		$list_layout = 'tpgb-isotope';
	}else{
		$list_layout = 'tpgb-isotope';
	}
	
	$styleLayoutclass ='';
	if(($style=='style-2') && $styleLayout){
		$styleLayoutclass .= 'layout-'.$styleLayout;
	}

	$classattr = '';
	$classattr .= ' tpgb-block-'.$block_id;
	$classattr .= ' '.$list_style;
	$classattr .= ' '.$list_layout;
	$classattr .= ' '.$styleLayoutclass;

	if($query->found_posts !=''){
		$total_posts=$query->found_posts;
		$post_offset = ($offsetPosts!='') ? $offsetPosts : 0;
		$display_posts = ($displayPosts!='') ? $displayPosts : 0;
		$offset_posts=intval($display_posts + $post_offset);
		$total_posts= intval($total_posts - $offset_posts);	
		
		$load_page=1;
		
		$load_page=$load_page+1;
	}else{
		$load_page=1;
	}

	if ( ! $query->have_posts() ) {
		$output .='<h3 class="tpgb-no-posts-found">'.esc_html__( "No Posts found", "tpgb" ).'</h3>';
	}else{
		$output .= '<div id="'.esc_attr($block_id).'" class="tpgb-post-listing '.esc_attr($blockClass).' '.esc_attr($classattr).' " data-id="'.esc_attr($block_id).'" data-style="'.esc_attr($list_style).'"  data-layout="'.esc_attr($layout).'"  data-connection="tpgb_search"  >';
			
			$output .= '<div id="tpgb_list" class="tpgb-row post-loop-inner" >';
				while ( $query->have_posts() ) {
					
					$query->the_post();
					$post = $query->post;
					
					$output .= '<div class="grid-item tpgb-col '.esc_attr($column_class).' ">';
					if(!empty($style) && $style!=='custom'){
						ob_start();
						include TPGB_PATH. 'includes/blog/blog-'.esc_attr($style).'.php'; 
						$output .= ob_get_contents();
						ob_end_clean();
					}else if($style=='custom' && $attributes['blockTemplate']!=''){
						ob_start();
							echo Tpgb_Library()->plus_do_block($attributes['blockTemplate']);
						$output .= ob_get_contents();
						ob_end_clean();
					}
					$output .= '</div>';
				}
			$output .= '</div>';

			if($postLodop=='pagination' && $layout!='carousel'){
				$output .= tpgb_pagination($query->max_num_pages,'2');
			}
		$output .= "</div>";
	}

	$output = Tpgb_Blocks_Global_Options::block_Wrap_Render($attributes, $output);
	wp_reset_postdata();
    return $output;
}

/**
 * Render for the server-side
 */
function tpgb_tp_post_listing() {
	$globalBgOption = Tpgb_Blocks_Global_Options::load_bg_options();
	$globalpositioningOption = Tpgb_Blocks_Global_Options::load_positioning_options();
	$globalPlusExtrasOption = Tpgb_Blocks_Global_Options::load_plusextras_options();

	$attributesOptions = [
			'block_id' => [
                'type' => 'string',
				'default' => '',
			],
			'postListing' => [
				'type' => 'string',
				'default' => 'page_listing',
			],
			'relatedPost' => [
				'type' => 'string',
				'default' => 'category',
			],
			'postType' => [
				'type' => 'string',
				'default' => 'post',
			],
			'style' => [
				'type' => 'string',
				'default' => 'style-1',
			],
			'blockTemplate' => [
				'type' => 'string',
				'default' => '',
			],
			'layout' => [
				'type' => 'string',
				'default' => 'grid',
			],
			'style2Alignment' => [
				'type' => 'string',		
				'default' => 'center', 	 
			],
			'styleLayout' => [
				'type' => 'string',
				'default' => 'style-1',
			],
			
			'postCategory' => [
				'type' => 'string',
        		'default' => '[]',
			],
			'postTag' => [
				'type' => 'string',
        		'default' => '[]',
			],
			'taxonomySlug' => [
				'type' => 'string',
				'default' => '',
			],
			'includePosts' => [
				'type' => 'string',
				'default' => '',
			],
			'excludePosts' => [
				'type' => 'string',
				'default' => '',
			],
			'displayPosts' => [
				'type' => 'string',
				'default' => 8,
			],
			'offsetPosts' => [
				'type' => 'string',
				'default' => 0,
			],
			'orderBy' => [
				'type' => 'string',
				'default' => 'date',
			],
			'order' => [
				'type' => 'string',
				'default' => 'desc',
			],
			
			'columns' => [
				'type' => 'object',
				'default' => [ 'md' => 3,'sm' => 4,'xs' => 6 ],
			],
			'columnSpace' => [
				'type' => 'object',
				'default' => (object) [ 
					'md' => [
						"top" => 15,
						"right" => 15,
						"bottom" => 15,
						"left" => 15,
					],
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'layout', 'relation' => '!=', 'value' => 'carousel']],
						'selector' => '{{PLUS_WRAP}}.tpgb-post-listing .grid-item{padding: {{columnSpace}};}',
					],
				],
			],
			'ShowFilter' => [
				'type' => 'boolean',
				'default' => false,
			],
			'catfilterId' => [
				'type' => 'string',
				'default' => '',
			],
			'ShowTitle' => [
				'type' => 'boolean',
				'default' => true,
			],
			
			'titleTag' => [
				'type'=> 'string',
				'default'=> 'h3',
			],
			'titleByLimit' => [
				'type' => 'string',
				'default' => 'default',
			],
			'Showdot' => [
				'type' => 'boolean',
				'default' => false,
			],
			'titleTypo' => [
				'type' => 'object',
				'default' => (object) [
					'openTypography' => 0,
					'size' => [ 'md' => 20, 'unit' => 'px' ],
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'ShowTitle', 'relation' => '==', 'value' => true ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-post-listing .tpgb-post-title',
					],
				],
			],
			
			'titleNormalColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'ShowTitle', 'relation' => '==', 'value' => true ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-post-listing .tpgb-post-title a{color: {{titleNormalColor}};}',
					],
				],
			],
			'titleHoverColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'ShowTitle', 'relation' => '==', 'value' => true ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-post-listing .dynamic-list-content:hover .tpgb-post-title a{color: {{titleHoverColor}};}',
					],
				],
			],
			
			'ShowExcerpt' => [
				'type' => 'boolean',
				'default' => true,
			],
			
			'excerptByLimit' => [
				'type' => 'string',
				'default' => 'default',
			],
			'excerptLimit' => [
				'type' => 'string',
				'default' => 30,
			],
			'excerptTypo' => [
				'type' => 'object',
				'default' => (object) [
					'openTypography' => 0,
					'size' => [ 'md' => 14, 'unit' => 'px' ],
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'ShowExcerpt', 'relation' => '==', 'value' => true ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-post-listing .tpgb-post-excerpt,{{PLUS_WRAP}}.tpgb-post-listing .tpgb-post-excerpt p',
					],
				],
			],
			
			'excerptNormalColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'ShowExcerpt', 'relation' => '==', 'value' => true ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-post-listing .tpgb-post-excerpt,{{PLUS_WRAP}}.tpgb-post-listing .tpgb-post-excerpt p{color: {{excerptNormalColor}};}',
					],
				],
			],
			'excerptHoverColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'ShowExcerpt', 'relation' => '==', 'value' => true ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-post-listing .dynamic-list-content:hover .tpgb-post-excerpt,{{PLUS_WRAP}}.tpgb-post-listing .dynamic-list-content:hover .tpgb-post-excerpt p{color: {{excerptHoverColor}};}',
					],
				],
			],
			'ShowPostMeta' => [
				'type' => 'boolean',
				'default' => true,
			],
			'ShowDate' => [
				'type' => 'boolean',
				'default' => true,
			],
			'ShowAuthor' => [
				'type' => 'boolean',
				'default' => true,
			],
			'ShowAuthorImg' => [
				'type' => 'boolean',
				'default' => true,
			],
			'postMetaStyle' => [
				'type' => 'string',
				'default' => 'style-1',
			],
			'postMetaTypo' => [
				'type' => 'object',
				'default' => (object) [
					'openTypography' => 0,
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'ShowPostMeta', 'relation' => '==', 'value' => true ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-post-listing .post-meta-info > span,{{PLUS_WRAP}}.tpgb-post-listing .post-meta-info > span > a,{{PLUS_WRAP}}.tpgb-post-listing .post-meta-info .post-author-date > a',
					],
				],
			],
			'postMetaNormalColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'ShowPostMeta', 'relation' => '==', 'value' => true ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-post-listing .post-meta-info > span,{{PLUS_WRAP}}.tpgb-post-listing .post-meta-info > span > a,{{PLUS_WRAP}}.tpgb-post-listing .post-meta-info .post-author-date > a{color: {{postMetaNormalColor}};}',
					],
				],
			],
			'postMetaHoverColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'ShowPostMeta', 'relation' => '==', 'value' => true ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-post-listing .dynamic-list-content:hover .post-meta-info > span,{{PLUS_WRAP}}.tpgb-post-listing .dynamic-list-content:hover .post-meta-info > span > a,{{PLUS_WRAP}}.tpgb-post-listing .dynamic-list-content:hover .post-meta-info .post-author-date > a{color: {{postMetaHoverColor}};}',
					],
				],
			],
			'postLodop' => [
				'type' => 'string',
				'default' => 'none',
			],
			'showPostCategory' => [
				'type' => 'boolean',
				'default' => true,
			],
			'postCategoryStyle' => [
				'type' => 'string',
				'default' => 'style-1',
			],
			'postCategoryTypo' => [
				'type' => 'object',
				'default' => (object) [
					'openTypography' => 0,
				],
				'style' => [
					(object) [
						'condition' => [
							(object) ['key' => 'showPostCategory', 'relation' => '==', 'value' => true]
						],
						'selector' => '{{PLUS_WRAP}}.tpgb-post-listing .tpgb-post-category > a',
					],
				],
			],
			'postCategoryColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [
							(object) ['key' => 'showPostCategory', 'relation' => '==', 'value' => true]
						],
						'selector' => '{{PLUS_WRAP}}.tpgb-post-listing .tpgb-post-category > a{color: {{postCategoryColor}};}',
					],
				],
			],
			'postCategoryHoverColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [
							(object) ['key' => 'showPostCategory', 'relation' => '==', 'value' => true]
						],
						'selector' => '{{PLUS_WRAP}}.tpgb-post-listing .tpgb-post-category > a:hover{color: {{postCategoryHoverColor}};}',
					],
				],
			],
			'catBorder' => [
				'type' => 'object',
				'default' => (object) [
					'openBorder' => 0,
				],
				'style' => [
					(object) [
						'condition' => [
							(object) ['key' => 'showPostCategory', 'relation' => '==', 'value' => true],
							(object) ['key' => 'postCategoryStyle', 'relation' => '==', 'value' => 'style-1']
						],
						'selector' => '{{PLUS_WRAP}}.tpgb-post-listing .tpgb-post-category.cat-style-1 > a',
					],
				],
			],
			'catBorderHover' => [
				'type' => 'object',
				'default' => (object) [
					'openBorder' => 0,
					'width' => (object) [
						'md' => (object)[
							'top' => '',
							'left' => '',
							'bottom' => '',
							'right' => '',
						],
					],
				],
				'style' => [
					(object) [
						'condition' => [
							(object) ['key' => 'showPostCategory', 'relation' => '==', 'value' => true],
							(object) ['key' => 'postCategoryStyle', 'relation' => '==', 'value' => 'style-1']
						],
						'selector' => '{{PLUS_WRAP}}.tpgb-post-listing .tpgb-post-category.cat-style-1 > a:hover',
					],
				],
			],
			'cat2BorderHover' => [
				'type' => 'string',
				'default' => '' ,
				'style' => [
					(object) [
						'condition' => [
							(object) ['key' => 'showPostCategory', 'relation' => '==', 'value' => true],
							(object) ['key' => 'postCategoryStyle', 'relation' => '==', 'value' => 'style-2']
						],
						'selector' => '{{PLUS_WRAP}} .tpgb-post-category.cat-style-2 > a:before{ background : {{cat2BorderHover}} }',
					],
				],
			],
			'catRadius' => [
				'type' => 'object',
				'default' => (object) [ 
					'md' => [
						"top" => '',
						"right" => '',
						"bottom" => '',
						"left" => '',
					],
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'condition' => [
							(object) ['key' => 'showPostCategory', 'relation' => '==', 'value' => true],
							(object) ['key' => 'postCategoryStyle', 'relation' => '==', 'value' => 'style-1']
						],
						'selector' => '{{PLUS_WRAP}}.tpgb-post-listing .tpgb-post-category.cat-style-1 > a{border-radius: {{catRadius}};}',
					],
				],
			],
			'catRadiusHover' => [
				'type' => 'object',
				'default' => (object) [ 
					'md' => [
						"top" => '',
						"right" => '',
						"bottom" => '',
						"left" => '',
					],
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'condition' => [
							(object) ['key' => 'showPostCategory', 'relation' => '==', 'value' => true],
							(object) ['key' => 'postCategoryStyle', 'relation' => '==', 'value' => 'style-1']
						],
						'selector' => '{{PLUS_WRAP}}.tpgb-post-listing .tpgb-post-category.cat-style-1 > a:hover{border-radius: {{catRadiusHover}};}',
					],
				],
			],
			'catBg' => [
				'type' => 'object',
				'default' => (object) [
					'bgType' => 'color',
					'bgDefaultColor' => '',
					'bgGradient' => (object) [
						"direction" => 90,
					],
				],
				'style' => [
					(object) [
						'condition' => [
							(object) ['key' => 'showPostCategory', 'relation' => '==', 'value' => true],
							(object) ['key' => 'postCategoryStyle', 'relation' => '==', 'value' => 'style-1']
						],
						'selector' => '{{PLUS_WRAP}}.tpgb-post-listing .tpgb-post-category.cat-style-1 > a',
					],
				],
			],
			'catBgHover' => [
				'type' => 'object',
				'default' => (object) [
					'bgType' => 'color',
					'bgDefaultColor' => '',
					'bgGradient' => (object) [
						"direction" => 90,
					],
				],
				'style' => [
					(object) [
						'condition' => [
							(object) ['key' => 'showPostCategory', 'relation' => '==', 'value' => true],
							(object) ['key' => 'postCategoryStyle', 'relation' => '==', 'value' => 'style-1']
						],
						'selector' => '{{PLUS_WRAP}}.tpgb-post-listing .tpgb-post-category.cat-style-1 > a:hover',
					],
				],
			],
			'catBoxShadow' => [
				'type' => 'object',
				'default' => (object) [
					'openShadow' => 0,
					'blur' => 8,
					'color' => "rgba(0,0,0,0.40)",
					'horizontal' => 0,
					'inset' => 0,
					'spread' => 0,
					'vertical' => 4
				],
				'style' => [
					(object) [
						'condition' => [
							(object) ['key' => 'showPostCategory', 'relation' => '==', 'value' => true],
							(object) ['key' => 'postCategoryStyle', 'relation' => '==', 'value' => 'style-1']
						],
						'selector' => '{{PLUS_WRAP}}.tpgb-post-listing .tpgb-post-category.cat-style-1 > a',
					],
				],
			],
			'catBoxShadowHover' => [
				'type' => 'object',
				'default' => (object) [
					'openShadow' => 0,
					'blur' => 8,
					'color' => "rgba(0,0,0,0.40)",
					'horizontal' => 0,
					'inset' => 0,
					'spread' => 0,
					'vertical' => 4
				],
				'style' => [
					(object) [
						'condition' => [
							(object) ['key' => 'showPostCategory', 'relation' => '==', 'value' => true],
							(object) ['key' => 'postCategoryStyle', 'relation' => '==', 'value' => 'style-1']
						],
						'selector' => '{{PLUS_WRAP}}.tpgb-post-listing .tpgb-post-category.cat-style-1 > a:hover',
					],
				],
			],
			'contentBg' => [
				'type' => 'object',
				'default' => (object) [
					'bgType' => 'color',
					'bgDefaultColor' => '',
					'bgGradient' => (object) [
						"direction" => 90,
					],
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-post-listing.dynamic-style-1 .dynamic-list-content .tpgb-content-bottom, 
										{{PLUS_WRAP}}.tpgb-post-listing.dynamic-style-2 .dynamic-list-content .tpgb-content-bottom,
										{{PLUS_WRAP}}.tpgb-post-listing.dynamic-style-3 .dynamic-list-content .tpgb-content-bottom',
					],
				],
			],
			'contentBgHover' => [
				'type' => 'object',
				'default' => (object) [
					'bgType' => 'color',
					'bgDefaultColor' => '',
					'bgGradient' => (object) [
						"direction" => 90,
					],
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-post-listing.dynamic-style-1 .dynamic-list-content:hover .tpgb-content-bottom, 
										{{PLUS_WRAP}}.tpgb-post-listing.dynamic-style-2 .dynamic-list-content:hover .tpgb-content-bottom,
										{{PLUS_WRAP}}.tpgb-post-listing.dynamic-style-3 .dynamic-list-content:hover .tpgb-content-bottom',
					],
				],
			],
			'imageHoverStyle' => [
				'type' => 'string',
				'default' => 'style-1',
			],
			'imageOverlayBg' => [
				'type' => 'object',
				'default' => (object) [
					'bgType' => 'color',
					'bgDefaultColor' => '',
					'bgGradient' => (object) [
						"direction" => 90,
					],
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-post-listing .dynamic-list-content .tpgb-post-featured-img > a:before',
					],
				],
			],
			'imageOverlayBgHover' => [
				'type' => 'object',
				'default' => (object) [
					'bgType' => 'color',
					'bgDefaultColor' => '',
					'bgGradient' => (object) [
						"direction" => 90,
					],
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-post-listing .dynamic-list-content:hover .tpgb-post-featured-img > a:before',
					],
				],
			],
			'imgRadius' => [
				'type' => 'object',
				'default' => (object) [ 
					'md' => [
						"top" => '',
						"right" => '',
						"bottom" => '',
						"left" => '',
					],
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'condition' => [
							(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-3']
						],
						'selector' => '{{PLUS_WRAP}}.tpgb-post-listing.dynamic-style-3 .tpgb-post-featured-img{border-radius: {{imgRadius}};}',
					],
					(object) [
						'condition' => [
							(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-2']
						],
						'selector' => '{{PLUS_WRAP}}.tpgb-post-listing.dynamic-style-2 .tpgb-post-featured-img{border-radius: {{imgRadius}};}',
					],
				],
			],
			'imgRadiusHover' => [
				'type' => 'object',
				'default' => (object) [ 
					'md' => [
						"top" => '',
						"right" => '',
						"bottom" => '',
						"left" => '',
					],
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'condition' => [
							(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-3']
						],
						'selector' => '{{PLUS_WRAP}}.tpgb-post-listing.dynamic-style-3 .dynamic-list-content:hover .tpgb-post-featured-img{border-radius: {{imgRadiusHover}};}',
					],
					(object) [
						'condition' => [
							(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-2']
						],
						'selector' => '{{PLUS_WRAP}}.tpgb-post-listing.dynamic-style-2 .dynamic-list-content:hover .tpgb-post-featured-img{border-radius: {{imgRadiusHover}};}',
					],
				],
			],
			'imgBoxShadow' => [
				'type' => 'object',
				'default' => (object) [
					'openShadow' => 0,
					'blur' => 8,
					'color' => "rgba(0,0,0,0.40)",
					'horizontal' => 0,
					'inset' => 0,
					'spread' => 0,
					'vertical' => 4
				],
				'style' => [
					(object) [
						'condition' => [
							(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-3']
						],
						'selector' => '{{PLUS_WRAP}}.tpgb-post-listing.dynamic-style-3 .tpgb-post-featured-img',
					],
					(object) [
						'condition' => [
							(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-2']
						],
						'selector' => '{{PLUS_WRAP}}.tpgb-post-listing.dynamic-style-2 .tpgb-post-featured-img',
					],
				],
			],
			'imgBoxShadowHover' => [
				'type' => 'object',
				'default' => (object) [
					'openShadow' => 0,
					'blur' => 8,
					'color' => "rgba(0,0,0,0.40)",
					'horizontal' => 0,
					'inset' => 0,
					'spread' => 0,
					'vertical' => 4
				],
				'style' => [
					(object) [
						'condition' => [
							(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-3']
						],
						'selector' => '{{PLUS_WRAP}}.tpgb-post-listing.dynamic-style-3 .dynamic-list-content:hover .tpgb-post-featured-img',
					],
					(object) [
						'condition' => [
							(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-2']
						],
						'selector' => '{{PLUS_WRAP}}.tpgb-post-listing.dynamic-style-2 .dynamic-list-content:hover .tpgb-post-featured-img',
					],
				],
			],
			'imgHeight' => [
				'type' => 'object',
				'default' => [ 
					'md' => '',
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'condition' => [
							(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-2']
						],
						'selector' => '{{PLUS_WRAP}}.tpgb-post-listing.dynamic-style-2 .dynamic-list-content .tpgb-post-featured-img img{min-height : {{imgHeight}}; max-height : {{imgHeight}}; }',
					],
				],
			],
			
			'boxPadding' => [
				'type' => 'object',
				'default' => (object) [ 
					'md' => [
						"top" => '',
						"right" => '',
						"bottom" => '',
						"left" => '',
					],
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-post-listing .dynamic-list-content{padding: {{boxPadding}};}',
					],
				],
			],
			'boxBorder' => [
				'type' => 'object',
				'default' => (object) [
					'openBorder' => 0,
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-post-listing .dynamic-list-content',
					],
				],
			],
			'boxBorderHover' => [
				'type' => 'object',
				'default' => (object) [
					'openBorder' => 0,
					'width' => (object) [
						'md' => (object)[
							'top' => '',
							'left' => '',
							'bottom' => '',
							'right' => '',
						],
					],
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-post-listing .dynamic-list-content:hover',
					],
				],
			],
			
			'boxBorderRadius' => [
				'type' => 'object',
				'default' => (object) [ 
					'md' => [
						"top" => '',
						"right" => '',
						"bottom" => '',
						"left" => '',
					],
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-post-listing .dynamic-list-content{border-radius: {{boxBorderRadius}};}',
					],
				],
			],
			'boxBorderRadiusHover' => [
				'type' => 'object',
				'default' => (object) [ 
					'md' => [
						"top" => '',
						"right" => '',
						"bottom" => '',
						"left" => '',
					],
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-post-listing .dynamic-list-content:hover{border-radius: {{boxBorderRadiusHover}};}',
					],
				],
			],
			'boxBg' => [
				'type' => 'object',
				'default' => (object) [
					'bgType' => 'color',
					'bgDefaultColor' => '',
					'bgGradient' => (object) [
						"direction" => 90,
					],
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-post-listing .dynamic-list-content',
					],
				],
			],
			'boxBgHover' => [
				'type' => 'object',
				'default' => (object) [
					'bgType' => 'color',
					'bgDefaultColor' => '',
					'bgGradient' => (object) [
						"direction" => 90,
					],
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-post-listing .dynamic-list-content:hover',
					],
				],
			],
			'boxBoxShadow' => [
				'type' => 'object',
				'default' => (object) [
					'openShadow' => 0,
					'blur' => 8,
					'color' => "rgba(0,0,0,0.40)",
					'horizontal' => 0,
					'inset' => 0,
					'spread' => 0,
					'vertical' => 4
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-post-listing .dynamic-list-content',
					],
				],
			],
			'boxBoxShadowHover' => [
				'type' => 'object',
				'default' => (object) [
					'openShadow' => 0,
					'blur' => 8,
					'color' => "rgba(0,0,0,0.40)",
					'horizontal' => 0,
					'inset' => 0,
					'spread' => 0,
					'vertical' => 4
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-post-listing .dynamic-list-content:hover',
					],
				],
			],
			
			'pagitypo' => [
				'type'=> 'object',
				'default'=> (object) [
					'openTypography' => 0,
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'postLodop', 'relation' => '==', 'value' => 'pagination' ]],
						'selector' => '{{PLUS_WRAP}} .tpgb-pagination a,{{PLUS_WRAP}} .tpgb-pagination span',
					],
				],
			],
			'pagiColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'postLodop', 'relation' => '==', 'value' => 'pagination' ]],
						'selector' => '{{PLUS_WRAP}} .tpgb-pagination a,{{PLUS_WRAP}} .tpgb-pagination span{color : {{pagiColor}}; }',
					],
				],
			],
			'pagihvrColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'postLodop', 'relation' => '==', 'value' => 'pagination' ]],
						'selector' => '{{PLUS_WRAP}} .tpgb-pagination a:hover,{{PLUS_WRAP}} .tpgb-pagination a:focus,{{PLUS_WRAP}} .tpgb-pagination span.current{color : {{pagihvrColor}}; border-bottom-color: {{pagihvrColor}} }',
					],
				],
			],

		];
	
	$attributesOptions = array_merge($attributesOptions,$globalBgOption,$globalpositioningOption,$globalPlusExtrasOption);
	
	register_block_type( 'tpgb/tp-post-listing', [
		'attributes' => $attributesOptions,
		'editor_script' => 'tpgb-block-editor-js',
		'editor_style'  => 'tpgb-block-editor-css',
        'render_callback' => 'tpgb_tp_post_listing_render_callback'
    ] );
}
add_action( 'init', 'tpgb_tp_post_listing' );

function tpgb_post_query($attr){

	$query_args = array(
		'post_type'           => $attr['postType'],
		'post_status'         => 'publish',
		'ignore_sticky_posts' => true,
		'posts_per_page'      => ( $attr['displayPosts'] ) ? intval($attr['displayPosts']) : -1,
		'orderby'      =>  ($attr['orderBy']) ? $attr['orderBy'] : 'date',
		'order'      => ($attr['order']) ? $attr['order'] : 'desc',
	);

	global $paged;
	if ( get_query_var('paged') ) {
		$paged = get_query_var('paged');
	}elseif ( get_query_var('page') ) {
		$paged = get_query_var('page');
	}else {
		$paged = 1;
	}
	$query_args['paged'] = $paged;
	
	
	$offset = !empty( $attr['offsetPosts'] ) ? absint( $attr['offsetPosts'] ) : 0;
	if ( $offset  && $attr['postLodop']!='pagination') {
		$query_args['offset'] = $offset;
	}else if($offset && $attr['postLodop']=='pagination'){
		$page = max( 1, $paged );
		$offset = ( $page - 1 ) * intval( $attr['displayPosts'] ) + $offset;
		$query_args['offset'] = $offset;
	}
	
	if ( '' !== $attr['postCategory'] && $attr['postType'] == 'post' ) {
		if ( is_string($attr['postCategory'] )) {
			$cat_arr = array();
			$attr['postCategory'] = json_decode($attr['postCategory']);
			if (is_array($attr['postCategory']) || is_object($attr['postCategory'])) {
				foreach ($attr['postCategory'] as $value) {
					$cat_arr[] = $value->value;
				}
			}
		}
		$query_args['category__in'] = $cat_arr;
	}
	if ( '' !== $attr['postTag'] && $attr['postType'] == 'post' ) {
		if ( is_string($attr['postTag'] )) {
			$tag_arr = array();
			$attr['postTag'] = json_decode($attr['postTag']);
			if (is_array($attr['postTag']) || is_object($attr['postTag'])) {
				foreach ($attr['postTag'] as $value) {
					$tag_arr[] = $value->value;
				}
			}
		}
		$query_args['tag__in'] = $tag_arr;
	}


	//Archive Posts
	if(!empty($attr["postListing"]) && $attr["postListing"]=='archive_listing'){
		global $wp_query;
		$query_var = $wp_query->query_vars;
		if(isset($query_var['cat'])){
			$query_args['category__in'] = $query_var['cat'];
		}
		if(isset($query_var[$attr["taxonomySlug"]]) && $query!=='post'){		
					
			$query_args['tax_query'] = array(						
			  array(		
				'taxonomy' => $attr["taxonomySlug"],		
				'field' => 'slug',		
				'terms' => $query_var[$attr["taxonomySlug"]],		
			  ),		
			);		
		}
		if(isset($query_var['tag_id'])){
			$query_args['tag__in'] = $query_var['tag_id'];
		}
		if(isset($query_var["author"])){
			$query_args['author'] = $query_var["author"];
		}
		if(is_search()){
			$search = get_query_var('s');
			$query_args['s'] = $search;
			$query_args['exact'] = false;
		}
	}

	//Related Posts
	if(!empty($attr["postListing"]) && $attr["postListing"]=='related_post'){
		global $post;
		
		if($post->post_type =='post'){
			$tag_slug = 'term_id';
			$tags = wp_get_post_tags($post->ID);
		}else{
			$tag_slug = 'slug';
			$tags = wp_get_post_terms($post->ID,$attr['taxonomySlug']);
		}
		if ($tags && !empty($attr["postListing"]) && ($attr["relatedPost"]=='both' || $attr["relatedPost"]=='tags')) {	
			$tag_ids = array();
			
			foreach($tags as $individual_tag) $tag_ids[] = $individual_tag->$tag_slug;
			
			$query_args['post__not_in'] = array($post->ID);
			 if($post->post_type =='post'){
				$query_args['tag__in'] = $tag_ids;
			}else{
				$query_args['tax_query'] = array(						
				  array(		
					'taxonomy' => $attr['taxonomySlug'],		
					'field' => 'slug',		
					'terms' => $tag_ids,		
				  ),		
				);
			}
		}
		if($post->post_type =='post'){
			$categories_slug = 'cat_ID';
			$categories = get_the_category($post->ID);
		}else{
			$categories_slug = 'slug';
			$categories = wp_get_post_terms($post->ID,$attr['taxonomySlug']);
		}

		if ($categories && !empty($attr["relatedPost"]) && ($attr["relatedPost"]=='both' || $attr["relatedPost"]=='category')) {	
			$category_ids = array();
			foreach($categories as $category) $category_ids[] = $category->$categories_slug;
			
			$query_args['post__not_in'] = array($post->ID);

			if($post->post_type =='post'){
				$query_args['category__in'] = $category_ids;
			}else{
				$query_args['tax_query'] = array(						
				  array(		
					'taxonomy' => $attr['taxonomySlug'],		
					'field' => 'slug',		
					'terms' => $category_ids,
				  ),		
				);
			}
		}
	}

	return $query_args;
}

function tpgb_pagination($pages = '', $range = 2){  
	$showitems = ($range * 2)+1;  
	
	global $paged;
	if(empty($paged)) $paged = 1;
	
	if($pages == '') {
		global $wp_query;
		if( $wp_query->max_num_pages <= 1 )
		return;
		
		$pages = $wp_query->max_num_pages;
		/*if(!$pages)
		{
			$pages = 1;
		}*/
		$pages = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
	}
	
	if(1 != $pages) {
		$paginate ="<div class=\"tpgb-pagination\">";
		if ( get_previous_posts_link() ){
			$paginate .= '<div class="paginate-prev">'.get_previous_posts_link('<i class="fa fa-long-arrow-left" aria-hidden="true"></i> PREV').'</div>';
		}
		
		for ($i=1; $i <= $pages; $i++)
		{
			if (1 != $pages && ( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
			{
				$paginate .= ($paged == $i)? "<span class=\"current\">".esc_html($i)."</span>":"<a href='".get_pagenum_link($i)."' class=\"inactive\">".esc_html($i)."</a>";
			}
		}
		if ( get_next_posts_link() ){
			$paginate .='<div class="paginate-next">'.get_next_posts_link('NEXT <i class="fa fa-long-arrow-right" aria-hidden="true"></i>',1).'</div>';
		}
		
		$paginate .="</div>\n";
		return $paginate;
	}
}