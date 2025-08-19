<?php
/* Block : Form Block
 * @since : 2.0.0
 */
defined( 'ABSPATH' ) || exit;


function nxt_form_block_callback($attr, $content) {
	$pattern = '/\btpgb-wrap-/';
    $output='';
    if (preg_match($pattern, $content)) {
        if( class_exists('Tpgb_Blocks_Global_Options') ){
            $global_blocks = Tpgb_Blocks_Global_Options::get_instance();
            $content = $global_blocks::block_row_conditional_render($attr,$content);
        }
       return $content;
    }
    $block_id = (isset($attr['block_id']) && !empty($attr['block_id'])) ? $attr['block_id'] : '';
    $layoutType = (isset($attr['layoutType']) && !empty($attr['layoutType'])) ? $attr['layoutType'] : '';
    $actionOption = (isset($attr['actionOption']) && !empty($attr['actionOption'])) ? $attr['actionOption'] : '';
    $subject1 = (isset($attr['subject1']) && !empty($attr['subject1'])) ? $attr['subject1'] : '';
    $emailTo1 = (isset($attr['emailTo1']) && !empty($attr['emailTo1'])) ? $attr['emailTo1'] : '';
    $selectedLayout = (isset($attr['selectedLayout']) && !empty($attr['selectedLayout'])) ? $attr['selectedLayout'] : '';
    $autoRespMsg = (isset($attr['autoRespMsg']) && !empty($attr['autoRespMsg'])) ? $attr['autoRespMsg'] : '';
    $ccEmail1 = (isset($attr['ccEmail1']) && !empty($attr['ccEmail1'])) ? $attr['ccEmail1'] : '';
    $bccEmail1 = (isset($attr['bccEmail1']) && !empty($attr['bccEmail1'])) ? $attr['bccEmail1'] : '';
    $emailHdg = (isset($attr['emailHdg']) && !empty($attr['emailHdg'])) ? $attr['emailHdg'] : '';
    $frmEmail = (isset($attr['frmEmail']) && !empty($attr['frmEmail'])) ? $attr['frmEmail'] : '';
    $frmNme = (isset($attr['frmNme']) && !empty($attr['frmNme'])) ? $attr['frmNme'] : '';
    $replyTo = (isset($attr['replyTo']) && !empty($attr['replyTo'])) ? $attr['replyTo'] : '';    
    $redirect = (isset($attr['redirect']) && !empty($attr['redirect']) ? $attr['redirect'] : '');
    $actionOption = (isset($attr['actionOption']) && !empty($attr['actionOption']) ? $attr['actionOption'] : '');
    $metaDataOpt = (isset($attr['metaDataOpt']) && !empty($attr['metaDataOpt']) ? $attr['metaDataOpt'] : '');
    $formId = (isset($attr['formId']) && !empty($attr['formId']) ? $attr['formId'] : '');
    $failMsg = (isset($attr['failMsg']) && !empty($attr['failMsg']) ? $attr['failMsg'] : '');
    $valErrMsg = (isset($attr['valErrMsg']) && !empty($attr['valErrMsg']) ? $attr['valErrMsg'] : '');

    $blockClass = Tp_Blocks_Helper::block_wrapper_classes( $attr );

    $actionOptionString = "";  

    if (is_string($actionOption)) {
        $actionOptionString = $actionOption;  
    } else {
        $actionOptionString = json_encode($actionOption);  
    }
    $formDataAttributes =[
      "emailTo1"=> $emailTo1,
      "subject1"=> $subject1,
      "actionOption"=> 'email',
      'ccEmail1'=>$ccEmail1,
      'bccEmail1'=>$bccEmail1,
      'emailHdg'=>$emailHdg,
      'frmEmail'=>$frmEmail,
      'frmNme'=>$frmNme,
      'replyTo'=>$replyTo,
      'block_id'=>$block_id,
      'metaDataOpt'=>$metaDataOpt,
      'failMsg'=>$failMsg,
      'valErrMsg'=>$valErrMsg
    ];
    $filteredFormDataAttributes = array_filter($formDataAttributes, function ($value) {
        return !empty($value);
    });

    $encryptedFormData = Tp_Blocks_Helper::tpgb_simple_decrypt(json_encode($filteredFormDataAttributes),'ey');
    
    $redirectUrl = is_array($redirect) && isset($redirect['url']) ? $redirect['url'] : '';
    $redirectTarget = is_array($redirect) && isset($redirect['target']) ? $redirect['target'] : '';
    $redirectNofollow = is_array($redirect) && isset($redirect['nofollow']) ? $redirect['nofollow'] : '';

    $dataRedirect = '';
    if (!empty($redirectUrl)) {
        $dataRedirect .= 'data-redirect="' . esc_url($redirectUrl) . '"';
    }
    if ($redirectTarget === 1 ) {
        $dataRedirect .= ' data-link-blank="1"';
    }
    if ($redirectNofollow === 1) {
        $dataRedirect .= ' data-link-nofollow="1"';
    }
    $dataAction = 'data-actionOption="' . esc_attr($actionOptionString) . '"';

    $output = '<div class="tp-form-block ' .esc_attr($layoutType). ' tpgb-relative-block tpgb-block-' .esc_attr($block_id).' '.esc_attr($blockClass).'" data-block-id="' .esc_attr($block_id) . '">';
        $output .= '<form id="'.esc_attr($formId).'" class="nxt-form" data-formdata='.$encryptedFormData.' '.$dataRedirect.' '.$dataAction.'>';
            $output .= $content;
        $output .=  ' </form>';
        $output.='<span class="nxt-success-message" data-success-message="'.esc_attr($autoRespMsg).'"></span>';
    $output .= '</div>';

    return Tpgb_Blocks_Global_Options::block_Wrap_Render($attr, $output);
}

function nxt_form_block_render() {
    $block_data = Tpgb_Blocks_Global_Options::merge_options_json(__DIR__, 'nxt_form_block_callback');
    $block_data['render_callback'] = 'nxt_form_block_callback';
	register_block_type( $block_data['name'], $block_data );
}
add_action( 'init', 'nxt_form_block_render' );
