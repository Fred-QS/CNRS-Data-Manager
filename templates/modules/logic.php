<?php

// Main cat name
ob_start();
single_cat_title();
$catName = ob_get_clean();

$cat = get_the_category(get_the_ID());
$parents = [];
if (!empty($cat)) {
    // Child cat details
    $name = trim($cat[0]->name);
    $slug = trim($cat[0]->slug);

    // Get terms structure
    $uri = get_category_parents($cat[0]->term_id);
    $parents = trim($uri) === '' ? [] : explode('/', $uri);
    $parents = array_filter($parents, function ($segment) {
        return strlen($segment) > 0;
    });
}
$candidatingCatFr = 'Recrutement';
$candidatingCatEn = 'Recruitment';

// Filter string for title
$toSentencePart = lcfirst(str_replace(['É'], ['é'], $catName));
if (in_array($toSentencePart, ['recrutement', 'recruitment'], true)) {
    $toSentencePart .= 's';
}

