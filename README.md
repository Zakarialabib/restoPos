# Fruit Bar Management System

## Overview

This project is management system designed for a fruit bar business. It utilizes the **Laravel** framework with **Livewire** for dynamic, reactive user interfaces. The system efficiently manages various aspects of the fruit bar business, including product management, order processing, inventory control, and customer interactions, with a special emphasis on customizable products and recipes.

## Core Features
- Categorized products (Juices, Coffee, Dried Fruits)
- Multi-language product names and descriptions
- Dynamic pricing and availability status
- Recipe-based product creation

### 2. Advanced Ingredient System
- Detailed ingredient tracking with:
  - Stock management
  - Expiry date monitoring
  - Nutritional information
  - Storage conditions
  - Supplier information
- Unit conversion support
- Category-based organization

### 3. Recipe Management
- Detailed recipe creation with:
  - Step-by-step instructions
  - Preparation time tracking
  - Nutritional calculations
  - Ingredient quantities and units
  - Preparation notes
- Featured recipes highlighting

### 4. Composable Products
- Custom product creation system
- Type-based ingredient compatibility
- Dynamic pricing calculation
- Nutritional information aggregation
- Supported types:
  - Custom juice blends
  - Coffee variations
  - Dried fruit mixes

### 5. Inventory Management
- Real-time stock tracking
- Automatic alerts for:
  - Low stock levels
  - Approaching expiry dates
- Stock movement history
- Supplier management

### Customer-Facing Features

1. Product Composition
- Custom juice creation with real-time pricing
- Custom coffee blend creation
- Custom dried fruits box creation
- Step-by-step composition wizard
- Real-time price calculation
- Nutritional information display
- Stock validation during composition

2. Shopping Cart
- Add/remove items (both standard and custom products)
- Update quantities with stock validation
- View order summary with nutritional information
- Seamless checkout process

3. Product Catalog
- Browse available products and recipes
- Advanced search functionality
- Category-based filtering
- Theme customization
- Nutritional information display

### Admin Features

1. Inventory Management
- Track ingredient stock levels with unit conversion
- Low stock alerts with resolution tracking
- Expiry date monitoring and notifications
- Batch management
- Supplier information tracking
- Storage condition management
- Cost and price tracking

2. Product & Recipe Management
- Create/edit standard products
- Manage product categories
- Set dynamic pricing
- Upload product images
- Configure ingredients with quantities
- Create and manage detailed recipes
- Track preparation times and instructions
- Monitor nutritional information

3. Order Management
- Process both standard and custom orders
- Real-time stock validation
- Update order status
- Track order history
- Generate revenue reports
- Calculate profits

4. Recipe Management
- Create detailed product recipes
- Manage ingredient quantities
- Set preparation instructions
- Track nutritional information
- Categorize recipes (desserts, drinks, etc.)
- Associate recipes with products

5. Dashboard
- Sales overview and analytics
- Low stock alerts
- Expiry notifications
- Recent orders
- Key performance metrics

## Technical Stack

### Backend
- PHP 8.2+
- Laravel Framework 11.x
- Livewire 3.4+
- Volt for Livewire components
- Laravel Breeze for authentication

### Frontend
- Alpine.js with plugins (mask, CSP)
- Tailwind CSS with plugins:
  - Typography
  - Forms
  - Aspect Ratio
  - Container Queries
- Additional Libraries:
  - Flatpickr for date picking
  - CropperJS for image handling
  - Swiper for carousels
  - Perfect Scrollbar

## Implementation Status

✅ Implemented:
- Dynamic product composition system
- Real-time inventory tracking
- Multi-language support (with RTL capabilities)
- Theme customization system
- Responsive dashboard layouts
- Recipe management system
- Nutritional information tracking
- Stock validation system

🔄 In Progress:
- Predictive analytics for inventory
- Supplier order automation
- Voice-controlled interface
- Advanced reporting system
- Customer preference tracking

🚀 Planned Improvements:
- Enhanced caching implementation
- Background job processing optimization
- Real-time notifications system
- API versioning implementation
- Mobile application integration

## Architecture & Best Practices

### Code Organization
- Strict typing enabled across the application
- PSR-12 coding standards compliance
- Modular component structure
- Comprehensive Git ignore rules for security
- Asset compilation and optimization with Vite

### Security Measures
- CSRF protection
- Secure asset handling
- Environment-based configuration
- Proper session handling
- XSS prevention

### Performance Optimizations
- Lazy loading for Livewire components
- Asset bundling and minification
- Database query optimization
- Caching implementation
- Image optimization

### Development Workflow
- Comprehensive documentation
- Code formatting with Prettier
- Static analysis with Larastan
- Development tools:
  - Laravel Debugbar
  - Laravel Pint
  - PHPUnit testing suite

## Models

The system uses several interconnected models to represent different entities in the business domain:

### Product
The core model for managing saleable items.

Attributes:
- name
- description
- slug (unique, auto-generated)
- price
- category_id
- image
- is_composable
- is_available
- is_featured
- stock

Key Features:
- Automatic slug generation
- Stock management with threshold alerts
- Price formatting with MAD currency
- Status tracking (In Stock, Low Stock, Out of Stock)
- Expiry date monitoring

Relationships:
- Belongs to Category
- Belongs to many Ingredients (with pivot)
- Has many OrderItems
- Has many InventoryAlerts
- Belongs to many Composables

### Category
Organizes products into logical groups.

Attributes:
- name
- slug (unique, auto-generated)
- description
- is_active

Key Features:
- Automatic slug generation
- Product count tracking

Relationships:
- Has many Products

### Ingredient
Manages raw materials and components used in products.

Attributes:
- name
- type (fruit, liquid, ice)
- unit (default: g)
- conversion_rate
- stock
- expiry_date
- supplier_info (JSON)
- instructions (JSON)

Key Features:
- Stock management with negative value prevention
- Expiry date monitoring
- Low stock notifications
- Stock level checks and updates

Relationships:
- Belongs to many Products (with pivot)
- Belongs to many Composables

### Order
Manages customer purchases and transactions.

Attributes:
- customer_name
- customer_phone
- total_amount
- status (using OrderStatus enum)

Key Features:
- Automatic total calculation
- Inventory update management
- Soft deletes support

Relationships:
- Has many OrderItems
- Has many OrderHistories

### OrderItem
Represents individual items within an order.

Attributes:
- order_id
- name
- quantity
- price
- details (JSON)

Key Features:
- Subtotal calculation
- Quantity management
- JSON details storage

Relationships:
- Belongs to Order
- Belongs to Product
- Belongs to Composable

### Composable
the goal is composable ingredients or products , like composing a juice based on fruits, this will help us Manages customizable combinations.

Attributes:
- name
- description
- price

Key Features:
- Soft deletes support
- Price formatting
- Total price calculation

Relationships:
- Belongs to many Products
- Belongs to many Ingredients

### InventoryAlert
Tracks inventory-related notifications.

Attributes:
- product_id
- message
- is_resolved

Key Features:
- Resolution tracking
- Product-specific alerts

Relationships:
- Belongs to Product

### ProductIngredient (Pivot)
Manages the relationship between products and ingredients.

Attributes:
- product_id
- ingredient_id
- stock

Key Features:
- Stock management for ingredient quantities in products

## Key Features

1. **Dynamic Product Composition**: Customers can create custom juices, coffees, and dried fruit mixes.
2. **Real-time Inventory Management**: Stock levels are updated in real-time as orders are placed.
3. **Low Stock Alerts**: The system automatically creates alerts when product stock falls below a threshold.
4. **Theme Customization**: The UI supports multiple themes that can be changed dynamically.
5. **Order Processing**: From creation to fulfillment, the system manages the entire order lifecycle.

## Key Optimizations

1. **Streamlined Order Processing**
   - Implement a single-page order form for quick order entry
   - Automate order status updates based on predefined triggers
   - Integrate real-time stock checks during order placement

2. **Enhanced Inventory Management**
   - Implement automatic reorder points for ingredients and products
   - Use predictive analytics to forecast inventory needs based on historical data
   - Introduce batch tracking for perishable items
   - Implement just-in-time (JIT) inventory management for fresh ingredients

3. **Efficient Product Management**
   - Create a dynamic recipe builder for easy product creation and modification
   - Implement automatic cost calculation based on ingredient prices
   - Add a quick-edit feature for updating multiple products simultaneously
   - Introduce seasonal menu planning based on ingredient availability

4. **Automated Reporting and Analytics**
   - Generate daily sales and inventory reports automatically
   - Implement real-time dashboards for key performance indicators
   - Create automated alerts for critical business metrics (e.g., low stock, high sales)
   - Develop trend analysis for product popularity and seasonal variations

5. **Optimized User Interface**
   - Develop role-based dashboards for different user types (e.g., cashier, manager, admin)
   - Implement a responsive design for seamless use across devices
   - Create intuitive shortcuts for frequently used functions
   - Implement a voice-controlled interface for hands-free operation in the kitchen


## Implementation Focus

To implement these optimizations while maintaining the existing structure:

1. **Enhance Existing Models**:
   - Add fields to `Product` for reorder points and average sales
   - Extend `Ingredient` with supplier information and lead times
   - Add loyalty points and preferences to the `Customer` model
   - Implement methods in `Order` for automatic status updates

2. **Optimize Livewire Components**:
   - Refactor `OrderManagement` to include batch processing and real-time stock checks
   - Enhance `InventoryDashboard` with predictive analytics and JIT features
   - Upgrade `RecipeManagement` to include cost calculations and quick-edit functionality
   - Create a new `CustomerInsights` component for personalized recommendations

3. **Improve Data Processing**:
   - Implement caching strategies for frequently accessed data
   - Use Laravel's queues for processing reports and analytics in the background
   - Optimize database queries for faster data retrieval

4. **Automate Workflows**:
   - Use Laravel's task scheduling for automated reporting and inventory checks
   - Implement event-driven architecture for real-time updates across the system
   - Create automated workflows for order fulfillment and supplier communications

5. **Improve Data Visualization**:
   - Integrate charting libraries for dynamic, interactive reports
   - Implement heat maps for visualizing sales trends and popular products
   - Create customizable dashboards for different user roles
