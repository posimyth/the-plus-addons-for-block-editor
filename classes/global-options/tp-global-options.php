<?php
/**
 * TPGB Global Options
 *
 * @package TPGB
 */

// phpcs:disable WordPress.Files.FileName

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

	/**
	 * Merge options.
	 *
	 * @var array
	 */
	public static $merge_options = array();

	/**
	 * Global options.
	 *
	 * @var array
	 */
	public static $global_options = array();

	/**
	 * Global pro opt.
	 *
	 * @var array
	 */
	public static $global_pro_opt = array();

	/**
	 *  Initiator
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Carousel Options
	 *
	 * @since 1.1.2
	 */
	public static function carousel_options() {

		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		$options = array(
			'sliderMode'           => array(
				'type'    => 'string',
				'default' => 'horizontal',
				'scopy'   => true,
			),
			'slideSpeed'           => array(
				'type'    => 'string',
				'default' => 1500,
				'scopy'   => true,
			),
			'slideColumns'         => array(
				'type'    => 'object',
				'default' => array(
					'md' => 1,
					'sm' => 1,
					'xs' => 1,
				),
				'scopy'   => true,
			),
			'initialSlide'         => array(
				'type'    => 'number',
				'default' => 0,
				'scopy'   => true,
			),
			'slideScroll'          => array(
				'type'    => 'object',
				'default' => array( 'md' => 1 ),
				'scopy'   => true,
			),
			'slideColumnSpace'     => array(
				'type'    => 'object',
				'default' => (object) array(
					'md'   => array(
						'top'    => '',
						'right'  => '',
						'bottom' => '',
						'left'   => '',
					),
					'unit' => 'px',
				),
				'style'   => array(
					(object) array(
						'selector' => '{{PLUS_WRAP}} .splide__list .splide__slide {padding: {{slideColumnSpace}};}',
					),
				),
				'scopy'   => true,
			),
			'slideDraggable'       => array(
				'type'    => 'object',
				'default' => array( 'md' => true ),
				'scopy'   => true,
			),
			'slideInfinite'        => array(
				'type'    => 'boolean',
				'default' => false,
				'scopy'   => true,
			),
			'slideHoverPause'      => array(
				'type'    => 'boolean',
				'default' => false,
				'scopy'   => true,
			),
			'slideAutoplay'        => array(
				'type'    => 'boolean',
				'default' => true,
				'scopy'   => true,
			),
			'slideAutoplaySpeed'   => array(
				'type'    => 'string',
				'default' => 1500,
				'scopy'   => true,
			),
			'showDots'             => array(
				'type'    => 'object',
				'default' => array( 'md' => true ),
				'scopy'   => true,
			),
			'dotsStyle'            => array(
				'type'    => 'string',
				'default' => 'style-1',
				'scopy'   => true,
			),
			'dotsBorderColor'      => array(
				'type'    => 'string',
				'default' => '',
				'style'   => array(
					(object) array(
						'condition' => array(
							(object) array(
								'key'      => 'dotsStyle',
								'relation' => '==',
								'value'    => 'style-1',
							),
						),
						'selector'  => '{{PLUS_WRAP}}.dots-style-1 ul.splide__pagination li button.splide__pagination__page{-webkit-box-shadow:inset 0 0 0 8px {{dotsBorderColor}};-moz-box-shadow: inset 0 0 0 8px {{dotsBorderColor}};box-shadow: inset 0 0 0 8px {{dotsBorderColor}};} {{PLUS_WRAP}}.dots-style-1 ul.splide__pagination li button.splide__pagination__page.is-active{-webkit-box-shadow:inset 0 0 0 1px {{dotsBorderColor}};-moz-box-shadow: inset 0 0 0 1px {{dotsBorderColor}};box-shadow: inset 0 0 0 1px {{dotsBorderColor}};}{{PLUS_WRAP}}.dots-style-1 ul.splide__pagination li button.splide__pagination__page{background: transparent;color: {{dotsBorderColor}};}',
					),
				),
				'scopy'   => true,
			),
			'dotsTopSpace'         => array(
				'type'    => 'object',
				'default' => array(
					'md'   => 0,
					'sm'   => 0,
					'xs'   => 0,
					'unit' => 'px',
				),
				'style'   => array(
					(object) array(
						'condition' => array(
							(object) array(
								'key'      => 'showDots',
								'relation' => '==',
								'value'    => true,
							),
						),
						'selector'  => '{{PLUS_WRAP}} .splide__pagination{ margin-top: {{dotsTopSpace}} !important;}',
					),
				),
				'scopy'   => true,
			),
			'slideHoverDots'       => array(
				'type'    => 'boolean',
				'default' => false,
				'scopy'   => true,
			),
			'showArrows'           => array(
				'type'    => 'object',
				'default' => array( 'md' => false ),
				'scopy'   => true,
			),
			'arrowsStyle'          => array(
				'type'    => 'string',
				'default' => 'style-1',
				'scopy'   => true,
			),
			'arrowsPosition'       => array(
				'type'    => 'string',
				'default' => 'top-right',
				'scopy'   => true,
			),
			'arrowsBgColor'        => array(
				'type'    => 'string',
				'default' => '',
				'style'   => array(
					(object) array(
						'condition' => array(
							(object) array(
								'key'      => 'arrowsStyle',
								'relation' => '==',
								'value'    => 'style-1',
							),
							(object) array(
								'key'      => 'showArrows',
								'relation' => '==',
								'value'    => true,
							),
						),
						'selector'  => '{{PLUS_WRAP}} .splide__arrows.style-1 .splide__arrow.style-1{background:{{arrowsBgColor}};}',
					),
					(object) array(
						'condition' => array(
							(object) array(
								'key'      => 'arrowsStyle',
								'relation' => '==',
								'value'    => array( 'style-3', 'style-4', 'style-6' ),
							),
							(object) array(
								'key'      => 'showArrows',
								'relation' => '==',
								'value'    => true,
							),
						),
						'selector'  => '{{PLUS_WRAP}} .splide__arrows.style-3 .splide__arrow.style-3 .icon-wrap, {{PLUS_WRAP}} .splide__arrows.style-6 .splide__arrow.style-6:before{background:{{arrowsBgColor}};} {{PLUS_WRAP}} .splide__arrows.style-4 .splide__arrow.style-4 .icon-wrap{border-color:{{arrowsBgColor}}}',
					),
				),
				'scopy'   => true,
			),
			'arrowsIconColor'      => array(
				'type'    => 'string',
				'default' => '',
				'style'   => array(
					(object) array(
						'condition' => array(
							(object) array(
								'key'      => 'arrowsStyle',
								'relation' => '==',
								'value'    => 'style-1',
							),
							(object) array(
								'key'      => 'showArrows',
								'relation' => '==',
								'value'    => true,
							),
						),
						'selector'  => '{{PLUS_WRAP}} .splide__arrows.style-1 .splide__arrow.style-1:before{color:{{arrowsIconColor}};}',
					),
					(object) array(
						'condition' => array(
							(object) array(
								'key'      => 'arrowsStyle',
								'relation' => '==',
								'value'    => array( 'style-2', 'style-3', 'style-4', 'style-5', 'style-6' ),
							),
							(object) array(
								'key'      => 'showArrows',
								'relation' => '==',
								'value'    => true,
							),
						),
						'selector'  => '{{PLUS_WRAP}} .splide__arrows.style-3 .splide__arrow.style-3 .icon-wrap,{{PLUS_WRAP}} .splide__arrows.style-4 .splide__arrow.style-4 .icon-wrap,{{PLUS_WRAP}} .splide__arrows.style-6 .splide__arrow.style-6 .icon-wrap svg{color:{{arrowsIconColor}};}{{PLUS_WRAP}} .splide__arrows.style-2 .splide__arrow.style-2 .icon-wrap:before,{{PLUS_WRAP}} .splide__arrows.style-2 .splide__arrow.style-2 .icon-wrap:after,{{PLUS_WRAP}} .splide__arrows.style-5 .splide__arrow.style-5 .icon-wrap:before,{{PLUS_WRAP}} .splide__arrows.style-5 .splide__arrow.style-5 .icon-wrap:after{background:{{arrowsIconColor}};}',
					),
				),
				'scopy'   => true,
			),
			'arrowsHoverBgColor'   => array(
				'type'    => 'string',
				'default' => '',
				'style'   => array(
					(object) array(
						'condition' => array(
							(object) array(
								'key'      => 'arrowsStyle',
								'relation' => '==',
								'value'    => 'style-1',
							),
							(object) array(
								'key'      => 'showArrows',
								'relation' => '==',
								'value'    => true,
							),
						),
						'selector'  => '{{PLUS_WRAP}} .splide__arrows.style-1 .splide__arrow.style-1:hover{background:{{arrowsHoverBgColor}};}',
					),
					(object) array(
						'condition' => array(
							(object) array(
								'key'      => 'arrowsStyle',
								'relation' => '==',
								'value'    => array( 'style-2', 'style-3', 'style-4' ),
							),
							(object) array(
								'key'      => 'showArrows',
								'relation' => '==',
								'value'    => true,
							),
						),
						'selector'  => '{{PLUS_WRAP}} .splide__arrows.style-2 .splide__arrow.style-2:hover:before,{{PLUS_WRAP}} .splide__arrows.style-3 .splide__arrow.style-3:hover .icon-wrap{background:{{arrowsHoverBgColor}};}{{PLUS_WRAP}} .splide__arrows.style-4 .splide__arrow.style-4:hover:before,{{PLUS_WRAP}} .splide__arrows.style-4 .splide__arrow.style-4:hover .icon-wrap{border-color:{{arrowsHoverBgColor}};}',
					),
				),
				'scopy'   => true,
			),
			'arrowsHoverIconColor' => array(
				'type'    => 'string',
				'default' => '',
				'style'   => array(
					(object) array(
						'condition' => array(
							(object) array(
								'key'      => 'arrowsStyle',
								'relation' => '==',
								'value'    => 'style-1',
							),
							(object) array(
								'key'      => 'showArrows',
								'relation' => '==',
								'value'    => true,
							),
						),
						'selector'  => '{{PLUS_WRAP}} .splide__arrows.style-1 .splide__arrow.style-1:hover:before{color:{{arrowsHoverIconColor}};}',
					),
					(object) array(
						'condition' => array(
							(object) array(
								'key'      => 'arrowsStyle',
								'relation' => '==',
								'value'    => array( 'style-2', 'style-3', 'style-4', 'style-5', 'style-6' ),
							),
							(object) array(
								'key'      => 'showArrows',
								'relation' => '==',
								'value'    => true,
							),
						),
						'selector'  => '{{PLUS_WRAP}} .splide__arrows.style-3 .splide__arrow.style-3:hover .icon-wrap,{{PLUS_WRAP}} .splide__arrows.style-4 .splide__arrow.style-4:hover .icon-wrap,{{PLUS_WRAP}} .splide__arrows.style-6 .splide__arrow.style-6:hover .icon-wrap svg{color:{{arrowsHoverIconColor}};}{{PLUS_WRAP}} .splide__arrows.style-2 .splide__arrow.style-2:hover .icon-wrap:before,{{PLUS_WRAP}} .splide__arrows.style-2 .splide__arrow.style-2:hover .icon-wrap:after,{{PLUS_WRAP}} .splide__arrows.style-5 .splide__arrow.style-5:hover .icon-wrap:before,{{PLUS_WRAP}} .splide__arrows.style-5 .splide__arrow.style-5:hover .icon-wrap:after{background:{{arrowsHoverIconColor}};}',
					),
				),
				'scopy'   => true,
			),
			'outerArrows'          => array(
				'type'    => 'boolean',
				'default' => false,
				'scopy'   => true,
			),
			'slideHoverArrows'     => array(
				'type'    => 'boolean',
				'default' => false,
				'scopy'   => true,
			),
			'centerMode'           => array(
				'type'    => 'object',
				'default' => array( 'md' => false ),
				'scopy'   => true,
			),
			'slidewheel'           => array(
				'type'    => 'boolean',
				'default' => false,
				'scopy'   => true,
			),
			'waitfortras'          => array(
				'type'    => 'boolean',
				'default' => false,
				'scopy'   => true,
			),
			'slidekeyNav'          => array(
				'type'    => 'boolean',
				'default' => false,
				'scopy'   => true,
			),
			'slideautoScroll'      => array(
				'type'    => 'boolean',
				'default' => false,
				'scopy'   => true,
			),
			'autoscSpeed'          => array(
				'type'    => 'string',
				'default' => '1',
			),
		);

		if ( has_filter( 'tpgb_carousel_options' ) ) {
			$options = apply_filters( 'tpgb_carousel_options', $options );
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
		$options = array(
			'saveGlobalStyle'      => array(
				'type'    => 'string',
				'default' => '',
			),
			'saveGlobalStyleClass' => array(
				'type'    => 'string',
				'default' => '',
			),
			'globalMargin'         => array(
				'type'    => 'object',
				'default' => (object) array(
					'md'   => array(
						'top'    => '',
						'bottom' => '',
						'left'   => '',
						'right'  => '',
					),
					'unit' => 'px',
				),
				'style'   => array(
					(object) array(
						'selector' => '{{PLUS_WRAP}}{margin: {{globalMargin}} !important;}',
					),
				),
				'scopy'   => true,
			),
			'globalPadding'        => array(
				'type'    => 'object',
				'default' => (object) array(
					'md'   => array(
						'top'    => '',
						'bottom' => '',
						'left'   => '',
						'right'  => '',
					),
					'unit' => 'px',
				),
				'style'   => array(
					(object) array(
						'selector' => '{{PLUS_WRAP}}{padding: {{globalPadding}};}',
					),
				),
				'scopy'   => true,
			),
			'globalBg'             => array(
				'type'    => 'object',
				'default' => (object) array(
					'bgType'         => 'color',
					'bgDefaultColor' => '',
				),
				'style'   => array(
					(object) array(
						'selector' => '{{PLUS_WRAP}}',
					),
				),
				'scopy'   => true,
			),
			'globalBgHover'        => array(
				'type'    => 'object',
				'default' => (object) array(
					'bgType'         => 'color',
					'bgDefaultColor' => '',
				),
				'style'   => array(
					(object) array(
						'selector' => '{{PLUS_WRAP}}:hover',
					),
				),
				'scopy'   => true,
			),
			'globalBorder'         => array(
				'type'    => 'object',
				'default' => (object) array(
					'openBorder' => 0,
				),
				'style'   => array(
					(object) array(
						'selector' => '{{PLUS_WRAP}}',
					),
				),
				'scopy'   => true,
			),
			'globalBorderHover'    => array(
				'type'    => 'object',
				'default' => (object) array(
					'openBorder' => 0,
				),
				'style'   => array(
					(object) array(
						'selector' => '{{PLUS_WRAP}}:hover',
					),
				),
				'scopy'   => true,
			),
			'globalBRadius'        => array(
				'type'    => 'object',
				'default' => (object) array(
					'md'   => array(
						'top'    => '',
						'bottom' => '',
						'left'   => '',
						'right'  => '',
					),
					'unit' => 'px',
				),
				'style'   => array(
					(object) array(
						'selector' => '{{PLUS_WRAP}}{border-radius: {{globalBRadius}};}',
					),
				),
				'scopy'   => true,
			),
			'globalBRadiusHover'   => array(
				'type'    => 'object',
				'default' => (object) array(
					'md'   => array(
						'top'    => '',
						'bottom' => '',
						'left'   => '',
						'right'  => '',
					),
					'unit' => 'px',
				),
				'style'   => array(
					(object) array(
						'selector' => '{{PLUS_WRAP}}:hover{border-radius: {{globalBRadiusHover}};}',
					),
				),
				'scopy'   => true,
			),
			'globalBShadow'        => array(
				'type'    => 'object',
				'default' => (object) array(
					'openShadow' => 0,
					'blur'       => 8,
					'color'      => 'rgba(0,0,0,0.40)',
					'horizontal' => 0,
					'inset'      => 0,
					'spread'     => 0,
					'vertical'   => 4,
				),
				'style'   => array(
					(object) array(
						'selector' => '{{PLUS_WRAP}}',
					),
				),
				'scopy'   => true,
			),
			'globalBShadowHover'   => array(
				'type'    => 'object',
				'default' => (object) array(
					'openShadow' => 0,
					'blur'       => 8,
					'color'      => 'rgba(0,0,0,0.40)',
					'horizontal' => 0,
					'inset'      => 0,
					'spread'     => 0,
					'vertical'   => 4,
				),
				'style'   => array(
					(object) array(
						'selector' => '{{PLUS_WRAP}}:hover',
					),
				),
				'scopy'   => true,
			),
		);

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
		$options = array(
			'globalWidth'            => array(
				'type'    => 'string',
				'default' => '',
				'style'   => array(
					(object) array(
						'condition' => array(
							(object) array(
								'key'      => 'globalWidth',
								'relation' => '==',
								'value'    => 'inline',
							),
						),
						'selector'  => '{{PLUS_BLOCK}},{{PLUS_WRAP}}{ display:inline-block;width: auto;margin-bottom: 0 !important }',
					),
				),
				'scopy'   => true,
			),
			'customWidth'            => array(
				'type'    => 'object',
				'default' => array(
					'md'   => '',
					'unit' => 'px',
				),
				'style'   => array(
					(object) array(
						'condition' => array(
							(object) array(
								'key'      => 'globalWidth',
								'relation' => '==',
								'value'    => 'custom',
							),
						),
						'selector'  => '{{PLUS_BLOCK}},{{PLUS_WRAP}}{ max-width: {{customWidth}}; width: 100% !important;}',
					),
				),
			),
			'globalZindex'           => array(
				'type'    => 'string',
				'default' => '',
				'style'   => array(
					(object) array(
						'selector' => '{{PLUS_BLOCK}},{{PLUS_WRAP}}{ position:relative;z-index: {{globalZindex}} !important; }',
					),
				),
				'scopy'   => true,
			),
			'globalflexCss'          => array(
				'type'       => 'object',
				'groupField' => array(
					(object) array(
						'gloflexShrink' => array(
							'type'    => 'object',
							'default' => array(
								'md' => '',
							),
							'style'   => array(
								(object) array(
									'condition' => array(
										(object) array(
											'key'      => 'tpgbReset',
											'relation' => '==',
											'value'    => 1,
										),
									),
									'selector'  => '{{PLUS_CLIENT_ID}}:not(.tp-core-heading):not(.tpgb-icon-box):not(.tpgb-image):not(.tp-button-core){ flex-shrink : {{gloflexShrink}} }',
									'backend'   => true,
								),
								(object) array(
									'condition' => array(
										(object) array(
											'key'      => 'tpgbReset',
											'relation' => '==',
											'value'    => 1,
										),
									),
									'selector'  => '{{PLUS_BLOCK}} { flex-shrink : {{gloflexShrink}} }',
									'backend'   => false,
								),
							),
							'scopy'   => true,
						),
						'gloflexGrow'   => array(
							'type'    => 'object',
							'default' => array(
								'md' => '',
							),
							'style'   => array(
								(object) array(
									'condition' => array(
										(object) array(
											'key'      => 'tpgbReset',
											'relation' => '==',
											'value'    => 1,
										),
									),
									'selector'  => '{{PLUS_CLIENT_ID}}:not(.tp-core-heading):not(.tpgb-icon-box):not(.tpgb-image):not(.tp-button-core){ flex-grow : {{gloflexGrow}} }',
									'backend'   => true,
								),
								(object) array(
									'condition' => array(
										(object) array(
											'key'      => 'tpgbReset',
											'relation' => '==',
											'value'    => 1,
										),
									),
									'selector'  => '{{PLUS_BLOCK}}{ flex-grow : {{gloflexGrow}} }',
									'backend'   => false,
								),
							),
							'scopy'   => true,
						),
						'gloflexBasis'  => array(
							'type'    => 'object',
							'default' => array(
								'md'   => '',
								'unit' => '%',
							),
							'style'   => array(
								(object) array(
									'condition' => array(
										(object) array(
											'key'      => 'tpgbReset',
											'relation' => '==',
											'value'    => 1,
										),
									),
									'selector'  => '{{PLUS_CLIENT_ID}}:not(.tp-core-heading):not(.tpgb-icon-box):not(.tpgb-image):not(.tp-button-core){ flex-basis : {{gloflexBasis}} }',
									'backend'   => true,
								),
								(object) array(
									'condition' => array(
										(object) array(
											'key'      => 'tpgbReset',
											'relation' => '==',
											'value'    => 1,
										),
									),
									'selector'  => '{{PLUS_BLOCK}}{ flex-basis : {{gloflexBasis}} }',
									'backend'   => false,
								),
							),
							'scopy'   => true,
						),
					),
				),
				'default'    => array(
					array(
						'gloflexShrink' => array( 'md' => '' ),
						'gloflexGrow'   => array( 'md' => '' ),
						'gloflexBasis'  => array(
							'md'   => '',
							'unit' => '%',
						),
					),
				),
			),
			'globalCssFilter'        => array(
				'type'    => 'object',
				'default' => array( 'openFilter' => false ),
				'style'   => array(
					(object) array(
						'selector' => '{{PLUS_BLOCK}} .tpgb-cssfilters',
					),
				),
				'scopy'   => true,
			),
			'globalHCssFilter'       => array(
				'type'    => 'object',
				'default' => array( 'openFilter' => false ),
				'style'   => array(
					(object) array(
						'selector' => '{{PLUS_BLOCK}} .tpgb-cssfilters:hover',
					),
				),
				'scopy'   => true,
			),
			'globalHideDesktop'      => array(
				'type'    => 'boolean',
				'default' => false,
				'style'   => array(
					(object) array(
						'selector' => '@media (min-width: 1201px){ .edit-post-visual-editor {{PLUS_WRAP}},.editor-styles-wrapper {{PLUS_WRAP}}{display: block;opacity: .5;} }',
						'backend'  => true,
					),
					(object) array(
						'selector' => '@media (min-width: 1201px){ {{PLUS_WRAP}}{ display:none !important; } }',
						'backend'  => false,
					),
				),
				'scopy'   => true,
			),
			'globalHideTablet'       => array(
				'type'    => 'boolean',
				'default' => false,
				'style'   => array(
					(object) array(
						'selector' => '@media (min-width: 768px) and (max-width: 1200px){ .edit-post-visual-editor {{PLUS_WRAP}},.editor-styles-wrapper {{PLUS_WRAP}}{display: block;opacity: .5;} }',
						'backend'  => true,
					),
					(object) array(
						'selector' => '@media (min-width: 768px) and (max-width: 1200px){ {{PLUS_WRAP}}{ display:none !important; } }',
						'backend'  => false,
					),
				),
				'scopy'   => true,
			),
			'globalHideMobile'       => array(
				'type'    => 'boolean',
				'default' => false,
				'style'   => array(
					(object) array(
						'selector' => '@media (max-width: 1024px){.text-center{text-align: center;}}@media (max-width: 767px){ .edit-post-visual-editor {{PLUS_WRAP}},.editor-styles-wrapper {{PLUS_WRAP}}{display: block;opacity: .5;} }',
						'backend'  => true,
					),
					(object) array(
						'selector' => '@media (max-width: 1024px){.text-center{text-align: center;}}@media (max-width: 767px){ {{PLUS_WRAP}}{ display:none !important; } }',
						'backend'  => false,
					),
				),
				'scopy'   => true,
			),

			'globalClasses'          => array(
				'type'    => 'string',
				'default' => '',
				'scopy'   => true,
			),
			'globalId'               => array(
				'type'    => 'string',
				'default' => '',
			),
			'globalCustomCss'        => array(
				'type'    => 'string',
				'default' => '',
				'style'   => array(
					(object) array(
						'selector' => '',
					),
				),
			),

			'globalAnim'             => array(
				'type'    => 'object',
				'default' => array( 'md' => 'none' ),
				'scopy'   => true,
			),
			'globalAnimDirect'       => array(
				'type'    => 'object',
				'default' => array( 'md' => '' ),
				'scopy'   => true,
			),
			'globalAnimDuration'     => array(
				'type'    => 'string',
				'default' => 'normal',
				'scopy'   => true,
			),
			'globalAnimCDuration'    => array(
				'type'    => 'object',
				'default' => array( 'md' => '' ),
				'style'   => array(
					(object) array(
						'selector' => '{{PLUS_BLOCK}}.tpgb_animated.tpgb-anim-dur-custom{-webkit-animation-duration: {{globalAnimCDuration}}s;animation-duration: {{globalAnimCDuration}}s;}',
					),
				),
				'scopy'   => true,
			),
			'globalAnimDelay'        => array(
				'type'    => 'object',
				'default' => array( 'md' => '' ),
				'style'   => array(
					(object) array(
						'selector' => '{{PLUS_BLOCK}}.tpgb-view-animation{-webkit-animation-delay: {{globalAnimDelay}}s;animation-delay: {{globalAnimDelay}}s;}',
					),
				),
				'scopy'   => true,
			),
			'globalAnimEasing'       => array(
				'type'    => 'string',
				'default' => '',
				'style'   => array(
					(object) array(
						'condition' => array(
							(object) array(
								'key'      => 'globalAnimEasing',
								'relation' => '!=',
								'value'    => 'custom',
							),
						),
						'selector'  => '{{PLUS_BLOCK}}.tpgb-view-animation{animation-timing-function: {{globalAnimEasing}};}',
					),
				),
				'scopy'   => true,
			),
			'globalAnimEasCustom'    => array(
				'type'    => 'string',
				'default' => '',
				'style'   => array(
					(object) array(
						'selector' => '{{PLUS_BLOCK}}.tpgb-view-animation{animation-timing-function: {{globalAnimEasCustom}};}',
					),
				),
				'scopy'   => true,
			),

			'globalAnimOut'          => array(
				'type'    => 'object',
				'default' => array( 'md' => 'none' ),
				'scopy'   => true,
			),
			'globalAnimDirectOut'    => array(
				'type'    => 'object',
				'default' => array( 'md' => '' ),
				'scopy'   => true,
			),
			'globalAnimDurationOut'  => array(
				'type'    => 'string',
				'default' => 'normal',
				'scopy'   => true,
			),
			'globalAnimCDurationOut' => array(
				'type'    => 'object',
				'default' => array( 'md' => '' ),
				'style'   => array(
					(object) array(
						'selector' => '{{PLUS_BLOCK}}.tpgb_animated_out.tpgb-anim-out-dur-custom{-webkit-animation-duration: {{globalAnimCDurationOut}}s;animation-duration: {{globalAnimCDurationOut}}s;}',
					),
				),
				'scopy'   => true,
			),
			'globalAnimDelayOut'     => array(
				'type'    => 'object',
				'default' => array( 'md' => '' ),
				'style'   => array(
					(object) array(
						'selector' => '{{PLUS_BLOCK}}.tpgb-view-animation-out{-webkit-animation-delay: {{globalAnimDelayOut}}s;animation-delay: {{globalAnimDelayOut}}s;}',
					),
				),
				'scopy'   => true,
			),
			'globalAnimEasingOut'    => array(
				'type'    => 'string',
				'default' => '',
				'style'   => array(
					(object) array(
						'condition' => array(
							(object) array(
								'key'      => 'globalAnimEasingOut',
								'relation' => '!=',
								'value'    => 'custom',
							),
						),
						'selector'  => '{{PLUS_BLOCK}}.tpgb-view-animation-out{animation-timing-function: {{globalAnimEasingOut}};}',
					),
				),
				'scopy'   => true,
			),
			'globalAnimEasCustomOut' => array(
				'type'    => 'string',
				'default' => '',
				'style'   => array(
					(object) array(
						'selector' => '{{PLUS_BLOCK}}.tpgb-view-animation-out{animation-timing-function: {{globalAnimEasCustomOut}};}',
					),
				),
				'scopy'   => true,
			),
			'globalPosition'         => array(
				'type'    => 'object',
				'default' => array(
					'md' => '',
					'sm' => '',
					'xs' => '',
				),
				'style'   => array(
					(object) array(
						'selector' => '{{PLUS_CLIENT_ID}}{ position : {{globalPosition}};width : unset }',
						'backend'  => true,
					),
				),
			),
			'gloabhorizoOri'         => array(
				'type'    => 'object',
				'default' => array(
					'md' => 'left',
					'sm' => '',
					'xs' => '',
				),
			),
			'glohoriOffset'          => array(
				'type'    => 'object',
				'default' => array(
					'md'   => '0',
					'unit' => 'px',
				),
				'style'   => array(
					(object) array(
						'condition' => array(
							(object) array(
								'key'      => 'globalPosition',
								'relation' => '==',
								'value'    => array( 'absolute', 'fixed' ),
							),
							(object) array(
								'key'      => 'gloabhorizoOri',
								'relation' => '==',
								'value'    => 'left',
							),
						),
						'selector'  => '{{PLUS_CLIENT_ID}}{ left : {{glohoriOffset}};right : auto; }',
						'backend'   => true,
					),
					(object) array(
						'condition' => array(
							(object) array(
								'key'      => 'globalPosition',
								'relation' => '==',
								'value'    => array( 'absolute', 'fixed' ),
							),
							(object) array(
								'key'      => 'gloabhorizoOri',
								'relation' => '==',
								'value'    => 'right',
							),
						),
						'selector'  => '{{PLUS_CLIENT_ID}}{ right : {{glohoriOffset}};left : auto; }',
						'backend'   => true,
					),
				),
			),
			'gloabverticalOri'       => array(
				'type'    => 'object',
				'default' => array(
					'md' => 'top',
					'sm' => '',
					'xs' => '',
				),
			),
			'gloverticalOffset'      => array(
				'type'    => 'object',
				'default' => array(
					'md'   => '0',
					'unit' => 'px',
				),
				'style'   => array(
					(object) array(
						'condition' => array(
							(object) array(
								'key'      => 'globalPosition',
								'relation' => '==',
								'value'    => array( 'absolute', 'fixed' ),
							),
							(object) array(
								'key'      => 'gloabverticalOri',
								'relation' => '==',
								'value'    => 'top',
							),
						),
						'selector'  => '{{PLUS_CLIENT_ID}}{ top : {{gloverticalOffset}}; bottom : auto; }',
						'backend'   => true,
					),
					(object) array(
						'condition' => array(
							(object) array(
								'key'      => 'globalPosition',
								'relation' => '==',
								'value'    => array( 'absolute', 'fixed' ),
							),
							(object) array(
								'key'      => 'gloabverticalOri',
								'relation' => '==',
								'value'    => 'bottom',
							),
						),
						'selector'  => '{{PLUS_CLIENT_ID}} { bottom : {{gloverticalOffset}}; top : auto; }',
						'backend'   => true,
					),
				),
			),
			'globalOverflow'         => array(
				'type'    => 'string',
				'default' => '',
				'style'   => array(
					(object) array(
						'selector' => '{{PLUS_BLOCK}} { overflow: {{globalOverflow}}; }',
					),
				),
			),
		);

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
		$options = array(
			'className'          => array(
				'type'    => 'string',
				'default' => '',
			),
			'contentHoverEffect' => array(
				'type'    => 'boolean',
				'default' => false,
				'scopy'   => true,
			),
			'selectHoverEffect'  => array(
				'type'    => 'string',
				'default' => '',
				'scopy'   => true,
			),

			'contentHoverColor'  => array(
				'type'    => 'string',
				'default' => '',
				'style'   => array(
					(object) array(
						'condition' => array(
							(object) array(
								'key'      => 'contentHoverEffect',
								'relation' => '==',
								'value'    => true,
							),
							array(
								'key'      => 'selectHoverEffect',
								'relation' => '==',
								'value'    => 'float_shadow',
							),
						),
						'selector'  => '{{PLUS_BLOCK}}.tpgb_cnt_hvr_effect.cnt_hvr_float_shadow:before{background: -webkit-radial-gradient(center, ellipse, {{contentHoverColor}} 0%, rgba(60, 60, 60, 0) 70%);background: radial-gradient(ellipse at 50% 150%,{{contentHoverColor}} 0%, rgba(60, 60, 60, 0) 70%); }',
					),
					(object) array(
						'condition' => array(
							(object) array(
								'key'      => 'contentHoverEffect',
								'relation' => '==',
								'value'    => true,
							),
							array(
								'key'      => 'selectHoverEffect',
								'relation' => '==',
								'value'    => 'grow_shadow',
							),
						),
						'selector'  => '{{PLUS_BLOCK}}.tpgb_cnt_hvr_effect.cnt_hvr_grow_shadow:hover {-webkit-box-shadow: 0 10px 10px -10px {{contentHoverColor}};-moz-box-shadow: 0 10px 10px -10px {{contentHoverColor}};box-shadow: 0 10px 10px -10px {{contentHoverColor}};}',
					),
					(object) array(
						'condition' => array(
							(object) array(
								'key'      => 'contentHoverEffect',
								'relation' => '==',
								'value'    => true,
							),
							array(
								'key'      => 'selectHoverEffect',
								'relation' => '==',
								'value'    => 'shadow_radial',
							),
						),
						'selector'  => '{{PLUS_BLOCK}}.tpgb_cnt_hvr_effect.cnt_hvr_shadow_radial:before{background: -webkit-radial-gradient(center, ellipse at 50% 150%, {{contentHoverColor}} 0%, rgba(60, 60, 60, 0) 70%);background: radial-gradient(ellipse at 50% 150%,{{contentHoverColor}} 0%, rgba(60, 60, 60, 0) 70%); }{{PLUS_BLOCK}}.tpgb_cnt_hvr_effect.cnt_hvr_shadow_radial:after {background: -webkit-radial-gradient(50% -50%, ellipse, {{contentHoverColor}} 0%, rgba(0, 0, 0, 0) 80%);background: radial-gradient(ellipse at 50% -50%, {{contentHoverColor}} 0%, rgba(0, 0, 0, 0) 80%);}',
					),
				),
				'scopy'   => true,
			),
		);

		if ( has_filter( 'tpgb_display_option' ) ) {
			$options = apply_filters( 'tpgb_display_option', $options );
		}

		return $options;
	}

	/**
	 * Load Global Background Options
	 *
	 * @since 1.0.0
	 */
	public static function load_plusButton_options() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid,WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid

		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}
		$options = array(
			'extBtnshow'         => array(
				'type'    => 'boolean',
				'default' => false,
			),
			'extBtnStyle'        => array(
				'type'    => 'string',
				'default' => 'style-7',
			),
			'extBtnText'         => array(
				'type'    => 'string',
				'default' => 'Have a Look',
			),
			'extBtnUrl'          => array(
				'type'    => 'object',
				'default' => array(
					'url'      => '#',
					'target'   => '',
					'nofollow' => '',
				),
			),
			'extBtniconFont'     => array(
				'type'    => 'string',
				'default' => 'none',
			),

			'extBtniconName'     => array(
				'type'    => 'string',
				'default' => '',
			),
			'extBtniconPosition' => array(
				'type'    => 'string',
				'default' => 'after',
			),
			'extBtniconSpacing'  => array(
				'type'    => 'object',
				'default' => array(
					'md'   => 5,
					'unit' => 'px',
				),
				'style'   => array(
					(object) array(
						'condition' => array(
							(object) array(
								'key'      => 'extBtnshow',
								'relation' => '==',
								'value'    => true,
							),
						),
						'selector'  => '{{PLUS_WRAP}} .button-link-wrap .button-before { margin-right: {{extBtniconSpacing}}; } {{PLUS_WRAP}} .button-link-wrap .button-after { margin-left: {{extBtniconSpacing}}; }',
					),
				),
				'scopy'   => true,
			),
			'extBtniconSize'     => array(
				'type'    => 'object',
				'default' => array(
					'md'   => '',
					'unit' => 'px',
				),
				'style'   => array(
					(object) array(
						'condition' => array(
							(object) array(
								'key'      => 'extBtnshow',
								'relation' => '==',
								'value'    => true,
							),
						),
						'selector'  => '{{PLUS_WRAP}} .button-link-wrap .btn-icon { font-size: {{extBtniconSize}}; }',
					),
				),
				'scopy'   => true,
			),
			'extbtnSpace'        => array(
				'type'    => 'object',
				'default' => (object) array(
					'md'   => '',
					'unit' => 'px',
				),
				'style'   => array(
					(object) array(
						'condition' => array(
							(object) array(
								'key'      => 'extBtnshow',
								'relation' => '==',
								'value'    => true,
							),
						),
						'selector'  => '{{PLUS_WRAP}} .tpgb-adv-button{ margin-top: {{extbtnSpace}}; }',
					),
				),
				'scopy'   => true,
			),
			'extbtnbottomSpace'  => array(
				'type'    => 'object',
				'default' => (object) array(
					'md'   => '',
					'unit' => 'px',
				),
				'style'   => array(
					(object) array(
						'condition' => array(
							(object) array(
								'key'      => 'extBtnshow',
								'relation' => '==',
								'value'    => true,
							),
						),
						'selector'  => '{{PLUS_WRAP}} .tpgb-adv-button{ margin-bottom : {{extbtnbottomSpace}}; }',
					),
				),
				'scopy'   => true,
			),
			'extbtnPadding'      => array(
				'type'    => 'object',
				'default' => (object) array(
					'md'   => array(
						'top'    => '',
						'right'  => '',
						'bottom' => '',
						'left'   => '',
					),
					'unit' => 'px',
				),
				'style'   => array(
					(object) array(
						'condition' => array(
							(object) array(
								'key'      => 'extBtnshow',
								'relation' => '==',
								'value'    => true,
							),
						),
						'selector'  => '{{PLUS_WRAP}} .tpgb-adv-button.button-style-8 .button-link-wrap{ padding: {{extbtnPadding}}; }',
					),
				),
				'scopy'   => true,
			),
			'extbtnTypo'         => array(
				'type'    => 'object',
				'default' => (object) array(
					'openTypography' => 0,
					'size'           => array(
						'md'   => '',
						'unit' => 'px',
					),
				),
				'style'   => array(
					(object) array(
						'condition' => array(
							(object) array(
								'key'      => 'extBtnshow',
								'relation' => '==',
								'value'    => true,
							),
						),
						'selector'  => '{{PLUS_WRAP}} .tpgb-adv-button .button-link-wrap',
					),
				),
				'scopy'   => true,
			),
			'extbtnTextColor'    => array(
				'type'    => 'string',
				'default' => '',
				'style'   => array(
					(object) array(
						'condition' => array(
							(object) array(
								'key'      => 'extBtnshow',
								'relation' => '==',
								'value'    => true,
							),
						),
						'selector'  => '{{PLUS_WRAP}} .tpgb-adv-button .button-link-wrap{ color: {{extbtnTextColor}}; }',
					),
					(object) array(
						'condition' => array(
							(object) array(
								'key'      => 'extBtnshow',
								'relation' => '==',
								'value'    => true,
							),
							array(
								'key'      => 'extBtnStyle',
								'relation' => '==',
								'value'    => 'style-7',
							),
						),
						'selector'  => '{{PLUS_WRAP}} .tpgb-adv-button.button-style-7 .button-link-wrap:after{ border-color: {{extbtnTextColor}}; }',
					),
				),
				'scopy'   => true,
			),
			'extbtnThoverColor'  => array(
				'type'    => 'string',
				'default' => '',
				'style'   => array(
					(object) array(
						'condition' => array(
							(object) array(
								'key'      => 'extBtnshow',
								'relation' => '==',
								'value'    => true,
							),
						),
						'selector'  => '{{PLUS_WRAP}} .tpgb-adv-button .button-link-wrap:hover{ color: {{extbtnThoverColor}}; }',
					),
				),
				'scopy'   => true,
			),
			'extbtnNormalB'      => array(
				'type'    => 'object',
				'default' => (object) array(
					'openBorder' => 0,
					'type'       => '',
					'color'      => '',
					'width'      => (object) array(
						'md'   => (object) array(
							'top'    => '1',
							'left'   => '1',
							'bottom' => '1',
							'right'  => '1',
						),
						'unit' => 'px',
					),
				),
				'style'   => array(
					(object) array(
						'condition' => array(
							(object) array(
								'key'      => 'extBtnshow',
								'relation' => '==',
								'value'    => true,
							),
							array(
								'key'      => 'extBtnStyle',
								'relation' => '==',
								'value'    => 'style-8',
							),
						),
						'selector'  => '{{PLUS_WRAP}} .tpgb-adv-button.button-style-8 .button-link-wrap',
					),
				),
				'scopy'   => true,
			),
			'extbtnBRadius'      => array(
				'type'    => 'object',
				'default' => (object) array(
					'md'   => array(
						'top'    => '',
						'right'  => '',
						'bottom' => '',
						'left'   => '',
					),
					'unit' => 'px',
				),
				'style'   => array(
					(object) array(
						'condition' => array(
							(object) array(
								'key'      => 'extBtnshow',
								'relation' => '==',
								'value'    => true,
							),
							array(
								'key'      => 'extBtnStyle',
								'relation' => '==',
								'value'    => 'style-8',
							),
						),
						'selector'  => '{{PLUS_WRAP}} .tpgb-adv-button.button-style-8 .button-link-wrap{border-radius: {{extbtnBRadius}};}',
					),
				),
				'scopy'   => true,
			),
			'extbtnBG'           => array(
				'type'    => 'object',
				'default' => (object) array(
					'openBg' => 0,
				),
				'style'   => array(
					(object) array(
						'condition' => array(
							(object) array(
								'key'      => 'extBtnshow',
								'relation' => '==',
								'value'    => true,
							),
							array(
								'key'      => 'extBtnStyle',
								'relation' => '==',
								'value'    => 'style-8',
							),
						),
						'selector'  => '{{PLUS_WRAP}} .tpgb-adv-button.button-style-8 .button-link-wrap',
					),
				),
				'scopy'   => true,
			),
			'extbtnHvrB'         => array(
				'type'    => 'object',
				'default' => (object) array(
					'openBorder' => 0,
					'type'       => '',
					'color'      => '',
					'width'      => (object) array(
						'md'   => (object) array(
							'top'    => '1',
							'left'   => '1',
							'bottom' => '1',
							'right'  => '1',
						),
						'unit' => 'px',
					),
				),
				'style'   => array(
					(object) array(
						'condition' => array(
							(object) array(
								'key'      => 'extBtnshow',
								'relation' => '==',
								'value'    => true,
							),
							array(
								'key'      => 'extBtnStyle',
								'relation' => '==',
								'value'    => 'style-8',
							),
						),
						'selector'  => '{{PLUS_WRAP}} .tpgb-adv-button.button-style-8 .button-link-wrap:hover',
					),
				),
				'scopy'   => true,
			),
			'extbtnHvrBRadius'   => array(
				'type'    => 'object',
				'default' => (object) array(
					'md'   => array(
						'top'    => '',
						'right'  => '',
						'bottom' => '',
						'left'   => '',
					),
					'unit' => 'px',
				),
				'style'   => array(
					(object) array(
						'condition' => array(
							(object) array(
								'key'      => 'extBtnshow',
								'relation' => '==',
								'value'    => true,
							),
							array(
								'key'      => 'extBtnStyle',
								'relation' => '==',
								'value'    => 'style-8',
							),
						),
						'selector'  => '{{PLUS_WRAP}} .tpgb-adv-button.button-style-8 .button-link-wrap:hover{border-radius: {{extbtnHvrBRadius}};}',
					),
				),
				'scopy'   => true,
			),
			'extbtnHvrBG'        => array(
				'type'    => 'object',
				'default' => (object) array(
					'openBg' => 0,
				),
				'style'   => array(
					(object) array(
						'condition' => array(
							(object) array(
								'key'      => 'extBtnshow',
								'relation' => '==',
								'value'    => true,
							),
							array(
								'key'      => 'extBtnStyle',
								'relation' => '==',
								'value'    => 'style-8',
							),
						),
						'selector'  => '{{PLUS_WRAP}} .tpgb-adv-button.button-style-8 .button-link-wrap:hover',
					),
				),
				'scopy'   => true,
			),
			'extbtnShadow'       => array(
				'type'    => 'object',
				'default' => (object) array(
					'horizontal' => 0,
					'vertical'   => 8,
					'blur'       => 20,
					'spread'     => 1,
					'color'      => 'rgba(0,0,0,0.27)',
				),
				'style'   => array(
					(object) array(
						'condition' => array(
							(object) array(
								'key'      => 'extBtnshow',
								'relation' => '==',
								'value'    => true,
							),
						),
						'selector'  => '{{PLUS_WRAP}} .tpgb-adv-button .button-link-wrap',
					),
				),
				'scopy'   => true,
			),
			'hoverextbtnShadow'  => array(
				'type'    => 'object',
				'default' => (object) array(
					'horizontal' => '',
					'vertical'   => '',
					'blur'       => '',
					'spread'     => '',
					'color'      => 'rgba(0,0,0,0.27)',
				),
				'style'   => array(
					(object) array(
						'condition' => array(
							(object) array(
								'key'      => 'extBtnshow',
								'relation' => '==',
								'value'    => true,
							),
						),
						'selector'  => '{{PLUS_WRAP}} .tpgb-adv-button .button-link-wrap:hover',
					),
				),
				'scopy'   => true,
			),
		);

		return $options;
	}

	/**
	 * Load plus button saves.
	 *
	 * @param mixed $attributes The attributes.
	 * @return mixed The result.
	 */
	public static function load_plusButton_saves( $attributes ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid,WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid

		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}
		if ( empty( $attributes ) ) {
			return;
		}
		$ext_btnshow          = ( ! empty( $attributes['extBtnshow'] ) ) ? $attributes['extBtnshow'] : false;
		$ext_btn_style        = ( ! empty( $attributes['extBtnStyle'] ) ) ? $attributes['extBtnStyle'] : 'style-8';
		$ext_btn_text         = ( ! empty( $attributes['extBtnText'] ) ) ? $attributes['extBtnText'] : '';
		$ext_btn_url          = ( ! empty( $attributes['extBtnUrl'] ) ) ? $attributes['extBtnUrl'] : '';
		$ext_btntarget        = ( ! empty( $attributes['extBtnUrl']['target'] ) ) ? ' target="_blank"' : '';
		$ext_btnrel           = ( ! empty( $attributes['extBtnUrl']['nofollow'] ) ) ? ' rel="nofollow" ' : '';
		$ext_btnicon_font     = ( ! empty( $attributes['extBtniconFont'] ) ) ? $attributes['extBtniconFont'] : '';
		$ext_btnicon_name     = ( ! empty( $attributes['extBtniconName'] ) ) ? $attributes['extBtniconName'] : '';
		$ext_btnicon_position = ( ! empty( $attributes['extBtniconPosition'] ) ) ? $attributes['extBtniconPosition'] : 'after';
		$i_box_link_tgl       = ( ! empty( $attributes['IBoxLinkTgl'] ) ) ? $attributes['IBoxLinkTgl'] : false;

			$output  = '';
			$output .= '<div class="tpgb-adv-button button-' . esc_attr( $ext_btn_style ) . '">';
		if ( ! empty( $i_box_link_tgl ) ) {
			$output .= '<div class="button-link-wrap">';
		} else {
			$link_attr = Tp_Blocks_Helper::add_link_attributes( $ext_btn_url );

			$ext_url = '';
			if ( class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) {
				$ext_url = ( isset( $attributes['extBtnUrl']['dynamic'] ) ) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_repeat_url( $attributes['extBtnUrl'] ) : ( ! empty( $attributes['extBtnUrl']['url'] ) ? $attributes['extBtnUrl']['url'] : '' );
			} else {
				$ext_url = ( ! empty( $attributes['extBtnUrl']['url'] ) ? $attributes['extBtnUrl']['url'] : '' );
			}
			$output .= '<a class="button-link-wrap"  href="' . ( ! empty( $ext_url ) ? esc_url( $ext_url ) : '' ) . '"  ' . $ext_btntarget . ' ' . $ext_btnrel . '  ' . $link_attr . '>';
		}
		if ( 'style-8' === $ext_btn_style ) {
			if ( 'before' === $ext_btnicon_position ) {
				( ( 'font_awesome' === $ext_btnicon_font && ! empty( $ext_btnicon_name ) ) ? $output .= '<span class="btn-icon button-' . esc_attr( $ext_btnicon_position ) . '"><i class="' . esc_attr( $ext_btnicon_name ) . ' "></i></span>' : '' );
				$output .= wp_kses_post( $ext_btn_text );
			} else {
				$output .= wp_kses_post( $ext_btn_text );
				( ( 'font_awesome' === $ext_btnicon_font && ! empty( $ext_btnicon_name ) ) ? $output .= '<span class="btn-icon button-' . esc_attr( $ext_btnicon_position ) . '"><i class="' . esc_attr( $ext_btnicon_name ) . ' "></i></span>' : '' );
			}
		} else {
			$output .= wp_kses_post( $ext_btn_text );
			$output .= '<span class="button-arrow"> ';
			if ( 'style-7' === $ext_btn_style ) {
						$output .= '<span class="btn-right-arrow"><i class="fas fa-chevron-right"></i></span>';
			}
			if ( 'style-9' === $ext_btn_style ) {
							$output .= '<i class="btn-show fa fa-chevron-right" aria-hidden="true"></i>';
							$output .= '<i class="btn-hide fa fa-chevron-right" aria-hidden="true"></i>';
			}
									$output .= '</span>';
		}
		if ( ! empty( $i_box_link_tgl ) ) {
			$output .= '</div>';
		} else {
			$output .= '</a>';
		}
			$output .= '</div>';

		return $output;
	}

	/**
	 * Tpgb animation device.
	 *
	 * @param string $global_anim The global anim.
	 * @param string $anim_direct The anim direct.
	 * @param string $device The device.
	 * @return mixed The result.
	 */
	public static function tpgbAnimationDevice( $global_anim = '', $anim_direct = '', $device = '' ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid,WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
		$animation_val = '';
		if ( 'fadeIn' === $global_anim ) {
			$animation_val .= ( ( '' === $anim_direct[ $device ] || 'default' === $anim_direct[ $device ] ) ? 'fadeIn' : 'fadeIn' . $anim_direct[ $device ] );
		} elseif ( 'slideIn' === $global_anim ) {
			$animation_val .= ( ( '' === $anim_direct[ $device ] || 'default' === $anim_direct[ $device ] ) ? 'slideInDown' : 'slideIn' . $anim_direct[ $device ] );
		} elseif ( 'zoomIn' === $global_anim ) {
			$animation_val .= ( ( '' === $anim_direct[ $device ] || 'default' === $anim_direct[ $device ] ) ? 'zoomIn' : 'zoomIn' . $anim_direct[ $device ] );
		} elseif ( 'rotateIn' === $global_anim ) {
			$animation_val .= ( ( '' === $anim_direct[ $device ] || 'default' === $anim_direct[ $device ] ) ? 'rotateIn' : 'rotateIn' . $anim_direct[ $device ] );
		} elseif ( 'flipIn' === $global_anim ) {
			$animation_val .= ( ( '' === $anim_direct[ $device ] || 'default' === $anim_direct[ $device ] ) ? 'flipInX' : 'flipIn' . $anim_direct[ $device ] );
		} elseif ( 'lightSpeedIn' === $global_anim ) {
			$animation_val .= ( ( '' === $anim_direct[ $device ] || 'default' === $anim_direct[ $device ] ) ? 'lightSpeedInLeft' : 'lightSpeedIn' . $anim_direct[ $device ] );
		} elseif ( 'seekers' === $global_anim ) {
			$animation_val .= ( ( '' === $anim_direct[ $device ] || 'default' === $anim_direct[ $device ] ) ? 'bounce' : $anim_direct[ $device ] );
		} elseif ( 'rollIn' === $global_anim ) {
			$animation_val .= 'rollIn';
		}

		return $animation_val;
	}

	/**
	 * Block wrap render.
	 *
	 * @param mixed  $attributes The attributes.
	 * @param string $content The content.
	 * @return mixed The result.
	 */
	public static function block_Wrap_Render( $attributes, $content = '' ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid,WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid

		if ( ! function_exists( 'register_block_type' ) ) {
			return $content;
		}
		if ( empty( $attributes ) || empty( $attributes['block_id'] ) || empty( $content ) ) {
			return $content;
		}
		$attributes = json_decode( wp_json_encode( $attributes ), true );

		$animation_effect = false;
		$anim_class       = '';
		$anim_attr        = '';
		$anim_desktop     = '';
		$anim_tablet      = '';
		$anim_mobile      = '';
		$anim_settings    = array();
		if ( ( ! empty( $attributes['globalAnim'] ) ) ) {
			if ( ! empty( $attributes['globalAnim']['md'] ) && 'none' !== $attributes['globalAnim']['md'] ) {
				$animation_effect = true;
				$global_anim      = $attributes['globalAnim']['md'];
				if ( ! empty( $attributes['globalAnimDirect'] ) ) {
					$anim_desktop = self::tpgbAnimationDevice( $global_anim, $attributes['globalAnimDirect'], 'md' );
				}
			}

			if ( ! empty( $attributes['globalAnim']['sm'] ) && 'none' !== $attributes['globalAnim']['sm'] ) {
				$animation_effect = true;
				$global_anim      = $attributes['globalAnim']['sm'];
				if ( ! empty( $attributes['globalAnimDirect'] ) ) {
					$anim_tablet = self::tpgbAnimationDevice( $global_anim, $attributes['globalAnimDirect'], 'sm' );
				}
			}

			if ( ! empty( $attributes['globalAnim']['xs'] ) && 'none' !== $attributes['globalAnim']['xs'] ) {
				$animation_effect = true;
				$global_anim      = $attributes['globalAnim']['xs'];
				if ( ! empty( $attributes['globalAnimDirect'] ) ) {
					$anim_mobile = self::tpgbAnimationDevice( $global_anim, $attributes['globalAnimDirect'], 'xs' );
				}
			}

			if ( ! empty( $animation_effect ) ) {

				if ( ! empty( $attributes['globalAnimDuration'] ) && 'custom' === $attributes['globalAnimDuration'] ) {
					$anim_class .= ' tpgb-anim-dur-custom';
				} elseif ( ! empty( $attributes['globalAnimDuration'] ) ) {
					$anim_class .= ' tpgb-anim-dur-' . esc_attr( $attributes['globalAnimDuration'] );
				}

				$anim_settings['anime']['md'] = ! empty( $anim_desktop ) ? $anim_desktop : '';
				$anim_settings['anime']['sm'] = ! empty( $anim_tablet ) ? $anim_tablet : '';
				$anim_settings['anime']['xs'] = ! empty( $anim_mobile ) ? $anim_mobile : '';
			}
		}

		$animation_out_effect = array( 'check' => false );
		if ( ( ! empty( $attributes['globalAnimOut'] ) ) ) {

			if ( has_filter( 'tpgb_globalAnimOut_filter' ) ) {
				$animation_out_effect = apply_filters( 'tpgb_globalAnimOut_filter', $animation_out_effect, $attributes ); // phpcs:ignore WordPress.NamingConventions.ValidHookName
			}

			if ( ! empty( $animation_out_effect['check'] ) ) {

				if ( ! empty( $attributes['globalAnimDurationOut'] ) && 'custom' === $attributes['globalAnimDurationOut'] ) {
					$anim_class .= ' tpgb-anim-out-dur-custom';
				} elseif ( ! empty( $attributes['globalAnimDurationOut'] ) ) {
					$anim_class .= ' tpgb-anim-out-dur-' . $attributes['globalAnimDurationOut'];
				}

				$anim_settings['animeOut']['md'] = ( isset( $animation_out_effect['md'] ) && ! empty( $animation_out_effect['md'] ) ) ? $animation_out_effect['md'] : '';
				$anim_settings['animeOut']['sm'] = ( isset( $animation_out_effect['sm'] ) && ! empty( $animation_out_effect['sm'] ) ) ? $animation_out_effect['sm'] : '';
				$anim_settings['animeOut']['xs'] = ( isset( $animation_out_effect['xs'] ) && ! empty( $animation_out_effect['xs'] ) ) ? $animation_out_effect['xs'] : '';
			}
		}

		if ( ! empty( $animation_effect ) || ! empty( $animation_out_effect['check'] ) ) {
			$anim_class .= ' tpgb-view-animation';
			$anim_attr  .= 'data-animationSetting=\'' . htmlspecialchars( wp_json_encode( $anim_settings ), ENT_QUOTES, 'UTF-8' ) . '\'';
		}

		if ( ! empty( $attributes['PlusMouseParallax'] ) && ! empty( $attributes['PlusMouseParallax']['tpgbReset'] ) ) {
			$anim_class .= ' tpgb-mouse-parallax';
		}

		$output_wrap = '';

		$wrap_class = '';
		if ( ( ! empty( $attributes['globalClasses'] ) ) ) {
			$wrap_class .= $attributes['globalClasses'];
		}
		if ( ( isset( $attributes['layout'] ) && ! empty( $attributes['layout'] ) ) || ( isset( $attributes['telayout'] ) && ! empty( $attributes['telayout'] ) ) ) {
			$wrap_class .= ' tpgb-wrap-fw';
		}
		if ( ! isset( $attributes['contentWidth'] ) ) {
			$wrap_class .= ( ! empty( $attributes['align'] ) ) ? ' align' . $attributes['align'] : '';
		}
		$wrap_id = '';
		if ( ( ! empty( $attributes['globalId'] ) ) ) {
			$wrap_id .= 'id="' . esc_attr( $attributes['globalId'] ) . '"';
		}

		$has_wrapper = false;
		if ( ! empty( $wrap_id ) || ! empty( $wrap_class ) || ! empty( $attributes['globalCustomCss'] ) || ! empty( $animation_effect ) || ! empty( $animation_out_effect['check'] ) || ( isset( $attributes['globalflexCss'] ) && ! empty( $attributes['globalflexCss']['tpgbReset'] ) ) || ( ! empty( $attributes['globalPosition'] ) && ( ( isset( $attributes['globalPosition']['md'] ) && ! empty( $attributes['globalPosition']['md'] ) ) || ( isset( $attributes['globalPosition']['sm'] ) && ! empty( $attributes['globalPosition']['sm'] ) ) || ( isset( $attributes['globalPosition']['xs'] ) && ! empty( $attributes['globalPosition']['xs'] ) ) ) ) ) {
			$has_wrapper = true;
			if ( isset( $attributes['contentWidth'] ) && ! empty( $attributes['contentWidth'] ) ) {
				$wrap_class .= ' alignfull';
			}
		}

		if ( has_filter( 'tpgb_hasWrapper' ) ) {
			$has_wrapper = apply_filters( 'tpgb_hasWrapper', $has_wrapper, $attributes ); // phpcs:ignore WordPress.NamingConventions.ValidHookName
		}

		$wrapper_attr = '';
		if ( has_filter( 'tpgb_globalWrapAttr' ) ) {
			$wrapper_attr = apply_filters( 'tpgb_globalWrapAttr', $wrapper_attr, $attributes ); // phpcs:ignore WordPress.NamingConventions.ValidHookName
		}
		if ( ! empty( $has_wrapper ) ) {

			if ( has_filter( 'tpgb_globalWrapClass' ) ) {
				$wrap_class = apply_filters( 'tpgb_globalWrapClass', $wrap_class, $attributes ); // phpcs:ignore WordPress.NamingConventions.ValidHookName
			}

			if ( isset( $attributes['globalPosition']['md'] ) && ! empty( $attributes['globalPosition']['md'] ) ) {
				$wrap_class .= ' tpgb-position-' . esc_attr( $attributes['globalPosition']['md'] );
			}
			if ( isset( $attributes['globalPosition']['sm'] ) && ! empty( $attributes['globalPosition']['sm'] ) ) {
				$wrap_class .= ' tpgb-tab-position-' . esc_attr( $attributes['globalPosition']['sm'] );
			} elseif ( isset( $attributes['globalPosition']['md'] ) && ! empty( $attributes['globalPosition']['md'] ) ) {
				$wrap_class .= ' tpgb-tab-position-' . esc_attr( $attributes['globalPosition']['md'] );
			}

			if ( isset( $attributes['globalPosition']['xs'] ) && ! empty( $attributes['globalPosition']['xs'] ) ) {
				$wrap_class .= ' tpgb-mobile-position-' . esc_attr( $attributes['globalPosition']['xs'] );
			} elseif ( isset( $attributes['globalPosition']['sm'] ) && ! empty( $attributes['globalPosition']['sm'] ) ) {
				$wrap_class .= ' tpgb-mobile-position-' . esc_attr( $attributes['globalPosition']['sm'] );
			} elseif ( isset( $attributes['globalPosition']['md'] ) && ! empty( $attributes['globalPosition']['md'] ) ) {
				$wrap_class .= ' tpgb-mobile-position-' . esc_attr( $attributes['globalPosition']['md'] );
			}

			$output_wrap .= '<div ' . $wrap_id . ' class="tpgb-wrap-' . esc_attr( $attributes['block_id'] ) . ' ' . esc_attr( $wrap_class ) . ' ' . esc_attr( $anim_class ) . '" ' . $anim_attr . ' ' . $wrapper_attr . '>';
				ob_start();
				do_action( 'tpgb_wrapper_inner_before', $attributes );
				$output_wrap .= ob_get_contents();
				ob_end_clean();

				$output_wrap .= $content;

				ob_start();
				do_action( 'tpgb_wrapper_inner_after', $attributes );
				$output_wrap .= ob_get_contents();
				ob_end_clean();

			$output_wrap .= '</div>';

		} else {
			$output_wrap .= $content;
		}

		if ( isset( $attributes['tpgbDisrule'] ) && ! empty( $attributes['tpgbDisrule'] ) && class_exists( 'Tpgb_Display_Conditions_Rules' ) ) {
			$tpgb_condition_rules = Tpgb_Display_Conditions_Rules::get_instance();
			if ( ! empty( $tpgb_condition_rules ) ) {

				$check_dis = $tpgb_condition_rules::tpgb_rules_actions( $attributes['block_id'], $attributes );

				if ( false === $check_dis ) {
					$output_wrap = '';
				}
			}
		}

		if ( ( isset( $attributes['className'] ) && ! empty( $attributes['className'] ) && strpos( $attributes['className'], 'nxt-lazy-load' ) !== false ) || ( ! empty( $attributes['globalClasses'] ) && strpos( $attributes['globalClasses'], 'nxt-lazy-load' ) !== false ) ) {
			$output_wrap = '<div class="tpgb-lazy-render" data-bid="' . esc_attr( $attributes['block_id'] ) . '"><noscript>' . $output_wrap . '</noscript></div>';
		} else {
			$output_wrap = $output_wrap;
		}

		return $output_wrap;
	}

	/**
	 * Row Block Render Display Rules
	 *
	 * @since 1.2.0
	 * @param mixed $attributes The attributes.
	 * @param mixed $output The output.
	 */
	public static function block_row_conditional_render( $attributes, $output ) {
		if ( isset( $attributes['tpgbDisrule'] ) && ! empty( $attributes['tpgbDisrule'] ) && class_exists( 'Tpgb_Display_Conditions_Rules' ) ) {
			$tpgb_condition_rules = Tpgb_Display_Conditions_Rules::get_instance();
			if ( ! empty( $tpgb_condition_rules ) ) {
				$uid       = ( isset( $attributes['block_id'] ) && ! empty( $attributes['block_id'] ) ) ? $attributes['block_id'] : uniqid( 'block' );
				$check_dis = $tpgb_condition_rules::tpgb_rules_actions( $uid, $attributes );
				if ( false === $check_dis ) {
					$output = '';
				}
			}
		}
		return $output;
	}

	/**
	 * Render block default attributes.
	 *
	 * @return mixed The result.
	 */
	public static function render_block_default_attributes() {

		return array(
			'tpgbDisrule'  => false,
			'disRule'      => 'all',
			'displayRules' => array(
				(object) array(
					'_key'                         => '0',
					'displayKey'                   => 'authentication',
					'tpgb_authentication_value'    => 'authenticated',
					'tpgb_role_value'              => 'administrator',
					'tpgb_os_value'                => 'iphone',
					'tpgb_browser_value'           => 'ie',
					'assigOpr'                     => 'is',
					'tpgb_startdate_value'         => '2021-10-13',
					'tpgb_enddate_value'           => '2021-10-15',
					'tpgb_time_value'              => '12:00',
					'tpgb_day_value'               => '[]',
					'tpgb_post_type_value'         => '[]',
					'tpgb_page_value'              => '[]',
					'tpgb_post_value'              => '[]',
					'tpgb_taxonomy_archive_value'  => '[]',
					'tpgb_author_archive_value'    => '[]',
					'tpgb_post_type_archive_value' => '[]',
					'tpgb_static_page_value'       => 'home',
					'tpgb_date_archive_value'      => 'day',
					'tpgb_search_results_value'    => '',
					'tpgb_single_terms_value'      => '[]',
					'tpgb_single_archive_value'    => '[]',
				),
			),
		);
	}

	/** // phpcs:ignore Squiz.Commenting.FunctionComment, Generic.Commenting.DocComment.ShortNotCapital,Generic.Commenting.DocComment.LongNotCapital,Generic.Commenting.DocComment.MissingShort
	 * Merge Attributes Options Block JSON
	 *
	 * @since V4.0.0
	 * */
	public static function merge_options_json( $block_path = '', $render_callback = '', $adv_opt = true, $carousel_opt = false, $plus_button = false, $equal_hight = false ) {

		if ( empty( $block_path ) ) {
			return;
		}

		$block_data = $block_path . '/block.json';
		if ( is_string( $block_data ) && file_exists( $block_data ) ) {
			$metadata_file = ( ! str_ends_with( $block_data, 'block.json' ) ) ? trailingslashit( $block_data ) . 'block.json' : $block_data;
			$metadata      = wp_json_file_decode( $metadata_file, array( 'associative' => true ) );

			// carousel options.
			if ( ! empty( $carousel_opt ) && true === $carousel_opt ) {
				if ( ! empty( self::$merge_options ) && isset( self::$merge_options['global-carousel'] ) && ! empty( self::$merge_options['global-carousel'] ) ) {
					$metadata['attributes'] = array_merge( self::$merge_options['global-carousel'], $metadata['attributes'] );
				} else {
					$option_path = __DIR__ . '/global-carousel-option.json';
					if ( is_string( $option_path ) && file_exists( $option_path ) ) {
						$option_data = wp_json_file_decode( $option_path, array( 'associative' => true ) );
						if ( ! empty( $option_data ) ) {
							self::$merge_options['global-carousel'] = $option_data;
						}
						if ( ! empty( $option_data ) && ! empty( $metadata ) && isset( $metadata['attributes'] ) ) {
							$metadata['attributes'] = array_merge( $option_data, $metadata['attributes'] );
						}
					}
				}

				if ( defined( 'TPGBP_VERSION' ) && defined( 'TPGBP_PATH' ) ) {
					if ( ! empty( self::$merge_options ) && isset( self::$merge_options['global-pro-carousel'] ) && ! empty( self::$merge_options['global-pro-carousel'] ) ) {
						$metadata['attributes'] = array_merge( self::$merge_options['global-pro-carousel'], $metadata['attributes'] );
					} else {
						$option_path = TPGBP_PATH . 'classes/global-options/global-carousel-option.json';
						if ( is_string( $option_path ) && file_exists( $option_path ) ) {
							$option_data = wp_json_file_decode( $option_path, array( 'associative' => true ) );
							if ( ! empty( $option_data ) ) {
								self::$merge_options['global-pro-carousel'] = $option_data;
							}
							if ( ! empty( $option_data ) && ! empty( $metadata ) && isset( $metadata['attributes'] ) ) {
								$metadata['attributes'] = array_merge( $option_data, $metadata['attributes'] );
							}
						}
					}
				}
			}

			// Plus button Options.
			if ( ! empty( $plus_button ) && true === $plus_button ) {
				if ( ! empty( self::$merge_options ) && isset( self::$merge_options['global-button'] ) && ! empty( self::$merge_options['global-button'] ) ) {
					$metadata['attributes'] = array_merge( $metadata['attributes'], self::$merge_options['global-button'] );
				} else {
					$option_path = __DIR__ . '/global-button-option.json';
					if ( is_string( $option_path ) && file_exists( $option_path ) ) {
						$option_data = wp_json_file_decode( $option_path, array( 'associative' => true ) );
						if ( ! empty( $option_data ) ) {
							self::$merge_options['global-button'] = $option_data;
						}
						if ( ! empty( $option_data ) && ! empty( $metadata ) && isset( $metadata['attributes'] ) ) {
							$metadata['attributes'] = array_merge( $metadata['attributes'], $option_data );
						}
					}
				}
			}

			// Equal Height Option.
			if ( ! empty( $equal_hight ) && true === $equal_hight ) {
				if ( ! empty( self::$merge_options ) && isset( self::$merge_options['global-equal-height'] ) && ! empty( self::$merge_options['global-equal-height'] ) ) {
					$metadata['attributes'] = array_merge( $metadata['attributes'], self::$merge_options['global-equal-height'] );
				} else {
					$option_path = __DIR__ . '/global-equal-height-option.json';
					if ( is_string( $option_path ) && file_exists( $option_path ) ) {
						$option_data = wp_json_file_decode( $option_path, array( 'associative' => true ) );
						if ( ! empty( $option_data ) ) {
							self::$merge_options['global-equal-height'] = $option_data;
						}
						if ( ! empty( $option_data ) && ! empty( $metadata ) && isset( $metadata['attributes'] ) ) {
							$metadata['attributes'] = array_merge( $metadata['attributes'], $option_data );
						}
					}
				}
			}

			// advanced tab options.
			if ( ! empty( $adv_opt ) && true === $adv_opt ) {
				$global_options = array(
					'global-option.json',
					'global-position-option.json',
					'global-plus-option.json',
					'global-display-rules.json',
				);

				if ( ! empty( self::$global_options ) ) {
					if ( ! empty( $metadata ) && isset( $metadata['attributes'] ) ) {
						$metadata['attributes'] = array_merge( $metadata['attributes'], self::$global_options );
					}
				} else {
					foreach ( $global_options as $option ) {
						$option_path = __DIR__ . '/' . $option;
						if ( is_string( $option_path ) && file_exists( $option_path ) ) {
							$option_data = wp_json_file_decode( $option_path, array( 'associative' => true ) );
							if ( ! empty( $option_data ) ) {
								self::$global_options = array_merge( self::$global_options, $option_data );
							}
							if ( ! empty( $option_data ) && ! empty( $metadata ) && isset( $metadata['attributes'] ) ) {
								$metadata['attributes'] = array_merge( $metadata['attributes'], $option_data );
							}
						}
					}
				}

				if ( defined( 'TPGBP_VERSION' ) && defined( 'TPGBP_PATH' ) ) {
					$global_pro_opt = array(
						'global-magic-scroll-option.json',
						'global-plus-extras-option.json',
					);

					if ( ! empty( self::$global_pro_opt ) ) {
						if ( ! empty( $metadata ) && isset( $metadata['attributes'] ) ) {
							$metadata['attributes'] = array_merge( $metadata['attributes'], self::$global_pro_opt );
						}
					} else {

						foreach ( $global_pro_opt as $option ) {
							$option_path = TPGBP_PATH . 'classes/global-options/' . $option;
							if ( is_string( $option_path ) && file_exists( $option_path ) ) {
								$option_data = wp_json_file_decode( $option_path, array( 'associative' => true ) );
								if ( ! empty( $option_data ) ) {
									self::$global_pro_opt = array_merge( self::$global_pro_opt, $option_data );
								}
								if ( ! empty( $option_data ) && ! empty( $metadata ) && isset( $metadata['attributes'] ) ) {
									$metadata['attributes'] = array_merge( $metadata['attributes'], $option_data );
								}
							}
						}
					}
				}
			}

			// render block php.
			if ( ! empty( $metadata ) && ! empty( $render_callback ) ) {
				$metadata['render_callback'] = $render_callback;
			}
			return $metadata;
		}

		return false;
	}

	/**
	 * Extra Options : Equal Height
	 *
	 * @since 4.5.8
	 */
	public static function load_plusEqualHeight_options() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid,WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}
		$options = array(
			'tpgbEqualHeight' => array(
				'type'    => 'boolean',
				'default' => false,
				'scopy'   => true,
			),
			'equalUnqClass'   => array(
				'type'    => 'string',
				'default' => '',
				'scopy'   => true,
			),
		);

		return $options;
	}
}

Tpgb_Blocks_Global_Options::get_instance();
