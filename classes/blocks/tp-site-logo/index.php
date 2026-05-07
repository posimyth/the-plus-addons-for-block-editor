<?php
/**
 * Tp Block : Site Logo.
 *
 * @package ThePluginAddonsForBlockEditor
 */

defined( 'ABSPATH' ) || exit;

/**
 * Tpgb tp site logo render callback.
 *
 * @param mixed $attributes The attributes.
 * @param mixed $content The content.
 * @return mixed The result.
 */
function tpgb_tp_site_logo_render_callback( $attributes, $content ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
	$block_id     = ( ! empty( $attributes['block_id'] ) ) ? $attributes['block_id'] : uniqid( 'title' );
	$logo_nml_dbl = ( ! empty( $attributes['logoNmlDbl'] ) ) ? $attributes['logoNmlDbl'] : 'normal';
	$logo_type    = ( ! empty( $attributes['logoType'] ) ) ? $attributes['logoType'] : 'img';
	$image_store  = ( ! empty( $attributes['imageStore']['url'] ) ) ? $attributes['imageStore'] : array( 'url' => TPGB_ASSETS_URL . 'assets/images/tpgb-placeholder.jpg' );
	$image_size   = ( ! empty( $attributes['imageSize'] ) ) ? $attributes['imageSize'] : 'thumbnail';
	$svg_store    = ( ! empty( $attributes['svgStore']['url'] ) ) ? $attributes['svgStore'] : array( 'url' => '' );

	$hvr_image_store = ( ! empty( $attributes['hvrImageStore']['url'] ) ) ? $attributes['hvrImageStore'] : array( 'url' => TPGB_ASSETS_URL . 'assets/images/tpgb-placeholder.jpg' );
	$hvr_image_size  = ( ! empty( $attributes['hvrImageSize'] ) ) ? $attributes['hvrImageSize'] : 'thumbnail';
	$hvr_svg_store   = ( ! empty( $attributes['hvrSvgStore']['url'] ) ) ? $attributes['hvrSvgStore'] : array( 'url' => '' );

	$url_type = ( ! empty( $attributes['urlType'] ) ) ? $attributes['urlType'] : 'home';

	$sticky_logo = ( ! empty( $attributes['stickyLogo'] ) ) ? $attributes['stickyLogo'] : false;
	$sticky_img  = ( ! empty( $attributes['stickyImg']['url'] ) ) ? $attributes['stickyImg'] : array( 'url' => TPGB_ASSETS_URL . 'assets/images/tpgb-placeholder.jpg' );
	$s_img_size  = ( ! empty( $attributes['sImgSize'] ) ) ? $attributes['sImgSize'] : 'thumbnail';
	$sticky_svg  = ( ! empty( $attributes['stickySvg']['url'] ) ) ? $attributes['stickySvg'] : '';
	$markup_sch  = ( ! empty( $attributes['markupSch'] ) ) ? $attributes['markupSch'] : false;

	$block_class = Tp_Blocks_Helper::block_wrapper_classes( $attributes );

	$normal_hover = '';
	$sticky_class = '';
	if ( 'double' === $logo_nml_dbl && ! empty( $hvr_image_store ) ) {
		$normal_hover = 'logo-hover-normal';
	}
	if ( ! empty( $sticky_logo ) ) {
		$sticky_class = 'tp-sticky-logo-cls';
	}

	$default_img = TPGB_ASSETS_URL . 'assets/images/tpgb-placeholder.jpg';

	$img_src  = '';
	$alt_text = ( isset( $image_store['alt'] ) && ! empty( $image_store['alt'] ) ) ? esc_attr( $image_store['alt'] ) : ( ( ! empty( $image_store['title'] ) ) ? esc_attr( $image_store['title'] ) : esc_attr__( 'Site Logo', 'the-plus-addons-for-block-editor' ) );

	if ( ! empty( $image_store ) && ! empty( $image_store['id'] ) ) {
		$img_src = wp_get_attachment_image(
			$image_store['id'],
			$image_size,
			false,
			array(
				'class' => 'image-logo-wrap tpgb-trans-ease normal-image ' . esc_attr( $sticky_class ),
				'alt'   => $alt_text,
			)
		);
		$img_src = ( ! empty( $img_src ) ) ? $img_src : '<img src="' . esc_url( $default_img ) . '" class="image-logo-wrap tpgb-trans-ease normal-image ' . esc_attr( $sticky_class ) . '" alt="' . $alt_text . '"/>';
	} elseif ( ! empty( $image_store['url'] ) ) {
		$img_url = ( isset( $image_store['dynamic'] ) && class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_repeat_url( $image_store ) : $image_store['url'];
		$img_src = '<img src="' . esc_url( $img_url ) . '" class="image-logo-wrap normal-image tpgb-trans-ease ' . esc_attr( $sticky_class ) . '" alt="' . $alt_text . '"/>';
	}

	$h_img_src = '';
	$alt_text2 = ( isset( $hvr_image_store['alt'] ) && ! empty( $hvr_image_store['alt'] ) ) ? esc_attr( $hvr_image_store['alt'] ) : ( ( ! empty( $hvr_image_store['title'] ) ) ? esc_attr( $hvr_image_store['title'] ) : esc_attr__( 'Site Logo', 'the-plus-addons-for-block-editor' ) );

	if ( ! empty( $hvr_image_store ) && ! empty( $hvr_image_store['id'] ) ) {
		$h_img_src = wp_get_attachment_image(
			$hvr_image_store['id'],
			$hvr_image_size,
			false,
			array(
				'class' => 'image-logo-wrap tpgb-trans-ease',
				'alt'   => $alt_text2,
			)
		);
		$h_img_src = ( ! empty( $h_img_src ) ) ? $h_img_src : '<img src="' . esc_url( $default_img ) . '" class="image-logo-wrap tpgb-trans-ease"  alt="' . $alt_text2 . '"/>';
	} elseif ( ! empty( $hvr_image_store['url'] ) ) {
		$hvrimg_url = ( isset( $hvr_image_store['dynamic'] ) && class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_repeat_url( $hvr_image_store ) : $hvr_image_store['url'];
		$h_img_src  = '<img src="' . esc_url( $hvrimg_url ) . '" class="image-logo-wrap tpgb-trans-ease"  alt="' . $alt_text2 . '"/>';
	}

	$s_img_src = '';
	$alt_text3 = ( isset( $sticky_img['alt'] ) && ! empty( $sticky_img['alt'] ) ) ? esc_attr( $sticky_img['alt'] ) : ( ( ! empty( $sticky_img['title'] ) ) ? esc_attr( $sticky_img['title'] ) : esc_attr__( 'Site Logo', 'the-plus-addons-for-block-editor' ) );

	if ( ! empty( $sticky_img ) && ! empty( $sticky_img['id'] ) ) {
		$site_s_img = $sticky_img['id'];
		$s_img_src  = wp_get_attachment_image(
			$site_s_img,
			$s_img_size,
			false,
			array(
				'class' => 'image-logo-wrap tpgb-trans-ease sticky-image',
				'alt'   => $alt_text3,
			)
		);
		$s_img_src  = ( ! empty( $s_img_src ) ) ? $s_img_src : '<img src="' . esc_url( $default_img ) . '" class="image-logo-wrap tpgb-trans-ease sticky-image"  alt="' . $alt_text3 . '"/>';
	} elseif ( ! empty( $sticky_img['url'] ) ) {
		$st_img_src = ( isset( $sticky_img['dynamic'] ) && class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_repeat_url( $sticky_img ) : $sticky_img['url'];
		$s_img_src  = '<img src="' . esc_url( $st_img_src ) . '" class="image-logo-wrap tpgb-trans-ease sticky-image"  alt="' . $alt_text3 . '"/>';
	}

	$url_link  = '';
	$target    = '';
	$nofollow  = '';
	$link_attr = '';
	if ( 'home' === $url_type ) {
		$url_link = get_home_url();
	} elseif ( 'custom' === $url_type ) {

		// Set Dynamic URL For Custom Link.
		if ( class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) {
			$url_link = ( isset( $attributes['customURL']['dynamic'] ) ) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_repeat_url( $attributes['customURL'] ) : ( ! empty( $attributes['customURL']['url'] ) ? $attributes['customURL']['url'] : '' );
		} else {
			$url_link = ( ! empty( $attributes['customURL']['url'] ) ) ? $attributes['customURL']['url'] : '';
		}

		$target    = ( ! empty( $attributes['customURL']['target'] ) ) ? ' target="_blank"' : '';
		$nofollow  = ( ! empty( $attributes['customURL']['nofollow'] ) ) ? 'rel="nofollow"' : '';
		$link_attr = Tp_Blocks_Helper::add_link_attributes( $attributes['customURL'] );
	}
	$aria_label  = ( ! empty( $attributes['ariaLabel'] ) ) ? esc_attr( $attributes['ariaLabel'] ) : esc_attr__( 'Site Logo', 'the-plus-addons-for-block-editor' );
	$output      = '';
	$output     .= '<div class="tpgb-site-logo tpgb-relative-block tpgb-trans-linear tpgb-block-' . esc_attr( $block_id ) . ' ' . esc_attr( $block_class ) . '">';
		$output .= '<div class="site-logo-wrap tpgb-trans-ease ' . esc_attr( $normal_hover ) . '">';
	if ( 'img' === $logo_type ) {
		if ( ! empty( $image_store ) ) {

			$output     .= '<a href="' . esc_url( $url_link ) . '" ' . $target . ' ' . $nofollow . ' class="site-normal-logo image-logo" ' . $link_attr . ' aria-label="' . $aria_label . '">';
				$output .= $img_src;
			if ( ! empty( $sticky_logo ) ) {
				$output .= $s_img_src;
			}
				$output .= '</a>';
			if ( 'double' === $logo_nml_dbl && ! empty( $hvr_image_store ) ) {
				$output     .= '<a href="' . esc_url( $url_link ) . '" ' . $target . ' ' . $nofollow . ' class="site-normal-logo image-logo hover-logo" ' . $link_attr . ' aria-label="' . $aria_label . '">';
					$output .= $h_img_src;
				$output     .= '</a>';
			}
		}
	}
	if ( 'svg' === $logo_type ) {
		if ( ! empty( $svg_store ) ) {
			$output       .= '<a href="' . esc_url( $url_link ) . '" ' . $target . ' ' . $nofollow . ' class="site-normal-logo svg-logo" ' . $link_attr . ' aria-label="' . $aria_label . '">';
				$svg_url   = ( isset( $svg_store['dynamic'] ) && class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_repeat_url( $svg_store ) : $svg_store['url'];
				$alt_text4 = ( isset( $svg_store['alt'] ) && ! empty( $svg_store['alt'] ) ) ? esc_attr( $svg_store['alt'] ) : ( ( ! empty( $svg_store['title'] ) ) ? esc_attr( $svg_store['title'] ) : esc_attr__( 'Site Logo', 'the-plus-addons-for-block-editor' ) );

				$output .= '<img src="' . esc_url( $svg_url ) . '" class="image-logo-wrap normal-image ' . esc_attr( $sticky_class ) . '" alt="' . $alt_text4 . '"/>';
			if ( ! empty( $sticky_logo ) && ! empty( $sticky_svg['url'] ) ) {
				$stsvg_url = ( isset( $sticky_svg['dynamic'] ) && class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_repeat_url( $sticky_svg ) : $sticky_svg['url'];
				$alt_text5 = ( isset( $sticky_svg['alt'] ) && ! empty( $sticky_svg['alt'] ) ) ? esc_attr( $sticky_svg['alt'] ) : ( ( ! empty( $sticky_svg['title'] ) ) ? esc_attr( $sticky_svg['title'] ) : esc_attr__( 'Site Logo', 'the-plus-addons-for-block-editor' ) );
				$output   .= '<img src="' . esc_url( $stsvg_url ) . '" class="image-logo-wrap tpgb-trans-ease sticky-image" alt="' . $alt_text5 . '"/>';
			}
				$output .= '</a>';
			if ( 'double' === $logo_nml_dbl && ! empty( $hvr_svg_store ) ) {
				$hvrsvg_url  = ( isset( $hvr_svg_store['dynamic'] ) && class_exists( 'Tpgbp_Pro_Blocks_Helper' ) ) ? Tpgbp_Pro_Blocks_Helper::tpgb_dynamic_repeat_url( $hvr_svg_store ) : $hvr_svg_store['url'];
				$alt_text6   = ( isset( $hvr_svg_store['alt'] ) && ! empty( $hvr_svg_store['alt'] ) ) ? esc_attr( $hvr_svg_store['alt'] ) : ( ( ! empty( $hvr_svg_store['title'] ) ) ? esc_attr( $hvr_svg_store['title'] ) : esc_attr__( 'Site Logo', 'the-plus-addons-for-block-editor' ) );
				$output     .= '<a href="' . esc_url( $url_link ) . '"  ' . $target . ' ' . $nofollow . '  class="site-normal-logo svg-logo hover-logo" ' . $link_attr . ' aria-label="' . $aria_label . '">';
					$output .= '<img src="' . esc_url( $hvrsvg_url ) . '" class="image-logo-wrap tpgb-trans-ease" alt="' . $alt_text6 . '"/>';
				$output     .= '</a>';
			}
		}
	}
		$output .= '</div>';
	$output     .= '</div>';

	$output = Tpgb_Blocks_Global_Options::block_Wrap_Render( $attributes, $output );
	if ( ! empty( $markup_sch ) ) {
		$output .= '<script type="application/ld+json">';
		$output .= '{';
		$output .= '"@context": "https://schema.org",';
		$output .= '"@type": "Organization",';
		$output .= '"url": "' . esc_url( $url_link ) . '"';
		if ( isset( $img_url ) ) {
			$output .= ', "logo": "' . esc_url( $img_url ) . '"';
		}
		$output .= '}';
		$output .= '</script>';
	}
	return $output;
}

/**
 * Render for the server-side
 */
function tpgb_site_logo() {

	if ( method_exists( 'Tpgb_Blocks_Global_Options', 'merge_options_json' ) ) {
		$block_data = Tpgb_Blocks_Global_Options::merge_options_json( __DIR__, 'tpgb_tp_site_logo_render_callback' );
		register_block_type( $block_data['name'], $block_data );
	}
}
add_action( 'init', 'tpgb_site_logo' );
