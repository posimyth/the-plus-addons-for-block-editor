<?php
/**
 * Nexter Extension Deactivate Survey
 *
 * @since 4.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Nxt_Block_Whats_New' ) ) {

	class Nxt_Block_Whats_New {

        /**
		 * Member Variable
		 */
		private static $instance;

        const FEED_URL = 'https://nexterwp.com/topic/product-announcements/blocks/feed';
        const TRANSIENT_ITEM = 'nxt_block_latest_whats_new_item';
        const OPTION_NAME      = 'nxt_menu_notice_count';

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
		 *  Constructor
		 */
		public function __construct() {
            $this->nxt_block_initialize_notice_data();
            if ( is_admin() ) {
                // add_action( 'admin_init', [ $this, 'nxt_block_check_and_store_latest_item' ] );
                // add_action( 'admin_init', [ $this, 'nxt_block_cache_whats_new_feed' ] );
            }
            add_action( 'wp_ajax_nxt_block_fetch_whats_new', [ $this, 'nxt_block_fetch_whats_new_data' ] );
        }

        /**
         * Initialize option with default values if not exists
         */
        private function nxt_block_initialize_notice_data() {
            $data = get_option( self::OPTION_NAME );
            if ( ! is_array( $data ) ) {
                $data = [
                    'menu_notice_count' => 0,
                    'notice_flag'       => 1,
                ];
                update_option( self::OPTION_NAME, $data );
            }
        }

        public function nxt_block_cache_whats_new_feed() {

            if ( empty( $_GET['page'] ) || $_GET['page'] !== 'nexter_welcome_page' ) {
                return;
            }

            if ( false === get_transient( 'nxtext_cached_feed_data' ) ) {

                add_filter( 'wp_feed_options', function ( $options ) {
                    if ( is_object( $options ) ) {
                        $options = (array) $options;
                    }
                    $options['timeout'] = 15; 
                    return $options;
                });

                include_once ABSPATH . WPINC . '/feed.php';
                $rss = fetch_feed( self::FEED_URL );

                // Remove the filter after use
                remove_all_filters( 'wp_feed_options' );

                if ( ! is_wp_error( $rss ) ) {
                    $max_items = $rss->get_item_quantity( 50 );
                    $items = $rss->get_items( 0, $max_items );

                    $results = [];

                    foreach ( $items as $item ) {
                        $namespace = $item->get_item_tags( '', 'featured_image_rss' );
                        $featured_image = ! empty( $namespace[0]['data'] ) ? esc_url( $namespace[0]['data'] ) : '';

                        $excerpt_tag = $item->get_item_tags( '', 'post_excerpt' );
                        $post_excerpt = ! empty( $excerpt_tag[0]['data'] ) ? wp_strip_all_tags( $excerpt_tag[0]['data'] ) : '';

                        $results[] = [
                            'title' => $item->get_title(),
                            'link'  => $item->get_permalink(),
                            'date'  => $item->get_date( get_option( 'date_format' ) ),
                            'desc'  => wp_trim_words( $post_excerpt, 25 ),
                            'image' => $featured_image,
                        ];
                    }

                    set_transient( 'nxtext_cached_feed_data', $results, DAY_IN_SECONDS );
                }
            }
        }

        public function nxt_block_fetch_whats_new_data() {
            check_ajax_referer('tpgb-dash-ajax-nonce', 'nexter_nonce' );

            if ( ! current_user_can( 'manage_options' ) ) {
                wp_send_json_error();
            }

            $offset = isset($_POST['offset']) ? intval($_POST['offset']) : 0;
            $limit  = 5;

            // $cached = get_transient( 'nxtext_cached_feed_data' );
            // if ( ! $cached || ! is_array( $cached ) ) {
            //     wp_send_json_error( 'No cached feed data found.' );
            // }
            
            $cached = [
                [
                    "title" => "Introducing Blocks Presets, Smooth Scroll Effects & More in Nexter Blocks.",
                    "link" => "https://nexterwp.com/blog/introducing-blocks-presets-smooth-scroll-effects-more-in-nexter-blocks/",
                    "date" => "July 28, 2025",
                    "desc" => "We’ve got exciting news for you! The latest Nexter Blocks v4.5.0 update is packed with enhancements that’ll save you time, make your workflow smoother, and…",
                    "image" => "https://nexterwp.com/wp-content/uploads/2025/07/Smooth-Scroll-scaled.png"
                ],
                [
                    "title" => "June 2025 Monthly Updates: New Dynamic Repeater Block, Glassmorphism Effects, Introducing Academy & More",
                    "link" => "https://nexterwp.com/blog/june-2025-monthly-updates/",
                    "date" => "July 14, 2025",
                    "desc" => "This month, we’ve rolled out powerful new features and enhancements in Nexter Blocks to help you build more dynamic, visually stunning websites faster and with more control.…",
                    "image" => "https://nexterwp.com/wp-content/uploads/2025/07/June-2025-Monthly-Updates-New-Dynamic-Repeater-Block-Glassmorphism-Effects-Introducing-Academy-More.png"
                ],
                [
                    "title" => "Introducing Glassmorphism(Liquid Glass Effect) & Repeater Block in Nexter Block (Support ACF & JetEngine) WordPress",
                    "link" => "https://nexterwp.com/blog/introducing-glassmorphism-repeater-block-in-nexter-block/",
                    "date" => "June 24, 2025",
                    "desc" => "We’ve got some exciting news to share with you: two new updates just landed in Nexter Blocks! If you’ve been wanting more flexibility and style…",
                    "image" => "https://nexterwp.com/wp-content/uploads/2025/06/Introducing-Glassmorphism-Repeater-Block-in-Nexter-Block.png"
                ],
                [
                    "title" => "May 2025 Monthly Updates: Nexter v4.2.0 Major Upgrade, Global Styles Revamp, Form Layout Controls & More",
                    "link" => "https://nexterwp.com/blog/may-2025-monthly-updates/",
                    "date" => "June 11, 2025",
                    "desc" => "This month, we've rolled out major new updates to Nexter Extension and Nexter Blocks that will completely level up your WordPress workflow, giving you more control, speed, and a smoother user…",
                    "image" => "https://nexterwp.com/wp-content/uploads/2025/06/Monthly-updates-Nexter.jpg"
                ],
                [
                    "title" => "Introducing the New Nexter Website Setup, Global Styles & More",
                    "link" => "https://nexterwp.com/blog/introducing-the-new-nexter-website-setup-global-styles-more/",
                    "date" => "May 19, 2025",
                    "desc" => "We’ve got some exciting updates to share with you in Nexter Blocks, and trust us, you’re going to love them. We’ve been fine-tuning things to…",
                    "image" => "https://nexterwp.com/wp-content/uploads/2025/05/image-7-1.png"
                ]
            ];

            $cached = array_slice( $cached, $offset, $limit );

            wp_send_json_success( $cached );
        }

        /**
         * Runs weekly (admin-triggered): Checks and updates the latest feed item.
         * Hooked via `admin_init`, but runs only once per week via transient.
         */
        public function nxt_block_check_and_store_latest_item() {

            // Avoid running if we already have fresh data
            if ( get_transient( self::TRANSIENT_ITEM ) ) {
                return;
            }
            // Set custom timeout to prevent cURL error 28
            add_filter( 'wp_feed_options', function ( $options ) {
                if ( is_object( $options ) ) {
                    $options = (array) $options;
                }
                $options['timeout'] = 10;
                return $options;
            });

            include_once ABSPATH . WPINC . '/feed.php';
            $rss = fetch_feed( self::FEED_URL );

            remove_all_filters( 'wp_feed_options' );
            // If feed failed, exit silently or optionally log
            if ( is_wp_error( $rss ) ) {
                error_log( 'RSS Feed fetch failed: ' . $rss->get_error_message() );
                return;
            }

            $items = $rss->get_items( 0, 1 );
            if ( empty( $items ) ) {
                return;
            }

            $new_item  = $items[0];
            $new_title = $new_item->get_title();

            $stored = get_transient( self::TRANSIENT_ITEM );
            if ( empty( $stored ) || ( isset( $stored['title'] ) && $stored['title'] !== $new_title ) ) {
                $latest = [
                    'title' => $new_title,
                    'link'  => $new_item->get_permalink(),
                    'date'  => $new_item->get_date( 'U' ),
                ];
                set_transient( self::TRANSIENT_ITEM, $latest, WEEK_IN_SECONDS );

                // Update internal state
                $data = get_option( self::OPTION_NAME, [] );
                if ( ! is_array( $data ) ) {
                    $data = [
                        'menu_notice_count' => 0,
                        'notice_flag'       => 1,
                    ];
                }
                $data['notice_flag'] = isset( $data['notice_flag'] ) ? (int) $data['notice_flag'] + 1 : 1;
                update_option( self::OPTION_NAME, $data );
            }
        }

    }

    Nxt_Block_Whats_New::get_instance();
}
