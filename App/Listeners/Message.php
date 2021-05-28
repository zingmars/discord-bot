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

        $logMessage = '[%s] Received a message from %s#%s: %s';
        Log::console(sprintf($logMessage, date("Y-m-d H:i:s T"), $this->message->author->username, $this->message->author->discriminator, $message->content));

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

        // Cleverbot (only if enabled and bot was directly mentioned)
        if (Env::get('ENABLE_CLEVERBOT') === "True") {
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

        $logMessage = '[%s] Executing a command: %s (%s)';
        $commandClass = CommandHelper::getClassName($commandName);

        if (class_exists($commandClass)) {
            $command = new Command($this->discord, $this->message, $arguments);
            new $commandClass($command);
        } else {
            $this->message->react('❌');
        }

        Log::console(sprintf($logMessage, date("Y-m-d H:i:s T"), $commandName, implode(' ', $arguments)));
    }
}
