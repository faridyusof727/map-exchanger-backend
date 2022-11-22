<?php

namespace Libs;

use Phalcon\Http\ResponseInterface;


class Response
{
    const NOT_FOUND_HTTP_CODE             = 404;
    const OK_HTTP_CODE                    = 200;
    const BAD_REQUEST_HTTP_CODE           = 400;
    const INTERNAL_SERVER_ERROR_HTTP_CODE = 500;

    const CONTENT_TYPE_JSON = "application/json";

    /**
     * @return ResponseInterface
     */
    public static function notFound(): ResponseInterface
    {
        $response = new \Phalcon\Http\Response();
        $result   = [
            "code"    => self::NOT_FOUND_HTTP_CODE,
            "message" => "Not found"
        ];

        return $response
            ->setStatusCode(self::NOT_FOUND_HTTP_CODE)
            ->setContent(json_encode($result))
            ->setContentType(self::CONTENT_TYPE_JSON);
    }

    /**
     * @param array $data
     * @return ResponseInterface
     */
    public static function json(array $data = []): ResponseInterface
    {
        $response = new \Phalcon\Http\Response();

        return $response
            ->setStatusCode(self::OK_HTTP_CODE)
            ->setContent(json_encode($data))
            ->setContentType(self::CONTENT_TYPE_JSON);
    }

    /**
     * @param string $message
     * @param array $data
     * @param int $errorCode
     * @return ResponseInterface
     */
    public static function fail(
        string $message,
        array  $data = [],
        int    $errorCode = self::BAD_REQUEST_HTTP_CODE
    ): ResponseInterface
    {
        $response = new \Phalcon\Http\Response();

        $data = [
            "code"    => $errorCode,
            "data"    => $data,
            "message" => $message
        ];

        return $response
            ->setStatusCode($errorCode)
            ->setContent(json_encode($data))
            ->setContentType(self::CONTENT_TYPE_JSON);
    }
}
