<?php
/**
 * After rendring from the block editor display output on front-end
 */
function tpgb_tp_accordion_render_callback( $attributes, $content) {
	$output = '';
	$block_id = (!empty($attributes['block_id'])) ? $attributes['block_id'] : uniqid("title");
	$accordianList = (!empty($attributes['accordianList'])) ? $attributes['accordianList'] : [];
	$titleAlign = (!empty($attributes['titleAlign'])) ? $attributes['titleAlign'] :'text-left';
	$toggleIcon = (!empty($attributes['toggleIcon'])) ? $attributes['toggleIcon'] :false;
	$iconFont = (!empty($attributes['iconFont'])) ? $attributes['iconFont'] : 'font_awesome';
	$iconName = (!empty($attributes['iconName'])) ? $attributes['iconName'] : 'fas fa-plus';
	$ActiconName = (!empty($attributes['ActiconName'])) ? $attributes['ActiconName'] : 'fas fa-minus';
	$iconAlign = (!empty($attributes['iconAlign'])) ? $attributes['iconAlign'] : 'end';
	$titleTag = (!empty($attributes['titleTag'])) ? $attributes['titleTag'] : 'h3';
	$descAlign = (!empty($attributes['descAlign'])) ? $attributes['descAlign'] :'';
	
	$className = (!empty($attributes['className'])) ? $attributes['className'] :'';
	$align = (!empty($attributes['align'])) ? $attributes['align'] :'';
	
	$blockClass = '';
	if(!empty($className)){
		$blockClass .= $className;
	}
	if(!empty($align)){
		$blockClass .= ' align'.$align;
	}
	
	$i=0;
	//Get Toogle icon
	$tgicon = '';
	if(!empty($toggleIcon)){	
		$tgicon .= '<div class="accordion-toggle-icon">';
			$tgicon .= '<span class="close-toggle-icon  toggle-icon">';
				if($iconFont == 'font_awesome'){
					$tgicon .=  '<i class="'.esc_attr($iconName).'"> </i>' ; 
				}
			$tgicon .= '</span>';
			$tgicon .= '<span class="open-toggle-icon  toggle-icon">';
				if($iconFont == 'font_awesome'){
					$tgicon .= '<i class="'.esc_attr($ActiconName).'"> </i>' ; 
				}
			$tgicon .= '</span>';
		$tgicon .= '</div>';
	}
	
	
	
	$loop_content = '';
	if(!empty($accordianList)){
		foreach ( $accordianList as $index => $item ) :
			$i++;
			
			//set active class
			$active = '';
			if($i==0){
				$active = 'active-default';
			}

			$loop_content .= '<div class="tpgb-accordion-item">';
				$loop_content .= '<div id="'.(!empty($item['UniqueId']) ? esc_attr($item['UniqueId']) :'' ).'" class="tpgb-accordion-header '.esc_attr($titleAlign).' '.esc_attr($active).'"  role="tab" data-tab="'.esc_attr($i).'" >';
					if($iconAlign == 'start'){
						$loop_content .= $tgicon;
					}
					$loop_content .= '<span class="accordion-title-icon-wrap">';
						if(!empty($item['innerIcon'])){
							$loop_content .= '<span class="accordion-tab-icon">';
								if($item['iconFonts'] == 'font_awesome'){
									$loop_content .= '<i class="'.esc_attr($item['innericonName']).'"></i>';
								}
							$loop_content .= '</span>';
						}
						$loop_content .= '<'.esc_attr($titleTag).' class="accordion-title"> '.wp_kses_post($item['title']).'</'.esc_attr($titleTag).'>';
					$loop_content .= '</span>';

					if($iconAlign == 'end'){
						$loop_content .= $tgicon;
					}

				$loop_content .= '</div>';

				$loop_content .= '<div class="tpgb-accordion-content '.esc_attr($active).'" role="tabpanel" data-tab="'.esc_attr($i).'" >';
					$loop_content .= '<div class="tpgb-content-editor '.esc_attr($descAlign).'">';
						if( !empty($item['contentType']) && $item['contentType'] == 'content'){
							$loop_content .= wp_kses_post($item['desc']);
						}
					$loop_content .= '</div>';
				$loop_content .= '</div>';
			$loop_content .= '</div>';
		endforeach;
	}
	
	$output .= '<div class="tpgb-accordion tpgb-block-'.esc_attr($block_id).' '.esc_attr($blockClass).'">';
		$output .= '<div class="tpgb-accordion-wrapper " data-type="accordion">';
			$output .= $loop_content;
		$output .= '</div>';
    $output .= "</div>";
	
	$output = Tpgb_Blocks_Global_Options::block_Wrap_Render($attributes, $output);
	
	return $output;
}

/**
 * Render for the server-side
 */
function tpgb_tp_accordion() {
	$globalBgOption = Tpgb_Blocks_Global_Options::load_bg_options();
	$globalpositioningOption = Tpgb_Blocks_Global_Options::load_positioning_options();
	$attributesOptions = [
			'block_id' => [
                'type' => 'string',
				'default' => '',
			],
			'accordianList' => [
				'type'=> 'array',
				'repeaterField' => [
					(object) [
						'title' => [
							'type' => 'string',
							'default' => 'Accordion'
						],
						'desc' => [
							'type' => 'string',
							'default' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.'
						],
						'innerIcon' => [
							'type' => 'boolean',
							'default' => false
						],
						'UniqueId' => [
							'type' => 'string',
							'default' => ''
						],
						'iconFonts' => [
							'type' => 'string',
							'default' => 'font_awesome'
						],
						'innericonName' => [
							'type'=> 'string',
							'default'=> 'fas fa-home',
						],
						'contentType' => [
							'type' => 'string',
							'default' => 'content'
						],
						'stepImage' => [
							'type' => 'string'
						],
					],
				],
				'default' => [
					[
						"_key" => '0',
						"title" => 'Accordion 1',
						"contentType" => 'content',
						'desc' => 'This is just dummy content. Put your relevant content over here. We want to remind you, smile and passion are contagious, be a carrier.',
						'innerIcon' => false,
						'iconFonts' => 'font_awesome',
						'innericonName' => 'fas fa-home',
					],
					[
						"_key" => '1',
						"title" => 'Accordion 2',
						"contentType" => 'content',
						'desc' => 'Enter your relevant content over here. This is just dummy content.  We want to remind you, smile and passion are contagious, be a carrier.',
						'innerIcon' => false,
						'iconFonts' => 'font_awesome',
						'innericonName' => 'fas fa-home',
					],
				],
			],
			'toggleIcon' => [
				'type' => 'boolean',
				'default' => false,	
			],
			'iconFont' => [
				'type' => 'string',
				'default' => 'font_awesome',	
			],
			'iconName' => [
				'type'=> 'string',
				'default'=> 'fas fa-plus ',
			],
			'ActiconName' => [
				'type'=> 'string',
				'default'=> 'fas fa-minus',
			],
			'titleTag' => [
				'type' => 'string',
				'default' => 'h3',
			],
			
			'iconAlign' => [
				'type' => 'string',
				'default' => 'end',

			],
			'howTotitle' => [
				'type' => 'string',
				'default' => 'How To'
			],
			'howTodesc' => [
				'type' => 'string',
				'default' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo. '
			],
			'howToimg' => [
				'type' => 'object',
				'default'=> [
					'url' => TPGB_ASSETS_URL.'assets/images/tpgb-placeholder.jpg'
				],	
			],
			'imageSize' => [
				'type' => 'string',
				'default' => 'full',	
			],
			'howTostep' => [
				'type' => 'string',
				'default' => 'Steps to configure the How-to Schema:'
			],
			'inIconColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-accordion-header .accordion-tab-icon{ color: {{inIconColor}}; }',
					]
				],
			],
			'inIconActcolor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-accordion-header.active .accordion-tab-icon{ color: {{inIconActcolor}}; }',
					]
				],
			],
			'inIconGap' => [
				'type' => 'object',
				'default' => [ 
					'md' => '',
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-accordion-header .accordion-tab-icon { margin-right: {{inIconGap}}; }',
					]
				],
			],
			'inIconSize' => [
				'type' => 'object',
				'default' => [ 
					'md' => '',
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-accordion-header .accordion-tab-icon{ font-size: {{inIconSize}}; }',
					]
				],
			],
			'tgiconColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'toggleIcon', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}} .tpgb-accordion-header .close-toggle-icon{ color: {{tgiconColor}}; }',
					]
				],
			],
			'tgiconActcolor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'toggleIcon', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}} .tpgb-accordion-header .open-toggle-icon{ color: {{tgiconActcolor}}; }',
					]
				],
			],
			'tgiconGap' => [
				'type' => 'object',
				'default' => [ 
					'md' => '',
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'iconAlign', 'relation' => '==', 'value' => 'start']],
						'selector' => '{{PLUS_WRAP}} .tpgb-accordion-header .accordion-toggle-icon { margin-right: {{tgiconGap}}; }',
					],
					(object) [
						'condition' => [(object) ['key' => 'iconAlign', 'relation' => '==', 'value' => 'end']],
						'selector' => '{{PLUS_WRAP}} .tpgb-accordion-header .accordion-toggle-icon { margin-left: {{tgiconGap}}; }',
					],
				],
			],
			'tgiconSize' => [
				'type' => 'object',
				'default' => [ 
					'md' => '',
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'toggleIcon', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}} .tpgb-accordion-header .toggle-icon{ font-size: {{tgiconSize}}; }',
					]
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
						'selector' => '{{PLUS_WRAP}} .tpgb-accordion-header .accordion-title',
					]
				],
			],
			'titleAlign' =>[
				'type' => 'string',
				'default' => 'text-left',
			],
			'titleColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-accordion-header .accordion-title{color : {{titleColor}}}',
					]
				],
			],
			'titleActcolor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-accordion-header.active .accordion-title{color : {{titleActcolor}}}',
					]
				],
			],
			'titleHvrcolor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-accordion-header:hover .accordion-title{color : {{titleHvrcolor}}}',
					]
				],
			], 
			'titlePadding' => [
				'type' => 'object',
				'default' => (object) [ 
					'md' => ['top' => '', 'bottom' => '', 'left' => '', 'right' => ''],
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-accordion-header{ padding : {{titlePadding}}}',
					]
				],
			],
			'accorBetspace' => [
				'type' => 'object',
				'default' => [
					'md' => '',
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-accordion-wrapper .tpgb-accordion-item{ margin-bottom : {{accorBetspace}}}',
					]
				],
			],
			'titleBorder' => [
				'type' => 'object',
				'default' => (object) [
					'openBorder' => 0,
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-accordion-header',
					]
				],
			],
			'titleBradius' => [
				'type' => 'object',
				'default' => (object) [ 
					'md' => ['top' => '', 'bottom' => '', 'left' => '', 'right' => ''],
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-accordion-header{ border-radius : {{titleBradius}} }',
					]
				],
			],
			'titleActborder' => [
				'type' => 'object',
				'default' => (object) [
					'openBorder' => 0,
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-accordion-header.active',
					]
				],
			],
			'titleActBradius' => [
				'type' => 'object',
				'default' => (object) [ 
					'md' => ['top' => '', 'bottom' => '', 'left' => '', 'right' => ''],
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-accordion-header.active{ border-radius : {{titleActBradius}} }',
					]
				],
			],
			'titlebgType' => [
				'type' => 'object',
				'default' => (object) [
					'openBg'=> 0,
					'bgType' => 'color',
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-accordion-header',
					]
				],
			],
			'titleBshadow' => [
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
						'selector' => '{{PLUS_WRAP}} .tpgb-accordion-header',
					]
				],
			],
			'titleActbgtype' => [
				'type' => 'object',
				'default' => (object) [
					'openBg'=> 0,
					'bgType' => 'color',
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-accordion-header.active',
					]
				],
			],
			'titleActBshadow' => [
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
						'selector' => '{{PLUS_WRAP}} .tpgb-accordion-header.active',
					]
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
						'selector' => '{{PLUS_WRAP}} .tpgb-accordion-content .tpgb-content-editor',
					]
				],
			],
			'descAlign' => [
				'type'=> 'string',
				'default' => 'text-left'
			],
			'descColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-accordion-content .tpgb-content-editor{color : {{descColor}}}',
					]
				],
			],
			'descMargin' => [
				'type' => 'object',
				'default' => (object) [ 
					'md' => ['top' => '', 'bottom' => '', 'left' => '', 'right' => ''],
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-accordion-content { margin : {{descMargin}}}',
					]
				],
			],
			'descPadding' => [	
				'type' => 'object',
				'default' => (object) [ 
					'md' => ['top' => '', 'bottom' => '', 'left' => '', 'right' => ''],
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-accordion-content { padding : {{descPadding}}}',
					]
				],
			],
			'descbgType' => [
				'type' => 'object',
				'default' => (object) [
					'openBg'=> 0,
					'bgType' => 'color',
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-accordion-content',
					]
				],
			],
		];
		
	$attributesOptions = array_merge($attributesOptions, $globalBgOption, $globalpositioningOption);
	
	register_block_type( 'tpgb/tp-accordion', array(
		'attributes' => $attributesOptions,
		'editor_script' => 'tpgb-block-editor-js',
		'editor_style'  => 'tpgb-block-editor-css',
        'render_callback' => 'tpgb_tp_accordion_render_callback'
    ) );
}


add_action( 'init', 'tpgb_tp_accordion' );