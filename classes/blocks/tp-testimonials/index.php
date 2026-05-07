<?php
/**
 * Testimonials.
 *
 * @package ThePluginAddonsForBlockEditor
 */

defined( 'ABSPATH' ) || exit;

/**
 * Tpgb tp testimonials render callback.
 *
 * @param mixed $attributes The attributes.
 * @param mixed $content The content.
 */
function tpgb_tp_testimonials_render_callback( $attributes, $content ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
	$output          = '';
	$block_id        = ( ! empty( $attributes['block_id'] ) ) ? $attributes['block_id'] : uniqid( 'title' );
	$style           = ( ! empty( $attributes['style'] ) ) ? $attributes['style'] : 'style-1';
	$style_layout    = ( ! empty( $attributes['styleLayout'] ) ) ? $attributes['styleLayout'] : 'style-1';
	$block_class     = Tp_Blocks_Helper::block_wrapper_classes( $attributes );
	$show_dots       = ( ! empty( $attributes['showDots'] ) ) ? $attributes['showDots'] : array( 'md' => false );
	$dots_style      = ( ! empty( $attributes['dotsStyle'] ) ) ? $attributes['dotsStyle'] : false;
	$show_arrows     = ( ! empty( $attributes['showArrows'] ) ) ? $attributes['showArrows'] : array( 'md' => false );
	$arrows_style    = ( ! empty( $attributes['arrowsStyle'] ) ) ? $attributes['arrowsStyle'] : 'style-1';
	$arrows_position = ( ! empty( $attributes['arrowsPosition'] ) ) ? $attributes['arrowsPosition'] : 'top-right';
	$item_repeater   = ( ! empty( $attributes['ItemRepeater'] ) ) ? $attributes['ItemRepeater'] : array();

	$telayout = ( ! empty( $attributes['telayout'] ) ) ? $attributes['telayout'] : '';

	$desc_by_limit = isset( $attributes['descByLimit'] ) ? $attributes['descByLimit'] : 'default';
	$desc_limit    = isset( $attributes['descLimit'] ) ? $attributes['descLimit'] : 30;
	$cntscroll_on  = ( ! empty( $attributes['cntscrollOn'] ) ) ? $attributes['cntscrollOn'] : '';
	$caro_byheight = ( ! empty( $attributes['caroByheight'] ) ) ? $attributes['caroByheight'] : '';

	$redmor_txt = ( ! empty( $attributes['redmorTxt'] ) ) ? $attributes['redmorTxt'] : '';
	$redles_txt = ( ! empty( $attributes['redlesTxt'] ) ) ? $attributes['redlesTxt'] : '';

	$style3_layout = '';
	if ( 'style-3' === $style && ! empty( $style_layout ) ) {
		$style3_layout = 'layout-' . $style_layout;
	}

	// Equal Height.
	$equal_height_attr = Tp_Blocks_Helper::global_equal_height( $attributes );
	if ( ! empty( $equal_height_attr ) ) {
			$block_class .= ' tpgb-equal-height';
	}

	// Carousel Options.

	$data_attr   = '';
	$sliderclass = '';
	if ( 'carousel' === $telayout ) {
		if ( ( isset( $show_dots['md'] ) && ! empty( $show_dots['md'] ) ) || ( isset( $show_dots['sm'] ) && ! empty( $show_dots['sm'] ) ) || ( isset( $show_dots['xs'] ) && ! empty( $show_dots['xs'] ) ) ) {
			$sliderclass .= ' dots-' . esc_attr( $dots_style );
		}

		$carousel_settings = Tp_Blocks_Helper::carousel_settings( $attributes );

		if ( isset( $carousel_settings['pauseOnHover'] ) && true === $carousel_settings['pauseOnHover'] ) {
			$carousel_settings['pauseOnHover'] = false;
		}

		if ( isset( $carousel_settings['sliderMode'] ) && 'vertical' === $carousel_settings['sliderMode'] ) {
			$carousel_settings['sliderMode'] = 'horizontal';
		}

		$data_attr = 'data-splide=\'' . wp_json_encode( $carousel_settings ) . '\'';
	}

	$read_attr = array();
	$attr      = '';
	if ( 'masonry' === $telayout || ( 'carousel' === $telayout && 'text-limit' === $caro_byheight ) ) {

		$read_attr['readMore'] = $redmor_txt;
		$read_attr['readLess'] = $redles_txt;

		$read_attr = htmlspecialchars( wp_json_encode( $read_attr ), ENT_QUOTES, 'UTF-8' );

		$attr = 'data-readData = \'' . $read_attr . '\'';
	}

	$list_layout = '';
	if ( 'grid' === $telayout || 'masonry' === $telayout ) {
		$list_layout = 'tpgb-isotope';
	} elseif ( 'carousel' === $telayout ) {
		$list_layout = 'tpgb-carousel splide';
	}

	$column_class = ' tpgb-col';
	if ( 'carousel' !== $telayout && ! empty( $attributes['columns'] ) && is_array( $attributes['columns'] ) ) {
		$column_class .= isset( $attributes['columns']['md'] ) ? ' tpgb-col-lg-' . $attributes['columns']['md'] : ' tpgb-col-lg-3';
		$column_class .= isset( $attributes['columns']['sm'] ) ? ' tpgb-col-md-' . $attributes['columns']['sm'] : ' tpgb-col-md-4';
		$column_class .= isset( $attributes['columns']['xs'] ) ? ' tpgb-col-sm-' . $attributes['columns']['xs'] : ' tpgb-col-sm-6';
		$column_class .= isset( $attributes['columns']['xs'] ) ? ' tpgb-col-' . $attributes['columns']['xs'] : ' tpgb-col-6';
	}

	$output .= '<div class="tpgb-testimonials tpgb-relative-block testimonial-' . esc_attr( $style ) . ' ' . esc_attr( $style3_layout ) . ' tpgb-block-' . esc_attr( $block_id ) . ' ' . esc_attr( $block_class ) . ' ' . esc_attr( $sliderclass ) . ' ' . esc_attr( $list_layout ) . ' " ' . $data_attr . ' data-layout="' . esc_attr( $telayout ) . '" data-id="' . esc_attr( $block_id ) . '" ' . $equal_height_attr . ' >';

	if ( 'carousel' === $telayout && ( isset( $show_arrows['md'] ) && ! empty( $show_arrows['md'] ) ) || ( isset( $show_arrows['sm'] ) && ! empty( $show_arrows['sm'] ) ) || ( isset( $show_arrows['xs'] ) && ! empty( $show_arrows['xs'] ) ) ) { // phpcs:ignore Generic.CodeAnalysis.RequireExplicitBooleanOperatorPrecedence.MissingParentheses
		$output .= Tp_Blocks_Helper::tpgb_carousel_arrow( $arrows_style, $arrows_position );
	}
		$output .= '<div class="post-loop-inner ' . ( 'carousel' === $telayout ? 'splide__track' : 'tpgb-row' ) . '">';
	if ( 'carousel' === $telayout ) {
		$output .= '<div class="splide__list">';
	}
	if ( ! empty( $item_repeater ) ) {
		foreach ( $item_repeater as $index => $item ) :
			if ( is_array( $item ) ) {

				$item_content = '';
				if ( ! empty( $item['content'] ) ) {
					if ( 'default' === $desc_by_limit || ( 'carousel' === $telayout && ( '' === $caro_byheight || 'height' === $caro_byheight ) ) ) {
						$item_content     .= '<div class="entry-content scroll-' . esc_attr( $cntscroll_on ) . '">';
							$item_content .= wp_kses_post( $item['content'] );
						$item_content     .= '</div>';
					} else {
						if ( 'words' === $desc_by_limit ) {
							$total           = explode( ' ', $item['content'] );
							$limit_words     = explode( ' ', $item['content'], $desc_limit );
							$ltn             = count( $limit_words );
							$remaining_words = implode( ' ', array_slice( $total, $desc_limit - 1 ) );
							if ( count( $limit_words ) >= $desc_limit ) {
								array_pop( $limit_words );
								$excerpt = implode( ' ', $limit_words ) . ' <span class="testi-more-text" style = "display: none" >' . wp_kses_post( $remaining_words ) . '</span><a ' . $attr . ' class="testi-readbtn"> ' . esc_attr( $redmor_txt ) . ' </a>';
							} else {
								$excerpt = implode( ' ', $limit_words );
							}
						} elseif ( 'letters' === $desc_by_limit ) {
							$ltn             = strlen( $item['content'] );
							$limit_words     = substr( $item['content'], 0, $desc_limit );
							$remaining_words = substr( $item['content'], $desc_limit, $ltn );
							if ( strlen( $item['content'] ) > $desc_limit ) {
								$excerpt = $limit_words . '<span class="testi-more-text" style = "display:none" >' . wp_kses_post( $remaining_words ) . '</span><a ' . $attr . ' class="testi-readbtn"> ' . esc_attr( $redmor_txt ) . ' </a>';
							} else {
								$excerpt = $limit_words;
							}
						}

						$item_content     .= '<div class="entry-content">';
							$item_content .= $excerpt;
						$item_content     .= '</div>';
					}
				}

				$item_author_title = '';
				if ( ! empty( $item['authorTitle'] ) ) {
					$item_author_title .= '<h3 class="testi-author-title title-scroll-' . esc_attr( $cntscroll_on ) . '">' . esc_html( $item['authorTitle'] ) . '</h3>';
				}

				$item_title = '';
				if ( ! empty( $item['testiTitle'] ) ) {
					$item_title .= '<div class="testi-post-title">' . esc_html( $item['testiTitle'] ) . '</div>';
				}

				$item_designation = '';
				if ( ! empty( $item['designation'] ) ) {
					$item_designation .= '<div class="testi-post-designation">' . esc_html( $item['designation'] ) . '</div>';
				}

				$img_url  = '';
				$alt_text = ( isset( $item['avatar']['alt'] ) && ! empty( $item['avatar']['alt'] ) ) ? esc_attr( $item['avatar']['alt'] ) : ( ( ! empty( $item['avatar']['title'] ) ) ? esc_attr( $item['avatar']['title'] ) : esc_attr__( 'Author Avatar', 'the-plus-addons-for-block-editor' ) );

				if ( ! empty( $item['avatar'] ) && ! empty( $item['avatar']['id'] ) ) {
					$img_url = wp_get_attachment_image( $item['avatar']['id'], 'medium', false, array( 'alt' => $alt_text ) );
				} elseif ( ! empty( $item['avatar'] ) && ! empty( $item['avatar']['url'] ) ) {
					$img_url = '<img src="' . esc_url( $item['avatar']['url'] ) . '" alt="' . $alt_text . '"/>';
				} else {
					$img_url = '<img src="' . esc_url( TPGB_URL . 'assets/images/tpgb-placeholder-grid.jpg' ) . '" alt="' . esc_html__( 'Author Avatar', 'the-plus-addons-for-block-editor' ) . '"/>';
				}

				$output     .= '<div class="grid-item ' . ( 'carousel' === $telayout ? 'splide__slide' : $column_class ) . ' tp-repeater-item-' . ( isset( $item['_key'] ) ? esc_attr( $item['_key'] ) : '' ) . '" >';
					$output .= '<div class="testimonial-list-content" >';

				if ( 'style-4' !== $style ) {
						$output .= '<div class="testimonial-content-text">';
					if ( 'style-1' === $style || 'style-2' === $style ) {
						$output .= $item_content;
						$output .= $item_author_title;
					}
					if ( 'style-3' === $style ) {
						$output .= $item_author_title;
						$output .= $item_content;
					}
						$output .= '</div>';
				}

						$output         .= '<div class="post-content-image">';
							$output     .= '<div class="author-thumb">';
								$output .= $img_url;
							$output     .= '</div>';
				if ( 'style-1' === $style || 'style-2' === $style ) {
					$output .= $item_title;
					$output .= $item_designation;
				}
				if ( 'style-3' === $style ) {
					$output     .= '<div class="author-left-text">';
						$output .= $item_title;
						$output .= $item_designation;
					$output     .= '</div>';
				}
						$output .= '</div>';

					$output         .= '</div>';
							$output .= '</div>';
			}
		endforeach;
	}
	if ( 'carousel' === $telayout ) {
		$output .= '</div>';
	}
		$output .= '</div>';
	$output     .= '</div>';

	$output = Tpgb_Blocks_Global_Options::block_Wrap_Render( $attributes, $output );

	$arrow_css = Tp_Blocks_Helper::tpgb_carousel_arrow_css( $show_arrows, $block_id );
	if ( ! empty( $arrow_css ) ) {
		$output .= $arrow_css;
	}
	return $output;
}

/**
 * Render for the server-side
 */
function tpgb_tp_testimonials() {
	$block_data = Tpgb_Blocks_Global_Options::merge_options_json( __DIR__, 'tpgb_tp_testimonials_render_callback', true, true, false, true );
	register_block_type( $block_data['name'], $block_data );
}
add_action( 'init', 'tpgb_tp_testimonials' );
