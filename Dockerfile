ARG PHP_VERSION=7.2
FROM php:${PHP_VERSION}-apache-stretch

COPY composer.json /var/www
RUN apt-get update && \
    apt-get install -y wget git sqlite3 libxml2-dev libpq-dev && \
    docker-php-ext-install soap pdo pdo_mysql pdo_pgsql && \
    wget https://getcomposer.org/download/1.6.5/composer.phar -O /tmp/composer.phar && \
    cd /var/www && \
    php /tmp/composer.phar install && \
    echo "date.timezone = 'UTC'" > /usr/local/etc/php/php.ini && \
    rm /tmp/composer.phar && \
    apt-get autoremove -f wget git -y && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

COPY . /var/www
RUN cd /var/www && \
    mv log4php_docker.xml log4php.xml && \
    rmdir /var/www/html && ln -s /var/www/public /var/www/html

EXPOSE 80