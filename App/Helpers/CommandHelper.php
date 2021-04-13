<?php

namespace App\Helpers;

use App\Commands\Blackjack;
use App\Commands\Credits;
use App\Commands\DirectMessage;
use App\Commands\EightBall;
use App\Commands\Help;
use App\Commands\NSFW;
use App\Commands\Say;
use App\Commands\Stonks\Stonk;
use App\Commands\Test;
use App\Commands\Tinker;
use App\Commands\UserConfig;
use JetBrains\PhpStorm\Pure;
use App\Commands\Stonks\Options;

class CommandHelper
{
    private const COMMANDS = [
        'bj' => Blackjack::class,
        'blackjack' => Blackjack::class,
        'dm' => DirectMessage::class,
        'directmessage' => DirectMessage::class,
        'nsfw' => NSFW::class,
        'test' => Test::class,
        'credits' => Credits::class,
        'respekcija' => Credits::class,
        '8ball' => EightBall::class,
        'say' => Say::class,
        'option' => Options::class,
        'options' => Options::class,
        'stonk' => Stonk::class,
        'userconfig' => UserConfig::class,
        'help' => Help::class,
        'tinker' => Tinker::class,
        'eval' => Tinker::class,
    ];

    public const COMMANDS_DESCRIPTIONS = [
        Help::class => [
            'command' => 'help',
            'shortDescription' => 'Help',
            'longDescription' => 'Literally how you got to this message, you braindead degenerate',
            'syntax' => '',
        ],
        Blackjack::class => [
            'command' => 'bj',
            'shortDescription' => 'Blackjack',
            'longDescription' => 'Dīleris sev izdala 21 un appiš Tevi kā pilnīgāko nabagu',
            'syntax' => '[bet size]',
        ],
        NSFW::class => [
            'command' => 'nsfw',
            'shortDescription' => 'NSFW',
            'longDescription' => 'daunis esi?',
            'syntax' => '',
        ],
        Credits::class => [
            'command' => 'credits',
            'shortDescription' => 'Kredīti',
            'longDescription' => 'Kredīti',
            'syntax' => '',
        ],
        Stonk::class => [
            'command' => 'stonk',
            'shortDescription' => 'Stonk',
            'longDescription' => 'Looks up a stonk',
            'syntax' => '[ticker]',
        ],
        Options::class => [
            'command' => 'options',
            'shortDescription' => 'Options',
            'longDescription' => 'Looks up options',
            'syntax' => '[ticker] [call/put] [expiry yyyy-mm-dd] [strike]',
        ],
        UserConfig::class => [
            'command' => 'userconfig',
            'shortDescription' => 'User Config',
            'longDescription' => 'User configuration options',
            'syntax' => '[get/set] [key] [?value]',
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
