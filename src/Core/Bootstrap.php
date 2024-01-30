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
     * Init plugin
     *
     * @return void
     */
    public static function init()
    {
        add_action('init', array(__CLASS__, 'hookWpInit'));
        @mkdir(ABSPATH . '/XML', 0755);
        define('CNRS_DATA_MANAGER_XML_DATA', Manager::defineArrayFromXML());

        if (is_admin()) {
            add_action('plugins_loaded', array(__CLASS__, 'loadTextdomain'));
            Uninstall::registerHooks();
        }

        /*CronUtils::init();
        StatsBootstrap::init();
        EmailSummaryBootstrap::init();*/
    }

    /**
     * Method called on wordpress hook init action
     *
     * @return void
     */
    public static function hookWpInit()
    {
        if (is_admin()) {

            add_action('admin_init', array(__CLASS__, 'adminInit'));
            add_action('admin_menu', array(__CLASS__, 'menuInit'));
            add_filter('admin_body_class', array(__CLASS__, 'addBodyClass'));

            //Init Class
            /*DUP_Custom_Host_Manager::getInstance()->init();
            DUP_Settings::init();
            DUP_Log::Init();
            DUP_Util::init();
            DUP_DB::init();
            MigrationMng::init();
            Notice::init();
            NoticeBar::init();
            Review::init();
            AdminNotices::init();
            DUP_Web_Services::init();
            WelcomeController::init();
            DashboardWidget::init();
            EducationElements::init();
            Notifications::init();
            EmailSummaryPreviewPageController::init();
            HelpPageController::init();
            $dashboardService = new ServicesDashboard();
            $dashboardService->init();
            $extraPlugin = new ServicesExtraPlugins();
            $extraPlugin->init();
            $educationService = new ServicesEducation();
            $educationService->init();*/
        }
    }

    /**
     * Return admin body classes
     *
     * @param string $classes classes
     *
     * @return string
     */
    public static function addBodyClass($classes)
    {
        if (Manager::isDataManagerPage()) {
            $classes .= ' cnrs-data-manager-pages';
        }
        
        return $classes;
    }

    /**
     * Load text domain for translation
     *
     * @return void
     */
    public static function loadTextdomain()
    {
        load_plugin_textdomain('cnrs-data-manager', false, 'cnrs-data-manager/languages');
    }

    /**
     * Hooked into `admin_init`.  Init routines for all admin pages
     *
     * @return void
     */
    public static function adminInit()
    {
        wp_enqueue_style('cnrs-data-manager-styles', CNRS_DATA_MANAGER_PLUGIN_URL . '/assets/css/cnrs-data-manager-styles.css', [], CNRS_DATA_MANAGER_VERSION);
        wp_enqueue_script('cnrs-data-manager-scripts', CNRS_DATA_MANAGER_PLUGIN_URL . '/assets/js/cnrs-data-manager-scripts.js', [], CNRS_DATA_MANAGER_VERSION, true);
    }

    /**
     * Hooked into `admin_menu`.  Loads all the wp left nav admin menus for CNRS Data Manager
     *
     * @return void
     */
    public static function menuInit()
    {
        $menuLabel = apply_filters('cnrs_data_manager_menu_label_cnrs_data_manager', 'My UMR');
        $hook_prefix = add_menu_page('CNRS Data Manager Plugin', $menuLabel, 'manage', 'cnrs-data-manager', null, CNRS_DATA_MANAGER_ICON);
        add_action('admin_print_scripts-' . $hook_prefix, array(__CLASS__, 'scripts'));
        add_action('admin_print_styles-' . $hook_prefix, array(__CLASS__, 'styles'));

        //Submenus are displayed in the same order they have in the array
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
     * Submenu datas
     *
     * @return array[]
     */
    protected static function getSubmenuItems()
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