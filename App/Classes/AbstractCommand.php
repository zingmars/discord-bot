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
    public Command $command;
    public Message $message;
    public Discord $discord;
    public array $arguments;
    public string $name;

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
            try {
                $this->execute();
            } catch (Exception $e) {
                $this->react('🛠️');
            }
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
        $channel = $this->message->channel;
        $guildId = null;

        if ($channel->guild) {
            $guildId = $channel->guild->id;
        }

        $reference = [
            'message_id' => $this->message->id,
            'channel_id' => $channel->id,
            'guild_id' => $guildId,
            'fail_if_not_exists' => false,
        ];

        return $this->message->channel->sendMessage($message, false, $embed, false, $reference);
    }

    /**
     * @param string $message
     * @param Embed|null $embed
     * @return ExtendedPromiseInterface
     * @throws Exception
     */
    protected function sendDM(string $message, ?Embed $embed = null): ExtendedPromiseInterface
    {
        return $this->message->author->user->sendMessage($message, false, $embed);
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

    /**
     * @return bool
     */
    #[Pure] protected function isPrivateMessage(): bool
    {
        return $this->message->channel->is_private;
    }

    /**
     * @return int
     */
    protected function getAuthorId(): int
    {
        return $this->message->author->id;
    }
}
