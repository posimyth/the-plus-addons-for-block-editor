<?php
/**
 * Ability: Return the Image-to-Page skill (procedural guide) so MCP clients
 * can fetch it and follow the documented workflow.
 *
 * The MCP client should call this ability the moment a user provides a layout
 * image and asks to recreate / build / match it as a Nexter Blocks page.
 *
 * @package The_Plus_Addons_For_Block_Editor
 * @since   1.3.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_register_ability(
	'nexter-blocks/get-image-to-page-skill',
	array(
		'label'               => __( 'Get Image-to-Page Skill Guide', 'the-plus-addons-for-block-editor' ),
		'description'         => __( 'Returns a procedural guide for converting a layout image into a Nexter Blocks Gutenberg page. Call this whenever the user provides a screenshot, mockup, or design image and asks to recreate, build, match, convert, or "make this" as a WordPress page using nexter-blocks/* abilities — but only AFTER nexter-blocks/get-performance-skill has been called for the session, so the build respects the performance budget. The returned guide instructs how to analyze the image (palette, typography, spacing), plan the block tree, build in the correct outer-to-inner order, and verify the result. Skipping this step typically reduces visual fidelity to under 60%.', 'the-plus-addons-for-block-editor' ),
		'category'            => 'nexter-blocks',

		'input_schema'        => array(
			'type'                 => 'object',
			'properties'           => new stdClass(),
			'additionalProperties' => false,
		),

		'output_schema'       => array(
			'type'       => 'object',
			'properties' => array(
				'guide' => array(
					'type'        => 'string',
					'description' => 'Markdown content of the image-to-page workflow guide.',
				),
			),
		),

		'execute_callback'    => 'tpgb_mcp_get_image_to_page_skill',
		'permission_callback' => 'tpgb_mcp_get_image_to_page_skill_permission',
		'meta'                => array(
			'show_in_rest' => true,
			'mcp'          => array(
				'public' => true,
				'type'   => 'tool',
			),
		),
	)
);

/**
 * Permission callback for the get-image-to-page-skill ability.
 *
 * @param array|null $input Ability input arguments (unused; kept for callback signature).
 * @return bool True when the current user may read the skill guide.
 */
function tpgb_mcp_get_image_to_page_skill_permission( ?array $input = null ): bool {
	unset( $input );
	return current_user_can( 'edit_posts' );
}

/**
 * Execute callback: returns the image-to-page skill guide as Markdown.
 *
 * @param array $input Ability input arguments (unused; kept for callback signature).
 * @return array|WP_Error array{guide:string} on success, WP_Error when the
 *                        bundled SKILL.md is missing or unreadable so the MCP
 *                        client can surface a real failure instead of silently
 *                        treating an empty guide as valid output.
 */
function tpgb_mcp_get_image_to_page_skill( array $input = array() ) {
	unset( $input );
	$path = TPGB_PATH . 'includes/abilities/skills/image-to-page/SKILL.md';
	if ( ! is_readable( $path ) ) {
		return new WP_Error(
			'skill_unreadable',
			__( 'Image-to-page skill guide is missing or unreadable.', 'the-plus-addons-for-block-editor' )
		);
	}
	// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Local plugin file path, not a remote URL.
	return array( 'guide' => (string) file_get_contents( $path ) );
}
