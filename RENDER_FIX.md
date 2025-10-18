# 🔧 Fix: Render Dockerfile Error

## ❌ Error You're Seeing

```
error: failed to solve: failed to read dockerfile: read /home/user/.local/tmp/buildkit-mount2700050060/src: is a directory
```

## 🎯 Root Cause

Render is **NOT using your `render.yaml` file**. It's trying to auto-detect settings and looking in the wrong place.

---

## ✅ Solution: Manual Configuration

### Step 1: Delete Current Service (If Created)

1. Go to: https://dashboard.render.com
2. Find your service: `swordhub-backend`
3. Click **Settings** (bottom left)
4. Scroll to bottom → **Delete Web Service**
5. Confirm deletion

---

### Step 2: Create New Service with Correct Settings

#### 2.1 Click "New +" → "Web Service"

#### 2.2 Connect GitHub Repository
- Select: `Oussamabassor/SwordHUB-backend`
- Click **Connect**

#### 2.3 Configure Build Settings

**IMPORTANT**: Fill these EXACT values:

| Setting | Value |
|---------|-------|
| **Name** | `swordhub-backend` |
| **Region** | `Frankfurt (EU Central)` |
| **Branch** | `main` |
| **Runtime** | `Docker` ⚠️ CRITICAL |
| **Dockerfile Path** | `./Dockerfile` |
| **Docker Build Context Directory** | `.` (just a dot) |
| **Instance Type** | `Free` |

#### 2.4 Add Environment Variables

Click **Add Environment Variable** for each:

```bash
# Required - Add these manually
MONGODB_URI=mongodb+srv://SWORDHUB:sword1234@cluster0.ryhv1ve.mongodb.net/
MONGODB_DATABASE=swordhub
JWT_SECRET=ED*8]EF=+h?)so{]Evvh6ACORU'R)Q-9j|;ydtJN1L|'<LFR'E;]tR[PzgEw(/_
JWT_EXPIRE=86400
FRONTEND_URL=https://your-frontend.vercel.app
APP_ENV=production
PORT=80
USE_CLOUDINARY=true
CLOUDINARY_CLOUD_NAME=dupmbtrcn
CLOUDINARY_API_KEY=699826948667517
CLOUDINARY_API_SECRET=gMfTd5ZgE5yWRW2M6n1Gb6vYeXk
CLOUDINARY_FOLDER=swordhub
MAX_FILE_SIZE=5242880
RATE_LIMIT_WINDOW=900
RATE_LIMIT_MAX_REQUESTS=100
```

#### 2.5 Auto-Deploy Settings

- ✅ **Auto-Deploy**: `Yes` (Enable)
- **Health Check Path**: `/` (leave default or set to `/`)

#### 2.6 Click "Create Web Service"

---

## 🎯 What Will Happen

1. Render will clone your GitHub repo
2. Find `Dockerfile` in root directory
3. Build Docker image (5-10 minutes)
4. Deploy container
5. Run health checks
6. Service goes live! 🚀

---

## 📊 Watch Build Progress

After clicking "Create Web Service":

1. You'll be taken to the **Logs** page automatically
2. Watch for these steps:

```bash
==> Cloning repository
==> Checking out commit...
==> Building Docker image
    Step 1/14: FROM php:8.1-apache
    Step 2/14: RUN apt-get update
    Step 3/14: RUN docker-php-ext-install
    Step 4/14: RUN pecl install mongodb
    ...
    Step 14/14: CMD apache2-foreground
==> Build successful!
==> Starting service...
==> Deploy successful!
Your service is live at https://swordhub-backend.onrender.com
```

---

## ⚠️ Common Mistakes to Avoid

### ❌ Wrong Runtime
If you select **Node**, **Python**, or **Go** instead of **Docker**, it won't work!

### ❌ Wrong Dockerfile Path
- ✅ Correct: `./Dockerfile`
- ❌ Wrong: `Dockerfile` (missing `./`)
- ❌ Wrong: `/Dockerfile` (absolute path)
- ❌ Wrong: `src/Dockerfile` (wrong directory)

### ❌ Wrong Build Context
- ✅ Correct: `.` (just a dot)
- ❌ Wrong: `./` 
- ❌ Wrong: `/`
- ❌ Wrong: `src`

---

## 🔍 Verification After Deploy

### Test Health Endpoint
```bash
curl https://swordhub-backend.onrender.com/
```

**Expected Response**:
```json
{
  "status": "success",
  "message": "SwordHUB API is running!",
  "version": "1.0",
  "timestamp": "2025-10-18T..."
}
```

### Test Products API
```bash
curl https://swordhub-backend.onrender.com/api/products
```

### Test Stats API
```bash
curl https://swordhub-backend.onrender.com/api/dashboard/stats
```

---

## 🎉 After Successful Deployment

### 1. Get Your URL
Render will give you: `https://swordhub-backend.onrender.com`

### 2. Update Frontend
```bash
cd c:\xampp\htdocs\SwordHUB\SwordHub-frontend

# Edit .env
VITE_API_URL=https://swordhub-backend.onrender.com

# Commit and push
git add .env
git commit -m "Update API URL to Render production"
git push origin main
```

### 3. Vercel Auto-Deploys
Your frontend will automatically redeploy with the new API URL!

### 4. Test Complete App
- ✅ Browse products
- ✅ Add to cart
- ✅ Place order
- ✅ Login as admin
- ✅ Check dashboard
- ✅ Manage orders

---

## 🆘 If Build Still Fails

### Error: "Cannot find Dockerfile"
**Solution**: Check that `Dockerfile` is in the root of your repo (not in a subdirectory)

### Error: "pecl install mongodb failed"
**Solution**: Already using PHP 8.1, should work. Try rebuilding.

### Error: "composer install failed"
**Solution**: Check that `composer.json` and `composer.lock` are committed

### Error: "Permission denied on uploads/"
**Solution**: Already handled in Dockerfile with `chown www-data`

---

## 📞 Need Help?

### Check These:
1. **Logs**: Render Dashboard → Your Service → Logs
2. **Events**: Render Dashboard → Your Service → Events  
3. **Settings**: Verify all env vars are set
4. **Health**: Check health check is passing

### Render Support:
- Dashboard: https://dashboard.render.com
- Docs: https://render.com/docs
- Community: https://community.render.com

---

## ✅ Success Checklist

- [ ] Deleted old service (if existed)
- [ ] Created new service with **Docker runtime**
- [ ] Set Dockerfile path to `./Dockerfile`
- [ ] Set build context to `.`
- [ ] Added all 15 environment variables
- [ ] Enabled auto-deploy
- [ ] Build completed successfully
- [ ] Service is live
- [ ] Health check passing
- [ ] API endpoints responding
- [ ] Frontend updated with new URL
- [ ] Full app tested and working

---

## 🚀 Ready to Deploy!

Follow the steps above carefully, especially:
1. ⚠️ **Runtime must be "Docker"**
2. ⚠️ **Dockerfile Path must be "./Dockerfile"**
3. ⚠️ **Build Context must be "."**

These are the critical settings that fix your error!

Good luck! 🎉
