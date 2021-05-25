<?php

namespace App\Helpers;

use App\Commands\ChristianInsult;
use App\Commands\DadJoke;
use App\Commands\Dictionary;
use App\Commands\EightBall;
use App\Commands\FindIp;
use App\Commands\FindPhone;
use App\Commands\Help;
use App\Commands\Lenny;
use App\Commands\LocateIp;
use App\Commands\Ping;
use App\Commands\ReallyFuckingLove;
use App\Commands\Roll;
use App\Commands\Say;
use App\Commands\Fact;
use App\Commands\SpamGL;
use App\Commands\SpamGLCount;
use App\Commands\Weather;
use App\Commands\WolframAlpha;
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
        'findip' => FindIp::class,
        'findphone' => FindPhone::class,
        'insult' => ChristianInsult::class,
        'joke' => DadJoke::class,
        'wa' => WolframAlpha::class,
        'locateip' => LocateIp::class,
        'define' => Dictionary::class,

        // These commands don't have help entries
        'rfl' => ReallyFuckingLove::class,
        'lenny' => Lenny::class,
        'spamgl' => SpamGL::class,
        'spamcount' => SpamGLCount::class,
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
            'longDescription' => 'Rolls dice. Optionally the amount of dice and the amount of sides each dice has can be specified',
            'syntax' => '[dice = 1] [sides = 6]',
        ],
        FindIp::class => [
            'command' => 'findip',
            'shortDescription' => 'Find ip address',
            'longDescription' => 'Looks up a given ip address',
            'syntax' => '[ip address]',
        ],
        LocateIp::class => [
            'command' => 'locateip',
            'shortDescription' => 'Find ip address',
            'longDescription' => 'Looks up a given ip address using Shodan. Data might not be available for all IPs!',
            'syntax' => '[ip address]',
        ],
        FindPhone::class => [
            'command' => 'findphone',
            'shortDescription' => 'Lookup phone number\'s caller id',
            'longDescription' => 'Looks up a phone number to see if they have a caller id',
            'syntax' => '[phone number]',
        ],
        ChristianInsult::class => [
            'command' => 'insult',
            'shortDescription' => 'Insult someone',
            'longDescription' => 'Insult someone',
            'syntax' => '[user tag]',
        ],
        DadJoke::class => [
            'command' => 'joke',
            'shortDescription' => 'Get a dad joke',
            'longDescription' => 'Get a random (dad) joke',
            'syntax' => '',
        ],
        WolframAlpha::class => [
            'command' => 'wa',
            'shortDescription' => 'Execute a query against WolframAlpha',
            'longDescription' => 'Run query on WolframAlpha. Please note that this command might take a while to output because WolframAlpha is quite slow.',
            'syntax' => '[query]',
        ],
        Dictionary::class => [
            'command' => 'define',
            'shortDescription' => 'Define a word',
            'longDescription' => 'Get a definition of a word',
            'syntax' => '[word] [language=en_US|en_GB|hi|es|fr|ja|ru|de|it|ko|pt-BR|ar|tr]',
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
