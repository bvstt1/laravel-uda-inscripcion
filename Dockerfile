# 1️⃣ Imagen base Octane
FROM elrincondeisma/octane:latest

# 2️⃣ Instalar dependencias del sistema y extensiones PHP necesarias
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql zip mbstring bcmath opcache \
    && rm -rf /var/lib/apt/lists/*

# 3️⃣ Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 4️⃣ Instalar RoadRunner (si no está incluido)
COPY --from=spiralscout/roadrunner:2.4.2 /usr/bin/rr /usr/bin/rr

# 5️⃣ Directorio de trabajo
WORKDIR /app

# 6️⃣ Copiar archivos del proyecto
COPY . .

# 7️⃣ Copiar .env de ejemplo si no existe
RUN cp .env.example .env

# 8️⃣ Instalar dependencias PHP vía Composer
RUN composer install --no-dev --optimize-autoloader --no-interaction -vvv

# 9️⃣ Crear carpetas necesarias
RUN mkdir -p storage/logs \
    && php artisan cache:clear \
    && php artisan view:clear \
    && php artisan config:clear

# 10️⃣ Instalar Octane (solo si no está en composer.json)
RUN php artisan octane:install --server="swoole"

# 11️⃣ Exponer puerto
EXPOSE 8000

# 12️⃣ Comando por defecto
CMD ["php", "artisan", "octane", "--server=swoole", "--host=0.0.0.0"]
