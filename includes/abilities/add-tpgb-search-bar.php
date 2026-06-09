<?php
/**
 * Ability: Add Nexter Blocks Search Bar (tpgb/tp-search-bar) to a Gutenberg page.
 *
 * @package The_Plus_Addons_For_Block_Editor
 * @since   1.3.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_register_ability(
	'nexter-blocks/add-tpgb-search-bar',
	array(
		'label'               => __( 'Add Nexter Blocks Search Bar', 'the-plus-addons-for-block-editor' ),
		'description'         => __( 'Adds the Nexter Blocks Search Bar block (tpgb/tp-search-bar) — a powerful AJAX-powered search form with live suggestions, filters, result preview cards, taxonomy filters, pagination/load-more, custom post types, and extensive styling for input field, select dropdowns, submit button, and results area. This is a dynamic block.', 'the-plus-addons-for-block-editor' ),
		'category'            => 'nexter-blocks',

		'input_schema'        => array(
			'type'                 => 'object',
			'properties'           => array(

				/* ── Core ─────────────────────────────────────────────────── */
				'post_id'                    => array( 'type' => 'integer' ),
				'position'                   => array(
					'type'    => 'integer',
					'default' => -1,
				),
				'parent_block_id'            => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Search Input ─────────────────────────────────────────── */
				'searchLabel'                => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Label above search input.',
				),
				'placeholder'                => array(
					'type'    => 'string',
					'default' => 'Type your keyword to search...',
				),
				'searchIcon'                 => array(
					'type'    => 'string',
					'default' => 'fas fa-search',
				),
				'iconType'                   => array(
					'type'    => 'string',
					'enum'    => array( 'fontAwesome', 'none' ),
					'default' => 'fontAwesome',
				),
				'disableInput'               => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Disable the text input (use filters only).',
				),

				/* ── Search Type ──────────────────────────────────────────── */
				'searchType'                 => array(
					'type'    => 'string',
					'enum'    => array( 'otheroption', 'wpdefault', 'acf' ),
					'default' => 'otheroption',
				),

				/* ── Generic filters (what to search) ─────────────────────── */
				'searchTitle'                => array(
					'type'        => 'boolean',
					'default'     => true,
					'description' => 'Search post titles.',
				),
				'searchContent'              => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Search post content.',
				),
				'searchExcerpt'              => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Search excerpts.',
				),
				'searchTaxonomy'             => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Search by taxonomy.',
				),

				/* ── Post type ────────────────────────────────────────────── */
				'specificPostType'           => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'postType'                   => array(
					'type'        => 'string',
					'default'     => 'post',
					'description' => 'Post type slug (post, page, product, custom).',
				),

				/* ── AJAX / Live search ───────────────────────────────────── */
				'enableAjax'                 => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Enable live AJAX search with preview.',
				),
				'searchCharLimit'            => array(
					'type'        => 'string',
					'default'     => '3',
					'description' => 'Min chars before search triggers.',
				),
				'preSuggest'                 => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Show suggested searches before user types.',
				),
				'suggestText'                => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Results ──────────────────────────────────────────────── */
				'postCount'                  => array(
					'type'        => 'string',
					'default'     => '3',
					'description' => 'Max results shown.',
				),
				'resultStyle'                => array(
					'type'    => 'string',
					'default' => 'style-1',
				),
				'showResultTitle'            => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'showResultContent'          => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'showResultThumbnail'        => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'showResultPrice'            => array(
					'type'        => 'boolean',
					'default'     => true,
					'description' => 'Show WooCommerce price.',
				),
				'postNotFoundMsg'            => array(
					'type'    => 'string',
					'default' => 'Sorry, No Results Were Found.',
				),

				/* ── Text limits ──────────────────────────────────────────── */
				'enableTextLimit'            => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'enableTitleLimit'           => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'enableContentLimit'         => array(
					'type'    => 'boolean',
					'default' => false,
				),

				/* ── Result link ──────────────────────────────────────────── */
				'resultLinkEnable'           => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'resultLinkTarget'           => array(
					'type'    => 'string',
					'enum'    => array( '_self', '_blank' ),
					'default' => '_blank',
				),

				/* ── Scroll bar ───────────────────────────────────────────── */
				'scrollBar'                  => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Enable custom scrollbar for results.',
				),
				'scrollBarHeight'            => array(
					'type'    => 'integer',
					'default' => 0,
				),

				/* ── Pagination / Load more ───────────────────────────────── */
				'loadMode'                   => array(
					'type'    => 'string',
					'enum'    => array( '', 'pagination', 'loadmore', 'infinite' ),
					'default' => '',
				),
				'counterEnable'              => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'counterLimit'               => array(
					'type'    => 'string',
					'default' => '',
				),
				'arrowNav'                   => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'counterStyle'               => array(
					'type'    => 'string',
					'enum'    => array( 'left', 'center', 'right' ),
					'default' => 'center',
				),

				/* ── Load more ────────────────────────────────────────────── */
				'loadMoreBtnText'            => array(
					'type'    => 'string',
					'default' => 'Load More',
				),
				'loadingText'                => array(
					'type'    => 'string',
					'default' => 'Loading...',
				),
				'allDoneText'                => array(
					'type'    => 'string',
					'default' => 'All Done',
				),
				'postPerView'                => array(
					'type'    => 'string',
					'default' => '3',
				),
				'loadMoreCounter'            => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'counterText'                => array(
					'type'    => 'string',
					'default' => 'Totals:',
				),

				/* ── Pagination arrow buttons ─────────────────────────────── */
				'nextText'                   => array(
					'type'    => 'string',
					'default' => 'Next',
				),
				'nextIconType'               => array(
					'type'    => 'string',
					'enum'    => array( 'none', 'icon' ),
					'default' => 'none',
				),
				'nextIcon'                   => array(
					'type'    => 'string',
					'default' => 'fas fa-arrow-right',
				),
				'prevText'                   => array(
					'type'    => 'string',
					'default' => 'Prev',
				),
				'prevIconType'               => array(
					'type'    => 'string',
					'enum'    => array( 'none', 'icon' ),
					'default' => 'none',
				),
				'prevIcon'                   => array(
					'type'    => 'string',
					'default' => 'fas fa-arrow-left',
				),

				/* ── Submit button ────────────────────────────────────────── */
				'showSubmitButton'           => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'submitButtonText'           => array(
					'type'    => 'string',
					'default' => 'Search',
				),
				'submitButtonIconType'       => array(
					'type'    => 'string',
					'enum'    => array( 'none', 'fontAwesome' ),
					'default' => 'fontAwesome',
				),
				'submitButtonIcon'           => array(
					'type'    => 'string',
					'default' => 'fas fa-search',
				),

				/* ── Taxonomy filter ──────────────────────────────────────── */
				'taxonomySlug'               => array(
					'type'    => 'string',
					'default' => '',
				),
				'includeTerms'               => array(
					'type'    => 'string',
					'default' => '[]',
				),
				'excludeTerms'               => array(
					'type'    => 'string',
					'default' => '[]',
				),

				/* ── Overlay ──────────────────────────────────────────────── */
				'enableOverlay'              => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Dim page behind results.',
				),
				'overlayBgColor'             => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Form alignment ───────────────────────────────────────── */
				'formAlignment'              => array(
					'type'    => 'string',
					'enum'    => array( '', 'left', 'center', 'right' ),
					'default' => '',
				),

				/* ── Grid columns (filter dropdowns) ──────────────────────── */
				'columnsDesktop'             => array(
					'type'    => 'integer',
					'default' => 3,
				),
				'columnsTablet'              => array(
					'type'    => 'integer',
					'default' => 4,
				),
				'columnsMobile'              => array(
					'type'    => 'integer',
					'default' => 6,
				),
				'columnSpacing'              => array( 'type' => 'object' ),

				/* ── Input styling ────────────────────────────────────────── */
				'enableInputTypo'            => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'inputTypoSize'              => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'inputPadding'               => array( 'type' => 'object' ),
				'inputWidth'                 => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'inputIconSize'              => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'inputTextColor'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'inputTextFocusColor'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'inputPlaceholderColor'      => array(
					'type'    => 'string',
					'default' => '',
				),
				'inputPlaceholderFocusColor' => array(
					'type'    => 'string',
					'default' => '',
				),
				'inputIconColor'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'inputIconFocusColor'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'inputBgColor'               => array(
					'type'    => 'string',
					'default' => '',
				),
				'inputFocusBgColor'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'inputBorder'                => array( 'type' => 'object' ),
				'inputFocusBorder'           => array( 'type' => 'object' ),
				'inputBorderRadius'          => array( 'type' => 'object' ),
				'inputFocusBorderRadius'     => array( 'type' => 'object' ),

				/* ── Label styling ────────────────────────────────────────── */
				'enableLabelTypo'            => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'labelTypoSize'              => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'labelPadding'               => array( 'type' => 'object' ),
				'labelMargin'                => array( 'type' => 'object' ),
				'labelColor'                 => array(
					'type'    => 'string',
					'default' => '',
				),
				'labelHoverColor'            => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Submit button styling ────────────────────────────────── */
				'enableBtnTypo'              => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'btnTypoSize'                => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'btnPadding'                 => array( 'type' => 'object' ),
				'btnIconSize'                => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'btnIconSpacing'             => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'btnColor'                   => array(
					'type'    => 'string',
					'default' => '',
				),
				'btnHoverColor'              => array(
					'type'    => 'string',
					'default' => '',
				),
				'btnIconColor'               => array(
					'type'    => 'string',
					'default' => '',
				),
				'btnIconHoverColor'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'btnBgColor'                 => array(
					'type'    => 'string',
					'default' => '',
				),
				'btnBgHoverColor'            => array(
					'type'    => 'string',
					'default' => '',
				),
				'btnBorder'                  => array( 'type' => 'object' ),
				'btnBorderHover'             => array( 'type' => 'object' ),
				'btnBorderRadius'            => array( 'type' => 'object' ),
				'btnBorderRadiusHover'       => array( 'type' => 'object' ),

				/* ── Result area styling ──────────────────────────────────── */
				'resultAreaWidth'            => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'resultAreaPadding'          => array( 'type' => 'object' ),
				'resultAreaBgColor'          => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Result item styling ──────────────────────────────────── */
				'enableTitleTypo'            => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'titleTypoSize'              => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'enableContentTypo'          => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'contentTypoSize'            => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'resTitleColor'              => array(
					'type'    => 'string',
					'default' => '',
				),
				'resTitleHoverColor'         => array(
					'type'    => 'string',
					'default' => '',
				),
				'resContentColor'            => array(
					'type'    => 'string',
					'default' => '',
				),
				'resContentHoverColor'       => array(
					'type'    => 'string',
					'default' => '',
				),
				'resPriceColor'              => array(
					'type'    => 'string',
					'default' => '',
				),
				'resPriceHoverColor'         => array(
					'type'    => 'string',
					'default' => '',
				),
				'resImageWidth'              => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'resImageBorderRadius'       => array( 'type' => 'object' ),
				'resBoxPadding'              => array( 'type' => 'object' ),
				'resBoxBgColor'              => array(
					'type'    => 'string',
					'default' => '',
				),
				'resBoxBgHoverColor'         => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Form styling ─────────────────────────────────────────── */
				'formPadding'                => array( 'type' => 'object' ),
				'formMargin'                 => array( 'type' => 'object' ),
				'formBgColor'                => array(
					'type'    => 'string',
					'default' => '',
				),
				'formBgHoverColor'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'formBorder'                 => array( 'type' => 'object' ),
				'formBorderHover'            => array( 'type' => 'object' ),
				'formBorderRadius'           => array( 'type' => 'object' ),
				'formBorderRadiusHover'      => array( 'type' => 'object' ),

				/* ── Error message styling ────────────────────────────────── */
				'enableErrorTypo'            => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'errorTypoSize'              => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'errorColor'                 => array(
					'type'    => 'string',
					'default' => '',
				),
				'errorHoverColor'            => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Scroll animation ────────────────────────────────────── */
				'scrollAnimation'            => array(
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
				'animDuration'               => array(
					'type'    => 'string',
					'enum'    => array( '', 'slow', 'normal', 'fast' ),
					'default' => '',
				),
				'animDelay'                  => array(
					'type'    => 'string',
					'default' => '',
				),
				'animEasing'                 => array(
					'type'    => 'string',
					'enum'    => array( '', 'linear', 'ease', 'ease-in', 'ease-out', 'ease-in-out' ),
					'default' => '',
				),

				/* ── Advanced ─────────────────────────────────────────────── */
				'hideDesktop'                => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'hideTablet'                 => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'hideMobile'                 => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'globalClasses'              => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalId'                   => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalCustomCss'            => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalWidth'                => array(
					'type'    => 'string',
					'enum'    => array( '', 'inline', 'full', 'custom' ),
					'default' => '',
				),
				'globalZindex'               => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalPosition'             => array(
					'type'    => 'string',
					'enum'    => array( '', 'relative', 'absolute', 'fixed', 'sticky' ),
					'default' => '',
				),
				'globalMargin'               => array( 'type' => 'object' ),
				'globalPadding'              => array( 'type' => 'object' ),

				'settings'                   => array(
					'type'        => 'object',
					'description' => 'Raw overrides for any of the 291 internal attributes.',
				),
				'fontFamily'                 => array(
					'type'        => 'string',
					'description' => 'Font family name (e.g. "Inter", "Roboto", "Playfair Display"). When inspecting a URL via nexter-blocks/inspect-page, pass the returned fonts[].family value verbatim.',
					'default'     => '',
				),
				'fontType'                   => array(
					'type'        => 'string',
					'description' => 'Font category — "sans-serif", "serif", "display", "handwriting", or "monospace".',
					'default'     => '',
				),
				'customFont'                 => array(
					'type'        => 'string',
					'description' => 'Custom (non-Google) font name. Overrides fontFamily.',
					'default'     => '',
				),
				'fontWeight'                 => array(
					'type'        => 'string',
					'description' => 'Font weight as a string ("100"..."900"). Embedded inside every typography group\'s fontFamily.fontWeight on this block. For per-group control, use the settings raw override or sprout/update-element.',
					'default'     => '',
				),
				'textDecoration'             => array(
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

		'execute_callback'    => 'tpgb_mcp_add_search_bar_ability',
		'permission_callback' => 'tpgb_mcp_add_search_bar_permission',
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
 * Permission callback for the add-search-bar ability.
 *
 * @param array|null $input Ability input arguments.
 * @return bool True when the current user may insert the block.
 */
function tpgb_mcp_add_search_bar_permission( ?array $input = null ): bool {
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
function tpgb_mcp_srch_spacing( array $v ): array {
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
function tpgb_mcp_srch_border( array $b ): array {
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
function tpgb_mcp_srch_radius( array $r ): array {
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
function tpgb_mcp_srch_bg( string $color ): array {
	return array(
		'openBg'         => 1,
		'bgType'         => 'color',
		'bgDefaultColor' => sanitize_text_field( $color ),
	);
}
/**
 * Determine whether any wrapper-level (global/transform) attributes are set.
 *
 * @param array $attrs Block attributes built so far.
 * @return bool True when the block needs the tpgbDisrule wrapper enabled.
 */
function tpgb_mcp_srch_needs_wrapper( array $attrs ): bool {
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
 * Execute callback: insert a tpgb/tp-search-bar block into a post.
 *
 * @param array $input Ability input arguments.
 * @return array|WP_Error Ability result or error on failure.
 */
function tpgb_mcp_add_search_bar_ability( array $input ) {

	if ( ! defined( 'TPGB_VERSION' ) && ! defined( 'TPGBP_VERSION' ) ) {
		return new WP_Error( 'nexter_blocks_missing', __( 'Nexter Blocks must be active.', 'the-plus-addons-for-block-editor' ) );
	}

	$block_name = 'tpgb/tp-search-bar';
	if ( ! tpgb_mcp_has_registered_block( $block_name ) ) {
		return new WP_Error( 'block_missing', __( 'tpgb/tp-search-bar is not registered.', 'the-plus-addons-for-block-editor' ) );
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

	/* ── Search Input ─────────────────────────────────────────────────── */
	if ( ! empty( $input['searchLabel'] ) ) {
		$attrs['searchLabel'] = sanitize_text_field( $input['searchLabel'] ); }
	if ( ! empty( $input['placeholder'] ) && 'Type your keyword to search...' !== $input['placeholder'] ) {
		$attrs['placeholder'] = sanitize_text_field( $input['placeholder'] );
	}
	if ( ! empty( $input['searchIcon'] ) && 'fas fa-search' !== $input['searchIcon'] ) {
		$attrs['searchIcon'] = sanitize_text_field( $input['searchIcon'] ); }
	if ( ! empty( $input['iconType'] ) && 'fontAwesome' !== $input['iconType'] ) {
		$attrs['iconType'] = sanitize_key( $input['iconType'] ); }
	if ( ! empty( $input['disableInput'] ) ) {
		$attrs['inputDis'] = true; }

	/* ── Search Type ──────────────────────────────────────────────────── */
	if ( ! empty( $input['searchType'] ) && 'otheroption' !== $input['searchType'] ) {
		$attrs['searchType'] = sanitize_key( $input['searchType'] ); }

	/* ── Generic filters ──────────────────────────────────────────────── */
	$generic = array();
	if ( isset( $input['searchTitle'] ) && ! $input['searchTitle'] ) {
		$generic['searchTitle'] = false; }
	if ( ! empty( $input['searchContent'] ) ) {
		$generic['searchContent'] = true; }
	if ( ! empty( $input['searchExcerpt'] ) ) {
		$generic['searchExcerpt'] = true; }
	if ( ! empty( $input['searchTaxonomy'] ) ) {
		$generic['searchTaxonomy'] = true; }
	if ( ! empty( $generic ) ) {
		$default                = array( 'searchTitle' => true );
		$attrs['genericFilter'] = array_merge( $default, $generic );
	}

	/* ── Post type ────────────────────────────────────────────────────── */
	if ( ! empty( $input['specificPostType'] ) ) {
		$attrs['specificCTP'] = true;
		if ( ! empty( $input['postType'] ) && 'post' !== $input['postType'] ) {
			$attrs['ctpType'] = sanitize_key( $input['postType'] ); }
	}

	/* ── AJAX ─────────────────────────────────────────────────────────── */
	if ( ! empty( $input['enableAjax'] ) ) {
		$attrs['ajaxsearch'] = true; }
	if ( ! empty( $input['searchCharLimit'] ) && '3' !== $input['searchCharLimit'] ) {
		$attrs['searchClimit'] = sanitize_text_field( $input['searchCharLimit'] ); }
	if ( ! empty( $input['preSuggest'] ) ) {
		$attrs['preSuggest'] = true; }
	if ( ! empty( $input['suggestText'] ) ) {
		$attrs['suggestText'] = sanitize_text_field( $input['suggestText'] ); }

	/* ── Results ──────────────────────────────────────────────────────── */
	if ( ! empty( $input['postCount'] ) && '3' !== $input['postCount'] ) {
		$attrs['postCount'] = sanitize_text_field( $input['postCount'] ); }
	if ( ! empty( $input['resultStyle'] ) && 'style-1' !== $input['resultStyle'] ) {
		$attrs['resultStyle'] = sanitize_text_field( $input['resultStyle'] ); }

	$vis_set = array();
	if ( isset( $input['showResultTitle'] ) && ! $input['showResultTitle'] ) {
		$vis_set['enTitle'] = false; }
	if ( isset( $input['showResultContent'] ) && ! $input['showResultContent'] ) {
		$vis_set['enContent'] = false; }
	if ( isset( $input['showResultThumbnail'] ) && ! $input['showResultThumbnail'] ) {
		$vis_set['enThumb'] = false; }
	if ( isset( $input['showResultPrice'] ) && ! $input['showResultPrice'] ) {
		$vis_set['enPrice'] = false; }
	if ( ! empty( $vis_set ) ) {
		$default               = array(
			'enTitle'   => true,
			'enContent' => true,
			'enThumb'   => true,
			'enPrice'   => true,
		);
		$attrs['resultVisSet'] = array_merge( $default, $vis_set );
	}

	if ( ! empty( $input['postNotFoundMsg'] ) && 'Sorry, No Results Were Found.' !== $input['postNotFoundMsg'] ) {
		$attrs['postNFmessage'] = sanitize_text_field( $input['postNotFoundMsg'] );
	}

	/* ── Text limits ──────────────────────────────────────────────────── */
	$limit = array();
	if ( ! empty( $input['enableTextLimit'] ) ) {
		$limit['open'] = 1; }
	if ( ! empty( $input['enableTitleLimit'] ) ) {
		$limit['titleLimit'] = true; }
	if ( ! empty( $input['enableContentLimit'] ) ) {
		$limit['contentLimit'] = true; }
	if ( ! empty( $limit ) ) {
		$attrs['textLimit'] = array_merge(
			array(
				'open'         => 0,
				'titleLimit'   => false,
				'contentLimit' => false,
			),
			$limit
		);
	}

	/* ── Result link ──────────────────────────────────────────────────── */
	$res_link = array();
	if ( isset( $input['resultLinkEnable'] ) && ! $input['resultLinkEnable'] ) {
		$res_link['resLinkEn'] = false; }
	if ( ! empty( $input['resultLinkTarget'] ) && '_blank' !== $input['resultLinkTarget'] ) {
		$res_link['resLinkTarget'] = sanitize_text_field( $input['resultLinkTarget'] ); }
	if ( ! empty( $res_link ) ) {
		$attrs['resAreaLink'] = array_merge(
			array(
				'resLinkEn'     => true,
				'resLinkTarget' => '_blank',
			),
			$res_link
		);
	}

	/* ── Scrollbar ────────────────────────────────────────────────────── */
	if ( ! empty( $input['scrollBar'] ) ) {
		$attrs['scrollBar'] = true; }
	if ( ! empty( $input['scrollBarHeight'] ) ) {
		$attrs['scBarHeight'] = array(
			'md'   => (string) absint( $input['scrollBarHeight'] ),
			'unit' => 'px',
		); }

	/* ── Load mode ────────────────────────────────────────────────────── */
	if ( ! empty( $input['loadMode'] ) ) {
		$attrs['loadOptions'] = sanitize_key( $input['loadMode'] ); }
	if ( isset( $input['counterEnable'] ) && ! $input['counterEnable'] ) {
		$attrs['counterEnable'] = false; }
	if ( ! empty( $input['counterLimit'] ) ) {
		$attrs['counterLimit'] = sanitize_text_field( $input['counterLimit'] ); }
	if ( isset( $input['arrowNav'] ) && ! $input['arrowNav'] ) {
		$attrs['arrowNav'] = false; }
	if ( ! empty( $input['counterStyle'] ) && 'center' !== $input['counterStyle'] ) {
		$attrs['counterStyle'] = sanitize_key( $input['counterStyle'] ); }

	/* ── Load more ────────────────────────────────────────────────────── */
	if ( ! empty( $input['loadMoreBtnText'] ) && 'Load More' !== $input['loadMoreBtnText'] ) {
		$attrs['loadbtnText'] = sanitize_text_field( $input['loadMoreBtnText'] ); }
	if ( ! empty( $input['loadingText'] ) && 'Loading...' !== $input['loadingText'] ) {
		$attrs['loadingtxt'] = sanitize_text_field( $input['loadingText'] ); }
	if ( ! empty( $input['allDoneText'] ) && 'All Done' !== $input['allDoneText'] ) {
		$attrs['allposttext'] = sanitize_text_field( $input['allDoneText'] ); }
	if ( ! empty( $input['postPerView'] ) && '3' !== $input['postPerView'] ) {
		$attrs['postview'] = sanitize_text_field( $input['postPerView'] ); }
	if ( isset( $input['loadMoreCounter'] ) && ! $input['loadMoreCounter'] ) {
		$attrs['loadMoreCounter'] = false; }
	if ( ! empty( $input['counterText'] ) && 'Totals:' !== $input['counterText'] ) {
		$attrs['counterText'] = sanitize_text_field( $input['counterText'] ); }

	/* ── Pagination arrows ────────────────────────────────────────────── */
	if ( ! empty( $input['nextText'] ) && 'Next' !== $input['nextText'] ) {
		$attrs['cNextText'] = sanitize_text_field( $input['nextText'] ); }
	if ( ! empty( $input['nextIconType'] ) && 'none' !== $input['nextIconType'] ) {
		$attrs['cNextIconType'] = sanitize_key( $input['nextIconType'] ); }
	if ( ! empty( $input['nextIcon'] ) && 'fas fa-arrow-right' !== $input['nextIcon'] ) {
		$attrs['cNextIcon'] = sanitize_text_field( $input['nextIcon'] ); }
	if ( ! empty( $input['prevText'] ) && 'Prev' !== $input['prevText'] ) {
		$attrs['cPrevText'] = sanitize_text_field( $input['prevText'] ); }
	if ( ! empty( $input['prevIconType'] ) && 'none' !== $input['prevIconType'] ) {
		$attrs['cPrevIconType'] = sanitize_key( $input['prevIconType'] ); }
	if ( ! empty( $input['prevIcon'] ) && 'fas fa-arrow-left' !== $input['prevIcon'] ) {
		$attrs['cPrevIcon'] = sanitize_text_field( $input['prevIcon'] ); }

	/* ── Submit button ────────────────────────────────────────────────── */
	$s_btn = array();
	if ( isset( $input['showSubmitButton'] ) && ! $input['showSubmitButton'] ) {
		$s_btn['searchBtnTgl'] = false; }
	if ( ! empty( $input['submitButtonText'] ) && 'Search' !== $input['submitButtonText'] ) {
		$s_btn['sBtnText'] = sanitize_text_field( $input['submitButtonText'] ); }
	if ( ! empty( $input['submitButtonIconType'] ) && 'fontAwesome' !== $input['submitButtonIconType'] ) {
		$s_btn['sBtnIconType'] = sanitize_key( $input['submitButtonIconType'] ); }
	if ( ! empty( $input['submitButtonIcon'] ) && 'fas fa-search' !== $input['submitButtonIcon'] ) {
		$s_btn['sBtnIcon'] = sanitize_text_field( $input['submitButtonIcon'] ); }
	if ( ! empty( $s_btn ) ) {
		$attrs['searchBtn'] = array_merge(
			array(
				'searchBtnTgl' => true,
				'sBtnText'     => 'Search',
				'sBtnIconType' => 'fontAwesome',
				'sBtnIcon'     => 'fas fa-search',
			),
			$s_btn
		);
	}

	/* ── Taxonomy filter ──────────────────────────────────────────────── */
	if ( ! empty( $input['taxonomySlug'] ) ) {
		$attrs['taxonomySlug'] = sanitize_key( $input['taxonomySlug'] ); }
	if ( ! empty( $input['includeTerms'] ) ) {
		$attrs['includeTerms'] = sanitize_text_field( $input['includeTerms'] ); }
	if ( ! empty( $input['excludeTerms'] ) ) {
		$attrs['excludeTerms'] = sanitize_text_field( $input['excludeTerms'] ); }

	/* ── Overlay ──────────────────────────────────────────────────────── */
	if ( ! empty( $input['enableOverlay'] ) ) {
		$attrs['overlayTgl'] = true; }
	if ( ! empty( $input['overlayBgColor'] ) ) {
		$attrs['overlayBG'] = tpgb_mcp_srch_bg( $input['overlayBgColor'] ); }

	/* ── Form alignment ───────────────────────────────────────────────── */
	if ( ! empty( $input['formAlignment'] ) ) {
		$attrs['formAlign'] = sanitize_key( $input['formAlignment'] ); }

	/* ── Grid columns ─────────────────────────────────────────────────── */
	$cols_d = absint( $input['columnsDesktop'] ?? 3 );
	$cols_t = absint( $input['columnsTablet'] ?? 4 );
	$cols_m = absint( $input['columnsMobile'] ?? 6 );
	if ( 3 !== $cols_d || 4 !== $cols_t || 6 !== $cols_m ) {
		$attrs['columns'] = array(
			'md' => $cols_d,
			'sm' => $cols_t,
			'xs' => $cols_m,
		);
	}
	if ( ! empty( $input['columnSpacing'] ) ) {
		$attrs['columnSpace'] = tpgb_mcp_srch_spacing( $input['columnSpacing'] ); }

	/* ── Input styling ────────────────────────────────────────────────── */
	if ( ! empty( $input['enableInputTypo'] ) ) {
		$attrs['inputTypo'] = array(
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
	if ( ! empty( $input['inputPadding'] ) ) {
		$attrs['inputPadding'] = tpgb_mcp_srch_spacing( $input['inputPadding'] ); }
	if ( ! empty( $input['inputWidth'] ) ) {
		$attrs['inputWidth'] = array(
			'md'   => (string) absint( $input['inputWidth'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['inputIconSize'] ) ) {
		$attrs['searchIconSize'] = array(
			'md'   => (string) absint( $input['inputIconSize'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['inputTextColor'] ) ) {
		$attrs['intextColor'] = sanitize_text_field( $input['inputTextColor'] ); }
	if ( ! empty( $input['inputTextFocusColor'] ) ) {
		$attrs['intxtFcolor'] = sanitize_text_field( $input['inputTextFocusColor'] ); }
	if ( ! empty( $input['inputPlaceholderColor'] ) ) {
		$attrs['intPHColor'] = sanitize_text_field( $input['inputPlaceholderColor'] ); }
	if ( ! empty( $input['inputPlaceholderFocusColor'] ) ) {
		$attrs['intPHFColor'] = sanitize_text_field( $input['inputPlaceholderFocusColor'] ); }
	if ( ! empty( $input['inputIconColor'] ) ) {
		$attrs['intIconColor'] = sanitize_text_field( $input['inputIconColor'] ); }
	if ( ! empty( $input['inputIconFocusColor'] ) ) {
		$attrs['intIconFColor'] = sanitize_text_field( $input['inputIconFocusColor'] ); }
	if ( ! empty( $input['inputBgColor'] ) ) {
		$attrs['inbgType'] = tpgb_mcp_srch_bg( $input['inputBgColor'] ); }
	if ( ! empty( $input['inputFocusBgColor'] ) ) {
		$attrs['inFbgType'] = tpgb_mcp_srch_bg( $input['inputFocusBgColor'] ); }
	if ( ! empty( $input['inputBorder'] ) ) {
		$attrs['inNBorder'] = tpgb_mcp_srch_border( $input['inputBorder'] ); }
	if ( ! empty( $input['inputFocusBorder'] ) ) {
		$attrs['inFBorder'] = tpgb_mcp_srch_border( $input['inputFocusBorder'] ); }
	if ( ! empty( $input['inputBorderRadius'] ) ) {
		$attrs['inBradius'] = tpgb_mcp_srch_radius( $input['inputBorderRadius'] ); }
	if ( ! empty( $input['inputFocusBorderRadius'] ) ) {
		$attrs['inFBradius'] = tpgb_mcp_srch_radius( $input['inputFocusBorderRadius'] ); }

	/* ── Label styling ────────────────────────────────────────────────── */
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
	if ( ! empty( $input['labelPadding'] ) ) {
		$attrs['labelPadding'] = tpgb_mcp_srch_spacing( $input['labelPadding'] ); }
	if ( ! empty( $input['labelMargin'] ) ) {
		$attrs['labelMargin'] = tpgb_mcp_srch_spacing( $input['labelMargin'] ); }
	if ( ! empty( $input['labelColor'] ) ) {
		$attrs['labelNColor'] = sanitize_text_field( $input['labelColor'] ); }
	if ( ! empty( $input['labelHoverColor'] ) ) {
		$attrs['labelHColor'] = sanitize_text_field( $input['labelHoverColor'] ); }

	/* ── Submit button styling ────────────────────────────────────────── */
	if ( ! empty( $input['enableBtnTypo'] ) ) {
		$attrs['btnTypo'] = array(
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
		$attrs['btnPadding'] = tpgb_mcp_srch_spacing( $input['btnPadding'] ); }
	if ( ! empty( $input['btnIconSize'] ) ) {
		$attrs['btnIcnSize'] = array(
			'md'   => (string) absint( $input['btnIconSize'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['btnIconSpacing'] ) ) {
		$attrs['btnIcnSpace'] = array(
			'md'   => (string) absint( $input['btnIconSpacing'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['btnColor'] ) ) {
		$attrs['sbtnColor'] = sanitize_text_field( $input['btnColor'] ); }
	if ( ! empty( $input['btnHoverColor'] ) ) {
		$attrs['sbtnHcolor'] = sanitize_text_field( $input['btnHoverColor'] ); }
	if ( ! empty( $input['btnIconColor'] ) ) {
		$attrs['btnIconColor'] = sanitize_text_field( $input['btnIconColor'] ); }
	if ( ! empty( $input['btnIconHoverColor'] ) ) {
		$attrs['btnIconHColor'] = sanitize_text_field( $input['btnIconHoverColor'] ); }
	if ( ! empty( $input['btnBgColor'] ) ) {
		$attrs['sbtnBgtype'] = tpgb_mcp_srch_bg( $input['btnBgColor'] ); }
	if ( ! empty( $input['btnBgHoverColor'] ) ) {
		$attrs['sbtnHbg'] = tpgb_mcp_srch_bg( $input['btnBgHoverColor'] ); }
	if ( ! empty( $input['btnBorder'] ) ) {
		$attrs['sbtnBorder'] = tpgb_mcp_srch_border( $input['btnBorder'] ); }
	if ( ! empty( $input['btnBorderHover'] ) ) {
		$attrs['sbtnHborder'] = tpgb_mcp_srch_border( $input['btnBorderHover'] ); }
	if ( ! empty( $input['btnBorderRadius'] ) ) {
		$attrs['sbtnBradius'] = tpgb_mcp_srch_radius( $input['btnBorderRadius'] ); }
	if ( ! empty( $input['btnBorderRadiusHover'] ) ) {
		$attrs['sbtnHBradius'] = tpgb_mcp_srch_radius( $input['btnBorderRadiusHover'] ); }

	/* ── Result area styling ──────────────────────────────────────────── */
	if ( ! empty( $input['resultAreaWidth'] ) ) {
		$attrs['rAreaWidth'] = array(
			'md'   => (string) absint( $input['resultAreaWidth'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['resultAreaPadding'] ) ) {
		$attrs['rAreaPadding'] = tpgb_mcp_srch_spacing( $input['resultAreaPadding'] ); }
	if ( ! empty( $input['resultAreaBgColor'] ) ) {
		$attrs['rAreaNBG'] = tpgb_mcp_srch_bg( $input['resultAreaBgColor'] ); }

	/* ── Result item styling ──────────────────────────────────────────── */
	if ( ! empty( $input['enableTitleTypo'] ) ) {
		$attrs['titleTypo'] = array(
			'openTypography' => 1,
			'size'           => array(
				'md'   => ! empty( $input['titleTypoSize'] ) ? (string) absint( $input['titleTypoSize'] ) : '',
				'unit' => 'px',
			),
			'height'         => '',
			'spacing'        => '',
			'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
		);
	}
	if ( ! empty( $input['enableContentTypo'] ) ) {
		$attrs['contentTypo'] = array(
			'openTypography' => 1,
			'size'           => array(
				'md'   => ! empty( $input['contentTypoSize'] ) ? (string) absint( $input['contentTypoSize'] ) : '',
				'unit' => 'px',
			),
			'height'         => '',
			'spacing'        => '',
			'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
		);
	}
	if ( ! empty( $input['resTitleColor'] ) ) {
		$attrs['titleNColor'] = sanitize_text_field( $input['resTitleColor'] ); }
	if ( ! empty( $input['resTitleHoverColor'] ) ) {
		$attrs['titleHColor'] = sanitize_text_field( $input['resTitleHoverColor'] ); }
	if ( ! empty( $input['resContentColor'] ) ) {
		$attrs['contentNColor'] = sanitize_text_field( $input['resContentColor'] ); }
	if ( ! empty( $input['resContentHoverColor'] ) ) {
		$attrs['contentHColor'] = sanitize_text_field( $input['resContentHoverColor'] ); }
	if ( ! empty( $input['resPriceColor'] ) ) {
		$attrs['wPriceNColor'] = sanitize_text_field( $input['resPriceColor'] ); }
	if ( ! empty( $input['resPriceHoverColor'] ) ) {
		$attrs['wPriceHColor'] = sanitize_text_field( $input['resPriceHoverColor'] ); }
	if ( ! empty( $input['resImageWidth'] ) ) {
		$attrs['rCImageWidth'] = array(
			'md'   => (string) absint( $input['resImageWidth'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['resImageBorderRadius'] ) ) {
		$attrs['rCImageRadius'] = tpgb_mcp_srch_radius( $input['resImageBorderRadius'] ); }
	if ( ! empty( $input['resBoxPadding'] ) ) {
		$attrs['resBoxPadding'] = tpgb_mcp_srch_spacing( $input['resBoxPadding'] ); }
	if ( ! empty( $input['resBoxBgColor'] ) ) {
		$attrs['resBoxNBG'] = tpgb_mcp_srch_bg( $input['resBoxBgColor'] ); }
	if ( ! empty( $input['resBoxBgHoverColor'] ) ) {
		$attrs['resBoxHBG'] = tpgb_mcp_srch_bg( $input['resBoxBgHoverColor'] ); }

	/* ── Form styling ─────────────────────────────────────────────────── */
	if ( ! empty( $input['formPadding'] ) ) {
		$attrs['formPadding'] = tpgb_mcp_srch_spacing( $input['formPadding'] ); }
	if ( ! empty( $input['formMargin'] ) ) {
		$attrs['formMargin'] = tpgb_mcp_srch_spacing( $input['formMargin'] ); }
	if ( ! empty( $input['formBgColor'] ) ) {
		$attrs['formNBG'] = tpgb_mcp_srch_bg( $input['formBgColor'] ); }
	if ( ! empty( $input['formBgHoverColor'] ) ) {
		$attrs['formHBG'] = tpgb_mcp_srch_bg( $input['formBgHoverColor'] ); }
	if ( ! empty( $input['formBorder'] ) ) {
		$attrs['formNBorder'] = tpgb_mcp_srch_border( $input['formBorder'] ); }
	if ( ! empty( $input['formBorderHover'] ) ) {
		$attrs['formHBorder'] = tpgb_mcp_srch_border( $input['formBorderHover'] ); }
	if ( ! empty( $input['formBorderRadius'] ) ) {
		$attrs['formNRadius'] = tpgb_mcp_srch_radius( $input['formBorderRadius'] ); }
	if ( ! empty( $input['formBorderRadiusHover'] ) ) {
		$attrs['formHRadius'] = tpgb_mcp_srch_radius( $input['formBorderRadiusHover'] ); }

	/* ── Error styling ────────────────────────────────────────────────── */
	if ( ! empty( $input['enableErrorTypo'] ) ) {
		$attrs['errorTypo'] = array(
			'openTypography' => 1,
			'size'           => array(
				'md'   => ! empty( $input['errorTypoSize'] ) ? (string) absint( $input['errorTypoSize'] ) : '',
				'unit' => 'px',
			),
			'height'         => '',
			'spacing'        => '',
			'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
		);
	}
	if ( ! empty( $input['errorColor'] ) ) {
		$attrs['errorNColor'] = sanitize_text_field( $input['errorColor'] ); }
	if ( ! empty( $input['errorHoverColor'] ) ) {
		$attrs['errorHColor'] = sanitize_text_field( $input['errorHoverColor'] ); }

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
		$attrs['globalMargin'] = tpgb_mcp_srch_spacing( $input['globalMargin'] );  }
	if ( ! empty( $input['globalPadding'] ) ) {
		$attrs['globalPadding'] = tpgb_mcp_srch_spacing( $input['globalPadding'] ); }

	/* ── tpgbDisrule ──────────────────────────────────────────────────── */
	if ( tpgb_mcp_srch_needs_wrapper( $attrs ) ) {
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
