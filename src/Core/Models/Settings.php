<?php

namespace CnrsDataManager\Core\Models;

use CnrsDataManager\Core\Controllers\HttpClient;

class Settings
{
    /**
     * Retrieves the settings from the WordPress database.
     *
     * This method queries the WordPress database to retrieve the settings
     * stored in the `wp_{$wpdb->prefix}cnrs_data_manager_settings` table.
     *
     * @return array An array of settings objects.
     * @global wpdb $wpdb The WordPress database object.
     */
    public static function getSettings(): array
    {
        global $wpdb;
        $result = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}cnrs_data_manager_settings", ARRAY_A );
        return $result[0];
    }

    /**
     * Updates the filename setting in the WordPress database.
     *
     * This method updates the filename setting in the `wp_{$wpdb->prefix}cnrs_data_manager_settings`
     * table based on the value provided in the $_POST['cnrs-dm-filename'] variable. The new filename
     * value is prepared using stripslashes() to remove any slashes that might have been added to
     * escape special characters. The update operation is executed using the global $wpdb object.
     *
     * @return void
     * @global wpdb $wpdb The WordPress database object.
     */
    public static function updateFilename(): void
    {
        if (isset($_POST['cnrs-dm-filename']) && HttpClient::call($_POST['cnrs-dm-filename'], true)) {
            global $wpdb;
            $wpdb->query($wpdb->prepare(
                "UPDATE {$wpdb->prefix}cnrs_data_manager_settings SET filename=%s",
                stripslashes($_POST['cnrs-dm-filename'])
            ));
        }
    }

    public static function update(): void
    {
        if (isset($_POST['cnrs-dm-mode'])
            && isset($_POST['cnrs-dm-selector-teams'])
            && isset($_POST['cnrs-dm-selector-services'])
            && isset($_POST['cnrs-dm-selector-platforms'])
            && isset($_POST['cnrs-data-manager-categories-list-teams'])
            && isset($_POST['cnrs-data-manager-categories-list-services'])
            && isset($_POST['cnrs-data-manager-categories-list-platforms'])
            && isset($_POST['cnrs-dm-filter-module']))
        {
            $post = [
                'mode' => stripslashes($_POST['cnrs-dm-mode']),
                'teams_category' => stripslashes($_POST['cnrs-data-manager-categories-list-teams']),
                'teams_view_selector' => stripslashes($_POST['cnrs-dm-selector-teams']),
                'services_category' => stripslashes($_POST['cnrs-data-manager-categories-list-services']),
                'services_view_selector' => stripslashes($_POST['cnrs-dm-selector-services']),
                'platforms_category' => stripslashes($_POST['cnrs-data-manager-categories-list-platforms']),
                'platforms_view_selector' => stripslashes($_POST['cnrs-dm-selector-platforms']),
                'category_template' => stripslashes($_POST['cnrs-dm-category-template']) === 'on' ? 1 : 0,
                'silent_pagination' => stripslashes($_POST['cnrs-dm-pagination-ajax-checkbox']) === 'on' ? 1 : 0,
                'filter_modules' => !empty($_POST['cnrs-dm-filter-module']) ? stripslashes(implode(',', $_POST['cnrs-dm-filter-module'])) : 'none',
            ];
            global $wpdb;
            $currents = $wpdb->get_results( "SELECT teams_category, services_category, platforms_category FROM {$wpdb->prefix}cnrs_data_manager_settings ", ARRAY_A );
            $currentTeams = (int) $currents[0]['teams_category'];
            $currentServices = (int) $currents[0]['services_category'];
            $currentPlatforms = (int) $currents[0]['platforms_category'];

            $relationTable = $wpdb->prefix . 'cnrs_data_manager_relations';
            if ($currentTeams !== (int) $post['teams_category']) {
                $wpdb->delete($relationTable, ['type' => 'teams']);
            }

            if ($currentServices !== (int) $post['services_category']) {
                $wpdb->delete($relationTable, ['type' => 'services']);
            }

            if ($currentPlatforms !== (int) $post['platforms_category']) {
                $wpdb->delete($relationTable, ['type' => 'platforms']);
            }

            $wpdb->query($wpdb->prepare(
                "UPDATE {$wpdb->prefix}cnrs_data_manager_settings SET teams_category=%d,teams_view_selector=%d,services_category=%d,services_view_selector=%d,platforms_category=%d,platforms_view_selector=%d,mode=%s,category_template=%d,silent_pagination=%d,filter_modules=%s",
                $post['teams_category'],
                $post['teams_view_selector'],
                $post['services_category'],
                $post['services_view_selector'], 
                $post['platforms_category'],
                $post['platforms_view_selector'],
                $post['mode'],
                $post['category_template'],
                $post['silent_pagination'],
                $post['filter_modules']
            ));
        }
    }

    /**
     * Retrieve the default marker coordinates.
     *
     * This method queries the database to fetch the latitude and longitude
     * values of the default marker from the cnrs_data_manager_settings table.
     *
     * @return array An associative array containing the latitude and longitude
     *               values of the default marker. The array will have the
     *               following structure:
     *               [
     *                   'lat' => The latitude value as a string,
     *                   'lng' => The longitude value as a string
     *               ]
     */
    public static function getDefaultMarker(): array
    {
        global $wpdb;
        $result = $wpdb->get_results( "SELECT default_latitude as lat, default_longitude as lng FROM {$wpdb->prefix}cnrs_data_manager_settings", ARRAY_A );
        return $result[0];
    }

    /**
     * Update the map markers in the database.
     *
     * This method is used to update the map markers in the cnrs_data_manager_map_markers table based on the
     * data received from the $_POST superglobal array.
     *
     * @return void
     */
    public static function updateMarkers(): void
    {
        if (isset($_POST['action'])) {
            global $wpdb;
            if ($_POST['action'] === 'update-default-marker') {
                $wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}cnrs_data_manager_settings SET default_latitude=%s,default_longitude=%s", stripslashes($_POST['cnrs-dm-main-lat']), stripslashes($_POST['cnrs-dm-main-lng'])));
                $prepareMapSettings = [
                    'sunlight' => isset($_POST['cnrs-dm-map-settings-sunlight']) ? (int) stripslashes($_POST['cnrs-dm-map-settings-sunlight']) : 0,
                    'view' => stripslashes($_POST['cnrs-dm-map-settings-view']),
                    'stars' => isset($_POST['cnrs-dm-map-settings-stars']) ? (int) stripslashes($_POST['cnrs-dm-map-settings-stars']) : 0,
                    'black_bg' => isset($_POST['cnrs-dm-map-settings-black_bg']) ? (int) stripslashes($_POST['cnrs-dm-map-settings-black_bg']) : 0,
                    'atmosphere' => isset($_POST['cnrs-dm-map-settings-atmosphere']) ? (int) stripslashes($_POST['cnrs-dm-map-settings-atmosphere']) : 0,
                ];
                $wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}cnrs_data_manager_map_settings SET sunlight=%d,view=%s,stars=%d,black_bg=%d,atmosphere=%d", $prepareMapSettings['sunlight'],$prepareMapSettings['view'],$prepareMapSettings['stars'],$prepareMapSettings['black_bg'],$prepareMapSettings['atmosphere']));
            } else if ($_POST['action'] === 'update-markers') {
                $posts = [];
                foreach ($_POST as $key => $value) {
                    if (stripos($key, 'cnrs-dm-marker-title-') !== false) {
                        $posts[] = $key;
                    }
                }

                $creates = [];
                $updates = [];
                $deletes = [];
                $exists = $wpdb->get_results( "SELECT id FROM {$wpdb->prefix}cnrs_data_manager_map_markers", ARRAY_A );
                $ids = array_column($exists, 'id');

                foreach ($posts as $post) {
                    $index = (int) str_replace('cnrs-dm-marker-title-', '', $post);
                    if ($_POST['cnrs-dm-marker-id-' . $index] === null) {
                        $creates[] = [
                            'title' => $_POST['cnrs-dm-marker-title-' . $index],
                            'lat' => $_POST['cnrs-dm-marker-lat-' . $index],
                            'lng' => $_POST['cnrs-dm-marker-lng-' . $index]
                        ];
                    } else {
                        $updates[] = [
                            'title' => $_POST['cnrs-dm-marker-title-' . $index],
                            'id' => $_POST['cnrs-dm-marker-id-' . $index],
                            'lat' => $_POST['cnrs-dm-marker-lat-' . $index],
                            'lng' => $_POST['cnrs-dm-marker-lng-' . $index]
                        ];
                    }
                }
                foreach ($creates as $c) {
                    $wpdb->insert("{$wpdb->prefix}cnrs_data_manager_map_markers", $c);
                }
                foreach ($updates as $u) {
                    $id = $u['id'];
                    unset($u['id']);
                    $wpdb->update("{$wpdb->prefix}cnrs_data_manager_map_markers", $u, ['id' => $id]);
                }
                foreach ($ids as $id) {
                    if (!in_array($id, array_column($updates, 'id'))) {
                        $deletes[] = $id;
                    }
                }
                foreach ($deletes as $delete) {
                    $wpdb->delete("{$wpdb->prefix}cnrs_data_manager_map_markers", ['id' => $delete]);
                }
            }
        }
    }

    /**
     * Retrieves the display mode from the WordPress database.
     *
     * This method queries the WordPress database to retrieve the display mode
     * stored in the `wp_{$wpdb->prefix}cnrs_data_manager_settings` table.
     *
     * @return string The display mode.
     * @global wpdb $wpdb The WordPress database object.
     */
    public static function getDisplayMode(): string
    {
        global $wpdb;
        $settings = $wpdb->get_results( "SELECT mode FROM {$wpdb->prefix}cnrs_data_manager_settings", ARRAY_A );
        return $settings[0]['mode'];
    }

    /**
     * Checks if a specific selector is available in the WordPress database.
     *
     * This method queries the WordPress database to check if the specified
     * selector is available in the `wp_{$wpdb->prefix}cnrs_data_manager_settings` table.
     *
     * @param string $type The selector type.
     * @return bool Returns true if the selector is available, false otherwise.
     * @global wpdb $wpdb The WordPress database object.
     */
    public static function isSelectorAvailable(string $type): bool
    {
        global $wpdb;
        $settings = $wpdb->get_results( "SELECT {$type}_view_selector FROM {$wpdb->prefix}cnrs_data_manager_settings", ARRAY_A );
        return $settings[0][$type . '_view_selector'] === '1';
    }

    /**
     * Deploys the category template based on the settings in the database.
     *
     * Retrieves the value of the 'category_template' field from the 'cnrs_data_manager_settings'
     * table in the WordPress database and determines whether to deploy or remove the category
     * template files based on the returned value.
     *
     * @return void
     */
    public static function deployCategoryTemplate(): void
    {
        global $wpdb;
        $settings = $wpdb->get_results( "SELECT category_template FROM {$wpdb->prefix}cnrs_data_manager_settings", ARRAY_A );
        $newFiles = [
            'category' => get_theme_root() . '/' . wp_get_theme()->get_stylesheet() . '/category.php',
            'archive' => get_theme_root() . '/' . wp_get_theme()->get_stylesheet() . '/archive.php',
            'project' => get_theme_root() . '/' . wp_get_theme()->get_stylesheet() . '/project.php',
        ];
        if ((int) $settings[0]['category_template'] === 1) {
            foreach ($newFiles as $name => $newFile) {
                if (!file_exists($newFile)) {
                    $template = CNRS_DATA_MANAGER_PATH . '/templates/samples/' . $name . '.php';
                    copy($template, $newFile);
                }
            }
        } else if ((int) $settings[0]['category_template'] === 0) {
            foreach ($newFiles as $currentFile) {
                if (file_exists($currentFile)) {
                    unlink($currentFile);
                }
            }
        }
    }

    /**
     * Retrieve the active filters.
     *
     * This method fetches the active filters from the cnrs_data_manager_settings
     * table in the database. If the retrieved result is 'none', it returns
     * an empty array indicating that no filters are active. Otherwise, it splits
     * the result by comma (',') and returns it as an array.
     *
     * @return array An array containing the active filters. If no filters are
     *               active, an empty array is returned.
     */
    public static function getFilters(): array
    {
        global $wpdb;
        $result = $wpdb->get_results( "SELECT filter_modules FROM {$wpdb->prefix}cnrs_data_manager_settings", ARRAY_A );
        $filters = $result[0]['filter_modules'];
        return $filters === 'none' ? [] : explode(',', $filters);
    }


    /**
     * Retrieves the pagination type from the database.
     *
     * Retrieves the value of the 'silent_pagination' field from the 'cnrs_data_manager_settings'
     * table in the WordPress database and determines the pagination type based on the returned value.
     *
     * @return bool Returns true if the pagination type is silent pagination, false otherwise.
     */
    public static function getPaginationType(): bool
    {
        global $wpdb;
        $result = $wpdb->get_results( "SELECT silent_pagination FROM {$wpdb->prefix}cnrs_data_manager_settings", ARRAY_A );
        $filters = (int) $result[0]['silent_pagination'];
        return $filters === 1;
    }

    public static function getSubCategoriesFromParentSlug(string $slug): array
    {
        global $wpdb;
        $parent = $wpdb->get_results( "SELECT term_id FROM {$wpdb->prefix}terms WHERE slug = '{$slug}'", ARRAY_A );
        if (empty($parent)) {
            return [];
        }

        $parent_term_id = (int) $parent[0]['term_id'];
        return $wpdb->get_results( "SELECT {$wpdb->prefix}terms.slug, {$wpdb->prefix}terms.name FROM {$wpdb->prefix}term_taxonomy INNER JOIN {$wpdb->prefix}terms ON {$wpdb->prefix}term_taxonomy.term_id = {$wpdb->prefix}terms.term_id WHERE {$wpdb->prefix}term_taxonomy.parent = {$parent_term_id} ");
    }
}