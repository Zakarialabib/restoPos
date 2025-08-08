# Restaurant Theme System Documentation

## Overview

The Restaurant Theme System provides a centralized, consistent approach to managing themes and views across all Livewire components in the RestoPos application. This system eliminates redundant `availableViews` arrays and provides marketing psychology-based theme configurations.

## Architecture

### Core Components

1. **ThemeService** - Consolidated theme management service
2. **ThemeConfigurationService** - TV menu configuration with theme integration
3. **Widget Classes** - Updated to use centralized theme service

### Theme Structure

Each theme includes:
- **Name & Description** - Human-readable theme information
- **Marketing Psychology** - Color psychology and target audience insights
- **Colors** - Recommended color palette
- **Typography** - Font recommendations
- **Target Audience** - Ideal customer demographics
- **Target Restaurants** - Restaurant types that benefit from this theme

## Available Themes

### 1. Classic Theme
- **Psychology**: Warmth, tradition, comfort, family-oriented
- **Colors**: Warm browns, cream, gold accents
- **Typography**: Serif fonts
- **Target**: Family restaurants, traditional cuisine, comfort food
- **Audience**: Families, traditional diners, comfort seekers

### 2. Modern Theme
- **Psychology**: Innovation, efficiency, contemporary appeal
- **Colors**: Indigo, white, gray accents
- **Typography**: Sans-serif fonts
- **Target**: Fast-casual, urban dining, tech-savvy establishments
- **Audience**: Young professionals, tech-savvy diners, urban millennials

### 3. Minimal Theme
- **Psychology**: Sophistication, cleanliness, premium quality
- **Colors**: White, light gray, subtle accents
- **Typography**: Clean sans-serif
- **Target**: Cafes, health-focused, scandinavian-style
- **Audience**: Health-conscious diners, minimalists, wellness enthusiasts

### 4. Vintage Theme
- **Psychology**: Nostalgia, authenticity, craftsmanship
- **Colors**: Amber, stone, sepia tones
- **Typography**: Serif and script fonts
- **Target**: Bistros, artisanal, craft establishments
- **Audience**: Artisan food lovers, nostalgic diners, craft enthusiasts

### 5. Default Theme
- **Psychology**: Versatility, broad appeal, accessibility
- **Colors**: Neutral palette, flexible theming
- **Typography**: Readable fonts
- **Target**: General purpose, chain restaurants, versatile establishments
- **Audience**: General public, diverse demographics, broad appeal

## Widget Types

### Supported Widget Types
- `menu-showcase` - Menu display widgets
- `retail-grid` - Product grid layouts
- `featured-product` - Featured product displays
- `retail-slide` - Sliding product presentations

### View Path Structure
```
resources/views/livewire/themes/
├── classic/
│   ├── menu/
│   └── retail/
├── modern/
│   ├── menu/
│   └── retail/
├── minimal/
│   ├── menu/
│   └── retail/
├── vintage/
│   ├── menu/
│   └── retail/
└── default/
    ├── menu/
    └── retail/
```

## Usage Examples

### Getting Available Views for a Widget
```php
// In a Livewire component
public function getAvailableViews(): array
{
    return ThemeService::getViewsForWidget('menu-showcase');
}
```

### Getting Theme Marketing Psychology
```php
$psychology = ThemeService::getMarketingPsychology('classic');
// Returns: ['psychology' => '...', 'colors' => [...], 'typography' => '...', 'target_audience' => '...']
```

### Getting Recommended Themes for Restaurant Type
```php
$themes = ThemeService::getRecommendedThemes('fast-food');
// Returns: ['modern', 'minimal']
```

### Validating Theme and Widget Combinations
```php
$isValid = ThemeConfigurationService::hasViewForWidget('menu-showcase', 'classic');
// Returns: true/false
```

## Widget Integration

### Before (Redundant)
```php
class RetailSlide extends Component
{
    protected $availableViews = [
        'modern-grid' => [
            'name' => 'Modern Grid',
            'view_path' => 'livewire.themes.modern.retail.modern-grid'
        ],
        // ... more hardcoded views
    ];
}
```

### After (Centralized)
```php
class RetailSlide extends Component
{
    public function getAvailableViews(): array
    {
        return ThemeService::getViewsForWidget('retail-slide');
    }
    
    public function render()
    {
        $viewPath = ThemeService::getViewPath('retail-slide', $this->activeView);
        return view($viewPath ?: 'livewire.themes.default.retail.retail-slide', [
            'settings' => $this->settings
        ]);
    }
}
```

## Benefits

1. **Consistency** - All widgets use the same theme definitions
2. **Maintainability** - Single source of truth for theme configurations
3. **Marketing Psychology** - Built-in guidance for restaurant branding
4. **Scalability** - Easy to add new themes and widget types
5. **Validation** - Built-in checks for theme and widget compatibility
6. **Flexibility** - Fallback mechanisms for missing views

## Testing

The system includes comprehensive tests in `tests/Feature/ThemeSystemTest.php` that verify:
- Theme retrieval and structure
- Widget view availability
- Marketing psychology data
- Restaurant type recommendations
- Service integration
- Validation methods

## Migration Guide

To migrate existing widgets to the new system:

1. Remove hardcoded `availableViews` arrays
2. Add `ThemeService` import
3. Implement `getAvailableViews()` method
4. Update `render()` method to use `ThemeService::getViewPath()`
5. Add fallback view handling
6. Test widget functionality

## Future Enhancements

- Dynamic theme customization
- Custom theme builder interface
- Theme preview functionality