# Usa PHP FPM 8.2
FROM php:8.2-fpm

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
    default-mysql-client \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Instala extensiones PHP necesarias
RUN docker-php-ext-install bcmath gd zip pdo_mysql

# Instala Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Directorio de trabajo
WORKDIR /var/www

# Copia todo el código
COPY . .

# Recibe secretos como build args
ARG DB_CONNECTION
ARG DB_HOST
ARG DB_PORT
ARG DB_DATABASE
ARG DB_USERNAME
ARG DB_PASSWORD
ARG MAIL_MAILER
ARG MAIL_HOST
ARG MAIL_PORT
ARG MAIL_USERNAME
ARG MAIL_PASSWORD
ARG MAIL_ENCRYPTION
ARG MAIL_FROM_ADDRESS
ARG MAIL_FROM_NAME

# Convierte los args en variables de entorno
ENV DB_CONNECTION=${DB_CONNECTION}
ENV DB_HOST=${DB_HOST}
ENV DB_PORT=${DB_PORT}
ENV DB_DATABASE=${DB_DATABASE}
ENV DB_USERNAME=${DB_USERNAME}
ENV DB_PASSWORD=${DB_PASSWORD}
ENV MAIL_MAILER=${MAIL_MAILER}
ENV MAIL_HOST=${MAIL_HOST}
ENV MAIL_PORT=${MAIL_PORT}
ENV MAIL_USERNAME=${MAIL_USERNAME}
ENV MAIL_PASSWORD=${MAIL_PASSWORD}
ENV MAIL_ENCRYPTION=${MAIL_ENCRYPTION}
ENV MAIL_FROM_ADDRESS=${MAIL_FROM_ADDRESS}
ENV MAIL_FROM_NAME=${MAIL_FROM_NAME}

# Instala dependencias de Laravel y Node
RUN composer install --no-dev --optimize-autoloader \
    && npm install \
    && npm run build

EXPOSE 8000

# Comandos para inicializar Laravel y correr PHP-FPM
CMD php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache \
    && php artisan migrate --force \
    && php artisan db:seed --force \
    && php-fpm
