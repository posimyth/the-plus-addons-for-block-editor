<?php
/**
 * After rendring from the block editor display output on front-end
 */
function tpgb_tp_social_icons_render_callback( $attributes, $content) {
    $block_id = (!empty($attributes['block_id'])) ? $attributes['block_id'] : uniqid("title");
	$style = (!empty($attributes['style'])) ? $attributes['style'] : 'style-1';
	$socialIcon = (!empty($attributes['socialIcon'])) ? $attributes['socialIcon'] : [];
	$Alignment = (!empty($attributes['Alignment'])) ? $attributes['Alignment'] : 'text-center';
	$className = (!empty($attributes['className'])) ? $attributes['className'] :'';
	$align = (!empty($attributes['align'])) ? $attributes['align'] :'';
	
	$blockClass = '';
	if(!empty($className)){
		$blockClass .= $className;
	}
	if(!empty($align)){
		$blockClass .= ' align'.$align;
	}
	
	$alignattr ='';
	if($Alignment!==''){
		$alignattr .= (!empty($Alignment['md'])) ? ' text-'.esc_attr($Alignment['md']) : ' text-center';
		$alignattr .= (!empty($Alignment['sm'])) ? ' tsocialtext-'.esc_attr($Alignment['sm']) : '';
		$alignattr .= (!empty($Alignment['xs'])) ? ' msocialtext-'.esc_attr($Alignment['xs']) : '';
	}
	
	
	$i =0;
	$output = '';
    $output .= '<div class="tpgb-social-icons '.esc_attr($style).' '.esc_attr($alignattr).' tpgb-block-'.esc_attr($block_id).' '.esc_attr($blockClass).'">';
		if(!empty($socialIcon)){
		$output .='<ul class="tpgb-social-list ">';
			
				foreach ( $socialIcon as $index => $network ) :
					//Tooltip
					$i++;
					
					$uniqid=uniqid("tooltip");
					
					$output .= '<li id="'.esc_attr($uniqid).'" class="tp-repeater-item-'.esc_attr($network['_key']).' '.esc_attr($style).'"  >';
						if(!empty($network['linkUrl']['url']) && !empty($network['socialNtwk'])){
							$target = (!empty($network['linkUrl']['target'])) ? '_blank' : '';
							$nofollow = (!empty($network['linkUrl']['nofollow'])) ? 'nofollow' : '';
							$output .= '<div class="tpgb-social-loop-inner ">';
								$output .= '<a class="tpgb-icon-link" href="'.esc_url($network['linkUrl']['url']).'" target="'.esc_attr($target).'" rel="'.esc_attr($nofollow).'">';
									if($network['socialNtwk']=='custom' && $network['customType']=='icon' && !empty($network['customIcons'])) {
										$output .= '<span class="tpgb-social-icn">';
											$output .= '<i class="'.esc_attr($network['customIcons']).'"></i>';
										$output .= '</span>';
									}else if($network['socialNtwk']=='custom' && $network['customType']=='image' && !empty($network['imgField']) && !empty($network['imgField']['url'])) {
										$output .= '<span class="tpgb-social-icn social-img">';
											$output .= '<img src="'.esc_url($network['imgField']['url']).'" alt="'.esc_attr__('Custom icon','tpgb').'" />';
										$output .= '</span>';
									}else if($network['socialNtwk']!='custom'){
										$output .= '<span class="tpgb-social-icn">';
											$output .= '<i class="'.esc_attr($network['socialNtwk']).'"></i>';
										$output .= '</span>';
									}
									
									if(!empty($network['title']) && $style=='style-1'){
										$output .= '<span class="tpgb-social-title" data-lang="en">'.esc_html($network['title']).'</span>';
									}
									
								$output .= '</a>';
							$output .= '</div>';
						}
					$output .= '</li>';
					
						endforeach;
				$output .='</ul>';
			}
			
    $output .= '</div>';
	
	$output = Tpgb_Blocks_Global_Options::block_Wrap_Render($attributes, $output);
	
    return $output;
}
/**
 * Render for the server-side
 */
function tpgb_social_icons() {
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
		'socialIcon' => [
			'type'=> 'array',
			'repeaterField' => [
				(object) [
					'socialNtwk' => [
						'type' => 'string',
						'default' => 'fab fa-facebook'
					],
					'customType' => [
						'type' => 'string',
						'default' => 'icon',
					],
					'customIcons' => [
						'type'=> 'string',
						'default'=> 'fab fa-whatsapp',
					],
					'imageField' => [
						'type' => 'object',
						'default' => [
							'url' => TPGB_ASSETS_URL.'assets/images/tpgb-placeholder.jpg',
						],
					],
					'linkUrl' => [
						'type'=> 'object',
						'default'=>[
							'url' => '#',	
							'target' => '',	
							'nofollow' => ''	
						]
					],
					'title' => [
						'type' => 'string',
						'default' => 'Network'
					],
					'iconNmlColor' => [
						'type' => 'string',
						'default' => '',
						'style' => [
							(object) [
								'selector' => '{{PLUS_WRAP}}.tpgb-social-icons ul.tpgb-social-list {{TP_REPEAT_ID}}:not(.style-12) .tpgb-icon-link{ color: {{iconNmlColor}}; }',
							],
						],
					],
					'iconHvrColor' => [
						'type' => 'string',
						'default' => '',
						'style' => [
							(object) [
								'selector' => '{{PLUS_WRAP}}.tpgb-social-icons ul.tpgb-social-list {{TP_REPEAT_ID}}:not(.style-12):not(.style-4):hover .tpgb-icon-link { color: {{iconHvrColor}}; }',
							], 
						],
					],
					'nmlBG' => [
						'type' => 'string',
						'default' => '',
						'style' => [
							(object) [
								'selector' => '{{PLUS_WRAP}}.tpgb-social-icons {{TP_REPEAT_ID}}:not(.style-3):not(.style-9):not(.style-11):not(.style-12) .tpgb-icon-link{ background: {{nmlBG}}; }',
							], 
						],
					],
					'hvrBG' => [
						'type' => 'string',
						'default' => '',
						'style' => [
							(object) [
								'selector' => '{{PLUS_WRAP}}.tpgb-social-icons {{TP_REPEAT_ID}}:not(.style-3):not(.style-9):not(.style-11):not(.style-12):hover .tpgb-icon-link { background: {{hvrBG}}; }',
							], 
						],
					],
					'nmlBColor' => [
						'type' => 'string',
						'default' => '',
						'style' => [
							(object) [
								'selector' => '{{PLUS_WRAP}}.tpgb-social-icons ul.tpgb-social-list {{TP_REPEAT_ID}}:not(.style-11):not(.style-12):not(.style-13) .tpgb-icon-link { border-color: {{nmlBColor}}; }',
							],
						],
					],
					'hvrBColor' => [
						'type' => 'string',
						'default' => '',
						'style' => [
							(object) [
								'selector' => '{{PLUS_WRAP}}.tpgb-social-icons ul.tpgb-social-list {{TP_REPEAT_ID}}:not(.style-11):not(.style-12):not(.style-13):hover .tpgb-icon-link { border-color: {{hvrBColor}}; }',
							], 
						], 
					],
					'itemTooltip' => [
						'type' => 'boolean',
						'default' => false,
					],
					'tooltipText' => [
						'type' => 'string',
						'default' => '',
					],
					'tooltipTypo' => [
						'default' => ['openTypography' => 0 ],
					],
					'tooltipColor' => [
						'default' => '',
					],
				],
			],
			'default' => [
				[
					'_key' => '0',
					'socialNtwk' => 'fab fa-facebook-f',
					'title' => 'Facebook',
					'linkUrl'=> [
						'url' => '#',	
						'target' => '',	
						'nofollow' => ''	
					],
					'nmlBG' => '#3a579a',
					'nmlBColor' => '#3a579a',
					'customType' => 'icon',
					'customIcons' => 'fab fa-whatsapp',
					'imageField' => [
						'url' => TPGB_ASSETS_URL.'assets/images/tpgb-placeholder.jpg',
					],
					'tooltipTypo' => ['openTypography' => 0 ],
				],
				[
					'_key' => '1',
					'socialNtwk' => 'fab fa-youtube',
					'title' => 'Youtube',
					'linkUrl'=> [
						'url' => '#',	
						'target' => '',	
						'nofollow' => ''	
					],
					'nmlBG' => '#FF0000',
					'nmlBColor' => '#FF0000',
					'customType' => 'icon',
					'customIcons' => 'fab fa-whatsapp',
					'imageField' => [
						'url' => TPGB_ASSETS_URL.'assets/images/tpgb-placeholder.jpg',
					],
					'tooltipTypo' => ['openTypography' => 0 ],
				],
				[
					'_key' => '2',
					'socialNtwk' => 'fab fa-twitter',
					'title' => 'Twitter',
					'linkUrl'=> [
						'url' => '#',	
						'target' => '',	
						'nofollow' => ''	
					],
					'nmlBG' => '#0aaded',
					'nmlBColor' => '#0aaded',
					'customType' => 'icon',
					'customIcons' => 'fab fa-whatsapp',
					'imageField' => [
						'url' => TPGB_ASSETS_URL.'assets/images/tpgb-placeholder.jpg',
					],
					'tooltipTypo' => ['openTypography' => 0 ],
				],
			],
		],
		'Alignment' => [
			'type' => 'object',
			'default' => ['md' => 'center'],
		],
		'iconGap' => [
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
					'selector' => '{{PLUS_WRAP}}.tpgb-social-icons ul.tpgb-social-list li{margin: {{iconGap}};}',
				],
			],
		],
		'iconSize' => [
			'type' => 'object',
			'default' => [ 
				'md' => '',
				"unit" => 'px',
			],
			'style' => [
				(object) [
					'selector' => '{{PLUS_WRAP}} ul.tpgb-social-list .style-1 .tpgb-icon-link , {{PLUS_WRAP}} ul.tpgb-social-list .style-16 .tpgb-icon-link{ font-size: {{iconSize}}; }',
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
					'selector' => ' {{PLUS_WRAP}} ul.tpgb-social-list .tpgb-social-icn.social-img img{ max-width: {{imgWidth}}; }',
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
					'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-16']],
					'selector' => ' {{PLUS_WRAP}}.style-16 ul.tpgb-social-list .style-16 .tpgb-icon-link{ width: {{iconWidth}}; }',
				],
			],
		],
		'iconHeight' => [
			'type' => 'object',
			'default' => [ 
				'md' => '',
				"unit" => 'px',
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-16']],
					'selector' => ' {{PLUS_WRAP}}.style-16 ul.tpgb-social-list .style-16 .tpgb-icon-link{ height: {{iconHeight}}; }',
				],
			],
		],
		'borderStyle' => [
			'type' => 'string',
			'default' => 'solid',	
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-16' ] ],
					'selector' => ' {{PLUS_WRAP}}.tpgb-social-icons.style-16 ul.tpgb-social-list .tpgb-icon-link{border-style: {{borderStyle}};}',
				],
			],
		],
		'borderWidth' => [
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
					'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-16' ] , ['key' => 'borderStyle', 'relation' => '!=', 'value' => 'none' ]],
					'selector' => ' {{PLUS_WRAP}}.tpgb-social-icons.style-16 ul.tpgb-social-list .tpgb-icon-link{border-width: {{borderWidth}};}',
				],
			],
		],
		'iconBRadius' => [
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
					'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => ['style-1','style-16'] ]],
					'selector' => '{{PLUS_WRAP}}.tpgb-social-icons ul.tpgb-social-list .tpgb-icon-link , {{PLUS_WRAP}}.tpgb-social-icons.style-16 ul.tpgb-social-list .tpgb-icon-link{border-radius: {{iconBRadius}};}',
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
					'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-1' ]],
					'selector' => '{{PLUS_WRAP}}.tpgb-social-icons .tpgb-icon-link .tpgb-social-title',
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
					'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-16' ] ],
					'selector' => '{{PLUS_WRAP}}.tpgb-social-icons.style-16 ul.tpgb-social-list .tpgb-icon-link',
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
					'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-16' ] ],
					'selector' => '{{PLUS_WRAP}}.tpgb-social-icons.style-16 ul.tpgb-social-list li:hover .tpgb-icon-link',
				],
			],
		],
		
	);
	$attributesOptions = array_merge($attributesOptions,$globalBgOption,$globalpositioningOption,$globalPlusExtrasOption);
	
	register_block_type( 'tpgb/tp-social-icons', array(
		'attributes' => $attributesOptions,
		'editor_script' => 'tpgb-block-editor-js',
		'editor_style'  => 'tpgb-block-editor-css',
        'render_callback' => 'tpgb_tp_social_icons_render_callback'
    ) );
}
add_action( 'init', 'tpgb_social_icons' );