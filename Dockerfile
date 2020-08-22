FROM composer as composer
COPY . /app
RUN composer install --ignore-platform-reqs --no-scripts

# PHP Version environment variable
ARG PHP_VERSION

FROM php:7.1-fpm

# Application environment variable
ARG APP_ENV

# Set working directory
WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    zlib1g-dev \
    vim \
    nano \
    git \
    curl \
    wget \
    unzip \
    nodejs npm \
    imagemagick \
    ghostscript

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install pdo pdo_mysql zip exif pcntl

# Copy existing application directory permissions
COPY . /var/www/

# Set ownership to www-data
RUN chown -R www-data:www-data \
        /var/www/storage \
        /var/www/bootstrap/cache

COPY --from=composer /app/vendor /var/www/vendor
