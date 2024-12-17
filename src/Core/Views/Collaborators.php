<?php

use CnrsDataManager\Core\Models\Collaborators;

//Collaborators::importCollabs();
//Collaborators::importFunders();
Collaborators::updateOrCreate();
Collaborators::saveRelations();
$allCollaborators = Collaborators::getCollaborators();

?>

<div class="wrap cnrs-data-manager-page" id="cnrs-data-manager-collaborators-wrapper">
    <div style="margin-bottom: 20px;">
        <h1 class="wp-heading-inline title-and-logo">
            <?php echo svgFromBase64(CNRS_DATA_MANAGER_COLLABORATORS_ICON, '#5d5d5d', 22) ?>
            <?php echo __('Collaborators', 'cnrs-data-manager'); ?>
        </h1>
        <p><?php echo __('In this part of the extension, you will be able to create <b>collaborators for the projects</b> of UMR researchers. These collaborators are either funders or partners. You will thus be able to associate funders and/or partners with the different projects.', 'cnrs-data-manager') ?></p>
        <p><?php echo __('You must first create collaborators in order to then be able to assign them to projects.', 'cnrs-data-manager') ?></p>
        <hr>
        <h3 class="cnrs-dm-tools-h2" style="margin-bottom: 5px;"><?php echo __('Collaborators list', 'cnrs-data-manager') ?></h3>
        <br>
        <form method="post" id="cnrs-dm-collaborators-list-wrapper">
            <div id="cnrs-dm-collaborators-loader-container" class="show">
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
        <br>
        <hr>
        <h3 class="cnrs-dm-tools-h2"><?php echo __('Projects & Collaborators', 'cnrs-data-manager') ?></h3>
        <?php cnrs_polylang_installed() ?>
        <br>
        <form method="post" class="cnrs-dm-filters-allowed-wrapper" id="cnrs-data-manager-collaborators-attribution">
            <?php foreach (getProjects() as $project): ?>
            <?php $collaborators = Collaborators::getCollaboratorsFromProjectId((int) $project['id']) ?>
            <div class="cnrs-dm-filters-allowed-container cnrs-dm-collaborators-container">
                <div class="cnrs-dm-filters-allowed-header">
                    <a class="cnrs-dm-collaborator-project-title" href="<?php echo $project['slug'] ?>" target="_blank"><?php echo isset($project['flag']) ? $project['flag'] . '&nbsp;&nbsp;' : null ?><?php echo $project['name'] ?></a>
                    <span class="cnrs-dm-collaborators-chevron">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="12" height="12" style="color: currentColor;">
                        <path fill="currentColor" d="M233.4 406.6c12.5 12.5 32.8 12.5 45.3 0l192-192c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L256 338.7 86.6 169.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l192 192z"/>
                    </svg>
                </span>
                    <span>
                    <span><b><?php echo __('Funders', 'cnrs-data-manager') ?></b> <i class="cnrs-dm-collaborators-counter-funders">(<?php echo count($collaborators['funders']) ?>)</i></span>
                    <span style="opacity: 0.7;"> | </span>
                    <span><b><?php echo __('Partners', 'cnrs-data-manager') ?></b> <i class="cnrs-dm-collaborators-counter-partners">(<?php echo count($collaborators['partners']) ?>)</i></span>
                </span>
                </div>
                <div class="cnrs-dm-collaborators-project-assign-wrapper">
                    <div class="cnrs-dm-collaborators-project-assign-container">
                        <div class="cnrs-dm-collaborators-project-assign-funders-wrapper cnrs-dm-collaborators-project-assign-generic-wrapper">
                            <b><?php echo __('Funders', 'cnrs-data-manager') ?></b>
                            <div class="cnrs-dm-collaborators-project-assign-funders cnrs-dm-collaborators-project-assign-generic"><?php echo getCollaboratorsThumbnails($collaborators['funders']) ?></div>
                            <input class="cnrs-dm-collaborators-search-input" placeholder="<?php echo sprintf(__('Search for %s', 'cnrs-data-manager'), __('funders', 'cnrs-data-manager')) ?>" type="search" spellcheck="false">
                            <?php if (!empty($allCollaborators['funders'])): ?>
                                <ul class="cnrs-dm-collaborators-lister">
                                    <?php foreach ($allCollaborators['funders'] as $funder): ?>
                                        <li class="cnrs-dm-collaborator-li" data-type="funder">
                                            <img src="<?php echo $funder['entity_logo'] === null ? '/wp-content/plugins/cnrs-data-manager/assets/media/default_avatar.png' : $funder['entity_logo'] ?>" alt="<?php echo $funder['entity_name'] ?> picture">
                                            <label>
                                                <input<?php echo isCollaboratorSelected($funder['id'], $collaborators['funders']) ? ' checked' : '' ?> type="checkbox" name="cnrs-dm-collaborators[<?php echo $project['id'] ?>][funders][]" value="<?php echo $funder['id'] ?>" data-id="<?php echo $funder['id'] ?>" data-name="<?php echo $funder['entity_name'] ?>" data-type="funders">
                                                <span><?php echo $funder['entity_name'] ?></span>
                                            </label>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                        <br>
                        <div class="cnrs-dm-collaborators-project-assign-partners-wrapper cnrs-dm-collaborators-project-assign-generic-wrapper">
                            <b><?php echo __('Partners', 'cnrs-data-manager') ?></b>
                            <div class="cnrs-dm-collaborators-project-assign-partners cnrs-dm-collaborators-project-assign-generic"><?php echo getCollaboratorsThumbnails($collaborators['partners']) ?></div>
                            <input class="cnrs-dm-collaborators-search-input" placeholder="<?php echo sprintf(__('Search for %s', 'cnrs-data-manager'), __('partners', 'cnrs-data-manager')) ?>" type="search" spellcheck="false">
                            <?php if (!empty($allCollaborators['partners'])): ?>
                                <ul class="cnrs-dm-collaborators-lister">
                                    <?php foreach ($allCollaborators['partners'] as $partner): ?>
                                        <li class="cnrs-dm-collaborator-li" data-type="partner">
                                            <img src="<?php echo $partner['entity_logo'] === null ? '/wp-content/plugins/cnrs-data-manager/assets/media/default_avatar.png' : $partner['entity_logo'] ?>" alt="<?php echo $partner['entity_name'] ?> picture">
                                            <label>
                                                <input<?php echo isCollaboratorSelected($partner['id'], $collaborators['partners']) ? ' checked' : '' ?> type="checkbox" name="cnrs-dm-collaborators[<?php echo $project['id'] ?>][partners][]" value="<?php echo $partner['id'] ?>" data-id="<?php echo $partner['id'] ?>" data-name="<?php echo $partner['entity_name'] ?>" data-type="partners">
                                                <span><?php echo $partner['entity_name'] ?></span>
                                            </label>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </form>
        <p class="submit" style="text-align: center;">
            <input form="cnrs-data-manager-collaborators-attribution" type="submit" id="submit-collaborators" class="button button-primary" value="<?php echo __('Save') ?>">
        </p>
    </div>
</div>
<?php include_once CNRS_DATA_MANAGER_PATH . '/assets/icons/cnrs.svg';
