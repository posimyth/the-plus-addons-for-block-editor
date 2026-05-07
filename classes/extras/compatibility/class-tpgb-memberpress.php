<?php
/**
 * MemberPress Course Compatibility
 *
 * @since 4.5.10
 * @package ThePluginAddonsForBlockEditor
 */

// phpcs:disable WordPress.Files.FileName

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Tpgb_Memberpress_Compat' ) ) {

	/**
	 * Tpgb_ Memberpress_ Compat.
	 *
	 * @since 1.0.0
	 */
	final class Tpgb_Memberpress_Compat {

		/**
		 * Instance
		 */ // phpcs:ignore Squiz.Commenting.VariableComment.MissingVar,Squiz.Commenting.VariableComment.Missing
		private static $instance; // phpcs:ignore Squiz.Commenting.VariableComment.Missing,Squiz.Commenting.VariableComment.MissingVar

		/**
		 *  Initiator
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new Tpgb_Memberpress_Compat();

				add_filter( 'mpcs_classroom_style_handles', array( self::$instance, 'memberpress_remove_style' ) );
			}

			return self::$instance;
		}

		/** // phpcs:ignore Squiz.Commenting.FunctionComment, Generic.Commenting.DocComment.ShortNotCapital,Generic.Commenting.DocComment.LongNotCapital,Generic.Commenting.DocComment.MissingShort
		 * MemberPress Course Compatibility
		 *
		 * @since 3.0.5
		 * */
		public function memberpress_remove_style( $allowed_handles = array() ) {
			global $wp_styles;
			if ( ! empty( $wp_styles ) ) {
				foreach ( $wp_styles->queue as $style ) {
					$handle = $wp_styles->registered[ $style ]->handle;
					if ( preg_match( '/^tpgb-/i', $handle ) || substr( $handle, 0, 13 ) === 'plus-preview-' || substr( $handle, 0, 10 ) === 'plus-post-' || substr( $handle, 0, 7 ) === 'theplus' || 'plus-global' === $handle ) {
						$allowed_handles[] = $handle;
					}
				}
			}
			return $allowed_handles;
		}
	}

	Tpgb_Memberpress_Compat::instance();
}
