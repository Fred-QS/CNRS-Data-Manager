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
            if (wp_next_scheduled('cnrs_data_manager_cron_hook')) {
                wp_clear_scheduled_hook('cnrs_data_manager_cron_hook');
            }
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
            $wpdb->prefix . 'cnrs_data_manager_agents_accounts',
            $wpdb->prefix . 'cnrs_data_manager_mission_forms',
            $wpdb->prefix . 'cnrs_data_manager_mission_form_settings',
            $wpdb->prefix . 'cnrs_data_manager_conventions',
            $wpdb->prefix . 'cnrs_data_manager_revisions',
            $wpdb->prefix . 'cnrs_data_manager_hidden_filters',
            $wpdb->prefix . 'cnrs_data_manager_candidating',
            $wpdb->prefix . 'cnrs_data_manager_articles_preview_design',
            $wpdb->prefix . 'cnrs_data_manager_project_entities',
            $wpdb->prefix . 'cnrs_data_manager_project_entity_relation',
            $wpdb->prefix . 'cnrs_data_manager_project_attachment_relation',
            $wpdb->prefix . 'cnrs_data_manager_emails',
        ];

        foreach ($tables as $table) {
            $wpdb->query("DROP TABLE IF EXISTS {$table}");
        }

        $metas = [
            'cnrs_project_acronym',
            'cnrs_project_leaders_and_team',
            'cnrs_project_link',
            'cnrs_project_link_text'
        ];

        foreach ($metas as $meta) {
            $wpdb->query("DELETE FROM {$wpdb->prefix}postmeta WHERE meta_key = '{$meta}'");
        }
    }
}

CnrsDataManagerUninstall::uninstall();
