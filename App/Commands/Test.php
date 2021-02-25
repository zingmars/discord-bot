<?php

namespace App\Commands;

use App\Classes\AbstractCommand;
use Discord\Parts\Channel\Message;
use Discord\Discord;
use Exception;

class Test extends AbstractCommand
{
    /**
     * @param string $commandName
     * @param array $arguments
     * @param Message $message
     * @param Discord $discord
     * @throws Exception
     */
    public function __construct(string $commandName, array $arguments, Message $message, Discord $discord)
    {
        $reply = 'Invoked Command %s (%s). Arguments: %s';
        $reply = sprintf($reply,'test', self::class, implode(' ', $arguments));
        $message->reply($reply);
    }
}
