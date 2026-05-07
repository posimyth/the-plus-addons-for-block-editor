<?php
/**
 * Global Settings.
 *
 * @package ThePluginAddonsForBlockEditor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Tpgb global settings render.
 */
function tpgb_global_settings_render() {
	$attributes_options = array(
		'block_id'     => array(
			'type'    => 'string',
			'default' => '',
		),
		'PresetColor1' => array(
			'type'    => 'string',
			'default' => '#8072FC',
		),
		'PresetColor2' => array(
			'type'    => 'string',
			'default' => '#6FC784',
		),
		'PresetColor3' => array(
			'type'    => 'string',
			'default' => '#FF5A6E',
		),
		'PresetColor4' => array(
			'type'    => 'string',
			'default' => '#F3F3F3',
		),
		'PresetColor5' => array(
			'type'    => 'string',
			'default' => '#888888',
		),
		'PresetColor6' => array(
			'type'    => 'string',
			'default' => '#FFFFFF',
		),
	);

	register_block_type(
		'tpgb/tpgb-settings',
		array(
			'attributes'      => $attributes_options,
			'editor_script'   => 'tpgb-block-editor-js',
			'editor_style'    => 'tpgb-block-editor-css',
			'render_callback' => 'tpgb_global_settings_callback',
		)
	);
}
add_action( 'init', 'tpgb_global_settings_render' );

/**
 * After rendering from the block editor display output on front-end
 *
 * @param mixed $attributes The attributes.
 * @param mixed $content The content.
 */
function tpgb_global_settings_callback( $attributes, $content ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
	$output   = '';
	$block_id = ( ! empty( $attributes['block_id'] ) ) ? $attributes['block_id'] : uniqid( 'title' );

	return $output;
}
