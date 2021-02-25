<?php

namespace App\Interfaces;

use Discord\Discord;
use Discord\Parts\Channel\Message;

interface CommandInterface
{
    public function __construct(string $commandName, array $arguments, Message $message, Discord $discord);
}
