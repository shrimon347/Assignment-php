FROM php:7.4-apache

# Install required PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy the application files into the container
COPY . /var/www/html/

# Set permissions
RUN chown -R www-data:www-data /var/www/html