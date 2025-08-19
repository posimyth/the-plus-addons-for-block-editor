<?php
/* Block : Progress Tracker
 * @since : 3.0.0
 */
defined( 'ABSPATH' ) || exit;

function tpgb_progress_tracker_render_callback( $attributes, $content) {
    $block_id = (!empty($attributes['block_id'])) ? $attributes['block_id'] : uniqid("title");
	$progressType = (!empty($attributes['progressType'])) ? $attributes['progressType'] : 'horizontal';
	$horizontalPos = (!empty($attributes['horizontalPos'])) ? $attributes['horizontalPos'] : 'top';
	$hzDirection = (!empty($attributes['hzDirection'])) ? $attributes['hzDirection'] : 'ltr';
	$verticalPos = (!empty($attributes['verticalPos'])) ? $attributes['verticalPos'] : 'left';
	$circularPos = (!empty($attributes['circularPos'])) ? $attributes['circularPos'] : 'top-left';
	$percentageText = (!empty($attributes['percentageText'])) ? $attributes['percentageText'] : false;
	$percentageStyle = (!empty($attributes['percentageStyle'])) ? $attributes['percentageStyle'] : 'style-1';
	$circleSize = (!empty($attributes['circleSize'])) ? $attributes['circleSize'] : '50';
	$applyTo = (!empty($attributes['applyTo'])) ? $attributes['applyTo'] : 'entire';
	$unqSelector = (!empty($attributes['unqSelector'])) ? $attributes['unqSelector'] : '';
	$pinPoint = (!empty($attributes['pinPoint'])) ? $attributes['pinPoint'] : false;
	$pinPStyle = (!empty($attributes['pinPStyle'])) ? $attributes['pinPStyle'] : 'style-1';
	$pinPointRep = (!empty($attributes['pinPointRep'])) ? $attributes['pinPointRep'] : [];

	$blockClass = Tp_Blocks_Helper::block_wrapper_classes( $attributes );

	$relTselector = (!empty($attributes['relTselector']) && !empty($unqSelector) && $applyTo=='selector') ? 'tracker-rel-sel' : '';

	$positionClass = $posClass = '';
	$positionClass = 'tpgb-fixed-block';
	if($progressType=='horizontal'){
		$posClass = 'pos-'.$horizontalPos.' direction-'.$hzDirection;
	}else if($progressType=='vertical'){
		$posClass = 'pos-'.$verticalPos;
	}else{
		$posClass = 'pos-'.$circularPos;
	}

	$pinPointEnable = '';
	if(!empty($pinPoint)){
		$pinPointEnable = 'container-pinpoint-yes';
	}

	$data_attr=[];
	$data_attr['apply_to'] = $applyTo;
	if($applyTo=='selector' && !empty($unqSelector)){
		$data_attr['selector'] = $unqSelector;
	}
	$data_attr = 'data-attr="'.htmlspecialchars(json_encode($data_attr, true), ENT_QUOTES, 'UTF-8').'"';
		
	$output = '';
	$getPinItem = '';
    $output .= '<div class="tpgb-progress-tracker tpgb-relative-block type-'.esc_attr($progressType).' '.esc_attr($relTselector).' tpgb-block-'.esc_attr($block_id).' '.esc_attr($blockClass).' '.esc_attr($pinPointEnable).'" '.$data_attr.'>';
		$output .= '<div class="tpgb-progress-track '.esc_attr($positionClass).' '.esc_attr($posClass).'">';
			if($progressType!='circular'){
				$output .= '<div class="progress-track-fill">';
					if(!empty($percentageText)){
						$output .= '<div class="progress-track-percentage '.esc_attr($percentageStyle).'"></div>';
					}
				$output .= '</div>';

				if(!empty($pinPoint) && $applyTo=='entire' && $progressType!='circular'){
					if(!empty($pinPointRep)){
						$getPinItem .= '<div class="tracker-pin-point-wrap pin-'.esc_attr($pinPStyle).'">';
						foreach ( $pinPointRep as $index => $item ) :
							if(!empty($item['conID']) && !empty($item['Title'])){
								$getPinItem .= '<div class="tracker-pin" data-id="'.esc_attr($item['conID']).'">';
									$getPinItem .= '<span class="tracker-pin-text">'.wp_kses_post($item['Title']).'</span>';
								$getPinItem .= '</div>';
							}
						endforeach;
						$getPinItem .= '</div>';
					}
				}
				$output .= $getPinItem;
			}else{
				$output .='<svg class="tpgb-pt-svg-circle" width="200" height="200" viewport="0 0 100 100" xmlns="https://www.w3.org/2000/svg">
				<circle class="tpgb-pt-circle-st" cx="100" cy="100" r="'.esc_attr($circleSize).'"></circle>
				<circle class="tpgb-pt-circle-st1" cx="100" cy="100" r="'.esc_attr($circleSize).'"></circle>
				<circle class="tpgb-pt-circle-st2" cx="100" cy="100" r="'.esc_attr($circleSize).'"></circle></svg>';
				if(!empty($percentageText)){
					$output .= '<div class="progress-track-percentage"></div>';
				}
			}
		$output .= '</div>';
	$output .= '</div>';

	$output = Tpgb_Blocks_Global_Options::block_Wrap_Render($attributes, $output);
	
    return $output;
}

/**
 * Render for the server-side
 */
function tpgb_progress_tracker() {
	/* $globalBgOption = Tpgb_Blocks_Global_Options::load_bg_options();
	$globalpositioningOption = Tpgb_Blocks_Global_Options::load_positioning_options();
	$globalPlusExtrasOption = Tpgb_Blocks_Global_Options::load_plusextras_options();
	
	$attributesOptions = array(
		'block_id' => [
			'type' => 'string',
			'default' => '',
		],
		'progressType' => [
			'type' => 'string',
			'default' => 'horizontal',	
		],
		'horizontalPos' => [
			'type' => 'string',
			'default' => 'top',	
		],
		'hzDirection' => [
			'type' => 'string',
			'default' => 'ltr',	
		],
		'verticalPos' => [
			'type' => 'string',
			'default' => 'left',	
		],
		'circularPos' => [
			'type' => 'string',
			'default' => 'top-left',	
		],
		'cPosTopOff' => [
			'type' => 'object',
			'default' => (object) [ 
				'md' => '0',
				"unit" => 'px',
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'progressType', 'relation' => '==', 'value' => 'circular' ], ['key' => 'circularPos', 'relation' => '==', 'value' => 'top-left' ] ],
					'selector' => '{{PLUS_WRAP}}.type-circular .pos-top-left{ top : {{cPosTopOff}}; }',
				],
				(object) [
					'condition' => [(object) ['key' => 'progressType', 'relation' => '==', 'value' => 'circular' ], ['key' => 'circularPos', 'relation' => '==', 'value' => 'top-right' ] ],
					'selector' => '{{PLUS_WRAP}}.type-circular .pos-top-right{ top : {{cPosTopOff}}; }',
				],
			],
			'scopy' => true,
		],
		'cPosBottomOff' => [
			'type' => 'object',
			'default' => (object) [ 
				'md' => '0',
				"unit" => 'px',
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'progressType', 'relation' => '==', 'value' => 'circular' ], ['key' => 'circularPos', 'relation' => '==', 'value' => 'bottom-left' ] ],
					'selector' => '{{PLUS_WRAP}}.type-circular .pos-bottom-left{ bottom : {{cPosBottomOff}}; }',
				],
				(object) [
					'condition' => [(object) ['key' => 'progressType', 'relation' => '==', 'value' => 'circular' ], ['key' => 'circularPos', 'relation' => '==', 'value' => 'bottom-right' ] ],
					'selector' => '{{PLUS_WRAP}}.type-circular .pos-bottom-right{ bottom : {{cPosBottomOff}}; }',
				],
			],
			'scopy' => true,
		],
		'cPosLeftOff' => [
			'type' => 'object',
			'default' => (object) [ 
				'md' => '0',
				"unit" => 'px',
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'progressType', 'relation' => '==', 'value' => 'circular' ], ['key' => 'circularPos', 'relation' => '==', 'value' => 'top-left' ] ],
					'selector' => '{{PLUS_WRAP}}.type-circular .pos-top-left{ left : {{cPosLeftOff}}; }',
				],
				(object) [
					'condition' => [(object) ['key' => 'progressType', 'relation' => '==', 'value' => 'circular' ], ['key' => 'circularPos', 'relation' => '==', 'value' => 'center-left' ] ],
					'selector' => '{{PLUS_WRAP}}.type-circular .pos-center-left{ left : {{cPosLeftOff}}; }',
				],
				(object) [
					'condition' => [(object) ['key' => 'progressType', 'relation' => '==', 'value' => 'circular' ], ['key' => 'circularPos', 'relation' => '==', 'value' => 'bottom-left' ] ],
					'selector' => '{{PLUS_WRAP}}.type-circular .pos-bottom-left{ left : {{cPosLeftOff}}; }',
				],
			],
			'scopy' => true,
		],
		'cPosRightOff' => [
			'type' => 'object',
			'default' => (object) [ 
				'md' => '0',
				"unit" => 'px',
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'progressType', 'relation' => '==', 'value' => 'circular' ], ['key' => 'circularPos', 'relation' => '==', 'value' => 'top-right' ] ],
					'selector' => '{{PLUS_WRAP}}.type-circular .pos-top-right{ right : {{cPosRightOff}}; }',
				],
				(object) [
					'condition' => [(object) ['key' => 'progressType', 'relation' => '==', 'value' => 'circular' ], ['key' => 'circularPos', 'relation' => '==', 'value' => 'center-right' ] ],
					'selector' => '{{PLUS_WRAP}}.type-circular .pos-center-right{ right : {{cPosRightOff}}; }',
				],
				(object) [
					'condition' => [(object) ['key' => 'progressType', 'relation' => '==', 'value' => 'circular' ], ['key' => 'circularPos', 'relation' => '==', 'value' => 'bottom-right' ] ],
					'selector' => '{{PLUS_WRAP}}.type-circular .pos-bottom-right{ right : {{cPosRightOff}}; }',
				],
			],
			'scopy' => true,
		],
		'pinPoint' => [
			'type' => 'boolean',
			'default' => false,	
		],
		'pinPStyle' => [
			'type' => 'string',
			'default' => 'style-1',	
		],
		'pinPointRep' => [
			'type'=> 'array',
			'repeaterField' => [
				(object) [
					'Title' => [
						'type' => 'string',
						'default' => 'Pin 1'
					],
					'conID' => [
						'type' => 'string',
						'default' => ''
					],
				],
			],
			'default' => [
				[
					'_key' => '0',
					'Title' => 'Pin 1',
					'conID' => '',
				]
			]
		],
		'applyTo' => [
			'type' => 'string',
			'default' => 'entire',	
		],
		'unqSelector' => [
			'type' => 'string',
			'default' => '',	
		],
		'relTselector' => [
			'type' => 'boolean',
			'default' => false,	
		],
		'percentageText' => [
			'type' => 'boolean',
			'default' => false,	
		],
		'percentageStyle' => [
			'type' => 'string',
			'default' => 'style-1',	
		],
		'circleSize' => [
			'type' => 'string',
			'default' => '',
			'scopy' => true,
		],
		'circleBGColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'progressType', 'relation' => '==', 'value' => 'circular' ] ],
					'selector' => '{{PLUS_WRAP}} circle.tpgb-pt-circle-st { fill: {{circleBGColor}}; }',
				],
			],
			'scopy' => true,
		],
		'trackSize' => [
			'type' => 'object',
			'default' => (object) [ 
				'md' => '',
				"unit" => 'px',
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'progressType', 'relation' => '==', 'value' => 'horizontal' ] ],
					'selector' => '{{PLUS_WRAP}}.type-horizontal .tpgb-progress-track{ height : {{trackSize}}; }',
				],
				(object) [
					'condition' => [(object) ['key' => 'progressType', 'relation' => '==', 'value' => 'vertical' ] ],
					'selector' => '{{PLUS_WRAP}}.type-vertical .tpgb-progress-track{ width : {{trackSize}}; }',
				],
				(object) [
					'condition' => [(object) ['key' => 'progressType', 'relation' => '==', 'value' => 'circular' ] ],
					'selector' => '{{PLUS_WRAP}} circle.tpgb-pt-circle-st1, {{PLUS_WRAP}} circle.tpgb-pt-circle-st2{ stroke-width : {{trackSize}}; }',
				],
			],
			'scopy' => true,
		],
		'trackBG' => [
			'type' => 'object',
			'default' => (object) [
				'openBg'=> 0,
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'progressType', 'relation' => '==', 'value' => 'horizontal' ] ],
					'selector' => '{{PLUS_WRAP}}.type-horizontal .tpgb-progress-track',
				],
				(object) [
					'condition' => [(object) ['key' => 'progressType', 'relation' => '==', 'value' => 'vertical' ] ],
					'selector' => '{{PLUS_WRAP}}.type-vertical .tpgb-progress-track',
				],
			],
			'scopy' => true,
		],
		'trackBdr' => [
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
					'condition' => [(object) ['key' => 'progressType', 'relation' => '==', 'value' => 'horizontal' ] ],
					'selector' => '{{PLUS_WRAP}}.type-horizontal .tpgb-progress-track',
				],
				(object) [
					'condition' => [(object) ['key' => 'progressType', 'relation' => '==', 'value' => 'vertical' ] ],
					'selector' => '{{PLUS_WRAP}}.type-vertical .tpgb-progress-track',
				],
			],
			'scopy' => true,
		],
		'trackBRadius' => [
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
					'condition' => [(object) ['key' => 'progressType', 'relation' => '==', 'value' => 'horizontal' ] ],
					'selector' => '{{PLUS_WRAP}}.type-horizontal .tpgb-progress-track, {{PLUS_WRAP}}.type-horizontal .progress-track-fill{border-radius: {{trackBRadius}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'progressType', 'relation' => '==', 'value' => 'vertical' ] ],
					'selector' => '{{PLUS_WRAP}}.type-vertical .tpgb-progress-track, {{PLUS_WRAP}}.type-vertical .progress-track-fill{border-radius: {{trackBRadius}};}',
				],
			],
			'scopy' => true,
		],
		'trackBShadow' => [
			'type' => 'object',
			'default' => (object) [
				'horizontal' => 0,
				'vertical' => 8,
				'blur' => 20,
				'spread' => 1,
				'color' => "rgba(0,0,0,0.27)",
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'progressType', 'relation' => '==', 'value' => 'horizontal' ] ],
					'selector' => '{{PLUS_WRAP}}.type-horizontal .tpgb-progress-track',
				],
				(object) [
					'condition' => [(object) ['key' => 'progressType', 'relation' => '==', 'value' => 'vertical' ] ],
					'selector' => '{{PLUS_WRAP}}.type-vertical .tpgb-progress-track',
				],
			],
			'scopy' => true,
		],
		'fillBG' => [
			'type' => 'object',
			'default' => (object) [
				'openBg'=> 0,
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'progressType', 'relation' => '==', 'value' => 'horizontal' ] ],
					'selector' => '{{PLUS_WRAP}}.type-horizontal .progress-track-fill',
				],
				(object) [
					'condition' => [(object) ['key' => 'progressType', 'relation' => '==', 'value' => 'vertical' ] ],
					'selector' => '{{PLUS_WRAP}}.type-vertical .progress-track-fill',
				],
			],
			'scopy' => true,
		],
		'cTrackColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'progressType', 'relation' => '==', 'value' => 'circular' ] ],
					'selector' => '{{PLUS_WRAP}} circle.tpgb-pt-circle-st1 { stroke: {{cTrackColor}}; }',
				],
			],
			'scopy' => true,
		],
		'cTrackDShadow' => [
			'type' => 'object',
			'default' => (object) [
				'openShadow' => 0,
				'typeShadow' => 'drop-shadow', 
				'horizontal' => 2,
				'vertical' => 3,
				'blur' => 2,
				'color' => "rgba(0,0,0,0.5)",
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'progressType', 'relation' => '==', 'value' => 'circular' ] ],
					'selector' => '{{PLUS_WRAP}} .tpgb-pt-svg-circle',
				],
			],
			'scopy' => true,
		],
		'cFillColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'progressType', 'relation' => '==', 'value' => 'circular' ] ],
					'selector' => '{{PLUS_WRAP}} circle.tpgb-pt-circle-st2 { stroke: {{cFillColor}}; }',
				],
			],
			'scopy' => true,
		],

		'texTypo' => [
			'type'=> 'object',
			'default'=> (object) [
				'openTypography' => 0,
				'size' => [ 'md' => '', 'unit' => 'px' ],
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'percentageText', 'relation' => '==', 'value' => true ] ],
					'selector' => '{{PLUS_WRAP}} .progress-track-percentage',
				],
			],
			'scopy' => true,
		],
		'ttPadding' => [
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
					'condition' => [(object) ['key' => 'progressType', 'relation' => '!=', 'value' => 'circular' ],['key' => 'percentageStyle', 'relation' => '==', 'value' => 'style-2' ],['key' => 'percentageText', 'relation' => '==', 'value' => true ]],
					'selector' => '{{PLUS_WRAP}}.type-horizontal .progress-track-percentage.style-2, {{PLUS_WRAP}}.type-vertical .progress-track-percentage.style-2{padding: {{ttPadding}};}',
				],
			],
			'scopy' => true,
		],
		'textColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'percentageText', 'relation' => '==', 'value' => true ] ],
					'selector' => '{{PLUS_WRAP}} .progress-track-percentage { color: {{textColor}}; }',
				],
			],
			'scopy' => true,
		],
		'ttBGColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'progressType', 'relation' => '!=', 'value' => 'circular' ],['key' => 'percentageStyle', 'relation' => '==', 'value' => 'style-2' ],['key' => 'percentageText', 'relation' => '==', 'value' => true ]],
					'selector' => '{{PLUS_WRAP}}.type-horizontal .progress-track-percentage.style-2, {{PLUS_WRAP}}.type-vertical .progress-track-percentage.style-2 { background-color: {{ttBGColor}}; }',
				],
				(object) [
					'condition' => [(object) ['key' => 'progressType', 'relation' => '==', 'value' => 'horizontal' ],['key' => 'percentageStyle', 'relation' => '==', 'value' => 'style-2' ],['key' => 'percentageText', 'relation' => '==', 'value' => true ],['key' => 'horizontalPos', 'relation' => '==', 'value' => 'top' ]],
					'selector' => '{{PLUS_WRAP}}.type-horizontal .progress-track-percentage.style-2::before { border-color: transparent transparent {{ttBGColor}} transparent; }',
				],
				(object) [
					'condition' => [(object) ['key' => 'progressType', 'relation' => '==', 'value' => 'horizontal' ],['key' => 'percentageStyle', 'relation' => '==', 'value' => 'style-2' ],['key' => 'percentageText', 'relation' => '==', 'value' => true ],['key' => 'horizontalPos', 'relation' => '==', 'value' => 'bottom' ]],
					'selector' => '{{PLUS_WRAP}}.type-horizontal .progress-track-percentage.style-2::before { border-color: {{ttBGColor}} transparent transparent transparent; }',
				],
				(object) [
					'condition' => [(object) ['key' => 'progressType', 'relation' => '==', 'value' => 'vertical' ],['key' => 'percentageStyle', 'relation' => '==', 'value' => 'style-2' ],['key' => 'percentageText', 'relation' => '==', 'value' => true ],['key' => 'verticalPos', 'relation' => '==', 'value' => 'left' ]],
					'selector' => '{{PLUS_WRAP}}.type-vertical .progress-track-percentage.style-2::before { border-color: transparent {{ttBGColor}} transparent transparent; }',
				],
				(object) [
					'condition' => [(object) ['key' => 'progressType', 'relation' => '==', 'value' => 'vertical' ],['key' => 'percentageStyle', 'relation' => '==', 'value' => 'style-2' ],['key' => 'percentageText', 'relation' => '==', 'value' => true ],['key' => 'verticalPos', 'relation' => '==', 'value' => 'right' ]],
					'selector' => '{{PLUS_WRAP}}.type-vertical .progress-track-percentage.style-2::before { border-color: transparent transparent transparent {{ttBGColor}} }',
				],
			],
			'scopy' => true,
		],

		'pinTypo' => [
			'type'=> 'object',
			'default'=> (object) [
				'openTypography' => 0,
				'size' => [ 'md' => '', 'unit' => 'px' ],
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'applyTo', 'relation' => '==', 'value' => 'entire' ], ['key' => 'progressType', 'relation' => '!=', 'value' => 'circular' ], ['key' => 'pinPoint', 'relation' => '==', 'value' => true ] ],
					'selector' => '{{PLUS_WRAP}} .tracker-pin-text',
				],
			],
			'scopy' => true,
		],
		'pinPadding' => [
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
					'condition' => [(object) ['key' => 'applyTo', 'relation' => '==', 'value' => 'entire' ], ['key' => 'progressType', 'relation' => '!=', 'value' => 'circular' ], ['key' => 'pinPoint', 'relation' => '==', 'value' => true ]],
					'selector' => '{{PLUS_WRAP}} .tracker-pin-text {padding: {{pinPadding}};}',
				],
			],
			'scopy' => true,
		],
		'pinOffset' => [
			'type' => 'object',
			'default' => (object) [ 
				'md' => '',
				"unit" => 'px',
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'applyTo', 'relation' => '==', 'value' => 'entire' ], ['key' => 'progressType', 'relation' => '==', 'value' => 'horizontal' ], ['key' => 'pinPoint', 'relation' => '==', 'value' => true ], ['key' => 'horizontalPos', 'relation' => '==', 'value' => 'top' ]],
					'selector' => '{{PLUS_WRAP}}.type-horizontal .pos-top .pin-style-1 .tracker-pin, {{PLUS_WRAP}}.type-horizontal .pos-top .pin-style-2 .tracker-pin-text{ top: calc(100% + {{pinOffset}}); }',
				],
				(object) [
					'condition' => [(object) ['key' => 'applyTo', 'relation' => '==', 'value' => 'entire' ], ['key' => 'progressType', 'relation' => '==', 'value' => 'horizontal' ], ['key' => 'pinPoint', 'relation' => '==', 'value' => true ], ['key' => 'horizontalPos', 'relation' => '==', 'value' => 'bottom' ]],
					'selector' => '{{PLUS_WRAP}}.type-horizontal .pos-bottom .pin-style-1 .tracker-pin, {{PLUS_WRAP}}.type-horizontal .pos-bottom .pin-style-2 .tracker-pin-text{ bottom: calc(100% + {{pinOffset}}); }',
				],
				(object) [
					'condition' => [(object) ['key' => 'applyTo', 'relation' => '==', 'value' => 'entire' ], ['key' => 'progressType', 'relation' => '==', 'value' => 'vertical' ], ['key' => 'pinPoint', 'relation' => '==', 'value' => true ], ['key' => 'verticalPos', 'relation' => '==', 'value' => 'left' ]],
					'selector' => '{{PLUS_WRAP}}.type-vertical .pos-left .pin-style-1 .tracker-pin, {{PLUS_WRAP}}.type-vertical .pos-left .pin-style-2 .tracker-pin-text{ left: calc(100% + {{pinOffset}}); }',
				],
				(object) [
					'condition' => [(object) ['key' => 'applyTo', 'relation' => '==', 'value' => 'entire' ], ['key' => 'progressType', 'relation' => '==', 'value' => 'vertical' ], ['key' => 'pinPoint', 'relation' => '==', 'value' => true ], ['key' => 'verticalPos', 'relation' => '==', 'value' => 'right' ]],
					'selector' => '{{PLUS_WRAP}}.type-vertical .pos-right .pin-style-1 .tracker-pin, {{PLUS_WRAP}}.type-vertical .pos-right .pin-style-2 .tracker-pin-text{ right: calc(100% + {{pinOffset}}); }',
				],
			],
			'scopy' => true,
		],
		
		'pinColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'applyTo', 'relation' => '==', 'value' => 'entire' ], ['key' => 'progressType', 'relation' => '!=', 'value' => 'circular' ], ['key' => 'pinPoint', 'relation' => '==', 'value' => true ]],
					'selector' => '{{PLUS_WRAP}} .tracker-pin-text { color: {{pinColor}}; }',
				],
			],
			'scopy' => true,
		],
		'pinHColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'applyTo', 'relation' => '==', 'value' => 'entire' ], ['key' => 'progressType', 'relation' => '!=', 'value' => 'circular' ], ['key' => 'pinPoint', 'relation' => '==', 'value' => true ]],
					'selector' => '{{PLUS_WRAP}} .tracker-pin-text:hover { color: {{pinHColor}}; }',
				],
			],
			'scopy' => true,
		],
		'pinAColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'applyTo', 'relation' => '==', 'value' => 'entire' ], ['key' => 'progressType', 'relation' => '!=', 'value' => 'circular' ], ['key' => 'pinPoint', 'relation' => '==', 'value' => true ]],
					'selector' => '{{PLUS_WRAP}} .tracker-pin.active .tracker-pin-text { color: {{pinAColor}}; }',
				],
			],
			'scopy' => true,
		],
		'pinBG' => [
			'type' => 'object',
			'default' => (object) [
				'openBg'=> 0,
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'applyTo', 'relation' => '==', 'value' => 'entire' ], ['key' => 'progressType', 'relation' => '!=', 'value' => 'circular' ], ['key' => 'pinPoint', 'relation' => '==', 'value' => true ]],
					'selector' => '{{PLUS_WRAP}} .tracker-pin-text',
				],
			],
			'scopy' => true,
		],
		'pinHBG' => [
			'type' => 'object',
			'default' => (object) [
				'openBg'=> 0,
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'applyTo', 'relation' => '==', 'value' => 'entire' ], ['key' => 'progressType', 'relation' => '!=', 'value' => 'circular' ], ['key' => 'pinPoint', 'relation' => '==', 'value' => true ]],
					'selector' => '{{PLUS_WRAP}} .tracker-pin-text:hover',
				],
			],
			'scopy' => true,
		],
		'pinABG' => [
			'type' => 'object',
			'default' => (object) [
				'openBg'=> 0,
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'applyTo', 'relation' => '==', 'value' => 'entire' ], ['key' => 'progressType', 'relation' => '!=', 'value' => 'circular' ], ['key' => 'pinPoint', 'relation' => '==', 'value' => true ]],
					'selector' => '{{PLUS_WRAP}} .tracker-pin.active .tracker-pin-text',
				],
			],
			'scopy' => true,
		],
		'pinBdr' => [
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
					'condition' => [(object) ['key' => 'applyTo', 'relation' => '==', 'value' => 'entire' ], ['key' => 'progressType', 'relation' => '!=', 'value' => 'circular' ], ['key' => 'pinPoint', 'relation' => '==', 'value' => true ]],
					'selector' => '{{PLUS_WRAP}} .tracker-pin-text',
				],
			],
			'scopy' => true,
		],
		'pinHBdr' => [
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
					'condition' => [(object) ['key' => 'applyTo', 'relation' => '==', 'value' => 'entire' ], ['key' => 'progressType', 'relation' => '!=', 'value' => 'circular' ], ['key' => 'pinPoint', 'relation' => '==', 'value' => true ]],
					'selector' => '{{PLUS_WRAP}} .tracker-pin-text:hover',
				],
			],
			'scopy' => true,
		],
		'pinABdr' => [
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
					'condition' => [(object) ['key' => 'applyTo', 'relation' => '==', 'value' => 'entire' ], ['key' => 'progressType', 'relation' => '!=', 'value' => 'circular' ], ['key' => 'pinPoint', 'relation' => '==', 'value' => true ]],
					'selector' => '{{PLUS_WRAP}} .tracker-pin.active .tracker-pin-text',
				],
			],
			'scopy' => true,
		],
		'pinRadius' => [
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
					'condition' => [(object) ['key' => 'applyTo', 'relation' => '==', 'value' => 'entire' ], ['key' => 'progressType', 'relation' => '!=', 'value' => 'circular' ], ['key' => 'pinPoint', 'relation' => '==', 'value' => true ]],
					'selector' => '{{PLUS_WRAP}} .tracker-pin-text {border-radius: {{pinRadius}};}',
				],
			],
			'scopy' => true,
		],
		'pinHRadius' => [
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
					'condition' => [(object) ['key' => 'applyTo', 'relation' => '==', 'value' => 'entire' ], ['key' => 'progressType', 'relation' => '!=', 'value' => 'circular' ], ['key' => 'pinPoint', 'relation' => '==', 'value' => true ]],
					'selector' => '{{PLUS_WRAP}} .tracker-pin-text:hover {border-radius: {{pinHRadius}};}',
				],
			],
			'scopy' => true,
		],
		'pinARadius' => [
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
					'condition' => [(object) ['key' => 'applyTo', 'relation' => '==', 'value' => 'entire' ], ['key' => 'progressType', 'relation' => '!=', 'value' => 'circular' ], ['key' => 'pinPoint', 'relation' => '==', 'value' => true ]],
					'selector' => '{{PLUS_WRAP}} .tracker-pin.active .tracker-pin-text {border-radius: {{pinARadius}};}',
				],
			],
			'scopy' => true,
		],
		'pinBShadow' => [
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
					'condition' => [(object) ['key' => 'applyTo', 'relation' => '==', 'value' => 'entire' ], ['key' => 'progressType', 'relation' => '!=', 'value' => 'circular' ], ['key' => 'pinPoint', 'relation' => '==', 'value' => true ]],
					'selector' => '{{PLUS_WRAP}} .tracker-pin-text',
				],
			],
			'scopy' => true,
		],
		'pinHBShadow' => [
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
					'condition' => [(object) ['key' => 'applyTo', 'relation' => '==', 'value' => 'entire' ], ['key' => 'progressType', 'relation' => '!=', 'value' => 'circular' ], ['key' => 'pinPoint', 'relation' => '==', 'value' => true ]],
					'selector' => '{{PLUS_WRAP}} .tracker-pin-text:hover',
				],
			],
			'scopy' => true,
		],
		'pinABShadow' => [
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
					'condition' => [(object) ['key' => 'applyTo', 'relation' => '==', 'value' => 'entire' ], ['key' => 'progressType', 'relation' => '!=', 'value' => 'circular' ], ['key' => 'pinPoint', 'relation' => '==', 'value' => true ]],
					'selector' => '{{PLUS_WRAP}} .tracker-pin.active .tracker-pin-text',
				],
			],
			'scopy' => true,
		],

		'pinDotSize' => [
			'type' => 'object',
			'default' => (object) [ 
				'md' => '',
				"unit" => 'px',
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'applyTo', 'relation' => '==', 'value' => 'entire' ], ['key' => 'progressType', 'relation' => '!=', 'value' => 'circular' ], ['key' => 'pinPoint', 'relation' => '==', 'value' => true ], ['key' => 'pinPStyle', 'relation' => '==', 'value' => 'style-2' ]],
					'selector' => '{{PLUS_WRAP}} .tracker-pin{ width : {{pinDotSize}}; height : {{pinDotSize}}; }',
				],
			],
			'scopy' => true,
		],
		'pDotBG' => [
			'type' => 'object',
			'default' => (object) [
				'openBg'=> 0,
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'applyTo', 'relation' => '==', 'value' => 'entire' ], ['key' => 'progressType', 'relation' => '!=', 'value' => 'circular' ], ['key' => 'pinPoint', 'relation' => '==', 'value' => true ], ['key' => 'pinPStyle', 'relation' => '==', 'value' => 'style-2' ]],
					'selector' => '{{PLUS_WRAP}} .tracker-pin',
				],
			],
			'scopy' => true,
		],
		'pDotHBG' => [
			'type' => 'object',
			'default' => (object) [
				'openBg'=> 0,
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'applyTo', 'relation' => '==', 'value' => 'entire' ], ['key' => 'progressType', 'relation' => '!=', 'value' => 'circular' ], ['key' => 'pinPoint', 'relation' => '==', 'value' => true ], ['key' => 'pinPStyle', 'relation' => '==', 'value' => 'style-2' ]],
					'selector' => '{{PLUS_WRAP}} .tracker-pin:hover',
				],
			],
			'scopy' => true,
		],
		'pDotABG' => [
			'type' => 'object',
			'default' => (object) [
				'openBg'=> 0,
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'applyTo', 'relation' => '==', 'value' => 'entire' ], ['key' => 'progressType', 'relation' => '!=', 'value' => 'circular' ], ['key' => 'pinPoint', 'relation' => '==', 'value' => true ], ['key' => 'pinPStyle', 'relation' => '==', 'value' => 'style-2' ]],
					'selector' => '{{PLUS_WRAP}} .tracker-pin.active',
				],
			],
			'scopy' => true,
		],
		'pDotBdr' => [
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
					'condition' => [(object) ['key' => 'applyTo', 'relation' => '==', 'value' => 'entire' ], ['key' => 'progressType', 'relation' => '!=', 'value' => 'circular' ], ['key' => 'pinPoint', 'relation' => '==', 'value' => true ], ['key' => 'pinPStyle', 'relation' => '==', 'value' => 'style-2' ]],
					'selector' => '{{PLUS_WRAP}} .tracker-pin',
				],
			],
			'scopy' => true,
		],
		'pDotHBdr' => [
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
					'condition' => [(object) ['key' => 'applyTo', 'relation' => '==', 'value' => 'entire' ], ['key' => 'progressType', 'relation' => '!=', 'value' => 'circular' ], ['key' => 'pinPoint', 'relation' => '==', 'value' => true ], ['key' => 'pinPStyle', 'relation' => '==', 'value' => 'style-2' ]],
					'selector' => '{{PLUS_WRAP}} .tracker-pin:hover',
				],
			],
			'scopy' => true,
		],
		'pDotABdr' => [
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
					'condition' => [(object) ['key' => 'applyTo', 'relation' => '==', 'value' => 'entire' ], ['key' => 'progressType', 'relation' => '!=', 'value' => 'circular' ], ['key' => 'pinPoint', 'relation' => '==', 'value' => true ], ['key' => 'pinPStyle', 'relation' => '==', 'value' => 'style-2' ]],
					'selector' => '{{PLUS_WRAP}} .tracker-pin.active',
				],
			],
			'scopy' => true,
		],
		'pDotRadius' => [
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
					'condition' => [(object) ['key' => 'applyTo', 'relation' => '==', 'value' => 'entire' ], ['key' => 'progressType', 'relation' => '!=', 'value' => 'circular' ], ['key' => 'pinPoint', 'relation' => '==', 'value' => true ], ['key' => 'pinPStyle', 'relation' => '==', 'value' => 'style-2' ]],
					'selector' => '{{PLUS_WRAP}} .tracker-pin {border-radius: {{pDotRadius}};}',
				],
			],
			'scopy' => true,
		],
		'pDotHRadius' => [
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
					'condition' => [(object) ['key' => 'applyTo', 'relation' => '==', 'value' => 'entire' ], ['key' => 'progressType', 'relation' => '!=', 'value' => 'circular' ], ['key' => 'pinPoint', 'relation' => '==', 'value' => true ], ['key' => 'pinPStyle', 'relation' => '==', 'value' => 'style-2' ]],
					'selector' => '{{PLUS_WRAP}} .tracker-pin:hover {border-radius: {{pDotHRadius}};}',
				],
			],
			'scopy' => true,
		],
		'pDotARadius' => [
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
					'condition' => [(object) ['key' => 'applyTo', 'relation' => '==', 'value' => 'entire' ], ['key' => 'progressType', 'relation' => '!=', 'value' => 'circular' ], ['key' => 'pinPoint', 'relation' => '==', 'value' => true ], ['key' => 'pinPStyle', 'relation' => '==', 'value' => 'style-2' ]],
					'selector' => '{{PLUS_WRAP}} .tracker-pin.active {border-radius: {{pDotARadius}};}',
				],
			],
			'scopy' => true,
		],
		'pDotBShadow' => [
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
					'condition' => [(object) ['key' => 'applyTo', 'relation' => '==', 'value' => 'entire' ], ['key' => 'progressType', 'relation' => '!=', 'value' => 'circular' ], ['key' => 'pinPoint', 'relation' => '==', 'value' => true ], ['key' => 'pinPStyle', 'relation' => '==', 'value' => 'style-2' ]],
					'selector' => '{{PLUS_WRAP}} .tracker-pin',
				],
			],
			'scopy' => true,
		],
		'pDotHBShadow' => [
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
					'condition' => [(object) ['key' => 'applyTo', 'relation' => '==', 'value' => 'entire' ], ['key' => 'progressType', 'relation' => '!=', 'value' => 'circular' ], ['key' => 'pinPoint', 'relation' => '==', 'value' => true ], ['key' => 'pinPStyle', 'relation' => '==', 'value' => 'style-2' ]],
					'selector' => '{{PLUS_WRAP}} .tracker-pin:hover',
				],
			],
			'scopy' => true,
		],
		'pDotABShadow' => [
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
					'condition' => [(object) ['key' => 'applyTo', 'relation' => '==', 'value' => 'entire' ], ['key' => 'progressType', 'relation' => '!=', 'value' => 'circular' ], ['key' => 'pinPoint', 'relation' => '==', 'value' => true ], ['key' => 'pinPStyle', 'relation' => '==', 'value' => 'style-2' ]],
					'selector' => '{{PLUS_WRAP}} .tracker-pin.active',
				],
			],
			'scopy' => true,
		],

		'trackOffset' => [
			'type' => 'object',
			'default' => (object) [ 
				'md' => '',
				"unit" => 'px',
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'progressType', 'relation' => '==', 'value' => 'horizontal' ], ['key' => 'horizontalPos', 'relation' => '==', 'value' => 'top' ]],
					'selector' => 'body.admin-bar {{PLUS_WRAP}}.type-horizontal .tpgb-progress-track.pos-top { top : calc(32px + {{trackOffset}}); } {{PLUS_WRAP}}.type-horizontal .tpgb-progress-track.pos-top { top : {{trackOffset}}; }',
				],
				(object) [
					'condition' => [(object) ['key' => 'progressType', 'relation' => '==', 'value' => 'horizontal' ], ['key' => 'horizontalPos', 'relation' => '==', 'value' => 'bottom' ]],
					'selector' => 'body.admin-bar {{PLUS_WRAP}}.type-horizontal .tpgb-progress-track.pos-bottom { bottom : calc(32px + {{trackOffset}}); } {{PLUS_WRAP}}.type-horizontal .tpgb-progress-track.pos-bottom { bottom : {{trackOffset}}; }',
				],
				(object) [
					'condition' => [(object) ['key' => 'progressType', 'relation' => '==', 'value' => 'vertical' ], ['key' => 'verticalPos', 'relation' => '==', 'value' => 'left' ]],
					'selector' => '{{PLUS_WRAP}}.type-vertical .tpgb-progress-track.pos-left { left : {{trackOffset}}; }',
				],
				(object) [
					'condition' => [(object) ['key' => 'progressType', 'relation' => '==', 'value' => 'vertical' ], ['key' => 'verticalPos', 'relation' => '==', 'value' => 'right' ]],
					'selector' => '{{PLUS_WRAP}}.type-vertical .tpgb-progress-track.pos-right { right : {{trackOffset}}; }',
				],
			],
			'scopy' => true,
		],
	);
	$attributesOptions = array_merge($attributesOptions,$globalBgOption,$globalpositioningOption, $globalPlusExtrasOption);
	
	register_block_type( 'tpgb/tp-progress-tracker', array(
		'attributes' => $attributesOptions,
		'editor_script' => 'tpgb-block-editor-js',
		'editor_style'  => 'tpgb-block-editor-css',
        'render_callback' => 'tpgb_progress_tracker_render_callback'
    ) ); */
	$block_data = Tpgb_Blocks_Global_Options::merge_options_json(__DIR__, 'tpgb_progress_tracker_render_callback');
	register_block_type( $block_data['name'], $block_data );
}
add_action( 'init', 'tpgb_progress_tracker' );