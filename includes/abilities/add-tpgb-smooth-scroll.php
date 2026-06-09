<?php
/**
 * Ability: Add Nexter Blocks Smooth Scroll (tpgb/tp-smooth-scroll) to a Gutenberg page.
 *
 * @package The_Plus_Addons_For_Block_Editor
 * @since   1.3.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_register_ability(
	'nexter-blocks/add-tpgb-smooth-scroll',
	array(
		'label'               => __( 'Add Nexter Blocks Smooth Scroll', 'the-plus-addons-for-block-editor' ),
		'description'         => __( 'Adds the Nexter Blocks Smooth Scroll block (tpgb/tp-smooth-scroll) — a site-wide smooth-scrolling effect controller with configurable animation time, step size, time multiplier, wheel normalisation, easing curve, infinite scroll, smooth navigation, custom cubic-bezier easing, and viewport selector. This block has no visible output — it just applies the scroll effect to the page.', 'the-plus-addons-for-block-editor' ),
		'category'            => 'nexter-blocks',

		'input_schema'        => array(
			'type'                 => 'object',
			'properties'           => array(

				/* ── Core ─────────────────────────────────────────────────── */
				'post_id'         => array(
					'type'        => 'integer',
					'description' => 'WordPress page/post ID to insert the block into.',
				),
				'position'        => array(
					'type'        => 'integer',
					'default'     => -1,
					'description' => 'Zero-based insert position. Use -1 to append.',
				),
				'parent_block_id' => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Optional parent block_id to nest this block inside a container.',
				),

				/* ── Settings ─────────────────────────────────────────────── */
				'animationTime'   => array(
					'type'        => 'string',
					'default'     => '2500',
					'description' => 'Scroll animation duration in ms.',
				),
				'stepSize'        => array(
					'type'        => 'string',
					'default'     => '500',
					'description' => 'Scroll step size in px per wheel tick.',
				),
				'timeMultiplier'  => array(
					'type'        => 'string',
					'default'     => '2',
					'description' => 'Time multiplier for scroll velocity.',
				),
				'normalizeWheel'  => array(
					'type'        => 'boolean',
					'default'     => true,
					'description' => 'Normalise mouse-wheel delta across browsers.',
				),
				'easing'          => array(
					'type'        => 'string',
					'enum'        => array(
						'ease-out-cubic',
						'ease-in-cubic',
						'ease-in-out-cubic',
						'ease-out-quad',
						'ease-in-quad',
						'ease-in-out-quad',
						'ease-out-quart',
						'ease-in-quart',
						'ease-in-out-quart',
						'ease-out-quint',
						'ease-in-quint',
						'ease-in-out-quint',
						'ease-out-expo',
						'ease-in-expo',
						'ease-in-out-expo',
						'ease-out-sine',
						'ease-in-sine',
						'ease-in-out-sine',
						'linear',
						'custom',
					),
					'description' => 'Easing curve for the scroll animation.',
					'default'     => 'ease-out-cubic',
				),
				'customEasing'    => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Custom cubic-bezier value when easing = custom (e.g. "0.25,0.1,0.25,1").',
				),
				'infiniteScroll'  => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Enable continuous/infinite scroll looping.',
				),
				'smoothNav'       => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Smooth-scroll on anchor link (#) clicks.',
				),
				'viewport'        => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'CSS selector to scope smooth scroll (leave blank for whole document).',
				),

				'className'       => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Additional CSS class.',
				),

				'settings'        => array(
					'type'        => 'object',
					'description' => 'Raw attribute overrides merged directly into the block.',
				),
			),
			'required'             => array( 'post_id' ),
			'additionalProperties' => false,
		),

		'output_schema'       => array(
			'type'       => 'object',
			'properties' => array(
				'block_id'   => array( 'type' => 'string' ),
				'block_name' => array( 'type' => 'string' ),
				'post_id'    => array( 'type' => 'integer' ),
			),
		),

		'execute_callback'    => 'tpgb_mcp_add_smooth_scroll_ability',
		'permission_callback' => 'tpgb_mcp_add_smooth_scroll_permission',
		'meta'                => array(
			'show_in_rest' => true,
			'mcp'          => array(
				'public' => true,
				'type'   => 'tool',
			),
		),
	)
);

// -------------------------------------------------------------------------
// PERMISSION / EXECUTE
// -------------------------------------------------------------------------

/**
 * Permission callback for the add-smooth-scroll ability.
 *
 * @param array|null $input Ability input arguments.
 * @return bool True when the current user may insert the block.
 */
function tpgb_mcp_add_smooth_scroll_permission( ?array $input = null ): bool {
	if ( ! current_user_can( 'edit_posts' ) ) {
		return false; }
	$post_id = absint( $input['post_id'] ?? 0 );
	if ( $post_id > 0 && ! current_user_can( 'edit_post', $post_id ) ) {
		return false; }
	return true;
}

/**
 * Execute callback for the add-smooth-scroll ability.
 *
 * @param array $input Ability input arguments.
 * @return array|WP_Error Result payload or WP_Error on failure.
 */
function tpgb_mcp_add_smooth_scroll_ability( array $input ) {

	if ( ! defined( 'TPGB_VERSION' ) && ! defined( 'TPGBP_VERSION' ) ) {
		return new WP_Error( 'nexter_blocks_missing', __( 'Nexter Blocks must be active.', 'the-plus-addons-for-block-editor' ) );
	}

	$block_name = 'tpgb/tp-smooth-scroll';
	if ( ! tpgb_mcp_has_registered_block( $block_name ) ) {
		return new WP_Error( 'block_missing', __( 'tpgb/tp-smooth-scroll is not registered.', 'the-plus-addons-for-block-editor' ) );
	}

	$post_id  = absint( $input['post_id'] ?? 0 );
	$position = intval( $input['position'] ?? -1 );
	if ( $post_id <= 0 ) {
		return new WP_Error( 'missing_params', __( 'post_id is required.', 'the-plus-addons-for-block-editor' ) ); }

	$post = get_post( $post_id );
	if ( ! $post instanceof WP_Post ) {
		return new WP_Error( 'invalid_post', __( 'Target post not found.', 'the-plus-addons-for-block-editor' ) ); }

	$blocks = tpgb_mcp_get_blocks( $post_id );
	if ( is_wp_error( $blocks ) ) {
		return $blocks; }

	$block_id = tpgb_mcp_generate_block_id();
	$attrs    = array( 'block_id' => $block_id );

	/* ── Settings ─────────────────────────────────────────────────────── */
	if ( ! empty( $input['animationTime'] ) && '2500' !== (string) $input['animationTime'] ) {
		$attrs['aniTime'] = sanitize_text_field( (string) $input['animationTime'] ); }
	if ( ! empty( $input['stepSize'] ) && '500' !== (string) $input['stepSize'] ) {
		$attrs['stepSize'] = sanitize_text_field( (string) $input['stepSize'] ); }
	if ( ! empty( $input['timeMultiplier'] ) && '2' !== (string) $input['timeMultiplier'] ) {
		$attrs['tMult'] = sanitize_text_field( (string) $input['timeMultiplier'] ); }
	if ( isset( $input['normalizeWheel'] ) && false === $input['normalizeWheel'] ) {
		$attrs['normalizeWheel'] = false; }
	if ( ! empty( $input['easing'] ) && 'ease-out-cubic' !== $input['easing'] ) {
		$attrs['easing'] = sanitize_text_field( $input['easing'] ); }
	if ( ! empty( $input['customEasing'] ) ) {
		$attrs['custEase'] = sanitize_text_field( $input['customEasing'] ); }
	if ( ! empty( $input['infiniteScroll'] ) ) {
		$attrs['infinite'] = true; }
	if ( ! empty( $input['smoothNav'] ) ) {
		$attrs['smNav'] = true; }
	if ( ! empty( $input['viewport'] ) ) {
		$attrs['viewport'] = sanitize_text_field( $input['viewport'] ); }

	if ( ! empty( $input['className'] ) ) {
		$attrs['className'] = sanitize_text_field( $input['className'] ); }

	/* ── Raw override ─────────────────────────────────────────────────── */
	$attrs = tpgb_mcp_merge_block_settings( $attrs, $input['settings'] ?? array() );

	$block = tpgb_mcp_build_block( $block_name, $attrs );

	$parent_id = ! empty( $input['parent_block_id'] ) ? sanitize_text_field( $input['parent_block_id'] ) : '';
	if ( '' !== $parent_id ) {
		if ( ! tpgb_mcp_insert_inner_block( $blocks, $parent_id, $block, $position ) ) {
			return new WP_Error( 'parent_not_found', __( 'Parent block not found.', 'the-plus-addons-for-block-editor' ) );
		}
	} else {
		tpgb_mcp_insert_block( $blocks, $block, $position );
	}

	$save_result = tpgb_mcp_save_blocks( $post_id, $blocks );
	if ( is_wp_error( $save_result ) ) {
		return $save_result; }

	return array(
		'block_id'   => $block_id,
		'block_name' => $block_name,
		'post_id'    => $post_id,
	);
}
