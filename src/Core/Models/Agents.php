<?php

namespace CnrsDataManager\Core\Models;

class Agents
{
    /**
     * Get agents based on category ID and mode.
     *
     * @param int $cat_id The category ID.
     * @param string $mode The mode for filtering agents. Default is 'all'.
     *                    Possible values are 'teams', 'services', and 'platforms'.
     * @return array The array of agents retrieved based on the given category ID and mode.
     */
    public static function getAgents(int $cat_id, string $mode = 'all'): array
    {
        $provider = CNRS_DATA_MANAGER_XML_DATA['agents'];
        if (in_array($mode, ['teams', 'services', 'platforms'])) {
            global $wpdb;
            $xml = $wpdb->get_results( "SELECT xml_entity_id as id FROM {$wpdb->prefix}cnrs_data_manager_relations WHERE type = '{$mode}' AND term_id = {$cat_id}", ARRAY_A );
            if (!empty($xml) && isset($xml[0]['id'])) {
                $users = [];
                $entity_id = $xml[0]['id'];
                $type = 'equipes';
                if ($mode === 'services') {
                    $type = 'services';
                } else if ($mode === 'platforms') {
                    $type = 'plateformes';
                }
                foreach ($provider as $agent) {
                    $entity = $agent[$type];
                    foreach ($entity as $row) {
                        if ((int) $row[substr($type, 0, -1) . '_id'] ===(int)  $entity_id) {
                            $users[] = $agent;
                            break;
                        }
                    }
                }

                return $users;
            }
            return [];
        }
        return $provider;
    }
}