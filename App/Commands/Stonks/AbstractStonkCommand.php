<?php

namespace App\Commands\Stonks;

use App\Classes\AbstractCommand;
use App\Helpers\Env;
use App\Helpers\Tradier;
use App\Interfaces\CommandInterface;
use Exception;
use JetBrains\PhpStorm\Pure;

abstract class AbstractStonkCommand extends AbstractCommand implements CommandInterface
{
    /**
     * @return string
     */
    #[Pure] protected function tradierToken(): string
    {
        return Tradier::getKey($this, true);
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function validate(): bool
    {
        if (!$this->authorIsOwner() && false) {
            $this->react('🔐');
            return false;
        }

        if ($this->isPrivateMessage()) {
            $this->reply('neck yourself');
            return false;
        }

        if (!Env::get('TRADIER_ENVIRONMENT')) {
            $this->react('🛠️');
            return false;
        }

        if (!$this->tradierToken()) {
            $this->reply('Set your Tradier (https://tradier.com/products/market-data-api) API Key using `.userconfig`');
            return false;
        }

        return true;
    }
}
