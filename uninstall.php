<?php

require_once (__DIR__ . '/src/Tools/define.php');
require_once (__DIR__ . '/src/Tools/helper.php');

/**
 * Fired when the plugin is uninstalled.
 *
 * Maintain PHP 5.2 compatibility, don't use namespace and don't include CNRS Data Manager Libs
 */

// If uninstall not called from WordPress, then exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

/**
 * Uninstall class
 * Maintain PHP 5.2 compatibility, don't use namespace and don't include CNRS Data Manager Libs.
 * This is a standalone class.
 */
class CnrsDataManagerUninstall // phpcs:ignore
{
    /**
     * Uninstall plugin
     *
     * @return void
     */
    public static function uninstall()
    {
        try {
            cnrs_remove_folders(true);
            self::removeTables();
        } catch (Exception|Error $e) {
            // Prevent error on uninstall
        }
    }

    private static function removeTables()
    {
        global $wpdb;
        $tables = [
            $wpdb->prefix . 'cnrs_data_manager_map_markers',
            $wpdb->prefix . 'cnrs_data_manager_map_settings',
            $wpdb->prefix . 'cnrs_data_manager_relations',
            $wpdb->prefix . 'cnrs_data_manager_settings',
            $wpdb->prefix . 'cnrs_data_manager_team_project',
        ];
        foreach ($tables as $table) {
            $wpdb->query("DROP TABLE IF EXISTS {$table}");
        }
        $wpdb->query("DELETE FROM {$wpdb->prefix}posts WHERE post_name = 'mission-form'");
    }
}

CnrsDataManagerUninstall::uninstall();
