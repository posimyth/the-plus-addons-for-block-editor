<?php
/* Block : Tp Column
 * @since : 1.1.5
 */
defined( 'ABSPATH' ) || exit;

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
			'horizontalPosition' => [
                'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} > .tpgb-column-wrap > .tpgb-column-inner,{{PLUS_WRAP}} > .tpgb-column-wrap > .tpgb-column-inner > .block-editor-inner-blocks > .block-editor-block-list__layout{ justify-content:{{horizontalPosition}}; }{{PLUS_WRAP}}.tpgb-column-editor > .tpgb-column-wrap > .tpgb-column-inner > .block-editor-inner-blocks > .block-editor-block-list__layout { display: -ms-flexbox;display: flex;-ms-flex-wrap: wrap;flex-wrap: wrap;}',
					],
				],
			],
			'blockSpace' => [
                'type' => 'object',
				'default' => [ 'md' => '' ],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} > .tpgb-column-wrap > .tpgb-column-inner > *:not(:last-child){ margin-bottom:{{blockSpace}}; } {{PLUS_WRAP}} > .tpgb-column-wrap > .tpgb-column-inner > .block-editor-inner-blocks > .block-editor-block-list__layout > .block-editor-block-list__block:not(:nth-last-child(2)){ margin-bottom:{{blockSpace}}; }',
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
				'scopy' => true,
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
				'scopy' => true,
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
				'scopy' => true,
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
				'scopy' => true,
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
				'scopy' => true,
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
				'scopy' => true,
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
				'scopy' => true,
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
				'scopy' => true,
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
				'scopy' => true,
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
				'scopy' => true,
			],
			'ZIndex' => [
				'type' => 'number',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}{z-index: {{ZIndex}};}',
					],
				],
				'scopy' => true,
			],
			'hideDesktop' => [
				'type' => 'boolean',
				'default' => false,
				'style' => [
					(object) [
						'selector' => '@media (min-width: 1201px){ .edit-post-visual-editor {{PLUS_WRAP}}{display: block;opacity: .5;} {{PLUS_WRAP}}{ display:none } }',
					],
				],
				'scopy' => true,
			],
			'hideTablet' => [
				'type' => 'boolean',
				'default' => false,
				'style' => [
					(object) [
						'selector' => '@media (min-width: 768px) and (max-width: 1200px){ .edit-post-visual-editor {{PLUS_WRAP}}{display: block;opacity: .5;} {{PLUS_WRAP}}{ display:none } }',
					],
				],
				'scopy' => true,
			],
			'hideMobile' => [
				'type' => 'boolean',
				'default' => false,
				'style' => [
					(object) [
						'selector' => '@media (max-width: 767px){ .edit-post-visual-editor {{PLUS_WRAP}}{display: block;opacity: .5;} {{PLUS_WRAP}}{ display:none !important; } }',
					],
				],
				'scopy' => true,
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