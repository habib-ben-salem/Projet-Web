FROM php:8.2-apache

# Installation des extensions PHP nécessaires
RUN apt-get update \
 && apt-get install -y autoconf gcc make \
 && docker-php-ext-install pdo pdo_mysql mysqli

# Désactiver tous les MPM puis activer uniquement prefork
RUN a2dismod mpm_event mpm_worker mpm_prefork 2>/dev/null; \
    a2enmod mpm_prefork rewrite

# Copier le code source dans le conteneur (nécessaire pour Railway)
COPY src/ /var/www/html/

EXPOSE 80