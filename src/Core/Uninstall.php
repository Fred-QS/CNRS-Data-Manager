<?php

/**
 * Interface that collects the functions of initial cnrs-data-manager Bootstrap
 *
 * @package   CNRS Data Manager
 * @copyright (c) 2024, QS Conseils
 */

namespace CnrsDataManager\Core;

/**
 * Uninstall class
 */
class Uninstall
{
    /**
     * Registrer unistall hoosk
     *
     * @return void
     */
    public static function registerHooks()
    {
        if (is_admin()) {
            register_deactivation_hook(CNRS_DATA_MANAGER_FILE, array(__CLASS__, 'deactivate'));
        }
    }

    /**
     * Deactivation Hook:
     * Hooked into `register_deactivation_hook`.  Routines used to deactivate the plugin
     * For uninstall see uninstall.php  WordPress by default will call the uninstall.php file
     *
     * @return void
     */
    public static function deactivate()
    {
        // Remove xml folder
        do_action('duplicator_after_deactivation');
    }
}
