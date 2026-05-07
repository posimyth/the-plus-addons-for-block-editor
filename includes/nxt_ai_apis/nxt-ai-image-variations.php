<?php
/**
 * Nxt Ai Image Variations.
 *
 * @package ThePluginAddonsForBlockEditor
 */

// phpcs:disable WordPress.Files.FileName

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

/**
 * Nxt_ A I_ Image_ Variations.
 *
 * @since 1.0.0
 */
class Nxt_AI_Image_Variations {

	/**
	 * Generate variations.
	 *
	 * @param array $args The args.
	 * @return mixed The result.
	 */
	public function generate_variations( $args = array() ) {
		$args = wp_parse_args(
			$args,
			array(
				'image' => '',
				'n'     => 1,
				'size'  => '1024x1024',
			)
		);

		if ( empty( $args['image'] ) ) {
			return array(
				'success' => false,
				'message' => 'Image parameter is required',
			);
		}

		$n = absint( $args['n'] );
		if ( $n < 1 || $n > 10 ) {
			return array(
				'success' => false,
				'message' => 'Number of variations must be between 1 and 10',
			);
		}

		$settings_raw = Tp_Blocks_Helper::get_extra_option( 'nxtAiSettings' );
		$encrypted    = '';

		if ( is_string( $settings_raw ) ) {
			$encrypted = $settings_raw;
		} elseif ( is_array( $settings_raw ) ) {
			if ( isset( $settings_raw[0] ) && is_string( $settings_raw[0] ) ) {
				$encrypted = $settings_raw[0];
			} elseif ( count( $settings_raw ) === 1 ) {
				$encrypted = reset( $settings_raw );
			}
		}

		if ( is_string( $encrypted ) && ! empty( $encrypted ) ) {
			$decrypted = Tp_Blocks_Helper::tpgb_simple_decrypt( $encrypted, 'dy' );
			$settings  = json_decode( $decrypted, true );
		} else {
			$settings = array();
		}

		$api_key     = $settings['chatgptApiKey'] ?? '';
		$img_enabled = $settings['chatgptEnableImage'] ?? false;

		if ( true !== $img_enabled ) {
			return array(
				'success' => false,
				'message' => 'AI Image Generation is not enabled',
			);
		}

		if ( empty( $api_key ) ) {
			return array(
				'success' => false,
				'message' => 'OpenAI API key is not configured',
			);
		}

		try {
			$img_data = $this->process_to_png( $args['image'] );

			if ( is_wp_error( $img_data ) ) {
				return array(
					'success' => false,
					'message' => $img_data->get_error_message(),
				);
			}

			$api_result = $this->send_request( $api_key, $img_data, $n, $args['size'] );

			if ( is_wp_error( $api_result ) ) {
				return array(
					'success' => false,
					'message' => $api_result->get_error_message(),
				);
			}

			return array(
				'success'      => true,
				'data'         => $api_result['data'],
				'created'      => $api_result['created'],
				'total_tokens' => $api_result['total_tokens'],
			);
		} catch ( Exception $e ) {
			return array(
				'success' => false,
				'message' => $e->getMessage(),
			);
		}
	}

	/**
	 * Process to png.
	 *
	 * @param mixed $image The image.
	 * @return mixed The result.
	 */
	private function process_to_png( $image ) {
		$img_resource = $this->load_resource( $image );

		if ( is_wp_error( $img_resource ) ) {
			return $img_resource;
		}

		$width  = imagesx( $img_resource );
		$height = imagesy( $img_resource );

		if ( $width !== $height ) {
			$square = $this->make_square( $img_resource, $width, $height );
			imagedestroy( $img_resource );
			$img_resource = $square;
		}

		ob_start();
		imagepng( $img_resource, null, 9 );
		$png_data = ob_get_clean();
		imagedestroy( $img_resource );

		if ( strlen( $png_data ) > 4 * 1024 * 1024 ) {
			return new WP_Error( 'size_limit', 'Image must be less than 4MB' );
		}

		return $png_data;
	}

	/**
	 * Load resource.
	 *
	 * @param mixed $image The image.
	 * @return mixed The result.
	 */
	private function load_resource( $image ) {
		$img_data = null;

		if ( strpos( $image, 'data:image' ) === 0 ) {
			preg_match( '/data:image\/(\w+);base64,(.*)/', $image, $matches );

			if ( empty( $matches[2] ) ) {
				return new WP_Error( 'invalid_base64', 'Invalid base64 image data' );
			}

			$img_data = base64_decode( $matches[2] ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode,WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode
		} elseif ( filter_var( $image, FILTER_VALIDATE_URL ) ) {
			$img_data = @file_get_contents( $image ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents, WordPress.PHP.NoSilencedErrors.Discouraged

			if ( false === $img_data ) {
				return new WP_Error( 'fetch_failed', 'Failed to fetch image from URL' );
			}
		} else {
			return new WP_Error( 'invalid_format', 'Invalid image format. Must be base64 data URL or valid URL' );
		}

		$resource = @imagecreatefromstring( $img_data ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged

		if ( false === $resource ) {
			return new WP_Error( 'invalid_image', 'Invalid image data' );
		}

		return $resource;
	}

	/**
	 * Make square.
	 *
	 * @param mixed $resource The resource.
	 * @param int   $width The width.
	 * @param int   $height The height.
	 * @return mixed The result.
	 */
	private function make_square( $resource, $width, $height ) { // phpcs:ignore Universal.NamingConventions.NoReservedKeywordParameterNames.stringFound,Universal.NamingConventions.NoReservedKeywordParameterNames.arrayFound, Universal.NamingConventions.NoReservedKeywordParameterNames.resourceFound
		$size = min( $width, $height );
		$x    = ( $width - $size ) / 2;
		$y    = ( $height - $size ) / 2;

		$square = imagecreatetruecolor( $size, $size );

		imagealphablending( $square, false );
		imagesavealpha( $square, true );

		imagecopyresampled( $square, $resource, 0, 0, $x, $y, $size, $size, $size, $size );

		return $square;
	}

	/**
	 * Send request.
	 *
	 * @param mixed $api_key The api key.
	 * @param array $img_data The img data.
	 * @param mixed $n The n.
	 * @param int   $size The size.
	 * @return mixed The result.
	 */
	private function send_request( $api_key, $img_data, $n, $size ) {
		$boundary = wp_generate_password( 24, false );
		$eol      = "\r\n";

		$body = '';

		$body .= '--' . $boundary . $eol;
		$body .= 'Content-Disposition: form-data; name="image"; filename="image.png"' . $eol;
		$body .= 'Content-Type: image/png' . $eol . $eol;
		$body .= $img_data . $eol;

		$body .= '--' . $boundary . $eol;
		$body .= 'Content-Disposition: form-data; name="n"' . $eol . $eol;
		$body .= $n . $eol;

		$body .= '--' . $boundary . $eol;
		$body .= 'Content-Disposition: form-data; name="size"' . $eol . $eol;
		$body .= $size . $eol;

		$body .= '--' . $boundary . '--' . $eol;

		$response = wp_remote_post(
			'https://api.openai.com/v1/images/variations',
			array(
				'headers'   => array(
					'Authorization' => 'Bearer ' . $api_key,
					'Content-Type'  => 'multipart/form-data; boundary=' . $boundary,
				),
				'body'      => $body,
				'timeout'   => 60,
				'sslverify' => true,
			)
		);

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$code = wp_remote_retrieve_response_code( $response );
		$body = wp_remote_retrieve_body( $response );
		$data = json_decode( $body, true );

		if ( 200 !== $code ) {
			$error_msg = isset( $data['error']['message'] ) ? $data['error']['message'] : 'Unknown error occurred';
			return new WP_Error( 'api_error', $error_msg );
		}

		return array(
			'data'         => $data['data'] ?? array(),
			'created'      => $data['created'] ?? time(),
			'total_tokens' => null,
		);
	}

	/**
	 * Nxt ai variations.
	 *
	 * @param array $params The params.
	 * @return mixed The result.
	 */
	function nxt_ai_variations( $params ) { // phpcs:ignore Squiz.Scope.MethodScope.Missing
		$variations = self::get_instance();

		$args = array(
			'image' => $params->get_param( 'image' ),
			'n'     => ( intval( $params->get_param( 'n' ) ) ) ? intval( $params->get_param( 'n' ) ) : 1,
			'size'  => ( $params->get_param( 'size' ) ) ? $params->get_param( 'size' ) : '1024x1024',
		);

		return $variations->generate_variations( $args );
	}

	/**
	 * Get instance.
	 *
	 * @return mixed The result.
	 */
	public static function get_instance() {
		static $instance = null;
		if ( null === $instance ) {
			$instance = new self();
		}
		return $instance;
	}
}
