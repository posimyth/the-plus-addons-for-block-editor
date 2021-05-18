<?php
/* Tp Block : Site Logo
 * @since	: 1.1.0
 */
function tpgb_tp_site_logo_render_callback( $attributes, $content) {
    $block_id = (!empty($attributes['block_id'])) ? $attributes['block_id'] : uniqid("title");
	$logoNmlDbl = (!empty($attributes['logoNmlDbl'])) ? $attributes['logoNmlDbl'] : 'normal';
	$logoType = (!empty($attributes['logoType'])) ? $attributes['logoType'] : 'img';
	$imageStore = (!empty($attributes['imageStore']['url'])) ? $attributes['imageStore'] : '';
	$imageSize = (!empty($attributes['imageSize'])) ? $attributes['imageSize'] : 'thumbnail' ;
	$svgStore = (!empty($attributes['svgStore']['url'])) ? $attributes['svgStore'] : '';
	
	$hvrImageStore = (!empty($attributes['hvrImageStore']['url'])) ? $attributes['hvrImageStore'] : '';
	$hvrImageSize = (!empty($attributes['hvrImageSize'])) ? $attributes['hvrImageSize'] : 'thumbnail' ;
	$hvrSvgStore = (!empty($attributes['hvrSvgStore']['url'])) ? $attributes['hvrSvgStore'] : '';
	
	$urlType = (!empty($attributes['urlType'])) ? $attributes['urlType'] : 'home';
	
	$stickyLogo = (!empty($attributes['stickyLogo'])) ? $attributes['stickyLogo'] : false;
	$stickyImg = (!empty($attributes['stickyImg']['url'])) ? $attributes['stickyImg'] : '';
	$sImgSize = (!empty($attributes['sImgSize'])) ? $attributes['sImgSize'] : 'thumbnail' ;
	$stickySvg = (!empty($attributes['stickySvg']['url'])) ? $attributes['stickySvg'] : '';
	
	if(!empty($imageStore) && !empty($imageStore['id'])){
		$site_img = $imageStore['id'];
		$imgSrc = wp_get_attachment_image_src($site_img , $imageSize);
		$imgSrc = (!empty($imgSrc[0])) ? $imgSrc[0] : '';
	}else if(!empty($imageStore['url'])){
		$imgSrc = $imageStore['url'];
	}else{
		$imgSrc = $imageStore;
	}
	
	if(!empty($hvrImageStore) && !empty($hvrImageStore['id'])){
		$site_hImg = $hvrImageStore['id'];
		$hImgSrc = wp_get_attachment_image_src($site_hImg , $hvrImageSize);
		$hImgSrc = (!empty($hImgSrc[0])) ? $hImgSrc[0] : '';
	}else if(!empty($hvrImageStore['url'])){
		$hImgSrc = $hvrImageStore['url'];
	}else{
		$hImgSrc = $hvrImageStore;
	}
	
	if(!empty($stickyImg) && !empty($stickyImg['id'])){
		$site_sImg = $stickyImg['id'];
		$sImgSrc = wp_get_attachment_image_src($site_sImg , $sImgSize);
		$sImgSrc = (!empty($sImgSrc[0])) ? $sImgSrc[0] : '';
	}else if(!empty($stickyImg['url'])){
		$sImgSrc = $stickyImg['url'];
	}else{
		$sImgSrc = $stickyImg;
	}
	
	$normal_hover = $sticky_class ='';
	if($logoNmlDbl=='double' && !empty($hvrImageStore)){
		$normal_hover = 'logo-hover-normal';
	}
	if(!empty($stickyLogo)){
		$sticky_class = 'tp-sticky-logo-cls';
	}
	
	$url_link = $target = $nofollow = '';
	if($urlType=='home'){
		$url_link = get_home_url();
	}else if($urlType=='custom'){
		$url_link = (!empty($attributes['customURL']['url'])) ? $attributes['customURL']['url'] : '';
		$target = (!empty($attributes['customURL']['target'])) ? '_blank' : '';
		$nofollow = (!empty($attributes['customURL']['nofollow'])) ? 'nofollow' : '';
	}
	
	$output = '';
	$output .= '<div class="tpgb-site-logo tpgb-block-'.esc_attr($block_id).'">';
		$output .= '<div class="site-logo-wrap '.esc_attr($normal_hover).'">';
		if($logoType=='img'){
			if(!empty($imageStore)){
				$output .= '<a href="'.esc_url($url_link).'" target="'.esc_attr($target).'" rel="'.esc_attr($nofollow).'" class="site-normal-logo image-logo">';
					$output .= '<img src="'.esc_url($imgSrc).'" class="image-logo-wrap normal-image '.esc_attr($sticky_class).'"/>';
					if(!empty($stickyLogo)){
						$output .= '<img src="'.esc_url($sImgSrc).'" class="image-logo-wrap sticky-image"  alt="'.esc_attr__('Site Logo','tpgb').'"/>';
					}
				$output .= '</a>';
				if($logoNmlDbl=='double' && !empty($hvrImageStore)){
					$output .= '<a href="'.esc_url($url_link).'" target="'.esc_attr($target).'" rel="'.esc_attr($nofollow).'" class="site-normal-logo image-logo hover-logo">';
						$output .= '<img src="'.esc_url($hImgSrc).'" class="image-logo-wrap"  alt="'.esc_attr__('Site Logo','tpgb').'"/>';
					$output .= '</a>';
				}
			}
		}
		if($logoType=='svg'){
			if(!empty($svgStore)){
				$output .= '<a href="'.esc_url($url_link).'" target="'.esc_attr($target).'" rel="'.esc_attr($nofollow).'" class="site-normal-logo svg-logo">';
					$output .= '<img src="'.esc_url($svgStore).'" class="image-logo-wrap normal-image '.esc_attr($sticky_class).'"/>';
					if(!empty($stickyLogo)){
						$output .= '<img src="'.esc_url($stickySvg).'" class="image-logo-wrap sticky-image"/>';
					}
				$output .= '</a>';
				if($logoNmlDbl=='double' && !empty($hvrSvgStore)){
					$output .= '<a href="'.esc_url($url_link).'" target="'.esc_attr($target).'" rel="'.esc_attr($nofollow).'" class="site-normal-logo svg-logo hover-logo">';
						$output .= '<img src="'.esc_url($hvrSvgStore).'" class="image-logo-wrap"/>';
					$output .= '</a>';
				}
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
function tpgb_site_logo() {
	$globalBgOption = Tpgb_Blocks_Global_Options::load_bg_options();
    $globalpositioningOption = Tpgb_Blocks_Global_Options::load_positioning_options();
    $globalPlusExtrasOption = Tpgb_Blocks_Global_Options::load_plusextras_options();
  
	$attributesOptions = array(
		'block_id' => [
			'type' => 'string',
			'default' => '',
		],
		'logoNmlDbl' => [
			'type' => 'string',
			'default' => 'normal',	
		],
		'logoType' => [
			'type' => 'string',
			'default' => 'img',	
		],
		'imageStore' => [
			'type' => 'object',
			'default' => [
				'url' => TPGB_ASSETS_URL.'assets/images/tpgb-placeholder.jpg',
			],
		],
		'imageSize' => [
			'type' => 'string',
			'default' => 'thumbnail',	
		],
		'svgStore' => [
			'type' => 'object',
			'default' => [
				'url' => '',
			],
		],
		'logoWidth' => [
			'type' => 'object',
			'default' => (object) [ 
				'md' => '100',
				"unit" => 'px',
			],
			'style' => [
				(object) [
					'selector' => '{{PLUS_WRAP}} .site-normal-logo img.image-logo-wrap{ max-width: {{logoWidth}}; }',
				],
			],
		],
		
		'hvrImageStore' => [
			'type' => 'object',
			'default' => [
				'url' => TPGB_ASSETS_URL.'assets/images/tpgb-placeholder.jpg',
			],
		],
		'hvrImageSize' => [
			'type' => 'string',
			'default' => 'thumbnail',	
		],
		'hvrSvgStore' => [
			'type' => 'object',
			'default' => [
				'url' => '',
			],
		],
		'hvrLogoWidth' => [
			'type' => 'object',
			'default' => (object) [ 
				'md' => '100',
				"unit" => 'px',
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'logoNmlDbl', 'relation' => '==', 'value' => 'double' ]],
					'selector' => '{{PLUS_WRAP}} .site-normal-logo.hover-logo img.image-logo-wrap{ max-width: {{hvrLogoWidth}}; width: {{hvrLogoWidth}}; }',
				],
			],
		],
		'urlType' => [
			'type' => 'string',
			'default' => 'home',	
		],
		'customURL' => [
			'type'=> 'object',
			'default'=> [
				'url' => '#',
				'target' => '',
				'nofollow' => ''
			],
		],
		'Alignment' => [
			'type' => 'object',
			'default' => 'left',
			'style' => [
				(object) [
					'selector' => '{{PLUS_WRAP}} { text-align: {{Alignment}}; }',
				],
			],
		],
		'stickyLogo' => [
			'type' => 'boolean',
			'default' => false,	
		],
		'stickyImg' => [
			'type' => 'object',
			'default' => [
				'url' => TPGB_ASSETS_URL.'assets/images/tpgb-placeholder.jpg',
			],
		],
		'sImgSize' => [
			'type' => 'string',
			'default' => 'thumbnail',	
		],
		'stickySvg' => [
			'type' => 'object',
			'default' => [
				'url' => '',
			],
		],
		'stickyWidth' => [
			'type' => 'object',
			'default' => (object) [ 
				'md' => '',
				"unit" => 'px',
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'logoNmlDbl', 'relation' => '==', 'value' => 'normal' ] , ['key' => 'stickyLogo', 'relation' => '==', 'value' => true ]],
					'selector' => '{{PLUS_WRAP}} .site-normal-logo img.image-logo-wrap.sticky-image{ max-width: {{stickyWidth}}; }',
				],
			],
		],
	);
	$attributesOptions = array_merge($attributesOptions	, $globalBgOption, $globalpositioningOption, $globalPlusExtrasOption);
	
	register_block_type( 'tpgb/tp-site-logo', array(
		'attributes' => $attributesOptions,
		'editor_script' => 'tpgb-block-editor-js',
		'editor_style'  => 'tpgb-block-editor-css',
        'render_callback' => 'tpgb_tp_site_logo_render_callback'
    ) );
}
add_action( 'init', 'tpgb_site_logo' );