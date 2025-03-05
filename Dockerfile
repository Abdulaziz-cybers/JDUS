FROM php:8.3-cli

ARG UID=1000
ARG GID=1000

# Modify existing www-data user and group to match host UID and GID
RUN usermod -u ${UID} www-data && groupmod -g ${GID} www-data

# Switch to a faster mirror for package installation
RUN sed -i 's|http://deb.debian.org|http://ftp.debian.org|g' /etc/apt/sources.list

# Install system dependencies in separate steps for debugging
RUN apt-get update
RUN apt-get install -y --no-install-recommends git curl zip unzip
RUN apt-get install -y --no-install-recommends libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libxml2-dev

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install -j$(nproc) gd pdo mbstring zip bcmath

# Clean up APT cache to reduce image size
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy application code (excluding unnecessary files)
COPY . .

# Install dependencies efficiently
RUN composer install --no-interaction --no-dev --optimize-autoloader || composer install --no-interaction --no-dev --optimize-autoloader

# Set correct permissions
RUN chown -R www-data:www-data /var/www/html && chmod -R 775 /var/www/html/storage

USER www-data
