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

if ( ! class_exists( 'Tpgb_Toolset_Compat' ) ) {

	/**
	 * Tpgb_ Toolset_ Compat.
	 *
	 * @since 1.0.0
	 */
	final class Tpgb_Toolset_Compat {

		/**
		 * Instance
		 */ // phpcs:ignore Squiz.Commenting.VariableComment.MissingVar,Squiz.Commenting.VariableComment.Missing
		private static $instance; // phpcs:ignore Squiz.Commenting.VariableComment.Missing,Squiz.Commenting.VariableComment.MissingVar

		/**
		 *  Initiator
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new Tpgb_Toolset_Compat();

				add_filter( 'wp', array( self::$instance, 'toolset_blocks_compatibility_enqueue_wpa' ) );
				add_action( 'wp', array( self::$instance, 'toolset_blocks_compatibility_ct_assets' ) );
			}
		}

		/** // phpcs:ignore Squiz.Commenting.FunctionComment, Generic.Commenting.DocComment.ShortNotCapital,Generic.Commenting.DocComment.LongNotCapital,Generic.Commenting.DocComment.MissingShort
		 * Toolset blocks Compatibility enqueue templates
		 *
		 * @since 2.0.0
		 * */
		public function toolset_blocks_compatibility_enqueue_wpa( $content ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
			if ( ! is_archive() && ! is_home() && ! is_search() ) {
				return;
			}
			$wpa_id = apply_filters( 'wpv_filter_wpv_get_current_archive', null );

			if ( ! $wpa_id ) {
				return;
			}

			$maybe_wpa_helper_id = apply_filters( 'wpv_filter_wpv_get_wpa_helper_post', $wpa_id );

			if ( ! empty( $maybe_wpa_helper_id ) && class_exists( 'Tpgb_Core_Init_Blocks' ) ) {
				$load_enqueue = Tpgb_Core_Init_Blocks::get_instance();

				if ( ! empty( $load_enqueue ) && is_callable( array( $load_enqueue, 'enqueue_post_css' ) ) ) {
					$load_enqueue->enqueue_post_css( $maybe_wpa_helper_id );
				}
			}
		}

		/**
		 * Toolset blocks Compatibility enqueue assets
		 *
		 * @since 2.0.0
		 * */
		public function toolset_blocks_compatibility_ct_assets() {
			if ( ! is_single() ) {
				return;
			}

			global $post;

			$maybe_ct_selected = apply_filters( 'wpv_content_template_for_post', 0, $post );

			if ( 0 === (int) $maybe_ct_selected ) {
				return;
			}

			if ( ! empty( $maybe_ct_selected ) && class_exists( 'Tpgb_Core_Init_Blocks' ) ) {
				$load_enqueue = Tpgb_Core_Init_Blocks::get_instance();

				if ( ! empty( $load_enqueue ) && is_callable( array( $load_enqueue, 'enqueue_post_css' ) ) ) {
					$load_enqueue->enqueue_post_css( $maybe_ct_selected );
				}
			}
		}
	}
	Tpgb_Toolset_Compat::instance();
}
