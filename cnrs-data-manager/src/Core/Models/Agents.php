<?php

namespace CnrsDataManager\Core\Models;

class Agents
{
    private static array $allowed_filters = ['teams' => ['services', 'platforms'], 'services' => ['teams', 'platforms'], 'platforms' => ['teams', 'services']];
    private static array $mapper = ['teams' => 'equipes', 'services' => 'services', 'platforms' => 'plateformes'];
    private static array $entityMapper = ['equipes' => 'equipe', 'services' => 'service', 'plateformes' => 'plateforme'];

    /**
     * Retrieves agents based on category ID, mode, and filter.
     *
     * @param int $cat_id The category ID.
     * @param string $mode The mode (teams, services, platforms).
     * @param string|null $filter The filter if not null (teams, services, platforms).
     *
     * @return array The array of agents.
     */
    public static function getAgents(int $cat_id, string $mode, string|null $filter): array
    {
        $provider = filterAgents(CNRS_DATA_MANAGER_XML_DATA['agents']);
        if (in_array($mode, ['teams', 'services', 'platforms'])) {
            global $wpdb;
            $xml = $wpdb->get_results( "SELECT xml_entity_id as id FROM {$wpdb->prefix}cnrs_data_manager_relations WHERE type = '{$mode}' AND term_id = {$cat_id}", ARRAY_A );
            if (!empty($xml) && isset($xml[0]['id'])) {
                $users = [];
                $type = 'equipes';
                if ($mode === 'services') {
                    $type = 'services';
                } else if ($mode === 'platforms') {
                    $type = 'plateformes';
                }
                if ($filter !== null && !in_array($filter, self::$allowed_filters[$mode], true)) {
                    return [];
                }
                foreach ($xml as $item) {
                    $entity_id = $item['id'];
                    foreach ($provider as $agent) {
                        $entity = $agent[$type];
                        foreach ($entity as $row) {
                            if ((int) $row[substr($type, 0, -1) . '_id'] === (int) $entity_id) {
                                $users[] = $agent;
                            }
                        }
                    }
                }
                return $filter !== null ? self::filter($users, $filter) : $users;
            }
            return [];
        }

        $provider = self::provideSearchResults($provider);

        if (count($provider) === 0) {
            return [
                'count' => 0,
                'displayed_items' => 0,
                'data' => [],
                'pages' => 0,
                'current' => 1,
                'next' => null,
                'previous' => null
            ];
        }

        $batch = isset($_GET['cdm-pagi']) && ctype_digit($_GET['cdm-pagi']) ? (int) $_GET['cdm-pagi'] : 10;
        $page = isset($_GET['cdm-page']) && ctype_digit($_GET['cdm-page']) ? (int) $_GET['cdm-page'] : 1;
        $index = $page > 0 ? $page - 1 : 0;
        $length = count($provider);
        $chunk = array_chunk($provider, $batch);
        return [
            'count' => $length,
            'displayed_items' => count($chunk[$index]),
            'data' => $chunk[$index],
            'pages' => count($chunk),
            'current' => $index + 1,
            'next' => $index >= count($chunk) - 1 ? null : $index + 2,
            'previous' => $index === 0 ? null : $index
        ];
    }

    private static function filter(array $users, string $filter): array
    {
        global $wpdb;
        $filterKey = self::$mapper[$filter];
        $word = self::$entityMapper[$filterKey];
        $orphans = [
            'xml_name' => null,
            'wp_name' => null,
            'agents' => []
        ];
        $render = [];
        foreach ($users as $user) {
            if (empty($user[$filterKey])) {
                $orphans['agents'][] = $user;
            } else {
                foreach ($user[$filterKey] as $entity) {
                    $index = $word . '-' . $entity[$word . '_id'];
                    if (!isset($render[$index])) {
                        $res = $wpdb->get_results( "SELECT term_id FROM {$wpdb->prefix}cnrs_data_manager_relations WHERE xml_entity_id = {$entity[$word . '_id']} AND type = '{$filter}'", ARRAY_A );
                        $render[$index] = [
                            'xml_name' => $entity['nom'],
                            'wp_name' => get_the_title((int) $res[0]['term_id']),
                            'agents' => []
                        ];
                    }
                    $render[$index]['agents'][] = $user;
                }
            }
        }
        $render['orphans'] = $orphans;
        ksort($render, SORT_STRING);
        return $render;
    }

    /**
     * Provides search results based on the given agents array and filters specified in $_GET parameters.
     *
     * @param array $agents The array of agents to search through.
     *
     * @return array The filtered array of agents based on the search and filter criteria.
     */
    private static function provideSearchResults(array $agents): array
    {
        if (empty($agents)) {
            return $agents;
        }

        $allowedFilters = ['all', 'lastname', 'firstname', 'status', 'membership'];

        $search = isset($_GET['cdm-search']) && strlen($_GET['cdm-search']) > 0 ? stripslashes($_GET['cdm-search']) : '';
        $filter = isset($_GET['cdm-type']) && in_array($_GET['cdm-type'], $allowedFilters, true) ? stripslashes($_GET['cdm-type']) : 'all';
        $filteredAgents = $agents;
        if (strlen($search) > 0) {
            $filteredAgents = array_filter($agents, function ($agent) use ($search, $filter) {
                if ($filter === 'all') {
                    return stripos($agent['nom'], $search) !== false || stripos($agent['prenom'], $search) !== false || stripos($agent['statut'], $search) !== false || self::searchInCats($agent['equipes'], $search) !== false;
                } else if ($filter === 'lastname') {
                    return stripos($agent['nom'], $search) !== false;
                } else if ($filter === 'firstname') {
                    return stripos($agent['prenom'], $search) !== false;
                } else if ($filter === 'status') {
                    return stripos($agent['statut'], $search) !== false;
                } else if ($filter === 'membership') {
                    return self::searchInCats($agent['equipes'], $search) !== false;
                }
                return false;
            });
        }
        return $filteredAgents;
    }

    /**
     * Searches for a specific string in the titles of teams.
     *
     * @param array $teams The array of teams to search in.
     * @param string $search The string to search for.
     *
     * @return bool Returns true if the string is found in any of the team titles, otherwise false.
     */
    private static function searchInCats(array $teams, string $search): bool
    {
        foreach ($teams as $team) {
            $extra = $team['extra'];
            if (stripos($extra['title'], $search) !== false) {
                return true;
            }
        }
        return false;
    }
}