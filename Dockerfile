FROM php:8.1.25-apache

RUN docker-php-ext-install pdo pdo_mysql

# Activar mod_rewrite
RUN a2enmod rewrite

# Cambiar DocumentRoot a /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# Permitir .htaccess
RUN printf "<Directory /var/www/html/public>\n\
    AllowOverride All\n\
</Directory>\n" >> /etc/apache2/apache2.conf
