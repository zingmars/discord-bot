<?php

namespace App\Commands;

use App\Classes\AbstractCommand;
use App\Helpers\Env;
use App\Helpers\FileStore;
use Exception;

class SpamGL extends AbstractCommand
{
    /**
     * @return bool
     */
    public function validate(): bool
    {
        if ($this->authorIsOwner() && !empty($this->arguments) && is_numeric($this->arguments[0])) {
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
        $this->message->delete();

        $targetCount = $this->arguments[0];
        $target = Env::Get('SPAM_TARGET_USER_ID');

        $spamCount = (int)FileStore::Get(Env::Get('SPAM_TARGET_FILE_NAME'));
        if ($spamCount == false) {
            $spamCount = 0;
        }

        foreach(range(1, $targetCount) as $number) {
            $this->channelMessage('Good luck <@!' . $target . '>!');
            $spamCount += 1;

            sleep(1);
        }

        FileStore::Set(Env::Get('SPAM_TARGET_FILE_NAME'), $spamCount);
    }
}
