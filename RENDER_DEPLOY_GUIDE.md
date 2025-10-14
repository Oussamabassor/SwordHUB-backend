# 🚀 Render.com Deployment Guide (100% FREE)

Complete step-by-step guide to deploy SwordHUB backend on Render.com's free tier.

---

## ✅ Prerequisites Checklist

Before starting, ensure you have:

- [ ] GitHub account (free)
- [ ] Render.com account (free - sign up at [render.com](https://render.com))
- [ ] MongoDB Atlas M0 cluster (512MB free tier)
- [ ] Your backend code pushed to GitHub repository
- [ ] All required environment variables ready

---

## 📋 Step 1: Prepare MongoDB Atlas

### Update IP Whitelist (CRITICAL)

1. Go to [MongoDB Atlas](https://cloud.mongodb.com)
2. Select your cluster
3. Click **Network Access** (left sidebar)
4. Click **ADD IP ADDRESS**
5. Click **ALLOW ACCESS FROM ANYWHERE**
6. IP Address will be: `0.0.0.0/0`
7. Add description: "Render.com deployment"
8. Click **Confirm**

⚠️ **This is required** because Render uses dynamic IPs that change frequently.

### Get Connection String

1. Click **Database** (left sidebar)
2. Click **Connect** on your cluster
3. Choose **Connect your application**
4. Copy the connection string:
   ```
   mongodb+srv://<username>:<password>@cluster.xxxxx.mongodb.net/?retryWrites=true&w=majority
   ```
5. Replace `<username>` and `<password>` with your actual credentials
6. Save this - you'll need it for environment variables

---

## 📋 Step 2: Push Code to GitHub

### Create GitHub Repository

```bash
# Navigate to backend directory
cd c:\xampp\htdocs\SwordHUB\SwordHub-backend

# Initialize git (if not already done)
git init

# Add all files
git add .

# Commit
git commit -m "Prepare backend for Render deployment"

# Add remote repository
git remote add origin https://github.com/YOUR_USERNAME/swordhub-backend.git

# Push to GitHub
git push -u origin main
```

### Verify Files

Make sure these files are in your repository:
- ✅ `render.yaml` (Render configuration)
- ✅ `.renderignore` (Files to exclude)
- ✅ `composer.json` (PHP dependencies)
- ✅ All PHP files (index.php, controllers, models, etc.)

---

## 📋 Step 3: Create Render Web Service

### Sign Up / Log In

1. Go to [render.com](https://render.com)
2. Click **Get Started for Free**
3. Sign up with GitHub (recommended) or email

### Create New Web Service

1. Click **New +** (top right)
2. Select **Web Service**
3. Connect your GitHub repository:
   - Click **Connect account** (if first time)
   - Search for `swordhub-backend`
   - Click **Connect**

### Configure Service

**Basic Settings:**
- **Name**: `swordhub-backend` (or any name you prefer)
- **Region**: Oregon (US West) - or closest to your users
- **Branch**: `main`
- **Root Directory**: Leave blank (or `.` if prompted)

**Build Settings:**
- **Runtime**: PHP
- **Build Command**: 
  ```
  composer install --no-dev --optimize-autoloader
  ```
- **Start Command**:
  ```
  php -S 0.0.0.0:$PORT -t .
  ```

**Instance Type:**
- **Plan**: FREE
  - 750 hours/month (24/7 uptime)
  - 512 MB RAM
  - Shared CPU
  - Cold starts after 15 min inactivity

---

## 📋 Step 4: Configure Environment Variables

Click **Environment** tab and add these variables:

### Required Variables:

1. **MONGODB_URI**
   ```
   mongodb+srv://username:password@cluster.xxxxx.mongodb.net/swordhub?retryWrites=true&w=majority
   ```
   ⚠️ Replace with your actual MongoDB connection string

2. **JWT_SECRET**
   ```
   your-super-secret-jwt-key-change-this-in-production
   ```
   ⚠️ Use a strong random string (at least 32 characters)

3. **CORS_ORIGIN**
   ```
   https://your-frontend-domain.vercel.app
   ```
   ⚠️ You'll update this after deploying frontend

4. **UPLOAD_DIR**
   ```
   uploads/products
   ```

### Optional Variables:

5. **PHP_VERSION** (if needed)
   ```
   8.1
   ```

---

## 📋 Step 5: Deploy

1. Click **Create Web Service**
2. Render will start building your app:
   - Installing Composer dependencies
   - Running build command
   - Starting PHP server
3. Wait 2-5 minutes for first deployment
4. Check **Logs** tab for build progress

### Deployment Status

You'll see:
- ✅ **Build successful** - Dependencies installed
- ✅ **Deploy live** - Server started
- Your service URL: `https://swordhub-backend.onrender.com`

---

## 📋 Step 6: Verify Backend

### Test Health Check

1. Copy your Render service URL
2. Open in browser: `https://swordhub-backend.onrender.com/`
3. Should see your API response (not an error)

### Test API Endpoints

```bash
# Test products endpoint
curl https://swordhub-backend.onrender.com/api/products/read.php

# Test root
curl https://swordhub-backend.onrender.com/
```

### Check Logs

If something fails:
1. Go to Render dashboard
2. Click your service
3. Click **Logs** tab
4. Look for errors

---

## 📋 Step 7: Update Frontend

### Update API Base URL

In your frontend `.env` file:

```env
VITE_API_BASE_URL=https://swordhub-backend.onrender.com
```

### Update CORS Origin (Backend)

1. Go back to Render dashboard
2. Click your backend service
3. Click **Environment** tab
4. Find **CORS_ORIGIN** variable
5. Update to your Vercel frontend URL
6. Click **Save Changes**

Service will automatically redeploy.

---

## 🚀 Common Issues & Solutions

### Issue: "502 Bad Gateway"

**Cause**: Server not starting properly

**Solutions**:
- Check Logs for PHP errors
- Verify `composer.json` is valid
- Ensure PHP version is 8.1+
- Check environment variables are set

### Issue: "Cold Start Delay"

**Cause**: Free tier spins down after 15 min of inactivity

**Solutions**:
- First request may take 30-60 seconds
- Use [UptimeRobot](https://uptimerobot.com) (free) to ping every 5 min
- Or accept cold starts (normal for free tier)

### Issue: "Database Connection Failed"

**Cause**: MongoDB IP whitelist or wrong connection string

**Solutions**:
- Verify `0.0.0.0/0` is in MongoDB Network Access
- Check `MONGODB_URI` environment variable
- Test connection string locally first
- Ensure database name is correct

### Issue: "CORS Errors"

**Cause**: Wrong CORS_ORIGIN value

**Solutions**:
- Update `CORS_ORIGIN` to exact Vercel URL (no trailing slash)
- Redeploy backend after change
- Clear browser cache
- Check browser console for actual error

### Issue: "File Upload Fails"

**Cause**: Render filesystem is ephemeral (resets on deploy)

**Solutions**:
- Use Cloudinary for permanent image storage
- See `DEPLOYMENT_GUIDE.md` for Cloudinary setup
- Update FileUpload.php to use Cloudinary SDK

---

## 🎯 Keep Your App Warm (Optional)

### Use UptimeRobot (Free)

1. Sign up at [uptimerobot.com](https://uptimerobot.com)
2. Create New Monitor:
   - **Monitor Type**: HTTP(s)
   - **URL**: `https://swordhub-backend.onrender.com/`
   - **Monitoring Interval**: 5 minutes
   - **Alert Contact**: Your email
3. Save

This pings your backend every 5 minutes, preventing cold starts during active hours.

---

## 📊 Render Free Tier Limits

| Feature | Free Tier |
|---------|-----------|
| **Uptime** | 750 hours/month (24/7) |
| **RAM** | 512 MB |
| **CPU** | Shared |
| **Build Time** | 15 min max |
| **Cold Starts** | After 15 min inactive |
| **Bandwidth** | 100 GB/month |
| **Custom Domain** | ✅ Yes (free SSL) |
| **Auto Deploy** | ✅ Yes (from GitHub) |

---

## 🔗 Useful Links

- **Render Dashboard**: https://dashboard.render.com
- **Render Docs**: https://render.com/docs
- **MongoDB Atlas**: https://cloud.mongodb.com
- **GitHub Repo**: https://github.com/YOUR_USERNAME/swordhub-backend

---

## 🎉 Next Steps

After backend is deployed:

1. ✅ Backend deployed on Render
2. ⏸️ Deploy frontend on Vercel (see DEPLOYMENT_GUIDE.md)
3. ⏸️ Update CORS_ORIGIN with Vercel URL
4. ⏸️ Test full application
5. ⏸️ (Optional) Add custom domain
6. ⏸️ (Optional) Set up UptimeRobot

---

## 📝 Maintenance

### View Logs
- Dashboard → Your Service → Logs tab

### Redeploy Manually
- Dashboard → Your Service → Manual Deploy → Deploy latest commit

### Update Environment Variables
- Dashboard → Your Service → Environment tab → Edit → Save Changes (auto-redeploys)

### Monitor Usage
- Dashboard → Your Service → Metrics tab

---

## 💡 Pro Tips

1. **First Deploy**: Wait 3-5 minutes, don't refresh repeatedly
2. **Cold Starts**: First request after inactivity takes 30-60 seconds (normal)
3. **Logs**: Always check logs first when troubleshooting
4. **Environment Variables**: Changes trigger automatic redeployment
5. **GitHub Sync**: Push to main branch triggers auto-deploy
6. **Database**: Always test connection string locally before deploying

---

**Need Help?** Check TROUBLESHOOTING.md for common deployment issues.

**Ready?** Let's deploy! 🚀
