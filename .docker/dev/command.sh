printf "xdebug.mode=coverage\n" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
cd /var/www/package
rm -rf vendor composer.lock
composer install
php-fpm -F -R
