<?php
/**
 * Tpgb Admin.
 *
 * @package Tpgb
 */

// phpcs:disable WordPress.Files.FileName

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Tpgb_Admin' ) ) {

	/**
	 * Class UAGB_Admin.
	 */
	final class Tpgb_Admin {

		/**
		 * Member Variable
		 *
		 * @var instance
		 */
		private static $instance;

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

			// Show admin notice after 2 days.
			if ( ! defined( 'TPGBP_VERSION' ) && ! defined( 'NEXTER_EXT_VER' ) ) {
				add_action( 'admin_notices', array( $this, 'nexter_block_show_pro_notice' ) );
			}

			if ( defined( 'NEXTER_EXT_VER' ) && version_compare( NEXTER_EXT_VER, '4.4.0', '<' ) ) {
				add_action( 'admin_notices', array( $this, 'nexter_extension_version_notice' ) );
			}

			$whitedata = get_option( 'tpgb_white_label' );
			if ( ( empty( $whitedata ) || ( ! empty( $whitedata['nxt_help_link'] ) && 'on' !== $whitedata['nxt_help_link'] ) ) ) {
				add_filter( 'plugin_action_links_' . TPGB_BASENAME, array( $this, 'tpgb_settings_pro_link' ) );
			}

			add_filter( 'plugin_row_meta', array( $this, 'tpbg_extra_links_plugin_row_meta' ), 10, 2 );

			add_action( 'wp_ajax_nxt_dismiss_plugin_rebranding', array( $this, 'nxt_dismiss_plugin_rebranding_callback' ), 10, 1 );

			add_action( 'wp_ajax_nexter_dismiss_notice', array( $this, 'nexter_dismiss_notice' ) );

			add_action( 'wp_ajax_tpgb_cross_cp_import', array( $this, 'cross_copy_paste_media_import' ) );

			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_css_js' ) );

			/**Remove Cache Transient*/
			add_action( 'wp_ajax_Tp_f_delete_transient', array( $this, 'Tp_f_delete_transient' ) );

			// Table Of Content Rank Math Compatiblility.
			if ( defined( 'RANK_MATH_VERSION' ) ) {
				add_filter( 'rank_math/researches/toc_plugins', array( $this, 'tpgb_rank_table_of_content' ) );
			}
		}

		/**
		 * Pro Block Notice
		 *
		 * @since 4.5.6
		 */
		public function nexter_block_show_pro_notice() {

			if ( get_option( 'nexter_block_show_pro_dismissed' ) ) {
				return;
			}

			// Only show for admins.
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			$nxt_data = get_option( 'nexter-installed-data' );

			if ( ! is_array( $nxt_data ) || empty( $nxt_data['install-date'] ) ) {
				return;
			}

			// Convert from d-m-Y to timestamp.
			$in_time = strtotime( $nxt_data['install-date'] );

			if ( ! $in_time ) {
				return;
			}

			$day_count = floor( ( current_time( 'timestamp' ) - $in_time ) / DAY_IN_SECONDS ); // phpcs:ignore WordPress.DateTime.CurrentTimeTimestamp.Requested
			// Show after 2 days.
			if ( $day_count >= 2 ) {

				echo '<div class="notice notice-info is-dismissible nxt-notice-wrap" data-notice-id="nexter_block_show_pro">';
					echo '<div class="nexter-license-activate">';
						echo '<div class="nexter-license-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"><rect width="24" height="24" fill="#1717CC" rx="5"/><path fill="#fff" d="M12.605 17.374c.026 0 .038.013.039.038.102 0 .192.014.27.04.025 0 .05.012.076.037.128.077.23.167.307.27v.038c0 .026.013.051.039.077v.038a.63.63 0 0 1 .038.193l-.038 1.882h-2.652v-2.613h1.921Zm.308-13.414c.128 0 .23.038.308.115a.259.259 0 0 1 .115.23V15.26c.025.153-.052.295-.23.423a.872.872 0 0 1-.578.192h-1.844V3.96h2.23Z"/></svg></div>';
						echo '<div class="nexter-license-content">';
							echo '<h2>' . esc_html__( 'Upgrade to Nexter Blocks Pro', 'the-plus-addons-for-block-editor' ) . '</h2>';
							echo '<p>' . esc_html__( 'Nexter Blocks free features are just the tip of the iceberg. Unlock powerful tools like Login Form, Form Builder, Mega Menu Builder, Popup Builder, and many more.', 'the-plus-addons-for-block-editor' ) . '</p>';
							echo '<a href="' . esc_url( 'https://nexterwp.com/pricing/?utm_source=wpbackend&utm_medium=admin&utm_campaign=pluginpage' ) . '" target="_blank" rel="noopener noreferrer" class="nxt-nobtn-primary">' . esc_html__( 'Upgrade Now', 'the-plus-addons-for-block-editor' ) . '</a>';
							echo '<a href="' . esc_url( 'https://nexterwp.com/free-vs-pro/?utm_source=wpbackend&utm_medium=admin&utm_campaign=pluginpage' ) . '" target="_blank" rel="noopener noreferrer" class="nxt-nobtn-secondary">' . esc_html__( 'Compare Free vs Pro', 'the-plus-addons-for-block-editor' ) . '</a>';
						echo '</div>';
					echo '</div>';
				echo '</div>';
			}
		}

		/**
		 * Adds Links to the plugins page.
		 *
		 * @since 2.0.0
		 * @param mixed $links The links.
		 */
		public function tpgb_settings_pro_link( $links ) {
			// Settings link.
			$nxtlink = array();
			if ( current_user_can( 'manage_options' ) ) {
				$nxtlinks[] = sprintf( '<a href="%s" rel="noopener noreferrer">%s</a>', admin_url( 'admin.php?page=nexter_welcome' ), __( 'Settings', 'the-plus-addons-for-block-editor' ) );
				$nxtlinks[] = sprintf( '<a href="%s" target="_blank" rel="noopener noreferrer">%s</a>', esc_url( 'https://nexterwp.com/free-vs-pro/?utm_source=wpbackend&utm_medium=admin&utm_campaign=pluginpage' ), __( 'Free vs Pro', 'the-plus-addons-for-block-editor' ) );
				$nxtlinks[] = sprintf( '<a href="%s" target="_blank" rel="noopener noreferrer">%s</a>', esc_url( 'https://store.posimyth.com/get-support-nexterwp/?utm_source=wpbackend&utm_medium=admin&utm_campaign=pluginpage' ), __( 'Need Help?', 'the-plus-addons-for-block-editor' ) );
			}

			// Upgrade PRO link.
			if ( ! defined( 'TPGBP_VERSION' ) ) {
				$nxtlinks[] = sprintf( '<a href="%s" target="_blank" style="color: #cc0000;font-weight: 700;" rel="noopener noreferrer">%s</a>', esc_url( 'https://nexterwp.com/pricing/' ), __( 'Upgrade PRO', 'the-plus-addons-for-block-editor' ) );
			}

			return array_merge( $nxtlinks, $links );
		}

		/**
		 * Nexter Extension Version Notice
		 *
		 * @since 4.5.6
		 */
		public function nexter_extension_version_notice() {

			if ( get_option( 'nexter_extension_installed_dismissed' ) ) {
				return;
			}

			echo '<div class="notice notice-info is-dismissible nxt-notice-wrap" data-notice-id="nexter_extension_installed">';
				echo '<div class="nexter-license-activate" style="align-items: center;">';
					echo '<div class="nexter-license-icon" style="display: flex;"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"><rect width="24" height="24" fill="#1717CC" rx="5"/><path fill="#fff" d="M12.605 17.374c.026 0 .038.013.039.038.102 0 .192.014.27.04.025 0 .05.012.076.037.128.077.23.167.307.27v.038c0 .026.013.051.039.077v.038a.63.63 0 0 1 .038.193l-.038 1.882h-2.652v-2.613h1.921Zm.308-13.414c.128 0 .23.038.308.115a.259.259 0 0 1 .115.23V15.26c.025.153-.052.295-.23.423a.872.872 0 0 1-.578.192h-1.844V3.96h2.23Z"/></svg></div>';
					echo '<div class="nexter-license-content">';
						echo '<h2>' . esc_html__( 'To continue using all latest features smoothly, please update Nexter Extension to version 4.4.0 or higher.', 'the-plus-addons-for-block-editor' ) . '</h2>';
					echo '</div>';
				echo '</div>';
			echo '</div>';
		}

		/** // phpcs:ignore Squiz.Commenting.FunctionComment, Generic.Commenting.DocComment.ShortNotCapital,Generic.Commenting.DocComment.LongNotCapital,Generic.Commenting.DocComment.MissingShort
		 * Adds Extra Links to the plugins row meta.
		 *
		 * @since 2.0.0
		 * @param array $plugin_meta The plugin meta.
		 */
		public function tpbg_extra_links_plugin_row_meta( $plugin_meta = array(), $plugin_file = '' ) {

			$whitedata = get_option( 'tpgb_white_label' );
			if ( strpos( $plugin_file, TPGB_BASENAME ) !== false && current_user_can( 'manage_options' ) && ( empty( $whitedata ) || ( ! empty( $whitedata['nxt_help_link'] ) && 'on' !== $whitedata['nxt_help_link'] ) ) ) {
				$new_links = array(
					'official-site'   => '<a href="' . esc_url( 'https://nexterwp.com/nexter-blocks/?utm_source=wpbackend&utm_medium=pluginpage&utm_campaign=links' ) . '" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Visit Plugin site', 'the-plus-addons-for-block-editor' ) . '</a>',
					'docs'            => '<a href="' . esc_url( 'https://nexterwp.com/docs/?utm_source=wpbackend&utm_medium=admin&utm_campaign=pluginpage' ) . '" target="_blank" rel="noopener noreferrer" style="color:green;">' . esc_html__( 'Docs', 'the-plus-addons-for-block-editor' ) . '</a>',
					'video-tutorials' => '<a href="' . esc_url( 'https://www.youtube.com/c/POSIMYTHInnovations/?sub_confirmation=1' ) . '" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Video Tutorials', 'the-plus-addons-for-block-editor' ) . '</a>',
					'join-community'  => '<a href="' . esc_url( 'https://www.facebook.com/groups/nexterwpcommunity/' ) . '" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Join Community', 'the-plus-addons-for-block-editor' ) . '</a>',
					'whats-new'       => '<a href="' . esc_url( 'https://roadmap.nexterwp.com/updates?filter=Nexter+Blocks+-+FREE' ) . '" target="_blank" rel="noopener noreferrer" style="color: orange;">' . esc_html__( 'What\'s New?', 'the-plus-addons-for-block-editor' ) . '</a>',
					'req-feature'     => '<a href="' . esc_url( 'https://roadmap.nexterwp.com/boards/feature-requests/' ) . '" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Request Feature', 'the-plus-addons-for-block-editor' ) . '</a>',
				);

				$plugin_meta = array_merge( $plugin_meta, $new_links );
			}

			if ( ! empty( $whitedata['nxt_help_link'] ) ) {
				foreach ( $plugin_meta as $key => $meta ) {
					if ( stripos( $meta, 'View details' ) !== false ) {
						unset( $plugin_meta[ $key ] );
					}
				}
			}

			return $plugin_meta;
		}

		/**
		 * Dismiss Notice Ajax
		 *
		 * @since 4.5.5
		 */
		public function nexter_dismiss_notice() {
			if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'tpgb-addons' ) ) {
				wp_send_json_error( array( 'message' => esc_html__( 'Invalid nonce. Unauthorized request.', 'the-plus-addons-for-block-editor' ) ) );
			}

			if ( ! empty( $_POST['notice_id'] ) ) {
				$notice_id = sanitize_text_field( wp_unslash( $_POST['notice_id'] ) );
				update_option( $notice_id . '_dismissed', true );
				wp_send_json_success( array( 'dismissed' => $notice_id ) );
			} else {
				wp_send_json_error( 'Invalid Notice ID' );
			}
		}

		/**
		 * Rebranding Notice disable
		 *
		 * @since 4.0.2
		 */
		public function nxt_dismiss_plugin_rebranding_callback() {
			// Verify nonce for security.
			if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'tpgb-addons' ) ) {
				wp_send_json_error( array( 'message' => esc_html__( 'Invalid nonce. Unauthorized request.', 'the-plus-addons-for-block-editor' ) ) );
			}

			if ( ! current_user_can( 'manage_options' ) ) {
				wp_send_json_error( array( 'message' => esc_html__( 'Insufficient permissions.', 'the-plus-addons-for-block-editor' ) ) );
			}

			$option_key = 'nxt_rebranding_dismissed';
			update_option( $option_key, true );

			wp_send_json_success( array( 'message' => esc_html__( 'Notice dismissed successfully.', 'the-plus-addons-for-block-editor' ) ) );
		}

		/**
		 * Cross copy paste media import
		 *
		 * @since  1.1.0
		 */
		public static function cross_copy_paste_media_import() {

			check_ajax_referer( 'tpgb-addons', 'nonce' );

			if ( ! current_user_can( 'edit_posts' ) ) {
				wp_send_json_error(
					__( 'Not a Valid', 'the-plus-addons-for-block-editor' ),
					403
				);
			}
			require_once TPGB_PATH . 'classes/global-options/tp-import-media.php';
			$media_import = isset( $_POST['content'] ) ? wp_unslash( $_POST['content'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.NonceVerification.Missing

			if ( empty( $media_import ) ) {
				wp_send_json_error( __( 'Empty Content.', 'the-plus-addons-for-block-editor' ) );
			}

			$media_import = array( json_decode( $media_import, true ) );
			$media_import = self::tp_import_media_copy_content( $media_import );

			wp_send_json_success( $media_import );
		}

		/**
		 * Recursively data.
		 *
		 * Accept any type of data and a callback function. The callback
		 * function runs recursively for each data and his child data.
		 *
		 * @since 1.1.0
		 * @access public
		 * @param array $data_import The data import.
		 */
		public static function tp_import_media_copy_content( $data_import ) {
			return self::array_recursively_data(
				$data_import,
				function ( $block_data ) {

					$elements = self::block_data_instance( $block_data );

					return $elements;
				}
			);
		}

		/** // phpcs:ignore Squiz.Commenting.FunctionComment, Generic.Commenting.DocComment.ShortNotCapital,Generic.Commenting.DocComment.LongNotCapital,Generic.Commenting.DocComment.MissingShort
		 * Block Data inner Block Instance
		 *
		 * @since 1.1.3
		 * @param array $block_data The block data.
		 * @param array $args The args.
		 */
		public static function block_data_instance( array $block_data, array $args = array(), $block_args = null ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter

			if ( $block_data['name'] && $block_data['clientId'] && $block_data['attributes'] ) {

				foreach ( $block_data['attributes'] as $block_key => $block_val ) {

					if ( isset( $block_val['url'] ) && isset( $block_val['id'] ) && ! empty( $block_val['url'] ) ) {
						$new_media                              = Tpgb_Import_Images::media_import( $block_val );
						$block_data['attributes'][ $block_key ] = $new_media;
					} elseif ( isset( $block_val['url'] ) && ! empty( $block_val['url'] ) && preg_match( '/\.(jpg|png|jpeg|gif|svg|webp|avif)$/', $block_val['url'] ) ) {
						$new_media                              = Tpgb_Import_Images::media_import( $block_val );
						$block_data['attributes'][ $block_key ] = $new_media;
					} elseif ( is_array( $block_val ) && ! empty( $block_val ) ) {
						if ( ! array_key_exists( 'md', $block_val ) && ! array_key_exists( 'openTypography', $block_val ) && ! array_key_exists( 'openBorder', $block_val ) && ! array_key_exists( 'openShadow', $block_val ) && ! array_key_exists( 'openFilter', $block_val ) ) {
							foreach ( $block_val as $key => $val ) {
								if ( is_array( $val ) && ! empty( $val ) ) {

									if ( isset( $val['url'] ) && ( isset( $val['Id'] ) || isset( $val['id'] ) ) && ! empty( $val['url'] ) ) {
										$new_media                                      = Tpgb_Import_Images::media_import( $val );
										$block_data['attributes'][ $block_key ][ $key ] = $new_media;
									} elseif ( isset( $val['url'] ) && ! empty( $val['url'] ) && preg_match( '/\.(jpg|png|jpeg|gif|svg|webp|avif)$/', $val['url'] ) ) {
										$new_media                                      = Tpgb_Import_Images::media_import( $val );
										$block_data['attributes'][ $block_key ][ $key ] = $new_media;
									} else {
										foreach ( $val as $sub_key => $sub_val ) {
											if ( isset( $sub_val['url'] ) && ( isset( $sub_val['Id'] ) || isset( $sub_val['id'] ) ) && ! empty( $sub_val['url'] ) ) {
												$new_media = Tpgb_Import_Images::media_import( $sub_val );

												if ( is_array( $sub_val ) && is_array( $new_media ) ) {
													$block_data['attributes'][ $block_key ][ $key ][ $sub_key ] = array_merge( $sub_val, $new_media );
												} else {
													$block_data['attributes'][ $block_key ][ $key ][ $sub_key ] = $new_media;
												}
											} elseif ( isset( $sub_val['url'] ) && ! empty( $sub_val['url'] ) && preg_match( '/\.(jpg|png|jpeg|gif|svg|webp|avif)$/', $sub_val['url'] ) ) {
												$new_media = Tpgb_Import_Images::media_import( $sub_val );
												$block_data['attributes'][ $block_key ][ $key ][ $sub_key ] = $new_media;
											} elseif ( is_array( $sub_val ) && ! empty( $sub_val ) ) {
												foreach ( $sub_val as $sub_key1 => $sub_val1 ) {
													if ( isset( $sub_val1['url'] ) && ( isset( $sub_val1['Id'] ) || isset( $sub_val1['id'] ) ) && ! empty( $sub_val1['url'] ) ) {
														$new_media = Tpgb_Import_Images::media_import( $sub_val1 );
														if ( is_array( $sub_val1 ) && is_array( $new_media ) ) {
															$block_data['attributes'][ $block_key ][ $key ][ $sub_key ][ $sub_key1 ] = array_merge( $sub_val1, $new_media );
														} else {
															$block_data['attributes'][ $block_key ][ $key ][ $sub_key ][ $sub_key1 ] = $new_media;
														}
													} elseif ( isset( $sub_val1['url'] ) && ! empty( $sub_val1['url'] ) && preg_match( '/\.(jpg|png|jpeg|gif|svg|webp|avif)$/', $sub_val1['url'] ) ) {
														$new_media = Tpgb_Import_Images::media_import( $sub_val1 );
														$block_data['attributes'][ $block_key ][ $key ][ $sub_key ][ $sub_key1 ] = $new_media;
													}
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}

			return $block_data;
		}

		/**
		 * Recursively data.
		 *
		 * Accept any type of data and a callback function. The callback
		 * function runs recursively for each data and his child data.
		 *
		 * @since 1.1.0
		 * @access public
		 * @param array $data The data.
		 * @param mixed $callback The callback.
		 * @param array $args The args.
		 */
		public static function array_recursively_data( $data, $callback, $args = array() ) {
			if ( isset( $data['name'] ) ) {
				if ( ! empty( $data['innerBlocks'] ) ) {
					$data['innerBlocks'] = self::array_recursively_data( $data['innerBlocks'], $callback, $args );
				}

				return call_user_func( $callback, $data, $args );
			}

			foreach ( $data as $block_key => $block_value ) {
				$block_data = self::array_recursively_data( $data[ $block_key ], $callback, $args );

				if ( null === $block_data ) {
					continue;
				}

				$data[ $block_key ] = $block_data;
			}

			return $data;
		}

		/**
		 * Admin Enqueue Scripts
		 *
		 * @since 1.3.0.2
		 **/
		/**
		 * Admin enqueue css js.
		 *
		 * @param mixed $hook The hook.
		 */
		public function admin_enqueue_css_js( $hook ) {

			if ( ! did_action( 'wp_enqueue_media' ) ) {
				wp_enqueue_media();
			}
			wp_enqueue_style( 'tpgb-admin-css', TPGB_URL . 'assets/css/admin/tpgb-admin.css', array(), TPGB_VERSION, false );

			if ( 'edit-comments.php' === $hook ) {
				return;
			}

			wp_enqueue_script( 'tpgb-admin-js', TPGB_URL . 'assets/js/admin/tpgb-admin.js', array(), TPGB_VERSION, true );
			wp_localize_script(
				'tpgb-admin-js',
				'tpgb_admin',
				array(
					'ajax_url'   => esc_url( admin_url( 'admin-ajax.php' ) ),
					'tpgb_nonce' => wp_create_nonce( 'tpgb-addons' ),
				)
			);
		}

		/**
		 * Remove Cache Transient Data
		 *
		 * @since 1.4.8
		 */
		public function Tp_f_delete_transient() { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid,WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
			$result = array();
			check_ajax_referer( 'tpgb-addons', 'tpgb_nonce' );
			if ( ! current_user_can( 'edit_posts' ) ) {
				wp_die( 'You can not Permission.' );
			}
			global $wpdb;
			$transient      = array();
				$table_name = $wpdb->prefix . 'options';
				$query      = $wpdb->prepare( 'SELECT * FROM %s', $table_name );
				$data_bash  = $wpdb->get_results( $query ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery,WordPress.DB.DirectDatabaseQuery.NoCaching,WordPress.DB.PreparedSQL.InterpolatedNotPrepared,WordPress.DB.PreparedSQL.NotPrepared,WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				$block_name = ! empty( $_POST['blockName'] ) ? sanitize_text_field( wp_unslash( $_POST['blockName'] ) ) : '';

			if ( 'SocialFeed' === $block_name ) {
				$transient = array(
					// facebook.
						'Fb-Url-',
					'Fb-Time-',
					'Data-Fb-',
					// vimeo.
						'Vm-Url-',
					'Vm-Time-',
					'Data-Vm-',
					// Instagram basic.
						'IG-Url-',
					'IG-Profile-',
					'IG-Time-',
					'Data-IG-',
					// Instagram Graph.
						'IG-GP-Url-',
					'IG-GP-Time-',
					'IG-GP-Data-',
					'IG-GP-UserFeed-Url-',
					'IG-GP-UserFeed-Data-',
					'IG-GP-Hashtag-Url-',
					'IG-GP-HashtagID-data-',
					'IG-GP-HashtagData-Url-',
					'IG-GP-Hashtag-Data-',
					'IG-GP-story-Url-',
					'IG-GP-story-Data-',
					'IG-GP-Tag-Url-',
					'IG-GP-Tag-Data-',
					// Tweeter.
						'Tw-BaseUrl-',
					'Tw-Url-',
					'Tw-Time-',
					'Data-tw-',
					// Youtube.
						'Yt-user-',
					'Yt-user-Time-',
					'Data-Yt-user-',
					'Yt-Url-',
					'Yt-Time-',
					'Data-Yt-',
					'Yt-C-Url-',
					'Yt-c-Time-',
					'Data-c-Yt-',
					// loadmore.
						'SF-Loadmore-',
					// Performance.
						'SF-Performance-',
				);
			} elseif ( 'SocialReview' === $block_name ) {
				$transient = array(
					// Facebook.
						'Fb-R-Url-',
					'Fb-R-Time-',
					'Fb-R-Data-',
					// Google.
						'G-R-Url-',
					'G-R-Time-',
					'G-R-Data-',
					// loadmore.
						'SR-LoadMore-',
					// Performance.
						'SR-Performance-',
					// Beach.
						'Beach-Url-',
					'Beach-Time-',
					'Beach-Data-',
				);
			}
			foreach ( $data_bash as $first ) {
				if ( ! empty( $transient ) ) {
					foreach ( $transient as $second ) {
						$find_transient = ! empty( $first->option_name ) ? strpos( $first->option_name, $second ) : '';
						if ( ! empty( $find_transient ) ) {
							$wpdb->delete( $table_name, array( 'option_name' => $first->option_name ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery,WordPress.DB.DirectDatabaseQuery.NoCaching,WordPress.DB.PreparedSQL.InterpolatedNotPrepared,WordPress.DB.PreparedSQL.NotPrepared,WordPress.DB.SlowDBQuery.slow_db_query_tax_query
						}
					}
				}
			}

			$result['success']   = 1;
			$result['blockName'] = $block_name;
			echo wp_send_json( $result ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		/**
		 * Rank Math SEO filter For TOC List
		 *
		 * @param array $plugins TOC plugins.
		 */
		public function tpgb_rank_table_of_content( $plugins ) {
			$plugins['the-plus-addons-for-block-editor/the-plus-addons-for-block-editor.php'] = 'Nexter Blocks';
			return $plugins;
		}
	}

	Tpgb_Admin::get_instance();
}
