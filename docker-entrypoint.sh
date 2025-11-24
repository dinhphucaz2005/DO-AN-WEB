#!/bin/bash
set -e

# Copy .env if not exists
if [ ! -f .env ]; then
    echo "Creating .env file..."
    cp .env.example .env
fi

# Force DB_DATABASE to be absolute path in .env
if grep -q "DB_DATABASE=" .env; then
    sed -i 's|DB_DATABASE=.*|DB_DATABASE=/app/database/database.sqlite|g' .env
else
    echo "DB_DATABASE=/app/database/database.sqlite" >> .env
fi

# Generate key if not set
if grep -q "APP_KEY=" .env && [ -z "$(grep "APP_KEY=" .env | cut -d '=' -f 2)" ]; then
    echo "Generating application key..."
    php artisan key:generate
fi

# Create database if not exists
if [ ! -f /app/database/database.sqlite ]; then
    echo "Creating database file..."
    touch /app/database/database.sqlite
fi

# Clear config cache to ensure correct paths
php artisan config:clear

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Start server
echo "Starting server..."
php artisan serve --host=0.0.0.0 --port=8000
