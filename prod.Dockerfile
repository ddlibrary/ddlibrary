# PHP Version environment variable
ARG PHP_VERSION

FROM php:$PHP_VERSION-fpm

# Set working directory
WORKDIR /var/www/html

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
    ghostscript \
    poppler-utils

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install pdo pdo_mysql zip exif pcntl

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy existing application directory permissions
COPY . /var/www/html

# Set ownership to www-data
RUN chown -R www-data:www-data \
        /var/www/html/storage \
        /var/www/html/bootstrap/cache
