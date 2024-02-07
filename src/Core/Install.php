<?php

/**
 * Interface that collects the functions of initial cnrs-data-manager Bootstrap
 *
 * @package   CNRS Data Manager
 * @copyright (c) 2024, QS Conseils
 */

namespace CnrsDataManager\Core;

/**
 * Install class
 */
class Install
{
    /**
     * Register install hook
     *
     * @return void
     */
    public static function registerHooks()
    {
        if (is_admin()) {
            register_activation_hook(__FILE__, array(__CLASS__, 'activate'));
        }
    }

    /**
     * Activation Hook:
     * Hooked into `register_activation_hook`.  Routines used to activate the plugin.
     *
     * @return void
     */
    public static function activate()
    {
        global $wpdb;
        // Drop tables if exists
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}cnrs_data_manager_map_markers");
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}cnrs_data_manager_map_settings");
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}cnrs_data_manager_relations");
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}cnrs_data_manager_settings");
        // Create tables
        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}cnrs_data_manager_map_markers (
              id int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Primary key',
              title varchar(255) NOT NULL COMMENT 'Marker title',
              lat decimal(8,6) NOT NULL COMMENT 'Marker latitude',
              lng decimal(9,6) NOT NULL COMMENT 'Marker longitude',
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Markers coordinates for the map'"
        );
        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}cnrs_data_manager_map_settings (
              sunlight tinyint(4) NOT NULL DEFAULT 0 COMMENT 'Sets sun illumination ',
              view enum('space','news','classic','hologram','cork') NOT NULL DEFAULT 'space' COMMENT 'Choice of view type',
              stars tinyint(4) NOT NULL DEFAULT 0 COMMENT 'Star Generation',
              black_bg tinyint(4) NOT NULL DEFAULT 1 COMMENT 'Choose a black background for the map',
              atmosphere tinyint(4) NOT NULL DEFAULT 1 COMMENT 'Bring out the atmosphere'
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='3D map settings'"
        );
        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}cnrs_data_manager_relations (
              term_id int(10) UNSIGNED NOT NULL COMMENT 'WordPress term ID',
              xml_entity_id int(10) UNSIGNED NOT NULL COMMENT 'XML provider entity ID',
              type varchar(50) NOT NULL COMMENT 'Type of the entity'
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='CNRS Data Manager relation table between terms and XML data'"
        );
        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}cnrs_data_manager_settings (
              filename varchar(100) NOT NULL DEFAULT 'my_umr' COMMENT 'XML filename without extension.',
              teams_category int(11) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Category for teams',
              teams_view_selector tinyint(4) NOT NULL DEFAULT 1 COMMENT 'Display teams view selector',
              services_category int(11) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Category for services',
              services_view_selector tinyint(4) NOT NULL DEFAULT 1 COMMENT 'Display services view selector',
              platforms_category int(11) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Category for platforms',
              platforms_view_selector tinyint(4) NOT NULL DEFAULT 1 COMMENT 'Display platforms view selector',
              mode enum('widget','page') NOT NULL DEFAULT 'widget' COMMENT 'Display mode for entity list',
              default_latitude decimal(8,6) NOT NULL DEFAULT 48.883890 COMMENT 'Default latitude value for 3D map',
              default_longitude decimal(9,6) NOT NULL DEFAULT 2.353520 COMMENT 'Default longitude value for 3D map'
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='CNRS Data Manager extension settings'"
        );
        // Populate tables
        $wpdb->query("INSERT INTO {$wpdb->prefix}cnrs_data_manager_map_markers (title, lat, lng) VALUES
            ('New York', 40.700000, -74.100000),
            ('Tokyo', 35.600000, 139.700000),
            ('Berlin', 52.500000, 13.400000)"
        );
        $wpdb->query("INSERT INTO {$wpdb->prefix}cnrs_data_manager_map_settings (sunlight, view, stars, black_bg, atmosphere) VALUES 
            (1, 'space', 1, 1, 1)"
        );
        $wpdb->query("INSERT INTO {$wpdb->prefix}cnrs_data_manager_relations (term_id, xml_entity_id, type) VALUES
            (876, 1, 'teams'),
            (898, 2, 'teams'),
            (1070, 1, 'platforms'),
            (0, 2, 'platforms'),
            (0, 3, 'platforms')"
        );
        $wpdb->query("INSERT INTO {$wpdb->prefix}cnrs_data_manager_settings (filename, teams_category, teams_view_selector, services_category, services_view_selector, platforms_category, platforms_view_selector, mode, default_latitude, default_longitude) VALUES
            ('umr_5805', 25, 1, 31, 1, 30, 1, 'widget', 44.869222, 0.494797)"
        );
    }
}
