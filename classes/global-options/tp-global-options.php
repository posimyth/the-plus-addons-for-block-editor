<?php
/**
 * TPGB Global Options
 *
 * @package TPGB
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Tpgb_Blocks_Global_Options.
 *
 * @package TPGB
 */
class Tpgb_Blocks_Global_Options {

	/**
	 * Member Variable
	 *
	 * @var instance
	 */
	private static $instance;
	
	public static $merge_options = array();

	public static $global_options = array();

	public static $global_pro_opt = array();
	
	/**
	 *  Initiator
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self;
		}
		return self::$instance;
	}
	
	/*
	 * Carousel Options
	 * @since 1.1.2
	 */
	public static function carousel_options(){
	
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}
		
		$options = [
			'sliderMode' => [
				'type' => 'string',
				'default' => 'horizontal',
				'scopy' => true,
			],
			'slideSpeed' => [
				'type' => 'string',
				'default' => 1500,
				'scopy' => true,
			],
			'slideColumns' => [
				'type' => 'object',
				'default' => [ 'md' => 1,'sm' => 1,'xs' => 1 ],
				'scopy' => true,
			],
			'initialSlide' => [
				'type' => 'number',
				'default' => 0,
				'scopy' => true,
			],
			'slideScroll' => [
				'type' => 'object',
				'default' => [ 'md' => 1 ],
				'scopy' => true,
			],
			'slideColumnSpace' => [
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
						'selector' => '{{PLUS_WRAP}} .splide__list .splide__slide {padding: {{slideColumnSpace}};}',
					],
				],
				'scopy' => true,
			],
			'slideDraggable' => [
				'type' => 'object',
				'default' => [ 'md' => true ],
				'scopy' => true,
			],
			'slideInfinite' => [
				'type' => 'boolean',
				'default' => false,
				'scopy' => true,
			],
			'slideHoverPause' => [
				'type' => 'boolean',
				'default' => false,
				'scopy' => true,
			],
			'slideAutoplay' => [
				'type' => 'boolean',
				'default' => true,
				'scopy' => true,
			],
			'slideAutoplaySpeed' => [
				'type' => 'string',
				'default' => 1500,
				'scopy' => true,
			],
			'showDots' => [
				'type' => 'object',
				'default' => [ 'md' => true ],
				'scopy' => true,
			],
			'dotsStyle' => [
				'type' => 'string',
				'default' => 'style-1',
				'scopy' => true,
			],
			'dotsBorderColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
						(object) [
						'condition' => [
							(object) [ 'key' => 'dotsStyle', 'relation' => '==', 'value' => 'style-1' ],
						],
						'selector' => '{{PLUS_WRAP}}.dots-style-1 ul.splide__pagination li button.splide__pagination__page{-webkit-box-shadow:inset 0 0 0 8px {{dotsBorderColor}};-moz-box-shadow: inset 0 0 0 8px {{dotsBorderColor}};box-shadow: inset 0 0 0 8px {{dotsBorderColor}};} {{PLUS_WRAP}}.dots-style-1 ul.splide__pagination li button.splide__pagination__page.is-active{-webkit-box-shadow:inset 0 0 0 1px {{dotsBorderColor}};-moz-box-shadow: inset 0 0 0 1px {{dotsBorderColor}};box-shadow: inset 0 0 0 1px {{dotsBorderColor}};}{{PLUS_WRAP}}.dots-style-1 ul.splide__pagination li button.splide__pagination__page{background: transparent;color: {{dotsBorderColor}};}',
					],
				],
				'scopy' => true,
			],
			'dotsTopSpace' => [
				'type' => 'object',
				'default' => [ 'md' => 0,'sm' => 0,'xs' => 0,'unit' => 'px' ],
				'style' => [
						(object) [
						'condition' => [ 
							(object) [ 'key' => 'showDots', 'relation' => '==', 'value' => true ]
						],
						'selector' => '{{PLUS_WRAP}} .splide__pagination{ margin-top: {{dotsTopSpace}} !important;}',
					],
				],
				'scopy' => true,
			],
			'slideHoverDots' => [
				'type' => 'boolean',
				'default' => false,
				'scopy' => true,
			],
			'showArrows' => [
				'type' => 'object',
				'default' => [ 'md' => false ],
				'scopy' => true,
			],
			'arrowsStyle' => [
				'type' => 'string',
				'default' => 'style-1',
				'scopy' => true,
			],
			'arrowsPosition' => [
				'type' => 'string',
				'default' => 'top-right',
				'scopy' => true,
			],
			'arrowsBgColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [
							(object) [ 'key' => 'arrowsStyle', 'relation' => '==', 'value' => 'style-1' ],
							(object) [ 'key' => 'showArrows', 'relation' => '==', 'value' => true ]
						],
						'selector' => '{{PLUS_WRAP}} .splide__arrows.style-1 .splide__arrow.style-1{background:{{arrowsBgColor}};}',
					],
					(object) [
						'condition' => [
							(object) [ 'key' => 'arrowsStyle', 'relation' => '==', 'value' => ['style-3','style-4','style-6'] ],
							(object) [ 'key' => 'showArrows', 'relation' => '==', 'value' => true ]
						],
						'selector' => '{{PLUS_WRAP}} .splide__arrows.style-3 .splide__arrow.style-3 .icon-wrap, {{PLUS_WRAP}} .splide__arrows.style-6 .splide__arrow.style-6:before{background:{{arrowsBgColor}};} {{PLUS_WRAP}} .splide__arrows.style-4 .splide__arrow.style-4 .icon-wrap{border-color:{{arrowsBgColor}}}',
					],
				],
				'scopy' => true,
			],
			'arrowsIconColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [
							(object) [ 'key' => 'arrowsStyle', 'relation' => '==', 'value' => 'style-1' ],
							(object) [ 'key' => 'showArrows', 'relation' => '==', 'value' => true ]
						],
						'selector' => '{{PLUS_WRAP}} .splide__arrows.style-1 .splide__arrow.style-1:before{color:{{arrowsIconColor}};}',
					],
					(object) [
						'condition' => [
							(object) [ 'key' => 'arrowsStyle', 'relation' => '==', 'value' => ['style-2','style-3','style-4','style-5','style-6'] ],
							(object) [ 'key' => 'showArrows', 'relation' => '==', 'value' => true ]
						],
						'selector' => '{{PLUS_WRAP}} .splide__arrows.style-3 .splide__arrow.style-3 .icon-wrap,{{PLUS_WRAP}} .splide__arrows.style-4 .splide__arrow.style-4 .icon-wrap,{{PLUS_WRAP}} .splide__arrows.style-6 .splide__arrow.style-6 .icon-wrap svg{color:{{arrowsIconColor}};}{{PLUS_WRAP}} .splide__arrows.style-2 .splide__arrow.style-2 .icon-wrap:before,{{PLUS_WRAP}} .splide__arrows.style-2 .splide__arrow.style-2 .icon-wrap:after,{{PLUS_WRAP}} .splide__arrows.style-5 .splide__arrow.style-5 .icon-wrap:before,{{PLUS_WRAP}} .splide__arrows.style-5 .splide__arrow.style-5 .icon-wrap:after{background:{{arrowsIconColor}};}',
					],
				],
				'scopy' => true,
			],
			'arrowsHoverBgColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [
							(object) [ 'key' => 'arrowsStyle', 'relation' => '==', 'value' => 'style-1' ],
							(object) [ 'key' => 'showArrows', 'relation' => '==', 'value' => true ]
						],
						'selector' => '{{PLUS_WRAP}} .splide__arrows.style-1 .splide__arrow.style-1:hover{background:{{arrowsHoverBgColor}};}',
					],
					(object) [
						'condition' => [
							(object) [ 'key' => 'arrowsStyle', 'relation' => '==', 'value' => ['style-2','style-3','style-4'] ],
							(object) [ 'key' => 'showArrows', 'relation' => '==', 'value' => true ]
						],
						'selector' => '{{PLUS_WRAP}} .splide__arrows.style-2 .splide__arrow.style-2:hover:before,{{PLUS_WRAP}} .splide__arrows.style-3 .splide__arrow.style-3:hover .icon-wrap{background:{{arrowsHoverBgColor}};}{{PLUS_WRAP}} .splide__arrows.style-4 .splide__arrow.style-4:hover:before,{{PLUS_WRAP}} .splide__arrows.style-4 .splide__arrow.style-4:hover .icon-wrap{border-color:{{arrowsHoverBgColor}};}',
					],
				],
				'scopy' => true,
			],
			'arrowsHoverIconColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [
							(object) [ 'key' => 'arrowsStyle', 'relation' => '==', 'value' => 'style-1' ],
							(object) [ 'key' => 'showArrows', 'relation' => '==', 'value' => true ]
						],
						'selector' => '{{PLUS_WRAP}} .splide__arrows.style-1 .splide__arrow.style-1:hover:before{color:{{arrowsHoverIconColor}};}',
					],
					(object) [
						'condition' => [
							(object) [ 'key' => 'arrowsStyle', 'relation' => '==', 'value' => ['style-2','style-3','style-4','style-5','style-6'] ],
							(object) [ 'key' => 'showArrows', 'relation' => '==', 'value' => true ]
						],
						'selector' => '{{PLUS_WRAP}} .splide__arrows.style-3 .splide__arrow.style-3:hover .icon-wrap,{{PLUS_WRAP}} .splide__arrows.style-4 .splide__arrow.style-4:hover .icon-wrap,{{PLUS_WRAP}} .splide__arrows.style-6 .splide__arrow.style-6:hover .icon-wrap svg{color:{{arrowsHoverIconColor}};}{{PLUS_WRAP}} .splide__arrows.style-2 .splide__arrow.style-2:hover .icon-wrap:before,{{PLUS_WRAP}} .splide__arrows.style-2 .splide__arrow.style-2:hover .icon-wrap:after,{{PLUS_WRAP}} .splide__arrows.style-5 .splide__arrow.style-5:hover .icon-wrap:before,{{PLUS_WRAP}} .splide__arrows.style-5 .splide__arrow.style-5:hover .icon-wrap:after{background:{{arrowsHoverIconColor}};}',
					],
				],
				'scopy' => true,
			],
			'outerArrows' => [
				'type' => 'boolean',
				'default' => false,
				'scopy' => true,
			],
			'slideHoverArrows' => [
				'type' => 'boolean',
				'default' => false,
				'scopy' => true,
			],
			'centerMode' => [
				'type' => 'object',
				'default' => [ 'md' => false ],
				'scopy' => true,
			],
			'slidewheel' => [
				'type' => 'boolean',
				'default' => false,
				'scopy' => true,
			],
			'waitfortras' => [
				'type' => 'boolean',
				'default' => false,
				'scopy' => true,
			],
			'slidekeyNav' => [
				'type' => 'boolean',
				'default' => false,
				'scopy' => true,
			],
			'slideautoScroll' => [
				'type' => 'boolean',
				'default' => false,
				'scopy' => true,
			],
			'autoscSpeed' => [
				'type' => 'string',
				'default' => '1',
			],
		];
		
		if(has_filter('tpgb_carousel_options')) {
			$options = apply_filters('tpgb_carousel_options', $options);
		}
		
		return $options;
	}
	
	/**
	 * Load Global Background Options
	 *
	 * @since 1.0.0
	 */
	public static function load_bg_options() {
		
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}
		$options = [
			'saveGlobalStyle' => [
				'type' => 'string',
				'default' => '',
			],
			'saveGlobalStyleClass' => [
				'type' => 'string',
				'default' => '',
			],
			'globalMargin' => [
				'type' => 'object',
				'default' => (object) [ 
					'md' => [
						"top" => '',
						"bottom" => '',
						"left" => '',
						"right" => '',
					],
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}{margin: {{globalMargin}} !important;}',
					],
				],
				'scopy' => true,
			],
			'globalPadding' => [
				'type' => 'object',
				'default' => (object) [ 
					'md' => [
						"top" => '',
						"bottom" => '',
						"left" => '',
						"right" => '',
					],
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}{padding: {{globalPadding}};}',
					],
				],
				'scopy' => true,
			],
			'globalBg' => [
				'type' => 'object',
				'default' => (object) [
					'bgType' => 'color',
					'bgDefaultColor' => '',
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}',
					],
				],
				'scopy' => true,
			],
			'globalBgHover' => [
				'type' => 'object',
				'default' => (object) [
					'bgType' => 'color',
					'bgDefaultColor' => '',
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}:hover',
					],
				],
				'scopy' => true,
			],
			'globalBorder' => [
				'type' => 'object',
				'default' => (object) [
					'openBorder' => 0,
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}',
					],
				],
				'scopy' => true,
			],
			'globalBorderHover' => [
				'type' => 'object',
				'default' => (object) [
					'openBorder' => 0,
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}:hover',
					],
				],
				'scopy' => true,
			],
			'globalBRadius' => [
				'type' => 'object',
				'default' => (object) [ 
					'md' => [
						"top" => '',
						"bottom" => '',
						"left" => '',
						"right" => '',
					],
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}{border-radius: {{globalBRadius}};}',
					],
				],
				'scopy' => true,
			],
			'globalBRadiusHover' => [
				'type' => 'object',
				'default' => (object) [ 
					'md' => [
						"top" => '',
						"bottom" => '',
						"left" => '',
						"right" => '',
					],
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}:hover{border-radius: {{globalBRadiusHover}};}',
					],
				],
				'scopy' => true,
			],
			'globalBShadow' => [
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
						'selector' => '{{PLUS_WRAP}}',
					],
				],
				'scopy' => true,
			],
			'globalBShadowHover' => [
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
						'selector' => '{{PLUS_WRAP}}:hover',
					],
				],
				'scopy' => true,
			],
		];
		
		return $options;
	}
	
	/**
	 * Load Global Background Options
	 *
	 * @since 1.0.0
	 */
	public static function load_positioning_options() {
		
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}
		$options = [
			'globalWidth' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'globalWidth', 'relation' => '==', 'value' => 'inline' ]],
						'selector' => '{{PLUS_BLOCK}},{{PLUS_WRAP}}{ display:inline-block;width: auto;margin-bottom: 0 !important }',
					],
				],
				'scopy' => true,
			],
			'customWidth' => [
				'type' => 'object',
				'default' => [ 
					'md' => '',
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'globalWidth', 'relation' => '==', 'value' => 'custom']],
						'selector' => '{{PLUS_BLOCK}},{{PLUS_WRAP}}{ max-width: {{customWidth}}; width: 100% !important;}',
					],
				],
			],
			'globalZindex' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_BLOCK}},{{PLUS_WRAP}}{ position:relative;z-index: {{globalZindex}} !important; }',
					],
				],
				'scopy' => true,
			],
			'globalflexCss' => [
				'type'=> 'object',
				'groupField' => [
					(object) [
						'gloflexShrink' => [
							'type' => 'object',
							'default' => [ 
								'md' => '',
							],
							'style' => [
								(object) [
									'condition' => [(object) ['key' => 'tpgbReset', 'relation' => '==', 'value' => 1 ]],
									'selector' => '{{PLUS_CLIENT_ID}}:not(.tp-core-heading):not(.tpgb-icon-box):not(.tpgb-image):not(.tp-button-core){ flex-shrink : {{gloflexShrink}} }',
									'backend' => true,
								],
								(object) [
									'condition' => [(object) ['key' => 'tpgbReset', 'relation' => '==', 'value' => 1 ]],
									'selector' => '{{PLUS_BLOCK}} { flex-shrink : {{gloflexShrink}} }',
									'backend' => false,
								],
							],
							'scopy' => true,
						],
						'gloflexGrow' => [
							'type' => 'object',
							'default' => [ 
								'md' => '',
							],
							'style' => [
								(object) [
									'condition' => [(object) ['key' => 'tpgbReset', 'relation' => '==', 'value' => 1 ]],
									'selector' => '{{PLUS_CLIENT_ID}}:not(.tp-core-heading):not(.tpgb-icon-box):not(.tpgb-image):not(.tp-button-core){ flex-grow : {{gloflexGrow}} }',
									'backend' => true,
								],
								(object) [
									'condition' => [(object) ['key' => 'tpgbReset', 'relation' => '==', 'value' => 1 ]],
									'selector' => '{{PLUS_BLOCK}}{ flex-grow : {{gloflexGrow}} }',
									'backend' => false,
								],
							],
							'scopy' => true,
						],
						'gloflexBasis' => [
							'type' => 'object',
							'default' => [ 
								'md' => '',
								"unit" => '%',
							],
							'style' => [
								(object) [
									'condition' => [(object) ['key' => 'tpgbReset', 'relation' => '==', 'value' => 1 ]],
									'selector' => '{{PLUS_CLIENT_ID}}:not(.tp-core-heading):not(.tpgb-icon-box):not(.tpgb-image):not(.tp-button-core){ flex-basis : {{gloflexBasis}} }',
									'backend' => true,
								],
								(object) [
									'condition' => [(object) ['key' => 'tpgbReset', 'relation' => '==', 'value' => 1 ]],
									'selector' => '{{PLUS_BLOCK}}{ flex-basis : {{gloflexBasis}} }',
									'backend' => false,
								],
							],
							'scopy' => true,
						],
					],
				],
				'default' => [ 
					['gloflexShrink' => [ 'md' => '' ] , 'gloflexGrow' => [ 'md' => '' ], 'gloflexBasis' => [ 'md' => '' , "unit" => '%' ] ]
				],
			],
			'globalCssFilter' => [
                'type' => 'object',
				'default' => ["openFilter" => false],
				'style' => [
						(object) [
						'selector' => '{{PLUS_BLOCK}} .tpgb-cssfilters',
					],
				],
				'scopy' => true,
			],
			'globalHCssFilter' => [
                'type' => 'object',
				'default' => ["openFilter" => false],
				'style' => [
						(object) [
						'selector' => '{{PLUS_BLOCK}} .tpgb-cssfilters:hover',
					],
				],
				'scopy' => true,
			],
			'globalHideDesktop' => [
				'type' => 'boolean',
				'default' => false,
				'style' => [
					(object) [
						'selector' => '@media (min-width: 1201px){ .edit-post-visual-editor {{PLUS_WRAP}},.editor-styles-wrapper {{PLUS_WRAP}}{display: block;opacity: .5;} }',
                        'backend' => true,
					],
                    (object) [
						'selector' => '@media (min-width: 1201px){ {{PLUS_WRAP}}{ display:none !important; } }',
                        'backend' => false,
					],
				],
				'scopy' => true,
			],
			'globalHideTablet' => [
				'type' => 'boolean',
				'default' => false,
				'style' => [
					(object) [
						'selector' => '@media (min-width: 768px) and (max-width: 1200px){ .edit-post-visual-editor {{PLUS_WRAP}},.editor-styles-wrapper {{PLUS_WRAP}}{display: block;opacity: .5;} }',
                        'backend' => true,
					],
                    (object) [
						'selector' => '@media (min-width: 768px) and (max-width: 1200px){ {{PLUS_WRAP}}{ display:none !important; } }',
                        'backend' => false,
					],
				],
				'scopy' => true,
			],
			'globalHideMobile' => [
				'type' => 'boolean',
				'default' => false,
				'style' => [
					(object) [
						'selector' => '@media (max-width: 1024px){.text-center{text-align: center;}}@media (max-width: 767px){ .edit-post-visual-editor {{PLUS_WRAP}},.editor-styles-wrapper {{PLUS_WRAP}}{display: block;opacity: .5;} }',
                        'backend' => true,
					],
                    (object) [
						'selector' => '@media (max-width: 1024px){.text-center{text-align: center;}}@media (max-width: 767px){ {{PLUS_WRAP}}{ display:none !important; } }',
                        'backend' => false,
					],
				],
				'scopy' => true,
			],
			
			'globalClasses' => [
				'type' => 'string',
				'default' => '',
				'scopy' => true,
			],
			'globalId' => [
				'type' => 'string',
				'default' => '',
			],
			'globalCustomCss' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '',
					],
				],
			],
			
			'globalAnim' => [
				'type' => 'object',
				'default' => [ 'md' => 'none' ],
				'scopy' => true,
			],
			'globalAnimDirect' => [
				'type' => 'object',
				'default' => [ 'md' => '' ],
				'scopy' => true,
			],
			'globalAnimDuration' => [
				'type' => 'string',
				'default' => 'normal',
				'scopy' => true,
			],
			'globalAnimCDuration' => [
				'type' => 'object',
				'default' => [ 'md' => '' ],
				'style' => [
					(object) [
						'selector' => '{{PLUS_BLOCK}}.tpgb_animated.tpgb-anim-dur-custom{-webkit-animation-duration: {{globalAnimCDuration}}s;animation-duration: {{globalAnimCDuration}}s;}',
					],
				],
				'scopy' => true,
			],
			'globalAnimDelay' => [
				'type' => 'object',
				'default' => [ 'md' => '' ],
				'style' => [
					(object) [
						'selector' => '{{PLUS_BLOCK}}.tpgb-view-animation{-webkit-animation-delay: {{globalAnimDelay}}s;animation-delay: {{globalAnimDelay}}s;}',
					],
				],
				'scopy' => true,
			],
			'globalAnimEasing' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'globalAnimEasing', 'relation' => '!=', 'value' => 'custom' ]],
						'selector' => '{{PLUS_BLOCK}}.tpgb-view-animation{animation-timing-function: {{globalAnimEasing}};}',
					],
				],
				'scopy' => true,
			],
			'globalAnimEasCustom' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_BLOCK}}.tpgb-view-animation{animation-timing-function: {{globalAnimEasCustom}};}',
					],
				],
				'scopy' => true,
			],
			
			'globalAnimOut' => [
				'type' => 'object',
				'default' => [ 'md' => 'none' ],
				'scopy' => true,
			],
			'globalAnimDirectOut' => [
				'type' => 'object',
				'default' => [ 'md' => '' ],
				'scopy' => true,
			],
			'globalAnimDurationOut' => [
				'type' => 'string',
				'default' => 'normal',
				'scopy' => true,
			],
			'globalAnimCDurationOut' => [
				'type' => 'object',
				'default' => [ 'md' => '' ],
				'style' => [
					(object) [
						'selector' => '{{PLUS_BLOCK}}.tpgb_animated_out.tpgb-anim-out-dur-custom{-webkit-animation-duration: {{globalAnimCDurationOut}}s;animation-duration: {{globalAnimCDurationOut}}s;}',
					],
				],
				'scopy' => true,
			],
			'globalAnimDelayOut' => [
				'type' => 'object',
				'default' => [ 'md' => '' ],
				'style' => [
					(object) [
						'selector' => '{{PLUS_BLOCK}}.tpgb-view-animation-out{-webkit-animation-delay: {{globalAnimDelayOut}}s;animation-delay: {{globalAnimDelayOut}}s;}',
					],
				],
				'scopy' => true,
			],
			'globalAnimEasingOut' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'globalAnimEasingOut', 'relation' => '!=', 'value' => 'custom' ]],
						'selector' => '{{PLUS_BLOCK}}.tpgb-view-animation-out{animation-timing-function: {{globalAnimEasingOut}};}',
					],
				],
				'scopy' => true,
			],
			'globalAnimEasCustomOut' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_BLOCK}}.tpgb-view-animation-out{animation-timing-function: {{globalAnimEasCustomOut}};}',
					],
				],
				'scopy' => true,
			],
			'globalPosition' => [
				'type' => 'object',
				'default' => [ 'md' => '','sm' => '','xs' => '' ],
				'style' => [
					(object) [
						'selector' => '{{PLUS_CLIENT_ID}}{ position : {{globalPosition}};width : unset }',
						'backend' => true,
					],
				],
			],
			'gloabhorizoOri' => [
				'type' => 'object',
				'default' => [ 'md' => 'left', 'sm' =>  '', 'xs' =>  '' ]
			],
			'glohoriOffset' => [
				'type' => 'object',
				'default' =>[ 
					'md' => '0',
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'condition' => [
							(object) [ 'key' => 'globalPosition', 'relation' => '==', 'value' => ['absolute' , 'fixed'] ],
							(object) [ 'key' => 'gloabhorizoOri', 'relation' => '==', 'value' => 'left' ]
						],
						'selector' => '{{PLUS_CLIENT_ID}}{ left : {{glohoriOffset}};right : auto; }',
						'backend' => true,
					],
					(object) [
						'condition' => [
							(object) [ 'key' => 'globalPosition', 'relation' => '==', 'value' => ['absolute' , 'fixed'] ],
							(object) [ 'key' => 'gloabhorizoOri', 'relation' => '==', 'value' => 'right' ]
						],
						'selector' => '{{PLUS_CLIENT_ID}}{ right : {{glohoriOffset}};left : auto; }',
						'backend' => true,
					],
				],
			],
			'gloabverticalOri' => [
				'type' => 'object',
				'default' => [ 'md' => 'top', 'sm' =>  '', 'xs' =>  '' ]
			],
			'gloverticalOffset' => [
				'type' => 'object',
				'default' => [ 
					'md' => '0',
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'condition' => [
							(object) [ 'key' => 'globalPosition', 'relation' => '==', 'value' => ['absolute' , 'fixed'] ],
							(object) [ 'key' => 'gloabverticalOri', 'relation' => '==', 'value' => 'top' ]
						],
						'selector' => '{{PLUS_CLIENT_ID}}{ top : {{gloverticalOffset}}; bottom : auto; }',
						'backend' => true,
					],
					(object) [
						'condition' => [
							(object) [ 'key' => 'globalPosition', 'relation' => '==', 'value' => ['absolute' , 'fixed'] ],
							(object) [ 'key' => 'gloabverticalOri', 'relation' => '==', 'value' => 'bottom' ]
						],
						'selector' => '{{PLUS_CLIENT_ID}} { bottom : {{gloverticalOffset}}; top : auto; }',
						'backend' => true,
					],
				],
			],
			'globalOverflow' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_BLOCK}} { overflow: {{globalOverflow}}; }',
					],
				],
			],
		];
		
		return $options;
	}
	
	/**
	 * Load Global Background Options
	 *
	 * @since 1.0.0
	 */
	public static function load_plusextras_options() {
		
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}
		$options = [
			'className' => [
				'type' => 'string',
				'default' => '',
			],
			'contentHoverEffect' => [
				'type' => 'boolean',
				'default' => false,	
				'scopy' => true,
			],
			'selectHoverEffect' => [
				'type' => 'string',
				'default' => '',	
				'scopy' => true,
			],
			
			'contentHoverColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'contentHoverEffect', 'relation' => '==', 'value' => true ],
							['key' => 'selectHoverEffect', 'relation' => '==', 'value' => 'float_shadow' ]],
						'selector' => '{{PLUS_BLOCK}}.tpgb_cnt_hvr_effect.cnt_hvr_float_shadow:before{background: -webkit-radial-gradient(center, ellipse, {{contentHoverColor}} 0%, rgba(60, 60, 60, 0) 70%);background: radial-gradient(ellipse at 50% 150%,{{contentHoverColor}} 0%, rgba(60, 60, 60, 0) 70%); }',
					],
					(object) [
						'condition' => [(object) ['key' => 'contentHoverEffect', 'relation' => '==', 'value' => true ],
							['key' => 'selectHoverEffect', 'relation' => '==', 'value' => 'grow_shadow' ]],
						'selector' => '{{PLUS_BLOCK}}.tpgb_cnt_hvr_effect.cnt_hvr_grow_shadow:hover {-webkit-box-shadow: 0 10px 10px -10px {{contentHoverColor}};-moz-box-shadow: 0 10px 10px -10px {{contentHoverColor}};box-shadow: 0 10px 10px -10px {{contentHoverColor}};}',
					],
					(object) [
						'condition' => [(object) ['key' => 'contentHoverEffect', 'relation' => '==', 'value' => true ],
							['key' => 'selectHoverEffect', 'relation' => '==', 'value' => 'shadow_radial' ]],
						'selector' => '{{PLUS_BLOCK}}.tpgb_cnt_hvr_effect.cnt_hvr_shadow_radial:before{background: -webkit-radial-gradient(center, ellipse at 50% 150%, {{contentHoverColor}} 0%, rgba(60, 60, 60, 0) 70%);background: radial-gradient(ellipse at 50% 150%,{{contentHoverColor}} 0%, rgba(60, 60, 60, 0) 70%); }{{PLUS_BLOCK}}.tpgb_cnt_hvr_effect.cnt_hvr_shadow_radial:after {background: -webkit-radial-gradient(50% -50%, ellipse, {{contentHoverColor}} 0%, rgba(0, 0, 0, 0) 80%);background: radial-gradient(ellipse at 50% -50%, {{contentHoverColor}} 0%, rgba(0, 0, 0, 0) 80%);}',
					],
				],
				'scopy' => true,
			],
		];
		
		if(has_filter('tpgb_display_option')) {
			$options = apply_filters('tpgb_display_option', $options);
		}
		
		return $options;
	}
	
	/**
	 * Load Global Background Options
	 *
	 * @since 1.0.0
	 */
	public static function load_plusButton_options() {
		
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}
		$options = [
			'extBtnshow' => [
				'type' => 'boolean',
				'default' => false,	
			],
			'extBtnStyle' => [
				'type' => 'string',
				'default' => 'style-7',	
			],
			'extBtnText' => [
				'type' => 'string',
				'default' => 'Have a Look',	
			],
			'extBtnUrl' => [
				'type'=> 'object',
				'default'=> [
					'url' => '#',
					'target' => '',
					'nofollow' => ''
				],
			],
			'extBtniconFont'  => [
				'type' => 'string' ,
				'default' => 'none',	
			],
			
			'extBtniconName' => [
				'type'=> 'string',
				'default'=> '',
			],
			'extBtniconPosition' => [
				'type'=> 'string',
				'default'=> 'after',
			],
			'extBtniconSpacing' => [
				'type' => 'object',
				'default' => [ 
					'md' => 5,
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'extBtnshow', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}} .button-link-wrap .button-before { margin-right: {{extBtniconSpacing}}; } {{PLUS_WRAP}} .button-link-wrap .button-after { margin-left: {{extBtniconSpacing}}; }',
					],
				],
				'scopy' => true,
			],
			'extBtniconSize' => [
				'type' => 'object',
				'default' => [ 
					'md' => '',
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'extBtnshow', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}} .button-link-wrap .btn-icon { font-size: {{extBtniconSize}}; }',
					],
				],
				'scopy' => true,
			],
			'extbtnSpace' => [
				'type' => 'object',
				'default' => (object) [ 
					'md' => '',
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'extBtnshow', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}} .tpgb-adv-button{ margin-top: {{extbtnSpace}}; }',
					],
				],
				'scopy' => true,
			],
			'extbtnbottomSpace' => [
				'type' => 'object',
				'default' => (object) [ 
					'md' => '',
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'extBtnshow', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}} .tpgb-adv-button{ margin-bottom : {{extbtnbottomSpace}}; }',
					],
				],
				'scopy' => true,
			],
			'extbtnPadding' => [
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
						'condition' => [(object) ['key' => 'extBtnshow', 'relation' => '==', 'value' =>true]],
						'selector' => '{{PLUS_WRAP}} .tpgb-adv-button.button-style-8 .button-link-wrap{ padding: {{extbtnPadding}}; }',
					],
				],
				'scopy' => true,
			],
			'extbtnTypo' => [
				'type'=> 'object',
				'default'=> (object) [
					'openTypography' => 0,
					'size' => [ 'md' => '', 'unit' => 'px' ],
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'extBtnshow', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}} .tpgb-adv-button .button-link-wrap',
					],
				],
				'scopy' => true,
			],
			'extbtnTextColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'extBtnshow', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}} .tpgb-adv-button .button-link-wrap{ color: {{extbtnTextColor}}; }',
					],
					(object) [
						'condition' => [
							(object) ['key' => 'extBtnshow', 'relation' => '==', 'value' => true ],
							['key' => 'extBtnStyle', 'relation' => '==', 'value' => 'style-7']
						],
						'selector' => '{{PLUS_WRAP}} .tpgb-adv-button.button-style-7 .button-link-wrap:after{ border-color: {{extbtnTextColor}}; }',
					],
				],
				'scopy' => true,
			],
			'extbtnThoverColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'extBtnshow', 'relation' => '==', 'value' =>  true]],
						'selector' => '{{PLUS_WRAP}} .tpgb-adv-button .button-link-wrap:hover{ color: {{extbtnThoverColor}}; }',
					],
				],
				'scopy' => true,
			],
			'extbtnNormalB' => [
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
						'condition' => [(object) ['key' => 'extBtnshow', 'relation' => '==', 'value' => true ], ['key' => 'extBtnStyle', 'relation' => '==', 'value' => 'style-8' ]],
						'selector' => '{{PLUS_WRAP}} .tpgb-adv-button.button-style-8 .button-link-wrap',
					],
				],
				'scopy' => true,
			],
			'extbtnBRadius' => [
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
						'condition' => [(object) ['key' => 'extBtnshow', 'relation' => '==', 'value' => true ], ['key' => 'extBtnStyle', 'relation' => '==', 'value' => 'style-8' ]],
						'selector' => '{{PLUS_WRAP}} .tpgb-adv-button.button-style-8 .button-link-wrap{border-radius: {{extbtnBRadius}};}',
					],
				],
				'scopy' => true,
			],
			'extbtnBG' => [
				'type' => 'object',
				'default' => (object) [
					'openBg'=> 0,
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'extBtnshow', 'relation' => '==', 'value' => true ], ['key' => 'extBtnStyle', 'relation' => '==', 'value' => 'style-8' ]],
						'selector' => '{{PLUS_WRAP}} .tpgb-adv-button.button-style-8 .button-link-wrap',
					],
				],
				'scopy' => true,
			],
			'extbtnHvrB' => [
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
						'condition' => [(object) ['key' => 'extBtnshow', 'relation' => '==', 'value' => true ], ['key' => 'extBtnStyle', 'relation' => '==', 'value' => 'style-8' ]],
						'selector' => '{{PLUS_WRAP}} .tpgb-adv-button.button-style-8 .button-link-wrap:hover',
					],
				],
				'scopy' => true,
			],
			'extbtnHvrBRadius' => [
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
						'condition' => [(object) ['key' => 'extBtnshow', 'relation' => '==', 'value' => true ], ['key' => 'extBtnStyle', 'relation' => '==', 'value' => 'style-8' ]],
						'selector' => '{{PLUS_WRAP}} .tpgb-adv-button.button-style-8 .button-link-wrap:hover{border-radius: {{extbtnHvrBRadius}};}',
					],
				],
				'scopy' => true,
			],
			'extbtnHvrBG' => [
				'type' => 'object',
				'default' => (object) [
					'openBg'=> 0,
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'extBtnshow', 'relation' => '==', 'value' => true ], ['key' => 'extBtnStyle', 'relation' => '==', 'value' => 'style-8' ]],
						'selector' => '{{PLUS_WRAP}} .tpgb-adv-button.button-style-8 .button-link-wrap:hover',
					],
				],
				'scopy' => true,
			],
			'extbtnShadow' => [
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
						'condition' => [(object) ['key' => 'extBtnshow', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}} .tpgb-adv-button .button-link-wrap',
					],
				],
				'scopy' => true,
			],
			'hoverextbtnShadow' => [
				'type' => 'object',
				'default' => (object) [
					'horizontal' => '',
					'vertical' => '',
					'blur' => '',
					'spread' => '',
					'color' => "rgba(0,0,0,0.27)",
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'extBtnshow', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}} .tpgb-adv-button .button-link-wrap:hover',
					],
				],
				'scopy' => true,
			],
	];
		
		return $options;
	}

	public static function load_plusButton_saves($attributes) {
		
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}
		if(empty($attributes)){
			return;
		}
		$extBtnshow = (!empty($attributes['extBtnshow'])) ? $attributes['extBtnshow'] : false;
		$extBtnStyle = (!empty($attributes['extBtnStyle'])) ? $attributes['extBtnStyle'] : 'style-8';
		$extBtnText = (!empty($attributes['extBtnText'])) ? $attributes['extBtnText'] : '';
		$extBtnUrl = (!empty($attributes['extBtnUrl'])) ? $attributes['extBtnUrl'] : '';
		$extBtntarget = (!empty($attributes['extBtnUrl']['target'])) ? ' target="_blank"' : '';
		$extBtnrel = (!empty($attributes['extBtnUrl']['nofollow'])) ? ' rel="nofollow" ' : '';
		$extBtniconFont = (!empty($attributes['extBtniconFont'])) ? $attributes['extBtniconFont'] : '';
		$extBtniconName = (!empty($attributes['extBtniconName'])) ? $attributes['extBtniconName'] : '';
		$extBtniconPosition = (!empty($attributes['extBtniconPosition'])) ? $attributes['extBtniconPosition'] : 'after';
		$IBoxLinkTgl = (!empty($attributes['IBoxLinkTgl'])) ? $attributes['IBoxLinkTgl'] : false;
		
			$output ='';
			$output .='<div class="tpgb-adv-button button-'.esc_attr($extBtnStyle).'">';
				if(!empty($IBoxLinkTgl)){
					$output .= '<div class="button-link-wrap">';
				}else{
					$link_attr = Tp_Blocks_Helper::add_link_attributes($extBtnUrl);

                    $extUrl = '';
                    if(class_exists('Tpgbp_Pro_Blocks_Helper')){    
                        $extUrl = (isset($attributes['extBtnUrl']['dynamic'])) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_repeat_url($attributes['extBtnUrl']) : (!empty($attributes['extBtnUrl']['url']) ? $attributes['extBtnUrl']['url'] : '');
                    }else{
                        $extUrl = (!empty($attributes['extBtnUrl']['url']) ? $attributes['extBtnUrl']['url'] : '');
                    }
					$output .= '<a class="button-link-wrap"  href="'.(!empty($extUrl) ? esc_url($extUrl)  : '').'"  '.$extBtntarget.' '.$extBtnrel.'  '.$link_attr.'>';
				}
					if($extBtnStyle == 'style-8'){
						if($extBtniconPosition == 'before'){
							(($extBtniconFont == 'font_awesome' && !empty($extBtniconName)  ) ? $output .= '<span class="btn-icon button-'.esc_attr($extBtniconPosition).'"><i class="'.esc_attr($extBtniconName).' "></i></span>' : '');
							$output .= wp_kses_post($extBtnText);
						}else{
							$output .= wp_kses_post($extBtnText);
							(($extBtniconFont == 'font_awesome' && !empty($extBtniconName)) ? $output .= '<span class="btn-icon button-'.esc_attr($extBtniconPosition).'"><i class="'.esc_attr($extBtniconName).' "></i></span>' : '');
						}
					}else{
						$output .= wp_kses_post($extBtnText);
						$output .= '<span class="button-arrow"> ';
							if($extBtnStyle == 'style-7'){
								$output .= '<span class="btn-right-arrow"><i class="fas fa-chevron-right"></i></span>';
							}
							if($extBtnStyle == 'style-9'){
								$output .= '<i class="btn-show fa fa-chevron-right" aria-hidden="true"></i>';
								$output .= '<i class="btn-hide fa fa-chevron-right" aria-hidden="true"></i>';
							}
						$output .= '</span>';
					}
				if(!empty($IBoxLinkTgl)){
					$output .= '</div>';
				}else{
					$output .= '</a>';
				}
			$output .='</div>';

		return $output;
	}
	
	public static function tpgbAnimationDevice($globalAnim='', $AnimDirect='',$device=''){
		$animationVal = '';
		if($globalAnim=='fadeIn'){
			$animationVal .= (($AnimDirect[$device]==='' || $AnimDirect[$device]==='default') ? 'fadeIn' : 'fadeIn'.$AnimDirect[$device]);
		}else if($globalAnim=='slideIn'){
			$animationVal .= (($AnimDirect[$device]==='' || $AnimDirect[$device]==='default') ? 'slideInDown' : 'slideIn'.$AnimDirect[$device]);
		}else if($globalAnim=='zoomIn'){
			$animationVal .= (($AnimDirect[$device]==='' || $AnimDirect[$device]==='default') ? 'zoomIn' : 'zoomIn'.$AnimDirect[$device]);
		}else if($globalAnim=='rotateIn'){
			$animationVal .= (($AnimDirect[$device]==='' || $AnimDirect[$device]==='default') ? 'rotateIn' : 'rotateIn'.$AnimDirect[$device]);
		}else if($globalAnim=='flipIn'){
			$animationVal .= (($AnimDirect[$device]==='' || $AnimDirect[$device]==='default') ? 'flipInX' : 'flipIn'.$AnimDirect[$device]);
		}else if($globalAnim=='lightSpeedIn'){
			$animationVal .= (($AnimDirect[$device]==='' || $AnimDirect[$device]==='default') ? 'lightSpeedInLeft' : 'lightSpeedIn'.$AnimDirect[$device]);
		}else if($globalAnim=='seekers'){
			$animationVal .= (($AnimDirect[$device]==='' || $AnimDirect[$device]==='default') ? 'bounce' : $AnimDirect[$device]);
		}else if($globalAnim=='rollIn'){
			$animationVal .= 'rollIn';
		}
		
		return $animationVal;
	}
	
	public static function block_Wrap_Render($attributes, $content=''){
		
		if ( ! function_exists( 'register_block_type' ) ) {
			return $content;
		}
		if(empty($attributes) || empty($attributes['block_id']) || empty($content)){
			return $content;
		}
		$attributes = json_decode(json_encode($attributes), true);
		
		$animationEffect = false;
		$animClass = $animAttr = $animDesktop = $animTablet = $animMobile = '';
		$animSettings = [];
		if( (!empty($attributes['globalAnim'])) ){
			if(!empty($attributes['globalAnim']['md']) && $attributes['globalAnim']['md']!='none'){
				$animationEffect = true;
				$globalAnim = $attributes['globalAnim']['md'];
				if( !empty($attributes['globalAnimDirect']) ){
					$animDesktop = self::tpgbAnimationDevice($globalAnim, $attributes['globalAnimDirect'], 'md');
				}
			}
			
			if(!empty($attributes['globalAnim']['sm']) && $attributes['globalAnim']['sm']!='none'){
				$animationEffect = true;
				$globalAnim = $attributes['globalAnim']['sm'];
				if( !empty($attributes['globalAnimDirect']) ){
					$animTablet = self::tpgbAnimationDevice($globalAnim, $attributes['globalAnimDirect'], 'sm');
				}
			}
			
			if(!empty($attributes['globalAnim']['xs']) && $attributes['globalAnim']['xs']!='none'){
				$animationEffect = true;
				$globalAnim = $attributes['globalAnim']['xs'];
				if( !empty($attributes['globalAnimDirect']) ){
					$animMobile = self::tpgbAnimationDevice($globalAnim, $attributes['globalAnimDirect'], 'xs');
				}
			}
			
			if(!empty($animationEffect)){
				
				if(!empty($attributes['globalAnimDuration']) && $attributes['globalAnimDuration']=='custom'){
					$animClass .= ' tpgb-anim-dur-custom';
				}else if(!empty($attributes['globalAnimDuration'])){
					$animClass .= ' tpgb-anim-dur-'.esc_attr($attributes['globalAnimDuration']);
				}
				
				$animSettings['anime']['md'] = !empty($animDesktop) ? $animDesktop : '';
				$animSettings['anime']['sm'] = !empty($animTablet) ? $animTablet : '';
				$animSettings['anime']['xs'] = !empty($animMobile) ? $animMobile : '';
			}
		}
		
		$animationOutEffect = ['check' => false ];
		if( (!empty($attributes['globalAnimOut'])) ){
			
			if(has_filter('tpgb_globalAnimOut_filter')) {
				$animationOutEffect = apply_filters('tpgb_globalAnimOut_filter', $animationOutEffect, $attributes);
			}
			
			if(!empty($animationOutEffect['check'])){
				
				if(!empty($attributes['globalAnimDurationOut']) && $attributes['globalAnimDurationOut']=='custom'){
					$animClass .= ' tpgb-anim-out-dur-custom';
				}else if(!empty($attributes['globalAnimDurationOut'])){
					$animClass .= ' tpgb-anim-out-dur-'.$attributes['globalAnimDurationOut'];
				}
				
				$animSettings['animeOut']['md'] = (isset($animationOutEffect['md']) && !empty($animationOutEffect['md'])) ? $animationOutEffect['md'] : '';
				$animSettings['animeOut']['sm'] = (isset($animationOutEffect['sm']) && !empty($animationOutEffect['sm']) ) ? $animationOutEffect['sm'] : '';
				$animSettings['animeOut']['xs'] = (isset($animationOutEffect['xs']) && !empty($animationOutEffect['xs']) ) ? $animationOutEffect['xs'] : '';
			}
		}
		
		if(!empty($animationEffect) || !empty($animationOutEffect['check']) ){
			$animClass .= ' tpgb-view-animation';
			$animAttr .= 'data-animationSetting=\'' .htmlspecialchars(json_encode($animSettings), ENT_QUOTES, 'UTF-8'). '\'';
		}
		
		if(!empty($attributes['PlusMouseParallax']) && !empty($attributes['PlusMouseParallax']['tpgbReset'])){
			$animClass .= ' tpgb-mouse-parallax';
		}
		
		$outputWrap = '';
		
		$wrapClass = '';
		if( (!empty($attributes['globalClasses'])) ){
			$wrapClass .= $attributes['globalClasses'];
		}
		if( (isset($attributes['layout']) && !empty($attributes['layout'])) || (isset($attributes['telayout']) && !empty($attributes['telayout'])) ){
			$wrapClass .= ' tpgb-wrap-fw';
		}
		if( !isset($attributes['contentWidth']) ){
			$wrapClass .= (!empty($attributes['align'])) ? ' align'.$attributes['align'] : '';
		}
		$wrapID = '';
		if( (!empty($attributes['globalId'])) ){
			$wrapID .= 'id="'.esc_attr($attributes['globalId']).'"';
		}
		
		$hasWrapper =false;
		if(!empty($wrapID) || !empty($wrapClass) || !empty($attributes['globalCustomCss']) || !empty($animationEffect) || !empty($animationOutEffect['check']) || ( isset($attributes['globalflexCss']) && !empty($attributes['globalflexCss']['tpgbReset']) ) || ( !empty($attributes['globalPosition']) && ( ( isset($attributes['globalPosition']['md']) && !empty($attributes['globalPosition']['md']) ) || ( isset($attributes['globalPosition']['sm']) && !empty($attributes['globalPosition']['sm']) ) || ( isset($attributes['globalPosition']['xs']) && !empty($attributes['globalPosition']['xs']) ) ) ) ){
			$hasWrapper = true;
			if(isset($attributes['contentWidth']) && !empty($attributes['contentWidth'])){
				$wrapClass .= ' alignfull';
			}
		}
		
		if(has_filter('tpgb_hasWrapper')) {
			$hasWrapper = apply_filters('tpgb_hasWrapper', $hasWrapper, $attributes);
		}
		
		$wrapperAttr = '';
		if(has_filter('tpgb_globalWrapAttr')){
			$wrapperAttr = apply_filters('tpgb_globalWrapAttr', $wrapperAttr, $attributes);
		}
		if( !empty($hasWrapper) ){
		
			if(has_filter('tpgb_globalWrapClass')){
				$wrapClass = apply_filters('tpgb_globalWrapClass', $wrapClass, $attributes);
			}
			
			if( isset($attributes['globalPosition']['md']) && !empty($attributes['globalPosition']['md']) ){
				$wrapClass .= ' tpgb-position-'.esc_attr($attributes['globalPosition']['md']);
			} 
			if( isset($attributes['globalPosition']['sm']) && !empty($attributes['globalPosition']['sm']) ){
				$wrapClass .= ' tpgb-tab-position-'.esc_attr($attributes['globalPosition']['sm']);
			}else if( isset($attributes['globalPosition']['md']) && !empty($attributes['globalPosition']['md']) ) {
				$wrapClass .= ' tpgb-tab-position-'.esc_attr($attributes['globalPosition']['md']);
			}

			if( isset($attributes['globalPosition']['xs']) && !empty($attributes['globalPosition']['xs']) ){
				$wrapClass .= ' tpgb-mobile-position-'.esc_attr($attributes['globalPosition']['xs']);
			}else if( isset($attributes['globalPosition']['sm']) && !empty($attributes['globalPosition']['sm']) ){
				$wrapClass .= ' tpgb-mobile-position-'.esc_attr($attributes['globalPosition']['sm']);
			}else if( isset($attributes['globalPosition']['md']) && !empty($attributes['globalPosition']['md']) ) {
				$wrapClass .= ' tpgb-mobile-position-'.esc_attr($attributes['globalPosition']['md']);
			}

			$outputWrap .= '<div '.$wrapID.' class="tpgb-wrap-'.esc_attr($attributes['block_id']).' '.esc_attr($wrapClass).' '.esc_attr($animClass).'" '.$animAttr.' '.$wrapperAttr.'>';
				ob_start();
				do_action('tpgb_wrapper_inner_before', $attributes );
				$outputWrap .= ob_get_contents();
				ob_end_clean();
				
				$outputWrap .= $content;
				
				ob_start();
				do_action('tpgb_wrapper_inner_after', $attributes );
				$outputWrap .= ob_get_contents();
				ob_end_clean();
				
			$outputWrap .= '</div>';

		}else{
			$outputWrap .= $content;
		}

		if( isset($attributes[ 'tpgbDisrule' ]) && !empty($attributes[ 'tpgbDisrule' ]) && class_exists('Tpgb_Display_Conditions_Rules') ){
			$tpgb_condition_rules = Tpgb_Display_Conditions_Rules::get_instance();
			if(!empty($tpgb_condition_rules)){
			
				$checkDis = $tpgb_condition_rules::tpgb_rules_actions($attributes['block_id'],$attributes);
				
				if($checkDis === false){
					$outputWrap = '';
				}
			}
		}
		
		if( (isset($attributes['className']) && !empty($attributes['className']) && strpos($attributes['className'], 'nxt-lazy-load') !== false) || (!empty($attributes['globalClasses'])  && strpos($attributes['globalClasses'], 'nxt-lazy-load') !== false ) ){
			$outputWrap = '<div class="tpgb-lazy-render" data-bid="'.esc_attr($attributes['block_id']).'"><noscript>'.$outputWrap.'</noscript></div>';
		}else{
			$outputWrap = $outputWrap;
		}

		return $outputWrap;
	}
	
	/*
	 * Row Block Render Display Rules
	 * @since 1.2.0
	 */
	public static function block_row_conditional_render($attributes,$output){
		if( isset($attributes[ 'tpgbDisrule' ]) && !empty($attributes[ 'tpgbDisrule' ]) && class_exists('Tpgb_Display_Conditions_Rules') ){
			$tpgb_condition_rules = Tpgb_Display_Conditions_Rules::get_instance();
			if(!empty($tpgb_condition_rules)){
				$uid = (isset($attributes['block_id']) && !empty($attributes['block_id']) ) ? $attributes['block_id'] : uniqid('block');
				$checkDis =  $tpgb_condition_rules::tpgb_rules_actions($uid,$attributes);
				if($checkDis === false){
					$output = '';
				}
			}
		}
		return $output;
	}
	
	public static function render_block_default_attributes(){
		
		return [
			'tpgbDisrule' => false,
			'disRule' => 'all',
			'displayRules' => [ 
				(object)[ "_key" => '0','displayKey' => 'authentication', 'tpgb_authentication_value' => 'authenticated', 'tpgb_role_value' => 'administrator', 'tpgb_os_value' => 'iphone', 'tpgb_browser_value' => 'ie', 'assigOpr' => 'is', 'tpgb_startdate_value' => '2021-10-13', 'tpgb_enddate_value' => '2021-10-15', 'tpgb_time_value' => '12:00', 'tpgb_day_value' => '[]' ,'tpgb_post_type_value' => '[]','tpgb_page_value' => '[]' ,'tpgb_post_value' => '[]' ,'tpgb_taxonomy_archive_value' => '[]', 'tpgb_author_archive_value' => '[]', 'tpgb_post_type_archive_value' => '[]', 'tpgb_static_page_value' => 'home', 'tpgb_date_archive_value' => 'day', 'tpgb_search_results_value' => '' , 'tpgb_single_terms_value' => '[]' , 'tpgb_single_archive_value' => '[]' ]
			],
		];
	}

	/*
	 * Merge Attributes Options Block JSON
	 * @since V4.0.0
	 * */
	public static function merge_options_json($block_path= '', $render_callback = '', $adv_opt = true, $carousel_opt = false, $plus_button = false){

		if(empty($block_path)){
			return;
		}

		$block_data = $block_path .'/block.json';
		if(is_string( $block_data ) && file_exists( $block_data )){
			$metadata_file = ( ! str_ends_with( $block_data, 'block.json' ) ) ? trailingslashit( $block_data ) . 'block.json' :	$block_data;
			$metadata = wp_json_file_decode( $metadata_file, array( 'associative' => true ) );
			
			//carousel options
			if(!empty($carousel_opt) && $carousel_opt===true){
				if(!empty(self::$merge_options) && isset(self::$merge_options['global-carousel']) && !empty(self::$merge_options['global-carousel'])){
					$metadata['attributes'] = array_merge( self::$merge_options['global-carousel'] , $metadata['attributes'] );
				}else{
					$option_path = __DIR__ . '/global-carousel-option.json';
					if (is_string($option_path) && file_exists($option_path)) {
						$option_data = wp_json_file_decode($option_path, ['associative' => true]);
						if(!empty($option_data)){
							self::$merge_options['global-carousel'] = $option_data;
						}
						if(!empty($option_data) && !empty($metadata) && isset($metadata['attributes'])){
							$metadata['attributes'] = array_merge( $option_data , $metadata['attributes'] );
						}
					}
				}

				if(defined('TPGBP_VERSION') && defined('TPGBP_PATH')){
					if(!empty(self::$merge_options) && isset(self::$merge_options['global-pro-carousel']) && !empty(self::$merge_options['global-pro-carousel'])){
						$metadata['attributes'] = array_merge( self::$merge_options['global-pro-carousel'] , $metadata['attributes'] );
					}else{
						$option_path = TPGBP_PATH.'classes/global-options/global-carousel-option.json';
						if (is_string($option_path) && file_exists($option_path)) {
							$option_data = wp_json_file_decode($option_path, ['associative' => true]);
							if(!empty($option_data)){
								self::$merge_options['global-pro-carousel'] = $option_data;
							}
							if(!empty($option_data) && !empty($metadata) && isset($metadata['attributes'])){
								$metadata['attributes'] = array_merge( $option_data , $metadata['attributes']);
							}
						}
					}
				}
			}

			//Plus button Options
			if(!empty($plus_button) && $plus_button===true){
				if(!empty(self::$merge_options) && isset(self::$merge_options['global-button']) && !empty(self::$merge_options['global-button'])){
					$metadata['attributes'] = array_merge( $metadata['attributes'],self::$merge_options['global-button'] );
				}else{
					$option_path = __DIR__ . '/global-button-option.json';
					if (is_string($option_path) && file_exists($option_path)) {
						$option_data = wp_json_file_decode($option_path, ['associative' => true]);
						if(!empty($option_data)){
							self::$merge_options['global-button'] = $option_data;
						}
						if(!empty($option_data) && !empty($metadata) && isset($metadata['attributes'])){
							$metadata['attributes'] = array_merge($metadata['attributes'], $option_data);
						}
					}
				}
			}

			//advanced tab options
			if(!empty($adv_opt) && $adv_opt===true){
				$global_options = [
					'global-option.json',
					'global-position-option.json',
					'global-plus-option.json',
					'global-display-rules.json'
				];
		
				if(!empty(self::$global_options)){
					if(!empty($metadata) && isset($metadata['attributes'])){
						$metadata['attributes'] = array_merge($metadata['attributes'], self::$global_options);
					}
				}else{
					foreach ($global_options as $option) {
						$option_path = __DIR__ . '/' . $option;
						if (is_string($option_path) && file_exists($option_path)) {
							$option_data = wp_json_file_decode($option_path, ['associative' => true]);
							if(!empty($option_data)){
								self::$global_options = array_merge(self::$global_options, $option_data);
							}
							if(!empty($option_data) && !empty($metadata) && isset($metadata['attributes'])){
								$metadata['attributes'] = array_merge($metadata['attributes'], $option_data);
							}
						}
					}
				}

				if(defined('TPGBP_VERSION') && defined('TPGBP_PATH')){
					$global_pro_opt = [
						'global-magic-scroll-option.json',
						'global-plus-extras-option.json',
					];

					if(!empty(self::$global_pro_opt)){
						if(!empty($metadata) && isset($metadata['attributes'])){
							$metadata['attributes'] = array_merge($metadata['attributes'], self::$global_pro_opt);
						}
					}else{

						foreach ($global_pro_opt as $option) {
							$option_path = TPGBP_PATH.'classes/global-options/' . $option;
							if (is_string($option_path) && file_exists($option_path)) {
								$option_data = wp_json_file_decode($option_path, ['associative' => true]);
								if(!empty($option_data)){
									self::$global_pro_opt = array_merge(self::$global_pro_opt, $option_data);
								}
								if(!empty($option_data) && !empty($metadata) && isset($metadata['attributes'])){
									$metadata['attributes'] = array_merge($metadata['attributes'], $option_data);
								}
							}
						}
					}
				}
			}
			
			//render block php
			if(!empty($metadata) && !empty($render_callback)){
				$metadata['render_callback'] = $render_callback;
			}
			return $metadata;
		}

		return false;
	}
}

Tpgb_Blocks_Global_Options::get_instance();