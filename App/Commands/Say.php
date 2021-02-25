<?php

namespace App\Commands;

use App\Classes\AbstractCommand;
use App\Helpers\Env;
use App\Interfaces\CommandInterface;
use Exception;

class Say extends AbstractCommand implements CommandInterface
{

    /**
     * @return bool
     */
    public function validate(): bool
    {
        if ($this->message->author->id === Env::get('BOT_OWNER_USER_ID')) {
            return true;
        }

        $this->react('❌');
        return false;
    }

    /**
     * @throws Exception
     */
    public function execute(): void
    {
        $message = implode(' ', $this->arguments);
        $this->channelMessage($message);
        $this->message->delete();
    }
}
