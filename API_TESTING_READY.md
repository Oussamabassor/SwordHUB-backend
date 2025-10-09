# 🎯 API Testing Resources - Ready for Postman!

## 📦 What You Have

I've created complete API testing resources for your SwordHub backend:

### 1. **Postman Collection** 
`SwordHub-API-Collection.postman_collection.json`
- 20 pre-configured API requests
- Automatic token management
- Test scripts included
- Ready to import

### 2. **Complete API Documentation**
`POSTMAN_API_GUIDE.md`
- All 20 endpoints documented
- Request/response examples
- Error handling guide
- Authentication flow

### 3. **Quick Start Guide**
`QUICK_TEST_GUIDE.md`
- Step-by-step testing workflow
- Troubleshooting tips
- Test checklist
- Pro tips for Postman

---

## 🚀 Quick Start (3 Steps)

### Step 1: Import to Postman
1. Open Postman
2. Click **Import** button
3. Select `SwordHub-API-Collection.postman_collection.json`
4. Done! ✅

### Step 2: Setup Environment
1. In Postman, create new environment: `SwordHub Local`
2. Add variables:
   - `baseUrl` = `http://localhost:5000`
   - `authToken` = (leave empty)
3. Select this environment

### Step 3: Start Testing
1. Start your backend:
   ```powershell
   cd C:\xampp\htdocs\SwordHUB\SwordHub-backend
   php -S localhost:5000 index.php
   ```
2. In Postman, run: **Authentication** → **Login User**
3. Token saves automatically! 🎉
4. Test any endpoint!

---

## 📋 All Available APIs (20 Endpoints)

### 🔐 Authentication (4 endpoints)
1. POST `/auth/register` - Register new user
2. POST `/auth/login` - Login user
3. GET `/auth/profile` - Get user profile
4. PUT `/auth/profile` - Update profile

### 🛍️ Products (5 endpoints)
5. GET `/products` - Get all products (with filters)
6. GET `/products/:id` - Get single product
7. POST `/products` - Create product (Admin)
8. PUT `/products/:id` - Update product (Admin)
9. DELETE `/products/:id` - Delete product (Admin)

### 📁 Categories (5 endpoints)
10. GET `/categories` - Get all categories
11. GET `/categories/:id` - Get single category
12. POST `/categories` - Create category (Admin)
13. PUT `/categories/:id` - Update category (Admin)
14. DELETE `/categories/:id` - Delete category (Admin)

### 🛒 Orders (5 endpoints)
15. POST `/orders` - Create order
16. GET `/orders` - Get user orders
17. GET `/orders/:id` - Get single order
18. PUT `/orders/:id/status` - Update status (Admin)
19. GET `/admin/orders` - Get all orders (Admin)

### 📊 Dashboard (1 endpoint)
20. GET `/admin/dashboard/stats` - Get statistics (Admin)

---

## 🎯 Testing Workflow

```
1. Login as Admin
   ↓
2. Get All Categories (copy category ID)
   ↓
3. Get All Products (copy product ID)
   ↓
4. Create New Product (Admin)
   ↓
5. Create Order (as user)
   ↓
6. Update Order Status (Admin)
   ↓
7. View Dashboard Stats (Admin)
```

---

## 🔑 Test Credentials

### Admin Account
```
Email: admin@swordhub.com
Password: Admin123!
```

### Test User (Register this)
```
Name: Test User
Email: test@example.com
Password: Test123!
```

---

## 📊 Current Database

Your MongoDB Atlas database has:
- ✅ 1 Admin user
- ✅ 4 Categories (Training, Footwear, Accessories, Apparel)
- ✅ 12 Products (various swords and equipment)
- 📝 0 Orders (create some!)

---

## 🔧 Backend Status

✅ **MongoDB Atlas**: Connected
✅ **Database**: Seeded with data
✅ **API Routes**: All configured
✅ **Authentication**: JWT working
✅ **Admin Features**: Ready
✅ **File Upload**: Configured

---

## 📖 Documentation Files

| File | Description |
|------|-------------|
| `SwordHub-API-Collection.postman_collection.json` | Import into Postman |
| `POSTMAN_API_GUIDE.md` | Complete API documentation |
| `QUICK_TEST_GUIDE.md` | Step-by-step testing guide |
| `SUCCESS.md` | Setup summary & credentials |
| `test-connection.php` | Test MongoDB connection |
| `verify-data.php` | Verify database data |

---

## 💡 Pro Tips

### Tip 1: Auto-Save Token
The collection automatically saves your login token. Just login once!

### Tip 2: Use Collection Runner
1. Click **▶️ Run** on collection
2. Test all endpoints sequentially
3. View all results in one place

### Tip 3: Create Test Scenarios
Set up folder with:
- Login → Get Products → Create Order → View Order

### Tip 4: Environment Switching
Create multiple environments:
- `SwordHub Local` - http://localhost:5000
- `SwordHub Production` - https://your-production-url.com

---

## 🎨 Sample Requests

### Login
```bash
POST http://localhost:5000/auth/login
Content-Type: application/json

{
  "email": "admin@swordhub.com",
  "password": "Admin123!"
}
```

### Get Products
```bash
GET http://localhost:5000/products?page=1&limit=10
```

### Create Order
```bash
POST http://localhost:5000/orders
Authorization: Bearer YOUR_TOKEN
Content-Type: application/json

{
  "items": [{
    "product": "PRODUCT_ID",
    "quantity": 2,
    "price": 129.99
  }],
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

---

## 🐛 Common Issues

### Issue: "Connection refused"
**Fix:** Start backend server
```powershell
php -S localhost:5000 index.php
```

### Issue: "No token provided"
**Fix:** Login first (token auto-saves)

### Issue: "Access denied"
**Fix:** Use admin credentials for admin endpoints

### Issue: "Invalid ID"
**Fix:** Copy actual ID from previous response

---

## ✅ Test Checklist

Before deploying to production:
- [ ] All authentication endpoints working
- [ ] Products CRUD working
- [ ] Categories CRUD working
- [ ] Orders creation working
- [ ] Admin endpoints require admin role
- [ ] User endpoints require authentication
- [ ] Dashboard stats returning data
- [ ] File uploads working (if tested)
- [ ] Pagination working
- [ ] Search/filters working
- [ ] Error handling working

---

## 🚀 Next Steps

1. **Import collection** to Postman
2. **Start backend** server
3. **Login** to get token
4. **Test all endpoints** using the collection
5. **Create orders** and test full workflow
6. **Connect frontend** to these APIs
7. **Deploy** when ready!

---

## 📞 Need Help?

Refer to:
- `POSTMAN_API_GUIDE.md` - Detailed API docs
- `QUICK_TEST_GUIDE.md` - Testing workflow
- `SUCCESS.md` - Setup info & credentials

---

**Everything is ready! Just import the collection and start testing! 🎉**

*Last updated: October 9, 2025*
