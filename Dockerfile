# Basis-Image: Ein offizielles, schlankes PHP 8.2 Image mit integriertem Apache-Webserver
FROM php:8.2-apache

# 1. Apache mod_rewrite aktivieren (Zwingend notwendig für unser index.php Routing!)
RUN a2enmod rewrite

# 2. DocumentRoot auf den "public"-Ordner ändern (Sicherheits-Best-Practice!)
# So sind /src, /templates und /data niemals direkt über den Browser erreichbar.
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 3. Das gesamte lokale Projekt in den Container kopieren
COPY . /var/www/html/

# 4. Berechtigungen setzen, damit der Webserver die Dateien lesen (und ggf. schreiben) kann
RUN chown -R www-data:www-data /var/www/html/