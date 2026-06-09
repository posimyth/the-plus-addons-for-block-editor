<?php
/**
 * Nexter Blocks Initialize
 *
 * Load of all the blocks.
 *
 * @since   1.0.0
 * @package TPGB
 */

// phpcs:disable Squiz.Commenting.FunctionComment.EmptyThrows
// phpcs:disable Squiz.Commenting.FunctionComment.MissingThrowsTag
// phpcs:disable Squiz.Commenting.FunctionComment.ThrowsNoFullStop
// phpcs:disable Generic.Commenting.DocComment.ShortNotCapital
// phpcs:disable Squiz.PHP.CommentedOutCode.Found
// phpcs:disable WordPress.Files.FileName

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'TPGB_ASSET_PATH', wp_upload_dir()['basedir'] . DIRECTORY_SEPARATOR . 'theplus_gutenberg' );
define( 'TPGB_ASSET_URL', Tp_Blocks_Helper::tpgb_get_upload_url() . '/theplus_gutenberg' );

/**
 * Tpgb_Core_Init_Blocks.
 *
 * @package TPGB
 */
class Tpgb_Core_Init_Blocks {

	/**
	 * Member Variable
	 *
	 * @var instance
	 */
	private static $instance;

	/**
	 * Tpgb global.
	 *
	 * @var mixed
	 */
	protected $tpgb_global = 'tpgb_global_options';

	/**
	 * Template ids.
	 *
	 * @var array
	 */
	public $template_ids = array();

	/**
	 * Got theme template.
	 *
	 * @var bool
	 */
	protected $got_theme_template = false;
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

		add_filter( 'block_categories_all', array( $this, 'tp_register_block_category' ), 9999991, 2 );

		require_once TPGB_PATH . 'classes/tp-registered-blocks.php';
		tpgb_library();

		add_action( 'enqueue_block_assets', array( $this, 'tp_block_assets' ) ); // front end load.
		add_action( 'enqueue_block_editor_assets', array( $this, 'editor_assets' ) ); // Gutenberg editor load.

		$this->tpgb_global_settings_post_meta();

		add_action( 'rest_api_init', array( $this, 'plus_register_api_hook' ) );
		add_action( 'after_setup_theme', array( $this, 'plus_add_image_size' ) );
		add_filter( 'image_resize_dimensions', array( $this, 'tpgb_thumbnail_upscale' ), 10, 6 );
		// Load Css/Js File blocks.
		if ( ! is_admin() ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_load_block_css_js' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_post_css' ) );
		}

		// Blocksy Compatibility.
		add_action( 'blocksy:pro:content-blocks:pre-output', array( $this, 'tpgb_blocksy_content_blocks' ), 10, 1 );
		add_filter( 'blocksy:pro:content-blocks:output-content', array( $this, 'tpgb_blocksy_content_output' ), 10, 2 );

		if ( ! defined( 'NEXTER_EXT' ) ) {
			// admin bar enqueue scripts.
			add_action( 'wp_footer', array( $this, 'admin_bar_enqueue_scripts' ) );
		}

		if ( class_exists( 'Astra_Target_Rules_Fields' ) ) {
			add_action( 'wp', array( $this, 'astra_custom_layouts_assets' ) );
		}

		add_filter( 'tpgb_google_font_load', array( $this, 'check_load_google_fonts' ) );
		add_filter( 'tpgb_global_css_load', array( $this, 'check_load_global_css' ) );

		if ( class_exists( 'memberpress\courses\models\Course' ) && class_exists( 'memberpress\courses\models\Lesson' ) ) {
			require_once TPGB_PATH . 'classes/extras/compatibility/class-tpgb-memberpress.php';
		}

		add_filter( 'tpgb_dashicons_icon_disable', array( $this, 'check_tpgb_dashicons_icon' ) );
		add_filter( 'tpgb_preset_import_disable', array( $this, 'check_tpgb_preset_import' ) );
		add_filter( 'nxt_qab_enable', array( $this, 'nxt_qab_enable_callback' ) );
	}

	/**
	 * Plus Image Size Gutenberg block.
	 *
	 * @since 1.0.0
	 */
	public function plus_add_image_size() {
		add_image_size( 'tp-image-grid', 700, 700, true );
	}

	/**
	 * Tpgb_thumbnail hard crop
	 *
	 * @since 1.1.3
	 * @param mixed $default The default.
	 * @param mixed $orig_w The orig w.
	 * @param mixed $orig_h The orig h.
	 * @param mixed $new_w The new w.
	 * @param mixed $new_h The new h.
	 * @param mixed $crop The crop.
	 */
	public function tpgb_thumbnail_upscale( $default, $orig_w, $orig_h, $new_w, $new_h, $crop ) { // phpcs:ignore Universal.NamingConventions.NoReservedKeywordParameterNames.stringFound,Universal.NamingConventions.NoReservedKeywordParameterNames.arrayFound, Universal.NamingConventions.NoReservedKeywordParameterNames.defaultFound

		if ( ! $crop ) {
			return null; // let the WordPress default function handle this.
		}

		$aspect_ratio = $orig_w / $orig_h;
		$size_ratio   = max( $new_w / $orig_w, $new_h / $orig_h );

		$crop_w = round( $new_w / $size_ratio );
		$crop_h = round( $new_h / $size_ratio );

		$s_x = floor( ( $orig_w - $crop_w ) / 2 );
		$s_y = floor( ( $orig_h - $crop_h ) / 2 );

		return array( 0, 0, (int) $s_x, (int) $s_y, (int) $new_w, (int) $new_h, (int) $crop_w, (int) $crop_h );
	}

	/**
	 * Gutenberg block category for Nexter Blocks.
	 *
	 * @param array  $categories Block categories.
	 * @param object $post Post object.
	 * @since 1.0.0
	 */
	public function tp_register_block_category( $categories, $post ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
		$get_whitelabel = get_option( 'tpgb_white_label' );
		$wplugin_name   = isset( $get_whitelabel ) && ! empty( $get_whitelabel['tpgb_free_plugin_name'] ) ? $get_whitelabel['tpgb_free_plugin_name'] : '';
		return array_merge(
			array(
				array(
					'slug'  => TPGB_CATEGORY,
					'title' => $wplugin_name && '' !== $wplugin_name ? $wplugin_name : __( 'Nexter Blocks', 'the-plus-addons-for-block-editor' ),
				),
			),
			$categories
		);
	}

	/**
	 * Enqueue block styles for both frontend + backend.
	 *
	 * @since 1.0.0
	 */
	public function tp_block_assets() {

		// Generate Block Editor Style and Scripts.
		if ( tpgb_library()->is_preview_mode() ) {

			if ( ! tpgb_library()->check_cache_files() ) {
				$blocks_list = tpgb_library()->plus_generate_scripts( tpgb_library()->get_plus_block_settings() );
			}

			// enqueue scripts.
			if ( tpgb_library()->check_cache_files() ) {
				$css_file = TPGB_ASSET_URL . '/theplus.min.css';
				$js_file  = TPGB_ASSET_URL . '/theplus.min.js';
			} else {
				$tpgb_url = TPGB_URL;
				if ( defined( 'TPGBP_VERSION' ) && defined( 'TPGBP_URL' ) ) {
					$tpgb_url = TPGBP_URL;
				}
				$css_file = $tpgb_url . 'assets/css/main/general/theplus.min.css';
				$js_file  = $tpgb_url . 'assets/js/main/general/theplus.min.js';
			}

			// fontawesome icon load frontend.
			$fontawesome_pro = Tp_Blocks_Helper::get_extra_option( 'fontawesome_pro_kit' );
			if ( empty( $fontawesome_pro ) || ! defined( 'TPGBP_VERSION' ) ) {
				wp_enqueue_style( 'tpgb-fontawesome', TPGB_URL . 'assets/css/extra/fontawesome.min.css', array(), TPGB_VERSION );
			}

			wp_enqueue_script(
				'tpgb-purge-js',
				TPGB_URL . 'assets/js/main/general/tpgb-purge.js',
				array(),
				TPGB_VERSION,
				true
			);

			$plus_version = get_option( 'tpgb_backend_cache_at' );
			if ( empty( $plus_version ) ) {
				$plus_version = TPGB_VERSION;
			}

			// Load Plus Style Editor Block.
			wp_enqueue_style(
				'tpgb-plus-block-editor-css',
				$css_file,
				array( 'wp-edit-blocks', 'dashicons' ),
				$plus_version
			);

		} else {
			tpgb_library()->enqueue_frontend_load();
		}

		wp_localize_script(
			'jQuery',
			'tpgb_load',
			array(
				'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
				'tpgb_nonce' => wp_create_nonce( 'tpgb-addons' ),
			)
		);
	}

	/**
	 * Enqueue block styles and scripts for backend editor.
	 *
	 * @since 1.2.0
	 */
	public function editor_assets() {

		if ( ! defined( 'TPGBP_VERSION' ) ) {
			wp_enqueue_style( 'tpgb-block-editor-css', TPGB_ASSETS_URL . 'assets/css/admin/blocks.css', array( 'wp-edit-blocks', 'dashicons' ), TPGB_VERSION );
		}
		global $pagenow;
		if ( ! defined( 'TPGBP_VERSION' ) ) {
			$scripts_dep = array( 'react', 'react-dom', 'wp-block-editor', 'wp-escape-html', 'wp-element', 'wp-wordcount', 'wp-blocks', 'wp-i18n', 'wp-plugins', 'wp-components', 'wp-api-fetch' );
			if ( 'widgets.php' !== $pagenow && 'customize.php' !== $pagenow ) {
				$scripts_dep = array_merge( $scripts_dep, array( 'wp-editor', 'wp-edit-post' ) );

				// Core — global init, shared settings. Must load first.
				wp_enqueue_script( 'tpgb-block-editor-js', TPGB_ASSETS_URL . 'assets/js/admin/blocks.js', $scripts_dep, TPGB_VERSION, false );

				// Category scripts — one file per block category.
				// Split to keep each file small so GlotPress never times out.
				$block_dep        = array_merge( $scripts_dep, array( 'tpgb-block-editor-js' ) );
				$lang_path        = TPGB_PATH . 'languages/';
				$js_url           = TPGB_ASSETS_URL . 'assets/js/admin/';
				$css_url          = TPGB_ASSETS_URL . 'assets/css/admin/';
				$block_categories = array(
					'blocks-essential',
					'blocks-advanced',
					'blocks-creative',
					'blocks-tabbed',
					'blocks-builder',
					'blocks-social',
					'blocks-listing',
				);

				foreach ( $block_categories as $cat ) {
					$handle = 'tpgb-' . $cat . '-js';
					wp_enqueue_script( $handle, $js_url . $cat . '.js', $block_dep, TPGB_VERSION, false );
					wp_enqueue_style( 'tpgb-' . $cat . '-css', $css_url . $cat . '.css', array( 'tpgb-block-editor-css' ), TPGB_VERSION );
					wp_set_script_translations( $handle, 'the-plus-addons-for-block-editor', $lang_path );
				}

				wp_set_script_translations( 'tpgb-block-editor-js', 'the-plus-addons-for-block-editor', TPGB_PATH . 'languages/' );
			}
		}

		if ( 'widgets.php' !== $pagenow && 'customize.php' !== $pagenow ) {
			wp_enqueue_script( 'tpgb-deactivate-block-js', TPGB_ASSETS_URL . 'assets/js/admin/blocks.deactivate.min.js', array( 'wp-blocks' ), TPGB_VERSION, true );
		}

		// WP Localized globals.
		$google_map_enable = Tp_Blocks_Helper::get_extra_option( 'gmap_api_switch' );
		$google_map_api    = '';
		if ( ! empty( $google_map_enable ) && 'enable' === $google_map_enable || 'disable' === $google_map_enable ) { // phpcs:ignore Generic.CodeAnalysis.RequireExplicitBooleanOperatorPrecedence.MissingParentheses
			$google_map_api = Tp_Blocks_Helper::get_extra_option( 'googlemap_api' );
		}

		if ( empty( trim( $google_map_api ) ) ) {
			$google_map_api = 'AIzaSyA_ez85P6duaw7IrvfeK8LmRxLZPdLG7gs';
		}

		$google_fonts = apply_filters( 'tpgb_google_font_load', true );
		$global_css   = apply_filters( 'tpgb_global_css_load', true );
		$dash_icons   = apply_filters( 'tpgb_dashicons_icon_disable', true );

		$google_fonts_list = apply_filters( 'tpgb_custom_fonts_list', array() );
		if ( empty( $google_fonts_list ) ) {
			$google_fonts_list = false;
		}

		$preset_import = Tp_Blocks_Helper::get_extra_option( 'preset_import' );

		// Check WDesignkit Installed Or Not.
		$wdadded = false;
		include_once ABSPATH . 'wp-admin/includes/plugin.php';
		$pluginslist = get_plugins();
		if ( isset( $pluginslist['wdesignkit/wdesignkit.php'] ) && ! empty( $pluginslist['wdesignkit/wdesignkit.php'] ) ) {
			if ( is_plugin_active( 'wdesignkit/wdesignkit.php' ) ) {
				$wdadded = true;
			}
		}

		$preset_import = apply_filters( 'tpgb_preset_import_disable', true );

		$nxt_ai_settings     = array();
		$nxt_ai_settings_raw = Tp_Blocks_Helper::get_extra_option( 'nxtAiSettings' );
		$encrypted           = '';

		if ( is_string( $nxt_ai_settings_raw ) && '' !== $nxt_ai_settings_raw ) {
			$encrypted = $nxt_ai_settings_raw;
		} elseif ( is_array( $nxt_ai_settings_raw ) && ! empty( $nxt_ai_settings_raw ) ) {

			if ( isset( $nxt_ai_settings_raw[0] ) && is_string( $nxt_ai_settings_raw[0] ) ) {
				$encrypted = $nxt_ai_settings_raw[0];
			} elseif ( count( $nxt_ai_settings_raw ) === 1 ) {
				$value = reset( $nxt_ai_settings_raw );
				if ( is_string( $value ) && '' !== $value ) {
					$encrypted = $value;
				}
			}
		}

		// Decrypt ONLY if we truly have a value.
		if ( '' !== $encrypted ) {
			$decrypted = Tp_Blocks_Helper::tpgb_simple_decrypt( $encrypted, 'dy' );
			$decoded   = json_decode( $decrypted, true );

			// Accept only valid decoded arrays.
			if ( is_array( $decoded ) ) {
				$nxt_ai_settings = $decoded;
			}
		}

		$wp_localize_tpgb = array(
			'activeTheme'            => esc_html( get_template() ),
			'category'               => TPGB_CATEGORY,
			'activated_blocks'       => Tp_Blocks_Helper::get_block_enabled( array() ),
			'deactivated_blocks'     => Tp_Blocks_Helper::get_block_deactivate(),
			'post_type_list'         => Tp_Blocks_Helper::get_post_type_list(),
			'plugin_url'             => TPGB_ASSETS_URL,
			'admin_url'              => esc_url( admin_url() ),
			'home_url'               => home_url(),
			'block_icon_url'         => esc_url( TPGB_ASSETS_URL . 'assets/images/block-icons' ),
			'ajax_url'               => esc_url( admin_url( 'admin-ajax.php' ) ),
			'image_sizes'            => Tp_Blocks_Helper::get_image_sizes(),
			'googlemap_api'          => $google_map_api,
			'googlefont_load'        => $google_fonts,
			'globalcss_load'         => $global_css,
			'googlefont_list'        => $google_fonts_list,
			'fontawesome'            => false,
			'contactform_list'       => Tp_Blocks_Helper::get_contact_form_post(),
			'everestform_list'       => Tp_Blocks_Helper::get_everest_form_post(),
			'gravityform_list'       => Tp_Blocks_Helper::get_gravity_form_post(),
			'ninjaform_list'         => Tp_Blocks_Helper::get_ninja_form_post(),
			'wpform_list'            => Tp_Blocks_Helper::get_wpforms_form_post(),
			'preview_image'          => esc_url( TPGB_URL . 'assets/images/tpgb-placeholder.jpg' ),
			'preview_grid_image'     => esc_url( TPGB_URL . 'assets/images/tpgb-placeholder-grid.jpg' ),
			'taxonomy_list'          => Tp_Blocks_Helper::tpgb_get_post_taxonomies(),
			'custom_font'            => Tp_Blocks_Helper::tpgb_custom_font(),
			'tpgb_extra_opt'         => Tp_Blocks_Helper::get_extra_opt_enabled(),
			'pluginnonce'            => wp_create_nonce( 'nexter_admin_nonce' ),
			'WDesignkit_in'          => $wdadded,
			'dashicons_icon'         => $dash_icons,
			'nexter_block_pro'       => defined( 'TPGBP_VERSION' ),
			'adminEmail'             => current_user_can( 'manage_options' ) ? get_option( 'admin_email' ) : '',
			'preset_import'          => $preset_import,
			'qab'                    => apply_filters( 'nxt_qab_enable', true ),
			'site_name'              => get_bloginfo( 'name' ),
			'isNxtAiText'            => ( ! empty( $nxt_ai_settings['geminiEnableText'] ) && ! empty( $nxt_ai_settings['geminiApiKey'] ) ) || ( ! empty( $nxt_ai_settings['chatgptEnableText'] ) && ! empty( $nxt_ai_settings['chatgptApiKey'] ) ) ? '1' : '0',
			'isNxtAiImage'           => ( ! empty( $nxt_ai_settings['geminiEnableImage'] ) && ! empty( $nxt_ai_settings['geminiApiKey'] ) ) || ( ! empty( $nxt_ai_settings['chatgptEnableImage'] ) && ! empty( $nxt_ai_settings['chatgptApiKey'] ) ) ? '1' : '0',
			'isGemini'               => ! empty( $nxt_ai_settings['geminiEnableImage'] ) && ! empty( $nxt_ai_settings['geminiApiKey'] ) ? '1' : '0',
			'isAiTextApi'            => ! empty( $nxt_ai_settings['geminiApiKey'] ) || ! empty( $nxt_ai_settings['chatgptApiKey'] ) ? '1' : '0',
			'isAiIntegrationEnabled' => ! empty( $nxt_ai_settings['aiIntegrationEnabled'] ) ? '1' : '0',
		);

		if ( has_filter( 'tpgb_load_localize' ) ) {
			$wp_localize_tpgb = apply_filters( 'tpgb_load_localize', $wp_localize_tpgb );
		}

		wp_localize_script( 'tpgb-block-editor-js', 'tpgb_blocks_load', $wp_localize_tpgb );
	}

	/**
	 * Plus register api hook.
	 *
	 * @return mixed The result.
	 */
	public function plus_register_api_hook() {

		$post_types = get_post_types();

		// Update ThePlus Global Options.
		register_rest_route(
			'tpgb/v1',
			'/theplus_global_settings/',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'tpgb_get_global_settings' ),
					'permission_callback' => function () {
						return current_user_can( 'edit_posts' );
					},
					'args'                => array(),
				),
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'tpgb_update_global_settings' ),
					'permission_callback' => function ( WP_REST_Request $request ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
						return current_user_can( 'edit_posts' );
					},
					'args'                => array(),
				),
			)
		);

		// Get Post Content by ID.
		register_rest_route(
			'tpgb/v1',
			'/tpgb_get_content/',
			array(
				array(
					'methods'             => 'POST',
					'callback'            => array( $this, 'tpgb_get_post_content' ),
					'permission_callback' => function () {
						return current_user_can( 'edit_posts' );
					},
					'args'                => array(),
				),
			)
		);

		// ThePlus Save Block Css file.
		register_rest_route(
			'the-plus-addons-for-block-editor/v1',
			'/plus_save_block_css/',
			array(
				array(
					'methods'             => 'POST',
					'callback'            => array( $this, 'plus_save_block_css' ),
					'permission_callback' => function ( WP_REST_Request $request ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
						return current_user_can( 'edit_posts' );
					},
					'args'                => array(),
				),
			)
		);

		// post type featured image.
		register_rest_field(
			$post_types,
			'tpgb_featured_images',
			array(
				'get_callback'    => array( $this, 'tpgb_get_featured_image_url' ),
				'update_callback' => null,
				'schema'          => array(
					'description' => __( 'Nexter Blocks Different sized of featured images', 'the-plus-addons-for-block-editor' ),
					'type'        => 'array',
				),
			)
		);

		// Post Type Meta Info.
		register_rest_field(
			$post_types,
			'tpgb_post_meta_info',
			array(
				'get_callback'    => array( $this, 'tpgb_get_post_meta_info' ),
				'update_callback' => null,
				'schema'          => array(
					'description' => __( 'Post Listing of get Post Meta Info.', 'the-plus-addons-for-block-editor' ),
					'type'        => 'array',
				),
			)
		);

		// POST Category Lists.
		register_rest_field(
			$post_types,
			'tpgb_post_category',
			array(
				'get_callback'    => array( $this, 'tpgb_get_category_list' ),
				'update_callback' => null,
				'schema'          => array(
					'description' => __( 'Category list links', 'the-plus-addons-for-block-editor' ),
					'type'        => 'string',
				),
			)
		);

		/**
		 * Rest api Product Info
		 *
		 * @since 1.1.2
		 */
		register_rest_field(
			'product',
			'tpgb_product_data',
			array(
				'get_callback'    => array( $this, 'tpgb_get_product_data' ),
				'update_callback' => null,
				'schema'          => array(
					'description' => __( 'Product Data.', 'the-plus-addons-for-block-editor' ),
					'type'        => 'array',
				),
			)
		);

		// Get Terms List.
		register_rest_route(
			'tpgb/v1',
			'/tpgb_get_taxolist/',
			array(
				array(
					'methods'             => 'POST',
					'callback'            => array( $this, 'tpgb_get_taxonomy_list' ),
					'permission_callback' => function () {
						return current_user_can( 'edit_posts' );
					},
					'args'                => array(),
				),
			)
		);

		if ( class_exists( 'ACF' ) ) {
			// Get ACF Field.
			register_rest_route(
				'tpgb/v1',
				'/tpgb_get_Acf_Field/',
				array(
					array(
						'methods'             => 'POST',
						'callback'            => array( $this, 'tpgb_get_Acf_Field' ),
						'permission_callback' => function () {
							return current_user_can( 'edit_posts' );
						},
						'args'                => array(),
					),
				)
			);
		}
	}

	/**
	 * Build Category Tree
	 *
	 * @since 1.2.3
	 * @param array $items The items.
	 */
	public static function tpgb_build_category_tree( $items ) {
		$childs = array();
		foreach ( $items as &$item ) {
			if ( isset( $item->parent ) ) {
				$childs[ $item->parent ][] = & $item;
			}
			unset( $item );
		}
		foreach ( $items as &$item ) {
			if ( isset( $item->term_id ) && isset( $childs[ $item->term_id ] ) ) {
				$item->child = $childs[ $item->term_id ];
			}
		}
		return ( isset( $childs[0] ) ) ? $childs[0] : array();
	}

	/**
	 * API call Get Terms Hierarchy
	 *
	 * @since 1.2.3
	 * @param array $params The params.
	 */
	public function tpgb_get_taxonomy_list( $params ) {
		$cat_data = array();
		if ( ! empty( $params ) && ! empty( $params['texonomy'] ) ) {
			$cat_args   = array(
				'taxonomy'     => $params['texonomy'],
				'hide_empty'   => true,
				'hierarchical' => true,
			);
			$categories = get_terms( $cat_args );
			if ( $categories ) {
				$cat_data = self::tpgb_build_category_tree( (array) $categories );
			}
		}
		return $cat_data;
	}

	/**
	 * Add post meta tpgb
	 */
	public function tpgb_global_settings_post_meta() {
		register_meta(
			'post',
			'tpgb_global_settings',
			array(
				'show_in_rest' => true,
				'single'       => true,
				'type'         => 'string',
			)
		);
	}

	/**
	 * API call Get ThePlus Global Options
	 *
	 * @since 1.0.0
	 */
	public function tpgb_get_global_settings() {
		try {

			$plus_settings = get_option( $this->tpgb_global );

			$plus_settings = ( false === $plus_settings ) ? json_decode( '{}' ) : json_decode( $plus_settings );

			$tpgb_save_global_style = get_option( 'tpgb-block-global-style' );

			$tpgb_save_global_style = ( false === $tpgb_save_global_style ) ? json_decode( '{}' ) : json_decode( $tpgb_save_global_style );

			return array(
				'success'            => true,
				'settings'           => $plus_settings,
				'block_global_style' => $tpgb_save_global_style,
			);
		} catch ( Exception $e ) {
			return array(
				'success' => false,
				'message' => $e->getMessage(),
			);
		}
	}

	/**
	 * API call Get Post Content
	 *
	 * @since 1.1.2
	 * @param mixed $request The request.
	 */
	public function tpgb_get_post_content( $request ) {
		$params = $request->get_params();
		try {
			if ( isset( $params['post_id'] ) ) {
				$post_data = get_post( $params['post_id'] );
				if ( ! $post_data || 'publish' !== $post_data->post_status || post_password_required( $post_data ) ) {
					$content = '';
				} elseif ( $post_data && get_post_status( $post_data ) === 'publish' ) {
					$content = isset( $post_data->post_content ) ? $post_data->post_content : '';
				}

				return array(
					'success' => true,
					'data'    => $content,
					'message' => 'Get Success!!',
				);
			}
		} catch ( Exception $e ) {
			return array(
				'success' => false,
				'message' => $e->getMessage(),
			);
		}
	}

	/**
	 * API call Update ThePlus Global Options
	 *
	 * @since 1.0.0
	 * @param mixed $request The request.
	 * @throws \Exception May throw exception. // phpcs:ignore Squiz.Commenting.FunctionComment.EmptyThrows.
	 */
	public function tpgb_update_global_settings( $request ) {
		try {
			$params = $request->get_params();
			if ( isset( $params['settings'] ) ) {
				$plus_settings = $params['settings'];

				if ( get_option( $this->tpgb_global ) === false ) {
					add_option( $this->tpgb_global, $plus_settings );
				} else {
					update_option( $this->tpgb_global, $plus_settings );
				}
			} elseif ( isset( $params['block_global_style'] ) ) {
				$global_style = $params['block_global_style'];
				if ( get_option( 'tpgb-block-global-style' ) === false ) {
					add_option( 'tpgb-block-global-style', $global_style );
				} else {
					update_option( 'tpgb-block-global-style', $global_style );
				}
			} else {
				throw new Exception( __( 'Settings parameter is missing!', 'the-plus-addons-for-block-editor' ) );
			}

			return array(
				'success' => true,
				'message' => __( 'Nexter Global settings updated!', 'the-plus-addons-for-block-editor' ),
			);
		} catch ( Exception $e ) {
			return array(
				'success' => false,
				'message' => $e->getMessage(),
			);
		}
	}

	/**
	 * Update PostMeta Value
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
	 * @param bool   $is_editor The is editor.
	 */
	public function update_posts_metadata( $post_id = '', $meta_key = '', $update_key = '', $val = '', $is_editor = false ) {
		if ( '' !== $post_id && is_numeric( $post_id ) && ! empty( $update_key ) ) {

			if ( is_404() || is_search() || 0 === $post_id ) {
				$old_value = get_option( 'theplus-term-' . $post_id );
			} elseif ( ! is_singular() && false === $is_editor ) {
				$old_value = get_term_meta( $post_id, $meta_key, true );
			} else {
				$old_value = get_post_meta( $post_id, $meta_key, true );
			}

			$old_value                = ( is_array( $old_value ) ) ? $old_value : array();
			$old_value[ $update_key ] = $val;

			if ( is_404() || is_search() || 0 === $post_id ) {
				update_option( 'theplus-term-' . $post_id, $old_value );
			} elseif ( ! is_singular() && false === $is_editor ) {
				update_term_meta( $post_id, $meta_key, $old_value );
			} else {
				update_post_meta( $post_id, $meta_key, $old_value );
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
	 * @return mixed The result.
	 */
	public function get_posts_metadata( $post_id = '', $meta_key = '', $get_key_val = '' ) {
		$old_value = '';
		$value     = '';
		if ( is_singular() && '' !== $post_id && is_numeric( $post_id ) ) {
			$old_value = get_post_meta( $post_id, $meta_key, true );
		} elseif ( is_404() || is_search() || 0 === $post_id ) {
			$old_value = get_option( 'theplus-term-' . $post_id );
		} elseif ( ! is_singular() && is_numeric( $post_id ) ) {
			$old_value = get_term_meta( $post_id, $meta_key, true );
		}

		if ( ! empty( $old_value ) && is_array( $old_value ) && isset( $old_value[ $get_key_val ] ) ) {
			$value = $old_value[ $get_key_val ];
		} elseif ( ! empty( $old_value ) && ! is_array( $old_value ) ) {
			$value = $old_value;
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
	 */
	public function remove_posts_metadata( $post_id = '', $meta_key = '', $get_key_val = '' ) {
		$value = array();
		if ( is_singular() && '' !== $post_id && is_numeric( $post_id ) ) {
			$value = get_post_meta( $post_id, $meta_key, true );
		} elseif ( is_404() || is_search() || 0 === $post_id ) {
			$value = get_option( 'theplus-term-' . $post_id );
		} elseif ( ! is_singular() && is_numeric( $post_id ) ) {
			$value = get_term_meta( $post_id, $meta_key, true );
		}

		if ( ! empty( $value ) && is_array( $value ) && isset( $value[ $get_key_val ] ) ) {
			unset( $value[ $get_key_val ] );
			if ( is_singular() && '' !== $post_id && is_numeric( $post_id ) ) {
				update_post_meta( $post_id, $meta_key, $value );
			} elseif ( is_404() || is_search() || 0 === $post_id ) {
				update_option( 'theplus-term-' . $post_id, $value );
			} elseif ( ! is_singular() && is_numeric( $post_id ) ) {
				update_term_meta( $post_id, $meta_key, $value );
			}
		}
	}

	/**
	 * Save block css
	 *
	 * @since 2.0.0
	 * @param mixed $request The request.
	 * @throws \Exception May throw exception. // phpcs:ignore Squiz.Commenting.FunctionComment.EmptyThrows.
	 */
	public function plus_save_block_css( $request ) {
		try {
			global $wp_filesystem;
			if ( ! $wp_filesystem ) {
				if ( ! function_exists( 'WP_Filesystem' ) ) {
					require_once wp_normalize_path( ABSPATH . '/wp-admin/includes/file.php' );
				}
			}

			$params     = $request->get_params();
			$is_preview = isset( $params['is_preview'] ) ? $params['is_preview'] : false;
			$post_id    = (int) sanitize_text_field( $params['post_id'] );

			if ( $params['is_global'] ) {
				$global_css     = ( ! empty( $params['global_css'] ) ) ? $params['global_css'] : '';
				$globalfilename = 'plus-global.css';

				$upload_dir = wp_upload_dir();
				$dir        = trailingslashit( $upload_dir['basedir'] ) . 'theplus_gutenberg/';

				$import_global_css = array();
				if ( ! empty( $params['is_global'] ) && true === $params['is_global'] && ! empty( $global_css ) ) {
					$import_global_css = $this->exclude_gfont_block_css( $global_css );
				}

				if ( true === $is_preview ) {
					$globalfilename = 'plus-global-preview.css';
				} else {
					update_option( '_tpgb_global_css', $import_global_css );
				}

				WP_Filesystem( false, $upload_dir['basedir'], true );

				if ( ! $wp_filesystem->is_dir( $dir ) ) {
					$wp_filesystem->mkdir( $dir );
				}

				if ( ! empty( $params['is_global'] ) && true === $params['is_global'] && isset( $import_global_css['css'] ) ) {
					if ( ! $wp_filesystem->put_contents( $dir . $globalfilename, $import_global_css['css'] ) ) {
						throw new Exception( __( 'CSS can not be load due to permission!!!', 'the-plus-addons-for-block-editor' ) );
					}
				}
			}
			if ( $params['is_block'] ) {
				$block_css = $params['block_css'];
				$filename  = "plus-css-{$post_id}.css";

				$upload_dir = wp_upload_dir();
				$dir        = trailingslashit( $upload_dir['basedir'] ) . 'theplus_gutenberg/';
				$import_css = array();
				if ( ! empty( $block_css ) ) {
					$import_css = $this->exclude_gfont_block_css( $block_css );
				}

				if ( true === $is_preview ) {
					$filename = "plus-preview-{$post_id}.css";
				} else {
					update_post_meta( $post_id, '_tpgb_css', $import_css );
					$this->update_posts_metadata( $post_id, '_block_css', 'version', time(), true );
					$this->delete_post_dynamic( $post_id, true );
				}

				WP_Filesystem( false, $upload_dir['basedir'], true );

				if ( ! $wp_filesystem->is_dir( $dir ) ) {
					$wp_filesystem->mkdir( $dir );
				}
				if ( ! empty( $import_css ) && isset( $import_css['css'] ) ) {
					if ( ! $wp_filesystem->put_contents( $dir . $filename, $import_css['css'] ) ) {
						throw new Exception( __( 'CSS can not be load due to permission!!!', 'the-plus-addons-for-block-editor' ) );
					}
				}
			} else {
				delete_post_meta( $post_id, '_tpgb_css' );
				$this->remove_posts_metadata( $post_id, '_block_css', 'version' );
				$this->delete_post_dynamic( $post_id );
			}

			// set block meta.
			if ( false === $is_preview ) {

				// Clear Litespeed cache.
				if ( method_exists( 'Purge', 'purge_all' ) ) {
					Purge::purge_all();
				} elseif ( method_exists( 'LiteSpeed_Cache_API', 'purge_all' ) ) {
					LiteSpeed_Cache_API::purge_all();
				}

				// berqCache Cache.
				if ( class_exists( 'berqCache' ) && method_exists( 'berqCache', 'purge_page' ) ) {
					$post_url = get_permalink( $post_id );
					berqCache::purge_page( $post_url, true );
				}

				// Purge WP-Optimize.
				if ( class_exists( 'WP_Optimize' ) ) {
					$wpop = new WP_Optimize();
					if ( is_callable( array( $wpop, 'get_page_cache' ) ) ) {
						WP_Optimize()->get_page_cache()->purge();
					}
				}

				// Site ground.
				if ( class_exists( 'SG_CachePress_Supercacher' ) && method_exists( 'SG_CachePress_Supercacher ', 'purge_cache' ) ) {
					SG_CachePress_Supercacher::purge_cache( true );
				}

				// W3 Total Cache.
				if ( function_exists( 'w3tc_flush_all' ) ) {
					w3tc_flush_all();
				}

				// WP Fastest Cache.
				/**
				if ( ! empty( $GLOBALS['wp_fastest_cache'] ) && method_exists( $GLOBALS['wp_fastest_cache'], 'deleteCache' ) ) { // phpcs:ignore Generic.Commenting.DocComment.ShortNotCapital,Generic.Commenting.DocComment.LongNotCapital,Generic.Commenting.DocComment.MissingShort
				$GLOBALS['wp_fastest_cache']->deleteCache(true);
				} */

				// WP Super Cache.
				if ( function_exists( 'wp_cache_clean_cache' ) ) {
					global $file_prefix;
					wp_cache_clean_cache( $file_prefix, true );
				}

				// Purge WP Engine.
				if ( class_exists( 'WpeCommon' ) ) {
					if ( method_exists( 'WpeCommon', 'purge_memcached' ) ) {
						WpeCommon::purge_memcached();
					}
					if ( method_exists( 'WpeCommon', 'clear_maxcdn_cache' ) ) {
						WpeCommon::clear_maxcdn_cache();
					}
					if ( method_exists( 'WpeCommon', 'purge_varnish_cache' ) ) {
						WpeCommon::purge_varnish_cache();
					}
				}

				// Purge Pagely.
				if ( class_exists( 'PagelyCachePurge' ) ) {
					$purge_pagely = new PagelyCachePurge();
					if ( is_callable( array( $purge_pagely, 'purgeAll' ) ) ) {
						$purge_pagely->purgeAll();
					}
				}

				if ( function_exists( 'rocket_clean_post' ) ) {
					rocket_clean_post( $post_id );
				}
				if ( function_exists( 'rocket_clean_minify' ) ) {
					rocket_clean_minify();
				}

				$all_clear_cache = array(
					'W3 Total Cache'    => 'w3tc_pgcache_flush',
					// 'WP Fastest Cache' => 'wpfc_clear_all_cache', // phpcs:ignore Squiz.PHP.CommentedOutCode.Found
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
				return array(
					'success' => true,
					'message' => __( 'Plus block css updated.', 'the-plus-addons-for-block-editor' ),
				);
			} else {
				return array(
					'success' => true,
					'message' => __( 'Plus block preview css updated.', 'the-plus-addons-for-block-editor' ),
				);
			}
		} catch ( Exception $e ) {
			return array(
				'success' => false,
				'message' => $e->getMessage(),
			);
		}
	}

	/**
	 * Make Dynamic Block Css By Post ID
	 *
	 * @since 1.1.3
	 * @param string $post_id The post id.
	 * @param array  $dependency The dependency.
	 * @throws \Exception May throw exception. // phpcs:ignore Squiz.Commenting.FunctionComment.EmptyThrows.
	 */
	public function make_block_css_by_post_id( $post_id = '', $dependency = array( 'tpgb-plus-block-front-css' ) ) {
		if ( ! empty( $post_id ) || 0 === $post_id ) {

			global $wp_filesystem;
			if ( ! $wp_filesystem ) {
				if ( ! function_exists( 'WP_Filesystem' ) ) {
					require_once wp_normalize_path( ABSPATH . '/wp-admin/includes/file.php' );
				}
			}

			$filename   = "plus-css-{$post_id}.css";
			$upload_dir = wp_upload_dir();
			$dir        = trailingslashit( $upload_dir['basedir'] ) . 'theplus_gutenberg/';
			$block_css  = '';
			if ( class_exists( 'Tpgb_Generate_Blocks_Css' ) ) {
				$generate_class = new Tpgb_Generate_Blocks_Css();
				$block_css      = $generate_class->generate_dynamic_css( $post_id );
			}
			if ( ! empty( $block_css ) ) {
				$import_css = $this->exclude_gfont_block_css( $block_css );

				update_post_meta( $post_id, '_tpgb_css', $import_css );

				WP_Filesystem( false, $upload_dir['basedir'], true );

				if ( ! $wp_filesystem->is_dir( $dir ) ) {
					$wp_filesystem->mkdir( $dir );
				}

				if ( ! empty( $import_css ) && isset( $import_css['font_link'] ) && ! empty( $import_css['font_link'] ) ) {
					$this->tpgb_load_google_fonts( $post_id, $import_css['font_link'] );
				}

				if ( isset( $import_css['css'] ) && ! $wp_filesystem->put_contents( $dir . $filename, $import_css['css'] ) ) {
					throw new Exception( esc_html__( 'CSS can not be load due to permission!!!', 'the-plus-addons-for-block-editor' ) );
				} else {
					$css_path = $dir . $filename;
					if ( ! $this->is_editor_screen() && $wp_filesystem->exists( $css_path ) ) {
						$css_url      = Tp_Blocks_Helper::tpgb_get_upload_url() . 'theplus_gutenberg/' . $filename;
						$plus_version = time();
						$this->update_posts_metadata( $post_id, '_block_css', 'version', $plus_version );
						wp_enqueue_style( "plus-post-{$post_id}", esc_url( $css_url ), $dependency, $plus_version );
					}
				}
			}
		}
	}

	/**
	 * Check Load Google Font In Nexter
	 *
	 * @since 2.0.0
	 * */
	public function check_load_google_fonts() {
		$check_gfont_load = Tp_Blocks_Helper::get_extra_option( 'gfont_load' );
		if ( ! empty( $check_gfont_load ) && 'disable' === $check_gfont_load ) {
			return false;
		}
		return true;
	}

	/** // phpcs:ignore Squiz.Commenting.FunctionComment, Generic.Commenting.DocComment.ShortNotCapital,Generic.Commenting.DocComment.LongNotCapital,Generic.Commenting.DocComment.MissingShort
	 * Check Global CSS In Nexter
	 *
	 * @since 2.0.9
	 * */
	public function check_load_global_css( $data = true ) {
		$check_global_css = Tp_Blocks_Helper::get_extra_option( 'gbl_css' );
		if ( ! empty( $check_global_css ) && 'disable' === $check_global_css ) {
			$data = false;
		}
		return $data;
	}

	/**
	 * Load Google Font Post Css
	 *
	 * @since 2.0.0
	 * @param string $post_id The post id.
	 * @param string $font_link The font link.
	 */
	public function tpgb_load_google_fonts( $post_id = '', $font_link = '' ) {

		$load_google_fonts = apply_filters( 'tpgb_google_font_load', true );

		if ( ! $load_google_fonts || empty( $font_link ) ) {
			return;
		}

		$extra_attr = '';

		$subsets = apply_filters( 'tpgb_font_subset', array() );
		if ( ! empty( $subsets ) ) {
			$extra_attr .= '&subset=' . implode( ',', $subsets );
		} else {
			$extra_attr .= '&subset=latin';
		}

		$display = apply_filters( 'tpgb_font_display', 'swap' );
		if ( ! empty( $display ) ) {
			$extra_attr .= '&display=' . $display;
		}

		if ( ! empty( $font_link ) ) {
			wp_enqueue_style( 'tpgb-gfonts-' . $post_id, $font_link . $extra_attr, array(), TPGB_VERSION, 'all' );
		}
	}

	/**
	 * Frontend Enqueue Scripts
	 *
	 * @since 2.0.0
	 **/
	/**
	 * Enqueue load block css js.
	 *
	 * @param bool $check_load The check load.
	 */
	public function enqueue_load_block_css_js( $check_load = false ) {
		$caching_opt = get_option( 'tpgb_performance_cache' );
		if ( class_exists( 'Tpgb_Library' ) && ! empty( $caching_opt ) && 'separate' === $caching_opt && ! $check_load ) {
			$library = Tpgb_Library::get_instance();
			if ( ! empty( $library ) && ! empty( $library->plus_uid ) && ! empty( $library->requires_update ) ) {
				return;
			}
		}

		$post_id          = $this->is_tpgb_post_id();
		$upload_dir       = wp_get_upload_dir();
		$upload_base_dir  = trailingslashit( $upload_dir['basedir'] );
		$css_path         = $upload_base_dir . "theplus_gutenberg/plus-css-{$post_id}.css";
		$preview_css_path = $upload_base_dir . "theplus_gutenberg/plus-preview-{$post_id}.css";

		$plus_version = $this->get_posts_metadata( $post_id, '_block_css', 'version' );
		if ( empty( $plus_version ) ) {
			$plus_version = time();
			$this->update_posts_metadata( $post_id, '_block_css', 'version', $plus_version );
		}

		$plus_css = get_post_meta( $post_id, '_tpgb_css', true );
		if ( ! empty( $plus_css ) && isset( $plus_css['font_link'] ) && ! empty( $plus_css['font_link'] ) ) {
			$this->tpgb_load_google_fonts( $post_id, $plus_css['font_link'] );
		}

		$css_file_url = Tp_Blocks_Helper::tpgb_get_upload_url();
		if ( isset( $_GET['preview'] ) && true === $_GET['preview'] && file_exists( $preview_css_path ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if ( file_exists( $preview_css_path ) ) {
				$css_url = $css_file_url . "theplus_gutenberg/plus-preview-{$post_id}.css";

				if ( ! $this->is_editor_screen() ) {
					wp_enqueue_style( "plus-preview-{$post_id}", esc_url( $css_url ), false, $plus_version . time() );
				}
			}
		} elseif ( file_exists( $css_path ) ) {
				$css_url = $css_file_url . "theplus_gutenberg/plus-css-{$post_id}.css";

			if ( ! $this->is_editor_screen() ) {
				wp_enqueue_style( "plus-post-{$post_id}", esc_url( $css_url ), array( 'tpgb-plus-block-front-css' ), $plus_version );
			}

			if ( ! isset( $_GET['preview'] ) && empty( $_GET['preview'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$css_preview_path = $upload_base_dir . "theplus_gutenberg/plus-preview-{$post_id}.css";
				if ( file_exists( $css_preview_path ) ) {
					wp_delete_file( $css_preview_path );
				}
			}
		} elseif ( ! file_exists( $css_path ) ) {
			$this->make_block_css_by_post_id( $post_id );
		}

		// block templates get_block_templates.
		if ( function_exists( 'wp_is_block_theme' ) && wp_is_block_theme() ) {
			foreach ( $this->get_block_template_names() as $template => $conditional ) {
				if ( $this->got_theme_template ) {
					break;
				}
				$get_template = "get_{$template}_template";

				if ( function_exists( $conditional ) && function_exists( $get_template ) && call_user_func( $conditional ) ) {
					$this->got_theme_template = true;
					$filter                   = str_replace( '_', '', "{$template}" );
					add_filter( "{$filter}_template", array( $this, 'filter_template' ), PHP_INT_MAX, 3 );
					call_user_func( $get_template );
					remove_filter( "{$filter}_template", array( $this, 'filter_template' ), PHP_INT_MAX );
				}
			}
		}

		// third party plugins compatibility.
		$this->tpgb_compatibility_plugins();
	}

	/**
	 * Compatibility of plugins
	 *
	 * @since 2.0.0
	 */
	public function tpgb_compatibility_plugins() {

		// GeneratePress GP Premium Templates Compatibility.
		global $generate_elements;
		if ( class_exists( 'GeneratePress_Elements_Helper' ) && ! empty( $generate_elements ) ) {
			foreach ( (array) $generate_elements as $key => $data ) {
				$this->enqueue_post_css( $key );
			}
		}

		// LearnPress Lesson Compatibility.
		global $lp_course_item;
		if ( class_exists( 'LearnPress' ) && ! empty( $lp_course_item ) ) {
			if ( $lp_course_item->get_id() ) {
				$this->enqueue_post_css( $lp_course_item->get_id() );
			}
		}

		// kadence Plugin Compatibility.
		if ( ! ( is_admin() || is_singular( 'kadence_element' ) || is_singular( 'kadence_wootemplate' ) ) ) {
			if ( class_exists( 'Kadence_Pro' ) || class_exists( 'Kadence_Pro\Elements_Controller' ) || class_exists( 'Kadence_Pro\Elements_Post_Type_Controller' ) ) {
				require_once TPGB_PATH . 'classes/extras/compatibility/class-kadence-theme.php';
			}
		}
	}

	/**
	 * Frontend Reusable Block Load Css
	 *
	 * @since 2.0.0
	 * @param int $post_id The post id.
	 */
	public function tpgb_reusable_block_css( $post_id ) {
		if ( $post_id && class_exists( 'Tpgb_Get_Blocks' ) ) {
			$post_type    = ( is_singular() ? 'post' : 'term' );
			$load_enqueue = tpgb_get_post_assets( $post_type, $post_id );
			if ( isset( $load_enqueue->templates_ids ) && ! empty( $load_enqueue->templates_ids ) && is_array( $load_enqueue->templates_ids ) ) {
				$res_id = array_unique( $load_enqueue->templates_ids );
				foreach ( $res_id as $value ) {
					$this->enqueue_post_css( $value );
				}
			}
		}
	}

	/**
	 * Get block template names.
	 *
	 * @return mixed The result.
	 */
	public function get_block_template_names() {
		$names = array();

		$names['embed']             = 'is_embed';
		$names['404']               = 'is_404';
		$names['search']            = 'is_search';
		$names['front_page']        = 'is_front_page';
		$names['home']              = 'is_home';
		$names['privacy_policy']    = 'is_privacy_policy';
		$names['post_type_archive'] = 'is_post_type_archive';
		$names['taxonomy']          = 'is_tax';
		$names['attachment']        = 'is_attachment';
		$names['single']            = 'is_single';
		$names['page']              = 'is_page';
		$names['singular']          = 'is_singular';
		$names['category']          = 'is_category';
		$names['tag']               = 'is_tag';
		$names['author']            = 'is_author';
		$names['date']              = 'is_date';
		$names['archive']           = 'is_archive';
		$names['index']             = '__return_true';

		return $names;
	}

	/**
	 * Filter template.
	 *
	 * @param mixed $template The template.
	 * @param mixed $type The type.
	 * @param mixed $templates The templates.
	 * @return mixed The result.
	 */
	public function filter_template( $template, $type, $templates ) {

		$block_template = self::wp_resolve_block_template( $type, $templates, $template );

		if ( ! empty( $block_template ) && isset( $block_template->wp_id ) && ! empty( $block_template->wp_id ) ) {
			$this->enqueue_post_css( $block_template->wp_id );
		}

		return $template;
	}

	/**
	 * Wp resolve block template.
	 *
	 * @param mixed $template_type The template type.
	 * @param mixed $template_hierarchy The template hierarchy.
	 * @param mixed $fallback_template The fallback template.
	 * @return mixed The result.
	 */
	protected static function wp_resolve_block_template( $template_type, $template_hierarchy, $fallback_template ) {
		if ( ! function_exists( 'resolve_block_template' ) ) {
			return null;
		}

		if ( ! current_theme_supports( 'block-templates' ) ) {
			return null;
		}

		return resolve_block_template( $template_type, $template_hierarchy, $fallback_template );
	}

	/**
	 * Enqueue Post Id Load Css
	 *
	 * @since 2.0.0
	 * @param string $post_id The post id.
	 * @param array  $dependency The dependency.
	 */
	public function enqueue_post_css( $post_id = '', $dependency = array( 'tpgb-plus-block-front-css' ) ) {
		if ( ! empty( $post_id ) ) {
			$post_type = ( is_singular() ? 'post' : 'term' );
			if ( class_exists( 'Tpgb_Library' ) && ! empty( $dependency ) ) {
				tpgb_library()->header_init_css_js( $post_type, $post_id );
			}
			array_push( $this->template_ids, $post_id );
			$upload_dir       = wp_get_upload_dir();
			$upload_base_dir  = trailingslashit( $upload_dir['basedir'] );
			$css_path         = $upload_base_dir . "theplus_gutenberg/plus-css-{$post_id}.css";
			$preview_css_path = $upload_base_dir . "theplus_gutenberg/plus-preview-{$post_id}.css";

			$plus_version = $this->get_posts_metadata( $post_id, '_block_css', 'version' );
			if ( empty( $plus_version ) ) {
				$plus_version = time();
			}

			$plus_css = get_post_meta( $post_id, '_tpgb_css', true );
			if ( ! empty( $plus_css ) && isset( $plus_css['font_link'] ) && ! empty( $plus_css['font_link'] ) ) {
				$this->tpgb_load_google_fonts( $post_id, $plus_css['font_link'] );
			}
			if ( isset( $_GET['preview'] ) && true === $_GET['preview'] && file_exists( $preview_css_path ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$css_file_url = Tp_Blocks_Helper::tpgb_get_upload_url();
				$css_url      = $css_file_url . "theplus_gutenberg/plus-preview-{$post_id}.css";
				if ( ! $this->is_editor_screen() ) {
					wp_enqueue_style( "plus-preview-{$post_id}", esc_url( $css_url ), false, $plus_version . time() );
				}
			} elseif ( file_exists( $css_path ) ) {

				$css_file_url = Tp_Blocks_Helper::tpgb_get_upload_url();
				$css_url      = $css_file_url . "theplus_gutenberg/plus-css-{$post_id}.css";
				if ( ! $this->is_editor_screen() ) {
					wp_enqueue_style( "plus-post-{$post_id}", esc_url( $css_url ), $dependency, $plus_version );
				}
			} elseif ( ! file_exists( $css_path ) ) {
				$this->make_block_css_by_post_id( $post_id, $dependency );
			}
		}
	}

	/**
	 * Check wpdb_editor backend
	 *
	 * @since 1.0.0
	 * @return bool The result.
	 */
	private function is_editor_screen() {
		if ( ! empty( $_GET['action'] ) && 'wppb_editor' === $_GET['action'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return true;
		}
		return false;
	}

	/**
	 * Get Featured Image Url.
	 *
	 * @since 1.0.0
	 * @param mixed $obj The obj.
	 */
	public function tpgb_get_featured_image_url( $obj ) {

		$images = array();
		if ( ! isset( $obj['featured_media'] ) ) {
			$images['default'] = TPGB_URL . 'assets/images/tpgb-placeholder.jpg';
			return $images;
		} else {
			$image = wp_get_attachment_image_src( $obj['featured_media'], 'full', false );
			if ( is_array( $image ) ) {
				$images['full']          = $image;
				$images['tp-image-grid'] = wp_get_attachment_image_src( $obj['featured_media'], 'tp-image-grid', false );
				$images['thumbnail']     = wp_get_attachment_image_src( $obj['featured_media'], 'thumbnail', false );
				$images['medium']        = wp_get_attachment_image_src( $obj['featured_media'], 'medium', false );
				$images['medium_large']  = wp_get_attachment_image_src( $obj['featured_media'], 'medium_large', false );
				$images['large']         = wp_get_attachment_image_src( $obj['featured_media'], 'large', false );
				$images['default']       = TPGB_URL . 'assets/images/tpgb-placeholder.jpg';

				return $images;
			}
		}
	}

	/**
	 * Get Post Meta Info.
	 *
	 * @since 1.1.1
	 * @param mixed $obj The obj.
	 */
	public function tpgb_get_post_meta_info( $obj ) {

		$post_meta = array();
		if ( ! isset( $obj['id'] ) ) {
			return $post_meta;
		} else {

			$data_date = get_the_date( '', $obj['id'] );
			if ( ! empty( $data_date ) ) {
				$post_meta['get_date'] = $data_date;
			}

			$date_modi = get_the_modified_date( '', $obj['id'] );
			if ( ! empty( $date_modi ) ) {
				$post_meta['get_modified_date'] = $date_modi;
			}

			get_the_category_list( __( ', ', 'the-plus-addons-for-block-editor' ), '', $obj['id'] );
			$post_type       = isset( $obj['type'] ) ? $obj['type'] : '';
			$taxonomies_list = $this->tpgb_get_taxnomy_terms( $post_type );
			if ( ! empty( $taxonomies_list ) ) {
				foreach ( $taxonomies_list as $key => $value ) {
					if ( ! empty( $value ) ) {
						$terms                                = get_the_terms( $obj['id'], $value, array( 'hide_empty' => true ) );
						$post_meta['category_list'][ $value ] = $terms;
					}
				}
			}

			if ( ! empty( $obj['author'] ) ) {
				$post_meta['author_name']        = get_the_author_meta( 'display_name', $obj['author'] );
				$post_meta['author_url']         = get_author_posts_url( $obj['author'] );
				$post_meta['author_email']       = get_the_author_meta( 'email', $obj['author'] );
				$post_meta['author_website']     = get_the_author_meta( 'user_url', $obj['author'] );
				$post_meta['author_description'] = get_the_author_meta( 'user_description', $obj['author'] );
				$post_meta['author_facebook']    = get_the_author_meta( 'author_facebook', $obj['author'] );
				$post_meta['author_twitter']     = get_the_author_meta( 'author_twitter', $obj['author'] );
				$post_meta['author_instagram']   = get_the_author_meta( 'author_instagram', $obj['author'] );
				$post_meta['author_role']        = get_the_author_meta( 'roles', $obj['author'] );
				$post_meta['author_firstname']   = get_the_author_meta( 'first_name', $obj['author'] );
				$post_meta['author_lastname']    = get_the_author_meta( 'last_name', $obj['author'] );
				$post_meta['user_login']         = get_the_author_meta( 'user_login', $obj['author'] );

				global $user;
				$author_avatar = get_avatar( get_the_author_meta( 'ID' ), 200 );
				if ( $author_avatar ) {
					$post_meta['author_avatar']     = $author_avatar;
					$post_meta['author_avatar_url'] = get_avatar_url( get_the_author_meta( 'ID' ) );
				}
			}

			$comments_count = wp_count_comments( $obj['id'] );
			if ( ! empty( $comments_count ) ) {
				$post_meta['comment_count'] = $comments_count->total_comments;
			}

			$post_like               = get_post_meta( $obj['id'], 'tpgb_post_likes', true );
			$post_meta['post_likes'] = ( ! empty( $post_like ) ) ? $post_like : 0;
			$post_view               = get_post_meta( $obj['id'], 'tpgb_post_viwes', true );
			$post_meta['post_views'] = ( ! empty( $post_view ) ) ? $post_view : 0;
		}
		return $post_meta;
	}

	// Get Category Lists.
	/**
	 * Tpgb get category list.
	 *
	 * @param mixed $obj The obj.
	 * @return mixed The result.
	 */
	public function tpgb_get_category_list( $obj ) {
		$meta_list = array();
		if ( isset( $obj['id'] ) && isset( $obj['type'] ) && ! empty( $obj['type'] ) ) {

			$taxonomies_list = $this->tpgb_get_taxnomy_terms( $obj['type'] );
			if ( ! empty( $taxonomies_list ) ) {
				foreach ( $taxonomies_list as $key => $value ) {
					if ( ! empty( $value ) ) {
						$terms = get_the_terms( $obj['id'], $value, array( 'hide_empty' => true ) );
						if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
							$render_list = '';
							foreach ( $terms as $term ) {
								$render_list .= '<a href="' . esc_url( get_term_link( $term ) ) . '" alt="' . esc_attr( $term->name ) . '"  class="' . esc_attr( $value ) . '-' . esc_attr( $term->slug ) . '">' . $term->name . '</a> ';
							}
							$meta_list[ $value ] = $render_list;
						}
					}
				}
			}
		}
		return $meta_list;
	}

	/**
	 * Get Taxonomy List
	 *
	 * @since 1.1.2
	 * @param string $post_type The post type.
	 */
	public function tpgb_get_taxnomy_terms( $post_type = '' ) {
		$terms_list = array();
		if ( ! empty( $post_type ) ) {
			$taxonomies      = get_object_taxonomies( $post_type, 'objects' );
			$taxonomies_list = wp_filter_object_list(
				$taxonomies,
				array(
					'public'            => true,
					'show_in_nav_menus' => true,
				)
			);
			if ( ! empty( $taxonomies_list ) ) {
				foreach ( $taxonomies_list as $slug => $object ) {
					if ( isset( $object->name ) ) {
						$terms_list[] = $object->name;
					}
				}
			}
		}
		return $terms_list;
	}

	/**
	 * Rest api Product Data
	 *
	 * @since 1.1.2
	 * @param mixed $obj The obj.
	 */
	public function tpgb_get_product_data( $obj ) {
		$product_data = array();
		if ( ! isset( $obj['id'] ) ) {
			return $product_data;
		} else {
			$product1                   = wc_get_product( $obj['id'] );
			$product_data['price_html'] = $product1->get_price_html();
			$product_data['type']       = $product1->get_type();

			// Set Gallery Image Src.
			$img_id                  = $product1->get_gallery_image_ids();
			$img_id                  = ( isset( $img_id[0] ) ) ? $img_id[0] : '';
			$product_data['gallery'] = wp_get_attachment_image_src( $img_id, 'full' );

			$terms                      = get_the_terms( $obj['id'], 'product_cat' );
			$product_data['category']   = ( isset( $terms[0] ) && isset( $terms[0]->name ) ) ? $terms[0]->name : '';
			$product_data['procatslug'] = ( isset( $terms[0] ) && isset( $terms[0]->slug ) ) ? $terms[0]->slug : '';
			if ( $product1->get_rating_count() > 0 ) {
				$product_data['productRating'] = wc_get_rating_html( $product1->get_average_rating() );
			}

			include_once ABSPATH . 'wp-admin/includes/plugin.php';

			$status = get_post_meta( $obj['id'], '_stock_status', true );

			global $post, $product;
			if ( 'outofstock' === $status ) {
				$product_data['productBadge'] = '<span class="badge out-of-stock">Out Of stock</span>';
			} elseif ( $product && $product->is_on_sale() ) {
				if ( 'discount' === 'discount' ) {
					if ( $product->get_type() === 'variable' ) {
						$available_variations       = $product->get_available_variations();
						$maximumper                 = 0;
						$available_variations_count = count( $available_variations );
						for ( $i = 0; $i < $available_variations_count; ++$i ) {
							$variation_id      = $available_variations[ $i ]['variation_id'];
							$variable_product1 = new WC_Product_Variation( $variation_id );
							$regular_price     = $variable_product1->get_regular_price();
							$sales_price       = $variable_product1->get_sale_price();
							$percentage        = $sales_price ? round( ( ( $regular_price - $sales_price ) / $regular_price ) * 100 ) : 0;
							if ( $percentage > $maximumper ) {
								$maximumper = $percentage;
							}
						}
						$product_data['productBadge'] = apply_filters( 'woocommerce_sale_flash', '<span class="badge onsale perc">&darr; ' . $maximumper . '%</span>', $post, $product );
					} elseif ( $product->get_type() === 'simple' ) {
						$percentage                   = round( ( ( $product->get_regular_price() - $product->get_sale_price() ) / $product->get_regular_price() ) * 100 );
						$product_data['productBadge'] = apply_filters( 'woocommerce_sale_flash', '<span class="badge onsale perc">&darr; ' . $percentage . '%</span>', $post, $product );
					} elseif ( $product->get_type() === 'external' ) {
						$percentage                   = round( ( ( $product->get_regular_price() - $product->get_sale_price() ) / $product->get_regular_price() ) * 100 );
						$product_data['productBadge'] = apply_filters( 'woocommerce_sale_flash', '<span class="badge onsale perc">&darr; ' . $percentage . '%</span>', $post, $product );
					}
				} else {
					$product_data['productBadge'] = apply_filters( 'woocommerce_sale_flash', '<span class="badge onsale">' . esc_html__( 'Sale', 'the-plus-addons-for-block-editor' ) . '</span>', $post, $product );
				}
			}

			return $product_data;
		}
	}

	/**
	 * Exclude Css Google Import font Url
	 *
	 * @since 2.0.0
	 * @param string $post_css The post css.
	 */
	public function exclude_gfont_block_css( $post_css = '' ) {

		$pattern_url = '/@import[ ]*[\'\"]{0,}(url\()*[\'\"]*([^;\'\"\)]*)[\'\"\)]*/i';

		$g_fonts   = array();
		$font_link = '';
		if ( preg_match_all( $pattern_url, $post_css, $matches, PREG_SET_ORDER, 0 ) ) {

			if ( ! empty( $matches ) ) {

				$i = 0;
				foreach ( $matches as $key => $url ) {
					if ( ! empty( $url ) && isset( $url[0] ) && ! empty( $url[0] ) ) {
						$post_css = str_replace( $url[0] . ';', '', $post_css );
					}
					if ( ! empty( $url ) && isset( $url[2] ) && ! empty( $url[2] ) ) {

						$get_fonts = '/(?:\?|\&)(?<key>[\w]+)=(?<family>[\w+,-]+)(?:\:?)(?<weight>[\w,]*)/';

						if ( preg_match_all( $get_fonts, $url[2], $match_fonts, PREG_SET_ORDER, 0 ) ) {
							if ( ! empty( $match_fonts ) ) {
								if ( isset( $match_fonts[0] ) && ! empty( $match_fonts[0] ) ) {
									$font_family = '';
									$font_weight = '';
									if ( isset( $match_fonts[0]['family'] ) && ! empty( $match_fonts[0]['family'] ) ) {
										$font_family = str_replace( '+', ' ', $match_fonts[0]['family'] );
									}
									if ( isset( $match_fonts[0]['weight'] ) && ! empty( $match_fonts[0]['weight'] ) ) {
										$font_weight = $match_fonts[0]['weight'];
									}
									if ( ! empty( $font_family ) ) {
										if ( isset( $g_fonts[ $font_family ] ) ) {
											$g_fonts[ $font_family ] = $g_fonts[ $font_family ] . ',' . $font_weight;
										} else {
											$g_fonts[ $font_family ] = $font_weight;
										}
									}
								}
							}
						}
						++$i;
					}
				}
			}
		}

		if ( ! empty( $g_fonts ) ) {
			$join_attr = '';
			foreach ( $g_fonts as $family => $weight ) {
				if ( ! empty( $join_attr ) ) {
					$join_attr .= '|'; // join multiple font.
				}
				if ( isset( $family ) && ! empty( $family ) ) {
					$join_attr .= str_replace( ' ', '+', $family );

					if ( ! empty( $weight ) ) {
						$join_attr .= ':';
						$join_attr .= $weight;
					}
				}
			}

			if ( isset( $join_attr ) && ! empty( $join_attr ) ) {
				$font_link = 'https://fonts.googleapis.com/css?family=' . esc_attr( $join_attr );
			}
		}

		$post_css = ! empty( $post_css ) ? trim( $post_css ) : '';
		return array(
			'css'       => $post_css,
			'fonts'     => $g_fonts,
			'font_link' => $font_link,
		);
	}

	/** // phpcs:ignore Generic.Commenting.DocComment.ShortNotCapital,Generic.Commenting.DocComment.LongNotCapital,Generic.Commenting.DocComment.MissingShort
	 *
	 * @return bool|false|int The result.
	 *
	 * get post id current page id
	 */
	private function is_tpgb_post_id() {
		$post_id = get_the_ID();

		if ( function_exists( 'is_shop' ) && is_shop() ) {
			$post_id = wc_get_page_id( 'shop' ); // Gets the shop page ID.
		}

		if ( ! $post_id ) {
			return false;
		}
		return $post_id;
	}

	/**
	 * Delete dynamic post releated data
	 *
	 * @delete post css file
	 * @param string $post_id The post id.
	 * @param bool   $is_preview The is preview.
	 */
	private function delete_post_dynamic( $post_id = '', $is_preview = false ) {
		$post_id = $post_id ? $post_id : $this->is_tpgb_post_id();
		if ( $post_id ) {
			$upload_dir     = wp_get_upload_dir();
			$upload_css_dir = trailingslashit( $upload_dir['basedir'] );
			if ( false === $is_preview ) {
				$css_path = $upload_css_dir . "theplus_gutenberg/plus-css-{$post_id}.css";
				if ( file_exists( $css_path ) ) {
					wp_delete_file( $css_path );
				}
			}
			$css_preview_path = $upload_css_dir . "theplus_gutenberg/plus-preview-{$post_id}.css";
			if ( file_exists( $css_preview_path ) ) {
				wp_delete_file( $css_preview_path );
			}
		}
	}

	/**
	 * Admin Bar enqueue Scripts
	 *
	 * @since 1.2.0
	 */
	public function admin_bar_enqueue_scripts() {
		global $wp_admin_bar;

		if ( ! is_super_admin()
		|| ! is_object( $wp_admin_bar )
		|| ! function_exists( 'is_admin_bar_showing' )
		|| ! is_admin_bar_showing() ) {
			return;
		}

		if ( class_exists( 'Tpgb_Library' ) ) {
			$tpgb_libraby = Tpgb_Library::get_instance();
			if ( isset( $tpgb_libraby->plus_template_blocks ) ) {
				$this->template_ids = array_unique( array_merge( $this->template_ids, $tpgb_libraby->plus_template_blocks ) );

			}
		}

		// Load js 'tpgb-admin-bar' before 'admin-bar'.
		wp_dequeue_script( 'admin-bar' );

		wp_enqueue_script(
			'tpgb-admin-bar',
			TPGB_URL . 'assets/js/main/general/tpgb-admin-bar.min.js',
			array(),
			TPGB_VERSION,
			true
		);

		wp_enqueue_script( // phpcs:ignore WordPress.WP.EnqueuedResourceParameters
			'admin-bar',
			null,
			array( 'tpgb-admin-bar' ),
			TPGB_VERSION,
			true
		);

		$template_list = array();
		if ( ! empty( $this->template_ids ) ) {
			foreach ( $this->template_ids as $key => $post_id ) {
				if ( ! isset( $template_list[ $post_id ] ) ) {
					$posts = get_post( $post_id );
					if ( isset( $posts->post_title ) ) {
						$template_list[ $post_id ]['id']       = $post_id;
						$template_list[ $post_id ]['title']    = $posts->post_title;
						$template_list[ $post_id ]['edit_url'] = esc_url( get_edit_post_link( $post_id ) );
					}
					if ( isset( $posts->post_type ) ) {
						$template_list[ $post_id ]['post_type'] = $posts->post_type;
						$post_type_obj                          = get_post_type_object( $posts->post_type );

						if ( ! empty( $post_type_obj ) && isset( $post_type_obj->labels ) && isset( $post_type_obj->labels->singular_name ) ) {
							$template_list[ $post_id ]['post_type_name'] = $post_type_obj->labels->singular_name;
						} else {
							$template_list[ $post_id ]['post_type_name'] = '';
						}

						if ( 'nxt_builder' === $posts->post_type ) {
							if ( get_post_meta( $post_id, 'nxt-hooks-layout', true ) ) {
								$layout = get_post_meta( $post_id, 'nxt-hooks-layout', true );
								$type   = '';
								if ( ! empty( $layout ) && 'sections' === $layout ) {
									$type = get_post_meta( $post_id, 'nxt-hooks-layout-sections', true );
								} elseif ( ! empty( $layout ) && 'pages' === $layout ) {
									$type = get_post_meta( $post_id, 'nxt-hooks-layout-pages', true );
								} elseif ( ! empty( $layout ) && 'code_snippet' === $layout ) {
									$type = get_post_meta( $post_id, 'nxt-hooks-layout-code-snippet', true );
								} elseif ( ! empty( $layout ) && 'none' === $layout ) {
									unset( $template_list[ $post_id ] );
								}
								if ( isset( $template_list[ $post_id ] ) ) {
									$template_list[ $post_id ]['nexter_layout'] = $layout;
									$template_list[ $post_id ]['nexter_type']   = $type;
								}
							}
						}
					}
				}
			}
		}

		$template_list1 = array_column( $template_list, 'post_type' );
		array_multisort( $template_list1, SORT_DESC, $template_list );
		$tpgb_template = array( 'tpgb_edit_template' => $template_list );
		$scripts       = 'var TpgbAdminbar = ' . wp_json_encode( $tpgb_template );

		wp_add_inline_script( 'tpgb-admin-bar', $scripts, 'before' );
	}

	/**
	 * Blocksy Content Blocks Compatibility
	 *
	 * @since 1.3.0
	 * @param int $id The id.
	 */
	public function tpgb_blocksy_content_blocks( $id ) {
		if ( ! empty( $id ) ) {
			$this->enqueue_post_css( $id );
			$this->tpgb_reusable_block_css( $id );
		}
	}

	/**
	 * Blocksy Content Blocks Compatibility
	 *
	 * @since 3.3.0
	 * @param mixed $content The content.
	 * @param int   $id The id.
	 */
	public function tpgb_blocksy_content_output( $content, $id ) {
		if ( ! empty( $id ) ) {
			$this->enqueue_post_css( $id );
			$this->tpgb_reusable_block_css( $id );
		}

		return $content;
	}

	/**
	 * Astra Pro addons Compatibility of Custom Layout
	 *
	 * @since 1.4.4
	 */
	public function astra_custom_layouts_assets() {
		$option = array(
			'location'  => 'ast-advanced-hook-location',
			'exclusion' => 'ast-advanced-hook-exclusion',
			'users'     => 'ast-advanced-hook-users',
		);
		if ( class_exists( 'Astra_Target_Rules_Fields' ) ) {
			$result = Astra_Target_Rules_Fields::get_instance()->get_posts_by_conditions( 'astra-advanced-hook', $option );

			if ( ! empty( $result ) ) {
				foreach ( $result as $post_id => $post_data ) {
					$this->enqueue_post_css( $post_id );
				}
			}
		}
	}

	/** // phpcs:ignore Squiz.Commenting.FunctionComment, Generic.Commenting.DocComment.ShortNotCapital,Generic.Commenting.DocComment.LongNotCapital,Generic.Commenting.DocComment.MissingShort
	 * Get Acf Field API
	 *
	 * @since 3.1.2
	 * */
	public function tpgb_get_Acf_Field( $params ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid,WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
		$acf_data = '';
		if ( ! empty( $params ) && ! empty( $params->get_params() ) ) {
			$acf_data = $params->get_params();
		}

		$results     = array();
		$field_types = array(
			'acf_text'         => array(
				'text',
				'textarea',
				'email',
				'url',
				'number',
				'password',
				'range',
			),
			'acf_select'       => array(
				'select',
				'checkbox',
				'radio',
				'acfe_image_selector',
			),
			'acf_button_group' => array(
				'button_group',
			),
			'acf_boolean'      => array(
				'true_false',
			),
			'acf_post'         => array(
				'post_object',
				'relationship',
			),
			'acf_taxonomy'     => array(
				'taxonomy',
			),
			'acf_datetime'     => array(
				'date_picker',
				'date_time_picker',
			),
		);

		if ( class_exists( 'ACF' ) ) {
			if ( function_exists( 'acf_get_field_groups' ) ) {
				$acffield = acf_get_field_groups();
			} else {
				$acffield = apply_filters( 'acf/get_field_groups', array() ); // phpcs:ignore WordPress.NamingConventions.ValidHookName
			}

			foreach ( $acffield as $field_group ) {
				$tpgb_dyfield = array();
				$title        = $field_group['title'];
				if ( function_exists( 'acf_get_fields' ) ) {
					if ( isset( $field_group['ID'] ) && ! empty( $field_group['ID'] ) ) {
						$fields = acf_get_fields( $field_group['ID'] );
					} else {
						$fields = acf_get_fields( $field_group );
					}
				} else {
					$fields = apply_filters( 'acf/field_group/get_fields', array(), $field_group['id'] ); // phpcs:ignore WordPress.NamingConventions.ValidHookName
				}

				foreach ( $fields as $acf_field ) {
					if ( isset( $acf_data['fieldType'] ) && ! empty( $acf_data['fieldType'] ) ) {

						if ( ! empty( $acf_field['name'] ) && in_array( $acf_field['type'], $field_types[ $acf_data['fieldType'] ], true ) ) {
							$results[] = array(
								'value' => $acf_field['name'],
								'label' => $title . ' : ' . $acf_field['label'],
							);
						}
					}
				}
			}
		}
		return $results;
	}

	/**
	 * Generic helper to disable a feature based on an option key.
	 *
	 * @param bool   $data       Default state (true = enabled).
	 * @param string $option_key Option name to check.
	 * @return bool The result.
	 */
	private function is_option_disabled( $data, $option_key ) {
		$option_value = Tp_Blocks_Helper::get_extra_option( $option_key );
		return ( ! empty( $option_value ) && 'enable' === $option_value ) ? false : $data;
	}

	/**
	 * Disable DashIcons
	 *
	 * @since 4.1.0
	 * @param bool $data The data.
	 */
	public function check_tpgb_dashicons_icon( $data = true ) {
		return $this->is_option_disabled( $data, 'tpgb_dashicons_icon' );
	}

	/**
	 * Disable Preset Import
	 *
	 * @since 4.5.6
	 * @param bool $data The data.
	 */
	public function check_tpgb_preset_import( $data = true ) {
		return $this->is_option_disabled( $data, 'tpgb_preset_import' );
	}

	/**
	 * Enable/Disable Quick Action Bar
	 *
	 * @since 4.5.9
	 * @param bool $data The data.
	 */
	public function nxt_qab_enable_callback( $data = true ) {
		return $this->is_option_disabled( $data, 'nxt_qab_enable' );
	}
}

Tpgb_Core_Init_Blocks::get_instance();
