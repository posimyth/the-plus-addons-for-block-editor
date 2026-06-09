<?php
/**
 * Ability: Add Nexter Blocks Hover Card (tpgb/tp-hovercard) to a Gutenberg page.
 *
 * @package The_Plus_Addons_For_Block_Editor
 * @since   1.3.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_register_ability(
	'nexter-blocks/add-tpgb-hovercard',
	array(
		'label'               => __( 'Add Nexter Blocks Hover Card', 'the-plus-addons-for-block-editor' ),
		'description'         => __(
			'Adds the Nexter Blocks Hover Card block (tpgb/tp-hovercard) — a flexible HTML structure builder that creates hoverable card layouts by composing nested HTML tags (div, a, span, p, h1-h6) with content types (text, image, custom HTML, or container-only). Each card is built from a list of "items" where each item is either an open tag, close tag, or content element. Supports per-element positioning, typography, shadows, backgrounds, borders, and link wrappers. This is a dynamic block.

USAGE: Pass a "cards" array of item objects. Each item represents one HTML element:
  - { openTag: "div", content: "none" } → starts a div container
  - { openTag: "a", aLink: {url: "..."}, content: "none" } → starts an anchor
  - { openTag: "h3", content: "text", cntText: "Title" } → opens h3 with text
  - { content: "text", cntText: "Some text" } → standalone text
  - { content: "img", imageUrl: "..." } → an image element
  - { closeTag: "close" } → closes the most recent open tag

IMPORTANT: Every openTag must have a matching closeTag in the correct order (nested properly).',
			'the-plus-addons-for-block-editor'
		),
		'category'            => 'nexter-blocks',

		'input_schema'        => array(
			'type'                 => 'object',
			'properties'           => array(

				/* ── Core ─────────────────────────────────────────────────── */
				'post_id'             => array( 'type' => 'integer' ),
				'position'            => array(
					'type'    => 'integer',
					'default' => -1,
				),
				'parent_block_id'     => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Cards ────────────────────────────────────────────────── */
				'cards'               => array(
					'type'        => 'array',
					'description' => 'Array of card item objects. Each item is an HTML element in the structure. Items can open tags, add content, or close tags.',
					'items'       => array(
						'type'       => 'object',
						'properties' => array(
							'openTag'        => array(
								'type'        => 'string',
								'enum'        => array( '', 'none', 'div', 'a', 'span', 'p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'section', 'article' ),
								'description' => 'HTML tag to open. Use "none" or omit to skip opening a tag.',
							),
							'closeTag'       => array(
								'type'        => 'string',
								'enum'        => array( '', 'close', 'none' ),
								'description' => 'Set to "close" to close the most recent open tag.',
							),
							'content'        => array(
								'type'        => 'string',
								'enum'        => array( 'none', 'text', 'img', 'html' ),
								'description' => 'Content type inside this element. "none" = container only.',
								'default'     => 'none',
							),
							'cntText'        => array(
								'type'        => 'string',
								'description' => 'Text content (when content is "text" or "html"). Supports HTML for "html" type.',
							),
							'imageUrl'       => array(
								'type'        => 'string',
								'description' => 'Image URL (when content is "img").',
							),
							'imageId'        => array(
								'type'        => 'integer',
								'description' => 'Media attachment ID (preferred over imageUrl).',
							),
							'linkUrl'        => array(
								'type'        => 'string',
								'description' => 'URL for anchor tag (when openTag is "a").',
							),
							'linkTarget'     => array(
								'type'    => 'string',
								'enum'    => array( '_self', '_blank' ),
								'default' => '_self',
							),
							'linkNofollow'   => array(
								'type'    => 'boolean',
								'default' => false,
							),
							'position'       => array(
								'type'        => 'string',
								'enum'        => array( 'relative', 'absolute', 'fixed' ),
								'default'     => 'relative',
								'description' => 'CSS position of the element.',
							),
							'textColor'      => array(
								'type'        => 'string',
								'description' => 'Text colour for this element.',
							),
							'bgColor'        => array(
								'type'        => 'string',
								'description' => 'Background colour for this element.',
							),
							'hoverTextColor' => array( 'type' => 'string' ),
							'hoverBgColor'   => array( 'type' => 'string' ),
						),
					),
				),

				/* ── Scroll animation ────────────────────────────────────── */
				'scrollAnimation'     => array(
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
				'animDuration'        => array(
					'type'    => 'string',
					'enum'    => array( '', 'slow', 'normal', 'fast' ),
					'default' => '',
				),
				'animDelay'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'animEasing'          => array(
					'type'    => 'string',
					'enum'    => array( '', 'linear', 'ease', 'ease-in', 'ease-out', 'ease-in-out' ),
					'default' => '',
				),

				/* ── Advanced ─────────────────────────────────────────────── */
				'hideDesktop'         => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'hideTablet'          => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'hideMobile'          => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'globalClasses'       => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalId'            => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalCustomCss'     => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalWidth'         => array(
					'type'    => 'string',
					'enum'    => array( '', 'inline', 'full', 'custom' ),
					'default' => '',
				),
				'globalZindex'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalPosition'      => array(
					'type'    => 'string',
					'enum'    => array( '', 'relative', 'absolute', 'fixed', 'sticky' ),
					'default' => '',
				),
				'transitionDuration'  => array(
					'type'    => 'string',
					'default' => '',
				),
				'transitionFunction'  => array(
					'type'    => 'string',
					'enum'    => array( '', 'ease', 'ease-in', 'ease-out', 'ease-in-out', 'linear' ),
					'default' => '',
				),
				'transitionOrigin'    => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalMargin'        => array( 'type' => 'object' ),
				'globalPadding'       => array( 'type' => 'object' ),
				'globalBgColor'       => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalBgHoverColor'  => array(
					'type'    => 'string',
					'default' => '',
				),
				'globalBorder'        => array( 'type' => 'object' ),
				'globalBorderHover'   => array( 'type' => 'object' ),
				'globalBRadius'       => array( 'type' => 'object' ),
				'globalBRadiusHover'  => array( 'type' => 'object' ),
				'globalBShadow'       => array( 'type' => 'object' ),
				'globalBShadowHover'  => array( 'type' => 'object' ),

				'rotateDeg'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotateX'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotateY'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotatePerspective'   => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotateDegHover'      => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotateXHover'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotateYHover'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotatePersHover'     => array(
					'type'    => 'string',
					'default' => '',
				),
				'offsetX'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'offsetY'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'offsetZ'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'offsetXHover'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'offsetYHover'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'offsetZHover'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'scaleValue'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'scaleKeepRatio'      => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'scaleValueHover'     => array(
					'type'    => 'string',
					'default' => '',
				),
				'skewX'               => array(
					'type'    => 'string',
					'default' => '',
				),
				'skewY'               => array(
					'type'    => 'string',
					'default' => '',
				),
				'skewXHover'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'skewYHover'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'flipHorizontal'      => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'flipVertical'        => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'flipHorizontalHover' => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'flipVerticalHover'   => array(
					'type'    => 'boolean',
					'default' => false,
				),

				'settings'            => array( 'type' => 'object' ),
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

		'execute_callback'    => 'tpgb_mcp_add_hovercard_ability',
		'permission_callback' => 'tpgb_mcp_add_hovercard_permission',
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
 * Permission callback for the add-hovercard ability.
 *
 * @param array|null $input Ability input arguments.
 * @return bool True when the current user may insert the block.
 */
function tpgb_mcp_add_hovercard_permission( ?array $input = null ): bool {
	if ( ! current_user_can( 'edit_posts' ) ) {
		return false; }
	$post_id = absint( $input['post_id'] ?? 0 );
	if ( $post_id > 0 && ! current_user_can( 'edit_post', $post_id ) ) {
		return false; }
	return true;
}

/**
 * Build hovercard spacing values.
 *
 * @param array $v Spacing values.
 * @return array Sanitized spacing values.
 */
function tpgb_mcp_hcard_spacing( array $v ): array {
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
 * Build hovercard border values.
 *
 * @param array $b Border values.
 * @return array Sanitized border values.
 */
function tpgb_mcp_hcard_border( array $b ): array {
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
 * Build hovercard border radius values.
 *
 * @param array $r Border radius values.
 * @return array Sanitized border radius values.
 */
function tpgb_mcp_hcard_radius( array $r ): array {
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
 * Build hovercard box shadow values.
 *
 * @param array $s Box shadow values.
 * @return array Sanitized box shadow values.
 */
function tpgb_mcp_hcard_bshadow( array $s ): array {
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
 * Build hovercard background values.
 *
 * @param string $color Background colour.
 * @return array Sanitized background values.
 */
function tpgb_mcp_hcard_bg( string $color ): array {
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
 * Check if hovercard needs a wrapper element.
 *
 * @param array $attrs Hovercard attributes.
 * @return bool True if a wrapper is needed.
 */
function tpgb_mcp_hcard_needs_wrapper( array $attrs ): bool {
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
 * Build a hovercard item from input data.
 *
 * @param int   $index Item index in the cards array.
 * @param array $card  Item data from input.
 * @return array Sanitized item data for block attributes.
 */
function tpgb_mcp_hcard_build_item( int $index, array $card ): array {
	$item = array(
		'_key'          => (string) $index,
		'openTag'       => 'div',
		'content'       => 'none',
		'closeTag'      => '',
		'cntText'       => '',
		'position'      => 'relative',
		'txtTypo'       => array( 'openTypography' => 0 ),
		'normalBshadow' => array( 'openShadow' => 0 ),
		'HvrBshadow'    => array( 'openShadow' => 0 ),
		'txtShadow'     => array( 'openShadow' => 0 ),
		'txtHvrShadow'  => array( 'openShadow' => 0 ),
		'imgBShadow'    => array( 'openShadow' => 0 ),
		'HvrimgBShadow' => array( 'openShadow' => 0 ),
		'aLink'         => array( 'url' => '' ),
	);

	// Open tag.
	if ( isset( $card['openTag'] ) && '' !== $card['openTag'] ) {
		$item['openTag'] = sanitize_key( $card['openTag'] );
	} elseif ( isset( $card['closeTag'] ) && 'close' === $card['closeTag'] ) {
		$item['openTag'] = 'none';
	}

	// Close tag.
	if ( ! empty( $card['closeTag'] ) && 'close' === $card['closeTag'] ) {
		$item['closeTag'] = 'close';
	}

	// Content type.
	$content_type    = sanitize_key( $card['content'] ?? 'none' );
	$item['content'] = in_array( $content_type, array( 'none', 'text', 'img', 'html' ), true ) ? $content_type : 'none';

	// Text/HTML content.
	if ( in_array( $item['content'], array( 'text', 'html' ), true ) && ! empty( $card['cntText'] ) ) {
		$item['cntText'] = tpgb_mcp_clean_text( (string) $card['cntText'] );
	}

	// Image content.
	if ( 'img' === $item['content'] ) {
		$img = array( 'url' => '' );
		if ( ! empty( $card['imageId'] ) ) {
			$img['id'] = absint( $card['imageId'] );
			$src       = wp_get_attachment_image_url( $img['id'], 'full' );
			if ( $src ) {
				$img['url'] = $src; }
		} elseif ( ! empty( $card['imageUrl'] ) ) {
			$img['url'] = esc_url_raw( $card['imageUrl'] );
		}
		$item['imageName'] = $img;
	}

	// Link (for <a> tags).
	if ( 'a' === $item['openTag'] && ! empty( $card['linkUrl'] ) ) {
		$item['aLink'] = array(
			'url'      => esc_url_raw( $card['linkUrl'] ),
			'target'   => ( $card['linkTarget'] ?? '' ) === '_blank' ? '_blank' : '',
			'nofollow' => ! empty( $card['linkNofollow'] ) ? 'on' : '',
		);
	}

	// Position.
	if ( ! empty( $card['position'] ) && 'relative' !== $card['position'] ) {
		$item['position'] = sanitize_key( $card['position'] );
	}

	// Per-item colours.
	if ( ! empty( $card['textColor'] ) ) {
		$item['txtColor'] = sanitize_text_field( $card['textColor'] ); }
	if ( ! empty( $card['bgColor'] ) ) {
		$item['bgColor'] = tpgb_mcp_hcard_bg( $card['bgColor'] ); }
	if ( ! empty( $card['hoverTextColor'] ) ) {
		$item['hvrTxtColor'] = sanitize_text_field( $card['hoverTextColor'] ); }
	if ( ! empty( $card['hoverBgColor'] ) ) {
		$item['hvrBgColor'] = tpgb_mcp_hcard_bg( $card['hoverBgColor'] ); }

	return $item;
}

/**
 * Execute callback for the add-hovercard ability.
 *
 * @param array $input Ability input arguments.
 * @return array|WP_Error Block data on success, WP_Error on failure.
 */
function tpgb_mcp_add_hovercard_ability( array $input ) {

	if ( ! defined( 'TPGB_VERSION' ) && ! defined( 'TPGBP_VERSION' ) ) {
		return new WP_Error( 'nexter_blocks_missing', __( 'Nexter Blocks must be active.', 'the-plus-addons-for-block-editor' ) );
	}

	$block_name = 'tpgb/tp-hovercard';
	if ( ! tpgb_mcp_has_registered_block( $block_name ) ) {
		return new WP_Error( 'block_missing', __( 'tpgb/tp-hovercard is not registered.', 'the-plus-addons-for-block-editor' ) );
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

	// Generate a unique block ID and prepare attributes.
	$block_id = tpgb_mcp_generate_block_id();
	$attrs    = array( 'block_id' => $block_id );

	/* ── Cards ────────────────────────────────────────────────────────── */
	if ( ! empty( $input['cards'] ) && is_array( $input['cards'] ) ) {
		$card_list = array();
		foreach ( $input['cards'] as $i => $card ) {
			if ( ! is_array( $card ) ) {
				continue; }
			$card_list[] = tpgb_mcp_hcard_build_item( $i, $card );
		}
		if ( ! empty( $card_list ) ) {
			$attrs['cardList'] = $card_list; }
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

	/* ── Transition ───────────────────────────────────────────────────── */
	if ( ! empty( $input['transitionDuration'] ) ) {
		$attrs['gTraDur'] = sanitize_text_field( $input['transitionDuration'] ); }
	if ( ! empty( $input['transitionFunction'] ) ) {
		$attrs['gTraFunc'] = sanitize_text_field( $input['transitionFunction'] ); }
	if ( ! empty( $input['transitionOrigin'] ) ) {
		$attrs['gTraOrigin'] = sanitize_text_field( $input['transitionOrigin'] );   }

	/* ── Global: Spacing/Bg/Border/Shadow ─────────────────────────────── */
	if ( ! empty( $input['globalMargin'] ) ) {
		$attrs['globalMargin'] = tpgb_mcp_hcard_spacing( $input['globalMargin'] );  }
	if ( ! empty( $input['globalPadding'] ) ) {
		$attrs['globalPadding'] = tpgb_mcp_hcard_spacing( $input['globalPadding'] ); }
	if ( ! empty( $input['globalBgColor'] ) ) {
		$attrs['globalBg'] = tpgb_mcp_hcard_bg( $input['globalBgColor'] );      }
	if ( ! empty( $input['globalBgHoverColor'] ) ) {
		$attrs['globalBgHover'] = tpgb_mcp_hcard_bg( $input['globalBgHoverColor'] ); }
	if ( ! empty( $input['globalBorder'] ) ) {
		$attrs['globalBorder'] = tpgb_mcp_hcard_border( $input['globalBorder'] );       }
	if ( ! empty( $input['globalBorderHover'] ) ) {
		$attrs['globalBorderHover'] = tpgb_mcp_hcard_border( $input['globalBorderHover'] );  }
	if ( ! empty( $input['globalBRadius'] ) ) {
		$attrs['globalBRadius'] = tpgb_mcp_hcard_radius( $input['globalBRadius'] );      }
	if ( ! empty( $input['globalBRadiusHover'] ) ) {
		$attrs['globalBRadiusHover'] = tpgb_mcp_hcard_radius( $input['globalBRadiusHover'] ); }
	if ( ! empty( $input['globalBShadow'] ) ) {
		$attrs['globalBShadow'] = tpgb_mcp_hcard_bshadow( $input['globalBShadow'] );      }
	if ( ! empty( $input['globalBShadowHover'] ) ) {
		$attrs['globalBShadowHover'] = tpgb_mcp_hcard_bshadow( $input['globalBShadowHover'] ); }

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
	if ( tpgb_mcp_hcard_needs_wrapper( $attrs ) ) {
		$attrs['tpgbDisrule'] = true; }

	/* ── Raw settings override ────────────────────────────────────────── */
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
