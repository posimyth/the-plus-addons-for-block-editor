<?php  // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Nexter Blocks Generate Css
 *
 * @since   1.1.3
 * @package TPGB
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Tpgb_Generate_Blocks_Css class
 *
 * @since 1.1.3
 * @package TPGB
 */
class Tpgb_Generate_Blocks_Css {

	/**
	 * Member Variable
	 *
	 * @var instance
	 */
	private static $instance;

	/**
	 * Get instance
	 *
	 * @return Tpgb_Generate_Blocks_Css
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * All attributes
	 *
	 * @var array
	 */
	protected static $all_attributes = array();

	/**
	 * All dynamic attributes
	 *
	 * @var bool
	 */
	protected static $all_dynamicattr = false;

	/**
	 * Constructor
	 */
	public function __construct() {
		if ( ! class_exists( 'csstidy' ) ) {
			require_once TPGB_PATH . 'classes/parse_css/class.csstidy.php';
		}
	}

	/**
	 * Google font load
	 *
	 * @return bool
	 */
	public function google_font_load() {
		$google_font_load = Tp_Blocks_Helper::get_extra_option( 'gfont_load' );
		$google_fonts     = false;
		if ( empty( $google_font_load ) || ( ! empty( $google_font_load ) && 'disable' !== $google_font_load ) ) {
			$google_fonts = true;
			$google_fonts = apply_filters( 'tpgb_google_font_load', $google_fonts );
		}
		return $google_fonts;
	}

	/**
	 * Generate Dynamic Css
	 *
	 * @param string $post_id Post ID. Empty for current post.
	 * @param bool   $dynamic Whether dynamic.
	 * @return string
	 * @since 1.1.3
	 */
	public function generate_dynamic_css( $post_id = '', $dynamic = false ) {
		self::$all_dynamicattr = $dynamic;
		self::$all_attributes  = array();
		$post_id               = ( ! empty( $post_id ) ) ? $post_id : $this->is_post_id();
		$post_data             = get_post( $post_id );
		$content               = ( isset( $post_data->post_content ) ) ? $post_data->post_content : '';
		$parse_blocks          = parse_blocks( $content );

		foreach ( $parse_blocks as $block ) {
			$this->parse_block_settings( $block, $dynamic );
		}

		$css_render = $this->tpgb_cssGenerator();

		if ( ! empty( $css_render ) ) {
			$csstidy = new csstidy();

			$csstidy->set_cfg( 'optimise_shorthands', 1 );
			$csstidy->set_cfg( 'merge_selectors', 2 );
			$csstidy->set_cfg( 'remove_bslash', false );
			$csstidy->set_cfg( 'sort_selectors', true );
			// $csstidy->set_cfg('sort_properties',true);.phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf, Squiz.ControlStructures.ControlSignature.SpaceAfterCloseParenthesis
			// $csstidy->set_cfg('preserve_css',true);.phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf, Squiz.ControlStructures.ControlSignature.SpaceAfterCloseParenthesis
			$csstidy->set_cfg( 'template', 'high' );

			$csstidy->parse( $css_render );

			$css_render = $csstidy->print->plain();
		}

		return $this->minify_css( $css_render );
	}

	/**
	 * Parse Block Settings
	 *
	 * @param array $block Block.
	 * @param bool  $dynamic Whether dynamic.
	 * @return array
	 * @since 1.3.0
	 */
	public function parse_block_settings( $block = array(), $dynamic = false ) {
		$settings = array();

		if ( ! empty( $block ) ) {
			$context = array();

			global $post;
			if ( $post instanceof WP_Post ) {
				$context['postId']   = $post->ID;
				$context['postType'] = $post->post_type;
			}

			$context = apply_filters( 'render_block_context', $context, $block, null );
			$wpblock = new WP_Block( $block, $context );

			$attributes = isset( $wpblock->parsed_block['attrs'] ) ? $wpblock->parsed_block['attrs'] : array();

			if ( ! is_null( $wpblock->block_type ) ) {
				if ( ! isset( $wpblock->block_type->attributes ) ) {
					return $attributes;
				}

				$block_attribute = $wpblock->block_type->attributes;

				foreach ( $attributes as $attribute_name => $value ) {
					if ( ! isset( $block_attribute[ $attribute_name ] ) ) {
						continue;
					}
					$schema = $block_attribute[ $attribute_name ];

					$is_valid = rest_validate_value_from_schema( $value, $schema, $attribute_name );
					if ( is_wp_error( $is_valid ) ) {
						unset( $attributes[ $attribute_name ] );
					}
				}

				foreach ( $block_attribute as $attribute_name => $schema ) {

					if ( isset( $schema['default'] ) && ! isset( $schema['repeaterField'] ) && ! isset( $schema['groupField'] ) ) {
						$attributes[ $attribute_name ] = ( isset( $attributes[ $attribute_name ] ) ) ? array( 'value' => $attributes[ $attribute_name ] ) : array( 'value' => $schema['default'] );

						if ( isset( $schema['style'] ) ) {
							$attributes[ $attribute_name ]['style'] = $schema['style'];
						}
					} elseif ( isset( $schema['repeaterField'] ) ) {
						$repeat_field = array();
						if ( isset( $attributes[ $attribute_name ] ) ) {
							foreach ( $attributes[ $attribute_name ] as $repeat_key => $repeat ) {
								foreach ( $repeat as $key => $repeat_value ) {
									$repeat_field[ $repeat_key ][ $key ] = array( 'value' => $repeat_value );
									if ( isset( $schema['repeaterField'][0]->$key['style'] ) ) {
										$repeat_field[ $repeat_key ][ $key ]['style'] = $schema['repeaterField'][0]->$key['style'];
									} elseif ( is_array( $schema['repeaterField'][0] ) && isset( $schema['repeaterField'][0][ $key ]['style'] ) ) {
										$repeat_field[ $repeat_key ][ $key ]['style'] = $schema['repeaterField'][0][ $key ]['style'];
									}
								}
							}
						} elseif ( isset( $schema['repeaterField'] ) && isset( $schema['default'] ) ) {
							foreach ( $schema['default'] as $repeat_key => $repeat ) {
								foreach ( $repeat as $key => $repeat_value ) {
									$repeat_field[ $repeat_key ][ $key ] = array( 'value' => $repeat_value );
									if ( isset( $schema['repeaterField'][0]->$key['style'] ) ) {
										$repeat_field[ $repeat_key ][ $key ]['style'] = $schema['repeaterField'][0]->$key['style'];
									} elseif ( is_array( $schema['repeaterField'][0] ) && isset( $schema['repeaterField'][0][ $key ]['style'] ) ) {
										$repeat_field[ $repeat_key ][ $key ]['style'] = $schema['repeaterField'][0][ $key ]['style'];
									}
								}
							}
						}
						$attributes[ $attribute_name ]['repeaterField'] = $repeat_field;
					} elseif ( isset( $schema['groupField'] ) ) {
						$repeat_field = array();
						if ( isset( $attributes[ $attribute_name ] ) ) {
							foreach ( $attributes[ $attribute_name ] as $repeat_key => $repeat_value ) {
								$repeat_field[ $repeat_key ] = array( 'value' => $repeat_value );
								if ( isset( $schema['groupField'][0]->$repeat_key['style'] ) ) {
									$repeat_field[ $repeat_key ]['style'] = $schema['groupField'][0]->$repeat_key['style'];
								} elseif ( is_array( $schema['groupField'][0] ) && isset( $schema['groupField'][0][ $repeat_key ]['style'] ) ) {
									$repeat_field[ $repeat_key ]['style'] = $schema['groupField'][0][ $repeat_key ]['style'];
								}
							}
						} elseif ( isset( $schema['groupField'] ) && isset( $schema['default'] ) ) {
							foreach ( $schema['default'] as $repeat_key => $repeat_value ) {
								$repeat_field[ $repeat_key ] = array( 'value' => $repeat_value );
								if ( isset( $schema['groupField'][0]->$repeat_key['style'] ) ) {
									$repeat_field[ $repeat_key ]['style'] = $schema['groupField'][0]->$repeat_key['style'];
								} elseif ( is_array( $schema['groupField'][0] ) && isset( $schema['groupField'][0][ $repeat_key ]['style'] ) ) {
									$repeat_field[ $repeat_key ]['style'] = $schema['groupField'][0][ $repeat_key ]['style'];
								}
							}
						}
						$attributes[ $attribute_name ]['groupField'] = $repeat_field;
					}
				}
			}

			if ( ! empty( $attributes ) ) {

				// Dynamic Value Attr List.
				$dynamic_attr = array();
				if ( ! empty( $dynamic ) ) {
					foreach ( $attributes as $key => $val ) {
						$dynamicpara = array();
						if ( 'block_id' === $key ) {
							$dynamic_attr[ $key ] = $val;
						} elseif ( isset( $val['value'] ) && ! empty( $val['value'] ) && gettype( $val['value'] ) === 'string' && preg_match_all( '/<span data-tpgb-dynamic=(.*?)>(.*?)<\/span>/', $val['value'], $matches ) ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf, Squiz.ControlStructures.ControlSignature.SpaceAfterCloseParenthesis
							// Color Dynamic.
							if ( isset( $matches[1] ) && ! empty( $matches[1] ) ) {
								$json_string          = '[' . str_replace( "'", '', $matches[1][0] ) . ']';
								$array                = json_decode( $json_string, true );
								$dynamicpara['field'] = ( isset( $array[0] ) && ! empty( $array[0] ) ) ? (array) $array[0] : array();
								$dynamicpara['id']    = get_queried_object_id();

								$dynamic_val                   = TPGBP_Pro_Init_Blocks::tpgb_get_dynamic_content( $dynamicpara );
								$dynamic_attr[ $key ]          = $val;
								$dynamic_attr[ $key ]['value'] = $dynamic_val;
							}
						} elseif ( isset( $val['value'] ) && ! empty( $val['value'] ) && gettype( $val['value'] ) === 'array' ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf, Squiz.ControlStructures.ControlSignature.SpaceAfterCloseParenthesis
							if ( isset( $val['value']['openBg'] ) && 1 === $val['value']['openBg'] && 'color' === $val['value']['bgType'] && isset( $val['value']['bgDefaultColor'] ) && preg_match_all( '/<span data-tpgb-dynamic=(.*?)>(.*?)<\/span>/', $val['value']['bgDefaultColor'], $matches ) ) {
								// Bg Dynamic Color.
								if ( isset( $matches[1] ) && ! empty( $matches[1] ) ) {
									$json_string          = '[' . str_replace( "'", '', $matches[1][0] ) . ']';
									$array                = json_decode( $json_string, true );
									$dynamicpara['field'] = ( isset( $array[0] ) && ! empty( $array[0] ) ) ? (array) $array[0] : array();
									$dynamicpara['id']    = get_queried_object_id();

									$dynamic_val                                     = TPGBP_Pro_Init_Blocks::tpgb_get_dynamic_content( $dynamicpara );
									$dynamic_attr[ $key ]                            = $val;
									$dynamic_attr[ $key ]['value']['bgDefaultColor'] = $dynamic_val;
								}
							} elseif ( isset( $val['value']['openBg'] ) && 1 === $val['value']['openBg'] && 'image' === $val['value']['bgType'] && isset( $val['value']['bgImage'] ) && isset( $val['value']['bgImage']['dynamic'] ) && isset( $val['value']['bgImage']['dynamic']['dynamicUrl'] ) ) {
								// Bg Dynamic Image.
								$dynamicpara['field'] = ( ! empty( $val['value']['bgImage']['dynamic'] ) ) ? (array) $val['value']['bgImage']['dynamic'] : array();
								$dynamicpara['id']    = get_queried_object_id();

								$dynamic_val = TPGBP_Pro_Init_Blocks::tpgb_get_dynamic_content( $dynamicpara );

								$dynamic_attr[ $key ] = $val;

								$dynamic_attr[ $key ]['value']['bgImage']['id']  = isset( $dynamic_val['id'] ) ? $dynamic_val['id'] : '';
								$dynamic_attr[ $key ]['value']['bgImage']['url'] = isset( $dynamic_val['url'] ) ? $dynamic_val['url'] : '';

							} elseif ( ( ( isset( $val['value']['openBorder'] ) && 1 === $val['value']['openBorder'] ) || ( isset( $val['value']['openShadow'] ) && true === $val['value']['openShadow'] ) ) && isset( $val['value']['color'] ) && preg_match_all( '/<span data-tpgb-dynamic=(.*?)>(.*?)<\/span>/', $val['value']['color'], $matches ) ) {
								// Border/BoxShadow Dynamic Color.
								if ( isset( $matches[1] ) && ! empty( $matches[1] ) ) {
									$json_string          = '[' . str_replace( "'", '', $matches[1][0] ) . ']';
									$array                = json_decode( $json_string, true );
									$dynamicpara['field'] = ( isset( $array[0] ) && ! empty( $array[0] ) ) ? (array) $array[0] : array();
									$dynamicpara['id']    = get_queried_object_id();

									$dynamic_val                            = TPGBP_Pro_Init_Blocks::tpgb_get_dynamic_content( $dynamicpara );
									$dynamic_attr[ $key ]                   = $val;
									$dynamic_attr[ $key ]['value']['color'] = $dynamic_val;
								}
							}
						} elseif ( isset( $val['value'] ) && ! isset( $val['style'] ) ) {
							$dynamic_attr[ $key ] = $val;
						}
					}
					if ( isset( $attributes['ref'] ) && ! empty( $attributes['ref'] ) ) {
						$post_data    = get_post( $attributes['ref'] );
						$content      = ( isset( $post_data->post_content ) ) ? $post_data->post_content : '';
						$parse_blocks = parse_blocks( $content );
						if ( ! empty( $parse_blocks ) ) {
							foreach ( $parse_blocks as $block ) {
								self::$all_attributes = $this->parse_block_settings( $block, $dynamic );
							}
						}
					}
					$attributes = $dynamic_attr;
				}

				// Tpgb Block.
				if ( preg_match( '/\btpgb\/\b/', $block['blockName'] ) ) {
					$settings[ $block['blockName'] ] = $attributes;
					self::$all_attributes[]          = $settings;

				}
			}
			if ( ! empty( $block['innerBlocks'] ) ) {
				foreach ( $block['innerBlocks'] as $inner_block ) {
					self::$all_attributes = $this->parse_block_settings( $inner_block, $dynamic );
				}
			}
		}

		return self::$all_attributes;
	}

	/**
	 * Nexter Blocks Dynamic CSS Generator.
	 *
	 * @return string The CSS.
	 */
	public function tpgb_cssGenerator() {  // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid,WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
		$make_css          = '';
		$md                = array();
		$sm                = array();
		$xs                = array();
		$no_responsive_css = array();
		$tab_css           = '';
		if ( ! empty( self::$all_attributes ) ) {
			foreach ( self::$all_attributes as $key => $value ) {

				if ( is_array( $value ) && ! empty( $value ) ) {
					foreach ( $value as $block_key => $block_value ) {
						$block_i_d = '';

						// change position first block_id attr.
						if ( isset( $block_value['block_id'] ) && ! empty( $block_value['block_id'] ) ) {
							$position_block_id = $block_value['block_id'];
							unset( $block_value['block_id'] );
							$block_value = array( 'block_id' => $position_block_id ) + $block_value;
						}

						foreach ( $block_value as $attr_key => $attr_value ) {
							$block_i_d = ( 'block_id' === $attr_key && isset( $attr_value['value'] ) ) ? $attr_value['value'] : $block_i_d;

							if ( isset( $attr_value['style'] ) && ! empty( $attr_value['style'] ) ) {

								foreach ( $attr_value['style'] as $index_style => $select_data ) {
									$select_data = (array) $select_data;
									$css_selecor = isset( $select_data['selector'] ) ? $select_data['selector'] : '';

									if ( $this->conditions_styling( $block_value, $select_data, $attr_key, $index_style ) && ( ! isset( $select_data['backend'] ) || ( isset( $select_data['backend'] ) && false === $select_data['backend'] ) ) ) {

										if ( isset( $block_value[ $attr_key ]['value'] ) && ( gettype( $block_value[ $attr_key ]['value'] ) === 'array' || gettype( $block_value[ $attr_key ]['value'] ) === 'object' ) ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf, Squiz.ControlStructures.ControlSignature.SpaceAfterCloseParenthesis
											$values    = $block_value[ $attr_key ]['value'];
											$device    = false;
											$dimension = '';
											// Desktop.
											$values = (array) $values;

											if ( isset( $values['md'] ) && ( ! isset( $select_data['media'] ) || ( isset( $select_data['media'] ) && 'md' === $select_data['media'] ) ) ) {
												$device = true;

												$_gbr     = isset( $values['globalBorderRadius'] ) ? $values['globalBorderRadius'] : null;
												$_gbrf    = isset( $values['globalBorderRadiusFallback'] ) ? $values['globalBorderRadiusFallback'] : null;
												$_gbr_idx = is_array( $_gbr ) ? ( isset( $_gbr['md'] ) ? $_gbr['md'] : '' ) : $_gbr;
												if ( ! empty( $_gbr_idx ) && is_numeric( $_gbr_idx ) ) {
													$_gbr_fb   = is_array( $_gbrf ) ? ( isset( $_gbrf['md'] ) ? $_gbrf['md'] : '' ) : ( is_string( $_gbrf ) ? $_gbrf : '' );
													$dimension = 'var(--tpgb-RAD' . intval( $_gbr_idx ) . ( ! empty( $_gbr_fb ) && is_string( $_gbr_fb ) ? ', ' . $_gbr_fb : '' ) . ')';
												} elseif ( gettype( $values['md'] ) === 'object' || gettype( $values['md'] ) === 'array' ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf, Squiz.ControlStructures.ControlSignature.SpaceAfterCloseParenthesis
													$dimension = $this->tp_object_field( $values['md'] )['data'];
												} else {
													$dimension = ( ! empty( $values['md'] ) || '0' === $values['md'] ) ? $values['md'] . ( isset( $values['unit'] ) ? $values['unit'] : '' ) : '';
												}

												if ( '' !== $dimension ) {
													$selector_data = $this->single_field( $css_selecor, $block_i_d, $attr_key, $dimension, 'tpgb' );
													if ( isset( $selector_data[0] ) && strpos( $selector_data[0], '{{' ) ) {

														$matches = preg_match_all( '/\{{(.*?)\}}/', $selector_data[0], $output_array );
														if ( $matches ) {
															if ( ! empty( $output_array[1] ) ) {
																foreach ( $output_array[1] as $new_key ) {
																	if ( gettype( $block_value[ $new_key ]['value'] ) === 'object' ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf, Squiz.ControlStructures.ControlSignature.SpaceAfterCloseParenthesis
																		$block_value[ $new_key ]['value'] = (array) $block_value[ $new_key ]['value'];
																	}
																	if ( ( isset( $block_value[ $new_key ]['value'] ) && isset( $block_value[ $new_key ]['value']['md'] ) ) && ( gettype( $block_value[ $new_key ]['value']['md'] ) === 'object' || gettype( $block_value[ $new_key ]['value']['md'] ) === 'array' ) ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf, Squiz.ControlStructures.ControlSignature.SpaceAfterCloseParenthesis
																		$dimension = $this->tp_object_field( $block_value[ $new_key ]['value']['md'] )['data'];
																	} elseif ( isset( $block_value[ $new_key ]['value'] ) && isset( $block_value[ $new_key ]['value']['md'] ) ) {
																			$dimension = $block_value[ $new_key ]['value']['md'] . ( isset( $block_value[ $new_key ]['value']['unit'] ) ? $block_value[ $new_key ]['value']['unit'] : '' );
																	}
																	$selector_data = $this->single_field( $selector_data[0], $block_i_d, $new_key, $dimension, 'tpgb' );
																}
															}
														}
													}
													$md = array_merge( $md, $selector_data );
												}
											}

											// Tablet.
											if ( isset( $values['sm'] ) && ( ! isset( $select_data['media'] ) || ( isset( $select_data['media'] ) && 'sm' === $select_data['media'] ) ) ) {
												$device = true;

												$_gbr_idx = is_array( $_gbr ) ? ( isset( $_gbr['sm'] ) ? $_gbr['sm'] : '' ) : $_gbr;
												if ( ! empty( $_gbr_idx ) && is_numeric( $_gbr_idx ) ) {
													$_gbr_fb   = is_array( $_gbrf ) ? ( isset( $_gbrf['sm'] ) ? $_gbrf['sm'] : '' ) : ( is_string( $_gbrf ) ? $_gbrf : '' );
													$dimension = 'var(--tpgb-RAD' . intval( $_gbr_idx ) . ( ! empty( $_gbr_fb ) && is_string( $_gbr_fb ) ? ', ' . $_gbr_fb : '' ) . ')';
												} elseif ( gettype( $values['sm'] ) === 'object' || gettype( $values['sm'] ) === 'array' ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf, Squiz.ControlStructures.ControlSignature.SpaceAfterCloseParenthesis
													$dimension = $this->tp_object_field( $values['sm'] )['data'];
												} else {
													$dimension = ( ! empty( $values['sm'] ) || '0' === $values['sm'] ) ? $values['sm'] . ( isset( $values['unitsm'] ) ? $values['unitsm'] : ( isset( $values['unit'] ) ? $values['unit'] : '' ) ) : '';
												}
												if ( '' !== $dimension ) {
													$selector_data = $this->single_field( $css_selecor, $block_i_d, $attr_key, $dimension, 'tpgb' );
													if ( isset( $selector_data[0] ) && strpos( $selector_data[0], '{{' ) ) {
														$matches = preg_match_all( '/\{{(.*?)\}}/', $selector_data[0], $output_array );
														if ( $matches ) {
															if ( ! empty( $output_array[1] ) ) {
																foreach ( $output_array[1] as $new_key ) {
																	if ( gettype( $block_value[ $new_key ]['value'] ) === 'object' ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf, Squiz.ControlStructures.ControlSignature.SpaceAfterCloseParenthesis
																		$block_value[ $new_key ]['value'] = (array) $block_value[ $new_key ]['value'];
																	}
																	if ( ( isset( $block_value[ $new_key ]['value'] ) && isset( $block_value[ $new_key ]['value']['sm'] ) ) && ( gettype( $block_value[ $new_key ]['value']['sm'] ) === 'object' || gettype( $block_value[ $new_key ]['value']['sm'] ) === 'array' ) ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf, Squiz.ControlStructures.ControlSignature.SpaceAfterCloseParenthesis
																		$dimension = $this->tp_object_field( $block_value[ $new_key ]['value']['sm'] )['data'];
																	} elseif ( isset( $block_value[ $new_key ]['value'] ) && isset( $block_value[ $new_key ]['value']['sm'] ) ) {
																			$dimension = $block_value[ $new_key ]['value']['sm'] . ( isset( $block_value[ $new_key ]['value']['unitsm'] ) ? $block_value[ $new_key ]['value']['unitsm'] : ( isset( $block_value[ $new_key ]['value']['unit'] ) ? $block_value[ $new_key ]['value']['unit'] : '' ) );
																	}
																	$selector_data = $this->single_field( $selector_data[0], $block_i_d, $new_key, $dimension, 'tpgb' );
																}
															}
														}
													}
													/* $sm = array_merge($sm, $selector_data); phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf, Squiz.ControlStructures.ControlSignature.SpaceAfterCloseParenthesis */
													$my_regex = '/@media/m';
													foreach ( $selector_data as $rule ) {
														if ( gettype( $rule ) === 'string' && preg_match( $my_regex, $rule ) ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf, Squiz.ControlStructures.ControlSignature.SpaceAfterCloseParenthesis
															$md = array_merge( $md, array( $rule ) );
														} else {
															$sm = array_merge( $sm, array( $rule ) );
														}
													}
												}
											}

											// Mobile.
											if ( isset( $values['xs'] ) && ( ! isset( $select_data['media'] ) || ( isset( $select_data['media'] ) && 'xs' === $select_data['media'] ) ) ) {
												$device = true;

												$_gbr_idx = is_array( $_gbr ) ? ( isset( $_gbr['xs'] ) ? $_gbr['xs'] : '' ) : $_gbr;
												if ( ! empty( $_gbr_idx ) && is_numeric( $_gbr_idx ) ) {
													$_gbr_fb   = is_array( $_gbrf ) ? ( isset( $_gbrf['xs'] ) ? $_gbrf['xs'] : '' ) : ( is_string( $_gbrf ) ? $_gbrf : '' ); // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf, Squiz.ControlStructures.ControlSignature.SpaceAfterCloseParenthesis
													$dimension = 'var(--tpgb-RAD' . intval( $_gbr_idx ) . ( ! empty( $_gbr_fb ) && is_string( $_gbr_fb ) ? ', ' . $_gbr_fb : '' ) . ')';
												} elseif ( gettype( $values['xs'] ) === 'object' || gettype( $values['xs'] ) === 'array' ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf, Squiz.ControlStructures.ControlSignature.SpaceAfterCloseParenthesis
													$dimension = $this->tp_object_field( $values['xs'] )['data'];
												} else {
													$dimension = ( ! empty( $values['xs'] ) || '0' === $values['xs'] ) ? $values['xs'] . ( isset( $values['unitxs'] ) ? $values['unitxs'] : ( isset( $values['unit'] ) ? $values['unit'] : '' ) ) : '';
												}
												if ( '' !== $dimension ) {
													$selector_data = $this->single_field( $css_selecor, $block_i_d, $attr_key, $dimension, 'tpgb' );
													if ( isset( $selector_data[0] ) && strpos( $selector_data[0], '{{' ) ) {
														$matches = preg_match_all( '/\{{(.*?)\}}/', $selector_data[0], $output_array );
														if ( $matches ) {
															if ( ! empty( $output_array[1] ) ) {
																foreach ( $output_array[1] as $new_key ) {
																	if ( gettype( $block_value[ $new_key ]['value'] ) === 'object' ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf, Squiz.ControlStructures.ControlSignature.SpaceAfterCloseParenthesis
																		$block_value[ $new_key ]['value'] = (array) $block_value[ $new_key ]['value'];
																	}
																	if ( isset( $block_value[ $new_key ]['value'] ) && isset( $block_value[ $new_key ]['value']['xs'] ) && ( gettype( $block_value[ $new_key ]['value']['xs'] ) === 'object' || gettype( $block_value[ $new_key ]['value']['xs'] ) === 'array' ) ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf, Squiz.ControlStructures.ControlSignature.SpaceAfterCloseParenthesis
																		$dimension = $this->tp_object_field( $block_value[ $new_key ]['value']['xs'] )['data'];
																	} elseif ( isset( $block_value[ $new_key ]['value'] ) && isset( $block_value[ $new_key ]['value']['xs'] ) ) {
																		$dimension = $block_value[ $new_key ]['value']['xs'] . ( isset( $block_value[ $new_key ]['value']['unitxs'] ) ? $block_value[ $new_key ]['value']['unitxs'] : ( isset( $block_value[ $new_key ]['value']['unit'] ) ? $block_value[ $new_key ]['value']['unit'] : '' ) );
																	}
																	$selector_data = $this->single_field( $selector_data[0], $block_i_d, $new_key, $dimension, 'tpgb' );
																}
															}
														}
													}
													/* $xs = array_merge($xs, $selector_data); phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf, Squiz.ControlStructures.ControlSignature.SpaceAfterCloseParenthesis */
													$my_regex = '/@media/m';
													foreach ( $selector_data as $rule ) {
														if ( gettype( $rule ) === 'string' && preg_match( $my_regex, $rule ) ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf, Squiz.ControlStructures.ControlSignature.SpaceAfterCloseParenthesis
															$md = array_merge( $md, array( $rule ) );
														} else {
															$xs = array_merge( $xs, array( $rule ) );
														}
													}
												}
											}

											// Normal Responsive.
											if ( ! $device ) {
												$object_css = $this->tp_object_field( $block_value[ $attr_key ]['value'] );

												$rep_warp = $this->replaceWarp( $css_selecor, $block_i_d, 'tpgb' );

												if ( gettype( $object_css['data'] ) === 'array' ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf, Squiz.ControlStructures.ControlSignature.SpaceAfterCloseParenthesis

													if ( count( $object_css['data'] ) > 0 ) {
														if ( isset( $object_css['data']['background'] ) ) {
															array_push( $no_responsive_css, $rep_warp . $object_css['data']['background'] );
														}
														// Typography.
														if ( $object_css['data']['md'] ) {
															if ( gettype( $object_css['data']['md'] ) === 'array' && '' !== $object_css['data']['md'] ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf, Squiz.ControlStructures.ControlSignature.SpaceAfterCloseParenthesis
																array_push( $md, $this->objectReplace( $rep_warp, $object_css['data']['md'] ) );
															} elseif ( '' !== $object_css['data']['md'] ) {
																array_push( $no_responsive_css, $rep_warp . '{' . $object_css['data']['md'] . '}' );
															}
														}
														if ( $object_css['data']['sm'] ) {
															if ( gettype( $object_css['data']['sm'] ) === 'array' && '' !== $object_css['data']['sm'] ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf, Squiz.ControlStructures.ControlSignature.SpaceAfterCloseParenthesis
																array_push( $sm, $this->objectReplace( $rep_warp, $object_css['data']['sm'] ) );
															} elseif ( '' !== $object_css['data']['sm'] ) {
																array_push( $sm, $rep_warp . '{' . $object_css['data']['sm'] . '}' );
															}
														}
														if ( $object_css['data']['xs'] ) {
															if ( gettype( $object_css['data']['xs'] ) === 'array' && '' !== $object_css['data']['xs'] ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf, Squiz.ControlStructures.ControlSignature.SpaceAfterCloseParenthesis
																array_push( $xs, $this->objectReplace( $rep_warp, $object_css['data']['xs'] ) );
															} elseif ( '' !== $object_css['data']['xs'] ) {
																array_push( $xs, $rep_warp . '{' . $object_css['data']['xs'] . '}' );
															}
														}
														if ( isset( $object_css['data']['simple'] ) ) {
															array_push( $no_responsive_css, $rep_warp . '{' . $object_css['data']['simple'] . '}' );
														}
														if ( isset( $object_css['data']['font'] ) ) {
															array_unshift( $no_responsive_css, $object_css['data']['font'] );
														}
													}
												} elseif ( $object_css['data'] && ! strpos( $object_css['data'], '{{' ) ) {
													if ( 'append' === $object_css['action'] ) {
														array_push( $no_responsive_css, $rep_warp . '{' . $object_css['data'] . '}' );
													} else {
														array_push( $no_responsive_css, $this->single_field( $css_selecor, $block_i_d, $attr_key, $object_css['data'], 'tpgb' ) );
													}
												}
											}
										} elseif ( 'hideDesktop' === $attr_key || 'globalHideDesktop' === $attr_key ) {
												$no_responsive_css = array_merge( $no_responsive_css, $this->single_field( $css_selecor, $block_i_d, $attr_key, $block_value[ $attr_key ]['value'], 'tpgb' ) );
										} elseif ( 'hideTablet' === $attr_key || 'globalHideTablet' === $attr_key ) {
											$no_responsive_css = array_merge( $no_responsive_css, $this->single_field( $css_selecor, $block_i_d, $attr_key, $block_value[ $attr_key ]['value'], 'tpgb' ) );
										} elseif ( 'hideMobile' === $attr_key || 'globalHideMobile' === $attr_key ) {
											$no_responsive_css = array_merge( $no_responsive_css, $this->single_field( $css_selecor, $block_i_d, $attr_key, $block_value[ $attr_key ]['value'], 'tpgb' ) );
										} elseif ( '' !== $block_value[ $attr_key ]['value'] ) {
												$is_dynamic = false;
											if ( 'string' === gettype( $block_value[ $attr_key ]['value'] ) && preg_match_all( '/<span data-tpgb-dynamic=(.*?)>(.*?)<\/span>/', $block_value[ $attr_key ]['value'], $matches ) ) {
												if ( isset( $matches[1] ) && ! empty( $matches[1] ) ) {
													$is_dynamic = true;
												}
											}
											if ( ! $is_dynamic ) {
												$no_responsive_css = array_merge( $no_responsive_css, $this->single_field( $css_selecor, $block_i_d, $attr_key, $block_value[ $attr_key ]['value'], 'tpgb' ) );
											}
										}
									}
								}
							} elseif ( isset( $attr_value['repeaterField'] ) && ! empty( $attr_value['repeaterField'] ) ) {

								foreach ( $attr_value['repeaterField'] as $item_index => $item_data ) {
									$item_data = (array) $item_data;

									foreach ( $item_data as $attr_key => $attr_value ) {

										if ( isset( $attr_value['style'] ) && ! empty( $attr_value['style'] ) ) {

											foreach ( $attr_value['style'] as $index_style => $select_data ) {
												$select_data = (array) $select_data;
												$css_selecor = isset( $select_data['selector'] ) ? $select_data['selector'] : '';

												if ( $this->conditions_styling( $block_value, $select_data, $attr_key, $index_style ) ) {
													if ( $this->conditions_styling( $item_data, $select_data, $attr_key, $index_style ) ) {
														if ( isset( $item_data[ $attr_key ]['value'] ) && ( gettype( $item_data[ $attr_key ]['value'] ) === 'array' || gettype( $item_data[ $attr_key ]['value'] ) === 'object' ) ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf, Squiz.ControlStructures.ControlSignature.SpaceAfterCloseParenthesis
															$values    = $item_data[ $attr_key ]['value'];
															$device    = false;
															$dimension = '';

															// Desktop.
															$values = (array) $values;

															// Desktop Responsive.
															if ( isset( $values['md'] ) ) {
																$device = true;
																if ( gettype( $values['md'] ) === 'object' || gettype( $values['md'] ) === 'array' ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf, Squiz.ControlStructures.ControlSignature.SpaceAfterCloseParenthesis
																	$dimension = $this->tp_object_field( $values['md'] )['data'];
																} else {
																	$dimension = ( ! empty( $values['md'] ) || '0' === $values['md'] ) ? $values['md'] . ( isset( $values['unit'] ) ? $values['unit'] : '' ) : '';
																}
																if ( '' !== $dimension ) {
																	$selector_data = $this->single_field( $css_selecor, $block_i_d, $attr_key, $dimension, 'tpgb', $item_data['_key']['value'], $item_index );
																	$md            = array_merge( $md, $selector_data );
																}
															}
															// Tablet Responsive.
															if ( isset( $values['sm'] ) ) {
																$device = true;

																if ( gettype( $values['sm'] ) === 'object' || gettype( $values['sm'] ) === 'array' ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf, Squiz.ControlStructures.ControlSignature.SpaceAfterCloseParenthesis
																	$dimension = $this->tp_object_field( $values['sm'] )['data'];
																} else {
																	$dimension = ( ! empty( $values['sm'] ) || '0' === $values['sm'] ) ? $values['sm'] . ( isset( $values['unitsm'] ) ? $values['unitsm'] : ( isset( $values['unit'] ) ? $values['unit'] : '' ) ) : '';
																}
																if ( '' !== $dimension ) {
																	$selector_data = $this->single_field( $css_selecor, $block_i_d, $attr_key, $dimension, 'tpgb', $item_data['_key']['value'], $item_index );
																	$sm            = array_merge( $sm, $selector_data );
																}
															}
															// Mobile Responsive.
															if ( isset( $values['xs'] ) ) {
																$device = true;

																if ( gettype( $values['xs'] ) === 'object' || gettype( $values['xs'] ) === 'array' ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf, Squiz.ControlStructures.ControlSignature.SpaceAfterCloseParenthesis
																	$dimension = $this->tp_object_field( $values['xs'] )['data'];
																} else {
																	$dimension = ( ! empty( $values['xs'] ) || '0' === $values['xs'] ) ? $values['xs'] . ( isset( $values['unitxs'] ) ? $values['unitxs'] : ( isset( $values['unit'] ) ? $values['unit'] : '' ) ) : '';
																}
																if ( '' !== $dimension ) {
																	$selector_data = $this->single_field( $css_selecor, $block_i_d, $attr_key, $dimension, 'tpgb', $item_data['_key']['value'], $item_index );
																	$xs            = array_merge( $xs, $selector_data );
																}
															}
															if ( ! $device ) {
																$object_css = $this->tp_object_field( $item_data[ $attr_key ]['value'] );

																$rep_warp = $this->replaceWarp( $css_selecor, $block_i_d, 'tpgb' );
																$rep_warp = $this->replaceWarpItem( $rep_warp, $item_data['_key']['value'], $item_index );

																if ( gettype( $object_css['data'] ) === 'array' ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf, Squiz.ControlStructures.ControlSignature.SpaceAfterCloseParenthesis

																	if ( count( $object_css['data'] ) > 0 ) {
																		if ( isset( $object_css['data']['background'] ) ) {
																			array_push( $no_responsive_css, $rep_warp . $object_css['data']['background'] );
																		}
																	}

																	// Typography.
																	if ( $object_css['data']['md'] ) {
																		if ( gettype( $object_css['data']['md'] ) === 'array' && '' !== $object_css['data']['md'] ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf, Squiz.ControlStructures.ControlSignature.SpaceAfterCloseParenthesis
																			array_push( $md, $this->objectReplace( $rep_warp, $object_css['data']['md'] ) );
																		} elseif ( '' !== $object_css['data']['md'] ) {
																			array_push( $no_responsive_css, $rep_warp . '{' . $object_css['data']['md'] . '}' );
																		}
																	}
																	if ( $object_css['data']['sm'] ) {
																		if ( gettype( $object_css['data']['sm'] ) === 'array' && '' !== $object_css['data']['sm'] ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf, Squiz.ControlStructures.ControlSignature.SpaceAfterCloseParenthesis
																			array_push( $sm, $this->objectReplace( $rep_warp, $object_css['data']['sm'] ) );
																		} elseif ( '' !== $object_css['data']['sm'] ) {
																			array_push( $sm, $rep_warp . '{' . $object_css['data']['sm'] . '}' );
																		}
																	}
																	if ( $object_css['data']['xs'] ) {
																		if ( gettype( $object_css['data']['xs'] ) === 'array' && '' !== $object_css['data']['xs'] ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf, Squiz.ControlStructures.ControlSignature.SpaceAfterCloseParenthesis
																			array_push( $xs, $this->objectReplace( $rep_warp, $object_css['data']['xs'] ) );
																		} elseif ( '' !== $object_css['data']['xs'] ) {
																			array_push( $xs, $rep_warp . '{' . $object_css['data']['xs'] . '}' );
																		}
																	}
																	if ( isset( $object_css['data']['simple'] ) ) {
																		array_push( $no_responsive_css, $rep_warp . '{' . $object_css['data']['simple'] . '}' );
																	}
																	if ( isset( $object_css['data']['font'] ) ) {
																		array_unshift( $no_responsive_css, $object_css['data']['font'] );
																	}
																} elseif ( $object_css['data'] && ! strpos( $object_css['data'], '{{' ) ) {
																	if ( 'append' === $object_css['action'] ) {
																		array_push( $no_responsive_css, $rep_warp . '{' . $object_css['data'] . '}' );
																	} else {
																		array_push( $no_responsive_css, $this->single_field( $css_selecor, $block_i_d, $attr_key, $object_css['data'], 'tpgb', $item_data['_key']['value'], $item_index ) );
																	}
																}
															}
														} elseif ( '' !== $item_data[ $attr_key ]['value'] ) {

																$object_css = $this->single_field( $css_selecor, $block_i_d, $attr_key, $item_data[ $attr_key ]['value'], 'tpgb', $item_data['_key']['value'], $item_index );
															if ( isset( $object_css[0] ) && strpos( $object_css[0], '{{' ) ) {
																$matches = preg_match_all( '/\{{(.*?)\}}/', $object_css[0], $output_array );
																if ( $matches ) {
																	if ( ! empty( $output_array[1] ) ) {
																		foreach ( $output_array[1] as $new_key ) {
																			if ( gettype( $block_value[ $new_key ]['value'] ) === 'object' ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf, Squiz.ControlStructures.ControlSignature.SpaceAfterCloseParenthesis
																				$block_value[ $new_key ]['value'] = (array) $block_value[ $new_key ]['value'];
																			}
																			if ( isset( $block_value[ $new_key ]['value'] ) && ( gettype( $block_value[ $new_key ]['value'] ) === 'object' || gettype( $block_value[ $new_key ]['value'] ) === 'array' ) ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf, Squiz.ControlStructures.ControlSignature.SpaceAfterCloseParenthesis
																				$dimension = $this->tp_object_field( $block_value[ $new_key ]['value'] )['data'];
																			} else {
																				$dimension = $block_value[ $new_key ]['value'];
																			}
																			$object_css = $this->single_field( $object_css[0], $block_i_d, $new_key, $dimension, 'tpgb' );
																		}
																	}
																}
															}
																$no_responsive_css = array_merge( $no_responsive_css, $object_css );
														}
													}
												}
											}
										}
									}
								}
							} elseif ( isset( $attr_value['groupField'] ) && ! empty( $attr_value['groupField'] ) ) {

								foreach ( $attr_value['groupField'] as $item_index => $item_data ) {
									$item_data = (array) $item_data;
									if ( isset( $item_data['style'] ) && ! empty( $item_data['style'] ) ) {

										foreach ( $item_data['style'] as $index_style => $select_data ) {
											$select_data = (array) $select_data;
											$css_selecor = isset( $select_data['selector'] ) ? $select_data['selector'] : '';

											if ( $this->conditions_styling( $attr_value['groupField'], $select_data, $item_index, $index_style ) && ( ! isset( $select_data['backend'] ) || ( isset( $select_data['backend'] ) && false === $select_data['backend'] ) ) ) {

												if ( isset( $item_data['value'] ) && ( gettype( $item_data['value'] ) === 'array' || gettype( $item_data['value'] ) === 'object' ) ) {
													$values    = $item_data['value'];
													$device    = false;
													$dimension = '';

													// Desktop.
													$values = (array) $values;

													// Desktop Responsive.
													if ( isset( $values['md'] ) ) {
														$device = true;

														if ( gettype( $values['md'] ) === 'object' || gettype( $values['md'] ) === 'array' ) {
															$dimension = $this->tp_object_field( $values['md'] )['data'];
														} else {
															$dimension = ( ! empty( $values['md'] ) || '0' === $values['md'] ) ? $values['md'] . ( isset( $values['unit'] ) ? $values['unit'] : '' ) : '';
														}
														if ( '' !== $dimension ) {
															$selector_data = $this->single_field( $css_selecor, $block_i_d, $item_index, $dimension, 'tpgb' );
															$md            = array_merge( $md, $selector_data );
														}
													}
													// Tablet Responsive.
													if ( isset( $values['sm'] ) ) {
														$device = true;

														if ( gettype( $values['sm'] ) === 'object' || gettype( $values['sm'] ) === 'array' ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf, Squiz.ControlStructures.ControlSignature.SpaceAfterCloseParenthesis
															$dimension = $this->tp_object_field( $values['sm'] )['data'];
														} else {
															$dimension = ( ! empty( $values['sm'] ) || '0' === $values['sm'] ) ? $values['sm'] . ( isset( $values['unitsm'] ) ? $values['unitsm'] : ( isset( $values['unit'] ) ? $values['unit'] : '' ) ) : '';
														}
														if ( '' !== $dimension ) {
															$selector_data = $this->single_field( $css_selecor, $block_i_d, $item_index, $dimension, 'tpgb' );
															$sm            = array_merge( $sm, $selector_data );
														}
													}
													// Mobile Responsive.
													if ( isset( $values['xs'] ) ) {
														$device = true;

														if ( gettype( $values['xs'] ) === 'object' || gettype( $values['xs'] ) === 'array' ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf, Squiz.ControlStructures.ControlSignature.SpaceAfterCloseParenthesis
															$dimension = $this->tp_object_field( $values['xs'] )['data'];
														} else {
															$dimension = ( ! empty( $values['xs'] ) || '0' === $values['xs'] ) ? $values['xs'] . ( isset( $values['unitxs'] ) ? $values['unitxs'] : ( isset( $values['unit'] ) ? $values['unit'] : '' ) ) : '';
														}
														if ( '' !== $dimension ) {
															$selector_data = $this->single_field( $css_selecor, $block_i_d, $item_index, $dimension, 'tpgb' );
															$xs            = array_merge( $xs, $selector_data );
														}
													}
													if ( ! $device ) {
														$object_css = $this->tp_object_field( $item_data['value'] );

														$rep_warp = $this->replaceWarp( $css_selecor, $block_i_d, 'tpgb' );
														if ( gettype( $object_css['data'] ) === 'array' ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf, Squiz.ControlStructures.ControlSignature.SpaceAfterCloseParenthesis

															if ( count( $object_css['data'] ) > 0 ) {
																if ( isset( $object_css['data']['background'] ) ) {
																	array_push( $no_responsive_css, $rep_warp . $object_css['data']['background'] );
																}
															}

															// Typography.
															if ( $object_css['data']['md'] ) {
																if ( gettype( $object_css['data']['md'] ) === 'array' && '' !== $object_css['data']['md'] ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf, Squiz.ControlStructures.ControlSignature.SpaceAfterCloseParenthesis
																	array_push( $md, $this->objectReplace( $rep_warp, $object_css['data']['md'] ) );
																} elseif ( '' !== $object_css['data']['md'] ) {
																	array_push( $no_responsive_css, $rep_warp . '{' . $object_css['data']['md'] . '}' );
																}
															}
															if ( $object_css['data']['sm'] ) {
																if ( gettype( $object_css['data']['sm'] ) === 'array' && '' !== $object_css['data']['sm'] ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf, Squiz.ControlStructures.ControlSignature.SpaceAfterCloseParenthesis
																	array_push( $sm, $this->objectReplace( $rep_warp, $object_css['data']['sm'] ) );
																} elseif ( '' !== $object_css['data']['sm'] ) {
																	array_push( $sm, $rep_warp . '{' . $object_css['data']['sm'] . '}' );
																}
															}
															if ( $object_css['data']['xs'] ) {
																if ( gettype( $object_css['data']['xs'] ) === 'array' && '' !== $object_css['data']['xs'] ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf, Squiz.ControlStructures.ControlSignature.SpaceAfterCloseParenthesis
																	array_push( $xs, $this->objectReplace( $rep_warp, $object_css['data']['xs'] ) );
																} elseif ( '' !== $object_css['data']['xs'] ) {
																	array_push( $xs, $rep_warp . '{' . $object_css['data']['xs'] . '}' );
																}
															}
															if ( isset( $object_css['data']['simple'] ) ) {
																array_push( $no_responsive_css, $rep_warp . '{' . $object_css['data']['simple'] . '}' );
															}
															if ( isset( $object_css['data']['font'] ) ) {
																array_unshift( $no_responsive_css, $object_css['data']['font'] );
															}
														} elseif ( $object_css['data'] && ! strpos( $object_css['data'], '{{' ) ) {
															if ( 'append' === $object_css['action'] ) {
																array_push( $no_responsive_css, $rep_warp . '{' . $object_css['data'] . '}' );
															} else {
																array_push( $no_responsive_css, $this->single_field( $css_selecor, $block_i_d, $item_index, $object_css['data'], 'tpgb' ) );
															}
														}
													}
												} elseif ( '' !== $item_data['value'] ) {
														$object_css = $this->single_field( $css_selecor, $block_i_d, $item_index, $item_data['value'], 'tpgb' );
													if ( isset( $object_css[0] ) && strpos( $object_css[0], '{{' ) ) {
														$matches = preg_match_all( '/\{{(.*?)\}}/', $object_css[0], $output_array );
														if ( $matches ) {
															if ( ! empty( $output_array[1] ) ) {
																foreach ( $output_array[1] as $new_key ) {
																	if ( gettype( $block_value[ $new_key ]['value'] ) === 'object' ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf, Squiz.ControlStructures.ControlSignature.SpaceAfterCloseParenthesis
																		$block_value[ $new_key ]['value'] = (array) $block_value[ $new_key ]['value'];
																	}
																	if ( isset( $block_value[ $new_key ]['value'] ) && ( gettype( $block_value[ $new_key ]['value'] ) === 'object' || gettype( $block_value[ $new_key ]['value'] ) === 'array' ) ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf, Squiz.ControlStructures.ControlSignature.SpaceAfterCloseParenthesis

																		$dimension = $this->tp_object_field( $block_value[ $new_key ]['value'] )['data'];
																	} else {
																		$dimension = $block_value[ $new_key ]['value'];
																	}
																	$object_css = $this->single_field( $object_css[0], $block_i_d, $new_key, $dimension, 'tpgb' );
																}
															}
														}
													}
													$no_responsive_css = array_merge( $no_responsive_css, $object_css );
												}
											}
										}
									}
								}
							}
						}

						// Global Postion Css.
						if ( ! empty( $block_value['globalPosition'] ) && ! empty( $block_value['globalPosition']['value'] ) && ( ( isset( $block_value['globalPosition']['value']['md'] ) && ! empty( $block_value['globalPosition']['value']['md'] ) ) || ( isset( $block_value['globalPosition']['value']['sm'] ) && ! empty( $block_value['globalPosition']['value']['sm'] ) ) || ( isset( $block_value['globalPosition']['value']['xs'] ) && ! empty( $block_value['globalPosition']['value']['xs'] ) ) ) ) {
							$target = '.tpgb-wrap-' . esc_attr( $block_value['block_id']['value'] );

							if ( ( isset( $block_value['gloabhorizoOri']['value'] ) && ! empty( $block_value['gloabhorizoOri']['value'] ) ) && ( isset( $block_value['glohoriOffset']['value'] ) ) && ( isset( $block_value['glohoriOffset']['value']['unit'] ) ) ) {

								if ( ( isset( $block_value['gloabhorizoOri']['value']['md'] ) && isset( $block_value['glohoriOffset']['value']['md'] ) ) ) {
									array_push( $md, '' . $target . '{ ' . $block_value['gloabhorizoOri']['value']['md'] . ' : ' . $block_value['glohoriOffset']['value']['md'] . $block_value['glohoriOffset']['value']['unit'] . ' }' );
								}

								if ( isset( $block_value['gloabhorizoOri']['value']['sm'] ) && isset( $block_value['glohoriOffset']['value']['sm'] ) ) {
									$tab_css .= '@media (max-width:1024px) and (min-width:767px)  { ' . $target . '{ ' . $block_value['gloabhorizoOri']['value']['sm'] . ' : ' . $block_value['glohoriOffset']['value']['sm'] . $block_value['glohoriOffset']['value']['unit'] . '} } ';
								}

								if ( isset( $block_value['gloabhorizoOri']['value']['xs'] ) && isset( $block_value['glohoriOffset']['value']['xs'] ) ) {
									array_push( $sm, '' . $target . '{ ' . $block_value['gloabhorizoOri']['value']['xs'] . ' : ' . $block_value['glohoriOffset']['value']['xs'] . $block_value['glohoriOffset']['value']['unit'] . ' }' );
								}
							}

							if ( ( isset( $block_value['gloabverticalOri']['value'] ) && ! empty( $block_value['gloabverticalOri']['value'] ) ) && ( isset( $block_value['gloverticalOffset']['value'] ) ) && ( isset( $block_value['gloverticalOffset']['value']['unit'] ) ) ) {

								if ( ( isset( $block_value['gloabverticalOri']['value']['md'] ) && isset( $block_value['gloverticalOffset']['value']['md'] ) ) ) {
									array_push( $md, '' . $target . '{ ' . $block_value['gloabverticalOri']['value']['md'] . ' : ' . $block_value['gloverticalOffset']['value']['md'] . $block_value['gloverticalOffset']['value']['unit'] . ' }' );
								}

								if ( isset( $block_value['gloabverticalOri']['value']['sm'] ) && isset( $block_value['gloverticalOffset']['value']['sm'] ) ) {
									$tab_css .= '@media (max-width:1024px) and (min-width:767px)  { ' . $target . '{ ' . $block_value['gloabverticalOri']['value']['sm'] . ' : ' . $block_value['gloverticalOffset']['value']['sm'] . $block_value['gloverticalOffset']['value']['unit'] . ' } } ';
								}

								if ( isset( $block_value['gloabverticalOri']['value']['xs'] ) && isset( $block_value['gloverticalOffset']['value']['xs'] ) ) {
									array_push( $sm, '' . $target . '{ ' . $block_value['gloabverticalOri']['value']['xs'] . ' : ' . $block_value['gloverticalOffset']['value']['xs'] . $block_value['gloverticalOffset']['value']['unit'] . ' }' );
								}
							}
						}

						// Transform & Transition.
						if ( ( ! empty( $block_value['gRotte']['tpgbReset'] ) ) || ( ! empty( $block_value['gOfset']['tpgbReset'] ) ) || ( ! empty( $block_value['gScle']['tpgbReset'] ) ) || ( ! empty( $block_value['gSkew']['tpgbReset'] ) ) || ( ! empty( $block_value['gRotteHov']['tpgbReset'] ) ) || ( ! empty( $block_value['gOfsetHov']['tpgbReset'] ) ) || ( ! empty( $block_value['gScleHov']['tpgbReset'] ) ) || ( ! empty( $block_value['gSkewHov']['tpgbReset'] ) ) || ( ! empty( $block_value['gFHori'] ) ) || ( ! empty( $block_value['gFVert'] ) ) || ( ! empty( $block_value['gFHoriHov'] ) ) || ( ! empty( $block_value['gFVertHov'] ) ) ) {

							$target = isset( $block_value['block_id']['value'] ) && ! empty( $block_value['block_id']['value'] ) ? '.tpgb-block-' . esc_attr( $block_value['block_id']['value'] ) : '';

							// Generate CSS for each breakpoint.
							$breakpoints = array( 'md', 'sm', 'xs' );

							foreach ( $breakpoints as $breakpoint ) {
								// Normal state.
								$transform = $this->tpgb_build_transform_string( $block_value, $breakpoint );
								if ( ! empty( $transform ) ) {
									$css = $target . '{ transform: ' . $transform . '; }';

									if ( 'md' === $breakpoint ) {
										array_push( $md, $css );
									} elseif ( 'sm' === $breakpoint ) {
										$tab_css .= '@media (max-width:1024px) and (min-width:767px) { ' . $css . ' } ';
									} elseif ( 'xs' === $breakpoint ) {
										array_push( $xs, $css );
									}
								}

								// Hover state.
								$transform_hov = $this->tpgb_build_transform_string( $block_value, $breakpoint, true );
								if ( ! empty( $transform_hov ) ) {
									$css = $target . ':hover{ transform: ' . $transform_hov . '; }';

									if ( 'md' === $breakpoint ) {
										array_push( $md, $css );
									} elseif ( 'sm' === $breakpoint ) {
										$tab_css .= '@media (max-width:1024px) and (min-width:767px) { ' . $css . ' } ';
									} elseif ( 'xs' === $breakpoint ) {
										array_push( $xs, $css );
									}
								}
							}

							// Transition Duration (only for md).
							if ( isset( $block_value['gTraDur']['value'] ) && ! empty( $block_value['gTraDur']['value'] ) ) {
								array_push( $md, $target . '{ transition: all ' . $block_value['gTraDur']['value'] . 'ms; }' );
							}

							// Transition Timing Function (only for md).
							if ( isset( $block_value['gTraFunc']['value'] ) && ! empty( $block_value['gTraFunc']['value'] ) ) {
								array_push( $md, $target . '{ transition-timing-function: ' . $block_value['gTraFunc']['value'] . '; }' );
							}

							// Transform Origin (only for md).
							if ( isset( $block_value['gTraOrigin']['value'] ) && ! empty( $block_value['gTraOrigin']['value'] ) ) {
								array_push( $md, $target . '{ transform-origin: ' . $block_value['gTraOrigin']['value'] . '; }' );
							}
						}

						// Pro All Inline Css.
						if ( has_filter( 'tpgb_generate_inline_css' ) && false === self::$all_dynamicattr ) {
							$border_css = apply_filters( 'tpgb_generate_inline_css', $block_value, $block_key );

							if ( isset( $border_css['noResponsive'] ) && ! empty( $border_css['noResponsive'] ) ) {
								array_push( $no_responsive_css, $border_css['noResponsive'] );
							}
							if ( isset( $border_css['md'] ) && ! empty( $border_css['md'] ) ) {
								array_push( $md, $border_css['md'] );
							}
							if ( isset( $border_css['sm'] ) && ! empty( $border_css['sm'] ) ) {
								array_push( $sm, $border_css['sm'] );
							}
							if ( isset( $border_css['xs'] ) && ! empty( $border_css['xs'] ) ) {
								array_push( $xs, $border_css['xs'] );
							}
							if ( isset( $border_css['tabCss'] ) && ! empty( $border_css['tabCss'] ) ) {
								$tab_css .= $border_css['tabCss'];
							}
						}

						if ( 'tpgb/tp-creative-image' === $block_key ) {
							$uid = 'bg-image' . esc_attr( $block_value['block_id']['value'] );
							if ( ! empty( $block_value['showMaskImg']['value'] ) && isset( $block_value['MaskImg'] ) && isset( $block_value['MaskImg']['value']['url'] ) && ! empty( $block_value['MaskImg']['value']['url'] ) ) {
								array_push( $no_responsive_css, '.' . esc_attr( $uid ) . '.tpgb-animate-image .tpgb-creative-img-wrap.tpgb-creative-mask-media{mask-image: url(' . esc_url( $block_value['MaskImg']['value']['url'] ) . ');-webkit-mask-image: url(' . esc_url( $block_value['MaskImg']['value']['url'] ) . ');}' );
							}
						}

						if ( 'tpgb/tp-heading-title' === $block_key && false === self::$all_dynamicattr ) {
							if ( ! empty( $block_value['Alignment'] ) && isset( $block_value['Alignment']['value'] ) && ! empty( $block_value['Alignment']['value'] ) ) {
								$style_css = '';
								$style_md  = '';
								$style_sm  = '';
								$style_xs  = '';
								$alignment = $block_value['Alignment']['value'] ?? '';
								$block_id  = esc_attr( $block_value['block_id']['value'] );
								$style     = $block_value['style']['value'];
								$styles    = array( 'style-3', 'style-6', 'style-8' );

								if ( in_array( $style, $styles, true ) ) {
									foreach ( array( 'md', 'sm', 'xs' ) as $size ) {
										if ( ! empty( $alignment[ $size ] ) ) {
											$align    = $alignment[ $size ];
											$selector = ".tpgb-block-$block_id";
											if ( 'style-6' === $style ) {
												$selector                        .= 'heading-style-6 .head-title:after';
												$margin                           = ( 'center' === $align ) ? '-30px' : '0';
												$left                             = ( 'left' === $align ) ? '15px' : 'auto';
												$right                            = ( 'right' === $align ) ? '15px' : 'auto';
												${'style_' . strtoupper( $size )} = "$selector { margin-left: $margin; left: $left; right: $right; }";
											} else {
												$selector                        .= 'tpgb-heading-title .seprator';
												$margin                           = ( 'center' === $align ) ? '0 auto' : ( ( 'left' === $align ) ? '0 auto 0 0' : '0 0 0 auto' );
												${'style_' . strtoupper( $size )} = "$selector { margin: $margin; }";
											}
										}
									}
									array_push( $md, $style_md ?? '' );
									array_push( $sm, $style_sm ?? '' );
									array_push( $xs, $style_xs ?? '' );
								}
							}
						}

						if ( 'tpgb/tp-infobox' === $block_key && false === self::$all_dynamicattr ) {

							if ( ( isset( $block_value['iconOverlay']['value'] ) && ! empty( $block_value['iconOverlay']['value'] ) ) || ( ! empty( $block_value['imgOverlay']['value'] ) ) ) {
								$box_padding = ( isset( $block_value['boxPadding']['value'] ) && ! empty( $block_value['boxPadding']['value'] ) ) ? $block_value['boxPadding']['value'] : '';
								$style_type  = ( isset( $block_value['styleType']['value'] ) && ! empty( $block_value['styleType']['value'] ) ) ? $block_value['styleType']['value'] : '';

								if ( 'style-1' === $style_type ) {
									$box_padding_md = ( ! empty( $box_padding['md'] ) && ! empty( $box_padding['md']['left'] ) ) ? $box_padding['md']['left'] : '15';
									$box_padding_sm = ( ! empty( $box_padding['sm'] ) && ! empty( $box_padding['sm']['left'] ) ) ? $box_padding['sm']['left'] : '';
									$box_padding_xs = ( ! empty( $box_padding['xs'] ) && ! empty( $box_padding['xs']['left'] ) ) ? $box_padding['xs']['left'] : '';
								}
								if ( 'style-2' === $style_type ) {
									$box_padding_md = ( ! empty( $box_padding['md'] ) && ! empty( $box_padding['md']['right'] ) ) ? $box_padding['md']['right'] : '15';
									$box_padding_sm = ( ! empty( $box_padding['sm'] ) && ! empty( $box_padding['sm']['right'] ) ) ? $box_padding['sm']['right'] : '';
									$box_padding_xs = ( ! empty( $box_padding['xs'] ) && ! empty( $box_padding['xs']['right'] ) ) ? $box_padding['xs']['right'] : '';
								}
								if ( 'style-3' === $style_type ) {
									$box_padding_md = ( ! empty( $box_padding['md'] ) && ! empty( $box_padding['md']['top'] ) ) ? $box_padding['md']['top'] : '15';
									$box_padding_sm = ( ! empty( $box_padding['sm'] ) && ! empty( $box_padding['sm']['top'] ) ) ? $box_padding['sm']['top'] : '';
									$box_padding_xs = ( ! empty( $box_padding['xs'] ) && ! empty( $box_padding['xs']['top'] ) ) ? $box_padding['xs']['top'] : '';
								}

								$box_padding_md = ( ! empty( $box_padding_md ) && isset( $box_padding['unit'] ) ) ? $box_padding_md . $box_padding['unit'] : '15px';
								$box_padding_sm = ( ! empty( $box_padding_sm ) && isset( $box_padding['unit'] ) ) ? $box_padding_sm . $box_padding['unit'] : '';
								$box_padding_xs = ( ! empty( $box_padding_xs ) && isset( $box_padding['unit'] ) ) ? $box_padding_xs . $box_padding['unit'] : '';

								if ( 'style-1' === $style_type ) {
									array_push( $md, '.tpgb-block-' . esc_attr( $block_value['block_id']['value'] ) . '.tpgb-infobox.info-box-style-1 .icon-overlay .m-r-16{left: -' . esc_attr( $box_padding_md ) . ';}' );

									if ( ! empty( $box_padding_sm ) ) {
										array_push( $sm, '.tpgb-block-' . esc_attr( $block_value['block_id']['value'] ) . '.tpgb-infobox.info-box-style-1 .icon-overlay .m-r-16{left: -' . esc_attr( $box_padding_sm ) . ';}' );
									}

									if ( ! empty( $box_padding_xs ) ) {
										array_push( $xs, '.tpgb-block-' . esc_attr( $block_value['block_id']['value'] ) . '.tpgb-infobox.info-box-style-1 .icon-overlay .m-r-16{left: -' . esc_attr( $box_padding_xs ) . ';}' );
									}
								}
								if ( 'style-2' === $style_type ) {
									array_push( $md, '.tpgb-block-' . esc_attr( $block_value['block_id']['value'] ) . '.tpgb-infobox.info-box-style-2 .icon-overlay .m-l-16{right: -' . esc_attr( $box_padding_md ) . ';}' );

									if ( ! empty( $box_padding_sm ) ) {
										array_push( $sm, '.tpgb-block-' . esc_attr( $block_value['block_id']['value'] ) . '.tpgb-infobox.info-box-style-2 .icon-overlay .m-l-16{right: -' . esc_attr( $box_padding_sm ) . ';}' );
									}

									if ( ! empty( $box_padding_xs ) ) {
										array_push( $xs, '.tpgb-block-' . esc_attr( $block_value['block_id']['value'] ) . '.tpgb-infobox.info-box-style-2 .icon-overlay .m-l-16{right: -' . esc_attr( $box_padding_xs ) . ';}' );
									}
								}
								if ( 'style-3' === $style_type ) {
									array_push( $md, '.tpgb-block-' . esc_attr( $block_value['block_id']['value'] ) . '.tpgb-infobox.info-box-style-3 .icon-overlay .info-icon-content{top: -' . esc_attr( $box_padding_md ) . ';}' );

									if ( ! empty( $box_padding_sm ) ) {
										array_push( $sm, '.tpgb-block-' . esc_attr( $block_value['block_id']['value'] ) . '.tpgb-infobox.info-box-style-3 .icon-overlay .info-icon-content{top: -' . esc_attr( $box_padding_sm ) . ';}' );
									}

									if ( ! empty( $box_padding_xs ) ) {
										array_push( $xs, '.tpgb-block-' . esc_attr( $block_value['block_id']['value'] ) . '.tpgb-infobox.info-box-style-3 .icon-overlay .info-icon-content{top: -' . esc_attr( $box_padding_xs ) . ';}' );
									}
								}
							}
						}

						if ( 'tpgb/tp-pricing-list' === $block_key ) {
							if ( ! empty( $block_value['imgShape']['value'] ) && 'custom' === $block_value['imgShape']['value'] && isset( $block_value['maskImg'] ) && isset( $block_value['maskImg']['value']['url'] ) && ! empty( $block_value['maskImg']['value']['url'] ) ) {

								array_push( $no_responsive_css, '.tpgb-block-' . esc_attr( $block_value['block_id']['value'] ) . '.tpgb-pricing-list .food-img.img-custom{mask-image: url(' . esc_url( $block_value['maskImg']['value']['url'] ) . ');-webkit-mask-image: url(' . esc_url( $block_value['maskImg']['value']['url'] ) . ');}' );

							}
						}

						if ( 'tpgb/tp-container' === $block_key ) {

							// Grid CSS for Column.
							if ( ! empty( $block_value['columnsRepeater'] ) ) {
								array_push( $md, $this->generate_grid_styles( $block_value['columnsRepeater'], $block_value['contentWidth']['value'], $block_value['block_id']['value'], 'column', 'md' ) );

								array_push( $sm, $this->generate_grid_styles( $block_value['columnsRepeater'], $block_value['contentWidth']['value'], $block_value['block_id']['value'], 'column', 'sm' ) );

								array_push( $xs, $this->generate_grid_styles( $block_value['columnsRepeater'], $block_value['contentWidth']['value'], $block_value['block_id']['value'], 'column', 'xs' ) );
							}

							// Grid CSS for row.
							if ( ! empty( $block_value['rowsRepeater'] ) ) {

								array_push( $md, $this->generate_grid_styles( $block_value['rowsRepeater'], $block_value['contentWidth']['value'], $block_value['block_id']['value'], 'row', 'md' ) );

								array_push( $sm, $this->generate_grid_styles( $block_value['rowsRepeater'], $block_value['contentWidth']['value'], $block_value['block_id']['value'], 'row', 'sm' ) );

								array_push( $xs, $this->generate_grid_styles( $block_value['rowsRepeater'], $block_value['contentWidth']['value'], $block_value['block_id']['value'], 'row', 'xs' ) );
							}

							// Flex Child CSS.
							$flex_child = ( ! empty( $block_value['flexChild'] ) ) ? $block_value['flexChild'] : array();
							if ( ! empty( $flex_child ) && ! empty( $block_value['showchild'] ) ) {
								$block_id      = $block_value['block_id']['value'];
								$is_full_width = empty( $block_value['contentWidth']['value'] ) || 'full' === $block_value['contentWidth']['value'];
								$flex_props    = array(
									'flexShrink' => 'flex-shrink',
									'flexGrow'   => 'flex-grow',
									'flexOrder'  => 'order',
								);

								foreach ( $flex_child as $index => $item ) {
									$nth            = (int) $index + 1;
									$child_selector = $is_full_width
										? '.tpgb-block-' . $block_id . '.tpgb-container-row > *:nth-child(' . $nth . ')'
										: '.tpgb-block-' . $block_id . '.tpgb-container-row > .tpgb-cont-in > *:nth-child(' . $nth . ')';

									foreach ( array( 'md', 'sm', 'xs' ) as $bp ) {
										foreach ( $flex_props as $attr => $css ) {
											if ( isset( $item[ $attr ][ $bp ] ) && '' !== $item[ $attr ][ $bp ] ) {
												array_push( $$bp, $child_selector . '{ ' . $css . ': ' . $item[ $attr ][ $bp ] . '; }' );
											}
										}
										if ( isset( $item['flexBasis'][ $bp ] ) && '' !== $item['flexBasis'][ $bp ] && isset( $item['flexBasis']['unit'] ) ) {
											array_push( $$bp, $child_selector . '{ flex-basis: ' . $item['flexBasis'][ $bp ] . $item['flexBasis']['unit'] . '; }' );
										}
										if ( ! empty( $item['flexselfAlign'][ $bp ] ) ) {
											array_push( $$bp, $child_selector . '{ align-self: ' . $item['flexselfAlign'][ $bp ] . '; }' );
										}
									}
								}
							}
						}

						if ( 'tpgb/tp-testimonials' === $block_key ) {
							if ( isset( $block_value['imageBorderRadius']['value']['md'] ) && empty( $block_value['imageBorderRadius']['value']['md']['top'] ) && empty( $block_value['imageBorderRadius']['value']['md']['right'] ) && empty( $block_value['imageBorderRadius']['value']['md']['bottom'] ) && empty( $block_value['imageBorderRadius']['value']['md']['left'] ) ) {
								array_push( $md, '.tpgb-block-' . esc_attr( $block_value['block_id']['value'] ) . '.tpgb-testimonials .post-content-image .author-thumb{ -webkit-mask-image: url(' . esc_url( TPGB_URL . 'assets/images/testimonial-mask.svg' ) . ');mask-image: url(' . esc_url( TPGB_URL . 'assets/images/testimonial-mask.svg' ) . ');display:inline-block;-webkit-mask-repeat:no-repeat;mask-repeat:no-repeat;mask-size:contain;width:75px;height:75px;}' );
							}
						}
					}
				}
			}

			// Combine Css.
			$fonts_url = array();
			foreach ( $no_responsive_css as $key => $font_url ) {
				if ( ! is_array( $font_url ) && strpos( $font_url, '@import url' ) !== false ) {
					$fonts_url[] = $font_url;
					unset( $no_responsive_css[ $key ] );
				}
			}

			if ( ! empty( $fonts_url ) ) {
				$unique_font = array_unique( $fonts_url );
				$make_css   .= join( '', $unique_font );
			}
			if ( ! empty( $no_responsive_css ) ) {
				$new_arr = array();
				array_walk_recursive(
					$no_responsive_css,
					function ( $v ) use ( &$new_arr ) {
						$new_arr[] = $v;
					}
				);
				$make_css .= join( '', $new_arr );
			}

			if ( ! empty( $md ) ) {
				$make_css .= join( '', $md );
			}
			if ( ! empty( $sm ) ) {
				$make_css .= '@media (max-width: 1024px) {' . join( '', $sm ) . '}';
			}

			// Tab Position Css.
			if ( ! empty( $tab_css ) ) {
				$make_css .= $tab_css;
			}

			if ( ! empty( $xs ) ) {
				$make_css .= '@media (max-width: 767px) {' . join( '', $xs ) . '}';
			}

			return $make_css;
		}
	}

	/**
	 * Build transform string for a specific breakpoint.
	 *
	 * @since 1.0.0
	 * @param array<string, mixed> $block_value The block value.
	 * @param string               $breakpoint  The breakpoint name. Default 'md'.
	 * @param boolean              $is_hover    Whether the hover is active. Default false.
	 * @return string The transform string.
	 */
	public function tpgb_build_transform_string( $block_value, $breakpoint, $is_hover = false ) {
		$transforms = array();
		$suffix     = $is_hover ? 'Hov' : '';

		// Add perspective as first transform function.
		if ( isset( $block_value[ 'gRotte' . $suffix ] ) && ! empty( $block_value[ 'gRotte' . $suffix ]['tpgbReset'] ) ) {
			$rotate     = $block_value[ 'gRotte' . $suffix ];
			$toggle_key = $is_hover ? 'rToggleHov' : 'rotateToogle';

			if ( isset( $rotate[ $toggle_key ] ) && $rotate[ $toggle_key ] ) {
				$perspective_key = $is_hover ? 'gPersHov' : 'globalPerspective';
				if ( isset( $rotate[ $perspective_key ][ $breakpoint ] ) && '' !== $rotate[ $perspective_key ][ $breakpoint ] ) {
					$unit         = isset( $rotate[ $perspective_key ]['unit'] ) ? $rotate[ $perspective_key ]['unit'] : 'px';
					$transforms[] = 'perspective(' . $rotate[ $perspective_key ][ $breakpoint ] . $unit . ')';
				}
			}
		}

		// Rotate.
		if ( isset( $block_value[ 'gRotte' . $suffix ] ) && ! empty( $block_value[ 'gRotte' . $suffix ]['tpgbReset'] ) ) {

			$rotate = $block_value[ 'gRotte' . $suffix ];
			if ( isset( $rotate[ 'gRotteDeg' . $suffix ][ $breakpoint ] ) && ! empty( $rotate[ 'gRotteDeg' . $suffix ][ $breakpoint ] ) ) {
				$transforms[] = 'rotate(' . $rotate[ 'gRotteDeg' . $suffix ][ $breakpoint ] . 'deg)';
			}

			$toggle_key = $is_hover ? 'rToggleHov' : 'rotateToogle';
			if ( isset( $rotate[ $toggle_key ] ) && '' !== $rotate[ $toggle_key ] ) {
				if ( isset( $rotate[ 'gRotteX' . $suffix ][ $breakpoint ] ) && '' !== $rotate[ 'gRotteX' . $suffix ][ $breakpoint ] ) {
					$transforms[] = 'rotateX(' . $rotate[ 'gRotteX' . $suffix ][ $breakpoint ] . 'deg)';
				}
				if ( isset( $rotate[ 'gRotteY' . $suffix ][ $breakpoint ] ) && '' !== $rotate[ 'gRotteY' . $suffix ][ $breakpoint ] ) {
					$transforms[] = 'rotateY(' . $rotate[ 'gRotteY' . $suffix ][ $breakpoint ] . 'deg)';
				}
			}
		}

		// Offset/Translate.
		if ( isset( $block_value[ 'gOfset' . $suffix ] ) && ! empty( $block_value[ 'gOfset' . $suffix ]['tpgbReset'] ) ) {
			$offset = $block_value[ 'gOfset' . $suffix ];

			if ( isset( $offset[ 'gOfsetX' . $suffix ][ $breakpoint ] ) && ! empty( $offset[ 'gOfsetX' . $suffix ][ $breakpoint ] ) ) {
				$unit         = isset( $offset[ 'gOfsetX' . $suffix ]['unit'] ) ? $offset[ 'gOfsetX' . $suffix ]['unit'] : 'px';
				$transforms[] = 'translateX(' . $offset[ 'gOfsetX' . $suffix ][ $breakpoint ] . $unit . ')';
			}
			if ( isset( $offset[ 'gOfsetY' . $suffix ][ $breakpoint ] ) && ! empty( $offset[ 'gOfsetY' . $suffix ][ $breakpoint ] ) ) {
				$unit         = isset( $offset[ 'gOfsetY' . $suffix ]['unit'] ) ? $offset[ 'gOfsetY' . $suffix ]['unit'] : 'px';
				$transforms[] = 'translateY(' . $offset[ 'gOfsetY' . $suffix ][ $breakpoint ] . $unit . ')';
			}
			if ( isset( $offset[ 'gOfsetZ' . $suffix ][ $breakpoint ] ) && ! empty( $offset[ 'gOfsetZ' . $suffix ][ $breakpoint ] ) ) {
				$unit         = isset( $offset[ 'gOfsetZ' . $suffix ]['unit'] ) ? $offset[ 'gOfsetZ' . $suffix ]['unit'] : 'px';
				$transforms[] = 'translateZ(' . $offset[ 'gOfsetZ' . $suffix ][ $breakpoint ] . $unit . ')';
			}
		}

		// Scale.
		if ( isset( $block_value[ 'gScle' . $suffix ] ) && ! empty( $block_value[ 'gScle' . $suffix ]['tpgbReset'] ) ) {
			$scale         = $block_value[ 'gScle' . $suffix ];
			$keep_prop_key = $is_hover ? 'keepPropHov' : 'keepProportions';

			if ( isset( $scale[ $keep_prop_key ] ) && $scale[ $keep_prop_key ] &&
				isset( $scale[ 'gScleValue' . $suffix ][ $breakpoint ] ) && '' !== $scale[ 'gScleValue' . $suffix ][ $breakpoint ] ) {
				$transforms[] = 'scale(' . $scale[ 'gScleValue' . $suffix ][ $breakpoint ] . ')';
			} elseif ( isset( $scale[ $keep_prop_key ] ) && ! $scale[ $keep_prop_key ] ) {
				if ( isset( $scale[ 'gScleX' . $suffix ][ $breakpoint ] ) && '' !== $scale[ 'gScleX' . $suffix ][ $breakpoint ] ) {
					$transforms[] = 'scaleX(' . $scale[ 'gScleX' . $suffix ][ $breakpoint ] . ')';
				}
				if ( isset( $scale[ 'gScleY' . $suffix ][ $breakpoint ] ) && '' !== $scale[ 'gScleY' . $suffix ][ $breakpoint ] ) {
					$transforms[] = 'scaleY(' . $scale[ 'gScleY' . $suffix ][ $breakpoint ] . ')';
				}
			}
		}

		// Skew.
		if ( isset( $block_value[ 'gSkew' . $suffix ] ) && ! empty( $block_value[ 'gSkew' . $suffix ]['tpgbReset'] ) ) {
			$skew = $block_value[ 'gSkew' . $suffix ];

			if ( isset( $skew[ 'gSkewX' . $suffix ][ $breakpoint ] ) && '' !== $skew[ 'gSkewX' . $suffix ][ $breakpoint ] ) {
				$transforms[] = 'skewX(' . $skew[ 'gSkewX' . $suffix ][ $breakpoint ] . 'deg)';
			}
			if ( isset( $skew[ 'gSkewY' . $suffix ][ $breakpoint ] ) && '' !== $skew[ 'gSkewY' . $suffix ][ $breakpoint ] ) {
				$transforms[] = 'skewY(' . $skew[ 'gSkewY' . $suffix ][ $breakpoint ] . 'deg)';
			}
		}

		// Flip Horizontal.
		if ( isset( $block_value[ 'gFHori' . $suffix ]['value'] ) && $block_value[ 'gFHori' . $suffix ]['value'] ) {
			$transforms[] = 'scaleX(-1)';
		}

		// Flip Vertical.
		if ( isset( $block_value[ 'gFVert' . $suffix ]['value'] ) && $block_value[ 'gFVert' . $suffix ]['value'] ) {
			$transforms[] = 'scaleY(-1)';
		}

		return ! empty( $transforms ) ? implode( ' ', $transforms ) : '';
	}

	/**
	 * Generate grid styles for a specific breakpoint.
	 *
	 * @since 1.0.0
	 * @param array<string, mixed> $grid_repeater      The settings.
	 * @param string               $content_width The content width.
	 * @param string               $block_id      The block ID.
	 * @param string               $type          The type of grid. Default 'column'.
	 * @param string               $media_size    The media size. Default 'md'.
	 * @return string The grid styles.
	 */
	public function generate_grid_styles( $grid_repeater, $content_width, $block_id, $type = 'column', $media_size = 'md' ) {

		$grid_val    = array(
			'md' => array(),
			'sm' => array(),
			'xs' => array(),
		);
		$grid_styles = '';

		if ( ! empty( $grid_repeater ) ) {
			$child_sele = '';

			if ( ! empty( $content_width ) && 'full' !== $content_width ) {
				$child_sele = ".tpgb-block-{$block_id}.alignwide.tpgb-container-wide.tpgb-grid > .tpgb-cont-in ";
			} else {
				$child_sele = ".tpgb-block-{$block_id}.alignfull.tpgb-container-full.tpgb-grid ";
			}

			foreach ( $grid_repeater as $item ) {
				$property_key = 'column' === $type ? 'gridProperty' : 'gridRowProperty';
				$custom_key   = 'column' === $type ? 'gridWidth' : 'gridHeight';
				$min_key      = 'column' === $type ? 'gridMin' : 'gridRowMin';
				$max_key      = 'column' === $type ? 'gridMax' : 'gridRowMax';

				foreach ( array( 'md', 'sm', 'xs' ) as $size ) {

					if ( isset( $item[ $property_key ][ $size ] ) && 'auto' === $item[ $property_key ][ $size ] ) {

						$grid_val[ $size ][] = 'minmax(1px, auto)';
					}

					if ( isset( $item[ $property_key ][ $size ], $item[ $custom_key ][ $size ], $item[ $custom_key ]['unit'] ) && 'custom' === $item[ $property_key ][ $size ] && '' !== $item[ $custom_key ][ $size ] && '' !== $item[ $custom_key ]['unit'] ) {

						$grid_val[ $size ][] = 'minmax(1px, ' . $item[ $custom_key ][ $size ] . $item[ $custom_key ]['unit'] . ')';
					}

					if ( isset( $item[ $property_key ][ $size ], $item[ $min_key ][ $size ], $item[ $min_key ]['unit'], $item[ $max_key ][ $size ], $item[ $max_key ]['unit'] ) && 'minmax' === $item[ $property_key ][ $size ] && '1px' !== $item[ $min_key ][ $size ] && '' !== $item[ $min_key ]['unit'] && '' !== $item[ $max_key ][ $size ] && '' !== $item[ $max_key ]['unit'] ) {

						$grid_val[ $size ][] = 'minmax(' . $item[ $min_key ][ $size ] . $item[ $min_key ]['unit'] . ', ' . $item[ $max_key ][ $size ] . $item[ $max_key ]['unit'] . ')';
					}
				}
			}

			$grid_template_property = 'column' === $type ? 'grid-template-columns' : 'grid-template-rows';
			if ( ! empty( $grid_val[ $media_size ] ) ) {
				$template_values = implode( ' ', $grid_val[ $media_size ] );
				$grid_styles     = "{$child_sele} { $grid_template_property: $template_values; }";
			}
		}

		return $grid_styles;
	}

	/**
	 * Generate block CSS.
	 *
	 * $key and $index_style are required by the parent signature / filter callback,
	 * even though they are not used in this implementation.
	 *
	 * @param string $settings        Block name.
	 * @param array  $select_data        Block attributes.
	 * @param string $key         Unused — kept for signature compatibility.
	 * @param string $index_style Unused — kept for signature compatibility.
	 */
	public function conditions_styling( $settings = array(), $select_data = array(), $key = '', $index_style = '' ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed -- parameters required for signature compatibility; intentional
		$check = true;

			$select_data = (array) $select_data;
		if ( isset( $select_data['condition'] ) && ! empty( $select_data['condition'] ) ) {

			foreach ( $select_data['condition'] as $index => $data ) {

				$previous_cond = $check;
				$obj_data      = (array) $data;

				if ( isset( $settings[ $obj_data['key'] ] ) ) {

					if ( isset( $obj_data['relation'] ) && '==' === $obj_data['relation'] ) {

						if ( isset( $obj_data['value'] ) && ( 'string' === gettype( $obj_data['value'] ) || 'number' === gettype( $obj_data['value'] ) || 'boolean' === gettype( $obj_data['value'] ) || 'integer' === gettype( $obj_data['value'] ) ) ) {

							if ( $settings[ $obj_data['key'] ]['value'] === $obj_data['value'] ) {
								$check = true;
							} elseif ( 'object' === gettype( $settings[ $obj_data['key'] ]['value'] ) || 'array' === gettype( $settings[ $obj_data['key'] ]['value'] ) ) {
								$select = false;

								if ( ( isset( $settings[ $obj_data['key'] ]['value']['md'] ) && $settings[ $obj_data['key'] ]['value']['md'] === $obj_data['value'] ) || ( isset( $settings[ $obj_data['key'] ]['value']['sm'] ) && $settings[ $obj_data['key'] ]['value']['sm'] === $obj_data['value'] ) || ( isset( $settings[ $obj_data['key'] ]['value']['xs'] ) && $settings[ $obj_data['key'] ]['value']['xs'] === $obj_data['value'] ) ) {
									$select = true;
								}

								if ( $select ) {
									$check = true;
								} else {
									$check = false;
								}
							} else {
								$check = false;
							}
						} else {
							$select = false;
							if ( ! empty( $obj_data['value'] ) ) {
								foreach ( $obj_data['value'] as $arr_data ) {
									$obj_key   = $obj_data['key'];
									$obj_value = $settings[ $obj_data['key'] ]['value'];

									if ( isset( $obj_value ) && $obj_value === $arr_data ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf, Squiz.ControlStructures.ControlSignature.SpaceAfterCloseParenthesis
										$select = true;
									} elseif ( ( isset( $obj_value['md'] ) && $obj_value['md'] === $arr_data ) || ( isset( $obj_value['sm'] ) && $obj_value['sm'] === $arr_data ) || ( isset( $obj_value['xs'] ) && $obj_value['xs'] === $arr_data ) ) {
										$select = true;
									}
								}
							}

							if ( $select ) {
								$check = true;
							} else {
								$check = false;
							}
						}
					} elseif ( isset( $obj_data['relation'] ) && '!=' === $obj_data['relation'] ) {
						if ( isset( $obj_data['value'] ) && ( 'string' === gettype( $obj_data['value'] ) || 'number' === gettype( $obj_data['value'] ) || 'boolean' === gettype( $obj_data['value'] ) ) ) {
							$attr_key = explode( '.', $obj_data['key'] );
							if ( count( $attr_key ) > 1 ) {
								if ( is_array( $settings[ $attr_key[0] ]['value'] ) ) {
									$attr_key_value = $settings[ $attr_key[0] ]['value'][ $attr_key[1] ];
								} else {
									$attr_key_value = $settings[ $attr_key[0] ]['value'];
								}
							} elseif ( isset( $settings[ $attr_key[0] ]['value'] ) ) {
								$attr_key_value = $settings[ $attr_key[0] ]['value'];
							} else {
								$attr_key_value = $attr_key[0];
							}

							if ( $attr_key_value !== $obj_data['value'] ) {
								$check = true;
							} else {
								$check = false;
							}
						} else {
							$_select = false;
							foreach ( $obj_data['value'] as $arr_data ) {
								if ( isset( $settings[ $obj_data['key'] ]['value'] ) && $settings[ $obj_data['key'] ]['value'] !== $arr_data ) {
									$_select = true;
								}
							}
							if ( $_select ) {
								$check = true;
							} else {
								$check = false;
							}
						}
					}
				}
				if ( false === $previous_cond ) {
					$check = false;
				}
			}
		}
		return $check;
	}

	/**
	 * Object Field
	 *
	 * @since 1.0.0
	 * @param array<string, mixed> $data The data.
	 * @return array<string, mixed> The output.
	 */
	public function tp_object_field( $data ) {
		$data = (array) $data;
		if ( isset( $data['openTypography'] ) && $data['openTypography'] ) {
			return array(
				'data'   => $this->cssTypography( $data ),
				'action' => 'append',
			); // Typography.
		} elseif ( isset( $data['openBorder'] ) && $data['openBorder'] ) {
			return array(
				'data'   => $this->cssBorder( $data ),
				'action' => 'append',
			); // Border.
		} elseif ( isset( $data['url'] ) && ! empty( $data['url'] ) && isset( $data['id'] ) && ! empty( $data['id'] ) ) {
			return array(
				'data'   => $this->cssMedia( $data ),
				'action' => 'replace',
			); // Media Image.
		} elseif ( isset( $data['openShadow'] ) && $data['openShadow'] ) {
			return array(
				'data'   => $this->cssBoxShadow( $data ),
				'action' => 'append',
			); // BoxShadow.
		} elseif ( isset( $data['openBg'] ) && $data['openBg'] ) {
			return array(
				'data'   => $this->cssBackground( $data ),
				'action' => 'append',
			); // Background Color/Image/Video.
		} elseif ( isset( $data['openTransform'] ) && $data['openTransform'] ) {
			return array(
				'data'   => $this->cssTransform( $data ),
				'action' => 'append',
			); // Transform.
		} elseif ( isset( $data['openFilter'] ) && $data['openFilter'] ) {
			if ( isset( $data['isbackdrop'] ) && $data['isbackdrop'] ) {
				return array(
					'data'   => $this->cssFilter( $data, true ),
					'action' => 'append',
				); // cssFilter Ex. blur,contrast....
			} else {
				return array(
					'data'   => $this->cssFilter( $data, false ),
					'action' => 'append',
				); // cssFilter Ex. blur,contrast....
			}
		} elseif ( isset( $data['top'] ) || isset( $data['left'] ) || isset( $data['right'] ) || isset( $data['bottom'] ) ) {
			return array(
				'data'   => $this->cssDimension( $data ),
				'action' => 'replace',
			); // Css Dimension Ex.Padding/Margin....
		} else {
			return array(
				'data'   => '',
				'action' => 'append',
			);
		}
	}

	/**
	 * Replace Dimension
	 *
	 * @since 1.0.0
	 * @param string $selector The selector.
	 * @param string $value The value.
	 * @return string The selector.
	 */
	public function replace_dimension( $selector, $value ) {
		if ( gettype( $value ) === 'string' && ! empty( $value ) && strpos( $value, ' ' ) !== false ) {
				$dim_value = explode( ' ', $value );

			if ( strpos( $selector, 'padding-left' ) !== false ) {
				if ( '0' == $dim_value[3] ) { // phpcs:ignore Universal.Operators.StrictComparisons.LooseEqual -- dimension value checked against string '0' for readability; loose comparison intentional
					$dim_value[3] = '0px';
				}
				$selector = str_replace( '{{LEFT}}' . $value, $dim_value[3], $selector );
			}
			if ( strpos( $selector, 'padding-right' ) !== false ) {
				if ( '0' == $dim_value[1] ) { // phpcs:ignore Universal.Operators.StrictComparisons.LooseEqual -- dimension value checked against string '0' for readability; loose comparison intentional
					$dim_value[1] = '0px';
				}
				$selector = str_replace( '{{RIGHT}}' . $value, $dim_value[1], $selector );
			}
			if ( strpos( $selector, 'padding-top' ) !== false ) {
				$selector = str_replace( '{{TOP}}' . $value, $dim_value[0], $selector );
			}
			if ( strpos( $selector, 'padding-bottom' ) !== false ) {
				$selector = str_replace( '{{BOTTOM}}' . $value, $dim_value[2], $selector );
			}
		}
		return $selector;
	}


	/**
	 * Single Field Check
	 *
	 * @since 1.0.0
	 * @param string $style The style.
	 * @param string $block_i_d The block id.
	 * @param string $key The key.
	 * @param string $value The value.
	 * @param string $category The category.
	 * @param string $repeater The repeater.
	 * @param string $keyindex The key index.
	 * @return array<string, mixed> The output.
	 */
	public function single_field( $style, $block_i_d, $key, $value, $category, $repeater = '', $keyindex = '' ) {
		$value = ( gettype( $value ) === 'undefined' ? 'undefined' : gettype( $value ) ) != 'object' ? $value : $this->tp_object_field( $value )['data']; // phpcs:ignore Universal.Operators.StrictComparisons.LooseNotEqual -- type comparison checked against string 'object' for readability; loose comparison intentional
		if ( gettype( $style ) === 'string' ) {
			if ( ! empty( $style ) ) {
				if ( $value != '' ) { // phpcs:ignore WordPress.PHP.YodaConditions.NotYoda, Universal.Operators.StrictComparisons.LooseNotEqual -- repeater checked against empty string for readability

					$warp_data = $this->replaceWarp( $style, $block_i_d, $category );
					if ( $repeater != '' ) { // phpcs:ignore WordPress.PHP.YodaConditions.NotYoda, Universal.Operators.StrictComparisons.LooseNotEqual -- repeater checked against empty string for readability
						$warp_data = $this->replaceWarpItem( $warp_data, $repeater, $keyindex );
					}

					if ( gettype( $value ) === 'boolean' ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf, Squiz.ControlStructures.ControlSignature.SpaceAfterCloseParenthesis
						return array( $warp_data );
					} elseif ( strpos( $warp_data, '{{' ) == -1 && strpos( $warp_data, '{' ) < 0 ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf, Universal.Operators.StrictComparisons.LooseEqual -- string comparison checked against integer '-1' for readability; loose comparison intentional
						return array( $warp_data . $value );
					} else {
						return array( $this->replace_dimension( $this->replaceData( $warp_data, '{{' . $key . '}}', $value ), $value ) );
					}
				} else {
					return array();
				}
			} else {
				// Custom CSS Field.
				return array( $this->replaceWarp( $value, $block_i_d, $category ) );
			}
		} else {
			$output = array();
			if ( ! empty( $style ) ) {
				foreach ( $style as $sel ) {
					array_push( $output, $this->replaceData( $this->replaceWarp( $sel, $block_i_d, $category ), '{{' . $key . '}}', $value ) );
				}
			}
			return $output;
		}
	}

	/**
	 * Replace Wrap
	 *
	 * @since 1.0.0
	 * @param string $selector The selector. Default ''.
	 * @param string $i_d The block id.
	 * @param string $category The category.
	 * @return string The selector.
	 */
	public function replaceWarp( $selector, $i_d, $category ) {  // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid,WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
		$selector = str_replace( '{{PLUS_WRAP}}', '.' . ( $category ? $category : 'tpgb' ) . '-block-' . $i_d, $selector );
		return str_replace( '{{PLUS_BLOCK}}', '.' . ( $category ? $category : 'tpgb' ) . '-wrap-' . $i_d, $selector );
	}

	/**
	 * Replace Data
	 *
	 * @since 1.0.0
	 * @param string $selector The selector. Default ''.
	 * @param string $key The key.
	 * @param string $value The value.
	 * @return string The selector.
	 */
	public function replaceData( $selector, $key, $value ) {  // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid,WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
		return str_replace( $key, $value, $selector );
	}

	/**
	 * Replace Repeater Wrap Item
	 *
	 * @since 1.0.0
	 * @param string $selector The selector. Default ''.
	 * @param string $i_d The block id.
	 * @param string $index The index. Default ''.
	 * @return string The selector.
	 */
	public function replaceWarpItem( $selector, $i_d, $index = '' ) {  // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid,WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
		if ( '' !== $index ) {
			$selector = str_replace( '{{TP_INDEX}}', (int) $index + 1, $selector );
		}
		return str_replace( '{{TP_REPEAT_ID}}', '.tp-repeater-item-' . $i_d, $selector );
	}

	/**
	 * Object Replace Data
	 *
	 * @since 1.0.0
	 * @param string $warp The warp.
	 * @param string $value The value.
	 * @return string The selector.
	 */
	public function objectReplace( $warp, $value ) {  // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid,WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
		$output = '';
		foreach ( $value as $sel ) {
			$output .= $sel . ';';
		}
		return $warp . '{' . $output . '}';
	}

	/**
	 * Replace Unit Without Digits
	 *
	 * @since 1.0.0
	 * @param string $value The value.
	 * @return string The selector.
	 */
	public function replaceUnitWithoutDigits( $value ) {  // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid,WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
		$output = preg_replace( '/(?<!\d)(px|%|em)/', '0', $value );
		return $output;
	}

	/**
	 * Css Dimension Style
	 *
	 * @since 1.0.0
	 * @param array<string, mixed> $val The value.
	 * @return string The selector.
	 */
	public function cssDimension( $val ) {  // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid,WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
		$unit   = ( isset( $val['unit'] ) && ! empty( $val['unit'] ) ) ? $val['unit'] : 'px';
		$output = '';
		if ( ( isset( $val['top'] ) && '' !== $val['top'] ) || ( isset( $val['right'] ) && '' !== $val['right'] ) || ( isset( $val['bottom'] ) && '' !== $val['bottom'] ) || ( isset( $val['left'] ) && '' !== $val['left'] ) ) {
			$output .= ( ! empty( $val['top'] ) ? $val['top'] . $unit : 0 ) . ' ' . ( isset( $val['right'] ) ? $val['right'] . $unit : ( ( isset( $val['autoset'] ) && true === $val['autoset'] ) ? 'auto' : 0 ) ) . ' ' . ( ! empty( $val['bottom'] ) ? $val['bottom'] . $unit : 0 ) . ' ' . ( isset( $val['left'] ) ? $val['left'] . $unit : ( ( isset( $val['autoset'] ) && true === $val['autoset'] ) ? 'auto' : 0 ) );
		}
		return $this->replaceUnitWithoutDigits( $output );
	}

	/**
	 * Css Border Style
	 *
	 * @since 1.0.0
	 * @param array<string, mixed> $val The value.
	 * @return string The selector.
	 */
	public function cssBorder( $val ) {  // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid,WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
		if ( ! empty( $val ) ) {
			if ( ! empty( $val['openBorder'] ) && isset( $val['globalBorder'] ) && ! empty( $val['globalBorder'] ) ) {
				$g_border   = $val['globalBorder'];
				$global_css = 'border-style:var(--tpgb-BRT' . $g_border . ');border-width:var(--tpgb-BRW' . $g_border . ');';
				if ( isset( $val['disableWidthColor'] ) && ! empty( $val['disableWidthColor'] ) ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf, Squiz.ControlStructures.ControlSignature.SpaceAfterCloseParenthesis
				} elseif ( isset( $val['color'] ) && ! empty( $val['color'] ) ) {
					$global_css .= 'border-color: ' . ( $val['color'] ? $val['color'] : '#000' ) . ';';
				} else {
					$global_css .= 'border-color:var(--tpgb-BRC' . $g_border . ');';
				}
				return array(
					'md' => $global_css,
					'sm' => array(),
					'xs' => array(),
				);
			}

			$val['type']  = ( isset( $val['type'] ) && ! empty( $val['type'] ) ) ? $val['type'] : 'solid';
			$val['color'] = ( isset( $val['color'] ) && ! empty( $val['color'] ) ) ? $val['color'] : '#000';
			$val['width'] = ( isset( $val['width'] ) && ! empty( $val['width'] ) ) ? $val['width'] : array();

			// FIX: json_decode produces stdClass objects; normalise to array before the type check.
			if ( is_object( $val['width'] ) ) {
				$val['width'] = wp_json_decode( wp_json_encode( $val['width'] ), true );
			}

			$default_css = 'border-style: ' . $val['type'] . ';';
			if ( ! isset( $val['disableWidthColor'] ) || empty( $val['disableWidthColor'] ) ) {
				$default_css .= 'border-color: ' . $val['color'] . ';';
			}

			if ( gettype( $val['width'] ) === 'array' ) {
				$data = array(
					'md' => array(),
					'sm' => array(),
					'xs' => array(),
				);
				$data = $this->res_push( $this->customDevice( $val['width'], 'border-width:{{key}};' ), $data );
				array_push( $data['md'], $default_css );
				return array(
					'md' => $data['md'],
					'sm' => $data['sm'],
					'xs' => $data['xs'],
				);
			}

			// Fallback: width was empty or a scalar — output just style + color.
			return array(
				'md' => $default_css,
				'sm' => array(),
				'xs' => array(),
			);
		}
	}

	/**
	 * Css Media Image Style
	 *
	 * @since 1.0.0
	 * @param array<string, mixed> $val The value.
	 * @return string The selector.
	 */
	public function cssMedia( $val ) {  // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid,WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
		if ( ! empty( $val ) ) {
			$imgurl = '';
			if ( ! empty( $val['id'] ) ) {
				$imgurl = wp_get_attachment_image_url( $val['id'], 'full' );
			}
			if ( empty( $imgurl ) && isset( $val['url'] ) && ! empty( $val['url'] ) ) {
				$imgurl = $val['url'];
			}
			if ( ! empty( $imgurl ) ) {
				return 'url(' . esc_url( $imgurl ) . ')';
			} else {
				return;
			}
		} else {
			return;
		}
	}

	/**
	 * Css BoxShadow Style
	 *
	 * @since 1.0.0
	 * @param array<string, mixed> $val The value.
	 * @return string The selector.
	 */
	public function cssBoxShadow( $val ) {  // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid,WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
		$css = '';
		if ( ! empty( $val['openShadow'] ) && isset( $val['globalShadow'] ) && ! empty( $val['globalShadow'] ) ) {
			$g_shadow = $val['globalShadow'];
			if ( isset( $val['typeShadow'] ) && 'text-shadow' === $val['typeShadow'] ) {
				return 'text-shadow:var(--tpgb-BS' . $g_shadow . ');';
			} elseif ( isset( $val['typeShadow'] ) && 'drop-shadow' === $val['typeShadow'] ) {
				return 'filter: drop-shadow(var(--tpgb-BS' . $g_shadow . '));';
			} else {
				return 'box-shadow:var(--tpgb-BS' . $g_shadow . ');';
			}
		}
		if ( ! empty( $val['openShadow'] ) && isset( $val['typeShadow'] ) && 'text-shadow' === $val['typeShadow'] ) {
			return $val['typeShadow'] . ':' . $val['horizontal'] . 'px ' . $val['vertical'] . 'px ' . $val['blur'] . 'px ' . $val['color'] . ';';
		} elseif ( ! empty( $val['openShadow'] ) && isset( $val['typeShadow'] ) && 'drop-shadow' === $val['typeShadow'] ) {
			return 'filter: drop-shadow(' . $val['horizontal'] . 'px ' . $val['vertical'] . 'px ' . $val['blur'] . 'px ' . $val['color'] . ');';
		} elseif ( ! empty( $val['openShadow'] ) ) {
			return 'box-shadow:' . ( ( isset( $val['inset'] ) && ! empty( $val['inset'] ) ) ? $val['inset'] : '' ) . ' ' . $val['horizontal'] . 'px ' . $val['vertical'] . 'px ' . $val['blur'] . 'px ' . $val['spread'] . 'px ' . $val['color'] . ';';
		} else {
			return;
		}
	}

	/**
	 * CSS Background Style
	 *
	 * @param mixed $val The val.
	 */
	public function cssBackground( $val ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid,WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
		$background = '';
		if ( isset( $val['bgType'] ) && '' === $val['bgType'] ) {
			$val['bgDefaultColor'] = '';
		}
		if ( isset( $val['bgType'] ) && '' !== $val['bgType'] ) {
			$bg_type               = isset( $val['bgType'] ) ? $val['bgType'] : '';
			$bg_image              = isset( $val['bgImage'] ) ? $val['bgImage'] : '';
			$bgimg_position        = isset( $val['bgimgPosition'] ) ? $val['bgimgPosition'] : '';
			$bgimg_attachment      = isset( $val['bgimgAttachment'] ) ? $val['bgimgAttachment'] : '';
			$bgimg_repeat          = isset( $val['bgimgRepeat'] ) ? $val['bgimgRepeat'] : '';
			$bgimg_size            = isset( $val['bgimgSize'] ) ? $val['bgimgSize'] : '';
			$bg_default_color      = isset( $val['bgDefaultColor'] ) ? $val['bgDefaultColor'] : '';
			$bg_gradient           = isset( $val['bgGradient'] ) ? $val['bgGradient'] : '';
			$bgimg_position_tablet = isset( $val['bgimgPositionTablet'] ) ? $val['bgimgPositionTablet'] : '';
			$bgimg_position_mobile = isset( $val['bgimgPositionMobile'] ) ? $val['bgimgPositionMobile'] : '';
			$bgimg_repeat_tablet   = isset( $val['bgimgRepeatTablet'] ) ? $val['bgimgRepeatTablet'] : '';
			$bgimg_repeat_mobile   = isset( $val['bgimgRepeatMobile'] ) ? $val['bgimgRepeatMobile'] : '';
			$bgimg_size_tablet     = isset( $val['bgimgSizeTablet'] ) ? $val['bgimgSizeTablet'] : '';
			$bgimg_size_mobile     = isset( $val['bgimgSizeMobile'] ) ? $val['bgimgSizeMobile'] : '';
			$position_x            = isset( $val['positionX'] ) ? $val['positionX'] : 0;
			$position_y            = isset( $val['positionY'] ) ? $val['positionY'] : 0;
			$is_custom             = isset( $val['isCustom'] ) ? $val['isCustom'] : 'fpp';
			$focal_point           = isset( $val['focalPoint'] ) ? $val['focalPoint'] : array();
			$background            = $this->split_bg(
				$bg_type,
				$bg_image,
				$bgimg_position,
				$bgimg_attachment,
				$bgimg_repeat,
				$bgimg_size,
				$bg_default_color,
				$bg_gradient,
				$bgimg_position_tablet,
				$bgimg_position_mobile,
				$bgimg_repeat_tablet,
				$bgimg_repeat_mobile,
				$bgimg_size_tablet,
				$bgimg_size_mobile,
				$is_custom,
				array(
					'x' => 'fpp' === $is_custom && isset( $focal_point['x'] ) ? $focal_point['x'] : ( 'range' === $is_custom ? $position_x : '' ),
					'y' => 'fpp' === $is_custom && isset( $focal_point['y'] ) ? $focal_point['y'] : ( 'range' === $is_custom ? $position_y : '' ),
				)
			);

			if ( ! empty( $background ) ) {
				return $background;
			}
			return array();
		} else {
			return '';
		}
	}

	/** // phpcs:ignore Squiz.Commenting.FunctionComment, Generic.Commenting.DocComment.ShortNotCapital,Generic.Commenting.DocComment.LongNotCapital,Generic.Commenting.DocComment.MissingShort
	 * Css Background Style
	 *
	 * @param mixed $type The type.
	 * @param array $image The image.
	 */
	public function split_bg( $type, $image = array(), $img_position = '', $img_attachment = '', $img_repeat = '', $img_size = '', $default_color = '', $bg_gradient = '', $bgimg_position_tablet = '', $bgimg_position_mobile = '', $bgimg_repeat_tablet = '', $bgimg_repeat_mobile = '', $bgimg_size_tablet = '', $bgimg_size_mobile = '', $is_custom = '', $positions = array() ) {

		$dk_selectors = $default_color ? 'background-color:' . $default_color . ';' : '';

		$tb_selectors = '';
		$mb_selectors = '';

		if ( 'image' === $type ) {
			$dk_selectors .= ( ( ! empty( $image ) && isset( $image['url'] ) ) ? 'background-image: url(' . $image['url'] . ');' : '' );

			$dk_selectors .= is_array( $positions ) && ( 'fpp' === $is_custom ? isset( $positions['x'], $positions['y'] ) && '' !== $positions['x'] && '' !== $positions['y'] : ( 'custom' === $img_position || empty( $img_position ) ) && 'range' === $is_custom && isset( $positions['x']['md'], $positions['y']['md'] ) && '' !== $positions['x']['md'] && '' !== $positions['y']['md'] ) ? 'background-position: ' . ( 'fpp' === $is_custom ? $positions['x'] * 100 . '% ' . $positions['y'] * 100 : $positions['x']['md'] . '% ' . $positions['y']['md'] ) . '%;' : ( ! empty( $img_position ) ? 'background-position: ' . $img_position . ';' : '' );

			$tb_selectors . is_array( $positions ) && ( 'range' === $is_custom && isset( $positions['x']['sm'], $positions['y']['sm'] ) && '' !== $positions['x']['sm'] && '' !== $positions['y']['sm'] ) ? 'background-position: ' . $positions['x']['sm'] . '% ' . $positions['y']['sm'] . '%;' : ( ! empty( $bgimg_position_tablet ) ? 'background-position: ' . $bgimg_position_tablet . ';' : '' );

			$mb_selectors .= is_array( $positions ) && ( 'range' === $is_custom && isset( $positions['x']['xs'], $positions['y']['xs'] ) && '' !== $positions['x']['xs'] && '' !== $positions['y']['xs'] ) ? 'background-position: ' . $positions['x']['xs'] . '% ' . $positions['y']['xs'] . '%;' : ( ! empty( $bgimg_position_mobile ) ? 'background-position: ' . $bgimg_position_mobile . ';' : '' );

			$dk_selectors .= ( ! empty( $img_attachment ) ? 'background-attachment: ' . $img_attachment . ';' : '' );
			$dk_selectors .= ( ! empty( $img_repeat ) ? 'background-repeat: ' . $img_repeat . ';' : '' );

			$tb_selectors .= ( ! empty( $bgimg_repeat_tablet ) ? 'background-repeat: ' . $bgimg_repeat_tablet . ';' : '' );
			$mb_selectors .= ( ! empty( $bgimg_repeat_mobile ) ? 'background-repeat:' . $bgimg_repeat_mobile . ';' : '' );

			$dk_selectors .= ( ! empty( $img_size ) ? 'background-size: ' . $img_size : '' );
			$tb_selectors .= ( ! empty( $bgimg_size_tablet ) ? 'background-size: ' . $bgimg_size_tablet . ';' : '' );
			$mb_selectors .= ( ! empty( $bgimg_size_mobile ) ? 'background-size: ' . $bgimg_size_mobile . ';' : '' );
		} elseif ( 'gradient' === $type ) {
			if ( ! empty( $bg_gradient ) && '' !== $bg_gradient && ! is_array( $bg_gradient ) ) {
				$dk_selectors .= 'background-image : ' . $bg_gradient . ';';
			}
		}

		$dk_res        = array();
		$dk_res['md']  = $dk_selectors;
		$tab_res       = array();
		$tab_res['sm'] = $tb_selectors;
		$mob_res       = array();
		$mob_res['xs'] = $mb_selectors;

		$all_selectors = array_merge( $dk_res, $tab_res, $mob_res );

		return $all_selectors;
	}

	/**
	 * Css Typography Style
	 *
	 * @since 1.0.0
	 * @param array<string, mixed> $val The value.
	 * @return string The typography.
	 */
	public function cssTypography( $val ) {  // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid,WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
		$cssfont = '';

		if ( isset( $val['globalTypo'] ) ) {
			$css  = '';
			$typo = $val['globalTypo'];
			$css .= 'font-family:var(--tpgb-T' . $typo . '-font-family);';
			$css .= 'font-weight:var(--tpgb-T' . $typo . '-font-weight);';
			$css .= 'font-style:var(--tpgb-T' . $typo . '-font-style);';
			$css .= 'font-size:var(--tpgb-T' . $typo . '-font-size);';
			$css .= 'line-height:var(--tpgb-T' . $typo . '-line-height);';
			$css .= 'letter-spacing:var(--tpgb-T' . $typo . '-letter-spacing);';
			$css .= 'text-transform:var(--tpgb-T' . $typo . '-text-transform);';
			$css .= 'text-decoration:var(--tpgb-T' . $typo . '-text-decoration);';
			return $css;
		}
		if ( isset( $val['fontFamily'] ) && '' !== $val['fontFamily'] && isset( $val['fontFamily']['family'] ) && '' !== $val['fontFamily']['family'] && $this->google_font_load() && ( ! isset( $val['fontFamily']['customFont'] ) || '' === $val['fontFamily']['customFont'] ) ) {
			if ( ! in_array( $val['fontFamily']['family'], array( 'Arial', 'Tahoma', 'Verdana', 'Helvetica', 'Times New Roman', 'Trebuchet MS', 'Georgia' ) ) ) { // phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict -- family name compared against known string list; strict mode intentional
				$cssfont = '@import url(https://fonts.googleapis.com/css?family=' . preg_replace( '/\s/i', '+', $val['fontFamily']['family'] ) . ':' . ( isset( $val['fontFamily']['fontWeight'] ) ? $val['fontFamily']['fontWeight'] : 400 ) . '&display=swap);';
			}
		}
		$data = array(
			'md' => array(),
			'sm' => array(),
			'xs' => array(),
		);
		if ( isset( $val['size'] ) && '' !== $val['size'] ) {
			$data = $this->res_push( $this->res_device( $val['size'], 'font-size:{{key}}' ), $data );
		}
		if ( isset( $val['height'] ) && '' !== $val['height'] ) {
			$data = $this->res_push( $this->res_device( $val['height'], 'line-height:{{key}}' ), $data );
		}
		if ( isset( $val['spacing'] ) && '' !== $val['spacing'] ) {
			$data = $this->res_push( $this->res_device( $val['spacing'], 'letter-spacing:{{key}}' ), $data );
		}
		$css = '';
		if ( isset( $val['fontFamily'] ) && '' !== $val['fontFamily'] ) {
			if ( isset( $val['fontFamily']['family'] ) && '' !== $val['fontFamily']['family'] && isset( $val['fontFamily']['customFont'] ) && '' !== $val['fontFamily']['customFont'] ) {
				$css .= ( $val['fontFamily']['family'] ) ? "font-family:'" . $val['fontFamily']['family'] . "',Sans-serif;" : '';
			} elseif ( isset( $val['fontFamily']['family'] ) && '' !== $val['fontFamily']['family'] ) {
				$css .= ( $val['fontFamily']['family'] ) ? "font-family:'" . $val['fontFamily']['family'] . ( isset( $val['fontFamily']['type'] ) && $val['fontFamily']['type'] ? "'," . $val['fontFamily']['type'] : "'" ) . ';' : '';
			}
			if ( isset( $val['fontFamily']['fontWeight'] ) ) {
				( 'string' === gettype( $val['fontFamily']['fontWeight'] ) && preg_match( '/[a-z]/i', $val['fontFamily']['fontWeight'] ) ) ? // phpcs:ignore Universal.Operators.StrictComparisons.LooseEqual -- type comparison checked against string 'string' for readability; loose comparison intentional
				$css .= 'font-weight:' . substr( $val['fontFamily']['fontWeight'], 0, -1 ) . ';font-style:italic;'
				:
				$css .= ( isset( $val['fontFamily']['fontWeight'] ) && ! empty( $val['fontFamily']['fontWeight'] ) ) ? 'font-weight:' . $val['fontFamily']['fontWeight'] . ';' : '';
			}
		}
		if ( isset( $val['fontStyle'] ) && '' !== $val['fontStyle'] ) {
			$css .= ( $val['fontStyle'] ) ? 'font-style:' . $val['fontStyle'] . ';' : '';
		}
		if ( isset( $val['textTransform'] ) && ! empty( $val['textTransform'] ) ) {
			$css .= ( $val['textTransform'] ) ? 'text-transform:' . $val['textTransform'] . ';' : '';
		}
		if ( isset( $val['textDecoration'] ) && '' !== $val['textDecoration'] ) {
			$css .= ( $val['textDecoration'] ) ? 'text-decoration:' . $val['textDecoration'] . ';' : '';
		}
		return array(
			'md'     => $data['md'],
			'sm'     => $data['sm'],
			'xs'     => $data['xs'],
			'simple' => $css,
			'font'   => $cssfont,
		);
	}

	/**
	 * Generate CSS forfilter effects.
	 *
	 * @since 1.0.0
	 * @param array<string, mixed> $val        The value.
	 * @param bool                 $isbackdrop Whether backdrop filter is applied.
	 * @return string              The filter CSS.
	 */
	public function cssFilter( $val, $isbackdrop = false ) {  // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid,WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
		if ( ! empty( $val['openFilter'] ) ) {
			$filter = '';
			if ( isset( $val['blur'] ) && '' !== $val['blur'] ) {
				$filter .= ' blur(' . $val['blur'] . 'px)';
			}
			if ( isset( $val['brightness'] ) && '' !== $val['brightness'] ) {
				$filter .= ' brightness(' . $val['brightness'] . '%)';
			}
			if ( isset( $val['contrast'] ) && '' !== $val['contrast'] ) {
				$filter .= ' contrast(' . $val['contrast'] . '%)';
			}
			if ( isset( $val['saturate'] ) && '' !== $val['saturate'] ) {
				$filter .= ' saturate(' . $val['saturate'] . '%)';
			}
			if ( isset( $val['hue'] ) && '' !== $val['hue'] ) {
				$filter .= ' hue-rotate(' . $val['hue'] . 'deg)';
			}
			if ( '' !== $filter ) {
				if ( $isbackdrop ) {
					$filter = 'backdrop-filter : ' . $filter . ';-webkit-backdrop-filter : ' . $filter . ';';
				} else {
					$filter = 'filter : ' . $filter . ';';
				}
			}
			return $filter;
		} else {
			return;
		}
	}

	/**
	 * Css Transform Style
	 *
	 * @since 1.0.0
	 * @param array<string, mixed> $val The value.
	 * @return string The transform.
	 */
	public function cssTransform( $val ) {  // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid,WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
		if ( ! empty( $val['openTransform'] ) ) {

			$data = array(
				'md' => array(),
				'sm' => array(),
				'xs' => array(),
			);
			$data = $this->res_push( $this->res_device( $val['perspective'], 'perspective({{key}})' ), $data );
			$data = $this->res_push( $this->res_device( $val['translateX'], 'translateX({{key}})' ), $data );
			$data = $this->res_push( $this->res_device( $val['translateY'], 'translateY({{key}})' ), $data );
			$data = $this->res_push( $this->res_device( $val['translateZ'], 'translateZ({{key}})' ), $data );
			$data = $this->res_push( $this->res_device( $val['scaleX'], 'scaleX({{key}})' ), $data );
			$data = $this->res_push( $this->res_device( $val['scaleY'], 'scaleY({{key}})' ), $data );
			$data = $this->res_push( $this->res_device( $val['scaleZ'], 'scaleZ({{key}})' ), $data );
			$data = $this->res_push( $this->res_device( $val['rotateX'], 'rotateX({{key}}deg)' ), $data );
			$data = $this->res_push( $this->res_device( $val['rotateY'], 'rotateY({{key}}deg)' ), $data );
			$data = $this->res_push( $this->res_device( $val['rotateZ'], 'rotateZ({{key}}deg)' ), $data );
			$data = $this->res_push( $this->res_device( $val['skewX'], 'skewX({{key}}deg)' ), $data );
			$data = $this->res_push( $this->res_device( $val['skewY'], 'skewY({{key}}deg)' ), $data );
			if ( isset( $data['md'] ) && ! empty( $data['md'] ) ) {
				$data['md'] = array( 'transform : ' . join( ' ', $data['md'] ) . ';' );
			}
			if ( isset( $val['origin'] ) && '' !== $val['origin'] ) {
				$origin_css = ( 'custom' !== $val['origin'] ? 'transform-origin:' . $val['origin'] . ';' : ( ( isset( $val['customOrigin'] ) && ! empty( $val['customOrigin'] ) ) ? 'transform-origin:' . $val['customOrigin'] . ';' : '' ) );
				array_push( $data['md'], $origin_css );
			}
			if ( isset( $val['Transition'] ) && '' !== $val['Transition'] ) {
				$transition_eff = ( 'custom' !== $val['Transition'] ? $val['Transition'] : ( ( isset( $val['customTransition'] ) && '' !== $val['customTransition'] ) ? $val['customTransition'] : 'linear' ) );
				$transition_dur = ( '' !== $val['TraDuration'] ) ? $val['TraDuration'] . 's' : '0.3s';
				$transition_css = ( 'none' !== $val['Transition'] ) ? 'transition : transform ' . $transition_dur . ' ' . $transition_eff : '';
				array_push( $data['md'], $transition_css );
			}
			if ( isset( $data['sm'] ) ) {
				$data['sm'] = 'transform : ' . join( ' ', $data['sm'] );
			}
			if ( isset( $data['xs'] ) ) {
				$data['xs'] = 'transform : ' . join( ' ', $data['xs'] );
			}

			return array(
				'md' => $data['md'],
				'sm' => $data['sm'],
				'xs' => $data['xs'],
			);
		} else {
			return;
		}
	}

	/**
	 * Custom Device Set
	 *
	 * @since 1.0.0
	 * @param array<string, mixed> $val      The value.
	 * @param string               $selector The selector.
	 * @return array<string, mixed> The data.
	 */
	public function customDevice( $val, $selector ) {  // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid,WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
		$data = array();

		if ( $val && isset( $val['md'] ) ) {
			if ( gettype( $val['md'] ) === 'object' || gettype( $val['md'] ) === 'array' ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf, Squiz.ControlStructures.ControlSignature.SpaceAfterCloseParenthesis
				$val_md               = is_array( $val['md'] ) ? '' : $val['md'];
				$selector_replace_spl = explode( ':', str_replace( '{{key}}', $val_md, $selector ) );
				/* $selectorReplaceSpl2 = array_slice($selectorReplaceSpl, 2); phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf, Squiz.ControlStructures.ControlSignature.SpaceAfterCloseParenthesis */
				$css_syntax = $selector_replace_spl[0];
				$top        = isset( $val['md']['top'] ) ? $val['md']['top'] : '';
				$right      = isset( $val['md']['right'] ) ? $val['md']['right'] : '';
				$bottom     = isset( $val['md']['bottom'] ) ? $val['md']['bottom'] : '';
				$left       = isset( $val['md']['left'] ) ? $val['md']['left'] : '';
				if ( '' !== $top || '' !== $right || '' !== $bottom || '' !== $left ) {
					$data['md'] = $css_syntax . ':' . ( $top ? $top : '0' ) . $val['unit'] . ' ' . ( $right ? $right : '0' ) . $val['unit'] . ' ' . ( $bottom ? $bottom : '0' ) . $val['unit'] . ' ' . ( $left ? $left : '0' ) . $val['unit'];
				}
			}
		}
		if ( $val && isset( $val['sm'] ) ) {
			if ( gettype( $val['sm'] ) === 'object' || gettype( $val['sm'] ) === 'array' ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf, Squiz.ControlStructures.ControlSignature.SpaceAfterCloseParenthesis
				$val_sm                = is_array( $val['sm'] ) ? '' : $val['sm'];
				$selector_replace_spl3 = explode( ':', str_replace( '{{key}}', $val_sm, $selector ) );
				/* $selector$replace$spl4 = _slicedToArray(_selector$replace$spl3, 2), phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf, Squiz.ControlStructures.ControlSignature.SpaceAfterCloseParenthesis */
				$css_syntax = $selector_replace_spl3[0];
				$top        = isset( $val['sm']['top'] ) ? $val['sm']['top'] : '';
				$right      = isset( $val['sm']['right'] ) ? $val['sm']['right'] : '';
				$bottom     = isset( $val['sm']['bottom'] ) ? $val['sm']['bottom'] : '';
				$left       = isset( $val['sm']['left'] ) ? $val['sm']['left'] : '';
				if ( '' !== $top || '' !== $right || '' !== $bottom || '' !== $left ) {
					$data['sm'] = $css_syntax . ':' . ( $top ? $top : '0' ) . $val['unit'] . ' ' . ( $right ? $right : '0' ) . $val['unit'] . ' ' . ( $bottom ? $bottom : '0' ) . $val['unit'] . ' ' . ( $left ? $left : '0' ) . $val['unit'];
				}
			}
		}
		if ( $val && isset( $val['xs'] ) ) {
			if ( gettype( $val['xs'] ) === 'object' || gettype( $val['xs'] ) === 'array' ) { // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf, Squiz.ControlStructures.ControlSignature.SpaceAfterCloseParenthesis
				$val_xs                = is_array( $val['xs'] ) ? '' : $val['xs'];
				$selector_replace_spl3 = explode( ':', str_replace( '{{key}}', $val_xs, $selector ) );
				$css_syntax            = $selector_replace_spl3[0];
				$top                   = isset( $val['xs']['top'] ) ? $val['xs']['top'] : '';
				$right                 = isset( $val['xs']['right'] ) ? $val['xs']['right'] : '';
				$bottom                = isset( $val['xs']['bottom'] ) ? $val['xs']['bottom'] : '';
				$left                  = isset( $val['xs']['left'] ) ? $val['xs']['left'] : '';
				if ( '' !== $top || '' !== $right || '' !== $bottom || '' !== $left ) {
					$data['xs'] = $css_syntax . ':' . ( $top ? $top : '0' ) . $val['unit'] . ' ' . ( $right ? $right : '0' ) . $val['unit'] . ' ' . ( $bottom ? $bottom : '0' ) . $val['unit'] . ' ' . ( $left ? $left : '0' ) . $val['unit'];
				}
			}
		}

		return $data;
	}

	/**
	 * Generate CSS replacements for device-specific values.
	 *
	 * Replaces the {{key}} placeholder in the selector with the respective value and unit
	 * for each device (md, sm, xs), supporting unit override per device.
	 *
	 * @since 1.0.0
	 * @param array<string, mixed> $val The value.
	 * @param string               $selector The selector string with a {{key}} placeholder.
	 * @return array<string, mixed> The device-specific CSS strings.
	 */
	public function res_device( $val, $selector ) {  // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid,WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
		$val  = (array) $val;
		$data = array();

		$unit = '';
		if ( ! empty( $val ) && isset( $val['unit'] ) && ! empty( $val['unit'] ) && 'c' !== $val['unit'] ) {
			$unit = $val['unit'];
		}
		if ( $val && isset( $val['md'] ) && '' !== $val['md'] ) {
			$data['md'] = str_replace( '{{key}}', $val['md'] . $unit, $selector );
		}
		if ( $val && isset( $val['sm'] ) && '' !== $val['sm'] ) {
			if ( ! empty( $val ) && isset( $val['unitsm'] ) && ! empty( $val['unitsm'] ) && 'c' !== $val['unitsm'] ) {
				$unit = $val['unitsm'];
			}
			$data['sm'] = str_replace( '{{key}}', $val['sm'] . $unit, $selector );
		}
		if ( $val && isset( $val['xs'] ) && '' !== $val['xs'] ) {
			if ( ! empty( $val ) && isset( $val['unitxs'] ) && ! empty( $val['unitxs'] ) && 'c' !== $val['unitxs'] ) {
				$unit = $val['unitxs'];
			}
			$data['xs'] = str_replace( '{{key}}', $val['xs'] . $unit, $selector );
		}
		return $data;
	}
	/**
	 * Array Device Push
	 *
	 * @since 1.0.0
	 * @param array<string, mixed> $val The value.
	 * @param array<string, mixed> $data The data.
	 * @return array<string, mixed> The data.
	 */
	public function res_push( $val, $data ) { // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid,WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid

		if ( isset( $val['md'] ) ) {
			array_push( $data['md'], $val['md'] );
		}
		if ( isset( $val['sm'] ) ) {
			array_push( $data['sm'], $val['sm'] );
		}
		if ( isset( $val['xs'] ) ) {
			array_push( $data['xs'], $val['xs'] );
		}
		return $data;
	}

	/**
	 * Minify Style Css
	 *
	 * @since 1.0.0
	 * @param string $css The css.
	 * @return string The css.
	 */
	public function minify_css( $css ) {  // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid,WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
		if ( trim( (string) $css ) === '' ) {
			return $css;
		}
		return preg_replace(
			array(
				// Remove comment(s).
				'#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')|\/\*(?!\!)(?>.*?\*\/)|^\s*|\s*$#s',
				// Remove unused white-space(s).
				'#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/))|\s*+;\s*+(})\s*+|\s*+([*$~^|]?+=|[{};,>~]|\s(?![0-9\.])|!important\b)\s*+|([[(:])\s++|\s++([])])|\s++(:)\s*+(?!(?>[^{}"\']++|"(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')*+{)|^\s++|\s++\z|(\s)\s+#si',
				// Replace `0(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)` with `0`.
				'#(?<=[\s:])(0)(cm|ex|in|mm|pc|pt|vh|vw|%)#si',
				// Replace `:0 0 0 0` with `:0`.
				'#:(0\s+0|0\s+0\s+0\s+0)(?=[;\}]|\!important)#i',
				// Replace `background-position:0` with `background-position:0 0`.
				'#(background-position):0(?=[;\}])#si',
				// Replace `0.6` with `.6`, but only when preceded by `:`, `,`, `-` or a white-space.
				'#(?<=[\s:,\-])0+\.(\d+)#s',
				// Minify string value.
				'#(\/\*(?>.*?\*\/))|(?<!content\:)([\'"])([a-z_][a-z0-9\-_]*?)\2(?=[\s\{\}\];,])#si',
				'#(\/\*(?>.*?\*\/))|(\burl\()([\'"])([^\s]+?)\3(\))#si',
				// Minify HEX color code.
				// '#(?<=[\s:,\-]\#)([a-f0-6]+)\1([a-f0-6]+)\2([a-f0-6]+)\3#i',
				// Replace `(border|outline):none` with `(border|outline):0`.
				'#(?<=[\{;])(border|outline):none(?=[;\}\!])#',
				// Remove empty selector(s).
				'#(\/\*(?>.*?\*\/))|(^|[\{\}])(?:[^\s\{\}]+)\{\}#s',
			),
			array(
				'$1',
				'$1$2$3$4$5$6$7',
				'$1',
				':0',
				'$1:0 0',
				'.$1',
				'$1$3',
				'$1$2$4$5',
				'$1$2$3',
				'$1:0',
				'$1$2',
			),
			$css
		);
	}

	/**
	 * Is Post Id
	 *
	 * @since 1.0.0
	 * @return bool|false|int The post id.
	 */
	private function is_post_id() {  // phpcs:ignore WordPress.NamingConventions.ValidFunctionName.MethodNameInvalid,WordPress.NamingConventions.ValidFunctionName.FunctionNameInvalid
		$post_id = get_the_ID();

		if ( ! $post_id ) {
			return false;
		}
		return $post_id;
	}
}
