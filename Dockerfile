FROM php:8.1-apache

# Installer PDO MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Configurer les permissions
RUN chown -R www-data:www-data /var/www/html
