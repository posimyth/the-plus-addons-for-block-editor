<?php
/* Tp Block : Site Logo
 * @since	: 1.3.0
 */
defined( 'ABSPATH' ) || exit;

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
	$altText = (isset($imageStore['alt']) && !empty($imageStore['alt'])) ? esc_attr($imageStore['alt']) : ((!empty($imageStore['title'])) ? esc_attr($imageStore['title']) : esc_attr__('Site Logo','the-plus-addons-for-block-editor'));

	if(!empty($imageStore) && !empty($imageStore['id'])){
		$imgSrc = wp_get_attachment_image($imageStore['id'] , $imageSize, false, ['class' => 'image-logo-wrap tpgb-trans-ease normal-image '.esc_attr($sticky_class), 'alt'=> $altText ] );
		$imgSrc = (!empty($imgSrc)) ? $imgSrc : '<img src="'.esc_url($default_img).'" class="image-logo-wrap tpgb-trans-ease normal-image '.esc_attr($sticky_class).'" alt="'.$altText.'"/>';
	}else if(!empty($imageStore['url'])){
		$imgUrl = (isset($imageStore['dynamic']) && class_exists('Tpgbp_Pro_Blocks_Helper')) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_repeat_url($imageStore) : $imageStore['url'];
		$imgSrc = '<img src="'.esc_url($imgUrl).'" class="image-logo-wrap normal-image tpgb-trans-ease '.esc_attr($sticky_class).'" alt="'.$altText.'"/>';
	}
	
	$hImgSrc = '';
	$altText2 = (isset($hvrImageStore['alt']) && !empty($hvrImageStore['alt'])) ? esc_attr($hvrImageStore['alt']) : ((!empty($hvrImageStore['title'])) ? esc_attr($hvrImageStore['title']) : esc_attr__('Site Logo','the-plus-addons-for-block-editor'));

	if(!empty($hvrImageStore) && !empty($hvrImageStore['id'])){
		$hImgSrc = wp_get_attachment_image($hvrImageStore['id'] , $hvrImageSize, false, ['class' => 'image-logo-wrap tpgb-trans-ease', 'alt'=> $altText2 ] );
		$hImgSrc = (!empty($hImgSrc)) ? $hImgSrc : '<img src="'.esc_url($default_img).'" class="image-logo-wrap tpgb-trans-ease"  alt="'.$altText2.'"/>';
	}else if(!empty($hvrImageStore['url'])){
		$hvrimgUrl = (isset($hvrImageStore['dynamic']) && class_exists('Tpgbp_Pro_Blocks_Helper')) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_repeat_url($hvrImageStore) : $hvrImageStore['url'];
		$hImgSrc = '<img src="'.esc_url($hvrimgUrl).'" class="image-logo-wrap tpgb-trans-ease"  alt="'.$altText2.'"/>';
	}
	
	$sImgSrc = '';
	$altText3 = (isset($stickyImg['alt']) && !empty($stickyImg['alt'])) ? esc_attr($stickyImg['alt']) : ((!empty($stickyImg['title'])) ? esc_attr($stickyImg['title']) : esc_attr__('Site Logo','the-plus-addons-for-block-editor'));

	if(!empty($stickyImg) && !empty($stickyImg['id'])){
		$site_sImg = $stickyImg['id'];
		$sImgSrc = wp_get_attachment_image($site_sImg , $sImgSize, false, ['class' => 'image-logo-wrap tpgb-trans-ease sticky-image', 'alt'=> $altText3 ] );
		$sImgSrc = (!empty($sImgSrc)) ? $sImgSrc : '<img src="'.esc_url($default_img).'" class="image-logo-wrap tpgb-trans-ease sticky-image"  alt="'.$altText3.'"/>';
	}else if(!empty($stickyImg['url'])){
		$stImgSrc = (isset($stickyImg['dynamic']) && class_exists('Tpgbp_Pro_Blocks_Helper')) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_repeat_url($stickyImg) : $stickyImg['url'];
		$sImgSrc = '<img src="'.esc_url($stImgSrc).'" class="image-logo-wrap tpgb-trans-ease sticky-image"  alt="'.$altText3.'"/>';
	}
	
	$url_link = $target = $nofollow = $link_attr = '';
	if($urlType=='home'){
		$url_link = get_home_url();
	}else if($urlType=='custom'){
		
        // Set Dynamic URL For Custom Link
        if(class_exists('Tpgbp_Pro_Blocks_Helper')){
            $url_link = (isset($attributes['customURL']['dynamic'])) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_repeat_url($attributes['customURL']) : (!empty($attributes['customURL']['url']) ? $attributes['customURL']['url'] : '');
        }else{
            $url_link = (!empty($attributes['customURL']['url'])) ? $attributes['customURL']['url'] : '';
        }

		$target = (!empty($attributes['customURL']['target'])) ? ' target="_blank"' : '';
		$nofollow = (!empty($attributes['customURL']['nofollow'])) ? 'rel="nofollow"' : '';
		$link_attr = Tp_Blocks_Helper::add_link_attributes($attributes['customURL']);
	}
	$ariaLabel = (!empty($attributes['ariaLabel'])) ? esc_attr($attributes['ariaLabel']) : esc_attr__("Site Logo", 'the-plus-addons-for-block-editor');
	$output = '';
	$output .= '<div class="tpgb-site-logo tpgb-relative-block tpgb-trans-linear tpgb-block-'.esc_attr($block_id).' '.esc_attr($blockClass).'">';
		$output .= '<div class="site-logo-wrap tpgb-trans-ease '.esc_attr($normal_hover).'">';
		if($logoType=='img'){
			if(!empty($imageStore)){
				
				$output .= '<a href="'.esc_url($url_link).'" '.$target.' '.$nofollow.' class="site-normal-logo image-logo" '.$link_attr.' aria-label="'.$ariaLabel.'">';
					$output .= $imgSrc;
					if(!empty($stickyLogo)){
						$output .= $sImgSrc;
					}
				$output .= '</a>';
				if($logoNmlDbl=='double' && !empty($hvrImageStore)){
					$output .= '<a href="'.esc_url($url_link).'" '.$target.' '.$nofollow.' class="site-normal-logo image-logo hover-logo" '.$link_attr.' aria-label="'.$ariaLabel.'">';
						$output .= $hImgSrc;
					$output .= '</a>';
				}
			}
		}
		if($logoType=='svg'){
			if(!empty($svgStore)){
				$output .= '<a href="'.esc_url($url_link).'" '.$target.' '.$nofollow.' class="site-normal-logo svg-logo" '.$link_attr.' aria-label="'.$ariaLabel.'">';
					$svgUrl = (isset($svgStore['dynamic']) && class_exists('Tpgbp_Pro_Blocks_Helper')) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_repeat_url($svgStore) : $svgStore['url'];
					$altText4 = (isset($svgStore['alt']) && !empty($svgStore['alt'])) ? esc_attr($svgStore['alt']) : ((!empty($svgStore['title'])) ? esc_attr($svgStore['title']) : esc_attr__('Site Logo','the-plus-addons-for-block-editor'));

					$output .= '<img src="'.esc_url($svgUrl).'" class="image-logo-wrap normal-image '.esc_attr($sticky_class).'" alt="'.$altText4.'"/>';
					if(!empty($stickyLogo) && !empty($stickySvg['url'])){
						$stsvgUrl = (isset($stickySvg['dynamic']) && class_exists('Tpgbp_Pro_Blocks_Helper')) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_repeat_url($stickySvg) : $stickySvg['url'];
						$altText5 = (isset($stickySvg['alt']) && !empty($stickySvg['alt'])) ? esc_attr($stickySvg['alt']) : ((!empty($stickySvg['title'])) ? esc_attr($stickySvg['title']) : esc_attr__('Site Logo','the-plus-addons-for-block-editor'));
						$output .= '<img src="'.esc_url($stsvgUrl).'" class="image-logo-wrap tpgb-trans-ease sticky-image" alt="'.$altText5.'"/>';
					}
				$output .= '</a>';
				if($logoNmlDbl=='double' && !empty($hvrSvgStore)){
					$hvrsvgUrl = (isset($hvrSvgStore['dynamic']) && class_exists('Tpgbp_Pro_Blocks_Helper')) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_repeat_url($hvrSvgStore) : $hvrSvgStore['url'];
					$altText6 = (isset($hvrSvgStore['alt']) && !empty($hvrSvgStore['alt'])) ? esc_attr($hvrSvgStore['alt']) : ((!empty($hvrSvgStore['title'])) ? esc_attr($hvrSvgStore['title']) : esc_attr__('Site Logo','the-plus-addons-for-block-editor'));
					$output .= '<a href="'.esc_url($url_link).'"  '.$target.' '.$nofollow.'  class="site-normal-logo svg-logo hover-logo" '.$link_attr.' aria-label="'.$ariaLabel.'">';
						$output .= '<img src="'.esc_url($hvrsvgUrl).'" class="image-logo-wrap tpgb-trans-ease" alt="'.$altText6.'"/>';
					$output .= '</a>';
				}
			}
		}
		$output .= '</div>';
	$output .= '</div>';
	
	$output = Tpgb_Blocks_Global_Options::block_Wrap_Render($attributes, $output);
	if (!empty($markupSch)) {
		$output .= '<script type="application/ld+json">';
		$output .= '{';
		$output .= '"@context": "https://schema.org",';
		$output .= '"@type": "Organization",';
		$output .= '"url": "' . esc_url($url_link) . '"';
		if (isset($imgUrl)) {
			$output .= ', "logo": "' . esc_url($imgUrl) . '"';
		}
		$output .= '}';
		$output .= '</script>';
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
			'default' => [ 'md' => 'left', 'sm' => '', 'xs' => '' ],
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
		'ariaLabel' => [
			'type' => 'string',
			'default' => '',	
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