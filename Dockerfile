FROM php:8.2-apache

# Installation des extensions PHP nécessaires
RUN apt-get update \
 && apt-get install -y autoconf gcc make \
 && docker-php-ext-install pdo pdo_mysql mysqli

# Fix MPM: supprimer TOUS les fichiers MPM puis laisser Apache utiliser son défaut (prefork)
RUN rm -f /etc/apache2/mods-enabled/mpm_*.load /etc/apache2/mods-enabled/mpm_*.conf \
 && a2enmod mpm_prefork rewrite

# Copier le code source dans le conteneur
COPY src/ /var/www/html/

EXPOSE 80

CMD ["apache2-foreground"]