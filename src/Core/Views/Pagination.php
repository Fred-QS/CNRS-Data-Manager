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
$fromModule = true;
?>
<!-- Start CNRS Data Manager pagination section -->
<div class="cnrs-dm-front-pagination-module-wrapper" data-shortcode="cnrs-data-manager-shortcode-<?php echo $shortCodesCounter ?>" style="display: none">
    <?php include(CNRS_DATA_MANAGER_PATH . '/templates/includes/pagination.php') ?>
</div>
<?php if ($isSilentPagination === false): ?>
<script id="cnrs-dm-front-temporary-script">
    window.addEventListener('load', function (){
        document.querySelector('.cnrs-dm-front-pagination-module-wrapper').removeAttribute('style');
        document.querySelector('#cnrs-dm-front-temporary-script').remove();
    });
</script>
<?php endif; ?>
<!-- End CNRS Data Manager pagination section -->
