<?php

namespace App\Commands;

use App\Classes\AbstractCommand;
use App\Interfaces\CommandInterface;
use Exception;

class Credits extends AbstractCommand implements CommandInterface
{
    /**
     * @return bool
     */
    public function validate(): bool
    {
        return true;
    }

    /**
     * @throws Exception
     */
    public function execute(): void
    {
        $currencyName = 'kredītu';

        if ($this->name === 'respekcija') {
            $currencyName = 'respekcija';
        }

        $this->reply('Tev ir 0 ' . $currencyName);
    }
}
