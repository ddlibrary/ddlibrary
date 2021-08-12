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
    mysql-client

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-configure intl
RUN docker-php-ext-install pdo pdo_mysql zip exif pcntl intl

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy existing application directory permissions
COPY . /var/www/html

# Set ownership to www-data
RUN chown -R www-data:www-data \
        /var/www/html/storage \
        /var/www/html/bootstrap/cache
