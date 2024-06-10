<?php

namespace CnrsDataManager\Core\Models;

class Map
{
    public static function getData(): array
    {
        global $wpdb;
        $mainCoords = $wpdb->get_row( "SELECT default_latitude as lat, default_longitude as lng FROM {$wpdb->prefix}cnrs_data_manager_settings", ARRAY_A );
        $options = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}cnrs_data_manager_map_settings", ARRAY_A );
        $markers = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}cnrs_data_manager_map_markers", ARRAY_A );

        return [
            'main' => [
                'lat' => $mainCoords['lat'],
                'lng' => $mainCoords['lng']
            ],
            'sunlight' => (int) $options['sunlight'] === 1,
            'view' => $options['view'],
            'stars' => (int) $options['stars'] === 1,
            'black_bg' => (int) $options['black_bg'] === 1,
            'atmosphere' => (int) $options['atmosphere'] === 1,
            'markers' => array_map(function($marker) {
                return ['id' => $marker['id'], 'lat' => $marker['lat'], 'lng' => $marker['lng'], 'title' => $marker['title']];
            }, $markers)
        ];
    }
}
