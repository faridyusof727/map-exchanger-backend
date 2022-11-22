<?php

namespace Models;

use Contracts\CurrencyInterface;

class Currency implements CurrencyInterface
{

    private string $symbol;
    private float $baseAmount;
    private array $toConvertSymbols;

    public function setFromCurrency(string $symbol, float $baseAmount = 1): void
    {
        $this->symbol     = $symbol;
        $this->baseAmount = $baseAmount;
    }

    public function getFromCurrency(): string
    {
        return $this->symbol;
    }

    public function getFromCurrencyBase(): float
    {
        return $this->baseAmount;
    }

    public function setToCurrencies(array $symbols): void
    {
        $this->toConvertSymbols = $symbols;
    }

    public function getToCurrencies(): array
    {
        return $this->toConvertSymbols;
    }
}
