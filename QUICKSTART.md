# SwordHub Backend - Quick Setup Guide

## 🚀 Quick Start (5 Minutes)

### Prerequisites Checklist
- [ ] XAMPP installed (Apache + PHP 7.4+)
- [ ] MongoDB installed and running
- [ ] MongoDB PHP extension installed
- [ ] Composer installed

### Step-by-Step Setup

#### 1. Install MongoDB PHP Extension

**Windows (XAMPP):**
```powershell
# Download from: https://pecl.php.net/package/mongodb
# Choose version matching your PHP (check with: php -v)
# For PHP 8.0: php_mongodb-1.20.0-8.0-ts-x64.zip

# Copy php_mongodb.dll to: C:\xampp\php\ext\

# Edit C:\xampp\php\php.ini and add:
extension=mongodb

# Restart Apache in XAMPP Control Panel
```

See `INSTALLATION.md` for detailed instructions.

#### 2. Install Composer Dependencies

```powershell
cd c:\xampp\htdocs\SwordHub-backend
composer install
```

#### 3. Configure Environment

```powershell
# .env file is already configured with defaults
# Update if needed (MongoDB URI, JWT secret, etc.)
```

#### 4. Start MongoDB

**Windows:**
```powershell
# MongoDB should auto-start if installed as service
# Or run manually:
"C:\Program Files\MongoDB\Server\6.0\bin\mongod.exe"
```

**Check if MongoDB is running:**
```powershell
# Open another terminal:
mongo --eval "db.version()"
```

#### 5. Seed Database

```powershell
php seed.php
```

**Expected Output:**
```
🌱 Starting database seed...
👤 Creating admin user...
✅ Admin user created: admin@swordhub.com (Password: Admin123!)
📁 Creating categories...
  ✅ Training
  ✅ Footwear
  ✅ Accessories
  ✅ Apparel
🏷️  Creating sample products...
  ✅ Professional Training Sword
  ... (12 products total)
🎉 Database seeding completed successfully!
```

#### 6. Test API

```powershell
# Test health endpoint
curl http://localhost/api/health

# Test login
curl -X POST http://localhost/api/auth/login -H "Content-Type: application/json" -d '{\"email\":\"admin@swordhub.com\",\"password\":\"Admin123!\"}'

# Test products
curl http://localhost/api/products
```

---

## 🎯 Default Credentials

**Admin User:**
- Email: `admin@swordhub.com`
- Password: `Admin123!`

---

## 📊 What's Included

### ✅ Completed Features

#### Authentication & Authorization
- ✅ JWT token-based authentication
- ✅ Admin/Customer role-based access control
- ✅ Password hashing with bcrypt
- ✅ Login, logout, register, and "get me" endpoints
- ✅ Token expiration (24 hours)

#### Product Management
- ✅ CRUD operations (Create, Read, Update, Delete)
- ✅ Product filtering (category, price, search, featured)
- ✅ Pagination support
- ✅ Image upload functionality
- ✅ Stock management

#### Category Management
- ✅ CRUD operations
- ✅ Product count per category
- ✅ Prevent deletion if category has products

#### Order Management
- ✅ Order creation with validation
- ✅ Automatic stock reduction
- ✅ Order status tracking (pending → processing → shipped → delivered)
- ✅ Order filtering by status and date
- ✅ Order statistics

#### Dashboard & Analytics
- ✅ Total products, orders, revenue
- ✅ Pending orders count
- ✅ Recent orders list
- ✅ Sales analytics by day/week/month/year

#### Security Features
- ✅ CORS configuration
- ✅ Rate limiting (100 requests per 15 minutes)
- ✅ Input validation and sanitization
- ✅ File upload security (images only, max 5MB)
- ✅ Error handling and logging
- ✅ SQL/NoSQL injection protection

---

## 🌐 API Endpoints Summary

### Public Endpoints
- `POST /api/auth/login` - Login
- `POST /api/auth/register` - Register
- `GET /api/products` - Get all products
- `GET /api/products/:id` - Get product
- `GET /api/categories` - Get all categories
- `GET /api/categories/:id` - Get category
- `POST /api/orders` - Create order
- `GET /api/orders/:id` - Get order

### Admin-Only Endpoints
- `POST /api/products` - Create product
- `PUT /api/products/:id` - Update product
- `DELETE /api/products/:id` - Delete product
- `POST /api/products/upload` - Upload image
- `POST /api/categories` - Create category
- `PUT /api/categories/:id` - Update category
- `DELETE /api/categories/:id` - Delete category
- `GET /api/orders` - Get all orders
- `PATCH /api/orders/:id/status` - Update order status
- `DELETE /api/orders/:id` - Delete order
- `GET /api/orders/stats` - Get order stats
- `GET /api/dashboard/stats` - Get dashboard stats
- `GET /api/dashboard/analytics` - Get analytics

---

## 📁 Project Structure

```
SwordHub-backend/
├── api/                    # Legacy endpoints (deprecated)
├── config/                 # Configuration files
│   ├── Database.php       # Legacy MySQL (deprecated)
│   └── MongoDB.php        # Active MongoDB connection
├── controllers/           # Request handlers
│   ├── AuthController.php
│   ├── CategoryController.php
│   ├── DashboardController.php
│   ├── OrderController.php
│   └── ProductController.php
├── database/
│   └── schema.sql         # Legacy schema (deprecated)
├── middleware/            # Middleware functions
│   ├── AdminMiddleware.php
│   ├── AuthMiddleware.php
│   ├── ErrorMiddleware.php
│   └── RateLimiter.php
├── models/               # MongoDB models
│   ├── Category.php
│   ├── Order.php
│   ├── Product.php
│   └── User.php
├── routes/              # Route handlers
│   ├── AuthRoutes.php
│   ├── CategoryRoutes.php
│   ├── DashboardRoutes.php
│   ├── OrderRoutes.php
│   └── ProductRoutes.php
├── uploads/            # Uploaded files
│   └── products/
├── utils/             # Utility functions
│   ├── FileUpload.php
│   ├── JWT.php
│   └── Validator.php
├── vendor/           # Composer dependencies
├── .env             # Environment variables
├── .env.example    # Environment template
├── .gitignore
├── .htaccess      # Apache rewrite rules
├── API_TESTING.md # API testing guide
├── composer.json  # PHP dependencies
├── index.php     # Main entry point
├── INSTALLATION.md # Setup instructions
├── README.md      # Documentation
├── QUICKSTART.md  # This file
└── seed.php      # Database seeder
```

---

## 🔧 Troubleshooting

### Issue: "MongoDB extension not loaded"
**Solution:** Follow `INSTALLATION.md` to install the extension

### Issue: "Failed to connect to MongoDB"
**Solution:** 
```powershell
# Check if MongoDB is running
mongod --version
# Start MongoDB service
net start MongoDB
```

### Issue: "composer: command not found"
**Solution:** Install Composer from https://getcomposer.org/

### Issue: "404 Not Found" on API requests
**Solution:** 
1. Check `.htaccess` exists
2. Enable `mod_rewrite` in Apache
3. Verify DocumentRoot points to project directory

### Issue: "CORS error" in frontend
**Solution:** Update `FRONTEND_URL` in `.env` to match your frontend URL

### Issue: "Permission denied" on uploads
**Solution:**
```powershell
# Give write permissions to uploads folder
icacls "C:\xampp\htdocs\SwordHub-backend\uploads" /grant Users:F /T
```

---

## 📚 Documentation

- `README.md` - Complete documentation
- `API_TESTING.md` - API testing examples
- `INSTALLATION.md` - Detailed installation guide
- `QUICKSTART.md` - This quick setup guide

---

## ✅ Verification Checklist

After setup, verify:

- [ ] MongoDB extension loaded: `php -m | findstr mongodb`
- [ ] MongoDB running: `mongo --eval "db.version()"`
- [ ] Composer dependencies installed: `composer check-platform-reqs`
- [ ] Database seeded: Check for admin user and products
- [ ] Health endpoint works: `curl http://localhost/api/health`
- [ ] Login works: Get JWT token
- [ ] Can fetch products: `curl http://localhost/api/products`
- [ ] Admin can create product (with token)
- [ ] Customer can create order
- [ ] Dashboard stats work (admin only)

---

## 🚀 Next Steps

### 1. Frontend Integration

Update your frontend to use:
```javascript
const API_URL = 'http://localhost/api';

// Login
const response = await fetch(`${API_URL}/auth/login`, {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({ email, password })
});
const { token } = await response.json();
localStorage.setItem('token', token);

// Authenticated request
const products = await fetch(`${API_URL}/products`, {
  headers: {
    'Authorization': `Bearer ${localStorage.getItem('token')}`
  }
});
```

### 2. Production Deployment

See `README.md` → Production Checklist

### 3. Testing

See `API_TESTING.md` for comprehensive testing examples

---

## 🎉 Success!

Your SwordHub Backend is now ready! You have:

✅ Complete REST API with MongoDB
✅ Authentication & Authorization
✅ Product, Category, Order Management
✅ Dashboard & Analytics
✅ Security Features
✅ Sample Data

**Start your frontend and begin building! 🚀**

---

## 📞 Need Help?

- Check `README.md` for detailed documentation
- Review `INSTALLATION.md` for setup issues
- See `API_TESTING.md` for API examples
- Check Apache logs: `C:\xampp\apache\logs\error.log`
- Check PHP logs: `C:\xampp\php\logs\php_error_log`
