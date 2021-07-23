<?php
/* Block : Pricing Table
 * @since : 1.1.2
 */
function tpgb_tp_pricing_table_render_callback( $attributes, $content) {
    $block_id = (!empty($attributes['block_id'])) ? $attributes['block_id'] : uniqid("title");
	$style = (!empty($attributes['style'])) ? $attributes['style'] : 'style-1';
	$contentStyle = (!empty($attributes['contentStyle'])) ? $attributes['contentStyle'] : 'wysiwyg';
	$conListStyle = (!empty($attributes['conListStyle'])) ? $attributes['conListStyle'] : 'style-1';
	$wyStyle = (!empty($attributes['wyStyle'])) ? $attributes['wyStyle'] : 'style-1';
	$wyContent = (!empty($attributes['wyContent'])) ? $attributes['wyContent'] : '';
	
	$disRibbon = (!empty($attributes['disRibbon'])) ? $attributes['disRibbon'] : false;
	
	$titleStyle = (!empty($attributes['titleStyle'])) ? $attributes['titleStyle'] : 'style-1';
	$iconType = (!empty($attributes['iconType'])) ? $attributes['iconType'] : 'none';
	$iconStyle = (!empty($attributes['iconStyle'])) ? $attributes['iconStyle'] : 'square';
	$iconStore = (!empty($attributes['iconStore'])) ? $attributes['iconStore'] : 'fas fa-home';
	$imgStore = (!empty($attributes['imgStore'])) ? $attributes['imgStore']['url'] : '';
	$title = (!empty($attributes['title'])) ? $attributes['title'] : '';
	$subTitle = (!empty($attributes['subTitle'])) ? $attributes['subTitle'] : '';
	
	$priceStyle = (!empty($attributes['priceStyle'])) ? $attributes['priceStyle'] : 'style-1';
	$disPrePrice = (!empty($attributes['disPrePrice'])) ? $attributes['disPrePrice'] : false;
	$prevPreText = (!empty($attributes['prevPreText'])) ? $attributes['prevPreText'] : '';
	$prevPriceValue = (!empty($attributes['prevPriceValue'])) ? $attributes['prevPriceValue'] : '';
	$prevPostText = (!empty($attributes['prevPostText'])) ? $attributes['prevPostText'] : '';
	$preText = (!empty($attributes['preText'])) ? $attributes['preText'] : '';
	$priceValue = (isset($attributes['priceValue'])) ? $attributes['priceValue'] : '';
	$postText = (!empty($attributes['postText'])) ? $attributes['postText'] : '';
	
	$blockClass = Tp_Blocks_Helper::block_wrapper_classes( $attributes );
	
	$extBtnshow = (!empty($attributes['extBtnshow'])) ? $attributes['extBtnshow'] : false ;
	
	$hoverStyle = (!empty($attributes['hoverStyle'])) ? $attributes['hoverStyle'] : 'hover_normal';
	
	$i = 0;
	// Overlay
	$contentOverlay = '';
	$contentOverlay .= '<div class="content-overlay-bg-color"></div>';
	
	//Get Icon
	$getPriceIcon = '';
	$icon_style = '';
	if($iconType=='icon'){
		$icon_style = $iconStyle ;
	}
	$getPriceIcon .= '<div class="pricing-icon icon-'.esc_attr($icon_style).'">';
		if($iconType=='icon'){
			$getPriceIcon .= '<i class="'.esc_attr($iconStore).'"></i>';
		}
		if($iconType=='img' && !empty($imgStore)){
			$getPriceIcon .= '<img src='.esc_url($imgStore).' class="pricing-icon-img" alt="'.esc_attr__('Icon','tpgb').'"/>';
		}
	$getPriceIcon .= '</div>';
		
	//Get Title
	$getPriceTitle = '';
	if(!empty($title)){
		$getPriceTitle .= '<div class="pricing-title-wrap">';
			$getPriceTitle .= '<div class="pricing-title">'.wp_kses_post($title).'</div>';
		$getPriceTitle .= '</div>';
	}
	
	//Get Sub Title
	$getPriceSubTitle = '';
	if(!empty($subTitle)){
		$getPriceSubTitle .= '<div class="pricing-subtitle-wrap">';
			$getPriceSubTitle .= '<div class="pricing-subtitle">'.wp_kses_post($subTitle).'</div>';
		$getPriceSubTitle .= '</div>'; 
	}
	
	//Get Title-SubTitle Content
	$getTitleContent = '';
	$getTitleContent .= '<div class="pricing-title-content '.esc_attr($titleStyle).'">';
		if($iconType!='none'){
			$getTitleContent .= $getPriceIcon;
		}
		$getTitleContent .= $getPriceTitle;
		$getTitleContent .= $getPriceSubTitle;
	$getTitleContent .= '</div>';
	
	//Get Price Content
	$getPriceContent = '';
	$getPriceContent .= '<div class="pricing-price-wrap '.esc_attr($priceStyle).'">';
		if(!empty($disPrePrice)){
			$getPriceContent .= '<span class="pricing-previous-price-wrap">';
				$getPriceContent .= $prevPreText;
				$getPriceContent .= $prevPriceValue;
				$getPriceContent .= $prevPostText;
			$getPriceContent .='</span>';
		}
		if(!empty($preText)){
			$getPriceContent .= '<span class="price-prefix-text">'.esc_html($preText).'</span>';
		}
		if(isset($priceValue) && $priceValue!=''){
			$getPriceContent .= '<span class="pricing-price">'.esc_html($priceValue).'</span>'; 
		}
		if(!empty($postText)){
			$getPriceContent .= '<span class="price-postfix-text">'.esc_html($postText).'</span>';
		}
	$getPriceContent .= '</div>';
		
	//Get Button & CTA Text
	$getBtnCta = '';
	if(!empty($extBtnshow)){
		$getBtnCta .= '<div class="pricing-table-button">';
			$getBtnCta .= Tpgb_Blocks_Global_Options::load_plusButton_saves($attributes);
		$getBtnCta .= '</div>';
	}
	
	//Get wysiwyg Content
	$getWysiwygContent = '';
	if($contentStyle=='wysiwyg'){
		$getWysiwygContent .= '<div class="pricing-content-wrap content-desc '.esc_attr($wyStyle).'">';
			if($wyStyle=='style-1'){
				$getWysiwygContent .= '<hr class="border-line"/>';
			}
			$getWysiwygContent .= '<div class="pricing-content">'.wp_kses_post($wyContent).'</div>';
			$getWysiwygContent .= $contentOverlay;
		$getWysiwygContent .= '</div>';
	}
		
	$output = '';
    $output .= '<div class="tpgb-pricing-table pricing-'.esc_attr($style).' '.esc_attr($hoverStyle).' tpgb-block-'.esc_attr($block_id).' '.esc_attr($blockClass).'">';
		 $output .= '<div class="pricing-table-inner">';
		if($style=='style-1'){
			$output .= $getTitleContent;
			$output .= $getPriceContent;
			$output .= $getBtnCta;
			$output .= $getWysiwygContent;
		}
		
		$output .= '</div>';
	$output .= '</div>';

	$output = Tpgb_Blocks_Global_Options::block_Wrap_Render($attributes, $output);
	
    return $output;
}

/**
 * Render for the server-side
 */
function tpgb_pricing_table() {
	$globalBgOption = Tpgb_Blocks_Global_Options::load_bg_options();
	$globalpositioningOption = Tpgb_Blocks_Global_Options::load_positioning_options();
	$plusButton_options = Tpgb_Blocks_Global_Options::load_plusButton_options();
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
		'titleStyle' => [
			'type' => 'string',
			'default' => 'style-1',	
		],
		'title' => [
			'type' => 'string',
			'default' => 'Professional',	
		],
		'subTitle' => [
			'type' => 'string',
			'default' => 'Designed for Agency',	
		],
		'iconType' => [
			'type' => 'string',
			'default' => 'none',	
		],
		'iconStore' => [
			'type'=> 'string',
			'default'=> 'fas fa-home',
		],
		'imgStore' => [
			'type' => 'object',
			'default' => [
				'url' => TPGB_ASSETS_URL.'assets/images/tpgb-placeholder.jpg',
			],
		],
		'imgSize' => [
			'type' => 'object',
			'default' => [ 
				'md' => '',
				"unit" => 'px',
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'iconType', 'relation' => '==', 'value' => 'img' ]],
					'selector' => '{{PLUS_WRAP}} .pricing-table-inner .pricing-icon-img{ width: {{imgSize}}; }',
				],
			],
		],
		'priceStyle' => [
			'type' => 'string',
			'default' => 'style-1',	
		],
		'preText' => [
			'type' => 'string',
			'default' => '$',	
		],
		'priceValue' => [
			'type' => 'string',
			'default' => '99.99',	
		],
		'postText' => [
			'type' => 'string',
			'default' => 'Per Year',	
		],
		'disPrePrice' => [
			'type' => 'boolean',
			'default' => false,	
		],
		'prevPreText' => [
			'type' => 'string',
			'default' => '$',	
		],
		'prevPriceValue' => [
			'type' => 'string',
			'default' => '199.99',	
		],
		'prevPostText' => [
			'type' => 'string',
			'default' => '',	
		],
		'contentStyle' => [
			'type' => 'string',
			'default' => 'wysiwyg',	
		],
		'conListStyle' => [
			'type' => 'string',
			'default' => 'style-1',	
		],
		'wyStyle' => [
			'type' => 'string',
			'default' => 'style-1',	
		],
		'wyContent' => [
			'type' => 'string',
			'default' => 'All features of plan will be available here.</br>- Feature 1</br>- Feature 2</br>- Feature 3',	
		],
		
		'disRibbon' => [
			'type' => 'boolean',
			'default' => false,	
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
					'condition' => [(object) ['key' => 'iconType', 'relation' => '==', 'value' => 'icon' ]],
					'selector' => '{{PLUS_WRAP}} .pricing-table-inner .pricing-icon{ font-size: {{iconSize}}; }',
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
					'condition' => [(object) ['key' => 'iconType', 'relation' => '==', 'value' => 'icon' ]],
					'selector' => '{{PLUS_WRAP}} .pricing-table-inner .pricing-icon{ width: {{iconWidth}}; height: {{iconWidth}}; line-height: {{iconWidth}}; }',
				],
			],
		],
		'icnNmlColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'iconType', 'relation' => '==', 'value' => 'icon' ]],
					'selector' => '{{PLUS_WRAP}} .pricing-table-inner .pricing-icon{ color: {{icnNmlColor}}; }',
				],
			],
		],
		'icnHvrColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [ 
					'condition' => [(object) ['key' => 'iconType', 'relation' => '==', 'value' => 'icon' ]],
					'selector' => '{{PLUS_WRAP}} .pricing-table-inner:hover .pricing-icon{ color: {{icnHvrColor}}; }',
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
					'condition' => [(object) ['key' => 'iconType', 'relation' => '==', 'value' => 'icon' ]],
					'selector' => '{{PLUS_WRAP}} .pricing-table-inner .pricing-icon',
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
					'condition' => [(object) ['key' => 'iconType', 'relation' => '==', 'value' => 'icon' ]],
					'selector' => '{{PLUS_WRAP}} .pricing-table-inner:hover .pricing-icon',
				],
			],
		],
		'nmlBColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [
						(object) ['key' => 'iconType', 'relation' => '==', 'value' => 'icon' ],
						(object) [ 'key' => 'iconStyle', 'relation' => '==', 'value' => ['square','rounded'] ]
					],
					'selector' => '{{PLUS_WRAP}} .pricing-table-inner .pricing-icon{ border:1px solid ; border-color: {{nmlBColor}}; }',
				],
			],
		],
		'hvrBColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [ 
					'condition' => [
						(object) ['key' => 'iconType', 'relation' => '==', 'value' => 'icon' ],
						(object) [ 'key' => 'iconStyle', 'relation' => '==', 'value' => ['square','rounded'] ]
					],
					'selector' => '{{PLUS_WRAP}} .pricing-table-inner:hover .pricing-icon{ border:1px solid ; border-color: {{hvrBColor}}; }',
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
						(object) ['key' => 'iconType', 'relation' => '==', 'value' => 'icon' ],
						(object) [ 'key' => 'iconStyle', 'relation' => '==', 'value' => ['none','square','rounded'] ]
					],
					'selector' => '{{PLUS_WRAP}} .pricing-table-inner .pricing-icon{border-radius: {{nmlIcnBRadius}};}',
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
					'condition' => [
						(object) ['key' => 'iconType', 'relation' => '==', 'value' => 'icon' ],
						(object) [ 'key' => 'iconStyle', 'relation' => '==', 'value' => ['none','square','rounded'] ]
					],
					'selector' => '{{PLUS_WRAP}} .pricing-table-inner:hover .pricing-icon{border-radius: {{hvrIcnBRadius}};}',
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
					'condition' => [(object) ['key' => 'iconType', 'relation' => '==', 'value' => 'icon' ]],
					'selector' => '{{PLUS_WRAP}} .pricing-table-inner .pricing-icon',
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
					'condition' => [(object) ['key' => 'iconType', 'relation' => '==', 'value' => 'icon' ]],
					'selector' => '{{PLUS_WRAP}} .pricing-table-inner:hover .pricing-icon',
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
					'selector' => '{{PLUS_WRAP}}.pricing-style-1 .pricing-title-wrap .pricing-title',
				],
			],
		],
		'titleNmlColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'title', 'relation' => '!=', 'value' => '' ]],
					'selector' => '{{PLUS_WRAP}}.pricing-style-1 .pricing-title-wrap .pricing-title { color: {{titleNmlColor}}; }',
				],
			],
		],
		'titleHvrColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [ 
					'condition' => [(object) ['key' => 'title', 'relation' => '!=', 'value' => '' ]],
					'selector' => '{{PLUS_WRAP}}.pricing-style-1 .pricing-table-inner:hover .pricing-title { color: {{titleHvrColor}}; }',
				],
			],
		],
		
		'subTitleTypo' => [
			'type'=> 'object',
			'default'=> (object) [
				'openTypography' => 0,
				'size' => [ 'md' => '', 'unit' => 'px' ],
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'subTitle', 'relation' => '!=', 'value' => '' ]],
					'selector' => '{{PLUS_WRAP}}.tpgb-pricing-table .pricing-subtitle',
				],
			],
		],
		'subTitleNmlColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'subTitle', 'relation' => '!=', 'value' => '' ]],
					'selector' => '{{PLUS_WRAP}}.tpgb-pricing-table .pricing-subtitle{ color: {{subTitleNmlColor}}; }',
				],
			],
		],
		'subTitleHvrColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [ 
					'condition' => [(object) ['key' => 'subTitle', 'relation' => '!=', 'value' => '' ]],
					'selector' => '{{PLUS_WRAP}}.tpgb-pricing-table .pricing-table-inner:hover .pricing-subtitle{ color: {{subTitleHvrColor}}; }',
				],
			],
		],
		
		'prevPriceTypo' => [
			'type'=> 'object',
			'default'=> (object) [
				'openTypography' => 0,
				'size' => [ 'md' => '', 'unit' => 'px' ],
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'disPrePrice', 'relation' => '==', 'value' => true ]],
					'selector' => '{{PLUS_WRAP}} .pricing-previous-price-wrap',
				],
			],
		],
		'prevPriceAlign' => [
			'type' => 'string',
			'default' => 'top',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'disPrePrice', 'relation' => '==', 'value' => true ]],
					'selector' => '{{PLUS_WRAP}} .pricing-previous-price-wrap{ vertical-align: {{prevPriceAlign}}; }',
				],
			],
		],
		'prevPriceNmlColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'disPrePrice', 'relation' => '==', 'value' => true ]],
					'selector' => '{{PLUS_WRAP}} .pricing-previous-price-wrap{ color: {{prevPriceNmlColor}}; }',
				],
			],
		],
		'prevPriceHvrColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [ 
					'condition' => [(object) ['key' => 'disPrePrice', 'relation' => '==', 'value' => true ]],
					'selector' => '{{PLUS_WRAP}} .pricing-table-inner:hover .pricing-previous-price-wrap{ color: {{prevPriceHvrColor}}; }',
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
					'condition' => [(object) ['key' => 'priceValue', 'relation' => '!=', 'value' => '' ]],
					'selector' => '{{PLUS_WRAP}} .pricing-price-wrap.style-1 .pricing-price ,{{PLUS_WRAP}} .pricing-price-wrap.style-1 span.price-prefix-text',
				],
			],
		],
		'priceNmlColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'priceValue', 'relation' => '!=', 'value' => '' ]],
					'selector' => '{{PLUS_WRAP}} .pricing-price-wrap.style-1 .pricing-price , {{PLUS_WRAP}} .pricing-price-wrap.style-1 span.price-prefix-text{ color: {{priceNmlColor}}; }',
				],
			],
		],
		'priceHvrColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [ 
					'condition' => [(object) ['key' => 'priceValue', 'relation' => '!=', 'value' => '' ]],
					'selector' => '{{PLUS_WRAP}} .pricing-table-inner:hover .pricing-price-wrap.style-1 .pricing-price , {{PLUS_WRAP}} .pricing-table-inner:hover .pricing-price-wrap.style-1 span.price-prefix-text { color: {{priceHvrColor}}; }',
				],
			],
		],
		'postfixTypo' => [
			'type'=> 'object',
			'default'=> (object) [
				'openTypography' => 0,
				'size' => [ 'md' => '', 'unit' => 'px' ],
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'postText', 'relation' => '!=', 'value' => '' ]],
					'selector' => '{{PLUS_WRAP}} .pricing-price-wrap span.price-postfix-text',
				],
			],
		],
		'postfixNmlColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'postText', 'relation' => '!=', 'value' => '' ]],
					'selector' => '{{PLUS_WRAP}} .pricing-price-wrap span.price-postfix-text{ color: {{postfixNmlColor}}; }',
				],
			],
		],
		'postfixHvrColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [ 
					'condition' => [(object) ['key' => 'postText', 'relation' => '!=', 'value' => '' ]],
					'selector' => '{{PLUS_WRAP}} .pricing-table-inner:hover span.price-postfix-text{ color: {{postfixHvrColor}}; }',
				],
			],
		],
		
		'wysiwygTypo' => [
			'type'=> 'object',
			'default'=> (object) [
				'openTypography' => 0,
				'size' => [ 'md' => '', 'unit' => 'px' ],
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'contentStyle', 'relation' => '==', 'value' => 'wysiwyg' ]],
					'selector' => '{{PLUS_WRAP}} .pricing-content-wrap.content-desc .pricing-content',
				],
			],
		],
		'wysiwygTextColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'contentStyle', 'relation' => '==', 'value' => 'wysiwyg' ]],
					'selector' => '{{PLUS_WRAP}} .pricing-content-wrap.content-desc .pricing-content , {{PLUS_WRAP}} .pricing-content-wrap.content-desc .pricing-content p{ color: {{wysiwygTextColor}}; }',
				],
			],
		],
		'wyBorderWidth' => [
			'type' => 'object',
			'default' => [ 
				'md' => '',
				"unit" => '%',
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'contentStyle', 'relation' => '==', 'value' => 'wysiwyg' ] , ['key' => 'wyStyle', 'relation' => '==', 'value' => 'style-1' ]],
					'selector' => '{{PLUS_WRAP}} .content-desc.style-1 hr.border-line{ margin: 30px {{wyBorderWidth}}; }',
				],
			],
		],
		'wysiwygBColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'contentStyle', 'relation' => '==', 'value' => 'wysiwyg' ] , ['key' => 'wyStyle', 'relation' => '==', 'value' => 'style-1' ]],
					'selector' => '{{PLUS_WRAP}} .content-desc.style-1 hr.border-line{ border-color: {{wysiwygBColor}}; }',
				],
			],
		],
		'wysiwygAlign' => [
			'type' => 'string',
			'default' => 'left',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'contentStyle', 'relation' => '==', 'value' => 'wysiwyg' ]],
					'selector' => '{{PLUS_WRAP}} .pricing-content-wrap.content-desc .pricing-content , {{PLUS_WRAP}} .pricing-content-wrap.content-desc .pricing-content p{ text-align: {{wysiwygAlign}}; }',
				],
			],
		],
		
		
		'innerPadding' => [
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
					'selector' => '{{PLUS_WRAP}}.tpgb-pricing-table.pricing-style-1 .pricing-table-inner {padding: {{innerPadding}};}',
				],
			],
		],
		'bgNmlBorder' => [
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
					'selector' => '{{PLUS_WRAP}}.tpgb-pricing-table.pricing-style-1 .pricing-table-inner ',
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
					'selector' => '{{PLUS_WRAP}}.tpgb-pricing-table.pricing-style-1 .pricing-table-inner , {{PLUS_WRAP}}.tpgb-pricing-table.pricing-style-1 .pricing-overlay-color {border-radius: {{bgNmlBRadius}};}',
				],
			],
		],
		'bgHvrBorder' => [
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
					'selector' => '{{PLUS_WRAP}}.tpgb-pricing-table.pricing-style-1:hover .pricing-table-inner',
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
					'selector' => '{{PLUS_WRAP}}.tpgb-pricing-table.pricing-style-1:hover .pricing-table-inner ,  {{PLUS_WRAP}}.tpgb-pricing-table.pricing-style-1:hover .pricing-overlay-color {border-radius: {{bgHvrBRadius}};}',
				],
			],
		],
		'hoverStyle' => [
			'type' => 'string',
			'default' => 'hover_normal',	
		],
		'normalBG' => [
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
					'selector' => '{{PLUS_WRAP}}.tpgb-pricing-table.pricing-style-1 .pricing-table-inner ',
				],
			],
		],
		'nmlOverlay' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'hoverStyle', 'relation' => '==', 'value' => 'hover_normal' ]],
					'selector' => '{{PLUS_WRAP}}.tpgb-pricing-table.pricing-style-1 .pricing-overlay-color{ background: {{nmlOverlay}}; }',
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
					'selector' => '{{PLUS_WRAP}}.tpgb-pricing-table.pricing-style-1 .pricing-table-inner ',
				],
			],
		],
		'hoverBG' => [
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
					'selector' => '{{PLUS_WRAP}}.tpgb-pricing-table.pricing-style-1:hover .pricing-table-inner , {{PLUS_WRAP}}.tpgb-pricing-table.hover_fadein .pricing-overlay-color , {{PLUS_WRAP}}.tpgb-pricing-table.hover_slide_left .pricing-overlay-color , {{PLUS_WRAP}}.tpgb-pricing-table.hover_slide_right .pricing-overlay-color , {{PLUS_WRAP}}.tpgb-pricing-table.hover_slide_top .pricing-overlay-color , {{PLUS_WRAP}}.tpgb-pricing-table.hover_slide_bottom .pricing-overlay-color ',
				],
			],
		],
		'hvrOverlay' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'hoverStyle', 'relation' => '==', 'value' => 'hover_normal' ]],
					'selector' => '{{PLUS_WRAP}}.tpgb-pricing-table.pricing-style-1:hover .pricing-overlay-color { background: {{hvrOverlay}}; }',
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
					'selector' => '{{PLUS_WRAP}}.tpgb-pricing-table.pricing-style-1:hover .pricing-table-inner ',
				],
			],
		],
		
	);
	$attributesOptions = array_merge($attributesOptions,$globalBgOption,$globalpositioningOption,$plusButton_options, $globalPlusExtrasOption);
	
	register_block_type( 'tpgb/tp-pricing-table', array(
		'attributes' => $attributesOptions,
		'editor_script' => 'tpgb-block-editor-js',
		'editor_style'  => 'tpgb-block-editor-css',
        'render_callback' => 'tpgb_tp_pricing_table_render_callback'
    ) );
}
add_action( 'init', 'tpgb_pricing_table' );