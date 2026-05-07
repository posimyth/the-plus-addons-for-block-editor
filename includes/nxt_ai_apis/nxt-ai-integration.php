<?php
/**
 * Nxt Ai Integration.
 *
 * @package ThePluginAddonsForBlockEditor
 */

// phpcs:disable WordPress.Files.FileName

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

/**
 * Nxt_ A I_ Integration.
 *
 * @since 1.0.0
 */
class Nxt_AI_Integration {

	private $tone_instructions = array( // phpcs:ignore Squiz.Commenting.VariableComment.MissingVar,Squiz.Commenting.VariableComment.Missing
		'professional'  =>
			'Write with a polished, clear, and credible tone that reflects expertise and confidence.',
		'casual'        =>
			'Write in a relaxed, friendly, conversational tone that feels natural and approachable.',
		'humorous'      =>
			'Write with light, clever humor and playful timing without being exaggerated.',
		'enthusiastic'  =>
			'Write with energetic, upbeat language that feels positive and full of momentum.',
		'formal'        =>
			'Write with refined, structured, and sophisticated language using proper etiquette.',
		'informal'      =>
			'Write in an easy-going, relaxed style that feels simple, warm, and human.',
		'optimistic'    =>
			'Write with uplifting, positive language that expresses hope and bright possibilities.',
		'friendly'      =>
			'Write with warmth and openness, using approachable language that feels welcoming.',
		'serious'       =>
			'Write in a focused, grounded, and no-nonsense tone that conveys importance.',
		'excited'       =>
			'Write with lively, animated wording that conveys strong enthusiasm and anticipation.',
		'dramatic'      =>
			'Write with expressive, bold language that heightens intensity and emotional impact.',
		'witty'         =>
			'Write with clever, sharp phrasing and intelligent humor delivered subtly.',
		'sarcastic'     =>
			'Write with dry, ironic language that conveys humor through contrast and understatement.',
		'curious'       =>
			'Write with inquisitive, exploratory language that encourages discovery and wonder.',
		'adventurous'   =>
			'Write with daring, bold language that evokes excitement, exploration, and risk-taking.',
		'romantic'      =>
			'Write with gentle, affectionate language that conveys warmth, tenderness, and emotion.',
		'persuasive'    =>
			'Write with compelling, benefit-driven language that guides readers toward agreement.',
		'inspirational' =>
			'Write with uplifting, motivational language that sparks ambition and emotional strength.',
		'educational'   =>
			'Write with clear, structured, informative language designed to teach effectively.',
		'motivational'  =>
			'Write with energizing, empowering language that encourages action and progress.',
		'empathetic'    =>
			'Write with sensitive, understanding language that validates feelings and builds trust.',
		'respectful'    =>
			'Write with courteous, considerate language that maintains dignity and professionalism.',
		'sympathetic'   =>
			'Write with gentle, supportive language that expresses care and emotional understanding.',
		'encouraging'   =>
			'Write with positive, confidence-building language that helps readers feel capable.',
		'hopeful'       =>
			'Write with forward-looking, optimistic language that suggests better outcomes ahead.',
		'confident'     =>
			'Write with assured, decisive language that demonstrates clarity and strong conviction.',
		'bold'          =>
			'Write with strong, fearless language that creates a vivid and memorable impression.',
		'funny'         =>
			'Write with playful, amusing language that creates lighthearted comedic moments.',
		'lighthearted'  =>
			'Write with cheerful, breezy language that feels enjoyable and carefree.',
		'playful'       =>
			'Write with lively, spirited language that adds fun and creative energy.',
		'serene'        =>
			'Write with calm, soothing language that creates a peaceful and balanced tone.',
		'calm'          =>
			'Write with steady, composed language that feels relaxing and reassuring.',
		'peaceful'      =>
			'Write with gentle, harmonious language that evokes quiet and emotional ease.',
		'reflective'    =>
			'Write with thoughtful, introspective language that explores ideas meaningfully.',
		'thoughtful'    =>
			'Write with deliberate, considerate language that shows care and intention.',
		'introspective' =>
			'Write with self-aware, reflective language that examines deeper meaning.',
	);

	/**
	 * Generate text.
	 *
	 * @param array $args The args.
	 * @return mixed The result.
	 */
	public function generate_text( $args = array() ) {
		$args = wp_parse_args(
			$args,
			array(
				'prompt'        => '',
				'temperature'   => 0.7,
				'tone'          => 'professional',
				'model'         => 'gemini',
				'code_language' => '',
			)
		);

		$prompt        = sanitize_text_field( $args['prompt'] );
		$temp          = floatval( $args['temperature'] );
		$tone          = sanitize_text_field( $args['tone'] );
		$model         = sanitize_text_field( $args['model'] );
		$code_language = sanitize_text_field( $args['code_language'] );

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
		$chatgpt_model  = sanitize_text_field(
			$settings['chatgptSelectedTextModel'] ?? 'gpt-5.2'
		);

		$gemini_key    = $settings['geminiApiKey'] ?? '';
		$gemini_txt    = $settings['geminiEnableText'] ?? false;
		$gemini_tokens = absint( $settings['geminiTextMaxTokens'] ?? 0 );
		$gemini_model  = sanitize_text_field(
			$settings['geminiSelectedTextModel'] ?? 'gemini-3-pro-preview'
		);

		$biz_ctx  = sanitize_text_field( $settings['businessContext'] ?? '' );
		$audience = sanitize_text_field( $settings['targetedAudience'] ?? '' );

		$use_gemini  = false;
		$use_chatgpt = false;
		$api_key     = '';
		$max_tokens  = null;

		if ( true === $gemini_txt && ! empty( $gemini_key ) ) {
			$use_gemini = true;
			$api_key    = $gemini_key;
			$max_tokens = ! empty( $gemini_tokens )
				? intval( $gemini_tokens )
				: null;
		} elseif ( true === $chatgpt_txt && ! empty( $chatgpt_key ) ) {
			$use_chatgpt = true;
			$api_key     = $chatgpt_key;
			$max_tokens  = ! empty( $chatgpt_tokens )
				? intval( $chatgpt_tokens )
				: null;
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

		// Check if code generation is requested.
		$is_code_request = ! empty( $code_language );

		if ( $is_code_request ) {
			$template = $this->build_code_template(
				$prompt,
				$code_language,
				$biz_ctx,
				$audience
			);
			// For code generation, use fixed temperature for consistency.
			$temp = 0.3;
		} else {
			$template = $this->build_template(
				$prompt,
				$tone,
				$biz_ctx,
				$audience
			);
		}

		if ( $use_gemini ) {
			$result = $this->call_gemini(
				$api_key,
				$template,
				$temp,
				$gemini_model,
				$max_tokens
			);
		} elseif ( $use_chatgpt ) {
			$result = $this->call_chatgpt(
				$api_key,
				$template,
				$temp,
				$chatgpt_model,
				$max_tokens
			);
		} else {
			return array(
				'success' => false,
				'message' => 'Unsupported model',
			);
		}

		// Clean code response if it's a code request.
		if ( $is_code_request && $result['success'] ) {
			$result['data'] = $this->clean_code_response( $result['data'] );
		}

		return $result;
	}

	private function build_code_template( // phpcs:ignore Squiz.Commenting.FunctionComment
		$prompt,
		$code_language,
		$biz_ctx = '',
		$audience = ''
	) {
		$ctx_section = '';
		if ( ! empty( $biz_ctx ) || ! empty( $audience ) ) {
			$ctx_section = "\n\nContext Information:";
			if ( ! empty( $biz_ctx ) ) {
				$ctx_section .=
					"\n- Business Context: " . sanitize_text_field( $biz_ctx );
			}
			if ( ! empty( $audience ) ) {
				$ctx_section .=
					"\n- Target Audience: " . sanitize_text_field( $audience );
			}
		}

		return "You are a professional code generator. Generate clean, production-ready code based on the following requirements:

Primary Task: {$prompt}
Programming Language: {$code_language}
{$ctx_section}

CRITICAL REQUIREMENTS:
- Generate ONLY the raw code in {$code_language} with NO markdown formatting.
- DO NOT wrap the code in backticks, code blocks, or any markdown syntax.
- DO NOT include ANY comments in the code (no //, #, /** */, or similar).
- Write clean, efficient, well-structured, and production-ready code.
- Follow best practices and coding standards for {$code_language}.
- Do not include explanations, tutorials, or meta-commentary.
- Do not add phrases like \"Here is the code\" or \"Sure, let me help.\"
- Begin directly with the code itself with proper indentation.
- Ensure the code is complete, functional, and ready to use.
- If the task requires multiple functions or components, provide them all in a single complete file.
- Use proper indentation and formatting for the {$code_language}.
- Return ONLY executable code with no additional text.

Generate the code now:";
	}

	private function build_template( // phpcs:ignore Squiz.Commenting.FunctionComment
		$prompt,
		$tone,
		$biz_ctx = '',
		$audience = ''
	) {
		$tone_txt =
			$this->tone_instructions[ $tone ] ??
			$this->tone_instructions['professional'];

		$ctx_section = '';
		if ( ! empty( $biz_ctx ) || ! empty( $audience ) ) {
			$ctx_section = "\n\nContext Information:";
			if ( ! empty( $biz_ctx ) ) {
				$ctx_section .=
					"\n- Business Context: " . sanitize_text_field( $biz_ctx );
			}
			if ( ! empty( $audience ) ) {
				$ctx_section .=
					"\n- Target Audience: " . sanitize_text_field( $audience );
			}
		}

		return "You are a content generator for WordPress websites. Generate clean, plain text content based on the following requirements:

Primary Task: {$prompt}
{$ctx_section}

Requirements:
- {$tone_txt}
- Write in plain text only, with NO HTML tags, NO markdown, and NO special formatting characters such as asterisks (*), underscores (_), hashes (#), bullets, or symbols.
- Do not use bold, italics, headers, lists, decorative characters, or stylized formatting.
- Begin directly with the content. Do not add phrases like \"Here is the content\" or \"Sure, let me help.\"
- Write as a human would speak: natural flow, clear sentences, steady pacing, and meaningful expression.
- Stay focused on the primary task. Treat the context as helpful background only.
- Do not include explanations, meta-commentary, or system-like replies. Output ONLY the final content requested.
- Maintain the exact tone specified in the tone requirement.
- Avoid filler language, clichés, repetitive phrasing, or overly generic statements.

Generate the content now:";
	}

	private function call_gemini( // phpcs:ignore Squiz.Commenting.FunctionComment
		$key,
		$template,
		$temp,
		$gemini_model,
		$tokens = null
	) {
		$url = "https://generativelanguage.googleapis.com/v1beta/models/{$gemini_model}:generateContent";

		$gen_config = array(
			'temperature' => $temp,
		);

		if ( ! empty( $tokens ) && $tokens > 0 ) {
			$gen_config['maxOutputTokens'] = (int) $tokens;
		}

		$body = array(
			'contents'         => array(
				array(
					'parts' => array( array( 'text' => $template ) ),
				),
			),
			'generationConfig' => $gen_config,
		);

		$response = wp_remote_post(
			$url,
			array(
				'headers' => array(
					'Content-Type'   => 'application/json',
					'x-goog-api-key' => $key, // DO NOT LOG THIS.
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

		$raw_body = wp_remote_retrieve_body( $response );

		if ( 200 !== $code ) {
			return array(
				'success' => false,
				'message' => 'API Error: ' . $code,
			);
		}

		$data = json_decode( $raw_body, true );

		if ( json_last_error() !== JSON_ERROR_NONE ) {
			return array(
				'success' => false,
				'message' => 'Invalid JSON response from Gemini',
			);
		}

		$text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';

		$total_tokens = $data['usageMetadata']['totalTokenCount'] ?? 0;

		return array(
			'success'      => true,
			'message'      => 'Gemini AI response fetched',
			'data'         => $text,
			'total_tokens' => $total_tokens,
		);
	}

	private function call_chatgpt( // phpcs:ignore Squiz.Commenting.FunctionComment
		$key,
		$template,
		$temp,
		$model,
		$tokens = null
	) {
		$url = 'https://api.openai.com/v1/chat/completions';

		if (
			'gpt-5-mini' === $model ||
			'gpt-5' === $model ||
			'o3' === $model ||
			'o4-mini' === $model
		) {
			$temp = 1;
		}

		$body    = array(
			'model'    => $model,
			'messages' => array(
				array(
					'role'    => 'user',
					'content' => $template,
				),
			),
		);
		$is_gpt5 = strpos( $model, 'gpt-5' ) === 0;

		if ( ! empty( $tokens ) && (int) $tokens > 0 ) {
			if (
				strpos( $model, 'gpt-5' ) === 0 ||
				strpos( $model, 'o1' ) === 0 ||
				strpos( $model, 'o3' ) === 0 ||
				strpos( $model, 'o4-mini' ) === 0
			) {
				// GPT-5 models.
				$body['max_completion_tokens'] = (int) $tokens;
			} else {
				// Older models.
				$body['max_tokens'] = (int) $tokens;
			}
		}

		if ( strpos( $model, 'o1' ) !== 0 && strpos( $model, 'o3-mini' ) !== 0 ) {
			$body['temperature'] = $temp;
		}

		$response = wp_remote_post(
			$url,
			array(
				'headers' => array(
					'Content-Type'  => 'application/json',
					'Authorization' => 'Bearer ' . $key, // DO NOT log key.
				),
				'body'    => wp_json_encode( $body ),
				'timeout' => 90,
			)
		);

		// ---- Transport error ----
		if ( is_wp_error( $response ) ) {
			return array(
				'success' => false,
				'message' => $response->get_error_message(),
			);
		}

		// ---- HTTP response ----
		$code     = wp_remote_retrieve_response_code( $response );
		$raw_body = wp_remote_retrieve_body( $response );

		if ( 200 !== $code ) {
			return array(
				'success' => false,
				'message' => 'API Error: ' . $code,
			);
		}

		// ---- JSON decode ----
		$data = json_decode( $raw_body, true );

		if ( json_last_error() !== JSON_ERROR_NONE ) {
			return array(
				'success' => false,
				'message' => 'Invalid API response',
			);
		}

		// ---- Validate response structure ----
		if ( empty( $data['choices'][0]['message']['content'] ) ) {
			return array(
				'success' => false,
				'message' => 'Empty response from AI',
			);
		}

		$text         = $data['choices'][0]['message']['content'];
		$total_tokens = $data['usage']['total_tokens'] ?? 0;

		return array(
			'success'      => true,
			'message'      => 'ChatGPT AI response fetched',
			'data'         => $text,
			'total_tokens' => $total_tokens,
		);
	}

	/**
	 * Clean response.
	 *
	 * @param mixed $text The text.
	 * @return mixed The result.
	 */
	private function clean_response( $text ) {
		$text = trim( $text );
		$text = preg_replace( '/\s+/', ' ', $text );
		return $text;
	}

	/**
	 * Clean code response.
	 *
	 * @param mixed $text The text.
	 * @return mixed The result.
	 */
	private function clean_code_response( $text ) {
		// Remove markdown code block syntax.
		$text = preg_replace( '/```[a-zA-Z]*\n/', '', $text );
		$text = preg_replace( '/```\n?/', '', $text );
		$text = str_replace( '```', '', $text );

		// Trim whitespace.
		$text = trim( $text );

		// Preserve proper line breaks and indentation (don't compress whitespace for code).
		return $text;
	}

	/**
	 * Nxt ai integration.
	 *
	 * @param array $params The params.
	 * @return mixed The result.
	 */
	public function nxt_ai_integration( $params ) {
		$ai = self::get_instance();

		$temperature = $params->get_param( 'temperature' );

		if ( null === $temperature ) {
			$temperature = $params->get_param( 'temprature' ); // fallback.
		}

		$args = array(
			'prompt'        => $params->get_param( 'prompt' ),
			'temperature'   => $temperature,
			'tone'          => $params->get_param( 'tone' ),
			'model'         => $params->get_param( 'model' ),
			'code_language' => $params->get_param( 'code_language' ),
		);

		$result = $ai->generate_text( $args );

		if ( $result['success'] ) {
			return new WP_REST_Response( $result, 200 ); // phpcs:ignore Universal.CodeAnalysis.ConstructorDestructorReturn.ReturnValueFound
		} else {
			return new WP_REST_Response( $result, 400 ); // phpcs:ignore Universal.CodeAnalysis.ConstructorDestructorReturn.ReturnValueFound
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
