FROM php:8.2-apache

# Instala lo mínimo
RUN apt-get update && apt-get install -y \
    unzip libzip-dev libpq-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# Configura Apache y permisos
COPY . /var/www/html
RUN chown -R www-data:www-data /var/www/html && \
    a2enmod rewrite

# Instala Composer y dependencias
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Puerto y directorio
WORKDIR /var/www/html
EXPOSE 80