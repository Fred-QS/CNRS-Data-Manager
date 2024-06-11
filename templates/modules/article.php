<?php if (!in_array($name,
    [
        'Offres d\'emploi',
        'Stages',
        'Sujets de Thèse',
        'Recrutement',
        'Jobs',
        'Internships',
        'Thesis Topics',
        'Recruitment'
    ], 
    true)
): ?>

    <div class="column size-1of3">
        <article id="post-<?php echo get_the_ID() ?>" class="et_pb_post clearfix et_pb_has_overlay et_pb_blog_item_0_0 post-1070 post type-post status-publish format-standard has-post-thumbnail hentry category-<?php echo $slug ?>">
            <div class="et_pb_image_container">
                <?php $image_url = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full'); ?>
                <?php $image = strlen($image_url[0]) > 0 ? $image_url[0] : '/wp-content/plugins/cnrs-data-manager/assets/media/default-project-image.jpg'; ?>
                <a href="<?php echo the_permalink() ?>" class="entry-featured-image-url" style="background-image: url(<?php echo esc_url( $image ) ?>);">
                    <span class="et_overlay et_pb_inline_icon" data-icon=""></span>
                </a>
            </div>
            <h2 class="entry-title">
                <a href="<?php echo the_permalink() ?>"><?php the_title(); ?></a>
            </h2>
        </article>
    </div>

<?php else: ?>

    <div class="column size-1of3 recruter">
        <article id="post-<?php echo get_the_ID() ?>" class="et_pb_post clearfix et_pb_no_thumb et_pb_blog_item_0_0 post-567 post type-post status-publish format-standard hentry category-<?php echo $slug ?>">
            <h2 class="entry-title">
                <span><?php the_title(); ?></span>
            </h2>
            <div class="post-content">
                <div class="post-content-inner et_pb_blog_show_content">
                    <?php echo get_the_content(); ?>
                </div>
            </div>
        </article>
    </div>

<?php endif; ?>
