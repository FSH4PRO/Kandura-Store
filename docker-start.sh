#!/bin/sh
set -e

cd /var/www

# Clear all cached config/routes/views/events so fresh env vars take effect
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true
php artisan event:clear || true
php artisan cache:clear || true

# Ensure directories are writable
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache || true

# Rebuild optimized caches for production
if [ "$APP_ENV" = "production" ]; then
    php artisan config:cache || true
    php artisan route:cache || true
    php artisan view:cache || true
fi

# Apply database migrations on every start so schema is always up to date
php artisan migrate --force || true

# Seed only when the database is empty (first deploy). Re-running seeders that
# use Admin::create()/Customer::create() would hit unique constraints and fail,
# so guard seeding behind an emptiness check to keep deploys idempotent.
if [ "$(php artisan tinker --execute='echo App\\Models\\User::query()->count();' 2>/dev/null || echo 0)" = "0" ]; then
    php artisan db:seed --force || true
fi

# Start Apache in the foreground
exec apache2-foreground
