<div id="cnrs-dm-front-loader-icon" style="display: none">
    <?php include_once(CNRS_DATA_MANAGER_DEPORTED_SVG_PATH . '/loader.svg') ?>
</div>

<div class="cnrs-category-title">
    <h3><?php echo get_bloginfo() ?></h3>
    <h2><?php echo sprintf(__('Find the %s', 'cnrs-data-manager'), $toSentencePart) ?></h2>
</div>

<?php echo do_shortcode('[cnrs-data-manager type="filters"]') ?>

<?php $previewTemplate = null ?>
<?php if (have_posts()): ?>
    <div id="cnrs-data-manager-front-blog" class="cnrs-dm-front-silent-container">
    <?php while (have_posts()) : the_post(); ?>
        <?php if (!in_array($name, $candidateCatNames, true)): ?>
            <?php $previewTemplate = 'poster' ?>
        <?php else: ?>
            <?php $previewTemplate = 'card' ?>
        <?php endif; ?>
        <?php include(CNRS_DATA_MANAGER_DEPORTED_TEMPLATES_PATH . '/cnrs-data-manager-post-preview.php') ?>
    <?php endwhile; ?>
    </div>
<?php else: ?>
    <p class="cnrs-dm-front-no-result"><?php echo __('Sorry, no posts matched your criteria.', 'cnrs-data-manager') ?></p>
<?php endif; ?>

<?php echo do_shortcode('[cnrs-data-manager type="pagination"]') ?>

<?php if (in_array($candidatingCatFr, $parents, true) || in_array($candidatingCatEn, $parents, true)): ?>
    <div class="cnrs-data-manager-front-candidate-wrapper">
        <div class="cnrs-data-manager-front-candidate-title">
            <h2><?php echo __('Spontaneous application', 'cnrs-data-manager') ?></h2>
            <p>
                <span style="font-weight: 400;"><?php echo __('Send us your CV, profile and cover letter directly.', 'cnrs-data-manager') ?></span>
            </p>
        </div>
        <div class="cnrs-data-manager-front-candidate-btn-container">
            <a class="cnrs-data-manager-front-candidate-btn" target="_blank" href="<?php echo $urls[$name] ?>"><?php echo __('I postulate', 'cnrs-data-manager') ?></a>
        </div>
    </div>
<?php endif; ?>
