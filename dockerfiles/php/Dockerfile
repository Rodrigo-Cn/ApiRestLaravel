FROM php:8.3-fpm-alpine3.19

RUN apk add --no-cache \
    bash \
    curl \
    libpng-dev \
    libzip-dev \
    zlib-dev \
    mariadb-dev  # Adicione esta linha para instalar o driver do MySQL

RUN docker-php-ext-install gd \
    && docker-php-ext-install zip \
    && docker-php-ext-install pdo pdo_mysql  # Adicione o PDO e PDO MySQL

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN apk add --no-cache nodejs npm

WORKDIR /var/www

EXPOSE 9000
