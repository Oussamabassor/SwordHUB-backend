# 🚀 Quick Start - Testing APIs with Postman

## 📥 Import Postman Collection

### Method 1: Import JSON File
1. Open Postman
2. Click **Import** button (top left)
3. Select **File** tab
4. Choose `SwordHub-API-Collection.postman_collection.json`
5. Click **Import**

### Method 2: Import from Link
1. Copy the JSON file content
2. In Postman, click **Import**
3. Select **Raw text** tab
4. Paste the JSON content
5. Click **Continue** → **Import**

---

## ⚙️ Setup Environment

### Create Environment Variables
1. In Postman, click **Environments** (left sidebar)
2. Click **+** to create new environment
3. Name it: `SwordHub Local`
4. Add variables:

| Variable | Initial Value | Current Value |
|----------|---------------|---------------|
| baseUrl | http://localhost:5000 | http://localhost:5000 |
| authToken | (leave empty) | (leave empty) |

5. Click **Save**
6. Select this environment from dropdown (top right)

---

## 🎯 Testing Workflow

### Step 1: Start Your Backend Server

```powershell
cd C:\xampp\htdocs\SwordHUB\SwordHub-backend
php -S localhost:5000 index.php
```

Or if using XAMPP Apache, update baseUrl to:
```
http://localhost/SwordHUB/SwordHub-backend
```

### Step 2: Test Connection

Open browser and go to:
```
http://localhost:5000
```

You should see a welcome message or API info.

---

## 📋 Test Sequence

### 1️⃣ Authentication Tests

#### A. Login as Admin
1. Open collection: **Authentication** → **Login User**
2. Body is pre-filled with admin credentials:
   ```json
   {
     "email": "admin@swordhub.com",
     "password": "Admin123!"
   }
   ```
3. Click **Send**
4. ✅ Response should be `200 OK`
5. 🔑 Token will be **automatically saved** to environment variable `authToken`

#### B. Register New User (Optional)
1. Open: **Authentication** → **Register User**
2. Modify the body if needed
3. Click **Send**
4. ✅ Response should be `201 Created`

#### C. Get User Profile
1. Open: **Authentication** → **Get User Profile**
2. Click **Send**
3. ✅ Should return your profile data

---

### 2️⃣ Categories Tests

#### A. Get All Categories
1. Open: **Categories** → **Get All Categories**
2. Click **Send**
3. ✅ Should return 4 categories (Training, Footwear, Accessories, Apparel)

**Sample Response:**
```json
{
  "success": true,
  "data": [
    {
      "_id": "671234567890abcdef",
      "name": "Training",
      "description": "Training equipment and gear"
    }
  ]
}
```

#### B. Get Single Category
1. Copy a category `_id` from previous response
2. Open: **Categories** → **Get Single Category**
3. Replace `:id` in URL with the copied ID
4. Click **Send**

#### C. Create Category (Admin Only)
1. Open: **Categories** → **Create Category (Admin)**
2. Modify body:
   ```json
   {
     "name": "Medieval Weapons",
     "description": "Authentic medieval weapon replicas"
   }
   ```
3. Click **Send**
4. ✅ Should create new category

---

### 3️⃣ Products Tests

#### A. Get All Products
1. Open: **Products** → **Get All Products**
2. Click **Send**
3. ✅ Should return 12 products

**Note:** You can test filters by enabling query params:
- `search=sword`
- `category=CATEGORY_ID`
- `minPrice=50`
- `maxPrice=200`

#### B. Get Single Product
1. Copy a product `_id` from previous response
2. Open: **Products** → **Get Single Product**
3. Replace `:id` in URL with the copied ID
4. Click **Send**

#### C. Create Product (Admin Only)
1. First, get a category ID from **Get All Categories**
2. Open: **Products** → **Create Product (Admin)**
3. Update body with valid category ID:
   ```json
   {
     "name": "Excalibur Replica",
     "description": "Legendary sword replica",
     "price": 299.99,
     "category": "PASTE_CATEGORY_ID_HERE",
     "stock": 15,
     "images": ["excalibur.jpg"]
   }
   ```
4. Click **Send**
5. ✅ Should create new product

#### D. Update Product (Admin Only)
1. Copy a product ID
2. Open: **Products** → **Update Product (Admin)**
3. Replace `:id` in URL
4. Modify body as needed
5. Click **Send**

---

### 4️⃣ Orders Tests

#### A. Create Order
1. Get a product ID from **Get All Products**
2. Open: **Orders** → **Create Order**
3. Update body with valid product ID:
   ```json
   {
     "items": [
       {
         "product": "PASTE_PRODUCT_ID_HERE",
         "quantity": 2,
         "price": 129.99
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
4. Click **Send**
5. ✅ Should create new order

#### B. Get User Orders
1. Open: **Orders** → **Get User Orders**
2. Click **Send**
3. ✅ Should return all your orders

#### C. Get All Orders (Admin Only)
1. Open: **Orders** → **Get All Orders (Admin)**
2. Click **Send**
3. ✅ Should return all orders from all users

#### D. Update Order Status (Admin Only)
1. Copy an order ID from previous response
2. Open: **Orders** → **Update Order Status (Admin)**
3. Replace `:id` in URL
4. Update body with desired status:
   ```json
   {
     "status": "processing"
   }
   ```
   (Options: `pending`, `processing`, `shipped`, `delivered`, `cancelled`)
5. Click **Send**

---

### 5️⃣ Dashboard Tests (Admin Only)

#### Get Dashboard Statistics
1. Open: **Admin Dashboard** → **Get Dashboard Stats**
2. Click **Send**
3. ✅ Should return comprehensive statistics:
   - Total revenue
   - Total orders
   - Total products
   - Total users
   - Recent orders
   - Top products
   - Sales by month

---

## 🔍 Checking Responses

### Success Response Structure
```json
{
  "success": true,
  "message": "Optional success message",
  "data": { ... }
}
```

### Error Response Structure
```json
{
  "success": false,
  "message": "Error description",
  "errors": { ... } // Optional validation errors
}
```

---

## 🐛 Troubleshooting

### ❌ "No token provided"
**Solution:** Make sure you logged in first. The token should be auto-saved.
- Check: Environment variable `authToken` has a value
- If empty, run **Login User** again

### ❌ "Invalid token" or "Token expired"
**Solution:** Token expires after 24 hours. Login again.

### ❌ "Access denied. Admin only"
**Solution:** This endpoint requires admin role.
- Login with admin credentials: `admin@swordhub.com` / `Admin123!`

### ❌ "Connection refused" or "Cannot connect"
**Solution:** Backend server is not running.
```powershell
cd C:\xampp\htdocs\SwordHUB\SwordHub-backend
php -S localhost:5000 index.php
```

### ❌ "Resource not found"
**Solution:** Check the ID in the URL. Make sure it's a valid MongoDB ObjectID.

---

## 📊 Test Results Checklist

Use this checklist to verify all endpoints:

### Authentication
- [ ] Register User (201 Created)
- [ ] Login User (200 OK, token received)
- [ ] Get Profile (200 OK)
- [ ] Update Profile (200 OK)

### Categories
- [ ] Get All Categories (200 OK, 4 categories)
- [ ] Get Single Category (200 OK)
- [ ] Create Category - Admin (201 Created)
- [ ] Update Category - Admin (200 OK)
- [ ] Delete Category - Admin (200 OK)

### Products
- [ ] Get All Products (200 OK, 12+ products)
- [ ] Get Single Product (200 OK)
- [ ] Get Products with Filters (200 OK)
- [ ] Create Product - Admin (201 Created)
- [ ] Update Product - Admin (200 OK)
- [ ] Delete Product - Admin (200 OK)

### Orders
- [ ] Create Order (201 Created)
- [ ] Get User Orders (200 OK)
- [ ] Get Single Order (200 OK)
- [ ] Get All Orders - Admin (200 OK)
- [ ] Update Order Status - Admin (200 OK)

### Dashboard
- [ ] Get Dashboard Stats - Admin (200 OK)

---

## 💡 Pro Tips

### 1. Use Variables
Instead of copying IDs manually, save them to environment:
```javascript
// In Tests tab of any request
var jsonData = pm.response.json();
pm.environment.set("productId", jsonData.data._id);
```

### 2. Chain Requests
Create a folder and set up a test sequence:
1. Login → Save token
2. Get Categories → Save category ID
3. Create Product → Use saved category ID
4. Create Order → Use saved product ID

### 3. Test Scripts
Add to **Tests** tab:
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

## 📝 Sample Test Data

### Test User
```json
{
  "name": "Jane Smith",
  "email": "jane@example.com",
  "password": "Jane123!",
  "phone": "5551234567",
  "address": "456 Oak Ave, Los Angeles, CA"
}
```

### Test Product
```json
{
  "name": "Samurai Katana Premium",
  "description": "Hand-forged katana with authentic design",
  "price": 499.99,
  "category": "CATEGORY_ID",
  "stock": 8,
  "images": ["katana1.jpg", "katana2.jpg"]
}
```

### Test Order
```json
{
  "items": [
    {
      "product": "PRODUCT_ID",
      "quantity": 1,
      "price": 129.99
    }
  ],
  "shippingAddress": {
    "street": "789 Elm St",
    "city": "Chicago",
    "state": "IL",
    "zipCode": "60601",
    "country": "USA"
  },
  "paymentMethod": "paypal"
}
```

---

## 🎉 You're Ready!

1. ✅ Import collection
2. ✅ Setup environment
3. ✅ Start backend server
4. ✅ Login to get token
5. ✅ Test all endpoints

**Happy Testing! 🚀**

For full API documentation, see: `POSTMAN_API_GUIDE.md`
