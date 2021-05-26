<?php

namespace App\Commands;

use App\Classes\AbstractCommand;
use App\Helpers\Curl;
use App\Helpers\Env;
use Discord\Parts\Embed\Embed;
use Exception;

class LocateIp extends AbstractCommand
{
    /**
     * @return bool
     */
    public function validate(): bool
    {
        if (empty($this->arguments)) {
            $reply = 'syntax: %s%s [ip address]';
            $this->reply(sprintf($reply, Env::Get('COMMAND_PREFIX'), $this->name));
            $this->react('❌');
            return false;
        }

        return true;
    }

    /**
     * @throws Exception
     */
    public function execute(): void
    {
        $apiKey = Env::Get('SHODAN_API_KEY');
        if (empty($apiKey)) {
            $this->reply('API key not set. No IP lookups for you :(');
            $this->react('❌');
            return;
        }

        $url = 'https://api.shodan.io/shodan/host/%s?key=%s';
        $url = sprintf($url, urlencode($this->arguments[0]), $apiKey);

        $output = Curl::Get($url);
        $result = json_decode($output);

        if (isset($result->error)) {
            $message = "Encountered an error: %s";
            $message = sprintf($message, $result->error);
            $this->react('❌');
            $this->reply($message);
            return;
        }

        $embed = new Embed($this->discord);
        $embed->setTitle('IP lookup:');
        $body = '**Lookup for IP:** %s (%s)' . PHP_EOL
            . '**ISP:** %s (%s) - %s' . PHP_EOL
            . '**Country:** %s (%s), **Region:**: %s' . PHP_EOL
            . '**City:** %s, **Approx. location:** %s %s' . PHP_EOL
            . '**OS:** %s';
        $body = sprintf($body, $result->ip_str, $result->hostnames[0] ?? "", $result->isp, $result->org,
            $result->asn, $result->country_name, $result->country_code, $result->region_code, $result->city,
            $result->latitude, $result->longitude, $result->os);

        $embed->setDescription($body);
        $this->reply('', $embed);
    }
}
