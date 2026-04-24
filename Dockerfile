FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git unzip zip libzip-dev libpng-dev \
    && docker-php-ext-install zip gd

# install composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer

WORKDIR /app

COPY . .

RUN composer install --no-dev --optimize-autoloader

# optimize laravel
RUN php artisan config:cache || true
RUN php artisan route:cache || true

CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT