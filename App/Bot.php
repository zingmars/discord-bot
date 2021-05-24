<?php

namespace App;

use App\Listeners\Message;
use App\Services\CleverbotService;
use Discord\Discord;

class Bot
{
    public function __construct(Discord $discord, CleverbotService $cleverbotService)
    {
        $messageListener = function ($message) use ($discord, $cleverbotService) {
            new Message($discord, $cleverbotService, $message);
        };

        $discord->on('message', $messageListener);
    }
}
