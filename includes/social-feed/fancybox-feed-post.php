<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

    if($selectFeed == 'Facebook'){
        if($EmbedURL == 'Alb' && !empty($FbAlbum)){ 
            $ij = 0;
            $albumSize = (is_array($videoURL) ? count($videoURL) : 1);
            $uniqId = uniqid('f-');
            if( $albumSize > 1 ){
                foreach ( $videoURL as $index => $fdata ){
                    $AImg = (!empty($fdata['images'])) ? $fdata['images'][0]['source'] : []; 
                    if( $ij == 0 ){
                        echo '<a href="'.esc_url($AImg).'" data-fancybox="'.esc_attr($uniqId).'" aria-label="'.esc_attr__('Facebook Post','the-plus-addons-for-block-editor').'">
                                <img class="reference-thumb tpgb-post-thumb" src="'.esc_url($ImageURL).'" alt="'.esc_attr__('Facebook Image','the-plus-addons-for-block-editor').'"/>
                            </a>';
                    }else{ 
                        echo '<a href="'.esc_url($AImg).'" data-fancybox="'.esc_attr($uniqId).'" aria-label="'.esc_attr__('Facebook Post','the-plus-addons-for-block-editor').'">
                                <img class="hidden-image" src="'.esc_url($AImg).'" alt="'.esc_attr__('Facebook Image','the-plus-addons-for-block-editor').'"/>
                            </a>';
                    }
                $ij++;
                }
            } else {
                echo '<img class="tpgb-post-thumb" src="'.esc_url($ImageURL).'" alt="'.esc_attr__('Facebook Image','the-plus-addons-for-block-editor').'"/>';
            }
        }else if( $EmbedType == 'video' && empty($FbAlbum) ){
            echo '<div class="tpgb-fcb-container">
                    <iframe class="responsive-iframe" src="'.esc_url($videoURL).'" title="'.esc_attr__('Social Feed','the-plus-addons-for-block-editor').'"></iframe>
                </div>';
        }else {
            echo '<img class="tpgb-post-thumb" src="'.esc_url($ImageURL).'" alt="'.esc_attr__('Facebook Image','the-plus-addons-for-block-editor').'"/>';
        }
    }else if(!empty($ImageURL)){
        echo '<img class="tpgb-fcb-thumb" src="'.esc_url($ImageURL).'" alt="'.esc_attr__('Social Media Image','the-plus-addons-for-block-editor').'"/>';
    }

?>