<?php

namespace Contracts;

interface ExchangerInterface
{
    public function getConversionResult(CurrencyInterface $currency): ExchangerInterface;

    /**
     * You can transform response result from the exchange provider.
     * Transform could be modifying response, calculate and etc.
     * @return array
     */
    public function map(): array;
}
