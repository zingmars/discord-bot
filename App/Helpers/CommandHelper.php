<?php

namespace App\Helpers;

use JetBrains\PhpStorm\Pure;

class CommandHelper
{
    private const COMMAND_NAMESPACE = '\\App\\Commands\\';
    private const ALIASES = [
        'bj' => 'blackjack',
        '8ball' => 'eightball',
    ];

    /**
     * @param string $command
     * @return string
     */
    #[Pure] public static function getClassName(string $command): string
    {
        $command = strtolower($command);

        if (isset(self::ALIASES[$command])) {
            $command = self::ALIASES[$command];
        }

        $command = ucfirst(strtolower($command));

        return self::COMMAND_NAMESPACE . $command;
    }
}
