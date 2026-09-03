FROM php:8.5-fpm-alpine

ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN install-php-extensions pdo_pgsql gd zip bcmath intl opcache

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN { \
        echo 'opcache.enable=1'; \
        echo 'opcache.memory_consumption=128'; \
        echo 'opcache.validate_timestamps=0'; \
        echo 'opcache.max_accelerated_files=20000'; \
    } > /usr/local/etc/php/conf.d/opcache-prod.ini \
    && { \
        echo 'upload_max_filesize=25M'; \
        echo 'post_max_size=25M'; \
    } > /usr/local/etc/php/conf.d/uploads.ini

WORKDIR /var/www/html
