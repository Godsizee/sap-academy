# Basis-Image
FROM php:8.2-apache

# 1. mod_rewrite aktivieren
RUN a2enmod rewrite

# 2. DocumentRoot Variablen setzen
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# 3. VirtualHost mit fest integrierten Rewrite-Regeln erstellen (ersetzt die .htaccess)
RUN echo "<VirtualHost *:80>\n\
    DocumentRoot ${APACHE_DOCUMENT_ROOT}\n\
    <Directory ${APACHE_DOCUMENT_ROOT}>\n\
        Options Indexes FollowSymLinks\n\
        AllowOverride None\n\
        Require all granted\n\
        \n\
        # Routing-Logik direkt im Server-Kern\n\
        RewriteEngine On\n\
        RewriteCond %{REQUEST_FILENAME} !-f\n\
        RewriteCond %{REQUEST_FILENAME} !-d\n\
        RewriteRule ^(.*)$ index.php [QSA,L]\n\
    </Directory>\n\
    ErrorLog \${APACHE_LOG_DIR}/error.log\n\
    CustomLog \${APACHE_LOG_DIR}/access.log combined\n\
</VirtualHost>" > /etc/apache2/sites-available/000-default.conf

# 4. Projektdateien kopieren
COPY . /var/www/html/

# 5. Berechtigungen setzen
RUN chown -R www-data:www-data /var/www/html/