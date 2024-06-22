<div id="cnrs-dm-front-loader-icon" style="display: none">
    <?php include_once(CNRS_DATA_MANAGER_DEPORTED_SVG_PATH . '/loader.svg') ?>
</div>

<div class="cnrs-category-title">
    <h3><?php echo get_bloginfo() ?></h3>
    <h2><?php echo sprintf(__('Find the %s', 'cnrs-data-manager'), strtolower($catName)) ?></h2>
</div>

<?php if ($isFilterHidden === false): ?>
    <?php echo do_shortcode('[cnrs-data-manager type="filters"]') ?>
<?php endif; ?>

<?php if (have_posts()): ?>
    <div id="cnrs-data-manager-front-blog" class="cnrs-dm-front-silent-container cnrs-dm-front-silent-container-<?php echo strtolower($previewTemplate) ?>">
    <?php while (have_posts()) : the_post(); ?>
        <?php include(CNRS_DATA_MANAGER_DEPORTED_TEMPLATES_PATH . '/cnrs-data-manager-post-preview.php') ?>
    <?php endwhile; ?>
    </div>
<?php else: ?>
    <div class="cnrs-dm-front-silent-container">
        <p class="cnrs-dm-front-no-result"><?php echo __('Sorry, no posts matched your criteria.', 'cnrs-data-manager') ?></p>
    </div>
<?php endif; ?>

<?php echo do_shortcode('[cnrs-data-manager type="pagination"]') ?>

<?php if ($isCandidating === true && have_posts()): ?>
    <div class="cnrs-data-manager-front-candidate-wrapper">
        <div class="cnrs-data-manager-front-candidate-title">
            <h2><?php echo __('Spontaneous application', 'cnrs-data-manager') ?></h2>
            <p>
                <span style="font-weight: 400;"><?php echo __('Send us your CV, profile and cover letter directly.', 'cnrs-data-manager') ?></span>
            </p>
        </div>
        <div class="cnrs-data-manager-front-candidate-btn-container">
            <a class="cnrs-data-manager-front-candidate-btn" target="_blank" href="mailto:<?php echo $candidate_email ?>"><?php echo __('I postulate', 'cnrs-data-manager') ?></a>
        </div>
    </div>
<?php endif; ?>
