<?php

namespace App\Listeners;

use App\Classes\Command;
use App\Helpers\CommandHelper;
use App\Helpers\Env;
use App\Services\CleverbotService;
use Discord\Discord;
use Discord\Parts\Channel\Message as DiscordMessage;
use Exception;
use Monolog\Logger;

class Message
{
    private DiscordMessage $message;
    private Discord $discord;
    private Logger $logger;
    private CleverbotService $cleverbotService;

    /**
     * @param Discord $discord
     * @param DiscordMessage $message
     * @throws Exception
     */
    public function __construct(Discord $discord, CleverbotService $cleverbotService, DiscordMessage $message)
    {
        $this->discord = $discord;
        $this->logger = $discord->__get('options')['logger'];
        $this->message = $message;
        $this->cleverbotService = $cleverbotService;


        $logMessage = 'Received a message from %s#%s: %s';
        $this->logger->info(sprintf($logMessage, $this->message->author->username, $this->message->author->discriminator, $message->content));

        // Don't handle private messages.
        if ($this->message->channel->is_private) return;

        // Don't let the bot talk to itself.
        if ($this->message->author->id === Env::get('BOT_USER_ID')) return;

        // Don't let the bot handle bot messages to avoid loops.
        if ($this->message->author->bot) return;

        // Ghetto error handling
        try {
            $this->handleCommand();
        } catch (Exception $ignored) {
            $this->message->react('🚨');
        }
    }

    /**
     * @throws Exception
     */
    private function handleCommand(): void
    {
        if (strlen($this->message->content) === 0) {
            return;
        }

        // Cleverbot (only if enabled and bot was directly mentioned or replied to)
        if (Env::get('ENABLE_CLEVERBOT') === "True") {
            // Change this to check message contents for the bot id if you want to disable cleverbot on replies
            if ($this->message->mentions->count() > 0 && $this->message->mentions->first()->id === Env::get('BOT_USER_ID')) {
                $response = $this->cleverbotService->handle($this->message);
                if ($response !== null || $response !== false) {
                    $this->message->reply($response);
                } else {
                    $this->message->react('😭');
                }
                return;
            }
        }

        // Handle commands normally
        $content = $this->message->content;

        // Drop the message if there's no command prefix
        if ($content[0] !== Env::get('COMMAND_PREFIX')) {
            return;
        }

        // Some prefixes (like !) might be spammed naturally by users. Ignore such cases (i.e. '!!!!!!!').
        if (strlen($content) === 1 || $content[1] === Env::get('COMMAND_PREFIX')) {
            return;
        }

        // Handle the command
        $content = substr($content, 1);
        $arguments = explode(' ', $content);

        $commandName = $arguments[0];

        $logMessage = 'Executing a command: %s (%s)';
        $commandClass = CommandHelper::getClassName($commandName);

        if (class_exists($commandClass)) {
            $command = new Command($this->discord, $this->message, $this->logger, $arguments);
            new $commandClass($command);
        } else {
            $this->message->react('❌');
        }

        $this->logger->info(sprintf($logMessage, $commandName, implode(' ', $arguments)));
    }
}
