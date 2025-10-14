# 🔐 Environment Variables Reference

Complete guide for all environment variables used in SwordHUB backend.

---

## 📋 Local Development (.env)

```env
# Server Configuration
PORT=5000
APP_ENV=development

# MongoDB Configuration (Local)
MONGODB_URI=mongodb://localhost:27017
MONGODB_DATABASE=swordhub

# JWT Configuration
JWT_SECRET=your_super_secret_jwt_key_change_this_in_production
JWT_EXPIRE=86400

# Frontend Configuration
FRONTEND_URL=http://localhost:5173

# File Upload Configuration
UPLOAD_DIR=./uploads
MAX_FILE_SIZE=5242880

# Cloudinary Configuration (Optional for local testing)
USE_CLOUDINARY=false
CLOUDINARY_CLOUD_NAME=your_cloud_name_here
CLOUDINARY_API_KEY=your_api_key_here
CLOUDINARY_API_SECRET=your_api_secret_here
CLOUDINARY_FOLDER=swordhub/products

# Security
RATE_LIMIT_WINDOW=900
RATE_LIMIT_MAX_REQUESTS=100
```

---

## 🚀 Production Deployment (Render.com)

Add these in Render Dashboard → Environment tab:

```env
# App Configuration
APP_ENV=production

# MongoDB Configuration (MongoDB Atlas)
MONGODB_URI=mongodb+srv://username:password@cluster.xxxxx.mongodb.net/?retryWrites=true&w=majority
MONGODB_DATABASE=swordhub

# JWT Configuration (IMPORTANT: Use strong secret!)
JWT_SECRET=GENERATE_A_VERY_LONG_RANDOM_STRING_AT_LEAST_64_CHARACTERS_LONG
JWT_EXPIRE=86400

# Frontend Configuration (Update after Vercel deployment)
FRONTEND_URL=https://your-frontend.vercel.app

# Cloudinary Configuration (REQUIRED for image uploads)
USE_CLOUDINARY=true
CLOUDINARY_CLOUD_NAME=your_cloud_name
CLOUDINARY_API_KEY=your_api_key
CLOUDINARY_API_SECRET=your_api_secret
CLOUDINARY_FOLDER=swordhub/products

# File Upload
UPLOAD_DIR=./uploads
MAX_FILE_SIZE=5242880

# Security
RATE_LIMIT_WINDOW=900
RATE_LIMIT_MAX_REQUESTS=100
```

---

## 📖 Variable Descriptions

### Server Configuration

**PORT**
- Default: `5000` (local), `$PORT` (Render auto-assigned)
- Description: Port number the server listens on

**APP_ENV**
- Values: `development` | `production`
- Description: Application environment
- Usage: Controls error display, logging, optimizations

---

### MongoDB Configuration

**MONGODB_URI**
- Local: `mongodb://localhost:27017`
- Production: `mongodb+srv://...` (MongoDB Atlas connection string)
- Description: MongoDB connection string
- Get from: MongoDB Atlas → Connect → Connect your application

**MONGODB_DATABASE**
- Default: `swordhub`
- Description: Database name
- Note: Must match across all environments

---

### JWT Configuration

**JWT_SECRET**
- Format: Long random string (64+ characters recommended)
- Description: Secret key for signing JWT tokens
- ⚠️ **CRITICAL**: Use different secrets for dev/prod
- Generate with: 
  ```bash
  # PowerShell
  -join ((48..57) + (65..90) + (97..122) | Get-Random -Count 64 | ForEach-Object {[char]$_})
  ```

**JWT_EXPIRE**
- Default: `86400` (24 hours in seconds)
- Description: Token expiration time
- Common values:
  - `3600` = 1 hour
  - `86400` = 24 hours
  - `604800` = 7 days

---

### Frontend Configuration

**FRONTEND_URL**
- Local: `http://localhost:5173`
- Production: `https://your-app.vercel.app`
- Description: Frontend URL for CORS configuration
- ⚠️ **IMPORTANT**: No trailing slash!
- Update after deploying frontend

---

### Cloudinary Configuration

**USE_CLOUDINARY**
- Values: `true` | `false`
- Local: `false` (use local storage for testing)
- Production: `true` (REQUIRED - Render has ephemeral storage)
- Description: Enables/disables Cloudinary image upload

**CLOUDINARY_CLOUD_NAME**
- Example: `dxxxxx`
- Get from: Cloudinary Dashboard → Product Environment Credentials
- Description: Your Cloudinary cloud name (unique identifier)

**CLOUDINARY_API_KEY**
- Example: `123456789012345`
- Get from: Cloudinary Dashboard → Product Environment Credentials
- Description: API key for authentication

**CLOUDINARY_API_SECRET**
- Example: `abcdefghijklmnopqrstuvwxyz`
- Get from: Cloudinary Dashboard → Product Environment Credentials
- Description: API secret for authentication
- ⚠️ **KEEP SECRET**: Never commit to Git!

**CLOUDINARY_FOLDER**
- Default: `swordhub/products`
- Description: Folder path in Cloudinary for organized storage
- Benefits: Keeps images organized, easy to find

---

### File Upload Configuration

**UPLOAD_DIR**
- Default: `./uploads`
- Description: Local directory for file uploads
- Note: Only used when `USE_CLOUDINARY=false`

**MAX_FILE_SIZE**
- Default: `5242880` (5MB in bytes)
- Description: Maximum allowed file size for uploads
- Common values:
  - `1048576` = 1MB
  - `5242880` = 5MB
  - `10485760` = 10MB

---

### Security Configuration

**RATE_LIMIT_WINDOW**
- Default: `900` (15 minutes in seconds)
- Description: Time window for rate limiting
- Example: Block after X requests in this window

**RATE_LIMIT_MAX_REQUESTS**
- Default: `100`
- Description: Maximum requests allowed per window
- Example: 100 requests per 15 minutes = ~6.7 requests/min

---

## 🔧 How to Set Variables

### Local Development

1. Copy `.env.example` to `.env`:
   ```bash
   cp .env.example .env
   ```

2. Edit `.env` with your values

3. Never commit `.env` to Git (already in `.gitignore`)

### Render.com Production

1. Go to [Render Dashboard](https://dashboard.render.com)
2. Click your service
3. Click **Environment** tab
4. Click **Add Environment Variable**
5. Enter **Key** and **Value**
6. Click **Save Changes** (triggers auto-redeploy)

**Bulk add:**
```
Key: MONGODB_URI
Value: mongodb+srv://...

Key: JWT_SECRET  
Value: your_secret

Key: USE_CLOUDINARY
Value: true
```

---

## ✅ Required Variables Checklist

### Minimum for Local Development
- [ ] `MONGODB_URI` (local MongoDB)
- [ ] `JWT_SECRET` (any strong string)
- [ ] `FRONTEND_URL` (localhost:5173)

### Required for Production
- [ ] `MONGODB_URI` (Atlas connection string)
- [ ] `MONGODB_DATABASE` (swordhub)
- [ ] `JWT_SECRET` (strong random 64+ chars)
- [ ] `FRONTEND_URL` (Vercel URL)
- [ ] `USE_CLOUDINARY=true`
- [ ] `CLOUDINARY_CLOUD_NAME`
- [ ] `CLOUDINARY_API_KEY`
- [ ] `CLOUDINARY_API_SECRET`

---

## 🧪 Testing Variables

Test if variables are loaded:

```php
<?php
require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

echo "MongoDB URI: " . ($_ENV['MONGODB_URI'] ?? 'NOT SET') . "\n";
echo "Use Cloudinary: " . ($_ENV['USE_CLOUDINARY'] ?? 'NOT SET') . "\n";
echo "Cloudinary Name: " . ($_ENV['CLOUDINARY_CLOUD_NAME'] ?? 'NOT SET') . "\n";
```

Or use the test script:
```bash
php test_cloudinary.php
```

---

## ⚠️ Security Best Practices

### DO:
✅ Use different `JWT_SECRET` for dev/prod
✅ Use long random strings (64+ characters)
✅ Keep `.env` in `.gitignore`
✅ Use environment variables in Render (never hardcode)
✅ Rotate secrets periodically
✅ Use HTTPS URLs in production

### DON'T:
❌ Commit `.env` to Git
❌ Share secrets in screenshots/logs
❌ Use simple/guessable secrets
❌ Reuse secrets across projects
❌ Store secrets in code comments
❌ Use HTTP URLs in production

---

## 🔄 Updating Variables

### Local
1. Edit `.env` file
2. Restart server: `php -S localhost:8000`

### Production (Render)
1. Go to Render Dashboard
2. Click your service
3. **Environment** tab
4. Edit variable → **Save Changes**
5. Service auto-redeploys (wait 2-3 min)

---

## 🆘 Troubleshooting

### "Variable not found" error
- Check variable name spelling (case-sensitive)
- Verify `.env` file exists
- Restart server after changing `.env`
- Check `$_ENV['VAR_NAME']` syntax

### Cloudinary not working
- Verify `USE_CLOUDINARY=true`
- Check all 3 Cloudinary credentials are set
- Run `php test_cloudinary.php`
- Check credentials in Cloudinary dashboard

### CORS errors
- Check `FRONTEND_URL` matches exactly
- No trailing slash
- Use HTTPS in production
- Restart server after changing

### Database connection failed
- Check `MONGODB_URI` format
- Verify IP whitelist (0.0.0.0/0 for Atlas)
- Test connection string locally first
- Check database name matches

---

## 📚 Related Documentation

- **CLOUDINARY_SETUP.md** - Cloudinary setup guide
- **DEPLOYMENT_CHECKLIST.md** - Deployment steps
- **RENDER_DEPLOY_GUIDE.md** - Render.com guide

---

## 🎯 Quick Copy Templates

### Generate JWT Secret (PowerShell)
```powershell
-join ((48..57) + (65..90) + (97..122) | Get-Random -Count 64 | ForEach-Object {[char]$_})
```

### MongoDB Atlas Connection String
```
mongodb+srv://username:password@cluster.xxxxx.mongodb.net/?retryWrites=true&w=majority
```

### Cloudinary Check
```bash
php test_cloudinary.php
```

---

**Need help?** Check variable values in Render logs or test locally first!
