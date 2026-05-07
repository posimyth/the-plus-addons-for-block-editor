<?php
/**
 * TPGB Conditions Rules.
 *
 * @package TPGBP
 * @since 1.0.6
 */

// phpcs:disable WordPress.Files.FileName

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Tpgb_ Display_ Conditions_ Rules.
 *
 * @since 1.0.0
 */
class Tpgb_Display_Conditions_Rules {

	/**
	 * Member Variable
	 *
	 * @var instance
	 */
	private static $instance;

	/**
	 * Display Rules
	 *
	 * @access protected
	 *
	 * @var bool
	 */
	public static $conditions = array();

	/**
	 *  Initiator
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor
	 */
	public function __construct() {
		/**Display Rules Options*/
		add_filter( 'tpgb_display_option', array( $this, 'tpgb_display_option' ), 10 );

		$load_enable_extra = get_option( 'tpgb_normal_blocks_opts' );
		if ( ! empty( $load_enable_extra ) && isset( $load_enable_extra['tp_extra_option'] ) && ! empty( $load_enable_extra['tp_extra_option'] ) && in_array( 'tp-display-rules', $load_enable_extra['tp_extra_option'], true ) ) {
			WP_Block_Supports::get_instance()->register(
				'displayrules',
				array(
					'register_attribute' => array( $this, 'register_attr_display_rules' ),
				)
			);
		}
	}

	/**
	 * Register attr display rules.
	 *
	 * @param mixed $block_type The block type.
	 */
	public function register_attr_display_rules( $block_type ) {
		if ( $block_type && isset( $block_type->name ) && 'ai/ai-block' !== $block_type->name && strpos( $block_type->name, 'tpgb/' ) === false && strpos( $block_type->name, 'kadence/' ) === false && strpos( $block_type->name, 'fluent-support/' ) === false && $block_type->attributes && ! array_key_exists( 'tpgbDisrule', $block_type->attributes ) ) {
			$attributes             = self::tpgb_display_option();
			$block_type->attributes = array_merge( $block_type->attributes, $attributes );
		}
	}

	/**
	 * Display Rules Options
	 *
	 * @since 1.0.6
	 * @param array $option The option.
	 */
	public static function tpgb_display_option( $option = array() ) {
		$disoption = array(
			'tpgbDisrule'  => array(
				'type'    => 'boolean',
				'default' => false,
			),
			'disRule'      => array(
				'type'    => 'string',
				'default' => 'all',
			),
			'displayRules' => array(
				'type'          => 'array',
				'repeaterField' => array(
					(object) array(
						'displayKey'                   => array(
							'type'    => 'string',
							'default' => 'authentication',
						),
						'assigOpr'                     => array(
							'type'    => 'string',
							'default' => 'is',
						),
						'tpgb_startdate_value'         => array(
							'type'    => 'time',
							'default' => '',
						),
						'tpgb_enddate_value'           => array(
							'type'    => 'time',
							'default' => '',
						),
						'tpgb_time_value'              => array(
							'type'    => 'time',
							'default' => '',
						),
						'tpgb_day_value'               => array(
							'type'    => 'string',
							'default' => '[]',
						),
						'tpgb_post_type_value'         => array(
							'type'    => 'string',
							'default' => '[]',
						),
						'tpgb_page_value'              => array(
							'type'    => 'string',
							'default' => '[]',
						),
						'tpgb_post_value'              => array(
							'type'    => 'string',
							'default' => '[]',
						),
						'tpgb_taxonomy_archive_value'  => array(
							'type'    => 'string',
							'default' => '[]',
						),
						'tpgb_single_terms_value'      => array(
							'type'    => 'string',
							'default' => '[]',
						),
						'tpgb_author_archive_value'    => array(
							'type'    => 'string',
							'default' => '[]',
						),
						'tpgb_static_page_value'       => array(
							'type'    => 'string',
							'default' => 'home',
						),
						'tpgb_post_type_archive_value' => array(
							'type'    => 'string',
							'default' => '[]',
						),
						'tpgb_date_archive_value'      => array(
							'type'    => 'string',
							'default' => 'day',
						),
						'tpgb_search_results_value'    => array(
							'type'    => 'string',
							'default' => '',
						),
						'tpgb_authentication_value'    => array(
							'type'    => 'string',
							'default' => 'authenticated',
						),
						'tpgb_role_value'              => array(
							'type'    => 'string',
							'default' => 'administrator',
						),
						'tpgb_os_value'                => array(
							'type'    => 'string',
							'default' => 'iphone',
						),
						'tpgb_browser_value'           => array(
							'type'    => 'string',
							'default' => 'ie',
						),
						'tpgb_single_archive_value'    => array(
							'type'    => 'string',
							'default' => '[]',
						),
						'tpgb_acf_text_name'           => array(
							'type'    => 'string',
							'default' => '[]',
						),
						'tpgb_acf_text_value'          => array(
							'type'    => 'string',
							'default' => '',
						),
						'tpgb_acf_select_name'         => array(
							'type'    => 'string',
							'default' => '[]',
						),
						'tpgb_acf_select_value'        => array(
							'type'    => 'string',
							'default' => '',
						),
						'tpgb_acf_button_group_name'   => array(
							'type'    => 'string',
							'default' => '[]',
						),
						'tpgb_acf_button_group_value'  => array(
							'type'    => 'string',
							'default' => '',
						),
						'tpgb_acf_boolean_name'        => array(
							'type'    => 'string',
							'default' => '[]',
						),
						'tpgb_acf_boolean_value'       => array(
							'type'    => 'string',
							'default' => 'true',
						),
						'tpgb_acf_datetime_name'       => array(
							'type'    => 'string',
							'default' => '[]',
						),
						'tpgb_acf_datetime_value'      => array(
							'type'    => 'string',
							'default' => '',
						),
					),
				),
				'default'       => array(
					(object) array(
						'_key'                         => '0',
						'displayKey'                   => 'authentication',
						'tpgb_authentication_value'    => 'authenticated',
						'tpgb_role_value'              => 'administrator',
						'tpgb_os_value'                => 'iphone',
						'tpgb_browser_value'           => 'ie',
						'assigOpr'                     => 'is',
						'tpgb_startdate_value'         => '2021-10-13',
						'tpgb_enddate_value'           => '2021-10-15',
						'tpgb_time_value'              => '12:00',
						'tpgb_day_value'               => '[]',
						'tpgb_post_type_value'         => '[]',
						'tpgb_page_value'              => '[]',
						'tpgb_post_value'              => '[]',
						'tpgb_taxonomy_archive_value'  => '[]',
						'tpgb_single_terms_value'      => '[]',
						'tpgb_author_archive_value'    => '[]',
						'tpgb_post_type_archive_value' => '[]',
						'tpgb_static_page_value'       => 'home',
						'tpgb_date_archive_value'      => 'day',
						'tpgb_search_results_value'    => '',
						'tpgb_single_archive_value'    => '[]',
						'tpgb_acf_text_name'           => '[]',
						'tpgb_acf_text_value'          => '',
						'tpgb_acf_select_name'         => '[]',
						'tpgb_acf_select_value'        => '',
						'tpgb_acf_button_group_name'   => '[]',
						'tpgb_acf_button_group_value'  => '',
						'tpgb_acf_boolean_name'        => '[]',
						'tpgb_acf_boolean_value'       => 'true',
						'tpgb_acf_datetime_name'       => '[]',
						'tpgb_acf_datetime_value'      => '',
					),
				),
			),
		);

		return array_merge( $option, $disoption );
	}

	/**
	 * Check Display Rules Actions
	 *
	 * @param int   $block_id The block id.
	 * @param mixed $attribute The attribute.
	 */
	public static function tpgb_rules_actions( $block_id, $attribute ) {

		if ( ! empty( $block_id ) && isset( $attribute['tpgbDisrule'] ) && ! empty( $attribute['tpgbDisrule'] ) ) {
			// Set the rules.
			if ( ! empty( $attribute['displayRules'] ) ) {
				self::set_rules( $block_id, $attribute['displayRules'] );
			}

			if ( ! empty( $attribute['disRule'] ) ) {
				if ( ! self::display_is_visible( $block_id, $attribute['disRule'] ) && ! empty( $attribute['disRule'] ) ) { // Check the rules.
					return false;
				}
			}
		}
		return true;
	}

	/**
	 * Check Set Rules
	 *
	 * @param int   $id The id.
	 * @param array $rules The rules.
	 */
	public static function set_rules( $id, $rules = array() ) {
		$tpgb_startdate_value = '';
		$tpgb_enddate_value   = '';
		if ( ! $rules ) {
			return;
		}

		foreach ( $rules as $index => $rule ) {
			$rule     = (array) $rule;
			$key      = $rule['displayKey'];
			$key_name = null;

			if ( array_key_exists( 'tpgb_' . $key . '_name', $rule ) ) {
				$key_name = $rule[ 'tpgb_' . $key . '_name' ];
			}

			$check_is_not = isset( $rule['assigOpr'] ) ? $rule['assigOpr'] : 'is';
			if ( isset( $rule['displayKey'] ) && 'date' === $rule['displayKey'] ) {
				$tpgb_startdate_value = isset( $rule['tpgb_startdate_value'] ) ? $rule['tpgb_startdate_value'] : '';
				$tpgb_enddate_value   = isset( $rule['tpgb_enddate_value'] ) ? $rule['tpgb_enddate_value'] : '';
				$value                = $tpgb_startdate_value . ' to ' . $tpgb_enddate_value;
			} else {
				$keyvalue = isset( $rule[ 'tpgb_' . $key . '_value' ] ) ? $rule[ 'tpgb_' . $key . '_value' ] : '';
				if ( 'single_archive' === $key || 'single_terms' === $key ) {
					$texo           = isset( $rule['taxonomySlug'] ) ? $rule['taxonomySlug'] : '';
					$value[ $texo ] = $keyvalue;
				} else {
					$value = $keyvalue;
				}
			}

			if ( method_exists( 'Tpgb_Display_Conditions_Rules', 'tpgb_check_' . $key ) ) {
				$check = call_user_func( array( 'Tpgb_Display_Conditions_Rules', 'tpgb_check_' . $key ), $value, $check_is_not, $key_name );
				self::$conditions[ $id ][ $key . '_' . $rule['_key'] ] = $check;
			} elseif ( method_exists( 'Tpgbp_Display_Conditions_Rules', 'tpgb_check_' . $key ) ) {

				$check = call_user_func( array( 'Tpgbp_Display_Conditions_Rules', 'tpgb_check_' . $key ), $value, $check_is_not, $key_name );
				self::$conditions[ $id ][ $key . '_' . $rule['_key'] ] = $check;
			}
		}
	}

	/**
	 * Display is visible.
	 *
	 * @param int   $id The id.
	 * @param mixed $relation The relation.
	 * @return mixed The result.
	 */
	public static function display_is_visible( $id, $relation ) {

		if ( ! array_key_exists( $id, self::$conditions ) ) {
			return;
		}

		if ( 'any' === $relation ) {
			if ( ! in_array( true, self::$conditions[ $id ], true ) ) {
				return false;
			}
		} elseif ( in_array( false, self::$conditions[ $id ], true ) ) {
				return false;
		}

		return true;
	}

	/**
	 * Compare check.
	 *
	 * @param mixed $first_value The first value.
	 * @param mixed $second_value The second value.
	 * @param bool  $check_is_not The check is not.
	 * @return mixed The result.
	 */
	public static function compare_check( $first_value, $second_value, $check_is_not ) {
		switch ( $check_is_not ) {
			case 'is':
				return $first_value === $second_value;
			case 'not':
				return $first_value !== $second_value;
			default:
				return $first_value === $second_value;
		}
	}

	/**
	 * Check Login Status of visitor
	 *
	 * @param mixed $value The value.
	 * @param mixed $check_is_not The check is not.
	 * @param mixed $key The key.
	 */
	public static function tpgb_check_authentication( $value, $check_is_not, $key ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
		return self::compare_check( is_user_logged_in(), true, $check_is_not );
	}
}
Tpgb_Display_Conditions_Rules::get_instance();
