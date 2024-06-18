<?php

if (get_queried_object()->name !== 'project') {
    // Redirect all archived posts url to 404 error
    global $wp_query;
    $wp_query->set_404();
    status_header(404);
    get_template_part(404);
    exit();
} else {
    // Only projects posts displayed at /project uri
    echo do_shortcode('[cnrs-data-manager type="categories"]');
}
