<?php
global $wp_query;
$current = max(1, get_query_var('paged'));
$pagination = [
    'pages' => (int) $wp_query->max_num_pages,
    'displayed_items' => $wp_query->post_count,
    'count' => get_queried_object()->count,
    'current' => $current,
    'next' => $current + 1 <= $wp_query->max_num_pages ? $current + 1 : null,
    'previous' => $current - 1 <= 0 ? null : $current - 1,
];
?>
<!-- Start CNRS Data Manager pagination section -->
<div class="cnrs-dm-front-pagination-module-wrapper" data-shortcode="cnrs-data-manager-shortcode-<?= $shortCodesCounter ?>">
    <?php include(CNRS_DATA_MANAGER_PATH . '/templates/includes/pagination.php') ?>
</div>
<!-- End CNRS Data Manager pagination section -->
