<?php

namespace App\Commands;

use App\Interfaces\CommandInterface;
use Discord\Discord;
use Discord\Parts\Channel\Message;
use Discord\Parts\Embed\Embed;

class Blackjack implements CommandInterface
{

    public function __construct(string $commandName, array $arguments, Message $message, Discord $discord)
    {
        $embed = new Embed($discord);
        $embed->setType(Embed::TYPE_RICH);
        $embed->setTitle('Blackjack');

        $message->channel->sendEmbed($embed);
    }
}
