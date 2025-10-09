# SwordHub E-Commerce Backend API

Complete RESTful backend API for the SwordHub e-commerce platform built with **PHP and MongoDB**.

## 🚀 Features

- ✅ Complete RESTful API with all required endpoints
- ✅ JWT authentication and authorization
- ✅ Role-based access control (Admin/Customer)
- ✅ Product management with image upload
- ✅ Category management
- ✅ Order processing with stock management
- ✅ Dashboard statistics and analytics
- ✅ Input validation and sanitization
- ✅ CORS configuration
- ✅ Rate limiting
- ✅ Error handling
- ✅ MongoDB integration

## 📋 Requirements

- PHP 7.4 or higher
- MongoDB 4.4 or higher
- Composer (PHP package manager)
- MongoDB PHP Extension

## 🛠️ Installation

### 1. Install MongoDB PHP Extension

```bash
# For Windows (XAMPP):
# Download php_mongodb.dll from https://pecl.php.net/package/mongodb
# Add to php.ini: extension=mongodb
# Restart Apache

# For Linux:
sudo apt-get install php-mongodb

# For macOS:
pecl install mongodb
```

### 2. Install Composer Dependencies

```bash
composer install
```

### 3. Configure Environment

```bash
# Copy .env.example to .env
cp .env.example .env

# Edit .env and update values
```

### 4. Seed Database

```bash
php seed.php
```

This will create:
- Admin user (admin@swordhub.com / Admin123!)
- 4 categories (Training, Footwear, Accessories, Apparel)
- 12 sample products

## 🌐 API Endpoints

### Authentication Routes (`/api/auth`)

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| POST | `/api/auth/login` | Admin/User login | No |
| POST | `/api/auth/register` | User registration | No |
| POST | `/api/auth/logout` | Logout | Yes |
| GET | `/api/auth/me` | Get current user | Yes |

### Product Routes (`/api/products`)

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/products` | Get all products (with filters) | No |
| GET | `/api/products/:id` | Get product by ID | No |
| POST | `/api/products` | Create product | Admin |
| PUT | `/api/products/:id` | Update product | Admin |
| DELETE | `/api/products/:id` | Delete product | Admin |
| POST | `/api/products/upload` | Upload product image | Admin |

**Query Parameters for GET /api/products:**
- `category` - Filter by category ID
- `search` - Search by product name
- `minPrice` - Minimum price
- `maxPrice` - Maximum price
- `featured` - Filter featured products (true/false)
- `page` - Page number (default: 1)
- `limit` - Items per page (default: 10)

### Category Routes (`/api/categories`)

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/categories` | Get all categories | No |
| GET | `/api/categories/:id` | Get category by ID | No |
| POST | `/api/categories` | Create category | Admin |
| PUT | `/api/categories/:id` | Update category | Admin |
| DELETE | `/api/categories/:id` | Delete category | Admin |

### Order Routes (`/api/orders`)

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/orders` | Get all orders | Admin |
| GET | `/api/orders/:id` | Get order by ID | No |
| POST | `/api/orders` | Create order | No |
| PATCH | `/api/orders/:id/status` | Update order status | Admin |
| DELETE | `/api/orders/:id` | Delete order | Admin |
| GET | `/api/orders/stats` | Get order statistics | Admin |

**Query Parameters for GET /api/orders:**
- `status` - Filter by status (pending, processing, shipped, delivered, cancelled)
- `startDate` - Filter by start date
- `endDate` - Filter by end date

### Dashboard Routes (`/api/dashboard`)

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/dashboard/stats` | Get dashboard statistics | Admin |
| GET | `/api/dashboard/analytics` | Get sales analytics | Admin |

**Query Parameters for GET /api/dashboard/analytics:**
- `period` - Analytics period (day, week, month, year)

## 📝 Request/Response Examples

### Login

**Request:**
```bash
POST /api/auth/login
Content-Type: application/json

{
  "email": "admin@swordhub.com",
  "password": "Admin123!"
}
```

**Response:**
```json
{
  "success": true,
  "token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
  "user": {
    "id": "507f1f77bcf86cd799439011",
    "name": "Admin User",
    "email": "admin@swordhub.com",
    "role": "admin"
  }
}
```

### Create Product

**Request:**
```bash
POST /api/products
Authorization: Bearer <token>
Content-Type: application/json

{
  "name": "Training Sword",
  "description": "Professional training sword",
  "price": 89.99,
  "category": "507f1f77bcf86cd799439011",
  "stock": 50,
  "image": "http://example.com/image.jpg",
  "featured": true
}
```

**Response:**
```json
{
  "success": true,
  "message": "Product created successfully",
  "data": {
    "id": "507f1f77bcf86cd799439012",
    "name": "Training Sword",
    "description": "Professional training sword",
    "price": 89.99,
    "category": "507f1f77bcf86cd799439011",
    "stock": 50,
    "image": "http://example.com/image.jpg",
    "featured": true,
    "createdAt": "2024-10-06T10:00:00Z"
  }
}
```

### Create Order

**Request:**
```bash
POST /api/orders
Content-Type: application/json

{
  "customerName": "John Doe",
  "customerEmail": "john@example.com",
  "customerPhone": "+1234567890",
  "customerAddress": "123 Main St, City, Country",
  "items": [
    {
      "productId": "507f1f77bcf86cd799439012",
      "quantity": 2
    }
  ]
}
```

**Response:**
```json
{
  "success": true,
  "message": "Order created successfully",
  "data": {
    "id": "507f1f77bcf86cd799439013",
    "customerName": "John Doe",
    "customerEmail": "john@example.com",
    "items": [...],
    "total": 179.98,
    "status": "pending",
    "createdAt": "2024-10-06T10:00:00Z"
  }
}
```

## 🔐 Authentication

The API uses JWT (JSON Web Tokens) for authentication. Include the token in the Authorization header:

```
Authorization: Bearer <your_jwt_token>
```

Tokens expire after 24 hours (configurable in `.env`).

## 🚦 Error Handling

All errors follow this format:

```json
{
  "success": false,
  "message": "Error message here",
  "errors": []
}
```

**HTTP Status Codes:**
- `200` - Success
- `201` - Created
- `400` - Bad Request (validation errors)
- `401` - Unauthorized (invalid/missing token)
- `403` - Forbidden (insufficient permissions)
- `404` - Not Found
- `429` - Too Many Requests (rate limit exceeded)
- `500` - Internal Server Error

## 🔒 Security Features

- ✅ Password hashing with bcrypt (cost: 10)
- ✅ JWT token authentication
- ✅ Role-based access control
- ✅ CORS protection
- ✅ Rate limiting (100 requests per 15 minutes)
- ✅ Input validation and sanitization
- ✅ SQL injection protection (MongoDB)
- ✅ File upload validation (images only, max 5MB)

## 📁 Project Structure

```
backend/
├── api/                    # Legacy PHP files (can be removed)
├── config/                 # Configuration files
│   ├── Database.php       # Legacy MySQL config
│   └── MongoDB.php        # MongoDB connection
├── controllers/           # Request handlers
│   ├── AuthController.php
│   ├── CategoryController.php
│   ├── DashboardController.php
│   ├── OrderController.php
│   └── ProductController.php
├── middleware/            # Middleware functions
│   ├── AdminMiddleware.php
│   ├── AuthMiddleware.php
│   ├── ErrorMiddleware.php
│   └── RateLimiter.php
├── models/               # Data models
│   ├── Category.php
│   ├── Order.php
│   ├── Product.php
│   └── User.php
├── routes/              # Route definitions
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
├── .env.example     # Environment template
├── .gitignore      # Git ignore rules
├── composer.json   # PHP dependencies
├── index.php      # Main entry point
├── README.md     # This file
└── seed.php     # Database seeder
```

## 🧪 Testing

### Using cURL

```bash
# Login
curl -X POST http://localhost/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@swordhub.com","password":"Admin123!"}'

# Get products
curl http://localhost/api/products

# Create product (with auth)
curl -X POST http://localhost/api/products \
  -H "Authorization: Bearer <token>" \
  -H "Content-Type: application/json" \
  -d '{"name":"Test Product","description":"Test","price":99.99,"category":"<category_id>","stock":10}'
```

### Using Postman/Thunder Client

1. Import the API endpoints
2. Set base URL: `http://localhost/api`
3. For protected routes, add Authorization header: `Bearer <token>`

## 🚀 Deployment

### Apache Configuration

Ensure your `.htaccess` or virtual host configuration routes all requests to `index.php`:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^api/(.*)$ index.php [QSA,L]
</IfModule>
```

### Production Checklist

- [ ] Change `JWT_SECRET` to a strong random value
- [ ] Set `APP_ENV=production` in `.env`
- [ ] Disable error display in production
- [ ] Configure proper MongoDB authentication
- [ ] Set up SSL/HTTPS
- [ ] Configure firewall rules
- [ ] Set up regular backups
- [ ] Configure log rotation
- [ ] Review and adjust rate limits

## 📊 Database Collections

### users
- `_id`: ObjectId
- `name`: String
- `email`: String (unique)
- `password`: String (hashed)
- `role`: String (admin/customer)
- `createdAt`: Date
- `updatedAt`: Date

### products
- `_id`: ObjectId
- `name`: String
- `description`: String
- `price`: Number
- `category`: String (category ID)
- `stock`: Number
- `image`: String
- `featured`: Boolean
- `createdAt`: Date
- `updatedAt`: Date

### categories
- `_id`: ObjectId
- `name`: String (unique)
- `description`: String
- `createdAt`: Date
- `updatedAt`: Date

### orders
- `_id`: ObjectId
- `customerName`: String
- `customerEmail`: String
- `customerPhone`: String
- `customerAddress`: String
- `items`: Array
- `total`: Number
- `status`: String
- `createdAt`: Date
- `updatedAt`: Date

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## 📄 License

MIT License

## 📞 Support

For issues and questions:
- Email: support@swordhub.com
- Documentation: https://api.swordhub.com/docs

## 🎉 Acknowledgments

Built following the SwordHub Backend Development Prompt specifications.
#   S w o r d H U B - b a c k e n d  
 