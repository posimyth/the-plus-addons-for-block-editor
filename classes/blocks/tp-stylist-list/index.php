<?php
/* Block : Stylist List
 * @since : 1.0.0
 */
function tpgb_tp_stylist_list_render_callback( $attributes, $content) {
	$output = '';
    $block_id = (!empty($attributes['block_id'])) ? $attributes['block_id'] : uniqid("title");
    $alignment = (!empty($attributes['alignment'])) ? $attributes['alignment'] : 'align-left';
    $iconAlignment = (!empty($attributes['iconAlignment'])) ? $attributes['iconAlignment'] : 'd-flex-top';
    $listsRepeater = (!empty($attributes['listsRepeater'])) ? $attributes['listsRepeater'] : [];
    $hoverInverseEffect = (!empty($attributes['hoverInverseEffect'])) ? $attributes['hoverInverseEffect'] : false;
	
    $readMoreToggle = (!empty($attributes['readMoreToggle'])) ? $attributes['readMoreToggle'] : false;
	
	$blockClass = Tp_Blocks_Helper::block_wrapper_classes( $attributes );
	
	$alignattr ='';
	if($alignment!==''){
		$alignattr .= (!empty($alignment['md'])) ? ' align-'.esc_attr($alignment['md']) : ' align-left';
		$alignattr .= (!empty($alignment['sm'])) ? ' tablet-align-'.esc_attr($alignment['sm']) : ' tablet-align-left';
		$alignattr .= (!empty($alignment['xs'])) ? ' mobile-align-'.esc_attr($alignment['xs']) : ' mobile-align-left';
	}
	$iconalignattr ='';
	if($iconAlignment!==''){
		$iconalignattr = ($iconAlignment) ? ' d-flex-center' : ' d-flex-top';
	}
	
	$hoverInvertClass ='';
	if($iconAlignment!==''){
		$hoverInvertClass = ($hoverInverseEffect) ? ' hover-inverse-effect' : '';
	}
	
	$i=0;$j=0;
	
    $output .= '<div class="tpgb-stylist-list tpgb-block-'.esc_attr($block_id).' '.esc_attr($alignattr).' '.esc_attr($hoverInvertClass).' '.esc_attr($blockClass).'">';
		if(!empty($listsRepeater)){
		
			
			$output .= '<ul class="tpgb-icon-list-items'.esc_attr($iconalignattr).'">';
				foreach ( $listsRepeater as $index => $item ) :
					
					$i++;
					$active_class=$descurl_open=$descurl_close='';
					if($i==1){
						$active_class='active';
					}
					//Url
					if(!empty($item['descurl']) && !empty($item['descurl']['url'])){
						$target = ($item['descurl']['target']!='') ? '_blank' : '';
						$nofollow = ($item['descurl']['nofollow']!='') ? 'nofollow' : '';
						$descurl_open ='<a href="'.esc_url($item['descurl']['url']).'" target="'.esc_attr($target).'" rel="'.esc_attr($nofollow).'">';
						$descurl_close ='</a>';
					}
					
					//Icon
					$icons ='';
					if(!empty($item['selectIcon'])){
						$icons .= '<div class="tpgb-icon-list-icon">';
							if($item['selectIcon']=='fontawesome' && !empty($item['iconFontawesome'])){ 
								$icons .='<i class="list-icon '.esc_attr($item['iconFontawesome']).'" aria-hidden="true"></i>';
							}else if($item['selectIcon'] == 'img' && !empty($item['iconImg']['url'])){
								$icon_image = $item['iconImg']['url'];
								$icons .= '<img src="'.esc_url($icon_image).'"  alt="'.esc_attr__('icon-img','tpgb').'" />';
							} 
						$icons .= '</div>';
					}
					
					//Description and Pin
					$itemdesc = '';
					if(!empty($item['description'])){
						$itemdesc .= '<div class="tpgb-icon-list-text"><p>'.wp_kses_post($item['description']).'</p></div>';
					}

					//Item Content
					$output .= '<li class="tpgb-icon-list-item tp-repeater-item-'.esc_attr($item['_key']).' '.esc_attr($active_class).'" >';
						$output .= $descurl_open;
						$output .= $icons;
						$output .= $itemdesc;
						$output .= $descurl_close;
					$output .= "</li>";
				endforeach;
			$output .= "</ul>";
			
		}
    $output .= "</div>";
	
	$output = Tpgb_Blocks_Global_Options::block_Wrap_Render($attributes, $output);
	
    return $output;
}

/**
 * Render for the server-side
 */
function tpgb_tp_stylist_list() {
	$globalBgOption = Tpgb_Blocks_Global_Options::load_bg_options();
	$globalpositioningOption = Tpgb_Blocks_Global_Options::load_positioning_options();
	$globalPlusExtrasOption = Tpgb_Blocks_Global_Options::load_plusextras_options();
	
	$attributesOptions = array(
			'block_id' => array(
                'type' => 'string',
				'default' => '',
			),
			'hover_bg_style' => array(
                'type' => 'boolean',
				'default' => false,
			),
			'listsRepeater' => [
				'type' => 'array',
				'repeaterField' => [
					(object) [
						'description' => [
							'type' => 'string',
							'default' => 'List item',
						],
						'selectIcon' => [
							'type' => 'string',
							'default' => 'fontawesome',
						],
						'iconFontawesome' => [
							'type' => 'string',
							'default' => 'fas fa-check-circle',
						],
						'itemTooltip' => [
							'type' => 'boolean',
							'default' => false,
						],
						'tooltipContentType' => [
							'type' => 'string',
							'default' => '',
						],
						'tooltipTypo' => [
							'type' => 'object',
							'default' => (object) [
								'openTypography' => 0,
							],
						],
						'tooltipColor' => [
							'type' => 'string',
							'default' => '',
						],
					],
				], 
				'default' => [
					[
						"_key" => '0',
						"description" => "List item 1",
						"selectIcon" => "fontawesome",
						"iconFontawesome" => "fas fa-check-circle",
						'tooltipTypo' => ['openTypography' => 0 ],
					],
					[
						"_key" => '1',
						"description" => "List item 2",
						"selectIcon" => "fontawesome",
						"iconFontawesome" => "fas fa-check-circle",
						'tooltipTypo' => ['openTypography' => 0 ],
					],
					[ 
						"_key" => '2',
						"description" => "List item 3",
						"selectIcon" => "fontawesome",
						"iconFontawesome" => "fas fa-check-circle",
						'tooltipTypo' => ['openTypography' => 0 ],
					]
				],
			],
			'listType' => [
                'type' => 'string',
				'default' => 'vertical',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'listType', 'relation' => '==', 'value' => 'horizontal']],
						'selector' => '{{PLUS_WRAP}} .tpgb-icon-list-items, {{PLUS_WRAP}} .tpgb-icon-list-items .tpgb-icon-list-item{flex-wrap: wrap;flex-flow: wrap;}',
					],
				],
			],
			'readMoreToggle' => [
                'type' => 'boolean',
				'default' => false,
			],
			
			'listSpaceBetween' => [
                'type' => 'object',
				'default' => ['md' => ''],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'listType', 'relation' => '==', 'value' => 'vertical']],
						'selector' => '{{PLUS_WRAP}} .tpgb-icon-list-items .tpgb-icon-list-item:not(:first-child){margin-top: calc({{listSpaceBetween}}/2);}{{PLUS_WRAP}} .tpgb-icon-list-items .tpgb-icon-list-item:not(:last-child){margin-bottom: calc({{listSpaceBetween}}/2);}{{PLUS_WRAP}} .tpgb-icon-list-items .tpgb-icon-list-item:not(:last-child):before{ top: calc(100% + {{listSpaceBetween}}/2); }',
					],
					(object) [
						'condition' => [(object) ['key' => 'listType', 'relation' => '==', 'value' => 'horizontal']],
						'selector' => '{{PLUS_WRAP}} .tpgb-icon-list-items .tpgb-icon-list-item{margin-top: calc({{listSpaceBetween}}/2);}{{PLUS_WRAP}} .tpgb-icon-list-items .tpgb-icon-list-item{margin-bottom: calc({{listSpaceBetween}}/2);}{{PLUS_WRAP}} .tpgb-icon-list-items .tpgb-icon-list-item:before{ top: calc(100% + {{listSpaceBetween}}/2);}',
					],
				],
			],
			'horizontalSpaceBetween' => [
                'type' => 'object',
				'default' => ['md' => ''],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'alignment', 'relation' => '==', 'value' => 'left'],['key' => 'listType', 'relation' => '==', 'value' => 'horizontal']],
						'selector' => '{{PLUS_WRAP}} .tpgb-icon-list-items .tpgb-icon-list-item:not(:last-child){margin-right: {{horizontalSpaceBetween}};}',
					],
					(object) [
						'condition' => [(object) ['key' => 'alignment', 'relation' => '==', 'value' => 'right'],['key' => 'listType', 'relation' => '==', 'value' => 'horizontal']],
						'selector' => '{{PLUS_WRAP}} .tpgb-icon-list-items .tpgb-icon-list-item:not(:first-child){margin-left: {{horizontalSpaceBetween}};}',
					],
					(object) [
						'condition' => [(object) ['key' => 'alignment', 'relation' => '==', 'value' => 'center'],['key' => 'listType', 'relation' => '==', 'value' => 'horizontal']],
						'selector' => '{{PLUS_WRAP}} .tpgb-icon-list-items .tpgb-icon-list-item{margin-left:0;margin-right:0}{{PLUS_WRAP}} .tpgb-icon-list-items .tpgb-icon-list-item:not(:first-child){margin-left: calc({{horizontalSpaceBetween}}/2);}{{PLUS_WRAP}} .tpgb-icon-list-items .tpgb-icon-list-item:not(:last-child){margin-right: calc({{horizontalSpaceBetween}}/2);}',
					],
					(object) [
						'condition' => [(object) ['key' => 'alignment', 'relation' => '==', 'value' => 'justify'],['key' => 'listType', 'relation' => '==', 'value' => 'horizontal']],
						'selector' => '{{PLUS_WRAP}} .tpgb-icon-list-items .tpgb-icon-list-item{margin-left:0;margin-right:0}{{PLUS_WRAP}} .tpgb-icon-list-items .tpgb-icon-list-item:not(:first-child){margin-left: calc({{horizontalSpaceBetween}}/2);}{{PLUS_WRAP}} .tpgb-icon-list-items .tpgb-icon-list-item:not(:last-child){margin-right: calc({{horizontalSpaceBetween}}/2);}',
					],
				],
			],
			'alignment' => [
                'type' => 'object',
				'default' => ['md' => 'left'],
			],
			'separatorColor' => [
                'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-icon-list-items .tpgb-icon-list-item:not(:last-child):before{border-bottom : 1px solid {{separatorColor}};}{{PLUS_WRAP}} .tpgb-icon-list-items .tpgb-icon-list-item{width: 100%;}',
					],
				],
			],
			
			'iconNormalColor' => [
                'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-icon-list-icon .list-icon{color: {{iconNormalColor}};}',
					],
				],
			],
			'iconHoverColor' => [
                'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-icon-list-item:hover .tpgb-icon-list-icon .list-icon{color: {{iconHoverColor}};}',
					],
				],
			],
			'iconSize' => [
                'type' => 'object',
				'default' => ['md' => ''],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-icon-list-item .tpgb-icon-list-icon .list-icon{font-size: {{iconSize}};}',
					],
				],
			],
			'iconImgSize' => [
                'type' => 'object',
				'default' => ['md' => ''],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-icon-list-item .tpgb-icon-list-icon img{max-width: {{iconImgSize}};}',
					],
				],
			],
			'iconAlignment' => [
                'type' => 'boolean',
				'default' => true,
			],
			
			'iconAdvancedStyle' => [
                'type' => 'boolean',
				'default' => false,
			],
			
			'textTypo' => [
                'type' => 'object',
				'default' => (object) [
					'openTypography' => 0,
					'size' => [ 'md' => '', 'unit' => 'px' ],
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-icon-list-text,{{PLUS_WRAP}} .tpgb-icon-list-text p',
					],
				],
			],
			'textNormalColor' => [
                'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-icon-list-text{color: {{textNormalColor}};}',
					],
				],
			],
			'textHoverColor' => [
                'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-icon-list-item:hover .tpgb-icon-list-text{color: {{textHoverColor}};}',
					],
				],
			],
			'textIndent' => [
                'type' => 'object',
				'default' => ['md' => ''],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-stylist-list .tpgb-icon-list-text{padding-left: {{textIndent}};}',
					],
				],
			],
			
			'textPadding' => [
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
						'selector' => '{{PLUS_WRAP}} .tpgb-icon-list-item{padding: {{textPadding}};}',
					],
				],
			],
			'textBorder' => [
				'type' => 'object',
				'default' => (object) [
					'openBorder' => 0,
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-icon-list-item',
					],
				],
			],
			'textBRadius' => [
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
						'selector' => '{{PLUS_WRAP}} .tpgb-icon-list-item{border-radius: {{textBRadius}};}',
					],
				],
			],
			'textBg' => [
				'type' => 'object',
				'default' => (object) [
					'openBg'=> 0,
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-icon-list-item',
					],
				],
			],
			'titleBShadow' => [
				'type' => 'object',
				'default' => (object) [
					'openShadow' => 0,
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-icon-list-item',
					],
				],
			],
			
			'textHBorder' => [
				'type' => 'object',
				'default' => (object) [
					'openBorder' => 0,
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-icon-list-item:hover',
					],
				],
			],
			'textHBRadius' => [
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
						'selector' => '{{PLUS_WRAP}} .tpgb-icon-list-item:hover{border-radius: {{textHBRadius}};}',
					],
				],
			],
			'textBgHover' => [
				'type' => 'object',
				'default' => (object) [
					'openBg'=> 0,
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-icon-list-item:hover',
					],
				],
			],
			'titleHBShadow' => [
				'type' => 'object',
				'default' => (object) [
					'openShadow' => 0,
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-icon-list-item:hover',
					],
				],
			],
			
			'hoverInverseEffect' => [
                'type' => 'boolean',
				'default' => false,
			],
			'unhoverItemOpacity' => [
                'type' => 'string',
				'default' => 0.6,
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'hoverInverseEffect', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}}.hover-inverse-effect:hover .on-hover .tpgb-icon-list-item{opacity: {{unhoverItemOpacity}};}',
					],
				],
			],
		);
	$attributesOptions = array_merge($attributesOptions,$globalBgOption,$globalpositioningOption,$globalPlusExtrasOption);
	
	register_block_type( 'tpgb/tp-stylist-list', array(
		'attributes' => $attributesOptions,
		'editor_script' => 'tpgb-block-editor-js',
		'editor_style'  => 'tpgb-block-editor-css',
        'render_callback' => 'tpgb_tp_stylist_list_render_callback'
    ) );
}
add_action( 'init', 'tpgb_tp_stylist_list' );