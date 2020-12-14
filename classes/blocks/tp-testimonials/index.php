<?php
/**
 * After rendring from the block editor display output on front-end
 */
function tpgb_tp_testimonials_render_callback( $attributes, $content) {
	$output = '';
    $block_id = (!empty($attributes['block_id'])) ? $attributes['block_id'] : uniqid("title");
    $style = (!empty($attributes['style'])) ? $attributes['style'] : 'style-1';
    $className = (!empty($attributes['className'])) ? $attributes['className'] :'';
	$align = (!empty($attributes['align'])) ? $attributes['align'] :'';
	
	$blockClass = '';
	if(!empty($className)){
		$blockClass .= $className;
	}
	if(!empty($align)){
		$blockClass .= ' align'.$align;
	}
	
	$ItemRepeater = (!empty($attributes['ItemRepeater'])) ? $attributes['ItemRepeater'] : [];
	
	//Carousel Options
	$carousel_settings = tpgb_testmonial_carousel_settings($attributes);
	$carousel_settings = json_encode($carousel_settings);
	

    $output .= '<div class="tpgb-testimonials tpgb-carousel testimonial-'.esc_attr($style).' tpgb-block-'.esc_attr($block_id).' '.esc_attr($blockClass).'" data-carousel-option=\'' . $carousel_settings . '\' >';
		$output .= '<div class="post-loop-inner ">';
			
			if( !empty( $ItemRepeater ) ){
				foreach ( $ItemRepeater as $index => $item ) :
					if(is_array($item)){
					
						$itemContent = '';
						if( !empty($item['content']) ){
							$itemContent .= '<div class="entry-content">';
								$itemContent .= wp_kses_post($item['content']);
							$itemContent .= '</div>';
						}
						
						$itemAuthorTitle = '';
						if( !empty($item['authorTitle']) ){
							$itemAuthorTitle .= '<h3 class="testi-author-title">'.esc_html($item['authorTitle']).'</h3>';
						}
						
						$itemTitle ='';
						if(!empty($item['testiTitle'])){
							$itemTitle .= '<div class="testi-post-title">'.esc_html($item['testiTitle']).'</div>';
						}
						
						$itemDesignation ='';
						if(!empty($item['designation'])){
							$itemDesignation .= '<div class="testi-post-designation">'.esc_html($item['designation']).'</div>';
						}
						
						$imgUrl ='';
						if(!empty($item['avatar']) && !empty($item['avatar']['url'])){
							$imgUrl =$item['avatar']['url'];
						}else{
							$imgUrl =TPGB_URL.'assets/images/tpgb-placeholder.jpg';
						}
						
						$output .= '<div class="grid-item tpgb-col tp-repeater-item-"'.esc_attr($item['_key']).' >';
							$output .= '<div class="testimonial-list-content" >';
								
								if($style!='style-4'){
									$output .= '<div class="testimonial-content-text">';
										if($style=="style-1" || $style=="style-2"){
											$output .= $itemContent;
											$output .= $itemAuthorTitle;
										}
									$output .= '</div>';
								}
								
								$output .= '<div class="post-content-image">';
									$output .= '<div class="author-thumb">';
										$output .= '<img src="'.esc_url($imgUrl).'" alt="'.esc_attr__('author avatar','tpgb').'"/>';
									$output .= '</div>';
									if($style=="style-1" || $style=="style-2"){
										$output .= $itemTitle;
										$output .= $itemDesignation;
									}
								$output .= '</div>';
								
								
							$output .= "</div>";
						$output .= "</div>";
					}
				endforeach;
			}
			
		$output .= "</div>";
    $output .= "</div>";
	
	$output = Tpgb_Blocks_Global_Options::block_Wrap_Render($attributes, $output);
	
    return $output;
}

function tpgb_testmonial_carousel_settings($attr){
	$settings =array();
	$settings['sliderMode'] = $attr['sliderMode'];
	$settings['slidesToShow'] = $attr['slideColumns'];
	$settings['initialSlide'] = $attr['initialSlide'];
	$settings['slidesToScroll'] = $attr['slideScroll'];
	$settings['speed'] = $attr['slideSpeed'];
	$settings['draggable'] = $attr['slideDraggable'];
	$settings['infinite'] = $attr['slideInfinite'];
	$settings['pauseOnHover'] = $attr['slideHoverPause'];
	$settings['adaptiveHeight'] = $attr['slideAdaptiveHeight'];
	$settings['autoplay'] = $attr['slideAutoplay'];
	$settings['autoplaySpeed'] = $attr['slideAutoplaySpeed'];
	$settings['dots'] = $attr['showDots'];
	$settings['dotsStyle'] = $attr['dotsStyle'];
	$settings['centerMode'] = $attr['centerMode'];
	$settings['arrows'] = $attr['showArrows'];
	$settings['arrowsStyle'] = $attr['arrowsStyle'];
	
	return $settings;
}

/**
 * Render for the server-side
 */
function tpgb_tp_testimonials() {
	$globalBgOption = Tpgb_Blocks_Global_Options::load_bg_options();
	$globalpositioningOption = Tpgb_Blocks_Global_Options::load_positioning_options();
	$globalPlusExtrasOption = Tpgb_Blocks_Global_Options::load_plusextras_options();
	
	$attributesOptions = array(
			'block_id' => [
                'type' => 'string',
				'default' => '',
			],
			'style' => [
                'type' => 'string',
				'default' => 'style-1',
			],
			'ItemRepeater' => [
				'type' => 'array',
				'repeaterField' => [
					(object) [
						'testiTitle' => [
							'type' => 'string',
							'default' => 'John Doe',
						],
						'designation' => [
							'type' => 'string',
							'default' => 'MD at Orange',
						],
						'content' => [
							'type' => 'string',
							'default' => ' I am pretty satisfied with The Plus Gutenberg Addons. The Plus has completely surpassed our expectations. I was amazed at the quality of The Plus Gutenberg Addons.',
						],
						'authorTitle' => [
							'type' => 'string',
							'default' => 'Supercharge ⚡ Gutenberg',
						],
					],
				], 
				'default' => [ 
					[ '_key'=> 'cvi9', 'testiTitle' => 'John Doe', 'designation' => 'MD at Orange', 'content' => ' I am pretty satisfied with The Plus Gutenberg Addons. The Plus has completely surpassed our expectations. I was amazed at the quality of The Plus Gutenberg Addons.','authorTitle' => 'Supercharge ⚡ Gutenberg' ]
				],
			],
			
			'titleTypo' => [
				'type' => 'object',
				'default' => (object) [
					'openTypography' => 0,
					'size' => [ 'md' => '', 'unit' => 'px' ],
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .post-content-image .testi-post-title',
					],
				],
			],
			'titleNormalColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .post-content-image .testi-post-title{color: {{titleNormalColor}};}',
					],
				],
            ],
			'titleHoverColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .testimonial-list-content:hover .testi-post-title{color: {{titleHoverColor}};}',
					],
				],
            ],
			
			'AuthortitleTypo' => [
				'type' => 'object',
				'default' => (object) [
					'openTypography' => 0,
					'size' => [ 'md' => '', 'unit' => 'px' ],
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .testi-author-title',
					],
				],
			],
			'AuthortitleNormalColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .testi-author-title{color: {{AuthortitleNormalColor}};}',
					],
				],
            ],
			'AuthortitleHoverColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .testimonial-list-content:hover .testi-author-title{color: {{AuthortitleHoverColor}};}',
					],
				],
            ],
			
			'DesTypo' => [
				'type' => 'object',
				'default' => (object) [
					'openTypography' => 0,
					'size' => [ 'md' => '', 'unit' => 'px' ],
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .testi-post-designation',
					],
				],
			],
			'DesNormalColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .testi-post-designation{color: {{DesNormalColor}};}',
					],
				],
            ],
			'DesHoverColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .testimonial-list-content:hover .testi-post-designation{color: {{DesHoverColor}};}',
					],
				],
            ],
			
			'contentTypo' => [
				'type' => 'object',
				'default' => (object) [
					'openTypography' => 0,
					'size' => [ 'md' => '', 'unit' => 'px' ],
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .testimonial-list-content .entry-content',
					],
				],
			],
			'contentNormalColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .testimonial-list-content .entry-content{color: {{contentNormalColor}};}',
					],
				],
            ],
			'contentHoverColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .testimonial-list-content:hover .entry-content{color: {{contentHoverColor}};}',
					],
				],
            ],
			
			'boxMargin' => [
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
						'selector' => '{{PLUS_WRAP}}.testimonial-style-1 .testimonial-content-text,{{PLUS_WRAP}}.testimonial-style-2 .testimonial-list-content{margin: {{boxMargin}};}',
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
						'selector' => '{{PLUS_WRAP}}.testimonial-style-1 .testimonial-content-text,{{PLUS_WRAP}}.testimonial-style-2 .testimonial-list-content{padding: {{boxPadding}};}',
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
						'selector' => '{{PLUS_WRAP}}.testimonial-style-1 .testimonial-content-text,{{PLUS_WRAP}}.testimonial-style-2 .testimonial-list-content{border-radius: {{boxBorderRadius}};}',
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
						'selector' => '{{PLUS_WRAP}}.testimonial-style-1 .testimonial-list-content:hover .testimonial-content-text,{{PLUS_WRAP}}.testimonial-style-2 .testimonial-list-content:hover{border-radius: {{boxBorderRadiusHover}};}',
					],
				],
			],
			'boxBg' => [
				'type' => 'object',
				'default' => (object) [
					'openBg'=> 0,
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.testimonial-style-1 .testimonial-content-text,{{PLUS_WRAP}}.testimonial-style-2 .testimonial-list-content',
					],
				],
			],
			'arrowNormalColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.testimonial-style-1 .testimonial-content-text:after{border-top-color: {{arrowNormalColor}};}',
					],
				],
            ],
			'boxBgHover' => [
				'type' => 'object',
				'default' => (object) [
					'openBg'=> 0,
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.testimonial-style-1 .testimonial-list-content:hover .testimonial-content-text,{{PLUS_WRAP}}.testimonial-style-2 .testimonial-list-content:hover',
					],
				],
			],
			'arrowHoverColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.testimonial-style-1 .testimonial-list-content:hover .testimonial-content-text:after{border-top-color: {{arrowHoverColor}};}',
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
						'selector' => '{{PLUS_WRAP}}.testimonial-style-1 .testimonial-content-text,{{PLUS_WRAP}}.testimonial-style-2 .testimonial-list-content',
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
						'selector' => '{{PLUS_WRAP}}.testimonial-style-1 .testimonial-list-content:hover .testimonial-content-text,{{PLUS_WRAP}}.testimonial-style-2 .testimonial-list-content:hover',
					],
				],
			],
			
			'imgMaxWidth' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.testimonial-style-1 .author-thumb,{{PLUS_WRAP}}.testimonial-style-2 .author-thumb{max-width: {{imgMaxWidth}}px;}',
					],
				],
			],
			'imageBorderRadius' => [
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
						'selector' => '{{PLUS_WRAP}} .author-thumb img{border-radius: {{imageBorderRadius}};}',
					],
				],
			],
			'imageBoxShadow' => [
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
						'selector' => '{{PLUS_WRAP}} .author-thumb img',
					],
				],
			],
			'imageBoxShadowHover' => [
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
						'selector' => '{{PLUS_WRAP}} .testimonial-list-content:hover .author-thumb img',
					],
				],
			],
			
			'sliderMode' => [
				'type' => 'string',
				'default' => 'horizontal',
			],
			'slideSpeed' => [
				'type' => 'string',
				'default' => 1500,
			],
			
			'slideColumns' => [
				'type' => 'object',
				'default' => [ 'md' => 1,'sm' => 1,'xs' => 1 ],
			],
			'initialSlide' => [
				'type' => 'number',
				'default' => 0,
			],
			'slideScroll' => [
				'type' => 'object',
				'default' => [ 'md' => 1 ],
			],
			'slideColumnSpace' => [
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
						'selector' => '{{PLUS_WRAP}} .grid-item{padding: {{slideColumnSpace}};}',
					],
				],
			],
			'slideDraggable' => [
				'type' => 'object',
				'default' => [ 'md' => true ],
			],
			'slideInfinite' => [
				'type' => 'object',
				'default' => [ 'md' => false ],
			],
			'slideHoverPause' => [
				'type' => 'boolean',
				'default' => false,
			],
			'slideAdaptiveHeight' => [
				'type' => 'boolean',
				'default' => false,
			],
			'slideAutoplay' => [
				'type' => 'object',
				'default' => [ 'md' => false ],
			],
			'slideAutoplaySpeed' => [
				'type' => 'object',
				'default' => ['md' => 1500 ],
			],
			'showDots' => [
				'type' => 'object',
				'default' => [ 'md' => true ],
			],
			'dotsStyle' => [
				'type' => 'string',
				'default' => 'style-1',
			],
			'dotsBorderColor' => [
				'type' => 'string',
				'default' => '#8072fc',
				'style' => [
						(object) [
						'condition' => [
							(object) [ 'key' => 'dotsStyle', 'relation' => '==', 'value' => ['style-1','style-2','style-3','style-4','style-6'] ],
							(object) [ 'key' => 'showDots', 'relation' => '==', 'value' => true ]
						],
						'selector' => '{{PLUS_WRAP}} .slick-dots.style-1 li button{-webkit-box-shadow:inset 0 0 0 8px {{dotsBorderColor}};-moz-box-shadow: inset 0 0 0 8px {{dotsBorderColor}};box-shadow: inset 0 0 0 8px {{dotsBorderColor}};} {{PLUS_WRAP}} .slick-dots.style-1 li.slick-active button{-webkit-box-shadow:inset 0 0 0 1px {{dotsBorderColor}};-moz-box-shadow: inset 0 0 0 1px {{dotsBorderColor}};box-shadow: inset 0 0 0 1px {{dotsBorderColor}};}{{PLUS_WRAP}} .slick-dots.style-1 li button:before{color: {{dotsBorderColor}};}',
					],
				],
			],
			'dotsTopSpace' => [
				'type' => 'object',
				'default' => [ 'md' => 0,'sm' => 0,'xs' => 0,'unit' => 'px' ],
				'style' => [
						(object) [
						'condition' => [ 
							(object) [ 'key' => 'showDots', 'relation' => '==', 'value' => true ]
						],
						'selector' => '{{PLUS_WRAP}} .slick-dots{transform: translateY({{dotsTopSpace}});}',
					],
				],
			],
			'slideHoverDots' => [
				'type' => 'boolean',
				'default' => false,
			],
			'showArrows' => [
				'type' => 'object',
				'default' => [ 'md' => false ],
			],
			'arrowsStyle' => [
				'type' => 'string',
				'default' => 'style-1',
			],
			'arrowsBgColor' => [
				'type' => 'string',
				'default' => '#8072fc',
				'style' => [
						(object) [
						'condition' => [
							(object) [ 'key' => 'arrowsStyle', 'relation' => '==', 'value' => ['style-1','style-3','style-4','style-6'] ],
							(object) [ 'key' => 'showArrows', 'relation' => '==', 'value' => true ]
						],
						'selector' => '{{PLUS_WRAP}} .slick-nav.style-1{background:{{arrowsBgColor}};}',
					],
				],
			],
			'arrowsIconColor' => [
				'type' => 'string',
				'default' => '#fff',
				'style' => [
						(object) [
						'condition' => [
							(object) [ 'key' => 'showArrows', 'relation' => '==', 'value' => true ]
						],
						'selector' => '{{PLUS_WRAP}} .slick-nav.style-1:before{color:{{arrowsIconColor}};}',
					],
				],
			],
			'arrowsHoverBgColor' => [
				'type' => 'string',
				'default' => '#fff',
				'style' => [
						(object) [
						'condition' => [
							(object) [ 'key' => 'arrowsStyle', 'relation' => '==', 'value' => ['style-1','style-2','style-3','style-4'] ],
							(object) [ 'key' => 'showArrows', 'relation' => '==', 'value' => true ]
						],
						'selector' => '{{PLUS_WRAP}} .slick-nav.style-1:hover{background:{{arrowsHoverBgColor}};}',
					],
				],
			],
			'arrowsHoverIconColor' => [
				'type' => 'string',
				'default' => '#8072fc',
				'style' => [
						(object) [
						'condition' => [
							(object) [ 'key' => 'showArrows', 'relation' => '==', 'value' => true ]
						],
						'selector' => '{{PLUS_WRAP}} .slick-nav.style-1:hover:before{color:{{arrowsHoverIconColor}};}',
					],
				],
			],
			'outerArrows' => [
				'type' => 'boolean',
				'default' => false,
			],
			'slideHoverArrows' => [
				'type' => 'boolean',
				'default' => false,
			],
			'centerMode' => [
				'type' => 'object',
				'default' => [ 'md' => false ],
			],
		);
	
	$attributesOptions = array_merge($attributesOptions,$globalBgOption,$globalpositioningOption,$globalPlusExtrasOption);
	
	register_block_type( 'tpgb/tp-testimonials', array(
		'attributes' => $attributesOptions,
		'editor_script' => 'tpgb-block-editor-js',
		'editor_style'  => 'tpgb-block-editor-css',
        'render_callback' => 'tpgb_tp_testimonials_render_callback'
    ) );
}
add_action( 'init', 'tpgb_tp_testimonials' );