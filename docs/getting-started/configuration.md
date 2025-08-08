# Configuration Guide

This guide covers the essential configuration steps to get RestoPos running optimally for your restaurant.

## Environment Configuration

The `.env` file contains all the environment-specific settings for your RestoPos installation.

### Basic Application Settings

```env
# Application
APP_NAME="RestoPos"
APP_ENV=production
APP_KEY=base64:your-generated-key
APP_DEBUG=false
APP_URL=https://your-domain.com

# Timezone
APP_TIMEZONE=UTC

# Locale
APP_LOCALE=en
APP_FALLBACK_LOCALE=en
```

### Database Configuration

::: tabs

== MySQL

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=restopos
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

== PostgreSQL

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=restopos
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

== SQLite

```env
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database.sqlite
```

:::

### Cache Configuration

```env
# Cache Driver (file, redis, memcached)
CACHE_DRIVER=redis

# Redis Configuration
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### Queue Configuration

```env
# Queue Driver (sync, database, redis, sqs)
QUEUE_CONNECTION=redis

# For database queue
QUEUE_TABLE=jobs
```

### Mail Configuration

::: tabs

== SMTP

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@restopos.com
MAIL_FROM_NAME="RestoPos"
```

== Mailgun

```env
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=your-domain.mailgun.org
MAILGUN_SECRET=your-mailgun-secret
MAIL_FROM_ADDRESS=noreply@restopos.com
MAIL_FROM_NAME="RestoPos"
```

== SendGrid

```env
MAIL_MAILER=sendgrid
SENDGRID_API_KEY=your-sendgrid-api-key
MAIL_FROM_ADDRESS=noreply@restopos.com
MAIL_FROM_NAME="RestoPos"
```

:::

### File Storage Configuration

```env
# Filesystem Driver (local, s3, gcs)
FILESYSTEM_DISK=local

# AWS S3 Configuration
AWS_ACCESS_KEY_ID=your-access-key
AWS_SECRET_ACCESS_KEY=your-secret-key
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your-bucket-name
AWS_USE_PATH_STYLE_ENDPOINT=false
```

## Application Configuration

### Multi-Language Setup

RestoPos supports multiple languages out of the box. Configure available languages:

```php
// config/app.php
'locales' => [
    'en' => 'English',
    'fr' => 'Français', 
    'ar' => 'العربية',
    'es' => 'Español',
    'de' => 'Deutsch',
],

'rtl_locales' => ['ar'], // Right-to-left languages
```

### Currency Configuration

```php
// config/restopos.php
'currency' => [
    'default' => 'USD',
    'symbol' => '$',
    'position' => 'before', // before or after
    'decimal_places' => 2,
    'thousands_separator' => ',',
    'decimal_separator' => '.',
],
```

### Tax Configuration

```php
// config/restopos.php
'tax' => [
    'enabled' => true,
    'default_rate' => 10.00, // Percentage
    'inclusive' => false, // Tax inclusive pricing
    'label' => 'VAT',
],
```

### Order Configuration

```php
// config/restopos.php
'orders' => [
    'reference_prefix' => 'ORD',
    'reference_length' => 8,
    'auto_accept' => false,
    'preparation_time' => 30, // minutes
    'allow_modifications' => true,
],
```

## Payment Gateway Configuration

### Stripe Configuration

```env
# Stripe
STRIPE_KEY=pk_test_your_publishable_key
STRIPE_SECRET=sk_test_your_secret_key
STRIPE_WEBHOOK_SECRET=whsec_your_webhook_secret
```

### PayPal Configuration

```env
# PayPal
PAYPAL_MODE=sandbox # or live
PAYPAL_CLIENT_ID=your_client_id
PAYPAL_CLIENT_SECRET=your_client_secret
```

### Square Configuration

```env
# Square
SQUARE_APPLICATION_ID=your_application_id
SQUARE_ACCESS_TOKEN=your_access_token
SQUARE_ENVIRONMENT=sandbox # or production
```

## Security Configuration

### HTTPS and SSL

```env
# Force HTTPS
APP_FORCE_HTTPS=true

# Session Security
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=strict
```

### CORS Configuration

```php
// config/cors.php
'allowed_origins' => [
    'https://your-frontend-domain.com',
    'https://your-mobile-app.com',
],

'allowed_methods' => ['*'],
'allowed_headers' => ['*'],
'exposed_headers' => [],
'max_age' => 0,
'supports_credentials' => true,
```

### Rate Limiting

```php
// config/restopos.php
'rate_limiting' => [
    'api' => [
        'requests' => 60,
        'per_minute' => 1,
    ],
    'auth' => [
        'attempts' => 5,
        'lockout_duration' => 900, // 15 minutes
    ],
],
```

## Performance Configuration

### Caching

```bash
# Enable configuration caching
php artisan config:cache

# Enable route caching
php artisan route:cache

# Enable view caching
php artisan view:cache

# Enable event caching
php artisan event:cache
```

### Queue Workers

Set up queue workers for background processing:

```bash
# Start queue worker
php artisan queue:work --daemon

# Or use Supervisor for production
sudo nano /etc/supervisor/conf.d/restopos-worker.conf
```

Supervisor configuration:

```ini
[program:restopos-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/restopos/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/path/to/restopos/storage/logs/worker.log
stopwaitsecs=3600
```

### Database Optimization

```bash
# Optimize database tables
php artisan db:optimize

# Clear expired data
php artisan restopos:cleanup
```

## Backup Configuration

### Automated Backups

```php
// config/backup.php
'backup' => [
    'name' => env('APP_NAME', 'restopos'),
    'source' => [
        'files' => [
            'include' => [
                base_path(),
            ],
            'exclude' => [
                base_path('vendor'),
                base_path('node_modules'),
            ],
        ],
        'databases' => [
            'mysql',
        ],
    ],
    'destination' => [
        'filename_prefix' => '',
        'disks' => [
            's3',
        ],
    ],
],
```

Schedule backups in `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule)
{
    $schedule->command('backup:run')
             ->daily()
             ->at('02:00');
             
    $schedule->command('backup:clean')
             ->daily()
             ->at('03:00');
}
```

## Monitoring and Logging

### Logging Configuration

```env
# Logging
LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

# Sentry Error Tracking
SENTRY_LARAVEL_DSN=your_sentry_dsn
```

### Health Checks

```php
// config/health.php
'checks' => [
    \Spatie\Health\Checks\Checks\DatabaseCheck::new(),
    \Spatie\Health\Checks\Checks\CacheCheck::new(),
    \Spatie\Health\Checks\Checks\QueueCheck::new(),
    \Spatie\Health\Checks\Checks\StorageCheck::new(),
],
```

## Final Steps

After configuration:

1. **Clear all caches**:
   ```bash
   php artisan optimize:clear
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

2. **Set up cron jobs**:
   ```bash
   # Add to crontab
   * * * * * cd /path/to/restopos && php artisan schedule:run >> /dev/null 2>&1
   ```

3. **Test the configuration**:
   ```bash
   php artisan config:show
   php artisan health:check
   ```

4. **Set up monitoring**:
   - Configure uptime monitoring
   - Set up error tracking
   - Monitor performance metrics

## Next Steps

- [Take your first steps](/getting-started/first-steps)
- [Explore the dashboard](/user-guide/dashboard)
- [Configure your menu](/user-guide/menu)
- [Set up payment methods](/user-guide/settings)