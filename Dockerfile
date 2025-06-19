FROM php:8.2-fpm

# Instala dependencias del sistema
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

# Define directorio de trabajo
WORKDIR /var/www

# Copia archivos del proyecto
COPY . .

# Instala dependencias y build
RUN composer install --no-dev --optimize-autoloader \
    && npm install \
    && npm run build \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache \
    && php artisan migrate --force \
    && php artisan db:seed --force
# Puerto de Laravel
EXPOSE 8000

# Comando para correr la app
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
