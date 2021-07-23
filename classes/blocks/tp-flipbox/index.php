<?php
/* Block : Flip Box
 * @since : 1.1.2
 */
function tpgb_tp_flipbox_render_callback( $attributes, $content) {
    $block_id = (!empty($attributes['block_id'])) ? $attributes['block_id'] : uniqid("title");
	$layoutType = (!empty($attributes['layoutType'])) ? $attributes['layoutType'] : 'listing';
	$flipType = (!empty($attributes['flipType'])) ? $attributes['flipType'] : 'horizontal';
	$iconType = (!empty($attributes['iconType'])) ? $attributes['iconType'] : 'icon';
	$iconStore = (!empty($attributes['iconStore'])) ? $attributes['iconStore'] : '';
	$iconStyle = (!empty($attributes['iconStyle'])) ? $attributes['iconStyle'] : 'square';
	$imagestore = (!empty($attributes['imagestore'])) ? $attributes['imagestore'] : '';
	$imageSize = (!empty($attributes['imageSize'])) ? $attributes['imageSize'] : 'thumbnail';
	$title = (!empty($attributes['title'])) ? $attributes['title'] : '';
	$description = (!empty($attributes['description'])) ? $attributes['description'] : '';
	
	$backBtn = (!empty($attributes['backBtn'])) ? $attributes['backBtn'] : false;
	$backCarouselBtn = (!empty($attributes['backCarouselBtn'])) ? $attributes['backCarouselBtn'] : false;
	
	$flipcarousel = (!empty($attributes['flipcarousel'])) ? $attributes['flipcarousel'] : [];
	
	$blockClass = Tp_Blocks_Helper::block_wrapper_classes( $attributes );
	
	$count = '';
	$Sliderclass = $arrowCss = '';
	$carousel_settings = [];
	if($layoutType=='carousel'){
		
		$carousel_settings = Tp_Blocks_Helper::carousel_settings( $attributes );
		
		$Sliderclass .= 'tpgb-carousel splide';
		
		$showDots = (!empty($attributes['showDots'])) ? $attributes['showDots'] : [ 'md' => false ];
		$dotsStyle = (!empty($attributes['dotsStyle'])) ? $attributes['dotsStyle'] : false;
		if( ( isset($showDots['md']) && !empty($showDots['md']) ) || ( isset($showDots['sm']) && !empty($showDots['sm']) ) || ( isset($showDots['xs']) && !empty($showDots['xs']) ) ){
			$Sliderclass .= ' dots-'.esc_attr($dotsStyle);
		}
		
		$showArrows = (!empty($attributes['showArrows'])) ? $attributes['showArrows'] : [ 'md' => false ];
		$arrowsStyle = (!empty($attributes['arrowsStyle'])) ? $attributes['arrowsStyle'] : 'style-1';
		
		$arrowCss = Tp_Blocks_Helper::tpgb_carousel_arrow_css( $showArrows , $block_id );
	}
	
	//img src
	if(!empty($imagestore) && !empty($imagestore['id'])){
		$counter_img = $imagestore['id'];
		$imgSrc = wp_get_attachment_image_src($counter_img , $imageSize);
		$imgSrc = $imgSrc[0];
	}else if(!empty($imagestore['url'])){
		$imgSrc = $imagestore['url'];
	}else{
		$imgSrc = $imagestore;
	}
			
	$output = '';
    $output .= '<div class="tpgb-flipbox '.esc_attr($Sliderclass).' list-'.esc_attr($layoutType).' flip-box-style-1 tpgb-block-'.esc_attr($block_id).' '.esc_attr($blockClass).'" data-carousel-option=\'' . json_encode($carousel_settings) . '\'>';
		if($layoutType=='listing'){
			$output .= '<div class="flip-box-inner content_hover_effect ">';
				$output .= '<div class="flip-box-bg-box">';
					$output .= '<div class="service-flipbox flip-'.esc_attr($flipType).'" height-full"}>';
						$output .= '<div class="service-flipbox-holder height-full text-center perspective bezier-1">';
							$output .= '<div class="service-flipbox-front bezier-1 no-backface origin-center">';
								$output .= '<div class="service-flipbox-content width-full">';
									if($iconType=='icon'){
										$output .= '<span class="service-icon icon-'.esc_attr($iconStyle).'">';
											$output .= '<i class="'.esc_attr($iconStore).'"></i>';
										$output .= '</span>';
									}
									if($iconType=='img' && !empty($imagestore)){
										$output .= '<img src='.esc_url($imgSrc).' class="service-img" alt="'.esc_attr__('FlipBox','tpgb').'"/>';
									}
									$output .= '<div class="service-content">';
										$output .= '<div class="service-title">'.wp_kses_post($title).'</div>';
									$output .= '</div>';
								$output .= '</div>';
								$output .= '<div class="flipbox-front-overlay"></div>';
							$output .= '</div>';
							$output .= '<div class="service-flipbox-back fold-back-'.esc_attr($flipType).' no-backface bezier-1 origin-center">';
								$output .= '<div class="service-flipbox-content width-full">';
									$output .= '<div class="service-desc">'.wp_kses_post($description).'</div>';
									if(!empty($backBtn)){
										$output .= tpgb_getButtonRender($attributes);
									}
								$output .= '</div>';
								$output .= '<div class="flipbox-back-overlay"></div>';
							$output .= '</div>';
						$output .= '</div>';
					$output .= '</div>';
				$output .= '</div>';
			$output .= '</div>';
		}
		if($layoutType=='carousel'){
			if( ( isset($showArrows['md']) && !empty($showArrows['md']) ) || ( isset($showArrows['sm']) && !empty($showArrows['sm']) ) || ( isset($showArrows['xs']) && !empty($showArrows['xs']) ) ){
				$output .= Tp_Blocks_Helper::tpgb_carousel_arrow($arrowsStyle, '');
			}
			$output .= '<div class="post-loop-inner splide__track">';
			$output .= '<div class="splide__list">';
			if(!empty($flipcarousel)){
				foreach ( $flipcarousel as $index => $item ) {
					$count++;
					$output .= '<div class="splide__slide flip-box-inner content_hover_effect tp-repeater-item-'.esc_attr($item['_key']).'" data-index="'.esc_attr($count).'">';
						$output .= '<div class="flip-box-bg-box">';
							$output .= '<div class="service-flipbox flip-'.esc_attr($flipType).'" height-full"}>';
								$output .= '<div class="service-flipbox-holder height-full text-center perspective bezier-1">';
									$output .= '<div class="service-flipbox-front bezier-1 no-backface origin-center">';
										$output .= '<div class="service-flipbox-content width-full">';
											if($item['iconType']=='icon'){
												$output .= '<span class="service-icon icon-'.esc_attr($iconStyle).'"></i>';
													$output .= '<i class="'.esc_attr($item['iconStore']).'"></i>';
												$output .= '</span>';
											}
											if($item['iconType']=='image' && !empty($item['imagestore'])){
												$imageSize = (!empty($item['imageSize'])) ? $item['imageSize'] : 'thumbnail';
												if(!empty($item['imagestore']['id'])){
													$imgSrc = wp_get_attachment_image_src($item['imagestore']['id'] , $imageSize);
													$imgSrc = $imgSrc[0];
												}else if(!empty($item['imagestore']['url'])){
													$imgSrc = $item['imagestore']['url'];
												}
												$output .= '<img src='.esc_url($imgSrc).' class="service-img" alt="'.esc_attr__('FlipBox','tpgb').'"/>';
											}
											$output .= '<div class="service-content">';
												$output .= '<div class="service-title">'.wp_kses_post($item['title']).'</div>';
											$output .= '</div>';
										$output .= '</div>';
										$output .= '<div class="flipbox-front-overlay"></div>';
									$output .= '</div>';
									$output .= '<div class="service-flipbox-back fold-back-'.esc_attr($flipType).' no-backface bezier-1 origin-center">';
										$output .= '<div class="service-flipbox-content width-full">';
											$output .= '<div class="service-desc">'.wp_kses_post($item['description']).'</div>';
											if(!empty($backCarouselBtn)){
												$output .=tpgb_getButtonRender($attributes,$item['btnUrl'],$item['btnText']);
											}
										$output .= '</div>';
										$output .= '<div class="flipbox-back-overlay"></div>';
									$output .= '</div>';
								$output .= '</div>';
							$output .= '</div>';
						$output .= '</div>';
					$output .= '</div>';
				}
			}
			$output .= '</div>';
			$output .= '</div>';
		}
    $output .= '</div>';
	
	$output = Tpgb_Blocks_Global_Options::block_Wrap_Render($attributes, $output);
	if($layoutType=='carousel' && !empty($arrowCss) ){
		$output .= $arrowCss;
	}
    return $output;
}

function tpgb_getButtonRender($attributes,$itemBtnUrl='',$itemBtnText=''){
	$layoutType = (!empty($attributes['layoutType'])) ? $attributes['layoutType'] : 'listing';
	$btnStyle = (!empty($attributes['btnStyle'])) ? $attributes['btnStyle'] : 'style-7';
	$btnCarouselStyle = (!empty($attributes['btnCarouselStyle'])) ? $attributes['btnCarouselStyle'] : 'style-7';
	$btnIconType = (!empty($attributes['btnIconType'])) ? $attributes['btnIconType'] : 'none';
	$btnCarouselIconType = (!empty($attributes['btnCarouselIconType'])) ? $attributes['btnCarouselIconType'] : 'none';
	$btnIconName = (!empty($attributes['btnIconName'])) ? $attributes['btnIconName'] : '';
	$btnCarouselIconName = (!empty($attributes['btnCarouselIconName'])) ? $attributes['btnCarouselIconName'] : '';
	$btnIconPosition = (!empty($attributes['btnIconPosition'])) ? $attributes['btnIconPosition'] : 'after';
	$btnCarouselIconPosition = (!empty($attributes['btnCarouselIconPosition'])) ? $attributes['btnCarouselIconPosition'] : 'after';
	$btnText = (!empty($attributes['btnText'])) ? $attributes['btnText'] : '';
	$btnUrl = (!empty($attributes['btnUrl'])) ? $attributes['btnUrl'] : '';
	
	$NewBtnText = ($layoutType=='carousel') ? $itemBtnText : $btnText;
	$getBtnText = '<div class="btn-text">'.esc_html($NewBtnText).'</div>';
	
	$getbutton = '';
	
	$NewBtnStyle = ($layoutType=='carousel') ? $btnCarouselStyle : $btnStyle;
	$NewBtnType = ($layoutType=='carousel' ) ? $btnCarouselIconType : $btnIconType;
	$NewBtnIconPosition = ($layoutType=='carousel' ) ? $btnCarouselIconPosition : $btnIconPosition;
	$NewBtnIconName = ($layoutType=='carousel' ) ? $btnCarouselIconName : $btnIconName;
	$NewBtnUrl = ($layoutType=='carousel') ? $itemBtnUrl : $btnUrl;
	$target = (!empty($NewBtnUrl['target']) ? '_blank' : '' ) ;
	$nofollow = (!empty($NewBtnUrl['nofollow'])) ? 'nofollow' : '';
	$getbutton .= '<div class="tpgb-adv-button button-'.esc_attr($NewBtnStyle).'">';
		$getbutton .= '<a href="'.esc_url($NewBtnUrl['url']).'" class="button-link-wrap" role="button" target="'.esc_attr($target).'" rel="'.esc_attr($nofollow).'">';
		if($NewBtnStyle == 'style-8'){
			if($NewBtnIconPosition == 'before'){
				if($NewBtnType == 'icon'){
					$getbutton .= '<span class="btn-icon  button-'.esc_attr($NewBtnIconPosition).'">';
						$getbutton .= '<i class="'.esc_attr($NewBtnIconName).'"></i>';
					$getbutton .= '</span>';
				}
				$getbutton .= $getBtnText;
			}
			if($NewBtnIconPosition == 'after'){
				$getbutton .= $getBtnText;
				if($NewBtnType == 'icon'){
					$getbutton .= '<span class="btn-icon  button-'.esc_attr($NewBtnIconPosition).'">';
						$getbutton .= '<i class="'.esc_attr($NewBtnIconName).'"></i>';
					$getbutton .= '</span>';
				}
			}
		}
		if($NewBtnStyle == 'style-7' || $NewBtnStyle == 'style-9' ){
			$getbutton .= $getBtnText;
			
			$getbutton .= '<span class="button-arrow">';
			if($NewBtnStyle == 'style-7'){
				$getbutton .= '<span class="btn-right-arrow"><i class="fas fa-chevron-right"></i></span>';
			}
			if($NewBtnStyle == 'style-9'){
				$getbutton .= '<i class="btn-show fas fa-chevron-right"></i>';
				$getbutton .= '<i class="btn-hide fas fa-chevron-right"></i>';
			}
			$getbutton .= '</span>';
		}
		$getbutton .= '</a>';
	$getbutton .= '</div>';
	return $getbutton;
}

/**
 * Render for the server-side
 */
function tpgb_flipbox() {
	$globalBgOption = Tpgb_Blocks_Global_Options::load_bg_options();
	$globalpositioningOption = Tpgb_Blocks_Global_Options::load_positioning_options();
	$globalPlusExtrasOption = Tpgb_Blocks_Global_Options::load_plusextras_options();
	$carousel_options = Tpgb_Blocks_Global_Options::carousel_options();
	$attributesOptions = array(
		'block_id' => [
			'type' => 'string',
			'default' => '',
		],
		'layoutType' => [
			'type' => 'string',
			'default' => 'listing',	
		],
		'flipType' => [
			'type' => 'string',
			'default' => 'horizontal',	
		],
		'boxHeight' => [
			'type' => 'object',
			'default' => (object) [ 
				'md' => '',
				"unit" => 'px',
			],
			'style' => [
				(object) [
					'selector' => '{{PLUS_WRAP}}.tpgb-flipbox .flip-box-inner .service-flipbox, {{PLUS_WRAP}}.tpgb-flipbox .flip-box-inner .service-flipbox-front, {{PLUS_WRAP}}.tpgb-flipbox .flip-box-inner .service-flipbox-back{ min-height: {{boxHeight}}; }',
				],
			],
		],
		'backCarouselBtn' => [
			'type' => 'boolean',
			'default' => false,	
		],
		'btnCarouselStyle' => [
			'type' => 'string',
			'default' => 'style-7',	
		],
		'btnCarouselIconType'  => [
			'type' => 'string' ,
			'default' => 'none',	
		],
			
		'btnCarouselIconName' => [
			'type'=> 'string',
			'default'=> '',
		],
		'btnCarouselIconPosition' => [
			'type'=> 'string',
			'default'=> 'after',
		],
		'flipcarousel' => [
			'type'=> 'array',
			'repeaterField' => [
				(object) [
					'title' => [
						'type' => 'string',
						'default' => 'Special Feature'
					],
					'description' => [
						'type' => 'string',
						'default' => 'Lookout flogging bilge rat main sheet bilge water nipper fluke to go on account heave down clap of thunder. Reef sails six pounders skysail code of conduct sloop cog Yellow Jack gunwalls grog blossom starboard.'
					],
					'btnText' => [
						'type' => 'string',
						'default' => 'Read more',	
					],
					'btnUrl' => [
						'type'=> 'object',
						'default'=> [
							'url' => '',
							'target' => '',
							'nofollow' => ''
						],
					],
					'iconType' => [
						'type' => 'string',
						'default' => 'icon'
					],
					'iconStore' => [
						'type'=> 'string',
						'default' => 'fas fa-box-open'
					],
					'imagestore' => [
						'type' => 'object',
						'default' => [
							'url' => TPGB_ASSETS_URL.'assets/images/tpgb-placeholder.jpg',
						],
					],
					'nmlBG' => [
						'type' => 'object',
						'default' => (object) [
							'openBg'=> 0,
							'bgType' => 'color',
							'bgDefaultColor' => '',
							'bgGradient' => (object) [ 'color1' => '#16d03e', 'color2' => '#1f91f3', 'type' => 'linear', 'direction' => '90', 'start' => 5, 'stop' => 80, 'radial' => 'center', 'clip' => false ],
							'overlayBg' => '',
							'overlayBgOpacity' => '',
							'bgGradientOpacity' => ''
						],
						'style' => [
							(object) [
								'selector' => '{{PLUS_WRAP}} .flip-box-inner{{TP_REPEAT_ID}} .service-flipbox-front',
							],
						],
					],
					'hvrBG' => [
						'type' => 'object',
						'default' => (object) [
							'openBg'=> 0,
							'bgType' => 'color',
							'bgDefaultColor' => '',
							'bgGradient' => (object) [ 'color1' => '#16d03e', 'color2' => '#1f91f3', 'type' => 'linear', 'direction' => '90', 'start' => 5, 'stop' => 80, 'radial' => 'center', 'clip' => false ],
							'overlayBg' => '',
							'overlayBgOpacity' => '',
							'bgGradientOpacity' => ''
						],
						'style' => [
							(object) [
								'selector' => '{{PLUS_WRAP}} {{TP_REPEAT_ID}} .service-flipbox-back',
							],
						],
					],
					'overNmlBG' => [
						'type' => 'string',
						'default' => '',
						'style' => [
							(object) [
								'selector' => '{{PLUS_WRAP}} {{TP_REPEAT_ID}} .flipbox-front-overlay{ background: {{overNmlBG}}; }',
							],
						],
					],
					'overHvrBG' => [
						'type' => 'string',
						'default' => '',
						'style' => [
							(object) [
								'selector' => '{{PLUS_WRAP}} {{TP_REPEAT_ID}} .flipbox-back-overlay{ background: {{overHvrBG}}; }',
							],
						],
					],
				],
			],
			'default' => [
				[
					'_key' => '0',
					'title' => 'Special Feature 1',
					'description' => 'Lookout flogging bilge rat main sheet bilge water nipper fluke to go on account heave down clap of thunder. Reef sails six pounders skysail code of conduct sloop cog Yellow Jack gunwalls grog blossom starboard.',
					'iconType' => 'icon',
					'iconStore'=> 'fas fa-box-open',
					'btnText'=> 'Read More',
					'btnUrl' => ['url'  => ''],
					'imagestore' => [
						'url' => TPGB_ASSETS_URL.'assets/images/tpgb-placeholder.jpg',
					],
				],
				[
					'_key' => '1',
					'title' => 'Special Feature 2',
					'description' => 'Lookout flogging bilge rat main sheet bilge water nipper fluke to go on account heave down clap of thunder. Reef sails six pounders skysail code of conduct sloop cog Yellow Jack gunwalls grog blossom starboard.',
					'iconType' => 'icon',
					'iconStore'=> 'fas fa-box-open',
					'btnText'=> 'Read More',
					'btnUrl' => ['url'  => ''],
					'imagestore' => [
						'url' => TPGB_ASSETS_URL.'assets/images/tpgb-placeholder.jpg',
					],
				] 
			]
		],
		'title' => [
			'type' => 'string',
			'default' => 'Special Feature',	
		],
		'iconType' => [
			'type' => 'string',
			'default' => 'icon',	
		],
		'iconStore' => [
			'type'=> 'string',
			'default'=> 'fas fa-box-open',
		],
		'imagestore' => [
			'type' => 'object',
			'default' => [
				'url' => TPGB_ASSETS_URL.'assets/images/tpgb-placeholder.jpg',
			],
		],
		'imageSize' => [
			'type' => 'string',
			'default' => 'thumbnail',	
		],
		'description' => [
			'type' => 'string',
			'default' => 'Lookout flogging bilge rat main sheet bilge water nipper fluke to go on account heave down clap of thunder. Reef sails six pounders skysail code of conduct sloop cog Yellow Jack gunwalls grog blossom starboard.',	
		],
		'backBtn' => [
			'type' => 'boolean',
			'default' => false,	
		],
		'btnStyle' => [
			'type' => 'string',
			'default' => 'style-7',	
		],
		'btnText' => [
			'type' => 'string',
			'default' => 'Read more',	
		],
		'btnUrl' => [
			'type'=> 'object',
			'default'=> [
				'url' => '',
				'target' => '',
				'nofollow' => ''
			],
		],
		'btnIconType'  => [
			'type' => 'string' ,
			'default' => 'none',	
		],
			
		'btnIconName' => [
			'type'=> 'string',
			'default'=> '',
		],
		'btnIconPosition' => [
			'type'=> 'string',
			'default'=> 'after',
		],
		
		'iconStyle' => [
			'type' => 'string',
			'default' => 'square',	
		],
		'iconSize' => [
			'type' => 'object',
			'default' => [ 
				'md' => '',
				"unit" => 'px',
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'layoutType', 'relation' => '==', 'value' => 'listing' ],(object)['key' => 'iconType', 'relation' => '==', 'value' => 'icon' ]],
					'selector' => '{{PLUS_WRAP}} .flip-box-inner .service-icon{ font-size: {{iconSize}}; }',
				],
				(object) [
					'condition' => [(object) ['key' => 'layoutType', 'relation' => '==', 'value' => 'carousel' ]],
					'selector' => '{{PLUS_WRAP}} .flip-box-inner .service-icon{ font-size: {{iconSize}}; }',
				],
			],
		],
		'iconWidth' => [
			'type' => 'object',
			'default' => [ 
				'md' => '',
				"unit" => 'px',
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'layoutType', 'relation' => '==', 'value' => 'listing' ],(object)['key' => 'iconType', 'relation' => '==', 'value' => 'icon' ]],
					'selector' => '{{PLUS_WRAP}} .flip-box-inner .service-icon{ width: {{iconWidth}}; height: {{iconWidth}}; line-height: {{iconWidth}}; }',
				],
				(object) [
					'condition' => [(object) ['key' => 'layoutType', 'relation' => '==', 'value' => 'carousel' ]],
					'selector' => '{{PLUS_WRAP}} .flip-box-inner .service-icon{ width: {{iconWidth}}; height: {{iconWidth}}; line-height: {{iconWidth}}; }',
				],
			],
		],
		'icnNmlColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'layoutType', 'relation' => '==', 'value' => 'listing' ],(object)['key' => 'iconType', 'relation' => '==', 'value' => 'icon' ]],
					'selector' => '{{PLUS_WRAP}} .flip-box-inner .service-icon{ color: {{icnNmlColor}}; }',
				],
				(object) [
					'condition' => [(object) ['key' => 'layoutType', 'relation' => '==', 'value' => 'carousel' ]],
					'selector' => '{{PLUS_WRAP}} .flip-box-inner .service-icon{ color: {{icnNmlColor}}; }',
				],
			],
		],
		'icnHvrColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [ 
					'condition' => [(object) ['key' => 'layoutType', 'relation' => '==', 'value' => 'listing' ],(object)['key' => 'iconType', 'relation' => '==', 'value' => 'icon' ]],
					'selector' => '{{PLUS_WRAP}} .flip-horizontal:hover .service-icon{ color: {{icnHvrColor}}; }',
				],
				(object) [ 
					'condition' => [(object) ['key' => 'layoutType', 'relation' => '==', 'value' => 'carousel' ]],
					'selector' => '{{PLUS_WRAP}} .flip-horizontal:hover .service-icon{ color: {{icnHvrColor}}; }',
				],
			],
		],
		'icnNormalBG' => [
			'type' => 'object',
			'default' => (object) [
				'openBg'=> 0,
				'bgType' => 'color',
				'bgDefaultColor' => '',
				'bgGradient' => (object) [ 'color1' => '#16d03e', 'color2' => '#1f91f3', 'type' => 'linear', 'direction' => '90', 'start' => 5, 'stop' => 80, 'radial' => 'center', 'clip' => false ],
				'overlayBg' => '',
				'overlayBgOpacity' => '',
				'bgGradientOpacity' => ''
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'layoutType', 'relation' => '==', 'value' => 'listing' ],(object)['key' => 'iconType', 'relation' => '==', 'value' => 'icon' ]],
					'selector' => '{{PLUS_WRAP}} .flip-box-inner .service-icon',
				],
				(object) [
					'condition' => [(object) ['key' => 'layoutType', 'relation' => '==', 'value' => 'carousel' ]],
					'selector' => '{{PLUS_WRAP}} .flip-box-inner .service-icon',
				],
			],
		],
		'icnHoverBG' => [
			'type' => 'object',
			'default' => (object) [
				'openBg'=> 0,
				'bgType' => 'color',
				'bgDefaultColor' => '',
				'bgGradient' => (object) [ 'color1' => '#16d03e', 'color2' => '#1f91f3', 'type' => 'linear', 'direction' => '90', 'start' => 5, 'stop' => 80, 'radial' => 'center', 'clip' => false ],
				'overlayBg' => '',
				'overlayBgOpacity' => '',
				'bgGradientOpacity' => ''
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'layoutType', 'relation' => '==', 'value' => 'listing' ],(object)['key' => 'iconType', 'relation' => '==', 'value' => 'icon' ]],
					'selector' => '{{PLUS_WRAP}} .flip-horizontal:hover .service-icon',
				],
				(object) [
					'condition' => [(object) ['key' => 'layoutType', 'relation' => '==', 'value' => 'carousel' ]],
					'selector' => '{{PLUS_WRAP}} .flip-horizontal:hover .service-icon',
				],
			],
		],
		'nmlBColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [
						(object) ['key' => 'layoutType', 'relation' => '==', 'value' => 'listing' ],(object) ['key' => 'iconType', 'relation' => '==', 'value' => 'icon' ],(object) [ 'key' => 'iconStyle', 'relation' => '==', 'value' => ['square','rounded'] ]
					],
					'selector' => '{{PLUS_WRAP}} .flip-box-inner .service-icon{ border-color: {{nmlBColor}}; }',
				],
				(object) [
					'condition' => [
						(object) ['key' => 'layoutType', 'relation' => '==', 'value' => 'carousel' ],(object) [ 'key' => 'iconStyle', 'relation' => '==', 'value' => ['square','rounded'] ]
					],
					'selector' => '{{PLUS_WRAP}} .flip-box-inner .service-icon{ border-color: {{nmlBColor}}; }',
				],
			],
		],
		'hvrBColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [ 
					'condition' => [
						(object) ['key' => 'layoutType', 'relation' => '==', 'value' => 'listing' ],(object) ['key' => 'iconType', 'relation' => '==', 'value' => 'icon' ],(object) [ 'key' => 'iconStyle', 'relation' => '==', 'value' => ['square','rounded'] ]
					],
					'selector' => '{{PLUS_WRAP}} .flip-horizontal:hover .service-icon{ border-color: {{hvrBColor}}; }',
				],
				(object) [ 
					'condition' => [
						(object) ['key' => 'layoutType', 'relation' => '==', 'value' => 'carousel' ],(object) [ 'key' => 'iconStyle', 'relation' => '==', 'value' => ['square','rounded'] ]
					],
					'selector' => '{{PLUS_WRAP}} .flip-horizontal:hover .service-icon{ border-color: {{hvrBColor}}; }',
				],
			],
		],
		'nmlIcnBRadius' => [
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
						(object) ['key' => 'layoutType', 'relation' => '==', 'value' => 'listing' ],(object) ['key' => 'iconType', 'relation' => '==', 'value' => 'icon' ],
						(object) [ 'key' => 'iconStyle', 'relation' => '==', 'value' => ['none','square','rounded'] ]
					],
					'selector' => '{{PLUS_WRAP}} .flip-box-inner .service-icon{border-radius: {{nmlIcnBRadius}};}',
				],
				(object) [
					'condition' => [
						(object) ['key' => 'layoutType', 'relation' => '==', 'value' => 'carousel' ],(object) [ 'key' => 'iconStyle', 'relation' => '==', 'value' => ['none','square','rounded'] ]
					],
					'selector' => '{{PLUS_WRAP}} .flip-box-inner .service-icon{border-radius: {{nmlIcnBRadius}};}',
				],
			],
		],
		'hvrIcnBRadius' => [
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
					'condition' => [(object) ['key' => 'layoutType', 'relation' => '==', 'value' => 'listing' ],(object) ['key' => 'iconType', 'relation' => '==', 'value' => 'icon' ],
						(object) [ 'key' => 'iconStyle', 'relation' => '==', 'value' => ['none','square','rounded'] ]
					],
					'selector' => '{{PLUS_WRAP}} .flip-horizontal:hover .service-icon{border-radius: {{hvrIcnBRadius}};}',
				],
				(object) [
					'condition' => [
						(object) ['key' => 'layoutType', 'relation' => '==', 'value' => 'carousel' ],(object) [ 'key' => 'iconStyle', 'relation' => '==', 'value' => ['none','square','rounded'] ]
					],
					'selector' => '{{PLUS_WRAP}} .flip-horizontal:hover .service-icon{border-radius: {{hvrIcnBRadius}};}',
				],
			],
		],
		'nmlIcnShadow' => [
			'type' => 'object',
			'default' => (object) [
				'openShadow' => 0,
				'inset' => 0,
				'horizontal' => 0,
				'vertical' => 4,
				'blur' => 8,
				'spread' => 0,
				'color' => "rgba(0,0,0,0.40)",
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'layoutType', 'relation' => '==', 'value' => 'listing' ],(object) ['key' => 'iconType', 'relation' => '==', 'value' => 'icon' ]],
					'selector' => '{{PLUS_WRAP}} .flip-box-inner .service-icon',
				],
				(object) [
					'condition' => [(object) ['key' => 'layoutType', 'relation' => '==', 'value' => 'carousel' ]],
					'selector' => '{{PLUS_WRAP}} .flip-box-inner .service-icon',
				],
			],
		],
		'hvrIcnShadow' => [
			'type' => 'object',
			'default' => (object) [
				'openShadow' => 0,
				'inset' => 0,
				'horizontal' => 0,
				'vertical' => 4,
				'blur' => 8,
				'spread' => 0,
				'color' => "rgba(0,0,0,0.40)",
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'layoutType', 'relation' => '==', 'value' => 'listing' ],(object) ['key' => 'iconType', 'relation' => '==', 'value' => 'icon' ]],
					'selector' => '{{PLUS_WRAP}} .flip-horizontal:hover .service-icon',
				],
				(object) [
					'condition' => [(object) ['key' => 'layoutType', 'relation' => '==', 'value' => 'carousel' ]],
					'selector' => '{{PLUS_WRAP}} .flip-horizontal:hover .service-icon',
				],
			],
		],
		'imgWidth' => [
			'type' => 'object',
			'default' => [ 
				'md' => '',
				"unit" => 'px',
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'layoutType', 'relation' => '==', 'value' => 'listing' ],(object)['key' => 'iconType', 'relation' => '==', 'value' => 'img' ]],
					'selector' => '{{PLUS_WRAP}} .flip-box-inner .service-img{ max-width: {{imgWidth}}; }',
				],
				(object) [
					'condition' => [(object) ['key' => 'layoutType', 'relation' => '==', 'value' => 'carousel' ]],
					'selector' => '{{PLUS_WRAP}} .flip-box-inner .service-img{ max-width: {{imgWidth}}; }',
				],
			],
		],
		'titleTypo' => [
			'type'=> 'object',
			'default'=> (object) [
				'openTypography' => 0,
				'size' => [ 'md' => '', 'unit' => 'px' ],
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'title', 'relation' => '!=', 'value' => '' ]],
					'selector' => '{{PLUS_WRAP}} .flip-box-inner .service-title',
				],
			],
		],
		'titleNmlColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'title', 'relation' => '!=', 'value' => '' ]],
					'selector' => '{{PLUS_WRAP}} .flip-box-inner .service-title{ color: {{titleNmlColor}}; }',
				],
			],
		],
		'titleHvrColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [ 
					'condition' => [(object) ['key' => 'title', 'relation' => '!=', 'value' => '' ]],
					'selector' => '{{PLUS_WRAP}} .flip-horizontal:hover .service-title{ color: {{titleHvrColor}}; }',
				],
			],
		],
		'titleTopSpace' => [
			'type' => 'object',
			'default' => [ 
				'md' => '',
				"unit" => 'px',
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'title', 'relation' => '!=', 'value' => '' ]],
					'selector' => '{{PLUS_WRAP}}.flip-box-style-1 .flip-box-inner .service-title{ margin-top: {{titleTopSpace}}; }',
				],
			],
		],
		'titleBottomSpace' => [
			'type' => 'object',
			'default' => [ 
				'md' => '',
				"unit" => 'px',
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'title', 'relation' => '!=', 'value' => '' ]],
					'selector' => '{{PLUS_WRAP}}.flip-box-style-1 .flip-box-inner .service-title{ margin-bottom: {{titleBottomSpace}}; }',
				],
			],
		],
		'descTypo' => [
			'type'=> 'object',
			'default'=> (object) [
				'openTypography' => 0,
				'size' => [ 'md' => '', 'unit' => 'px' ],
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'description', 'relation' => '!=', 'value' => '' ]],
					'selector' => '{{PLUS_WRAP}} .flip-box-inner .service-desc',
				],
			],
		],
		'descColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'description', 'relation' => '!=', 'value' => '' ]],
					'selector' => '{{PLUS_WRAP}} .flip-box-inner .service-desc{ color: {{descColor}}; }',
				],
			],
		],
		'backBtnTypo' => [
			'type'=> 'object',
			'default'=> (object) [
				'openTypography' => 0,
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'backBtn', 'relation' => '==', 'value' => true]],
					'selector' => '{{PLUS_WRAP}} .tpgb-adv-button .button-link-wrap',
				],
				(object) [
					'condition' => [(object) ['key' => 'backCarouselBtn', 'relation' => '==', 'value' => true]],
					'selector' => '{{PLUS_WRAP}} .tpgb-adv-button .button-link-wrap',
				],
			],
		],
		'backBtnTextColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'backBtn', 'relation' => '==', 'value' => true]],
					'selector' => '{{PLUS_WRAP}} .tpgb-adv-button .button-link-wrap{ color: {{backBtnTextColor}}; }',
				],
				(object) [
					'condition' => [(object) ['key' => 'backCarouselBtn', 'relation' => '==', 'value' => true]],
					'selector' => '{{PLUS_WRAP}} .tpgb-adv-button .button-link-wrap{ color: {{backBtnTextColor}}; }',
				],
				(object) [
					'condition' => [
						(object) ['key' => 'backBtn', 'relation' => '==', 'value' => true],
						['key' => 'btnStyle' , 'relation' => '==', 'value' => 'style-7']
					],
					'selector' => '{{PLUS_WRAP}} .tpgb-adv-button.button-style-7 .button-link-wrap:after{ border-color: {{backBtnTextColor}}; }',
				],
				(object) [
					'condition' => [
						(object) ['key' => 'backCarouselBtn', 'relation' => '==', 'value' => true],
						['key' => 'btnCarouselStyle' , 'relation' => '==', 'value' => 'style-7']
					],
					'selector' => '{{PLUS_WRAP}} .tpgb-adv-button.button-style-7 .button-link-wrap:after{ border-color: {{backBtnTextColor}}; }',
				],
			],
		],
		'backBThoverColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'backBtn' , 'relation' => '==', 'value' =>  true]],
					'selector' => '{{PLUS_WRAP}} .tpgb-adv-button .button-link-wrap:hover{ color: {{backBThoverColor}}; }',
				],
				(object) [
					'condition' => [(object) ['key' => 'backCarouselBtn' , 'relation' => '==', 'value' =>  true]],
					'selector' => '{{PLUS_WRAP}} .tpgb-adv-button .button-link-wrap:hover{ color: {{backBThoverColor}}; }',
				],
			],
		],
		'backBtnSpace' => [
			'type' => 'object',
			'default' => (object) [ 
				'md' => '',
				"unit" => 'px',
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'backBtn' , 'relation' => '==', 'value' =>  true]],
					'selector' => '{{PLUS_WRAP}} .tpgb-adv-button{ margin-top: {{backBtnSpace}}; }',
				],
				(object) [
					'condition' => [(object) ['key' => 'backCarouselBtn' , 'relation' => '==', 'value' =>  true]],
					'selector' => '{{PLUS_WRAP}} .tpgb-adv-button{ margin-top: {{backBtnSpace}}; }',
				],
			],
		],
		'backBtnbottomSpace' => [
			'type' => 'object',
			'default' => (object) [ 
				'md' => '',
				"unit" => 'px',
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'backBtn', 'relation' => '==', 'value' => true]],
					'selector' => '{{PLUS_WRAP}} .tpgb-adv-button{ margin-bottom : {{backBtnbottomSpace}}; }',
				],
				(object) [
					'condition' => [(object) ['key' => 'backCarouselBtn', 'relation' => '==', 'value' => true]],
					'selector' => '{{PLUS_WRAP}} .tpgb-adv-button{ margin-bottom : {{backBtnbottomSpace}}; }',
				],
			],
		],
		'btnIconSpacing' => [
			'type' => 'object',
			'default' => [ 
				'md' => 5,
				"unit" => 'px',
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'backBtn', 'relation' => '==', 'value' => true]],
					'selector' => '{{PLUS_WRAP}} .button-link-wrap .button-before { margin-right: {{btnIconSpacing}}; } {{PLUS_WRAP}} .button-link-wrap .button-after { margin-left: {{btnIconSpacing}}; }',
				],
				(object) [
					'condition' => [(object) ['key' => 'backCarouselBtn', 'relation' => '==', 'value' => true]],
					'selector' => '{{PLUS_WRAP}} .button-link-wrap .button-before { margin-right: {{btnIconSpacing}}; } {{PLUS_WRAP}} .button-link-wrap .button-after { margin-left: {{btnIconSpacing}}; }',
				],
			],
		],
		'btnIconSize' => [
			'type' => 'object',
			'default' => [ 
				'md' => '',
				"unit" => 'px',
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'backBtn', 'relation' => '==', 'value' => true]],
					'selector' => '{{PLUS_WRAP}} .button-link-wrap .btn-icon { font-size: {{btnIconSize}}; }',
				],
				(object) [
					'condition' => [(object) ['key' => 'backCarouselBtn', 'relation' => '==', 'value' => true]],
					'selector' => '{{PLUS_WRAP}} .button-link-wrap .btn-icon { font-size: {{btnIconSize}}; }',
				],
			],
		],
		'backBtnPadding' => [
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
					'condition' => [(object) ['key' => 'backBtn', 'relation' => '==', 'value' =>true]],
					'selector' => '{{PLUS_WRAP}} .tpgb-adv-button.button-style-8 .button-link-wrap{ padding: {{backBtnPadding}}; }',
				],
				(object) [
					'condition' => [(object) ['key' => 'backCarouselBtn', 'relation' => '==', 'value' =>true]],
					'selector' => '{{PLUS_WRAP}} .tpgb-adv-button.button-style-8 .button-link-wrap{ padding: {{backBtnPadding}}; }',
				],
			],
		],
		'backBtnNormalB' => [
			'type' => 'object',
			'default' => (object) [
				'openBorder' => 0,
				'type' => '',
				'color' => '',
				'width' => (object) [
					'md' => (object)[
						'top' => '1',
						'left' => '1',
						'bottom' => '1',
						'right' => '1',
					],
					"unit" => "px",
				],			
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'backBtn', 'relation' => '==', 'value' => true ], ['key' => 'btnStyle', 'relation' => '==', 'value' => 'style-8' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-adv-button.button-style-8 .button-link-wrap',
				],
				(object) [
					'condition' => [(object) ['key' => 'backCarouselBtn', 'relation' => '==', 'value' => true ], ['key' => 'btnCarouselStyle', 'relation' => '==', 'value' => 'style-8' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-adv-button.button-style-8 .button-link-wrap',
				],
			],
		],
		'backBtnBRadius' => [
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
					'condition' => [(object) ['key' => 'backBtn', 'relation' => '==', 'value' => true ], ['key' => 'btnStyle', 'relation' => '==', 'value' => 'style-8' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-adv-button.button-style-8 .button-link-wrap{border-radius: {{backBtnBRadius}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'backCarouselBtn', 'relation' => '==', 'value' => true ], ['key' => 'btnCarouselStyle', 'relation' => '==', 'value' => 'style-8' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-adv-button.button-style-8 .button-link-wrap{border-radius: {{backBtnBRadius}};}',
				],
			],
		],
		'backBtnBG' => [
			'type' => 'object',
			'default' => (object) [
				'openBg'=> 0,
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'backBtn', 'relation' => '==', 'value' => true ], ['key' => 'btnStyle', 'relation' => '==', 'value' => 'style-8' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-adv-button.button-style-8 .button-link-wrap',
				],
				(object) [
					'condition' => [(object) ['key' => 'backCarouselBtn', 'relation' => '==', 'value' => true ], ['key' => 'btnCarouselStyle', 'relation' => '==', 'value' => 'style-8' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-adv-button.button-style-8 .button-link-wrap',
				],
			],
		],
		'backBtnShadow' => [
			'type' => 'object',
			'default' => (object) [
				'horizontal' => 0,
				'vertical' => 8,
				'blur' => 20,
				'spread' => 1,
				'color' => "rgba(0,0,0,0.27)",
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'backBtn', 'relation' => '==', 'value' => true ], ['key' => 'btnStyle', 'relation' => '==', 'value' => 'style-8' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-adv-button .button-link-wrap',
				],
				(object) [
					'condition' => [(object) ['key' => 'backCarouselBtn', 'relation' => '==', 'value' => true ], ['key' => 'btnCarouselStyle', 'relation' => '==', 'value' => 'style-8' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-adv-button .button-link-wrap',
				],
			],
		],
		'backBtnHvrB' => [
			'type' => 'object',
			'default' => (object) [
				'openBorder' => 0,
				'type' => '',
				'color' => '',
				'width' => (object) [
					'md' => (object)[
						'top' => '1',
						'left' => '1',
						'bottom' => '1',
						'right' => '1',
					],
					"unit" => "px",
				],			
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'backBtn', 'relation' => '==', 'value' => true ], ['key' => 'btnStyle', 'relation' => '==', 'value' => 'style-8' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-adv-button.button-style-8 .button-link-wrap:hover',
				],
				(object) [
					'condition' => [(object) ['key' => 'backCarouselBtn', 'relation' => '==', 'value' => true ], ['key' => 'btnCarouselStyle', 'relation' => '==', 'value' => 'style-8' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-adv-button.button-style-8 .button-link-wrap:hover',
				],
			],
		],
		'backBtnHvrBRadius' => [
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
					'condition' => [(object) ['key' => 'backBtn', 'relation' => '==', 'value' => true ], ['key' => 'btnStyle', 'relation' => '==', 'value' => 'style-8' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-adv-button.button-style-8 .button-link-wrap:hover{border-radius: {{backBtnHvrBRadius}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'backCarouselBtn', 'relation' => '==', 'value' => true ], ['key' => 'btnCarouselStyle', 'relation' => '==', 'value' => 'style-8' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-adv-button.button-style-8 .button-link-wrap:hover{border-radius: {{backBtnHvrBRadius}};}',
				],
			],
		],
		'backBtnHvrBG' => [
			'type' => 'object',
			'default' => (object) [
				'openBg'=> 0,
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'backBtn', 'relation' => '==', 'value' => true ], ['key' => 'btnStyle', 'relation' => '==', 'value' => 'style-8' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-adv-button.button-style-8 .button-link-wrap:hover',
				],
				(object) [
					'condition' => [(object) ['key' => 'backCarouselBtn', 'relation' => '==', 'value' => true ], ['key' => 'btnCarouselStyle', 'relation' => '==', 'value' => 'style-8' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-adv-button.button-style-8 .button-link-wrap:hover',
				],
			],
		],
		'backBtnHvrShadow' => [
			'type' => 'object',
			'default' => (object) [
				'horizontal' => '',
				'vertical' => '',
				'blur' => '',
				'spread' => '',
				'color' => "rgba(0,0,0,0.27)",
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'backBtn', 'relation' => '==', 'value' => true ], ['key' => 'btnStyle', 'relation' => '==', 'value' => 'style-8' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-adv-button .button-link-wrap:hover',
				],
				(object) [
					'condition' => [(object) ['key' => 'backCarouselBtn', 'relation' => '==', 'value' => true ], ['key' => 'btnCarouselStyle', 'relation' => '==', 'value' => 'style-8' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-adv-button .button-link-wrap:hover',
				],
			],
		],
		'bgBorder' => [
			'type' => 'object',
			'default' => (object) [
				'openBorder' => 0,
				'type' => '',
				'color' => '',
				'width' => (object) [
					'md' => (object)[
						'top' => '1',
						'left' => '1',
						'bottom' => '1',
						'right' => '1',
					],
					'sm' => (object)[ ],
					'xs' => (object)[ ],
					"unit" => "px",
				],			
			],
			'style' => [
				(object) [
					'selector' => '{{PLUS_WRAP}} .flip-box-inner .service-flipbox-front, {{PLUS_WRAP}} .flip-box-inner .service-flipbox-back',
				],
			],
		],
		'bgBRadius' => [
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
					'selector' => '{{PLUS_WRAP}} .flip-box-inner .service-flipbox-front, {{PLUS_WRAP}} .flip-box-inner .service-flipbox-back, {{PLUS_WRAP}} .flipbox-front-overlay, {{PLUS_WRAP}} .flipbox-back-overlay{border-radius: {{bgBRadius}};}',
				],
			],
		],
		'normalBG' => [
			'type' => 'object',
			'default' => (object) [
				'openBg'=> 0,
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'layoutType', 'relation' => '==', 'value' => 'listing' ]],
					'selector' => '{{PLUS_WRAP}} .flip-box-inner .service-flipbox-front',
				],
			],
		],
		'hoverBG' => [
			'type' => 'object',
			'default' => (object) [
				'openBg'=> 0,
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'layoutType', 'relation' => '==', 'value' => 'listing' ]],
					'selector' => '{{PLUS_WRAP}} .flip-box-inner .service-flipbox-back',
				],
			],
		],
		'overlayNmlBG' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'layoutType', 'relation' => '==', 'value' => 'listing' ]],
					'selector' => '{{PLUS_WRAP}} .flipbox-front-overlay{ background: {{overlayNmlBG}}; }',
				],
			],
		],
		'overlayHvrBG' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'layoutType', 'relation' => '==', 'value' => 'listing' ]],
					'selector' => '{{PLUS_WRAP}} .flipbox-back-overlay{ background: {{overlayHvrBG}}; }',
				],
			],
		],
		'bgNmlShadow' => [
			'type' => 'object',
			'default' => (object) [
				'openShadow' => 0,
				'inset' => 0,
				'horizontal' => 0,
				'vertical' => 4,
				'blur' => 8,
				'spread' => 0,
				'color' => "rgba(0,0,0,0.40)",
			],
			'style' => [
				(object) [
					'selector' => '{{PLUS_WRAP}} .flip-box-inner .service-flipbox-front',
				],
			],
		],
		'bgHvrShadow' => [
			'type' => 'object',
			'default' => (object) [
				'openShadow' => 0,
				'inset' => 0,
				'horizontal' => 0,
				'vertical' => 4,
				'blur' => 8,
				'spread' => 0,
				'color' => "rgba(0,0,0,0.40)",
			],
			'style' => [
				(object) [
					'selector' => '{{PLUS_WRAP}} .flip-box-inner .service-flipbox-back',
				],
			],
		],
		
	);
	$attributesOptions = array_merge($attributesOptions, $carousel_options, $globalBgOption, $globalpositioningOption, $globalPlusExtrasOption);
	
	register_block_type( 'tpgb/tp-flipbox', array(
		'attributes' => $attributesOptions,
		'editor_script' => 'tpgb-block-editor-js',
		'editor_style'  => 'tpgb-block-editor-css',
        'render_callback' => 'tpgb_tp_flipbox_render_callback'
    ) );
}
add_action( 'init', 'tpgb_flipbox' );