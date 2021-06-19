<?php
/* Block : Breadcrumbs
 * @since : 1.0.0
 */
function tpgb_breadcrumbs_callback( $attributes, $content) {
	$output = '';
    $uid = (!empty($attributes['block_id'])) ? $attributes['block_id'] : uniqid("title");
    $style = (!empty($attributes['style'])) ? $attributes['style'] : '';
    
	$blockClass = Tp_Blocks_Helper::block_wrapper_classes( $attributes );
	
    $icons = $icontype = '';
    if($attributes['homeIcon'] == "icon") {
        if(!empty($attributes["iconFontStyle"]) && $attributes["iconFontStyle"] == 'font_awesome') {
            $icons = (!empty($attributes["iconFawesome"])) ? $attributes["iconFawesome"] : '';
            $icontype = 'icon';
        } else if(!empty($attributes["iconFontStyle"]) && $attributes["iconFontStyle"] == 'icon_image') {
            $iconsImg = $attributes['iconsImg']['id'];
            $img = wp_get_attachment_image_src($iconsImg);
            $icons = $img[0];
            $icontype = 'image';
        }
    }
    
    $sepIcons = $sepIconType = '';
    if($attributes['sepIcon']=="sep_icon") {
        if(!empty($attributes["sepIconFontStyle"]) && $attributes["sepIconFontStyle"]=='sep_font_awesome') {
            $sepIcons= (!empty($attributes["sepIconFawesome"])) ? $attributes["sepIconFawesome"] : '';
            $sepIconType='sep_icon';
        } else if(!empty($attributes["sepIconFontStyle"]) && $attributes["sepIconFontStyle"]=='sep_icon_image') {
            $sepIconImg = $attributes['sepIconImg']['id'];
            $img = wp_get_attachment_image_src($sepIconImg);
            $sepIcons = $img[0];
            $sepIconType = 'sep_image';
        }
    }
    
    $cssClass = '';
    if($style == 'style-1') {
        $bredStyleClass = 'bred_style_1';
    } else if($style == 'style-2') {
        $bredStyleClass = 'bred_style_2';
    }
	
    $cssClass = (!empty($attributes["bredAlign"]['md'])) ? ' bred-' . esc_attr($attributes["bredAlign"]['md']) : '';
    $cssClass .= (!empty($attributes["bredAlign"]['sm'])) ? ' bred-tablet-' . esc_attr($attributes["bredAlign"]['sm']) : '';
    $cssClass .= (!empty($attributes["bredAlign"]['xs'])) ? ' bred-mobile-' . esc_attr($attributes["bredAlign"]['xs']) : '';

    $homeTitle = $attributes["homeTitle"];
    
    $bdToggleHome = (!empty($attributes['bdToggleHome'])) ? "on-off-home" : "";
    $bdToggleParent = (!empty($attributes['bdToggleParent'])) ? "on-off-parent" : "";	

    if((!empty($attributes['letterLimitParentT']))){
    	$letterLimitParent = (!empty($attributes['letterLimitParent'])) ? $attributes['letterLimitParent'] : '';
	}else{
		$letterLimitParent ='0';
	}
	if((!empty($attributes['letterLimitCurrentT']))){
    	$letterLimitCurrent = (!empty($attributes['letterLimitCurrent'])) ? $attributes['letterLimitCurrent'] : '';
	}else{
		$letterLimitCurrent = '0';
	}
    
    $bdToggleCurrent = (!empty($attributes['bdToggleCurrent'])) ? "on-off-current" : "";
    
    $breadcrumbs_last_sec_tri_normal = '';
    $breadcrumbs_bar = '';	
    
    $breadcrumbs_bar .= '<div class="tp-breadcrumbs tpgb-block-'.esc_attr($uid).' '.esc_attr($blockClass).'">';
    $breadcrumbs_bar .= '<div class="pt_plus_breadcrumbs_bar '.  trim( $cssClass ) .'">';
    
    if(!empty($attributes['bredWidth']) && $style == 'style-1') {
        $breadcrumbs_bar .= '<div class="pt_plus_breadcrumbs_bar_inner '.esc_attr($bredStyleClass).'" style="width:100%">';
    } else {
        $breadcrumbs_bar .= '<div class="pt_plus_breadcrumbs_bar_inner '.esc_attr($bredStyleClass).'">';
    }
    
    $activeColorCurrent = ($attributes['activeColorCurrent'] == true) ? "default_active" : "";

    $breadcrumbs_bar .= Tp_Blocks_Helper::theplus_breadcrumbs($icontype,$sepIconType,$icons,$homeTitle,$sepIcons,$activeColorCurrent,$breadcrumbs_last_sec_tri_normal,$bdToggleHome,$bdToggleParent,$bdToggleCurrent,$letterLimitParent,$letterLimitCurrent);
    $breadcrumbs_bar .= '</div>';
    $breadcrumbs_bar .= '</div></div>';
    
	$breadcrumbs_bar = Tpgb_Blocks_Global_Options::block_Wrap_Render($attributes, $breadcrumbs_bar);
	
	return $breadcrumbs_bar;
}

function tpgb_tp_breadcrumbs_render() {
    $globalBgOption = Tpgb_Blocks_Global_Options::load_bg_options();
    $globalpositioningOption = Tpgb_Blocks_Global_Options::load_positioning_options();
	$globalPlusExtrasOption = Tpgb_Blocks_Global_Options::load_plusextras_options();
    $attributesOptions = [
        'block_id' => [
            'type' => 'string',
            'default' => '',
        ],
        'style' => [
            'type' => 'string',
            'default' => 'style-1',
        ],
        'bredWidth' => [
            'type' => 'boolean',
            'default' => false,
        ],
        'bredAlign' => [
            'type' => 'object',
            'default' => [ 'md' => '', 'sm' =>  '', 'xs' =>  '' ],
            'style' => [
                (object) [
                    'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-1']],
                    'selector' => '{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar{ text-align: {{bredAlign}}; }',
                ]
            ],
        ],
        'homeTitle' => [
            'type' => 'string',
            'default' => 'Home',
        ],
        'homeIcon' => [
            'type' => 'string',
            'default' => 'icon',
        ],
        'iconFontStyle' => [
            'type' => 'string',
            'default' => 'font_awesome',
        ],
        'iconFawesome' => [
            'type' => 'string',
            'default' => 'fas fa-home',
        ],
        'iconsImg' => [
            'type' => 'object',
            'default' => [
                'url' => '',
                'Id' => '',
            ],
        ],
        'sepIcon' => [
            'type' => 'string',
            'default' => 'sep_icon',
        ],
        'sepIconFontStyle' => [
            'type' => 'string',
            'default' => 'sep_font_awesome',
        ],
        'sepIconFawesome' => [
            'type' => 'string',
            'default' => 'fas fa-angle-right',
        ],
        'sepIconImg' => [
            'type' => 'object',
            'default' => [
                'url' => '',
                'Id' => '',
            ],
                      
        ],
        'bdToggleHome' => [
            'type' => 'boolean',
            'default' => true,	
        ],
        'bdToggleParent' => [
            'type' => 'boolean',
            'default' => true,	
        ],
        'bdToggleCurrent' => [
            'type' => 'boolean',
            'default' => true,	
        ],
        'bredMargin' => [
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
                    'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-2']],
                    'selector' => '{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner.bred_style_2 nav#breadcrumbs a,{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner.bred_style_2 nav#breadcrumbs .current .current_tab_sec,{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner.bred_style_2 nav#breadcrumbs .current_active .current_tab_sec{ padding: {{bredMargin}}; }',
                ],
            ],
        ],
        'bredPadding' => [
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
                    'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-2']],
                    'selector' => '{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner.bred_style_2 nav#breadcrumbs a,{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner.bred_style_2 nav#breadcrumbs .current .current_tab_sec,{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner.bred_style_2 nav#breadcrumbs .current_active .current_tab_sec{ padding: {{bredPadding}}; }',
                ],
            ],
        ],
        'bredTypo' => [
            'type'=> 'object',
            'default'=> (object) [
                'openTypography' => 0,
                'size' => [ 'md' => '', 'unit' => 'px' ],
            ],
            'style' => [
                (object) [
                    'selector' => '{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner.bred_style_1 nav#breadcrumbs a,{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner.bred_style_1 nav#breadcrumbs span.current,{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner.bred_style_1 nav#breadcrumbs .current_active,
                    {{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner.bred_style_2 nav#breadcrumbs a,{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner.bred_style_2 nav#breadcrumbs span.current .current_tab_sec,{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner.bred_style_2 nav#breadcrumbs .current_active .current_tab_sec',
                ],
            ],
        ],
        'textColor' => [
            'type' => 'string',
            'default' => '',
            'style' => [
                (object) [
                    'selector' => '{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner.bred_style_1 nav#breadcrumbs a,{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner.bred_style_1 nav#breadcrumbs span.current .current_tab_sec,{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner.bred_style_1 nav#breadcrumbs span.current_active .current_tab_sec,
                    {{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner.bred_style_2 nav#breadcrumbs a,{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner.bred_style_2 nav#breadcrumbs span.current .current_tab_sec,{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner.bred_style_2 nav#breadcrumbs span.current_active .current_tab_sec{ color: {{textColor}}; }',
                ],
            ],
        ],
        'textHColor' => [
            'type' => 'string',
            'default' => '',
            'style' => [
                (object) [
                    'selector' => '{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner.bred_style_1 nav#breadcrumbs a:hover,{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner.bred_style_1 nav#breadcrumbs span.current:hover .current_tab_sec,{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner.bred_style_1 nav#breadcrumbs span.current_active .current_tab_sec,
                    {{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner.bred_style_2 nav#breadcrumbs a:hover,{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner.bred_style_2 nav#breadcrumbs span.current:hover .current_tab_sec,{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner.bred_style_2 nav#breadcrumbs span.current_active .current_tab_sec{ color: {{textHColor}}; }',
                ],
            ],
        ],
        'activeColorCurrent' => [
            'type' => 'boolean',
	        'default' => false,
        ],
        'textBorder' => [
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
                    'selector' => '{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner.bred_style_1 nav#breadcrumbs a,{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner.bred_style_1 nav#breadcrumbs .current_tab_sec,
                    {{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner.bred_style_2 nav#breadcrumbs a,{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner.bred_style_2 nav#breadcrumbs .current_tab_sec',
                ],
            ],
        ],
        'textBorderHover' => [
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
                    'selector' => '{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner.bred_style_1 nav#breadcrumbs a:hover,{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner.bred_style_1 nav#breadcrumbs span.current:hover .current_tab_sec,{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner.bred_style_1 nav#breadcrumbs span.current_active:hover .current_tab_sec,
                    {{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner.bred_style_2 nav#breadcrumbs a:hover,{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner.bred_style_2 nav#breadcrumbs span.current:hover .current_tab_sec,{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner.bred_style_2 nav#breadcrumbs span.current_active:hover .current_tab_sec',
                ],
            ],
        ],
        'iconPadding' => [
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
                    'selector' => '{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner.bred_style_1 nav#breadcrumbs .bread-home-icon,{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner.bred_style_2 nav#breadcrumbs .bread-home-icon,{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner nav#breadcrumbs img.bread-home-img{ padding: {{iconPadding}}; }',
                ],
            ],
        ],
        'iconSize' => [
            'type' => 'object',
            'default' => [ 
                'md' => '',
                "unit" => 'px',
            ],
            'style' => [
                (object) [
                    'condition' => [(object) ['key' => 'iconFontStyle', 'relation' => '==', 'value' => 'font_awesome']],
                    'selector' => '{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner.bred_style_1 nav#breadcrumbs .bread-home-icon,{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner.bred_style_2 nav#breadcrumbs .bread-home-icon{ font-size: {{iconSize}}; }',
                ],
            ],
        ],
        'iconColor' => [
            'type' => 'string',
            'default' => '',
            'style' => [
                (object) [
                    'condition' => [(object) ['key' => 'iconFontStyle', 'relation' => '==', 'value' => 'font_awesome']],
                    'selector' => '{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner.bred_style_1 nav#breadcrumbs .bread-home-icon,{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner.bred_style_2 nav#breadcrumbs .bread-home-icon{ color: {{iconColor}}; -webkit-transition: all .4s ease; -moz-transition: all .4s ease; -o-transition: all .4s ease; -ms-transition: all .4s ease; transition: all .4s ease }',
                ],
            ],
        ],
        'iconColorHover' => [
            'type' => 'string',
            'default' => '',
            'style' => [
                (object) [
                    'condition' => [(object) ['key' => 'iconFontStyle', 'relation' => '==', 'value' => 'font_awesome']],
                    'selector' => '{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner.bred_style_1 nav#breadcrumbs a:hover .bread-home-icon,{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner.bred_style_2 nav#breadcrumbs a:hover .bread-home-icon{ color: {{iconColorHover}}; }',
                ],
            ],
        ],
        'imgSize' => [
            'type' => 'object',
            'default' => [ 
                'md' => '',
                "unit" => 'px',
            ],
            'style' => [
                (object) [
                    'condition' => [(object) ['key' => 'iconFontStyle', 'relation' => '==', 'value' => 'icon_image']],
                    'selector' => '{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner nav#breadcrumbs img.bread-home-img{ max-width: {{imgSize}};height: auto; }',
                ],
            ],
        ],
        'imgBorderRadius' => [
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
                    'condition' => [(object) ['key' => 'iconFontStyle', 'relation' => '==', 'value' => 'icon_image']],
                    'selector' => '{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner nav#breadcrumbs img.bread-home-img{ border-radius: {{imgBorderRadius}}; }',
                ],
            ],
        ],
        'sepPadding' =>[
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
                    'selector' => '{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner nav#breadcrumbs .bread-sep-icon,{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner nav#breadcrumbs img.bread-sep-icon{ padding: {{sepPadding}}; }',
                ],
            ],
        ],
        'sepSize' => [
            'type' => 'object',
            'default' => [ 
                'md' => '',
                "unit" => 'px',
            ],
            'style' => [
                (object) [
                    'condition' => [(object) ['key' => 'sepIconFontStyle', 'relation' => '==', 'value' => 'sep_font_awesome']],
                    'selector' => '{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner.bred_style_1 nav#breadcrumbs .bread-sep-icon,{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner.bred_style_2 nav#breadcrumbs .bread-sep-icon{ font-size: {{sepSize}}; }',
                ],
            ],
        ],
        'sepColor' => [
            'type' => 'string',
            'default' => '',
            'style' => [
                (object) [
                    'condition' => [(object) ['key' => 'sepIconFontStyle', 'relation' => '==', 'value' => 'sep_font_awesome']],
                    'selector' => '{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner.bred_style_1 nav#breadcrumbs .bread-sep-icon,{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner.bred_style_2 nav#breadcrumbs .bread-sep-icon{ color: {{sepColor}}; -webkit-transition: all .4s ease; -moz-transition: all .4s ease; -o-transition: all .4s ease; -ms-transition: all .4s ease; transition: all .4s ease }',
                ],
            ],
        ],
        'sepColorHover' => [
            'type' => 'string',
            'default' => '',
            'style' => [
                (object) [
                    'condition' => [(object) ['key' => 'sepIconFontStyle', 'relation' => '==', 'value' => 'sep_font_awesome']],
                    'selector' => '{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner.bred_style_1 nav#breadcrumbs a:hover .bread-sep-icon,{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner.bred_style_2 nav#breadcrumbs a:hover .bread-sep-icon{ color: {{sepColorHover}}; }',
                ],
            ],
        ],
        'sepImgSize' => [
            'type' => 'object',
            'default' => [ 
                'md' => '',
                "unit" => 'px',
            ],
            'style' => [
                (object) [
                    'condition' => [(object) ['key' => 'sepIconFontStyle', 'relation' => '==', 'value' => 'sep_icon_image']],
                    'selector' => '{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner nav#breadcrumbs img.bread-sep-icon{ max-width: {{sepImgSize}};height: auto; }',
                ],
            ],
        ],
        'letterLimitParentT' => [
            'type' => 'boolean',
            'default' => true,
        ],
        'letterLimitParent' => [
            'type' => 'string',
            'default' => 20,	
        ],
        'letterLimitCurrentT' => [
            'type' => 'boolean',
            'default' => true,	
        ],
        'letterLimitCurrent' => [
            'type' => 'string',
            'default' => 20,	
        ],
        'contentBgPadding' => [
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
                    'selector' => '{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner.bred_style_1{ padding: {{contentBgPadding}}; }',
                ],
            ],
        ],
        'contentBg' => [
            'type' => 'object',
            'default' => (object) [
                'openBg'=> 0,
                'bgType' => 'color',
                'videoSource' => 'local',
                'bgDefaultColor' => '',
                'bgGradient' => (object) [ 'color1' => '#16d03e', 'color2' => '#1f91f3', 'type' => 'linear', 'direction' => '90', 'start' => 5, 'stop' => 80, 'radial' => 'center', 'clip' => false ],
            ],
            'style' => [
                (object) [
                    'selector' => '{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner.bred_style_1',
                ],
            ],
        ],
        'contentBorder' => [
            'type' => 'object',
            'default' => (object) [
                'openBorder' => 0,
            ],
            'style' => [
                (object) [
                    'selector' => '{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner.bred_style_1',
                ],
            ],
        ],
        'contentBorderRad' => [
            'type' => 'object',
            'default' => (object) [ ],
            'style' => [
                (object) [
                    'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-1']],
                    'selector' => '{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar_inner.bred_style_1{ border-radius: {{contentBorderRad}}; }',
                ],
            ],
        ],  
        'contentBorderRadH' => [
            'type' => 'object',
            'default' => (object) [ ],
            'style' => [
                (object) [
                    'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-1']],
                    'selector' => '{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar_inner.bred_style_1:hover{ border-radius: {{contentBorderRadH}}; }',
                ],
            ],
        ],
        'boxShadow' => [
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
                    'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-1']],
                    'selector' => '{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner.bred_style_1',
                ],
            ],
        ],
        'boxShadowH' => [
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
                    'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-1']],
                    'selector' => '{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner:hover.bred_style_1',
                ],
            ],
        ],
        'sepBgPadding' => [
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
                    'selector' => '{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner.bred_style_1 nav#breadcrumbs a,{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner.bred_style_1 nav#breadcrumbs .current_tab_sec{ padding: {{sepBgPadding}}; }',
                ],
            ],
        ],
        'sepBgMargin' => [
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
                    'selector' => '{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner.bred_style_1 nav#breadcrumbs a,{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner.bred_style_1 nav#breadcrumbs .current_tab_sec,{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar_inner.bred_style_2 #breadcrumbs>span:not(.del){ margin: {{sepBgMargin}}; }',
                ],
            ],
        ],
        'sepBorderRadius' => [
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
                    'selector' => '{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner.bred_style_1 nav#breadcrumbs a,{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar .pt_plus_breadcrumbs_bar_inner.bred_style_1 nav#breadcrumbs .current_tab_sec{ border-radius: {{sepBorderRadius}}; }',
                ],
            ],
        ],
        'bredAll' => [
            'type' => 'string',
            'default' => '',
            'style' => [
                (object) [
                    'selector' => '{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar_inner #breadcrumbs > span:not(.del) a,{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar_inner #breadcrumbs > span:not(.del) .current_tab_sec{ background: {{bredAll}} !important }{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar_inner.bred_style_2 #breadcrumbs > span:not(.del):before{ border-left: 30px solid {{bredAll}} }',
                ],
            ],
        ],
        'bredHome' => [
            'type' => 'string',
            'default' => '',
            'style' => [
                (object) [
                    'selector' => '{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar_inner #breadcrumbs > span.bc_home .home_bread_tab{ background: {{bredHome}} !important }{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar_inner.bred_style_2 #breadcrumbs > span.bc_home:before{ border-left: 30px solid {{bredHome}} }',
                ],
            ],
        ],
        'bredCurrent' => [
            'type' => 'string',
            'default' => '',
            'style' => [
                (object) [                        
                    'selector' => '{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar_inner #breadcrumbs > span:not(.del) .current_tab_sec{ background: {{bredCurrent}} !important; }{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar_inner.bred_style_2 #breadcrumbs > span.current:before,{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar_inner.bred_style_2 #breadcrumbs > span.current_active:before{ border-left: 30px solid {{bredCurrent}} }',
                ],
            ],
        ],
        'bredAllHover' => [
            'type' => 'string',
            'default' => '',
            'style' => [
                (object) [
                    'selector' => '{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar_inner #breadcrumbs > span:not(.del):hover a,{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar_inner #breadcrumbs > span:not(.del):hover .current_tab_sec{ background: {{bredAllHover}} !important }{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar_inner.bred_style_2 #breadcrumbs > span:not(.del):hover:before{ border-left: 30px solid {{bredAllHover}} }',
                ],
            ],
        ],
        'bredHomeHover' => [
            'type' => 'string',
            'default' => '',
            'style' => [
                (object) [
                    'selector' => '{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar_inner #breadcrumbs > span.bc_home:hover a{ background: {{bredHomeHover}} !important }{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar_inner.bred_style_2 #breadcrumbs > span.bc_home:hover:before { border-left: 30px solid {{bredHomeHover}} }',
                ],
            ],
        ],
        'bredCurrentHover' => [
            'type' => 'string',
            'default' => '',
            'style' => [
                (object) [
                    'selector' => '{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar_inner #breadcrumbs > span.current:hover .current_tab_sec,{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar_inner #breadcrumbs > span.current_active:hover .current_tab_sec{ background: {{bredCurrentHover}} !important }{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar_inner.bred_style_2 #breadcrumbs > span.current:hover:before,{{PLUS_WRAP}} .pt_plus_breadcrumbs_bar_inner.bred_style_2 #breadcrumbs > span.current_active:hover:before{ border-left: 30px solid {{bredCurrentHover}} }',
                ],
            ],
        ],
    ];

    $attributesOptions = array_merge($attributesOptions	, $globalBgOption, $globalpositioningOption, $globalPlusExtrasOption);

    register_block_type( 'tpgb/tp-breadcrumbs', array(
		'attributes' => $attributesOptions,
		'editor_script' => 'tpgb-block-editor-js',
		'editor_style'  => 'tpgb-block-editor-css',
        'render_callback' => 'tpgb_breadcrumbs_callback'
    ));
}
add_action( 'init', 'tpgb_tp_breadcrumbs_render' );