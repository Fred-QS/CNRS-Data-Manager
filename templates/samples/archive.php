<?php

if (get_queried_object()->name !== 'project') {
    // Redirect all archived posts url to 404 error
    header('Location: /404');
} else {
    // Only projects posts displayed at /project uri
    include_once(__DIR__ . '/project.php');
}