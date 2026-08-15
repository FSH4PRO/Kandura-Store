#!/bin/sh

set -e

cd /var/www

echo "========================================"
echo "Starting Kandura Store..."
echo "========================================"

# ---------------------------------------------------------
# Clear old Laravel caches
# ---------------------------------------------------------

echo "Clearing Laravel caches..."

php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true
php artisan event:clear || true
php artisan cache:clear || true

# ---------------------------------------------------------
# Ensure Laravel directories are writable
# ---------------------------------------------------------

echo "Setting permissions..."

chmod -R 775 storage bootstrap/cache || true

chown -R www-data:www-data storage bootstrap/cache || true

# ---------------------------------------------------------
# Database migrations
# ---------------------------------------------------------

echo "Running database migrations..."

php artisan migrate --force

# ---------------------------------------------------------
# Database seeding
# ---------------------------------------------------------

if [ "${RUN_SEEDER:-false}" = "true" ]; then

    echo "========================================"
    echo "RUN_SEEDER=true"
    echo "Running database seeders..."
    echo "========================================"

    php artisan db:seed --force

    echo "Database seeding completed."

else

    echo "RUN_SEEDER is not enabled."
    echo "Skipping database seeding."

fi

# ---------------------------------------------------------
# Production caches
# ---------------------------------------------------------

if [ "${APP_ENV:-production}" = "production" ]; then

    echo "Building production caches..."

    php artisan config:cache
    php artisan route:cache
    php artisan view:cache

fi

# ---------------------------------------------------------
# Start Apache
# ---------------------------------------------------------

echo "========================================"
echo "Starting Apache..."
echo "========================================"

exec apache2-foreground