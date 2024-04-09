<?php get_header(); ?>
    <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">
            <div id="cnrs-dm-mission-master-wrapper">
                <?php echo do_shortcode('[cnrs-data-manager type="form"]') ?>
            </div>
        </main><!-- .site-main -->
        <?php if (is_active_sidebar('content-bottom')): get_sidebar( 'content-bottom' ); endif; ?>
    </div><!-- .content-area -->
<?php if (is_active_sidebar('right-side')): get_sidebar('right-side'); endif; ?>
<?php get_footer(); ?>