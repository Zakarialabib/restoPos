# RestoPos Route Testing Suite

This comprehensive testing suite provides automated browser testing for all routes in the RestoPos system using Laravel Dusk. It generates screenshots and detailed reports to help with documentation and issue identification.

## Overview

The testing suite covers:
- **Public Routes**: Customer-facing pages and functionality
- **Admin Routes**: Administrative interface and management tools
- **TV Display Routes**: Digital signage and menu display
- **Installation Routes**: System setup and configuration

## Quick Start

### Windows Users
```bash
# Simply run the batch file
run-tests.bat
```

### Manual Execution
```bash
# Run the PHP test runner
php run-route-tests.php

# Or run individual Dusk tests
php artisan dusk tests/Browser/PublicRoutesTest.php
php artisan dusk tests/Browser/AdminDashboardTest.php
```

## Test Files Structure

```
tests/Browser/
├── PublicRoutesTest.php              # Public routes testing
├── AdminDashboardTest.php            # Admin dashboard & POS
├── AdminProductManagementTest.php    # Product management
├── AdminInventoryTest.php            # Inventory management
├── AdminKitchenTest.php              # Kitchen management
├── AdminFinanceOrderTest.php         # Finance & orders
├── AdminManagementSettingsTest.php   # Management & settings
├── ComprehensiveRouteTest.php        # All routes comprehensive test
└── screenshots/                      # Generated screenshots
```

## Documentation Files

```
docs/testing/
├── README.md                         # This file
├── route-testing-report.md           # Detailed route documentation
├── test-execution-report.md          # Generated after test runs
└── test-execution-results.json       # Machine-readable results
```

## Prerequisites

### System Requirements
- PHP 8.1+
- Laravel 10+
- Chrome/Chromium browser
- ChromeDriver (automatically managed by Dusk)

### Laravel Dusk Setup
```bash
# Install Dusk if not already installed
composer require --dev laravel/dusk

# Install Dusk
php artisan dusk:install

# Create test database
php artisan migrate --env=testing
```

### Environment Configuration
Ensure your `.env.dusk.local` file is configured:
```env
APP_URL=http://localhost:8000
DB_CONNECTION=sqlite
DB_DATABASE=:memory:
```

## Running Tests

### Option 1: Automated Test Runner (Recommended)
```bash
# Windows
run-tests.bat

# Linux/Mac
php run-route-tests.php
```

### Option 2: Individual Test Suites
```bash
# Test public routes
php artisan dusk tests/Browser/PublicRoutesTest.php

# Test admin dashboard
php artisan dusk tests/Browser/AdminDashboardTest.php

# Test all admin routes
php artisan dusk tests/Browser/Admin*

# Run comprehensive test
php artisan dusk tests/Browser/ComprehensiveRouteTest.php
```

### Option 3: All Dusk Tests
```bash
# Run all browser tests
php artisan dusk

# Run with browser visible (for debugging)
php artisan dusk --browse
```

## Test Features

### Screenshot Generation
- Automatic screenshot capture for each route
- Error screenshots for failed tests
- Organized naming convention for easy identification
- Screenshots saved to `tests/Browser/screenshots/`

### Error Handling
- Graceful error handling for missing routes
- Detailed error logging and reporting
- Continuation of testing even if individual routes fail
- Error screenshots for debugging

### Authentication Testing
- Automatic admin user creation for protected routes
- Role-based access testing
- Session management verification

### Comprehensive Reporting
- Detailed markdown reports
- JSON results for programmatic access
- Execution time tracking
- Success/failure statistics

## Understanding Test Results

### Screenshot Naming Convention
```
public_landing_page.png           # Public route screenshots
admin_dashboard.png               # Admin route screenshots
comprehensive_admin_*.png         # Comprehensive test screenshots
error_admin_*.png                 # Error screenshots
```

### Report Files
- **route-testing-report.md**: Static documentation with route details
- **test-execution-report.md**: Generated report with actual test results
- **test-execution-results.json**: Machine-readable test results

## Common Issues & Solutions

### 1. ChromeDriver Issues
```bash
# Update ChromeDriver
php artisan dusk:chrome-driver

# Use specific version
php artisan dusk:chrome-driver 91
```

### 2. Authentication Failures
- Ensure user factories are properly configured
- Check admin role assignment in tests
- Verify authentication middleware is working

### 3. Empty State Displays
- Create seed data for better visual testing
- Add test data factories
- Implement proper empty state handling

### 4. Route Not Found Errors
- Verify route definitions in `web.php` and `admin.php`
- Check middleware configuration
- Ensure Livewire components exist

### 5. Timeout Issues
```bash
# Increase wait times in tests
$browser->waitFor('body', 15); // Increase from 10 to 15 seconds
```

## Customization

### Adding New Routes
1. Add route to appropriate test file
2. Update `route-testing-report.md`
3. Add to comprehensive test if needed

### Modifying Test Behavior
```php
// Increase wait time
$browser->waitFor('body', 15);

// Add custom assertions
$browser->assertSee('Expected Text');

// Custom screenshot names
$browser->screenshot('custom_screenshot_name');
```

### Environment-Specific Testing
```php
// Test different environments
if (app()->environment('testing')) {
    // Testing-specific behavior
}
```

## Best Practices

### 1. Test Data Management
- Use factories for consistent test data
- Clean up test data after each test
- Use database transactions when possible

### 2. Screenshot Organization
- Use descriptive screenshot names
- Organize by feature/module
- Include error screenshots for debugging

### 3. Test Maintenance
- Update tests when routes change
- Keep test documentation current
- Regular test execution to catch regressions

### 4. Performance Considerations
- Use appropriate wait times
- Minimize unnecessary page loads
- Optimize test data creation

## Continuous Integration

### GitHub Actions Example
```yaml
name: Dusk Tests
on: [push, pull_request]
jobs:
  dusk-tests:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: 8.1
      - name: Install dependencies
        run: composer install
      - name: Run Dusk tests
        run: php artisan dusk
```

## Troubleshooting

### Debug Mode
```bash
# Run with visible browser
php artisan dusk --browse

# Enable debug output
php artisan dusk --debug
```

### Log Files
- Laravel logs: `storage/logs/laravel.log`
- Dusk logs: `tests/Browser/console/`
- Chrome logs: Check browser console

### Common Commands
```bash
# Clear test cache
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Reset test database
php artisan migrate:fresh --env=testing
php artisan db:seed --env=testing
```

## Contributing

When adding new tests:
1. Follow existing naming conventions
2. Add proper error handling
3. Include screenshot capture
4. Update documentation
5. Test in clean environment

## Support

For issues with the testing suite:
1. Check the generated error screenshots
2. Review the test execution report
3. Verify environment configuration
4. Check Laravel and Dusk documentation

---

*This testing suite is designed to provide comprehensive coverage of all RestoPos routes with detailed reporting and screenshot documentation.*