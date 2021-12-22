<?php
/* Tp Block : Site Logo
 * @since	: 1.2.1
 */
defined( 'ABSPATH' ) || exit;

function tpgb_tp_site_logo_render_callback( $attributes, $content) {
    $block_id = (!empty($attributes['block_id'])) ? $attributes['block_id'] : uniqid("title");
	$logoNmlDbl = (!empty($attributes['logoNmlDbl'])) ? $attributes['logoNmlDbl'] : 'normal';
	$logoType = (!empty($attributes['logoType'])) ? $attributes['logoType'] : 'img';
	$imageStore = (!empty($attributes['imageStore']['url'])) ? $attributes['imageStore'] : '';
	$imageSize = (!empty($attributes['imageSize'])) ? $attributes['imageSize'] : 'thumbnail' ;
	$svgStore = (!empty($attributes['svgStore']['url'])) ? $attributes['svgStore']['url'] : '';
	
	$hvrImageStore = (!empty($attributes['hvrImageStore']['url'])) ? $attributes['hvrImageStore'] : '';
	$hvrImageSize = (!empty($attributes['hvrImageSize'])) ? $attributes['hvrImageSize'] : 'thumbnail' ;
	$hvrSvgStore = (!empty($attributes['hvrSvgStore']['url'])) ? $attributes['hvrSvgStore']['url'] : '';
	
	$urlType = (!empty($attributes['urlType'])) ? $attributes['urlType'] : 'home';
	
	$stickyLogo = (!empty($attributes['stickyLogo'])) ? $attributes['stickyLogo'] : false;
	$stickyImg = (!empty($attributes['stickyImg']['url'])) ? $attributes['stickyImg'] : '';
	$sImgSize = (!empty($attributes['sImgSize'])) ? $attributes['sImgSize'] : 'thumbnail' ;
	$stickySvg = (!empty($attributes['stickySvg']['url'])) ? $attributes['stickySvg'] : '';
	$markupSch = (!empty($attributes['markupSch'])) ? $attributes['markupSch'] : false;
	
	$blockClass = Tp_Blocks_Helper::block_wrapper_classes( $attributes );
	
	$normal_hover = $sticky_class ='';
	if($logoNmlDbl=='double' && !empty($hvrImageStore)){
		$normal_hover = 'logo-hover-normal';
	}
	if(!empty($stickyLogo)){
		$sticky_class = 'tp-sticky-logo-cls';
	}
	
	$default_img = TPGB_ASSETS_URL. 'assets/images/tpgb-placeholder.jpg';
	
	$imgSrc ='';
	if(!empty($imageStore) && !empty($imageStore['id'])){
		$imgSrc = wp_get_attachment_image($imageStore['id'] , $imageSize, false, ['class' => 'image-logo-wrap normal-image '.esc_attr($sticky_class) ] );
		$imgSrc = (!empty($imgSrc)) ? $imgSrc : '<img src="'.esc_url($default_img).'" class="image-logo-wrap normal-image '.esc_attr($sticky_class).'"/>';
	}else if(!empty($imageStore['url'])){
		$imgSrc = '<img src="'.esc_url($imageStore['url']).'" class="image-logo-wrap normal-image '.esc_attr($sticky_class).'"/>';
	}
	
	$hImgSrc = '';
	if(!empty($hvrImageStore) && !empty($hvrImageStore['id'])){
		$hImgSrc = wp_get_attachment_image($hvrImageStore['id'] , $hvrImageSize, false, ['class' => 'image-logo-wrap' ] );
		$hImgSrc = (!empty($hImgSrc)) ? $hImgSrc : '<img src="'.esc_url($default_img).'" class="image-logo-wrap"  alt="'.esc_attr__('Site Logo','tpgb').'"/>';
	}else if(!empty($hvrImageStore['url'])){
		$hImgSrc = '<img src="'.esc_url($hvrImageStore['url']).'" class="image-logo-wrap"  alt="'.esc_attr__('Site Logo','tpgb').'"/>';
	}
	
	$sImgSrc = '';
	if(!empty($stickyImg) && !empty($stickyImg['id'])){
		$site_sImg = $stickyImg['id'];
		$sImgSrc = wp_get_attachment_image($site_sImg , $sImgSize, false, ['class' => 'image-logo-wrap sticky-image' ] );
		$sImgSrc = (!empty($sImgSrc)) ? $sImgSrc : '<img src="'.esc_url($default_img).'" class="image-logo-wrap sticky-image"  alt="'.esc_attr__('Site Logo','tpgb').'"/>';
	}else if(!empty($stickyImg['url'])){
		$sImgSrc = $stickyImg['url'];
		$sImgSrc = '<img src="'.esc_url($sImgSrc).'" class="image-logo-wrap sticky-image"  alt="'.esc_attr__('Site Logo','tpgb').'"/>';
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
	$output .= '<div class="tpgb-site-logo tpgb-block-'.esc_attr($block_id).' '.esc_attr($blockClass).'">';
		$output .= '<div class="site-logo-wrap '.esc_attr($normal_hover).'">';
		if($logoType=='img'){
			if(!empty($imageStore)){
				$output .= '<a href="'.esc_url($url_link).'" target="'.esc_attr($target).'" rel="'.esc_attr($nofollow).'" class="site-normal-logo image-logo">';
					$output .= $imgSrc;
					if(!empty($stickyLogo)){
						$output .= $sImgSrc;
					}
				$output .= '</a>';
				if($logoNmlDbl=='double' && !empty($hvrImageStore)){
					$output .= '<a href="'.esc_url($url_link).'" target="'.esc_attr($target).'" rel="'.esc_attr($nofollow).'" class="site-normal-logo image-logo hover-logo">';
						$output .= $hImgSrc;
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
	if(!empty($markupSch)){
		$output .= '<script type="application/ld+json">';
			$output .= '@context: https://schema.org,';
			$output .= '@type: Organization,';
			$output .= 'url:'.esc_url($url_link).',';
			$output .= isset($imageStore['url']) ? 'logo: '.esc_url($imageStore['url']) : '';
		$output .= ' </script>';
	}
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
			'scopy' => true,
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
			'scopy' => true,
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
			'scopy' => true,
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
			'scopy' => true,
		],
		'logoSpeed' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'logoNmlDbl', 'relation' => '==', 'value' => 'double' ] ],
					'selector' => '{{PLUS_WRAP}} .site-normal-logo,{{PLUS_WRAP}} .site-normal-logo.hover-logo,{{PLUS_WRAP}} .site-logo-wrap.logo-hover-normal:hover .site-normal-logo.hover-logo{ transition-duration : {{logoSpeed}}s; }',
				],
			],
			'scopy' => true,
		],
		'markupSch' => [
			'type' => 'boolean',
			'default' => false,	
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