<?php
/* Block : Core Button
 * @since : 2.0.0
 */
defined( 'ABSPATH' ) || exit;


function tpgb_tp_button_core_render($attr, $content) {
    $pattern = '/\btpgb-wrap-/';

    if (preg_match($pattern, $content)) {
        if( class_exists('Tpgb_Blocks_Global_Options') ){
            $global_blocks = Tpgb_Blocks_Global_Options::get_instance();
            $content = $global_blocks::block_row_conditional_render($attr,$content);
        }
       return $content;
    }
    
    return Tpgb_Blocks_Global_Options::block_Wrap_Render($attr, $content);
}

function tpgb_tp_button_core() {
    /* $globalBgOption = Tpgb_Blocks_Global_Options::load_bg_options();
    $globalpositioningOption = Tpgb_Blocks_Global_Options::load_positioning_options();
	$globalPlusExtrasOption = Tpgb_Blocks_Global_Options::load_plusextras_options();
    $positionCore = [
        'globalPosition' => [
            'type' => 'object',
            'default' => [ 'md' => '','sm' => '','xs' => '' ],
            'style' => [
                (object) [
                    'selector' => '{{PLUS_CLIENT_ID}}:not(.tp-button-core){ position : {{globalPosition}};width : 100%; }',
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
                    'selector' => '{{PLUS_CLIENT_ID}}:not(.tp-button-core){ left : {{glohoriOffset}};right : auto; }',
                    'backend' => true,
                ],
                (object) [
                    'condition' => [
                        (object) [ 'key' => 'globalPosition', 'relation' => '==', 'value' => ['absolute' , 'fixed'] ],
                        (object) [ 'key' => 'gloabhorizoOri', 'relation' => '==', 'value' => 'right' ]
                    ],
                    'selector' => '{{PLUS_CLIENT_ID}}:not(.tp-button-core){ right : {{glohoriOffset}};left : auto; }',
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
                    'selector' => '{{PLUS_CLIENT_ID}}:not(.tp-button-core){ top : {{gloverticalOffset}}; bottom : auto; }',
                    'backend' => true,
                ],
                (object) [
                    'condition' => [
                        (object) [ 'key' => 'globalPosition', 'relation' => '==', 'value' => ['absolute' , 'fixed'] ],
                        (object) [ 'key' => 'gloabverticalOri', 'relation' => '==', 'value' => 'bottom' ]
                    ],
                    'selector' => '{{PLUS_CLIENT_ID}}:not(.tp-button-core){ bottom : {{gloverticalOffset}}; top : auto; }',
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
        'btxt' => [
            'type' => 'string',
            'default' => 'Click Here',
        ],
        'bLink' => [
            'type'=> 'object',
            'default'=> [
                'url' => '#',
                'target' => '',
                'nofollow' => ''
            ],
        ],
        'bAlign' => [
            'type' => 'object',
            'default' => [ 'md' => '', 'sm' =>  '', 'xs' =>  '' ],
            'style' => [
                (object) [
                    'selector' => '{{PLUS_WRAP}}{ text-align: {{bAlign}}; }',
                ],
            ],
            'scopy' => true,
        ],
        'biType' => [
            'type' => 'string',
            'default' => 'none',
        ],
        'bIcon' => [
            'type' => 'string',
            'default' => '',
        ],
        'bImg' => [
            'type' => 'object',
            'default' => [
                'url' => '',
                'Id' => '',
            ],
        ],
        'bipos' => [
            'type' => 'string',
            'default' => '',
            'style' => [
                (object) [
                    'condition' => [(object) ['key' => 'biType', 'relation' => '!=', 'value' => 'none']],
                    'selector' => '{{PLUS_WRAP}} .tpgb-btn-icon-wrap { order: {{bipos}}; }',
                ],
            ],
            'scopy' => true,
        ],
        'bispac' => [
            'type' => 'object',
            'default' => [ 
                'md' => '',
                "unit" => 'px',
            ],
            'style' => [
                (object) [
                    'condition' => [(object) ['key' => 'biType', 'relation' => '!=', 'value' => 'none']],
                    'selector' => '{{PLUS_WRAP}} .tpgb-btn-wrap{ column-gap: {{bispac}}; }',
                ],
            ],
            'scopy' => true,
        ],
        'bTypo' => [
            'type'=> 'object',
            'default'=> (object) [
                'openTypography' => 0,
            ],
            'style' => [
                (object) [
                    'selector' => '{{PLUS_WRAP}} .tpgb-btn-wrap',
                ],
            ],
            'scopy' => true,
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
                    'selector' => '{{PLUS_WRAP}} .tpgb-btn-txt',
                ],
            ],
        ],
        'btColor' => [
            'type' => 'string',
            'default' => '#fff',
            'style' => [
                (object) [
                    'selector' => '{{PLUS_WRAP}} .tpgb-btn-wrap{ color : {{btColor}} }',
                ],
            ],
            'scopy' => true,
        ],
        'bthColor' => [
            'type' => 'string',
            'default' => '',
            'style' => [
                (object) [
                    'selector' => '{{PLUS_WRAP}} .tpgb-btn-wrap:hover{ color : {{bthColor}} }',
                ],
            ],
            'scopy' => true,
        ],
        'btBg' => [
            'type' => 'object',
            'default' => (object) [
                'openBg'=> 1,
                'bgType' => 'color',
                'bgDefaultColor' => '#6f14f1',
            ],
            'style' => [
                (object) [
                    'selector' => '{{PLUS_WRAP}} .tpgb-btn-wrap',
                ],
            ],
            'scopy' => true,
        ],
        'bthBg' => [
            'type' => 'object',
            'default' => (object) [
                'openBg'=> 0,
            ],
            'style' => [
                (object) [
                    'selector' => '{{PLUS_WRAP}} .tpgb-btn-wrap:hover',
                ],
            ],
            'scopy' => true,
        ],
        'bBord' => [
            'type' => 'object',
            'default' => (object) [
                'openBorder' => 0,
            ],
            'style' => [
                (object) [
                    'selector' => '{{PLUS_WRAP}} .tpgb-btn-wrap',
                ],
            ],
            'scopy' => true,
        ],
        'bthBColor' => [
            'type' => 'string',
            'default' => '',
            'style' => [
                (object) [
                    'selector' => '{{PLUS_WRAP}} .tpgb-btn-wrap:hover{ border-color : {{bthBColor}} }',
                ],
            ],
            'scopy' => true,
        ],
        'brad' => [
            'type' => 'object',
            'default' => (object) [
                'md' => ['top' => '', 'bottom' => '', 'left' => '', 'right' => ''],
                "unit" => 'px',
            ],
            'style' => [
                (object) [
                    'selector' => '{{PLUS_WRAP}} .tpgb-btn-wrap{ border-radius : {{brad}} }',
                ]
            ],
            'scopy' => true,
        ],
        'btPad' => [
            'type' => 'object',
            'default' => (object) [
                'md' => ['top' => '', 'bottom' => '', 'left' => '', 'right' => ''],
                "unit" => 'px',
            ],
            'style' => [
                (object) [
                    'selector' => '{{PLUS_WRAP}} .tpgb-btn-wrap{ padding : {{btPad}} }',
                ]
            ],
            'scopy' => true,
        ],
        'biSize' => [
            'type' => 'object',
            'default' => [ 
                'md' => '',
                "unit" => 'px',
            ],
            'style' => [
                (object) [
                    'condition' => [(object) ['key' => 'biType', 'relation' => '==', 'value' => 'fontAwesome']],
                    'selector' => '{{PLUS_WRAP}} .tpgb-btn-icon { font-size: {{biSize}}; }',
                ],
                (object) [
                    'condition' => [(object) ['key' => 'biType', 'relation' => '==', 'value' => 'image']],
                    'selector' => '{{PLUS_WRAP}} img.tpgb-btn-icon { max-width: {{biSize}}; }',
                ],
            ],
            'scopy' => true,
        ],
        'btshadow' => [
            'type' => 'object',
            'default' => (object) [
                'openShadow' => 0,
            ],
            'style' => [
                (object) [
                    'selector' => '{{PLUS_WRAP}} .tpgb-btn-wrap',
                ],
            ],
            'scopy' => true,
        ],
    ];

    $attributesOptions = array_merge($attributesOptions	, $globalBgOption, $globalpositioningOption, $globalPlusExtrasOption,$positionCore);

    register_block_type( 'tpgb/tp-button-core', array(
		'attributes' => $attributesOptions,
		'editor_script' => 'tpgb-block-editor-js',
		'editor_style'  => 'tpgb-block-editor-css',
        'render_callback' => 'tpgb_tp_button_core_render'
    )); */
    $block_data = Tpgb_Blocks_Global_Options::merge_options_json(__DIR__, 'tpgb_tp_button_core_render');
	register_block_type( $block_data['name'], $block_data );
}
add_action( 'init', 'tpgb_tp_button_core' );