FROM php:8.2-apache

# Installation des extensions PHP nécessaires
RUN apt-get update \
 && apt-get install -y autoconf gcc make \
 && docker-php-ext-install pdo pdo_mysql mysqli \
 && pecl install xdebug \
 && docker-php-ext-enable xdebug

# Activation du module rewrite d'Apache pour les URLs propres
RUN a2enmod rewrite