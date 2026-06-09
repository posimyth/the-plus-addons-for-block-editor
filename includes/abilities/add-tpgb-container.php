<?php
/**
 * Ability: Add Nexter Blocks Container (tpgb/tp-container) to a Gutenberg page.
 *
 * @package The_Plus_Addons_For_Block_Editor
 * @since   1.3.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_register_ability(
	'nexter-blocks/add-tpgb-container',
	array(
		'label'               => __( 'Add Nexter Blocks Container', 'the-plus-addons-for-block-editor' ),
		'description'         => __( 'Adds the Nexter Blocks Container block (tpgb/tp-container) — a layout container that segments pages into rows. Supports flex and grid layouts, content width (wide/full), min-height, gutter spacing, flex direction/align/justify/wrap, background, border, box shadow, shape dividers, wrapper link, margin, padding, z-index, overflow, custom tag, responsive visibility, and custom CSS. This is a dynamic block that accepts inner blocks (nested content).', 'the-plus-addons-for-block-editor' ),
		'category'            => 'nexter-blocks',

		'input_schema'        => array(
			'type'                 => 'object',
			'properties'           => array(

				/* ── Core ─────────────────────────────────────────────────── */
				'post_id'            => array(
					'type'        => 'integer',
					'description' => 'WordPress page/post ID to insert the block into.',
				),
				'position'           => array(
					'type'        => 'integer',
					'default'     => -1,
					'description' => 'Zero-based insert position. Use -1 to append.',
				),
				'parent_block_id'    => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Optional block_id of a parent container block. When provided, this block is inserted INSIDE the parent as an inner block instead of at the page top level.',
				),

				/* ── Layout ───────────────────────────────────────────────── */
				'contentWidth'       => array(
					'type'        => 'string',
					'enum'        => array( 'wide', 'full' ),
					'description' => 'Content width mode. "wide" = boxed container with max-width; "full" = full viewport width.',
					'default'     => 'wide',
				),
				'containerWide'      => array(
					'type'        => 'integer',
					'description' => 'Container max-width in % when contentWidth is "wide". Default theme width if empty.',
					'default'     => 0,
				),
				'selectedLayout'     => array(
					'type'        => 'string',
					'enum'        => array( '', 'grid' ),
					'description' => 'Layout mode. Empty = flex layout (default), "grid" = CSS grid layout.',
					'default'     => '',
				),
				'columns'            => array(
					'type'        => 'integer',
					'description' => 'Number of columns (for flex layout presets).',
					'default'     => 0,
				),

				/* ── Flex settings ────────────────────────────────────────── */
				'flexDirection'      => array(
					'type'        => 'string',
					'enum'        => array( '', 'row', 'column' ),
					'description' => 'Flex direction on desktop. If empty, auto-defaults to "column" for single-column containers (children stack vertically) and "row" for multi-column. Mobile (xs) always falls back to "column" so columns stack on phones. Ignored when selectedLayout is "grid".',
					'default'     => '',
				),
				'flexAlign'          => array(
					'type'        => 'string',
					'enum'        => array( '', 'flex-start', 'center', 'flex-end', 'stretch', 'baseline' ),
					'description' => 'Flex align-items (cross-axis alignment). For row direction this is vertical alignment; for column direction it is horizontal. Ignored when selectedLayout is "grid".',
					'default'     => '',
				),
				'flexJustify'        => array(
					'type'        => 'string',
					'enum'        => array( '', 'flex-start', 'center', 'flex-end', 'space-between', 'space-around', 'space-evenly' ),
					'description' => 'Flex justify-content (main-axis distribution). For row direction this is horizontal; for column direction it is vertical. Ignored when selectedLayout is "grid".',
					'default'     => '',
				),
				'flexWrap'           => array(
					'type'        => 'string',
					'enum'        => array( '', 'nowrap', 'wrap' ),
					'description' => 'Flex wrap behaviour. If empty and columns > 1, auto-defaults to "wrap" so columns reflow on narrow viewports. Ignored when selectedLayout is "grid".',
					'default'     => '',
				),
				'flexGap'            => array(
					'type'        => 'integer',
					'description' => 'Flex gap between items in px.',
					'default'     => 0,
				),
				'flexReverse'        => array(
					'type'        => 'boolean',
					'description' => 'Reverse flex direction on desktop.',
					'default'     => false,
				),

				/* ── Height ───────────────────────────────────────────────── */
				'height'             => array(
					'type'        => 'string',
					'enum'        => array( '', 'min-height', 'fit', '100vh' ),
					'description' => 'Height mode. "" = default, "min-height" = custom min-height, "fit" = fit to content, "100vh" = full viewport.',
					'default'     => '',
				),
				'minHeight'          => array(
					'type'        => 'integer',
					'description' => 'Min-height in px when height is "min-height".',
					'default'     => 300,
				),

				/* ── Gutter / spacing ─────────────────────────────────────── */
				'gutterSpace'        => array(
					'type'        => 'integer',
					'description' => 'Gutter space (column padding) in px.',
					'default'     => 15,
				),

				/* ── Extra options ─────────────────────────────────────────── */
				'tagName'            => array(
					'type'        => 'string',
					'enum'        => array( 'div', 'header', 'footer', 'main', 'article', 'section', 'aside', 'nav' ),
					'description' => 'HTML semantic tag for the container.',
					'default'     => 'div',
				),
				'overflow'           => array(
					'type'        => 'string',
					'enum'        => array( '', 'hidden', 'auto' ),
					'description' => 'CSS overflow property.',
					'default'     => '',
				),
				'wrapLink'           => array(
					'type'        => 'boolean',
					'description' => 'Make entire container clickable as a link.',
					'default'     => false,
				),
				'wrapLinkUrl'        => array(
					'type'        => 'string',
					'description' => 'URL for wrapper link when wrapLink is enabled.',
					'default'     => '',
				),
				'wrapLinkTarget'     => array(
					'type'    => 'string',
					'enum'    => array( '_self', '_blank' ),
					'default' => '_self',
				),

				/* ── Background ───────────────────────────────────────────── */
				'normalBgColor'      => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Container background colour (normal).',
				),
				'hoverBgColor'       => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Container background colour (hover).',
				),

				/* ── Border ───────────────────────────────────────────────── */
				'normalBorder'       => array(
					'type'        => 'object',
					'description' => 'Container border (normal) {type,color,width:{top,right,bottom,left,unit}}.',
				),
				'hoverBorder'        => array(
					'type'        => 'object',
					'description' => 'Container border (hover).',
				),
				'normalBRadius'      => array(
					'type'        => 'object',
					'description' => 'Border radius (normal) {top,bottom,left,right,unit}.',
				),
				'hoverBRadius'       => array(
					'type'        => 'object',
					'description' => 'Border radius (hover).',
				),

				/* ── Box shadow ───────────────────────────────────────────── */
				'enableNormalShadow' => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'normalShadowH'      => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'normalShadowV'      => array(
					'type'    => 'integer',
					'default' => 4,
				),
				'normalShadowBlur'   => array(
					'type'    => 'integer',
					'default' => 8,
				),
				'normalShadowSpread' => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'normalShadowColor'  => array(
					'type'    => 'string',
					'default' => 'rgba(0,0,0,0.40)',
				),
				'enableHoverShadow'  => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'hoverShadowH'       => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'hoverShadowV'       => array(
					'type'    => 'integer',
					'default' => 4,
				),
				'hoverShadowBlur'    => array(
					'type'    => 'integer',
					'default' => 8,
				),
				'hoverShadowSpread'  => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'hoverShadowColor'   => array(
					'type'    => 'string',
					'default' => 'rgba(0,0,0,0.40)',
				),

				/* ── Shape divider ────────────────────────────────────────── */
				'shapeTop'           => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Shape divider for top edge (shape name key).',
				),
				'shapeBottom'        => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Shape divider for bottom edge (shape name key).',
				),

				/* ── Spacing ──────────────────────────────────────────────── */
				'margin'             => array(
					'type'        => 'object',
					'description' => 'Container margin {top,bottom,left,right,unit}.',
				),
				'padding'            => array(
					'type'        => 'object',
					'description' => 'Container padding {top,bottom,left,right,unit}.',
				),
				'zIndex'             => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'CSS z-index value.',
				),

				/* ── Identity ─────────────────────────────────────────────── */
				'customClass'        => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Extra CSS classes.',
				),
				'customId'           => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Custom HTML id.',
				),
				'customCss'          => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Custom CSS scoped to this block.',
				),
				'anchor'             => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'HTML anchor id for in-page links.',
				),

				/* ── Responsive visibility ─────────────────────────────────── */
				'hideDesktop'        => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'hideTablet'         => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'hideMobile'         => array(
					'type'    => 'boolean',
					'default' => false,
				),

				/* ── Raw override ───────────────────────────────────────── */
				'settings'           => array(
					'type'        => 'object',
					'description' => 'Raw attribute overrides merged directly into the block. Internal names: NormalBg, HoverBg, NormalBorder, HoverBorder, NormalBradius, HoverBradius, NormalBShadow, HoverBShadow, Margin, Padding, ZIndex, flexDirection, flexAlign, flexJustify, flexwrap, flexGap, etc.',
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

		'execute_callback'    => 'tpgb_mcp_add_container_ability',
		'permission_callback' => 'tpgb_mcp_add_container_permission',
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
// PERMISSION CALLBACK
// -------------------------------------------------------------------------

/**
 * Permission callback for the add-container ability.
 *
 * @param array|null $input Ability input arguments.
 * @return bool True when the current user may insert the block.
 */
function tpgb_mcp_add_container_permission( ?array $input = null ): bool {
	if ( ! current_user_can( 'edit_posts' ) ) {
		return false; }
	$post_id = absint( $input['post_id'] ?? 0 );
	if ( $post_id > 0 && ! current_user_can( 'edit_post', $post_id ) ) {
		return false; }
	return true;
}

// -------------------------------------------------------------------------
// HELPERS
// -------------------------------------------------------------------------

/**
 * Build a Nexter Blocks spacing attribute from {top,bottom,left,right,unit}.
 *
 * @param array $v Raw spacing values.
 * @return array Spacing attribute structured for the block.
 */
function tpgb_mcp_container_spacing( array $v ): array {
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
function tpgb_mcp_container_border( array $b ): array {
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
function tpgb_mcp_container_radius( array $r ): array {
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
 * Build a Nexter Blocks solid-colour background attribute.
 *
 * @param string $color Background colour value.
 * @return array Background attribute structured for the block.
 */
function tpgb_mcp_container_bg( string $color ): array {
	return array(
		'openBg'         => 1,
		'bgType'         => 'color',
		'videoSource'    => 'local',
		'bgDefaultColor' => sanitize_text_field( $color ),
		'bgGradient'     => '',
		'isCustom'       => 'fpp',
	);
}

// -------------------------------------------------------------------------
// EXECUTE CALLBACK
// -------------------------------------------------------------------------

/**
 * Execute callback: insert a tpgb/tp-container block into a post.
 *
 * @param array $input Ability input arguments.
 * @return array|WP_Error Ability result or error on failure.
 */
function tpgb_mcp_add_container_ability( array $input ) {

	if ( ! defined( 'TPGB_VERSION' ) && ! defined( 'TPGBP_VERSION' ) ) {
		return new WP_Error( 'nexter_blocks_missing', __( 'Nexter Blocks must be active.', 'the-plus-addons-for-block-editor' ) );
	}

	$block_name = 'tpgb/tp-container';

	if ( ! tpgb_mcp_has_registered_block( $block_name ) ) {
		return new WP_Error( 'block_missing', __( 'tpgb/tp-container is not registered.', 'the-plus-addons-for-block-editor' ) );
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
	// Build attributes (only non-defaults).
	// ---------------------------------------------------------------------
	$block_id = tpgb_mcp_generate_block_id();
	$attrs    = array( 'block_id' => $block_id );

	/* ── Layout ───────────────────────────────────────────────────────── */
	$content_width = sanitize_key( $input['contentWidth'] ?? 'wide' );
	if ( 'wide' !== $content_width ) {
		$attrs['contentWidth'] = $content_width;
		$attrs['align']        = 'full';
	}

	if ( ! empty( $input['containerWide'] ) ) {
		$attrs['containerWide'] = array(
			'md'   => (string) absint( $input['containerWide'] ),
			'unit' => '%',
		);
	}

	$selected_layout         = sanitize_key( $input['selectedLayout'] ?? '' );
	$attrs['selectedLayout'] = ( 'grid' === $selected_layout ) ? 'grid' : 'flex';

	/*
	Default columns to 1 so editor skips the "Select Your Structure" picker.
	 * The editor shows that picker whenever `columns` is empty/0.
	 */
	$num_columns = absint( $input['columns'] ?? 0 );
	if ( $num_columns < 1 ) {
		$num_columns = 1; }
	$attrs['columns'] = $num_columns;

	/*
	Set colDir/gridStyle to a known preset so the picker is bypassed.
	 * For flex layouts we always use "c100" (single-column-fluid) — even when
	 * num_columns > 1 — so the editor's templateLock:'all' branch is skipped
	 * (see classes/blocks/tp-container/edit.js: the lock is only applied when
	 * colDir is a multi-column preset like "50-50", "25-50-25", etc.).
	 *
	 * The visual multi-column layout still works because:
	 *   - We set flexDirection.md = "row" on the parent.
	 *   - Each generated tp-container-inner has an explicit Width (e.g. 50%).
	 * With "c100" + templateLock:false, inner columns can be freely
	 * added / removed / reordered in the editor.
	 *
	 * Grid: "grid-1" = 2-col, "grid-3" = 3-col, "grid-5" = 4-col, "grid-6" = 6-col.
	 */
	if ( 'grid' !== $selected_layout ) {
		$attrs['colDir'] = 'c100';
	} else {
		$grid_presets       = array(
			2 => 'grid-1',
			3 => 'grid-3',
			4 => 'grid-5',
			6 => 'grid-6',
		);
		$attrs['gridStyle'] = $grid_presets[ $num_columns ] ?? 'grid-1';
		$attrs['colDir']    = $attrs['gridStyle'];
	}

	/* ── Flex settings ────────────────────────────────────────────────── */
	if ( ! empty( $input['flexDirection'] ) ) {
		$attrs['flexDirection'] = array(
			'md' => sanitize_key( $input['flexDirection'] ),
			'sm' => '',
			'xs' => 'column',
		);
	}
	if ( ! empty( $input['flexAlign'] ) ) {
		$attrs['flexAlign'] = array(
			'md' => sanitize_text_field( $input['flexAlign'] ),
			'sm' => '',
			'xs' => '',
		);
	}
	if ( ! empty( $input['flexJustify'] ) ) {
		$attrs['flexJustify'] = array(
			'md' => sanitize_text_field( $input['flexJustify'] ),
			'sm' => '',
			'xs' => '',
		);
	}
	if ( ! empty( $input['flexWrap'] ) ) {
		$attrs['flexwrap'] = array(
			'md' => sanitize_key( $input['flexWrap'] ),
			'sm' => '',
			'xs' => '',
		);
	}
	if ( ! empty( $input['flexGap'] ) ) {
		$attrs['flexGap'] = array(
			'md'   => (string) absint( $input['flexGap'] ),
			'unit' => 'px',
		);
	}
	if ( ! empty( $input['flexReverse'] ) ) {
		$attrs['flexreverse'] = true;
	}

	/* ── Height ───────────────────────────────────────────────────────── */
	if ( ! empty( $input['height'] ) ) {
		$attrs['height'] = sanitize_key( $input['height'] );
		if ( 'min-height' === $input['height'] && isset( $input['minHeight'] ) && 300 !== (int) $input['minHeight'] ) {
			$attrs['minHeight'] = array(
				'md'   => absint( $input['minHeight'] ),
				'unit' => 'px',
			);
		}
	}

	/* ── Gutter ───────────────────────────────────────────────────────── */
	if ( isset( $input['gutterSpace'] ) && 15 !== (int) $input['gutterSpace'] ) {
		$attrs['gutterSpace'] = array(
			'md'   => absint( $input['gutterSpace'] ),
			'unit' => 'px',
		);
	}

	/* ── Extra options ────────────────────────────────────────────────── */
	$allowed_tags = array( 'div', 'header', 'footer', 'main', 'article', 'section', 'aside', 'nav' );
	if ( ! empty( $input['tagName'] ) && 'div' !== $input['tagName'] ) {
		$tag = sanitize_key( $input['tagName'] );
		if ( in_array( $tag, $allowed_tags, true ) ) {
			$attrs['tagName'] = $tag; }
	}
	if ( ! empty( $input['overflow'] ) ) {
		$attrs['overflow'] = sanitize_key( $input['overflow'] ); }

	if ( ! empty( $input['wrapLink'] ) ) {
		$attrs['wrapLink'] = true;
		if ( ! empty( $input['wrapLinkUrl'] ) ) {
			$attrs['rowUrl'] = array(
				'url'      => esc_url_raw( $input['wrapLinkUrl'] ),
				'target'   => '_blank' === ( $input['wrapLinkTarget'] ?? '' ) ? '_blank' : '',
				'nofollow' => '',
			);
		}
	}

	/* ── Background ───────────────────────────────────────────────────── */
	if ( ! empty( $input['normalBgColor'] ) ) {
		$attrs['NormalBg'] = tpgb_mcp_container_bg( $input['normalBgColor'] ); }
	if ( ! empty( $input['hoverBgColor'] ) ) {
		$attrs['HoverBg'] = tpgb_mcp_container_bg( $input['hoverBgColor'] );  }

	/* ── Border ───────────────────────────────────────────────────────── */
	if ( ! empty( $input['normalBorder'] ) ) {
		$attrs['NormalBorder'] = tpgb_mcp_container_border( $input['normalBorder'] ); }
	if ( ! empty( $input['hoverBorder'] ) ) {
		$attrs['HoverBorder'] = tpgb_mcp_container_border( $input['hoverBorder'] );  }
	if ( ! empty( $input['normalBRadius'] ) ) {
		$attrs['NormalBradius'] = tpgb_mcp_container_radius( $input['normalBRadius'] ); }
	if ( ! empty( $input['hoverBRadius'] ) ) {
		$attrs['HoverBradius'] = tpgb_mcp_container_radius( $input['hoverBRadius'] );  }

	/* ── Box shadow ───────────────────────────────────────────────────── */
	if ( ! empty( $input['enableNormalShadow'] ) ) {
		$attrs['NormalBShadow'] = array(
			'openShadow' => true,
			'inset'      => 0,
			'horizontal' => (string) intval( $input['normalShadowH'] ?? 0 ),
			'vertical'   => (string) intval( $input['normalShadowV'] ?? 4 ),
			'blur'       => (string) absint( $input['normalShadowBlur'] ?? 8 ),
			'spread'     => (string) intval( $input['normalShadowSpread'] ?? 0 ),
			'color'      => sanitize_text_field( $input['normalShadowColor'] ?? 'rgba(0,0,0,0.40)' ),
		);
	}
	if ( ! empty( $input['enableHoverShadow'] ) ) {
		$attrs['HoverBShadow'] = array(
			'openShadow' => true,
			'inset'      => 0,
			'horizontal' => (string) intval( $input['hoverShadowH'] ?? 0 ),
			'vertical'   => (string) intval( $input['hoverShadowV'] ?? 4 ),
			'blur'       => (string) absint( $input['hoverShadowBlur'] ?? 8 ),
			'spread'     => (string) intval( $input['hoverShadowSpread'] ?? 0 ),
			'color'      => sanitize_text_field( $input['hoverShadowColor'] ?? 'rgba(0,0,0,0.40)' ),
		);
	}

	/* ── Shape dividers ───────────────────────────────────────────────── */
	if ( ! empty( $input['shapeTop'] ) ) {
		$attrs['shapeTop'] = sanitize_key( $input['shapeTop'] ); }
	if ( ! empty( $input['shapeBottom'] ) ) {
		$attrs['shapeBottom'] = sanitize_key( $input['shapeBottom'] ); }

	/* ── Spacing ──────────────────────────────────────────────────────── */
	if ( ! empty( $input['margin'] ) ) {
		$attrs['Margin'] = tpgb_mcp_container_spacing( $input['margin'] );  }
	if ( ! empty( $input['padding'] ) ) {
		$attrs['Padding'] = tpgb_mcp_container_spacing( $input['padding'] ); }
	if ( ! empty( $input['zIndex'] ) ) {
		$attrs['ZIndex'] = sanitize_text_field( $input['zIndex'] ); }

	/* ── Identity ─────────────────────────────────────────────────────── */
	if ( ! empty( $input['customClass'] ) ) {
		$attrs['customClass'] = sanitize_text_field( $input['customClass'] ); }
	if ( ! empty( $input['customId'] ) ) {
		$attrs['customId'] = sanitize_text_field( $input['customId'] ); }
	if ( ! empty( $input['customCss'] ) ) {
		$attrs['customCss'] = wp_strip_all_tags( $input['customCss'] ); }
	if ( ! empty( $input['anchor'] ) ) {
		$attrs['anchor'] = sanitize_title( $input['anchor'] ); }

	/* ── Responsive visibility ────────────────────────────────────────── */
	if ( ! empty( $input['hideDesktop'] ) ) {
		$attrs['HideDesktop'] = true; }
	if ( ! empty( $input['hideTablet'] ) ) {
		$attrs['HideTablet'] = true; }
	if ( ! empty( $input['hideMobile'] ) ) {
		$attrs['HideMobile'] = true; }

	/* ── Raw settings override ────────────────────────────────────────── */
	$attrs = tpgb_mcp_merge_block_settings( $attrs, $input['settings'] ?? array() );

	// ---------------------------------------------------------------------
	// Build, insert, save (dynamic block with inner blocks).
	// ---------------------------------------------------------------------
	$block = tpgb_mcp_build_block( $block_name, $attrs );

	/* ── Auto-create inner column blocks when columns specified ────────── */
	$block['innerBlocks'] = array();

	if ( $num_columns > 1 && 'grid' !== $selected_layout ) {
		// Multi-column flex: create tp-container-inner blocks as columns.
		// Single-column (c100) does NOT need inner wrappers — content goes directly into the container.
		$col_width = round( 100 / $num_columns, 2 );
		if ( empty( $attrs['flexDirection'] ) ) {
			$attrs['flexDirection'] = array(
				'md' => 'row',
				'sm' => '',
				'xs' => 'column',
			);
		}
		$block['attrs'] = $attrs;

		for ( $i = 0; $i < $num_columns; $i++ ) {
			$inner_id               = tpgb_mcp_generate_block_id();
			$inner_attrs            = array(
				'block_id' => $inner_id,
				'Width'    => array(
					'md'     => $col_width,
					'sm'     => $col_width,
					'xs'     => 100,
					'unit'   => '%',
					'device' => 'md',
				),
			);
			$inner_block            = tpgb_mcp_build_block( 'tpgb/tp-container-inner', $inner_attrs );
			$block['innerBlocks'][] = $inner_block;
		}

		// innerContent needs null entries for each inner block.
		$block['innerContent'] = array_fill( 0, $num_columns, null );
	} elseif ( 'grid' === $selected_layout && $num_columns > 1 ) {
		// Grid layout: match editor grid presets.
		// Grid presets: grid-1(2col×1row=2), grid-2(1col×2row=2), grid-3(3col×1row=3),
		// grid-4(1col×3row=3), grid-5(2col×2row=4), grid-6(3col×2row=6).
		$grid_presets = array(
			2 => array(
				'style' => 'grid-1',
				'col'   => 2,
				'row'   => 1,
				'items' => 2,
			),
			3 => array(
				'style' => 'grid-3',
				'col'   => 3,
				'row'   => 1,
				'items' => 3,
			),
			4 => array(
				'style' => 'grid-5',
				'col'   => 2,
				'row'   => 2,
				'items' => 4,
			),
			6 => array(
				'style' => 'grid-6',
				'col'   => 3,
				'row'   => 2,
				'items' => 6,
			),
		);

		// Find best matching preset, or fallback to custom grid.
		$preset = $grid_presets[ $num_columns ] ?? null;

		if ( $preset ) {
			$grid_cols          = $preset['col'];
			$grid_rows          = $preset['row'];
			$grid_items         = $preset['items'];
			$attrs['gridStyle'] = $preset['style'];
		} else {
			// Custom grid: calculate sensible col/row split.
			$grid_cols          = min( $num_columns, 4 );
			$grid_rows          = max( 1, (int) ceil( $num_columns / $grid_cols ) );
			$grid_items         = $num_columns;
			$attrs['gridStyle'] = 'grid-1';
		}

		$attrs['noofGrid']     = $grid_items;
		$attrs['contentWidth'] = 'full';
		$attrs['align']        = 'full';

		// Build columnsRepeater (one entry per grid column).
		$col_repeater = array();
		for ( $i = 0; $i < $grid_cols; $i++ ) {
			$col_repeater[] = array(
				'gridProperty' => array( 'md' => 'custom' ),
				'gridMin'      => array(
					'md'   => '10',
					'unit' => 'px',
				),
				'gridMax'      => array(
					'md'   => '1',
					'unit' => 'fr',
				),
				'gridWidth'    => array(
					'md'   => '1',
					'unit' => 'fr',
				),
			);
		}
		$attrs['columnsRepeater'] = $col_repeater;

		// Build rowsRepeater (one entry per grid row).
		$row_repeater = array();
		for ( $i = 0; $i < $grid_rows; $i++ ) {
			$row_repeater[] = array(
				'gridRowProperty' => array( 'md' => 'custom' ),
				'gridRowMin'      => array(
					'md'   => '10',
					'unit' => 'px',
				),
				'gridRowMax'      => array(
					'md'   => '1',
					'unit' => 'fr',
				),
				'gridHeight'      => array(
					'md'   => '1',
					'unit' => 'fr',
				),
			);
		}
		$attrs['rowsRepeater'] = $row_repeater;

		// Update block attrs before creating inner blocks.
		$block['attrs'] = $attrs;

		// Create inner container blocks as grid items.
		for ( $i = 0; $i < $grid_items; $i++ ) {
			$grid_id                = tpgb_mcp_generate_block_id();
			$grid_block             = tpgb_mcp_build_block(
				'tpgb/tp-container',
				array(
					'block_id'       => $grid_id,
					'iscontGrid'     => true,
					'selectedLayout' => 'flex',
					'columns'        => 1,
					'colDir'         => 'c100',
					'flexDirection'  => array(
						'md' => 'row',
						'xs' => 'column',
					),
					'gridStyle'      => 'grid-1',
					'contentWidth'   => 'full',
					'align'          => 'full',
				)
			);
			$block['innerBlocks'][] = $grid_block;
		}

		$block['innerContent'] = array_fill( 0, $grid_items, null );
	}

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
