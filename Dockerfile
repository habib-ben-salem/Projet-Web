FROM php:8.2-apache

# Installation des extensions PHP nécessaires
RUN apt-get update \
 && apt-get install -y autoconf gcc make \
 && docker-php-ext-install pdo pdo_mysql mysqli \
 && pecl install xdebug \
 && docker-php-ext-enable xdebug

# Désactiver les MPM en conflit et activer prefork + rewrite
RUN a2dismod mpm_event && a2enmod mpm_prefork && a2enmod rewrite

# Copier le code source dans le conteneur (nécessaire pour Railway)
COPY src/ /var/www/html/

EXPOSE 80