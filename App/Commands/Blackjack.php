<?php

namespace App\Commands;

use App\Classes\AbstractCommand;
use App\Interfaces\CommandInterface;
use Discord\Parts\Embed\Embed;
use Exception;

class Blackjack extends AbstractCommand implements CommandInterface
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
        $embed = new Embed($this->discord);
        $embed->setType(Embed::TYPE_RICH);
        $embed->setTitle('Blackjack');
        $embed->setDescription(
            'Dealer: ♠️ 10 ♠️ A
        You: ♥️ 3 ♠️ 1 
        
        Dealer blackjack. Uzsūc silto.'
        );

        $this->sendEmbed($embed);
    }
}
