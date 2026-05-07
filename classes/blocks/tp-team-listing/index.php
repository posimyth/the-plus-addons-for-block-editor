<?php
/**
 * Team Member Listing.
 *
 * @package ThePluginAddonsForBlockEditor
 */

defined( 'ABSPATH' ) || exit;
if ( ! function_exists( 'tpgb_tp_team_member_listing_render_callback' ) ) {
	/**
	 * Tpgb tp team member listing render callback.
	 *
	 * @param mixed $attributes The attributes.
	 * @param mixed $content The content.
	 * @return mixed The result.
	 */
	function tpgb_tp_team_member_listing_render_callback( $attributes, $content ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
		$team_member    = '';
		$block_id       = ( ! empty( $attributes['block_id'] ) ) ? $attributes['block_id'] : uniqid( 'title' );
		$style          = ( ! empty( $attributes['Style'] ) ) ? $attributes['Style'] : 'style-1';
		$layout         = ( ! empty( $attributes['layout'] ) ) ? $attributes['layout'] : 'grid';
		$disble_link    = ( ! empty( $attributes['DisLink'] ) ) ? $attributes['DisLink'] : false;
		$team_member_r  = ( ! empty( $attributes['TeamMemberR'] ) ) ? $attributes['TeamMemberR'] : array();
		$columns        = ( ! empty( $attributes['columns'] ) ) ? $attributes['columns'] : 'md';
		$title_tag      = ( ! empty( $attributes['TitleTag'] ) ) ? $attributes['TitleTag'] : 'h3';
		$designation    = ( ! empty( $attributes['DesignDis'] ) ) ? $attributes['DesignDis'] : false;
		$disable_icon   = ( ! empty( $attributes['SocialIcon'] ) ) ? $attributes['SocialIcon'] : false;
		$disable_i_size = ( ! empty( $attributes['DImgS'] ) ) ? $attributes['DImgS'] : false;
		$image_size     = ( ! empty( $attributes['ImgSize'] ) ) ? $attributes['ImgSize'] : 'full';

		$show_arrows = ( ! empty( $attributes['showArrows'] ) ) ? $attributes['showArrows'] : array( 'md' => false );

		$default_img = TPGB_ASSETS_URL . 'assets/images/tpgb-placeholder.jpg';

		$block_class = Tp_Blocks_Helper::block_wrapper_classes( $attributes );

		$desktop_class = '';
		if ( 'carousel' !== $layout && 'metro' !== $layout && $columns ) {
			$desktop_class .= 'tpgb-col-' . esc_attr( $columns['xs'] );
			$desktop_class .= ' tpgb-col-lg-' . esc_attr( $columns['md'] );
			$desktop_class .= ' tpgb-col-md-' . esc_attr( $columns['sm'] );
			$desktop_class .= ' tpgb-col-sm-' . esc_attr( $columns['xs'] );
		}

		$team_member .= '<div id="' . esc_attr( $block_id ) . '" class="tpgb-block-' . esc_attr( $block_id ) . ' tpgb-relative-block  tpgb-team-member-list team-' . esc_attr( $style ) . ' ' . esc_attr( $block_class ) . ' " data-style="' . esc_attr( $style ) . '" data-layout="' . esc_attr( $layout ) . '" data-id="' . esc_attr( $block_id ) . '">';
		$team_member .= '<div class="post-loop-inner tpgb-row">';

		if ( ! empty( $team_member_r ) ) {
			foreach ( $team_member_r as $index => $team_item ) {
				$team_name        = ( isset( $team_item['TName'] ) && class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_val( $team_item['TName'] ) : $team_item['TName'];
				$team_designation = ( ! empty( $team_item['TDesig'] ) ) ? $team_item['TDesig'] : '';
				$img_id           = ( ! empty( $team_item['TImage'] ) ) ? $team_item['TImage'] : array();
				$team_c_url       = ( isset( $team_item['CusUrl']['dynamic'] ) && class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_repeat_url( $team_item['CusUrl'] ) : ( ! empty( $team_item['CusUrl']['url'] ) ? $team_item['CusUrl']['url'] : '' );
				$team_ws_url      = ( isset( $team_item['WsUrl']['dynamic'] ) && class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_repeat_url( $team_item['WsUrl'] ) : ( ! empty( $team_item['WsUrl']['url'] ) ? $team_item['WsUrl']['url'] : '' );
				$team_fb_url      = ( isset( $team_item['FbUrl']['dynamic'] ) && class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_repeat_url( $team_item['FbUrl'] ) : ( ! empty( $team_item['FbUrl']['url'] ) ? $team_item['FbUrl']['url'] : '' );
				$team_mail_url    = ( isset( $team_item['MailUrl']['dynamic'] ) && class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_repeat_url( $team_item['MailUrl'] ) : ( ! empty( $team_item['MailUrl']['url'] ) ? $team_item['MailUrl']['url'] : '' );
				$team_ig_url      = ( isset( $team_item['IGUrl']['dynamic'] ) && class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_repeat_url( $team_item['IGUrl'] ) : ( ! empty( $team_item['IGUrl']['url'] ) ? $team_item['IGUrl']['url'] : '' );
				$team_tw_url      = ( isset( $team_item['TwUrl']['dynamic'] ) && class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_repeat_url( $team_item['TwUrl'] ) : ( ! empty( $team_item['TwUrl']['url'] ) ? $team_item['TwUrl']['url'] : '' );
				$teamld_url       = ( isset( $team_item['ldUrl']['dynamic'] ) && class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_repeat_url( $team_item['ldUrl'] ) : ( ! empty( $team_item['ldUrl']['url'] ) ? $team_item['ldUrl']['url'] : '' );
				$telephone        = ( isset( $team_item['TelNum'] ) && class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_val( $team_item['TelNum'] ) : $team_item['TelNum'];

				// Set Default Image Url.
				if ( empty( $img_id ) ) {
					$img_id['url'] = $default_img;
				}

				$team_member     .= '<div class="grid-item ' . esc_attr( $desktop_class ) . '">';
					$team_member .= '<div class="team-list-content tpgb-trans-linear">';

							$image_html = '';
							$team_image = '';
							$att_img    = '';
				if ( ! empty( $team_c_url ) || ! empty( $img_id ) ) {

					if ( ! empty( $img_id ) ) {
									$link_image = '';
									$alt_text   = ( isset( $img_id['alt'] ) && ! empty( $img_id['alt'] ) ) ? esc_attr( $img_id['alt'] ) : ( ( ! empty( $img_id['title'] ) ) ? esc_attr( $img_id['title'] ) : esc_attr__( 'Profile Image', 'the-plus-addons-for-block-editor' ) );
						if ( 'carousel' !== $layout && ! empty( $disable_i_size ) ) {
							if ( ! empty( $img_id['id'] ) ) {
											$att_img .= wp_get_attachment_image( $img_id['id'], $image_size, false, array( 'alt' => $alt_text ) );
							} elseif ( ! empty( $img_id['url'] ) ) {
									$img_url  = ( isset( $img_id['dynamic'] ) && class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_repeat_url( $img_id ) : ( ! empty( $img_id['url'] ) ? $img_id['url'] : '' );
									$att_img .= '<img src="' . esc_url( $img_url ) . '" alt="' . $alt_text . '"/>';
							} else {
								$att_img .= '<img src="' . esc_url( $default_img ) . '" alt="' . $alt_text . '"/>';
							}
										$team_image .= $att_img;
						} else {
							if ( ! empty( $img_id['id'] ) ) {

									$att_img .= wp_get_attachment_image( $img_id['id'], 'full', false, array( 'alt' => $alt_text ) );
							} elseif ( ! empty( $img_id['url'] ) ) {
								$img_url  = ( isset( $img_id['dynamic'] ) && class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_repeat_url( $img_id ) : ( ! empty( $img_id['url'] ) ? $img_id['url'] : '' );
								$att_img .= '<img src="' . esc_url( $img_url ) . '" alt="' . $alt_text . '"/>';
							} else {
								$att_img .= '<img src="' . esc_url( $default_img ) . '" alt="' . $alt_text . '"/>';
							}
							$team_image .= $att_img;
						}

									$link_image     .= '<div class="tpgb-team-profile">';
										$link_image .= '<span class="thumb-wrap">' . $team_image . '</span>';
									$link_image     .= '</div>';

						if ( ! empty( $disble_link ) ) {
							$image_html .= $link_image;
						} else {
							$link_attr   = ( isset( $team_item['CusUrl'] ) && class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) ? Tpgbp_Pro_Blocks_Helper::add_link_attributes( $team_item['CusUrl'] ) : '';
							$image_html .= '<a href="' . esc_url( $team_c_url ) . '" ' . $link_attr . ' aria-label="' . esc_attr( $team_name ) . '">' . $link_image . '</a>';
						}
					}
				}

							$icon_html = '';
				if ( ! empty( $disable_icon ) ) {
					$nofollow = '';
					$target   = '';

					$icon_html .= '<div class="tpgb-team-social-content">';
					$icon_html .= '<div class="tpgb-team-social-list">';
					if ( ! empty( $team_ws_url ) ) {
							$wb_attr        = ( isset( $team_item['WsUrl'] ) && class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) ? Tpgbp_Pro_Blocks_Helper::add_link_attributes( $team_item['WsUrl'] ) : '';
							$target         = ( ! empty( $team_item['WsUrl'] ) && ! empty( $team_item['WsUrl']['target'] ) ) ? 'target="_blank"' : '';
							$nofollow       = ( ! empty( $team_item['WsUrl'] ) && ! empty( $team_item['WsUrl']['nofollow'] ) ) ? 'rel="nofollow"' : '';
							$icon_html     .= '<div class="tpgb-team-profile-link">';
								$icon_html .= '<a href="' . esc_url( $team_ws_url ) . '" ' . $target . ' ' . $nofollow . ' ' . $wb_attr . '  aria-label="' . esc_attr__( 'Site URL', 'the-plus-addons-for-block-editor' ) . '"><i class="fas fa-globe" aria-hidden="true"></i></a>';
							$icon_html     .= '</div>';
					}
					if ( ! empty( $team_fb_url ) ) {
										$fb_attr        = ( isset( $team_item['FbUrl'] ) && class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) ? Tpgbp_Pro_Blocks_Helper::add_link_attributes( $team_item['FbUrl'] ) : '';
										$target         = ( ! empty( $team_item['FbUrl'] ) && ! empty( $team_item['FbUrl']['target'] ) ) ? 'target="_blank"' : '';
										$nofollow       = ( ! empty( $team_item['FbUrl'] ) && ! empty( $team_item['FbUrl']['nofollow'] ) ) ? 'rel="nofollow"' : '';
										$icon_html     .= '<div class="fb-link">';
											$icon_html .= '<a href="' . esc_url( $team_fb_url ) . '" ' . $target . ' ' . $nofollow . ' ' . $fb_attr . ' aria-label="' . esc_attr__( 'Facebook', 'the-plus-addons-for-block-editor' ) . '"><i class="fab fa-facebook-f" aria-hidden="true"></i></a>';
										$icon_html     .= '</div>';
					}
					if ( ! empty( $team_tw_url ) ) {
						$tw_attr        = ( isset( $team_item['TwUrl'] ) && class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) ? Tpgbp_Pro_Blocks_Helper::add_link_attributes( $team_item['TwUrl'] ) : '';
						$target         = ( ! empty( $team_item['TwUrl'] ) && ! empty( $team_item['TwUrl']['target'] ) ) ? 'target="_blank"' : '';
						$nofollow       = ( ! empty( $team_item['TwUrl'] ) && ! empty( $team_item['TwUrl']['nofollow'] ) ) ? 'rel="nofollow"' : '';
						$icon_html     .= '<div class="twitter-link">';
							$icon_html .= '<a href="' . esc_url( $team_tw_url ) . '" ' . $target . ' ' . $nofollow . ' ' . $tw_attr . ' aria-label="' . esc_attr__( 'Twitter', 'the-plus-addons-for-block-editor' ) . '"><i class="fab fa-twitter" aria-hidden="true"></i></a>';
						$icon_html     .= '</div>';
					}
					if ( ! empty( $team_ig_url ) ) {
									$ig_attr    = ( isset( $team_item['IGUrl'] ) && class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) ? Tpgbp_Pro_Blocks_Helper::add_link_attributes( $team_item['IGUrl'] ) : '';
									$target     = ( ! empty( $team_item['IGUrl'] ) && ! empty( $team_item['IGUrl']['target'] ) ) ? 'target="_blank"' : '';
									$nofollow   = ( ! empty( $team_item['IGUrl'] ) && ! empty( $team_item['IGUrl']['nofollow'] ) ) ? 'rel="nofollow"' : '';
									$icon_html .= '<div class="instagram-link">';
									$icon_html .= '<a href="' . esc_url( $team_ig_url ) . '" ' . $target . ' ' . $nofollow . ' ' . $ig_attr . ' aria-label="' . esc_attr__( 'Instagram', 'the-plus-addons-for-block-editor' ) . '"><i class="fab fa-instagram" aria-hidden="true"></i></a>';
									$icon_html .= '</div>';
					}
					if ( ! empty( $team_mail_url ) ) {
						$ml_attr    = ( isset( $team_item['MailUrl'] ) && class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) ? Tpgbp_Pro_Blocks_Helper::add_link_attributes( $team_item['MailUrl'] ) : '';
						$target     = ( ! empty( $team_item['MailUrl'] ) && ! empty( $team_item['MailUrl']['target'] ) ) ? 'target="_blank"' : '';
						$nofollow   = ( ! empty( $team_item['MailUrl'] ) && ! empty( $team_item['MailUrl']['nofollow'] ) ) ? 'rel="nofollow"' : '';
						$icon_html .= '<div class="mail-link">';
						$icon_html .= '<a href="' . esc_url( $team_mail_url ) . '" ' . $target . ' ' . $nofollow . ' ' . $ml_attr . ' aria-label="' . esc_attr__( 'Mail', 'the-plus-addons-for-block-editor' ) . '"><i class="fas fa-envelope-square"></i></a>';
						$icon_html .= '</div>';
					}
					if ( ! empty( $team_item['ldUrl']['url'] ) ) {
						$ld_attr    = ( isset( $team_item['ldUrl'] ) && class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) ? Tpgbp_Pro_Blocks_Helper::add_link_attributes( $team_item['ldUrl'] ) : '';
						$target     = ( ! empty( $team_item['ldUrl']['target'] ) ) ? 'target="_blank"' : '';
						$nofollow   = ( ! empty( $team_item['ldUrl']['nofollow'] ) ) ? 'rel="nofollow"' : '';
						$icon_html .= '<div class="linkedin-link">';
						$icon_html .= '<a href="' . esc_url( $team_item['ldUrl']['url'] ) . '" ' . $target . ' ' . $nofollow . ' ' . $ld_attr . ' aria-label="' . esc_attr__( 'LinkedIn', 'the-plus-addons-for-block-editor' ) . '"><i class="fab fa-linkedin-in" aria-hidden="true"></i></a>';
						$icon_html .= '</div>';
					}
					if ( ! empty( $telephone ) ) {
						$icon_html .= '<div class="Telephone-link">';
						$icon_html .= '<a href="' . esc_url( 'tel:' . $telephone ) . '" aria-label="' . esc_attr__( 'Phone No', 'the-plus-addons-for-block-editor' ) . '"><i class="fas fa-phone" aria-hidden="true"></i></a>';
						$icon_html .= '</div>';
					}
														$icon_html .= '</div>';
														$icon_html .= '</div>';
				}

						$title_html = '';
				if ( ! empty( $team_name ) ) {
					$title_html .= '<' . Tp_Blocks_Helper::validate_html_tag( $title_tag ) . ' class="tpgb-post-title">';
					if ( ! empty( $disble_link ) ) {
							$title_html .= wp_kses_post( $team_name );
					} else {
								$link_attr   = ( isset( $team_item['CusUrl'] ) && class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) ? Tpgbp_Pro_Blocks_Helper::add_link_attributes( $team_item['CusUrl'] ) : '';
								$title_html .= '<a href="' . esc_attr( $team_c_url ) . '" ' . $link_attr . '>' . wp_kses_post( $team_name ) . '</a>';
					}
									$title_html .= '</' . Tp_Blocks_Helper::validate_html_tag( $title_tag ) . '>';
				}

						$desig_html = '';
				if ( ! empty( $team_designation ) && ! empty( $designation ) ) {
					$desig_html .= '<div class="tpgb-member-designation">' . wp_kses_post( $team_designation ) . '</div>';
				}

						$final_html = '';
				if ( 'style-1' === $style ) {
					$final_html     .= '<div class="post-content-image">';
						$final_html .= $image_html;
						$final_html .= $icon_html;
					$final_html     .= '</div>';
					$final_html     .= '<div class="post-content-bottom">';
						$final_html .= $title_html;
						$final_html .= $desig_html;
					$final_html     .= '</div>';
				}

						$team_member .= $final_html;
						$team_member .= '</div>';
						$team_member .= '</div>';
			}
		}
		$team_member .= '</div>';
		$team_member .= '</div>';

		$team_member = Tpgb_Blocks_Global_Options::block_Wrap_Render( $attributes, $team_member );
		return $team_member;
	}

}
if ( ! function_exists( 'tpgb_tp_team_member_listing' ) ) {
	/**
	 * Tpgb tp team member listing.
	 */
	function tpgb_tp_team_member_listing() {
		if ( method_exists( 'Tpgb_Blocks_Global_Options', 'merge_options_json' ) ) {
			$block_data = Tpgb_Blocks_Global_Options::merge_options_json( __DIR__, 'tpgb_tp_team_member_listing_render_callback', true, true );
			register_block_type( $block_data['name'], $block_data );
		}
	}
}
add_action( 'init', 'tpgb_tp_team_member_listing' );
