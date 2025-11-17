# -----------------------------
# 1) Build Frontend (Node)
# -----------------------------
FROM node:20-alpine AS node_builder
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

# -----------------------------
# 2) Install Composer deps (PHP)
# -----------------------------
FROM composer:2 AS php_builder
WORKDIR /app
COPY . .

# Tạm tắt scripts lúc install để cache layer composer
RUN composer install --no-dev --prefer-dist --no-scripts --optimize-autoloader
RUN composer run-script post-autoload-dump

# -----------------------------
# 3) Final Runtime Image
# -----------------------------
FROM php:8.2-fpm-alpine

# Install dependencies for Laravel + Nginx + Supervisor
RUN apk add --no-cache \
    nginx \
    supervisor \
    sqlite \
    sqlite-dev \
    curl \
    libpng \
    libpng-dev \
    libxml2-dev \
    oniguruma-dev \
    zip \
    unzip \
    gcc \
    musl-dev \
    make \
    autoconf \
    g++ \
    bash

# PHP extensions
RUN docker-php-ext-install pdo pdo_mysql pdo_sqlite mbstring exif bcmath gd

# Workdir
WORKDIR /var/www/html

# Copy Laravel code (PHP builder)
COPY --from=php_builder /app /var/www/html

# Copy built assets (Node builder)
COPY --from=node_builder /app/public/build /var/www/html/public/build

# Copy database SQLite
COPY database/database.sqlite /var/www/html/database/database.sqlite

# Copy .env và generate APP_KEY
COPY .env.example /var/www/html/.env
RUN php artisan key:generate --force

# Storage, cache, log
RUN mkdir -p storage bootstrap/cache /var/log/supervisor \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 755 storage bootstrap/cache /var/log/supervisor \
    && chmod 664 /var/www/html/database/database.sqlite

# Laravel setup
RUN php artisan storage:link || true
RUN php artisan config:cache || true
RUN php artisan route:cache || true

# Nginx + Supervisor configs
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 80
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
