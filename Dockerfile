FROM elrincondeisma/octane:latest

# Instalar dependencias necesarias y extensiones PHP
RUN apk add --no-cache \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    oniguruma-dev \
    libxml2-dev \
    curl-dev \
    bash \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd curl zip

# Copiar Composer desde imagen oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar RoadRunner desde imagen oficial
COPY --from=spiralscout/roadrunner:2.4.2 /usr/bin/rr /usr/bin/rr

WORKDIR /app
COPY . .

# Instalar dependencias de Composer
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Instalar Octane y RoadRunner
RUN composer require laravel/octane spiral/roadrunner

# Configuración inicial de Laravel
COPY .env.example .env
RUN mkdir -p storage/logs

# Limpiar caches de Laravel
RUN php artisan cache:clear
RUN php artisan view:clear
RUN php artisan config:clear

# Instalar Octane con Swoole
RUN php artisan octane:install --server="swoole"

EXPOSE 8000
CMD ["php", "artisan", "octane", "--server=swoole", "--host=0.0.0.0"]
