FROM php:8.3-cli
RUN echo "upload_max_filesize = 25M\npost_max_size = 30M\nmemory_limit = 256M" > /usr/local/etc/php/conf.d/uploads.ini

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    zip \
    nodejs \
    npm \
    && docker-php-ext-install zip pdo pdo_mysql gd

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build

EXPOSE 10000

CMD php artisan serve --host=0.0.0.0 --port=10000