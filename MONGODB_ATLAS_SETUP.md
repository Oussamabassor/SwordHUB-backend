# MongoDB Atlas Connection Guide for XAMPP

## Current Configuration

Your backend is now configured to connect to MongoDB Atlas using:
- **Connection String**: `mongodb+srv://SWORDHUB:sword1234@cluster0.ryhv1ve.mongodb.net/`
- **Database Name**: `swordhub`

## SSL/TLS Error Fix for XAMPP

The current error is related to XAMPP's OpenSSL/TLS configuration. Here are the solutions:

### Solution 1: Update php.ini (Recommended)

1. **Locate your php.ini file**:
   - Path: `C:\xampp\php\php.ini`

2. **Add/Update OpenSSL settings**:
   ```ini
   ; Enable OpenSSL extension
   extension=openssl
   
   ; Add these lines for better MongoDB Atlas compatibility
   openssl.cafile="C:\xampp\apache\bin\curl-ca-bundle.crt"
   ```

3. **Verify MongoDB extension is enabled**:
   ```ini
   extension=mongodb
   ```

4. **Restart Apache**:
   - Stop and start Apache from XAMPP Control Panel

### Solution 2: Update XAMPP's OpenSSL

1. Download the latest OpenSSL DLLs for Windows
2. Replace files in `C:\xampp\apache\bin\`:
   - `libeay32.dll`
   - `ssleay32.dll`
   - `libssl-1_1-x64.dll`
   - `libcrypto-1_1-x64.dll`

3. Restart Apache

### Solution 3: Use Standard MongoDB URI (Alternative)

If SSL issues persist, you can use the standard MongoDB connection string instead of `mongodb+srv://`:

1. In MongoDB Atlas:
   - Go to your cluster
   - Click "Connect"
   - Choose "Connect your application"
   - Select "Standard connection string" instead of SRV

2. Update `.env` file with the standard connection string:
   ```
   MONGODB_URI=mongodb://ac-w2v2kzl-shard-00-00.ryhv1ve.mongodb.net:27017,ac-w2v2kzl-shard-00-01.ryhv1ve.mongodb.net:27017,ac-w2v2kzl-shard-00-02.ryhv1ve.mongodb.net:27017/?ssl=true&replicaSet=atlas-xxxxx-shard-0&authSource=admin&retryWrites=true&w=majority
   ```

### Solution 4: Whitelist Your IP in MongoDB Atlas

1. Login to MongoDB Atlas
2. Go to "Network Access"
3. Click "Add IP Address"
4. Add your current IP or use `0.0.0.0/0` for testing (allows all IPs)
5. Save changes

### Solution 5: Update MongoDB PHP Driver

Check if you need to update the MongoDB PHP extension:

```powershell
# Check current version
php -r "echo phpversion('mongodb');"

# Should be version 1.11.1 or higher
```

To update:
1. Download the appropriate DLL from https://pecl.php.net/package/mongodb
2. Extract `php_mongodb.dll`
3. Place it in `C:\xampp\php\ext\`
4. Restart Apache

## Testing the Connection

Run the test script:
```powershell
cd C:\xampp\htdocs\SwordHUB\SwordHub-backend
php test-connection.php
```

## Verifying Configuration Files

### 1. Check .env file:
```bash
# Location: SwordHub-backend/.env
MONGODB_URI=mongodb+srv://SWORDHUB:sword1234@cluster0.ryhv1ve.mongodb.net/?retryWrites=true&w=majority&tls=true&tlsAllowInvalidCertificates=true
MONGODB_DATABASE=swordhub
```

### 2. Check MongoDB.php:
- Location: `SwordHub-backend/config/MongoDB.php`
- Should include TLS configuration for Atlas connections

### 3. Check composer dependencies:
```powershell
cd SwordHub-backend
composer show | Select-String mongo
```

Should show: `mongodb/mongodb`

## Common Issues and Solutions

### Issue 1: "No suitable servers found"
- **Cause**: Network/SSL issue or IP not whitelisted
- **Fix**: Check MongoDB Atlas Network Access, verify IP whitelist

### Issue 2: "Authentication failed"
- **Cause**: Wrong credentials
- **Fix**: Verify username/password in connection string

### Issue 3: "TLS handshake failed"
- **Cause**: XAMPP's OpenSSL is outdated
- **Fix**: Update php.ini with proper OpenSSL configuration (Solution 1)

### Issue 4: "Connection timeout"
- **Cause**: Firewall or antivirus blocking connection
- **Fix**: Allow MongoDB Atlas IPs through firewall (port 27017)

## Production Recommendations

When deploying to production:

1. **Remove `tlsAllowInvalidCertificates`** from connection string
2. **Use environment variables** for sensitive data
3. **Restrict IP whitelist** in MongoDB Atlas
4. **Use a strong JWT secret** in .env
5. **Enable proper error logging**

## Additional Resources

- MongoDB Atlas Documentation: https://docs.atlas.mongodb.com/
- MongoDB PHP Library: https://docs.mongodb.com/php-library/
- XAMPP SSL Configuration: https://community.apachefriends.org/

## Next Steps

1. Apply one of the solutions above
2. Restart Apache/PHP
3. Run the test script
4. If successful, you can start using the API endpoints

## Support

If you continue to experience issues:
1. Check Apache error logs: `C:\xampp\apache\logs\error.log`
2. Check PHP error logs: `C:\xampp\php\logs\php_error_log`
3. Verify MongoDB Atlas cluster is running
4. Check MongoDB Atlas logs for connection attempts
