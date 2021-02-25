<?php

namespace App\Commands;

use App\Classes\Command;
use Discord\Discord;
use Discord\Parts\Channel\Message;
use App\Classes\AbstractCommand;
use App\Interfaces\CommandInterface;

class NSFW extends AbstractCommand implements CommandInterface
{
    /**
     * @return bool
     */
    public function validate(): bool
    {
        return true;
    }
}
