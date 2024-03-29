<?php 
/**
 * The Plus Blocks Initialize
 *
 * Load of all the blocks.
 *
 * @since   1.0.0
 * @package TPGB
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define('TPGB_ASSET_PATH', wp_upload_dir()['basedir'] . DIRECTORY_SEPARATOR . 'theplus_gutenberg');
define('TPGB_ASSET_URL', wp_upload_dir()['baseurl'] . '/theplus_gutenberg');

/**
 * Tp_Core_Init_Blocks.
 *
 * @package TPGB
 */
class Tp_Core_Init_Blocks {


	/**
	 * Member Variable
	 *
	 * @var instance
	 */
	private static $instance;
	
	protected $tpgb_global = 'tpgb_global_options';

	protected $template_ids = array();
	/**
	 *  Initiator
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	/**
	 * Constructor
	 */
	public function __construct() {
		
		add_filter( 'block_categories_all', array( $this, 'tp_register_block_category' ), 10, 2 );
		
		require_once TPGB_PATH.'classes/tp-registered-blocks.php';
		tpgb_library();
		
		add_action( 'enqueue_block_assets', array( $this, 'tp_block_assets' ) ); //front end load
		add_action( 'enqueue_block_editor_assets', array( $this, 'editor_assets' ) ); //Gutenberg editor load
		
		$this->tpgb_global_settings_post_meta();
		
		add_action('rest_api_init', array($this, 'plus_register_api_hook'));
		add_action('after_setup_theme', array($this, 'plus_add_image_size'));
		add_filter( 'image_resize_dimensions', array($this,'tpgb_thumbnail_upscale'), 10, 6 );
		//Load Css/Js File blocks
		add_action('wp_enqueue_scripts', array($this, 'enqueue_load_block_css_js'));
		add_action('wp_enqueue_scripts', array($this, 'enqueue_post_css'));
		add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_css_js'));
		
		if(!defined('NEXTER_EXT')){
			//admin bar enqueue scripts
			add_action( 'wp_footer', [ $this, 'admin_bar_enqueue_scripts' ] );
		}
	}
	
	/**
	 * Plus Image Size Gutenberg block.
	 * @since 1.0.0
	 */
	public function plus_add_image_size(){
		add_image_size( 'tp-image-grid', 700, 700, true);
	}
	
	/*
	 * tpgb_thumbnail hard crop
	 * @since 1.1.3
	 */
	public function tpgb_thumbnail_upscale( $default, $orig_w, $orig_h, $new_w, $new_h, $crop ){

		if ( !$crop ) return null; // let the wordpress default function handle this

		$aspect_ratio = $orig_w / $orig_h;
		$size_ratio = max($new_w / $orig_w, $new_h / $orig_h);

		$crop_w = round($new_w / $size_ratio);
		$crop_h = round($new_h / $size_ratio);

		$s_x = floor( ($orig_w - $crop_w) / 2 );
		$s_y = floor( ($orig_h - $crop_h) / 2 );

		return array( 0, 0, (int) $s_x, (int) $s_y, (int) $new_w, (int) $new_h, (int) $crop_w, (int) $crop_h );
	}
	
	/**
	 * Gutenberg block category for The Plus Addon.
	 *
	 * @param array  $categories Block categories.
	 * @param object $post Post object.
	 * @since 1.0.0
	 */
	public function tp_register_block_category( $categories, $post ) {
		return array_merge(
			$categories,
			array(
				array(
					'slug'  => TPGB_CATEGORY,
					'title' => __( 'The Plus Addons Blocks', 'tpgb' ),
				),
			)
		);
	}
	
	/*
	 * Enqueue block styles for both frontend + backend.
	 *
     * @since 1.0.0
	 */
	public function tp_block_assets(){
	

		$GoogleMap_Enable = Tp_Blocks_Helper::get_extra_option('gmap_api_switch');
		$GoogleMap_Api = '';
		if(!empty($GoogleMap_Enable) && $GoogleMap_Enable=='enable' && has_block( 'tpgb/tp-google-map' )){
			$GoogleMap_Api = Tp_Blocks_Helper::get_extra_option('googlemap_api');
			if(!empty($GoogleMap_Api)){
				wp_enqueue_script( 'gmaps-js','//maps.googleapis.com/maps/api/js?key='.esc_attr($GoogleMap_Api).'&sensor=false', array('jquery'), null, false, true);
			}
		}
		
		// Generate Block Editor Style and Scripts
		if (tpgb_library()->is_preview_mode()) {

			if (!tpgb_library()->check_cache_files()) {
				$blocksList= tpgb_library()->plus_generate_scripts(tpgb_library()->get_plus_block_settings());
			}

			// enqueue scripts
			if (tpgb_library()->check_cache_files()) {
				$css_file = TPGB_ASSET_URL . '/theplus.min.css';
				$js_file = TPGB_ASSET_URL . '/theplus.min.js';
			} else {
				$tpgb_url = TPGB_URL;
				if (defined('TPGBP_VERSION') && defined('TPGBP_URL')) {
					$tpgb_url = TPGBP_URL;
				}
				$css_file = $tpgb_url . 'assets/css/main/general/theplus.min.css';
				$js_file = $tpgb_url . 'assets/js/main/general/theplus.min.js';
			}

			//fontawesome icon load frontend
			$fontawesome_pro = Tp_Blocks_Helper::get_extra_option('fontawesome_pro_kit');
			if(empty($fontawesome_pro) || !defined('TPGBP_VERSION')){
				wp_enqueue_style('tpgb-fontawesome', TPGB_URL.'assets/css/extra/fontawesome.min.css', array());
			}

			wp_enqueue_script(
				'tpgb-purge-js',
				TPGB_URL."assets/js/main/general/tpgb-purge.js",
				['jquery'],
				TPGB_VERSION,
				true
			);
			
			$plus_version=get_option( 'tpgb_backend_cache_at' );
			if(empty($plus_version)){
				$plus_version = TPGB_VERSION;
			}
			
			// Load Plus Style Editor Block
			wp_enqueue_style(
				'tpgb-plus-block-editor-css',
				tpgb_library()->pathurl_security($css_file),
				array('wp-edit-blocks'),
				$plus_version
			);
			
			// Load Plus Script Editor Block
			wp_enqueue_script(
				'tpgb-plus-block-editor-js',
				tpgb_library()->pathurl_security($js_file),
				['jquery'],
				$plus_version,
				false
			);
		}else{
		
			global $wp_query;
			
			if (is_home() || is_singular() || is_archive() || is_search() || (isset( $wp_query ) && (bool) $wp_query->is_posts_page) || is_404()) {
				$queried_obj = get_queried_object_id();
				
				if(is_search()){
					$queried_obj = 'search';
				}
				if(is_404()){
					$queried_obj = '404';
				}
				$post_type = (is_singular() ? 'post' : 'term');
				
				tpgb_library()->enqueue_frontend_load($post_type, $queried_obj);
			}
		}
		
		wp_localize_script(
			'jquery', 'tpgb_load', array(
				'ajaxUrl' => admin_url('admin-ajax.php'),
			)
		);
	}
	
	
	/**
     * Enqueue block styles and scripts for backend editor.
     *
     * @since 1.2.0
     */
    public function editor_assets() {
		
		if (!defined('TPGBP_VERSION')) {
			wp_enqueue_style('tpgb-block-editor-css', TPGB_ASSETS_URL.'assets/css/admin/tpgb-blocks-editor.min.css', array('wp-edit-blocks'),TPGB_VERSION);
		}
		
		wp_enqueue_script( 'tpgb-xdlocalstorage-js', TPGB_ASSETS_URL . 'assets/js/extra/xdlocalstorage.js', array( 'wp-blocks' ), TPGB_VERSION, false );
		wp_enqueue_script( 'tpgb-cp-js', TPGB_ASSETS_URL . 'assets/js/extra/tpgb-cp.js', array( 'jquery', 'tpgb-xdlocalstorage-js' ), TPGB_VERSION, false );
		
		if (!defined('TPGBP_VERSION')) {
			wp_enqueue_script('tpgb-block-editor-js', TPGB_ASSETS_URL.'assets/js/admin/tpgb-blocks-editor.min.js', array( 'wp-blocks', 'wp-i18n','wp-plugins','wp-edit-post', 'wp-element', 'wp-editor'),TPGB_VERSION, false);
		}
		
		wp_enqueue_script( 'tpgb-deactivate-block-js', TPGB_ASSETS_URL . 'assets/js/admin/blocks.deactivate.min.js', array( 'wp-blocks' ), TPGB_VERSION, true );
		//WP Localized globals
		
		$GoogleMap_Enable = Tp_Blocks_Helper::get_extra_option('gmap_api_switch');
		$GoogleMap_Api = '';
		if(!empty($GoogleMap_Enable) && $GoogleMap_Enable=='enable' || $GoogleMap_Enable=='disable'){
			$GoogleMap_Api = Tp_Blocks_Helper::get_extra_option('googlemap_api');
		}
		
		$googleFont_Load = Tp_Blocks_Helper::get_extra_option('gfont_load');
		$googleFonts = false;
		if( empty($googleFont_Load) || (!empty($googleFont_Load) && $googleFont_Load!='disable') ){
			$googleFonts = true;
			$googleFonts = apply_filters( 'tpgb_google_font_load', $googleFonts );
		}
		
		$wp_localize_tpgb = array(
			'activeTheme' => esc_html( get_template() ),
			'category' => TPGB_CATEGORY,
			'activated_blocks' => Tp_Blocks_Helper::get_block_enabled([]),
			'deactivated_blocks' => Tp_Blocks_Helper::get_block_deactivate(),
			'post_type_list' => Tp_Blocks_Helper::get_post_type_list(),
			'plugin_url' => TPGB_ASSETS_URL,
			'admin_url' => esc_url(admin_url()),
			'home_url' => home_url(),
			'block_icon_url' => esc_url(TPGB_ASSETS_URL.'/assets/images/block-icons'),
			'ajax_url' => esc_url( admin_url( 'admin-ajax.php' ) ),
			'image_sizes' => Tp_Blocks_Helper::get_image_sizes(),
			'googlemap_api' => $GoogleMap_Api,
			'googlefont_load' => $googleFonts,
			'fontawesome' => false,
			'contactform_list' => Tp_Blocks_Helper::get_contact_form_post(),
			'calderaform_list' => Tp_Blocks_Helper::get_caldera_forms_post(),
			'everestform_list' => Tp_Blocks_Helper::get_everest_form_post(),
			'gravityform_list' => Tp_Blocks_Helper::get_gravity_form_post(),
			'ninjaform_list' => Tp_Blocks_Helper::get_ninja_form_post(),
			'wpform_list' => Tp_Blocks_Helper::get_wpforms_form_post(),
			'preview_image' => esc_url(TPGB_URL .'assets/images/tpgb-placeholder.jpg'),
			'preview_grid_image' => esc_url(TPGB_URL .'assets/images/tpgb-placeholder-grid.jpg'),
			'taxonomy_list' => Tp_Blocks_Helper::tpgb_get_post_taxonomies(),
			'custom_font' => Tp_Blocks_Helper::tpgb_custom_font(),
		);
		
		if(has_filter('tpgb_load_localize')) {
			$wp_localize_tpgb = apply_filters('tpgb_load_localize', $wp_localize_tpgb);
		}
		
		wp_localize_script('tpgb-block-editor-js', 'tpgb_blocks_load', $wp_localize_tpgb );
    }
	
	public function plus_register_api_hook(){
		
		$post_types = get_post_types();
		
		// Update ThePlus Global Options
		register_rest_route(
			'tpgb/v1',
			'/theplus_global_settings/',
			array(
				array(
					'methods'  => WP_REST_Server::READABLE,
					'callback' => array($this, 'tpgb_get_global_settings'),
					'permission_callback' => function () {
                        return true;
                    },
					'args' => array()
				),
				array(
					'methods'  => WP_REST_Server::EDITABLE,
					'callback' => array($this, 'tpgb_update_global_settings'),
					'permission_callback' => function (WP_REST_Request $request) {
						return current_user_can('edit_posts');
					},
					'args' => array()
				)
			)
		);
		
		// Get Post Content by ID
		register_rest_route(
			'tpgb/v1',
			'/tpgb_get_content/',
			array(
				array(
					'methods' => 'POST',
					'callback' => array( $this, 'tpgb_get_post_content' ),
					'permission_callback' => function () {
						return current_user_can( 'edit_posts' );
					},
					'args' => array(),
				),
			)
		);
		
		// ThePlus Save Block Css file
		register_rest_route(
			'the-plus-addons-for-block-editor/v1',
			'/plus_save_block_css/',
			array(
				array(
					'methods'  => 'POST',
					'callback' => array($this, 'plus_save_block_css'),
					'permission_callback' => function (WP_REST_Request $request) {
						return current_user_can('edit_posts');
					},
					'args' => array()
				)
			)
		);
		
		//post type featured image
		register_rest_field(
			$post_types,
			'tpgb_featured_images',
			array(
				'get_callback' => array($this, 'tpgb_get_featured_image_url'),
				'update_callback' => null,
				'schema' => array(
					'description' => __('The Plus Different sized of featured images','tpgb'),
					'type' => 'array',
				),
			)
		);
		
		//Post Type Meta Info
		register_rest_field(
			$post_types,
			'tpgb_post_meta_info',
			array(
				'get_callback' => array($this, 'tpgb_get_post_meta_info'),
				'update_callback' => null,
				'schema' => array(
					'description' => __('Post Listing of get Post Meta Info.','tpgb'),
					'type' => 'array',
				),
			)
		);
		
		// POST Category Lists.
		register_rest_field(
			$post_types,
			'tpgb_post_category',
			array(
				'get_callback' => array($this, 'tpgb_get_category_list'),
				'update_callback' => null,
				'schema' => array(
					'description' => __('Category list links','tpgb'),
					'type' => 'string',
				),
			)
		);
		
		/**
		 * rest api Product Info
		 * @since 1.1.2
		 */
		register_rest_field(
			'product',
			'tpgb_product_data',
			array(
				'get_callback' => array($this, 'tpgb_get_product_data'),
				'update_callback' => null,
				'schema' => array(
					'description' => __('Product Data.','tpgb'),
					'type' => 'array',
				),
			)
		);
	}
	
	
	/**
	 * Add post meta tpgb
	 */
	public function tpgb_global_settings_post_meta()
	{
		register_meta('post', 'tpgb_global_settings', [
			'show_in_rest' => true,
			'single' => true,
			'type' => 'string'
		]);

	}
	
	/**
	 * API call Get ThePlus Global Options
	 * @since 1.0.0
	 */
	public function tpgb_get_global_settings(){
		try {

			$plus_settings = get_option($this->tpgb_global);

			$plus_settings = ($plus_settings == false) ? json_decode('{}') : json_decode($plus_settings);
			return ['success' => true, 'settings' => $plus_settings];
		} catch (Exception $e) {
			return ['success' => false, 'message' => $e->getMessage()];
		}
	}
	
	/**
	 * API call Get Post Content
	 * @since 1.1.2
	 */
	public function tpgb_get_post_content( $request ) {
		$params = $request->get_params();
		try {
			if ( isset( $params['post_id'] ) ) {
				$post_data = get_post( $params['post_id'] );
				$content = (isset($post_data->post_content)) ? $post_data->post_content : ''; 
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
	 * @since 1.0.0
	 */
	public function tpgb_update_global_settings($request)
	{
		try {
			$params = $request->get_params();
			if (!isset($params['settings']))
				throw new Exception( __("Settings parameter is missing!",'tpgb') );

			$plus_settings = $params['settings'];

			if (get_option($this->tpgb_global) == false) {
				add_option($this->tpgb_global, $plus_settings);
			} else {
				update_option($this->tpgb_global, $plus_settings);
			}

			return ['success' => true, 'message' => __("ThePlus Global settings updated!",'tpgb') ];
		} catch (Exception $e) {
			return ['success' => false, 'message' => $e->getMessage()];
		}
	}
	
	/**
	 * Save block css 
	 * @since 1.1.3
	 */
	public function  plus_save_block_css($request) {
		try {
			global $wp_filesystem;
			if (!$wp_filesystem) {
				require_once(ABSPATH . 'wp-admin/includes/file.php');
			}

			$params = $request->get_params();
			$is_preview = $params['is_preview'];
			$post_id = (int) sanitize_text_field($params['post_id']);
			
			if($params['is_global']){
				$global_css = (!empty($params['global_css'])) ? $params['global_css'] : '';
				$globalfilename = "plus-global.css";
				
				$upload_dir = wp_upload_dir();
				$dir = trailingslashit($upload_dir['basedir']) . 'theplus_gutenberg/';
				
				$import_global_first = '';
				if(!empty($params['is_global']) && $params['is_global'] == true && !empty($global_css)){
					$import_global_first = $this->set_font_import_css($global_css);
				}
				
				if($is_preview==true){
					$globalfilename = "plus-global-preview.css";
				}
				WP_Filesystem(false, $upload_dir['basedir'], true);

				if (!$wp_filesystem->is_dir($dir)) {
					$wp_filesystem->mkdir($dir);
				}
				
				if(!empty($params['is_global']) && $params['is_global'] == true){
					if (!$wp_filesystem->put_contents($dir . $globalfilename, $import_global_first)) {
						throw new Exception(__('CSS can not be load due to permission!!!', 'tpgb'));
					}
				}
			}
			
			if ($params['is_block']) {
				$block_css = $params['block_css'];
				$filename = "plus-css-{$post_id}.css";

				$upload_dir = wp_upload_dir();
				$dir = trailingslashit($upload_dir['basedir']) . 'theplus_gutenberg/';
				
				$import_css = $this->set_font_import_css($block_css);

				if($is_preview==true){
					$filename = "plus-preview-{$post_id}.css";
				}else{
					update_post_meta($post_id, '_tpgb_css', $import_css);
					update_post_meta($post_id, '_block_css',time());
					$this->delete_post_dynamic($post_id,true);
				}
				
				WP_Filesystem(false, $upload_dir['basedir'], true);

				if (!$wp_filesystem->is_dir($dir)) {
					$wp_filesystem->mkdir($dir);
				}
				
				if (!$wp_filesystem->put_contents($dir . $filename, $import_css)) {
					throw new Exception(__('CSS can not be load due to permission!!!', 'tpgb'));
				}
			} else {
				delete_post_meta($post_id, '_tpgb_css');
				delete_post_meta($post_id, '_block_css');
				$this->delete_post_dynamic($post_id);
			}
			
			// set block meta
			if ($is_preview==false) {
				return ['success' => true, 'message' => __('Plus block css updated.', 'tpgb'), 'data' => $params];
			}else{
				return ['success' => true, 'message' => __('Plus block preview css updated.', 'tpgb'), 'data' => $params];
			}
			
		} catch (Exception $e) {
			return ['success' => false, 'message' => $e->getMessage()];
		}
	}
	
	/**
	 * Make Dynamic Block Css By Post ID
	 * @since 1.1.3
	 */
	public function make_block_css_by_post_id( $post_id = '' ){
		if( !empty($post_id) ){
			
			global $wp_filesystem;
			if (!$wp_filesystem) {
				require_once(ABSPATH . 'wp-admin/includes/file.php');
			}
			
			$filename = "plus-css-{$post_id}.css";
			$upload_dir = wp_upload_dir();
			$dir = trailingslashit($upload_dir['basedir']) . 'theplus_gutenberg/';
			$block_css ='';
			if( class_exists('Tp_Generate_Blocks_Css') ){
				$generateClass = new Tp_Generate_Blocks_Css();
				$block_css = $generateClass->generate_dynamic_css( $post_id );
			}
			if( !empty($block_css) ){
				$import_css = $this->set_font_import_css($block_css);
				
				WP_Filesystem(false, $upload_dir['basedir'], true);

				if (!$wp_filesystem->is_dir($dir)) {
					$wp_filesystem->mkdir($dir);
				}

				if (!$wp_filesystem->put_contents($dir . $filename, $import_css)) {
					throw new Exception(__('CSS can not be load due to permission!!!', 'tpgb'));
				}else{
					$css_path = $dir . $filename;
					if (!$this->is_editor_screen() && file_exists($css_path)) {
						$css_url = trailingslashit($upload_dir['baseurl']) . 'theplus_gutenberg/'. $filename;
						$plus_version=time();
						update_post_meta($post_id, '_block_css',$plus_version);
						wp_enqueue_style("plus-post-{$post_id}", $css_url, false, $plus_version);
					}
				}
			}
		}
	}
	
	/*
	 * Frontend Enqueue Scripts
	 * @since 1.1.3
	 **/
	public function enqueue_load_block_css_js(){
		$post_id			= $this->is_tpgb_post_id();
		$upload_dir			= wp_get_upload_dir();
		$upload_base_dir 	= trailingslashit($upload_dir['basedir']);
		$global_path		= $upload_base_dir . "theplus_gutenberg/plus-global.css";
		$css_path			= $upload_base_dir . "theplus_gutenberg/plus-css-{$post_id}.css";
		$preview_css_path	= $upload_base_dir . "theplus_gutenberg/plus-preview-{$post_id}.css";
		
		$plus_version=get_post_meta( $post_id, '_block_css', true );
		if(empty($plus_version)){
			$plus_version=time();
			update_post_meta($post_id, '_block_css',$plus_version);
		}

		$css_file_url = trailingslashit($upload_dir['baseurl']);
		$this->tpgb_reusable_block_css();
		if( isset($_GET['preview']) && $_GET['preview'] == true && file_exists($preview_css_path)){
			
			if (file_exists($preview_css_path)) {
				$css_url     = $css_file_url . "theplus_gutenberg/plus-preview-{$post_id}.css";
				
				if (!$this->is_editor_screen()) {
					wp_enqueue_style("plus-preview-{$post_id}", $css_url, false, $plus_version);
				}
			}

		}else{
			if (file_exists($css_path)) {
				$css_url     = $css_file_url . "theplus_gutenberg/plus-css-{$post_id}.css";

				if (!$this->is_editor_screen()) {
					wp_enqueue_style("plus-post-{$post_id}", $css_url, false, $plus_version);
				}

				if( !isset($_GET['preview']) && empty($_GET['preview']) ){
					$css_preview_path = $upload_base_dir . "theplus_gutenberg/plus-preview-{$post_id}.css";
					if (file_exists($css_preview_path)) {
						unlink($css_preview_path);
					}
				}
			}else if(!file_exists($css_path)){
				$this->make_block_css_by_post_id($post_id);
			}
		}
		
		$queried_obj = get_queried_object_id();
		if(is_search()){
			$queried_obj = 'search';
		}
		if(is_404()){
			$queried_obj = '404';
		}
		
		$optionName = 'tpgb-load-templates-list';
		$get_opt = get_option($optionName);
		if(!empty($get_opt) && isset($get_opt[$queried_obj]) && !empty($get_opt[$queried_obj]) ){
			foreach($get_opt[$queried_obj] as $template_id){
				$css_path = $upload_base_dir . "theplus_gutenberg/plus-css-{$template_id}.css";
				if (file_exists($css_path)) {
					$css_url     = $css_file_url . "theplus_gutenberg/plus-css-{$template_id}.css";
					$plus_version=get_post_meta( $template_id, '_block_css', true );
					if(empty($plus_version)){
						$plus_version = TPGB_VERSION;
					}
					if (!$this->is_editor_screen()) {
						wp_enqueue_style("plus-post-{$template_id}", $css_url, false, $plus_version);
					}
				}
			}
		}
	}
	
	/**
	 * Get Reference ID
	 * @since 1.1.1
	 */
	public function block_reference_id( $res_blocks ) {
		$ref_id = array();
		if ( ! empty( $res_blocks ) ) {
			foreach ( $res_blocks as $key => $block ) {
				if ( $block['blockName'] == 'core/block' ) {
					$ref_id[] = $block['attrs']['ref'];
				}
				if ( count( $block['innerBlocks'] ) > 0 ) {
					$ref_id = array_merge( $this->block_reference_id( $block['innerBlocks'] ), $ref_id );
				}
			}
		}
		return $ref_id;
	}
	
	/*
	 * Frontend Reusable Block Load Css
	 * @since 1.1.1
	 */
	public function tpgb_reusable_block_css(){
		$post_id = $this->is_tpgb_post_id();
		
		if ( $post_id ) {
			$post_content = get_post( $post_id );
			if ( isset( $post_content->post_content ) ) {
				$content = $post_content->post_content;
				$parse_blocks = parse_blocks( $content );
				$res_id = $this->block_reference_id( $parse_blocks );
				if ( is_array( $res_id ) && ! empty( $res_id )) {
					$res_id = array_unique( $res_id );
					
					foreach ( $res_id as $value ) {
						$this->enqueue_post_css($value);
					}
				}
			}
		}
	}

	/*
	 * Enqueue Post Id Load Css
	 * @since 1.1.1
	 */
	public function enqueue_post_css($post_id = ''){
		if(!empty($post_id)){
			array_push($this->template_ids, $post_id);
			$upload_dir			= wp_get_upload_dir();
			$upload_base_dir 	= trailingslashit($upload_dir['basedir']);
			$css_path			= $upload_base_dir . "theplus_gutenberg/plus-css-{$post_id}.css";
			$preview_css_path	= $upload_base_dir . "theplus_gutenberg/plus-preview-{$post_id}.css";
			
			$plus_version=get_post_meta( $post_id, '_block_css', true );
			if(empty($plus_version)){
				$plus_version=time();
			}
			
			if( isset($_GET['preview']) && $_GET['preview'] == true && file_exists($preview_css_path)){
				$css_file_url = trailingslashit($upload_dir['baseurl']);
				$css_url     = $css_file_url . "theplus_gutenberg/plus-preview-{$post_id}.css";
				if (!$this->is_editor_screen()) {
					wp_enqueue_style("plus-preview-{$post_id}", $css_url, false, $plus_version);
				}
			}else if (file_exists($css_path)) {
				
				$css_file_url = trailingslashit($upload_dir['baseurl']);
				$css_url     = $css_file_url . "theplus_gutenberg/plus-css-{$post_id}.css";
				if (!$this->is_editor_screen()) {
					wp_enqueue_style("plus-post-{$post_id}", $css_url, false, $plus_version);
				}
			}else if(!file_exists($css_path)){
				$this->make_block_css_by_post_id($post_id);
			}
		}
	}
	
	/*
	 * Admin Enqueue Scripts
	 **/
	public function admin_enqueue_css_js(){
		wp_enqueue_style( 'tpgb-admin-css', TPGB_URL .'assets/css/admin/tpgb-admin.css', array(),TPGB_VERSION,false );
		wp_enqueue_script( 'tpgb-admin-js', TPGB_URL . 'assets/js/admin/tpgb-admin.js',array() , TPGB_VERSION, true );
		wp_localize_script(
			'tpgb-admin-js', 'tpgb_admin', array(
				'ajax_url' => esc_url( admin_url('admin-ajax.php') ),
				'tpgb_nonce' => wp_create_nonce("tpgb-addons"),
			)
		);
	}
	
	/**
	 * Check wpdb_editor backend
	 *
	 * @since 1.0.0
	 * @return bool
	 *
	 */
	private function is_editor_screen(){
		if (!empty($_GET['action']) &&  $_GET['action'] === 'wppb_editor') {
			return true;
		}
		return false;
	}
	
	/*
	 * Get Featured Image Url.
	 * @since 1.0.0
	 */
	public function tpgb_get_featured_image_url($obj){
		
		$images = array();
		if (!isset($obj['featured_media'])) {
			$images['default'] = TPGB_URL .'assets/images/tpgb-placeholder.jpg';
			return $images;
		} else {
			$image = wp_get_attachment_image_src($obj['featured_media'], 'full', false);
			if (is_array($image)) {
				$images['full'] = $image;
				$images['tp-image-grid'] = wp_get_attachment_image_src($obj['featured_media'], 'tp-image-grid', false);
				$images['thumbnail'] = wp_get_attachment_image_src($obj['featured_media'], 'thumbnail', false);
				$images['medium'] = wp_get_attachment_image_src($obj['featured_media'], 'medium', false);
				$images['medium_large'] = wp_get_attachment_image_src($obj['featured_media'], 'medium_large', false);
				$images['large'] = wp_get_attachment_image_src($obj['featured_media'], 'large', false);
				$images['default'] = TPGB_URL .'assets/images/tpgb-placeholder.jpg';
				
				return $images;
			}
		}
	}
	
	/*
	 * Get Post Meta Info.
	 * @since 1.1.1
	 */
	public function tpgb_get_post_meta_info($obj){
		
		$post_meta = array();
		if (!isset($obj['id'])) {
			return $post_meta;
		} else {
		
			$data_date = get_the_date('',$obj['id']);
			if(!empty($data_date)){
				$post_meta['get_date'] = $data_date;
			}
			get_the_category_list( __( ', ', 'tpgb' ), '', $obj['id'] );
			$post_type = isset($obj['type']) ? $obj['type'] : '';
			$taxonomies_list = $this->tpgb_get_taxnomy_terms( $post_type );
			if(!empty($taxonomies_list)){
				foreach ( $taxonomies_list as $key => $value ) {
					if(!empty($value)){
						$terms = get_the_terms( $obj['id'], $value, array("hide_empty" => true) );
						$post_meta['category_list'][$value] = $terms;
					}
				}
			}
			
			if(!empty($obj['author'])){
				$post_meta['author_name'] = get_the_author_meta('display_name', $obj['author']);
				$post_meta['author_url'] = get_author_posts_url($obj['author']);
				$post_meta['author_email'] =  get_the_author_meta('email',$obj['author']);
				$post_meta['author_website'] = get_the_author_meta('user_url', $obj['author']);
				$post_meta['author_description'] = get_the_author_meta('user_description', $obj['author']);
				$post_meta['author_facebook'] = get_the_author_meta('author_facebook', $obj['author']);
				$post_meta['author_twitter'] = get_the_author_meta('author_twitter', $obj['author']);
				$post_meta['author_instagram'] = get_the_author_meta('author_instagram', $obj['author']);
				$post_meta['author_role'] = get_the_author_meta('roles', $obj['author']);
				
				global $user;  
				$author_avatar = get_avatar( get_the_author_meta('ID'), 200);
				if($author_avatar){
					$post_meta['author_avatar'] = $author_avatar;
				}
			}
			
			$comments_count = wp_count_comments($obj['id']);
			if(!empty($comments_count)){
				$post_meta['comment_count'] = $comments_count->total_comments;
			}
			
			$post_like = get_post_meta( $obj['id'], 'tpgb_post_likes', true );
			$post_meta['post_likes'] = (!empty($post_like)) ? $post_like : 0;
			$post_view = get_post_meta( $obj['id'], 'tpgb_post_viwes', true );
			$post_meta['post_views'] = (!empty($post_view)) ? $post_view : 0;
		}
		return $post_meta;
	}
	
	// Get Category Lists
    public function tpgb_get_category_list($obj){
		$meta_list= [];
		if(isset($obj['id']) && isset($obj['type']) && !empty($obj['type'])){
			
			$taxonomies_list = $this->tpgb_get_taxnomy_terms( $obj['type'] );
			if(!empty($taxonomies_list)){
				foreach ( $taxonomies_list as $key => $value ) {
					if(!empty($value)){
						$terms = get_the_terms( $obj['id'], $value, array("hide_empty" => true) );
						if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
							$render_list = '';
							foreach ( $terms as $term ) {
								$render_list .= '<a href="' . esc_url( get_term_link( $term ) ) . '" alt="' . esc_attr( sprintf( __( '%s', 'tpgb' ), $term->name ) ) . '">' . $term->name . '</a> ';
							}
							$meta_list[$value] = $render_list;
						}
					}
				}
			}
		}
		return $meta_list;
    }
	
	/**
	 * Get Taxonomy List
	 * @since 1.1.2
	 */
	public function tpgb_get_taxnomy_terms( $post_type = ''){
		$terms_list = [];
		if(!empty($post_type)){
			$taxonomies = get_object_taxonomies( $post_type, 'objects' );
			$taxonomies_list = wp_filter_object_list( $taxonomies, [
				'public' => true,
				'show_in_nav_menus' => true,
			] );
			if(!empty($taxonomies_list)){
				foreach ( $taxonomies_list as $slug => $object ) {
					if(isset($object->name)){
						$terms_list[] = $object->name;
					}
				}
			}
		}
		return $terms_list;
	}
	
	/**
	 * rest api Product Data
	 * @since 1.1.2
	 */
	public function tpgb_get_product_data($obj){
		$product_data = array();
		if (!isset($obj['id'])) {
			return $product_data;
		} else { 
			$product1 = wc_get_product( $obj['id'] );
			$product_data['price_html'] = $product1->get_price_html();
			$product_data['type'] = $product1->get_type();

			//Set Gallery Image Src
			$img_Id = $product1->get_gallery_image_ids();
			$img_Id = (isset($img_Id[0])) ? $img_Id[0] : '';
			$product_data['gallery'] = wp_get_attachment_image_src($img_Id,'full');

			$terms = get_the_terms( $obj['id'], 'product_cat' );
			$product_data['category'] = $terms[0]->name;
			if($product1->get_rating_count() > 0){
				$product_data['productRating'] = wc_get_rating_html( $product1->get_average_rating() );
			}
			
			include_once(ABSPATH.'wp-admin/includes/plugin.php');
			if( is_plugin_active('yith-woocommerce-compare/init.php') ){
				$product_data['yithcompare'] = '<a href="'.home_url().'?action=yith-woocompare-add-product&id='.esc_attr( $obj['id']).'" class="compare button" data-product_id="'.esc_attr( $obj['id']).'" rel="nofollow"><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="exchange-alt" class="tpgb-yith-icon svg-inline--fa fa-exchange-alt fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M0 168v-16c0-13.255 10.745-24 24-24h360V80c0-21.367 25.899-32.042 40.971-16.971l80 80c9.372 9.373 9.372 24.569 0 33.941l-80 80C409.956 271.982 384 261.456 384 240v-48H24c-13.255 0-24-10.745-24-24zm488 152H128v-48c0-21.314-25.862-32.08-40.971-16.971l-80 80c-9.372 9.373-9.372 24.569 0 33.941l80 80C102.057 463.997 128 453.437 128 432v-48h360c13.255 0 24-10.745 24-24v-16c0-13.255-10.745-24-24-24z"></path></svg></a>';
			}

			
			if( shortcode_exists( 'yith_wcwl_add_to_wishlist' ) ){
				$product_data['yithwishlist'] = do_shortcode('[yith_wcwl_add_to_wishlist icon="fas fa-heart" label="" already_in_wishslist_text="" browse_wishlist_text=""]' );
			}
			
			if( is_plugin_active('yith-woocommerce-quick-view/init.php') ){
				$product_data['yithquickView'] = do_shortcode('[yith_quick_view product_id='.esc_attr($obj['id']).' icons="fas fa-eye"]');
			}
			
			$status = get_post_meta($obj['id'], '_stock_status',true);

			global $post, $product;
			if ($status == 'outofstock') {
				$product_data['productBadge'] =  '<span class="badge out-of-stock">Out Of stock</span>';
			} else if ( $product->is_on_sale() ) {
				if ('discount' == 'discount') {
					if ($product->get_type() == 'variable') {
						$available_variations = $product->get_available_variations();								
						$maximumper = 0;
						for ($i = 0; $i < count($available_variations); ++$i) {
							$variation_id=$available_variations[$i]['variation_id'];
							$variable_product1= new WC_Product_Variation( $variation_id );
							$regular_price = $variable_product1->get_regular_price();
							$sales_price = $variable_product1->get_sale_price();
							$percentage = $sales_price ? round( (($regular_price - $sales_price) / $regular_price) * 100) : 0;
							if ($percentage > $maximumper) {
								$maximumper = $percentage;
							}
						}
						$product_data['productBadge'] = apply_filters('woocommerce_sale_flash', '<span class="badge onsale perc">&darr; '.$maximumper.'%</span>', $post, $product);
					} else if ($product->get_type() == 'simple'){
						$percentage = round( (($product->get_regular_price() - $product->get_sale_price()) / $product->get_regular_price() ) * 100);
						$product_data['productBadge'] = apply_filters('woocommerce_sale_flash', '<span class="badge onsale perc">&darr; '.$percentage.'%</span>', $post, $product);
					} else if ($product->get_type() == 'external'){
						$percentage = round( (($product->get_regular_price() - $product->get_sale_price()) / $product->get_regular_price() ) * 100);
						$product_data['productBadge'] = apply_filters('woocommerce_sale_flash', '<span class="badge onsale perc">&darr; '.$percentage.'%</span>', $post, $product);
					}
				} else {
					$product_data['productBadge'] = apply_filters('woocommerce_sale_flash', '<span class="badge onsale">'.esc_html__( 'Sale','tpgb' ).'</span>', $post, $product);
				}
			}

			return $product_data;
		}	
		
	}
	
	/**
	 * @since 1.0.0
	 * Import font set to the CSS file
	 */
	public function set_font_import_css($post_css = ''){
		$font_css_url = "@import url('https://fonts.googleapis.com/css?family=";
		$google_font_exists = substr_count($post_css, $font_css_url);

		if ($google_font_exists) {
			$pattern_url = sprintf(
				'/%s(.+?)%s/ims',
				preg_quote($font_css_url, '/'),
				preg_quote("');", '/')
			);

			if (preg_match_all($pattern_url, $post_css, $matches)) {
				$fonts = $matches[0];
				$post_css = str_replace($fonts, '', $post_css);
				if (preg_match_all('/font-weight[ ]?:[ ]?[\d]{3}[ ]?;/', $post_css, $matche_weight)) { // short out font weight
					$weight = array_map(function ($val) {
						$process = trim(str_replace(array('font-weight', ':', ';'), '', $val));
						if (is_numeric($process)) {
							return $process;
						}
					}, $matche_weight[0]);
					foreach ($fonts as $key => $val) {
						$fonts[$key] = str_replace("');", '', $val) . ':' . implode(',', $weight) . "');";
					}
				}

				//Multiple same fonts to single font
				$fonts = array_unique($fonts);
				$post_css = implode('', $fonts) . $post_css;
			}
		}
		return $post_css;
	}
	
	/**
	 * @return bool|false|int
	 *
	 * get post id current page id
	 */
	private function is_tpgb_post_id(){
		$post_id = get_the_ID();
		
		if (!$post_id) {
			return false;
		}
		return $post_id;
	}
	
	/**
	 * Delete dynamic post releated data
	 * @delete post css file
	 */
	private function delete_post_dynamic($post_id = '', $is_preview=false){
		$post_id = $post_id ? $post_id : $this->is_tpgb_post_id();
		if ($post_id) {
			$upload_dir     = wp_get_upload_dir();
			$upload_css_dir = trailingslashit($upload_dir['basedir']);
			if($is_preview==false){
				$css_path       = $upload_css_dir . "theplus_gutenberg/plus-css-{$post_id}.css";
				if (file_exists($css_path)) {
					unlink($css_path);
				}
			}
			$css_preview_path	= $upload_css_dir . "theplus_gutenberg/plus-preview-{$post_id}.css";
			if (file_exists($css_preview_path)) {
				unlink($css_preview_path);
			}
		}
	}
	
	/*
	 * Admin Bar enqueue Scripts
	 * @since 1.2.0
	 */
	public function admin_bar_enqueue_scripts(){
		global $wp_admin_bar;
		
		if ( ! is_super_admin()
			 || ! is_object( $wp_admin_bar ) 
			 || ! function_exists( 'is_admin_bar_showing' ) 
			 || ! is_admin_bar_showing() ) {
			return;
		}
		
		if(class_exists('Tpgb_Library')){
			$tpgb_libraby = Tpgb_Library::get_instance();
			if(isset($tpgb_libraby->plus_template_blocks)){
				$this->template_ids = array_unique(array_merge($this->template_ids, $tpgb_libraby->plus_template_blocks));
				
			}
		}
		
		// Load js 'tpgb-admin-bar' before 'admin-bar'
		wp_dequeue_script( 'admin-bar' );

		wp_enqueue_script(
			'tpgb-admin-bar',
			TPGB_URL."assets/js/main/general/tpgb-admin-bar.min.js",
			[],
			TPGB_VERSION,
			true
		);

		wp_enqueue_script( // phpcs:ignore WordPress.WP.EnqueuedResourceParameters
			'admin-bar',
			null,
			[ 'tpgb-admin-bar' ],
			false,
			true
		);
		
		$template_list = [];
		if(!empty($this->template_ids)){
			foreach($this->template_ids as $key => $post_id){
				if(!isset($template_list[$post_id])){
					$posts = get_post($post_id);
					if(isset($posts->post_title)){
						$template_list[$post_id]['id'] = $post_id;
						$template_list[$post_id]['title'] = $posts->post_title;
						$template_list[$post_id]['edit_url'] = esc_url( get_edit_post_link( $post_id ) );
					}
					if(isset($posts->post_type)){
						$template_list[$post_id]['post_type'] = $posts->post_type;
						$post_type_obj = get_post_type_object( $posts->post_type );
						$template_list[$post_id]['post_type_name'] = $post_type_obj->labels->singular_name;
						
						if($posts->post_type==='nxt_builder'){
							if ( get_post_meta( $post_id, 'nxt-hooks-layout', true ) ){
								$layout = get_post_meta( $post_id, 'nxt-hooks-layout', true );
								$type = '';
								if(!empty($layout) && $layout==='sections'){
									$type = get_post_meta( $post_id, 'nxt-hooks-layout-sections', true );
								}else if(!empty($layout) && $layout==='pages'){
									$type = get_post_meta( $post_id, 'nxt-hooks-layout-pages', true );
								}else if(!empty($layout) && $layout==='code_snippet'){
									$type = get_post_meta( $post_id, 'nxt-hooks-layout-code-snippet', true );
								}else if(!empty($layout) && $layout==='none'){
									unset($template_list[$post_id]);
								}
								if(isset($template_list[$post_id])){
									$template_list[$post_id]['nexter_layout'] = $layout;
									$template_list[$post_id]['nexter_type'] = $type;
								}
							}
						}
					}
				}
			}
		}
		
		$template_list1 = array_column($template_list, 'post_type');
		array_multisort($template_list1, SORT_DESC, $template_list);
		$tpgb_template = ['tpgb_edit_template' => $template_list ];
		$scripts = 'var TpgbAdminbar = '. wp_json_encode($tpgb_template);

		wp_add_inline_script( 'tpgb-admin-bar', $scripts, 'before' );
	}
}

Tp_Core_Init_Blocks::get_instance();
?>