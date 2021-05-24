<?php

namespace App\Services;

class CleverbotService
{
    private array $history;
    private ?array $pipes;
    private $nodeProcess; // can't type hint because of https://github.com/php/php-src/pull/1631

    public function __construct()
    {
        $this->history = [];

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
    }

    public function __destruct() {
        fclose($this->pipes[0]);
        fclose($this->pipes[1]);
        fclose($this->pipes[2]);
        proc_close($this->nodeProcess);
    }

    public function makeRequest($message): null|false|string
    {
        $write_status = fwrite($this->pipes[0], $message);
        $flush_status = fflush($this->pipes[0]);

        if ($write_status && $flush_status) {
            $reply = fgets($this->pipes[1]);
            fflush($this->pipes[1]);
            var_dump($reply);

            if ($reply !== null && $reply !== false) {
                array_push($this->history, $message);
                array_push($this->history, $reply);

                // Limit the max history size
                if (count($this->history) > 60) {
                    $this->history = array_reverse($this->history);
                    array_pop($this->history);
                    array_pop($this->history);
                    $this->history = array_reverse($this->history);
                }
            }

            return $reply;
        } else {
            return false;
        }
    }
}