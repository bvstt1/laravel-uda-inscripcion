FROM elrincondeisma/octane:latest

# Instalar Composer desde la imagen oficial
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY --from=spiralscout/roadrunner:2.4.2 /usr/bin/rr /usr/bin/rr

# Establecer directorio de trabajo
WORKDIR /app

# Copiar archivos
COPY . .

# Instalar dependencias PHP de Composer
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress

# Copiar .env de ejemplo
COPY .env.example .env

# Crear carpetas necesarias
RUN mkdir -p /app/storage/logs

# Limpiar cachés de Laravel
RUN php artisan cache:clear \
    && php artisan view:clear \
    && php artisan config:clear

# Instalar Octane (si no lo tienes ya en composer.json)
RUN php artisan octane:install --server="swoole"

# Comando por defecto
CMD ["php", "artisan", "octane", "--server=swoole", "--host=0.0.0.0"]

EXPOSE 8000
