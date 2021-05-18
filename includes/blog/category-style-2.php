<div class="tpgb-post-category cat-style-2">	
	<?php 
	$categories = get_the_terms(get_the_ID() , $taxonomySlug ); 
	if(!empty($categories)) {
		foreach ( $categories as $category ) {
			echo '<a href="'.esc_url(get_category_link($category->term_id)).'">'.esc_attr($category->name).'</a>';
		}
	}
	?>
</div>

