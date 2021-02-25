<?php

namespace App\Listeners;

use App\Helpers\CommandHelper;
use App\Helpers\Log;
use App\Helpers\Retard;
use Discord\Discord;
use \Discord\Parts\Channel\Message as DiscordMessage;
use Exception;

class Message
{
    private DiscordMessage $message;
    private Discord $discord;

    /**
     * @param Discord $discord
     * @param DiscordMessage $message
     * @throws Exception
     */
    public function __construct(Discord $discord, DiscordMessage $message)
    {
        $this->discord = $discord;
        $this->message = $message;

        $logMessage = 'Received a message from %s: %s';
        Log::console(sprintf($logMessage, $message->author->username, $message->content));
        $this->handleCommand();
    }

    /**
     * @throws Exception
     */
    private function handleCommand(): void
    {
        $content = $this->message->content;

        if (strlen($content) === 0) {
            return;
        }

        if ($content[0] !== '.') {
            return;
        }

        $content = str_replace('.', '', $content);
        $arguments = explode(' ', $content);

        $commandName = $arguments[0];

        $logMessage = 'Received a command from %s: %s';
        $commandClass = CommandHelper::getClassName($commandName);

        array_shift($arguments);

        if (class_exists($commandClass)) {
            new $commandClass($commandName, $arguments, $this->message, $this->discord);
        } else {
            $this->message->reply(Retard::getRandomMessage());
        }

        Log::console(sprintf($logMessage, $this->message->author->username, $commandName, implode(' ', $arguments)));
    }
}
