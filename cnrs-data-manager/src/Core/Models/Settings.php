<?php

namespace CnrsDataManager\Core\Models;

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

    public static function update(): void
    {
        if (isset($_POST['cnrs-dm-filename'])
            && strlen($_POST['cnrs-dm-filename']) > 0
            && strlen($_POST['cnrs-dm-filename']) <= 100
            && isset($_POST['cnrs-dm-mode'])
            && isset($_POST['cnrs-dm-selector-teams'])
            && isset($_POST['cnrs-dm-selector-services'])
            && isset($_POST['cnrs-dm-selector-platforms'])
            && isset($_POST['cnrs-data-manager-categories-list-teams'])
            && isset($_POST['cnrs-data-manager-categories-list-services'])
            && isset($_POST['cnrs-data-manager-categories-list-platforms']))
        {
            $post = [
                'filename' => stripslashes($_POST['cnrs-dm-filename']),
                'mode' => stripslashes($_POST['cnrs-dm-mode']),
                'teams_category' => stripslashes($_POST['cnrs-data-manager-categories-list-teams']),
                'teams_view_selector' => stripslashes($_POST['cnrs-dm-selector-teams']),
                'services_category' => stripslashes($_POST['cnrs-data-manager-categories-list-services']),
                'services_view_selector' => stripslashes($_POST['cnrs-dm-selector-services']),
                'platforms_category' => stripslashes($_POST['cnrs-data-manager-categories-list-platforms']),
                'platforms_view_selector' => stripslashes($_POST['cnrs-dm-selector-platforms']),
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
                "UPDATE {$wpdb->prefix}cnrs_data_manager_settings SET filename=%s,teams_category=%d,teams_view_selector=%d,services_category=%d,services_view_selector=%d,platforms_category=%d,platforms_view_selector=%d,mode=%s",
                $post['filename'],
                $post['teams_category'],
                $post['teams_view_selector'],
                $post['services_category'],
                $post['services_view_selector'], 
                $post['platforms_category'],
                $post['platforms_view_selector'],
                $post['mode']
            ));
        }
    }

    public static function getDefaultMarker(): array
    {
        global $wpdb;
        $result = $wpdb->get_results( "SELECT default_latitude as lat, default_longitude as lng FROM {$wpdb->prefix}cnrs_data_manager_settings", ARRAY_A );
        return $result[0];
    }

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
}