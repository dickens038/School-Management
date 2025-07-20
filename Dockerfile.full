# Use PHP 8.2 with Apache (more stable for deployment)
FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libwebp-dev \
    libicu-dev \
    nodejs \
    npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl \
    && a2enmod rewrite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files first for better caching
COPY composer.json composer.lock ./

# Install PHP dependencies with comprehensive error handling
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist --memory-limit=-1 --no-progress --verbose || \
    (composer clear-cache && composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist --memory-limit=-1 --no-progress --verbose) || \
    (composer self-update && composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist --memory-limit=-1 --no-progress --verbose)

# Copy package files for Node.js
COPY package.json package-lock.json ./

# Install Node.js dependencies
RUN npm ci --only=production

# Copy application files
COPY . .

# Build frontend assets
RUN npm run build

# Remove Node.js and npm to reduce image size
RUN apt-get remove -y nodejs npm && apt-get autoremove -y

# Create .env file if it doesn't exist
RUN cp .env.example .env || true

# Generate application key
RUN php artisan key:generate --no-interaction || true

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Configure Apache for Laravel
RUN echo '<VirtualHost *:80>\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Clear and cache Laravel
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"] 