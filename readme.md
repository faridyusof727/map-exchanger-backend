# Exchanger Rate Backend

## Step to deploy

### Build image

```shell
docker build . -t map-exchanger-backend:latest
```

### Run container

```shell
docker run -e APILAYER_KEY='<APILAYER API KEY>' -e ENV='prod' -d -p 8081:80 map-exchanger-backend:latest
```

### Notes

File `Factories/CacheFactory.php:19`

```php
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
```

The current cache mechanism is using disk cache. 

"Disk Cache" is persistent. Cached resources are stored and loaded to and from disk.

"Memory Cache" stores and loads resources to and from Memory (RAM). So this is much faster but it is non-persistent. 
Content is available until the server restarted.

Phalcon provide different adapter for caching. Refer https://docs.phalcon.io/5.0/en/cache.

