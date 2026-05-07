<?php
/**
 * Creative Image.
 *
 * @package ThePluginAddonsForBlockEditor
 */

// phpcs:disable Squiz.PHP.CommentedOutCode.Found
defined( 'ABSPATH' ) || exit;

/**
 * Tpgb tp creative image callback.
 *
 * @param mixed $settings The settings.
 * @param mixed $content The content.
 * @return mixed The result.
 */
function tpgb_tp_creative_image_callback( $settings, $content ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter

	$block_id    = ! empty( $settings['block_id'] ) ? $settings['block_id'] : '';
	$fancy_box   = ( ! empty( $settings['fancyBox'] ) ) ? $settings['fancyBox'] : false;
	$float_align = ! empty( $settings['floatAlign'] ) ? $settings['floatAlign'] : '';
	$block_class = Tp_Blocks_Helper::block_wrapper_classes( $settings );

	// Float Align Class.
	if ( ! empty( $float_align ) && 'none' !== $float_align ) {
		$block_class .= 'tpgb-image-' . esc_attr( $float_align );
	}

	$content_image = '';
	$img_id        = '';
	$alt_text      = ( isset( $settings['SelectImg']['alt'] ) && ! empty( $settings['SelectImg']['alt'] ) ) ? esc_attr( $settings['SelectImg']['alt'] ) : ( ( ! empty( $settings['SelectImg']['title'] ) ) ? esc_attr( $settings['SelectImg']['title'] ) : esc_attr__( 'Creative Image', 'the-plus-addons-for-block-editor' ) );
	if ( isset( $settings['SelectImg']['id'] ) && ! empty( $settings['SelectImg']['id'] ) ) {
		$img_id = $settings['SelectImg']['id'];
	}
	if ( ! empty( $settings['SelectImg']['url'] ) && isset( $settings['SelectImg']['id'] ) ) {
		$attr          = array(
			'class' => 'hover__img info_img',
			'alt'   => $alt_text,
		);
		$content_image = wp_get_attachment_image( $img_id, $settings['ImgSize'], '', $attr );
	} else {
		$content_image .= tpgb_loading_image_grid( get_the_ID() );
	}

	$href   = '';
	$target = '';
	$rel    = '';
	if ( ! empty( $settings['link']['url'] ) ) {
		$href   = ( '' !== $settings['link']['url'] ) ? $settings['link']['url'] : '';
		$target = ( ! empty( $settings['link']['target'] ) ) ? 'target="_blank"' : '';
		$rel    = ( ! empty( $settings['link']['nofollow'] ) ) ? 'rel="nofollow"' : '';
	}

	$mask_image = '';
	if ( ! empty( $settings['showMaskImg'] ) ) {
		$mask_image = ' tpgb-creative-mask-media';
	}
	$wrapper_class = 'tpgb-creative-img-wrap ' . esc_attr( $mask_image );

	$data_image = '';
	$fancy_img  = TPGB_ASSETS_URL . 'assets/images/tpgb-placeholder.jpg';
	if ( isset( $settings['SelectImg']['id'] ) ) {
		$full_image = wp_get_attachment_image_src( $img_id, 'full' );
		$fancy_img  = isset( $full_image[0] ) ? $full_image[0] : '';
		$data_image = ( ! empty( $full_image ) && ! empty( $full_image[0] ) ) ? 'background: url(' . esc_url( $full_image[0] ) . ');' : '';
	} else {
		$data_image = tpgb_loading_image_grid( '', 'background' );
	}

	$data_settings = '';
	if ( ! empty( $fancy_box ) ) {
		$fancy_data = ( ! empty( $settings['FancyOption'] ) ) ? json_decode( $settings['FancyOption'] ) : array();
		$button     = array();
		if ( is_array( $fancy_data ) || is_object( $fancy_data ) ) {
			foreach ( $fancy_data as $value ) {
				$button_opt = ( ( 'zoom' === $value->value ) ? 'iterateZoom' : ( ( 'fullScreen' === $value->value ) ? 'fullscreen' : $value->value ) );
				if ( 'share' !== $value->value ) {
					$button[] = $button_opt;
				}
			}
		}
		$fancybox           = array();
		$fancybox['button'] = $button;
		// $fancybox['animationEffect'] = $settings['AnimationFancy']; // phpcs:ignore Squiz.PHP.CommentedOutCode.Found
		// $fancybox['animationDuration'] = $settings['DurationFancy'];
		$data_settings .= ' data-fancy-option=\'' . wp_json_encode( $fancybox ) . '\'';
		$data_settings .= ' data-id="' . esc_attr( $block_id ) . '"';
	}

	if ( ! empty( $settings['link']['url'] ) ) {
		$link_attr    = Tp_Blocks_Helper::add_link_attributes( $settings['link'] );
		$aria_label_t = ( ! empty( $settings['ariaLabel'] ) ) ? esc_attr( $settings['ariaLabel'] ) : esc_attr__( 'Creative Image', 'the-plus-addons-for-block-editor' );
		$html         = '<a href="' . esc_url( $href ) . '" ' . $target . ' ' . $rel . ' class="' . esc_attr( $wrapper_class ) . '" ' . $link_attr . ' aria-label="' . $aria_label_t . '">' . $content_image . '</a>';
	} else {
		$tag        = ! empty( $fancy_box ) && empty( $settings['ScrollParallax'] ) ? 'a' : 'div';
		$fancy_attr = ! empty( $fancy_box ) ? 'href= "' . esc_url( $fancy_img ) . '" data-fancybox="fancyImg-' . esc_attr( $block_id ) . '"' : '';

		$html = '<' . Tp_Blocks_Helper::validate_html_tag( $tag ) . ' class="' . esc_attr( $wrapper_class ) . '" ' . $fancy_attr . '>' . $content_image . '</' . Tp_Blocks_Helper::validate_html_tag( $tag ) . '>';
	}

	$uid            = 'bg-image' . esc_attr( $block_id );
	$animated_class = '';
	$css_class      = '';
	$css_class      = ' text-' . esc_attr( $settings['Alignment']['md'] ) . ' ' . esc_attr( $animated_class );
	$css_class     .= ( ! empty( $settings['Alignment']['sm'] ) ) ? ' text-tablet-' . esc_attr( $settings['Alignment']['sm'] ) : '';
	$css_class     .= ( ! empty( $settings['Alignment']['xs'] ) ) ? ' text-mobile-' . esc_attr( $settings['Alignment']['xs'] ) : '';

	$uid_widget              = 'plus' . esc_attr( $block_id );
	$output                  = '<div id="' . esc_attr( $uid_widget ) . '" class="tpgb-creative-image tpgb-relative-block tpgb-block-' . esc_attr( $block_id ) . ' ' . esc_attr( $block_class ) . '">';
		$output             .= '<div class="tpgb-anim-img-parallax tpgb-relative-block" >';
			$output         .= '<div class="tpgb-animate-image ' . esc_attr( $uid ) . ' ' . trim( $css_class ) . ' ' . ( ! empty( $fancy_box ) ? 'tpgb-fancy-add' : '' ) . '" ' . $data_settings . '>';
				$output     .= '<figure>';
					$output .= $html;
				$output     .= '</figure>';
			$output         .= '</div>';
		$output             .= '</div>';
	$output                 .= '</div>';

	$output = Tpgb_Blocks_Global_Options::block_Wrap_Render( $settings, $output );

	return $output;
}

/**
 * Tpgb tp creative image render.
 */
function tpgb_tp_creative_image_render() {
	$block_data = Tpgb_Blocks_Global_Options::merge_options_json( __DIR__, 'tpgb_tp_creative_image_callback' );
	register_block_type( $block_data['name'], $block_data );
}
add_action( 'init', 'tpgb_tp_creative_image_render' );

/**
 * Tpgb loading image grid.
 *
 * @param string $postid The postid.
 * @param string $type The type.
 * @return mixed The result.
 */
function tpgb_loading_image_grid( $postid = '', $type = '' ) {
	global $post;
	$content_image = '';
	if ( 'background' !== $type ) {
		$image_url     = TPGB_ASSETS_URL . 'assets/images/tpgb-placeholder.jpg';
		$content_image = '<img src="' . esc_url( $image_url ) . '" alt="' . esc_attr( get_the_title() ) . '"/>';
		return $content_image;
	} elseif ( 'background' === $type ) {
		$image_url = TPGB_ASSETS_URL . 'assets/images/tpgb-placeholder.jpg';
		$data_src  = 'background:url(' . esc_url( $image_url ) . ') #f7f7f7;';
		return $data_src;
	}
}
