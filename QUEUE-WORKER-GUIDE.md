# Queue Worker Guide - How to Run Locally

## ⚠️ **PENTING: Queue Worker HARUS Running!**

Tanpa queue worker yang berjalan, email akan masuk ke queue tapi **tidak akan pernah dikirim**.

---

## 🚀 **Cara Menjalankan Locally (Development)**

### **Option 1: Two Terminals (Recommended)**

**Terminal 1 - Web Server:**
```bash
cd backend
php artisan serve
```

**Terminal 2 - Queue Worker:**
```bash
cd backend
php artisan queue:work database --queue=default,emails --tries=3 --timeout=90 --verbose
```

Biarkan **kedua terminal tetap running**!

---

### **Option 2: Background Process (Advanced)**

**Windows (Git Bash/PowerShell):**
```bash
cd backend

# Start worker in background
start /B php artisan queue:work database --queue=default,emails --tries=3 --timeout=90

# Start server
php artisan serve
```

**Mac/Linux:**
```bash
cd backend

# Start worker in background
php artisan queue:work database --queue=default,emails --tries=3 --timeout=90 &

# Start server
php artisan serve
```

---

### **Option 3: Use Sync Queue (No Worker Needed)**

Untuk testing cepat tanpa worker:

**Update `.env`:**
```bash
QUEUE_CONNECTION=sync
```

**Restart server:**
```bash
php artisan serve
```

⚠️ **Note:** Dengan sync, email akan dikirim langsung (blocking), jadi API akan lambat lagi.

---

## 🔍 **Monitoring Queue**

### Check Jobs in Queue:
```bash
php artisan queue:work --once --verbose
```

### Check Queue Status:
```bash
php artisan tinker
DB::table('jobs')->count();
DB::table('failed_jobs')->count();
exit
```

### Clear Queue:
```bash
php artisan queue:clear
```

### Failed Jobs:
```bash
php artisan queue:failed        # List failed jobs
php artisan queue:retry all     # Retry all failed
php artisan queue:flush         # Delete all failed
```

---

## 📊 **Verify Worker is Processing**

### Test Flow:

**1. Start worker in Terminal 1:**
```bash
cd backend
php artisan queue:work database --queue=default,emails --verbose
```

**2. In Terminal 2, dispatch a test job:**
```bash
cd backend
php artisan tinker
```

```php
App\Jobs\SendLeadNotificationEmail::dispatch(
    'test@example.com',
    'Test Email',
    '<h1>Test</h1><p>Testing queue worker</p>'
);
```

**3. Watch Terminal 1** - You should see:
```
Processing: App\Jobs\SendLeadNotificationEmail
Email sent successfully via Resend API
Processed: App\Jobs\SendLeadNotificationEmail
```

---

## 🎯 **Worker Command Explained**

```bash
php artisan queue:work database --queue=default,emails --tries=3 --timeout=90 --verbose
```

- `database` - Connection (from `QUEUE_CONNECTION=database`)
- `--queue=default,emails` - Process both queues
- `--tries=3` - Retry failed jobs 3 times
- `--timeout=90` - Job timeout 90 seconds
- `--verbose` - Show detailed output

---

## 🚀 **Production (Railway)**

Di Railway, worker akan **auto-start** dari `Procfile`:

```
worker: php artisan queue:work database --queue=default,emails --tries=3 --timeout=90 --sleep=3 --max-jobs=1000 --max-time=3600
```

**Verify di Railway Logs:**
```
worker | Processing: App\Jobs\SendLeadNotificationEmail
worker | Email sent successfully via Resend API
worker | Processed: App\Jobs\SendLeadNotificationEmail
```

---

## ❌ **Common Issues**

### Issue: "Jobs not processing"
**Solution:**
- ✅ Check worker is running: `ps aux | grep queue:work`
- ✅ Check queue connection: `QUEUE_CONNECTION=database`
- ✅ Restart worker if code changed

### Issue: "Email not sent"
**Solution:**
- ✅ Check RESEND_API_KEY is set
- ✅ Check worker logs for errors
- ✅ Check failed_jobs table: `php artisan queue:failed`

### Issue: "Worker stops after processing one job"
**Solution:**
- Don't use `--once` flag
- Use `queue:work` not `queue:work --once`

### Issue: "Code changes not reflected"
**Solution:**
- Restart worker (Ctrl+C, then run again)
- Workers don't auto-reload on code changes

---

## 📝 **Quick Reference**

| Command | Purpose |
|---------|---------|
| `queue:work` | Start worker (keep running) |
| `queue:work --once` | Process one job then stop |
| `queue:listen` | Auto-reload on code changes (slower) |
| `queue:restart` | Gracefully restart all workers |
| `queue:clear` | Clear all queued jobs |
| `queue:failed` | List failed jobs |
| `queue:retry all` | Retry all failed jobs |
| `queue:flush` | Delete all failed jobs |

---

## ✅ **Checklist: Is Queue Working?**

- [ ] Worker running in separate terminal
- [ ] `QUEUE_CONNECTION=database` in `.env`
- [ ] `RESEND_API_KEY` set in `.env`
- [ ] Jobs appear in `jobs` table when dispatched
- [ ] Worker processes jobs (check logs)
- [ ] Emails actually sent (check inbox)

---

## 🎉 **Expected Behavior**

### When Everything Works:

1. **User submits form** → API returns instantly (< 0.1s) ✅
2. **Job queued** → Appears in `jobs` table ✅
3. **Worker picks up job** → Processes in background ✅
4. **Email sent** → Via Resend HTTP API (~1s) ✅
5. **Job removed** → From `jobs` table ✅

### What You Should See:

**API Response:**
```json
{
  "success": true,
  "message": "Contact form submitted successfully..."
}
```
↑ **Instant response!**

**Worker Logs:**
```
Processing: App\Jobs\SendLeadNotificationEmail
Email sent successfully via Resend API
Processed: App\Jobs\SendLeadNotificationEmail
```
↑ **Background processing!**

---

**Status:** Complete Guide for Queue Worker Setup ✅
