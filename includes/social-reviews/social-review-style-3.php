<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
	<div class="grid-item <?php echo esc_attr($desktop_class).esc_attr($tablet_class).esc_attr($mobile_class)." ".esc_attr($RKey)." ".esc_attr($ReviewClass); ?>">
    <?php 
        include TPGB_INCLUDES_URL. "social-reviews/social-review-ob-style.php";
		echo '<div class="review-s3-wrap">';
			echo '<div class="tpgb-review tpgb-trans-linear '.esc_attr($ErrClass).'">';
				echo '<div class="review-top-area">'; 
					echo wp_kses_post($Star_HTML);
					if(empty($disSocialIcon)){  
                        echo wp_kses_post($Logo_HTML);
                    }
				echo '</div>'; 
				echo wp_kses_post($Description_HTML); 
			echo '</div>'; 
			echo '<div class="tpgb-sr-header tpgb-trans-linear">';
				if(empty($disProfileIcon)){
					echo wp_kses_post($Profile_HTML);
				}
				echo '<div class="tpgb-sr-separator">';
					echo wp_kses_post($UserName_HTML);
					echo wp_kses_post($Time_HTML);
				echo '</div>';
			echo '</div>';
		echo '</div>';
    ?>
</div>