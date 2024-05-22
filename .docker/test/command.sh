cd /var/www/package
rm -rf vendor composer.lock
composer install
php ./vendor/bin/phpunit
