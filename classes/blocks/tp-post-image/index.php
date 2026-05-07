<?php
/**
 * Tp Block : Post Image.
 *
 * @package ThePluginAddonsForBlockEditor
 */

// phpcs:disable Squiz.PHP.CommentedOutCode.Found
defined( 'ABSPATH' ) || exit;

/**
 * Tpgb tp post image render callback.
 *
 * @param mixed $attr The attr.
 * @param mixed $content The content.
 * @return mixed The result.
 */
function tpgb_tp_post_image_render_callback( $attr, $content ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
	$output  = '';
	$post_id = get_the_ID();

	$block_id    = ( ! empty( $attr['block_id'] ) ) ? $attr['block_id'] : uniqid( 'title' );
	$image_type  = ( ! empty( $attr['imageType'] ) ) ? $attr['imageType'] : 'default';
	$bg_location = ( ! empty( $attr['bgLocation'] ) ) ? $attr['bgLocation'] : 'section';
	$image_size  = ( ! empty( $attr['imageSize'] ) ) ? $attr['imageSize'] : 'full';
	$fancy_box   = ( ! empty( $attr['fancyBox'] ) ) ? $attr['fancyBox'] : false;
	$block_class = Tp_Blocks_Helper::block_wrapper_classes( $attr );

	$data_attr = array();
	if ( ! empty( $image_type ) && 'background' === $image_type ) {
		$block_class             .= ' post-img-bg';
		$data_attr['id']          = $block_id;
		$data_attr['imgType']     = $image_type;
		$data_attr['imgLocation'] = $bg_location;
	}

	$data_attr = wp_json_encode( $data_attr );

	$image_content = '';
	if ( has_post_thumbnail( $post_id ) ) {
		$image_content = get_the_post_thumbnail_url( $post_id, $image_size );
		$fancy_content = get_the_post_thumbnail_url( $post_id, 'full' );
		$image_content = ( ! empty( $image_content ) ) ? $image_content : TPGB_ASSETS_URL . 'assets/images/tpgb-placeholder.jpg';

		// Set Fancy Box Option.
		$data_settings = '';
		$data_fancy    = '';
		$href          = '';
		if ( ! empty( $fancy_box ) ) {
			$fancy_data = ( ! empty( $attr['FancyOption'] ) ) ? json_decode( $attr['FancyOption'] ) : array();

			$button = array();
			if ( is_array( $fancy_data ) || is_object( $fancy_data ) ) {
				foreach ( $fancy_data as $value ) {
					$button_opt = ( ( 'zoom' === $value->value ) ? 'iterateZoom' : ( ( 'fullScreen' === $value->value ) ? 'fullscreen' : $value->value ) );
					if ( 'share' !== $value->value ) {
						$button[] = $button_opt;
					}
				}
			}
			$href               = $fancy_content;
			$fancybox           = array();
			$fancybox['button'] = $button;
			// $fancybox['animationEffect'] = $attr['AnimationFancy']; // phpcs:ignore Squiz.PHP.CommentedOutCode.Found
			// $fancybox['animationDuration'] = $attr['DurationFancy'];
			$data_settings .= ' data-fancy-option=\'' . wp_json_encode( $fancybox ) . '\'';
			$data_settings .= ' data-id="' . esc_attr( $block_id ) . '" ';
			$data_fancy     = 'data-fancybox="postImg-' . esc_attr( $block_id ) . '"';

		} else {
			$href = get_the_permalink();
		}

		$output .= '<div class="tpgb-post-image tpgb-block-' . esc_attr( $block_id ) . ' ' . esc_attr( $block_class ) . ' ' . ( ! empty( $fancy_box ) ? 'tpgb-fancy-add' : '' ) . '" data-setting=\'' . $data_attr . '\' ' . $data_settings . '>';

		if ( ! empty( $image_type ) && 'background' !== $image_type ) {
			$output         .= '<div class="tpgb-featured-image">';
				$output     .= '<a href="' . esc_url( $href ) . '" ' . $data_fancy . '>';
					$output .= get_the_post_thumbnail( $post_id, $image_size, array( 'class' => 'tpgb-featured-img' ) );
				$output     .= '</a>';
			$output         .= '</div>';
		} elseif ( ! empty( $image_type ) && 'background' === $image_type ) {
			$output     .= '<a href="' . esc_url( $href ) . '" ' . $data_fancy . '>';
				$output .= '<div class="tpgb-featured-image" style="background-image: url(' . esc_url( $image_content ) . ')"></div>';
			$output     .= '</a>';
		}

		$output .= '</div>';
	}

	$output = Tpgb_Blocks_Global_Options::block_Wrap_Render( $attr, $output );

	return $output;
}

/**
 * Render for the server-side
 */
function tpgb_post_image_content() {
	if ( method_exists( 'Tpgb_Blocks_Global_Options', 'merge_options_json' ) ) {
		$block_data = Tpgb_Blocks_Global_Options::merge_options_json( __DIR__, 'tpgb_tp_post_image_render_callback' );
		register_block_type( $block_data['name'], $block_data );
	}
}
add_action( 'init', 'tpgb_post_image_content' );
