<?php

namespace Services;

use Contracts\CurrencyInterface;
use Contracts\ExchangerInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ApiLayer implements ExchangerInterface
{
    private string $apiResponse;

    private float $baseAmount;

    /**
     * @throws GuzzleException
     */
    public function getConversionResult(CurrencyInterface $currency): ExchangerInterface
    {
        $client = new Client();

        $toSymbols = $currency->getToCurrencies();
        $toSymbols = implode(",", $toSymbols);

        $toSymbols  = urlencode($toSymbols);
        $requestUrl = "https://api.apilayer.com/exchangerates_data/latest" .
            "?symbols={$toSymbols}&base={$currency->getFromCurrency()}";

        $res = $client->request('GET', $requestUrl, [
            'headers' => [
                'apikey' => $_ENV["APILAYER_KEY"]
            ]
        ]);

        $this->apiResponse = $res->getBody()->getContents();
        $this->baseAmount  = $currency->getFromCurrencyBase();

        return $this;
    }

    public function map(): array
    {
        $rawResult = json_decode($this->apiResponse, true);

        $newRate = [];
        foreach ($rawResult["rates"] as $symbol => $rate) {
            $newRate[$symbol] = $this->baseAmount * $rate;
        }

        return [
            "base"       => $rawResult["base"],
            "baseAmount" => $this->baseAmount,
            "rates"      => $newRate,
        ];
    }
}

