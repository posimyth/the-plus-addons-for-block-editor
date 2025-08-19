<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
    if(!empty($videoURL)) {
        $Iconlogo = '<div class="tpgb-sf-logo">
            <a href="'. ( ( is_array($videoURL) && isset($videoURL[0]['link']) && !empty($videoURL[0]['link']) ) ? esc_url($videoURL[0]['link']) : esc_url($videoURL) ) .'" 
               class="tpgb-sf-logo-link" target="_blank" rel="noopener noreferrer" aria-label="'.esc_attr__('Post URL','the-plus-addons-for-block-editor').'">
                <i class="'.esc_attr($socialIcon).'"></i>
            </a>
        </div>';
    }

    if( ( ($style == 'style-1' || $style == 'style-2'  ) && $selectFeed == 'Facebook' && $PopupOption == 'GoWebsite' && !empty($ImageURL)) ){
        echo '<a href="'. ( ( is_array($videoURL) && isset($videoURL[0]['link']) && !empty($videoURL[0]['link']) ) ? esc_url($videoURL[0]['link']) : esc_url($videoURL) ) .'" class="tpgb-sf-logo-link" target="_blank" rel="noopener noreferrer" aria-label="'.esc_attr__('Post URL','the-plus-addons-for-block-editor').'"> <img class="tpgb-post-thumb" src="'.esc_url($ImageURL).'"> </a>';
    }

	ob_start();
    	echo '<div class="tpgb-sf-header">';
    		if(!empty($UserImage)){
    			echo '<div class="tpgb-sf-profile"><img class="tpgb-sf-logo" src="'.esc_url($UserImage).'" alt="'.esc_attr__('User Profile','the-plus-addons-for-block-editor').'"/></div>';
    		} 
    		echo '<div class="tpgb-sf-usercontact">';
    			if(!empty($UserName)){
    				echo '<div class="tpgb-sf-username">
							<a href="'.esc_url($UserLink).'" target="_blank" rel="noopener noreferrer" aria-label="'.esc_attr($UserName).'">'.wp_kses_post($UserName).'</a></div>';
    			} 
    			if(!empty($CreatedTime)){
    				echo '<div class="tpgb-sf-time">
							<a href="'. ( ( is_array($videoURL) && isset($videoURL[0]['link']) && !empty($videoURL[0]['link']) ) ? esc_url($videoURL[0]['link']) : esc_url($videoURL) ) .'"  target="_blank" rel="noopener noreferrer" alt="'.esc_attr__('Post URL','the-plus-addons-for-block-editor').'">'.wp_kses_post($CreatedTime).'</a></div>';
    			}   
    		echo '</div>';
    		if( isset($Iconlogo) && (!empty($socialIcon) && $style != "style-3") || (empty($ImageURL) && $style == "style-3") ){
    			echo wp_kses_post($Iconlogo);
    		}
    	echo '</div>';
    $Header_html = ob_get_clean();

	// Title
	$Massage_html='';
	if(!empty($ShowTitle)){
		ob_start();
			echo '<div class="tpgb-title">'.wp_kses_post($Massage).'</div>';
		$Massage_html = ob_get_clean();
	}