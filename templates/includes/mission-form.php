<?php

$url = get_site_url() . $_SERVER['REQUEST_URI'];
$type = 'form';

if (str_starts_with($url, get_site_url() . '/cnrs-umr/mission-form-revision/manager')) {
    $type = 'revision-manager';
} else if (str_starts_with($url, get_site_url() . '/cnrs-umr/mission-form-revision/agent')) {
    $type = 'revision-agent';
}

get_header(); ?>
<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
        <div id="cnrs-dm-mission-master-wrapper">
            <?php echo do_shortcode('[cnrs-data-manager type="' . $type . '"]') ?>
        </div>
    </main><!-- .site-main -->
    <?php if (is_active_sidebar('content-bottom')): get_sidebar( 'content-bottom' ); endif; ?>
</div><!-- .content-area -->
<?php if (is_active_sidebar('right-side')): get_sidebar('right-side'); endif; ?>
<?php get_footer(); ?>
