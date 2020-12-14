<?php


/**
 * After rendring from the block editor display output on front-end
 */
function tpgb_tp_empty_space_render_callback( $attributes, $content) {
	$output = '';
    $block_id = (!empty($attributes['block_id'])) ? $attributes['block_id'] : uniqid("title");
	$className = (!empty($attributes['className'])) ? $attributes['className'] :'';
	$align = (!empty($attributes['align'])) ? $attributes['align'] :'';
	
	$blockClass = '';
	if(!empty($className)){
		$blockClass .= $className;
	}
	if(!empty($align)){
		$blockClass .= ' align'.$align;
	}
	
    $output .= '<div class="tpgb-empty-space tpgb-block-'.esc_attr($block_id).' '.esc_attr($blockClass).'">';
    $output .= '</div>';
  
    return $output;
}

/**
 * Render for the server-side
 */
function tpgb_tp_empty_space() {
  register_block_type( 'tpgb/tp-empty-space', array(
		'attributes' => array(
			'block_id' => array(
                'type' => 'string',
				'default' => '',
			),
			'toggle' => [
				'type' => 'string',
				'default' => 'normal',
			],
			'space' => [
			    'type' => 'object',
				'default' => [ 'md' => 50 ],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'toggle', 'relation' => '==', 'value' => 'normal' ]],
						'selector' => '{{PLUS_WRAP}}{height: {{space}}px;}',
					],
					(object) [
						'condition' => [(object) ['key' => 'toggle', 'relation' => '==', 'value' => 'global' ]],
						'selector' => '{{PLUS_WRAP}}{height: {{space}};}',
					],
				],
			],
		),
		'editor_script' => 'tpgb-block-editor-js',
		'editor_style'  => 'tpgb-block-editor-css',
        'render_callback' => 'tpgb_tp_empty_space_render_callback'
    ) );
}
add_action( 'init', 'tpgb_tp_empty_space' );