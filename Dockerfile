FROM elrincondeisma/octane:latest

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar proyecto
WORKDIR /app
COPY . .

# Limpiar vendor y lock (opcional, si quieres forzar re-instalar)
RUN rm -rf vendor composer.lock || true

# Instalar dependencias
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Instalar Octane y RoadRunner
RUN composer require laravel/octane spiral/roadrunner

# Configuración inicial de Laravel
COPY .env.example .env
RUN mkdir -p storage/logs
RUN php artisan cache:clear
RUN php artisan view:clear
RUN php artisan config:clear
RUN php artisan octane:install --server="swoole"

# Exponer puerto y comando
EXPOSE 8000
CMD ["php", "artisan", "octane", "--server=swoole", "--host=0.0.0.0"]
