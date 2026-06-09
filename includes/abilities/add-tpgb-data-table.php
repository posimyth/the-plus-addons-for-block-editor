<?php
/**
 * Ability: Add Nexter Blocks Data Table (tpgb/tp-data-table) to a Gutenberg page.
 *
 * @package The_Plus_Addons_For_Block_Editor
 * @since   1.3.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_register_ability(
	'nexter-blocks/add-tpgb-data-table',
	array(
		'label'               => __( 'Add Nexter Blocks Data Table', 'the-plus-addons-for-block-editor' ),
		'description'         => __(
			'Adds the Nexter Blocks Data Table block (tpgb/tp-data-table) — a feature-rich table with custom headers and body rows, search, sort, filter, mobile responsive modes, alignment per cell, stripe effect, per-state colours (normal/row-hover/cell-hover) for both header and body, borders, button cells, icon/image cells, and full styling controls. This is a dynamic block.

    IMPORTANT: Pass `headers` as a simple array of column header strings and `rows` as a 2D array where each inner array is a row of cell values. The ability will automatically build the required internal structure with row/cell markers.',
			'the-plus-addons-for-block-editor'
		),
		'category'            => 'nexter-blocks',

		'input_schema'        => array(
			'type'                 => 'object',
			'properties'           => array(

				/* ── Core ─────────────────────────────────────────────────── */
				'post_id'                  => array(
					'type'        => 'integer',
					'description' => 'WordPress page/post ID to insert the block into.',
				),
				'position'                 => array(
					'type'        => 'integer',
					'default'     => -1,
					'description' => 'Zero-based insert position. Use -1 to append.',
				),
				'parent_block_id'          => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Optional parent container block_id for nesting.',
				),

				/* ── Content ──────────────────────────────────────────────── */
				'contentType'              => array(
					'type'        => 'string',
					'enum'        => array( 'custom', 'dynamic' ),
					'description' => 'Content source. "custom" = manually defined rows; "dynamic" = fetched from post/query (Pro).',
					'default'     => 'custom',
				),
				'headers'                  => array(
					'type'        => 'array',
					'description' => 'Array of column header strings e.g. ["ID", "Name", "Email"]. If omitted, defaults to ["ID", "Title 1", "Title 2"].',
					'items'       => array( 'type' => 'string' ),
				),
				'rows'                     => array(
					'type'        => 'array',
					'description' => '2D array of row data. Each inner array is a row of cell values e.g. [["1","John","john@x.com"],["2","Jane","jane@x.com"]]. Cell count should match headers count.',
					'items'       => array(
						'type'  => 'array',
						'items' => array( 'type' => 'string' ),
					),
				),

				/* ── Features ─────────────────────────────────────────────── */
				'enableSearch'             => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Enable search filter.',
				),
				'enableSort'               => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Enable column sorting.',
				),
				'enableFilter'             => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Enable column filters.',
				),
				'mobileMode'               => array(
					'type'        => 'string',
					'enum'        => array( 'swipe', 'stack', 'collapse' ),
					'description' => 'How the table responds on mobile. "swipe" = horizontal scroll; "stack" = stack cells; "collapse" = collapse columns.',
					'default'     => 'swipe',
				),

				/* ── Header styling ───────────────────────────────────────── */
				'headerAlignment'          => array(
					'type'    => 'string',
					'enum'    => array( 'left', 'center', 'right' ),
					'default' => 'left',
				),
				'enableHeaderTypo'         => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'headerTypoSize'           => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'headerPadding'            => array(
					'type'        => 'object',
					'description' => 'Header cell padding {top,right,bottom,left,unit}.',
				),
				'headerTextColor'          => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Header text colour (normal).',
				),
				'headerBgColor'            => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Header background colour (normal).',
				),
				'headerHoverTextColor'     => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Header text colour (row hover).',
				),
				'headerHoverBgColor'       => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Header background colour (row hover).',
				),
				'headerCellHoverTextColor' => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Header text colour (cell hover).',
				),
				'headerCellHoverBgColor'   => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Header background colour (cell hover).',
				),
				'enableHeaderBorderAll'    => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Apply border to all sides of header cells.',
				),
				'headerBorder'             => array(
					'type'        => 'object',
					'description' => 'Header border {type,color,width}.',
				),

				/* ── Body styling ─────────────────────────────────────────── */
				'bodyAlignment'            => array(
					'type'    => 'string',
					'enum'    => array( 'left', 'center', 'right' ),
					'default' => 'left',
				),
				'bodyVAlignment'           => array(
					'type'    => 'string',
					'enum'    => array( '', 'top', 'middle', 'bottom' ),
					'default' => '',
				),
				'enableBodyTypo'           => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'bodyTypoSize'             => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'bodyPadding'              => array(
					'type'        => 'object',
					'description' => 'Body cell padding {top,right,bottom,left,unit}.',
				),
				'bodyTextColor'            => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Body text colour.',
				),
				'enableStripeEffect'       => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Enable alternating row stripes.',
				),
				'bodyStripeBgColor'        => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Stripe background colour.',
				),
				'enableBodyBorderAll'      => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'bodyBorder'               => array(
					'type'        => 'object',
					'description' => 'Body border {type,color,width}.',
				),
				'bodyRowHoverTextColor'    => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Body text colour (row hover).',
				),
				'bodyRowHoverBgColor'      => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Body background colour (row hover).',
				),
				'bodyCellHoverTextColor'   => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Body text colour (cell hover).',
				),
				'bodyCellHoverBgColor'     => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Body background colour (cell hover).',
				),

				/* ── Button cell styling ──────────────────────────────────── */
				'enableBtnTypo'            => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'btnTypoSize'              => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'btnPadding'               => array(
					'type'        => 'object',
					'description' => 'Button padding.',
				),
				'btnWidth'                 => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Button width in px.',
				),
				'btnTextColor'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'btnBgColor'               => array(
					'type'    => 'string',
					'default' => '',
				),
				'btnHoverTextColor'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'btnHoverBgColor'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'btnHoverBorderColor'      => array(
					'type'    => 'string',
					'default' => '',
				),
				'btnIconColor'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'btnIconHoverColor'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'btnIconSize'              => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'btnIconSpacing'           => array(
					'type'    => 'integer',
					'default' => 0,
				),

				/* ── Icon/Image cell settings ─────────────────────────────── */
				'iconColor'                => array(
					'type'    => 'string',
					'default' => '',
				),
				'iconSize'                 => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'iconPosition'             => array(
					'type'    => 'string',
					'enum'    => array( 'left', 'right' ),
					'default' => 'left',
				),
				'iconSpacing'              => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'imgSize'                  => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'imgPosition'              => array(
					'type'    => 'string',
					'enum'    => array( 'left', 'right' ),
					'default' => 'left',
				),
				'imgSpacing'               => array(
					'type'    => 'integer',
					'default' => 0,
				),

				/* ── Toolbar styling ──────────────────────────────────────── */
				'toolbarMargin'            => array( 'type' => 'object' ),
				'toolbarPadding'           => array( 'type' => 'object' ),
				'toolbarBgColor'           => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Scroll animation ────────────────────────────────────── */
				'scrollAnimation'          => array(
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
				'animDuration'             => array(
					'type'    => 'string',
					'enum'    => array( '', 'slow', 'normal', 'fast' ),
					'default' => '',
				),
				'animDelay'                => array(
					'type'    => 'string',
					'default' => '',
				),
				'animEasing'               => array(
					'type'    => 'string',
					'enum'    => array( '', 'linear', 'ease', 'ease-in', 'ease-out', 'ease-in-out' ),
					'default' => '',
				),

				/* ── Advanced ─────────────────────────────────────────────── */
				'hideDesktop'              => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'hideTablet'               => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'hideMobile'               => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'globalClasses'            => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalId'                 => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalCustomCss'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalWidth'              => array(
					'type'    => 'string',
					'enum'    => array( '', 'inline', 'full', 'custom' ),
					'default' => '',
				),
				'globalZindex'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalPosition'           => array(
					'type'    => 'string',
					'enum'    => array( '', 'relative', 'absolute', 'fixed', 'sticky' ),
					'default' => '',
				),
				'transitionDuration'       => array(
					'type'    => 'string',
					'default' => '',
				),
				'transitionFunction'       => array(
					'type'    => 'string',
					'enum'    => array( '', 'ease', 'ease-in', 'ease-out', 'ease-in-out', 'linear' ),
					'default' => '',
				),
				'transitionOrigin'         => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalMargin'             => array( 'type' => 'object' ),
				'globalPadding'            => array( 'type' => 'object' ),
				'globalBgColor'            => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalBgHoverColor'       => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalBorder'             => array( 'type' => 'object' ),
				'globalBorderHover'        => array( 'type' => 'object' ),
				'globalBRadius'            => array( 'type' => 'object' ),
				'globalBRadiusHover'       => array( 'type' => 'object' ),
				'globalBShadow'            => array( 'type' => 'object' ),
				'globalBShadowHover'       => array( 'type' => 'object' ),

				/* ── Transforms ──────────────────────────────────────────── */
				'rotateDeg'                => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotateX'                  => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotateY'                  => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotatePerspective'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotateDegHover'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotateXHover'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotateYHover'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotatePersHover'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'offsetX'                  => array(
					'type'    => 'string',
					'default' => '',
				),
				'offsetY'                  => array(
					'type'    => 'string',
					'default' => '',
				),
				'offsetZ'                  => array(
					'type'    => 'string',
					'default' => '',
				),
				'offsetXHover'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'offsetYHover'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'offsetZHover'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'scaleValue'               => array(
					'type'    => 'string',
					'default' => '',
				),
				'scaleKeepRatio'           => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'scaleValueHover'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'skewX'                    => array(
					'type'    => 'string',
					'default' => '',
				),
				'skewY'                    => array(
					'type'    => 'string',
					'default' => '',
				),
				'skewXHover'               => array(
					'type'    => 'string',
					'default' => '',
				),
				'skewYHover'               => array(
					'type'    => 'string',
					'default' => '',
				),
				'flipHorizontal'           => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'flipVertical'             => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'flipHorizontalHover'      => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'flipVerticalHover'        => array(
					'type'    => 'boolean',
					'default' => false,
				),

				'settings'                 => array(
					'type'        => 'object',
					'description' => 'Raw attribute overrides merged directly into the block.',
				),
				'fontFamily'               => array(
					'type'        => 'string',
					'description' => 'Font family name (e.g. "Inter", "Roboto", "Playfair Display"). When inspecting a URL via nexter-blocks/inspect-page, pass the returned fonts[].family value verbatim.',
					'default'     => '',
				),
				'fontType'                 => array(
					'type'        => 'string',
					'description' => 'Font category — "sans-serif", "serif", "display", "handwriting", or "monospace".',
					'default'     => '',
				),
				'customFont'               => array(
					'type'        => 'string',
					'description' => 'Custom (non-Google) font name. Overrides fontFamily.',
					'default'     => '',
				),
				'fontWeight'               => array(
					'type'        => 'string',
					'description' => 'Font weight as a string ("100"..."900"). Embedded inside every typography group\'s fontFamily.fontWeight on this block. For per-group control, use the settings raw override or sprout/update-element.',
					'default'     => '',
				),
				'textDecoration'           => array(
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

		'execute_callback'    => 'tpgb_mcp_add_data_table_ability',
		'permission_callback' => 'tpgb_mcp_add_data_table_permission',
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
 * Permission callback for the add-data-table ability.
 *
 * @param array|null $input Ability input arguments.
 * @return bool True when the current user may insert the block.
 */
function tpgb_mcp_add_data_table_permission( ?array $input = null ): bool {
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
function tpgb_mcp_dtbl_spacing( array $v ): array {
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
function tpgb_mcp_dtbl_border( array $b ): array {
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
function tpgb_mcp_dtbl_radius( array $r ): array {
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
function tpgb_mcp_dtbl_bshadow( array $s ): array {
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
function tpgb_mcp_dtbl_bg( string $color ): array {
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
function tpgb_mcp_dtbl_needs_wrapper( array $attrs ): bool {
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

/**
 * Build TableHeader array from simple headers array.
 * Format: [{thAction: 'row'}, {thAction: 'cell', thtext: 'Col1'}, ...]
 *
 * @param array $headers List of header cell texts.
 * @return array TableHeader attribute structured for the block.
 */
function tpgb_mcp_dtbl_build_headers( array $headers ): array {
	$result = array(
		array(
			'_key'     => 'r1',
			'thAction' => 'row',
		),
	);
	foreach ( $headers as $i => $h ) {
		$result[] = array(
			'_key'        => 'r' . ( $i + 2 ),
			'thAction'    => 'cell',
			'thtext'      => sanitize_text_field( (string) $h ),
			'resColWidth' => false,
		);
	}
	return $result;
}

/**
 * Build Tablebody array from 2D rows array.
 * Format: [{trAction:'row',TrLink:{url:''}}, {trAction:'cell',trtext:'Val', TrLink:{url:''}}, ...]
 *
 * @param array $rows 2D array of body rows; each row is a list of cell values.
 * @return array Tablebody attribute structured for the block.
 */
function tpgb_mcp_dtbl_build_body( array $rows ): array {
	$result = array();
	$key    = 0;
	foreach ( $rows as $row ) {
		$result[] = array(
			'_key'     => (string) $key,
			'trAction' => 'row',
			'TrLink'   => array( 'url' => '' ),
		);
		++$key;
		foreach ( $row as $cell ) {
			$result[] = array(
				'_key'      => (string) $key,
				'trAction'  => 'cell',
				'trtext'    => tpgb_mcp_clean_text( (string) $cell ),
				'TrLink'    => array( 'url' => '' ),
				'TrbtnLink' => array( 'url' => '' ),
			);
			++$key;
		}
	}
	return $result;
}

// -------------------------------------------------------------------------
// EXECUTE CALLBACK
// -------------------------------------------------------------------------

/**
 * Execute callback: insert a tpgb/tp-data-table block into a post.
 *
 * @param array $input Ability input arguments.
 * @return array|WP_Error Ability result or error on failure.
 */
function tpgb_mcp_add_data_table_ability( array $input ) {

	if ( ! defined( 'TPGB_VERSION' ) && ! defined( 'TPGBP_VERSION' ) ) {
		return new WP_Error( 'nexter_blocks_missing', __( 'Nexter Blocks must be active.', 'the-plus-addons-for-block-editor' ) );
	}

	$block_name = 'tpgb/tp-data-table';
	if ( ! tpgb_mcp_has_registered_block( $block_name ) ) {
		return new WP_Error( 'block_missing', __( 'tpgb/tp-data-table is not registered.', 'the-plus-addons-for-block-editor' ) );
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

	/* ── Content ──────────────────────────────────────────────────────── */
	if ( ! empty( $input['contentType'] ) && 'custom' !== $input['contentType'] ) {
		$attrs['ContentTable'] = sanitize_key( $input['contentType'] );
	}

	if ( ! empty( $input['headers'] ) && is_array( $input['headers'] ) ) {
		$attrs['TableHeader'] = tpgb_mcp_dtbl_build_headers( $input['headers'] );
	}

	if ( ! empty( $input['rows'] ) && is_array( $input['rows'] ) ) {
		$attrs['Tablebody'] = tpgb_mcp_dtbl_build_body( $input['rows'] );
	}

	/* ── Features ─────────────────────────────────────────────────────── */
	if ( ! empty( $input['enableSearch'] ) ) {
		$attrs['TbSearch'] = true; }
	if ( ! empty( $input['enableSort'] ) ) {
		$attrs['TbSort'] = true; }
	if ( ! empty( $input['enableFilter'] ) ) {
		$attrs['TbFilter'] = true; }
	if ( ! empty( $input['mobileMode'] ) && 'swipe' !== $input['mobileMode'] ) {
		$attrs['MResponsive'] = sanitize_key( $input['mobileMode'] );
	}

	/* ── Header styling ───────────────────────────────────────────────── */
	if ( ! empty( $input['headerAlignment'] ) && 'left' !== $input['headerAlignment'] ) {
		$attrs['ThAlignment'] = array(
			'md' => sanitize_text_field( $input['headerAlignment'] ),
			'sm' => '',
			'xs' => '',
		);
	}
	if ( ! empty( $input['enableHeaderTypo'] ) ) {
		$attrs['ThTypo'] = array(
			'openTypography' => 1,
			'size'           => array(
				'md'   => ! empty( $input['headerTypoSize'] ) ? (string) absint( $input['headerTypoSize'] ) : '',
				'unit' => 'px',
			),
			'height'         => '',
			'spacing'        => '',
			'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
		);
	}
	if ( ! empty( $input['headerPadding'] ) ) {
		$attrs['ThPadding'] = tpgb_mcp_dtbl_spacing( $input['headerPadding'] ); }
	if ( ! empty( $input['headerTextColor'] ) ) {
		$attrs['ThRTxCr'] = sanitize_text_field( $input['headerTextColor'] ); }
	if ( ! empty( $input['headerBgColor'] ) ) {
		$attrs['ThRBgCr'] = sanitize_text_field( $input['headerBgColor'] ); }
	if ( ! empty( $input['headerHoverTextColor'] ) ) {
		$attrs['ThHTxCr'] = sanitize_text_field( $input['headerHoverTextColor'] ); }
	if ( ! empty( $input['headerHoverBgColor'] ) ) {
		$attrs['ThHBgCr'] = sanitize_text_field( $input['headerHoverBgColor'] ); }
	if ( ! empty( $input['headerCellHoverTextColor'] ) ) {
		$attrs['ThHCellCr'] = sanitize_text_field( $input['headerCellHoverTextColor'] ); }
	if ( ! empty( $input['headerCellHoverBgColor'] ) ) {
		$attrs['ThHCellBGCr'] = sanitize_text_field( $input['headerCellHoverBgColor'] ); }
	if ( ! empty( $input['enableHeaderBorderAll'] ) ) {
		$attrs['ThABorder'] = true; }
	if ( ! empty( $input['headerBorder'] ) ) {
		$attrs['ThBorderType'] = tpgb_mcp_dtbl_border( $input['headerBorder'] ); }

	/* ── Body styling ─────────────────────────────────────────────────── */
	if ( ! empty( $input['bodyAlignment'] ) && 'left' !== $input['bodyAlignment'] ) {
		$attrs['TBAlignment'] = array(
			'md' => sanitize_text_field( $input['bodyAlignment'] ),
			'sm' => '',
			'xs' => '',
		);
	}
	if ( ! empty( $input['bodyVAlignment'] ) ) {
		$attrs['TBvAlignment'] = sanitize_text_field( $input['bodyVAlignment'] ); }
	if ( ! empty( $input['enableBodyTypo'] ) ) {
		$attrs['TBTypo'] = array(
			'openTypography' => 1,
			'size'           => array(
				'md'   => ! empty( $input['bodyTypoSize'] ) ? (string) absint( $input['bodyTypoSize'] ) : '',
				'unit' => 'px',
			),
			'height'         => '',
			'spacing'        => '',
			'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
		);
	}
	if ( ! empty( $input['bodyPadding'] ) ) {
		$attrs['TBPadding'] = tpgb_mcp_dtbl_spacing( $input['bodyPadding'] ); }
	if ( ! empty( $input['bodyTextColor'] ) ) {
		$attrs['TBrTxCr'] = sanitize_text_field( $input['bodyTextColor'] ); }
	if ( ! empty( $input['enableStripeEffect'] ) ) {
		$attrs['TBStripEff'] = true;
		if ( ! empty( $input['bodyStripeBgColor'] ) ) {
			$attrs['TBbgCR'] = sanitize_text_field( $input['bodyStripeBgColor'] ); }
	}
	if ( ! empty( $input['enableBodyBorderAll'] ) ) {
		$attrs['TBABorder'] = true; }
	if ( ! empty( $input['bodyBorder'] ) ) {
		$attrs['TBborder'] = tpgb_mcp_dtbl_border( $input['bodyBorder'] ); }
	if ( ! empty( $input['bodyRowHoverTextColor'] ) ) {
		$attrs['TBhRTxCr'] = sanitize_text_field( $input['bodyRowHoverTextColor'] ); }
	if ( ! empty( $input['bodyRowHoverBgColor'] ) ) {
		$attrs['TBhRBGCr'] = sanitize_text_field( $input['bodyRowHoverBgColor'] ); }
	if ( ! empty( $input['bodyCellHoverTextColor'] ) ) {
		$attrs['TBHcellCr'] = sanitize_text_field( $input['bodyCellHoverTextColor'] ); }
	if ( ! empty( $input['bodyCellHoverBgColor'] ) ) {
		$attrs['TBHcellBGCr'] = sanitize_text_field( $input['bodyCellHoverBgColor'] ); }

	/* ── Button cell styling ──────────────────────────────────────────── */
	if ( ! empty( $input['enableBtnTypo'] ) ) {
		$attrs['BtnTypo'] = array(
			'openTypography' => 1,
			'size'           => array(
				'md'   => ! empty( $input['btnTypoSize'] ) ? (string) absint( $input['btnTypoSize'] ) : '',
				'unit' => 'px',
			),
			'height'         => '',
			'spacing'        => '',
			'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
		);
	}
	if ( ! empty( $input['btnPadding'] ) ) {
		$attrs['BtnPadding'] = tpgb_mcp_dtbl_spacing( $input['btnPadding'] ); }
	if ( ! empty( $input['btnWidth'] ) ) {
		$attrs['Btnwidth'] = array(
			'md'   => (string) absint( $input['btnWidth'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['btnTextColor'] ) ) {
		$attrs['BtnNtxcr'] = sanitize_text_field( $input['btnTextColor'] ); }
	if ( ! empty( $input['btnBgColor'] ) ) {
		$attrs['BtnNcr'] = tpgb_mcp_dtbl_bg( $input['btnBgColor'] ); }
	if ( ! empty( $input['btnHoverTextColor'] ) ) {
		$attrs['BtnHtxcr'] = sanitize_text_field( $input['btnHoverTextColor'] ); }
	if ( ! empty( $input['btnHoverBgColor'] ) ) {
		$attrs['BtnHcr'] = tpgb_mcp_dtbl_bg( $input['btnHoverBgColor'] ); }
	if ( ! empty( $input['btnHoverBorderColor'] ) ) {
		$attrs['BtnHBcr'] = sanitize_text_field( $input['btnHoverBorderColor'] ); }
	if ( ! empty( $input['btnIconColor'] ) ) {
		$attrs['btnIconColor'] = sanitize_text_field( $input['btnIconColor'] ); }
	if ( ! empty( $input['btnIconHoverColor'] ) ) {
		$attrs['hoverBtnIconColor'] = sanitize_text_field( $input['btnIconHoverColor'] ); }
	if ( ! empty( $input['btnIconSize'] ) ) {
		$attrs['btnIconSize'] = array(
			'md'   => (string) absint( $input['btnIconSize'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['btnIconSpacing'] ) ) {
		$attrs['btnIconSpacing'] = array(
			'md'   => (string) absint( $input['btnIconSpacing'] ),
			'unit' => 'px',
		); }

	/* ── Icon/Image cell settings ─────────────────────────────────────── */
	if ( ! empty( $input['iconColor'] ) ) {
		$attrs['IconColor'] = sanitize_text_field( $input['iconColor'] ); }
	if ( ! empty( $input['iconSize'] ) ) {
		$attrs['IconSize'] = array(
			'md'   => (string) absint( $input['iconSize'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['iconPosition'] ) && 'left' !== $input['iconPosition'] ) {
		$attrs['IconPosition'] = sanitize_key( $input['iconPosition'] ); }
	if ( ! empty( $input['iconSpacing'] ) ) {
		$attrs['IconSpacing'] = array(
			'md'   => (string) absint( $input['iconSpacing'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['imgSize'] ) ) {
		$attrs['ImgSize'] = array(
			'md'   => (string) absint( $input['imgSize'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['imgPosition'] ) && 'left' !== $input['imgPosition'] ) {
		$attrs['ImgPosition'] = sanitize_key( $input['imgPosition'] ); }
	if ( ! empty( $input['imgSpacing'] ) ) {
		$attrs['ImgSpacing'] = array(
			'md'   => (string) absint( $input['imgSpacing'] ),
			'unit' => 'px',
		); }

	/* ── Toolbar styling ──────────────────────────────────────────────── */
	if ( ! empty( $input['toolbarMargin'] ) ) {
		$attrs['ToMargin'] = tpgb_mcp_dtbl_spacing( $input['toolbarMargin'] ); }
	if ( ! empty( $input['toolbarPadding'] ) ) {
		$attrs['ToPadding'] = tpgb_mcp_dtbl_spacing( $input['toolbarPadding'] ); }
	if ( ! empty( $input['toolbarBgColor'] ) ) {
		$attrs['Tobg'] = tpgb_mcp_dtbl_bg( $input['toolbarBgColor'] ); }

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
		$attrs['globalMargin'] = tpgb_mcp_dtbl_spacing( $input['globalMargin'] );  }
	if ( ! empty( $input['globalPadding'] ) ) {
		$attrs['globalPadding'] = tpgb_mcp_dtbl_spacing( $input['globalPadding'] ); }
	if ( ! empty( $input['globalBgColor'] ) ) {
		$attrs['globalBg'] = tpgb_mcp_dtbl_bg( $input['globalBgColor'] );      }
	if ( ! empty( $input['globalBgHoverColor'] ) ) {
		$attrs['globalBgHover'] = tpgb_mcp_dtbl_bg( $input['globalBgHoverColor'] ); }
	if ( ! empty( $input['globalBorder'] ) ) {
		$attrs['globalBorder'] = tpgb_mcp_dtbl_border( $input['globalBorder'] );       }
	if ( ! empty( $input['globalBorderHover'] ) ) {
		$attrs['globalBorderHover'] = tpgb_mcp_dtbl_border( $input['globalBorderHover'] );  }
	if ( ! empty( $input['globalBRadius'] ) ) {
		$attrs['globalBRadius'] = tpgb_mcp_dtbl_radius( $input['globalBRadius'] );      }
	if ( ! empty( $input['globalBRadiusHover'] ) ) {
		$attrs['globalBRadiusHover'] = tpgb_mcp_dtbl_radius( $input['globalBRadiusHover'] ); }
	if ( ! empty( $input['globalBShadow'] ) ) {
		$attrs['globalBShadow'] = tpgb_mcp_dtbl_bshadow( $input['globalBShadow'] );      }
	if ( ! empty( $input['globalBShadowHover'] ) ) {
		$attrs['globalBShadowHover'] = tpgb_mcp_dtbl_bshadow( $input['globalBShadowHover'] ); }

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
	if ( tpgb_mcp_dtbl_needs_wrapper( $attrs ) ) {
		$attrs['tpgbDisrule'] = true; }

	/* ── Raw settings override ────────────────────────────────────────── */
	tpgb_mcp_apply_typo_decoration( $attrs, $input );
	$attrs = tpgb_mcp_merge_block_settings( $attrs, $input['settings'] ?? array() );

	/* ── Build, insert, save (dynamic block) ──────────────────────────── */
	$block = tpgb_mcp_build_block( $block_name, $attrs );

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
