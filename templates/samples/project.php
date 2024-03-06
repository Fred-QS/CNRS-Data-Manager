<!-- Start custom projects page -->
<?php get_header(); ?>

<section id="primary" class="site-content">
    <div id="content" role="main">

        <!-- Mandatory if silent pagination is activated -->
        <div id="cnrs-dm-front-loader-icon" style="display: none">
            <?php include_once(CNRS_DATA_MANAGER_DEPORTED_SVG_PATH . '/loader.svg') ?>
        </div>

        <?php
        // Check if there are any posts to display
        if (have_posts()): ?>
            <header class="archive-header">
                <h1 class="archive-title">Projects</h1>
            </header>

            <?php

            // Filters
            echo do_shortcode('[cnrs-data-manager type="filters"]'); ?>

            <!-- <div> width class="cnrs-dm-front-silent-container" attribute to contain articles is mandatory if silent pagination is activated -->
            <div class="cnrs-dm-front-silent-container">

                <?php // The Loop
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
                <?php endwhile; ?>

            </div>

        <?php else: ?>
            <p><?= __('Sorry, no posts matched your criteria.', 'cnrs-data-manager') ?></p>
        <?php endif;

        // Pagination
        echo do_shortcode('[cnrs-data-manager type="pagination"]'); ?>

    </div>
</section>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
<!-- End custom project page -->