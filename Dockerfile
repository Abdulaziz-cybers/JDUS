FROM php:8.3-cli

ARG UID=1000
ARG GID=1000

# Modify existing www-data user and group to match host UID and GID
RUN usermod -u ${UID} www-data && groupmod -g ${GID} www-data

# Ensure sources.list exists before modifying it
RUN [ -f /etc/apt/sources.list ] || echo "deb http://deb.debian.org/debian bookworm main" > /etc/apt/sources.list
RUN sed -i 's|http://deb.debian.org|http://ftp.debian.org|g' /etc/apt/sources.list

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y --no-install-recommends \
    git curl zip unzip \
    libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libxml2-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy application code
COPY . ./var/www


# Set correct permissions
RUN mkdir -p /var/www/html/storage && \
    chown -R www-data:www-data /var/www/html && \
    chmod -R 775 /var/www/html/storage

USER www-data
