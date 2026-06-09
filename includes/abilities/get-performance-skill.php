<?php
/**
 * Ability: Return the Performance skill (procedural guide) so MCP clients
 * fetch and follow performance-conscious rules BEFORE any other Nexter
 * Blocks ability is invoked.
 *
 * The MCP client should call this ability FIRST in every Nexter Blocks
 * session — even before nexter-blocks/get-image-to-page-skill or
 * nexter-blocks/inspect-page — so that all subsequent block builds are
 * planned with image weight, font count, animation cost, lazy-loading,
 * caching, and Core Web Vitals impact already considered.
 *
 * @package The_Plus_Addons_For_Block_Editor
 * @since   1.3.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_register_ability(
	'nexter-blocks/get-performance-skill',
	array(
		'label'               => __( 'Get Performance Skill Guide', 'the-plus-addons-for-block-editor' ),
		'description'         => __( 'Returns a procedural guide that enforces performance-conscious rules for every Nexter Blocks page build. CALL THIS FIRST — before nexter-blocks/get-image-to-page-skill, nexter-blocks/inspect-page, or any nexter-blocks/add-tpgb-* ability — at the very start of every session that will create, edit, or recreate a Gutenberg page with Nexter Blocks. The guide enforces an image-weight budget, a Google-Fonts cap, animation/effect limits, correct lazy-loading defaults, and Core Web Vitals (LCP, CLS, INP) checks. Skipping this step is the leading cause of slow pages: heavy hero images, four font families, and four blocks with scroll animations on the same screen.', 'the-plus-addons-for-block-editor' ),
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
					'description' => 'Markdown content of the performance workflow guide. Must be read and followed before any other nexter-blocks/* ability runs.',
				),
			),
		),

		'execute_callback'    => 'tpgb_mcp_get_performance_skill',
		'permission_callback' => 'tpgb_mcp_get_performance_skill_permission',
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
 * Permission callback for the get-performance-skill ability.
 *
 * @param array|null $input Ability input arguments (unused; kept for callback signature).
 * @return bool True when the current user may read the skill guide.
 */
function tpgb_mcp_get_performance_skill_permission( ?array $input = null ): bool {
	unset( $input );
	return current_user_can( 'edit_posts' );
}

/**
 * Execute callback: returns the performance skill guide as Markdown.
 *
 * @param array $input Ability input arguments (unused; kept for callback signature).
 * @return array|WP_Error array{guide:string} on success, WP_Error when the
 *                        bundled SKILL.md is missing or unreadable so the MCP
 *                        client can surface a real failure instead of silently
 *                        treating an empty guide as valid output.
 */
function tpgb_mcp_get_performance_skill( array $input = array() ) {
	unset( $input );
	$path = TPGB_PATH . 'includes/abilities/skills/performance/SKILL.md';
	if ( ! is_readable( $path ) ) {
		return new WP_Error(
			'skill_unreadable',
			__( 'Performance skill guide is missing or unreadable.', 'the-plus-addons-for-block-editor' )
		);
	}
	// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Local plugin file path, not a remote URL.
	return array( 'guide' => (string) file_get_contents( $path ) );
}
