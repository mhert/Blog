FROM php:7.3.2-cli-alpine3.9

ADD https://raw.githubusercontent.com/composer/getcomposer.org/cb19f2aa3aeaa2006c0cd69a7ef011eb31463067/web/installer composer-installer
RUN php composer-installer --install-dir=/usr/local/bin --filename=composer

RUN mkdir /build
WORKDIR /build
ADD app/ /build/
RUN rm -rf var vendor && \
    #We need this because of https://forums.aws.amazon.com/thread.jspa?threadID=235452
    chmod +x bin/console && \
    COMPOSER_ALLOW_SUPERUSER=1 composer install --dev --ignore-platform-reqs  && \
    rm -rf var && \
    bin/console cache:warmup --env=dev && \
    \
    vendor/bin/phing check test && \
    \
    COMPOSER_ALLOW_SUPERUSER=1 composer install --no-dev && \
    rm -rf var && \
    bin/console cache:warmup --env=prod && \
    mkdir out && \
    cp -r .env config public src var vendor out

FROM php:7.3.2-fpm-alpine3.9

ENTRYPOINT ["/sbin/tini"]
CMD ["/usr/local/sbin/php-fpm"]

RUN apk add --no-cache tini

RUN apk add --no-cache libzip && \
    apk add --no-cache --virtual .deps $PHPIZE_DEPS libzip-dev && \
    docker-php-ext-install -j$(nproc) opcache zip bcmath && \
    apk del .deps

RUN mkdir -p -m 0644 /var/www/html/var && chown www-data:www-data /var/www/html/var

COPY --from=0 /build/out/ /var/www/html/
