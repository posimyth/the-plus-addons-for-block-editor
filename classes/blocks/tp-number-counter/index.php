<?php
/* Block : Number Counter
 * @since : 1.0.0
 */
function tpgb_tp_number_counter_render_callback( $attributes, $content) {
    $block_id = (!empty($attributes['block_id'])) ? $attributes['block_id'] : uniqid("title");
	$style = (!empty($attributes['style'])) ? $attributes['style'] : 'style-1';
	$title = (!empty($attributes['title'])) ? $attributes['title'] : '';
	$style1Align = (!empty($attributes['style1Align'])) ? $attributes['style1Align'] : 'text-center';
	$style2Align = (!empty($attributes['style2Align'])) ? $attributes['style2Align'] : 'text-left';
	$numValue = (!empty($attributes['numValue'])) ? $attributes['numValue'] : '1000';
	$startValue = (!empty($attributes['startValue'])) ? $attributes['startValue'] : '0';
	$timeDelay = (!empty($attributes['timeDelay'])) ? $attributes['timeDelay'] : '5';
	$numGap = (!empty($attributes['numGap'])) ? $attributes['numGap'] : '5';
	$symbol = (!empty($attributes['symbol'])) ? $attributes['symbol'] : '';
	$symbolPos = (!empty($attributes['symbolPos'])) ? $attributes['symbolPos'] : 'after';
	$iconType = (!empty($attributes['iconType'])) ? $attributes['iconType'] : 'icon';
	$iconStyle = (!empty($attributes['iconStyle'])) ? $attributes['iconStyle'] : 'square';
	$iconStore = (!empty($attributes['iconStore'])) ? $attributes['iconStore'] : '';
	$linkURL = (!empty($attributes['linkURL']['url'])) ? $attributes['linkURL']['url'] : '';
	$imagestore = (!empty($attributes['imagestore'])) ? $attributes['imagestore'] : TPGB_ASSETS_URL.'assets/images/tpgb-placeholder.jpg';
	$imageSize = (!empty($attributes['imageSize'])) ? $attributes['imageSize'] : 'thumbnail';
	$target = (!empty($attributes['linkURL']['target'])) ? '_blank' : '';
	$nofollow = (!empty($attributes['linkURL']['nofollow'])) ? 'nofollow' : '';
	$verticalCenter = (!empty($attributes['verticalCenter'])) ? $attributes['verticalCenter'] : false;
	
	$blockClass = Tp_Blocks_Helper::block_wrapper_classes( $attributes );
	
	if(!empty($imagestore) && !empty($imagestore['id'])){
		$counter_img = $imagestore['id'];
		$imgSrc = wp_get_attachment_image_src($counter_img , $imageSize);
		$imgSrc = $imgSrc[0];
	}else if(!empty($imagestore['url'])){
		$imgSrc = $imagestore['url'];
	}else{
		$imgSrc = $imagestore;
	}
	
	$vCenter = '';
	if(!empty($verticalCenter)){
		$vCenter='vertical-center';
	}
	
	$alignment = '';
	if($style=='style-1'){
		$alignment=$style1Align;
	}
	if($style=='style-2'){
		$alignment=$style2Align;
	}
		
	$getCounterNo = '';
	$getCounterNo .= '<h5 class="nc-counter-number">';
		if(!empty($symbol) && $symbolPos=='before'){
			$getCounterNo .= '<span class="counter-symbol-text">'.esc_html($symbol).'</span>';
		}
		$getCounterNo .= '<span class="counter-number-inner numscroller" data-min="'.esc_attr($startValue).'" data-max="'.esc_attr($numValue).'" data-delay="'.esc_attr($timeDelay).'" data-increment="'.esc_attr($numGap).'">';
			$getCounterNo .= $startValue;
		$getCounterNo .= '</span>';
		if(!empty($symbol) && $symbolPos=='after'){
			$getCounterNo .= '<span class="counter-symbol-text">'.esc_html($symbol).'</span>';
		}
	$getCounterNo .= '</h5>';
	
	$getTitle = '';
	if(!empty($linkURL)){
		$getTitle .='<a href="'.esc_url($linkURL).'" target="'.esc_attr($target).' "  rel="'.esc_attr($nofollow).' ">';
	}
	$getTitle .= '<h6 class="counter-title">'.wp_kses_post($title).'</h6>';
	if(!empty($linkURL)){
		$getTitle .= '</a>';
	}
	
	$getIcon = '';
	if(!empty($linkURL)){
		$getIcon .='<a href="'.esc_url($linkURL).'" target="'.esc_attr($target).' "  rel="'.esc_attr($nofollow).' ">';
	}
			$getIcon .= '<div class="counter-icon-inner shape-icon-'.esc_attr($iconStyle).'">';
				$getIcon .= '<span class="counter-icon">';
					$getIcon .= '<i class="'.esc_attr($iconStore).'"></i>';
				$getIcon .= '</span>';
			$getIcon .= '</div>';
	if(!empty($linkURL)){
		$getIcon .= '</a>';
	}
	
	$getImg = '';
	if(!empty($linkURL)){
		$getImg .= '<a href="'.esc_url($linkURL).'" target="'.esc_attr($target).' "  rel="'.esc_attr($nofollow).' ">';
	}
			$getImg .= '<div class="counter-image-inner">';
				$getImg .= '<img class="counter-icon-image" src='.esc_url($imgSrc).' alt="'.esc_attr__('Counter Number','tpgb').'"/>';
			$getImg .= '</div>';
	if(!empty($linkURL)){
		$getImg .= '</a>';
	}
	$output = '';
    $output .= '<div class="tpgb-number-counter counter-'.esc_attr($style).' '.esc_attr($alignment).' tpgb-block-'.esc_attr($block_id).' '.esc_attr($blockClass).'">';
		$output .= '<div class="number-counter-inner '.esc_attr($vCenter).'">';
			if($style=='style-1'){
				$output .= '<div class="counter-wrap-content">';
					if($iconType=='icon'){
						$output .= $getIcon;
					}
					if($iconType=='img'){
						$output .= $getImg;
					}
					$output .= $getCounterNo;
					if(!empty($title)){
						$output .= $getTitle;
					}
				$output .= '</div>';
			}
			if($style=='style-2'){
				$output .= '<div class="icn-header">';
					if($iconType=='icon'){
						$output .= $getIcon;
					}
					if($iconType=='img'){
						$output .= $getImg;
					}
				$output .= '</div>';
				$output .= '<div class="counter-content">';
					$output .= $getCounterNo;
					if(!empty($title)){
						$output .= $getTitle;
					}
				$output .= '</div>';
			}
		$output .= '</div>';
    $output .= '</div>';

	$output = Tpgb_Blocks_Global_Options::block_Wrap_Render($attributes, $output);
	
    return $output;
}

/**
 * Render for the server-side
 */
function tpgb_number_counter() {
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
		'style1Align' => [
			'type' => 'string',
			'default' => 'text-center',
		],
		'style2Align' => [
			'type' => 'string',
			'default' => 'text-left',
		],
		'title' => [
			'type' => 'string',
			'default' => 'Awards Won',	
		],
		'linkURL' => [
			'type'=> 'object',
			'default'=> [
				'url' => '#',	
				'target' => '',
				'nofollow' => ''
			],
		],
		'numValue' => [
			'type' => 'string',
			'default' => '999',	
		],
		'startValue' => [
			'type' => 'string',
			'default' => '0',	
		],
		'numGap' => [
			'type' => 'string',
			'default' => '5',	
		],
		'timeDelay' => [
			'type' => 'string',
			'default' => '5',	
		],
		'symbol' => [
			'type' => 'string',
			'default' => '',	
		],
		'symbolPos' => [
			'type' => 'string',
			'default' => 'after',	
		],
		'iconType' => [
			'type' => 'string',
			'default' => 'icon',	
		],
		'iconStore' => [
			'type'=> 'string',
			'default'=> 'fas fa-award',
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
		
		'titleTypo' => [
			'type'=> 'object',
			'default'=> (object) [
				'openTypography' => 0,
				'size' => [ 'md' => '', 'unit' => 'px' ],
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'title', 'relation' => '!=', 'value' => '' ]],
					'selector' => '{{PLUS_WRAP}} .number-counter-inner .counter-title',
				],
			],
		],
		'titleNmlColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'title', 'relation' => '!=', 'value' => '' ]],
					'selector' => '{{PLUS_WRAP}} .number-counter-inner .counter-title{ color: {{titleNmlColor}}; }',
				],
			],
		],
		'titleHvrColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [ 
					'condition' => [(object) ['key' => 'title', 'relation' => '!=', 'value' => '' ]],
					'selector' => '{{PLUS_WRAP}} .number-counter-inner:hover .counter-title{ color: {{titleHvrColor}}; }',
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
					'selector' => '{{PLUS_WRAP}} .number-counter-inner .counter-title{ margin-top: {{titleTopSpace}}; }',
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
					'selector' => '{{PLUS_WRAP}} .number-counter-inner .counter-title{ margin-bottom: {{titleBottomSpace}}; }',
				],
			],
		],
		'digitTypo' => [
			'type'=> 'object',
			'default'=> (object) [
				'openTypography' => 0,
				'size' => [ 'md' => '', 'unit' => 'px' ],
			],
			'style' => [
				(object) [
					'selector' => '{{PLUS_WRAP}} .number-counter-inner .nc-counter-number',
				],
			],
		],
		'digitNmlColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'selector' => '{{PLUS_WRAP}} .number-counter-inner .nc-counter-number{ color: {{digitNmlColor}}; }',
				],
			],
		],
		'digitHvrColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [ 
					'selector' => '{{PLUS_WRAP}} .number-counter-inner:hover .nc-counter-number{ color: {{digitHvrColor}}; }',
				],
			],
		],
		'digitTopSpace' => [
			'type' => 'object',
			'default' => [ 
				'md' => '',
				"unit" => 'px',
			],
			'style' => [
				(object) [
					'selector' => '{{PLUS_WRAP}} .number-counter-inner .nc-counter-number{ margin-top: {{digitTopSpace}}; }',
				],
			],
		],
		'symbolTypo' => [
			'type'=> 'object',
			'default'=> (object) [
				'openTypography' => 0,
				'size' => [ 'md' => '', 'unit' => 'px' ],
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'symbol', 'relation' => '!=', 'value' => '' ]],
					'selector' => '{{PLUS_WRAP}} .number-counter-inner .counter-symbol-text',
				],
			],
		],
		'symbolNmlColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'symbol', 'relation' => '!=', 'value' => '' ]],
					'selector' => '{{PLUS_WRAP}} .number-counter-inner .counter-symbol-text{ color: {{symbolNmlColor}}; }',
				],
			],
		],
		'symbolHvrColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [ 
					'condition' => [(object) ['key' => 'symbol', 'relation' => '!=', 'value' => '' ]],
					'selector' => '{{PLUS_WRAP}} .number-counter-inner:hover .counter-symbol-text{ color: {{symbolHvrColor}}; }',
				],
			],
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
					'selector' => '{{PLUS_WRAP}} .number-counter-inner .counter-icon-inner .counter-icon{ font-size: {{iconSize}}; }',
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
					'selector' => '{{PLUS_WRAP}} .number-counter-inner .counter-icon-inner { width: {{iconWidth}}; height: {{iconWidth}}; line-height: {{iconWidth}}; }',
				],
			],
		],
		'icnNmlColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'iconType', 'relation' => '==', 'value' => 'icon' ]],
					'selector' => '{{PLUS_WRAP}} .number-counter-inner .counter-icon-inner .counter-icon{ color: {{icnNmlColor}}; }',
				],
			],
		],
		'icnHvrColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [ 
					'condition' => [(object) ['key' => 'iconType', 'relation' => '==', 'value' => 'icon' ]],
					'selector' => '{{PLUS_WRAP}} .number-counter-inner:hover .counter-icon{ color: {{icnHvrColor}}; }',
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
					'selector' => '{{PLUS_WRAP}} .number-counter-inner .counter-icon-inner',
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
					'selector' => '{{PLUS_WRAP}} .number-counter-inner:hover .counter-icon-inner',
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
					'selector' => '{{PLUS_WRAP}}.tpgb-number-counter .counter-icon-inner{ border-color: {{nmlBColor}}; }',
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
					'selector' => '{{PLUS_WRAP}}.tpgb-number-counter .number-counter-inner:hover .counter-icon-inner{ border-color: {{hvrBColor}}; }',
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
					'selector' => '{{PLUS_WRAP}}.tpgb-number-counter .counter-icon-inner{border-radius: {{nmlIcnBRadius}};}',
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
					'selector' => '{{PLUS_WRAP}}.tpgb-number-counter .number-counter-inner:hover .counter-icon-inner{border-radius: {{hvrIcnBRadius}};}',
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
					'selector' => '{{PLUS_WRAP}}.tpgb-number-counter .counter-icon-inner',
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
					'selector' => '{{PLUS_WRAP}}.tpgb-number-counter .number-counter-inner:hover .counter-icon-inner',
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
					'condition' => [(object) ['key' => 'iconType', 'relation' => '==', 'value' => 'img' ]],
					'selector' => '{{PLUS_WRAP}}.tpgb-number-counter .counter-image-inner { max-width: {{imgWidth}}; }',
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
					'sm' => (object)[ ],
					'xs' => (object)[ ],
					"unit" => "px",
				],			
			],
			'style' => [
				(object) [
					'selector' => '{{PLUS_WRAP}}.tpgb-number-counter .number-counter-inner',
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
					'sm' => (object)[ ],
					'xs' => (object)[ ],
					"unit" => "px",
				],			
			],
			'style' => [
				(object) [
					'selector' => '{{PLUS_WRAP}}.tpgb-number-counter .number-counter-inner:hover',
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
					'selector' => '{{PLUS_WRAP}}.tpgb-number-counter .number-counter-inner{border-radius: {{bgNmlBRadius}};}',
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
					'selector' => '{{PLUS_WRAP}}.tpgb-number-counter .number-counter-inner:hover {border-radius: {{bgHvrBRadius}};}',
				],
			],
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
					'selector' => '{{PLUS_WRAP}}.tpgb-number-counter .number-counter-inner',
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
					'selector' => '{{PLUS_WRAP}}.tpgb-number-counter .number-counter-inner:hover',
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
					'selector' => '{{PLUS_WRAP}}.tpgb-number-counter .number-counter-inner',
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
					'selector' => '{{PLUS_WRAP}}.tpgb-number-counter .number-counter-inner:hover',
				],
			],
		],
		'bgPadding' => [
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
					'selector' => '{{PLUS_WRAP}}.tpgb-number-counter .number-counter-inner{padding: {{bgPadding}};}',
				],
			],
		],
		'verticalCenter' => [
			'type' => 'boolean',
			'default' => false,	
		],
	);
	$attributesOptions = array_merge($attributesOptions,$globalBgOption,$globalpositioningOption, $globalPlusExtrasOption);
	
	register_block_type( 'tpgb/tp-number-counter', array(
		'attributes' => $attributesOptions,
		'editor_script' => 'tpgb-block-editor-js',
		'editor_style'  => 'tpgb-block-editor-css',
        'render_callback' => 'tpgb_tp_number_counter_render_callback'
    ) );
}
add_action( 'init', 'tpgb_number_counter' );