# MongoDB Atlas Connection Status

## ✅ Configuration Completed

Your SwordHub backend has been configured to connect to MongoDB Atlas:

### Files Updated:
1. **`.env`** - MongoDB Atlas connection string added
2. **`MongoDB.php`** - TLS/SSL configuration added
3. **`composer.json`** - MongoDB PHP library installed (v1.10.1)

### Connection Details:
```
URI: mongodb+srv://SWORDHUB:sword1234@cluster0.ryhv1ve.mongodb.net/
Database: swordhub
```

## ⚠️ Current Issue: TLS/SSL Handshake Error

The connection is failing due to XAMPP's OpenSSL version not being compatible with MongoDB Atlas's TLS requirements.

**Error**: `TLS handshake failed: error:14094438:SSL routines:ssl3_read_bytes:tlsv1 alert internal error`

## 🔧 Recommended Solutions

### Option 1: Update Your MongoDB Extension (Recommended)

The current MongoDB extension (1.11.1) might be outdated. Update it:

1. Download the latest MongoDB extension for PHP 8.0 (TS x64):
   - Visit: https://windows.php.net/downloads/pecl/releases/mongodb/
   - Download the appropriate version for PHP 8.0 Thread Safe (TS) x64

2. Extract and replace `php_mongodb.dll` in `C:\xampp\php\ext\`

3. Restart Apache from XAMPP Control Panel

4. Test connection again

### Option 2: Use Local MongoDB (For Development)

If the TLS issue persists, you can use a local MongoDB instance for development:

1. **Install MongoDB Community Server**:
   - Download from: https://www.mongodb.com/try/download/community
   - Install with default settings
   - MongoDB will run on `localhost:27017`

2. **Update `.env` file**:
   ```
   MONGODB_URI=mongodb://localhost:27017
   MONGODB_DATABASE=swordhub
   ```

3. **Import data** (if needed):
   ```powershell
   # Run the seed script
   php seed.php
   ```

### Option 3: Switch to Different Hosting Environment

XAMPP has known SSL/TLS issues with MongoDB Atlas. Consider:

- **Use Docker**: Run PHP with proper OpenSSL in a container
- **Use WAMP**: Often has newer OpenSSL versions
- **Use Laragon**: Better OpenSSL support
- **Deploy to cloud**: Heroku, AWS, DigitalOcean (production)

### Option 4: Update XAMPP's OpenSSL Libraries

This is more advanced but can fix the issue:

1. Backup current DLL files in `C:\xampp\php\`:
   - `libssl-1_1-x64.dll`
   - `libcrypto-1_1-x64.dll`

2. Download newer OpenSSL DLLs from https://slproweb.com/products/Win32OpenSSL.html

3. Replace the DLLs in `C:\xampp\php\`

4. Restart Apache

⚠️ **Warning**: This can break other XAMPP components if not done correctly.

## ✅ What's Already Working

The backend code is properly configured and will work once the SSL/TLS issue is resolved. All these are ready:

- ✅ MongoDB connection class (`MongoDB.php`)
- ✅ Environment variables (`.env`)
- ✅ Composer dependencies installed
- ✅ TLS configuration added
- ✅ Models (User, Product, Category, Order)
- ✅ Controllers and Routes
- ✅ Middleware (Auth, Admin, Rate Limiter)

## 🧪 Testing After Fix

Once you apply one of the solutions above, test with:

```powershell
cd C:\xampp\htdocs\SwordHUB\SwordHub-backend
php test-connection.php
```

You should see:
```
✓ Successfully connected to MongoDB Atlas!
Database Name: swordhub
✓ Connection test completed successfully!
```

## 📝 Next Steps

1. **Choose a solution** from above (I recommend Option 2 for quick development)
2. **Apply the fix**
3. **Test the connection**
4. **Run the seed script** to populate data: `php seed.php`
5. **Start using the API**

## 🆘 Need Help?

See `MONGODB_ATLAS_SETUP.md` for detailed troubleshooting steps.

## 📊 Backend API Endpoints

Once connected, your backend will support these endpoints:

### Authentication
- POST `/auth/register` - Register new user
- POST `/auth/login` - User login
- GET `/auth/profile` - Get user profile

### Products
- GET `/products` - Get all products
- GET `/products/:id` - Get single product
- POST `/products` - Create product (Admin)
- PUT `/products/:id` - Update product (Admin)
- DELETE `/products/:id` - Delete product (Admin)

### Categories
- GET `/categories` - Get all categories
- POST `/categories` - Create category (Admin)

### Orders
- GET `/orders` - Get user orders
- POST `/orders` - Create new order
- GET `/admin/orders` - Get all orders (Admin)

### Dashboard
- GET `/admin/dashboard/stats` - Get dashboard stats (Admin)

---

**Status**: Configuration complete, waiting for SSL/TLS fix to establish connection.
