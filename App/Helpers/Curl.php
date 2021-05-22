<?php

namespace App\Helpers;

class Curl
{
    public static function Get(string $url): string
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);

        return $output;
    }

    public static function GetJson(string $url): string
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: application/json', 'User-Agent: https://github.com/zingmars/discord-bot'));
        $output = curl_exec($curl);
        curl_close($curl);

        return $output;
    }
}
