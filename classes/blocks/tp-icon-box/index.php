<?php
/* Block : Core Icon Box
 * @since : 2.0.0
 */
defined( 'ABSPATH' ) || exit;

function tpgb_tp_icon_box_callback($attr, $content) {
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

function tpgb_tp_icon_box_render() {
    /* $globalBgOption = Tpgb_Blocks_Global_Options::load_bg_options();
    $globalpositioningOption = Tpgb_Blocks_Global_Options::load_positioning_options();
	$globalPlusExtrasOption = Tpgb_Blocks_Global_Options::load_plusextras_options();
    $positionCore = [
        'globalPosition' => [
            'type' => 'object',
            'default' => [ 'md' => '','sm' => '','xs' => '' ],
            'style' => [
                (object) [
                    'selector' => '{{PLUS_CLIENT_ID}}:not(.tpgb-icon-box){ position : {{globalPosition}};width : 100%; }',
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
                    'selector' => '{{PLUS_CLIENT_ID}}:not(.tpgb-icon-box){ left : {{glohoriOffset}};right : auto; }',
                    'backend' => true,
                ],
                (object) [
                    'condition' => [
                        (object) [ 'key' => 'globalPosition', 'relation' => '==', 'value' => ['absolute' , 'fixed'] ],
                        (object) [ 'key' => 'gloabhorizoOri', 'relation' => '==', 'value' => 'right' ]
                    ],
                    'selector' => '{{PLUS_CLIENT_ID}}:not(.tpgb-icon-box){ right : {{glohoriOffset}};left : auto; }',
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
                    'selector' => '{{PLUS_CLIENT_ID}}:not(.tpgb-icon-box){ top : {{gloverticalOffset}}; bottom : auto; }',
                    'backend' => true,
                ],
                (object) [
                    'condition' => [
                        (object) [ 'key' => 'globalPosition', 'relation' => '==', 'value' => ['absolute' , 'fixed'] ],
                        (object) [ 'key' => 'gloabverticalOri', 'relation' => '==', 'value' => 'bottom' ]
                    ],
                    'selector' => '{{PLUS_CLIENT_ID}}:not(.tpgb-icon-box){ bottom : {{gloverticalOffset}}; top : auto; }',
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
        'icon' => [
            'type' => 'string',
            'default' => 'fontAwesome',
        ],
        'fIcon' => [
            'type' => 'string',
            'default' => 'fas fa-star',
        ],
        'isvg' => [
            'type' => 'object',
            'default' => [
                'url' => ''
            ],
        ],
        'iAlign' => [
            'type' => 'object',
            'default' => [ 'md' => '', 'sm' =>  '', 'xs' =>  '' ],
            'style' => [
                (object) [
                    'selector' => '{{PLUS_WRAP}}{ text-align: {{iAlign}}; }',
                ],
            ],
            'scopy' => true,
        ],
        'anchor' => [
            'type' => 'string',
        ],
        'iview' => [
            'type' => 'string',
            'default' => 'default',
        ],
        'iLink' => [
            'type'=> 'object',
            'default'=> [
                'url' => '',
                'target' => '',
                'nofollow' => ''
            ],
        ],
        'iColor' => [
            'type' => 'string',
            'default' => '',
            'style' => [
                (object) [
                    'condition' => [(object) ['key' => 'iview', 'relation' => '==', 'value' => 'default']],
                    'selector' => '{{PLUS_WRAP}} .tpgb-nicon-wrap{ color : {{iColor}}; }',
                ],
                (object) [
                    'condition' => [(object) ['key' => 'iview', 'relation' => '==', 'value' => 'stacked']],
                    'selector' => '{{PLUS_WRAP}} .tpgb-nicon-wrap{ background-color : {{iColor}}; }',
                ],
                (object) [
                    'condition' => [(object) ['key' => 'iview', 'relation' => '==', 'value' => 'framed']],
                    'selector' => '{{PLUS_WRAP}} .tpgb-nicon-wrap{ color : {{iColor}};border-color : {{iColor}}; }',
                ],
            ],
            'scopy' => true,
        ],
        'ihColor' => [
            'type' => 'string',
            'default' => '',
            'style' => [
                (object) [
                    'condition' => [(object) ['key' => 'iview', 'relation' => '==', 'value' => 'default']],
                    'selector' => '{{PLUS_WRAP}} .tpgb-nicon-wrap:hover{ color : {{ihColor}}; }',
                ],
                (object) [
                    'condition' => [(object) ['key' => 'iview', 'relation' => '==', 'value' => 'stacked']],
                    'selector' => '{{PLUS_WRAP}} .tpgb-nicon-wrap:hover{ background-color : {{ihColor}}; }',
                ],
                (object) [
                    'condition' => [(object) ['key' => 'iview', 'relation' => '==', 'value' => 'framed']],
                    'selector' => '{{PLUS_WRAP}} .tpgb-nicon-wrap:hover{ color : {{ihColor}};;border-color : {{ihColor}}; }',
                ],
            ],
            'scopy' => true,
        ],
        'ioSize' => [
            'type' => 'object',
            'default' => [ 
                'md' => '',
                "unit" => 'px',
            ],
            'style' => [
                (object) [
                    'condition' => [(object) ['key' => 'icon', 'relation' => '==', 'value' => 'fontAwesome']],
                    'selector' => '{{PLUS_WRAP}} .tpgb-nicon{ font-size: {{ioSize}}; }',
                ],
                (object) [
                    'condition' => [(object) ['key' => 'icon', 'relation' => '==', 'value' => 'Ctm-svg']],
                    'selector' => '{{PLUS_WRAP}} img.tpgb-nicon{ max-width : {{ioSize}}; width: 100%; }',
                ],
            ],
            'scopy' => true,
        ],
        'ioRotate' => [
            'type' => 'object',
            'default' => [ 
                'md' => '',
                "unit" => 'deg',
            ],
            'style' => [
                (object) [
                    'selector' => '{{PLUS_WRAP}} .tpgb-nicon{ transform: rotate({{ioRotate}}); }',
                ],
            ],
            'scopy' => true,
        ],
        'iseColor' => [
            'type' => 'string',
            'default' => '',
            'style' => [
                (object) [
                    'condition' => [(object) ['key' => 'iview', 'relation' => '==', 'value' => 'stacked']],
                    'selector' => '{{PLUS_WRAP}} .tpgb-nicon-wrap{ color : {{iseColor}}; }',
                ],
                (object) [
                    'condition' => [(object) ['key' => 'iview', 'relation' => '==', 'value' => 'framed']],
                    'selector' => '{{PLUS_WRAP}} .tpgb-nicon-wrap{ background-color : {{iseColor}}; }',
                ],
            ],
        ],
        'isehColor' => [
            'type' => 'string',
            'default' => '',
            'style' => [
                (object) [
                    'condition' => [(object) ['key' => 'iview', 'relation' => '==', 'value' => 'stacked']],
                    'selector' => '{{PLUS_WRAP}} .tpgb-nicon-wrap:hover{ color : {{isehColor}}; }',
                ],
                (object) [
                    'condition' => [(object) ['key' => 'iview', 'relation' => '==', 'value' => 'framed']],
                    'selector' => '{{PLUS_WRAP}} .tpgb-nicon-wrap:hover{ background-color : {{isehColor}}; }',
                ],
            ],
        ],
        'iopadd' => [
            'type' => 'object',
            'default' => [ 
                'md' => '',
                "unit" => 'px',
            ],
            'style' => [
                (object) [
                    'selector' => '{{PLUS_WRAP}} .tpgb-nicon-wrap{ padding: {{iopadd}}; }',
                ],
            ],
            'scopy' => true,
        ],
        'ioBor' => [
            'type' => 'object',
            'default' => (object) [
                'openBorder' => 0,
                'type' => '',
                'color' => '',
            ],
            'style' => [
                (object) [
                    'condition' => [(object) ['key' => 'iview', 'relation' => '==', 'value' => 'framed']],
                    'selector' => '{{PLUS_WRAP}} .tpgb-nicon-wrap',
                ],
            ],
            'scopy' => true,
        ],
        'ioBRed' => [
            'type' => 'object',
            'default' => (object) [ 
                'md' => [
                    "top" => '',
                    "right" => '',
                    "bottom" => '',
                    "left" => '',
                ],
                "unit" => 'px',
            ],
            'style' => [
                (object) [
                    'selector' => '{{PLUS_WRAP}} .tpgb-nicon-wrap{ border-radius : {{ioBRed}}; }',
                ],
            ],
            'scopy' => true,
        ],
    ];

    $attributesOptions = array_merge($attributesOptions	, $globalBgOption, $globalpositioningOption, $globalPlusExtrasOption,$positionCore);

    register_block_type( 'tpgb/tp-icon-box', array(
		'attributes' => $attributesOptions,
		'editor_script' => 'tpgb-block-editor-js',
		'editor_style'  => 'tpgb-block-editor-css',
        'render_callback' => 'tpgb_tp_icon_box_callback'
    )); */
    $block_data = Tpgb_Blocks_Global_Options::merge_options_json(__DIR__, 'tpgb_tp_icon_box_callback');
	register_block_type( $block_data['name'], $block_data );
}
add_action( 'init', 'tpgb_tp_icon_box_render' );