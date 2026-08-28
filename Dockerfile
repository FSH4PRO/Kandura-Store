FROM php:8.3-apache

# --------------------------------------------------
# System dependencies & PHP Extensions
# --------------------------------------------------

RUN apt-get update && apt-get install -y \
    libpq-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libzip-dev \
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
        pdo_mysql \
        mbstring \
        exif \
        bcmath \
        gd \
        zip \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# --------------------------------------------------
# Composer
# --------------------------------------------------

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# --------------------------------------------------
# PHP dependencies (Optimized Docker Layering)
# --------------------------------------------------

WORKDIR /var/www

# Copy only dependency definitions first to utilize layer caching
COPY composer.json composer.lock ./

# Pass high memory limit to avoid out-of-memory errors on autoloader optimization
RUN COMPOSER_MEMORY_LIMIT=-1 composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --no-scripts

# Copy the rest of the application code
COPY . .

# Run scripts that depend on project source files post-copy
RUN composer dump-autoload --optimize --no-dev