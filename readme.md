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
