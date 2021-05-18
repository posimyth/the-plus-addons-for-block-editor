<?php
/* Tp Block : Post Image
 * @since	: 1.1.0
 */
function tpgb_tp_post_image_render_callback( $attr, $content) {
	$output = '';
	$post_id = get_the_ID();

    $block_id = (!empty($attr['block_id'])) ? $attr['block_id'] : uniqid("title");
	$className = (!empty($attr['className'])) ? $attr['className'] : '';
	$imageType = (!empty($attr['imageType'])) ? $attr['imageType'] : 'default';
	$bgLocation = (!empty($attr['bgLocation'])) ? $attr['bgLocation'] : 'section';
	$align = (!empty($attr['align'])) ? $attr['align'] :'';
	$imageSize = (!empty($attr['imageSize'])) ? $attr['imageSize'] : 'full';
    
	$blockClass = '';
	if(!empty($className)){
		$blockClass .= $className;
	}
	if(!empty($align)){
		$blockClass .= ' align'.$align;
	}
	
	$data_attr = [];
	if(!empty($imageType) && $imageType=='background'){
		$blockClass .= ' post-img-bg';
		$data_attr['id'] = $block_id;
		$data_attr['imgType'] = $imageType;
		$data_attr['imgLocation'] = $bgLocation;
	}
	
	$data_attr = json_encode($data_attr);

    $image_content ='';
	if (has_post_thumbnail( $post_id ) ){
		$image_content = get_the_post_thumbnail_url($post_id,$imageSize);
		$output .= '<div class="tpgb-post-image tpgb-block-'.esc_attr($block_id).' '.esc_attr($blockClass).' " data-setting=\'' . $data_attr . '\'>';
			
				if(!empty($imageType) && $imageType!='background'){
					$output .= '<div class="tpgb-featured-image">';
						$output .= '<a href="'.esc_url(get_the_permalink()).'">';
							$output .= '<img src="'.esc_url($image_content).'" alt="'.get_the_title().'" class="tpgb-featured-img" />';
						$output .= '</a>';
					$output .= '</div>';
				}else if(!empty($imageType) && $imageType=='background'){
					$output .= '<div class="tpgb-featured-image" style="background-image: url('.esc_url($image_content).')"></div>';
				}
			
		$output .= "</div>";
	}
	
	$output = Tpgb_Blocks_Global_Options::block_Wrap_Render($attr, $output);
	
    return $output;
}

/**
 * Render for the server-side
 */
function tpgb_post_image_content() {
	$globalBgOption = Tpgb_Blocks_Global_Options::load_bg_options();
    $globalpositioningOption = Tpgb_Blocks_Global_Options::load_positioning_options();
    $globalPlusExtrasOption = Tpgb_Blocks_Global_Options::load_plusextras_options();
	
	$attributesOptions = array(
			'block_id' => [
                'type' => 'string',
				'default' => '',
			],
			'imageType' => [
                'type' => 'string',
				'default' => 'default',
			],
			'bgLocation' => [
                'type' => 'string',
				'default' => 'section',
			],
			'imageSize' => [
                'type' => 'string',
				'default' => 'full',
			],
			'imageAlign' => [
				'type' => 'object',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'imageType', 'relation' => '==', 'value' => 'default' ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-post-image {text-align: {{imageAlign}};}',
					],
				],
			],
			'maxWidth' => [
				'type' => 'object',
				'default' => ['md' => '', 'unit' => 'px'],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'imageType', 'relation' => '==', 'value' => 'default' ]],
						'selector' => '{{PLUS_WRAP}} .tpgb-featured-image img{max-width: {{maxWidth}};width: 100%;}',
					],
				],
			],
			'bgPosition' => [
				'type' => 'string',
				'default' => 'center center',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'imageType', 'relation' => '==', 'value' => 'background' ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-post-image.post-img-bg .tpgb-featured-image{background-position : {{bgPosition}} }',
					],
				],
			],
			'bgAttachment' => [
				'type' => 'string',
				'default' => 'scroll',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'imageType', 'relation' => '==', 'value' => 'background' ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-post-image.post-img-bg .tpgb-featured-image{background-attachment : {{bgAttachment}} }',
					],
				],
			],
			'bgRepeat' => [
				'type' => 'string',
				'default' => 'no-repeat',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'imageType', 'relation' => '==', 'value' => 'background' ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-post-image.post-img-bg .tpgb-featured-image{background-repeat : {{bgRepeat}} }',
					],
				],
			],
			'bgSize' => [
				'type' => 'string',
				'default' => 'cover',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'imageType', 'relation' => '==', 'value' => 'background' ]],
						'selector' => '{{PLUS_WRAP}}.tpgb-post-image.post-img-bg .tpgb-featured-image{background-size : {{bgSize}} }',
					],
				],
			],
			
			'postimgBg' => [
				'type' => 'object',
				'default' => (object) [
					'openBg'=> 0,
					'bgType' => '',
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-featured-image a:after,{{PLUS_WRAP}}.tpgb-post-image.post-img-bg .tpgb-featured-image:after',
					],
				],
			],
			
			'postimgHvrBg' => [
				'type' => 'object',
				'default' => (object) [
					'openBg'=> 0,
					'bgType' => '',
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-featured-image:hover a:after,{{PLUS_WRAP}}.tpgb-post-image.post-img-bg .tpgb-featured-image:after',
					],
				],
			],
		);
	
	$attributesOptions = array_merge($attributesOptions,$globalBgOption,$globalpositioningOption,$globalPlusExtrasOption);
	
	register_block_type( 'tpgb/tp-post-image', array(
		'attributes' => $attributesOptions,
		'editor_script' => 'tpgb-block-editor-js',
		'editor_style'  => 'tpgb-block-editor-css',
        'render_callback' => 'tpgb_tp_post_image_render_callback'
    ) );
}
add_action( 'init', 'tpgb_post_image_content' );