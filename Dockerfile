FROM php:8.4-cli

WORKDIR /app

COPY . .

RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libpng-dev libonig-dev curl \
    && docker-php-ext-install pdo pdo_mysql zip gd

# Install Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install PHP deps (tanpa --no-dev karena pakai Breeze)
RUN composer install --optimize-autoloader

# Build frontend
RUN npm install
RUN npm run build

# ❗ HANYA CLEAR (JANGAN CACHE)
RUN php artisan config:clear

# 🔥 Jalankan saat runtime (bukan build)
CMD php artisan config:clear && php artisan cache:clear && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=8080