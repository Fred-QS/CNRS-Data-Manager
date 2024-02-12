<!-- Start CNRS Data Manager render -->
<div class="cnrs-dm-front-container" style="display: none">
    <?php if ($type !== 'all'): ?>
        <?php if ($isSelectorAvailable === true): ?>
            <!-- Start CNRS Data Manager display selector -->
            <div class="cnrs-dm-front-selector-container">
                <p class="cnrs-dm-front-selector-title"><?= __('Display', 'cnrs-data-manager') ?></p>
                <button type="button" data-action="list" class="cnrs-dm-front-selector cnrs-dm-front-selector-list<?= in_array($defaultView, [null, 'list']) ? ' selected' : '' ?>">
                    <?php include_once(CNRS_DATA_MANAGER_DEPORTED_SVG_PATH . '/list.svg') ?>
                </button>
                <button type="button" data-action="grid" class="cnrs-dm-front-selector cnrs-dm-front-selector-grid<?= $defaultView === 'grid' ? ' selected' : '' ?>">
                    <?php include_once(CNRS_DATA_MANAGER_DEPORTED_SVG_PATH . '/grid.svg') ?>
                </button>
            </div>
            <!-- End CNRS Data Manager display selector -->
        <?php endif; ?>

        <?php if ($renderMode === 'simple'): ?>
            <?php $entities = filterAgents($entities); ?>
            <!-- Start CNRS Data Manager agents list -->
            <div class="cnrs-dm-front-agents-container">
                <?php foreach ($entities as $agent): ?>
                    <div class="cnrs-dm-front-agent-wrapper<?= in_array($defaultView, [null, 'list']) ? ' cnrs-dm-front-agent-wrapper-list' : ' cnrs-dm-front-agent-wrapper-grid' ?>">
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
            <!-- Start CNRS Data Manager agents list filtered by <?= $type ?> -->
            <div class="cnrs-dm-front-sorted-agents-container">
                <?php foreach ($entities as $entity): ?>
                    <!-- Start CNRS Data Manager <?= $type ?> title -->
                    <div class="cnrs-dm-front-entity-title-container">
                        <?php include(CNRS_DATA_MANAGER_DEPORTED_TEMPLATES_PATH . '/cnrs-data-manager-sorted-title.php')?>
                    </div>
                    <!-- End CNRS Data Manager <?= $type ?> title -->
                    <!-- Start CNRS Data Manager agents list -->
                    <div class="cnrs-dm-front-agents-container">
                        <?php $entity['agents'] = filterAgents($entity['agents']); ?>
                        <?php foreach ($entity['agents'] as $agent): ?>
                            <div class="cnrs-dm-front-agent-wrapper<?= in_array($defaultView, [null, 'list']) ? ' cnrs-dm-front-agent-wrapper-list' : ' cnrs-dm-front-agent-wrapper-grid' ?>">
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
                <?php endforeach; ?>
            </div>
            <!-- End CNRS Data Manager agents list filtered by <?= $type ?> -->
        <?php endif; ?>
    <?php else: ?>
        <?php $entities = filterAgents($entities); ?>
        <!-- Start CNRS Data Manager all agent render -->
        <div class="cnrs-dm-front-all-agents-container">
            <div class="cnrs-dm-front-all-agents-titles-container">
                <div class="cnrs-dm-front-all-agents-title cnrs-dm-front-all-agents-title-avatar">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                        <path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z"/>
                    </svg>
                </div>
                <div class="cnrs-dm-front-all-agents-titles">
                    <div class="cnrs-dm-front-all-agents-title">
                        <p><?= __('Lastname', 'cnrs-data-manager') ?></p>
                    </div>
                    <div class="cnrs-dm-front-all-agents-title">
                        <p><?= __('Firstname', 'cnrs-data-manager') ?></p>
                    </div>
                    <div class="cnrs-dm-front-all-agents-title">
                        <p><?= __('Status', 'cnrs-data-manager') ?></p>
                    </div>
                    <div class="cnrs-dm-front-all-agents-title">
                        <p><?= __('Membership', 'cnrs-data-manager') ?></p>
                    </div>
                </div>
            </div>
            <div class="cnrs-dm-front-all-agents-titles-container-mobile">
                <div class="cnrs-dm-front-all-agents-titles">
                    <div class="cnrs-dm-front-all-agents-title">
                        <p><?= __('Identity', 'cnrs-data-manager') ?></p>
                    </div>
                    <div class="cnrs-dm-front-all-agents-title">
                        <p><?= __('Membership', 'cnrs-data-manager') ?></p>
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
        <!-- End CNRS Data Manager all agent render -->
    <?php endif; ?>
</div>
<!-- End CNRS Data Manager render -->