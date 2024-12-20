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
            register_activation_hook(CNRS_DATA_MANAGER_FILE, [__CLASS__, 'activate']);
        }
    }

    /**
     * Activation Hook:
     * Hooked into 'register_activation_hook'.  Routines used to activate the plugin.
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
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}cnrs_data_manager_team_project");
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}cnrs_data_manager_agents_accounts");
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}cnrs_data_manager_mission_forms");
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}cnrs_data_manager_mission_form_settings");
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}cnrs_data_manager_conventions");
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}cnrs_data_manager_revisions");
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}cnrs_data_manager_hidden_filters");
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}cnrs_data_manager_candidating");
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}cnrs_data_manager_articles_preview_design");
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}cnrs_data_manager_project_entities");
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}cnrs_data_manager_project_entity_relation");
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}cnrs_data_manager_project_attachment_relation");
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}cnrs_data_manager_emails");
        // Create tables
        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}cnrs_data_manager_map_markers (
              id bigint(20) UNSIGNED PRIMARY (id) NOT NULL AUTO_INCREMENT COMMENT 'Primary key',
              title varchar(255) NOT NULL COMMENT 'Marker title',
              lat decimal(8,6) NOT NULL COMMENT 'Marker latitude',
              lng decimal(9,6) NOT NULL COMMENT 'Marker longitude'
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
              filename varchar(255) DEFAULT NULL COMMENT 'XML endpoint url',
              teams_category varchar(255) NOT NULL COMMENT 'Categories for teams',
              teams_view_selector tinyint(4) NOT NULL DEFAULT 1 COMMENT 'Display teams view selector',
              services_category varchar(255) NOT NULL COMMENT 'Categories for services',
              services_view_selector tinyint(4) NOT NULL DEFAULT 1 COMMENT 'Display services view selector',
              platforms_category varchar(255) NOT NULL COMMENT 'Categories for platforms',
              platforms_view_selector tinyint(4) NOT NULL DEFAULT 1 COMMENT 'Display platforms view selector',
              mode enum('widget','page') NOT NULL DEFAULT 'widget' COMMENT 'Display mode for entity list',
              default_latitude decimal(8,6) NOT NULL DEFAULT 48.883890 COMMENT 'Default latitude value for 3D map',
              default_longitude decimal(9,6) NOT NULL DEFAULT 2.353520 COMMENT 'Default longitude value for 3D map',
              category_template tinyint(4) NOT NULL DEFAULT 0 COMMENT 'Tells if the category.php file has to be created',
              silent_pagination tinyint(4) NOT NULL DEFAULT 0 COMMENT 'Define if posts silent pagination is activate',
              filter_modules varchar(255) NOT NULL DEFAULT 'per-page,sub-categories-list,by-year,search-field' COMMENT 'Module selection for post filters',
              candidating_email varchar(255) DEFAULT NULL COMMENT 'Email to send candidating info',
              project_default_image_url varchar(255) DEFAULT '/wp-content/plugins/cnrs-data-manager/assets/media/default-project-image.jpg' COMMENT 'Default project image'
              project_default_thumbnail_url varchar(255) DEFAULT '/wp-content/plugins/cnrs-data-manager/assets/media/default-project-image.jpg' COMMENT 'Default project thumbnail'
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='CNRS Data Manager extension settings'"
        );
        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}cnrs_data_manager_team_project (
              team_id int(10) UNSIGNED NOT NULL COMMENT 'Team unique ID',
              project_id int(10) UNSIGNED NOT NULL COMMENT 'Project unique ID',
              display_order int(10) UNSIGNED DEFAULT NULL COMMENT 'Display order from 1 to 16. Not displayed if NULL',
              lang varchar(2) NOT NULL DEFAULT 'fr' COMMENT 'Language of the post',
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Relations and display order between projects and teams'"
        );
        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}cnrs_data_manager_agents_accounts (
              id bigint(20) UNSIGNED PRIMARY (id) NOT NULL AUTO_INCREMENT COMMENT 'Auto incremented primary key',
              uuid varchar(34) NOT NULL UNIQUE (uuid),
              email varchar(255) NOT NULL UNIQUE (email) COMMENT 'Agent email',
              password varchar(255) NOT NULL COMMENT 'Agent password',
              created_at datetime NOT NULL DEFAULT current_timestamp() COMMENT 'Creation timestamp'
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Agents accounts table'"
        );
        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}cnrs_data_manager_mission_forms (
              id bigint(20) UNSIGNED NOT NULL PRIMARY (id) COMMENT 'Auto incremented key',
              uuid varchar(36) NOT NULL UNIQUE (uuid) COMMENT 'Unique identifier for a filled out form',
              email varchar(255) NOT NULL COMMENT 'Email from user who filled out the form',
              original longtext NOT NULL COMMENT 'Original form json',
              form longtext NOT NULL COMMENT 'Filled out form json',
              status ENUM('PENDING','VALIDATED','EXCEPTION','CANCELED') NOT NULL DEFAULT 'PENDING' COMMENT 'The form status once completed',
              funder_email varchar(150) DEFAULT NULL COMMENT 'Email from funding service referent',
              mission_start_at DATE NOT NULL COMMENT 'Mission start date',
              has_fees tinyint(1) NOT NULL DEFAULT 0 COMMENT 'If mission has fees',
              created_at timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Form creation timestamp'
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Filled out mission forms list'"
        );
        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}cnrs_data_manager_mission_form_settings (
              form longtext NOT NULL COMMENT 'Form in json string format',
              debug_mode tinyint(4) NOT NULL DEFAULT 0 COMMENT 'Debug mode for emails logic',
              debug_email varchar(255) DEFAULT NULL COMMENT 'Debug email address',
              admin_emails text NOT NULL COMMENT 'Administrators emails for form validation',
              generic_email varchar(150) NOT NULL COMMENT 'Generic email for validated form',
              generic_active tinyint(4) NOT NULL DEFAULT 1 COMMENT 'Activate the generic email sending for validated form',
              days_limit INT UNSIGNED NOT NULL DEFAULT 5 COMMENT 'Number of days for form submission deadline',
              month_limit INT UNSIGNED NOT NULL DEFAULT 20 COMMENT 'Number of days for form submission deadline outside France'
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='UMR Mission form settings'"
        );
        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}cnrs_data_manager_conventions (
              id bigint(20) UNSIGNED PRIMARY (id) NOT NULL AUTO_INCREMENT COMMENT 'Primary key',
              name varchar(150) NOT NULL COMMENT 'The convention name',
              primary_email varchar(150) NOT NULL COMMENT 'Main manager email',
              secondary_email varchar(150) NOT NULL COMMENT 'Fallback email if main manager not available',
              available tinyint(4) NOT NULL COMMENT 'Availability from the main manager'
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='UMR form conventions'"
        );
        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}cnrs_data_manager_revisions (
              id bigint(20) UNSIGNED PRIMARY (id) NOT NULL AUTO_INCREMENT COMMENT 'Primary key',
              active tinyint(4) NOT NULL DEFAULT 1 COMMENT 'Active if is current revision',
              uuid varchar(36) NOT NULL UNIQUE (uuid) COMMENT 'Unique revision identifier',
              manager_name varchar(150) DEFAULT NULL COMMENT 'Manager firstname and lastname',
              manager_email varchar(150) DEFAULT NULL COMMENT 'Email from the manager who has checked the form validity',
              form_id bigint(20) UNSIGNED NOT NULL COMMENT 'Relation to filled form',
              sender enum('AGENT','MANAGER','FUNDER') NOT NULL DEFAULT 'AGENT' COMMENT 'Origin from email',
              observations longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'Observations list',
              created_at datetime NOT NULL DEFAULT current_timestamp() COMMENT 'Revision creation timestamp'
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='UMR form revisions'"
        );
        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}cnrs_data_manager_hidden_filters (
              term_id bigint(20) UNSIGNED NOT NULL COMMENT 'Term ID for which filters are hidden'
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Hidden filters for categories and projects'"
        );
        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}cnrs_data_manager_candidating (
              term_id bigint(20) UNSIGNED NOT NULL COMMENT 'Term ID for which candating is activated'
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Candidating association with categories'"
        );
        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}cnrs_data_manager_articles_preview_design (
            term_id bigint(20) UNSIGNED NOT NULL COMMENT 'The term id from the articles', 
            design ENUM('POSTER','CARD','THUMBNAIL') NOT NULL DEFAULT 'POSTER' COMMENT 'The article preview design' 
            ) ENGINE = InnoDB COMMENT = 'Design pattern for article preview in categories landing page'"
        );
        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}cnrs_data_manager_project_entities (
            id bigint(20) UNSIGNED PRIMARY (id) NOT NULL AUTO_INCREMENT COMMENT 'The primary key',
            entity_type ENUM('FUNDER','PARTNER') NOT NULL DEFAULT 'FUNDER' COMMENT 'The entity type', 
            entity_name VARCHAR(255) NOT NULL COMMENT 'The entity name', 
            entity_logo bigint(20) UNSIGNED DEFAULT NULL COMMENT 'The attachment ID'
            ) ENGINE = InnoDB COMMENT = 'Entites related to a project like partners or funders'"
        );
        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}cnrs_data_manager_project_entity_relation (
            project_id bigint(20) UNSIGNED NOT NULL COMMENT 'The project post ID',
            entity_id bigint(20) UNSIGNED NOT NULL COMMENT 'The entity ID'
            ) ENGINE = InnoDB COMMENT = 'Entites and Projects relations'"
        );
        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}cnrs_data_manager_project_attachment_relation (
            project_id bigint(20) UNSIGNED NOT NULL COMMENT 'The project post ID',
            attachment_id bigint(20) UNSIGNED NOT NULL COMMENT 'The attachment post ID'
            ) ENGINE = InnoDB COMMENT = 'Relation between a projects and its attachments'"
        );
        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}cnrs_data_manager_emails (
            id bigint(20) UNSIGNED NOT NULL COMMENT 'The email ID',
            subject VARCHAR(255) NOT NULL COMMENT 'The email subject',
            title VARCHAR(255) NOT NULL COMMENT 'The email title',
            file VARCHAR(255) NOT NULL COMMENT 'The email template file',
            content LONGTEXT NOT NULL COMMENT 'The email content',
            title_logo VARCHAR(255) DEFAULT NULL COMMENT 'The email title sub logo',
            lang VARCHAR(2) NOT NULL COMMENT 'The email template locale',
            shortcodes LONGTEXT DEFAULT NULL COMMENT 'The email shortcodes'
            ) ENGINE = InnoDB COMMENT = 'Emails templates'"
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
        $wpdb->query("INSERT INTO {$wpdb->prefix}cnrs_data_manager_settings (filename, teams_category, teams_view_selector, services_category, services_view_selector, platforms_category, platforms_view_selector, mode, default_latitude, default_longitude, category_template, silent_pagination, filter_modules) VALUES
            ('umr_5805', 1, 1, 1, 1, 1, 1, 'widget', 44.869222, 0.494797, 0, 0, 'per-page,sub-categories-list,by-year,search-field')"
        );
    }
}
