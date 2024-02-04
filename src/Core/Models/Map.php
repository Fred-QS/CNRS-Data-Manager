<?php

namespace CnrsDataManager\Core\Models;

class Map
{
    public static function getData(): array
    {
        global $wpdb;
        $mainCoords = $wpdb->get_results( "SELECT default_latitude as lat, default_longitude as lng FROM {$wpdb->prefix}cnrs_data_manager_settings", ARRAY_A );
        $options = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}cnrs_data_manager_map_settings", ARRAY_A );

        $data = [
            'main' => [
                'lat' => $mainCoords[0]['lat'],
                'lng' => $mainCoords[0]['lng']
            ],
            'sunlight' => (int) $options[0]['sunlight'] === 1,
            'view' => $options[0]['view'],
            'stars' => (int) $options[0]['stars'] === 1,
            'black_bg' => (int) $options[0]['black_bg'] === 1,
            'atmosphere' => (int) $options[0]['atmosphere'] === 1,
            'markers' => [
                ['lat' => 40.7, 'lng' => -74.1, 'title' => 'New York'],
                ['lat' => 35.6, 'lng' => 139.7, 'title' => 'Tokyo'],
                ['lat' => 52.5, 'lng' => 13.40, 'title' => 'Berlin']
            ]
        ];
        return $data;
    }
}