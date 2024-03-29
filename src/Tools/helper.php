<?php

defined('ABSPATH') || exit;

use CnrsDataManager\Core\Models\Agents;
use CnrsDataManager\Core\Models\Map;
use CnrsDataManager\Core\Models\Settings;
use CnrsDataManager\Core\Models\Tools;
use CnrsDataManager\Core\Models\Projects;
use CnrsDataManager\Core\Controllers\Manager;

$shortCodesCounter = 0;

if (!function_exists('rrmdir')) {
    /**
     * Recursively removes a directory and its contents.
     *
     * @param string $dir The path to the directory to be removed.
     *
     * @return void
     */
    function rrmdir(string $dir): void
    {
        if (file_exists($dir) && is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (is_dir($dir. DIRECTORY_SEPARATOR .$object) && !is_link($dir."/".$object))
                        rrmdir($dir. DIRECTORY_SEPARATOR .$object);
                    else
                        unlink($dir. DIRECTORY_SEPARATOR .$object);
                }
            }
            rmdir($dir);
        }
    }
}

if (!function_exists('cnrs_install_folders')) {

    /**
     * Installs necessary folders and files for the CNRS Data Manager plugin.
     *
     * Creates required folders and copies necessary files for the CNRS Data Manager plugin.
     * The folders to be created include:
     * - /wp-includes/cnrs-data-manager
     * - CNRS_DATA_MANAGER_DEPORTED_TEMPLATES_PATH
     * - CNRS_DATA_MANAGER_DEPORTED_SVG_PATH
     *
     * The files to be copied include:
     * - cnrs-data-manager-style.css
     * - cnrs-data-manager-script.js
     * - cnrs-data-manager-inline.php
     * - cnrs-data-manager-card.php
     * - cnrs-data-manager-sorted-title.php
     * - cnrs-data-manager-list-item.php
     * - list.svg
     * - grid.svg
     * - cnrs-data-manager-fr_FR.mo
     * - cnrs-data-manager-fr_FR.po
     *
     * If any of the folders already exist, they will not be created.
     * If any of the files already exist, they will not be copied.
     *
     * @return void
     */
    function cnrs_install_folders(): void
    {
        $folders = [
            ABSPATH . '/wp-includes/cnrs-data-manager',
            CNRS_DATA_MANAGER_DEPORTED_TEMPLATES_PATH,
            CNRS_DATA_MANAGER_DEPORTED_SVG_PATH
        ];

        foreach ($folders as $folder) {
            if (!file_exists($folder)) {
                @mkdir($folder, 0755);
            }
        }

        $files = [
            [
                'from' => CNRS_DATA_MANAGER_PATH . '/templates/assets/cnrs-data-manager-style.css',
                'to' => ABSPATH . '/wp-includes/cnrs-data-manager/assets/cnrs-data-manager-style.css'
            ],
            [
                'from' => CNRS_DATA_MANAGER_PATH . '/templates/assets/cnrs-data-manager-filters-style.css',
                'to' => ABSPATH . '/wp-includes/cnrs-data-manager/assets/cnrs-data-manager-filters-style.css'
            ],
            [
                'from' => CNRS_DATA_MANAGER_PATH . '/templates/assets/cnrs-data-manager-pagination-style.css',
                'to' => ABSPATH . '/wp-includes/cnrs-data-manager/assets/cnrs-data-manager-pagination-style.css'
            ],
            [
                'from' => CNRS_DATA_MANAGER_PATH . '/templates/assets/cnrs-data-manager-script.js',
                'to' => ABSPATH . '/wp-includes/cnrs-data-manager/assets/cnrs-data-manager-script.js'
            ],
            [
                'from' => CNRS_DATA_MANAGER_PATH . '/templates/partials/cnrs-data-manager-inline.php',
                'to' => CNRS_DATA_MANAGER_DEPORTED_TEMPLATES_PATH . '/cnrs-data-manager-inline.php'
            ],
            [
                'from' => CNRS_DATA_MANAGER_PATH . '/templates/partials/cnrs-data-manager-card.php',
                'to' => CNRS_DATA_MANAGER_DEPORTED_TEMPLATES_PATH . '/cnrs-data-manager-card.php'
            ],
            [
                'from' => CNRS_DATA_MANAGER_PATH . '/templates/partials/cnrs-data-manager-sorted-title.php',
                'to' => CNRS_DATA_MANAGER_DEPORTED_TEMPLATES_PATH . '/cnrs-data-manager-sorted-title.php'
            ],
            [
                'from' => CNRS_DATA_MANAGER_PATH . '/templates/partials/cnrs-data-manager-list-item.php',
                'to' => CNRS_DATA_MANAGER_DEPORTED_TEMPLATES_PATH . '/cnrs-data-manager-list-item.php'
            ],
            [
                'from' => CNRS_DATA_MANAGER_PATH . '/templates/partials/cnrs-data-manager-info.php',
                'to' => CNRS_DATA_MANAGER_DEPORTED_TEMPLATES_PATH . '/cnrs-data-manager-info.php'
            ],
            [
                'from' => CNRS_DATA_MANAGER_PATH . '/templates/svg/list.svg',
                'to' => CNRS_DATA_MANAGER_DEPORTED_SVG_PATH . '/list.svg'
            ],
            [
                'from' => CNRS_DATA_MANAGER_PATH . '/templates/svg/grid.svg',
                'to' => CNRS_DATA_MANAGER_DEPORTED_SVG_PATH . '/grid.svg'
            ],
            [
                'from' => CNRS_DATA_MANAGER_PATH . '/templates/svg/loader.svg',
                'to' => CNRS_DATA_MANAGER_DEPORTED_SVG_PATH . '/loader.svg'
            ],
            [
                'from' => CNRS_DATA_MANAGER_PATH . '/languages/cnrs-data-manager-fr_FR.mo',
                'to' => ABSPATH . '/wp-content/languages/plugins/cnrs-data-manager-fr_FR.mo'
            ],
            [
                'from' => CNRS_DATA_MANAGER_PATH . '/languages/cnrs-data-manager-fr_FR.po',
                'to' => ABSPATH . '/wp-content/languages/plugins/cnrs-data-manager-fr_FR.po'
            ]
        ];

        foreach ($files as $file) {
            if (!file_exists($file['to'])) {
                copy($file['from'], $file['to']);
            }
        }
    }
}

if (!function_exists('cnrs_remove_folders')) {

    /**
     * Removes the folders and translations files for the CNRS Data Manager plugin.
     *
     * Removes the 'cnrs-data-manager' folder located in '/wp-includes' directory
     * and the translations files for the CNRS Data Manager plugin if they exist.
     * The translations files are removed only if the $all parameter is set to true.
     *
     * @param bool $all Optional. Whether to remove translations files. Default false.
     *
     * @return void
     */
    function cnrs_remove_folders(bool $all = false): void
    {
        rrmdir(ABSPATH . '/wp-includes/cnrs-data-manager');
        if ($all === true) {
            cnrs_remove_translations();
        }
    }
}

if (!function_exists('cnrs_remove_translations')) {

    /**
     * Removes translations files for the CNRS Data Manager plugin.
     *
     * Removes the .mo and .po translation files for the CNRS Data Manager plugin if they exist.
     * The .mo file is removed first, followed by the .po file.
     *
     * @return void
     */
    function cnrs_remove_translations(): void
    {
        $moFile = ABSPATH . '/wp-content/languages/plugins/cnrs-data-manager-fr_FR.mo';
        $poFile = ABSPATH . '/wp-content/languages/plugins/cnrs-data-manager-fr_FR.po';
        if (file_exists($moFile)) {
            unlink($moFile);
        }
        if (file_exists($poFile)) {
            unlink($poFile);
        }
    }
}

if (!function_exists('cnrs_dm_cloned_get_home_path')) {
    /**
     * Cloned function of the get_home_path(). It is same code except two lines of code
     * Get the absolute filesystem path to the root of the WordPress installation
     *
     * @return string Full filesystem path to the root of the WordPress installation
     */
    function cnrs_dm_cloned_get_home_path()
    {
        $home    = set_url_scheme(get_option('home'), 'http');
        $siteurl = set_url_scheme(get_option('siteurl'), 'http');

        // below two lines
        // extra added by Qs Conseils
        // when home is www. path and siteurl is non-www , the duplicator_get_home_psth() was  returning empty value
        $home    = str_ireplace('://www.', '://', $home);
        $siteurl = str_ireplace('://www.', '://', $siteurl);

        if (!empty($home) && 0 !== strcasecmp($home, $siteurl)  && $home !== $siteurl) {
            $wp_path_rel_to_home = str_ireplace($home, '', $siteurl); /* $siteurl - $home */
            $pos                 = strripos(str_replace('\\', '/', $_SERVER['SCRIPT_FILENAME']), trailingslashit($wp_path_rel_to_home));
            $home_path           = substr($_SERVER['SCRIPT_FILENAME'], 0, $pos);
            $home_path           = trailingslashit($home_path);
        } else {
            $home_path = ABSPATH;
        }
        return str_replace('\\', '/', $home_path);
    }
}

if (!function_exists('cnrs_dm_get_home_path')) {
    /**
     * Retrieves the home path for the CNRS Data Manager.
     *
     * @return string|null Returns the home path for the CNRS Data Manager,
     *                     or null if the home path cannot be determined.
     */
    function cnrs_dm_get_home_path(): ?string
    {
        static $homePath = null;
        if (is_null($homePath)) {
            if (!function_exists('get_home_path')) {
                require_once(ABSPATH . 'wp-admin/includes/file.php');
            }
            $homePath = wp_normalize_path(cnrs_dm_cloned_get_home_path());
            if ($homePath == '//' || $homePath == '') {
                $homePath = '/';
            } else {
                $homePath = rtrim($homePath, '/');
            }
        }
        return $homePath;
    }
}

if (!function_exists('wp_normalize_path')) {
    /**
     * Normalize a filesystem path.
     *
     * On Windows systems, replaces backslashes with forward slashes
     * and forces upper-case drive letters.
     * Allows for two leading slashes for Windows network shares, but
     * ensures that all other duplicate slashes are reduced to a single.
     *
     * @param string $path Path to normalize.
     *
     * @return string Normalized path.
     */
    function wp_normalize_path($path): string
    {
        $wrapper = '';
        if (wp_is_stream($path)) {
            list( $wrapper, $path ) = explode('://', $path, 2);
            $wrapper               .= '://';
        }

        // Standardise all paths to use /
        $path = str_replace('\\', '/', $path);

        // Replace multiple slashes down to a singular, allowing for network shares having two slashes.
        $path = preg_replace('|(?<=.)/+|', '/', $path);

        // Windows paths should uppercase the drive letter
        if (':' === substr($path, 1, 1)) {
            $path = ucfirst($path);
        }

        return $wrapper . $path;
    }
}

if (!function_exists('svgFromBase64')) {
    /**
     * Converts the given SVG string representation to an SVG with specified color and size.
     *
     * @param string $str The SVG string representation.
     * @param string $color The color to replace the black fill. Default is 'currentColor'.
     * @param int $size The size to replace the width and height. Default is 20.
     * @return string The modified SVG string representation.
     */
    function svgFromBase64(string $str, string $color = 'currentColor', int $size = 20)
    {
        return str_replace(['fill="black"', 'width="20"', 'height="20"'], ['fill="' . $color . '"', 'width="' . $size . '"', 'height="' . $size . '"'], $str);
    }
}

if (!function_exists('setDataFromSearch')) {

    /**
     * Sets the data from a search query.
     *
     * @param array $provider The array containing the data to search through.
     *
     * @return array Returns a new array containing the filtered data based on the search query,
     *               or the original array if no search query is provided.
     */
    function setDataFromSearch(array $provider): array
    {
        if (isset($_GET['cnrs-data-manager-search']) && strlen($_GET['cnrs-data-manager-search']) > 0) {
            $final = [];
            foreach ($provider as $row) {
                if (stripos(strtolower($row['nom']), strtolower($_GET['cnrs-data-manager-search'])) !== false || (isset($row['prenom']) && stripos(strtolower($row['nom'] . ' ' . $row['prenom']), strtolower($_GET['cnrs-data-manager-search'])) !== false)) {
                    $final[] = $row;
                }
            }
            return $final;
        }
        return $provider;
    }
}

if (!function_exists('preparePaginationAndSearch')) {

    /**
     * Prepare pagination and search.
     *
     * @param array $provider The array of data to be paginated and searched.
     * @return array The paginated and searched data.
     */
    function preparePaginationAndSearch(array $provider): array
    {
        $setSearch = setDataFromSearch($provider);
        $length = count($setSearch);
        if ($length > 0) {
            $limit = CNRS_DATA_MANAGER_LIMIT_OFFSET;
            if (isset($_GET['cnrs-data-manager-limit']) && in_array((int)$_GET['cnrs-data-manager-limit'], [5, 10, 20, 50], true)) {
                $limit = (int) $_GET['cnrs-data-manager-limit'];
            }
            $chunk = array_chunk($setSearch, $limit);
            $index = 0;
            if (isset($_GET['cnrs-data-manager-pagi']) && $_GET['cnrs-data-manager-pagi'] > 0 && $_GET['cnrs-data-manager-pagi'] <= count($chunk)) {
                $index = $_GET['cnrs-data-manager-pagi'] - 1;
            }
            return [
                'count' => $length,
                'data' => $chunk[$index],
                'pages' => count($chunk),
                'current' => $index + 1,
                'next' => $index >= count($chunk) - 1 ? null : $index + 2,
                'previous' => $index === 0 ? null : $index
            ];
        }
        return [
            'count' => 0,
            'data' => [],
            'pages' => 0,
            'current' => 1,
            'next' => null,
            'previous' => null
        ];
    }
}

if (!function_exists('selectCNRSDataProvider')) {

    /**
     * Selects the CNRS data provider based on the value of the 'cnrs-data-manager-provider' request parameter
     * and returns the corresponding data.
     *
     * @return array The selected CNRS data provider.
     */
    function selectCNRSDataProvider(): array
    {
        $supported = ['teams', 'services', 'platforms', 'agents'];
        $provider = [
            'teams' => CNRS_DATA_MANAGER_XML_DATA['teams'],
            'services' => CNRS_DATA_MANAGER_XML_DATA['services'],
            'platforms' => CNRS_DATA_MANAGER_XML_DATA['platforms'],
            'agents' => CNRS_DATA_MANAGER_XML_DATA['agents']
        ];
        if (isset($_GET['cnrs-data-manager-provider']) && in_array($_GET['cnrs-data-manager-provider'], $supported, true)) {
            return ['provider' => preparePaginationAndSearch($provider[$_GET['cnrs-data-manager-provider']]), 'type' => $_GET['cnrs-data-manager-provider']];
        }
        return ['provider' => preparePaginationAndSearch($provider['teams']), 'type' => 'teams'];
    }
}

if (!function_exists('humanizeCivility')) {

    /**
     * Converts the given civility abbreviation to a human-readable form.
     *
     * @param string|null $civility The civility abbreviation. Null if unspecified.
     * @return string The human-readable form of the civility.
     */
    function humanizeCivility(string|null $civility): string
    {
        if ($civility === null) {
            return __('Unspecified', 'cnrs-data-manager');
        }
        if (in_array($civility, ['M', 'Mr'])) {
            return __('Mister', 'cnrs-data-manager');
        }
        return __('Misses', 'cnrs-data-manager');
    }
}

if (!function_exists('sanitizeURIForPagination')) {


    /**
     * Sanitizes the current URI for pagination.
     *
     * @param int $page The page number to append to the URI.
     * @param string $mode The mode of pagination ('back' or 'cdm').
     * @return string Returns the sanitized URI with the appended page number.
     */
    function sanitizeURIForPagination(int $page, string $mode = 'back'): string
    {
        $current = $_SERVER['REQUEST_URI'];
        $trigger = $mode === 'back' ? 'cnrs-data-manager-pagi' : 'paged';
        $trigger = $mode === 'all' ? 'cdm-page' : $trigger;
        if (stripos($current, $trigger) !== false) {
            $current = explode($trigger, $current)[0];
            if (str_ends_with($current, '&')) {
                $current = substr($current, 0, -1);
            }
        }
        return $current . '&' . $trigger . '=' . $page;
    }
}

if (!function_exists('getCategoriesConfig')) {

    /**
     * Get the configuration options for categories list.
     *
     * @param string $name The name of the categories list.
     * @param int $selected The selected option value.
     * @param string $class The additional CSS class to be applied to the list. Default is an empty string.
     *
     * @return array The configuration options for the categories list.
     */
    function getCategoriesConfig(string $name, int $selected, string $class = ''): array
    {
        $c = 'cnrs-data-manager-categories-list';
        if (strlen($class) > 0) {
            $c .= ' ' . $class;
        }
        return [
            'echo' => false,
            'hide_empty' => false,
            'class' => $c,
            'orderby' => 'id',
            'name' => 'cnrs-data-manager-categories-list-' . $name,
            'required' => true,
            'selected' => $selected
        ];
    }
}

if (!function_exists('getAllPostsFromCategoryId')) {

    /**
     * Get all posts from a given category ID.
     *
     * @param array $data An associative array containing the necessary data:
     *                    - 'id': The category ID.
     *                    - 'name': The name of the category.
     * @return array An array containing the retrieved posts:
     *               - 'data': An array of posts, each containing the post ID and title.
     *               - 'name': The name of the category.
     */
    function getAllPostsFromCategoryId(array $data): array
    {
        $id = $data['id'];
        $args = array(
            'post_type'      => 'post',
            'cat'            => $id,
            'posts_per_page' => -1,
            'orderby'        => 'title',
            'order'          => 'ASC'
        );

        $the_query = new WP_Query( $args );
        $array = [];

        if ($the_query->have_posts()) {
            while ($the_query->have_posts()) {
                $the_query->the_post();
                $array[] = ['id' => get_the_ID(), 'title' => get_the_title()];
            }
        }
        return ['data' => $array, 'name' => $data['name']];
    }
}

if (!function_exists('isCNRSDataManagerToolsSelected')) {

    function isCNRSDataManagerToolsSelected(array $relations, int $cat_id, int $xml_id): bool
    {
        foreach ($relations as $array) {
            if ((int) $array['cat'] === $cat_id && (int) $array['xml'] === $xml_id) {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('cnrsReadShortCode')) {

    /**
     * Reads the shortcode attributes and returns the corresponding output.
     *
     * @param array $atts The shortcode attributes.
     * @return string The output generated by the shortcode.
     */
    function cnrsReadShortCode(array $atts = [
        'type' => null,
        'filter' => null,
        'default' => null,
        'target' => null,
        'text' => null,
        'entity' => null
    ]): string
    {
        $type = $atts['type'];
        $filter = $atts['filter'];
        $defaultView = $atts['default'];
        $target = $atts['target'];
        $text = $atts['text'];
        $entity = $atts['entity'];
        global $shortCodesCounter;

        /*if (!defined('CNRS_DATA_MANAGER_XML_DATA')) {
            define('CNRS_DATA_MANAGER_XML_DATA', Manager::defineArrayFromXML());
        }*/

        $id = get_the_ID();
        $displayMode = !in_array($type, ['navigate', 'filters', 'map'], true) ? Settings::getDisplayMode() : null;

        if ($displayMode === 'page' && !in_array($type, ['all', 'map', null, 'navigate', 'filters', 'page-title', 'pagination', 'projects'], true)) {

            if (isset($_GET['cnrs-dm-ref']) && ctype_digit($_GET['cnrs-dm-ref']) !== false) {
                $id = $_GET['cnrs-dm-ref'];
            } else {
                $shortCodesCounter++;
                wp_enqueue_style('cnrs-data-manager-styling', get_site_url() . '/wp-includes/cnrs-data-manager/assets/cnrs-data-manager-style.css', [], null);
                ob_start();
                include_once(dirname(__DIR__) . '/Core/Views/NoResult.php');
                return ob_get_clean();
            }
        }

        if (in_array($type, ['all', 'teams', 'services', 'platforms', null], true)) {

            if (!defined('CNRS_DATA_MANAGER_XML_DATA')) {
                define('CNRS_DATA_MANAGER_XML_DATA', Manager::defineArrayFromXML());
            }

            $shortCodesCounter++;
            $cats = getAllLinkedCats();
            $type = $type === null ? 'all' : $type;
            $isSelectorAvailable = $type === 'all' ? false : Settings::isSelectorAvailable($type);
            $renderMode = $filter !== null ? 'sorted' : 'simple';
            $entities = Agents::getAgents($id, $type, $filter);

            if ($type !== 'all' && empty($entities)) {
                ob_start();
                include_once(dirname(__DIR__) . '/Core/Views/NoResult.php');
                return ob_get_clean();
            }

            if ($type === 'all') {

                $isSilentPagination = Settings::getPaginationType();
                wp_enqueue_style('cnrs-data-manager-filters-styling', get_site_url() . '/wp-includes/cnrs-data-manager/assets/cnrs-data-manager-filters-style.css', [], null);

                if ($isSilentPagination === true) {
                    wp_enqueue_script(
                        'cnrs-data-manager-pagination-all-script',
                        plugin_dir_url(dirname(__DIR__)) . 'assets/js/cnrs-data-manager-pagination-all.js',
                        array(),
                        filemtime(dirname(__DIR__) . '/assets/js/cnrs-data-manager-pagination-all.js')
                    );
                }

                $pagination = [
                    'count' => $entities['count'],
                    'displayed_items' => $entities['displayed_items'],
                    'pages' => $entities['pages'],
                    'current' => $entities['current'],
                    'next' => $entities['next'],
                    'previous' => $entities['previous']
                ];
                $entities = $entities['data'];
            }
            wp_enqueue_style('cnrs-data-manager-styling', get_site_url() . '/wp-includes/cnrs-data-manager/assets/cnrs-data-manager-style.css', [], null);
            wp_enqueue_script('cnrs-data-manager-script', get_site_url() . '/wp-includes/cnrs-data-manager/assets/cnrs-data-manager-script.js', [], null);

            ob_start();
            include_once(dirname(__DIR__) . '/Core/Views/Agents.php');
            return ob_get_clean();

        } else if ($type === 'map') {

            wp_enqueue_script(
                'cnrs-data-manager-map-script',
                plugin_dir_url(dirname(__DIR__)) . 'assets/js/cnrs-data-manager-map.js',
                array('cnrs-data-manager-map-library-script'),
                filemtime(__DIR__ . '/assets/js/cnrs-data-manager-map.js')
            );

            wp_enqueue_script(
                'cnrs-data-manager-map-library-script',
                plugin_dir_url(dirname(__DIR__)) . 'assets/js/cnrs-data-manager-map-library.min.js',
                array(),
                '1.0'
            );

            wp_enqueue_style(
                'cnrs-data-manager-map-style',
                plugin_dir_url(dirname(__DIR__)) . 'assets/css/cnrs-data-manager-map.css',
                array(),
                filemtime(__DIR__ . '/assets/css/cnrs-data-manager-map.css')
            );

            $shortCodesCounter++;
            $json = Map::getData();
            ob_start();
            include_once(dirname(__DIR__) . '/Core/Views/Map.php');
            return ob_get_clean();

        } else if ($type === 'navigate'
            && $target !== null
            && $text !== null
            && strlen($target) > 0
            && strlen($text) > 0)
        {

            $shortCodesCounter++;

            wp_enqueue_style('cnrs-data-manager-styling', get_site_url() . '/wp-includes/cnrs-data-manager/assets/cnrs-data-manager-style.css', [], null);
            wp_enqueue_script('cnrs-data-manager-script', get_site_url() . '/wp-includes/cnrs-data-manager/assets/cnrs-data-manager-script.js', [], null);

            $link = stripos($target, '?') !== false ? $target . '&cnrs-dm-ref=' . $id  : $target . '?cnrs-dm-ref=' . $id;
            $link = $link[0] === '/' || stripos($link, 'http') === 0 ? $link : '/' . $link;
            return "<a class='cnrs-dm-front-btn cnrs-dm-front-btn-{$id}' data-shortcode='cnrs-data-manager-{$shortCodesCounter}' id='cnrs-dm-front-btn-{$shortCodesCounter}' href='{$link}'>{$text}</a>";

        } else if ($type === 'page-title') {

            $shortCodesCounter++;
            ob_start();
            include_once(dirname(__DIR__) . '/Core/Views/Title.php');
            return ob_get_clean();

        } else if ($type === 'filters') {

            $shortCodesCounter++;
            wp_enqueue_style('cnrs-data-manager-filters-styling', get_site_url() . '/wp-includes/cnrs-data-manager/assets/cnrs-data-manager-filters-style.css', [], null);

            $filters = Settings::getFilters();
            $filterType = get_queried_object()->name;
            if ($filterType !== 'project') {
                $parentCatSlug = get_queried_object()->slug;
                $terms = Settings::getSubCategoriesFromParentSlug($parentCatSlug);
                cnrsFiltersController($parentCatSlug);
            } else {
                $teams = getTeams(true);
                cnrsFiltersController();
            }

            ob_start();
            include_once(dirname(__DIR__) . '/Core/Views/Filters.php');
            return !empty($filters) ? ob_get_clean() : '';

        } else if ($type === 'pagination') {

            $shortCodesCounter++;
            $isSilentPagination = Settings::getPaginationType();
            $parentCatSlug = get_queried_object()->slug;
            wp_enqueue_style('cnrs-data-manager-pagination-styling', get_site_url() . '/wp-includes/cnrs-data-manager/assets/cnrs-data-manager-pagination-style.css', [], null);

            if ($isSilentPagination === true) {
                wp_enqueue_script(
                    'cnrs-data-manager-pagination-script',
                    plugin_dir_url(dirname(__DIR__)) . 'assets/js/cnrs-data-manager-pagination.js',
                    array(),
                    filemtime(dirname(__DIR__) . '/assets/js/cnrs-data-manager-pagination.js')
                );
            }
            ob_start();
            include_once(dirname(__DIR__) . '/Core/Views/Pagination.php');
            return ob_get_clean();

        } else if ($type === 'projects') {

            $shortCodesCounter++;
            wp_enqueue_style('cnrs-data-manager-styling', get_site_url() . '/wp-includes/cnrs-data-manager/assets/cnrs-data-manager-style.css', [], null);
            $post = get_queried_object();
            $id = $post->ID;
            $projects = Projects::getProjectsForTeam($id);

            ob_start();
            include_once(dirname(__DIR__) . '/Core/Views/Projects.php');
            return ob_get_clean();
        }
        return '';
    }
}

if (!function_exists('cnrsFiltersController')) {

    /**
     * The controller method for CNRS filters.
     *
     * @param string $currentCatSlug The slug of the current category.
     * @return void
     */
    function cnrsFiltersController(string $currentCatSlug = 'none'): void
    {
        $paged = get_query_var('paged') ? absint(get_query_var('paged')) : 1;
        $posts_per_page = get_query_var('cdm-limit') ? absint(get_query_var('cdm-limit')) : 10;

        if ($currentCatSlug !== 'none') {

            $tax_query = get_query_var('cdm-tax') && get_query_var('cdm-tax') !== $currentCatSlug
                ? [
                    'relation' => 'AND',
                    ['taxonomy' => 'category', 'field' => 'slug', 'terms' => [get_query_var('cdm-tax')]]
                ]
                : [];

            $args = [
                'posts_per_page' => $posts_per_page,
                'category_name' => $currentCatSlug,
                'paged' => $paged
            ];

        } else {

            $teamID = absint(get_query_var('cdm-team'));
            $forbiddenProjectsIDs = $teamID === 0
                ? []
                : Projects::getAllForbiddenProjectsIDs($teamID);
            $tax_query = [];

            $args = [
                'posts_per_page' => $posts_per_page,
                'post_type' => 'project',
                'paged' => $paged
            ];

            if (!empty($forbiddenProjectsIDs)) {
                $args['post__in'] = $forbiddenProjectsIDs;
            }
        }

        if (get_query_var('s')) {
            $args['s'] = get_query_var('s');
        }

        if (get_query_var('cdm-year') && get_query_var('cdm-year') !== 'all') {
            $args['year'] = (int) get_query_var('cdm-year');
        }

        if (!empty($tax_query)) {
            $args['tax_query'] = $tax_query;
        }

        global $wp_query;
        $wp_query = new WP_Query($args);
    }
}

if (!function_exists('getAllLinkedCats')) {

    /**
     * Retrieves all linked categories.
     *
     * Retrieves all linked categories by iterating over the relations array obtained
     * from the static method getAllRelations() of the Tools class. For each relation,
     * it retrieves the associated post using the 'cat' value as the post ID, and adds
     * the 'ref', 'url', and 'title' properties to the final result array.
     *
     * @return array An array containing the 'ref', 'url', and 'title' properties of each linked category.
     */
    function getAllLinkedCats(): array
    {
        $relations = Tools::getAllRelations();
        $final = [];
        foreach ($relations as $relation) {
            $post = get_post($relation['cat'], ARRAY_A);
            $final[] = [
                'ref' => $relation['xml'],
                'type' => $relation['type'],
                'url' => $post['guid'],
                'title' => $post['post_title']
            ];
        }
        return $final;
    }
}

if (!function_exists('filterEntity')) {

    /**
     * Filters an array of relations to find a specific entity by its ID.
     *
     * @param int $id The ID of the entity to search for.
     * @param array $relations The array of relations to filter.
     * @return array|null Returns an array containing the title and URL of the found entity, or null if not found.
     */
    function filterEntity(int $id, array $relations, string $type): array|null
    {
        foreach ($relations as $relation) {
            if ((int) $relation['ref'] === $id && $type === $relation['type']) {
                return ['title' => $relation['title'], 'url' => $relation['url']];
            }
        }
        return null;
    }
}

if (!function_exists('filterAgents')) {

    /**
     * Filters an array of agents to add extra information from the entitiesData array.
     *
     * @param array $agents The array of agents to filter.
     * @return array Returns an array of agents with additional information from the entitiesData array.
     */
    function filterAgents(array $agents): array
    {
        $entitiesData = getAllLinkedCats();
        $results = [];
        foreach ($agents as $agent) {

            $teams = $agent['equipes'];
            $filteredTeams = [];
            foreach ($teams as $team) {
                $exist = filterEntity($team['equipe_id'], $entitiesData, 'teams');
                if ($exist !== null) {
                    $team['extra'] = $exist;
                    $filteredTeams[] = $team;
                }
            }
            $agent['equipes'] = $filteredTeams;

            $services = $agent['services'];
            $filteredServices = [];
            foreach ($services as $service) {
                $exist = filterEntity($service['service_id'], $entitiesData, 'services');
                if ($exist !== null) {
                    $service['extra'] = $exist;
                    $filteredServices[] = $service;
                }
            }
            $agent['services'] = $filteredServices;

            $platforms = $agent['plateformes'];
            $filteredPlatforms = [];
            foreach ($platforms as $platform) {
                $exist = filterEntity($platform['plateforme_id'], $entitiesData, 'platforms');
                if ($exist !== null) {
                    $platform['extra'] = $exist;
                    $filteredPlatforms[] = $platform;
                }
            }
            $agent['plateformes'] = $filteredPlatforms;
            $results[] = $agent;
        }
        return $results;
    }
}

if (!function_exists('inlineInfo')) {

    /**
     * Constructs an inline information string based on the given agent data.
     *
     * @param array $agent The agent data to construct the inline information from.
     *                     The array should have the following structure:
     *                     [
     *                         'tutelle' => string|null,
     *                         'responsabilite' => string|null
     *                     ]
     * @return string Returns a comma-separated string that contains the non-null values
     *                of 'tutelle', and 'responsabilite' fields of the agent data.
     *                If all three fields are null or empty, an empty string is returned.
     */
    function inlineInfo(array $agent): string
    {
        $line = [];
        if ($agent['tutelle'] !== null) {
            $line[] = '<span>' . $agent['tutelle'] . '</span>';
        }
        if ($agent['responsabilite'] !== null) {
            $line[] = '<span>' . $agent['responsabilite'] . '</span>';
        }
        if (empty($line)) {
            return '';
        }
        return implode('<span class="cnrs-dm-front-comma">, </span>', $line);
    }
}

if (!function_exists('addQueryVars')) {

    /**
     * Adds a query variable "limit" to the global query variables.
     *
     * @return void
     */
    function addQueryVars(): void
    {
        add_filter('query_vars', function ($qvars) {
            $qvars[] = 'cdm-limit';
            $qvars[] = 'cdm-tax';
            $qvars[] = 'cdm-year';
            $qvars[] = 'cdm-parent';
            $qvars[] = 'cdm-team';
            return $qvars;
        });
    }
}

if (!function_exists('getTeams')) {

    /**
     * Retrieves teams with their names and optionally their WordPress names.
     *
     * @param bool $onlyNames If true, only the names of the teams will be returned.
     * @return array Returns an array of teams, each containing the ID and name of the team. If $onlyNames is true, the array will only contain name data.
     */
    function getTeams(bool $onlyNames = false): array
    {
        $xmlTeams = CNRS_DATA_MANAGER_XML_DATA['teams'];
        $teams = Tools::getTeams();
        $result = [];
        foreach ($xmlTeams as $xmlTeam) {
            $id = $xmlTeam['equipe_id'];
            $xml_name = $xmlTeam['nom'];
            $wp_name = (function () use ($teams, $id) {
                foreach ($teams as $team) {
                    if ((int) $team['xml'] === (int) $id) {
                        $post = get_post((int) $team['cat']);
                        $title = $post->post_title;
                        return strlen($title) > 0 ? $title : null;
                    }
                }
                return null;
            })();
            if ($onlyNames === true && $wp_name !== null) {
                $result[] = [
                    'id' => $id,
                    'name' => $wp_name
                ];
            } else if ($onlyNames === false) {
                $result[] = [
                    'id' => $id,
                    'name' => $xml_name . ($wp_name !== null
                            ? ' (' . $wp_name . ')'
                            : ' (' . __('not assigned', 'cnrs-data-manager') . ')')
                ];
            }
        }
        return $result;
    }
}

if (!function_exists('getProjects')) {

    /**
     * Retrieves the list of projects.
     *
     * @return array Returns an array containing the projects.
     */
    function getProjects(): array
    {
        $projects = get_posts([
            'post_type' => 'project',
            'orderby'    => 'ID',
            'numberposts' => -1,
        ]);
        $relations = Projects::getProjects();
        $results = [];
        foreach ($projects as $project) {
            $res = [
                'id' => $project->ID,
                'url' => $project->guid,
                'name' => $project->post_title,
                'excerpt' => $project->post_excerpt,
                'image' => get_the_post_thumbnail($project->ID),
                'teams' => []
            ];
            foreach ($relations as $relation) {
                if ((int) $relation['project_id'] === $project->ID) {
                    $res['teams'][] = [
                        'id' => $relation['team_id'],
                        'order' => $relation['display_order']
                    ];
                }
            }

            $results[] = $res;
        }
        return $results;
    }
}

if (!function_exists('isTeamSelected')) {

    /**
     * Checks if a team is selected based on its ID.
     *
     * @param int $teamID The ID of the team to check.
     * @param array $teams The array of teams to search in.
     * @return bool Returns true if the team is selected, false otherwise.
     */
    function isTeamSelected(int $teamID, array $teams): bool
    {
        foreach ($teams as $team) {
            if ((int) $team['id'] === $teamID) {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('isOrderSelected')) {

    /**
     * Checks if a specific order is selected for a given team.
     *
     * @param int $value The order value to check.
     * @param int $teamID The ID of the team.
     * @param array $teams The array of teams to check.
     * @return bool Returns true if the order is selected for the team, false otherwise.
     */
    function isOrderSelected(int $value, int $teamID, array $teams): bool
    {
        if ($value > 0) {
            foreach ($teams as $team) {
                if ($team['order'] !== null
                    && (int) $team['id'] === $teamID
                    && (int) $team['order'] === $value
                ) {
                    return true;
                }
            }
        }
        return false;
    }
}

if (!function_exists('updateProjectsRelations')) {


    /**
     * Updates the relationships between projects and teams.
     *
     * This method processes the data sent via the $_POST['cnrs-data-manager-project'] array
     * to update the relationships between projects and teams. It retrieves the project IDs
     * from the $_POST['cnrs-data-manager-project'] array and then checks if there are team
     * relationships for each project specified in the $_POST['cnrs-data-manager-project-teams-{projectID}']
     * array. For each team relationship, it constructs an associative array containing the
     * team ID, project ID, and the display order. This array is then added to the $inserts array,
     * which will be used to update the relationships using the Tools::updateProjectsRelations() method.
     *
     * Note: The Tools class is assumed to have a static method called "updateProjectsRelations"
     * that accepts an array of relationships as a parameter.
     *
     * @return void
     */
    function updateProjectsRelations(): void
    {
        if (isset($_POST['cnrs-data-manager-project'])) {

            $projects = $_POST['cnrs-data-manager-project'];
            $inserts = [];

            foreach ($projects as $projectID) {
                if (isset($_POST['cnrs-data-manager-project-teams-' . $projectID])) {

                    $teams = $_POST['cnrs-data-manager-project-teams-' . $projectID];
                    $orders = $_POST['cnrs-data-manager-project-order-' . $projectID];

                    for ($i = 0; $i < count($teams); $i++) {
                        $inserts[] = [
                            'team_id' => (int) $teams[$i],
                            'project_id' => (int) $projectID,
                            'display_order' => $orders[$i] === '0' ? null : (int) $orders[$i]
                        ];
                    }
                }
            }
            Projects::updateProjectsRelations($inserts);
        }
    }
}