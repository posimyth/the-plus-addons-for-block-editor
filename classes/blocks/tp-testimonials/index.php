<?php
/* Block : Testimonials
 * @since : 1.0.0
 */
defined( 'ABSPATH' ) || exit;

function tpgb_tp_testimonials_render_callback( $attributes, $content) {
	$output = '';
    $block_id = (!empty($attributes['block_id'])) ? $attributes['block_id'] : uniqid("title");
    $style = (!empty($attributes['style'])) ? $attributes['style'] : 'style-1';
   
	$blockClass = Tp_Blocks_Helper::block_wrapper_classes( $attributes );
	$showDots = (!empty($attributes['showDots'])) ? $attributes['showDots'] : [ 'md' => false ];
	$dotsStyle = (!empty($attributes['dotsStyle'])) ? $attributes['dotsStyle'] : false;
	$showArrows = (!empty($attributes['showArrows'])) ? $attributes['showArrows'] : [ 'md' => false ];
	$arrowsStyle = (!empty($attributes['arrowsStyle'])) ? $attributes['arrowsStyle'] : 'style-1';
	
	$ItemRepeater = (!empty($attributes['ItemRepeater'])) ? $attributes['ItemRepeater'] : [];
	
	//Carousel Options
	$carousel_settings = Tp_Blocks_Helper::carousel_settings($attributes);
	
	$Sliderclass ='';
	if( ( isset($showDots['md']) && !empty($showDots['md']) ) || ( isset($showDots['sm']) && !empty($showDots['sm']) ) || ( isset($showDots['xs']) && !empty($showDots['xs']) ) ){
		$Sliderclass .= 'dots-'.esc_attr($dotsStyle);
	}
	
    $output .= '<div class="tpgb-testimonials tpgb-carousel splide testimonial-'.esc_attr($style).' tpgb-block-'.esc_attr($block_id).' '.esc_attr($blockClass).' '.esc_attr($Sliderclass).'" data-carousel-option=\'' . json_encode($carousel_settings) . '\' >';
		if( ( isset($showArrows['md']) && !empty($showArrows['md']) ) || ( isset($showArrows['sm']) && !empty($showArrows['sm']) ) || ( isset($showArrows['xs']) && !empty($showArrows['xs']) ) ){
			$output .= Tp_Blocks_Helper::tpgb_carousel_arrow($arrowsStyle,'');
		}
		$output .= '<div class="post-loop-inner splide__track">';
			$output .= '<div class="splide__list">';
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
							if(!empty($item['avatar']) && !empty($item['avatar']['id'])){
								$imgUrl = wp_get_attachment_image($item['avatar']['id'],'medium');
							}else if(!empty($item['avatar']) && !empty($item['avatar']['url'])){
								$imgUrl = '<img src="'.esc_url($item['avatar']['url']).'" alt="'.esc_html__('author avatar','tpgb').'"/>';
							}else{
								$imgUrl ='<img src="'.esc_url(TPGB_URL.'assets/images/tpgb-placeholder-grid.jpg').'" alt="'.esc_html__('author avatar','tpgb').'"/>';
							}
							
							$output .= '<div class="grid-item splide__slide tpgb-col tp-repeater-item-'.esc_attr($item['_key']).'" >';
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
											$output .= $imgUrl;
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
    $output .= "</div>";
	
	$output = Tpgb_Blocks_Global_Options::block_Wrap_Render($attributes, $output);
	
	$arrowCss = Tp_Blocks_Helper::tpgb_carousel_arrow_css( $showArrows , $block_id );
	if( !empty($arrowCss) ){
		$output .= $arrowCss;
	}
    return $output;
}

/**
 * Render for the server-side
 */
function tpgb_tp_testimonials() {
	$globalBgOption = Tpgb_Blocks_Global_Options::load_bg_options();
	$globalpositioningOption = Tpgb_Blocks_Global_Options::load_positioning_options();
	$globalPlusExtrasOption = Tpgb_Blocks_Global_Options::load_plusextras_options();
	$carousel_options = Tpgb_Blocks_Global_Options::carousel_options();
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
			'cntHovercolor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .testimonial-list-content:hover .entry-content{color: {{cntHovercolor}};}',
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
		);
	
	$attributesOptions = array_merge($attributesOptions, $carousel_options, $globalBgOption,$globalpositioningOption,$globalPlusExtrasOption);
	
	register_block_type( 'tpgb/tp-testimonials', array(
		'attributes' => $attributesOptions,
		'editor_script' => 'tpgb-block-editor-js',
		'editor_style'  => 'tpgb-block-editor-css',
        'render_callback' => 'tpgb_tp_testimonials_render_callback'
    ) );
}
add_action( 'init', 'tpgb_tp_testimonials' );