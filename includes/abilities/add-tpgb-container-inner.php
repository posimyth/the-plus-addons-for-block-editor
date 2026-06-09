<?php
/**
 * Ability: Add Nexter Blocks Inner Container (tpgb/tp-container-inner) to a Gutenberg page.
 *
 * @package The_Plus_Addons_For_Block_Editor
 * @since   1.3.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_register_ability(
	'nexter-blocks/add-tpgb-container-inner',
	array(
		'label'               => __( 'Add Nexter Blocks Inner Container', 'the-plus-addons-for-block-editor' ),
		'description'         => __( 'Adds the Nexter Blocks Inner Container block (tpgb/tp-container-inner) — a column wrapper used inside tp-container. Supports width, min-height, flex layout, background, border, box shadow, margin, padding, wrapper link, responsive visibility, and custom CSS. This block accepts inner blocks (nested content).', 'the-plus-addons-for-block-editor' ),
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
				'width'              => array(
					'type'        => 'integer',
					'description' => 'Column width in % on desktop.',
					'default'     => 50,
				),
				'minHeight'          => array(
					'type'        => 'integer',
					'description' => 'Minimum height in px.',
					'default'     => 0,
				),

				/* ── Flex settings ────────────────────────────────────────── */
				'flexDirection'      => array(
					'type'        => 'string',
					'enum'        => array( '', 'row', 'column' ),
					'description' => 'Flex direction.',
					'default'     => '',
				),
				'flexAlign'          => array(
					'type'        => 'string',
					'enum'        => array( '', 'flex-start', 'center', 'flex-end', 'stretch', 'baseline' ),
					'description' => 'Flex align-items.',
					'default'     => '',
				),
				'flexJustify'        => array(
					'type'        => 'string',
					'enum'        => array( '', 'flex-start', 'center', 'flex-end', 'space-between', 'space-around', 'space-evenly' ),
					'description' => 'Flex justify-content.',
					'default'     => '',
				),
				'flexGap'            => array(
					'type'        => 'integer',
					'description' => 'Flex gap in px.',
					'default'     => 0,
				),
				'flexWrap'           => array(
					'type'        => 'string',
					'enum'        => array( '', 'nowrap', 'wrap' ),
					'description' => 'Flex wrap behaviour.',
					'default'     => '',
				),

				/* ── Background ───────────────────────────────────────────── */
				'normalBgColor'      => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Background colour (normal).',
				),
				'hoverBgColor'       => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Background colour (hover).',
				),

				/* ── Border ───────────────────────────────────────────────── */
				'normalBorder'       => array(
					'type'        => 'object',
					'description' => 'Border (normal) {type,color,width:{top,right,bottom,left,unit}}.',
				),
				'hoverBorder'        => array(
					'type'        => 'object',
					'description' => 'Border (hover).',
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

				/* ── Spacing ──────────────────────────────────────────────── */
				'margin'             => array(
					'type'        => 'object',
					'description' => 'Margin {top,bottom,left,right,unit}.',
				),
				'padding'            => array(
					'type'        => 'object',
					'description' => 'Padding {top,bottom,left,right,unit}.',
				),
				'zIndex'             => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'CSS z-index value.',
				),

				/* ── Wrapper link ─────────────────────────────────────────── */
				'wrapLink'           => array(
					'type'        => 'boolean',
					'description' => 'Make entire column clickable as a link.',
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
					'description' => 'Raw attribute overrides merged directly into the block.',
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

		'execute_callback'    => 'tpgb_mcp_add_container_inner_ability',
		'permission_callback' => 'tpgb_mcp_add_container_inner_permission',
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
 * Permission callback for the add-container-inner ability.
 *
 * @param array|null $input Ability input arguments.
 * @return bool True when the current user may insert the block.
 */
function tpgb_mcp_add_container_inner_permission( ?array $input = null ): bool {
	if ( ! current_user_can( 'edit_posts' ) ) {
		return false;
	}
	$post_id = absint( $input['post_id'] ?? 0 );
	if ( $post_id > 0 && ! current_user_can( 'edit_post', $post_id ) ) {
		return false;
	}
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
function tpgb_mcp_ci_spacing( array $v ): array {
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
function tpgb_mcp_ci_border( array $b ): array {
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
function tpgb_mcp_ci_radius( array $r ): array {
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
function tpgb_mcp_ci_bg( string $color ): array {
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
 * Build a Nexter Blocks box-shadow attribute.
 *
 * @param array $s Raw shadow values {horizontal,vertical,blur,spread,color,inset}.
 * @return array Box-shadow attribute structured for the block.
 */
function tpgb_mcp_ci_bshadow( array $s ): array {
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

// -------------------------------------------------------------------------
// EXECUTE CALLBACK
// -------------------------------------------------------------------------

/**
 * Execute callback: insert a tpgb/tp-container-inner block into a post.
 *
 * @param array $input Ability input arguments.
 * @return array|WP_Error Ability result or error on failure.
 */
function tpgb_mcp_add_container_inner_ability( array $input ) {

	if ( ! defined( 'TPGB_VERSION' ) && ! defined( 'TPGBP_VERSION' ) ) {
		return new WP_Error( 'nexter_blocks_missing', __( 'Nexter Blocks must be active.', 'the-plus-addons-for-block-editor' ) );
	}

	$block_name = 'tpgb/tp-container-inner';

	if ( ! tpgb_mcp_has_registered_block( $block_name ) ) {
		return new WP_Error( 'block_missing', __( 'tpgb/tp-container-inner is not registered.', 'the-plus-addons-for-block-editor' ) );
	}

	$post_id  = absint( $input['post_id'] ?? 0 );
	$position = intval( $input['position'] ?? -1 );

	if ( $post_id <= 0 ) {
		return new WP_Error( 'missing_params', __( 'post_id is required.', 'the-plus-addons-for-block-editor' ) );
	}

	$post = get_post( $post_id );
	if ( ! $post instanceof WP_Post ) {
		return new WP_Error( 'invalid_post', __( 'Target post not found.', 'the-plus-addons-for-block-editor' ) );
	}

	$blocks = tpgb_mcp_get_blocks( $post_id );
	if ( is_wp_error( $blocks ) ) {
		return $blocks;
	}

	// ---------------------------------------------------------------------
	// Build attributes.
	// ---------------------------------------------------------------------
	$block_id = tpgb_mcp_generate_block_id();
	$attrs    = array( 'block_id' => $block_id );

	/* ── Layout ───────────────────────────────────────────────────────── */
	$width = absint( $input['width'] ?? 50 );
	if ( 50 !== $width ) {
		$attrs['Width'] = array(
			'md'     => $width,
			'sm'     => $width,
			'xs'     => 100,
			'unit'   => '%',
			'device' => 'md',
		);
	}

	if ( ! empty( $input['minHeight'] ) ) {
		$attrs['minHeight'] = array(
			'md'   => absint( $input['minHeight'] ),
			'unit' => 'px',
		);
	}

	/* ── Flex settings ────────────────────────────────────────────────── */
	if ( ! empty( $input['flexDirection'] ) ) {
		$attrs['flexDirection'] = array(
			'md' => sanitize_key( $input['flexDirection'] ),
			'sm' => '',
			'xs' => '',
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
	if ( ! empty( $input['flexGap'] ) ) {
		$attrs['flexGap'] = array(
			'md'   => (string) absint( $input['flexGap'] ),
			'unit' => 'px',
		);
	}
	if ( ! empty( $input['flexWrap'] ) ) {
		$attrs['flexwrap'] = array(
			'md' => sanitize_key( $input['flexWrap'] ),
			'sm' => '',
			'xs' => '',
		);
	}

	/* ── Background ───────────────────────────────────────────────────── */
	if ( ! empty( $input['normalBgColor'] ) ) {
		$attrs['NormalBg'] = tpgb_mcp_ci_bg( $input['normalBgColor'] );
	}
	if ( ! empty( $input['hoverBgColor'] ) ) {
		$attrs['HoverBg'] = tpgb_mcp_ci_bg( $input['hoverBgColor'] );
	}

	/* ── Border ───────────────────────────────────────────────────────── */
	if ( ! empty( $input['normalBorder'] ) ) {
		$attrs['NormalBorder'] = tpgb_mcp_ci_border( $input['normalBorder'] );
	}
	if ( ! empty( $input['hoverBorder'] ) ) {
		$attrs['HoverBorder'] = tpgb_mcp_ci_border( $input['hoverBorder'] );
	}
	if ( ! empty( $input['normalBRadius'] ) ) {
		$attrs['NormalBradius'] = tpgb_mcp_ci_radius( $input['normalBRadius'] );
	}
	if ( ! empty( $input['hoverBRadius'] ) ) {
		$attrs['HoverBradius'] = tpgb_mcp_ci_radius( $input['hoverBRadius'] );
	}

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

	/* ── Spacing ──────────────────────────────────────────────────────── */
	if ( ! empty( $input['margin'] ) ) {
		$attrs['Margin'] = tpgb_mcp_ci_spacing( $input['margin'] );
	}
	if ( ! empty( $input['padding'] ) ) {
		$attrs['Padding'] = tpgb_mcp_ci_spacing( $input['padding'] );
	}
	if ( ! empty( $input['zIndex'] ) ) {
		$attrs['ZIndex'] = sanitize_text_field( $input['zIndex'] );
	}

	/* ── Wrapper link ─────────────────────────────────────────────────── */
	if ( ! empty( $input['wrapLink'] ) ) {
		$attrs['wrapLink'] = true;
		if ( ! empty( $input['wrapLinkUrl'] ) ) {
			$attrs['colUrl'] = array(
				'url'      => esc_url_raw( $input['wrapLinkUrl'] ),
				'target'   => ( $input['wrapLinkTarget'] ?? '' ) === '_blank' ? '_blank' : '',
				'nofollow' => '',
			);
		}
	}

	/* ── Identity ─────────────────────────────────────────────────────── */
	if ( ! empty( $input['customClass'] ) ) {
		$attrs['customClasses'] = sanitize_text_field( $input['customClass'] );
	}
	if ( ! empty( $input['customId'] ) ) {
		$attrs['customId'] = sanitize_text_field( $input['customId'] );
	}
	if ( ! empty( $input['customCss'] ) ) {
		$attrs['customCss'] = wp_strip_all_tags( $input['customCss'] );
	}
	if ( ! empty( $input['anchor'] ) ) {
		$attrs['anchor'] = sanitize_title( $input['anchor'] );
	}

	/* ── Responsive visibility ────────────────────────────────────────── */
	if ( ! empty( $input['hideDesktop'] ) ) {
		$attrs['hideDesktop'] = true;
	}
	if ( ! empty( $input['hideTablet'] ) ) {
		$attrs['hideTablet'] = true;
	}
	if ( ! empty( $input['hideMobile'] ) ) {
		$attrs['hideMobile'] = true;
	}

	/* ── Raw settings override ────────────────────────────────────────── */
	$attrs = tpgb_mcp_merge_block_settings( $attrs, $input['settings'] ?? array() );

	// ---------------------------------------------------------------------
	// Build, insert, save (dynamic block with inner blocks).
	// ---------------------------------------------------------------------
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
		return $save_result;
	}

	return array(
		'block_id'   => $block_id,
		'block_name' => $block_name,
		'post_id'    => $post_id,
	);
}
