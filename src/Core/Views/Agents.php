<!-- Start CNRS Data Manager render -->
<div class="cnrs-dm-front-container" style="display: none" data-shortcode="cnrs-data-manager-shortcode-<?php echo $shortCodesCounter ?>">
    <?php if ($type !== 'all'): ?>
        <?php if ($isSelectorAvailable === true): ?>
            <!-- Start CNRS Data Manager display selector -->
            <div class="cnrs-dm-front-selector-container">
                <p class="cnrs-dm-front-selector-title"><?php echo __('Display', 'cnrs-data-manager') ?></p>
                <button type="button" data-action="list" class="cnrs-dm-front-selector cnrs-dm-front-selector-list<?php echo in_array($defaultView, [null, 'list']) ? ' selected' : '' ?>">
                    <?php include_once(CNRS_DATA_MANAGER_DEPORTED_SVG_PATH . '/list.svg') ?>
                </button>
                <button type="button" data-action="grid" class="cnrs-dm-front-selector cnrs-dm-front-selector-grid<?php echo $defaultView === 'grid' ? ' selected' : '' ?>">
                    <?php include_once(CNRS_DATA_MANAGER_DEPORTED_SVG_PATH . '/grid.svg') ?>
                </button>
            </div>
            <!-- End CNRS Data Manager display selector -->
        <?php endif; ?>
        <i class="cnrs-dm-front-disclaimer">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                <path d="M192 0c-41.8 0-77.4 26.7-90.5 64H64C28.7 64 0 92.7 0 128V448c0 35.3 28.7 64 64 64H320c35.3 0 64-28.7 64-64V128c0-35.3-28.7-64-64-64H282.5C269.4 26.7 233.8 0 192 0zm0 64a32 32 0 1 1 0 64 32 32 0 1 1 0-64zM128 256a64 64 0 1 1 128 0 64 64 0 1 1 -128 0zM80 432c0-44.2 35.8-80 80-80h64c44.2 0 80 35.8 80 80c0 8.8-7.2 16-16 16H96c-8.8 0-16-7.2-16-16z"/>
            </svg>
            <?php echo __('Click on an agent to view their card.', 'cnrs-data-manager') ?>
        </i>
        <?php if ($renderMode === 'simple'): ?>
            <!-- Start CNRS Data Manager agents list -->
            <div class="cnrs-dm-front-agents-container">
                <?php foreach ($entities as $agent): ?>
                    <div class="cnrs-dm-front-agent-wrapper<?php echo in_array($defaultView, [null, 'list']) ? ' cnrs-dm-front-agent-wrapper-list' : ' cnrs-dm-front-agent-wrapper-grid' ?>">
                        <?php if ($isSelectorAvailable === true || in_array($defaultView, [null, 'list'])): ?>
                            <?php include(CNRS_DATA_MANAGER_DEPORTED_TEMPLATES_PATH . '/cnrs-data-manager-inline.php')?>
                        <?php endif; ?>
                        <?php if ($isSelectorAvailable === true || $defaultView === 'grid'): ?>
                            <?php include(CNRS_DATA_MANAGER_DEPORTED_TEMPLATES_PATH . '/cnrs-data-manager-card.php')?>
                        <?php endif; ?>
                        <?php include(CNRS_DATA_MANAGER_DEPORTED_TEMPLATES_PATH . '/cnrs-data-manager-info.php')?>
                    </div>
                <?php endforeach; ?>
            </div>
            <!-- End CNRS Data Manager agents list -->
        <?php else: ?>
            <!-- Start CNRS Data Manager agents list filtered by <?php echo $type ?> -->
            <?php if (cnrs_is_there_agents($entities)): ?>
                <div class="cnrs-dm-front-sorted-agents-container">
                    <?php foreach ($entities as $entity): ?>
                        <?php if (count($entity['agents']) > 0): ?>
                            <!-- Start CNRS Data Manager <?php echo $type ?> title -->
                            <div class="cnrs-dm-front-entity-title-container">
                                <?php include(CNRS_DATA_MANAGER_DEPORTED_TEMPLATES_PATH . '/cnrs-data-manager-sorted-title.php')?>
                            </div>
                            <!-- End CNRS Data Manager <?php echo $type ?> title -->
                            <!-- Start CNRS Data Manager agents list -->
                            <div class="cnrs-dm-front-agents-container">
                                <?php foreach ($entity['agents'] as $agent): ?>
                                    <div class="cnrs-dm-front-agent-wrapper<?php echo in_array($defaultView, [null, 'list']) ? ' cnrs-dm-front-agent-wrapper-list' : ' cnrs-dm-front-agent-wrapper-grid' ?>">
                                        <?php if ($isSelectorAvailable === true || in_array($defaultView, [null, 'list'])): ?>
                                            <?php include(CNRS_DATA_MANAGER_DEPORTED_TEMPLATES_PATH . '/cnrs-data-manager-inline.php')?>
                                        <?php endif; ?>
                                        <?php if ($isSelectorAvailable === true || $defaultView === 'grid'): ?>
                                            <?php include(CNRS_DATA_MANAGER_DEPORTED_TEMPLATES_PATH . '/cnrs-data-manager-card.php')?>
                                        <?php endif; ?>
                                        <?php include(CNRS_DATA_MANAGER_DEPORTED_TEMPLATES_PATH . '/cnrs-data-manager-info.php')?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <!-- End CNRS Data Manager agents list -->
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="cnrs-dm-front-no-result-found">
                    <i><?php echo __('No agent matches your search criteria.', 'cnrs-data-manager') ?></i>
                </div>
            <?php endif; ?>
            <!-- End CNRS Data Manager agents list filtered by <?php echo $type ?> -->
        <?php endif; ?>
    <?php else: ?>
        <div id="cnrs-dm-front-loader-icon" style="display: none">
            <?php include_once(CNRS_DATA_MANAGER_DEPORTED_SVG_PATH . '/loader.svg') ?>
        </div>
        <!-- Start CNRS Data Manager all agent render -->
        <?php require_once(CNRS_DATA_MANAGER_PATH . '/templates/includes/filters.php') ?>
        <?php if (count($entities) > 0): ?>
            <?php include(CNRS_DATA_MANAGER_PATH . '/templates/includes/pagination.php') ?>
            <i class="cnrs-dm-front-disclaimer">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                    <path d="M192 0c-41.8 0-77.4 26.7-90.5 64H64C28.7 64 0 92.7 0 128V448c0 35.3 28.7 64 64 64H320c35.3 0 64-28.7 64-64V128c0-35.3-28.7-64-64-64H282.5C269.4 26.7 233.8 0 192 0zm0 64a32 32 0 1 1 0 64 32 32 0 1 1 0-64zM128 256a64 64 0 1 1 128 0 64 64 0 1 1 -128 0zM80 432c0-44.2 35.8-80 80-80h64c44.2 0 80 35.8 80 80c0 8.8-7.2 16-16 16H96c-8.8 0-16-7.2-16-16z"/>
                </svg>
                <?php echo __('Click on an agent to view their card.', 'cnrs-data-manager') ?>
            </i>
            <div class="cnrs-dm-front-all-agents-container">
                <div class="cnrs-dm-front-all-agents-titles-container">
                    <div class="cnrs-dm-front-all-agents-title cnrs-dm-front-all-agents-title-avatar">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                            <path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z"/>
                        </svg>
                    </div>
                    <div class="cnrs-dm-front-all-agents-titles">
                        <div class="cnrs-dm-front-all-agents-title">
                            <p><?php echo __('Lastname', 'cnrs-data-manager') ?></p>
                        </div>
                        <div class="cnrs-dm-front-all-agents-title">
                            <p><?php echo __('Firstname', 'cnrs-data-manager') ?></p>
                        </div>
                        <div class="cnrs-dm-front-all-agents-title">
                            <p><?php echo __('Status', 'cnrs-data-manager') ?></p>
                        </div>
                        <div class="cnrs-dm-front-all-agents-title">
                            <p><?php echo __('Membership', 'cnrs-data-manager') ?></p>
                        </div>
                    </div>
                </div>
                <div class="cnrs-dm-front-all-agents-titles-container-mobile">
                    <div class="cnrs-dm-front-all-agents-titles">
                        <div class="cnrs-dm-front-all-agents-title">
                            <p><?php echo __('Identity', 'cnrs-data-manager') ?></p>
                        </div>
                        <div class="cnrs-dm-front-all-agents-title">
                            <p><?php echo __('Membership', 'cnrs-data-manager') ?></p>
                        </div>
                    </div>
                </div>
                <?php foreach ($entities as $agent): ?>
                    <div class="cnrs-dm-front-agent-wrapper cnrs-dm-front-all-mode-wrapper">
                        <?php include(CNRS_DATA_MANAGER_DEPORTED_TEMPLATES_PATH . '/cnrs-data-manager-list-item.php')?>
                        <?php include(CNRS_DATA_MANAGER_DEPORTED_TEMPLATES_PATH . '/cnrs-data-manager-info.php')?>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php include(CNRS_DATA_MANAGER_PATH . '/templates/includes/pagination.php') ?>
        <?php else: ?>
            <div class="cnrs-dm-front-no-result-found">
                <i><?php echo __('No agent matches your search criteria.', 'cnrs-data-manager') ?></i>
            </div>
        <?php endif; ?>
        <!-- End CNRS Data Manager all agent render -->
    <?php endif; ?>
</div>
<!-- End CNRS Data Manager render -->
