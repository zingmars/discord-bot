<?php

namespace App\Commands;

use App\Classes\AbstractCommand;
use App\Helpers\Curl;
use Exception;

class Fact extends AbstractCommand
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
        $output = Curl::Get("http://randomfunfacts.com");

        preg_match('/<strong><i>(.*?)<\/i><\/strong>/s', $output, $result);

        $this->reply($result[1]);
    }
}
