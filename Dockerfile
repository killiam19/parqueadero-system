FROM php:8.2-apache

# Instala dependencias de Composer
RUN apt-get update && apt-get install -y unzip git curl

# Instala Composer
RUN curl -sS https://getcomposer.org/installer | php && \
    mv composer.phar /usr/local/bin/composer

# Copia tu proyecto
COPY . /var/www/html

# Instala dependencias de Composer
RUN composer install --no-dev --optimize-autoloader

# Habilita mod_rewrite (si usas .htaccess)
RUN a2enmod rewrite
