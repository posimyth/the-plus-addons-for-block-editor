<?php
/**
 * TPGB Core Plugin.
 *
 * @package TPGB
 */

// phpcs:disable Squiz.PHP.DisallowMultipleAssignments.Found
// phpcs:disable Squiz.PHP.DisallowMultipleAssignments.FoundInControlStructure
// phpcs:disable WordPress.Files.FileName
// phpcs:disable Squiz.PHP.CommentedOutCode.Found

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Tp_Blocks_Helper.
 *
 * @package TPGB
 */
class Tp_Blocks_Helper {

	/**
	 * Member Variable
	 *
	 * @var instance
	 */
	private static $instance;
	/**
	 * Get load block.
	 *
	 * @var mixed
	 */
	protected static $get_load_block;

	/**
	 * Get block deactivate.
	 *
	 * @var array
	 */
	protected static $get_block_deactivate = array();

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
	 * Constructor
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'init_blocks_load' ) );
		add_action( 'wp_head', array( $this, 'custom_css_js_load' ) );
		add_filter( 'upload_mimes', array( $this, 'tpgb_mime_types' ) );

		// Ajax For Template Content.
		add_action( 'wp_ajax_tpgb_get_template_content', array( $this, 'tpgb_get_template_content' ) );
		add_action( 'wp_ajax_nopriv_tpgb_get_template_content', array( $this, 'tpgb_get_template_content' ) );

		// Form Block AJAX Function.
		add_action( 'wp_ajax_nxt_form_action', array( $this, 'nxt_form_action_callback' ) );
		add_action( 'wp_ajax_nopriv_nxt_form_action', array( $this, 'nxt_form_action_callback' ) );
	}

	/**
	Load Custom Css and Js
	 *
	 * @since 1.0.0
	 */
	public function custom_css_js_load() {
		$get_custom_css_js = get_option( 'tpgb_custom_css_js' );

		$load_css_js = '';
		// Load Custom Style.
		if ( ! empty( $get_custom_css_js['tpgb_custom_css_editor'] ) ) {
			$get_css = $get_custom_css_js['tpgb_custom_css_editor'];

			// Remove comments.
			$get_css = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $get_css );
			// Remove space after colons.
			$get_css = str_replace( ': ', ':', $get_css );
			// Remove whitespace.
			$get_css = str_replace( array( "\r\n", "\r", "\n", "\t", '  ', '    ', '    ' ), '', $get_css );
			// Remove Last Semi colons.
			$get_css = preg_replace( '/;}/', '}', $get_css );

			$load_css_js .= '<style type="text/css">';
			$load_css_js .= $get_css;
			$load_css_js .= '</style>';
		}

		// Load Custom Script.
		if ( ! empty( $get_custom_css_js['tpgb_custom_js_editor'] ) ) {
			$get_js       = $get_custom_css_js['tpgb_custom_js_editor'];
			$load_css_js .= wp_print_inline_script_tag( $get_js );
		}
		echo $load_css_js; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * SVG Upload Mime types
	 *
	 * @since 1.0.0
	 * @param mixed $mimes The mimes.
	 */
	public function tpgb_mime_types( $mimes ) {
		$mimes['svg'] = 'image/svg+xml';
		return $mimes;
	}

	/**
	 * Get extra option.
	 *
	 * @param mixed $field The field.
	 * @return mixed The result.
	 */
	public static function get_extra_option( $field ) {
		$options    = get_option( 'tpgb_connection_data' );
			$values = '';
		if ( isset( $options[ $field ] ) && ! empty( $options[ $field ] ) ) {
			$values = $options[ $field ];
		}
		return $values;
	}

	/**
	 * Init Block Load.
	 *
	 * @since 1.0.0
	 */
	public function init_blocks_load() {
		// Return early if this function does not exist.
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}
		include_once 'global-options/tp-global-options.php';

		$load_blocks = array(
			'tp-accordion'               => TPGB_CATEGORY . '/tp-accordion',
			'tp-blockquote'              => TPGB_CATEGORY . '/tp-blockquote',
			'tp-breadcrumbs'             => TPGB_CATEGORY . '/tp-breadcrumbs',
			'tp-button'                  => TPGB_CATEGORY . '/tp-button',
			'tp-button-core'             => TPGB_CATEGORY . '/tp-button-core',
			'tp-container'               => TPGB_CATEGORY . '/tp-container',
			'tp-code-highlighter'        => TPGB_CATEGORY . '/tp-code-highlighter',
			'tp-countdown'               => TPGB_CATEGORY . '/tp-countdown',
			'tp-creative-image'          => TPGB_CATEGORY . '/tp-creative-image',
			'tp-data-table'              => TPGB_CATEGORY . '/tp-data-table',
			'tp-dark-mode'               => TPGB_CATEGORY . '/tp-dark-mode',
			'tp-draw-svg'                => TPGB_CATEGORY . '/tp-draw-svg',
			'tp-empty-space'             => TPGB_CATEGORY . '/tp-empty-space',
			'tp-external-form-styler'    => TPGB_CATEGORY . '/tp-external-form-styler',
			'tp-flipbox'                 => TPGB_CATEGORY . '/tp-flipbox',
			'tp-form-block'              => TPGB_CATEGORY . '/tp-form-block',
			'tp-google-map'              => TPGB_CATEGORY . '/tp-google-map',
			'tp-heading'                 => TPGB_CATEGORY . '/tp-heading',
			'tp-heading-title'           => TPGB_CATEGORY . '/tp-heading-title',
			'tp-hovercard'               => TPGB_CATEGORY . '/tp-hovercard',
			'tp-icon-box'                => TPGB_CATEGORY . '/tp-icon-box',
			'tp-image'                   => TPGB_CATEGORY . '/tp-image',
			'tp-infobox'                 => TPGB_CATEGORY . '/tp-infobox',
			'tp-interactive-circle-info' => TPGB_CATEGORY . '/tp-interactive-circle-info',
			'tp-messagebox'              => TPGB_CATEGORY . '/tp-messagebox',
			'tp-navigation-builder'      => TPGB_CATEGORY . '/tp-navigation-builder',
			'tp-number-counter'          => TPGB_CATEGORY . '/tp-number-counter',
			'tp-post-author'             => TPGB_CATEGORY . '/tp-post-author',
			'tp-post-comment'            => TPGB_CATEGORY . '/tp-post-comment',
			'tp-post-content'            => TPGB_CATEGORY . '/tp-post-content',
			'tp-post-image'              => TPGB_CATEGORY . '/tp-post-image',
			'tp-post-listing'            => TPGB_CATEGORY . '/tp-post-listing',
			'tp-post-meta'               => TPGB_CATEGORY . '/tp-post-meta',
			'tp-post-title'              => TPGB_CATEGORY . '/tp-post-title',
			'tp-pricing-list'            => TPGB_CATEGORY . '/tp-pricing-list',
			'tp-pricing-table'           => TPGB_CATEGORY . '/tp-pricing-table',
			'tp-pro-paragraph'           => TPGB_CATEGORY . '/tp-pro-paragraph',
			'tp-progress-bar'            => TPGB_CATEGORY . '/tp-progress-bar',
			'tp-progress-tracker'        => TPGB_CATEGORY . '/tp-progress-tracker',
			'tp-row'                     => TPGB_CATEGORY . '/tp-row',
			'tp-search-bar'              => TPGB_CATEGORY . '/tp-search-bar',
			'tp-site-logo'               => TPGB_CATEGORY . '/tp-site-logo',
			'tp-stylist-list'            => TPGB_CATEGORY . '/tp-stylist-list',
			'tp-social-icons'            => TPGB_CATEGORY . '/tp-social-icons',
			'tp-social-feed'             => TPGB_CATEGORY . '/tp-social-feed',
			'tp-social-reviews'          => TPGB_CATEGORY . '/tp-social-reviews',
			'tpgb-settings'              => TPGB_CATEGORY . '/tpgb-settings',
			'tp-smooth-scroll'           => TPGB_CATEGORY . '/tp-smooth-scroll',
			'tp-social-embed'            => TPGB_CATEGORY . '/tp-social-embed',
			'tp-switcher'                => TPGB_CATEGORY . '/tp-switcher',
			'tp-tabs-tours'              => TPGB_CATEGORY . '/tp-tabs-tours',
			'tp-team-listing'            => TPGB_CATEGORY . '/tp-team-listing',
			'tp-testimonials'            => TPGB_CATEGORY . '/tp-testimonials',
			'tp-video'                   => TPGB_CATEGORY . '/tp-video',
		);

		if ( has_filter( 'tpgb_load_blocks' ) ) {
			$load_blocks = apply_filters( 'tpgb_load_blocks', $load_blocks );
		}

		$enable_normal_blocks = $this->tpgb_get_option( 'tpgb_normal_blocks_opts', 'enable_normal_blocks' );

		if ( ! empty( $enable_normal_blocks ) ) {
			self::$get_load_block   = $enable_normal_blocks;
			self::$get_load_block[] = 'tpgb-settings';
			$this->include_block( 'tpgb-settings' );

			foreach ( $load_blocks as $block_id => $block ) {
				if ( in_array( $block_id, $enable_normal_blocks, true ) ) {
					$this->include_block( $block_id );
					if ( ! empty( $block_id ) && 'tp-container' === $block_id ) {
						self::$get_load_block[] = 'tp-container-inner';
						$this->include_block( 'tp-container-inner' );
					}
					if ( ! empty( $block_id ) && 'tp-accordion' === $block_id ) {
						self::$get_load_block[] = 'tp-accordion-inner';
						$this->include_block( 'tp-accordion-inner' );
					}
					if ( ! empty( $block_id ) && 'tp-tabs-tours' === $block_id ) {
						self::$get_load_block[] = 'tp-tab-item';
						$this->include_block( 'tp-tab-item' );
					}
					if ( ! empty( $block_id ) && 'tp-anything-carousel' === $block_id ) {
						self::$get_load_block[] = 'tp-anything-slide';
						$this->include_block( 'tp-anything-slide' );
					}

					if ( ! empty( $block_id ) && 'tp-form-block' === $block_id ) {
						$form_child = array(
							'tp-form-block/child-blocks/nxt-name-field',
							'tp-form-block/child-blocks/nxt-number-field',
							'tp-form-block/child-blocks/nxt-email-field',
							'tp-form-block/child-blocks/nxt-message-field',
							'tp-form-block/child-blocks/nxt-submit-button',
							'tp-form-block/child-blocks/nxt-option-field',
							'tp-form-block/child-blocks/nxt-radio-button',
							'tp-form-block/child-blocks/nxt-checkbox-button',

							'tp-form-block/child-blocks/nxt-url-field',
							'tp-form-block/child-blocks/nxt-acceptance-button',
							'tp-form-block/child-blocks/nxt-time-field',
							'tp-form-block/child-blocks/nxt-date-field',
							'tp-form-block/child-blocks/nxt-phone-field',
						);

						foreach ( $form_child as $block ) {
							self::$get_load_block[] = $block;
							$this->include_block( $block );
						}
					}

					if ( defined( 'TPGBP_VERSION' ) ) {
						if ( ! empty( $block_id ) && 'tp-switcher' === $block_id ) {
							self::$get_load_block[] = 'tp-switch-inner';
							$this->include_block( 'tp-switch-inner' );
						}
						if ( ! empty( $block_id ) && 'tp-timeline' === $block_id ) {
							self::$get_load_block[] = 'tp-timeline-inner';
							$this->include_block( 'tp-timeline-inner' );
						}

						if ( ! empty( $block_id ) && 'tp-repeater-block' === $block_id ) {
							self::$get_load_block[] = 'tp-repeater-layout';
							$this->include_block( 'tp-repeater-block/child-block/tp-repeater-layout' );

							self::$get_load_block[] = 'tp-dynamic-heading';
							$this->include_block( 'tp-repeater-block/child-block/tp-dynamic-heading' );
						}
					}
				}
			}

			$deactivate_block = array();
			foreach ( $load_blocks as $block_id => $block ) {
				if ( ! in_array( $block_id, $enable_normal_blocks, true ) && 'tpgb-settings' !== $block_id ) {
					$deactivate_block[] = $block_id;
				}
			}
			if ( ! in_array( 'tp-container', $enable_normal_blocks, true ) ) {
				$deactivate_block[] = 'tp-container-inner';
			}
			if ( ! in_array( 'tp-accordion', $enable_normal_blocks, true ) ) {
				$deactivate_block[] = 'tp-accordion-inner';
			}
			if ( ! in_array( 'tp-anything-carousel', $enable_normal_blocks, true ) ) {
				$deactivate_block[] = 'tp-anything-slide';
			}
			if ( ! in_array( 'tp-tabs-tours', $enable_normal_blocks, true ) ) {
				$deactivate_block[] = 'tp-tab-item';
			}
			if ( ! in_array( 'tp-form-block', $enable_normal_blocks, true ) ) {
				$form_child = array(
					'tp-form-block/child-blocks/nxt-name-field',
					'tp-form-block/child-blocks/nxt-number-field',
					'tp-form-block/child-blocks/nxt-email-field',
					'tp-form-block/child-blocks/nxt-message-field',
					'tp-form-block/child-blocks/nxt-submit-button',
					'tp-form-block/child-blocks/nxt-option-field',
					'tp-form-block/child-blocks/nxt-radio-button',
					'tp-form-block/child-blocks/nxt-checkbox-button',

					'tp-form-block/child-blocks/nxt-url-field',
					'tp-form-block/child-blocks/nxt-acceptance-button',
					'tp-form-block/child-blocks/nxt-time-field',
					'tp-form-block/child-blocks/nxt-date-field',
					'tp-form-block/child-blocks/nxt-phone-field',
				);
				foreach ( $form_child as $block ) {
					$deactivate_block[] = $block;
				}
			}
			if ( defined( 'TPGBP_VERSION' ) ) {
				if ( ! in_array( 'tp-switcher', $enable_normal_blocks, true ) ) {
					$deactivate_block[] = 'tp-switch-inner';
				}
				if ( ! in_array( 'tp-timeline', $enable_normal_blocks, true ) ) {
					$deactivate_block[] = 'tp-timeline-inner';
				}
				if ( ! in_array( 'tp-repeater-block', $enable_normal_blocks, true ) ) {
					$deactivate_block[] = 'tp-repeater-layout';
					$deactivate_block[] = 'tp-dynamic-heading';
				}
			}
			self::$get_block_deactivate = $deactivate_block;
		} else {
			foreach ( $load_blocks as $block_id => $block ) {
				self::$get_block_deactivate[] = $block_id;
				if ( ! empty( $block_id ) && 'tp-row' === $block_id ) {
					self::$get_block_deactivate[] = 'tp-column';
				}
				if ( ! empty( $block_id ) && 'tp-container' === $block_id ) {
					self::$get_block_deactivate[] = 'tp-container-inner';
				}
				if ( ! empty( $block_id ) && 'tp-accordion' === $block_id ) {
					self::$get_block_deactivate[] = 'tp-accordion-inner';
				}
				if ( ! empty( $block_id ) && 'tp-tabs-tours' === $block_id ) {
					self::$get_block_deactivate[] = 'tp-tab-item';
				}
				if ( ! empty( $block_id ) && 'tp-anything-carousel' === $block_id ) {
					self::$get_block_deactivate[] = 'tp-anything-slide';
				}

				if ( ! empty( $block_id ) && 'tp-form-block' === $block_id ) {
					$form_child = array(
						'tp-form-block/child-blocks/nxt-name-field',
						'tp-form-block/child-blocks/nxt-number-field',
						'tp-form-block/child-blocks/nxt-email-field',
						'tp-form-block/child-blocks/nxt-message-field',
						'tp-form-block/child-blocks/nxt-submit-button',
						'tp-form-block/child-blocks/nxt-option-field',
						'tp-form-block/child-blocks/nxt-radio-button',
						'tp-form-block/child-blocks/nxt-checkbox-button',

						'tp-form-block/child-blocks/nxt-url-field',
						'tp-form-block/child-blocks/nxt-acceptance-button',
						'tp-form-block/child-blocks/nxt-time-field',
						'tp-form-block/child-blocks/nxt-date-field',
						'tp-form-block/child-blocks/nxt-phone-field',
					);
					foreach ( $form_child as $block ) {
						self::$get_block_deactivate[] = $block;
					}
				}

				if ( defined( 'TPGBP_VERSION' ) ) {
					if ( ! empty( $block_id ) && 'tp-switcher' === $block_id ) {
						self::$get_block_deactivate[] = 'tp-switch-inner';
					}
					if ( ! empty( $block_id ) && 'tp-timeline' === $block_id ) {
						self::$get_block_deactivate[] = 'tp-timeline-inner';
					}
					if ( ! empty( $block_id ) && 'tp-repeater-block' === $block_id ) {
						self::$get_load_block[] = 'tp-repeater-layout';
						$this->include_block( 'tp-repeater-layout' );

						self::$get_load_block[] = 'tp-dynamic-heading';
						$this->include_block( 'tp-dynamic-heading' );
					}
				}
			}
		}
	}

	/**
	 * Load Block Include Required File
	 *
	 * @since 1.0.0
	 * @param int $block_id The block id.
	 */
	public function include_block( $block_id ) {
		$filename = sprintf( 'classes/blocks/' . esc_attr( $block_id ) . '/index.php' );

		$block_path = TPGB_PATH;
		if ( defined( 'TPGBP_VERSION' ) && defined( 'TPGBP_PATH' ) && version_compare( TPGB_VERSION, '4.0.0', '>=' ) ) {
			$block_path = TPGBP_PATH;
		}

		if ( file_exists( $block_path . $filename ) ) {
			require $block_path . $filename;
			return true;
		} elseif ( file_exists( TPGB_PATH . $filename ) ) {
			require TPGB_PATH . $filename;
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Get load activate Block for tpgb
	 *
	 *  @Array
	 */
	public static function get_block_enabled() {
		$load_enable_block = self::$get_load_block;

		if ( ! empty( $load_enable_block ) ) {
			return $load_enable_block;
		} else {
			return;
		}
	}

	/**
	 * Get load deactivate Block for tpgb
	 *
	 *  @Array
	 */
	public static function get_block_deactivate() {
		$load_disable_block = self::$get_block_deactivate;

		if ( ! empty( $load_disable_block ) ) {
			return $load_disable_block;
		} else {
			return;
		}
	}

	/**
	 * Get post type list.
	 *
	 * @return mixed The result.
	 */
	public static function get_post_type_list() {
		$args       = array(
			'public'  => true,
			'show_ui' => true,
		);
		$post_types = get_post_types( $args, 'objects' );
		$options    = array();
		foreach ( $post_types  as $post_type ) {
			$exclude = array( 'attachment', 'elementor_library', 'e-landing-page', 'nxt_builder' );
			if ( true === in_array( $post_type->name, $exclude, true ) ) {
				continue;
			}

			$options[] = array( $post_type->name, $post_type->label );
		}

		return $options;
	}

	/**
	 * Get Image size information for all currently-registered image sizes
	 */
	public static function get_image_sizes() {

		global $_wp_additional_image_sizes;

		$sizes       = get_intermediate_image_sizes();
		$image_sizes = array();

		$image_sizes[] = array( 'full', esc_html__( 'Full', 'the-plus-addons-for-block-editor' ) );

		foreach ( $sizes as $size ) {
			if ( in_array( $size, array( 'thumbnail', 'medium', 'medium_large', 'large' ), true ) ) {
				$image_sizes[] = array( $size, ucwords( trim( str_replace( array( '-', '_' ), array( ' ', ' ' ), $size ) ) ) );
			} else {
				$image_sizes[] = array(
					$size,
					sprintf(
						'%1$s (%2$sx%3$s)',
						ucwords( trim( str_replace( array( '-', '_' ), array( ' ', ' ' ), $size ) ) ),
						$_wp_additional_image_sizes[ $size ]['width'],
						$_wp_additional_image_sizes[ $size ]['height']
					),
				);
			}
		}

		$image_sizes = apply_filters( 'tpgb_image_sizes', $image_sizes );

		return $image_sizes;
	}

	/**
	 * Tpgb get option.
	 *
	 * @param array $options The options.
	 * @param mixed $field The field.
	 * @return mixed The result.
	 */
	public function tpgb_get_option( $options, $field ) {

		$tpgb_options = get_option( $options );
		$values       = '';
		if ( $tpgb_options ) {
			if ( isset( $tpgb_options[ $field ] ) && ! empty( $tpgb_options[ $field ] ) ) {
				$values = $tpgb_options[ $field ];
			}
		}
		return $values;
	}

	/**
	 * Get default thumb.
	 *
	 * @return mixed The result.
	 */
	public static function get_default_thumb() {
		return TPGB_ASSETS_URL . 'assets/images/tpgb-placeholder.jpg';
	}

	/**-contact form 7 start-*/
	/**
	 * Get contact form post.
	 *
	 * @return mixed The result.
	 */
	public static function get_contact_form_post() {
		$contact_forms = array();
		$cf7           = get_posts( 'post_type="wpcf7_contact_form"&numberposts=-1' );
		if ( $cf7 ) {
			$contact_forms[0] = array( '', 'Select Form', 'the-plus-addons-for-block-editor' );
			foreach ( $cf7 as $cform ) {
				$contact_forms[] = array( $cform->ID, $cform->post_title );
			}
		} else {
			$contact_forms[0] = array( '', 'No contact forms found', 'the-plus-addons-for-block-editor' );
		}
		return $contact_forms;
	}
	/**-contact form 7 end-*/

	/*-everest form start-*/
	/**
	 * Get everest form post.
	 *
	 * @return mixed The result.
	 */
	public static function get_everest_form_post() {
		$everest_form = array();
		$ev_form      = get_posts( 'post_type="everest_form"&numberposts=-1' );
		if ( $ev_form ) {
			$everest_form[0] = array( '', esc_html__( 'Select Form', 'the-plus-addons-for-block-editor' ) );
			foreach ( $ev_form as $evform ) {
				$everest_form[] = array( $evform->ID, $evform->post_title );
			}
		} else {
			$everest_form[0] = array( '', esc_html__( 'No everest forms found', 'the-plus-addons-for-block-editor' ) );
		}
		return $everest_form;
	}
	/**-everest form end-*/

	/*-gravity form start-*/
	/**
	 * Get gravity form post.
	 *
	 * @return mixed The result.
	 */
	public static function get_gravity_form_post() {
		$g_form_options = array();
		if ( class_exists( 'GFCommon' ) ) {
			$gravity_forms      = \RGFormsModel::get_forms( null, 'title' );
			$g_form_options [0] = array( '', esc_html__( 'Select Form', 'the-plus-addons-for-block-editor' ) );
			if ( ! empty( $gravity_forms ) && ! is_wp_error( $gravity_forms ) ) {
				foreach ( $gravity_forms as $form ) {
					$g_form_options[] = array( $form->id, $form->title );
				}
			}
		} else {
			$g_form_options [0] = array( '', esc_html__( 'Form Not Found!', 'the-plus-addons-for-block-editor' ) );
		}
		return $g_form_options;
	}
	/**-gravity form end-*/

	/*-ninja form start-*/
	/**
	 * Get ninja form post.
	 *
	 * @return mixed The result.
	 */
	public static function get_ninja_form_post() {
		$options = array();
		if ( class_exists( 'Ninja_Forms' ) ) {
			$contact_forms = Ninja_Forms()->form()->get_forms();
			if ( ! empty( $contact_forms ) && ! is_wp_error( $contact_forms ) ) {
				$options[0] = array( '', esc_html__( 'Select Ninja Form', 'the-plus-addons-for-block-editor' ) );
				foreach ( $contact_forms as $form ) {
					// $options[ $form->get_id() ] = $form->get_setting( 'title' ); // phpcs:ignore Squiz.PHP.CommentedOutCode.Found
					$options[] = array( $form->get_id(), $form->get_setting( 'title' ) );
				}
			}
		} else {
			$options[0] = array( '', esc_html__( 'Create a Form First', 'the-plus-addons-for-block-editor' ) );
		}
		return $options;
	}
	/**-ninja form end-*/

	/*-wpforms start-*/
	/**
	 * Get wpforms form post.
	 *
	 * @return mixed The result.
	 */
	public static function get_wpforms_form_post() {
		$options = array();
		if ( class_exists( '\WPForms\WPForms' ) ) {
			$args          = array(
				'post_type'      => 'wpforms',
				'posts_per_page' => -1,
			);
			$contact_forms = get_posts( $args );
			if ( ! empty( $contact_forms ) && ! is_wp_error( $contact_forms ) ) {
				$options[0] = array( '', esc_html__( 'Select a WPForm', 'the-plus-addons-for-block-editor' ) );
				foreach ( $contact_forms as $post ) {
					// $options[ $post->ID ] = $post->post_title; // phpcs:ignore Squiz.PHP.CommentedOutCode.Found
					$options[] = array( $post->ID, $post->post_title );
				}
			}
		} else {
			$options[0] = array( '', esc_html__( 'Create a Form First', 'the-plus-addons-for-block-editor' ) );
		}
		return $options;
	}
	/**-wpforms end-*/

	/* Generate HTML of Breadcrumbs */
	/**
	 * Theplus breadcrumbs.
	 *
	 * @param string $icontype The icontype.
	 * @param string $sep_icon_type The sep icon type.
	 * @param string $icons The icons.
	 * @param string $home_title The home title.
	 * @param string $sep_icons The sep icons.
	 * @param string $active_text_default The active text default.
	 * @param string $breadcrumbs_last_sec_tri_normal The breadcrumbs last sec tri normal.
	 * @param string $bd_toggle_home The bd toggle home.
	 * @param string $bd_toggle_parent The bd toggle parent.
	 * @param string $bd_toggle_current The bd toggle current.
	 * @param string $letter_limit_parent The letter limit parent.
	 * @param string $letter_limit_current The letter limit current.
	 * @param bool   $markup_sch The markup sch.
	 * @param array  $ctm_homeurl The ctm homeurl.
	 * @param bool   $show_terms The show terms.
	 * @param string $taxonomy_slug The taxonomy slug.
	 * @param bool   $showpart_terms The showpart terms.
	 * @param bool   $showchild_terms The showchild terms.
	 */
	public static function theplus_breadcrumbs( $icontype = '', $sep_icon_type = '', $icons = '', $home_title = '', $sep_icons = '', $active_text_default = '', $breadcrumbs_last_sec_tri_normal = '', $bd_toggle_home = '', $bd_toggle_parent = '', $bd_toggle_current = '', $letter_limit_parent = '', $letter_limit_current = '', $markup_sch = false, $ctm_homeurl = array(), $show_terms = false, $taxonomy_slug = '', $showpart_terms = true, $showchild_terms = true ) {

		// Normalise toggle flags — block attributes can arrive as bool, string ("true"/"false"/"1"/"0"), or int.
		$bd_toggle_home    = ! empty( $bd_toggle_home ) && filter_var( $bd_toggle_home, FILTER_VALIDATE_BOOLEAN );
		$bd_toggle_parent  = ! empty( $bd_toggle_parent ) && filter_var( $bd_toggle_parent, FILTER_VALIDATE_BOOLEAN );
		$bd_toggle_current = ! empty( $bd_toggle_current ) && filter_var( $bd_toggle_current, FILTER_VALIDATE_BOOLEAN );
		$show_terms        = ! empty( $show_terms ) && filter_var( $show_terms, FILTER_VALIDATE_BOOLEAN );
		$showpart_terms    = ! empty( $showpart_terms ) && filter_var( $showpart_terms, FILTER_VALIDATE_BOOLEAN );
		$showchild_terms   = ! empty( $showchild_terms ) && filter_var( $showchild_terms, FILTER_VALIDATE_BOOLEAN );

		if ( '' !== $home_title ) {
			$text['home'] = $home_title;
		} else {
			$text['home'] = 'Home';
		}
		/* translators: Archive by: %s */
		$text['category'] = esc_html__( 'Archive by "%s"', 'the-plus-addons-for-block-editor' );
		/* translators: Search Results for: %s */
		$text['search'] = esc_html__( 'Search Results for "%s"', 'the-plus-addons-for-block-editor' );
		/* translators: Posts Tagged for: %s */
		$text['tag'] = esc_html__( 'Posts Tagged "%s"', 'the-plus-addons-for-block-editor' );
		/* translators: Articles Posted by for: %s */
		$text['author'] = esc_html__( 'Articles Posted by %s', 'the-plus-addons-for-block-editor' );
		/* translators: Error 404: %s */
		$text['404']  = esc_html__( 'Error 404', 'the-plus-addons-for-block-editor' );
		$show_current = 1;
		$show_on_home = 1;
		$delimiter    = ' <span class="del"></span> ';

		$schema_arr = array(
			'@context'        => 'https://schema.org',
			'@type'           => 'BreadcrumbList',
			'itemListElement' => array(),
		);
		$breadposi  = 0;

		if ( $bd_toggle_current ) {
			if ( '' !== $breadcrumbs_last_sec_tri_normal ) {
				if ( '' !== $active_text_default ) {
					$before = '<span class="current_active normal"><div class="current_tab_sec">';
				} else {
					$before = '<span class="current normal"><div class="current_tab_sec">';
				}
			} elseif ( '' !== $active_text_default ) {
				$before = '<span class="current_active"><div class="current_tab_sec">';
			} else {
				$before = '<span class="current"><div class="current_tab_sec">';
			}
		} elseif ( '' !== $breadcrumbs_last_sec_tri_normal ) {
			if ( '' !== $active_text_default ) {
				$before = '<span class="current_active normal on-off-current"><div class="current_tab_sec">';
			} else {
				$before = '<span class="current normal on-off-current"><div class="current_tab_sec">';
			}
		} elseif ( '' !== $active_text_default ) {
			$before = '<span class="current_active on-off-current"><div class="current_tab_sec">';
		} else {
			$before = '<span class="current on-off-current"><div class="current_tab_sec">';
		}

		$after = '</div></span>';

		$icons_content = '';
		if ( 'icon' === $icontype && '' !== $icons ) {
			$icons_content = '<i class=" ' . esc_attr( $icons ) . ' bread-home-icon" aria-hidden="true"></i>';
		}
		if ( 'image' === $icontype && '' !== $icons ) {
			$icons_content = '<img class="bread-home-img" alt="' . esc_attr__( 'home', 'the-plus-addons-for-block-editor' ) . '" src="' . esc_url( $icons ) . '" />';
		}

		$icons_sep_content = '';
		if ( 'sep_icon' === $sep_icon_type && '' !== $sep_icons ) {
			$icons_sep_content = '<i class=" ' . esc_attr( $sep_icons ) . ' bread-sep-icon" aria-hidden="true"></i>';
		}
		if ( 'sep_image' === $sep_icon_type && '' !== $sep_icons ) {
			$icons_sep_content = '<img class="bread-sep-icon" alt="' . esc_attr__( 'separator', 'the-plus-addons-for-block-editor' ) . '" src="' . esc_url( $sep_icons ) . '" />';
		}

		global $post;

		// Set Dynamic URL For Home Link.
		if ( class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) {
			$home_url = ( isset( $ctm_homeurl['dynamic'] ) ) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_repeat_url( $ctm_homeurl ) : ( ! empty( $ctm_homeurl['url'] ) ? $ctm_homeurl['url'] : home_url() . '/' );
		} else {
			$home_url = ( ! empty( $ctm_homeurl ) && ! empty( $ctm_homeurl['url'] ) ) ? $ctm_homeurl['url'] : home_url() . '/';
		}

		$link_after = '</span>';

		// Home crumb: sep icon lives INSIDE the anchor after label text.
		$home_template  = '';
		$home_delimiter = '';
		if ( '' !== $icons_content || '' !== $icons_sep_content || '' !== $text['home'] ) {
			if ( $bd_toggle_home ) {
				$link_attr = self::add_link_attributes( $ctm_homeurl );
				if ( ! empty( $ctm_homeurl ) && ! empty( $ctm_homeurl['target'] ) ) {
					$link_attr .= ' target="_blank"';
				}
				if ( ! empty( $ctm_homeurl ) && ! empty( $ctm_homeurl['nofollow'] ) ) {
					$link_attr .= ' rel="nofollow" ';
				}
				$home_template = '<span class="bc_home"><a class="home_bread_tab" href="%1$s" ' . $link_attr . '>' . $icons_content . '%2$s' . $icons_sep_content . '</a></span>';
			}
			$home_delimiter = ( '' !== $home_template ) ? ' <span class="del"></span> ' : '';
		}

		// Parent link template: sep icon INSIDE anchor after label text — always built with sep icon.
		// When $bd_toggle_current is false, the sep icon on the LAST parent is stripped by post-processing below.
		if ( $bd_toggle_parent ) {
			$link = '<span class="bc_parent"><a class="parent_sub_bread_tab" href="%1$s">%2$s' . $icons_sep_content . '</a></span>';
		} else {
			$link = '';
		}

		$crumbs_output = '';

		if ( is_home() || is_front_page() ) {
			if ( 1 === $show_on_home ) {
				$crumbs_output = '<nav id="breadcrumbs"><a href="' . esc_url( home_url() ) . '">' . $icons_content . esc_html( $text['home'] ) . '</a></nav>';
			}
			$schema_arr['itemListElement'][] = array(
				'@type'    => 'ListItem',
				'position' => ++$breadposi,
				'name'     => $text['home'],
				'item'     => esc_url( home_url() ),
			);
		} else {
			$crumbs_output = '<nav id="breadcrumbs">';
			if ( '' !== $home_template ) {
				$crumbs_output .= sprintf( $home_template, esc_url( $home_url ), esc_html( $text['home'] ) ) . $home_delimiter;
			}

			if ( is_category() ) {
				$this_cat = get_category( get_query_var( 'cat' ), false );
				if ( 0 !== $this_cat->parent ) {
					$cats                            = get_category_parents( $this_cat->parent, true, $delimiter );
					$schema_arr['itemListElement'][] = array(
						'@type'    => 'ListItem',
						'position' => ++$breadposi,
						'name'     => $text['category'],
						'item'     => get_category_link( $this_cat->term_id ),
					);
					$cats                            = str_replace( '<a', '<span class="bc_parent"><a class="parent_sub_bread_tab"', $cats );
					$cats                            = str_replace( '<span class="del"></span>', $delimiter, $cats );
					$cats                            = str_replace( '</a>', $icons_sep_content . '</a></span>', $cats );
					$crumbs_output                  .= $cats;
				} else {
					$schema_arr['itemListElement'][] = array(
						'@type'    => 'ListItem',
						'position' => ++$breadposi,
						'name'     => $text['category'],
						'item'     => get_category_link( $this_cat->term_id ),
					);
				}
				$crumbs_output .= $before . sprintf( $text['category'], esc_html( single_cat_title( '', false ) ) ) . $after;

			} elseif ( is_search() ) {
				$searchdata = get_search_query();
				if ( '0' !== $letter_limit_current ) {
					$searchdata = mb_substr( $searchdata, 0, intval( $letter_limit_current ) );
				}
				$crumbs_output                  .= $before . sprintf( $text['search'], esc_html( $searchdata ) ) . $after;
				$schema_arr['itemListElement'][] = array(
					'@type'    => 'ListItem',
					'position' => ++$breadposi,
					'name'     => $text['search'],
					'item'     => site_url() . '/' . get_search_query(),
				);

			} elseif ( is_singular( 'topic' ) ) {
				$post_type                       = get_post_type_object( get_post_type() );
				$crumbs_output                  .= sprintf( $link, esc_url( $home_url . '/forums/' ), esc_html( $post_type->labels->singular_name ) ) . $delimiter;
				$schema_arr['itemListElement'][] = array(
					'@type'    => 'ListItem',
					'position' => ++$breadposi,
					'name'     => $post_type->labels->singular_name,
					'item'     => $home_url . '/forums/',
				);
			} // phpcs:ignore Squiz.ControlStructures.ControlSignature.SpaceAfterCloseBrace
			/* in forum, add link to support forum page template */
			elseif ( is_singular( 'forum' ) ) {
				$post_type                       = get_post_type_object( get_post_type() );
				$crumbs_output                  .= sprintf( $link, esc_url( $home_url . '/forums/' ), esc_html( $post_type->labels->singular_name ) ) . $delimiter;
				$schema_arr['itemListElement'][] = array(
					'@type'    => 'ListItem',
					'position' => ++$breadposi,
					'name'     => $post_type->labels->singular_name,
					'item'     => $home_url . '/forums/',
				);

			} elseif ( is_tax( 'topic-tag' ) ) {
				$post_type                       = get_post_type_object( get_post_type() );
				$crumbs_output                  .= sprintf( $link, esc_url( $home_url . '/forums/' ), esc_html( $post_type->labels->singular_name ) ) . $delimiter;
				$schema_arr['itemListElement'][] = array(
					'@type'    => 'ListItem',
					'position' => ++$breadposi,
					'name'     => $post_type->labels->singular_name,
					'item'     => $home_url . '/forums/',
				);

			} elseif ( is_day() ) {
				$crumbs_output                  .= sprintf( $link, esc_url( get_year_link( get_the_time( 'Y' ) ) ), esc_html( get_the_time( 'Y' ) ) ) . $delimiter;
				$crumbs_output                  .= sprintf( $link, esc_url( get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ) ), esc_html( get_the_time( 'F' ) ) ) . $delimiter;
				$crumbs_output                  .= $before . esc_html( get_the_time( 'd' ) ) . $after;
				$schema_arr['itemListElement'][] = array(
					'@type'    => 'ListItem',
					'position' => ++$breadposi,
					'name'     => get_the_time( 'd' ),
					'item'     => get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ),
				);

			} elseif ( is_month() ) {
				$crumbs_output                  .= sprintf( $link, esc_url( get_year_link( get_the_time( 'Y' ) ) ), esc_html( get_the_time( 'Y' ) ) ) . $delimiter;
				$crumbs_output                  .= $before . esc_html( get_the_time( 'F' ) ) . $after;
				$schema_arr['itemListElement'][] = array(
					'@type'    => 'ListItem',
					'position' => ++$breadposi,
					'name'     => get_the_time( 'F' ),
					'item'     => get_year_link( get_the_time( 'Y' ) ),
				);

			} elseif ( is_year() ) {
				$crumbs_output                  .= $before . esc_html( get_the_time( 'Y' ) ) . $after;
				$schema_arr['itemListElement'][] = array(
					'@type'    => 'ListItem',
					'position' => ++$breadposi,
					'name'     => get_the_time( 'Y' ),
				);

			} elseif ( is_single() && ! is_attachment() ) {

				if ( 'product' === get_post_type( $post ) ) {

					$terms_cate = wc_get_product_terms(
						$post->ID,
						'product_cat',
						apply_filters(
							'woocommerce_breadcrumb_product_terms_args',
							array(
								'orderby' => 'parent',
								'order'   => 'DESC',
							)
						)
					);

					if ( $terms_cate ) {
						$first_term = apply_filters( 'woocommerce_breadcrumb_main_term', $terms_cate[0], $terms_cate );
						$ancestors  = get_ancestors( $first_term->term_id, 'product_cat' );
						$ancestors  = array_reverse( $ancestors );

						foreach ( $ancestors as $ancestor ) {
							$ancestor = get_term( $ancestor, 'product_cat' );
							if ( ! is_wp_error( $ancestor ) && $ancestor ) {
								$crumbs_output                  .= sprintf( $link, esc_url( get_term_link( $ancestor ) ), esc_html( $ancestor->name ) ) . $delimiter;
								$schema_arr['itemListElement'][] = array(
									'@type'    => 'ListItem',
									'position' => ++$breadposi,
									'name'     => $ancestor->name,
									'item'     => get_term_link( $ancestor ),
								);
							}
						}

						$crumbs_output                  .= sprintf( $link, esc_url( get_term_link( $first_term ) ), esc_html( $first_term->name ) ) . $delimiter;
						$schema_arr['itemListElement'][] = array(
							'@type'    => 'ListItem',
							'position' => ++$breadposi,
							'name'     => $first_term->name,
							'item'     => get_term_link( $first_term ),
						);
					}

					if ( 1 === $show_current ) {
						$crumbs_output .= $delimiter;
						if ( '0' !== $letter_limit_current ) {
							$crumbs_output .= $before . esc_html( mb_substr( get_the_title(), 0, intval( $letter_limit_current ) ) ) . $after;
						} else {
							$crumbs_output .= $before . esc_html( get_the_title() ) . $after;
						}
					}
				} elseif ( get_post_type() !== 'post' ) {

					$post_type = get_post_type_object( get_post_type() );
					$slug      = $post_type->rewrite;

					if ( $bd_toggle_parent ) {
						$crumbs_output .= '<span class="bc_parent"><a class="parent_sub_bread_tab" href="' . esc_url( $home_url ) . '?post_type=' . esc_attr( $slug['slug'] ) . '">' . esc_html( $post_type->labels->singular_name ) . $icons_sep_content . '</a></span>' . $delimiter;
					}

					// Single Page Category Breadcumb.
					if ( $show_terms ) {
						$terms = get_the_terms( $post->ID, $taxonomy_slug );

						if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
							$parent_term = null;
							foreach ( $terms as $term ) {
								if ( 0 === $term->parent ) {
									$parent_term = $term;
									if ( $showpart_terms ) {
										$crumbs_output                  .= '<span class="bc_parent"><a class="parent_sub_bread_tab" href="' . esc_url( get_term_link( $term->term_id, $taxonomy_slug ) ) . '">' . esc_html( $term->name ) . $icons_sep_content . '</a></span>' . $delimiter;
										$schema_arr['itemListElement'][] = array(
											'@type'    => 'ListItem',
											'position' => ++$breadposi,
											'name'     => $term->name,
											'item'     => esc_url( get_term_link( $term->term_id, $taxonomy_slug ) ),
										);
										break;
									}
								}
							}
							foreach ( $terms as $term ) {
								if ( ! empty( $parent_term ) && $term->parent === $parent_term->term_id ) {
									if ( $showchild_terms ) {
										$crumbs_output                  .= '<span class="bc_parent"><a class="parent_sub_bread_tab" href="' . esc_url( get_term_link( $term->term_id, $taxonomy_slug ) ) . '">' . esc_html( $term->name ) . $icons_sep_content . '</a></span>' . $delimiter;
										$schema_arr['itemListElement'][] = array(
											'@type'    => 'ListItem',
											'position' => ++$breadposi,
											'name'     => $term->name,
											'item'     => esc_url( get_term_link( $term->term_id, $taxonomy_slug ) ),
										);
										break;
									}
								} elseif ( 0 !== $term->parent ) {
									if ( $showpart_terms ) {
										$crumbs_output                  .= '<span class="bc_parent"><a class="parent_sub_bread_tab" href="' . esc_url( get_term_link( $term->term_id, $taxonomy_slug ) ) . '">' . esc_html( $term->name ) . $icons_sep_content . '</a></span>' . $delimiter;
										$schema_arr['itemListElement'][] = array(
											'@type'    => 'ListItem',
											'position' => ++$breadposi,
											'name'     => $term->name,
											'item'     => esc_url( get_term_link( $term->term_id, $taxonomy_slug ) ),
										);
									}
								}
							}
						}
					}

					if ( 1 === $show_current ) {
						if ( '0' !== $letter_limit_current ) {
							$crumbs_output .= $before . esc_html( mb_substr( get_the_title(), 0, intval( $letter_limit_current ) ) ) . $after;
						} else {
							$crumbs_output .= $before . esc_html( get_the_title() ) . $after;
						}
					}
					$schema_arr['itemListElement'][] = array(
						'@type'    => 'ListItem',
						'position' => ++$breadposi,
						'name'     => $post_type->labels->singular_name,
						'item'     => $home_url . '?post_type=' . esc_attr( $slug['slug'] ),
					);
				} else {

					$cat = get_the_category();
					if ( isset( $cat[0] ) ) {
						$cat  = $cat[0];
						$cats = get_category_parents( $cat, true, $delimiter );
						if ( 0 === $show_current ) {
							$cats = preg_replace( "#^(.+)$delimiter$#", '$1', $cats );
						}
						if ( $bd_toggle_parent ) {
							$cats                            = str_replace( '<a', '<span class="bc_parent"><a class="parent_sub_bread_tab"', $cats );
							$cats                            = str_replace( '</a>', $icons_sep_content . '</a></span>', $cats );
							$crumbs_output                  .= $cats;
							$schema_arr['itemListElement'][] = array(
								'@type'    => 'ListItem',
								'position' => ++$breadposi,
								'name'     => $cat->name,
								'item'     => get_category_link( $cat->term_id ),
							);
						}

						// Show Child Parent Category (custom taxonomy terms).
						if ( $show_terms ) {
							$terms = get_the_terms( $post->ID, $taxonomy_slug );

							if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
								$parent_term = null;
								foreach ( $terms as $term ) {
									if ( 0 === $term->parent ) {
										$parent_term = $term;
										if ( $showpart_terms ) {
											$crumbs_output                  .= $delimiter . '<span class="bc_parent"><a class="parent_sub_bread_tab" href="' . esc_url( get_term_link( $term->term_id, $taxonomy_slug ) ) . '">' . esc_html( $term->name ) . $icons_sep_content . '</a></span>';
											$schema_arr['itemListElement'][] = array(
												'@type'    => 'ListItem',
												'position' => ++$breadposi,
												'name'     => $term->name,
												'item'     => esc_url( get_term_link( $term->term_id, $taxonomy_slug ) ),
											);
										}
										break;
									}
								}
								foreach ( $terms as $term ) {
									if ( ! empty( $parent_term ) && $term->parent === $parent_term->term_id ) {
										if ( $showchild_terms ) {
											$crumbs_output                  .= $delimiter . '<span class="bc_parent"><a class="parent_sub_bread_tab" href="' . esc_url( get_term_link( $term->term_id, $taxonomy_slug ) ) . '">' . esc_html( $term->name ) . $icons_sep_content . '</a></span>';
											$schema_arr['itemListElement'][] = array(
												'@type'    => 'ListItem',
												'position' => ++$breadposi,
												'name'     => $term->name,
												'item'     => esc_url( get_term_link( $term->term_id, $taxonomy_slug ) ),
											);
											break;
										}
									} elseif ( 0 !== $term->parent ) {
										if ( $showpart_terms ) {
											$crumbs_output                  .= $delimiter . '<span class="bc_parent"><a class="parent_sub_bread_tab" href="' . esc_url( get_term_link( $term->term_id, $taxonomy_slug ) ) . '">' . esc_html( $term->name ) . $icons_sep_content . '</a></span>';
											$schema_arr['itemListElement'][] = array(
												'@type'    => 'ListItem',
												'position' => ++$breadposi,
												'name'     => $term->name,
												'item'     => esc_url( get_term_link( $term->term_id, $taxonomy_slug ) ),
											);
										}
									}
								}
							}
						}

						if ( 1 === $show_current ) {
							$crumbs_output .= $delimiter;
							if ( '0' !== $letter_limit_current ) {
								$crumbs_output .= $before . esc_html( mb_substr( get_the_title(), 0, intval( $letter_limit_current ) ) ) . $after;
							} else {
								$crumbs_output .= $before . esc_html( get_the_title() ) . $after;
							}
						}
						$schema_arr['itemListElement'][] = array(
							'@type'    => 'ListItem',
							'position' => ++$breadposi,
							'name'     => get_the_title(),
							'item'     => get_the_permalink(),
						);
					}
				}
			} elseif ( class_exists( 'WooCommerce' ) && is_product_category() ) {

				$current_term = $GLOBALS['wp_query']->get_queried_object();
				$permalinks   = wc_get_permalink_structure();
				$shop_page_id = wc_get_page_id( 'shop' );
				$shop_page    = get_post( $shop_page_id );

				if ( $shop_page_id && $shop_page && isset( $permalinks['product_base'] ) && strstr( $permalinks['product_base'], '/' . $shop_page->post_name ) && intval( get_option( 'page_on_front' ) ) !== $shop_page_id ) {
					$crumbs_output .= sprintf( $link, esc_url( get_permalink( $shop_page ) ), esc_html( get_the_title( $shop_page ) ) ) . $delimiter;
				}

				if ( $bd_toggle_parent ) {
					$ancestors = get_ancestors( $current_term->term_id, 'product_cat' );
					$ancestors = array_reverse( $ancestors );
					foreach ( $ancestors as $ancestor ) {
						$ancestor = get_term( $ancestor, 'product_cat' );
						if ( ! is_wp_error( $ancestor ) && $ancestor ) {
							$crumbs_output .= sprintf( $link, esc_url( get_term_link( $ancestor ) ), esc_html( $ancestor->name ) ) . $delimiter;
						}
					}
				}

				if ( $current_term && $bd_toggle_current ) {
					$crumbs_output .= '<span class="current_active normal"><div class="current_tab_sec">' . esc_html( $current_term->name ) . '</div></span>';
				}
			} elseif ( class_exists( 'WooCommerce' ) && is_product_tag() ) {

				$current_term = $GLOBALS['wp_query']->get_queried_object();
				$permalinks   = wc_get_permalink_structure();
				$shop_page_id = wc_get_page_id( 'shop' );
				$shop_page    = get_post( $shop_page_id );

				if ( $shop_page_id && $shop_page && isset( $permalinks['product_base'] ) && strstr( $permalinks['product_base'], '/' . $shop_page->post_name ) && intval( get_option( 'page_on_front' ) ) !== $shop_page_id ) {
					$crumbs_output .= sprintf( $link, esc_url( get_permalink( $shop_page ) ), esc_html( get_the_title( $shop_page ) ) ) . $delimiter;
				}

				if ( $current_term && $bd_toggle_current ) {
					$crumbs_output .= '<span class="current_active normal"><div class="current_tab_sec">' . esc_html( $current_term->name ) . '</div></span>';
				}
			} elseif ( class_exists( 'WooCommerce' ) && is_shop() ) {

				if ( intval( get_option( 'page_on_front' ) ) === wc_get_page_id( 'shop' ) ) {
					return;
				}

				$_name = wc_get_page_id( 'shop' ) ? get_the_title( wc_get_page_id( 'shop' ) ) : '';
				if ( ! $_name ) {
					$product_post_type = get_post_type_object( 'product' );
					$_name             = $product_post_type->labels->name;
				}

				if ( $bd_toggle_current ) {
					$crumbs_output .= '<span class="current_active normal"><div class="current_tab_sec">' . esc_html( $_name ) . '</div></span>';
				}
			} elseif ( ! is_single() && ! is_page() && get_post_type() !== 'post' && ! is_404() ) {

				$post_type = get_post_type_object( get_post_type() );
				if ( ! empty( $post_type ) && isset( $post_type->labels ) && isset( $post_type->labels->singular_name ) ) {
					$crumbs_output .= $before . esc_html( $post_type->labels->singular_name ) . $after;
				}
			} elseif ( is_attachment() ) {

				$parent = get_post( $post->post_parent );
				$cat    = get_the_category( $parent->ID );
				if ( $cat ) {
					$cat_obj        = $cat[0];
					$cats           = get_category_parents( $cat_obj, true, $delimiter );
					$cats           = str_replace( '</a>', $icons_sep_content . '</a></span>', $cats );
					$cats           = str_replace( '<a', '<span class="bc_parent"><a class="parent_sub_bread_tab"', $cats );
					$cats           = str_replace( '</a>', $icons_sep_content . '</a></span>', $cats );
					$crumbs_output .= $cats . $delimiter;

					$schema_arr['itemListElement'][] = array(
						'@type'    => 'ListItem',
						'position' => ++$breadposi,
						'name'     => $cat_obj->name,
						'item'     => get_category_link( $cat_obj->term_id ),
					);

					if ( '0' !== $letter_limit_parent ) {
						$crumbs_output .= sprintf( $link, esc_url( get_permalink( $parent ) ), esc_html( mb_substr( $parent->post_title, 0, intval( $letter_limit_parent ) ) ) ) . $delimiter;
					} else {
						$crumbs_output .= sprintf( $link, esc_url( get_permalink( $parent ) ), esc_html( $parent->post_title ) ) . $delimiter;
					}

					if ( 1 === $show_current ) {
						if ( '0' !== $letter_limit_current ) {
							$crumbs_output .= $before . esc_html( mb_substr( get_the_title(), 0, intval( $letter_limit_current ) ) ) . $after;
						} else {
							$crumbs_output .= $before . esc_html( get_the_title() ) . $after;
						}
					}
				}
			} elseif ( is_page() && ! $post->post_parent ) {

				if ( 1 === $show_current ) {
					if ( '0' !== $letter_limit_current ) {
						$crumbs_output .= $before . esc_html( mb_substr( get_the_title(), 0, intval( $letter_limit_current ) ) ) . $after;
					} else {
						$crumbs_output .= $before . esc_html( get_the_title() ) . $after;
					}
				}
				$schema_arr['itemListElement'][] = array(
					'@type'    => 'ListItem',
					'position' => ++$breadposi,
					'name'     => get_the_title(),
					'item'     => '',
				);
			} elseif ( is_page() && $post->post_parent ) {

				$parent_id   = $post->post_parent;
				$breadcrumbs = array();
				$posi        = ++$breadposi;
				while ( $parent_id ) {
					++$posi;
					$page = get_page( $parent_id );
					if ( '0' !== $letter_limit_parent ) {
						$breadcrumbs[] = sprintf( $link, esc_url( get_permalink( $page->ID ) ), esc_html( mb_substr( get_the_title( $page->ID ), 0, intval( $letter_limit_parent ) ) ) );
					} else {
						$breadcrumbs[] = sprintf( $link, esc_url( get_permalink( $page->ID ) ), esc_html( get_the_title( $page->ID ) ) );
					}
					$schema_arr['itemListElement'][] = array(
						'@type'    => 'ListItem',
						'position' => $posi,
						'name'     => get_the_title( $page->ID ),
						'item'     => get_permalink( $page->ID ),
					);
					$parent_id                       = $page->post_parent;
				}
				$breadposi         = $posi;
				$breadcrumbs       = array_reverse( $breadcrumbs );
				$breadcrumbs_count = count( $breadcrumbs );
				for ( $i = 0; $i < $breadcrumbs_count; $i++ ) {
					$crumbs_output .= $breadcrumbs[ $i ] . $delimiter;
				}
				if ( 1 === $show_current ) {
					if ( '0' !== $letter_limit_current ) {
						$crumbs_output .= $before . esc_html( mb_substr( get_the_title(), 0, intval( $letter_limit_current ) ) ) . $after;
					} else {
						$crumbs_output .= $before . esc_html( get_the_title() ) . $after;
					}
					$schema_arr['itemListElement'][] = array(
						'@type'    => 'ListItem',
						'position' => ++$breadposi,
						'name'     => get_the_title(),
						'item'     => get_permalink(),
					);
				}
			} elseif ( is_tag() ) {

				$crumbs_output                  .= $before . sprintf( $text['tag'], esc_html( single_tag_title( '', false ) ) ) . $after;
				$schema_arr['itemListElement'][] = array(
					'@type'    => 'ListItem',
					'position' => ++$breadposi,
					'name'     => $text['tag'],
					'item'     => get_permalink(),
				);
			} elseif ( is_author() ) {

				global $author;
				$userdata                        = get_userdata( $author );
				$crumbs_output                  .= $before . sprintf( $text['author'], esc_html( $userdata->display_name ) ) . $after;
				$schema_arr['itemListElement'][] = array(
					'@type'    => 'ListItem',
					'position' => ++$breadposi,
					'name'     => $text['author'],
					'item'     => $userdata->user_url,
				);
			} elseif ( is_404() ) {

				$crumbs_output                  .= $before . $text['404'] . $after;
				$schema_arr['itemListElement'][] = array(
					'@type'    => 'ListItem',
					'position' => ++$breadposi,
					'name'     => $text['404'],
				);
			}

			if ( get_query_var( 'paged' ) ) {
				if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) {
					$crumbs_output .= ' (';
				}
				$crumbs_output .= '<span class="del"></span>' . esc_html__( 'Page', 'the-plus-addons-for-block-editor' ) . ' ' . intval( get_query_var( 'paged' ) );
				if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) {
					$crumbs_output .= ')';
				}
			}

			// When current crumb is hidden: strip sep icon from the LAST parent anchor
			// and strip the trailing delimiter — single post-process covers every branch.
			if ( ! $bd_toggle_current && '' !== $icons_sep_content ) {
				// 1. Remove sep icon from the last parent anchor.
				$pos = strrpos( $crumbs_output, $icons_sep_content . '</a></span>' );
				if ( false !== $pos ) {
					$crumbs_output = substr_replace( $crumbs_output, '</a></span>', $pos, strlen( $icons_sep_content . '</a></span>' ) );
				}
				// 2. Also check the home anchor in case it is the only crumb.
				$pos_home = strrpos( $crumbs_output, $icons_sep_content . '</a></span>' );
				if ( false === $pos_home ) {
					$pos_home = strrpos( $crumbs_output, $icons_sep_content . '</a></span>' );
				}
			}

			// Strip trailing delimiter before </nav>.
			if ( ! $bd_toggle_current ) {
				$del_needle = '<span class="del"></span>';
				$trimmed    = rtrim( $crumbs_output );
				if ( substr( $trimmed, -strlen( $del_needle ) ) === $del_needle ) {
					$crumbs_output = rtrim( substr( $trimmed, 0, -strlen( $del_needle ) ) );
				}
			}

			$crumbs_output .= '</nav>';
		}

		if ( ! empty( $markup_sch ) ) {
			$encoded_data   = wp_json_encode( $schema_arr );
			$crumbs_output .= '<script type="application/ld+json">' . $encoded_data . '</script>';
		}

		return $crumbs_output;
	}

	/*
	Get Taxonomie  Slug
	 * @since 1.1.0
	 */
	public static function tpgb_get_post_taxonomies() { // phpcs:ignore Squiz.Commenting.FunctionComment
		$args       = array(
			'public'  => true,
			'show_ui' => true,
		);
		$output     = 'objects'; // or objects.
		$operator   = 'and'; // 'and' or 'or' // phpcs:ignore Squiz.PHP.CommentedOutCode.Found
		$cat_list   = array();
		$cat_list[] = array( '', 'None' );
		$taxonomies = get_taxonomies( $args, $output, $operator );
		if ( $taxonomies ) {

			foreach ( $taxonomies  as $taxonomy ) {
				$exclude = array( 'nxt_builder_category' );
				if ( true === in_array( $taxonomy->name, $exclude, true ) ) {
					continue;
				}

				$cat_list[] = array( $taxonomy->name, $taxonomy->label );

			}
		}
		return $cat_list;
	}

	/**
	 * Get Common Classes Block Options
	 *
	 * @since 1.1.1
	 * @param mixed $attr The attr.
	 */
	public static function block_wrapper_classes( $attr ) {
		$class_name              = ( ! empty( $attr['className'] ) ) ? $attr['className'] : '';
		$align                   = ( ! empty( $attr['align'] ) ) ? $attr['align'] : '';
		$save_global_style_class = ( isset( $attr['saveGlobalStyleClass'] ) && ! empty( $attr['saveGlobalStyleClass'] ) ) ? $attr['saveGlobalStyleClass'] : '';

		$block_class = '';
		if ( ! empty( $class_name ) ) {
			$block_class .= $class_name;
		}

		if ( ! empty( $save_global_style_class ) ) {
			$block_class .= ' tpgb-block-' . $save_global_style_class;
		}

		if ( isset( $attr['contwidFull'] ) && ! empty( $attr['contwidFull'] ) && 'full' === $attr['contwidFull'] ) {
			$block_class .= ' alignfull';
		} elseif ( ! empty( $align ) ) {
			$block_class .= ' align' . $align;
		}

		return $block_class;
	}

	/**
	 * Get Carousel Settings Block Options
	 *
	 * @since 1.1.2
	 * @param mixed $attr The attr.
	 */
	public static function carousel_settings( $attr ) {
		$cenpadding = isset( $attr['centerPadding'] ) ? (array) $attr['centerPadding'] : '';

		$settings = array(
			'updateOnMove'      => true,
			'direction'         => isset( $attr['sliderMode'] ) && 'vertical' === $attr['sliderMode'] ? 'ttb' : ( is_rtl() ? 'rtl' : 'ltr' ),
			'start'             => isset( $attr['initialSlide'] ) ? $attr['initialSlide'] : 0,
			'autoplay'          => isset( $attr['slideAutoplay'] ) ? $attr['slideAutoplay'] : false,
			'speed'             => isset( $attr['slideSpeed'] ) ? (int) $attr['slideSpeed'] : 1500,
			'interval'          => isset( $attr['slideAutoplaySpeed'] ) ? (int) $attr['slideAutoplaySpeed'] : '',
			'drag'              => isset( $attr['slideDraggable']['md'] ) ? $attr['slideDraggable']['md'] : false,
			'type'              => ! empty( $attr['slideInfinite'] ) ? 'loop' : ( isset( $attr['carType'] ) && ! empty( $attr['carType'] ) ? $attr['carType'] : 'slide' ),
			'pauseOnHover'      => isset( $attr['slideHoverPause'] ) ? $attr['slideHoverPause'] : false,
			'pagination'        => isset( $attr['showDots']['md'] ) ? $attr['showDots']['md'] : false,
			'arrows'            => ( ! empty( $attr['showArrows']['md'] ) || ! empty( $attr['showArrows']['sm'] ) || ! empty( $attr['showArrows']['xs'] ) ) ? true : false,
			'padding'           => isset( $cenpadding['md'] ) ? (int) $cenpadding['md'] : '',
			'perMove'           => isset( $attr['slideScroll']['md'] ) ? (int) $attr['slideScroll']['md'] : 1,
			'perPage'           => isset( $attr['slideColumns']['md'] ) ? (int) $attr['slideColumns']['md'] : 1,
			'wheel'             => isset( $attr['slidewheel'] ) ? $attr['slidewheel'] : false,
			'releaseWheel'      => isset( $attr['slidewheel'] ) ? $attr['slidewheel'] : false,
			'waitForTransition' => isset( $attr['waitfortras'] ) ? $attr['waitfortras'] : false,
			'keyboard'          => ( isset( $attr['slidekeyNav'] ) && ! empty( $attr['slidekeyNav'] ) ) ? 'global' : false,
			'breakpoints'       => array(
				'1024' => array(
					'pagination' => ( ! isset( $attr['showDots']['sm'] ) ) ? $attr['showDots']['md'] : ( isset( $attr['showDots']['sm'] ) ? $attr['showDots']['sm'] : false ),
					'drag'       => ( ! isset( $attr['slideDraggable']['sm'] ) ) ? $attr['slideDraggable']['md'] : ( isset( $attr['slideDraggable']['sm'] ) ? $attr['slideDraggable']['sm'] : false ),
					'padding'    => ( ! isset( $cenpadding['sm'] ) ) ? ( isset( $cenpadding['md'] ) ? (int) $cenpadding['md'] : '' ) : ( isset( $cenpadding['sm'] ) ? $cenpadding['sm'] : '' ),
					'perMove'    => ( ! isset( $attr['slideScroll']['sm'] ) ) ? (int) $attr['slideScroll']['md'] : ( isset( $attr['slideScroll']['sm'] ) ? (int) $attr['slideScroll']['sm'] : 1 ),
					'perPage'    => ( ! isset( $attr['slideColumns']['sm'] ) ) ? $attr['slideColumns']['md'] : ( isset( $attr['slideColumns']['sm'] ) ? $attr['slideColumns']['sm'] : 1 ),
				),
				'767'  => array(
					'pagination' => ( ! isset( $attr['showDots']['xs'] ) ) ? ( ( ! isset( $attr['showDots']['sm'] ) ) ? $attr['showDots']['md'] : $attr['showDots']['sm'] ) : ( isset( $attr['showDots']['xs'] ) ? $attr['showDots']['xs'] : false ),
					'drag'       => ( ! isset( $attr['slideDraggable']['xs'] ) ) ? ( ( ! isset( $attr['slideDraggable']['sm'] ) ) ? $attr['slideDraggable']['md'] : $attr['slideDraggable']['sm'] ) : ( isset( $attr['slideDraggable']['xs'] ) ? $attr['slideDraggable']['xs'] : false ),
					'padding'    => ( ! isset( $cenpadding['xs'] ) ) ? ( ( ! isset( $cenpadding['sm'] ) ) ? ( isset( $cenpadding['md'] ) ? (int) $cenpadding['md'] : '' ) : $cenpadding['sm'] ) : ( isset( $cenpadding['xs'] ) ? $cenpadding['xs'] : '' ),
					'perMove'    => ( ! isset( $attr['slideScroll']['xs'] ) ) ? ( ( ! isset( $attr['slideScroll']['sm'] ) ) ? (int) $attr['slideScroll']['md'] : (int) $attr['slideScroll']['sm'] ) : ( isset( $attr['slideScroll']['xs'] ) ? (int) $attr['slideScroll']['xs'] : 1 ),
					'perPage'    => ( ! isset( $attr['slideColumns']['xs'] ) ) ? ( ( ! isset( $attr['slideColumns']['sm'] ) ) ? $attr['slideColumns']['md'] : $attr['slideColumns']['sm'] ) : ( isset( $attr['slideColumns']['xs'] ) ? $attr['slideColumns']['xs'] : 1 ),
				),
			),
		);

		if ( isset( $attr['centerMode']['md'] ) && true === $attr['centerMode']['md'] ) {
			$settings['focus'] = 'center';
		} elseif ( isset( $attr['slideScroll']['md'] ) && 1 === $attr['slideScroll']['md'] ) {
			$settings['focus'] = 0;
		} else {
			$settings['focus'] = false;
		}
		if ( isset( $attr['carType'] ) && ! empty( $attr['carType'] ) && 'fade' === $attr['carType'] ) {
			$settings['rewind'] = isset( $attr['rewindFade'] ) ? $attr['rewindFade'] : false;
		}

		if ( isset( $attr['centerMode']['sm'] ) && true === $attr['centerMode']['sm'] ) {
			$settings['breakpoints']['1024']['focus'] = 'center';
		} elseif ( ! isset( $attr['centerMode']['sm'] ) && ! isset( $attr['slideScroll']['sm'] ) ) {
			$settings['breakpoints']['1024']['focus'] = $settings['focus'];
		} elseif ( isset( $attr['slideScroll']['sm'] ) && 1 === $attr['slideScroll']['sm'] ) {
			$settings['breakpoints']['1024']['focus'] = 0;
		} else {
			$settings['breakpoints']['1024']['focus'] = false;
		}

		if ( isset( $attr['centerMode']['xs'] ) && true === $attr['centerMode']['xs'] ) {
			$settings['breakpoints']['767']['focus'] = 'center';
		} elseif ( ! isset( $attr['centerMode']['xs'] ) && ! isset( $attr['slideScroll']['xs'] ) ) {
			$settings['breakpoints']['767']['focus'] = $settings['breakpoints']['1024']['focus'];
		} elseif ( isset( $attr['slideScroll']['xs'] ) && 1 === $attr['slideScroll']['xs'] ) {
			$settings['breakpoints']['767']['focus'] = 0;
		} else {
			$settings['breakpoints']['767']['focus'] = false;
		}

		if ( ( isset( $attr['centerMode']['md'] ) && true === $attr['centerMode']['md'] ) || ( isset( $attr['centerMode']['sm'] ) && true === $attr['centerMode']['sm'] ) || ( isset( $attr['centerMode']['xs'] ) && true === $attr['centerMode']['xs'] ) ) {
			if ( isset( $attr['trimSpace'] ) && true === $attr['trimSpace'] ) {
				$settings['trimSpace'] = true;
			} else {
				$settings['trimSpace'] = false;
			}
		}

		if ( isset( $attr['sliderMode'] ) && 'vertical' === $attr['sliderMode'] ) {
			$settings['heightRatio'] = ( isset( $attr['slideheightRatio'] ) && ! empty( $attr['slideheightRatio'] ) ) ? $attr['slideheightRatio'] : 0.5;

			if ( isset( $attr['tabslideRatio'] ) && ! empty( $attr['tabslideRatio'] ) ) {
				$settings['breakpoints']['1024']['heightRatio'] = $attr['tabslideRatio'];
			}

			if ( isset( $attr['mobslideRatio'] ) && ! empty( $attr['mobslideRatio'] ) ) {
				$settings['breakpoints']['767']['heightRatio'] = $attr['mobslideRatio'];
			}
		}

		if ( isset( $attr['slideautoScroll'] ) && ! empty( $attr['slideautoScroll'] ) ) {

			if ( isset( $attr['autoscSpeed'] ) ) {
				$settings['autoScroll']['speed'] = (int) $attr['autoscSpeed'];
			} else {
				$settings['autoScroll']['speed'] = 1;
			}
			$settings['lazyLoad']                   = 'nearby';
			$settings['autoScroll']['pauseOnHover'] = isset( $attr['slideHoverPause'] ) ? $attr['slideHoverPause'] : false;
		}

		return $settings;
	}

	/**
	 * Get Carousel Custom dots Block Options
	 *
	 *  @since 1.1.2
	 * @param array  $arrows_style The arrows style.
	 * @param string $arrows_position The arrows position.
	 */
	public static function tpgb_carousel_arrow( $arrows_style, $arrows_position = '' ) {
		$arrow      = '';
		$arrow     .= '<div class="splide__arrows ' . esc_attr( $arrows_style ) . '">';
			$arrow .= '<button class="splide__arrow splide__arrow--prev ' . esc_attr( $arrows_style ) . ' ' . ( 'style-3' === $arrows_style || 'style-4' === $arrows_style ? esc_attr( $arrows_position ) : '' ) . ' ">';
		if ( 'style-2' === $arrows_style || 'style-5' === $arrows_style ) {
			$arrow .= '<span class="icon-wrap"></span>';
		} elseif ( 'style-3' === $arrows_style || 'style-4' === $arrows_style ) {
			$arrow .= '<span class="icon-wrap"><svg aria-hidden="true" focusable="false" data-prefix="far" data-icon="angle-left" class="svg-inline--fa fa-angle-left fa-w-6" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 192 512"><path fill="currentColor" d="M4.2 247.5L151 99.5c4.7-4.7 12.3-4.7 17 0l19.8 19.8c4.7 4.7 4.7 12.3 0 17L69.3 256l118.5 119.7c4.7 4.7 4.7 12.3 0 17L168 412.5c-4.7 4.7-12.3 4.7-17 0L4.2 264.5c-4.7-4.7-4.7-12.3 0-17z"></path></svg></span>';
		} elseif ( 'style-6' === $arrows_style ) {
			$arrow .= '<span class="icon-wrap"><svg aria-hidden="true" focusable="false" data-prefix="far" data-icon="long-arrow-left" class="svg-inline--fa fa-long-arrow-left fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M152.485 396.284l19.626-19.626c4.753-4.753 4.675-12.484-.173-17.14L91.22 282H436c6.627 0 12-5.373 12-12v-28c0-6.627-5.373-12-12-12H91.22l80.717-77.518c4.849-4.656 4.927-12.387.173-17.14l-19.626-19.626c-4.686-4.686-12.284-4.686-16.971 0L3.716 247.515c-4.686 4.686-4.686 12.284 0 16.971l131.799 131.799c4.686 4.685 12.284 4.685 16.97-.001z"></path></svg></span>';
		}
			$arrow .= '</button>';
			$arrow .= '<button class="splide__arrow splide__arrow--next ' . esc_attr( $arrows_style ) . ' ' . ( 'style-3' === $arrows_style || 'style-4' === $arrows_style ? esc_attr( $arrows_position ) : '' ) . '">';
		if ( 'style-2' === $arrows_style || 'style-5' === $arrows_style ) {
			$arrow .= '<span class="icon-wrap"></span>';
		} elseif ( 'style-3' === $arrows_style || 'style-4' === $arrows_style ) {
			$arrow .= '<span class="icon-wrap"><svg aria-hidden="true" focusable="false" data-prefix="far" data-icon="angle-right" class="svg-inline--fa fa-angle-right fa-w-6" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 192 512"><path fill="currentColor" d="M187.8 264.5L41 412.5c-4.7 4.7-12.3 4.7-17 0L4.2 392.7c-4.7-4.7-4.7-12.3 0-17L122.7 256 4.2 136.3c-4.7-4.7-4.7-12.3 0-17L24 99.5c4.7-4.7 12.3-4.7 17 0l146.8 148c4.7 4.7 4.7 12.3 0 17z"></path></svg></span>';
		} elseif ( 'style-6' === $arrows_style ) {
			$arrow .= '<span class="icon-wrap"><svg aria-hidden="true" focusable="false" data-prefix="far" data-icon="long-arrow-alt-right" class="svg-inline--fa fa-long-arrow-alt-right fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M340.485 366l99.03-99.029c4.686-4.686 4.686-12.284 0-16.971l-99.03-99.029c-7.56-7.56-20.485-2.206-20.485 8.485v71.03H12c-6.627 0-12 5.373-12 12v32c0 6.627 5.373 12 12 12h308v71.03c0 10.689 12.926 16.043 20.485 8.484z"></path></svg></span>';
		}
			$arrow .= '</button>';
		$arrow     .= '</div>';

		return $arrow;
	}

	/**
	 * Get Carousel Arrows Css
	 *
	 *  @since 1.1.2
	 * @param array $show_arrows The show arrows.
	 * @param int   $block_id The block id.
	 */
	public static function tpgb_carousel_arrow_css( $show_arrows, $block_id ) {
		$arrow_css = '';
		if ( isset( $show_arrows['md'] ) && true === $show_arrows['md'] ) {
			$arrow_css .= '.tpgb-block-' . esc_attr( $block_id ) . '.splide .splide__arrows{display: block }';
			if ( isset( $show_arrows['sm'] ) && false === $show_arrows['sm'] ) {
				$arrow_css .= '@media (max-width:1024px){.tpgb-block-' . esc_attr( $block_id ) . '.splide .splide__arrows{display: none } }';
			}
			if ( isset( $show_arrows['xs'] ) && false === $show_arrows['xs'] ) {
				$arrow_css .= '@media (max-width: 1024px){.text-center{text-align: center;}}@media (max-width:767px){.tpgb-block-' . esc_attr( $block_id ) . '.splide .splide__arrows{display: none } }';
			}
		}
		if ( isset( $show_arrows['sm'] ) && true === $show_arrows['sm'] ) {
			$arrow_css .= '@media (max-width:1024px){.tpgb-block-' . esc_attr( $block_id ) . '.splide .splide__arrows{display: block } }';
			if ( isset( $show_arrows['xs'] ) && false === $show_arrows['xs'] ) {
				$arrow_css .= '@media (max-width:767px){.tpgb-block-' . esc_attr( $block_id ) . '.splide .splide__arrows{display: none } }';
			}
		}
		if ( isset( $show_arrows['xs'] ) && true === $show_arrows['xs'] ) {
			$arrow_css .= '@media (max-width: 1024px){.text-center{text-align: center;}}@media (max-width:767px){.tpgb-block-' . esc_attr( $block_id ) . '.splide .splide__arrows{display: block } }';
		}

		$style_css = '';
		if ( ! empty( $arrow_css ) ) {
			$style_css = '<style>' . $arrow_css . '</style>';
		}

		return $style_css;
	}

	/**
	 * Get Carousel Arrow Dot Class
	 *
	 * @since 3.0.4
	 * @param mixed $attr The attr.
	 */
	public static function tpgb_carousel_arrowdot_class( $attr ) {
		$show_dots          = ( ! empty( $attr['showDots'] ) ) ? $attr['showDots'] : array( 'md' => false );
		$show_arrows        = ( ! empty( $attr['showArrows'] ) ) ? $attr['showArrows'] : array( 'md' => false );
		$dots_style         = ( ! empty( $attr['dotsStyle'] ) ) ? $attr['dotsStyle'] : false;
		$outer_arrows       = ( ! empty( $attr['outerArrows'] ) ) ? $attr['outerArrows'] : false;
		$slide_hover_arrows = ( ! empty( $attr['slideHoverArrows'] ) ) ? $attr['slideHoverArrows'] : false;
		$slide_hover_dots   = ( ! empty( $attr['slideHoverDots'] ) ) ? $attr['slideHoverDots'] : false;

		$sliderclass = '';

		if ( true === $slide_hover_dots && ( ( isset( $show_dots['md'] ) && ! empty( $show_dots['md'] ) ) || ( isset( $show_dots['sm'] ) && ! empty( $show_dots['sm'] ) ) || ( isset( $show_dots['xs'] ) && ! empty( $show_dots['xs'] ) ) ) ) {
			$sliderclass .= ' hover-slider-dots';
		}
		if ( true === $outer_arrows && ( ( isset( $show_arrows['md'] ) && ! empty( $show_arrows['md'] ) ) || ( isset( $show_arrows['sm'] ) && ! empty( $show_arrows['sm'] ) ) || ( isset( $show_arrows['xs'] ) && ! empty( $show_arrows['xs'] ) ) ) ) {
			$sliderclass .= ' outer-slider-arrow';
		}
		if ( true === $slide_hover_arrows && ( ( isset( $show_arrows['md'] ) && ! empty( $show_arrows['md'] ) ) || ( isset( $show_arrows['sm'] ) && ! empty( $show_arrows['sm'] ) ) || ( isset( $show_arrows['xs'] ) && ! empty( $show_arrows['xs'] ) ) ) ) {
			$sliderclass .= ' hover-slider-arrow';
		}
		if ( ( isset( $show_dots['md'] ) && ! empty( $show_dots['md'] ) ) || ( isset( $show_dots['sm'] ) && ! empty( $show_dots['sm'] ) ) || ( isset( $show_dots['xs'] ) && ! empty( $show_dots['xs'] ) ) ) {
			$sliderclass .= ' dots-' . esc_attr( $dots_style );
		}

		return $sliderclass;
	}

	/**
	 * Custom Font Load
	 *
	 * @since 1.2.0
	 */
	public static function tpgb_custom_font() {
		$system_fonts = array(
			'id'      => 'tpgb-system-fonts',
			'title'   => __( 'System', 'the-plus-addons-for-block-editor' ),
			'options' => apply_filters(
				'tpgb-system-fonts-list', // phpcs:ignore WordPress.NamingConventions.ValidHookName
				array(
					(object) array(
						'label' => __( 'Default', 'the-plus-addons-for-block-editor' ),
						'value' => '',
					),
					(object) array(
						'label' => __( 'Arial', 'the-plus-addons-for-block-editor' ),
						'value' => 'Arial',
					),
					(object) array(
						'label' => __( 'Georgia', 'the-plus-addons-for-block-editor' ),
						'value' => 'Georgia',
					),
					(object) array(
						'label' => __( 'Helvetica', 'the-plus-addons-for-block-editor' ),
						'value' => 'Helvetica',
					),
					(object) array(
						'label' => __( 'Tahoma', 'the-plus-addons-for-block-editor' ),
						'value' => 'Tahoma',
					),
					(object) array(
						'label' => __( 'Times New Roman', 'the-plus-addons-for-block-editor' ),
						'value' => 'Times New Roman',
					),
					(object) array(
						'label' => __( 'Trebuchet MS', 'the-plus-addons-for-block-editor' ),
						'value' => 'Trebuchet MS',
					),
					(object) array(
						'label' => __( 'Verdana', 'the-plus-addons-for-block-editor' ),
						'value' => 'Verdana',
					),
				)
			),
		);
		$custom_fonts = array(
			'id'      => 'tpgb-custom-fonts',
			'title'   => __( 'Custom Fonts', 'the-plus-addons-for-block-editor' ),
			'options' => apply_filters( 'tpgb-custom-fonts-list', array() ), // phpcs:ignore WordPress.NamingConventions.ValidHookName
		);

		/** Theme json */
		if ( function_exists( 'wp_get_global_settings' ) ) {
			$theme_json_settings = wp_get_global_settings();

			if ( ! empty( $theme_json_settings ) ) {
				if ( isset( $theme_json_settings['typography'] ) && ! empty( $theme_json_settings['typography'] ) ) {
					if ( isset( $theme_json_settings['typography']['fontFamilies'] ) && ! empty( $theme_json_settings['typography']['fontFamilies'] ) ) {
						foreach ( $theme_json_settings['typography']['fontFamilies'] as $category => $fonts ) {
							foreach ( $fonts as $font ) {
								$custom_fonts['options'][] = (object) array(
									'label' => str_replace( '"', '', $font['name'] ),
									'value' => str_replace( '"', '', $font['name'] ),
								);
							}
						}
					}
				}
			}
		}

		/*Custom Fonts*/
		if ( class_exists( 'Bsf_Custom_Fonts_Taxonomy' ) ) {
			$fonts = Bsf_Custom_Fonts_Taxonomy::get_fonts();
			if ( ! empty( $fonts ) ) {
				foreach ( $fonts as $font => $values ) {
					$custom_fonts['options'][] = (object) array(
						'label' => $font,
						'value' => $font,
					);
				}
			}
		}
		/*Use any Font*/
		if ( function_exists( 'uaf_get_font_families' ) ) {
			$uaf_fonts = uaf_get_font_families();
			if ( ! empty( $uaf_fonts ) ) {
				foreach ( $uaf_fonts as $font => $values ) {
					$custom_fonts['options'][] = (object) array(
						'label' => $values,
						'value' => $values,
					);
				}
			}
		}
		if ( ! empty( $custom_fonts['options'] ) ) {
			return wp_json_encode( array_merge( $system_fonts, $custom_fonts ) );
		} else {
			return wp_json_encode( $system_fonts );
		}
		return false;
	}

	/*
	 * Check Html Tag
	 * @since 1.2.1
	 */
	public static function tpgb_html_tag_check() { // phpcs:ignore Squiz.Commenting.FunctionComment
		return array(
			'div',
			'h1',
			'h2',
			'h3',
			'h4',
			'h5',
			'h6',
			'a',
			'span',
			'p',
			'header',
			'footer',
			'article',
			'aside',
			'main',
			'nav',
			'section',
			'tr',
			'th',
			'td',
		);
	}

	/**
	 * Validate Html Tag
	 *
	 * @since 1.2.1
	 * @param mixed $check_tag The check tag.
	 */
	public static function validate_html_tag( $check_tag ) {
		return in_array( strtolower( $check_tag ), self::tpgb_html_tag_check(), true ) ? $check_tag : 'div';
	}

	/** // phpcs:ignore Squiz.Commenting.FunctionComment, Generic.Commenting.DocComment.ShortNotCapital,Generic.Commenting.DocComment.LongNotCapital,Generic.Commenting.DocComment.MissingShort
	 * Add Link Custom Attribute
	 *
	 * @since 1.3.1
	 * @param array $fieldname The fieldname.
	 */
	public static function add_link_attributes( $fieldname = array(), $separator = ',' ) {
		if ( ! empty( $fieldname ) && is_array( $fieldname ) && isset( $fieldname['attr'] ) && ! empty( $fieldname['attr'] ) ) {
			$output      = array();
			$custom_attr = $fieldname['attr'];

			$attributes = explode( $separator, $custom_attr );
			foreach ( $attributes as $attribute ) {
				$key_val = explode( '|', $attribute );

				$attr_key = mb_strtolower( $key_val[0] );

				// Remove any not allowed characters.
				preg_match( '/[-_a-z0-9]+/', $attr_key, $key_matches );

				if ( empty( $key_matches[0] ) ) {
					continue;
				}

				$attr_key = $key_matches[0];

				// Avoid Javascript events and unescaped href.
				if ( 'on' === substr( $attr_key, 0, 2 ) || 'href' === $attr_key ) {
					continue;
				}

				if ( isset( $key_val[1] ) ) {
					$attr_value = trim( $key_val[1] );
				} else {
					$attr_value = '';
				}

				$output[ $attr_key ] = $attr_value;
			}

			return self::link_render_html_attributes( $output );
		}

		return '';
	}

	/**
	 * Html Render Attributes
	 *
	 * @since 1.3.1
	 * @param array $attributes The attributes.
	 */
	public static function link_render_html_attributes( array $attributes ) {
		$html_attr = array();

		foreach ( $attributes as $key => $values ) {
			if ( is_array( $values ) ) {
				$values = implode( ' ', $values );
			}

			$html_attr[] = sprintf( '%1$s="%2$s"', $key, esc_attr( $values ) );
		}

		return implode( ' ', $html_attr );
	}

	/**
	 * DECRIPT
	 *
	 * @since 1.2.1
	 * @param mixed $key The key.
	 */
	public static function tpgb_check_decrypt_key( $key ) {
		$decrypted = self::tpgb_simple_decrypt( $key, 'dy' );
		return $decrypted;
	}

	/**
	 * ENCRYPT
	 *
	 * @since 1.2.1
	 * @param mixed $string The string.
	 * @param mixed $action The action.
	 */
	public static function tpgb_simple_decrypt( $string, $action = 'dy' ) { // phpcs:ignore Universal.NamingConventions.NoReservedKeywordParameterNames.stringFound,Universal.NamingConventions.NoReservedKeywordParameterNames.arrayFound
		$tppk         = get_option( 'tpgb_activate' );
		$pro_key      = ( isset( $tppk['tpgb_activate_key'] ) && ! empty( $tppk['tpgb_activate_key'] ) ) ? $tppk['tpgb_activate_key'] : null;
		$fallback_key = 'PO$_key';
		$secret_key   = null !== $pro_key ? $pro_key : $fallback_key;
		$secret_iv    = 'PO$_iv';

		$output         = false;
		$encrypt_method = 'AES-128-CBC';
		$key            = hash( 'sha256', $secret_key );
		$iv             = substr( hash( 'sha256', $secret_iv ), 0, 16 );

		if ( 'ey' === $action ) {
			$output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode,WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode
		} elseif ( 'dy' === $action ) {
			$output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode,WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode

			// If decryption failed and we used the Pro key, retry with the fallback.
			// This handles data that was encrypted before the Pro key was activated.
			if ( ( false === $output || '' === $output ) && null !== $pro_key ) {
				$fallback_hash = hash( 'sha256', $fallback_key );
				$output        = openssl_decrypt( base64_decode( $string ), $encrypt_method, $fallback_hash, 0, $iv ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode,WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode
			}
		}

		return $output;
	}

	/**
	 * Get load activate extra Option for tpgb
	 *
	 *  @Array
	 */
	public static function get_extra_opt_enabled() {
		$load_enable_extra = get_option( 'tpgb_normal_blocks_opts' );

		if ( ! empty( $load_enable_extra ) && isset( $load_enable_extra['tp_extra_option'] ) && ! empty( $load_enable_extra['tp_extra_option'] ) ) {
			return $load_enable_extra['tp_extra_option'];
		} else {
			return;
		}
	}

	/**
	 * Tpgb get template content.
	 */
	public function tpgb_get_template_content() {

		check_ajax_referer( 'tpgb-addons', 'tpgb_nonce' );

		if ( isset( $_POST['postid'] ) && ! empty( $_POST['postid'] ) ) {
			$post_id = intval( $_POST['postid'] );
			if ( isset( $post_id ) && ! empty( $post_id ) ) {
				$content_post = get_post( $post_id );
				$content      = '';

				if ( is_object( $content_post ) ) {

					if ( 'wp_block' !== $content_post->post_type ) {
						$content = 'Please use this feature only for reusable blocks ( Pattern )';
						wp_send_json_error( $content );
						return;
					}

					if ( 'publish' !== $content_post->post_status && ! current_user_can( 'edit_posts' ) ) {
						wp_send_json_error( 'Block not available.' );
						return;
					}

					if ( ! current_user_can( 'read_post', $post_id ) && 'publish' !== $content_post->post_status ) {
						wp_send_json_error( 'Block not available.' );
						return;
					}

					$content = $content_post->post_content;
					$content = apply_filters( 'the_content', $content );
					$content = str_replace( 'strokewidth', 'stroke-width', $content );
					$content = str_replace( 'strokedasharray', 'stroke-dasharray', $content );
					$content = str_replace( 'stopcolor', 'stop-color', $content );
					$content = str_replace( 'loading="lazy"', '', $content );
				}
				if ( $content ) {
					wp_send_json_success( $content );
				} else {
					wp_send_json_success( 'fail' );
				}
			}
		}
		wp_die();
	}

	/**
	 * Form Action Ajax
	 *
	 *  @Array
	 */
	public function nxt_form_action_callback() {
		check_ajax_referer( 'tpgb-addons', 'nonce' );
		$response          = array(
			'success' => true,
			'data'    => '',
		);
		$actions_success   = array(
			'email' => false,
		);
		$errors            = '';
		$action_option_raw = isset( $_POST['actionOption'] ) ? sanitize_text_field( wp_unslash( $_POST['actionOption'] ) ) : '[]';
		$action_option     = $this->tpgb_simple_decrypt( $action_option_raw, 'dy' );
		$action_option     = json_decode( $action_option, true );

		$form_id = $action_option['formId'] ?? '';

		$proceed_with_email = true;

		if ( isset( $_POST['cf-turnstile-response'] ) ) {
			// If Turnstile response exists, validate it.
			$captcha_value = sanitize_text_field( wp_unslash( $_POST['cf-turnstile-response'] ) );
			$captcha_error = apply_filters( 'nxt_block_form_content', $captcha_value, $form_id, '' );

			if ( ! empty( $captcha_error ) ) {
				$proceed_with_email = false;
				$errors            .= $captcha_error;
			}
		}

		if ( $proceed_with_email && $action_option && $action_option['actionOption'] && 'email' === $action_option['actionOption'] ) {
			$email_to = isset( $action_option['emailTo1'] ) && ! empty( $action_option['emailTo1'] ) ? ( strpos( $action_option['emailTo1'], ',' ) !== false ? array_map( 'sanitize_email', array_map( 'trim', explode( ',', $action_option['emailTo1'] ) ) ) : sanitize_email( $action_option['emailTo1'] ) ) : '';
			$subject  = isset( $action_option['subject1'] ) && ! empty( $action_option['subject1'] ) ? sanitize_text_field( $action_option['subject1'] ) : '';

			if ( ! empty( $email_to ) && ! empty( $subject ) ) {
				$message        = isset( $action_option['message1'] ) && ! empty( $action_option['message1'] ) ? sanitize_textarea_field( $action_option['message1'] ) : '';
				$from_name      = isset( $action_option['frmNme'] ) && ! empty( $action_option['frmNme'] ) ? ( '[nxt_name]' === $action_option['frmNme'] ? get_option( 'blogname' ) : sanitize_text_field( $action_option['frmNme'] ) ) : '';
				$from_email     = isset( $action_option['frmEmail'] ) && ! empty( $action_option['frmEmail'] ) ? ( '[nxt_email]' === $action_option['frmEmail'] ? get_option( 'admin_email' ) : sanitize_email( $action_option['frmEmail'] ) ) : 'no-reply@example.com';
				$reply_to       = isset( $action_option['replyTo'] ) && ! empty( $action_option['replyTo'] ) ? sanitize_text_field( $action_option['replyTo'] ) : '';
				$cc             = isset( $action_option['ccEmail1'] ) && ! empty( $action_option['ccEmail1'] ) ? sanitize_text_field( $action_option['ccEmail1'] ) : '';
				$bcc            = isset( $action_option['bccEmail1'] ) && ! empty( $action_option['bccEmail1'] ) ? sanitize_text_field( $action_option['bccEmail1'] ) : '';
				$email_hdg      = isset( $action_option['emailHdg'] ) && ! empty( $action_option['emailHdg'] ) ? sanitize_text_field( $action_option['emailHdg'] ) : 'You have received a new form submission:';
				$regular_fields = array();

				foreach ( $_POST as $key => $value ) {
					if ( in_array( $key, array( 'actionOption', 'nonce', 'Captchaopt', 'cf-turnstile-response' ), true ) ) {
						continue;
					}

					$formatted_key = str_replace( '_', ' ', $key );

					// validation for html.
					if ( preg_replace( '/<[^>]*>/', '', $value ) !== $value ) {
						$errors .= "HTML content not allowed in $formatted_key field. ";
						continue;
					}

					// validation for email.
					if ( ! empty( $value ) && is_string( $value ) && strpos( $value, '@' ) !== false && ! filter_var( $value, FILTER_VALIDATE_EMAIL ) ) {
						$field_label = ucfirst( str_replace( array( '_', '-' ), ' ', $key ) );
						$val_err_msg = isset( $action_option['valErrMsg'] ) && ! empty( $action_option['valErrMsg'] ) ? sanitize_text_field( $action_option['valErrMsg'] ) : 'Invalid email format in ' . $field_label . ' field. ';
						$errors     .= $val_err_msg;
						continue;
					}

					// user Email Shortcode.
					if ( '[nxt_user_email]' === $reply_to && filter_var( $value, FILTER_VALIDATE_EMAIL ) ) {
						$reply_to = sanitize_email( $value );
					}

					if ( is_array( $value ) ) {
						$regular_fields[ $formatted_key ] = array_map( 'sanitize_text_field', $value );
					} else {
						$regular_fields[ $formatted_key ] = sanitize_text_field( $value );
					}
				}

				$full_message     = "<h2>$email_hdg</h2>";
				$non_empty_fields = array_filter(
					$regular_fields,
					function ( $value, $key ) {
						if ( is_array( $value ) ) {
							$value = array_filter(
								$value,
								function ( $item ) {
									return ! empty( $item ) && strtolower( $item ) !== 'undefined';
								}
							);
							return ! empty( $value );
						}
						return ! empty( $value ) && strtolower( $value ) !== 'undefined' && strtolower( $key ) !== 'action';
					},
					ARRAY_FILTER_USE_BOTH
				);

				foreach ( $non_empty_fields as $key => $value ) {
					if ( is_array( $value ) ) {
						$value = implode( ', ', $value );
					}
					$full_message .= '<p>' . ucfirst( $key ) . ": $value\n</p>";
				}

				$full_message .= "<hr style='border: 1px dashed #ccc; margin: 20px 0;'>";

				if ( isset( $action_option['metaDataOpt'] ) && is_array( $action_option['metaDataOpt'] ) ) {
					$full_message .= '<p>Meta Data:</p>';

					foreach ( $action_option['metaDataOpt'] as $meta_data ) {
						$label = isset( $meta_data['label'] ) && ! empty( $meta_data['label'] ) ? $meta_data['label'] : 'Unknown Label';
						$value = 'Unknown Value';

						if ( isset( $meta_data['value'] ) ) {
							switch ( $meta_data['value'] ) {
								case 'metaDate':
									$value = gmdate( 'Y-m-d' );
									break;
								case 'metaTime':
									$value = gmdate( 'H:i:s' );
									break;
								case 'metaRemoteIp':
									$value = $_SERVER['REMOTE_ADDR'] ?? 'Unknown IP'; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput,WordPress.Security.NonceVerification.Missing,WordPress.Security.NonceVerification.Recommended
									break;
								case 'metaUserAgent':
									$value = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown User Agent'; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput,WordPress.Security.NonceVerification.Missing,WordPress.Security.NonceVerification.Recommended
									break;
								case 'metaPageUrl':
									$value = $_SERVER['HTTP_REFERER'] ?? 'Unknown Page URL'; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput,WordPress.Security.NonceVerification.Missing,WordPress.Security.NonceVerification.Recommended
									break;
								default:
									$value = 'Unknown Value';
							}
						}

						$full_message .= "$label: $value<br>";
					}

					// $full_message .= "</ul>"; // phpcs:ignore Squiz.PHP.CommentedOutCode.Found
				} else {
					$full_message .= '<p><em>No metadata options provided.</em></p>';
				}
				$full_message .= '<p>Powered by Nexter Form</p>';
				$headers       = array(
					"From: $from_name <$from_email>",
					"Reply-To: $reply_to",
					'Content-Type: text/html; charset=UTF-8',
				);

				if ( ! empty( $cc ) ) {
					$headers[] = "Cc: $cc";
				}

				if ( ! empty( $bcc ) ) {
					$headers[] = "Bcc: $bcc";
				}

				$mail_sent                = wp_mail( $email_to, $subject, $full_message, $headers );
				$actions_success['email'] = $mail_sent ? true : false;
				if ( ! $mail_sent ) {
					$fail_msg = isset( $action_option['failMsg'] ) && ! empty( $action_option['failMsg'] ) ? sanitize_textarea_field( $action_option['failMsg'] ) : __( 'Failed to send email.', 'the-plus-addons-for-block-editor' );
					$errors  .= $fail_msg;
				}
			} else {
				$errors                  .= __( 'Email address and Subject is required. ', 'the-plus-addons-for-block-editor' );
				$actions_success['email'] = false;
			}
		}
		if ( has_filter( 'nxt_form_pro_action_callback' ) ) {
			$data = apply_filters( 'nxt_form_pro_action_callback', $data );
		}

		$response['success'] = empty( $errors );
		$response['data']    = empty( $errors ) ? 'Success' : $errors;

		echo wp_json_encode( $response );
		wp_die();
	}

	/**
	 * Form Content Render
	 *
	 * @since 4.5.6
	 * @param string $captcha_val The captcha val.
	 * @param string $form_id The form id.
	 * @param string $errors The errors.
	 */
	public function nxt_block_form_content_render( $captcha_val = '', $form_id = '', $errors = '' ) {

		if ( ! empty( $captcha_val ) ) {
			$captcha_value    = sanitize_text_field( wp_unslash( $captcha_val ) );
			$response_captcha = apply_filters(
				'nexter_form_validate' . esc_attr( $form_id ),
				array(),
				$captcha_value
			);

			// Ensure result is an array.
			if ( is_array( $response_captcha ) ) {

				// If success is explicitly false.
				if ( isset( $response_captcha['success'] ) && false === $response_captcha['success'] ) {

					// If there is data, you could log it or use it.
					if ( ! empty( $response_captcha['data'] ) ) {
						$errors .= $response_captcha['data'];
					} else {
						$errors .= __( 'No Cloudflare Turnstile response received.', 'the-plus-addons-for-block-editor' );
					}
				}
			} else {
				$errors .= __( 'Invalid CAPTCHA validation response format.', 'the-plus-addons-for-block-editor' );
			}
		}

		return $errors;
	}

	/**
	 * Equal Height Attribute Function
	 *
	 * @since 4.5.8
	 * @param mixed $attr The attr.
	 */
	public static function global_equal_height( $attr ) {
		$equal_height    = ( ! empty( $attr['tpgbEqualHeight'] ) ) ? $attr['tpgbEqualHeight'] : false;
		$equal_unq_class = ( ! empty( $attr['equalUnqClass'] ) ) ? $attr['equalUnqClass'] : '';

		$eql_opt           = '';
		$equal_height_attr = '';
		if ( ! empty( $equal_height ) ) {
			$eql_opt            = esc_attr( $equal_unq_class );
			$equal_height_attr .= ' data-tpgb-equal-height="' . esc_attr( $eql_opt ) . '"';
		}

		return $equal_height_attr;
	}

	/**
	 * Get the upload directory with HTTPS enforced (proxy/CDN safe).
	 *
	 * @since 4.5.10
	 * @return array Modified wp_upload_dir() array with correct baseurl.
	 */
	public static function tpgb_get_upload_url() {
		// Get default upload directory info.
		$upload_dir = wp_get_upload_dir();

		// Detect HTTPS correctly (including proxy/CDN setups).
		$is_ssl = ( is_ssl() || ( 0 === stripos( get_option( 'siteurl' ), 'https://' ) ) || ( isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) && 'https' === $_SERVER['HTTP_X_FORWARDED_PROTO'] ) );

		// Correct protocol for URLs.
		if ( $is_ssl ) {
			$upload_dir['baseurl'] = str_replace( 'http://', 'https://', $upload_dir['baseurl'] );
		}

		return trailingslashit( $upload_dir['baseurl'] );
	}
}

Tp_Blocks_Helper::get_instance();
