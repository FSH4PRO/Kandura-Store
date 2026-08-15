#!/bin/sh

set -e

cd /var/www

echo "========================================="
echo "Starting Kandura..."
echo "========================================="

# --------------------------------------------------
# 1. Permissions
# --------------------------------------------------

mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p bootstrap/cache

chown -R www-data:www-data storage bootstrap/cache

chmod -R 775 storage bootstrap/cache

# Passport keys need restrictive permissions
if [ -f storage/oauth-private.key ]; then
    chmod 600 storage/oauth-private.key
fi

if [ -f storage/oauth-public.key ]; then
    chmod 600 storage/oauth-public.key
fi


# --------------------------------------------------
# 2. Clear old Laravel caches
# --------------------------------------------------

echo "Clearing Laravel caches..."

php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true
php artisan event:clear || true
php artisan cache:clear || true


# --------------------------------------------------
# 3. Database migrations
# --------------------------------------------------

echo "Running migrations..."

php artisan migrate --force


# --------------------------------------------------
# 4. Passport keys
# --------------------------------------------------

echo "Checking Passport keys..."

if [ ! -f storage/oauth-private.key ] || [ ! -f storage/oauth-public.key ]; then

    echo "Passport keys not found. Generating..."

    php artisan passport:keys --force

fi

# Fix permissions AFTER generating keys
chmod 600 storage/oauth-private.key
chmod 600 storage/oauth-public.key

chown www-data:www-data storage/oauth-private.key
chown www-data:www-data storage/oauth-public.key


# --------------------------------------------------
# 5. Passport Personal Access Client
# --------------------------------------------------

echo "Checking Passport personal access client..."

CLIENT_EXISTS=$(php artisan tinker --execute="
use Laravel\Passport\Client;

echo Client::where('provider', 'customers')
    ->where('personal_access_client', true)
    ->exists() ? 'yes' : 'no';
" 2>/dev/null || echo "no")

if [ "$CLIENT_EXISTS" != "yes" ]; then

    echo "Creating Customer Personal Access Client..."

    php artisan passport:client \
        --personal \
        --provider=customers \
        --name="Kandura Customer Personal Access Client" \
        --no-interaction

else

    echo "Customer Personal Access Client already exists."

fi


# --------------------------------------------------
# 6. Seed database only when completely empty
# --------------------------------------------------

USER_COUNT=$(php artisan tinker --execute="
echo App\Models\User::count();
" 2>/dev/null || echo "0")

if [ "$USER_COUNT" = "0" ]; then

    echo "Database appears empty. Running seeders..."

    php artisan db:seed --force

else

    echo "Database already contains users. Skipping seeders."

fi


# --------------------------------------------------
# 7. Production caches
# --------------------------------------------------

if [ "$APP_ENV" = "production" ]; then

    echo "Building production caches..."

    php artisan config:cache
    php artisan route:cache
    php artisan view:cache

fi


# --------------------------------------------------
# 8. Final Passport permissions
# --------------------------------------------------

chmod 600 storage/oauth-private.key
chmod 600 storage/oauth-public.key

chown www-data:www-data storage/oauth-private.key
chown www-data:www-data storage/oauth-public.key


# --------------------------------------------------
# 9. Start Apache
# --------------------------------------------------

echo "========================================="
echo "Kandura is ready!"
echo "========================================="

exec apache2-foreground