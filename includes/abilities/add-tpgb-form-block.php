<?php
/**
 * Ability: Add Nexter Blocks Form Block (tpgb/tp-form-block) to a Gutenberg page.
 *
 * @package The_Plus_Addons_For_Block_Editor
 * @since   1.3.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_register_ability(
	'nexter-blocks/add-tpgb-form-block',
	array(
		'label'               => __( 'Add Nexter Blocks Form Block', 'the-plus-addons-for-block-editor' ),
		'description'         => __( 'Adds the Nexter Blocks Form Block (tpgb/tp-form-block) — a contact/lead form container. Supports layout styles, email actions (To/CC/BCC/Subject), auto-response message, redirect on submit, meta data collection, comprehensive styling for labels, inputs, placeholders, buttons, checkboxes/radios, select dropdowns, success/error messages, loader, form alignment, row/column gaps, and full animation/transform/advanced controls. This is a dynamic block that accepts form field inner blocks (name, email, textarea, checkbox, radio, select, submit button, etc.).', 'the-plus-addons-for-block-editor' ),
		'category'            => 'nexter-blocks',

		'input_schema'        => array(
			'type'                 => 'object',
			'properties'           => array(

				/* ── Core ─────────────────────────────────────────────────── */
				'post_id'                     => array(
					'type'        => 'integer',
					'description' => 'WordPress page/post ID to insert the block into.',
				),
				'position'                    => array(
					'type'        => 'integer',
					'default'     => -1,
					'description' => 'Zero-based insert position. Use -1 to append.',
				),
				'parent_block_id'             => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Optional parent container block_id for nesting.',
				),

				/* ── Layout ───────────────────────────────────────────────── */
				'layoutType'                  => array(
					'type'        => 'string',
					'description' => 'Form layout style preset e.g. "nxt-style-1".',
					'default'     => 'nxt-style-1',
				),
				'formAlignment'               => array(
					'type'    => 'string',
					'enum'    => array( '', 'left', 'center', 'right' ),
					'default' => '',
				),
				'rowGap'                      => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Gap between form rows in px.',
				),
				'columnGap'                   => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Gap between form columns in px.',
				),
				'showRequiredIcon'            => array(
					'type'        => 'boolean',
					'default'     => true,
					'description' => 'Show asterisk for required fields.',
				),
				'formId'                      => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Custom form identifier.',
				),

				/* ── Action / Email settings ──────────────────────────────── */
				'emailTo'                     => array(
					'type'        => 'string',
					'description' => 'Primary email recipient address. Supports comma-separated list.',
					'default'     => '',
				),
				'emailSubject'                => array(
					'type'        => 'string',
					'description' => 'Email subject line.',
					'default'     => 'New Form Submission',
				),
				'emailCc'                     => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'CC email address(es).',
				),
				'emailBcc'                    => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'BCC email address(es).',
				),
				'emailHeading'                => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Email body heading/preamble.',
				),
				'fromName'                    => array(
					'type'        => 'string',
					'default'     => '[nxt_name]',
					'description' => 'Sender name. Shortcodes allowed.',
				),
				'fromEmail'                   => array(
					'type'        => 'string',
					'default'     => '[nxt_email]',
					'description' => 'Sender email. Shortcodes allowed.',
				),
				'replyTo'                     => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Reply-to email address.',
				),
				'autoResponseMsg'             => array(
					'type'        => 'string',
					'description' => 'Success message shown after submit.',
					'default'     => 'Thank you for your message. It has been sent.',
				),
				'failMsg'                     => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Failure/error message.',
				),
				'validationErrorMsg'          => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Validation error message.',
				),

				/* ── Redirect after submit ────────────────────────────────── */
				'redirectUrl'                 => array(
					'type'    => 'string',
					'default' => '',
				),
				'redirectTarget'              => array(
					'type'    => 'string',
					'enum'    => array( '_self', '_blank' ),
					'default' => '_self',
				),
				'redirectNofollow'            => array(
					'type'    => 'boolean',
					'default' => false,
				),

				/* ── Meta data collection ─────────────────────────────────── */
				'collectFormData'             => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Save form submissions to database.',
				),

				/* ── Label styling ────────────────────────────────────────── */
				'labelColor'                  => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Label text colour (normal).',
				),
				'labelHoverColor'             => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Label text colour (hover).',
				),
				'enableLabelTypo'             => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'labelTypoSize'               => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'labelBottomMargin'           => array(
					'type'        => 'integer',
					'default'     => 5,
					'description' => 'Space below label in px.',
				),

				/* ── Input styling ────────────────────────────────────────── */
				'inputColor'                  => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Input text colour.',
				),
				'placeholderColor'            => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Placeholder text colour (normal).',
				),
				'hoverPlaceholderColor'       => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Placeholder colour on focus.',
				),
				'inputBgColor'                => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Input background colour (normal).',
				),
				'hoverInputBgColor'           => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Input background colour (focus).',
				),
				'inputBorder'                 => array(
					'type'        => 'object',
					'description' => 'Input border {type,color,width}.',
				),
				'inputBorderRadius'           => array(
					'type'        => 'object',
					'description' => 'Input border radius.',
				),
				'hoverInputBorderColor'       => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Input border colour on focus.',
				),
				'inputPadding'                => array(
					'type'        => 'object',
					'description' => 'Input padding {top,right,bottom,left,unit}.',
				),
				'enableInputTypo'             => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'inputTypoSize'               => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'activePlaceholderColor'      => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Placeholder colour when active/filled.',
				),
				'activeInputBgColor'          => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Input bg when active/filled.',
				),
				'activeInputBorderColor'      => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Input border colour when active/filled.',
				),

				/* ── Submit button styling ────────────────────────────────── */
				'buttonColor'                 => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Submit button text colour (normal).',
				),
				'hoverButtonColor'            => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Submit button text colour (hover).',
				),
				'buttonBgColor'               => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Submit button bg colour (normal).',
				),
				'hoverButtonBgColor'          => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Submit button bg colour (hover).',
				),
				'buttonBorder'                => array(
					'type'        => 'object',
					'description' => 'Submit button border.',
				),
				'hoverButtonBorderColor'      => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Submit button border colour (hover).',
				),
				'buttonBorderRadius'          => array(
					'type'        => 'object',
					'description' => 'Submit button border radius.',
				),
				'buttonPadding'               => array(
					'type'        => 'object',
					'description' => 'Submit button padding.',
				),
				'enableButtonTypo'            => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'buttonTypoSize'              => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'buttonPreset'                => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Global button preset key (e.g. "btnpreset1"). When provided, raw submit-button colour/border/padding values are ignored and the preset is applied instead.',
				),

				/* ── Checkbox/Radio styling ───────────────────────────────── */
				'checkboxRadioSize'           => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Checkbox/radio size in px.',
				),
				'checkboxRadioBgColor'        => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Checkbox/radio background (unchecked).',
				),
				'checkboxRadioBorder'         => array(
					'type'        => 'object',
					'description' => 'Checkbox/radio border.',
				),
				'checkedBgColor'              => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Checkbox/radio background (checked).',
				),
				'checkedBorderColor'          => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Checkbox/radio border colour (checked).',
				),
				'checkedColor'                => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Check mark / radio dot colour.',
				),
				'checkboxRadioTextColor'      => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Checkbox/radio label text colour.',
				),
				'checkboxRadioTextHoverColor' => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Checkbox/radio label text colour (hover).',
				),
				'enableCheckboxRadioTypo'     => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'checkboxRadioTypoSize'       => array(
					'type'    => 'integer',
					'default' => 0,
				),

				/* ── Select dropdown styling ──────────────────────────────── */
				'selectColor'                 => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Select text colour (normal).',
				),
				'selectBgColor'               => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Select background colour (normal).',
				),
				'selectBorder'                => array(
					'type'        => 'object',
					'description' => 'Select border.',
				),
				'selectBorderRadius'          => array(
					'type'        => 'object',
					'description' => 'Select border radius.',
				),
				'hoverSelectColor'            => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Select text colour (hover).',
				),
				'hoverSelectBgColor'          => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Select background colour (hover).',
				),
				'hoverSelectBorderColor'      => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Select border colour (hover).',
				),
				'selectPadding'               => array(
					'type'        => 'object',
					'description' => 'Select padding.',
				),
				'enableSelectTypo'            => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'selectTypoSize'              => array(
					'type'    => 'integer',
					'default' => 0,
				),

				/* ── Description styling ──────────────────────────────────── */
				'descColor'                   => array(
					'type'    => 'string',
					'default' => '',
				),
				'hoverDescColor'              => array(
					'type'    => 'string',
					'default' => '',
				),
				'enableDescTypo'              => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'descTypoSize'                => array(
					'type'    => 'integer',
					'default' => 0,
				),

				/* ── Success message styling ──────────────────────────────── */
				'successColor'                => array(
					'type'    => 'string',
					'default' => '',
				),
				'enableSuccessTypo'           => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'successTypoSize'             => array(
					'type'    => 'integer',
					'default' => 0,
				),

				/* ── Loader ───────────────────────────────────────────────── */
				'loaderColor'                 => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Loader spinner colour.',
				),

				/* ── Scroll animation ────────────────────────────────────── */
				'scrollAnimation'             => array(
					'type'    => 'string',
					'enum'    => array(
						'',
						'fadeIn',
						'fadeInUp',
						'fadeInDown',
						'fadeInLeft',
						'fadeInRight',
						'zoomIn',
						'zoomInUp',
						'zoomInDown',
						'slideInUp',
						'slideInDown',
						'slideInLeft',
						'slideInRight',
						'bounceIn',
						'rotateIn',
						'flipInX',
						'flipInY',
					),
					'default' => '',
				),
				'animDuration'                => array(
					'type'    => 'string',
					'enum'    => array( '', 'slow', 'normal', 'fast' ),
					'default' => '',
				),
				'animDelay'                   => array(
					'type'    => 'string',
					'default' => '',
				),
				'animEasing'                  => array(
					'type'    => 'string',
					'enum'    => array( '', 'linear', 'ease', 'ease-in', 'ease-out', 'ease-in-out' ),
					'default' => '',
				),

				/* ── Advanced ─────────────────────────────────────────────── */
				'hideDesktop'                 => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'hideTablet'                  => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'hideMobile'                  => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'globalClasses'               => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalId'                    => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalCustomCss'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalWidth'                 => array(
					'type'    => 'string',
					'enum'    => array( '', 'inline', 'full', 'custom' ),
					'default' => '',
				),
				'globalZindex'                => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalPosition'              => array(
					'type'    => 'string',
					'enum'    => array( '', 'relative', 'absolute', 'fixed', 'sticky' ),
					'default' => '',
				),
				'transitionDuration'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'transitionFunction'          => array(
					'type'    => 'string',
					'enum'    => array( '', 'ease', 'ease-in', 'ease-out', 'ease-in-out', 'linear' ),
					'default' => '',
				),
				'transitionOrigin'            => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalMargin'                => array( 'type' => 'object' ),
				'globalPadding'               => array( 'type' => 'object' ),
				'globalBgColor'               => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalBgHoverColor'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalBorder'                => array( 'type' => 'object' ),
				'globalBorderHover'           => array( 'type' => 'object' ),
				'globalBRadius'               => array( 'type' => 'object' ),
				'globalBRadiusHover'          => array( 'type' => 'object' ),
				'globalBShadow'               => array( 'type' => 'object' ),
				'globalBShadowHover'          => array( 'type' => 'object' ),

				'rotateDeg'                   => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotateX'                     => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotateY'                     => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotatePerspective'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotateDegHover'              => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotateXHover'                => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotateYHover'                => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotatePersHover'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'offsetX'                     => array(
					'type'    => 'string',
					'default' => '',
				),
				'offsetY'                     => array(
					'type'    => 'string',
					'default' => '',
				),
				'offsetZ'                     => array(
					'type'    => 'string',
					'default' => '',
				),
				'offsetXHover'                => array(
					'type'    => 'string',
					'default' => '',
				),
				'offsetYHover'                => array(
					'type'    => 'string',
					'default' => '',
				),
				'offsetZHover'                => array(
					'type'    => 'string',
					'default' => '',
				),
				'scaleValue'                  => array(
					'type'    => 'string',
					'default' => '',
				),
				'scaleKeepRatio'              => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'scaleValueHover'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'skewX'                       => array(
					'type'    => 'string',
					'default' => '',
				),
				'skewY'                       => array(
					'type'    => 'string',
					'default' => '',
				),
				'skewXHover'                  => array(
					'type'    => 'string',
					'default' => '',
				),
				'skewYHover'                  => array(
					'type'    => 'string',
					'default' => '',
				),
				'flipHorizontal'              => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'flipVertical'                => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'flipHorizontalHover'         => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'flipVerticalHover'           => array(
					'type'    => 'boolean',
					'default' => false,
				),

				'settings'                    => array(
					'type'        => 'object',
					'description' => 'Raw attribute overrides merged directly into the block.',
				),
				'fontFamily'                  => array(
					'type'        => 'string',
					'description' => 'Font family name (e.g. "Inter", "Roboto", "Playfair Display"). When inspecting a URL via nexter-blocks/inspect-page, pass the returned fonts[].family value verbatim.',
					'default'     => '',
				),
				'fontType'                    => array(
					'type'        => 'string',
					'description' => 'Font category — "sans-serif", "serif", "display", "handwriting", or "monospace".',
					'default'     => '',
				),
				'customFont'                  => array(
					'type'        => 'string',
					'description' => 'Custom (non-Google) font name. Overrides fontFamily.',
					'default'     => '',
				),
				'fontWeight'                  => array(
					'type'        => 'string',
					'description' => 'Font weight as a string ("100"..."900"). Embedded inside every typography group\'s fontFamily.fontWeight on this block. For per-group control, use the settings raw override or sprout/update-element.',
					'default'     => '',
				),
				'textDecoration'              => array(
					'type'        => 'string',
					'enum'        => array( '', 'none', 'underline', 'overline', 'line-through' ),
					'description' => 'Text decoration applied to every typography group on this block. Stamped as a sibling of fontFamily inside each typo array. For per-group control, use the settings raw override or sprout/update-element.',
					'default'     => '',
				),
			),
			'required'             => array( 'post_id' ),
			'additionalProperties' => false,
		),

		'output_schema'       => array(
			'type'       => 'object',
			'properties' => array(
				'block_id'   => array( 'type' => 'string' ),
				'block_name' => array( 'type' => 'string' ),
				'post_id'    => array( 'type' => 'integer' ),
			),
		),

		'execute_callback'    => 'tpgb_mcp_add_form_block_ability',
		'permission_callback' => 'tpgb_mcp_add_form_block_permission',
		'meta'                => array(
			'show_in_rest' => true,
			'mcp'          => array(
				'public' => true,
				'type'   => 'tool',
			),
		),
	)
);

// -------------------------------------------------------------------------
// PERMISSION / HELPERS
// -------------------------------------------------------------------------

/**
 * Permission callback for the add-form-block ability.
 *
 * @param array|null $input Ability input arguments.
 * @return bool True when the current user may insert the block.
 */
function tpgb_mcp_add_form_block_permission( ?array $input = null ): bool {
	if ( ! current_user_can( 'edit_posts' ) ) {
		return false; }
	$post_id = absint( $input['post_id'] ?? 0 );
	if ( $post_id > 0 && ! current_user_can( 'edit_post', $post_id ) ) {
		return false; }
	return true;
}

/**
 * Build a Nexter Blocks spacing attribute from {top,bottom,left,right,unit}.
 *
 * @param array $v Raw spacing values.
 * @return array Spacing attribute structured for the block.
 */
function tpgb_mcp_frm_spacing( array $v ): array {
	return array(
		'md'   => array(
			'top'    => sanitize_text_field( $v['top'] ?? '0' ),
			'bottom' => sanitize_text_field( $v['bottom'] ?? '0' ),
			'left'   => sanitize_text_field( $v['left'] ?? '0' ),
			'right'  => sanitize_text_field( $v['right'] ?? '0' ),
			'unit'   => sanitize_text_field( $v['unit'] ?? 'px' ),
		),
		'unit' => sanitize_text_field( $v['unit'] ?? 'px' ),
	);
}
/**
 * Build a Nexter Blocks border attribute from {type,color,width}.
 *
 * @param array $b Raw border values.
 * @return array Border attribute structured for the block.
 */
function tpgb_mcp_frm_border( array $b ): array {
	$w = $b['width'] ?? array();
	return array(
		'openBorder' => 1,
		'type'       => sanitize_text_field( $b['type'] ?? 'solid' ),
		'color'      => sanitize_text_field( $b['color'] ?? '' ),
		'width'      => array(
			'md'   => array(
				'top'    => sanitize_text_field( $w['top'] ?? '1' ),
				'right'  => sanitize_text_field( $w['right'] ?? '1' ),
				'bottom' => sanitize_text_field( $w['bottom'] ?? '1' ),
				'left'   => sanitize_text_field( $w['left'] ?? '1' ),
				'unit'   => sanitize_text_field( $w['unit'] ?? 'px' ),
			),
			'unit' => sanitize_text_field( $w['unit'] ?? 'px' ),
		),
	);
}
/**
 * Build a Nexter Blocks border-radius attribute from {top,bottom,left,right,unit}.
 *
 * @param array $r Raw radius values.
 * @return array Border-radius attribute structured for the block.
 */
function tpgb_mcp_frm_radius( array $r ): array {
	return array(
		'md'   => array(
			'top'    => sanitize_text_field( $r['top'] ?? '0' ),
			'bottom' => sanitize_text_field( $r['bottom'] ?? '0' ),
			'left'   => sanitize_text_field( $r['left'] ?? '0' ),
			'right'  => sanitize_text_field( $r['right'] ?? '0' ),
			'unit'   => sanitize_text_field( $r['unit'] ?? 'px' ),
		),
		'unit' => sanitize_text_field( $r['unit'] ?? 'px' ),
	);
}
/**
 * Build a Nexter Blocks box-shadow attribute.
 *
 * @param array $s Raw shadow values {horizontal,vertical,blur,spread,color,inset}.
 * @return array Box-shadow attribute structured for the block.
 */
function tpgb_mcp_frm_bshadow( array $s ): array {
	return array(
		'openShadow' => true,
		'inset'      => $s['inset'] ?? 0,
		'horizontal' => (string) intval( $s['horizontal'] ?? 0 ),
		'vertical'   => (string) intval( $s['vertical'] ?? 4 ),
		'blur'       => (string) absint( $s['blur'] ?? 8 ),
		'spread'     => (string) intval( $s['spread'] ?? 0 ),
		'color'      => sanitize_text_field( $s['color'] ?? 'rgba(0,0,0,0.40)' ),
	);
}
/**
 * Build a Nexter Blocks solid-colour background attribute.
 *
 * @param string $color Background colour value.
 * @return array Background attribute structured for the block.
 */
function tpgb_mcp_frm_bg( string $color ): array {
	return array(
		'openBg'         => 1,
		'bgType'         => 'color',
		'videoSource'    => 'local',
		'bgDefaultColor' => sanitize_text_field( $color ),
		'bgGradient'     => '',
		'isCustom'       => 'fpp',
	);
}
/**
 * Determine whether any wrapper-level (global/transform) attributes are set.
 *
 * @param array $attrs Block attributes built so far.
 * @return bool True when the block needs the tpgbDisrule wrapper enabled.
 */
function tpgb_mcp_frm_needs_wrapper( array $attrs ): bool {
	$keys = array(
		'globalMargin',
		'globalPadding',
		'globalBg',
		'globalBgHover',
		'globalBorder',
		'globalBorderHover',
		'globalBRadius',
		'globalBRadiusHover',
		'globalBShadow',
		'globalBShadowHover',
		'globalAnim',
		'globalWidth',
		'globalZindex',
		'globalPosition',
		'globalClasses',
		'globalId',
		'globalCustomCss',
		'globalHideDesktop',
		'globalHideTablet',
		'globalHideMobile',
		'tpgbDisrule',
		'gRotte',
		'gRotteHov',
		'gOfset',
		'gOfsetHov',
		'gScle',
		'gScleHov',
		'gSkew',
		'gSkewHov',
		'gFHori',
		'gFVert',
		'gFHoriHov',
		'gFVertHov',
		'gTraDur',
	);
	foreach ( $keys as $k ) {
		if ( ! empty( $attrs[ $k ] ) ) {
			return true;
		}
	}
	return false;
}

// -------------------------------------------------------------------------
// EXECUTE CALLBACK
// -------------------------------------------------------------------------

/**
 * Execute callback: insert a tpgb/tp-form-block block into a post.
 *
 * @param array $input Ability input arguments.
 * @return array|WP_Error Ability result or error on failure.
 */
function tpgb_mcp_add_form_block_ability( array $input ) {

	if ( ! defined( 'TPGB_VERSION' ) && ! defined( 'TPGBP_VERSION' ) ) {
		return new WP_Error( 'nexter_blocks_missing', __( 'Nexter Blocks must be active.', 'the-plus-addons-for-block-editor' ) );
	}

	$block_name = 'tpgb/tp-form-block';
	if ( ! tpgb_mcp_has_registered_block( $block_name ) ) {
		return new WP_Error( 'block_missing', __( 'tpgb/tp-form-block is not registered.', 'the-plus-addons-for-block-editor' ) );
	}

	$post_id  = absint( $input['post_id'] ?? 0 );
	$position = intval( $input['position'] ?? -1 );
	if ( $post_id <= 0 ) {
		return new WP_Error( 'missing_params', __( 'post_id is required.', 'the-plus-addons-for-block-editor' ) ); }

	$post = get_post( $post_id );
	if ( ! $post instanceof WP_Post ) {
		return new WP_Error( 'invalid_post', __( 'Target post not found.', 'the-plus-addons-for-block-editor' ) ); }

	$blocks = tpgb_mcp_get_blocks( $post_id );
	if ( is_wp_error( $blocks ) ) {
		return $blocks; }

	// ---------------------------------------------------------------------
	// Build attributes.
	// ---------------------------------------------------------------------
	$block_id = tpgb_mcp_generate_block_id();
	$attrs    = array( 'block_id' => $block_id );

	/* ── Submit button preset ─────────────────────────────────────────── */
	$has_preset = false;
	$preset_key = sanitize_text_field( $input['buttonPreset'] ?? '' );
	if ( '' !== $preset_key ) {
		$validated = tpgb_mcp_validate_button_preset( $preset_key );
		if ( is_wp_error( $validated ) ) {
			return $validated; }
		tpgb_mcp_apply_button_preset( $attrs, $validated, 'direct' );
		$has_preset = true;
	}

	/* ── Layout ───────────────────────────────────────────────────────── */
	if ( ! empty( $input['layoutType'] ) && 'nxt-style-1' !== $input['layoutType'] ) {
		$attrs['layoutType'] = sanitize_text_field( $input['layoutType'] );
	}
	if ( ! empty( $input['formAlignment'] ) ) {
		$attrs['formAlign'] = array(
			'md' => sanitize_key( $input['formAlignment'] ),
			'sm' => '',
			'xs' => '',
		);
	}
	if ( ! empty( $input['rowGap'] ) ) {
		$attrs['rowGap'] = array(
			'md'   => (string) absint( $input['rowGap'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['columnGap'] ) ) {
		$attrs['columnGap'] = array(
			'md'   => (string) absint( $input['columnGap'] ),
			'unit' => 'px',
		); }
	if ( isset( $input['showRequiredIcon'] ) && ! $input['showRequiredIcon'] ) {
		$attrs['reqIcn'] = false; }
	if ( ! empty( $input['formId'] ) ) {
		$attrs['formId'] = sanitize_text_field( $input['formId'] ); }

	/* ── Email settings ───────────────────────────────────────────────── */
	if ( ! empty( $input['emailTo'] ) ) {
		$attrs['emailTo1'] = sanitize_text_field( $input['emailTo'] ); }
	if ( ! empty( $input['emailSubject'] ) && 'New Form Submission' !== $input['emailSubject'] ) {
		$attrs['subject1'] = sanitize_text_field( $input['emailSubject'] );
	}
	if ( ! empty( $input['emailCc'] ) ) {
		$attrs['ccEmail1'] = sanitize_text_field( $input['emailCc'] ); }
	if ( ! empty( $input['emailBcc'] ) ) {
		$attrs['bccEmail1'] = sanitize_text_field( $input['emailBcc'] ); }
	if ( ! empty( $input['emailHeading'] ) ) {
		$attrs['emailHdg'] = sanitize_text_field( $input['emailHeading'] ); }
	if ( ! empty( $input['fromName'] ) && '[nxt_name]' !== $input['fromName'] ) {
		$attrs['frmNme'] = sanitize_text_field( $input['fromName'] ); }
	if ( ! empty( $input['fromEmail'] ) && '[nxt_email]' !== $input['fromEmail'] ) {
		$attrs['frmEmail'] = sanitize_text_field( $input['fromEmail'] ); }
	if ( ! empty( $input['replyTo'] ) ) {
		$attrs['replyTo'] = sanitize_text_field( $input['replyTo'] ); }
	if ( ! empty( $input['autoResponseMsg'] ) && 'Thank you for your message. It has been sent.' !== $input['autoResponseMsg'] ) {
		$attrs['autoRespMsg'] = sanitize_text_field( $input['autoResponseMsg'] );
	}
	if ( ! empty( $input['failMsg'] ) ) {
		$attrs['failMsg'] = sanitize_text_field( $input['failMsg'] ); }
	if ( ! empty( $input['validationErrorMsg'] ) ) {
		$attrs['valErrMsg'] = sanitize_text_field( $input['validationErrorMsg'] ); }

	/* ── Redirect ─────────────────────────────────────────────────────── */
	if ( ! empty( $input['redirectUrl'] ) ) {
		$attrs['redirect'] = array(
			'url'      => esc_url_raw( $input['redirectUrl'] ),
			'target'   => ( $input['redirectTarget'] ?? '' ) === '_blank' ? '_blank' : '',
			'nofollow' => ! empty( $input['redirectNofollow'] ) ? 'on' : '',
		);
	}

	/* ── Meta data ────────────────────────────────────────────────────── */
	if ( ! empty( $input['collectFormData'] ) ) {
		$attrs['frmDta'] = true; }

	/* ── Label styling ────────────────────────────────────────────────── */
	if ( ! empty( $input['labelColor'] ) ) {
		$attrs['lblClr'] = sanitize_text_field( $input['labelColor'] ); }
	if ( ! empty( $input['labelHoverColor'] ) ) {
		$attrs['hvrLblClr'] = sanitize_text_field( $input['labelHoverColor'] ); }
	if ( ! empty( $input['enableLabelTypo'] ) ) {
		$attrs['lblTypo'] = array(
			'openTypography' => 1,
			'size'           => array(
				'md'   => ! empty( $input['labelTypoSize'] ) ? (string) absint( $input['labelTypoSize'] ) : '',
				'unit' => 'px',
			),
			'height'         => '',
			'spacing'        => '',
			'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
		);
	}
	if ( isset( $input['labelBottomMargin'] ) && 5 !== (int) $input['labelBottomMargin'] ) {
		$attrs['lblBtmMrg'] = array(
			'md'   => (string) absint( $input['labelBottomMargin'] ),
			'unit' => 'px',
		);
	}

	/* ── Input styling ────────────────────────────────────────────────── */
	if ( ! empty( $input['inputColor'] ) ) {
		$attrs['inpClr'] = sanitize_text_field( $input['inputColor'] ); }
	if ( ! empty( $input['placeholderColor'] ) ) {
		$attrs['plcClr'] = sanitize_text_field( $input['placeholderColor'] ); }
	if ( ! empty( $input['hoverPlaceholderColor'] ) ) {
		$attrs['hvrPlc'] = sanitize_text_field( $input['hoverPlaceholderColor'] ); }
	if ( ! empty( $input['inputBgColor'] ) ) {
		$attrs['inpBg'] = sanitize_text_field( $input['inputBgColor'] ); }
	if ( ! empty( $input['hoverInputBgColor'] ) ) {
		$attrs['hvrInpBg'] = sanitize_text_field( $input['hoverInputBgColor'] ); }
	if ( ! empty( $input['inputBorder'] ) ) {
		$attrs['inpBdr'] = tpgb_mcp_frm_border( $input['inputBorder'] ); }
	if ( ! empty( $input['inputBorderRadius'] ) ) {
		$attrs['inpBdrRds'] = tpgb_mcp_frm_radius( $input['inputBorderRadius'] ); }
	if ( ! empty( $input['hoverInputBorderColor'] ) ) {
		$attrs['hvrInpBdrClr'] = sanitize_text_field( $input['hoverInputBorderColor'] ); }
	if ( ! empty( $input['inputPadding'] ) ) {
		$attrs['inpPad'] = tpgb_mcp_frm_spacing( $input['inputPadding'] ); }
	if ( ! empty( $input['enableInputTypo'] ) ) {
		$attrs['inpTypo'] = array(
			'openTypography' => 1,
			'size'           => array(
				'md'   => ! empty( $input['inputTypoSize'] ) ? (string) absint( $input['inputTypoSize'] ) : '',
				'unit' => 'px',
			),
			'height'         => '',
			'spacing'        => '',
			'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
		);
	}
	if ( ! empty( $input['activePlaceholderColor'] ) ) {
		$attrs['actPlc'] = sanitize_text_field( $input['activePlaceholderColor'] ); }
	if ( ! empty( $input['activeInputBgColor'] ) ) {
		$attrs['actInpBg'] = sanitize_text_field( $input['activeInputBgColor'] ); }
	if ( ! empty( $input['activeInputBorderColor'] ) ) {
		$attrs['actInpBdrClr'] = sanitize_text_field( $input['activeInputBorderColor'] ); }

	/* ── Submit button styling ────────────────────────────────────────── */
	if ( ! $has_preset ) {
		if ( ! empty( $input['buttonColor'] ) ) {
			$attrs['btnClr'] = sanitize_text_field( $input['buttonColor'] ); }
		if ( ! empty( $input['hoverButtonColor'] ) ) {
			$attrs['hvrBtnClr'] = sanitize_text_field( $input['hoverButtonColor'] ); }
		if ( ! empty( $input['buttonBgColor'] ) ) {
			$attrs['btnBgClr'] = tpgb_mcp_frm_bg( $input['buttonBgColor'] ); }
		if ( ! empty( $input['hoverButtonBgColor'] ) ) {
			$attrs['hvrBtnBgClr'] = tpgb_mcp_frm_bg( $input['hoverButtonBgColor'] ); }
		if ( ! empty( $input['buttonBorder'] ) ) {
			$attrs['btnBdr'] = tpgb_mcp_frm_border( $input['buttonBorder'] ); }
		if ( ! empty( $input['hoverButtonBorderColor'] ) ) {
			$attrs['hvrBtnBdrClr'] = sanitize_text_field( $input['hoverButtonBorderColor'] ); }
		if ( ! empty( $input['buttonBorderRadius'] ) ) {
			$attrs['btnBdrRds'] = tpgb_mcp_frm_radius( $input['buttonBorderRadius'] ); }
		if ( ! empty( $input['buttonPadding'] ) ) {
			$attrs['btnPad'] = tpgb_mcp_frm_spacing( $input['buttonPadding'] ); }
		if ( ! empty( $input['enableButtonTypo'] ) ) {
			$attrs['btnTypo'] = array(
				'openTypography' => 1,
				'size'           => array(
					'md'   => ! empty( $input['buttonTypoSize'] ) ? (string) absint( $input['buttonTypoSize'] ) : '',
					'unit' => 'px',
				),
				'height'         => '',
				'spacing'        => '',
				'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
			);
		}
	}

	/* ── Checkbox/Radio styling ───────────────────────────────────────── */
	if ( ! empty( $input['checkboxRadioSize'] ) ) {
		$attrs['chkRadSz'] = array(
			'md'   => (string) absint( $input['checkboxRadioSize'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['checkboxRadioBgColor'] ) ) {
		$attrs['chkRadBg'] = tpgb_mcp_frm_bg( $input['checkboxRadioBgColor'] ); }
	if ( ! empty( $input['checkboxRadioBorder'] ) ) {
		$attrs['chkRadBdr'] = tpgb_mcp_frm_border( $input['checkboxRadioBorder'] ); }
	if ( ! empty( $input['checkedBgColor'] ) ) {
		$attrs['chkdChkRadBg'] = tpgb_mcp_frm_bg( $input['checkedBgColor'] ); }
	if ( ! empty( $input['checkedBorderColor'] ) ) {
		$attrs['chkdChkRadBdr'] = tpgb_mcp_frm_border( array( 'color' => $input['checkedBorderColor'] ) ); }
	if ( ! empty( $input['checkedColor'] ) ) {
		$attrs['chkdChkRadClr'] = sanitize_text_field( $input['checkedColor'] ); }
	if ( ! empty( $input['checkboxRadioTextColor'] ) ) {
		$attrs['chkRadTxtClr'] = sanitize_text_field( $input['checkboxRadioTextColor'] ); }
	if ( ! empty( $input['checkboxRadioTextHoverColor'] ) ) {
		$attrs['hvrChkRadTxtClr'] = sanitize_text_field( $input['checkboxRadioTextHoverColor'] ); }
	if ( ! empty( $input['enableCheckboxRadioTypo'] ) ) {
		$attrs['chkRadTxtTypo'] = array(
			'openTypography' => 1,
			'size'           => array(
				'md'   => ! empty( $input['checkboxRadioTypoSize'] ) ? (string) absint( $input['checkboxRadioTypoSize'] ) : '',
				'unit' => 'px',
			),
			'height'         => '',
			'spacing'        => '',
			'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
		);
	}

	/* ── Select styling ───────────────────────────────────────────────── */
	if ( ! empty( $input['selectColor'] ) ) {
		$attrs['selClr'] = sanitize_text_field( $input['selectColor'] ); }
	if ( ! empty( $input['selectBgColor'] ) ) {
		$attrs['selBg'] = tpgb_mcp_frm_bg( $input['selectBgColor'] ); }
	if ( ! empty( $input['selectBorder'] ) ) {
		$attrs['selBdr'] = tpgb_mcp_frm_border( $input['selectBorder'] ); }
	if ( ! empty( $input['selectBorderRadius'] ) ) {
		$attrs['selBdrRds'] = tpgb_mcp_frm_radius( $input['selectBorderRadius'] ); }
	if ( ! empty( $input['hoverSelectColor'] ) ) {
		$attrs['hvrSelClr'] = sanitize_text_field( $input['hoverSelectColor'] ); }
	if ( ! empty( $input['hoverSelectBgColor'] ) ) {
		$attrs['hvrSelBg'] = tpgb_mcp_frm_bg( $input['hoverSelectBgColor'] ); }
	if ( ! empty( $input['hoverSelectBorderColor'] ) ) {
		$attrs['hvrSelBdrClr'] = sanitize_text_field( $input['hoverSelectBorderColor'] ); }
	if ( ! empty( $input['selectPadding'] ) ) {
		$attrs['selPad'] = tpgb_mcp_frm_spacing( $input['selectPadding'] ); }
	if ( ! empty( $input['enableSelectTypo'] ) ) {
		$attrs['selTypo'] = array(
			'openTypography' => 1,
			'size'           => array(
				'md'   => ! empty( $input['selectTypoSize'] ) ? (string) absint( $input['selectTypoSize'] ) : '',
				'unit' => 'px',
			),
			'height'         => '',
			'spacing'        => '',
			'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
		);
	}

	/* ── Description styling ──────────────────────────────────────────── */
	if ( ! empty( $input['descColor'] ) ) {
		$attrs['descColor'] = sanitize_text_field( $input['descColor'] ); }
	if ( ! empty( $input['hoverDescColor'] ) ) {
		$attrs['hvrdescColor'] = sanitize_text_field( $input['hoverDescColor'] ); }
	if ( ! empty( $input['enableDescTypo'] ) ) {
		$attrs['descTypo'] = array(
			'openTypography' => 1,
			'size'           => array(
				'md'   => ! empty( $input['descTypoSize'] ) ? (string) absint( $input['descTypoSize'] ) : '',
				'unit' => 'px',
			),
			'height'         => '',
			'spacing'        => '',
			'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
		);
	}

	/* ── Success message styling ──────────────────────────────────────── */
	if ( ! empty( $input['successColor'] ) ) {
		$attrs['sucClr'] = sanitize_text_field( $input['successColor'] ); }
	if ( ! empty( $input['enableSuccessTypo'] ) ) {
		$attrs['sucTypo'] = array(
			'openTypography' => 1,
			'size'           => array(
				'md'   => ! empty( $input['successTypoSize'] ) ? (string) absint( $input['successTypoSize'] ) : '',
				'unit' => 'px',
			),
			'height'         => '',
			'spacing'        => '',
			'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
		);
	}

	/* ── Loader ───────────────────────────────────────────────────────── */
	if ( ! empty( $input['loaderColor'] ) ) {
		$attrs['ldrClr'] = sanitize_text_field( $input['loaderColor'] ); }

	/* ── Scroll animation ─────────────────────────────────────────────── */
	if ( ! empty( $input['scrollAnimation'] ) ) {
		$attrs['globalAnim'] = array( 'md' => sanitize_text_field( $input['scrollAnimation'] ) ); }
	if ( ! empty( $input['animDuration'] ) ) {
		$attrs['globalAnimDuration'] = sanitize_text_field( $input['animDuration'] ); }
	if ( ! empty( $input['animDelay'] ) ) {
		$attrs['globalAnimDelay'] = array( 'md' => sanitize_text_field( $input['animDelay'] ) ); }
	if ( ! empty( $input['animEasing'] ) ) {
		$attrs['globalAnimEasing'] = sanitize_text_field( $input['animEasing'] ); }

	/* ── Visibility ───────────────────────────────────────────────────── */
	if ( ! empty( $input['hideDesktop'] ) ) {
		$attrs['globalHideDesktop'] = true; }
	if ( ! empty( $input['hideTablet'] ) ) {
		$attrs['globalHideTablet'] = true; }
	if ( ! empty( $input['hideMobile'] ) ) {
		$attrs['globalHideMobile'] = true; }

	/* ── Identity ─────────────────────────────────────────────────────── */
	if ( ! empty( $input['globalClasses'] ) ) {
		$attrs['globalClasses'] = sanitize_text_field( $input['globalClasses'] ); }
	if ( ! empty( $input['globalId'] ) ) {
		$attrs['globalId'] = sanitize_text_field( $input['globalId'] ); }
	if ( ! empty( $input['globalCustomCss'] ) ) {
		$attrs['globalCustomCss'] = wp_strip_all_tags( $input['globalCustomCss'] ); }

	/* ── Layout ───────────────────────────────────────────────────────── */
	if ( ! empty( $input['globalWidth'] ) ) {
		$attrs['globalWidth'] = sanitize_text_field( $input['globalWidth'] ); }
	if ( ! empty( $input['globalZindex'] ) ) {
		$attrs['globalZindex'] = sanitize_text_field( $input['globalZindex'] ); }
	if ( ! empty( $input['globalPosition'] ) ) {
		$attrs['globalPosition'] = array(
			'md' => sanitize_text_field( $input['globalPosition'] ),
			'sm' => '',
			'xs' => '',
		); }

	/* ── Transition ───────────────────────────────────────────────────── */
	if ( ! empty( $input['transitionDuration'] ) ) {
		$attrs['gTraDur'] = sanitize_text_field( $input['transitionDuration'] ); }
	if ( ! empty( $input['transitionFunction'] ) ) {
		$attrs['gTraFunc'] = sanitize_text_field( $input['transitionFunction'] ); }
	if ( ! empty( $input['transitionOrigin'] ) ) {
		$attrs['gTraOrigin'] = sanitize_text_field( $input['transitionOrigin'] );   }

	/* ── Global: Spacing/Bg/Border/Shadow ─────────────────────────────── */
	if ( ! empty( $input['globalMargin'] ) ) {
		$attrs['globalMargin'] = tpgb_mcp_frm_spacing( $input['globalMargin'] );  }
	if ( ! empty( $input['globalPadding'] ) ) {
		$attrs['globalPadding'] = tpgb_mcp_frm_spacing( $input['globalPadding'] ); }
	if ( ! empty( $input['globalBgColor'] ) ) {
		$attrs['globalBg'] = tpgb_mcp_frm_bg( $input['globalBgColor'] );      }
	if ( ! empty( $input['globalBgHoverColor'] ) ) {
		$attrs['globalBgHover'] = tpgb_mcp_frm_bg( $input['globalBgHoverColor'] ); }
	if ( ! empty( $input['globalBorder'] ) ) {
		$attrs['globalBorder'] = tpgb_mcp_frm_border( $input['globalBorder'] );       }
	if ( ! empty( $input['globalBorderHover'] ) ) {
		$attrs['globalBorderHover'] = tpgb_mcp_frm_border( $input['globalBorderHover'] );  }
	if ( ! empty( $input['globalBRadius'] ) ) {
		$attrs['globalBRadius'] = tpgb_mcp_frm_radius( $input['globalBRadius'] );      }
	if ( ! empty( $input['globalBRadiusHover'] ) ) {
		$attrs['globalBRadiusHover'] = tpgb_mcp_frm_radius( $input['globalBRadiusHover'] ); }
	if ( ! empty( $input['globalBShadow'] ) ) {
		$attrs['globalBShadow'] = tpgb_mcp_frm_bshadow( $input['globalBShadow'] );      }
	if ( ! empty( $input['globalBShadowHover'] ) ) {
		$attrs['globalBShadowHover'] = tpgb_mcp_frm_bshadow( $input['globalBShadowHover'] ); }

	/* ── Transforms ───────────────────────────────────────────────────── */
	if ( ! empty( $input['rotateDeg'] ) || ! empty( $input['rotateX'] ) || ! empty( $input['rotateY'] ) || ! empty( $input['rotatePerspective'] ) ) {
		$attrs['gRotte'] = array(
			'tpgbReset'         => 1,
			'rotateToogle'      => false,
			'gRotteDeg'         => array( 'md' => sanitize_text_field( $input['rotateDeg'] ?? '0' ) ),
			'gRotteX'           => array( 'md' => sanitize_text_field( $input['rotateX'] ?? '' ) ),
			'gRotteY'           => array( 'md' => sanitize_text_field( $input['rotateY'] ?? '' ) ),
			'globalPerspective' => array( 'md' => sanitize_text_field( $input['rotatePerspective'] ?? '' ) ),
		);
	}
	if ( ! empty( $input['rotateDegHover'] ) || ! empty( $input['rotateXHover'] ) || ! empty( $input['rotateYHover'] ) || ! empty( $input['rotatePersHover'] ) ) {
		$attrs['gRotteHov'] = array(
			'tpgbReset'    => 1,
			'rToggleHov'   => true,
			'gRotteDegHov' => array( 'md' => sanitize_text_field( $input['rotateDegHover'] ?? '0' ) ),
			'gRotteXHov'   => array( 'md' => sanitize_text_field( $input['rotateXHover'] ?? '' ) ),
			'gRotteYHov'   => array( 'md' => sanitize_text_field( $input['rotateYHover'] ?? '' ) ),
			'gPersHov'     => array( 'md' => sanitize_text_field( $input['rotatePersHover'] ?? '' ) ),
		);
	}
	if ( ! empty( $input['offsetX'] ) || ! empty( $input['offsetY'] ) || ! empty( $input['offsetZ'] ) ) {
		$attrs['gOfset'] = array(
			'tpgbReset' => 1,
			'gOfsetX'   => array(
				'md'   => sanitize_text_field( $input['offsetX'] ?? '0' ),
				'unit' => 'px',
			),
			'gOfsetY'   => array(
				'md'   => sanitize_text_field( $input['offsetY'] ?? '0' ),
				'unit' => 'px',
			),
			'gOfsetZ'   => array(
				'md'   => sanitize_text_field( $input['offsetZ'] ?? '' ),
				'unit' => 'px',
			),
		);
	}
	if ( ! empty( $input['offsetXHover'] ) || ! empty( $input['offsetYHover'] ) || ! empty( $input['offsetZHover'] ) ) {
		$attrs['gOfsetHov'] = array(
			'tpgbReset'  => 1,
			'gOfsetXHov' => array(
				'md'   => sanitize_text_field( $input['offsetXHover'] ?? '0' ),
				'unit' => 'px',
			),
			'gOfsetYHov' => array(
				'md'   => sanitize_text_field( $input['offsetYHover'] ?? '0' ),
				'unit' => 'px',
			),
			'gOfsetZHov' => array(
				'md'   => sanitize_text_field( $input['offsetZHover'] ?? '0' ),
				'unit' => 'px',
			),
		);
	}
	if ( ! empty( $input['scaleValue'] ) && '1' !== $input['scaleValue'] ) {
		$attrs['gScle'] = array(
			'tpgbReset'       => 1,
			'keepProportions' => $input['scaleKeepRatio'] ?? true,
			'gScleValue'      => array( 'md' => sanitize_text_field( $input['scaleValue'] ) ),
			'gScleX'          => array( 'md' => '' ),
			'gScleY'          => array( 'md' => '' ),
		);
	}
	if ( ! empty( $input['scaleValueHover'] ) && '1' !== $input['scaleValueHover'] ) {
		$attrs['gScleHov'] = array(
			'tpgbReset'     => 1,
			'keepPropHov'   => true,
			'gScleValueHov' => array( 'md' => sanitize_text_field( $input['scaleValueHover'] ) ),
			'gScleXHov'     => array( 'md' => '' ),
			'gScleYHov'     => array( 'md' => '' ),
		);
	}
	if ( ! empty( $input['skewX'] ) || ! empty( $input['skewY'] ) ) {
		$attrs['gSkew'] = array(
			'tpgbReset' => 1,
			'gSkewX'    => array( 'md' => sanitize_text_field( $input['skewX'] ?? '0' ) ),
			'gSkewY'    => array( 'md' => sanitize_text_field( $input['skewY'] ?? '0' ) ),
		);
	}
	if ( ! empty( $input['skewXHover'] ) || ! empty( $input['skewYHover'] ) ) {
		$attrs['gSkewHov'] = array(
			'tpgbReset' => 1,
			'gSkewXHov' => array( 'md' => sanitize_text_field( $input['skewXHover'] ?? '0' ) ),
			'gSkewYHov' => array( 'md' => sanitize_text_field( $input['skewYHover'] ?? '0' ) ),
		);
	}
	if ( ! empty( $input['flipHorizontal'] ) ) {
		$attrs['gFHori'] = true; }
	if ( ! empty( $input['flipVertical'] ) ) {
		$attrs['gFVert'] = true; }
	if ( ! empty( $input['flipHorizontalHover'] ) ) {
		$attrs['gFHoriHov'] = true; }
	if ( ! empty( $input['flipVerticalHover'] ) ) {
		$attrs['gFVertHov'] = true; }

	/* ── tpgbDisrule ──────────────────────────────────────────────────── */
	if ( tpgb_mcp_frm_needs_wrapper( $attrs ) ) {
		$attrs['tpgbDisrule'] = true; }

	/* ── Raw settings override ────────────────────────────────────────── */
	tpgb_mcp_apply_typo_decoration( $attrs, $input );
	$attrs = tpgb_mcp_merge_block_settings( $attrs, $input['settings'] ?? array() );

	/* ── Build, insert, save (dynamic block with inner blocks support) ── */
	$block                = tpgb_mcp_build_block( $block_name, $attrs );
	$block['innerBlocks'] = array();

	$parent_id = ! empty( $input['parent_block_id'] ) ? sanitize_text_field( $input['parent_block_id'] ) : '';
	if ( '' !== $parent_id ) {
		if ( ! tpgb_mcp_insert_inner_block( $blocks, $parent_id, $block, $position ) ) {
			return new WP_Error( 'parent_not_found', __( 'Parent block not found.', 'the-plus-addons-for-block-editor' ) );
		}
	} else {
		tpgb_mcp_insert_block( $blocks, $block, $position );
	}

	$save_result = tpgb_mcp_save_blocks( $post_id, $blocks );
	if ( is_wp_error( $save_result ) ) {
		return $save_result; }

	return array(
		'block_id'   => $block_id,
		'block_name' => $block_name,
		'post_id'    => $post_id,
	);
}
