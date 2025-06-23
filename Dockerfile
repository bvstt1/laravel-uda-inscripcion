FROM php:8.2-fpm


# TRIGGER DEPLOY
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

# Instala Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
COPY . .

# Instala dependencias y compila
RUN composer install --no-dev --optimize-autoloader \
    && npm install \
    && npm run build

EXPOSE 8000

# Comandos Laravel
CMD php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache \
    && php artisan migrate --force \
    && php artisan db:seed --force \
    && php artisan serve --host=0.0.0.0 --port=8000
