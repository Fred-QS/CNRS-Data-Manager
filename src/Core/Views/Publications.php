<!-- Start CNRS Data Manager render -->
<div class="cnrs-dm-front-container" style="display: none" data-shortcode="cnrs-data-manager-shortcode-<?php echo $shortCodesCounter ?>">
    <form method="get" class="cnrs-dm-front-filters-wrapper" id="cnrs-dm-front-filters-publications-form">
        <div id="cnrs-dm-front-filters-modules-container">
            <div class="cnrs-dm-front-filter cnrs-dm-front-filter-column cnrs-dm-front-filters-modules cnrs-dm-front-filters-size-15">
                <label class="cnrs-dm-front-single-input-label" for="cnrs-dm-front-filter-authors-selector"><?php echo __('Author', 'cnrs-data-manager') ?></label>
                <select id="cnrs-dm-front-filter-authors-selector" name="cdm-author">
                    <option<?php echo $_GET['cdm-author'] === 'all' ? ' selected' : '' ?> value="all"><?php echo __('All authors', 'cnrs-data-manager') ?></option>
                    <?php foreach ($filters['authors'] as $author): ?>
                        <option<?php echo $_GET['cdm-author'] === $author['title'] ? ' selected' : '' ?> value="<?php echo $author['title'] ?>"><?php echo $author['title'] ?> (<?php echo $author['count'] ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="cnrs-dm-front-filter cnrs-dm-front-filter-column cnrs-dm-front-filters-modules cnrs-dm-front-filters-size-15">
                <label class="cnrs-dm-front-single-input-label" for="cnrs-dm-front-filter-date-selector"><?php echo __('Year', 'cnrs-data-manager') ?></label>
                <select id="cnrs-dm-front-filter-date-selector" name="cdm-date">
                    <option<?php echo $_GET['cdm-date'] === 'all' ? ' selected' : '' ?> value="all"><?php echo __('All years', 'cnrs-data-manager') ?></option>
                    <?php foreach ($filters['dates'] as $date): ?>
                        <option<?php echo (int) $_GET['cdm-date'] === (int) $date['title'] ? ' selected' : '' ?> value="<?php echo $date['title'] ?>"><?php echo $date['title'] ?> (<?php echo $date['count'] ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="cnrs-dm-front-filter cnrs-dm-front-filter-column cnrs-dm-front-filters-modules cnrs-dm-front-filters-size-15">
                <label class="cnrs-dm-front-single-input-label" for="cnrs-dm-front-filter-type-selector"><?php echo __('Document type', 'cnrs-data-manager') ?></label>
                <select id="cnrs-dm-front-filter-type-selector" name="cdm-type">
                    <option<?php echo $_GET['cdm-type'] === 'all' ? ' selected' : '' ?> value="all"><?php echo __('All types', 'cnrs-data-manager') ?></option>
                    <?php foreach ($filters['types'] as $type): ?>
                        <option<?php echo $_GET['cdm-type'] === $type['title'] ? ' selected' : '' ?> value="<?php echo $type['title'] ?>"><?php echo $type['title'] ?> (<?php echo $type['count'] ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="cnrs-dm-front-filter cnrs-dm-front-filter-column cnrs-dm-front-filters-modules cnrs-dm-front-filters-size-15">
                <label class="cnrs-dm-front-single-input-label" for="cnrs-dm-front-filter-guardianship-selector"><?php echo __('Guardianship', 'cnrs-data-manager') ?></label>
                <select id="cnrs-dm-front-filter-guardianship-selector" name="cdm-guardianship">
                    <option<?php echo $_GET['cdm-guardianship'] === 'all' ? ' selected' : '' ?> value="all"><?php echo __('All guardianships', 'cnrs-data-manager') ?></option>
                    <?php foreach ($filters['guardianships'] as $guardianship): ?>
                        <option<?php echo $_GET['cdm-guardianship'] === $guardianship['title'] ? ' selected' : '' ?> value="<?php echo $guardianship['title'] ?>"><?php echo $guardianship['title'] ?> (<?php echo $guardianship['count'] ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="cnrs-dm-front-filter cnrs-dm-front-filter-column cnrs-dm-front-filters-modules cnrs-dm-front-filters-size-15">
                <label class="cnrs-dm-front-single-input-label" for="cnrs-dm-front-filter-category-selector"><?php echo __('Discipline', 'cnrs-data-manager') ?></label>
                <select id="cnrs-dm-front-filter-category-selector" name="cdm-category">
                    <option<?php echo $_GET['cdm-category'] === 'all' ? ' selected' : '' ?> value="all"><?php echo __('All disciplines', 'cnrs-data-manager') ?></option>
                    <?php foreach ($filters['categories'] as $category): ?>
                        <option<?php echo $_GET['cdm-category'] === (strlen($category['title']) > 0 ? $category['title'] : 'unclassified') ? ' selected' : '' ?> value="<?php echo strlen($category['title']) > 0 ? $category['title'] : 'unclassified' ?>"><?php echo strlen($category['title']) > 0 ? $category['title'] : __('Unclassified', 'cnrs-data-manager') ?> (<?php echo $category['count'] ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="cnrs-dm-front-filter cnrs-dm-front-filter-column cnrs-dm-front-filters-modules cnrs-dm-front-filters-size-15">
                <label class="cnrs-dm-front-single-input-label" for="cnrs-dm-front-filter-search-input"><?php echo __('Search', 'cnrs-data-manager') ?></label>
                <input type="search" name="cdm-search" id="cnrs-dm-front-filter-search-input" value="<?php echo $_GET['cdm-search'] ?>">
            </div>
        </div>
        <div id="cnrs-dm-front-filter-button-container">
            <button type="submit" id="cnrs-dm-front-publication-submit" class="cnrs-dm-front-submit-btn">
                <?php echo __('Apply filters', 'cnrs-data-manager') ?>
            </button>
        </div>
    </form>
    <div id="cnrs-dm-front-publication-loader-wrapper" class="hide">
        <div id="cnrs-dm-front-loader-icon">
            <?php include_once(CNRS_DATA_MANAGER_DEPORTED_SVG_PATH . '/loader.svg') ?>
        </div>
    </div>
    <div id="cnrs-dm-front-publications-list">
        <?php include_once(CNRS_DATA_MANAGER_PATH . '/templates/includes/publications.php') ?>
    </div>
</div>
<!-- End CNRS Data Manager render -->
