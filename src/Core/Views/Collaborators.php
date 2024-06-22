<?php

use CnrsDataManager\Core\Models\Collaborators;

Collaborators::updateOrCreate();

?>

<div class="wrap cnrs-data-manager-page" id="cnrs-data-manager-collaborators-wrapper">
    <div style="margin-bottom: 20px;">
        <h1 class="wp-heading-inline title-and-logo">
            <?php echo svgFromBase64(CNRS_DATA_MANAGER_COLLABORATORS_ICON, '#5d5d5d', 22) ?>
            <?php echo __('Collaborators', 'cnrs-data-manager'); ?>
        </h1>
        <p><?php echo __('In this part of the extension, you will be able to create <b>collaborators for the projects</b> of UMR researchers. These collaborators are either funders or partners. You will thus be able to associate funders and/or partners with the different projects.', 'cnrs-data-manager') ?></p>
        <p><?php echo __('You must first create collaborators in order to then be able to assign them to projects.', 'cnrs-data-manager') ?></p>
        <br>
        <form method="post" id="cnrs-dm-collaborators-list-wrapper">
            <div id="cnrs-dm-collaborators-loader-container">
                <div id="cnrs-dm-collaborators-loader">
                    <?php include_once(CNRS_DATA_MANAGER_PATH . '/templates/svg/loader.svg'); ?>
                </div>
            </div>
            <div class="subsubsub cnrs-data-manager-subsubsub">
                <p id="cnrs-dm-collaborators-total" class="current" aria-current="page"><?php echo __('Total', 'cnrs-data-manager') ?> <span class="count">(0)</span></p>
            </div>
            <div class="cnrs-dm-collaborators-create-container">
                <button type="button" id="cnrs-dm-add-new-collaborator" class="button button-primary"><?php echo __('Create', 'cnrs-data-manager') ?></button>
            </div>
            <div id="cnrs-dm-search-box-form" class="cnrs-dm-search-box-form-collaborators">
                <div class="search-box">
                    <label class="screen-reader-text" for="cnrs-data-manager-collaborators-search"><?php echo __('Search', 'cnrs-data-manager') ?>:</label>
                    <input type="search" id="cnrs-data-manager-mission-search" name="cnrs-data-manager-collaborators-search">
                    <input type="button" id="cnrs-data-manager-search-submit" class="button" value="<?php echo __('Search', 'cnrs-data-manager') ?>">
                </div>
            </div>
            <div id="cnrs-dm-collaborators-list-container"></div>
        </form>
    </div>
</div>
<?php include_once CNRS_DATA_MANAGER_PATH . '/assets/icons/cnrs.svg';
