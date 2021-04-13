<?php

namespace App\Commands;

use App\Classes\AbstractCommand;
use App\Interfaces\CommandInterface;

class Tinker extends AbstractCommand implements CommandInterface
{

    /**
     * @return bool
     */
    public function validate(): bool
    {
        if ($this->authorIsOwner() && $this->message->author->id === '203950441237577738') {
            return true;
        }

        $this->react('🔐');
        return false;
    }

    public function execute(): void
    {
        $command = implode(' ', $this->arguments);
        $evalResult = (string)eval($command);

        $reply = '```%s%s%s```';
        $this->reply(sprintf($reply, PHP_EOL, $evalResult, PHP_EOL));
    }
}
