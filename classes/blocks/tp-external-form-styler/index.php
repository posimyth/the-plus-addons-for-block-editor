<?php
/* Block : External Form Styler
 * @since : 1.1.3
 */
defined( 'ABSPATH' ) || exit;

function tpgb_external_form_styler_render_callback( $attributes, $content) {
    $block_id = (!empty($attributes['block_id'])) ? $attributes['block_id'] : uniqid("title");
    $contactForm = (!empty($attributes['contactForm'])) ? $attributes['contactForm'] : '';
    $formType = (!empty($attributes['formType'])) ? $attributes['formType'] : 'contact-form-7';
    $titleShow = (!empty($attributes['titleShow'])) ? $attributes['titleShow'] : false;
    $outerSecStyle = (!empty($attributes['outerSecStyle'])) ? $attributes['outerSecStyle'] : 'tpgb-cf7-label';

	$blockClass = Tp_Blocks_Helper::block_wrapper_classes( $attributes );
	
	$titleShowLine = '';
	if($formType=='gravity-form' && !empty($titleShow)){
		$titleShowLine .= 'title=true description=true';
	} else if($formType=='gravity-form' && empty($titleShow)){
		$titleShowLine .= 'title=false description=false';
	}
	$cf7class = '';
	if($formType=='contact-form-7'){
		$cf7class = $outerSecStyle;
	}
	$output = '';
	$output .= '<div class="tpgb-external-form-styler tpgb-block-'.esc_attr($block_id).' '.esc_attr($blockClass).'">';
		if($contactForm==''){
			$output .= '<div class="tpgb-select-form-alert">Please select Form</div>';
		} else {
			$sc = "id = ".$contactForm;
			$shortcode   = [];
			if($formType=='contact-form-7'){
				$shortcode[] = sprintf( '[contact-form-7 %s]', $sc );
			} else if($formType=='caldera-form'){
				$shortcode[] = sprintf( '[caldera_form %s]', $sc );
			} else if($formType=='everest-form'){
				$shortcode[] = sprintf( '[everest_form %s]', $sc );
			} else if($formType=='gravity-form'){
				$shortcode[] = sprintf( '[gravityform %s '.$titleShowLine.']', $sc );
			} else if($formType=='ninja-form'){
				$shortcode[] = sprintf( '[ninja_form %s]', $sc );
			} else if($formType=='wp-form'){
				$shortcode[] = sprintf( '[wpforms %s]', $sc );
			}

			$shortcode_str = implode("", $shortcode);
			
			$output .='<div class="tpgb-'.esc_attr($formType).' '.esc_attr($cf7class).'">';
				$output .= do_shortcode( $shortcode_str );				
			$output .= '</div>';
		}
	$output .= '</div>';
  
    return $output;
}
function tpgb_get_form_rendered(){
    $form_id = isset($_POST['form_id']) ? wp_unslash($_POST['form_id']) : '';
    $form_type = isset($_POST['form_type']) ? sanitize_text_field(wp_unslash($_POST['form_type'])) : '';
	
	if (!empty($form_id) && $form_type=='contact-form-7'){
		echo do_shortcode ( "[contact-form-7 id=".$form_id."]" );
	} else if(!empty($form_id) && $form_type=='caldera-form'){
		echo do_shortcode ( "[caldera_form id=".$form_id."]" );
	} else if(!empty($form_id) && $form_type=='everest-form'){
		echo do_shortcode ( "[everest_form id=".$form_id."]" );
	} else if(!empty($form_id) && $form_type=='gravity-form'){
		echo do_shortcode ( "[gravityform id=".$form_id." title=false description=false]" );
	} else if(!empty($form_id) && $form_type=='ninja-form'){
		echo do_shortcode ( "[ninja_form id=".$form_id."]" );
	} else if(!empty($form_id) && $form_type=='wp-form'){
		echo do_shortcode ( "[wpforms id=".$form_id."]" );
	}
    exit();
}
add_action('wp_ajax_tpgb_external_form_ajax', 'tpgb_get_form_rendered');
/**
 * Render for the server-side
 */
function tpgb_external_form_styler() {
	$globalBgOption = Tpgb_Blocks_Global_Options::load_bg_options();
	$globalpositioningOption = Tpgb_Blocks_Global_Options::load_positioning_options();
  
	$attributesOptions = array(
		'block_id' => [
			'type' => 'string',
			'default' => '',
		],
		'formType' => [
			'type' => 'string',
			'default' => 'contact-form-7',	
		],
		'contactForm' => [
			'type' => 'string',
			'default' => ''
		],
		'titleShow' => [
			'type' => 'boolean',
			'default' => false,	
		],
		'Alignment' => [
			'type' => 'object',
			'default' => 'center',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}}.tpgb-external-form-styler{ text-align: {{Alignment}}; }',
				],
			],
		],
		'outerSecStyle' => [
			'type' => 'string',
			'default' => 'tpgb-cf7-label',
		],
		
		/* Label Field start*/
		'labelTypo' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7.tpgb-cf7-label form.wpcf7-form  label,{{PLUS_WRAP}} .tpgb-contact-form-7.tpgb-cf7-custom form.wpcf7-form .tpgb-cf7-outer',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form .control-label',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .evf-field-label .evf-label',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gfield_label, {{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .ginput_full label, {{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .ginput_left label, {{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .ginput_right label, {{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .address_city label, {{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .address_zip label, {{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .address_country label',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .nf-form-layout .nf-field-label label',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-wp-form .wpforms-container label.wpforms-field-label',
				],
			],
		],
		'subLabelTypo' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .name_prefix label,
					{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .name_first label,
					{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .name_middle label,
					{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .name_last label,
					{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .name_suffix label,
					{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .ginput_container.ginput_container_email label',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-wp-form .wpforms-container .wpforms-field-sublabel',
				],
			],
		],
		'labelNColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7.tpgb-cf7-label form.wpcf7-form  label,{{PLUS_WRAP}} .tpgb-contact-form-7.tpgb-cf7-custom form.wpcf7-form .tpgb-cf7-outer{color: {{labelNColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form .control-label{color: {{labelNColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .evf-field-label .evf-label{color: {{labelNColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gfield_label, {{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .ginput_full label, {{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .ginput_left label, {{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .ginput_right label, {{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .address_city label, {{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .address_zip label, {{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .address_country label{color: {{labelNColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .nf-field-label label{color: {{labelNColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-wp-form .wpforms-container label.wpforms-field-label{color: {{labelNColor}};}',
				],
			],
		],
		'subLabelNColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .name_prefix label,
					{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .name_first label,
					{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .name_middle label,
					{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .name_last label,
					{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .name_suffix label,
					{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .ginput_container.ginput_container_email label{color: {{subLabelNColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-wp-form .wpforms-container .wpforms-field-label-inline,{{PLUS_WRAP}} .tpgb-wp-form .wpforms-container .wpforms-field-sublabel{color: {{subLabelNColor}};}',
				],
			],
		],
		'maxCharColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .charleft.ginput_counter{color: {{maxCharColor}};}',
				],
			],
		],
		'labelDescColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form .caldera-grid .help-block{color: {{labelDescColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .form-row .everest-forms-field-label-inline,{{PLUS_WRAP}} .tpgb-everest-form .form-row .evf-field-description{color: {{labelDescColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gfield_description,
					{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper span.gf_step_number,
					{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gsection_description,
					{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper span.ginput_product_price_label,
					{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper span.ginput_quantity_label{color: {{labelDescColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .nf-field-description,{{PLUS_WRAP}} .tpgb-ninja-form .nf-field-description p{color: {{labelDescColor}};}',
				],
			],
		],
		'reqSymColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form .caldera-grid .field_required{color: {{reqSymColor}} !important;}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-forms .evf-field-container .evf-frontend-row label .required{color: {{reqSymColor}} !important;}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gfield_required{color: {{reqSymColor}} !important;}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .ninja-forms-req-symbol{color: {{reqSymColor}} !important;}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-wp-form .wpforms-container .wpforms-required-label{color: {{reqSymColor}} !important;}',
				],
			],
		],
		'progressBarTSize' => [
			'type' => 'object',
			'default' => (object) [ 
				'md' => '',
				"unit" => 'px',
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper h3.gf_progressbar_title{ font-size: {{progressBarTSize}}; }',
				],
			],
		],
		'progressBarTColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper h3.gf_progressbar_title{color: {{progressBarTColor}};}',
				],
			],
		],
		'progressBarBdrSize' => [
			'type' => 'object',
			'default' => (object) [ 
				'md' => '',
				"unit" => 'px',
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gf_progressbar{ padding: {{progressBarBdrSize}}; }',
				],
			],
		],
		'progressBarBdrColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gf_progressba{background-color: {{progressBarBdrColor}};}',
				],
			],
		],
		'priceColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .ginput_product_price,{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .ginput_shipping_price,{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper span.ginput_total{color: {{priceColor}};}',
				],
			],
		],
		'consentGrColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gfield_consent_label{color: {{consentGrColor}};}',
				],
			],
		],
		'labelHColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7.tpgb-cf7-label form.wpcf7-form  label:hover,{{PLUS_WRAP}} .tpgb-contact-form-7.tpgb-cf7-custom form.wpcf7-form .tpgb-cf7-outer:hover{color: {{labelHColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form .control-label:hover{color: {{labelHColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .evf-field-label .evf-label:hover{color: {{labelHColor}};}',
				],
			],
		],
		/* Label Field end*/
		
		/* Description Field(wp-form) start*/
		'wpDescTypo' => [
			'type'=> 'object',
			'default'=> (object) [
				'openTypography' => 0,
				'size' => [ 'md' => '', 'unit' => 'px' ],
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-wp-form .wpforms-container .wpforms-field-description',
				],
			],
		],
		'wpDescPadding' => [
			'type' => 'object',
			'default' => (object) [ 
				'md' => [
					"top" => '',
					"right" => '',
					"bottom" => '',
					"left" => '',
				],
				"unit" => 'px',
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-wp-form .wpforms-container .wpforms-field-description{padding: {{wpDescPadding}};}',
				],
			],
		],
		'wpDescMargin' => [
			'type' => 'object',
			'default' => (object) [ 
				'md' => [
					"top" => '',
					"right" => '',
					"bottom" => '',
					"left" => '',
				],
				"unit" => 'px',
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-wp-form .wpforms-container .wpforms-field-description{margin: {{wpDescMargin}};}',
				],
			],
		],
		'wpDescColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-wp-form .wpforms-container .wpforms-field-description{ color: {{wpDescColor}}; }',
				],
			],
		],
		'wpDescBG' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-wp-form .wpforms-container .wpforms-field-description{ background: {{wpDescBG}}; }',
				],
			],
		],
		'wpDescBdr' => [
			'type' => 'object',
			'default' => (object) [
				'openBorder' => 0,	
				'type' => '',	
				'color' => '',	
				'width' => (object) [
					'md' => (object)[
						'top' => '',
						'left' => '',
						'bottom' => '',
						'right' => '',
					],
					'sm' => (object)[ ],
					'xs' => (object)[ ],
					"unit" => "px",
				],
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-wp-form .wpforms-container .wpforms-field-description',
				],
			],
		],
		'wpDescBRadius' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-wp-form .wpforms-container .wpforms-field-description{border-radius: {{wpDescBRadius}};}',
				],
			],
		],
		/* Description Field(wp-form) end*/
		
		/* Form Heading Field start*/
		'formHeadTypo' => [
			'type'=> 'object',
			'default'=> (object) [
				'openTypography' => 0,
				'size' => [ 'md' => '', 'unit' => 'px' ],
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .nf-form-title h3',
				],
			],
		],
		'formHeadColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .nf-form-title h3{ color: {{formHeadColor}}; }',
				],
			],
		],
		/* Form Heading Field end*/
		
		/* Hint Field start*/
		'hintIconColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form span.fa.fa-info-circle.nf-help:before{ color: {{hintIconColor}}; }',
				],
			],
		],
		'hintDescColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .nf-field-description{ color: {{hintDescColor}}; }',
				],
			],
		],
		/* Hint Field end*/
		
		/* Input Field start*/
		'inputTypo' => [
			'type'=> 'object',
			'default'=> (object) [
				'openTypography' => 0,
				'size' => [ 'md' => '', 'unit' => 'px' ],
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 .wpcf7-form-control:not(.wpcf7-submit):not(.wpcf7-checkbox):not(.wpcf7-radio):not(.wpcf7-file)',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form input[type="text"], {{PLUS_WRAP}} .tpgb-caldera-form input[type="email"], {{PLUS_WRAP}} .tpgb-caldera-form input[type="number"], {{PLUS_WRAP}} .tpgb-caldera-form input[type=tel], {{PLUS_WRAP}} .tpgb-caldera-form input[type=credit_card_cvc], {{PLUS_WRAP}} .tpgb-caldera-form input[type=phone], {{PLUS_WRAP}} .tpgb-caldera-form input[type=url], {{PLUS_WRAP}} .tpgb-caldera-form input[type=color_picker], {{PLUS_WRAP}} .tpgb-caldera-form input[type=date], {{PLUS_WRAP}} .tpgb-caldera-form select.form-control',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form input[type="text"], {{PLUS_WRAP}} .tpgb-everest-form input[type="email"], {{PLUS_WRAP}} .tpgb-everest-form input[type="number"], {{PLUS_WRAP}} .tpgb-everest-form input[type="url"], {{PLUS_WRAP}} .tpgb-everest-form .everest-forms .evf-field-container .evf-frontend-row select',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input[type="text"], {{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper select,{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input[type="email"], {{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input[type="tel"], {{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input[type="url"]',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .nf-field-element input[type="text"], {{PLUS_WRAP}} .tpgb-ninja-form .nf-field-element input[type="email"], {{PLUS_WRAP}} .tpgb-ninja-form .nf-field-element input[type="number"], {{PLUS_WRAP}} .tpgb-ninja-form .nf-field-element select',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-wp-form .wpforms-container input[type="text"], {{PLUS_WRAP}} .tpgb-wp-form .wpforms-container input[type="email"], {{PLUS_WRAP}} .tpgb-wp-form .wpforms-container input[type="number"], {{PLUS_WRAP}} .tpgb-wp-form .wpforms-container select',
				],
			],
		],
		'inputPHcolor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 .wpcf7-form-control:not(.wpcf7-submit):not(.wpcf7-checkbox):not(.wpcf7-radio):not(.wpcf7-file)::placeholder{ color: {{inputPHcolor}}; }',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form input::placeholder,
					{{PLUS_WRAP}} .tpgb-caldera-form email::placeholder,
					{{PLUS_WRAP}} .tpgb-caldera-form number::placeholder,
					{{PLUS_WRAP}} .tpgb-caldera-form select::placeholder{ color: {{inputPHcolor}}; }',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form input::-webkit-input-placeholder,
					{{PLUS_WRAP}} .tpgb-everest-form  email::-webkit-input-placeholder,
					{{PLUS_WRAP}} .tpgb-everest-form  number::-webkit-input-placeholder,
					{{PLUS_WRAP}} .tpgb-everest-form  select::-webkit-input-placeholder,
					{{PLUS_WRAP}} .tpgb-everest-form  url::-webkit-input-placeholder{ color: {{inputPHcolor}}; }',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input::placeholder, {{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper  select::placeholder{ color: {{inputPHcolor}}; }',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .nf-form-content input::placeholder, {{PLUS_WRAP}} .tpgb-ninja-form .nf-form-content  email::placeholder, {{PLUS_WRAP}} .tpgb-ninja-form .nf-form-content  number::placeholder, {{PLUS_WRAP}} .tpgb-ninja-form .nf-form-content  select::placeholder{ color: {{inputPHcolor}}; }',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-wp-form .wpforms-container input::placeholder, {{PLUS_WRAP}} .tpgb-wp-form .wpforms-container  email::placeholder, {{PLUS_WRAP}} .tpgb-wp-form .wpforms-container  number::placeholder, {{PLUS_WRAP}} .tpgb-wp-form .wpforms-container  select::placeholder{ color: {{inputPHcolor}}; }',
				],
			],
		],
		'inputPadding' => [
			'type' => 'object',
			'default' => (object) [ 
				'md' => [
					"top" => '',
					"right" => '',
					"bottom" => '',
					"left" => '',
				],
				"unit" => 'px',
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 .wpcf7-form-control:not(.wpcf7-submit):not(.wpcf7-checkbox):not(.wpcf7-radio):not(.wpcf7-file){padding: {{inputPadding}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form input[type="text"], {{PLUS_WRAP}} .tpgb-caldera-form input[type="email"], {{PLUS_WRAP}} .tpgb-caldera-form input[type="number"], {{PLUS_WRAP}} .tpgb-caldera-form input[type="tel"], {{PLUS_WRAP}} .tpgb-caldera-form input[type="credit_card_cvc"], {{PLUS_WRAP}} .tpgb-caldera-form input[type="phone"], {{PLUS_WRAP}} .tpgb-caldera-form input[type="url"], {{PLUS_WRAP}} .tpgb-caldera-form input[type="color_picker"], {{PLUS_WRAP}} .tpgb-caldera-form input[type="date"], {{PLUS_WRAP}} .tpgb-caldera-form select.form-control, {{PLUS_WRAP}} .tpgb-caldera-form .help-block, {{PLUS_WRAP}} .tpgb-caldera-form .flag-container{padding: {{inputPadding}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form input[type="text"], {{PLUS_WRAP}} .tpgb-everest-form input[type="email"], {{PLUS_WRAP}} .tpgb-everest-form input[type="number"], {{PLUS_WRAP}} .tpgb-everest-form input[type="url"], {{PLUS_WRAP}} .tpgb-everest-form .everest-forms .evf-field-container .evf-frontend-row select{padding: {{inputPadding}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gfield .ginput_container input[type="text"],{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gfield .ginput_container select,{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input[type="email"],{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input[type="tel"],{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input[type="url"]{padding: {{inputPadding}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .textbox-wrap:not(.submit-wrap) .nf-field-element .ninja-forms-field, {{PLUS_WRAP}} .tpgb-ninja-form .firstname-wrap .nf-field-element .ninja-forms-field, {{PLUS_WRAP}} .tpgb-ninja-form .lastname-wrap .nf-field-element .ninja-forms-field, {{PLUS_WRAP}} .tpgb-ninja-form .email-wrap .nf-field-element .ninja-forms-field, {{PLUS_WRAP}} .tpgb-ninja-form .number-wrap .nf-field-element .ninja-forms-field, {{PLUS_WRAP}} .tpgb-ninja-form .date-wrap .nf-field-element .ninja-forms-field, {{PLUS_WRAP}} .tpgb-ninja-form .city-wrap .nf-field-element .ninja-forms-field, {{PLUS_WRAP}} .tpgb-ninja-form .address-wrap .nf-field-element .ninja-forms-field, {{PLUS_WRAP}} .tpgb-ninja-form .list-select-wrap .nf-field-element .ninja-forms-field{padding: {{inputPadding}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-wp-form .wpforms-container .wpforms-field input[type="text"], {{PLUS_WRAP}} .tpgb-wp-form .wpforms-container .wpforms-field input[type="email"], {{PLUS_WRAP}} .tpgb-wp-form .wpforms-container .wpforms-field input[type="number"], {{PLUS_WRAP}} .tpgb-wp-form .wpforms-container .wpforms-field select{padding: {{inputPadding}};}',
				],
			],
		],
		'inputMargin' => [
			'type' => 'object',
			'default' => (object) [ 
				'md' => [
					"top" => '',
					"right" => '',
					"bottom" => '',
					"left" => '',
				],
				"unit" => 'px',
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 .wpcf7-form-control:not(.wpcf7-submit):not(.wpcf7-checkbox):not(.wpcf7-radio):not(.wpcf7-file){margin: {{inputMargin}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form input[type="text"], {{PLUS_WRAP}} .tpgb-caldera-form input[type="email"], {{PLUS_WRAP}} .tpgb-caldera-form input[type="number"], {{PLUS_WRAP}} .tpgb-caldera-form input[type="tel"], {{PLUS_WRAP}} .tpgb-caldera-form input[type="credit_card_cvc"], {{PLUS_WRAP}} .tpgb-caldera-form input[type="phone"], {{PLUS_WRAP}} .tpgb-caldera-form input[type="url"], {{PLUS_WRAP}} .tpgb-caldera-form input[type="color_picker"], {{PLUS_WRAP}} .tpgb-caldera-form input[type="date"], {{PLUS_WRAP}} .tpgb-caldera-form select.form-control, {{PLUS_WRAP}} .tpgb-caldera-form .help-block{margin: {{inputMargin}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form input[type="text"], {{PLUS_WRAP}} .tpgb-everest-form input[type="email"], {{PLUS_WRAP}} .tpgb-everest-form input[type="number"], {{PLUS_WRAP}} .tpgb-everest-form input[type="url"], {{PLUS_WRAP}} .tpgb-everest-form .everest-forms .evf-field-container .evf-frontend-row select{margin: {{inputMargin}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gfield .ginput_container input[type="text"],{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gfield .ginput_container select,{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input[type="email"],{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input[type="tel"],{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input[type="url"]{margin: {{inputMargin}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .textbox-wrap:not(.submit-wrap) .nf-field-element .ninja-forms-field, {{PLUS_WRAP}} .tpgb-ninja-form .firstname-wrap .nf-field-element .ninja-forms-field, {{PLUS_WRAP}} .tpgb-ninja-form .lastname-wrap .nf-field-element .ninja-forms-field, {{PLUS_WRAP}} .tpgb-ninja-form .email-wrap .nf-field-element .ninja-forms-field, {{PLUS_WRAP}} .tpgb-ninja-form .number-wrap .nf-field-element .ninja-forms-field, {{PLUS_WRAP}} .tpgb-ninja-form .date-wrap .nf-field-element .ninja-forms-field, {{PLUS_WRAP}} .tpgb-ninja-form .city-wrap .nf-field-element .ninja-forms-field, {{PLUS_WRAP}} .tpgb-ninja-form .address-wrap .nf-field-element .ninja-forms-field, {{PLUS_WRAP}} .tpgb-ninja-form .list-select-wrap .nf-field-element .ninja-forms-field{margin: {{inputMargin}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-wp-form .wpforms-container .wpforms-field input[type="text"], {{PLUS_WRAP}} .tpgb-wp-form .wpforms-container .wpforms-field input[type="email"], {{PLUS_WRAP}} .tpgb-wp-form .wpforms-container .wpforms-field input[type="number"], {{PLUS_WRAP}} .tpgb-wp-form .wpforms-container .wpforms-field select{margin: {{inputMargin}};}',
				],
			],
		],
		'inputFNColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 .wpcf7-form-control:not(.wpcf7-submit):not(.wpcf7-checkbox):not(.wpcf7-radio):not(.wpcf7-file){color: {{inputFNColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form input[type="text"], {{PLUS_WRAP}} .tpgb-caldera-form input[type="email"], {{PLUS_WRAP}} .tpgb-caldera-form input[type="number"], {{PLUS_WRAP}} .tpgb-caldera-form input[type="tel"], {{PLUS_WRAP}} .tpgb-caldera-form input[type="credit_card_cvc"], {{PLUS_WRAP}} .tpgb-caldera-form input[type="phone"], {{PLUS_WRAP}} .tpgb-caldera-form input[type="url"], {{PLUS_WRAP}} .tpgb-caldera-form input[type="color_picker"], {{PLUS_WRAP}} .tpgb-caldera-form input[type="date"], {{PLUS_WRAP}} .tpgb-caldera-form select.form-control, {{PLUS_WRAP}} .tpgb-caldera-form .flag-container{color: {{inputFNColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form input[type="text"], {{PLUS_WRAP}} .tpgb-everest-form input[type="email"], {{PLUS_WRAP}} .tpgb-everest-form input[type="number"], {{PLUS_WRAP}} .tpgb-everest-form input[type="url"], {{PLUS_WRAP}} .tpgb-everest-form .everest-forms .evf-field-container .evf-frontend-row select{color: {{inputFNColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gfield .ginput_container input[type="text"],{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gfield .ginput_container select,{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input[type="email"],{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input[type="tel"],{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input[type="url"]{color: {{inputFNColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .nf-field-element input[type="text"], {{PLUS_WRAP}} .tpgb-ninja-form .nf-field-element input[type="email"], {{PLUS_WRAP}} .tpgb-ninja-form .nf-field-element input[type="number"], {{PLUS_WRAP}} .tpgb-ninja-form .nf-field-element input[type="tel"], {{PLUS_WRAP}} .tpgb-ninja-form .nf-field-element select{color: {{inputFNColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-wp-form .wpforms-container .wpforms-field input[type="text"], {{PLUS_WRAP}} .tpgb-wp-form .wpforms-container .wpforms-field input[type="email"], {{PLUS_WRAP}} .tpgb-wp-form .wpforms-container .wpforms-field input[type="number"], {{PLUS_WRAP}} .tpgb-wp-form .wpforms-container .wpforms-field select{color: {{inputFNColor}};}',
				],
			],
		],
		'inputFNBG' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 .wpcf7-form-control:not(.wpcf7-submit):not(.wpcf7-checkbox):not(.wpcf7-radio):not(.wpcf7-file)'
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form input[type="text"], {{PLUS_WRAP}} .tpgb-caldera-form input[type="email"], {{PLUS_WRAP}} .tpgb-caldera-form input[type="number"], {{PLUS_WRAP}} .tpgb-caldera-form input[type="tel"], {{PLUS_WRAP}} .tpgb-caldera-form input[type="credit_card_cvc"], {{PLUS_WRAP}} .tpgb-caldera-form input[type="phone"], {{PLUS_WRAP}} .tpgb-caldera-form input[type="url"], {{PLUS_WRAP}} .tpgb-caldera-form input[type="color_picker"], {{PLUS_WRAP}} .tpgb-caldera-form input[type="date"], {{PLUS_WRAP}} .tpgb-caldera-form select.form-control, {{PLUS_WRAP}} .tpgb-caldera-form .flag-container'
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form input[type="text"], {{PLUS_WRAP}} .tpgb-everest-form input[type="email"], {{PLUS_WRAP}} .tpgb-everest-form input[type="number"], {{PLUS_WRAP}} .tpgb-everest-form input[type="url"], {{PLUS_WRAP}} .tpgb-everest-form .everest-forms .evf-field-container .evf-frontend-row select',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gfield .ginput_container input[type="text"],{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gfield .ginput_container select,{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input[type="email"],{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input[type="tel"],{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input[type="url"]',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .nf-field-element input[type="text"], {{PLUS_WRAP}} .tpgb-ninja-form .nf-field-element input[type="email"], {{PLUS_WRAP}} .tpgb-ninja-form .nf-field-element input[type="number"], {{PLUS_WRAP}} .tpgb-ninja-form .nf-field-element input[type="tel"], {{PLUS_WRAP}} .tpgb-ninja-form .list-select-wrap .nf-field-element select',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-wp-form .wpforms-container .wpforms-field input[type="text"], {{PLUS_WRAP}} .tpgb-wp-form .wpforms-container .wpforms-field input[type="email"], {{PLUS_WRAP}} .tpgb-wp-form .wpforms-container .wpforms-field input[type="number"], {{PLUS_WRAP}} .tpgb-wp-form .wpforms-container .wpforms-field select',
				],
			],
		],
		'inputFFColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 .wpcf7-form-control:not(.wpcf7-submit):not(.wpcf7-checkbox):not(.wpcf7-radio):not(.wpcf7-file):focus{color: {{inputFFColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form input[type="text"]:focus, {{PLUS_WRAP}} .tpgb-caldera-form input[type="email"]:focus, {{PLUS_WRAP}} .tpgb-caldera-form input[type="number"]:focus, {{PLUS_WRAP}} .tpgb-caldera-form input[type="tel"]:focus, {{PLUS_WRAP}} .tpgb-caldera-form input[type="credit_card_cvc"]:focus, {{PLUS_WRAP}} .tpgb-caldera-form input[type="phone"]:focus, {{PLUS_WRAP}} .tpgb-caldera-form input[type="url"]:focus, {{PLUS_WRAP}} .tpgb-caldera-form input[type="color_picker"]:focus, {{PLUS_WRAP}} .tpgb-caldera-form input[type="date"]:focus, {{PLUS_WRAP}} .tpgb-caldera-form select.form-control:focus, {{PLUS_WRAP}} .tpgb-caldera-form .flag-container:focus{color: {{inputFFColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form input[type="text"]:focus, {{PLUS_WRAP}} .tpgb-everest-form input[type="email"]:focus, {{PLUS_WRAP}} .tpgb-everest-form input[type="number"]:focus, {{PLUS_WRAP}} .tpgb-everest-form input[type="url"]:focus, {{PLUS_WRAP}} .tpgb-everest-form .everest-forms .evf-field-container .evf-frontend-row select:focus{color: {{inputFFColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gfield .ginput_container input[type="text"]:focus,{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gfield .ginput_container select:focus,{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input[type="email"]:focus,{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input[type="tel"]:focus,{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input[type="url"]:focus{color: {{inputFFColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .nf-field-element input[type="text"]:focus, {{PLUS_WRAP}} .tpgb-ninja-form .nf-field-element input[type="email"]:focus, {{PLUS_WRAP}} .tpgb-ninja-form .nf-field-element input[type="number"]:focus, {{PLUS_WRAP}} .tpgb-ninja-form .nf-field-element input[type="tel"]:focus, {{PLUS_WRAP}} .tpgb-ninja-form .list-select-wrap:focus .nf-field-element select{color: {{inputFFColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-wp-form .wpforms-container .wpforms-field input[type="text"]:focus, {{PLUS_WRAP}} .tpgb-wp-form .wpforms-container .wpforms-field input[type="email"]:focus, {{PLUS_WRAP}} .tpgb-wp-form .wpforms-container .wpforms-field input[type="number"]:focus, {{PLUS_WRAP}} .tpgb-wp-form .wpforms-container .wpforms-field select:focus{color: {{inputFFColor}};}',
				],
			],
		],
		'inputFFBG' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 .wpcf7-form-control:not(.wpcf7-submit):not(.wpcf7-checkbox):not(.wpcf7-radio):not(.wpcf7-file):focus',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form input[type="text"]:focus, {{PLUS_WRAP}} .tpgb-caldera-form input[type="email"]:focus, {{PLUS_WRAP}} .tpgb-caldera-form input[type="number"]:focus, {{PLUS_WRAP}} .tpgb-caldera-form input[type="tel"]:focus, {{PLUS_WRAP}} .tpgb-caldera-form input[type="credit_card_cvc"]:focus, {{PLUS_WRAP}} .tpgb-caldera-form input[type="phone"]:focus, {{PLUS_WRAP}} .tpgb-caldera-form input[type="url"]:focus, {{PLUS_WRAP}} .tpgb-caldera-form input[type="color_picker"]:focus, {{PLUS_WRAP}} .tpgb-caldera-form input[type="date"]:focus, {{PLUS_WRAP}} .tpgb-caldera-form select.form-control:focus, {{PLUS_WRAP}} .tpgb-caldera-form .flag-container:focus',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form input[type="text"]:focus, {{PLUS_WRAP}} .tpgb-everest-form input[type="email"]:focus, {{PLUS_WRAP}} .tpgb-everest-form input[type="number"]:focus, {{PLUS_WRAP}} .tpgb-everest-form input[type="url"]:focus, {{PLUS_WRAP}} .tpgb-everest-form .everest-forms .evf-field-container .evf-frontend-row select:focus',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gfield .ginput_container input[type="text"]:focus,{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gfield .ginput_container select:focus,{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input[type="email"]:focus,{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input[type="tel"]:focus,{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input[type="url"]:focus',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .nf-field-element input[type="text"]:focus, {{PLUS_WRAP}} .tpgb-ninja-form .nf-field-element input[type="email"]:focus, {{PLUS_WRAP}} .tpgb-ninja-form .nf-field-element input[type="number"]:focus, {{PLUS_WRAP}} .tpgb-ninja-form .nf-field-element input[type="tel"]:focus, {{PLUS_WRAP}} .tpgb-ninja-form .list-select-wrap:focus .nf-field-element select',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-wp-form .wpforms-container .wpforms-field input[type="text"]:focus, {{PLUS_WRAP}} .tpgb-wp-form .wpforms-container .wpforms-field input[type="email"]:focus, {{PLUS_WRAP}} .tpgb-wp-form .wpforms-container .wpforms-field input[type="number"]:focus, {{PLUS_WRAP}} .tpgb-wp-form .wpforms-container .wpforms-field select:focus',
				],
			],
		],
		'inputNBdr' => [
			'type' => 'object',
			'default' => (object) [
				'openBorder' => 0,	
				'type' => '',	
				'color' => '',	
				'width' => (object) [
					'md' => (object)[
						'top' => '',
						'left' => '',
						'bottom' => '',
						'right' => '',
					],
					'sm' => (object)[ ],
					'xs' => (object)[ ],
					"unit" => "px",
				],
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 .wpcf7-form-control:not(.wpcf7-submit):not(.wpcf7-checkbox):not(.wpcf7-radio):not(.wpcf7-file)',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form input[type="text"], {{PLUS_WRAP}} .tpgb-caldera-form input[type="email"], {{PLUS_WRAP}} .tpgb-caldera-form input[type="number"], {{PLUS_WRAP}} .tpgb-caldera-form input[type="tel"], {{PLUS_WRAP}} .tpgb-caldera-form input[type="credit_card_cvc"], {{PLUS_WRAP}} .tpgb-caldera-form input[type="phone"], {{PLUS_WRAP}} .tpgb-caldera-form input[type="url"], {{PLUS_WRAP}} .tpgb-caldera-form input[type="color_picker"], {{PLUS_WRAP}} .tpgb-caldera-form input[type="date"], {{PLUS_WRAP}} .tpgb-caldera-form select.form-control, {{PLUS_WRAP}} .tpgb-caldera-form .flag-container',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form input[type="text"], {{PLUS_WRAP}} .tpgb-everest-form input[type="email"], {{PLUS_WRAP}} .tpgb-everest-form input[type="number"], {{PLUS_WRAP}} .tpgb-everest-form input[type="url"], {{PLUS_WRAP}} .tpgb-everest-form .everest-forms .evf-field-container .evf-frontend-row select',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gfield .ginput_container input[type="text"],{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gfield .ginput_container select,{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input[type="email"],{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input[type="tel"],{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input[type="url"]',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .nf-field-element input[type="text"], {{PLUS_WRAP}} .tpgb-ninja-form .nf-field-element input[type="email"], {{PLUS_WRAP}} .tpgb-ninja-form .nf-field-element input[type="number"], {{PLUS_WRAP}} .tpgb-ninja-form .nf-field-element input[type="tel"], {{PLUS_WRAP}} .tpgb-ninja-form .nf-field-element select',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-wp-form .wpforms-container .wpforms-field input[type="text"], {{PLUS_WRAP}} .tpgb-wp-form .wpforms-container .wpforms-field input[type="email"], {{PLUS_WRAP}} .tpgb-wp-form .wpforms-container .wpforms-field input[type="number"], {{PLUS_WRAP}} .tpgb-wp-form .wpforms-container .wpforms-field select',
				],
			],
		],
		'inputFBdr' => [
			'type' => 'object',
			'default' => (object) [
				'openBorder' => 0,	
				'type' => '',	
				'color' => '',	
				'width' => (object) [
					'md' => (object)[
						'top' => '',
						'left' => '',
						'bottom' => '',
						'right' => '',
					],
					'sm' => (object)[ ],
					'xs' => (object)[ ],
					"unit" => "px",
				],
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 .wpcf7-form-control:not(.wpcf7-submit):not(.wpcf7-checkbox):not(.wpcf7-radio):not(.wpcf7-file):focus',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form input[type="text"]:focus, {{PLUS_WRAP}} .tpgb-caldera-form input[type="email"]:focus, {{PLUS_WRAP}} .tpgb-caldera-form input[type="number"]:focus, {{PLUS_WRAP}} .tpgb-caldera-form input[type="tel"]:focus, {{PLUS_WRAP}} .tpgb-caldera-form input[type="credit_card_cvc"]:focus, {{PLUS_WRAP}} .tpgb-caldera-form input[type="phone"]:focus, {{PLUS_WRAP}} .tpgb-caldera-form input[type="url"]:focus, {{PLUS_WRAP}} .tpgb-caldera-form input[type="color_picker"]:focus, {{PLUS_WRAP}} .tpgb-caldera-form input[type="date"]:focus, {{PLUS_WRAP}} .tpgb-caldera-form select.form-control:focus, {{PLUS_WRAP}} .tpgb-caldera-form .flag-container:focus',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form input[type="text"]:focus, {{PLUS_WRAP}} .tpgb-everest-form input[type="email"]:focus, {{PLUS_WRAP}} .tpgb-everest-form input[type="number"]:focus, {{PLUS_WRAP}} .tpgb-everest-form input[type="url"]:focus, {{PLUS_WRAP}} .tpgb-everest-form .everest-forms .evf-field-container .evf-frontend-row select:focus',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gfield .ginput_container input[type="text"]:focus,{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gfield .ginput_container select:focus,{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input[type="email"]:focus,{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input[type="tel"]:focus,{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input[type="url"]:focus',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .nf-field-element input[type="text"]:focus, {{PLUS_WRAP}} .tpgb-ninja-form .nf-field-element input[type="email"]:focus, {{PLUS_WRAP}} .tpgb-ninja-form .nf-field-element input[type="number"]:focus, {{PLUS_WRAP}} .tpgb-ninja-form .nf-field-element input[type="tel"]:focus, {{PLUS_WRAP}} .tpgb-ninja-form .list-select-wrap:focus .nf-field-element select',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-wp-form .wpforms-container .wpforms-field input[type="text"]:focus, {{PLUS_WRAP}} .tpgb-wp-form .wpforms-container .wpforms-field input[type="email"]:focus, {{PLUS_WRAP}} .tpgb-wp-form .wpforms-container .wpforms-field input[type="number"]:focus, {{PLUS_WRAP}} .tpgb-wp-form .wpforms-container .wpforms-field select:focus',
				],
			],
		],
		'inputNBRadius' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 .wpcf7-form-control:not(.wpcf7-submit):not(.wpcf7-checkbox):not(.wpcf7-radio):not(.wpcf7-file){border-radius: {{inputNBRadius}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form input[type="text"], {{PLUS_WRAP}} .tpgb-caldera-form input[type="email"], {{PLUS_WRAP}} .tpgb-caldera-form input[type="number"], {{PLUS_WRAP}} .tpgb-caldera-form input[type="tel"], {{PLUS_WRAP}} .tpgb-caldera-form input[type="credit_card_cvc"], {{PLUS_WRAP}} .tpgb-caldera-form input[type="phone"], {{PLUS_WRAP}} .tpgb-caldera-form input[type="url"], {{PLUS_WRAP}} .tpgb-caldera-form input[type="color_picker"], {{PLUS_WRAP}} .tpgb-caldera-form input[type="date"], {{PLUS_WRAP}} .tpgb-caldera-form select.form-control, {{PLUS_WRAP}} .tpgb-caldera-form .flag-container{border-radius: {{inputNBRadius}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form input[type="text"], {{PLUS_WRAP}} .tpgb-everest-form input[type="email"], {{PLUS_WRAP}} .tpgb-everest-form input[type="number"], {{PLUS_WRAP}} .tpgb-everest-form input[type="url"], {{PLUS_WRAP}} .tpgb-everest-form .everest-forms .evf-field-container .evf-frontend-row select{border-radius: {{inputNBRadius}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gfield .ginput_container input[type="text"],{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gfield .ginput_container select,{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input[type="email"],{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input[type="tel"],{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input[type="url"]{border-radius: {{inputNBRadius}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .nf-field-element input[type="text"], {{PLUS_WRAP}} .tpgb-ninja-form .nf-field-element input[type="email"], {{PLUS_WRAP}} .tpgb-ninja-form .nf-field-element input[type="number"], {{PLUS_WRAP}} .tpgb-ninja-form .nf-field-element input[type="tel"], {{PLUS_WRAP}} .tpgb-ninja-form .nf-field-element select{border-radius: {{inputNBRadius}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-wp-form .wpforms-container .wpforms-field input[type="text"], {{PLUS_WRAP}} .tpgb-wp-form .wpforms-container .wpforms-field input[type="email"], {{PLUS_WRAP}} .tpgb-wp-form .wpforms-container .wpforms-field input[type="number"], {{PLUS_WRAP}} .tpgb-wp-form .wpforms-container .wpforms-field select{border-radius: {{inputNBRadius}};}',
				],
			],
		],
		'inputFBRadius' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 .wpcf7-form-control:not(.wpcf7-submit):not(.wpcf7-checkbox):not(.wpcf7-radio):not(.wpcf7-file):focus{border-radius: {{inputFBRadius}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form input[type="text"]:focus, {{PLUS_WRAP}} .tpgb-caldera-form input[type="email"]:focus, {{PLUS_WRAP}} .tpgb-caldera-form input[type="number"]:focus, {{PLUS_WRAP}} .tpgb-caldera-form input[type="tel"]:focus, {{PLUS_WRAP}} .tpgb-caldera-form input[type="credit_card_cvc"]:focus, {{PLUS_WRAP}} .tpgb-caldera-form input[type="phone"]:focus, {{PLUS_WRAP}} .tpgb-caldera-form input[type="url"]:focus, {{PLUS_WRAP}} .tpgb-caldera-form input[type="color_picker"]:focus, {{PLUS_WRAP}} .tpgb-caldera-form input[type="date"]:focus, {{PLUS_WRAP}} .tpgb-caldera-form select.form-control:focus, {{PLUS_WRAP}} .tpgb-caldera-form .flag-container:focus{border-radius: {{inputFBRadius}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form input[type="text"]:focus, {{PLUS_WRAP}} .tpgb-everest-form input[type="email"]:focus, {{PLUS_WRAP}} .tpgb-everest-form input[type="number"]:focus, {{PLUS_WRAP}} .tpgb-everest-form input[type="url"]:focus, {{PLUS_WRAP}} .tpgb-everest-form .everest-forms .evf-field-container .evf-frontend-row select:focus{border-radius: {{inputFBRadius}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gfield .ginput_container input[type="text"]:focus,{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gfield .ginput_container select:focus,{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input[type="email"]:focus,{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input[type="tel"]:focus,{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input[type="url"]:focus{border-radius: {{inputFBRadius}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .nf-field-element input[type="text"]:focus, {{PLUS_WRAP}} .tpgb-ninja-form .nf-field-element input[type="email"]:focus, {{PLUS_WRAP}} .tpgb-ninja-form .nf-field-element input[type="number"]:focus, {{PLUS_WRAP}} .tpgb-ninja-form .nf-field-element input[type="tel"]:focus, {{PLUS_WRAP}} .tpgb-ninja-form .list-select-wrap:focus .nf-field-element select{border-radius: {{inputFBRadius}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-wp-form .wpforms-container .wpforms-field input[type="text"]:focus, {{PLUS_WRAP}} .tpgb-wp-form .wpforms-container .wpforms-field input[type="email"]:focus, {{PLUS_WRAP}} .tpgb-wp-form .wpforms-container .wpforms-field input[type="number"]:focus, {{PLUS_WRAP}} .tpgb-wp-form .wpforms-container .wpforms-field select:focus{border-radius: {{inputFBRadius}};}',
				],
			],
		],
		'inputNBShadow' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 .wpcf7-form-control:not(.wpcf7-submit):not(.wpcf7-checkbox):not(.wpcf7-radio):not(.wpcf7-file)',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form input[type="text"], {{PLUS_WRAP}} .tpgb-caldera-form input[type="email"], {{PLUS_WRAP}} .tpgb-caldera-form input[type="number"], {{PLUS_WRAP}} .tpgb-caldera-form input[type="tel"], {{PLUS_WRAP}} .tpgb-caldera-form input[type="credit_card_cvc"], {{PLUS_WRAP}} .tpgb-caldera-form input[type="phone"], {{PLUS_WRAP}} .tpgb-caldera-form input[type="url"], {{PLUS_WRAP}} .tpgb-caldera-form input[type="color_picker"], {{PLUS_WRAP}} .tpgb-caldera-form input[type="date"], {{PLUS_WRAP}} .tpgb-caldera-form select.form-control, {{PLUS_WRAP}} .tpgb-caldera-form .flag-container',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form input[type="text"], {{PLUS_WRAP}} .tpgb-everest-form input[type="email"], {{PLUS_WRAP}} .tpgb-everest-form input[type="number"], {{PLUS_WRAP}} .tpgb-everest-form input[type="url"], {{PLUS_WRAP}} .tpgb-everest-form .everest-forms .evf-field-container .evf-frontend-row select',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gfield .ginput_container input[type="text"],{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gfield .ginput_container select,{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input[type="email"],{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input[type="tel"],{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input[type="url"]',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .nf-field-element input[type="text"], {{PLUS_WRAP}} .tpgb-ninja-form .nf-field-element input[type="email"], {{PLUS_WRAP}} .tpgb-ninja-form .nf-field-element input[type="number"], {{PLUS_WRAP}} .tpgb-ninja-form .nf-field-element input[type="tel"], {{PLUS_WRAP}} .tpgb-ninja-form .nf-field-element select',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-wp-form .wpforms-container .wpforms-field input[type="text"], {{PLUS_WRAP}} .tpgb-wp-form .wpforms-container .wpforms-field input[type="email"], {{PLUS_WRAP}} .tpgb-wp-form .wpforms-container .wpforms-field input[type="number"], {{PLUS_WRAP}} .tpgb-wp-form .wpforms-container .wpforms-field select',
				],
			],
		],
		'inputFBShadow' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 .wpcf7-form-control:not(.wpcf7-submit):not(.wpcf7-checkbox):not(.wpcf7-radio):not(.wpcf7-file):focus',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form input[type="text"]:focus, {{PLUS_WRAP}} .tpgb-caldera-form input[type="email"]:focus, {{PLUS_WRAP}} .tpgb-caldera-form input[type="number"]:focus, {{PLUS_WRAP}} .tpgb-caldera-form input[type="tel"]:focus, {{PLUS_WRAP}} .tpgb-caldera-form input[type="credit_card_cvc"]:focus, {{PLUS_WRAP}} .tpgb-caldera-form input[type="phone"]:focus, {{PLUS_WRAP}} .tpgb-caldera-form input[type="url"]:focus, {{PLUS_WRAP}} .tpgb-caldera-form input[type="color_picker"]:focus, {{PLUS_WRAP}} .tpgb-caldera-form input[type="date"]:focus, {{PLUS_WRAP}} .tpgb-caldera-form select.form-control:focus, {{PLUS_WRAP}} .tpgb-caldera-form .flag-container:focus',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form input[type="text"]:focus, {{PLUS_WRAP}} .tpgb-everest-form input[type="email"]:focus, {{PLUS_WRAP}} .tpgb-everest-form input[type="number"]:focus, {{PLUS_WRAP}} .tpgb-everest-form input[type="url"]:focus, {{PLUS_WRAP}} .tpgb-everest-form .everest-forms .evf-field-container .evf-frontend-row select:focus',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gfield .ginput_container input[type="text"]:focus,{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gfield .ginput_container select:focus,{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input[type="email"]:focus,{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input[type="tel"]:focus,{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input[type="url"]:focus',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .nf-field-element input[type="text"]:focus, {{PLUS_WRAP}} .tpgb-ninja-form .nf-field-element input[type="email"]:focus, {{PLUS_WRAP}} .tpgb-ninja-form .nf-field-element input[type="number"]:focus, {{PLUS_WRAP}} .tpgb-ninja-form .nf-field-element input[type="tel"]:focus, {{PLUS_WRAP}} .tpgb-ninja-form .list-select-wrap:focus .nf-field-element select',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-wp-form .wpforms-container .wpforms-field input[type="text"]:focus, {{PLUS_WRAP}} .tpgb-wp-form .wpforms-container .wpforms-field input[type="email"]:focus, {{PLUS_WRAP}} .tpgb-wp-form .wpforms-container .wpforms-field input[type="number"]:focus, {{PLUS_WRAP}} .tpgb-wp-form .wpforms-container .wpforms-field select:focus',
				],
			],
		],
		/* Input Field end*/
		
		/* TextArea Field start*/
		'textATypo' => [
			'type'=> 'object',
			'default'=> (object) [
				'openTypography' => 0,
				'size' => [ 'md' => '', 'unit' => 'px' ],
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 textarea.wpcf7-form-control:not(.wpcf7-submit):not(.wpcf7-checkbox):not(.wpcf7-radio):not(.wpcf7-file)',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form textarea.form-control',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-forms .evf-field-container .evf-frontend-row textarea',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gfield .ginput_container textarea',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .nf-field-element textarea',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .wpforms-container .wpforms-field.wpforms-field-textarea textarea',
				],
			],
		],
		'textAPHcolor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 textarea.wpcf7-form-control:not(.wpcf7-submit):not(.wpcf7-checkbox):not(.wpcf7-radio):not(.wpcf7-file)::placeholder{ color: {{textAPHcolor}}; }',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form textarea::placeholder{ color: {{textAPHcolor}}; }',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form textarea::placeholder{ color: {{textAPHcolor}}; }',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gfield .ginput_container textarea::placeholder{ color: {{textAPHcolor}}; }',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .nf-form-content  textarea::placeholder{ color: {{textAPHcolor}}; }',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .wpforms-container .wpforms-field.wpforms-field-textarea textarea::placeholder{ color: {{textAPHcolor}}; }',
				],
			],
		],
		'textAPadding' => [
			'type' => 'object',
			'default' => (object) [ 
				'md' => [
					"top" => '',
					"right" => '',
					"bottom" => '',
					"left" => '',
				],
				"unit" => 'px',
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 textarea.wpcf7-form-control:not(.wpcf7-submit):not(.wpcf7-checkbox):not(.wpcf7-radio):not(.wpcf7-file){padding: {{textAPadding}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form textarea.form-control{padding: {{textAPadding}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-forms .evf-field-container .evf-frontend-row textarea{padding: {{textAPadding}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gfield .ginput_container textarea{padding: {{textAPadding}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .wpforms-container .wpforms-field.wpforms-field-textarea textarea{padding: {{textAPadding}};}',
				],
			],
		],
		'textAMargin' => [
			'type' => 'object',
			'default' => (object) [ 
				'md' => [
					"top" => '',
					"right" => '',
					"bottom" => '',
					"left" => '',
				],
				"unit" => 'px',
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 textarea.wpcf7-form-control:not(.wpcf7-submit):not(.wpcf7-checkbox):not(.wpcf7-radio):not(.wpcf7-file){margin: {{textAMargin}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form textarea.form-control{margin: {{textAMargin}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-forms .evf-field-container .evf-frontend-row textarea{margin: {{textAMargin}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gfield .ginput_container textarea{margin: {{textAMargin}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .nf-field-element textarea{margin: {{textAMargin}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .wpforms-container .wpforms-field.wpforms-field-textarea textarea{margin: {{textAMargin}};}',
				],
			],
		],
		'textANColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 textarea.wpcf7-form-control:not(.wpcf7-submit):not(.wpcf7-checkbox):not(.wpcf7-radio):not(.wpcf7-file){color: {{textANColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form textarea.form-control{color: {{textANColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-forms .evf-field-container .evf-frontend-row textarea{color: {{textANColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gfield .ginput_container textarea{color: {{textANColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .nf-field-element textarea{color: {{textANColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .wpforms-container .wpforms-field.wpforms-field-textarea textarea{color: {{textANColor}};}',
				],
			],
		],
		'textANBG' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 textarea.wpcf7-form-control:not(.wpcf7-submit):not(.wpcf7-checkbox):not(.wpcf7-radio):not(.wpcf7-file)'
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form textarea.form-control',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-forms .evf-field-container .evf-frontend-row textarea',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gfield .ginput_container textarea',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .nf-field-element textarea',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .wpforms-container .wpforms-field.wpforms-field-textarea textarea',
				],
			],
		],
		'textAFColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 textarea.wpcf7-form-control:not(.wpcf7-submit):not(.wpcf7-checkbox):not(.wpcf7-radio):not(.wpcf7-file):focus{color: {{textAFColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form textarea:focus.form-control{color: {{textAFColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-forms .evf-field-container .evf-frontend-row textarea:focus{color: {{textAFColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gfield .ginput_container textarea:focus{color: {{textAFColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .nf-field-element textarea:focus{color: {{textAFColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .wpforms-container .wpforms-field.wpforms-field-textarea textarea:focus{color: {{textAFColor}};}',
				],
			],
		],
		'textAFBG' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 textarea.wpcf7-form-control:not(.wpcf7-submit):not(.wpcf7-checkbox):not(.wpcf7-radio):not(.wpcf7-file):focus',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form textarea:focus.form-control',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-forms .evf-field-container .evf-frontend-row textarea:focus',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gfield .ginput_container textarea:focus',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .nf-field-element textarea:focus',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .wpforms-container .wpforms-field.wpforms-field-textarea textarea:focus',
				],
			],
		],
		'textANBdr' => [
			'type' => 'object',
			'default' => (object) [
				'openBorder' => 0,	
				'type' => '',	
				'color' => '',	
				'width' => (object) [
					'md' => (object)[
						'top' => '',
						'left' => '',
						'bottom' => '',
						'right' => '',
					],
					'sm' => (object)[ ],
					'xs' => (object)[ ],
					"unit" => "px",
				],
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 textarea.wpcf7-form-control:not(.wpcf7-submit):not(.wpcf7-checkbox):not(.wpcf7-radio):not(.wpcf7-file)',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form textarea.form-control',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-forms .evf-field-container .evf-frontend-row textarea',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gfield .ginput_container textarea',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .nf-field-element textarea',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .wpforms-container .wpforms-field.wpforms-field-textarea textarea',
				],
			],
		],
		'textAFBdr' => [
			'type' => 'object',
			'default' => (object) [
				'openBorder' => 0,	
				'type' => '',	
				'color' => '',	
				'width' => (object) [
					'md' => (object)[
						'top' => '',
						'left' => '',
						'bottom' => '',
						'right' => '',
					],
					'sm' => (object)[ ],
					'xs' => (object)[ ],
					"unit" => "px",
				],
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 textarea.wpcf7-form-control:not(.wpcf7-submit):not(.wpcf7-checkbox):not(.wpcf7-radio):not(.wpcf7-file):focus',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form textarea:focus.form-control',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-forms .evf-field-container .evf-frontend-row textarea:focus',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gfield .ginput_container textarea:focus',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .nf-field-element textarea:focus',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .wpforms-container .wpforms-field.wpforms-field-textarea textarea:focus',
				],
			],
		],
		'textANBRadius' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 textarea.wpcf7-form-control:not(.wpcf7-submit):not(.wpcf7-checkbox):not(.wpcf7-radio):not(.wpcf7-file){border-radius: {{textANBRadius}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form textarea.form-control{border-radius: {{textANBRadius}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-forms .evf-field-container .evf-frontend-row textarea{border-radius: {{textANBRadius}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gfield .ginput_container textarea{border-radius: {{textANBRadius}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .nf-field-element textarea{border-radius: {{textANBRadius}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .wpforms-container .wpforms-field.wpforms-field-textarea textarea{border-radius: {{textANBRadius}};}',
				],
			],
		],
		'textAFBRadius' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} tpgb-contact-form-7 textarea.wpcf7-form-control:not(.wpcf7-submit):not(.wpcf7-checkbox):not(.wpcf7-radio):not(.wpcf7-file):focus{border-radius: {{textAFBRadius}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form textarea:focus.form-control{border-radius: {{textAFBRadius}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-forms .evf-field-container .evf-frontend-row textarea:focus{border-radius: {{textAFBRadius}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gfield .ginput_container textarea:focus{border-radius: {{textAFBRadius}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .nf-field-element textareaa:focus{border-radius: {{textAFBRadius}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .wpforms-container .wpforms-field.wpforms-field-textarea textarea:focus{border-radius: {{textAFBRadius}};}',
				],
			],
		],
		'textANBShadow' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 textarea.wpcf7-form-control:not(.wpcf7-submit):not(.wpcf7-checkbox):not(.wpcf7-radio):not(.wpcf7-file)',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form textarea.form-control',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-forms .evf-field-container .evf-frontend-row textarea',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gfield .ginput_container textarea',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .nf-field-element textarea',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .wpforms-container .wpforms-field.wpforms-field-textarea textarea',
				],
			],
		],
		'textAFBShadow' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 textarea.wpcf7-form-control:not(.wpcf7-submit):not(.wpcf7-checkbox):not(.wpcf7-radio):not(.wpcf7-file):focus',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form textarea:focus.form-control',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-forms .evf-field-container .evf-frontend-row textarea:focus',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gfield .ginput_container textarea:focus',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .nf-field-element textarea:focus',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .wpforms-container .wpforms-field.wpforms-field-textarea textarea:focus',
				],
			],
		],
		/* TextArea Field end*/
		
		/*Select Field(gravity-form) start*/
		'heightAuto' => [
			'type' => 'boolean',
			'default' => false,	
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ], ['key' => 'heightAuto', 'relation' => '==', 'value' => true ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gfield .ginput_container select{height: auto}',
				],
			],
		],
		'selectPadding' => [
			'type' => 'object',
			'default' => (object) [ 
				'md' => [
					"top" => '',
					"right" => '',
					"bottom" => '',
					"left" => '',
				],
				"unit" => 'px',
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gfield .ginput_container select{padding: {{selectPadding}};}',
				],
			],
		],
		/*Select Field(gravity-form) end*/
		
		/* CheckBox Field start*/
		'checkBTypo' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 span.wpcf7-form-control-wrap .input__checkbox_btn',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form .caldera-grid label,{{PLUS_WRAP}} .tpgb-caldera-form .caldera-grid .checkbox-inline',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .evf-field-checkbox label.everest-forms-field-label-inline',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gfield_checkbox li label',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .field-wrap.listcheckbox-wrap .nf-field-element label',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .wpforms-field.wpforms-field-checkbox li label,{{PLUS_WRAP}} .wpforms-field.wpforms-field-checkbox li.wpforms-image-choices-item .wpforms-image-choices-label',
				],
			],
		],
		'checkIconSize' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .evf-field-checkbox .everest-forms-field-label-inline .tpgb-everest-check{font-size: {{checkIconSize}}px;}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .ginput_container_checkbox .tpgb-gravity-check{font-size: {{checkIconSize}}px;}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .field-wrap.listcheckbox-wrap .nf-field-element label:before,{{PLUS_WRAP}} .tpgb-ninja-form .field-wrap.checkbox-wrap .nf-field-label label:before{font-size: {{checkIconSize}}px;}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .wpforms-field.wpforms-field-checkbox li label .tpgb-wp-check{font-size: {{checkIconSize}}px;}',
				],
			],
		],
		'checkBTextColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .evf-field-checkbox label.everest-forms-field-label-inline{color: {{checkBTextColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gfield_checkbox li label{color: {{checkBTextColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .field-wrap.listcheckbox-wrap .nf-field-element label{color: {{checkBTextColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .wpforms-field.wpforms-field-checkbox li label,{{PLUS_WRAP}} .wpforms-field.wpforms-field-checkbox li.wpforms-image-choices-item .wpforms-image-choices-label{color: {{checkBTextColor}};}',
				],
			],
		],
		'checkBUnCheckedColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .evf-field-checkbox .everest-forms-field-label-inline .tpgb-everest-check{color: {{checkBUnCheckedColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .ginput_container_checkbox .tpgb-gravity-check{color: {{checkBUnCheckedColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .checkbox-wrap .nf-field-element li label:before,
					{{PLUS_WRAP}} .tpgb-ninja-form .listcheckbox-wrap .nf-field-element li label:before{color: {{checkBUnCheckedColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .wpforms-field.wpforms-field-checkbox li:not(.wpforms-selected) label .tpgb-wp-check{color: {{checkBUnCheckedColor}};}',
				],
			],
		],
		'checkBCheckedColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 .input__checkbox_btn .toggle-button__icon .tpgb-checkcf7-icon{color: {{checkBCheckedColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form .caldera-grid input[type=checkbox]:checked + .caldera_checkbox_label{color: {{checkBCheckedColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-form .evf-field-checkbox input[type=checkbox]:checked + .everest-forms-field-label-inline .tpgb-everest-check{color: {{checkBCheckedColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .ginput_container_checkbox input[type=checkbox]:checked + label .tpgb-gravity-check{color: {{checkBCheckedColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .checkbox-wrap .nf-field-element label.nf-checked-label:before, {{PLUS_WRAP}} .tpgb-ninja-form .checkbox-wrap .nf-field-label label.nf-checked-label:before, {{PLUS_WRAP}} .tpgb-ninja-form .listcheckbox-wrap .nf-field-element label.nf-checked-label:before, {{PLUS_WRAP}} .tpgb-ninja-form .listcheckbox-wrap .nf-field-label label.nf-checked-label:before{color: {{checkBCheckedColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .wpforms-field.wpforms-field-checkbox li.wpforms-selected label .tpgb-wp-check{color: {{checkBCheckedColor}};}',
				],
			],
		],
		'checkBUnCheckedBG' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 .input__checkbox_btn .toggle-button__icon{background: {{checkBUnCheckedBG}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form label .caldera_checkbox_label{background: {{checkBUnCheckedBG}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .evf-field-checkbox .everest-forms-field-label-inline .tpgb-everest-check {background: {{checkBUnCheckedBG}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .ginput_container_checkbox .tpgb-gravity-check {background: {{checkBUnCheckedBG}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .checkbox-wrap .nf-field-element li label:before,
					{{PLUS_WRAP}} .tpgb-ninja-form .listcheckbox-wrap .nf-field-element li label:before {background: {{checkBUnCheckedBG}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .wpforms-field.wpforms-field-checkbox li:not(.wpforms-selected) label .tpgb-wp-check {background: {{checkBUnCheckedBG}};}',
				],
			],
		],
		'checkBCheckedBG' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 .input__checkbox_btn .toggle-button__icon .tpgb-checkcf7-icon{background: {{checkBCheckedBG}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form .caldera-grid input[type=checkbox]:checked + .caldera_checkbox_label{background: {{checkBCheckedBG}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-form .evf-field-checkbox input[type=checkbox]:checked + .everest-forms-field-label-inline .tpgb-everest-check{background: {{checkBCheckedBG}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .ginput_container_checkbox input[type=checkbox]:checked + label .tpgb-gravity-check{background: {{checkBCheckedBG}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .checkbox-wrap .nf-field-element label.nf-checked-label:before, {{PLUS_WRAP}} .tpgb-ninja-form .checkbox-wrap .nf-field-label label.nf-checked-label:before, {{PLUS_WRAP}} .tpgb-ninja-form .listcheckbox-wrap .nf-field-element label.nf-checked-label:before, {{PLUS_WRAP}} .tpgb-ninja-form .listcheckbox-wrap .nf-field-label label.nf-checked-label:before{background: {{checkBCheckedBG}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .wpforms-field.wpforms-field-checkbox li.wpforms-selected label .tpgb-wp-check{background: {{checkBCheckedBG}};}',
				],
			],
		],             
		'checkBBdr' => [
			'type' => 'object',
			'default' => (object) [
				'openBorder' => 0,	// 0 Or 1
				'type' => 'solid',	// 'solid' OR 'dotted' OR 'dashed' OR 'double'
				'color' => '',	// "#000"
				'width' => (object) [
					'md' => (object)[
						'top' => '',
						'left' => '',
						'bottom' => '',
						'right' => '',
					],
					'sm' => (object)[ ],
					'xs' => (object)[ ],
					"unit" => "",
				],
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 .input__checkbox_btn .toggle-button__icon',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form label .caldera_checkbox_label',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .evf-field-checkbox .everest-forms-field-label-inline .tpgb-everest-check',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .ginput_container_checkbox .tpgb-gravity-check',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .checkbox-wrap .nf-field-element li label:before,
					{{PLUS_WRAP}} .tpgb-ninja-form .listcheckbox-wrap .nf-field-element li label:before',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .wpforms-field.wpforms-field-checkbox li label .tpgb-wp-check,
					{{PLUS_WRAP}} div.wpforms-container .wpforms-form .wpforms-field-checkbox ul.wpforms-image-choices-modern li label, {{PLUS_WRAP}} div.wpforms-container .wpforms-form .wpforms-field-checkbox ul.wpforms-image-choices-classic li label',
				],
			],
		],
		'checkBBRadius' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 .input__checkbox_btn .toggle-button__icon{border-radius: {{checkBBRadius}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form label .caldera_checkbox_label{border-radius: {{checkBBRadius}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .evf-field-checkbox .everest-forms-field-label-inline .tpgb-everest-check{border-radius: {{checkBBRadius}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .ginput_container_checkbox .tpgb-gravity-check{border-radius: {{checkBBRadius}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .checkbox-wrap .nf-field-element li label:before,
					{{PLUS_WRAP}} .tpgb-ninja-form .listcheckbox-wrap .nf-field-element li label:before{border-radius: {{checkBBRadius}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .wpforms-field.wpforms-field-checkbox li label .tpgb-wp-check,
					{{PLUS_WRAP}} div.wpforms-container .wpforms-form .wpforms-field-checkbox ul.wpforms-image-choices-modern li label, {{PLUS_WRAP}} div.wpforms-container .wpforms-form .wpforms-field-checkbox ul.wpforms-image-choices-classic li label{border-radius: {{checkBBRadius}};}',
				],
			],
		],
		//Img Choice Style
		'wpImgChoiceStyle' => [
			'type' => 'boolean',
			'default' => false,	
		],
		'wpImgCPadding' => [
			'type' => 'object',
			'default' => (object) [ 
				'md' => [
					"top" => '',
					"right" => '',
					"bottom" => '',
					"left" => '',
				],
				"unit" => 'px',
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ],['key' => 'wpImgChoiceStyle', 'relation' => '==', 'value' => true ]],
					'selector' => '{{PLUS_WRAP}} div.wpforms-container .wpforms-form .wpforms-field-checkbox ul.wpforms-image-choices-modern label, {{PLUS_WRAP}} div.wpforms-container .wpforms-form .wpforms-field-checkbox ul.wpforms-image-choices-classic label, {{PLUS_WRAP}} div.wpforms-container .wpforms-form .wpforms-field-checkbox ul.wpforms-image-choices-none label{padding: {{wpImgCPadding}};}',
				],
			],
		],
		'imgCBNormal' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ],['key' => 'wpImgChoiceStyle', 'relation' => '==', 'value' => true ]],
					'selector' => '{{PLUS_WRAP}} div.wpforms-container .wpforms-form .wpforms-field-checkbox ul.wpforms-image-choices-modern label{background: {{imgCBNormal}};}
					{{PLUS_WRAP}} div.wpforms-container .wpforms-form .wpforms-field-checkbox ul.wpforms-image-choices-classic label{border: solid {{imgCBNormal}};}',
				],
			],
		],
		'imgCBSelected' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ],['key' => 'wpImgChoiceStyle', 'relation' => '==', 'value' => true ]],
					'selector' => '{{PLUS_WRAP}} div.wpforms-container .wpforms-form .wpforms-field-checkbox ul.wpforms-image-choices-modern li.wpforms-selected label{background: {{imgCBSelected}};}
					{{PLUS_WRAP}} div.wpforms-container .wpforms-form .wpforms-field-checkbox ul.wpforms-image-choices-classic li.wpforms-selected label{border: solid {{imgCBSelected}};}',
				],
			],
		],
		'imgCheckedColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ],['key' => 'wpImgChoiceStyle', 'relation' => '==', 'value' => true ]],
					'selector' => '{{PLUS_WRAP}} div.wpforms-container .wpforms-form .wpforms-field-checkbox ul.wpforms-image-choices-modern .wpforms-image-choices-image:after, {{PLUS_WRAP}} div.wpforms-container .wpforms-form .wpforms-field-checkbox ul.wpforms-image-choices-classic .wpforms-image-choices-image:after{color: {{imgCheckedColor}};}',
				],
			],
		],
		'imgCheckedBG' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ],['key' => 'wpImgChoiceStyle', 'relation' => '==', 'value' => true ]],
					'selector' => '{{PLUS_WRAP}} div.wpforms-container .wpforms-form .wpforms-field-checkbox ul.wpforms-image-choices-modern .wpforms-image-choices-image:after, {{PLUS_WRAP}} div.wpforms-container .wpforms-form .wpforms-field-checkbox ul.wpforms-image-choices-classic .wpforms-image-choices-image:after{background: {{imgCheckedBG}};}',
				],
			],
		],
		'imgIconSize' => [
			'type' => 'object',
			'default' => (object) [ 
				'md' => '',
				"unit" => 'px',
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} div.wpforms-container .wpforms-form .wpforms-field-checkbox ul.wpforms-image-choices-modern .wpforms-image-choices-image:after, {{PLUS_WRAP}} div.wpforms-container .wpforms-form .wpforms-field-checkbox ul.wpforms-image-choices-classic .wpforms-image-choices-image:after{ font-size: {{imgIconSize}}; }',
				],
			],
		],
		'imgIconBGSize' => [
			'type' => 'object',
			'default' => (object) [ 
				'md' => '',
				"unit" => 'px',
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} div.wpforms-container .wpforms-form .wpforms-field-checkbox ul.wpforms-image-choices-modern .wpforms-image-choices-image:after, {{PLUS_WRAP}} div.wpforms-container .wpforms-form .wpforms-field-checkbox ul.wpforms-image-choices-classic .wpforms-image-choices-image:after{ width: {{imgIconBGSize}}; height: {{imgIconBGSize}}; line-height: {{imgIconBGSize}}; }',
				],
			],
		],
		/* CheckBox Field end*/
		
		/* Radio Field start*/
		'radioTypo' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 span.wpcf7-form-control-wrap .input__radio_btn',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .caldera-grid .radio label,{{PLUS_WRAP}} .caldera-grid .radio-inline',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .evf-field-radio label.everest-forms-field-label-inline',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gfield_radio li label',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .field-wrap.listradio-wrap .nf-field-element label',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .wpforms-field.wpforms-field-radio li label,{{PLUS_WRAP}} .wpforms-field.wpforms-field-radio li.wpforms-image-choices-item .wpforms-image-choices-label',
				],
			],
		],
		'radioIconSize' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .evf-field-radio .everest-forms-field-label-inline .tpgb-everest-radio{font-size: {{radioIconSize}}px;}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .ginput_container_radio .tpgb-gravity-radio{font-size: {{radioIconSize}}px;}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .wpforms-field.wpforms-field-radio li label .tpgb-wp-radio{font-size: {{radioIconSize}}px;}',
				],
			],
		],
		'radioBTextColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .evf-field-radio label.everest-forms-field-label-inline{color: {{radioBTextColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gfield_radio li label{color: {{radioBTextColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .field-wrap.listradio-wrap .nf-field-element label{color: {{radioBTextColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .wpforms-field.wpforms-field-radio li label, {{PLUS_WRAP}} .wpforms-field.wpforms-field-radio li.wpforms-image-choices-item .wpforms-image-choices-label{color: {{radioBTextColor}};}',
				],
			],
		],
		'radioBUnCheckedColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .evf-field-radio .everest-forms-field-label-inline .tpgb-everest-radio{color: {{radioBUnCheckedColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .ginput_container_radio .tpgb-gravity-radio{color: {{radioBUnCheckedColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .listradio-wrap .nf-field-element label:after{color: {{radioBUnCheckedColor}};} {{PLUS_WRAP}} .tpgb-ninja-form .listradio-wrap .nf-field-element label:after{border: 2px solid {{radioBUnCheckedColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .wpforms-field.wpforms-field-radio li:not(.wpforms-selected) label .tpgb-wp-radio{color: {{radioBUnCheckedColor}};}',
				],
			],
		],
		'radioCheckColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 .input__radio_btn .toggle-button__icon .tpgb-radiocf7-icon{color: {{radioCheckColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .caldera-grid .radio input[type=radio]:checked + .caldera_radio_label{color: {{radioCheckColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .everest-form .evf-field-radio input[type=radio]:checked + .everest-forms-field-label-inline .tpgb-everest-radio{color: {{radioCheckColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .gform_wrapper .ginput_container_radio input[type=radio]:checked + label .tpgb-gravity-radio{color: {{radioCheckColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .listradio-wrap .nf-field-element label.nf-checked-label:before{background: {{radioCheckColor}};} {{PLUS_WRAP}} .listradio-wrap .nf-field-element label.nf-checked-label:after{border-color: {{radioCheckColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .wpforms-field.wpforms-field-radio li.wpforms-selected label .tpgb-wp-radio{color: {{radioCheckColor}};}',
				],
			],
		],
		'radioUncheckBG' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 label.input__radio_btn .toggle-button__icon{background: {{radioUncheckBG}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .radio label .caldera_radio_label{background: {{radioUncheckBG}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .evf-field-radio .everest-forms-field-label-inline .tpgb-everest-radio{background: {{radioUncheckBG}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .ginput_container_radio .tpgb-gravity-radio{background: {{radioUncheckBG}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .wpforms-field.wpforms-field-radio li:not(.wpforms-selected) label .tpgb-wp-radio{background: {{radioUncheckBG}};}',
				],
			],
		],
		'radioCheckBG' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 .input__radio_btn .toggle-button__icon .tpgb-radiocf7-icon{background: {{radioCheckBG}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .caldera-grid .radio input[type=radio]:checked + .caldera_radio_label{background: {{radioCheckBG}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .everest-form .evf-field-radio input[type=radio]:checked + .everest-forms-field-label-inline .tpgb-everest-radio{background: {{radioCheckBG}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .gform_wrapper .ginput_container_radio input[type=radio]:checked + label .tpgb-gravity-radio{background: {{radioCheckBG}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .wpforms-field.wpforms-field-radio li.wpforms-selected label .tpgb-wp-radio{background: {{radioCheckBG}};}',
				],
			],
		],         
		'radioBdr' => [
			'type' => 'object',
			'default' => (object) [
				'openBorder' => 0,	// 0 Or 1
				'type' => 'solid',	// 'solid' OR 'dotted' OR 'dashed' OR 'double'
				'color' => '',	// "#000"
				'width' => (object) [
					'md' => (object)[
						'top' => '',
						'left' => '',
						'bottom' => '',
						'right' => '',
					],
					'sm' => (object)[ ],
					'xs' => (object)[ ],
					"unit" => "px",
				],
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 .input__radio_btn .toggle-button__icon',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form .radio label .caldera_radio_label',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .evf-field-radio .everest-forms-field-label-inline .tpgb-everest-radio',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .ginput_container_radio .tpgb-gravity-radio',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .wpforms-field.wpforms-field-radio li label .tpgb-wp-radio, {{PLUS_WRAP}} div.wpforms-container .wpforms-form .wpforms-field-radio ul.wpforms-image-choices-modern li label, {{PLUS_WRAP}} div.wpforms-container .wpforms-form .wpforms-field-checkbox ul.wpforms-image-choices-classic li label',
				],
			],
		],         
		'radioBRadius' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 .input__radio_btn .toggle-button__icon{border-radius: {{radioBRadius}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form .radio label .caldera_radio_label{border-radius: {{radioBRadius}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .evf-field-radio .everest-forms-field-label-inline .tpgb-everest-radio{border-radius: {{radioBRadius}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .ginput_container_radio .tpgb-gravity-radio{border-radius: {{radioBRadius}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .wpforms-field.wpforms-field-radio li label .tpgb-wp-radio, {{PLUS_WRAP}} div.wpforms-container .wpforms-form .wpforms-field-radio ul.wpforms-image-choices-modern li label, {{PLUS_WRAP}} div.wpforms-container .wpforms-form .wpforms-field-checkbox ul.wpforms-image-choices-classic li label{border-radius: {{radioBRadius}};}',
				],
			],
		],
		//Img Choice Style
		'wpImgChoiceRadioStyle' => [
			'type' => 'boolean',
			'default' => false,	
		],
		'wpImgRPadding' => [
			'type' => 'object',
			'default' => (object) [ 
				'md' => [
					"top" => '',
					"right" => '',
					"bottom" => '',
					"left" => '',
				],
				"unit" => 'px',
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ],['key' => 'wpImgChoiceRadioStyle', 'relation' => '==', 'value' => true ]],
					'selector' => '{{PLUS_WRAP}} div.wpforms-container .wpforms-form .wpforms-field-radio ul.wpforms-image-choices-modern label, {{PLUS_WRAP}} div.wpforms-container .wpforms-form .wpforms-field-radio ul.wpforms-image-choices-classic label, {{PLUS_WRAP}} div.wpforms-container .wpforms-form .wpforms-field-radio ul.wpforms-image-choices-none label{padding: {{wpImgRPadding}};}',
				],
			],
		],
		'imgRNormal' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ],['key' => 'wpImgChoiceRadioStyle', 'relation' => '==', 'value' => true ]],
					'selector' => '{{PLUS_WRAP}} div.wpforms-container .wpforms-form .wpforms-field-radio ul.wpforms-image-choices-modern label{background: {{imgRNormal}};}
					{{PLUS_WRAP}} div.wpforms-container .wpforms-form .wpforms-field-radio ul.wpforms-image-choices-classic label{border: solid {{imgRNormal}};}',
				],
			],
		],
		'imgRSelected' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ],['key' => 'wpImgChoiceRadioStyle', 'relation' => '==', 'value' => true ]],
					'selector' => '{{PLUS_WRAP}} div.wpforms-container .wpforms-form .wpforms-field-radio ul.wpforms-image-choices-modern li.wpforms-selected label{background: {{imgRSelected}};}
					{{PLUS_WRAP}} div.wpforms-container .wpforms-form .wpforms-field-radio ul.wpforms-image-choices-classic li.wpforms-selected label{border: solid {{imgRSelected}};}',
				],
			],
		],
		'imgRadioColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ],['key' => 'wpImgChoiceRadioStyle', 'relation' => '==', 'value' => true ]],
					'selector' => '{{PLUS_WRAP}} div.wpforms-container .wpforms-form .wpforms-field-radio ul.wpforms-image-choices-modern .wpforms-image-choices-image:after, {{PLUS_WRAP}} div.wpforms-container .wpforms-form .wpforms-field-radio ul.wpforms-image-choices-classic .wpforms-image-choices-image:after{color: {{imgRadioColor}};}',
				],
			],
		],
		'imgRadioBG' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ],['key' => 'wpImgChoiceRadioStyle', 'relation' => '==', 'value' => true ]],
					'selector' => '{{PLUS_WRAP}} div.wpforms-container .wpforms-form .wpforms-field-radio ul.wpforms-image-choices-modern .wpforms-image-choices-image:after, {{PLUS_WRAP}} div.wpforms-container .wpforms-form .wpforms-field-radio ul.wpforms-image-choices-classic .wpforms-image-choices-image:after{background: {{imgRadioBG}};}',
				],
			],
		],
		'imgRadioIconSize' => [
			'type' => 'object',
			'default' => (object) [ 
				'md' => '',
				"unit" => 'px',
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ],['key' => 'wpImgChoiceRadioStyle', 'relation' => '==', 'value' => true ]],
					'selector' => '{{PLUS_WRAP}} div.wpforms-container .wpforms-form .wpforms-field-radio ul.wpforms-image-choices-modern .wpforms-image-choices-image:after, {{PLUS_WRAP}} div.wpforms-container .wpforms-form .wpforms-field-radio ul.wpforms-image-choices-classic .wpforms-image-choices-image:after{ font-size: {{imgRadioIconSize}}; }',
				],
			],
		],
		'imgRadioIconBGSize' => [
			'type' => 'object',
			'default' => (object) [ 
				'md' => '',
				"unit" => 'px',
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ],['key' => 'wpImgChoiceRadioStyle', 'relation' => '==', 'value' => true ]],
					'selector' => '{{PLUS_WRAP}} div.wpforms-container .wpforms-form .wpforms-field-radio ul.wpforms-image-choices-modern .wpforms-image-choices-image:after, {{PLUS_WRAP}} div.wpforms-container .wpforms-form .wpforms-field-radio ul.wpforms-image-choices-classic .wpforms-image-choices-image:after{ width: {{imgRadioIconBGSize}}; height: {{imgRadioIconBGSize}}; line-height: {{imgRadioIconBGSize}}; }',
				],
			],
		],
		/* Radio Field end*/
		
		/* Toggle Field start*/
		'tglBtnTypo' => [
			'type'=> 'object',
			'default'=> (object) [
				'openTypography' => 0,
				'size' => [ 'md' => '', 'unit' => 'px' ],
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form .cf-toggle-group-buttons .btn',
				],
			],
		],
		'tglBtnPadding' => [
			'type' => 'object',
			'default' => (object) [ 
				'md' => [
					"top" => '',
					"right" => '',
					"bottom" => '',
					"left" => '',
				],
				"unit" => 'px',
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form .cf-toggle-group-buttons .btn{padding: {{tglBtnPadding}};}',
				],
			],
		],
		'tglBtnMargin' => [
			'type' => 'object',
			'default' => (object) [ 
				'md' => [
					"top" => '',
					"right" => '',
					"bottom" => '',
					"left" => '',
				],
				"unit" => 'px',
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form .cf-toggle-group-buttons .btn{margin: {{tglBtnMargin}};}',
				],
			],
		],
		'tglBtnNTextColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .caldera-grid .cf-toggle-group-buttons .btn{color: {{tglBtnNTextColor}};}',
				],
			],
		],
		'tglBtnHTextColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .caldera-grid .cf-toggle-group-buttons .btn:hover{color: {{tglBtnHTextColor}};}',
				],
			],
		],
		'tglBtnATextColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .caldera-grid .cf-toggle-group-buttons .btn.btn-success{color: {{tglBtnATextColor}};}',
				],
			],
		],
		'tglBtnNBG' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .caldera-grid .cf-toggle-group-buttons .btn{background: {{tglBtnNBG}};}',
				],
			],
		],
		'tglBtnHBG' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .caldera-grid .cf-toggle-group-buttons .btn:hover{background: {{tglBtnHBG}};}',
				],
			],
		],
		'tglBtnABG' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .caldera-grid .cf-toggle-group-buttons .btn.btn-success{background: {{tglBtnABG}};}',
				],
			],
		],
		'tglBtnNBdr' => [
			'type' => 'object',
			'default' => (object) [
				'openBorder' => 0,	// 0 Or 1
				'type' => 'solid',	// 'solid' OR 'dotted' OR 'dashed' OR 'double'
				'color' => '',	// "#000"
				'width' => (object) [
					'md' => (object)[
						'top' => '',
						'left' => '',
						'bottom' => '',
						'right' => '',
					],
					'sm' => (object)[ ],
					'xs' => (object)[ ],
					"unit" => "px",
				],
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .caldera-grid .cf-toggle-group-buttons .btn, {{PLUS_WRAP}} .caldera-grid .cf-toggle-group-buttons .btn:hover, {{PLUS_WRAP}} .caldera-grid .cf-toggle-group-buttons .btn.btn-success',
				],
			],
		],
		'tglBtnHBdrColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .caldera-grid .cf-toggle-group-buttons .btn:hover{border-color: {{tglBtnHBdrColor}};}',
				],
			],
		],
		'tglBtnABdrColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .caldera-grid .cf-toggle-group-buttons .btn.btn-success{border-color: {{tglBtnABdrColor}};}',
				],
			],
		],
		'tglBtnNBRadius' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .caldera-grid .cf-toggle-group-buttons .btn, {{PLUS_WRAP}} .caldera-grid .cf-toggle-group-buttons .btn:hover, {{PLUS_WRAP}} .caldera-grid .cf-toggle-group-buttons .btn.btn-success{border-radius: {{tglBtnNBRadius}};}',
				],
			],
		],
		'tglBtnNBShadow' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .caldera-grid .cf-toggle-group-buttons .btn',
				],
			],
		],
		'tglBtnHBShadow' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .caldera-grid .cf-toggle-group-buttons .btn:hover',
				],
			],
		],
		'tglBtnABShadow' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .caldera-grid .cf-toggle-group-buttons .btn.btn-success',
				],
			],
		],
		/* Toggle Field end*/
		
		/* File Field start*/
		'fileTypo' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 .wpcf7-file + .input__file_btn',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form input[type=file],{{PLUS_WRAP}} .tpgb-caldera-form .caldera_forms_form .form-control.cf2-file .btn',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .ginput_container_fileupload input[type="file"]',
				],
			],
		],
		'filePadding' => [
			'type' => 'object',
			'default' => (object) [ 
				'md' => [
					"top" => '',
					"right" => '',
					"bottom" => '',
					"left" => '',
				],
				"unit" => 'px',
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form input[type=file],{{PLUS_WRAP}} .tpgb-caldera-form .caldera_forms_form .form-control.cf2-file .btn{padding: {{filePadding}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .ginput_container_fileupload{padding: {{filePadding}};}',
				],
			],
		],
		'fileMargin' => [
			'type' => 'object',
			'default' => (object) [ 
				'md' => [
					"top" => '',
					"right" => '',
					"bottom" => '',
					"left" => '',
				],
				"unit" => 'px',
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form input[type=file],{{PLUS_WRAP}} .tpgb-caldera-form .caldera_forms_form .form-control.cf2-file .btn{margin: {{fileMargin}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .ginput_container_fileupload{margin: {{fileMargin}};}',
				],
			],
		],
		'fileMinHeight' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 span.wpcf7-form-control-wrap.your-file.cf7-style-file{min-height: {{fileMinHeight}}px;}',
				],
			],
		],
		'fileAlign' => [
			'type' => 'string',
			'default' => 'center',
			'style' => [
				(object) [  
					'condition' => [ (object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ],['key' => 'fileAlign', 'relation' => '==', 'value' => 'left'],],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 span.wpcf7-form-control-wrap.cf7-style-file {-webkit-justify-content: flex-start; -ms-flex-pack: flex-start; justify-content: flex-start;}',
					'{{PLUS_WRAP}} .tpgb-contact-form-7 span.wpcf7-form-control-wrap.cf7-style-file span{text-align: {{fileAlign}};}',
				],
				(object) [  
					'condition' => [ (object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ],['key' => 'fileAlign', 'relation' => '==', 'value' => 'center'],],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 span.wpcf7-form-control-wrap.cf7-style-file {-webkit-justify-content: flex-start; -ms-flex-pack: flex-start; justify-content: flex-start;}',
					'{{PLUS_WRAP}} .tpgb-contact-form-7 span.wpcf7-form-control-wrap.cf7-style-file span{text-align: {{fileAlign}};}',
				],
				(object) [  
					'condition' => [ (object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ],['key' => 'fileAlign', 'relation' => '==', 'value' => 'right'],],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 span.wpcf7-form-control-wrap.cf7-style-file {-webkit-justify-content: flex-end; -ms-flex-pack: flex-end; justify-content: flex-end;}',
					'{{PLUS_WRAP}} .tpgb-contact-form-7 span.wpcf7-form-control-wrap.cf7-style-file span{text-align: {{fileAlign}};}',
				],
			],
		],
		'fileStyle' => [
			'type' => 'boolean',
			'default' => false,
			'style' => [
				(object) [ 
					'condition' => [ (object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ],['key' => 'fileStyle', 'relation' => '==', 'value' => true],], 
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 span.wpcf7-form-control-wrap.cf7-style-file .input__file_btn svg,{{PLUS_WRAP}} .tpgb-contact-form-7 span.wpcf7-form-control-wrap.cf7-style-file span{display:block;margin: 0 auto;text-align:center;}',
				],
			],
		],
		'fileTextColor' => [
			'type' => 'string',
			'default' => '#212121',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 span.wpcf7-form-control-wrap.cf7-style-file .input__file_btn span{color: {{fileTextColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form input[type=file],{{PLUS_WRAP}} .tpgb-caldera-form .caldera_forms_form .form-control.cf2-file .btn{color: {{fileTextColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .ginput_container_fileupload input[type="file"]{color: {{fileTextColor}};}',
				],
			],
		],
		'fileTextHColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 span.wpcf7-form-control-wrap.cf7-style-file .input__file_btn:hover span{color: {{fileTextHColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form input[type=file]:hover,{{PLUS_WRAP}} .tpgb-caldera-form .caldera_forms_form .form-control.cf2-file .btn:hover{color: {{fileTextHColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .ginput_container_fileupload input[type="file"]:hover{color: {{fileTextHColor}};}',
				],
			],
		],
		'fileIconColor' => [
			'type' => 'string',
			'default' => '#212121',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 span.wpcf7-form-control-wrap.cf7-style-file .input__file_btn svg *{fill: {{fileIconColor}};stroke:none;}',
				],
			],
		],
		'fileIconHColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 span.wpcf7-form-control-wrap.cf7-style-file .input__file_btn:hover svg *{fill: {{fileIconHColor}};stroke:none;}',
				],
			],
		],
		'fileBG' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 span.wpcf7-form-control-wrap.cf7-style-file .wpcf7-file + .input__file_btn',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form input[type=file],{{PLUS_WRAP}} .tpgb-caldera-form .caldera_forms_form .form-control.cf2-file .btn',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .ginput_container_fileupload input[type="file"]',
				],
			],
		],
		'fileHBG' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 span.wpcf7-form-control-wrap.cf7-style-file .wpcf7-file + .input__file_btn:hover',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form input[type=file]:hover,{{PLUS_WRAP}} .tpgb-caldera-form .caldera_forms_form .form-control.cf2-file .btn:hover',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .ginput_container_fileupload input[type="file"]:hover',
				],
			],
		],
		'fileBdr' => [
			'type' => 'object',
			'default' => (object) [
				'openBorder' => 0,	// 0 Or 1
				'type' => 'solid',	// 'solid' OR 'dotted' OR 'dashed' OR 'double'
				'color' => '',	// "#000"
				'width' => (object) [
					'md' => (object)[
						'top' => '',
						'left' => '',
						'bottom' => '',
						'right' => '',
					],
					'sm' => (object)[ ],
					'xs' => (object)[ ],
					"unit" => "px",
				],
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 .cf7-style-file .wpcf7-file + .input__file_btn',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form input[type=file],{{PLUS_WRAP}} .tpgb-caldera-form .caldera_forms_form .form-control.cf2-file .btn',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .ginput_container_fileupload input[type="file"]',
				],
			],
		], 
		'fileBdrHColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 span.wpcf7-form-control-wrap.cf7-style-file .input__file_btn:hover{border-color: {{fileBdrHColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form input[type=file]:hover,{{PLUS_WRAP}} .tpgb-caldera-form .caldera_forms_form .form-control.cf2-file .btn:hover{border-color: {{fileBdrHColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .ginput_container_fileupload input[type="file"]:hover{border-color: {{fileBdrHColor}};}',
				],
			],
		],
		'fileBRadius' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 .cf7-style-file .wpcf7-file + .input__file_btn{border-radius: {{fileBRadius}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form input[type=file],{{PLUS_WRAP}} .tpgb-caldera-form .caldera_forms_form .form-control.cf2-file .btn{border-radius: {{fileBRadius}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .ginput_container_fileupload input[type="file"]{border-radius: {{fileBRadius}};}',
				],
			],
		],
		'fileNBshadow' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 .cf7-style-file .wpcf7-file + .input__file_btn',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form input[type=file],{{PLUS_WRAP}} .tpgb-caldera-form .caldera_forms_form .form-control.cf2-file .btn',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .ginput_container_fileupload input[type="file"]',
				],
			],
		],
		'fileHBshadow' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 .cf7-style-file .wpcf7-file + .input__file_btn:hover',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form input[type=file]:hover,{{PLUS_WRAP}} .tpgb-caldera-form .caldera_forms_form .form-control.cf2-file .btn:hover',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .ginput_container_fileupload input[type="file"]:hover',
				],
			],
		],
		'multipleFileUpld' => [
			'type' => 'boolean',
			'default' => false,
		],
		'mFileTypo' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ], ['key' => 'multipleFileUpld', 'relation' => '==', 'value' => true ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input.button.gform_button_select_files',
				],
			],
		],
		'mFileTextNColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ], ['key' => 'multipleFileUpld', 'relation' => '==', 'value' => true ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input.button.gform_button_select_files{color: {{mFileTextNColor}};}',
				],
			],
		],
		'mFileTextHColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ], ['key' => 'multipleFileUpld', 'relation' => '==', 'value' => true ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input.button.gform_button_select_files:hover{color: {{mFileTextHColor}};}',
				],
			],
		],
		'mFileNBG' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ], ['key' => 'multipleFileUpld', 'relation' => '==', 'value' => true ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input.button.gform_button_select_files',
				],
			],
		],
		'mFileHBG' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ], ['key' => 'multipleFileUpld', 'relation' => '==', 'value' => true ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input.button.gform_button_select_files:hover',
				],
			],
		],
		'mFileBdr' => [
			'type' => 'object',
			'default' => (object) [
				'openBorder' => 0,	// 0 Or 1
				'type' => 'solid',	// 'solid' OR 'dotted' OR 'dashed' OR 'double'
				'color' => '',	// "#000"
				'width' => (object) [
					'md' => (object)[
						'top' => '',
						'left' => '',
						'bottom' => '',
						'right' => '',
					],
					'sm' => (object)[ ],
					'xs' => (object)[ ],
					"unit" => "px",
				],
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ], ['key' => 'multipleFileUpld', 'relation' => '==', 'value' => true ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input.button.gform_button_select_files',
				],
			],
		], 
		'mFileBdrHColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ], ['key' => 'multipleFileUpld', 'relation' => '==', 'value' => true ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input.button.gform_button_select_files:hover{border-color: {{mFileBdrHColor}};}',
				],
			],
		],
		'mFileBRadius' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ], ['key' => 'multipleFileUpld', 'relation' => '==', 'value' => true ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input.button.gform_button_select_files{border-radius: {{mFileBRadius}};}',
				],
			],
		],
		'mFileNBshadow' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ], ['key' => 'multipleFileUpld', 'relation' => '==', 'value' => true ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input.button.gform_button_select_files',
				],
			],
		],
		'mFileHBshadow' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ], ['key' => 'multipleFileUpld', 'relation' => '==', 'value' => true ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input.button.gform_button_select_files:hover',
				],
			],
		],
		/* File Field end*/
		
		/* Summary/Break Field start*/
		'summryHeadTypo' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form h2',
				],
			],
		],
		'secBrkColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form .caldera-grid hr{border-color: {{secBrkColor}};}',
				],
			],
		],
		'summryHeadColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form h2{border-color: {{summryHeadColor}};}',
				],
			],
		],
		'summryHeadAlign' => [
			'type' => 'string',
			'default' => 'center',
			'style' => [
				(object) [  
					'condition' => [ (object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form h2{text-align: {{fileAlign}};}',
				],
			],
		],
		'summryHBelowSpace' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form h2{margin-bottom: {{summryHBelowSpace}px;}',
				],
			],
		],
		'summaryPadding' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form .caldera-forms-summary-field{padding: {{summaryPadding}};}',
				],
			],
		],
		'summaryTypo' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form .caldera-forms-summary-field ul>li',
				],
			],
		],
		'summaryColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form .caldera-forms-summary-field ul>li{color: {{summaryColor}};}',
				],
			],
		],
		/* Summary/Break Field start*/
		
		/* Caldera Special Field start*/
		'calSpecCalTypo' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form .total-line',
				],
			],
		],
		'calSpecCalColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form .total-line{color: {{calSpecCalColor}};}',
				],
			],
		],
		'consentTypo' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form label.caldera-forms-gdpr-field-label,{{PLUS_WRAP}} .tpgb-caldera-form .caldera-forms-consent-field-linked_text',
				],
			],
		],
		'consentColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form label.caldera-forms-gdpr-field-label,{{PLUS_WRAP}} .tpgb-caldera-form .caldera-forms-consent-field-linked_text{color: {{consentColor}};}',
				],
			],
		],
		'consentPrivHColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form .caldera-forms-consent-field-linked_text:hover{color: {{consentPrivHColor}};}',
				],
			],
		],
		'consentReqSignColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form .caldera-forms-consent-field span{color: {{consentReqSignColor}} !important;}',
				],
			],
		],
		/* Caldera Special Field end*/
		
		/* Outer Field start*/
		'outerPadding' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7.tpgb-cf7-label form.wpcf7-form  label,{{PLUS_WRAP}} .tpgb-contact-form-7.tpgb-cf7-custom form.wpcf7-form .tpgb-cf7-outer{padding: {{outerPadding}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form .form-group{padding: {{outerPadding}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-forms .evf-field{padding: {{outerPadding}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper ul li.gfield{padding: {{outerPadding}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .nf-field-container:not(.submit-container){padding: {{outerPadding}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-wp-form div.wpforms-container .wpforms-field,{{PLUS_WRAP}} .tpgb-wp-form .wpforms-container .wpforms-submit-container{padding: {{outerPadding}};}',
				],
			],
		],
		'outerMargin' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7.tpgb-cf7-label form.wpcf7-form  label,{{PLUS_WRAP}} .tpgb-contact-form-7.tpgb-cf7-custom form.wpcf7-form .tpgb-cf7-outer{margin: {{outerMargin}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form .form-group{margin: {{outerMargin}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-forms .evf-field{margin: {{outerMargin}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper ul li.gfield{margin: {{outerMargin}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .nf-field-container:not(.submit-container){margin: {{outerMargin}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} div.wpforms-container .wpforms-field,{{PLUS_WRAP}} .wpforms-container .wpforms-submit-container{margin: {{outerMargin}};}',
				],
			],
		],
		'outerNBG' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7.tpgb-cf7-label form.wpcf7-form  label,{{PLUS_WRAP}} .tpgb-contact-form-7.tpgb-cf7-custom form.wpcf7-form .tpgb-cf7-outer',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form .form-group',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-forms .evf-field',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper ul li.gfield',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .nf-field-container:not(.submit-container)',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .wpforms-container .wpforms-field',
				],
			],
		],
		'outerHBG' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form.tpgb-cf7-label form.wpcf7-form  label:hover,{{PLUS_WRAP}} .tpgb-contact-form.tpgb-cf7-custom form.wpcf7-form .tpgb-cf7-outer:hover',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form .form-group:hover',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-forms .evf-field:hover',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper ul li.gfield:hover',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .nf-field-container:not(.submit-container):hover',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .wpforms-container .wpforms-field:hover',
				],
			],
		],          
		'outerNBdr' => [
			'type' => 'object',
			'default' => (object) [
				'openBorder' => 0,	// 0 Or 1
				'type' => 'solid',	// 'solid' OR 'dotted' OR 'dashed' OR 'double'
				'color' => '',	// "#000"
				'width' => (object) [
					'md' => (object)[
						'top' => '',
						'left' => '',
						'bottom' => '',
						'right' => '',
					],
					'sm' => (object)[ ],
					'xs' => (object)[ ],
					"unit" => "px",
				],
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7.tpgb-cf7-label form.wpcf7-form  label,{{PLUS_WRAP}} .tpgb-contact-form-7.tpgb-cf7-custom form.wpcf7-form .tpgb-cf7-outer',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form .form-group',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-forms .evf-field',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper ul li.gfield',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .nf-field-container:not(.submit-container)',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .wpforms-container .wpforms-field',
				],
			],
		],
		'outerHBdr' => [
			'type' => 'object',
			'default' => (object) [
				'openBorder' => 0,	// 0 Or 1
				'type' => 'solid',	// 'solid' OR 'dotted' OR 'dashed' OR 'double'
				'color' => '',	// "#000"
				'width' => (object) [
					'md' => (object)[
						'top' => '',
						'left' => '',
						'bottom' => '',
						'right' => '',
					],
					'sm' => (object)[ ],
					'xs' => (object)[ ],
					"unit" => "px",
				],
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7.tpgb-cf7-label form.wpcf7-form  label,{{PLUS_WRAP}} .tpgb-contact-form-7.tpgb-cf7-custom form.wpcf7-form .tpgb-cf7-outer:hover',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form .form-group:hover',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-forms .evf-field:hover',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper ul li.gfield:hover',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .nf-field-container:not(.submit-container):hover',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .wpforms-container .wpforms-field:hover',
				],
			],
		],           
		'outerNBRadius' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form.tpgb-cf7-label form.wpcf7-form  label,{{PLUS_WRAP}} .tpgb-contact-form-7.tpgb-cf7-custom form.wpcf7-form .tpgb-cf7-outer{border-radius: {{outerNBRadius}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form .form-group{border-radius: {{outerNBRadius}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-forms .evf-field{border-radius: {{outerNBRadius}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper ul li.gfield{border-radius: {{outerNBRadius}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .nf-field-container:not(.submit-container){border-radius: {{outerNBRadius}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .wpforms-container .wpforms-field{border-radius: {{outerNBRadius}};}',
				],
			],
		],
		'outerHBRadius' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7.tpgb-cf7-label form.wpcf7-form  label:hover,{{PLUS_WRAP}} .tpgb-contact-form-7.tpgb-cf7-custom form.wpcf7-form .tpgb-cf7-outer:hover{border-radius: {{outerHBRadius}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form .form-group:hover{border-radius: {{outerHBRadius}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-forms .evf-field:hover{border-radius: {{outerHBRadius}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper ul li.gfield:hover{border-radius: {{outerHBRadius}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .nf-field-container:not(.submit-container):hover {border-radius: {{outerHBRadius}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .wpforms-container .wpforms-field:hover {border-radius: {{outerHBRadius}};}',
				],
			],
		],
		'outerNBshadow' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7.tpgb-cf7-label form.wpcf7-form  label,{{PLUS_WRAP}} .tpgb-contact-form-7.tpgb-cf7-custom form.wpcf7-form .tpgb-cf7-outer',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form .form-group',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-forms .evf-field',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper ul li.gfield',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .nf-field-container:not(.submit-container)',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .wpforms-container .wpforms-field',
				],
			],
		],
		'outerHBshadow' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7.tpgb-cf7-label form.wpcf7-form  label:hover,{{PLUS_WRAP}} .tpgb-contact-form-7.tpgb-cf7-custom form.wpcf7-form .tpgb-cf7-outer:hover',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form .form-group:hover',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-forms .evf-field:hover',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper ul li.gfield:hover',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .nf-field-container:not(.submit-container):hover',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .wpforms-container .wpforms-field:hover',
				],
			],
		],
		/* Outer Field end*/
		
		/* Button Field start*/
		'btnMWidth' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 input.wpcf7-form-control.wpcf7-submit{max-width: {{btnMWidth}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form input[type = submit], {{PLUS_WRAP}} .tpgb-caldera-form input[type = button], {{PLUS_WRAP}} .tpgb-caldera-form input[type = reset]{width: {{btnMWidth}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-forms .everest-forms-part-button,{{PLUS_WRAP}} .tpgb-everest-form .everest-forms button[type=submit],{{PLUS_WRAP}} .tpgb-everest-form .everest-forms input[type=submit]{width: {{btnMWidth}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input[type="button"],
					{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input[type="submit"]{width: {{btnMWidth}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .field-wrap input[type=button]{max-width: {{btnMWidth}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} div.wpforms-container .wpforms-form button[type=submit],
					{{PLUS_WRAP}} div.wpforms-container .wpforms-form .wpforms-page-button{width: {{btnMWidth}};}',
				],
			],
		],
		'gBtnAlign' => [
			'type' => 'object',
			'default' => 'left',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gform_footer{ text-align: {{gBtnAlign}};}',
				],
			],
		],
		'btnTypo' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 input.wpcf7-form-control.wpcf7-submit',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form input[type = submit], {{PLUS_WRAP}} .tpgb-caldera-form input[type = button], {{PLUS_WRAP}} .tpgb-caldera-form input[type = reset]',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-forms .everest-forms-part-button,{{PLUS_WRAP}} .tpgb-everest-form .everest-forms button[type=submit],{{PLUS_WRAP}} .tpgb-everest-form .everest-forms input[type=submit]',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input[type="button"],
					{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input[type="submit"]',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .field-wrap input[type=button]',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} div.wpforms-container .wpforms-form button[type=submit],
					{{PLUS_WRAP}} div.wpforms-container .wpforms-form .wpforms-page-button',
				],
			],
		],
		'btnPadding' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 input.wpcf7-form-control.wpcf7-submit{padding: {{btnPadding}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form input[type = submit], {{PLUS_WRAP}} .tpgb-caldera-form input[type = button], {{PLUS_WRAP}} .tpgb-caldera-form input[type = reset]{padding: {{btnPadding}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-forms .everest-forms-part-button,{{PLUS_WRAP}} .tpgb-everest-form .everest-forms button[type=submit],{{PLUS_WRAP}} .tpgb-everest-form .everest-forms input[type=submit]{padding: {{btnPadding}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input[type="button"],
					{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input[type="submit"]{padding: {{btnPadding}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .field-wrap input[type=button]{padding: {{btnPadding}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} div.wpforms-container .wpforms-form button[type=submit],
					{{PLUS_WRAP}} div.wpforms-container .wpforms-form .wpforms-page-button{padding: {{btnPadding}};}',
				],
			],
		],
		'btnMargin' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 input.wpcf7-form-control.wpcf7-submit{margin: {{btnMargin}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form input[type = submit], {{PLUS_WRAP}} .tpgb-caldera-form input[type = button], {{PLUS_WRAP}} .tpgb-caldera-form input[type = reset]{margin: {{btnMargin}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-forms .everest-forms-part-button,{{PLUS_WRAP}} .tpgb-everest-form .everest-forms button[type=submit],{{PLUS_WRAP}} .tpgb-everest-form .everest-forms input[type=submit]{margin: {{btnMargin}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .field-wrap input[type=button]{margin: {{btnMargin}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} div.wpforms-container .wpforms-form button[type=submit],
					{{PLUS_WRAP}} div.wpforms-container .wpforms-form .wpforms-page-button{margin: {{btnMargin}};}',
				],
			],
		],
		'btnNColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 input.wpcf7-form-control.wpcf7-submit{color: {{btnNColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form input[type = submit], {{PLUS_WRAP}} .tpgb-caldera-form input[type = button], {{PLUS_WRAP}} .tpgb-caldera-form input[type = reset]{color: {{btnNColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-forms .everest-forms-part-button,{{PLUS_WRAP}} .tpgb-everest-form .everest-forms button[type=submit],{{PLUS_WRAP}} .tpgb-everest-form .everest-forms input[type=submit]{color: {{btnNColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gform_button.button{color: {{btnNColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .field-wrap input[type=button]{color: {{btnNColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} div.wpforms-container .wpforms-form button[type=submit],
					{{PLUS_WRAP}} div.wpforms-container .wpforms-form .wpforms-page-button{color: {{btnNColor}};}',
				],
			],
		],
		'nextBtnNColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gform_next_button{color: {{nextBtnNColor}};}',
				],
			],
		],
		'prevBtnNColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gform_previous_button{color: {{prevBtnNColor}};}',
				],
			],
		],
		'btnNBG' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 input.wpcf7-form-control.wpcf7-submit',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form input[type = submit], {{PLUS_WRAP}} .tpgb-caldera-form input[type = button], {{PLUS_WRAP}} .tpgb-caldera-form input[type = reset]',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-forms .everest-forms-part-button,{{PLUS_WRAP}} .tpgb-everest-form .everest-forms button[type=submit],{{PLUS_WRAP}} .tpgb-everest-form .everest-forms input[type=submit]',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gform_button.button',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .field-wrap input[type=button]',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} div.wpforms-container .wpforms-form button[type=submit],
					{{PLUS_WRAP}} div.wpforms-container .wpforms-form .wpforms-page-button',
				],
			],
		],
		'nextBtnNBG' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gform_next_button',
				],
			],
		],
		'prevBtnNBG' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gform_previous_button',
				],
			],
		],
		'btnHColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 input.wpcf7-form-control.wpcf7-submit:hover{color: {{btnHColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form input[type = submit]:hover, {{PLUS_WRAP}} .tpgb-caldera-form input[type = button]:hover, {{PLUS_WRAP}} .tpgb-caldera-form input[type = reset]:hover{color: {{btnHColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-forms .everest-forms-part-button:hover,{{PLUS_WRAP}} .tpgb-everest-form .everest-forms button[type=submit]:hover,{{PLUS_WRAP}} .tpgb-everest-form .everest-forms input[type=submit]:hover{color: {{btnHColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gform_button.button:hover{color: {{btnHColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .field-wrap:hover input[type=button]{color: {{btnHColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} div.wpforms-container .wpforms-form button[type=submit]:hover,
					{{PLUS_WRAP}} div.wpforms-container .wpforms-form .wpforms-page-button:hover{color: {{btnHColor}};}',
				],
			],
		],
		'nextBtnHColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_next_button:hover{color: {{nextBtnHColor}};}',
				],
			],
		],
		'prevBtnHColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_previous_button:hover{color: {{prevBtnHColor}};}',
				],
			],
		],
		'btnHBG' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 input.wpcf7-form-control.wpcf7-submit:hover',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form input[type = submit]:hover, {{PLUS_WRAP}} .tpgb-caldera-form input[type = button]:hover, {{PLUS_WRAP}} .tpgb-caldera-form input[type = reset]:hover',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-forms .everest-forms-part-button:hover,{{PLUS_WRAP}} .tpgb-everest-form .everest-forms button[type=submit]:hover,{{PLUS_WRAP}} .tpgb-everest-form .everest-forms input[type=submit]:hover',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gform_button.button:hover',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .field-wrap:hover input[type=button]',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} div.wpforms-container .wpforms-form button[type=submit]:hover,
					{{PLUS_WRAP}} div.wpforms-container .wpforms-form .wpforms-page-button:hover',
				],
			],
		],
		'nextBtnHBG' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gform_next_button:hover',
				],
			],
		],
		'prevBtnHBG' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gform_previous_button:hover',
				],
			],
		],
		'btnNBdr' => [
			'type' => 'object',
			'default' => (object) [
				'openBorder' => 0,	// 0 Or 1
				'type' => 'solid',	// 'solid' OR 'dotted' OR 'dashed' OR 'double'
				'color' => '',	// "#000"
				'width' => (object) [
					'md' => (object)[
						'top' => '',
						'left' => '',
						'bottom' => '',
						'right' => '',
					],
					'sm' => (object)[ ],
					'xs' => (object)[ ],
					"unit" => "px",
				],
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 input.wpcf7-form-control.wpcf7-submit',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form input[type = submit], {{PLUS_WRAP}} .tpgb-caldera-form input[type = button], {{PLUS_WRAP}} .tpgb-caldera-form input[type = reset]',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-forms .everest-forms-part-button,{{PLUS_WRAP}} .tpgb-everest-form .everest-forms button[type=submit],{{PLUS_WRAP}} .tpgb-everest-form .everest-forms input[type=submit]',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input[type="button"],
					{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input[type="submit"]',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .field-wrap input[type=button]',
				],
			],
		],
		'btnHBdr' => [
			'type' => 'object',
			'default' => (object) [
				'openBorder' => 0,	// 0 Or 1
				'type' => 'solid',	// 'solid' OR 'dotted' OR 'dashed' OR 'double'
				'color' => '',	// "#000"
				'width' => (object) [
					'md' => (object)[
						'top' => '',
						'left' => '',
						'bottom' => '',
						'right' => '',
					],
					'sm' => (object)[ ],
					'xs' => (object)[ ],
					"unit" => "",
				],
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 input.wpcf7-form-control.wpcf7-submit:hover',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form input[type = submit]:hover, {{PLUS_WRAP}} .tpgb-caldera-form input[type = button]:hover, {{PLUS_WRAP}} .tpgb-caldera-form input[type = reset]:hover',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-forms .everest-forms-part-button:hover,{{PLUS_WRAP}} .tpgb-everest-form .everest-forms button[type=submit]:hover,{{PLUS_WRAP}} .tpgb-everest-form .everest-forms input[type=submit]:hover',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input[type="button"]:hover,
					{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input[type="submit"]:hover',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .field-wrap:hover input[type=button]',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} div.wpforms-container .wpforms-form button[type=submit]:hover,
					{{PLUS_WRAP}} div.wpforms-container .wpforms-form .wpforms-page-button:hover',
				],
			],
		],
		'btnNBRadius' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 input.wpcf7-form-control.wpcf7-submit{border-radius: {{btnNBRadius}} !important;}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form input[type = submit], {{PLUS_WRAP}} .tpgb-caldera-form input[type = button], {{PLUS_WRAP}} .tpgb-caldera-form input[type = reset]{border-radius: {{btnNBRadius}} !important;}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-forms .everest-forms-part-button,{{PLUS_WRAP}} .tpgb-everest-form .everest-forms button[type=submit],{{PLUS_WRAP}} .tpgb-everest-form .everest-forms input[type=submit]{border-radius: {{btnNBRadius}} !important;}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input[type="button"],
					{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input[type="submit"]{border-radius: {{btnNBRadius}} !important;}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .field-wrap input[type=button]{border-radius: {{btnNBRadius}} !important;}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} div.wpforms-container .wpforms-form button[type=submit],
					{{PLUS_WRAP}} div.wpforms-container .wpforms-form .wpforms-page-button{border-radius: {{btnNBRadius}} !important;}',
				],
			],
		],            
		'btnHBRadius' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 input.wpcf7-form-control.wpcf7-submit:hover{border-radius: {{btnHBRadius}} !important;}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form input[type = submit]:hover, {{PLUS_WRAP}} .tpgb-caldera-form input[type = button]:hover, {{PLUS_WRAP}} .tpgb-caldera-form input[type = reset]:hover{border-radius: {{btnHBRadius}} !important;}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-forms .everest-forms-part-button:hover,{{PLUS_WRAP}} .tpgb-everest-form .everest-forms button[type=submit]:hover,{{PLUS_WRAP}} .tpgb-everest-form .everest-forms input[type=submit]:hover{border-radius: {{btnHBRadius}} !important;}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input[type="button"]:hover,
					{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input[type="submit"]:hover{border-radius: {{btnHBRadius}} !important;}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .field-wrap:hover input[type=button]{border-radius: {{btnHBRadius}} !important;}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} div.wpforms-container .wpforms-form button[type=submit]:hover,
					{{PLUS_WRAP}} div.wpforms-container .wpforms-form .wpforms-page-button:hover{border-radius: {{btnHBRadius}} !important;}',
				],
			],
		],
		'btnNBshadow' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 input.wpcf7-form-control.wpcf7-submit',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form input[type = submit], {{PLUS_WRAP}} .tpgb-caldera-form input[type = button], {{PLUS_WRAP}} .tpgb-caldera-form input[type = reset]',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-forms .everest-forms-part-button,{{PLUS_WRAP}} .tpgb-everest-form .everest-forms button[type=submit],{{PLUS_WRAP}} .tpgb-everest-form .everest-forms input[type=submit]',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input[type="button"],
					{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input[type="submit"]',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .field-wrap input[type=button]',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} div.wpforms-container .wpforms-form button[type=submit],
					{{PLUS_WRAP}} div.wpforms-container .wpforms-form .wpforms-page-button',
				],
			],
		],
		'btnHBshadow' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 input.wpcf7-form-control.wpcf7-submit:hover',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form input[type = submit]:hover, {{PLUS_WRAP}} .tpgb-caldera-form input[type = button]:hover, {{PLUS_WRAP}} .tpgb-caldera-form input[type = reset]:hover',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-forms .everest-forms-part-button:hover,{{PLUS_WRAP}} .tpgb-everest-form .everest-forms button[type=submit]:hover,{{PLUS_WRAP}} .tpgb-everest-form .everest-forms input[type=submit]:hover',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input[type="button"]:hover,
					{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper input[type="submit"]:hover',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .field-wrap:hover input[type=button]',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} div.wpforms-container .wpforms-form button[type=submit],
					{{PLUS_WRAP}} div.wpforms-container .wpforms-form .wpforms-page-button',
				],
			],
		],
		/* Button Field end*/
		
		/* Form Container start*/
		'formPadding' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form .caldera_forms_form{padding: {{formPadding}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-forms{padding: {{formPadding}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper{padding: {{formPadding}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form {padding: {{formPadding}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .wpforms-container {padding: {{formPadding}};}',
				],
			],
		],
		'formMargin' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form .caldera_forms_form{margin: {{formMargin}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-forms{margin: {{formMargin}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper{margin: {{formMargin}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form {margin: {{formMargin}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .wpforms-container {margin: {{formMargin}};}',
				],
			],
		],
		'formNBG' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form .caldera_forms_form',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-forms',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .wpforms-container',
				],
			],
		],
		'formHBG' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form .caldera_forms_form:hover',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-forms:hover',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper:hover',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form:hover',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .wpforms-container:hover',
				],
			],
		],
		'formNBdr' => [
			'type' => 'object',
			'default' => (object) [
				'openBorder' => 0,	// 0 Or 1
				'type' => 'solid',	// 'solid' OR 'dotted' OR 'dashed' OR 'double'
				'color' => '',	// "#000"
				'width' => (object) [
					'md' => (object)[
						'top' => '',
						'left' => '',
						'bottom' => '',
						'right' => '',
					],
					'sm' => (object)[ ],
					'xs' => (object)[ ],
					"unit" => "px",
				],
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form .caldera_forms_form',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-forms',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .wpforms-container',
				],
			],
		],
		'formHBdr' => [
			'type' => 'object',
			'default' => (object) [
				'openBorder' => 0,	// 0 Or 1
				'type' => 'solid',	// 'solid' OR 'dotted' OR 'dashed' OR 'double'
				'color' => '',	// "#000"
				'width' => (object) [
					'md' => (object)[
						'top' => '',
						'left' => '',
						'bottom' => '',
						'right' => '',
					],
					'sm' => (object)[ ],
					'xs' => (object)[ ],
					"unit" => "",
				],
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form .caldera_forms_form:hover',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-forms:hover',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper:hover',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form:hover',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .wpforms-container:hover',
				],
			],
		],
		'formNBRadius' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form .caldera_forms_form{border-radius: {{formNBRadius}} !important;}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-forms{border-radius: {{formNBRadius}} !important;}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper{border-radius: {{formNBRadius}} !important;}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form{border-radius: {{formNBRadius}} !important;}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .wpforms-container{border-radius: {{formNBRadius}} !important;}',
				],
			],
		],            
		'formHBRadius' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form .caldera_forms_form:hover{border-radius: {{formHBRadius}} !important;}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-forms:hover{border-radius: {{formHBRadius}} !important;}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper:hover{border-radius: {{formHBRadius}} !important;}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form:hover{border-radius: {{formHBRadius}} !important;}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .wpforms-container:hover{border-radius: {{formHBRadius}} !important;}',
				],
			],
		],
		'formNBshadow' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form .caldera_forms_form',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-forms',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .wpforms-container',
				],
			],
		],
		'formHBshadow' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form .caldera_forms_form:hover',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-forms:hover',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper:hover',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form:hover',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .wpforms-container:hover',
				],
			],
		],
		/* Form Container end*/
		
		/* Response Message Field start*/
		'responseMsgTypo' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .wpcf7-response-output',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form .caldera-grid .alert.alert-success, {{PLUS_WRAP}} .tpgb-caldera-form .parsley-required',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-forms .everest-forms-notice--success, {{PLUS_WRAP}} .tpgb-everest-form .everest-forms label.evf-error',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_confirmation_wrapper, {{PLUS_WRAP}} .tpgb-gravity-form .gfield_description.validation_message',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .nf-form-wrap .nf-response-msg',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .wpforms-confirmation-container-full,{{PLUS_WRAP}} .wpforms-confirmation-container-full p, {{PLUS_WRAP}} div.wpforms-container .wpforms-form label.wpforms-error',
				],
			],
		],
		'responseMsgPadding' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 .wpcf7-response-output{padding: {{responseMsgPadding}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form .caldera-grid .alert.alert-success, {{PLUS_WRAP}} .tpgb-caldera-form .parsley-required{padding: {{responseMsgPadding}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-forms .everest-forms-notice--success,{{PLUS_WRAP}} .tpgb-everest-form .everest-forms .everest-forms-notice::before,{{PLUS_WRAP}} .tpgb-everest-form .everest-forms label.evf-error{padding: {{responseMsgPadding}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_confirmation_wrapper, {{PLUS_WRAP}} .tpgb-gravity-form .gfield_description.validation_message{padding: {{responseMsgPadding}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .nf-form-wrap .nf-response-msg{padding: {{responseMsgPadding}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .wpforms-confirmation-container-full{padding: {{responseMsgPadding}};}',
				],
			],
		],
		'responseMsgMargin' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 .wpcf7-response-output{margin: {{responseMsgMargin}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form .caldera-grid .alert.alert-success, {{PLUS_WRAP}} .tpgb-caldera-form .parsley-required{margin: {{responseMsgMargin}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-forms .everest-forms-notice--success,{{PLUS_WRAP}} .tpgb-everest-form .everest-forms .everest-forms-notice::before,{{PLUS_WRAP}} .tpgb-everest-form .everest-forms label.evf-error{margin: {{responseMsgMargin}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_confirmation_wrapper, {{PLUS_WRAP}} .tpgb-gravity-form .gfield_description.validation_message{margin: {{responseMsgMargin}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .nf-form-wrap .nf-response-msg{margin: {{responseMsgMargin}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .wpforms-confirmation-container-full{margin: {{responseMsgMargin}};}',
				],
			],
		],
		'responseSuccessColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 .wpcf7-response-output.wpcf7-mail-sent-ok{color: {{responseSuccessColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form .caldera-grid .alert.alert-success{color: {{responseSuccessColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-forms .everest-forms-notice--success{color: {{responseSuccessColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_confirmation_wrapper{color: {{responseSuccessColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .nf-form-wrap .nf-response-msg p{color: {{responseSuccessColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .wpforms-confirmation-container-full,{{PLUS_WRAP}} .wpforms-confirmation-container-full p{color: {{responseSuccessColor}};}',
				],
			],
		],
		'responseSuccessBG' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 .wpcf7-response-output.wpcf7-mail-sent-ok',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form .caldera-grid .alert.alert-success',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-forms .everest-forms-notice--success',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_confirmation_wrapper',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .nf-form-wrap .nf-response-msg',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .wpforms-confirmation-container-full',
				],
			],
		],
		'responseValidateColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 .wpcf7-response-output.wpcf7-validation-errors, {{PLUS_WRAP}} .tpgb-contact-form-7  .wpcf7-response-output.wpcf7-acceptance-missing{color: {{responseValidateColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form .parsley-required, {{PLUS_WRAP}} .tpgb-caldera-form .parsley-type{color: {{responseValidateColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-forms label.evf-error{color: {{responseValidateColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .validation_message, {{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper div.validation_error{color: {{responseValidateColor}};}
					{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper li.gfield_error input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=button]):not([type=image]):not([type=file]), {{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper li.gfield_error textarea{border-color: {{responseValidateColor}};}
					{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper div.validation_error{border-top-color: {{responseValidateColor}}; border-bottom-color: {{responseValidateColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .nf-form-wrap .nf-form-errors,{{PLUS_WRAP}} .tpgb-ninja-form .nf-form-wrap .nf-error-msg.nf-error-field-errors{color: {{responseValidateColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} div.wpforms-container .wpforms-form label.wpforms-error{color: {{responseValidateColor}};}',
				],
			],
		],
		'responseValidateBG' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 .wpcf7-response-output.wpcf7-validation-errors, {{PLUS_WRAP}} .tpgb-contact-form-7  .wpcf7-response-output.wpcf7-acceptance-missing',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form .parsley-required, {{PLUS_WRAP}} .tpgb-caldera-form .parsley-type',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-forms label.evf-error',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .gform_wrapper li.gfield.gfield_error,{{PLUS_WRAP}} .gform_wrapper li.gfield.gfield_error.gfield_contains_required.gfield_creditcard_warning',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .nf-form-wrap .nf-form-errors',
				],
			],
		],
		'responseSuccessBdr' => [
			'type' => 'object',
			'default' => (object) [
				'openBorder' => 0,	// 0 Or 1
				'type' => 'solid',	// 'solid' OR 'dotted' OR 'dashed' OR 'double'
				'color' => '',	// "#000"
				'width' => (object) [
					'md' => (object)[
						'top' => '',
						'left' => '',
						'bottom' => '',
						'right' => '',
					],
					'sm' => (object)[ ],
					'xs' => (object)[ ],
					"unit" => "px",
				],
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 .wpcf7-response-output',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form .caldera-grid .alert.alert-success',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-forms .everest-forms-notice--success',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_confirmation_wrapper',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .nf-form-wrap .nf-response-msg',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .wpforms-confirmation-container-full',
				],
			],
		],
		'responseValidateBdr' => [
			'type' => 'object',
			'default' => (object) [
				'openBorder' => 0,	// 0 Or 1
				'type' => 'solid',	// 'solid' OR 'dotted' OR 'dashed' OR 'double'
				'color' => '',	// "#000"
				'width' => (object) [
					'md' => (object)[
						'top' => '',
						'left' => '',
						'bottom' => '',
						'right' => '',
					],
					'sm' => (object)[ ],
					'xs' => (object)[ ],
					"unit" => "px",
				],
			],
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 .wpcf7-response-output.wpcf7-validation-errors, {{PLUS_WRAP}} .tpgb-contact-form-7  .wpcf7-response-output.wpcf7-acceptance-missing',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form .parsley-required, {{PLUS_WRAP}} .tpgb-caldera-form .parsley-type',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-forms label.evf-error',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .gform_wrapper li.gfield.gfield_error,{{PLUS_WRAP}} .gform_wrapper li.gfield.gfield_error.gfield_contains_required.gfield_creditcard_warning',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .nf-form-wrap .nf-form-errors',
				],
			],
		],          
		'responseSuccessBRadius' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 .wpcf7-response-output.wpcf7-mail-sent-ok {border-radius: {{responseSuccessBRadius}} !important;}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form .caldera-grid .alert.alert-success {border-radius: {{responseSuccessBRadius}} !important;}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-forms .everest-forms-notice--success {border-radius: {{responseSuccessBRadius}} !important;}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_confirmation_wrapper {border-radius: {{responseSuccessBRadius}} !important;}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .nf-form-wrap .nf-response-msg {border-radius: {{responseSuccessBRadius}} !important;}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'wp-form' ]],
					'selector' => '{{PLUS_WRAP}} .wpforms-confirmation-container-full {border-radius: {{responseSuccessBRadius}} !important;}',
				],
			],
		],
		'responseValidateBRadius' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 .wpcf7-response-output.wpcf7-validation-errors, {{PLUS_WRAP}} .tpgb-contact-form-7 .wpcf7-response-output.wpcf7-acceptance-missing{border-radius: {{responseValidateBRadius}} !important;}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form .parsley-required, {{PLUS_WRAP}} .tpgb-caldera-form .parsley-type{border-radius: {{responseValidateBRadius}} !important;}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-forms label.evf-error{border-radius: {{responseValidateBRadius}} !important;}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .gform_wrapper li.gfield.gfield_error,{{PLUS_WRAP}} .gform_wrapper li.gfield.gfield_error.gfield_contains_required.gfield_creditcard_warning{border-radius: {{responseValidateBRadius}} !important;}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .nf-form-wrap .nf-form-errors{border-radius: {{responseValidateBRadius}} !important;}',
				],
			],
		],
		'cntntMWidth' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7{max-width: {{cntntMWidth}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'caldera-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-caldera-form .caldera_forms_form{max-width: {{cntntMWidth}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'everest-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-everest-form .everest-forms{max-width: {{cntntMWidth}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper{max-width: {{cntntMWidth}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form{max-width: {{cntntMWidth}};}',
				],
			],
		],
		'ninjaReqFPadding' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .nf-error-msg.nf-error-required-error,{{PLUS_WRAP}} .tpgb-ninja-form .nf-error-msg.nf-error-field-errors{padding: {{ninjaReqFPadding}};}',
				],
			],
		],
		'reqTextColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 span.wpcf7-form-control-wrap .wpcf7-not-valid-tip{color: {{reqTextColor}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .nf-error-msg.nf-error-required-error,{{PLUS_WRAP}} .tpgb-ninja-form .nf-error-msg.nf-error-field-errors{color: {{reqTextColor}};} {{PLUS_WRAP}} .tpgb-ninja-form .nf-error-msg.nf-error-required-error,{{PLUS_WRAP}} .tpgb-ninja-form .nf-error-msg.nf-error-field-errors{border: 1px solid {{reqTextColor}};}',
				],
			],
		],
		'reqTextBG' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'contact-form-7' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-contact-form-7 span.wpcf7-form-control-wrap .wpcf7-not-valid-tip{background: {{reqTextBG}};} {{PLUS_WRAP}} .tpgb-contact-form-7 span.wpcf7-not-valid-tip:before{border-bottom-color: {{reqTextBG}};}',
				],
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .nf-error-msg.nf-error-required-error{background: {{reqTextBG}};}',
				],
			],
		],
		'reqBdrColor' => [
			'type' => 'string',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'ninja-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-ninja-form .nf-error-msg.nf-error-required-error{border: 1px solid {{reqBdrColor}};}',
				],
			],
		],
		'captchaMargin' => [
			'type' => 'object',
			'default' => '',
			'style' => [
				(object) [
					'condition' => [(object) ['key' => 'formType', 'relation' => '==', 'value' => 'gravity-form' ]],
					'selector' => '{{PLUS_WRAP}} .tpgb-gravity-form .gform_wrapper .gfield .ginput_recaptcha{margin: {{captchaMargin}};}',
				],
			],
		],
		/* Response Message Field end*/
	);
	$attributesOptions = array_merge($attributesOptions,$globalBgOption,$globalpositioningOption);
	
	register_block_type( 'tpgb/tp-external-form-styler', array(
		'attributes' => $attributesOptions,
		'editor_script' => 'tpgb-block-editor-js',
		'editor_style'  => 'tpgb-block-editor-css',
        'render_callback' => 'tpgb_external_form_styler_render_callback'
    ) );
}
add_action( 'init', 'tpgb_external_form_styler');