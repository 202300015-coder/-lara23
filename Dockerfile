FROM php:8.2-cli

WORKDIR /var/www/html

RUN apt-get update \
    && apt-get install -y --no-install-recommends git unzip libzip-dev \
    && docker-php-ext-install zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist \
    && if [ -f .env.example ]; then cp .env.example .env; elif [ ! -f .env ]; then touch .env; fi \
    && mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && php artisan key:generate --force \
    && php artisan config:clear

EXPOSE 10000
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT:-10000}
