<?php
/* Block : Core Heading
 * @since : 2.0.0
 */
defined( 'ABSPATH' ) || exit;

function tpgb_tp_core_heading_callback($attr, $content) {
    $pattern = '/\btpgb-wrap-/';
    
    if (preg_match($pattern, $content)) {
        // Check Display Condition
        if( class_exists('Tpgb_Blocks_Global_Options') ){
            $global_blocks = Tpgb_Blocks_Global_Options::get_instance();
            $content = $global_blocks::block_row_conditional_render($attr,$content);
        }
        return $content;
    }
	return Tpgb_Blocks_Global_Options::block_Wrap_Render($attr, $content);
}

function tpgb_tp_core_heading_render() {
    /* $globalBgOption = Tpgb_Blocks_Global_Options::load_bg_options();
    $globalpositioningOption = Tpgb_Blocks_Global_Options::load_positioning_options();
	$globalPlusExtrasOption = Tpgb_Blocks_Global_Options::load_plusextras_options();
    $positionCore = [
        'globalPosition' => [
            'type' => 'object',
            'default' => [ 'md' => '','sm' => '','xs' => '' ],
            'style' => [
                (object) [
                    'selector' => '{{PLUS_CLIENT_ID}}:not(.tp-core-heading){ position : {{globalPosition}};width : 100%; }',
                    'backend' => true,
                ],
            ],
        ],
        'glohoriOffset' => [
            'type' => 'object',
            'default' =>[ 
                'md' => '0',
                "unit" => 'px',
            ],
            'style' => [
                (object) [
                    'condition' => [
                        (object) [ 'key' => 'globalPosition', 'relation' => '==', 'value' => ['absolute' , 'fixed'] ],
                        (object) [ 'key' => 'gloabhorizoOri', 'relation' => '==', 'value' => 'left' ]
                    ],
                    'selector' => '{{PLUS_CLIENT_ID}}:not(.tp-core-heading){ left : {{glohoriOffset}};right : auto; }',
                    'backend' => true,
                ],
                (object) [
                    'condition' => [
                        (object) [ 'key' => 'globalPosition', 'relation' => '==', 'value' => ['absolute' , 'fixed'] ],
                        (object) [ 'key' => 'gloabhorizoOri', 'relation' => '==', 'value' => 'right' ]
                    ],
                    'selector' => '{{PLUS_CLIENT_ID}}:not(.tp-core-heading){ right : {{glohoriOffset}};left : auto; }',
                    'backend' => true,
                ],
            ],
        ],
        'gloverticalOffset' => [
            'type' => 'object',
            'default' => [ 
                'md' => '0',
                "unit" => 'px',
            ],
            'style' => [
                (object) [
                    'condition' => [
                        (object) [ 'key' => 'globalPosition', 'relation' => '==', 'value' => ['absolute' , 'fixed'] ],
                        (object) [ 'key' => 'gloabverticalOri', 'relation' => '==', 'value' => 'top' ]
                    ],
                    'selector' => '{{PLUS_CLIENT_ID}}:not(.tp-core-heading){ top : {{gloverticalOffset}}; bottom : auto; }',
                    'backend' => true,
                ],
                (object) [
                    'condition' => [
                        (object) [ 'key' => 'globalPosition', 'relation' => '==', 'value' => ['absolute' , 'fixed'] ],
                        (object) [ 'key' => 'gloabverticalOri', 'relation' => '==', 'value' => 'bottom' ]
                    ],
                    'selector' => '{{PLUS_CLIENT_ID}}:not(.tp-core-heading){ bottom : {{gloverticalOffset}}; top : auto; }',
                    'backend' => true,
                ],
            ],
        ],
    ];
    $attributesOptions = [
        'block_id' => [
            'type' => 'string',
            'default' => '',
        ],
        'title' => [
            'type' => 'string',
            'default' => 'Add Your Heading Text Here',
        ],
        'tLink' => [
            'type'=> 'object',
            'default'=> [
                'url' => '',
                'target' => '',
                'nofollow' => ''
            ],
        ],
        'tTag' => [
            'type' => 'string',
            'default' => 'h3',
        ],
        'tColor' => [
            'type' => 'string',
            'default' => '',
            'style' => [
                (object) [
                    'selector' => '{{PLUS_WRAP}}.tp-core-heading { color: {{tColor}}; }',
                ],
            ],
            'scopy' => true,
        ],
        'tTypo' => [
            'type'=> 'object',
            'default'=> (object) [
                'openTypography' => 0,
                'size' => [ 'md' => '', 'unit' => 'px' ],
            ],
            'style' => [
                (object) [
                    'selector' => '{{PLUS_WRAP}}.tp-core-heading',
                ],
            ],
            'scopy' => true,
        ],
        'tAlign' => [
            'type' => 'object',
            'default' => [ 'md' => '', 'sm' =>  '', 'xs' =>  '' ],
            'style' => [
                (object) [
                    'selector' => '{{PLUS_WRAP}}{ text-align: {{tAlign}}; }',
                ],
            ],
        ],
        'tStroke' => [
            'type'=> 'object',
            'groupField' => [
                (object) [
                    'tstWidth' => [
                        'type' => 'object',
                        'default' => [ 
                            'md' => '',
                            "unit" => 'px',
                        ],
                        'style' => [
                            (object) [
                                'condition' => [(object) ['key' => 'tpgbReset', 'relation' => '==', 'value' => 1 ]],
                                'selector' => '{{PLUS_WRAP}} { -webkit-text-stroke-width: {{tstWidth}}; stroke-width : {{tstWidth}}; }',
                            ],
                        ],
                        'scopy' => true,
                    ],
                    'tstColor' => [
                        'type' => 'string',
                        'default' => '',
                        'style' => [
                            (object) [
                                'condition' => [(object) ['key' => 'tpgbReset', 'relation' => '==', 'value' => 1 ]],
                                'selector' => '{{PLUS_WRAP}} { -webkit-text-stroke-color: {{tstColor}}; stroke: {{tstColor}}; }',
                            ],
                        ],
                        'scopy' => true,
                    ],
                ],
            ],
            'default' => [ 
                'tpgbReset' => 0 , 
                'tstWidth' => [ 'md' => '' ], 
                'tstColor' => ''
            ],
        ],
        'tShadow' => [
            'type' => 'object',
            'default' => (object) [
                'openShadow' => 0,
                'typeShadow' => 'text-shadow',
                'horizontal' => 2,
                'vertical' => 3,
                'blur' => 2,
                'color' => "rgba(0,0,0,0.5)",
            ],
            'style' => [
                (object) [
                    'selector' => '{{PLUS_WRAP}}',
                ],
            ],
            'scopy' => true,
        ],
        'tblendm' => [
            'type' => 'string',
            'default' => '',
            'style' => [
                (object) [
                    'selector' => '{{PLUS_WRAP}} { mix-blend-mode: {{tblendm}}; }',
                ],
            ],
            'scopy' => true,
        ],
        'anchor' => [
            'type' => 'string',
        ],
    ];

    $attributesOptions = array_merge($attributesOptions	, $globalBgOption, $globalpositioningOption, $globalPlusExtrasOption,$positionCore);

    register_block_type( 'tpgb/tp-heading', array(
		'attributes' => $attributesOptions,
		'editor_script' => 'tpgb-block-editor-js',
		'editor_style'  => 'tpgb-block-editor-css',
        'render_callback' => 'tpgb_tp_core_heading_callback'
    )); */
    $block_data = Tpgb_Blocks_Global_Options::merge_options_json(__DIR__, 'tpgb_tp_core_heading_callback');
	register_block_type( $block_data['name'], $block_data );
}
add_action( 'init', 'tpgb_tp_core_heading_render' );