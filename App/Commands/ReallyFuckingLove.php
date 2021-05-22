<?php

namespace App\Commands;

use App\Classes\AbstractCommand;
use App\Helpers\Env;
use App\Interfaces\CommandInterface;
use Exception;

class ReallyFuckingLove extends AbstractCommand implements CommandInterface
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
        $message = implode(' ', $this->arguments);
        $this->channelMessage('I really fucking love ' . $message);
    }
}
