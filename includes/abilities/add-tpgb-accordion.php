<?php
/**
 * Ability: Add Nexter Blocks Accordion (tpgb/tp-accordion) to a Gutenberg page.
 *
 * @package The_Plus_Addons_For_Block_Editor
 * @since   1.3.0
 */

declare(strict_types=1);

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_register_ability(
	'nexter-blocks/add-tpgb-accordion',
	array(
		'label'               => __( 'Add Nexter Blocks Accordion', 'the-plus-addons-for-block-editor' ),
		'description'         => __( 'Adds the Nexter Blocks Accordion block (tpgb/tp-accordion) with full support for all style, layout, animation, transform, and advanced settings.', 'the-plus-addons-for-block-editor' ),
		'category'            => 'nexter-blocks',

		'input_schema'        => array(
			'type'                 => 'object',
			'properties'           => array(

				/* ── Core ─────────────────────────────────────────────────── */
				'post_id'               => array(
					'type'        => 'integer',
					'description' => 'WordPress page/post ID to insert the block into.',
				),
				'position'              => array(
					'type'        => 'integer',
					'description' => 'Zero-based insert position. Use -1 to append.',
					'default'     => -1,
				),
				'parent_block_id'       => array(
					'type'        => 'string',
					'description' => 'Optional block_id of a parent container block. When provided, this block is inserted INSIDE the parent as an inner block instead of at the page top level.',
					'default'     => '',
				),

				/* ── Accordion items ─────────────────────────────────────── */
				'accordion_items'       => array(
					'type'        => 'array',
					'description' => 'List of accordion items.',
					'items'       => array(
						'type'       => 'object',
						'properties' => array(
							'title'         => array(
								'type'        => 'string',
								'description' => 'Item heading text.',
							),
							'contentType'   => array(
								'type'    => 'string',
								'enum'    => array( 'content', 'editor', 'template' ),
								'default' => 'content',
							),
							'desc'          => array(
								'type'        => 'string',
								'description' => 'Body HTML when contentType is content.',
							),
							'ajaxbase'      => array(
								'type'    => 'string',
								'default' => '',
							),
							'innerIcon'     => array(
								'type'    => 'boolean',
								'default' => false,
							),
							'iconFonts'     => array(
								'type'    => 'string',
								'enum'    => array( 'font_awesome', 'lineawesome' ),
								'default' => 'font_awesome',
							),
							'innericonName' => array(
								'type'    => 'string',
								'default' => 'fas fa-plus',
							),
							'UniqueId'      => array( 'type' => 'string' ),
						),
						'required'   => array( 'title' ),
					),
				),

				/* ── Heading tag ─────────────────────────────────────────── */
				'titleTag'              => array(
					'type'    => 'string',
					'enum'    => array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p' ),
					'default' => 'h3',
				),

				/* ── Global toggle icons ─────────────────────────────────── */
				'toggleIcon'            => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'iconAlign'             => array(
					'type'    => 'string',
					'enum'    => array( 'start', 'end' ),
					'default' => 'end',
				),
				'iconFont'              => array(
					'type'    => 'string',
					'enum'    => array( 'font_awesome', 'lineawesome' ),
					'default' => 'font_awesome',
				),
				'iconName'              => array(
					'type'        => 'string',
					'default'     => 'fas fa-plus',
					'description' => 'Collapsed state icon class.',
				),
				'ActiconName'           => array(
					'type'        => 'string',
					'default'     => 'fas fa-minus',
					'description' => 'Expanded state icon class.',
				),

				/* ── Icon styles ─────────────────────────────────────────── */
				'inIconColor'           => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Per-item icon colour (hex/CSS var).',
				),
				'inIconActcolor'        => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Per-item icon active colour.',
				),
				'inIconGap'             => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Gap between per-item icon and title in px.',
				),
				'inIconSize'            => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Per-item icon size in px.',
				),
				'tgiconColor'           => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Global toggle icon colour.',
				),
				'tgiconActcolor'        => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Global toggle icon active colour.',
				),
				'tgiconGap'             => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Gap between toggle icon and title in px.',
				),
				'tgiconSize'            => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Toggle icon size in px.',
				),

				/* ── Title styles ────────────────────────────────────────── */
				'titleAlign'            => array(
					'type'    => 'string',
					'enum'    => array( 'text-left', 'text-center', 'text-right' ),
					'default' => 'text-left',
				),
				'titleColor'            => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Title text colour.',
				),
				'titleActcolor'         => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Title colour when item is open.',
				),
				'titleHvrcolor'         => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Title colour on hover.',
				),
				'enableTitleTypo'       => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Enable custom title typography.',
				),
				'titleTypoSize'         => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Title font size in px.',
				),
				'titleTypoGlobalPreset' => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Global typography preset ID for title.',
				),
				'titlePadding'          => array(
					'type'        => 'object',
					'description' => 'Title padding {top,bottom,left,right,unit}.',
				),
				'accorBetspace'         => array(
					'type'        => 'integer',
					'default'     => 0,
					'description' => 'Space between accordion items in px.',
				),
				'titleBorder'           => array(
					'type'        => 'object',
					'description' => 'Title border {type,color,width:{top,right,bottom,left,unit}}.',
				),
				'titleBradius'          => array(
					'type'        => 'object',
					'description' => 'Title border radius {top,bottom,left,right,unit}.',
				),
				'titleActborder'        => array(
					'type'        => 'object',
					'description' => 'Title border when item is active.',
				),
				'titleActBradius'       => array(
					'type'        => 'object',
					'description' => 'Title border radius when active.',
				),
				'titleBgColor'          => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Title background colour (normal).',
				),
				'titleActBgColor'       => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Title background colour when open.',
				),
				'enableTitleShadow'     => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Enable title box shadow.',
				),
				'titleShadowHorizontal' => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'titleShadowVertical'   => array(
					'type'    => 'integer',
					'default' => 4,
				),
				'titleShadowBlur'       => array(
					'type'    => 'integer',
					'default' => 8,
				),
				'titleShadowSpread'     => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'titleShadowColor'      => array(
					'type'    => 'string',
					'default' => 'rgba(0,0,0,0.40)',
				),

				/* ── Description styles ──────────────────────────────────── */
				'descAlign'             => array(
					'type'    => 'string',
					'enum'    => array( 'text-left', 'text-center', 'text-right' ),
					'default' => 'text-left',
				),
				'descColor'             => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Description text colour.',
				),
				'enableDescTypo'        => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Enable custom description typography.',
				),
				'descTypoSize'          => array(
					'type'    => 'integer',
					'default' => 0,
				),
				'descTypoGlobalPreset'  => array(
					'type'    => 'string',
					'default' => '',
				),
				'descMargin'            => array(
					'type'        => 'object',
					'description' => 'Description margin {top,bottom,left,right,unit}.',
				),
				'descPadding'           => array(
					'type'        => 'object',
					'description' => 'Description padding {top,bottom,left,right,unit}.',
				),
				'descBgColor'           => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Description background colour.',
				),
				'descBorder'            => array(
					'type'        => 'object',
					'description' => 'Description border {type,color,width}.',
				),
				'descBRadius'           => array(
					'type'        => 'object',
					'description' => 'Description border radius {top,bottom,left,right,unit}.',
				),

				/* ── Interaction ─────────────────────────────────────────── */
				'onHvrtab'              => array(
					'type'    => 'string',
					'enum'    => array( 'click', 'hover' ),
					'default' => 'click',
				),
				'defaultAct'            => array(
					'type'    => 'string',
					'default' => '',
				),
				'atOneOpen'             => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'expCollBtn'            => array(
					'type'    => 'boolean',
					'default' => false,
				),

				/* ── SEO ─────────────────────────────────────────────────── */
				'markupSch'             => array(
					'type'    => 'boolean',
					'default' => false,
				),

				/* ── Scroll animation ────────────────────────────────────── */
				'scrollAnimation'       => array(
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
				'animDuration'          => array(
					'type'        => 'string',
					'enum'        => array( '', 'slow', 'normal', 'fast' ),
					'default'     => '',
					'description' => 'Animation duration.',
				),
				'animDelay'             => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Animation delay in seconds e.g. "0.2".',
				),
				'animEasing'            => array(
					'type'    => 'string',
					'enum'    => array( '', 'linear', 'ease', 'ease-in', 'ease-out', 'ease-in-out' ),
					'default' => '',
				),

				/* ── Advanced: Visibility ────────────────────────────────── */
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

				/* ── Advanced: Identity ──────────────────────────────────── */
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

				/* ── Advanced: Layout ────────────────────────────────────── */
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

				/* ── Advanced: Transition ────────────────────────────────── */
				'transitionDuration'    => array(
					'type'    => 'string',
					'default' => '',
				),
				'transitionFunction'    => array(
					'type'    => 'string',
					'enum'    => array( '', 'ease', 'ease-in', 'ease-out', 'ease-in-out', 'linear' ),
					'default' => '',
				),
				'transitionOrigin'      => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Global: Spacing ─────────────────────────────────────── */
				'globalMargin'          => array(
					'type'        => 'object',
					'description' => 'Outer margin {top,bottom,left,right,unit}.',
				),
				'globalPadding'         => array(
					'type'        => 'object',
					'description' => 'Inner padding {top,bottom,left,right,unit}.',
				),

				/* ── Global: Background ──────────────────────────────────── */
				'globalBgColor'         => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Normal background colour.',
				),
				'globalBgHoverColor'    => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Hover background colour.',
				),

				/* ── Global: Border ──────────────────────────────────────── */
				'globalBorder'          => array(
					'type'        => 'object',
					'description' => 'Normal border {type,color,width}.',
				),
				'globalBorderHover'     => array(
					'type'        => 'object',
					'description' => 'Hover border.',
				),

				/* ── Global: Border radius ───────────────────────────────── */
				'globalBRadius'         => array(
					'type'        => 'object',
					'description' => 'Normal border radius {top,bottom,left,right,unit}.',
				),
				'globalBRadiusHover'    => array(
					'type'        => 'object',
					'description' => 'Hover border radius.',
				),

				/* ── Global: Box shadow ──────────────────────────────────── */
				'globalBShadow'         => array(
					'type'        => 'object',
					'description' => 'Normal box shadow {horizontal,vertical,blur,spread,color}.',
				),
				'globalBShadowHover'    => array(
					'type'        => 'object',
					'description' => 'Hover box shadow.',
				),

				/* ── Transform: Rotate ───────────────────────────────────── */
				'rotateDeg'             => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Rotation degrees e.g. "10". Setting any rotate value enables the transform.',
				),
				'rotateX'               => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Rotate X axis degrees.',
				),
				'rotateY'               => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Rotate Y axis degrees.',
				),
				'rotatePerspective'     => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Perspective for 3D rotation.',
				),
				'rotateDegHover'        => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Hover rotation degrees. Setting any hover rotate value enables hover transform.',
				),
				'rotateXHover'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotateYHover'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'rotatePersHover'       => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Transform: Offset ───────────────────────────────────── */
				'offsetX'               => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Translate X in px e.g. "10". Setting any offset value enables the transform.',
				),
				'offsetY'               => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Translate Y in px.',
				),
				'offsetZ'               => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Translate Z in px.',
				),
				'offsetXHover'          => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Hover translate X. Setting any hover offset value enables hover transform.',
				),
				'offsetYHover'          => array(
					'type'    => 'string',
					'default' => '',
				),
				'offsetZHover'          => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Transform: Scale ────────────────────────────────────── */
				'scaleValue'            => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Scale value e.g. "1.5". Values other than "1" enable the transform.',
				),
				'scaleKeepRatio'        => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'scaleValueHover'       => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Hover scale value. Values other than "1" enable hover transform.',
				),

				/* ── Transform: Skew ─────────────────────────────────────── */
				'skewX'                 => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Skew X in degrees e.g. "10". Setting any skew value enables the transform.',
				),
				'skewY'                 => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Skew Y in degrees.',
				),
				'skewXHover'            => array(
					'type'        => 'string',
					'default'     => '',
					'description' => 'Hover skew X. Setting any hover skew value enables hover transform.',
				),
				'skewYHover'            => array(
					'type'    => 'string',
					'default' => '',
				),

				/* ── Transform: Flip ─────────────────────────────────────── */
				'flipHorizontal'        => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Flip block horizontally.',
				),
				'flipVertical'          => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Flip block vertically.',
				),
				'flipHorizontalHover'   => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Flip horizontally on hover.',
				),
				'flipVerticalHover'     => array(
					'type'        => 'boolean',
					'default'     => false,
					'description' => 'Flip vertically on hover.',
				),

				/* ── Raw override ───────────────────────────────────────── */
				'settings'              => array(
					'type'        => 'object',
					'description' => 'Raw attribute overrides merged directly into the block.',
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

		'execute_callback'    => 'tpgb_mcp_add_accordion_ability',
		'permission_callback' => 'tpgb_mcp_add_accordion_permission',
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
 * Permission callback for the add-accordion ability.
 *
 * @param array|null $input Ability input arguments.
 * @return bool True when the current user may insert the block.
 */
function tpgb_mcp_add_accordion_permission( ?array $input = null ): bool {
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
function tpgb_mcp_accordion_spacing( array $v ): array {
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
function tpgb_mcp_accordion_border( array $b ): array {
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
function tpgb_mcp_accordion_radius( array $r ): array {
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
function tpgb_mcp_accordion_bshadow( array $s ): array {
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
function tpgb_mcp_accordion_bg( string $color ): array {
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
 * Build a Nexter Blocks typography attribute.
 *
 * @param bool              $enable           Whether custom typography is enabled.
 * @param int               $size             Font size in px (0 to skip).
 * @param string            $preset           Global typography preset ID, or '' for none.
 * @param object|array|null $font_family_attr Font-family attribute structured for the block.
 * @return array Typography attribute structured for the block.
 */
function tpgb_mcp_accordion_typo( bool $enable, int $size, string $preset, $font_family_attr = null ): array {
	$typo = array(
		'openTypography' => $enable ? 1 : 0,
		'size'           => array(
			'md'   => $size > 0 ? (string) $size : '',
			'unit' => 'px',
		),
		'height'         => '',
		'spacing'        => '',
		'fontFamily'     => $font_family_attr ?? (object) array(),
	);
	// Only emit globalTypo when a preset is set; an empty string makes the
	// CSS generator emit `var(--tpgb-T-font-family)` which is undefined.
	if ( '' !== $preset ) {
		$typo['globalTypo'] = sanitize_text_field( $preset );
	}
	return $typo;
}

/**
 * Determine whether any wrapper-level (global/transform) attributes are set.
 *
 * @param array $attrs Block attributes built so far.
 * @return bool True when the block needs the tpgbDisrule wrapper enabled.
 */
function tpgb_mcp_accordion_needs_wrapper( array $attrs ): bool {
	$wrapper_keys = array(
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
		'PlusGlassMorphism',
		'globalWidth',
		'globalZindex',
		'globalPosition',
		'globalClasses',
		'globalId',
		'globalCustomCss',
		'globalHideDesktop',
		'globalHideTablet',
		'globalHideMobile',
		'globalflexCss',
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
	foreach ( $wrapper_keys as $key ) {
		if ( ! empty( $attrs[ $key ] ) ) {
			return true;
		}
	}
	return false;
}

/**
 * Merge user-provided raw settings into block attributes.
 *
 * Block attribute names are camelCase (tTypo, globalBgColor, fontWeight, …),
 * so we cannot use sanitize_key() here — it lowercases the key and turns
 * tTypo into ttypo, which Nexter Blocks does not read. Keys are restricted
 * to [A-Za-z0-9_] instead, preserving case.
 *
 * @param array $attrs    Existing block attributes.
 * @param array $settings Raw overrides keyed by attribute name.
 * @return array Merged attributes.
 */
function tpgb_mcp_merge_block_settings( array $attrs, array $settings ): array {
	if ( empty( $settings ) || ! is_array( $settings ) ) {
		return $attrs;
	}
	foreach ( $settings as $key => $value ) {
		if ( ! is_string( $key ) ) {
			continue;
		}
		$clean = preg_replace( '/[^A-Za-z0-9_]/', '', $key );
		if ( '' === $clean ) {
			continue;
		}
		$attrs[ $clean ] = $value;
	}
	return $attrs;
}

// -------------------------------------------------------------------------
// EXECUTE CALLBACK
// -------------------------------------------------------------------------

/**
 * Execute callback: insert a tpgb/tp-accordion block into a post.
 *
 * @param array $input Ability input arguments.
 * @return array|WP_Error Ability result or error on failure.
 */
function tpgb_mcp_add_accordion_ability( array $input ) {

	if ( ! defined( 'TPGB_VERSION' ) && ! defined( 'TPGBP_VERSION' ) ) {
		return new WP_Error( 'nexter_blocks_missing', __( 'Nexter Blocks must be active.', 'the-plus-addons-for-block-editor' ) );
	}

	$block_name = 'tpgb/tp-accordion';

	if ( ! tpgb_mcp_has_registered_block( $block_name ) ) {
		return new WP_Error( 'block_missing', __( 'tpgb/tp-accordion is not registered.', 'the-plus-addons-for-block-editor' ) );
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

	/* ── Accordion items ──────────────────────────────────────────────── */
	if ( ! empty( $input['accordion_items'] ) && is_array( $input['accordion_items'] ) ) {
		$attrs['accordianList'] = array_map(
			static function ( array $item, int $index ): array {
				$content_type = sanitize_key( (string) ( $item['contentType'] ?? 'content' ) );
				if ( ! in_array( $content_type, array( 'content', 'editor', 'template' ), true ) ) {
					$content_type = 'content';
				}
				$entry = array(
					'_key'          => (string) $index,
					'title'         => sanitize_text_field( (string) ( $item['title'] ?? 'Accordion ' . ( $index + 1 ) ) ),
					'contentType'   => $content_type,
					'desc'          => 'content' === $content_type ? tpgb_mcp_clean_text( (string) ( $item['desc'] ?? '' ) ) : '',
					'ajaxbase'      => 'template' === $content_type ? sanitize_text_field( (string) ( $item['ajaxbase'] ?? '' ) ) : '',
					'innerIcon'     => ! empty( $item['innerIcon'] ),
					'iconFonts'     => sanitize_key( (string) ( $item['iconFonts'] ?? 'font_awesome' ) ),
					'innericonName' => sanitize_text_field( (string) ( $item['innericonName'] ?? 'fas fa-plus' ) ),
				);
				if ( ! empty( $item['UniqueId'] ) ) {
					$entry['UniqueId'] = sanitize_title( (string) $item['UniqueId'] );
				}
				return $entry;
			},
			$input['accordion_items'],
			array_keys( $input['accordion_items'] )
		);

		foreach ( $attrs['accordianList'] as $item ) {
			if ( ( $item['contentType'] ?? '' ) === 'editor' ) {
				$attrs['accorType'] = 'editor';
				break;
			}
		}
	}

	/* ── Title tag ────────────────────────────────────────────────────── */
	$allowed_tags      = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p' );
	$title_tag         = sanitize_key( (string) ( $input['titleTag'] ?? 'h3' ) );
	$attrs['titleTag'] = in_array( $title_tag, $allowed_tags, true ) ? $title_tag : 'h3';

	/* ── Toggle icons ─────────────────────────────────────────────────── */
	$attrs['toggleIcon'] = ! empty( $input['toggleIcon'] );
	if ( $attrs['toggleIcon'] ) {
		$icon_align         = sanitize_key( (string) ( $input['iconAlign'] ?? 'end' ) );
		$attrs['iconAlign'] = in_array( $icon_align, array( 'start', 'end' ), true ) ? $icon_align : 'end';
		$icon_font          = sanitize_key( (string) ( $input['iconFont'] ?? 'font_awesome' ) );
		$attrs['iconFont']  = in_array( $icon_font, array( 'font_awesome', 'lineawesome' ), true ) ? $icon_font : 'font_awesome';
		if ( ! empty( $input['iconName'] ) ) {
			$attrs['iconName'] = sanitize_text_field( (string) $input['iconName'] );    }
		if ( ! empty( $input['ActiconName'] ) ) {
			$attrs['ActiconName'] = sanitize_text_field( (string) $input['ActiconName'] ); }
	}

	/* ── Icon styles ──────────────────────────────────────────────────── */
	if ( ! empty( $input['inIconColor'] ) ) {
		$attrs['inIconColor'] = sanitize_text_field( $input['inIconColor'] );    }
	if ( ! empty( $input['inIconActcolor'] ) ) {
		$attrs['inIconActcolor'] = sanitize_text_field( $input['inIconActcolor'] ); }
	if ( ! empty( $input['inIconGap'] ) ) {
		$attrs['inIconGap'] = array(
			'md'   => (string) absint( $input['inIconGap'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['inIconSize'] ) ) {
		$attrs['inIconSize'] = array(
			'md'   => (string) absint( $input['inIconSize'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['tgiconColor'] ) ) {
		$attrs['tgiconColor'] = sanitize_text_field( $input['tgiconColor'] );    }
	if ( ! empty( $input['tgiconActcolor'] ) ) {
		$attrs['tgiconActcolor'] = sanitize_text_field( $input['tgiconActcolor'] ); }
	if ( ! empty( $input['tgiconGap'] ) ) {
		$attrs['tgiconGap'] = array(
			'md'   => (string) absint( $input['tgiconGap'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['tgiconSize'] ) ) {
		$attrs['tgiconSize'] = array(
			'md'   => (string) absint( $input['tgiconSize'] ),
			'unit' => 'px',
		); }

	/* ── Title styles ─────────────────────────────────────────────────── */
	if ( ! empty( $input['titleAlign'] ) ) {
		$t_align             = sanitize_key( (string) $input['titleAlign'] );
		$attrs['titleAlign'] = in_array( $t_align, array( 'text-left', 'text-center', 'text-right' ), true ) ? $t_align : 'text-left';
	}
	if ( ! empty( $input['titleColor'] ) ) {
		$attrs['titleColor'] = sanitize_text_field( $input['titleColor'] );    }
	if ( ! empty( $input['titleActcolor'] ) ) {
		$attrs['titleActcolor'] = sanitize_text_field( $input['titleActcolor'] ); }
	if ( ! empty( $input['titleHvrcolor'] ) ) {
		$attrs['titleHvrcolor'] = sanitize_text_field( $input['titleHvrcolor'] ); }
	if ( ! empty( $input['enableTitleTypo'] ) ) {
		$attrs['titleTypo'] = tpgb_mcp_accordion_typo( true, absint( $input['titleTypoSize'] ?? 0 ), $input['titleTypoGlobalPreset'] ?? '', tpgb_mcp_font_family_attr( $input ) );
	}
	if ( ! empty( $input['titlePadding'] ) ) {
		$attrs['titlePadding'] = tpgb_mcp_accordion_spacing( $input['titlePadding'] ); }
	if ( ! empty( $input['accorBetspace'] ) ) {
		$attrs['accorBetspace'] = array(
			'md'   => (string) absint( $input['accorBetspace'] ),
			'unit' => 'px',
		); }
	if ( ! empty( $input['titleBorder'] ) ) {
		$attrs['titleBorder'] = tpgb_mcp_accordion_border( $input['titleBorder'] );   }
	if ( ! empty( $input['titleBradius'] ) ) {
		$attrs['titleBradius'] = tpgb_mcp_accordion_radius( $input['titleBradius'] );   }
	if ( ! empty( $input['titleActborder'] ) ) {
		$attrs['titleActborder'] = tpgb_mcp_accordion_border( $input['titleActborder'] ); }
	if ( ! empty( $input['titleActBradius'] ) ) {
		$attrs['titleActBradius'] = tpgb_mcp_accordion_radius( $input['titleActBradius'] ); }
	if ( ! empty( $input['titleBgColor'] ) ) {
		$attrs['titlebgType'] = tpgb_mcp_accordion_bg( $input['titleBgColor'] );    }
	if ( ! empty( $input['titleActBgColor'] ) ) {
		$attrs['titleActbgtype'] = tpgb_mcp_accordion_bg( $input['titleActBgColor'] ); }
	if ( ! empty( $input['enableTitleShadow'] ) ) {
		$attrs['titleBshadow'] = array(
			'openShadow' => true,
			'inset'      => 0,
			'horizontal' => intval( $input['titleShadowHorizontal'] ?? 0 ),
			'vertical'   => intval( $input['titleShadowVertical'] ?? 4 ),
			'blur'       => absint( $input['titleShadowBlur'] ?? 8 ),
			'spread'     => intval( $input['titleShadowSpread'] ?? 0 ),
			'color'      => sanitize_text_field( $input['titleShadowColor'] ?? 'rgba(0,0,0,0.40)' ),
		);
	}

	/* ── Description styles ───────────────────────────────────────────── */
	if ( ! empty( $input['descAlign'] ) ) {
		$d_align            = sanitize_key( (string) $input['descAlign'] );
		$attrs['descAlign'] = in_array( $d_align, array( 'text-left', 'text-center', 'text-right' ), true ) ? $d_align : 'text-left';
	}
	if ( ! empty( $input['descColor'] ) ) {
		$attrs['descColor'] = sanitize_text_field( $input['descColor'] ); }
	if ( ! empty( $input['enableDescTypo'] ) ) {
		$attrs['descTypo'] = tpgb_mcp_accordion_typo( true, absint( $input['descTypoSize'] ?? 0 ), $input['descTypoGlobalPreset'] ?? '', tpgb_mcp_font_family_attr( $input ) );
	}
	if ( ! empty( $input['descMargin'] ) ) {
		$attrs['descMargin'] = tpgb_mcp_accordion_spacing( $input['descMargin'] );  }
	if ( ! empty( $input['descPadding'] ) ) {
		$attrs['descPadding'] = tpgb_mcp_accordion_spacing( $input['descPadding'] ); }
	if ( ! empty( $input['descBgColor'] ) ) {
		$attrs['descbgType'] = tpgb_mcp_accordion_bg( $input['descBgColor'] );      }
	if ( ! empty( $input['descBorder'] ) ) {
		$attrs['descBorder'] = tpgb_mcp_accordion_border( $input['descBorder'] );   }
	if ( ! empty( $input['descBRadius'] ) ) {
		$attrs['descBRedius'] = tpgb_mcp_accordion_radius( $input['descBRadius'] );  }

	/* ── Interaction ──────────────────────────────────────────────────── */
	$on_hover          = sanitize_key( (string) ( $input['onHvrtab'] ?? 'click' ) );
	$attrs['onHvrtab'] = in_array( $on_hover, array( 'click', 'hover' ), true ) ? $on_hover : 'click';
	if ( isset( $input['defaultAct'] ) && '' !== $input['defaultAct'] ) {
		$attrs['defaultAct'] = sanitize_text_field( (string) $input['defaultAct'] );
	}
	$attrs['atOneOpen']  = ! empty( $input['atOneOpen'] );
	$attrs['expCollBtn'] = ! empty( $input['expCollBtn'] );

	/* ── SEO ──────────────────────────────────────────────────────────── */
	$attrs['markupSch'] = ! empty( $input['markupSch'] );

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

	/* ── Global: Spacing ──────────────────────────────────────────────── */
	if ( ! empty( $input['globalMargin'] ) ) {
		$attrs['globalMargin'] = tpgb_mcp_accordion_spacing( $input['globalMargin'] );  }
	if ( ! empty( $input['globalPadding'] ) ) {
		$attrs['globalPadding'] = tpgb_mcp_accordion_spacing( $input['globalPadding'] ); }

	/* ── Global: Background ───────────────────────────────────────────── */
	if ( ! empty( $input['globalBgColor'] ) ) {
		$attrs['globalBg'] = tpgb_mcp_accordion_bg( $input['globalBgColor'] );      }
	if ( ! empty( $input['globalBgHoverColor'] ) ) {
		$attrs['globalBgHover'] = tpgb_mcp_accordion_bg( $input['globalBgHoverColor'] ); }

	/* ── Global: Border ───────────────────────────────────────────────── */
	if ( ! empty( $input['globalBorder'] ) ) {
		$attrs['globalBorder'] = tpgb_mcp_accordion_border( $input['globalBorder'] );      }
	if ( ! empty( $input['globalBorderHover'] ) ) {
		$attrs['globalBorderHover'] = tpgb_mcp_accordion_border( $input['globalBorderHover'] ); }

	/* ── Global: Border radius ────────────────────────────────────────── */
	if ( ! empty( $input['globalBRadius'] ) ) {
		$attrs['globalBRadius'] = tpgb_mcp_accordion_radius( $input['globalBRadius'] );      }
	if ( ! empty( $input['globalBRadiusHover'] ) ) {
		$attrs['globalBRadiusHover'] = tpgb_mcp_accordion_radius( $input['globalBRadiusHover'] ); }

	/* ── Global: Box shadow ───────────────────────────────────────────── */
	if ( ! empty( $input['globalBShadow'] ) ) {
		$attrs['globalBShadow'] = tpgb_mcp_accordion_bshadow( $input['globalBShadow'] );      }
	if ( ! empty( $input['globalBShadowHover'] ) ) {
		$attrs['globalBShadowHover'] = tpgb_mcp_accordion_bshadow( $input['globalBShadowHover'] ); }

	/* ── Transform: Rotate ────────────────────────────────────────────── */
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

	/* ── Transform: Offset ────────────────────────────────────────────── */
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

	/* ── Transform: Scale ─────────────────────────────────────────────── */
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

	/* ── Transform: Skew ──────────────────────────────────────────────── */
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

	/* ── Transform: Flip ──────────────────────────────────────────────── */
	if ( ! empty( $input['flipHorizontal'] ) ) {
		$attrs['gFHori'] = true; }
	if ( ! empty( $input['flipVertical'] ) ) {
		$attrs['gFVert'] = true; }
	if ( ! empty( $input['flipHorizontalHover'] ) ) {
		$attrs['gFHoriHov'] = true; }
	if ( ! empty( $input['flipVerticalHover'] ) ) {
		$attrs['gFVertHov'] = true; }

	/* ── tpgbDisrule ──────────────────────────────────────────────────── */
	if ( tpgb_mcp_accordion_needs_wrapper( $attrs ) ) {
		$attrs['tpgbDisrule'] = true;
	}

	/* ── Raw settings override ────────────────────────────────────────── */
	tpgb_mcp_apply_typo_decoration( $attrs, $input );
	$attrs = tpgb_mcp_merge_block_settings( $attrs, $input['settings'] ?? array() );

	// ---------------------------------------------------------------------
	// Build, insert, save.
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
