# Routes & Endpoints Overview

This document provides a comprehensive overview of all routes and endpoints in the RestoPos system, organized by functionality and user access levels.

## System Architecture Overview

RestoPos is built with Laravel and Livewire, providing a modern, reactive web application with the following main areas:

- **Customer Portal**: Public-facing menu and ordering system
- **TV Display System**: Digital signage and menu display
- **Admin Management**: Complete restaurant management system
- **Printing System**: Automated printing for various business needs

## Customer Portal Routes

### Public Access Routes

| Route | Method | Component | Description |
|-------|--------|-----------|-------------|
| `/` | GET | `Landing` | Landing page and registration |
| `/registration/success/{user}` | GET | `RegistrationSuccess` | Registration confirmation |
| `/menu` | GET | `MenuIndex` | Customer menu display |
| `/menu/tv` | GET | `TvMenu` | TV menu display |
| `/showcase` | GET | `ThemeShowcase` | Theme showcase demo |
| `/theme-demo` | GET | View | Theme demo page |
| `/menu-grid-demo` | GET | `ThemedMenuGrid` | Menu grid demo |
| `/compose/{productType}` | GET | `ComposableProductIndex` | Composable product interface |

### Features

- **Menu Display**: Responsive menu with categories and products
- **Ordering System**: Real-time order placement and tracking
- **Composable Products**: Custom product composition interface
- **Theme Customization**: Multiple theme options for different restaurant styles

## TV Display System

### Routes

| Route | Method | Component | Description |
|-------|--------|-----------|-------------|
| `/menu/tv` | GET | `TvMenu` | Main TV menu display |
| `/showcase` | GET | `ThemeShowcase` | Theme showcase for TV |
| `/admin/settings/theme-showcase` | GET | `ThemeShowcase` | Admin theme management |

### Features

- **Digital Signage**: High-quality menu display for TV screens
- **Theme Management**: Multiple visual themes for different restaurant styles
- **Content Scheduling**: Time-based content display
- **Real-time Updates**: Live menu updates without page refresh

## Admin Management Routes

### Dashboard & POS

| Route | Method | Component | Description |
|-------|--------|-----------|-------------|
| `/admin/dashboard` | GET | `Dashboard` | Admin dashboard |
| `/admin/pos` | GET | `Pos` | Point of Sale system |

### Product Management

| Route | Method | Component | Description |
|-------|--------|-----------|-------------|
| `/admin/products` | GET | `AdminProductIndex` | Product management |
| `/admin/products/composable` | GET | `AdminComposableProductIndex` | Composable products |

### Inventory Management

| Route | Method | Component | Description |
|-------|--------|-----------|-------------|
| `/admin/inventory` | GET | `AdminInventoryIndex` | Inventory overview |
| `/admin/inventory/ingredients` | GET | `AdminIngredientIndex` | Ingredient management |
| `/admin/inventory/recipes` | GET | `AdminRecipeIndex` | Recipe management |
| `/admin/inventory/waste` | GET | `AdminWasteIndex` | Waste tracking |

### Order Management

| Route | Method | Component | Description |
|-------|--------|-----------|-------------|
| `/admin/orders` | GET | `AdminOrderIndex` | Order management |

### Kitchen Management

| Route | Method | Component | Description |
|-------|--------|-----------|-------------|
| `/admin/kitchen` | GET | `AdminKitchenIndex` | Kitchen dashboard |
| `/admin/kitchen/display` | GET | `AdminKitchenDisplay` | Kitchen display |
| `/admin/kitchen/dashboard` | GET | `AdminKitchenDashboard` | Kitchen management |

### Finance & Reports

| Route | Method | Component | Description |
|-------|--------|-----------|-------------|
| `/admin/finance/cash-register` | GET | `AdminCashRegisterIndex` | Cash register |
| `/admin/finance/purchases` | GET | `AdminPurchaseIndex` | Purchase management |
| `/admin/finance/expenses` | GET | `AdminExpenseIndex` | Expense tracking |

### Customer & Supplier Management

| Route | Method | Component | Description |
|-------|--------|-----------|-------------|
| `/admin/customers` | GET | `Index` | Customer management |
| `/admin/suppliers` | GET | `SupplierManagement` | Supplier management |

### Settings & Configuration

| Route | Method | Component | Description |
|-------|--------|-----------|-------------|
| `/admin/settings` | GET | `Settings` | General settings |
| `/admin/settings/languages` | GET | `AdminLanguageIndex` | Language management |
| `/admin/settings/theme-showcase` | GET | `ThemeShowcase` | Theme management |
| `/admin/settings/section-schedules` | GET | `ScheduleManager` | Schedule management |

## Installation Routes

| Route | Method | Component | Description |
|-------|--------|-----------|-------------|
| `/install` | GET | `StepManager` | System installation wizard |

## Route Middleware

### Authentication Middleware

- **`web`**: Standard web middleware for session handling
- **`admin`**: Admin authentication and authorization
- **`installation.check`**: Installation status verification

### Route Groups

```php
// Public routes (no authentication required)
Route::middleware(['web'])->group(function () {
    // Customer portal routes
    // TV display routes
    // Public menu routes
});

// Admin routes (authentication required)
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['web', 'admin'])
    ->group(function () {
    // All admin management routes
});
```

## API Endpoints

The system also provides RESTful API endpoints for:

- **Orders API**: Order management and tracking
- **Menu API**: Menu and product data
- **Inventory API**: Stock management
- **Kitchen API**: Kitchen operations
- **Printing API**: Print job management

## Route Naming Conventions

- **Customer routes**: No prefix, descriptive names
- **Admin routes**: `admin.` prefix with functional grouping
- **API routes**: `api.` prefix with versioning
- **Installation routes**: `installation.` prefix

## Security Considerations

- All admin routes require authentication
- Customer routes are publicly accessible
- API routes require proper authentication tokens
- Installation routes have special middleware checks

## Performance Optimization

- Route caching for production environments
- Lazy loading of components
- Optimized database queries
- CDN integration for static assets

## Next Steps

- [System Architecture](./architecture.md) - Detailed system architecture
- [Features Overview](./features.md) - Complete feature list
- [Installation Guide](./installation.md) - Step-by-step installation
- [API Reference](../api/) - Complete API documentation 