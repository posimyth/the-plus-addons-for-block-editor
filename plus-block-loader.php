<?php
/**
 * The Plus Gutenberg Loader.
 * @since 1.0.0
 * @package TP_Gutenberg_Loader
 */
if ( !defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( !class_exists( 'TP_Gutenberg_Loader' ) ) {
    
    /**
     * Class TP_Gutenberg_Loader.
     */
    final class TP_Gutenberg_Loader
    {
        
        /**
         * Member Variable
         *
         * @var instance
         */
        private static $instance;
        
        /**
         *  Initiator
         */
        public static function get_instance()
        {
            if ( !isset( self::$instance ) ) {
                self::$instance = new self;
            } 
            return self::$instance;
        }
        
        /**
         * Constructor
         */
        public function __construct() {
            
            //check Gutenberg plugin required
            if ( !function_exists( 'register_block_type' ) ) {
                add_action( 'admin_notices', array( $this, 'tpgb_check_gutenberg_req' ) );
                return;
            } 
            
            $this->loader_helper();
            
            add_action( 'plugins_loaded', array( $this, 'tp_plugin_loaded' ) );
        }
        
        /**
         * Loads Helper/Other files.
         *
         * @since 1.0.0
         *
         * @return void
         */
        public function loader_helper() {
           
			if ( ! class_exists( 'CMB2' ) ){
				require_once TPGB_PATH . 'includes/metabox/init.php';
            }
			
			
			$option_name='default_tpgb_load_opt';
			$value='1';
			if ( is_admin() && get_option( $option_name ) !== false ) {
			} else if( is_admin() ){
				$default_load=get_option( 'tpgb_normal_blocks_opts' );
				if ( $default_load !== false && $default_load!='') {
					$deprecated = null;
					$autoload = 'no';
					add_option( $option_name,$value, $deprecated, $autoload );
				}else{
					$tpgb_normal_blocks_opts=get_option( 'tpgb_normal_blocks_opts' );
					$tpgb_normal_blocks_opts['enable_normal_blocks']= array("tp-accordion","tp-breadcrumbs","tp-blockquote","tp-button","tp-countdown","tp-creative-image","tp-data-table","tp-draw-svg","tp-empty-space","tp-flipbox","tp-google-map","tp-heading-title","tp-hovercard","tp-infobox","tp-messagebox","tp-number-counter","tp-pricing-list","tp-pricing-table","tp-pro-paragraph","tp-progress-bar","tp-row","tp-stylist-list","tp-social-icons","tp-tabs-tours","tp-testimonials","tp-video");
					
					$deprecated = null;
					$autoload = 'no';
					add_option( 'tpgb_normal_blocks_opts',$tpgb_normal_blocks_opts, $deprecated, $autoload );
					add_option( $option_name,$value, $deprecated, $autoload );
				}
			}
            require TPGB_PATH . 'includes/plus-settings-options.php';
            
            require_once TPGB_PATH . 'classes/tp-block-helper.php';
        }
        
        /*
         * Files load plugin loaded.
         *
         * @since 1.0.0
         *
         * @return void
         */
        public function tp_plugin_loaded() {
            $this->load_textdomain();
            require_once TPGB_PATH . 'classes/tp-core-init-blocks.php';
        }
        
        /*
         * Check Gutenberg Plugin Install / Activate Notice
         *
         * @since 1.0.0
         *
         */
        public function tpgb_check_gutenberg_req() {
            
            $notice_class = 'notice notice-error';
            
            $plugin = 'gutenberg/gutenberg.php';
            if ( $this->check_gutenberg_installed( $plugin ) ) {
                if ( !current_user_can( 'activate_plugins' ) ) {
                    return;
                }
                $message              = sprintf( __( '%1$sThe Plus Addons for Block Editor%2$s plugin requires %1$sGutenberg%2$s plugin activated.', 'tpgb' ), '<strong>', '</strong>' );
                $button_text          = __( 'Activate Gutenberg', 'tpgb' );
                $gutenberg_action_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );
                
            } else {
                
                if ( !current_user_can( 'install_plugins' ) ) {
                    return;
                }
                $message              = sprintf( __( '%1$sThe Plus Addons for Block Editor%2$s plugin requires %1$sGutenberg%2$s plugin installed.', 'tpgb' ), '<strong>', '</strong>' );
                $button_text          = __( 'Install Gutenberg', 'tpgb' );
                $gutenberg_action_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=gutenberg' ), 'install-plugin_gutenberg' );
            }
            
            $button = '<p><a href="' . $gutenberg_action_url . '" class="button-primary">' . $button_text . '</a></p>';
            printf( '<div class="%1$s"><p>%2$s</p>%3$s</div>', esc_attr( $notice_class ), $message, $button );
            
        }
        
        /**
         * Load The Plus Addon Gutenberg Text Domain.
         * Text Domain : tpgb
         * @since  1.0.0
         * @return void
         */
        public function load_textdomain() {
            load_plugin_textdomain( 'tpgb', false, TPGB_BDNAME . '/lang' );
        }
        
        /**
         * If Check Gutenberg is installed
         *
         * @since 1.0.0
         *
         * @param string $plugin_url Plugin path.
         * @return boolean true | false
         * @access public
         */
        public function check_gutenberg_installed( $plugin_url ) {
            $get_plugins = get_plugins();
            return isset( $get_plugins[ $plugin_url ] );
        }
    }
    
    TP_Gutenberg_Loader::get_instance();
}