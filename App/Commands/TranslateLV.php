<?php

namespace App\Commands;

use App\Classes\AbstractCommand;
use App\Helpers\Env;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Exception;

class TranslateLV extends AbstractCommand
{
    /**
     * @return bool
     */
    public function validate(): bool
    {
        if (empty($this->arguments)) {
            $reply = 'syntax: %s%s [query]';
            $this->reply(sprintf($reply, Env::Get('COMMAND_PREFIX'), $this->name));
            $this->react('❌');
            return false;
        }
        return true;
    }

    /**
     * @throws Exception
     */
    public function execute(): void
    {
        $tr = new GoogleTranslate('lv');
        $tr->setSource();
        $this->reply($tr->translate(implode(' ', $this->arguments)));
    }
}
