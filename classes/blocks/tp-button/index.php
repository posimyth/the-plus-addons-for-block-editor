<?php
/* Block : TP Button
 * @since : 1.0.0
 */
defined( 'ABSPATH' ) || exit;

function tpgb_button_render_callback( $attributes ) {
	$output = '';
    $block_id = (!empty($attributes['block_id'])) ? $attributes['block_id'] : uniqid("title");
	$styleType = (!empty($attributes['styleType'])) ? $attributes['styleType'] : 'style-1';
	$btnHvrType = (!empty($attributes['btnHvrType'])) ? $attributes['btnHvrType'] : 'hover-left';
	$iconPosition = (!empty($attributes['iconPosition'])) ? $attributes['iconPosition'] : 'iconAfter';
	$iconType = (!empty($attributes['iconType'])) ? $attributes['iconType'] : 'fontAwesome';
	$fontAwesomeIcon = (!empty($attributes['fontAwesomeIcon'])) ? $attributes['fontAwesomeIcon'] : '';
	$btnText = (!empty($attributes['btnText'])) ? $attributes['btnText'] : '';
	$hoverText = (!empty($attributes['hoverText'])) ? $attributes['hoverText'] : '';
	$btnLink = (!empty($attributes['btnLink']['url'])) ? $attributes['btnLink']['url'] : '';
	$target = (!empty($attributes['btnLink']['target'])) ? '_blank' : '';
	$nofollow = (!empty($attributes['btnLink']['nofollow'])) ? 'nofollow' : '';
	
	$blockClass = Tp_Blocks_Helper::block_wrapper_classes( $attributes );
	
	$iconHover='';
	if($styleType=='style-11' || $styleType=='style-13'){
		$iconHover .=$btnHvrType;
	}
	$s23VrtclCntr ='';
	$getBfrIcon ='';
	$getBfrIcon .='<span class="btn-icon'.($styleType!='style-17' ? ' button-before' : '').'">';
	$getBfrIcon .='<i class="'.esc_attr($fontAwesomeIcon).'"></i>';
	$getBfrIcon .='</span>';
	
	$getAftrIcon ='';
	$getAftrIcon .='<span class="btn-icon'.($styleType!='style-17' ? ' button-after' : '').'">';
	$getAftrIcon .='<i class="'.esc_attr($fontAwesomeIcon).'"></i>';
	$getAftrIcon .='</span>';
	
	$getButtonSource='';
	
		if($styleType!='style-3' && $styleType!='style-6' && $styleType!='style-7' && $styleType!='style-9' && $styleType!='style-23' && $iconType=='fontAwesome' && $iconPosition=='iconBefore'){
			$getButtonSource .= $getBfrIcon;
		}
		if($styleType!='style-17' && $styleType!='style-23'){
			$getButtonSource .= esc_html($btnText);
		}
		
		if($styleType!='style-3' && $styleType!='style-6' && $styleType!='style-7' && $styleType!='style-9' && $styleType!='style-23' && $iconType=='fontAwesome' && $iconPosition=='iconAfter'){
			$getButtonSource .= $getAftrIcon;
		}
		if($styleType=='style-12'){
			$getButtonSource .='<div class="button_line"></div>';
		}

    $output .= '<div class="tpgb-plus-button tpgb-block-'.esc_attr($block_id).' button-'.esc_attr($styleType).' '.esc_attr($iconHover).' '.esc_attr($blockClass).'">';
		$output .='<div class="animted-content-inner ">';
			$output .='<a href="'.esc_url($btnLink).'" target="'.esc_attr($target).'" rel="'.esc_attr($nofollow).'" class="button-link-wrap '.esc_attr($s23VrtclCntr).'" role="button" data-hover="'.esc_attr($hoverText).'">';
				if($styleType != 'style-17' && $styleType != 'style-23'){
					$output .='<span>'.$getButtonSource.'</span>';
				}
			$output .='</a>';
		$output .='</div>';
    $output .= '</div>';

	$output = Tpgb_Blocks_Global_Options::block_Wrap_Render($attributes, $output);
	
    return $output;
}

/**
 * Render for the server-side
 */
function tpgb_tp_button() {
	$globalPlusExtrasOption = Tpgb_Blocks_Global_Options::load_plusextras_options();
	$globalBgOption = Tpgb_Blocks_Global_Options::load_bg_options();
	$globalpositioningOption = Tpgb_Blocks_Global_Options::load_positioning_options();
  
	$attributesOptions = array(
			'block_id' => array(
                'type' => 'string',
				'default' => '',
			),
			'styleType' => [
				'type' => 'string',
				'default' => 'style-1',	
			],
			'btnText' => [
				'type' => 'string',
				'default' => 'Buy Now',	
			],
			'hoverText' => [
				'type' => 'string',
				'default' => 'Click Here',
			],
			'btnLink' => [
				'type'=> 'object',
				'default'=> [
					'url' => '',	
					'target' => '',	
					'nofollow' => ''
				],
			],
			'Alignment' => [
				'type' => 'object',
				'default' => 'left',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-plus-button{ text-align: {{Alignment}}; }',
					],
				],
				'scopy' => true,
			],
			'btnHvrType' => [
				'type' => 'string',
				'default' => 'hover-left',	
			],
			'iconType' => [
				'type' => 'string',
				'default' => 'fontAwesome',	
			],
			'fontAwesomeIcon' => [
				'type'=> 'string',
				'default'=> 'fa fa-chevron-right',
			],
			'iconPosition' => [
				'type' => 'string',
				'default' => 'iconAfter',	
			],
			'iconSpace' => [
				'type' => 'object',
				'default' => (object) [ 
					'md' => '',
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'styleType', 'relation' => '!=', 'value' => ['style-3', 'style-6', 'style-7', 'style-9'] ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-plus-button .button-link-wrap .button-before { margin-right: {{iconSpace}}; } {{PLUS_WRAP}}.tpgb-plus-button .button-link-wrap .button-after { margin-left: {{iconSpace}}; } ',
					],
				],
				'scopy' => true,
			],
			'iconSize' => [
				'type' => 'object',
				'default' => (object) [ 
					'md' => '',
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'styleType', 'relation' => '!=', 'value' => ['style-3','style-6','style-7','style-9'] ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-plus-button .button-link-wrap .btn-icon { font-size: {{iconSize}}; }',
					],
				],
				'scopy' => true,
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
						'condition' => [(object) ['key' => 'styleType', 'relation' => '!=', 'value' => 'style-3' ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-plus-button:not(.button-style-11):not(.button-style-17) .button-link-wrap , {{PLUS_WRAP}}.tpgb-plus-button.button-style-11 .button-link-wrap > span',
					],
				],
				'scopy' => true,
			],
			'texTyp' => [
				'type'=> 'object',
				'default'=> (object) [
					'openTypography' => 0,
					'size' => [ 'md' => '', 'unit' => 'px' ],
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'btnText', 'relation' => '!=', 'value' => '' ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-plus-button .button-link-wrap',
					],
				],
				'scopy' => true,
			],
			'btnTextNmlColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'btnText', 'relation' => '!=', 'value' => '' ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-plus-button .button-link-wrap{ color: {{btnTextNmlColor}}; }',
					],
					(object) [
						'condition' => [(object) ['key' => 'styleType', 'relation' => '!=', 'value' => ['style-3','style-6','style-7','style-9'] ],['key' => 'iconType', 'relation' => '==', 'value' => 'fontAwesome' ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-plus-button .button-link-wrap .btn-icon{ color: {{btnTextNmlColor}};}',
					],
				],
				'scopy' => true,
			],
			'iconNmlColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'styleType', 'relation' => '!=', 'value' => ['style-3','style-6','style-7','style-9'] ],['key' => 'iconType', 'relation' => '==', 'value' => 'fontAwesome' ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-plus-button .button-link-wrap .btn-icon{ color: {{iconNmlColor}};}',
					],
				],
				'scopy' => true,
			],
			'BNmlColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'styleType', 'relation' => '==', 'value' => ['style-12'] ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-plus-button .button-link-wrap .button_line{ background: {{BNmlColor}}; }',
					],
				],
				'scopy' => true,
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
						'condition' => [(object) ['key' => 'styleType', 'relation' => '==', 'value' => 'style-1' ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-plus-button.button-style-1 .button-link-wrap',
					],
					(object) [
						'condition' => [(object) ['key' => 'styleType', 'relation' => '==', 'value' => 'style-4' ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-plus-button.button-style-4 a.button-link-wrap',
					],
					(object) [
						'condition' => [(object) ['key' => 'styleType', 'relation' => '==', 'value' => 'style-8' ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-plus-button.button-style-8 .button-link-wrap',
					],
					(object) [
						'condition' => [(object) ['key' => 'styleType', 'relation' => '==', 'value' => 'style-11' ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-plus-button.button-style-11 .button-link-wrap',
					],
					(object) [
						'condition' => [(object) ['key' => 'styleType', 'relation' => '==', 'value' => 'style-13' ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-plus-button.button-style-13 .button-link-wrap',
					],
					(object) [
						'condition' => [(object) ['key' => 'styleType', 'relation' => '==', 'value' => 'style-20' ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-plus-button.button-style-20 .button-link-wrap',
					],
				],
				'scopy' => true,
			],
			'bgNormalB' => [
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
						'condition' => [(object) ['key' => 'styleType', 'relation' => '==', 'value' => 'style-1' ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-plus-button.button-style-1 .button-link-wrap',
					],
					(object) [
						'condition' => [(object) ['key' => 'styleType', 'relation' => '==', 'value' => 'style-4' ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-plus-button.button-style-4 .button-link-wrap',
					],
					(object) [
						'condition' => [(object) ['key' => 'styleType', 'relation' => '==', 'value' => 'style-8' ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-plus-button.button-style-8 .button-link-wrap',
					],
					(object) [
						'condition' => [(object) ['key' => 'styleType', 'relation' => '==', 'value' => 'style-11' ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-plus-button.button-style-11 .button-link-wrap',
					],
					(object) [
						'condition' => [(object) ['key' => 'styleType', 'relation' => '==', 'value' => 'style-13' ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-plus-button.button-style-13 .button-link-wrap',
					],
					(object) [
						'condition' => [(object) ['key' => 'styleType', 'relation' => '==', 'value' => 'style-20' ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-plus-button.button-style-20 .button-link-wrap',
					],
				],
				'scopy' => true,
			],
			'normalBRadius' => [
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
						'condition' => [(object) ['key' => 'styleType', 'relation' => '!=', 'value' => ['style-12', 'style-2', 'style-3', 'style-5', 'style-6', 'style-7', 'style-9', 'style-18'] ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-plus-button .button-link-wrap{border-radius: {{normalBRadius}};}',
					],
				],
				'scopy' => true,
			],
			'nmlboxShadow' => [
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
						'condition' => [(object) ['key' => 'styleType', 'relation' => '==', 'value' => 'style-1' ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-plus-button.button-style-1 .button-link-wrap',
					],
					(object) [
						'condition' => [(object) ['key' => 'styleType', 'relation' => '==', 'value' => 'style-4' ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-plus-button.button-style-4 .button-link-wrap',
					],
					(object) [
						'condition' => [(object) ['key' => 'styleType', 'relation' => '==', 'value' => 'style-8' ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-plus-button.button-style-8 .button-link-wrap',
					],
					(object) [
						'condition' => [(object) ['key' => 'styleType', 'relation' => '==', 'value' => 'style-11' ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-plus-button.button-style-11 .button-link-wrap',
					],
					(object) [
						'condition' => [(object) ['key' => 'styleType', 'relation' => '==', 'value' => 'style-13' ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-plus-button.button-style-13 .button-link-wrap',
					],
					(object) [
						'condition' => [(object) ['key' => 'styleType', 'relation' => '==', 'value' => 'style-20' ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-plus-button.button-style-20 .button-link-wrap',
					],
				],
				'scopy' => true,
			],
			'borderHeight' => [
				'type' => 'object',
				'default' => (object) [ 
					'md' => '',
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'styleType', 'relation' => '==', 'value' => 'style-12' ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-plus-button .button-link-wrap .button_line{ height: {{borderHeight}}; }',
					],
				],
				'scopy' => true,
			],
			'btnTextHvrColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'btnText', 'relation' => '!=', 'value' => '' ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-plus-button .button-link-wrap:hover{ color: {{btnTextHvrColor}}; }
						{{PLUS_WRAP}}.tpgb-plus-button.button-style-11 .button-link-wrap::before{ color: {{btnTextHvrColor}}; }',
					],
					(object) [
						'condition' => [(object) ['key' => 'styleType', 'relation' => '!=', 'value' => ['style-3','style-6','style-7','style-9'] ],['key' => 'iconType', 'relation' => '==', 'value' => 'fontAwesome' ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-plus-button .button-link-wrap:hover .btn-icon{ color: {{btnTextHvrColor}}; }',
					],
				],
				'scopy' => true,
			],
			'iconHvrColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'styleType', 'relation' => '!=', 'value' => ['style-3','style-6','style-7','style-9'] ],['key' => 'iconType', 'relation' => '==', 'value' => 'fontAwesome' ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-plus-button .button-link-wrap:hover .btn-icon{ color: {{iconHvrColor}}; }',
					],
				],
				'scopy' => true,
			],
			'BHoverColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'styleType', 'relation' => '==', 'value' => ['style-12'] ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-plus-button .button-link-wrap:hover .button_line{ background: {{BHoverColor}}; }',
					],
				],
				'scopy' => true,
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
						'condition' => [(object) ['key' => 'styleType', 'relation' => '==', 'value' => 'style-1' ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-plus-button.button-style-1 .button-link-wrap::before',
					],
					(object) [
						'condition' => [(object) ['key' => 'styleType', 'relation' => '==', 'value' => 'style-4' ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-plus-button.button-style-4 a.button-link-wrap::after',
					],
					(object) [
						'condition' => [(object) ['key' => 'styleType', 'relation' => '==', 'value' => 'style-8' ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-plus-button.button-style-8 .button-link-wrap:hover',
					],
					(object) [
						'condition' => [(object) ['key' => 'styleType', 'relation' => '==', 'value' => 'style-11' ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-plus-button.button-style-11 .button-link-wrap::before',
					],
					(object) [
						'condition' => [(object) ['key' => 'styleType', 'relation' => '==', 'value' => 'style-13' ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-plus-button.button-style-13 .button-link-wrap::before, {{PLUS_WRAP}}.tpgb-plus-button.button-style-13 .button-link-wrap::after',
					],
					(object) [
						'condition' => [(object) ['key' => 'styleType', 'relation' => '==', 'value' => 'style-20' ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-plus-button.button-style-20 .button-link-wrap::after',
					],
				],
				'scopy' => true,
			],
			'bgHoverB' => [
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
						'condition' => [(object) ['key' => 'styleType', 'relation' => '==', 'value' => 'style-1' ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-plus-button.button-style-1 .button-link-wrap:hover',
					],
					(object) [
						'condition' => [(object) ['key' => 'styleType', 'relation' => '==', 'value' => 'style-4' ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-plus-button.button-style-4 .button-link-wrap:hover',
					],
					(object) [
						'condition' => [(object) ['key' => 'styleType', 'relation' => '==', 'value' => 'style-8' ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-plus-button.button-style-8 .button-link-wrap:hover',
					],
					(object) [
						'condition' => [(object) ['key' => 'styleType', 'relation' => '==', 'value' => 'style-11' ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-plus-button.button-style-11 .button-link-wrap:hover',
					],
					(object) [
						'condition' => [(object) ['key' => 'styleType', 'relation' => '==', 'value' => 'style-13' ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-plus-button.button-style-13 .button-link-wrap:hover',
					],
					(object) [
						'condition' => [(object) ['key' => 'styleType', 'relation' => '==', 'value' => 'style-20' ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-plus-button.button-style-20 .button-link-wrap:hover',
					],
				],
				'scopy' => true,
			],
			'hoverBRadius' => [
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
						'condition' => [(object) ['key' => 'styleType', 'relation' => '!=', 'value' => ['style-12','style-2','style-3','style-5', 'style-6','style-7','style-9','style-18'] ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-plus-button .button-link-wrap:hover{border-radius: {{hoverBRadius}};} ',
					],
				],
				'scopy' => true,
			],
			'hvrboxShadow' => [
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
						'condition' => [(object) ['key' => 'styleType', 'relation' => '==', 'value' => 'style-1' ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-plus-button.button-style-1 .button-link-wrap:hover',
					],
					(object) [
						'condition' => [(object) ['key' => 'styleType', 'relation' => '==', 'value' => 'style-4' ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-plus-button.button-style-4 .button-link-wrap:hover',
					],
					(object) [
						'condition' => [(object) ['key' => 'styleType', 'relation' => '==', 'value' => 'style-8' ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-plus-button.button-style-8 .button-link-wrap:hover',
					],
					(object) [
						'condition' => [(object) ['key' => 'styleType', 'relation' => '==', 'value' => 'style-11' ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-plus-button.button-style-11 .button-link-wrap:hover',
					],
					(object) [
						'condition' => [(object) ['key' => 'styleType', 'relation' => '==', 'value' => 'style-13' ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-plus-button.button-style-13 .button-link-wrap:hover',
					],
					(object) [
						'condition' => [(object) ['key' => 'styleType', 'relation' => '==', 'value' => 'style-20' ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-plus-button.button-style-20 .button-link-wrap:hover',
					],
				],
				'scopy' => true,
			],
			'shakeAnimate' => [
				'type' => 'boolean',
				'default' => false,	
			],
		);
	$attributesOptions = array_merge($attributesOptions,$globalPlusExtrasOption,$globalBgOption,$globalpositioningOption);
	
	register_block_type( 'tpgb/tp-button', array(
		'attributes' => $attributesOptions,
		'editor_script' => 'tpgb-block-editor-js',
		'editor_style'  => 'tpgb-block-editor-css',
        'render_callback' => 'tpgb_button_render_callback'
    ) );
}
add_action( 'init', 'tpgb_tp_button' );