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

    public static function Post(string $url, string $data, array $cookies): string
    {
        $headers = array('User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.85 Safari/537.36', 'Content-Type: text/plain');
        $cookiesStr = '';
        foreach ($cookies as $name => $value) {
            $cookiesStr .= $name . '=' . $value . '; ';
        }
        $cookiesStr = trim("Cookie: " . $cookiesStr);
        var_dump($cookiesStr);

        array_push($headers, $cookiesStr);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $output = curl_exec($curl);
        curl_close($curl);

        return $output;
    }
}
