FROM node:22-alpine AS frontend

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY resources ./resources
COPY public ./public
COPY vite.config.js ./
RUN npm run build

FROM composer:2 AS vendor

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --no-progress --no-scripts

COPY . .
RUN composer dump-autoload --no-dev --optimize --no-interaction

FROM php:8.5-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends libpq-dev \
    && docker-php-ext-install pdo_pgsql \
    && a2dismod mpm_event mpm_worker \
    && a2enmod mpm_prefork rewrite \
    && test "$(apache2ctl -M | grep -c 'mpm_')" -eq 1 \
    && rm -rf /var/lib/apt/lists/*

COPY docker/apache-vhost.conf /etc/apache2/sites-available/000-default.conf
COPY --from=vendor /app /var/www/html
COPY --from=frontend /app/public/build /var/www/html/public/build

WORKDIR /var/www/html

RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs storage/app/public bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80

CMD ["sh", "-c", "chown -R www-data:www-data storage/framework storage/logs storage/app/public bootstrap/cache && php artisan storage:link --force && php artisan migrate --force && exec apache2-foreground"]
