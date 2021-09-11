<?php
/* Block : Heading Title
 * @since : 1.1.3
 */
defined( 'ABSPATH' ) || exit;

function tpgb_limit_words($string, $word_limit){
	$words = explode(" ",$string);
	return implode(" ",array_splice($words,0,$word_limit));
}
function tpgb_tp_heading_title_render_callback( $attributes, $content) {
	$output = '';
    $block_id = (!empty($attributes['block_id'])) ? $attributes['block_id'] : uniqid("title");
	$style = (!empty($attributes['style'])) ? $attributes['style'] : 'style-1';
	$headingType = (!empty($attributes['headingType'])) ? $attributes['headingType'] : 'default';
	$Title = (!empty($attributes['Title'])) ? $attributes['Title'] : '';
	$titleType = (!empty($attributes['titleType'])) ? $attributes['titleType'] : 'h3';
	$subTitle = (!empty($attributes['subTitle'])) ? $attributes['subTitle'] : '';
	$subTitleType = (!empty($attributes['subTitleType'])) ? $attributes['subTitleType'] : 'h3';
	$extraTitle = (!empty($attributes['extraTitle'])) ? $attributes['extraTitle'] : '';
	$ETPosition = (!empty($attributes['ETPosition'])) ? $attributes['ETPosition'] : 'afterTitle';
	$subTitlePosition = (!empty($attributes['subTitlePosition'])) ? $attributes['subTitlePosition'] : 'onBottonTitle';
	
	$limitTgl = (!empty($attributes['limitTgl'])) ? $attributes['limitTgl'] : false;
	$titleLimit = (!empty($attributes['titleLimit'])) ? $attributes['titleLimit'] : false;
	$titleLimitOn = (!empty($attributes['titleLimitOn'])) ? $attributes['titleLimitOn'] : 'char';
	$titleCount = (!empty($attributes['titleCount'])) ? $attributes['titleCount'] : '3';
	$titleDots = (!empty($attributes['titleDots'])) ? $attributes['titleDots'] : false;
	
	$subTitleLimit = (!empty($attributes['subTitleLimit'])) ? $attributes['subTitleLimit'] : false;
	$subTitleLimitOn = (!empty($attributes['subTitleLimitOn'])) ? $attributes['subTitleLimitOn'] : 'char';
	$subTitleCount = (!empty($attributes['subTitleCount'])) ? $attributes['subTitleCount'] : '3';
	$subTitleDots = (!empty($attributes['subTitleDots'])) ? $attributes['subTitleDots'] : false;
	
	$blockClass = Tp_Blocks_Helper::block_wrapper_classes( $attributes );
	
	$getExtraTitle = '';
	if(!empty($extraTitle)){
		$getExtraTitle .='<span class="title-s ">'.wp_kses_post($extraTitle).'</span>';
	}
	
	$getTitle = '';
	if($headingType=='page'){
		$Title = get_the_title();
	}
	$getTitle .='<div class="head-title ">';
		$getTitle .='<'.esc_attr($titleType).' class="heading-title">';
			if($style=='style-1' && $ETPosition=='beforeTitle'){
				$getTitle .= $getExtraTitle;
			}
				if(!empty($limitTgl) && !empty($titleLimit)){
					if($titleLimitOn=='char'){												
						$getTitle .= substr($Title,0,$titleCount);
						if(!empty($titleDots)){
							$getTitle .= '...';
						}
					}else if($titleLimitOn=='word'){
						$getTitle .= tpgb_limit_words($Title,$titleCount);
						if(!empty($titleDots)){
							if(str_word_count($Title) > $titleCount){
								$getTitle .= '...';
							}
						}
					}
				}else{
					$getTitle .= wp_kses_post($Title);
				}
			if($style=='style-1' && $ETPosition=='afterTitle'){
				$getTitle .= $getExtraTitle;
			}
		$getTitle .='</'.esc_attr($titleType).'>';
	$getTitle .='</div>';
	
	$style_8_sep = '';
	$style_8_sep .='<div class="seprator sep-l">';
		$style_8_sep .='<span class="title-sep sep-l"></span>';
		$style_8_sep .='<div class="sep-dot">.</div>';
		$style_8_sep .='<span class="title-sep sep-r"></span>';
	$style_8_sep .='</div>';
	
	$style_3_sep = '';
	$style_3_sep .='<div class="seprator sep-l">';
		$style_3_sep .='<span class="title-sep sep-l"></span>';
		if(isset($attributes['imgName']) && isset($attributes['imgName']['url']) && $attributes['imgName']['url']!=''){
			$imgSrc ='';
			if(!empty($attributes['imgName']['id'])){
				$imgSrc = wp_get_attachment_image( $attributes['imgName']['id'] , 'full' );
			}else if(!empty($attributes['imgName']['url'])){
				$imgSrc = '<img src="'.esc_url($attributes['imgName']['url']).'"  alt="'.esc_attr__('image seprator','tpgb').'" />';
			}
			$style_3_sep .='<div class="sep-mg">';
				$style_3_sep .= $imgSrc;
			$style_3_sep .='</div>';
		}
		$style_3_sep .='<span class="title-sep sep-r"></span>';
	$style_3_sep .='</div>';
	
	$getSubTitle = '';
	if(!empty($subTitle)){
		$getSubTitle .= '<div class="sub-heading ">';
			$getSubTitle .= '<'.esc_attr($subTitleType).' class="heading-sub-title">';
				if(!empty($limitTgl) && !empty($subTitleLimit)){
					if($subTitleLimitOn=='char'){												
						$getSubTitle .= substr($subTitle,0,$subTitleCount);
						if(!empty($subTitleDots)){
							$getSubTitle .= '...';
						}
					}else if($subTitleLimitOn=='word'){
						$getSubTitle .= tpgb_limit_words($subTitle,$subTitleCount);
						if(str_word_count($subTitle) > $subTitleCount){
							if(!empty($subTitleDots)){
								$getSubTitle .= '...';
							}
						}
					}
				}else{
					$getSubTitle .= wp_kses_post($subTitle);
				}
			$getSubTitle .= '</'.esc_attr($subTitleType).'>';
			$getSubTitle .= '</div>';
	}
	
    $output .= '<div class="tpgb-heading-title heading_style  tpgb-block-'.esc_attr($block_id).' '.esc_attr($blockClass).' heading-'.esc_attr($style).'">';
		$output .='<div class="sub-style">';
			if($style=='style-5'){
				$output .='<div class="vertical-divider top"></div>';
			}
			if($subTitlePosition=='onBottonTitle'){
				if(!empty($Title)){
					$output .=$getTitle;
				}
				if($style=='style-3' && !empty($Title)){
					$output .=$style_3_sep;
				}
				if($style=='style-8' && !empty($Title)){
					$output .=$style_8_sep;
				}
			}
			if($subTitlePosition=='onTopTitle'){
				$output .=$getSubTitle;
			}
			
			if($subTitlePosition=='onBottonTitle'){
				$output .=$getSubTitle;
			}
			if($subTitlePosition=='onTopTitle'){
				if(!empty($Title)){
					$output .=$getTitle;
				}
				if($style=='style-3' && !empty($Title)){
					$output .=$style_3_sep;
				}
				if($style=='style-8' && !empty($Title)){
					$output .=$style_8_sep;
				}
			}
			if($style=='style-5'){
				$output .='<div class="vertical-divider bottom"></div>';
			}
	$output .= '</div></div>';
	
	$output = Tpgb_Blocks_Global_Options::block_Wrap_Render($attributes, $output);
	
    return $output;
}

/**
 * Render for the server-side
 */
function tpgb_tp_heading_title() {
	$globalBgOption = Tpgb_Blocks_Global_Options::load_bg_options();
	$globalpositioningOption = Tpgb_Blocks_Global_Options::load_positioning_options();
	$globalPlusExtrasOption = Tpgb_Blocks_Global_Options::load_plusextras_options();
	
	$attributesOptions = array(
			'block_id' => array(
                'type' => 'string',
				'default' => '',
			),
			'style' => [
				'type' => 'string',
				'default' => 'style-1',	
			],
			
			'Title' => [
				'type' => 'string',
				'default' => 'Main Heading',
			],
			'subTitle' => [
				'type' => 'string',
				'default' => 'It’s Sub Heading',
			],
			'extraTitle' => [
				'type' => 'string',
				'default' => 'I am Extra',
			],
			'ETPosition' => [
				'type' => 'string',
				'default' => 'afterTitle',	
			],
			
			'headingType' => [
				'type' => 'string',
				'default' => 'default',	
			],
			'Alignment' => [
				'type' => 'object',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'style', 'relation' => '!=', 'value' => 'style-5' ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-heading-title{ text-align: {{Alignment}}; }',
					],
					(object) [
						'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => ['style-3' , 'style-8'] ] , ['key' => 'Alignment', 'relation' => '==', 'value' => 'left' ]],
						'selector' => '{{PLUS_WRAP}} .seprator{ margin-left: 0; }',
					],
					(object) [
						'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => ['style-3' , 'style-8'] ] , ['key' => 'Alignment', 'relation' => '==', 'value' => 'justify' ]],
						'selector' => '{{PLUS_WRAP}} .seprator{ margin-left: 0; }',
					],
					(object) [
						'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => ['style-3' , 'style-8'] ] , ['key' => 'Alignment', 'relation' => '==', 'value' => 'right' ]],
						'selector' => '{{PLUS_WRAP}} .seprator{ margin-right: 0; }',
					],
					(object) [
						'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-6' ] , ['key' => 'Alignment', 'relation' => '==', 'value' => 'left' ]],
						'selector' => '{{PLUS_WRAP}}.heading-style-6 .head-title:after{ margin-left: 0; left:15px ;right: auto; }',
					],
					(object) [
						'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-6' ] , ['key' => 'Alignment', 'relation' => '==', 'value' => 'justify' ]],
						'selector' => '{{PLUS_WRAP}}.heading-style-6 .head-title:after{ margin-left: 0; left:15px ;right: auto; }',
					],
					(object) [
						'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-6' ] , ['key' => 'Alignment', 'relation' => '==', 'value' => 'right' ]],
						'selector' => '{{PLUS_WRAP}}.heading-style-6 .head-title:after{ margin-right: 0; right:15px ;left: auto; }',
					],
				],
			],
			'limitTgl' => [
				'type' => 'boolean',
				'default' => false,
			],
			'titleLimit' => [
				'type' => 'boolean',
				'default' => false,
			],
			'titleLimitOn' => [
				'type' => 'string',
				'default' => 'char',	
			],
			'titleCount' => [
				'type' => 'string',
				'default' => '3',	
			],
			'titleDots' => [
				'type' => 'boolean',
				'default' => false,
			],
			'subTitleLimit' => [
				'type' => 'boolean',
				'default' => false,
			],
			'subTitleLimitOn' => [
				'type' => 'string',
				'default' => 'char',	
			],
			'subTitleCount' => [
				'type' => 'string',
				'default' => '3',	
			],
			'subTitleDots' => [
				'type' => 'boolean',
				'default' => false,
			],
			'subTitlePosition' => [
				'type' => 'string',
				'default' => 'onBottonTitle',	
			],
			
			'imgName' => [
				'type' => 'object',
				'default' => [
					'url' => '',
					'Id' => '',
				],
			],
			'sepColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
						(object) [
						'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-3' ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-heading-title .title-sep{ border-color: {{sepColor}}; }',
					],
					(object) [
						'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-4' ]],
						'selector' => '{{PLUS_WRAP}}.heading-style-4 .heading-title:after,{{PLUS_WRAP}}.heading-style-4 .heading-title:before{ background: {{sepColor}}; }',
					],
					(object) [
						'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-5' ]],
						'selector' => '{{PLUS_WRAP}}.heading-style-5 .vertical-divider{ background-color: {{sepColor}}; }',
					],
					(object) [
						'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-8' ]],
						'selector' => '{{PLUS_WRAP}}.heading-style-8 .title-sep{ border-color: {{sepColor}}; }',
					],
				],
			],
			'sepWidth' => [
				'type' => 'object',
				'default' => ["md" => "","unit" => "%"],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-3' ]],
						'selector' => '{{PLUS_WRAP}}.heading-style-3 .title-sep{ width: {{sepWidth}}; }',
					],
					(object) [
						'condition' => [(object) ['key' => 'imgName.url', 'relation' => '!=', 'value' => '' ]],
						'selector' => '{{PLUS_WRAP}}.heading-style-3 .seprator{ width: {{sepWidth}}; }',
					],
					(object) [
						'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-8' ]],
						'selector' => '{{PLUS_WRAP}}.heading-style-8 .seprator{ width: {{sepWidth}}; }',
					],
				],
			],
			'sepHeight' => [
				'type' => 'object',
				'default' => ["md" => "","unit" => "px"],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-3' ]],
						'selector' => '{{PLUS_WRAP}}.heading-style-3 .title-sep{ border-width: {{sepHeight}}; }',
					],
				],
			],
			
			'topSepHeight' => [
				'type' => 'object',
				'default' => ["md" => "","unit" => "px"],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-4' ]],
						'selector' => '{{PLUS_WRAP}}.heading-style-4 .heading-title:before{ height: {{topSepHeight}}; }',
					],
				],
			],
			'bottomSepHeight' => [
				'type' => 'object',
				'default' => ["md" => "","unit" => "px"],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-4' ]],
						'selector' => '{{PLUS_WRAP}}.heading-style-4 .heading-title:after{ height: {{bottomSepHeight}}; }',
					],
				],
			],
			'sepDotColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-6' ]],
						'selector' => '{{PLUS_WRAP}}.heading-style-6 .head-title:after{ color: {{sepDotColor}}; text-shadow:15px 0 {{sepDotColor}},-15px 0 {{sepDotColor}};}',
					],
					(object) [
						'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-8' ]],
						'selector' => '{{PLUS_WRAP}}.heading-style-8 .sep-dot{ color: {{sepDotColor}}; }',
					],
				],
			],
			'septopspa' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-6' ]],
						'selector' => '{{PLUS_WRAP}}.heading-style-6 .head-title:after{ top : {{septopspa}}px; }',
					]
				],
			],
			'titleType' => [
				'type' => 'string',
				'default' => 'h3',
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
						'selector' => '{{PLUS_WRAP}}.heading_style .heading-title',
					],
				],
			],
			'titleColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'Title', 'relation' => '!=', 'value' => '' ]],
						'selector' => '{{PLUS_WRAP}}.heading_style .heading-title{ color: {{titleColor}}; }',
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
						'selector' => '{{PLUS_WRAP}}.heading_style .heading-title{margin: {{titleMargin}};}',
					],
				],
			],
			'titlePadd' => [
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
						'selector' => '{{PLUS_WRAP}}.heading_style .heading-title{padding: {{titlePadd}};}',
					],
				],
			],
			'titleB' => [
				'type' => 'object',
				'default' => (object) [
					'openBorder' => 0,
					'type' => '',
					'color' => '',
					'width' => (object) [
						'md' => (object)[
							'top' => '',
							'left' => '',
							'bottom' => '',
							'right' => '',
						],
						"unit" => "",
					],
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.heading_style .heading-title',
					],
				],
			],
			'titleBRadius' => [
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
						'selector' => '{{PLUS_WRAP}}.heading_style .heading-title{border-radius: {{titleBRadius}};}',
					],
				],
			],
			'titleBg' => [
				'type' => 'object',
				'default' => (object) [
					'openBg'=> 0,
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.heading_style .heading-title',
					],
				],
			],
			'titleShadow' => [
				'type' => 'object',
				'default' => (object) [
					'openShadow' => 0,
					'blur' => 8,
					'color' => "rgba(0,0,0,0.40)",
					'horizontal' => 0,
					'inset' => 0,
					'spread' => 0,
					'vertical' => 4
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.heading_style .heading-title',
					],
				],
			],
			'subTitleType' => [
				'type' => 'string',
				'default' => 'h3',
			],
			'subTitleTypo' => [
				'type'=> 'object',
				'default'=> (object) [
					'openTypography' => 0,
					'size' => [ 'md' => '', 'unit' => 'px' ],
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'subTitle', 'relation' => '!=', 'value' => '' ]],
						'selector' => '{{PLUS_WRAP}}.heading_style .heading-sub-title',
					],
				],
			],
			'subTitleColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'subTitle', 'relation' => '!=', 'value' => '' ]],
						'selector' => '{{PLUS_WRAP}}.heading_style .heading-sub-title{ color: {{subTitleColor}}; }',
					],
				],
			],
			'subTitleMargin' => [
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
						'selector' => '{{PLUS_WRAP}}.heading_style .heading-sub-title{margin: {{subTitleMargin}};}',
					],
				],
			],
			'extraTitleTypo' => [
				'type'=> 'object',
				'default'=> (object) [
					'openTypography' => 0,
					'size' => [ 'md' => '', 'unit' => 'px' ],
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'extraTitle', 'relation' => '!=', 'value' => '' ]],
						'selector' => '{{PLUS_WRAP}}.heading_style .title-s',
					],
				],
			],
			'extraTitleColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'extraTitle', 'relation' => '!=', 'value' => '' ]],
						'selector' => '{{PLUS_WRAP}}.heading_style .title-s{ color: {{extraTitleColor}}; }',
					],
				],
			],
			
		);
	$attributesOptions = array_merge($attributesOptions, $globalBgOption, $globalpositioningOption, $globalPlusExtrasOption);
	
	register_block_type( 'tpgb/tp-heading-title', array(
		'attributes' => $attributesOptions,
		'editor_script' => 'tpgb-block-editor-js',
		'editor_style'  => 'tpgb-block-editor-css',
        'render_callback' => 'tpgb_tp_heading_title_render_callback'
    ) );
}
add_action( 'init', 'tpgb_tp_heading_title' );