<?php
/* Tp Block : Post Author
 * @since	: 1.1.0
 */
function tpgb_tp_post_author_render_callback( $attr, $content) {
	$output = '';
	
    $post = get_queried_object();
    $block_id = (!empty($attr['block_id'])) ? $attr['block_id'] : uniqid("title");
	$className = (!empty($attr['className'])) ? $attr['className'] : '';
	$Align = (!empty($attr['Align'])) ? $attr['Align'] : '';
	$authorStyle = (!empty($attr['authorStyle'])) ? $attr['authorStyle'] : 'style-1';
    $ShowName = (!empty($attr['ShowName'])) ? $attr['ShowName'] : false;
    $ShowBio = (!empty($attr['ShowBio'])) ? $attr['ShowBio'] : false;
    $ShowAvatar = (!empty($attr['ShowAvatar'])) ? $attr['ShowAvatar'] : false;
    $ShowSocial = (!empty($attr['ShowSocial'])) ? $attr['ShowSocial'] : false;
    
	$blockClass = '';
	if(!empty($className)){
		$blockClass .= $className;
	}
	$align = (!empty($attr['align'])) ? $attr['align'] :'';
	if(!empty($align)){
		$blockClass .= ' align'.$align;
	}
	
	$outputavatar=$outputname=$outputbio=$outputrole=$authorsocial='';
	if(!empty($post)){
		$author_page_url = get_author_posts_url($post->post_author);
		$avatar_url = get_avatar_url($post->post_author);
		$author_bio =  get_the_author_meta('user_description',$post->post_author);
		if( !empty( $ShowName ) ){
			$author_name = get_the_author_meta('display_name', $post->post_author);
			$outputname .='<a href="'.esc_url($author_page_url).'" class="author-name tpgb-author-trans" rel="'.esc_attr__('author','tpgb').'" >'.esc_html($author_name).'</a>';
		}
		if(!empty($ShowAvatar)){
			$outputavatar .= '<a href="'.esc_url($author_page_url).'" rel="'.esc_attr__('author','tpgb').'" class="author-avatar tpgb-author-trans"><img src="'.esc_url($avatar_url).'" /></a>';
		}
		if(!empty($ShowBio)){
			$outputbio .= '<div class="author-bio tpgb-author-trans" >'.esc_html($author_bio).'</div>';
		}

		$user_meta=get_the_author_meta('roles',$post->post_author);
		if(!empty($user_meta)){
			$author_role = $user_meta[0];
			$outputrole .= '<span class="author-role">'.esc_html__( 'Role', 'tpgb' ).":-".esc_html($author_role).'</span>';
		}

		if(!empty($ShowSocial)){
			$author_website =  get_the_author_meta('user_url',$post->post_author);
			$author_facebook = get_the_author_meta('author_facebook', $post->post_author);
			$author_email =  get_the_author_meta('email',$post->post_author);
			$author_twitter = get_the_author_meta('author_twitter', $post->post_author);
			$author_instagram = get_the_author_meta('author_instagram', $post->post_author);
			$authorsocial .= '<ul class="author-social">';
				if(!empty($author_website)){
					$authorsocial .= '<li><a href="'.esc_url($author_website).'" rel="'.esc_attr__("website","tpgb").'" target="_blank"><i class="fas fa-globe-asia"></i></a></li>';
				}
				if(!empty($author_email)){
					$authorsocial .= '<li><a href="'.esc_url($author_email).'" rel="'.esc_attr__("Email","tpgb").'" target="_blank"><i class="fas fa-envelope"></i></a></li>';
				}
				if(!empty($author_facebook)){
					$authorsocial .= '<li><a href="'.esc_url($author_facebook).'" rel="'.esc_attr__("facebook","tpgb").'" target="_blank"><i class="fab fa-facebook-f"></i></a></li>';
				}
				if(!empty($author_twitter)){
					$authorsocial .= '<li><a href="'.esc_url($author_twitter).'" rel="'.esc_attr__("twitter","tpgb").'" target="_blank"><i class="fab fa-twitter" ></i></a></li>';
				}
				if(!empty($author_instagram)){
					$authorsocial .= '<li><a href="'.esc_url($author_instagram).'" rel="'.esc_attr__("instagram","tpgb").'" target="_blank"><i class="fab fa-instagram"></i></a></li>';
				}
			$authorsocial .='</ul>';
		}
	}

    $output .= '<div class="tpgb-post-author tpgb-block-'.esc_attr($block_id ).' '.esc_attr($blockClass).'" >';
		$output .= '<div class="tpgb-post-inner  author-'.esc_attr($authorStyle).' '.($authorStyle == 'style-2' ? ' text-'.esc_attr($Align) : '' ).' ">';
			if($ShowAvatar){
				$output .=$outputavatar;
			}
			$output .='<div class="author-info">';
				if($ShowName){
					$output .=$outputname;
				}
				$output .= $outputrole;
				if($ShowBio){
					$output .=$outputbio;
				}
				if($ShowSocial){
					$output .=$authorsocial;
				}
			$output .= '</div>';
		$output .= '</div>';
    $output .= '</div>';
	
	$output = Tpgb_Blocks_Global_Options::block_Wrap_Render($attr, $output);
	
    return $output;
}

/**
 * Render for the server-side
 */
function tpgb_post_author_content() {
	$globalBgOption = Tpgb_Blocks_Global_Options::load_bg_options();
    $globalpositioningOption = Tpgb_Blocks_Global_Options::load_positioning_options();
    $globalPlusExtrasOption = Tpgb_Blocks_Global_Options::load_plusextras_options();
	
	$attributesOptions = array(
			'block_id' => [
                'type' => 'string',
				'default' => '',
			],
			'authorStyle' => [
				'type' => 'string',
				'default' => 'style-1',
			],
			'Align' => [
				'type' => 'string',
				'default' => 'left',
			],
			'maxWidth' => [
				'type' => 'object',
				'default' => [ 
					'md' => '',
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'authorStyle', 'relation' => '==', 'value' => 'style-2']],
						'selector' => '{{PLUS_WRAP}} .tpgb-post-inner{ max-width: {{maxWidth}}; }',
					],
				],
			],
			'ShowName' => array(
                'type' => 'boolean',
				'default' => true,
			),
			'nameTypo' => [
				'type' => 'object',
				'default' => (object) [
					'openTypography' => 0,
					'size' => [ 'md' => '', 'unit' => 'px' ],
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'ShowName', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}} .tpgb-post-inner .author-name',
					],
				],
			],
			
			'nameNormalColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'ShowName', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}} .tpgb-post-inner .author-name{color: {{nameNormalColor}};}',
					],
				],
            ],
			'nameHoverColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'ShowName', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}} .tpgb-post-inner:hover .author-name{color: {{nameHoverColor}};}',
					],
				],
            ],
			'ShowRole' => [
				'type' => 'boolean',
				'default' => true,
			],
			'roleLabel' => [
				'type' => 'string',
				'default' => 'Role',
			],
			'roleTypo' => [
				'type' => 'object',
				'default' => (object) [
					'openTypography' => 0,
					'size' => [ 'md' => '', 'unit' => 'px' ],
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'ShowRole', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}} .tpgb-post-inner .author-role',
					],
				],
			],
			'roleColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'ShowBio', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}} .tpgb-post-inner .author-role{color: {{roleColor}};}',
					],
				],
            ],
			'roleHvrColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'ShowBio', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}} .tpgb-post-inner:hover .author-role{color: {{roleHvrColor}};}',
					],
				],
            ],
			'ShowBio' => array(
                'type' => 'boolean',
				'default' => true,
			),
			'bioMargin' => [
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
						'selector' => '{{PLUS_WRAP}} .tpgb-post-inner .author-bio {margin: {{bioMargin}};}',
					],
				],
			],
			'bioTypo' => [
				'type' => 'object',
				'default' => (object) [
					'openTypography' => 0,
					'size' => [ 'md' => '', 'unit' => 'px' ],
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'ShowBio', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}} .tpgb-post-inner .author-bio',
					],
				],
			],
			
			'bioNormalColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'ShowBio', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}} .tpgb-post-inner .author-bio{color: {{bioNormalColor}};}',
					],
				],
            ],
			'bioHoverColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'ShowBio', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}} .tpgb-post-inner:hover .author-bio{color: {{bioHoverColor}};}',
					],
				],
            ],
			'ShowAvatar' => array(
                'type' => 'boolean',
				'default' => true,
			),
			'avatarWidth' => [
				'type' => 'object',
				'default' => ['md' => '', 'unit' => 'px'],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'ShowAvatar', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}} .tpgb-post-inner .author-avatar{max-width: {{avatarWidth}};}',
					],
				],
			],
			
			'avatarBorderRadius' => [
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
						'condition' => [(object) ['key' => 'ShowAvatar', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}} .tpgb-post-inner .author-avatar,{{PLUS_WRAP}} .tpgb-post-inner .author-avatar img{border-radius: {{avatarBorderRadius}};}',
					],
				],
			],
			'avatarBoxShadow' => [
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
						'condition' => [(object) ['key' => 'ShowAvatar', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}} .tpgb-post-inner .author-avatar',
					],
				],
			],
			
			'ShowSocial' => array(
                'type' => 'boolean',
				'default' => true,
			),
			'socialSize' => [
				'type' => 'object',
				'default' => ['md' => '', 'unit' => 'px'],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'ShowSocial', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}} .tpgb-post-inner ul.author-social li a{font-size: {{socialSize}};}',
					],
				],
			],
			'socialNormalColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'ShowSocial', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}} .tpgb-post-inner ul.author-social li a{color: {{socialNormalColor}};}',
					],
				],
            ],
			'socialHoverColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'ShowSocial', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}} .tpgb-post-inner ul.author-social li a:hover{color: {{socialHoverColor}};}',
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
						'selector' => '{{PLUS_WRAP}} .tpgb-post-inner {padding: {{padding}};}',
					],
				],
			],
			'boxBorder' => [
				'type' => 'object',
				'default' => (object) [
					'openBorder' => 0,
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-post-inner ',
					],
				],
			],
			'boxBorderHover' => [
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
					],
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-post-inner:hover',
					],
				],
			],
			
			'boxBRadius' => [
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
						'selector' => '{{PLUS_WRAP}} .tpgb-post-inner {border-radius: {{boxBRadius}};}',
					],
				],
			],
			'boxBRadiusHover' => [
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
						'selector' => '{{PLUS_WRAP}} .tpgb-post-inner:hover{border-radius: {{boxBRadiusHover}};}',
					],
				],
			],
			'boxBg' => [
				'type' => 'object',
				'default' => (object) [
					'bgType' => 'color',
					'bgDefaultColor' => '',
					'bgGradient' => (object) [
						"direction" => 90,
					],
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-post-inner ',
					],
				],
			],
			'boxBgHover' => [
				'type' => 'object',
				'default' => (object) [
					'bgType' => 'color',
					'bgDefaultColor' => '',
					'bgGradient' => (object) [
						"direction" => 90,
					],
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}} .tpgb-post-inner:hover',
					],
				],
			],
			'boxBoxShadow' => [
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
						'selector' => '{{PLUS_WRAP}} .tpgb-post-inner ',
					],
				],
			],
			'boxBoxShadowHover' => [
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
						'selector' => '{{PLUS_WRAP}} .tpgb-post-inner:hover',
					],
				],
			],
		);
	
	$attributesOptions = array_merge($attributesOptions,$globalBgOption,$globalpositioningOption,$globalPlusExtrasOption);
	
	register_block_type( 'tpgb/tp-post-author', array(
		'attributes' => $attributesOptions,
		'editor_script' => 'tpgb-block-editor-js',
		'editor_style'  => 'tpgb-block-editor-css',
        'render_callback' => 'tpgb_tp_post_author_render_callback'
    ) );
}
add_action( 'init', 'tpgb_post_author_content' );