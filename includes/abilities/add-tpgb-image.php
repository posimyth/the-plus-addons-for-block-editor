<?php
/**
 * Ability: Add Nexter Blocks Image (tpgb/tp-image) to a Gutenberg page.
 *
 * @package The_Plus_Addons_For_Block_Editor
 * @since   1.3.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_register_ability(
	'nexter-blocks/add-tpgb-image',
	array(
		'label'               => __( 'Add Nexter Blocks Image', 'the-plus-addons-for-block-editor' ),
		'description'         => __( 'Adds the Nexter Blocks Image block (tpgb/tp-image) — a standard image display block with caption support (default or custom), link wrapper, size/width/height control, object-fit, opacity, CSS filters (normal + hover), border, border radius, box shadow, caption typography/colour/background/alignment, lazy-load toggle, and position controls. Lighter and simpler than tpgb/tp-creative-image — use this for straightforward image placements. This is a dynamic block.', 'the-plus-addons-for-block-editor' ),
		'category'            => 'nexter-blocks',

		'input_schema'        => array(
			'type'                 => 'object',
			'properties'           => array(

				/* ── Core ─────────────────────────────────────────────────── */
				'post_id'                 => array( 'type' => 'integer' ),
				'position'                => array(
					'type'    => 'integer',
					'default' => -1,
				),
				'parent_block_id'         => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Image ────────────────────────────────────────────────── */
				'imageUrl'                => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Image URL.',
				),
				'imageId'                 => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'WordPress media attachment ID. Preferred over imageUrl.',
				),
				'imageSize'               => array(
					'type'        => 'string',
					'description' => 'WordPress image size: "full", "large", "medium", "thumbnail", or custom size.',
					'default'     => 'full',
				),

				/* ── Caption ──────────────────────────────────────────────── */
				'captionType'             => array(
					'type'        => 'string',
					'enum'        => array( 'none', 'default', 'custom' ),
					'description' => '"none" = no caption; "default" = use media library caption; "custom" = use customCaption text.',
					'default'     => 'none',
				),
				'customCaption'           => array(
					'type'        => 'string',
					'description' => 'Custom caption text (when captionType is "custom").',
					'default'     => '',
				),

				/* ── Link ─────────────────────────────────────────────────── */
				'linkUrl'                 => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'URL to wrap image in link.',
				),
				'linkTarget'              => array(
					'type'    => 'string',
					'enum'    => array( '_self', '_blank' ),
					'default' => '_self',
				),
				'linkNofollow'            => array(
					'type'    => 'boolean',
					'default' => false,
				),

				/* ── Layout ───────────────────────────────────────────────── */
				'alignment'               => array(
					'type'    => 'string',
					'enum'    => array( '', 'left', 'center', 'right' ),
					'default' => '',
				),
				'imageWidth'              => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Width in px.',
				),
				'imageMaxWidth'           => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Max width in px.',
				),
				'imageHeight'             => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Height in px.',
				),
				'objectFit'               => array(
					'type'        => 'string',
					'enum'        => array( '', 'cover', 'contain', 'fill', 'scale-down', 'none' ),
					'description' => 'CSS object-fit.',
					'default'     => '',
				),

				/* ── Opacity ──────────────────────────────────────────────── */
				'opacity'                 => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Image opacity 0-1.',
				),
				'hoverOpacity'            => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Image opacity on hover.',
				),
				'hoverTransitionDuration' => array(
					'type'        => 'string',
					'default'     => '0.3',
					'description' => 'Hover transition duration in seconds.',
				),

				/* ── Border & Shadow ──────────────────────────────────────── */
				'border'                  => array(
					'type'        => 'object',
					'description' => 'Image border {type,color,width}.',
				),
				'borderRadius'            => array(
					'type'        => 'object',
					'description' => 'Image border radius.',
				),
				'enableShadow'            => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'shadowH'                 => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'shadowV'                 => array(
					'type'    => 'integer',
					'default' => 4,
				),
				'shadowBlur'              => array(
					'type'    => 'integer',
					'default' => 8,
				),
				'shadowSpread'            => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'shadowColor'             => array(
					'type'    => 'string',
					'default' => 'rgba(0,0,0,0.40)',
				),

				/* ── Caption styling ──────────────────────────────────────── */
				'enableCaptionTypo'       => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'captionTypoSize'         => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'captionColor'            => array(
					'type'    => 'string',
					'default' => '',
				),
				'captionBgColor'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'captionAlignment'        => array(
					'type'    => 'string',
					'enum'    => array( '', 'left', 'center', 'right' ),
					'default' => '',
				),
				'captionSpacing'          => array(
					'type'        => 'integer',
					'default'     => 15,
					'description' => 'Space between image and caption in px.',
				),
				'enableCaptionShadow'     => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'captionShadowH'          => array(
					'type'    => 'integer',
					'default' => 2,
				),
				'captionShadowV'          => array(
					'type'    => 'integer',
					'default' => 3,
				),
				'captionShadowBlur'       => array(
					'type'    => 'integer',
					'default' => 8,
				),
				'captionShadowColor'      => array(
					'type'    => 'string',
					'default' => 'rgba(0,0,0,0.5)',
				),

				/* ── Lazy load ────────────────────────────────────────────── */
				'disableLazyLoad'         => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Disable WordPress lazy loading for this image.',
				),

				/* ── Position ─────────────────────────────────────────────── */
				'absolutePosition'        => array(
					'type'    => 'string',
					'enum'    => array( '', 'relative', 'absolute', 'fixed', 'sticky' ),
					'default' => '',
				),
				'horizontalOffset'        => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'verticalOffset'          => array(
					'type'    => 'integer',
					'default' => 0,
				),

				/* ── Scroll animation ────────────────────────────────────── */
				'scrollAnimation'         => array(
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
				'animDuration'            => array(
					'type'    => 'string',
					'enum'    => array( '', 'slow', 'normal', 'fast' ),
					'default' => '',
				),
				'animDelay'               => array(
					'type'    => 'string',
					'default' => '',
				),
				'animEasing'              => array(
					'type'    => 'string',
					'enum'    => array( '', 'linear', 'ease', 'ease-in', 'ease-out', 'ease-in-out' ),
					'default' => '',
				),

				/* ── Advanced ─────────────────────────────────────────────── */
				'hideDesktop'             => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'hideTablet'              => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'hideMobile'              => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'globalClasses'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalId'                => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalCustomCss'         => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalWidth'             => array(
					'type'    => 'string',
					'enum'    => array( '', 'inline', 'full', 'custom' ),
					'default' => '',
				),
				'globalZindex'            => array(
					'type'    => 'string',
					'default' => '',
				),
				'transitionDuration'      => array(
					'type'    => 'string',
					'default' => '',
				),
				'transitionFunction'      => array(
					'type'    => 'string',
					'enum'    => array( '', 'ease', 'ease-in', 'ease-out', 'ease-in-out', 'linear' ),
					'default' => '',
				),
				'transitionOrigin'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalMargin'            => array( 'type' => 'object' ),
				'globalPadding'           => array( 'type' => 'object' ),
				'globalBgColor'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalBgHoverColor'      => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalBorder'            => array( 'type' => 'object' ),
				'globalBorderHover'       => array( 'type' => 'object' ),
				'globalBRadius'           => array( 'type' => 'object' ),
				'globalBRadiusHover'      => array( 'type' => 'object' ),
				'globalBShadow'           => array( 'type' => 'object' ),
				'globalBShadowHover'      => array( 'type' => 'object' ),

				'rotateDeg'               => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotateX'                 => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotateY'                 => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotatePerspective'       => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotateDegHover'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotateXHover'            => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotateYHover'            => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotatePersHover'         => array(
					'type'    => 'string',
					'default' => '',
				),
				'offsetX'                 => array(
					'type'    => 'string',
					'default' => '',
				),
				'offsetY'                 => array(
					'type'    => 'string',
					'default' => '',
				),
				'offsetZ'                 => array(
					'type'    => 'string',
					'default' => '',
				),
				'offsetXHover'            => array(
					'type'    => 'string',
					'default' => '',
				),
				'offsetYHover'            => array(
					'type'    => 'string',
					'default' => '',
				),
				'offsetZHover'            => array(
					'type'    => 'string',
					'default' => '',
				),
				'scaleValue'              => array(
					'type'    => 'string',
					'default' => '',
				),
				'scaleKeepRatio'          => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'scaleValueHover'         => array(
					'type'    => 'string',
					'default' => '',
				),
				'skewX'                   => array(
					'type'    => 'string',
					'default' => '',
				),
				'skewY'                   => array(
					'type'    => 'string',
					'default' => '',
				),
				'skewXHover'              => array(
					'type'    => 'string',
					'default' => '',
				),
				'skewYHover'              => array(
					'type'    => 'string',
					'default' => '',
				),
				'flipHorizontal'          => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'flipVertical'            => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'flipHorizontalHover'     => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'flipVerticalHover'       => array(
					'type'    => 'boolean',
					'default' => false,
				),

				'settings'                => array( 'type' => 'object' ),
				'fontFamily'              => array(
					'type'        => 'string',
					'description' => 'Font family name (e.g. "Inter", "Roboto", "Playfair Display"). When inspecting a URL via nexter-blocks/inspect-page, pass the returned fonts[].family value verbatim.',
					'default'     => '',
				),
				'fontType'                => array(
					'type'        => 'string',
					'description' => 'Font category — "sans-serif", "serif", "display", "handwriting", or "monospace".',
					'default'     => '',
				),
				'customFont'              => array(
					'type'        => 'string',
					'description' => 'Custom (non-Google) font name. Overrides fontFamily.',
					'default'     => '',
				),
				'fontWeight'              => array(
					'type'        => 'string',
					'description' => 'Font weight as a string ("100"..."900"). Embedded inside every typography group\'s fontFamily.fontWeight on this block. For per-group control, use the settings raw override or sprout/update-element.',
					'default'     => '',
				),
				'textDecoration'          => array(
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

		'execute_callback'    => 'tpgb_mcp_add_image_ability',
		'permission_callback' => 'tpgb_mcp_add_image_permission',
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
 * Permission callback for the add-image ability.
 *
 * @param array|null $input Ability input arguments.
 * @return bool True when the current user may insert the block.
 */
function tpgb_mcp_add_image_permission( ?array $input = null ): bool {
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
function tpgb_mcp_img_spacing( array $v ): array {
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
function tpgb_mcp_img_border( array $b ): array {
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
function tpgb_mcp_img_radius( array $r ): array {
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
function tpgb_mcp_img_bshadow( array $s ): array {
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
function tpgb_mcp_img_bg( string $color ): array {
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
function tpgb_mcp_img_needs_wrapper( array $attrs ): bool {
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
 * Execute callback: insert a tpgb/tp-image block into a post.
 *
 * @param array $input Ability input arguments.
 * @return array|WP_Error Ability result or error on failure.
 */
function tpgb_mcp_add_image_ability( array $input ) {

	if ( ! defined( 'TPGB_VERSION' ) && ! defined( 'TPGBP_VERSION' ) ) {
		return new WP_Error( 'nexter_blocks_missing', __( 'Nexter Blocks must be active.', 'the-plus-addons-for-block-editor' ) );
	}

	$block_name = 'tpgb/tp-image';
	if ( ! tpgb_mcp_has_registered_block( $block_name ) ) {
		return new WP_Error( 'block_missing', __( 'tpgb/tp-image is not registered.', 'the-plus-addons-for-block-editor' ) );
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

	/* ── Image ────────────────────────────────────────────────────────── */
	$img_obj = array(
		'url' => '',
		'Id'  => '',
	);
	if ( ! empty( $input['imageId'] ) ) {
		$img_obj['Id'] = absint( $input['imageId'] );
		$src           = wp_get_attachment_image_url( $img_obj['Id'], 'full' );
		if ( $src ) {
			$img_obj['url'] = $src; }
	} elseif ( ! empty( $input['imageUrl'] ) ) {
		$img_obj['url'] = esc_url_raw( $input['imageUrl'] );
	}
	if ( ! empty( $img_obj['url'] ) ) {
		$attrs['tImg'] = $img_obj; }

	if ( ! empty( $input['imageSize'] ) && 'full' !== $input['imageSize'] ) {
		$attrs['iSize'] = sanitize_key( $input['imageSize'] );
	}

	/* ── Caption ──────────────────────────────────────────────────────── */
	$caption_type = sanitize_key( $input['captionType'] ?? 'none' );
	if ( 'none' !== $caption_type ) {
		$attrs['tiCap'] = $caption_type;
		if ( 'custom' === $caption_type && ! empty( $input['customCaption'] ) ) {
			$attrs['ctmCap'] = tpgb_mcp_clean_text( $input['customCaption'] );
		}
	}

	/* ── Link ─────────────────────────────────────────────────────────── */
	if ( ! empty( $input['linkUrl'] ) ) {
		$attrs['tiLink'] = array(
			'url'      => esc_url_raw( $input['linkUrl'] ),
			'target'   => '_blank' === ( $input['linkTarget'] ?? '' ) ? '_blank' : '',
			'nofollow' => ! empty( $input['linkNofollow'] ) ? 'on' : '',
		);
	}

	/* ── Layout ───────────────────────────────────────────────────────── */
	if ( ! empty( $input['alignment'] ) ) {
		$attrs['tiAlign'] = array(
			'md' => sanitize_key( $input['alignment'] ),
			'sm' => '',
			'xs' => '',
		);
	}
	if ( ! empty( $input['imageWidth'] ) ) {
		$attrs['iWidth'] = array(
			'md'   => (string) absint( $input['imageWidth'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['imageMaxWidth'] ) ) {
		$attrs['imWidth'] = array(
			'md'   => (string) absint( $input['imageMaxWidth'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['imageHeight'] ) ) {
		$attrs['iHeig'] = array(
			'md'   => (string) absint( $input['imageHeight'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['objectFit'] ) ) {
		$attrs['imgFit'] = array( 'md' => sanitize_key( $input['objectFit'] ) ); }

	/* ── Opacity ──────────────────────────────────────────────────────── */
	if ( isset( $input['opacity'] ) && '' !== $input['opacity'] ) {
		$attrs['inOpa'] = sanitize_text_field( $input['opacity'] ); }
	if ( isset( $input['hoverOpacity'] ) && '' !== $input['hoverOpacity'] ) {
		$attrs['iHOpa'] = sanitize_text_field( $input['hoverOpacity'] ); }
	if ( ! empty( $input['hoverTransitionDuration'] ) && '0.3' !== $input['hoverTransitionDuration'] ) {
		$attrs['intran'] = sanitize_text_field( $input['hoverTransitionDuration'] );
	}

	/* ── Border & shadow ──────────────────────────────────────────────── */
	if ( ! empty( $input['border'] ) ) {
		$attrs['ibord'] = tpgb_mcp_img_border( $input['border'] ); }
	if ( ! empty( $input['borderRadius'] ) ) {
		$attrs['ibrad'] = tpgb_mcp_img_radius( $input['borderRadius'] ); }
	if ( ! empty( $input['enableShadow'] ) ) {
		$attrs['ishadow'] = array(
			'openShadow' => true,
			'inset'      => 0,
			'horizontal' => (string) intval( $input['shadowH'] ?? 0 ),
			'vertical'   => (string) intval( $input['shadowV'] ?? 4 ),
			'blur'       => (string) absint( $input['shadowBlur'] ?? 8 ),
			'spread'     => (string) intval( $input['shadowSpread'] ?? 0 ),
			'color'      => sanitize_text_field( $input['shadowColor'] ?? 'rgba(0,0,0,0.40)' ),
		);
	}

	/* ── Caption styling ──────────────────────────────────────────────── */
	if ( ! empty( $input['enableCaptionTypo'] ) ) {
		$attrs['icapTypo'] = array(
			'openTypography' => 1,
			'size'           => array(
				'md'   => ! empty( $input['captionTypoSize'] ) ? (string) absint( $input['captionTypoSize'] ) : '',
				'unit' => 'px',
			),
			'height'         => '',
			'spacing'        => '',
			'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
		);
	}
	if ( ! empty( $input['captionColor'] ) ) {
		$attrs['icapColor'] = sanitize_text_field( $input['captionColor'] ); }
	if ( ! empty( $input['captionBgColor'] ) ) {
		$attrs['icapbgColor'] = sanitize_text_field( $input['captionBgColor'] ); }
	if ( ! empty( $input['captionAlignment'] ) ) {
		$attrs['icapAlign'] = array(
			'md' => sanitize_key( $input['captionAlignment'] ),
			'sm' => '',
			'xs' => '',
		); }
	if ( isset( $input['captionSpacing'] ) && 15 !== (int) $input['captionSpacing'] ) {
		$attrs['icapSpa'] = array(
			'md'   => (string) absint( $input['captionSpacing'] ),
			'unit' => 'px',
		);
	}
	if ( ! empty( $input['enableCaptionShadow'] ) ) {
		$attrs['caShadow'] = array(
			'openShadow' => true,
			'typeShadow' => 'text-shadow',
			'horizontal' => intval( $input['captionShadowH'] ?? 2 ),
			'vertical'   => intval( $input['captionShadowV'] ?? 3 ),
			'blur'       => absint( $input['captionShadowBlur'] ?? 8 ),
			'color'      => sanitize_text_field( $input['captionShadowColor'] ?? 'rgba(0,0,0,0.5)' ),
		);
	}

	/* ── Lazy load ────────────────────────────────────────────────────── */
	if ( ! empty( $input['disableLazyLoad'] ) ) {
		$attrs['dislazyLoad'] = true; }

	/* ── Position ─────────────────────────────────────────────────────── */
	if ( ! empty( $input['absolutePosition'] ) ) {
		$attrs['globalPosition'] = array(
			'md' => sanitize_key( $input['absolutePosition'] ),
			'sm' => '',
			'xs' => '',
		);
	}
	if ( ! empty( $input['horizontalOffset'] ) ) {
		$attrs['glohoriOffset'] = array(
			'md'   => (string) intval( $input['horizontalOffset'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['verticalOffset'] ) ) {
		$attrs['gloverticalOffset'] = array(
			'md'   => (string) intval( $input['verticalOffset'] ),
			'unit' => 'px',
		); }

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

	/* ── Transition ───────────────────────────────────────────────────── */
	if ( ! empty( $input['transitionDuration'] ) ) {
		$attrs['gTraDur'] = sanitize_text_field( $input['transitionDuration'] ); }
	if ( ! empty( $input['transitionFunction'] ) ) {
		$attrs['gTraFunc'] = sanitize_text_field( $input['transitionFunction'] ); }
	if ( ! empty( $input['transitionOrigin'] ) ) {
		$attrs['gTraOrigin'] = sanitize_text_field( $input['transitionOrigin'] );   }

	/* ── Global: Spacing/Bg/Border/Shadow ─────────────────────────────── */
	if ( ! empty( $input['globalMargin'] ) ) {
		$attrs['globalMargin'] = tpgb_mcp_img_spacing( $input['globalMargin'] );  }
	if ( ! empty( $input['globalPadding'] ) ) {
		$attrs['globalPadding'] = tpgb_mcp_img_spacing( $input['globalPadding'] ); }
	if ( ! empty( $input['globalBgColor'] ) ) {
		$attrs['globalBg'] = tpgb_mcp_img_bg( $input['globalBgColor'] );      }
	if ( ! empty( $input['globalBgHoverColor'] ) ) {
		$attrs['globalBgHover'] = tpgb_mcp_img_bg( $input['globalBgHoverColor'] ); }
	if ( ! empty( $input['globalBorder'] ) ) {
		$attrs['globalBorder'] = tpgb_mcp_img_border( $input['globalBorder'] );       }
	if ( ! empty( $input['globalBorderHover'] ) ) {
		$attrs['globalBorderHover'] = tpgb_mcp_img_border( $input['globalBorderHover'] );  }
	if ( ! empty( $input['globalBRadius'] ) ) {
		$attrs['globalBRadius'] = tpgb_mcp_img_radius( $input['globalBRadius'] );      }
	if ( ! empty( $input['globalBRadiusHover'] ) ) {
		$attrs['globalBRadiusHover'] = tpgb_mcp_img_radius( $input['globalBRadiusHover'] ); }
	if ( ! empty( $input['globalBShadow'] ) ) {
		$attrs['globalBShadow'] = tpgb_mcp_img_bshadow( $input['globalBShadow'] );      }
	if ( ! empty( $input['globalBShadowHover'] ) ) {
		$attrs['globalBShadowHover'] = tpgb_mcp_img_bshadow( $input['globalBShadowHover'] ); }

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
	if ( tpgb_mcp_img_needs_wrapper( $attrs ) ) {
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
