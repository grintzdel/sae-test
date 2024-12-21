FROM php:8.1.13-apache

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions intl gd && \
    curl -sSk https://getcomposer.org/installer | php -- --disable-tls && \
    mv composer.phar /usr/local/bin/composer && \
    apt update && apt install -yqq zip git libpng-dev

COPY . /var/www/

COPY ./docker/apache.conf /etc/apache2/sites-available/000-default.conf

ENV APP_ENV=prod

RUN rm -rf /var/www/vendor && \
    rm -rf /var/www/.git && \
    rm -rf /var/www/.env.local && \
    cd /var/www && \
    composer install && \
    php bin/console cache:clear && \
    php bin/console cache:warmup && \
    chown -R www-data:www-data /var/www

RUN echo "memory_limit=-1" > /usr/local/etc/php/conf.d/memory-limit.ini
RUN echo "max_execution_time=0" > /usr/local/etc/php/conf.d/execution-time-limit.ini

COPY public/.htpasswd /var/www/public/.htpasswd
COPY public/.htaccess /var/www/public/.htaccess
    
WORKDIR /var/www/

ENTRYPOINT ["bash", "./docker/docker.sh"]

EXPOSE 80