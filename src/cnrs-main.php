<?php

/**
 * @package   CNRS Data Manager
 * @copyright (c) 2024, QS Conseils
 */

defined('ABSPATH') || exit;

use CnrsDataManager\Core\Bootstrap;

/** @var string $currentPluginBootFile */

define('CNRS_DATA_MANAGER_PATH', dirname($currentPluginBootFile));
define('CNRS_DATA_MANAGER_FILE', $currentPluginBootFile);
define('CNRS_DATA_MANAGER_PLUGIN_URL', plugins_url('', $currentPluginBootFile));

require_once(CNRS_DATA_MANAGER_PATH . '/src/Utils/Autoloader.php');
\CnrsDataManager\Utils\Autoloader::register();

require_once("Tools/helper.php");
require_once("Tools/define.php");

Bootstrap::init();
setVirtualPages();
