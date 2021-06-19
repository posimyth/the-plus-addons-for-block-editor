<?php
/**
 * Block : TP Pro Paragraph
 * @since : 1.1.1
 */
function tpgb_tp_pro_paragraph_render_callback( $attributes ) {
	$output = '';
    $block_id = (!empty($attributes['block_id'])) ? $attributes['block_id'] : uniqid("title");
    $title = (!empty($attributes['title'])) ? $attributes['title'] : '';
    $Showtitle = (!empty($attributes['Showtitle'])) ? $attributes['Showtitle'] : false;
    $titleTag = (!empty($attributes['titleTag'])) ? $attributes['titleTag'] : 'h3';
	$content = (!empty($attributes['content'])) ? $attributes['content'] : '';
	$descTag = (!empty($attributes['descTag'])) ? $attributes['descTag'] : 'p';
	
	$blockClass = Tp_Blocks_Helper::block_wrapper_classes( $attributes );
	
    $output .= '<div class="tpgb-pro-paragraph tpgb-block-'.esc_attr($block_id).' '.esc_attr($blockClass).'">';
		if(!empty($Showtitle) && !empty($title)){
			$output .= '<'.esc_attr($titleTag).' class="pro-heading-inner">';
				$output .= wp_kses_post($title);
			$output .= '</'.esc_attr($titleTag).'>';
		}
		if(!empty($content)){
			$output .= '<div class="pro-paragraph-inner">';
				$output .= '<'.esc_attr($descTag).'>'.wp_kses_post($content).'</'.esc_attr($descTag).'>';
			$output .= '</div>';
		}
	$output .= "</div>";
	
	$output = Tpgb_Blocks_Global_Options::block_Wrap_Render($attributes, $output);
	
    return $output;
}

/**
 * Render for the server-side
 */
function tpgb_tp_pro_paragraph() {
	$globalBgOption = Tpgb_Blocks_Global_Options::load_bg_options();
	$globalpositioningOption = Tpgb_Blocks_Global_Options::load_positioning_options();
	$globalPlusExtrasOption = Tpgb_Blocks_Global_Options::load_plusextras_options();
	
	$attributesOptions = [
			'block_id' => [
                'type' => 'string',
				'default' => '',
			],
			'Showtitle' => [
				'type' => 'boolean',
				'default' => true,
			],
			'title' => [
				'type' => 'string',
				'default' => 'Space for your Pretty Title',
			],
			'titleTag' => [
				'type' => 'string',
				'default' => 'h3',
			],
			'descTag' => [
				'type' => 'string',
				'default' => 'p',
			],
			'content' => [
				'type' => 'string',
				'default' => 'Just say anything, George, say what ever’s natural, the first thing that comes to your mind. Take that you mutated son-of-a-bitch. My pine, why you. You space bastard, you killed a pine. You do? Yeah, it’s 8:00. Hey, McFly, I thought I told you never to come in here. Well it’s gonna cost you. How much money you got on you?',
			],
			'alignment' => [
				'type' => 'object',
				'default' => [ 'md' => '' ],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .pro-heading-inner,{{PLUS_WRAP}} .pro-paragraph-inner{ text-align: {{alignment}}; }',
					],
				],
			],
			
			'textTypo' => [
				'type'=> 'object',
				'default'=> (object) [
					'openTypography' => 0,
					'size' => [ 'md' => '', 'unit' => 'px' ],
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .pro-paragraph-inner',
					],
				],
			],
			
			'textColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
						(object) [
						'selector' => '{{PLUS_WRAP}} .pro-paragraph-inner,{{PLUS_WRAP}} .pro-paragraph-inner p{ color: {{textColor}}; }',
					],
				],
			],
			
			'linkColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
						(object) [
						'selector' => '{{PLUS_WRAP}} .pro-paragraph-inner a{ color: {{linkColor}}; }',
					],
				],
			],
			'linkHoverColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
						(object) [
						'selector' => '{{PLUS_WRAP}} .pro-paragraph-inner a:hover{ color: {{linkHoverColor}}; }',
					],
				],
			],
			'textShadow' => [
				'type' => 'object',
				'default' => (object) [
					'openShadow' => 0,
					'typeShadow' => 'text-shadow',
					'horizontal' => 2,
					'vertical' => 3,
					'blur' => 2,
					'color' => "rgba(0,0,0,0.5)",
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .pro-paragraph-inner',
					],
				],
			],
			'HovertextShadow' => [
				'type' => 'object',
				'default' => (object) [
					'openShadow' => 0,
					'typeShadow' => 'text-shadow',
					'horizontal' => 2,
					'vertical' => 3,
					'blur' => 2,
					'color' => "rgba(0,0,0,0.5)",
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .pro-paragraph-inner:hover',
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
						'condition' => [(object) ['key' => 'Showtitle', 'relation' => '==', 'value' => true ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-pro-paragraph .pro-heading-inner',
					],
				],
			],
			
			'titleColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
						(object) [
							'condition' => [(object) ['key' => 'Showtitle', 'relation' => '==', 'value' => true ]],
							'selector' => '{{PLUS_WRAP}}.tpgb-pro-paragraph .pro-heading-inner{ color: {{titleColor}}; }',
					],
				],
			],
			'titleBtmSpace' => [
                'type' => 'object',
				'default' => [ 'md' => '', 'unit' => 'px' ],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'Showtitle', 'relation' => '==', 'value' => true ]],
						'selector' => '{{PLUS_WRAP}} .pro-heading-inner{margin-bottom: {{titleBtmSpace}};}',
					],
				],
			],
			'titleShadow' => [
				'type' => 'object',
				'default' => (object) [
					'openShadow' => 0,
					'typeShadow' => 'text-shadow',
					'horizontal' => 2,
					'vertical' => 3,
					'blur' => 2,
					'color' => "rgba(0,0,0,0.5)",
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'Showtitle', 'relation' => '==', 'value' => true ]],
						'selector' => '{{PLUS_WRAP}} .pro-heading-inner',
					],
				],
			],
			'HovertitleShadow' => [
				'type' => 'object',
				'default' => (object) [
					'openShadow' => 0,
					'typeShadow' => 'text-shadow',
					'horizontal' => 2,
					'vertical' => 3,
					'blur' => 2,
					'color' => "rgba(0,0,0,0.5)",
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'Showtitle', 'relation' => '==', 'value' => true ]],
						'selector' => '{{PLUS_WRAP}} .pro-heading-inner:hover',
					],
				],
			],
			
		];
	
	$attributesOptions = array_merge($attributesOptions,$globalBgOption,$globalpositioningOption,$globalPlusExtrasOption);
	
	register_block_type( 'tpgb/tp-pro-paragraph', [
		'attributes' => $attributesOptions,
		'editor_script' => 'tpgb-block-editor-js',
		'editor_style'  => 'tpgb-block-editor-css',
        'render_callback' => 'tpgb_tp_pro_paragraph_render_callback'
    ] );
}
add_action( 'init', 'tpgb_tp_pro_paragraph' );