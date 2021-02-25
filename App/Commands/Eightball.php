<?php

namespace App\Commands;

use App\Helpers\Env;
use App\Interfaces\CommandInterface;
use Discord\Discord;
use Discord\Parts\Channel\Message;
use Discord\Parts\Guild\Guild;

class Eightball implements CommandInterface
{
    private array $responses = [
        [
            'tu jau zini gang',
            'jā',
            'nemīz',
            'jā, bet fonā mirgos',
        ],
        [
            'daunis esi?',
            'nē',
            'labāk sūkā dirsu',
            'labāk palaizi oliņas',
        ],
        [
            'nez',
            '<:nez:810903050704912414>',
            'jāprasa <@131877167549251584>',
            'jāprasa <@221755442513051649>',
        ]
    ];

    public function __construct(string $commandName, array $arguments, Message $message, Discord $discord)
    {
        $responseType = rand(0, count($this->responses) - 1);
        $responseKey = rand(0, count($this->responses[$responseType]) - 1);

        $response = $this->responses[$responseType][$responseKey];

        $message->reply($response);
    }
}
