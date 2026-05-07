<?php
/**
 * Plugin Name: Nexter Blocks
 * Plugin URI: https://nexterwp.com/nexter-blocks/
 * Description: Highly customizable WordPress Gutenberg blocks to build professional websites with top-notch performance and sleek design. Includes 40+ FREE WordPress Blocks.
 * Version: 4.7.7
 * Author: POSIMYTH
 * Author URI: https://posimyth.com
 * Tested up to: 6.9
 * Text Domain: the-plus-addons-for-block-editor
 * Domain Path: /languages
 * License: GPLv3
 * License URI: https://opensource.org/licenses/GPL-3.0
 *
 * @package ThePluginAddonsForBlockEditor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

defined( 'TPGB_VERSION' ) || define( 'TPGB_VERSION', '4.7.7' );
define( 'TPGB_FILE__', __FILE__ );

define( 'TPGB_PATH', plugin_dir_path( __FILE__ ) );
define( 'TPGB_BASENAME', plugin_basename( __FILE__ ) );
define( 'TPGB_BDNAME', basename( __DIR__ ) );
define( 'TPGB_URL', plugins_url( '/', __FILE__ ) );
define( 'TPGB_ASSETS_URL', TPGB_URL );
define( 'TPGB_INCLUDES_URL', TPGB_PATH . 'includes/' );
define( 'TPGB_CATEGORY', 'tpgb' );
define( 'TPGB_ADMIN_NOTICE_FALG', 3 );

if ( ! version_compare( PHP_VERSION, '5.6.40', '>=' ) ) {
	add_action( 'admin_notices', 'tpgb_check_php_version' );
} elseif ( ! version_compare( get_bloginfo( 'version' ), '4.7.1', '>=' ) ) {
	add_action( 'admin_notices', 'tpgb_check_wp_version' );
} else {
	if ( defined( 'TPGBP_VERSION' ) && ! version_compare( TPGBP_VERSION, '4.0.0', '>=' ) ) {
		add_action( 'admin_notices', 'tpgb_free_check_tpag_version' );
	}
	require_once 'plus-block-loader.php';
}

/**
 * Nexter Blocks check minimum PHP version.
 *
 * Warning when the site doesn't have the minimum required PHP version.
 *
 * @since 1.0.0
 *
 * @return void The result.
 */
function tpgb_check_php_version() {
	/* translators: Nexter Blocks requires PHP version %s+. The plugin is currently not running. Please update to the latest PHP version. */
	$check_message   = sprintf( esc_html__( 'Nexter Blocks requires PHP version %s+. The plugin is currently not running. Please update to the latest PHP version.', 'the-plus-addons-for-block-editor' ), '5.6.40' ); // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment
	$display_message = sprintf( '<div class="error">%s</div>', wpautop( $check_message ) );
	echo wp_kses_post( $display_message );
}

/**
 * Nexter Blocks check minimum WordPress version.
 *
 * Warning when the site doesn't have the minimum required WordPress version.
 *
 * @since 1.0.0
 *
 * @return void The result.
 */
function tpgb_check_wp_version() {
	/* translators: Nexter Blocks requires at least WordPress version %s+. Because you’re using an older version, the plugin is currently not running. Please update WordPress to the latest version. */
	$check_message   = sprintf( esc_html__( 'Nexter Blocks requires at least WordPress version %s+. Because you’re using an older version, the plugin is currently not running. Please update WordPress to the latest version.', 'the-plus-addons-for-block-editor' ), '4.7.1' );
	$display_message = sprintf( '<div class="error">%s</div>', wpautop( $check_message ) );
	echo wp_kses_post( $display_message );
}

/**
 * Nexter Blocks Pro check minimum version 4.0.0.
 *
 * Warning when the site doesn't have the minimum required Nexter Blocks version.
 *
 * @since 4.0.2
 *
 * @return void The result.
 */
function tpgb_free_check_tpag_version() {
	/** translators: Nexter Blocks Pro requires Nexter Blocks Free version %s+. Since you’re using an older version, the plugin is currently not active. */ // phpcs:ignore Generic.Commenting.DocComment.ShortNotCapital,Generic.Commenting.DocComment.LongNotCapital,Generic.Commenting.DocComment.MissingShort
	$check_message = sprintf( '<b>Note:</b>' . esc_html__( ' Please update the Pro version to at least V4.0.0. If you don’t see the upgrade notice, upload the zip manually to the latest version from the ', 'the-plus-addons-for-block-editor' ) . '<a href="%s">store download.</a>', esc_url( 'https://store.posimyth.com/download/' ) );

	$display_message = sprintf( '<div class="error">%s</div>', wpautop( $check_message ) );

	echo wp_kses_post( $display_message );
}

/*
 * Nexter Blocks Plugin Update Message
 * @since 1.1.3
 */
add_action( 'in_plugin_update_message-the-plus-addons-for-block-editor/the-plus-addons-for-block-editor.php', 'tpgb_plugin_update_message', 10, 2 );
/**
 * Tpgb plugin update message.
 *
 * @param array $data The data.
 * @param mixed $response The response.
 */
function tpgb_plugin_update_message( $data, $response ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
	if ( isset( $data['upgrade_notice'] ) && ! empty( $data['upgrade_notice'] ) ) {
		$message = sprintf( '<div class="update-message">%s</div>', wpautop( $data['upgrade_notice'] ) );
		echo wp_kses_post( $message );
	}
}

add_filter(
	'wpml_config_files',
	function ( $config_files ) {
		$config_files[] = TPGB_PATH . '/wpml-config.xml';
		return $config_files; // phpcs:ignore Squiz.PHP.NonExecutableCode.Unreachable
	}
);
