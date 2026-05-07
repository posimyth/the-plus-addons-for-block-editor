<?php
/**
 * Nxt Ai Image Expand.
 *
 * @package ThePluginAddonsForBlockEditor
 */

// phpcs:disable WordPress.Files.FileName

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

/**
 * Nxt_ A I_ Image_ Expand.
 *
 * @since 1.0.0
 */
class Nxt_AI_Image_Expand {

	/**
	 * Expand image.
	 *
	 * @param array $args The args.
	 * @return mixed The result.
	 */
	public function expand_image( $args = array() ) {
		$args = wp_parse_args(
			$args,
			array(
				'image_url' => '',
				'width'     => 512,
				'height'    => 512,
				'offset_x'  => 0,
				'offset_y'  => 0,
				'prompt'    => '',
			)
		);

		$image_url     = $args['image_url'];
		$width         = absint( $args['width'] );
		$height        = absint( $args['height'] );
		$offset_x      = intval( $args['offset_x'] );
		$offset_y      = intval( $args['offset_y'] );
		$custom_prompt = sanitize_textarea_field( $args['prompt'] );

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
		$is_enabled  = ( true === $img_enabled || 1 === $img_enabled );

		if ( empty( $image_url ) ) {
			return array(
				'success' => false,
				'message' => 'Image URL is required',
			);
		}

		if ( empty( $api_key ) ) {
			return array(
				'success' => false,
				'message' => 'API key not configured',
			);
		}

		try {
			$img_data = $this->load_image_data( $image_url );
			if ( is_wp_error( $img_data ) ) {
				return array(
					'success' => false,
					'message' => $img_data->get_error_message(),
				);
			}

			$original = imagecreatefromstring( $img_data );
			if ( ! $original ) {
				return array(
					'success' => false,
					'message' => 'Invalid image format',
				);
			}

			$orig_w = imagesx( $original );
			$orig_h = imagesy( $original );

			$canvas_size = $this->calculate_canvas_size( $width, $height );

			$result = $this->create_canvas_and_mask( $original, $canvas_size, $width, $height, $offset_x, $offset_y );
			imagedestroy( $original );

			if ( is_wp_error( $result ) ) {
				return array(
					'success' => false,
					'message' => $result->get_error_message(),
				);
			}

			$prompt = ! empty( $custom_prompt ) ? $custom_prompt : $this->generate_expansion_prompt();

			$api_result = $this->send_expansion_request( $api_key, $result['canvas_data'], $result['mask_data'], $prompt, $canvas_size );
			if ( is_wp_error( $api_result ) ) {
				return array(
					'success' => false,
					'message' => $api_result->get_error_message(),
				);
			}

			$final = $this->process_and_crop_result( $api_result['url'], $canvas_size, $width, $height );
			if ( is_wp_error( $final ) ) {
				return array(
					'success' => false,
					'message' => $final->get_error_message(),
				);
			}

			$total_tokens = 'undefined';
			if ( $is_enabled && isset( $api_result['usage']['total_tokens'] ) ) {
				$total_tokens = $api_result['usage']['total_tokens'];
			}

			return array(
				'success'         => true,
				'message'         => 'Image expanded successfully',
				'url'             => $final,
				'dimensions'      => array(
					'original'  => array(
						'width'  => $orig_w,
						'height' => $orig_h,
					),
					'container' => array(
						'width'  => $width,
						'height' => $height,
					),
					'canvas'    => array(
						'width'  => $canvas_size,
						'height' => $canvas_size,
					),
					'final'     => array(
						'width'  => $width,
						'height' => $height,
					),
				),
				'total_tokens'    => $total_tokens,
				'chatgpt_enabled' => $is_enabled,
			);
		} catch ( Exception $e ) {
			return array(
				'success' => false,
				'message' => $e->getMessage(),
			);
		}
	}

	/**
	 * Load image data.
	 *
	 * @param mixed $img_url The img url.
	 * @return mixed The result.
	 */
	private function load_image_data( $img_url ) {
		if ( strpos( $img_url, 'data:image' ) === 0 ) {
			$parts = explode( ',', $img_url );
			return base64_decode( $parts[1] ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode,WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode
		}

		$response = wp_remote_get(
			$img_url,
			array(
				'timeout'    => 60,
				'sslverify'  => false,
				'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
			)
		);

		if ( is_wp_error( $response ) ) {
			return new WP_Error( 'download_failed', 'Download failed: ' . $response->get_error_message() );
		}

		$code = wp_remote_retrieve_response_code( $response );
		if ( 200 !== $code ) {
			return new WP_Error( 'http_error', 'HTTP Error: ' . $code );
		}

		$data = wp_remote_retrieve_body( $response );
		if ( empty( $data ) ) {
			return new WP_Error( 'empty_data', 'Empty image data received' );
		}

		return $data;
	}

	/**
	 * Calculate canvas size.
	 *
	 * @param int $width The width.
	 * @param int $height The height.
	 * @return mixed The result.
	 */
	private function calculate_canvas_size( $width, $height ) {
		$max = max( $width, $height );
		if ( $max <= 256 ) {
			return 256;
		}
		if ( $max <= 512 ) {
			return 512;
		}
		return 1024;
	}

	/**
	 * Create canvas and mask.
	 *
	 * @param mixed $original The original.
	 * @param int   $canvas_size The canvas size.
	 * @param mixed $cont_w The cont w.
	 * @param mixed $cont_h The cont h.
	 * @param mixed $off_x The off x.
	 * @param mixed $off_y The off y.
	 * @return mixed The result.
	 */
	private function create_canvas_and_mask( $original, $canvas_size, $cont_w, $cont_h, $off_x, $off_y ) {
		$orig_w = imagesx( $original );
		$orig_h = imagesy( $original );

		$placed_ratio = 0.5;
		$placed_w     = intval( $orig_w * $placed_ratio );
		$placed_h     = intval( $orig_h * $placed_ratio );

		if ( $placed_w > $canvas_size * 0.8 || $placed_h > $canvas_size * 0.8 ) {
			$scale    = min( ( $canvas_size * 0.8 ) / $placed_w, ( $canvas_size * 0.8 ) / $placed_h );
			$placed_w = intval( $placed_w * $scale );
			$placed_h = intval( $placed_h * $scale );
		}

		$canvas = imagecreatetruecolor( $canvas_size, $canvas_size );
		imagealphablending( $canvas, false );
		imagesavealpha( $canvas, true );

		$trans = imagecolorallocatealpha( $canvas, 0, 0, 0, 127 );
		imagefill( $canvas, 0, 0, $trans );
		imagealphablending( $canvas, true );

		$center_x = intval( ( $canvas_size - $placed_w ) / 2 );
		$center_y = intval( ( $canvas_size - $placed_h ) / 2 );

		$scale_factor = $canvas_size / max( $cont_w, $cont_h );
		$canvas_off_x = $center_x + intval( $off_x * $scale_factor );
		$canvas_off_y = $center_y + intval( $off_y * $scale_factor );

		$canvas_off_x = max( 0, min( $canvas_size - $placed_w, $canvas_off_x ) );
		$canvas_off_y = max( 0, min( $canvas_size - $placed_h, $canvas_off_y ) );

		imagecopyresampled( $canvas, $original, $canvas_off_x, $canvas_off_y, 0, 0, $placed_w, $placed_h, $orig_w, $orig_h );
		imagealphablending( $canvas, false );
		imagesavealpha( $canvas, true );

		$mask = imagecreatetruecolor( $canvas_size, $canvas_size );
		imagealphablending( $mask, false );
		imagesavealpha( $mask, true );

		$trans_mask = imagecolorallocatealpha( $mask, 0, 0, 0, 127 );
		imagefill( $mask, 0, 0, $trans_mask );
		imagealphablending( $mask, true );

		$white = imagecolorallocate( $mask, 255, 255, 255 );
		$pad   = 5;
		imagefilledrectangle( $mask, $canvas_off_x + $pad, $canvas_off_y + $pad, $canvas_off_x + $placed_w - $pad, $canvas_off_y + $placed_h - $pad, $white );
		imagealphablending( $mask, false );
		imagesavealpha( $mask, true );

		ob_start();
		imagepng( $canvas );
		$canvas_data = ob_get_clean();

		ob_start();
		imagepng( $mask );
		$mask_data = ob_get_clean();

		imagedestroy( $canvas );
		imagedestroy( $mask );

		return array(
			'canvas_data' => $canvas_data,
			'mask_data'   => $mask_data,
		);
	}

	/**
	 * Send expansion request.
	 *
	 * @param mixed $api_key The api key.
	 * @param array $canvas_data The canvas data.
	 * @param array $mask_data The mask data.
	 * @param mixed $prompt The prompt.
	 * @param int   $canvas_size The canvas size.
	 * @return mixed The result.
	 */
	private function send_expansion_request( $api_key, $canvas_data, $mask_data, $prompt, $canvas_size ) {
		$url      = 'https://api.openai.com/v1/images/edits';
		$boundary = wp_generate_password( 24, false );

		$body  = '';
		$body .= '--' . $boundary . "\r\n";
		$body .= 'Content-Disposition: form-data; name="image"; filename="image.png"' . "\r\n";
		$body .= 'Content-Type: image/png' . "\r\n\r\n";
		$body .= $canvas_data . "\r\n";

		$body .= '--' . $boundary . "\r\n";
		$body .= 'Content-Disposition: form-data; name="mask"; filename="mask.png"' . "\r\n";
		$body .= 'Content-Type: image/png' . "\r\n\r\n";
		$body .= $mask_data . "\r\n";

		$body .= '--' . $boundary . "\r\n";
		$body .= 'Content-Disposition: form-data; name="prompt"' . "\r\n\r\n";
		$body .= $prompt . "\r\n";

		$body .= '--' . $boundary . "\r\n";
		$body .= 'Content-Disposition: form-data; name="n"' . "\r\n\r\n";
		$body .= '1' . "\r\n";

		$body .= '--' . $boundary . "\r\n";
		$body .= 'Content-Disposition: form-data; name="size"' . "\r\n\r\n";
		$body .= $canvas_size . 'x' . $canvas_size . "\r\n";

		$body .= '--' . $boundary . '--';

		$response = wp_remote_post(
			$url,
			array(
				'headers' => array(
					'Authorization' => 'Bearer ' . $api_key,
					'Content-Type'  => 'multipart/form-data; boundary=' . $boundary,
				),
				'body'    => $body,
				'timeout' => 120,
			)
		);

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$code = wp_remote_retrieve_response_code( $response );
		if ( 200 !== $code ) {
			$error_data = json_decode( wp_remote_retrieve_body( $response ), true );
			$error_msg  = $error_data['error']['message'] ?? 'OpenAI API error';
			return new WP_Error( 'api_error', $error_msg );
		}

		$data = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( empty( $data['data'][0]['url'] ) ) {
			return new WP_Error( 'invalid_response', 'No image URL in response' );
		}

		return array(
			'url'   => $data['data'][0]['url'],
			'usage' => isset( $data['usage'] ) ? $data['usage'] : null,
		);
	}

	/**
	 * Process and crop result.
	 *
	 * @param mixed $expanded_url The expanded url.
	 * @param int   $canvas_size The canvas size.
	 * @param mixed $cont_w The cont w.
	 * @param mixed $cont_h The cont h.
	 * @return mixed The result.
	 */
	private function process_and_crop_result( $expanded_url, $canvas_size, $cont_w, $cont_h ) {
		$response = wp_remote_get( $expanded_url, array( 'timeout' => 60 ) );

		if ( is_wp_error( $response ) ) {
			return new WP_Error( 'download_failed', 'Failed to download expanded image' );
		}

		$img_data = wp_remote_retrieve_body( $response );
		$expanded = imagecreatefromstring( $img_data );

		if ( ! $expanded ) {
			return new WP_Error( 'process_failed', 'Failed to process expanded image' );
		}

		$aspect = $cont_w / $cont_h;

		if ( $aspect > 1 ) {
			$crop_w = $canvas_size;
			$crop_h = intval( $canvas_size / $aspect );
			$crop_x = 0;
			$crop_y = intval( ( $canvas_size - $crop_h ) / 2 );
		} else {
			$crop_h = $canvas_size;
			$crop_w = intval( $canvas_size * $aspect );
			$crop_x = intval( ( $canvas_size - $crop_w ) / 2 );
			$crop_y = 0;
		}

		$final = imagecreatetruecolor( $cont_w, $cont_h );
		imagealphablending( $final, false );
		imagesavealpha( $final, true );

		imagecopyresampled( $final, $expanded, 0, 0, $crop_x, $crop_y, $cont_w, $cont_h, $crop_w, $crop_h );
		imagealphablending( $final, false );
		imagesavealpha( $final, true );

		ob_start();
		imagepng( $final );
		$final_data = ob_get_clean();

		$base64 = 'data:image/png;base64,' . base64_encode( $final_data ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode,WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode

		imagedestroy( $expanded );
		imagedestroy( $final );

		return $base64;
	}

	/**
	 * Generate expansion prompt.
	 *
	 * @return mixed The result.
	 */
	private function generate_expansion_prompt() {
		return 'Expand this image in all directions with seamless, pixel-perfect continuity. Preserve every aspect of the original image — including its composition, proportions, lighting, shadows, perspective, color grading, textures, and visual style. Extend the scene naturally by continuing the same background, patterns, environment, and depth without altering the main subject. Do not introduce new objects, subjects, or visual elements. Do not distort, stretch, warp, or modify any part of the original image. Maintain the exact same mood, tone, sharpness, and light direction. The expanded areas must blend flawlessly with the original, with no visible transitions or artifacts, as if the image were originally captured with a wider frame.';
	}

	/**
	 * Nxt ai expand.
	 *
	 * @param array $params The params.
	 * @return mixed The result.
	 */
	function nxt_ai_expand( $params ) { // phpcs:ignore Squiz.Scope.MethodScope.Missing
		$expander = self::get_instance();

		$args = array(
			'image_url' => $params->get_param( 'image_url' ),
			'width'     => ( intval( $params->get_param( 'width' ) ) ) ? intval( $params->get_param( 'width' ) ) : 512,
			'height'    => ( intval( $params->get_param( 'height' ) ) ) ? intval( $params->get_param( 'height' ) ) : 512,
			'offset_x'  => ( intval( $params->get_param( 'offset_x' ) ) ) ? intval( $params->get_param( 'offset_x' ) ) : 0,
			'offset_y'  => ( intval( $params->get_param( 'offset_y' ) ) ) ? intval( $params->get_param( 'offset_y' ) ) : 0,
			'prompt'    => ( $params->get_param( 'prompt' ) ) ? $params->get_param( 'prompt' ) : '',
		);

		return $expander->expand_image( $args );
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
