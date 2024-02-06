<?php

namespace CnrsDataManager\Core\Models;

/**
 * Class Tools
 *
 * Provides various utility methods for interacting with databases and handling form inputs.
 */
class Tools
{
    /**
     * Retrieves the teams category ID and its corresponding name.
     *
     * This function queries the database to get the teams category ID from the
     * 'cnrs_data_manager_settings' table and returns an array containing the ID and
     * the name of the category.
     *
     * @return array An associative array with the category ID and name.
     * @global wpdb $wpdb WordPress database object.
     */
    public static function getTeamsCategoryId(): array
    {
        global $wpdb;
        $result = $wpdb->get_results( "SELECT teams_category FROM {$wpdb->prefix}cnrs_data_manager_settings", ARRAY_A );
        return ['id' => $result[0]['teams_category'], 'name' => get_cat_name($result[0]['teams_category'])];
    }

    /**
     * Retrieves the services category ID and name.
     *
     * @return array The array containing the services category ID and name.
     */
    public static function getServicesCategoryId(): array
    {
        global $wpdb;
        $result = $wpdb->get_results( "SELECT services_category FROM {$wpdb->prefix}cnrs_data_manager_settings", ARRAY_A );
        return ['id' => $result[0]['services_category'], 'name' => get_cat_name($result[0]['services_category'])];
    }

    /**
     * Retrieves the platforms category ID and name.
     *
     * @return array The array containing the platforms category ID and name.
     */
    public static function getPlatformsCategoryId(): array
    {
        global $wpdb;
        $result = $wpdb->get_results( "SELECT platforms_category FROM {$wpdb->prefix}cnrs_data_manager_settings", ARRAY_A );
        return ['id' => $result[0]['platforms_category'], 'name' => get_cat_name($result[0]['platforms_category'])];
    }

    /**
     * Updates the records in the CNRS data manager relations table based on the form inputs.
     *
     * This method retrieves the form inputs related to teams, services, and platforms XML files.
     * It then deletes all existing records with the specified type in the CNRS data manager relations table.
     * After that, it inserts new records into the table using the retrieved form inputs.
     *
     * @return void
     */
    public static function update(): void
    {
        if (isset($_POST['cnrs-data-manager-tools-teams-xml-0'])
            || isset($_POST['cnrs-data-manager-tools-services-xml-0'])
            || isset($_POST['cnrs-data-manager-tools-platforms-xml-0']))
        {
            global $wpdb;
            $table = $wpdb->prefix . 'cnrs_data_manager_relations';
            $type = stripslashes($_POST['cnrs-data-manager-tools-type']);
            $prefix = 'cnrs-data-manager-tools-' . $type . '-';
            $wpdb->delete($table, ['type' => $type]);

            $posts = [];
            foreach ($_POST as $key => $value) {
                if (stripos($key, 'cnrs-data-manager-tools-' . $type . '-xml-') !== false) {
                    $posts[] = $key;
                }
            }

            foreach ($posts as $post) {
                $index = (int) str_replace('cnrs-data-manager-tools-' . $type . '-xml-', '', $post);
                if (stripslashes($_POST['cnrs-data-manager-tools-' . $type . '-post-' . $index]) !== null) {
                    $insert = [
                        'term_id' => stripslashes($_POST['cnrs-data-manager-tools-' . $type . '-post-' . $index]),
                        'xml_entity_id' => stripslashes($_POST[$post]),
                        'type' => stripslashes($_POST['cnrs-data-manager-tools-' . $type . '-type-' . $index])
                    ];

                    $wpdb->insert($table, $insert, ['%d', '%d', '%s']);
                }
            }
        }
    }

    /**
     * Retrieves the relations between categories and XML entity IDs.
     *
     * This method retrieves the relations between different types of categories (teams, services, platforms)
     * and their corresponding XML entity IDs from the cnrs_data_manager_relations table in the WordPress database.
     *
     * @return array The array containing the relations between categories and XML entity IDs.
     *               The array structure is as follows:
     *               - teams: An array of objects representing the relations between teams and XML entity IDs.
     *                        Each object has two properties: 'cat' (category ID) and 'xml' (XML entity ID).
     *               - services: An array of objects representing the relations between services and XML entity IDs.
     *                           Each object has two properties: 'cat' (category ID) and 'xml' (XML entity ID).
     *               - platforms: An array of objects representing the relations between platforms and XML entity IDs.
     *                            Each object has two properties: 'cat' (category ID) and 'xml' (XML entity ID).
     */
    public static function getRelations(): array
    {
        global $wpdb;
        $teams = $wpdb->get_results( "SELECT term_id as cat, xml_entity_id as xml FROM {$wpdb->prefix}cnrs_data_manager_relations WHERE type = 'teams'", ARRAY_A );
        $services = $wpdb->get_results( "SELECT term_id as cat, xml_entity_id as xml FROM {$wpdb->prefix}cnrs_data_manager_relations WHERE type = 'services'", ARRAY_A );
        $platforms = $wpdb->get_results( "SELECT term_id as cat, xml_entity_id as xml FROM {$wpdb->prefix}cnrs_data_manager_relations WHERE type = 'platforms'", ARRAY_A );

        return compact('teams', 'services', 'platforms');
    }
}