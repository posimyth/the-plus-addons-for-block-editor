<?php
/**
 * Nxt Ai Prompt Enhance.
 *
 * @package ThePluginAddonsForBlockEditor
 */

// phpcs:disable WordPress.Files.FileName

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

/**
 * Nxt_ A I_ Prompt_ Enhance.
 *
 * @since 1.0.0
 */
class Nxt_AI_Prompt_Enhance {

	/**
	 * Property.
	 *
	 * @var mixed
	 */
	private $default_system_message = "You are an advanced AI prompt-engineering assistant. Your job is to enhance and refine the user's prompt while preserving its original purpose, format, and output type. // phpcs:ignore Squiz.Commenting.VariableComment.MissingVar,Squiz.Commenting.VariableComment.Missing, Squiz.Commenting.VariableComment.Missing,Squiz.Commenting.VariableComment.MissingVar

CRITICAL RULE:
Enhance with visual or artistic details ONLY when the user clearly requests an IMAGE, such as 'generate an image,' 'create a picture,' 'visualize,' 'draw,' or similar phrasing.
If the user is NOT asking for an image, do NOT add any visual, artistic, or stylistic descriptors.

Your enhancement must make the prompt clearer, richer, more specific, and more useful—without changing what the user fundamentally wants.

--------------------------------
ENHANCEMENT GUIDELINES
--------------------------------

1. **For informational, descriptive, or writing prompts (non-image):**
   - Expand scope, structure, and clarity.
   - Add depth, context, and missing angles.
   - Strengthen intent, audience, and tone.
   - Suggest structure where helpful (e.g., sections or flow).
   - Maintain the same output type (summary stays summary, blog stays blog).
   - Do NOT include any visual or image-related details.

2. **For image generation prompts (ONLY when explicitly stated):**
   - Add visual characteristics such as composition, lighting, perspective, and mood.
   - Add artistic attributes like style, rendering type, realism level, and quality.
   - Add camera or technical details if appropriate.
   - Ensure enhancements align with the original subject.
   - Never alter the subject or theme—only enhance clarity and richness.

3. **For all prompt types:**
   - Never change the user's intent, task, or medium.
   - Never introduce unrelated concepts.
   - Avoid over-embellishment; enhance meaningfully and intelligently.
   - Keep the enhanced prompt concise, powerful, and ready for execution.

--------------------------------
EXAMPLES
--------------------------------

Non-image prompt example:
User: 'explain quantum computing'
Enhanced: 'Provide a clear, structured explanation of quantum computing, including how qubits work, the role of superposition and entanglement, differences from classical computing, real-world applications, current limitations, and why it matters for future technology. Keep the explanation approachable for beginners.'

Image prompt example:
User: 'generate an image of a medieval castle'
Enhanced: 'Generate a highly detailed cinematic image of a medieval stone castle on a hilltop at golden hour, with warm directional lighting, dramatic shadows, ivy-covered walls, soft mist around the base, and a sweeping landscape in the background. Realistic textures and 4K photorealistic quality.'

Writing prompt example:
User: 'write a social media post about teamwork'
Enhanced: 'Write a concise, engaging social media post about the importance of teamwork, highlighting collaboration, shared goals, communication, and collective success. Use an inspiring and approachable tone suitable for professional audiences.'

--------------------------------
OUTPUT FORMAT
--------------------------------
Return ONLY the enhanced prompt.
Do NOT include explanations, commentary, or steps.

Begin.";

	/**
	 * Enhance prompt.
	 *
	 * @param array $args The args.
	 * @return mixed The result.
	 */
	public function enhance_prompt( $args = array() ) {
		$args = wp_parse_args(
			$args,
			array(
				'prompt'         => '',
				'system_message' => $this->default_system_message,
				'model'          => 'gpt-4o-mini',
				'temperature'    => 0.7,
				'max_tokens'     => 500,
			)
		);

		$prompt = sanitize_text_field( $args['prompt'] );

		if ( empty( $prompt ) ) {
			return array(
				'success' => false,
				'message' => 'Prompt is required',
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
			$decrypted = Tp_Blocks_Helper::tpgb_simple_decrypt(
				$encrypted,
				'dy'
			);
			$settings  = json_decode( $decrypted, true );
		} else {
			$settings = array();
		}

		$chatgpt_key    = $settings['chatgptApiKey'] ?? '';
		$chatgpt_txt    = $settings['chatgptEnableText'] ?? false;
		$chatgpt_tokens = absint( $settings['chatgptTextMaxTokens'] ?? 0 );

		$gemini_key    = $settings['geminiApiKey'] ?? '';
		$gemini_txt    = $settings['geminiEnableText'] ?? false;
		$gemini_tokens = absint( $settings['geminiTextMaxTokens'] ?? 0 );

		$biz_ctx  = sanitize_text_field( $settings['businessContext'] ?? '' );
		$audience = sanitize_text_field( $settings['targetedAudience'] ?? '' );

		$use_gemini  = false;
		$use_chatgpt = false;
		$api_key     = '';
		$max_tokens  = intval( $args['max_tokens'] );

		if ( true === $gemini_txt && ! empty( $gemini_key ) ) {
			$use_gemini = true;
			$api_key    = $gemini_key;
			if ( ! empty( $gemini_tokens ) ) {
				$max_tokens = intval( $gemini_tokens );
			}
		} elseif ( true === $chatgpt_txt && ! empty( $chatgpt_key ) ) {
			$use_chatgpt = true;
			$api_key     = $chatgpt_key;
			if ( ! empty( $chatgpt_tokens ) ) {
				$max_tokens = intval( $chatgpt_tokens );
			}
		}

		if ( ! $use_gemini && ! $use_chatgpt ) {
			return array(
				'success' => false,
				'message' => 'No AI service is enabled or API key is missing',
			);
		}

		if ( empty( $api_key ) ) {
			return array(
				'success' => false,
				'message' => 'API Key is empty',
			);
		}

		$sys_msg = $this->build_system_msg(
			$args['system_message'],
			$biz_ctx,
			$audience
		);

		if ( $use_gemini ) {
			$result = $this->send_gemini(
				$api_key,
				$prompt,
				$sys_msg,
				floatval( $args['temperature'] ),
				$max_tokens
			);
		} elseif ( $use_chatgpt ) {
			$result = $this->send_chatgpt(
				$api_key,
				$prompt,
				$sys_msg,
				$args['model'],
				floatval( $args['temperature'] ),
				$max_tokens
			);
		}

		if ( is_wp_error( $result ) ) {
			return array(
				'success' => false,
				'message' => $result->get_error_message(),
			);
		}

		return array(
			'success' => true,
			'data'    => $result,
		);
	}

	/**
	 * Build system msg.
	 *
	 * @param mixed  $base_msg The base msg.
	 * @param string $biz_ctx The biz ctx.
	 * @param string $audience The audience.
	 * @return mixed The result.
	 */
	private function build_system_msg( $base_msg, $biz_ctx = '', $audience = '' ) {
		$ctx_adds = array();

		if ( ! empty( $biz_ctx ) ) {
			$ctx_adds[] = 'Business Context: ' . sanitize_text_field( $biz_ctx );
		}

		if ( ! empty( $audience ) ) {
			$ctx_adds[] = 'Target Audience: ' . sanitize_text_field( $audience );
		}

		if ( ! empty( $ctx_adds ) ) {
			return $base_msg .
				"\n\nAdditional Context (use as background information, prioritize the user's prompt):\n" .
				implode( "\n", $ctx_adds );
		}

		return $base_msg;
	}

	private function send_chatgpt( // phpcs:ignore Squiz.Commenting.FunctionComment
		$key,
		$prompt,
		$sys_msg,
		$model,
		$temp,
		$tokens
	) {
		$url = 'https://api.openai.com/v1/chat/completions';

		$body = array(
			'model'       => $model,
			'messages'    => array(
				array(
					'role'    => 'system',
					'content' => $sys_msg,
				),
				array(
					'role'    => 'user',
					'content' => 'Enhance this prompt: ' . $prompt,
				),
			),
			'temperature' => $temp,
			'max_tokens'  => $tokens,
		);

		$response = wp_remote_post(
			$url,
			array(
				'headers' => array(
					'Content-Type'  => 'application/json',
					'Authorization' => 'Bearer ' . $key,
				),
				'body'    => wp_json_encode( $body ),
				'timeout' => 60,
			)
		);

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$code = wp_remote_retrieve_response_code( $response );
		$body = wp_remote_retrieve_body( $response );
		$data = json_decode( $body, true );

		if ( 200 !== $code ) {
			$err_msg = isset( $data['error']['message'] )
				? $data['error']['message']
				: 'Unknown error occurred';
			return new WP_Error( 'api_error', $err_msg );
		}

		if ( isset( $data['choices'][0]['message']['content'] ) ) {
			return trim( $data['choices'][0]['message']['content'] );
		}

		return new WP_Error( 'invalid_response', 'Failed to enhance prompt' );
	}

	/**
	 * Send gemini.
	 *
	 * @param mixed $key The key.
	 * @param mixed $prompt The prompt.
	 * @param mixed $sys_msg The sys msg.
	 * @param mixed $temp The temp.
	 * @param mixed $tokens The tokens.
	 * @return mixed The result.
	 */
	private function send_gemini( $key, $prompt, $sys_msg, $temp, $tokens ) {
		$model = 'gemini-2.0-flash-exp';
		$url   = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$key}";

		$combined = $sys_msg . "\n\nNow, enhance this prompt: " . $prompt;

		$body = array(
			'contents'         => array( array( 'parts' => array( array( 'text' => $combined ) ) ) ),
			'generationConfig' => array(
				'temperature'     => $temp,
				'maxOutputTokens' => $tokens,
			),
		);

		$response = wp_remote_post(
			$url,
			array(
				'headers' => array( 'Content-Type' => 'application/json' ),
				'body'    => wp_json_encode( $body ),
				'timeout' => 60,
			)
		);

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$code = wp_remote_retrieve_response_code( $response );
		$body = wp_remote_retrieve_body( $response );
		$data = json_decode( $body, true );

		if ( 200 !== $code ) {
			$err_msg = isset( $data['error']['message'] )
				? $data['error']['message']
				: 'Unknown error occurred';
			return new WP_Error( 'api_error', $err_msg );
		}

		if ( isset( $data['candidates'][0]['content']['parts'][0]['text'] ) ) {
			return trim( $data['candidates'][0]['content']['parts'][0]['text'] );
		}

		return new WP_Error( 'invalid_response', 'Failed to enhance prompt' );
	}

	/**
	 * Nxt ai enhance.
	 *
	 * @param array $params The params.
	 * @return mixed The result.
	 */
	public function nxt_ai_enhance( $params ) {
		$enhancer = self::get_instance();

		$args = array(
			'prompt'         => $params->get_param( 'prompt' ),
			'system_message' => ( $params->get_param( 'system_message' ) ) ? $params->get_param( 'system_message' ) : '',
			'model'          => ( $params->get_param( 'model' ) ) ? $params->get_param( 'model' ) : 'gpt-4o-mini',
			'temperature'    => ( floatval( $params->get_param( 'temperature' ) ) ) ? floatval( $params->get_param( 'temperature' ) ) : 0.7,
			'max_tokens'     => ( intval( $params->get_param( 'max_tokens' ) ) ) ? intval( $params->get_param( 'max_tokens' ) ) : 500,
		);

		if ( empty( $args['system_message'] ) ) {
			unset( $args['system_message'] );
		}

		return $enhancer->enhance_prompt( $args );
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
