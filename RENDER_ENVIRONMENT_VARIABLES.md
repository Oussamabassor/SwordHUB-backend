# 🚀 RENDER DASHBOARD ENVIRONMENT VARIABLES

**Complete guide for setting up environment variables in Render.com dashboard**

---

## 📋 HOW TO ADD VARIABLES IN RENDER

### Step 1: Access Environment Variables
1. Go to [Render Dashboard](https://dashboard.render.com)
2. Click on your web service: **swordhub-backend**
3. Click **Environment** tab (left sidebar)
4. Click **Add Environment Variable** button

### Step 2: Add Each Variable Below
- Copy the **Key** (exact spelling, case-sensitive)
- Copy/paste the **Value** (replace with your actual values)
- Click **Save Changes** (triggers auto-redeploy)

---

## ✅ REQUIRED ENVIRONMENT VARIABLES

### Copy these EXACT key-value pairs into Render:

---

### 1. APP_ENV
```
Key:   APP_ENV
Value: production
```
**Purpose:** Sets application to production mode
**Action:** Type "production" exactly

---

### 2. MONGODB_URI
```
Key:   MONGODB_URI
Value: mongodb+srv://SWORDHUB:sword1234@cluster0.ryhv1ve.mongodb.net/?retryWrites=true&w=majority
```
**Purpose:** MongoDB Atlas connection string
**Action:** Copy the EXACT value from above (your current connection string)
⚠️ **IMPORTANT:** Make sure MongoDB Atlas IP whitelist includes `0.0.0.0/0`

---

### 3. MONGODB_DATABASE
```
Key:   MONGODB_DATABASE
Value: swordhub
```
**Purpose:** Database name
**Action:** Type "swordhub" exactly

---

### 4. JWT_SECRET
```
Key:   JWT_SECRET
Value: Kj8mP2qL9vN4xR7tY5wZ3aB6cD1eF0gH9iJ8kL7mN6oP5rS4uV3xW2zA1bC0dE9fG8hI7jK6lM5nO4pQ3rS2tU1vW0xY9zA8bC7dE6fG5hI4jK3lM2nO1pQ0
```
**Purpose:** Secret key for JWT token encryption
**Action:** 
- ⚠️ **DO NOT use the value above** - it's just an example!
- **Generate your own strong secret:**
  
  **Option 1 - Use Online Generator:**
  1. Visit: https://www.grc.com/passwords.htm
  2. Copy "63 random printable ASCII characters"
  3. Paste as value
  
  **Option 2 - Use PowerShell:**
  ```powershell
  -join ((48..57) + (65..90) + (97..122) + 33..47 | Get-Random -Count 64 | ForEach-Object {[char]$_})
  ```
  
  **Option 3 - Just make it long:**
  ```
  your_super_secret_jwt_key_change_this_in_production_2024_make_it_very_long_and_random_with_numbers_12345_and_symbols_!@#$%
  ```

---

### 5. JWT_EXPIRE
```
Key:   JWT_EXPIRE
Value: 86400
```
**Purpose:** JWT token expiration (24 hours in seconds)
**Action:** Type "86400" (keeps users logged in for 24 hours)

---

### 6. FRONTEND_URL
```
Key:   FRONTEND_URL
Value: https://swordhub.vercel.app
```
**Purpose:** CORS configuration - allows frontend to call backend
**Action:** 
- ⚠️ **Initially use a placeholder URL** (guess your Vercel URL)
- ✅ **Update this AFTER deploying frontend to Vercel**
- ❌ **NO trailing slash!** (don't write https://swordhub.vercel.app/)

**Example guesses:**
- `https://swordhub.vercel.app`
- `https://sword-hub.vercel.app`
- `https://your-name-swordhub.vercel.app`

**You'll update this later in Step 3 below**

---

### 7. UPLOAD_DIR
```
Key:   UPLOAD_DIR
Value: ./uploads
```
**Purpose:** Local fallback upload directory
**Action:** Type "./uploads" exactly

---

### 8. MAX_FILE_SIZE
```
Key:   MAX_FILE_SIZE
Value: 5242880
```
**Purpose:** Maximum file upload size (5MB in bytes)
**Action:** Type "5242880" exactly

---

### 9. USE_CLOUDINARY ⚠️ CRITICAL!
```
Key:   USE_CLOUDINARY
Value: true
```
**Purpose:** Enables Cloudinary cloud storage (REQUIRED for Render!)
**Action:** Type "true" exactly (lowercase)
⚠️ **MUST BE true** - Render has ephemeral filesystem, images would be deleted without this!

---

### 10. CLOUDINARY_CLOUD_NAME
```
Key:   CLOUDINARY_CLOUD_NAME
Value: [YOUR_CLOUDINARY_CLOUD_NAME]
```
**Purpose:** Your Cloudinary account identifier
**Action:** 
1. Sign up at [cloudinary.com](https://cloudinary.com) (free)
2. Go to Dashboard
3. Copy "Cloud name" (looks like: `dv7qw8zxm`)
4. Paste as value

**Example:** `dv7qw8zxm`

---

### 11. CLOUDINARY_API_KEY
```
Key:   CLOUDINARY_API_KEY
Value: [YOUR_CLOUDINARY_API_KEY]
```
**Purpose:** Cloudinary authentication
**Action:**
1. From Cloudinary Dashboard
2. Copy "API Key" (15-digit number like: `847562139845632`)
3. Paste as value

**Example:** `847562139845632`

---

### 12. CLOUDINARY_API_SECRET
```
Key:   CLOUDINARY_API_SECRET
Value: [YOUR_CLOUDINARY_API_SECRET]
```
**Purpose:** Cloudinary authentication secret
**Action:**
1. From Cloudinary Dashboard
2. Copy "API Secret" (long string like: `Abc123XyZ789-qWe456RtY`)
3. Paste as value

**Example:** `Abc123XyZ789-qWe456RtY`

---

### 13. CLOUDINARY_FOLDER
```
Key:   CLOUDINARY_FOLDER
Value: swordhub/products
```
**Purpose:** Folder path in Cloudinary for organized storage
**Action:** Type "swordhub/products" exactly

---

### 14. RATE_LIMIT_WINDOW
```
Key:   RATE_LIMIT_WINDOW
Value: 900
```
**Purpose:** Rate limiting window (15 minutes in seconds)
**Action:** Type "900" exactly

---

### 15. RATE_LIMIT_MAX_REQUESTS
```
Key:   RATE_LIMIT_MAX_REQUESTS
Value: 100
```
**Purpose:** Maximum API requests per window
**Action:** Type "100" exactly

---

## 📊 COMPLETE LIST FOR COPY-PASTE

Copy this into a text editor, fill in your values, then add to Render:

```plaintext
APP_ENV=production

MONGODB_URI=mongodb+srv://SWORDHUB:sword1234@cluster0.ryhv1ve.mongodb.net/?retryWrites=true&w=majority
MONGODB_DATABASE=swordhub

JWT_SECRET=[GENERATE_YOUR_OWN_64_CHARACTER_RANDOM_STRING]
JWT_EXPIRE=86400

FRONTEND_URL=https://swordhub.vercel.app

UPLOAD_DIR=./uploads
MAX_FILE_SIZE=5242880

USE_CLOUDINARY=true
CLOUDINARY_CLOUD_NAME=[FROM_CLOUDINARY_DASHBOARD]
CLOUDINARY_API_KEY=[FROM_CLOUDINARY_DASHBOARD]
CLOUDINARY_API_SECRET=[FROM_CLOUDINARY_DASHBOARD]
CLOUDINARY_FOLDER=swordhub/products

RATE_LIMIT_WINDOW=900
RATE_LIMIT_MAX_REQUESTS=100
```

---

## 🎯 DEPLOYMENT STEPS

### STEP 1: Before Adding Variables

#### A. Setup MongoDB Atlas IP Whitelist
1. Go to [MongoDB Atlas](https://cloud.mongodb.com)
2. Click **Network Access** (left sidebar)
3. Click **ADD IP ADDRESS**
4. Click **ALLOW ACCESS FROM ANYWHERE**
5. Confirm IP: `0.0.0.0/0`
6. Click **Confirm**

⚠️ **This is REQUIRED** - without this, Render can't connect to your database!

#### B. Sign Up for Cloudinary
1. Go to [cloudinary.com](https://cloudinary.com)
2. Click "Sign Up for Free"
3. Verify email
4. Go to Dashboard
5. Copy these 3 values:
   - Cloud name: `dxxxxx`
   - API Key: `123456789012345`
   - API Secret: `Abc123XyZ...`

#### C. Generate JWT Secret
Use one of these methods:
- Visit: https://www.grc.com/passwords.htm
- Or PowerShell: `-join ((48..122) | Get-Random -Count 64 | ForEach-Object {[char]$_})`
- Or just make a very long random string (64+ chars)

---

### STEP 2: Add Variables to Render

1. **Go to Render Dashboard**
   - URL: https://dashboard.render.com

2. **Create Web Service** (if not created yet)
   - Click **New +** → **Web Service**
   - Connect your GitHub repository: `swordhub-backend`
   - Render auto-detects PHP and `render.yaml`

3. **Add Environment Variables**
   - Click **Environment** tab
   - Add each variable from the list above
   - **Critical variables to add first:**
     ```
     MONGODB_URI
     JWT_SECRET
     USE_CLOUDINARY=true
     CLOUDINARY_CLOUD_NAME
     CLOUDINARY_API_KEY
     CLOUDINARY_API_SECRET
     FRONTEND_URL
     ```

4. **Click "Save Changes"**
   - Service will auto-redeploy
   - Wait 3-5 minutes

5. **Get Your Backend URL**
   - Copy: `https://swordhub-backend.onrender.com`
   - Save this for frontend deployment

---

### STEP 3: After Deploying Frontend

Once your frontend is deployed to Vercel:

1. **Get Exact Vercel URL**
   - Example: `https://swordhub-kj8mz.vercel.app`

2. **Update FRONTEND_URL in Render**
   - Go to Render Dashboard
   - Click your service
   - **Environment** tab
   - Find **FRONTEND_URL**
   - Click **Edit**
   - Update to exact Vercel URL (no trailing slash!)
   - Click **Save Changes**

3. **Service Redeploys Automatically**
   - Wait 2-3 minutes
   - CORS will now work correctly

---

## ✅ VERIFICATION CHECKLIST

After adding all variables:

```
□ 15 environment variables added
□ MONGODB_URI contains your actual connection string
□ JWT_SECRET is 64+ characters and random
□ USE_CLOUDINARY is set to "true" (not "True" or "TRUE")
□ All 3 Cloudinary credentials added
□ FRONTEND_URL has no trailing slash
□ No typos in variable names (case-sensitive!)
□ Clicked "Save Changes"
□ Service is redeploying (check Logs tab)
```

---

## 🧪 TEST YOUR DEPLOYMENT

### 1. Health Check
Open in browser:
```
https://your-service-name.onrender.com/health
```

**Should see:**
```json
{
  "success": true,
  "message": "Server is running",
  "timestamp": 1234567890
}
```

### 2. Products API
Open in browser:
```
https://your-service-name.onrender.com/api/products
```

**Should see:**
```json
{
  "success": true,
  "data": {
    "products": [],
    "total": 0,
    ...
  }
}
```

### 3. Check Logs
If errors occur:
1. Render Dashboard → Your Service
2. Click **Logs** tab
3. Look for error messages
4. Common issues:
   - MongoDB connection failed → Check MONGODB_URI and IP whitelist
   - Cloudinary error → Check CLOUDINARY credentials
   - CORS error → Check FRONTEND_URL

---

## 🚨 COMMON MISTAKES TO AVOID

### ❌ WRONG:
```
USE_CLOUDINARY=True          # Capital T
USE_CLOUDINARY=TRUE          # All caps
FRONTEND_URL=https://app.vercel.app/  # Trailing slash
MONGODB_URI=mongodb://localhost  # Local URL
JWT_SECRET=mysecret123       # Too short/simple
```

### ✅ CORRECT:
```
USE_CLOUDINARY=true          # Lowercase
FRONTEND_URL=https://app.vercel.app  # No trailing slash
MONGODB_URI=mongodb+srv://...  # Atlas connection string
JWT_SECRET=Kj8mP2qL9vN4xR7...  # 64+ random characters
```

---

## 🔐 SECURITY NOTES

### Keep These Secret:
- ❌ Never commit `.env` to Git
- ❌ Never share JWT_SECRET publicly
- ❌ Never share Cloudinary API Secret
- ❌ Never share MongoDB password

### Environment Variables Are Safe:
- ✅ Render keeps them encrypted
- ✅ Not visible in logs
- ✅ Not visible in public GitHub
- ✅ Only you can see/edit them

---

## 📞 NEED HELP?

### If Variables Not Working:
1. Check spelling (case-sensitive!)
2. Check for extra spaces
3. Check Render Logs for errors
4. Verify all required variables are set
5. Try redeploying manually

### If Images Not Uploading:
1. Verify `USE_CLOUDINARY=true`
2. Check all 3 Cloudinary credentials
3. Test connection: `php test_cloudinary.php` locally
4. Check Cloudinary dashboard for uploaded images

### If CORS Errors:
1. Update `FRONTEND_URL` to exact Vercel URL
2. No trailing slash
3. Redeploy backend after changing
4. Clear browser cache

---

## 🎉 YOU'RE READY!

Once all 15 variables are added:
- ✅ Click "Save Changes"
- ✅ Wait for deployment to complete
- ✅ Test health endpoint
- ✅ Deploy frontend next
- ✅ Update FRONTEND_URL
- ✅ Test image upload via admin dashboard

**Your backend will be live at:** `https://your-service-name.onrender.com`

---

## 📚 NEXT STEPS

1. **Add all variables above** ✅
2. **Deploy frontend to Vercel** → See `DEPLOYMENT_CHECKLIST.md`
3. **Update FRONTEND_URL** → See Step 3 above
4. **Test everything** → Upload product with image
5. **Celebrate!** 🎊 Your app is live!

---

**Need more help?** Check:
- `DEPLOYMENT_CHECKLIST.md` - Complete deployment guide
- `CLOUDINARY_SETUP.md` - Cloudinary setup instructions
- `TROUBLESHOOTING.md` - Common issues

**Questions about variables?** Check `ENV_VARIABLES_GUIDE.md`

---

**🚀 Good luck with deployment!**
