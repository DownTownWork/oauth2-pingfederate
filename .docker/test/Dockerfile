ARG PHP_VERSION

FROM php:${PHP_VERSION}-cli-alpine

WORKDIR /var/www/package

RUN apk update && \
    apk upgrade && \
    apk add git zip

COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY command.sh /var/www/command.sh

RUN chmod +x /var/www/command.sh

CMD ["/var/www/command.sh"]
