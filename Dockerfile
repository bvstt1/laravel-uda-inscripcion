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

# Instala Composer desde la imagen oficial
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Define directorio de trabajo
WORKDIR /var/www

# Copia todo el proyecto
COPY . .

# Instala dependencias PHP y compila assets
RUN composer install --no-dev --optimize-autoloader \
    && npm install \
    && npm run build

# Crea carpeta para guardar QR y da permisos
RUN mkdir -p storage/app/public/qr && chmod -R 775 storage

# Expone el puerto esperado por Railway
EXPOSE 8000

# Comandos Laravel al iniciar el contenedor
CMD php artisan config:clear \
 && php artisan route:clear \
 && php artisan view:clear \
 && php artisan migrate --force \
 && php artisan db:seed --force \
 && php artisan storage:link \
 && php artisan serve --host=0.0.0.0 --port=8000
