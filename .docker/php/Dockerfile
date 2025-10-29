FROM php:8.4-fpm

WORKDIR /var/www/html

RUN docker-php-ext-install pdo pdo_mysql \
    && pecl install redis xdebug-3.4.7 \
    && docker-php-ext-enable redis xdebug

RUN apt-get update && apt-get install -y \
        unzip curl git gosu && rm -rf /var/lib/apt/lists/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ARG NODE_VERSION=22

RUN curl -sLS https://deb.nodesource.com/setup_$NODE_VERSION.x | bash - \
    && apt-get install -y nodejs

COPY entrypoint.sh /usr/local/bin/entrypoint.sh

COPY www.conf /usr/local/etc/php-fpm.d/www.conf

RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT [ "/usr/local/bin/entrypoint.sh" ]
