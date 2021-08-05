FROM php:7.4-cli

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Install unzip for composer dependencies
RUN apt-get update \
    && apt-get install -y --no-install-recommends unzip \
    && apt-get purge -y --autoremove

ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /root/.composer

COPY --from=composer:2.1.5 /usr/bin/composer /usr/bin/composer

WORKDIR /application

CMD ["php"]
