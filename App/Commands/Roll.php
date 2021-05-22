<?php

namespace App\Commands;

use App\Classes\AbstractCommand;
use Exception;

class Roll extends AbstractCommand
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
        if (isset($this->arguments[0])) {
            $dice = $this->arguments[0];
            if (!is_numeric($dice)) {
                $this->reply("Number of dice must be numeric");
                $this->react('❌');
                return;
            }
        } else {
            $dice = 1;
        }

        if (isset($this->arguments[1])) {
            $sides = $this->arguments[1];
            if (!is_numeric($sides)) {
                $this->reply("Number of sides must be numeric");
                $this->react('❌');
                return;
            }
        } else {
            $sides = 6;
        }

        $result = 'Dice rolls:';
        foreach(range(1, $dice) as $number) {
            $result .= ' ' . random_int(1, $sides);
        }

        $this->reply($result);
    }
}
