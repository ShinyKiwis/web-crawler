FROM php:fpm

# Install necessary extensions
RUN apt-get update && \
    apt-get install -y \
        libzip-dev \
        zip \
  && docker-php-ext-install zip

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
