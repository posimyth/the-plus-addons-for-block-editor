<?php
/* Block : Countdown
 * @since : 1.0.0
 */
function tpgb_tp_countdown_callback( $attributes, $content) {
	$output = '';
	$block_id = (!empty($attributes['block_id'])) ? $attributes['block_id'] : uniqid("title");
   
	$blockClass = Tp_Blocks_Helper::block_wrapper_classes( $attributes );
	
	$style = $attributes['style'];
    $countdownSelection = $attributes['countdownSelection'];
    $offset_time = get_option('gmt_offset');
    $offsetTime = wp_timezone_string();
    $now    = new DateTime('NOW', new DateTimeZone($offsetTime));
	$future = '';
    if(!empty($attributes['datetime']) && $attributes['datetime'] != 'Invalid date') {
        $future = new DateTime($attributes['datetime'], new DateTimeZone($offsetTime));
    }
    $now    = $now->modify("+1 second");

    if(!empty($attributes['datetime'])) {
        $datetime = $attributes['datetime'];
        $datetime = date('m/d/Y H:i:s', strtotime($datetime) );
    } else {
        $curr_date = date("m/d/Y H:i:s");
		$datetime = date('m/d/Y H:i:s', strtotime($curr_date . ' +1 month'));
    }
    
    $encodedUrl = '';
    if($attributes['countdownExpiry'] == 'redirect') {
        $encodedUrl = $attributes['expiryRedirect']['url'];
    }
    
    $dataAttr = '';
    $showLabels = (!empty($attributes['showLabels'])) ? $attributes['showLabels'] : '' ;
    $daysText = (!empty($attributes['daysText'])) ? $attributes['daysText'] : esc_html__('Days','tpgb');
    $hoursText = (!empty($attributes['hoursText'])) ? $attributes['hoursText'] : esc_html__('Hours','tpgb');
    $minutesText = (!empty($attributes['minutesText'])) ? $attributes['minutesText'] : esc_html__('Minutes','tpgb');
    $secondsText = (!empty($attributes['secondsText'])) ? $attributes['secondsText'] : esc_html__('Seconds','tpgb');
    
    if(!empty($showLabels) && $showLabels == true) {
        $dataAttr .= 'data-day="'.esc_attr($daysText).'" data-hour="'.esc_attr($hoursText).'" data-min="'.esc_attr($minutesText).'" data-sec="'.esc_attr($secondsText).'"';
    }

    $output .= '<div class="tp-countdown tpgb-block-'.esc_attr($block_id).' '.esc_attr($blockClass).' countdown-'.esc_attr($style).'" data-id="tpgb-block-'.esc_attr($block_id).'" data-style="'.esc_attr($style).'" data-offset="'.esc_attr($offset_time).'" data-expiry="'.esc_attr($attributes['countdownExpiry']).'" data-redirect="'.esc_url($encodedUrl).'" '.$dataAttr.'>';
    
    if($countdownSelection == 'normal') {

        if($future >= $now && isset($future)) {

            if($style == 'style-1') {

                $inline_style = (!empty($attributes["inlineStyle"])) ? 'count-inline-style' : '';
                
                $output .= '<ul class="tpgb-countdown-counter '.esc_attr($inline_style).'" data-time = "'.esc_attr($datetime).'">';
                $output .= '<li class="count_1">';
                $output .= '<span class="days">'.esc_html__('00','tpgb').'</span>';
                if(!empty($showLabels) && $showLabels==true) {
                    $output .= '<h6 class="days_ref">'.esc_html($daysText).'</h6>';
                }
                $output .= '</li>';
                $output .= '<li class="count_2">';
                $output .= '<span class="hours">'.esc_html__('00','tpgb').'</span>';
                if(!empty($showLabels) && $showLabels==true) {
                    $output .= '<h6 class="hours_ref">'.esc_html($hoursText).'</h6>';
                }
                $output .= '</li>';
                $output .= '<li class="count_3">';
                $output .= '<span class="minutes">'.esc_html__('00','tpgb').'</span>';
                if(!empty($showLabels) && $showLabels==true) {
                    $output .= '<h6 class="minutes_ref">'.esc_html($minutesText).'</h6>';
                }
                $output .= '</li>';
                $output .= '<li class="count_4">';
                $output .= '<span class="seconds last">'.esc_html__('00','tpgb').'</span>';
                if(!empty($showLabels) && $showLabels==true) {
                    $output .= '<h6 class="seconds_ref">'.esc_html($secondsText).'</h6>';
                }
                $output .= '</li></ul>';
            }
        
        } else {
            
            if($attributes['countdownExpiry'] == 'showmsg') {
                $output .= '<div class="tpgb-countdown-expiry">'.esc_html($attributes['expiryMsg']).'</div>';
            }

        }
    }
        
    $output .= '</div>';
	
	$output = Tpgb_Blocks_Global_Options::block_Wrap_Render($attributes, $output);
	
    return $output;
}

function tpgb_tp_countdown_render() {
 
    $globalBgOption = Tpgb_Blocks_Global_Options::load_bg_options();
    $globalpositioningOption = Tpgb_Blocks_Global_Options::load_positioning_options();
	$globalPlusExtrasOption = Tpgb_Blocks_Global_Options::load_plusextras_options();
	
	$curr_date = date("Y-m-d h:i:s");
	$curr_date = date('Y-m-d H:i:s', strtotime($curr_date . ' +1 month'));
    $attributesOptions = [
        'block_id' => [
            'type' => 'string',
            'default' => '',
        ],
        'countdownSelection' => [
            'type' => 'string',
            'default' => 'normal',	
        ],
        'style' => [
            'type' => 'string',
            'default' => 'style-1',
        ],
        'datetime' => [
            'type' => 'string',
            'default' => $curr_date,
        ],
        'countdownExpiry' => [
            'type' => 'string',
            'default' => 'showmsg',
        ],
        'expiryMsg' => [
            'type' => 'string',
            'default' => 'Countdown Has Ended !',
        ],
        'expiryRedirect' => [
            'type'=> 'object',
            'default'=> [
                'url' => '',
                'target' => '',
                'nofollow' => ''
            ],
        ],
        'inlineStyle' => [
            'type' => 'boolean',
            'default' => false,
        ],
        'showLabels' => [
            'type' => 'boolean',
            'default' => true,
        ],
        'daysText' => [
            'type'=> 'string',
            'default'=> 'Days',
        ],
        'hoursText' => [
            'type'=> 'string',
            'default'=> 'Hours',
        ],
        'minutesText' => [
            'type'=> 'string',
            'default'=> 'Minutes',
        ],
        'secondsText' => [
            'type'=> 'string',
            'default'=> 'Seconds',
        ],    
        'counterFontColor' => [
            'type' => 'string',
            'default' => '',
            'style' => [
                (object) [
                    'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-1']],
                    'selector' => '{{PLUS_WRAP}} .tpgb-countdown-counter li > span{ color: {{counterFontColor}}; }',
                ],
            ],
        ],     
        'counterTypo' => [
            'type'=> 'object',
            'default'=> (object) [
                'openTypography' => 0,
            ],
            'style' => [
                (object) [
                    'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-1' ]],
                    'selector' => '{{PLUS_WRAP}} .tpgb-countdown-counter li > span',
                ],
            ],
        ],
        'labelTypo' => [
            'type'=> 'object',
            'default'=> (object) [
                'openTypography' => 0,
            ],
            'style' => [
                (object) [
                    'condition' => [
                        (object) ['key' => 'style', 'relation' => '==', 'value' => 'style-1'],
                            ['key' => 'showLabels', 'relation' => '==', 'value' => true]
                        ],
                    'selector' => '{{PLUS_WRAP}} .tpgb-countdown-counter li > h6',
                ],
            ],
        ],
        'expiryMsgTypo' => [
            'type'=> 'object',
            'default'=> (object) [
                'openTypography' => 0,
            ],
            'style' => [
                (object) [
                    'condition' => [(object) ['key' => 'countdownExpiry', 'relation' => '==', 'value' => 'showmsg']],
                    'selector' => '{{PLUS_WRAP}} .tpgb-countdown-expiry',
                ],
            ],
        ],
        'expiryFontColor' => [
            'type' => 'string',
            'default' => '',
            'style' => [
                (object) [
                    'condition' => [(object) ['key' => 'countdownExpiry', 'relation' => '==', 'value' => 'showmsg']],
                    'selector' => '{{PLUS_WRAP}} .tpgb-countdown-expiry{ color: {{expiryFontColor}}; }',
                ]
            ],
        ],
        'daysTextColor' => [
            'type' => 'string',
            'default' => '',
            'style' => [
                (object) [
                    'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-1']],
                    'selector' => '{{PLUS_WRAP}} .tpgb-countdown-counter li.count_1 h6{ color: {{daysTextColor}}; }',
                ],
            ],
        ],
        'daysBorderColor' => [
            'type' => 'string',
            'default' => '',
            'style' => [
                (object) [
                    'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-1']],
                    'selector' => '{{PLUS_WRAP}} .tpgb-countdown-counter li.count_1{ border-color: {{daysBorderColor}}; }',
                ],
            ],
        ],
        'daysBg' => [
            'type' => 'object',
            'default' => (object) [
                'openBg'=> 0,
                'bgType' => 'color',
                'videoSource' => 'local',
                'bgDefaultColor' => '',
                'bgGradient' => (object) [ 'color1' => '#16d03e', 'color2' => '#1f91f3', 'type' => 'linear', 'direction' => '90', 'start' => 5, 'stop' => 80, 'radial' => 'center', 'clip' => false ],
            ],
            'style' => [
                (object) [
                    'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-1']],
                    'selector' => '{{PLUS_WRAP}} .tpgb-countdown-counter li.count_1',
                ],
            ],
        ],
        'hourTextColor' => [
            'type' => 'string',
            'default' => '',
            'style' => [
                (object) [
                    'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-1']],
                    'selector' => '{{PLUS_WRAP}} .tpgb-countdown-counter li.count_2 h6{ color: {{hourTextColor}}; }',
                ],
            ],
        ],
        'hourBorderColor' => [
            'type' => 'string',
            'default' => '',
            'style' => [
                (object) [
                    'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-1']],
                    'selector' => '{{PLUS_WRAP}} .tpgb-countdown-counter li.count_2{ border-color: {{hourBorderColor}}; }',
                ],
            ],
        ],
        'hourBg' => [
            'type' => 'object',
            'default' => (object) [
                'openBg'=> 0,
                'bgType' => 'color',
                'videoSource' => 'local',
                'bgDefaultColor' => '',
                'bgGradient' => (object) [ 'color1' => '#16d03e', 'color2' => '#1f91f3', 'type' => 'linear', 'direction' => '90', 'start' => 5, 'stop' => 80, 'radial' => 'center', 'clip' => false ],
            ],
            'style' => [
                (object) [
                    'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-1']],
                    'selector' => '{{PLUS_WRAP}} .tpgb-countdown-counter li.count_2',
                ],
            ],
        ],
        'minTextColor' => [
            'type' => 'string',
            'default' => '',
            'style' => [
                (object) [
                    'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-1']],
                    'selector' => '{{PLUS_WRAP}} .tpgb-countdown-counter li.count_3 h6{ color: {{minTextColor}}; }',
                ],
            ],
        ],
        'minBorderColor' => [
            'type' => 'string',
            'default' => '',
            'style' => [
                (object) [
                    'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-1']],
                    'selector' => '{{PLUS_WRAP}} .tpgb-countdown-counter li.count_3{ border-color: {{minBorderColor}}; }',
                ],
            ],
        ],
        'minBg' => [
            'type' => 'object',
            'default' => (object) [
                'openBg'=> 0,
                'bgType' => 'color',
                'videoSource' => 'local',
                'bgDefaultColor' => '',
                'bgGradient' => (object) [ 'color1' => '#16d03e', 'color2' => '#1f91f3', 'type' => 'linear', 'direction' => '90', 'start' => 5, 'stop' => 80, 'radial' => 'center', 'clip' => false ],
            ],
            'style' => [
                (object) [
                    'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-1']],
                    'selector' => '{{PLUS_WRAP}} .tpgb-countdown-counter li.count_3',
                ],
            ],
        ],
        'secTextColor' => [
            'type' => 'string',
            'default' => '',
            'style' => [
                (object) [
                    'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-1']],
                    'selector' => '{{PLUS_WRAP}} .tpgb-countdown-counter li.count_4 h6{ color: {{secTextColor}}; }',
                ],
            ]
        ],
        'secBorderColor' => [
            'type' => 'string',
            'default' => '',
            'style' => [
                (object) [
                    'condition' => [
                        (object) ['key' => 'style', 'relation' => '==', 'value' => 'style-1']
                    ],
                    'selector' => '{{PLUS_WRAP}} .tpgb-countdown-counter li.count_4{ border-color: {{secBorderColor}}; }',
                ],
            ],
        ],
        'secBg' => [
            'type' => 'object',
            'default' => (object) [
                'openBg'=> 0,
                'bgType' => 'color',
                'videoSource' => 'local',
                'bgDefaultColor' => '',
                'bgGradient' => (object) [ 'color1' => '#16d03e', 'color2' => '#1f91f3', 'type' => 'linear', 'direction' => '90', 'start' => 5, 'stop' => 80, 'radial' => 'center', 'clip' => false ],
            ],
            'style' => [
                (object) [
                    'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-1']],
                    'selector' => '{{PLUS_WRAP}} .tpgb-countdown-counter li.count_4',
                ],
            ],
        ],
        'padding' => [
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
                    'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-1']],
                    'selector' => '{{PLUS_WRAP}}.countdown-style-1 .tpgb-countdown-counter li{ padding: {{padding}}; }',
                ],
            ],
        ],
        'margin' => [
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
                    'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-1']],
                    'selector' => '{{PLUS_WRAP}}.countdown-style-1 .tpgb-countdown-counter li{ margin: {{margin}}; }',
                ],
            ],
        ],
        'border' => [
            'type' => 'object',
            'default' => (object) [
               'openBorder' => 0,
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
                    'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-1']],
                    'selector' => '{{PLUS_WRAP}} .tpgb-countdown-counter li',
                ],
            ],
        ],
        'borderR' => [
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
                    'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-1']],
                    'selector' => '{{PLUS_WRAP}}.countdown-style-1 .tpgb-countdown-counter li{ border-radius: {{borderR}}; }',
                ],
            ],
        ],
        'boxShadow' => [
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
                    'condition' => [(object) ['key' => 'style', 'relation' => '==', 'value' => 'style-1']],
                    'selector' => '{{PLUS_WRAP}} .tpgb-countdown-counter li',
                ],
            ],
        ],
    ];
    
    $attributesOptions = array_merge($attributesOptions	, $globalBgOption, $globalpositioningOption, $globalPlusExtrasOption);

    register_block_type( 'tpgb/tp-countdown', array(
		'attributes' => $attributesOptions,
		'editor_script' => 'tpgb-block-editor-js',
		'editor_style'  => 'tpgb-block-editor-css',
        'render_callback' => 'tpgb_tp_countdown_callback'
    ));
}
add_action('init', 'tpgb_tp_countdown_render');