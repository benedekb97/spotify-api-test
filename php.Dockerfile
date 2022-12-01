FROM php:7.4-fpm

RUN docker-php-ext-install pdo pdo_mysql

RUN pecl install -o -f redis \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis

RUN apt-get update && apt-get -y install cron zlib1g-dev libpng-dev git wget nano libicu-dev libzip-dev zip unzip

RUN docker-php-ext-install gd zip

COPY .cron /etc/cron.d/.cron

RUN chmod 0644 /etc/cron.d/.cron

RUN crontab /etc/cron.d/.cron

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');"

RUN mv composer.phar /usr/local/bin/composer

RUN cron
