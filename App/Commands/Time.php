<?php

namespace App\Commands;

use App\Classes\AbstractCommand;
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
        $location = implode("_", $this->arguments);
        $tz = "";

        foreach (DateTimeZone::listIdentifiers() as $identifier) {
            if (str_contains($identifier, $location)) {
                $tz = $identifier;
                break;
            }
        }

        if ($tz === "") {
            $this->reply("Could not find " . implode(" ", $this->arguments) . "!");
        }

        $date = new DateTime("now", new DateTimeZone($tz));
        $this->reply($date->format("Y-m-d H:i:s T"));
    }
}
