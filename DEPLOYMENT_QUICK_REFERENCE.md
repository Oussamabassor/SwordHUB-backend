# 🚀 Quick Deployment Commands

## Push to GitHub
```bash
cd SwordHub-backend
git add .
git commit -m "Prepare for Docker deployment on Render"
git push origin main
```

## Environment Variables to Add in Render

Copy these from your `.env` file and paste in Render dashboard:

```bash
# Database
MONGODB_URI=mongodb+srv://SWORDHUB:sword1234@cluster0.ryhv1ve.mongodb.net/
MONGODB_DATABASE=swordhub

# Security
JWT_SECRET=ED*8]EF=+h?)so{]Evvh6ACORU'R)Q-9j|;ydtJN1L|'<LFR'E;]tR[PzgEw(/_

# Frontend URL (UPDATE THIS!)
FRONTEND_URL=https://your-frontend.vercel.app

# Cloudinary
CLOUDINARY_CLOUD_NAME=dupmbtrcn
CLOUDINARY_API_KEY=699826948667517
CLOUDINARY_API_SECRET=gMfTd5ZgE5yWRW2M6n1Gb6vYeXk
```

## Test After Deployment

```bash
# Replace with your actual Render URL
export API_URL="https://your-service.onrender.com"

# Test health
curl $API_URL/

# Test products
curl $API_URL/api/products

# Test stats
curl $API_URL/api/dashboard/stats

# Test with auth (replace with real token)
curl -H "Authorization: Bearer YOUR_JWT_TOKEN" $API_URL/api/orders
```

## Update Frontend

Update `SwordHub-frontend/.env`:
```bash
VITE_API_URL=https://your-service.onrender.com
```

Then redeploy frontend:
```bash
cd SwordHub-frontend
git add .
git commit -m "Update API URL for production"
git push origin main
# Vercel will auto-deploy
```

## Troubleshooting

### If deployment fails:
1. Check Render logs
2. Verify all env vars are set
3. Check MongoDB Atlas IP whitelist (0.0.0.0/0)
4. Verify Cloudinary credentials

### If you get CORS errors:
- Ensure FRONTEND_URL matches exactly (no trailing slash)
- Check browser console for exact error
- Verify .htaccess CORS headers

### If images don't upload:
- Check USE_CLOUDINARY=true
- Verify Cloudinary credentials
- Test Cloudinary separately

## Monitor

- **Logs**: Render Dashboard → Your Service → Logs
- **Metrics**: Render Dashboard → Your Service → Metrics
- **Events**: Render Dashboard → Your Service → Events

## Keep Service Awake (Optional)

Free tier sleeps after 15 min. To prevent:
1. Go to [UptimeRobot](https://uptimerobot.com)
2. Add new monitor
3. URL: https://your-service.onrender.com/
4. Interval: 5 minutes
5. This will ping your API to keep it awake

---

**Your backend will be live at:**
`https://swordhub-backend.onrender.com` (or your chosen name)

🎉 Deployment complete!
