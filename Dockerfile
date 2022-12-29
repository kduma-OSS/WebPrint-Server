FROM php:8.1-cli-alpine AS composer_stage

WORKDIR /var/www/html

COPY --from=composer /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./
RUN composer install --ignore-platform-reqs --prefer-dist --no-scripts --no-progress --no-interaction --no-dev --no-autoloader

COPY . ./
RUN composer dump-autoload --optimize --apcu --no-dev
RUN php artisan view:cache


FROM node:19-alpine AS vite_stage

WORKDIR /var/www/html

COPY package.json package-lock.json ./
RUN npm ci

COPY --from=composer_stage /var/www/html/vendor /var/www/html/vendor
COPY --from=composer_stage /var/www/html/storage/framework/views/ /var/www/html/storage/framework/views/
COPY postcss.config.js tailwind.config.js vite.config.js ./
COPY resources ./resources
RUN npm run build




FROM nginx:alpine AS ngnix

WORKDIR /var/www/html

COPY docker/nginx/default.conf /etc/nginx/conf.d

COPY --from=vite_stage /var/www/html/public/build /var/www/html/public/build

COPY ./ /var/www/html/






FROM php:8.1-fpm-alpine AS fpm

WORKDIR /var/www/html

RUN docker-php-ext-install pdo pdo_mysql
RUN docker-php-ext-install opcache

COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini



COPY --from=vite_stage /var/www/html/public/build /var/www/html/public/build

COPY --from=composer_stage /var/www/html/vendor /var/www/html/vendor
COPY --from=composer_stage /var/www/html/bootstrap/cache /var/www/html/bootstrap/cache

COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html/storage
RUN chown -R www-data:www-data /var/www/html/bootstrap/cache


ENV APP_ENV=production
ENV APP_DEBUG=false
