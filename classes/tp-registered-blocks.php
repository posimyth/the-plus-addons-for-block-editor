<?php
/**
 * Nexter Blocks Registered Lists
 *
 * @since   1.0.0
 * @package TPGB
 */

// phpcs:disable Squiz.Commenting.BlockComment.NoCapital
// phpcs:disable Squiz.Commenting.BlockComment.CloserSameLine
// phpcs:disable Squiz.Commenting.BlockComment.NoEmptyLineBefore
// phpcs:disable Squiz.Commenting.BlockComment.HasEmptyLineBefore
// phpcs:disable Universal.Files.SeparateFunctionsFromOO.Mixed
// phpcs:disable Squiz.PHP.CommentedOutCode.Found
// phpcs:disable WordPress.Files.FileName

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Tpgb registered blocks.
 */
function tpgb_registered_blocks() {
	$tpgb_free = TPGB_PATH . DIRECTORY_SEPARATOR;
	// Blocks class map.
	$load_blocks_css_js = array(
		TPGB_CATEGORY . '/tp-row'                     => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-row/style.css',
			),
			'js'  => array(
				$tpgb_free . 'assets/js/main/tp-row/tpgb-row.min.js',
			),
		),
		TPGB_CATEGORY . '/tp-column'                  => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-column/style.css',
			),
		),
		TPGB_CATEGORY . '/tp-container'               => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-container/style.css',
			),
		),
		TPGB_CATEGORY . '/tp-container-inner'         => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-container-inner/style.css',
			),
		),
		TPGB_CATEGORY . '/tp-code-highlighter'        => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-code-highlighter/style.css',
			),
			'js'  => array(
				$tpgb_free . 'assets/js/extra/code-highlighter/prism.js',
				$tpgb_free . 'assets/js/main/code-highlighter/tpgb-code-highlighter.min.js',
			),
		),
		'tpx-prism-default'                           => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-code-highlighter/theme/default.css',
			),
		),
		'tpx-prism-coy'                               => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-code-highlighter/theme/coy.css',
			),
		),
		'tpx-prism-dark'                              => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-code-highlighter/theme/dark.css',
			),
		),
		'tpx-prism-funky'                             => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-code-highlighter/theme/funky.css',
			),
		),
		'tpx-prism-okaidia'                           => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-code-highlighter/theme/okaidia.css',
			),
		),
		'tpx-prism-solarizedlight'                    => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-code-highlighter/theme/solarizedlight.css',
			),
		),
		'tpx-prism-tomorrownight'                     => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-code-highlighter/theme/tomorrownight.css',
			),
		),
		'tpx-prism-twilight'                          => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-code-highlighter/theme/twilight.css',
			),
		),
		TPGB_CATEGORY . '/tp-pro-paragraph'           => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-pro-paragraph/style.css',
			),
		),
		TPGB_CATEGORY . '/tp-accordion'               => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-accordion/style.css',
			),
			'js'  => array(
				$tpgb_free . 'assets/js/main/common-created/tpgb-slidetoggle-block.min.js',
				$tpgb_free . 'assets/js/main/accordion/tpgb-accordion.min.js',
			),
		),
		TPGB_CATEGORY . '/tp-accordion-inner'         => array(),
		TPGB_CATEGORY . '/tp-button'                  => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-button/style.css',
			),
		),
		TPGB_CATEGORY . '/tp-button-core'             => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-button-core/style.css',
			),
		),
		'tpx-button-style-1'                          => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-button/style/style-1.css',
			),
		),
		'tpx-button-style-2'                          => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-button/style/style-2.css',
			),
		),
		'tpx-button-style-3'                          => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-button/style/style-3.css',
			),
		),
		'tpx-button-style-4'                          => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-button/style/style-4.css',
			),
		),
		'tpx-button-style-5'                          => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-button/style/style-5.css',
			),
		),
		'tpx-button-style-6'                          => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-button/style/style-6.css',
			),
		),
		'tpx-button-style-7'                          => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-button/style/style-7.css',
			),
		),
		'tpx-button-style-8'                          => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-button/style/style-8.css',
			),
		),
		'tpx-button-style-9'                          => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-button/style/style-9.css',
			),
		),
		'tpx-button-style-10'                         => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-button/style/style-10.css',
			),
		),
		'tpx-button-style-11'                         => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-button/style/style-11.css',
			),
		),
		'tpx-button-style-12'                         => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-button/style/style-12.css',
			),
		),
		'tpx-button-style-13'                         => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-button/style/style-13.css',
			),
		),
		'tpx-button-style-14'                         => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-button/style/style-14.css',
			),
		),
		'tpx-button-style-15'                         => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-button/style/style-15.css',
			),
		),
		'tpx-button-style-16'                         => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-button/style/style-16.css',
			),
		),
		'tpx-button-style-17'                         => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-button/style/style-17.css',
			),
		),
		'tpx-button-style-18'                         => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-button/style/style-18.css',
			),
		),
		'tpx-button-style-19'                         => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-button/style/style-19.css',
			),
		),
		'tpx-button-style-20'                         => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-button/style/style-20.css',
			),
		),
		'tpx-button-style-21'                         => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-button/style/style-21.css',
			),
		),
		'tpx-button-style-22'                         => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-button/style/style-22.css',
			),
		),
		'tpx-button-style-23'                         => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-button/style/style-23.css',
			),
		),
		'tpx-button-shake-ani'                        => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-button/shake-ani.css',
			),
		),
		TPGB_CATEGORY . '/tp-countdown'               => array(
			'js' => array(
				$tpgb_free . 'assets/js/main/countdown/countdown.min.js',
			),
		),
		'countdown-style-1'                           => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-countdown/style.css',
			),
			'js'  => array(
				$tpgb_free . 'assets/js/extra/downCount.min.js',
			),
		),
		'countdown-style-2'                           => array(
			'css' => array(
				$tpgb_free . 'assets/css/extra/flipdown.min.css',
				$tpgb_free . 'classes/blocks/tp-countdown/style-2.css',
			),
			'js'  => array(
				$tpgb_free . 'assets/js/extra/flipdown.min.js',
			),
		),
		'countdown-style-3'                           => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-countdown/style-3.css',
			),
			'js'  => array(
				$tpgb_free . 'assets/js/extra/progressbar.min.js',
			),
		),
		TPGB_CATEGORY . '/tp-icon-box'                => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-icon-box/style.css',
			),
		),
		TPGB_CATEGORY . '/tp-data-table'              => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-data-table/style.css',
			),
			'js'  => array(
				$tpgb_free . 'assets/js/extra/datatables.min.js',
				$tpgb_free . 'assets/js/main/data-table/tpgb-data-table.min.js',
			),
		),
		TPGB_CATEGORY . '/tp-dark-mode'               => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-dark-mode/style.css',
			),
			'js'  => array(
				$tpgb_free . 'assets/js/main/dark-mode/tpgb-dark-mode.min.js',
			),
		),
		'tpx-dark-mode-style-1'                       => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-dark-mode/style-1.css',
			),
		),
		'tpx-dark-mode-style-2'                       => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-dark-mode/style-2.css',
			),
		),
		'tpx-dark-mode-style-3'                       => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-dark-mode/style-3.css',
			),
		),
		TPGB_CATEGORY . '/tp-draw-svg'                => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-draw-svg/style.css',
			),
			'js'  => array(
				$tpgb_free . 'assets/js/extra/vivus.min.js',
				$tpgb_free . 'assets/js/main/draw-svg/tpgb-draw-svg.min.js',
			),
		),
		TPGB_CATEGORY . '/tp-messagebox'              => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-messagebox/style.css',
			),
			'js'  => array(
				$tpgb_free . 'assets/js/main/common-created/tpgb-slidetoggle-block.min.js',
				$tpgb_free . 'assets/js/main/messagebox/tpgb-messagebox.min.js',
			),
		),

		TPGB_CATEGORY . '/tp-testimonials'            => array(
			'css' => array(
				$tpgb_free . 'assets/css/extra/bootstrap-grid.min.css',
				$tpgb_free . 'assets/css/extra/splide.min.css',
				$tpgb_free . 'assets/css/main/post-listing/splide-carousel.min.css',
				$tpgb_free . 'classes/blocks/tp-testimonials/style.css',
			),
			'js'  => array(
				$tpgb_free . 'assets/js/extra/splide.min.js',
				$tpgb_free . 'assets/js/main/post-listing/post-splide.min.js',
				$tpgb_free . 'assets/js/main/testimonial/tpgb-testimonial.min.js',
			),
		),
		'tpx-testimonials-style-1'                    => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-testimonials/tp-testimonials-style-1.css',
			),
		),
		'tpx-testimonials-style-2'                    => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-testimonials/tp-testimonials-style-2.css',
			),
		),
		'tpx-testimonials-style-3'                    => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-testimonials/tp-testimonials-style-3.css',
			),
		),
		'tpx-testimonials-scroll'                     => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-testimonials/tp-testimonials-scroll.css',
			),
		),
		TPGB_CATEGORY . '/tp-stylist-list'            => array(
			'css' => array(
				$tpgb_free . 'assets/css/extra/tippy.css',
				$tpgb_free . 'classes/blocks/tp-stylist-list/style.css',
			),
			'js'  => array(
				$tpgb_free . 'assets/js/extra/popper.min.js',
				$tpgb_free . 'assets/js/extra/tippy.min.js',
				$tpgb_free . 'assets/js/main/stylist-list/tp-stylist-list.min.js',
			),
		),
		'tpx-stylist-list-hover-bg'                   => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-stylist-list/style-hover-bg.css',
			),
		),
		'tpx-stylist-list-hover-inverse'              => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-stylist-list/style-hover-inverse.css',
			),
		),
		'tpx-stylist-list-hover-inverse'              => array( // phpcs:ignore Universal.Arrays.DuplicateArrayKey.Found
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-stylist-list/style-hover-inverse.css',
			),
		),
		TPGB_CATEGORY . '/tp-empty-space'             => array(),
		TPGB_CATEGORY . '/tp-external-form-styler'    => array(
			'css' => array(
				$tpgb_free . 'assets/css/extra/bootstrap-grid.min.css',
				$tpgb_free . 'classes/blocks/tp-external-form-styler/style.css',
			),
		),
		'tpx-contact-form-7'                          => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-external-form-styler/forms/cf7.css',
			),
			'js'  => array(
				$tpgb_free . 'assets/js/main/external-form-styler/tpgb-cf7.min.js',
			),
		),
		'tpx-everest-form'                            => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-external-form-styler/forms/everest.css',
			),
			'js'  => array(
				$tpgb_free . 'assets/js/main/external-form-styler/tpgb-everest-form.min.js',
			),
		),
		'tpx-gravity-form'                            => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-external-form-styler/forms/gravity.css',
			),
			'js'  => array(
				$tpgb_free . 'assets/js/main/external-form-styler/tpgb-gravity-form.min.js',
			),
		),
		'tpx-ninja-form'                              => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-external-form-styler/forms/ninja.css',
			),
		),
		'tpx-wp-form'                                 => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-external-form-styler/forms/wp.css',
			),
			'js'  => array(
				$tpgb_free . 'assets/js/main/external-form-styler/tpgb-wp-form.min.js',
			),
		),
		TPGB_CATEGORY . '/tp-hovercard'               => array(),
		TPGB_CATEGORY . '/tp-heading'                 => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-heading/style.css',
			),
		),
		TPGB_CATEGORY . '/tp-heading-title'           => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-heading-title/style.css',
			),
		),
		'tpx-heading-title-style-1'                   => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-heading-title/style/style-1.css',
			),
		),
		'tpx-heading-title-style-3'                   => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-heading-title/style/style-3.css',
			),
		),
		'tpx-heading-title-style-4'                   => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-heading-title/style/style-4.css',
			),
		),
		'tpx-heading-title-style-5'                   => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-heading-title/style/style-5.css',
			),
		),
		'tpx-heading-title-style-6'                   => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-heading-title/style/style-6.css',
			),
		),
		'tpx-heading-title-style-8'                   => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-heading-title/style/style-8.css',
			),
		),
		TPGB_CATEGORY . '/tp-progress-bar'            => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-progress-bar/style.css',
			),
			'js'  => array(
				$tpgb_free . 'assets/js/extra/waypoints.min.js',
			),
		),
		'tpx-progressbar'                             => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-progress-bar/progressbar.css',
			),
			'js'  => array(
				$tpgb_free . 'assets/js/main/progress-bar/plus-progressbar.min.js',
			),
		),
		'tpx-piechart'                                => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-progress-bar/piechart.css',
			),
			'js'  => array(
				$tpgb_free . 'assets/js/extra/circle-progress.min.js',
				$tpgb_free . 'assets/js/main/progress-bar/plus-piechart.min.js',
			),
		),
		TPGB_CATEGORY . '/tp-progress-tracker'        => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-progress-tracker/style.css',
			),
			'js'  => array(
				$tpgb_free . 'assets/js/main/progress-tracker/tpgb-progress-tracker.min.js',
			),
		),
		TPGB_CATEGORY . '/tp-pricing-list'            => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-pricing-list/style.css',
			),
		),
		'tpx-pricing-list-style-1'                    => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-pricing-list/style-1.css',
			),
		),
		'tpx-pricing-list-style-2'                    => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-pricing-list/style-2.css',
			),
		),
		'tpx-pricing-list-style-3'                    => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-pricing-list/style-3.css',
			),
		),
		'tpx-pricing-list-masking'                    => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-pricing-list/masking.css',
			),
		),
		TPGB_CATEGORY . '/tp-navigation-builder'      => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-navigation-builder/style.css',
			),
			'js'  => array(
				$tpgb_free . 'assets/js/main/common-created/tpgb-slidetoggle-block.min.js',
				$tpgb_free . 'assets/js/main/common-created/tpgb-fade-in-out.min.js',
				$tpgb_free . 'assets/js/main/navigation-builder/tpgb-nav.min.js',
			),
		),
		TPGB_CATEGORY . '/tp-number-counter'          => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-number-counter/style.css',
			),
			'js'  => array(
				$tpgb_free . 'assets/js/extra/numscroller.min.js',
			),
		),
		'tpx-number-counter-s2'                       => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-number-counter/style-2.css',
			),
		),
		TPGB_CATEGORY . '/tp-image'                   => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-image/style.css',
			),
		),
		TPGB_CATEGORY . '/tp-infobox'                 => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-infobox/style.css',
			),
		),
		'tpx-infobox-style-1'                         => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-infobox/style-1.css',
			),
		),
		'tpx-infobox-style-2'                         => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-infobox/style-2.css',
			),
		),
		'tpx-infobox-style-3'                         => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-infobox/style-3.css',
			),
		),
		'tpx-infobox-style-5'                         => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-infobox/style-5.css',
			),
		),
		'tpx-infobox-style-6'                         => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-infobox/style-6.css',
			),
		),
		TPGB_CATEGORY . '/tp-interactive-circle-info' => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-interactive-circle-info/style.css',
			),
			'js'  => array(
				$tpgb_free . 'assets/js/main/interactive-circle-info/tpgb-interactive-circle-info.min.js',
			),
		),
		TPGB_CATEGORY . '/tp-flipbox'                 => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-flipbox/style.css',
			),
		),
		TPGB_CATEGORY . '/tp-form-block'              => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-form-block/style.css',
			),
			'js'  => array(
				$tpgb_free . 'assets/js/main/form-block/tpgb-form-block.min.js',
			),
		),
		TPGB_CATEGORY . '/tp-google-map'              => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-google-map/style.css',
			),
			'js'  => array(
				$tpgb_free . 'assets/js/main/google-map/tpgb-google-map.min.js',
			),
		),

		TPGB_CATEGORY . '/tp-tabs-tours'              => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-tabs-tours/style.css',
			),
			'js'  => array(
				$tpgb_free . 'assets/js/main/tabs-tours/plus-tabs-tours.min.js',
			),
		),
		'tpx-tabs-tours-vertical'                     => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-tabs-tours/style-vertical.css',
			),
		),
		TPGB_CATEGORY . '/tp-tab-item'                => array(),
		TPGB_CATEGORY . '/tp-blockquote'              => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-blockquote/style.css',
			),
		),
		'tpx-blockquote-style-2'                      => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-blockquote/style-2.css',
			),
		),
		TPGB_CATEGORY . '/tp-breadcrumbs'             => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-breadcrumbs/style.css',
			),
		),
		'tpx-breadcrumbs-style-1'                     => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-breadcrumbs/style-1.css',
			),
		),
		'tpx-breadcrumbs-style-2'                     => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-breadcrumbs/style-2.css',
			),
		),
		TPGB_CATEGORY . '/tp-video'                   => array(
			'css' => array(
				$tpgb_free . 'assets/css/extra/fancybox.css',
				$tpgb_free . 'classes/blocks/tp-video/style.css',
			),
			'js'  => array(
				$tpgb_free . 'assets/js/extra/fancybox.umd.js',
				$tpgb_free . 'assets/js/main/video/plus-video-player.min.js',
			),
		),
		'nxt-video-fallback-image'                    => array(
			'js' => array(
				$tpgb_free . 'assets/js/main/video/nxt-video-fallback-image-min.js',
			),
		),
		TPGB_CATEGORY . '/tp-creative-image'          => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-creative-image/style.css',
			),
			'js'  => array(
				$tpgb_free . 'assets/js/main/creative-image/plus-image-factory.min.js',
			),
		),
		'tpx-tp-image-mask-img'                       => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-creative-image/style-mask-image.css',
			),
		),
		TPGB_CATEGORY . '/tp-social-icons'            => array(
			'css' => array(
				$tpgb_free . 'assets/css/extra/tippy.css',
				$tpgb_free . 'classes/blocks/tp-social-icons/style.css',
			),
			'js'  => array(
				$tpgb_free . 'assets/js/extra/popper.min.js',
				$tpgb_free . 'assets/js/extra/tippy.min.js',
				$tpgb_free . 'assets/js/main/social-icons/tpgb-social-icons.min.js',
			),
		),
		'tpx-social-icons-style-1'                    => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-social-icons/style/style-1.css',
			),
		),
		'tpx-social-icons-style-2'                    => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-social-icons/style/style-2.css',
			),
		),
		'tpx-social-icons-style-3'                    => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-social-icons/style/style-3.css',
			),
		),
		'tpx-social-icons-style-4'                    => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-social-icons/style/style-4.css',
			),
		),
		'tpx-social-icons-style-5'                    => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-social-icons/style/style-5.css',
			),
		),
		'tpx-social-icons-style-6'                    => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-social-icons/style/style-6.css',
			),
		),
		'tpx-social-icons-style-7'                    => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-social-icons/style/style-7.css',
			),
		),
		'tpx-social-icons-style-8'                    => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-social-icons/style/style-8.css',
			),
		),
		'tpx-social-icons-style-9'                    => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-social-icons/style/style-9.css',
			),
		),
		'tpx-social-icons-style-10'                   => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-social-icons/style/style-10.css',
			),
		),
		'tpx-social-icons-style-11'                   => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-social-icons/style/style-11.css',
			),
		),
		'tpx-social-icons-style-12'                   => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-social-icons/style/style-12.css',
			),
		),
		'tpx-social-icons-style-13'                   => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-social-icons/style/style-13.css',
			),
		),
		'tpx-social-icons-style-14'                   => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-social-icons/style/style-14.css',
			),
		),
		'tpx-social-icons-style-15'                   => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-social-icons/style/style-15.css',
			),
		),
		'tpx-social-icons-style-16'                   => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-social-icons/style/style-16.css',
			),
		),
		TPGB_CATEGORY . '/tp-social-feed'             => array(
			'css' => array(
				$tpgb_free . 'assets/css/extra/fancybox.css',
				$tpgb_free . 'assets/css/extra/bootstrap-grid.min.css',
				$tpgb_free . 'classes/blocks/tp-social-feed/style.css',
			),
			'js'  => array(
				$tpgb_free . 'assets/js/extra/fancybox.umd.js',
				$tpgb_free . 'assets/js/extra/isotope.pkgd.min.js',
				$tpgb_free . 'assets/js/main/post-listing/post-masonry.min.js',
				$tpgb_free . 'assets/js/main/social-feed/tp-social-feed.min.js',
			),
		),
		'tpx-social-feed-style-3'                     => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-social-feed/style-3.css',
			),
		),
		'tpx-social-feed-style-4'                     => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-social-feed/style-4.css',
			),
		),
		TPGB_CATEGORY . '/tp-social-reviews'          => array(
			'css' => array(
				$tpgb_free . 'assets/css/extra/bootstrap-grid.min.css',
				$tpgb_free . 'classes/blocks/tp-social-reviews/style.css',
			),
			'js'  => array(
				$tpgb_free . 'assets/js/extra/isotope.pkgd.min.js',
				$tpgb_free . 'assets/js/main/post-listing/post-masonry.min.js',
				$tpgb_free . 'assets/js/main/social-reviews/tp-social-reviews.min.js',
			),
		),
		'tpx-review-style-1'                          => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-social-reviews/review/style-1.css',
			),
		),
		'tpx-review-style-2'                          => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-social-reviews/review/style-2.css',
			),
		),
		'tpx-review-style-3'                          => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-social-reviews/review/style-3.css',
			),
		),
		TPGB_CATEGORY . '/tp-switcher'                => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-switcher/style.css',
			),
			'js'  => array(
				$tpgb_free . 'assets/js/main/switcher/tpgb-switcher.min.js',
			),
		),
		TPGB_CATEGORY . '/tp-switch-inner'            => array(),
		TPGB_CATEGORY . '/tp-post-title'              => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-post-title/style.css',
			),
		),
		TPGB_CATEGORY . '/tp-post-content'            => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-post-content/style.css',
			),
		),
		TPGB_CATEGORY . '/tp-post-image'              => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-post-image/style.css',
			),
			'js'  => array(
				$tpgb_free . 'assets/js/main/tp-post-image/tpgb-post-image.min.js',
			),
		),
		TPGB_CATEGORY . '/tp-post-listing'            => array(
			'css' => array(
				$tpgb_free . 'assets/css/extra/bootstrap-grid.min.css',
				$tpgb_free . 'classes/blocks/tp-post-listing/style.css',
			),
			'js'  => array(
				$tpgb_free . 'assets/js/extra/isotope.pkgd.min.js',
				$tpgb_free . 'assets/js/main/post-listing/post-masonry.min.js',
			),
		),
		'tpx-post-listing-style-1'                    => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-post-listing/tp-post-listing-style-1.css',
			),
			'js'  => array(
				$tpgb_free . 'assets/js/main/common-created/tpgb-slidetoggle-block.min.js',
				$tpgb_free . 'assets/js/main/post-listing/post-listing.min.js',
			),
		),
		'tpx-post-listing-style-2'                    => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-post-listing/tp-post-listing-style-2.css',
			),
		),
		'tpgb-pagination'                             => array(
			'css' => array(
				$tpgb_free . 'assets/css/main/post-listing/tpgb-pagination.css',
			),
		),
		'tpx-post-metro'                              => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-post-listing/tp-post-metro.css',
			),
			'js'  => array(
				$tpgb_free . 'assets/js/main/post-listing/post-metro.min.js',
			),
		),
		TPGB_CATEGORY . '/tp-post-author'             => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-post-author/style.css',
			),
		),
		'tpx-tp-post-author-style-2'                  => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-post-author/style-author-style-2.css',
			),
		),
		'tpx-tp-post-author-avatar'                   => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-post-author/style-author-avatar.css',
			),
		),
		'tpx-tp-post-author-social'                   => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-post-author/style-author-social.css',
			),
		),
		'tpx-tp-post-author-role'                     => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-post-author/style-author-role.css',
			),
		),
		TPGB_CATEGORY . '/tp-post-meta'               => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-post-meta/style.css',
			),
		),
		'tpx-tp-post-meta-category'                   => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-post-meta/style-category.css',
			),
		),
		'tpx-tp-post-layout-2'                        => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-post-meta/style-layout-2.css',
			),
		),
		TPGB_CATEGORY . '/tp-post-comment'            => array(
			'css' => array(
				$tpgb_free . 'assets/css/extra/bootstrap-grid.min.css',
				$tpgb_free . 'classes/blocks/tp-post-comment/style.css',
			),
		),
		TPGB_CATEGORY . '/tp-search-bar'              => array(
			'css' => array(
				$tpgb_free . 'assets/css/extra/bootstrap-grid.min.css',
				$tpgb_free . 'assets/css/main/post-listing/tpgb-post-load.css',
				$tpgb_free . 'classes/blocks/tp-search-bar/style.css',
			),
			'js'  => array(
				$tpgb_free . 'assets/js/main/common-created/tpgb-slidetoggle-block.min.js',
				$tpgb_free . 'assets/js/main/search-bar/tpgb-search-bar.min.js',
			),
		),
		TPGB_CATEGORY . '/tp-site-logo'               => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-site-logo/style.css',
			),
		),
		TPGB_CATEGORY . '/tp-smooth-scroll'           => array(
			'js' => array(
				$tpgb_free . 'assets/js/extra/smoothscroll.min.js',
				$tpgb_free . 'assets/js/main/smooth-scroll/tpgb-smooth-scroll.min.js',
			),
		),
		'tpx-smooth-navigation'                       => array(
			'js' => array(
				$tpgb_free . 'assets/js/main/smooth-scroll/smooth-navigation.min.js',
			),
		),
		TPGB_CATEGORY . '/tp-team-listing'            => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-team-listing/style.css',
				$tpgb_free . 'assets/css/extra/bootstrap-grid.min.css',
			),
		),
		TPGB_CATEGORY . '/tp-social-embed'            => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-social-embed/style.css',
			),
		),
		TPGB_CATEGORY . '/tp-pricing-table'           => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-pricing-table/style.css',
			),
			'js'  => array(
				$tpgb_free . 'assets/js/main/pricing-table/tp-pricing-table.min.js',
			),
		),
		'tpx-pricing-table-content-stylish'           => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-pricing-table/content/stylish.css',
			),
		),
		'tpx-pricing-table-ribbon'                    => array(
			'css' => array(
				$tpgb_free . 'classes/blocks/tp-pricing-table/ribbon/style.css',
			),
		),
		'carouselSlider'                              => array(
			'css' => array(
				$tpgb_free . 'assets/css/extra/splide.min.css',
				$tpgb_free . 'assets/css/main/post-listing/splide-carousel.min.css',
			),
			'js'  => array(
				$tpgb_free . 'assets/js/extra/splide.min.js',
				$tpgb_free . 'assets/js/main/post-listing/post-splide.min.js',
			),
		),
		'carouselautoScroll'                          => array(
			'js' => array(
				$tpgb_free . 'assets/js/extra/splide-extension-auto-scroll.min.js',
			),
		),
		'content-hover-effect'                        => array(
			'css' => array(
				$tpgb_free . 'assets/css/main/plus-extras/plus-content-hover-effect.css',
			),
		),
		'tpgb-group-button'                           => array(
			'css' => array(
				$tpgb_free . 'assets/css/main/plus-extras/plus-group-button.css',
			),
		),
		'tpgb-animation'                              => array(
			'css' => array(
				$tpgb_free . 'assets/css/extra/animate.min.css',
			),
			'js'  => array(
				$tpgb_free . 'assets/js/extra/waypoints.min.js',
				$tpgb_free . 'assets/js/main/plus-extras/plus-animation.min.js',
			),
		),
		'tpgb-animation-fadeIn'                       => array(
			'css' => array(
				$tpgb_free . 'assets/css/extra/animate-fadeIn.css',
			),
		),
		'tpgb-animation-fadeOut'                      => array(
			'css' => array(
				$tpgb_free . 'assets/css/extra/animate-fadeOut.css',
			),
		),
		'tpgb-animation-flipIn'                       => array(
			'css' => array(
				$tpgb_free . 'assets/css/extra/animate-flipIn.css',
			),
		),
		'tpgb-animation-flipOut'                      => array(
			'css' => array(
				$tpgb_free . 'assets/css/extra/animate-flipOut.css',
			),
		),
		'tpgb-animation-lightSpeedIn'                 => array(
			'css' => array(
				$tpgb_free . 'assets/css/extra/animate-lightSpeedIn.css',
			),
		),
		'tpgb-animation-lightSpeedOut'                => array(
			'css' => array(
				$tpgb_free . 'assets/css/extra/animate-lightSpeedOut.css',
			),
		),
		'tpgb-animation-rollIn'                       => array(
			'css' => array(
				$tpgb_free . 'assets/css/extra/animate-rollIn.css',
			),
		),
		'tpgb-animation-rollOut'                      => array(
			'css' => array(
				$tpgb_free . 'assets/css/extra/animate-rollOut.css',
			),
		),
		'tpgb-animation-rotateIn'                     => array(
			'css' => array(
				$tpgb_free . 'assets/css/extra/animate-rotateIn.css',
			),
		),
		'tpgb-animation-rotateOut'                    => array(
			'css' => array(
				$tpgb_free . 'assets/css/extra/animate-rotateOut.css',
			),
		),
		'tpgb-animation-seekers'                      => array(
			'css' => array(
				$tpgb_free . 'assets/css/extra/animate-seekers.css',
			),
		),
		'tpgb-animation-slideIn'                      => array(
			'css' => array(
				$tpgb_free . 'assets/css/extra/animate-slideIn.css',
			),
		),
		'tpgb-animation-slideOut'                     => array(
			'css' => array(
				$tpgb_free . 'assets/css/extra/animate-slideOut.css',
			),
		),
		'tpgb-animation-zoomIn'                       => array(
			'css' => array(
				$tpgb_free . 'assets/css/extra/animate-zoomIn.css',
			),
		),
		'tpgb-animation-zoomOut'                      => array(
			'css' => array(
				$tpgb_free . 'assets/css/extra/animate-zoomOut.css',
			),
		),
		'tpgb-draw-svg'                               => array(
			'js' => array(
				$tpgb_free . 'assets/js/extra/vivus.min.js',
				$tpgb_free . 'assets/js/main/draw-svg/tpgb-draw-svg.min.js',
			),
		),
		'tpgb-plus-hover-effect'                      => array(
			'css' => array(
				$tpgb_free . 'assets/css/main/plus-extras/plus-hover-effect.css',
			),
		),
		'tpgb-fancy-box'                              => array(
			'css' => array(
				$tpgb_free . 'assets/css/extra/fancybox.css',
			),
			'js'  => array(
				$tpgb_free . 'assets/js/extra/fancybox.umd.js',
			),
		),
		'tpgb-fancy-custom'                           => array(
			'js' => array(
				$tpgb_free . 'assets/js/main/fancy-box/tpgb-fancy-box.min.js',
			),
		),
		'tpgb-row-column-link'                        => array(
			'js' => array(
				$tpgb_free . 'assets/js/main/tp-row/tpgb-link.min.js',
			),
		),
		'tpgb_grid_layout'                            => array(
			'js' => array(
				$tpgb_free . 'assets/js/extra/isotope.pkgd.min.js',
				$tpgb_free . 'assets/js/main/post-listing/post-masonry.min.js',
			),
		),
		'equal-height'                                => array(
			'js' => array(
				$tpgb_free . 'assets/js/main/plus-extras/plus-equal-height.min.js',
			),
		),
	);

	if ( has_filter( 'tpgb_blocks_register' ) ) {
		$load_blocks_css_js = apply_filters( 'tpgb_blocks_register', $load_blocks_css_js );
	}

	return $load_blocks_css_js;
}

/**
 * Tpgb_ Library.
 *
 * @since 1.0.0
 */
class Tpgb_Library {
	/**
	 * A reference to an instance of this class.
	 *
	 * @since 1.0.0
	 * @var   object
	 */
	private static $instance = null;

	/**
	 * Tpgb registered blocks.
	 *
	 * @var mixed
	 */
	public $tpgb_registered_blocks;
	/**
	 * Plus template blocks.
	 *
	 * @var mixed
	 */
	public $plus_template_blocks;

	/**
	 * Transient blocks.
	 *
	 * @var array
	 */
	public $transient_blocks = array();

	/**
	 * Post assets object.
	 *
	 * @var array
	 */
	public $post_assets_object = array();
	/**
	 * Block ids.
	 *
	 * @var array
	 */
	public $block_ids = array();

	/**
	 * Plus uid.
	 *
	 * @var mixed
	 */
	public $plus_uid = null;
	/**
	 * Requires update.
	 *
	 * @var mixed
	 */
	public $requires_update;
	/**
	 * Tpgb cache.
	 *
	 * @var mixed
	 */
	public $tpgb_cache = null;
	/**
	 * Tpgb delay perf.
	 *
	 * @var mixed
	 */
	public $tpgb_delay_perf = null;
	/**
	 * Tpgb defer perf.
	 *
	 * @var mixed
	 */
	public $tpgb_defer_perf = null;

	/**
	 * Blocks list.
	 *
	 * @var array
	 */
	private static $blocks_list = array();
	/**
	 * Blocks render.
	 *
	 * @var array
	 */
	private static $blocks_render = array();

	/**
	 * Tpgb post type.
	 *
	 * @var string
	 */
	private static $tpgb_post_type = '';
	/**
	 * Tpgb post id.
	 *
	 * @var string
	 */
	private static $tpgb_post_id = '';
	/**
	 * Dependency.
	 *
	 * @var bool
	 */
	public $dependency = false;
	/**
	 * Localize.
	 *
	 * @var string
	 */
	public $localize = '';
	/**
	 * Learn view item.
	 *
	 * @var bool
	 */
	public $learn_view_item = false;

	/**
	 * Data cache.
	 *
	 * @var array
	 */
	public $data_cache = array();
	/**
	 * Save cache.
	 *
	 * @var bool
	 */
	public $save_cache = false;
	/**
	 * Is preview mode.
	 *
	 * @var mixed
	 */
	public $is_preview_mode = null;

	/**
	 *  Return array of registered elements.
	 *
	 * @todo filter output
	 */
	public function get_registered_blocks() {
		$this->tpgb_registered_blocks = tpgb_registered_blocks();
		return $this->tpgb_registered_blocks;
	}

	/**
	 * Get Caching System Load Nexter
	 *
	 * @since 1.4.0
	 */
	public function get_caching_option() {
		if ( null !== $this->tpgb_cache ) {
			return $this->tpgb_cache;
		}
		$caching_opt = get_option( 'tpgb_performance_cache' );
		if ( ! empty( $caching_opt ) && 'separate' === $caching_opt ) {
			$this->tpgb_cache = 'separate';
			return 'separate';
		} else {
			$this->tpgb_cache = false;
			return false;
		}
	}

	/**
	 * Get Delay Css Js Best Performance
	 *
	 * @since 2.0.0
	 */
	public function get_delay_css_js() {
		if ( null !== $this->tpgb_delay_perf ) {
			return $this->tpgb_delay_perf;
		}
		$delay_opt = get_option( 'tpgb_delay_css_js' );
		if ( isset( $delay_opt ) && 'true' === $delay_opt ) {
			$this->tpgb_delay_perf = true;
			return true;
		} else {
			$this->tpgb_delay_perf = false;
			return false;
		}
	}

	/**
	 * Get Defer Css Js Best Performance
	 *
	 * @since 2.0.0
	 */
	public function get_defer_css_js() {
		if ( null !== $this->tpgb_defer_perf ) {
			return $this->tpgb_defer_perf;
		}
		$defer_opt = get_option( 'tpgb_defer_css_js' );
		if ( isset( $defer_opt ) && 'true' === $defer_opt ) {
			$this->tpgb_defer_perf = true;
			return true;
		} else {
			$this->tpgb_defer_perf = false;
			return false;
		}
	}

	/**
	 * Return saved settings
	 *
	 * @since 1.1.1
	 * @param mixed $block The block.
	 */
	public function get_plus_block_settings( $block = null ) {

		$replace = array(
			TPGB_CATEGORY . '/tp-accordion'               => TPGB_CATEGORY . '/tp-accordion',
			TPGB_CATEGORY . '/tp-accordion-inner'         => TPGB_CATEGORY . '/tp-accordion-inner',
			TPGB_CATEGORY . '/tp-blockquote'              => TPGB_CATEGORY . '/tp-blockquote',
			TPGB_CATEGORY . '/tp-breadcrumbs'             => TPGB_CATEGORY . '/tp-breadcrumbs',
			TPGB_CATEGORY . '/tp-button'                  => TPGB_CATEGORY . '/tp-button',
			TPGB_CATEGORY . '/tp-button-core'             => TPGB_CATEGORY . '/tp-button-core',
			TPGB_CATEGORY . '/tp-column'                  => TPGB_CATEGORY . '/tp-column',
			TPGB_CATEGORY . '/tp-code-highlighter'        => TPGB_CATEGORY . '/tp-code-highlighter',
			TPGB_CATEGORY . '/tp-countdown'               => TPGB_CATEGORY . '/tp-countdown',
			TPGB_CATEGORY . '/tp-creative-image'          => TPGB_CATEGORY . '/tp-creative-image',
			TPGB_CATEGORY . '/tp-data-table'              => TPGB_CATEGORY . '/tp-data-table',
			TPGB_CATEGORY . '/tp-dark-mode'               => TPGB_CATEGORY . '/tp-dark-mode',
			TPGB_CATEGORY . '/tp-draw-svg'                => TPGB_CATEGORY . '/tp-draw-svg',
			TPGB_CATEGORY . '/tp-empty-space'             => TPGB_CATEGORY . '/tp-empty-space',
			TPGB_CATEGORY . '/tp-external-form-styler'    => TPGB_CATEGORY . '/tp-external-form-styler',
			TPGB_CATEGORY . '/tp-flipbox'                 => TPGB_CATEGORY . '/tp-flipbox',
			TPGB_CATEGORY . '/tp-form-block'              => TPGB_CATEGORY . '/tp-form-block',
			TPGB_CATEGORY . '/tp-google-map'              => TPGB_CATEGORY . '/tp-google-map',
			TPGB_CATEGORY . '/tp-heading'                 => TPGB_CATEGORY . '/tp-heading',
			TPGB_CATEGORY . '/tp-heading-title'           => TPGB_CATEGORY . '/tp-heading-title',
			TPGB_CATEGORY . '/tp-hovercard'               => TPGB_CATEGORY . '/tp-hovercard',
			TPGB_CATEGORY . '/tp-icon-box'                => TPGB_CATEGORY . '/tp-icon-box',
			TPGB_CATEGORY . '/tp-image'                   => TPGB_CATEGORY . '/tp-image',
			TPGB_CATEGORY . '/tp-infobox'                 => TPGB_CATEGORY . '/tp-infobox',
			TPGB_CATEGORY . '/tp-interactive-circle-info' => TPGB_CATEGORY . '/tp-interactive-circle-info',
			TPGB_CATEGORY . '/tp-messagebox'              => TPGB_CATEGORY . '/tp-messagebox',
			TPGB_CATEGORY . '/tp-navigation-builder'      => TPGB_CATEGORY . '/tp-navigation-builder',
			TPGB_CATEGORY . '/tp-number-counter'          => TPGB_CATEGORY . '/tp-number-counter',
			TPGB_CATEGORY . '/tp-pricing-list'            => TPGB_CATEGORY . '/tp-pricing-list',
			TPGB_CATEGORY . '/tp-pricing-table'           => TPGB_CATEGORY . '/tp-pricing-table',
			TPGB_CATEGORY . '/tp-pro-paragraph'           => TPGB_CATEGORY . '/tp-pro-paragraph',
			TPGB_CATEGORY . '/tp-progress-bar'            => TPGB_CATEGORY . '/tp-progress-bar',
			TPGB_CATEGORY . '/tp-progress-tracker'        => TPGB_CATEGORY . '/tp-progress-tracker',
			TPGB_CATEGORY . '/tp-row'                     => TPGB_CATEGORY . '/tp-row',
			TPGB_CATEGORY . '/tp-search-bar'              => TPGB_CATEGORY . '/tp-search-bar',
			TPGB_CATEGORY . '/tp-stylist-list'            => TPGB_CATEGORY . '/tp-stylist-list',
			TPGB_CATEGORY . '/tp-social-icons'            => TPGB_CATEGORY . '/tp-social-icons',
			TPGB_CATEGORY . '/tp-social-feed'             => TPGB_CATEGORY . '/tp-social-feed',
			TPGB_CATEGORY . '/tp-social-reviews'          => TPGB_CATEGORY . '/tp-social-reviews',
			TPGB_CATEGORY . '/tp-tabs-tours'              => TPGB_CATEGORY . '/tp-tabs-tours',
			TPGB_CATEGORY . '/tp-tab-item'                => TPGB_CATEGORY . '/tp-tab-item',
			TPGB_CATEGORY . '/tp-team-listing'            => TPGB_CATEGORY . '/tp-team-listing',
			TPGB_CATEGORY . '/tp-testimonials'            => TPGB_CATEGORY . '/tp-testimonials',
			TPGB_CATEGORY . '/tp-video'                   => TPGB_CATEGORY . '/tp-video',
			TPGB_CATEGORY . '/tp-post-title'              => TPGB_CATEGORY . '/tp-post-title',
			TPGB_CATEGORY . '/tp-post-content'            => TPGB_CATEGORY . '/tp-post-content',
			TPGB_CATEGORY . '/tp-post-image'              => TPGB_CATEGORY . '/tp-post-image',
			TPGB_CATEGORY . '/tp-post-author'             => TPGB_CATEGORY . '/tp-post-author',
			TPGB_CATEGORY . '/tp-post-listing'            => TPGB_CATEGORY . '/tp-post-listing',
			TPGB_CATEGORY . '/tp-post-meta'               => TPGB_CATEGORY . '/tp-post-meta',
			TPGB_CATEGORY . '/tp-post-comment'            => TPGB_CATEGORY . '/tp-post-comment',
			TPGB_CATEGORY . '/tp-site-logo'               => TPGB_CATEGORY . '/tp-site-logo',
			TPGB_CATEGORY . '/tp-smooth-scroll'           => TPGB_CATEGORY . '/tp-smooth-scroll',
			TPGB_CATEGORY . '/tp-social-embed'            => TPGB_CATEGORY . '/tp-social-embed',
			TPGB_CATEGORY . '/tp-switcher'                => TPGB_CATEGORY . '/tp-switcher',
			TPGB_CATEGORY . '/tp-switch-inner'            => TPGB_CATEGORY . '/tp-switch-inner',
			TPGB_CATEGORY . '/tp-container'               => TPGB_CATEGORY . '/tp-container',
			TPGB_CATEGORY . '/tp-container-inner'         => TPGB_CATEGORY . '/tp-container-inner',
		);

		if ( has_filter( 'tpgb_blocks_register_render' ) ) {
			$replace = apply_filters( 'tpgb_blocks_register_render', $replace );
		}

		$blocks = WP_Block_Type_Registry::get_instance()->get_all_registered();

		// Nexter Extras Options Array.
		$all_tpgb_blocks = $this->tpgb_registered_blocks;
		$plus_extras     = array_filter(
			$all_tpgb_blocks,
			function ( $key ) {
				return strpos( $key, 'tpgb/' ) === 0 ? '' : $key;
			},
			ARRAY_FILTER_USE_KEY
		);

		$plus_extras = array_keys( $plus_extras );
		if ( has_filter( 'tpgb_exrta_conditions_blocks_register' ) ) {
			$plus_extras = apply_filters( 'tpgb_exrta_conditions_blocks_register', $plus_extras );
		}
		$plus_extras = array_unique( $plus_extras );

		if ( empty( $blocks ) ) {
			$blocks = array_keys( $replace );
		} else {
			$blocks = array_keys( $blocks );
		}

		$blocks = array_map(
			function ( $val ) use ( $replace ) {
				return ( array_key_exists( $val, $replace ) ? $replace[ $val ] : '' );
			},
			$blocks
		);

		$blocks = array_merge( $plus_extras, $blocks );

		$load_blocks = ( isset( $block ) ? ( isset( $blocks[ $block ] ) ? $blocks[ $block ] : 0 ) : array_filter( $blocks ) );

		asort( $load_blocks );

		return $load_blocks;
	}

	/**
	 * Check if block editor preview mode or not
	 *
	 * @since 1.0.0
	 */
	public function is_preview_mode() {
		if ( null !== $this->is_preview_mode ) {
			return $this->is_preview_mode;
		}

		$preview_mode = false;

		require_once ABSPATH . 'wp-admin/includes/screen.php';
		if ( ! is_null( get_current_screen() ) && get_current_screen()->is_block_editor() ) {
			$preview_mode = true;
		}

		$this->is_preview_mode = $preview_mode;

		return $preview_mode;
	}

	/**
	 * Check if cache files exists
	 *
	 * @since 1.0.0
	 * @param mixed $post_type The post type.
	 * @param mixed $post_id The post id.
	 * @param mixed $extension The extension.
	 * @param bool  $preload The preload.
	 */
	public function check_cache_files( $post_type = null, $post_id = null, $extension = 'both', $preload = false ) {
		$filename = '';
		if ( ! empty( $preload ) ) {
			$filename = 'preload-';
		}
		$css_url = TPGB_ASSET_PATH . DIRECTORY_SEPARATOR . ( $post_type ? 'theplus-' . $filename . $post_type : 'theplus' ) . ( isset( $post_id ) ? '-' . $post_id : '' ) . '.min.css';
		$js_url  = TPGB_ASSET_PATH . DIRECTORY_SEPARATOR . ( $post_type ? 'theplus-' . $filename . $post_type : 'theplus' ) . ( isset( $post_id ) ? '-' . $post_id : '' ) . '.min.js';

		if ( 'css' === $extension && is_readable( $this->secure_path_url( $css_url ) ) ) {
			return true;
		} elseif ( 'js' === $extension && is_readable( $this->secure_path_url( $js_url ) ) ) {
			return true;
		} elseif ( 'both' === $extension && is_readable( $this->secure_path_url( $css_url ) ) && is_readable( $this->secure_path_url( $js_url ) ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Check if cache files exists
	 *
	 * @since 1.3.1
	 * @param mixed $post_type The post type.
	 * @param mixed $post_id The post id.
	 * @param mixed $type The type.
	 * @param bool  $preload The preload.
	 */
	public function check_css_js_cache_files( $post_type = null, $post_id = null, $type = 'css', $preload = false ) {
		if ( empty( $type ) ) {
			return false;
		}
		$filename = '';
		if ( ! empty( $preload ) ) {
			$filename = 'preload-';
		}
		if ( 'css' === $type ) {
			$css_url = TPGB_ASSET_PATH . DIRECTORY_SEPARATOR . ( $post_type ? 'theplus-' . $filename . $post_type : 'theplus' ) . ( isset( $post_id ) ? '-' . $post_id : '' ) . '.min.css';
			if ( is_readable( $this->secure_path_url( $css_url ) ) ) {
				return true;
			}
		} elseif ( 'js' === $type ) {
			$js_url = TPGB_ASSET_PATH . DIRECTORY_SEPARATOR . ( $post_type ? 'theplus-' . $filename . $post_type : 'theplus' ) . ( isset( $post_id ) ? '-' . $post_id : '' ) . '.min.js';
			if ( is_readable( $this->secure_path_url( $js_url ) ) ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Generate Separate style and scripts.
	 *
	 * @since 1.4.0
	 * @param mixed $elements The elements.
	 * @param mixed $file_name The file name.
	 */
	public function load_separate_file( $elements, $file_name = null ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter

		if ( empty( $elements ) ) {
			return;
		}

		// default load js and css.
		$js_url  = array();
		$css_url = array(
			TPGB_PATH . DIRECTORY_SEPARATOR . 'assets/css/main/general/tpgb-common.css',
		);

		// Ajax Base Loder Css.
		$tpgb_ajax = Tp_Blocks_Helper::get_extra_option( 'tpgb_template_load' );
		if ( ( isset( $tpgb_ajax ) && ! empty( $tpgb_ajax ) && 'enable' === $tpgb_ajax ) || empty( $tpgb_ajax ) ) {
				$css_url = array_merge( $css_url, array( TPGB_PATH . DIRECTORY_SEPARATOR . 'assets/css/main/general/tpgb-ajax-load.css' ) );
		}

		// collect library scripts & styles.
		$js_url  = array_merge( $js_url, $this->plus_dependency_widgets( $elements, 'js' ) );
		$css_url = array_merge( $css_url, $this->plus_dependency_widgets( $elements, 'css' ) );

		return array(
			'css' => $css_url,
			'js'  => $js_url,
		);
	}

	/** // phpcs:ignore Squiz.Commenting.FunctionComment, Generic.Commenting.DocComment.ShortNotCapital,Generic.Commenting.DocComment.LongNotCapital,Generic.Commenting.DocComment.MissingShort
	 * Generate scripts and combine minify.
	 *
	 * @since 1.0.0
	 * @param mixed $elements The elements.
	 * @param mixed $file_name The file name.
	 * @param array $extension The extension.
	 */
	public function plus_generate_scripts( $elements, $file_name = null, $extension = array( 'css', 'js' ), $common = true ) {

		if ( empty( $elements ) ) {
			return;
		}

		if ( ! file_exists( TPGB_ASSET_PATH ) ) {
			wp_mkdir_p( TPGB_ASSET_PATH );
		}

		// default load js and css.
		$js_url = array();
		if ( false === $common ) {
			$css_url = array();
		} else {
			$css_url = array(
				TPGB_PATH . DIRECTORY_SEPARATOR . 'assets/css/main/general/tpgb-common.css',
			);

			// Ajax Loader Css Enqueue.
			$tpgb_ajax = Tp_Blocks_Helper::get_extra_option( 'tpgb_template_load' );
			if ( ( isset( $tpgb_ajax ) && ! empty( $tpgb_ajax ) && 'enable' === $tpgb_ajax ) || empty( $tpgb_ajax ) ) {
					$css_url = array_merge( $css_url, array( TPGB_PATH . DIRECTORY_SEPARATOR . 'assets/css/main/general/tpgb-ajax-load.css' ) );
			}
		}

		// collect library scripts & styles And Merge.
		if ( in_array( 'js', $extension, true ) ) {
			$js_url = array_merge( $js_url, $this->plus_dependency_widgets( $elements, 'js' ) );
			if ( ! empty( $js_url ) ) {
				$this->plus_merge_files( $js_url, ( $file_name ? $file_name : 'theplus' ) . '.min.js', 'js' );
			}
		}

		if ( in_array( 'css', $extension, true ) ) {
			$css_url = array_merge( $css_url, $this->plus_dependency_widgets( $elements, 'css' ) );
			if ( ! empty( $css_url ) ) {
				$this->plus_merge_files( $css_url, ( $file_name ? $file_name : 'theplus' ) . '.min.css', 'css' );
			}
		}
	}

	/**
	 * Widgets dependency for modules
	 *
	 * @since 1.0.0
	 * @param array $elements The elements.
	 * @param mixed $type The type.
	 */
	public function plus_dependency_widgets( array $elements, $type ) {
		$paths = array();

		foreach ( $elements as $element ) {
			if ( isset( $this->tpgb_registered_blocks[ $element ] ) ) {
				if ( ! empty( $this->tpgb_registered_blocks[ $element ][ $type ] ) ) {
					foreach ( $this->tpgb_registered_blocks[ $element ][ $type ] as $path ) {
						$paths[] = $path;
					}
				}
			}
		}
		return array_unique( $paths );
	}

	/** // phpcs:ignore Squiz.Commenting.FunctionComment, Generic.Commenting.DocComment.ShortNotCapital,Generic.Commenting.DocComment.LongNotCapital,Generic.Commenting.DocComment.MissingShort
	 * Merge all Files Load
	 *
	 * @since 1.0.0
	 * @param array $paths The paths.
	 */
	public function plus_merge_files( $paths = array(), $file = 'theplus-style.min.css', $type = '' ) {
		$output = '';

		if ( ! empty( $paths ) ) {
			foreach ( $paths as $path ) {
				$output .= file_get_contents( $this->secure_path_url( $path ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
			}
		}
		if ( ! empty( $type ) && 'css' === $type ) {
			$output = preg_replace( '/\s+/', ' ', $output );
			$output = preg_replace( '/\/\*[^\!](.*?)\*\//', '', $output );
			$output = preg_replace( '/(,|:|;|\{|}) /', '$1', $output );
			$output = preg_replace( '/ (,|;|\{|})/', '$1', $output );
			$output = preg_replace( '/(:| )0\.([0-9]+)(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}.${2}${3}', $output );
		}
		if ( ! empty( $output ) ) {
			return file_put_contents( $this->secure_path_url( TPGB_ASSET_PATH . DIRECTORY_SEPARATOR . $file ), $output ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents
		}
		return false;
	}

	/**
	 * Plus template load parse blocks.
	 *
	 * @param mixed $blocks The blocks.
	 * @return mixed The result.
	 */
	public function plus_template_load_parse_blocks( $blocks ) {

		$parse_content_template = array();
		if ( ! $this->is_preview_mode() && ! empty( $blocks ) && isset( $blocks ) && ! empty( $this->plus_template_blocks ) ) {
			if ( $this->plus_template_blocks ) {
				$template_post_id = array_unique( $this->plus_template_blocks );

				foreach ( $template_post_id as $id ) {
					$block_post               = get_post( $id );
					$parse_content_template[] = parse_blocks( $block_post->post_content );
				}
			}
			$blocks = array_merge( $parse_content_template, $blocks );

			return $blocks;
		} else {
			return $blocks;
		}
	}

	/**
	 * Tpgb get load template id.
	 *
	 * @return mixed The result.
	 */
	public function tpgb_get_load_template_id() {
		return $this->plus_template_blocks;
	}

	/**
	 * Template Post Do block
	 *
	 * @since 1.1.2
	 * @param string $post_id The post id.
	 */
	public function plus_do_block( $post_id = '' ) {

		if ( ! $this->is_preview_mode() && ! empty( $post_id ) && isset( $post_id ) ) {
			$new_post_id = apply_filters( 'wpml_object_id', $post_id, get_post_type( $post_id ), true );
			$new_post_id = ( $new_post_id ) ? $new_post_id : $post_id;
			$block_post  = get_post( $new_post_id );
			if ( ! is_wp_error( $block_post ) ) {
				$this->plus_template_blocks[] = $new_post_id;
				$content                      = ( isset( $block_post->post_content ) ) ? $block_post->post_content : '';
				if ( isset( $content ) ) {
					return do_blocks( $content );
				} else {
					return;
				}
			}
		} else {
			return;
		}
	}

	/** // phpcs:ignore Squiz.Commenting.FunctionComment, Generic.Commenting.DocComment.ShortNotCapital,Generic.Commenting.DocComment.LongNotCapital,Generic.Commenting.DocComment.MissingShort
	 * Save Load Template Post Id And Dynamic Enqueue Css
	 *
	 * @since 2.0.0
	 * @param array $template_post_ids The template post ids.
	 */
	public function save_load_templates( $template_post_ids = array(), $in_footer = false ) {
		$option_name = 'tpgb-load-templates-list';
		$get_opt     = get_option( $option_name );

		if ( empty( self::$tpgb_post_type ) ) {
			$this->get_post_type_post_id();
		}

		if ( ! empty( $in_footer ) ) {
			if ( false === $get_opt ) {
				add_option( $option_name, array( self::$tpgb_post_id => $template_post_ids ) );
			} elseif ( false !== $get_opt && ! empty( $get_opt ) ) {
				if ( ! isset( $get_opt[ self::$tpgb_post_id ] ) ) {
					$get_opt[ self::$tpgb_post_id ] = $template_post_ids;
					update_option( $option_name, $get_opt, false );
				} elseif ( $get_opt[ self::$tpgb_post_id ] !== $template_post_ids ) {
						$get_opt[ self::$tpgb_post_id ] = $template_post_ids;
						update_option( $option_name, $get_opt, false );
				}
			}
		} elseif ( ! empty( $get_opt ) && isset( $get_opt[ self::$tpgb_post_id ] ) && ! empty( $get_opt[ self::$tpgb_post_id ] ) ) {
			$template_post_ids = $get_opt[ self::$tpgb_post_id ];
		}

		if ( ! empty( $template_post_ids ) ) {
			$post_type = ( is_singular() ? 'post' : 'term' );
			foreach ( $template_post_ids as $post_id ) {
				$this->header_init_css_js( $post_type, $post_id );
				$this->enqueue_dynamic_css_post_id( $post_id, $in_footer );
			}
		}
	}

	/**
	 * Dynamic Enqueue Css by Post Id
	 *
	 * @since 2.0.0
	 * @param string $post_id The post id.
	 * @param bool   $in_footer The in footer.
	 */
	public function enqueue_dynamic_css_post_id( $post_id = '', $in_footer = false ) {
		if ( ! empty( $post_id ) ) {
			$upload_dir       = wp_get_upload_dir();
			$upload_base_dir  = trailingslashit( $upload_dir['basedir'] );
			$css_path         = $upload_base_dir . "theplus_gutenberg/plus-css-{$post_id}.css";
			$preview_css_path = $upload_base_dir . "theplus_gutenberg/plus-preview-{$post_id}.css";

			$plus_version = $this->get_enqueue_version( $post_id );

			$plus_css = get_post_meta( $post_id, '_tpgb_css', true );
			if ( ! empty( $plus_css ) && isset( $plus_css['font_link'] ) && ! empty( $plus_css['font_link'] ) && class_exists( 'Tpgb_Core_Init_Blocks' ) ) {
				$tp_core = Tpgb_Core_Init_Blocks::get_instance();
				$tp_core->tpgb_load_google_fonts( $post_id, $plus_css['font_link'] );
			}
			if ( isset( $_GET['preview'] ) && true === $_GET['preview'] && file_exists( $preview_css_path ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$css_file_url = Tp_Blocks_Helper::tpgb_get_upload_url();
				$css_url      = $css_file_url . "theplus_gutenberg/plus-preview-{$post_id}.css";
				if ( ! $this->is_editor_screen() ) {
					wp_enqueue_style( "plus-preview-{$post_id}", esc_url( $css_url ), false, $plus_version . time() );
				}
			} elseif ( file_exists( $css_path ) ) {
				$deps = array( 'tpgb-plus-block-front-css' );
				if ( true === $in_footer && 'separate' === $this->get_caching_option() ) {
					$deps = array();
				}
				$css_file_url = Tp_Blocks_Helper::tpgb_get_upload_url();
				$css_url      = $css_file_url . "theplus_gutenberg/plus-css-{$post_id}.css";
				wp_enqueue_style( "plus-post-{$post_id}", esc_url( $css_url ), $deps, $plus_version );
			} elseif ( ! file_exists( $css_path ) && class_exists( 'Tpgb_Core_Init_Blocks' ) ) {
				$tp_core = Tpgb_Core_Init_Blocks::get_instance();
				$tp_core->make_block_css_by_post_id( $post_id );
			}
		}
	}

	/**
	 * Check generate Style and Scripts
	 *
	 * @since 2.0.0
	 */
	public function check_generate_script() {
		if ( $this->is_background_running() ) {
			return false;
		}

		if ( null === $this->plus_uid ) {
			return false;
		}

		if ( $this->is_preview_mode() ) {
			return false;
		}

		if ( ! $this->requires_update ) {
			return false;
		}

		if ( $this->get_save_updated_at() === false ) {
			$this->update_save_updated_at();
		}

		$updated_at = $this->get_posts_metadata( self::$tpgb_post_id, '_block_css', 'updated_at', $this->plus_uid . '_updated_at' );
		if ( $this->get_save_updated_at() === $updated_at ) {
			return false;
		}

		return true;
	}

	/**
	 * Tpgb Save Updated At
	 *
	 * @since 3.2.9
	 */
	public function get_save_updated_at() {
		if ( false !== $this->save_cache && ! empty( $this->save_cache ) ) {
			return $this->save_cache;
		}

		$this->save_cache = get_option( 'tpgb_save_updated_at' );

		return $this->save_cache;
	}

	/**
	 * Tpgb Save Updated At
	 *
	 * @since 3.2.9
	 */
	public function update_save_updated_at() {
		$this->save_cache = strtotime( 'now' );
		update_option( 'tpgb_save_updated_at', $this->save_cache, false );
	}

	/**
	 * Get Post Type and Post ID
	 *
	 * @since 2.0.0
	 */
	public function get_post_type_post_id() {

		if ( ! empty( self::$tpgb_post_type ) && '' !== self::$tpgb_post_id ) {
			return true;
		}

		global $wp_query;
		if ( is_home() || is_singular() || is_archive() || is_search() || ( isset( $wp_query ) && (bool) $wp_query->is_posts_page ) || is_404() ) {
			$queried_obj = get_queried_object_id();
			if ( isset( $wp_query ) && isset( $wp_query->is_post_type_archive ) && ! empty( $wp_query->is_post_type_archive ) ) {
				if ( isset( $wp_query->query['post_type'] ) && ! empty( $wp_query->query['post_type'] ) ) {
					$queried_obj = $wp_query->query['post_type'];
				}
			}
			if ( is_search() ) {
				$queried_obj = 'search';
			}
			if ( is_404() ) {
				$queried_obj = '404';
			}
			$post_type = ( is_singular() ? 'post' : 'term' );

			self::$tpgb_post_type = $post_type;
			self::$tpgb_post_id   = $queried_obj;

			return true;
		}

		return false;
	}

	/**
	 * Update PostMeta / TermMeta Value
	 *
	 * @since 3.2.1
	 ***/
	/**
	 * Update posts metadata.
	 *
	 * @param string $post_id The post id.
	 * @param string $meta_key The meta key.
	 * @param string $update_key The update key.
	 * @param string $val The val.
	 * @param string $save_update The save update.
	 */
	public function update_posts_metadata( $post_id = '', $meta_key = '', $update_key = '', $val = '', $save_update = '' ) {
		if ( '' !== $post_id ) {
			$old_value = array();
			if ( is_404() || is_search() || 0 === $post_id || ! is_numeric( $post_id ) ) {
				$old_value = get_option( 'theplus-term-' . $post_id );
			} elseif ( 'term' === self::$tpgb_post_type && is_numeric( $post_id ) ) {
				$old_value = get_term_meta( $post_id, $meta_key, true );
			} elseif ( is_numeric( $post_id ) ) {
				$old_value = get_post_meta( $post_id, $meta_key, true );
			}

			if ( ! empty( $old_value ) && is_array( $old_value ) ) {
				$old_value[ $update_key ] = $val;
			} elseif ( ! empty( $old_value ) && ! is_array( $old_value ) ) {
				$old_value                = array();
				$old_value[ $update_key ] = $val;
			} elseif ( empty( $old_value ) ) {
				$old_value                = array();
				$old_value[ $update_key ] = $val;
			}

			if ( ! empty( $save_update ) ) {
				$old_value['updated_at'] = $this->get_save_updated_at();
			}

			$cache_key = $post_id;
			if ( is_404() || is_search() || 0 === $post_id || ! is_numeric( $post_id ) ) {
				update_option( 'theplus-term-' . $post_id, $old_value );
				$cache_key = 'term-' . $post_id;
			} elseif ( 'term' === self::$tpgb_post_type && is_numeric( $post_id ) ) {
				update_term_meta( $post_id, $meta_key, $old_value );
				$cache_key = 'term-' . $post_id;
			} elseif ( is_numeric( $post_id ) ) {
				update_post_meta( $post_id, $meta_key, $old_value );
			}

			if ( ! empty( $this->data_cache ) && isset( $this->data_cache[ $cache_key ] ) ) {
				$this->data_cache[ $cache_key ] = $old_value;
			}
		}
	}

	/**
	 * Get PostMeta / TermMeta Value
	 *
	 * @since 3.2.1
	 ***/
	/**
	 * Get posts metadata.
	 *
	 * @param string $post_id The post id.
	 * @param string $meta_key The meta key.
	 * @param string $get_key_val The get key val.
	 * @param string $old_key The old key.
	 * @return mixed The result.
	 */
	public function get_posts_metadata( $post_id = '', $meta_key = '', $get_key_val = '', $old_key = '' ) {

		$cache_key = $post_id;

		if ( '' !== $post_id ) {
			if ( is_404() || is_search() || 0 === $post_id || ! is_numeric( $post_id ) ) {
				$cache_key = 'term-' . $post_id;
			} elseif ( 'term' === self::$tpgb_post_type && is_numeric( $post_id ) ) {
				$cache_key = 'term-' . $post_id;
			}

			if ( ! empty( $this->data_cache ) && isset( $this->data_cache[ $cache_key ] ) ) {
				$value = '';

				$cache_value = $this->data_cache[ $cache_key ];
				if ( ! empty( $cache_value ) && is_array( $cache_value ) && isset( $cache_value[ $get_key_val ] ) ) {
					$value = $cache_value[ $get_key_val ];
				} elseif ( ! empty( $cache_value ) && ! is_array( $cache_value ) ) {
					$value = $cache_value;
				}

				return $value;
			}
		}

		$value = '';
		if ( '' !== $post_id ) {
			$old_value = '';
			if ( is_404() || is_search() || 0 === $post_id || ! is_numeric( $post_id ) || ( function_exists( 'is_shop' ) && is_shop() ) ) {
				$old_value = get_option( 'theplus-term-' . $post_id );
			} elseif ( 'term' === self::$tpgb_post_type && is_numeric( $post_id ) ) {
				$old_value = get_term_meta( $post_id, $meta_key, true );
			} elseif ( is_numeric( $post_id ) ) {
				$old_value = get_post_meta( $post_id, $meta_key, true );
			}
			$this->data_cache[ $cache_key ] = $old_value;

			if ( ! empty( $old_value ) && is_array( $old_value ) && isset( $old_value[ $get_key_val ] ) ) {
				$value = $old_value[ $get_key_val ];
			} elseif ( ! empty( $old_value ) && ! is_array( $old_value ) ) {
				$value = $old_value;
			}
		}

		// old options key remove.
		if ( empty( $value ) && ! empty( $old_key ) ) {
			$old_key_value = get_option( $old_key );
			if ( ! empty( $old_key_value ) && ! is_404() && ! is_search() ) {
				$this->update_posts_metadata( $post_id, $meta_key, $get_key_val, $old_key_value );
				delete_option( $old_key );
			}
		}

		return $value;
	}

	/**
	 * Remove PostMeta / TermMeta Value
	 *
	 * @since 3.2.1
	 ***/
	/**
	 * Remove posts metadata.
	 *
	 * @param string $post_id The post id.
	 * @param string $meta_key The meta key.
	 * @param string $get_key_val The get key val.
	 * @param string $old_key The old key.
	 */
	public function remove_posts_metadata( $post_id = '', $meta_key = '', $get_key_val = '', $old_key = '' ) {
		if ( ! empty( $old_key ) ) {
			$value = get_option( $old_key );
			if ( ! empty( $value ) ) {
				delete_option( $old_key );
			}
		}
		if ( ! empty( $post_id ) && is_numeric( $post_id ) ) {
			$value = get_post_meta( $post_id, $meta_key, true );
			if ( ! empty( $value ) && isset( $value[ $get_key_val ] ) ) {
				unset( $value[ $get_key_val ] );
				update_post_meta( $post_id, $meta_key, $value );
			}
		} elseif ( 0 === $post_id || ! is_numeric( $post_id ) ) {
			$value = get_option( 'theplus-term-' . $post_id );
			if ( ! empty( $value ) && isset( $value[ $get_key_val ] ) ) {
				unset( $value[ $get_key_val ] );
				update_option( 'theplus-term-' . $post_id, $value );
			}
		}
	}

	/** // phpcs:ignore Squiz.Commenting.FunctionComment, Generic.Commenting.DocComment.ShortNotCapital,Generic.Commenting.DocComment.LongNotCapital,Generic.Commenting.DocComment.MissingShort
	 * Enqueue Load Css/JS version
	 *
	 * @since 3.2.1
	 * */
	public function get_enqueue_version( $post_id = '' ) {

		$version = '';
		if ( 0 === $post_id || ! is_numeric( $post_id ) ) {
			$post_version = get_option( 'theplus-term-' . $post_id );
			if ( ! empty( $post_version ) && is_array( $post_version ) ) {
				$version = isset( $post_version['version'] ) ? $post_version['version'] : '';
			} elseif ( ! empty( $post_version ) && ! is_array( $post_version ) ) {
				$version = $post_version;
			}
			if ( empty( $version ) && is_array( $post_version ) ) {
				$post_version['version'] = time();
				update_option( 'theplus-term-' . $post_id, $post_version );
			}
		} elseif ( ! empty( $post_id ) ) {
			$post_version = get_post_meta( $post_id, '_block_css', true );
			if ( ! empty( $post_version ) && is_array( $post_version ) ) {
				$version = isset( $post_version['version'] ) ? $post_version['version'] : '';
			} elseif ( ! empty( $post_version ) && ! is_array( $post_version ) ) {
				$version = $post_version;
			}
			if ( empty( $version ) && is_array( $post_version ) ) {
				$post_version['version'] = time();
				update_post_meta( $post_id, '_block_css', $post_version );
			}
		} else {
			$version = time();
		}
		if ( empty( $version ) ) {
			$version = get_option( 'tpgb_backend_cache_at' );
		}
		return $version;
	}

	/** // phpcs:ignore Squiz.Commenting.FunctionComment, Generic.Commenting.DocComment.ShortNotCapital,Generic.Commenting.DocComment.LongNotCapital,Generic.Commenting.DocComment.MissingShort
	 * Frontend load css js by Cache Manager
	 *
	 * @since 2.0.0
	 * @param array $elements The elements.
	 */
	public function enqueue_css_js( $elements = array(), $in_footer = false, $load_depend = array() ) {

		$tpgb_path    = TPGB_PATH . DIRECTORY_SEPARATOR;
		$tpgb_url     = TPGB_URL;
		$plus_version = $this->get_enqueue_version( self::$tpgb_post_id );

		$scripts_loader = array();

		// DashIcon CSS Enqueue.
		$nxtdepend = ( apply_filters( 'tpgb_dashicons_icon_disable', true ) ) ? true : false;
		if ( ! empty( $nxtdepend ) && true === $nxtdepend ) {
			wp_enqueue_style( 'dashicons' );
		}

		// Google Map.
		$google_map_enable = Tp_Blocks_Helper::get_extra_option( 'gmap_api_switch' );
		if ( ! empty( $google_map_enable ) && ( ( 'enable' === $google_map_enable && has_block( 'tpgb/tp-google-map' ) ) || ( 'enable' === $google_map_enable && class_exists( 'GeneratePress_Elements_Helper' ) ) || ( ! empty( $elements ) && in_array( 'tpgb/tp-google-map', $elements, true ) ) ) ) {
			$google_map_api = Tp_Blocks_Helper::get_extra_option( 'googlemap_api' );

			if ( empty( trim( $google_map_api ) ) ) {
				$google_map_api = 'AIzaSyA_ez85P6duaw7IrvfeK8LmRxLZPdLG7gs';
			}

			if ( ! empty( $google_map_api ) && ! $this->get_delay_css_js() ) {
				$depend = array();
				if ( $this->get_caching_option() === 'separate' ) {
					array_push( $depend, 'theplus-tpgb-google-map' );
				} else {
					array_push( $depend, 'tpgb-plus-block-front-js' );
				}
				wp_enqueue_script( 'gmaps-js', '//maps.googleapis.com/maps/api/js?key=' . esc_attr( $google_map_api ) . '&sensor=false&callback=tpgbInitMap', $depend, null, false, true );  // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
			} elseif ( ! empty( $google_map_api ) && $this->get_delay_css_js() ) {
				$scripts_loader['google_api'] = $google_map_api;
			}
		}

		$load_localize = '';

		// caching type load.
		if ( 'separate' === $this->get_caching_option() && ! empty( $elements ) && false === $in_footer ) {
			$separate_path = $this->load_separate_file( $elements );

			if ( isset( $separate_path['css'] ) && ! empty( $separate_path['css'] ) ) {
				$iji       = 1;
				$total_eng = count( $separate_path['css'] );
				foreach ( $separate_path['css'] as $key => $path ) {
					if ( is_readable( $this->secure_path_url( $path ) ) ) {
						$css_sep_url = str_replace( $tpgb_path, $tpgb_url, $path );
						if ( defined( 'TPGBP_VERSION' ) && defined( 'TPGBP_URL' ) ) {
							$css_sep_url = str_replace( TPGBP_PATH . DIRECTORY_SEPARATOR, TPGBP_URL, $css_sep_url );
						}
						$css_file_key = basename( $css_sep_url, '.css' );
						$css_file_key = basename( $css_file_key, '.min' );
						$last_folder  = basename( dirname( $css_sep_url ) );
						$enq_name     = 'theplus-' . $css_file_key . '-' . $last_folder;
						if ( $iji === $total_eng ) {
							$enq_name = 'tpgb-plus-block-front-css';
						}
						wp_enqueue_style( $enq_name, esc_url( $css_sep_url ), false, $plus_version );
						++$iji;
					}
				}
			}

			if ( isset( $separate_path['js'] ) && ! empty( $separate_path['js'] ) ) {
				$iji = 0;
				foreach ( $separate_path['js'] as $key => $path ) {
					if ( is_readable( $this->secure_path_url( $path ) ) ) {
						$js_sep_url = str_replace( $tpgb_path, $tpgb_url, $path );
						if ( defined( 'TPGBP_VERSION' ) && defined( 'TPGBP_URL' ) ) {
							$js_sep_url = str_replace( TPGBP_PATH . DIRECTORY_SEPARATOR, TPGBP_URL, $js_sep_url );
						}
						$js_file_key = basename( $js_sep_url, '.js' );
						$js_file_key = basename( $js_file_key, '.min' );
						if ( 0 === $iji ) {
							$load_localize = 'theplus-' . $js_file_key;
						}
						wp_enqueue_script( 'theplus-' . $js_file_key, esc_url( $js_sep_url ), $load_depend, $plus_version, true );
						++$iji;
					}
				}
			}
		} elseif ( $this->get_caching_option() === false ) {
			if ( $this->check_css_js_cache_files( self::$tpgb_post_type, self::$tpgb_post_id, 'css' ) && ( false === $this->get_caching_option() && false === $in_footer ) ) {
				$enq = 'tpgb-plus-block-front-css';
				// LearnPress.
				if ( true === $this->learn_view_item && true === $this->dependency ) {
					$enq = 'tpgb-plus-' . self::$tpgb_post_type;
				}
				$css_file = TPGB_ASSET_URL . '/theplus-' . self::$tpgb_post_type . '-' . self::$tpgb_post_id . '.min.css';
				wp_enqueue_style( $enq, esc_url( $css_file ), false, $plus_version );
			}

			$load_localize = 'tpgb-purge-js';
			if ( $this->check_css_js_cache_files( self::$tpgb_post_type, self::$tpgb_post_id, 'js' ) ) {
				$js_file = TPGB_ASSET_URL . '/theplus-' . self::$tpgb_post_type . '-' . self::$tpgb_post_id . '.min.js';

				wp_enqueue_script( 'tpgb-plus-block-front-js', esc_url( $js_file ), array(), $plus_version, true );
				$load_localize = 'tpgb-plus-block-front-js';
			}
		}

		$tpgb_ajax = Tp_Blocks_Helper::get_extra_option( 'tpgb_template_load' );
		$tplzy     = Tp_Blocks_Helper::get_extra_option( 'tpgb_lazy_render' );
		if ( ( isset( $tpgb_ajax ) && ! empty( $tpgb_ajax ) && 'enable' === $tpgb_ajax ) || ( isset( $tplzy ) && ! empty( $tplzy ) && 'enable' === $tplzy ) ) {
			$ajaxdepe = array();
			if ( ! empty( $load_localize ) ) {
				$ajaxdepe = array( $load_localize );
			}

			wp_enqueue_script( 'tpgb-ajax-load-template', TPGB_URL . 'assets/js/main/general/tpgb-ajax-load.min.js', $ajaxdepe, TPGB_VERSION, true );
			$load_localize = 'tpgb-ajax-load-template';
		}

		// First time load in footer.
		if ( $this->get_caching_option() === 'separate' && ! empty( $in_footer ) && class_exists( 'Tpgb_Core_Init_Blocks' ) ) {
			$load_enqueue = Tpgb_Core_Init_Blocks::get_instance();

			if ( ! empty( $load_enqueue ) && is_callable( array( $load_enqueue, 'tpgb_compatibility_plugins' ) ) ) {
				$load_enqueue->tpgb_compatibility_plugins();
			}
		}

		// delay js like fontawesome,google map, lottiefiles.
		if ( has_filter( 'tpgb_onloader_scripts' ) ) {
			$scripts_loader = apply_filters( 'tpgb_onloader_scripts', $scripts_loader, $elements, $plus_version );
		}

		if ( $this->get_delay_css_js() && ! empty( $scripts_loader ) ) {
			wp_enqueue_script( 'tpgb-delay', TPGB_URL . 'assets/js/main/general/tpgb-loader.min.js', array(), TPGB_VERSION, true );
			wp_localize_script(
				'tpgb-delay',
				'tpgbLoadScripts',
				$scripts_loader
			);
		}

		// Load Templates Dynamic css.
		$template_post_ids = array();
		if ( ! empty( $in_footer ) && ! empty( $this->plus_template_blocks ) ) {
			$template_post_ids = array_unique( $this->plus_template_blocks );
		}
		$this->save_load_templates( $template_post_ids, $in_footer );

		if ( ( 'separate' === $this->get_caching_option() && false === $in_footer ) || false === $this->get_caching_option() ) {
			$this->load_localize_data( $load_localize );
		}
	}

	/**
	 * Load localize data.
	 *
	 * @param string $load_localize The load localize.
	 */
	public function load_localize_data( $load_localize = '' ) {
		wp_localize_script(
			$load_localize,
			'tpgb_config',
			array(
				'ajax_url'   => esc_url( admin_url( 'admin-ajax.php' ) ),
				'tpgb_nonce' => wp_create_nonce( 'tpgb-addons' ),
			)
		);
	}

	/**
	 * Check templates ids.
	 *
	 * @param string $post_id The post id.
	 */
	public function check_templates_ids( $post_id = '' ) {
		if ( ! empty( $post_id ) ) {
			$option_name = 'tpgb-load-templates-list';
			$get_opt     = get_option( $option_name );
			if ( ! is_array( $get_opt ) ) {
				$get_opt = array();
			}
			if ( empty( self::$tpgb_post_type ) ) {
				$this->get_post_type_post_id();
			}
			if ( ! empty( $get_opt ) && isset( $get_opt[ self::$tpgb_post_id ] ) && $post_id !== self::$tpgb_post_id ) {
				if ( ! in_array( $post_id, $get_opt[ self::$tpgb_post_id ], true ) ) {
					$get_opt[ self::$tpgb_post_id ] = array_merge( $get_opt[ self::$tpgb_post_id ], array( $post_id ) );
					$this->requires_update          = $this->requires_update();
					update_option( $option_name, $get_opt, false );
					$this->update_save_updated_at();
				}
			} elseif ( ( empty( $get_opt ) || ! isset( $get_opt[ self::$tpgb_post_id ] ) ) && $post_id !== self::$tpgb_post_id ) {
				$get_opt[ self::$tpgb_post_id ] = array( $post_id );
				$this->requires_update          = $this->requires_update();
				update_option( $option_name, $get_opt, false );
				$this->update_save_updated_at();
			}
		}
	}

	/**
	 * Header init css js.
	 *
	 * @param mixed $post_type The post type.
	 * @param mixed $post_id The post id.
	 * @return mixed The result.
	 */
	public function header_init_css_js( $post_type = null, $post_id = null ) {

		if ( ! empty( $post_id ) && ! in_array( $post_id, $this->plus_template_blocks, true ) ) {
			$this->check_templates_ids( $post_id );
			$this->plus_template_blocks[] = $post_id;
			if ( isset( self::$tpgb_post_id ) && ! empty( $this->post_assets_object[ self::$tpgb_post_id ] ) ) {
				$this->dependency = true;
			}
		}

		if ( $this->check_generate_script() === false ) {
			return;
		}

		if ( ! $this->get_post_type_post_id() ) {
			return;
		}
		$first_time = false;
		if ( is_object( $post_type ) && empty( $post_id ) ) {

			if ( ! self::$tpgb_post_id ) {
				return;
			}
			$first_time = true;
			$post_type  = self::$tpgb_post_type;
			$post_id    = self::$tpgb_post_id;
		}

		if ( ! isset( $this->post_assets_object[ $post_id ] ) && class_exists( 'Tpgb_Get_Blocks' ) ) {

			$load_enqueue = tpgb_get_post_assets( $post_type, $post_id );
			if ( ! empty( $load_enqueue ) ) {
				if ( $this->get_caching_option() === 'separate' && ! empty( $first_time ) ) {
					$this->enqueue_global_css();
				}
				if ( isset( $load_enqueue->transient_blocks ) && ! empty( $load_enqueue->transient_blocks ) ) {
					if ( has_filter( 'tpgb_load_blocks_css_js' ) ) {
						$load_extra = apply_filters( 'tpgb_load_blocks_css_js', array(), $load_enqueue->transient_blocks );
					}
					$load_localize    = $load_enqueue->enqueue_scripts( $this->dependency );
					$this->dependency = true;
					if ( $this->get_caching_option() === 'separate' ) {
						if ( ! empty( $load_localize ) && empty( $this->localize ) ) {
							$this->localize = $load_localize;
							$this->load_localize_data( $load_localize );
						}
						if ( class_exists( 'Tpgb_Core_Init_Blocks' ) ) {
							$load_init = Tpgb_Core_Init_Blocks::get_instance();
							if ( ! empty( $load_init ) && is_callable( array( $load_init, 'enqueue_post_css' ) ) ) {
								$load_init->enqueue_post_css( $post_id, array() );
							}
						}
					}
				}
				// template load css.
				if ( isset( $load_enqueue->templates_ids ) && ! empty( $load_enqueue->templates_ids ) && is_array( $load_enqueue->templates_ids ) ) {
					$res_id    = array_unique( $load_enqueue->templates_ids );
					$post_type = ( is_singular() ? 'post' : 'term' );
					foreach ( $res_id as $value ) {
						$this->header_init_css_js( $post_type, $value );
						$this->plus_template_blocks[] = $value;
						$this->enqueue_dynamic_css_post_id( $value );
					}
				}
				$block_lists                          = array_unique( $load_enqueue->transient_blocks );
				$this->post_assets_object[ $post_id ] = $block_lists;
			}
		}
		return $this->post_assets_object[ $post_id ];
	}

	/**
	 * Get Blocks Elements
	 *
	 * @since 2.0.0
	 * @param array $block_lists The block lists.
	 */
	public function get_blocks_elements( $block_lists = array() ) {
		$elements = array();
		if ( ! empty( $block_lists ) ) {
			$replace  = array();
			$elements = array_map(
				function ( $val ) use ( $replace ) {
					return ( array_key_exists( $val, $replace ) ? $replace[ $val ] : $val );
				},
				$block_lists
			);
			$elements = array_intersect( array_keys( $this->tpgb_registered_blocks ), $elements );

			$elements = array_unique( $elements );
			sort( $elements );
		}
		return $elements;
	}

	/**
	 * Generate single post scripts
	 *
	 * @since 2.0.0
	 */
	public function generate_scripts_frontend() {

		if ( $this->check_generate_script() === false ) {
			return;
		}

		if ( has_filter( 'tpgb_extra_load_css_js' ) ) {
			$this->transient_blocks = apply_filters( 'tpgb_extra_load_css_js', $this->transient_blocks );
		}

		if ( ! empty( $this->transient_blocks ) ) {
			$this->transient_blocks = array_unique( $this->transient_blocks );
		}

		$elements = $this->get_blocks_elements( $this->transient_blocks );

		if ( $this->get_post_type_post_id() ) {

			// LearnPress.
			if ( class_exists( 'LP_Global' ) && false === $this->learn_view_item ) {
				$course_item = LP_Global::course_item();
				if ( ! empty( $course_item ) ) {
					$this->learn_view_item = true;
					self::$tpgb_post_id   .= '-' . $course_item->get_id();
					$this->plus_uid       .= '-' . $course_item->get_id();
				}
			}

			$this->remove_files_unlink( self::$tpgb_post_type, self::$tpgb_post_id );

			// if no cache files, generate new.
			if ( ! $this->check_cache_files( self::$tpgb_post_type, self::$tpgb_post_id ) && $this->get_caching_option() === false ) {
				$this->plus_generate_scripts( $elements, 'theplus-' . self::$tpgb_post_type . '-' . self::$tpgb_post_id );
			}

			// First time load footer css and js.
			$this->enqueue_css_js( $elements, true );

			$this->update_posts_metadata( self::$tpgb_post_id, '_block_css', 'blocks', $elements );
			$this->update_posts_metadata( self::$tpgb_post_id, '_block_css', 'version', time(), true );
		}
	}

	/**
	 * Enqueue FSE Theme
	 *
	 * @since 2.0.3
	 */
	public function assets_fse_theme() {
		if ( is_admin() ) {
			return;
		}

		// Check if current user can have edit access.
		/*
		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		} */

		global $_wp_current_template_content;
		if ( $_wp_current_template_content ) {

			$id                  = '';
			$get_block_templates = get_block_templates();
			$template_slug       = get_post_meta( get_the_ID(), '_wp_page_template', true );

			foreach ( $get_block_templates as $single ) {
				if ( $template_slug && isset( $single->slug ) && $single->slug === $template_slug ) {
					$id = $single->wp_id;
					break;
				}

				if ( isset( $single->content ) && $single->content === $_wp_current_template_content ) {
					$id = $single->wp_id;
					break;
				}
			}

			if ( ! empty( $id ) ) {
				$type = 'post';
				$this->header_init_css_js( $type, $id );
			}

			$dynamic_id = get_queried_object_id();
			$blocks     = parse_blocks( $_wp_current_template_content );
			if ( ! empty( $blocks ) && class_exists( 'Tpgb_Get_Blocks' ) ) {
				$type = is_singular() ? 'post' : 'term';

				$post_obj  = new Tpgb_Get_Blocks( $type, $dynamic_id );
				$templates = $post_obj->block_reference_id( $blocks );
				$post_obj->tpgb_post_block_css( $type, $dynamic_id );

				if ( isset( $post_obj->transient_blocks ) && ! empty( $post_obj->transient_blocks ) ) {
					if ( class_exists( 'Tpgb_Core_Init_Blocks' ) ) {
						$tp_core = Tpgb_Core_Init_Blocks::get_instance();
						$tp_core->make_block_css_by_post_id( $dynamic_id );
					}
					$post_obj->enqueue_scripts( $this->dependency );
					$this->dependency = true;
				}
				if ( ! empty( $templates ) ) {
					foreach ( $templates as $temp_id ) {
						if ( is_array( $temp_id ) ) {
							foreach ( $temp_id as $inner_temp ) {
								$this->header_init_css_js( $type, $inner_temp );
							}
						} else {
							$this->header_init_css_js( $type, $temp_id );
						}
					}
				}
			}
		}
	}

	/*
	 * Frontend Load Plus Styles and Scripts
	 * @since 2.0.0
	 */
	public function enqueue_frontend_load() { // phpcs:ignore Squiz.Commenting.FunctionComment

		if ( $this->get_post_type_post_id() ) {

			if ( ! $this->is_preview_mode() ) {

				if ( class_exists( 'LP_Global' ) && false === $this->learn_view_item ) {
					$course_item = LP_Global::course_item();
					if ( ! empty( $course_item ) ) {
						$this->learn_view_item = true;
						self::$tpgb_post_id   .= '-' . $course_item->get_id();
						$this->plus_uid       .= '-' . $course_item->get_id();
						if ( $this->requires_update ) {
							$this->requires_update = $this->requires_update();
						}
					}
				}

				$elements = array();
				if ( ! $this->requires_update ) {
					$elements = $this->get_posts_metadata( self::$tpgb_post_id, '_block_css', 'blocks', $this->plus_uid . '_blocks' );
					if ( $this->get_caching_option() === 'separate' ) { // separate file.
						$this->remove_files_unlink( self::$tpgb_post_type, self::$tpgb_post_id );
					} elseif ( $this->get_caching_option() === false ) { // combine.
						if ( ! $this->check_css_js_cache_files( self::$tpgb_post_type, self::$tpgb_post_id, 'css' ) && ! $this->check_css_js_cache_files( self::$tpgb_post_type, self::$tpgb_post_id, 'js' ) && ! empty( $elements ) ) {
							$this->update_posts_metadata( self::$tpgb_post_id, '_block_css', 'version', time() );
							$this->plus_generate_scripts( $elements, 'theplus-' . self::$tpgb_post_type . '-' . self::$tpgb_post_id );
						}
					}
					// if no widget in page, return.
					if ( empty( $elements ) ) {
						return;
					}
				} elseif ( function_exists( 'wp_is_block_theme' ) && wp_is_block_theme() ) {
					$this->assets_fse_theme();
				}

				if ( ( ! $this->requires_update && $this->get_caching_option() === 'separate' ) || $this->get_caching_option() === false ) {
					$this->enqueue_global_css();
				}

				if ( is_admin_bar_showing() && $this->get_caching_option() === false ) {
					wp_enqueue_script(
						'tpgb-purge-js',
						TPGB_URL . 'assets/js/main/general/tpgb-purge.js',
						array(),
						TPGB_VERSION,
						true
					);
				}

				if ( ! $this->requires_update && ! empty( $elements ) ) {
					// generate css & js load.
					$this->enqueue_css_js( $elements, false );
				}
			}
		}
	}

	/**
	 * Enqueue Global Css
	 *
	 * @since 2.0.0
	 */
	public function enqueue_global_css() {
		$plus_version = $this->get_enqueue_version( self::$tpgb_post_id );

		// fontawesome icon load frontend.
		$fontawesome_load   = Tp_Blocks_Helper::get_extra_option( 'fontawesome_load' );
		$fontawesome_pro    = Tp_Blocks_Helper::get_extra_option( 'fontawesome_pro_kit' );
		$global_block_style = Tp_Blocks_Helper::get_extra_opt_enabled();
		if ( ( empty( $fontawesome_load ) || 'enable' === $fontawesome_load || empty( $fontawesome_pro ) || ! defined( 'TPGBP_VERSION' ) ) && 'disable' !== $fontawesome_load ) {
			wp_enqueue_style( 'tpgb-fontawesome', TPGB_URL . 'assets/css/extra/fontawesome.min.css', array(), TPGB_VERSION );
		}

		$check_gfontandcss_load = get_option( 'tpgb_connection_data' );

		/* Disable Global CSS Here */
		if ( ! empty( $check_gfontandcss_load ) && isset( $check_gfontandcss_load['gbl_css'] ) && 'disable' === $check_gfontandcss_load['gbl_css'] && ! empty( $global_block_style ) && ! in_array( 'tp-global-block-style', $global_block_style, true ) ) {
			return;
		}

		$upload_dir      = wp_get_upload_dir();
		$upload_base_dir = trailingslashit( $upload_dir['basedir'] );
		$global_path     = $upload_base_dir . 'theplus_gutenberg/plus-global.css';
		$css_file_url    = Tp_Blocks_Helper::tpgb_get_upload_url();
		$global_css      = get_option( '_tpgb_global_css' );

		global $wp_filesystem;

		if ( ! $wp_filesystem ) {
			if ( ! function_exists( 'WP_Filesystem' ) ) {
				require_once wp_normalize_path( ABSPATH . '/wp-admin/includes/file.php' );
			}
		}

		WP_Filesystem( false, $upload_dir['basedir'], true );

		if ( ! empty( $wp_filesystem ) && ! $wp_filesystem->exists( $global_path ) ) {
			if ( ! empty( $global_css ) && isset( $global_css['css'] ) ) {
				$dir       = $upload_base_dir . 'theplus_gutenberg/';
				$file_name = 'plus-global.css';

				if ( ! $wp_filesystem->is_dir( $dir ) ) {
					$wp_filesystem->mkdir( $dir );
				}
				if ( ! empty( $check_gfontandcss_load ) && isset( $check_gfontandcss_load['gfont_load'] ) && 'disable' === $check_gfontandcss_load['gfont_load'] ) {
					if ( preg_match_all( '/@import[^;]+;/', $global_css['css'], $matches ) ) {
						if ( ! empty( $matches ) && isset( $matches[0] ) ) {
							$fonts = $matches[0];
							foreach ( $fonts as $key => $val ) {
								$global_css['css'] = str_replace( $val, '', $global_css['css'] );
							}
						}
					}
				}
				$wp_filesystem->put_contents( $dir . $file_name, $global_css['css'] );
			}
		}
		if ( ! empty( $global_css ) && isset( $global_css['font_link'] ) && ! empty( $global_css['font_link'] ) ) {
			if ( class_exists( 'Tpgb_Core_Init_Blocks' ) ) {
				$tp_core = Tpgb_Core_Init_Blocks::get_instance();
				$tp_core->tpgb_load_google_fonts( 'global', $global_css['font_link'] );
			}
		}
		if ( isset( $_GET['preview'] ) && true === $_GET['preview'] ) {  // phpcs:ignore WordPress.Security.NonceVerification.Recommended,WordPress.Security.NonceVerification.Missing
			$global_path = $upload_base_dir . 'theplus_gutenberg/plus-global-preview.css';
			if ( $wp_filesystem->exists( $global_path ) && ! $this->is_editor_screen() ) {
				$global_url = $css_file_url . 'theplus_gutenberg/plus-global-preview.css';
				wp_enqueue_style( 'plus-global', esc_url( $global_url ), false, $plus_version );
			} elseif ( $wp_filesystem->exists( $upload_base_dir . 'theplus_gutenberg/plus-global.css' ) && ! $this->is_editor_screen() ) {
				$global_url = $css_file_url . 'theplus_gutenberg/plus-global.css';
				wp_enqueue_style( 'plus-global', esc_url( $global_url ), false, $plus_version );
			}
		} elseif ( $wp_filesystem->exists( $global_path ) && ! $this->is_editor_screen() ) {
			$global_url = $css_file_url . 'theplus_gutenberg/plus-global.css';
			wp_enqueue_style( 'plus-global', esc_url( $global_url ), false, $plus_version );
		}
	}

	/**
	 * Is editor screen.
	 *
	 * @return mixed The result.
	 */
	private function is_editor_screen() {
		if ( ! empty( $_GET['action'] ) && 'wppb_editor' === $_GET['action'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return true;
		}
		return false;
	}

	/**
	 * Generate secure path url
	 *
	 * @since 1.0.0
	 * @param mixed $path_url The path url.
	 */
	public function secure_path_url( $path_url ) {
		$path_url = str_replace( array( '//', '\\\\' ), array( '/', '\\' ), $path_url );

		return str_replace( array( '/', '\\' ), DIRECTORY_SEPARATOR, $path_url );
	}

	/**
	 * Generate secure path url
	 *
	 * @since 1.0.0
	 * @param mixed $url The url.
	 */
	public function pathurl_security( $url ) {
		return preg_replace( array( '/^http:/', '/^https:/', '/(?!^)\/\//' ), array( '', '', '/' ), $url );
	}

	/**
	 * Add menu in admin bar.
	 *
	 * Adds "Plus Clear Cache" items to the WordPress admin bar.
	 *
	 * Fired by `admin_bar_menu` filter.
	 *
	 * @since 1.0.0
	 * @param \WP_Admin_Bar $wp_admin_bar The wp admin bar.
	 */
	public function add_tpgb_clear_cache_admin_bar( \WP_Admin_Bar $wp_admin_bar ) {

		global $wp_admin_bar;

		if ( ! is_super_admin()
			|| ! is_object( $wp_admin_bar )
			|| ! function_exists( 'is_admin_bar_showing' )
			|| ! is_admin_bar_showing() ) {
			return;
		}

		if ( empty( self::$tpgb_post_type ) ) {
			$this->get_post_type_post_id();
		}
		if ( file_exists( TPGB_ASSET_PATH . '/theplus-' . self::$tpgb_post_type . '-' . self::$tpgb_post_id . '.min.css' ) || file_exists( TPGB_ASSET_PATH . '/theplus-' . self::$tpgb_post_type . '-' . self::$tpgb_post_id . '.min.js' ) ) {

			$show_perf_bar = get_option( 'tpgb_connection_data' );
			if ( ! empty( $show_perf_bar ) && isset( $show_perf_bar['assets_performance'] ) && 'enable' === $show_perf_bar['assets_performance'] ) {

				// Parent.
				$wp_admin_bar->add_node(
					array(
						'id'    => 'tpda-purge-clear',
						'meta'  => array(
							'class' => 'tpda-purge-clear',
						),
						'title' => esc_html__( 'Nexter Performance', 'the-plus-addons-for-block-editor' ),
					)
				);

				// Child Item.
				$args = array();
				array_push(
					$args,
					array(
						'id'     => 'tpda-purge-all-pages',
						'title'  => esc_html__( 'Purge All Pages', 'the-plus-addons-for-block-editor' ),
						'href'   => '#tpda-clear-gutenberg-all',
						'parent' => 'tpda-purge-clear',
						'meta'   => array( 'class' => 'tpda-purge-all-pages' ),
					)
				);

				array_push(
					$args,
					array(
						'id'     => 'tpda-purge-current-page',
						'title'  => esc_html__( 'Purge Current Page', 'the-plus-addons-for-block-editor' ),
						'href'   => '#tpda-clear-theplus-' . self::$tpgb_post_type . '-' . self::$tpgb_post_id,
						'parent' => 'tpda-purge-clear',
						'meta'   => array( 'class' => 'tpda-purge-current-page' ),
					)
				);

				sort( $args );
				foreach ( $args as $each_arg ) {
					$wp_admin_bar->add_node( $each_arg );
				}
			}
			if ( ! defined( 'NEXTER_EXT' ) ) {
				// Parent.
				$wp_admin_bar->add_node(
					array(
						'id'    => 'tpgb_edit_template',
						'meta'  => array(
							'class' => 'tpgb_edit_template',
						),
						'title' => esc_html__( 'Edit Template', 'the-plus-addons-for-block-editor' ),
					)
				);
			}
		}
	}

	/**
	 * Remove all Clear cache files
	 *
	 * @since 1.0.0
	 */
	public function tpgb_smart_perf_clear_cache() {
		check_ajax_referer( 'nexter_admin_nonce', 'security' );

		// clear cache files.
		$this->remove_dir_files( TPGB_ASSET_PATH );

		wp_send_json( true );
	}

	/**
	 * Remove all Dynamic Style files
	 *
	 * @since 1.1.3
	 */
	public function tpgb_dynamic_style_cache() {
		check_ajax_referer( 'nexter_admin_nonce', 'security' );

		// clear cache files.
		$this->remove_dir_dynamic_style_files( TPGB_ASSET_PATH );

		wp_send_json( true );
	}

	/**
	 * After Save Block Remove Clear cache files
	 *
	 * @since 1.0.0
	 */
	public function tpgb_backend_clear_cache() {
		check_ajax_referer( 'tpgb-addons', 'security' );

		// clear cache files.
		$this->remove_backend_dir_files();

		wp_send_json( true );
	}

	/**
	 * Current Page Clear cache files
	 *
	 * @since 1.0.0
	 */
	public function tpgb_current_page_clear_cache() {
		check_ajax_referer( 'tpgb-addons', 'security' );

		$plus_name = '';
		if ( isset( $_POST['plus_name'] ) && ! empty( $_POST['plus_name'] ) ) {
			$plus_name = sanitize_text_field( wp_unslash( $_POST['plus_name'] ) );
		}
		if ( 'gutenberg-all' === $plus_name ) {
			// All clear cache files.
			$this->remove_dir_files( TPGB_ASSET_PATH );
		} else {
			// Current Page cache files.
			if ( $this->plus_uid ) {
				$this->remove_posts_metadata( self::$tpgb_post_id, '_block_css', 'blocks', $this->plus_uid . '_blocks' );
			}
			$this->remove_current_page_dir_files( TPGB_ASSET_PATH, $plus_name );
		}
		wp_send_json( true );
	}

	/** // phpcs:ignore Squiz.Commenting.FunctionComment, Generic.Commenting.DocComment.ShortNotCapital,Generic.Commenting.DocComment.LongNotCapital,Generic.Commenting.DocComment.MissingShort
	 * Remove files
	 *
	 * @since 1.0.0
	 * @param mixed $post_type The post type.
	 * @param mixed $post_id The post id.
	 * @param array $extension The extension.
	 */
	public function remove_files_unlink( $post_type = null, $post_id = null, $extension = array( 'css', 'js' ), $preload = false ) {
		$filename = '';
		if ( ! empty( $preload ) ) {
			$filename = 'preload-';
		}
		$css_path_url = $this->secure_path_url( TPGB_ASSET_PATH . DIRECTORY_SEPARATOR . ( $post_type ? 'theplus-' . $filename . $post_type : 'theplus' ) . ( isset( $post_id ) ? '-' . $post_id : '' ) . '.min.css' );
		$js_path_url  = $this->secure_path_url( TPGB_ASSET_PATH . DIRECTORY_SEPARATOR . ( $post_type ? 'theplus-' . $filename . $post_type : 'theplus' ) . ( isset( $post_id ) ? '-' . $post_id : '' ) . '.min.js' );

		if ( file_exists( $css_path_url ) && in_array( 'css', $extension, true ) ) {
			wp_delete_file( $css_path_url );
		}

		if ( file_exists( $js_path_url ) && in_array( 'js', $extension, true ) ) {
			wp_delete_file( $js_path_url );
		}
	}

	/**
	 * Remove in Dynamic Styles files
	 *
	 * @since 1.1.3
	 * @param mixed $path_url The path url.
	 */
	public function remove_dir_dynamic_style_files( $path_url ) {
		if ( ! is_dir( $path_url ) || ! file_exists( $path_url ) ) {
			return;
		}

		foreach ( scandir( $path_url ) as $item ) {
			if ( '.' === $item || '..' === $item ) {
				continue;
			}

			if ( strpos( $item, 'plus-global' ) !== false || strpos( $item, 'theplus' ) !== false || strpos( $item, 'plus-json-' ) !== false ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIF, Generic.CodeAnalysis.EmptyStatement.DetectedIf
			} else {
				wp_delete_file( $this->secure_path_url( $path_url . DIRECTORY_SEPARATOR . $item ) );
			}
		}
	}

	/**
	 * Remove in directory files
	 *
	 * @since 1.0.0
	 * @param mixed $path_url The path url.
	 */
	public function remove_dir_files( $path_url ) {
		if ( ! is_dir( $path_url ) || ! file_exists( $path_url ) ) {
			return;
		}
		if ( get_option( 'tpgb_backend_cache_at' ) === false ) {
			add_option( 'tpgb_backend_cache_at', strtotime( 'now' ) );
		} else {
			update_option( 'tpgb_backend_cache_at', strtotime( 'now' ), false );
		}
		foreach ( scandir( $path_url ) as $item ) {
			if ( '.' === $item || '..' === $item ) {
				continue;
			}

			if ( strpos( $item, 'plus-global' ) !== false || strpos( $item, 'plus-css-' ) !== false || strpos( $item, 'plus-json-' ) !== false ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIF, Generic.CodeAnalysis.EmptyStatement.DetectedIf
			} else {
				wp_delete_file( $this->secure_path_url( $path_url . DIRECTORY_SEPARATOR . $item ) );
			}
		}
	}

	/**
	 * Remove backend in directory files
	 *
	 * @since 1.0.0
	 */
	public function remove_backend_dir_files() {
		if ( file_exists( TPGB_ASSET_PATH . '/theplus.min.css' ) ) {
			wp_delete_file( $this->secure_path_url( TPGB_ASSET_PATH . DIRECTORY_SEPARATOR . '/theplus.min.css' ) );
		}
		if ( file_exists( TPGB_ASSET_PATH . '/theplus.min.js' ) ) {
			wp_delete_file( $this->secure_path_url( TPGB_ASSET_PATH . DIRECTORY_SEPARATOR . '/theplus.min.js' ) );
		}
		if ( get_option( 'tpgb_backend_cache_at' ) === false ) {
			add_option( 'tpgb_backend_cache_at', strtotime( 'now' ) );
		} else {
			update_option( 'tpgb_backend_cache_at', strtotime( 'now' ), false );
		}
	}

	/**
	 * Remove current Page in directory files
	 *
	 * @since 1.0.0
	 * @param mixed  $path_url The path url.
	 * @param string $plus_name The plus name.
	 */
	public function remove_current_page_dir_files( $path_url, $plus_name = '' ) {

		if ( ( ! is_dir( $path_url ) || ! file_exists( $path_url ) ) && empty( $plus_name ) ) {
			return;
		}

		if ( file_exists( $path_url . '/' . $plus_name . '.min.css' ) ) {
			wp_delete_file( $this->secure_path_url( $path_url . DIRECTORY_SEPARATOR . '/' . $plus_name . '.min.css' ) );
		}
		if ( file_exists( $path_url . '/' . str_replace( 'theplus', 'theplus-preload', $plus_name ) . '.min.css' ) ) {
			wp_delete_file( $this->secure_path_url( $path_url . DIRECTORY_SEPARATOR . '/' . str_replace( 'theplus', 'theplus-preload', $plus_name ) . '.min.css' ) );
			array_map( 'unlink', glob( $this->secure_path_url( $path_url . DIRECTORY_SEPARATOR . '/' . str_replace( 'theplus', 'theplus-preload', $plus_name . '-' ) . '*.*' ) ) );
		}
		if ( file_exists( $path_url . '/' . $plus_name . '.min.js' ) ) {
			wp_delete_file( $this->secure_path_url( $path_url . DIRECTORY_SEPARATOR . '/' . $plus_name . '.min.js' ) );
		}
	}

	/**
	 * Dynamic Style Forcely Remove In Version
	 *
	 * @since 1.1.3
	 */
	public function dynamic_style_version_clear_cache() {
		$option_name = 'tpgb_version_dynamic_cache';
		$get_version = get_option( $option_name );
		$versions    = array( TPGB_VERSION );

		if ( false === $get_version ) {
			add_option( $option_name, $versions );
			$this->remove_dir_files( TPGB_ASSET_PATH );
			$this->remove_dir_dynamic_style_files( TPGB_ASSET_PATH );
		}
		if ( false !== $get_version ) {

			// 1.4.1
			if ( version_compare( TPGB_VERSION, '1.4.1', '==' ) && ! in_array( '1.4.1', $get_version, true ) ) {
				$this->remove_dir_files( TPGB_ASSET_PATH );
				$versions = array_unique( array_merge( $get_version, $versions ) );
				update_option( $option_name, $versions );

				$get_blocks_list = get_option( 'tpgb_normal_blocks_opts' );
				if ( isset( $get_blocks_list ) && ! empty( $get_blocks_list ) ) {
					if ( isset( $get_blocks_list['enable_normal_blocks'] ) && ! empty( $get_blocks_list['enable_normal_blocks'] ) && ! in_array( 'tp-container', $get_blocks_list['enable_normal_blocks'], true ) ) {
						$get_blocks_list['enable_normal_blocks'] = array_merge( $get_blocks_list['enable_normal_blocks'], array( 'tp-container' ) );
						update_option( 'tpgb_normal_blocks_opts', $get_blocks_list );
					}
				}
			}

			if ( ! in_array( '3.2.4', $get_version, true ) ) {
				$versions = array_unique( array_merge( $get_version, $versions ) );
				update_option( $option_name, $versions );

				$get_blocks_list = get_option( 'tpgb_normal_blocks_opts' );
				if ( isset( $get_blocks_list ) && ! empty( $get_blocks_list ) ) {
					if ( ! isset( $get_blocks_list['tp_extra_option'] ) ) {

						$plus_ex_list = array( 'tp-advanced-border-radius', 'tp-display-rules', 'tp-equal-height', 'tp-event-tracking', 'tp-magic-scroll', 'tp-global-tooltip', 'tp-continuous-animation', 'tp-content-hover-effect', 'tp-mouse-parallax', 'tp-3d-tilt', 'tp-scoll-animation' );

						$get_blocks_list['tp_extra_option'] = $plus_ex_list;
						update_option( 'tpgb_normal_blocks_opts', $get_blocks_list );
					}
				}
			}

			// 4.1.0
			if ( version_compare( TPGB_VERSION, '4.1.0', '==' ) && ! in_array( '4.1.0', $get_version, true ) ) {
				$this->remove_dir_files( TPGB_ASSET_PATH );
				$this->remove_dir_dynamic_style_files( TPGB_ASSET_PATH );
				$versions = array_unique( array_merge( $get_version, $versions ) );
				$this->update_save_updated_at();
				update_option( $option_name, $versions );

				// Clear Litespeed cache.
				if ( method_exists( 'LiteSpeed_Cache_API', 'purge_all' ) ) {
					LiteSpeed_Cache_API::purge_all();
				}

				// berqCache All cache.
				if ( class_exists( 'berqCache' ) && method_exists( 'berqCache', 'delete_cache_files' ) ) {
					$berq_cache = new berqCache();
					$berq_cache->delete_cache_files();
				}

				// W3 Total Cache.
				if ( function_exists( 'w3tc_flush_all' ) ) {
					w3tc_flush_all();
				}

				// WP Fastest Cache.
				if ( ! empty( $GLOBALS['wp_fastest_cache'] ) && method_exists( $GLOBALS['wp_fastest_cache'], 'deleteCache' ) ) {
					$GLOBALS['wp_fastest_cache']->deleteCache( true );
				}

				// WP Super Cache.
				if ( function_exists( 'wp_cache_clean_cache' ) ) {
					global $file_prefix;
					wp_cache_clean_cache( $file_prefix, true );
				}

				$all_clear_cache = array(
					'W3 Total Cache'    => 'w3tc_pgcache_flush',
					'WP Fastest Cache'  => 'wpfc_clear_all_cache',
					'WP Rocket'         => 'rocket_clean_domain',
					'Cachify'           => 'cachify_flush_cache',
					'Comet Cache'       => array( 'comet_cache', 'clear' ),
					'SG Optimizer'      => 'sg_cachepress_purge_cache',
					'Pantheon'          => 'pantheon_wp_clear_edge_all',
					'Zen Cache'         => array( 'zencache', 'clear' ),
					'Breeze'            => array( 'Breeze_PurgeCache', 'breeze_cache_flush' ),
					'Swift Performance' => array( 'Swift_Performance_Cache', 'clear_all_cache' ),
				);

				foreach ( $all_clear_cache as $plugin => $method ) {
					if ( is_callable( $method ) ) {
						call_user_func( $method );
					}
				}
			}
		}
	}

	/**
	 * Returns the instance.
	 *
	 * @since  1.0.0
	 * @param array $shortcodes The shortcodes.
	 */
	public static function get_instance( $shortcodes = array() ) {

		if ( null === self::$instance ) {
			self::$instance = new self( $shortcodes );
		}
		return self::$instance;
	}

	/**
	 * Filters the content of a single block.
	 *
	 * @since 1.3.0.2
	 * @access public
	 * @param string $block_content The block content about to be appended.
	 * @param array  $block         The full block, including name and attributes.
	 * @return string               Returns $block_content unaltered.
	 */
	public function render_block( $block_content, $block ) {

		if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
			return $block_content;
		}
		if ( $block['blockName'] ) {

			if ( isset( $block['blockName'] ) && in_array( $block['blockName'], array( 'core/html', 'tpgb/tp-creative-image', 'tpgb/tp-media-listing' ), true ) ) {

				$block_content = preg_replace_callback(
					'/data-caption="([^"]*)"/i',
					function ( $matches ) {
						return 'data-caption="' . esc_attr( wp_strip_all_tags( html_entity_decode( $matches[1] ) ) ) . '"';
					},
					$block_content
				);

			}

			$options = ( ! empty( $block['attrs'] ) ) ? $block['attrs'] : '';
			if ( ! empty( $options ) && class_exists( 'Tpgb_Get_Blocks' ) && $this->check_generate_script() === true ) {
				if ( isset( $options['block_id'] ) && ! in_array( $options['block_id'], $this->block_ids, true ) ) {
					$new_blocks             = Tpgb_Get_Blocks::get_instance()->plus_blocks_options( $options, $block['blockName'] );
					$this->transient_blocks = array_merge( $this->transient_blocks, $new_blocks );
					$this->block_ids[]      = $options['block_id'];
				}
			}

			$this->transient_blocks[] = $block['blockName'];

			if ( ! preg_match( '/\btpgb\/\b/', $block['blockName'] ) && isset( $options['tpgbDisrule'] ) && ! empty( $options['tpgbDisrule'] ) ) {
				$global_blocks = Tpgb_Blocks_Global_Options::get_instance();
				$options       = array_merge( $global_blocks::render_block_default_attributes(), $options );
				$block_content = $global_blocks::block_row_conditional_render( $options, $block_content );
			}

			// check content shortcode.
			if ( ! empty( $block_content ) && preg_match_all( '/' . get_shortcode_regex() . '/s', $block_content, $matches ) ) {
				if ( ! empty( $matches ) && array_key_exists( 2, $matches ) && has_shortcode( $block_content, $matches[2][0] ) ) {
					$attrs = shortcode_parse_atts( $matches[3][0] );
					if ( ! empty( $attrs ) && isset( $attrs['id'] ) && ! empty( $attrs['id'] ) ) {
						if ( class_exists( 'Tpgb_Core_Init_Blocks' ) ) {
							$css_file = Tpgb_Core_Init_Blocks::get_instance();
							if ( ! empty( $css_file ) && is_callable( array( $css_file, 'enqueue_post_css' ) ) ) {
								$css_file->enqueue_post_css( $attrs['id'] );
							}
						}
					}
				}
			}

			// Get Dynamic Content.
			if ( class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) {
				$blocks_helper = Tpgbp_Pro_Blocks_Helper::get_instance();
				if ( ! empty( $blocks_helper ) && is_callable( array( $blocks_helper, 'tpgb_dynamic_val' ) ) ) {
					$block_content = $blocks_helper::tpgb_dynamic_val( $block_content, $block );
				}
			}

			// get pattern content.
			if ( ! empty( $block_content ) ) {

				$ref_pattern = '/<div class="tpgb-ref-temp" data-ref="(\d+)"><\/div>/';
				$matches     = array();

				if ( preg_match_all( $ref_pattern, $block_content, $matches, PREG_SET_ORDER ) ) {
					foreach ( $matches as $match ) {
						if ( isset( $match[1] ) && ! empty( $match[1] ) ) {
							$ref_id      = $match[1];
							$new_content = $this->plus_do_block( $ref_id );

							if ( class_exists( 'Tpgb_Core_Init_Blocks' ) ) {
								$css_file = Tpgb_Core_Init_Blocks::get_instance();
								if ( ! empty( $css_file ) && is_callable( array( $css_file, 'enqueue_post_css' ) ) ) {
									$css_file->enqueue_post_css( $ref_id );
								}
							}

							$replacement   = $new_content;
							$block_content = str_replace( $match[0], $replacement, $block_content );
						}
					}
				}

				// ajax pattern match.
				if ( preg_match_all( '/class="[^"]*tpgb-load-template-[^"]* tpgb-load-(\d+)[^"]*"/', $block_content, $matches1 ) ) {
					foreach ( $matches1[1] as $match ) {

						if ( isset( $match ) && ! empty( $match ) && is_numeric( $match ) ) {
							$ref_id = $match;
							if ( ! in_array( $ref_id, $this->plus_template_blocks, true ) ) {
								$this->plus_template_blocks[] = $ref_id;
								$this->plus_do_block( $ref_id );

								if ( class_exists( 'Tpgb_Core_Init_Blocks' ) ) {
									$css_file = Tpgb_Core_Init_Blocks::get_instance();
									if ( ! empty( $css_file ) && is_callable( array( $css_file, 'enqueue_post_css' ) ) ) {
										$css_file->enqueue_post_css( $ref_id );
									}
								}
							}
						}
					}
				}
			}

			// Full site editing compatibility.
			if ( preg_match( '/\btpgb\/\b/', $block['blockName'] ) && ! empty( $block ) && ! empty( $block['innerHTML'] ) ) {
				$styletag = '/<style>(.*?)<\/style>/m';
				if ( preg_match_all( $styletag, $block['innerHTML'], $style_matches ) ) {
					$style = ( $style_matches[0] && $style_matches[0][0] ) ? $style_matches[0][0] : '';
					if ( strpos( $block_content, $style ) === false ) {
						$block_content .= $style;
					}
				}
			}
		}
		return $block_content;
	}

	/**
	 * Plus post save transient.
	 *
	 * @param int   $post_id The post id.
	 * @param mixed $post The post.
	 * @param mixed $update The update.
	 */
	public function plus_post_save_transient( $post_id, $post, $update ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter

		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		$current_post_type = get_post_type( $post_id );

		$shop_page_id = '';
		if ( function_exists( 'is_shop' ) && function_exists( 'wc_get_page_id' ) ) {
			$shop_page_id = wc_get_page_id( 'shop' );
		}

		if ( ! in_array( $current_post_type, array( 'post', 'page', 'product' ), true ) ) {
			$this->update_save_updated_at();

			if ( class_exists( 'Breeze_Configuration' ) && class_exists( 'Breeze_CloudFlare_Helper' ) && class_exists( 'Breeze_Admin' ) ) {
				// clear varnish cache.
				$admin = new Breeze_Admin();
				$admin->breeze_clear_varnish();

				// clear All cache.
				Breeze_Configuration::breeze_clean_cache();
				Breeze_CloudFlare_Helper::reset_all_cache();
			}
		} elseif ( $this->get_posts_metadata( $post_id, '_block_css', 'updated_at' ) !== false || ( ! empty( $shop_page_id ) && $post_id === $shop_page_id ) ) {
			$this->remove_posts_metadata( $post_id, '_block_css', 'updated_at', 'theplus-post-' . $post_id . '_updated_at' );

			if ( ! empty( $shop_page_id ) && $post_id === $shop_page_id ) {
				$this->remove_posts_metadata( 'product', '_block_css', 'updated_at', 'theplus-term-product_updated_at' );
			}
		}

		if ( wp_is_block_theme() ) {
			if ( 'wp_template_part' === $current_post_type || 'wp_template' === $current_post_type || 'wp_block' === $current_post_type || 'page' === $current_post_type ) {
				$css_path_url = $this->secure_path_url( TPGB_ASSET_PATH . DIRECTORY_SEPARATOR . 'plus-css-' . $post_id . '.css' );

				if ( file_exists( $css_path_url ) ) {
					wp_delete_file( $css_path_url );
				}

				// WP Super Cache.
				if ( function_exists( 'wp_cache_clean_cache' ) ) {
					global $file_prefix;
					wp_cache_clean_cache( $file_prefix, true );
				}
			}
		}
	}

	/**
	 * Init post request data.
	 */
	public function init_post_request_data() {

		if ( is_admin() ) {
			return;
		}

		if ( $this->is_background_running() ) {
			return;
		}

		$uid = null;

		if ( ! $this->is_preview_mode() ) {
			if ( $this->get_post_type_post_id() ) {
				$uid = 'theplus-' . self::$tpgb_post_type . '-' . self::$tpgb_post_id;
			}
		} else {
			$uid = 'theplus';
		}

		if ( $uid && null === $this->plus_uid ) {
			$this->plus_uid        = $uid;
			$this->requires_update = $this->requires_update();
		}
	}

	/**
	 * Requires update.
	 *
	 * @return mixed The result.
	 */
	public function requires_update() {

		$blocks          = $this->get_posts_metadata( self::$tpgb_post_id, '_block_css', 'blocks', $this->plus_uid . '_blocks' );
		$save_updated_at = $this->get_save_updated_at();
		$post_updated_at = $this->get_posts_metadata( self::$tpgb_post_id, '_block_css', 'updated_at', $this->plus_uid . '_updated_at' );

		if ( false === $blocks ) {
			return true;
		}
		if ( false === $save_updated_at ) {
			return true;
		}
		if ( false === $post_updated_at || empty( $post_updated_at ) || ( ! empty( $post_updated_at ) && $save_updated_at !== $post_updated_at ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Check if wp running in background
	 *
	 * @since 1.0.0
	 */
	public function is_background_running() {
		if ( wp_doing_cron() ) {
			return true;
		}

		if ( wp_doing_ajax() ) {
			return true;
		}

		/*
		if (isset($_REQUEST['action'])) {
			return true;
		}*/

		return false;
	}

	/**
	 * Tpgb onload style css.
	 *
	 * @param mixed $html The html.
	 * @param mixed $handle The handle.
	 * @param mixed $href The href.
	 * @param mixed $media The media.
	 * @return mixed The result.
	 */
	public function tpgb_onload_style_css( $html, $handle, $href, $media ) {
		$handles = array( 'dashicons', 'wp-block-library', 'tpgb-fontawesome' );
		if ( ( substr( $handle, 0, 10 ) === 'plus-post-' ) || in_array( $handle, $handles, true ) ) {
			$html = '<link rel="preload" href="' . $href . '" as="style" id="' . $handle . '" media="' . $media . '" onload="this.onload=null;this.rel=\'stylesheet\'">'
			. '<noscript>' . $html . '</noscript>';
		}

		return $html;
	}

	/**
	 * Tpgb onload defer js.
	 *
	 * @param mixed $html The html.
	 * @param mixed $handle The handle.
	 * @return mixed The result.
	 */
	public function tpgb_onload_defer_js( $html, $handle ) {
		$handles = array( 'nexter-frontend-js', 'tpgb-delay', 'tpgb-plus-block-front-js', 'tpgb-lottie-extra', 'tpgb-lottie', 'gmaps-js', 'tpgb-font-awesome-pro' );
		if ( in_array( $handle, $handles, true ) || substr( $handle, 0, 7 ) === 'theplus' ) {
			$html = str_replace( '></script>', ' defer></script>', $html );
		}
		return $html;
	}

	/**
	 * Tpgb lazy render defer js.
	 *
	 * @param mixed $html The html.
	 * @param mixed $handle The handle.
	 * @return mixed The result.
	 */
	public function tpgb_lazy_render_defer_js( $html, $handle ) {

		if ( 'tpgb-ajax-load-template' === $handle ) {
			$html = str_replace( '></script>', ' defer></script>', $html );
		}
		return $html;
	}

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->get_registered_blocks();
		$this->plus_template_blocks = array();
		add_filter( 'render_block', array( $this, 'render_block' ), 1000, 2 );

		add_filter( 'plus_template_parse_blocks', array( $this, 'plus_template_load_parse_blocks' ) );
		add_action( 'wp_footer', array( $this, 'generate_scripts_frontend' ) ); // wp_print_footer_scripts.

		add_action( 'save_post', array( $this, 'plus_post_save_transient' ), 10, 3 );
		if ( ! is_admin() ) {
			add_action( 'wp', array( $this, 'init_post_request_data' ) );
		}
		if ( $this->get_caching_option() !== 'separate' ) {
			add_action( 'admin_bar_menu', array( $this, 'add_tpgb_clear_cache_admin_bar' ), 300 );
			add_action( 'wp_ajax_tpda_purge_current_clear', array( $this, 'tpgb_current_page_clear_cache' ) );
		}

		if ( is_admin() ) {
			add_action( 'admin_init', array( $this, 'dynamic_style_version_clear_cache' ) );
			add_action( 'wp_ajax_tpgb_all_perf_clear_cache', array( $this, 'tpgb_smart_perf_clear_cache' ) );
			add_action( 'wp_ajax_tpgb_all_dynamic_clear_style', array( $this, 'tpgb_dynamic_style_cache' ) );
			add_action( 'wp_ajax_tpgb_backend_clear_cache', array( $this, 'tpgb_backend_clear_cache' ) );
		}
		if ( ! is_admin() ) {
			add_action( 'wp', array( $this, 'header_init_css_js' ), 10, 2 );
		}
		if ( ! is_admin() && $this->get_defer_css_js() ) {
			add_filter( 'style_loader_tag', array( $this, 'tpgb_onload_style_css' ), 10, 4 );
			add_filter( 'script_loader_tag', array( $this, 'tpgb_onload_defer_js' ), 10, 2 );
		}

		$tpgb_ajax = Tp_Blocks_Helper::get_extra_option( 'tpgb_template_load' );
		$tplzy     = Tp_Blocks_Helper::get_extra_option( 'tpgb_lazy_render' );
		if ( ! is_admin() && ( ( isset( $tpgb_ajax ) && ! empty( $tpgb_ajax ) && 'enable' === $tpgb_ajax ) || ( isset( $tplzy ) && ! empty( $tplzy ) && 'enable' === $tplzy ) || empty( $tpgb_ajax ) || empty( $tplzy ) ) ) {
			add_filter( 'script_loader_tag', array( $this, 'tpgb_lazy_render_defer_js' ), 10, 2 );
		}

		// Toolset Compatibility.
		if ( defined( 'WPV_VERSION' ) ) {
			require_once TPGB_PATH . 'classes/extras/compatibility/class-tpgb-toolset.php';
		}
	}
}

/**
 * Returns instance of Tpgb_Library
 */
function tpgb_library() {
	return Tpgb_Library::get_instance();
}
