<?php
/**
 * After rendring from the block editor display output on front-end
 */
function tpgb_tp_progress_bar_render_callback( $attributes, $content) {
	$output = '';
    $block_id = (!empty($attributes['block_id'])) ? $attributes['block_id'] : uniqid("title");
	$layoutType = (!empty($attributes['layoutType'])) ? $attributes['layoutType'] : 'progressbar';
    $styleType = (!empty($attributes['styleType'])) ? $attributes['styleType'] : 'style-1';
    $pieStyleType = (!empty($attributes['pieStyleType'])) ? $attributes['pieStyleType'] : 'pieStyle-1';
    $circleStyle = (!empty($attributes['circleStyle'])) ? $attributes['circleStyle'] : 'style-1';
	$heightType = (!empty($attributes['heightType'])) ? $attributes['heightType'] : 'small-height';
	$iconType = (!empty($attributes['iconType'])) ? $attributes['iconType'] : 'iconIcon';
	$iconLibrary = (!empty($attributes['iconLibrary'])) ? $attributes['iconLibrary'] : 'fontawesome';
    $Title = (!empty($attributes['Title'])) ? $attributes['Title'] : '';
	$subTitle = (!empty($attributes['subTitle'])) ? $attributes['subTitle'] : '';
	$IconName = (!empty($attributes['IconName'])) ? $attributes['IconName'] : '';
	$imageName = (!empty($attributes['imageName']['url'])) ? $attributes['imageName'] : '';
	$imageSize = (!empty($attributes['imageSize'])) ? $attributes['imageSize'] : 'thumbnail' ;
	$prepostSymbol = (!empty($attributes['prepostSymbol'])) ? $attributes['prepostSymbol'] : '';
	$sPosition = (!empty($attributes['sPosition'])) ? $attributes['sPosition'] : 'afterNumber';
	$dynamicValue = (!empty($attributes['dynamicValue'])) ? $attributes['dynamicValue'] : '';
	$dynamicPieValue = (!empty($attributes['dynamicPieValue'])) ? $attributes['dynamicPieValue'] : '';
	$dispNumber = (!empty($attributes['dispNumber'])) ? $attributes['dispNumber'] : false;
	$imgPosition = (!empty($attributes['imgPosition'])) ? $attributes['imgPosition'] : 'beforeTitle';
	$emptyColor = (!empty($attributes['emptyColor'])) ? $attributes['emptyColor'] : 'transparent';
	$pieCircleSize = (!empty($attributes['pieCircleSize'])) ? $attributes['pieCircleSize'] : '200';
	$pieThickness = (!empty($attributes['pieThickness'])) ? $attributes['pieThickness'] : '5';
	$pieFillColor = (!empty($attributes['pieFillColor'])) ? $attributes['pieFillColor'] : 'normal';
	$pieColor1 = (!empty($attributes['pieColor1'])) ? $attributes['pieColor1'] : '#FFA500';
	$pieColor2 = (!empty($attributes['pieColor2'])) ? $attributes['pieColor2'] : '#008000';
	$fillReverse = (!empty($attributes['fillReverse'])) ? $attributes['fillReverse'] : false;
	$className = (!empty($attributes['className'])) ? $attributes['className'] :'';
	$align = (!empty($attributes['align'])) ? $attributes['align'] :'';
	
	$blockClass = '';
	if(!empty($className)){
		$blockClass .= $className;
	}
	if(!empty($align)){
		$blockClass .= ' align'.$align;
	}
	//image size
	if(!empty($imageName) && !empty($imageName['id'])){
		$pbar_img = $imageName['id'];
		$imgSrc = wp_get_attachment_image_src($pbar_img , $imageSize);
		$imgSrc = $imgSrc[0];
	}else if(!empty($imageName['url'])){
		$imgSrc = $imageName['url'];
	}else{
		$imgSrc = $imageName;
	}
	
	$data_fill_color='';
	if($pieFillColor =='gradient'){
		$data_fill_color = ' data-fill="{&quot;gradient&quot;: [&quot;' . esc_attr($pieColor1) . '&quot;,&quot;' . esc_attr($pieColor2) . '&quot;]}" ';
	}else{
		$data_fill_color = ' data-fill="{&quot;color&quot;: &quot;'.esc_attr($pieColor1).'&quot;}" ';
	}
	
	$piechartClass=$piechantAttr='';
	if($layoutType=='piechart'){
		$piechartClass='tpgb-piechart';
		$piechantAttr = $data_fill_color.' data-emptyfill="'.esc_attr($emptyColor).'" data-value="'.esc_attr($dynamicPieValue).'"  data-size="'.esc_attr($pieCircleSize).'" data-thickness="'.esc_attr($pieThickness).'" data-animation-start-value="0"  data-reverse="'.esc_attr($fillReverse).'"';
	}
	
	$getTitle ='';
	if(!empty($Title)){
		$before_after = ($imgPosition=='beforeTitle') ? ' before-icon' : ' after-icon';
		$getTitle .= '<span class="progress-bar-title '.($iconType!='iconNone' ? $before_after : '').'">'.wp_kses_post($Title).'</span>';
	}
	$getIcon='';
	if(!empty($IconName)){
		$getIcon .='<span class="progres-ims">';
			if($iconType=='iconIcon' && $iconLibrary=='fontawesome'){
				$getIcon .='<i class="'.esc_attr($IconName).'"></i>';
			}
			elseif($iconType=='iconImage'){
				$getIcon .='<img src="'.esc_url($imgSrc).'" class="progress-bar-img" />';
			}
		$getIcon .='</span>';
			
	}
	$getSubTitle='';
	if(!empty($subTitle)){
		$getSubTitle .='<div class="progress-bar-sub-title">'.wp_kses_post($subTitle).'</div>';
	}
	
	$getCounterNo=$SymbolGet=$NumberGet='';
	if(!empty($prepostSymbol)){
		$SymbolGet .= '<span class="theserivce-milestone-symbol">'.esc_html($prepostSymbol).'</span>';
	}
	if(!empty($dynamicValue) || !empty($dynamicPieValue)){
		$Number ='';
		if($layoutType =='progressbar') {
			$Number .= (float)$dynamicValue;
		}elseif($layoutType =='piechart') {
			$Number .= (float)$dynamicPieValue*100 ;
		}
		if(!empty($dispNumber)){
			$NumberGet .= '<span class="theserivce-milestone-number icon-milestone">'.esc_html($Number).'</span>';
		}
	}
	
	$getCounterNo .= '<h5 class="counter-number">';
	if(!empty($sPosition=='afterNumber')){
		$getCounterNo .= $NumberGet.$SymbolGet;
	}
	if(!empty($sPosition=='beforeNumber')){
		$getCounterNo .= $SymbolGet.$NumberGet;
	}
	$getCounterNo .= '</h5>';
	
	$htype='';
	$sml='';
		if($heightType=='small-height'){
			$htype = 'small';
			$sml = 'prog-title prog-icon';
		}elseif($heightType=='medium-height'){
			$htype = 'medium';
			$sml = 'prog-title prog-icon';
		}elseif($heightType=='large-height'){
			$htype = 'large';
			$sml = 'prog-title prog-icon large';
		}
	$circleBorder = '' ;
	if($circleStyle=='style-2'){
		$circleBorder = 'pie-circle-border';
	}
		
    $output .= '<div class="tpgb-progress-bar tpgb-block-'.esc_attr($block_id).' '.esc_attr($piechartClass).' '.esc_attr($blockClass).'" '.$piechantAttr.'>';
		//Progrssbar
		if($layoutType =='progressbar'){
			if($styleType=='style-1'){
				if($heightType=='small-height'){
					$output .= '<div class="progress-bar-media"><div class="'.esc_attr($sml).'">';
							if($imgPosition=='beforeTitle'){
								$output .= $getIcon;
								$output .= $getTitle;
							}
							elseif($imgPosition=='afterTitle'){
								$output .= $getTitle;
								$output .= $getIcon;
							}
							$output .=$getSubTitle;
					$output .='</div>'.$getCounterNo.'</div><div class="progress-bar-skill skill-fill '.esc_attr($htype).'"><div class="progress-bar-skill-bar-filled" data-width="'.esc_attr($Number).'%"></div></div>';
				}
				if($heightType=='medium-height'){
					$output .= '<div class="progress-bar-media"><div class="'.esc_attr($sml).'">';
							if($imgPosition=='beforeTitle'){
								$output .= $getIcon;
								$output .= $getTitle;
							}
							elseif($imgPosition=='afterTitle'){
								$output .= $getTitle;
								$output .= $getIcon;
							}
							$output .=$getSubTitle;
						$output .='</div>'.$getCounterNo.'</div><div class="progress-bar-skill skill-fill '.esc_attr($htype).'"><div class="progress-bar-skill-bar-filled " data-width="'.esc_attr($Number).'%"></div></div>';
				}
				if($heightType=='large-height'){
					$output .='<div class="progress-bar-skill skill-fill '.esc_attr($htype).'"><div class="progress-bar-skill-bar-filled " data-width="'.esc_attr($Number).'%"></div><div class="progress-bar-media large" data-width="'.esc_attr($Number).'%"><div class="'.esc_attr($sml).'">';
							if($imgPosition=='beforeTitle'){
								$output .= $getIcon;
								$output .= $getTitle;
							}
							elseif($imgPosition=='afterTitle'){
								$output .= $getTitle;
								$output .= $getIcon;
							}
					$output .='</div>'.$getCounterNo.'</div></div>';
				}
			}
			if($styleType=='style-2'){
				$output .='<div class="progress-bar-media"><div class="'.esc_attr($sml).'">';
						if($imgPosition=='beforeTitle'){
							$output .= $getIcon;
							$output .= $getTitle;
						}
						elseif($imgPosition=='afterTitle'){
							$output .= $getTitle;
							$output .= $getIcon;
						}
						$output .=$getSubTitle;
					$output .='</div>'.$getCounterNo.'</div><div class="progress-bar-skill skill-fill progress-style-2"><div class="progress-bar-skill-bar-filled " data-width="'.esc_attr($Number).'%"></div></div>';
			}
		}
		if($layoutType=='piechart'){
			if($pieStyleType=='pieStyle-1'){
				$output .='<div class = "tpgb-piechart '.esc_attr($circleBorder).'"><div class="circle"><div class="pie-numbers">'.$getCounterNo.'</div></div></div>';
				$output .='<div class = "tpgb-pie-chart">';		 
					$output .= $getTitle;
					$output .=$getSubTitle;
				$output .='</div>';
			}
			if($pieStyleType=='pieStyle-2'){
				$output .='<div class = "tpgb-piechart '.esc_attr($circleBorder).'"><div class="circle"><div class="pie-numbers">'.$getCounterNo.'</div></div></div>';
					$output .='<div class = "tpgb-pie-chart style-2"><div class = "pie-chart">'.$getIcon.'</div>';
					$output .='<div class = "pie-chart-style2">';		 
						$output .= $getTitle;
						$output .=$getSubTitle;
					$output .='</div></div>';
			}
			if($pieStyleType=='pieStyle-3'){
				$output .='<div class = "tpgb-piechart '.esc_attr($circleBorder).'"><div class="circle"><div class="pie-numbers">'.$getIcon.'</div></div></div>';
				$output .='<div class = "tpgb-pie-chart style-3"><div class = "pie-chart">'.$getCounterNo.'</div>';
					$output .='<div class = "pie-chart-style3">';		 
						$output .= $getTitle;
						$output .=$getSubTitle;
				$output .='</div></div>';
			}
		}
    $output .= "</div>";
	
	$output = Tpgb_Blocks_Global_Options::block_Wrap_Render($attributes, $output);
	
    return $output;
}


/**
 * Render for the server-side
 */
function tpgb_tp_progress_bar() {
	$globalBgOption = Tpgb_Blocks_Global_Options::load_bg_options();
	$globalpositioningOption = Tpgb_Blocks_Global_Options::load_positioning_options();
	$globalPlusExtrasOption = Tpgb_Blocks_Global_Options::load_plusextras_options();
	$attributesOptions = array(
			'block_id' => array(
                'type' => 'string',
				'default' => '',
			),
			'layoutType' => [
				'type' => 'string',
				'default' => 'progressbar',	
			],
			'styleType' => [
				'type' => 'string',
				'default' => 'style-1',	
			],
			'heightType' => [
				'type' => 'string',
				'default' => 'small-height',	
			],
			'pieStyleType' => [
				'type' => 'string',
				'default' => 'pieStyle-1',	
			],
			'circleStyle' => [
				'type' => 'string',
				'default' => 'style-1',	
			],
			'dynamicValue' => [
				'type' => 'string',
				'default' => 69,
			],
			'dynamicPieValue' => [
				'type' => 'string',
				'default' => 0.7,
				
			],
			'dispNumber' => [
				'type' => 'boolean',
				'default' => true,
			],
			'pieCircleSize' => [
				'type' => 'string',
				'default' => 200,
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-piechart .circle{ width:{{pieCircleSize}}px; height:{{pieCircleSize}}px;}',
					],
				],
			],
			'pieThickness' => [
				'type' => 'string',
				'default' => 5,
			],
			'pieFillColor' => [
				'type' => 'string',
				'default' => 'normal',
			],
			'pieColor1' => [
				'type' => 'string',
				'default' => '#FFA500',
			],
			'pieColor2' => [
				'type' => 'string',
				'default' => '#008000',
			],
			'fillReverse' => [
				'type' => 'boolean',
				'default' => false,
			],
			'Title' => [
				'type' => 'string',
				'default' => 'Web Design',	
			],
			'subTitle' => [
				'type' => 'string',
				'default' => 'HTML, CSS and WordPress',	
			],
			'prepostSymbol' => [
				'type' => 'string',
				'default' => '%',	
			],
			'sPosition' => [
				'type' => 'string',
				'default' => 'afterNumber',	
			],
			'iconType' => [
				'type' => 'string',
				'default' => 'iconIcon',	
			],
			'iconLibrary' => [
				'type' => 'string',
				'default' => 'fontawesome',	
			],
			'IconName' => [
				'type'=> 'string',
				'default'=> 'fas fa-code',
			],
			'imageName' => [
				'type' => 'object',
				'default' => [
					'url' => '',
					'Id' => '',
				],
			],
			'imageSize' => [
				'type' => 'string',
				'default' => 'thumbnail',	
			],
			'imgPosition' => [
				'type' => 'string',
				'default' => 'beforeTitle',	
			],
			'pbTopMargin' => [
				'type' => 'object',
				'default' => (object) [ 
					'md' => '',
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'layoutType', 'relation' => '==', 'value' => 'progressbar']],
						'selector' => '{{PLUS_WRAP}} .progress-bar-skill.skill-fill { margin-top: {{pbTopMargin}}; }',
					],
				],
			],
			'bgColor' => [
				'type' => 'object',
				'default' => (object) [
					'openBg'=> 0,
					'bgType' => 'color',
					'bgDefaultColor' => '',
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .progress-bar-skill-bar-filled',
					],
				],
			],
			'emptyColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .progress-bar-skill.skill-fill{ background-color: {{emptyColor}}; }',
					],
				],
			],
			'sepColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .progress-style-2 .progress-bar-skill-bar-filled:after{ border-color: {{sepColor}}; }',
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
						'condition' => [(object) ['key' => 'Title', 'relation' => '!=', 'value' => '']],
						'selector' => '{{PLUS_WRAP}} .progress-bar-title , {{PLUS_WRAP}} .progress-bar-media.large .progress-bar-title ',
					],
				],
			],
			'titleColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'Title', 'relation' => '!=', 'value' => '']],
						'selector' => '{{PLUS_WRAP}} .progress-bar-title , {{PLUS_WRAP}} .progress-bar-media.large .prog-title.prog-icon.large .progress-bar-title { color: {{titleColor}}; }',
					],
				],
			],
			'titleSpace' => [
				'type' => 'object',
				'default' => (object) [ 
					'md' => '',
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'layoutType', 'relation' => '==', 'value' => 'progressbar']],
						'selector' => '{{PLUS_WRAP}}:not(.tpgb-piechart) .progress-bar-title.before-icon,{{PLUS_WRAP}}:not(.tpgb-piechart) .progress-bar-media.large .progress-bar-title.before-icon{ padding-left: {{titleSpace}}; } {{PLUS_WRAP}}:not(.tpgb-piechart) .progress-bar-title.after-icon,{{PLUS_WRAP}}:not(.tpgb-piechart) .progress-bar-media.large .progress-bar-title.after-icon{ padding-right: {{titleSpace}}; }',
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
						'condition' => [(object) ['key' => 'subTitle', 'relation' => '!=', 'value' => '']],
						'selector' => '{{PLUS_WRAP}} .progress-bar-sub-title',
					],
				],
			],
			'subTitleColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'subTitle', 'relation' => '!=', 'value' => '']],
						'selector' => '{{PLUS_WRAP}} .progress-bar-sub-title{ color: {{subTitleColor}}; }',
					],
				],
			],
			'numTypo' => [
				'type'=> 'object',
				'default'=> (object) [
					'openTypography' => 0,
					'size' => [ 'md' => '', 'unit' => 'px' ],
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .theserivce-milestone-number.icon-milestone',
					],
				],
			],
			'numberColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .theserivce-milestone-number.icon-milestone{ color: {{numberColor}}; }',
					],
				],
			],
			'numPrePostTypo' => [
				'type'=> 'object',
				'default'=> (object) [
					'openTypography' => 0,
					'size' => [ 'md' => '', 'unit' => 'px' ],
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'prepostSymbol', 'relation' => '!=', 'value' => '']],
						'selector' => '{{PLUS_WRAP}} .theserivce-milestone-symbol',
					],
				],
			],
			'numPrePostColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'prepostSymbol', 'relation' => '!=', 'value' => '']],
						'selector' => '{{PLUS_WRAP}} .theserivce-milestone-symbol{ color: {{numPrePostColor}}; }',
					],
				],
			],
			'iconColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'iconType', 'relation' => '==', 'value' => 'iconIcon']],
						'selector' => '{{PLUS_WRAP}} .progres-ims i{ color: {{iconColor}}; }',
					],
				],
			],
			'iconSize' => [
				'type' => 'object',
				'default' => (object) [ 
					'md' => '',
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'iconType', 'relation' => '==', 'value' => 'iconIcon']],
						'selector' => '{{PLUS_WRAP}} .progres-ims { font-size: {{iconSize}}; }',
					],
				],
			],
			'imgSize' => [
				'type' => 'object',
				'default' => (object) [ 
					'md' => '',
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'iconType', 'relation' => '==', 'value' => 'iconImage']],
						'selector' => '{{PLUS_WRAP}} .progress-bar-img { width: {{imgSize}}; }',
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
						'condition' => [(object) ['key' => 'iconType', 'relation' => '==', 'value' => 'iconImage']],
						'selector' => '{{PLUS_WRAP}} .progress-bar-img{border-radius: {{imgBRadius}};}',
					],
				],
			],
		);
	$attributesOptions = array_merge($attributesOptions, $globalBgOption, $globalpositioningOption, $globalPlusExtrasOption);
	
	register_block_type( 'tpgb/tp-progress-bar', array(
		'attributes' => $attributesOptions,
		'editor_script' => 'tpgb-block-editor-js',
		'editor_style'  => 'tpgb-block-editor-css',
        'render_callback' => 'tpgb_tp_progress_bar_render_callback'
    ) );
}
add_action( 'init', 'tpgb_tp_progress_bar' );