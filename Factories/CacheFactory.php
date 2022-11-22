<?php

namespace Factories;

use Phalcon\Cache\Adapter\Stream;
use Phalcon\Cache\Cache;
use Phalcon\Storage\Exception;
use Phalcon\Storage\SerializerFactory;

class CacheFactory
{
    const CACHE_TTL = 7200;

    /**
     * @throws Exception
     */
    public static function init(): Cache
    {
        $serializerFactory = new SerializerFactory();
        $options           = [
            'defaultSerializer' => 'Json',
            'lifetime'          => self::CACHE_TTL,
            'storageDir'        => 'cache',
        ];
        $adapter           = new Stream($serializerFactory, $options);
        return new Cache($adapter);
    }
}
