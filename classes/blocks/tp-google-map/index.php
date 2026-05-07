<?php
/**
 * Google Map.
 *
 * @package ThePluginAddonsForBlockEditor
 */

defined( 'ABSPATH' ) || exit;

/**
 * Tpgb tp google map render callback.
 *
 * @param mixed $attributes The attributes.
 * @param mixed $content The content.
 * @return mixed The result.
 */
function tpgb_tp_google_map_render_callback( $attributes, $content ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
	$block_id       = ( ! empty( $attributes['block_id'] ) ) ? $attributes['block_id'] : uniqid( 'map' );
	$content_tgl    = ( ! empty( $attributes['contentTgl'] ) ) ? $attributes['contentTgl'] : false;
	$title          = ( ! empty( $attributes['title'] ) ) ? $attributes['title'] : '';
	$description    = ( ! empty( $attributes['description'] ) ) ? $attributes['description'] : '';
	$location_point = ( ! empty( $attributes['locationPoint'] ) ) ? $attributes['locationPoint'] : '';

	$zoom             = ( ! empty( $attributes['Zoom'] ) ) ? $attributes['Zoom'] : 10;
	$scroll_wheel     = ( ! empty( $attributes['scrollWheel'] ) ) ? $attributes['scrollWheel'] : false;
	$pan_ctrl         = ( ! empty( $attributes['panCtrl'] ) ) ? $attributes['panCtrl'] : false;
	$draggable        = ( ! empty( $attributes['Draggable'] ) ) ? $attributes['Draggable'] : false;
	$zoom_ctrl        = ( ! empty( $attributes['zoomCtrl'] ) ) ? $attributes['zoomCtrl'] : false;
	$map_type_ctrl    = ( ! empty( $attributes['mapTypeCtrl'] ) ) ? $attributes['mapTypeCtrl'] : false;
	$scale_ctrl       = ( ! empty( $attributes['scaleCtrl'] ) ) ? $attributes['scaleCtrl'] : false;
	$full_screen_ctrl = ( ! empty( $attributes['fullScreenCtrl'] ) ) ? $attributes['fullScreenCtrl'] : false;
	$street_view_ctrl = ( ! empty( $attributes['streetViewCtrl'] ) ) ? $attributes['streetViewCtrl'] : false;

	$custom_style_tgl = ( ! empty( $attributes['customStyleTgl'] ) ) ? $attributes['customStyleTgl'] : false;
	$custom_style     = ( ! empty( $attributes['customStyle'] ) ) ? $attributes['customStyle'] : 'style-1';

	$modify_colors = ( ! empty( $attributes['modifyColors'] ) ) ? $attributes['modifyColors'] : false;
	$hue           = ( ! empty( $attributes['hue'] ) ) ? $attributes['hue'] : '';
	$saturation    = ( ! empty( $attributes['saturation'] ) ) ? $attributes['saturation'] : '';
	$lightness     = ( ! empty( $attributes['lightness'] ) ) ? $attributes['lightness'] : '';

	$gmap_type = ( ! empty( $attributes['gmapType'] ) ) ? $attributes['gmapType'] : 'roadmap';

	$block_class = Tp_Blocks_Helper::block_wrapper_classes( $attributes );

	$json_map            = array();
	$json_map['places']  = array();
	$json_map['options'] = array(
		'zoom'              => intval( $zoom ),
		'scrollwheel'       => $scroll_wheel,
		'draggable'         => $draggable,
		'panControl'        => $pan_ctrl,
		'zoomControl'       => $zoom_ctrl,
		'scaleControl'      => $scale_ctrl,
		'mapTypeControl'    => $map_type_ctrl,
		'fullscreenControl' => $full_screen_ctrl,
		'streetViewControl' => $street_view_ctrl,
		'mapTypeId'         => esc_attr( $gmap_type ),
	);

	if ( ! empty( $location_point ) ) {
		foreach ( $location_point as $index => $item ) {
			if ( isset( $item['latOrAddr'] ) && 'address' === $item['latOrAddr'] ) {
				$adrr     = ( ! empty( $item['addr'] ) ) ? $item['addr'] : '';
				$address  = ( ! empty( $item['address'] ) ) ? $item['address'] : '';
				$pin_icon = '';
				if ( ! empty( $item['pinIcon']['id'] ) && ! empty( $item['pinIcon']['url'] ) ) {
					$pin_icon_size = ( ! empty( $item['pinIconSize'] ) ) ? $item['pinIconSize'] : 'full';
					$img           = wp_get_attachment_image_src( $item['pinIcon']['id'], $pin_icon_size );
					$pin_icon      = ( ! empty( $img ) ) ? $img[0] : $item['pinIcon']['url'];
				} elseif ( ! empty( $item['pinIcon']['url'] ) ) {
					$pin_icon = $item['pinIcon']['url'];
				}
				if ( ! empty( $adrr ) ) {
					$json_map['places'][] = array(
						'pin_icon'  => esc_url( $pin_icon ),
						'latOrAddr' => isset( $item['latOrAddr'] ) ? $item['latOrAddr'] : 'address',
						'addr'      => $adrr,
						'address'   => $address,
					);
				}
			} else {
				$longitude = ( ! empty( $item['longitude'] ) ) ? $item['longitude'] : '';
				$latitude  = ( ! empty( $item['latitude'] ) ) ? $item['latitude'] : '';
				$address   = ( ! empty( $item['address'] ) ) ? $item['address'] : '';
				$pin_icon  = '';
				if ( ! empty( $item['pinIcon']['id'] ) && ! empty( $item['pinIcon']['url'] ) ) {
					$pin_icon_size = ( ! empty( $item['pinIconSize'] ) ) ? $item['pinIconSize'] : 'full';
					$img           = wp_get_attachment_image_src( $item['pinIcon']['id'], $pin_icon_size );
					$pin_icon      = ( ! empty( $img ) ) ? $img[0] : $item['pinIcon']['url'];
				} elseif ( ! empty( $item['pinIcon']['url'] ) ) {
					$pin_icon = $item['pinIcon']['url'];
				}
				if ( ! empty( $longitude ) || ! empty( $latitude ) ) {
					$json_map['places'][] = array(
						'address'   => wp_kses_post( $address ),
						'latitude'  => (float) $latitude,
						'longitude' => (float) $longitude,
						'pin_icon'  => esc_url( $pin_icon ),
						'latOrAddr' => isset( $item['latOrAddr'] ) ? $item['latOrAddr'] : 'latitude',
					);
				}
			}
		}
	}

	$json_map = str_replace( "'", '&apos;', wp_json_encode( $json_map ) );

	$output  = '';
	$output .= '<div class="tpgb-google-map tpgb-relative-block tpgb-block-' . esc_attr( $block_id ) . ' ' . esc_attr( $block_class ) . '">';

		$output .= '<div id="gmap-' . esc_attr( $block_id ) . '" class="tpgb-adv-map" data-id="gmap-' . esc_attr( $block_id ) . '" data-map-settings="' . htmlentities( $json_map, ENT_QUOTES, 'UTF-8' ) . '" ></div>';

	$output .= '</div>';

	$output = Tpgb_Blocks_Global_Options::block_Wrap_Render( $attributes, $output );

	return $output;
}
/**
 * Render for the server-side
 */
function tpgb_google_map() {
	$block_data = Tpgb_Blocks_Global_Options::merge_options_json( __DIR__, 'tpgb_tp_google_map_render_callback' );
	register_block_type( $block_data['name'], $block_data );
}
add_action( 'init', 'tpgb_google_map' );
