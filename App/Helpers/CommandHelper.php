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
use App\Commands\Locate;
use App\Commands\LocateIp;
use App\Commands\Ping;
use App\Commands\ReallyFuckingLove;
use App\Commands\Roll;
use App\Commands\Say;
use App\Commands\Fact;
use App\Commands\SpamGL;
use App\Commands\SpamGLCount;
use App\Commands\Time;
use App\Commands\Translate;
use App\Commands\TranslateLV;
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
        'translate' => Translate::class,
        'translatelv' => TranslateLV::class,
        'time' => Time::class,
        'locate' => Locate::class,

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
            'longDescription' => 'List of commands.',
            'syntax' => '',
        ],
        Ping::class => [
            'command' => 'ping',
            'shortDescription' => 'Ping',
            'longDescription' => 'Returns a message to verify that the bot is indeed alive.',
            'syntax' => '',
        ],
        EightBall::class => [
            'command' => '8ball',
            'shortDescription' => '8 Ball',
            'longDescription' => 'Shakes a 8ball and returns a response.',
            'syntax' => '',
        ],
        Fact::class => [
            'command' => 'fact',
            'shortDescription' => 'Fact',
            'longDescription' => 'Returns a random fact.',
            'syntax' => '',
        ],
        Weather::class => [
            'command' => 'weather',
            'shortDescription' => 'Weather',
            'longDescription' => 'Checks weather in a given location.',
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
            'longDescription' => 'Looks up a given ip address and returns information about it.',
            'syntax' => '[ip address]',
        ],
        LocateIp::class => [
            'command' => 'locateip',
            'shortDescription' => 'Find ip address',
            'longDescription' => 'Looks up a given ip address using Shodan. Data only available for IPs scanned by Shodan, so it might not be available your ip.',
            'syntax' => '[ip address]',
        ],
        FindPhone::class => [
            'command' => 'findphone',
            'shortDescription' => 'Lookup phone number\'s caller id',
            'longDescription' => 'Looks up a phone number to see if they have a caller id.',
            'syntax' => '[phone number]',
        ],
        ChristianInsult::class => [
            'command' => 'insult',
            'shortDescription' => 'Insult someone',
            'longDescription' => 'Insult someone in a very Christian manner.',
            'syntax' => '[user tag]',
        ],
        DadJoke::class => [
            'command' => 'joke',
            'shortDescription' => 'Get a dad joke',
            'longDescription' => 'Get a random (dad) joke.',
            'syntax' => '',
        ],
        WolframAlpha::class => [
            'command' => 'wa',
            'shortDescription' => 'Execute a query against WolframAlpha',
            'longDescription' => 'Run query on WolframAlpha (i.e. math, time, scientific queries). Please note that this command might take a while to output because WolframAlpha is quite slow.',
            'syntax' => '[query]',
        ],
        Dictionary::class => [
            'command' => 'define',
            'shortDescription' => 'Define a word',
            'longDescription' => 'Get a definition of a word. Language is optional if the word isn\'t in English. Note that returned results are in the same language as the word itself.',
            'syntax' => '[word] [language=en_US|en_GB|hi|es|fr|ja|ru|de|it|ko|pt-BR|ar|tr]',
        ],
        Translate::class => [
            'command' => 'translate[lv]',
            'shortDescription' => 'Translate a query',
            'longDescription' => 'Translates text using Google Translate. Might not be 100% accurate, but what can you do. Might not even work because Google has banned my IP. Oops! Use !translatelv to translate to Latvian.',
            'syntax' => '[query]',
        ],
        Time::class => [
            'command' => 'time',
            'shortDescription' => 'Returns time in a location',
            'longDescription' => 'Returns time in a given country or a city. Note that the location needs to have an entry in a TZ database, so smaller towns won\'t work. For those usage of the WolframAlpha command is recommended.',
            'syntax' => '[location]',
        ],
        Locate::class => [
            'command' => 'locate',
            'shortDescription' => 'Attempts to return basic information about a place',
            'longDescription' => 'Looks up and tries to return some basic information about a given location.',
            'syntax' => '[location]',
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
