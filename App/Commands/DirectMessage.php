<?php

namespace App\Commands;

use App\Classes\AbstractCommand;
use App\Interfaces\CommandInterface;

class DirectMessage extends AbstractCommand implements CommandInterface
{
    /**
     * @return bool
     */
    public function validate(): bool
    {
        if ($this->authorIsOwner()) {
            return true;
        }

        $this->react('🔐');
        return false;
    }


    public function execute(): void
    {
        $this->message->author->user->sendMessage('daunis esi?');
    }
}
