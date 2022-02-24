FROM php:7.4-fpm

# php ini 파일 적용
# Use the default production configuration
# RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

WORKDIR /var/www/html/jpro-blog
COPY ./src /var/www/html/jpro-blog

# 운영은 볼륨 데이터가 아니기때문에 권한 부여
RUN chmod -R 755 ./
RUN chown -R www-data:www-data ./

# php 모듈 설치
RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd mysqli calendar exif pdo pdo_mysql shmop sockets

# git 설치
RUN apt-get install -y git

# composer 설치 및 경로 지정
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin/
RUN ln -s /usr/local/bin/composer.phar /usr/local/bin/composer
