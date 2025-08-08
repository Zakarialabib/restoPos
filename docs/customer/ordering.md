# Customer Ordering Portal

The RestoPos Customer Ordering Portal provides a seamless mobile-first experience for customers to browse menus and place orders via QR code access.

## Overview

The customer ordering system is designed for:
- **QR Code Access** - Instant menu access via smartphone scan
- **Mobile-First Design** - Optimized for smartphones and tablets
- **Contactless Ordering** - Reduce physical contact and wait times
- **Real-time Updates** - Live menu availability and pricing
- **Multi-language Support** - Serve diverse customer base

## QR Code Integration

### QR Code Generation

RestoPos automatically generates QR codes for:
- **Table-specific ordering** - Each table gets a unique QR code
- **General menu access** - Single QR code for takeaway/delivery
- **Location-specific menus** - Different QR codes per restaurant location

### QR Code URL Structure

```
https://your-restopos-domain.com/menu?table=TABLE_ID&location=LOCATION_ID
```

**URL Parameters:**
- `table` - Table identifier for dine-in orders
- `location` - Restaurant location identifier
- `lang` - Language preference (en, fr, ar)
- `theme` - Visual theme selection
- `promo` - Promotional code application

**Example URLs:**
```
# Table 5 at main location
https://restopos.com/menu?table=5&location=main

# Takeaway orders
https://restopos.com/menu?table=takeaway&location=main

# French language with promo
https://restopos.com/menu?table=3&location=main&lang=fr&promo=WELCOME10
```

### QR Code Placement

#### Dine-in Service
- **Table tents** - Prominent placement on each table
- **Table stickers** - Permanent QR codes on table surfaces
- **Menu cards** - QR codes on physical menu covers
- **Wall displays** - Large QR codes for shared tables

#### Takeaway/Delivery
- **Entrance displays** - QR codes at restaurant entrance
- **Window stickers** - Visible from outside
- **Social media** - Digital QR codes for online sharing
- **Print materials** - Business cards, flyers, receipts

## Mobile User Experience

### Customer Journey

1. **QR Code Scan** - Customer scans QR code with smartphone
2. **Menu Loading** - Instant access to digital menu
3. **Browse Categories** - Navigate through food categories
4. **Item Selection** - View details, customize options
5. **Cart Management** - Add, modify, remove items
6. **Order Placement** - Confirm order and preferences
7. **Payment Processing** - Secure payment handling
8. **Order Tracking** - Real-time order status updates

### Mobile Interface Features

#### Responsive Design
- **Touch-optimized** - Large buttons and easy navigation
- **Swipe gestures** - Natural mobile interactions
- **Zoom support** - Pinch to zoom on images
- **Offline capability** - Basic functionality without internet

#### Visual Elements
- **High-quality images** - Appetizing food photography
- **Clear typography** - Easy-to-read fonts and sizes
- **Intuitive icons** - Universal symbols for actions
- **Loading indicators** - Clear feedback during operations

## Menu Navigation

### Category Structure

```
Menu Categories:
├── Appetizers
├── Main Courses
│   ├── Meat Dishes
│   ├── Seafood
│   ├── Vegetarian
│   └── Vegan Options
├── Composable Items
│   ├── Bowls
│   ├── Salads
│   ├── Juices
│   └── Custom Combinations
├── Desserts
├── Beverages
│   ├── Hot Drinks
│   ├── Cold Drinks
│   └── Alcoholic (if applicable)
└── Specials
```

### Search and Filtering

#### Search Functionality
- **Text search** - Search by item name or ingredient
- **Voice search** - Speak to search (mobile browsers)
- **Barcode scan** - Scan product barcodes (if applicable)

#### Filter Options
- **Dietary restrictions** - Vegetarian, vegan, gluten-free
- **Allergen information** - Filter by allergen presence
- **Price range** - Set minimum and maximum price
- **Preparation time** - Filter by cooking time
- **Spice level** - Filter by heat level
- **Availability** - Show only available items

### Item Details

#### Information Display
- **Item name and description**
- **High-resolution images**
- **Pricing information**
- **Nutritional data**
- **Allergen warnings**
- **Preparation time**
- **Customization options**
- **Customer reviews/ratings**

#### Customization Options
- **Size selection** - Small, medium, large portions
- **Ingredient modifications** - Add, remove, substitute
- **Cooking preferences** - Rare, medium, well-done
- **Spice level** - Mild, medium, hot, extra hot
- **Special instructions** - Custom preparation notes

## Composable Products System

### Overview
Composable products allow customers to build custom items within defined parameters:

- **Bowls** - Choose base, protein, vegetables, sauce
- **Salads** - Select greens, toppings, dressing
- **Juices** - Combine fruits, vegetables, supplements
- **Pizza** - Choose size, crust, toppings
- **Sandwiches** - Select bread, fillings, condiments

### Configuration Structure

```php
// Example: Custom Bowl Configuration
'bowl' => [
    'base' => [
        'required' => true,
        'max_selections' => 1,
        'options' => ['rice', 'quinoa', 'noodles', 'lettuce']
    ],
    'protein' => [
        'required' => true,
        'max_selections' => 2,
        'options' => ['chicken', 'beef', 'tofu', 'fish']
    ],
    'vegetables' => [
        'required' => false,
        'max_selections' => 5,
        'options' => ['broccoli', 'carrots', 'peppers', 'onions']
    ],
    'sauce' => [
        'required' => false,
        'max_selections' => 2,
        'options' => ['teriyaki', 'spicy', 'garlic', 'herb']
    ]
]
```

### Pricing Logic

#### Base Pricing
- **Fixed base price** - Starting price for the composable item
- **Component pricing** - Additional cost per selected ingredient
- **Premium ingredients** - Higher cost for premium options
- **Size multipliers** - Price adjustments based on portion size

#### Dynamic Pricing Display
```javascript
// Real-time price calculation
function calculatePrice(selections) {
    let basePrice = composableConfig.base_price;
    let additionalCost = 0;
    
    selections.forEach(selection => {
        additionalCost += selection.price || 0;
    });
    
    return basePrice + additionalCost;
}
```

### User Interface

#### Step-by-Step Builder
1. **Category Selection** - Choose composable item type
2. **Base Selection** - Select foundation ingredient
3. **Component Selection** - Add proteins, vegetables, etc.
4. **Customization** - Adjust quantities and preferences
5. **Review** - Confirm selections and pricing
6. **Add to Cart** - Finalize the custom item

#### Visual Feedback
- **Progress indicator** - Show completion status
- **Live preview** - Visual representation of selections
- **Price updates** - Real-time price calculation
- **Validation messages** - Guide user through requirements

## Cart Management

### Cart Functionality

#### Item Management
- **Add items** - Single tap to add to cart
- **Modify quantities** - Increase/decrease item counts
- **Remove items** - Swipe to delete or tap remove
- **Save for later** - Wishlist functionality
- **Duplicate items** - Quick reorder of previous selections

#### Cart Persistence
- **Session storage** - Maintain cart during browsing
- **Local storage** - Persist cart between visits
- **Account sync** - Sync cart across devices (logged-in users)

### Order Customization

#### Special Instructions
- **Item-specific notes** - Custom preparation requests
- **Order-wide notes** - General instructions for kitchen
- **Delivery instructions** - Special delivery requirements
- **Timing requests** - Preferred preparation/delivery time

#### Dietary Preferences
- **Allergen alerts** - Automatic warnings for allergens
- **Dietary tags** - Mark items as vegetarian, vegan, etc.
- **Substitution suggestions** - Alternative ingredients

## Payment Integration

### Payment Methods

#### Supported Options
- **Credit/Debit Cards** - Visa, MasterCard, American Express
- **Digital Wallets** - Apple Pay, Google Pay, Samsung Pay
- **Bank Transfers** - Direct bank account payments
- **Cash on Delivery** - Pay upon order receipt
- **Loyalty Points** - Redeem accumulated points

#### Security Features
- **PCI DSS Compliance** - Secure payment processing
- **SSL Encryption** - Encrypted data transmission
- **Tokenization** - Secure card data storage
- **3D Secure** - Additional authentication layer

### Checkout Process

1. **Cart Review** - Final order confirmation
2. **Customer Information** - Contact and delivery details
3. **Payment Method** - Select preferred payment option
4. **Order Summary** - Final price and item review
5. **Payment Processing** - Secure transaction handling
6. **Confirmation** - Order receipt and tracking information

## Real-time Features

### Live Updates

#### Menu Availability
- **Stock levels** - Real-time inventory updates
- **Item availability** - Automatic hiding of unavailable items
- **Price changes** - Dynamic pricing updates
- **New items** - Instant addition of new menu items

#### Order Status
- **Order confirmation** - Immediate order acknowledgment
- **Preparation status** - Kitchen progress updates
- **Ready notification** - Order completion alerts
- **Delivery tracking** - Real-time delivery status

### WebSocket Integration

```javascript
// Real-time order updates
window.Echo.private(`order.${orderId}`)
    .listen('OrderStatusUpdated', (e) => {
        updateOrderStatus(e.status);
        showNotification(e.message);
    });

// Menu availability updates
window.Echo.channel('menu-updates')
    .listen('MenuItemUpdated', (e) => {
        updateItemAvailability(e.item_id, e.available);
    });
```

## Multi-language Support

### Language Detection
- **Browser language** - Automatic detection from browser settings
- **URL parameter** - Manual language selection via URL
- **User preference** - Saved language choice
- **Location-based** - Default language per restaurant location

### Supported Languages
- **English** - Default language
- **French** - Full translation support
- **Arabic** - RTL (Right-to-Left) layout support
- **Additional languages** - Configurable via admin panel

### Translation Features
- **Menu items** - Item names and descriptions
- **Categories** - Menu category translations
- **Interface elements** - Buttons, labels, messages
- **Error messages** - Localized error handling
- **Currency formatting** - Locale-appropriate price display

## Accessibility Features

### WCAG Compliance
- **Keyboard navigation** - Full keyboard accessibility
- **Screen reader support** - ARIA labels and descriptions
- **High contrast mode** - Enhanced visibility options
- **Font size adjustment** - Scalable text display
- **Voice commands** - Voice-controlled navigation

### Mobile Accessibility
- **Touch target size** - Minimum 44px touch targets
- **Gesture alternatives** - Alternative input methods
- **Haptic feedback** - Tactile response for actions
- **Audio cues** - Sound feedback for interactions

## Performance Optimization

### Loading Performance
- **Image optimization** - WebP format with fallbacks
- **Lazy loading** - Load images as needed
- **Caching strategy** - Browser and CDN caching
- **Minification** - Compressed CSS and JavaScript
- **Progressive loading** - Prioritize above-the-fold content

### Offline Capability
- **Service worker** - Cache critical resources
- **Offline menu** - Basic menu browsing without internet
- **Queue orders** - Store orders for later submission
- **Sync on reconnect** - Automatic data synchronization

## Analytics and Tracking

### Customer Behavior
- **Menu browsing patterns** - Popular categories and items
- **Cart abandonment** - Incomplete order analysis
- **Conversion rates** - Order completion statistics
- **Time spent** - Average session duration
- **Device usage** - Mobile vs desktop statistics

### Order Analytics
- **Popular items** - Best-selling menu items
- **Peak hours** - Busiest ordering times
- **Average order value** - Revenue per order
- **Customer retention** - Repeat order rates
- **Geographic data** - Order location analysis

## Troubleshooting

### Common Issues

#### QR Code Not Working
1. **Check QR code generation** - Verify URL format
2. **Test QR code scanner** - Try different scanner apps
3. **Verify network connectivity** - Check internet connection
4. **Clear browser cache** - Remove cached data

#### Menu Not Loading
1. **Check server status** - Verify RestoPos server availability
2. **Network connectivity** - Test internet connection
3. **Browser compatibility** - Try different browser
4. **JavaScript errors** - Check browser console

#### Payment Issues
1. **Payment gateway status** - Verify payment processor
2. **Card validation** - Check card details
3. **Network timeout** - Retry payment
4. **Browser security** - Disable ad blockers

### Support Resources

- **User Guide**: [Customer Portal Guide](../customer/)
- **FAQ**: [Frequently Asked Questions](../resources/faq.md)
- **Support**: [Contact Support](mailto:support@restopos.com)
- **Community**: [RestoPos Community Forum](https://community.restopos.com)

---

**Next Steps:**
- [Composable Products](./composable.md)
- [Theme Customization](./themes.md)
- [Menu Display System](./menu.md)