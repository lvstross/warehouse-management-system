FROM php:7.4-fpm-alpine

RUN apk add --update sqlite && rm -rf /var/cache/apk/*

RUN docker-php-ext-install pdo pdo_mysql sockets

RUN curl -sS https://getcomposer.org/installer​ | php -- \
     --install-dir=/usr/local/bin --filename=composer

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .

# RUN useradd -u 1234 my-user

# USER my-user

RUN composer install

RUN php artisan key:generate

RUN php artisan passport:keys

RUN touch database/database.sqlite

RUN php artisan migrate