FROM php:7.1-cli

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Install unzip for composer dependencies
RUN apt-get update \
    && apt-get install -y --no-install-recommends unzip \
    && apt-get autoremove -y

ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /root/.composer

COPY --from=composer:1.10.10 /usr/bin/composer /usr/bin/composer

WORKDIR /application

CMD ["php"]