<?php
/**
 * Nxt Ai Image Compress.
 *
 * @package ThePluginAddonsForBlockEditor
 */

// phpcs:disable WordPress.Files.FileName

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

/**
 * Nxt_ A I_ Image_ Compress.
 *
 * @since 1.0.0
 */
class Nxt_AI_Image_Compress {

	/**
	 * Compress image.
	 *
	 * @param array $args The args.
	 * @return mixed The result.
	 */
	public function compress_image( $args = array() ) {
		$args = wp_parse_args(
			$args,
			array(
				'image'  => '',
				'width'  => '',
				'height' => '',
				'save'   => true,
			)
		);

		if ( empty( $args['image'] ) ) {
			return array(
				'success' => false,
				'message' => 'Image is required',
			);
		}

		$resolution = ! empty( $args['width'] ) ? $args['width'] : $args['height'];
		if ( empty( $resolution ) ) {
			return array(
				'success' => false,
				'message' => 'Resolution is required',
			);
		}

		try {
			$target_res = $this->parse_resolution( $resolution );
			$quality    = $this->calculate_quality( $target_res );
			$img_data   = $this->load_image_data( $args['image'] );

			if ( is_wp_error( $img_data ) ) {
				return array(
					'success' => false,
					'message' => $img_data->get_error_message(),
				);
			}

			$source = @imagecreatefromstring( $img_data['data'] ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
			if ( false === $source ) {
				return array(
					'success' => false,
					'message' => 'Invalid image format or corrupted image data',
				);
			}

			$width     = imagesx( $source );
			$height    = imagesy( $source );
			$orig_size = $img_data['size'];

			$result = $this->compress_and_save( $source, $quality, $target_res, $width, $height, $orig_size, $args['save'] );
			imagedestroy( $source );

			return $result;
		} catch ( Exception $e ) {
			return array(
				'success' => false,
				'message' => 'Error: ' . $e->getMessage(),
			);
		}
	}

	/**
	 * Parse resolution.
	 *
	 * @param mixed $res_str The res str.
	 * @return mixed The result.
	 */
	private function parse_resolution( $res_str ) {
		if ( preg_match( '/(\d+)x\d+/', $res_str, $matches ) ) {
			return intval( $matches[1] );
		}
		return intval( $res_str );
	}

	/**
	 * Calculate quality.
	 *
	 * @param mixed $resolution The resolution.
	 * @return mixed The result.
	 */
	private function calculate_quality( $resolution ) {
		if ( $resolution <= 256 ) {
			return 30;
		}
		if ( $resolution <= 512 ) {
			return 60;
		}
		if ( $resolution <= 1024 ) {
			return 85;
		}
		return 95;
	}

	/**
	 * Load image data.
	 *
	 * @param mixed $image The image.
	 * @return mixed The result.
	 */
	private function load_image_data( $image ) {
		if ( filter_var( $image, FILTER_VALIDATE_URL ) ) {
			$response = wp_remote_get(
				$image,
				array(
					'timeout'   => 30,
					'sslverify' => false,
				)
			);

			if ( is_wp_error( $response ) ) {
				return new WP_Error( 'download_failed', 'Failed to download image: ' . $response->get_error_message() );
			}

			$data = wp_remote_retrieve_body( $response );
			if ( empty( $data ) ) {
				return new WP_Error( 'empty_data', 'Empty image data from URL' );
			}

			return array(
				'data' => $data,
				'size' => strlen( $data ),
			);
		}

		if ( strpos( $image, 'data:image' ) === 0 ) {
			$image = preg_replace( '/^data:image\/\w+;base64,/', '', $image );
		}

		$data = base64_decode( $image ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode,WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode
		if ( false === $data || empty( $data ) ) {
			return new WP_Error( 'invalid_base64', 'Invalid base64 image data' );
		}

		return array(
			'data' => $data,
			'size' => strlen( $data ),
		);
	}

	/**
	 * Compress and save.
	 *
	 * @param mixed $source The source.
	 * @param mixed $quality The quality.
	 * @param mixed $target_res The target res.
	 * @param int   $width The width.
	 * @param int   $height The height.
	 * @param int   $orig_size The orig size.
	 * @param bool  $save The save.
	 * @return mixed The result.
	 */
	private function compress_and_save( $source, $quality, $target_res, $width, $height, $orig_size, $save = true ) {
		$upload_dir = wp_upload_dir();
		$filename   = 'compressed-' . $target_res . '-' . time() . '.jpg';
		$filepath   = $upload_dir['path'] . '/' . $filename;

		$saved = imagejpeg( $source, $filepath, $quality );
		if ( ! $saved ) {
			return array(
				'success' => false,
				'message' => 'Failed to save compressed image',
			);
		}

		$new_size = filesize( $filepath );
		$result   = array(
			'success'    => true,
			'message'    => 'Image compressed successfully',
			'quality'    => $quality,
			'resolution' => $target_res,
			'dimensions' => array(
				'width'  => $width,
				'height' => $height,
			),
			'file_size'  => array(
				'original'   => round( $orig_size / 1024, 2 ) . ' KB',
				'compressed' => round( $new_size / 1024, 2 ) . ' KB',
				'saved'      => round( ( $orig_size - $new_size ) / 1024, 2 ) . ' KB',
				'reduction'  => round( ( ( $orig_size - $new_size ) / $orig_size ) * 100, 1 ) . '%',
			),
		);

		if ( $save ) {
			$attachment = $this->save_to_media_library( $filepath );
			if ( is_wp_error( $attachment ) ) {
				wp_delete_file( $filepath );
				return array(
					'success' => false,
					'message' => $attachment->get_error_message(),
				);
			}
			$result['url']      = $attachment['url'];
			$result['image_id'] = $attachment['id'];
		} else {
			$compressed_data = file_get_contents( $filepath ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
			$result['url']   = 'data:image/jpeg;base64,' . base64_encode( $compressed_data ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode,WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode
			wp_delete_file( $filepath );
		}

		return $result;
	}

	/**
	 * Save to media library.
	 *
	 * @param mixed $filepath The filepath.
	 * @return mixed The result.
	 */
	private function save_to_media_library( $filepath ) {
		$filetype   = wp_check_filetype( basename( $filepath ), null );
		$attachment = array(
			'post_mime_type' => $filetype['type'],
			'post_title'     => sanitize_file_name( pathinfo( $filepath, PATHINFO_FILENAME ) ),
			'post_content'   => '',
			'post_status'    => 'inherit',
		);

		$attach_id = wp_insert_attachment( $attachment, $filepath );
		if ( is_wp_error( $attach_id ) ) {
			return new WP_Error( 'attachment_failed', 'Failed to create attachment' );
		}

		require_once ABSPATH . 'wp-admin/includes/image.php';
		$attach_data = wp_generate_attachment_metadata( $attach_id, $filepath );
		wp_update_attachment_metadata( $attach_id, $attach_data );

		return array(
			'id'  => $attach_id,
			'url' => wp_get_attachment_url( $attach_id ),
		);
	}

	/**
	 * Nxt ai resize.
	 *
	 * @param array $params The params.
	 * @return mixed The result.
	 */
	function nxt_ai_resize( $params ) { // phpcs:ignore Squiz.Scope.MethodScope.Missing
		$compressor = self::get_instance();
		$args       = array(
			'image'  => $params->get_param( 'image' ),
			'width'  => $params->get_param( 'width' ),
			'height' => $params->get_param( 'height' ),
			'save'   => $params->get_param( 'save' ) !== false,
		);
		return $compressor->compress_image( $args );
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
