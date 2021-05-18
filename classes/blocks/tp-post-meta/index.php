<?php
/* Tp Block : Post Meta
 * @since	: 1.1.0
 */
function tpgb_tp_post_meta_render_callback( $attr, $content) {
	$output = '';
    $post_id = get_queried_object_id();
	
    $block_id = (!empty($attr['block_id'])) ? $attr['block_id'] : uniqid("title");
	$className = (!empty($attr['className'])) ? $attr['className'] :'';
	$align = (!empty($attr['align'])) ? $attr['align'] :'';
	$showDate = (!empty($attr['showDate'])) ? $attr['showDate'] : false;
	$showCategory = (!empty($attr['showCategory'])) ? $attr['showCategory'] : false;
	$showAuthor = (!empty($attr['showAuthor'])) ? $attr['showAuthor'] : false;
	$showComment = (!empty($attr['showComment'])) ? $attr['showComment'] : false;
	$metaSort = (!empty($attr['metaSort'])) ? (Array)$attr['metaSort'] :'';
	$metaLayout = (!empty($attr['metaLayout'])) ? $attr['metaLayout'] :'';
    
	$blockClass = '';
	if(!empty($className)){
		$blockClass .= $className;
	}
	if(!empty($align)){
		$blockClass .= ' align'.$align;
	}
	
	$outputDate='';
	if($showDate){
		$datePrefix = (!empty($attr['datePrefix'])) ? '<span class="tpgb-meta-date-label">'.esc_html($attr['datePrefix']).'</span>' : '';
		$dateIcon = (!empty($attr['dateIcon'])) ? '<i class="'.esc_attr($attr['dateIcon']).'"></i>' : '';
		$outputDate .='<span class="tpgb-meta-date" >'.$datePrefix.'<a href="'.esc_url(get_the_permalink()).'">'.$dateIcon.esc_html(get_the_date()).'</a></span>';
	}
	
	$outputCategory='';
	if($showCategory && ! empty(get_the_category()) ){
		$catePrefix = (!empty($attr['catePrefix'])) ? '<span class="tpgb-meta-category-label">'.esc_html($attr['catePrefix']).'</span>' : '';
		$cateDisplayNo = (!empty($attr['cateDisplayNo'])) ? $attr['cateDisplayNo'] : 0;
		$cateStyle = (!empty($attr['cateStyle'])) ? $attr['cateStyle'] : 'style-1';
		$terms = get_the_terms( $post_id, 'category', array("hide_empty" => true) );
		$category_list ='';
		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			$i = 0;
			foreach ( $terms as $term ) {
				if($cateDisplayNo >= $i){
					$category_list .= '<a href="' . esc_url( get_term_link( $term ) ) . '" alt="' . esc_attr( sprintf( __( '%s', 'tpgb' ), $term->name ) ) . '">' . $term->name . '</a>';
				}
				$i++;
			}
		}
		$outputCategory .='<span class="tpgb-meta-category '.esc_attr($cateStyle).'" >'.$catePrefix . $category_list.'</span>';
	}
	
	$outputAuthor='';
	if($showAuthor){
		global $post;
		$author_id = $post->post_author;
		$authorPrefix = (!empty($attr['authorPrefix'])) ? '<span class="tpgb-meta-author-label">'.esc_attr($attr['authorPrefix']).'</span>' : '';
		$authorIcon = (!empty($attr['authorIcon'])) ? $attr['authorIcon'] : '';
		$iconauthor = '';
		if(!empty($authorIcon) && $authorIcon=='profile'){
			$iconauthor = '<span>'.get_avatar( get_the_author_meta('ID'), 200).'</span>';
		}else if(!empty($authorIcon)){
			$iconauthor = '<i class="'.esc_attr($authorIcon).'"></i>';
		}
		$outputAuthor .='<span class="tpgb-meta-author" >'.$authorPrefix.'<a href="'.esc_url(get_author_posts_url(get_the_author_meta('ID'))).'" rel="'.esc_attr__('author','tpgb').'">'.$iconauthor.''.get_the_author_meta( 'display_name', $author_id ).'</a></span>';
	}
	
	$outputComment='';
	if($showComment){
		$commentIcon =(!empty($attr['commentIcon'])) ? '<i class="'.$attr['commentIcon'].'"></i>' : '';
		$comments_count = wp_count_comments($post_id);
		$count=0;
		if(!empty($comments_count)){
			$count = $comments_count->total_comments;
		}
		if($count===0){
			$comment_text = esc_html__('No Comments','tpgb');
		}else if($count > 0){
			$comment_text = 'Comments('.$count.')';
		}
		$commentPrefix = (!empty($attr['commentPrefix'])) ? '<span class="tpgb-meta-comment-label">'.esc_html($attr['commentPrefix']).'</span>' : '';
		$outputComment .='<span class="tpgb-meta-comment" >'.$commentPrefix.'<a href="'.esc_url(get_the_permalink()).'#respond" rel="'.esc_attr__('comment','tpgb').'">'.$commentIcon.$comment_text.'</a></span>';
	}
	
    $output .= '<div class="tpgb-post-meta tpgb-block-'.esc_attr($block_id ).' '.esc_attr($blockClass).'" >';
		$output .= '<div class="tpgb-meta-info '.esc_attr($metaLayout).'">';
			foreach($metaSort['sort'] as $item => $value){
				if($value == 'Date') { $output .= $outputDate;  }
				if($value == 'Category') { $output .= $outputCategory;  }
				if($value == 'Author') { $output .= $outputAuthor;  }
				if($value == 'Comments') { $output .= $outputComment;  }
			}
		$output .= '</div>';
    $output .= '</div>';
	
	$output = Tpgb_Blocks_Global_Options::block_Wrap_Render($attr, $output);
	
    return $output;
	}

/**
 * Render for the server-side
 */
function tpgb_post_meta_content() {
	$globalBgOption = Tpgb_Blocks_Global_Options::load_bg_options();
    $globalpositioningOption = Tpgb_Blocks_Global_Options::load_positioning_options();
    $globalPlusExtrasOption = Tpgb_Blocks_Global_Options::load_plusextras_options();
	
	$attributesOptions = array(
			'block_id' => [
                'type' => 'string',
				'default' => '',
			],
			'metaLayout' => [
				'type' => 'string',
				'default' => 'layout-1',
			],
			'metaSort' => [
                'type' => 'object',
				'default' => (object)[
					'sort' => ['Date', 'Category', 'Author', 'Comments'],
				],
			],
			'alignment' => [
				'type' => 'object',
				'default' => [ 'md' => 'left' ],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-post-meta-info,{{PLUS_WRAP}}.tpgb-post-meta {justify-content: {{alignment}};}',
					],
				],
			],
			'metaTypo' => [
				'type' => 'object',
				'default' => (object) [
					'openTypography' => 0,
					'size' => [ 'md' => '', 'unit' => 'px' ],
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-post-meta .tpgb-meta-info',
					],
				],
			],
			'metaColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-post-meta .tpgb-meta-info,{{PLUS_WRAP}}.tpgb-post-meta .tpgb-meta-info a{color: {{metaColor}};}',
					],
				],
            ],
			'labelTypo' => [
				'type' => 'object',
				'default' => (object) [
					'openTypography' => 0,
					'size' => [ 'md' => '', 'unit' => 'px' ],
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-post-meta .tpgb-meta-date-label,{{PLUS_WRAP}}.tpgb-post-meta .tpgb-meta-category-label,{{PLUS_WRAP}}.tpgb-post-meta .tpgb-meta-author-label,{{PLUS_WRAP}}.tpgb-post-meta .tpgb-meta-comment-label',
					],
				],
			],
			'labelColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-post-meta .tpgb-meta-date-label,{{PLUS_WRAP}}.tpgb-post-meta .tpgb-meta-category-label,{{PLUS_WRAP}}.tpgb-post-meta .tpgb-meta-author-label,{{PLUS_WRAP}}.tpgb-post-meta .tpgb-meta-comment-label{color: {{labelColor}};}',
					],
				],
            ],
			'separator' => [
                'type' => 'string',
				'default' => ',',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'metaLayout', 'relation' => '==', 'value' => 'layout-1']],
						'selector' => '{{PLUS_WRAP}}.tpgb-post-meta .tpgb-meta-info>span:after{content: "{{separator}}";}',
					],
				],
			],
			'sepLeftSpace' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-post-meta .tpgb-meta-info>span:after{margin-left: {{sepLeftSpace}}px;}',
					],
				],
			],
			'sepRightSpace' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-post-meta .tpgb-meta-info>span:after{margin-right: {{sepRightSpace}}px;}',
					],
				],
			],
			'sepSize' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-post-meta .tpgb-meta-info>span:after{font-size: {{sepSize}}px;}',
					],
				],
			],
			
			'sepColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-post-meta .tpgb-meta-info>span:after{color: {{sepColor}};}',
					],
				],
            ],
			
			'showDate' => [
                'type' => 'boolean',
				'default' => true,
			],
			'datePrefix' => [
                'type' => 'string',
				'default' => '',
			],
			'dateColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'showDate', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}} .tpgb-meta-date a{color: {{dateColor}};}',
					],
				],
            ],
			'dateHoverColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'showDate', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}} .tpgb-meta-date a:hover{color: {{dateHoverColor}};}',
					],
				],
            ],
			'dateIcon' => [
                'type' => 'string',
				'default' => '',
			],
			'dateIconSpace' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-meta-date i{margin-right: {{dateIconSpace}}px;}',
					],
				],
			],
			'dateIconColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'showDate', 'relation' => '==', 'value' => true],
										['key' => 'dateIcon', 'relation' => '!=', 'value' => '']
										],
						'selector' => '{{PLUS_WRAP}} .tpgb-meta-date i{color: {{dateIconColor}};}',
					],
				],
            ],
			'dateIconHoverColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'showDate', 'relation' => '==', 'value' => true],
										['key' => 'dateIcon', 'relation' => '!=', 'value' => '']
										],
						'selector' => '{{PLUS_WRAP}} .tpgb-meta-date a:hover i{color: {{dateIconHoverColor}};}',
					],
				],
            ],
			
			'showCategory' => [
                'type' => 'boolean',
				'default' => true,
			],
			'catePrefix' => [
                'type' => 'string',
				'default' => 'in ',
			],
			'cateDisplayNo' => [
                'type' => 'string',
				'default' => 5,
			],
			'cateColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'showCategory', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}} .tpgb-meta-category a,{{PLUS_WRAP}}.tpgb-post-meta .tpgb-meta-category:after{color: {{cateColor}};}',
					],
				],
            ],
			'cateHoverColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'showCategory', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}} .tpgb-meta-category a:hover{color: {{cateHoverColor}};}',
					],
				],
            ],
			'cateStyle' => [
                'type' => 'string',
				'default' => 'style-1',
			],
			'cateSpace' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'cateStyle', 'relation' => '==', 'value' => 'style-2']],
						'selector' => '{{PLUS_WRAP}}.tpgb-post-meta .tpgb-meta-category.style-2 a{margin-right: {{cateSpace}}px;}',
					],
				],
			],
			'catepadding' => [
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
						'condition' => [(object) ['key' => 'cateStyle', 'relation' => '==', 'value' => 'style-2']],
						'selector' => '{{PLUS_WRAP}}.tpgb-post-meta .tpgb-meta-category.style-2 a{padding: {{catepadding}};}',
					],
				],
			],
			'cateBorder' => [
				'type' => 'object',
				'default' => (object) [
					'openBorder' => 0,
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'cateStyle', 'relation' => '==', 'value' => 'style-2']],
						'selector' => '{{PLUS_WRAP}}.tpgb-post-meta .tpgb-meta-category.style-2 a',
					],
				],
			],
			'cateBorderHover' => [
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
						'condition' => [(object) ['key' => 'cateStyle', 'relation' => '==', 'value' => 'style-2']],
						'selector' => '{{PLUS_WRAP}}.tpgb-post-meta .tpgb-meta-category.style-2 a:hover',
					],
				],
			],
			
			'cateBorderRadius' => [
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
						'condition' => [(object) ['key' => 'cateStyle', 'relation' => '==', 'value' => 'style-2']],
						'selector' => '{{PLUS_WRAP}}.tpgb-post-meta .tpgb-meta-category.style-2 a{border-radius: {{cateBorderRadius}};}',
					],
				],
			],
			'cateBorderRadiusHover' => [
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
						'condition' => [(object) ['key' => 'cateStyle', 'relation' => '==', 'value' => 'style-2']],
						'selector' => '{{PLUS_WRAP}}.tpgb-post-meta .tpgb-meta-category.style-2 a:hover{border-radius: {{cateBorderRadiusHover}};}',
					],
				],
			],
			'cateBg' => [
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
						'condition' => [(object) ['key' => 'cateStyle', 'relation' => '==', 'value' => 'style-2']],
						'selector' => '{{PLUS_WRAP}}.tpgb-post-meta .tpgb-meta-category.style-2 a',
					],
				],
			],
			'cateBgHover' => [
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
						'condition' => [(object) ['key' => 'cateStyle', 'relation' => '==', 'value' => 'style-2']],
						'selector' => '{{PLUS_WRAP}}.tpgb-post-meta .tpgb-meta-category.style-2 a:hover',
					],
				],
			],
			'cateBoxShadow' => [
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
						'condition' => [(object) ['key' => 'cateStyle', 'relation' => '==', 'value' => 'style-2']],
						'selector' => '{{PLUS_WRAP}}.tpgb-post-meta .tpgb-meta-category.style-2 a',
					],
				],
			],
			'cateBoxShadowHover' => [
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
						'condition' => [(object) ['key' => 'cateStyle', 'relation' => '==', 'value' => 'style-2']],
						'selector' => '{{PLUS_WRAP}}.tpgb-post-meta .tpgb-meta-category.style-2 a:hover',
					],
				],
			],
			
			'showAuthor' => [
                'type' => 'boolean',
				'default' => true,
			],
			'authorPrefix' => [
                'type' => 'string',
				'default' => 'By ',
			],
			'authorIcon' => [
                'type' => 'string',
				'default' => '',
			],
			'authorIconSpace' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-meta-author i,{{PLUS_WRAP}} .tpgb-meta-author img{margin-right: {{authorIconSpace}}px;}',
					],
				],
			],
			'authorIconSize' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-meta-author img{max-width: {{authorIconSize}}px;}',
					],
				],
			],
			
			'authorColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'showAuthor', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}} .tpgb-meta-author a{color: {{authorColor}};}',
					],
				],
            ],
			'authorHoverColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'showAuthor', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}} .tpgb-meta-author a:hover{color: {{authorHoverColor}};}',
					],
				],
            ],
			'authorIconColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'showAuthor', 'relation' => '==', 'value' => true],
										['key' => 'authorIcon', 'relation' => '!=', 'value' => 'profile']
						],
						'selector' => '{{PLUS_WRAP}} .tpgb-meta-author i{color: {{authorIconColor}};}',
					],
				],
            ],
			'authorIconHoverColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'showAuthor', 'relation' => '==', 'value' => true],
										['key' => 'authorIcon', 'relation' => '!=', 'value' => 'profile']
						],
						'selector' => '{{PLUS_WRAP}} .tpgb-meta-author a:hover i{color: {{authorIconHoverColor}};}',
					],
				],
            ],
			
			'showComment' => [
                'type' => 'boolean',
				'default' => true,
			],
			'commentPrefix' => [
                'type' => 'string',
				'default' => '',
			],
			'commentIcon' => [
                'type' => 'string',
				'default' => '',
			],
			'commentIconSpace' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-meta-comment i{margin-right: {{commentIconSpace}}px;}',
					],
				],
			],
			'commentColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'showComment', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}} .tpgb-meta-comment a{color: {{commentColor}};}',
					],
				],
            ],
			'commentHoverColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'showComment', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}} .tpgb-meta-comment a:hover{color: {{commentHoverColor}};}',
					],
				],
            ],
			'commentIconColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'showComment', 'relation' => '==', 'value' => true],
												['key' => 'commentIcon', 'relation' => '!=', 'value' => '']
						],
						'selector' => '{{PLUS_WRAP}} .tpgb-meta-comment i{color: {{commentIconColor}};}',
					],
				],
            ],
			'commentIconHoverColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'showComment', 'relation' => '==', 'value' => true],
							['key' => 'commentIcon', 'relation' => '!=', 'value' => '']
						],
						'selector' => '{{PLUS_WRAP}} .tpgb-meta-comment a:hover i{color: {{commentIconHoverColor}};}',
					],
				],
            ],
			
			'padding' => [
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
						'selector' => '{{PLUS_WRAP}} .tpgb-meta-info .tpgb-meta-comment,{{PLUS_WRAP}} .tpgb-meta-info .tpgb-meta-category,{{PLUS_WRAP}} .tpgb-meta-info .tpgb-meta-post-views,{{PLUS_WRAP}} .tpgb-meta-info .tpgb-meta-post-likes,{{PLUS_WRAP}} .tpgb-meta-info .tpgb-meta-date,{{PLUS_WRAP}} .tpgb-meta-info .tpgb-meta-author{padding: {{padding}};}',
					],
				],
			],
			'inMargin' => [
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
						'selector' => '{{PLUS_WRAP}} .tpgb-meta-info .tpgb-meta-comment,{{PLUS_WRAP}} .tpgb-meta-info .tpgb-meta-category,{{PLUS_WRAP}} .tpgb-meta-info .tpgb-meta-post-views,{{PLUS_WRAP}} .tpgb-meta-info .tpgb-meta-post-likes,{{PLUS_WRAP}} .tpgb-meta-info .tpgb-meta-date,{{PLUS_WRAP}} .tpgb-meta-info .tpgb-meta-author{margin: {{inMargin}};}',
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
						'selector' => '{{PLUS_WRAP}} .tpgb-meta-info .tpgb-meta-comment,{{PLUS_WRAP}} .tpgb-meta-info .tpgb-meta-category,{{PLUS_WRAP}} .tpgb-meta-info .tpgb-meta-post-views,{{PLUS_WRAP}} .tpgb-meta-info .tpgb-meta-post-likes,{{PLUS_WRAP}} .tpgb-meta-info .tpgb-meta-date,{{PLUS_WRAP}} .tpgb-meta-info .tpgb-meta-author',
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
						'selector' => '{{PLUS_WRAP}} .tpgb-meta-info .tpgb-meta-comment:hover,{{PLUS_WRAP}} .tpgb-meta-info .tpgb-meta-category:hover,{{PLUS_WRAP}} .tpgb-meta-info .tpgb-meta-post-views:hover,{{PLUS_WRAP}} .tpgb-meta-info .tpgb-meta-post-likes:hover,{{PLUS_WRAP}} .tpgb-meta-info .tpgb-meta-date:hover,{{PLUS_WRAP}} .tpgb-meta-info .tpgb-meta-author:hover',
					],
				],
			],
			
			'boxBRadius' => [
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
						'selector' => '{{PLUS_WRAP}} .tpgb-meta-info .tpgb-meta-comment,{{PLUS_WRAP}} .tpgb-meta-info .tpgb-meta-category,{{PLUS_WRAP}} .tpgb-meta-info .tpgb-meta-post-views,{{PLUS_WRAP}} .tpgb-meta-info .tpgb-meta-post-likes,{{PLUS_WRAP}} .tpgb-meta-info .tpgb-meta-date,{{PLUS_WRAP}} .tpgb-meta-info .tpgb-meta-author {border-radius: {{boxBRadius}};}',
					],
				],
			],
			'boxBRadiusHover' => [
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
						'selector' => '{{PLUS_WRAP}} .tpgb-meta-info .tpgb-meta-comment:hover,{{PLUS_WRAP}} .tpgb-meta-info .tpgb-meta-category:hover,{{PLUS_WRAP}} .tpgb-meta-info .tpgb-meta-post-views:hover,{{PLUS_WRAP}} .tpgb-meta-info .tpgb-meta-post-likes:hover,{{PLUS_WRAP}} .tpgb-meta-info .tpgb-meta-date:hover,{{PLUS_WRAP}} .tpgb-meta-info .tpgb-meta-author:hover{border-radius: {{boxBRadiusHover}};}',
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
						'selector' => '{{PLUS_WRAP}} .tpgb-meta-info .tpgb-meta-comment,{{PLUS_WRAP}} .tpgb-meta-info .tpgb-meta-category,{{PLUS_WRAP}} .tpgb-meta-info .tpgb-meta-post-views,{{PLUS_WRAP}} .tpgb-meta-info .tpgb-meta-post-likes,{{PLUS_WRAP}} .tpgb-meta-info .tpgb-meta-date,{{PLUS_WRAP}} .tpgb-meta-info .tpgb-meta-author ',
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
						'selector' => '{{PLUS_WRAP}} .tpgb-meta-info .tpgb-meta-comment:hover,{{PLUS_WRAP}} .tpgb-meta-info .tpgb-meta-category:hover,{{PLUS_WRAP}} .tpgb-meta-info .tpgb-meta-post-views:hover,{{PLUS_WRAP}} .tpgb-meta-info .tpgb-meta-post-likes:hover,{{PLUS_WRAP}} .tpgb-meta-info .tpgb-meta-date:hover,{{PLUS_WRAP}} .tpgb-meta-info .tpgb-meta-author:hover',
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
						'selector' => '{{PLUS_WRAP}} .tpgb-meta-info .tpgb-meta-comment,{{PLUS_WRAP}} .tpgb-meta-info .tpgb-meta-category,{{PLUS_WRAP}} .tpgb-meta-info .tpgb-meta-post-views,{{PLUS_WRAP}} .tpgb-meta-info .tpgb-meta-post-likes,{{PLUS_WRAP}} .tpgb-meta-info .tpgb-meta-date,{{PLUS_WRAP}} .tpgb-meta-info .tpgb-meta-author ',
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
						'selector' => '{{PLUS_WRAP}} .tpgb-meta-info .tpgb-meta-comment:hover,{{PLUS_WRAP}} .tpgb-meta-info .tpgb-meta-category:hover,{{PLUS_WRAP}} .tpgb-meta-info .tpgb-meta-post-views:hover,{{PLUS_WRAP}} .tpgb-meta-info .tpgb-meta-post-likes:hover,{{PLUS_WRAP}} .tpgb-meta-info .tpgb-meta-date:hover,{{PLUS_WRAP}} .tpgb-meta-info .tpgb-meta-author:hover',
					],
				],
			],
		);
	
	$attributesOptions = array_merge($attributesOptions,$globalBgOption,$globalpositioningOption,$globalPlusExtrasOption);
	
	register_block_type( 'tpgb/tp-post-meta', array(
		'attributes' => $attributesOptions,
		'editor_script' => 'tpgb-block-editor-js',
		'editor_style'  => 'tpgb-block-editor-css',
        'render_callback' => 'tpgb_tp_post_meta_render_callback'
    ) );
}
add_action( 'init', 'tpgb_post_meta_content' );