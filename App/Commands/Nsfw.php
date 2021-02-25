<?php

namespace App\Commands;

use Discord\Discord;
use Discord\Parts\Channel\Message;

class Nsfw implements \App\Interfaces\CommandInterface
{

    public function __construct(string $commandName, array $arguments, Message $message, Discord $discord)
    {
        $message->reply('not yet implemented');
    }
}
