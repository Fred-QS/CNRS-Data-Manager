<?php if ($previewTemplate === 'POSTER'): ?>

    <div class="cnrs-data-manager-front-blog-item">
        <article id="cnrs-data-manager-post-<?php echo get_the_ID() ?>" class="cnrs-data-manager-front-article-wrapper category-<?php echo $slug ?>">
            <div class="cnrs-data-manager-front-article-image">
                <?php $image_url = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full'); ?>
                <?php $image = strlen($image_url[0]) > 0 ? $image_url[0] : getDefaultImageUrl(true); ?>
                <a href="<?php echo the_permalink() ?>" class="entry-featured-image-url" style="background-image: url(<?php echo esc_url( $image ) ?>);">
                    <span class="cnrs-data-manager-front-article-image-overlay" data-icon=""></span>
                </a>
            </div>
            <h2 class="cnrs-data-manager-front-article-title">
                <?php $acronym = get_post_meta(get_the_ID(), 'cnrs_project_acronym', true) ?>
                <a href="<?php echo the_permalink() ?>"><?php echo strlen($acronym) === 0 ? the_title() : $acronym ?></a>
            </h2>
            <?php $posttags = get_the_tags(); if ($isCandidating === true && $posttags): ?>
                <?php foreach($posttags as $tag): ?>
                    <p class="cnrs-dm-front-tags cnrs-dm-front-tag-<?php echo $tag->slug ?>"><?php echo $tag->name ?></p>
                <?php endforeach; ?>
            <?php endif; ?>
        </article>
    </div>

<?php elseif ($previewTemplate === 'CARD'): ?>

    <div class="cnrs-data-manager-front-blog-item recruter">
        <article id="cnrs-data-manager-post-<?php echo get_the_ID() ?>" class="cnrs-data-manager-front-article-wrapper category-<?php echo $slug ?>">
            <h2 class="cnrs-data-manager-front-article-title">
                <span><?php the_title(); ?></span>
            </h2>
            <div class="cnrs-data-manager-front-article-content">
                <?php echo str_replace("\n", '<span class="clearfix"></span>', get_the_content()); ?>
                <?php $posttags = get_the_tags(); if ($isCandidating === true && $posttags): ?>
                    <?php foreach($posttags as $tag): ?>
                        <p class="cnrs-dm-front-tags cnrs-dm-front-tag-<?php echo $tag->slug ?>"><?php echo $tag->name ?></p>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </article>
    </div>

<?php elseif ($previewTemplate === 'THUMBNAIL'): ?>

    <div class="cnrs-data-manager-front-blog-item recruter">
        <article id="cnrs-data-manager-post-<?php echo get_the_ID() ?>" class="cnrs-data-manager-front-article-wrapper category-<?php echo $slug ?>">
            <h2 class="cnrs-data-manager-front-article-title">
                <span><?php the_title(); ?></span>
            </h2>
            <div class="cnrs-data-manager-front-article-content">
                <?php echo str_replace("\n", '<span class="clearfix"></span>', get_the_content()); ?>
                <?php $posttags = get_the_tags(); if ($isCandidating === true && $posttags): ?>
                    <?php foreach($posttags as $tag): ?>
                        <p class="cnrs-dm-front-tags cnrs-dm-front-tag-<?php echo $tag->slug ?>"><?php echo $tag->name ?></p>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </article>
    </div>

<?php endif; ?>
