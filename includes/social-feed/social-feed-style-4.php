<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div class="tpgb-sf-feed tpgb-trans-linear">
    <div class="tpgb-sf-contant-img tpgb-trans-easeinout-before" style="background-image: url('<?php echo esc_url($ImageURL); ?>');">
        <?php
            echo '<div class="tpgb-sf-contant tpgb-relative-block tpgb-trans-easeinout">';
            include TPGB_INCLUDES_URL."social-feed/social-feed-ob-style.php";

            if(!empty($Massage)){
                echo wp_kses_post($Massage_html);
            }
            if(!empty($Description)){ 
                include TPGB_INCLUDES_URL."social-feed/feed-Description.php"; 
            }
                echo wp_kses_post($Header_html);
                include TPGB_INCLUDES_URL."social-feed/feed-footer.php"; 
            echo '</div>';

            include TPGB_INCLUDES_URL."social-feed/fancybox-feed.php"; 
        ?>
    </div>
</div>