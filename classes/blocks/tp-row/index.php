<?php
/* Block : TP Row(Section)
 * @since : 1.1.6
 */
defined( 'ABSPATH' ) || exit;

function tpgb_tp_section_row_render_callback( $attributes, $content) {
	$output = '';
    $block_id = (!empty($attributes['block_id'])) ? $attributes['block_id'] : uniqid("title");
    $stretchRow = (!empty($attributes['stretchRow'])) ? $attributes['stretchRow'] : false;
    $height = (!empty($attributes['height'])) ? $attributes['height'] : '';
	$columnPosition = (!empty($attributes['columnPosition'])) ? $attributes['columnPosition'] : '';
    $sectionWidth = (!empty($attributes['sectionWidth'])) ? $attributes['sectionWidth'] : '';
    $customClass = (!empty($attributes['customClass'])) ? $attributes['customClass'] : '';
    $customId = (!empty($attributes['customId'])) ? 'id="'.esc_attr($attributes['customId']).'"' : '';
    
	$sectionClass = Tp_Blocks_Helper::block_wrapper_classes( $attributes );
	if( !empty( $height ) ){
		$sectionClass .= ' tpgb-section-height-'.esc_attr($height);
	}
	if( !empty( $stretchRow ) ){
		$sectionClass .= ' tpgb-section-stretch-row alignfull';
	}
	
	$containerClass ='';
	if($columnPosition!='' && !empty( $height )){
		$containerClass .= ' tpgb-align-items-'.esc_attr($columnPosition);
	}
	if($sectionWidth=='full'){
		$containerClass .= ' tpgb-container-fluid';
	}else{
		$containerClass .= ' tpgb-container';
	}
	
    $output .= '<div '.$customId.' class="tpgb-section tpgb-block-'.esc_attr($block_id).' '.esc_attr($sectionClass).' '.esc_attr($customClass).' " data-id="'.esc_attr($block_id).'">';
		$output .= '<div class="tpgb-section-wrap '.esc_attr($containerClass).'">';
				$output .= $content;
		$output .= "</div>";
    $output .= "</div>";
	
	$output = Tpgb_Blocks_Global_Options::block_row_conditional_render($attributes, $output);
	
    return $output;
}

/**
 * Render for the server-side
 */
function tpgb_tp_section_row() {
	
	$displayRules = Tpgb_Display_Conditions_Rules::tpgb_display_option();
	
	$attributesOptions = [
			'block_id' => [
                'type' => 'string',
				'default' => '',
			],
			'stretchRow' => [
                'type' => 'boolean',
				'default' => false,
			],
			'columns' => [
                'type' => 'number',
				'default' => '',
			],
			'sectionWidth' => [
				'type' => 'string',
				'default' => 'boxed',	
			],
			'height' => [
				'type' => 'string',
				'default' => '',	
			],
			'minHeight' => [
				'type' => 'object',
				'default' => [ 
					'md' => 300,
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'height', 'relation' => '==', 'value' => 'min-height']],
						'selector' => '{{PLUS_WRAP}} > .tpgb-section-wrap{ min-height: {{minHeight}}; }',
					],
				],
			],
			'columnPosition' => [
                'type' => 'string',
				'default' => 'center',
			],
			'verticalPosition' => [
                'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} > .tpgb-section-wrap .tpgb-column-editor > .tpgb-column-wrap > .tpgb-column-inner,{{PLUS_WRAP}} > .tpgb-section-wrap > .tpgb-column > .tpgb-column-wrap > .tpgb-column-inner{ align-content: {{verticalPosition}};align-item: {{verticalPosition}}; }',
					],
				],
			],
			'gutterSpace' => [
				'type' => 'object',
				'default' => [ 
					'md' => 15,
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-section-editor > .tpgb-section-wrap > .block-editor-inner-blocks > .block-editor-block-list__layout > [data-type="tpgb/tp-column"] > .tpgb-column-resizable > .tpgb-column > .tpgb-column-wrap { padding: {{gutterSpace}}; } {{PLUS_WRAP}} > .tpgb-section-wrap > .tpgb-column > .tpgb-column-wrap, {{PLUS_WRAP}} > .tpgb-section-wrap > .tpgb-column > .inner-wrapper-sticky > .tpgb-column-wrap{ padding: {{gutterSpace}}; }',
					],
				],
			],
			'overflow' => [
                'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-section{ overflow: {{overflow}}; }',
					],
				],
			],
			'customClass' => [
				'type' => 'string',
				'default' => '',	
			],
			'customId' => [
				'type' => 'string',
				'default' => '',	
			],
			
			
			'shapeTop' => [
                'type' => 'string',
				'default' => '',
			],
			
			'shapeBottom' => [
                'type' => 'string',
				'default' => '',
			],
			
			
			'NormalBg' => [
				'type' => 'object',
				'default' => (object) [
					'openBg'=> 0,
					'bgType' => '',
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}',
					],
				],
				'scopy' => true,
			],
			'HoverBg' => [
				'type' => 'object',
				'default' => (object) [
					'openBg'=> 0,
					'bgType' => '',
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}:hover',
					],
				],
				'scopy' => true,
			],
			'NormalBorder' => [
				'type' => 'object',
				'default' => (object) [
					'openBorder' => 0,
					'type' => '',
					'color' => '',
					'width' => (object) [
						'md' => [
							"top" => '',
							'bottom' => '',
							'left' => '',
							'right' => '',
						],
						"unit" => "",
					],
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}',
					],
				],
				'scopy' => true,
			],
			'HoverBorder' => [
				'type' => 'object',
				'default' => (object) [
					'openBorder' => 0,
					'type' => '',
					'color' => '',
					'width' => (object) [
						'md' => (object)[
							"top" => '',
							'bottom' => '',
							'left' => '',
							'right' => '',
						],
						"unit" => "",
					],
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}:hover',
					],
				],
				'scopy' => true,
			],
			'NormalBradius' => [
				'type' => 'object',
				'default' => (object) [ 
					'md' => [
						"top" => '',
						"bottom" => '',
						"left" => '',
						"right" => '',
					],
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}{ border-radius: {{NormalBradius}}; }',
					],
				],
				'scopy' => true,
			],
			'HoverBradius' => [
				'type' => 'object',
				'default' => (object) [ 
					'md' => [
						"top" => '',
						"bottom" => '',
						"left" => '',
						"right" => '',
					],
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}:hover{ border-radius: {{HoverBradius}}; }',
					],
				],
				'scopy' => true,
			],
			'NormalBShadow' => [
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
						'selector' => '{{PLUS_WRAP}}',
					],
				],
				'scopy' => true,
			],
			'HoverBShadow' => [
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
						'selector' => '{{PLUS_WRAP}}:hover',
					],
				],
				'scopy' => true,
			],
			'Margin' => [
				'type' => 'object',
				'default' => (object) [ 
					'md' => [
						"top" => '',
						"bottom" => '',
						"left" => '',
						"right" => '',
					],
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}{margin: {{Margin}};}',
					],
				],
				'scopy' => true,
			],
			'Padding' => [
				'type' => 'object',
				'default' => (object) [ 
					'md' => [
						"top" => '',
						"bottom" => '',
						"left" => '',
						"right" => '',
					],
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}{padding: {{Padding}} !important;}',
					],
				],
				'scopy' => true,
			],
			'ZIndex' => [
				'type' => 'number',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}{z-index: {{ZIndex}};}',
					],
				],
				'scopy' => true,
			],
			
			'HideDesktop' => [
				'type' => 'boolean',
				'default' => false,
				'style' => [
					(object) [
						'selector' => '@media (min-width: 1201px){ .edit-post-visual-editor {{PLUS_WRAP}}{display: block;opacity: .5;} {{PLUS_WRAP}}{ display:none } }',
					],
				],
				'scopy' => true,
			],
			'HideTablet' => [
				'type' => 'boolean',
				'default' => false,
				'style' => [
					(object) [
						'selector' => '@media (min-width: 768px) and (max-width: 1200px){ .edit-post-visual-editor {{PLUS_WRAP}}{display: block;opacity: .5;} {{PLUS_WRAP}}{ display:none } }',
					],
				],
				'scopy' => true,
			],
			'HideMobile' => [
				'type' => 'boolean',
				'default' => false,
				'style' => [
					(object) [
						'selector' => '@media (max-width: 767px){ .edit-post-visual-editor {{PLUS_WRAP}}{display: block;opacity: .5;} {{PLUS_WRAP}}{ display:none !important; } }',
					],
				],
				'scopy' => true,
			],
		];
		
	$attributesOptions = array_merge( $attributesOptions, $displayRules );
	
	register_block_type( 'tpgb/tp-row', array(
		'attributes' => $attributesOptions,
		'editor_script' => 'tpgb-block-editor-js',
		'editor_style'  => 'tpgb-block-editor-css',
        'render_callback' => 'tpgb_tp_section_row_render_callback'
    ) );
}
add_action( 'init', 'tpgb_tp_section_row' );