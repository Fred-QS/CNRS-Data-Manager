<?php

namespace CnrsDataManager\Core\Models;

class Map
{
    public static function getData(): array
    {
        global $wpdb;
        $mainCoords = $wpdb->get_results( "SELECT default_latitude as lat, default_longitude as lng FROM {$wpdb->prefix}cnrs_data_manager_settings", ARRAY_A );
        $options = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}cnrs_data_manager_map_settings", ARRAY_A );
        $markers = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}cnrs_data_manager_map_markers", ARRAY_A );

        return [
            'main' => [
                'lat' => $mainCoords[0]['lat'],
                'lng' => $mainCoords[0]['lng']
            ],
            'sunlight' => (int) $options[0]['sunlight'] === 1,
            'view' => $options[0]['view'],
            'stars' => (int) $options[0]['stars'] === 1,
            'black_bg' => (int) $options[0]['black_bg'] === 1,
            'atmosphere' => (int) $options[0]['atmosphere'] === 1,
            'markers' => array_map(function($marker) {
                return ['id' => $marker['id'], 'lat' => $marker['lat'], 'lng' => $marker['lng'], 'title' => $marker['title']];
            }, $markers)
        ];
    }
}