# FROM php:5.6-apache-stretch

FROM php:7.2-apache-stretch
COPY . /var/www

RUN apt-get update && \
    apt-get install -y wget git sqlite3 libxml2-dev && \
    docker-php-ext-install soap && \
    wget https://getcomposer.org/download/1.6.5/composer.phar -O /tmp/composer.phar && \
    cd /var/www && \
    mv log4php_docker.xml log4php.xml && \
    php /tmp/composer.phar install && \
    echo "date.timezone = 'UTC'" > /usr/local/etc/php/php.ini && \
    sqlite3 /var/www/data/db/demo.db < /var/www/data/db/demo.sqlite.dump && \
    chgrp www-data /var/www/data/db && \
    chmod g+w /var/www/data/db && \
    chgrp www-data /var/www/data/db/demo.db && \
    chmod g+w /var/www/data/db/demo.db && \
    rmdir /var/www/html && ln -s /var/www/public /var/www/html && \
    rm /tmp/composer.phar && \
    apt-get autoremove -f wget git -y && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*
