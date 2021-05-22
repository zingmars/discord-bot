<?php

namespace App\Helpers;

use App\Commands\EightBall;
use App\Commands\Help;
use App\Commands\Ping;
use App\Commands\Roll;
use App\Commands\Say;
use App\Commands\Fact;
use App\Commands\Weather;
use JetBrains\PhpStorm\Pure;

class CommandHelper
{
    private const COMMANDS = [
        'ping' => Ping::class,
        '8ball' => EightBall::class,
        'say' => Say::class,
        'help' => Help::class,
        'fact' => Fact::class,
        'tfw' => Weather::class,
        'weather' => Weather::class,
        'roll' => Roll::class,
    ];

    public const COMMANDS_DESCRIPTIONS = [
        Help::class => [
            'command' => 'help',
            'shortDescription' => 'Help',
            'longDescription' => 'List of commands',
            'syntax' => '',
        ],
        EightBall::class => [
            'command' => '8ball',
            'shortDescription' => '8 Ball',
            'longDescription' => 'Shakes a 8ball and returns a response',
            'syntax' => '',
        ],
        Ping::class => [
            'command' => 'ping',
            'shortDescription' => 'Ping',
            'longDescription' => 'Returns a message to verify that the bot is indeed a live',
            'syntax' => '',
        ],
        Fact::class => [
            'command' => 'fact',
            'shortDescription' => 'Fact',
            'longDescription' => 'Returns a random fact',
            'syntax' => '',
        ],
        Weather::class => [
            'command' => 'weather',
            'shortDescription' => 'Weather',
            'longDescription' => 'Checks weather in a given location',
            'syntax' => '[location]',
        ],
        Roll::class => [
            'command' => 'roll',
            'shortDescription' => 'Roll',
            'longDescription' => 'Rolls dice. Optionally the amount of dice and the amount of sides each dice has can be specified.',
            'syntax' => '[dice = 1] [sides = 6]',
        ],
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
