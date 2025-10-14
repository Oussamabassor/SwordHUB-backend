# 🎯 QUICK REFERENCE - Render Environment Variables

**Use this as a checklist when adding variables to Render Dashboard**

---

## 📝 COPY-PASTE TEMPLATE

Copy this, fill in your values, then add to Render one by one:

```env
# ============================================
# 1. APPLICATION
# ============================================
APP_ENV=production


# ============================================
# 2. DATABASE (Your existing MongoDB Atlas)
# ============================================
MONGODB_URI=mongodb+srv://SWORDHUB:sword1234@cluster0.ryhv1ve.mongodb.net/?retryWrites=true&w=majority
MONGODB_DATABASE=swordhub


# ============================================
# 3. AUTHENTICATION
# ============================================
JWT_SECRET=[GENERATE_64_CHAR_RANDOM_STRING]
JWT_EXPIRE=86400


# ============================================
# 4. FRONTEND CORS
# ============================================
FRONTEND_URL=https://swordhub.vercel.app


# ============================================
# 5. FILE UPLOADS
# ============================================
UPLOAD_DIR=./uploads
MAX_FILE_SIZE=5242880


# ============================================
# 6. CLOUDINARY (CRITICAL FOR RENDER!)
# ============================================
USE_CLOUDINARY=true
CLOUDINARY_CLOUD_NAME=[FROM_CLOUDINARY_DASHBOARD]
CLOUDINARY_API_KEY=[FROM_CLOUDINARY_DASHBOARD]
CLOUDINARY_API_SECRET=[FROM_CLOUDINARY_DASHBOARD]
CLOUDINARY_FOLDER=swordhub/products


# ============================================
# 7. SECURITY
# ============================================
RATE_LIMIT_WINDOW=900
RATE_LIMIT_MAX_REQUESTS=100
```

---

## ✅ CHECKLIST

### Before Adding Variables:

- [ ] **MongoDB Atlas IP Whitelist updated to 0.0.0.0/0**
  - Go to: https://cloud.mongodb.com
  - Network Access → Add IP Address → Allow Access From Anywhere

- [ ] **Cloudinary Account Created**
  - Sign up at: https://cloudinary.com
  - Copy: Cloud Name, API Key, API Secret

- [ ] **JWT Secret Generated**
  - Visit: https://www.grc.com/passwords.htm
  - Copy "63 random printable ASCII characters"

---

### Add These 15 Variables to Render:

```
1.  ☐ APP_ENV = production
2.  ☐ MONGODB_URI = mongodb+srv://SWORDHUB:sword1234@cluster0...
3.  ☐ MONGODB_DATABASE = swordhub
4.  ☐ JWT_SECRET = [64+ char random string]
5.  ☐ JWT_EXPIRE = 86400
6.  ☐ FRONTEND_URL = https://swordhub.vercel.app
7.  ☐ UPLOAD_DIR = ./uploads
8.  ☐ MAX_FILE_SIZE = 5242880
9.  ☐ USE_CLOUDINARY = true (lowercase!)
10. ☐ CLOUDINARY_CLOUD_NAME = [your cloud name]
11. ☐ CLOUDINARY_API_KEY = [your api key]
12. ☐ CLOUDINARY_API_SECRET = [your api secret]
13. ☐ CLOUDINARY_FOLDER = swordhub/products
14. ☐ RATE_LIMIT_WINDOW = 900
15. ☐ RATE_LIMIT_MAX_REQUESTS = 100
```

---

### After Adding:

- [ ] **Clicked "Save Changes"**
- [ ] **Deployment started** (check Logs tab)
- [ ] **Wait 3-5 minutes** for first deploy
- [ ] **Test health endpoint:** `https://your-app.onrender.com/health`
- [ ] **Backend URL saved** for frontend deployment

---

## 🚨 CRITICAL VALUES

### MUST BE EXACTLY:

| Variable | Value | ⚠️ Warning |
|----------|-------|-----------|
| `USE_CLOUDINARY` | `true` | Lowercase! Not `True` or `TRUE` |
| `FRONTEND_URL` | `https://...` | No trailing slash! |
| `MONGODB_URI` | `mongodb+srv://...` | Must be Atlas URI, not local |
| `JWT_SECRET` | 64+ chars | Must be strong and random |

---

## 🔗 YOUR ACTUAL VALUES

### MongoDB (Already Configured ✅):
```
MONGODB_URI=mongodb+srv://SWORDHUB:sword1234@cluster0.ryhv1ve.mongodb.net/?retryWrites=true&w=majority
MONGODB_DATABASE=swordhub
```

### Cloudinary (Get from Dashboard):
```
CLOUDINARY_CLOUD_NAME=_____________
CLOUDINARY_API_KEY=_____________
CLOUDINARY_API_SECRET=_____________
```

### JWT Secret (Generate New):
```
JWT_SECRET=_____________________________________________
```

### Frontend URL (Update After Vercel Deploy):
```
FRONTEND_URL=https://_______________.vercel.app
```

---

## 🎯 STEP-BY-STEP

### 1. Open Render Dashboard
- Go to: https://dashboard.render.com
- Click your service: **swordhub-backend**
- Click **Environment** tab

### 2. Add First Variable
- Click **Add Environment Variable**
- **Key:** `APP_ENV`
- **Value:** `production`
- Click outside field to save

### 3. Repeat for All 15 Variables
- Add each variable from checklist above
- Double-check spelling (case-sensitive!)
- No extra spaces

### 4. Save and Deploy
- Click **Save Changes** (bottom)
- Service redeploys automatically
- Check **Logs** tab for progress

---

## 🧪 TEST DEPLOYMENT

### After Variables Added:

1. **Wait for deployment** (3-5 minutes)

2. **Test health endpoint:**
   ```
   https://your-service.onrender.com/health
   ```
   Should return JSON with "success": true

3. **Check logs:**
   - Render Dashboard → Logs tab
   - Look for "Server started" or errors

4. **If errors:**
   - Check variable spelling
   - Check MongoDB IP whitelist
   - Check Cloudinary credentials

---

## 📞 COMMON ERRORS

### "MongoDB connection failed"
- ✅ Check MONGODB_URI is correct
- ✅ Check IP whitelist includes 0.0.0.0/0
- ✅ Test connection string locally first

### "Cloudinary initialization failed"
- ✅ Check all 3 credentials are set
- ✅ Check USE_CLOUDINARY=true (lowercase)
- ✅ Run `php test_cloudinary.php` locally

### "CORS error"
- ✅ Check FRONTEND_URL matches exact Vercel URL
- ✅ Remove trailing slash
- ✅ Redeploy after changing

---

## 🎉 SUCCESS INDICATORS

Your deployment is successful when:
- ✅ Health endpoint returns JSON
- ✅ No errors in Logs
- ✅ Service status: "Live"
- ✅ Backend URL accessible

**Backend URL:** `https://your-service-name.onrender.com`

---

## 📚 FULL DOCUMENTATION

**Need more details?** See:
- `RENDER_ENVIRONMENT_VARIABLES.md` - Complete guide with explanations
- `DEPLOYMENT_CHECKLIST.md` - Full deployment workflow
- `CLOUDINARY_SETUP.md` - Cloudinary account setup
- `ENV_VARIABLES_GUIDE.md` - What each variable does

---

**🚀 Add these 15 variables to Render and you're ready to deploy!**
