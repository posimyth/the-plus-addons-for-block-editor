<?php
/* Block : TP Column
 * @since : 1.3.0
 */
defined( 'ABSPATH' ) || exit;

function tpgb_tp_switch_inner_render_callback( $attributes, $content) {
	$pattern = '/\bswitch-content-/';
    
	if (preg_match($pattern, $content)) {
		if( class_exists('Tpgb_Blocks_Global_Options') ){
            $global_blocks = Tpgb_Blocks_Global_Options::get_instance();
            $content = $global_blocks::block_row_conditional_render($attributes,$content);
        }
	   return $content;
	}
	$output = '';
	$index = (!empty($attributes['index'])) ? $attributes['index'] : '';

	$output .= '<div class="switch-content-'.esc_attr($index).'">';
		$output .= $content;
	$output .= '</div>';

	return $output;
}

/**
 * Render for the server-side
 */
function tpgb_tp_switch_inner() {
	
	/* $attributesOptions = [
		'block_id' => [
			'type' => 'string',
			'default' => '',
		],
		'className' => [
			'type' => 'string',
			'default' => '',
		],
		'index' => [
			'type' => 'number',
			'default' => '',
		],
	];
		
	$attributesOptions = array_merge( $attributesOptions );
	
	register_block_type( 'tpgb/tp-switch-inner', array(
		'attributes' => $attributesOptions,
		'editor_script' => 'tpgb-block-editor-js',
		'editor_style'  => 'tpgb-block-editor-css',
        'render_callback' => 'tpgb_tp_switch_inner_render_callback'
    ) ); */
	if(method_exists('Tpgb_Blocks_Global_Options', 'merge_options_json')){
		$block_data = Tpgb_Blocks_Global_Options::merge_options_json(__DIR__, 'tpgb_tp_switch_inner_render_callback');
		register_block_type( $block_data['name'], $block_data );
	}
}
add_action( 'init', 'tpgb_tp_switch_inner' );