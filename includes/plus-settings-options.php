<?php 
/**
 * TPGB Gutenberg Settings Options
 * @since 1.0.0
 *
 */
if (!defined('ABSPATH')) {
    exit;
}

class Tpgb_Gutenberg_Settings_Options {
	
	/**
     * Setting Name/Title
     * @var string
     */
    protected $setting_name = '';
	
	/**
     * Options Page hook
     * @var string
     */
    protected $block_lists = [];
	protected $block_extra = [];
	
	/**
     * Constructor
     * @since 1.0.0
     */
    public function __construct() {
		if( is_admin() ){
            
            add_action('init', [$this, 'nexter_block_init']);
		
			add_action( 'wp_ajax_tpgb_blocks_opts_save', array( $this,'tpgb_blocks_opts_save_action') );
			add_action( 'wp_ajax_tpgb_connection_data_save', array( $this,'tpgb_connection_data_save_action') );
			add_action( 'wp_ajax_tpgb_custom_css_js_save', array( $this,'tpgb_custom_css_js_save_action') );
			
			add_action( 'wp_ajax_tpgb_is_block_used_not', array( $this, 'tpgb_is_block_used_not_fun' ) );
			add_action( 'wp_ajax_tpgb_unused_disable_block', array( $this, 'tpgb_disable_unsed_block_fun' ) );
			add_action( 'wp_ajax_tpgb_performance_opt_cache', array( $this,'tpgb_performance_opt_cache_save_action') );
			
			add_action( 'admin_enqueue_scripts', array( $this, 'tpgb_dash_admin_scripts' ), 10, 1 );

			// Install WdesignKit
			add_action( 'wp_ajax_nxt_install_wdesign', array( $this,'nxt_install_wdesign') );

			// Remove All Notice From Dashboard Screnn
			add_action( 'admin_head', array( $this,'nxt_remove_admin_notices_page') );

			// Install Nexter Theme
			add_action( 'wp_ajax_nxt_install_theme', array( $this,'nxt_install_theme') );

			// add Filter to Enable All Block 
			add_filter( 'tpgb_blocks_enable_all', array( $this, 'tpgb_blocks_enable_all_filter' ) );

			// Scan Unused Block & Disabled it filter function
			add_filter( 'tpgb_disable_unsed_block_filter', array( $this, 'tpgb_disable_unsed_block_filter_fun' ) );

            // Wdesignkit Block Enable Ajax
            add_action( 'wp_ajax_nxt_wdk_widget_ajax_call', array( $this,'nxt_wdk_widget_ajax_call') );
			
            // WDesignKit Template Block List Merge hook
            add_filter( 'nexter_block_list_merge', [$this, 'nexter_block_list_merge_action'] , 10, 1 );

            // Store User Data in Database From Onborading
            add_action('wp_ajax_nxt_boarding_store', array($this, 'nxt_block_boarding_store'));
		}
		
    }

    /**
     * Initiate Nexter Block
     * @since 4.2.0
     */

    public function nexter_block_init(){
        if(defined('TPGBP_VERSION')){
            $options = get_option( 'tpgb_white_label' );
            $this->setting_name = (!empty($options['tpgb_plugin_name'])) ? $options['tpgb_plugin_name'] : __('Nexter Blocks','the-plus-addons-for-block-editor');
        }else{
            $this->setting_name = esc_html__('Nexter Blocks', 'the-plus-addons-for-block-editor');
        }

        $this->block_listout();
    }

	/**
     * Initiate our hooks
     * @since 1.0.0
     */
	public function hooks() {
		if( is_admin() ){
            add_action( 'nxt_new_update_notice' , array( $this, 'nxt_new_update_notice_callback' ) );
			add_action('admin_menu', array( $this, 'add_options_page' ));
		}
    }
	
    /**
     * Add action to Update Notice Count
     * @since 4.2.1
     */

    public function nxt_new_update_notice_callback(){
        $data = get_option( 'nxt_menu_notice_count', [] );
        if ( ! is_array( $data ) ) {
            $data = [];
        }
        $flag = isset( $data['notice_flag'] ) ? intval( $data['notice_flag'] ) : 1;
        $data['menu_notice_count'] = $flag;
        update_option( 'nxt_menu_notice_count', $data );

    }

    /**
     * Condition to Check Notice Show
     * @since 4.2.1
     */

     public function nxt_notice_should_show(){
        $data = get_option( 'nxt_menu_notice_count', [] );
        if ( ! is_array( $data ) ) {
            return false;
        }
        $menu_count = isset( $data['menu_notice_count'] ) ? intval( $data['menu_notice_count'] ) : 0;
        $flag       = isset( $data['notice_flag'] ) ? intval( $data['notice_flag'] ) : 1;
        return $menu_count < $flag;
    }

	/**
     * Add menu options page
     * @since 1.0.0
     */
	public function add_options_page(){
		add_menu_page( $this->setting_name, $this->setting_name, 'manage_options', 'nexter_welcome_page', array( $this, 'admin_page_display' ),'dashicons-tpgb-plus-settings' , 58.5 );

		add_submenu_page( 'nexter_welcome_page', esc_html__( 'Patterns', 'the-plus-addons-for-block-editor' ), esc_html__( 'Patterns', 'the-plus-addons-for-block-editor' ), 'manage_options', esc_url( admin_url('edit.php?post_type=wp_block') ));

		if( !defined('TPGBP_VERSION') ){
			add_submenu_page( 'nexter_welcome_page', esc_html__( 'Upgrade Now', 'the-plus-addons-for-block-editor' ), esc_html__( 'Upgrade Now', 'the-plus-addons-for-block-editor' ), 'manage_options', esc_url('https://nexterwp.com/pricing/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings'));
		}

		add_action('admin_footer', array($this, 'nxt_link_in_new_tab'));

		// Hook to modify the submenu head title
		add_action('admin_menu', array($this, 'nxt_submenu_head_title') , 101);
	}

	/**
     * Parent Page Rename in Sub menu
     * @since 2.0.0
     */
	public function nxt_submenu_head_title() {
		global $submenu;
		if ( isset($submenu['nexter_welcome_page'] )) {
			$submenu['nexter_welcome_page'][0][0] = esc_html__( 'Dashboard', 'the-plus-addons-for-block-editor' );
		}
	}

	/**
     * Open Link in New Tab Wordpress Menu
     * @since 2.0.0
     */
	public function nxt_link_in_new_tab(){
		?>
		<script type="text/javascript">
			document.addEventListener('DOMContentLoaded', function() {
				var upgradeLink = document.querySelector('a[href*="https://nexterwp.com/pricing/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings"]');
				if (upgradeLink) {
					upgradeLink.setAttribute('target', '_blank');
					upgradeLink.setAttribute('rel', 'noopener noreferrer');
				}
                <?php if( $this->nxt_notice_should_show() ) { ?>
                    var menuItem = document.querySelector('.toplevel_page_nexter_welcome_page.menu-top');
                    if (menuItem) {
                        menuItem.classList.add('nxt-admin-notice-active');
                    }
                <?php } ?>
			});
		</script>
		<?php
	}

	
	/**
	 * Enqueue DashBoard Scripts admin area.
	 *
	 * @since   1.0.0
	 *
	 * @param string $page use for check page type.
	 */
	public function tpgb_dash_admin_scripts( $page ) {
		
		$slug = array( 'toplevel_page_nexter_welcome_page' );
		if ( ! in_array( $page, $slug, true ) ) {
			return;
		}

		$this->tpgb_dash_enqueue_style();
		$this->tpgb_dash_enqueue_scripts();
	}

	/**
	 * Enqueue Styles admin area.
	 *
	 * @since   1.0.0
	 *
	 * @param string $page use for check page type.
	 */
	public function tpgb_dash_enqueue_style() {
		wp_enqueue_style( 'tpgb-dash-style', TPGB_URL . 'dashboard/build/index.css', array(), TPGB_VERSION, 'all' );
	}

	/**
	 * Enqueue script admin area.
	 *
	 * @since   1.0.0
	 */
	public function tpgb_dash_enqueue_scripts() {
		$user = wp_get_current_user();
		$default_load=get_option( 'tpgb_normal_blocks_opts' );
		$rollback_url = wp_nonce_url(admin_url('admin-post.php?action=tpgb_rollback&version=TPGB_VERSION'), 'tpgb_rollback');
		$dashData = [];
		$wdadded = false;
		$nxtextension = false;
		$uichemy = false;
        $nxtheme = false;
        $plusAddons = false ;

		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		$pluginslist = get_plugins();
		if ( isset( $pluginslist[ 'wdesignkit/wdesignkit.php' ] ) && !empty( $pluginslist[ 'wdesignkit/wdesignkit.php' ] ) ) {
			if( is_plugin_active('wdesignkit/wdesignkit.php') ){
				$wdadded = true;
			}
		}

		if ( isset( $pluginslist[ 'nexter-extension/nexter-extension.php' ] ) && !empty( $pluginslist[ 'nexter-extension/nexter-extension.php' ] ) ) {
			if( is_plugin_active('nexter-extension/nexter-extension.php') ){
				$nxtextension = true;
			}
		}

		if ( isset( $pluginslist[ 'uichemy/uichemy.php' ] ) && !empty( $pluginslist[ 'uichemy/uichemy.php' ] ) ) {
			if( is_plugin_active('uichemy/uichemy.php') ){
				$uichemy = true;
			}
		}

		$active_theme = wp_get_theme();
		$theme_name = $active_theme->get('Name');
		if( isset($theme_name) && !empty($theme_name) && $theme_name == 'Nexter' ){
				$nxtheme = true;
		}else if ( file_exists( WP_CONTENT_DIR.'/themes/'.'nexter') && $theme_name != 'Nexter' ) {
				$nxtheme = 'available';
		}

        if ( isset( $pluginslist[ 'the-plus-addons-for-elementor-page-builder/theplus_elementor_addon.php' ] ) && !empty( $pluginslist[ 'the-plus-addons-for-elementor-page-builder/theplus_elementor_addon.php' ] ) ) {
            if( is_plugin_active('the-plus-addons-for-elementor-page-builder/theplus_elementor_addon.php') ){
                $plusAddons = true;
            }
        }

        //Apply Filters
        $nxt_format_widget = [];
        if ( has_filter( 'nxt_wdk_widget_ajax_call' ) ) {
            $wdk_widget_data = apply_filters('nxt_wdk_widget_ajax_call', 'nxt_wdk_get_widget_ajax');
            foreach ($wdk_widget_data as $block) {
                $uniqueKey = isset($block['title']) ? $block['title'] : 'block_' . $block['id'];
            
                $nxt_format_widget[$uniqueKey] = [
                    'label'      => esc_html($block['title']),
                    'demoUrl'    => esc_url($block['live_demo']),
                    'docUrl'     => '',
                    'videoUrl'   => '',
                    'tag'        => $block['free_pro'] === 'pro' ? 'pro' : 'free',
                    'block_cate' => esc_html__('WDesignKit', 'the-plus-addons-for-block-editor'),
                    'keyword'    => [],
                    'w_unique'   => $block['id'],
                    'uniqueId'   => $block['w_unique'],
                ];
                if (isset($block['w_type']) && !empty($block['w_type'])) {
                    if( $block['w_type'] === 'Publish' ){
                        $nxt_format_widget[$uniqueKey]['w_type'] = 'Publish';
                    }else if( $block['w_type'] === 'Draft' ){
                        $nxt_format_widget[$uniqueKey]['w_type'] = 'Draft';
                    }
                    
                }
            }
        }

		if ( $user ){
			$dashData = [
				'userData' => [
					'userName' => esc_html($user->display_name),
					'profileLink' => esc_url( get_avatar_url( $user->ID ) ),
                    'userEmail' => get_option('admin_email'),
                    'siteUrl' => get_option('siteurl'),
				],
				'blockList' => array_merge($this->block_lists,$this->block_extra,(array) $nxt_format_widget),
				'avtiveBlock' => isset( $default_load['enable_normal_blocks']) && is_array($default_load['enable_normal_blocks']) ? count($default_load['enable_normal_blocks']) : 0,
				'enableBlock' => array_merge( is_array($default_load['enable_normal_blocks']) ? $default_load['enable_normal_blocks'] : [], isset($default_load['tp_extra_option']) && is_array($default_load['tp_extra_option']) ? $default_load['tp_extra_option'] : [] ),
				'extOption' => get_option('tpgb_connection_data'),
				'cacheData' => [ get_option('tpgb_performance_cache') , get_option('tpgb_delay_css_js') , get_option('tpgb_defer_css_js') ],
				'customCode' => get_option('tpgb_custom_css_js'),
				'rollbacVer' => Tpgb_Rollback::get_rollback_versions(),
				'rollbackUrl' => $rollback_url,
				'wdadded' => $wdadded,
				'wdTemplates' => [], 
				'nexterext' => $nxtextension,
				'wpVersion' => get_bloginfo('version'),
				'pluginVer' => TPGB_VERSION,
				'uichemy' => $uichemy,
				'nextheme' => $nxtheme,
				'whiteLabel' => get_option('tpgb_white_label'),
				'keyActmsg' => class_exists('Tpgb_Pro_Library') ? Tpgb_Pro_Library::tpgb_pro_activate_msg() : '',
				'nxtactivateKey' => get_option('tpgb_activate'),
				'activePlan' => ( class_exists('Tpgb_Pro_Library') && method_exists('Tpgb_Pro_Library', 'tpgb_get_activate_plan') ) ? Tpgb_Pro_Library::tpgb_get_activate_plan() : '',
                'showSidebar' => $this->nxt_notice_should_show(),
                'nxt_onboarding' => get_option('nxt_onboarding_done'),
                'plusAddons'=>$plusAddons,
			];
		}

		wp_enqueue_script( 'tpgb-dashscript', TPGB_URL . 'dashboard/build/index.js', array( 'react', 'react-dom', 'wp-dom-ready', 'wp-element','wp-i18n' ), TPGB_VERSION, true );
		wp_set_script_translations( 'tpgb-dashscript', 'the-plus-addons-for-block-editor' );
		wp_localize_script(
			'tpgb-dashscript',
			'tpgb_ajax_object',
			array(
				'adminUrl' => admin_url(),
				'ajax_url'    => admin_url( 'admin-ajax.php' ),
				'nonce'       => wp_create_nonce( 'tpgb-dash-ajax-nonce' ),
				'tpgb_url' => TPGB_URL.'dashboard/',
				'pro' => defined('TPGBP_VERSION'),
				'dashData' => $dashData
			)
		);
	}
	
	/*
	 * Install Wdesignkit Plugin
	 * @since 1.4.0
	 */
	public function nxt_install_wdesign(){
		check_ajax_referer('tpgb-dash-ajax-nonce', 'security');

		if ( ! is_user_logged_in() || ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'content' => __( 'Insufficient permissions.', 'the-plus-addons-for-block-editor' ) ) );
		}

		$plu_slug = ( isset( $_POST['slug'] ) && !empty( $_POST['slug'] ) ) ? sanitize_text_field($_POST['slug']) : '';

        $plugin_file_map = [
            'the-plus-addons-for-elementor-page-builder' => 'theplus_elementor_addon.php',
        ];
        
        $plugin_file = isset($plugin_file_map[$plu_slug]) ? $plugin_file_map[$plu_slug] : $plu_slug.'.php';
        
        $plugin_basename = $plu_slug.'/'.$plugin_file;

		$installed_plugins = get_plugins();

		include_once ABSPATH . 'wp-admin/includes/file.php';
		include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		include_once ABSPATH . 'wp-admin/includes/class-automatic-upgrader-skin.php';
		include_once ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php';

		$result   = array();
		$response = wp_remote_post(
			'http://api.wordpress.org/plugins/info/1.0/',
			array(
				'body' => array(
					'action'  => 'plugin_information',
					'request' => serialize(
						(object) array(
							'slug'   => $plu_slug,
							'fields' => array(
								'version' => false,
							),
						)
					),
				),
			)
		);

		$plugin_info = unserialize( wp_remote_retrieve_body( $response ) );

		if ( ! $plugin_info ) {
			wp_send_json_error( array( 'content' => __( 'Failed to retrieve plugin information.', 'the-plus-addons-for-block-editor' ) ) );
		}

		$skin     = new \Automatic_Upgrader_Skin();
		$upgrader = new \Plugin_Upgrader( $skin );

		$plugin_basename = ''.esc_attr($plu_slug).'/'.esc_attr($plu_slug).'.php';
		
		if ( ! isset( $installed_plugins[ $plugin_basename ] ) && empty( $installed_plugins[ $plugin_basename ] ) ) {
			$installed = $upgrader->install( $plugin_info->download_link );

			$activation_result = activate_plugin( $plugin_basename );

			$success = null === $activation_result;
            add_option('wkit_onbording_end ', true);
			wp_send_json(['Sucees' => true]);

		} elseif ( isset( $installed_plugins[ $plugin_basename ] ) ) {
			$activation_result = activate_plugin( $plugin_basename );

			$success = null === $activation_result;
            add_option('wkit_onbording_end ', true);
			wp_send_json(['Sucees' => true]);

		}
	}

	/*
	 * Install Nexter Theme
	 * @since 2.0.0
	 */
	public function nxt_install_theme(){
		check_ajax_referer('tpgb-dash-ajax-nonce', 'security');

		if ( !current_user_can( 'manage_options' ) ) {
			wp_send_json_error( __( 'You are not allowed to do this action', 'the-plus-addons-for-block-editor' ) );
		}

		$theme_slug = 'nexter';
		$theme_api_url = 'https://api.wordpress.org/themes/info/1.0/';

		// Parameters for the request
		$args = array(
			'body' => array(
				'action' => 'theme_information',
				'request' => serialize((object) array(
					'slug' => 'nexter',
					'fields' => [
						'description' => false,
						'sections' => false,
						'rating' => true,
						'ratings' => false,
						'downloaded' => true,
						'download_link' => true,
						'last_updated' => true,
						'homepage' => true,
                		'tags' => true,
						'template' => true,
						'active_installs' => false,
						'parent' => false,
						'versions' => false,
						'screenshot_url' => true,
						'active_installs' => false
					],
				))),
		);

		// Make the request
		$response = wp_remote_post($theme_api_url, $args);

		// Check for errors
		if (is_wp_error($response)) {
			$error_message = $response->get_error_message();

			wp_send_json(['Sucees' => false]);
		} else {
			$theme_info = unserialize( $response['body'] );
			$theme_name = $theme_info->name;
			$theme_zip_url = $theme_info->download_link;
			global $wp_filesystem;
			// Install the theme
			$theme = wp_remote_get( $theme_zip_url );
			if ( ! function_exists( 'WP_Filesystem' ) ) {
				require_once wp_normalize_path( ABSPATH . '/wp-admin/includes/file.php' );
			}

			WP_Filesystem();

			$active_theme = wp_get_theme();
			$theme_name = $active_theme->get('Name');
			
			$wp_filesystem->put_contents( WP_CONTENT_DIR.'/themes/'.$theme_slug . '.zip', $theme['body'] );
			$zip = new ZipArchive();
			if ( $zip->open( WP_CONTENT_DIR . '/themes/' . $theme_slug . '.zip' ) === true ) {
				$zip->extractTo( WP_CONTENT_DIR . '/themes/' );
				$zip->close();
			}
			$wp_filesystem->delete( WP_CONTENT_DIR . '/themes/' . $theme_slug . '.zip' );
			

			wp_send_json(['Sucees' => true]);
		}
		exit;
	}

	/*
	 * Remove All Notice From Dash Board
	 * @since 2.0.0
	 */
	public function nxt_remove_admin_notices_page(){
		$current_screen = get_current_screen();
    
		if ($current_screen->base == 'toplevel_page_nexter_welcome_page') {
			$this->nxt_remove_all_actions('admin_notices');
			$this->nxt_remove_all_actions('all_admin_notices');
		}
	}

	/*
	 * Helper function to remove all actions for a specific hook
	 * @since 2.0.0
	 */
	public function nxt_remove_all_actions($hook_name){
		global $wp_filter;

		if (isset($wp_filter[$hook_name])) {
			unset($wp_filter[$hook_name]);
		}
	}

	/*
	 * Save Performance Cache Option 
	 * @since 1.4.0
	 */
	public function tpgb_performance_opt_cache_save_action(){
		check_ajax_referer('tpgb-dash-ajax-nonce', 'security');
		$action_page = 'tpgb_performance_cache';
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_redirect( esc_url( admin_url('admin.php?page='.$action_page) ) );
		}
		$perf_caching = wp_unslash( sanitize_text_field( $_POST['perf_caching'] ) );
		if((isset($perf_caching) && !empty($perf_caching)) || isset($_POST['delay_js']) || isset($_POST['defer_js'])){
			if ( FALSE === get_option($action_page) ){
				add_option( $action_page, $perf_caching );
			}else{
				update_option( $action_page, $perf_caching );
			}

			$action_page = 'tpgb_delay_css_js';
			$delay_js = wp_unslash( sanitize_text_field( $_POST['delay_js'] ) );
			if ( FALSE === get_option($action_page) ){
				add_option( $action_page, $delay_js );
			}else{
				update_option( $action_page, $delay_js );
			}
			$action_page = 'tpgb_defer_css_js';
			$defer_js = wp_unslash( sanitize_text_field( $_POST['defer_js'] ) );
			if ( FALSE === get_option($action_page) ){
				add_option( $action_page, $defer_js );
			}else{
				update_option( $action_page, $defer_js );
			}
			wp_send_json_success();
		}
		wp_send_json_error();
	}

	public function tpgb_blocks_opts_save_action() {
		$action_page = 'tpgb_normal_blocks_opts';
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_redirect( esc_url( admin_url('admin.php?page='.$action_page) ) );
		}
		if(isset($_POST["submit-key"]) && !empty($_POST["submit-key"]) && $_POST["submit-key"]=='Save'){
			
			if ( ! isset( $_POST['nonce_tpgb_normal_blocks_opts'] ) || ! wp_verify_nonce( sanitize_key($_POST['nonce_tpgb_normal_blocks_opts']), 'tpgb-dash-ajax-nonce' ) ) { //nonce_tpgb_normal_blocks_action
			   wp_redirect( esc_url(admin_url('admin.php?page='.$action_page)) );
			} else {
				Tpgb_Library()->remove_backend_dir_files();
				if ( FALSE === get_option($action_page) ){
					$default_value = array('enable_normal_blocks' => '' , 'tp_extra_option' => '');
					add_option($action_page,$default_value);
					wp_redirect( esc_url(admin_url('admin.php?page=tpgb_normal_blocks_opts')) );
				}
				else{
					$update_value = array('enable_normal_blocks' => '');
					if(isset($_POST['enable_normal_blocks']) && !empty($_POST['enable_normal_blocks'])){
						$blockList = map_deep(wp_unslash(json_decode(stripslashes($_POST['enable_normal_blocks']), true)), 'sanitize_text_field');

						if(is_array($blockList)){
							$update_value = array('enable_normal_blocks' => $blockList );
						}else{
							$update_value = array('enable_normal_blocks' => sanitize_text_field($blockList) );
						}
					}
					
					$update_extra_val = array('tp_extra_option' => '');
					if(isset($_POST['tp_extra_option']) && !empty($_POST['tp_extra_option'])){
						$extraList = map_deep(wp_unslash(json_decode(stripslashes($_POST['tp_extra_option']), true)), 'sanitize_text_field');
    
						if(is_array($extraList)){
							$update_extra_val = array('tp_extra_option' =>  $extraList);
						}else{
							$update_extra_val = array('tp_extra_option' => sanitize_text_field($extraList) );
						}
					}
					
					$block_value = array_merge( $update_value , $update_extra_val );
					$updated = update_option($action_page, $block_value);

                    $response = '';
                    if ( has_filter( 'nxt_wdk_widget_ajax_call' ) ) {
                        $response = apply_filters( 'nxt_wdk_widget_ajax_call', 'wdk_update_widget' );
                    }

					if ($updated) {
						wp_send_json(['Success' => true]);
					} else {
						wp_send_json(['Success' => false]);
					}
				}
			}

		}else{
			wp_send_json(['Sucees' => false]);
		}
	}

	public function tpgb_connection_data_save_action(){
		$action_page = 'tpgb_connection_data';
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_redirect( esc_url( admin_url('admin.php?page='.$action_page) ) );
		}
		if(isset($_POST["submit-key"]) && !empty($_POST["submit-key"]) && $_POST["submit-key"]=='Save'){
			if ( ! isset( $_POST['nonce_tpgb_connection_data'] ) || ! wp_verify_nonce( sanitize_key($_POST['nonce_tpgb_connection_data']), 'tpgb-dash-ajax-nonce' ) ) {
				wp_redirect( esc_url(admin_url('admin.php?page='.$action_page)) );
			} else {
				$getArr = array_map('sanitize_text_field', $_POST);
				unset($getArr['nonce_tpgb_connection_data']);
				unset($getArr['_wp_http_referer']);
				unset($getArr['action']);
				unset($getArr['submit-key']);

				$getArr = json_decode(stripslashes( $getArr['tpgb_connection_data'] ),true);
				if ( FALSE === get_option($action_page) ){
					$added = add_option($action_page,$getArr);
					if ($added) {
						wp_send_json(['Success' => true]);
					} else {
						wp_send_json(['Success' => false]);
					}
				}else{
					$updated = update_option( $action_page, $getArr );
					if ($updated) {
						wp_send_json(['Success' => true]);
					} else {
						wp_send_json(['Success' => false]);
					}
					wp_redirect( esc_url( admin_url('admin.php?page='.$action_page) ) );
				}
			}
		}else{
			wp_redirect( esc_url( admin_url('admin.php?page='.$action_page) ) );
		}

	}

	public function tpgb_custom_css_js_save_action(){
		$action_page = 'tpgb_custom_css_js';
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json(['Success' => false]);
		}
		if(isset($_POST["submit-key"]) && !empty($_POST["submit-key"]) && $_POST["submit-key"]=='Save'){
			if ( ! isset( $_POST['nonce_tpgb_custom_css_js'] ) || ! wp_verify_nonce( sanitize_key($_POST['nonce_tpgb_custom_css_js']), 'tpgb-dash-ajax-nonce' ) ) {
				wp_send_json(['Success' => false]);
			} else {
				$getArr = $_POST;
				unset($getArr['nonce_tpgb_custom_css_js']);
				unset($getArr['_wp_http_referer']);
				unset($getArr['action']);
				unset($getArr['submit-key']);

				$getArr['tpgb_custom_js_editor'] = isset($getArr['tpgb_custom_js_editor']) ? stripslashes($getArr['tpgb_custom_js_editor']) : '';
				$getArr['tpgb_custom_css_editor'] = isset($getArr['tpgb_custom_css_editor']) ? stripslashes($getArr['tpgb_custom_css_editor']) : '';
				if ( FALSE === get_option($action_page) ){
					add_option($action_page,$getArr);
					wp_send_json(['Success' => true]);
				}else{
					update_option( $action_page, $getArr );
					wp_send_json(['Success' => true]);
				}
			}
		}else{
			wp_send_json(['Success' => false]);
		}
	}

    public function block_listout(){
		$this->block_lists = [
			'tp-accordion' => [
				'label' => esc_html__('Accordion','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-accordion-toggle/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/wordpress-accordion-toggle/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'freemium',
				'block_cate' => esc_html__('Tabbed', 'the-plus-addons-for-block-editor'),
				'keyword' => ['accordion', 'tabs', 'toggle', 'faq', 'collapse', 'show hide content', 'Tiles'],
			],
			'tp-advanced-buttons' => [
				'label' => esc_html__('Pro Buttons', 'the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-pro-button/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/wordpress-pro-button/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'pro',
				'block_cate' => esc_html__('Advanced', 'the-plus-addons-for-block-editor'),
				'keyword' => ['Button', 'CTA', 'link', 'creative button', 'Call to action', 'Marketing Button'],
			],
			'tp-advanced-chart' => [
				'label' => esc_html__('Advanced Chart', 'the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-advanced-charts-and-graph/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/advanced-charts/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'pro',
				'block_cate' => esc_html__('Advanced', 'the-plus-addons-for-block-editor'),
				'keyword' => ['chart', 'diagram'],
			],
			'tp-adv-typo' => [
				'label' => esc_html__('Advanced Typography', 'the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-advanced-typography/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/advanced-typography/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'pro',
				'block_cate' => esc_html__('Advanced', 'the-plus-addons-for-block-editor'),
				'keyword' => ['adv','text','typo'],
			],
			'tp-animated-service-boxes' => [
				'label' => esc_html__('Animated Service Boxes', 'the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-animated-service-boxes/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/animated-service-boxes/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'pro',
				'block_cate' => esc_html__('Advanced', 'the-plus-addons-for-block-editor'),
			],
			'tp-audio-player' => [
				'label' => esc_html__('Audio Player','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-audio-music-player/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/audio-player/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'pro',
				'block_cate' => esc_html__('Creative', 'the-plus-addons-for-block-editor'),
				'keyword' => ['audio player', 'music player'],
			],
			'tp-before-after' => [
				'label' => esc_html__('Before After', 'the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-before-after-image-comparison-slider/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/before-after/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'pro',
				'block_cate' => esc_html__('Creative', 'the-plus-addons-for-block-editor'),
			],
			'tp-blockquote' => [
				'label' => esc_html__('Blockquote','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-blockquote-block/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/blockquote/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'free',
				'block_cate' => esc_html__('Creative', 'the-plus-addons-for-block-editor'),
                'keyword' => ['blockquote', 'Block Quotation', 'Citation', 'Pull Quotes','block quote'],
			],
			'tp-breadcrumbs' => [
				'label' => esc_html__('Breadcrumbs','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-breadcrumb-bar/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/breadcrumb/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'free',
				'block_cate' => esc_html__('Essential', 'the-plus-addons-for-block-editor'),
				'keyword' => ['breadcrumbs bar', 'breadcrumb trail', 'navigation', 'site navigation', 'breadcrumb navigation']
			],
			'tp-button' => [
				'label' => esc_html__('Advanced Button','the-plus-addons-for-block-editor'),				
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-advanced-button/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/advance-button/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'free',
				'block_cate' => esc_html__('Advanced', 'the-plus-addons-for-block-editor'),
				'keyword' => ['Button', 'CTA', 'link', 'creative button', 'Call to action', 'Marketing Button']
			],
			'tp-button-core' => [
				'label' => esc_html__('Button','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-button/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/wordpress-button/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'free',
				'block_cate' => esc_html__('Essential', 'the-plus-addons-for-block-editor'),
				'keyword' => ['core button','Button', 'CTA', 'link', 'creative button', 'Call to action', 'Marketing Button']
			],
			'tp-anything-carousel' => [
				'label' => esc_html__('Carousel Anything','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-advanced-carousel-sliders/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/wordpress-advanced-carousel-sliders/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'pro',
				'block_cate' => esc_html__('Essential', 'the-plus-addons-for-block-editor'),
				'keyword' => ['carousel anything', 'slider', 'slideshow'],
			],
			'tp-carousel-remote' => [
				'label' => esc_html__('Carousel Remote','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-remote-sync/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/wordpress-remote-sync/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'pro',
				'block_cate' => esc_html__('Advanced', 'the-plus-addons-for-block-editor'),
                'keyword' => ['carousel remote', 'slider controller','next prev','dots','Remote Sync'],
			],
			'tp-circle-menu' => [
				'label' => esc_html__('Circle Menu', 'the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-circle-menu/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/circle-menu/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'pro',
				'block_cate' => esc_html__('Creative', 'the-plus-addons-for-block-editor'),
				'keyword' => ['circle menu', 'compact menu', 'mobile menu']
			],
			'tp-code-highlighter' => [
				'label' => esc_html__('Code Highlighter', 'the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-source-code-syntax-highlighter/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => '',
				'videoUrl' => '',
				'tag' => 'free',
				'block_cate' => esc_html__('Creative', 'the-plus-addons-for-block-editor'),
				'keyword' => ['prism', 'Source code beautifier', 'code Highlighter',  'syntax Highlighter', 'Custom Code', 'CSS', 'JS', 'PHP', 'HTML', 'React']
			],
			'tp-countdown' => [
				'label' => esc_html__('Countdown','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-countdown-timer/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/countdown/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'freemium',
				'block_cate' => esc_html__('Advanced', 'the-plus-addons-for-block-editor'),
				'keyword' => ['Countdown', 'countdown timer', 'timer', 'Scarcity Countdown', 'Urgency Countdown', 'Event countdown', 'Sale Countdown', 'chronometer', 'stopwatch']
			],
			'tp-container' => [
				'label' => esc_html__('Container','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-container/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/container/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'free',
				'block_cate' => esc_html__('Essential', 'the-plus-addons-for-block-editor'),
				'keyword' => ['container','flex-wrap','flex-based','full-width']
			],
			'tp-coupon-code' => [
				'label' => esc_html__('Coupon Code', 'the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-coupon-code/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/coupon-code/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'pro',
				'block_cate' => esc_html__('Creative', 'the-plus-addons-for-block-editor'),
				'keyword' => ['Coupon Code', 'Promo Code', 'Offers' , 'Discounts', 'Sales', 'Copy Coupon Code']
			],
			'tp-creative-image' => [
				'label' => esc_html__('Advanced Image','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-advanced-image/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/advanced-image/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '#video',
				'tag' => 'freemium',
				'block_cate' => esc_html__('Advanced', 'the-plus-addons-for-block-editor'),
				'keyword' => ['Creative image', 'Image', 'Animated Image', 'ScrollReveal', 'scrolling image', 'decorative image', 'image effect', 'Photo', 'Visual']
			],
			'tp-cta-banner' => [
				'label' => esc_html__('CTA Banner','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-cta-banner/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/cta-banner/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'pro',
				'block_cate' => esc_html__('Creative', 'the-plus-addons-for-block-editor'),
				'keyword' => ['advertisement', 'banner', 'advertisement banner', 'ad manager', 'announcement', 'announcement banner']
			],
			'tp-data-table' => [
				'label' => esc_html__('Data Table','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-data-table/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/wordpress-data-table/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'freemium',
				'block_cate' => esc_html__('Essential', 'the-plus-addons-for-block-editor'),
				'keyword' => ['Data table', 'datatable', 'grid', 'csv table', 'table', 'tabular layout', 'Table Showcase']
			],
			'tp-dark-mode' => [
				'label' => esc_html__('Dark Mode','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-dark-mode-switcher/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/dark-mode/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'free',
				'block_cate' => esc_html__('Creative', 'the-plus-addons-for-block-editor'),
				'keyword' => ['dark', 'light', 'darkmode', 'dual']
			],
			'tp-design-tool' => [
				'label' => esc_html__('Design Tool','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/extras/wordpress-design-grid-tool/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => '',
				'videoUrl' => '',
				'tag' => 'pro',
				'block_cate' => esc_html__('Creative', 'the-plus-addons-for-block-editor'),
				'keyword' => ['design','tool']
			],
			'tp-draw-svg' => [
				'label' => esc_html__('Draw SVG','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-draw-animated-svg-icon/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/draw-svg/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'free',
				'block_cate' => esc_html__('Advanced', 'the-plus-addons-for-block-editor'),
				'keyword' => ['Draw SVG', 'Draw Icon', 'illustration', 'animated svg', 'animated icons', 'Lottie animations', 'Lottie files', 'effects', 'image effect']
			],
			'tp-dynamic-device' => [
				'label' => esc_html__('Dynamic Device','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-device-mockups/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/wordpress-device-mockups/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'pro',
				'block_cate' => esc_html__('Advanced', 'the-plus-addons-for-block-editor'),
				'keyword' => ['dynamic device', 'website mockups', 'portfolio', 'desktop view', 'tablet view', 'mobile view']
			],
			'tp-empty-space' => [
				'label' => esc_html__('Spacer','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-spacer/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/spacer/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'free',
				'block_cate' => esc_html__('Essential', 'the-plus-addons-for-block-editor'),
				'keyword' => ['Spacer', 'Divider', 'Spacing','empty space']
			],
			'tp-external-form-styler' => [
				'label' => esc_html__('External Form Styler','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-contact-form-stylers/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/wordpress-contact-form-stylers/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '#',
				'tag' => 'free',
				'block_cate' => esc_html__('Essential', 'the-plus-addons-for-block-editor'),
				'keyword' => ['form', 'contect form', 'everest', 'gravity', 'wpform','Contact Form 7', 'contact form', 'form', 'feedback', 'subscribe', 'newsletter', 'contact us', 'custom form', 'popup form', 'cf7']
			],
			'tp-expand' => [
				'label' => esc_html__('Expand','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-unfold-read-more-button/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/wordpress-unfold-read-more-button/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'pro',
				'block_cate' => esc_html__('Creative', 'the-plus-addons-for-block-editor'),
				'keyword' => ['Expand', 'read more', 'show hide content', 'Expand tabs', 'show more', 'toggle', 'Excerpt']
			],
			'tp-flipbox' => [
				'label' => esc_html__('Flipbox','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-flipbox/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/flipbox/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'freemium',
				'block_cate' => esc_html__('Creative', 'the-plus-addons-for-block-editor'),
				'keyword' => ['flipbox', 'flip box', 'flip', 'flip image', 'flip card', 'action box', 'flipbox 3D', 'card'],
			],
            'tp-form-block' => [
                'label' => esc_html__('Form', 'the-plus-addons-for-block-editor'),
               'demoUrl' => 'https://nexterwp.com/nexter-blocks/builder/wordpress-form-builder/',
                'docUrl' => '',
                'videoUrl' => '',
                'tag' => 'freemium',
                'block_cate' => esc_html__('Builder', 'the-plus-addons-for-block-editor'),
                'keyword' => ['forms' , 'contact Form' , 'marketing']
            ],
			'tp-google-map' => [
				'label' => esc_html__('Google Map','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-google-maps/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/wordpress-google-maps/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'freemium',
				'block_cate' => esc_html__('Essential', 'the-plus-addons-for-block-editor'),
				'keyword' => ['Map', 'Maps', 'Google Maps', 'g maps', 'location map', 'map iframe', 'embed']
			],
			'tp-heading-animation' => [
				'label' => esc_html__('Heading Animation','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-heading-animation/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/heading-animation/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'pro',
				'block_cate' => esc_html__('Creative', 'the-plus-addons-for-block-editor'),
				'keyword' => ['Heading Animation', 'Animated Heading', 'Animation Text', 'Animated Text', 'Text Animation']
			],
			'tp-heading' => [
				'label' => esc_html__('Heading','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-heading/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/wordpress-heading/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'free',
				'block_cate' => esc_html__('Essential', 'the-plus-addons-for-block-editor'),
				'keyword' => ['Heading', 'Title', 'Text', 'Heading title', 'Headline']
			],
			'tp-heading-title' => [
				'label' => esc_html__('Advanced Heading','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-title-block/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/advance-heading/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'free',
				'block_cate' => esc_html__('Advanced', 'the-plus-addons-for-block-editor'),
				'keyword' => ['Heading', 'Title', 'Text', 'Heading title', 'Headline']
			],
			'tp-hotspot' => [
				'label' => esc_html__('Hotspot','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-hotspot-pinpoint-image/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/hotspot/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'pro',
				'block_cate' => esc_html__('Creative', 'the-plus-addons-for-block-editor'),
				'keyword' => [ 'Image hotspot', 'maps', 'pin' ],
			],
			'tp-hovercard' => [
				'label' => esc_html__('Hover Card','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/hover-card-animations-wordpress/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/hover-card/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'free',
				'block_cate' => esc_html__('Advanced', 'the-plus-addons-for-block-editor'),
				'keyword' => ['Hover Card', 'Card', 'Business Card'],
			],
			'tp-icon-box' => [
				'label' => esc_html__('Icon','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-icon/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/wordpress-icon/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'free',
				'block_cate' => esc_html__('Essential', 'the-plus-addons-for-block-editor'),
				'keyword' => ['iconbox', 'icon box', 'fontawesome']
			],
			'tp-image' => [
				'label' => esc_html__('Image','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-image/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/wordpress-image/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'free',
				'block_cate' => esc_html__('Essential', 'the-plus-addons-for-block-editor'),
				'keyword' => ['image', 'media']
			],
			'tp-infobox' => [
				'label' => esc_html__('Infobox','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-infobox/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/infobox/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'free',
				'block_cate' => esc_html__('Essential', 'the-plus-addons-for-block-editor'),
				'keyword' => ['Infobox', 'Information', 'Info box', 'card', 'info']
			],
			'tp-interactive-circle-info' => [
				'label' => esc_html__('Interactive Circle Info','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-interactive-circle-infographic/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/interactive-circle-info/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'free',
				'block_cate' => esc_html__('Tabbed', 'the-plus-addons-for-block-editor'),
				'keyword' => ['interactive circle', 'interactive', 'circle', 'info']
			],
			'tp-login-register' => [
				'label' => __('Login & Signup', 'the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-login-form/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/wordpress-login-and-registration-form/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'pro',
				'block_cate' => esc_html__('Advanced', 'the-plus-addons-for-block-editor'),
				'keyword' => ['login', 'register', 'Sign up','forgot password']
			],
			'tp-lottiefiles' => [
				'label' => esc_html__('LottieFiles Animation','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-lottiefiles-animations/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/lottie-animations-nexter-blocks/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'pro',
				'block_cate' => esc_html__('Creative', 'the-plus-addons-for-block-editor'),
				'keyword' => ['animation', 'lottie', 'files']
			],
			'tp-mailchimp' => [
				'label' => esc_html__('Mailchimp','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-mailchimp-form/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => '',
				'videoUrl' => '',
				'tag' => 'pro',
				'block_cate' => esc_html__('Creative', 'the-plus-addons-for-block-editor'),
				'keyword' => ['Mailchimp', 'Mailchimp addon', 'subscribe form']
			],
			'tp-media-listing' => [
				'label' => esc_html__('Media Listing','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/listing/wordpress-image-gallery/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => '',
				'videoUrl' => '',
				'tag' => 'pro',
				'block_cate' => esc_html__('Listing', 'the-plus-addons-for-block-editor'),
				'keyword' => ['Video Gallery', 'Image Gallery', 'Video Carousel', 'Image Carousel', 'Video Listing', 'Image Listing', 'Youtube', 'Vimeo','media gallery']
			],
			'tp-messagebox' => [
				'label' => esc_html__('Message box','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-message-box/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/message-box/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'free',
				'block_cate' => esc_html__('Creative', 'the-plus-addons-for-block-editor'),
				'keyword' => ['Message box', 'Notification box', 'alert box']
			],
			'tp-mobile-menu' => [
				'label' => esc_html__('Mobile Menu','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/builder/wordpress-mobile-menu/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => '',
				'videoUrl' => '',
				'tag' => 'pro',
				'block_cate' => esc_html__('Builder', 'the-plus-addons-for-block-editor'),
				'keyword' => ['mobile menu', 'menu','toggle menu']
			],
			'tp-mouse-cursor' => [
				'label' => esc_html__('Mouse Cursor','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-custom-cursors/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/mouse-cursor/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'pro',
				'block_cate' => esc_html__('Creative', 'the-plus-addons-for-block-editor'),
				'keyword' => ['mouse', 'cursor', 'animated cursor', 'mouse cursor', 'pointer']
			],
			'tp-navigation-builder' => [
				'label' => esc_html__('Navigation Menu','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/builder/wordpress-navigation-menu/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => '',
				'videoUrl' => '',
				'tag' => 'freemium',
				'block_cate' => esc_html__('Builder', 'the-plus-addons-for-block-editor'),
				'keyword' => ['navigation menu', 'mega menu', 'header builder', 'sticky menu', 'navigation bar', 'header menu', 'menu', 'navigation builder','vertical menu', 'swiper menu']
			],
			'tp-number-counter' => [
				'label' => esc_html__('Number Counter','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-number-counter/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/number-counter/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'free',
				'block_cate' => esc_html__('Essential', 'the-plus-addons-for-block-editor'),
				'keyword' => ['number counter', 'counter', 'animated counter', 'Odometer']
			],
			'tp-popup-builder' => [
				'label' => esc_html__('Popup Builder','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/builder/wordpress-popup-builder/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => '',
				'videoUrl' => '',
				'tag' => 'pro',
				'block_cate' => esc_html__('Builder', 'the-plus-addons-for-block-editor'),
				'keyword' => ['popup', 'pop up', 'alertbox', 'offcanvas', 'modal box', 'modal popup']
			],
			'tp-post-author' => [
				'label' => esc_html__('Post Author', 'the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/builder/wordpress-post-author-box/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => '',
				'videoUrl' => '',
				'tag' => 'free',
				'block_cate' => esc_html__('Builder', 'the-plus-addons-for-block-editor'),
				'keyword' => ['post author', 'author','user info']
			],
			'tp-post-comment' => [
				'label' => esc_html__('Post Comments', 'the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/builder/wordpress-post-comment-form/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => '',
				'videoUrl' => '',
				'tag' => 'free',
				'block_cate' => esc_html__('Builder', 'the-plus-addons-for-block-editor'),
				'keyword' => ['post comments', 'comments','comments area']
			],
			'tp-post-content' => [
				'label' => esc_html__('Post Content', 'the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/builder/wordpress-post-content/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => '',
				'videoUrl' => '',
				'tag' => 'free',
				'block_cate' => esc_html__('Builder', 'the-plus-addons-for-block-editor'),
				'keyword' => ['content', 'post content', 'post excerpt', 'archive description']
			],
			'tp-post-image' => [
				'label' => esc_html__('Post Image', 'the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/builder/wordpress-post-featured-image/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => '',
				'videoUrl' => '',
				'tag' => 'free',
				'block_cate' => esc_html__('Builder', 'the-plus-addons-for-block-editor'),
				'keyword' => ['post featured image', 'post image', 'featured image']
			],
			'tp-post-listing' => [
				'label' => esc_html__('Post Listing', 'the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/listing/wordpress-post-listing/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => '',
				'videoUrl' => '',
				'tag' => 'freemium',
				'block_cate' => esc_html__('Listing', 'the-plus-addons-for-block-editor'),
				'keyword' => ['blog listing', 'article listing','custom post listing','blog view','post listing','masonry','carousel','content view','blog item listing','grid', 'post listing', 'related posts', 'archive posts', 'post list', 'post grid', 'post masonry','post carousel', 'post slider']
			],
			'tp-post-meta' => [
				'label' => esc_html__('Post Meta Info', 'the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/builder/wordpress-post-meta-info/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => '',
				'videoUrl' => '',
				'tag' => 'free',
				'block_cate' => esc_html__('Builder', 'the-plus-addons-for-block-editor'),
				'keyword' => ['post category', 'post tags', 'post meta info', 'meta info', 'post date', 'post comment', 'post author']
			],
			'tp-post-navigation' => [
				'label' => esc_html__('Post Navigation', 'the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/builder/wordpress-post-navigation/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => '',
				'videoUrl' => '',
				'tag' => 'pro',
				'block_cate' => esc_html__('Builder', 'the-plus-addons-for-block-editor'),
				'keyword' => ['previous next', 'post previous next', 'post navigation']
			],
			'tp-post-title' => [
				'label' => esc_html__('Post Title', 'the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/builder/wordpress-post-title/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => '',
				'videoUrl' => '',
				'tag' => 'free',
				'block_cate' => esc_html__('Builder', 'the-plus-addons-for-block-editor'),
				'keyword' => ['post title', 'page title', 'archive title']
			],
			'tp-pricing-list' => [
				'label' => esc_html__('Pricing List','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-pricing-list/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/pricing-list/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'free',
				'block_cate' => esc_html__('Essential', 'the-plus-addons-for-block-editor'),
				'keyword' => ['Pricing list', 'Item price', 'price card', 'Price Guide', 'price box']
			],
			'tp-pricing-table' => [
				'label' => esc_html__('Pricing Table','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-pricing-table/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/pricing-table/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'freemium',
				'block_cate' => esc_html__('Essential', 'the-plus-addons-for-block-editor'),
				'keyword' => ['Pricing table', 'pricing list', 'price table', 'plans table', 'pricing plans', 'dynamic pricing', 'price comparison', 'Plans & Pricing Table', 'Price Chart']
			],
			'tp-preloader' => [
				'label' => esc_html__('Pre Loader','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-preloader-animation-and-page-transitions/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/wordpress-preloader-animation-and-page-transitions/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'pro',
				'block_cate' => esc_html__('Creative', 'the-plus-addons-for-block-editor'),
				'keyword' => [ 'pre loader', 'loader', 'loading','Preloader' ],
			],
			'tp-pro-paragraph' => [
				'label' => esc_html__('Paragraph','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-pro-paragraph/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/wordpress-pro-paragraph/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '#video',
				'tag' => 'free',
				'block_cate' => esc_html__('Essential', 'the-plus-addons-for-block-editor'),
				'keyword' => ['Paragraph', 'wysiwyg', 'editor', 'editor block', 'textarea', 'text area', 'text editor'],
			],
			'tp-process-steps' => [
				'label' => esc_html__('Process Steps','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-process-steps/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/process-steps/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'pro',
				'block_cate' => esc_html__('Creative', 'the-plus-addons-for-block-editor'),
				'keyword' => ['Process steps', 'post timeline', 'step process', 'steps form', 'Steppers', 'timeline', 'Progress Tracker']
			],
			'tp-product-listing' => [
				'label' => esc_html__('Product Listing','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/listing/wordpress-product-listing/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => '',
				'videoUrl' => '',
				'tag' => 'pro',
				'block_cate' => esc_html__('Listing', 'the-plus-addons-for-block-editor'),
				'keyword' => ['Product', 'Woocommerce']
			],
			'tp-progress-bar' => [
				'label' => esc_html__('Progress Bar','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-progress-bar/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/progress-bar/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'free',
				'block_cate' => esc_html__('Essential', 'the-plus-addons-for-block-editor'),
				'keyword' => ['Progress bar', 'progressbar', 'status bar', 'progress indicator', 'scroll progress', 'process progress bar', 'Progress Tracker']
			],
			'tp-progress-tracker' => [
				'label' => esc_html__('Progress Tracker','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-reading-scroll-progress-bar/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/progress-tracker/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'free',
				'block_cate' => esc_html__('Creative', 'the-plus-addons-for-block-editor'),
				'keyword' => [ 'Progress bar', 'progressbar', 'status bar', 'progress indicator', 'scroll progress', 'process progress bar', 'Progress Tracker', 'Page scroll tracker','Reading progress indicator','Reading progress bar','Reading position tracker', 'Scroll depth indicator', 'Scroll tracking', 'Scroll Progress Visualizer' ]
			],
             'tp-repeater-block' => [
				'label' => esc_html__('Repeater', 'tpgb'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/listing/wordpress-repeater/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/docs/display-dynamic-repeater-field-data-in-wordpress/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'pro',
				'block_cate' => esc_html__('Listing', 'tpgb'),
				'keyword' => ['repeater']
			],
			'tp-row' => [
				'label' => esc_html__('Row','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-container/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => '',
				'videoUrl' => '',
				'tag' => 'free',
				'block_cate' => esc_html__('Essential', 'the-plus-addons-for-block-editor'),
				'keyword' => ['Row', 'layout'],
			],
			'tp-site-logo' => [
				'label' => esc_html__('Site Logo','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/builder/wordpress-site-logo/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => '',
				'videoUrl' => '#video',
				'tag' => 'free',
				'block_cate' => esc_html__('Builder', 'the-plus-addons-for-block-editor'),
				'keyword' => ['site logo', 'logo','dual logo'],
			],
			'tp-stylist-list' => [
				'label' => esc_html__('Stylish List','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-stylish-list/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/stylist-list/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '#video',
				'tag' => 'free',
				'block_cate' => esc_html__('Essential', 'the-plus-addons-for-block-editor'),
				'keyword' => ['Stylish list', 'listing', 'item listing'],
			],
			'tp-scroll-navigation' => [
				'label' => esc_html__('Scroll Navigation','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-one-page-scroll-navigation/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/scroll-navigation/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '#video',
				'tag' => 'pro',
				'block_cate' => esc_html__('Creative', 'the-plus-addons-for-block-editor'),
				'keyword' => ['Scroll navigation', 'slide show', 'slideshow', 'vertical slider'],
			],
			'tp-scroll-sequence' => [
				'label' => esc_html__('Scroll Sequence','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-image-scroll-sequence/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/scroll-sequence/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '#video',
				'tag' => 'pro',
				'block_cate' => esc_html__('Creative', 'the-plus-addons-for-block-editor'),
				'keyword' => ['Cinematic Scroll Image Animation', 'Video Scroll Sequence', 'Image Scroll Sequence'],
			],
			'tp-search-bar' => [
				'label' => esc_html__('Search Bar', 'the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/builder/wordpress-ajax-search-bar/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/search-bar/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'free',
				'block_cate' => esc_html__('Essential', 'the-plus-addons-for-block-editor'),
				'keyword' => ['search', 'post search','WordPress Search Bar', 'Find', 'Search Tool', 'SearchWP'],
			],
			'tp-social-icons' => [
				'label' => esc_html__('Social Icon','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-social-icons/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/social-icon/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'free',
				'block_cate' => esc_html__('Essential', 'the-plus-addons-for-block-editor'),
				'keyword' => ['Social Icon', 'Icon', 'link']
			],
			'tp-social-embed' => [
				'label' => esc_html__('Social Embed','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-social-embed/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => '',
				'videoUrl' => '',
				'tag' => 'free',
				'block_cate' => esc_html__('Social', 'the-plus-addons-for-block-editor'),
				'keyword' => ['iframe', 'facebook feed', 'facebook comments', 'facebook like', 'facebook share', 'facebook page' ]
			],
			'tp-social-feed' => [
				'label' => esc_html__('Social Feed','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-social-feed/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => '',
				'videoUrl' => '',
				'tag' => 'freemium',
				'block_cate' => esc_html__('Social', 'the-plus-addons-for-block-editor'),
				'keyword' => ['feed', 'facebook', 'google', 'youtube', 'social', 'posts', 'instagram','vimeo']
			],
			'tp-social-sharing' => [
				'label' => esc_html__('Social Sharing','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-social-sharing-icons/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/social-sharing/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '#',
				'tag' => 'pro',
				'block_cate' => esc_html__('Advanced', 'the-plus-addons-for-block-editor'),
				'keyword' => ['Social Sharing', 'Social Media Sharing']
			],
			'tp-social-reviews' => [
				'label' => esc_html__('Social Reviews','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-social-reviews/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => '',
				'videoUrl' => '',
				'tag' => 'freemium',
				'block_cate' => esc_html__('Social', 'the-plus-addons-for-block-editor'),
				'keyword' => ['social', 'reviews', 'rating', 'stars', 'badges']
			],
			'tp-spline-3d-viewer' => [
				'label' => esc_html__('Spline 3D Viewer','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-spline-3d-viewer/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/spline-3d-viewer/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'pro',
				'block_cate' => esc_html__('Creative', 'the-plus-addons-for-block-editor'),
				'keyword' => ['canvas animation', 'spline', '3d', 'Spline 3D viewer', 'Spline 3D model embed', 'Spline 3D interactive']
			],
			'tp-smooth-scroll' => [
				'label' => esc_html__('Smooth Scroll','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-smooth-scroll/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/smooth-scroll/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '#',
				'tag' => 'free',
				'block_cate' => esc_html__('Creative', 'the-plus-addons-for-block-editor'),
			],
			'tp-switcher' => [
				'label' => esc_html__('Switcher','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-content-switcher/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/wordpress-content-switcher/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '#video',
				'tag' => 'freemium',
				'block_cate' => esc_html__('Tabbed', 'the-plus-addons-for-block-editor'),
				'keyword' => ['Switcher', 'on/off', 'switch control', 'toggle', 'true/false', 'toggle switch', 'state', 'binary']
			],
			'tp-table-content' => [
				'label' => esc_html__('Table of Contents','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-table-of-contents/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/table-of-content/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'pro',
				'block_cate' => esc_html__('Essential', 'the-plus-addons-for-block-editor'),
				'keyword' => [ 'Table of Contents', 'Contents', 'toc', 'index', 'listing', 'appendix' ]
			],
			'tp-tabs-tours' => [
				'label' => esc_html__('Tabs Tours', 'the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-tab-content/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/wordpress-tab-content/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '#video',
				'tag' => 'freemium',
				'block_cate' => esc_html__('Tabbed', 'the-plus-addons-for-block-editor'),
				'keyword' => ['Tabs', 'Tours', 'tab content', 'pills', 'toggle']
			],
			'tp-dynamic-category' => [
				'label' => esc_html__('Taxonomy Listing','the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/builder/wordpress-taxonomy-listing/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => '',
				'videoUrl' => '',
				'tag' => 'pro',
				'block_cate' => esc_html__('Listing', 'the-plus-addons-for-block-editor'),
				'keyword' => ['Category', 'Tags', 'Taxonomy', 'WP Term' , 'Category Grid' , 'product category' , 'Post' , 'CPT' , 'WooCommerce', 'Product Tags']
			],
			'tp-team-listing' => [
				'label' => esc_html__('Team Member', 'the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/listing/wordpress-team-members/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => '',
				'videoUrl' => '',
				'tag' => 'freemium',
				'block_cate' => esc_html__('Listing', 'the-plus-addons-for-block-editor'),
				'keyword' => ['Team Member Gallery', 'Team Gallery', 'Team Member Carousel']
			],
			'tp-testimonials' => [
				'label' => esc_html__('Testimonials', 'the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/listing/wordpress-testimonial-reviews/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => '',
				'videoUrl' => '',
				'tag' => 'freemium',
				'block_cate' => esc_html__('Listing', 'the-plus-addons-for-block-editor'),
				'keyword' => ['Testimonials', 'testimonial', 'slider', 'client reviews', 'ratings']
			],
			'tp-timeline' => [
				'label' => esc_html__('Timeline', 'the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-timeline/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/timeline/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'pro',
				'block_cate' => esc_html__('Creative', 'the-plus-addons-for-block-editor'),
                'keyword' => ['timeline','Schedule','Sequence','History','Events','Timeframe','Historical data','Time Line']
			],
			'tp-video' => [
				'label' => esc_html__('Video', 'the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/blocks/wordpress-video-player/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-blocks/video/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'free',
				'block_cate' => esc_html__('Essential', 'the-plus-addons-for-block-editor'),
				'keyword' => ['Video', 'youtube video', 'vimeo video', 'video player', 'mp4 player', 'web player', 'youtube content', 'Youtube embed', 'youtube iframe']
			],
		];
	
		$this->block_extra = [
            'tp-global-block-style' => [
				'label' => esc_html__('Global Block Style', 'the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/extras/wordpress-global-block-style/',
				'videoUrl' => '',
				'tag' => 'free',
				'block_cate' => esc_html__('Extras', 'the-plus-addons-for-block-editor'),
			],
			'tp-advanced-border-radius' => [
				'label' => esc_html__('Advanced Border Radius', 'the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/extras/wordpress-advanced-border-radius/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'pro',
				'block_cate' => esc_html__('Extras', 'the-plus-addons-for-block-editor'),
			],
			'tp-content-hover-effect' => [
				'label' => esc_html__('Content Hover Effect', 'the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/extras/mouse-hover-animation-for-wordpress/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'pro',
				'block_cate' => esc_html__('Extras', 'the-plus-addons-for-block-editor'),
			],
			'tp-continuous-animation' => [
				'label' => esc_html__('Continuous Animation', 'the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/extras/wordpress-floating-effect/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'pro',
				'block_cate' => esc_html__('Extras', 'the-plus-addons-for-block-editor'),
			],
			'tp-display-rules' => [
				'label' => esc_html__('Display Rules', 'the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/extras/wordpress-display-conditional-rules/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-extras/wordpress-display-conditional-rules/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'freemium',
				'block_cate' => esc_html__('Extras', 'the-plus-addons-for-block-editor'),
			],
			'tp-equal-height' => [
				'label' => esc_html__('Equal Column Height', 'the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/extras/wordpress-same-equal-height/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'docUrl' => 'https://nexterwp.com/help/nexter-extras/wordpress-same-equal-height/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'pro',
				'block_cate' => esc_html__('Extras', 'the-plus-addons-for-block-editor'),
			],
			'tp-global-tooltip' => [
				'label' => esc_html__('Global Tooltip', 'the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/extras/wordpress-global-tooltip/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'pro',
				'block_cate' => esc_html__('Extras', 'the-plus-addons-for-block-editor'),
			],
			'tp-magic-scroll' => [
				'label' => esc_html__('Magic Scroll', 'the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/extras/magic-scroll-effect-for-wordpress/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'pro',
				'block_cate' => esc_html__('Extras', 'the-plus-addons-for-block-editor'),
			],
			'tp-mouse-parallax' => [
				'label' => esc_html__('Mouse Parallax', 'the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/extras/mouse-hover-animation-for-wordpress/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'pro',
				'block_cate' => esc_html__('Extras', 'the-plus-addons-for-block-editor'),
			],
			'tp-scoll-animation' => [
				'label' => esc_html__('On Scroll Animation', 'the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/extras/wordpress-on-scroll-content-animations/?utm_source=wpbackend&utm_medium=blocks&utm_campaign=nextersettings',
				'videoUrl' => '',
				'tag' => 'freemium',
				'block_cate' => esc_html__('Extras', 'the-plus-addons-for-block-editor'),
			],
			'tp-3d-tilt' => [
				'label' => esc_html__('3D Tilt', 'the-plus-addons-for-block-editor'),
				'demoUrl' => 'https://nexterwp.com/nexter-blocks/extras/mouse-hover-animation-for-wordpress/#tilt-3d',
				'videoUrl' => '',
				'tag' => 'pro',
				'block_cate' => esc_html__('Extras', 'the-plus-addons-for-block-editor'),
			],
		];
	}
	
	/**
     * Theplus Gutenberg Display Page
     * @since  1.0.0
     */
    public function admin_page_display() {
		echo '<div id="tpgb-dash"></div>';
        do_action('nxt_new_update_notice');
	}
	
	public function get_post_statuses_sql(){
		$statuses = array_map(
			function( $item ){
				return esc_sql( $item );
			},
			array( 'publish', 'private', 'pending', 'future', 'draft' )
		);
		return "'" . implode( "', '", $statuses ) . "'";
	}

	/*
	 * Scan Unused Blocks
	 * @since 1.3.1
	 */
	public function tpgb_is_block_used_not_fun(){
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX && 
			isset( $_POST['nonce'] ) && 
			!empty( $_POST['nonce'] ) && 
			wp_verify_nonce( sanitize_text_field( wp_unslash ($_POST['nonce']) ), 'tpgb-dash-ajax-nonce' ) 
		) {
			global $wpdb;
			$block_scan =[];
			
			if(isset($_POST['default_block']) && $_POST['default_block']=='false'){
				$this->block_listout();
				if(!empty($this->block_lists)){
					foreach($this->block_lists as $key => $block){
						$sql_posts = $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE post_status IN ( {$this->get_post_statuses_sql()} ) AND post_content LIKE %s LIMIT 1", '%<!-- wp:tpgb/' . $wpdb->esc_like($key) . '%' );
                        $found_in_posts = $wpdb->get_var($sql_posts);
						
						$block_scan[$key]= $found_in_posts ? 1 : 0;
						if( ! $found_in_posts ){
							$sql_widgets = $wpdb->prepare( "SELECT option_id FROM {$wpdb->options} WHERE option_name = %s AND option_value LIKE %s LIMIT 1", 'widget_block','%<!-- wp:tpgb/' . $wpdb->esc_like($key) . '%');
                            $found_in_widgets = $wpdb->get_var($sql_widgets);
							
							$block_scan[$key]= $found_in_widgets ? 1 : 0;
						}
					}
				}
			}else if(isset($_POST['default_block']) && $_POST['default_block']!='' && $_POST['default_block']==true){
				$block_types = WP_Block_Type_Registry::get_instance()->get_all_registered();
				if( !empty($block_types) ){
					foreach($block_types as $key => $block){
						if(str_contains($key, 'core/')){
							if( $key !='core/missing' && $key !='core/block'&& $key !='core/widget-group' && !empty($block->title) ){
								$core_key = str_replace( 'core/', '', $key );
								$core_key = esc_sql( $core_key );
								$pass_key = str_replace( 'core/', 'core-', $key );
								$sql_posts = $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE post_status IN ( {$this->get_post_statuses_sql()} ) AND post_content LIKE %s LIMIT 1", '%<!-- wp:' . $wpdb->esc_like($core_key) . '%');
                                $found_in_posts = $wpdb->get_var($sql_posts);
								
								$block_scan[$pass_key]= $found_in_posts ? 1 : 0;
								if( ! $found_in_posts ){
									$sql_widgets = $wpdb->prepare( "SELECT option_id FROM {$wpdb->options} WHERE option_name = %s AND option_value LIKE %s LIMIT 1", 'widget_block', '%<!-- wp:' . $wpdb->esc_like($core_key) .'%' );
                                    $found_in_widgets = $wpdb->get_var($sql_widgets);
									$block_scan[$pass_key]= $found_in_widgets ? 1 : 0;
								}
							}
						}
					}
				}
			}
			wp_send_json($block_scan);
			exit;
		}
		exit;
	}

	/*
	 * Unused Disable Blocks
	 * @since 1.4.4
	 */
	public function tpgb_disable_unsed_block_fun(){
		if( defined('DOING_AJAX') && DOING_AJAX && isset( $_POST['nonce'] ) && !empty($_POST['nonce']) && wp_verify_nonce( sanitize_text_field( wp_unslash ($_POST['nonce'] ) ), 'tpgb-dash-ajax-nonce') ){
			
			if(!isset($_POST['blocks']) || empty($_POST['blocks'])){
				echo 0;
				exit;
			}
			$default_block = (isset($_POST['default_block']) && $_POST['default_block']!='') ? sanitize_text_field( wp_unslash ($_POST['default_block'] ) ) : '';
			if(isset($default_block) && $default_block!='' && $default_block=='false'){
				$blocks = map_deep(wp_unslash(json_decode(stripslashes($_POST['blocks']), true)), 'sanitize_text_field');
				
				$action_page = 'tpgb_normal_blocks_opts';
				$all_block = get_option($action_page);
				$update_block = [];
				if(is_array($blocks)){
					foreach($blocks as $key => $val){
						if($val==1){
							$update_block[] = $key;
						}
					}
					$all_block['enable_normal_blocks'] = map_deep( wp_unslash( $update_block ), 'sanitize_text_field');
					update_option( $action_page, $all_block );
					Tpgb_Library()->remove_backend_dir_files();
					echo 1;
					exit;
				}
			}
			
		}
		echo 0;
		exit;
	}

	/*
	 * Filter to Enable All Block For WDesignkit
	 * @since 4.0.14
	 */

	 public function tpgb_blocks_enable_all_filter() {
		$this->block_listout();
		$default_value = array(
			'enable_normal_blocks' => array(),
			'tp_extra_option' => array()
		);
	
		$free_enable = !defined('TPGBP_VERSION');
	
		$process_blocks = function ($blocks, $key) use (&$default_value, $free_enable) {
			foreach ($blocks as $block_key => $block) {
				if (class_exists('Tpgb_Library') && method_exists('Tpgb_Library', 'remove_backend_dir_files')) {
					Tpgb_Library()->remove_backend_dir_files();
				}

				if (!$free_enable || ( isset($block['tag']) && $block['tag'] === 'free' )) {
					$default_value[$key][] = $block_key;
				}
			}
		};
	
		if (!empty($this->block_lists)) {
			$process_blocks($this->block_lists, 'enable_normal_blocks');
		}
		if (!empty($this->block_extra)) {
			$process_blocks($this->block_extra, 'tp_extra_option');
		}

		$option_exists = get_option('tpgb_normal_blocks_opts');
		return $option_exists === false ? add_option('tpgb_normal_blocks_opts', $default_value) : update_option('tpgb_normal_blocks_opts', $default_value);
	}

	/*
	 * Unused Disable Blocks Filter Function For WDesignkit
	 * @since 4.0.1
	 */

	public function tpgb_disable_unsed_block_filter_fun() {
		global $wpdb;
	
		$this->block_listout();
		$block_scan = [];
	
		if (!empty($this->block_lists)) {
			foreach ($this->block_lists as $key => $block) {
				$post_statuses = $this->get_post_statuses_sql();
				$like_key = '%<!-- wp:tpgb/' . $wpdb->esc_like($key) . '%';
	
				$sql_posts = $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE post_status IN ($post_statuses) AND post_content LIKE %s LIMIT 1", $like_key );
				$found_in_posts = $wpdb->get_var($sql_posts);
	
				$block_scan[$key] = $found_in_posts ? 1 : 0;
	
				if (!$found_in_posts) {
					$sql_widgets = $wpdb->prepare( "SELECT option_id FROM {$wpdb->options} WHERE option_name = %s AND option_value LIKE %s LIMIT 1", 'widget_block', $like_key );
					$found_in_widgets = $wpdb->get_var($sql_widgets);
	
					$block_scan[$key] = $found_in_widgets ? 1 : 0;
				}
			}
	
			$action_page = 'tpgb_normal_blocks_opts';
			$all_block = get_option($action_page);
	
			if (is_array($block_scan)) {
				foreach ($block_scan as $key => $val) {
					if ($val == 1) {
						$update_block[] = $key;
					}
				}
				$all_block['enable_normal_blocks'] = map_deep(wp_unslash($update_block), 'sanitize_text_field');
				update_option($action_page, $all_block);
	
				if (class_exists('Tpgb_Library') && method_exists('Tpgb_Library', 'remove_backend_dir_files')) {
					Tpgb_Library()->remove_backend_dir_files();
				}
			}
		}
	}

    /*
	 * Wdesignkit Widgets Enable / Disabled Ajax
	 * @since 4.0.7
	 */
    public function  nxt_wdk_widget_ajax_call(){
        $response = apply_filters( 'nxt_wdk_widget_ajax_call', 'wdk_update_widget' );
        wp_send_json( $response );
        wp_die();
    }

    /*
	 * Wdesignkit Import Template Block List Merge
	 * @since 4.2.4
	 */

    public function nexter_block_list_merge_action( $blockList ){

        if( empty( $blockList ) ){
            return [ 'success' => true , 'message'  => 'Block Name Not Found.' , 'description' => 'Block Name Not Found.' ];
        }

        $option_key = 'tpgb_normal_blocks_opts';
        $all_block  = get_option( $option_key );

        if ( ! is_array( $all_block ) ) {
            $all_block = [];
        }

        if ( ! isset( $all_block['enable_normal_blocks'] ) || ! is_array( $all_block['enable_normal_blocks'] ) ) {
            $all_block['enable_normal_blocks'] = [];
        }

        $sani_blockList = map_deep( wp_unslash( $blockList ), 'sanitize_text_field' );

        $all_block['enable_normal_blocks'] = array_unique( array_merge( $all_block['enable_normal_blocks'], $sani_blockList ) );

        update_option( $option_key, $all_block );

        if (class_exists('Tpgb_Library') && method_exists('Tpgb_Library', 'remove_backend_dir_files')) {
            Tpgb_Library()->remove_backend_dir_files();
        }

        return [ 'success' => true , 'message'  => 'success' , 'description' => 'success' ];
    }

    /*
    * Store User Data in Database From Onboarding Process
    * @since 4.2.4
    */
    public function nxt_block_boarding_store(){

		check_ajax_referer('tpgb-dash-ajax-nonce', 'security');

		$tponbData = ( isset($_POST['boardingData']) && !empty($_POST['boardingData']) ) ? wp_unslash(json_decode(stripslashes($_POST['boardingData']), true)) : [];
    
		$userData = [];
		if( isset($tponbData) && !empty($tponbData) ){
            
			// $tpoUpdate = update_option('tpgb_onboarding_data' , $tponbData);
			if(isset($tponbData['tpgb_onboarding']) && $tponbData['tpgb_onboarding'] == true ){
                
				$userData['web_server'] = $_SERVER['SERVER_SOFTWARE'];

				// Memory Limit
				$userData['memory_limit'] = ini_get('memory_limit');

				// Memory Limit
				$userData['max_execution_time'] = ini_get('max_execution_time');

				// Php Version
				$userData['php_version'] = phpversion();

				// Wordpress Version
				$userData['wp_version'] = get_bloginfo( 'version' );

				// Active Theme
				$acthemeobj = wp_get_theme();
				if(  $acthemeobj->get( 'Name' ) !== null && !empty( $acthemeobj->get( 'Name' ) ) ){
					$userData['theme'] = $acthemeobj->get( 'Name' );
				}

				// Active Plugin Name
				$actPlugin = [];
				$actplu = get_option('active_plugins');
				if ( ! function_exists( 'get_plugins' ) ) {
					require_once ABSPATH . 'wp-admin/includes/plugin.php';
				}

				$plugins=get_plugins();
				foreach ($actplu as $p){
					if(isset($plugins[$p])){
						$actPlugin[] = $plugins[$p]['Name'];
					}
				}

				$userData['plugin'] = json_encode($actPlugin);

                // No Of TPAG Block Used
				$get_blocks_list = get_option('tpgb_normal_blocks_opts');

				if(isset($get_blocks_list) && !empty($get_blocks_list) && isset($get_blocks_list['enable_normal_blocks']) && !empty($get_blocks_list['enable_normal_blocks']) ){
					$userData['no_block']  = count($get_blocks_list['enable_normal_blocks']);
					$userData['used_blocks'] = json_encode($get_blocks_list['enable_normal_blocks']);
 				}

				// User Email
				$userData['email'] = get_option('admin_email');

				// Site Url
				$userData['site_url'] = get_option('siteurl');

				// Site Url
				$userData['site_language'] = get_bloginfo("language");

                // Nexter Block Version
                $userData['nexter_block_version'] = TPGB_VERSION;

				$response = wp_remote_post('https://api.posimyth.com/wp-json/tpgb/v2/tpgb_store_user_data' , array(
					'method' => 'POST',
					'body' => json_encode($userData)
				) );

				if (is_wp_error($response)) {
					echo wp_send_json([ 'onBoarding' => false ]);
				} else {
					$StatusOne = wp_remote_retrieve_response_code($response);
					if($StatusOne == 200){
						$GetDataOne = wp_remote_retrieve_body($response);
						$GetDataOne = (array) json_decode(json_decode($GetDataOne, true));
						if(isset($GetDataOne['success']) && !empty($GetDataOne['success']) ){
                            add_option( 'nxt_onboarding_done' , true );
                            echo wp_send_json([ 'onBoarding' => true ]);
						}
					}
				}
			}else{
                add_option( 'nxt_onboarding_done' , true );
                echo wp_send_json([ 'onBoarding' => true ]);
            }
		}
		exit;
	}

}

// Get it started
$Tpgb_Gutenberg_Settings_Options = new Tpgb_Gutenberg_Settings_Options();
$Tpgb_Gutenberg_Settings_Options->hooks();