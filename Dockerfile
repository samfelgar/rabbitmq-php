FROM php:8

RUN docker-php-ext-install sockets

WORKDIR /var/www/html

CMD ['tail', '-f', '/dev/null']