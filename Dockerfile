FROM php:8.4-cli

WORKDIR /app

# Copy semua file
COPY . .

# Install dependency system + node
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libpng-dev libonig-dev curl \
    && docker-php-ext-install pdo pdo_mysql zip gd

# Install Node.js (stabil, bukan versi jadul)
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-dev

# 🔥 Build frontend (INI YANG SEBELUMNYA KURANG)
RUN npm install
RUN npm run build

# Clear & cache config
RUN php artisan config:clear
RUN php artisan config:cache

# Optional tapi bagus
RUN php artisan route:cache
RUN php artisan view:cache

# Jalankan migrate saat start + serve
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=8080