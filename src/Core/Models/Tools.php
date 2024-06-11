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
    public static function getTeamsCategoryId(string $lang = 'fr'): array
    {
        global $wpdb;
        $result = $wpdb->get_row( "SELECT teams_category FROM {$wpdb->prefix}cnrs_data_manager_settings", ARRAY_A );
        $res = json_decode($result['teams_category'], true);
        return ['id' => (int) $res[$lang], 'name' => get_cat_name((int) $res[$lang])];
    }

    /**
     * Retrieves the services category ID and name.
     *
     * @return array The array containing the services category ID and name.
     */
    public static function getServicesCategoryId(string $lang = 'fr'): array
    {
        global $wpdb;
        $result = $wpdb->get_row( "SELECT services_category FROM {$wpdb->prefix}cnrs_data_manager_settings", ARRAY_A );
        $res = json_decode($result['services_category'], true);
        return ['id' => (int) $res[$lang], 'name' => get_cat_name((int) $res[$lang])];
    }

    /**
     * Retrieves the platforms category ID and name.
     *
     * @return array The array containing the platforms category ID and name.
     */
    public static function getPlatformsCategoryId(string $lang = 'fr'): array
    {
        global $wpdb;
        $result = $wpdb->get_row( "SELECT platforms_category FROM {$wpdb->prefix}cnrs_data_manager_settings", ARRAY_A );
        $res = json_decode($result['platforms_category'], true);
        return ['id' => (int) $res[$lang], 'name' => get_cat_name((int) $res[$lang])];
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
            $prefix = 'cnrs-data-manager-tools-' . $type;
            $wpdb->delete($table, ['type' => $type]);

            $posts = [];
            foreach ($_POST as $key => $value) {
                if (stripos($key, $prefix . '-xml-') !== false) {
                    $posts[] = $key;
                }
            }

            foreach ($posts as $post) {
                $index = (int) str_replace($prefix . '-xml-', '', $post);
                if ($_POST[$prefix . '-post-' . $index] !== null) {
                    $array = $_POST[$prefix . '-post-' . $index];
                    foreach ($array as $item) {
                        $insert = [
                            'term_id' => stripslashes($item),
                            'xml_entity_id' => stripslashes($_POST[$post]),
                            'type' => stripslashes($_POST[$prefix . '-type-' . $index])
                        ];

                        $wpdb->insert($table, $insert, ['%d', '%d', '%s']);
                    }
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

    /**
     * Retrieves all relations from the database.
     *
     * @return array The array containing all relations.
     */
    public static function getAllRelations(): array
    {
        global $wpdb;
        return $wpdb->get_results( "SELECT term_id as cat, xml_entity_id as xml, type FROM {$wpdb->prefix}cnrs_data_manager_relations WHERE term_id != 0", ARRAY_A );
    }

    /**
     * Retrieves the teams.
     *
     * @return array The array containing the teams' term ID and related xml entity ID.
     */
    public static function getTeams(string $lang = 'fr'): array
    {
        global $wpdb;

        $cat = match ($lang) {
            'en' => get_category_by_slug('teams'),
            default => get_category_by_slug('equipes'),
        };

        if ($cat !== false) {

            $posts = get_posts([
                'fields' => 'ids',
                'post_type' => 'post',
                'lang' => $lang,
                'numberposts' => -1,
                'post_status'    => 'publish',
                'category' => $cat->cat_ID,
                'orderby' => 'post_title',
                'order' => 'ASC',
            ]);

            $values = implode(', ', $posts);

            return $wpdb->get_results( "SELECT DISTINCT {$wpdb->prefix}cnrs_data_manager_relations.term_id, {$wpdb->prefix}cnrs_data_manager_relations.xml_entity_id FROM {$wpdb->prefix}cnrs_data_manager_relations INNER JOIN {$wpdb->prefix}posts ON {$wpdb->prefix}cnrs_data_manager_relations.term_id = {$wpdb->prefix}posts.ID WHERE {$wpdb->prefix}cnrs_data_manager_relations.type = 'teams' AND {$wpdb->prefix}cnrs_data_manager_relations.term_id IN ({$values}) ORDER BY {$wpdb->prefix}posts.post_title ASC", ARRAY_A );
        }

        return $wpdb->get_results( "SELECT DISTINCT {$wpdb->prefix}cnrs_data_manager_relations.term_id, {$wpdb->prefix}cnrs_data_manager_relations.xml_entity_id FROM {$wpdb->prefix}cnrs_data_manager_relations INNER JOIN {$wpdb->prefix}posts ON {$wpdb->prefix}cnrs_data_manager_relations.term_id = {$wpdb->prefix}posts.ID WHERE {$wpdb->prefix}cnrs_data_manager_relations.type = 'teams' AND {$wpdb->prefix}cnrs_data_manager_relations.term_id != 0 ORDER BY {$wpdb->prefix}posts.post_title ASC", ARRAY_A );
    }
}
