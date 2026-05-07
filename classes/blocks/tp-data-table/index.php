<?php
/**
 * Data Table.
 *
 * @package ThePluginAddonsForBlockEditor
 */

defined( 'ABSPATH' ) || exit;

/**
 * Tpgb tp datatable callback.
 *
 * @param mixed $attributes The attributes.
 * @param mixed $content The content.
 * @return mixed The result.
 */
function tpgb_tp_datatable_callback( $attributes, $content ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
	$data_table    = '';
	$block_id      = ( ! empty( $attributes['block_id'] ) ) ? $attributes['block_id'] : uniqid( 'title' );
	$content_table = ( ! empty( $attributes['ContentTable'] ) ) ? $attributes['ContentTable'] : '';
	$table_header  = ( ! empty( $attributes['TableHeader'] ) ) ? $attributes['TableHeader'] : array();
	$tablebody     = ( ! empty( $attributes['Tablebody'] ) ) ? $attributes['Tablebody'] : array();
	$tb_sort       = ( ! empty( $attributes['TbSort'] ) ) ? $attributes['TbSort'] : false;
	$icon_position = ( ! empty( $attributes['IconPosition'] ) ) ? $attributes['IconPosition'] : 'left';
	$img_position  = ( ! empty( $attributes['ImgPosition'] ) ) ? $attributes['ImgPosition'] : 'left';

	$block_class = Tp_Blocks_Helper::block_wrapper_classes( $attributes );

	$sorting = ( ! empty( $tb_sort ) ) ? 'yes' : 'no';
	$search  = 'no';
	$filter  = 'no';

	$dt_header = '';
	$dt_body   = '';

	$data_table         .= '<div class="tpgb-block-' . esc_attr( $block_id ) . ' ' . esc_attr( $block_class ) . '">';
		$data_table     .= '<div class="tpgb-table-wrapper">';
			$data_table .= '<table class="tpgb-table" id="tpgb-table-id-' . esc_attr( $block_id ) . '" data-id="' . esc_attr( $block_id ) . '" data-sort-table="' . esc_attr( $sorting ) . '" data-show-entry="' . esc_attr( $filter ) . '" data-searchable="' . esc_attr( $search ) . '" role="grid">';

	if ( 'custom' === $content_table ) {
		$row_count_tb      = count( $table_header );
		$header_array      = array();
		$header_arrayicon  = array();
		$header_arrayimage = array();
		if ( $row_count_tb > 1 ) {
			$counter_row    = 1;
			$inline_count   = 0;
			$cell_col_count = 0;
			$first_row_th   = true;
			$mob_thc        = 0;

			foreach ( $table_header as $index => $item ) {
				$th_icon        = '';
				$th_img         = '';
				$th_column_span = ( ! empty( $item['thColumnSpan'] ) ) ? $item['thColumnSpan'] : 1;
				$th_row_span    = ( ! empty( $item['thRowSpan'] ) ) ? $item['thRowSpan'] : 1;
				$check_text     = ( ! empty( $item['thtext'] ) ? '' : ' less-icon-space' );
				if ( ( ! empty( $item['thDRicon'] ) ) && 'icon' === $item['thDRicon'] && ! empty( $item['thicon'] ) ) {
					$th_icon = '<span class="tpgb-align-icon--' . esc_attr( $icon_position ) . esc_attr( $check_text ) . '"><i class="' . esc_attr( $item['thicon'] ) . ' tableicon"></i></span>';
				} elseif ( ! empty( $item['thDRicon'] ) && 'image' === $item['thDRicon'] && ! empty( $item['thDRimage'] ) ) {
					$thimagesize = ( ! empty( $item['thimagesize'] ) ) ? $item['thimagesize'] : 'thumbnail';
					$th_img_id   = ( isset( $item['thDRimage']['id'] ) ) ? $item['thDRimage']['id'] : '';
					$th_imgurl   = wp_get_attachment_image( $th_img_id, $thimagesize, false, array( 'class' => 'tpgb-col-img--' . esc_attr( $icon_position ) . esc_attr( $check_text ) ) );
					$th_img      = $th_imgurl;
				}

				if ( 'cell' === $item['thAction'] ) {
					$dt_header                 .= '<th class="tpgb-table-col tp-repeater-item-' . esc_attr( $item['_key'] ) . '" colspan="' . esc_attr( $th_column_span ) . '" rowspan="' . esc_attr( $th_row_span ) . '" data-sort="' . esc_attr( $cell_col_count ) . '" scope="col">';
							$dt_header         .= '<span class="tpgb-table__text">';
								$dt_header     .= ( 'left' === $icon_position ) ? $th_icon : '';
								$dt_header     .= ( 'left' === $img_position ) ? $th_img : '';
									$dt_header .= ( ! empty( $item['thtext'] ) ? '<span class="tpgb-table__text-inner">' . wp_kses_post( $item['thtext'] ) . '</span>' : '' );
								$dt_header     .= ( 'right' === $icon_position ) ? $th_icon : '';
								$dt_header     .= ( 'right' === $img_position ) ? $th_img : '';
							$dt_header         .= '</span>';
							$dt_header         .= '<span class="tpgb-sort-icon">';
					if ( ! empty( $tb_sort ) ) {
						$dt_header .= '<i class="up-icon fas fa-sort-up"></i>';
						$dt_header .= '<i class="down-icon fas fa-sort-down"></i>';
					}
							$dt_header     .= '</span>';
								$dt_header .= '</th>';

					if ( isset( $item['thtext'] ) ) {
									$header_array[ $mob_thc ] = wp_kses_post( $item['thtext'] );
					}
											$header_arrayicon[ $mob_thc ]  = $th_icon;
											$header_arrayimage[ $mob_thc ] = $th_img;

											++$mob_thc;
											++$cell_col_count;
				} else {
					if ( $counter_row > 1 && $counter_row < $row_count_tb ) {
						$dt_header   .= '</tr><tr class="tpgb-table-row" role="row">';
						$first_row_th = false;
					} elseif ( 1 === $counter_row && 'row' === $attributes['TableHeader'][0]['thAction'] ) {
						$dt_header .= '<tr class="tpgb-table-row" role="row">';
					}
					$mob_thc = 0;
				}
				++$counter_row;
				++$inline_count;
			}
		}

		$row_count = count( $tablebody );
		if ( $row_count > 1 ) {
			$counter           = 1;
			$cell_inline_count = 0;
			$data_entry_col    = 0;
			$mob_trc           = 0;

			foreach ( $tablebody as $index => $item ) {
				if ( 'cell' === $item['trAction'] ) {
					$tr_column_span = ( ! empty( $item['TrColumnSpan'] ) ) ? $item['TrColumnSpan'] : 1;
					$tr_row_span    = ( ! empty( $item['TrRowSpan'] ) ) ? $item['TrRowSpan'] : 1;
					$tag            = ( ! empty( $item['TrHeading'] ) && 'th' === $item['TrHeading'] ) ? $item['TrHeading'] : 'td';
					$btntx          = ( ! empty( $item['Trbtntext'] ) ? $item['Trbtntext'] : __( 'Click Here', 'the-plus-addons-for-block-editor' ) );
					$btnlink        = ( ! empty( $item['TrbtnLink'] ) && ! empty( $item['TrbtnLink']['url'] ) ) ? 'href="' . esc_url( $item['TrbtnLink']['url'] ) . '"' : '';
					$target         = ( ! empty( $item['TrbtnLink']['target'] ) ) ? 'target="_blank"' : '';
					$nofollow       = ( ! empty( $item['TrbtnLink']['nofollow'] ) ) ? 'rel="nofollow"' : '';

					$tr_icon = '';
					$tr_img  = '';

					$check_text = ( ! empty( $item['trtext'] ) ? '' : ' less-icon-space' );

					if ( ! empty( $item['trDricon'] ) && 'icon' === $item['trDricon'] && ! empty( $item['TrfaIcon'] ) ) {
						$tr_icon = '<span class="tpgb-align-icon--' . esc_attr( $icon_position ) . $check_text . '"><i class="' . esc_attr( $item['TrfaIcon'] ) . ' tableicon"></i></span>';
					} elseif ( ! empty( $item['trDricon'] ) && 'image' === $item['trDricon'] && ! empty( $item['trDrimage'] ) ) {
						$t_rimagesize = ( ! empty( $item['trimagesize'] ) ) ? $item['trimagesize'] : 'thumbnail';
						$tr_drimgid   = ( ! empty( $item['trDrimage']['id'] ) ) ? $item['trDrimage']['id'] : '';
						$tr_imgurl    = wp_get_attachment_image( $tr_drimgid, $t_rimagesize, false, array( 'class' => 'tpgb-col-img--' . esc_attr( $icon_position ) . $check_text ) );
						$tr_img       = $tr_imgurl;
					}
					$dt_body .= '<' . Tp_Blocks_Helper::validate_html_tag( $tag ) . ' class="tpgb-table-col tp-repeater-item-' . esc_attr( $item['_key'] ) . ' ' . ( ! empty( $item['TrLink'] ) && ! empty( $item['TrLink']['url'] ) ? ' tpgb-td-flex' : '' ) . '"  colspan="' . esc_attr( $tr_column_span ) . '" rowspan="' . esc_attr( $tr_row_span ) . '">';

					if ( ! empty( $item['TrLink'] ) && ! empty( $item['TrLink']['url'] ) ) {
								$target1   = ( ! empty( $item['TrLink']['target'] ) ) ? 'target="_blank"' : '';
								$nofollow1 = ( ! empty( $item['TrLink']['nofollow'] ) ) ? 'rel="nofollow"' : '';
								$link_attr = Tp_Blocks_Helper::add_link_attributes( $item['TrLink'] );
								$dt_body  .= '<a href="' . esc_url( $item['TrLink']['url'] ) . '" class="tb-col-link tpgb-relative-block " ' . $target1 . '  ' . $nofollow1 . ' ' . $link_attr . '>';
					}
					if ( ( isset( $item['trtext'] ) && '' !== $item['trtext'] ) || '' !== $tr_icon || '' !== $tr_img ) {
									$dt_body         .= '<span class="tpgb-table__text">';
										$dt_body     .= ( 'left' === $icon_position ) ? $tr_icon : '';
										$dt_body     .= ( 'left' === $img_position ) ? $tr_img : '';
											$dt_body .= ( ! empty( $item['trtext'] ) ? '<span class="tpgb-table__text-inner">' . wp_kses_post( $item['trtext'] ) . '</span>' : '' );
										$dt_body     .= ( 'right' === $icon_position ) ? $tr_icon : '';
										$dt_body     .= ( 'right' === $img_position ) ? $tr_img : '';
									$dt_body         .= '</span>';
					}

					if ( ! empty( $item['TrLink'] ) && ! empty( $item['TrLink']['url'] ) ) {
						$dt_body .= '</a>';
					}

					if ( ( ! empty( $item['Trbtn'] ) ) && true === $item['Trbtn'] ) {
						$btn_attr     = Tp_Blocks_Helper::add_link_attributes( $item['TrbtnLink'] );
						$dt_body     .= '<div class="pt_tpgb_button tp-repeater-item-' . esc_attr( $item['_key'] ) . ' button-style-8">';
							$dt_body .= '<a ' . ( ! empty( $btnlink ) ? $btnlink : 'href="#"' ) . '  ' . $target . ' ' . $nofollow . ' class="button-link-wrap" ' . $btn_attr . '>' . wp_kses_post( $btntx ) . ( ! empty( $item['btnIcon'] ) ? '<i class="' . esc_attr( $item['btnIcon'] ) . '"></i>' : '' ) . '</a>';
						$dt_body     .= '</div>';
					}

											$dt_body .= '</' . Tp_Blocks_Helper::validate_html_tag( $tag ) . '>';

											++$mob_trc;
				} else {
					if ( $counter > 1 && $counter < $row_count ) {
						++$data_entry_col;
						$dt_body .= '</tr><tr data-entry="' . esc_attr( $data_entry_col ) . '" class="tpgb-table-row odd" role="row">';
					} elseif ( 1 === $counter && 'row' === $attributes['Tablebody'][0]['trAction'] ) {
						$data_entry_col = 1;
						$dt_body       .= '<tr data-entry="' . esc_attr( $data_entry_col ) . '" class="tpgb-table-row odd" role="row">';
					}
					$mob_trc = 0;
				}
				++$counter;
				++$cell_inline_count;
			}
		}

			$data_table     .= '<thead>';
				$data_table .= $dt_header;
			$data_table     .= '</thead>';

			$data_table     .= '<tbody>';
				$data_table .= $dt_body;
			$data_table     .= '</tbody>';
	}

				$data_table .= '</table>';
		$data_table         .= '</div>';
	$data_table             .= '</div>';

	$data_table = Tpgb_Blocks_Global_Options::block_Wrap_Render( $attributes, $data_table );

	return $data_table;
}

/**
 * Tpgb tp datatable render.
 */
function tpgb_tp_datatable_render() {

	if ( method_exists( 'Tpgb_Blocks_Global_Options', 'merge_options_json' ) ) {
		$block_data = Tpgb_Blocks_Global_Options::merge_options_json( __DIR__, 'tpgb_tp_datatable_callback' );
		register_block_type( $block_data['name'], $block_data );
	}
}
add_action( 'init', 'tpgb_tp_datatable_render' );
