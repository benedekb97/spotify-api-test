FROM php:fpm

RUN docker-php-ext-install pdo pdo_mysql

RUN pecl install -o -f redis \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis

RUN apt-get update && apt-get -y install cron zlib1g-dev libpng-dev git wget nano

RUN curl -O https://phar.phpunit.de/phpunit-9.5.26.phar
RUN chmod +x phpunit-9.5.26.phar && mv phpunit-9.5.26.phar /usr/local/bin/phpunit

RUN docker-php-ext-install gd

COPY .cron /etc/cron.d/.cron

RUN chmod 0644 /etc/cron.d/.cron

RUN crontab /etc/cron.d/.cron

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');"

RUN mv composer.phar /usr/local/bin/composer

RUN cron