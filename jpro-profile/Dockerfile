FROM php:7.4-fpm as builder
WORKDIR /var/www/html
COPY ./src /var/www/html/jpro-profile

RUN chmod -R 777 ./
RUN chown -R www-data:www-data ./

RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

# FROM nginx
# EXPOSE 81
# COPY ./nginx/default.conf /etc/nginx/conf.d/default.conf
# COPY --from=builder /var/www/frontend/src /usr/share/nginx/html

# FROM php:7.4-cli
# COPY . /usr/src/myapp
# WORKDIR /usr/src/myapp
# CMD [ "php", "./index.php" ]

# FROM php:7.4-cli
# RUN docker-php-source extract \
#     # do important things \
#     && docker-php-source delete
