<?php
/* Block : Tp Image
 * @since : 2.0.0
 */
defined( 'ABSPATH' ) || exit;

function tpgb_tp_image_callback($attr, $content) {
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

function tpgb_tp_image_render() {
    /* $globalBgOption = Tpgb_Blocks_Global_Options::load_bg_options();
    $globalpositioningOption = Tpgb_Blocks_Global_Options::load_positioning_options();
	$globalPlusExtrasOption = Tpgb_Blocks_Global_Options::load_plusextras_options();
    $positionCore = [
        'globalPosition' => [
            'type' => 'object',
            'default' => [ 'md' => '','sm' => '','xs' => '' ],
            'style' => [
                (object) [
                    'selector' => '{{PLUS_CLIENT_ID}}:not(.tpgb-image){ position : {{globalPosition}};width : 100%; }',
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
                    'selector' => '{{PLUS_CLIENT_ID}}:not(.tpgb-image){ left : {{glohoriOffset}};right : auto; }',
                    'backend' => true,
                ],
                (object) [
                    'condition' => [
                        (object) [ 'key' => 'globalPosition', 'relation' => '==', 'value' => ['absolute' , 'fixed'] ],
                        (object) [ 'key' => 'gloabhorizoOri', 'relation' => '==', 'value' => 'right' ]
                    ],
                    'selector' => '{{PLUS_CLIENT_ID}}:not(.tpgb-image){ right : {{glohoriOffset}};left : auto; }',
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
                    'selector' => '{{PLUS_CLIENT_ID}}:not(.tpgb-image){ top : {{gloverticalOffset}}; bottom : auto; }',
                    'backend' => true,
                ],
                (object) [
                    'condition' => [
                        (object) [ 'key' => 'globalPosition', 'relation' => '==', 'value' => ['absolute' , 'fixed'] ],
                        (object) [ 'key' => 'gloabverticalOri', 'relation' => '==', 'value' => 'bottom' ]
                    ],
                    'selector' => '{{PLUS_CLIENT_ID}}:not(.tpgb-image){ bottom : {{gloverticalOffset}}; top : auto; }',
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
        'tImg' => [
            'type' => 'object',
            'default' => [
                'url' => '',
                'Id' => '',
            ],
        ],
        'capimg' => [
            'type' => 'string',
            'default' => '',
        ],
        'ctmCap' => [
            'type' => 'string',
            'default' => '',
        ],
        'tiLink' => [
            'type'=> 'object',
            'default'=> [
                'url' => '',
                'target' => '',
                'nofollow' => ''
            ],
        ],
        'iSize' => [
            'type' => 'string',
            'default' => 'full',
        ],
        'tiAlign' => [
            'type' => 'object',
            'default' => [ 'md' => '', 'sm' =>  '', 'xs' =>  '' ],
            'style' => [
                (object) [
                    'selector' => '{{PLUS_WRAP}}{ text-align: {{tiAlign}}; } {{PLUS_WRAP}} .tpgb-figure{ justify-content: {{tiAlign}}; } {{PLUS_WRAP}} .tpgb-figure .tpgb-img-inner{ justify-self: {{tiAlign}}; }',
                ],
            ],
            'scopy' => true,
        ],
        'tiCap' => [
            'type' => 'string',
            'default' => 'none',
        ],
        'iWidth' => [
            'type' => 'object',
            'default' => [ 
                'md' => '',
                "unit" => 'px',
            ],
            'style' => [
                (object) [
                    'selector' => '{{PLUS_WRAP}} img.tpgb-img-inner{ width: {{iWidth}}; }',
        
                ],
            ],
            'scopy' => true,
        ],
        'imWidth' => [
            'type' => 'object',
            'default' => [ 
                'md' => '',
                "unit" => 'px',
            ],
            'style' => [
                (object) [
                    'selector' => '{{PLUS_WRAP}} img.tpgb-img-inner{ max-width: {{imWidth}}; }',
        
                ],
            ],
            'scopy' => true,
        ],
        'iHeig' => [
            'type' => 'object',
            'default' => [ 
                'md' => '',
                "unit" => 'px',
            ],
            'style' => [
                (object) [
                    'selector' => '{{PLUS_WRAP}} img.tpgb-img-inner{ height: {{iHeig}}; }',
        
                ],
            ],
            'scopy' => true,
        ],
        'imgFit' => [
            'type' => 'string',
	        'default' => '',
            'style' => [
                (object) [
                    'selector' => '{{PLUS_WRAP}} img.tpgb-img-inner{ object-fit: {{imgFit}}; object-position: center center; }',
        
                ],
            ],
            'scopy' => true,
        ],
        'inOpa' => [
            'type' => 'string',
	        'default' => '',
            'style' => [
                (object) [
                    'selector' => '{{PLUS_WRAP}} img.tpgb-img-inner{ opacity: {{inOpa}}; }',
        
                ],
            ],
            'scopy' => true,
        ],
        'iHOpa' => [
            'type' => 'string',
	        'default' => '',
            'style' => [
                (object) [
                    'selector' => '{{PLUS_WRAP}}:hover img.tpgb-img-inner{ opacity: {{iHOpa}}; }',
        
                ],
            ],
            'scopy' => true,
        ],
        'inFilt' => [
            'type' => 'object',
            'default' =>  [
                'openFilter' => false,
            ],
            'style' => [
                (object) [
                    'selector' => '{{PLUS_WRAP}} img.tpgb-img-inner',
                ],
            ],
            'scopy' => true,
        ],
        'iHfilt' => [
            'type' => 'object',
            'default' =>  [
                'openFilter' => false,
            ],
            'style' => [
                (object) [
                    'selector' => '{{PLUS_WRAP}}:hover img.tpgb-img-inner',
                ],
            ],
            'scopy' => true,
        ],
        'intran' => [
            'type' => 'string',
	        'default' => '0.3',
            'style' => [
                (object) [
                    'selector' => '{{PLUS_WRAP}} img.tpgb-img-inner{ transition-duration: {{intran}}s; }',
        
                ],
            ],
            'scopy' => true,
        ],
        'ibord' => [
            'type' => 'object',
            'default' =>  [
                'openBorder' => 0,
            ],
            'style' => [
                (object) [
                    'selector' => '{{PLUS_WRAP}} img.tpgb-img-inner',
                ],
            ],
            'scopy' => true,
        ],
        'ibrad' => [
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
                    'selector' => '{{PLUS_WRAP}} img.tpgb-img-inner{ border-radius : {{ibrad}} } ',
                ],
            ],
            'scopy' => true,
        ],
        'ishadow' => [
            'type' => 'object',
            'default' =>  [
                'openShadow' => 0,
            ],
            'style' => [
                (object) [
                    'selector' => '{{PLUS_WRAP}} img.tpgb-img-inner',
                ],
            ],
            'scopy' => true,
        ],
        'icapTypo' => [
            'type'=> 'object',
            'default'=> (object) [
                'openTypography' => 0,
            ],
            'style' => [
                (object) [
                    'condition' => [(object) ['key' => 'tiCap', 'relation' => '!=', 'value' => 'none' ]],
                    'selector' => '{{PLUS_WRAP}} figcaption.tpgb-image-caption',
                ],
            ],
            'scopy' => true,

        ],
        'icapColor' => [
            'type'=> 'string',
            'default'=> '',
            'style' => [
                (object) [
                    'condition' => [(object) ['key' => 'tiCap', 'relation' => '!=', 'value' => 'none' ]],
                    'selector' => '{{PLUS_WRAP}} figcaption.tpgb-image-caption{ Color : {{icapColor}} }',
                ],
            ],
            'scopy' => true,
        ],
        'icapAlign' => [
            'type' => 'object',
            'default' => [ 'md' => '', 'sm' =>  '', 'xs' =>  '' ],
            'style' => [
                (object) [
                    'condition' => [(object) ['key' => 'tiCap', 'relation' => '!=', 'value' => 'none' ]],
                    'selector' => '{{PLUS_WRAP}} figcaption.tpgb-image-caption{ text-align: {{icapAlign}}; }',
                ],
            ],
            'scopy' => true,
        ],
        'icapbgColor' => [
            'type'=> 'string',
            'default'=> '',
            'style' => [
                (object) [
                    'condition' => [(object) ['key' => 'tiCap', 'relation' => '!=', 'value' => 'none' ]],
                    'selector' => '{{PLUS_WRAP}} figcaption.tpgb-image-caption{ background-Color : {{icapbgColor}} }',
                ],
            ],
            'scopy' => true,
        ],
        'caShadow' => [
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
                    'condition' => [(object) ['key' => 'tiCap', 'relation' => '!=', 'value' => 'none' ]],
                    'selector' => '{{PLUS_WRAP}} figcaption.tpgb-image-caption',
                ],
            ],
            'scopy' => true,
        ],
        'icapSpa' => [
            'type' => 'object',
            'default' => [ 
                'md' => 15,
                "unit" => 'px',
            ],
            'style' => [
                (object) [
                    'condition' => [(object) ['key' => 'tiCap', 'relation' => '!=', 'value' => 'none' ]],
                    'selector' => '{{PLUS_WRAP}} figcaption.tpgb-image-caption{ margin-top : {{icapSpa}} }',
                ],
            ],

        ],
    ];

    $attributesOptions = array_merge($attributesOptions	, $globalBgOption, $globalpositioningOption, $globalPlusExtrasOption,$positionCore);

    register_block_type( 'tpgb/tp-image', array(
		'attributes' => $attributesOptions,
		'editor_script' => 'tpgb-block-editor-js',
		'editor_style'  => 'tpgb-block-editor-css',
        'render_callback' => 'tpgb_tp_image_callback'
    )); */
    $block_data = Tpgb_Blocks_Global_Options::merge_options_json(__DIR__, 'tpgb_tp_image_callback');
	register_block_type( $block_data['name'], $block_data );
}
add_action( 'init', 'tpgb_tp_image_render' );