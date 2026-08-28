FROM php:8.3-apache

# --------------------------------------------------
# System dependencies
# --------------------------------------------------

RUN apt-get update && apt-get install -y \
    libpq-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    unzip \
    git \
    curl \
    nodejs \
    npm \
    && docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
    && docker-php-ext-install \
        pdo_pgsql \
        mbstring \
        exif \
        bcmath \
        gd \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*


# --------------------------------------------------
# Composer
# --------------------------------------------------

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer


# --------------------------------------------------
# Laravel
# --------------------------------------------------

WORKDIR /var/www

COPY . .


# --------------------------------------------------
# PHP dependencies
# --------------------------------------------------

 RUN composer install
 
#     --no-dev \
#     --optimize-autoloader \
#     --no-interaction


# --------------------------------------------------
# Frontend
# --------------------------------------------------

RUN npm install

RUN npm run build


# --------------------------------------------------
# Laravel storage
# --------------------------------------------------

RUN php artisan storage:link || true


# --------------------------------------------------
# Permissions
# --------------------------------------------------

RUN mkdir -p \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

RUN chown -R www-data:www-data \
    storage \
    bootstrap/cache

RUN chmod -R 775 \
    storage \
    bootstrap/cache


# --------------------------------------------------
# Apache configuration
# --------------------------------------------------

RUN printf '<VirtualHost *:80>\n\
    DocumentRoot /var/www/public\n\
\n\
    <Directory /var/www/public>\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
\n\
    ErrorLog ${APACHE_LOG_DIR}/error.log\n\
    CustomLog ${APACHE_LOG_DIR}/access.log combined\n\
</VirtualHost>\n' \
> /etc/apache2/sites-available/000-default.conf


# --------------------------------------------------
# Startup script
# --------------------------------------------------

COPY docker-start.sh /var/www/docker-start.sh

RUN chmod +x /var/www/docker-start.sh


# --------------------------------------------------
# Port
# --------------------------------------------------

EXPOSE 80


# --------------------------------------------------
# Start
# --------------------------------------------------

CMD ["/var/www/docker-start.sh"]