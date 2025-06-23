FROM php:8.2-fpm

# TRIGGER DEPLOY (Railway hook)
RUN echo "trigger migrate"

# Instala dependencias del sistema
RUN apt-get update && apt-get install -y \
    build-essential \
    curl \
    zip \
    unzip \
    git \
    nodejs \
    npm \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    default-mysql-client

# Instala extensiones PHP necesarias
RUN docker-php-ext-install bcmath gd zip pdo_mysql

# Instala Composer desde imagen oficial
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copia el código
WORKDIR /var/www
COPY . .

# Instala dependencias PHP y JS
RUN composer install --no-dev --optimize-autoloader
