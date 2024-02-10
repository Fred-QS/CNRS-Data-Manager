<!-- Start CNRS Data Manager render -->
<div class="cnrs-dm-front-container">
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
            <!-- Start CNRS Data Manager agents list -->
            <div class="cnrs-dm-front-agents-container">
                <?php foreach ($entities as $agent): ?>
                    <div class="cnrs-dm-front-agent-wrapper">
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
                        <?php foreach ($entity['agents'] as $agent): ?>
                            <div class="cnrs-dm-front-agent-wrapper">
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
        <!-- Start CNRS Data Manager all agent render -->
        <div class="cnrs-dm-front-all-agents-container">
            <?php foreach ($entities as $agent): ?>
                <div class="cnrs-dm-front-agent-wrapper">
                    <?php include(CNRS_DATA_MANAGER_DEPORTED_TEMPLATES_PATH . '/cnrs-data-manager-list-item.php')?>
                    <?php include(CNRS_DATA_MANAGER_DEPORTED_TEMPLATES_PATH . '/cnrs-data-manager-info.php')?>
                </div>
            <?php endforeach; ?>
        </div>
        <!-- End CNRS Data Manager all agent render -->
    <?php endif; ?>
</div>
<!-- End CNRS Data Manager render -->