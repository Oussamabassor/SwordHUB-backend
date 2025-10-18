# 🔧 CORS Issue - Frontend Can't Fetch Data

## ❌ Problem

Backend returns: `{"success":true,...}` ✅  
But API calls return: **403 Forbidden** ❌

---

## 🎯 Root Cause

The `FRONTEND_URL` environment variable in Render doesn't match your actual Vercel URL.

---

## ✅ Quick Fix

### Step 1: Verify Your Frontend URL

Your frontend is deployed at:
```
https://sword-hub-frontend.vercel.app
```

### Step 2: Update FRONTEND_URL in Render

1. Go to: https://dashboard.render.com
2. Click: **swordhub-backend**
3. Click: **Environment** (left sidebar)
4. Find: `FRONTEND_URL`
5. Update to **EXACTLY**:
   ```
   https://sword-hub-frontend.vercel.app
   ```
   **⚠️ NO trailing slash!**

6. Click **Save Changes**

### Step 3: Wait for Auto-Redeploy

Render will automatically redeploy (2-3 minutes)

### Step 4: Test Again

```bash
curl https://swordhub-backend.onrender.com/api/products
```

Should return product list!

---

## 🔍 Alternative: Allow All Origins (For Testing)

If you want to test quickly, temporarily set in Render:

```
FRONTEND_URL=*
```

**⚠️ Warning**: This allows ALL domains. Only use for testing!

---

## 📋 Checklist

- [ ] `FRONTEND_URL` in Render = `https://sword-hub-frontend.vercel.app`
- [ ] No trailing slash in URL
- [ ] Saved changes in Render
- [ ] Waited for redeploy (2-3 min)
- [ ] Tested `/api/products` endpoint
- [ ] Frontend can fetch data

---

## 🧪 Test CORS is Working

### From Browser Console:

Visit: https://sword-hub-frontend.vercel.app

Open Developer Tools (F12) → Console:

```javascript
fetch('https://swordhub-backend.onrender.com/api/products')
  .then(r => r.json())
  .then(data => console.log(data))
  .catch(err => console.error(err))
```

**Expected**: Array of products  
**If error**: CORS still blocked

---

## 🔧 If Still Not Working

### Check Render Logs:

1. Render Dashboard → swordhub-backend → **Logs**
2. Look for CORS errors when you try to fetch

### Verify Environment Variables:

Make sure ALL these are set in Render:

```
FRONTEND_URL=https://sword-hub-frontend.vercel.app
MONGODB_URI=mongodb+srv://SWORDHUB:sword1234@cluster0.ryhv1ve.mongodb.net/?retryWrites=true&w=majority
MONGODB_DATABASE=swordhub
JWT_SECRET=ED*8]EF=+h?)so{]Evvh6ACORU'R)Q-9j|;ydtJN1L|'<LFR'E;]tR[PzgEw(/_
```

### Test Direct API Call:

```bash
curl -H "Origin: https://sword-hub-frontend.vercel.app" https://swordhub-backend.onrender.com/api/products
```

---

## 🎉 After Fix

Once CORS is working:

1. ✅ Backend responds to API calls
2. ✅ Frontend can fetch products
3. ✅ Frontend can fetch categories
4. ✅ Users can browse products
5. ✅ Users can add to cart
6. ✅ Users can place orders
7. ✅ Admin dashboard works

---

**Next Action**: Update `FRONTEND_URL` in Render to `https://sword-hub-frontend.vercel.app`
