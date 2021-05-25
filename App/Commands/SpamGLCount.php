<?php

namespace App\Commands;

use App\Classes\AbstractCommand;
use App\Helpers\Env;
use App\Helpers\FileStore;
use Exception;

class SpamGLCount extends AbstractCommand
{
    /**
     * @return bool
     */
    public function validate(): bool
    {
        if ($this->authorIsOwner()) {
            return true;
        }

        $this->react('🔐');
        return false;
    }

    /**
     * @throws Exception
     */
    public function execute(): void
    {
        $spamCount = (int)FileStore::Get(Env::Get('SPAM_TARGET_FILE_NAME'));
        if ($spamCount == false) {
            $this->reply("Plugin has not bee initialised yet");
            return;
        }
        $this->channelMessage('Good luck has been spammed ' . $spamCount . ' times.');
    }
}
