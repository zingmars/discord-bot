<?php

namespace App\Services;

// Adapted from: https://github.com/IntriguingTiles/cleverbot-free/blob/master/index.js
use App\Helpers\Curl;
use App\Helpers\FileStore;

class CleverbotService
{
    private string $prefix = './Data/';
    private string $history_file;
    private string $cookie_file;
    private ?string $cbsid = null;
    private array $cookies;
    private ?array $history;

    public function __construct($history_file, $cookie_file)
    {
        $this->history_file = $history_file;
        $this->cookie_file = $cookie_file;

        if (!file_exists($this->prefix)) {
            $result = mkdir($this->prefix, 0770, true);
            if ($result === false) {
                die("Data directory is required and could not be created");
            }
        }

        $this->revalidateCookie();
        $this->loadHistory();
    }

    private function revalidateCookie() {
        $url = "https://www.cleverbot.com/";

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.85 Safari/537.36'));
        $output = curl_exec($curl);
        curl_close($curl);

        preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $output, $matches);
        $cookies = array();
        foreach($matches[1] as $item) {
            parse_str($item, $cookie);
            $cookies = array_merge($cookies, $cookie);
        }
        $this->cookies = $cookies;
        $this->cookies["_cbsid"] = '-1';

        //FileStore::Set($this->cookie_file, var_export($this->cookies));
    }

    private function loadHistory() {
        if (!file_exists($this->prefix . $this->history_file)) {
            fopen($this->prefix . $this->history_file, 'w') or die("Can't create file");
            $this->history = [];
        } else {
            $history = FileStore::Get($this->history_file);
            if ($history !== false && strlen($history) > 0) {
                $this->history = json_decode($history);
            } else {
                $this->history = [];
            }
        }
    }

    private function dumpHistory() {
        FileStore::Set($this->history_file, json_encode($this->history));
    }

    public function makeRequest($message): ?string
    {
        $payload = 'stimulus=' . htmlspecialchars(trim($message)) . "&";

        for ($i = 0; $i < count($this->history); $i++) {
            $payload .= 'vText' . ($i + 2) . '=' . htmlspecialchars($this->history[$i]) . '&';
        }

        $payload .= 'cb_settings_scripting=no&islearning=1&icognoid=wsf&icognocheck=';
        $payload .= md5(substr($payload, 7, 33));

        var_dump($payload);

        $url = 'https://www.cleverbot.com/webservicemin?uc=UseOfficialCleverbotAPI';
        $url .= $this->cbsid !== null ? '&out=&in=&bot=c&cbsid=' . $this->cbsid . '&xai=' . substr($this->cbsid, 0, 3) . '&ns=1&al=&dl=&flag=&user=&mode=1&alt=0&reac=&emo=&sou=website&xed=&' : '';

        var_dump($url);

        $response = Curl::Post($url, $payload, $this->cookies);
        var_dump($response);

        if (strlen($response) > 0 && $response !== '<HTML><BODY>DENIED</BODY></HTML>') {
            $response = explode("\r", $response);
            $this->cbsid = $response[1];

            array_push($this->history, $message);
            if (count($this->history) > 50) {
                array_shift($this->history);
            }
            $this->$this->dumpHistory();

            return $response[0];
        }

        return null;
    }
}