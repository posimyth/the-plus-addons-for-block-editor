<?php
/**
 * Ability: Return the Typography skill (procedural guide) so MCP clients
 * fetch and follow the correct two-step fontWeight / textDecoration
 * workaround BEFORE adding any typographic Nexter Blocks block.
 *
 * The MCP client should call this ability immediately AFTER
 * nexter-blocks/get-performance-skill — before nexter-blocks/inspect-page
 * or any nexter-blocks/add-tpgb-* ability — so that every block build
 * involving typography uses the correct tTypo (camelCase) structure via
 * sprout/update-element instead of the lowercased settings override.
 *
 * @package The_Plus_Addons_For_Block_Editor
 * @since   1.3.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_register_ability(
	'nexter-blocks/get-typography-skill',
	array(
		'label'               => __( 'Get Typography Skill Guide', 'the-plus-addons-for-block-editor' ),
		'description'         => __( 'Returns the authoritative recipe for applying fontWeight and textDecoration on any Nexter Blocks typographic block. CALL THIS RIGHT AFTER nexter-blocks/get-performance-skill — before nexter-blocks/inspect-page or any nexter-blocks/add-tpgb-* ability — whenever the build involves a non-default font weight, any text decoration, or any typography setting beyond plain fontFamily. Every typography-bearing add-tpgb-* ability now accepts fontWeight ("100"…"900") and textDecoration (none / underline / overline / line-through) as proper TOP-LEVEL parameters — pass them directly. For multi-typo-group blocks (infobox, pricing-table, accordion, etc.) the top-level values apply uniformly to every typography group on the block. Use the settings raw override (camelCase keys are preserved) or sprout/update-element only when different groups must have different settings. The guide also pins the exact tTypo JSON shape — fontWeight nested inside fontFamily, textDecoration sibling of fontFamily — and lists the AI mistakes (weight outside fontFamily, font-weight key, partial typo objects) to avoid.', 'the-plus-addons-for-block-editor' ),
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
					'description' => 'Markdown content of the typography workflow guide. Must be read and followed before any nexter-blocks/add-tpgb-* ability that involves fontWeight or textDecoration.',
				),
			),
		),

		'execute_callback'    => 'tpgb_mcp_get_typography_skill',
		'permission_callback' => 'tpgb_mcp_get_typography_skill_permission',
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
 * Permission callback for the get-typography-skill ability.
 *
 * @param array|null $input Ability input arguments (unused; kept for callback signature).
 * @return bool True when the current user may read the skill guide.
 */
function tpgb_mcp_get_typography_skill_permission( ?array $input = null ): bool {
	unset( $input );
	return current_user_can( 'edit_posts' );
}

/**
 * Execute callback: returns the typography skill guide as Markdown.
 *
 * @param array $input Ability input arguments (unused; kept for callback signature).
 * @return array|WP_Error array{guide:string} on success, WP_Error when the
 *                        bundled typography-settings.md is missing or unreadable
 *                        so the MCP client can surface a real failure instead
 *                        of silently treating an empty guide as valid output.
 */
function tpgb_mcp_get_typography_skill( array $input = array() ) {
	unset( $input );
	$path = TPGB_PATH . 'includes/abilities/skills/typography-settings.md';
	if ( ! is_readable( $path ) ) {
		return new WP_Error(
			'skill_unreadable',
			__( 'Typography skill guide is missing or unreadable.', 'the-plus-addons-for-block-editor' )
		);
	}
	// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Local plugin file path, not a remote URL.
	return array( 'guide' => (string) file_get_contents( $path ) );
}
