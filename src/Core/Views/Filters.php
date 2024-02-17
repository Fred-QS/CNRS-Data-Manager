<?php
if (empty($terms) && $index = array_search('sub-categories-list', $filters, true) !== false) {
    unset($filters[$index]);
}
switch (count($filters)) {
    case 4: $moduleSizeClass = 'cnrs-dm-front-filters-size-25'; break;
    case 3: $moduleSizeClass = 'cnrs-dm-front-filters-size-33'; break;
    case 2: $moduleSizeClass = 'cnrs-dm-front-filters-size-50'; break;
    default: $moduleSizeClass = 'cnrs-dm-front-filters-size-full'; break;
}
?>

<!-- Start CNRS Data Manager filter section -->
<form method="get" class="cnrs-dm-front-filters-wrapper" data-shortcode="cnrs-data-manager-shortcode-<?= $shortCodesCounter ?>">
    <div id="cnrs-dm-front-filters-modules-container">
        <?php if (in_array('per-page', $filters, true)): ?>
            <!-- Start CNRS Data Manager posts limit per page filter module section -->
            <div class="cnrs-dm-front-filter cnrs-dm-front-filter-column cnrs-dm-front-filters-modules <?= $moduleSizeClass ?>" id="cnrs-dm-front-filter-pagi-container-<?= $shortCodesCounter ?>">
                <label class="cnrs-dm-front-single-input-label" for="cnrs-dm-front-filter-pagination-selector"><?= __('Number of elements per page', 'cnrs-data-manager') ?></label>
                <select id="cnrs-dm-front-filter-pagination-selector" name="cdm-limit">
                    <option<?= isset($_GET['cdm-limit']) && $_GET['cdm-limit'] === '5' ? ' selected' : '' ?> value="5">5</option>
                    <option<?= (isset($_GET['cdm-limit']) && $_GET['cdm-limit'] === '10') || !isset($_GET['cdm-limit']) ? ' selected' : '' ?> value="10">10</option>
                    <option<?= isset($_GET['cdm-limit']) && $_GET['cdm-limit'] === '20' ? ' selected' : '' ?> value="20">20</option>
                    <option<?= isset($_GET['cdm-limit']) && $_GET['cdm-limit'] === '50' ? ' selected' : '' ?> value="50">50</option>
                    <option<?= isset($_GET['cdm-limit']) && $_GET['cdm-limit'] === '100' ? ' selected' : '' ?> value="100">100</option>
                </select>
            </div>
            <!-- End CNRS Data Manager posts limit per page filter module section -->
        <?php endif; ?>
        <?php if (in_array('sub-categories-list', $filters, true)): ?>
            <!-- Start CNRS Data Manager posts categories list filter module section -->
            <div class="cnrs-dm-front-filter cnrs-dm-front-filter-column cnrs-dm-front-filters-modules <?= $moduleSizeClass ?>" id="cnrs-dm-front-filter-cats-container-<?= $shortCodesCounter ?>">
                <label class="cnrs-dm-front-single-input-label" for="cnrs-dm-front-filter-cats-selector"><?= __('Select a category', 'cnrs-data-manager') ?></label>
                <select id="cnrs-dm-front-filter-cats-selector" name="cdm-tax">
                    <option selected value="<?= $parentCatSlug ?>"><?= __('All categories', 'cnrs-data-manager') ?></option>
                    <?php foreach ($terms as $term): ?>
                        <option<?= isset($_GET['cdm-tax']) && $_GET['cdm-tax'] === $term->slug ? ' selected' : '' ?> value="<?= $term->slug ?>"><?= $term->name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <!-- End CNRS Data Manager posts categories list filter module section -->
        <?php endif; ?>
        <?php if (in_array('by-year', $filters, true)): ?>
            <!-- Start CNRS Data Manager filter by date module section -->
            <div class="cnrs-dm-front-filter cnrs-dm-front-filter-column cnrs-dm-front-filters-modules <?= $moduleSizeClass ?>" id="cnrs-dm-front-filter-year-container-<?= $shortCodesCounter ?>">
                <label class="cnrs-dm-front-single-input-label" for="cnrs-dm-front-filter-year-selector"><?= __('Select a date', 'cnrs-data-manager') ?></label>
                <select id="cnrs-dm-front-filter-year-selector" name="cdm-year">
                    <option<?= isset($_GET['cdm-year']) && $_GET['cdm-year'] === 'all' ? ' selected' : '' ?> value="all"><?= __('All years', 'cnrs-data-manager') ?></option>
                    <?php for ($i = (int) date("Y"); $i >= (int) date("Y") - 10; $i--): ?>
                        <option<?= isset($_GET['cdm-year']) && (int) $_GET['cdm-year'] === $i ? ' selected' : '' ?> value="<?= $i ?>"><?= $i ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <!-- End CNRS Data Manager filter by date module section -->
        <?php endif; ?>
        <?php if (in_array('search-field', $filters, true)): ?>
            <!-- Start CNRS Data Manager search filter module section -->
            <div class="cnrs-dm-front-filter cnrs-dm-front-filter-column cnrs-dm-front-filters-modules <?= $moduleSizeClass ?>" id="cnrs-dm-front-filter-search-container-<?= $shortCodesCounter ?>">
                <label class="cnrs-dm-front-single-input-label" for="cnrs-dm-front-filter-search-input"><?= __('Search', 'cnrs-data-manager') ?></label>
                <input type="search" name="s" id="cnrs-dm-front-filter-search-input" value="<?= $_GET['s'] ?>">
            </div>
            <!-- End CNRS Data Manager search filter module section -->
        <?php endif; ?>
    </div>
    <div id="cnrs-dm-front-filter-button-container">
        <button type="submit" class="cnrs-dm-front-submit-btn">
            <?= __('Apply filters', 'cnrs-data-manager') ?>
        </button>
    </div>
</form>
<!-- End CNRS Data Manager filter section -->
