# Basis-Image
FROM php:8.2-apache

# DocumentRoot auf den "public"-Ordner ändern
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Kugelsicheres Routing ohne .htaccess:
# FallbackResource leitet alle Anfragen, die keine echten Dateien sind, auf index.php um.
RUN echo "<Directory ${APACHE_DOCUMENT_ROOT}>\n\
    FallbackResource /index.php\n\
</Directory>" > /etc/apache2/conf-available/routing.conf \
    && a2enconf routing

# Projektdateien kopieren (Fallback für Systeme ohne Volumes)
COPY . /var/www/html/

# Berechtigungen setzen
RUN chown -R www-data:www-data /var/www/html/