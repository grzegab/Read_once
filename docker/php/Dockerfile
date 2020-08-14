FROM php:7.4-fpm

MAINTAINER Grzegorz Gabryel <greg@grzegab.eu>

RUN apt-get update && apt-get install -y apt-transport-https git-all wget \
    libpq-dev zlib1g-dev libpng-dev libfreetype6-dev libjpeg62-turbo-dev \
    libxml2-dev libcurl4 libcurl4-openssl-dev \
    && rm -rf /var/lib/apt/lists/*

RUN pecl install xdebug-2.9.2
RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/
RUN docker-php-ext-install -j$(nproc) pgsql soap gd intl opcache
RUN docker-php-ext-install pdo pdo_pgsql pgsql pdo_mysql
RUN docker-php-ext-enable intl opcache xdebug

# Override with custom opcache settings
COPY xdebug.ini $PHP_INI_DIR/conf.d/
COPY php-additional.ini $PHP_INI_DIR/conf.d/

WORKDIR /var/www/html
