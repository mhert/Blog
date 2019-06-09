FROM php:7.3.2-cli-alpine3.9

ADD https://raw.githubusercontent.com/composer/getcomposer.org/cb19f2aa3aeaa2006c0cd69a7ef011eb31463067/web/installer composer-installer
RUN php composer-installer --install-dir=/usr/local/bin --filename=composer

RUN mkdir /build && \
    mkdir -p /var/www/html

WORKDIR /build
ADD app/ /build/
RUN rm -rf var vendor && \
    #We need this because of https://forums.aws.amazon.com/thread.jspa?threadID=235452
    chmod +x bin/console && \
    COMPOSER_ALLOW_SUPERUSER=1 composer install --dev --ignore-platform-reqs  && \
    rm -rf var && \
    bin/console cache:warmup --env=dev && \
    vendor/bin/phing check test

FROM php:7.3.2-fpm-alpine3.9

ADD https://raw.githubusercontent.com/composer/getcomposer.org/cb19f2aa3aeaa2006c0cd69a7ef011eb31463067/web/installer composer-installer
RUN php composer-installer --install-dir=/usr/local/bin --filename=composer

ENTRYPOINT ["/sbin/tini"]
CMD ["/usr/local/sbin/php-fpm"]

RUN apk add --no-cache tini && \
    apk add --no-cache libzip postgresql-libs && \
    apk add --no-cache --virtual .deps $PHPIZE_DEPS libzip-dev postgresql-dev && \
    docker-php-ext-install -j$(nproc) opcache zip bcmath pdo_pgsql && \
    apk del .deps

COPY --from=0 /build /build
RUN cp -r /build/bin /build/config /build/migrations /build/public /build/src /build/templates /build/.env /build/composer.json /build/composer.lock /build/symfony.lock /var/www/html/ && \
    rm -rf /build

WORKDIR /var/www/html
RUN COMPOSER_ALLOW_SUPERUSER=1 composer install --no-dev && \
    bin/console cache:warmup --env=prod
