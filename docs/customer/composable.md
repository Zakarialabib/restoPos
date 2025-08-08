# Composable Products

Composable products in RestoPos allow customers to create custom menu items by selecting from predefined ingredients and components within structured limits. This system provides flexibility while maintaining operational efficiency.

## Overview

The composable system enables:
- **Custom Bowls** - Build personalized bowl combinations
- **Fresh Juices** - Mix fruits and vegetables for custom drinks
- **Salad Builder** - Create custom salad combinations
- **Pizza Customization** - Choose toppings and crust options
- **Sandwich Builder** - Select bread, fillings, and condiments
- **Smoothie Mixer** - Combine fruits, proteins, and supplements

## System Architecture

### Component Structure

Each composable product consists of:

```php
'composable_item' => [
    'base_price' => 12.99,
    'components' => [
        'component_name' => [
            'required' => true|false,
            'min_selections' => 0,
            'max_selections' => 3,
            'allow_duplicates' => true|false,
            'options' => [
                'option_id' => [
                    'name' => 'Option Name',
                    'price' => 2.50,
                    'available' => true,
                    'allergens' => ['gluten', 'dairy'],
                    'dietary_tags' => ['vegetarian', 'vegan']
                ]
            ]
        ]
    ]
]
```

### Configuration Parameters

#### Component Rules
- **required** - Must customer select from this component?
- **min_selections** - Minimum number of options to select
- **max_selections** - Maximum number of options allowed
- **allow_duplicates** - Can same option be selected multiple times?
- **exclusive** - Only one option can be selected

#### Option Properties
- **name** - Display name for the ingredient
- **price** - Additional cost for this option
- **available** - Real-time availability status
- **allergens** - List of allergens present
- **dietary_tags** - Dietary classification tags
- **image** - Visual representation of ingredient
- **description** - Detailed ingredient information

## Product Types

### Custom Bowls

#### Configuration Example
```php
'custom_bowl' => [
    'base_price' => 14.99,
    'components' => [
        'base' => [
            'required' => true,
            'max_selections' => 1,
            'options' => [
                'brown_rice' => ['name' => 'Brown Rice', 'price' => 0],
                'quinoa' => ['name' => 'Quinoa', 'price' => 1.50],
                'cauliflower_rice' => ['name' => 'Cauliflower Rice', 'price' => 2.00],
                'mixed_greens' => ['name' => 'Mixed Greens', 'price' => 0.50]
            ]
        ],
        'protein' => [
            'required' => true,
            'max_selections' => 2,
            'options' => [
                'grilled_chicken' => ['name' => 'Grilled Chicken', 'price' => 3.00],
                'salmon' => ['name' => 'Grilled Salmon', 'price' => 5.00],
                'tofu' => ['name' => 'Marinated Tofu', 'price' => 2.50],
                'black_beans' => ['name' => 'Black Beans', 'price' => 1.50]
            ]
        ],
        'vegetables' => [
            'required' => false,
            'max_selections' => 5,
            'options' => [
                'broccoli' => ['name' => 'Steamed Broccoli', 'price' => 1.00],
                'bell_peppers' => ['name' => 'Bell Peppers', 'price' => 1.00],
                'cherry_tomatoes' => ['name' => 'Cherry Tomatoes', 'price' => 1.50],
                'avocado' => ['name' => 'Fresh Avocado', 'price' => 2.50],
                'corn' => ['name' => 'Sweet Corn', 'price' => 1.00]
            ]
        ],
        'sauce' => [
            'required' => false,
            'max_selections' => 2,
            'options' => [
                'teriyaki' => ['name' => 'Teriyaki Sauce', 'price' => 0.50],
                'tahini' => ['name' => 'Tahini Dressing', 'price' => 0.75],
                'sriracha_mayo' => ['name' => 'Sriracha Mayo', 'price' => 0.50],
                'lemon_herb' => ['name' => 'Lemon Herb Vinaigrette', 'price' => 0.50]
            ]
        ],
        'toppings' => [
            'required' => false,
            'max_selections' => 3,
            'options' => [
                'sesame_seeds' => ['name' => 'Sesame Seeds', 'price' => 0.25],
                'crushed_nuts' => ['name' => 'Crushed Almonds', 'price' => 1.00],
                'feta_cheese' => ['name' => 'Feta Cheese', 'price' => 1.50],
                'crispy_onions' => ['name' => 'Crispy Onions', 'price' => 0.75]
            ]
        ]
    ]
]
```

#### Bowl Building Process
1. **Select Base** - Choose foundation (rice, quinoa, greens)
2. **Add Protein** - Select main protein source(s)
3. **Choose Vegetables** - Add fresh or cooked vegetables
4. **Pick Sauce** - Select dressing or sauce
5. **Add Toppings** - Final garnishes and extras
6. **Review & Customize** - Adjust quantities and special requests

### Fresh Juice Combinations

#### Configuration Example
```php
'fresh_juice' => [
    'base_price' => 8.99,
    'size_options' => [
        'small' => ['multiplier' => 0.8, 'volume' => '12oz'],
        'medium' => ['multiplier' => 1.0, 'volume' => '16oz'],
        'large' => ['multiplier' => 1.3, 'volume' => '20oz']
    ],
    'components' => [
        'primary_fruits' => [
            'required' => true,
            'min_selections' => 1,
            'max_selections' => 3,
            'options' => [
                'apple' => ['name' => 'Fresh Apple', 'price' => 0],
                'orange' => ['name' => 'Fresh Orange', 'price' => 0],
                'pineapple' => ['name' => 'Pineapple', 'price' => 1.00],
                'mango' => ['name' => 'Mango', 'price' => 1.50],
                'berries' => ['name' => 'Mixed Berries', 'price' => 2.00]
            ]
        ],
        'vegetables' => [
            'required' => false,
            'max_selections' => 2,
            'options' => [
                'carrot' => ['name' => 'Fresh Carrot', 'price' => 0.50],
                'celery' => ['name' => 'Celery', 'price' => 0.50],
                'spinach' => ['name' => 'Fresh Spinach', 'price' => 1.00],
                'kale' => ['name' => 'Kale', 'price' => 1.00],
                'cucumber' => ['name' => 'Cucumber', 'price' => 0.50]
            ]
        ],
        'boosters' => [
            'required' => false,
            'max_selections' => 3,
            'options' => [
                'ginger' => ['name' => 'Fresh Ginger', 'price' => 0.75],
                'turmeric' => ['name' => 'Turmeric', 'price' => 1.00],
                'chia_seeds' => ['name' => 'Chia Seeds', 'price' => 1.50],
                'protein_powder' => ['name' => 'Protein Powder', 'price' => 2.50],
                'spirulina' => ['name' => 'Spirulina', 'price' => 2.00]
            ]
        ]
    ]
]
```

#### Juice Creation Process
1. **Choose Size** - Select juice volume (12oz, 16oz, 20oz)
2. **Primary Fruits** - Select main fruit base
3. **Add Vegetables** - Optional vegetable additions
4. **Health Boosters** - Supplements and superfoods
5. **Taste Adjustment** - Sweetness and flavor preferences
6. **Final Review** - Confirm combination and nutritional info

### Salad Builder

#### Configuration Structure
```php
'custom_salad' => [
    'base_price' => 11.99,
    'components' => [
        'greens' => [
            'required' => true,
            'max_selections' => 2,
            'options' => [
                'romaine' => ['name' => 'Romaine Lettuce', 'price' => 0],
                'spinach' => ['name' => 'Baby Spinach', 'price' => 0.50],
                'arugula' => ['name' => 'Arugula', 'price' => 1.00],
                'kale' => ['name' => 'Massaged Kale', 'price' => 1.00],
                'mixed_greens' => ['name' => 'Spring Mix', 'price' => 0.50]
            ]
        ],
        'protein' => [
            'required' => false,
            'max_selections' => 1,
            'options' => [
                'grilled_chicken' => ['name' => 'Grilled Chicken Breast', 'price' => 4.00],
                'salmon' => ['name' => 'Grilled Salmon', 'price' => 6.00],
                'shrimp' => ['name' => 'Grilled Shrimp', 'price' => 5.00],
                'chickpeas' => ['name' => 'Roasted Chickpeas', 'price' => 2.00],
                'hard_boiled_egg' => ['name' => 'Hard-Boiled Egg', 'price' => 1.50]
            ]
        ],
        'vegetables' => [
            'required' => false,
            'max_selections' => 6,
            'options' => [
                'tomatoes' => ['name' => 'Cherry Tomatoes', 'price' => 1.00],
                'cucumber' => ['name' => 'Cucumber', 'price' => 0.75],
                'red_onion' => ['name' => 'Red Onion', 'price' => 0.50],
                'bell_peppers' => ['name' => 'Bell Peppers', 'price' => 1.00],
                'carrots' => ['name' => 'Shredded Carrots', 'price' => 0.75],
                'avocado' => ['name' => 'Fresh Avocado', 'price' => 2.50]
            ]
        ],
        'cheese' => [
            'required' => false,
            'max_selections' => 2,
            'options' => [
                'feta' => ['name' => 'Feta Cheese', 'price' => 1.50],
                'goat_cheese' => ['name' => 'Goat Cheese', 'price' => 2.00],
                'parmesan' => ['name' => 'Parmesan', 'price' => 1.50],
                'cheddar' => ['name' => 'Sharp Cheddar', 'price' => 1.25]
            ]
        ],
        'dressing' => [
            'required' => false,
            'max_selections' => 1,
            'options' => [
                'balsamic' => ['name' => 'Balsamic Vinaigrette', 'price' => 0],
                'caesar' => ['name' => 'Caesar Dressing', 'price' => 0],
                'ranch' => ['name' => 'Ranch Dressing', 'price' => 0],
                'olive_oil_lemon' => ['name' => 'Olive Oil & Lemon', 'price' => 0],
                'tahini' => ['name' => 'Tahini Dressing', 'price' => 0.50]
            ]
        ],
        'toppings' => [
            'required' => false,
            'max_selections' => 4,
            'options' => [
                'croutons' => ['name' => 'Herb Croutons', 'price' => 0.75],
                'nuts' => ['name' => 'Mixed Nuts', 'price' => 1.50],
                'seeds' => ['name' => 'Sunflower Seeds', 'price' => 1.00],
                'dried_fruit' => ['name' => 'Dried Cranberries', 'price' => 1.25]
            ]
        ]
    ]
]
```

## User Interface Design

### Step-by-Step Builder

#### Visual Layout
```html
<!-- Component Selection Interface -->
<div class="composable-builder">
    <div class="progress-bar">
        <div class="step active">Base</div>
        <div class="step">Protein</div>
        <div class="step">Vegetables</div>
        <div class="step">Sauce</div>
        <div class="step">Review</div>
    </div>
    
    <div class="component-section">
        <h3>Choose Your Base</h3>
        <p class="requirement">Required • Select 1</p>
        
        <div class="options-grid">
            <div class="option-card" data-option="brown_rice">
                <img src="/images/ingredients/brown_rice.jpg" alt="Brown Rice">
                <h4>Brown Rice</h4>
                <p class="price">Included</p>
                <button class="select-btn">Select</button>
            </div>
            <!-- More options... -->
        </div>
    </div>
    
    <div class="price-summary">
        <div class="current-price">$14.99</div>
        <button class="next-step">Next: Choose Protein</button>
    </div>
</div>
```

#### Interactive Elements
- **Option Cards** - Visual ingredient selection
- **Quantity Selectors** - Adjust ingredient amounts
- **Price Calculator** - Real-time price updates
- **Progress Indicator** - Show completion status
- **Validation Messages** - Guide user through requirements

### Mobile Optimization

#### Touch-Friendly Design
- **Large Touch Targets** - Minimum 44px touch areas
- **Swipe Navigation** - Swipe between component steps
- **Drag and Drop** - Intuitive ingredient selection
- **Haptic Feedback** - Tactile response for selections

#### Responsive Layout
```css
/* Mobile-first responsive design */
.composable-builder {
    padding: 1rem;
}

.options-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
}

.option-card {
    border: 2px solid #e0e0e0;
    border-radius: 12px;
    padding: 1rem;
    text-align: center;
    transition: all 0.3s ease;
}

.option-card.selected {
    border-color: #4CAF50;
    background-color: #f8fff8;
}

@media (max-width: 768px) {
    .options-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .option-card {
        padding: 0.75rem;
    }
}
```

## Pricing Logic

### Dynamic Price Calculation

```javascript
class ComposablePricing {
    constructor(basePrice, sizeMultiplier = 1) {
        this.basePrice = basePrice;
        this.sizeMultiplier = sizeMultiplier;
        this.selections = new Map();
    }
    
    addSelection(componentId, optionId, option) {
        if (!this.selections.has(componentId)) {
            this.selections.set(componentId, new Map());
        }
        
        this.selections.get(componentId).set(optionId, {
            ...option,
            quantity: (this.selections.get(componentId).get(optionId)?.quantity || 0) + 1
        });
        
        this.updatePrice();
    }
    
    removeSelection(componentId, optionId) {
        if (this.selections.has(componentId)) {
            this.selections.get(componentId).delete(optionId);
        }
        this.updatePrice();
    }
    
    calculateTotal() {
        let additionalCost = 0;
        
        this.selections.forEach((options, componentId) => {
            options.forEach((option, optionId) => {
                additionalCost += (option.price || 0) * (option.quantity || 1);
            });
        });
        
        return (this.basePrice + additionalCost) * this.sizeMultiplier;
    }
    
    updatePrice() {
        const total = this.calculateTotal();
        document.querySelector('.current-price').textContent = `$${total.toFixed(2)}`;
    }
}
```

### Pricing Rules

#### Base Pricing
- **Fixed Base Price** - Starting cost for the composable item
- **Size Multipliers** - Adjust price based on portion size
- **Component Pricing** - Additional cost per selected ingredient
- **Quantity Pricing** - Cost multiplication for multiple selections

#### Special Pricing
- **Premium Ingredients** - Higher cost for premium options
- **Bulk Discounts** - Reduced per-unit cost for multiple selections
- **Combo Pricing** - Special rates for ingredient combinations
- **Seasonal Pricing** - Adjusted costs based on ingredient availability

## Validation and Constraints

### Selection Validation

```javascript
class ComposableValidator {
    constructor(config) {
        this.config = config;
    }
    
    validateComponent(componentId, selections) {
        const component = this.config.components[componentId];
        const errors = [];
        
        // Check required components
        if (component.required && selections.length === 0) {
            errors.push(`${componentId} is required`);
        }
        
        // Check minimum selections
        if (component.min_selections && selections.length < component.min_selections) {
            errors.push(`Select at least ${component.min_selections} ${componentId} options`);
        }
        
        // Check maximum selections
        if (component.max_selections && selections.length > component.max_selections) {
            errors.push(`Select no more than ${component.max_selections} ${componentId} options`);
        }
        
        // Check duplicates
        if (!component.allow_duplicates) {
            const uniqueSelections = new Set(selections.map(s => s.optionId));
            if (uniqueSelections.size !== selections.length) {
                errors.push(`Duplicate ${componentId} selections not allowed`);
            }
        }
        
        return errors;
    }
    
    validateAll(allSelections) {
        const allErrors = [];
        
        Object.keys(this.config.components).forEach(componentId => {
            const selections = allSelections[componentId] || [];
            const errors = this.validateComponent(componentId, selections);
            allErrors.push(...errors);
        });
        
        return allErrors;
    }
}
```

### Constraint Types

#### Selection Constraints
- **Required Components** - Must select from these categories
- **Minimum Selections** - Minimum number of options required
- **Maximum Selections** - Maximum number of options allowed
- **Exclusive Selection** - Only one option can be selected
- **Duplicate Prevention** - Same option cannot be selected multiple times

#### Availability Constraints
- **Stock Levels** - Real-time inventory checking
- **Time-based Availability** - Ingredients available only at certain times
- **Location Restrictions** - Ingredients available only at specific locations
- **Seasonal Availability** - Ingredients available only during certain seasons

## Nutritional Information

### Nutritional Calculation

```javascript
class NutritionalCalculator {
    constructor() {
        this.nutritionData = new Map();
    }
    
    loadNutritionData(ingredients) {
        ingredients.forEach(ingredient => {
            this.nutritionData.set(ingredient.id, {
                calories: ingredient.calories_per_serving,
                protein: ingredient.protein_grams,
                carbs: ingredient.carbs_grams,
                fat: ingredient.fat_grams,
                fiber: ingredient.fiber_grams,
                sodium: ingredient.sodium_mg,
                sugar: ingredient.sugar_grams
            });
        });
    }
    
    calculateTotals(selections) {
        const totals = {
            calories: 0,
            protein: 0,
            carbs: 0,
            fat: 0,
            fiber: 0,
            sodium: 0,
            sugar: 0
        };
        
        selections.forEach((options, componentId) => {
            options.forEach((option, optionId) => {
                const nutrition = this.nutritionData.get(optionId);
                if (nutrition) {
                    Object.keys(totals).forEach(key => {
                        totals[key] += nutrition[key] * (option.quantity || 1);
                    });
                }
            });
        });
        
        return totals;
    }
}
```

### Nutritional Display

#### Information Panel
```html
<div class="nutrition-panel">
    <h4>Nutritional Information</h4>
    <div class="nutrition-grid">
        <div class="nutrition-item">
            <span class="label">Calories</span>
            <span class="value" id="calories">450</span>
        </div>
        <div class="nutrition-item">
            <span class="label">Protein</span>
            <span class="value" id="protein">25g</span>
        </div>
        <div class="nutrition-item">
            <span class="label">Carbs</span>
            <span class="value" id="carbs">35g</span>
        </div>
        <div class="nutrition-item">
            <span class="label">Fat</span>
            <span class="value" id="fat">18g</span>
        </div>
    </div>
    
    <div class="dietary-tags">
        <span class="tag vegetarian">Vegetarian</span>
        <span class="tag gluten-free">Gluten-Free</span>
        <span class="tag high-protein">High Protein</span>
    </div>
</div>
```

## Allergen Management

### Allergen Tracking

```javascript
class AllergenManager {
    constructor() {
        this.commonAllergens = [
            'gluten', 'dairy', 'eggs', 'nuts', 'peanuts',
            'soy', 'fish', 'shellfish', 'sesame'
        ];
    }
    
    checkAllergens(selections) {
        const presentAllergens = new Set();
        
        selections.forEach((options, componentId) => {
            options.forEach((option, optionId) => {
                if (option.allergens) {
                    option.allergens.forEach(allergen => {
                        presentAllergens.add(allergen);
                    });
                }
            });
        });
        
        return Array.from(presentAllergens);
    }
    
    generateWarnings(allergens, customerAllergens = []) {
        const warnings = [];
        
        allergens.forEach(allergen => {
            if (customerAllergens.includes(allergen)) {
                warnings.push({
                    type: 'danger',
                    message: `WARNING: This item contains ${allergen}, which you've marked as an allergen.`,
                    allergen: allergen
                });
            }
        });
        
        return warnings;
    }
}
```

### Allergen Display

#### Warning System
```html
<div class="allergen-warnings">
    <div class="warning danger">
        <i class="icon-warning"></i>
        <span>WARNING: Contains nuts - marked as your allergen</span>
        <button class="remove-allergen">Remove nuts</button>
    </div>
    
    <div class="allergen-info">
        <h5>Allergens in this item:</h5>
        <div class="allergen-tags">
            <span class="allergen-tag">Gluten</span>
            <span class="allergen-tag">Dairy</span>
            <span class="allergen-tag danger">Nuts</span>
        </div>
    </div>
</div>
```

## Integration with Kitchen System

### Order Processing

```php
// ComposableOrderProcessor.php
class ComposableOrderProcessor
{
    public function processComposableOrder($orderData)
    {
        $composableItems = [];
        
        foreach ($orderData['items'] as $item) {
            if ($item['type'] === 'composable') {
                $composableItems[] = $this->formatForKitchen($item);
            }
        }
        
        return $this->sendToKitchen($composableItems);
    }
    
    private function formatForKitchen($item)
    {
        return [
            'item_name' => $item['base_name'],
            'components' => $this->formatComponents($item['selections']),
            'special_instructions' => $item['notes'] ?? '',
            'preparation_time' => $this->calculatePrepTime($item['selections']),
            'station_assignments' => $this->assignStations($item['selections'])
        ];
    }
    
    private function formatComponents($selections)
    {
        $formatted = [];
        
        foreach ($selections as $componentId => $options) {
            $formatted[$componentId] = [];
            foreach ($options as $optionId => $option) {
                $formatted[$componentId][] = [
                    'name' => $option['name'],
                    'quantity' => $option['quantity'] ?? 1,
                    'preparation_notes' => $option['prep_notes'] ?? ''
                ];
            }
        }
        
        return $formatted;
    }
}
```

### Kitchen Display

#### Order Ticket Format
```
================================
ORDER #1234 - TABLE 5
================================
CUSTOM BOWL

Base:
  ✓ Quinoa

Protein:
  ✓ Grilled Chicken
  ✓ Black Beans

Vegetables:
  ✓ Broccoli
  ✓ Bell Peppers
  ✓ Avocado (extra)

Sauce:
  ✓ Tahini Dressing

Toppings:
  ✓ Sesame Seeds

Special Instructions:
  - Extra sauce on the side
  - Light on the salt

Prep Time: 8 minutes
Station: Salad/Bowl Station
================================
```

## Analytics and Reporting

### Popular Combinations

```sql
-- Track popular ingredient combinations
SELECT 
    component_type,
    option_name,
    COUNT(*) as selection_count,
    AVG(order_rating) as avg_rating
FROM composable_selections cs
JOIN orders o ON cs.order_id = o.id
WHERE o.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
GROUP BY component_type, option_name
ORDER BY selection_count DESC;
```

### Revenue Analysis

```sql
-- Analyze composable item profitability
SELECT 
    base_item_name,
    AVG(total_price) as avg_price,
    AVG(ingredient_cost) as avg_cost,
    AVG(total_price - ingredient_cost) as avg_profit,
    COUNT(*) as order_count
FROM composable_orders
WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
GROUP BY base_item_name
ORDER BY avg_profit DESC;
```

### Customer Preferences

```javascript
// Track customer customization patterns
class ComposableAnalytics {
    trackSelection(customerId, itemType, componentId, optionId) {
        const event = {
            customer_id: customerId,
            item_type: itemType,
            component_id: componentId,
            option_id: optionId,
            timestamp: new Date().toISOString()
        };
        
        // Send to analytics service
        this.sendAnalyticsEvent('composable_selection', event);
    }
    
    getRecommendations(customerId, itemType) {
        // Get personalized recommendations based on past selections
        return fetch(`/api/recommendations/composable`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                customer_id: customerId,
                item_type: itemType
            })
        }).then(response => response.json());
    }
}
```

## Best Practices

### Configuration Management

1. **Logical Grouping** - Group related ingredients in components
2. **Clear Naming** - Use descriptive names for components and options
3. **Reasonable Limits** - Set appropriate min/max selection limits
4. **Price Balance** - Ensure fair pricing for all combinations
5. **Regular Updates** - Keep ingredient availability current

### User Experience

1. **Progressive Disclosure** - Show information as needed
2. **Visual Feedback** - Provide clear selection indicators
3. **Error Prevention** - Guide users through valid selections
4. **Mobile Optimization** - Ensure touch-friendly interface
5. **Performance** - Optimize for fast loading and interaction

### Kitchen Operations

1. **Clear Instructions** - Provide detailed preparation notes
2. **Station Assignment** - Route items to appropriate kitchen stations
3. **Timing Coordination** - Account for different preparation times
4. **Quality Control** - Maintain consistency across custom items
5. **Staff Training** - Ensure kitchen staff understand composable system

---

**Next Steps:**
- [Customer Ordering Portal](./ordering.md)
- [Menu Display System](./menu.md)
- [Admin Management](../admin/)
- [API Reference](../api/)