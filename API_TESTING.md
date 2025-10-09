# API Testing Guide

This guide provides examples for testing all SwordHub Backend API endpoints.

## Setup

**Base URL:** `http://localhost/api`

**Note:** Replace `localhost` with your actual domain if different.

## Test Flow

1. Install MongoDB Extension (see INSTALLATION.md)
2. Run `composer install`
3. Run `php seed.php` to populate database
4. Test endpoints below

---

## 1. Authentication Endpoints

### 1.1 Login (Admin)

```bash
curl -X POST http://localhost/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@swordhub.com",
    "password": "Admin123!"
  }'
```

**Expected Response:**
```json
{
  "success": true,
  "token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
  "user": {
    "id": "...",
    "name": "Admin User",
    "email": "admin@swordhub.com",
    "role": "admin"
  }
}
```

**Save the token for subsequent requests!**

### 1.2 Register (Customer)

```bash
curl -X POST http://localhost/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123"
  }'
```

### 1.3 Get Current User

```bash
curl -X GET http://localhost/api/auth/me \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### 1.4 Logout

```bash
curl -X POST http://localhost/api/auth/logout \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

---

## 2. Product Endpoints

### 2.1 Get All Products

```bash
curl -X GET http://localhost/api/products
```

**With Filters:**
```bash
curl -X GET "http://localhost/api/products?category=CATEGORY_ID&search=sword&minPrice=50&maxPrice=150&featured=true&page=1&limit=10"
```

### 2.2 Get Product by ID

```bash
curl -X GET http://localhost/api/products/PRODUCT_ID
```

### 2.3 Create Product (Admin Only)

```bash
curl -X POST http://localhost/api/products \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Advanced Training Sword",
    "description": "Professional grade training sword with balanced weight distribution",
    "price": 149.99,
    "category": "CATEGORY_ID",
    "stock": 25,
    "image": "http://example.com/image.jpg",
    "featured": true
  }'
```

### 2.4 Update Product (Admin Only)

```bash
curl -X PUT http://localhost/api/products/PRODUCT_ID \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Updated Product Name",
    "price": 139.99,
    "stock": 30
  }'
```

### 2.5 Delete Product (Admin Only)

```bash
curl -X DELETE http://localhost/api/products/PRODUCT_ID \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### 2.6 Upload Product Image (Admin Only)

```bash
curl -X POST http://localhost/api/products/upload \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -F "image=@/path/to/your/image.jpg"
```

---

## 3. Category Endpoints

### 3.1 Get All Categories

```bash
curl -X GET http://localhost/api/categories
```

### 3.2 Get Category by ID

```bash
curl -X GET http://localhost/api/categories/CATEGORY_ID
```

### 3.3 Create Category (Admin Only)

```bash
curl -X POST http://localhost/api/categories \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Protection Gear",
    "description": "Safety and protection equipment"
  }'
```

### 3.4 Update Category (Admin Only)

```bash
curl -X PUT http://localhost/api/categories/CATEGORY_ID \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Updated Category Name",
    "description": "Updated description"
  }'
```

### 3.5 Delete Category (Admin Only)

```bash
curl -X DELETE http://localhost/api/categories/CATEGORY_ID \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

---

## 4. Order Endpoints

### 4.1 Get All Orders (Admin Only)

```bash
curl -X GET http://localhost/api/orders \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

**With Filters:**
```bash
curl -X GET "http://localhost/api/orders?status=pending&startDate=2024-01-01&endDate=2024-12-31" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### 4.2 Get Order by ID

```bash
curl -X GET http://localhost/api/orders/ORDER_ID
```

### 4.3 Create Order (Customer)

```bash
curl -X POST http://localhost/api/orders \
  -H "Content-Type: application/json" \
  -d '{
    "customerName": "Jane Smith",
    "customerEmail": "jane@example.com",
    "customerPhone": "+1234567890",
    "customerAddress": "456 Oak St, Springfield, USA",
    "items": [
      {
        "productId": "PRODUCT_ID_1",
        "quantity": 2
      },
      {
        "productId": "PRODUCT_ID_2",
        "quantity": 1
      }
    ]
  }'
```

### 4.4 Update Order Status (Admin Only)

```bash
curl -X PATCH http://localhost/api/orders/ORDER_ID/status \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{
    "status": "processing"
  }'
```

**Valid statuses:** `pending`, `processing`, `shipped`, `delivered`, `cancelled`

### 4.5 Delete Order (Admin Only)

```bash
curl -X DELETE http://localhost/api/orders/ORDER_ID \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### 4.6 Get Order Statistics (Admin Only)

```bash
curl -X GET http://localhost/api/orders/stats \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

---

## 5. Dashboard Endpoints

### 5.1 Get Dashboard Statistics (Admin Only)

```bash
curl -X GET http://localhost/api/dashboard/stats \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

**Response:**
```json
{
  "success": true,
  "data": {
    "totalProducts": 100,
    "totalOrders": 150,
    "totalRevenue": 15000.50,
    "pendingOrders": 20,
    "totalCategories": 10,
    "recentOrders": [...]
  }
}
```

### 5.2 Get Sales Analytics (Admin Only)

```bash
curl -X GET "http://localhost/api/dashboard/analytics?period=month" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

**Valid periods:** `day`, `week`, `month`, `year`

---

## Testing with Postman

### Import Collection

1. Open Postman
2. Click "Import"
3. Create new collection "SwordHub API"
4. Set collection variable: `baseUrl` = `http://localhost/api`

### Setup Environment

Create environment variables:
- `baseUrl`: `http://localhost/api`
- `token`: (Will be set after login)

### Auto-save Token

In the login request, add to "Tests" tab:

```javascript
if (pm.response.code === 200) {
    const response = pm.response.json();
    pm.environment.set("token", response.token);
}
```

### Use Token in Requests

In Authorization tab:
- Type: Bearer Token
- Token: `{{token}}`

---

## Testing Workflow Example

### Complete Test Flow

```bash
# 1. Login as admin
TOKEN=$(curl -s -X POST http://localhost/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@swordhub.com","password":"Admin123!"}' \
  | grep -o '"token":"[^"]*' | cut -d'"' -f4)

echo "Token: $TOKEN"

# 2. Get all products
curl -X GET http://localhost/api/products

# 3. Get all categories
curl -X GET http://localhost/api/categories

# 4. Create a new product
curl -X POST http://localhost/api/products \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test Product",
    "description": "This is a test product",
    "price": 99.99,
    "category": "GET_CATEGORY_ID_FROM_STEP_3",
    "stock": 50,
    "featured": false
  }'

# 5. Create an order
curl -X POST http://localhost/api/orders \
  -H "Content-Type: application/json" \
  -d '{
    "customerName": "Test Customer",
    "customerEmail": "test@example.com",
    "customerPhone": "+1234567890",
    "customerAddress": "123 Test St",
    "items": [
      {
        "productId": "GET_PRODUCT_ID_FROM_STEP_2",
        "quantity": 1
      }
    ]
  }'

# 6. Get dashboard stats
curl -X GET http://localhost/api/dashboard/stats \
  -H "Authorization: Bearer $TOKEN"

# 7. Get analytics
curl -X GET "http://localhost/api/dashboard/analytics?period=month" \
  -H "Authorization: Bearer $TOKEN"
```

---

## Error Testing

### Test Invalid Authentication

```bash
curl -X POST http://localhost/api/products \
  -H "Content-Type: application/json" \
  -d '{"name":"Test"}'
```

Expected: `401 Unauthorized`

### Test Invalid Input

```bash
curl -X POST http://localhost/api/products \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"name":""}'
```

Expected: `400 Bad Request` with validation errors

### Test Non-existent Resource

```bash
curl -X GET http://localhost/api/products/000000000000000000000000
```

Expected: `404 Not Found`

### Test Admin-only Route as Customer

```bash
# First register as customer
curl -X POST http://localhost/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Customer",
    "email": "customer@example.com",
    "password": "password123"
  }'

# Login as customer
CUSTOMER_TOKEN=$(curl -s -X POST http://localhost/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"customer@example.com","password":"password123"}' \
  | grep -o '"token":"[^"]*' | cut -d'"' -f4)

# Try to create product (should fail)
curl -X POST http://localhost/api/products \
  -H "Authorization: Bearer $CUSTOMER_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"name":"Test","description":"Test","price":99.99,"category":"test","stock":10}'
```

Expected: `403 Forbidden`

---

## Health Check

```bash
curl -X GET http://localhost/health
```

**Response:**
```json
{
  "success": true,
  "message": "Server is running",
  "timestamp": 1234567890
}
```

---

## Rate Limiting Test

```bash
# Run this multiple times quickly (>100 times in 15 minutes)
for i in {1..150}; do
  curl -X GET http://localhost/api/products
  echo "Request $i"
done
```

After 100 requests, you should get:
```json
{
  "success": false,
  "message": "Too many requests. Please try again later."
}
```

---

## Common Issues

### Issue: "No authorization token provided"
**Solution:** Add `-H "Authorization: Bearer YOUR_TOKEN"`

### Issue: "Invalid or expired token"
**Solution:** Login again to get a new token (tokens expire after 24 hours)

### Issue: "Product not found"
**Solution:** Use valid MongoDB ObjectId (24 hex characters)

### Issue: "Cannot connect to MongoDB"
**Solution:** Ensure MongoDB is running (`mongod` service)

### Issue: "Access denied. Admin privileges required"
**Solution:** Login with admin credentials (admin@swordhub.com / Admin123!)

---

## Success Criteria Checklist

- [ ] Admin can login successfully
- [ ] Customer can register successfully
- [ ] Can retrieve all products
- [ ] Can filter products by category, price, etc.
- [ ] Admin can create products
- [ ] Admin can update products
- [ ] Admin can delete products
- [ ] Admin can upload product images
- [ ] Can retrieve all categories
- [ ] Admin can manage categories
- [ ] Customer can create orders
- [ ] Order reduces product stock
- [ ] Admin can view all orders
- [ ] Admin can update order status
- [ ] Admin can view dashboard stats
- [ ] Admin can view analytics
- [ ] Non-admin cannot access admin routes
- [ ] Invalid credentials are rejected
- [ ] Rate limiting works
- [ ] CORS allows frontend access

---

## Next Steps

After successful testing:

1. ✅ All endpoints working
2. ✅ Authentication functional
3. ✅ Authorization enforced
4. ✅ Validation working
5. ✅ Error handling proper
6. 🚀 Connect frontend application!

**Frontend Expected URL:** `http://localhost/api`

The frontend should:
1. Store JWT token in localStorage after login
2. Include token in Authorization header for protected routes
3. Handle 401 errors by redirecting to login
4. Handle 403 errors by showing access denied message
