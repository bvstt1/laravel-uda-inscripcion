# Base con PHP 8.2 y Alpine
FROM php:8.2-fpm-alpine

# Instalar dependencias del sistema y extensiones PHP necesarias
RUN apk add --no-cache \
    bash \
    git \
    unzip \
    libzip-dev \
    oniguruma-dev \
    libpng-dev \
    libxml2-dev \
    curl-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    zlib-dev \
    libwebp-dev \
    icu-dev \
    && docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
        --with-webp \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd curl zip intl opcache

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instalar RoadRunner
RUN curl -Ls https://github.com/spiral/roadrunner/releases/download/v2.11.1/roadrunner-2.11.1-linux-amd64.tar.gz \
    | tar -xz -C /usr/local/bin rr

# Configurar directorio de trabajo
WORKDIR /app
COPY . .

# Instalar dependencias de Composer
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Instalar Octane y RoadRunner si no están en composer.json
RUN composer require laravel/octane spiral/roadrunner

# Configuración Laravel
COPY .env.example .env
RUN mkdir -p storage/logs
RUN php artisan cache:clear
RUN php artisan view:clear
RUN php artisan config:clear

# Instalar Octane con Swoole
RUN php artisan octane:install --server="swoole"

# Exponer puerto y comando por defecto
EXPOSE 8000
CMD ["php", "artisan", "octane", "--server=swoole", "--host=0.0.0.0"]
