<?php

namespace App\Commands\Stonks;

use App\Classes\AbstractCommand;
use App\Interfaces\CommandInterface;
use Discord\Parts\Embed\Embed;
use Exception;
use SJohnson\MarketData\Classes\Ticker;
use SJohnson\MarketData\Objects\Option;
use SJohnson\MarketData\Objects\OptionChain;
use SJohnson\MarketData\Objects\OptionDates;

class Options extends AbstractCommand implements CommandInterface
{
    private const ENCOURAGING_MESSAGES = [
        'It literally cannot go tits up',
        'YOLO TSLA $1337 Calls EXPIRING RIGHT FUCKING NOW',
        'I like the stock.',
        'You should look at `.options SPY Call 2021-04-16 420`',
        'There\'s a non-zero chance that you might get cucked by this position',
        'HODL!',
        'Send it, motherfucker',
    ];

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
        } else {
            $this->reply('Syntax: ' . $this->name . ' [ticker] [side (call/put)] [expiry i.e. 2021-04-16] [strike]');
            return;
        }

        // Set side. If no side - error message
        if (isset($this->arguments[1])) {
            $side = strtolower($this->arguments[1]);
        } else {
            $this->reply('Syntax: ' . $this->name . ' [ticker] [side (call/put)] [expiry i.e. 2021-04-16] [strike]');
            return;
        }

        // Set expiry date. If no expiry date - show a list of available expiry dates
        if (isset($this->arguments[2])) {
            $expiry = $this->arguments[2];
        } else {
            $embed = $this->optionDateEmbed($ticker, $ticker->getOptionsDates());
            $this->reply('Here are the option dates for ' . $ticker->ticker, $embed);
            return;
        }

        // Set strike price & show option card. If no strike - show a list of available strikes for the specified expiry date
        if (isset($this->arguments[3])) {
            $strike = $this->arguments[3];

            $chain = $ticker->getOptionsChain($expiry);
            $embed = $this->optionCardEmbed($chain, $side, $strike);
            $this->reply($this->getRandomEncouragingMessage(), $embed);
        } else {
            $chain = $ticker->getOptionsChain($expiry);
            $embed = $this->optionChainEmbed($ticker, $chain, $expiry, $side);
            $reply = 'Strike prices for %s %ss expiring on %s';
            $this->reply(sprintf($reply, $ticker->ticker, $side, $expiry), $embed);
        }
    }

    /**
     * @param OptionChain $optionChain
     * @param string $side
     * @param float $strike
     * @return Embed
     */
    private function optionCardEmbed(OptionChain $optionChain, string $side, float $strike): Embed
    {
        $embed = new Embed($this->discord);

        /** @var Option $option */
        foreach ($optionChain->$side as $option) {
            if ($option->strike === $strike) {
                $embed->setTitle($option->description);
                $description = 'Symbol: ' . $option->symbol . PHP_EOL;
                $description .= 'Bid: $' . (float)$option->bid . PHP_EOL;
                $description .= 'Ask: $' . (float)$option->ask . PHP_EOL;
                $description .= 'Volume: ' . $option->volume . PHP_EOL;
                $description .= 'Open Interest: ' . $option->openInterest . PHP_EOL;
                $embed->setDescription($description);

                break;
            }
        }

        return $embed;
    }

    /**
     * @param Ticker $ticker
     * @param OptionChain $optionChain
     * @param string $expiry
     * @param string $side
     * @return Embed
     */
    private function optionChainEmbed(Ticker $ticker, OptionChain $optionChain, string $expiry, string $side): Embed
    {
        $embed = new Embed($this->discord);
        $embed->setTitle($ticker->ticker . ' ' . ucfirst($side) . ' Option Strike Prices');
        $description = '';

        foreach ($optionChain->$side as $option) {
            $description .= '$' . $option->strike . ' ';
        }

        $embed->setDescription($description);
        return $embed;
    }

    /**
     * @param Ticker $ticker
     * @param OptionDates $optionDates
     * @return Embed
     */
    private function optionDateEmbed(Ticker $ticker, OptionDates $optionDates): Embed
    {
        $embed = new Embed($this->discord);
        $embed->setTitle($ticker->ticker . ' Option Dates');
        $description = '';

        foreach ($optionDates as $optionDate => $humanReadable) {
            $description .= $optionDate . PHP_EOL;
        }

        $embed->setDescription($description);

        return $embed;
    }

    /**
     * @return string
     */
    private function getRandomEncouragingMessage(): string
    {
        return self::ENCOURAGING_MESSAGES[rand(0, count(self::ENCOURAGING_MESSAGES)) - 1];
    }
}
