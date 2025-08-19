<?php 
// condition uncommented as it was making issue for metro style
	$bg_attr = '';
	if(!empty($layout) && $layout=='metro'){
		$thumbnail_id = get_post_thumbnail_id(get_the_ID()); 
		$featured_image = wp_get_attachment_url($thumbnail_id);

		if(!empty($featured_image)){
			$bg_attr = 'style="background:url('.$featured_image.');"';
		}
	}	
	// end
?>

<div class="dynamic-list-content tpgb-dynamic-tran">
	
	<?php if($layout != 'metro') { ?> 
	<div class="post-content-image">
		<?php include TPGB_INCLUDES_URL. 'blog/format-image.php'; ?>
		<?php if($showPostCategory=='yes' && $styleLayout=='style-2'){ ?>
			<?php include TPGB_INCLUDES_URL. 'blog/'.sanitize_file_name('category-'.$postCategoryStyle.'.php'); ?>
		<?php } ?>
	</div>
	<?php } ?>
	
	<div class="tpgb-content-bottom <?php echo ($style2Alignment=='center') ? 'text-center' : 'text-left'; ?>">
		<?php if($showPostCategory=='yes' && $styleLayout=='style-1' || ( ($styleLayout=='style-2' && $layout == 'metro')) ){ ?>
			<div class="tpgb-post-metro-category-top <?php echo ($style2Alignment=='center') ? 'text-center' : 'text-left'; ?>">
			<?php include TPGB_INCLUDES_URL. 'blog/'.sanitize_file_name('category-'.$postCategoryStyle.'.php'); ?>
			</div>
		<?php } ?>
		<?php if($layout == 'metro'){?>
			<div class="tpgb-post-metro-content">
		<?php } ?>
		<?php
		if(!empty($ShowTitle) && $ShowTitle=='yes'){
			include TPGB_INCLUDES_URL. 'blog/post-title.php';
		}
		?>
		<div class="tpgb-post-hover-content">
			<?php
				if(!empty($showExcerpt) && $showExcerpt=='yes' && get_the_excerpt()){
					include TPGB_INCLUDES_URL. 'blog/get-excerpt.php';
				}
				
				if(!empty($showPostMeta) && $showPostMeta=='yes'){
					include TPGB_INCLUDES_URL. 'blog/'.sanitize_file_name('post-meta-'.$postMetaStyle.'.php');
				}
			?>
		</div>
		<?php if($layout == 'metro'){?>
			</div>
		<?php } ?>
	</div>
	<?php if( $layout == 'metro' ) { ?>
		<div class="tpgb-post-featured-img tpgb-dynamic-tran <?php echo esc_attr($imageHoverStyle); ?>">
			<a href="<?php echo esc_url(get_the_permalink()); ?>" aria-label="<?php echo esc_attr(get_the_title()); ?>" <?php echo $newTabPostAttr; ?>>
				<?php echo '<div class="tpgb-blog-image-metro"  '.$bg_attr.' ></div>'; ?>
			</a>
		</div>
	<?php } ?>
	<?php 
		if($postListing == 'searchList' || $postListing =='search_list') {
			include TPGB_INCLUDES_URL. 'blog/blog-skeleton.php';
		} 
	?>
</div>