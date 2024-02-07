<?php

/**
 * These functions are performed before including any other CNRS Data Manager file so
 * do not use any CNRS Data Manager library or feature and use code compatible with PHP 8.1
 */

defined('ABSPATH') || exit;

if (!class_exists('CnrsDataManagerPhpVersionCheck')) {

    class CnrsDataManagerPhpVersionCheck // phpcs:ignore 
    {
        /** @var string */
        protected static $minVer = '';
        /** @var string */
        protected static $suggestedVer = '';

        /**
         * Check PhpVersion
         *
         * @param string $minVer       min version of PHP
         * @param string $suggestedVer suggested version of PHP
         *
         * @return bool
         */
        public static function check($minVer, $suggestedVer)
        {
            self::$minVer       = $minVer;
            self::$suggestedVer = $suggestedVer;

            if (version_compare(PHP_VERSION, self::$minVer, '<')) {
                if (is_multisite()) {
                    add_action('network_admin_notices', array(__CLASS__, 'notice'));
                } else {
                    add_action('admin_notices', array(__CLASS__, 'notice'));
                }
                return false;
            } else {
                return true;
            }
        }

        /**
         * Display notice
         *
         * @return void
         */
        public static function notice()
        {
            ?>
            <div class="error notice">
                <p>

                    <?php
                    printf(
                        __(
                            'CNRS Data Manager: Your system is running a very old version of PHP (%s) that is no longer supported by CNRS Data Manager.  ',
                            'cnrs-data-manager'
                        ),
                        PHP_VERSION
                    );
                    ?><br><br>
                    <b>
                        <?php
                        printf(
                            __(
                                'Please ask your host or server administrator to update to PHP %1s or greater.',
                                'cnrs-data-manager'
                            ),
                            self::$suggestedVer
                        );
                        ?></b><br>
                    <?php
                    printf(
                        __(
                            'If this is not possible, please visit the FAQ link titled 
                            %1$s"What version of PHP Does CNRS Data Manager Support?"%2$s
                            for instructions on how to download a previous version of CNRS Data Manager compatible with PHP %3$s.',
                            'cnrs-data-manager'
                        ),
                        '<a href="' . esc_url('https://github.com/Fred-QS/CNRS-Data-Manager/requirements') . '" target="blank">',
                        '</a>',
                        self::$minVer
                    );
                    ?>
                </p>
            </div>
            <?php
        }
    }
}
