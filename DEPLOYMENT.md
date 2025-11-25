# PerkPal Deployment Guide

## Email Configuration (Resend)

### Issue: Email Timeout
The application was experiencing 30-second timeouts when sending emails via `/api/v1/leads/contact` and other lead endpoints.

### Root Cause
1. SMTP connection timeout due to unverified domain
2. Emails were being sent synchronously, blocking the request
3. No timeout configuration for SMTP connections

### Solution Implemented

#### 1. Queue-based Email Sending
- All emails are now queued using Laravel's queue system
- Prevents blocking HTTP requests
- Emails are sent asynchronously in the background

#### 2. SMTP Timeout Configuration
- Added `MAIL_TIMEOUT=10` to limit connection attempts to 10 seconds
- Default timeout in `config/mail.php` set to 10 seconds

#### 3. Queue Worker Setup
- Added `worker` process to `Procfile` for Railway deployment
- Queue worker processes emails in the background

### Required Setup Steps

#### Step 1: Verify Domain in Resend
1. Go to [Resend Dashboard](https://resend.com/domains)
2. Add your domain: `venturenext.shop`
3. Add the DNS records provided by Resend:
   - TXT record for domain verification
   - CNAME records for DKIM
   - MX records (optional, for receiving emails)
4. Wait for DNS propagation (can take up to 48 hours)
5. Verify the domain in Resend dashboard

#### Step 2: Update Environment Variables
Ensure these variables are set in your Railway environment:

```bash
MAIL_MAILER=smtp
MAIL_HOST=smtp.resend.com
MAIL_PORT=587
MAIL_USERNAME=resend
MAIL_PASSWORD=your_resend_api_key
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=hello@venturenext.shop
MAIL_FROM_NAME="VentureNext"
MAIL_TIMEOUT=10
```

#### Step 3: Setup Queue Worker on Railway

**Option A: Using Procfile (Recommended)**
The `Procfile` now includes a worker process:
```
web: php artisan config:cache && php artisan migrate --force && php artisan storage:link && php -S 0.0.0.0:${PORT:-8080} -t public
worker: php artisan queue:work --tries=3 --timeout=90
```

**To enable the worker on Railway:**
1. Go to your Railway project
2. Click on your service
3. Go to "Settings" → "Deploy"
4. Under "Process Type", create a new service with:
   - **Name**: worker
   - **Command**: `php artisan queue:work --tries=3 --timeout=90`
5. Deploy the changes

**Option B: Manual Setup (Alternative)**
If Procfile doesn't work, create a separate worker service:
1. Create a new service in Railway
2. Connect to the same GitHub repository
3. Set the start command to: `php artisan queue:work --tries=3 --timeout=90`
4. Use the same environment variables as the web service

#### Step 4: Verify Queue System
1. Ensure `jobs` table exists in database (already migrated)
2. Check queue connection in `.env`:
   ```bash
   QUEUE_CONNECTION=database
   ```
3. Monitor queue jobs:
   ```bash
   php artisan queue:work --verbose
   ```

### Testing

#### Test Email Sending Locally
```bash
# Start queue worker
php artisan queue:work

# Send test email via API
curl -X POST http://localhost:8000/api/v1/leads/contact \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "message": "Test message"
  }'
```

The API should return immediately without waiting for email to be sent.
Check the queue worker logs to see email processing.

#### Test on Railway
1. Deploy the changes
2. Send a POST request to: `https://your-app.railway.app/api/v1/leads/contact`
3. Response should be instant (not 30 second timeout)
4. Check Railway logs for queue worker processing

### Monitoring

#### Check Failed Jobs
```bash
php artisan queue:failed
```

#### Retry Failed Jobs
```bash
php artisan queue:retry all
```

#### Clear Failed Jobs
```bash
php artisan queue:flush
```

### Troubleshooting

#### Issue: Emails not being sent
**Check:**
1. Is queue worker running? Check Railway logs
2. Is database connection working?
3. Are there failed jobs? Run `php artisan queue:failed`

#### Issue: Still getting timeout
**Solutions:**
1. Verify domain in Resend is fully verified (green checkmark)
2. Check SMTP credentials are correct
3. Ensure `MAIL_TIMEOUT=10` is set
4. Clear config cache: `php artisan config:clear`

#### Issue: Queue worker not starting on Railway
**Solutions:**
1. Check Railway logs for errors
2. Verify Procfile syntax is correct
3. Try manual worker service setup (Option B above)
4. Ensure `jobs` table exists in database

### Email Domain Verification Status

Current status:
- ❌ Domain `venturenext.io` - Not verified (old domain)
- ⚠️ Domain `venturenext.shop` - Needs verification

**Action Required:**
Verify `venturenext.shop` in Resend dashboard and update DNS records.

### Changes Made to Codebase

#### Files Modified:
1. `app/Http/Controllers/Api/LeadController.php`
   - Changed `Mail::send()` to `Mail::queue()` for PerkClaimConfirmation
   - Changed `Mail::html()` to `Mail::html()->onQueue('emails')` for notification emails

2. `config/mail.php`
   - Added timeout configuration: `'timeout' => env('MAIL_TIMEOUT', 10)`

3. `Procfile`
   - Added worker process for queue handling

### Additional Notes

- All email sending is now asynchronous
- Failed emails will retry up to 3 times
- Timeout is set to 10 seconds for SMTP connections
- Queue worker timeout is 90 seconds per job
- Emails are logged on failure for debugging

### Contact Email Configuration

The notification emails are sent to the email configured in:
- Admin Settings → Lead Notification Email
- Fallback: Contact Email from settings

Make sure these are configured in the admin panel.
