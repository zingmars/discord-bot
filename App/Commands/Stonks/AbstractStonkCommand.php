<?php

namespace App\Commands\Stonks;

use App\Classes\AbstractCommand;
use App\Helpers\Env;
use App\Interfaces\CommandInterface;

abstract class AbstractStonkCommand extends AbstractCommand implements CommandInterface
{
    /**
     * @return bool
     */
    public function validate(): bool
    {
        if (!$this->authorIsOwner()) {
            $this->react('🔐');
            return false;
        }

        if (!Env::get('TRADIER_TOKEN') || !Env::get('TRADIER_ENVIRONMENT')) {
            $this->react('⚒️');
            return false;
        }

        return true;
    }
}
