<div class="wrap cnrs-data-manager-page" id="cnrs-data-manager-documentation-wrapper">
    <div style="margin-bottom: 20px;">
        <h1 class="wp-heading-inline title-and-logo">
            <?php echo svgFromBase64(CNRS_DATA_MANAGER_DOC_ICON, '#5d5d5d', 22) ?>
            <?php echo __('Documentation', 'cnrs-data-manager'); ?>
        </h1>
        <?php foreach (getDoc() as $title => $page): ?>
            <div class="cnrs-dm-documentation-container">
                <?php echo str_replace('<h1', '<h1 id="' . sanitize_title($title) . '"', $page) ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php include_once CNRS_DATA_MANAGER_PATH . '/assets/icons/cnrs.svg';
