FROM php:8.2-fpm

# Instala Composer desde la imagen oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copia el código fuente
COPY . .

# Muestra la versión de PHP en los logs de build
RUN php -v

# Instala dependencias de Composer
RUN composer install --no-interaction --optimize-autoloader

# Instala Octane y RoadRunner/Swoole
RUN composer require laravel/octane spiral/roadrunner --no-interaction

# Copia el ejemplo de entorno y prepara directorios
COPY .env.example .env
RUN mkdir -p /app/storage/logs

# Limpia caches de Laravel
RUN php artisan cache:clear
RUN php artisan view:clear
RUN php artisan config:clear

# Instala Octane (por defecto uso Swoole, cambia --server si prefieres RoadRunner)
RUN php artisan octane:install --server="swoole"

# Comando de arranque
CMD ["php", "artisan", "octane", "--server=swoole", "--host=0.0.0.0"]
EXPOSE 8000