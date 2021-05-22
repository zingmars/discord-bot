<?php

namespace App\Commands;

use App\Classes\AbstractCommand;
use Exception;

class Ping extends AbstractCommand
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
        $this->channelMessage('PONG');
    }
}
