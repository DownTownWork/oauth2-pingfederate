ARG PHP_VERSION
ARG COMPOSER_VERSION=latest

FROM composer:${COMPOSER_VERSION} as composer_stage

FROM php:${PHP_VERSION}-cli-alpine

WORKDIR /var/www/package

RUN apk update && \
    apk upgrade && \
    apk add git zip

COPY --from=composer_stage /usr/bin/composer /usr/bin/composer
COPY command.sh /var/www/command.sh

RUN chmod +x /var/www/command.sh

CMD ["/var/www/command.sh"]
