<?php

namespace App\Classes;

use Discord\Discord;
use Discord\Parts\Channel\Message;
use Monolog\Logger;

class Command
{
    public Discord $discord;
    public Message $message;
    public Logger $logger;

    public string $commandName;
    public array $arguments;

    public function __construct(Discord $discord, Message $message, Logger $logger, array $arguments)
    {
        $this->discord = $discord;
        $this->message = $message;
        $this->logger = $logger;
        $this->commandName = $arguments[0];
        array_shift($arguments);
        $this->arguments = $arguments;
    }
}
