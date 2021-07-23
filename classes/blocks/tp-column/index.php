<?php
/* Block : Tp Column
 * @since : 1.0.0
 */
function tpgb_tp_section_column_render_callback( $attributes, $content) {
	$output = '';
    $block_id = (!empty($attributes['block_id'])) ? $attributes['block_id'] : uniqid("column");
    $Width = (!empty($attributes['Width'])) ? $attributes['Width'] : [ 'md' => 100, 'sm' => 100, 'xs' => 100 ];
    
	$blockClass = Tp_Blocks_Helper::block_wrapper_classes( $attributes );

	if(!empty($Width)){
		if(!empty($Width['md'])){
			$blockClass .= ' tpgb-md-col-'.intval($Width['md']);
		}
		if(!empty($Width['sm'])){
			$blockClass .= ' tpgb-sm-col-'.intval($Width['sm']);
		}
		if(!empty($Width['xs'])){
			$blockClass .= ' tpgb-xs-col-'.intval($Width['xs']);
		}
	}
	
    $output .= '<div class="tpgb-column tpgb-block-'.esc_attr($block_id).' '.esc_attr($blockClass).'">';
		$output .= '<div class="tpgb-column-wrap">';
			$output .= '<div class="tpgb-column-inner">';
				$output .= $content;
			$output .= "</div>";
		$output .= "</div>";
    $output .= "</div>";

    return $output;
}

/**
 * Render for the server-side
 */
function tpgb_tp_section_column() {
	
	$attributesOptions = [
			'block_id' => [
                'type' => 'string',
				'default' => '',
			],
			'Width' => [
                'type' => 'object',
				'default' => [ 'md' => 50, 'sm' => 50, 'xs' => 100, 'unit' => '%', 'device' => 'md' ],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}:not(.tpgb-column-editor){ width:{{Width}}; }',
					],
				],
			],
			'verticalPosition' => [
                'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} > .tpgb-column-wrap > .tpgb-column-inner{ align-content:{{verticalPosition}} !important; align-items:{{verticalPosition}} !important; }',
					],
				],
			],
			'blockSpace' => [
                'type' => 'object',
				'default' => [ 'md' => '' ],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} > .tpgb-column-wrap > .tpgb-column-inner > *:not(:last-child){ margin-bottom:{{blockSpace}}; }',
					],
				],
			],
			'NormalBg' => [
				'type' => 'object',
				'default' => (object) [
					'openBg'=> 0,
					'bgType' => '',
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} > .tpgb-column-wrap',
					],
				],
			],
			'HoverBg' => [
				'type' => 'object',
				'default' => (object) [
					'openBg'=> 0,
					'bgType' => '',
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}:hover > .tpgb-column-wrap',
					],
				],
			],
			'NormalBorder' => [
				'type' => 'object',
				'default' => (object) [
					'openBorder' => 0,
					'type' => '',
					'color' => '',
					'width' => (object) [
						'md' => (object)[
							'top' => '',
							'bottom' => '',
							'left' => '',
							'right' => '',
						],
						"unit" => "",
					],
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} > .tpgb-column-wrap',
					],
				],
			],
			'HoverBorder' => [
				'type' => 'object',
				'default' => (object) [
					'openBorder' => 0,
					'type' => '',
					'color' => '',
					'width' => (object) [
						'md' => (object)[
							'top' => '',
							'bottom' => '',
							'left' => '',
							'right' => '',
						],
						"unit" => "",
					],
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}:hover > .tpgb-column-wrap',
					],
				],
			],
			'NormalBradius' => [
				'type' => 'object',
				'default' => (object) [ 
					'md' => [
						"top" => '',
						'bottom' => '',
						'left' => '',
						'right' => '',
					],
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} > .tpgb-column-wrap{ border-radius: {{NormalBradius}}; }',
					],
				],
			],
			'HoverBradius' => [
				'type' => 'object',
				'default' => (object) [ 
					'md' => [
						"top" => '',
						'bottom' => '',
						'left' => '',
						'right' => '',
					],
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}:hover > .tpgb-column-wrap{ border-radius: {{HoverBradius}}; }',
					],
				],
			],
			'NormalBShadow' => [
				'type' => 'object',
				'default' => (object) [
					'openShadow' => 0,
					'inset' => 0,
					'horizontal' => 0,
					'vertical' => 4,
					'blur' => 8,
					'spread' => 0,
					'color' => "rgba(0,0,0,0.40)",
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} > .tpgb-column-wrap',
					],
				],
			],
			'HoverBShadow' => [
				'type' => 'object',
				'default' => (object) [
					'openShadow' => 0,
					'inset' => 0,
					'horizontal' => 0,
					'vertical' => 4,
					'blur' => 8,
					'spread' => 0,
					'color' => "rgba(0,0,0,0.40)",
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}:hover > .tpgb-column-wrap',
					],
				],
			],
			'Margin' => [
				'type' => 'object',
				'default' => (object) [ 
					'md' => [
						"top" => '',
						'bottom' => '',
						'left' => '',
						'right' => '',
					],
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} > .tpgb-column-wrap{margin: {{Margin}};}',
					],
				],
			],
			'Padding' => [
				'type' => 'object',
				'default' => (object) [ 
					'md' => [
						"top" => '',
						'bottom' => '',
						'left' => '',
						'right' => '',
					],
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} > .tpgb-column-wrap{padding: {{Padding}} !important;}',
					],
				],
			],
			'ZIndex' => [
				'type' => 'number',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}{z-index: {{ZIndex}};}',
					],
				],
			],
			'hideDesktop' => [
				'type' => 'boolean',
				'default' => false,
				'style' => [
					(object) [
						'selector' => '@media (min-width: 1201px){ .edit-post-visual-editor {{PLUS_WRAP}}{display: block;opacity: .5;} {{PLUS_WRAP}}{ display:none } }',
					],
				],
			],
			'hideTablet' => [
				'type' => 'boolean',
				'default' => false,
				'style' => [
					(object) [
						'selector' => '@media (min-width: 768px) and (max-width: 1200px){ .edit-post-visual-editor {{PLUS_WRAP}}{display: block;opacity: .5;} {{PLUS_WRAP}}{ display:none } }',
					],
				],
			],
			'hideMobile' => [
				'type' => 'boolean',
				'default' => false,
				'style' => [
					(object) [
						'selector' => '@media (max-width: 767px){ .edit-post-visual-editor {{PLUS_WRAP}}{display: block;opacity: .5;} {{PLUS_WRAP}}{ display:none !important; } }',
					],
				],
			],
		];
		
	$attributesOptions = array_merge( $attributesOptions );
	
	register_block_type( 'tpgb/tp-column', array(
		'attributes' => $attributesOptions,
		'editor_script' => 'tpgb-block-editor-js',
		'editor_style'  => 'tpgb-block-editor-css',
        'render_callback' => 'tpgb_tp_section_column_render_callback'
    ) );
}
add_action( 'init', 'tpgb_tp_section_column' );