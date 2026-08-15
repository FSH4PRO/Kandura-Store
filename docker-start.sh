#!/bin/sh

set -e

cd /var/www

echo "=========================================="
echo "      Starting Kandura Store"
echo "=========================================="


# =========================================================
# Environment validation
# =========================================================

echo "Checking environment..."

if [ -z "$APP_KEY" ]; then
    echo "ERROR: APP_KEY is not configured."
    exit 1
fi

if [ -z "$APP_ENV" ]; then
    export APP_ENV=production
fi


# =========================================================
# Permissions
# =========================================================

echo "Preparing Laravel directories..."

mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p bootstrap/cache

chown -R www-data:www-data \
    storage \
    bootstrap/cache

chmod -R 775 \
    storage \
    bootstrap/cache


# =========================================================
# Clear old Laravel caches
# =========================================================

echo "Clearing old Laravel caches..."

php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true
php artisan event:clear || true
php artisan cache:clear || true


# =========================================================
# Database migrations
# =========================================================

echo "Running database migrations..."

php artisan migrate --force


# =========================================================
# Passport
# =========================================================

echo "Checking Passport keys..."

if [ ! -f "storage/oauth-private.key" ] || [ ! -f "storage/oauth-public.key" ]; then

    echo "Passport keys not found."
    echo "Generating Passport keys..."

    php artisan passport:keys --force

else

    echo "Passport keys already exist."
    echo "Skipping Passport key generation."

fi


# =========================================================
# Passport Personal Access Client
# =========================================================

echo "Checking Passport Personal Access Client..."

PERSONAL_CLIENT_COUNT=$(php artisan tinker --execute="
echo DB::table('oauth_clients')
    ->where('personal_access_client', true)
    ->count();
" 2>/dev/null || echo "0")


if [ "$PERSONAL_CLIENT_COUNT" = "0" ]; then

    echo "No Personal Access Client found."

    php artisan passport:client \
        --personal \
        --name="Kandura Personal Access Client" \
        --provider=customers \
        --no-interaction \
        || true

else

    echo "Personal Access Client already exists."

fi


# =========================================================
# Database seeding
# =========================================================

echo "Checking database seed status..."

ADMIN_COUNT=$(php artisan tinker --execute="
echo App\\\\Models\\\\Admin::query()->count();
" 2>/dev/null || echo "0")


if [ "$ADMIN_COUNT" = "0" ]; then

    echo "No admins found."
    echo "Running database seeders..."

    php artisan db:seed --force

else

    echo "Admins already exist."
    echo "Skipping database seed."

fi


# =========================================================
# Production cache
# =========================================================

if [ "$APP_ENV" = "production" ]; then

    echo "Building production caches..."

    php artisan config:cache
    php artisan route:cache
    php artisan view:cache

fi


# =========================================================
# Final permissions
# =========================================================

echo "Applying final permissions..."

chown -R www-data:www-data \
    storage \
    bootstrap/cache

chmod -R 775 \
    storage \
    bootstrap/cache

# Passport key permissions
if [ -f storage/oauth-private.key ]; then
    chmod 600 storage/oauth-private.key
fi

if [ -f storage/oauth-public.key ]; then
    chmod 600 storage/oauth-public.key
fi


# =========================================================
# Start Supervisor
# =========================================================
echo "=========================================="
echo "Kandura Store is ready!"
echo "Starting Apache + Queue Worker..."
echo "=========================================="

exec /usr/bin/supervisord \
    -c /etc/supervisor/conf.d/kandura.conf