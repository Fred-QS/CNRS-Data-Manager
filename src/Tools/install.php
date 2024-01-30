<?php

/**
 * Fired when the plugin is installed.
 *
 * Maintain PHP 5.2 compatibility, don't use namespace and don't include CNRS Data Manager Libs
 */

// If install not called from WordPress, then exit
if (!defined('WP_INSTALL_PLUGIN')) {
    exit;
}

/**
 * Install class
 * Maintain PHP 5.2 compatibility, don't use namespace and don't include CNRS Data Manager Libs.
 * This is a standalone class.
 */

class CnrsDataManagerInstall
{

}