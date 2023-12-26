# Usa la imagen oficial de PHP 8.1.25
FROM php:8.1.25-fpm

# Establece el directorio de trabajo en el contenedor
WORKDIR /var/www/html

# Instala las dependencias de Laravel y extensiones PHP necesarias
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Instala Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copia los archivos de la aplicación
COPY . .

# Instala las dependencias de Composer
RUN composer install

# Otorga permisos de escritura
RUN chmod -R 777 storage bootstrap/cache

# Comando para iniciar el servidor de Laravel
CMD php artisan serve --host=0.0.0.0 --port=8000
