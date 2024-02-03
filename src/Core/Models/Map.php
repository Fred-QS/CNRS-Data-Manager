<?php

namespace CnrsDataManager\Core\Models;

use JsonException;

class Map
{
    /**
     * @throws JsonException
     */
    public static function getData(): string
    {
        $data = [
            'main' => [
                'lat' => 40.7,
                'lng' => -74.1,
                'land' => '#EEE'
            ],
            'markers' => [
                ['lat' => 40.7, 'lng' => -74.1, 'title' => 'New York', 'color' => '#0000FF'],
                ['lat' => 35.6, 'lng' => 139.7, 'title' => 'Tokyo', 'color' => '#FF0000'],
                ['lat' => 52.5, 'lng' => 13.40, 'title' => 'Berlin', 'color' => '#00FF00']
            ]
        ];
        return json_encode($data, JSON_THROW_ON_ERROR);
    }
}