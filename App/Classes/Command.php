<?php

namespace App\Classes;

use Discord\Discord;
use Discord\Parts\Channel\Message;

class Command
{
    public Discord $discord;
    public Message $message;

    public string $commandName;
    public array $arguments;

    public function __construct(Discord $discord, Message $message, array $arguments)
    {
        $this->discord = $discord;
        $this->message = $message;
        $this->commandName = $arguments[0];
        array_shift($arguments);
        $this->arguments = $arguments;
    }
}
