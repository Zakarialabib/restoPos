# Installation Guide

This guide will walk you through the process of installing RestoPos on your system.

## System Requirements

Before installing RestoPos, ensure your system meets the following requirements:

### Server Requirements
- **PHP**: 8.1 or higher
- **Database**: MySQL 8.0+ or PostgreSQL 13+
- **Web Server**: Apache 2.4+ or Nginx 1.18+
- **Node.js**: 18.0+ (for asset compilation)
- **Composer**: 2.0+ (PHP dependency manager)

### PHP Extensions
Ensure the following PHP extensions are installed:
- BCMath
- Ctype
- cURL
- DOM
- Fileinfo
- JSON
- Mbstring
- OpenSSL
- PCRE
- PDO
- Tokenizer
- XML
- GD or Imagick (for image processing)

## Installation Methods

::: tabs

== Manual Installation

### Step 1: Download RestoPos

```bash
# Clone the repository
git clone https://github.com/restopos/restopos.git
cd restopos

# Or download and extract the ZIP file
wget https://github.com/restopos/restopos/archive/main.zip
unzip main.zip
cd restopos-main
```

### Step 2: Install Dependencies

```bash
# Install PHP dependencies
composer install --optimize-autoloader --no-dev

# Install Node.js dependencies
npm install
```

### Step 3: Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 4: Configure Database

Edit the `.env` file and update the database configuration:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=restopos
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Step 5: Database Setup

```bash
# Run database migrations
php artisan migrate

# Seed the database with sample data (optional)
php artisan db:seed

# Create storage symbolic link
php artisan storage:link
```

### Step 6: Build Assets

```bash
# Build production assets
npm run build

# Or for development
npm run dev
```

== Docker Installation

### Prerequisites
- Docker 20.0+
- Docker Compose 2.0+

### Step 1: Clone Repository

```bash
git clone https://github.com/restopos/restopos.git
cd restopos
```

### Step 2: Environment Setup

```bash
# Copy Docker environment file
cp .env.docker .env

# Update configuration as needed
nano .env
```

### Step 3: Build and Start Containers

```bash
# Build and start all services
docker-compose up -d

# Install dependencies
docker-compose exec app composer install
docker-compose exec app npm install

# Generate application key
docker-compose exec app php artisan key:generate

# Run migrations
docker-compose exec app php artisan migrate

# Seed database (optional)
docker-compose exec app php artisan db:seed

# Build assets
docker-compose exec app npm run build
```

== One-Click Deployment

### Heroku

[![Deploy to Heroku](https://www.herokucdn.com/deploy/button.svg)](https://heroku.com/deploy?template=https://github.com/restopos/restopos)

### DigitalOcean App Platform

[![Deploy to DO](https://www.deploytodo.com/do-btn-blue.svg)](https://cloud.digitalocean.com/apps/new?repo=https://github.com/restopos/restopos/tree/main)

### Railway

[![Deploy on Railway](https://railway.app/button.svg)](https://railway.app/new/template/restopos)

:::

## Web Server Configuration

### Apache Configuration

Create a virtual host configuration:

```apache
<VirtualHost *:80>
    ServerName restopos.local
    DocumentRoot /path/to/restopos/public
    
    <Directory /path/to/restopos/public>
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog ${APACHE_LOG_DIR}/restopos_error.log
    CustomLog ${APACHE_LOG_DIR}/restopos_access.log combined
</VirtualHost>
```

### Nginx Configuration

```nginx
server {
    listen 80;
    server_name restopos.local;
    root /path/to/restopos/public;
    
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

## File Permissions

Set the correct permissions for Laravel directories:

```bash
# Set ownership (replace www-data with your web server user)
sudo chown -R www-data:www-data /path/to/restopos

# Set directory permissions
sudo find /path/to/restopos -type d -exec chmod 755 {} \;

# Set file permissions
sudo find /path/to/restopos -type f -exec chmod 644 {} \;

# Set specific permissions for storage and cache
sudo chmod -R 775 /path/to/restopos/storage
sudo chmod -R 775 /path/to/restopos/bootstrap/cache
```

## Verification

After installation, verify that RestoPos is working correctly:

1. **Access the Application**: Navigate to your configured domain or `http://localhost:8000`
2. **Check System Status**: Visit `/health` endpoint to verify system health
3. **Login**: Use the default admin credentials (if seeded):
   - Email: `admin@restopos.com`
   - Password: `password`

## Troubleshooting

### Common Issues

#### Permission Denied Errors
```bash
# Fix storage permissions
sudo chmod -R 775 storage bootstrap/cache
sudo chown -R www-data:www-data storage bootstrap/cache
```

#### Database Connection Issues
- Verify database credentials in `.env`
- Ensure database server is running
- Check firewall settings

#### Asset Compilation Errors
```bash
# Clear npm cache
npm cache clean --force

# Reinstall dependencies
rm -rf node_modules package-lock.json
npm install
```

#### Application Key Missing
```bash
php artisan key:generate
```

### Getting Help

If you encounter issues during installation:

- Check the [FAQ](/getting-started/faq)
- Search [GitHub Issues](https://github.com/restopos/restopos/issues)
- Join our [Discord Community](https://discord.gg/restopos)
- Contact [Support](mailto:support@restopos.com)

## Next Steps

Once installation is complete:

1. [Configure your system](/getting-started/configuration)
2. [Take your first steps](/getting-started/first-steps)
3. [Explore the user guide](/user-guide/dashboard)