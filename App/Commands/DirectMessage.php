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
        return true;
    }


    public function execute(): void
    {
        $this->message->author->user->sendMessage('daunis esi?');
    }
}
