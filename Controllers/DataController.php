<?php

namespace Controllers;

use DateTime;
use Factories\CacheFactory;
use GuzzleHttp\Exception\GuzzleException;
use Libs\Response;
use Models\Currency;
use Phalcon\Cache\Adapter\Stream;
use Phalcon\Cache\Cache;
use Phalcon\Cache\Exception\InvalidArgumentException;
use Phalcon\Filter\Validation;
use Phalcon\Http\ResponseInterface;
use Phalcon\Mvc\Controller;
use Phalcon\Storage\Exception;
use Phalcon\Storage\SerializerFactory;
use Services\ApiLayer;

class DataController extends Controller
{
    /**
     * @return ResponseInterface
     * @throws GuzzleException
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function getData(): ResponseInterface
    {
        $data = $this->request->getJsonRawBody(true);

        $validation = new Validation();

        $validation->add("fromCurrency", new Validation\Validator\PresenceOf());
        $validation->add("fromCurrencyBaseAmount", new Validation\Validator\Numericality());
        $validation->add(
            "toCurrencies",
            new Validation\Validator\Callback([
                "message"  => "toCurrencies must be an array",
                "callback" => function ($field) {
                    return is_array($field["toCurrencies"]);
                }
            ])
        );

        $validation = $validation->validate($data);

        if ($validation->valid()) {
            return Response::fail("validation fail", $validation->jsonSerialize());
        }

        $cache = CacheFactory::init();
        $key = "{$data["fromCurrency"]}__{$data["fromCurrencyBaseAmount"]}";

        if (!empty($data["refresh"])) {
            $cache->delete($key);
        }

        if ($cache->has($key)) {
            return Response::json((array)$cache->get($key));
        }

        $currency = new Currency();
        $currency->setFromCurrency($data["fromCurrency"], $data["fromCurrencyBaseAmount"]);
        $currency->setToCurrencies($data["toCurrencies"]);

        $apiLayer = new ApiLayer();
        $result   = $apiLayer->getConversionResult($currency)->map();

        $timestamp           = new DateTime();
        $result["timestamp"] = $timestamp->format('c');

        $cache->set("{$data["fromCurrency"]}__{$data["fromCurrencyBaseAmount"]}", $result, 300);
        return Response::json($result);
    }
}
