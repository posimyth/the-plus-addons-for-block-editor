<?php
/**
 * Ability: Add Nexter Blocks Post Listing (tpgb/tp-post-listing) to a Gutenberg page.
 *
 * @package The_Plus_Addons_For_Block_Editor
 * @since   1.3.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_register_ability(
	'nexter-blocks/add-tpgb-post-listing',
	array(
		'label'               => __( 'Add Nexter Blocks Post Listing', 'the-plus-addons-for-block-editor' ),
		'description'         => __( 'Adds the Nexter Blocks Post Listing block (tpgb/tp-post-listing) — a powerful post grid/list/metro with filters, pagination, excerpt, meta info (author/date/category), featured image with hover effects, and read more button. Supports multiple layouts (grid/list/metro), custom post types, taxonomy filters, order/offset, category filter UI, and extensive styling for every element. Use for blog listings, portfolios, product grids. This is a dynamic block.', 'the-plus-addons-for-block-editor' ),
		'category'            => 'nexter-blocks',

		'input_schema'        => array(
			'type'                 => 'object',
			'properties'           => array(

				/* ── Core ─────────────────────────────────────────────────── */
				'post_id'                  => array( 'type' => 'integer' ),
				'position'                 => array(
					'type'    => 'integer',
					'default' => -1,
				),
				'parent_block_id'          => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Source ───────────────────────────────────────────────── */
				'sourceMode'               => array(
					'type'        => 'string',
					'enum'        => array( 'page_listing', 'related', 'current_author' ),
					'default'     => 'page_listing',
					'description' => 'Post source mode.',
				),
				'relatedBy'                => array(
					'type'    => 'string',
					'enum'    => array( 'category', 'tag', 'custom' ),
					'default' => 'category',
				),
				'postType'                 => array(
					'type'        => 'string',
					'default'     => 'post',
					'description' => 'Post type slug (post, page, product, or custom).',
				),

				/* ── Layout & Style ───────────────────────────────────────── */
				'layout'                   => array(
					'type'    => 'string',
					'enum'    => array( 'grid', 'list', 'metro' ),
					'default' => 'grid',
				),
				'style'                    => array(
					'type'    => 'string',
					'default' => 'style-1',
				),
				'styleLayout'              => array(
					'type'    => 'string',
					'default' => 'style-1',
				),
				'listAlignment'            => array(
					'type'        => 'string',
					'enum'        => array( 'left', 'center', 'right' ),
					'default'     => 'center',
					'description' => 'Alignment for list/style-2 variants.',
				),

				/* ── Filters ──────────────────────────────────────────────── */
				'postCategory'             => array(
					'type'        => 'array',
					'items'       => array( 'type' => 'string' ),
					'description' => 'Array of category slugs/IDs to filter by.',
				),
				'postTag'                  => array(
					'type'        => 'array',
					'items'       => array( 'type' => 'string' ),
					'description' => 'Array of tag slugs/IDs to filter by.',
				),
				'taxonomySlug'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'includePosts'             => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Comma-separated post IDs to include.',
				),
				'excludePosts'             => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Comma-separated post IDs to exclude.',
				),

				/* ── Query ────────────────────────────────────────────────── */
				'displayCount'             => array(
					'type'        => 'string',
					'default'     => '6',
					'description' => 'Number of posts to display.',
				),
				'offsetCount'              => array(
					'type'        => 'string',
					'default'     => '0',
					'description' => 'Number of posts to skip.',
				),
				'orderBy'                  => array(
					'type'    => 'string',
					'enum'    => array( 'date', 'title', 'modified', 'rand', 'comment_count', 'menu_order' ),
					'default' => 'date',
				),
				'order'                    => array(
					'type'    => 'string',
					'enum'    => array( 'asc', 'desc' ),
					'default' => 'desc',
				),

				/* ── Grid ─────────────────────────────────────────────────── */
				'columnsDesktop'           => array(
					'type'        => 'integer',
					'default'     => 6,
					'description' => 'Columns on desktop (12 = full width, 6 = half, 4 = third, 3 = quarter).',
				),
				'columnsTablet'            => array(
					'type'    => 'integer',
					'default' => 6,
				),
				'columnsMobile'            => array(
					'type'    => 'integer',
					'default' => 12,
				),
				'columnSpacing'            => array(
					'type'        => 'object',
					'description' => 'Column gap padding.',
				),

				/* ── Category filter UI ───────────────────────────────────── */
				'showFilter'               => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Show AJAX category filter buttons.',
				),
				'filterCategoryId'         => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Title ────────────────────────────────────────────────── */
				'showTitle'                => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'titleTag'                 => array(
					'type'    => 'string',
					'enum'    => array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p' ),
					'default' => 'h3',
				),
				'titleLimitType'           => array(
					'type'    => 'string',
					'enum'    => array( 'default', 'char', 'word' ),
					'default' => 'default',
				),
				'showDots'                 => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'enableTitleTypo'          => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'titleTypoSize'            => array(
					'type'    => 'integer',
					'default' => 20,
				),
				'titleColor'               => array(
					'type'    => 'string',
					'default' => '',
				),
				'titleHoverColor'          => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Excerpt ──────────────────────────────────────────────── */
				'showExcerpt'              => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'excerptLimitType'         => array(
					'type'    => 'string',
					'enum'    => array( 'default', 'char', 'word' ),
					'default' => 'default',
				),
				'excerptLimit'             => array(
					'type'    => 'string',
					'default' => '30',
				),
				'enableExcerptTypo'        => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'excerptTypoSize'          => array(
					'type'    => 'integer',
					'default' => 14,
				),
				'excerptColor'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'excerptHoverColor'        => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Post meta (author/date) ──────────────────────────────── */
				'showPostMeta'             => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'showDate'                 => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'showAuthor'               => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'authorPrefix'             => array(
					'type'    => 'string',
					'default' => 'By ',
				),
				'showAuthorImage'          => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'postMetaStyle'            => array(
					'type'    => 'string',
					'default' => 'style-1',
				),
				'enablePostMetaTypo'       => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'postMetaTypoSize'         => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'postMetaColor'            => array(
					'type'    => 'string',
					'default' => '',
				),
				'postMetaHoverColor'       => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Pagination/Load More ─────────────────────────────────── */
				'paginationMode'           => array(
					'type'    => 'string',
					'enum'    => array( 'none', 'numbered', 'loadmore', 'infinite' ),
					'default' => 'none',
				),

				/* ── Post category tags ───────────────────────────────────── */
				'showPostCategory'         => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'postCategoryStyle'        => array(
					'type'    => 'string',
					'default' => 'style-1',
				),
				'enablePostCategoryTypo'   => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'postCategoryTypoSize'     => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'postCategoryColor'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'postCategoryHoverColor'   => array(
					'type'    => 'string',
					'default' => '',
				),
				'postCategoryBgColor'      => array(
					'type'    => 'string',
					'default' => '',
				),
				'postCategoryBgHoverColor' => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Image ────────────────────────────────────────────────── */
				'imageHoverStyle'          => array(
					'type'    => 'string',
					'default' => 'style-1',
				),
				'imageOverlayColor'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'imageOverlayHoverColor'   => array(
					'type'    => 'string',
					'default' => '',
				),
				'imageWidth'               => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'imageHeight'              => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'imageBorderRadius'        => array( 'type' => 'object' ),

				/* ── Content area ─────────────────────────────────────────── */
				'contentBgColor'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'contentBgHoverColor'      => array(
					'type'    => 'string',
					'default' => '',
				),
				'contentPadding'           => array( 'type' => 'object' ),
				'contentMargin'            => array( 'type' => 'object' ),
				'contentBorderRadius'      => array( 'type' => 'object' ),

				/* ── Box styling ──────────────────────────────────────────── */
				'boxPadding'               => array( 'type' => 'object' ),
				'boxBorder'                => array( 'type' => 'object' ),
				'boxBorderHover'           => array( 'type' => 'object' ),
				'boxBorderRadius'          => array( 'type' => 'object' ),
				'boxBorderRadiusHover'     => array( 'type' => 'object' ),
				'boxBgColor'               => array(
					'type'    => 'string',
					'default' => '',
				),
				'boxBgHoverColor'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'enableBoxShadow'          => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'boxShadowH'               => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'boxShadowV'               => array(
					'type'    => 'integer',
					'default' => 4,
				),
				'boxShadowBlur'            => array(
					'type'    => 'integer',
					'default' => 8,
				),
				'boxShadowColor'           => array(
					'type'    => 'string',
					'default' => 'rgba(0,0,0,0.40)',
				),

				/* ── Read More button ─────────────────────────────────────── */
				'showButton'               => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'buttonStyle'              => array(
					'type'    => 'string',
					'default' => 'style-7',
				),
				'buttonAlignment'          => array(
					'type'    => 'string',
					'enum'    => array( 'left', 'center', 'right' ),
					'default' => 'center',
				),
				'buttonText'               => array(
					'type'    => 'string',
					'default' => 'Read More',
				),
				'buttonIconType'           => array(
					'type'    => 'string',
					'enum'    => array( 'none', 'icon' ),
					'default' => 'icon',
				),
				'buttonIcon'               => array(
					'type'    => 'string',
					'default' => 'fa fa-angle-right',
				),
				'buttonIconPosition'       => array(
					'type'    => 'string',
					'enum'    => array( 'before', 'after' ),
					'default' => 'after',
				),
				'enableButtonTypo'         => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'buttonTypoSize'           => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'buttonColor'              => array(
					'type'    => 'string',
					'default' => '',
				),
				'buttonHoverColor'         => array(
					'type'    => 'string',
					'default' => '',
				),
				'buttonBgColor'            => array(
					'type'    => 'string',
					'default' => '',
				),
				'buttonBgHoverColor'       => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Global button preset ─────────────────────────────────── */
				'buttonPreset'             => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Global button preset key for the read-more button (e.g. "btnpreset1"). When provided, raw button colour/background values are ignored and the preset is applied instead.',
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
				'globalMargin'             => array( 'type' => 'object' ),
				'globalPadding'            => array( 'type' => 'object' ),

				'settings'                 => array(
					'type'        => 'object',
					'description' => 'Raw overrides for any of the 136 internal attributes.',
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

		'execute_callback'    => 'tpgb_mcp_add_post_listing_ability',
		'permission_callback' => 'tpgb_mcp_add_post_listing_permission',
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
 * Permission callback for the add-post-listing ability.
 *
 * @param array|null $input Ability input arguments.
 * @return bool True when the current user may insert the block.
 */
function tpgb_mcp_add_post_listing_permission( ?array $input = null ): bool {
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
function tpgb_mcp_plst_spacing2( array $v ): array {
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
function tpgb_mcp_plst_border2( array $b ): array {
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
function tpgb_mcp_plst_radius2( array $r ): array {
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
function tpgb_mcp_plst_bg2( string $color ): array {
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
function tpgb_mcp_plst_needs_wrapper2( array $attrs ): bool {
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
 * Execute callback: insert a tpgb/tp-post-listing block into a post.
 *
 * @param array $input Ability input arguments.
 * @return array|WP_Error Ability result or error on failure.
 */
function tpgb_mcp_add_post_listing_ability( array $input ) {

	if ( ! defined( 'TPGB_VERSION' ) && ! defined( 'TPGBP_VERSION' ) ) {
		return new WP_Error( 'nexter_blocks_missing', __( 'Nexter Blocks must be active.', 'the-plus-addons-for-block-editor' ) );
	}

	$block_name = 'tpgb/tp-post-listing';
	if ( ! tpgb_mcp_has_registered_block( $block_name ) ) {
		return new WP_Error( 'block_missing', __( 'tpgb/tp-post-listing is not registered.', 'the-plus-addons-for-block-editor' ) );
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

	/* ── Preset ───────────────────────────────────────────────────────── */
	$has_preset = false;
	$preset_key = sanitize_text_field( $input['buttonPreset'] ?? '' );
	if ( '' !== $preset_key ) {
		$validated = tpgb_mcp_validate_button_preset( $preset_key );
		if ( is_wp_error( $validated ) ) {
			return $validated;
		}
		tpgb_mcp_apply_button_preset( $attrs, $validated, 'direct' );
		$has_preset = true;
	}

	/* ── Source ───────────────────────────────────────────────────────── */
	if ( ! empty( $input['sourceMode'] ) && 'page_listing' !== $input['sourceMode'] ) {
		$attrs['postListing'] = sanitize_key( $input['sourceMode'] ); }
	if ( ! empty( $input['relatedBy'] ) && 'category' !== $input['relatedBy'] ) {
		$attrs['relatedPost'] = sanitize_key( $input['relatedBy'] ); }
	if ( ! empty( $input['postType'] ) && 'post' !== $input['postType'] ) {
		$attrs['postType'] = sanitize_key( $input['postType'] ); }

	/* ── Layout & Style ───────────────────────────────────────────────── */
	if ( ! empty( $input['layout'] ) && 'grid' !== $input['layout'] ) {
		$attrs['layout'] = sanitize_key( $input['layout'] ); }
	if ( ! empty( $input['style'] ) && 'style-1' !== $input['style'] ) {
		$attrs['style'] = sanitize_text_field( $input['style'] ); }
	if ( ! empty( $input['styleLayout'] ) && 'style-1' !== $input['styleLayout'] ) {
		$attrs['styleLayout'] = sanitize_text_field( $input['styleLayout'] ); }
	if ( ! empty( $input['listAlignment'] ) && 'center' !== $input['listAlignment'] ) {
		$attrs['style2Alignment'] = sanitize_key( $input['listAlignment'] ); }

	/* ── Filters ──────────────────────────────────────────────────────── */
	if ( ! empty( $input['postCategory'] ) && is_array( $input['postCategory'] ) ) {
		$attrs['postCategory'] = wp_json_encode( array_map( 'sanitize_text_field', $input['postCategory'] ) );
	}
	if ( ! empty( $input['postTag'] ) && is_array( $input['postTag'] ) ) {
		$attrs['postTag'] = wp_json_encode( array_map( 'sanitize_text_field', $input['postTag'] ) );
	}
	if ( ! empty( $input['taxonomySlug'] ) ) {
		$attrs['taxonomySlug'] = sanitize_key( $input['taxonomySlug'] ); }
	if ( ! empty( $input['includePosts'] ) ) {
		$attrs['includePosts'] = sanitize_text_field( $input['includePosts'] ); }
	if ( ! empty( $input['excludePosts'] ) ) {
		$attrs['excludePosts'] = sanitize_text_field( $input['excludePosts'] ); }

	/* ── Query ────────────────────────────────────────────────────────── */
	if ( ! empty( $input['displayCount'] ) && '6' !== $input['displayCount'] ) {
		$attrs['displayPosts'] = sanitize_text_field( $input['displayCount'] ); }
	if ( ! empty( $input['offsetCount'] ) && '0' !== $input['offsetCount'] ) {
		$attrs['offsetPosts'] = sanitize_text_field( $input['offsetCount'] ); }
	if ( ! empty( $input['orderBy'] ) && 'date' !== $input['orderBy'] ) {
		$attrs['orderBy'] = sanitize_key( $input['orderBy'] ); }
	if ( ! empty( $input['order'] ) && 'desc' !== $input['order'] ) {
		$attrs['order'] = sanitize_key( $input['order'] ); }

	/* ── Grid ─────────────────────────────────────────────────────────── */
	$cols_d = absint( $input['columnsDesktop'] ?? 6 );
	$cols_t = absint( $input['columnsTablet'] ?? 6 );
	$cols_m = absint( $input['columnsMobile'] ?? 12 );
	if ( 6 !== $cols_d || 6 !== $cols_t || 12 !== $cols_m ) {
		$attrs['columns'] = array(
			'md' => $cols_d,
			'sm' => $cols_t,
			'xs' => $cols_m,
		);
	}
	if ( ! empty( $input['columnSpacing'] ) ) {
		$attrs['columnSpace'] = tpgb_mcp_plst_spacing2( $input['columnSpacing'] ); }

	/* ── Filter UI ────────────────────────────────────────────────────── */
	if ( ! empty( $input['showFilter'] ) ) {
		$attrs['ShowFilter'] = true; }
	if ( ! empty( $input['filterCategoryId'] ) ) {
		$attrs['catfilterId'] = sanitize_text_field( $input['filterCategoryId'] ); }

	/* ── Title ────────────────────────────────────────────────────────── */
	if ( isset( $input['showTitle'] ) && ! $input['showTitle'] ) {
		$attrs['ShowTitle'] = false; }
	if ( ! empty( $input['titleTag'] ) && 'h3' !== $input['titleTag'] ) {
		$attrs['titleTag'] = sanitize_key( $input['titleTag'] ); }
	if ( ! empty( $input['titleLimitType'] ) && 'default' !== $input['titleLimitType'] ) {
		$attrs['titleByLimit'] = sanitize_key( $input['titleLimitType'] ); }
	if ( ! empty( $input['showDots'] ) ) {
		$attrs['Showdot'] = true; }
	if ( ! empty( $input['enableTitleTypo'] ) ) {
		$attrs['titleTypo'] = array(
			'openTypography' => 1,
			'size'           => array(
				'md'   => ! empty( $input['titleTypoSize'] ) ? (string) absint( $input['titleTypoSize'] ) : '20',
				'unit' => 'px',
			),
			'height'         => '',
			'spacing'        => '',
			'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
		);
	}
	if ( ! empty( $input['titleColor'] ) ) {
		$attrs['titleNormalColor'] = sanitize_text_field( $input['titleColor'] ); }
	if ( ! empty( $input['titleHoverColor'] ) ) {
		$attrs['titleHoverColor'] = sanitize_text_field( $input['titleHoverColor'] ); }

	/* ── Excerpt ──────────────────────────────────────────────────────── */
	if ( ! empty( $input['showExcerpt'] ) ) {
		$attrs['ShowExcerpt'] = true; }
	if ( ! empty( $input['excerptLimitType'] ) && 'default' !== $input['excerptLimitType'] ) {
		$attrs['excerptByLimit'] = sanitize_key( $input['excerptLimitType'] ); }
	if ( ! empty( $input['excerptLimit'] ) && '30' !== $input['excerptLimit'] ) {
		$attrs['excerptLimit'] = sanitize_text_field( $input['excerptLimit'] ); }
	if ( ! empty( $input['enableExcerptTypo'] ) ) {
		$attrs['excerptTypo'] = array(
			'openTypography' => 1,
			'size'           => array(
				'md'   => ! empty( $input['excerptTypoSize'] ) ? (string) absint( $input['excerptTypoSize'] ) : '14',
				'unit' => 'px',
			),
			'height'         => '',
			'spacing'        => '',
			'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
		);
	}
	if ( ! empty( $input['excerptColor'] ) ) {
		$attrs['excerptNormalColor'] = sanitize_text_field( $input['excerptColor'] ); }
	if ( ! empty( $input['excerptHoverColor'] ) ) {
		$attrs['excerptHoverColor'] = sanitize_text_field( $input['excerptHoverColor'] ); }

	/* ── Meta ─────────────────────────────────────────────────────────── */
	if ( isset( $input['showPostMeta'] ) && ! $input['showPostMeta'] ) {
		$attrs['ShowPostMeta'] = false; }
	if ( isset( $input['showDate'] ) && ! $input['showDate'] ) {
		$attrs['ShowDate'] = false; }
	if ( isset( $input['showAuthor'] ) && ! $input['showAuthor'] ) {
		$attrs['ShowAuthor'] = false; }
	if ( isset( $input['authorPrefix'] ) && 'By ' !== $input['authorPrefix'] ) {
		$attrs['authorTxt'] = sanitize_text_field( $input['authorPrefix'] ); }
	if ( isset( $input['showAuthorImage'] ) && ! $input['showAuthorImage'] ) {
		$attrs['ShowAuthorImg'] = false; }
	if ( ! empty( $input['postMetaStyle'] ) && 'style-1' !== $input['postMetaStyle'] ) {
		$attrs['postMetaStyle'] = sanitize_text_field( $input['postMetaStyle'] ); }
	if ( ! empty( $input['enablePostMetaTypo'] ) ) {
		$attrs['postMetaTypo'] = array(
			'openTypography' => 1,
			'size'           => array(
				'md'   => ! empty( $input['postMetaTypoSize'] ) ? (string) absint( $input['postMetaTypoSize'] ) : '',
				'unit' => 'px',
			),
			'height'         => '',
			'spacing'        => '',
			'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
		);
	}
	if ( ! empty( $input['postMetaColor'] ) ) {
		$attrs['postMetaNormalColor'] = sanitize_text_field( $input['postMetaColor'] ); }
	if ( ! empty( $input['postMetaHoverColor'] ) ) {
		$attrs['postMetaHoverColor'] = sanitize_text_field( $input['postMetaHoverColor'] ); }

	/* ── Pagination ───────────────────────────────────────────────────── */
	if ( ! empty( $input['paginationMode'] ) && 'none' !== $input['paginationMode'] ) {
		$attrs['postLodop'] = sanitize_key( $input['paginationMode'] ); }

	/* ── Post category ────────────────────────────────────────────────── */
	if ( ! empty( $input['showPostCategory'] ) ) {
		$attrs['showPostCategory'] = true; }
	if ( ! empty( $input['postCategoryStyle'] ) && 'style-1' !== $input['postCategoryStyle'] ) {
		$attrs['postCategoryStyle'] = sanitize_text_field( $input['postCategoryStyle'] ); }
	if ( ! empty( $input['enablePostCategoryTypo'] ) ) {
		$attrs['postCategoryTypo'] = array(
			'openTypography' => 1,
			'size'           => array(
				'md'   => ! empty( $input['postCategoryTypoSize'] ) ? (string) absint( $input['postCategoryTypoSize'] ) : '',
				'unit' => 'px',
			),
			'height'         => '',
			'spacing'        => '',
			'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
		);
	}
	if ( ! empty( $input['postCategoryColor'] ) ) {
		$attrs['postCategoryColor'] = sanitize_text_field( $input['postCategoryColor'] ); }
	if ( ! empty( $input['postCategoryHoverColor'] ) ) {
		$attrs['postCategoryHoverColor'] = sanitize_text_field( $input['postCategoryHoverColor'] ); }
	if ( ! empty( $input['postCategoryBgColor'] ) ) {
		$attrs['catBg'] = tpgb_mcp_plst_bg2( $input['postCategoryBgColor'] ); }
	if ( ! empty( $input['postCategoryBgHoverColor'] ) ) {
		$attrs['catBgHover'] = tpgb_mcp_plst_bg2( $input['postCategoryBgHoverColor'] ); }

	/* ── Image ────────────────────────────────────────────────────────── */
	if ( ! empty( $input['imageHoverStyle'] ) && 'style-1' !== $input['imageHoverStyle'] ) {
		$attrs['imageHoverStyle'] = sanitize_text_field( $input['imageHoverStyle'] ); }
	if ( ! empty( $input['imageOverlayColor'] ) ) {
		$attrs['imageOverlayBg'] = tpgb_mcp_plst_bg2( $input['imageOverlayColor'] ); }
	if ( ! empty( $input['imageOverlayHoverColor'] ) ) {
		$attrs['imageOverlayBgHover'] = tpgb_mcp_plst_bg2( $input['imageOverlayHoverColor'] ); }
	if ( ! empty( $input['imageWidth'] ) ) {
		$attrs['imgWidth'] = array(
			'md'   => (string) absint( $input['imageWidth'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['imageHeight'] ) ) {
		$attrs['imgHeight'] = array(
			'md'   => (string) absint( $input['imageHeight'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['imageBorderRadius'] ) ) {
		$attrs['imgRadius'] = tpgb_mcp_plst_radius2( $input['imageBorderRadius'] ); }

	/* ── Content area ─────────────────────────────────────────────────── */
	if ( ! empty( $input['contentBgColor'] ) ) {
		$attrs['contentBg'] = tpgb_mcp_plst_bg2( $input['contentBgColor'] ); }
	if ( ! empty( $input['contentBgHoverColor'] ) ) {
		$attrs['contentBgHover'] = tpgb_mcp_plst_bg2( $input['contentBgHoverColor'] ); }
	if ( ! empty( $input['contentPadding'] ) ) {
		$attrs['conSpace'] = tpgb_mcp_plst_spacing2( $input['contentPadding'] ); }
	if ( ! empty( $input['contentMargin'] ) ) {
		$attrs['conMargin'] = tpgb_mcp_plst_spacing2( $input['contentMargin'] ); }
	if ( ! empty( $input['contentBorderRadius'] ) ) {
		$attrs['blogConRdius'] = tpgb_mcp_plst_radius2( $input['contentBorderRadius'] ); }

	/* ── Box styling ──────────────────────────────────────────────────── */
	if ( ! empty( $input['boxPadding'] ) ) {
		$attrs['boxPadding'] = tpgb_mcp_plst_spacing2( $input['boxPadding'] ); }
	if ( ! empty( $input['boxBorder'] ) ) {
		$attrs['boxBorder'] = tpgb_mcp_plst_border2( $input['boxBorder'] ); }
	if ( ! empty( $input['boxBorderHover'] ) ) {
		$attrs['boxBorderHover'] = tpgb_mcp_plst_border2( $input['boxBorderHover'] ); }
	if ( ! empty( $input['boxBorderRadius'] ) ) {
		$attrs['boxBorderRadius'] = tpgb_mcp_plst_radius2( $input['boxBorderRadius'] ); }
	if ( ! empty( $input['boxBorderRadiusHover'] ) ) {
		$attrs['boxBorderRadiusHover'] = tpgb_mcp_plst_radius2( $input['boxBorderRadiusHover'] ); }
	if ( ! empty( $input['boxBgColor'] ) ) {
		$attrs['boxBg'] = tpgb_mcp_plst_bg2( $input['boxBgColor'] ); }
	if ( ! empty( $input['boxBgHoverColor'] ) ) {
		$attrs['boxBgHover'] = tpgb_mcp_plst_bg2( $input['boxBgHoverColor'] ); }
	if ( ! empty( $input['enableBoxShadow'] ) ) {
		$attrs['boxBoxShadow'] = array(
			'openShadow' => true,
			'horizontal' => (string) intval( $input['boxShadowH'] ?? 0 ),
			'vertical'   => (string) intval( $input['boxShadowV'] ?? 4 ),
			'blur'       => (string) absint( $input['boxShadowBlur'] ?? 8 ),
			'color'      => sanitize_text_field( $input['boxShadowColor'] ?? 'rgba(0,0,0,0.40)' ),
		);
	}

	/* ── Read More button ─────────────────────────────────────────────── */
	if ( ! empty( $input['showButton'] ) ) {
		$attrs['ShowButton'] = true;
		if ( ! empty( $input['buttonStyle'] ) && 'style-7' !== $input['buttonStyle'] ) {
			$attrs['postBtnsty'] = sanitize_text_field( $input['buttonStyle'] ); }
		if ( ! empty( $input['buttonAlignment'] ) && 'center' !== $input['buttonAlignment'] ) {
			$attrs['btnAlign'] = array(
				'md' => sanitize_key( $input['buttonAlignment'] ),
				'sm' => '',
				'xs' => '',
			); }
		if ( ! empty( $input['buttonText'] ) && 'Read More' !== $input['buttonText'] ) {
			$attrs['postbtntext'] = sanitize_text_field( $input['buttonText'] ); }
		if ( ! empty( $input['buttonIconType'] ) && 'icon' !== $input['buttonIconType'] ) {
			$attrs['pobtnIconType'] = sanitize_key( $input['buttonIconType'] ); }
		if ( ! empty( $input['buttonIcon'] ) && 'fa fa-angle-right' !== $input['buttonIcon'] ) {
			$attrs['pobtnIconName'] = sanitize_text_field( $input['buttonIcon'] ); }
		if ( ! empty( $input['buttonIconPosition'] ) && 'after' !== $input['buttonIconPosition'] ) {
			$attrs['btnIconPosi'] = sanitize_key( $input['buttonIconPosition'] ); }
		if ( ! $has_preset && ! empty( $input['enableButtonTypo'] ) ) {
			$attrs['butTypo'] = array(
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
		if ( ! $has_preset && ! empty( $input['buttonColor'] ) ) {
			$attrs['butNcolor'] = sanitize_text_field( $input['buttonColor'] ); }
		if ( ! $has_preset && ! empty( $input['buttonHoverColor'] ) ) {
			$attrs['buthvrColor'] = sanitize_text_field( $input['buttonHoverColor'] ); }
		if ( ! $has_preset && ! empty( $input['buttonBgColor'] ) ) {
			$attrs['butbgType'] = array(
				'openBg'         => 1,
				'bgType'         => 'color',
				'bgDefaultColor' => sanitize_text_field( $input['buttonBgColor'] ),
			); }
		if ( ! $has_preset && ! empty( $input['buttonBgHoverColor'] ) ) {
			$attrs['butHvrbgType'] = array(
				'openBg'         => 1,
				'bgType'         => 'color',
				'bgDefaultColor' => sanitize_text_field( $input['buttonBgHoverColor'] ),
			); }
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

	if ( ! empty( $input['globalMargin'] ) ) {
		$attrs['globalMargin'] = tpgb_mcp_plst_spacing2( $input['globalMargin'] );  }
	if ( ! empty( $input['globalPadding'] ) ) {
		$attrs['globalPadding'] = tpgb_mcp_plst_spacing2( $input['globalPadding'] ); }

	/* ── tpgbDisrule ──────────────────────────────────────────────────── */
	if ( tpgb_mcp_plst_needs_wrapper2( $attrs ) ) {
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
