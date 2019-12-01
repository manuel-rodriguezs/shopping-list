FROM php:7.3-fpm-alpine

RUN apk update \
    && apk add --no-cache \
        git curl libmcrypt libmcrypt-dev openssh-client icu-dev libzip-dev \
        libxml2-dev freetype-dev libpng-dev libjpeg-turbo-dev g++ make autoconf \
        postgresql postgresql-contrib postgresql-dev\
    && docker-php-source extract \
    && docker-php-ext-install pdo_pgsql soap intl zip \
    && pecl install xdebug; \
       docker-php-ext-enable xdebug; \
       echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
       echo "display_startup_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
       echo "display_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
       echo "xdebug.remote_enable=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && rm -rf /tmp/*

CMD ["php-fpm", "-F"]

WORKDIR /var/www/shopping-list

EXPOSE 9000
