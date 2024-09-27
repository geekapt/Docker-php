# Use the official PHP image with Apache
FROM php:8.1-apache

# Install necessary dependencies for mysqli and other extensions
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libicu-dev \
    libonig-dev \
    && docker-php-ext-install mysqli \
    && docker-php-ext-enable mysqli \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite (optional if needed)
RUN a2enmod rewrite

# Copy the PHP application code to the Apache document root
COPY app/ /var/www/html/

# Copy custom php.ini if needed
COPY php.ini /usr/local/etc/php/php.ini

# Set file permissions for Apache
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Expose port 80 for the web service
EXPOSE 80

