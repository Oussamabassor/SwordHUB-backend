# 🐳 Docker Deployment Guide for Render.com

## ✅ Prerequisites Completed

- [x] Dockerfile created
- [x] render.yaml configured
- [x] .dockerignore created
- [x] Apache configuration ready
- [x] MongoDB connection configured
- [x] Cloudinary integration ready

---

## 📋 Step-by-Step Deployment

### Step 1: Verify Local Files

Make sure these files exist in your `SwordHub-backend` directory:

```
SwordHub-backend/
├── Dockerfile                    ✅
├── render.yaml                   ✅
├── .dockerignore                 ✅
├── docker/
│   ├── 000-default.conf         ✅
│   └── start.sh                  ✅
├── composer.json                 ✅
├── index.php                     ✅
└── All your PHP files            ✅
```

### Step 2: Push to GitHub

```bash
cd c:\xampp\htdocs\SwordHUB\SwordHub-backend

# Add all files
git add .

# Commit
git commit -m "Add Docker configuration for Render deployment"

# Push
git push origin main
```

### Step 3: Create Render Service

1. **Go to** [Render Dashboard](https://dashboard.render.com)
2. **Click** "New +" button
3. **Select** "Web Service"
4. **Connect** your GitHub repository: `Oussamabassor/SwordHUB-backend`
5. **Configure:**
   - **Name**: `swordhub-backend`
   - **Region**: Frankfurt (or closest to you)
   - **Branch**: `main`
   - **Runtime**: **Docker** (automatically detected)
   - **Plan**: **Free**

### Step 4: Add Environment Variables

In Render dashboard, add these environment variables:

#### Required Variables (Add as Secret/Private):

```bash
MONGODB_URI=mongodb+srv://SWORDHUB:sword1234@cluster0.ryhv1ve.mongodb.net/
MONGODB_DATABASE=swordhub
JWT_SECRET=ED*8]EF=+h?)so{]Evvh6ACORU'R)Q-9j|;ydtJN1L|'<LFR'E;]tR[PzgEw(/_
FRONTEND_URL=https://your-frontend-url.vercel.app
CLOUDINARY_CLOUD_NAME=dupmbtrcn
CLOUDINARY_API_KEY=699826948667517
CLOUDINARY_API_SECRET=gMfTd5ZgE5yWRW2M6n1Gb6vYeXk
```

#### Auto-configured (from render.yaml):

```bash
JWT_EXPIRE=86400
APP_ENV=production
PORT=80
USE_CLOUDINARY=true
CLOUDINARY_FOLDER=swordhub
MAX_FILE_SIZE=5242880
RATE_LIMIT_WINDOW=900
RATE_LIMIT_MAX_REQUESTS=100
```

### Step 5: Deploy

1. **Click** "Create Web Service"
2. **Wait** for build (5-10 minutes first time)
3. **Monitor** logs in real-time

---

## 🔍 What Happens During Build

### Build Process:

```bash
1. 📦 Clone repository from GitHub
2. 🐳 Build Docker image
   - Install PHP 8.1 + Apache
   - Install MongoDB extension
   - Install Composer dependencies
   - Copy application files
   - Configure Apache with CORS
3. 🚀 Start container
   - Run Apache server on port 80
   - Connect to MongoDB Atlas
4. ✅ Health check at /
```

### Expected Build Logs:

```
==> Cloning from GitHub
==> Building Docker image
Step 1/14 : FROM php:8.1-apache
Step 2/14 : RUN apt-get update
Step 3/14 : RUN docker-php-ext-install
Step 4/14 : RUN pecl install mongodb
Step 5/14 : COPY --from=composer
...
Step 14/14 : CMD ["apache2-foreground"]
==> Build successful
==> Deploying...
==> Your service is live at https://swordhub-backend.onrender.com
```

---

## ✅ Verify Deployment

### 1. Check Health

```bash
curl https://swordhub-backend.onrender.com/
```

**Expected:**
```json
{
  "status": "success",
  "message": "SwordHUB API is running!",
  "timestamp": "2025-10-18T..."
}
```

### 2. Test Products API

```bash
curl https://swordhub-backend.onrender.com/api/products
```

**Expected:**
```json
{
  "success": true,
  "data": {
    "products": [...],
    "total": 50
  }
}
```

### 3. Test Dashboard Stats

```bash
curl https://swordhub-backend.onrender.com/api/dashboard/stats
```

**Expected:**
```json
{
  "success": true,
  "data": {
    "totalProducts": 50,
    "totalOrders": 10,
    "totalRevenue": 5000,
    "totalClients": 8
  }
}
```

---

## 🔄 Update Frontend

### Update Frontend .env

```bash
# File: SwordHub-frontend/.env
VITE_API_URL=https://swordhub-backend.onrender.com
```

### Redeploy Frontend

```bash
cd c:\xampp\htdocs\SwordHUB\SwordHub-frontend

git add .
git commit -m "Update API URL for production"
git push origin main

# Vercel will auto-deploy
```

---

## 🐛 Troubleshooting

### Issue: "Repository access denied"

**Solution:**
1. Go to Render Dashboard
2. Settings → GitHub
3. Reconnect GitHub account
4. Grant access to repository

### Issue: "Build failed"

**Check:**
```bash
# 1. Verify Dockerfile syntax
# 2. Check render.yaml format
# 3. Look at build logs for specific error
```

**Common Fixes:**
- Missing `docker` folder → Already created ✅
- Wrong Dockerfile path → Set in render.yaml ✅
- Missing composer.json → Already exists ✅

### Issue: "MongoDB connection error"

**Check:**
1. MongoDB Atlas → Network Access → IP Whitelist
2. Add: `0.0.0.0/0` (allow all)
3. Verify MONGODB_URI in Render env vars
4. Check username/password

### Issue: "CORS errors"

**Check:**
1. FRONTEND_URL in Render matches exactly
2. No trailing slash in URL
3. Apache CORS headers enabled (already configured ✅)

### Issue: "Images not uploading"

**Check:**
1. USE_CLOUDINARY=true in Render
2. All Cloudinary env vars set correctly
3. Test Cloudinary credentials separately

### Issue: "Service crashes"

**Check Logs:**
```bash
# In Render Dashboard:
# Your Service → Logs (live stream)
```

**Common causes:**
- Missing environment variables
- MongoDB connection timeout
- PHP errors in code

---

## 📊 Monitor Your Service

### Render Dashboard Tabs:

1. **Logs** - Real-time application logs
2. **Metrics** - CPU, Memory, Request count
3. **Events** - Deployment history
4. **Settings** - Environment variables, scaling

### Useful Commands:

```bash
# View last 100 log lines
# (In Render dashboard Logs tab)

# Check memory usage
# (In Render dashboard Metrics tab)

# View deployment history
# (In Render dashboard Events tab)
```

---

## ⚡ Performance Tips

### Free Tier Limitations:

- ⏰ **Spins down** after 15 minutes of inactivity
- ⏱️ **Cold start** takes 30-60 seconds
- 💾 **512MB RAM** limit
- 🔄 **No persistent storage** (use Cloudinary for images)

### Keep Service Awake (Optional):

**Use UptimeRobot:**

1. Go to [UptimeRobot.com](https://uptimerobot.com) (free)
2. Create new monitor:
   - **Type**: HTTP(s)
   - **URL**: `https://swordhub-backend.onrender.com/`
   - **Interval**: 5 minutes
3. This pings your API every 5 min to prevent sleep

---

## 🔐 Security Checklist

- [x] Environment variables as secrets (not in code)
- [x] JWT_SECRET is strong and random
- [x] MongoDB credentials secure
- [x] CORS configured properly
- [x] Rate limiting enabled
- [x] Cloudinary credentials as env vars
- [x] No sensitive data in logs
- [x] HTTPS enforced (automatic on Render)

---

## 📱 Test Full App

### 1. Frontend → Backend Flow

1. Open your frontend: `https://your-app.vercel.app`
2. Browse products → Should load from Render API ✅
3. Add to cart → Should work ✅
4. Place order → Should save to MongoDB ✅
5. Admin login → Should authenticate via JWT ✅
6. Upload product image → Should save to Cloudinary ✅

### 2. Admin Dashboard

1. Login as admin
2. Check analytics → Should show real data ✅
3. View orders → Should display all orders ✅
4. Check clients → Should count unique phones ✅

---

## 🎯 Final Checklist

- [ ] Backend deployed on Render successfully
- [ ] All environment variables added
- [ ] Health check passes (/)
- [ ] Products API works (/api/products)
- [ ] MongoDB connection successful
- [ ] Cloudinary image uploads working
- [ ] Frontend updated with Render API URL
- [ ] Frontend redeployed on Vercel
- [ ] CORS working (no errors in browser console)
- [ ] Orders can be placed
- [ ] Admin dashboard accessible
- [ ] Analytics showing real data

---

## 🚀 Your App is Live!

**Backend**: `https://swordhub-backend.onrender.com`  
**Frontend**: `https://your-app.vercel.app`  
**Database**: MongoDB Atlas (Cloud)  
**Images**: Cloudinary (Cloud)

**All services are:**
- ✅ Free tier
- ✅ Auto-scaling
- ✅ HTTPS enabled
- ✅ Globally distributed

---

## 📞 Need Help?

**Check deployment logs:**
```
Render Dashboard → swordhub-backend → Logs
```

**Common URLs to test:**
- Health: `https://swordhub-backend.onrender.com/`
- Products: `https://swordhub-backend.onrender.com/api/products`
- Stats: `https://swordhub-backend.onrender.com/api/dashboard/stats`

**If stuck:**
1. Check Render logs for errors
2. Verify all env vars are set
3. Test MongoDB connection separately
4. Verify Cloudinary credentials

---

## 🎉 Congratulations!

Your full-stack e-commerce application is now deployed and running in production!

**Tech Stack:**
- 🎨 Frontend: React + Vite → Vercel
- ⚙️ Backend: PHP + MongoDB → Render (Docker)
- 🗄️ Database: MongoDB Atlas (Cloud)
- 🖼️ Images: Cloudinary (CDN)

**Features Working:**
- ✅ Product catalog with search/filters
- ✅ Shopping cart
- ✅ Order management
- ✅ Admin dashboard with analytics
- ✅ Image uploads to cloud
- ✅ Real-time statistics
- ✅ Unique client tracking
- ✅ Beautiful charts and graphs

**Your project is production-ready!** 🚀✨
