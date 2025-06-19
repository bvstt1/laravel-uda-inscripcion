FROM php:8.2-fpm

# Instala dependencias
RUN apt-get update && apt-get install -y \
    build-essential \
    default-mysql-client \
    curl \
    zip \
    unzip \
    git \
    nodejs \
    npm

# Instala Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
COPY . .

# Instala dependencias y compila
RUN composer install --no-dev --optimize-autoloader \
    && npm install \
    && npm run build \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

EXPOSE 8000

# ✅ Comando que corre cuando Railway levanta el contenedor:
CMD php artisan migrate --force && php artisan db:seed --force && php artisan serve --host=0.0.0.0 --port=8000
