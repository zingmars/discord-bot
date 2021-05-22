<?php

namespace App;

use App\Listeners\Message;
use App\Services\CleverbotService;
use Discord\Discord;

class Fuckboy
{
    public function __construct(Discord $discord, CleverbotService $cleverbotService)
    {
        $messageListener = function ($message) use ($discord, $cleverbotService) {
            new Message($discord, $cleverbotService, $message);
        };

        $discord->on('message', $messageListener);
    }
}
