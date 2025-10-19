# Use official PHP 8.1 with Apache
FROM php:8.1-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    libssl-dev

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Install MongoDB PHP extension
RUN pecl install mongodb && docker-php-ext-enable mongodb

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Enable Apache modules and disable autoindex
RUN a2enmod rewrite headers env setenvif && \
    a2dismod -f autoindex

# Set working directory
WORKDIR /var/www/html

# Copy application files FIRST (so composer can see the directories)
COPY . /var/www/html

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist --ignore-platform-reqs

# Verify autoload was generated and show directory structure
RUN ls -la /var/www/html/vendor/composer/ && \
    echo "=== Checking autoload files ===" && \
    ls -la /var/www/html/vendor/autoload.php && \
    echo "=== Application directories ===" && \
    ls -la /var/www/html/ | grep -E "(controllers|models|middleware|config|routes|utils)"

# Create uploads directory and set permissions
RUN mkdir -p /var/www/html/uploads/products && \
    chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html && \
    chmod -R 755 /var/www/html/uploads

# Configure Apache
COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf

# Enable additional Apache modules and configure
RUN a2enmod env setenvif && \
    a2dissite 000-default && \
    a2ensite 000-default

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
