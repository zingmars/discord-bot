<?php

namespace App\Classes;

use App\Interfaces\CommandInterface;
use Discord\Discord;
use Discord\Parts\Channel\Message;
use Discord\Parts\Embed\Embed;
use Exception;
use React\Promise\ExtendedPromiseInterface;

abstract class AbstractCommand implements CommandInterface
{
    protected Command $command;
    protected Message $message;
    protected Discord $discord;
    protected array $arguments;
    protected string $name;

    /**
     * @param Command $command
     * @throws Exception
     */
    public function __construct(Command $command)
    {
        $this->command = $command;
        $this->message = $command->message;
        $this->discord = $command->discord;
        $this->name = $command->commandName;
        $this->arguments = $command->arguments;

        if ($this->validate()) {
            $this->execute();
        }
    }

    /**
     * @throws Exception
     */
    public function execute(): void
    {
        $this->react('❌');
        $this->reply('not yet implemented');
    }

    /**
     * @param $icon
     * @return ExtendedPromiseInterface
     */
    protected function react($icon): ExtendedPromiseInterface
    {
        return $this->message->react($icon);
    }

    /**
     * @param $message
     * @return ExtendedPromiseInterface
     * @throws Exception
     */
    protected function reply($message): ExtendedPromiseInterface
    {
        return $this->message->reply($message);
    }

    /**
     * @param Embed $embed
     * @return ExtendedPromiseInterface
     * @throws Exception
     */
    protected function sendEmbed(Embed $embed): ExtendedPromiseInterface
    {
        return $this->message->channel->sendEmbed($embed);
    }
}
