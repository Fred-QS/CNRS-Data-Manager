<?php

namespace CnrsDataManager\Core\Controllers;

use CnrsDataManager\Core\Models\Settings;
use CnrsDataManager\Core\Models\Forms;
use Error;
use Exception;
use JsonException;

final class Manager
{
    const MAIN_MENU_SLUG         = 'data-manager';
    const TOOLS_SUBMENU_SLUG     = 'tools';
    const SETTINGS_SUBMENU_SLUG  = 'settings';
    const MAP_SUBMENU_SLUG  = '3D-map';

    const QUERY_STRING_MENU_KEY_L1     = 'page';
    const QUERY_STRING_MENU_KEY_L2     = 'tab';
    const QUERY_STRING_MENU_KEY_L3     = 'subtab';
    const QUERY_STRING_MENU_KEY_ACTION = 'action';
    const FEES_UUID = '013589e2-9014-4d5a-adc5-6e4b1e63eb89';

    private static string $xmlPath = '';

    /**
     * Get the original toggle data
     *
     * @return array An associative array containing the toggle data
     */
    public static function getOriginalToggle(): array
    {
        return [[
            'id' => self::FEES_UUID,
            'label' => __('Fees', 'cnrs-data-manager'),
            'values' => [
                __('With fees', 'cnrs-data-manager'),
                __('No charge', 'cnrs-data-manager')
            ]
        ]];
    }

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
            self::MAIN_MENU_SLUG, self::TOOLS_SUBMENU_SLUG, self::SETTINGS_SUBMENU_SLUG => true, self::MAP_SUBMENU_SLUG => true,
            default => false,
        };
    }

    /**
     * Parse XML file and return an array of teams, services, platforms and agents
     *
     * @param string $xmlFile The XML file to parse
     * @return array The parsed data as an array
     */
    private static function parseXML(string $xmlFile): array
    {
        $final = ['teams' => [], 'services' => [], 'platforms' => [], 'agents' => []];
        try {
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
                    $team['activite'] = is_array($team['activite']) && empty($team['activite']) ? null : $team['activite'];
                    $final['teams'][] = $team;
                }
            }
            if (isset($services['service']['service_id'])) {
                $services['service']['service_id'] = (int) $services['service']['service_id'];
                $final['services'][] = $services['service'];
            } else {
                foreach ($services['service'] as $service) {
                    $service['service_id'] = (int) $service['service_id'];
                    $service['activite'] = is_array($service['activite']) && empty($service['activite']) ? null : $service['activite'];
                    $final['services'][] = $service;
                }
            }
            if (isset($platforms['plateforme']['plateforme_id'])) {
                $platforms['plateforme']['plateforme_id'] = (int) $platforms['plateforme']['plateforme_id'];
                $final['platforms'][] = $platforms['plateforme'];
            } else {
                foreach ($platforms['plateforme'] as $platform) {
                    $platform['plateforme_id'] = (int) $platform['plateforme_id'];
                    $platform['activite'] = is_array($platform['activite']) && empty($platform['activite']) ? null : $platform['activite'];
                    $final['platforms'][] = $platform;
                }
            }
            if (isset($agents['agent']['agent_id'])) {
                $agents['agent']['agent_id'] = (int) $agents['agent']['agent_id'];
                $agents['agent']['autorise_pub_photo'] = (int) $agents['agent']['autorise_pub_photo'] === 1;
                $agents['agent']['photo'] = is_array($agents['agent']['photo']) && empty($agents['agent']['photo']) || $agents['agent']['autorise_pub_photo'] === false
                    ? '/wp-content/plugins/cnrs-data-manager/assets/media/default_avatar.png'
                    : $agents['agent']['photo'];
                $agents['agent']['tutelle'] = is_array($agents['agent']['tutelle']) && empty($agents['agent']['tutelle']) ? null : $agents['agent']['tutelle'];
                $agents['agent']['civilite'] = is_array($agents['agent']['civilite']) && empty($agents['agent']['civilite']) ? null : $agents['agent']['civilite'];
                $agents['agent']['responsabilite'] = is_array($agents['agent']['responsabilite']) && empty($agents['agent']['responsabilite']) ? null : $agents['agent']['responsabilite'];
                $agents['agent']['expertise'] = is_array($agents['agent']['expertise']) && empty($agents['agent']['expertise']) ? null : $agents['agent']['expertise'];
                $agents['agent']['activites'] = is_array($agents['agent']['activites']) && empty($agents['agent']['activites']) ? null : $agents['agent']['activites'];
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
                $agentsLinks = [];
                foreach ($agents['agent']['liens_externes'] as $agentsLink) {
                    if (isset($agentsLink['nom'])) {
                        $agentsLinks[] = $agentsLink;
                    } else {
                        foreach ($agentsLink as $l) {
                            $agentsLinks[] = $l;
                        }
                    }
                }
                $agents['agent']['equipes'] = $agentTeams;
                $agents['agent']['services'] = $agentServices;
                $agents['agent']['plateformes'] = $agentPlatforms;
                $agents['agent']['liens_externes'] = $agentsLinks;
                usort($agents['agent']['equipes'], function ($a, $b) { return strnatcmp($a['nom'], $b['nom']);});
                usort($agents['agent']['services'], function ($a, $b) { return strnatcmp($a['nom'], $b['nom']);});
                usort($agents['agent']['plateformes'], function ($a, $b) { return strnatcmp($a['nom'], $b['nom']);});
                usort($agents['agent']['liens_externes'], function ($a, $b) { return strnatcmp($a['nom'], $b['nom']);});
                $final['agents'][] = $agents['agent'];
            } else {
                foreach ($agents['agent'] as $agent) {
                    $agent['agent_id'] = (int) $agent['agent_id'];
                    $agent['autorise_pub_photo'] = (int) $agent['autorise_pub_photo'] === 1;
                    $agent['photo'] = is_array($agent['photo']) && empty($agent['photo']) || $agent['autorise_pub_photo'] === false
                        ? '/wp-content/plugins/cnrs-data-manager/assets/media/default_avatar.png'
                        : $agent['photo'];
                    $agent['tutelle'] = is_array($agent['tutelle']) && empty($agent['tutelle']) ? null : $agent['tutelle'];
                    $agent['civilite'] = is_array($agent['civilite']) && empty($agent['civilite']) ? null : $agent['civilite'];
                    $agent['responsabilite'] = is_array($agent['responsabilite']) && empty($agent['responsabilite']) ? null : $agent['responsabilite'];
                    $agent['expertise'] = is_array($agent['expertise']) && empty($agent['expertise']) ? null : $agent['expertise'];
                    $agent['activites'] = is_array($agent['activites']) && empty($agent['activites']) ? null : $agent['activites'];
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
                    $agentLinks = [];
                    foreach ($agent['liens_externes'] as $agentLink) {
                        if (isset($agentLink['nom'])) {
                            $agentLinks[] = $agentLink;
                        } else {
                            foreach ($agentLink as $l) {
                                $agentLinks[] = $l;
                            }
                        }
                    }
                    $agent['equipes'] = $agentTeams;
                    $agent['services'] = $agentServices;
                    $agent['plateformes'] = $agentPlatforms;
                    $agent['liens_externes'] = $agentLinks;
                    usort($agent['equipes'], function ($a, $b) { return strnatcmp($a['nom'], $b['nom']);});
                    usort($agent['services'], function ($a, $b) { return strnatcmp($a['nom'], $b['nom']);});
                    usort($agent['plateformes'], function ($a, $b) { return strnatcmp($a['nom'], $b['nom']);});
                    usort($agent['liens_externes'], function ($a, $b) { return strnatcmp($a['nom'], $b['nom']);});
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
    public static function defineArrayFromXML(bool $sync = false): array
    {
        $jsonPath = CNRS_DATA_MANAGER_PATH . '/tmp/data.json';
        if (file_exists($jsonPath) && $sync === false) {
            return json_decode(file_get_contents($jsonPath), true);
        }
        Settings::updateFilename();
        global $wpdb;
        $settings = $wpdb->get_row( "SELECT filename FROM {$wpdb->prefix}cnrs_data_manager_settings", ARRAY_A );
        $filename = $settings['filename'];

        if (HttpClient::call($filename, true)) {
            return self::parseXML(HttpClient::call($filename));
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
     * @param string $page  page key
     * @param string|null $tabL1 tab level 1 key, null not check
     * @param string|null $tabL2 tab level 12key, null not check
     *
     * @return boolean
     */
    public static function isCurrentPage(string $page, string|null $tabL1 = null, string|null $tabL2 = null): bool
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
    public static function getCurrentLink(array $extraData = array()): string
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
     * @param bool|null $default          default value if var $varName don't exists
     * @param string $extraAcceptChars extra accepted chars
     *
     * @return string|string[]|mixed return default value if varName isn't defined
     */
    private static function sanitizeStrictInput(int $type, string $varName, bool|null $default = false, string $extraAcceptChars = ''): mixed
    {
        if (($value = self::getValueByType($type, $varName)) === null) {
            return $default;
        }

        return self::sanitizeStrict($value, $extraAcceptChars);
    }

    /**
     * @throws JsonException
     */
    public static function formToolsInit(string $type, ?string $toggleUuid = null): string
    {
        $toolsLabels = [
            'input' => __('New input field', 'cnrs-data-manager'),
            'number' => __('New numeric field', 'cnrs-data-manager'),
            'date' => __('New date field', 'cnrs-data-manager'),
            'time' => __('New time field', 'cnrs-data-manager'),
            'datetime' => __('New date & time field', 'cnrs-data-manager'),
            'checkbox' => __('New multi choice field', 'cnrs-data-manager'),
            'comment' => __('New comment field', 'cnrs-data-manager'),
            'radio' => __('New single choice field', 'cnrs-data-manager'),
            'separator' => __('New separator', 'cnrs-data-manager'),
            'textarea' => __('New text field', 'cnrs-data-manager'),
            'title' => __('New title field', 'cnrs-data-manager'),
            'signs' => __('Signing pads', 'cnrs-data-manager'),
            'toggle' => __('New toggle set', 'cnrs-data-manager'),
        ];
        $toolsConfig = [
            'input' => ['value' => null, 'values' => null, 'choices' => null, 'required' => false, 'isReference' => false, 'toggles' => []],
            'number' => ['value' => null, 'values' => null, 'choices' => null, 'required' => false, 'isReference' => false, 'toggles' => []],
            'date' => ['value' => null, 'values' => null, 'choices' => null, 'required' => false, 'isReference' => false, 'toggles' => []],
            'time' => ['value' => null, 'values' => null, 'choices' => null, 'required' => false, 'isReference' => false, 'toggles' => []],
            'datetime' => ['value' => null, 'values' => null, 'choices' => null, 'required' => false, 'isReference' => false, 'toggles' => []],
            'checkbox' => ['value' => null, 'values' => [], 'choices' => [], 'required' => false, 'isReference' => false, 'toggles' => []],
            'comment' => ['value' => [__('My comment', 'cnrs-data-manager')], 'values' => null, 'choices' => null, 'required' => false, 'isReference' => false, 'toggles' => []],
            'radio' => ['value' => [], 'values' => null, 'choices' => [], 'required' => false, 'isReference' => false, 'toggles' => []],
            'separator' => ['value' => null, 'values' => null, 'choices' => null, 'required' => false, 'isReference' => false, 'toggles' => []],
            'textarea' => ['value' => null, 'values' => null, 'choices' => null, 'required' => false, 'isReference' => false, 'toggles' => []],
            'title' => ['value' => [__('My title', 'cnrs-data-manager')], 'values' => null, 'choices' => null, 'required' => false, 'isReference' => false, 'toggles' => []],
            'signs' => ['value' => [__('My signing pads', 'cnrs-data-manager')], 'values' => null, 'choices' => null, 'required' => false, 'isReference' => false, 'toggles' => []],
            'toggle' => ['value' => [$toggleUuid], 'values' => null, 'choices' => null, 'required' => false, 'isReference' => false, 'toggles' => []]
        ];
        return json_encode([
            'type' => $type,
            'label' => $toolsLabels[$type],
            'data' => $toolsConfig[$type]
        ], JSON_THROW_ON_ERROR);
    }

    /**
     * Create a new filled form as a JSON string
     *
     * @param array $data The form data
     * @param string $uuid The form UUID
     * @return string The JSON representation of the filled form
     */
    public static function newFilledForm(array $data, string $uuid): string
    {
        $original = json_decode(stripslashes($data['cnrs-dm-front-mission-form-original']), true);
        $international = (int) $_POST['cnrs-dm-front-mission-intl'] === 1;
        unset($data['cnrs-dm-front-mission-form-original']);
        unset($data['cnrs-dm-front-mission-uuid']);
        unset($data['cnrs-dm-front-mission-intl']);
        unset($data['cnrs-dm-front-funder-email']);
        foreach (self::getOriginalToggle() as $originalToggle) {
            unset($data['cnrs-dm-front-toggle-' . $originalToggle['id']]);
        }
        $recompose = [];
        foreach ($data as $index => $element) {
            $clean = str_replace('cnrs-dm-front-mission-form-element-', '', $index);
            $row = [];
            if (stripos($clean, 'input-') !== false) {
                $row = ['index' => (int) str_replace('input-', '', $clean), 'type' => 'input', 'values' => [htmlentities($element)]];
            } else if (stripos($clean, 'datetime-') !== false) {
                $row = ['index' => (int) str_replace('datetime-', '', $clean), 'type' => 'datetime', 'values' => [htmlentities($element)]];
            } else if (stripos($clean, 'time-') !== false && stripos($clean, 'datetime-') === false) {
                $row = ['index' => (int) str_replace('time-', '', $clean), 'type' => 'time', 'values' => [htmlentities($element)]];
            } else if (stripos($clean, 'date-') !== false) {
                $row = ['index' => (int) str_replace('date-', '', $clean), 'type' => 'date', 'values' => [htmlentities($element)]];
            } else if (stripos($clean, 'number-') !== false) {
                $row = ['index' => (int) str_replace('number-', '', $clean), 'type' => 'number', 'values' => [htmlentities($element)]];
            } else if (stripos($clean, 'toggle-') !== false) {
                $row = ['index' => (int) str_replace('toggle-', '', $clean), 'type' => 'toggle', 'values' => [htmlentities($element)]];
            } else if (stripos($clean, 'textarea-') !== false) {
                $row = ['index' => (int) str_replace('textarea-', '', $clean), 'type' => 'textarea', 'values' => [htmlentities($element)]];
            } else if (stripos($clean, 'checkbox-') !== false) {
                if (stripos($clean, 'opt-comment') === false) {
                    $htmlEntities = [];
                    foreach ($element as $el) {
                        $htmlEntities[] = htmlentities($el);
                    }
                    $row = ['index' => (int) str_replace('checkbox-', '', $clean), 'type' => 'checkbox', 'values' => $htmlEntities];
                } else {
                    $index = str_replace(['checkbox-', 'opt-comment-'], '', $clean);
                    $splitIndex = explode('-', $index);
                    $row = ['index' => (int) $splitIndex[0], 'type' => 'checkbox', 'option' => (int) $splitIndex[1], 'values' => htmlentities($element)];
                }
            } else if (stripos($clean, 'radio-') !== false) {
                if (stripos($clean, 'opt-comment') === false) {
                    $row = ['index' => (int) str_replace('radio-', '', $clean), 'type' => 'radio', 'values' => [htmlentities($element)]];
                } else {
                    $index = str_replace(['radio-', 'opt-comment-'], '', $clean);
                    $splitIndex = explode('-', $index);
                    $row = ['index' => (int) $splitIndex[0], 'type' => 'radio', 'option' => (int) $splitIndex[1], 'values' => htmlentities($element)];
                }
            } else if (stripos($clean, 'signs-') !== false) {
                $index = str_replace(['signs-', 'pad-'], '', $clean);
                $splitIndex = explode('-', $index);
                $row = ['index' => (int) $splitIndex[0], 'type' => 'signs', 'pad' => (int) $splitIndex[1], 'values' => json_decode(stripslashes($element), true)];
            }
            if (!empty($row)) {
                $recompose[] = $row;
            }
        }
        $jsonArray = self::prepareNewFormForDB($recompose, $original);
        $original['elements'] = $jsonArray;
        $original['convention'] = Forms::getConvention((int) $data['cnrs-dm-front-convention']);
        $original['revisions'] = 0;
        $original['uuid'] = $uuid;
        $original['international'] = $international;
        return json_encode($original);
    }

    /**
     * Update the form based on the provided POST data and JSON form definition
     *
     * @param array $post The POST data
     * @param string $json The JSON form definition
     * @return string The updated JSON form
     */
    public static function updateForm(array $post, string $json): string
    {
        $form = json_decode($json, true);
        $recompose = [];
        unset($post['cnrs-dm-front-agent-revision']);
        foreach ($post as $index => $element) {
            $clean = str_replace('cnrs-dm-front-mission-form-element-', '', $index);
            $row = [];
            if (stripos($clean, 'input-') !== false) {
                $row = ['index' => (int) str_replace('input-', '', $clean), 'type' => 'input', 'values' => [htmlentities($element)]];
            } else if (stripos($clean, 'datetime-') !== false) {
                $row = ['index' => (int) str_replace('datetime-', '', $clean), 'type' => 'datetime', 'values' => [htmlentities($element)]];
            } else if (stripos($clean, 'time-') !== false && stripos($clean, 'datetime-') === false) {
                $row = ['index' => (int) str_replace('time-', '', $clean), 'type' => 'time', 'values' => [htmlentities($element)]];
            } else if (stripos($clean, 'date-') !== false) {
                $row = ['index' => (int) str_replace('date-', '', $clean), 'type' => 'date', 'values' => [htmlentities($element)]];
            } else if (stripos($clean, 'toggle-') !== false) {
                $row = ['index' => (int) str_replace('toggle-', '', $clean), 'type' => 'toggle', 'values' => [htmlentities($element)]];
            } else if (stripos($clean, 'number-') !== false) {
                $row = ['index' => (int) str_replace('number-', '', $clean), 'type' => 'number', 'values' => [htmlentities($element)]];
            } else if (stripos($clean, 'textarea-') !== false) {
                $row = ['index' => (int) str_replace('textarea-', '', $clean), 'type' => 'textarea', 'values' => [htmlentities($element)]];
            } else if (stripos($clean, 'checkbox-') !== false) {
                if (stripos($clean, 'opt-comment') === false) {
                    $htmlEntities = [];
                    foreach ($element as $el) {
                        $htmlEntities[] = htmlentities($el);
                    }
                    $row = ['index' => (int) str_replace('checkbox-', '', $clean), 'type' => 'checkbox', 'values' => $htmlEntities];
                } else {
                    $index = str_replace(['checkbox-', 'opt-comment-'], '', $clean);
                    $splitIndex = explode('-', $index);
                    $row = ['index' => (int) $splitIndex[0], 'type' => 'checkbox', 'option' => (int) $splitIndex[1], 'values' => htmlentities($element)];
                }
            } else if (stripos($clean, 'radio-') !== false) {
                if (stripos($clean, 'opt-comment') === false) {
                    $row = ['index' => (int) str_replace('radio-', '', $clean), 'type' => 'radio', 'values' => [htmlentities($element)]];
                } else {
                    $index = str_replace(['radio-', 'opt-comment-'], '', $clean);
                    $splitIndex = explode('-', $index);
                    $row = ['index' => (int) $splitIndex[0], 'type' => 'radio', 'option' => (int) $splitIndex[1], 'values' => htmlentities($element)];
                }
            } else if (stripos($clean, 'signs-') !== false) {
                $index = str_replace(['signs-', 'pad-'], '', $clean);
                $splitIndex = explode('-', $index);
                $row = ['index' => (int) $splitIndex[0], 'type' => 'signs', 'pad' => (int) $splitIndex[1], 'values' => json_decode(stripslashes($element), true)];
            }
            if (!empty($row)) {
                $recompose[] = $row;
            }
        }
        $jsonArray = self::prepareNewFormForDB($recompose, $form, true);
        $form['elements'] = $jsonArray;
        return json_encode($form);
    }

    /**
     * Prepare new form data for database storage
     *
     * @param array $data The new form data
     * @param array $original The original form data
     * @param bool $isUpdated If Form is updated or not
     * @return array The modified form data ready for database storage
     */
    private static function prepareNewFormForDB(array $data, array $original, bool $isUpdated = false): array
    {
        $elements = $original['elements'];
        $indexes = [];
        foreach ($data as $row) {
            $index = $row['index'];
            $indexes[] = $row['index'];
            $type = $row['type'];
            $values = $row['values'];
            if (in_array($type, ['input', 'textarea', 'time', 'date', 'datetime', 'number'], true)) {
                $elements[$index]['data']['value'] = $values;
            } else if (in_array($type, ['checkbox', 'radio'], true)) {
                if (!isset($elements[$index]['data']['options'])) {
                    $elements[$index]['data']['options'] = [];
                }
                if (!isset($row['option'])) {
                    $elements[$index]['data']['values'] = $values;
                } else {
                    $elements[$index]['data']['options'][] = ['option' => $row['option'], 'value' => $values];
                }
            } else if ($type === 'signs') {
                $elements[$index]['data']['values'][] = ['pad' => $row['pad'], 'data' => $values];
            } else if ($type === 'toggle') {
                $elements[$index]['data']['choice'] = (int) $values[0];
            }
        }
        if ($isUpdated === false) {
            foreach ($elements as $key => $element) {
                if (!in_array($key, $indexes, true)) {
                    unset($elements[$key]);
                }
            }
        }
        return $elements;
    }
}
