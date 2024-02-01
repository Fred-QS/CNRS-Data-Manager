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
            && isset($_POST['cnrs-data-manager-categories-list-teams'])
            && isset($_POST['cnrs-data-manager-categories-list-services'])
            && isset($_POST['cnrs-data-manager-categories-list-platforms']))
        {
            $post = [
                'filename' => stripslashes($_POST['cnrs-dm-filename']),
                'teams_category' => stripslashes($_POST['cnrs-data-manager-categories-list-teams']),
                'services_category' => stripslashes($_POST['cnrs-data-manager-categories-list-services']),
                'platforms_category' => stripslashes($_POST['cnrs-data-manager-categories-list-platforms']),
            ];
            global $wpdb;
            $wpdb->query($wpdb->prepare("UPDATE {$wpdb->prefix}cnrs_data_manager_settings SET filename=%s,teams_category=%d,services_category=%d,platforms_category=%d", $post['filename'], $post['teams_category'], $post['services_category'], $post['platforms_category']));
        }
    }
}