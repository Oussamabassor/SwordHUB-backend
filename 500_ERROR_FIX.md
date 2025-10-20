# 🔧 500 Error Fix - Environment Variables Missing

## ✅ Progress!

- ❌ 403 Forbidden → **FIXED** ✅
- ❌ 404 Not Found → **FIXED** ✅  
- ❌ 500 Internal Server Error → **FIXING NOW** 🔧

---

## 🎯 Current Issue

**Error**: `500 Internal Server Error`

**Cause**: Environment variables are **NOT set** in Render Dashboard!

The backend is trying to connect to MongoDB but can't find the connection string.

---

## ⚠️ CRITICAL: Add Environment Variables NOW

### Step 1: Go to Render Dashboard

1. Visit: https://dashboard.render.com
2. Click: **swordhub-backend**
3. Click: **Environment** (left sidebar)

### Step 2: Add ALL These Variables

Click **"Add Environment Variable"** for each:

#### 1. MongoDB Configuration (REQUIRED)

```
Key: MONGODB_URI
Value: mongodb+srv://SWORDHUB:sword1234@cluster0.ryhv1ve.mongodb.net/?retryWrites=true&w=majority
```

```
Key: MONGODB_DATABASE
Value: swordhub
```

#### 2. JWT Configuration (REQUIRED)

```
Key: JWT_SECRET
Value: ED*8]EF=+h?)so{]Evvh6ACORU'R)Q-9j|;ydtJN1L|'<LFR'E;]tR[PzgEw(/_
```

```
Key: JWT_EXPIRE
Value: 86400
```

#### 3. Frontend Configuration (REQUIRED)

```
Key: FRONTEND_URL
Value: https://sword-hub-frontend.vercel.app
```

⚠️ **Important**: No trailing slash! Use your actual Vercel URL.

#### 4. App Configuration

```
Key: APP_ENV
Value: production
```

```
Key: PORT
Value: 80
```

#### 5. Cloudinary Configuration (REQUIRED)

```
Key: USE_CLOUDINARY
Value: true
```

```
Key: CLOUDINARY_CLOUD_NAME
Value: dupmbtrcn
```

```
Key: CLOUDINARY_API_KEY
Value: 699826948667517
```

```
Key: CLOUDINARY_API_SECRET
Value: gMfTd5ZgE5yWRW2M6n1Gb6vYeXk
```

```
Key: CLOUDINARY_FOLDER
Value: swordhub
```

#### 6. File Upload & Rate Limiting

```
Key: MAX_FILE_SIZE
Value: 5242880
```

```
Key: RATE_LIMIT_WINDOW
Value: 900
```

```
Key: RATE_LIMIT_MAX_REQUESTS
Value: 100
```

### Step 3: Save Changes

Click **"Save Changes"** button at bottom

---

## 🔄 What Happens After Saving

1. Render will **automatically redeploy** (2-3 minutes)
2. Environment variables will be loaded
3. MongoDB connection will work
4. API endpoints will work!

---

## 🧪 Test After Redeploy

### 1. Debug Endpoint (NEW!)

```bash
curl https://swordhub-backend.onrender.com/debug
```

**Expected**:
```json
{
  "success": true,
  "env_check": {
    "MONGODB_URI": "Set ✓",
    "MONGODB_DATABASE": "Set ✓",
    "JWT_SECRET": "Set ✓",
    "CLOUDINARY_CLOUD_NAME": "Set ✓"
  },
  "php_version": "8.1.x",
  "extensions": {
    "mongodb": "Loaded ✓",
    "pdo": "Loaded ✓"
  }
}
```

If you see **"Missing ✗"** anywhere, that variable isn't set!

### 2. Products Endpoint

```bash
curl https://swordhub-backend.onrender.com/api/products
```

**Expected**:
```json
{
  "success": true,
  "data": [
    {
      "id": "...",
      "name": "Sword Name",
      "price": 299.99,
      ...
    }
  ]
}
```

---

## 📋 Complete Environment Variables Checklist

Copy this to a text file and check off as you add each:

```
□ MONGODB_URI
□ MONGODB_DATABASE
□ JWT_SECRET
□ JWT_EXPIRE
□ FRONTEND_URL
□ APP_ENV
□ PORT
□ USE_CLOUDINARY
□ CLOUDINARY_CLOUD_NAME
□ CLOUDINARY_API_KEY
□ CLOUDINARY_API_SECRET
□ CLOUDINARY_FOLDER
□ MAX_FILE_SIZE
□ RATE_LIMIT_WINDOW
□ RATE_LIMIT_MAX_REQUESTS
```

**Total: 15 variables**

---

## 🔍 How to Add Variables (Step by Step)

### In Render Dashboard:

1. Scroll to **Environment** section
2. You'll see a form with:
   - **Key**: (name of variable)
   - **Value**: (the value)
3. Click **"Add Environment Variable"**
4. Paste **Key** name (e.g., `MONGODB_URI`)
5. Paste **Value** (e.g., `mongodb+srv://...`)
6. Repeat for all 15 variables
7. Click **"Save Changes"**

---

## ⏰ Timeline

1. **Now**: Add all 15 environment variables
2. **Save**: Click "Save Changes"
3. **Wait**: Render redeploys (2-3 min)
4. **Test**: Visit `/debug` endpoint
5. **Success**: All variables show "Set ✓"
6. **Test**: Visit `/api/products`
7. **Success**: Products return! 🎉

---

## 🎯 Why This Happened

Docker containers don't use `.env` files by default. All environment variables must be set in the hosting platform (Render).

### Before:
```
PHP: "Where's MONGODB_URI?"
System: "I don't know, check .env"
PHP: ".env doesn't exist in production!"
Result: 500 Error ❌
```

### After:
```
PHP: "Where's MONGODB_URI?"
System: "Here it is from Render environment!"
PHP: "Great! Connecting to MongoDB..."
Result: Products returned ✅
```

---

## 🆘 If Still Getting 500 Error

### Check Render Logs:

1. Render Dashboard → **swordhub-backend** → **Logs**
2. Look for PHP errors like:
   - `MongoDB connection failed`
   - `Undefined variable: _ENV`
   - `Call to undefined function`

### Verify MongoDB Atlas:

1. Go to: https://cloud.mongodb.com
2. Click: **Network Access**
3. Ensure: `0.0.0.0/0` is allowed (allows all IPs)

---

## 🎉 After Variables Are Set

Your backend will:
- ✅ Connect to MongoDB successfully
- ✅ Return products from database
- ✅ Handle authentication
- ✅ Upload images to Cloudinary
- ✅ Serve API requests
- ✅ Work with frontend perfectly!

---

## 📞 Next Steps

1. **Right now**: Go to Render Dashboard
2. **Add variables**: All 15 from list above
3. **Save**: Click "Save Changes"
4. **Wait**: 2-3 minutes for redeploy
5. **Test**: `/debug` endpoint first
6. **Test**: `/api/products` endpoint
7. **Visit**: Frontend and see products! 🎉

---

**The routing is fixed! Just add the environment variables and you're done!** 🚀
