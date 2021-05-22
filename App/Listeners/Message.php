<?php

namespace App\Listeners;

use App\Classes\Command;
use App\Helpers\CommandHelper;
use App\Helpers\Env;
use App\Helpers\Log;
use App\Services\CleverbotService;
use Discord\Discord;
use Discord\Parts\Channel\Message as DiscordMessage;
use Exception;

class Message
{
    private DiscordMessage $message;
    private Discord $discord;
    private CleverbotService $cleverbotService;

    /**
     * @param Discord $discord
     * @param DiscordMessage $message
     * @throws Exception
     */
    public function __construct(Discord $discord, CleverbotService $cleverbotService, DiscordMessage $message)
    {
        $this->discord = $discord;
        $this->message = $message;
        $this->cleverbotService = $cleverbotService;

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

        // Memes
        if (str_contains($this->message->content, 'https://cdn.discordapp.com/attachments/810884195516809238/829332905426812938/c82ef193241b16732d724ddac309698d.mp4')) {
            $this->message->reply('debils esi?');
        }

        // Cleverbot
        preg_match('/^<@!(.*?)>/s', $content, $match);
        if (count($match) > 0) {
            if ($match[1] === Env::get('BOT_USER_ID')) {
                $message = strstr($content," ");
                $response = $this->cleverbotService->makeRequest($message);
                if ($response !== null) {
                    $this->message->reply($response);
                }
            }
        }

        if ($content[0] !== Env::get('COMMAND_PREFIX')) {
            return;
        }

        $content = substr($content, 1);
        $arguments = explode(' ', $content);

        $commandName = $arguments[0];

        $logMessage = 'Received a command from %s: %s';
        $commandClass = CommandHelper::getClassName($commandName);

        //TODO: exception handling
        if (class_exists($commandClass)) {
            $command = new Command($this->discord, $this->message, $arguments);
            new $commandClass($command);
        } else {
            $this->message->react('❌');
        }

        Log::console(sprintf($logMessage, $this->message->author->username, $commandName, implode(' ', $arguments)));
    }
}
