<?php

/**
 * Singlethon class that manages the various controllers of the administration of wordpress
 *
 * @package   Duplicator
 * @copyright (c) 2021, Snapcreek LLC
 */

namespace CnrsDataManager\Core\Controllers;

use Error;
use Exception;
use JsonException;

final class Manager
{
    const MAIN_MENU_SLUG         = 'data-manager';
    const TOOLS_SUBMENU_SLUG     = 'tools';
    const SETTINGS_SUBMENU_SLUG  = 'settings';

    const QUERY_STRING_MENU_KEY_L1     = 'page';
    const QUERY_STRING_MENU_KEY_L2     = 'tab';
    const QUERY_STRING_MENU_KEY_L3     = 'subtab';
    const QUERY_STRING_MENU_KEY_ACTION = 'action';

    private static string $xmlPath = '';

    /**
     * Return current menu levels
     *
     * @return string[]
     */
    public static function getMenuLevels()
    {
        $result  = array();
        $exChars = '-_';
        $result[self::QUERY_STRING_MENU_KEY_L1] = self::sanitizeStrictInput(
            10000,
            self::QUERY_STRING_MENU_KEY_L1,
            null,
            $exChars
        );
        $result[self::QUERY_STRING_MENU_KEY_L2] = self::sanitizeStrictInput(
            10000,
            self::QUERY_STRING_MENU_KEY_L2,
            null,
            $exChars
        );
        $result[self::QUERY_STRING_MENU_KEY_L3] = self::sanitizeStrictInput(
            10000,
            self::QUERY_STRING_MENU_KEY_L3,
            null,
            $exChars
        );
        return $result;
    }

    /**
     * Return true if current page is a cnrs-data-manager page
     *
     * @return boolean
     */
    public static function isDataManagerPage()
    {
        if (!is_admin()) {
            return false;
        }

        return match (self::sanitizeStrictInput(10000, 'page', '', '-_ ')) {
            self::MAIN_MENU_SLUG, self::TOOLS_SUBMENU_SLUG, self::SETTINGS_SUBMENU_SLUG => true,
            default => false,
        };
    }

    /**
     * Return true if XML file exists
     *
     * @return bool
     */
    private static function isXMLExists(): bool
    {
        $fileName = 'umr_5805';
        self::$xmlPath = ABSPATH . 'XML/' . $fileName . '.xml';
        return file_exists(self::$xmlPath);
    }

    /**
     * Return array from xml file
     *
     * @return array
     */
    private static function parseXML(): array
    {
        $final = ['teams' => [], 'services' => [], 'platforms' => [], 'agents' => []];
        try {
            $xmlFile = file_get_contents(self::$xmlPath);
            $new = simplexml_load_string($xmlFile);
            $con = json_encode($new, JSON_THROW_ON_ERROR);
            $array = json_decode($con, true, 512, JSON_THROW_ON_ERROR);
            $teams = $array['equipes'];
            $services = $array['services'];
            $platforms = $array['plateformes'];
            $agents = $array['agents'];
            if (isset($teams['equipe']['equipe_id'])) {
                $teams['equipe']['equipe_id'] = (int) $teams['equipe']['equipe_id'];
                $final['teams'][] = $teams['equipe'];
            } else {
                foreach ($teams['equipe'] as $team) {
                    $team['equipe_id'] = (int) $team['equipe_id'];
                    $final['teams'][] = $team;
                }
            }
            if (isset($services['service']['service_id'])) {
                $services['service']['service_id'] = (int) $services['service']['service_id'];
                $final['services'][] = $services['service'];
            } else {
                foreach ($services['service'] as $service) {
                    $service['service_id'] = (int) $service['service_id'];
                    $final['services'][] = $service;
                }
            }
            if (isset($platforms['plateforme']['plateforme_id'])) {
                $platforms['plateforme']['plateforme_id'] = (int) $platforms['plateforme']['plateforme_id'];
                $final['platforms'][] = $platforms['plateforme'];
            } else {
                foreach ($platforms['plateforme'] as $platform) {
                    $platform['plateforme_id'] = (int) $platform['plateforme_id'];
                    $final['platforms'][] = $platform;
                }
            }
            if (isset($agents['agent']['agent_id'])) {
                $agents['agent']['agent_id'] = (int) $agents['agent']['agent_id'];
                $agents['agent']['autorise_pub_photo'] = (int) $agents['agent']['autorise_pub_photo'] === 1;
                $agents['agent']['photo'] = is_array($agents['agent']['photo']) && empty($agents['agent']['photo']) || $agents['agent']['autorise_pub_photo'] === false ? null : $agents['agent']['photo'];
                $agents['agent']['tutelle'] = is_array($agents['agent']['tutelle']) && empty($agents['agent']['tutelle']) ? null : $agents['agent']['tutelle'];
                $agents['agent']['civilite'] = is_array($agents['agent']['civilite']) && empty($agents['agent']['civilite']) ? null : $agents['agent']['civilite'];
                $agentTeams = [];
                foreach ($agents['agent']['equipes'] as $agentTeam) {
                    if (isset($agentTeam['equipe_id'])) {
                        $agentTeam['equipe_id'] = (int)$agentTeam['equipe_id'];
                        $agentTeams[] = $agentTeam;
                    } else {
                        foreach ($agentTeam as $t) {
                            $t['equipe_id'] = (int)$t['equipe_id'];
                            $agentTeams[] = $t;
                        }
                    }
                }
                $agentServices = [];
                foreach ($agents['agent']['services'] as $agentService) {
                    if (isset($agentService['service_id'])) {
                        $agentService['service_id'] = (int)$agentService['service_id'];
                        $agentServices[] = $agentService;
                    } else {
                        foreach ($agentService as $s) {
                            $s['service_id'] = (int)$s['service_id'];
                            $agentServices[] = $s;
                        }
                    }
                }
                $agentPlatforms = [];
                foreach ($agents['agent']['plateformes'] as $agentPlatform) {
                    if (isset($agentPlatform['plateforme_id'])) {
                        $agentPlatform['plateforme_id'] = (int)$agentPlatform['plateforme_id'];
                        $agentPlatforms[] = $agentPlatform;
                    } else {
                        foreach ($agentPlatform as $p) {
                            $p['plateforme_id'] = (int)$p['plateforme_id'];
                            $agentPlatforms[] = $p;
                        }
                    }
                }
                $agents['agent']['equipes'] = $agentTeams;
                $agents['agent']['services'] = $agentServices;
                $agents['agent']['plateformes'] = $agentPlatforms;
                usort($agents['agent']['equipes'], function ($a, $b) { return strnatcmp($a['nom'], $b['nom']);});
                usort($agents['agent']['services'], function ($a, $b) { return strnatcmp($a['nom'], $b['nom']);});
                usort($agents['agent']['plateformes'], function ($a, $b) { return strnatcmp($a['nom'], $b['nom']);});
                $final['agents'][] = $agents['agent'];
            } else {
                foreach ($agents['agent'] as $agent) {
                    $agent['agent_id'] = (int) $agent['agent_id'];
                    $agent['autorise_pub_photo'] = (int) $agent['autorise_pub_photo'] === 1;
                    $agent['photo'] = is_array($agent['photo']) && empty($agent['photo']) || $agent['autorise_pub_photo'] === false ? null : $agent['photo'];
                    $agent['tutelle'] = is_array($agent['tutelle']) && empty($agent['tutelle']) ? null : $agent['tutelle'];
                    $agent['civilite'] = is_array($agent['civilite']) && empty($agent['civilite']) ? null : $agent['civilite'];
                    $agentTeams = [];
                    foreach ($agent['equipes'] as $agentTeam) {
                        if (isset($agentTeam['equipe_id'])) {
                            $agentTeam['equipe_id'] = (int)$agentTeam['equipe_id'];
                            $agentTeams[] = $agentTeam;
                        } else {
                            foreach ($agentTeam as $t) {
                                $t['equipe_id'] = (int)$t['equipe_id'];
                                $agentTeams[] = $t;
                            }
                        }
                    }
                    $agentServices = [];
                    foreach ($agent['services'] as $agentService) {
                        if (isset($agentService['service_id'])) {
                            $agentService['service_id'] = (int)$agentService['service_id'];
                            $agentServices[] = $agentService;
                        } else {
                            foreach ($agentService as $s) {
                                $s['service_id'] = (int)$s['service_id'];
                                $agentServices[] = $s;
                            }
                        }
                    }
                    $agentPlatforms = [];
                    foreach ($agent['plateformes'] as $agentPlatform) {
                        if (isset($agentPlatform['plateforme_id'])) {
                            $agentPlatform['plateforme_id'] = (int)$agentPlatform['plateforme_id'];
                            $agentPlatforms[] = $agentPlatform;
                        } else {
                            foreach ($agentPlatform as $p) {
                                $p['plateforme_id'] = (int)$p['plateforme_id'];
                                $agentPlatforms[] = $p;
                            }
                        }
                    }
                    $agent['equipes'] = $agentTeams;
                    $agent['services'] = $agentServices;
                    $agent['plateformes'] = $agentPlatforms;
                    usort($agent['equipes'], function ($a, $b) { return strnatcmp($a['nom'], $b['nom']);});
                    usort($agent['services'], function ($a, $b) { return strnatcmp($a['nom'], $b['nom']);});
                    usort($agent['plateformes'], function ($a, $b) { return strnatcmp($a['nom'], $b['nom']);});
                    $final['agents'][] = $agent;
                }
            }

            usort($final['agents'], function ($a, $b) { return strnatcmp($a['nom'], $b['nom']);});
            usort($final['teams'], function ($a, $b) { return strnatcmp($a['nom'], $b['nom']);});
            usort($final['services'], function ($a, $b) { return strnatcmp($a['nom'], $b['nom']);});
            usort($final['platforms'], function ($a, $b) { return strnatcmp($a['nom'], $b['nom']);});
            return $final;
        } catch (Exception|Error|JsonException $e) {
            return $final;
        }
    }

    /**
     * Return array of data from epoc.xml file if exists, or empty array if not
     *
     * @return array
     */
    public static function defineArrayFromXML(): array
    {
        if (self::isXMLExists()) {
            return self::parseXML();
        }
        return ['teams' => [], 'services' => [], 'platforms' => [], 'agents' => []];
    }

    /**
     * Return current action key or false if not exists
     *
     * @return string|bool
     */
    public static function getAction()
    {
        return self::sanitizeStrictInput(
            10000,
            self::QUERY_STRING_MENU_KEY_ACTION,
            false,
            '-_'
        );
    }

    /**
     * Check current page
     *
     * @param string      $page  page key
     * @param null|string $tabL1 tab level 1 key, null not check
     * @param null|string $tabL2 tab level 12key, null not check
     *
     * @return boolean
     */
    public static function isCurrentPage($page, $tabL1 = null, $tabL2 = null)
    {
        $levels = self::getMenuLevels();

        if ($page !== $levels[self::QUERY_STRING_MENU_KEY_L1]) {
            return false;
        }

        if (!is_null($tabL1) && $tabL1 !== $levels[self::QUERY_STRING_MENU_KEY_L2]) {
            return false;
        }

        if (!is_null($tabL1) && !is_null($tabL2) && $tabL2 !== $levels[self::QUERY_STRING_MENU_KEY_L3]) {
            return false;
        }

        return true;
    }

    /**
     * Return current menu page URL
     *
     * @param array $extraData extra value in query string key=val
     *
     * @return string
     */
    public static function getCurrentLink($extraData = array())
    {
        $levels = self::getMenuLevels();
        return self::getMenuLink(
            $levels[self::QUERY_STRING_MENU_KEY_L1],
            $levels[self::QUERY_STRING_MENU_KEY_L2],
            $levels[self::QUERY_STRING_MENU_KEY_L3],
            $extraData
        );
    }

    /**
     * Return menu page URL
     *
     * @param string $page      page slug
     * @param string|null $subL2     tab level 1 slug, null not set
     * @param string|null $subL3     tab level 2 slug, null not set
     * @param array $extraData extra value in query string key=val
     * @param bool $relative  if true return relative path or absolute
     *
     * @return string
     */
    public static function getMenuLink(string $page, string|null $subL2 = null, string|null $subL3 = null, array $extraData = [], bool $relative = true): string
    {
        $data = $extraData;

        $data[self::QUERY_STRING_MENU_KEY_L1] = $page;

        if (!empty($subL2)) {
            $data[self::QUERY_STRING_MENU_KEY_L2] = $subL2;
        }

        if (!empty($subL3)) {
            $data[self::QUERY_STRING_MENU_KEY_L3] = $subL3;
        }

        if ($relative) {
            $url = self_admin_url('admin.php', 'relative');
        } else {
            if (is_multisite()) {
                $url = network_admin_url('admin.php');
            } else {
                $url = admin_url('admin.php');
            }
        }
        return $url . '?' . http_build_query($data);
    }

    /**
     * All characters that are not explicitly accepted are removed.
     * By default, only alphanumeric characters are accepted.
     *
     * @param mixed  $input            input value
     * @param string $extraAcceptChars extra accepted chars
     *
     * @return string|string[]
     */
    protected static function sanitizeStrict(mixed $input, string $extraAcceptChars = ''): array|string
    {
        $regex = '/[^a-zA-Z0-9' . preg_quote($extraAcceptChars, '/') . ' ]/m';
        if (is_scalar($input) || is_null($input)) {
            $input = (string) $input;
        } elseif (is_object($input)) {
            $input = (array) $input;
        } else {
            $input = '';
        }

        if (is_array($input)) {
            foreach ($input as $key => $val) {
                $input[$key] = self::sanitizeStrict($val, $extraAcceptChars);
            }
            return $input;
        }

        $result = preg_replace($regex, '', $input);
        return (is_null($result) ? '' : $result);
    }

    /**
     * Return value input by type null if don't exists
     *
     * @param int $type    One of INPUT_GET, INPUT_POST, INPUT_COOKIE, INPUT_SERVER, INPUT_ENV or SnapUtil::INPUT_REQUEST
     * @param string $varName Name of a variable to get.
     *
     * @return string|string[]|null
     */
    protected static function getValueByType(int $type, string $varName): array|string|null
    {
        $doNothingCallback = function ($v) {
            return $v;
        };

        if ($type === 10000) {
            $type = ((isset($_GET[$varName]) && !isset($_POST[$varName])) ? INPUT_GET : INPUT_POST);
        }

        $value = filter_input($type, $varName, FILTER_CALLBACK, array('options' => $doNothingCallback));

        /** @var string|string[]|null $value */
        return $value;
    }

    /**
     * Sanitize value from input $_GET, $_POST, $_REQUEST ...
     *
     * @param int $type             One of INPUT_GET, INPUT_POST, INPUT_COOKIE, INPUT_SERVER, INPUT_ENV or SnapUtil::INPUT_REQUEST
     * @param string $varName          Name of a variable to get.
     * @param false|null $default          default value if var $varName don't exists
     * @param string $extraAcceptChars extra accepted chars
     *
     * @return string|string[]|mixed return default value if varName isn't defined
     */
    private static function sanitizeStrictInput(int $type, string $varName, mixed $default = false, string $extraAcceptChars = ''): mixed
    {
        if (($value = self::getValueByType($type, $varName)) === null) {
            return $default;
        }

        return self::sanitizeStrict($value, $extraAcceptChars);
    }
}
