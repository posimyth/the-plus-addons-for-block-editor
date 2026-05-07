<?php
/**
 * Nexter Blocks Registered Lists
 *
 * @since   1.0.0
 * @package TPGB
 */

// phpcs:disable Universal.Files.SeparateFunctionsFromOO.Mixed
// phpcs:disable WordPress.Files.FileName

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Tpgb_ Get_ Blocks.
 *
 * @since 1.0.0
 */
class Tpgb_Get_Blocks {

	/**
	 * A reference to an instance of this class.
	 *
	 * @since 1.0.0
	 * @var   object
	 */
	private static $instance = null;

	/**
	 * Transient blocks.
	 *
	 * @var array
	 */
	public $transient_blocks = array();
	/**
	 * Post type.
	 *
	 * @var array
	 */
	public $post_type = array();
	/**
	 * Post id.
	 *
	 * @var array
	 */
	public $post_id = array();

	/**
	 * Templates ids.
	 *
	 * @var array
	 */
	public $templates_ids = array();

	/**
	 * Preload name.
	 *
	 * @var string
	 */
	public $preload_name = '';

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
	 *
	 * @param string $post_type The post type.
	 * @param string $post_id The post id.
	 */
	public function __construct( $post_type = '', $post_id = '' ) {
		if ( ! empty( $post_id ) ) {
			$this->post_type = $post_type;
			$this->post_id   = intval( $post_id );
			$this->tpgb_post_block_css( $post_type, $this->post_id );
		} else {
			$this->post_type = $post_type;
			$this->post_id   = intval( $post_id );
		}
	}

	/**
	 * Get ids for all wp template part
	 *
	 * @since 2.0.3
	 * @param mixed $block The block.
	 */
	public function get_fse_template_part( $block ) {
		if ( ! empty( $block['attrs'] ) && ! empty( $block['attrs']['slug'] ) ) {
			$slug            = $block['attrs']['slug'];
			$templates_parts = get_block_templates( array( 'slugs__in' => $slug ), 'wp_template_part' );
			$lang_slug       = '';
			if ( function_exists( 'pll_current_language' ) ) {
				$lang = pll_current_language();
				if ( ! empty( $lang ) ) {
					$lang_slug = $slug . '___' . $lang;
				}
			}
			foreach ( $templates_parts as $templates_part ) {
				if ( $slug === $templates_part->slug || $lang_slug === $templates_part->slug ) {
					$temp_id = $templates_part->wp_id;
					return $temp_id;
				}
			}
		}
	}

	/**
	 * Get Reference ID
	 *
	 * @since 1.1.1
	 * @param mixed $res_blocks The res blocks.
	 */
	public function block_reference_id( $res_blocks ) {
		$ref_id = array();

		if ( ! empty( $res_blocks ) ) {
			foreach ( $res_blocks as $key => $block ) {
				if ( 'core/block' === $block['blockName'] ) {
					if ( isset( $block['attrs']['ref'] ) ) {
						$ref_id[] = $block['attrs']['ref'];
					}
				} elseif ( 'core/template-part' === $block['blockName'] ) {
					$temp_id = $this->get_fse_template_part( $block );
					if ( ! empty( $temp_id ) ) {
						$ref_id[] = $temp_id;
					}
				} elseif ( 'core/pattern' === $block['blockName'] ) {
					$temp_id = $this->get_fse_pattern_assets( $block );
					if ( ! empty( $temp_id ) ) {
						$ref_id[] = $temp_id;
					}
				}
				$this->render_block( $block );
				if ( count( $block['innerBlocks'] ) > 0 ) {
					$ref_id = array_merge( $this->block_reference_id( $block['innerBlocks'] ), $ref_id );
				}
			}
		}
		return $ref_id;
	}

	/**
	 * Get fse pattern assets.
	 *
	 * @param mixed $block The block.
	 * @return mixed The result.
	 */
	public function get_fse_pattern_assets( $block ) {
		if ( empty( $block['attrs']['slug'] ) ) {
			return array();
		}

		$slug = $block['attrs']['slug'];

		if ( ! class_exists( 'WP_Block_Patterns_Registry' ) || ! method_exists( 'WP_Block_Patterns_Registry', 'get_instance' ) ) {
			return array();
		}

		$registry = WP_Block_Patterns_Registry::get_instance();

		if ( ! method_exists( $registry, 'is_registered' ) || ! method_exists( $registry, 'get_registered' ) || ! $registry->is_registered( $slug ) ) {
			return array();
		}

		$pattern = $registry->get_registered( $slug );

		return $this->block_reference_id( parse_blocks( $pattern['content'] ) );
	}

	/**
	 * Frontend Post Block Load Css
	 *
	 * @since 1.1.1
	 * @param string $post_type The post type.
	 * @param int    $post_id The post id.
	 */
	public function tpgb_post_block_css( $post_type = '', $post_id = 0 ) {
		if ( '' !== $post_id ) {

			if ( function_exists( 'is_shop' ) && function_exists( 'wc_get_page_id' ) && is_shop() ) {
				$post_id = wc_get_page_id( 'shop' );
			}

			$post_content = get_post( $post_id );
			if ( isset( $post_content->post_content ) ) {
				$content      = $post_content->post_content;
				$parse_blocks = parse_blocks( $content );
				$res_id       = $this->block_reference_id( $parse_blocks );
				if ( is_array( $res_id ) && ! empty( $res_id ) ) {
					$res_id              = array_unique( $res_id );
					$this->templates_ids = $res_id;
				}
			}

			$this->transient_blocks = $this->get_blocks_elements( $this->transient_blocks );
			$this->preload_name     = $post_id;
			// if no cache files, generate new.
			if ( class_exists( 'Tpgb_Library' ) ) {

				// current page/post load all templates one time load elements.
				if ( isset( $this->transient_blocks ) && ! empty( $this->transient_blocks ) ) {

					if ( isset( tpgb_load_data()->post_assets_objects['elements'] ) ) {
						$elements = tpgb_load_data()->post_assets_objects['elements'];
					} else {
						$elements = array();
					}
					$different_elements = array_diff( $this->transient_blocks, $elements );
					if ( $this->transient_blocks !== $different_elements ) {
						$this->preload_name = get_queried_object_id() . '-' . $post_id;
					}
					tpgb_load_data()->post_assets_objects['elements'] = array_unique( array_merge( $elements, $this->transient_blocks ) );
					$this->transient_blocks                           = $different_elements;
				}

				$updated_at = tpgb_library()->get_posts_metadata( $this->preload_name, '_block_css', 'updated_at', 'theplus-' . $post_type . '-' . $this->preload_name . '_updated_at' );
				if ( ! empty( $updated_at ) && get_option( 'tpgb_save_updated_at' ) === $updated_at ) {
					return false;
				}
				// remove page/post generate files.
				tpgb_library()->remove_files_unlink( $post_type, $this->preload_name, array( 'css' ), true );

				// regenerate files page/post.
				if ( ! tpgb_library()->check_cache_files( $post_type, $this->preload_name, 'css', true ) && tpgb_library()->get_caching_option() === false ) {
					tpgb_library()->plus_generate_scripts( $this->transient_blocks, 'theplus-preload-' . $post_type . '-' . $this->preload_name, array( 'css' ), false );
				}
			}
		}
	}

	/**
	 * Enqueue Styles preloaded
	 *
	 * @since 2.0.0
	 * @param bool $dependency The dependency.
	 */
	public function enqueue_scripts( $dependency = false ) {
		if ( is_array( $this->post_id ) || is_array( $this->post_type ) ) {
			return;
		}

		$plus_version = get_post_meta( $this->post_id, '_block_css', true );
		if ( ! empty( get_queried_object_id() ) && get_queried_object_id() !== $this->post_id ) {
			$plus_version = get_post_meta( get_queried_object_id(), '_block_css', true );
		}

		if ( ! empty( $plus_version ) && isset( $plus_version['version'] ) && ! empty( $plus_version['version'] ) ) {
			$plus_version = $plus_version['version'];
		} elseif ( empty( $plus_version ) ) {
			$plus_version = time();
		}

		$localize = '';
		if ( tpgb_library()->get_caching_option() === false ) {
			$check_global_css   = Tp_Blocks_Helper::get_extra_option( 'gbl_css' );
			$global_block_style = Tp_Blocks_Helper::get_extra_opt_enabled();
			if ( tpgb_library()->check_css_js_cache_files( $this->post_type, $this->preload_name, 'css', true ) ) {
				$css_file     = TPGB_ASSET_URL . '/theplus-preload-' . $this->post_type . '-' . $this->preload_name . '.min.css';
				$enqueue_name = 'tpgb-plus-' . $this->post_type . '-' . $this->preload_name;
				if ( false === $dependency ) {
					$enqueue_name = 'tpgb-plus-block-front-css';
					if ( ! empty( $check_global_css ) && 'disable' === $check_global_css && ! empty( $global_block_style ) && ! in_array( 'tp-global-block-style', $global_block_style, true ) ) {
						$dependency = array();
					} else {
						$global_css = get_option( '_tpgb_global_css' );
						$dependency = ( false !== $global_css ) ? array( 'plus-global' ) : array();
					}
					wp_enqueue_style( 'tpgb-common', esc_url( TPGB_URL . 'assets/css/main/general/tpgb-common.css' ), $dependency, $plus_version );
				} else {
					$dependency = array( 'tpgb-plus-block-front-css' );
				}
				wp_enqueue_style( $enqueue_name, esc_url( $css_file ), $dependency, $plus_version );
			} elseif ( false === $dependency ) {
				if ( ! empty( $check_global_css ) && 'disable' === $check_global_css && ! empty( $global_block_style ) && ! in_array( 'tp-global-block-style', $global_block_style, true ) ) {
					$dependency = array();
				} else {
					$global_css = get_option( '_tpgb_global_css' );
					$dependency = ( false !== $global_css ) ? array( 'plus-global' ) : array();
				}
				wp_enqueue_style( 'tpgb-plus-block-front-css', esc_url( TPGB_URL . 'assets/css/main/general/tpgb-common.css' ), $dependency, $plus_version );

			}

			$tpgb_ajax = Tp_Blocks_Helper::get_extra_option( 'tpgb_template_load' );
			if ( ( isset( $tpgb_ajax ) && ! empty( $tpgb_ajax ) && 'enable' === $tpgb_ajax ) || empty( $tpgb_ajax ) ) {
				wp_enqueue_style( 'tpgb-ajax-load-template-css', TPGB_URL . 'assets/css/main/general/tpgb-ajax-load.css', array(), TPGB_VERSION );
			}
		} elseif ( tpgb_library()->get_caching_option() === 'separate' ) {
			$tpgb_path     = TPGB_PATH . DIRECTORY_SEPARATOR;
			$tpgb_url      = TPGB_URL;
			$separate_path = tpgb_library()->load_separate_file( $this->transient_blocks );
			if ( isset( $separate_path['css'] ) && ! empty( $separate_path['css'] ) ) {
				$iji       = 1;
				$total_eng = count( $separate_path['css'] );
				foreach ( $separate_path['css'] as $key => $path ) {
					if ( is_readable( tpgb_library()->secure_path_url( $path ) ) ) {
						$css_sep_url = str_replace( $tpgb_path, $tpgb_url, $path );
						if ( defined( 'TPGBP_VERSION' ) && defined( 'TPGBP_URL' ) ) {
							$css_sep_url = str_replace( TPGBP_PATH . DIRECTORY_SEPARATOR, TPGBP_URL, $css_sep_url );
						}
						$css_file_key = basename( $css_sep_url, '.css' );
						$css_file_key = basename( $css_file_key, '.min' );
						$last_folder  = basename( dirname( $css_sep_url ) );
						$enq_name     = 'theplus-' . $css_file_key . '-' . $last_folder;
						if ( $iji === $total_eng && false === $dependency ) {
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
					if ( is_readable( tpgb_library()->secure_path_url( $path ) ) ) {
						$js_sep_url = str_replace( $tpgb_path, $tpgb_url, $path );
						if ( defined( 'TPGBP_VERSION' ) && defined( 'TPGBP_URL' ) ) {
							$js_sep_url = str_replace( TPGBP_PATH . DIRECTORY_SEPARATOR, TPGBP_URL, $js_sep_url );
						}
						$js_file_key = basename( $js_sep_url, '.js' );
						$js_file_key = basename( $js_file_key, '.min' );
						if ( 0 === $iji ) {
							$localize = 'theplus-' . $js_file_key;
						}
						wp_enqueue_script( 'theplus-' . $js_file_key, esc_url( $js_sep_url ), array(), $plus_version, true );
						++$iji;
					}
				}
			}
			return $localize;
		}
	}

	/**
	 * Post content of a single block.
	 *
	 * @param mixed $block The block.
	 */
	public function render_block( $block ) {
		if ( $block['blockName'] ) {
			$options = ( ! empty( $block['attrs'] ) ) ? $block['attrs'] : '';
			$this->plus_blocks_options( $options, $block['blockName'] );
			$this->transient_blocks[] = $block['blockName'];
		}
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
			$elements = array_intersect( array_keys( tpgb_registered_blocks() ), $elements );

			$elements = array_unique( $elements );
			sort( $elements );
		}
		return $elements;
	}

	/**
	 * List of Blocks Condition Check
	 *
	 * @since 1.1.1
	 * @param string $options The options.
	 * @param string $blockname The blockname.
	 */
	public function plus_blocks_options( $options = '', $blockname = '' ) {

		if ( ! empty( $options ) && ! empty( $options['contentHoverEffect'] ) ) {
			$this->transient_blocks[] = 'content-hover-effect';
		}
		if ( ! empty( $options ) && ! empty( $options['globalAnim'] ) && ( ( ! empty( $options['globalAnim']['md'] ) && 'none' !== $options['globalAnim']['md'] ) || ( ! empty( $options['globalAnim']['sm'] ) && 'none' !== $options['globalAnim']['sm'] ) || ( ! empty( $options['globalAnim']['xs'] ) && 'none' !== $options['globalAnim']['xs'] ) ) ) {
			$this->transient_blocks[] = 'tpgb-animation';
			if ( isset( $options['globalAnim']['md'] ) && 'none' !== $options['globalAnim']['md'] ) {
				$this->transient_blocks[] = 'tpgb-animation-' . $options['globalAnim']['md'];
			}
			if ( isset( $options['globalAnim']['sm'] ) && 'none' !== $options['globalAnim']['sm'] ) {
				$this->transient_blocks[] = 'tpgb-animation-' . $options['globalAnim']['sm'];
			}
			if ( isset( $options['globalAnim']['xs'] ) && 'none' !== $options['globalAnim']['xs'] ) {
				$this->transient_blocks[] = 'tpgb-animation-' . $options['globalAnim']['xs'];
			}
		}

		// Row Column Link Js.
		if ( ( 'tpgb/tp-row' === $blockname || 'tpgb/tp-column' === $blockname || 'tpgb/tp-container' === $blockname || 'tpgb/tp-container-inner' === $blockname ) && ! empty( $options ) && ! empty( $options['wrapLink'] ) && true === $options['wrapLink'] ) {
			$this->transient_blocks[] = 'tpgb-row-column-link';
		}

		if ( ! empty( $options ) && ! empty( $options['layoutType'] ) && 'carousel' === $options['layoutType'] ) {   // infobox / flipbox.

			if ( isset( $options['slideautoScroll'] ) && ! empty( $options['slideautoScroll'] ) ) {
				$this->transient_blocks[] = 'carouselautoScroll';
			}

			$this->transient_blocks[] = 'carouselSlider';
		}
		if ( ! empty( $options ) && ! empty( $options['extBtnshow'] ) ) {
			$this->transient_blocks[] = 'tpgb-group-button';
		}

		if ( 'tpgb/tp-countdown' === $blockname ) {
			if ( ! empty( $options ) && ! empty( $options['style'] ) ) {
				$this->transient_blocks[] = 'countdown-' . $options['style'];
			} else {
				$this->transient_blocks[] = 'countdown-style-1';
			}
		}
		if ( 'tpgb/tp-flipbox' === $blockname && ! empty( $options ) && ( ! empty( $options['backBtn'] ) || ! empty( $options['backCarouselBtn'] ) ) ) {
			$this->transient_blocks[] = 'tpgb-group-button';
		}
		/** Heading Title */
		if ( 'tpgb/tp-heading-title' === $blockname ) {
			if ( ! empty( $options ) && ! empty( $options['style'] ) ) {
				if ( 'style-8' === $options['style'] ) {
					$this->transient_blocks[] = 'tpx-heading-title-style-3';
				}
				$this->transient_blocks[] = 'tpx-heading-title-' . $options['style'];
			} else {
				$this->transient_blocks[] = 'tpx-heading-title-style-1';
			}
		}
		// Post Listing.
		if ( 'tpgb/tp-post-listing' === $blockname ) {
			if ( ! empty( $options ) && ! empty( $options['postLodop'] ) && 'pagination' === $options['postLodop'] ) {
				$this->transient_blocks[] = 'tpgb-pagination';
			}

			if ( isset( $options['layout'] ) && ! empty( $options['layout'] ) && 'metro' === $options['layout'] ) {
				$this->transient_blocks[] = 'tpx-post-metro';
			}

			if ( ! empty( $options ) && ! empty( $options['style'] ) ) {
				$this->transient_blocks[] = 'tpx-post-listing-' . $options['style'];
			} else {
					$this->transient_blocks[] = 'tpx-post-listing-style-1';
			}

			if ( ! empty( $options ) && ! empty( $options['tpgbEqualHeight'] ) ) {
				$this->transient_blocks[] = 'equal-height';
			}

			if ( ! empty( $options ) && ! empty( $options['ShowButton'] ) ) {
				$this->transient_blocks[] = 'tpgb-group-button';
			}
		}

		// External-Form-Styler.
		if ( 'tpgb/tp-external-form-styler' === $blockname && ! empty( $options ) ) {
			if ( ! empty( $options['formType'] ) ) {
				$this->transient_blocks[] = 'tpx-' . $options['formType'];
			} else {
				$this->transient_blocks[] = 'tpx-contact-form-7';
			}
		}
		// Social Feed.
		if ( 'tpgb/tp-social-feed' === $blockname && ! empty( $options ) ) {
			if ( ! empty( $options['style'] ) && ( 'style-3' === $options['style'] || 'style-4' === $options['style'] ) ) {
				$this->transient_blocks[] = 'tpx-social-feed-' . $options['style'];
			}
		}
		// social Review.
		if ( 'tpgb/tp-social-reviews' === $blockname && ! empty( $options ) ) {
			if ( ! empty( $options['RType'] ) ) {
				if ( ! empty( $options['style'] ) ) {
					$this->transient_blocks[] = 'tpx-review-' . $options['style'];
				} else {
					$this->transient_blocks[] = 'tpx-review-style-1';
				}
			} elseif ( ! empty( $options['style'] ) ) {
					$this->transient_blocks[] = 'tpx-review-' . $options['style'];
			} else {
				$this->transient_blocks[] = 'tpx-review-style-1';
			}
		}
		// Social Icons.
		if ( 'tpgb/tp-social-icons' === $blockname && ! empty( $options ) ) {
			if ( ! empty( $options['style'] ) ) {
				$this->transient_blocks[] = 'tpx-social-icons-' . $options['style'];
			} else {
				$this->transient_blocks[] = 'tpx-social-icons-style-1';
			}
		}
		// Progress Bar.
		if ( 'tpgb/tp-progress-bar' === $blockname && ! empty( $options ) ) {
			if ( ! empty( $options['layoutType'] ) ) {
				$this->transient_blocks[] = 'tpx-' . $options['layoutType'];
			} else {
				$this->transient_blocks[] = 'tpx-progressbar';
			}
		}
		// Pricing List.
		if ( 'tpgb/tp-pricing-list' === $blockname && ! empty( $options ) ) {
			if ( ! empty( $options['style'] ) ) {
				$this->transient_blocks[] = 'tpx-pricing-list-' . $options['style'];
			} else {
				$this->transient_blocks[] = 'tpx-pricing-list-style-1';
			}

			if ( ! empty( $options['imgShape'] ) && 'custom' === $options['imgShape'] ) {
				$this->transient_blocks[] = 'tpx-pricing-list-masking';
			}
		}

		// Svg Icon Load.
		if ( ( 'tpgb/tp-flipbox' === $blockname && ! empty( $options ) && ! empty( $options['layoutType'] ) && 'carousel' === $options['layoutType'] ) || ( 'tpgb/tp-infobox' === $blockname && ! empty( $options ) && ! empty( $options['layoutType'] ) && 'carousel' === $options['layoutType'] ) || ( 'tpgb/tp-flipbox' === $blockname && ! empty( $options ) && ! empty( $options['iconType'] ) && 'svg' === $options['iconType'] ) || ( 'tpgb/tp-infobox' === $blockname && ! empty( $options ) && ! empty( $options['iconType'] ) && 'svg' === $options['iconType'] ) || ( 'tpgb/tp-number-counter' === $blockname && ! empty( $options ) && ! empty( $options['iconType'] ) && 'svg' === $options['iconType'] ) || ( 'tpgb/tp-pricing-table' === $blockname && ! empty( $options ) && ! empty( $options['iconType'] ) && 'svg' === $options['iconType'] ) || ( 'tpgb/tp-button' === $blockname && ! empty( $options ) && ! empty( $options['svgIcon'] ) && ! empty( $options['svgIcon']['url'] ) ) ||
		( 'tpgb/tp-form-submit-button' === $blockname && ! empty( $options ) && ! empty( $options['ButtonType'] ) && 'svg' === $options['ButtonType'] ) ) {
			$this->transient_blocks[] = 'tpgb-draw-svg';
		}

		// tpgb-fancy-box.
		if ( 'tpgb/tp-post-image' === $blockname && ! empty( $options ) && ! empty( $options['fancyBox'] ) && true === $options['fancyBox'] ) {
			$this->transient_blocks[] = 'tpgb-fancy-box';
		}
		if ( 'tpgb/tp-creative-image' === $blockname && ! empty( $options ) ) {
			if ( ! empty( $options['fancyBox'] ) && true === $options['fancyBox'] ) {
				$this->transient_blocks[] = 'tpgb-fancy-box';
			}
			if ( ! empty( $options['showMaskImg'] ) ) {
				$this->transient_blocks[] = 'tpx-tp-image-mask-img';
			}
		}
		/*Button*/
		if ( 'tpgb/tp-button' === $blockname && ! empty( $options ) && ! empty( $options['btnHvrCnt'] ) && ! empty( $options['selectHvrCnt'] ) ) {
			$this->transient_blocks[] = 'content-hover-effect';
		}
		/* Infobox */
		if ( 'tpgb/tp-infobox' === $blockname ) {
			if ( ! empty( $options ) && ! empty( $options['styleType'] ) ) {
				$this->transient_blocks[] = 'tpx-infobox-' . $options['styleType'];
			} else {
				$this->transient_blocks[] = 'tpx-infobox-style-1';
			}

			if ( ! empty( $options ) && ! empty( $options['contenthoverEffect'] ) ) {
				$this->transient_blocks[] = 'content-hover-effect';
			}

			if ( ! empty( $options ) && ! empty( $options['carouselBtn'] ) ) {
				$this->transient_blocks[] = 'tpgb-group-button';
			}
		}

		/* Button */
		if ( 'tpgb/tp-button' === $blockname ) {
			if ( ! empty( $options ) && ! empty( $options['styleType'] ) ) {
				$this->transient_blocks[] = 'tpx-button-' . $options['styleType'];
			} else {
				$this->transient_blocks[] = 'tpx-button-style-1';
			}

			if ( ! empty( $options ) && isset( $options['fancyBox'] ) && ! empty( $options['fancyBox'] ) ) {
				$this->transient_blocks[] = 'tpgb-fancy-box';
				$this->transient_blocks[] = 'tpgb-fancy-custom';
			}
			/** Shake Animation */
			if ( ! empty( $options ) && ! empty( $options['shakeAnimate'] ) ) {
				$this->transient_blocks[] = 'tpx-button-shake-ani';
			}
		}

		/* Breadcrumb */
		if ( 'tpgb/tp-breadcrumbs' === $blockname ) {
			if ( ! empty( $options ) && ! empty( $options['style'] ) ) {
				$this->transient_blocks[] = 'tpx-breadcrumbs-' . $options['style'];
			} else {
				$this->transient_blocks[] = 'tpx-breadcrumbs-style-1';
			}
		}

		/* Number Counter */
		if ( 'tpgb/tp-number-counter' === $blockname ) {
			if ( ! empty( $options ) && ! empty( $options['style'] ) ) {
				$this->transient_blocks[] = 'tpx-number-counter-s2';
			}
		}
		/*Code Highlighter*/
		if ( 'tpgb/tp-code-highlighter' === $blockname ) {
			if ( ! empty( $options ) && ! empty( $options['themeType'] ) ) {
				$this->transient_blocks[] = 'tpx-' . $options['themeType'];
			} else {
				$this->transient_blocks[] = 'tpx-prism-default';
			}
		}
		/*Dark Mode*/
		if ( 'tpgb/tp-dark-mode' === $blockname ) {
			if ( ! empty( $options ) && ! empty( $options['dmStyle'] ) ) {
				$this->transient_blocks[] = 'tpx-dark-mode-' . $options['dmStyle'];
			} else {
				$this->transient_blocks[] = 'tpx-dark-mode-style-1';
			}
		}

		/* Testimonials */
		if ( 'tpgb/tp-testimonials' === $blockname ) {

			if ( isset( $options['slideautoScroll'] ) && ! empty( $options['slideautoScroll'] ) ) {
				$this->transient_blocks[] = 'carouselautoScroll';
			}

			if ( ! empty( $options ) && ! empty( $options['telayout'] ) && 'carousel' !== $options['telayout'] ) {
				$this->transient_blocks[] = 'tpgb_grid_layout';
			}

			if ( ! empty( $options ) && ! empty( $options['style'] ) ) {
				$this->transient_blocks[] = 'tpx-testimonials-' . $options['style'];
			} else {
				$this->transient_blocks[] = 'tpx-testimonials-style-1';
			}

			if ( ( ! empty( $options ) && ! empty( $options['telayout'] ) && 'grid' === $options['telayout'] ) || ( isset( $options['caroByheight'] ) && ! empty( $options['caroByheight'] ) && 'height' === $options['caroByheight'] ) ) {
				$this->transient_blocks[] = 'tpx-testimonials-scroll';
			}

			if ( ! empty( $options ) && ! empty( $options['tpgbEqualHeight'] ) ) {
				$this->transient_blocks[] = 'equal-height';
			}
		}

		/* Blockquote */
		if ( 'tpgb/tp-blockquote' === $blockname ) {
			if ( ! empty( $options ) && ! empty( $options['style'] ) && 'style-2' === $options['style'] ) {
				$this->transient_blocks[] = 'tpx-blockquote-style-2';
			}
		}

		/*Stylist List*/
		if ( 'tpgb/tp-stylist-list' === $blockname && ! empty( $options ) ) {
			if ( ! empty( $options['hover_bg_style'] ) ) {
				$this->transient_blocks[] = 'tpx-stylist-list-hover-bg';
			}
			if ( ! empty( $options['hoverInverseEffect'] ) ) {
				$this->transient_blocks[] = 'tpx-stylist-list-hover-inverse';
			}

			if ( ! empty( $options['listsRepeater'] ) && in_array( 'svg', array_column( $options['listsRepeater'], 'selectIcon' ), true ) ) {
				$this->transient_blocks[] = 'tpgb-draw-svg';
			}
		}

		/*tabs tours*/
		if ( 'tpgb/tp-tabs-tours' === $blockname && ! empty( $options ) && ! empty( $options['tabLayout'] ) && 'vertical' === $options['tabLayout'] ) {
			$this->transient_blocks[] = 'tpx-tabs-tours-vertical';
		}

		/*Post Meta info*/
		if ( 'tpgb/tp-post-meta' === $blockname ) {
			if ( ! empty( $options ) && ! empty( $options['metaLayout'] ) && 'layout-2' === $options['metaLayout'] ) {
				$this->transient_blocks[] = 'tpx-tp-post-layout-2';
			}
			if ( ! isset( $options['showCategory'] ) || ( ! empty( $options ) && true === $options['showCategory'] ) ) {
				$this->transient_blocks[] = 'tpx-tp-post-meta-category';
			}
		}

		/*Post author*/
		if ( 'tpgb/tp-post-author' === $blockname ) {
			if ( ! empty( $options ) && ! empty( $options['authorStyle'] ) && 'style-2' === $options['authorStyle'] ) {
				$this->transient_blocks[] = 'tpx-tp-post-author-style-2';
			}
			if ( ! isset( $options['ShowAvatar'] ) || ( ! empty( $options ) && true === $options['ShowAvatar'] ) ) {
				$this->transient_blocks[] = 'tpx-tp-post-author-avatar';
			}
			if ( ! isset( $options['ShowSocial'] ) || ( ! empty( $options ) && true === $options['ShowSocial'] ) ) {
				$this->transient_blocks[] = 'tpx-tp-post-author-social';
			}
			if ( ! isset( $options['ShowRole'] ) || ( ! empty( $options ) && true === $options['ShowRole'] ) ) {
				$this->transient_blocks[] = 'tpx-tp-post-author-role';
			}
		}

		if ( 'tpgb/tp-video' === $blockname && ! empty( $options ) && ! empty( $options['ContinueAnim'] ) ) {
			$this->transient_blocks[] = 'tpgb-plus-hover-effect';
		}
		if ( has_filter( 'tpgb_has_blocks_condition' ) ) {
			$this->transient_blocks = apply_filters( 'tpgb_has_blocks_condition', $this->transient_blocks, $options, $blockname );
		}

		// video.
		if ( 'tpgb/tp-video' === $blockname && ! empty( $options ) ) {
			if ( ! empty( $options['fallbackImage']['url'] ) ) {
				$this->transient_blocks[] = 'nxt-video-fallback-image';
			}
		}

		/* Pricing Table */
		if ( 'tpgb/tp-pricing-table' === $blockname ) {
			/* Content */
			if ( ! empty( $options ) && ! empty( $options['contentStyle'] ) ) {
				$this->transient_blocks[] = 'tpx-pricing-table-content-' . $options['contentStyle'];
			}
			/* Ribbon */
			if ( ! empty( $options ) && ! empty( $options['disRibbon'] ) ) {
				$this->transient_blocks[] = 'tpx-pricing-table-ribbon';
			}
		}

		if ( 'tpgb/tp-smooth-scroll' === $blockname && isset( $options['smNav'] ) && ! empty( $options['smNav'] ) ) {
			$this->transient_blocks[] = 'tpx-smooth-navigation';
		}

		if ( 'tpgb/tp-container' === $blockname && ! empty( $options ) && ! empty( $options['tpgbEqualHeight'] ) ) {
			$this->transient_blocks[] = 'equal-height';
		}

		return $this->transient_blocks;
	}
}

Tpgb_Get_Blocks::get_instance();

/**
 * Get post assets object.
 *
 * @since 2.0.0
 * @param mixed $post_type The post type.
 * @param int   $post_id The post id.
 */
function tpgb_get_post_assets( $post_type, $post_id ) {

	if ( ! isset( tpgb_load_data()->post_assets_objects[ $post_id ] ) ) {
		$post_obj = new Tpgb_Get_Blocks( $post_type, $post_id );
		tpgb_load_data()->post_assets_objects[ $post_id ] = $post_obj;
	}

	return tpgb_load_data()->post_assets_objects[ $post_id ];
}
