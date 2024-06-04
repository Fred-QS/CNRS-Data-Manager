<div class="wrap cnrs-data-manager-page" id="cnrs-data-manager-documentation-wrapper" data-copy="<?php echo __('Copy link', 'cnrs-data-manager') ?>">
    <div style="margin-bottom: 20px;">
        <h1 class="wp-heading-inline title-and-logo">
            <?php echo svgFromBase64(CNRS_DATA_MANAGER_DOC_ICON, '#5d5d5d', 22) ?>
            <?php echo __('Documentation', 'cnrs-data-manager'); ?>
        </h1>
        <?php foreach (getDoc() as $title => $page): ?>
            <div class="cnrs-dm-documentation-container<?php echo !in_array(sanitize_title($title), ['sommaire', 'summary'], true) ? ' cnrs-dm-documentation-container-not-summary' : '' ?>">
                <?php echo str_replace('<h1', '<h1 id="' . sanitize_title($title) . '"', $page) ?>
            </div>
        <?php endforeach; ?>
    </div>
    <div id="cnrs-dm-doc-back-to-top">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="20" height="20">
            <path fill="currentColor" d="M233.4 105.4c12.5-12.5 32.8-12.5 45.3 0l192 192c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L256 173.3 86.6 342.6c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3l192-192z"/>
        </svg>
    </div>
</div>
<?php include_once CNRS_DATA_MANAGER_PATH . '/assets/icons/cnrs.svg';
