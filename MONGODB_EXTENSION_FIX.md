# MongoDB PHP Extension Installation Fix

## Current Issue
The MongoDB extension is failing to load with error:
```
Unable to load dynamic library 'mongodb' - The specified procedure could not be found
```

This means the new `php_mongodb.dll` requires dependencies that aren't in your XAMPP installation.

## Solution: Install Compatible Version

### Step 1: Remove the Incompatible Extension

1. Navigate to `C:\xampp\php\ext\`
2. Rename or remove the current `php_mongodb.dll`

### Step 2: Download the Correct Version

You need the MongoDB extension that matches:
- **PHP Version**: 8.0
- **Thread Safety**: TS (Thread Safe)
- **Architecture**: x64

**Download Link**: https://windows.php.net/downloads/pecl/releases/mongodb/

#### Recommended Versions for PHP 8.0:
- **mongodb-1.11.1-8.0-ts-vs16-x64.zip** (most compatible with XAMPP)
- **mongodb-1.12.1-8.0-ts-vs16-x64.zip**

### Step 3: Extract and Install

1. Download the ZIP file
2. Extract it
3. Copy **`php_mongodb.dll`** to `C:\xampp\php\ext\`
4. If the ZIP contains other DLL files (like `libsasl.dll`, `libssl.dll`, `libcrypto.dll`):
   - Copy them to `C:\xampp\php\` (same folder as php.exe)

### Step 4: Verify php.ini

Open `C:\xampp\php\php.ini` and ensure this line exists and is uncommented:
```ini
extension=mongodb
```

Or:
```ini
extension=php_mongodb.dll
```

### Step 5: Restart Apache

1. Open XAMPP Control Panel
2. Stop Apache
3. Start Apache

### Step 6: Verify Installation

Run this command in PowerShell:
```powershell
php -m | Select-String mongodb
```

You should see `mongodb` without any errors.

## Alternative: Use Pre-compiled XAMPP-Compatible Version

If the above doesn't work, download from PECL's stable releases:

1. Visit: https://pecl.php.net/package/mongodb/1.11.1/windows
2. Download: **1.11.1 - PHP 8.0 Thread Safe (TS) x64**
3. Follow steps 3-6 above

## Quick Test Commands

After installation, test with:

```powershell
# Check PHP version
php -v

# Check if mongodb extension loads
php -m | Select-String mongodb

# Check mongodb extension version
php -r "echo phpversion('mongodb');"
```

## If Issues Persist

### Option A: Rollback to Working Version

If you had a working version before:
1. Restore the old `php_mongodb.dll` from your backup
2. Restart Apache
3. Use the old version (even if outdated, it may work better with XAMPP)

### Option B: Use Local MongoDB

Instead of fighting with extensions:
1. Install MongoDB Community Server locally
2. Update `.env`:
   ```
   MONGODB_URI=mongodb://localhost:27017
   MONGODB_DATABASE=swordhub
   ```
3. No SSL/TLS issues with local MongoDB

## Need Help?

Run these diagnostic commands and share the output:

```powershell
# Check PHP info
php -i | Select-String "MongoDB"

# Check loaded extensions
php -m

# Check DLL dependencies (requires Dependency Walker or similar tool)
# Download from: https://www.dependencywalker.com/
```

---

**Next Step**: Once the extension loads correctly, run `php test-connection.php` again.
