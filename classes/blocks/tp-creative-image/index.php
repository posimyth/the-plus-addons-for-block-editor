<?php
/**
 * Render for the server-side
 */
function tpgb_tp_creative_image_render() {
	
	$globalBgOption = Tpgb_Blocks_Global_Options::load_bg_options();
	$globalpositioningOption = Tpgb_Blocks_Global_Options::load_positioning_options();
	$globalPlusExtrasOption = Tpgb_Blocks_Global_Options::load_plusextras_options();
	$attributesOptions = array(
			'block_id' => [
                'type' => 'string',
				'default' => '',
			],
			'SelectImg' => [
				'type' => 'object',
				'default' => [
					'url' => TPGB_ASSETS_URL. 'assets/images/tpgb-placeholder.jpg'
				],
			],
			'ScrollRevelImg' => [
				'type' => 'boolean',
				'default' => false,
			],
			'ImgSize' => [
				'type' => 'string',
				'default' => 'full',
			],
			'Alignment' => [
				'type' => 'object',
				'default' => [ 'md' => 'center', 'sm' =>  '', 'xs' =>  '' ],
			],
			'link' => [
				'type'=> 'object',
				'default'=> [
					'url' => '',	
					'target' => '',	
					'nofollow' => ''
				],
			],
			'ImgWidth' => [
				'type' => 'object',
				'default' => ['md' => '',"unit" => 'px'],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-animate-image .tpgb-creative-img-wrap img,{{PLUS_WRAP}} .tpgb-animate-image .scroll-image-wrap,{{PLUS_WRAP}} .tpgb-animate-image figure:not(.tpgb-parallax-img-parent):not(.tpgb-creative-img-parallax){max-width: {{ImgWidth}};width:100%;}'
					],
				],
			],
			'showCaption' => [
				'type' => 'boolean',
				'default' => false,
			],
			'ScrollImgEffect' => [
				'type' => 'boolean',
				'default' => false,	
			],
			'showMaskImg' => [
				'type' => 'boolean',
				'default' => false,	
			],
			'MaskImg' => [
				'type' => 'object',
				'default' => [
                    'url' => TPGB_ASSETS_URL. 'assets/images/team-mask.png'
				],
			],
			'MaskShadow' => [
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
						'selector' => '{{PLUS_WRAP}} .tpgb-animate-image',
					],
				],
			],
			'ScrollParallax' => [
				'type' => 'boolean',
				'default' => false,
			],
			'ScrollMoveY' => [
				'type' => 'string',
				'default' => '120',
			],
            'border' => [
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
						'selector' => '{{PLUS_WRAP}}.tpgb-creative-image .tpgb-animate-image img, {{PLUS_WRAP}}.tpgb-creative-image .scroll-image-wrap',
					],
				],
            ],
			'borderHover' => [
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
						'selector' => '{{PLUS_WRAP}}.tpgb-creative-image .tpgb-animate-image img:hover, {{PLUS_WRAP}}.tpgb-creative-image .scroll-image-wrap:hover',
					],
				],
            ],
			'borderRadius' => [
				'type' => 'object',
				'default' => (object)[ 'md' => (object)['top' => '','right' => '','left' => '','bottom' => '',],],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-creative-image .tpgb-animate-image img,{{PLUS_WRAP}}.tpgb-creative-image .scroll-image-wrap{border-radius: {{borderRadius}};}'
					],
				],
			],
			'borderRadiusHover' => [
				'type' => 'object',
				'default' => (object)[ 'md' => (object)['top' => '','right' => '','left' => '','bottom' => '',],],
				'style' => [
					(object) [
                        'selector' => '{{PLUS_WRAP}}.tpgb-creative-image .tpgb-animate-image img:hover,{{PLUS_WRAP}}.tpgb-creative-image .scroll-image-wrap:hover{border-radius: {{borderRadiusHover}};}'
					],
				],
			],
			'shadow' => [
				'type' => 'object',
				'default' => ['openShadow' => 0,],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-creative-image .tpgb-animate-image img,{{PLUS_WRAP}}.tpgb-creative-image .scroll-image-wrap',
					],
				],
			],
			'shadowHover' => [
				'type' => 'object',
				'default' => ['openShadow' => 0,],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-creative-image .tpgb-animate-image img:hover,{{PLUS_WRAP}}.tpgb-creative-image .scroll-image-wrap:hover',
					],
				],
			],
		);
		
	$attributesOptions = array_merge($attributesOptions, $globalBgOption, $globalpositioningOption, $globalPlusExtrasOption);
	
	register_block_type( 'tpgb/tp-creative-image', array(
		'attributes' => $attributesOptions,
		'editor_script' => 'tpgb-block-editor-js',
		'editor_style'  => 'tpgb-block-editor-css',
        'render_callback' => 'tpgb_tp_creative_image_callback'
    ) );
}
add_action( 'init', 'tpgb_tp_creative_image_render' );

/*
 *  After rendering from the block editor display output on front-end
 */
function tpgb_tp_creative_image_callback( $settings, $content) {
	
	$block_id	= !empty($settings['block_id']) ? $settings['block_id'] : '';
	$className = (!empty($settings['className'])) ? $settings['className'] :'';
	$align = (!empty($settings['align'])) ? $settings['align'] :'';
	
	$blockClass = '';
	if(!empty($className)){
		$blockClass .= $className;
	}
	if(!empty($align)){
		$blockClass .= ' align'.$align;
	}
	
	$contentImage = $imgID ='';
	if ( isset( $settings['SelectImg']['id'] ) && !empty($settings['SelectImg']['id'])) {
		$imgID = $settings['SelectImg']['id'];
	}
	if ( ! empty( $settings['SelectImg']['url'] ) && isset( $settings['SelectImg']['id'] ) ) {
		$attr = array(
			'class' => "hover__img info_img",
		);
		$contentImage = wp_get_attachment_image($imgID, $settings['ImgSize'],"",$attr);				
	} else { 
		$contentImage .= tpgb_loading_image_grid(get_the_ID());
	}
	
	$href = $target = $rel = '';
	if (!empty($settings['link']['url'])) {
		$href  = ($settings['link']['url'] !== '' ) ? $settings['link']['url'] : ''; 
		$target  = (!empty($settings['link']['target'])) ? 'target="_blank"' : ''; 
		$rel = (!empty($settings['link']['rel'])) ? 'rel="nofollow"' : '';
	}

	$maskImage='';
	if(!empty($settings["showMaskImg"])){
		$maskImage=' tpgb-creative-mask-media';
	}
	$wrapperClass='tpgb-creative-img-wrap '.esc_attr($maskImage);

	$dataImage='';
	if(isset($settings['SelectImg']['id'])) {
		$fullImage = wp_get_attachment_image_src( $imgID, 'full' );
		
		$dataImage = (!empty($fullImage) && !empty($fullImage[0])) ? 'background: url('.esc_url($fullImage[0]).');' : '';
	} else {
		$dataImage = tpgb_loading_image_grid('','background');
	}

	if ( ! empty( $settings['link']['url'] ) ) {
		$html = '<a href="'.esc_url($href).'" '.$target.' '.$rel.' class="' . esc_attr($wrapperClass) . ' ">' .$contentImage. '</a>';
	} else {
		$html = '<div class="' . esc_attr($wrapperClass) . '">' .$contentImage. '</div>';
	}

	$uid=uniqid('bg-image');
	$cssRule=$cssData=$animatedClass='';

	if(!empty($settings["showMaskImg"]) && !empty($settings['MaskImg']['url'])) {
		$cssData .= '.' . esc_attr( $uid ) . '.tpgb-animate-image .tpgb-creative-img-wrap.tpgb-creative-mask-media{mask-image: url('.esc_url($settings['MaskImg']['url']).');-webkit-mask-image: url('.esc_url($settings['MaskImg']['url']).');}';
	}
	$cssClass = '';
	$cssClass = ' text-' . esc_attr($settings["Alignment"]['md']) . ' '.esc_attr($animatedClass);
	$cssClass .= (!empty($settings["Alignment"]['sm'])) ? ' text-tablet-' . esc_attr($settings["Alignment"]['sm']) : '';
	$cssClass .= (!empty($settings["Alignment"]['xs'])) ? ' text-mobile-' . esc_attr($settings["Alignment"]['xs']) : '';

	$uidWidget = uniqid("plus");
	$output = '<div id="'.esc_attr($uidWidget).'" class="tpgb-creative-image tpgb-block-'.esc_attr($block_id).' '.esc_attr($blockClass).'">';
		$output .= '<div class="tpgb-anim-img-parallax" >';
			$output .= '<div class="tpgb-animate-image '.esc_attr($uid).' ' .  trim( $cssClass ) . ' ">
				<figure>' . $html . '</figure>
				</div>';
		$output .= '</div>';
	$output .= '</div>';
	
	$cssRule='';
	if(!empty($cssData)){
		$cssRule='<style>';
		$cssRule .= $cssData;
		$cssRule .= '</style>';
	}
	
	$output = Tpgb_Blocks_Global_Options::block_Wrap_Render($settings, $output);
	
	return $cssRule.$output;
}

function tpgb_loading_image_grid($postid = '', $type = '') {
	global $post;
	$contentImage = '';
	if($type!='background'){		
		$imageUrl = TPGB_ASSETS_URL. 'assets/images/tpgb-placeholder.jpg';
		$contentImage = '<img src="'.esc_url($imageUrl).'" alt="'.esc_attr(get_the_title()).'"/>';
		return $contentImage;
	} elseif($type == 'background') {
		$imageUrl = TPGB_ASSETS_URL. 'assets/images/tpgb-placeholder.jpg';
		$dataSrc = "background:url(".esc_url($imageUrl).") #f7f7f7;";
		return $dataSrc;
	}
}