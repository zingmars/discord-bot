<?php

namespace App\Commands;

use App\Classes\AbstractCommand;
use App\Helpers\Curl;
use App\Helpers\Env;
use Discord\Parts\Embed\Embed;
use Exception;

class Locate extends AbstractCommand
{
    /**
     * @return bool
     */
    public function validate(): bool
    {
        if (empty($this->arguments)) {
            $reply = 'syntax: %s%s [location]';
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
        $url = 'http://api.positionstack.com/v1/forward?access_key=%s&query=%s&output=json';
        $query = urlencode(implode(" ", $this->arguments));
        $url = sprintf($url, Env::Get('POSITIONSTACK_API_KEY'), $query);

        $output = Curl::Get($url);
        $result = json_decode($output);

        if (isset($result->error) || count($result->data) < 1) {
            $this->reply("An error occurred: " . $result->error->message);
        } else {
            $best_result = $result->data[0];
            $embed = new Embed($this->discord);

            $body = '**name:** %s, **country:**: %s (%s), **continent**: %s' . PHP_EOL
                . '**county**: %s (%s), **locality:** %s' . PHP_EOL
                . '**latitude**: %s, **longitude**: %s';
            $body = sprintf($body, $best_result->name, $best_result->country, $best_result->country_code, $best_result->continent,
                $best_result->county, $best_result->region_code, $best_result->locality, $best_result->latitude, $best_result->longitude);

            if (isset($best_result->street)) {
                $body .= PHP_EOL . '**street:** %s, **number:** %s, **postal_code:** %s';
                $body = sprintf($body, $best_result->street, $best_result->number, $best_result->postal_code);
            }

            $body .= PHP_EOL . PHP_EOL . 'Open in google maps: https://www.google.com/maps/search/?api=1&query=%s,%s';
            $body = sprintf($body, $best_result->latitude, $best_result->longitude);

            $embed->setDescription($body);
            $this->reply('', $embed);
        }
    }
}
