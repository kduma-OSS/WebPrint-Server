FROM node:22-alpine AS vite_base
WORKDIR /var/www/html



FROM php:8.5-fpm-alpine AS php_base
WORKDIR /var/www/html

RUN apk add --no-cache icu-libs libzip oniguruma
RUN apk add --no-cache --virtual .php-build-deps icu-dev libzip-dev oniguruma-dev \
    && docker-php-ext-install pdo_mysql intl mbstring zip \
    && apk del .php-build-deps

COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

FROM php_base AS composer_base
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer







FROM nginx:alpine AS nginx
WORKDIR /var/www/html


COPY docker/nginx/default.conf /etc/nginx/conf.d


COPY build/nginx/ /var/www/html/



FROM php_base AS fpm
WORKDIR /var/www/html

COPY docker/php/entrypoint.sh /var/www/entrypoint.sh
RUN chmod +x /var/www/entrypoint.sh

COPY build/php/ /var/www/html/

RUN chown -R www-data:www-data /var/www/html/storage
RUN chown -R www-data:www-data /var/www/html/bootstrap/cache

ENV APP_ENV=production
ENV APP_DEBUG=false

VOLUME /var/www/html/storage/app/jobs

CMD ["/var/www/entrypoint.sh"]



FROM php_base AS cron
WORKDIR /var/www/html

COPY docker/php/entrypoint.sh /var/www/entrypoint.sh
RUN chmod +x /var/www/entrypoint.sh

COPY build/php/ /var/www/html/

COPY docker/php/crontab /etc/crontabs/root

RUN chown -R www-data:www-data /var/www/html/storage
RUN chown -R www-data:www-data /var/www/html/bootstrap/cache

ENV APP_ENV=production
ENV APP_DEBUG=false

VOLUME /var/www/html/storage/app/jobs

CMD ["crond", "-f"]
