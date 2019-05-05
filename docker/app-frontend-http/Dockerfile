FROM nginx:1.15.8-alpine

ENTRYPOINT ["/sbin/tini"]
CMD ["nginx", "-g", "daemon off;"]

RUN apk add --no-cache \
    tini

RUN mkdir -p /var/www/html/public

COPY docker/app-frontend-http/conf/etc /etc
COPY assets /var/www/html/public/assets
