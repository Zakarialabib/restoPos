# 🍽️ Premium Fruit Bar Management System

A modern, comprehensive system built using the **TALL stack (Tailwind CSS, Alpine.js, Livewire, Laravel)**. This application streamlines the operations of a premium fruit bar, specializing in fresh juices, fruit salads, and fruit-based desserts.

Key features include **dynamic product composition (build-your-own juices/items) powered by Livewire**, real-time inventory management linked to ingredients, order processing, and essential analytics for cost management and quality control. Designed initially for a small team, it emphasizes quality and customization.

---

## 📖 Table of Contents

1. [Technical Stack](#-technical-stack)
2. [System Architecture](#-system-architecture)
   - [Admin Dashboard (Livewire + Alpine.js)](#1-admin-dashboard-)
   - [Customer Experience (Livewire)](#2-customer-experience-)
   - [Composable Product Feature](#3-composable-product-feature)
   - [Order Management](#4-order-management-)
   - [Analytics & Reporting](#5-analytics--reporting-)
3. [Core Models Overview](#-core-models-overview)
4. [Product Lines & Strategies](#-product-lines--strategies)
5. [Development Setup](#-development-setup)
6. [Requirements](#-requirements)
7. [Contributing](#-contributing)
8. [Acknowledgments](#-acknowledgments)
9. [Support](#-support)
10. [License](#-license)

---

## 🚀 Technical Stack

- **Backend Framework**: Laravel (PHP 8.1+)
- **Database**: MySQL 8.0+
- **Frontend & UI (Admin)**: Livewire & Alpine.js for dynamic interfaces.
- **Frontend & UI (Customer)**: Livewire for interactive menu display and product composition.
- **Styling**: Tailwind CSS (Utility-first)
- **Localization**: Built-in support (Arabic, French, English via JSON files).

---

## 🔍 System Architecture

The application leverages Livewire extensively for both the customer-facing interactions and the administrative backend, creating reactive interfaces with minimal JavaScript.

### Testing

The application uses Laravel Dusk for browser testing. Test screenshots are stored in the `tests/Browser/screenshots` directory. See the `tests/LIVEWIRE_TESTING.md` file for detailed instructions on running tests.

### 1. Admin Dashboard 🛠️ (Livewire + Alpine.js)

A comprehensive backend powered by Livewire components, enhanced with Alpine.js for minor frontend interactions.

#### Key Functionalities
- Real-time dashboard (`Admin\\Dashboard`).
- Management Modules (Livewire Components):
    - `ProductManagement`, `CategoryManagement`, `IngredientManagement`, `RecipeManagement`
    - `OrderManagement`, `KitchenManagement`, `KitchenDisplay`, `CashRegister\\Index`
    - `InventoryManagement`, `PurchaseManagement`, `SupplierManagement`
    - `ExpenseManagement`, `ExpenseCategories\\Index`, `WasteManagement`
    - `ComposableConfigurationManager` (Configures rules for custom products)
    - Settings, Languages, etc.
- Ingredient tracking, stock monitoring, and cost analysis.

#### Admin Workflow Example (Product Setup)
1. Define categories (`CategoryManagement`).
2. Configure composable rules per category (`ComposableConfigurationManager`) - e.g., Does it need a base? How many ingredients? What sizes are offered and what's their price multiplier?
3. Add ingredients (`IngredientManagement`), specifying cost, unit, stock, and if usable in compositions (`is_composable`).
4. Add standard products (`ProductManagement`), defining recipes using ingredients, setting price/cost. Mark products as `is_composable=true` if they serve as starting points for customization.

---

### 2. Customer Experience 🍽️ (Livewire)

The customer interface relies on Livewire for dynamic content loading and interactions.

#### Key Features & Components
- **Menu Display**: `MenuIndex` component shows available products (standard and composable starting points).
- **TV Display**: `TvMenu` offers a dedicated view for screen displays.
- **Product Composition**: `ComposableProductIndex` handles the interactive "build-your-own" process (see details below).
- **Shopping Cart**: `CartDrawer` manages the user's cart in real-time.
- **Order Tracking**: `OrderTrack` allows customers to view their order status.
- Multi-language support via Laravel localization.

---

### 3. Composable Product Feature

This core feature allows customers to build custom items (e.g., juices) interactively.

#### Key Components & Flow:
1.  **Initiation**: User selects a composable product type (e.g., "Juices") from the menu (`MenuIndex`). This loads the `ComposableProductIndex` Livewire component via the `/compose/{productType}` route.
2.  **Configuration Loading**: `ComposableProductIndex` fetches the `Category` and its linked `ComposableConfiguration` to determine the rules (required base/sugar/size, min/max ingredients, available sizes with price multipliers).
3.  **Interactive Selection**: The user selects ingredients, base, sugar, size, and addons using the UI managed by `ComposableProductIndex`. Available options (ingredients, bases) are filtered based on availability and the `is_composable` flag.
4.  **Real-time Price Calculation**: With each selection change, `ComposableProductIndex` calls the `ComposablePriceCalculator` service. This service calculates the price based on:
    *   Sum of selected ingredient costs (fetched from `Ingredient` model, potentially using price history or current price).
    *   Fixed costs for selected base and sugar (currently hardcoded in the service).
    *   Fixed cost per addon (currently hardcoded).
    *   Application of a size multiplier (from hardcoded values in the service, potentially diverging from `ComposableConfiguration`).
5.  **Add to Cart**: `ComposableProductIndex` validates the selection, generates a name, bundles the selected options (ingredients, base, size, etc.) and the final price into an array, and adds it to the session cart (`CartDrawer` updates).
6.  **Order Placement**: When the order is placed:
    *   An `Order` record is created.
    *   An `OrderItem` record is created for the custom item. The `OrderItem` stores:
        *   The final calculated `price`.
        *   The calculated `cost`.
        *   The detailed user selections (ingredients, base, size etc.) in a `details` or `composition` field (likely JSON).

---

### 4. Order Management 🛒

#### Core Functionalities
- `Order` model tracks overall order details, status (`OrderStatus` enum), payment (`PaymentStatus`, `PaymentMethod` enums), totals, customer info.
- `OrderItem` model stores details of each item within an order, including the specific composition of custom-built products.
- Real-time updates possible via Livewire components (`Admin\\OrderManagement`, `KitchenDisplay`).
- Inventory deduction occurs during order placement (needs verification for accuracy, especially for composed items).
- Order cancellation includes logic placeholders for inventory restoration.

---

### 5. Analytics & Reporting 📊

- **Sales:** Track performance via `OrderItem` static methods (`getTopPerformers`, `getDailyRevenue`). Analyze peak hours, popular products, revenue trends.
- **Inventory:** `Ingredient` model includes methods for analyzing usage, wastage, turnover, and efficiency, likely relying on `StockLog` data (`calculateWastage`, `calculateTurnoverRate`, etc.). Services like `IngredientAnalyticsService` (mentioned previously) likely leverage these.

---

## 🏛️ Core Models Overview

- **`Product`**: Represents sellable items. Can be standard (fixed recipe using `ingredients` relationship) or a starting point for customization (`is_composable=true`). Includes inventory (`HasInventory` trait) and pricing (`HasPricing` trait).
- **`Ingredient`**: Raw materials. Stores cost, stock (`HasInventory`), unit, expiry (`HasExpiry`), availability, and `is_composable` flag. Central to inventory control and costing.
- **`Category`**: Groups products and ingredients. Links to `ComposableConfiguration`.
- **`ComposableConfiguration`**: Defines the rules for building a composable product within a specific category (min/max ingredients, required steps, available sizes with price multipliers).
- **`Order`**: Header information for a customer order, including status, totals, and customer details.
- **`OrderItem`**: Line item within an order. Stores quantity, final price/cost. For composed items, stores the user's specific choices in a `details`/`composition` field.
- **`User`**: Standard user model for authentication.
- **`StockLog`**: (Inferred) Tracks adjustments to ingredient stock levels (usage, deliveries, waste). Used by `Ingredient` analytics methods.

*(Other models exist for Suppliers, Purchases, Expenses, Cash Registers, Recipes, etc.)*

---

## 🍹 Product Lines & Strategies

This system supports various product lines:

- **Fresh Juices**: Standard single-fruit, signature blends, and the "Build Your Own" composable feature. Pricing considers ingredients and size.
- **Fruit Salads & Bowls**: Standard and potentially customizable using the composable framework extension.
- **Artisanal Desserts**: Managed as standard products.
- **Smoothies**: Can be managed as standard products or adapted using the composable feature.
- **Dried Fruits & Nuts**: Managed as standard products or ingredients/addons.

---

## 🛠️ Development Setup

1.  Clone the repository: `git clone https://github.com/zakarialabib/restopos.git`
2.  Navigate to directory: `cd restopos`
3.  Install PHP dependencies: `composer install`
4.  Install frontend dependencies: `npm install`
5.  Setup environment: Copy `.env.example` to `.env` and configure database, app URL, etc.
6.  Generate app key: `php artisan key:generate`
7.  Run database migrations and seeders: `php artisan migrate --seed`
8.  Compile frontend assets: `npm run dev` (for development) or `npm run build` (for production)
9.  Start the development server: `php artisan serve`
10. Access the application in your browser (usually http://127.0.0.1:8000).

---

## 📋 Requirements

- PHP 8.1+
- Node.js 16+ / npm
- MySQL 8.0+
- Composer 2.0+

---

## 🤝 Contributing

Contributions are welcome! Please fork the repository, create a feature branch (`git checkout -b feature/YourFeature`), commit your changes, and submit a pull request. Adhere to PSR-12 coding standards.

---

## 🙏 Acknowledgments

Special thanks to the TALL stack community, Laravel ecosystem contributors, and open-source developers worldwide.

---

## 📞 Support

For issues or inquiries, contact [zakarialabib@gmail.com](mailto:zakarialabib@gmail.com).

---

## 🔒 License

This project is licensed under the [MIT License](LICENSE).
