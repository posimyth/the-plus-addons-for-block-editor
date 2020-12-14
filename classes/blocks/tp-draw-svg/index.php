<?php
/**
 * After rendring from the block editor display output on front-end
 */
function tpgb_tp_draw_svg_render_callback( $attributes, $content) {
    $block_id = (!empty($attributes['block_id'])) ? $attributes['block_id'] : uniqid("title");
	$duration = (!empty($attributes['duration'])) ? $attributes['duration'] : 90;
	$drawType = (!empty($attributes['drawType'])) ? $attributes['drawType'] : 'delayed';
	$selectSvg = (!empty($attributes['selectSvg'])) ? $attributes['selectSvg'] : 'preBuild';
	$svgList = (!empty($attributes['svgList'])) ? $attributes['svgList'] : 'app';
	$hoverDraw = (!empty($attributes['hoverDraw'])) ? $attributes['hoverDraw'] : 'onScroll';
	$strokeColor = (!empty($attributes['strokeColor'])) ? $attributes['strokeColor'] : '';
	$fillToggle = (!empty($attributes['fillToggle'])) ? $attributes['fillToggle'] : false;
	$fillColor = (!empty($attributes['fillColor'])) ? $attributes['fillColor'] : '';
	
	$className = (!empty($attributes['className'])) ? $attributes['className'] :'';
	$align = (!empty($attributes['align'])) ? $attributes['align'] :'';
	
	$blockClass = '';
	if(!empty($className)){
		$blockClass .= $className;
	}
	if(!empty($align)){
		$blockClass .= ' align'.$align;
	}
	
	$fillEnable=$fill_color = '';
	if(!empty($fillToggle)){
		$fillEnable = 'yes';
		$fill_color = $fillColor;
	}else{
		$fillEnable = 'no';
		$fill_color = 'none';
	}
	
	$draw_hover = '';
	if($hoverDraw=='onHover'){
		$draw_hover = 'tpgb-hover-draw-svg';
	}
	$svgsrc = '';
	if($selectSvg=='custom'){
		$svgsrc = (!empty($attributes['customSVG']) && !empty($attributes['customSVG']['url'])) ? $attributes['customSVG']['url'] : '';
	}else{
		$svgsrc = TPGB_URL.'assets/images/svg/'.esc_attr($svgList).'.svg';
	}
	$output = '';
	$output .= '<div class="tpgb-draw-svg tpgb-block-'.esc_attr($block_id).' '.esc_attr($blockClass).' '.esc_attr($draw_hover).'" data-id="tpgb-block-'.esc_attr($block_id).'" data-type="'.esc_attr($drawType).'" data-duration="'.esc_attr($duration).'" data-stroke="'.esc_attr($strokeColor).'" data-fillColor="'.esc_attr($fill_color).'" data-fillEnable="'.esc_attr($fillEnable).'">';
		$output .= '<div class="svg-inner-block">';
			$output .= '<object id="tpgb-block-'.esc_attr($block_id).'" type="image/svg+xml" data="'.esc_url($svgsrc).'">';
			$output .= '</object>';
		$output .= '</div>';
	$output .= '</div>';
	
	$output = Tpgb_Blocks_Global_Options::block_Wrap_Render($attributes, $output);
	
    return $output;
}

/**
 * Render for the server-side
 */
function tpgb_draw_svg() {
	$globalBgOption = Tpgb_Blocks_Global_Options::load_bg_options();
	$globalpositioningOption = Tpgb_Blocks_Global_Options::load_positioning_options();
	$globalPlusExtrasOption = Tpgb_Blocks_Global_Options::load_plusextras_options();
	
	$attributesOptions = array(
		'block_id' => [
			'type' => 'string',
			'default' => '',
		],
		'selectSvg' => [
			'type' => 'string',
			'default' => 'preBuild',	
		],
		'svgList' => [
			'type' => 'string',
			'default' => 'app',	
		],
		'customSVG' => [
			'type' => 'object',
			'default' => [
				'url' => '',
			],
		],
		'alignment' => [
			'type' => 'string',
			'default' => 'center',
			'style' => [
				(object) [
					'selector' => '{{PLUS_WRAP}}{ text-align: {{alignment}}; }',
				],
			],
		],
		'maxWidth' => [
			'type' => 'object',
			'default' => [ 
				'md' => '',
				"unit" => 'px',
			],
			'style' => [
				(object) [
					'selector' => '{{PLUS_WRAP}} .svg-inner-block{ max-width: {{maxWidth}}; max-height: {{maxWidth}}; }',
				],
			],
		],
		'strokeColor' => [
			'type' => 'string',
			'default' => '#8072fc',
		],
		'fillToggle' => [
			'type' => 'boolean',
			'default' => false,	
		],
		'fillColor' => [
			'type' => 'string',
			'default' => '#000000',
		],
		'drawType' => [
			'type' => 'string',
			'default' => 'delayed',	
		],
		'duration' => [
			'type' => 'string',
			'default' => '90',	
		],
		'hoverDraw' => [
			'type' => 'string',
			'default' => 'onScroll',	
		],
	);
	$attributesOptions = array_merge($attributesOptions,$globalBgOption,$globalpositioningOption,$globalPlusExtrasOption);
	
	register_block_type( 'tpgb/tp-draw-svg', array(
		'attributes' => $attributesOptions,
		'editor_script' => 'tpgb-block-editor-js',
		'editor_style'  => 'tpgb-block-editor-css',
        'render_callback' => 'tpgb_tp_draw_svg_render_callback'
    ) );
}
add_action( 'init', 'tpgb_draw_svg' );