FROM php:8.4-cli

WORKDIR /app

COPY . .

RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libpng-dev libonig-dev \
    && docker-php-ext-install pdo pdo_mysql zip gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN composer install --no-dev --optimize-autoloader

RUN php artisan config:clear

CMD php artisan serve --host=0.0.0.0 --port=8080