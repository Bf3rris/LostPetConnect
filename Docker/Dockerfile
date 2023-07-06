# Use the official PHP and Apache base image
FROM php:8.1.0-apache

# Set permissions recursively to 777 for all files and directories
RUN chmod -R 777 /var/www/
RUN chmod -R 777 /var/www/html


# Install MySQL extension for PHP
RUN docker-php-ext-install mysqli

# Install additional PHP extensions if needed
# RUN docker-php-ext-install <extension_name>

# Copy the Apache virtual host configuration file
COPY apache.conf /etc/apache2/sites-available/000-default.conf

# Enable Apache modules if needed
# RUN a2enmod <module_name>

# Copy the PHPMyAdmin configuration file
COPY config.inc.php /etc/phpmyadmin/config.inc.php

# Set the document root
WORKDIR /var/www/html

# Copy your website files to the document root
COPY ./website /var/www/html

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]