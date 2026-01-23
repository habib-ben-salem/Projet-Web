FROM php:8.2-apache

RUN apt-get update \
 && apt-get install -y autoconf gcc make \
 && pecl install xdebug \
 && docker-php-ext-enable xdebug