<?php
/* Block : Price List
 * @since : 1.1.3
 */
defined( 'ABSPATH' ) || exit;

function tpgb_pricing_list( $attributes, $content) {
	$block_id = isset($attributes['block_id']) ? $attributes['block_id'] : '';
	$style = (!empty($attributes['style'])) ? $attributes['style'] : 'style-1';
	$boxAlign = (!empty($attributes['boxAlign'])) ? $attributes['boxAlign'] : 'top-left';
	$hoverEffect = (!empty($attributes['hoverEffect'])) ? $attributes['hoverEffect'] : 'horizontal';
	$tagField = (!empty($attributes['tagField'])) ? $attributes['tagField'] : '';
	$title = (!empty($attributes['title'])) ? $attributes['title'] : '';
	$description = (!empty($attributes['description'])) ? $attributes['description'] : '';
	$price = (!empty($attributes['price'])) ? $attributes['price'] : '';
	$imageField = (!empty($attributes['imageField'])) ? $attributes['imageField'] : '';
	$imgShape = (!empty($attributes['imgShape'])) ? $attributes['imgShape'] : 'none';
	$maskImg = (!empty($attributes['maskImg'])) ? $attributes['maskImg'] : '';
	$imageSize = (!empty($attributes['imageSize'])) ? $attributes['imageSize'] : 'thumbnail';
	
	$blockClass = Tp_Blocks_Helper::block_wrapper_classes( $attributes );
	
	$imgSrc = '';
	if(!empty($imageField) && !empty($imageField['id'])){
		$imgSrc = wp_get_attachment_image($imageField['id'] , $imageSize);
	}else if(!empty($imageField['url'])){
		$imgSrc = '<img src="'.esc_url($imageField['url']).'" alt="'.esc_attr__('food icon','tpgb').'" />';
	}
	
	$getMenuTag = '';
	$array=explode("|",$tagField);
	if(!empty($array[1])){
		foreach($array as $value){
			$getMenuTag .='<h5 class="food-menu-tag">'.esc_html($value).'</h5>';
		}
	}
	else{
		$getMenuTag .='<h5 class="food-menu-tag">'.esc_html($tagField).'</h5>';
	}
		
	$getTitle = '';
	if(!empty($title)){
		$getTitle .='<h3 class="food-menu-title">'.wp_kses_post($title).'</h3>';
	}
	$getDesc = '';
	if(!empty($description)){
		$getDesc .='<div class="food-desc">'.wp_kses_post($description).'</div>';
	}
	$getPrice = '';
	if(!empty($price)){
		$getPrice .='<h4 class="food-menu-price">'.esc_html($price).'</h4>';
	}
	$box_Align='';
	$hover_effect='';
	if($style=='style-2'){
		$box_Align=$boxAlign;
		$hover_effect=$hoverEffect;
	}
	$cssData = '' ;
	if ($imgShape=='custom' && ! empty($maskImg['url'] ) ) {
		$cssData .= '.tpgb-block-'.esc_attr($block_id).'.tpgb-pricing-list .food-img.img-custom{mask-image: url('.esc_url($maskImg['url']).');-webkit-mask-image: url('.esc_url($maskImg['url']).');}';
	}
	$output = '';
    	$output .= '<div class="tpgb-pricing-list tpgb-block-'.esc_attr($block_id).' food-menu-'.esc_attr($style).' '.esc_attr($blockClass).'">';
			$output .='<div class="food-menu-box '.esc_attr($box_Align).'">';
				if($style=='style-1'){
					if(!empty($tagField)){
						$output .=$getMenuTag;
					}
					$output .=$getTitle;
					$output .=$getDesc;
					$output .=$getPrice;
				}
				if($style=='style-2'){
					$output .='<div class="food-flipbox flip-'.esc_attr($hover_effect).' height-full">';
						$output .='<div class="food-flipbox-holder height-full perspective bezier-1">';
							$output .='<div class="food-flipbox-front bezier-1 no-backface origin-center">';
								$output .='<div class="food-flipbox-content width-full">';
								if(!empty($tagField)){
									$output .='<div class="food-menu-block">'.$getMenuTag.'</div>';
								}
									$output .='<div class="food-menu-block">'.$getTitle.'</div>';
									$output .=$getPrice;
								$output .='</div>';
							$output .='</div>';
							$output .='<div class="food-flipbox-back fold-back-'.esc_attr($hover_effect).' no-backface bezier-1 origin-center">';
								$output .='<div class="food-flipbox-content width-full ">';
									$output .='<div class="text-center">'.$getDesc.'</div>';
					$output .='</div></div></div></div>';
				}
				if($style=='style-3'){
					$output .='<div class="food-menu-flex ">';
						$output .='<div class="food-flex-line ">';
						if(!empty($imgSrc)){
							$output .='<div class="food-flex-imgs food-flex-img">';
								$output .='<div class="food-img img-'.esc_attr($imgShape).'">'; 
									$output .= $imgSrc;
								$output .='</div>';
							$output .='</div>';
						}
							$output .='<div class="food-flex-content">';
							if(!empty($tagField)){
								$output .='<div class="food-menu-block">'.$getMenuTag.'</div>';
							}
								$output .='<div class="food-title-price">';
									$output .=$getTitle;
									$output .='<div class="food-menu-divider"><div class="menu-divider no"></div></div>';
									$output .=$getPrice;
								$output .='</div>';
									$output .=$getDesc;
					$output .='</div></div></div>';
				}
		$output .='</div></div>';
		
		$output = Tpgb_Blocks_Global_Options::block_Wrap_Render($attributes, $output);
		
		if(!empty($cssData)){
			$output .= '<style>'.$cssData.'</style>';
		}
		
  	return $output;
}
/**
 * Render for the server-side
 */
function tpgb_tp_pricing_list() {
	$globalBgOption = Tpgb_Blocks_Global_Options::load_bg_options();
	$globalpositioningOption = Tpgb_Blocks_Global_Options::load_positioning_options();
	$globalPlusExtrasOption = Tpgb_Blocks_Global_Options::load_plusextras_options();
	
	$attributesOptions = array(
		'block_id' => array(
            'type' => 'string',
			'default' => '',
		),
		'style' => [
			'type' => 'string',
			'default' => 'style-1',	
		],
		'Alignment' => [
			'type' => 'object',
			'default' => 'left',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-1' ]],
					'selector' => '{{PLUS_WRAP}}.tpgb-pricing-list{ text-align: {{Alignment}}; }',
				],
			],
		],
		'boxAlign' => [
			'type' => 'string',
			'default' => 'top-left',	
		],
		'hoverEffect' => [
			'type' => 'string',
			'default' => 'horizontal',	
		],
		'title' => [
			'type' => 'string',
			'default' => 'Delicious Cup Cake',	
		],
		'tagField' => [
			'type' => 'string',
			'default' => 'Small|Medium|Large',	
		],
		'price' => [
			'type' => 'number',
			'default' => '$4.99',	
		],
		'description' => [
			'type' => 'string',
			'default' => 'Cupcake ipsum dolor. Sit amet marshmallow topping cheesecake muffin. Halvah croissant candy canes bonbon candy. Apple pie jelly beans topping carrot cake danish tart cake cheesecake. Muffin danish chocolate soufflé pastry icing bonbon oat cake. Powder cake jujubes oat cake. Lemon drops tootsie roll marshmallow halvah carrot cake.',	
		],
		'imageField' => [
			'type' => 'object',
			'default' => [
				'url' => TPGB_ASSETS_URL.'assets/images/tpgb-placeholder.jpg',
			],
		],
		'imageSize' => [
			'type' => 'string',
			'default' => 'full',	
		],
		'imgShape' => [
			'type' => 'string',
			'default' => 'none',	
		],
		'maskImg' => [
			'type' => 'object',
			'default' => [
				'url' => TPGB_ASSETS_URL.'assets/images/team-mask.png',
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
					'selector' => '{{PLUS_WRAP}}.tpgb-pricing-list .food-menu-box .food-menu-title',
				],
			],
		],
		'titleColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'title', 'relation' => '!=', 'value' => '' ]],
					'selector' => '{{PLUS_WRAP}}.tpgb-pricing-list .food-menu-title{ color: {{titleColor}}; } {{PLUS_WRAP}}.tpgb-pricing-list.food-menu-style-2 .food-menu-title{ color: {{titleColor}}; }',
				],
			],
		],
		'titleBG' => [
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
					'condition' => [(object) ['key' => 'title', 'relation' => '!=', 'value' => '' ]],
					'selector' => '{{PLUS_WRAP}}.tpgb-pricing-list .food-menu-title',
				],
			],
		],
		'titlePadding' => [
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
					'condition' => [(object) ['key' => 'title', 'relation' => '!=', 'value' => '' ]],
					'selector' => '{{PLUS_WRAP}}.tpgb-pricing-list .food-menu-box .food-menu-title{padding: {{titlePadding}};}',
				],
			],
		],
		'lineStyle' => [
			'type' => 'object',
			'default' => (object) [
				'openBorder' => 0,
				'type' => '',
				'color' => '#888',
				'width' => (object) [
					'md' => (object)[
						'top' => '',
						'left' => '',
						'bottom' => '',
						'right' => '',
					],
					'sm' => (object)[ ],
					'xs' => (object)[ ],
					"unit" => "px",
				],			
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-3' ]],
					'selector' => '{{PLUS_WRAP}}.tpgb-pricing-list.food-menu-style-3 .food-flex-line .food-menu-divider .menu-divider',
				],
			],
		],
		'tagTypo' => [
			'type'=> 'object',
			'default'=> (object) [
				'openTypography' => 0,
				'size' => [ 'md' => '', 'unit' => 'px' ],
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'tagField', 'relation' => '!=', 'value' => '' ]],
					'selector' => '{{PLUS_WRAP}}.tpgb-pricing-list .food-menu-box .food-menu-tag',
				],
			],
		],
		'tagSpace' => [
			'type' => 'object',
			'default' => (object) [ 
				'md' => '',
				"unit" => 'px',
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'tagField', 'relation' => '!=', 'value' => '' ]],
					'selector' => '{{PLUS_WRAP}}.tpgb-pricing-list .food-menu-box .food-menu-tag{ margin-right: {{tagSpace}}; } ',
				],
			],
		],
		'tagColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'tagField', 'relation' => '!=', 'value' => '' ]],
					'selector' => '{{PLUS_WRAP}}.tpgb-pricing-list .food-menu-box .food-menu-tag{ color: {{tagColor}}; }',
				],
			],
		],
		'tagBG' => [
			'type' => 'object',
			'default' => (object) [
				'openBg'=> 0,
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'tagField', 'relation' => '!=', 'value' => '' ]],
					'selector' => '{{PLUS_WRAP}}.tpgb-pricing-list .food-menu-box .food-menu-tag',
				],
			],
		],
		'tagBRadius' => [
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
					'condition' => [(object) ['key' => 'tagField', 'relation' => '!=', 'value' => '' ]],
					'selector' => '{{PLUS_WRAP}}.tpgb-pricing-list .food-menu-box .food-menu-tag{border-radius: {{tagBRadius}};}',
				],
			],
		],
		'tagPadding' => [
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
					'condition' => [(object) ['key' => 'tagField', 'relation' => '!=', 'value' => '' ]],
					'selector' => '{{PLUS_WRAP}}.tpgb-pricing-list .food-menu-box .food-menu-tag{padding: {{tagPadding}};}',
				],
			],
		],
		'priceTypo' => [
			'type'=> 'object',
			'default'=> (object) [
				'openTypography' => 0,
				'size' => [ 'md' => '', 'unit' => 'px' ],
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'price', 'relation' => '!=', 'value' => '' ]],
					'selector' => '{{PLUS_WRAP}}.tpgb-pricing-list .food-menu-box .food-menu-price',
				],
			],
		],
		'priceColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'price', 'relation' => '!=', 'value' => '' ]],
					'selector' => '{{PLUS_WRAP}}.tpgb-pricing-list .food-menu-box .food-menu-price{ color: {{priceColor}}; }',
				],
			],
		],
		'priceBG' => [
			'type' => 'object',
			'default' => (object) [
				'openBg'=> 0,
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'price', 'relation' => '!=', 'value' => '' ]],
					'selector' => '{{PLUS_WRAP}}.tpgb-pricing-list .food-menu-box .food-menu-price',
				],
			],
		],
		'priceBRadius' => [
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
					'condition' => [(object) ['key' => 'price', 'relation' => '!=', 'value' => '' ]],
					'selector' => '{{PLUS_WRAP}}.tpgb-pricing-list .food-menu-box .food-menu-price{border-radius: {{priceBRadius}};}',
				],
			],
		],
		'pricePadding' => [
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
					'condition' => [(object) ['key' => 'price', 'relation' => '!=', 'value' => '' ]],
					'selector' => '{{PLUS_WRAP}}.tpgb-pricing-list .food-menu-box .food-menu-price{padding: {{pricePadding}};}',
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
					'selector' => '{{PLUS_WRAP}}.tpgb-pricing-list .food-menu-box .food-desc',
				],
			],
		],
		'descColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'description', 'relation' => '!=', 'value' => '' ]],
					'selector' => '{{PLUS_WRAP}}.tpgb-pricing-list .food-menu-box .food-desc{ color: {{descColor}}; }',
				],
			],
		],
		'descBG' => [
			'type' => 'object',
			'default' => (object) [
				'openBg'=> 0,
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'description', 'relation' => '!=', 'value' => '' ]],
					'selector' => '{{PLUS_WRAP}}.tpgb-pricing-list .food-menu-box .food-desc',
				],
			],
		],
		'descBRadius' => [
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
					'condition' => [(object) ['key' => 'description', 'relation' => '!=', 'value' => '' ]],
					'selector' => '{{PLUS_WRAP}}.tpgb-pricing-list .food-menu-box .food-desc{border-radius: {{descBRadius}};}',
				],
			],
		],
		'descPadding' => [
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
					'condition' => [(object) ['key' => 'description', 'relation' => '!=', 'value' => '' ]],
					'selector' => '{{PLUS_WRAP}}.tpgb-pricing-list .food-menu-box .food-desc{padding: {{descPadding}};}',
				],
			],
		],
		'imgMinWidth' => [
			'type' => 'object',
			'default' => (object) [ 
				'md' => '',
				"unit" => 'px',
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-3' ] , ['key' => 'imageField', 'relation' => '!=', 'value' => '' ], ['key' => 'imageField.url', 'relation' => '!=', 'value' => '' ]],
					'selector' => '{{PLUS_WRAP}} .food-menu-box .food-flex-imgs.food-flex-img{ min-width: {{imgMinWidth}}; } ',
				],
			],
		],
		'imgMaxWidth' => [
			'type' => 'object',
			'default' => (object) [ 
				'md' => '',
				"unit" => 'px',
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-3' ] , ['key' => 'imageField', 'relation' => '!=', 'value' => '' ], ['key' => 'imageField.url', 'relation' => '!=', 'value' => '' ]],
					'selector' => '{{PLUS_WRAP}} .food-menu-box .food-flex-imgs.food-flex-img{ max-width: {{imgMaxWidth}}; } ',
				],
			],
		],
		'imgRightSpace' => [
			'type' => 'object',
			'default' => (object) [ 
				'md' => '',
				"unit" => 'px',
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-3' ] , ['key' => 'imageField', 'relation' => '!=', 'value' => '' ], ['key' => 'imageField.url', 'relation' => '!=', 'value' => '' ]],
					'selector' => '{{PLUS_WRAP}}.food-menu-style-3 .food-flex-line .food-flex-img{ margin-right: {{imgRightSpace}}; } ',
				],
			],
		],
		'imgBorder' => [
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
					'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-3' ] , ['key' => 'imageField', 'relation' => '!=', 'value' => '' ], ['key' => 'imageField.url', 'relation' => '!=', 'value' => '' ]],
					'selector' => '{{PLUS_WRAP}} .food-menu-box .food-flex-imgs.food-flex-img img',
				],
			],
		],
		'imgBRadius' => [
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
					'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-3' ] , ['key' => 'imageField', 'relation' => '!=', 'value' => '' ], ['key' => 'imageField.url', 'relation' => '!=', 'value' => '' ]],
					'selector' => '{{PLUS_WRAP}} .food-menu-box .food-flex-imgs.food-flex-img img{ border-radius: {{imgBRadius}}; } ',
				],
			],
		],
		'imgShadow' => [
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
					'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-3' ] , ['key' => 'imageField', 'relation' => '!=', 'value' => '' ], ['key' => 'imageField.url', 'relation' => '!=', 'value' => '' ]],
					'selector' => '{{PLUS_WRAP}} .food-menu-box .food-flex-imgs.food-flex-img',
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
					'condition' => [(object) ['key' => 'style', 'relation' => '!=', 'value' => 'style-2' ]],
					'selector' => '{{PLUS_WRAP}}.tpgb-pricing-list .food-menu-box',
				],
				(object) [
					'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-2' ]],
					'selector' => '{{PLUS_WRAP}}.tpgb-pricing-list .food-flipbox-front',
				],
			],
		],
		'bgNormalB' => [
			'type' => 'object',
			'default' => (object) [
				'openBorder' => 0,
				'type' => '',
				'color' => '',
				'width' => (object) [
					'md' => (object)[
						'top' => '',
						'left' => '',
						'bottom' => '',
						'right' => '',
					],
					'sm' => (object)[ ],
					'xs' => (object)[ ],
					"unit" => "px",
				],			
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'style', 'relation' => '!=', 'value' => 'style-2' ]],
					'selector' => '{{PLUS_WRAP}}.tpgb-pricing-list .food-menu-box ',
				],
				(object) [
					'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-2' ]],
					'selector' => '{{PLUS_WRAP}}.tpgb-pricing-list .food-flipbox-front',
				],
			],
		],
		'bgNmlBRadius' => [
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
					'condition' => [(object) ['key' => 'style', 'relation' => '!=', 'value' => 'style-2' ]],
					'selector' => '{{PLUS_WRAP}}.tpgb-pricing-list .food-menu-box{border-radius: {{bgNmlBRadius}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-2' ]],
					'selector' => '{{PLUS_WRAP}}.tpgb-pricing-list .food-flipbox-front{border-radius: {{bgNmlBRadius}};}',
				],
			],
		],
		'normalBGShadow' => [
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
					'condition' => [(object) ['key' => 'style', 'relation' => '!=', 'value' => 'style-2' ]],
					'selector' => '{{PLUS_WRAP}}.tpgb-pricing-list .food-menu-box ',
				],
				(object) [
					'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-2' ]],
					'selector' => '{{PLUS_WRAP}}.tpgb-pricing-list .food-flipbox-front',
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
					'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-2' ]],
					'selector' => '{{PLUS_WRAP}}.tpgb-pricing-list .food-flipbox-back',
				],
			],
		],
		'bgHoverB' => [
			'type' => 'object',
			'default' => (object) [
				'openBorder' => 0,
				'type' => '',
					'color' => '',
				'width' => (object) [
					'md' => (object)[
						'top' => '',
						'left' => '',
						'bottom' => '',
						'right' => '',
					],
					'sm' => (object)[ ],
					'xs' => (object)[ ],
					"unit" => "px",
				],			
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-2' ]],
					'selector' => '{{PLUS_WRAP}}.tpgb-pricing-list .food-flipbox-back',
				],
			],
		],
		'bgHvrBRadius' => [
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
					'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-2' ]],
					'selector' => '{{PLUS_WRAP}}.tpgb-pricing-list .food-flipbox-back{border-radius: {{bgHvrBRadius}};}  {{PLUS_WRAP}}.tpgb-pricing-list .flip-horizontal:hover .food-flipbox-front{border-radius: {{bgHvrBRadius}};}  {{PLUS_WRAP}}.tpgb-pricing-list .flip-vertical:hover .food-flipbox-front{border-radius: {{bgHvrBRadius}};}',
				],
			],
		],
		'hoverBGShadow' => [
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
					'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-2' ]],
					'selector' => '{{PLUS_WRAP}}.tpgb-pricing-list .food-flipbox-back',
				],
			],
		],
	);
	$attributesOptions = array_merge($attributesOptions,$globalBgOption,$globalpositioningOption, $globalPlusExtrasOption);
	register_block_type( 'tpgb/tp-pricing-list', array(
		'attributes' => $attributesOptions,
		'editor_script' => 'tpgb-block-editor-js',
		'editor_style'  => 'tpgb-block-editor-css',
        'render_callback' => 'tpgb_pricing_list'
    ) );
}
add_action( 'init', 'tpgb_tp_pricing_list' );