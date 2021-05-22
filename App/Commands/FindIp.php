<?php

namespace App\Commands;

use App\Classes\AbstractCommand;
use App\Helpers\Curl;
use App\Helpers\Env;
use Discord\Parts\Embed\Embed;
use Exception;

class FindIp extends AbstractCommand
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
        $url = 'http://ip-api.com/json/%s?fields=65535';
        $url = sprintf($url, $this->arguments[0]);

        $output = Curl::Get($url);
        $result = json_decode($output);

        if ($result->status === "fail") {
            $message = "Encountered an error: %s.";
            $message = sprintf($message, $result->message);
            $this->react('❌');
            $this->reply($message);
            return;
        }

        $embed = new Embed($this->discord);
        $embed->setTitle('IP lookup:');
        $body = '**Lookup for IP:** %s (%s)' . PHP_EOL
            . '**ISP:** %s(%s) - %s' . PHP_EOL
            . '**Country:** %s (%s); **Time zone:** %s' . PHP_EOL
            . '**Region:** %s(%s); **City:** %s; **Zip code:** %s' . PHP_EOL
            . '**Approx. location:** %s %s';
        $body = sprintf($body, $result->query, $result->reverse, $result->isp, $result->org, $result->as,
            $result->country, $result->countryCode, $result->timezone, $result->regionName, $result->region,
            $result->city, $result->zip, $result->lat, $result->lon);

        $embed->setDescription($body);
        $this->reply('', $embed);
    }
}
