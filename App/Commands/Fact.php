<?php

namespace App\Commands;

use App\Classes\AbstractCommand;
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
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "http://randomfunfacts.com");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);

        preg_match('/<strong><i>(.*?)<\/i><\/strong>/s', $output, $result);

        $this->reply($result[1]);
    }
}
