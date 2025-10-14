#!/bin/bash
# Install MongoDB PHP extension
pecl install mongodb
echo "extension=mongodb.so" > /opt/render/.php/etc/php.ini

# Install Composer dependencies
composer install --no-dev --optimize-autoloader
