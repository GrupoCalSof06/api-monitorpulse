FROM php:8.2-apache

# 1. Instala dependencias PARA POSTGRES (cambio principal)
RUN apt-get update && apt-get install -y \
    unzip libzip-dev libpq-dev git \
    && docker-php-ext-install pdo pdo_pgsql zip opcache  # Cambiado pdo_mysql → pdo_pgsql

# 2. Configura Apache para Lumen (igual)
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf && \
    a2enmod rewrite

# 3. Crea configuración personalizada de Apache (igual)
RUN mkdir -p /etc/apache2/sites-available
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf
RUN a2ensite 000-default.conf

# 4. Copia el código y configura permisos (igual)
COPY . /var/www/html
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html/storage

# 5. Instala Composer y dependencias (igual)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# 6. Configuración final (igual)
WORKDIR /var/www/html
EXPOSE 80
