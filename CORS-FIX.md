# CORS Fix - Complete Guide

## Problem
Frontend getting CORS error when calling API:
```
A cross-origin resource sharing (CORS) request was blocked because of invalid or missing response headers
Header: Access-Control-Allow-Origin - Header Not Present
```

## Root Cause
1. Middleware order was incorrect (`ForceCorsHeaders` before `HandleCors`)
2. CORS `max_age` was set to 0 (no caching of preflight)
3. OPTIONS preflight requests not handled properly

## Solutions Implemented

### 1. Fixed Middleware Order
**File:** `bootstrap/app.php`

Changed middleware order so `HandleCors` processes first:
```php
$middleware->use([
    HandleCors::class,           // ← First: handles OPTIONS
    ForceCorsHeaders::class,     // ← Second: adds headers on errors
]);
```

### 2. Updated CORS Configuration
**File:** `config/cors.php`

- Changed `max_age` from `0` to `86400` (24 hours)
- Specified paths explicitly: `['api/*', 'sanctum/csrf-cookie']`

```php
'max_age' => 86400,  // Cache preflight for 24 hours
```

### 3. Enhanced ForceCorsHeaders Middleware
**File:** `app/Http/Middleware/ForceCorsHeaders.php`

Added:
- Direct handling of OPTIONS requests
- `Access-Control-Max-Age` header
- `X-CSRF-TOKEN` to allowed headers

```php
// Handle preflight OPTIONS request immediately
if ($request->isMethod('OPTIONS')) {
    $response = response('', 204);
    return $this->addCorsHeaders($response);
}
```

## Testing CORS

### Test OPTIONS Preflight Request
```bash
curl -i -X OPTIONS https://your-app.railway.app/api/v1/leads/contact \
  -H "Origin: https://venturenext.shop" \
  -H "Access-Control-Request-Method: POST" \
  -H "Access-Control-Request-Headers: Content-Type"
```

**Expected Response:**
```
HTTP/1.1 204 No Content
Access-Control-Allow-Origin: *
Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS
Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Origin, Accept, X-CSRF-TOKEN
Access-Control-Max-Age: 86400
```

### Test Actual POST Request
```bash
curl -i -X POST https://your-app.railway.app/api/v1/leads/contact \
  -H "Origin: https://venturenext.shop" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"name":"Test","email":"test@example.com","message":"Test"}'
```

**Expected Headers in Response:**
```
Access-Control-Allow-Origin: *
```

## Deployment Checklist

### Before Deploy
- [x] Update middleware order in `bootstrap/app.php`
- [x] Update CORS config in `config/cors.php`
- [x] Update `ForceCorsHeaders` middleware
- [x] Clear and cache config

### Deploy to Railway
1. **Commit changes:**
```bash
git add .
git commit -m "Fix CORS - proper middleware order and OPTIONS handling"
git push
```

2. **Railway will auto-deploy**

3. **After deploy, run on Railway:**
```bash
php artisan config:cache
```

### Verify After Deploy

1. **Check from browser console:**
```javascript
// Open venturenext.shop in browser
fetch('https://your-railway-app.up.railway.app/api/v1/leads/contact', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  },
  body: JSON.stringify({
    name: 'Test',
    email: 'test@example.com',
    message: 'CORS test'
  })
})
.then(r => r.json())
.then(console.log)
.catch(console.error)
```

2. **Should NOT see CORS error**
3. **Should get instant response** (< 1 second thanks to queue fix)

## Common CORS Issues & Solutions

### Issue: Still getting CORS error after deploy
**Solution:**
1. Check Railway logs for errors
2. Verify config is cached: `php artisan config:cache`
3. Check if `APP_ENV=production` (affects error responses)

### Issue: CORS works in Postman but not browser
**Reason:** Postman doesn't send OPTIONS preflight
**Solution:** This is normal. Browser sends OPTIONS first, then actual request.

### Issue: Specific header blocked
**Solution:** Add header to `allowed_headers` in `config/cors.php`

### Issue: Credentials mode not working
**Solution:**
1. Change `supports_credentials` to `true` in `config/cors.php`
2. Change `allowed_origins` from `['*']` to specific domain
3. Cannot use `*` with credentials

## Current CORS Configuration

### Allowed Origins
- `*` (all origins) - For public API

### Allowed Methods
- GET, POST, PUT, PATCH, DELETE, OPTIONS

### Allowed Headers
- Content-Type
- Authorization
- X-Requested-With
- Origin
- Accept
- X-CSRF-TOKEN

### Preflight Cache
- 24 hours (86400 seconds)

### Credentials
- Not supported (public API)

## Files Modified

1. `bootstrap/app.php` - Middleware order
2. `config/cors.php` - CORS configuration
3. `app/Http/Middleware/ForceCorsHeaders.php` - OPTIONS handling

## Related Issues

This CORS fix complements the email queue fix:
- Email queue prevents timeout (see DEPLOYMENT.md)
- CORS fix allows frontend to call API
- Together: Fast, working API calls from frontend

## Security Notes

Current config allows all origins (`*`) for public API.

**For production with authentication:**
1. Change `allowed_origins` to specific domains
2. Enable `supports_credentials` if using cookies/sessions
3. Remove `*` and list exact domains

Example:
```php
'allowed_origins' => [
    'https://venturenext.shop',
    'https://www.venturenext.shop',
],
'supports_credentials' => true,
```

## Troubleshooting Commands

### Clear all caches
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

### Re-cache config
```bash
php artisan config:cache
```

### Check current config
```bash
php artisan tinker
config('cors')
```

### Test from command line
```bash
# OPTIONS request
curl -X OPTIONS https://your-app/api/v1/leads/contact -i

# POST request
curl -X POST https://your-app/api/v1/leads/contact \
  -H "Content-Type: application/json" \
  -H "Origin: https://venturenext.shop" \
  -d '{"name":"Test","email":"test@example.com","message":"Test"}' \
  -i
```

## Summary

✅ **Problem:** CORS blocking API requests from frontend
✅ **Solution:** Fixed middleware order + proper OPTIONS handling + max_age
✅ **Result:** Frontend can call API without CORS errors
✅ **Bonus:** Combined with queue fix = Fast API responses

**Status:** Ready to deploy! 🚀
