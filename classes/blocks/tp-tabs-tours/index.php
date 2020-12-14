<?php
/**
 * After rendring from the block editor display output on front-end
 */
function tpgb_tp_tabs_tours_render_callback( $attributes, $content) {
	
	$block_id = (!empty($attributes['block_id'])) ? $attributes['block_id'] :'';
	$tabLayout =  (!empty($attributes['tabLayout'])) ? $attributes['tabLayout'] :'horizontal';
	$navAlign =  (!empty($attributes['navAlign'])) ? $attributes['navAlign'] :'text-left';
	$fullwidthIcon = (!empty($attributes['fullwidthIcon'])) ? $attributes['fullwidthIcon'] :false;
	$navWidth =  (!empty($attributes['navWidth'])) ? $attributes['navWidth'] :false;
	$underline = (!empty($attributes['underline'])) ? $attributes['underline'] :false;
	$tablistRepeater = (!empty($attributes['tablistRepeater'])) ? $attributes['tablistRepeater'] : [];
	$titleShow =  (!empty($attributes['titleShow'])) ? $attributes['titleShow'] : false;
	$navPosition = (!empty($attributes['navPosition'])) ? $attributes['navPosition'] :'top' ;
	$VerticalAlign = (!empty($attributes['VerticalAlign'])) ? $attributes['VerticalAlign'] :'';
	$className = (!empty($attributes['className'])) ? $attributes['className'] :'';
	$align = (!empty($attributes['align'])) ? $attributes['align'] :'';
	
	$blockClass = '';
	if(!empty($className)){
		$blockClass .= $className;
	}
	if(!empty($align)){
		$blockClass .= ' align'.$align;
	}
	
	$output = '';
	$tab_nav = '';
	$tab_content = '';


	// Set Full Width Icon Class
	$full_icon_class = '';
	if($fullwidthIcon == true){
		$full_icon_class = 'full-width-icon';
	}else{
		$full_icon_class = 'normal-width-icon';
	}


	//Set class For full width Nav bar
	$full_width_nav = '';
	if($navWidth == true){
		$full_width_nav = 'full-width';
	}

	// set class For UnderLine
	$underline_class = '';
	if($underline == true){
		$underline_class = 'tab-underline';
	}

	//Set responsive class
	$responsive_class = '';
	

	//Set Vertival TabAlign class
	$alignclass = '';
	if($VerticalAlign == 'top'){
		$alignclass = 'align-top';
	}else if($VerticalAlign == 'center'){
		$alignclass = "align-center";
	}else if($VerticalAlign == 'bottom'){
		$alignclass = "align-bottom";
	}
	$i=0;$j=0;

	// Output for Tab Navigation
	$nav_loop='';
	if(!empty($tablistRepeater)){ 
		foreach ( $tablistRepeater as $index => $item ) :
			$j++;
			// Set active class
			$active='';
			if($j=='1'){
				$active=' active';
			}

			$nav_loop .= '<li>';
				$nav_loop .= '<div class="tpgb-tab-header '.esc_attr($active).'" data-tab="'.esc_attr($j).'" role="tab" >';
					if(!empty($item['innerIcon'])){
						$nav_loop .= '<span class="tab-icon-wrap">';
							if($item['iconFonts'] == 'font_awesome') {
								$nav_loop .= '<i class="tab-icon '.esc_attr($item['innericonName']).'"> </i>';
							}else if($item['iconFonts'] == 'image'){
								$icon_image=$item['iconImage']['id'];
								$img = wp_get_attachment_image_src($icon_image,$item['iconimageSize']);
								$icon_image = $img[0];
								$nav_loop .= '<img src="'.esc_url($icon_image).'"  alt="'.esc_attr__('icon-img','tpgb').'" />';
							} 
						$nav_loop .= '</span>';
					}
					if(!empty($titleShow)){
						$nav_loop .= '<span>' .esc_html($item['tabTitle']). '</span>';
					}
					
				$nav_loop .= '</div>';
			$nav_loop .= '</li>';
			
		endforeach;
	}
	$tab_nav .= '<div class="tpgb-tabs-nav-wrapper '.esc_attr($navAlign).' '.($tabLayout=='vertical' ? esc_attr($alignclass) : '').' ">';
		$tab_nav .= '<ul class="tpgb-tabs-nav '.esc_attr($full_icon_class).'  '.esc_attr($full_width_nav).' '.esc_attr($underline_class).' ">';
			$tab_nav .= $nav_loop;
		$tab_nav .= '</ul>';
	$tab_nav .= '</div>';
	
	//Output tab content
	$content_loop = '';
	if(!empty($tablistRepeater)){ 
		foreach ( $tablistRepeater as $index => $item ) :
			$i++;
		
			// Set active class
			$active='';
			if($i=='1'){
				$active=' active';
			}

			// Set Tab Title For responsive accordian
			$content_loop .= '<div class="tab-mobile-title '.esc_attr($active).' '.esc_attr($navAlign).'" data-tab="'.esc_attr($i).'" role="tab">';
				if(!empty($item['innerIcon'])){
					$content_loop .= '<span class="tab-icon-wrap">';
						if($item['iconFonts'] == 'font_awesome') {
								$content_loop .= '<i class="tab-icon '.esc_attr($item['innericonName']).'"> </i>';
						}else if($item['iconFonts'] == 'image'){
							$icon_image=$item['iconImage']['id'];
							$img = wp_get_attachment_image_src($icon_image,$item['iconimageSize']);
							$icon_image = $img[0];
							$content_loop .= '<img src="'.esc_url($icon_image).'"  alt="'.esc_attr__('icom_img','tpgb').'" />';
						} 
					$content_loop .= '</span>';
				}
				$content_loop .= '<span>'.esc_attr($item['tabTitle']).'</span>';
			$content_loop .= '</div>';

			$content_loop .= '<div class="tpgb-tab-content '.esc_attr($active).'" data-tab="'.esc_attr($i).'"  role="tabpanel" >';
				$content_loop .= '<div class ="tpgb-content-editor" >';
					if( !empty($item['contentType']) && $item['contentType'] == 'content'){
						$content_loop .= wp_kses_post($item['tabDescription']);
					}
				$content_loop .= '</div>';
			$content_loop .= '</div>';
			
		endforeach;
	}

	$tab_content .= '<div class="tpgb-tabs-content-wrapper" >' .$content_loop. '</div>';
	
	$output .= '<div class="tpgb-tabs-tours tpgb-block-'.esc_attr($block_id).'  tab-view-'.esc_attr($tabLayout).' '.esc_attr($blockClass).'">';
		$output .= '<div class="tpgb-tabs-wrapper '.esc_attr($responsive_class).' "    data-tab-default="1" data-tab-hover="no" >';
			if($navPosition == 'top' || $navPosition == 'left'  ){
				$output .= $tab_nav.$tab_content;
			}else{
				$output .= $tab_content.$tab_nav;
			}
		$output .= '</div>';
	$output .= '</div>';

	$output = Tpgb_Blocks_Global_Options::block_Wrap_Render($attributes, $output);
	
    return $output;
}

/**
 * Render for the server-side
 */
function tpgb_tp_tabs_tours() {
	$globalBgOption = Tpgb_Blocks_Global_Options::load_bg_options();
	$globalpositioningOption = Tpgb_Blocks_Global_Options::load_positioning_options();
 	$globalPlusExtrasOption = Tpgb_Blocks_Global_Options::load_plusextras_options();
	
	$attributesOptions = [
			'block_id' => array(
                'type' => 'string',
				'default' => '',
			),
			'tablistRepeater' => [
				'type' => 'array',
				'repeaterField' => [
					(object) [
						'tabTitle' => [
							'type' => 'string',
							'default' => 'Tab',	
						],
						'contentType' => [
							'type' => 'string',
							'default' => 'content',	
						],
						'blockTemp' => [
							'type' => 'string',
							'default' => '',	
						],
						'backendVisi' => [
							'type' => 'boolean',
							'default' => true,	
						],
						'tabDescription' => [
							'type' => 'string',
							'default' => 'This is just dummy content. Put your relevant content over here. We want to remind you, smile and passion are contagious, be a carrier.',	
						],
						'innerIcon'  => [
							'type' => 'boolean',
							'default' => false,	
						],
						'iconFonts' => [
							'type' => 'string',
							'default' => 'font_awesome',	
						],
						'iconImage' => [
							'type' => 'object',
							'default'=> [
								'url' => TPGB_ASSETS_URL.'assets/images/tpgb-placeholder.jpg'
							],	
						],
						'iconimageSize' => [
							'type' => 'string',
							'default' => 'full',	
						],
						'outerIcon'  => [
							'type' => 'boolean',
							'default' => false,	
						],
						'innericonName' => [
							'type'=> 'string',
							'default'=> 'fas fa-home',
						],
						'outericonName' => [
							'type'=> 'string',
							'default'=> 'fas fa-home',
						],
						'uniqueId' => [
							'type'=> 'string',
							'default'=> '',
						],
					],
				], 
				'default' => [
					[
						"_key" => '0',
						"tabTitle" => 'Tab 1',
						"tabDescription" => "This is just dummy content. Put your relevant content over here. We want to remind you, smile and passion are contagious, be a carrier.",
						'iconimageSize' => 'full',
						'contentType' => 'content',
						'iconFonts' => 'font_awesome',
						'innericonName' => 'fas fa-home',
						'outericonName' => 'fas fa-home'
					],
					[
						"_key" => '1',
						"tabTitle" => 'Tab 2',
						"tabDescription" => "Enter your relevant content over here. This is just dummy content.  We want to remind you, smile and passion are contagious, be a carrier.",
						'iconimageSize' => 'full',
						'contentType' => 'content',
						'iconFonts' => 'font_awesome',
						'innericonName' => 'fas fa-home',
						'outericonName' => 'fas fa-home'
					],
				],
			],
			'tabLayout' => [
				'type' => 'string',
				'default' => 'horizontal',	
			],
			'navPosition' => [
				'type' => 'string',
				'default' => 'top',
			],
			'swiperEffect' => [
				'type' => 'boolean',
				'default' => false,
			],
			'iconSize' => [
				'type' => 'object',
				'default' => [ 
					'md' => '',
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-tabs-nav-wrapper .tpgb-tab-header  .tab-icon-wrap { font-size: {{iconSize}};}{{PLUS_WRAP}} .tpgb-tabs-nav-wrapper .tpgb-tab-header  .tab-icon-wrap img { max-width: {{iconSize}};}{{PLUS_WRAP}} .mobile-accordion .tab-mobile-title .tab-icon-wrap{ font-size: {{iconSize}}; }{{PLUS_WRAP}} .mobile-accordion .tab-mobile-title .tab-icon-wrap img{ max-width: {{iconSize}};}',
					],
				],
			],
			'iconColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-tabs-nav-wrapper .tpgb-tab-header  .tab-icon-wrap {color: {{iconColor}};}
						{{PLUS_WRAP}} .mobile-accordion .tab-mobile-title .tab-icon-wrap{color: {{iconColor}};}',
					],
				],
			],
			'iconActcolor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-tabs-nav-wrapper .tpgb-tab-header.active .tab-icon-wrap ,{{PLUS_WRAP}} .tpgb-tabs-nav-wrapper .tpgb-tab-header:hover .tab-icon-wrap { color: {{iconActcolor}}; }{{PLUS_WRAP}} .mobile-accordion .tab-mobile-title.active .tab-icon-wrap{ color: {{iconActcolor}}; }',
					],
				],
			],
			'iconSpacing' => [
				'type' => 'object',
				'default' => [ 
					'md' => '',
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'fullwidthIcon', 'relation' => '==', 'value' => false]],
						'selector' => '{{PLUS_WRAP}} .tpgb-tabs-wrapper .tpgb-tabs-nav:not(.full-width-icon) .tpgb-tab-header .tab-icon-wrap{ padding-right: {{iconSpacing}}; }{{PLUS_WRAP}} .mobile-accordion .tab-mobile-title .tab-icon-wrap{ padding-right: {{iconSpacing}}; }',
						
						
					],
					(object) [
						'condition' => [(object) ['key' => 'fullwidthIcon', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}} .tpgb-tabs-wrapper ul.tpgb-tabs-nav.full-width-icon .tpgb-tab-header .tab-icon-wrap{ padding-right: 0px ; padding-bottom: {{iconSpacing}}; }',
					],
				],
			],
			'fullwidthIcon' => [
				'type' => 'boolean',
				'default' => false,
			],
			'vernavWidth' => [
				'type' => 'object',
				'default' => [ 
					'md' => '',
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'tabLayout', 'relation' => '==', 'value' => 'vertical']],
						'selector' => '{{PLUS_WRAP}}.tab-view-vertical  .tpgb-tabs-nav-wrapper{ width: {{vernavWidth}}; }',
					],
				],
			],
			'VerticalAlign' => [
				'type' => 'string',
				'default' => 'center',
			],
			'titleTypo' => [
				'type'=> 'object',
				'default'=> (object) [
					'openTypography' => 0,
					'size' => [ 'md' => '', 'unit' => 'px' ],
				],
				'style' =>[
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-tabs-nav .tpgb-tab-header,{{PLUS_WRAP}} .mobile-accordion .tab-mobile-title',
					]
				],
			],
			'navAlign' => [
				'type' => 'string',
				'default' => 'text-left',
			],
			
			'navWidth' => [
				'type' => 'boolean',
				'default' => false,
			],
			'titleShow' => [
				'type' => 'boolean',
				'default' => true,
			],
			'navequalwidth' => [
				'type' => 'boolean',
				'default' => false,
			],
			'titleColor' => [
				'type' => 'string',
				'style' =>[
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-tabs-nav .tpgb-tab-header{ color: {{titleColor}}; }{{PLUS_WRAP}} .mobile-accordion .tab-mobile-title{color: {{titleColor}};}',
						
					],
				],
			],
			'titleActcolor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-tabs-nav .tpgb-tab-header.active,{{PLUS_WRAP}} .tpgb-tabs-nav .tpgb-tab-header:hover{ color: {{titleActcolor}}; }{{PLUS_WRAP}} .mobile-accordion .tab-mobile-title.active{color: {{titleActcolor}};}',
					]
				]
			],
			'underline' => [
				'type' => 'boolean',
				'default' => true,	
			],
			'ulineColor' => [
				'type' => 'string',
				'default' => '',
				'style' =>[
					(object) [
						'condition' => [(object) ['key' => 'underline', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}} .tpgb-tabs-wrapper .tpgb-tabs-nav.tab-underline .tpgb-tab-header.active:before{ background: linear-gradient(to right,#fff0 0%,{{ulineColor}}  50%,#fff0 100%); }',
						
					],
				],
			],
			'lineMargin' => [
				'type' => 'string',
				'default' => [ 
					'md' => '',
					"unit" => 'px',
				],
				'style' =>[
					(object) [
						'condition' => [(object) ['key' => 'underline', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}} .tpgb-tabs-wrapper .tpgb-tabs-nav.tab-underline .tpgb-tab-header.active:before,{{PLUS_WRAP}} ul.tpgb-tabs-nav.tab-underline:before{ margin-top : {{lineMargin}} }',
						
					],
				],
			],
			'lineWidth' => [
				'type' => 'string',
				'default' => [ 
					'md' => '',
					"unit" => 'px',
				],
				'style' =>[
					(object) [
						'condition' => [(object) ['key' => 'underline', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}} .tpgb-tabs-wrapper .tpgb-tabs-nav.tab-underline .tpgb-tab-header.active:before{ width: {{lineWidth}}; }',
						
					],
				],
			],
			'lineHeight' => [
				'type' => 'string',
				'default' => [ 
					'md' => '',
					"unit" => 'px',
				],
				'style' =>[
					(object) [
						'condition' => [(object) ['key' => 'underline', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}} .tpgb-tabs-wrapper .tpgb-tabs-nav.tab-underline .tpgb-tab-header.active:before,{{PLUS_WRAP}} ul.tpgb-tabs-nav.tab-underline:before{ height: {{lineHeight}}; }',
						
					],
				],
			],
			'tabMargin' => [
				'type' => 'object',
				'default' => (object) [ 
					'md' => (object) ['top' => '','bottom' => '', 'left'=> '','right' => ''],
					"unit" => 'px',
				],
				'style' =>[
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-tabs-wrapper .tpgb-tabs-nav .tpgb-tab-header{ margin : {{tabMargin}};}{{PLUS_WRAP}} .mobile-accordion .tab-mobile-title{margin : {{tabMargin}};}',
						
					],
				],
			],
			'tabPadding' => [
				'type' => 'object',
				'default' => (object) [ 
					'md' => (object) ['top' => '','bottom' => '', 'left'=> '','right' => ''],
					"unit" => 'px',
				],
				'style' =>[
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-tabs-wrapper .tpgb-tabs-nav .tpgb-tab-header{ padding : {{tabPadding}}}{{PLUS_WRAP}} .mobile-accordion .tab-mobile-title{padding : {{tabPadding}};}',
						
					],
				],
			],
			'navSpace' => [
				'type' => 'object',
				'default' => [ 
					'md' => '',
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'tabLayout', 'relation' => '==', 'value' => 'horizontal']],
						'selector' => '{{PLUS_WRAP}}.tab-view-horizontal .tpgb-tabs-wrapper .tpgb-tabs-nav .tpgb-tab-header{ margin-left: {{navSpace}}; }  {{PLUS_WRAP}}.tab-view-horizontal .tpgb-tabs-wrapper .tpgb-tabs-nav li:first-child .tpgb-tab-header{ margin-left: 0 ; }  {{PLUS_WRAP}}.tab-view-horizontal .tpgb-tabs-wrapper .tpgb-tabs-nav li:last-child .tpgb-tab-header{ margin-right: 0; }',
						
						
					],
					(object) [
						'condition' => [(object) ['key' => 'tabLayout', 'relation' => '==', 'value' => 'vertical']],
						'selector' => '{{PLUS_WRAP}}.tab-view-vertical .tpgb-tabs-wrapper .tpgb-tabs-nav .tpgb-tab-header{ margin-top: {{navSpace}}; }  {{PLUS_WRAP}}.tab-view-vertical .tpgb-tabs-wrapper .tpgb-tabs-nav li:first-child .tpgb-tab-header{ margin-top: 0 ; }  {{PLUS_WRAP}}.tab-view-vertical .tpgb-tabs-wrapper .tpgb-tabs-nav li:last-child .tpgb-tab-header{ margin-bottom: 0; }',
					],
				],
			],
			'tabBorder' => [
				'type' => 'object',
				'default' => (object) [
					'openBorder' => 0,
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-tabs-nav .tpgb-tab-header',
					],
				],

			],
			'normalBradius' => [
				'type' => 'object',
				'default' => (object) [ 
					'md' => (object) ['top' => '','bottom' => '', 'left'=> '','right' => ''],
					"unit" => 'px',
				],
				'style' =>[
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-tabs-nav .tpgb-tab-header{border-radius : {{normalBradius}} }',
						
					],
				],
			],
			'tabActborder' => [
				'type' => 'object',
				'default' => (object) [
					'openBorder' => 0,
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-tabs-nav .tpgb-tab-header.active',
					],
				],

			],
			'actBradius' => [
				'type' => 'object',
				'default' => (object) [ 
					'md' => (object) ['top' => '','bottom' => '', 'left'=> '','right' => ''],
					"unit" => 'px',
				],
				'style' =>[
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-tabs-nav .tpgb-tab-header.active{border-radius : {{actBradius}} }',
					],
				],
			],
			'tabbgType' => [
				'type' => 'object',
				'default' => (object) [
					'openBg'=> 0,
					'bgType' => 'color',
				],
				'style' =>[
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-tabs-wrapper .tpgb-tabs-nav .tpgb-tab-header',
					],
				],
			],
			'acttabBgtype' => [
				'type' => 'object',
				'default' => (object) [
					'openBg'=> 0,
					'bgType' => 'color',
				],
				'style' =>[
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-tabs-wrapper .tpgb-tabs-nav .tpgb-tab-header.active , {{PLUS_WRAP}} .tpgb-tabs-wrapper .tpgb-tabs-nav .tpgb-tab-header:hover',
					],
				],
			],
			'tabNBshadow' => [
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
				'style' =>[
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-tabs-wrapper .tpgb-tabs-nav .tpgb-tab-header',
					],
				],
			],
			'tabActBshadow' => [
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
				'style' =>[
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-tabs-wrapper .tpgb-tabs-nav .tpgb-tab-header.active , {{PLUS_WRAP}} .tpgb-tabs-wrapper .tpgb-tabs-nav .tpgb-tab-header:hover',
					],
				],
			],
			'navbarMargin' => [
				'type' => 'object',
				'default' => (object) [ 
					'md' => (object) ['top' => '','bottom' => '', 'left'=> '','right' => ''],
					"unit" => 'px',
				],
				'style' =>[
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-tabs-wrapper .tpgb-tabs-nav{ margin: {{navbarMargin}} }',
					],
				],
			],
			'navbarPadding' => [
				'type' => 'object',
				'default' => (object) [ 
					'md' => (object) ['top' => '','bottom' => '', 'left'=> '','right' => ''],
					"unit" => 'px',
				],
				'style' =>[
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-tabs-wrapper .tpgb-tabs-nav{ padding: {{navbarPadding}} }',
					],
				],
			],
			'navBoder' => [
				'type' => 'object',
				'default' => (object) [
					'openBorder' => 0,	
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-tabs-wrapper .tpgb-tabs-nav',
					],
				],

			],
			'navNBradius' => [
				'type' => 'object',
				'default' => (object) [ 
					'md' => (object) ['top' => '','bottom' => '', 'left'=> '','right' => ''],
					"unit" => 'px',
				],
				'style' =>[
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-tabs-wrapper .tpgb-tabs-nav{border-radius : {{navNBradius}} }',
						
					],
				],
			],
			'navhvrBorder' => [
				'type' => 'object',
				'default' => (object) [
					'openBorder' => 0,
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-tabs-wrapper .tpgb-tabs-nav:hover',
					],
				],
			],
			'navhvrBradius' => [
				'type' => 'object',
				'default' => (object) [ 
					'md' => (object) ['top' => '','bottom' => '', 'left'=> '','right' => ''],
					"unit" => 'px',
				],
				'style' =>[
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-tabs-wrapper .tpgb-tabs-nav:hover{border-radius : {{navhvrBradius}} }',
						
					],
				],
			],
			'navbgType' => [
				'type' => 'object',
				'default' => (object) [
					'openBg'=> 0,
					'bgType' => 'color',
				],
				'style' =>[
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-tabs-wrapper .tpgb-tabs-nav-wrapper .tpgb-tabs-nav',
						
					],
				],
			],
			'navhvrBgtype' => [
				'type' => 'object',
				'default' => (object) [
					'openBg'=> 0,
					'bgType' => 'color',
				],
				'style' =>[
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-tabs-wrapper .tpgb-tabs-nav-wrapper .tpgb-tabs-nav:hover',
						
					],
				],
			],
			'navNBshadow' => [
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
				'style' =>[
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-tabs-wrapper .tpgb-tabs-nav-wrapper .tpgb-tabs-nav',
						
					],
				],
			],
			'navhvrBshadow' => [
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
				'style' =>[
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-tabs-wrapper .tpgb-tabs-nav-wrapper .tpgb-tabs-nav:hover',
						
					],
				],
			],
			'descTypo' => [
				'type'=> 'object',
				'default'=> (object) [
					'openTypography' => 0,
					'size' => [ 'md' => '', 'unit' => 'px' ],
				],
				'style' =>[
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-tabs-wrapper .tpgb-tabs-content-wrapper .tpgb-tab-content .tpgb-content-editor',
					],
				],
			],
			'descColor' => [
				'type' => 'string',
				'default' => '',
				'style' =>[
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-tabs-wrapper .tpgb-tabs-content-wrapper .tpgb-tab-content .tpgb-content-editor{color: {{descColor}}}',
						
					],
				],
			],
			'descMargin' => [
				'type' => 'object',
				'default' => (object) [ 
					'md' => (object) ['top' => '','bottom' => '', 'left'=> '','right' => ''],
					"unit" => 'px',
				],
				'style' =>[
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-tabs-wrapper .tpgb-tabs-content-wrapper{ margin : {{descMargin}}}',
						
					],
				],
			],
			'descPadding' => [	
				'type' => 'object',
				'default' => (object) [ 
					'md' => (object) ['top' => '','bottom' => '', 'left'=> '','right' => ''],
					"unit" => 'px',
				],
				'style' =>[
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-tabs-wrapper .tpgb-tabs-content-wrapper{ padding : {{descPadding}}}',
						
					],
				],
			],
			'descBorder' => [
				'type' => 'object',
				'default' => (object) [
					'openBorder' => 0,	
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-tabs-wrapper .tpgb-tabs-content-wrapper',
					],
				],
			],
			'descBRedius' => [
				'type' => 'object',
				'default' => (object) [ 
					'md' => (object) ['top' => '','bottom' => '', 'left'=> '','right' => ''],
					"unit" => 'px',
				],
				'style' =>[
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-tabs-wrapper .tpgb-tabs-content-wrapper{border-radius : {{descBRedius}} }',
						
					],
				],
			],
			'descbgType' => [
				'type' => 'object',
				'default' => (object) [
					'openBg'=> 0,
					'bgType' => 'color',
				],
				'style' =>[
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-tabs-wrapper .tpgb-tabs-content-wrapper',
						
					],
				],
			],
			'descboxShadow' => [
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
				'style' =>[
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-tabs-wrapper .tpgb-tabs-content-wrapper',
						
					],
				],
			],
			
		];
	$attributesOptions = array_merge($attributesOptions, $globalBgOption, $globalpositioningOption, $globalPlusExtrasOption);
		register_block_type( 'tpgb/tp-tabs-tours', array(
			'attributes' => $attributesOptions,
			'editor_script' => 'tpgb-block-editor-js',
			'editor_style'  => 'tpgb-block-editor-css',
			'render_callback' => 'tpgb_tp_tabs_tours_render_callback'
    	) );
}
add_action( 'init', 'tpgb_tp_tabs_tours' );