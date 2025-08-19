<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div class="grid-item <?php echo esc_attr($desktop_class).esc_attr($tablet_class).esc_attr($mobile_class)." ".esc_attr($RKey)." ".esc_attr($ReviewClass); ?>">
    <?php 
        include TPGB_INCLUDES_URL. "social-reviews/social-review-ob-style.php"; 
    ?>
    <div class="tpgb-review tpgb-trans-linear <?php echo esc_attr($ErrClass); ?>">
        <?php 
            echo '<div class="tpgb-sr-header tpgb-trans-linear">';
                if(empty($disProfileIcon)){
                    echo wp_kses_post($Profile_HTML);
                }
				echo '<div class="header-inner-content">';
					echo wp_kses_post($UserName_HTML);
					echo wp_kses_post($Star_HTML);
				echo '</div>';
            echo '</div>';
            echo wp_kses_post($Description_HTML); 
        ?>

        <div class="tpgb-sr-bottom tpgb-trans-linear">
			<div class="bottom-left-content">
				<?php 
                    if(empty($disSocialIcon)){  
                        echo wp_kses_post($Logo_HTML);
                    }
                ?>
				<div class="tpgb-sr-logotext tpgb-trans-linear">
					<span class="tpgb-newline tpgb-trans-linear"><?php echo esc_html__("Posted On ",'the-plus-addons-for-block-editor'); ?></span>
					<span class="tpgb-newline tpgb-trans-linear"><?php echo esc_html($PlatformName); ?></span>
				</div>
			</div>
            <?php echo wp_kses_post($Time_HTML); ?>
        </div>
    </div>
</div>