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
        $result = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}cnrs_data_manager_settings", ARRAY_A);
        $result['teams_category'] = json_decode($result['teams_category'], true);
        $result['services_category'] = json_decode($result['services_category'], true);
        $result['platforms_category'] = json_decode($result['platforms_category'], true);
        return $result;
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
            && isset($_POST['cnrs-dm-project-default-image-url'])
            && isset($_POST['cnrs-dm-project-default-thumbnail-url'])
            && isset($_POST['cnrs-dm-filter-module']))
        {
            $post = [
                'mode' => stripslashes($_POST['cnrs-dm-mode']),
                'teams_category' => json_encode($_POST['cnrs-data-manager-categories-list-teams']),
                'teams_view_selector' => stripslashes($_POST['cnrs-dm-selector-teams']),
                'services_category' => json_encode($_POST['cnrs-data-manager-categories-list-services']),
                'services_view_selector' => stripslashes($_POST['cnrs-dm-selector-services']),
                'platforms_category' => json_encode($_POST['cnrs-data-manager-categories-list-platforms']),
                'platforms_view_selector' => stripslashes($_POST['cnrs-dm-selector-platforms']),
                'category_template' => stripslashes($_POST['cnrs-dm-category-template']) === 'on' ? 1 : 0,
                'silent_pagination' => (isset($_POST['cnrs-dm-pagination-ajax-checkbox']) && stripslashes($_POST['cnrs-dm-pagination-ajax-checkbox']) === 'on') ? 1 : 0,
                'filter_modules' => !empty($_POST['cnrs-dm-filter-module']) ? stripslashes(implode(',', $_POST['cnrs-dm-filter-module'])) : 'none',
                'candidating_email' => strlen(stripslashes($_POST['cnrs-dm-candidating-email'])) > 0 ? stripslashes($_POST['cnrs-dm-candidating-email']) : null,
                'project_default_image_url' => stripslashes($_POST['cnrs-dm-project-default-image-url']),
                'project_default_thumbnail_url' => stripslashes($_POST['cnrs-dm-project-default-thumbnail-url']),
            ];

            global $wpdb;
            $currents = $wpdb->get_row( "SELECT teams_category, services_category, platforms_category FROM {$wpdb->prefix}cnrs_data_manager_settings ", ARRAY_A );
            $currentTeams = json_decode($currents['teams_category'], true);
            $currentServices = json_decode($currents['services_category'], true);
            $currentPlatforms = json_decode($currents['platforms_category'], true);

            $relationTable = $wpdb->prefix . 'cnrs_data_manager_relations';
            if (cnrs_categories_settings_have_changed($currentTeams, json_decode($post['teams_category'], true)) === true) {
                $wpdb->delete($relationTable, ['type' => 'teams']);
            }

            if (cnrs_categories_settings_have_changed($currentServices, json_decode($post['services_category'], true)) === true) {
                $wpdb->delete($relationTable, ['type' => 'services']);
            }

            if (cnrs_categories_settings_have_changed($currentPlatforms, json_decode($post['platforms_category'], true)) === true) {
                $wpdb->delete($relationTable, ['type' => 'platforms']);
            }

            if ($post['candidating_email'] === null) {
                $wpdb->query("UPDATE {$wpdb->prefix}cnrs_data_manager_settings SET teams_category='{$post['teams_category']}',teams_view_selector={$post['teams_view_selector']},services_category='{$post['services_category']}',services_view_selector={$post['services_view_selector']},platforms_category='{$post['platforms_category']}',platforms_view_selector={$post['platforms_view_selector']},mode='{$post['mode']}',category_template={$post['category_template']},silent_pagination={$post['silent_pagination']},filter_modules='{$post['filter_modules']}',project_default_image_url='{$post['project_default_image_url']}',project_default_thumbnail_url='{$post['project_default_thumbnail_url']}',candidating_email=NULL");
            } else {
                $wpdb->query("UPDATE {$wpdb->prefix}cnrs_data_manager_settings SET teams_category='{$post['teams_category']}',teams_view_selector={$post['teams_view_selector']},services_category='{$post['services_category']}',services_view_selector={$post['services_view_selector']},platforms_category='{$post['platforms_category']}',platforms_view_selector={$post['platforms_view_selector']},mode='{$post['mode']}',category_template={$post['category_template']},silent_pagination={$post['silent_pagination']},filter_modules='{$post['filter_modules']}',project_default_image_url='{$post['project_default_image_url']}',project_default_thumbnail_url='{$post['project_default_thumbnail_url']}',candidating_email='{$post['candidating_email']}'");
            }

            $wpdb->query("DELETE FROM {$wpdb->prefix}cnrs_data_manager_hidden_filters");
            if (isset($_POST['cnrs-dm-filter-allowed'])) {
                foreach ($_POST['cnrs-dm-filter-allowed'] as $id) {
                    $wpdb->insert(
                        "{$wpdb->prefix}cnrs_data_manager_hidden_filters",
                        ['term_id' => $id],
                        ['%d']
                    );
                }
            }

            $wpdb->query("DELETE FROM {$wpdb->prefix}cnrs_data_manager_candidating");
            if (isset($_POST['cnrs-dm-candidating'])) {
                foreach ($_POST['cnrs-dm-candidating'] as $id) {
                    $wpdb->insert(
                        "{$wpdb->prefix}cnrs_data_manager_candidating",
                        ['term_id' => $id],
                        ['%d']
                    );
                }
            }

            $wpdb->query("DELETE FROM {$wpdb->prefix}cnrs_data_manager_articles_preview_design");
            if (isset($_POST['cnrs-dm-design'])) {
                foreach ($_POST['cnrs-dm-design'] as $term_id => $template) {
                    $wpdb->insert(
                        "{$wpdb->prefix}cnrs_data_manager_articles_preview_design",
                        ['term_id' => $term_id, 'design' => $template],
                        ['%d', '%s']
                    );
                }
            }

            self::updateFilename();
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
        $settings = $wpdb->get_row( "SELECT mode FROM {$wpdb->prefix}cnrs_data_manager_settings", ARRAY_A );
        return $settings['mode'];
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
        $settings = $wpdb->get_row( "SELECT {$type}_view_selector FROM {$wpdb->prefix}cnrs_data_manager_settings", ARRAY_A );
        return $settings[$type . '_view_selector'] === '1';
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
            'category.php' => get_theme_root() . '/' . wp_get_theme()->get_stylesheet() . '/category.php',
            'archive.php' => get_theme_root() . '/' . wp_get_theme()->get_stylesheet() . '/archive.php',
            'cnrs-script.js' => get_theme_root() . '/' . wp_get_theme()->get_stylesheet() . '/cnrs-script.js',
        ];
        if ((int) $settings[0]['category_template'] === 1) {
            foreach ($newFiles as $name => $newFile) {
                if (!file_exists($newFile)) {
                    $template = CNRS_DATA_MANAGER_PATH . '/templates/samples/' . $name;
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
        $result = $wpdb->get_row( "SELECT filter_modules FROM {$wpdb->prefix}cnrs_data_manager_settings", ARRAY_A );
        $filters = $result['filter_modules'];
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
        $result = $wpdb->get_row( "SELECT silent_pagination FROM {$wpdb->prefix}cnrs_data_manager_settings", ARRAY_A );
        $filters = (int) $result['silent_pagination'];
        return $filters === 1;
    }

    public static function getSubCategoriesFromParentSlug(string $slug): array
    {
        global $wpdb;
        $parent = $wpdb->get_row( "SELECT term_id FROM {$wpdb->prefix}terms WHERE slug = '{$slug}'", ARRAY_A );
        if (empty($parent)) {
            return [];
        }

        $parent_term_id = (int) $parent['term_id'];
        return $wpdb->get_results( "SELECT {$wpdb->prefix}terms.slug, {$wpdb->prefix}terms.name FROM {$wpdb->prefix}term_taxonomy INNER JOIN {$wpdb->prefix}terms ON {$wpdb->prefix}term_taxonomy.term_id = {$wpdb->prefix}terms.term_id WHERE {$wpdb->prefix}term_taxonomy.parent = {$parent_term_id} ");
    }

    /**
     * Retrieves the debug mode and email from the database.
     *
     * Retrieves the value of the 'debug_email' field from the 'cnrs_data_manager_mission_form_settings' table
     * in the WordPress database, based on the value of the 'debug_mode' field being 0 (disabled). If a matching
     * setting is found, the debug email is returned; otherwise, null is returned.
     *
     * @return string|null Returns the debug email if debug mode is enabled, null otherwise.
     */
    public static function getDebugMode(): string|null
    {
        global $wpdb;
        $settings = $wpdb->get_row("SELECT debug_email FROM {$wpdb->prefix}cnrs_data_manager_mission_form_settings WHERE debug_mode = 1");
        return $settings?->debug_email;
    }

    /**
     * Returns the number of days limit from the database settings table.
     *
     * @return int The number of days limit. Defaults to 5 if no settings found.
     */
    public static function getDaysLimit(): int
    {
        global $wpdb;
        $settings = $wpdb->get_row("SELECT days_limit FROM {$wpdb->prefix}cnrs_data_manager_mission_form_settings");
        return $settings !== null ? (int) $settings->days_limit : 5;
    }

    /**
     * Retrieves the month limit from the database.
     *
     * This method fetches the month limit from the "cnrs_data_manager_mission_form_settings" table in the database.
     * If the month limit is not found in the database, a default value of 20 will be returned.
     *
     * @return int The month limit retrieved from the database, or the default value if not found.
     * @global wpdb $wpdb The global WordPress database access object.
     */
    public static function getMonthLimit(): int
    {
        global $wpdb;
        $settings = $wpdb->get_row("SELECT month_limit FROM {$wpdb->prefix}cnrs_data_manager_mission_form_settings");
        return $settings !== null ? (int) $settings->month_limit : 20;
    }

    /**
     * Retrieves the admin emails from the database.
     *
     * This method fetches the admin emails from the "cnrs_data_manager_mission_form_settings" table in the database.
     *
     * @return array The admin emails retrieved from the database as an associative array.
     * @global wpdb $wpdb The global WordPress database access object.
     */
    public static function getAdminEmails(): array
    {
        global $wpdb;
        $settings = $wpdb->get_row("SELECT admin_emails FROM {$wpdb->prefix}cnrs_data_manager_mission_form_settings");
        return json_decode($settings->admin_emails, true);
    }

    /**
     * Retrieves the generic email from the database.
     *
     * This method fetches the generic email from the "cnrs_data_manager_mission_form_settings" table in the database.
     *
     * @return string|null The generic email retrieved from the database.
     * @global wpdb $wpdb The global WordPress database access object.
     */
    public static function getGenericEmail(): ?string
    {
        global $wpdb;
        $settings = $wpdb->get_row("SELECT generic_email, generic_active FROM {$wpdb->prefix}cnrs_data_manager_mission_form_settings");
        return $settings->generic_active === '1' ? $settings->generic_email : null;
    }

    /**
     * Retrieves the hidden term IDs from the database.
     *
     * This method fetches the term IDs of hidden filters from the "cnrs_data_manager_hidden_filters" table in the database.
     *
     * @return int[] The array of hidden term IDs retrieved from the database.
     * @global wpdb $wpdb The global WordPress database access object.
     */
    public static function getHiddenTermsIds(): array
    {
        global $wpdb;
        $hidden = $wpdb->get_results("SELECT term_id FROM {$wpdb->prefix}cnrs_data_manager_hidden_filters", ARRAY_A);
        return array_map(function (array $row) {return (int) $row['term_id'];}, $hidden);
    }

    /**
     * Retrieves an array of candidating term IDs from the database.
     *
     * This method fetches the term IDs from the "cnrs_data_manager_candidating" table in the database and returns them as an array.
     *
     * @return array An array of candidating term IDs.
     * @global wpdb $wpdb The global WordPress database access object.
     */
    public static function getCandidatingTermsIds(): array
    {
        global $wpdb;
        $ids = $wpdb->get_results("SELECT term_id FROM {$wpdb->prefix}cnrs_data_manager_candidating", ARRAY_A);
        return array_map(function (array $row) {return (int) $row['term_id'];}, $ids);
    }

    /**
     * Retrieves the candidating email from the database.
     *
     * This method fetches the candidating email from the "cnrs_data_manager_settings" table in the database.
     *
     * @return string|null The candidating email retrieved from the database, or null if not found.
     * @global wpdb $wpdb The global WordPress database access object.
     */
    public static function getCandidatingEmail(): string|null
    {
        global $wpdb;
        $settings = $wpdb->get_row("SELECT candidating_email FROM {$wpdb->prefix}cnrs_data_manager_settings");
        return $settings->candidating_email;
    }

    /**
     * Retrieves an array of designs from the database.
     *
     * @return array An array of designs in the database.
     * @global wpdb $wpdb The WordPress database object.
     *
     */
    public static function getDesigns(): array
    {
        global $wpdb;
        return $wpdb->get_results("SELECT * FROM {$wpdb->prefix}cnrs_data_manager_articles_preview_design", ARRAY_A);
    }

    /**
     * Retrieves the design from the database associated with the given term ID.
     *
     * @param int $term_id The ID of the term.
     * @return string The design associated with the term ID.
     * @global wpdb $wpdb The WordPress database object.
     *
     */
    public static function getDesignFromTermId(int $term_id): string
    {
        global $wpdb;
        $result = $wpdb->get_row("SELECT design FROM {$wpdb->prefix}cnrs_data_manager_articles_preview_design WHERE term_id = {$term_id}");
        return $result->design;
    }

    public static function getDefaultProjectImageUrl(bool $thumbnail): ?string
    {
        global $wpdb;
        $type = $thumbnail === true ? 'project_default_thumbnail_url' : 'project_default_image_url';
        $result = $wpdb->get_row("SELECT {$type} FROM {$wpdb->prefix}cnrs_data_manager_settings");
        return $result->project_default_image_url;
    }

    public static function setDefaultProjectImageUrl(string $url): void
    {
        global $wpdb;
        $wpdb->update("{$wpdb->prefix}cnrs_data_manager_settings", ['project_default_image_url' => $url]);
    }
}
