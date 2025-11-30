FROM php:8.2-fpm

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
       git unzip pkg-config libssl-dev \
    && rm -rf /var/lib/apt/lists/*

# Instala a extensão binária do MongoDB e habilita
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

WORKDIR /var/www/html

