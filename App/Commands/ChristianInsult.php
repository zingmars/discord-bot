<?php

namespace App\Commands;

use App\Classes\AbstractCommand;
use App\Helpers\Env;
use App\Helpers\FileStore;
use Exception;

class ChristianInsult extends AbstractCommand
{
    /**
     * @return bool
     */
    public function validate(): bool
    {
        if (empty($this->arguments) || !preg_match('/^<@!(.*)>$/', $this->arguments[0])) {
            $reply = 'syntax: %s%s [user]';
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
        $lines = FileStore::Get("Luther.txt");
        if ($lines == false) {
            $this->channelMessage($this->arguments[0] . ' is a good boy!');
            return;
        }

        $lines = preg_split("/\r\n|\n|\r/", $lines);
        $insult = $lines[array_rand($lines)];

        $this->channelMessage($this->arguments[0] . ': ' . $insult);
        $this->message->delete();
    }
}
