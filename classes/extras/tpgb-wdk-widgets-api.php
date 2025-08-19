<?php
/**
 * The file store Database Default Entry
 *
 * @link       https://posimyth.com/
 * @since      4.0.7
 *
 * @package    TPGBP
 */

/**Exit if accessed directly.*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * nxt_Wdk_Widget_Api
 *
 * @since 4.0.7
 */
class nxt_Wdk_Widget_Api {

	/**
	 * WDesignKit site URL
	 *
	 * @var staring $wdk_site
	 */
	public $wdk_site = 'https://wdesignkit.com/api/wp/';

	/**
	 * Member Variable
	 *
	 * @var instance
	 */
	private static $instance;

	/**
	 *  Initiator
	 *
	 * @since 4.0.7
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Define the core functionality of the plugin.
	 *
	 * @since 4.0.7
	 */
	public function __construct() {
		add_filter( 'nxt_wdk_widget_ajax_call', array( $this, 'tp_nxt_wdkit_widget_ajax_call' ), 10 );
	}

	public function tp_nxt_wdkit_widget_ajax_call( $type ) {

		if ( ! is_user_logged_in() || ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'content' => __( 'Insufficient permissions.', 'the-plus-addons-for-block-editor' ) ) );
		}

		if ( ! $type ) {
			$this->wdkit_error_msg( __( 'Something went wrong.', 'the-plus-addons-for-block-editor' ) );
		}
        
        $response = array();

		switch ( $type ) {
			case 'nxt_wdk_get_widget_ajax':
				$response = $this->nxt_wdk_get_widget_ajax();
				break;
			case 'wdk_update_widget':
				$response = $this->wdk_update_widget();
				break;
		}
		return $response;
	}

	/**
	 * Perform API Call for WDesignkit Widget List
	 *
	 * @since 4.0.7
	 *
	 * @param array  $data An array of request data to be sent with the API call.
	 * @param string $name The name or identifier for the API request.
	 *
	 * @return mixed The API response or result of the call.
	 */
	public function nxt_wdesign_api_call( $data, $name ) {

		$url = $this->wdk_site;

		$args = array(
			'method'  => 'POST',
			'body'    => $data,
			'timeout' => 100,
		);

		$response = wp_remote_post( $url . $name, $args );

		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();

			/* Translators: %s is a placeholder for the error message */
			$error_message = printf( esc_html__( 'API request error: %s', 'the-plus-addons-for-block-editor' ), esc_html( $error_message ) );

			return array(
				'massage' => $error_message,
				'success' => false,
			);
		}

		$status_code = wp_remote_retrieve_response_code( $response );
		if ( 200 === $status_code ) {

			return array(
				'data'    => json_decode( wp_remote_retrieve_body( $response ), true ),
				'massage' => esc_html__( 'Success', 'the-plus-addons-for-block-editor' ),
				'status'  => $status_code,
				'success' => true,
			);

		}
		    $error_message = printf( 'Server error: %d', esc_html( $status_code ) );

		if ( isset( $error_data->message ) ) {
			$error_message .= ' (' . $error_data->message . ')';
		}

			return array(
				'massage' => $error_message,
				'status'  => $status_code,
				'success' => false,
			);


	}

	/**
	 * Get WDesignkit Widget List
	 *
	 * @since 4.0.7
	 */
	public function nxt_wdk_get_widget_ajax() {

		$widget_array = array();

		$server_widgets  = $this->nxt_wdk_server_widget();

		$local_widgets = [];
		if ( defined( 'WDKIT_VERSION' ) ) {
			$local_widgets = $this->nxt_wdk_local_widget();
		}

		$server_w_unique = array_column( $local_widgets, 'widget_id' );

		foreach ( $server_widgets as $key => $value ) {

			$widget_id = ! empty( $value['w_unique'] ) ? $value['w_unique'] : '';
			$index     = array_search( $widget_id, $server_w_unique, true );

			$widget_array[ $key ]['title']        = $value['title'];
			$widget_array[ $key ]['live_demo']    = $value['demo_url'];
			$widget_array[ $key ]['free_pro']     = $value['free_pro'];
			$widget_array[ $key ]['id']           = $value['id'];
			$widget_array[ $key ]['builder']      = $value['builder'];
			$widget_array[ $key ]['is_activated'] = $value['is_activated'];
			$widget_array[ $key ]['user_id']      = $value['user_id'];
			$widget_array[ $key ]['w_unique']     = $value['w_unique'];

			if ( isset( $index ) && $index !== false ) {
				$w_type                         = $local_widgets[ $index ]['publish_type'];
				$widget_array[ $key ]['w_type'] = $w_type;
			}
		}

		return $widget_array;
	}

	/**
	 * Get WDesignkit Local Widget List
	 *
	 * @since 4.0.7
	 */
	public function nxt_wdk_local_widget() {
		$local_array = array();
        if( defined('WDKIT_VERSION') && defined('WDKIT_BUILDER_PATH') ){
            $gutenberg_dir = WDKIT_BUILDER_PATH . '/gutenberg';

            if ( ! empty( $gutenberg_dir ) && is_dir( $gutenberg_dir ) ) {
                $gutenberg_list = scandir( $gutenberg_dir );
                $gutenberg_list = array_diff( $gutenberg_list, array( '.', '..' ) );

                $gutenberg_list = array_values( $gutenberg_list );

                foreach ( $gutenberg_list as $key => $value ) {

                    if ( file_exists( "{$gutenberg_dir}/{$value}" ) && is_dir( "{$gutenberg_dir}/{$value}" ) ) {
                        $sub_dir = scandir( "{$gutenberg_dir}/{$value}" );
                        $sub     = array_diff( $sub_dir, array( '.', '..' ) );
                        $sub     = array_values( $sub );

                        foreach ( $sub as $idx => $sub_dir_value ) {

                            $file      = new SplFileInfo( $sub_dir_value );
                            $check_ext = $file->getExtension();
                            $ext       = pathinfo( $sub_dir_value, PATHINFO_EXTENSION );

                            if ( 'json' === $ext ) {
                                $widget1     = "{$gutenberg_dir}/{$value}/{$sub_dir_value}";
                                $filedata    = wp_json_file_decode( $widget1 );
                                $decode_data = json_decode( wp_json_encode( $filedata ), true );
                                array_push( $local_array, $decode_data['widget_data']['widgetdata'] );
                            }
                        }
                    }
                }
            }
        }
		return $local_array;
	}

	/**
	 * Get WDesignkit serve Widget List
	 *
	 * @since 4.0.7
	 */
	public function nxt_wdk_server_widget() {

		$array_data = array(
			'CurrentPage' => isset( $_POST['page'] ) ? (int) $_POST['page'] : 1,
			'builder'     => isset( $_POST['buildertype'] ) ? wp_unslash( $_POST['buildertype'] ) : '["gutenberg"]',
			'category'    => isset( $_POST['category'] ) ? sanitize_text_field( wp_unslash( $_POST['category'] ) ) : '',
			'ParPage'     => isset( $_POST['perpage'] ) ? (int) $_POST['perpage'] : 1000,
			'search'      => isset( $_POST['search'] ) ? sanitize_text_field( wp_unslash( $_POST['search'] ) ) : '',
			'free_pro'    => isset( $_POST['free_pro'] ) ? sanitize_text_field( wp_unslash( $_POST['free_pro'] ) ) : '',
		);

		$response = $this->nxt_wdesign_api_call( $array_data, 'browse_widget' );

		if ( ! empty( $response['success'] ) && ! empty( $response['data']['data']['widgets'] ) ) {
			$widgets = $response['data']['data']['widgets'];

			return $widgets;
		} else {
			return array();
		}
	}

	/**
	 * Get WDesignkit serve Widget List
	 *
	 * @since 4.0.7
	 */
	public function wdk_update_widget() {
		$array_data = array(
			'w_name'     => isset( $_POST['w_name'] ) ? sanitize_text_field( wp_unslash( $_POST['w_name'] ) ) : '',
			'w_unique' 	 => isset( $_POST['w_unique'] ) ? sanitize_text_field( wp_unslash( $_POST['w_unique'] ) ) : '',
			'p_type'     => isset( $_POST['p_type'] ) ? sanitize_text_field( wp_unslash( $_POST['p_type'] ) ) : '',
		);
        
        if( defined('WDKIT_VERSION') && defined('WDKIT_BUILDER_PATH') ){
            $downlod_path = WDKIT_BUILDER_PATH . "/gutenberg/";
            $file_name    = str_replace( ' ', '_', $array_data['w_name'] );
            $folder_name  = str_replace( ' ', '-', $array_data['w_name'] );
    
            $tmp_file     = "$downlod_path{$folder_name}_{$array_data['w_unique']}/{$file_name}_{$array_data['w_unique']}.json";
    
            $json_data = wp_json_file_decode( $tmp_file, true );
    
            $json_data->widget_data->widgetdata->publish_type = $array_data['p_type'];
    
            include_once ABSPATH . 'wp-admin/includes/file.php';
            \WP_Filesystem();
            global $wp_filesystem;
    
            if ( ! empty( $json_data ) ) {
                $wp_filesystem->put_contents( $tmp_file, json_encode($json_data) );
    
                $responce = array(
                    'message'     => esc_html__( 'Update Saved Successfully', 'the-plus-addons-for-block-editor' ),
                    'description' => esc_html__( 'Success! Update Saved', 'the-plus-addons-for-block-editor' ),
                    'success'     => true,
                );
    
                return $responce;
            }
        }

	}
}

nxt_Wdk_Widget_Api::get_instance();