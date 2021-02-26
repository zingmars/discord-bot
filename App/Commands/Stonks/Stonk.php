<?php

namespace App\Commands\Stonks;

use App\Classes\AbstractCommand;
use App\Interfaces\CommandInterface;
use Discord\Parts\Embed\Embed;
use Exception;
use SJohnson\MarketData\Classes\Ticker;

class Stonk extends AbstractCommand implements CommandInterface
{
    /**
     * @return bool
     */
    public function validate(): bool
    {
        if (!$this->authorIsOwner()) {
            $this->react('🔐');
            return false;
        }

        return true;
    }

    /**
     * @throws Exception
     */
    public function execute(): void
    {
        // Set ticker. If no ticker - error message
        if (isset($this->arguments[0])) {
            $ticker = new Ticker($this->arguments[0]);
            $embed = $this->tickerEmbed($ticker);
            $this->reply('Heres ur stonk fam.', $embed);
        } else {
            $this->reply('Syntax: ' . $this->name . ' [ticker]');
            return;
        }

    }

    /**
     * @param Ticker $ticker
     * @return Embed
     */
    private function tickerEmbed(Ticker $ticker): Embed
    {
        $fundamentals = $ticker->getFundamentals();

        $embed = new Embed($this->discord);
        $embed->setTitle($fundamentals->description);
        $description = 'Symbol: ' . $fundamentals->symbol . PHP_EOL;
        $description .= 'Last Price: $' . $fundamentals->last . PHP_EOL;
        $description .= 'Open: $' . $fundamentals->open . '      Close: $' . $fundamentals->close . PHP_EOL;
        $description .= 'Low: $' . $fundamentals->low . '      High: $' . $fundamentals->high . PHP_EOL;
        $description .= '52wk Low: $' . $fundamentals->yearlow . '      52wk High: $' . $fundamentals->yearhigh . PHP_EOL;
        $description .= 'Volume: ' . $fundamentals->volume . '      Average: ' . $fundamentals->averageVolume . PHP_EOL;

        $embed->setDescription($description);

        return $embed;
    }
}
