<?php

defined('ABSPATH') || exit;

use CnrsDataManager\Core\Models\Agents;
use CnrsDataManager\Core\Models\Map;
use CnrsDataManager\Core\Models\Settings;
use CnrsDataManager\Core\Models\Tools;

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
                'from' => CNRS_DATA_MANAGER_PATH . '/templates/cnrs-data-manager-style.css',
                'to' => ABSPATH . '/wp-includes/cnrs-data-manager/cnrs-data-manager-style.css'
            ],
            [
                'from' => CNRS_DATA_MANAGER_PATH . '/templates/cnrs-data-manager-script.js',
                'to' => ABSPATH . '/wp-includes/cnrs-data-manager/cnrs-data-manager-script.js'
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
     * Sanitize the URI by removing any existing pagination parameter and adding the new page number.
     *
     * @param int $page The page number to be added to the URI.
     * @return string The sanitized URI with the page parameter included.
     */
    function sanitizeURIForPagination(int $page): string
    {
        $current = $_SERVER['REQUEST_URI'];
        $trigger = 'cnrs-data-manager-pagi';
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
    function cnrsReadShortCode(array $atts = ['type' => null, 'filter' => null, 'default' => null, 'target' => null, 'text' => null]): string
    {
        $type = $atts['type'];
        $filter = $atts['filter'];
        $defaultView = $atts['default'];
        $target = $atts['target'];
        $text = $atts['text'];
        global $shortCodesCounter;

        $id = get_the_ID();
        $displayMode = Settings::getDisplayMode();

        if ($displayMode === 'page' && !in_array($type, ['all', 'map', null, 'navigate'], true)) {

            if (isset($_GET['cnrs-dm-ref']) && is_int($_GET['cnrs-dm-ref']) !== false) {
                $id = $_GET['cnrs-dm-ref'];
            } else {
                $shortCodesCounter++;
                wp_enqueue_style('cnrs-data-manager-styling', get_site_url() . '/wp-includes/cnrs-data-manager/cnrs-data-manager-style.css', [], null);
                ob_start();
                include_once(dirname(__DIR__) . '/Core/Views/NoResult.php');
                return ob_get_clean();
            }
        }

        if (in_array($type, ['all', 'teams', 'services', 'platforms', null], true)) {

            $shortCodesCounter++;
            $cats = getAllLinkedCats();
            $type = $type === null ? 'all' : $type;
            $isSelectorAvailable = $type === 'all' ? false : Settings::isSelectorAvailable($type);
            $renderMode = $filter !== null ? 'sorted' : 'simple';
            $entities = Agents::getAgents($id, $type, $filter);

            if (empty($entities)) {
                return '';
            }

            wp_enqueue_style('cnrs-data-manager-styling', get_site_url() . '/wp-includes/cnrs-data-manager/cnrs-data-manager-style.css', [], null);
            wp_enqueue_script('cnrs-data-manager-script', get_site_url() . '/wp-includes/cnrs-data-manager/cnrs-data-manager-script.js', [], null);

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

            wp_enqueue_style('cnrs-data-manager-styling', get_site_url() . '/wp-includes/cnrs-data-manager/cnrs-data-manager-style.css', [], null);
            wp_enqueue_script('cnrs-data-manager-script', get_site_url() . '/wp-includes/cnrs-data-manager/cnrs-data-manager-script.js', [], null);

            $link = stripos($target, '?') !== false ? $target . '&cnrs-dm-ref=' . $id  : $target . '?cnrs-dm-ref=' . $id;
            $link = $link[0] === '/' || stripos($link, 'http') === 0 ? $link : '/' . $link;
            return "<a class='cnrs-dm-front-btn cnrs-dm-front-btn-{$id}' id='cnrs-dm-front-btn-{$shortCodesCounter}' href='{$link}'>{$text}</a>";
        }
        return '';
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