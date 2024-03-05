<?php

//Prevent directly browsing to the file
defined('ABSPATH') || exit;

$mainIcon = file_get_contents(CNRS_DATA_MANAGER_PATH . '/assets/icons/main.svg');
$dashboardIcon = file_get_contents(CNRS_DATA_MANAGER_PATH . '/assets/icons/dashboard.svg');
$toolsIcon = file_get_contents(CNRS_DATA_MANAGER_PATH . '/assets/icons/tools.svg');
$settingsIcon = file_get_contents(CNRS_DATA_MANAGER_PATH . '/assets/icons/settings.svg');
$mapIcon = file_get_contents(CNRS_DATA_MANAGER_PATH . '/assets/icons/map.svg');
$importIcon = file_get_contents(CNRS_DATA_MANAGER_PATH . '/assets/icons/import.svg');

define('CNRS_DATA_MANAGER_MAIN_ICON', $mainIcon);
define('CNRS_DATA_MANAGER_DASHBOARD_ICON', $dashboardIcon);
define('CNRS_DATA_MANAGER_TOOLS_ICON', $toolsIcon);
define('CNRS_DATA_MANAGER_SETTINGS_ICON', $settingsIcon);
define('CNRS_DATA_MANAGER_MAP_ICON', $mapIcon);
define('CNRS_DATA_MANAGER_IMPORT_ICON', $importIcon);
define('CNRS_DATA_MANAGER_ICON', 'data:image/svg+xml;base64,' . base64_encode(CNRS_DATA_MANAGER_MAIN_ICON));
define('CNRS_DATA_MANAGER_DEPORTED_TEMPLATES_PATH', ABSPATH . '/wp-includes/cnrs-data-manager/templates');
define('CNRS_DATA_MANAGER_DEPORTED_SVG_PATH', ABSPATH . '/wp-includes/cnrs-data-manager/svg');
define('CNRS_DATA_MANAGER_LIMIT_OFFSET', 5);
define('CNRS_DATA_MANAGER_IMPORT_TMP_PATH', CNRS_DATA_MANAGER_PATH . '/tmp');
define('CNRS_DATA_MANAGER_PROJECTS_DISPLAY_NUMBER', 16);

if (function_exists('plugin_dir_url')) {

    define('CNRS_DATA_MANAGER_VERSION', '1.0.0');
    define('CNRS_DATA_MANAGER_SITE_URL', get_site_url());

    if (!defined('ABSPATH')) {
        define('ABSPATH', dirname(__FILE__));
    }

    //PATH CONSTANTS
    if (! defined('CNRS_DATA_MANAGER_WPROOTPATH')) {
        define('CNRS_DATA_MANAGER_WPROOTPATH', str_replace('\\', '/', ABSPATH));
    }

    /* Let setup few things to cover all PHP versions */
    if (!defined('PHP_VERSION')) {
        define('PHP_VERSION', phpversion());
    }
    if (!defined('PHP_VERSION_ID')) {
        $version = explode('.', PHP_VERSION);
        var_dump($version);
        define('PHP_VERSION_ID', (((int) $version[0] * 10000) + ((int) $version[1] * 100) + (int) $version[2]));
    }
    if (PHP_VERSION_ID < 80100) {
        if (!(isset($version))) {
            $version = explode('.', PHP_VERSION);
        }
        if (!defined('PHP_MAJOR_VERSION')) {
            define('PHP_MAJOR_VERSION', (int) $version[0]);
        }
        if (!defined('PHP_MINOR_VERSION')) {
            define('PHP_MINOR_VERSION', (int) $version[1]);
        }
        if (!defined('PHP_RELEASE_VERSION')) {
            define('PHP_RELEASE_VERSION', (int) $version[2]);
        }
    }

    if (!defined('CNRS_DATA_MANAGER_CUSTOM_STATS_REMOTE_HOST')) {
        define('CNRS_DATA_MANAGER_CUSTOM_STATS_REMOTE_HOST', '');
    }

    if (!defined('CNRS_DATA_MANAGER_USTATS_DISALLOW')) {
        define('CNRS_DATA_MANAGER_USTATS_DISALLOW', false);
    }
} else {
    error_reporting(0);
    $port = (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] != "off") ? "https://" : "http://";
    $url  = $port . $_SERVER["HTTP_HOST"];
    header("HTTP/1.1 404 Not Found", true, 404);
    header("Status: 404 Not Found");
    exit();
}
