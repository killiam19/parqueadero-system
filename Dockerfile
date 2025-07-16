FROM php:8.2-apache

# Instala extensiones necesarias
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copia tu código PHP
COPY public/ /var/www/html/

# Habilita módulos de Apache si necesitas
RUN a2enmod rewrite
