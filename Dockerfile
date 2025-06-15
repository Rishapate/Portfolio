# Use an official PHP-Apache image
FROM php:8.2-apache

# Enable Apache mod_rewrite (common in PHP apps)
RUN a2enmod rewrite

# Copy your project files to the Apache web root
COPY . /var/www/html/

# Suppress FQDN warning
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf