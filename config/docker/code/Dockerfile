FROM php:7.2-fpm AS builder

ARG SYMFONY_ENV=prod

RUN mkdir -p /var/www/html

WORKDIR /var/www/html

ADD ./composer.json ./composer.lock /var/www/html/

RUN apt-get update && apt-get install -y \
        libpq-dev \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        git \
    && docker-php-ext-install -j$(nproc) iconv \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo \
    && docker-php-ext-install zip \
    && docker-php-ext-install pdo_pgsql \
    && php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && php composer.phar install --no-interaction --no-autoloader --no-scripts --no-dev

COPY . /var/www/html/

RUN APP_ENV=$SYMFONY_ENV php composer.phar install --no-interaction --no-dev --no-scripts --optimize-autoloader \
    && rm -rf var/cache/$SYMFONY_ENV

FROM alpine:3.10

COPY --from=builder /var/www/html /var/www/html

RUN apk add --update --no-cache nodejs --repository http://dl-3.alpinelinux.org/alpine/v3.10/main && \
    apk add --update --no-cache yarn --repository http://dl-3.alpinelinux.org/alpine/v3.10/community && \
    apk add --update --no-cache git && \
    yarn install --cwd=/var/www/html && \
    yarn run --cwd=/var/www/html encore production

RUN mkdir -p /var/www/html/var \
    && chown -R 82:82 \
        /var/www/html/var \
        /var/www/html/bin \
        /var/www/html/public

RUN rm -rf var/cache/$SYMFONY_ENV/*

ENTRYPOINT ["tail", "-f", "/dev/null"]
VOLUME ["/var/www/html"]