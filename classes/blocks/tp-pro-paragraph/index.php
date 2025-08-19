<?php
/**
 * Block : TP Pro Paragraph
 * @since : 1.3.0
 */
defined( 'ABSPATH' ) || exit;

function tpgb_tp_pro_paragraph_render_callback( $attributes, $content ) {
	$output = '';
    $block_id = (!empty($attributes['block_id'])) ? $attributes['block_id'] : uniqid("title");

	$pattern = '/\btpgb-block-'.esc_attr($block_id).'/';
	
	if (preg_match($pattern, $content)) {
		if( class_exists('Tpgb_Blocks_Global_Options') ){
            $global_blocks = Tpgb_Blocks_Global_Options::get_instance();
            $content = $global_blocks::block_row_conditional_render($attributes,$content);
        }
		return $content;
	}
	
    $title = (!empty($attributes['title'])) ? $attributes['title'] : '';
    $Showtitle = (!empty($attributes['Showtitle'])) ? $attributes['Showtitle'] : false;
    $titleTag = (!empty($attributes['titleTag'])) ? $attributes['titleTag'] : 'h3';
	$content = (!empty($attributes['content'])) ? $attributes['content'] : '';
	$descTag = (!empty($attributes['descTag'])) ? $attributes['descTag'] : 'p';
	$dropCap = (!empty($attributes['dropCap'])) ? $attributes['dropCap'] : false;
	$dcapView = (!empty($attributes['dcapView'])) ? $attributes['dcapView'] : '';
	
	$blockClass = Tp_Blocks_Helper::block_wrapper_classes( $attributes );
	
    $output .= '<div class="tpgb-pro-paragraph tpgb-block-'.esc_attr($block_id).' '.esc_attr($blockClass).'">';
		if(!empty($Showtitle) && !empty($title)){
			$output .= '<'.Tp_Blocks_Helper::validate_html_tag($titleTag).' class="pro-heading-inner">';
				$output .= wp_kses_post($title);
			$output .= '</'.Tp_Blocks_Helper::validate_html_tag($titleTag).'>';
		}
		if(!empty($content)){

			if(class_exists('Tpgbp_Pro_Blocks_Helper')){
				$content = (!empty($attributes['content'])) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_val($content,['blockName' => 'tpgb/tp-pro-paragraph']) : '';
			}

			$output .= '<div class="pro-paragraph-inner '.( !empty($dropCap) ? ' tpgb-drop-cap tpgb-drop-'.esc_attr($dcapView).'' : '' ).'">';
				$output .= '<'.Tp_Blocks_Helper::validate_html_tag($descTag).' class="tpgb-propara-txt">'.wp_kses_post($content).'</'.Tp_Blocks_Helper::validate_html_tag($descTag).'>';
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
	/* $globalBgOption = Tpgb_Blocks_Global_Options::load_bg_options();
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
				'default' => 'Save the Earth for future Generations.',
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
				'default' => 'No human technology can replace `nature`s technology`, perfected over hundreds of millions of years to sustain life on Earth. For those in power, the questions are straightforward. Are they prepared to jeopardise their careers – or their profits – for our children’s children? Are they ready to put short-term politicking aside and help deliver a sustainable plan for the future? Are they willing to take difficult decisions on behalf of voters they’ll never meet?',
			],
			'alignment' => [
				'type' => 'object',
				'default' => [ 'md' => '' ],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .pro-heading-inner,{{PLUS_WRAP}} .pro-paragraph-inner{ text-align: {{alignment}}; }',
					],
				],
				'scopy' => true,
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
				'scopy' => true,
			],
			
			'textColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
						(object) [
						'selector' => '{{PLUS_WRAP}} .pro-paragraph-inner,{{PLUS_WRAP}} .pro-paragraph-inner p{ color: {{textColor}}; }',
					],
				],
				'scopy' => true,
			],
			
			'linkColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
						(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-pro-paragraph .pro-paragraph-inner a{ color: {{linkColor}}; }',
					],
				],
				'scopy' => true,
			],
			'linkHoverColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
						(object) [
						'selector' => '{{PLUS_WRAP}} .pro-paragraph-inner a:hover{ color: {{linkHoverColor}}; }',
					],
				],
				'scopy' => true,
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
				'scopy' => true,
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
				'scopy' => true,
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
						'selector' => '{{PLUS_WRAP}}.tpgb-pro-paragraph .pro-heading-inner,{{PLUS_WRAP}}.tpgb-pro-paragraph .pro-heading-inner>a',
					],
				],
				'scopy' => true,
			],
			
			'titleColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
						(object) [
							'condition' => [(object) ['key' => 'Showtitle', 'relation' => '==', 'value' => true ]],
							'selector' => '{{PLUS_WRAP}}.tpgb-pro-paragraph .pro-heading-inner,{{PLUS_WRAP}}.tpgb-pro-paragraph .pro-heading-inner>a{ color: {{titleColor}}; }',
					],
				],
				'scopy' => true,
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
				'scopy' => true,
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
				'scopy' => true,
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
				'scopy' => true,
			],
			'ulMargin' => [
				'type' => 'object',
				'default' => (object) [ 
					'md' => [
						"top" => '',
						'bottom' => '',
						'left' => '',
						'right' => '',
					],
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-pro-paragraph .pro-paragraph-inner ul,{{PLUS_WRAP}}.tpgb-pro-paragraph .pro-paragraph-inner ol{margin: {{ulMargin}} !important;}',
					],
				],
				'scopy' => true,
			],
			'ulPadding' => [
				'type' => 'object',
				'default' => (object) [ 
					'md' => [
						"top" => '',
						'bottom' => '',
						'left' => '',
						'right' => '',
					],
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-pro-paragraph .pro-paragraph-inner ul,{{PLUS_WRAP}}.tpgb-pro-paragraph .pro-paragraph-inner ol{padding: {{ulPadding}} !important;}',
					],
				],
				'scopy' => true,
			],
			'pCol' => [
				'type' => 'object',
				'default' => [ 
					'md' => '',
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} { columns : {{pCol}}; }',
					],
				],
			],
			'pcolspac' => [
				'type' => 'object',
				'default' => [ 
					'md' => '',
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} { column-gap : {{pcolspac}}; }',
					],
				],
			],
			'dropCap' =>[
				'type' => 'boolean',
				'default' => false,
			],
			'dcapView' => [
				'type'=> 'string',
				'default'=> '',
			],
			'dcapCol' => [
				'type'=> 'string',
				'default'=> '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'dropCap', 'relation' => '==', 'value' => true ],['key' => 'dcapView', 'relation' => '==', 'value' => '']],
						'selector' => '{{PLUS_WRAP}} .tpgb-drop-cap p::first-letter{ color : {{dcapCol}}; }',
					],
					(object) [
						'condition' => [(object) ['key' => 'dropCap', 'relation' => '==', 'value' => true ], ['key' => 'dcapView', 'relation' => '==', 'value' => 'stacked']],
						'selector' => '{{PLUS_WRAP}} .tpgb-drop-cap.tpgb-drop-stacked p::first-letter{ background-color : {{dcapCol}}; }',
					],
					(object) [
						'condition' => [(object) ['key' => 'dropCap', 'relation' => '==', 'value' => true ],['key' => 'dcapView', 'relation' => '==', 'value' => 'framed']],
						'selector' => '{{PLUS_WRAP}} .tpgb-drop-cap.tpgb-drop-framed p::first-letter{ color : {{dcapCol}}; border-color : {{dcapCol}}; }',
					],
				],
			],
			'dcapseCol' => [
				'type'=> 'string',
				'default'=> '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'dropCap', 'relation' => '==', 'value' => true ],['key' => 'dcapView', 'relation' => '==', 'value' => 'stacked']],
						'selector' => '{{PLUS_WRAP}} .tpgb-drop-cap.tpgb-drop-stacked p::first-letter{ color : {{dcapseCol}}; }',
					],
					(object) [
						'condition' => [(object) ['key' => 'dropCap', 'relation' => '==', 'value' => true ],['key' => 'dcapView', 'relation' => '==', 'value' => 'framed']],
						'selector' => '{{PLUS_WRAP}} .tpgb-drop-cap.tpgb-drop-framed p::first-letter{ background-color : {{dcapseCol}}; }',
					],
				],
			],
			'captShadow' => [
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
						'condition' => [(object) ['key' => 'dropCap', 'relation' => '==', 'value' => true ] ],
						'selector' => '{{PLUS_WRAP}} .tpgb-drop-cap p::first-letter',
					],
				],
			],
			'tcapspac' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'dropCap', 'relation' => '==', 'value' => true ] ],
						'selector' => '{{PLUS_WRAP}} .tpgb-drop-cap p::first-letter{ margin-right: {{tcapspac}}px; }',
					],
				],
			],
			'tcapSize' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'dropCap', 'relation' => '==', 'value' => true ] ],
						'selector' => '{{PLUS_WRAP}} .tpgb-drop-cap p::first-letter{ padding : {{tcapSize}}px; }',
					],
				],
			],
			'tcapbrad' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'dropCap', 'relation' => '==', 'value' => true ],['key' => 'dcapView', 'relation' => '==', 'value' => 'framed']],
						'selector' => '{{PLUS_WRAP}} .tpgb-drop-cap p::first-letter{ border-radius: {{tcapbrad}}px; }',
					],
				],
			],
			'boWidth' => [
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
						'condition' => [(object) ['key' => 'dropCap', 'relation' => '==', 'value' => true ],['key' => 'dcapView', 'relation' => '==', 'value' => 'framed']],
						'selector' => '{{PLUS_WRAP}} .tpgb-drop-cap p::first-letter{ border-width : {{boWidth}}; }',
					],
				],
			],
			'dcapTypo' => [
				'type'=> 'object',
				'default'=> (object) [
					'openTypography' => 0,
					'size' => [ 'md' => '', 'unit' => 'px' ],
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'dropCap', 'relation' => '==', 'value' => true ] ],
						'selector' => '{{PLUS_WRAP}} .tpgb-drop-cap p::first-letter',
					],
				],
				'scopy' => true,
			],
		];
	
	$attributesOptions = array_merge($attributesOptions,$globalBgOption,$globalpositioningOption,$globalPlusExtrasOption);
	
	register_block_type( 'tpgb/tp-pro-paragraph', [
		'attributes' => $attributesOptions,
		'editor_script' => 'tpgb-block-editor-js',
		'editor_style'  => 'tpgb-block-editor-css',
        'render_callback' => 'tpgb_tp_pro_paragraph_render_callback'
    ] ); */
	$block_data = Tpgb_Blocks_Global_Options::merge_options_json(__DIR__, 'tpgb_tp_pro_paragraph_render_callback');
	register_block_type( $block_data['name'], $block_data );
}
add_action( 'init', 'tpgb_tp_pro_paragraph' );