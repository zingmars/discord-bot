<?php

namespace App\Commands;

use App\Classes\AbstractCommand;
use Discord\Parts\Embed\Embed;
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
        //$this->channelMessage('PONG');
        $embed = new Embed($this->discord);
        $embed->setDescription('PONG');
        $this->reply('', $embed);
    }
}
