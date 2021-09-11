<?php
/* Block : BlockQuote
 * @since : 1.1.1
 */
defined( 'ABSPATH' ) || exit;

function tpgb_tp_blockquote_callback($attributes, $content) {
	$output = '';
    $block_id = (!empty($attributes['block_id'])) ? $attributes['block_id'] : uniqid("title");
	
	$blockClass = Tp_Blocks_Helper::block_wrapper_classes( $attributes );
	
    $output ='<div class="tp-blockquote tpgb-block-'.esc_attr($block_id).' '.esc_attr($blockClass).'">';
        $output .='<div class="tpgb-blockquote-inner tpgb-quote-'.esc_attr($attributes['style']).'">';
            if($attributes['style'] == 'style-2') {
                $output .= '<span class="tpgb-quote-left"><i class="fa fa-quote-left" aria-hidden="true"></i></span>';
            }
            $output .= '<blockquote class="tpgb-quote-text">';
            $output .= '<div class="quote-text-wrap">'.wp_kses_post($content).'</div>';
            if($attributes['style'] == 'style-2' && !empty($attributes['authorName'])) {
                $output .= '<div class="tpgb-quote-author">'.esc_html($attributes['authorName']).'</div>';
            }
            $output .= '</blockquote>';
        $output .='</div>';
    $output .='</div>';
	
	$output = Tpgb_Blocks_Global_Options::block_Wrap_Render($attributes, $output);
	
    return $output;
}

function tpgb_tp_blockquote_render() {
    $globalBgOption = Tpgb_Blocks_Global_Options::load_bg_options();
    $globalpositioningOption = Tpgb_Blocks_Global_Options::load_positioning_options();
	$globalPlusExtrasOption = Tpgb_Blocks_Global_Options::load_plusextras_options();
    $attributesOptions = [
        'block_id' => [
            'type' => 'string',
            'default' => '',
        ],
        'style' => [
            'type' => 'string',
            'default' => 'style-1',
        ],
        'content' => [
            'type' => 'string',
            'source' => 'html',
            'selector' => 'span',
            'default' => 'Do everything you have to do, but not with ego, not with lust, not with envy but with love, compassion, humility, and devotion.'
        ],
        'authorName' => [
            'type' => 'string',
            'default' => 'Lord Krishna',
        ],
        'contentAlignment' => [
            'type' => 'string',
            'default' => '',
            'style' => [
                (object) [
                    'selector' => '{{PLUS_WRAP}} .tpgb-blockquote-inner div{text-align: {{contentAlignment}}; }',
                ],
            ],
        ],
        'typography' => [
            'type' => 'object',
            'default' => (object) [
                'openTypography' => 0,
                'size' => [ 'md' => '', 'unit' => 'px' ],
            ],
            'style' => [
                (object) [
                    'selector' => '{{PLUS_WRAP}} .tpgb-blockquote-inner blockquote.tpgb-quote-text > span,{{PLUS_WRAP}} .tpgb-blockquote-inner blockquote.tpgb-quote-text',
                ],
            ],
        ],
        'textNormalColor' => [
            'type' => 'string',
            'default' => '',
            'style' => [
                (object) [
                    'selector' => '{{PLUS_WRAP}} .tpgb-blockquote-inner .tpgb-quote-text{color: {{textNormalColor}};}',
                ],
            ],
        ],
        'textHoverColor' => [
            'type' => 'string',
            'default' => '',
            'style' => [
                (object) [
                    'selector' => '{{PLUS_WRAP}} .tpgb-blockquote-inner .tpgb-quote-text:hover{color: {{textHoverColor}};}',
                ],
            ],
        ],
        'authorNormalColor' => [
            'type' => 'string',
            'default' => '',
            'style' => [
                (object) [
                    'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-2']],
                    'selector' => '{{PLUS_WRAP}} .tpgb-blockquote-inner .tpgb-quote-text .tpgb-quote-author{color: {{authorNormalColor}};}',
                ],
            ],
        ],
        'authorHoverColor' => [
            'type' => 'string',
            'default' => '',
            'style' => [
                (object) [
                    'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-2']],
                    'selector' => '{{PLUS_WRAP}} .tpgb-blockquote-inner .tpgb-quote-text .tpgb-quote-author:hover{color: {{authorHoverColor}};}',
                ],
            ],
        ],
        'quoteColor' => [
            'type' => 'string',
            'default' => '',
            'style' => [
                (object) [
                    'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-2']],
                    'selector' => '{{PLUS_WRAP}} .tpgb-blockquote-inner .tpgb-quote-left{color: {{quoteColor}};}',
                ],
            ],
        ],
        'boxPadding' => [
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
                    'selector' => '{{PLUS_WRAP}} .tpgb-blockquote-inner{padding: {{boxPadding}};}',
                ],
            ],
        ],
        'boxMargin' => [
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
                    'selector' => '{{PLUS_WRAP}} .tpgb-blockquote-inner{margin: {{boxMargin}};}',
                ],
            ],
        ],
        'borderNormal' => [
            'type' => 'object',
            'default' => (object) [
                'openBorder' => 0,
                'type' => '',
                    'color' => '',
                'width' => (object) [
                    'md' => (object)[
                        'top' => '',
                        'left' => '',
                        'bottom' => '',
                        'right' => '',
                    ],
                    "unit" => "",
                ],
            ],
            'style' => [
                (object) [
                    'selector' => '{{PLUS_WRAP}} .tpgb-blockquote-inner',
                ],
            ],
        ],
        'borderHover' => [
            'type' => 'object',
            'default' => (object) [
                'openBorder' => 0,
                'type' => '',
                    'color' => '',
                'width' => (object) [
                    'md' => (object)[
                        'top' => '',
                        'left' => '',
                        'bottom' => '',
                        'right' => '',
                    ],
                    "unit" => "",
                ],
            ],
            'style' => [
                (object) [
                    'selector' => '{{PLUS_WRAP}} .tpgb-blockquote-inner:hover',
                ],
            ],
        ],
        'borderRadius' => [
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
                    'selector' => '{{PLUS_WRAP}} .tpgb-blockquote-inner{border-radius: {{borderRadius}};}',
                ],
            ],
        ],
        'HvrborderRadius' => [
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
                    'selector' => '{{PLUS_WRAP}} .tpgb-blockquote-inner:hover{border-radius: {{HvrborderRadius}};}',
                ],
            ],
        ],
        'catBg' => [
            'type' => 'object',
            'default' => (object) [
                'bgType' => 'color',
                'bgDefaultColor' => '',
            ],
            'style' => [
                (object) [
                    'selector' => '{{PLUS_WRAP}} .tpgb-blockquote-inner',
                ],
            ],
        ],
        'catBgHover' => [
            'type' => 'object',
            'default' => (object) [
                'bgType' => 'color',
                'bgDefaultColor' => '',
            ],
            'style' => [
                (object) [
                    'selector' => '{{PLUS_WRAP}} .tpgb-blockquote-inner:hover',
                ],
            ],
        ],
        'catBoxShadow' => [
            'type' => 'object',
            'default' => (object) [
                'openShadow' => 0,
                'blur' => 8,
                'color' => "rgba(0,0,0,0.40)",
                'horizontal' => 0,
                'inset' => 0,
                'spread' => 0,
                'vertical' => 4
            ],
            'style' => [
                (object) [
                    'selector' => '{{PLUS_WRAP}} .tpgb-blockquote-inner',
                ],
            ],
        ],
        'catBoxShadowHover' => [
            'type' => 'object',
            'default' => (object) [
                'openShadow' => 0,
                'blur' => 8,
                'color' => "rgba(0,0,0,0.40)",
                'horizontal' => 0,
                'inset' => 0,
                'spread' => 0,
                'vertical' => 4
            ],
            'style' => [
                (object) [
                    'selector' => '{{PLUS_WRAP}} .tpgb-blockquote-inner:hover',
                ],
            ],
        ]
    ];

    $attributesOptions = array_merge($attributesOptions	, $globalBgOption, $globalpositioningOption, $globalPlusExtrasOption);

    register_block_type( 'tpgb/tp-blockquote', array(
		'attributes' => $attributesOptions,
		'editor_script' => 'tpgb-block-editor-js',
		'editor_style'  => 'tpgb-block-editor-css',
        'render_callback' => 'tpgb_tp_blockquote_callback'
    ));
}
add_action( 'init', 'tpgb_tp_blockquote_render' );