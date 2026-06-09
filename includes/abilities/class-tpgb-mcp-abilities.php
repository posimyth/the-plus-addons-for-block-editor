<?php
/**
 * Bootstraps Nexter Blocks MCP abilities and their category.
 *
 * @package The_Plus_Addons_For_Block_Editor
 * @since   1.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers the Nexter Blocks ability category and loads each ability file.
 */
class Tpgb_MCP_Abilities {

	/**
	 * Singleton instance.
	 *
	 * @var ?Tpgb_MCP_Abilities
	 */
	private static $instance;

	/**
	 * Gets the singleton instance of the class.
	 *
	 * @return Tpgb_MCP_Abilities
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 *
	 * Initializes MCP abilities if enabled in the plugin options and hooks
	 * registration callbacks into the abilities API.
	 */
	public function __construct() {
		$mcp_option        = get_option( 'tpgb_connection_data', array() );
		$mcp_ability_value = isset( $mcp_option['nxt_enable_mcp_abilities'] ) ? $mcp_option['nxt_enable_mcp_abilities'] : 'enable';
		if ( 'enable' !== $mcp_ability_value ) {
			return;
		}

		add_action( 'wp_abilities_api_categories_init', array( $this, 'register_categories' ) );
		add_action( 'wp_abilities_api_init', array( $this, 'register_abilities' ) );
	}

	/**
	 * Registers ability categories for Nexter Blocks and Nexter Blocks Pro.
	 *
	 * @return void
	 */
	public function register_categories(): void {
		wp_register_ability_category(
			'nexter-blocks',
			array(
				'label'       => __( 'Nexter Blocks', 'the-plus-addons-for-block-editor' ),
				'description' => __( 'Nexter Blocks Gutenberg abilities.', 'the-plus-addons-for-block-editor' ),
			)
		);

		if ( defined( 'TPGBP_VERSION' ) && defined( 'TPGBP_PATH' ) ) {
			wp_register_ability_category(
				'nexter-blocks-pro',
				array(
					'label'       => __( 'Nexter Blocks Pro', 'the-plus-addons-for-block-editor' ),
					'description' => __( 'Nexter Blocks Pro procedural skill guides for orchestrating builds when the pro plugin is active.', 'the-plus-addons-for-block-editor' ),
				)
			);
		}
	}

	/**
	 * Registers MCP abilities by loading ability files for both free and pro versions.
	 *
	 * @return void
	 */
	public function register_abilities(): void {
		$base = TPGB_PATH . 'includes/abilities/';

		require_once $base . 'helpers.php';

		// Skills loaded first — performance skill must run before others, typography skill must run right after performance.
		foreach ( array( 'get-performance-skill', 'get-typography-skill', 'get-image-to-page-skill', 'get-doc-creator-skill', 'inspect-page' ) as $f ) {
			require_once $base . $f . '.php';
		}

		foreach ( array(
			'add-tpgb-accordion',
			'add-tpgb-blockquote',
			'add-tpgb-heading',
			'add-tpgb-breadcrumbs',
			'add-tpgb-button',
			'add-tpgb-button-core',
			'add-tpgb-button-preset-crud',
			'add-tpgb-code-highlighter',
			'add-tpgb-container',
			'add-tpgb-container-inner',
			'add-tpgb-countdown',
			'add-tpgb-creative-image',
			'add-tpgb-dark-mode',
			'add-tpgb-data-table',
			'add-tpgb-draw-svg',
			'add-tpgb-flipbox',
			'add-tpgb-form-block',
			'add-tpgb-form-fields',
			'add-tpgb-google-map',
			'add-tpgb-heading-title',
			'add-tpgb-hovercard',
			'add-tpgb-icon-box',
			'add-tpgb-image',
			'add-tpgb-infobox',
			'add-tpgb-interactive-circle-info',
			'add-tpgb-messagebox',
			'add-tpgb-navigation-builder',
			'add-tpgb-number-counter',
			'add-tpgb-pricing-list',
			'add-tpgb-pricing-table',
			'add-tpgb-pro-paragraph',
			'add-tpgb-progress-bar',
			'add-tpgb-post-author',
			'add-tpgb-post-comment',
			'add-tpgb-post-content',
			'add-tpgb-post-image',
			'add-tpgb-post-listing',
			'add-tpgb-post-meta',
			'add-tpgb-post-title',
			'add-tpgb-progress-tracker',
			'add-tpgb-search-bar',
			'add-tpgb-site-logo',
			'add-tpgb-smooth-scroll',
			'add-tpgb-social-embed',
			'add-tpgb-social-icons',
			'add-tpgb-stylist-list',
			'add-tpgb-switcher',
			'add-tpgb-tabs-tours',
			'add-tpgb-team-listing',
			'add-tpgb-testimonials',
			'add-tpgb-video',
		) as $f ) {
			require_once $base . $f . '.php';
		}

		if ( ! defined( 'TPGBP_VERSION' ) || ! defined( 'TPGBP_PATH' ) ) {
			return;
		}

		$pro = TPGBP_PATH . 'includes/abilities/';

		$pro_helpers = $pro . 'helpers.php';
		if ( file_exists( $pro_helpers ) ) {
			require_once $pro_helpers;
		}

		foreach ( array( 'get-performance-skill', 'get-typography-skill', 'get-image-to-page-skill', 'get-doc-creator-skill' ) as $f ) {
			$path = $pro . $f . '.php';
			if ( file_exists( $path ) ) {
				require_once $path;
			}
		}

		foreach ( array(
			'add-tpgb-accordion-inner',
			'add-tpgb-advanced-buttons',
			'add-tpgb-advanced-chart',
			'add-tpgb-adv-typo',
			'add-tpgb-animated-service-boxes',
			'add-tpgb-anything-carousel',
			'add-tpgb-anything-slide',
			'add-tpgb-audio-player',
			'add-tpgb-before-after',
			'add-tpgb-carousel-remote',
			'add-tpgb-circle-menu',
			'add-tpgb-column',
			'add-tpgb-coupon-code',
			'add-tpgb-cta-banner',
			'add-tpgb-design-tool',
			'add-tpgb-dynamic-category',
			'add-tpgb-dynamic-device',
			'add-tpgb-expand',
			'add-tpgb-free-shipping-progress-bar',
			'add-tpgb-heading-animation',
			'add-tpgb-hotspot',
			'add-tpgb-login-register',
			'add-tpgb-lottiefiles',
			'add-tpgb-mailchimp',
			'add-tpgb-media-listing',
			'add-tpgb-mobile-menu',
			'add-tpgb-mouse-cursor',
			'add-tpgb-popup-builder',
			'add-tpgb-post-navigation',
			'add-tpgb-preloader',
			'add-tpgb-process-steps',
			'add-tpgb-product-listing',
			'add-tpgb-repeater-block',
			'add-tpgb-scroll-navigation',
			'add-tpgb-scroll-sequence',
			'add-tpgb-social-feed',
			'add-tpgb-social-reviews',
			'add-tpgb-social-sharing',
			'add-tpgb-spline-3d-viewer',
			'add-tpgb-switch-inner',
			'add-tpgb-tab-item',
			'add-tpgb-table-content',
			'add-tpgb-timeline',
			'add-tpgb-timeline-inner',
		) as $f ) {
			$path = $pro . $f . '.php';
			if ( file_exists( $path ) ) {
				require_once $path;
			}
		}
	}
}
