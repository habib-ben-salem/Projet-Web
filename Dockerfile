FROM php:8.2-apache

# Installation des extensions PHP nécessaires
RUN apt-get update \
 && apt-get install -y autoconf gcc make \
 && docker-php-ext-install pdo pdo_mysql mysqli

# Fix MPM conflict : supprimer tous les MPM sauf prefork
RUN rm -f /etc/apache2/mods-enabled/mpm_event.load \
          /etc/apache2/mods-enabled/mpm_event.conf \
          /etc/apache2/mods-enabled/mpm_worker.load \
          /etc/apache2/mods-enabled/mpm_worker.conf \
 && ln -sf /etc/apache2/mods-available/mpm_prefork.load /etc/apache2/mods-enabled/mpm_prefork.load \
 && ln -sf /etc/apache2/mods-available/mpm_prefork.conf /etc/apache2/mods-enabled/mpm_prefork.conf 2>/dev/null; true

# Activer le module rewrite
RUN a2enmod rewrite

# Copier le code source dans le conteneur (nécessaire pour Railway)
COPY src/ /var/www/html/

EXPOSE 80

CMD ["apache2-foreground"]