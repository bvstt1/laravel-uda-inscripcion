FROM php:8.2-fpm
FROM elrincondeisma/octane:latest

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN php -v
RUN composer install --no-interaction --optimize-autoloader
RUN composer require laravel/octane spiral/roadrunner --no-interaction

COPY .env.example .env
RUN mkdir -p /app/storage/logs
RUN php artisan cache:clear
RUN php artisan view:clear
RUN php artisan config:clear
RUN php artisan octane:install --server="swoole"

CMD ["php", "artisan", "octane", "--server=swoole", "--host=0.0.0.0"]
EXPOSE 8000
