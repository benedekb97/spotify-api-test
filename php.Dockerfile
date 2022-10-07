FROM php:fpm

RUN docker-php-ext-install pdo pdo_mysql

RUN pecl install -o -f redis \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis

RUN apt-get update && apt-get -y install cron

COPY .cron /etc/cron.d/.cron

RUN chmod 0644 /etc/cron.d/.cron

RUN crontab /etc/cron.d/.cron
#
#RUN touch /var/log/cron.log
#
#CMD cron && tail -f /var/log/cron.log