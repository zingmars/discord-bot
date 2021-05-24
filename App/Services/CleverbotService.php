<?php

namespace App\Services;

class CleverbotService
{
    private ?array $pipes;
    private $nodeProcess; // can't type hint because of https://github.com/php/php-src/pull/1631

    public function __construct()
    {
        // Look dude, the web API gets cucked so often I really can't be arsed to maintain this crap when there's someone
        // else who already does it. And he does it for free :)
        $descriptorSpec = array(
            0 => array("pipe", "r"),  // stdin
            1 => array("pipe", "w"),  // stdout
            2 => array("pipe", "w") // stderr
        );

        $this->nodeProcess = proc_open('node Lib/cleverbot.js', $descriptorSpec, $this->pipes);
        if ($this->nodeProcess === false && !is_resource($this->nodeProcess)) {
            die("Could not initialise cleverbot service");
        }

        // Testing
        //fwrite($this->pipes[0], "Hello");
        //Log::console("Initialising cleverbot. Initial response:" . fgets($this->pipes[1]));
    }

    public function __destruct() {
        fclose($this->pipes[0]);
        fclose($this->pipes[1]);
        fclose($this->pipes[2]);
        proc_close($this->nodeProcess);
    }

    public function makeRequest($message): false|string
    {
        if (fwrite($this->pipes[0], $message)) {
            return fgets($this->pipes[1]);
        }

        return false;
    }
}