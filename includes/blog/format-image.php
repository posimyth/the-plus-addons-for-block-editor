<?php 
$image_url = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) );

if(! empty( $image_url )){
	if(!empty($layout) && $layout=='grid'){

		$featured_image=get_the_post_thumbnail_url(get_the_ID(),'tp-image-grid');
		$featured_image='<img src="'.esc_url($featured_image).'" alt="'.esc_attr(get_the_title()).'" class="tpgb-d-block tpgb-post-img tpgb-dynamic-tran">';

	}else if(!empty($layout) && $layout=='masonry'){

		$featured_image=get_the_post_thumbnail_url(get_the_ID(),'full');
		$featured_image='<img src="'.esc_url($featured_image).'" alt="'.esc_attr(get_the_title()).'" class="tpgb-d-block tpgb-post-img tpgb-dynamic-tran">';

	}else if(!empty($layout) && $layout=='carousel'){

		$featured_image_type='tp-image-grid';
		$featured_image=get_the_post_thumbnail_url(get_the_ID(),$featured_image_type);
		$featured_image='<img src="'.esc_url($featured_image).'" alt="'.esc_attr(get_the_title()).'" class="tpgb-d-block tpgb-post-img tpgb-dynamic-tran">';

	}else{

		$featured_image=get_the_post_thumbnail_url(get_the_ID(),'full');
		$featured_image='<img src="'.esc_url($featured_image).'" alt="'.esc_attr(get_the_title()).'" class="tpgb-d-block tpgb-post-img tpgb-dynamic-tran">';

	}
}else{
	$featured_image = Tp_Blocks_Helper::get_default_thumb();
	$featured_image = $featured_image='<img src="'.esc_url($featured_image).'" alt="'.esc_attr(get_the_title()).'"  class="tpgb-d-block tpgb-post-img tpgb-dynamic-tran">';
}
?>
<div class="tpgb-post-featured-img tpgb-dynamic-tran <?php echo esc_attr($imageHoverStyle); ?>">
	<a href="<?php echo esc_url(get_the_permalink()); ?>">
		<?php echo $featured_image; ?>
	</a>
</div>