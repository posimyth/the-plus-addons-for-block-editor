<?php
/**
 * Ability: Add Nexter Blocks Post Meta (tpgb/tp-post-meta) to a Gutenberg page.
 *
 * @package The_Plus_Addons_For_Block_Editor
 * @since   1.3.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_register_ability(
	'nexter-blocks/add-tpgb-post-meta',
	array(
		'label'               => __( 'Add Nexter Blocks Post Meta', 'the-plus-addons-for-block-editor' ),
		'description'         => __( 'Adds the Nexter Blocks Post Meta block (tpgb/tp-post-meta) — displays post metadata: date, category, author, comments count, and reading time. Each element can be individually toggled, styled (colours, icons, prefixes), and customised with separators. Ideal for blog post info bars below titles. This is a dynamic block.', 'the-plus-addons-for-block-editor' ),
		'category'            => 'nexter-blocks',

		'input_schema'        => array(
			'type'                 => 'object',
			'properties'           => array(

				/* ── Core ─────────────────────────────────────────────────── */
				'post_id'                   => array( 'type' => 'integer' ),
				'position'                  => array(
					'type'    => 'integer',
					'default' => -1,
				),
				'parent_block_id'           => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Layout ───────────────────────────────────────────────── */
				'metaLayout'                => array(
					'type'        => 'string',
					'description' => 'Layout preset e.g. layout-1, layout-2, layout-3.',
					'default'     => 'layout-1',
				),
				'alignment'                 => array(
					'type'    => 'string',
					'enum'    => array( 'left', 'center', 'right' ),
					'default' => 'left',
				),
				'separator'                 => array(
					'type'        => 'string',
					'default'     => '|',
					'description' => 'Separator character between meta items.',
				),
				'separatorColor'            => array(
					'type'    => 'string',
					'default' => '',
				),
				'separatorSize'             => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Separator size in px.',
				),
				'separatorLeftSpace'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'separatorRightSpace'       => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Typography ───────────────────────────────────────────── */
				'enableMetaTypo'            => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'metaTypoSize'              => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'metaColor'                 => array(
					'type'    => 'string',
					'default' => '',
				),
				'enableLabelTypo'           => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'labelTypoSize'             => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'labelColor'                => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Date ─────────────────────────────────────────────────── */
				'showDate'                  => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'datePrefix'                => array(
					'type'    => 'string',
					'default' => 'Published On ',
				),
				'dateType'                  => array(
					'type'    => 'string',
					'enum'    => array( 'published', 'modified' ),
					'default' => 'published',
				),
				'dateColor'                 => array(
					'type'    => 'string',
					'default' => '',
				),
				'dateHoverColor'            => array(
					'type'    => 'string',
					'default' => '',
				),
				'dateIcon'                  => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Font Awesome icon class for date.',
				),
				'dateIconSpacing'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'dateIconColor'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'dateIconHoverColor'        => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Category ─────────────────────────────────────────────── */
				'showCategory'              => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'categoryPrefix'            => array(
					'type'    => 'string',
					'default' => 'in ',
				),
				'taxonomySlug'              => array(
					'type'    => 'string',
					'default' => 'category',
				),
				'categoryDisplayCount'      => array(
					'type'    => 'string',
					'default' => '5',
				),
				'categoryColor'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'categoryHoverColor'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'categoryStyle'             => array(
					'type'    => 'string',
					'default' => 'style-1',
				),
				'categorySpacing'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'categoryPadding'           => array( 'type' => 'object' ),
				'categoryMargin'            => array( 'type' => 'object' ),
				'categoryBorder'            => array( 'type' => 'object' ),
				'categoryBorderHover'       => array( 'type' => 'object' ),
				'categoryBorderRadius'      => array( 'type' => 'object' ),
				'categoryBorderRadiusHover' => array( 'type' => 'object' ),
				'categoryBgColor'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'categoryBgHoverColor'      => array(
					'type'    => 'string',
					'default' => '',
				),
				'enableCategoryShadow'      => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'categoryShadowColor'       => array(
					'type'    => 'string',
					'default' => 'rgba(0,0,0,0.40)',
				),

				/* ── Author ───────────────────────────────────────────────── */
				'showAuthor'                => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'authorPrefix'              => array(
					'type'    => 'string',
					'default' => 'By ',
				),
				'authorIcon'                => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Icon class or "profile" for profile image.',
				),
				'authorIconSpacing'         => array(
					'type'    => 'string',
					'default' => '',
				),
				'authorIconSize'            => array(
					'type'    => 'string',
					'default' => '',
				),
				'authorProfileBorderRadius' => array( 'type' => 'object' ),
				'authorColor'               => array(
					'type'    => 'string',
					'default' => '',
				),
				'authorHoverColor'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'authorIconColor'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'authorIconHoverColor'      => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Comments ─────────────────────────────────────────────── */
				'showComment'               => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'commentPrefix'             => array(
					'type'    => 'string',
					'default' => 'Comments ',
				),
				'commentIcon'               => array(
					'type'    => 'string',
					'default' => '',
				),
				'commentIconSpacing'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'commentColor'              => array(
					'type'    => 'string',
					'default' => '',
				),
				'commentHoverColor'         => array(
					'type'    => 'string',
					'default' => '',
				),
				'commentIconColor'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'commentIconHoverColor'     => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Reading time ─────────────────────────────────────────── */
				'showReadTime'              => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'readTimePrefix'            => array(
					'type'    => 'string',
					'default' => 'Time To Read : ',
				),
				'readTimeColor'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'readTimeHoverColor'        => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Box styling ──────────────────────────────────────────── */
				'padding'                   => array( 'type' => 'object' ),
				'innerMargin'               => array(
					'type'        => 'object',
					'description' => 'Margin between meta items.',
				),
				'boxBorder'                 => array( 'type' => 'object' ),
				'boxBorderHover'            => array( 'type' => 'object' ),
				'boxBorderRadius'           => array( 'type' => 'object' ),
				'boxBorderRadiusHover'      => array( 'type' => 'object' ),
				'boxBgColor'                => array(
					'type'    => 'string',
					'default' => '',
				),
				'boxBgHoverColor'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'enableBoxShadow'           => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'boxShadowColor'            => array(
					'type'    => 'string',
					'default' => 'rgba(0,0,0,0.40)',
				),

				/* ── Scroll animation ────────────────────────────────────── */
				'scrollAnimation'           => array(
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
				'animDuration'              => array(
					'type'    => 'string',
					'enum'    => array( '', 'slow', 'normal', 'fast' ),
					'default' => '',
				),
				'animDelay'                 => array(
					'type'    => 'string',
					'default' => '',
				),
				'animEasing'                => array(
					'type'    => 'string',
					'enum'    => array( '', 'linear', 'ease', 'ease-in', 'ease-out', 'ease-in-out' ),
					'default' => '',
				),

				/* ── Advanced ─────────────────────────────────────────────── */
				'hideDesktop'               => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'hideTablet'                => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'hideMobile'                => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'globalClasses'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalId'                  => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalCustomCss'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalWidth'               => array(
					'type'    => 'string',
					'enum'    => array( '', 'inline', 'full', 'custom' ),
					'default' => '',
				),
				'globalZindex'              => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalPosition'            => array(
					'type'    => 'string',
					'enum'    => array( '', 'relative', 'absolute', 'fixed', 'sticky' ),
					'default' => '',
				),
				'globalMargin'              => array( 'type' => 'object' ),
				'globalPadding'             => array( 'type' => 'object' ),

				'settings'                  => array( 'type' => 'object' ),
				'fontFamily'                => array(
					'type'        => 'string',
					'description' => 'Font family name (e.g. "Inter", "Roboto", "Playfair Display"). When inspecting a URL via nexter-blocks/inspect-page, pass the returned fonts[].family value verbatim.',
					'default'     => '',
				),
				'fontType'                  => array(
					'type'        => 'string',
					'description' => 'Font category — "sans-serif", "serif", "display", "handwriting", or "monospace".',
					'default'     => '',
				),
				'customFont'                => array(
					'type'        => 'string',
					'description' => 'Custom (non-Google) font name. Overrides fontFamily.',
					'default'     => '',
				),
				'fontWeight'                => array(
					'type'        => 'string',
					'description' => 'Font weight as a string ("100"..."900"). Embedded inside every typography group\'s fontFamily.fontWeight on this block. For per-group control, use the settings raw override or sprout/update-element.',
					'default'     => '',
				),
				'textDecoration'            => array(
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

		'execute_callback'    => 'tpgb_mcp_add_post_meta_ability',
		'permission_callback' => 'tpgb_mcp_add_post_meta_permission',
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
 * Permission callback for the add-post-meta ability.
 *
 * @param array|null $input Ability input arguments.
 * @return bool True when the current user may insert the block.
 */
function tpgb_mcp_add_post_meta_permission( ?array $input = null ): bool {
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
function tpgb_mcp_pmeta_spacing( array $v ): array {
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
function tpgb_mcp_pmeta_border( array $b ): array {
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
function tpgb_mcp_pmeta_radius( array $r ): array {
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
 * Build a Nexter Blocks colour-only background attribute.
 *
 * @param string $color Background colour value.
 * @return array Background attribute structured for the block.
 */
function tpgb_mcp_pmeta_bg( string $color ): array {
	return array(
		'bgType'         => 'color',
		'bgDefaultColor' => sanitize_text_field( $color ),
		'bgGradient'     => (object) array(),
	);
}
/**
 * Determine whether any wrapper-level (global/transform) attributes are set.
 *
 * @param array $attrs Block attributes built so far.
 * @return bool True when the block needs the tpgbDisrule wrapper enabled.
 */
function tpgb_mcp_pmeta_needs_wrapper( array $attrs ): bool {
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
 * Execute callback: insert a tpgb/tp-post-meta block into a post.
 *
 * @param array $input Ability input arguments.
 * @return array|WP_Error Ability result or error on failure.
 */
function tpgb_mcp_add_post_meta_ability( array $input ) {

	if ( ! defined( 'TPGB_VERSION' ) && ! defined( 'TPGBP_VERSION' ) ) {
		return new WP_Error( 'nexter_blocks_missing', __( 'Nexter Blocks must be active.', 'the-plus-addons-for-block-editor' ) );
	}

	$block_name = 'tpgb/tp-post-meta';
	if ( ! tpgb_mcp_has_registered_block( $block_name ) ) {
		return new WP_Error( 'block_missing', __( 'tpgb/tp-post-meta is not registered.', 'the-plus-addons-for-block-editor' ) );
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

	/* ── Layout ───────────────────────────────────────────────────────── */
	if ( ! empty( $input['metaLayout'] ) && 'layout-1' !== $input['metaLayout'] ) {
		$attrs['metaLayout'] = sanitize_text_field( $input['metaLayout'] ); }
	if ( ! empty( $input['alignment'] ) && 'left' !== $input['alignment'] ) {
		$attrs['alignment'] = array( 'md' => sanitize_key( $input['alignment'] ) );
	}
	if ( ! empty( $input['separator'] ) && '|' !== $input['separator'] ) {
		$attrs['separator'] = sanitize_text_field( $input['separator'] ); }
	if ( ! empty( $input['separatorColor'] ) ) {
		$attrs['sepColor'] = sanitize_text_field( $input['separatorColor'] ); }
	if ( ! empty( $input['separatorSize'] ) ) {
		$attrs['sepSize'] = sanitize_text_field( $input['separatorSize'] ); }
	if ( ! empty( $input['separatorLeftSpace'] ) ) {
		$attrs['sepLeftSpace'] = sanitize_text_field( $input['separatorLeftSpace'] ); }
	if ( ! empty( $input['separatorRightSpace'] ) ) {
		$attrs['sepRightSpace'] = sanitize_text_field( $input['separatorRightSpace'] ); }

	/* ── Typography ───────────────────────────────────────────────────── */
	if ( ! empty( $input['enableMetaTypo'] ) ) {
		$attrs['metaTypo'] = array(
			'openTypography' => 1,
			'size'           => array(
				'md'   => ! empty( $input['metaTypoSize'] ) ? (string) absint( $input['metaTypoSize'] ) : '',
				'unit' => 'px',
			),
			'height'         => '',
			'spacing'        => '',
			'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
		);
	}
	if ( ! empty( $input['metaColor'] ) ) {
		$attrs['metaColor'] = sanitize_text_field( $input['metaColor'] ); }
	if ( ! empty( $input['enableLabelTypo'] ) ) {
		$attrs['labelTypo'] = array(
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
	if ( ! empty( $input['labelColor'] ) ) {
		$attrs['labelColor'] = sanitize_text_field( $input['labelColor'] ); }

	/* ── Date ─────────────────────────────────────────────────────────── */
	if ( isset( $input['showDate'] ) && ! $input['showDate'] ) {
		$attrs['showDate'] = false; }
	if ( isset( $input['datePrefix'] ) && 'Published On ' !== $input['datePrefix'] ) {
		$attrs['datePrefix'] = sanitize_text_field( $input['datePrefix'] ); }
	if ( ! empty( $input['dateType'] ) && 'published' !== $input['dateType'] ) {
		$attrs['dateType'] = sanitize_key( $input['dateType'] ); }
	if ( ! empty( $input['dateColor'] ) ) {
		$attrs['dateColor'] = sanitize_text_field( $input['dateColor'] ); }
	if ( ! empty( $input['dateHoverColor'] ) ) {
		$attrs['dateHoverColor'] = sanitize_text_field( $input['dateHoverColor'] ); }
	if ( ! empty( $input['dateIcon'] ) ) {
		$attrs['dateIcon'] = sanitize_text_field( $input['dateIcon'] ); }
	if ( ! empty( $input['dateIconSpacing'] ) ) {
		$attrs['dateIconSpace'] = sanitize_text_field( $input['dateIconSpacing'] ); }
	if ( ! empty( $input['dateIconColor'] ) ) {
		$attrs['dateIconColor'] = sanitize_text_field( $input['dateIconColor'] ); }
	if ( ! empty( $input['dateIconHoverColor'] ) ) {
		$attrs['dateIconHoverColor'] = sanitize_text_field( $input['dateIconHoverColor'] ); }

	/* ── Category ─────────────────────────────────────────────────────── */
	if ( isset( $input['showCategory'] ) && ! $input['showCategory'] ) {
		$attrs['showCategory'] = false; }
	if ( isset( $input['categoryPrefix'] ) && 'in ' !== $input['categoryPrefix'] ) {
		$attrs['catePrefix'] = sanitize_text_field( $input['categoryPrefix'] ); }
	if ( ! empty( $input['taxonomySlug'] ) && 'category' !== $input['taxonomySlug'] ) {
		$attrs['taxonomySlug'] = sanitize_key( $input['taxonomySlug'] ); }
	if ( ! empty( $input['categoryDisplayCount'] ) && '5' !== $input['categoryDisplayCount'] ) {
		$attrs['cateDisplayNo'] = sanitize_text_field( $input['categoryDisplayCount'] ); }
	if ( ! empty( $input['categoryColor'] ) ) {
		$attrs['cateColor'] = sanitize_text_field( $input['categoryColor'] ); }
	if ( ! empty( $input['categoryHoverColor'] ) ) {
		$attrs['cateHoverColor'] = sanitize_text_field( $input['categoryHoverColor'] ); }
	if ( ! empty( $input['categoryStyle'] ) && 'style-1' !== $input['categoryStyle'] ) {
		$attrs['cateStyle'] = sanitize_text_field( $input['categoryStyle'] ); }
	if ( ! empty( $input['categorySpacing'] ) ) {
		$attrs['cateSpace'] = sanitize_text_field( $input['categorySpacing'] ); }
	if ( ! empty( $input['categoryPadding'] ) ) {
		$attrs['catepadding'] = tpgb_mcp_pmeta_spacing( $input['categoryPadding'] ); }
	if ( ! empty( $input['categoryMargin'] ) ) {
		$attrs['catemargin'] = tpgb_mcp_pmeta_spacing( $input['categoryMargin'] ); }
	if ( ! empty( $input['categoryBorder'] ) ) {
		$attrs['cateBorder'] = tpgb_mcp_pmeta_border( $input['categoryBorder'] ); }
	if ( ! empty( $input['categoryBorderHover'] ) ) {
		$attrs['cateBorderHover'] = tpgb_mcp_pmeta_border( $input['categoryBorderHover'] ); }
	if ( ! empty( $input['categoryBorderRadius'] ) ) {
		$attrs['cateBorderRadius'] = tpgb_mcp_pmeta_radius( $input['categoryBorderRadius'] ); }
	if ( ! empty( $input['categoryBorderRadiusHover'] ) ) {
		$attrs['cateBorderRadiusHover'] = tpgb_mcp_pmeta_radius( $input['categoryBorderRadiusHover'] ); }
	if ( ! empty( $input['categoryBgColor'] ) ) {
		$attrs['cateBg'] = tpgb_mcp_pmeta_bg( $input['categoryBgColor'] ); }
	if ( ! empty( $input['categoryBgHoverColor'] ) ) {
		$attrs['cateBgHover'] = tpgb_mcp_pmeta_bg( $input['categoryBgHoverColor'] ); }
	if ( ! empty( $input['enableCategoryShadow'] ) ) {
		$attrs['cateBoxShadow'] = array(
			'openShadow' => true,
			'horizontal' => '0',
			'vertical'   => '4',
			'blur'       => '8',
			'color'      => sanitize_text_field( $input['categoryShadowColor'] ?? 'rgba(0,0,0,0.40)' ),
		);
	}

	/* ── Author ───────────────────────────────────────────────────────── */
	if ( isset( $input['showAuthor'] ) && ! $input['showAuthor'] ) {
		$attrs['showAuthor'] = false; }
	if ( isset( $input['authorPrefix'] ) && 'By ' !== $input['authorPrefix'] ) {
		$attrs['authorPrefix'] = sanitize_text_field( $input['authorPrefix'] ); }
	if ( ! empty( $input['authorIcon'] ) ) {
		$attrs['authorIcon'] = sanitize_text_field( $input['authorIcon'] ); }
	if ( ! empty( $input['authorIconSpacing'] ) ) {
		$attrs['authorIconSpace'] = sanitize_text_field( $input['authorIconSpacing'] ); }
	if ( ! empty( $input['authorIconSize'] ) ) {
		$attrs['authorIconSize'] = sanitize_text_field( $input['authorIconSize'] ); }
	if ( ! empty( $input['authorProfileBorderRadius'] ) ) {
		$attrs['proBradius'] = tpgb_mcp_pmeta_radius( $input['authorProfileBorderRadius'] ); }
	if ( ! empty( $input['authorColor'] ) ) {
		$attrs['authorColor'] = sanitize_text_field( $input['authorColor'] ); }
	if ( ! empty( $input['authorHoverColor'] ) ) {
		$attrs['authorHoverColor'] = sanitize_text_field( $input['authorHoverColor'] ); }
	if ( ! empty( $input['authorIconColor'] ) ) {
		$attrs['authorIconColor'] = sanitize_text_field( $input['authorIconColor'] ); }
	if ( ! empty( $input['authorIconHoverColor'] ) ) {
		$attrs['authorIconHoverColor'] = sanitize_text_field( $input['authorIconHoverColor'] ); }

	/* ── Comments ─────────────────────────────────────────────────────── */
	if ( isset( $input['showComment'] ) && ! $input['showComment'] ) {
		$attrs['showComment'] = false; }
	if ( isset( $input['commentPrefix'] ) && 'Comments ' !== $input['commentPrefix'] ) {
		$attrs['commentPrefix'] = sanitize_text_field( $input['commentPrefix'] ); }
	if ( ! empty( $input['commentIcon'] ) ) {
		$attrs['commentIcon'] = sanitize_text_field( $input['commentIcon'] ); }
	if ( ! empty( $input['commentIconSpacing'] ) ) {
		$attrs['commentIconSpace'] = sanitize_text_field( $input['commentIconSpacing'] ); }
	if ( ! empty( $input['commentColor'] ) ) {
		$attrs['commentColor'] = sanitize_text_field( $input['commentColor'] ); }
	if ( ! empty( $input['commentHoverColor'] ) ) {
		$attrs['commentHoverColor'] = sanitize_text_field( $input['commentHoverColor'] ); }
	if ( ! empty( $input['commentIconColor'] ) ) {
		$attrs['commentIconColor'] = sanitize_text_field( $input['commentIconColor'] ); }
	if ( ! empty( $input['commentIconHoverColor'] ) ) {
		$attrs['commentIconHoverColor'] = sanitize_text_field( $input['commentIconHoverColor'] ); }

	/* ── Reading time ─────────────────────────────────────────────────── */
	if ( ! empty( $input['showReadTime'] ) ) {
		$attrs['showreadTime'] = true; }
	if ( isset( $input['readTimePrefix'] ) && 'Time To Read : ' !== $input['readTimePrefix'] ) {
		$attrs['readPrefix'] = sanitize_text_field( $input['readTimePrefix'] ); }
	if ( ! empty( $input['readTimeColor'] ) ) {
		$attrs['mreadColor'] = sanitize_text_field( $input['readTimeColor'] ); }
	if ( ! empty( $input['readTimeHoverColor'] ) ) {
		$attrs['mreadHColor'] = sanitize_text_field( $input['readTimeHoverColor'] ); }

	/* ── Box styling ──────────────────────────────────────────────────── */
	if ( ! empty( $input['padding'] ) ) {
		$attrs['padding'] = tpgb_mcp_pmeta_spacing( $input['padding'] ); }
	if ( ! empty( $input['innerMargin'] ) ) {
		$attrs['inMargin'] = tpgb_mcp_pmeta_spacing( $input['innerMargin'] ); }
	if ( ! empty( $input['boxBorder'] ) ) {
		$attrs['boxBorder'] = tpgb_mcp_pmeta_border( $input['boxBorder'] ); }
	if ( ! empty( $input['boxBorderHover'] ) ) {
		$attrs['boxBorderHover'] = tpgb_mcp_pmeta_border( $input['boxBorderHover'] ); }
	if ( ! empty( $input['boxBorderRadius'] ) ) {
		$attrs['boxBRadius'] = tpgb_mcp_pmeta_radius( $input['boxBorderRadius'] ); }
	if ( ! empty( $input['boxBorderRadiusHover'] ) ) {
		$attrs['boxBRadiusHover'] = tpgb_mcp_pmeta_radius( $input['boxBorderRadiusHover'] ); }
	if ( ! empty( $input['boxBgColor'] ) ) {
		$attrs['boxBg'] = tpgb_mcp_pmeta_bg( $input['boxBgColor'] ); }
	if ( ! empty( $input['boxBgHoverColor'] ) ) {
		$attrs['boxBgHover'] = tpgb_mcp_pmeta_bg( $input['boxBgHoverColor'] ); }
	if ( ! empty( $input['enableBoxShadow'] ) ) {
		$attrs['boxBoxShadow'] = array(
			'openShadow' => true,
			'horizontal' => '0',
			'vertical'   => '4',
			'blur'       => '8',
			'color'      => sanitize_text_field( $input['boxShadowColor'] ?? 'rgba(0,0,0,0.40)' ),
		);
	}

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

	/* ── Spacing ──────────────────────────────────────────────────────── */
	if ( ! empty( $input['globalMargin'] ) ) {
		$attrs['globalMargin'] = tpgb_mcp_pmeta_spacing( $input['globalMargin'] );  }
	if ( ! empty( $input['globalPadding'] ) ) {
		$attrs['globalPadding'] = tpgb_mcp_pmeta_spacing( $input['globalPadding'] ); }

	/* ── tpgbDisrule ──────────────────────────────────────────────────── */
	if ( tpgb_mcp_pmeta_needs_wrapper( $attrs ) ) {
		$attrs['tpgbDisrule'] = true; }

	/* ── Raw settings override ────────────────────────────────────────── */
	tpgb_mcp_apply_typo_decoration( $attrs, $input );
	$attrs = tpgb_mcp_merge_block_settings( $attrs, $input['settings'] ?? array() );

	/* ── Build, insert, save ──────────────────────────────────────────── */
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
