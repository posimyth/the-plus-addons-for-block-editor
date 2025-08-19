<?php
/* Block : Team Member Listing
 * @since : 1.3.0
 */
defined( 'ABSPATH' ) || exit;
if (!function_exists('tpgb_tp_team_member_listing_render_callback')) {
function tpgb_tp_team_member_listing_render_callback( $attributes, $content) {
	$TeamMember = '';
	$block_id = (!empty($attributes['block_id'])) ? $attributes['block_id'] : uniqid("title");
	$style = (!empty($attributes['Style'])) ? $attributes['Style'] : 'style-1';
	$layout = (!empty($attributes['layout'])) ? $attributes['layout'] : 'grid';
	$DisbleLink = (!empty($attributes['DisLink'])) ? $attributes['DisLink'] : false;
	$TeamMemberR = (!empty($attributes['TeamMemberR'])) ? $attributes['TeamMemberR'] : [];
	$columns = (!empty($attributes['columns'])) ? $attributes['columns'] : 'md';
	$TitleTag = (!empty($attributes['TitleTag'])) ? $attributes['TitleTag'] : 'h3';
	$Designation = (!empty($attributes['DesignDis'])) ? $attributes['DesignDis'] : false;
	$DisableIcon = (!empty($attributes['SocialIcon'])) ? $attributes['SocialIcon'] : false;
	$DisableISize = (!empty($attributes['DImgS'])) ? $attributes['DImgS'] : false;
	$ImageSize = (!empty($attributes['ImgSize'])) ? $attributes['ImgSize'] : 'full';

	$showArrows = (!empty($attributes['showArrows'])) ? $attributes['showArrows'] : [ 'md' => false ];
	
	$Default_Img = TPGB_ASSETS_URL.'assets/images/tpgb-placeholder.jpg';

	$blockClass = Tp_Blocks_Helper::block_wrapper_classes( $attributes );

	$desktop_class = '';
	if( $layout !='carousel' && $layout !='metro' && $columns ){
		$desktop_class .= 'tpgb-col-'.esc_attr($columns['xs']);
		$desktop_class .= ' tpgb-col-lg-'.esc_attr($columns['md']);
		$desktop_class .= ' tpgb-col-md-'.esc_attr($columns['sm']);
		$desktop_class .= ' tpgb-col-sm-'.esc_attr($columns['xs']);
	}


	
	
	$TeamMember .= '<div id="'.esc_attr($block_id).'" class="tpgb-block-'.esc_attr($block_id).' tpgb-relative-block  tpgb-team-member-list team-'.esc_attr($style).' '.esc_attr($blockClass).' " data-style="'.esc_attr($style).'" data-layout="'.esc_attr($layout).'" data-id="'.esc_attr($block_id).'">';		
		$TeamMember .= '<div class="post-loop-inner tpgb-row">';
			
			if( !empty($TeamMemberR) ){
				foreach ( $TeamMemberR as $index => $TeamItem ) {
					$TeamName = (isset($TeamItem['TName']) && class_exists('Tpgbp_Pro_Blocks_Helper')) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_val($TeamItem['TName']) : $TeamItem['TName'];
					$TeamDesignation = ( !empty($TeamItem['TDesig']) ) ? $TeamItem['TDesig'] : '';
					$ImgId = ( !empty($TeamItem['TImage']) ) ? $TeamItem['TImage'] : [];
					$TeamCUrl = (isset($TeamItem['CusUrl']['dynamic']) && class_exists('Tpgbp_Pro_Blocks_Helper')) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_repeat_url($TeamItem['CusUrl']) : (!empty($TeamItem['CusUrl']['url']) ? $TeamItem['CusUrl']['url'] : '');
					$TeamWsUrl = (isset($TeamItem['WsUrl']['dynamic']) && class_exists('Tpgbp_Pro_Blocks_Helper')) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_repeat_url($TeamItem['WsUrl']) : (!empty($TeamItem['WsUrl']['url']) ? $TeamItem['WsUrl']['url'] : '');
					$TeamFbUrl = (isset($TeamItem['FbUrl']['dynamic']) && class_exists('Tpgbp_Pro_Blocks_Helper')) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_repeat_url($TeamItem['FbUrl']) : (!empty($TeamItem['FbUrl']['url']) ? $TeamItem['FbUrl']['url'] : '');
					$TeamMailUrl = (isset($TeamItem['MailUrl']['dynamic']) && class_exists('Tpgbp_Pro_Blocks_Helper')) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_repeat_url($TeamItem['MailUrl']) : (!empty($TeamItem['MailUrl']['url']) ? $TeamItem['MailUrl']['url'] : '');
					$TeamIGUrl = (isset($TeamItem['IGUrl']['dynamic']) && class_exists('Tpgbp_Pro_Blocks_Helper')) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_repeat_url($TeamItem['IGUrl']) : (!empty($TeamItem['IGUrl']['url']) ? $TeamItem['IGUrl']['url'] : '');
					$TeamTwUrl = (isset($TeamItem['TwUrl']['dynamic']) && class_exists('Tpgbp_Pro_Blocks_Helper')) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_repeat_url($TeamItem['TwUrl']) : (!empty($TeamItem['TwUrl']['url']) ? $TeamItem['TwUrl']['url'] : '');
					$TeamldUrl = (isset($TeamItem['ldUrl']['dynamic']) && class_exists('Tpgbp_Pro_Blocks_Helper')) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_repeat_url($TeamItem['ldUrl']) : (!empty($TeamItem['ldUrl']['url']) ? $TeamItem['ldUrl']['url'] : '');
					$Telephone = (isset($TeamItem['TelNum']) && class_exists('Tpgbp_Pro_Blocks_Helper')) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_val($TeamItem['TelNum']) : $TeamItem['TelNum'];

					// Set Default Image Url
					if(empty($ImgId)){
						$ImgId['url'] = $Default_Img;
					}

					$TeamMember .= '<div class="grid-item '.esc_attr($desktop_class).'">';
						$TeamMember .= '<div class="team-list-content tpgb-trans-linear">';
						
								$ImageHTML = $TeamImage = $AttImg = '';
								if(!empty($TeamCUrl) || !empty($ImgId)){
								
									if(!empty($ImgId)){
										$linkImage = '';
										$altText = (isset($ImgId['alt']) && !empty($ImgId['alt'])) ? esc_attr($ImgId['alt']) : ((!empty($ImgId['title'])) ? esc_attr($ImgId['title']) : esc_attr__('Profile Image','the-plus-addons-for-block-editor'));
										if( $layout !='carousel' && !empty($DisableISize) ){
											if(!empty($ImgId['id'])){
												$AttImg .= wp_get_attachment_image($ImgId['id'] , $ImageSize, false, ['alt'=> $altText]);
											}else if(!empty($ImgId['url'])){
												$imgUrl = (isset($ImgId['dynamic']) && class_exists('Tpgbp_Pro_Blocks_Helper')) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_repeat_url($ImgId) : (!empty($ImgId['url']) ? $ImgId['url'] : '');
												$AttImg .= '<img src="'.esc_url($imgUrl).'" alt="'.$altText.'"/>';
											}else{
												$AttImg .= '<img src="'.esc_url($Default_Img).'" alt="'.$altText.'"/>';
											}
											$TeamImage .= $AttImg;
										}else{
											if(!empty($ImgId['id'])){
												
												$AttImg .= wp_get_attachment_image($ImgId['id'] , 'full' , false, ['alt'=> $altText]);
											}else if(!empty($ImgId['url'])){
												$imgUrl = (isset($ImgId['dynamic']) && class_exists('Tpgbp_Pro_Blocks_Helper')) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_repeat_url($ImgId) : (!empty($ImgId['url']) ? $ImgId['url'] : '');
												$AttImg .= '<img src="'.esc_url($imgUrl).'" alt="'.$altText.'"/>';
											}else{
												$AttImg .= '<img src="'.esc_url($Default_Img).'" alt="'.$altText.'"/>';
											}
											$TeamImage .= $AttImg;
										}

										$linkImage .= '<div class="tpgb-team-profile">';
											$linkImage .= '<span class="thumb-wrap">'.$TeamImage.'</span>';
										$linkImage .= '</div>';

										if(!empty($DisbleLink)){
											$ImageHTML .= $linkImage;
										}else{
											$link_attr = (isset($TeamItem['CusUrl']) && class_exists('Tpgbp_Pro_Blocks_Helper')) ? Tpgbp_Pro_Blocks_Helper::add_link_attributes($TeamItem['CusUrl']) : '';
											$ImageHTML .= '<a href="'.esc_url($TeamCUrl).'" '.$link_attr.' aria-label="'.esc_attr($TeamName).'">'.$linkImage.'</a>';
										}
									}
								}

								$IconHTML = '';
								if( !empty($DisableIcon) ){
									$Nofollow=$Target="";

									$IconHTML .= '<div class="tpgb-team-social-content">';
										$IconHTML .= '<div class="tpgb-team-social-list">';
											if( !empty($TeamWsUrl) ){
												$wb_attr = (isset($TeamItem['WsUrl']) && class_exists('Tpgbp_Pro_Blocks_Helper')) ? Tpgbp_Pro_Blocks_Helper::add_link_attributes($TeamItem['WsUrl']) : "";
												$Target = ( !empty($TeamItem['WsUrl']) && !empty($TeamItem['WsUrl']['target']) ) ? 'target="_blank"' : "";
												$Nofollow = ( !empty($TeamItem['WsUrl']) && !empty($TeamItem['WsUrl']['nofollow']) ) ? 'rel="nofollow"' : "";
												$IconHTML .= '<div class="tpgb-team-profile-link">';
													$IconHTML .= '<a href="'.esc_url($TeamWsUrl).'" '.$Target.' '.$Nofollow.' '.$wb_attr.'  aria-label="'.esc_attr__('Site URL','the-plus-addons-for-block-editor').'"><i class="fas fa-globe" aria-hidden="true"></i></a>';
												$IconHTML .= '</div>';
											}
											if( !empty($TeamFbUrl) ){
												$fb_attr = (isset($TeamItem['FbUrl'])&& class_exists('Tpgbp_Pro_Blocks_Helper'))? Tpgbp_Pro_Blocks_Helper::add_link_attributes($TeamItem['FbUrl']):"";
												$Target = ( !empty($TeamItem['FbUrl']) && !empty($TeamItem['FbUrl']['target']) ) ? 'target="_blank"' : "";
												$Nofollow = ( !empty($TeamItem['FbUrl']) && !empty($TeamItem['FbUrl']['nofollow']) ) ? 'rel="nofollow"' : "";
												$IconHTML .= '<div class="fb-link">';
													$IconHTML .= '<a href="'.esc_url($TeamFbUrl).'" '.$Target.' '.$Nofollow.' '.$fb_attr.' aria-label="'.esc_attr__('Facebook','the-plus-addons-for-block-editor').'"><i class="fab fa-facebook-f" aria-hidden="true"></i></a>';
												$IconHTML .= '</div>';
											}
											if( !empty($TeamTwUrl) ){
												$tw_attr = (isset($TeamItem['TwUrl']) && class_exists('Tpgbp_Pro_Blocks_Helper')) ? Tpgbp_Pro_Blocks_Helper::add_link_attributes($TeamItem['TwUrl']) : "";
												$Target = ( !empty($TeamItem['TwUrl']) && !empty($TeamItem['TwUrl']['target']) ) ? 'target="_blank"' : "";
												$Nofollow = ( !empty($TeamItem['TwUrl']) && !empty($TeamItem['TwUrl']['nofollow']) ) ? 'rel="nofollow"' : "";
												$IconHTML .= '<div class="twitter-link">';
													$IconHTML .= '<a href="'.esc_url($TeamTwUrl).'" '.$Target.' '.$Nofollow.' '.$tw_attr.' aria-label="'.esc_attr__('Twitter','the-plus-addons-for-block-editor').'"><i class="fab fa-twitter" aria-hidden="true"></i></a>';
												$IconHTML .= '</div>';
											}
											if( !empty($TeamIGUrl) ){
												$ig_attr = (isset($TeamItem['IGUrl']) && class_exists('Tpgbp_Pro_Blocks_Helper')) ? Tpgbp_Pro_Blocks_Helper::add_link_attributes($TeamItem['IGUrl']) : '';
												$Target = ( !empty($TeamItem['IGUrl']) && !empty($TeamItem['IGUrl']['target']) ) ? 'target="_blank"' : "";
												$Nofollow = ( !empty($TeamItem['IGUrl']) && !empty($TeamItem['IGUrl']['nofollow']) ) ? 'rel="nofollow"' : "";
												$IconHTML .= '<div class="instagram-link">';
													$IconHTML .= '<a href="'.esc_url($TeamIGUrl).'" '.$Target.' '.$Nofollow.' '.$ig_attr.' aria-label="'.esc_attr__('Instagram','the-plus-addons-for-block-editor').'"><i class="fab fa-instagram" aria-hidden="true"></i></a>';
												$IconHTML .= '</div>';
											}
											if( !empty($TeamMailUrl) ){
												$ml_attr = (isset($TeamItem['MailUrl']) && class_exists('Tpgbp_Pro_Blocks_Helper')) ? Tpgbp_Pro_Blocks_Helper::add_link_attributes($TeamItem['MailUrl']) : "";
												$Target = ( !empty($TeamItem['MailUrl']) && !empty($TeamItem['MailUrl']['target']) ) ? 'target="_blank"' : "";
												$Nofollow = ( !empty($TeamItem['MailUrl']) && !empty($TeamItem['MailUrl']['nofollow']) ) ? 'rel="nofollow"' : "";
												$IconHTML .= '<div class="mail-link">';
													$IconHTML .= '<a href="'.esc_url($TeamMailUrl).'" '.$Target.' '.$Nofollow.' '.$ml_attr.' aria-label="'.esc_attr__('Mail','the-plus-addons-for-block-editor').'"><i class="fas fa-envelope-square"></i></a>';
												$IconHTML .= '</div>';
											}
											if( !empty($TeamItem['ldUrl']['url']) ){
												$ld_attr = (isset($TeamItem['ldUrl']) && class_exists('Tpgbp_Pro_Blocks_Helper')) ? Tpgbp_Pro_Blocks_Helper::add_link_attributes($TeamItem['ldUrl']) : '';
												$Target = ( !empty($TeamItem['ldUrl']['target']) ) ? 'target="_blank"' : "";
												$Nofollow = ( !empty($TeamItem['ldUrl']['nofollow']) ) ? 'rel="nofollow"' : "";
												$IconHTML .= '<div class="linkedin-link">';
													$IconHTML .= '<a href="'.esc_url($TeamItem['ldUrl']['url']).'" '.$Target.' '.$Nofollow.' '.$ld_attr.' aria-label="'.esc_attr__('LinkedIn','the-plus-addons-for-block-editor').'"><i class="fab fa-linkedin-in" aria-hidden="true"></i></a>';
												$IconHTML .= '</div>';
											}
											if( !empty($Telephone) ){
												$IconHTML .= '<div class="Telephone-link">';
													$IconHTML .= '<a href="'.esc_url('tel:'.$Telephone).'" aria-label="'.esc_attr__('Phone No','the-plus-addons-for-block-editor').'"><i class="fas fa-phone" aria-hidden="true"></i></a>';
												$IconHTML .= '</div>';
											}
										$IconHTML .= '</div>';
									$IconHTML .= '</div>';	
								}

							$TitleHTML = '';
							if(!empty($TeamName)){
								$TitleHTML .= '<'.Tp_Blocks_Helper::validate_html_tag($TitleTag).' class="tpgb-post-title">';
									if( !empty($DisbleLink) ){
										$TitleHTML .= wp_kses_post($TeamName);
									}else{
										$link_attr = (isset($TeamItem['CusUrl']) && class_exists('Tpgbp_Pro_Blocks_Helper')) ? Tpgbp_Pro_Blocks_Helper::add_link_attributes($TeamItem['CusUrl']) : '';
										$TitleHTML .= '<a href="'.esc_attr($TeamCUrl).'" '.$link_attr.'>'.wp_kses_post($TeamName).'</a>';
									}
								$TitleHTML .= '</'.Tp_Blocks_Helper::validate_html_tag($TitleTag).'>';
							}

							$DesigHTML = '';
							if( !empty($TeamDesignation) && !empty($Designation) ){
								$DesigHTML .= '<div class="tpgb-member-designation">'.wp_kses_post($TeamDesignation).'</div>';
							}					

							$FinalHTML = '';
							if( $style == 'style-1' ){
								$FinalHTML .= '<div class="post-content-image">';
									$FinalHTML .= $ImageHTML;
									$FinalHTML .= $IconHTML;
								$FinalHTML .= '</div>';
								$FinalHTML .= '<div class="post-content-bottom">';
									$FinalHTML .= $TitleHTML;
									$FinalHTML .= $DesigHTML;
								$FinalHTML .= '</div>';
							}

							$TeamMember .= $FinalHTML;
						$TeamMember .= '</div>';
					$TeamMember .= '</div>';
				}
			}
		$TeamMember .= "</div>";
	$TeamMember .= "</div>";
	
	$TeamMember = Tpgb_Blocks_Global_Options::block_Wrap_Render($attributes, $TeamMember);
    return $TeamMember;
}

}
if (!function_exists('tpgb_tp_team_member_listing')) {
function tpgb_tp_team_member_listing() {
	/* $globalBgOption = Tpgb_Blocks_Global_Options::load_bg_options();
	$globalpositioningOption = Tpgb_Blocks_Global_Options::load_positioning_options();
	$globalPlusExtrasOption = Tpgb_Blocks_Global_Options::load_plusextras_options();
	$carousel_options = Tpgb_Blocks_Global_Options::carousel_options();
	$sliderOpt = [
		'slideColumns' => [
			'type' => 'object',
			'default' => [ 'md' => 4,'sm' => 3,'xs' => 2 ],
		],
	];
	$carousel_options = array_merge($carousel_options,$sliderOpt);
	$attributesOptions = [
			'block_id' => [
                'type' => 'string',
				'default' => '',
			],
			'Style' => [
				'type' => 'string',
				'default' => 'style-1',	
			],
			'layout' => [
				'type' => 'string',
				'default' => 'grid',	
			],
			'Alignment' => [
				'type' => 'object',
				'default' => [ 'md' => 'left', 'sm' =>  '', 'xs' =>  '' ],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-team-member-list .post-content-bottom{text-align:{{Alignment}};}',
					],
					(object) [
						'condition' => [(object) ['key' => 'Style', 'relation' => '==', 'value' => 'style-3']],
						'selector' => '{{PLUS_WRAP}}.tpgb-team-member-list .post-content-bottom .tpgb-post-title,
						{{PLUS_WRAP}}.tpgb-team-member-list .post-content-bottom .tpgb-member-designation{text-align:{{Alignment}};}',
					],
				],
				'scopy' => true,
			],	

			'TeamMemberR' => [
				'type'=> 'array',
				'repeaterField' => [
					(object) [
						'TName' => [
							'type'=> 'string',
							'default'=> 'Team Member',
						],
						'TImage' => [
							'type' => 'object',
							'default' => [
								'url' => TPGB_ASSETS_URL.'assets/images/tpgb-placeholder.jpg',
								'Id' => '',
							],
						],						
						'TDesig' => [
							'type'=> 'string',
							'default'=> 'Manager',
						],
						'TCateg' => [
							'type'=> 'string',
							'default'=> '',
						],
						'CusUrl' => [
							'type'=> 'object',
							'default'=> [
								'url' => '',
								'target' => '',
								'nofollow' => ''
							],
						],
						'WsUrl' => [
							'type'=> 'object',
							'default'=> [
								'url' => '',
								'target' => '',
								'nofollow' => ''
							],
						],
						'FbUrl' => [
							'type'=> 'object',
							'default'=> [
								'url' => '',
								'target' => '',
								'nofollow' => ''
							],
						],
						'MailUrl' => [
							'type'=> 'object',
							'default'=> [
								'url' => '',
								'target' => '',
								'nofollow' => ''
							],
						],
						'IGUrl' => [
							'type'=> 'object',
							'default'=> [
								'url' => '',
								'target' => '',
								'nofollow' => ''
							],
						],
						'TwUrl' => [
							'type'=> 'object',
							'default'=> [
								'url' => '',
								'target' => '',
								'nofollow' => ''
							],
						],
						'ldUrl' => [
							'type'=> 'object',
							'default'=> [
								'url' => '',
								'target' => '',
								'nofollow' => ''
							],
						],
						'TelNum' => [
							'type'=> 'string',
							'default'=> '',
						],
					],
				],
				'default' => [
					['TName' =>'John Doe','TImage'=>['url'=>TPGB_ASSETS_URL.'assets/images/tpgb-placeholder.jpg'],'TDesig'=>'Director','CusUrl'=>['url'=>''],'WsUrl'=>['url'=>''],'FbUrl'=>['url'=>'#'],'MailUrl'=>['url'=>''],'IGUrl'=>['url'=>'#'],'TwUrl'=>['url'=>''],'ldUrl'=>['url'=>''], 'TelNum' => ''],
				],
			],

			'columns' => [
				'type' => 'object',
				'default' => [ 'md' => 3,'sm' => 4,'xs' => 6 ],
			],
			'columnSpace' => [
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
						'selector' => '{{PLUS_WRAP}} .grid-item{padding:{{columnSpace}};}',
					],
				],
			],

			'TitleTag' => [
				'type' => 'string',
				'default' => 'h3',	
			],
			'FImageTp' => [
				'type' => 'string',
				'default' => 'full',	
			],
			'DesignDis' => [
				'type' => 'boolean',
				'default' => true,	
			],
			'SocialIcon' => [
				'type' => 'boolean',
				'default' => true,	
			],
			'DisLink' => [
				'type' => 'boolean',
				'default' => false,	
			],
			'DImgS' => [
				'type' => 'boolean',
				'default' => true,	
			],
			'ImgSize' => [
				'type' => 'string',
				'default' => 'full',	
			],
			'CategoryWF' => [
				'type' => 'boolean',
				'default' => False,	
			],
			'TextCat' => [
				'type'=> 'string',
				'default'=> 'All',
			],
			'CatFilterS' => [
				'type' => 'string',
				'default' => 'style-1',	
			],
			'CatName' => [
				'type'=> 'string',
				'default'=> 'Filters',
			],
			'FilterHs' => [
				'type' => 'string',
				'default' => 'style-1',	
			],
			'FilterAlig' => [
				'type' => 'string',
				'default' =>  [ 'md' => 'center', 'sm' =>  '', 'xs' =>  '' ],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'CategoryWF', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}} .tpgb-filter-data{text-align:{{FilterAlig}};}',
					],
				],
				'scopy' => true,
			],

			'TitleTypo' => [
				'type'=> 'object',
				'default'=> (object) [
					'openTypography' => 0
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-team-member-list .tpgb-post-title,{{PLUS_WRAP}}.tpgb-team-member-list .tpgb-post-title a',
					],
				],
				'scopy' => true,
			],
			'TNcolor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [						
						'selector' => '{{PLUS_WRAP}}.tpgb-team-member-list .tpgb-post-title,{{PLUS_WRAP}}.tpgb-team-member-list .tpgb-post-title a{color:{{TNcolor}};}',
					],
				],
				'scopy' => true,
			],
			'THcolor' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-team-member-list .team-list-content:hover .tpgb-post-title,
							{{PLUS_WRAP}}.tpgb-team-member-list .team-list-content:hover .tpgb-post-title a{color:{{THcolor}};}',
					],
				],
				'scopy' => true,
			],

			'TextTypo' => [
				'type'=> 'object',
				'default'=> (object) [
					'openTypography' => 0
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'DesignDis', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}}.tpgb-team-member-list .tpgb-member-designation',
					],
				],
				'scopy' => true,
			],
			'TextNCr' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'DesignDis', 'relation' => '==', 'value' => true]],						
						'selector' => '{{PLUS_WRAP}}.tpgb-team-member-list .tpgb-member-designation{color:{{TextNCr}};}',
					],
				],
				'scopy' => true,
			],
			'TextHCr' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'DesignDis', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}}.tpgb-team-member-list .team-list-content:hover .tpgb-member-designation{color:{{TextHCr}};}',
					],
				],
				'scopy' => true,
			],

			'Iconsize' => [
				'type' => 'object',
				'default' => [ 
					'md' => '',
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'condition' => [ (object) ['key' => 'SocialIcon', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}}.tpgb-team-member-list .tpgb-team-social-content .tpgb-team-social-list > div a{font-size:{{Iconsize}};}',
					],
				],
				'scopy' => true,
			],
			'IconBgsize' => [
				'type' => 'object',
				'default' => [ 
					'md' => '',
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'condition' => [ (object) ['key' => 'SocialIcon', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}}.tpgb-team-member-list .tpgb-team-social-content .tpgb-team-social-list > div a{width:{{IconBgsize}};height:{{IconBgsize}};line-height:{{IconBgsize}};}',
					],
				],
				'scopy' => true,
			],
			'IconNCr' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [ (object) ['key' => 'SocialIcon', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}}.tpgb-team-member-list .tpgb-team-social-content .tpgb-team-social-list > div a{color:{{IconNCr}};}',
					],
				],
				'scopy' => true,
			],
			'IconNBgCr' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [ (object) ['key' => 'SocialIcon', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}}.tpgb-team-member-list .grid-item .tpgb-team-social-content .tpgb-team-social-list > div a{background:{{IconNBgCr}};}',
					],
				],
				'scopy' => true,
			],
			'IconHCr' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [ (object) ['key' => 'SocialIcon', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}}.tpgb-team-member-list .tpgb-team-social-content .tpgb-team-social-list > div a:hover{color:{{IconHCr}};}',
					],
				],
				'scopy' => true,
			],
			'IconHBgCr' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [ (object) ['key' => 'SocialIcon', 'relation' => '==', 'value' => true]],								
						'selector' => '{{PLUS_WRAP}}.tpgb-team-member-list .grid-item .tpgb-team-social-content .tpgb-team-social-list > div a:hover{background:{{IconHBgCr}};}',
					],
				],
				'scopy' => true,
			],

			'MaskImg' => [
				'type' => 'object',
				'default' => [
					'url' => '',
					'Id' => '',
				],				
			],
			'Imagesd' => [
				'type' => 'object',
				'default' => (object) [
					'openShadow' => 0,
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'Style', 'relation' => '==', 'value' => 'style-4']],	
						'selector' => '{{PLUS_WRAP}}.tpgb-team-member-list.team-style-4 .team-list-content .tpgb-team-profile',
					],
				],
				'scopy' => true,
			],
			'ExLImg' => [
				'type' => 'object',
				'default' => [
					'url' => '',
					'Id' => '',
				],
			],
			'AExlImg' => [
				'type' => 'string',
				'default' => 'none',	
				'scopy' => true,
			],
			'HAnimation' => [
				'type' => 'boolean',
				'default' => false,	
				'scopy' => true,
			],

			'FIMargin' => [
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
						'selector' => '{{PLUS_WRAP}}.tpgb-team-member-list .post-content-image{margin:{{FIMargin}};}',
					],
				],
				'scopy' => true,
			],
			'FIPadding' => [
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
						'selector' => '{{PLUS_WRAP}}.tpgb-team-member-list .post-content-image{padding:{{FIPadding}}; }',
					],
				],
				'scopy' => true,
			],
			'FImgBs' => [
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
						'selector' => '{{PLUS_WRAP}}.tpgb-team-member-list .tpgb-team-profile img,
									{{PLUS_WRAP}}.tpgb-team-member-list .post-content-image,
									{{PLUS_WRAP}}.tpgb-team-member-list.team-style-2 .tpgb-team-profile{border-radius:{{FImgBs}};}',
					],
				],
				'scopy' => true,
			],
			'InnerBgCr' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [					
						'selector' => '{{PLUS_WRAP}}.tpgb-team-member-list .post-content-image{background:{{InnerBgCr}};}',
					],
				],
				'scopy' => true,
			],

			'NFilter' => [
				'type' => 'object',
				'default' => [
					'openFilter' => false,
				],
				'style' => [
					(object) [					
						'selector' => '{{PLUS_WRAP}}.tpgb-team-member-list .post-content-image img',
					],
				],
				'scopy' => true,
			],
			'NBoxSd' => [
				'type' => 'object',
				'default' => (object) [
					'openBorder' => 0,
				],
				'style' => [
					(object) [						
						'selector' => '{{PLUS_WRAP}}.tpgb-team-member-list .post-content-image',
					],
				],
				'scopy' => true,
			],
			'HFilter' => [
				'type' => 'object',
				'default' => [
					'openFilter' => false,
				],
				'style' => [
					(object) [						
						'selector' => '{{PLUS_WRAP}}.tpgb-team-member-list .team-list-content:hover .post-content-image img',
					],
				],
			],
			'HBoxSd' => [
				'type' => 'object',
				'default' => (object) [
					'openBorder' => 0,
				],
				'style' => [
					(object) [						
						'selector' => '{{PLUS_WRAP}}.tpgb-team-member-list .team-list-content:hover .post-content-image',
					],
				],
				'scopy' => true,
			],

			'FcatTypo' => [
				'type'=> 'object',
				'default'=> (object) [
					'openTypography' => 0,
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'layout', 'relation' => '!=', 'value' => 'carousel'],
										(object) ['key' => 'CategoryWF', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}} .tpgb-categories .tpgb-filter-list a',
					],
				],
				'scopy' => true,
			],
			'InPadding' => [
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
						'condition' => [(object) ['key' => 'FilterHs', 'relation' => '==', 'value' => 'style-1'],
										(object) ['key' => 'layout', 'relation' => '!=', 'value' => 'carousel'],
										(object) ['key' => 'CategoryWF', 'relation' => '==', 'value' => true]],	
						'selector' => '{{PLUS_WRAP}} .tpgb-categories.hover-style-1 .tpgb-filter-list a span:not(.tpgb-category-count){padding:{{InPadding}};}',
					],
					(object) [
						'condition' => [(object) ['key' => 'FilterHs', 'relation' => '==', 'value' => 'style-2'],
										(object) ['key' => 'layout', 'relation' => '!=', 'value' => 'carousel'],
										(object) ['key' => 'CategoryWF', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}} .tpgb-categories.hover-style-2 .tpgb-filter-list a span:not(.tpgb-category-count),{{PLUS_WRAP}} .tpgb-categories.hover-style-2 .tpgb-filter-list a span:not(.tpgb-category-count)::before,{{PLUS_WRAP}} .tpgb-categories.hover-style-2 .tpgb-filter-list a span:not(.tpgb-category-count)::before{padding:{{InPadding}};}',
					],
					(object) [
						'condition' => [(object) ['key' => 'FilterHs', 'relation' => '==', 'value' => 'style-3'],
										(object) ['key' => 'layout', 'relation' => '!=', 'value' => 'carousel'],
										(object) ['key' => 'CategoryWF', 'relation' => '==', 'value' => true]],	
						'selector' => '{{PLUS_WRAP}} .tpgb-categories.hover-style-3 .tpgb-filter-list a{padding:{{InPadding}};}',
					],
					(object) [
						'condition' => [(object) ['key' => 'FilterHs', 'relation' => '==', 'value' => 'style-4'],
										(object) ['key' => 'layout', 'relation' => '!=', 'value' => 'carousel'],
										(object) ['key' => 'CategoryWF', 'relation' => '==', 'value' => true]],	
						'selector' => '{{PLUS_WRAP}} .tpgb-categories.hover-style-4 .tpgb-filter-list a{padding:{{InPadding}};}',
					],
				],
				'scopy' => true,
			],
			'FCMargin' => [
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
						'condition' => [(object) ['key' => 'layout', 'relation' => '!=', 'value' => 'carousel'],
										(object) ['key' => 'CategoryWF', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}} .tpgb-categories .tpgb-filter-list{margin:{{FCMargin}};}',
					],
				],
				'scopy' => true,
			],
			'FCNcr' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'layout', 'relation' => '!=', 'value' => 'carousel'],
										(object) ['key' => 'CategoryWF', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}}.tpgb-category-filter .tpgb-categories .tpgb-filter-list a,{{PLUS_WRAP}}.tpgb-category-filter .tpgb-categories .tpgb-filter-list a.all span.tpgb-category-count{color:{{FCNcr}};}',
					],
				],
				'scopy' => true,
			],
			'FCHBcr' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'FilterHs', 'relation' => '==', 'value' => 'style-1'],
										(object) ['key' => 'layout', 'relation' => '!=', 'value' => 'carousel'],
										(object) ['key' => 'CategoryWF', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}}.tpgb-category-filter .hover-style-1 .tpgb-filter-list a::after{background:{{FCHBcr}};}',
					],
				],
				'scopy' => true,
			],
			'FCHcr' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'layout', 'relation' => '!=', 'value' => 'carousel'],
										(object) ['key' => 'CategoryWF', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}}.tpgb-category-filter:not(.hover-style-2) .tpgb-filter-list a:hover,
						{{PLUS_WRAP}}.tpgb-category-filter:not(.hover-style-2) .tpgb-filter-list a:focus,
						{{PLUS_WRAP}}.tpgb-category-filter:not(.hover-style-2) .tpgb-filter-list a.active,
						{{PLUS_WRAP}}.tpgb-category-filter .hover-style-2 .tpgb-filter-list a span:not(.tpgb-category-count)::before{color:{{FCHcr}};}',
					],
				],
				'scopy' => true,
			],
			'FCBgHvrs' => [
				'type' => 'object',
				'default' => (object) [
					'openBg'=> 0,
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'layout', 'relation' => '!=', 'value' => 'carousel'],
										(object) ['key' => 'CategoryWF', 'relation' => '==', 'value' => true],
										(object) ['key' => 'FilterHs', 'relation' => '==', 'value' => ['style-2','style-4']]],
						'selector' => '.tpgb-category-filter .tpgb-categories.hover-style-2 .tpgb-filter-list a.tpgb-category-list:hover span:not(.tpgb-category-count):before,.tpgb-category-filter .tpgb-categories.hover-style-2 .tpgb-filter-list a.tpgb-category-list.active span:not(.tpgb-category-count):before',
	
					],
				],
				'scopy' => true,
			],
			'FCHvrBre' => [
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
						'condition' => [(object) ['key' => 'layout', 'relation' => '!=', 'value' => 'carousel'],
										(object) ['key' => 'Category', 'relation' => '==', 'value' => true],
										(object) ['key' => 'FilterHs', 'relation' => '==', 'value' => 'style-2']],
						'selector' => '{{PLUS_WRAP}}.tpgb-category-filter .tpgb-categories.hover-style-2 .tpgb-filter-list a:hover span:not(.tpgb-category-count):before,{{PLUS_WRAP}}.tpgb-category-filter .tpgb-categories.hover-style-2 .tpgb-filter-list a.active span:not(.tpgb-category-count):before{border-radius:{{FCHvrBre}};}',
					],
				],
				'scopy' => true,
			],
			'FcBoxhversd'=> [
				'type' => 'object',
				'default' => (object) [
					'openShadow' => 0,
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'layout', 'relation' => '!=', 'value' => 'carousel'],
										(object) ['key' => 'Category', 'relation' => '==', 'value' => true],
										(object) ['key' => 'FilterHs', 'relation' => '==', 'value' => 'style-2']],
						'selector' => '{{PLUS_WRAP}}.tpgb-category-filter .tpgb-categories.hover-style-2 .tpgb-filter-list a:hover span:not(.tpgb-category-count):before,{{PLUS_WRAP}}.tpgb-category-filter .tpgb-categories.hover-style-2 .tpgb-filter-list a.active span:not(.tpgb-category-count):before',
					],
				],
				'scopy' => true,
			],
			'FCBgHs' => [
				'type' => 'object',
				'default' => (object) [
					'openBg'=> 0,
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'FilterHs', 'relation' => '==', 'value' => 'style-2'],
										(object) ['key' => 'layout', 'relation' => '!=', 'value' => 'carousel'],
										(object) ['key' => 'CategoryWF', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}}.tpgb-category-filter .tpgb-categories.hover-style-2 .tpgb-filter-list a span:not(.tpgb-category-count)',
					],
					(object) [
						'condition' => [(object) ['key' => 'FilterHs', 'relation' => '==', 'value' => 'style-4'],
										(object) ['key' => 'layout', 'relation' => '!=', 'value' => 'carousel'],
										(object) ['key' => 'CategoryWF', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}}.tpgb-category-filter .tpgb-categories.hover-style-4 .tpgb-filter-list a:after',
					],
				],
				'scopy' => true,
			],
			'FCBgRs' => [
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
						'condition' => [(object) ['key' => 'layout', 'relation' => '!=', 'value' => 'carousel'],
										(object) ['key' => 'CategoryWF', 'relation' => '==', 'value' => true],
										(object) ['key' => 'FilterHs', 'relation' => '==', 'value' => 'style-2']],
						'selector' => '{{PLUS_WRAP}}.tpgb-category-filter .tpgb-categories.hover-style-2 .tpgb-filter-list a span:not(.tpgb-category-count){border-radius:{{FCBgRs}};}',
					],
				],
				'scopy' => true,
			],			
			'FcBoxhsd' => [
				'type' => 'object',
				'default' => (object) [
					'openShadow' => 0,
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'layout', 'relation' => '!=', 'value' => 'carousel'],
										(object) ['key' => 'CategoryWF', 'relation' => '==', 'value' => true],
										(object) ['key' => 'FilterHs', 'relation' => '==', 'value' => 'style-2']],
						'selector' => '{{PLUS_WRAP}}.tpgb-category-filter .tpgb-categories.hover-style-2 .tpgb-filter-list a span:not(.tpgb-category-count)',
					],
				],
				'scopy' => true,
			],
			'FCCategCcr' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'layout', 'relation' => '!=', 'value' => 'carousel'],
										(object) ['key' => 'CategoryWF', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}}.tpgb-category-filter .tpgb-categories .tpgb-filter-list a.all span.tpgb-category-count{color:{{FCCategCcr}};}',
					],
				],
				'scopy' => true,
			],
						
			'FCBgTp' => [
				'type' => 'object',
				'default' => (object) [
					'openBg'=> 0,
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'layout', 'relation' => '!=', 'value' => 'carousel'],(object) ['key' => 'CategoryWF', 'relation' => '==', 'value' => true],(object) ['key' => 'CatFilterS', 'relation' => '==', 'value' => 'style-1']],
						'selector' => '{{PLUS_WRAP}}.tpgb-category-filter .tpgb-categories.style-1 .tpgb-filter-list a.all span.tpgb-category-count',
					],
					(object) [
						'condition' => [(object) ['key' => 'layout', 'relation' => '!=', 'value' => 'carousel'],(object) ['key' => 'CategoryWF', 'relation' => '==', 'value' => true],(object) ['key' => 'CatFilterS', 'relation' => '==', 'value' => 'style-3']],
						'selector' => '{{PLUS_WRAP}}.tpgb-category-filter .tpgb-categories.style-3 .tpgb-filter-list a.all span.tpgb-category-count',
					],
				],
				'scopy' => true,
			],
			'FcBCrHs' => [
				'type' => 'string',
				'default' => '',
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'layout', 'relation' => '!=', 'value' => 'carousel'],
										(object) ['key' => 'CategoryWF', 'relation' => '==', 'value' => true],
										(object) ['key' => 'FilterHs', 'relation' => '==', 'value' => 'style-4']],
						'selector' => '{{PLUS_WRAP}}.tpgb-category-filter .tpgb-categories.hover-style-4 .tpgb-filter-list a:before{border-top-color:{{FcBCrHs}};}',
					],
				],
				'scopy' => true,
			],	
			'FCBoxSd' => [
				'type' => 'object',
				'default' =>  (object) [
					'openShadow' => 0,
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'layout', 'relation' => '!=', 'value' => 'carousel'],
										(object) ['key' => 'CategoryWF', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}}.tpgb-category-filter .tpgb-categories.style-1 .tpgb-filter-list a.all span.tpgb-category-count',
					],
				],
				'scopy' => true,
			],

			'BoxPadding' => [
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
						'selector' => '{{PLUS_WRAP}}.tpgb-team-member-list .team-list-content{padding:{{BoxPadding}};}',
					],
				],
				'scopy' => true,
			],
			'BoxTborder' => [
				'type' => 'boolean',
				'default' => false,	
				'scopy' => true,
			],
			'Boxborder' => [
				'type' => 'object',
				'default' => (object) [
					'openBorder' => 0,	
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'BoxTborder', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}}.tpgb-team-member-list .team-list-content',
					],
				],
				'scopy' => true,
			],
			'BoxNBrs' => [
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
						'selector' => '{{PLUS_WRAP}}.tpgb-team-member-list .team-list-content{border-radius:{{BoxNBrs}};}',
					],
				],
				'scopy' => true,
			],
			'BoxHBor' => [
				'type' => 'object',
				'default' => (object) [
					'openBorder' => 0,	
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'BoxTborder', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}}.tpgb-team-member-list .team-list-content:hover',
					],
				],
				'scopy' => true,
			],
			'BoxHBrs' => [
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
						'selector' => '{{PLUS_WRAP}}.tpgb-team-member-list .team-list-content:hover{border-radius:{{BoxHBrs}};}',
					],
				],
				'scopy' => true,
			],
			'BoxNBg' => [
				'type' => 'object',
				'default' => (object) [
					'openBg'=> 0,
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-team-member-list .team-list-content',
					],
				],
				'scopy' => true,
			],
			'BoxHBg' => [
				'type' => 'object',
				'default' => (object) [
					'openBg'=> 0,
				],
				'style' => [
					(object) [						
						'selector' => '{{PLUS_WRAP}}.tpgb-team-member-list .team-list-content:hover',
					],
				],
				'scopy' => true,
			],
			'BoxNSd' => [
				'type' => 'object',
				'default' => (object) [
					'openShadow' => 0,
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-team-member-list .team-list-content',
					],
				],
				'scopy' => true,
			],
			'BoxHSd' => [
				'type' => 'object',
				'default' => (object) [
					'openShadow' => 0,
				],
				'style' => [
					(object) [
						'selector' => '{{PLUS_WRAP}}.tpgb-team-member-list .team-list-content:hover',
					],
				],
				'scopy' => true,
			],

			'MessyCol' => [
				'type' => 'boolean',
				'default' => false,
				'scopy' => true,
			],
			'Column1' => [
				'type' => 'object',
				'default' => [
					'md' => '',
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'MessyCol', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}} .post-loop-inner .grid-item:nth-child(6n+1){margin-top:{{Column1}};}',
					],
				],
				'scopy' => true,
			],
			'Column2' => [
				'type' => 'object',
				'default' => [
					'md' => '',
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'MessyCol', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}} .post-loop-inner .grid-item:nth-child(6n+2){margin-top:{{Column2}};}',
					],
				],
				'scopy' => true,
			],
			'Column3' => [
				'type' => 'object',
				'default' => [
					'md' => '',
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'MessyCol', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}} .post-loop-inner .grid-item:nth-child(6n+3){margin-top:{{Column3}};}',
					],
				],
				'scopy' => true,
			],
			'Column4' => [
				'type' => 'object',
				'default' => [
					'md' => '',
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'MessyCol', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}} .post-loop-inner .grid-item:nth-child(6n+4){margin-top:{{Column4}};}',
					],
				],
				'scopy' => true,
			],
			'Column5' => [
				'type' => 'object',
				'default' => [
					'md' => '',
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'MessyCol', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}} .post-loop-inner .grid-item:nth-child(6n+5){margin-top:{{Column5}};}',
					],
				],
				'scopy' => true,
			],
			'Column6' => [
				'type' => 'object',
				'default' => [
					'md' => '',
					"unit" => 'px',
				],
				'style' => [
					(object) [
						'condition' => [(object) ['key' => 'MessyCol', 'relation' => '==', 'value' => true]],
						'selector' => '{{PLUS_WRAP}} .post-loop-inner .grid-item:nth-child(6n+6){margin-top:{{Column6}};}',
					],
				],
				'scopy' => true,
			],

		];
		
	$attributesOptions = array_merge($attributesOptions, $carousel_options, $globalBgOption, $globalpositioningOption, $globalPlusExtrasOption);
	
	register_block_type( 'tpgb/tp-team-listing', array(
		'attributes' => $attributesOptions,
		'editor_script' => 'tpgb-block-editor-js',
		'editor_style'  => 'tpgb-block-editor-css',
        'render_callback' => 'tpgb_tp_team_member_listing_render_callback'
    ) ); */
	if(method_exists('Tpgb_Blocks_Global_Options', 'merge_options_json')){
		$block_data = Tpgb_Blocks_Global_Options::merge_options_json(__DIR__, 'tpgb_tp_team_member_listing_render_callback', true, true);
		register_block_type( $block_data['name'], $block_data );
	}
}
}
add_action( 'init', 'tpgb_tp_team_member_listing' );