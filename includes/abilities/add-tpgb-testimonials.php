<?php
/**
 * Ability: Add Nexter Blocks Testimonials (tpgb/tp-testimonials) to a Gutenberg page.
 *
 * @package The_Plus_Addons_For_Block_Editor
 * @since   1.3.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_register_ability(
	'nexter-blocks/add-tpgb-testimonials',
	array(
		'label'               => __( 'Add Nexter Blocks Testimonials', 'the-plus-addons-for-block-editor' ),
		'description'         => __( 'Adds the Nexter Blocks Testimonials block (tpgb/tp-testimonials) — grid or carousel of customer testimonials. Each item has author name, designation, content/quote, author title overlay, and avatar image. Supports 3 visual styles, columns per breakpoint, content height limit (with hover/always scrollbars or read-more truncation), full styling for box (margin/padding/border/radius/bg/shadow normal+hover), title/author/designation/content typography & colors (normal+hover), avatar max-width/radius/shadow (normal+hover), Read-more button styling, scroll bar styling, and carousel pagination dots colour controls. This is a dynamic block.', 'the-plus-addons-for-block-editor' ),
		'category'            => 'nexter-blocks',

		'input_schema'        => array(
			'type'                 => 'object',
			'properties'           => array(

				/* ── Core ─────────────────────────────────────────────────── */
				'post_id'               => array( 'type' => 'integer' ),
				'position'              => array(
					'type'    => 'integer',
					'default' => -1,
				),
				'parent_block_id'       => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Items ────────────────────────────────────────────────── */
				'items'                 => array(
					'type'        => 'array',
					'description' => 'Testimonial items. Each: { name, designation, content, authorTitle, avatarUrl, avatarId }.',
					'items'       => array(
						'type'       => 'object',
						'properties' => array(
							'name'        => array( 'type' => 'string' ),
							'designation' => array( 'type' => 'string' ),
							'content'     => array( 'type' => 'string' ),
							'authorTitle' => array( 'type' => 'string' ),
							'avatarUrl'   => array( 'type' => 'string' ),
							'avatarId'    => array( 'type' => 'integer' ),
						),
					),
				),

				/* ── Layout ───────────────────────────────────────────────── */
				'style'                 => array(
					'type'    => 'string',
					'enum'    => array( 'style-1', 'style-2', 'style-3' ),
					'default' => 'style-1',
				),
				'styleLayout'           => array(
					'type'    => 'string',
					'enum'    => array( 'style-1', 'style-2', 'style-3', 'style-4' ),
					'default' => 'style-1',
				),
				'layout'                => array(
					'type'    => 'string',
					'enum'    => array( 'carousel', 'grid' ),
					'default' => 'carousel',
				),
				'columnsDesktop'        => array(
					'type'        => 'integer',
					'default'     => 6,
					'description' => 'Columns desktop (1-12).',
				),
				'columnsTablet'         => array(
					'type'    => 'integer',
					'default' => 6,
				),
				'columnsMobile'         => array(
					'type'    => 'integer',
					'default' => 12,
				),
				'columnSpace'           => array( 'type' => 'object' ),
				'showBlockContent'      => array(
					'type'    => 'boolean',
					'default' => true,
				),

				/* ── Content height / read-more ───────────────────────────── */
				'contentHeight'         => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Content area height in px (forces scroll).',
				),
				'titleHeight'           => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'scrollOn'              => array(
					'type'    => 'string',
					'enum'    => array( 'on-hover', 'always' ),
					'default' => 'on-hover',
				),
				'descByLimit'           => array(
					'type'        => 'string',
					'enum'        => array( 'default', 'word', 'character' ),
					'description' => 'default = full text; word/character = truncate + show Read More.',
					'default'     => 'default',
				),
				'caroByHeight'          => array(
					'type'        => 'string',
					'enum'        => array( '', 'height' ),
					'default'     => '',
					'description' => 'Set "height" to enforce contentHeight on carousel layout.',
				),
				'descLimit'             => array(
					'type'    => 'integer',
					'default' => 30,
				),
				'readMoreText'          => array(
					'type'    => 'string',
					'default' => 'Read More',
				),
				'readLessText'          => array(
					'type'    => 'string',
					'default' => 'Read Less',
				),
				'readBtnTypoSize'       => array(
					'type'    => 'integer',
					'default' => 14,
				),
				'readColor'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'readColorHover'        => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Title (post-title) ───────────────────────────────────── */
				'titleTypoSize'         => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'titleColor'            => array(
					'type'    => 'string',
					'default' => '',
				),
				'titleColorHover'       => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Author title (overlay) ───────────────────────────────── */
				'authorTitleTypoSize'   => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'authorTitleColor'      => array(
					'type'    => 'string',
					'default' => '',
				),
				'authorTitleColorHover' => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Designation ──────────────────────────────────────────── */
				'desigTypoSize'         => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'desigColor'            => array(
					'type'    => 'string',
					'default' => '',
				),
				'desigColorHover'       => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Content ──────────────────────────────────────────────── */
				'contentTypoSize'       => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'contentColor'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'contentColorHover'     => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Box / card ───────────────────────────────────────────── */
				'boxMargin'             => array( 'type' => 'object' ),
				'boxPadding'            => array( 'type' => 'object' ),
				'boxBorder'             => array( 'type' => 'object' ),
				'boxBorderHover'        => array( 'type' => 'object' ),
				'boxBorderRadius'       => array( 'type' => 'object' ),
				'boxBorderRadiusHover'  => array( 'type' => 'object' ),
				'boxBgColor'            => array(
					'type'    => 'string',
					'default' => '',
				),
				'boxBgColorHover'       => array(
					'type'    => 'string',
					'default' => '',
				),
				'boxShadow'             => array( 'type' => 'object' ),
				'boxShadowHover'        => array( 'type' => 'object' ),
				'arrowColor'            => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Style-1 speech-bubble arrow color.',
				),
				'arrowColorHover'       => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Avatar image ─────────────────────────────────────────── */
				'imgMaxWidth'           => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Avatar max-width in px.',
				),
				'imageBorderRadius'     => array( 'type' => 'object' ),
				'imageShadow'           => array( 'type' => 'object' ),
				'imageShadowHover'      => array( 'type' => 'object' ),

				/* ── Scrollbar ────────────────────────────────────────────── */
				'scrollWidth'           => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Scrollbar width in px.',
				),
				'scrollThumbBgColor'    => array(
					'type'    => 'string',
					'default' => '',
				),
				'scrollThumbRadius'     => array( 'type' => 'object' ),
				'scrollThumbShadow'     => array( 'type' => 'object' ),
				'scrollTrackBgColor'    => array(
					'type'    => 'string',
					'default' => '',
				),
				'scrollTrackRadius'     => array( 'type' => 'object' ),
				'scrollTrackShadow'     => array( 'type' => 'object' ),

				/* ── Carousel pagination dots ─────────────────────────────── */
				'dotsBgColor'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'dotsActiveBorderColor' => array(
					'type'    => 'string',
					'default' => '',
				),
				'dotsActiveBgColor'     => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Visibility / Globals ─────────────────────────────────── */
				'hideDesktop'           => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'hideTablet'            => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'hideMobile'            => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'globalClasses'         => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalId'              => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalCustomCss'       => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalWidth'           => array(
					'type'    => 'string',
					'enum'    => array( '', 'inline', 'full', 'custom' ),
					'default' => '',
				),
				'globalZindex'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalPosition'        => array(
					'type'    => 'string',
					'enum'    => array( '', 'relative', 'absolute', 'fixed', 'sticky' ),
					'default' => '',
				),
				'globalMargin'          => array( 'type' => 'object' ),
				'globalPadding'         => array( 'type' => 'object' ),
				'globalBgColor'         => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalBgHoverColor'    => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalBorder'          => array( 'type' => 'object' ),
				'globalBorderHover'     => array( 'type' => 'object' ),
				'globalBRadius'         => array( 'type' => 'object' ),
				'globalBRadiusHover'    => array( 'type' => 'object' ),
				'globalBShadow'         => array( 'type' => 'object' ),
				'globalBShadowHover'    => array( 'type' => 'object' ),
				'scrollAnimation'       => array(
					'type'    => 'string',
					'default' => '',
				),
				'animDuration'          => array(
					'type'    => 'string',
					'enum'    => array( '', 'slow', 'normal', 'fast' ),
					'default' => '',
				),
				'animDelay'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'animEasing'            => array(
					'type'    => 'string',
					'enum'    => array( '', 'linear', 'ease', 'ease-in', 'ease-out', 'ease-in-out' ),
					'default' => '',
				),

				'settings'              => array(
					'type'        => 'object',
					'description' => 'Raw attribute overrides (carousel/swiper options like dotsStyle, showDots, navigation arrows etc. live here).',
				),
				'fontFamily'            => array(
					'type'        => 'string',
					'description' => 'Font family name (e.g. "Inter", "Roboto", "Playfair Display"). When inspecting a URL via nexter-blocks/inspect-page, pass the returned fonts[].family value verbatim.',
					'default'     => '',
				),
				'fontType'              => array(
					'type'        => 'string',
					'description' => 'Font category — "sans-serif", "serif", "display", "handwriting", or "monospace".',
					'default'     => '',
				),
				'customFont'            => array(
					'type'        => 'string',
					'description' => 'Custom (non-Google) font name. Overrides fontFamily.',
					'default'     => '',
				),
				'fontWeight'            => array(
					'type'        => 'string',
					'description' => 'Font weight as a string ("100"..."900"). Embedded inside every typography group\'s fontFamily.fontWeight on this block. For per-group control, use the settings raw override or sprout/update-element.',
					'default'     => '',
				),
				'textDecoration'        => array(
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

		'execute_callback'    => 'tpgb_mcp_add_testimonials_ability',
		'permission_callback' => 'tpgb_mcp_add_testimonials_permission',
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
 * Permission callback for the add-testimonials ability.
 *
 * @param array|null $input Ability input arguments.
 * @return bool True when the current user may insert the block.
 */
function tpgb_mcp_add_testimonials_permission( ?array $input = null ): bool {
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
function tpgb_mcp_ts_spacing( array $v ): array {
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
function tpgb_mcp_ts_border( array $b ): array {
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
function tpgb_mcp_ts_radius( array $r ): array {
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
function tpgb_mcp_ts_bshadow( array $s ): array {
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
 * Build a Nexter Blocks background-color attribute.
 *
 * @param string $color CSS color value.
 * @return array Background attribute structured for the block.
 */
function tpgb_mcp_ts_bg( string $color ): array {
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
 * Build a Nexter Blocks typography attribute (font size only).
 *
 * @param int $size Font size in px.
 * @return array Typography attribute structured for the block.
 */
function tpgb_mcp_ts_typo( int $size ): array {
	return array(
		'openTypography' => 1,
		'size'           => array(
			'md'   => (string) absint( $size ),
			'unit' => 'px',
		),
		'height'         => '',
		'spacing'        => '',
		'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
	);
}
/**
 * Determine whether the block needs Nexter's wrapper rule for global styling.
 *
 * @param array $attrs Block attributes already gathered.
 * @return bool True if any wrapper-affecting attribute is present.
 */
function tpgb_mcp_ts_needs_wrapper( array $attrs ): bool {
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
 * Execute callback for the add-testimonials ability.
 *
 * @param array $input Ability input arguments.
 * @return array|WP_Error Result payload or WP_Error on failure.
 */
function tpgb_mcp_add_testimonials_ability( array $input ) {

	if ( ! defined( 'TPGB_VERSION' ) && ! defined( 'TPGBP_VERSION' ) ) {
		return new WP_Error( 'nexter_blocks_missing', __( 'Nexter Blocks must be active.', 'the-plus-addons-for-block-editor' ) );
	}

	$block_name = 'tpgb/tp-testimonials';
	if ( ! tpgb_mcp_has_registered_block( $block_name ) ) {
		return new WP_Error( 'block_missing', __( 'tpgb/tp-testimonials is not registered.', 'the-plus-addons-for-block-editor' ) );
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

	$block_id = tpgb_mcp_generate_block_id();
	$attrs    = array( 'block_id' => $block_id );

	/* ── Items ────────────────────────────────────────────────────────── */
	if ( ! empty( $input['items'] ) && is_array( $input['items'] ) ) {
		$rows = array();
		foreach ( $input['items'] as $i => $it ) {
			$rows[] = array(
				'_key'        => (string) $i,
				'testiTitle'  => sanitize_text_field( $it['name'] ?? 'John Doe' ),
				'designation' => sanitize_text_field( $it['designation'] ?? '' ),
				'content'     => tpgb_mcp_clean_text( $it['content'] ?? '' ),
				'authorTitle' => sanitize_text_field( $it['authorTitle'] ?? '' ),
				'avatar'      => array(
					'url' => esc_url_raw( $it['avatarUrl'] ?? '' ),
					'id'  => absint( $it['avatarId'] ?? 0 ),
				),
			);
		}
		$attrs['ItemRepeater'] = $rows;
	}

	/* ── Layout ───────────────────────────────────────────────────────── */
	if ( ! empty( $input['style'] ) && 'style-1' !== $input['style'] ) {
		$attrs['style'] = sanitize_text_field( $input['style'] ); }
	if ( ! empty( $input['styleLayout'] ) && 'style-1' !== $input['styleLayout'] ) {
		$attrs['styleLayout'] = sanitize_text_field( $input['styleLayout'] ); }
	if ( ! empty( $input['layout'] ) && 'carousel' !== $input['layout'] ) {
		$attrs['telayout'] = sanitize_key( $input['layout'] ); }

	$cd = isset( $input['columnsDesktop'] ) ? intval( $input['columnsDesktop'] ) : 6;
	$ct = isset( $input['columnsTablet'] ) ? intval( $input['columnsTablet'] ) : 6;
	$cm = isset( $input['columnsMobile'] ) ? intval( $input['columnsMobile'] ) : 12;
	if ( 6 !== $cd || 6 !== $ct || 12 !== $cm ) {
		$attrs['columns'] = array(
			'md' => (string) $cd,
			'sm' => (string) $ct,
			'xs' => (string) $cm,
		);
	}
	if ( ! empty( $input['columnSpace'] ) ) {
		$attrs['columnSpace'] = tpgb_mcp_ts_spacing( $input['columnSpace'] ); }
	if ( isset( $input['showBlockContent'] ) && false === $input['showBlockContent'] ) {
		$attrs['showBlockContent'] = false; }

	/* ── Content height / read-more ───────────────────────────────────── */
	if ( ! empty( $input['contentHeight'] ) ) {
		$attrs['contentHei'] = array(
			'md'   => (string) absint( $input['contentHeight'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['titleHeight'] ) ) {
		$attrs['titleHei'] = array(
			'md'   => (string) absint( $input['titleHeight'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['scrollOn'] ) && 'on-hover' !== $input['scrollOn'] ) {
		$attrs['cntscrollOn'] = sanitize_key( $input['scrollOn'] ); }
	if ( ! empty( $input['descByLimit'] ) && 'default' !== $input['descByLimit'] ) {
		$attrs['descByLimit'] = sanitize_key( $input['descByLimit'] ); }
	if ( ! empty( $input['caroByHeight'] ) ) {
		$attrs['caroByheight'] = sanitize_key( $input['caroByHeight'] ); }
	if ( ! empty( $input['descLimit'] ) && 30 !== intval( $input['descLimit'] ) ) {
		$attrs['descLimit'] = sanitize_text_field( (string) $input['descLimit'] ); }
	if ( ! empty( $input['readMoreText'] ) && 'Read More' !== $input['readMoreText'] ) {
		$attrs['redmorTxt'] = sanitize_text_field( $input['readMoreText'] ); }
	if ( ! empty( $input['readLessText'] ) && 'Read Less' !== $input['readLessText'] ) {
		$attrs['redlesTxt'] = sanitize_text_field( $input['readLessText'] ); }
	if ( ! empty( $input['readBtnTypoSize'] ) && 14 !== intval( $input['readBtnTypoSize'] ) ) {
		$attrs['readTypo'] = tpgb_mcp_ts_typo( (int) $input['readBtnTypoSize'] ); }
	if ( ! empty( $input['readColor'] ) && '#8072FC' !== $input['readColor'] ) {
		$attrs['readColor'] = sanitize_text_field( $input['readColor'] ); }
	if ( ! empty( $input['readColorHover'] ) && '#FF5A6E' !== $input['readColorHover'] ) {
		$attrs['readmhvrColor'] = sanitize_text_field( $input['readColorHover'] ); }

	/* ── Title ────────────────────────────────────────────────────────── */
	if ( ! empty( $input['titleTypoSize'] ) ) {
		$attrs['titleTypo'] = tpgb_mcp_ts_typo( (int) $input['titleTypoSize'] ); }
	if ( ! empty( $input['titleColor'] ) ) {
		$attrs['titleNormalColor'] = sanitize_text_field( $input['titleColor'] ); }
	if ( ! empty( $input['titleColorHover'] ) ) {
		$attrs['titleHoverColor'] = sanitize_text_field( $input['titleColorHover'] ); }

	/* ── Author title ─────────────────────────────────────────────────── */
	if ( ! empty( $input['authorTitleTypoSize'] ) ) {
		$attrs['AuthortitleTypo'] = tpgb_mcp_ts_typo( (int) $input['authorTitleTypoSize'] ); }
	if ( ! empty( $input['authorTitleColor'] ) ) {
		$attrs['AuthortitleNormalColor'] = sanitize_text_field( $input['authorTitleColor'] ); }
	if ( ! empty( $input['authorTitleColorHover'] ) ) {
		$attrs['AuthortitleHoverColor'] = sanitize_text_field( $input['authorTitleColorHover'] ); }

	/* ── Designation ──────────────────────────────────────────────────── */
	if ( ! empty( $input['desigTypoSize'] ) ) {
		$attrs['DesTypo'] = tpgb_mcp_ts_typo( (int) $input['desigTypoSize'] ); }
	if ( ! empty( $input['desigColor'] ) ) {
		$attrs['DesNormalColor'] = sanitize_text_field( $input['desigColor'] ); }
	if ( ! empty( $input['desigColorHover'] ) ) {
		$attrs['DesHoverColor'] = sanitize_text_field( $input['desigColorHover'] ); }

	/* ── Content ──────────────────────────────────────────────────────── */
	if ( ! empty( $input['contentTypoSize'] ) ) {
		$attrs['contentTypo'] = tpgb_mcp_ts_typo( (int) $input['contentTypoSize'] ); }
	if ( ! empty( $input['contentColor'] ) ) {
		$attrs['contentNormalColor'] = sanitize_text_field( $input['contentColor'] ); }
	if ( ! empty( $input['contentColorHover'] ) ) {
		$attrs['cntHovercolor'] = sanitize_text_field( $input['contentColorHover'] ); }

	/* ── Box ──────────────────────────────────────────────────────────── */
	if ( ! empty( $input['boxMargin'] ) ) {
		$attrs['boxMargin'] = tpgb_mcp_ts_spacing( $input['boxMargin'] ); }
	if ( ! empty( $input['boxPadding'] ) ) {
		$attrs['boxPadding'] = tpgb_mcp_ts_spacing( $input['boxPadding'] ); }
	if ( ! empty( $input['boxBorder'] ) ) {
		$attrs['boxBorder'] = tpgb_mcp_ts_border( $input['boxBorder'] ); }
	if ( ! empty( $input['boxBorderHover'] ) ) {
		$attrs['boxhvrBorder'] = tpgb_mcp_ts_border( $input['boxBorderHover'] ); }
	if ( ! empty( $input['boxBorderRadius'] ) ) {
		$attrs['boxBorderRadius'] = tpgb_mcp_ts_radius( $input['boxBorderRadius'] ); }
	if ( ! empty( $input['boxBorderRadiusHover'] ) ) {
		$attrs['boxBorderRadiusHover'] = tpgb_mcp_ts_radius( $input['boxBorderRadiusHover'] ); }
	if ( ! empty( $input['boxBgColor'] ) ) {
		$attrs['boxBg'] = tpgb_mcp_ts_bg( $input['boxBgColor'] ); }
	if ( ! empty( $input['boxBgColorHover'] ) ) {
		$attrs['boxBgHover'] = tpgb_mcp_ts_bg( $input['boxBgColorHover'] ); }
	if ( ! empty( $input['boxShadow'] ) ) {
		$attrs['boxBoxShadow'] = tpgb_mcp_ts_bshadow( $input['boxShadow'] ); }
	if ( ! empty( $input['boxShadowHover'] ) ) {
		$attrs['boxBoxShadowHover'] = tpgb_mcp_ts_bshadow( $input['boxShadowHover'] ); }
	if ( ! empty( $input['arrowColor'] ) ) {
		$attrs['arrowNormalColor'] = sanitize_text_field( $input['arrowColor'] ); }
	if ( ! empty( $input['arrowColorHover'] ) ) {
		$attrs['arrowHoverColor'] = sanitize_text_field( $input['arrowColorHover'] ); }

	/* ── Avatar ───────────────────────────────────────────────────────── */
	if ( ! empty( $input['imgMaxWidth'] ) ) {
		$attrs['imgMaxWidth'] = (string) absint( $input['imgMaxWidth'] ); }
	if ( ! empty( $input['imageBorderRadius'] ) ) {
		$attrs['imageBorderRadius'] = tpgb_mcp_ts_radius( $input['imageBorderRadius'] ); }
	if ( ! empty( $input['imageShadow'] ) ) {
		$attrs['imageBoxShadow'] = tpgb_mcp_ts_bshadow( $input['imageShadow'] ); }
	if ( ! empty( $input['imageShadowHover'] ) ) {
		$attrs['imageBoxShadowHover'] = tpgb_mcp_ts_bshadow( $input['imageShadowHover'] ); }

	/* ── Scrollbar ────────────────────────────────────────────────────── */
	if ( ! empty( $input['scrollWidth'] ) ) {
		$attrs['tesSclWidth'] = array(
			'md'   => (string) absint( $input['scrollWidth'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['scrollThumbBgColor'] ) ) {
		$attrs['tesThumbBg'] = tpgb_mcp_ts_bg( $input['scrollThumbBgColor'] ); }
	if ( ! empty( $input['scrollThumbRadius'] ) ) {
		$attrs['tesThumbBrs'] = tpgb_mcp_ts_radius( $input['scrollThumbRadius'] ); }
	if ( ! empty( $input['scrollThumbShadow'] ) ) {
		$attrs['tesThumbBsw'] = tpgb_mcp_ts_bshadow( $input['scrollThumbShadow'] ); }
	if ( ! empty( $input['scrollTrackBgColor'] ) ) {
		$attrs['tesTrackBg'] = tpgb_mcp_ts_bg( $input['scrollTrackBgColor'] ); }
	if ( ! empty( $input['scrollTrackRadius'] ) ) {
		$attrs['tesTrackBRs'] = tpgb_mcp_ts_radius( $input['scrollTrackRadius'] ); }
	if ( ! empty( $input['scrollTrackShadow'] ) ) {
		$attrs['tesTrackBsw'] = tpgb_mcp_ts_bshadow( $input['scrollTrackShadow'] ); }

	/* ── Pagination dots ──────────────────────────────────────────────── */
	if ( ! empty( $input['dotsBgColor'] ) ) {
		$attrs['dotsBgColor'] = sanitize_text_field( $input['dotsBgColor'] ); }
	if ( ! empty( $input['dotsActiveBorderColor'] ) ) {
		$attrs['dotsActiveBorderColor'] = sanitize_text_field( $input['dotsActiveBorderColor'] ); }
	if ( ! empty( $input['dotsActiveBgColor'] ) ) {
		$attrs['dotsActiveBgColor'] = sanitize_text_field( $input['dotsActiveBgColor'] ); }

	/* ── Visibility / Globals ─────────────────────────────────────────── */
	if ( ! empty( $input['hideDesktop'] ) ) {
		$attrs['globalHideDesktop'] = true; }
	if ( ! empty( $input['hideTablet'] ) ) {
		$attrs['globalHideTablet'] = true; }
	if ( ! empty( $input['hideMobile'] ) ) {
		$attrs['globalHideMobile'] = true; }
	if ( ! empty( $input['globalClasses'] ) ) {
		$attrs['globalClasses'] = sanitize_text_field( $input['globalClasses'] ); }
	if ( ! empty( $input['globalId'] ) ) {
		$attrs['globalId'] = sanitize_text_field( $input['globalId'] ); }
	if ( ! empty( $input['globalCustomCss'] ) ) {
		$attrs['globalCustomCss'] = wp_strip_all_tags( $input['globalCustomCss'] ); }
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
		$attrs['globalMargin'] = tpgb_mcp_ts_spacing( $input['globalMargin'] ); }
	if ( ! empty( $input['globalPadding'] ) ) {
		$attrs['globalPadding'] = tpgb_mcp_ts_spacing( $input['globalPadding'] ); }
	if ( ! empty( $input['globalBgColor'] ) ) {
		$attrs['globalBg'] = tpgb_mcp_ts_bg( $input['globalBgColor'] ); }
	if ( ! empty( $input['globalBgHoverColor'] ) ) {
		$attrs['globalBgHover'] = tpgb_mcp_ts_bg( $input['globalBgHoverColor'] ); }
	if ( ! empty( $input['globalBorder'] ) ) {
		$attrs['globalBorder'] = tpgb_mcp_ts_border( $input['globalBorder'] ); }
	if ( ! empty( $input['globalBorderHover'] ) ) {
		$attrs['globalBorderHover'] = tpgb_mcp_ts_border( $input['globalBorderHover'] ); }
	if ( ! empty( $input['globalBRadius'] ) ) {
		$attrs['globalBRadius'] = tpgb_mcp_ts_radius( $input['globalBRadius'] ); }
	if ( ! empty( $input['globalBRadiusHover'] ) ) {
		$attrs['globalBRadiusHover'] = tpgb_mcp_ts_radius( $input['globalBRadiusHover'] ); }
	if ( ! empty( $input['globalBShadow'] ) ) {
		$attrs['globalBShadow'] = tpgb_mcp_ts_bshadow( $input['globalBShadow'] ); }
	if ( ! empty( $input['globalBShadowHover'] ) ) {
		$attrs['globalBShadowHover'] = tpgb_mcp_ts_bshadow( $input['globalBShadowHover'] ); }
	if ( ! empty( $input['scrollAnimation'] ) ) {
		$attrs['globalAnim'] = array( 'md' => sanitize_text_field( $input['scrollAnimation'] ) ); }
	if ( ! empty( $input['animDuration'] ) ) {
		$attrs['globalAnimDuration'] = sanitize_text_field( $input['animDuration'] ); }
	if ( ! empty( $input['animDelay'] ) ) {
		$attrs['globalAnimDelay'] = array( 'md' => sanitize_text_field( $input['animDelay'] ) ); }
	if ( ! empty( $input['animEasing'] ) ) {
		$attrs['globalAnimEasing'] = sanitize_text_field( $input['animEasing'] ); }

	if ( tpgb_mcp_ts_needs_wrapper( $attrs ) ) {
		$attrs['tpgbDisrule'] = true; }

	tpgb_mcp_apply_typo_decoration( $attrs, $input );
	$attrs = tpgb_mcp_merge_block_settings( $attrs, $input['settings'] ?? array() );

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
