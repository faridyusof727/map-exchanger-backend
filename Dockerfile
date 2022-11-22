FROM php:7.4.20-fpm-alpine3.13

RUN apk update && apk upgrade
RUN apk add $PHPIZE_DEPS \
    		openssl-dev \
    		oniguruma-dev \
    		libxml2-dev \
    		freetype-dev \
    		libjpeg-turbo-dev \
            libpng-dev \
            libzip-dev \
            git \
            nginx \
            openssh \
            pcre-dev

RUN docker-php-ext-install bcmath ctype fileinfo json mbstring pdo_mysql mysqli xml zip

RUN pecl install phalcon-5.0.0

RUN echo "extension=phalcon.so" > /usr/local/etc/php/conf.d/phalcon.ini

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . .
COPY nginx.conf /etc/nginx/conf.d/default.conf

RUN composer install

RUN chown -R www-data:www-data .

RUN mkdir -p /run/nginx

EXPOSE 80

CMD ["sh", "-c", "php-fpm -D && nginx -g 'daemon off;'"]

