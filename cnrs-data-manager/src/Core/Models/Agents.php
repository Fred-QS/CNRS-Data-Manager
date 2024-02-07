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
        $provider = CNRS_DATA_MANAGER_XML_DATA['agents'];
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
        return $provider;
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
}