<?php
/* Tp Block : Post Content
 * @since	: 1.1.3
 */
defined( 'ABSPATH' ) || exit;

function tpgb_tp_post_content_render_callback( $attr, $content) {
	$output = '';
	$post_id = get_the_ID();
    $post = get_queried_object();
    $block_id = (!empty($attr['block_id'])) ? $attr['block_id'] : uniqid("title");
	$types = (!empty($attr['types'])) ? $attr['types'] : 'singular';
	$contentType = (!empty($attr['contentType'])) ? $attr['contentType'] :'';
	$limitCountType = (!empty($attr['limitCountType'])) ? $attr['limitCountType'] :'';
	$titleLimit = (!empty($attr['titleLimit'])) ? $attr['titleLimit'] :'';
    $chideDots = (!empty($attr['chideDots'])) ? $attr['chideDots'] : false ;
	
	$blockClass = Tp_Blocks_Helper::block_wrapper_classes( $attr );
	
	$content = '';
	if($types == 'archive'){
		if ( is_category() || is_tag() || is_tax() ) {
			$content = term_description();
		}
	}else{
		if($contentType == 'postContent'){
			
			global $current_screen;
			if ( method_exists($current_screen, 'is_block_editor') && $current_screen->is_block_editor() ) {
				$content = wp_strip_all_tags(get_the_content( '',true, $post));
			} else {
				$post = get_post($post_id);
				if ( ! $post || 'nxt_builder' == $post->post_type) {
					return '';
				}
				
				if ( ('publish' !== $post->post_status && 'draft' !== $post->post_status && 'private' !== $post->post_status) || ! empty( $post->post_password ) ) {
					return '';
				}
				
				$content = apply_filters( 'the_content', $post->post_content );
			}
		}else{
			$excerpt = get_post_field('post_excerpt', $post_id, 'display');
			if( $limitCountType == 'words' && !empty($excerpt) ){
				$content = wp_trim_words( $excerpt ,$titleLimit);
			} else if( $limitCountType == 'letters' && !empty($excerpt) ){
				$content = substr(wp_trim_words( $excerpt ),0, $titleLimit) . ( !empty($chideDots) ? '' : '...' );
			} else if( !empty($excerpt) ) {
				$content = $excerpt;
			}
		}
	}

    $output .= '<div class="tpgb-post-content tpgb-block-'.esc_attr($block_id ).' '.esc_attr($blockClass).'" >';
		$output .= '<div class="tpgb-entry-content">';
			$output .= $content;
		$output .= '</div>';
    $output .= "</div>";
	
	$output = Tpgb_Blocks_Global_Options::block_Wrap_Render($attr, $output);
	
    return $output;
}

/**
 * Render for the server-side
 */
function tpgb_post_content() {
	$globalBgOption = Tpgb_Blocks_Global_Options::load_bg_options();
    $globalpositioningOption = Tpgb_Blocks_Global_Options::load_positioning_options();
    $globalPlusExtrasOption = Tpgb_Blocks_Global_Options::load_plusextras_options();
	
	$attributesOptions = array(
			'block_id' => [
                'type' => 'string',
				'default' => '',
			],
			'className' => [
				'type' => 'string',
				'default' => '',
			],
			'types' => [
				'type' => 'string',
				'default' => 'singular',
			],
			'contentType' => [
				'type' => 'string',
				'default' => 'postExcerpt',
			],
			'limitCountType' => [
				'type' => 'string',
				'default' => 'default',
			],
			'titleLimit' => [
				'type' => 'number',
				'default' => '',
			],
			'chideDots' => [
				'type' => 'boolean',
				'default' => false,
			],
			'contentAlign' => [
				'type' => 'object',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-entry-content {text-align: {{contentAlign}};}',
					],
				],
			],
			'contentTypo' => [
				'type'=> 'object',
				'default'=> (object) [
					'openTypography' => 0,
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-entry-content',
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
						'selector' => '{{PLUS_WRAP}} .tpgb-entry-content{padding: {{padding}};}',
					],
				],
			],
			'contentColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-entry-content { color : {{contentColor}}; }',
					],
				],
			],
			'contentHvrColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-entry-content:hover { color : {{contentHvrColor}}; }',
					],
				],
			],
			'contentBg' => [
				'type' => 'object',
				'default' => (object) [
					'openBg'=> 0,
					'bgType' => '',
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-entry-content',
					],
				],
			],
			'contentHvrbg' => [
				'type' => 'object',
				'default' => (object) [
					'openBg'=> 0,
					'bgType' => '',
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-entry-content:hover',
					],
				],
			],
			'contentBorder' => [
				'type' => 'object',
				'default' => (object) [
					'openBorder' => 0,
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-entry-content',
					],
				],
			],
			'contentHvrBorder' => [
				'type' => 'object',
				'default' => (object) [
					'openBorder' => 0,
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-entry-content:hover',
					],
				],
			],
			'contentBRadius' => [
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
						'selector' => '{{PLUS_WRAP}} .tpgb-entry-content{ border-radius : {{contentBRadius}} }',
					],
				],
			],
			'contentHvrBra' => [
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
						'selector' => '{{PLUS_WRAP}} .tpgb-entry-content:hover { border-radius : {{contentHvrBra}} }',
					],
				],
			],
			'contentBshadow' => [
				'type' => 'object',
				'default' => (object) [
					'openShadow' => 0,
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-entry-content',
					],
				],
			],
			'contentHvrSha' => [
				'type' => 'object',
				'default' => (object) [
					'openShadow' => 0,
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-entry-content:hover',
					],
				],
			],
		);
	
	$attributesOptions = array_merge($attributesOptions,$globalBgOption,$globalpositioningOption,$globalPlusExtrasOption);
	
	register_block_type( 'tpgb/tp-post-content', array(
		'attributes' => $attributesOptions,
		'editor_script' => 'tpgb-block-editor-js',
		'editor_style'  => 'tpgb-block-editor-css',
        'render_callback' => 'tpgb_tp_post_content_render_callback'
    ) );
}
add_action( 'init', 'tpgb_post_content' );