<div class="column size-1of3">
    <article id="post-<?php echo get_the_ID() ?>" class="et_pb_post clearfix et_pb_blog_item_0_0 post-1649 project type-project status-publish has-post-thumbnail hentry">

        <div class="et_pb_image_container">
            <?php $image_url = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full'); ?>
            <?php $image = strlen($image_url[0]) > 0 ? $image_url[0] : '/wp-content/plugins/cnrs-data-manager/assets/media/default-project-image.jpg'; ?>
            <a href="<?php echo the_permalink() ?>" class="entry-featured-image-url cnrs-dm-front-project-image" style="background-image: url(<?php echo $image ?>);">
            </a>
        </div>
        <h2 class="entry-title">
            <a href="<?php echo the_permalink() ?>"><?php the_title(); ?></a>
        </h2>

        <div class="post-content">
            <div class="post-content-inner">
                <p>Mon second projet Interdum et malesuada fames ac ante...</p>
            </div>
            <a href="<?php echo the_permalink() ?>" class="more-link"><?php echo __('Read more', 'cnrs-data-manager') ?></a>
        </div>
    </article>
</div>
