# Railway Environment Variables - Complete List

## 🔥 **CRITICAL - Must Add to Railway**

These environment variables **must be added** to Railway for the application to work properly.

---

## 📝 **Complete Environment Variables List**

Copy and paste these to Railway → Project → Variables:

```bash
# App Configuration
APP_NAME=VentureNext
APP_ENV=production
APP_DEBUG=false
APP_URL=https://web-production-d034.up.railway.app

# Frontend URL (IMPORTANT for password reset links!)
FRONTEND_URL=https://venturenext.shop

# Database (Railway provides these automatically)
# DB_CONNECTION=pgsql
# DB_HOST=postgres.railway.internal
# DB_PORT=5432
# DB_DATABASE=railway
# DB_USERNAME=postgres
# DB_PASSWORD=xxx

# Queue Configuration (CRITICAL!)
QUEUE_CONNECTION=database

# Cache & Session
CACHE_STORE=database
SESSION_DRIVER=database
SESSION_LIFETIME=120

# Email - Resend HTTP API (CRITICAL!)
RESEND_API_KEY=re_N7ZLiQgN_8HfDRTNESW7HbvmbsJ8z3VMZ
MAIL_MAILER=smtp
MAIL_HOST=smtp.resend.com
MAIL_PORT=587
MAIL_USERNAME=resend
MAIL_PASSWORD=re_N7ZLiQgN_8HfDRTNESW7HbvmbsJ8z3VMZ
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=hello@venturenext.shop
MAIL_FROM_NAME=VentureNext
MAIL_TIMEOUT=5

# CORS
SANCTUM_STATEFUL_DOMAINS=venturenext.shop

# Logging
LOG_CHANNEL=stack
LOG_LEVEL=error
```

---

## 🎯 **Environment Variables Explanation**

### **App Configuration**

| Variable | Value | Purpose |
|----------|-------|---------|
| `APP_NAME` | `VentureNext` | Application name |
| `APP_ENV` | `production` | Environment (production/local) |
| `APP_DEBUG` | `false` | Disable debug in production |
| `APP_URL` | Railway URL | Backend URL |

### **Frontend Configuration** ⚠️ **IMPORTANT**

| Variable | Value | Purpose |
|----------|-------|---------|
| `FRONTEND_URL` | `https://venturenext.shop` | **Password reset links, email links** |

**Why Important:**
- Password reset emails use this for button link
- Without this, link will be `http://localhost:5173/admin/password-reset/...` ❌
- With this, link will be `https://venturenext.shop/admin/password-reset/...` ✅

### **Queue Configuration** ⚠️ **CRITICAL**

| Variable | Value | Purpose |
|----------|-------|---------|
| `QUEUE_CONNECTION` | `database` | **Enables queue system for emails** |

**Without this:**
- Emails sent synchronously ❌
- API timeout ❌
- Worker doesn't process jobs ❌

**With this:**
- Emails queued instantly ✅
- API fast response ✅
- Worker processes in background ✅

### **Email Configuration** ⚠️ **CRITICAL**

| Variable | Value | Purpose |
|----------|-------|---------|
| `RESEND_API_KEY` | `re_N7ZLiQgN...` | **Resend HTTP API for fast email** |
| `MAIL_TIMEOUT` | `5` | SMTP timeout (fallback) |
| `MAIL_FROM_ADDRESS` | `hello@venturenext.shop` | From email |

**Why Resend API:**
- HTTP API uses port 443 (never blocked) ✅
- Faster than SMTP (1s vs 5s) ✅
- More reliable in cloud ✅

### **Cache & Session**

| Variable | Value | Purpose |
|----------|-------|---------|
| `CACHE_STORE` | `database` | Cache storage |
| `SESSION_DRIVER` | `database` | Session storage |

**Benefits:**
- Faster responses ✅
- Persistent sessions ✅
- Works with multiple workers ✅

---

## 🚀 **How to Add to Railway**

### **Method 1: Railway Dashboard (Recommended)**

1. Go to [Railway Dashboard](https://railway.app)
2. Select your project
3. Click on your service (backend)
4. Go to **Variables** tab
5. Click **+ New Variable**
6. Add each variable one by one:
   - Variable: `FRONTEND_URL`
   - Value: `https://venturenext.shop`
   - Click **Add**
7. Repeat for all variables above
8. Railway will **auto-redeploy**

### **Method 2: Railway CLI**

```bash
# Set single variable
railway variables set FRONTEND_URL=https://venturenext.shop

# Set multiple variables
railway variables set QUEUE_CONNECTION=database CACHE_STORE=database
```

### **Method 3: Bulk Import**

1. Create file `railway-vars.txt`:
```
FRONTEND_URL=https://venturenext.shop
QUEUE_CONNECTION=database
CACHE_STORE=database
SESSION_DRIVER=database
RESEND_API_KEY=re_N7ZLiQgN_8HfDRTNESW7HbvmbsJ8z3VMZ
MAIL_TIMEOUT=5
```

2. Upload in Railway Dashboard → Variables → **Import from .env**

---

## ✅ **Priority List**

### **High Priority (Must Add):**
1. ✅ `FRONTEND_URL` - Fix password reset links
2. ✅ `QUEUE_CONNECTION=database` - Enable queue system
3. ✅ `RESEND_API_KEY` - Fast email delivery

### **Medium Priority (Recommended):**
4. ✅ `CACHE_STORE=database` - Performance
5. ✅ `SESSION_DRIVER=database` - Sessions
6. ✅ `MAIL_TIMEOUT=5` - SMTP fallback

### **Low Priority (Optional):**
7. `LOG_LEVEL=error` - Reduce logs
8. `APP_DEBUG=false` - Hide errors

---

## 🔍 **Verify Environment Variables**

### **Check in Railway Logs:**

After adding variables and redeploy, check logs:

```bash
# Password reset URL should be correct
FRONTEND_URL: https://venturenext.shop ✅

# Queue should be database
QUEUE_CONNECTION: database ✅

# Email API should be set
RESEND_API_KEY: re_N7... ✅
```

### **Test Password Reset Link:**

1. Request password reset
2. Check email
3. Click "Reset Password" button
4. Should redirect to: `https://venturenext.shop/admin/password-reset/...` ✅
5. **NOT** `http://localhost:5173/admin/password-reset/...` ❌

---

## 📊 **Impact of Each Variable**

| Variable | Without It | With It |
|----------|-----------|---------|
| `FRONTEND_URL` | Reset link = localhost ❌ | Reset link = production ✅ |
| `QUEUE_CONNECTION` | Email timeout 30s ❌ | Email instant ✅ |
| `RESEND_API_KEY` | SMTP slow/blocked ❌ | HTTP API fast ✅ |
| `CACHE_STORE` | Slow responses ❌ | Fast cached ✅ |
| `MAIL_TIMEOUT` | 30s timeout ❌ | 5s timeout ✅ |

---

## 🆘 **Troubleshooting**

### Issue: Password reset link still localhost
**Solution:**
1. Check `FRONTEND_URL` is set in Railway
2. Redeploy: Railway → Service → Deploy
3. Clear config cache: `php artisan config:clear`
4. Test again

### Issue: Emails not sent
**Solution:**
1. Check `QUEUE_CONNECTION=database` is set
2. Check worker is running in Railway logs
3. Check `RESEND_API_KEY` is set
4. Check failed_jobs table

### Issue: Config changes not reflected
**Solution:**
1. Railway auto-redeploys on variable change
2. If not, manual redeploy
3. Clear cache: `php artisan config:cache`

---

## 📝 **Environment Variable Template**

Save this as reference for future deployments:

```bash
# === APP ===
APP_NAME=VentureNext
APP_ENV=production
APP_DEBUG=false
APP_URL=https://web-production-d034.up.railway.app

# === FRONTEND ===
FRONTEND_URL=https://venturenext.shop

# === QUEUE ===
QUEUE_CONNECTION=database

# === CACHE & SESSION ===
CACHE_STORE=database
SESSION_DRIVER=database

# === EMAIL ===
RESEND_API_KEY=re_N7ZLiQgN_8HfDRTNESW7HbvmbsJ8z3VMZ
MAIL_TIMEOUT=5
MAIL_FROM_ADDRESS=hello@venturenext.shop

# === CORS ===
SANCTUM_STATEFUL_DOMAINS=venturenext.shop
```

---

## ✨ **After Adding Variables**

**Expected Behavior:**

1. **Password Reset Email:**
   - Button link: `https://venturenext.shop/admin/password-reset/TOKEN` ✅
   - Click works ✅
   - Redirects to production frontend ✅

2. **Email Sending:**
   - API response instant (< 0.1s) ✅
   - Email sent in background ✅
   - Delivered via Resend API ✅

3. **Application:**
   - Fast responses (cached) ✅
   - No timeouts ✅
   - Worker processing jobs ✅

---

**Last Updated:** 2025-11-26
**Status:** Complete Environment Variable Guide ✅
