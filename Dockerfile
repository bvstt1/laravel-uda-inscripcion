FROM edbizarro/octane:8.2

WORKDIR /app
COPY . .

# Composer ya está instalado en esta imagen
RUN composer install --no-interaction --prefer-dist --optimize-autoloader
RUN composer require laravel/octane spiral/roadrunner

COPY .env.example .env
RUN mkdir -p storage/logs

RUN php artisan cache:clear
RUN php artisan view:clear
RUN php artisan config:clear
RUN php artisan octane:install --server="swoole"

EXPOSE 8000
CMD ["php", "artisan", "octane", "--server=swoole", "--host=0.0.0.0"]
