# SwordHub Backend API Documentation

## Base URL
```
http://localhost:5000
```
or if using XAMPP Apache:
```
http://localhost/SwordHUB/SwordHub-backend
```

---

## 📋 Table of Contents
1. [Authentication APIs](#authentication-apis)
2. [Products APIs](#products-apis)
3. [Categories APIs](#categories-apis)
4. [Orders APIs](#orders-apis)
5. [Admin Dashboard APIs](#admin-dashboard-apis)

---

## 🔐 Authentication APIs

### 1. Register User
**POST** `/auth/register`

**Headers:**
```
Content-Type: application/json
```

**Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "Test123!",
  "phone": "1234567890",
  "address": "123 Main St, City, Country"
}
```

**Success Response (201):**
```json
{
  "success": true,
  "message": "User registered successfully",
  "data": {
    "user": {
      "_id": "...",
      "name": "John Doe",
      "email": "john@example.com",
      "role": "user"
    },
    "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
  }
}
```

---

### 2. Login User
**POST** `/auth/login`

**Headers:**
```
Content-Type: application/json
```

**Body:**
```json
{
  "email": "admin@swordhub.com",
  "password": "Admin123!"
}
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "user": {
      "_id": "...",
      "name": "Admin User",
      "email": "admin@swordhub.com",
      "role": "admin"
    },
    "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
  }
}
```

---

### 3. Get User Profile
**GET** `/auth/profile`

**Headers:**
```
Authorization: Bearer YOUR_JWT_TOKEN
```

**Success Response (200):**
```json
{
  "success": true,
  "data": {
    "_id": "...",
    "name": "Admin User",
    "email": "admin@swordhub.com",
    "role": "admin",
    "createdAt": "2025-10-09T..."
  }
}
```

---

### 4. Update User Profile
**PUT** `/auth/profile`

**Headers:**
```
Authorization: Bearer YOUR_JWT_TOKEN
Content-Type: application/json
```

**Body:**
```json
{
  "name": "Updated Name",
  "phone": "9876543210",
  "address": "456 New St, City, Country"
}
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Profile updated successfully",
  "data": {
    "_id": "...",
    "name": "Updated Name",
    "email": "admin@swordhub.com",
    "phone": "9876543210",
    "address": "456 New St, City, Country"
  }
}
```

---

## 🛍️ Products APIs

### 5. Get All Products
**GET** `/products`

**Query Parameters (Optional):**
- `category` - Filter by category ID
- `search` - Search in name/description
- `minPrice` - Minimum price
- `maxPrice` - Maximum price
- `page` - Page number (default: 1)
- `limit` - Items per page (default: 10)

**Example:**
```
GET /products?category=67123456789&search=sword&page=1&limit=10
```

**Success Response (200):**
```json
{
  "success": true,
  "data": {
    "products": [
      {
        "_id": "...",
        "name": "Professional Training Sword",
        "description": "High-quality training sword...",
        "price": 129.99,
        "category": {
          "_id": "...",
          "name": "Training"
        },
        "stock": 50,
        "images": ["image1.jpg"],
        "rating": 4.5,
        "reviews": []
      }
    ],
    "pagination": {
      "page": 1,
      "limit": 10,
      "total": 12,
      "pages": 2
    }
  }
}
```

---

### 6. Get Single Product
**GET** `/products/:id`

**Example:**
```
GET /products/67123456789abcdef
```

**Success Response (200):**
```json
{
  "success": true,
  "data": {
    "_id": "...",
    "name": "Professional Training Sword",
    "description": "High-quality training sword for professionals...",
    "price": 129.99,
    "category": {
      "_id": "...",
      "name": "Training",
      "description": "Training equipment and gear"
    },
    "stock": 50,
    "images": ["image1.jpg", "image2.jpg"],
    "rating": 4.5,
    "reviews": [],
    "createdAt": "2025-10-09T..."
  }
}
```

---

### 7. Create Product (Admin Only)
**POST** `/products`

**Headers:**
```
Authorization: Bearer ADMIN_JWT_TOKEN
Content-Type: multipart/form-data
```

**Body (form-data):**
```
name: "New Sword Product"
description: "Description of the new sword"
price: 199.99
category: "67123456789abcdef" (Category ID)
stock: 25
images: [Upload image file(s)]
```

**Success Response (201):**
```json
{
  "success": true,
  "message": "Product created successfully",
  "data": {
    "_id": "...",
    "name": "New Sword Product",
    "description": "Description of the new sword",
    "price": 199.99,
    "category": "...",
    "stock": 25,
    "images": ["uploads/products/image123.jpg"]
  }
}
```

---

### 8. Update Product (Admin Only)
**PUT** `/products/:id`

**Headers:**
```
Authorization: Bearer ADMIN_JWT_TOKEN
Content-Type: application/json
```

**Body:**
```json
{
  "name": "Updated Product Name",
  "price": 149.99,
  "stock": 30,
  "description": "Updated description"
}
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Product updated successfully",
  "data": {
    "_id": "...",
    "name": "Updated Product Name",
    "price": 149.99,
    "stock": 30
  }
}
```

---

### 9. Delete Product (Admin Only)
**DELETE** `/products/:id`

**Headers:**
```
Authorization: Bearer ADMIN_JWT_TOKEN
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Product deleted successfully"
}
```

---

## 📁 Categories APIs

### 10. Get All Categories
**GET** `/categories`

**Success Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "_id": "...",
      "name": "Training",
      "description": "Training equipment and gear",
      "productCount": 4,
      "createdAt": "2025-10-09T..."
    },
    {
      "_id": "...",
      "name": "Footwear",
      "description": "Athletic shoes and boots",
      "productCount": 2,
      "createdAt": "2025-10-09T..."
    }
  ]
}
```

---

### 11. Get Single Category
**GET** `/categories/:id`

**Success Response (200):**
```json
{
  "success": true,
  "data": {
    "_id": "...",
    "name": "Training",
    "description": "Training equipment and gear",
    "products": [
      {
        "_id": "...",
        "name": "Professional Training Sword",
        "price": 129.99,
        "images": ["image1.jpg"]
      }
    ],
    "createdAt": "2025-10-09T..."
  }
}
```

---

### 12. Create Category (Admin Only)
**POST** `/categories`

**Headers:**
```
Authorization: Bearer ADMIN_JWT_TOKEN
Content-Type: application/json
```

**Body:**
```json
{
  "name": "Protective Gear",
  "description": "Safety equipment and protective gear"
}
```

**Success Response (201):**
```json
{
  "success": true,
  "message": "Category created successfully",
  "data": {
    "_id": "...",
    "name": "Protective Gear",
    "description": "Safety equipment and protective gear",
    "createdAt": "2025-10-09T..."
  }
}
```

---

### 13. Update Category (Admin Only)
**PUT** `/categories/:id`

**Headers:**
```
Authorization: Bearer ADMIN_JWT_TOKEN
Content-Type: application/json
```

**Body:**
```json
{
  "name": "Updated Category Name",
  "description": "Updated description"
}
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Category updated successfully",
  "data": {
    "_id": "...",
    "name": "Updated Category Name",
    "description": "Updated description"
  }
}
```

---

### 14. Delete Category (Admin Only)
**DELETE** `/categories/:id`

**Headers:**
```
Authorization: Bearer ADMIN_JWT_TOKEN
```

**Success Response (200):**
```json
{
  "success": true,
  "message": "Category deleted successfully"
}
```

---

## 🛒 Orders APIs

### 15. Create Order (Authenticated User)
**POST** `/orders`

**Headers:**
```
Authorization: Bearer USER_JWT_TOKEN
Content-Type: application/json
```

**Body:**
```json
{
  "items": [
    {
      "product": "67123456789abcdef",
      "quantity": 2,
      "price": 129.99
    },
    {
      "product": "67123456789abcdef2",
      "quantity": 1,
      "price": 89.99
    }
  ],
  "shippingAddress": {
    "street": "123 Main St",
    "city": "New York",
    "state": "NY",
    "zipCode": "10001",
    "country": "USA"
  },
  "paymentMethod": "credit_card"
}
```

**Success Response (201):**
```json
{
  "success": true,
  "message": "Order created successfully",
  "data": {
    "_id": "...",
    "orderNumber": "ORD-2025-001",
    "items": [...],
    "totalAmount": 349.97,
    "status": "pending",
    "createdAt": "2025-10-09T..."
  }
}
```

---

### 16. Get User Orders (Authenticated User)
**GET** `/orders`

**Headers:**
```
Authorization: Bearer USER_JWT_TOKEN
```

**Query Parameters (Optional):**
- `status` - Filter by status (pending, processing, shipped, delivered, cancelled)
- `page` - Page number
- `limit` - Items per page

**Success Response (200):**
```json
{
  "success": true,
  "data": {
    "orders": [
      {
        "_id": "...",
        "orderNumber": "ORD-2025-001",
        "items": [...],
        "totalAmount": 349.97,
        "status": "pending",
        "createdAt": "2025-10-09T..."
      }
    ],
    "pagination": {
      "page": 1,
      "limit": 10,
      "total": 5,
      "pages": 1
    }
  }
}
```

---

### 17. Get Single Order (Authenticated User)
**GET** `/orders/:id`

**Headers:**
```
Authorization: Bearer USER_JWT_TOKEN
```

**Success Response (200):**
```json
{
  "success": true,
  "data": {
    "_id": "...",
    "orderNumber": "ORD-2025-001",
    "user": {
      "_id": "...",
      "name": "John Doe",
      "email": "john@example.com"
    },
    "items": [
      {
        "product": {
          "_id": "...",
          "name": "Professional Training Sword",
          "images": ["image1.jpg"]
        },
        "quantity": 2,
        "price": 129.99
      }
    ],
    "totalAmount": 349.97,
    "status": "pending",
    "shippingAddress": {...},
    "paymentMethod": "credit_card",
    "createdAt": "2025-10-09T..."
  }
}
```

---

### 18. Update Order Status (Admin Only)
**PUT** `/orders/:id/status`

**Headers:**
```
Authorization: Bearer ADMIN_JWT_TOKEN
Content-Type: application/json
```

**Body:**
```json
{
  "status": "processing"
}
```

**Allowed statuses:** `pending`, `processing`, `shipped`, `delivered`, `cancelled`

**Success Response (200):**
```json
{
  "success": true,
  "message": "Order status updated successfully",
  "data": {
    "_id": "...",
    "orderNumber": "ORD-2025-001",
    "status": "processing",
    "updatedAt": "2025-10-09T..."
  }
}
```

---

### 19. Get All Orders (Admin Only)
**GET** `/admin/orders`

**Headers:**
```
Authorization: Bearer ADMIN_JWT_TOKEN
```

**Query Parameters (Optional):**
- `status` - Filter by status
- `page` - Page number
- `limit` - Items per page
- `startDate` - Filter orders from date (YYYY-MM-DD)
- `endDate` - Filter orders to date (YYYY-MM-DD)

**Success Response (200):**
```json
{
  "success": true,
  "data": {
    "orders": [
      {
        "_id": "...",
        "orderNumber": "ORD-2025-001",
        "user": {
          "name": "John Doe",
          "email": "john@example.com"
        },
        "totalAmount": 349.97,
        "status": "pending",
        "createdAt": "2025-10-09T..."
      }
    ],
    "pagination": {...}
  }
}
```

---

## 📊 Admin Dashboard APIs

### 20. Get Dashboard Statistics (Admin Only)
**GET** `/admin/dashboard/stats`

**Headers:**
```
Authorization: Bearer ADMIN_JWT_TOKEN
```

**Success Response (200):**
```json
{
  "success": true,
  "data": {
    "overview": {
      "totalRevenue": 15234.50,
      "totalOrders": 125,
      "totalProducts": 45,
      "totalUsers": 230,
      "pendingOrders": 15
    },
    "recentOrders": [
      {
        "_id": "...",
        "orderNumber": "ORD-2025-125",
        "user": "John Doe",
        "totalAmount": 299.99,
        "status": "pending",
        "createdAt": "2025-10-09T..."
      }
    ],
    "topProducts": [
      {
        "product": "Professional Training Sword",
        "soldCount": 45,
        "revenue": 5849.55
      }
    ],
    "salesByMonth": [
      {
        "month": "January",
        "sales": 12500.00,
        "orders": 35
      }
    ]
  }
}
```

---

## 🔑 Testing Workflow

### Step 1: Register/Login
1. Use **Register** endpoint to create a new user
2. Or use **Login** with admin credentials:
   - Email: `admin@swordhub.com`
   - Password: `Admin123!`
3. Copy the `token` from the response

### Step 2: Set Authorization
In Postman:
1. Go to **Authorization** tab
2. Select **Type**: Bearer Token
3. Paste your token in the **Token** field

### Step 3: Test Endpoints
- Test public endpoints (Get Products, Get Categories)
- Test authenticated endpoints (Get Profile, Create Order)
- Test admin endpoints (Create Product, Get Dashboard Stats)

---

## 🧪 Example Postman Tests

Add these scripts to your Postman tests:

**For Login/Register:**
```javascript
// Save token to environment
pm.test("Status code is 200", function () {
    pm.response.to.have.status(200);
});

pm.test("Token is present", function () {
    var jsonData = pm.response.json();
    pm.expect(jsonData.data.token).to.be.a('string');
    pm.environment.set("authToken", jsonData.data.token);
});
```

**For other endpoints:**
```javascript
pm.test("Status code is 200", function () {
    pm.response.to.have.status(200);
});

pm.test("Response has success property", function () {
    var jsonData = pm.response.json();
    pm.expect(jsonData.success).to.eql(true);
});
```

---

## 📝 Notes

- Replace `localhost:5000` with your actual backend URL
- All timestamps are in ISO 8601 format
- File uploads use `multipart/form-data`
- All other requests use `application/json`
- JWT tokens expire after 24 hours (configurable in `.env`)
- Admin endpoints require admin role
- User endpoints require authentication

---

## 🐛 Common Errors

**401 Unauthorized:**
```json
{
  "success": false,
  "message": "No token provided" // or "Invalid token"
}
```

**403 Forbidden:**
```json
{
  "success": false,
  "message": "Access denied. Admin only."
}
```

**404 Not Found:**
```json
{
  "success": false,
  "message": "Resource not found"
}
```

**400 Bad Request:**
```json
{
  "success": false,
  "message": "Validation error",
  "errors": {
    "email": "Invalid email format",
    "password": "Password must be at least 8 characters"
  }
}
```

---

**Happy Testing! 🚀**
