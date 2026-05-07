<?php
/**
 * Breadcrumbs.
 *
 * @package ThePluginAddonsForBlockEditor
 */

defined( 'ABSPATH' ) || exit;

/**
 * Tpgb breadcrumbs callback.
 *
 * @param mixed $attributes The attributes.
 * @param mixed $content The content.
 * @return mixed The result.
 */
function tpgb_breadcrumbs_callback( $attributes, $content ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
	$uid         = ( ! empty( $attributes['block_id'] ) ) ? $attributes['block_id'] : uniqid( 'title' );
	$style       = ( ! empty( $attributes['style'] ) ) ? $attributes['style'] : '';
	$markup_sch  = ( ! empty( $attributes['markupSch'] ) ) ? $attributes['markupSch'] : '';
	$ctm_homeurl = ( ! empty( $attributes['ctmHomeurl'] ) ) ? $attributes['ctmHomeurl'] : '';

	$show_terms      = ( ! empty( $attributes['showTerms'] ) ) ? $attributes['showTerms'] : '';
	$taxonomy_slug   = ( ! empty( $attributes['taxonomySlug'] ) ) ? $attributes['taxonomySlug'] : '';
	$showpart_terms  = ( ! empty( $attributes['showpartTerms'] ) ) ? $attributes['showpartTerms'] : false;
	$showchild_terms = ( ! empty( $attributes['showchildTerms'] ) ) ? $attributes['showchildTerms'] : false;

	$block_class = Tp_Blocks_Helper::block_wrapper_classes( $attributes );

	$icons    = '';
	$icontype = '';
	if ( 'icon' === $attributes['homeIcon'] ) {
		if ( ! empty( $attributes['iconFontStyle'] ) && 'font_awesome' === $attributes['iconFontStyle'] ) {
			$icons    = ( ! empty( $attributes['iconFawesome'] ) ) ? $attributes['iconFawesome'] : '';
			$icontype = 'icon';
		} elseif ( ! empty( $attributes['iconFontStyle'] ) && 'icon_image' === $attributes['iconFontStyle'] ) {
			$icons_img = ( ! empty( $attributes['iconsImg']['id'] ) ) ? $attributes['iconsImg']['id'] : '';
			if ( ! empty( $icons_img ) ) {
				$img      = wp_get_attachment_image_src( $icons_img );
				$icons    = $img[0];
				$icontype = 'image';
			} elseif ( ! empty( $attributes['iconsImg']['url'] ) ) {
				$icons    = $attributes['iconsImg']['url'];
				$icontype = 'image';
			}
		}
	}

	$sep_icons     = '';
	$sep_icon_type = '';
	if ( 'sep_icon' === $attributes['sepIcon'] ) {
		if ( ! empty( $attributes['sepIconFontStyle'] ) && 'sep_font_awesome' === $attributes['sepIconFontStyle'] ) {
			$sep_icons     = ( ! empty( $attributes['sepIconFawesome'] ) ) ? $attributes['sepIconFawesome'] : '';
			$sep_icon_type = 'sep_icon';
		} elseif ( ! empty( $attributes['sepIconFontStyle'] ) && 'sep_icon_image' === $attributes['sepIconFontStyle'] ) {
			$sep_icon_img_id  = ! empty( $attributes['sepIconImg']['id'] ) ? absint( $attributes['sepIconImg']['id'] ) : 0;
			$sep_icon_img_url = ! empty( $attributes['sepIconImg']['url'] ) ? esc_url_raw( $attributes['sepIconImg']['url'] ) : '';
			if ( $sep_icon_img_id > 0 ) {
				$img = wp_get_attachment_image_src( $sep_icon_img_id, 'full' );
				if ( ! empty( $img[0] ) ) {
					$sep_icons     = $img[0];
					$sep_icon_type = 'sep_image';
				} elseif ( $sep_icon_img_url ) {
					$sep_icons     = $sep_icon_img_url;
					$sep_icon_type = 'sep_image';
				}
			} elseif ( $sep_icon_img_url ) {
				$sep_icons     = $sep_icon_img_url;
				$sep_icon_type = 'sep_image';
			}
		}
	}

	$css_class = '';
	if ( 'style-1' === $style ) {
		$bred_style_class = 'bred_style_1';
	} elseif ( 'style-2' === $style ) {
		$bred_style_class = 'bred_style_2';
	}

	$css_class  = ( ! empty( $attributes['bredAlign']['md'] ) ) ? ' bred-' . esc_attr( $attributes['bredAlign']['md'] ) : '';
	$css_class .= ( ! empty( $attributes['bredAlign']['sm'] ) ) ? ' bred-tablet-' . esc_attr( $attributes['bredAlign']['sm'] ) : '';
	$css_class .= ( ! empty( $attributes['bredAlign']['xs'] ) ) ? ' bred-mobile-' . esc_attr( $attributes['bredAlign']['xs'] ) : '';

	$home_title = $attributes['homeTitle'];

	$bd_toggle_home   = ( ! empty( $attributes['bdToggleHome'] ) ) ? true : false;
	$bd_toggle_parent = ( ! empty( $attributes['bdToggleParent'] ) ) ? true : false;

	if ( ( ! empty( $attributes['bdToggleParent'] ) ) ) {
		$letter_limit_parent = ( ! empty( $attributes['letterLimitParent'] ) ) ? $attributes['letterLimitParent'] : '';
	} else {
		$letter_limit_parent = '0';
	}
	if ( ( ! empty( $attributes['bdToggleCurrent'] ) ) ) {
		$letter_limit_current = ( ! empty( $attributes['letterLimitCurrent'] ) ) ? $attributes['letterLimitCurrent'] : '';
	} else {
		$letter_limit_current = '0';
	}

	$bd_toggle_current = ( ! empty( $attributes['bdToggleCurrent'] ) ) ? true : false;

	$breadcrumbs_last_sec_tri_normal = '';
	$breadcrumbs_bar                 = '';

	$breadcrumbs_bar     .= '<div class="tp-breadcrumbs tpgb-block-' . esc_attr( $uid ) . ' ' . esc_attr( $block_class ) . '">';
		$breadcrumbs_bar .= '<div class="pt_plus_breadcrumbs_bar ' . trim( $css_class ) . '">';

	if ( ! empty( $attributes['bredWidth'] ) && 'style-1' === $style ) {
		$breadcrumbs_bar .= '<div class="pt_plus_breadcrumbs_bar_inner ' . esc_attr( $bred_style_class ) . '" style="width:100%">';
	} else {
		$breadcrumbs_bar .= '<div class="pt_plus_breadcrumbs_bar_inner ' . esc_attr( $bred_style_class ) . '">';
	}

				$active_color_current = ( true === $attributes['activeColorCurrent'] ) ? 'default_active' : '';

				$breadcrumbs_bar .= Tp_Blocks_Helper::theplus_breadcrumbs( $icontype, $sep_icon_type, $icons, $home_title, $sep_icons, $active_color_current, $breadcrumbs_last_sec_tri_normal, $bd_toggle_home, $bd_toggle_parent, $bd_toggle_current, $letter_limit_parent, $letter_limit_current, $markup_sch, $ctm_homeurl, $show_terms, $taxonomy_slug, $showpart_terms, $showchild_terms );
			$breadcrumbs_bar     .= '</div>';
		$breadcrumbs_bar         .= '</div>';
	$breadcrumbs_bar             .= '</div>';

	$breadcrumbs_bar = Tpgb_Blocks_Global_Options::block_Wrap_Render( $attributes, $breadcrumbs_bar );

	return $breadcrumbs_bar;
}

/**
 * Tpgb tp breadcrumbs render.
 */
function tpgb_tp_breadcrumbs_render() {

	$block_data = Tpgb_Blocks_Global_Options::merge_options_json( __DIR__, 'tpgb_breadcrumbs_callback', true );
	register_block_type( $block_data['name'], $block_data );
}
add_action( 'init', 'tpgb_tp_breadcrumbs_render' );
