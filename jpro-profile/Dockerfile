FROM php:7.4-fpm as builder
WORKDIR /var/www/html
COPY ./src /var/www/html/jpro-profile

RUN chmod -R 755 ./
RUN chown -R www-data:www-data ./

RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

RUN apt-get install -y git
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin/
RUN ln -s /usr/local/bin/composer.phar /usr/local/bin/composer
