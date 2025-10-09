# MongoDB PHP Extension Installation Guide

## For Windows (XAMPP)

### Step 1: Download MongoDB PHP Extension

1. Visit: https://pecl.php.net/package/mongodb
2. Click on "DLL" link for your PHP version
3. Download the appropriate version:
   - For PHP 8.0: `php_mongodb-1.20.0-8.0-ts-x64.zip` (Thread Safe)
   - For PHP 7.4: `php_mongodb-1.15.0-7.4-ts-x64.zip` (Thread Safe)

### Step 2: Install the Extension

1. Extract the downloaded ZIP file
2. Copy `php_mongodb.dll` to: `C:\xampp\php\ext\`
3. Open `C:\xampp\php\php.ini` in a text editor
4. Add this line: `extension=mongodb`
5. Save and close the file

### Step 3: Restart Apache

1. Open XAMPP Control Panel
2. Stop Apache
3. Start Apache

### Step 4: Verify Installation

Run this command:
```bash
php -m | findstr mongodb
```

You should see "mongodb" in the output.

### Step 5: Install Composer Dependencies

```bash
cd c:\xampp\htdocs\SwordHub-backend
composer install
```

## For Linux (Ubuntu/Debian)

```bash
# Install MongoDB PHP extension
sudo apt-get update
sudo apt-get install php-mongodb php-dev pkg-config

# Or using PECL
sudo pecl install mongodb

# Add extension to php.ini
echo "extension=mongodb.so" | sudo tee -a /etc/php/8.0/cli/php.ini
echo "extension=mongodb.so" | sudo tee -a /etc/php/8.0/apache2/php.ini

# Restart Apache
sudo systemctl restart apache2

# Install Composer dependencies
cd /path/to/SwordHub-backend
composer install
```

## For macOS

```bash
# Install MongoDB PHP extension using PECL
pecl install mongodb

# Add extension to php.ini
echo "extension=mongodb.so" >> /usr/local/etc/php/8.0/php.ini

# Restart Apache
brew services restart httpd

# Install Composer dependencies
cd /path/to/SwordHub-backend
composer install
```

## Troubleshooting

### Error: "ext-mongodb is missing"

This means the MongoDB PHP extension is not installed or not enabled.

**Solution:**
1. Follow the installation steps above
2. Verify `extension=mongodb` is in your php.ini
3. Restart your web server
4. Run `php -m | grep mongodb` to verify

### Error: "Cannot find php_mongodb.dll"

The DLL file is not in the correct location.

**Solution:**
1. Ensure `php_mongodb.dll` is in `C:\xampp\php\ext\`
2. Check the file name is exactly `php_mongodb.dll`
3. Verify the extension matches your PHP version (8.0, 7.4, etc.)

### Error: "The specified module could not be found"

You may have downloaded the wrong version (Thread Safe vs Non-Thread Safe).

**Solution:**
1. Check if your PHP is Thread Safe: `php -i | findstr "Thread"`
2. Download the matching version (TS for Thread Safe, NTS for Non-Thread Safe)
3. For XAMPP, use Thread Safe (TS) version

## Quick Test After Installation

Create a file `test_mongodb.php`:

```php
<?php
if (extension_loaded('mongodb')) {
    echo "MongoDB extension is loaded!\n";
    try {
        $client = new MongoDB\Driver\Manager("mongodb://localhost:27017");
        echo "Successfully connected to MongoDB!\n";
    } catch (Exception $e) {
        echo "Connection failed: " . $e->getMessage() . "\n";
    }
} else {
    echo "MongoDB extension is NOT loaded.\n";
}
```

Run: `php test_mongodb.php`

## Alternative: Install MongoDB

If you don't have MongoDB installed:

### Windows
1. Download: https://www.mongodb.com/try/download/community
2. Run the installer
3. Install as a Windows Service
4. MongoDB will start automatically

### Linux
```bash
# Import MongoDB public GPG key
wget -qO - https://www.mongodb.org/static/pgp/server-6.0.asc | sudo apt-key add -

# Add MongoDB repository
echo "deb [ arch=amd64,arm64 ] https://repo.mongodb.org/apt/ubuntu focal/mongodb-org/6.0 multiverse" | sudo tee /etc/apt/sources.list.d/mongodb-org-6.0.list

# Install MongoDB
sudo apt-get update
sudo apt-get install -y mongodb-org

# Start MongoDB
sudo systemctl start mongod
sudo systemctl enable mongod
```

### macOS
```bash
# Install using Homebrew
brew tap mongodb/brew
brew install mongodb-community@6.0

# Start MongoDB
brew services start mongodb-community@6.0
```

## After Setup

Once MongoDB and the PHP extension are installed:

```bash
# Navigate to project directory
cd c:\xampp\htdocs\SwordHub-backend

# Install dependencies
composer install

# Seed database
php seed.php

# Start using the API!
```

## Need Help?

If you encounter issues:
1. Check PHP version: `php -v`
2. Check loaded extensions: `php -m`
3. Check php.ini location: `php --ini`
4. Check Apache error logs: `C:\xampp\apache\logs\error.log`
