# RestoPos Installation Guide

This guide provides step-by-step instructions for installing and setting up the RestoPos restaurant management system.

## 📋 Prerequisites

### System Requirements

- **PHP**: 8.1 or higher
- **Composer**: Latest version
- **Node.js**: 16.0 or higher
- **NPM**: Latest version
- **Database**: MySQL 8.0+ or PostgreSQL 13+
- **Web Server**: Apache 2.4+ or Nginx 1.18+
- **Redis**: 6.0+ (for caching and sessions)

### Server Specifications

#### Minimum Requirements
- **CPU**: 2 cores
- **RAM**: 4GB
- **Storage**: 20GB SSD
- **Network**: 10 Mbps

#### Recommended Requirements
- **CPU**: 4+ cores
- **RAM**: 8GB+
- **Storage**: 50GB+ SSD
- **Network**: 100 Mbps+

## 🚀 Quick Installation

### 1. Clone the Repository

```bash
git clone https://github.com/restopos/restopos.git
cd restopos
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 3. Environment Setup

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Configuration

Edit `.env` file with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=restopos
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Run Migrations

```bash
# Run database migrations
php artisan migrate

# Seed the database with initial data
php artisan db:seed
```

### 6. Build Assets

```bash
# Build frontend assets
npm run build
```

### 7. Set Permissions

```bash
# Set storage permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 8. Start the Development Server

```bash
# Start Laravel development server
php artisan serve

# Start Vite for asset compilation (in another terminal)
npm run dev
```

## 🔧 Detailed Installation

### Step 1: Server Preparation

#### Update System Packages

```bash
# Ubuntu/Debian
sudo apt update && sudo apt upgrade -y

# CentOS/RHEL
sudo yum update -y
```

#### Install Required Software

```bash
# Install PHP and extensions
sudo apt install php8.1 php8.1-fpm php8.1-mysql php8.1-xml php8.1-curl php8.1-mbstring php8.1-zip php8.1-gd php8.1-bcmath php8.1-redis

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt-get install -y nodejs

# Install Redis
sudo apt install redis-server
```

### Step 2: Database Setup

#### MySQL Installation

```bash
# Install MySQL
sudo apt install mysql-server

# Secure MySQL installation
sudo mysql_secure_installation

# Create database and user
sudo mysql -u root -p
```

```sql
CREATE DATABASE restopos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'restopos_user'@'localhost' IDENTIFIED BY 'your_secure_password';
GRANT ALL PRIVILEGES ON restopos.* TO 'restopos_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

#### PostgreSQL Installation (Alternative)

```bash
# Install PostgreSQL
sudo apt install postgresql postgresql-contrib

# Create database and user
sudo -u postgres psql
```

```sql
CREATE DATABASE restopos;
CREATE USER restopos_user WITH ENCRYPTED PASSWORD 'your_secure_password';
GRANT ALL PRIVILEGES ON DATABASE restopos TO restopos_user;
\q
```

### Step 3: Web Server Configuration

#### Nginx Configuration

Create Nginx configuration file:

```bash
sudo nano /etc/nginx/sites-available/restopos
```

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/restopos/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Enable the site:

```bash
sudo ln -s /etc/nginx/sites-available/restopos /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

#### Apache Configuration

Create Apache virtual host:

```bash
sudo nano /etc/apache2/sites-available/restopos.conf
```

```apache
<VirtualHost *:80>
    ServerName your-domain.com
    DocumentRoot /var/www/restopos/public

    <Directory /var/www/restopos/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/restopos_error.log
    CustomLog ${APACHE_LOG_DIR}/restopos_access.log combined
</VirtualHost>
```

Enable the site:

```bash
sudo a2ensite restopos.conf
sudo a2enmod rewrite
sudo systemctl restart apache2
```

### Step 4: Application Setup

#### Clone and Configure

```bash
# Clone repository
cd /var/www
sudo git clone https://github.com/restopos/restopos.git
sudo chown -R www-data:www-data restopos
cd restopos

# Install dependencies
composer install --no-dev --optimize-autoloader
npm install
npm run build

# Copy environment file
cp .env.example .env
```

#### Environment Configuration

Edit `.env` file:

```env
APP_NAME="RestoPos"
APP_ENV=production
APP_KEY=base64:your_generated_key
APP_DEBUG=false
APP_URL=https://your-domain.com

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=restopos
DB_USERNAME=restopos_user
DB_PASSWORD=your_secure_password

BROADCAST_DRIVER=pusher
CACHE_DRIVER=redis
FILESYSTEM_DISK=local
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
SESSION_LIFETIME=120

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

PUSHER_APP_ID=your_pusher_app_id
PUSHER_APP_KEY=your_pusher_key
PUSHER_APP_SECRET=your_pusher_secret
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1
```

#### Database Setup

```bash
# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Create storage link
php artisan storage:link
```

#### Set Permissions

```bash
# Set proper permissions
sudo chown -R www-data:www-data /var/www/restopos
sudo chmod -R 755 /var/www/restopos
sudo chmod -R 775 /var/www/restopos/storage
sudo chmod -R 775 /var/www/restopos/bootstrap/cache
```

### Step 5: Queue Configuration

#### Redis Setup

```bash
# Configure Redis
sudo nano /etc/redis/redis.conf
```

Add/modify these settings:

```conf
maxmemory 256mb
maxmemory-policy allkeys-lru
```

#### Queue Worker Setup

Create systemd service for queue workers:

```bash
sudo nano /etc/systemd/system/restopos-queue.service
```

```ini
[Unit]
Description=RestoPos Queue Worker
After=network.target

[Service]
Type=simple
User=www-data
Group=www-data
WorkingDirectory=/var/www/restopos
ExecStart=/usr/bin/php artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
Restart=always
RestartSec=10

[Install]
WantedBy=multi-user.target
```

Enable and start the service:

```bash
sudo systemctl enable restopos-queue
sudo systemctl start restopos-queue
```

### Step 6: SSL Configuration

#### Let's Encrypt SSL

```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx

# Obtain SSL certificate
sudo certbot --nginx -d your-domain.com

# Auto-renewal
sudo crontab -e
```

Add this line for auto-renewal:

```cron
0 12 * * * /usr/bin/certbot renew --quiet
```

## 🔧 Development Installation

### Using Laravel Sail (Docker)

```bash
# Install Docker and Docker Compose
curl -s "https://laravel.build/restopos" | bash

# Start the application
./vendor/bin/sail up -d

# Install dependencies
./vendor/bin/sail composer install
./vendor/bin/sail npm install

# Setup environment
cp .env.example .env
./vendor/bin/sail artisan key:generate

# Run migrations
./vendor/bin/sail artisan migrate --seed

# Build assets
./vendor/bin/sail npm run build
```

### Using Laravel Valet (macOS)

```bash
# Install Valet
composer global require laravel/valet
valet install

# Park the project
cd restopos
valet park

# Setup the application
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run build
```

## 🧪 Testing Installation

### Verify Installation

1. **Check Application**: Visit `http://your-domain.com`
2. **Check Admin Panel**: Visit `http://your-domain.com/admin`
3. **Check API**: Visit `http://your-domain.com/api`

### Run Tests

```bash
# Run PHP tests
php artisan test

# Run browser tests
php artisan dusk

# Run specific test suites
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit
```

### Performance Testing

```bash
# Install Artisan command for performance testing
php artisan make:command PerformanceTest

# Run performance tests
php artisan test:performance
```

## 🔧 Configuration

### Cache Configuration

```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Cache for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Queue Configuration

```bash
# Start queue workers
php artisan queue:work

# Monitor queue
php artisan queue:monitor

# Failed jobs
php artisan queue:failed
php artisan queue:retry all
```

### Log Configuration

```bash
# View logs
tail -f storage/logs/laravel.log

# Clear logs
php artisan log:clear
```

## 🚨 Troubleshooting

### Common Issues

#### Permission Errors
```bash
sudo chown -R www-data:www-data /var/www/restopos
sudo chmod -R 755 /var/www/restopos
sudo chmod -R 775 storage bootstrap/cache
```

#### Database Connection Issues
```bash
# Check database connection
php artisan tinker
DB::connection()->getPdo();

# Clear config cache
php artisan config:clear
```

#### Queue Issues
```bash
# Restart queue workers
sudo systemctl restart restopos-queue

# Check queue status
php artisan queue:work --once
```

#### Asset Issues
```bash
# Rebuild assets
npm run build

# Clear asset cache
php artisan view:clear
```

### Performance Optimization

#### Database Optimization
```bash
# Optimize database
php artisan db:optimize

# Analyze slow queries
php artisan db:analyze
```

#### Cache Optimization
```bash
# Enable Redis caching
php artisan cache:enable

# Warm up cache
php artisan cache:warm
```

## 📚 Next Steps

- [System Configuration](../getting-started/configuration.md) - Configure your system
- [First Steps](../getting-started/first-steps.md) - Get started with RestoPos
- [User Guide](../customer/) - Learn how to use the system
- [Developer Guide](../developer/) - Development documentation 