<?php

namespace App\Classes;

use App\Helpers\Env;
use App\Interfaces\CommandInterface;
use Discord\Discord;
use Discord\Parts\Channel\Message;
use Discord\Parts\Embed\Embed;
use Exception;
use JetBrains\PhpStorm\Pure;
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
     * @param string $icon
     * @return ExtendedPromiseInterface
     */
    protected function react(string $icon): ExtendedPromiseInterface
    {
        return $this->message->react($icon);
    }

    /**
     * @param string $message
     * @param Embed|null $embed
     * @return ExtendedPromiseInterface
     * @throws Exception
     */
    protected function reply(string $message, ?Embed $embed = null): ExtendedPromiseInterface
    {
        $reference = [
            'message_id' => $this->message->id,
            'channel_id' => $this->message->channel->id,
            'guild_id' => $this->message->channel->guild->id,
            'fail_if_not_exists' => false,
        ];

        return $this->message->channel->sendMessage($message, false, $embed, false, $reference);
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

    /**
     * @param string $message
     * @param Embed|null $embed
     * @return ExtendedPromiseInterface
     * @throws Exception
     */
    protected function channelMessage(string $message, ?Embed $embed = null): ExtendedPromiseInterface
    {
        return $this->message->channel->sendMessage($message, false, $embed);
    }

    /**
     * @return bool
     */
    #[Pure] protected function authorIsOwner(): bool
    {
        if ($this->message->author->id === Env::get('BOT_OWNER_USER_ID')) {
            return true;
        }

        return false;
    }
}
