FROM php:8.2-cli-bullseye

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /srv/app

RUN apt update && apt install -y \
    libzip-dev \
    libonig-dev \
    libgmp-dev \
    libicu-dev \
    libcurl3-dev \
    libssh-dev \
    vim \
    make

RUN docker-php-ext-install \
    zip \
    bcmath \
    intl \
    curl \
    gmp \
    opcache \
    mbstring
