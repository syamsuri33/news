# Gunakan PHP 8.2 dengan Apache
FROM php:8.2-apache

# Install ekstensi PHP yang dibutuhkan Yii2
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo_mysql zip mbstring

# Aktifkan mod_rewrite untuk Yii2
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy source code
COPY . /var/www/html

# Install dependency via composer
RUN composer install --no-dev --optimize-autoloader

# Ubah permission
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# 🔹 Konfigurasi Apache: arahkan ke folder /web
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/web|g' /etc/apache2/sites-available/000-default.conf \
 && sed -i 's|<Directory /var/www/>|<Directory /var/www/html/web/>|g' /etc/apache2/apache2.conf \
 && echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Expose port Apache
EXPOSE 80

# Jalankan apache
CMD ["apache2-foreground"]
