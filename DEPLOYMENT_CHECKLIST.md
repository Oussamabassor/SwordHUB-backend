# 🚀 Complete Deployment Checklist

**Last Updated:** Ready for production deployment with Cloudinary integration

---

## ✅ Pre-Deployment Checklist

### 1. Cloudinary Setup (REQUIRED)

- [ ] Sign up at [cloudinary.com](https://cloudinary.com) (free account)
- [ ] Get credentials from dashboard:
  - Cloud Name: `dxxxxx`
  - API Key: `123456789012345`
  - API Secret: `abcdefghijklmnopqrstuvwxyz`
- [ ] Test credentials locally (set `USE_CLOUDINARY=true` in local `.env`)
- [ ] Upload test image to verify connection

### 2. MongoDB Atlas Setup

- [ ] Update IP Whitelist to `0.0.0.0/0` in Network Access
- [ ] Get connection string: `mongodb+srv://...`
- [ ] Test connection locally
- [ ] Ensure database name is `swordhub`

### 3. GitHub Repository

- [ ] All code pushed to GitHub
- [ ] Repository is public or connected to Render
- [ ] Branch: `main` or `master`
- [ ] Files included:
  - `render.yaml` ✅
  - `.renderignore` ✅
  - `composer.json` with Cloudinary SDK ✅
  - All PHP files ✅

### 4. Environment Variables Ready

- [ ] MongoDB URI (connection string)
- [ ] JWT Secret (64+ character random string)
- [ ] Cloudinary credentials (3 values)
- [ ] Frontend URL (will update after Vercel deploy)

---

## 🔧 Backend Deployment (Render.com)

### Step 1: Create Web Service

1. [ ] Go to [render.com](https://render.com)
2. [ ] Sign up with GitHub
3. [ ] Click **New +** → **Web Service**
4. [ ] Connect your GitHub repository: `swordhub-backend`

### Step 2: Configure Service

**Basic:**
- [ ] Name: `swordhub-backend`
- [ ] Region: **Oregon (US West)**
- [ ] Branch: `main`
- [ ] Root Directory: (leave blank)

**Build:**
- [ ] Runtime: **PHP**
- [ ] Build Command:
  ```bash
  composer install --no-dev --optimize-autoloader
  ```
- [ ] Start Command:
  ```bash
  php -S 0.0.0.0:$PORT -t .
  ```

**Instance:**
- [ ] Plan: **FREE** (750 hrs/month)

### Step 3: Add Environment Variables

Click **Environment** tab and add:

```env
# MongoDB
MONGODB_URI=mongodb+srv://username:password@cluster.xxxxx.mongodb.net/?retryWrites=true&w=majority
MONGODB_DATABASE=swordhub

# JWT
JWT_SECRET=your_64_character_random_secret_here
JWT_EXPIRE=86400

# Cloudinary (CRITICAL for image uploads)
USE_CLOUDINARY=true
CLOUDINARY_CLOUD_NAME=your_cloud_name
CLOUDINARY_API_KEY=your_api_key
CLOUDINARY_API_SECRET=your_api_secret
CLOUDINARY_FOLDER=swordhub/products

# Frontend (temporary, update after Vercel deploy)
FRONTEND_URL=http://localhost:5173

# App
APP_ENV=production
UPLOAD_DIR=./uploads
MAX_FILE_SIZE=5242880
```

### Step 4: Deploy

- [ ] Click **Create Web Service**
- [ ] Wait 3-5 minutes for build
- [ ] Check **Logs** tab for any errors
- [ ] Copy your backend URL: `https://swordhub-backend.onrender.com`

### Step 5: Verify Backend

Test endpoints:

```bash
# Health check
curl https://swordhub-backend.onrender.com/health

# Products endpoint
curl https://swordhub-backend.onrender.com/api/products

# Expected: JSON response (not 502 error)
```

---

## 🎨 Frontend Deployment (Vercel)

### Step 1: Prepare Frontend

1. [ ] Update `.env.production`:
   ```env
   VITE_API_BASE_URL=https://swordhub-backend.onrender.com
   ```
2. [ ] Push to GitHub
3. [ ] Test build locally:
   ```bash
   npm run build
   ```

### Step 2: Deploy to Vercel

1. [ ] Go to [vercel.com](https://vercel.com)
2. [ ] Sign up with GitHub
3. [ ] Click **New Project**
4. [ ] Import `swordhub-frontend` repository
5. [ ] Configure:
   - Framework Preset: **Vite**
   - Build Command: `npm run build`
   - Output Directory: `dist`
   - Install Command: `npm install`

### Step 3: Add Environment Variables

In Vercel project settings:

```env
VITE_API_BASE_URL=https://swordhub-backend.onrender.com
```

### Step 4: Deploy

- [ ] Click **Deploy**
- [ ] Wait 2-3 minutes
- [ ] Copy your frontend URL: `https://swordhub.vercel.app`

---

## 🔄 Final Configuration Updates

### Update Backend CORS

1. [ ] Go to Render dashboard
2. [ ] Click your backend service
3. [ ] Go to **Environment** tab
4. [ ] Update `FRONTEND_URL`:
   ```env
   FRONTEND_URL=https://swordhub.vercel.app
   ```
5. [ ] Click **Save Changes** (auto-redeploys)

### Update Frontend API URL (if needed)

If you changed backend URL:
1. [ ] Go to Vercel dashboard
2. [ ] Click your project
3. [ ] Go to **Settings** → **Environment Variables**
4. [ ] Update `VITE_API_BASE_URL`
5. [ ] Redeploy: **Deployments** → **Redeploy**

---

## 🧪 Testing Checklist

### Backend Tests

- [ ] Health check: `https://YOUR-BACKEND.onrender.com/health`
- [ ] Products API: `https://YOUR-BACKEND.onrender.com/api/products`
- [ ] Auth register: Test with Postman/Thunder Client
- [ ] Image upload: Test via admin dashboard
- [ ] Cloudinary: Verify images appear in Cloudinary Media Library

### Frontend Tests

- [ ] Homepage loads
- [ ] Products display with images
- [ ] User registration works
- [ ] User login works
- [ ] Admin login works
- [ ] Admin dashboard accessible
- [ ] Add product with image upload (**MOST IMPORTANT**)
- [ ] Product images display correctly
- [ ] Cart functionality
- [ ] Checkout process

### Image Upload Test (CRITICAL)

1. [ ] Login to admin dashboard
2. [ ] Go to Products Management
3. [ ] Click "Add Product"
4. [ ] Upload an image
5. [ ] Submit form
6. [ ] Verify:
   - [ ] Image appears in product list
   - [ ] Image is in Cloudinary (check dashboard)
   - [ ] Image URL starts with `https://res.cloudinary.com/...`
   - [ ] Image loads fast (CDN)

---

## 🐛 Common Issues & Solutions

### Issue: Image Upload Returns Error

**Check:**
1. Cloudinary credentials are correct in Render environment variables
2. `USE_CLOUDINARY=true` is set
3. File size is under 5MB
4. File type is JPG, PNG, GIF, or WebP

**Debug:**
- Check Render logs: Dashboard → Your Service → Logs
- Look for "Cloudinary" errors
- Verify credentials in Cloudinary dashboard

### Issue: Images Don't Display

**Solutions:**
1. Check browser console for CORS errors
2. Verify image URL format (should be Cloudinary URL)
3. Check Cloudinary Media Library to see if upload succeeded
4. Test image URL directly in browser

### Issue: "502 Bad Gateway"

**Solutions:**
1. Check Render logs for PHP errors
2. Verify all environment variables are set
3. Check MongoDB connection string
4. Wait 30 seconds (cold start on free tier)

### Issue: CORS Errors

**Solutions:**
1. Update `FRONTEND_URL` in Render to exact Vercel URL
2. No trailing slash in URL
3. Redeploy backend after changing env vars
4. Clear browser cache

---

## 📊 Post-Deployment Monitoring

### Daily Checks

- [ ] Backend uptime: Check Render dashboard
- [ ] Frontend uptime: Check Vercel dashboard
- [ ] Cloudinary usage: Check bandwidth (25GB/month free)
- [ ] MongoDB Atlas usage: Check storage (512MB free)

### Weekly Tasks

- [ ] Review Render logs for errors
- [ ] Check Cloudinary image count
- [ ] Monitor MongoDB database size
- [ ] Test critical user flows

### Optional: Keep App Warm

Use [UptimeRobot](https://uptimerobot.com) (free):

1. [ ] Sign up for free account
2. [ ] Add monitor:
   - Type: HTTP(s)
   - URL: `https://YOUR-BACKEND.onrender.com/health`
   - Interval: 5 minutes
3. [ ] Enable email alerts

This prevents cold starts by pinging your backend every 5 minutes.

---

## 🎉 Success Metrics

Your deployment is successful when:

- ✅ Backend responds to API calls
- ✅ Frontend loads without errors
- ✅ Users can register and login
- ✅ Admin can add products
- ✅ **Admin can upload images via dashboard**
- ✅ **Images are stored in Cloudinary**
- ✅ **Images display on frontend**
- ✅ Cart and checkout work
- ✅ No CORS errors in browser console

---

## 🔗 Important URLs

**After deployment, save these:**

- Backend URL: `https://_____.onrender.com`
- Frontend URL: `https://_____.vercel.app`
- Cloudinary Dashboard: `https://console.cloudinary.com`
- MongoDB Atlas: `https://cloud.mongodb.com`
- Render Dashboard: `https://dashboard.render.com`
- Vercel Dashboard: `https://vercel.com/dashboard`

---

## 📞 Need Help?

If you encounter issues:

1. Check `TROUBLESHOOTING.md`
2. Check `RENDER_DEPLOY_GUIDE.md`
3. Check `CLOUDINARY_SETUP.md`
4. Review Render logs
5. Review browser console errors
6. Test locally first with `USE_CLOUDINARY=true`

---

## 🎯 Deployment Timeline

**Estimated time:** 30-45 minutes

- Cloudinary setup: 5 min
- Backend deployment: 10 min
- Frontend deployment: 10 min
- Configuration updates: 5 min
- Testing: 10 min

---

**Ready to deploy? Start with Cloudinary setup!** 🚀

Check `CLOUDINARY_SETUP.md` for detailed Cloudinary instructions.
