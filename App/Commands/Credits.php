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
        if ($this->name === 'respekcija') {
            $this->executeRespekcija();
        } else {
            $this->executeCredits();
        }

    }

    public function executeRespekcija(): void
    {
        if ($this->isHaris()) {
            $this->reply('<@131877167549251584> ir 12 respekcija');
        } else {
            $this->reply('Tev ir 0 respekcija');
        }
    }

    public function executeCredits(): void
    {
        $this->reply('Tev ir 0 kredītu');
    }

    private function isHaris()
    {
        $haris = 131877167549251584;

        if ((int)$this->getAuthorId() === $haris) {
            return true;
        }

        if ($this->arguments[0] === '<@' . $haris . '>') {
            return true;
        }

        if ($this->arguments[0] === '<@!' . $haris . '>') {
            return true;
        }



        return false;
    }
}
