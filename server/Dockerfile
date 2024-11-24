# Use official PHP 8.1.25 image
FROM php:8.1.25-fpm

# Sets working directory
WORKDIR /var/www/html

# Install Laravel dependencies and php required extensions
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy app's files
COPY . .

# Install composer's dependencies 
RUN composer install

# Grants write permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Run migrations and start the Laravel server
CMD php artisan serve --host=0.0.0.0 --port=8000
