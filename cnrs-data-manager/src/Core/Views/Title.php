<?php

$title = "<span data-shortcode='cnrs-data-manager-shortcode-{$shortCodesCounter}'>" . __('No member available', 'cnrs-data-manager') . "</span>";
if (in_array($entity, ['teams', 'services', 'platforms'], true) && $displayMode === 'page') {
    $message = [
        'teams' => __('No members in this team', 'cnrs-data-manager'),
        'services' => __('No members in this service', 'cnrs-data-manager'),
        'platforms' => __('No members in this platform', 'cnrs-data-manager'),
    ];
    if (!isset($_GET['cnrs-dm-ref']) || ctype_digit($_GET['cnrs-dm-ref']) === false) {
        $title = "<span data-shortcode='cnrs-data-manager-shortcode-{$shortCodesCounter}'>" . $message[$entity] . "</span>";
    } else {
        $id = (int) $_GET['cnrs-dm-ref'];
        $catName = get_the_title($id);
        if (strlen($catName) === 0) {
            $title = "<span data-shortcode='cnrs-data-manager-shortcode-{$shortCodesCounter}'>" . $message[$entity] . "</span>";
        } else {
            $message = [
                'teams' => __('%s team members', 'cnrs-data-manager'),
                'services' => __('%s service members', 'cnrs-data-manager'),
                'platforms' => __('%s platform members', 'cnrs-data-manager'),
            ];
            $title = "<span data-shortcode='cnrs-data-manager-shortcode-{$shortCodesCounter}'>" . sprintf($message[$entity], $catName) . "</span>";
        }
    }
}

echo $title;