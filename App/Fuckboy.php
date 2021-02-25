<?php

namespace App;

use App\Listeners\Message;
use Discord\Discord;

class Fuckboy
{
    public function __construct(Discord $discord)
    {
        $messageListener = function ($message) use ($discord) {
            new Message($discord, $message);
        };

        $discord->on('message', $messageListener);
    }
}
