FROM php:fpm

# Install necessary extensions
RUN apt-get update && \
    apt-get install -y \
        libzip-dev \
        zip \
  && docker-php-ext-install zip

# Enable the ZipArchive extension
RUN docker-php-ext-enable zip
# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
