<?php

namespace App\Commands;

use App\Classes\AbstractCommand;
use App\Helpers\Curl;
use App\Helpers\Env;
use DateTime;
use DateTimeZone;
use Exception;

class Time extends AbstractCommand
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
        $url = 'http://api.positionstack.com/v1/forward?access_key=%s&query=%s&output=json&timezone_module=1';
        $query = implode(" ", $this->arguments);
        $url = sprintf($url, Env::Get('POSITIONSTACK_API_KEY'), $query);

        $output = Curl::Get($url);
        $result = json_decode($output);

        if (isset($result->error) && count($result->data) < 1) {
            $this->reply("An error occurred: " . $result->error->message);
        } else {
            var_dump($result);
            $date = new DateTime("now", new DateTimeZone($result->data[0]->timezone_module->name));
            $this->reply($date->format("Y-m-d H:i:s T"));
        }
    }
}
