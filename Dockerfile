# Base con PHP + Swoole listo
FROM elrincondeisma/octane:latest

# Instalar dependencias del sistema necesarias para Composer y extensiones PHP
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libcurl4-openssl-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd curl zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Copiar Composer desde imagen oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar RoadRunner desde imagen oficial
COPY --from=spiralscout/roadrunner:2.4.2 /usr/bin/rr /usr/bin/rr

# Definir directorio de trabajo
WORKDIR /app

# Copiar archivos del proyecto
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

# Exponer puerto y definir comando
EXPOSE 8000
CMD ["php", "artisan", "octane", "--server=swoole", "--host=0.0.0.0"]
