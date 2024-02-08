<?php

namespace CnrsDataManager\Core\Controllers;

class HttpClient
{
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
}