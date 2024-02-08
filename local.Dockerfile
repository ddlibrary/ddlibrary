# PHP Version environment variable
ARG PHP_VERSION

FROM php:$PHP_VERSION-fpm

# Set working directory
WORKDIR /var/www/html

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    libicu-dev \
    jpegoptim optipng pngquant gifsicle \
    locales \
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
RUN docker-php-ext-configure intl
RUN docker-php-ext-install pdo pdo_mysql zip exif pcntl intl

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory permissions
COPY . /var/www/html

# Copy existing application directory permissions
COPY --chown=www:www . /var/www/html

# Change current user to www
USER www
