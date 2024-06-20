<?php if ($previewTemplate === 'poster'): ?>

    <div class="cnrs-data-manager-front-blog-item">
        <article id="cnrs-data-manager-post-<?php echo get_the_ID() ?>" class="cnrs-data-manager-front-article-wrapper category-<?php echo $slug ?>">
            <div class="cnrs-data-manager-front-article-image">
                <?php $image_url = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full'); ?>
                <?php $image = strlen($image_url[0]) > 0 ? $image_url[0] : '/wp-content/plugins/cnrs-data-manager/assets/media/default-project-image.jpg'; ?>
                <a href="<?php echo the_permalink() ?>" class="entry-featured-image-url" style="background-image: url(<?php echo esc_url( $image ) ?>);">
                    <span class="cnrs-data-manager-front-article-image-overlay" data-icon=""></span>
                </a>
            </div>
            <h2 class="cnrs-data-manager-front-article-title">
                <a href="<?php echo the_permalink() ?>"><?php the_title(); ?></a>
            </h2>
        </article>
    </div>

<?php elseif ($previewTemplate === 'card'): ?>

    <div class="cnrs-data-manager-front-blog-item recruter">
        <article id="cnrs-data-manager-post-<?php echo get_the_ID() ?>" class="cnrs-data-manager-front-article-wrapper category-<?php echo $slug ?>">
            <h2 class="cnrs-data-manager-front-article-title">
                <span><?php the_title(); ?></span>
            </h2>
            <div class="cnrs-data-manager-front-article-content">
                <?php echo get_the_content(); ?>
            </div>
        </article>
    </div>

<?php endif; ?>
