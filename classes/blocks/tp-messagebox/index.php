<?php
/* Block : Message Box
 * @since : 1.0.0
 */
defined( 'ABSPATH' ) || exit;

function tpgb_tp_messagebox_render_callback( $attributes, $content) {
	$output = '';
    $block_id = (!empty($attributes['block_id'])) ? $attributes['block_id'] : uniqid("title");
	$icon = (!empty($attributes['icon'])) ? $attributes['icon'] : false;
	$icnPosition = (!empty($attributes['icnPosition'])) ? $attributes['icnPosition'] : 'prefix';
	$msgArrow = (!empty($attributes['msgArrow'])) ? $attributes['msgArrow'] : false;
	$IconName = (!empty($attributes['IconName'])) ? $attributes['IconName'] : '';
	$dismiss = (!empty($attributes['dismiss'])) ? $attributes['dismiss'] : false;
	$Description = (!empty($attributes['Description'])) ? $attributes['Description'] : false;
	$dismsIcon = (!empty($attributes['dismsIcon'])) ? $attributes['dismsIcon'] : '';
	$Title = (!empty($attributes['Title'])) ? $attributes['Title'] : '';
	$descText = (!empty($attributes['descText'])) ? $attributes['descText'] : '';
	$iconstyleType = (!empty($attributes['iconstyleType'])) ? $attributes['iconstyleType'] : 'none';
	$extBtnshow = (!empty($attributes['extBtnshow'])) ? $attributes['extBtnshow'] : false ;
	
	$blockClass = Tp_Blocks_Helper::block_wrapper_classes( $attributes );
	
	$arrow='';
	if(!empty($msgArrow)){
		$arrow .= 'msg-arrow-'.esc_attr($icnPosition);
	}
	$withBtnCss = '';
	if(!empty($extBtnshow)){
		$withBtnCss .= 'extra-btn-enable';
	}
	$iconPostfixCss = '';
	if(!empty($icnPosition) && $icnPosition=='postfix'){
		$iconPostfixCss .= 'main-icon-postfix';
	}
	$getIcon = '';
		$getIcon .='<div class="msg-icon-content '.esc_attr($iconPostfixCss).'">';
			$getIcon .='<span class="msg-icon '.esc_attr($arrow).'">';
				$getIcon .='<i class="'.esc_attr($IconName).'"></i>';
			$getIcon .='</span>';
		$getIcon .='</div>';
	
	$getDismiss = '';
		$getDismiss .='<div class="msg-dismiss-content">';
			$getDismiss .='<span class="dismiss-icon">';
				$getDismiss .='<i class="'.esc_attr($dismsIcon).'"></i>';
			$getDismiss .='</span>';
		$getDismiss .='</div>';
	
	$getTitle = '';
	if(!empty($Title)){
		$getTitle .='<div class="msg-title ">'.wp_kses_post($Title).'</div>';
	}
	
	$getDesc = '';
	if(!empty($Description) && !empty($descText)){
		$getDesc .='<div class="msg-desc">'.wp_kses_post($descText).'</div>';
	}
	
	$getbutton = '';
	$getbutton .= Tpgb_Blocks_Global_Options::load_plusButton_saves($attributes);
	
    $output .= '<div class="tpgb-messagebox tpgb-block-'.esc_attr($block_id).' '.esc_attr($blockClass).'">';
				$output .='<div class="messagebox-bg-box ">';
					$output .='<div class="message-media ">';
						if(!empty($icon) && $icnPosition=='prefix'){
							$output .=$getIcon;
						}
						$output .='<div class="msg-content">';
							$output .= '<div class="msg-inner-body '.esc_attr($withBtnCss).'">';
								$output .= $getTitle;
								if(!empty($extBtnshow)){
									$output .= $getbutton;
								}
							$output .='</div>';
							$output .= $getDesc;
						$output .='</div>';
						if(!empty($dismiss)){
							$output .=$getDismiss;
						}
						if(!empty($icon) && $icnPosition=='postfix'){
							$output .=$getIcon;
						}
					$output .='</div>';
				$output .= '</div>';
    $output .= '</div>';
	
	$output = Tpgb_Blocks_Global_Options::block_Wrap_Render($attributes, $output);
	
    return $output;
}

/**
 * Render for the server-side
 */
function tpgb_tp_messagebox() {
	$globalBgOption = Tpgb_Blocks_Global_Options::load_bg_options();
	$globalpositioningOption = Tpgb_Blocks_Global_Options::load_positioning_options();
	$plusButton_options = Tpgb_Blocks_Global_Options::load_plusButton_options();
	$globalPlusExtrasOption = Tpgb_Blocks_Global_Options::load_plusextras_options();
		$attributesOptions = array(
			'block_id' => array(
                'type' => 'string',
				'default' => '',
			),
			'Title' => [
				'type' => 'string',
				'default' => 'Special Alert message for you. Got it?',	
			],
			'Description' => [
				'type' => 'boolean',
				'default' => false,	
			],
			'descText' => [
				'type' => 'string',
				'default' => 'I Am Text Block. Click Edit Button To Change This Text. Lorem Ipsum Dolor Sit Amet, Consectetur Adipiscing Elit. Ut Elit Tellus, Luctus Nec Ullamcorper Mattis, Pulvinar Dapibus Leo.',	
			],
			'icon' => [
				'type' => 'boolean',
				'default' => true,	
			],
			'icnPosition' => [
				'type' => 'string',
				'default' => 'prefix',	
			],
			'IconName' => [
				'type'=> 'string',
				'default'=> 'fas fa-exclamation',
			],
			'dismiss' => [
				'type' => 'boolean',
				'default' => true,	
			],
			'dismsIcon' => [
				'type'=> 'string',
				'default'=> 'far fa-times-circle',
			],
			'titleTypo' => [
				'type'=> 'object',
				'default'=> (object) [
					'openTypography' => 0,
					'size' => [ 'md' => '', 'unit' => 'px' ],
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'Title', 'relation' => '!=', 'value' => '' ]],
						'selector' => '{{PLUS_WRAP}} .msg-title',
					],
				],
			],
			'titleAdjust' => [
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
						'selector' => '{{PLUS_WRAP}} .msg-inner-body{padding: {{titleAdjust}};}',
					],
				],
			],
			'titleMargin' => [
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
						'selector' => '{{PLUS_WRAP}} .msg-inner-body{margin: {{titleMargin}};}',
					],
				],
			],
			'titleNmlColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'Title', 'relation' => '!=', 'value' => '' ]],
						'selector' => '{{PLUS_WRAP}} .msg-title{ color: {{titleNmlColor}}; }',
					],
				],
			],
			'titleHvrColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [ 
						'condition' => [(object) ['key' => 'Title', 'relation' => '!=', 'value' => '' ]],
						'selector' => '{{PLUS_WRAP}} .messagebox-bg-box:hover .msg-title{ color: {{titleHvrColor}}; }',
					],
				],
			],
			'titleNmlBG' => [
				'type' => 'object',
				'default' => (object) [
					'bgType' => 'color',
					'bgGradient' => (object) [],
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'Title', 'relation' => '!=', 'value' => '' ]],
						'selector' => '{{PLUS_WRAP}} .msg-inner-body',
					],
				],
			],
			'titleHvrBG' => [
				'type' => 'object',
				'default' => (object) [
					'bgType' => 'color',
					'bgGradient' => (object) [],
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'Title', 'relation' => '!=', 'value' => '' ]],
						'selector' => '{{PLUS_WRAP}} .messagebox-bg-box:hover .msg-inner-body',
					],
				],
			],
			'titleNmlShadow' => [
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
						'condition' => [(object) ['key' => 'Title', 'relation' => '!=', 'value' => '' ]],
						'selector' => '{{PLUS_WRAP}} .msg-inner-body',
					],
				],
			],
			'titleHvrShadow' => [
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
						'condition' => [(object) ['key' => 'Title', 'relation' => '!=', 'value' => '' ]],
						'selector' => '{{PLUS_WRAP}} .messagebox-bg-box:hover .msg-inner-body',
					],
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
						'condition' => [(object) ['key' => 'Description', 'relation' => '==', 'value' => true ]],
						'selector' => '{{PLUS_WRAP}} .msg-desc',
					],
				],
			],
			'descAdjust' => [
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
						'selector' => '{{PLUS_WRAP}} .msg-desc{padding: {{descAdjust}};}',
					],
				],
			],
			'descMargin' => [
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
						'selector' => '{{PLUS_WRAP}} .msg-desc{margin: {{descMargin}};}',
					],
				],
			],
			'descNmlColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'Description', 'relation' => '==', 'value' => true ]],
						'selector' => '{{PLUS_WRAP}} .msg-desc{ color: {{descNmlColor}}; }',
					],
				],
			],
			'descHvrColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'Description', 'relation' => '==', 'value' => true ]],
						'selector' => '{{PLUS_WRAP}} .messagebox-bg-box:hover .msg-desc{ color: {{descHvrColor}}; }',
					],
				],
			],
			'descNmlBG' => [
				'type' => 'object',
				'default' => (object) [
					'bgType' => 'color',
					'bgGradient' => (object) [],
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'Description', 'relation' => '==', 'value' => true ]],
						'selector' => '{{PLUS_WRAP}} .msg-desc',
					],
				],
			],
			'descHvrBG' => [
				'type' => 'object',
				'default' => (object) [
					'bgType' => 'color',
					'bgGradient' => (object) [],
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'Description', 'relation' => '==', 'value' => true ]],
						'selector' => '{{PLUS_WRAP}} .messagebox-bg-box:hover .msg-desc',
					],
				],
			],
			'descNmlBRadius' => [
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
						'condition' => [(object) ['key' => 'Description', 'relation' => '==', 'value' => true ]],
						'selector' => '{{PLUS_WRAP}} .msg-desc{border-radius: {{descNmlBRadius}};}',
					],
				],
			],
			'descHvrBRadius' => [
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
						'condition' => [(object) ['key' => 'Description', 'relation' => '==', 'value' => true ]],
						'selector' => '{{PLUS_WRAP}} .messagebox-bg-box:hover .msg-desc{border-radius: {{descHvrBRadius}};}',
					],
				],
			],
			'iconSize' => [
				'type' => 'object',
				'default' => ['md' => ''],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'icon', 'relation' => '==', 'value' => true ]],
						'selector' => '{{PLUS_WRAP}} .messagebox-bg-box .msg-icon{ font-size: {{iconSize}}; }',
					],
				],
			],
			'iconWidth' => [
				'type' => 'object',
				'default' => ['md' => ''],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'icon', 'relation' => '==', 'value' => true ]],
						'selector' => '{{PLUS_WRAP}}  .messagebox-bg-box .msg-icon{ width: {{iconWidth}}; height: {{iconWidth}}; line-height: {{iconWidth}}; }',
					],
				],
			],
			'msgArrow' => [
				'type' => 'boolean',
				'default' => true,	
			],
			'iconNormalColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'icon', 'relation' => '==', 'value' => true ]],
						'selector' => '{{PLUS_WRAP}}  .messagebox-bg-box .msg-icon{ color: {{iconNormalColor}}; }',
					],
				],
			],
			'iconHoverColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'icon', 'relation' => '==', 'value' => true ]],
						'selector' => '{{PLUS_WRAP}} .messagebox-bg-box:hover .msg-icon{ color: {{iconHoverColor}}; }',
					],
				],
			],
			'bgNormalColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'icon', 'relation' => '==', 'value' => true ]],
						'selector' => '{{PLUS_WRAP}} .messagebox-bg-box .msg-icon{ background: {{bgNormalColor}}; }',
					],
				],
			],
			'bgHoverColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'icon', 'relation' => '==', 'value' => true ]],
						'selector' => '{{PLUS_WRAP}} .messagebox-bg-box:hover .msg-icon{ background: {{bgHoverColor}}; }',
					],
				],
			],
			'arrowNormalColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'icnPosition', 'relation' => '==', 'value' => 'prefix' ] , ['key' => 'msgArrow', 'relation' => '==', 'value' => true ]],
						'selector' => '{{PLUS_WRAP}} .msg-arrow-prefix::after{ border-left-color: {{arrowNormalColor}}; }',
					],
					(object) [
						'condition' => [(object) ['key' => 'icnPosition', 'relation' => '==', 'value' => 'postfix' ] , ['key' => 'msgArrow', 'relation' => '==', 'value' => true ]],
						'selector' => '{{PLUS_WRAP}} .msg-arrow-postfix::after{ border-right-color: {{arrowNormalColor}}; }',
					],
				],
			],
			'arrowHoverColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'icnPosition', 'relation' => '==', 'value' => 'prefix' ] , ['key' => 'msgArrow', 'relation' => '==', 'value' => true ]],
						'selector' => '{{PLUS_WRAP}} .messagebox-bg-box:hover .msg-arrow-prefix::after{ border-left-color: {{arrowHoverColor}}; }',
					],
					(object) [
						'condition' => [(object) ['key' => 'icnPosition', 'relation' => '==', 'value' => 'postfix' ] , ['key' => 'msgArrow', 'relation' => '==', 'value' => true ]],
						'selector' => '{{PLUS_WRAP}} .messagebox-bg-box:hover .msg-arrow-postfix::after{ border-right-color: {{arrowHoverColor}}; }',
					],
				],
			],
			'iconNmlBorder' => [
				'type' => 'object',
				'default' => (object) [
					'openBorder' => 0,
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'icon', 'relation' => '==', 'value' => true ]],
						'selector' => '{{PLUS_WRAP}} .messagebox-bg-box .msg-icon',
					],
				],
			],
			'iconHvrBorder' => [
				'type' => 'object',
				'default' => (object) [
					'openBorder' => 0,
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'icon', 'relation' => '==', 'value' => true ]],
						'selector' => '{{PLUS_WRAP}} .messagebox-bg-box:hover .msg-icon',
					],
				],
			],
			'iconBdrNmlRadius' => [
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
						'condition' => [(object) ['key' => 'icon', 'relation' => '==', 'value' => true ]],
						'selector' => '{{PLUS_WRAP}} .messagebox-bg-box .msg-icon{border-radius: {{iconBdrNmlRadius}};}',
					],
				],
			],
			'iconBdrHvrRadius' => [
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
						'condition' => [(object) ['key' => 'icon', 'relation' => '==', 'value' => true ]],
						'selector' => '{{PLUS_WRAP}} .messagebox-bg-box:hover .msg-icon{border-radius: {{iconBdrHvrRadius}};}',
					],
				],
			],
			'nmlIconShadow' => [
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
						'condition' => [(object) ['key' => 'icon', 'relation' => '==', 'value' => true ]],
						'selector' => '{{PLUS_WRAP}} .messagebox-bg-box .msg-icon',
					],
				],
			],
			'hvrIconShadow' => [
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
						'condition' => [(object) ['key' => 'icon', 'relation' => '==', 'value' => true ]],
						'selector' => '{{PLUS_WRAP}} .messagebox-bg-box:hover .msg-icon',
					],
				],
			],
			'dIconSize' => [
				'type' => 'object',
				'default' => ['md' => ''],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'dismiss', 'relation' => '==', 'value' => true ]],
						'selector' => '{{PLUS_WRAP}} .messagebox-bg-box .dismiss-icon{ font-size: {{dIconSize}}; }',
					],
				],
			],
			'dIconWidth' => [
				'type' => 'object',
				'default' => ['md' => ''],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'dismiss', 'relation' => '==', 'value' => true ]],
						'selector' => '{{PLUS_WRAP}}  .messagebox-bg-box .dismiss-icon{ width: {{dIconWidth}}; height: {{dIconWidth}}; line-height: {{dIconWidth}}; }',
					],
				],
			],
			'dIconNmlColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'dismiss', 'relation' => '==', 'value' => true ]],
						'selector' => '{{PLUS_WRAP}}  .messagebox-bg-box .dismiss-icon{ color: {{dIconNmlColor}}; }',
					],
				],
			],
			'dIconHvrColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'dismiss', 'relation' => '==', 'value' => true ]],
						'selector' => '{{PLUS_WRAP}} .messagebox-bg-box:hover .dismiss-icon{ color: {{dIconHvrColor}}; }',
					],
				],
			],
			'dIconNmlBG' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'dismiss', 'relation' => '==', 'value' => true ]],
						'selector' => '{{PLUS_WRAP}} .messagebox-bg-box .dismiss-icon{ background: {{dIconNmlBG}}; }',
					],
				],
			],
			'dIconHvrBG' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'dismiss', 'relation' => '==', 'value' => true ]],
						'selector' => '{{PLUS_WRAP}} .messagebox-bg-box:hover .dismiss-icon{ background: {{dIconHvrBG}}; }',
					],
				],
			],
			'dIconNmlBRadius' => [
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
						'condition' => [(object) ['key' => 'dismiss', 'relation' => '==', 'value' => true ]],
						'selector' => '{{PLUS_WRAP}} .messagebox-bg-box .dismiss-icon{border-radius: {{dIconNmlBRadius}};}',
					],
				],
			],
			'dIconHvrBRadius' => [
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
						'condition' => [(object) ['key' => 'dismiss', 'relation' => '==', 'value' => true ]],
						'selector' => '{{PLUS_WRAP}} .messagebox-bg-box:hover .dismiss-icon{border-radius: {{dIconHvrBRadius}};}',
					],
				],
			],
			'dIconNmlShadow' => [
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
						'condition' => [(object) ['key' => 'dismiss', 'relation' => '==', 'value' => true ]],
						'selector' => '{{PLUS_WRAP}} .messagebox-bg-box .dismiss-icon',
					],
				],
			],
			'dIconHvrShadow' => [
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
						'condition' => [(object) ['key' => 'dismiss', 'relation' => '==', 'value' => true ]],
						'selector' => '{{PLUS_WRAP}} .messagebox-bg-box:hover .dismiss-icon',
					],
				],
			],
			'dIconMargin' => [
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
						'selector' => '{{PLUS_WRAP}} .msg-dismiss-content{margin: {{dIconMargin}};}',
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
						'selector' => '{{PLUS_WRAP}} .messagebox-bg-box{padding: {{bgPadding}};}',
					],
				],
			],
			'normalBG' => [
				'type' => 'object',
				'default' => (object) [
					'bgType' => 'color',
					'bgGradient' => (object) [],
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .messagebox-bg-box',
					],
				],
			],
			'HoverBG' => [
				'type' => 'object',
				'default' => (object) [
					'bgType' => 'color',
					'bgGradient' => (object) [],
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .messagebox-bg-box:hover',
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
						'selector' => '{{PLUS_WRAP}} .messagebox-bg-box',
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
						'selector' => '{{PLUS_WRAP}} .messagebox-bg-box:hover',
					],
				],
			],
			'boxBdrNmlRadius' => [
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
						'selector' => '{{PLUS_WRAP}} .messagebox-bg-box{border-radius: {{boxBdrNmlRadius}};}',
					],
				],
			],
			'boxBdrHvrRadius' => [
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
						'selector' => '{{PLUS_WRAP}} .messagebox-bg-box:hover{border-radius: {{boxBdrHvrRadius}};}',
					],
				],
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
						'selector' => '{{PLUS_WRAP}} .messagebox-bg-box',
					],
				],
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
						'selector' => '{{PLUS_WRAP}} .messagebox-bg-box:hover',
					],
				],
			],
		);
	$attributesOptions = array_merge($attributesOptions, $globalBgOption, $globalpositioningOption, $plusButton_options, $globalPlusExtrasOption);
	
	register_block_type( 'tpgb/tp-messagebox', array(
		'attributes' => $attributesOptions,
		'editor_script' => 'tpgb-block-editor-js',
		'editor_style'  => 'tpgb-block-editor-css',
        'render_callback' => 'tpgb_tp_messagebox_render_callback'
    ) );
}
add_action( 'init', 'tpgb_tp_messagebox' );