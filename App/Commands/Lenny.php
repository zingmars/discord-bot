<?php

namespace App\Commands;

use App\Classes\AbstractCommand;
use App\Interfaces\CommandInterface;
use Exception;

class Lenny extends AbstractCommand implements CommandInterface
{

    /**
     * @return bool
     */
    public function validate(): bool
    {
        return true;
    }

    /**
     * @throws Exception
     */
    public function execute(): void
    {
        $this->channelMessage('( ͡° ͜ʖ ͡°)');
        $this->message->delete();
    }
}
