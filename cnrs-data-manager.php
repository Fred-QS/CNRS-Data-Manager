<?php
/**
 * Plugin Name: CNRS Data Manager
 * Plugin URI: https://github.com/Fred-QS/CNRS-Data-Manager
 * Description: Allows WordPress to process the information transmitted to the CNRS UMRs by affiliating the categories created to agents, display the agent directory, manage the interactive 3D map by implementing markers and import UMR projects.
 * Version:     1.0.0
 * Author:      Frédéric GEFFRAY - QS Conseils
 * Author URI:  https://www.linkedin.com/in/freduino
 * License: GPLv2 or later
 * Text Domain: cnrs-data-manager
 * Domain Path: /languages
 * Requires at least: 6.4.2
 * Requires PHP: 8.1.0
 *
 * Copyright 2024 QS Conseils
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the MIT License, as published by QS Conseils.
 *
 * This program is distributed for the sole use by the CNRS IS.
 */

defined('ABSPATH') || exit;

// Use custom plugin update logic
require (__DIR__ . '/src/Libs/UpdateChecker/plugin-update-checker.php');
\YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker(
    'https://github.com/Fred-QS/CNRS-Data-Manager',
    __FILE__,
    'cnrs-data-manager'
)->setBranch('main');

// Check PHP version
define('CNRS_DATA_MANAGER_PHP_MINIMUM_VERSION', '8.1.0');
define('CNRS_DATA_MANAGER_PHP_SUGGESTED_VERSION', '8.2');
require_once dirname(__FILE__) . "/src/Utils/CnrsDataManagerPhpVersionCheck.php";
if (CnrsDataManagerPhpVersionCheck::check(CNRS_DATA_MANAGER_PHP_MINIMUM_VERSION, CNRS_DATA_MANAGER_PHP_SUGGESTED_VERSION) === false) {
    return;
}

// Start plugin
$currentPluginBootFile = __FILE__;
require_once(__DIR__ . '/src/cnrs-main.php');