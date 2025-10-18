# 🚀 Docker Deployment Guide for Render.com

## 📋 Prerequisites

1. ✅ GitHub repository with your code
2. ✅ Render.com account (free tier available)
3. ✅ MongoDB Atlas account (M0 free tier)
4. ✅ Cloudinary account (free tier)

---

## 🔧 Configuration Files Created

### 1. **Dockerfile**
- Uses PHP 8.1 with Apache
- Installs MongoDB extension
- Includes Composer
- Sets up proper permissions
- Enables CORS

### 2. **render.yaml**
- Render deployment configuration
- Environment variables setup
- Health check endpoint
- Auto-deployment enabled

### 3. **.htaccess**
- URL rewriting for clean URLs
- CORS headers
- Handles OPTIONS requests

### 4. **docker/000-default.conf**
- Apache virtual host configuration
- CORS headers
- Document root setup

### 5. **.dockerignore**
- Excludes unnecessary files from Docker image
- Reduces image size
- Improves build speed

---

## 🌐 Step 1: Update Environment Variables

### **Required Environment Variables for Render:**

Copy these from your local `.env` file:

```bash
MONGODB_URI=mongodb+srv://SWORDHUB:sword1234@cluster0.ryhv1ve.mongodb.net/
MONGODB_DATABASE=swordhub
JWT_SECRET=ED*8]EF=+h?)so{]Evvh6ACORU'R)Q-9j|;ydtJN1L|'<LFR'E;]tR[PzgEw(/_
FRONTEND_URL=https://your-frontend-url.vercel.app
CLOUDINARY_CLOUD_NAME=dupmbtrcn
CLOUDINARY_API_KEY=699826948667517
CLOUDINARY_API_SECRET=gMfTd5ZgE5yWRW2M6n1Gb6vYeXk
```

### **Auto-configured Variables:**
These are already set in `render.yaml`:
- APP_ENV=production
- PORT=80
- USE_CLOUDINARY=true
- CLOUDINARY_FOLDER=swordhub
- JWT_EXPIRE=86400
- MAX_FILE_SIZE=5242880
- RATE_LIMIT_WINDOW=900
- RATE_LIMIT_MAX_REQUESTS=100

---

## 🚀 Step 2: Deploy to Render.com

### **Option A: Automatic Deployment (Recommended)**

1. **Push to GitHub:**
   ```bash
   cd SwordHub-backend
   git add .
   git commit -m "Add Docker configuration for Render deployment"
   git push origin main
   ```

2. **Connect to Render:**
   - Go to [render.com](https://render.com)
   - Click **"New +"** → **"Blueprint"**
   - Connect your GitHub repository
   - Select `SwordHub-backend` repository
   - Render will detect `render.yaml` automatically
   - Click **"Apply"**

3. **Add Environment Variables:**
   - After blueprint is applied, go to your web service
   - Click **"Environment"** tab
   - Add the **Required Environment Variables** (see Step 1)
   - Click **"Save Changes"**

4. **Deploy:**
   - Render will automatically build and deploy
   - Wait 5-10 minutes for first deployment
   - Your API will be live at: `https://your-app-name.onrender.com`

### **Option B: Manual Deployment**

1. **Push to GitHub** (same as above)

2. **Create New Web Service:**
   - Go to [render.com](https://render.com)
   - Click **"New +"** → **"Web Service"**
   - Connect your GitHub repository
   - Configure:
     - **Name:** swordhub-backend
     - **Region:** Frankfurt (or closest to you)
     - **Branch:** main
     - **Runtime:** Docker
     - **Plan:** Free

3. **Add Environment Variables** (same as Option A)

4. **Deploy** (automatic after setup)

---

## 🔍 Step 3: Test Your Deployment

### **Test Endpoints:**

```bash
# Health check
curl https://your-app-name.onrender.com/

# Test products API
curl https://your-app-name.onrender.com/api/products

# Test stats
curl https://your-app-name.onrender.com/api/dashboard/stats
```

### **Expected Response:**
```json
{
  "success": true,
  "message": "SwordHub API is running",
  "version": "1.0",
  "endpoints": [...]
}
```

---

## 🔗 Step 4: Update Frontend

Update your frontend `.env`:

```bash
# Before (local)
VITE_API_URL=http://localhost:5000

# After (production)
VITE_API_URL=https://your-app-name.onrender.com
```

Then redeploy your frontend to Vercel.

---

## 🔒 Step 5: Update CORS Settings

In Render dashboard, update `FRONTEND_URL`:

```bash
FRONTEND_URL=https://your-frontend.vercel.app
```

This will allow your frontend to make API calls.

---

## 📊 Monitoring & Logs

### **View Logs:**
- Go to Render dashboard
- Select your service
- Click **"Logs"** tab
- See real-time logs

### **Common Issues & Solutions:**

**1. "MongoDB connection failed"**
```bash
# Fix: Update MongoDB IP whitelist
- Go to MongoDB Atlas
- Network Access → Add IP Address
- Add: 0.0.0.0/0 (allow all)
```

**2. "CORS error"**
```bash
# Fix: Check FRONTEND_URL matches exactly
FRONTEND_URL=https://your-frontend.vercel.app
# No trailing slash!
```

**3. "502 Bad Gateway"**
```bash
# Fix: Wait for deployment to complete
# First deployment takes 5-10 minutes
# Check "Events" tab for progress
```

**4. "Image upload fails"**
```bash
# Fix: Ensure Cloudinary variables are set
USE_CLOUDINARY=true
CLOUDINARY_CLOUD_NAME=your-cloud-name
CLOUDINARY_API_KEY=your-api-key
CLOUDINARY_API_SECRET=your-api-secret
```

---

## 🎯 Important Notes

### **Free Tier Limitations:**

1. **Cold Starts:**
   - Service sleeps after 15 minutes of inactivity
   - First request after sleep takes 30-60 seconds
   - Use [UptimeRobot](https://uptimerobot.com) to ping every 5 minutes (keeps it awake)

2. **Monthly Limits:**
   - 750 hours/month (free tier)
   - 100GB bandwidth
   - Enough for development/portfolio

3. **Performance:**
   - Shared CPU
   - 512MB RAM
   - Good for low-traffic sites

### **Production Recommendations:**

For production use:
- ✅ Upgrade to paid plan ($7/month) - no cold starts
- ✅ Enable auto-scaling
- ✅ Set up custom domain
- ✅ Configure CDN (Cloudinary already does this for images)
- ✅ Enable SSL (automatic on Render)

---

## 🔄 Auto-Deployment

Once set up, every push to `main` branch will:
1. Trigger automatic rebuild
2. Run Docker build
3. Deploy new version
4. Zero-downtime deployment

---

## 📝 Environment Variables Checklist

Before deploying, ensure these are set in Render:

- [ ] MONGODB_URI
- [ ] MONGODB_DATABASE
- [ ] JWT_SECRET
- [ ] FRONTEND_URL
- [ ] CLOUDINARY_CLOUD_NAME
- [ ] CLOUDINARY_API_KEY
- [ ] CLOUDINARY_API_SECRET

Auto-configured (in render.yaml):
- [x] APP_ENV
- [x] PORT
- [x] USE_CLOUDINARY
- [x] CLOUDINARY_FOLDER
- [x] JWT_EXPIRE
- [x] MAX_FILE_SIZE
- [x] RATE_LIMIT_WINDOW
- [x] RATE_LIMIT_MAX_REQUESTS

---

## 🎉 Success!

Your backend is now running on Render.com with:
- ✅ Docker containerization
- ✅ MongoDB Atlas connection
- ✅ Cloudinary image hosting
- ✅ CORS configured
- ✅ Auto-deployment
- ✅ SSL/HTTPS enabled
- ✅ Global CDN

**Your app is production-ready!** 🚀

---

## 🆘 Support

If you encounter issues:

1. Check Render logs
2. Verify environment variables
3. Test MongoDB connection
4. Check Cloudinary credentials
5. Verify CORS settings

**Your deployment URL will be:**
`https://swordhub-backend.onrender.com`

Update this in your frontend and enjoy your deployed app! 🎊
