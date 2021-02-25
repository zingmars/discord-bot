<?php

namespace App\Commands;

use App\Interfaces\CommandInterface;
use Discord\Discord;
use Discord\Parts\Channel\Message;

class Eightball implements CommandInterface
{

    public function __construct(string $commandName, array $arguments, Message $message, Discord $discord)
    {
        $message->reply('not yet implemented');
    }
}
