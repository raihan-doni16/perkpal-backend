# Railway Deployment Guide for PerkPal Backend

## The Problem

Railway provides database credentials through a `DATABASE_URL` environment variable in the format:
```
postgresql://user:password@host:port/database
```

However, if you try to use individual variables like `PGHOST`, `PGPORT`, etc., Railway may set them as literal strings like `"${PGPORT}"` which causes connection errors.

## Solution

Configure Laravel to use the `DATABASE_URL` directly instead of individual connection parameters.

## Step 1: Update Database Configuration

The [config/database.php](config/database.php) file should prioritize `DB_URL` (which will be set from `DATABASE_URL`) over individual connection parameters. This is already configured in the default Laravel setup at line 87.

## Step 2: Set Environment Variables in Railway

In your Railway project, configure these environment variables:

### Required Variables
```bash
APP_NAME=PerkPal
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE  # Generate with: php artisan key:generate --show
APP_DEBUG=false
APP_URL=https://your-app.up.railway.app

DB_CONNECTION=pgsql
# Railway will automatically provide DATABASE_URL from the linked Postgres service
# Do NOT manually set DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD
```

### Optional but Recommended
```bash
LOG_CHANNEL=stack
LOG_LEVEL=error

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

## Step 3: Link PostgreSQL Database

1. In your Railway project, click "New" → "Database" → "Add PostgreSQL"
2. Railway will automatically create a `DATABASE_URL` variable that links to this database
3. Your Laravel app will automatically use this connection

## Step 4: Deploy Configuration Files

Make sure these files are in your repository:

### nixpacks.toml
Configures the build process for Railway.

### Procfile (Optional)
Alternative to nixpacks.toml start command.

### .railwayignore (Optional)
```
node_modules/
.git/
.env
storage/logs/*
!storage/logs/.gitkeep
tests/
```

## Step 5: Deploy

1. Push your code to GitHub/GitLab
2. In Railway, create a new project from your repository
3. Add PostgreSQL database
4. Railway will automatically:
   - Detect it's a PHP/Laravel app
   - Install dependencies
   - Run migrations (via the start command)
   - Start the server

## Troubleshooting

### Error: "invalid integer value ${PGPORT}"
This means Laravel is trying to use individual connection parameters instead of DATABASE_URL.

**Fix**: Make sure you're NOT setting `DB_HOST`, `DB_PORT`, etc. in Railway's environment variables. Only set `DB_CONNECTION=pgsql` and let Railway's `DATABASE_URL` handle the rest.

### Error: "No application encryption key has been specified"
**Fix**: Generate a key locally with `php artisan key:generate --show` and set it as `APP_KEY` in Railway.

### Migrations don't run
**Fix**: Check the deploy logs. Migrations run automatically via the start command in [nixpacks.toml:19](nixpacks.toml#L19).

### 500 Server Error
**Fix**:
1. Set `APP_DEBUG=true` temporarily to see the error
2. Check Railway logs for detailed error messages
3. Make sure all required extensions are installed (php-pgsql, php-mbstring, etc.)

## Important Notes

1. **Never commit .env file** - It contains secrets
2. **Database URL Priority** - Laravel checks `DB_URL` before individual parameters (see [config/database.php:87](config/database.php#L87))
3. **Caching** - The build process caches config/routes/views for better performance
4. **Storage Link** - The start command automatically creates the storage symlink

## Verification

After deployment:

1. Check Railway logs for successful migration
2. Visit `https://your-app.up.railway.app/api/health` (if you have a health check endpoint)
3. Test API endpoints

## Laravel Configuration Cache Note

Since we cache configurations during build ([nixpacks.toml:13-15](nixpacks.toml#L13-L15)), environment variables are baked into the cache. If you change environment variables in Railway, you need to redeploy to update the cached config.

Alternatively, you can remove config caching from the build phase if you need more flexibility, but this will impact performance slightly.
