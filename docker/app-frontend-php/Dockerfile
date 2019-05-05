FROM php:7.3.2-fpm-alpine3.9

ENTRYPOINT ["/sbin/tini"]
CMD ["/usr/local/sbin/php-fpm"]

RUN apk add --no-cache \
    tini

RUN mkdir -p -m 0644 /var/www/html/var && chown www-data:www-data /var/www/html/var
