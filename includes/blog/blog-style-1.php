<?php

$bg_attr = '';
if (!empty($layout) && $layout == 'metro') {
    $thumbnail_id = get_post_thumbnail_id(get_the_ID()); 
    $featured_image = wp_get_attachment_url($thumbnail_id);

    if (!empty($featured_image)) {
        $bg_attr = 'style="background: url('.esc_url($featured_image).'); background-size: cover; background-repeat: no-repeat;"';
    }
}
?>
<div class="dynamic-list-content tpgb-dynamic-tran">

    <?php if ($layout == 'metro') { ?>
        <div class="tpgb-post-featured-img tpgb-dynamic-tran <?php echo esc_attr($imageHoverStyle); ?>">
            <a href="<?php echo esc_url(get_the_permalink()); ?>" aria-label="<?php echo esc_attr(get_the_title()); ?>">
                <?php echo '<div class="tpgb-blog-image-metro" '. $bg_attr .' ></div>'; ?>
            </a>
        </div>
    <?php } ?>

    <?php include TPGB_INCLUDES_URL . 'blog/format-image.php'; ?>

    <div class="tpgb-content-bottom">
        <?php if (!empty($showPostMeta) && $showPostMeta == 'yes') { ?>
            <?php include TPGB_INCLUDES_URL . 'blog/'. sanitize_file_name('post-meta-'.$postMetaStyle.'.php'); ?>
        <?php } ?>

        <?php if (!empty($ShowTitle) && $ShowTitle == 'yes') {
            include TPGB_INCLUDES_URL . 'blog/post-title.php'; 
        } ?>

        <div class="tpgb-post-hover-content">
            <?php if (!empty($showExcerpt) && $showExcerpt == 'yes' && get_the_excerpt()) {
                include TPGB_INCLUDES_URL . 'blog/get-excerpt.php';
            } ?>
        </div>
    </div>
</div>