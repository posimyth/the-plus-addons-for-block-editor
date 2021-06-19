<?php
/* Tp Block : Post Comment
 * @since	: 1.1.0
 */
function tpgb_tp_post_comment_render_callback( $attr, $content) {
	$output = '';
	$post_id = get_queried_object_id();
    $post = get_queried_object();
    $block_id = (!empty($attr['block_id'])) ? $attr['block_id'] : uniqid("title");
	$comment_args = tpgb_comment_args();
    $comment = get_comments($post);
    $list_args = array('style' => 'ul', 'short_ping' => true, 'avatar_size' => 100, 'page' => $post_id );
	
	$blockClass = Tp_Blocks_Helper::block_wrapper_classes( $attr );
	
	ob_start();
    echo '<div class="tpgb-post-comment tpgb-block-'.esc_attr($block_id ).' '.esc_attr($blockClass).'" >';
		echo '<div id="comments" class="comments-area">';
			if(get_comments_number($post_id) > 0) {
				echo '<ul class="comment-list">';
					echo '<li>';
						echo '<div class="comment-section-title">'.esc_html__('Comment', 'tpgb').' ('. get_comments_number($post_id) . ')</div>';
					echo '<li>'; 
					wp_list_comments($list_args, $comment);
				echo "</ul>";
			}
			comment_form($comment_args, $post_id);
		echo "</div>";
	echo '</div>';

	$output .= ob_get_clean();
	$output = Tpgb_Blocks_Global_Options::block_Wrap_Render($attr, $output);
	
    return $output;
}

/**
 * Render for the server-side
 */
function tpgb_post_comment_content() {
	$globalBgOption = Tpgb_Blocks_Global_Options::load_bg_options();
    $globalpositioningOption = Tpgb_Blocks_Global_Options::load_positioning_options();
    $globalPlusExtrasOption = Tpgb_Blocks_Global_Options::load_plusextras_options();
	
	$attributesOptions = array(
			'block_id' => [
                'type' => 'string',
				'default' => '',
			],
			'commTypo' => [
				'type' => 'object',
				'default' => (object) [
					'openTypography' => 0,
					'size' => [ 'md' => '', 'unit' => 'px' ],
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-post-comment .comment-list .comment-section-title,.tpgb-post-comment #respond.comment-respond h3#reply-title',
					],
				],
			],
			'commColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-post-comment .comment-list .comment-section-title,.tpgb-post-comment #respond.comment-respond h3#reply-title{color: {{commColor}};}',
					],
				],
            ],
			'profilePadding' => [
				'type' => 'object',
				'default' => [ 
					'md' => '',
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-post-comment .comment-list li.comment>.comment-body img.avatar, {{PLUS_WRAP}}.tpgb-post-comment .comment-list li.pingback>.comment-body img.avatar{ padding: {{profilePadding}}; }',
					],
				],
			],
			'profileBorderRadius' => [
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
						'selector' => '{{PLUS_WRAP}}.tpgb-post-comment .comment-list li.comment>.comment-body img.avatar, {{PLUS_WRAP}}.tpgb-post-comment .comment-list li.pingback>.comment-body img.avatar{border-radius: {{profileBorderRadius}};}',
					],
				],
			],
			'profileBoxShadow' => [
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
						'selector' => '{{PLUS_WRAP}}.tpgb-post-comment .comment-list li.comment>.comment-body img.avatar, {{PLUS_WRAP}}.tpgb-post-comment .comment-list li.pingback>.comment-body img.avatar',
					],
				],
			],
			'userTypo' => [
				'type' => 'object',
				'default' => (object) [
					'openTypography' => 0,
					'size' => [ 'md' => '', 'unit' => 'px' ],
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-post-comment .comment-author.vcard cite.fn .url',
					],
				],
			],
			
			'userColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-post-comment .comment-author.vcard cite.fn .url{color: {{userColor}};}',
					],
				],
            ],
			'userHoverColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-post-comment .comment-author.vcard cite.fn .url:hover{color: {{userHoverColor}};}',
					],
				],
            ],
			
			'metaTypo' => [
				'type' => 'object',
				'default' => (object) [
					'openTypography' => 0,
					'size' => [ 'md' => '', 'unit' => 'px' ],
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-post-comment .comment-meta.commentmetadata a',
					],
				],
			],
			'metaColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-post-comment .comment-meta.commentmetadata a{color: {{metaColor}};}',
					],
				],
            ],
			'metaHoverColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-post-comment .comment-meta.commentmetadata a:hover{color: {{metaHoverColor}};}',
					],
				],
            ],
			
			'replypadding' => [
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
						'selector' => '{{PLUS_WRAP}}.tpgb-post-comment .comment-list .reply a{padding: {{replypadding}};}',
					],
				],
			],
			'replyTypo' => [
				'type' => 'object',
				'default' => (object) [
					'openTypography' => 0,
					'size' => [ 'md' => '', 'unit' => 'px' ],
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-post-comment .comment-list .reply a',
					],
				],
			],
			'replyColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-post-comment .comment-list .reply a{color: {{replyColor}};}',
					],
				],
            ],
			'replyHoverColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-post-comment .comment-list .reply a:hover{border-color: {{replyHoverColor}};color: {{replyHoverColor}};}',
					],
				],
            ],
			'replyBorder' => [
				'type' => 'object',
				'default' => (object) [
					'openBorder' => 0,
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-post-comment .comment-list .reply a',
					],
				],
			],
			'replyBorderHover' => [
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
						'selector' => '{{PLUS_WRAP}}.tpgb-post-comment .comment-list .reply a:hover',
					],
				],
			],
			
			'replyBorderRadius' => [
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
						'selector' => '{{PLUS_WRAP}}.tpgb-post-comment .comment-list .reply a{border-radius: {{replyBorderRadius}};}',
					],
				],
			],
			'replyBorderRadiusHover' => [
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
						'selector' => '{{PLUS_WRAP}}.tpgb-post-comment .comment-list .reply a:hover{border-radius: {{replyBorderRadiusHover}};}',
					],
				],
			],
			'replyBg' => [
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
						'selector' => '{{PLUS_WRAP}}.tpgb-post-comment .comment-list .reply a',
					],
				],
			],
			'replyBgHover' => [
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
						'selector' => '{{PLUS_WRAP}}.tpgb-post-comment .comment-list .reply a:hover',
					],
				],
			],
			'replyBoxShadow' => [
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
						'selector' => '{{PLUS_WRAP}}.tpgb-post-comment .comment-list .reply a',
					],
				],
			],
			'replyBoxShadowHover' => [
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
						'selector' => '{{PLUS_WRAP}}.tpgb-post-comment .comment-list .reply a:hover',
					],
				],
			],
			
			'fieldTypo' => [
				'type' => 'object',
				'default' => (object) [
					'openTypography' => 0,
					'size' => [ 'md' => '', 'unit' => 'px' ],
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-post-comment #commentform #author, {{PLUS_WRAP}}.tpgb-post-comment #commentform #email, {{PLUS_WRAP}}.tpgb-post-comment #commentform #url, {{PLUS_WRAP}}.tpgb-post-comment form.comment-form textarea#comment',
					],
				],
			],
			'fieldColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-post-comment #commentform #author, {{PLUS_WRAP}}.tpgb-post-comment #commentform #email, {{PLUS_WRAP}}.tpgb-post-comment #commentform #url, {{PLUS_WRAP}}.tpgb-post-comment form.comment-form textarea#comment{color: {{fieldColor}};}',
					],
				],
            ],
			'fieldHoverColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-post-comment #commentform #author:focus, {{PLUS_WRAP}}.tpgb-post-comment #commentform #email:focus, {{PLUS_WRAP}}.tpgb-post-comment #commentform #url:focus, {{PLUS_WRAP}}.tpgb-post-comment form.comment-form textarea#comment:focus{color: {{fieldHoverColor}};}',
					],
				],
            ],
			
			'fieldpadding' => [
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
						'selector' => '{{PLUS_WRAP}}.tpgb-post-comment #commentform #author, {{PLUS_WRAP}}.tpgb-post-comment #commentform #email, {{PLUS_WRAP}}.tpgb-post-comment #commentform #url, {{PLUS_WRAP}}.tpgb-post-comment form.comment-form textarea#comment{padding: {{fieldpadding}};}',
					],
				],
			],
			'fieldBorder' => [
				'type' => 'object',
				'default' => (object) [
					'openBorder' => 0,
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-post-comment #commentform #author, {{PLUS_WRAP}}.tpgb-post-comment #commentform #email, {{PLUS_WRAP}}.tpgb-post-comment #commentform #url, {{PLUS_WRAP}}.tpgb-post-comment form.comment-form textarea#comment',
					],
				],
			],
			'fieldBorderHover' => [
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
						'selector' => '{{PLUS_WRAP}}.tpgb-post-comment #commentform #author:focus, {{PLUS_WRAP}}.tpgb-post-comment #commentform #email:focus, {{PLUS_WRAP}}.tpgb-post-comment #commentform #url:focus, {{PLUS_WRAP}}.tpgb-post-comment form.comment-form textarea#comment:focus',
					],
				],
			],
			
			'fieldBorderRadius' => [
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
						'selector' => '{{PLUS_WRAP}}.tpgb-post-comment #commentform #author, {{PLUS_WRAP}}.tpgb-post-comment #commentform #email, {{PLUS_WRAP}}.tpgb-post-comment #commentform #url, {{PLUS_WRAP}}.tpgb-post-comment form.comment-form textarea#comment{border-radius: {{fieldBorderRadius}};}',
					],
				],
			],
			'fieldBorderRadiusHover' => [
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
						'selector' => '{{PLUS_WRAP}}.tpgb-post-comment #commentform #author:focus, {{PLUS_WRAP}}.tpgb-post-comment #commentform #email:focus, {{PLUS_WRAP}}.tpgb-post-comment #commentform #url:focus, {{PLUS_WRAP}}.tpgb-post-comment form.comment-form textarea#comment:focus{border-radius: {{fieldBorderRadiusHover}};}',
					],
				],
			],
			'fieldBg' => [
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
						'selector' => '{{PLUS_WRAP}}.tpgb-post-comment #commentform #author, {{PLUS_WRAP}}.tpgb-post-comment #commentform #email, {{PLUS_WRAP}}.tpgb-post-comment #commentform #url, {{PLUS_WRAP}}.tpgb-post-comment form.comment-form textarea#comment',
					],
				],
			],
			'fieldBgHover' => [
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
						'selector' => '{{PLUS_WRAP}}.tpgb-post-comment #commentform #author:focus, {{PLUS_WRAP}}.tpgb-post-comment #commentform #email:focus, {{PLUS_WRAP}}.tpgb-post-comment #commentform #url:focus, {{PLUS_WRAP}}.tpgb-post-comment form.comment-form textarea#comment:focus',
					],
				],
			],
			'fieldBoxShadow' => [
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
						'selector' => '{{PLUS_WRAP}}.tpgb-post-comment #commentform #author, {{PLUS_WRAP}}.tpgb-post-comment #commentform #email, {{PLUS_WRAP}}.tpgb-post-comment #commentform #url, {{PLUS_WRAP}}.tpgb-post-comment form.comment-form textarea#comment',
					],
				],
			],
			'fieldBoxShadowHover' => [
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
						'selector' => '{{PLUS_WRAP}}.tpgb-post-comment #commentform #author:focus, {{PLUS_WRAP}}.tpgb-post-comment #commentform #email:focus, {{PLUS_WRAP}}.tpgb-post-comment #commentform #url:focus, {{PLUS_WRAP}}.tpgb-post-comment form.comment-form textarea#comment:focus',
					],
				],
			],
			
			'btnTypo' => [
				'type' => 'object',
				'default' => (object) [
					'openTypography' => 0,
					'size' => [ 'md' => '', 'unit' => 'px' ],
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-post-comment #commentform #submit',
					],
				],
			],
			'btnColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-post-comment #commentform #submit{color: {{btnColor}};}',
					],
				],
            ],
			'btnHoverColor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-post-comment #commentform #submit:hover{color: {{btnHoverColor}};}',
					],
				],
            ],
			
			'btnpadding' => [
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
						'selector' => '{{PLUS_WRAP}}.tpgb-post-comment #commentform #submit{padding: {{btnpadding}};}',
					],
				],
			],
			'btnBorder' => [
				'type' => 'object',
				'default' => (object) [
					'openBorder' => 0,
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-post-comment #commentform #submit',
					],
				],
			],
			'btnBorderHover' => [
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
						'selector' => '{{PLUS_WRAP}}.tpgb-post-comment #commentform #submit:hover',
					],
				],
			],
			
			'btnBorderRadius' => [
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
						'selector' => '{{PLUS_WRAP}}.tpgb-post-comment #commentform #submit{border-radius: {{btnBorderRadius}};}',
					],
				],
			],
			'btnBorderRadiusHover' => [
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
						'selector' => '{{PLUS_WRAP}}.tpgb-post-comment #commentform #submit:hover{border-radius: {{btnBorderRadiusHover}};}',
					],
				],
			],
			'btnBg' => [
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
						'selector' => '{{PLUS_WRAP}}.tpgb-post-comment #commentform #submit',
					],
				],
			],
			'btnBgHover' => [
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
						'selector' => '{{PLUS_WRAP}}.tpgb-post-comment #commentform #submit:hover',
					],
				],
			],
			'btnBoxShadow' => [
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
						'selector' => '{{PLUS_WRAP}}.tpgb-post-comment #commentform #submit',
					],
				],
			],
			'btnBoxShadowHover' => [
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
						'selector' => '{{PLUS_WRAP}}.tpgb-post-comment #commentform #submit:hover',
					],
				],
			],
		);
	
	$attributesOptions = array_merge($attributesOptions,$globalBgOption,$globalpositioningOption,$globalPlusExtrasOption);
	
	register_block_type( 'tpgb/tp-post-comment', array(
		'attributes' => $attributesOptions,
		'editor_script' => 'tpgb-block-editor-js',
		'editor_style'  => 'tpgb-block-editor-css',
        'render_callback' => 'tpgb_tp_post_comment_render_callback'
    ) );
}
add_action( 'init', 'tpgb_post_comment_content' );

function tpgb_comment_args(){
	$user          = wp_get_current_user();
	$user_identity = $user->exists() ? $user->display_name : '';
	$args = array(
	  'id_form'           => 'commentform',
	  'class_form' => 'comment-form',
	  'id_submit'         => 'submit',
	  'title_reply'       => esc_html__( 'Leave Your Comment', 'tpgb' ),
	  'title_reply_to'    => esc_html__( 'Leave a Reply to %s', 'tpgb' ),
	  'cancel_reply_link' => esc_html__( 'Cancel Reply', 'tpgb' ),
	  'label_submit'      => esc_html__( 'Submit Now', 'tpgb' ),

	  'comment_field' =>  '<div class="tpgb-row"><div class="tpgb-col-md-12 tpgb-col"><label><textarea id="comment" name="comment" cols="45" rows="6" placeholder="'.esc_attr__('Comment','tpgb').'" aria-required="true"></textarea></label></div></div>',

	  'must_log_in' => '<p class="must-log-in">' .
		sprintf(
		  esc_html__( 'You must be %1$slogged in%2$s to post a comment.', 'tpgb' ),
		  '<a href="'.wp_login_url( apply_filters( "the_permalink", get_permalink() ) ).'">',
		  '</a>'
		) . '</p>',

	  'logged_in_as' => '<p class="logged-in-as">' .
		sprintf(
		esc_html__( 'Logged in as %1$s%2$s. %3$sLog out?%4$s', 'tpgb' ),
		  '<a href="'.admin_url( "profile.php" ).'">'.$user_identity,
		  '</a>',
		  '<a href="'.wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) ).'" title="'.esc_attr__("Log out of this account","tpgb").'">',
		  '</a>'
		) . '</p>',

	  'comment_notes_before' => '',

	  'comment_notes_after' => '',

	);
	return $args;
}

function tpgb_move_comment_field_to_bottom( $fields ) {
	$comment_field = $fields['comment'];
	unset( $fields['comment'] );
	$fields['comment'] = $comment_field;
	return $fields;
} 
add_filter( 'comment_form_fields', 'tpgb_move_comment_field_to_bottom' );

function tpgb_comment_form_field( $fields ){
	$commenter = wp_get_current_commenter();
	$fields['author'] ='<div class="tpgb-row" style="padding-top: 10px;"> <div class="tpgb-col tpgb-col-md-4 tpgb-col-sm-12 tpgb-col-xs-12"><label>' .
		  '<input id="author" name="author" type="text" placeholder="'.esc_attr__('Name','tpgb').'" value="' . esc_attr( $commenter['comment_author'] ) .
		  '" size="30" /></label></div>';
	
	$fields['email'] ='<div class="tpgb-col tpgb-col-md-4 tpgb-col-sm-12 tpgb-col-xs-12"><label>' .
		  '<input id="email" name="email" type="text" placeholder="'.esc_attr__('Email Address *','tpgb').'" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" /></label></div>';
	
	$fields['url'] ='<div class="tpgb-col tpgb-col-md-4 tpgb-col-sm-12 tpgb-col-xs-12"><label>' .
		  '<input id="url" name="url" type="text" placeholder="'.esc_attr__('Website','tpgb').'" value="' . esc_attr( $commenter['comment_author_url'] ) .
		  '" size="30" /></label></div></div>';
	return $fields;
}
add_filter( 'comment_form_default_fields', 'tpgb_comment_form_field',11 );

//remove comment cookies field form
remove_action( 'set_comment_cookies', 'wp_set_comment_cookies' );