<?php

namespace App\Commands\Stonks;

use App\Interfaces\CommandInterface;
use Discord\Parts\Embed\Embed;
use Exception;
use SJohnson\MarketData\Classes\Ticker;

class Stonk extends AbstractStonkCommand implements CommandInterface
{
    /**
     * @return bool
     */
    public function validate(): bool
    {
        return parent::validate();
    }

    /**
     * @throws Exception
     */
    public function execute(): void
    {
        // Set ticker. If no ticker - error message
        if (isset($this->arguments[0])) {
            $ticker = new Ticker($this->arguments[0], $this->tradierToken());
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
        $embed->setTitle((string)$fundamentals->description);
        $description = 'Symbol: ' . $fundamentals->symbol . PHP_EOL;
        $description .= 'Last Price: $' . (float)$fundamentals->last . PHP_EOL;
        $description .= 'Open: $' . (float)$fundamentals->open . '      Close: $' . (float)$fundamentals->close . PHP_EOL;
        $description .= 'Low: $' . (float)$fundamentals->low . '      High: $' . (float)$fundamentals->high . PHP_EOL;
        $description .= '52wk Low: $' . (float)$fundamentals->yearlow . '      52wk High: $' . (float)$fundamentals->yearhigh . PHP_EOL;
        $description .= 'Volume: ' . (int)$fundamentals->volume . '      Average: ' . (int)$fundamentals->averageVolume . PHP_EOL;

        $embed->setDescription($description);

        return $embed;
    }
}
