<?php

namespace App\Helpers;

use App\Commands\Blackjack;
use App\Commands\DirectMessage;
use App\Commands\NSFW;
use App\Commands\Test;
use JetBrains\PhpStorm\Pure;

class CommandHelper
{
    private const COMMANDS = [
        'bj' => Blackjack::class,
        'blackjack' => Blackjack::class,
        'dm' => DirectMessage::class,
        'directmessage' => DirectMessage::class,
        'nsfw' => NSFW::class,
        'test' => Test::class,
    ];

    /**
     * @param string $command
     * @return string
     */
    #[Pure] public static function getClassName(string $command): string
    {
        $command = strtolower($command);

        if (isset(self::COMMANDS[$command])) {
            return self::COMMANDS[$command];
        }

        return '';
    }
}
