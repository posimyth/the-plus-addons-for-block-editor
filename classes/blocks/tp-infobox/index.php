<?php
/* Block : Info Box
 * @since : 1.1.7
 */
defined( 'ABSPATH' ) || exit;

function tpgb_tp_infobox_render_callback( $attributes, $content) {
	$output = '';
    $block_id = (!empty($attributes['block_id'])) ? $attributes['block_id'] : uniqid("title");
	$layoutType = (!empty($attributes['layoutType'])) ? $attributes['layoutType'] : 'listing';
	$styleType = (!empty($attributes['styleType'])) ? $attributes['styleType'] : 'style-1';
	$extBtnshow = (!empty($attributes['extBtnshow'])) ? $attributes['extBtnshow'] : false ;
	$verticalCenter = (!empty($attributes['verticalCenter'])) ? $attributes['verticalCenter'] : false;
	$sideImgBorder = (!empty($attributes['sideImgBorder'])) ? $attributes['sideImgBorder'] : false;
	$displayBorder = (!empty($attributes['displayBorder'])) ? $attributes['displayBorder'] : false;
	$dispPinText = (!empty($attributes['dispPinText'])) ? $attributes['dispPinText'] : false;
	$pinText = (!empty($attributes['pinText'])) ? $attributes['pinText'] : 'New';
	$IBoxLinkTgl = (!empty($attributes['IBoxLinkTgl'])) ? $attributes['IBoxLinkTgl'] : false;
	$IBoxLink = (!empty($attributes['IBoxLink']['url'])) ? $attributes['IBoxLink']['url'] : '';
	$target = (!empty($attributes['IBoxLink']['target'])) ? '_blank' : '';
	$nofollow = (!empty($attributes['IBoxLink']['nofollow'])) ? 'nofollow' : '';
	$iconType = (!empty($attributes['iconType'])) ? $attributes['iconType'] : 'icon';
	$IconName = (!empty($attributes['IconName'])) ? $attributes['IconName'] : '';
	$imageName = (!empty($attributes['imageName']['url'])) ? $attributes['imageName'] : '';
	$imageSize = (!empty($attributes['imageSize'])) ? $attributes['imageSize'] : 'full';
	$Title = (!empty($attributes['Title'])) ? $attributes['Title'] : '';
	$Description = (!empty($attributes['Description'])) ? $attributes['Description'] : '';
	$iconstyleType = (!empty($attributes['iconstyleType'])) ? $attributes['iconstyleType'] : 'none';
	$contenthoverEffect = (!empty($attributes['contenthoverEffect'])) ? $attributes['contenthoverEffect'] : '';
	
	$svgIcon = (!empty($attributes['svgIcon'])) ? $attributes['svgIcon'] : '';
	$svgDraw = (!empty($attributes['svgDraw'])) ? $attributes['svgDraw'] : 'delayed';
	$svgstroColor = (!empty($attributes['svgstroColor'])) ? $attributes['svgstroColor'] : '';
	$svgfillColor = (!empty($attributes['svgfillColor'])) ? $attributes['svgfillColor'] : 'none';
	$svgDura = (!empty($attributes['svgDura'])) ? $attributes['svgDura'] : 90;
	
	$blockClass = Tp_Blocks_Helper::block_wrapper_classes( $attributes );
	$imgSrc ='';
	if(!empty($imageName) && !empty($imageName['id'])){
		$imgSrc = wp_get_attachment_image($imageName['id'] , $imageSize, false, ['class' => 'service-icon']);
	}else if(!empty($imageName['url'])){
		$imgSrc = '<img src="'.esc_url($imageName['url']).'" class="service-icon " />';
	}
	
	$vcenter='';
	if(!empty($verticalCenter)){
		$vcenter = 'vertical-center';
	}
	
	$sib='';
	if($styleType=='style-1'){
		if($iconType!='none' && !empty($sideImgBorder)){
			$sib = 'service-img-border';
		}
	}
	
	$mlr16='';
	if($styleType=='style-1' && $iconType!='none'){ 
			$mlr16 = 'm-r-16 style-1 '; 
	}
	
	$getIcon = '';
	if(!empty($iconType)){
			$getIcon .='<div class="info-icon-content">';
				if($iconType!='none' && !empty($dispPinText)){
					$getIcon .='<div class="info-pin-text">'.esc_html($pinText).'</div>';
				}
				$getIcon .='<div class="service-icon-wrap">';
				if($iconType=='icon'){
					$getIcon .='<span class="service-icon  icon-'.esc_attr($iconstyleType).'">';
					$getIcon .='<i class="'.esc_attr($IconName).'"></i>';
					$getIcon .='</span>';
				}else if($iconType=='image'){
					$getIcon .= $imgSrc;
				}else if($iconType=='svg' && !empty($svgIcon) && !empty($svgIcon['url'])){
					$getIcon .= '<div class="tpgb-draw-svg" data-id="service-svg-'.esc_attr($block_id).'" data-type="'.esc_attr($svgDraw).'" data-duration="'.esc_attr($svgDura).'" data-stroke="'.esc_attr($svgstroColor).'" data-fillColor="'.esc_attr($svgfillColor).'" data-fillEnable="yes">';
						$getIcon .= '<object id="service-svg-'.esc_attr($block_id).'" type="image/svg+xml" data="'.$svgIcon['url'].'">';
						$getIcon .= '</object>';
					$getIcon .= '</div>';
				}
				$getIcon .='</div>';
			$getIcon .='</div>';
	}
	
	$getTitle = '';
	if(!empty($Title)){
		if(!$IBoxLinkTgl && !empty($IBoxLink)){
			$getTitle .='<a href="'.esc_url($IBoxLink).'" class="service-title " target="'.esc_attr($target).'"  rel="'.esc_attr($nofollow).'">'.wp_kses_post($Title).'</a>';
		}else{
			$getTitle .='<div class="service-title ">'.wp_kses_post($Title).'</div>';
		}
	}
	
	$getDesc = '';
	$getDesc .='<div class="service-desc">'.wp_kses_post($Description).'</div>';
	
	$getBorder='';
	$getBorder .='<div class="service-border"></div>';
	
	$getbutton = '';
	$getbutton .= Tpgb_Blocks_Global_Options::load_plusButton_saves($attributes);
	
	$getInfoBox='';
	$getInfoBox .='<div class="info-box-inner content_hover_effect  tp-info-nc content_hover_'.esc_attr($contenthoverEffect).'">';
				if(!empty($IBoxLinkTgl) && !empty($IBoxLink)){
					$getInfoBox .='<a href="'.esc_url($IBoxLink).'" class="info-box-bg-box " target="'.esc_attr($target).'"  rel="'.esc_attr($nofollow).'">';
				}else{
					$getInfoBox .='<div class="info-box-bg-box ">';
				}
					if($styleType=='style-1'){
						$getInfoBox .='<div class="service-media text-left '.esc_attr($vcenter).'">';
							if($iconType!='none'){
								$getInfoBox .='<div class="'.esc_attr($mlr16).' '.esc_attr($sib).'">';
									$getInfoBox .=$getIcon;
								$getInfoBox .='</div>';
									
							}
							$getInfoBox .='<div class="service-content">';
								$getInfoBox .=$getTitle;
									if(!empty($displayBorder)){
										$getInfoBox .=$getBorder;
									}
								$getInfoBox .=$getDesc;
									if(!empty($extBtnshow)){
										$getInfoBox .='<div class="infobox-btn-block ">'.$getbutton.'</div>';
									}
							$getInfoBox .= '</div>';
						$getInfoBox .= '</div>';
					}
					
					if($styleType=='style-3'){
						$getInfoBox .='<div class="text-alignment">';
							$getInfoBox .='<div class="style-3">';
								if($iconType!='none'){
									$getInfoBox .=$getIcon;
								}
								$getInfoBox .=$getTitle;
								if(!empty($displayBorder)){
									$getInfoBox .=$getBorder;
								}
								$getInfoBox .=$getDesc;
								if(!empty($extBtnshow)){
									$getInfoBox .='<div class="infobox-btn-block ">'.$getbutton.'</div>';
								}
							$getInfoBox .= '</div>';
						$getInfoBox .= '</div>';
					}
					
				
				if(!empty($IBoxLinkTgl) && !empty($IBoxLink)){
					$getInfoBox .= '</a>';
				}else{
					$getInfoBox .= '</div>';
				}
				
				$getInfoBox .= '<div class="infobox-overlay-color"></div>';
				
			$getInfoBox .= '</div>';
	
    $output .= '<div class="tpgb-infobox tpgb-block-'.esc_attr($block_id).' info-box-'.esc_attr($styleType).' '.esc_attr($blockClass).'">';
		$output .='<div class="post-inner-loop ">';
			$output .=$getInfoBox;
		$output .= '</div>';
    $output .= '</div>';
	
	$output = Tpgb_Blocks_Global_Options::block_Wrap_Render($attributes, $output);
	
    return $output;
}

/**
 * Render for the server-side
 */
function tpgb_tp_infobox() {
	$globalBgOption = Tpgb_Blocks_Global_Options::load_bg_options();
	$globalpositioningOption = Tpgb_Blocks_Global_Options::load_positioning_options();
	$plusButton_options = Tpgb_Blocks_Global_Options::load_plusButton_options();
	$globalPlusExtrasOption = Tpgb_Blocks_Global_Options::load_plusextras_options();
	
	$attributesOptions = array(
			'block_id' => array(
                'type' => 'string',
				'default' => '',
			),
			'layoutType' => [
				'type' => 'string',
				'default' => 'listing',	
			],
			'styleType' => [
				'type' => 'string',
				'default' => 'style-1',	
			],
			'Alignment' => [
				'type' => 'object',
				'default' => 'center',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'styleType', 'relation' => '==', 'value' => 'style-3' ]],
						'selector' => '{{PLUS_WRAP}} .text-alignment{ text-align: {{Alignment}}; }',
					],
					(object) [
						'condition' => [(object) ['key' => 'styleType', 'relation' => '==', 'value' => 'style-3' ],
													['key' => 'Alignment', 'relation' => '==', 'value' => 'center' ]],
						'selector' => '{{PLUS_WRAP}} .text-alignment .service-border{ margin-left:auto;margin-right:auto; }',
					],
					(object) [
						'condition' => [(object) ['key' => 'styleType', 'relation' => '==', 'value' => 'style-3' ],
													['key' => 'Alignment', 'relation' => '==', 'value' => 'left' ]],
						'selector' => '{{PLUS_WRAP}} .text-alignment .service-border{ margin-right:auto; }',
					],
					(object) [
						'condition' => [(object) ['key' => 'styleType', 'relation' => '==', 'value' => 'style-3' ],
													['key' => 'Alignment', 'relation' => '==', 'value' => 'right' ]],
						'selector' => '{{PLUS_WRAP}} .text-alignment .service-border{ margin-left:auto; }',
					],
				],
				'scopy' => true,
			],
			'Title' => [
				'type' => 'string',
				'default' => 'Amazing Feature',	
			],
			'Description' => [
				'type' => 'string',
				'default' => 'Disrupt inspire and think tank, social entrepreneur but preliminary thinking think tank compelling. Inspiring, invest synergy capacity building, white paper; silo, unprecedented challenge B-corp problem-solvers.',	
			],
			'iconType' => [
				'type' => 'string',
				'default' => 'icon',	
			],
			'IconName' => [
				'type'=> 'string',
				'default'=> 'fab fa-angellist',
			],
			'imageName' => [
				'type' => 'object',
				'default' => [],
			],
			'svgIcon' => [
				'type' => 'object',
				'default' => [],
			],
			'imageSize' => [
				'type' => 'string',
				'default' => 'full',	
			],
			'dispPinText' => [
				'type' => 'boolean',
				'default' => false,	
			],
			'pinText' => [
				'type' => 'string',
				'default' => 'New',	
			],
			'IBoxLink' => [
				'type'=> 'object',
				'default'=> [
					'url' => '',	
					'target' => '',
					'nofollow' => ''
				],
			],
			'IBoxLinkTgl' => [
				'type' => 'boolean',
				'default' => false,	
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
						'selector' => '{{PLUS_WRAP}} .service-title',
					],
				],
				'scopy' => true,
			],
			'titleNmlColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'Title', 'relation' => '!=', 'value' => '' ]],
						'selector' => '{{PLUS_WRAP}} .info-box-inner .service-title{ color: {{titleNmlColor}}; }',
					],
				],
				'scopy' => true,
			],
			'titleHvrColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [ 
						'condition' => [(object) ['key' => 'Title', 'relation' => '!=', 'value' => '' ]],
						'selector' => '{{PLUS_WRAP}} .info-box-inner:hover .service-title{ color: {{titleHvrColor}}; }',
					],
				],
				'scopy' => true,
			],
			'titleTopSpace' => [
				'type' => 'object',
				'default' => [ 
					'md' => '',
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'Title', 'relation' => '!=', 'value' => '' ]],
						'selector' => '{{PLUS_WRAP}} .service-title{ margin-top: {{titleTopSpace}}; }',
					],
				],
				'scopy' => true,
			],
			'titleBottomSpace' => [
				'type' => 'object',
				'default' => [ 
					'md' => '',
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'styleType', 'relation' => '!=', 'value' => 'style-4' ],['key' => 'Title', 'relation' => '!=', 'value' => '' ]],
						'selector' => '{{PLUS_WRAP}} .service-title{ margin-bottom: {{titleBottomSpace}}; }',
					],
					(object) [
						'condition' => [(object) ['key' => 'styleType', 'relation' => '==', 'value' => 'style-4' ],['key' => 'Title', 'relation' => '!=', 'value' => '' ]],
						'selector' => '{{PLUS_WRAP}} .service-media{ margin-bottom: {{titleBottomSpace}}; }',
					],
				],
				'scopy' => true,
			],
			'displayBorder' => [
				'type' => 'boolean',
				'default' => false,	
				'scopy' => true,
			],
			'displayBdrWidth' => [
				'type' => 'object',
				'default' => ["md" => "","unit" => "%"],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'displayBorder', 'relation' => '==', 'value' => true ]],
						'selector' => '{{PLUS_WRAP}} .service-border{ width: {{displayBdrWidth}}; }',
					],
				],
				'scopy' => true,
			],
			'displayBdrHeight' => [
				'type' => 'object',
				'default' => ["md" => "","unit" => "px"],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'displayBorder', 'relation' => '==', 'value' => true ]],
						'selector' => '{{PLUS_WRAP}} .service-border{ border-width: {{displayBdrHeight}}; }',
					],
				],
				'scopy' => true,
			],
			'borderColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
						(object) [
						'condition' => [(object) ['key' => 'displayBorder', 'relation' => '==', 'value' => true ]],
						'selector' => '{{PLUS_WRAP}} .service-border{ border-color: {{borderColor}}; }',
					],
				],
				'scopy' => true,
			],
			'descTypo' => [
				'type'=> 'object',
				'default'=> (object) [
					'openTypography' => 0,
					'size' => [ 'md' => '', 'unit' => 'px' ],
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'Description', 'relation' => '!=', 'value' => '' ]],
						'selector' => '{{PLUS_WRAP}} .service-desc',
					],
				],
				'scopy' => true,
			],
			'descNmlColor' => [
				'type' => 'string',
				'default' => '',	
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'Description', 'relation' => '!=', 'value' => '' ]],
						'selector' => '{{PLUS_WRAP}} .service-desc{ color: {{descNmlColor}}; }',
					],
				],
				'scopy' => true,
			],
			'descHvrColor' => [
				'type' => 'string',
				'default' => '',	
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'Description', 'relation' => '!=', 'value' => '' ]],
						'selector' => '{{PLUS_WRAP}} .info-box-inner:hover .service-desc{ color: {{descHvrColor}}; }',
					],
				],
				'scopy' => true,
			],
			'normalBG' => [
				'type' => 'object',
				'default' => (object) [
					'openBg'=> 0,
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .info-box-bg-box',
					],
				],
				'scopy' => true,
			],
			'HoverBG' => [
				'type' => 'object',
				'default' => (object) [
					'openBg'=> 0,
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .info-box-inner:hover .info-box-bg-box',
					],
				],
				'scopy' => true,
			],
			'overlayNmlBG' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-infobox .infobox-overlay-color{ background: {{overlayNmlBG}}; }',
					],
				],
				'scopy' => true,
			],
			'overlayHvrBG' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .info-box-inner:hover:hover .infobox-overlay-color{ background: {{overlayHvrBG}}; }',
					],
				],
				'scopy' => true,
			],
			'boxPadding' => [
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
						'selector' => '{{PLUS_WRAP}} .info-box-bg-box{padding: {{boxPadding}};}',
					],
				],
				'scopy' => true,
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
						'selector' => '{{PLUS_WRAP}} .info-box-bg-box',
					],
				],
				'scopy' => true,
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
						'selector' => '{{PLUS_WRAP}} .info-box-inner:hover .info-box-bg-box',
					],
				],
				'scopy' => true,
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
						'selector' => '{{PLUS_WRAP}} .info-box-bg-box,{{PLUS_WRAP}} .infobox-overlay-color{border-radius: {{boxBdrNmlRadius}};}',
					],
				],
				'scopy' => true,
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
						'selector' => '{{PLUS_WRAP}} .info-box-inner:hover .info-box-bg-box,{{PLUS_WRAP}} .info-box-inner:hover:hover .infobox-overlay-color{border-radius: {{boxBdrHvrRadius}};}',
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
						'selector' => '{{PLUS_WRAP}} .info-box-bg-box',
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
						'selector' => '{{PLUS_WRAP}} .info-box-inner:hover .info-box-bg-box',
					],
				],
				'scopy' => true,
			],
			'iconstyleType' => [
				'type' => 'string',
				'default' => 'none',
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
						'condition' => [(object) ['key' => 'iconType', 'relation' => '==', 'value' => 'icon' ]],
						'selector' => '{{PLUS_WRAP}} .info-box-inner .service-icon{ font-size: {{iconSize}}; }',
					],
				],
				'scopy' => true,
			],
			'iconWidth' => [
				'type' => 'object',
				'default' => (object) [ 
					'md' => '',
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'iconType', 'relation' => '==', 'value' => 'icon' ]],
						'selector' => '{{PLUS_WRAP}}  .info-box-inner .service-icon{ width: {{iconWidth}}; height: {{iconWidth}}; line-height: {{iconWidth}}; }',
					],
				],
				'scopy' => true,
			],
			'iconNormalColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'iconType', 'relation' => '==', 'value' => 'icon' ]],
						'selector' => '{{PLUS_WRAP}} .info-box-inner .service-icon{ color: {{iconNormalColor}}; }',
					],
				],
				'scopy' => true,
			],
			'iconHoverColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'iconType', 'relation' => '==', 'value' => 'icon' ]],
						'selector' => '{{PLUS_WRAP}} .info-box-inner:hover .service-icon{ color: {{iconHoverColor}}; }',
					],
				],
				'scopy' => true,
			],
			'bgNormalColor' => [
				'type' => 'object',
				'default' => (object) [
					'openBg'=> 0,
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'iconType', 'relation' => '==', 'value' => 'icon' ] , ['key' => 'iconstyleType', 'relation' => '!=', 'value' => 'none' ]],
						'selector' => '{{PLUS_WRAP}} .info-box-inner .service-icon',
					],
				],
				'scopy' => true,
			],
			'bgHoverColor' => [
				'type' => 'object',
				'default' => (object) [
					'openBg'=> 0,
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'iconType', 'relation' => '==', 'value' => 'icon' ] , ['key' => 'iconstyleType', 'relation' => '!=', 'value' => 'none' ]],
						'selector' => '{{PLUS_WRAP}} .info-box-inner:hover .service-icon',
					],
				],
				'scopy' => true,
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
						'condition' => [(object) ['key' => 'iconType', 'relation' => '==', 'value' => 'icon' ], ['key' => 'iconstyleType', 'relation' => '==', 'value' => 'square' ]],
						'selector' => '{{PLUS_WRAP}} .info-box-inner .service-icon{border-radius: {{iconBdrNmlRadius}};}',
					],
				],
				'scopy' => true,
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
						'condition' => [(object) ['key' => 'iconType', 'relation' => '==', 'value' => 'icon' ], ['key' => 'iconstyleType', 'relation' => '==', 'value' => 'square' ]],
						'selector' => '{{PLUS_WRAP}} .info-box-inner:hover .service-icon{border-radius: {{iconBdrHvrRadius}};}',
					],
				],
				'scopy' => true,
			],
			'iconBdrNmlType' => [
				'type' => 'object',
				'default' => (object) [
					'openBorder' => 0,
					'type' => 'solid',
					'disableWidthColor' => true,
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'iconType', 'relation' => '==', 'value' => 'icon' ] , ['key' => 'iconstyleType', 'relation' => '==', 'value' => ['square' , 'rounded'] ]],
						'selector' => '{{PLUS_WRAP}} .info-box-inner .icon-square,{{PLUS_WRAP}} .info-box-inner .icon-rounded',
					],
				],
				'scopy' => true,
			],
			'iconBdrNmlColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'iconType', 'relation' => '==', 'value' => 'icon' ] , ['key' => 'iconstyleType', 'relation' => '==', 'value' => ['square' , 'rounded'] ]],
						'selector' => '{{PLUS_WRAP}} .info-box-inner .icon-square,{{PLUS_WRAP}} .info-box-inner .icon-rounded{ border-color: {{iconBdrNmlColor}}; }',
					],
				],
				'scopy' => true,
			],
			'iconBWidth' => [
				'type' => 'object',
				'default' => (object) [ 
					'md' => '',
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'iconType', 'relation' => '==', 'value' => 'icon' ] , ['key' => 'iconstyleType', 'relation' => '==', 'value' => ['square' , 'rounded'] ]],
						'selector' => '{{PLUS_WRAP}} .info-box-inner .icon-square,{{PLUS_WRAP}} .info-box-inner .icon-rounded{ border-width: {{iconBWidth}}; }',
					],
				],
				'scopy' => true,
			],
			'iconBdrHvrColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'iconType', 'relation' => '==', 'value' => 'icon' ] , ['key' => 'iconstyleType', 'relation' => '==', 'value' => ['square' , 'rounded'] ]],
						'selector' => '{{PLUS_WRAP}} .info-box-inner:hover .icon-square,{{PLUS_WRAP}} .info-box-inner:hover .icon-rounded{ border-color: {{iconBdrHvrColor}}; }',
					],
				],
				'scopy' => true,
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
						'condition' => [(object) ['key' => 'iconType', 'relation' => '==', 'value' => 'icon' ] , ['key' => 'iconstyleType', 'relation' => '==', 'value' => ['square' , 'rounded'] ]],
						'selector' => '{{PLUS_WRAP}} .info-box-inner .service-icon',
					],
				],
				'scopy' => true,
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
						'condition' => [(object) ['key' => 'iconType', 'relation' => '==', 'value' => 'icon' ] , ['key' => 'iconstyleType', 'relation' => '==', 'value' => ['square' , 'rounded'] ]],
						'selector' => '{{PLUS_WRAP}} .info-box-inner:hover .service-icon',
					],
				],
				'scopy' => true,
			],
			'iconOverlay' => [
				'type' => 'boolean',
				'default' => false,	
				'scopy' => true,
			],
			'iconShine' => [
				'type' => 'boolean',
				'default' => false,	
				'scopy' => true,
			],
			'imageWidth' => [
				'type' => 'object',
				'default' => (object) [ 
					'md' => '',
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'iconType', 'relation' => '==', 'value' => 'image' ]],
						'selector' => '{{PLUS_WRAP}}  .info-box-inner .service-icon{ width: {{imageWidth}}; height: {{imageWidth}}; }',
					],
				],
				'scopy' => true,
			],
			'imgBdrNmlRadius' => [
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
						'condition' => [(object) ['key' => 'iconType', 'relation' => '==', 'value' => 'image' ]],
						'selector' => '{{PLUS_WRAP}} .info-box-inner .service-icon{border-radius: {{imgBdrNmlRadius}};}',
					],
				],
				'scopy' => true,
			],
			'normalImageShadow' => [
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
						'condition' => [(object) ['key' => 'iconType', 'relation' => '==', 'value' => 'image' ]],
						'selector' => '{{PLUS_WRAP}} .info-box-inner .service-icon',
					],
				],
				'scopy' => true,
			],
			'nmlImgDpShadow' => [
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
						'condition' => [(object) ['key' => 'iconType', 'relation' => '==', 'value' => 'image' ]],
						'selector' => '{{PLUS_WRAP}} .info-box-inner .service-icon',
					],
				],
				'scopy' => true,
			],
			'imgBdrHvrRadius' => [
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
						'condition' => [(object) ['key' => 'iconType', 'relation' => '==', 'value' => 'image' ]],
						'selector' => '{{PLUS_WRAP}} .info-box-inner:hover .service-icon{border-radius: {{imgBdrHvrRadius}};}',
					],
				],
				'scopy' => true,
			],
			'hoverImgShadow' => [
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
						'condition' => [(object) ['key' => 'iconType', 'relation' => '==', 'value' => 'image' ]],
						'selector' => '{{PLUS_WRAP}} .info-box-inner:hover .service-icon',
					],
				],
				'scopy' => true,
			],
			'hvrImgDpShadow' => [
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
						'condition' => [(object) ['key' => 'iconType', 'relation' => '==', 'value' => 'image' ]],
						'selector' => '{{PLUS_WRAP}} .info-box-inner:hover .service-icon',
					],
				],
				'scopy' => true,
			],
			'imgOverlay' => [
				'type' => 'boolean',
				'default' => false,	
				'scopy' => true,
			],
			'pinTextTypo' => [
				'type'=> 'object',
				'default'=> (object) [
					'openTypography' => 0,
					'size' => [ 'md' => '', 'unit' => 'px' ],
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'dispPinText', 'relation' => '==', 'value' => true ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-infobox .info-pin-text ',
					],
				],
				'scopy' => true,
			],
			'pinNmlBorder' => [
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
						'selector' => '{{PLUS_WRAP}}.tpgb-infobox .info-box-inner .info-pin-text',
					],
				],
				'scopy' => true,
			],
			'pinTextNmlColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'dispPinText', 'relation' => '==', 'value' => true ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-infobox .info-box-inner .info-pin-text{ color: {{pinTextNmlColor}}; }',
					],
				],
				'scopy' => true,
			],
			'pinNmlBG' => [
				'type' => 'object',
				'default' => (object) [
					'openBg'=> 0,
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'dispPinText', 'relation' => '==', 'value' => true ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-infobox .info-box-inner .info-pin-text',
					],
				],
				'scopy' => true,
			],
			'pinTextNmlRadius' => [
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
						'condition' => [(object) ['key' => 'dispPinText', 'relation' => '==', 'value' => true ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-infobox .info-box-inner .info-pin-text{border-radius: {{pinTextNmlRadius}};}',
					],
				],
				'scopy' => true,
			],
			'nmlPinShadow' => [
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
						'condition' => [(object) ['key' => 'dispPinText', 'relation' => '==', 'value' => true ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-infobox .info-box-inner .info-pin-text',
					],
				],
				'scopy' => true,
			],
			'pinHvrBorder' => [
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
						"unit" => "",
					],			
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-infobox .info-box-inner:hover .info-pin-text',
					],
				],
				'scopy' => true,
			],
			'pinTextHvrColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'dispPinText', 'relation' => '==', 'value' => true ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-infobox .info-box-inner:hover .info-pin-text{ color: {{pinTextHvrColor}}; }',
					],
				],
				'scopy' => true,
			],
			'pinHvrBG' => [
				'type' => 'object',
				'default' => (object) [
					'openBg'=> 0,
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'dispPinText', 'relation' => '==', 'value' => true ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-infobox .info-box-inner:hover .info-pin-text',
					],
				],
				'scopy' => true,
			],
			'pinTextHvrRadius' => [
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
						'condition' => [(object) ['key' => 'dispPinText', 'relation' => '==', 'value' => true ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-infobox .info-box-inner:hover .info-pin-text{border-radius: {{pinTextHvrRadius}};}',
					],
				],
				'scopy' => true,
			],
			'hvrPinShadow' => [
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
						'condition' => [(object) ['key' => 'dispPinText', 'relation' => '==', 'value' => true ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-infobox .info-box-inner:hover .info-pin-text',
					],
				],
				'scopy' => true,
			],
			'pinSize' => [
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
						'selector' => '{{PLUS_WRAP}}.tpgb-infobox .info-box-inner .info-pin-text{padding: {{pinSize}};}',
					],
				],
				'scopy' => true,
			],
			'pinHrztlAdj' => [
				'type' => 'object',
				'default' => (object) [ 
					'md' => '',
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-infobox .info-box-inner .info-pin-text{ left: {{pinHrztlAdj}}; }',
					],
				],
				'scopy' => true,
			],
			'pinVrtclAdj' => [
				'type' => 'object',
				'default' => (object) [ 
					'md' => '',
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-infobox .info-box-inner .info-pin-text{ top: {{pinVrtclAdj}}; }',
					],
				],
				'scopy' => true,
			],
			
			'verticalCenter' => [
				'type' => 'boolean',
				'default' => false,	
				'scopy' => true,
			],
			'sideImgBorder' => [
				'type' => 'boolean',
				'default' => false,	
				'scopy' => true,
			],
			'bdrRightColor' => [
				'type' => 'string',
				'default' => '',	
				'style' => [
						(object) [
						'condition' => [(object) ['key' => 'sideImgBorder', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}} .style-1.service-img-border{ color: {{bdrRightColor}}; }',
					],
				],
				'scopy' => true,
			],
			'minHeightTgl' => [
				'type' => 'boolean',
				'default' => false,	
				'scopy' => true,
			],
			'minHeight' => [
				'type' => 'object',
				'default' => (object) [ 
					'md' => '',
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'minHeightTgl', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}} .info-box-inner .info-box-bg-box{ min-height: {{minHeight}};display: -webkit-box;display: -ms-flexbox;display: flex;-webkit-box-orient: vertical;-webkit-align-items: center;-ms-align-items: center;align-items: center; } 
						{{PLUS_WRAP}}.info-box-style-3 .info-box-inner .info-box-bg-box{ -webkit-justify-content: center;-moz-justify-content: center;-ms-justify-content: center;justify-content: center; }',
					],
				],
				'scopy' => true,
			],
			'contenthoverEffect' => [
				'type' => 'string',
				'default' => '',
				'scopy' => true,				
			],
			
			'svgDraw' => [
				'type' => 'string',
				'default' => 'delayed',	
				'scopy' => true,
			],
			'svgDura' => [
				'type' => 'string',
				'default' => '90',
				'scopy' => true,
			],
			'svgmaxWidth' => [
				'type' => 'object',
				'default' => [ 
					'md' => '',
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .service-icon-wrap .tpgb-draw-svg{ max-width: {{svgmaxWidth}}; max-height: {{svgmaxWidth}}; }',
					],
				],
				'scopy' => true,
			],
			'svgstroColor' => [
				'type' => 'string',
				'default' => '#000000',
				'scopy' => true,
			],
			'svgfillColor' => [
				'type' => 'string',
				'default' => '',
				'scopy' => true,
			],
		);
	$attributesOptions = array_merge($attributesOptions,$globalBgOption,$globalpositioningOption,$plusButton_options, $globalPlusExtrasOption);
	
	register_block_type( 'tpgb/tp-infobox', array(
		'attributes' => $attributesOptions,
		'editor_script' => 'tpgb-block-editor-js',
		'editor_style'  => 'tpgb-block-editor-css',
        'render_callback' => 'tpgb_tp_infobox_render_callback'
    ) );
}
add_action( 'init', 'tpgb_tp_infobox' );