# =============================================================
# Stage 1: Build frontend assets (Node.js + Vite + Tailwind)
# =============================================================
FROM node:20-alpine AS frontend

WORKDIR /app

COPY package.json package-lock.json* ./
RUN npm ci --quiet

COPY . .
RUN npm run build

# =============================================================
# Stage 2: PHP Production Image (PHP-FPM + Alpine)
# =============================================================
FROM php:8.2-fpm-alpine AS app

# Install system libraries yang dibutuhkan oleh extension PHP
RUN apk add --no-cache \
    mysql-client \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    libxml2-dev \
    icu-dev \
    oniguruma-dev \
    bash \
    curl \
    unzip \
    git

# Konfigurasi dan install PHP extensions
# - pdo_mysql  : driver MySQL
# - gd         : generate barcode & QR code (picqer + simple-qrcode)
# - bcmath     : kalkulasi angka presisi tinggi
# - pcntl      : process control (queue worker)
# - zip        : maatwebsite/excel
# - intl       : internasionalisasi (locale id)
# - mbstring   : manipulasi string multibyte
# - simplexml  : parsing XML (maatwebsite/excel)
# - xml        : XML support
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        gd \
        bcmath \
        pcntl \
        zip \
        intl \
        mbstring \
        simplexml \
        xml

# Install Composer dari official image
COPY --from=composer:2.8 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy composer manifest lebih dulu agar layer di-cache
COPY composer.json composer.lock ./

# Install PHP dependencies (tanpa dev, tanpa autoload dulu)
RUN composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --prefer-dist \
    --no-interaction

# Copy seluruh source code
COPY . .

# Copy hasil build frontend dari stage 1
COPY --from=frontend /app/public/build ./public/build

# Generate optimized autoloader
RUN composer dump-autoload --optimize --no-dev

# Copy file konfigurasi PHP custom
COPY docker/php/local.ini /usr/local/etc/php/conf.d/local.ini

# Set ownership dan permission untuk storage & bootstrap/cache
RUN chown -R www-data:www-data \
        /var/www/html/storage \
        /var/www/html/bootstrap/cache \
    && chmod -R 775 \
        /var/www/html/storage \
        /var/www/html/bootstrap/cache

# Copy dan beri izin eksekusi entrypoint script
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 9000

ENTRYPOINT ["/entrypoint.sh"]
CMD ["php-fpm"]
