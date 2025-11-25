# Stage 1: Build frontend assets
FROM node:20-alpine as frontend
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm install
COPY . .
RUN npm run build

# Stage 2: Setup PHP runtime
FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    sqlite3 \
    libsqlite3-dev \
    && docker-php-ext-install pdo_sqlite bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy application code
COPY . .

# Copy built assets from frontend stage
COPY --from=frontend /app/public/build /app/public/build
COPY --from=frontend /app/public/build/manifest.json /app/public/build/manifest.json

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Create database file
RUN mkdir -p database && touch database/database.sqlite

# Set permissions
RUN chown -R www-data:www-data /app \
    && chmod -R 775 /app/storage \
    && chmod -R 775 /app/bootstrap/cache \
    && chmod -R 775 /app/database

# Expose port
EXPOSE 8000

# Copy entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["docker-entrypoint.sh"]
