FROM php:7.2-fpm-alpine

COPY ./config/docker/fpm/php.ini /usr/local/etc/php/php.ini

WORKDIR /var/www/html

RUN apk update && apk add \
        postgresql-dev \
        freetype-dev \
        libjpeg-turbo-dev \
        libpng-dev \
    && docker-php-ext-install -j$(nproc) iconv \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo \
    && docker-php-ext-install pdo_pgsql

EXPOSE 9000

CMD ["php-fpm"]