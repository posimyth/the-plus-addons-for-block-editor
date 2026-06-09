<?php
/**
 * Nexter Blocks Loader.
 *
 * @since 1.0.0
 * @package Tpgb_Gutenberg_Loader
 */

// phpcs:disable Squiz.PHP.CommentedOutCode.Found
// phpcs:disable Squiz.Commenting.InlineComment.InvalidEndChar

// phpcs:disable Universal.Files.SeparateFunctionsFromOO.Mixed
// phpcs:disable WordPress.Files.FileName

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Tpgb_Gutenberg_Loader' ) ) {

	/**
	 * Class Tpgb_Gutenberg_Loader.
	 */
	final class Tpgb_Gutenberg_Loader {

		/**
		 * Member Variable
		 *
		 * @var instance
		 */
		private static $instance;

		/**
		 * Post assets objects.
		 *
		 * @var array
		 */
		public $post_assets_objects = array();

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

			$this->loader_helper();

			add_action( 'plugins_loaded', array( $this, 'tp_plugin_loaded' ) );

			if ( is_admin() ) {
				require_once TPGB_PATH . 'classes/tp-admin.php';
			}
		}

		/**
		 * Loads Helper/Other files.
		 *
		 * @since 1.0.0
		 *
		 * @return void The result.
		 */
		public function loader_helper() {
			$option_name = 'default_tpgb_load_opt';
			$value       = '1';
			if ( is_admin() && get_option( $option_name ) !== false ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIF, Generic.CodeAnalysis.EmptyStatement.DetectedIf
			} elseif ( is_admin() ) {
				$default_load = get_option( 'tpgb_normal_blocks_opts' );
				if ( false !== $default_load && '' !== $default_load ) {
					$autoload = 'no';
					add_option( $option_name, $value, '', $autoload );
				} else {
					$tpgb_normal_blocks_opts = get_option( 'tpgb_normal_blocks_opts' );
					if ( false === $tpgb_normal_blocks_opts ) {
						$tpgb_normal_blocks_opts = array();
					}
					$tpgb_normal_blocks_opts['enable_normal_blocks'] = array( 'tp-accordion', 'tp-breadcrumbs', 'tp-blockquote', 'tp-button-core', 'tp-countdown', 'tp-container', 'tp-data-table', 'tp-draw-svg', 'tp-empty-space', 'tp-flipbox', 'tp-google-map', 'tp-heading', 'tp-icon-box', 'tp-infobox', 'tp-image', 'tp-messagebox', 'tp-number-counter', 'tp-post-listing', 'tp-pricing-list', 'tp-pricing-table', 'tp-pro-paragraph', 'tp-stylist-list', 'tp-social-icons', 'tp-tabs-tours', 'tp-testimonials' );

					$tpgb_normal_blocks_opts['tp_extra_option'] = array( 'tp-global-block-style', 'tp-advanced-border-radius', 'tp-display-rules', 'tp-equal-height', 'tp-event-tracking', 'tp-magic-scroll', 'tp-global-tooltip', 'tp-continuous-animation', 'tp-content-hover-effect', 'tp-mouse-parallax', 'tp-3d-tilt', 'tp-scoll-animation' );

					$autoload = 'no';
					add_option( 'tpgb_normal_blocks_opts', $tpgb_normal_blocks_opts, '', $autoload );
					add_option( $option_name, $value, '', $autoload );
					$action_delay = 'tpgb_delay_css_js';
					if ( false === get_option( $action_delay ) ) {
						add_option( $action_delay, 'true' );
					}
					$action_defer = 'tpgb_defer_css_js';
					if ( false === get_option( $action_defer ) ) {
						add_option( $action_defer, 'true' );
					}

					// Add option For Init Nexter Block Version.
					$nxt_data = array(
						'install-version' => TPGB_VERSION,
						'install-date'    => gmdate( 'd-m-Y' ),
					);
					add_option( 'nexter-installed-data', $nxt_data );

				}
			}

			// Load Conditions Rules.
			require_once TPGB_PATH . 'classes/extras/tpgb-conditions-rules.php';

			// Reusable Short code.
			require_once TPGB_PATH . 'classes/extras/tpag-reusable-shortcode.php';

			if ( is_admin() ) {

				// Rollback.
				require TPGB_PATH . 'includes/rollback.php';

				// What's New Notification.
				require TPGB_PATH . 'classes/extras/nexter-block-whats-new.php';

				// Dashboard Options.
				require TPGB_PATH . 'includes/plus-settings-options.php';

				// Plugin Deactive Popup.
				require_once TPGB_PATH . 'classes/extras/tpag-deactive.php';
			}

			require_once TPGB_PATH . 'classes/tp-block-helper.php';

			// Load AI API registration file only on admin OR during REST API requests to our namespace (tpgb/v1).
			$values = '';

			$ai_enabled          = false;
			$options             = get_option( 'tpgb_connection_data', array() );
			$nxt_ai_settings_raw = Tp_Blocks_Helper::get_extra_option( 'nxtAiSettings' );
			$encrypted           = '';
			if ( is_string( $nxt_ai_settings_raw ) ) {
				// Correct & ideal case.
				$encrypted = $nxt_ai_settings_raw;
			} elseif ( is_array( $nxt_ai_settings_raw ) ) {
				// Case: numeric array [0 => 'encrypted']. // phpcs:ignore Squiz.PHP.CommentedOutCode.Found, Squiz.Commenting.InlineComment.InvalidEndChar
				if ( isset( $nxt_ai_settings_raw[0] ) && is_string( $nxt_ai_settings_raw[0] ) ) {
					$encrypted = $nxt_ai_settings_raw[0];
					// Case: associative array ['value' => 'encrypted']. // phpcs:ignore Squiz.PHP.CommentedOutCode.Found, Squiz.Commenting.InlineComment.InvalidEndChar
				} elseif ( count( $nxt_ai_settings_raw ) === 1 ) {
					$encrypted = reset( $nxt_ai_settings_raw );
				}
			}
			if ( is_string( $encrypted ) && ! empty( $encrypted ) ) {
				$decrypted       = Tp_Blocks_Helper::tpgb_simple_decrypt( $encrypted, 'dy' );
				$nxt_ai_settings = json_decode( $decrypted, true );

				if ( is_array( $nxt_ai_settings ) && isset( $nxt_ai_settings['aiIntegrationEnabled'] ) ) {
					$ai_enabled = (bool) $nxt_ai_settings['aiIntegrationEnabled'];
				}
			} else {
				$nxt_ai_settings = array();
			}

			$rest_prefix     = '/' . rest_get_url_prefix() . '/';
			$is_our_rest_api = ( isset( $_SERVER['REQUEST_URI'] ) && strpos( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ), $rest_prefix . 'tpgb/v1/' ) !== false ) || ( isset( $_GET['rest_route'] ) && strpos( sanitize_text_field( wp_unslash( $_GET['rest_route'] ) ), '/tpgb/v1/' ) === 0 ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput,WordPress.Security.NonceVerification.Missing,WordPress.Security.NonceVerification.Recommended
			if ( $ai_enabled && ( ( defined( 'WP_ADMIN' ) && WP_ADMIN ) || ( isset( $_SERVER['REQUEST_URI'] ) && strpos( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ), '/wp-admin/' ) !== false ) || $is_our_rest_api ) ) {
				require_once TPGB_PATH . 'includes/nxt_ai_apis/nxt-ai-register-api.php';
			}

			if ( function_exists( 'wp_register_ability' ) ) {
				$mcp_option        = get_option( 'tpgb_connection_data', array() );
				$mcp_ability_value = isset( $mcp_option['nxt_enable_mcp_abilities'] ) ? $mcp_option['nxt_enable_mcp_abilities'] : 'enable';
				if ( empty( $mcp_option ) || 'enable' === $mcp_ability_value ) {
					require_once TPGB_PATH . 'includes/abilities/class-tpgb-mcp-abilities.php';
					Tpgb_MCP_Abilities::instance();
				}
			}
		}

		/**
		 * Files load plugin loaded.
		 *
		 * @since 1.1.3
		 *
		 * @return void The result.
		 */
		public function tp_plugin_loaded() {
			$this->load_textdomain();
			require_once TPGB_PATH . 'classes/tp-generate-block-css.php';

			require_once TPGB_PATH . 'classes/tp-get-blocks.php';
			require_once TPGB_PATH . 'classes/tp-core-init-blocks.php';

			if ( defined( 'AGNI_PLUGIN_URL' ) || class_exists( 'AgniBuilder' ) ) {
				require_once TPGB_PATH . 'classes/extras/compatibility/class-tpag-cartify.php';
			}

			// wdkit widget api.
			if ( is_admin() && isset( $_GET['page'] ) && 'nexter_welcome' === $_GET['page'] ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput,WordPress.Security.NonceVerification.Missing,WordPress.Security.NonceVerification.Recommended
				require_once TPGB_PATH . 'classes/extras/tpgb-wdk-widgets-api.php';
			}
		}

		/**
		 * Load Nexter Blocks Text Domain.
		 * Text Domain : the-plus-addons-for-block-editor
		 *
		 * @since  1.0.0
		 * @return void The result.
		 */
		public function load_textdomain() {
			load_plugin_textdomain( 'the-plus-addons-for-block-editor', false, TPGB_BDNAME . '/languages/' );
		}

		/**
		 * If Check Gutenberg is installed
		 *
		 * @since 1.0.0
		 *
		 * @param string $plugin_url Plugin path.
		 * @return boolean true | false.
		 * @access public
		 */
		public function check_gutenberg_installed( $plugin_url ) {
			$get_plugins = get_plugins();
			return isset( $get_plugins[ $plugin_url ] );
		}
	}

	add_action(
		'plugins_loaded',
		function () {
			Tpgb_Gutenberg_Loader::get_instance();
		},
		1
	);

	/**
	 * Tpgb load data.
	 *
	 * @return mixed The result.
	 */
	function tpgb_load_data() {
		return Tpgb_Gutenberg_Loader::get_instance();
	}
}
