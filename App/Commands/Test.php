<?php

namespace App\Commands;

use App\Classes\AbstractCommand;
use App\Helpers\Env;
use Exception;

class Test extends AbstractCommand
{
    /**
     * @return bool
     */
    public function validate(): bool
    {
        return $this->authorIsOwner();
    }

    /**
     * @throws Exception
     */
    public function execute(): void
    {
        $message = $this->message;
        $arguments = $this->arguments;

        $reply = 'Invoked Command %s (%s). Arguments: %s';
        $reply = sprintf($reply, 'test', self::class, implode(' ', $arguments));
        $message->reply($reply);
    }
}
