ARG PHP_VERSION

FROM php:${PHP_VERSION}-fpm-alpine

WORKDIR /var/www/package

RUN apk update \
 && apk upgrade \
 && apk add --no-cache linux-headers git zip $PHPIZE_DEPS \
 && pecl install xdebug \
 && docker-php-ext-enable xdebug

COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY command.sh /var/www/command.sh

RUN chmod +x /var/www/command.sh

CMD ["/var/www/command.sh"]
