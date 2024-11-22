# Use the official PHP image with Apache
FROM php:8.2-apache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy the API PHP file and .htaccess to the web root
COPY api.php /var/www/html/
COPY .htaccess /var/www/html/

# Set permissions
RUN chown -R www-data:www-data /var/www/html

# Expose the default Apache HTTP port
EXPOSE 80