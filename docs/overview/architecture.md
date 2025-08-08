# RestoPos System Architecture

This document provides a detailed overview of the RestoPos system architecture, including the technology stack, component structure, and data flow.

## 🏗️ System Overview

RestoPos is built as a modern web application using Laravel (PHP) framework with Livewire for reactive components. The system follows a modular architecture designed for scalability, maintainability, and real-time performance.

## 🛠️ Technology Stack

### Backend Framework
- **Laravel 10+**: Modern PHP framework with robust features
- **PHP 8.1+**: Latest PHP version with performance improvements
- **MySQL/PostgreSQL**: Relational database for data persistence
- **Redis**: Caching and session management

### Frontend Technologies
- **Livewire 3**: Reactive PHP components for dynamic UI
- **Alpine.js**: Lightweight JavaScript framework for interactivity
- **Tailwind CSS**: Utility-first CSS framework for styling
- **Vite**: Modern build tool for asset compilation

### Real-time Features
- **WebSockets**: Real-time communication via Pusher
- **Laravel Echo**: WebSocket client for Laravel
- **Livewire Polling**: Automatic component updates

### Development Tools
- **VitePress**: Documentation site generation
- **PHPStan**: Static analysis for code quality
- **Laravel Pint**: Code style enforcement
- **PHPUnit**: Testing framework

## 🏛️ Architecture Layers

### 1. Presentation Layer
```
┌─────────────────────────────────────┐
│           Presentation Layer        │
├─────────────────────────────────────┤
│ • Livewire Components              │
│ • Blade Templates                  │
│ • Alpine.js Interactions          │
│ • Responsive Design               │
└─────────────────────────────────────┘
```

### 2. Business Logic Layer
```
┌─────────────────────────────────────┐
│         Business Logic Layer        │
├─────────────────────────────────────┤
│ • Livewire Components              │
│ • Services                         │
│ • Event Listeners                  │
│ • Jobs & Queues                   │
└─────────────────────────────────────┘
```

### 3. Data Access Layer
```
┌─────────────────────────────────────┐
│         Data Access Layer           │
├─────────────────────────────────────┤
│ • Eloquent Models                  │
│ • Database Migrations              │
│ • Query Builders                   │
│ • Repository Pattern               │
└─────────────────────────────────────┘
```

### 4. Infrastructure Layer
```
┌─────────────────────────────────────┐
│        Infrastructure Layer         │
├─────────────────────────────────────┤
│ • Database (MySQL/PostgreSQL)      │
│ • Cache (Redis)                    │
│ • File Storage                     │
│ • External APIs                    │
└─────────────────────────────────────┘
```

## 📁 Directory Structure

```
restoPos/
├── app/
│   ├── Console/Commands/           # Artisan commands
│   ├── Enums/                      # PHP enums for type safety
│   ├── Events/                     # Event classes
│   ├── Exceptions/                 # Custom exceptions
│   ├── Facades/                    # Laravel facades
│   ├── Http/
│   │   ├── Controllers/           # HTTP controllers
│   │   ├── Middleware/            # Custom middleware
│   │   └── Requests/              # Form request validation
│   ├── Jobs/                      # Queue jobs
│   ├── Listeners/                 # Event listeners
│   ├── Livewire/                  # Livewire components
│   │   ├── Admin/                 # Admin components
│   │   ├── Front/                 # Customer-facing components
│   │   ├── Kitchen/               # Kitchen management
│   │   └── TvMenu/                # TV display components
│   ├── Models/                    # Eloquent models
│   ├── Notifications/             # Notification classes
│   ├── Providers/                 # Service providers
│   ├── Services/                  # Business logic services
│   ├── Support/                   # Helper classes
│   └── Traits/                    # Reusable traits
├── config/                        # Configuration files
├── database/
│   ├── factories/                 # Model factories
│   ├── migrations/                # Database migrations
│   └── seeders/                   # Database seeders
├── docs/                          # Documentation
├── lang/                          # Language files
├── public/                        # Public assets
├── resources/
│   ├── css/                       # Stylesheets
│   ├── js/                        # JavaScript files
│   └── views/                     # Blade templates
├── routes/                        # Route definitions
├── storage/                       # File storage
└── tests/                         # Test files
```

## 🔄 Data Flow Architecture

### Customer Order Flow
```
1. Customer Portal
   ↓
2. Menu Selection
   ↓
3. Order Creation
   ↓
4. Payment Processing
   ↓
5. Kitchen Notification
   ↓
6. Order Preparation
   ↓
7. Order Completion
   ↓
8. Customer Notification
```

### Real-time Updates Flow
```
1. Database Change
   ↓
2. Event Triggered
   ↓
3. Event Listener
   ↓
4. WebSocket Broadcast
   ↓
5. Livewire Component Update
   ↓
6. UI Update
```

## 🧩 Component Architecture

### Livewire Components Structure

#### Admin Components
```
Admin/
├── Dashboard/
│   └── Index.php                  # Main dashboard
├── Products/
│   ├── Index.php                  # Product management
│   └── Composable/
│       └── Index.php              # Composable products
├── Inventory/
│   ├── Index.php                  # Inventory overview
│   ├── Ingredients/
│   │   └── Index.php              # Ingredient management
│   ├── Recipes/
│   │   └── Index.php              # Recipe management
│   └── Waste/
│       └── Index.php              # Waste tracking
├── Kitchen/
│   ├── Index.php                  # Kitchen dashboard
│   ├── Display.php                # Kitchen display
│   └── Dashboard.php              # Kitchen management
├── Finance/
│   ├── CashRegister/
│   │   └── Index.php              # Cash register
│   ├── Expenses/
│   │   └── Index.php              # Expense management
│   └── Purchases/
│       └── Index.php              # Purchase management
└── Settings/
    └── Settings.php               # System settings
```

#### Customer Components
```
Front/
├── MenuIndex.php                  # Customer menu
├── ComposableProductIndex.php     # Composable products
└── TvMenu.php                     # TV menu display
```

#### TV Display Components
```
TvMenu/
├── Index.php                      # Main TV display
├── ThemeShowcase.php              # Theme showcase
└── Modes/                         # Display modes
```

## 🗄️ Database Architecture

### Core Tables
```sql
-- Users and Authentication
admins                              # Admin users
customers                           # Customer information

-- Product Management
categories                          # Product categories
products                           # Product information
ingredients                        # Ingredient database
recipes                           # Recipe definitions

-- Order Management
orders                            # Order records
order_items                       # Order line items
order_statuses                    # Order status tracking

-- Inventory Management
inventory_items                   # Inventory tracking
inventory_movements               # Stock movements
suppliers                         # Supplier information

-- Financial Management
transactions                      # Financial transactions
expenses                          # Expense tracking
purchases                         # Purchase orders

-- System Configuration
settings                          # System settings
languages                         # Language configuration
themes                           # Theme definitions
```

### Relationships
```
Admin (1) ←→ (Many) Orders
Order (1) ←→ (Many) OrderItems
Product (1) ←→ (Many) OrderItems
Category (1) ←→ (Many) Products
Ingredient (1) ←→ (Many) Recipes
Supplier (1) ←→ (Many) Purchases
```

## 🔐 Security Architecture

### Authentication & Authorization
- **Laravel Sanctum**: API authentication
- **Role-based Access Control**: Admin permissions
- **Middleware Protection**: Route-level security
- **CSRF Protection**: Cross-site request forgery prevention

### Data Security
- **Encryption**: Sensitive data encryption
- **Input Validation**: Comprehensive input sanitization
- **SQL Injection Prevention**: Parameterized queries
- **XSS Protection**: Cross-site scripting prevention

## ⚡ Performance Architecture

### Caching Strategy
```
┌─────────────────────────────────────┐
│           Cache Layers              │
├─────────────────────────────────────┤
│ • Application Cache (Redis)         │
│ • Database Query Cache              │
│ • View Cache (Blade)               │
│ • Route Cache                      │
│ • Config Cache                     │
└─────────────────────────────────────┘
```

### Optimization Techniques
- **Lazy Loading**: Component and image lazy loading
- **Database Indexing**: Optimized database queries
- **Asset Compression**: Minified CSS/JS
- **CDN Integration**: Content delivery network
- **Queue Processing**: Background job processing

## 🔄 Event-Driven Architecture

### Event System
```
┌─────────────────────────────────────┐
│           Event Flow                │
├─────────────────────────────────────┤
│ • Order Created → OrderCreated      │
│ • Order Updated → OrderUpdated      │
│ • Payment Received → PaymentReceived│
│ • Inventory Low → LowStockAlert     │
│ • Product Expired → ExpiryAlert     │
└─────────────────────────────────────┘
```

### Listener Actions
- **Real-time Updates**: WebSocket broadcasts
- **Email Notifications**: Customer communications
- **SMS Alerts**: Critical notifications
- **Print Jobs**: Automated printing
- **Analytics Updates**: Performance tracking

## 🖨️ Printing Architecture

### Print Job System
```
┌─────────────────────────────────────┐
│         Print Architecture          │
├─────────────────────────────────────┤
│ • Print Job Queue                  │
│ • Template Engine                  │
│ • Printer Drivers                  │
│ • Print Preview                    │
│ • Print History                    │
└─────────────────────────────────────┘
```

### Print Types
- **Menu Printing**: Professional menu layouts
- **Order Tickets**: Kitchen and customer tickets
- **Receipts**: Transaction receipts
- **Reports**: Analytics and financial reports
- **Alerts**: Low stock and expiry notifications

## 🌐 API Architecture

### RESTful API Design
```
┌─────────────────────────────────────┐
│           API Endpoints             │
├─────────────────────────────────────┤
│ • /api/v1/orders                  │
│ • /api/v1/menu                    │
│ • /api/v1/inventory               │
│ • /api/v1/kitchen                 │
│ • /api/v1/printing                │
└─────────────────────────────────────┘
```

### API Features
- **Authentication**: Token-based authentication
- **Rate Limiting**: API usage limits
- **Versioning**: API version management
- **Documentation**: Auto-generated API docs
- **Testing**: Comprehensive API tests

## 🚀 Deployment Architecture

### Production Environment
```
┌─────────────────────────────────────┐
│         Production Stack            │
├─────────────────────────────────────┤
│ • Web Server (Nginx/Apache)        │
│ • PHP-FPM                         │
│ • MySQL/PostgreSQL                 │
│ • Redis Cache                      │
│ • CDN (Cloudflare)                │
│ • SSL Certificate                  │
└─────────────────────────────────────┘
```

### Scalability Considerations
- **Horizontal Scaling**: Load balancer support
- **Database Replication**: Read/write separation
- **Cache Clustering**: Redis cluster support
- **CDN Distribution**: Global content delivery
- **Auto-scaling**: Cloud-based scaling

## 🔧 Development Architecture

### Development Environment
- **Local Development**: Laravel Sail/Docker
- **Version Control**: Git with feature branches
- **Code Quality**: PHPStan, Laravel Pint
- **Testing**: PHPUnit, Pest, Dusk
- **CI/CD**: GitHub Actions

### Code Organization
- **SOLID Principles**: Clean architecture
- **Design Patterns**: Repository, Factory, Observer
- **Dependency Injection**: Service container
- **Service Layer**: Business logic separation
- **Event Sourcing**: Event-driven development

## 📊 Monitoring & Analytics

### System Monitoring
- **Application Logs**: Laravel logging
- **Performance Metrics**: Response times
- **Error Tracking**: Exception monitoring
- **User Analytics**: Behavior tracking
- **Business Metrics**: Sales and operations

### Health Checks
- **Database Connectivity**: Connection monitoring
- **Cache Status**: Redis health checks
- **Queue Processing**: Job queue monitoring
- **External Services**: API health checks
- **System Resources**: Server monitoring

## Next Steps

- [Features Overview](./features.md) - Complete feature list
- [Routes & Endpoints](./routes.md) - Route documentation
- [Installation Guide](./installation.md) - Setup instructions
- [Developer Guide](../developer/) - Development documentation 