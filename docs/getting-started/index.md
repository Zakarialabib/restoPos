# Getting Started

Welcome to RestoPos! This comprehensive guide will help you get up and running with our modern restaurant management system.
<!-- 
<div style="display: flex; gap: 12px; margin: 24px 0;">
  <VersionBadge version="2.1.0" type="stable" :show-label="true" />
  <StatusBadge status="stable" />
</div> -->

## What is RestoPos?

RestoPos is a complete restaurant management system designed for modern establishments. It provides:

## Installation Options

<!-- <InstallationGuide 
  title="Choose Your Installation Method"
  description="Select the installation method that best fits your needs and environment"
  :methods="[
    {
      name: 'Docker (Recommended)',
      type: 'docker',
      description: 'Quick setup with Docker for production and development',
      prerequisites: [
        'Docker 20.10+',
        'Docker Compose 2.0+'
      ],
      steps: [
        {
          title: 'Clone the repository',
          description: 'Get the latest RestoPos source code',
          code: 'git clone https://github.com/restopos/restopos.git\ncd restopos',
          language: 'bash'
        },
        {
          title: 'Start with Docker Compose',
          description: 'Launch all services including database and web server',
          code: 'docker-compose up -d',
          language: 'bash',
          note: 'This will start RestoPos on port 8080'
        },
        {
          title: 'Run initial setup',
          description: 'Initialize the database and create admin user',
          code: 'docker-compose exec app php artisan migrate --seed',
          language: 'bash'
        }
      ],
      verification: {
        description: 'Open your browser and navigate to http://localhost:8080. You should see the RestoPos login page.',
        code: 'curl -f http://localhost:8080/api/health || echo \"Service not ready\"',
        language: 'bash'
      },
      troubleshooting: [
        {
          problem: 'Port 8080 already in use',
          solution: 'Change the port in docker-compose.yml or stop the conflicting service',
          code: 'docker-compose down\n# Edit docker-compose.yml to change port\ndocker-compose up -d',
          language: 'bash'
        },
        {
          problem: 'Database connection failed',
          solution: 'Ensure MySQL container is running and check environment variables',
          code: 'docker-compose logs mysql\ndocker-compose restart mysql',
          language: 'bash'
        }
      ]
    },
    {
      name: 'Manual Installation',
      type: 'manual',
      description: 'Traditional installation for development and customization',
      prerequisites: [
        'PHP 8.1 or higher with extensions: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML',
        'Composer 2.0+',
        'Node.js 18+ with npm',
        'MySQL 8.0+ or PostgreSQL 13+',
        'Web server (Apache/Nginx) or PHP built-in server for development'
      ],
      steps: [
        {
          title: 'Download RestoPos',
          description: 'Clone the repository or download the latest release',
          code: 'git clone https://github.com/restopos/restopos.git\ncd restopos',
          language: 'bash'
        },
        {
          title: 'Install PHP dependencies',
          description: 'Use Composer to install backend dependencies',
          code: 'composer install --optimize-autoloader --no-dev',
          language: 'bash',
          note: 'Remove --no-dev flag for development installation'
        },
        {
          title: 'Install Node.js dependencies',
          description: 'Install frontend build tools and dependencies',
          code: 'npm install',
          language: 'bash'
        },
        {
          title: 'Environment configuration',
          description: 'Set up your environment variables',
          code: 'cp .env.example .env\nphp artisan key:generate',
          language: 'bash',
          note: 'Edit .env file to configure database and other settings'
        },
        {
          title: 'Database setup',
          description: 'Create database tables and seed initial data',
          code: 'php artisan migrate --seed',
          language: 'bash'
        },
        {
          title: 'Build frontend assets',
          description: 'Compile CSS and JavaScript files',
          code: 'npm run build',
          language: 'bash'
        },
        {
          title: 'Start the application',
          description: 'Launch the development server',
          code: 'php artisan serve',
          language: 'bash'
        }
      ],
      verification: {
        description: 'Visit http://localhost:8000 to access RestoPos. Default login: admin@restopos.com / password',
        code: 'php artisan --version\nphp artisan route:list | grep api',
        language: 'bash'
      },
      troubleshooting: [
        {
          problem: 'Composer install fails',
          solution: 'Check PHP version and required extensions',
          code: 'php -v\nphp -m | grep -E \"(bcmath|ctype|fileinfo|json|mbstring|openssl|pdo|tokenizer|xml)\"',
          language: 'bash'
        },
        {
          problem: 'Database migration fails',
          solution: 'Verify database connection and credentials in .env file',
          code: 'php artisan config:clear\nphp artisan migrate:status',
          language: 'bash'
        },
        {
          problem: 'Permission denied errors',
          solution: 'Set proper file permissions for storage and cache directories',
          code: 'chmod -R 775 storage bootstrap/cache\nchown -R www-data:www-data storage bootstrap/cache',
          language: 'bash'
        }
      ]
    },
    {
      name: 'One-Click Deploy',
      type: 'cloud',
      description: 'Deploy to cloud platforms with pre-configured environments',
      steps: [
        {
          title: 'Choose your platform',
          description: 'Select from our supported cloud deployment options',
          code: '# Heroku\nheroku create your-restopos-app\ngit push heroku main\n\n# DigitalOcean App Platform\ndoctl apps create --spec .do/app.yaml\n\n# AWS Elastic Beanstalk\neb init\neb create production',
          language: 'bash'
        },
        {
          title: 'Configure environment',
          description: 'Set environment variables in your cloud platform',
          code: '# Example environment variables\nAPP_ENV=production\nDB_CONNECTION=mysql\nDB_HOST=your-db-host\nDB_DATABASE=restopos\nDB_USERNAME=your-username\nDB_PASSWORD=your-password',
          language: 'bash'
        }
      ],
      verification: {
        description: 'Access your deployed application using the provided URL',
        code: 'curl -f https://your-app.herokuapp.com/api/health',
        language: 'bash'
      }
    }
  ]"
/> -->

## System Requirements

### Minimum Requirements

| Component | Requirement |
|-----------|-------------|
| **PHP** | 8.1+ with required extensions |
| **Database** | MySQL 8.0+ or PostgreSQL 13+ |
| **Node.js** | 18+ (for building assets) |
| **Memory** | 512MB RAM minimum |
| **Storage** | 1GB free space |
| **Web Server** | Apache 2.4+, Nginx 1.18+, or PHP built-in |

### Recommended for Production

| Component | Recommendation |
|-----------|----------------|
| **PHP** | 8.2+ with OPcache enabled |
| **Database** | MySQL 8.0+ with InnoDB |
| **Memory** | 2GB+ RAM |
| **Storage** | SSD with 10GB+ free space |
| **Web Server** | Nginx with PHP-FPM |
| **SSL** | Valid SSL certificate |

## Quick Configuration

After installation, you'll need to configure a few essential settings:

### 1. Database Configuration

<CodeTabs>
<template #env>

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=restopos
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

</template>
<template #postgresql>

```bash
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=restopos
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

</template>
</CodeTabs>

### 2. Application Settings

```bash
# Application
APP_NAME="Your Restaurant Name"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
```

### 3. Payment Gateway Setup

::: tip Payment Integration
RestoPos supports multiple payment gateways. Configure your preferred payment processor in the admin panel after installation.
:::

## First Steps After Installation

1. **Access Admin Panel**: Visit `/admin` and log in with default credentials
2. **Change Default Password**: Update the admin password immediately
3. **Configure Restaurant Settings**: Set up your restaurant information
4. **Create Menu Categories**: Organize your menu structure
5. **Add Menu Items**: Input your products and pricing
6. **Set Up Payment Methods**: Configure accepted payment types
7. **Create Staff Accounts**: Add your team members with appropriate roles

## Next Steps

Now that RestoPos is installed, explore these guides to get the most out of your system:

<div class="features-grid">
  <FeatureCard
    title="Dashboard Overview"
    description="Learn about the main dashboard and key metrics"
    icon="📊"
    link="/user-guide/dashboard"
    size="small"
  />
  
  <FeatureCard
    title="Menu Management"
    description="Set up your menu items, categories, and pricing"
    icon="📋"
    link="/user-guide/menu"
    size="small"
  />
  
  <FeatureCard
    title="Order Processing"
    description="Handle orders from creation to completion"
    icon="🛒"
    link="/user-guide/orders"
    size="small"
  />
  
  <FeatureCard
    title="API Integration"
    description="Integrate with third-party services and build custom solutions"
    icon="🔌"
    link="/api/"
    size="small"
  />
</div>

## Getting Help

If you encounter any issues during installation or setup:

- 📖 **Documentation**: Check our comprehensive guides
- 💬 **Community Forum**: Ask questions and get help from other users
- 🐛 **GitHub Issues**: Report bugs or request features
- 📧 **Support Email**: Contact our support team for assistance

::: warning Important Security Notes
- Always use HTTPS in production
- Change default passwords immediately
- Keep your installation updated
- Regular database backups are essential
- Use strong, unique passwords for all accounts
:::