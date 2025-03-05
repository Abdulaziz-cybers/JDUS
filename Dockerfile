FROM php:8.3-cli

ARG UID=1000
ARG GID=1000

# Modify existing www-data user and group to match host UID and GID
RUN usermod -u ${UID} www-data && \
    groupmod -g ${GID} www-data

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libpq-dev \
    libonig-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_pgsql mbstring zip bcmath \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Retry mechanism for package installation (in case of network issues)
RUN for i in 1 2 3; do apt-get update && apt-get install -y --no-install-recommends \
    git curl zip unzip && break || sleep 5; done

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy application code (excluding unnecessary files)
COPY . .

# Install dependencies efficiently
RUN composer install --no-interaction --no-dev --optimize-autoloader || composer install --no-interaction --no-dev --optimize-autoloader

# Set correct permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage

USER www-data
