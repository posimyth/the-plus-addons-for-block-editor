<?php
/**
 * Nxt Ai Register Api.
 *
 * @package ThePluginAddonsForBlockEditor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// Load required files.
$file_list = array(
	'nxt-ai-integration',
	'nxt-ai-image-generator',
	'nxt-ai-image-saver',
	'nxt-ai-image-editor',
	'nxt-ai-image-expand',
	'nxt-ai-image-compress',
	'nxt-ai-image-variations',
	'nxt-ai-prompt-enhance',
);

foreach ( $file_list as $file ) {
	require_once TPGB_PATH . "includes/nxt_ai_apis/{$file}.php";
}

// Register REST API routes.
add_action(
	'rest_api_init',
	function () {
		$routes = array(
			'nxt_ai'            => array( Nxt_AI_Integration::get_instance(), 'nxt_ai_integration' ),
			'nxt_ai_image_gen'  => array( Nxt_AI_Image_Generator::get_instance(), 'nxt_ai_image_gen' ),
			'nxt_ai_save'       => array( Nxt_AI_Image_Saver::get_instance(), 'nxt_ai_save' ),
			'nxt_ai_edit'       => array( Nxt_AI_Image_Editor::get_instance(), 'nxt_ai_image_edit' ),
			'nxt_ai_expand'     => array( Nxt_AI_Image_Expand::get_instance(), 'nxt_ai_expand' ),
			'nxt_ai_resize'     => array( Nxt_AI_Image_Compress::get_instance(), 'nxt_ai_resize' ),
			'nxt_ai_variations' => array( Nxt_AI_Image_Variations::get_instance(), 'nxt_ai_variations' ),
			'nxt_ai_enhance'    => array( Nxt_AI_Prompt_Enhance::get_instance(), 'nxt_ai_enhance' ),
		);

		foreach ( $routes as $endpoint => $callback ) {
			register_rest_route(
				'tpgb/v1',
				"/{$endpoint}/",
				array(
					'methods'             => 'POST',
					'callback'            => $callback,
					'permission_callback' => fn() => current_user_can( 'manage_options' ),
				)
			);
		}
	}
);
