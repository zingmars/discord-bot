<?php

namespace App\Commands;

use App\Classes\AbstractCommand;
use App\Helpers\Curl;
use Exception;

class DadJoke extends AbstractCommand
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
        $url = "https://icanhazdadjoke.com/";
        $output = Curl::GetJson($url);
        $result = json_decode($output);

        if ($result->status !== 200) {
            $this->reply("No Jok.");
        } else {
            $this->reply($result->joke);
        }
    }
}
