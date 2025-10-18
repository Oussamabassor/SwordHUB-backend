# 🔧 Render Environment Variables - REQUIRED

## ⚠️ IMPORTANT: Add These Now!

Your backend is deployed but needs environment variables to work properly.

---

## 📋 How to Add Environment Variables

1. Go to: https://dashboard.render.com
2. Click on your service: **swordhub-backend**
3. Go to: **Environment** (left sidebar)
4. Click: **Add Environment Variable**
5. Copy-paste each variable below

---

## ✅ Required Environment Variables

### 1. MongoDB Configuration

```bash
Key: MONGODB_URI
Value: mongodb+srv://SWORDHUB:sword1234@cluster0.ryhv1ve.mongodb.net/?retryWrites=true&w=majority
```

```bash
Key: MONGODB_DATABASE
Value: swordhub
```

### 2. JWT Configuration

```bash
Key: JWT_SECRET
Value: ED*8]EF=+h?)so{]Evvh6ACORU'R)Q-9j|;ydtJN1L|'<LFR'E;]tR[PzgEw(/_
```

```bash
Key: JWT_EXPIRE
Value: 86400
```

### 3. Frontend URL

```bash
Key: FRONTEND_URL
Value: https://sword-hub-frontend.vercel.app
```

**⚠️ IMPORTANT**: Replace with your actual Vercel URL (no trailing slash!)

### 4. App Configuration

```bash
Key: APP_ENV
Value: production
```

```bash
Key: PORT
Value: 80
```

### 5. Cloudinary Configuration

```bash
Key: USE_CLOUDINARY
Value: true
```

```bash
Key: CLOUDINARY_CLOUD_NAME
Value: dupmbtrcn
```

```bash
Key: CLOUDINARY_API_KEY
Value: 699826948667517
```

```bash
Key: CLOUDINARY_API_SECRET
Value: gMfTd5ZgE5yWRW2M6n1Gb6vYeXk
```

```bash
Key: CLOUDINARY_FOLDER
Value: swordhub
```

### 6. File Upload

```bash
Key: MAX_FILE_SIZE
Value: 5242880
```

### 7. Rate Limiting

```bash
Key: RATE_LIMIT_WINDOW
Value: 900
```

```bash
Key: RATE_LIMIT_MAX_REQUESTS
Value: 100
```

---

## 🎯 After Adding Variables

1. **Save** all environment variables
2. Render will **automatically redeploy** your service
3. Wait **2-3 minutes** for redeployment
4. Test: https://swordhub-backend.onrender.com/

---

## ✅ Expected Response

When working correctly, you should see:

```json
{
  "success": true,
  "message": "SwordHUB API is running!",
  "version": "1.0",
  "endpoints": {
    "health": "/health",
    "auth": "/api/auth",
    "products": "/api/products",
    "categories": "/api/categories",
    "orders": "/api/orders",
    "dashboard": "/api/dashboard"
  },
  "timestamp": 1729263521
}
```

---

## 🧪 Test APIs

### 1. Health Check
```bash
curl https://swordhub-backend.onrender.com/health
```

### 2. Get Products
```bash
curl https://swordhub-backend.onrender.com/api/products
```

### 3. Get Categories
```bash
curl https://swordhub-backend.onrender.com/api/categories
```

---

## 🔍 If Still Getting Errors

### Check Render Logs:
1. Go to Render Dashboard
2. Click your service
3. Click **Logs** tab
4. Look for error messages

### Common Issues:

#### MongoDB Connection Error
```
Error: MongoDB connection failed
```
**Solution**: Check `MONGODB_URI` is correct and MongoDB Atlas allows connections from `0.0.0.0/0`

#### JWT Error
```
Error: JWT secret not configured
```
**Solution**: Verify `JWT_SECRET` is set

#### Cloudinary Error
```
Error: Cloudinary credentials invalid
```
**Solution**: Check all `CLOUDINARY_*` variables are correct

---

## 📱 Update Frontend After Backend Works

Once backend is working:

1. Go to your frontend `.env`:
```bash
VITE_API_URL=https://swordhub-backend.onrender.com
```

2. Commit and push:
```bash
git add .env
git commit -m "Update API URL to production"
git push origin main
```

3. Vercel will auto-redeploy

---

## 🎉 Success Checklist

- [ ] All 15 environment variables added in Render
- [ ] Service redeployed automatically
- [ ] Root endpoint returns success message
- [ ] `/api/products` returns product list
- [ ] `/api/categories` returns categories
- [ ] Frontend `.env` updated with Render URL
- [ ] Frontend redeployed on Vercel
- [ ] Full app tested end-to-end

---

## 🚀 You're Almost There!

Just add these environment variables in Render Dashboard and your backend will work perfectly!

**Render will auto-redeploy after you save the variables (2-3 minutes).**
