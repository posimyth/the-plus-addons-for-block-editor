<?php
/**
 * Nxt Ai Image Editor.
 *
 * @package ThePluginAddonsForBlockEditor
 */

// phpcs:disable WordPress.Files.FileName

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

/**
 * Nxt_ A I_ Image_ Editor.
 *
 * @since 1.0.0
 */
class Nxt_AI_Image_Editor {

	/**
	 * Edit image.
	 *
	 * @param array $args The args.
	 * @return mixed The result.
	 */
	public function edit_image( $args = array() ) {
		$args = wp_parse_args(
			$args,
			array(
				'prompt'         => '',
				'original_image' => '',
				'mask_image'     => '',
				'model'          => 'dall-e-2',
				'n'              => 1,
				'size'           => '1024x1024',
			)
		);

		$prompt         = sanitize_textarea_field( $args['prompt'] );
		$original_image = $args['original_image'];
		$mask_image     = $args['mask_image'];
		$model          = sanitize_text_field( $args['model'] );
		$n              = absint( $args['n'] );
		$size           = sanitize_text_field( $args['size'] );

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
			$decrypted = Tp_Blocks_Helper::tpgb_simple_decrypt(
				$encrypted,
				'dy'
			);
			$settings  = json_decode( $decrypted, true );
		} else {
			$settings = array();
		}

		$api_key     = $settings['chatgptApiKey'] ?? '';
		$img_enabled = $settings['chatgptEnableImage'] ?? false;

		$is_enabled = true === $img_enabled || 1 === $img_enabled;

		if ( empty( $prompt ) || empty( $original_image ) ) {
			return array(
				'success' => false,
				'message' => 'Prompt and original image are required',
			);
		}

		if ( empty( $api_key ) ) {
			return array(
				'success' => false,
				'message' => 'API key not configured',
			);
		}

		$original_png = $this->process_image_to_png( $original_image );
		if ( is_wp_error( $original_png ) ) {
			return array(
				'success' => false,
				'message' => $original_png->get_error_message(),
			);
		}

		$mask_png = null;
		if ( ! empty( $mask_image ) ) {
			$mask_png = $this->process_mask_image_correct( $mask_image );
			if ( is_wp_error( $mask_png ) ) {
				return array(
					'success' => false,
					'message' => $mask_png->get_error_message(),
				);
			}
		}

		$api_result = $this->send_edit_request(
			$api_key,
			$prompt,
			$original_png,
			$mask_png,
			$model,
			$n,
			$size
		);
		if ( is_wp_error( $api_result ) ) {
			return array(
				'success' => false,
				'message' => $api_result->get_error_message(),
			);
		}

		$base64_images = $this->process_result_images( $api_result );
		if ( empty( $base64_images ) ) {
			return array(
				'success' => false,
				'message' => 'Failed to process any images',
			);
		}

		$total_tokens = 'undefined';
		if ( $is_enabled && isset( $api_result['usage']['total_tokens'] ) ) {
			$total_tokens = $api_result['usage']['total_tokens'];
		}

		return array(
			'success'         => true,
			'message'         => 'Generated successfully',
			'data'            => $base64_images,
			'total_tokens'    => $total_tokens,
			'chatgpt_enabled' => $is_enabled,
		);
	}

	/**
	 * Process image to png.
	 *
	 * @param mixed $img_input The img input.
	 * @return mixed The result.
	 */
	private function process_image_to_png( $img_input ) {
		$img_resource = null;

		if ( filter_var( $img_input, FILTER_VALIDATE_URL ) ) {
			$img_data     = @file_get_contents( $img_input ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents, WordPress.PHP.NoSilencedErrors.Discouraged
			$img_resource = @imagecreatefromstring( $img_data ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
		} elseif ( strpos( $img_input, 'data:image' ) === 0 ) {
			$parts        = explode( ',', $img_input, 2 );
			$img_data     = base64_decode( $parts[1] ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode,WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode
			$img_resource = @imagecreatefromstring( $img_data ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
		} else {
			return new WP_Error( 'invalid_input', 'Invalid image' );
		}

		if ( ! $img_resource ) {
			return new WP_Error( 'load_failed', 'Failed to load image' );
		}

		$width  = imagesx( $img_resource );
		$height = imagesy( $img_resource );

		if ( $width !== $height ) {
			imagedestroy( $img_resource );
			return new WP_Error( 'not_square', 'Image must be square' );
		}

		if ( $width > 4096 ) {
			imagedestroy( $img_resource );
			return new WP_Error( 'too_large', 'Max size 4096x4096' );
		}

		$new_img = imagecreatetruecolor( $width, $height );
		$white   = imagecolorallocate( $new_img, 255, 255, 255 );
		imagefill( $new_img, 0, 0, $white );
		imagealphablending( $new_img, true );
		imagecopy( $new_img, $img_resource, 0, 0, 0, 0, $width, $height );
		imagealphablending( $new_img, false );
		imagesavealpha( $new_img, true );

		ob_start();
		imagepng( $new_img, null, 9 );
		$png_data = ob_get_clean();

		imagedestroy( $img_resource );
		imagedestroy( $new_img );

		return $png_data;
	}

	/**
	 * Process mask image correct.
	 *
	 * @param mixed $mask_input The mask input.
	 * @return mixed The result.
	 */
	private function process_mask_image_correct( $mask_input ) {
		if ( strpos( $mask_input, 'data:image' ) === 0 ) {
			$parts         = explode( ',', $mask_input, 2 );
			$mask_data     = base64_decode( $parts[1] ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode,WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode
			$mask_resource = @imagecreatefromstring( $mask_data ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
		} else {
			return new WP_Error( 'invalid_mask', 'Invalid mask format' );
		}

		if ( ! $mask_resource ) {
			return new WP_Error( 'load_failed', 'Failed to load mask' );
		}

		$width  = imagesx( $mask_resource );
		$height = imagesy( $mask_resource );

		$new_mask = imagecreatetruecolor( $width, $height );
		imagealphablending( $new_mask, false );
		imagesavealpha( $new_mask, true );

		$edit_pixels = 0;

		for ( $y = 0; $y < $height; $y++ ) {
			for ( $x = 0; $x < $width; $x++ ) {
				$rgba   = imagecolorat( $mask_resource, $x, $y );
				$colors = imagecolorsforindex( $mask_resource, $rgba );

				$r = $colors['red'];
				$g = $colors['green'];
				$b = $colors['blue'];

				$is_painted =
					( $r > 150 && $g > 150 && $b > 150 ) ||
					( $r > 150 && $g < 100 && $b < 100 );

				if ( $is_painted ) {
					$color = imagecolorallocatealpha( $new_mask, 0, 0, 0, 127 );
					imagesetpixel( $new_mask, $x, $y, $color );
					++$edit_pixels;
				} else {
					$color = imagecolorallocatealpha(
						$new_mask,
						255,
						255,
						255,
						0
					);
					imagesetpixel( $new_mask, $x, $y, $color );
				}
			}
		}

		if ( $edit_pixels < 100 ) {
			imagedestroy( $mask_resource );
			imagedestroy( $new_mask );
			return new WP_Error(
				'small_mask',
				'Mask too small. Paint larger area.'
			);
		}

		ob_start();
		imagepng( $new_mask, null, 9 );
		$processed_mask = ob_get_clean();

		imagedestroy( $mask_resource );
		imagedestroy( $new_mask );

		return $processed_mask;
	}

	private function send_edit_request( // phpcs:ignore Squiz.Commenting.FunctionComment
		$api_key,
		$prompt,
		$original_png,
		$mask_png,
		$model,
		$n,
		$size
	) {
		$boundary = wp_generate_password( 24, false );
		$url      = 'https://api.openai.com/v1/images/edits';

		$headers = array(
			'Authorization' => 'Bearer ' . $api_key,
			'Content-Type'  => 'multipart/form-data; boundary=' . $boundary,
		);

		$body = $this->build_multipart_body(
			$boundary,
			$original_png,
			$mask_png,
			$prompt,
			$model,
			$n,
			$size
		);

		$response = wp_remote_post(
			$url,
			array(
				'headers' => $headers,
				'body'    => $body,
				'timeout' => 60,
			)
		);

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$code = wp_remote_retrieve_response_code( $response );
		$body = wp_remote_retrieve_body( $response );

		if ( 200 !== $code ) {
			$error_data = json_decode( $body, true );
			$error_msg  = isset( $error_data['error']['message'] )
				? $error_data['error']['message']
				: 'Unknown error';
			return new WP_Error( 'api_error', $error_msg );
		}

		$data = json_decode( $body, true );

		if ( empty( $data['data'] ) ) {
			return new WP_Error(
				'invalid_response',
				'Invalid response from OpenAI'
			);
		}

		return $data;
	}

	private function build_multipart_body( // phpcs:ignore Squiz.Commenting.FunctionComment
		$boundary,
		$original_png,
		$mask_png,
		$prompt,
		$model,
		$n,
		$size
	) {
		$body = '';

		$body .= "--{$boundary}\r\n";
		$body .=
			"Content-Disposition: form-data; name=\"image\"; filename=\"image.png\"\r\n";
		$body .= "Content-Type: image/png\r\n\r\n";
		$body .= $original_png . "\r\n";

		if ( null !== $mask_png ) {
			$body .= "--{$boundary}\r\n";
			$body .=
				"Content-Disposition: form-data; name=\"mask\"; filename=\"mask.png\"\r\n";
			$body .= "Content-Type: image/png\r\n\r\n";
			$body .= $mask_png . "\r\n";
		}

		$body .= "--{$boundary}\r\n";
		$body .= "Content-Disposition: form-data; name=\"prompt\"\r\n\r\n";
		$body .= $prompt . "\r\n";

		$body .= "--{$boundary}\r\n";
		$body .= "Content-Disposition: form-data; name=\"model\"\r\n\r\n";
		$body .= $model . "\r\n";

		$body .= "--{$boundary}\r\n";
		$body .= "Content-Disposition: form-data; name=\"n\"\r\n\r\n";
		$body .= $n . "\r\n";

		$body .= "--{$boundary}\r\n";
		$body .= "Content-Disposition: form-data; name=\"size\"\r\n\r\n";
		$body .= $size . "\r\n";

		$body .= "--{$boundary}--\r\n";

		return $body;
	}

	/**
	 * Process result images.
	 *
	 * @param mixed $api_response The api response.
	 * @return mixed The result.
	 */
	private function process_result_images( $api_response ) {
		$base64_images = array();

		foreach ( $api_response['data'] as $index => $item ) {
			if ( empty( $item['url'] ) ) {
				continue;
			}

			$openai_url   = $item['url'];
			$img_response = wp_remote_get(
				$openai_url,
				array(
					'timeout'   => 30,
					'sslverify' => true,
				)
			);

			if ( is_wp_error( $img_response ) ) {
				continue;
			}

			$img_data = wp_remote_retrieve_body( $img_response );
			if ( empty( $img_data ) ) {
				continue;
			}

			$base64          = base64_encode( $img_data ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode,WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode
			$data_url        = 'data:image/png;base64,' . $base64;
			$base64_images[] = $data_url;
		}

		return $base64_images;
	}

	/**
	 * Edit and save.
	 *
	 * @param array $args The args.
	 * @param array $save_args The save args.
	 * @return mixed The result.
	 */
	public function edit_and_save( $args = array(), $save_args = array() ) {
		$edit_result = $this->edit_image( $args );

		if ( ! $edit_result['success'] ) {
			return $edit_result;
		}

		require_once plugin_dir_path( __FILE__ ) . 'class-nxt-ai-image-saver.php';
		$image_saver = Nxt_AI_Image_Saver::get_instance();

		$save_results = $image_saver->save_multiple_images(
			$edit_result['data'],
			$save_args
		);

		return array(
			'success'         => true,
			'message'         => 'Images edited and saved successfully',
			'edited_images'   => $edit_result['data'],
			'saved_images'    => $save_results['results'],
			'total_tokens'    => $edit_result['total_tokens'],
			'chatgpt_enabled' => $edit_result['chatgpt_enabled'],
		);
	}

	/**
	 * Nxt ai image edit.
	 *
	 * @param mixed $request The request.
	 * @return mixed The result.
	 */
	function nxt_ai_image_edit( $request ) { // phpcs:ignore Squiz.Scope.MethodScope.Missing
		$editor = self::get_instance();

		$args = array(
			'prompt'         => $request->get_param( 'prompt' ),
			'original_image' => $request->get_param( 'original_image' ),
			'mask_image'     => $request->get_param( 'mask_image' ),
			'model'          => $request->get_param( 'model' ),
			'n'              => $request->get_param( 'n' ),
			'size'           => $request->get_param( 'size' ),
		);

		$result = $editor->edit_image( $args );

		if ( $result['success'] ) {
			return new WP_REST_Response( $result, 200 );
		} else {
			return new WP_REST_Response( $result, 400 );
		}
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
