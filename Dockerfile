# Basis-Image
FROM php:8.2-apache

# Modul aktivieren
RUN a2enmod rewrite

# DocumentRoot setzen
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# Die Konfiguration direkt ins Image kopieren
COPY vhost.conf /etc/apache2/sites-available/000-default.conf

# Projektdateien kopieren
COPY . /var/www/html/

# Berechtigungen setzen
RUN chown -R www-data:www-data /var/www/html/