<?php
/**
 * Nxt Ai Image Generator.
 *
 * @package ThePluginAddonsForBlockEditor
 */

// phpcs:disable WordPress.Files.FileName

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

/**
 * Nxt_ A I_ Image_ Generator.
 *
 * @since 1.0.0
 */
class Nxt_AI_Image_Generator {

	private $type_modifiers = array( // phpcs:ignore Squiz.Commenting.VariableComment.MissingVar,Squiz.Commenting.VariableComment.Missing
		'landscape'           => 'landscape photography, wide panoramic vista, natural outdoor scenery',
		'seascape'            => 'seascape photography, open ocean view, dramatic coastal scenery',
		'mountainscape'       => 'mountain landscape, towering peaks and valleys, alpine scenery',
		'desert_photography'  => 'desert photography, arid open landscape, rolling sand dunes and heat haze',
		'forest_photography'  => 'forest photography, dense woodland scene, trees, foliage, and natural light',
		'aerial_landscape'    => 'aerial landscape view, expansive terrain from above, sweeping natural patterns',
		'night_sky'           => 'night sky photography, astrophotography, stars, galaxies, and celestial objects',
		'sunset_sunrise'      => 'sunset or sunrise scene, golden hour lighting, colorful dramatic sky',
		'portrait'            => 'portrait photography, single human subject, balanced and focused composition',
		'closeup_portrait'    => 'close-up portrait, detailed facial features, intimate framing and shallow depth',
		'headshot'            => 'professional headshot, shoulders and face, clean neutral background',
		'group_portrait'      => 'group portrait, multiple subjects together, well-arranged and cohesive posing',
		'fashion_photography' => 'fashion photography, stylish wardrobe, editorial-quality posing and lighting',
		'lifestyle_photo'     => 'lifestyle photography, candid everyday moment, natural and authentic scene',
		'street_portrait'     => 'street portrait, human subject in urban setting, environmental and expressive',
		'wildlife'            => 'wildlife photography, animals in nature, natural behavior in native habitat',
		'pet_portrait'        => 'pet portrait, domestic animal, personality and charm clearly captured',
		'bird_photography'    => 'bird photography, detailed avian subject, sharp plumage and graceful posture',
		'underwater_wildlife' => 'underwater wildlife photography, marine animals, immersive aquatic environment',
		'safari_shot'         => 'safari photography, African wildlife, open savanna setting and warm light',
		'street_photography'  => 'street photography, real urban life, candid moments and spontaneous scenes',
		'architecture'        => 'architecture photography, building design, clean lines and structural details',
		'cityscape'           => 'cityscape photography, urban skyline, wide city view and distant horizon',
		'urban_night'         => 'urban night photography, city lights, glowing signs and evening atmosphere',
		'industrial'          => 'industrial photography, factories and machinery, gritty urban industrial mood',
		'macro'               => 'macro photography, extreme close-up, tiny subjects with fine details',
		'closeup'             => 'close-up shot, detailed view, shallow depth of field and background blur',
		'texture_detail'      => 'texture detail photography, surface patterns, intricate and tactile details',
		'product_macro'       => 'product macro photography, close-up product shot, crisp commercial detail',
		'sports'              => 'sports photography, athletic action, strong energy and peak movement, dynamic movement',
		'action_shot'         => 'action shot, frozen motion, dynamic composition with clear subject',
		'motion_blur'         => 'motion blur effect, streaked movement, high-speed dynamic feel',
		'long_exposure'       => 'long exposure photography, smooth water or light trails, soft motion effect',
		'aerial'              => 'aerial photography, overhead view, high altitude and broad perspective',
		'drone_shot'          => "drone photography, bird's-eye angle, elevated sweeping view",
		'top_down'            => 'top-down view, flat lay or overhead, subject seen directly from above',
		'bird_eye'            => "bird's-eye view, high angle perspective, looking down on the scene",
		'abstract'            => 'abstract photography, non-literal subject, focus on shapes, colors, and forms',
		'minimalist'          => 'minimalist composition, few simple elements, strong clean negative space',
		'black_white'         => 'black and white photography, monochrome tones, focus on light and shadow',
		'high_contrast'       => 'high contrast photography, bold lights and deep shadows, dramatic tonality',
		'cinematic'           => 'cinematic still frame, movie-like quality, dramatic lighting and atmosphere',
		'bokeh'               => 'shallow depth of field, smooth bokeh effect, softly blurred background',
		'hdr'                 => 'HDR photography look, high dynamic range, enhanced detail and vivid tones',
		'tilt_shift'          => 'tilt-shift effect, miniature-style look, selective focus and blur',
		'still_life'          => 'still life photography, carefully arranged objects, balanced artistic composition',
		'food'                => 'food photography, appetizing dishes, professional culinary styling',
		'product'             => 'product photography, clean commercial shot, neutral background and clarity',
		'flat_lay'            => 'flat lay photography, neatly organized items, overhead arrangement layout',
	);

	private $style_modifiers = array( // phpcs:ignore Squiz.Commenting.VariableComment.MissingVar,Squiz.Commenting.VariableComment.Missing
		'realistic'        => 'realistic visual style, natural proportions, lifelike detail and lighting',
		'photorealistic'   => 'photorealistic rendering, ultra-detailed, indistinguishable from real photo',
		'cinematic'        => 'cinematic lighting, filmic mood, dramatic composition and depth',
		'film_noir'        => 'film noir style, high contrast, deep shadows and vintage crime-movie mood',
		'vintage'          => 'vintage style, retro color palette, slightly aged and nostalgic look',
		'black_white'      => 'black and white style, rich grayscale tones, emphasis on texture and light',
		'minimalist'       => 'minimalist style, clean and uncluttered, focus on one main subject',
		'modern'           => 'modern aesthetic, sleek lines, contemporary and polished design',
		'aesthetic'        => 'aesthetic composition, visually pleasing balance, stylized artistic mood',
		'fantasy'          => 'fantasy art, magical atmosphere, imaginative otherworldly elements',
		'surreal'          => 'surreal imagery, dreamlike scenes, unexpected and unusual combinations',
		'dreamy'           => 'dreamy atmosphere, soft focus, gentle light and ethereal quality',
		'abstract'         => 'abstract visual style, non-representational, expressive shapes and colors',
		'concept_art'      => 'concept art style, detailed designs, production-quality imaginative artwork',
		'digital_painting' => 'digital painting style, painterly brushwork, layered artistic rendering',
		'oil_painting'     => 'oil painting style, textured brushstrokes, classic fine art appearance',
		'watercolor'       => 'watercolor painting style, soft edges, translucent and flowing colors',
		'pencil_sketch'    => 'pencil sketch style, hand-drawn lines, monochrome shading and contours',
		'charcoal_drawing' => 'charcoal drawing style, bold strokes, dramatic shading and texture',
		'3d_render'        => '3D render style, polished computer-generated imagery, realistic lighting',
		'isometric'        => 'isometric view, angled geometric perspective, technical or game-like layout',
		'voxel_art'        => 'voxel art style, blocky cube forms, retro 3D pixel appearance',
		'pixel_art'        => 'pixel art style, low-resolution, retro game pixel aesthetic',
		'low_poly'         => 'low poly style, faceted geometric shapes, simplified 3D modeling',
		'flat_design'      => 'flat design style, 2D graphics, no heavy gradients, clean simple shapes',
		'vector_art'       => 'vector art style, crisp clean lines, scalable graphic illustration',
		'line_art'         => 'line art style, minimal outlines, simple monochrome or limited color',
		'pop_art'          => 'pop art style, bold saturated colors, graphic comic-inspired visuals',
		'comic_style'      => 'comic book style, strong outlines, halftone textures and dynamic framing',
		'anime'            => 'anime style, Japanese animation look, expressive faces and vibrant colors',
		'manga'            => 'manga style, black and white line art, dynamic panel-like drawing',
		'cartoon'          => 'cartoon style, playful and simplified shapes, exaggerated expressions',
		'disney_style'     => 'Disney animation style, charming expressive characters, polished look',
		'pixar_style'      => 'Pixar-style 3D character design, appealing proportions and cinematic lighting',
		'studio_ghibli'    => 'Studio Ghibli style, hand-drawn aesthetic, detailed environments and warmth',
		'cyberpunk'        => 'cyberpunk aesthetic, neon lights, dense futuristic dystopian cityscape',
		'steampunk'        => 'steampunk style, Victorian-era influence, gears, brass, and retro technology',
		'futuristic'       => 'futuristic style, sleek sci-fi design, advanced technology and clean lines',
		'sci_fi'           => 'science fiction style, space-age concepts, advanced tech and strange worlds',
		'noir'             => 'noir style, moody atmosphere, shadows, and mysterious storytelling tone',
		'gothic'           => 'gothic style, dark ornate structures, dramatic and medieval influences',
		'baroque'          => 'baroque style, ornate decoration, dramatic lighting and rich details',
		'renaissance'      => 'renaissance art style, classical realism, balanced composition and anatomy',
		'impressionism'    => 'impressionist style, loose brushwork, strong light and color impressions',
		'expressionism'    => 'expressionist style, emotional distortion, bold color and form',
		'cubism'           => 'cubist style, fractured geometric shapes, multiple viewpoints combined',
		'surrealism'       => 'surrealist art style, dreamlike symbolism, subconscious imagery',
		'hyperrealism'     => 'hyperrealistic style, extreme detail, almost indistinguishable from reality',
		'hdr'              => 'HDR visual style, expanded dynamic range, vivid highlights and deep shadows',
		'pastel'           => 'pastel color palette, soft gentle hues, light and soothing tones',
		'vaporwave'        => 'vaporwave aesthetic, retro-digital vibe, pink and cyan neon nostalgia',
		'synthwave'        => 'synthwave style, 1980s-inspired, neon grids, sunsets, and retrowave mood',
		'neon_glow'        => 'neon glow effect, bright luminous edges, vibrant light accents in the scene',
	);

	private $size_map = array( // phpcs:ignore Squiz.Commenting.VariableComment.MissingVar,Squiz.Commenting.VariableComment.Missing
		'dall-e-3'         => array(
			'1:1'  => '1024x1024',
			'16:9' => '1792x1024',
			'9:16' => '1024x1792',
		),
		'gpt-image-1'      => array(
			'1:1' => '1024x1024',
			'3:2' => '1536x1024',
			'2:3' => '1024x1536',
		),
		'gpt-image-1-mini' => array(
			'1:1' => '1024x1024',
			'3:2' => '1536x1024',
			'2:3' => '1024x1536',
		),
	);

	/**
	 * Generate image.
	 *
	 * @param array $args The args.
	 * @return mixed The result.
	 */
	public function generate_image( $args = array() ) {
		$args = wp_parse_args(
			$args,
			array(
				'prompt'        => '',
				'model'         => 'dall-e-2',
				'n'             => 1,
				'aspect_ratio'  => '1:1',
				'image_type'    => '',
				'image_style'   => '',
				'image_quality' => '',
			)
		);

		$prompt = sanitize_text_field( $args['prompt'] );
		if ( empty( $prompt ) ) {
			return array(
				'success' => false,
				'message' => 'Prompt is required',
			);
		}

		$settings = $this->get_settings();
		if ( ! $settings ) {
			return array(
				'success' => false,
				'message' => 'Settings not found',
			);
		}

		$is_enable = ( $settings['geminiEnableImage'] || $settings['chatgptEnableImage'] ) ? '1' : '0';
		if ( '1' !== $is_enable ) {
			return array(
				'success' => false,
				'message' => 'AI Image Generation is not enabled',
			);
		}

		$use_gemini = ! empty( $settings['geminiEnableImage'] )
		&& ! empty( $settings['geminiApiKey'] );

		$use_chatgpt = ! empty( $settings['chatgptEnableImage'] )
		&& ! empty( $settings['chatgptApiKey'] );

		if ( ! $use_gemini && ! $use_chatgpt ) {
			return array(
				'success' => false,
				'message' => 'No AI service enabled or API key missing',
			);
		}

		$enhanced = $this->enhance_prompt(
			$prompt,
			sanitize_text_field( $args['image_type'] ),
			sanitize_text_field( $args['image_style'] ),
			$settings['businessContext'] ?? '',
			$settings['targetedAudience'] ?? ''
		);

		if ( $use_gemini ) {
			return $this->call_gemini_api(
				$settings['geminiApiKey'] ?? '',
				$enhanced,
				sanitize_text_field( $args['model'] ?? '' ),
				absint( $args['n'] ?? 0 ),
				sanitize_text_field( $args['aspect_ratio'] ?? '' ),
				absint( $settings['geminiImageMaxTokens'] ?? 0 ),
				sanitize_text_field( $args['image_quality'] ?? '' )
			);
		}

		return $this->call_openai_api(
			$settings['chatgptApiKey'] ?? '',
			$enhanced,
			sanitize_text_field( $args['model'] ?? '' ),
			absint( $args['n'] ?? 0 ),
			$this->get_size(
				sanitize_text_field( $args['model'] ?? '' ),
				sanitize_text_field( $args['aspect_ratio'] ?? '' )
			),
			sanitize_text_field( $args['image_quality'] ?? '' ),
			absint( $settings['chatgptImageMaxTokens'] ?? 0 )
		);
	}

	/**
	 * Get settings.
	 *
	 * @return mixed The result.
	 */
	private function get_settings() {
		$raw       = Tp_Blocks_Helper::get_extra_option( 'nxtAiSettings' );
		$encrypted = is_string( $raw ) ? $raw : ( is_array( $raw ) ? reset( $raw ) : '' );

		if ( empty( $encrypted ) ) {
			return false;
		}

		$decrypted = Tp_Blocks_Helper::tpgb_simple_decrypt( $encrypted, 'dy' );
		$decoded   = json_decode( $decrypted, true );
		return $decoded ? $decoded : false;
	}

	/**
	 * Enhance prompt.
	 *
	 * @param mixed  $prompt The prompt.
	 * @param mixed  $type The type.
	 * @param mixed  $style The style.
	 * @param string $biz The biz.
	 * @param string $aud The aud.
	 * @return mixed The result.
	 */
	private function enhance_prompt( $prompt, $type, $style, $biz = '', $aud = '' ) {
		$mods = array_filter(
			array(
				$prompt,
				$this->type_modifiers[ $type ] ?? '',
				$this->style_modifiers[ $style ] ?? '',
				$biz ? "Context: $biz" : '',
				$aud ? "For audience: $aud" : '',
			)
		);
		return implode( ', ', $mods );
	}

	/**
	 * Get size.
	 *
	 * @param mixed $model The model.
	 * @param mixed $aspect The aspect.
	 * @return mixed The result.
	 */
	private function get_size( $model, $aspect ) {
		return $this->size_map[ $model ][ $aspect ] ?? '1024x1024';
	}

	/**
	 * Call openai api.
	 *
	 * @param mixed $key The key.
	 * @param mixed $prompt The prompt.
	 * @param mixed $model The model.
	 * @param mixed $n The n.
	 * @param int   $size The size.
	 * @param mixed $quality The quality.
	 * @param mixed $tokens The tokens.
	 * @return mixed The result.
	 */
	private function call_openai_api( $key, $prompt, $model, $n, $size, $quality, $tokens ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
		$body = array(
			'model'  => $model ? $model : 'dall-e-2',
			'prompt' => $prompt,
			'n'      => $n,
			'size'   => $size,
		);
		if ( $quality ) {
			$body['quality'] = $quality;
		}

		$response = wp_remote_post(
			'https://api.openai.com/v1/images/generations',
			array(
				'headers' => array(
					'Content-Type'  => 'application/json',
					'Authorization' => "Bearer $key",
				),
				'body'    => wp_json_encode( $body ),
				'timeout' => 60,
			)
		);

		if ( is_wp_error( $response ) ) {
			return array(
				'success' => false,
				'message' => $response->get_error_message(),
			);
		}

		$code = wp_remote_retrieve_response_code( $response );
		if ( 200 !== $code ) {
			return array(
				'success' => false,
				'message' => "API Error: $code",
			);
		}

		$data = json_decode( wp_remote_retrieve_body( $response ), true );
		if ( empty( $data['data'] ) ) {
			return array(
				'success' => false,
				'message' => 'No image data',
			);
		}

		$images = array_map(
			function ( $img ) {
				return $img['url'] ?? 'data:image/png;base64,' . ( $img['b64_json'] ?? '' );
			},
			$data['data']
		);

		return array(
			'success' => true,
			'message' => 'OpenAI response fetched',
			'data'    => $images,
		);
	}

	/**
	 * Call gemini api.
	 *
	 * @param mixed $key The key.
	 * @param mixed $prompt The prompt.
	 * @param mixed $model The model.
	 * @param mixed $n The n.
	 * @param mixed $aspect The aspect.
	 * @param mixed $tokens The tokens.
	 * @param int   $size The size.
	 * @return mixed The result.
	 */
	private function call_gemini_api( $key, $prompt, $model, $n, $aspect, $tokens, $size ) {

		$model = $model ? $model : 'gemini-2.5-flash-image';
		error_log( 'model = ' . $model ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
		$is_imagen = strpos( $model, 'imagen-' ) === 0;
		$url       = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:" .
				( $is_imagen ? 'predict' : 'generateContent' ) . "?key={$key}";

		if ( $is_imagen && $n <= 4 ) {
			return $this->call_imagen_batch( $url, $prompt, $n, $aspect, $size );
		}

		$all_imgs = array();
		$errors   = array();

		for ( $i = 0; $i < $n; $i++ ) {
			$body = $is_imagen ?
				$this->build_imagen_request( $prompt, $aspect, $size ) :
				$this->build_gemini_request( $prompt, $aspect, $tokens, $size );

			$response = wp_remote_post(
				$url,
				array(
					'headers' => array( 'Content-Type' => 'application/json' ),
					'body'    => wp_json_encode( $body ),
					'timeout' => 90,
				)
			);

			if ( is_wp_error( $response ) ) {
				$errors[] = 'Request ' . ( $i + 1 ) . ': ' . $response->get_error_message();
				continue;
			}

			$code = wp_remote_retrieve_response_code( $response );
			if ( 200 !== $code ) {
				$content = wp_remote_retrieve_body( $response );
				$err     = json_decode( $content, true );

				if ( 404 === $code && strpos( $content, 'not found' ) !== false ) {
					$msg = "Model '$model' not found. Try 'gemini-2.5-flash-image' or 'imagen-4.0-fast-generate-001'";
				} else {
					$msg = $err['error']['message'] ?? "API Error: $code";
				}

				$errors[] = 'Request ' . ( $i + 1 ) . ": $msg";
				continue;
			}

			$data = json_decode( wp_remote_retrieve_body( $response ), true );
			$imgs = $is_imagen ? $this->extract_imagen_images( $data ) : $this->extract_gemini_images( $data );

			if ( $imgs ) {
				$all_imgs = array_merge( $all_imgs, $imgs );
			} else {
				$errors[] = 'Request ' . ( $i + 1 ) . ': No image data';
			}

			if ( $i < $n - 1 ) {
				usleep( 500000 );
			}
		}

		if ( empty( $all_imgs ) ) {
			return array(
				'success' => false,
				'message' => ( implode( '; ', $errors ) ) ? implode( '; ', $errors ) : 'No images generated',
			);
		}

		return array(
			'success'    => true,
			'message'    => count( $all_imgs ) . " of $n images generated",
			'data'       => $all_imgs,
			'model_type' => $is_imagen ? 'imagen' : 'gemini',
		);
	}

	/**
	 * Build gemini request.
	 *
	 * @param mixed $prompt The prompt.
	 * @param mixed $aspect The aspect.
	 * @param mixed $tokens The tokens.
	 * @param int   $size The size.
	 * @return mixed The result.
	 */
	private function build_gemini_request( $prompt, $aspect, $tokens, $size ) {
		$body = array(
			'contents'         => array( array( 'parts' => array( array( 'text' => $prompt ) ) ) ),
			'generationConfig' => array( 'responseModalities' => array( 'IMAGE' ) ),
		);

		if ( $tokens > 0 ) {
			$body['generationConfig']['maxOutputTokens'] = $tokens;
		}

		$img_cfg = array();
		if ( $aspect && '1:1' !== $aspect ) {
			$img_cfg['aspectRatio'] = $aspect;
		}
		if ( $size && in_array( $size, array( '1K', '2K', '4K' ), true ) ) {
			$img_cfg['imageSize'] = $size;
		}
		if ( $img_cfg ) {
			$body['generationConfig']['imageConfig'] = $img_cfg;
		}

		return $body;
	}

	/**
	 * Build imagen request.
	 *
	 * @param mixed $prompt The prompt.
	 * @param mixed $aspect The aspect.
	 * @param int   $size The size.
	 * @return mixed The result.
	 */
	private function build_imagen_request( $prompt, $aspect, $size ) {
		$body = array(
			'instances'  => array( array( 'prompt' => $prompt ) ),
			'parameters' => array( 'sampleCount' => 1 ),
		);

		if ( $aspect && '1:1' !== $aspect ) {
			$body['parameters']['aspectRatio'] = $aspect;
		}
		if ( $size && in_array( $size, array( '1K', '2K' ), true ) ) {
			$body['parameters']['imageSize'] = $size;
		}

		return $body;
	}

	/**
	 * Call imagen batch.
	 *
	 * @param mixed $url The url.
	 * @param mixed $prompt The prompt.
	 * @param mixed $n The n.
	 * @param mixed $aspect The aspect.
	 * @param int   $size The size.
	 * @return mixed The result.
	 */
	private function call_imagen_batch( $url, $prompt, $n, $aspect, $size ) {
		$instances = array_fill( 0, $n, array( 'prompt' => $prompt ) );
		$body      = array(
			'instances'  => $instances,
			'parameters' => array( 'sampleCount' => $n ),
		);

		if ( $aspect && '1:1' !== $aspect ) {
			$body['parameters']['aspectRatio'] = $aspect;
		}
		if ( $size && in_array( $size, array( '1K', '2K' ), true ) ) {
			$body['parameters']['imageSize'] = $size;
		}

		$response = wp_remote_post(
			$url,
			array(
				'headers' => array( 'Content-Type' => 'application/json' ),
				'body'    => wp_json_encode( $body ),
				'timeout' => 90,
			)
		);

		if ( is_wp_error( $response ) ) {
			return array(
				'success' => false,
				'message' => $response->get_error_message(),
			);
		}

		$code = wp_remote_retrieve_response_code( $response );
		if ( 200 !== $code ) {
			$err = json_decode( wp_remote_retrieve_body( $response ), true );
			return array(
				'success' => false,
				'message' => $err['error']['message'] ?? "API Error: $code",
			);
		}

		$imgs = $this->extract_imagen_images( json_decode( wp_remote_retrieve_body( $response ), true ) );

		if ( empty( $imgs ) ) {
			return array(
				'success' => false,
				'message' => 'No images generated',
			);
		}

		return array(
			'success' => true,
			'message' => count( $imgs ) . ' images generated',
			'data'    => $imgs,
		);
	}

	/**
	 * Extract gemini images.
	 *
	 * @param array $data The data.
	 * @return mixed The result.
	 */
	private function extract_gemini_images( $data ) {
		$imgs = array();
		if ( ! empty( $data['candidates'][0]['content']['parts'] ) ) {
			foreach ( $data['candidates'][0]['content']['parts'] as $part ) {
				if ( isset( $part['inlineData']['data'] ) ) {
					$mime   = $part['inlineData']['mimeType'] ?? 'image/png';
					$imgs[] = "data:$mime;base64," . $part['inlineData']['data'];
				}
			}
		}
		return $imgs;
	}

	/**
	 * Extract imagen images.
	 *
	 * @param array $data The data.
	 * @return mixed The result.
	 */
	private function extract_imagen_images( $data ) {
		$imgs = array();
		if ( ! empty( $data['predictions'] ) ) {
			foreach ( $data['predictions'] as $pred ) {
				if ( isset( $pred['bytesBase64Encoded'] ) ) {
					$mime   = $pred['mimeType'] ?? 'image/png';
					$imgs[] = "data:$mime;base64," . $pred['bytesBase64Encoded'];
				}
			}
		}
		return $imgs;
	}

	/**
	 * Nxt ai image gen.
	 *
	 * @param array $params The params.
	 * @return mixed The result.
	 */
	public function nxt_ai_image_gen( $params ) {
		$result = $this->generate_image(
			array(
				'prompt'        => $params->get_param( 'prompt' ),
				'model'         => $params->get_param( 'model' ),
				'n'             => $params->get_param( 'n' ),
				'aspect_ratio'  => $params->get_param( 'aspect_ratio' ),
				'image_type'    => $params->get_param( 'image_type' ),
				'image_style'   => $params->get_param( 'image_style' ),
				'image_quality' => $params->get_param( 'image_quality' ),
			)
		);

		return new WP_REST_Response( $result, $result['success'] ? 200 : 400 );
	}

	/**
	 * Get instance.
	 *
	 * @return mixed The result.
	 */
	public static function get_instance() {
		static $instance = null;
		return $instance ? $instance : ( $instance = new self() ); // phpcs:ignore Squiz.PHP.DisallowMultipleAssignments.FoundInCondition,Squiz.PHP.DisallowMultipleAssignments.Found
	}
}
