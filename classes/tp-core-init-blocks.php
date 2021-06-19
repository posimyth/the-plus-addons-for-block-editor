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
		
		
		
		add_filter( 'block_categories', array( $this, 'tp_register_block_category' ), 10, 2 );
		
		require_once TPGB_PATH.'classes/tp-registered-blocks.php';
		tpgb_library();
		
		add_action( 'enqueue_block_assets', array( $this, 'tp_block_assets' ) ); //front end load
		add_action( 'enqueue_block_editor_assets', array( $this, 'editor_assets' ) ); //Gutenberg editor load
		
		$this->tpgb_global_settings_post_meta();
		
		add_action('rest_api_init', array($this, 'plus_register_api_hook'));
		add_action('after_setup_theme', array($this, 'plus_add_image_size'));
		
		//Load Css/Js File blocks
		add_action('wp_enqueue_scripts', array($this, 'enqueue_load_block_css_js'));
		add_action('wp_enqueue_scripts', array($this, 'enqueue_post_css'));
		add_action('wp_footer', array($this, 'template_load_block_css_js'));
		add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_css_js'));
	}
	
	/**
	 * Plus Image Size Gutenberg block.
	 * @since 1.0.0
	 */
	public function plus_add_image_size(){
		add_image_size( 'tp-image-grid', 700, 700, true);
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
			
			// Load Plus Style Editor Block
			wp_enqueue_style(
				'tpgb-plus-block-editor-css',
				tpgb_library()->pathurl_security($css_file),
				array('wp-edit-blocks'),
				TPGB_VERSION
			);
			
			// Load Plus Script Editor Block
			wp_enqueue_script(
				'tpgb-plus-block-editor-js',
				tpgb_library()->pathurl_security($js_file),
				['jquery'],
				TPGB_VERSION,
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
     * @since 1.0.0
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
		
		$wp_localize_tpgb = array(
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
			'fontawesome' => false,
			'contactform_list' => Tp_Blocks_Helper::get_contact_form_post(),
			'preview_image' => esc_url(TPGB_URL .'assets/images/tpgb-placeholder.jpg'),
			'taxonomy_list' => Tp_Blocks_Helper::tpgb_get_post_taxonomies(),
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
			'post',
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
	 * @since 1.1.1
	 */
	public function tpgb_get_post_content( $request ) {
		$params = $request->get_params();
		try {
			if ( isset( $params['post_id'] ) ) {
				return array(
					'success' => true,
					'data'    => get_post( $params['post_id'] )->post_content,
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
	 * @since 1.0.0
	 * Save block css 
	 */
	public function  plus_save_block_css($request)
	{
		try {
			global $wp_filesystem;
			if (!$wp_filesystem) {
				require_once(ABSPATH . 'wp-admin/includes/file.php');
			}

			$params = $request->get_params();
			$is_preview = $params['is_preview'];
			$post_id = (int) sanitize_text_field($params['post_id']);

			if ($params['is_block']) {
				$block_css = $params['block_css'];
				$filename = "plus-css-{$post_id}.css";

				$upload_dir = wp_upload_dir();
				$dir = trailingslashit($upload_dir['basedir']) . 'theplus_gutenberg/';
				
				$global_css = (!empty($params['global_css'])) ? $params['global_css'] : '';
				$globalfilename = "plus-global.css";
				$import_global_first = '';
				if(!empty($global_css)){
					$import_global_first = $this->set_font_import_css($global_css);
				}
				
				$import_css = $this->set_font_import_css($block_css);

				if($is_preview==true){
					$globalfilename = "plus-global-preview.css";
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
				
				if (!$wp_filesystem->put_contents($dir . $globalfilename, $import_global_first)) {
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
	
	
	/*
	 * Frontend Enqueue Scripts
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
		}
		
		if( isset($_GET['preview']) && $_GET['preview'] == true && file_exists($preview_css_path)){
			$global_path = $upload_base_dir . "theplus_gutenberg/plus-global-preview.css";
			
			if (file_exists($preview_css_path)) {
				$css_file_url = trailingslashit($upload_dir['baseurl']);
				$css_url     = $css_file_url . "theplus_gutenberg/plus-preview-{$post_id}.css";
				
				if (file_exists($global_path) && !$this->is_editor_screen()) {
					$global_url     = $css_file_url . "theplus_gutenberg/plus-global-preview.css";
					wp_enqueue_style("plus-global-preview", $global_url, false, $plus_version);
				}else if (file_exists($upload_base_dir . "theplus_gutenberg/plus-global.css") && !$this->is_editor_screen()) {
					$global_url     = $css_file_url . "theplus_gutenberg/plus-global.css";
					wp_enqueue_style("plus-global", $global_url, false, $plus_version);
				}
				$this->tpgb_reusable_block_css();
				if (!$this->is_editor_screen()) {
					wp_enqueue_style("plus-preview-{$post_id}", $css_url, false, $plus_version);
				}
			}
			
		}else{
			if (file_exists($css_path)) {
				$css_file_url = trailingslashit($upload_dir['baseurl']);
				$css_url     = $css_file_url . "theplus_gutenberg/plus-css-{$post_id}.css";
				
				if (file_exists($global_path) && !$this->is_editor_screen()) {
					$global_url     = $css_file_url . "theplus_gutenberg/plus-global.css";
					wp_enqueue_style("plus-global", $global_url, false, $plus_version);
				}
				if (!$this->is_editor_screen()) {
					wp_enqueue_style("plus-post-{$post_id}", $css_url, false, $plus_version);
				}
				
				$this->tpgb_reusable_block_css();
				if( !isset($_GET['preview']) && empty($_GET['preview']) ){
					$css_preview_path = $upload_base_dir . "theplus_gutenberg/plus-preview-{$post_id}.css";
					if (file_exists($css_preview_path)) {
						unlink($css_preview_path);
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
	 * Frontend Enqueue Scripts
	 **/
	public function template_load_block_css_js(){
		$post_ids = [];
		if(has_filter('tpgb_template_get_post_id')) {
			$post_ids = apply_filters('tpgb_template_get_post_id', $post_ids);
		}
		
		if(!empty($post_ids)){
			foreach($post_ids as $post_id){
				$this->enqueue_post_css($post_id);
			}
		}
	}
	
	/*
	 * Enqueue Post Id Load Css
	 * @since 1.1.1
	 */
	public function enqueue_post_css($post_id = ''){
		if(!empty($post_id)){
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
			$images['default'] = TPGB_URL .'assets/image/tpgb-placeholder.jpg';
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
				$images['default'] = TPGB_URL .'assets/image/tpgb-placeholder.jpg';
				
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
			$category_terms = get_the_terms( $obj['id'], 'category', array("hide_empty" => true) );
			$post_meta['category_list'] = $category_terms;
			
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
		if (isset($obj['id'])) {
			return get_the_category_list(' ', '', $obj['id']);
		}else{
			return;
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
}

Tp_Core_Init_Blocks::get_instance();
?>