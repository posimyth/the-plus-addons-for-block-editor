<?php
/**
 * Ability: Add Nexter Blocks Interactive Circle Info (tpgb/tp-interactive-circle-info) to a Gutenberg page.
 *
 * @package The_Plus_Addons_For_Block_Editor
 * @since   1.3.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_register_ability(
	'nexter-blocks/add-tpgb-interactive-circle-info',
	array(
		'label'               => __( 'Add Nexter Blocks Interactive Circle Info', 'the-plus-addons-for-block-editor' ),
		'description'         => __( 'Adds the Nexter Blocks Interactive Circle Info block (tpgb/tp-interactive-circle-info) — an interactive circular display where items arranged around a circle reveal associated content on hover/click. Supports 5+ style presets, multiple items with icon/image, title, description, CTA button, auto-rotation, continuous rotation, entry/exit animations, full styling for normal/hover/active states for circles, icons, content panel, button, and indicator line. This is a dynamic block.', 'the-plus-addons-for-block-editor' ),
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

				/* ── Style ────────────────────────────────────────────────── */
				'style'                   => array(
					'type'        => 'string',
					'description' => 'Style preset e.g. "style-1" through "style-5+".',
					'default'     => 'style-1',
				),
				'alignment'               => array(
					'type'    => 'string',
					'enum'    => array( 'left', 'center', 'right' ),
					'default' => 'center',
				),

				/* ── Items ────────────────────────────────────────────────── */
				'items'                   => array(
					'type'        => 'array',
					'description' => 'Array of circle items. Each item has title, content, icon, optional image, optional button.',
					'items'       => array(
						'type'       => 'object',
						'properties' => array(
							'iconTitle'      => array(
								'type'        => 'string',
								'description' => 'Short title shown on the circle node.',
							),
							'title'          => array(
								'type'        => 'string',
								'description' => 'Full content title.',
							),
							'description'    => array(
								'type'        => 'string',
								'description' => 'Content description/body.',
							),
							'iconType'       => array(
								'type'    => 'string',
								'enum'    => array( 'icon', 'image' ),
								'default' => 'icon',
							),
							'iconClass'      => array(
								'type'        => 'string',
								'description' => 'Font Awesome class when iconType is "icon".',
							),
							'imageUrl'       => array(
								'type'        => 'string',
								'description' => 'Image URL when iconType is "image".',
							),
							'imageId'        => array(
								'type'        => 'integer',
								'description' => 'Media attachment ID.',
							),
							'buttonText'     => array(
								'type'    => 'string',
								'default' => 'Read More',
							),
							'buttonUrl'      => array(
								'type'    => 'string',
								'default' => '#',
							),
							'buttonTarget'   => array(
								'type'    => 'string',
								'enum'    => array( '_self', '_blank' ),
								'default' => '_self',
							),
							'buttonNofollow' => array(
								'type'    => 'boolean',
								'default' => false,
							),
						),
					),
				),

				/* ── Interaction ──────────────────────────────────────────── */
				'mouseTrigger'            => array(
					'type'        => 'string',
					'enum'        => array( 'hover', 'click', 'auto' ),
					'default'     => 'hover',
					'description' => 'How items activate.',
				),
				'autoTime'                => array(
					'type'        => 'string',
					'default'     => '1000',
					'description' => 'Auto-rotation interval in ms (when mouseTrigger is "auto").',
				),
				'defaultActive'           => array(
					'type'        => 'string',
					'default'     => '1',
					'description' => 'Default active item index (1-based).',
				),

				/* ── Animations ───────────────────────────────────────────── */
				'enableOutAnimation'      => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Enable different exit animation.',
				),
				'selectAnimation'         => array(
					'type'        => 'string',
					'default'     => 'bounce',
					'description' => 'Item selection animation name.',
				),

				/* ── Carousel ─────────────────────────────────────────────── */
				'enableCarousel'          => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'carouselId'              => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Button (global CTA) ──────────────────────────────────── */
				'enableButton'            => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Show CTA button on each item.',
				),
				'buttonStyle'             => array(
					'type'    => 'string',
					'default' => 'style-7',
				),
				'buttonIconType'          => array(
					'type'    => 'string',
					'enum'    => array( 'none', 'icon' ),
					'default' => 'none',
				),
				'buttonIcon'              => array(
					'type'    => 'string',
					'default' => '',
				),
				'buttonIconPosition'      => array(
					'type'    => 'string',
					'enum'    => array( 'before', 'after' ),
					'default' => 'after',
				),

				/* ── Circle styling ───────────────────────────────────────── */
				'circleWidth'             => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Circle diameter in px.',
				),
				'circleBgColor'           => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Circle bg (normal).',
				),
				'circleHoverBgColor'      => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Circle bg (hover/active).',
				),
				'circleBorder'            => array( 'type' => 'object' ),
				'circleHoverBorder'       => array( 'type' => 'object' ),
				'circleBorderRadius'      => array( 'type' => 'object' ),
				'circleHoverBorderRadius' => array( 'type' => 'object' ),

				/* ── Icon/image styling ───────────────────────────────────── */
				'iconWidth'               => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'iconSize'                => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'imageWidth'              => array(
					'type'    => 'integer',
					'default' => 0,
				),

				/* ── Icon colours (3 states) ──────────────────────────────── */
				'iconColor'               => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Icon colour (normal).',
				),
				'iconHoverColor'          => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Icon colour (hover).',
				),
				'iconActiveColor'         => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Icon colour (active).',
				),
				'iconBgColor'             => array(
					'type'    => 'string',
					'default' => '',
				),
				'iconHoverBgColor'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'iconActiveBgColor'       => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Icon title typography/spacing ────────────────────────── */
				'enableIconTitleTypo'     => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'iconTitleTypoSize'       => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'iconTitleSpacing'        => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Space between icon and title.',
				),
				'iconTitleColor'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'iconTitleHoverColor'     => array(
					'type'    => 'string',
					'default' => '',
				),
				'iconTitleActiveColor'    => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Content panel styling ────────────────────────────────── */
				'enableContentTitleTypo'  => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'contentTitleTypoSize'    => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'enableContentDescTypo'   => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'contentDescTypoSize'     => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'contentDescSpacing'      => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'contentPadding'          => array( 'type' => 'object' ),
				'contentMargin'           => array( 'type' => 'object' ),

				/* ── Content colours (3 states) ───────────────────────────── */
				'contentTitleColor'       => array(
					'type'    => 'string',
					'default' => '',
				),
				'contentTitleHoverColor'  => array(
					'type'    => 'string',
					'default' => '',
				),
				'contentTitleActiveColor' => array(
					'type'    => 'string',
					'default' => '',
				),
				'contentDescColor'        => array(
					'type'    => 'string',
					'default' => '',
				),
				'contentDescHoverColor'   => array(
					'type'    => 'string',
					'default' => '',
				),
				'contentDescActiveColor'  => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Content backgrounds (3 states) ───────────────────────── */
				'contentBgColor'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'contentHoverBgColor'     => array(
					'type'    => 'string',
					'default' => '',
				),
				'contentActiveBgColor'    => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Button styling ───────────────────────────────────────── */
				'enableBtnTypo'           => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'btnTypoSize'             => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'btnColor'                => array(
					'type'    => 'string',
					'default' => '',
				),
				'btnHoverColor'           => array(
					'type'    => 'string',
					'default' => '',
				),
				'btnTopSpace'             => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'btnBottomSpace'          => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'btnIconSpacing'          => array(
					'type'    => 'integer',
					'default' => 5,
				),
				'btnIconSize'             => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'btnPadding'              => array( 'type' => 'object' ),
				'btnBorder'               => array( 'type' => 'object' ),
				'btnBorderRadius'         => array( 'type' => 'object' ),
				'btnBgColor'              => array(
					'type'    => 'string',
					'default' => '',
				),
				'btnHoverBorder'          => array( 'type' => 'object' ),
				'btnHoverBorderRadius'    => array( 'type' => 'object' ),
				'btnHoverBgColor'         => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Extra indicator line ─────────────────────────────────── */
				'indicatorStyle'          => array(
					'type'        => 'string',
					'default'     => 'style-1',
					'description' => 'Line/indicator style.',
				),
				'indicatorLineWidth'      => array(
					'type'    => 'integer',
					'default' => 0,
				),

				/* ── Continuous rotation ──────────────────────────────────── */
				'rotationDirection'       => array(
					'type'    => 'string',
					'enum'    => array( 'clock-wise', 'counter-clock-wise' ),
					'default' => 'clock-wise',
				),
				'rotationSpeed'           => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Rotation speed in seconds.',
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
				'globalPosition'          => array(
					'type'    => 'string',
					'enum'    => array( '', 'relative', 'absolute', 'fixed', 'sticky' ),
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

		'execute_callback'    => 'tpgb_mcp_add_circle_info_ability',
		'permission_callback' => 'tpgb_mcp_add_circle_info_permission',
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
 * Permission callback for the add-circle-info ability.
 *
 * @param array|null $input Ability input arguments.
 * @return bool True when the current user may insert the block.
 */
function tpgb_mcp_add_circle_info_permission( ?array $input = null ): bool {
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
function tpgb_mcp_cinfo_spacing( array $v ): array {
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
function tpgb_mcp_cinfo_border( array $b ): array {
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
function tpgb_mcp_cinfo_radius( array $r ): array {
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
function tpgb_mcp_cinfo_bshadow( array $s ): array {
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
function tpgb_mcp_cinfo_bg( string $color ): array {
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
function tpgb_mcp_cinfo_needs_wrapper( array $attrs ): bool {
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
 * Execute callback: insert a tpgb/tp-interactive-circle-info block into a post.
 *
 * @param array $input Ability input arguments.
 * @return array|WP_Error Ability result or error on failure.
 */
function tpgb_mcp_add_circle_info_ability( array $input ) {

	if ( ! defined( 'TPGB_VERSION' ) && ! defined( 'TPGBP_VERSION' ) ) {
		return new WP_Error( 'nexter_blocks_missing', __( 'Nexter Blocks must be active.', 'the-plus-addons-for-block-editor' ) );
	}

	$block_name = 'tpgb/tp-interactive-circle-info';
	if ( ! tpgb_mcp_has_registered_block( $block_name ) ) {
		return new WP_Error( 'block_missing', __( 'tpgb/tp-interactive-circle-info is not registered.', 'the-plus-addons-for-block-editor' ) );
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

	/* ── Style & Alignment ────────────────────────────────────────────── */
	if ( ! empty( $input['style'] ) && 'style-1' !== $input['style'] ) {
		$attrs['styleType'] = sanitize_text_field( $input['style'] ); }
	if ( ! empty( $input['alignment'] ) && 'center' !== $input['alignment'] ) {
		$attrs['Alignment'] = array(
			'md' => sanitize_key( $input['alignment'] ),
			'sm' => '',
			'xs' => '',
		);
	}

	/* ── Items ────────────────────────────────────────────────────────── */
	if ( ! empty( $input['items'] ) && is_array( $input['items'] ) ) {
		$items = array();
		foreach ( $input['items'] as $i => $it ) {
			if ( ! is_array( $it ) ) {
				continue; }
			$item = array(
				'_key'      => (string) $i,
				'iconType'  => sanitize_key( $it['iconType'] ?? 'icon' ),
				'iconStore' => sanitize_text_field( $it['iconClass'] ?? 'fas fa-check-circle' ),
				'iconTitle' => sanitize_text_field( $it['iconTitle'] ?? 'Item ' . ( $i + 1 ) ),
				'conTitle'  => tpgb_mcp_clean_text( $it['title'] ?? '' ),
				'conDesc'   => tpgb_mcp_clean_text( $it['description'] ?? '' ),
				'btnText'   => sanitize_text_field( $it['buttonText'] ?? 'Read More' ),
				'btnUrl'    => array(
					'url'      => esc_url_raw( $it['buttonUrl'] ?? '#' ),
					'target'   => '_blank' === ( $it['buttonTarget'] ?? '' ) ? '_blank' : '',
					'nofollow' => ! empty( $it['buttonNofollow'] ) ? 'on' : '',
				),
				'imageName' => array( 'url' => '' ),
			);

			// Image handling.
			if ( 'image' === ( $item['iconType'] ) ) {
				if ( ! empty( $it['imageId'] ) ) {
					$img_id            = absint( $it['imageId'] );
					$src               = wp_get_attachment_image_url( $img_id, 'full' );
					$item['imageName'] = array(
						'id'  => $img_id,
						'url' => $src ? $src : '',
					);
				} elseif ( ! empty( $it['imageUrl'] ) ) {
					$item['imageName'] = array( 'url' => esc_url_raw( $it['imageUrl'] ) );
				}
			}

			$items[] = $item;
		}
		if ( ! empty( $items ) ) {
			$attrs['intCircle'] = $items; }
	}

	/* ── Interaction ──────────────────────────────────────────────────── */
	if ( ! empty( $input['mouseTrigger'] ) && 'hover' !== $input['mouseTrigger'] ) {
		$attrs['mouseTrigger'] = sanitize_key( $input['mouseTrigger'] ); }
	if ( ! empty( $input['autoTime'] ) && '1000' !== $input['autoTime'] ) {
		$attrs['autoTime'] = sanitize_text_field( $input['autoTime'] ); }
	if ( ! empty( $input['defaultActive'] ) && '1' !== $input['defaultActive'] ) {
		$attrs['defaultActive'] = sanitize_text_field( $input['defaultActive'] ); }

	/* ── Animations ───────────────────────────────────────────────────── */
	if ( ! empty( $input['enableOutAnimation'] ) ) {
		$attrs['outAnimation'] = true; }
	if ( ! empty( $input['selectAnimation'] ) && 'bounce' !== $input['selectAnimation'] ) {
		$attrs['selAnimation'] = sanitize_text_field( $input['selectAnimation'] );
	}

	/* ── Carousel ─────────────────────────────────────────────────────── */
	if ( ! empty( $input['enableCarousel'] ) ) {
		$attrs['carouselToggle'] = true; }
	if ( ! empty( $input['carouselId'] ) ) {
		$attrs['carouselID'] = sanitize_text_field( $input['carouselId'] ); }

	/* ── Global CTA button ────────────────────────────────────────────── */
	if ( ! empty( $input['enableButton'] ) ) {
		$attrs['disBtn'] = true;
		if ( ! empty( $input['buttonStyle'] ) && 'style-7' !== $input['buttonStyle'] ) {
			$attrs['btnStyle'] = sanitize_text_field( $input['buttonStyle'] ); }
		if ( ! empty( $input['buttonIconType'] ) && 'none' !== $input['buttonIconType'] ) {
			$attrs['btnIconType'] = sanitize_key( $input['buttonIconType'] );
			if ( ! empty( $input['buttonIcon'] ) ) {
				$attrs['btnIconStore'] = sanitize_text_field( $input['buttonIcon'] ); }
			if ( ! empty( $input['buttonIconPosition'] ) && 'after' !== $input['buttonIconPosition'] ) {
				$attrs['btnIconPosition'] = sanitize_key( $input['buttonIconPosition'] );
			}
		}
	}

	/* ── Circle styling ───────────────────────────────────────────────── */
	if ( ! empty( $input['circleWidth'] ) ) {
		$attrs['circleWidth'] = array(
			'md'   => (string) absint( $input['circleWidth'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['circleBgColor'] ) ) {
		$attrs['circleNBG'] = tpgb_mcp_cinfo_bg( $input['circleBgColor'] ); }
	if ( ! empty( $input['circleHoverBgColor'] ) ) {
		$attrs['circleHBG'] = tpgb_mcp_cinfo_bg( $input['circleHoverBgColor'] ); }
	if ( ! empty( $input['circleBorder'] ) ) {
		$attrs['circleNbdr'] = tpgb_mcp_cinfo_border( $input['circleBorder'] ); }
	if ( ! empty( $input['circleHoverBorder'] ) ) {
		$attrs['circleHbdr'] = tpgb_mcp_cinfo_border( $input['circleHoverBorder'] ); }
	if ( ! empty( $input['circleBorderRadius'] ) ) {
		$attrs['circleNRadius'] = tpgb_mcp_cinfo_radius( $input['circleBorderRadius'] ); }
	if ( ! empty( $input['circleHoverBorderRadius'] ) ) {
		$attrs['circleHRadius'] = tpgb_mcp_cinfo_radius( $input['circleHoverBorderRadius'] ); }

	/* ── Icon/image styling ───────────────────────────────────────────── */
	if ( ! empty( $input['iconWidth'] ) ) {
		$attrs['iconWidth'] = array(
			'md'   => (string) absint( $input['iconWidth'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['iconSize'] ) ) {
		$attrs['iconSize'] = array(
			'md'   => (string) absint( $input['iconSize'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['imageWidth'] ) ) {
		$attrs['imageWidth'] = array(
			'md'   => (string) absint( $input['imageWidth'] ),
			'unit' => 'px',
		); }

	/* ── Icon colours ─────────────────────────────────────────────────── */
	if ( ! empty( $input['iconColor'] ) ) {
		$attrs['iconNColor'] = sanitize_text_field( $input['iconColor'] ); }
	if ( ! empty( $input['iconHoverColor'] ) ) {
		$attrs['iconHColor'] = sanitize_text_field( $input['iconHoverColor'] ); }
	if ( ! empty( $input['iconActiveColor'] ) ) {
		$attrs['iconAColor'] = sanitize_text_field( $input['iconActiveColor'] ); }
	if ( ! empty( $input['iconBgColor'] ) ) {
		$attrs['iconNBG'] = tpgb_mcp_cinfo_bg( $input['iconBgColor'] ); }
	if ( ! empty( $input['iconHoverBgColor'] ) ) {
		$attrs['iconHBG'] = tpgb_mcp_cinfo_bg( $input['iconHoverBgColor'] ); }
	if ( ! empty( $input['iconActiveBgColor'] ) ) {
		$attrs['iconABG'] = tpgb_mcp_cinfo_bg( $input['iconActiveBgColor'] ); }

	/* ── Icon title ───────────────────────────────────────────────────── */
	if ( ! empty( $input['enableIconTitleTypo'] ) ) {
		$attrs['iTitleTypo'] = array(
			'openTypography' => 1,
			'size'           => array(
				'md'   => ! empty( $input['iconTitleTypoSize'] ) ? (string) absint( $input['iconTitleTypoSize'] ) : '',
				'unit' => 'px',
			),
			'height'         => '',
			'spacing'        => '',
			'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
		);
	}
	if ( ! empty( $input['iconTitleSpacing'] ) ) {
		$attrs['iTitleSpace'] = array(
			'md'   => (string) absint( $input['iconTitleSpacing'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['iconTitleColor'] ) ) {
		$attrs['iTitleNColor'] = sanitize_text_field( $input['iconTitleColor'] ); }
	if ( ! empty( $input['iconTitleHoverColor'] ) ) {
		$attrs['iTitleHColor'] = sanitize_text_field( $input['iconTitleHoverColor'] ); }
	if ( ! empty( $input['iconTitleActiveColor'] ) ) {
		$attrs['iTitleAColor'] = sanitize_text_field( $input['iconTitleActiveColor'] ); }

	/* ── Content panel ────────────────────────────────────────────────── */
	if ( ! empty( $input['enableContentTitleTypo'] ) ) {
		$attrs['cTitleTypo'] = array(
			'openTypography' => 1,
			'size'           => array(
				'md'   => ! empty( $input['contentTitleTypoSize'] ) ? (string) absint( $input['contentTitleTypoSize'] ) : '',
				'unit' => 'px',
			),
			'height'         => '',
			'spacing'        => '',
			'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
		);
	}
	if ( ! empty( $input['enableContentDescTypo'] ) ) {
		$attrs['cDescTypo'] = array(
			'openTypography' => 1,
			'size'           => array(
				'md'   => ! empty( $input['contentDescTypoSize'] ) ? (string) absint( $input['contentDescTypoSize'] ) : '',
				'unit' => 'px',
			),
			'height'         => '',
			'spacing'        => '',
			'fontFamily'     => tpgb_mcp_font_family_attr( $input ),
		);
	}
	if ( ! empty( $input['contentDescSpacing'] ) ) {
		$attrs['cDescSpace'] = array(
			'md'   => (string) absint( $input['contentDescSpacing'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['contentPadding'] ) ) {
		$attrs['contentPadding'] = tpgb_mcp_cinfo_spacing( $input['contentPadding'] ); }
	if ( ! empty( $input['contentMargin'] ) ) {
		$attrs['contentMargin'] = tpgb_mcp_cinfo_spacing( $input['contentMargin'] ); }

	if ( ! empty( $input['contentTitleColor'] ) ) {
		$attrs['cTitleNColor'] = sanitize_text_field( $input['contentTitleColor'] ); }
	if ( ! empty( $input['contentTitleHoverColor'] ) ) {
		$attrs['cTitleHColor'] = sanitize_text_field( $input['contentTitleHoverColor'] ); }
	if ( ! empty( $input['contentTitleActiveColor'] ) ) {
		$attrs['cTitleAColor'] = sanitize_text_field( $input['contentTitleActiveColor'] ); }
	if ( ! empty( $input['contentDescColor'] ) ) {
		$attrs['cDescNColor'] = sanitize_text_field( $input['contentDescColor'] ); }
	if ( ! empty( $input['contentDescHoverColor'] ) ) {
		$attrs['cDescHColor'] = sanitize_text_field( $input['contentDescHoverColor'] ); }
	if ( ! empty( $input['contentDescActiveColor'] ) ) {
		$attrs['cDescAColor'] = sanitize_text_field( $input['contentDescActiveColor'] ); }
	if ( ! empty( $input['contentBgColor'] ) ) {
		$attrs['contentNBG'] = tpgb_mcp_cinfo_bg( $input['contentBgColor'] ); }
	if ( ! empty( $input['contentHoverBgColor'] ) ) {
		$attrs['contentHBG'] = tpgb_mcp_cinfo_bg( $input['contentHoverBgColor'] ); }
	if ( ! empty( $input['contentActiveBgColor'] ) ) {
		$attrs['contentABG'] = tpgb_mcp_cinfo_bg( $input['contentActiveBgColor'] ); }

	/* ── Button styling ───────────────────────────────────────────────── */
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
	if ( ! empty( $input['btnColor'] ) ) {
		$attrs['btnNmlColor'] = sanitize_text_field( $input['btnColor'] ); }
	if ( ! empty( $input['btnHoverColor'] ) ) {
		$attrs['btnHvrColor'] = sanitize_text_field( $input['btnHoverColor'] ); }
	if ( ! empty( $input['btnTopSpace'] ) ) {
		$attrs['btnTSpace'] = array(
			'md'   => (string) absint( $input['btnTopSpace'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['btnBottomSpace'] ) ) {
		$attrs['btnBSpace'] = array(
			'md'   => (string) absint( $input['btnBottomSpace'] ),
			'unit' => 'px',
		); }
	if ( isset( $input['btnIconSpacing'] ) && 5 !== (int) $input['btnIconSpacing'] ) {
		$attrs['btnIconSpacing'] = array(
			'md'   => (string) absint( $input['btnIconSpacing'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['btnIconSize'] ) ) {
		$attrs['btnIconSize'] = array(
			'md'   => (string) absint( $input['btnIconSize'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['btnPadding'] ) ) {
		$attrs['btnPadding'] = tpgb_mcp_cinfo_spacing( $input['btnPadding'] ); }
	if ( ! empty( $input['btnBorder'] ) ) {
		$attrs['btnNormalB'] = tpgb_mcp_cinfo_border( $input['btnBorder'] ); }
	if ( ! empty( $input['btnBorderRadius'] ) ) {
		$attrs['btnBRadius'] = tpgb_mcp_cinfo_radius( $input['btnBorderRadius'] ); }
	if ( ! empty( $input['btnBgColor'] ) ) {
		$attrs['btnBG'] = tpgb_mcp_cinfo_bg( $input['btnBgColor'] ); }
	if ( ! empty( $input['btnHoverBorder'] ) ) {
		$attrs['btnHvrB'] = tpgb_mcp_cinfo_border( $input['btnHoverBorder'] ); }
	if ( ! empty( $input['btnHoverBorderRadius'] ) ) {
		$attrs['btnHvrBRadius'] = tpgb_mcp_cinfo_radius( $input['btnHoverBorderRadius'] ); }
	if ( ! empty( $input['btnHoverBgColor'] ) ) {
		$attrs['btnHvrBG'] = tpgb_mcp_cinfo_bg( $input['btnHoverBgColor'] ); }

	/* ── Extra indicator ──────────────────────────────────────────────── */
	if ( ( ! empty( $input['indicatorStyle'] ) && 'style-1' !== $input['indicatorStyle'] ) || ! empty( $input['indicatorLineWidth'] ) ) {
		$attrs['extIndicator'] = array(
			'indiStyle'     => sanitize_text_field( $input['indicatorStyle'] ?? 'style-1' ),
			'indiLineWidth' => array(
				'md'   => ! empty( $input['indicatorLineWidth'] ) ? (string) absint( $input['indicatorLineWidth'] ) : '',
				'unit' => 'px',
			),
		);
	}

	/* ── Continuous rotation ──────────────────────────────────────────── */
	if ( ! empty( $input['rotationSpeed'] ) || ( ! empty( $input['rotationDirection'] ) && 'clock-wise' !== $input['rotationDirection'] ) ) {
		$attrs['contiRotate'] = array(
			'animDirection'    => sanitize_text_field( $input['rotationDirection'] ?? 'clock-wise' ),
			'contiRotateSpeed' => array(
				'md'   => ! empty( $input['rotationSpeed'] ) ? (string) absint( $input['rotationSpeed'] ) : '',
				'unit' => 's',
			),
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

	/* ── Transition ───────────────────────────────────────────────────── */
	if ( ! empty( $input['transitionDuration'] ) ) {
		$attrs['gTraDur'] = sanitize_text_field( $input['transitionDuration'] ); }
	if ( ! empty( $input['transitionFunction'] ) ) {
		$attrs['gTraFunc'] = sanitize_text_field( $input['transitionFunction'] ); }
	if ( ! empty( $input['transitionOrigin'] ) ) {
		$attrs['gTraOrigin'] = sanitize_text_field( $input['transitionOrigin'] );   }

	/* ── Global: Spacing/Bg/Border/Shadow ─────────────────────────────── */
	if ( ! empty( $input['globalMargin'] ) ) {
		$attrs['globalMargin'] = tpgb_mcp_cinfo_spacing( $input['globalMargin'] );  }
	if ( ! empty( $input['globalPadding'] ) ) {
		$attrs['globalPadding'] = tpgb_mcp_cinfo_spacing( $input['globalPadding'] ); }
	if ( ! empty( $input['globalBgColor'] ) ) {
		$attrs['globalBg'] = tpgb_mcp_cinfo_bg( $input['globalBgColor'] );      }
	if ( ! empty( $input['globalBgHoverColor'] ) ) {
		$attrs['globalBgHover'] = tpgb_mcp_cinfo_bg( $input['globalBgHoverColor'] ); }
	if ( ! empty( $input['globalBorder'] ) ) {
		$attrs['globalBorder'] = tpgb_mcp_cinfo_border( $input['globalBorder'] );       }
	if ( ! empty( $input['globalBorderHover'] ) ) {
		$attrs['globalBorderHover'] = tpgb_mcp_cinfo_border( $input['globalBorderHover'] );  }
	if ( ! empty( $input['globalBRadius'] ) ) {
		$attrs['globalBRadius'] = tpgb_mcp_cinfo_radius( $input['globalBRadius'] );      }
	if ( ! empty( $input['globalBRadiusHover'] ) ) {
		$attrs['globalBRadiusHover'] = tpgb_mcp_cinfo_radius( $input['globalBRadiusHover'] ); }
	if ( ! empty( $input['globalBShadow'] ) ) {
		$attrs['globalBShadow'] = tpgb_mcp_cinfo_bshadow( $input['globalBShadow'] );      }
	if ( ! empty( $input['globalBShadowHover'] ) ) {
		$attrs['globalBShadowHover'] = tpgb_mcp_cinfo_bshadow( $input['globalBShadowHover'] ); }

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
	if ( tpgb_mcp_cinfo_needs_wrapper( $attrs ) ) {
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
