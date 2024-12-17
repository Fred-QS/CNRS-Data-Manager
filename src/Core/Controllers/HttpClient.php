<?php

namespace CnrsDataManager\Core\Controllers;

class HttpClient
{
    /**
     * Makes a CURL call to the specified URL and returns the response XML string.
     *
     * @param string|null $url The URL to make the CURL call to. If null, no CURL call will be made.
     * @param bool $test Determines if the method is being called for testing purposes or not. Default is false.
     * @return bool|string If $test is true, returns true if the CURL call was successful and the response XML string
     *                     passes all the required checks, otherwise returns false. If $test is false, returns the response
     *                     XML string.
     */
    public static function call(string|null $url, bool $test = false): bool|string
    {
        $check = false;
        $xml = '';
        if ($url !== null) {
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => $url,
                CURLOPT_FAILONERROR => true
            ]);
            ob_start();
            curl_exec($curl);
            $xml = ob_get_clean();
            $check = !($xml === false)
                && stripos($xml, '<reference>') !== false
                && stripos($xml, '<equipes>') !== false
                && stripos($xml, '<services>') !== false
                && stripos($xml, '<plateformes>') !== false
                && stripos($xml, '<agents>') !== false
                && stripos($xml, '<equipe>') !== false
                && stripos($xml, '<service>') !== false
                && stripos($xml, '<plateforme>') !== false
                && stripos($xml, '<agent>') !== false;
        }
        if ($test === true) {
            return $check;
        }
        return $xml;
    }

    /**
     * Retrieves the IP address of the user.
     *
     * This method makes a cURL request to the "http://httpbin.org/ip" endpoint
     * to retrieve the IP address of the user. It then decodes the JSON response
     * and returns the IP address.
     *
     * @return string The IP address of the user.
     */
    public static function get_site_ip(): string
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "http://httpbin.org/ip");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        $ip = json_decode($output, true);
        return $ip['origin'];
    }

    public static function getPublications(): array
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://oskar-bordeaux.fr/rest/collections/69167023-87f3-4d50-a467-b47ba31c6411/items',
            CURLOPT_FAILONERROR => true
        ]);
        ob_start();
        curl_exec($curl);
        $json = ob_get_clean();
        $itemsUuids = array_map(function($row) {
            return $row['uuid'];
        },json_decode($json, true));

        $result = [];

        foreach ($itemsUuids as $itemsUuid) {
            $curlItem = curl_init();
            curl_setopt_array($curlItem, [
                CURLOPT_URL => 'https://oskar-bordeaux.fr/rest/items/' . $itemsUuid . '/metadata',
                CURLOPT_FAILONERROR => true
            ]);
            ob_start();
            curl_exec($curlItem);
            $jsonItem = ob_get_clean();
            $result[] = json_decode($jsonItem, true);
        }

        $jsonPath = CNRS_DATA_MANAGER_PATH . '/api-tmp/publications.json';
        $file = fopen($jsonPath, 'w+');
        fwrite($file, json_encode($result, JSON_PRETTY_PRINT));
        fclose($file);

        return $result;
    }

    /**
     * Checks if the current user's IP address is the maintenance admin IP.
     *
     * @return bool Returns true if the current user's IP address is the maintenance admin IP, otherwise false.
     */
    public static function maintenance_admin(): bool
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip === CNRS_DATA_MANAGER_MAINTENANCE_IP;
    }
}
