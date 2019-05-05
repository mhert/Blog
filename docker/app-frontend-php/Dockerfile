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
