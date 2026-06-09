<?php
/**
 * Abilities: Add Nexter Blocks Form Field child blocks (tpgb/tp-form-*).
 *
 * Registers 9 abilities — one for each form field type:
 *   - tpgb/tp-form-name-field
 *   - tpgb/tp-form-email-field
 *   - tpgb/tp-form-number-field
 *   - tpgb/tp-form-message-field (textarea)
 *   - tpgb/tp-form-option-field (select)
 *   - tpgb/tp-form-checkbox-button
 *   - tpgb/tp-form-radio-button
 *   - tpgb/tp-form-hidden-field
 *   - tpgb/tp-form-submit-button
 *
 * @package The_Plus_Addons_For_Block_Editor
 * @since   1.3.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// -------------------------------------------------------------------------
// SHARED HELPERS
// -------------------------------------------------------------------------

/**
 * Permission callback shared by every form-field ability.
 *
 * @param array|null $input Ability input arguments.
 * @return bool True when the current user may edit posts (and target post if given).
 */
function tpgb_mcp_formfield_permission( ?array $input = null ): bool {
	if ( ! current_user_can( 'edit_posts' ) ) {
		return false; }
	$post_id = absint( $input['post_id'] ?? 0 );
	if ( $post_id > 0 && ! current_user_can( 'edit_post', $post_id ) ) {
		return false; }
	return true;
}

/**
 * Insert a form-field block into the page or parent container.
 * To place inside a tp-form-block container, pass parent_block_id.
 *
 * @param array  $input      Ability input arguments.
 * @param string $block_name Block type, e.g. 'tpgb/tp-form-name-field'.
 * @param array  $attrs      Block attributes built so far.
 * @return array Ability result, or array with `error` key on failure.
 */
function tpgb_mcp_insert_formfield( array $input, string $block_name, array $attrs ): array {
	$post_id  = absint( $input['post_id'] ?? 0 );
	$position = intval( $input['position'] ?? -1 );

	if ( $post_id <= 0 ) {
		return array( 'error' => new WP_Error( 'missing_params', __( 'post_id is required.', 'the-plus-addons-for-block-editor' ) ) );
	}

	$post = get_post( $post_id );
	if ( ! $post instanceof WP_Post ) {
		return array( 'error' => new WP_Error( 'invalid_post', __( 'Target post not found.', 'the-plus-addons-for-block-editor' ) ) );
	}

	if ( ! tpgb_mcp_has_registered_block( $block_name ) ) {
		return array(
			'error' => new WP_Error(
				'block_missing',
				sprintf(
					/* translators: %s: block name. */
					__( '%s is not registered.', 'the-plus-addons-for-block-editor' ),
					$block_name
				)
			),
		);
	}

	$blocks = tpgb_mcp_get_blocks( $post_id );
	if ( is_wp_error( $blocks ) ) {
		return array( 'error' => $blocks ); }

	$block = tpgb_mcp_build_block( $block_name, $attrs );

	$parent_id = ! empty( $input['parent_block_id'] ) ? sanitize_text_field( $input['parent_block_id'] ) : '';
	if ( '' !== $parent_id ) {
		if ( ! tpgb_mcp_insert_inner_block( $blocks, $parent_id, $block, $position ) ) {
			return array( 'error' => new WP_Error( 'parent_not_found', __( 'Parent block not found.', 'the-plus-addons-for-block-editor' ) ) );
		}
	} else {
		tpgb_mcp_insert_block( $blocks, $block, $position );
	}

	$save_result = tpgb_mcp_save_blocks( $post_id, $blocks );
	if ( is_wp_error( $save_result ) ) {
		return array( 'error' => $save_result ); }

	return array(
		'block_id'   => $attrs['block_id'],
		'block_name' => $block_name,
		'post_id'    => $post_id,
	);
}

/**
 * Shared output schema declaration for every form-field ability.
 *
 * @return array JSON-schema describing the ability output payload.
 */
function tpgb_mcp_formfield_output_schema(): array {
	return array(
		'type'       => 'object',
		'properties' => array(
			'block_id'   => array( 'type' => 'string' ),
			'block_name' => array( 'type' => 'string' ),
			'post_id'    => array( 'type' => 'integer' ),
		),
	);
}

// -------------------------------------------------------------------------
// 1. NAME FIELD (tpgb/tp-form-name-field).
// -------------------------------------------------------------------------

wp_register_ability(
	'nexter-blocks/add-tpgb-form-name-field',
	array(
		'label'               => __( 'Add Form Name Field', 'the-plus-addons-for-block-editor' ),
		'description'         => __( 'Adds a text input field for names inside a Nexter Form Block. Must be placed inside tpgb/tp-form-block using parent_block_id.', 'the-plus-addons-for-block-editor' ),
		'category'            => 'nexter-blocks',
		'input_schema'        => array(
			'type'                 => 'object',
			'properties'           => array(
				'post_id'          => array( 'type' => 'integer' ),
				'position'         => array(
					'type'    => 'integer',
					'default' => -1,
				),
				'parent_block_id'  => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'block_id of parent tpgb/tp-form-block. REQUIRED for form fields.',
				),
				'label'            => array(
					'type'    => 'string',
					'default' => 'Name',
				),
				'placeholder'      => array(
					'type'    => 'string',
					'default' => 'Name',
				),
				'required'         => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'errorMsg'         => array(
					'type'    => 'string',
					'default' => 'This field is required.',
				),
				'autoComplete'     => array(
					'type'    => 'string',
					'enum'    => array( 'off', 'on', 'name', 'given-name', 'family-name' ),
					'default' => 'off',
				),
				'validateName'     => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Only allow alphabetic characters.',
				),
				'maxAlphabets'     => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Max character count.',
				),
				'iconType'         => array(
					'type'    => 'string',
					'enum'    => array( 'none', 'icon', 'image' ),
					'default' => 'none',
				),
				'icon'             => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Font Awesome class when iconType is "icon".',
				),
				'iconImageUrl'     => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Image URL when iconType is "image".',
				),
				'iconColor'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'iconHoverColor'   => array(
					'type'    => 'string',
					'default' => '',
				),
				'showHelpText'     => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'helpText'         => array(
					'type'    => 'string',
					'default' => 'Description',
				),
				'helpTextPosition' => array(
					'type'    => 'string',
					'enum'    => array( 'top', 'bottom' ),
					'default' => 'top',
				),
				'fieldSize'        => array(
					'type'    => 'string',
					'enum'    => array( '', 'small', 'medium', 'large', 'full' ),
					'default' => '',
				),
			),
			'required'             => array( 'post_id', 'parent_block_id' ),
			'additionalProperties' => false,
		),
		'output_schema'       => tpgb_mcp_formfield_output_schema(),
		'execute_callback'    => 'tpgb_mcp_add_form_name_field',
		'permission_callback' => 'tpgb_mcp_formfield_permission',
		'meta'                => array(
			'show_in_rest' => true,
			'mcp'          => array(
				'public' => true,
				'type'   => 'tool',
			),
		),
	)
);

/**
 * Execute callback: insert a tpgb/tp-form-name-field block.
 *
 * @param array $input Ability input arguments.
 * @return array|WP_Error Ability result or error on failure.
 */
function tpgb_mcp_add_form_name_field( array $input ) {
	$attrs = array( 'block_id' => tpgb_mcp_generate_block_id() );

	if ( ! empty( $input['label'] ) && 'Name' !== $input['label'] ) {
		$attrs['labelss'] = sanitize_text_field( $input['label'] ); }
	if ( ! empty( $input['placeholder'] ) && 'Name' !== $input['placeholder'] ) {
		$attrs['placeholder'] = sanitize_text_field( $input['placeholder'] ); }
	if ( ! empty( $input['required'] ) ) {
		$attrs['reqTgl'] = true; }
	if ( ! empty( $input['errorMsg'] ) && 'This field is required.' !== $input['errorMsg'] ) {
		$attrs['error'] = sanitize_text_field( $input['errorMsg'] ); }
	if ( ! empty( $input['autoComplete'] ) && 'off' !== $input['autoComplete'] ) {
		$attrs['autoComplete'] = sanitize_text_field( $input['autoComplete'] ); }
	if ( ! empty( $input['validateName'] ) ) {
		$attrs['valName'] = true; }
	if ( ! empty( $input['maxAlphabets'] ) ) {
		$attrs['maxAlphabets'] = (string) absint( $input['maxAlphabets'] ); }

	$icon_type = sanitize_key( $input['iconType'] ?? 'none' );
	if ( 'none' !== $icon_type ) {
		$attrs['nameIconType'] = $icon_type;
		if ( 'icon' === $icon_type && ! empty( $input['icon'] ) ) {
			$attrs['nameIcons'] = sanitize_text_field( $input['icon'] ); }
		if ( 'image' === $icon_type && ! empty( $input['iconImageUrl'] ) ) {
			$attrs['nameImage'] = array( 'url' => esc_url_raw( $input['iconImageUrl'] ) ); }
	}

	if ( ! empty( $input['iconColor'] ) ) {
		$attrs['icnClr'] = sanitize_text_field( $input['iconColor'] ); }
	if ( ! empty( $input['iconHoverColor'] ) ) {
		$attrs['hvrIcnClr'] = sanitize_text_field( $input['iconHoverColor'] ); }
	if ( ! empty( $input['showHelpText'] ) ) {
		$attrs['hlpTxt'] = true; }
	if ( ! empty( $input['helpText'] ) && 'Description' !== $input['helpText'] ) {
		$attrs['desctxt'] = sanitize_text_field( $input['helpText'] ); }
	if ( ! empty( $input['helpTextPosition'] ) && 'top' !== $input['helpTextPosition'] ) {
		$attrs['descPoss'] = sanitize_key( $input['helpTextPosition'] ); }
	if ( ! empty( $input['fieldSize'] ) ) {
		$attrs['inpSz'] = array( 'md' => sanitize_key( $input['fieldSize'] ) ); }

	$res = tpgb_mcp_insert_formfield( $input, 'tpgb/tp-form-name-field', $attrs );
	return isset( $res['error'] ) ? $res['error'] : $res;
}

// -------------------------------------------------------------------------
// 2. EMAIL FIELD.
// -------------------------------------------------------------------------

wp_register_ability(
	'nexter-blocks/add-tpgb-form-email-field',
	array(
		'label'               => __( 'Add Form Email Field', 'the-plus-addons-for-block-editor' ),
		'description'         => __( 'Adds an email input field inside a Nexter Form Block. Must be placed inside tpgb/tp-form-block using parent_block_id.', 'the-plus-addons-for-block-editor' ),
		'category'            => 'nexter-blocks',
		'input_schema'        => array(
			'type'                 => 'object',
			'properties'           => array(
				'post_id'          => array( 'type' => 'integer' ),
				'position'         => array(
					'type'    => 'integer',
					'default' => -1,
				),
				'parent_block_id'  => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'block_id of parent tpgb/tp-form-block. REQUIRED.',
				),
				'label'            => array(
					'type'    => 'string',
					'default' => 'Email',
				),
				'placeholder'      => array(
					'type'    => 'string',
					'default' => 'Email',
				),
				'required'         => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'errorMsg'         => array(
					'type'    => 'string',
					'default' => 'This field is required.',
				),
				'autoComplete'     => array(
					'type'    => 'string',
					'default' => 'off',
				),
				'iconType'         => array(
					'type'    => 'string',
					'enum'    => array( 'none', 'icon', 'image' ),
					'default' => 'none',
				),
				'icon'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'iconImageUrl'     => array(
					'type'    => 'string',
					'default' => '',
				),
				'iconColor'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'iconHoverColor'   => array(
					'type'    => 'string',
					'default' => '',
				),
				'showHelpText'     => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'helpText'         => array(
					'type'    => 'string',
					'default' => 'Description',
				),
				'helpTextPosition' => array(
					'type'    => 'string',
					'enum'    => array( 'top', 'bottom' ),
					'default' => 'top',
				),
				'fieldSize'        => array(
					'type'    => 'string',
					'enum'    => array( '', 'small', 'medium', 'large', 'full' ),
					'default' => '',
				),
			),
			'required'             => array( 'post_id', 'parent_block_id' ),
			'additionalProperties' => false,
		),
		'output_schema'       => tpgb_mcp_formfield_output_schema(),
		'execute_callback'    => 'tpgb_mcp_add_form_email_field',
		'permission_callback' => 'tpgb_mcp_formfield_permission',
		'meta'                => array(
			'show_in_rest' => true,
			'mcp'          => array(
				'public' => true,
				'type'   => 'tool',
			),
		),
	)
);

/**
 * Execute callback: insert a tpgb/tp-form-email-field block.
 *
 * @param array $input Ability input arguments.
 * @return array|WP_Error Ability result or error on failure.
 */
function tpgb_mcp_add_form_email_field( array $input ) {
	$attrs = array( 'block_id' => tpgb_mcp_generate_block_id() );

	if ( ! empty( $input['label'] ) && 'Email' !== $input['label'] ) {
		$attrs['labelss'] = sanitize_text_field( $input['label'] ); }
	if ( ! empty( $input['placeholder'] ) && 'Email' !== $input['placeholder'] ) {
		$attrs['placeholder'] = sanitize_text_field( $input['placeholder'] ); }
	if ( isset( $input['required'] ) && ! $input['required'] ) {
		$attrs['reqTgl'] = false; } // Default is true.
	if ( ! empty( $input['errorMsg'] ) && 'This field is required.' !== $input['errorMsg'] ) {
		$attrs['error'] = sanitize_text_field( $input['errorMsg'] ); }
	if ( ! empty( $input['autoComplete'] ) && 'off' !== $input['autoComplete'] ) {
		$attrs['autoComplete'] = sanitize_text_field( $input['autoComplete'] ); }

	$icon_type = sanitize_key( $input['iconType'] ?? 'none' );
	if ( 'none' !== $icon_type ) {
		$attrs['iconType'] = $icon_type;
		if ( 'icon' === $icon_type && ! empty( $input['icon'] ) ) {
			$attrs['icons'] = sanitize_text_field( $input['icon'] ); }
		if ( 'image' === $icon_type && ! empty( $input['iconImageUrl'] ) ) {
			$attrs['ButtonImage'] = array( 'url' => esc_url_raw( $input['iconImageUrl'] ) ); }
	}

	if ( ! empty( $input['iconColor'] ) ) {
		$attrs['icnClr'] = sanitize_text_field( $input['iconColor'] ); }
	if ( ! empty( $input['iconHoverColor'] ) ) {
		$attrs['hvrIcnClr'] = sanitize_text_field( $input['iconHoverColor'] ); }
	if ( ! empty( $input['showHelpText'] ) ) {
		$attrs['hlpTxt'] = true; }
	if ( ! empty( $input['helpText'] ) && 'Description' !== $input['helpText'] ) {
		$attrs['desctxt'] = sanitize_text_field( $input['helpText'] ); }
	if ( ! empty( $input['helpTextPosition'] ) && 'top' !== $input['helpTextPosition'] ) {
		$attrs['descPoss'] = sanitize_key( $input['helpTextPosition'] ); }
	if ( ! empty( $input['fieldSize'] ) ) {
		$attrs['inpSz'] = array( 'md' => sanitize_key( $input['fieldSize'] ) ); }

	$res = tpgb_mcp_insert_formfield( $input, 'tpgb/tp-form-email-field', $attrs );
	return isset( $res['error'] ) ? $res['error'] : $res;
}

// -------------------------------------------------------------------------
// 3. NUMBER FIELD.
// -------------------------------------------------------------------------

wp_register_ability(
	'nexter-blocks/add-tpgb-form-number-field',
	array(
		'label'               => __( 'Add Form Number Field', 'the-plus-addons-for-block-editor' ),
		'description'         => __( 'Adds a number input field inside a Nexter Form Block. Must be placed inside tpgb/tp-form-block using parent_block_id.', 'the-plus-addons-for-block-editor' ),
		'category'            => 'nexter-blocks',
		'input_schema'        => array(
			'type'                 => 'object',
			'properties'           => array(
				'post_id'          => array( 'type' => 'integer' ),
				'position'         => array(
					'type'    => 'integer',
					'default' => -1,
				),
				'parent_block_id'  => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'block_id of parent tpgb/tp-form-block. REQUIRED.',
				),
				'label'            => array(
					'type'    => 'string',
					'default' => 'Number',
				),
				'placeholder'      => array(
					'type'    => 'string',
					'default' => 'Number',
				),
				'required'         => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'errorMsg'         => array(
					'type'    => 'string',
					'default' => 'This field is required.',
				),
				'validateNumber'   => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Enforce numeric input only.',
				),
				'minNumber'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'maxNumber'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'maxCount'         => array(
					'type'        => 'string',
					'default'     => '15',
					'description' => 'Max digit count.',
				),
				'iconType'         => array(
					'type'    => 'string',
					'enum'    => array( 'none', 'icon', 'image' ),
					'default' => 'none',
				),
				'icon'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'iconImageUrl'     => array(
					'type'    => 'string',
					'default' => '',
				),
				'iconColor'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'iconHoverColor'   => array(
					'type'    => 'string',
					'default' => '',
				),
				'showHelpText'     => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'helpText'         => array(
					'type'    => 'string',
					'default' => 'Description',
				),
				'helpTextPosition' => array(
					'type'    => 'string',
					'enum'    => array( 'top', 'bottom' ),
					'default' => 'top',
				),
				'fieldSize'        => array(
					'type'    => 'string',
					'enum'    => array( '', 'small', 'medium', 'large', 'full' ),
					'default' => '',
				),
			),
			'required'             => array( 'post_id', 'parent_block_id' ),
			'additionalProperties' => false,
		),
		'output_schema'       => tpgb_mcp_formfield_output_schema(),
		'execute_callback'    => 'tpgb_mcp_add_form_number_field',
		'permission_callback' => 'tpgb_mcp_formfield_permission',
		'meta'                => array(
			'show_in_rest' => true,
			'mcp'          => array(
				'public' => true,
				'type'   => 'tool',
			),
		),
	)
);

/**
 * Execute callback: insert a tpgb/tp-form-number-field block.
 *
 * @param array $input Ability input arguments.
 * @return array|WP_Error Ability result or error on failure.
 */
function tpgb_mcp_add_form_number_field( array $input ) {
	$attrs = array( 'block_id' => tpgb_mcp_generate_block_id() );

	if ( ! empty( $input['label'] ) && 'Number' !== $input['label'] ) {
		$attrs['labelss'] = sanitize_text_field( $input['label'] ); }
	if ( ! empty( $input['placeholder'] ) && 'Number' !== $input['placeholder'] ) {
		$attrs['placeholder'] = sanitize_text_field( $input['placeholder'] ); }
	if ( ! empty( $input['required'] ) ) {
		$attrs['reqTgl'] = true; }
	if ( ! empty( $input['errorMsg'] ) && 'This field is required.' !== $input['errorMsg'] ) {
		$attrs['error'] = sanitize_text_field( $input['errorMsg'] ); }
	if ( ! empty( $input['validateNumber'] ) ) {
		$attrs['valNum'] = true; }
	if ( ! empty( $input['minNumber'] ) ) {
		$attrs['minNum'] = sanitize_text_field( $input['minNumber'] ); }
	if ( ! empty( $input['maxNumber'] ) ) {
		$attrs['maxNum'] = sanitize_text_field( $input['maxNumber'] ); }
	if ( ! empty( $input['maxCount'] ) && '15' !== $input['maxCount'] ) {
		$attrs['maxCount'] = sanitize_text_field( $input['maxCount'] ); }

	$icon_type = sanitize_key( $input['iconType'] ?? 'none' );
	if ( 'none' !== $icon_type ) {
		$attrs['numIconType'] = $icon_type;
		if ( 'icon' === $icon_type && ! empty( $input['icon'] ) ) {
			$attrs['numIcons'] = sanitize_text_field( $input['icon'] ); }
		if ( 'image' === $icon_type && ! empty( $input['iconImageUrl'] ) ) {
			$attrs['numImage'] = array( 'url' => esc_url_raw( $input['iconImageUrl'] ) ); }
	}

	if ( ! empty( $input['iconColor'] ) ) {
		$attrs['icnClr'] = sanitize_text_field( $input['iconColor'] ); }
	if ( ! empty( $input['iconHoverColor'] ) ) {
		$attrs['hvrIcnClr'] = sanitize_text_field( $input['iconHoverColor'] ); }
	if ( ! empty( $input['showHelpText'] ) ) {
		$attrs['hlpTxt'] = true; }
	if ( ! empty( $input['helpText'] ) && 'Description' !== $input['helpText'] ) {
		$attrs['desctxt'] = sanitize_text_field( $input['helpText'] ); }
	if ( ! empty( $input['helpTextPosition'] ) && 'top' !== $input['helpTextPosition'] ) {
		$attrs['descPoss'] = sanitize_key( $input['helpTextPosition'] ); }
	if ( ! empty( $input['fieldSize'] ) ) {
		$attrs['inpSz'] = array( 'md' => sanitize_key( $input['fieldSize'] ) ); }

	$res = tpgb_mcp_insert_formfield( $input, 'tpgb/tp-form-number-field', $attrs );
	return isset( $res['error'] ) ? $res['error'] : $res;
}

// -------------------------------------------------------------------------
// 4. MESSAGE FIELD (textarea).
// -------------------------------------------------------------------------

wp_register_ability(
	'nexter-blocks/add-tpgb-form-message-field',
	array(
		'label'               => __( 'Add Form Message Field', 'the-plus-addons-for-block-editor' ),
		'description'         => __( 'Adds a multi-line textarea field inside a Nexter Form Block. Must be placed inside tpgb/tp-form-block using parent_block_id.', 'the-plus-addons-for-block-editor' ),
		'category'            => 'nexter-blocks',
		'input_schema'        => array(
			'type'                 => 'object',
			'properties'           => array(
				'post_id'          => array( 'type' => 'integer' ),
				'position'         => array(
					'type'    => 'integer',
					'default' => -1,
				),
				'parent_block_id'  => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'block_id of parent tpgb/tp-form-block. REQUIRED.',
				),
				'label'            => array(
					'type'    => 'string',
					'default' => 'Message',
				),
				'placeholder'      => array(
					'type'    => 'string',
					'default' => 'Message',
				),
				'required'         => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'errorMsg'         => array(
					'type'    => 'string',
					'default' => 'This field is required.',
				),
				'rows'             => array(
					'type'        => 'string',
					'default'     => '2',
					'description' => 'Number of textarea rows.',
				),
				'autoComplete'     => array(
					'type'    => 'string',
					'default' => 'off',
				),
				'showHelpText'     => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'helpText'         => array(
					'type'    => 'string',
					'default' => 'Description',
				),
				'helpTextPosition' => array(
					'type'    => 'string',
					'enum'    => array( 'top', 'bottom' ),
					'default' => 'top',
				),
				'fieldSize'        => array(
					'type'    => 'string',
					'enum'    => array( '', 'small', 'medium', 'large', 'full' ),
					'default' => '',
				),
			),
			'required'             => array( 'post_id', 'parent_block_id' ),
			'additionalProperties' => false,
		),
		'output_schema'       => tpgb_mcp_formfield_output_schema(),
		'execute_callback'    => 'tpgb_mcp_add_form_message_field',
		'permission_callback' => 'tpgb_mcp_formfield_permission',
		'meta'                => array(
			'show_in_rest' => true,
			'mcp'          => array(
				'public' => true,
				'type'   => 'tool',
			),
		),
	)
);

/**
 * Execute callback: insert a tpgb/tp-form-message-field block.
 *
 * @param array $input Ability input arguments.
 * @return array|WP_Error Ability result or error on failure.
 */
function tpgb_mcp_add_form_message_field( array $input ) {
	$attrs = array( 'block_id' => tpgb_mcp_generate_block_id() );

	if ( ! empty( $input['label'] ) && 'Message' !== $input['label'] ) {
		$attrs['labelss'] = sanitize_text_field( $input['label'] ); }
	if ( ! empty( $input['placeholder'] ) && 'Message' !== $input['placeholder'] ) {
		$attrs['placeholder'] = sanitize_text_field( $input['placeholder'] ); }
	if ( ! empty( $input['required'] ) ) {
		$attrs['reqTgl'] = true; }
	if ( ! empty( $input['errorMsg'] ) && 'This field is required.' !== $input['errorMsg'] ) {
		$attrs['error'] = sanitize_text_field( $input['errorMsg'] ); }
	if ( ! empty( $input['rows'] ) && '2' !== $input['rows'] ) {
		$attrs['lineNum'] = sanitize_text_field( $input['rows'] ); }
	if ( ! empty( $input['autoComplete'] ) && 'off' !== $input['autoComplete'] ) {
		$attrs['autoComplete'] = sanitize_text_field( $input['autoComplete'] ); }
	if ( ! empty( $input['showHelpText'] ) ) {
		$attrs['hlpTxt'] = true; }
	if ( ! empty( $input['helpText'] ) && 'Description' !== $input['helpText'] ) {
		$attrs['desctxt'] = sanitize_text_field( $input['helpText'] ); }
	if ( ! empty( $input['helpTextPosition'] ) && 'top' !== $input['helpTextPosition'] ) {
		$attrs['descPoss'] = sanitize_key( $input['helpTextPosition'] ); }
	if ( ! empty( $input['fieldSize'] ) ) {
		$attrs['inpSz'] = array( 'md' => sanitize_key( $input['fieldSize'] ) ); }

	$res = tpgb_mcp_insert_formfield( $input, 'tpgb/tp-form-message-field', $attrs );
	return isset( $res['error'] ) ? $res['error'] : $res;
}

// -------------------------------------------------------------------------
// 5. OPTION FIELD (select dropdown).
// -------------------------------------------------------------------------

wp_register_ability(
	'nexter-blocks/add-tpgb-form-option-field',
	array(
		'label'               => __( 'Add Form Select Dropdown', 'the-plus-addons-for-block-editor' ),
		'description'         => __( 'Adds a select dropdown field inside a Nexter Form Block with custom options. Must be placed inside tpgb/tp-form-block using parent_block_id.', 'the-plus-addons-for-block-editor' ),
		'category'            => 'nexter-blocks',
		'input_schema'        => array(
			'type'                 => 'object',
			'properties'           => array(
				'post_id'          => array( 'type' => 'integer' ),
				'position'         => array(
					'type'    => 'integer',
					'default' => -1,
				),
				'parent_block_id'  => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'block_id of parent tpgb/tp-form-block. REQUIRED.',
				),
				'label'            => array(
					'type'    => 'string',
					'default' => 'Select',
				),
				'required'         => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'errorMsg'         => array(
					'type'    => 'string',
					'default' => 'This field is required.',
				),
				'options'          => array(
					'type'        => 'array',
					'description' => 'Array of option strings e.g. ["Option 1", "Option 2", "Option 3"].',
					'items'       => array( 'type' => 'string' ),
				),
				'showHelpText'     => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'helpText'         => array(
					'type'    => 'string',
					'default' => 'Description',
				),
				'helpTextPosition' => array(
					'type'    => 'string',
					'enum'    => array( 'top', 'bottom' ),
					'default' => 'top',
				),
				'fieldSize'        => array(
					'type'    => 'string',
					'enum'    => array( '', 'small', 'medium', 'large', 'full' ),
					'default' => '',
				),
			),
			'required'             => array( 'post_id', 'parent_block_id' ),
			'additionalProperties' => false,
		),
		'output_schema'       => tpgb_mcp_formfield_output_schema(),
		'execute_callback'    => 'tpgb_mcp_add_form_option_field',
		'permission_callback' => 'tpgb_mcp_formfield_permission',
		'meta'                => array(
			'show_in_rest' => true,
			'mcp'          => array(
				'public' => true,
				'type'   => 'tool',
			),
		),
	)
);

/**
 * Execute callback: insert a tpgb/tp-form-option-field block.
 *
 * @param array $input Ability input arguments.
 * @return array|WP_Error Ability result or error on failure.
 */
function tpgb_mcp_add_form_option_field( array $input ) {
	$attrs = array( 'block_id' => tpgb_mcp_generate_block_id() );

	if ( ! empty( $input['label'] ) && 'Select' !== $input['label'] ) {
		$attrs['labelss'] = sanitize_text_field( $input['label'] ); }
	if ( ! empty( $input['required'] ) ) {
		$attrs['reqTgl'] = true; }
	if ( ! empty( $input['errorMsg'] ) && 'This field is required.' !== $input['errorMsg'] ) {
		$attrs['error'] = sanitize_text_field( $input['errorMsg'] ); }

	if ( ! empty( $input['options'] ) && is_array( $input['options'] ) ) {
		$opts = array();
		foreach ( $input['options'] as $i => $o ) {
			$opts[] = array(
				'_key'     => (string) $i,
				'optValue' => sanitize_text_field( (string) $o ),
			);
		}
		$attrs['fldOptions'] = $opts;
	}

	if ( ! empty( $input['showHelpText'] ) ) {
		$attrs['hlpTxt'] = true; }
	if ( ! empty( $input['helpText'] ) && 'Description' !== $input['helpText'] ) {
		$attrs['desctxt'] = sanitize_text_field( $input['helpText'] ); }
	if ( ! empty( $input['helpTextPosition'] ) && 'top' !== $input['helpTextPosition'] ) {
		$attrs['descPoss'] = sanitize_key( $input['helpTextPosition'] ); }
	if ( ! empty( $input['fieldSize'] ) ) {
		$attrs['inpSz'] = array( 'md' => sanitize_key( $input['fieldSize'] ) ); }

	$res = tpgb_mcp_insert_formfield( $input, 'tpgb/tp-form-option-field', $attrs );
	return isset( $res['error'] ) ? $res['error'] : $res;
}

// -------------------------------------------------------------------------
// 6. CHECKBOX BUTTON.
// -------------------------------------------------------------------------

wp_register_ability(
	'nexter-blocks/add-tpgb-form-checkbox-button',
	array(
		'label'               => __( 'Add Form Checkbox Group', 'the-plus-addons-for-block-editor' ),
		'description'         => __( 'Adds a checkbox group field inside a Nexter Form Block. Must be placed inside tpgb/tp-form-block using parent_block_id.', 'the-plus-addons-for-block-editor' ),
		'category'            => 'nexter-blocks',
		'input_schema'        => array(
			'type'                 => 'object',
			'properties'           => array(
				'post_id'          => array( 'type' => 'integer' ),
				'position'         => array(
					'type'    => 'integer',
					'default' => -1,
				),
				'parent_block_id'  => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'block_id of parent tpgb/tp-form-block. REQUIRED.',
				),
				'label'            => array(
					'type'    => 'string',
					'default' => 'Checkbox',
				),
				'required'         => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'errorMsg'         => array(
					'type'    => 'string',
					'default' => 'This field is required.',
				),
				'options'          => array(
					'type'        => 'array',
					'description' => 'Array of checkbox option labels.',
					'items'       => array( 'type' => 'string' ),
				),
				'optionPosition'   => array(
					'type'    => 'string',
					'enum'    => array( 'vertical', 'horizontal' ),
					'default' => 'vertical',
				),
				'checkboxPosition' => array(
					'type'    => 'string',
					'enum'    => array( '', 'left', 'right' ),
					'default' => '',
				),
				'borderRadius'     => array(
					'type'    => 'string',
					'enum'    => array( 'square', 'round' ),
					'default' => 'square',
				),
				'showHelpText'     => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'helpText'         => array(
					'type'    => 'string',
					'default' => 'Description',
				),
				'helpTextPosition' => array(
					'type'    => 'string',
					'enum'    => array( 'top', 'bottom' ),
					'default' => 'top',
				),
				'fieldSize'        => array(
					'type'    => 'string',
					'enum'    => array( '', 'small', 'medium', 'large', 'full' ),
					'default' => '',
				),
			),
			'required'             => array( 'post_id', 'parent_block_id' ),
			'additionalProperties' => false,
		),
		'output_schema'       => tpgb_mcp_formfield_output_schema(),
		'execute_callback'    => 'tpgb_mcp_add_form_checkbox_button',
		'permission_callback' => 'tpgb_mcp_formfield_permission',
		'meta'                => array(
			'show_in_rest' => true,
			'mcp'          => array(
				'public' => true,
				'type'   => 'tool',
			),
		),
	)
);

/**
 * Execute callback: insert a tpgb/tp-form-checkbox-button block.
 *
 * @param array $input Ability input arguments.
 * @return array|WP_Error Ability result or error on failure.
 */
function tpgb_mcp_add_form_checkbox_button( array $input ) {
	$attrs = array( 'block_id' => tpgb_mcp_generate_block_id() );

	if ( ! empty( $input['label'] ) && 'Checkbox' !== $input['label'] ) {
		$attrs['labelss'] = sanitize_text_field( $input['label'] ); }
	if ( ! empty( $input['required'] ) ) {
		$attrs['reqTgl'] = true; }
	if ( ! empty( $input['errorMsg'] ) && 'This field is required.' !== $input['errorMsg'] ) {
		$attrs['error'] = sanitize_text_field( $input['errorMsg'] ); }

	if ( ! empty( $input['options'] ) && is_array( $input['options'] ) ) {
		$opts = array();
		foreach ( $input['options'] as $i => $o ) {
			$opts[] = array(
				'_key'     => (string) $i,
				'optValue' => sanitize_text_field( (string) $o ),
			);
		}
		$attrs['fldOptions'] = $opts;
	}

	if ( ! empty( $input['optionPosition'] ) && 'vertical' !== $input['optionPosition'] ) {
		$attrs['optPos'] = sanitize_key( $input['optionPosition'] ); }
	if ( ! empty( $input['checkboxPosition'] ) ) {
		$attrs['chkPosition'] = sanitize_key( $input['checkboxPosition'] ); }
	if ( ! empty( $input['borderRadius'] ) && 'square' !== $input['borderRadius'] ) {
		$attrs['bRadius'] = sanitize_key( $input['borderRadius'] ); }
	if ( ! empty( $input['showHelpText'] ) ) {
		$attrs['hlpTxt'] = true; }
	if ( ! empty( $input['helpText'] ) && 'Description' !== $input['helpText'] ) {
		$attrs['desctxt'] = sanitize_text_field( $input['helpText'] ); }
	if ( ! empty( $input['helpTextPosition'] ) && 'top' !== $input['helpTextPosition'] ) {
		$attrs['descPoss'] = sanitize_key( $input['helpTextPosition'] ); }
	if ( ! empty( $input['fieldSize'] ) ) {
		$attrs['inpSz'] = array( 'md' => sanitize_key( $input['fieldSize'] ) ); }

	$res = tpgb_mcp_insert_formfield( $input, 'tpgb/tp-form-checkbox-button', $attrs );
	return isset( $res['error'] ) ? $res['error'] : $res;
}

// -------------------------------------------------------------------------
// 7. RADIO BUTTON.
// -------------------------------------------------------------------------

wp_register_ability(
	'nexter-blocks/add-tpgb-form-radio-button',
	array(
		'label'               => __( 'Add Form Radio Group', 'the-plus-addons-for-block-editor' ),
		'description'         => __( 'Adds a radio button group field inside a Nexter Form Block. Must be placed inside tpgb/tp-form-block using parent_block_id.', 'the-plus-addons-for-block-editor' ),
		'category'            => 'nexter-blocks',
		'input_schema'        => array(
			'type'                 => 'object',
			'properties'           => array(
				'post_id'          => array( 'type' => 'integer' ),
				'position'         => array(
					'type'    => 'integer',
					'default' => -1,
				),
				'parent_block_id'  => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'block_id of parent tpgb/tp-form-block. REQUIRED.',
				),
				'label'            => array(
					'type'    => 'string',
					'default' => 'Radio',
				),
				'required'         => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'errorMsg'         => array(
					'type'    => 'string',
					'default' => 'This field is required.',
				),
				'options'          => array(
					'type'        => 'array',
					'description' => 'Array of radio option labels.',
					'items'       => array( 'type' => 'string' ),
				),
				'optionPosition'   => array(
					'type'    => 'string',
					'enum'    => array( 'vertical', 'horizontal' ),
					'default' => 'vertical',
				),
				'borderRadius'     => array(
					'type'    => 'string',
					'enum'    => array( 'round', 'square' ),
					'default' => 'round',
				),
				'showHelpText'     => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'helpText'         => array(
					'type'    => 'string',
					'default' => 'Description',
				),
				'helpTextPosition' => array(
					'type'    => 'string',
					'enum'    => array( 'top', 'bottom' ),
					'default' => 'top',
				),
				'fieldSize'        => array(
					'type'    => 'string',
					'enum'    => array( '', 'small', 'medium', 'large', 'full' ),
					'default' => '',
				),
			),
			'required'             => array( 'post_id', 'parent_block_id' ),
			'additionalProperties' => false,
		),
		'output_schema'       => tpgb_mcp_formfield_output_schema(),
		'execute_callback'    => 'tpgb_mcp_add_form_radio_button',
		'permission_callback' => 'tpgb_mcp_formfield_permission',
		'meta'                => array(
			'show_in_rest' => true,
			'mcp'          => array(
				'public' => true,
				'type'   => 'tool',
			),
		),
	)
);

/**
 * Execute callback: insert a tpgb/tp-form-radio-button block.
 *
 * @param array $input Ability input arguments.
 * @return array|WP_Error Ability result or error on failure.
 */
function tpgb_mcp_add_form_radio_button( array $input ) {
	$attrs = array( 'block_id' => tpgb_mcp_generate_block_id() );

	if ( ! empty( $input['label'] ) && 'Radio' !== $input['label'] ) {
		$attrs['labelss'] = sanitize_text_field( $input['label'] ); }
	if ( ! empty( $input['required'] ) ) {
		$attrs['reqTgl'] = true; }
	if ( ! empty( $input['errorMsg'] ) && 'This field is required.' !== $input['errorMsg'] ) {
		$attrs['error'] = sanitize_text_field( $input['errorMsg'] ); }

	if ( ! empty( $input['options'] ) && is_array( $input['options'] ) ) {
		$opts = array();
		foreach ( $input['options'] as $i => $o ) {
			$opts[] = array(
				'_key'     => (string) $i,
				'optValue' => sanitize_text_field( (string) $o ),
			);
		}
		$attrs['fldOptions'] = $opts;
	}

	if ( ! empty( $input['optionPosition'] ) && 'vertical' !== $input['optionPosition'] ) {
		$attrs['optPos'] = sanitize_key( $input['optionPosition'] ); }
	if ( ! empty( $input['borderRadius'] ) && 'round' !== $input['borderRadius'] ) {
		$attrs['bRadius'] = sanitize_key( $input['borderRadius'] ); }
	if ( ! empty( $input['showHelpText'] ) ) {
		$attrs['hlpTxt'] = true; }
	if ( ! empty( $input['helpText'] ) && 'Description' !== $input['helpText'] ) {
		$attrs['desctxt'] = sanitize_text_field( $input['helpText'] ); }
	if ( ! empty( $input['helpTextPosition'] ) && 'top' !== $input['helpTextPosition'] ) {
		$attrs['descPoss'] = sanitize_key( $input['helpTextPosition'] ); }
	if ( ! empty( $input['fieldSize'] ) ) {
		$attrs['inpSz'] = array( 'md' => sanitize_key( $input['fieldSize'] ) ); }

	$res = tpgb_mcp_insert_formfield( $input, 'tpgb/tp-form-radio-button', $attrs );
	return isset( $res['error'] ) ? $res['error'] : $res;
}

// -------------------------------------------------------------------------
// 8. HIDDEN FIELD.
// -------------------------------------------------------------------------

wp_register_ability(
	'nexter-blocks/add-tpgb-form-hidden-field',
	array(
		'label'               => __( 'Add Form Hidden Field', 'the-plus-addons-for-block-editor' ),
		'description'         => __( 'Adds a hidden input field inside a Nexter Form Block. Useful for passing context values with submissions. Must be placed inside tpgb/tp-form-block using parent_block_id.', 'the-plus-addons-for-block-editor' ),
		'category'            => 'nexter-blocks',
		'input_schema'        => array(
			'type'                 => 'object',
			'properties'           => array(
				'post_id'         => array( 'type' => 'integer' ),
				'position'        => array(
					'type'    => 'integer',
					'default' => -1,
				),
				'parent_block_id' => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'block_id of parent tpgb/tp-form-block. REQUIRED.',
				),
				'fieldName'       => array(
					'type'        => 'string',
					'default'     => 'Hidden Name',
					'description' => 'Name attribute for the hidden field.',
				),
				'fieldValue'      => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Hidden value submitted with the form.',
				),
			),
			'required'             => array( 'post_id', 'parent_block_id' ),
			'additionalProperties' => false,
		),
		'output_schema'       => tpgb_mcp_formfield_output_schema(),
		'execute_callback'    => 'tpgb_mcp_add_form_hidden_field',
		'permission_callback' => 'tpgb_mcp_formfield_permission',
		'meta'                => array(
			'show_in_rest' => true,
			'mcp'          => array(
				'public' => true,
				'type'   => 'tool',
			),
		),
	)
);

/**
 * Execute callback: insert a tpgb/tp-form-hidden-field block.
 *
 * @param array $input Ability input arguments.
 * @return array|WP_Error Ability result or error on failure.
 */
function tpgb_mcp_add_form_hidden_field( array $input ) {
	$attrs = array( 'block_id' => tpgb_mcp_generate_block_id() );

	if ( ! empty( $input['fieldName'] ) && 'Hidden Name' !== $input['fieldName'] ) {
		$attrs['nameValue'] = sanitize_text_field( $input['fieldName'] );
	}
	if ( ! empty( $input['fieldValue'] ) ) {
		$attrs['actualValue'] = sanitize_text_field( $input['fieldValue'] );
	}

	$res = tpgb_mcp_insert_formfield( $input, 'tpgb/tp-form-hidden-field', $attrs );
	return isset( $res['error'] ) ? $res['error'] : $res;
}

// -------------------------------------------------------------------------
// 9. SUBMIT BUTTON.
// -------------------------------------------------------------------------

wp_register_ability(
	'nexter-blocks/add-tpgb-form-submit-button',
	array(
		'label'               => __( 'Add Form Submit Button', 'the-plus-addons-for-block-editor' ),
		'description'         => __( 'Adds the submit button inside a Nexter Form Block. Every form needs exactly one submit button. Must be placed inside tpgb/tp-form-block using parent_block_id.', 'the-plus-addons-for-block-editor' ),
		'category'            => 'nexter-blocks',
		'input_schema'        => array(
			'type'                 => 'object',
			'properties'           => array(
				'post_id'         => array( 'type' => 'integer' ),
				'position'        => array(
					'type'    => 'integer',
					'default' => -1,
				),
				'parent_block_id' => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'block_id of parent tpgb/tp-form-block. REQUIRED.',
				),
				'label'           => array(
					'type'        => 'string',
					'default'     => 'Submit',
					'description' => 'Button text.',
				),
				'alignment'       => array(
					'type'    => 'string',
					'enum'    => array( 'left', 'center', 'right' ),
					'default' => 'left',
				),
				'isInline'        => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Display button inline with prior field.',
				),
				'iconType'        => array(
					'type'    => 'string',
					'enum'    => array( 'none', 'icon', 'image', 'svg' ),
					'default' => 'none',
				),
				'icon'            => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Font Awesome class (when iconType is "icon").',
				),
				'iconImageUrl'    => array(
					'type'    => 'string',
					'default' => '',
				),
				'iconPosition'    => array(
					'type'        => 'string',
					'enum'        => array( '-1', '1' ),
					'default'     => '-1',
					'description' => '-1 = before text, 1 = after text.',
				),
				'iconSize'        => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'iconSpacing'     => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'iconColor'       => array(
					'type'    => 'string',
					'default' => '',
				),
				'iconHoverColor'  => array(
					'type'    => 'string',
					'default' => '',
				),
				'buttonSize'      => array(
					'type'    => 'string',
					'enum'    => array( '', 'small', 'medium', 'large', 'full' ),
					'default' => '',
				),
			),
			'required'             => array( 'post_id', 'parent_block_id' ),
			'additionalProperties' => false,
		),
		'output_schema'       => tpgb_mcp_formfield_output_schema(),
		'execute_callback'    => 'tpgb_mcp_add_form_submit_button',
		'permission_callback' => 'tpgb_mcp_formfield_permission',
		'meta'                => array(
			'show_in_rest' => true,
			'mcp'          => array(
				'public' => true,
				'type'   => 'tool',
			),
		),
	)
);

/**
 * Execute callback: insert a tpgb/tp-form-submit-button block.
 *
 * @param array $input Ability input arguments.
 * @return array|WP_Error Ability result or error on failure.
 */
function tpgb_mcp_add_form_submit_button( array $input ) {
	$attrs = array( 'block_id' => tpgb_mcp_generate_block_id() );

	if ( ! empty( $input['label'] ) && 'Submit' !== $input['label'] ) {
		$attrs['labelss'] = sanitize_text_field( $input['label'] ); }
	if ( ! empty( $input['alignment'] ) && 'left' !== $input['alignment'] ) {
		$attrs['btnAlign'] = array( 'md' => sanitize_key( $input['alignment'] ) );
	}
	if ( ! empty( $input['isInline'] ) ) {
		$attrs['isInline'] = true; }

	$icon_type = sanitize_key( $input['iconType'] ?? 'none' );
	if ( 'none' !== $icon_type ) {
		$attrs['ButtonType'] = $icon_type;
		if ( 'icon' === $icon_type && ! empty( $input['icon'] ) ) {
			$attrs['ButtonIcon'] = sanitize_text_field( $input['icon'] ); }
		if ( 'image' === $icon_type && ! empty( $input['iconImageUrl'] ) ) {
			$attrs['ButtonImage'] = array( 'url' => esc_url_raw( $input['iconImageUrl'] ) ); }
	}

	if ( ! empty( $input['iconPosition'] ) && '-1' !== $input['iconPosition'] ) {
		$attrs['iconPos'] = sanitize_text_field( $input['iconPosition'] ); }
	if ( ! empty( $input['iconSize'] ) ) {
		$attrs['iconSz'] = array(
			'md'   => (string) absint( $input['iconSize'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['iconSpacing'] ) ) {
		$attrs['iconSpc'] = array(
			'md'   => (string) absint( $input['iconSpacing'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['iconColor'] ) ) {
		$attrs['icnClr'] = sanitize_text_field( $input['iconColor'] ); }
	if ( ! empty( $input['iconHoverColor'] ) ) {
		$attrs['hvrIcnClr'] = sanitize_text_field( $input['iconHoverColor'] ); }
	if ( ! empty( $input['buttonSize'] ) ) {
		$attrs['btnSz'] = array( 'md' => sanitize_key( $input['buttonSize'] ) ); }

	$res = tpgb_mcp_insert_formfield( $input, 'tpgb/tp-form-submit-button', $attrs );
	return isset( $res['error'] ) ? $res['error'] : $res;
}
