<!-- Start CNRS Data Manager filter section -->
<form method="get" class="cnrs-dm-front-filters-wrapper">
    <div id="cnrs-dm-front-filters-container">
        <div id="cnrs-dm-front-filter-left-side">
            <div class="cnrs-dm-front-filter" id="cnrs-dm-front-filter-pagi-container">
                <label class="cnrs-dm-front-pagination-text" for="cnrs-dm-front-filter-pagination-selector"><?php echo __('Number of elements per page', 'cnrs-data-manager') ?>:</label>
                <select id="cnrs-dm-front-filter-pagination-selector" name="cdm-pagi">
                    <option<?php echo isset($_GET['cdm-pagi']) && $_GET['cdm-pagi'] === '5' ? ' selected' : '' ?> value="5">5</option>
                    <option<?php echo (isset($_GET['cdm-pagi']) && $_GET['cdm-pagi'] === '10') || !isset($_GET['cdm-pagi']) ? ' selected' : '' ?> value="10">10</option>
                    <option<?php echo isset($_GET['cdm-pagi']) && $_GET['cdm-pagi'] === '20' ? ' selected' : '' ?> value="20">20</option>
                    <option<?php echo isset($_GET['cdm-pagi']) && $_GET['cdm-pagi'] === '50' ? ' selected' : '' ?> value="50">50</option>
                    <option<?php echo isset($_GET['cdm-pagi']) && $_GET['cdm-pagi'] === '100' ? ' selected' : '' ?> value="100">100</option>
                </select>
            </div>
        </div>
        <div id="cnrs-dm-front-filter-right-side">
            <div class="cnrs-dm-front-filter cnrs-dm-front-filter-column" id="cnrs-dm-front-filter-type-container">
                <label class="cnrs-dm-front-single-input-label" for="cnrs-dm-front-filter-type-selector"><?php echo __('Filter by', 'cnrs-data-manager') ?></label>
                <select id="cnrs-dm-front-filter-type-selector" name="cdm-type">
                    <option<?php echo (isset($_GET['cdm-type']) && $_GET['cdm-type'] === 'all') || !isset($_GET['cdm-type']) ? ' selected' : '' ?> value="all"><?php echo __('All', 'cnrs-data-manager') ?></option>
                    <option<?php echo isset($_GET['cdm-type']) && $_GET['cdm-type'] === 'lastname' ? ' selected' : '' ?> value="lastname"><?php echo __('Lastname', 'cnrs-data-manager') ?></option>
                    <option<?php echo isset($_GET['cdm-type']) && $_GET['cdm-type'] === 'firstname' ? ' selected' : '' ?> value="firstname"><?php echo __('Firstname', 'cnrs-data-manager') ?></option>
                    <option<?php echo isset($_GET['cdm-type']) && $_GET['cdm-type'] === 'status' ? ' selected' : '' ?> value="status"><?php echo __('Status', 'cnrs-data-manager') ?></option>
                    <option<?php echo isset($_GET['cdm-type']) && $_GET['cdm-type'] === 'membership' ? ' selected' : '' ?> value="membership"><?php echo __('Membership', 'cnrs-data-manager') ?></option>
                </select>
            </div>
            <div class="cnrs-dm-front-filter cnrs-dm-front-filter-column" id="cnrs-dm-front-filter-search-container">
                <label class="cnrs-dm-front-single-input-label" for="cnrs-dm-front-filter-search-input"><?php echo __('Search by person', 'cnrs-data-manager') ?></label>
                <input type="search" name="cdm-search" id="cnrs-dm-front-filter-search-input" value="<?php echo $_GET['cdm-search'] ?>">
            </div>
        </div>
    </div>
    <div id="cnrs-dm-front-filter-button-container">
        <button type="submit" class="cnrs-dm-front-submit-btn">
            <?php echo __('Apply filters', 'cnrs-data-manager') ?>
        </button>
    </div>
</form>
<!-- End CNRS Data Manager filter section -->