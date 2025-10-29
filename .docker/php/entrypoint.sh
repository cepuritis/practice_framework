#!/bin/sh
set -e

mkdir -p /sock
chown www-data:www-data /sock || true

if [ ! -d "/var/www/html/vendor" ]; then
    echo ">>> running composer install"
    echo 
    gosu ${UID}:${GID} composer install --no-interaction --prefer-dist
fi

if [ ! -d "/var/www/html/node_modules" ]; then
    echo ">>> running npm install/build"
    gosu ${UID}:${GID} npm install && npm run build:css
fi

echo ">>> starting php-fpm"
exec php-fpm -F -y /usr/local/etc/php-fpm.d/www.conf
