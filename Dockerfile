# PHP Version environment variable
ARG PHP_VERSION

FROM php:$PHP_VERSION-fpm

# Application environment variable
ARG APP_ENV

# Set working directory
WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    nano\
    unzip \
    git \
    curl \
    nodejs npm \
    imagemagick \
    ghostscript

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install pdo pdo_mysql zip exif pcntl
RUN docker-php-ext-configure gd --with-gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ --with-png-dir=/usr/include/
RUN docker-php-ext-install gd

# Install xdebug and enable it if the development environment is local
RUN if [ $APP_ENV = "local" ]; then \
   pecl install xdebug; \
   docker-php-ext-enable xdebug; \
fi;

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy existing application directory permissions
COPY . /var/www/

# Set ownership to www-data
RUN chown -R www-data:www-data \
        /var/www/storage \
        /var/www/bootstrap/cache
