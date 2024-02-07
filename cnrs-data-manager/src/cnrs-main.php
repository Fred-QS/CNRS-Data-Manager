<?php

/**
 *
 * @package   CNRS Data Manager
 * @copyright (c) 2024, QS Conseils
 */

defined('ABSPATH') || exit;

use CnrsDataManager\Core\Bootstrap;

/** @var string $currentPluginBootFile */

define('CNRS_DATA_MANAGER_PATH', dirname($currentPluginBootFile));
define('CNRS_DATA_MANAGER_FILE', $currentPluginBootFile);
define('CNRS_DATA_MANAGER_PLUGIN_URL', plugins_url('', $currentPluginBootFile));

if (!defined('DMXABSPATH')) {
    define('DMXABSPATH', dirname(CNRS_DATA_MANAGER_FILE));
}

require_once(CNRS_DATA_MANAGER_PATH . '/src/Utils/Autoloader.php');
\CnrsDataManager\Utils\Autoloader::register();

require_once("Tools/helper.php");
require_once("Tools/define.php");

if (defined('CNRS_DATA_MANAGER_DEACTIVATION_FEEDBACK') && CNRS_DATA_MANAGER_DEACTIVATION_FEEDBACK) {
    require_once (CNRS_DATA_MANAGER_PATH . '/src/Tools/deactivation.php');
}
/*require_once 'classes/class.constants.php';
require_once 'classes/host/class.custom.host.manager.php';
require_once 'classes/class.settings.php';
require_once 'classes/class.logging.php';
require_once 'classes/class.plugin.upgrade.php';
require_once 'classes/utilities/class.u.php';
require_once 'classes/utilities/class.u.string.php';
require_once 'classes/utilities/class.u.validator.php';
require_once 'classes/class.db.php';
require_once 'classes/class.server.php';
require_once 'classes/ui/class.ui.viewstate.php';
require_once 'classes/ui/class.ui.screen.base.php';
require_once 'classes/package/class.pack.php';
require_once 'ctrls/ctrl.package.php';
require_once 'ctrls/ctrl.tools.php';
require_once 'ctrls/ctrl.ui.php';
require_once 'ctrls/class.web.services.php';*/

Bootstrap::init();