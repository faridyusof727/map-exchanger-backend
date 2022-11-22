<?php

namespace Contracts;

interface CurrencyInterface
{
    public function setFromCurrency(string $symbol, float $baseAmount = 1): void;

    public function getFromCurrency(): string;

    public function getFromCurrencyBase(): float;

    /**
     * @param array<string> $symbols
     * @return void
     */
    public function setToCurrencies(array $symbols): void;

    /**
     * @return array<string>
     */
    public function getToCurrencies(): array;
}
