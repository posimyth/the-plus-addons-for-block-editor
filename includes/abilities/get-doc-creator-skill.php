<?php
/**
 * Ability: Return the Nexter Doc Post Creator skill (procedural guide) so MCP
 * clients can fetch it and follow the documented workflow when the user asks
 * to write documentation for an existing Nexter Blocks feature.
 *
 * The MCP client should call this ability whenever the user requests a new
 * Nexter Blocks documentation post — e.g. "create a doc post for [block]",
 * "write a Nexter doc page", "build a documentation post", "make a tutorial
 * post for [block]". The returned guide enforces the canonical section order,
 * Nexter-only widgets, and image-placeholder workflow.
 *
 * @package The_Plus_Addons_For_Block_Editor
 * @since   1.3.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_register_ability(
	'nexter-blocks/get-doc-creator-skill',
	array(
		'label'               => __( 'Get Nexter Doc Post Creator Skill Guide', 'the-plus-addons-for-block-editor' ),
		'description'         => __( 'Returns a procedural guide for creating a structured Nexter Blocks documentation post in WordPress. Call this whenever the user asks to create, write, build, or publish documentation / a tutorial / a how-to post for an existing Nexter Blocks feature or block — e.g. "create a doc post for [block name]", "write a Nexter doc page", "build a documentation post", "make a tutorial post for [block]". The returned guide enforces the canonical section order (Breadcrumb → Key Takeaways → TOC → Intro → CTA → Required Setup → Video → Activation → Key Features → Dynamic Sections → Styling), uses only Nexter Blocks widgets (never Elementor), leaves image placeholders for manual screenshot upload, and skips optional sections cleanly when their inputs are absent. Skipping this step leads to inconsistent doc structure, missing sections, and accidental use of non-Nexter blocks.', 'the-plus-addons-for-block-editor' ),
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
					'description' => 'Markdown content of the Nexter Doc Post Creator workflow guide.',
				),
			),
		),

		'execute_callback'    => 'tpgb_mcp_get_doc_creator_skill',
		'permission_callback' => 'tpgb_mcp_get_doc_creator_skill_permission',
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
 * Permission callback for the get-doc-creator-skill ability.
 *
 * @param array|null $input Ability input arguments (unused; kept for callback signature).
 * @return bool True when the current user may read the skill guide.
 */
function tpgb_mcp_get_doc_creator_skill_permission( ?array $input = null ): bool {
	unset( $input );
	return current_user_can( 'edit_posts' );
}

/**
 * Execute callback: returns the Nexter Doc Post Creator skill guide as Markdown.
 *
 * @param array $input Ability input arguments (unused; kept for callback signature).
 * @return array|WP_Error array{guide:string} on success, WP_Error when the
 *                        bundled SKILL file is missing or unreadable so the
 *                        MCP client can surface a real failure instead of
 *                        silently treating an empty guide as valid output.
 */
function tpgb_mcp_get_doc_creator_skill( array $input = array() ) {
	unset( $input );
	$path = TPGB_PATH . 'includes/abilities/skills/nxt-documentation/SKILL_DocCreate_NxtAbi.md';
	if ( ! is_readable( $path ) ) {
		return new WP_Error(
			'skill_unreadable',
			__( 'Doc creator skill guide is missing or unreadable.', 'the-plus-addons-for-block-editor' )
		);
	}
	// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Local plugin file path, not a remote URL.
	return array( 'guide' => (string) file_get_contents( $path ) );
}
