<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div class="tpgb-sf-feed tpgb-trans-linear tpgb-d-flex tpgb-flex-row">
	<?php 
		$imghideclass='';
		if(empty($ImageURL)){
			$imghideclass = 'tpgb-soc-image-not-found';
		}
    
        echo '<div class="tpgb-sf-contant '.esc_attr($imghideclass).'">';
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
		
	if(!empty($ImageURL)){ ?>

		 <div class="tpgb-sf-contant-img" style="background-image: url('<?php echo esc_url($ImageURL); ?>');">
            <?php 
                echo wp_kses_post($Iconlogo);
			    include TPGB_INCLUDES_URL."social-feed/fancybox-feed.php"; 
            ?>
		</div>
	<?php }
    ?>
</div>