FROM php:8.3-fpm

# Laravel + Playwright
RUN apt-get update && apt-get install -y --no-install-recommends \
    zip unzip git curl libpng-dev libonig-dev libxml2-dev libzip-dev \
    libx11-xcb1 libxrandr2 libxcomposite1 libxcursor1 libxdamage1 \
    libxfixes3 libxi6 libgtk-3-0 libatk1.0-0 libasound2 libdbus-1-3 \
    libcups2 libpangocairo-1.0-0 libpango-1.0-0 libnss3 libxss1 \
    fonts-liberation lsb-release ca-certificates libxext6 libxrender1 libxinerama1 \
    mesa-utils libgl1 libglib2.0-0 libatk-bridge2.0-0 \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl \
    && docker-php-ext-enable pdo_mysql

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Node + Playwright
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm@latest \
    && npm install -D @playwright/test \
    && npx playwright install --with-deps
