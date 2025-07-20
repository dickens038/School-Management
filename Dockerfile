# Use PHP 8.2 FPM Alpine as base image
FROM php:8.2-fpm-alpine AS base

# Install system dependencies
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    oniguruma-dev \
    libzip-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    icu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl \
    && docker-php-ext-enable pdo_mysql mbstring exif pcntl bcmath gd zip intl

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files
COPY composer.json composer.lock ./

# Install PHP dependencies with better error handling
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist --no-progress

# Copy application files
COPY . .

# Create .env file if it doesn't exist
RUN cp .env.example .env || true

# Generate application key (only if .env exists and APP_KEY is not set)
RUN php artisan key:generate --no-interaction || true

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Production stage
FROM base AS production

# Install Node.js and npm for frontend build
RUN apk add --no-cache nodejs npm

# Copy package files
COPY package.json package-lock.json ./

# Install Node.js dependencies
RUN npm ci --only=production

# Copy frontend source files
COPY resources/ ./resources/
COPY vite.config.js ./

# Build frontend assets
RUN npm run build

# Remove Node.js and npm (not needed in production)
RUN apk del nodejs npm

# Clear cache and optimize
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Expose port 9000 for PHP-FPM
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]

# Development stage
FROM base AS development

# Install Node.js and npm for development
RUN apk add --no-cache nodejs npm

# Copy package files
COPY package.json package-lock.json ./

# Install Node.js dependencies
RUN npm install

# Copy frontend source files
COPY resources/ ./resources/
COPY vite.config.js ./

# Expose port 9000 for PHP-FPM
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"] 