<?php

if ($filterType === 'project') {
    $index = array_search('sub-categories-list', $filters, true);
    if ($index !== false) {
        $filters[$index] = 'by-team';
    }
}

if (empty($terms) && $index = array_search('sub-categories-list', $filters, true) !== false) {
    unset($filters[$index]);
}

switch (count($filters)) {
    case 4: $moduleSizeClass = 'cnrs-dm-front-filters-size-25'; break;
    case 3: $moduleSizeClass = 'cnrs-dm-front-filters-size-33'; break;
    case 2: $moduleSizeClass = 'cnrs-dm-front-filters-size-50'; break;
    default: $moduleSizeClass = 'cnrs-dm-front-filters-size-full'; break;
} ?>

<!-- Start CNRS Data Manager filter section -->
<form method="get" class="cnrs-dm-front-filters-wrapper cnrs-dm-front-filters-wrapper-<?php echo $parentCatSlug ?>" data-shortcode="cnrs-data-manager-shortcode-<?php echo $shortCodesCounter ?>">
    <div id="cnrs-dm-front-filters-modules-container">
        <?php if (in_array('per-page', $filters, true)): ?>
            <!-- Start CNRS Data Manager posts limit per page filter module section -->
            <div class="cnrs-dm-front-filter cnrs-dm-front-filter-column cnrs-dm-front-filters-modules <?php echo $moduleSizeClass ?>" id="cnrs-dm-front-filter-pagi-container-<?php echo $shortCodesCounter ?>">
                <label class="cnrs-dm-front-single-input-label" for="cnrs-dm-front-filter-pagination-selector"><?php echo __('Number of elements per page', 'cnrs-data-manager') ?></label>
                <select id="cnrs-dm-front-filter-pagination-selector" name="cdm-limit">
                    <option<?php echo isset($_GET['cdm-limit']) && $_GET['cdm-limit'] === '5' ? ' selected' : '' ?> value="5">5</option>
                    <option<?php echo (isset($_GET['cdm-limit']) && $_GET['cdm-limit'] === '10') || !isset($_GET['cdm-limit']) ? ' selected' : '' ?> value="10">10</option>
                    <option<?php echo isset($_GET['cdm-limit']) && $_GET['cdm-limit'] === '20' ? ' selected' : '' ?> value="20">20</option>
                    <option<?php echo isset($_GET['cdm-limit']) && $_GET['cdm-limit'] === '50' ? ' selected' : '' ?> value="50">50</option>
                    <option<?php echo isset($_GET['cdm-limit']) && $_GET['cdm-limit'] === '100' ? ' selected' : '' ?> value="100">100</option>
                </select>
            </div>
            <!-- End CNRS Data Manager posts limit per page filter module section -->
        <?php endif; ?>
        <?php if (in_array('sub-categories-list', $filters, true)): ?>
            <!-- Start CNRS Data Manager posts categories list filter module section -->
            <div class="cnrs-dm-front-filter cnrs-dm-front-filter-column cnrs-dm-front-filters-modules <?php echo $moduleSizeClass ?>" id="cnrs-dm-front-filter-cats-container-<?php echo $shortCodesCounter ?>">
                <label class="cnrs-dm-front-single-input-label" for="cnrs-dm-front-filter-cats-selector"><?php echo __('Select a category', 'cnrs-data-manager') ?></label>
                <select id="cnrs-dm-front-filter-cats-selector" name="cdm-tax">
                    <option selected value="<?php echo $parentCatSlug ?>"><?php echo __('All categories', 'cnrs-data-manager') ?></option>
                    <?php foreach ($terms as $term): ?>
                        <option<?php echo isset($_GET['cdm-tax']) && $_GET['cdm-tax'] === $term->slug ? ' selected' : '' ?> value="<?php echo $term->slug ?>"><?php echo $term->name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <!-- End CNRS Data Manager posts categories list filter module section -->
        <?php endif; ?>
        <?php if ($filterType === 'project' && in_array('by-team', $filters, true)): ?>
            <!-- Start CNRS Data Manager posts teams list filter module section -->
            <div class="cnrs-dm-front-filter cnrs-dm-front-filter-column cnrs-dm-front-filters-modules <?php echo $moduleSizeClass ?>" id="cnrs-dm-front-filter-cats-container-<?php echo $shortCodesCounter ?>">
                <label class="cnrs-dm-front-single-input-label" for="cnrs-dm-front-filter-cats-selector"><?php echo __('Select a team', 'cnrs-data-manager') ?></label>
                <select id="cnrs-dm-front-filter-cats-selector" name="cdm-team">
                    <option selected value="0"><?php echo __('All teams', 'cnrs-data-manager') ?></option>
                    <?php foreach ($teams as $team): ?>
                        <option<?php echo isset($_GET['cdm-team']) && $_GET['cdm-team'] === $team['id'] ? ' selected' : '' ?> value="<?php echo $team['id'] ?>"><?php echo $team['name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <!-- End CNRS Data Manager posts teams list filter module section -->
        <?php endif; ?>
        <?php if (in_array('by-year', $filters, true)): ?>
            <!-- Start CNRS Data Manager filter by date module section -->
            <div class="cnrs-dm-front-filter cnrs-dm-front-filter-column cnrs-dm-front-filters-modules <?php echo $moduleSizeClass ?>" id="cnrs-dm-front-filter-year-container-<?php echo $shortCodesCounter ?>">
                <label class="cnrs-dm-front-single-input-label" for="cnrs-dm-front-filter-year-selector"><?php echo __('Select a date', 'cnrs-data-manager') ?></label>
                <select id="cnrs-dm-front-filter-year-selector" name="cdm-year">
                    <option<?php echo isset($_GET['cdm-year']) && $_GET['cdm-year'] === 'all' ? ' selected' : '' ?> value="all"><?php echo __('All years', 'cnrs-data-manager') ?></option>
                    <?php for ($i = (int) date("Y"); $i >= (int) date("Y") - 10; $i--): ?>
                        <option<?php echo isset($_GET['cdm-year']) && (int) $_GET['cdm-year'] === $i ? ' selected' : '' ?> value="<?php echo $i ?>"><?php echo $i ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <!-- End CNRS Data Manager filter by date module section -->
        <?php endif; ?>
        <?php if (in_array('search-field', $filters, true)): ?>
            <!-- Start CNRS Data Manager search filter module section -->
            <div class="cnrs-dm-front-filter cnrs-dm-front-filter-column cnrs-dm-front-filters-modules <?php echo $moduleSizeClass ?>" id="cnrs-dm-front-filter-search-container-<?php echo $shortCodesCounter ?>">
                <label class="cnrs-dm-front-single-input-label" for="cnrs-dm-front-filter-search-input"><?php echo __('Search', 'cnrs-data-manager') ?></label>
                <input type="search" name="s" id="cnrs-dm-front-filter-search-input" value="<?php echo $_GET['s'] ?>">
            </div>
            <!-- End CNRS Data Manager search filter module section -->
        <?php endif; ?>
    </div>
    <div id="cnrs-dm-front-filter-button-container">
        <button type="submit" class="cnrs-dm-front-submit-btn">
            <?php echo __('Apply filters', 'cnrs-data-manager') ?>
        </button>
    </div>
</form>
<!-- End CNRS Data Manager filter section -->