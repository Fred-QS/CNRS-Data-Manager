<?php

/**
 * Interface that collects the functions of initial cnrs data manager Bootstrap
 *
 * @package   CNRS Data Manager
 * @copyright (c) 2024, QS Conseils
 */

namespace CnrsDataManager\Core;

use CnrsDataManager\Core\Uninstall;
use CnrsDataManager\Core\Controllers\Manager;

class Bootstrap
{
    /**
     * Initialize the plugin
     *
     * This method initializes the plugin by setting up various actions, hooks, and constants.
     *
     * @return void
     */
    public static function init(): void
    {
        add_action('init', array(__CLASS__, 'hookWpInit'));
        @mkdir(ABSPATH . '/XML', 0755);
        define('CNRS_DATA_MANAGER_XML_DATA', Manager::defineArrayFromXML());

        if (is_admin()) {
            add_action('plugins_loaded', array(__CLASS__, 'loadTextDomain'));
            Uninstall::registerHooks();
        }

        add_shortcode( 'cnrs-data-manager', 'cnrsReadShortCode' );
    }

    /**
     * Executes actions and filters when WordPress initializes.
     *
     * This method is called when WordPress initializes and checks if the current context is the WordPress admin area.
     * If it is, several actions and filters are added to be executed during the admin initialization.
     *
     * @return void
     */
    public static function hookWpInit(): void
    {
        if (is_admin()) {

            add_action('admin_init', array(__CLASS__, 'adminInit'));
            add_action('admin_menu', array(__CLASS__, 'menuInit'));
            add_filter('admin_body_class', array(__CLASS__, 'addBodyClass'));

        } else {

            $currentThemeFolder = get_template_directory();
            define('CNRS_DATA_MANAGER_CURRENT_THEME_FOLDER', $currentThemeFolder);

            if (!file_exists(ABSPATH . '/wp-includes/cnrs-data-manager')) {
                @mkdir(ABSPATH . '/wp-includes/cnrs-data-manager', 0755);
            }

            if (!file_exists(ABSPATH . '/wp-includes/cnrs-data-manager/cnrs-data-manager-style.css')) {
                copy(CNRS_DATA_MANAGER_PATH . '/templates/cnrs-data-manager-style.css', ABSPATH . '/wp-includes/cnrs-data-manager/cnrs-data-manager-style.css');
            }

            if (!file_exists(ABSPATH . '/wp-includes/cnrs-data-manager/cnrs-data-manager-script.js')) {
                copy(CNRS_DATA_MANAGER_PATH . '/templates/cnrs-data-manager-script.js', ABSPATH . '/wp-includes/cnrs-data-manager/cnrs-data-manager-script.js');
            }
        }
    }

    /**
     * Add body class for DataManager pages
     *
     * @param string $classes The classes to be appended to the body class
     * @return string The modified body class with appended class if DataManager page, otherwise unchanged body class
     */
    public static function addBodyClass(string $classes): string
    {
        if (Manager::isDataManagerPage()) {
            $classes .= ' cnrs-data-manager-pages';
        }
        
        return $classes;
    }

    /**
     * Loads the text domain for the "cnrs-data-manager" plugin.
     *
     * This method loads the translation files for the "cnrs-data-manager" plugin to enable localization.
     *
     * @return void
     */
    public static function loadTextDomain(): void
    {
        load_plugin_textdomain('cnrs-data-manager', false, 'cnrs-data-manager/languages');
    }

    /**
     * Initializes the admin interface for the "cnrs-data-manager" plugin.
     *
     * This method enqueues the necessary styles and scripts for the admin interface of the "cnrs-data-manager" plugin.
     *
     * @return void
     */
    public static function adminInit(): void
    {
        wp_enqueue_style('cnrs-data-manager-styles', CNRS_DATA_MANAGER_PLUGIN_URL . '/assets/css/cnrs-data-manager-styles.css', [], CNRS_DATA_MANAGER_VERSION);
        wp_enqueue_script(
            'cnrs-data-manager-map-script',
            CNRS_DATA_MANAGER_PLUGIN_URL . '/assets/js/cnrs-data-manager-map.js',
            array('cnrs-data-manager-map-library-script'),
            filemtime(CNRS_DATA_MANAGER_PATH . '/assets/js/cnrs-data-manager-map.js'),
            true
        );

        wp_enqueue_script(
            'cnrs-data-manager-map-library-script',
            CNRS_DATA_MANAGER_PLUGIN_URL . '/assets/js/cnrs-data-manager-map-library.min.js',
            [],
            '1.0'
        );

        wp_enqueue_style(
            'cnrs-data-manager-map-style',
            CNRS_DATA_MANAGER_PLUGIN_URL . '/assets/css/cnrs-data-manager-map.css',
            [],
            filemtime(CNRS_DATA_MANAGER_PATH . '/assets/css/cnrs-data-manager-map.css')
        );

        wp_enqueue_script('cnrs-data-manager-scripts', CNRS_DATA_MANAGER_PLUGIN_URL . '/assets/js/cnrs-data-manager-scripts.js', ['cnrs-data-manager-map-script'], CNRS_DATA_MANAGER_VERSION, true);
    }

    /**
     * Initializes the menu for the "cnrs-data-manager" plugin.
     *
     * This method sets up the main menu and submenus for the "cnrs-data-manager" plugin.
     * It adds the necessary actions and filters to register menu items and enqueue scripts and styles.
     *
     * @return void
     */
    public static function menuInit(): void
    {
        $menuLabel = apply_filters('cnrs_data_manager_menu_label_cnrs_data_manager', 'My UMR');
        $hook_prefix = add_menu_page('CNRS Data Manager Plugin', $menuLabel, 'manage', 'cnrs-data-manager', null, CNRS_DATA_MANAGER_ICON);
        add_action('admin_print_scripts-' . $hook_prefix, array(__CLASS__, 'scripts'));
        add_action('admin_print_styles-' . $hook_prefix, array(__CLASS__, 'styles'));

        $subMenuItems = self::getSubmenuItems();
        foreach ($subMenuItems as $k => $subMenuItem) {
            $pageTitle = apply_filters('cnrs_data_manager_page_title_' . $subMenuItem['menu_slug'], $subMenuItem['page_title']);
            $menuLabel = apply_filters('cnrs_data_manager_menu_label_' . $subMenuItem['menu_slug'], $subMenuItem['menu_title']);

            $subMenuItems[$k]['hook_prefix'] = add_submenu_page(
                $subMenuItem['parent_slug'],
                $pageTitle,
                $menuLabel,
                $subMenuItem['capability'],
                $subMenuItem['menu_slug'],
                $subMenuItem['callback'],
                $k
            );
        }
    }

    /**
     * Retrieves the submenu items for the "cnrs-data-manager" plugin.
     *
     * This method returns an array of submenu items to be added under the parent menu item 'cnrs-data-manager'.
     * Each submenu item is represented by an associative array containing the following keys:
     *   - 'parent_slug'  : The slug of the parent menu item.
     *   - 'page_title'   : The title to be displayed in the browser page title and on the submenu item.
     *   - 'menu_title'   : The title to be displayed on the submenu item.
     *   - 'capability'   : The capability required to access the submenu item.
     *   - 'menu_slug'    : The slug of the submenu item.
     *   - 'callback'     : The callback function to be executed when the submenu item is clicked.
     *                      This function includes the corresponding view file.
     *
     * @return array An array of submenu items.
     */
    protected static function getSubmenuItems(): array
    {
        return [
            [
                'parent_slug' => 'cnrs-data-manager',
                'page_title'  => __('Dashboard', 'cnrs-data-manager'),
                'menu_title'  => __('Dashboard', 'cnrs-data-manager'),
                'capability'  => 'manage_options',
                'menu_slug'   => 'data-manager',
                'callback'    => function () {
                    include(CNRS_DATA_MANAGER_PATH . '/src/Core/Views/Dashboard.php');
                }
            ],
            [
                'parent_slug'            => 'cnrs-data-manager',
                'page_title'             => __('Tools', 'cnrs-data-manager'),
                'menu_title'             => __('Tools', 'cnrs-data-manager'),
                'capability'             => 'manage_options',
                'menu_slug'              => 'tools',
                'callback'               => function () {
                    include(CNRS_DATA_MANAGER_PATH . '/src/Core/Views/Tools.php');
                }
            ],
            [
                'parent_slug'            => 'cnrs-data-manager',
                'page_title'             => __('3D Map', 'cnrs-data-manager'),
                'menu_title'             => __('3D Map', 'cnrs-data-manager'),
                'capability'             => 'manage_options',
                'menu_slug'              => '3D-map',
                'callback'               => function () {
                    include(CNRS_DATA_MANAGER_PATH . '/src/Core/Views/MapSettings.php');
                }
            ],
            [
                'parent_slug'            => 'cnrs-data-manager',
                'page_title'             => __('Settings', 'cnrs-data-manager'),
                'menu_title'             => __('Settings', 'cnrs-data-manager'),
                'capability'             => 'manage_options',
                'menu_slug'              => 'settings',
                'callback'               => function () {
                    include(CNRS_DATA_MANAGER_PATH . '/src/Core/Views/Settings.php');
                }
            ]
        ];
    }
}