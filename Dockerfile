FROM php:fpm

RUN apt-get update \
     && apt-get install -y libzip-dev \
     && docker-php-ext-install zip

# RUN docker-php-ext-configure zip \
#     && docker-php-ext-install zip
# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
