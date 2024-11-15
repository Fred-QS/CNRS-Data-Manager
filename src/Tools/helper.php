<?php

defined('ABSPATH') || exit;

use CnrsDataManager\Core\Models\Agents;
use CnrsDataManager\Core\Models\Map;
use CnrsDataManager\Core\Models\Settings;
use CnrsDataManager\Core\Models\Tools;
use CnrsDataManager\Core\Models\Projects;
use CnrsDataManager\Core\Models\Forms;
use CnrsDataManager\Core\Models\Collaborators;
use CnrsDataManager\Core\Controllers\Manager;
use CnrsDataManager\Core\Controllers\HttpClient;
use CnrsDataManager\Core\Controllers\Emails;
use CnrsDataManager\Core\Controllers\Controller;
use CnrsDataManager\Core\Controllers\TemplateLoader;
use CnrsDataManager\Core\Controllers\Page;
use CnrsDataManager\Libs\MarkDown\ParseDown;

$shortCodesCounter = 0;

$dumpStyle = '<style>
    .dumper-container::-webkit-scrollbar {
        width: 16px;
        height: 16px;
    }

    .dumper-container::-webkit-scrollbar-track {
        background-color: transparent;
        border-radius: 100px;
    }

    .dumper-container::-webkit-scrollbar-thumb {
        background-color: gold;
        border-radius: 100px;
        background-clip: padding-box;
        border: 5px solid transparent;
        -webkit-border-radius: 100px;
        -webkit-box-shadow: inset -1px -1px 0 rgba(0, 0, 0, 0.05), inset 1px 1px 0 rgba(0, 0, 0, 0.05);
        -webkit-transition: all linear 0.2s;
    }

    .dumper-container::-webkit-scrollbar-button {
        width: 0;
        height: 0;
        display: none;
    }

    .dumper-container::-webkit-scrollbar-corner {
        background-color: transparent;
    }
    .dumper-container {
        width: max-content; 
        overflow-x: auto; 
        max-width: calc(100% - 20px); 
        background-color: #3c3c3c; 
        box-sizing: border-box; 
        padding: 10px 10px 0; 
        line-height: 1.3em; 
        margin: 10px;
    }
    .dumper-details {
        font-size: 11px; 
        color: violet; 
        line-height: 1.2em; 
        padding: 0;
    }
    .dumper-pre {
        color: #f1f1f1;
        font-size: 12px;
        padding: 5px 10px 10px 0;
        width: max-content;
        box-sizing: border-box;
    }
</style>';

if (!function_exists('dump')) {

    /**
     * Dump the provided variables for debugging purposes.
     *
     * @param mixed ...$vars The variables to be dumped.
     * @return void
     */
    function dump(mixed ...$vars): void
    {
        global $dumpStyle;
        echo $dumpStyle;
        echo '<div class="dumper-container">';
        ob_start();
        foreach ($vars as $key => $var) {
            echo '<p class="dumper-details"><b>File:</b> /' . str_replace(ABSPATH, '', debug_backtrace()[0]['file']) . '</p>';
            echo '<p class="dumper-details"><b>Line:</b> ' . str_replace(ABSPATH, '', debug_backtrace()[0]['line']) . '</p>';
            echo '<pre class="dumper-pre">';
            var_dump($var);
            echo '</pre>';
        }
        $content = ob_get_clean();
        echo parseDumper($content);
        echo '</div>';
    }
}

if (!function_exists('dd')) {

    /**
     * Dump and die function for debugging purposes.
     *
     * @param mixed ...$vars The variables to be dumped.
     * @return void
     */
    function dd(mixed ...$vars): void
    {
        global $dumpStyle;
        echo $dumpStyle;
        echo '<div class="dumper-container">';
        ob_start();
        foreach ($vars as $key => $var) {
            echo '<p class="dumper-details"><b>File:</b> /' . str_replace(ABSPATH, '', debug_backtrace()[0]['file']) . '</p>';
            echo '<p class="dumper-details"><b>Line:</b> ' . str_replace(ABSPATH, '', debug_backtrace()[0]['line']) . '</p>';
            echo '<pre class="dumper-pre">';
            var_dump($var);
            echo '</pre>';
        }
        $content = ob_get_clean();
        echo parseDumper($content);
        echo '</div>';
        die();
    }
}

if (!function_exists('parseDumper')) {

    /**
     * Parse the dumper content to highlight specific elements with colors.
     *
     * @param string $content The content to parse.
     * @return string The parsed content with highlighted elements.
     */
    function parseDumper(string $content): string
    {
        $content = str_replace(
            [
                "=>\n",
                "{",
                "}",
                "string",
                "array",
                "object",
                "int",
                "[\"",
                "\"]"
            ],
            [
                " <span style='color: dodgerblue'>=></span>",
                "<span style='color: gold'>{</span>",
                "<span style='color: gold'>}</span>",
                "<span style='color: limegreen'>String</span>",
                "<span style='color: limegreen'>Array</span>",
                "<span style='color: limegreen'>Object</span>",
                "<span style='color: limegreen'>Int</span>",
                "\"",
                "\""
            ],
            $content
        );
        $content = preg_replace_callback(
            '|[^"](.*)[^"]|',
            function ($matches) {
                return '<span style="color: greenyellow">' . $matches[0] . '</span>';
            },
            $content
        );
        return preg_replace_callback(
            '|[(](.*)[)]|',
            function ($matches) {
                return '<span style="color: #fff;">' . $matches[0] . '</span>';
            },
            $content
        );
    }
}

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
                'from' => CNRS_DATA_MANAGER_PATH . '/templates/assets/cnrs-data-manager-email.css',
                'to' => ABSPATH . '/wp-includes/cnrs-data-manager/assets/cnrs-data-manager-email.css'
            ],
            [
                'from' => CNRS_DATA_MANAGER_PATH . '/templates/assets/cnrs-data-manager-categories.css',
                'to' => ABSPATH . '/wp-includes/cnrs-data-manager/assets/cnrs-data-manager-categories.css'
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
                'from' => CNRS_DATA_MANAGER_PATH . '/templates/partials/cnrs-data-manager-email-footer.php',
                'to' => CNRS_DATA_MANAGER_DEPORTED_TEMPLATES_PATH . '/cnrs-data-manager-email-footer.php'
            ],
            [
                'from' => CNRS_DATA_MANAGER_PATH . '/templates/partials/cnrs-data-manager-email-header.php',
                'to' => CNRS_DATA_MANAGER_DEPORTED_TEMPLATES_PATH . '/cnrs-data-manager-email-header.php'
            ],
            [
                'from' => CNRS_DATA_MANAGER_PATH . '/templates/partials/cnrs-data-manager-post-preview.php',
                'to' => CNRS_DATA_MANAGER_DEPORTED_TEMPLATES_PATH . '/cnrs-data-manager-post-preview.php'
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
            $homePath = cnrs_normalize_path(cnrs_dm_cloned_get_home_path());
            if ($homePath == '//' || $homePath == '') {
                $homePath = '/';
            } else {
                $homePath = rtrim($homePath, '/');
            }
        }
        return $homePath;
    }
}

if (!function_exists('cnrs_normalize_path')) {
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
    function cnrs_normalize_path($path): string
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
        $trigger = $mode === 'back' ? 'cnrs-data-manager-pagi' : 'cdm-page';
        $trigger = $mode === 'all' ? 'cdm-page' : $trigger;
        if (stripos($current, $trigger) !== false) {
            $current = explode($trigger, $current)[0];
            if (str_ends_with($current, '&')) {
                $current = substr($current, 0, -1);
            }
        }
        return str_contains($current, '?')
            ? $current . '&' . $trigger . '=' . $page
            : $current . '?' . $trigger . '=' . $page;
    }
}

if (!function_exists('getCategoriesConfig')) {

    /**
     * Get the configuration options for categories list.
     *
     * @param string $name The name of the categories list.
     * @param array $selected The selected option values.
     * @param string $class The additional CSS class to be applied to the list. Default is an empty string.
     *
     * @return array The configuration options for the categories list.
     */
    function getCategoriesConfig(string $name, array $selected, string $class = ''): array
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
     * Get all posts from a specific category.
     *
     * @param string $cat The category identifier ('services', 'platforms', or 'teams').
     * @return array Returns an array containing the post data for the specified category.
     */
    function getAllPostsFromCategoryId(string $cat): array
    {
        $pll = function_exists('pll_the_languages');
        $array = [];
        $list = ['fr'];
        $name = null;
        $flags = ['fr' => null];

        if ($pll === true) {
            $list = pll_languages_list();
            $flags = cnrs_get_languages_from_pll([], false);
        }

        foreach ($list as $item) {

            $data = match ($cat) {
                'services' => Tools::getServicesCategoryId($item),
                'platforms' => Tools::getPlatformsCategoryId($item),
                default => Tools::getTeamsCategoryId($item)
            };

            if ($name === null && $item === 'fr') {
                $name = $data['name'];
            }

            $id = $data['id'];
            $args = array(
                'post_type'      => 'post',
                'cat'            => $id,
                'posts_per_page' => -1,
                'orderby'        => 'title',
                'order'          => 'ASC'
            );

            $the_query = new WP_Query( $args );

            if ($the_query->have_posts()) {
                while ($the_query->have_posts()) {
                    $the_query->the_post();
                    $array[$item][] = [
                        'id' => get_the_ID(),
                        'title' => get_the_title(),
                        'flag' => $flags[$item],
                        'lang' => $item
                    ];
                }
            }
        }

        return ['data' => $array, 'name' => $name, 'pll' => $pll];
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

if (!function_exists('getManagerEmailFromForm')) {

    function getManagerEmailFromForm(string $json): string
    {
        $convention = json_decode($json, true)['convention'];
        return $convention['available'] === '1'
            ? $convention['primary_email']
            : $convention['secondary_email'];
    }
}

if (!function_exists('isValidatedForm')) {

    function isValidatedForm(string $json, int $defined_limit): bool
    {
        $elements = json_decode($json, true)['elements'];
        foreach ($elements as $element) {
            if ($element['type'] === 'date' && $element['data']['isReference'] === true) {
                $missionDate = $element['data']['value'][0] ?? null;
                if ($missionDate !== null) {
                    $limitDate = date("Y-m-d");
                    while ($defined_limit > 0) {
                        $limitDate = date('Y-m-d', strtotime($limitDate. ' + 1 days'));
                        if (date('D', strtotime($limitDate)) !== 'Sat' && date('D', strtotime($limitDate)) !== 'Sun') {
                            $defined_limit--;
                        }
                    }
                    $missionTimeStamp = strtotime($missionDate);
                    $limitTimeStamp = strtotime($limitDate);
                    return $missionTimeStamp > $limitTimeStamp;
                }
            }
        }
        return true;
    }
}

if (!function_exists('incrementRevisionForm')) {

    /**
     * Increment the revision number of a form.
     *
     * @param string $json The JSON string representation of the form.
     * @return string The updated JSON string representation of the form with the revision number incremented.
     */
    function incrementRevisionForm(string $json): string
    {
        $form = json_decode($json, true);
        $form['revisions'] = $form['revisions'] + 1;
        return json_encode($form);
    }
}

if (!function_exists('cnrsReadShortCode')) {

    /**
     * Reads the shortcode attributes and returns the corresponding output.
     *
     * @param array $atts The shortcode attributes.
     * @return string The output generated by the shortcode.
     * @return string|null The content between two shortcode tags.
     */
    function cnrsReadShortCode(array $atts = [
        'type' => null,
        'filter' => null,
        'default' => null,
        'target' => null,
        'text' => null,
        'entity' => null
    ], ?string $content = null): string
    {
        $type = $atts['type'];
        $filter = $atts['filter'];
        $defaultView = $atts['default'];
        $target = $atts['target'];
        $text = $atts['text'];
        $entity = $atts['entity'];
        global $shortCodesCounter;

        $id = get_the_ID();
        $displayMode = !in_array($type, ['navigate', 'filters', 'map'], true) ? Settings::getDisplayMode() : null;

        if ($displayMode === 'page' && !in_array($type, ['all', 'map', null, 'navigate', 'filters', 'page-title', 'pagination', 'projects', 'form', 'revision-manager', 'revision-agent', 'revision-funder', 'publications', 'categories', 'collabs', 'if-collabs', 'project-slider'], true)) {

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
            return "<a class='cnrs-dm-front-btn cnrs-dm-front-btn-std cnrs-dm-front-btn-{$id}' data-shortcode='cnrs-data-manager-{$shortCodesCounter}' id='cnrs-dm-front-btn-{$shortCodesCounter}' href='{$link}'>{$text}</a>";

        } else if ($type === 'page-title') {

            $shortCodesCounter++;
            ob_start();
            include_once(dirname(__DIR__) . '/Core/Views/Title.php');
            return ob_get_clean();

        } else if ($type === 'form') {

            $conventions = Forms::getConventions();

            if (empty($conventions)) {
                global $wp_query;
                $wp_query->set_404();
                status_header(404);
                get_template_part(404);
                exit();
            }

            wp_enqueue_style('cnrs-data-manager-styling', get_site_url() . '/wp-includes/cnrs-data-manager/assets/cnrs-data-manager-style.css', [], null);
            wp_enqueue_script('cnrs-data-manager-pad-sign-script', 'https://cdn.jsdelivr.net/npm/signature_pad@4.2.0/dist/signature_pad.umd.min.js', [], null);
            wp_enqueue_script('cnrs-data-manager-script', get_site_url() . '/wp-includes/cnrs-data-manager/assets/cnrs-data-manager-script.js', ['cnrs-data-manager-pad-sign-script'], null);

            $json = Forms::getCurrentForm();
            $form = json_decode($json, true);
            $toggles = getFormToggles($form);

            $user = cnrs_dm_connexion();
            $xml = $user === null ? Manager::defineArrayFromXML()['agents'] : [];
            $error = is_connexion_error($xml);
            $agents = json_encode($xml);
            $validated = false;

            $errors = include __DIR__ . '/errors.php';
            $days_limit = Settings::getDaysLimit();
            $month_limit = Settings::getMonthLimit();

            if ($user !== null) {

                if (isset($_POST['cnrs-dm-front-mission-form-original']) && strlen($_POST['cnrs-dm-front-mission-form-original']) > 0) {
                    $uuid = $_POST['cnrs-dm-front-mission-uuid'];
                    $hasFees = $_POST['cnrs-dm-front-toggle-' . Manager::FEES_UUID];
                    $funderEmail = $_POST['cnrs-dm-front-funder-email'] ?? null;
                    $jsonForm = Manager::newFilledForm($_POST, $uuid);
                    $definedLimit = (int) $_POST['cnrs-dm-front-mission-intl'] === 1 ? $month_limit : $days_limit;
                    $validated = isValidatedForm($jsonForm, $definedLimit);
                    $revision_uuid = Forms::recordNewForm($jsonForm, stripslashes($json), $user->email, $uuid, $validated, $hasFees, $funderEmail);
                    if ($validated === false) {
                        $adminEmails = Settings::getAdminEmails();
                        Emails::sendToAdmins($adminEmails);
                    }else if ((int) $hasFees === 1 && $funderEmail !== null) {
                        Emails::sendToFunder($funderEmail, $revision_uuid);
                    } else {
                        $managerEmail = getManagerEmailFromForm($jsonForm);
                        Emails::sendToManager($managerEmail, $revision_uuid);
                    }
                    Emails::sendConfirmationEmail($user->email);
                    $validated = true;
                }

                $agent = Agents::getAgentByEmail($user->email, Manager::defineArrayFromXML()['agents']);

                ob_start();
                include_once(dirname(__DIR__) . '/Core/Views/MissionForm.php');
                return ob_get_clean();

            } else {

                ob_start();
                include_once(dirname(__DIR__) . '/Core/Views/Connexion.php');
                return ob_get_clean();
            }

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

        } else if (in_array($type, ['revision-manager', 'revision-agent', 'revision-funder'], true)) {

            if (!Forms::revisionExists()) {
                global $wp_query;
                $wp_query->set_404();
                status_header(404);
                get_template_part(404);
                exit();
            }

            wp_enqueue_style('cnrs-data-manager-styling', get_site_url() . '/wp-includes/cnrs-data-manager/assets/cnrs-data-manager-style.css', [], null);
            wp_enqueue_script('cnrs-data-manager-pad-sign-script', 'https://cdn.jsdelivr.net/npm/signature_pad@4.2.0/dist/signature_pad.umd.min.js', [], null);
            wp_enqueue_script('cnrs-data-manager-script', get_site_url() . '/wp-includes/cnrs-data-manager/assets/cnrs-data-manager-script.js', ['cnrs-data-manager-pad-sign-script'], null);

            $data = Forms::getRevision();
            $observations = $data->observations !== null ? json_decode($data->observations, true) : [];
            $validated = false;

            $errors = include __DIR__ . '/errors.php';
            $days_limit = Settings::getDaysLimit();
            $month_limit = Settings::getMonthLimit();

            if ($type === 'revision-funder'
                && isset($_POST['cnrs-dm-front-funder-revision']))
            {
                if ($_POST['cnrs-dm-front-funder-revision'] === 'ok') {

                    $data->form = incrementRevisionForm($data->form);
                    $uuid = wp_generate_uuid4();
                    $data->uuid = $uuid;
                    $data->sender = 'FUNDER';
                    $data->created_at = date("y-m-d H:i:s");
                    $managerEmail = getManagerEmailFromForm($data->form);
                    Forms::recordFunderValidation($data);
                    Emails::sendToManager($managerEmail, $uuid);

                } else {

                    Forms::setAbandonForm($data->form_id);
                    Emails::sendAbandonForm($data->agent_email, true);
                }
                $validated = true;

            } else if ($type === 'revision-manager'
                && isset($_POST['cnrs-dm-front-manager-revision'])
                && $_POST['cnrs-dm-front-manager-revision'] === 'ok')
            {
                $data->form = incrementRevisionForm($data->form);
                $uuid = wp_generate_uuid4();
                $data->uuid = $uuid;
                $data->sender = 'MANAGER';
                $data->manager_name = $_POST['cnrs-dm-front-revision-manager-name'];
                $data->manager_email = $_POST['cnrs-dm-front-revision-manager-email'];
                $data->created_at = date("y-m-d H:i:s");

                if (isset($_POST['cnrs-dm-front-observation'])) {
                    $posts = [];
                    foreach ($_POST['cnrs-dm-front-observation'] as $post) {
                        $posts[] = ['index' => (int) $post['index'], 'observation' => $post['observation']];
                    }
                    $data->observations = json_encode($posts);
                    Forms::recordObservation($data);
                    Emails::sendRevisionToAgent($data->agent_email, $uuid);

                } else {

                    Forms::recordObservation($data, true);
                    Emails::sendValidatedForm($data->agent_email, $data->form_uuid);
                    $genericEmail = Settings::getGenericEmail();
                    if ($genericEmail !== null) {
                        Emails::sendValidatedForm($genericEmail, $data->form_uuid, true);
                    }
                }
                $validated = true;

            } else if ($type === 'revision-agent'
                && isset($_POST['cnrs-dm-front-agent-revision'])
                && $_POST['cnrs-dm-front-agent-revision'] === 'ok')
            {
                $uuid = wp_generate_uuid4();
                $data->form = incrementRevisionForm($data->form);
                $data->uuid = $uuid;
                $data->sender = 'AGENT';
                $data->created_at = date("y-m-d H:i:s");
                $data->form = Manager::updateForm($_POST, $data->form);
                $managerEmail = getManagerEmailFromForm($data->form);
                Forms::updateForm($data);
                Emails::sendToManager($managerEmail, $uuid);
                Emails::sendConfirmationEmail($data->agent_email);
                $validated = true;

            }

            $json = $data->form;
            unset($data->form);
            $form = json_decode($json, true);

            $agent = Agents::getAgentByEmail($data->agent_email, Manager::defineArrayFromXML()['agents']);

            ob_start();
            include_once(dirname(__DIR__) . '/Core/Views/RevisionForm.php');
            return ob_get_clean();

        } else if ($type === 'publications') {

            $shortCodesCounter++;

            wp_enqueue_style('cnrs-data-manager-styling', get_site_url() . '/wp-includes/cnrs-data-manager/assets/cnrs-data-manager-style.css', [], null);
            wp_enqueue_style('cnrs-data-manager-styling-filters', get_site_url() . '/wp-includes/cnrs-data-manager/assets/cnrs-data-manager-filters-style.css', [], null);
            wp_enqueue_script('cnrs-data-manager-script', get_site_url() . '/wp-includes/cnrs-data-manager/assets/cnrs-data-manager-script.js', [], null);

            $oskar = Manager::getPublications();
            $formatted = cnrsFormatPublications($oskar);
            $publications = cnrsApplyFilters($formatted['publications'], $_GET);
            $filters = $formatted['filters'];
            $totalCount = count($formatted['publications']);
            $filteredCount = count($publications);

            ob_start();
            include_once(dirname(__DIR__) . '/Core/Views/Publications.php');
            return ob_get_clean();

        } else if ($type === 'categories') {

            wp_enqueue_style('cnrs-data-manager-categories-styling', get_site_url() . '/wp-includes/cnrs-data-manager/assets/cnrs-data-manager-categories.css', [], null);

            $cat = get_queried_object();
            $catName = $cat->name;
            $catID = $cat->term_id;
            $slug = $cat->slug;
            $previewTemplate = $catName !== 'project' ? Settings::getDesignFromTermId($catID) : 'POSTER';

            $candidate_email = Settings::getCandidatingEmail();

            $hiddenFiltersByCatId = Settings::getHiddenTermsIds();
            $isFilterHidden = in_array($catID, $hiddenFiltersByCatId, true) === true;
            $candidatingCatId = Settings::getCandidatingTermsIds();
            $isCandidating = in_array($catID, $candidatingCatId, true) === true && $candidate_email !== null;

            ob_start();
            include_once(dirname(__DIR__) . '/Core/Views/Categories.php');
            return ob_get_clean();

        }  else if ($type === 'collabs') {

            $shortCodesCounter++;

            wp_enqueue_style('cnrs-data-manager-styling', get_site_url() . '/wp-includes/cnrs-data-manager/assets/cnrs-data-manager-style.css', [], null);

            $project = get_queried_object();
            $collabs = Collaborators::getCollaboratorsFromProjectId($project->ID);
            $funders = $collabs['funders'];
            $partners = $collabs['partners'];

            $width = 50;
            if (empty($funders) && empty($partners)) {
                return '<span class="cnrs-dm-front-no-collaborators"></span>';
            } else if ((empty($funders) && !empty($partners)) || (!empty($funders) && empty($partners))) {
                $width = 100;
            }

            ob_start();
            include_once(dirname(__DIR__) . '/Core/Views/CollaboratorsFront.php');
            return ob_get_clean();

        }  else if ($type === 'project-slider') {

            $shortCodesCounter++;

            wp_enqueue_style('cnrs-data-manager-owl-styling', get_site_url() . '/wp-content/plugins/cnrs-data-manager/assets/css/owl.carousel.min.css', [], null);
            wp_enqueue_style('cnrs-data-manager-styling', get_site_url() . '/wp-includes/cnrs-data-manager/assets/cnrs-data-manager-style.css', ['cnrs-data-manager-owl-styling'], null);

            wp_enqueue_script('cnrs-data-manager-owl-script', get_site_url() . '/wp-content/plugins/cnrs-data-manager/assets/js/owl.carousel.js', [], null);
            wp_enqueue_script('cnrs-data-manager-carousel-script', get_site_url() . '/wp-content/plugins/cnrs-data-manager/assets/js/cnrs-data-manager-carousel-script.js', ['cnrs-data-manager-owl-script'], null);

            $project = get_queried_object();
            $attachments = Projects::getImagesFromProject($project->ID);

            if (empty($attachments)) {
                return '';
            }

            $images = array_map(function (int $imageIs) {
                return wp_get_attachment_url($imageIs);
            }, $attachments);

            ob_start();
            include_once(dirname(__DIR__) . '/Core/Views/Slider.php');
            return ob_get_clean();
        }

        return '';
    }
}

if (!function_exists('getFormToggles')) {

    function getFormToggles(array $form): array
    {
        $toggles = [];
        $originals = Manager::getOriginalToggle();
        foreach ($originals as $original) {
            $toggles[$original['id']] = [
                'label' => $original['label'],
                'option1' => ['value' => $original['values'][0], 'action' => true],
                'option2' => ['value' => $original['values'][1], 'action' => false]
            ];
        }
        foreach ($form['elements'] as $element) {
            if ($element['type'] === 'toggle') {
                $toggles[$element['data']['value'][0]] = [
                    'label' => $element['label'],
                    'option1' => ['value' => $element['data']['values'][0], 'action' => true],
                    'option2' => ['value' => $element['data']['values'][1], 'action' => false]
                ];
            }
        }
        return $toggles;
    }
}

if (!function_exists('hasObservation')) {

    /**
     * Check if an observation with the provided index exists in the given array of observations.
     *
     * @param array $observations The array of observations to search.
     * @param int $index The index of the observation to look for.
     * @return string|null Returns the observation if found, null otherwise.
     */
    function hasObservation(array $observations, int $index): ?string
    {
        foreach ($observations as $observation) {
            if ($observation['index'] === $index) {
                return $observation['observation'];
            }
        }
        return null;
    }
}

if (!function_exists('extractOptionComment')) {

    function extractOptionComment(int $index, array $options): string
    {
        foreach ($options as $option) {
            if ($option['option'] === $index) {
                return $option['value'];
            }
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
        $paged = get_query_var('cdm-page') ? absint(get_query_var('cdm-page')) : 1;
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

        if (get_query_var('cdm-search')) {
            $args['s'] = get_query_var('cdm-search');
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
            $qvars[] = 'cdm-page';
            $qvars[] = 'cdm-year';
            $qvars[] = 'cdm-search';
            $qvars[] = 'cdm-parent';
            $qvars[] = 'cdm-team';
            return $qvars;
        });
    }
}

if (!function_exists('getTeams')) {

    /**
     * Get teams from XML data.
     *
     * @param bool $onlyNames Optional. Whether to only get team names. Default false.
     * @return array Returns an array of teams. Each team is represented as an associative array with keys 'id' and 'name'.
     */
    function getTeams(bool $onlyNames = false): array
    {
        $pll = function_exists('pll_the_languages');
        $list = ['fr'];
        $flags = null;
        $result = ['fr' => []];

        if ($pll === true) {
            $list = pll_languages_list();
            $flags = cnrs_get_languages_from_pll([], false);
        }

        $xmlTeams = Manager::defineArrayFromXML()['teams'];

        foreach ($list as $lang) {

            $teams = Tools::getTeams($lang);

            foreach ($xmlTeams as $xmlTeam) {

                $id = $xmlTeam['equipe_id'];
                $xml_name = $xmlTeam['nom'];

                $wp_name = (function () use ($teams, $id) {
                    foreach ($teams as $team) {
                        if ((int) $team['xml_entity_id'] === (int) $id) {
                            $post = get_post((int) $team['term_id']);
                            $title = $post->post_title;
                            return strlen($title) > 0 ? $title : null;
                        }
                    }
                    return null;
                })();

                if ($onlyNames === true && $wp_name !== null) {

                    $result[$lang][] = [
                        'id' => $id,
                        'lang' => $lang,
                        'flag' => $flags[$lang],
                        'name' => $wp_name
                    ];

                } else if ($onlyNames === false) {

                    $result[$lang][] = [
                        'id' => $id,
                        'lang' => $lang,
                        'flag' => $flags[$lang],
                        'name' => $xml_name . ($wp_name !== null
                                ? ''
                                : ' (' . __('not assigned', 'cnrs-data-manager') . ')')
                    ];
                }
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
            'orderby' => 'ID',
            'order' => 'DESC',
            'numberposts' => -1,
        ]);
        $relations = Projects::getProjects();
        $results = [];
        foreach ($projects as $project) {
            $res = [
                'id' => $project->ID,
                'url' => $project->guid,
                'name' => $project->post_title,
                'uri' => $project->post_name,
                'excerpt' => $project->post_excerpt,
                'lang' => function_exists('pll_get_post_language')
                    ? pll_get_post_language($project->ID, 'slug')
                    : 'fr',
                'flag' => function_exists('pll_get_post_language')
                    ? cnrs_get_languages_from_pll([], false)[pll_get_post_language($project->ID, 'slug')]
                    : null,
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
                    $lang = $_POST['cnrs-data-manager-project-lang-' . $projectID];
                    $reorders = [];
                    foreach ($orders as $order) {
                        if ($order !== '0') {
                            $reorders[] = $order;
                        }
                    }

                    for ($i = 0; $i < count($teams); $i++) {
                        $inserts[] = [
                            'team_id' => (int) $teams[$i],
                            'project_id' => (int) $projectID,
                            'display_order' => (int) $reorders[$i],
                            'lang' => $lang
                        ];
                    }
                }
            }
            Projects::updateProjectsRelations($inserts);
        }
    }
}

if (!function_exists('sendCNRSEmail')) {

    /**
     * Sends a CNRS email.
     *
     * @param string $to The recipient email address.
     * @param string $subject The subject of the email.
     * @param string $body The body of the email, in HTML format.
     * @param array $cc (optional) An array of email addresses to be cc-ed.
     * @param array $attachments (optional) An array of file attachments.
     * @return bool Returns true if the email was sent successfully, false otherwise.
     */
    function sendCNRSEmail(
        string $to,
        string $subject,
        string $body,
        array $cc = [],
        array $attachments = []
    ): bool {
        $debugEmail = Settings::getDebugMode();
        $headers = ['Content-Type: text/html; charset=UTF-8'];
        foreach ($cc as $email) {
            $headers[] = 'Cc: ' . $debugEmail === null ? $email : $debugEmail;
        }
        $sender = $debugEmail === null ? $to : $debugEmail;
        return wp_mail($sender, $subject, $body, $headers, $attachments);
    }
}

if (!function_exists('cnrs_dm_connexion')) {

    /**
     * Establishes a connection and retrieves the user object based on the given cookie value.
     *
     * @return object|null Returns the user object if a connection is established successfully, otherwise returns null.
     */
    function cnrs_dm_connexion(): object|null
    {
        $cookie = null;
        if (defined('CNRS_DATA_MANAGER_FIRST_CONN')) {
            $cookie = CNRS_DATA_MANAGER_FIRST_CONN;
        } else if ($_COOKIE['wp-cnrs-dm'] !== null) {
            $cookie = $_COOKIE['wp-cnrs-dm'];
        }
        return Forms::getUserByUuid($cookie);
    }
}

if (!function_exists('is_connexion_error')) {

    /**
     * Check if there is a connection error in the provided XML data.
     *
     * @param array $xml The XML data to check.
     * @return string|null Returns an error message if there is a connection error, null otherwise.
     */
    function is_connexion_error(array $xml = []): string|null
    {
        if (isset($_POST['cnrs-dm-front-mission-form-login-action'])
            && $_POST['cnrs-dm-front-mission-form-login-action'] === 'login'
            && isset($_POST['cnrs-dm-front-mission-form-login-email'])
            && isset($_POST['cnrs-dm-front-mission-form-login-pwd'])
            && (!filter_var($_POST['cnrs-dm-front-mission-form-login-email'], FILTER_VALIDATE_EMAIL)
                || Forms::validatedUser($_POST['cnrs-dm-front-mission-form-login-email'], $_POST['cnrs-dm-front-mission-form-login-pwd']) === null)
        ) {
            return __('Your identifiers are not recognized.', 'cnrs-data-manager');
        }

        if (!empty($xml)
            && isset($_POST['cnrs-dm-front-mission-form-login-action'])
            && $_POST['cnrs-dm-front-mission-form-login-action'] === 'reset'
            && isset($_POST['cnrs-dm-front-mission-form-reset-email'])
        ) {
            if (filter_var($_POST['cnrs-dm-front-mission-form-reset-email'], FILTER_VALIDATE_EMAIL)
                && Emails::checkEmailValidity($_POST['cnrs-dm-front-mission-form-reset-email'], $xml)
            ) {
                $sent = Emails::sendResetEmail($_POST['cnrs-dm-front-mission-form-reset-email']);
                if ($sent === false) {
                    $error = __('An error has occurred while sending the email.', 'cnrs-data-manager');
                }
            } else {
                $error = __('The email is not valid.', 'cnrs-data-manager');
            }
        }

        return null;
    }
}

if (!function_exists('setUserConnexion')) {

    /**
     * Set the user's connection if the provided login action is "login" and valid email and password are provided.
     *
     * @return void
     */
    function setUserConnexion(): void
    {
        if (isset($_POST['cnrs-dm-front-mission-form-login-action'])
            && $_POST['cnrs-dm-front-mission-form-login-action'] === 'login'
            && isset($_POST['cnrs-dm-front-mission-form-login-email'])
            && isset($_POST['cnrs-dm-front-mission-form-login-pwd'])
        ) {
            $uuid = Forms::validatedUser($_POST['cnrs-dm-front-mission-form-login-email'], $_POST['cnrs-dm-front-mission-form-login-pwd']);
            if (filter_var($_POST['cnrs-dm-front-mission-form-login-email'], FILTER_VALIDATE_EMAIL) && $uuid !== null) {
                if (!defined('CNRS_DATA_MANAGER_FIRST_CONN')) {
                    define('CNRS_DATA_MANAGER_FIRST_CONN', $uuid);
                }
                setcookie('wp-cnrs-dm', $uuid, time() + 86400);
            }
        }
        if (isset($_POST['cnrs-dm-front-mission-form-original']) && strlen($_POST['cnrs-dm-front-mission-form-original']) > 0) {
            setcookie('wp-cnrs-dm', '', time() - 3600);
        }
    }
}

if (!function_exists('setMissionFormURL')) {

    function setMissionFormURL()
    {

        $controller = new Controller(new TemplateLoader);
        add_action( 'init', array( $controller, 'init' ) );
        add_filter( 'do_parse_request', array( $controller, 'dispatch' ), PHP_INT_MAX, 2 );
        add_action( 'loop_end', function( \WP_Query $query ) {
            if (isset( $query->virtual_page ) && !empty($query->virtual_page)) {
                $query->virtual_page = NULL;
            }
        } );
        add_filter('the_permalink', function($plink) {
            global $post, $wp_query;
            if (
                $wp_query->is_page && isset($wp_query->virtual_page)
                && $wp_query->virtual_page instanceof Page
                && isset($post->is_virtual) && $post->is_virtual
            ) {
                $plink = home_url($wp_query->virtual_page->getUrl());
            }
            return $plink;
        });

        add_action('cnrs_dm_virtual_pages', function($controller) {

            $pages = [
                [
                    'uri' => 'cnrs-umr/mission-form',
                    'title' => __('Mission form', 'cnrs-data-manager'),
                    'template' => 'mission-form'
                ],
                [
                    'uri' => 'cnrs-umr/mission-form-print',
                    'title' => __('Mission form print', 'cnrs-data-manager'),
                    'template' => 'mission-form-download'
                ],
                [
                    'uri' => 'cnrs-umr/mission-form-download',
                    'title' => __('Mission form download', 'cnrs-data-manager'),
                    'template' => 'mission-form-download'
                ],
                [
                    'uri' => 'cnrs-umr/mission-form-revision/manager',
                    'title' => __('Mission form revision by manager', 'cnrs-data-manager'),
                    'template' => 'mission-form'
                ],
                [
                    'uri' => 'cnrs-umr/mission-form-revision/agent',
                    'title' => __('Mission form revision by agent', 'cnrs-data-manager'),
                    'template' => 'mission-form'
                ],
                [
                    'uri' => 'cnrs-umr/mission-form-revision/funder',
                    'title' => __('Mission form revision by funder', 'cnrs-data-manager'),
                    'template' => 'mission-form'
                ]
            ];

            foreach ($pages as $page) {
                $controller->addPage(new \CnrsDataManager\Core\Controllers\Page('/' . $page['uri']))
                    ->setTitle($page['title'])
                    ->setTemplate($page['template'] . '.php');
            }
        });
    }
}

if (!function_exists('isActiveTab')) {

    /**
     * Check if the provided tab is the active tab.
     *
     * @param string|null $tab The tab to check. Defaults is null.
     * @return bool Returns true if the provided tab is the active tab, false otherwise.
     */
    function isActiveTab(string|null $tab = null): bool
    {
        return $_GET['tab'] === $tab;
    }
}

if (!function_exists('setAvatarForPDF')) {

    /**
     * Set the avatar image for a PDF document.
     *
     * @param string|null $avatar The avatar image to set. If null, the default avatar image will be used.
     * @return string The avatar image in the format 'data:image/png;base64,base64_encoded_data'.
     */
    function setAvatarForPDF(string $avatar = null): string
    {
        if (str_starts_with($avatar, 'data:image/')) {
            return $avatar;
        }

        $defaultAvatarPath = CNRS_DATA_MANAGER_PATH . '/assets/media/default_avatar.png';
        $data = file_get_contents($defaultAvatarPath);
        return 'data:image/png;base64,' . base64_encode($data);
    }
}

if (!function_exists('formatDateForPDF')) {

    /**
     * Format a date for PDF generation.
     *
     * @param string $date The input date to format.
     * @param string $type The type of formatting to apply. Defaults to 'full'.
     * @return string The formatted date.
     */
    function formatDateForPDF(string $date, string $type = 'datetime'): string
    {

        if (!in_array($type, ['time', 'datetime', 'date'], true)) {
            return $date;
        }

        if (strlen($date) < 1) {
            return '';
        }

        return match ($type) {
            'time' => date("H\hi", strtotime($date)),
            'date' => date("d/m/Y", strtotime($date)),
            default => sprintf(date("d/m/Y \%\s H\hi", strtotime($date)), __('at', 'cnrs-data-manager'))
        };
    }
}

if (!function_exists('isCheckedPDF')) {

    /**
     * Check if a choice is in the array of choices.
     *
     * @param string $choice The choice to check.
     * @param array $choices The array of choices to search in.
     * @return bool Returns true if the choice is found in the array, false otherwise.
     */
    function isCheckedPDF(string $choice, array $choices): bool
    {
        if (in_array($choice, $choices, true)) {
            return true;
        }
        return false;
    }
}

if (!function_exists('hasChoiceComment')) {

    /**
     * Check if the given index has a corresponding comment in the options array.
     *
     * @param int $index The index to check.
     * @param array $options The options array to search in.
     * @return string|null Returns the corresponding comment if found, null otherwise.
     */
    function hasChoiceComment(int $index, array $options): ?string
    {
        foreach ($options as $option) {
            if ($option['option'] === $index && strlen($option['value']) > 0) {
                return $option['value'];
            }
        }
        return null;
    }
}

if (!function_exists('getDoc')) {

    /**
     * Get the documentation pages.
     *
     * @return array Returns an array of documentation pages.
     */
    function getDoc(): array
    {
        $docLanguage = str_starts_with(get_locale(), 'fr_') ? 'FR' : 'EN';
        $path = CNRS_DATA_MANAGER_PATH . '/documentation/' . $docLanguage;
        $doc = scandir($path);
        $pages = [];
        $url = get_site_url() . '/wp-content/plugins/cnrs-data-manager/documentation/attachments';
        foreach ($doc as $page) {
            if (str_ends_with($page, '.md')) {
                $content = file_get_contents($path . '/' . $page);
                $content = str_replace(
                    ['](/documentation/attachments', '?raw=true', '](/documentation/' . $docLanguage . '/'],
                    ['](' . $url, '', '](#'],
                    $content
                );
                $parseDown = new ParseDown();
                $t = str_replace('.md', '', $page);
                $t = explode(' - ', $t)[1];
                $html = str_replace(
                    ['%20', '.md'],
                    [' ', ''],
                    $parseDown->text($content)
                );
                $indexes = [];
                for ($i = 0; $i < 100; $i++) {
                    $indexes[] = substr('0' . $i, -2) . ' - ';
                }
                $html = str_replace($indexes, '', $html);
                $html = preg_replace_callback('/#(.*?)\"/s', function($matches) {
                    return '#' . sanitize_title($matches[1]) . '"';
                }, $html);
                $html = preg_replace_callback([
                    '/<a href="#sommaire(.*?)\/a>/s',
                    '/<a href="#summary(.*?)\/a>/s'
                ], function() {
                    return '';
                }, $html);
                $pages[$t] = $html;
            }
        }
        return $pages;
    }
}

if (!function_exists('getFormReferenceDate')) {

    /**
     * Retrieve the reference date from a given JSON string.
     *
     * @param string $json The JSON string containing the form data.
     * @return string Returns the reference date if found in the form data, or the current date if not found.
     */
    function getFormReferenceDate(string $json): string
    {
        $form = json_decode($json, true);
        foreach ($form['elements'] as $element) {
            if ($element['data']['isReference'] === true) {
                return $element['data']['value'][0];
            }
        }

        return date("Y-m-d");
    }
}

if (!function_exists('stripArrayValuesSlashes')) {

    /**
     * Strip slashes from each value in the provided array.
     *
     * @param array|null $array The array to strip slashes from.
     * @return array Returns a new array with the slashes stripped from each value.
     */
    function stripArrayValuesSlashes(?array $array): array
    {
        $array = is_null($array) ? [] : $array;
        $striped = [];
        foreach ($array as $item) {
            $striped[] = html_entity_decode(stripslashes($item));
        }
        return $striped;
    }
}

if (!function_exists('highlightText')) {

    /**
     * Highlight and colorize the provided text based on the file extension.
     *
     * @param string $text The text to be highlighted.
     * @param string $fileExt The file extension to determine the highlighting settings.
     * @return string The highlighted and colorized text.
     */
    function highlightText(string $text, string $fileExt=""): string
    {
        if ($fileExt == "php")
        {
            ini_set("highlight.comment", "#4e574e");
            ini_set("highlight.default", "#5b82bd");
            ini_set("highlight.html", "#b35454");
            ini_set("highlight.keyword", "#b87fe3; font-weight: bold");
            ini_set("highlight.string", "#e6ce57");
        }
        else if ($fileExt == "html")
        {
            ini_set("highlight.comment", "4e574e");
            ini_set("highlight.default", "#f1f1f1");
            ini_set("highlight.html", "#b35454");
            ini_set("highlight.keyword", "orange; font-weight: bold");
            ini_set("highlight.string", "#ffffff");
        }

        $text = trim($text);
        $text = highlight_string("<?php " . $text, true);  // highlight_string() requires opening PHP tag or otherwise it will not colorize the text
        $text = trim($text);
        $text = preg_replace("|^\\<code\\>\\<span style\\=\"color\\: #[a-fA-F0-9]{0,6}\"\\>|", "", $text, 1);  // remove prefix
        $text = preg_replace("|\\</code\\>\$|", "", $text, 1);  // remove suffix 1
        $text = trim($text);  // remove line breaks
        $text = preg_replace("|\\</span\\>\$|", "", $text, 1);  // remove suffix 2
        $text = trim($text);  // remove line breaks
        $text = preg_replace("|^(\\<span style\\=\"color\\: #[a-fA-F0-9]{0,6}\"\\>)(&lt;\\?php&nbsp;)(.*?)(\\</span\\>)|", "\$1\$3\$4", $text);  // remove custom added "<?php "

        return $text;
    }
}

if (!function_exists('cnrs_get_languages_from_pll')) {


    /**
     * Get languages from Polylang and generate HTML select element.
     *
     * @param array $config The configuration options.
     * @param bool $dom Whether to return the generated HTML or the language data.
     * @return string|array Returns the generated HTML if $dom is true, otherwise returns an array of language data.
     */
    function cnrs_get_languages_from_pll(array $config, bool $dom = true): string|array
    {
        if (function_exists('pll_the_languages')) {

            ob_start();
            pll_the_languages(['show_flags' => 1, 'show_names' => 0]);
            $flags = ob_get_clean();

            $rows = explode('</li>', $flags);
            array_pop($rows);

            $langs = array_map(function($row) {
                preg_match('/lang="(.*?)-/s', $row, $match);
                $lang = $match[1];
                preg_match('/<img(.*?)\/>/s', $row, $match);
                $flag = $match[0];
                return ['lang' => $lang, 'flag' => $flag];
            }, $rows);

            if ($dom === false) {
                $res = [];
                foreach ($langs as $lang) {
                    $res[$lang['lang']] = $lang['flag'];
                }
                return $res;
            }

            $html = '';

            foreach ($langs as $lang) {

                $cats = get_categories(['lang' => $lang['lang'], 'hide_empty' => false]);
                ob_start(); ?>
                <div class="cnrs-dm-pll-select-wrapper">
                    <?php echo $lang['flag'] ?>
                    <select required name="<?php echo $config['name'] ?>[<?php echo $lang['lang'] ?>]">
                        <?php foreach ($cats as $cat): ?>
                            <option<?php echo isset($config['selected'][$lang['lang']]) && (int) $config['selected'][$lang['lang']] === (int) $cat->term_id ? ' selected' : '' ?> class="level-0" value="<?php echo $cat->term_id ?>"><?php echo $cat->name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php $html .= ob_get_clean();
            }
            return $html;
        }
        $config['selected'] = $config['selected']['fr'];
        $config['name'] = $config['name'] . '[fr]';
        return wp_dropdown_categories($config);
    }
}

if (!function_exists('cnrs_categories_settings_have_changed')) {

    /**
     * Check if the categories settings have changed between the old and new values.
     *
     * @param array $old The old categories settings array.
     * @param array $new The new categories settings array.
     * @return bool Returns true if the categories settings have changed, false otherwise.
     */
    function cnrs_categories_settings_have_changed(array $old, array $new): bool
    {
        foreach ($old as $lang => $id) {
            if (isset($new[$lang]) && (int) $id !== (int) $new[$lang]) {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('cnrs_is_there_agents')) {

    /**
     * Check if there are agents in the provided entities.
     *
     * @param array $entities The entities to check.
     * @return bool Returns true if there are agents in any of the entities, false otherwise.
     */
    function cnrs_is_there_agents(array $entities): bool
    {
        foreach ($entities as $entity) {
            if (count($entity['agents']) > 0) {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('cnrs_polylang_installed')) {

    /**
     * Check if the Polylang extension is installed.
     *
     * @return void
     */
    function cnrs_polylang_installed(): void
    {
        if (function_exists('pll_the_languages')) {
            ob_start(); ?>
            <div class="cnrs-dm-polylang-installed-wrapper">
                <img src="/wp-content/plugins/cnrs-data-manager/assets/media/polylang-logo.png" alt="polylang-logo">
                <p><?php echo __('The <b>Polylang</b> extension is installed! <b>CNRS Data Manager</b> is compatible with Polylang', 'cnrs-data-manager') ?> <span>😉</span></p>
            </div>
            <?php echo ob_get_clean();
        }
    }
}

if (!function_exists('cnrs_get_translated_categories')) {

    /**
     * Get translated categories with information about each language.
     *
     * @return array Returns an array containing translated categories with information about each language.
     */
    function cnrs_get_translated_categories(): array
    {
        $categories = get_categories([
            'hide_empty' => false,
            'orderby' => 'name',
            'order' => 'ASC'
        ]);
        $array = [];
        if (!function_exists('pll_the_languages')) {
            $array = array_map(function (object $category) {
                return [
                    'fr' => [
                        'term_id' => $category->term_id,
                        'name' => $category->name,
                        'slug' => $category->slug,
                        'flag' => null
                    ]
                ];
            }, $categories);
        } else {
            $originalLang = pll_default_language();
            $flags = cnrs_get_languages_from_pll([], false);
            foreach ($categories as $category) {
                if (pll_get_term_language($category->term_id) === $originalLang) {
                    $cat = [];
                    $cat[$originalLang] = [
                        'term_id' => $category->term_id,
                        'name' => $category->name,
                        'slug' => $category->slug,
                        'flag' => $flags[$originalLang]
                    ];
                    $toTranslate = pll_get_term_translations($category->term_id);
                    unset($toTranslate[$originalLang]);
                    foreach ($toTranslate as $lang => $translationId) {
                        $translatedCat = get_category($translationId);
                        $cat[$lang] = [
                            'term_id' => $translatedCat->term_id,
                            'name' => $translatedCat->name,
                            'slug' => $translatedCat->slug,
                            'flag' => $flags[$lang]
                        ];
                    }
                    $array[] = $cat;
                }
            }
        }
        return $array;
    }
}

if (!function_exists('cnrs_isDesignSelected')) {

    /**
     * Check if a design is selected for a specific term and template.
     *
     * @param int $term_id The ID of the term.
     * @param string $template The name of the template.
     * @param array $designs An array of designs to check against.
     *
     * @return bool True if a design is selected, false otherwise.
     */
    function cnrs_isDesignSelected(int $term_id, string $template, array $designs): bool
    {
        foreach ($designs as $design) {
            if ((int) $design['term_id'] === $term_id && $design['design'] === $template) {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('isCollaboratorSelected')) {

    /**
     * Check if a collaborator is selected by ID in the given array.
     *
     * @param int $id The ID of the collaborator to check.
     * @param array $array An array of collaborators to check against.
     *
     * @return bool True if the collaborator is selected, false otherwise.
     */
    function isCollaboratorSelected(int $id, array $array): bool
    {
        foreach ($array as $item) {
            if ((int) $item['id'] === $id) {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('getCollaboratorsThumbnails')) {

    /**
     * Get the HTML thumbnails for a list of collaborators.
     *
     * @param array $array An array of collaborators.
     *
     * @return string The HTML code for the collaborators thumbnails.
     */
    function getCollaboratorsThumbnails(array $array): string
    {
        $html = '';
        foreach ($array as $item) {
            $html .= "<div class=\"cnrs-dm-collaborators-tags\">
                <span class=\"cnrs-dm-collaborators-tags-delete\" data-collab-id=\"{$item['id']}\">
                    <svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 384 512\" width=\"12\" height=\"12\">
                        <path d=\"M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z\"/>
                    </svg>
                </span>
                <span class=\"cnrs-dm-collaborators-tags-text\">{$item['entity_name']}</span>
            </div>";
        }
        return $html;
    }
}

if (!function_exists('cnrsCreateExcerpt')) {

    /**
     * Create an excerpt from a given content string.
     *
     * @param string $content The content to create an excerpt from.
     * @param int $limit The maximum length of the excerpt (default: 100 characters).
     *
     * @return string The created excerpt.
     */
    function cnrsCreateExcerpt(string $content, int $limit = 100): string
    {
        $content = strip_tags($content);
        $content = strip_shortcodes($content);
        $content = trim(preg_replace('/\s+/', ' ', $content));
        $ret = $content;
        if (mb_strlen($content) >= $limit) {
            $ret = mb_substr($content, 0, $limit);
            if (mb_substr($ret, -1) !== ' ') {
                $space_pos_in_substr = mb_strrpos($ret, ' ');
                $space_pos_in_content = mb_strpos($content, ' ', $limit);
                if ($space_pos_in_content !== false && $space_pos_in_content - $limit <= $limit - $space_pos_in_substr) {
                    $ret = mb_substr($content, 0, $space_pos_in_content);
                } else {
                    $ret = mb_substr($content, 0, $space_pos_in_substr);
                }
            }
        }
        return $ret . '...';
    }
}

if (!function_exists('cnrsFormatPublication')) {

    function cnrsFormatPublications(array $publications): array
    {
        $all = [
            'publications' => []
        ];

        $authors = [];
        $dates = [];
        $types = [];
        $guardianships = [];
        $categories = [];

        foreach ($publications as $publication) {

            $formatted = [
                'url' => null,
                'title' => null,
                'type' => null,
                'authors' => [],
                'licence' => null,
                'reference' => [
                    'journal' => null,
                    'page' => null,
                    'volume' => null,
                    'number' => null,
                    'date' => null
                ],
                'guardianships' => [],
                'category' => null
            ];

            foreach ($publication as $item) {
                if ($item['key'] === 'dc.identifier.uri') {
                    $formatted['url'] = $item['value'];
                } else if ($item['key'] === 'dc.title.en') {
                    $formatted['title'] = $item['value'];
                } else if ($item['key'] === 'dc.type') {
                    $formatted['type'] = $item['value'];
                } else if ($item['key'] === 'dc.contributor.author') {
                    $formatted['authors'][] = str_replace(',', '', $item['value']);
                } else if ($item['key'] === 'dc.rights') {
                    $formatted['licence'] = $item['value'];
                } else if ($item['key'] === 'bordeaux.journal') {
                    $formatted['reference']['journal'] = $item['value'];
                } else if ($item['key'] === 'bordeaux.page') {
                    $formatted['reference']['page'] = $item['value'];
                } else if ($item['key'] === 'bordeaux.volume') {
                    $formatted['reference']['volume'] = $item['value'];
                } else if ($item['key'] === 'bordeaux.issue') {
                    $formatted['reference']['number'] = $item['value'];
                } else if ($item['key'] === 'dc.date') {
                    $formatted['reference']['date'] = $item['value'];
                } else if ($item['key'] === 'dc.date.available' && !isset($formatted['reference']['date'])) {
                    $formatted['reference']['date'] = explode('T', $item['value'])[0];
                } else if ($item['key'] === 'bordeaux.institution') {
                    $formatted['guardianships'][] = $item['value'];
                } else if ($item['key'] === 'dc.subject.hal') {
                    $formatted['category'] = $item['value'];
                } else if ($item['key'] === 'bordeaux.thesis.discipline' && !isset($formatted['category'])) {
                    $formatted['category'] = $item['value'];
                }
            }

            if ($formatted['url'] !== null) {
                $all['publications'][] = $formatted;
            }

            foreach ($all['publications'] as $row) {
                $authors = array_merge($authors, $row['authors']);
                if ($row['reference']['date'] !== null) {
                    $d = date_create($row['reference']['date']);
                    $dates[] = date_format($d,"Y");
                }
                $types[] = $row['type'];
                $guardianships = array_merge($guardianships, $row['guardianships']);
                $categories[] = $row['category'];
            }
        }

        $all['filters'] = [
            'authors' => cnrsArrayUnique(
                array_values(array_unique($authors)),
                $all['publications'],
                'authors'
            ),
            'dates' => cnrsArrayUnique(
                array_values(array_unique($dates)),
                $all['publications'],
                'date'
            ),
            'types' => cnrsArrayUnique(
                array_values(array_unique($types)),
                $all['publications'],
                'type'
            ),
            'guardianships' => cnrsArrayUnique(
                array_values(array_unique($guardianships)),
                $all['publications'],
                'guardianships'
            ),
            'categories' => cnrsArrayUnique(
                array_values(array_unique($categories)),
                $all['publications'],
                'category'
            )
        ];

        return $all;
    }
}

if (!function_exists('cnrsArrayUnique')) {

    function cnrsArrayUnique(array $references, array $publications, string $type): array
    {
        $final = [];
        foreach ($references as $reference) {
            $final[$reference] = 0;
        }
        foreach ($publications as $publication) {
            $search = $type !== 'date' ? $publication[$type] : $publication['reference']['date'];
            if ($type === 'date') {
                $d = date_create($search);
                $search = date_format($d, "Y");
            }
            if (in_array($type, ['authors', 'guardianships'])) {
                foreach ($search as $entity) {
                    $final[$entity]++;
                }
            } else {
                $final[$search]++;
            }
        }

        $result = [];
        if ($type === 'date') {
            krsort($final);
        } else {
            ksort($final, SORT_STRING);
        }

        foreach ($final as $title => $count) {
            $result[] = ['title' => $title, 'count' => $count];
        }
        return $result;
    }
}

if (!function_exists('cnrsBuildPublicationReference')) {

    function cnrsBuildPublicationReference(array $reference): string
    {
        $array = [
            $reference['journal'] . ($reference['volume'] !== null ? '. vol. ' . $reference['volume'] : ''),
            $reference['number'] !== null ? 'n° ' . $reference['number'] : '',
            $reference['page'] !== null ? 'pp. ' . $reference['page'] : '',
            $reference['date'] !== null ? $reference['date'] : '',
        ];
        $toImplode = [];
        foreach ($array as $item) {
            if (strlen($item) > 0) {
                $toImplode[] = $item;
            }
        }
        return implode(', ', $toImplode);
    }
}

if (!function_exists('cnrsApplyFilters')) {

    function cnrsApplyFilters(array $publications, array $query): array
    {
        $byAuthor = isset($query['cdm-author']) && $query['cdm-author'] !== 'all' ? $query['cdm-author'] : null;
        $byYear = isset($query['cdm-date']) && $query['cdm-date'] !== 'all' ? $query['cdm-date'] : null;
        $byType = isset($query['cdm-type']) && $query['cdm-type'] !== 'all' ? $query['cdm-type'] : null;
        $byGuardianship = isset($query['cdm-guardianship']) && $query['cdm-guardianship'] !== 'all' ? $query['cdm-guardianship'] : null;
        $byDiscipline = isset($query['cdm-category']) && $query['cdm-category'] !== 'all' ? $query['cdm-category'] : null;
        $search = isset($query['cdm-search']) && strlen($query['cdm-search']) > 0 ? $query['cdm-search'] : null;

        foreach ($publications as $key => $publication) {
            if ($byAuthor !== null && !in_array($byAuthor, $publication['authors'])) {
                unset($publications[$key]);
            } else if ($byYear !== null && !str_starts_with($publication['reference']['date'], $byYear)) {
                unset($publications[$key]);
            } else if ($byType !== null && $byType !== $publication['type']) {
                unset($publications[$key]);
            } else if ($byGuardianship !== null && !in_array($byGuardianship, $publication['guardianships'])) {
                unset($publications[$key]);
            } else if ($byDiscipline !== null && $byDiscipline !== ($publication['category'] !== null ? $publication['category'] : 'unclassified')) {
                unset($publications[$key]);
            } else if ($search !== null && !str_contains(strtolower($publication['title']), strtolower($search))) {
                unset($publications[$key]);
            }
        }

        return $publications;
    }
}
