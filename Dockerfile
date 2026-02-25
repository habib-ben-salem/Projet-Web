FROM php:8.2-apache

# Installation des extensions PHP nécessaires
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Copier le code source dans le conteneur
COPY src/ /var/www/html/

# Script de démarrage qui fixe le MPM avant de lancer Apache
RUN echo '#!/bin/bash\n\
rm -f /etc/apache2/mods-enabled/mpm_event.load /etc/apache2/mods-enabled/mpm_event.conf \
      /etc/apache2/mods-enabled/mpm_worker.load /etc/apache2/mods-enabled/mpm_worker.conf\n\
a2enmod mpm_prefork rewrite 2>/dev/null || true\n\
exec apache2-foreground' > /start.sh && chmod +x /start.sh

EXPOSE 80

CMD ["/start.sh"]