<?php
/**
 * Heading Title.
 *
 * @package ThePluginAddonsForBlockEditor
 */

defined( 'ABSPATH' ) || exit;

/**
 * Tpgb limit words.
 *
 * @param mixed $string The string.
 * @param mixed $word_limit The word limit.
 * @return mixed The result.
 */
function tpgb_limit_words( $string, $word_limit ) { // phpcs:ignore Universal.NamingConventions.NoReservedKeywordParameterNames.stringFound,Universal.NamingConventions.NoReservedKeywordParameterNames.arrayFound
	$words = explode( ' ', $string );
	return implode( ' ', array_splice( $words, 0, $word_limit ) );
}
/**
 * Tpgb tp heading title render callback.
 *
 * @param mixed $attributes The attributes.
 * @param mixed $content The content.
 */
function tpgb_tp_heading_title_render_callback( $attributes, $content ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
	$output             = '';
	$block_id           = ( ! empty( $attributes['block_id'] ) ) ? $attributes['block_id'] : uniqid( 'title' );
	$style              = ( ! empty( $attributes['style'] ) ) ? $attributes['style'] : 'style-1';
	$heading_type       = ( ! empty( $attributes['headingType'] ) ) ? $attributes['headingType'] : 'default';
	$title              = ( ! empty( $attributes['Title'] ) ) ? $attributes['Title'] : '';
	$title_type         = ( ! empty( $attributes['titleType'] ) ) ? $attributes['titleType'] : 'h3';
	$sub_title          = ( ! empty( $attributes['subTitle'] ) ) ? $attributes['subTitle'] : '';
	$sub_title_type     = ( ! empty( $attributes['subTitleType'] ) ) ? $attributes['subTitleType'] : 'h3';
	$extra_title        = ( ! empty( $attributes['extraTitle'] ) ) ? $attributes['extraTitle'] : '';
	$et_position        = ( ! empty( $attributes['ETPosition'] ) ) ? $attributes['ETPosition'] : 'afterTitle';
	$sub_title_position = ( ! empty( $attributes['subTitlePosition'] ) ) ? $attributes['subTitlePosition'] : 'onBottonTitle';

	$limit_tgl      = ( ! empty( $attributes['limitTgl'] ) ) ? $attributes['limitTgl'] : false;
	$title_limit    = ( ! empty( $attributes['titleLimit'] ) ) ? $attributes['titleLimit'] : false;
	$title_limit_on = ( ! empty( $attributes['titleLimitOn'] ) ) ? $attributes['titleLimitOn'] : 'char';
	$title_count    = ( ! empty( $attributes['titleCount'] ) ) ? $attributes['titleCount'] : '3';
	$title_dots     = ( ! empty( $attributes['titleDots'] ) ) ? $attributes['titleDots'] : false;

	$sub_title_limit    = ( ! empty( $attributes['subTitleLimit'] ) ) ? $attributes['subTitleLimit'] : false;
	$sub_title_limit_on = ( ! empty( $attributes['subTitleLimitOn'] ) ) ? $attributes['subTitleLimitOn'] : 'char';
	$sub_title_count    = ( ! empty( $attributes['subTitleCount'] ) ) ? $attributes['subTitleCount'] : '3';
	$sub_title_dots     = ( ! empty( $attributes['subTitleDots'] ) ) ? $attributes['subTitleDots'] : false;

	$split_type       = ( ! empty( $attributes['splitType'] ) ) ? $attributes['splitType'] : 'words';
	$ani_effect       = ( ! empty( $attributes['aniEffect'] ) ) ? $attributes['aniEffect'] : 'default';
	$ani_position     = ( ! empty( $attributes['aniPosition'] ) ) ? $attributes['aniPosition'] : array();
	$animation_scale  = ( ! empty( $attributes['animationScale'] ) ) ? $attributes['animationScale'] : array();
	$animation_rotate = ( ! empty( $attributes['animationRotate'] ) ) ? $attributes['animationRotate'] : array();
	$extr_opt         = ( ! empty( $attributes['extrOpt'] ) ) ? $attributes['extrOpt'] : array();

	$adv_heading_link = ( ! empty( $attributes['advHeadingLink']['url'] ) ) ? $attributes['advHeadingLink']['url'] : '';
	$target           = ( ! empty( $attributes['advHeadingLink']['target'] ) ) ? ' target="_blank" ' : '';
	$nofollow         = ( ! empty( $attributes['advHeadingLink']['nofollow'] ) ) ? ' rel="nofollow" ' : '';
	$link_attr        = Tp_Blocks_Helper::add_link_attributes( $attributes['advHeadingLink'] );

	$anchor = ( isset( $attributes['anchor'] ) && ! empty( $attributes['anchor'] ) ) ? 'id="' . esc_attr( $attributes['anchor'] ) . '"' : '';

	$block_class = Tp_Blocks_Helper::block_wrapper_classes( $attributes );

	if ( class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) {
		$adv_heading_link = ( isset( $attributes['advHeadingLink']['dynamic'] ) ) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_repeat_url( $attributes['advHeadingLink'] ) : ( ! empty( $attributes['advHeadingLink']['url'] ) ? $attributes['advHeadingLink']['url'] : '' );
	}

	$get_extra_title = '';
	if ( ! empty( $extra_title ) ) {
		$get_extra_title .= '<span class="title-s ">' . wp_kses_post( $extra_title ) . '</span>';
	}

	$get_title = '';
	if ( 'page' === $heading_type ) {
		$title = get_the_title();
	}
	$get_title     .= '<div class="head-title ">';
		$get_title .= '<' . Tp_Blocks_Helper::validate_html_tag( $title_type ) . ' class="heading-title">';
	if ( 'style-1' === $style && 'beforeTitle' === $et_position ) {
		$get_title .= $get_extra_title;
	}
	if ( ! empty( $limit_tgl ) && ! empty( $title_limit ) ) {

		if ( class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) {
			global $repeater_index;
			$rep_index = $repeater_index ?? 0;

			if ( strpos( $title, 'acf|' ) !== false || strpos( $title, 'jetengine|' ) !== false ) {
				if ( preg_match( '/<span[^>]*data-tpgb-dynamic=(["\'])([^"\']+)\1[^>]*><\/span>/', $title, $matches ) && ! empty( $matches[2] ) ) {
					$data_array = json_decode( html_entity_decode( $matches[2], ENT_QUOTES | ENT_HTML5 ), true );

					if ( json_last_error() === JSON_ERROR_NONE && ! empty( $data_array['dynamicField'] ) ) {
						$parts = explode( '|', $data_array['dynamicField'] );

						if ( count( $parts ) === 5 || count( $parts ) === 7 ) {
							$field_name = $parts[1] ?? 'Unknown Field';
							$rep_data   = apply_filters( 'tp_get_repeater_data', $parts );
							if ( is_wp_error( $rep_data ) ) {
								$replacement = '';
							} else {
								$replacement = $rep_data['repeater_data'][ $rep_index ][ $field_name ] ?? '';
							}

							$title = preg_replace(
								'/<span[^>]+data-tpgb-dynamic=(["\'])(.*?)\1[^>]*><\/span>/',
								esc_html( $replacement ),
								$title
							);
						}
					}
				}
			} else {
				$title = Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_val( $title );
			}
		} else {
			$title = $title;
		}

		if ( 'char' === $title_limit_on ) {
			$get_title .= substr( $title, 0, $title_count );
			if ( ! empty( $title_dots ) && strlen( $title ) > $title_count ) {
				$get_title .= '...';
			}
		} elseif ( 'word' === $title_limit_on ) {
			$get_title .= tpgb_limit_words( $title, $title_count );
			if ( ! empty( $title_dots ) && str_word_count( $title ) > $title_count ) {
				$get_title .= '...';
			}
		}
	} else {
		$get_title .= wp_kses_post( $title );
	}
	if ( 'style-1' === $style && 'afterTitle' === $et_position ) {
		$get_title .= $get_extra_title;
	}
		$get_title .= '</' . Tp_Blocks_Helper::validate_html_tag( $title_type ) . '>';
	$get_title     .= '</div>';

	$style_8_sep      = '';
	$style_8_sep     .= '<div class="seprator sep-l">';
		$style_8_sep .= '<span class="title-sep sep-l"></span>';
		$style_8_sep .= '<div class="sep-dot">.</div>';
		$style_8_sep .= '<span class="title-sep sep-r"></span>';
	$style_8_sep     .= '</div>';

	$style_3_sep      = '';
	$style_3_sep     .= '<div class="seprator sep-l">';
		$style_3_sep .= '<span class="title-sep sep-l"></span>';
	if ( isset( $attributes['imgName'] ) && isset( $attributes['imgName']['url'] ) && '' !== $attributes['imgName']['url'] ) {
		$img_src  = '';
		$alt_text = ( isset( $attributes['imgName']['alt'] ) && ! empty( $attributes['imgName']['alt'] ) ) ? esc_attr( $attributes['imgName']['alt'] ) : ( ( ! empty( $attributes['imgName']['title'] ) ) ? esc_attr( $attributes['imgName']['title'] ) : esc_attr__( 'Image Separator', 'the-plus-addons-for-block-editor' ) );
		if ( ! empty( $attributes['imgName']['id'] ) ) {
			$img_src = wp_get_attachment_image( $attributes['imgName']['id'], 'full', false, array( 'alt' => $alt_text ) );
		} elseif ( ! empty( $attributes['imgName']['url'] ) ) {
			$img_src = '<img src="' . esc_url( $attributes['imgName']['url'] ) . '"  alt="' . $alt_text . '" />';
		}
		$style_3_sep     .= '<div class="sep-mg">';
			$style_3_sep .= $img_src;
		$style_3_sep     .= '</div>';
	}
		$style_3_sep .= '<span class="title-sep sep-r"></span>';
	$style_3_sep     .= '</div>';

	$get_sub_title = '';
	if ( ! empty( $sub_title ) ) {
		$get_sub_title     .= '<div class="sub-heading ">';
			$get_sub_title .= '<' . Tp_Blocks_Helper::validate_html_tag( $sub_title_type ) . ' class="heading-sub-title">';
		if ( ! empty( $limit_tgl ) && ! empty( $sub_title_limit ) ) {
			$sub_title = ( class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_val( $sub_title ) : $sub_title;
			if ( 'char' === $sub_title_limit_on ) {
				$get_sub_title .= substr( $sub_title, 0, $sub_title_count );
				if ( ! empty( $sub_title_dots ) && strlen( $sub_title ) > $sub_title_count ) {
					$get_sub_title .= '...';
				}
			} elseif ( 'word' === $sub_title_limit_on ) {
				$get_sub_title .= tpgb_limit_words( $sub_title, $sub_title_count );
				if ( ! empty( $sub_title_dots ) && str_word_count( $sub_title ) > $sub_title_count ) {
					$get_sub_title .= '...';
				}
			}
		} else {
			$get_sub_title .= wp_kses_post( $sub_title );
		}
			$get_sub_title .= '</' . Tp_Blocks_Helper::validate_html_tag( $sub_title_type ) . '>';
			$get_sub_title .= '</div>';
	}

	$output .= '<div ' . $anchor . ' class="tpgb-heading-title tpgb-relative-block heading_style tpgb-block-' . esc_attr( $block_id ) . ' ' . esc_attr( $block_class ) . ' heading-' . esc_attr( $style ) . '">';
	if ( $adv_heading_link && ( '' !== $adv_heading_link ) ) {
		$output .= '<a ' . $link_attr . ' href="' . esc_url( $adv_heading_link ) . '" ' . $target . ' ' . $nofollow . '>';
		if ( 'style-9' !== $style ) {
			$output .= '<div class="sub-style">';
			if ( 'style-5' === $style ) {
				$output .= '<div class="vertical-divider top"></div>';
			}
			if ( 'onBottonTitle' === $sub_title_position ) {
				if ( ! empty( $title ) ) {
					$output .= $get_title;
				}
				if ( 'style-3' === $style && ! empty( $title ) ) {
					$output .= $style_3_sep;
				}
				if ( 'style-8' === $style && ! empty( $title ) ) {
					$output .= $style_8_sep;
				}
			}
			if ( 'onTopTitle' === $sub_title_position ) {
				$output .= $get_sub_title;
			}

			if ( 'onBottonTitle' === $sub_title_position ) {
				$output .= $get_sub_title;
			}
			if ( 'onTopTitle' === $sub_title_position ) {
				if ( ! empty( $title ) ) {
					$output .= $get_title;
				}
				if ( 'style-3' === $style && ! empty( $title ) ) {
					$output .= $style_3_sep;
				}
				if ( 'style-8' === $style && ! empty( $title ) ) {
					$output .= $style_8_sep;
				}
			}
			if ( 'style-5' === $style ) {
				$output .= '<div class="vertical-divider bottom"></div>';
			}
			$output .= '</div>';
		} else {
			$split_class      = 'tpgb-split-' . esc_attr( $split_type );
			$n_split_type     = ( 'lines' === $split_type ) ? 'lines,chars' : esc_attr( $split_type );
			$annimtypedtaattr = ' data-animsplit-type="' . $n_split_type . '"';
			$htaattr          = array(
				'effect'    => $ani_effect,
				'x'         => ( ! empty( $ani_position ) && ! empty( $ani_position['tpgbReset'] ) && ! empty( $ani_position['aniPositionX'] ) ) ? (int) $ani_position['aniPositionX'] : 0,
				'y'         => ( ! empty( $ani_position ) && ! empty( $ani_position['tpgbReset'] ) && ! empty( $ani_position['aniPositionY'] ) ) ? (int) $ani_position['aniPositionY'] : 0,

				'scaleX'    => ( ! empty( $animation_scale ) && ! empty( $animation_scale['tpgbReset'] ) && ! empty( $animation_scale['animationScaleX'] ) ) ? (int) $animation_scale['animationScaleX'] : 0,
				'scaleY'    => ( ! empty( $animation_scale ) && ! empty( $animation_scale['tpgbReset'] ) && ! empty( $animation_scale['animationScaleY'] ) ) ? (int) $animation_scale['animationScaleY'] : 0,
				'scaleZ'    => ( ! empty( $animation_scale ) && ! empty( $animation_scale['tpgbReset'] ) && ! empty( $animation_scale['animationScaleZ'] ) ) ? (int) $animation_scale['animationScaleZ'] : 0,
				'rotationX' => ( ! empty( $animation_rotate ) && ! empty( $animation_rotate['tpgbReset'] ) && ! empty( $animation_rotate['animationRotateX'] ) ) ? (int) $animation_rotate['animationRotateX'] : 0,
				'rotationY' => ( ! empty( $animation_rotate ) && ! empty( $animation_rotate['tpgbReset'] ) && ! empty( $animation_rotate['animationRotateY'] ) ) ? (int) $animation_rotate['animationRotateY'] : 0,
				'rotationZ' => ( ! empty( $animation_rotate ) && ! empty( $animation_rotate['tpgbReset'] ) && ! empty( $animation_rotate['animationRotateZ'] ) ) ? (int) $animation_rotate['animationRotateZ'] : 0,

				'opacity'   => ( ! empty( $extr_opt ) && ! empty( $extr_opt['tpgbReset'] ) && ! empty( $extr_opt['animationOpacity'] ) ) ? (float) $extr_opt['animationOpacity'] : 0,
				'speed'     => ( ! empty( $extr_opt ) && ! empty( $extr_opt['tpgbReset'] ) && ! empty( $extr_opt['animationSpeed'] ) ) ? (float) $extr_opt['animationSpeed'] : 1,
				'delay'     => ( ! empty( $extr_opt ) && ! empty( $extr_opt['tpgbReset'] ) && ! empty( $extr_opt['animationDelay'] ) ) ? (float) $extr_opt['animationDelay'] : 0.02,
			);
			$htaattrbunch     = 'data-aniattrht = ' . wp_json_encode( $htaattr );
			$output          .= '<' . Tp_Blocks_Helper::validate_html_tag( $title_type ) . ' class="sub-style ' . esc_attr( $split_class ) . '" ' . $annimtypedtaattr . ' ' . $htaattrbunch . '>';
				$title        = ( class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_val( $title ) : $title;
				$output      .= wp_kses_post( $title );
			$output          .= '</' . Tp_Blocks_Helper::validate_html_tag( $title_type ) . '>';
		}
		$output .= '</a>';
	} elseif ( 'style-9' !== $style ) {
			$output .= '<div class="sub-style">';
		if ( 'style-5' === $style ) {
			$output .= '<div class="vertical-divider top"></div>';
		}
		if ( 'onBottonTitle' === $sub_title_position ) {
			if ( ! empty( $title ) ) {
				$output .= $get_title;
			}
			if ( 'style-3' === $style && ! empty( $title ) ) {
				$output .= $style_3_sep;
			}
			if ( 'style-8' === $style && ! empty( $title ) ) {
				$output .= $style_8_sep;
			}
		}
		if ( 'onTopTitle' === $sub_title_position ) {
			$output .= $get_sub_title;
		}

		if ( 'onBottonTitle' === $sub_title_position ) {
			$output .= $get_sub_title;
		}
		if ( 'onTopTitle' === $sub_title_position ) {
			if ( ! empty( $title ) ) {
				$output .= $get_title;
			}
			if ( 'style-3' === $style && ! empty( $title ) ) {
				$output .= $style_3_sep;
			}
			if ( 'style-8' === $style && ! empty( $title ) ) {
				$output .= $style_8_sep;
			}
		}
		if ( 'style-5' === $style ) {
			$output .= '<div class="vertical-divider bottom"></div>';
		}
			$output .= '</div>';
	} else {
		$split_class      = 'tpgb-split-' . esc_attr( $split_type );
		$n_split_type     = ( 'lines' === $split_type ) ? 'lines,chars' : esc_attr( $split_type );
		$annimtypedtaattr = ' data-animsplit-type="' . $n_split_type . '"';
		$htaattr          = array(
			'effect'    => $ani_effect,
			'x'         => ( ! empty( $ani_position ) && ! empty( $ani_position['tpgbReset'] ) && ! empty( $ani_position['aniPositionX'] ) ) ? (int) $ani_position['aniPositionX'] : 0,
			'y'         => ( ! empty( $ani_position ) && ! empty( $ani_position['tpgbReset'] ) && ! empty( $ani_position['aniPositionY'] ) ) ? (int) $ani_position['aniPositionY'] : 0,

			'scaleX'    => ( ! empty( $animation_scale ) && ! empty( $animation_scale['tpgbReset'] ) && ! empty( $animation_scale['animationScaleX'] ) ) ? (int) $animation_scale['animationScaleX'] : 0,
			'scaleY'    => ( ! empty( $animation_scale ) && ! empty( $animation_scale['tpgbReset'] ) && ! empty( $animation_scale['animationScaleY'] ) ) ? (int) $animation_scale['animationScaleY'] : 0,
			'scaleZ'    => ( ! empty( $animation_scale ) && ! empty( $animation_scale['tpgbReset'] ) && ! empty( $animation_scale['animationScaleZ'] ) ) ? (int) $animation_scale['animationScaleZ'] : 0,
			'rotationX' => ( ! empty( $animation_rotate ) && ! empty( $animation_rotate['tpgbReset'] ) && ! empty( $animation_rotate['animationRotateX'] ) ) ? (int) $animation_rotate['animationRotateX'] : 0,
			'rotationY' => ( ! empty( $animation_rotate ) && ! empty( $animation_rotate['tpgbReset'] ) && ! empty( $animation_rotate['animationRotateY'] ) ) ? (int) $animation_rotate['animationRotateY'] : 0,
			'rotationZ' => ( ! empty( $animation_rotate ) && ! empty( $animation_rotate['tpgbReset'] ) && ! empty( $animation_rotate['animationRotateZ'] ) ) ? (int) $animation_rotate['animationRotateZ'] : 0,

			'opacity'   => ( ! empty( $extr_opt ) && ! empty( $extr_opt['tpgbReset'] ) && ! empty( $extr_opt['animationOpacity'] ) ) ? (float) $extr_opt['animationOpacity'] : 0,
			'speed'     => ( ! empty( $extr_opt ) && ! empty( $extr_opt['tpgbReset'] ) && ! empty( $extr_opt['animationSpeed'] ) ) ? (float) $extr_opt['animationSpeed'] : 1,
			'delay'     => ( ! empty( $extr_opt ) && ! empty( $extr_opt['tpgbReset'] ) && ! empty( $extr_opt['animationDelay'] ) ) ? (float) $extr_opt['animationDelay'] : 0.02,
		);
		$htaattrbunch     = 'data-aniattrht = ' . wp_json_encode( $htaattr );
		$output          .= '<' . Tp_Blocks_Helper::validate_html_tag( $title_type ) . ' class="sub-style ' . esc_attr( $split_class ) . '" ' . $annimtypedtaattr . ' ' . $htaattrbunch . '>';
			$title        = ( class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_val( $title ) : $title;
			$output      .= wp_kses_post( $title );
		$output          .= '</' . Tp_Blocks_Helper::validate_html_tag( $title_type ) . '>';
	}
	$output .= '</div>';

	$output = Tpgb_Blocks_Global_Options::block_Wrap_Render( $attributes, $output );

	return $output;
}

/**
 * Render for the server-side
 */
function tpgb_tp_heading_title() {
	$block_data = Tpgb_Blocks_Global_Options::merge_options_json( __DIR__, 'tpgb_tp_heading_title_render_callback' );
	register_block_type( $block_data['name'], $block_data );
}
add_action( 'init', 'tpgb_tp_heading_title' );
