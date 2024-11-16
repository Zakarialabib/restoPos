# Fruit Bar Management System

## Overview
The Fruit Bar Management System is designed for fruit bar businesses, using the **Laravel** framework and **Livewire** for a dynamic, reactive interface. It enables effective management of products, orders, inventory, and customer interactions with a focus on customizable products and recipes.

## Table of Contents
1. [Technology Stack](#technology-stack)
2. [Features](#features)
   - [Core Modules](#core-modules)
   - [Customer-Facing Features](#customer-facing-features)
   - [Admin Features](#admin-features)
3. [Implementation Status](#implementation-status)
4. [Data Models](#data-models)
5. [Key Optimizations](#key-optimizations)
6. [Implementation Focus](#implementation-focus)
7. [Directory Structure](#directory-structure)
8. [Testing Strategy](#testing-strategy)

---

## Technology Stack
- **Backend**: Laravel 11
- **Frontend**: Livewire 3, Alpine.js
- **Styling**: Tailwind CSS
- **Database**: MySQL

---

## Features

### Core Modules
1. **Product Management**
   - Enables dynamic product creation, customizable product composition, pricing control, availability management, and nutritional tracking.
2. **Recipe Management**
   - Detailed recipe creation with multilingual support, step-by-step instructions, preparation time tracking, and nutritional calculations.
3. **Order Processing**
   - Manages order creation, stock validation, order tracking, and payment status updates.

### Customer-Facing Features
1. **Custom Product Composition**
   - Allows customers to create custom juice blends and dried fruit boxes with real-time pricing and nutritional display.
2. **Product Catalog**
   - Enables browsing, category-based filtering, and detailed nutritional information for products.
3. **Shopping Cart**
   - Provides functionality to add/remove items, update quantities, view order summaries, and checkout.

### Admin Features
1. **Product & Recipe Management**
   - Allows admins to create/edit products and recipes, configure pricing, upload images, and set ingredient details.
2. **Order Management**
   - Admins can process customer orders, validate stock availability, update order statuses, and track order history.
3. **Dashboard**
   - Displays sales analytics, stock alerts, expiry notifications, recent orders, and performance metrics.

---

## Implementation Status

### Completed
- [x] Dynamic product composition
- [x] Inventory tracking
- [x] Multi-language support
- [x] Theme customization
- [x] Dashboard layouts
- [x] Recipe management
- [x] Stock validation

### In Progress
- [ ] Predictive analytics
- [ ] Supplier order automation
- [ ] Advanced reporting
- [ ] Customer preference tracking

### Planned
- [ ] Enhanced caching
- [ ] Background job optimization
- [ ] Real-time notifications
---

## Data Models

1. **Product**
   - Manages saleable items with pricing, availability, and stock management.
   - Relationships: Belongs to Category, Ingredients, OrderItems, and Composables.

2. **Category**
   - Organizes products, tracks counts, and supports slug generation.
   - Relationships: Has many Products.

3. **Ingredient**
   - Manages raw materials with stock and expiry tracking.
   - Relationships: Belongs to many Products and Composables.

4. **Order**
   - Manages transactions, tracks order history, and includes soft delete support.
   - Relationships: Has many OrderItems and OrderHistories.

5. **OrderItem**
   - Represents items within an order, with subtotal and quantity management.
   - Relationships: Belongs to Order, Product, and Composable.

6. **Composable**
   - Manages customizable products like juice mixes, with price calculation and soft delete support.
   - Relationships: Belongs to many Products and Ingredients.

7. **InventoryAlert**
   - Tracks inventory notifications and resolution status.
   - Relationships: Belongs to Ingredient.

---

## Key Optimizations

1. **Streamlined Order Processing**
   - Optimizes real-time order validation and tracking.
2. **Efficient Product Management**
   - Simplifies product customization and real-time pricing updates.
3. **Automated Reporting and Analytics**
   - Generates insights on sales, inventory, and performance metrics.
4. **Optimized User Interface**
   - Enhances user experience with Livewire and Alpine.js for a smooth, reactive interface.

---

## Implementation Focus

To ensure optimal performance and functionality:
1. **Enhance Existing Models**
   - Streamline relationships and optimize data structure.
2. **Optimize Livewire Components**
   - Refine components for efficient data binding and reactivity.
3. **Improve Data Processing**
   - Utilize caching, background processing, and optimized SQL queries.
4. **Automate Workflows**
   - Enable predictive analytics and automated supplier order management.
5. **Improve Data Visualization**
   - Enhance the dashboard for actionable insights on sales, stock, and trends.

---
## Directory Structure

### app/
#### Livewire/
- **Admin/**
  - `ProductManagement.php` - Product CRUD operations and management
  - `OrderManagement.php` - Order processing and tracking
  - `CategoryManagement.php` - Category management
  - `IngredientManagement.php` - Ingredient inventory management
  - `RecipeManagement.php` - Recipe creation and management
  - `Dashboard.php` - Admin dashboard with analytics
- **User/**
  - `Index.php` - Main user interface component
  - `ComposableJuicesIndex.php` - Custom juice creation interface
  - `ComposableSaladeIndex.php` - Custom salad creation interface
  - `ComposableDriedFruitsIndex.php` - Dried fruits selection interface

### resources/views/
#### livewire/
- **admin/**
  - `dashboard.blade.php` - Admin dashboard view with analytics
  - `product-management.blade.php` - Product management interface
  - `order-management.blade.php` - Order management interface
  - `category-management.blade.php` - Category management interface
  - `recipe-management.blade.php` - Recipe management interface
- **user/**
  - `index.blade.php` - Main user interface
  - `composable-juices-index.blade.php` - Juice customization interface
  - `composable-salade-index.blade.php` - Salad customization interface
  - `composable-dried-fruits-index.blade.php` - Dried fruits selection interface

### database/
#### migrations/
- `2024_01_01_000000_create_users_table.php`
- `2024_01_01_000001_create_products_table.php`
- `2024_01_01_000002_create_categories_table.php`
- `2024_01_01_000003_create_ingredients_table.php`
- `2024_01_01_000004_create_orders_table.php`
- `2024_01_01_000005_create_order_items_table.php`
- `2024_01_01_000006_create_recipes_table.php`
- `2024_01_01_000007_create_composables_table.php`
- `2024_01_01_000008_create_inventory_alerts_table.php`

#### seeders/
- `DatabaseSeeder.php`
- `ProductSeeder.php`
- `CategorySeeder.php`
- `IngredientSeeder.php`
- `RecipeSeeder.php`

#### factories/
- `UserFactory.php`
- `ProductFactory.php`
- `CategoryFactory.php`
- `IngredientFactory.php`
- `OrderFactory.php`

### routes/
- `web.php` - Web routes configuration
- `auth.php` - Authentication routes
- `api.php` - API routes (if needed)

### lang/
- `en/` - English translations
- `fr/` - French translations
- `ar/` - Arabic translations

---

## Testing Strategy

- **Feature Tests**: Validate the functionality of key features like order processing and product management.
- **Unit Tests**: Ensure individual components and models work as expected.
- **Integration Tests**: Test the interaction between different parts of the application.
- **Tools Used**: Pest for testing, with potential use of Laravel Dusk for end-to-end testing.
