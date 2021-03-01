<?php

namespace App\Classes;

use Discord\Discord;
use Discord\Parts\Channel\Message;
use Doctrine\ORM\EntityManager;

class Command
{
    public Discord $discord;
    public Message $message;
    public EntityManager $entityManager;

    public string $commandName;
    public array $arguments;

    public function __construct(Discord $discord, EntityManager $entityManager, Message $message, array $arguments)
    {
        $this->discord = $discord;
        $this->message = $message;
        $this->commandName = $arguments[0];
        array_shift($arguments);
        $this->arguments = $arguments;
        $this->entityManager = $entityManager;
    }
}
