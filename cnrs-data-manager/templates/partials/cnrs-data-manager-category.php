<!-- Start custom category page -->
<?php get_header(); ?>

<section id="primary" class="site-content">
    <div id="content" role="main">

        <?php
        // Check if there are any posts to display
        if (have_posts()): ?>
            <header class="archive-header">
                <h1 class="archive-title">Category: <?php single_cat_title(); ?></h1>

                <?php
                // Display optional category description
                if (category_description()): ?>
                    <div class="archive-meta"><?php echo category_description(); ?></div>
                <?php endif; ?>
            </header>

            <?php

            // Filters
            echo do_shortcode('[cnrs-data-manager type="filters"]');

            // The Loop
            while (have_posts()): the_post(); ?>
                <article>
                    <?php $image_url = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'medium'); ?>
                    <a href="<?= the_permalink() ?>" style="background-image: url(<?= esc_url( $image_url[0] ) ?>);"></a>
                    <h2>
                        <a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">
                            <?php the_title(); ?>
                        </a>
                    </h2>
                    <small><?php the_time('F jS, Y') ?> by <?php the_author_posts_link() ?></small>
                </article>
            <?php endwhile;

            // Pagination
            echo do_shortcode('[cnrs-data-manager type="pagination"]');

        else: ?>
            <p>Sorry, no posts matched your criteria.</p>
        <?php endif;
        ?>

    </div>
</section>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
<!-- End custom category page -->