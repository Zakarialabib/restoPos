# Menu API

The Menu API allows you to manage restaurant menu items, categories, modifiers, and pricing through the RestoPos system.

## Menu Item Object

The menu item object represents a dish or product available for order.

### Menu Item Structure

```json
{
  "id": 101,
  "name": "Margherita Pizza",
  "description": "Classic pizza with fresh mozzarella, tomato sauce, and basil",
  "price": 18.00,
  "category_id": 1,
  "image_url": "https://example.com/images/margherita-pizza.jpg",
  "is_available": true,
  "is_featured": false,
  "preparation_time": 15,
  "calories": 280,
  "allergens": ["gluten", "dairy"],
  "dietary_info": ["vegetarian"],
  "ingredients": [
    "Pizza dough",
    "Tomato sauce",
    "Mozzarella cheese",
    "Fresh basil",
    "Olive oil"
  ],
  "nutritional_info": {
    "calories": 280,
    "protein": 12.5,
    "carbohydrates": 35.2,
    "fat": 8.9,
    "fiber": 2.1,
    "sodium": 590
  },
  "variants": [
    {
      "id": 1001,
      "name": "Small",
      "price": 14.00,
      "size": "9 inch"
    },
    {
      "id": 1002,
      "name": "Large",
      "price": 22.00,
      "size": "14 inch"
    }
  ],
  "modifiers": [
    {
      "id": 201,
      "name": "Extra Cheese",
      "price": 2.00,
      "category": "Toppings"
    },
    {
      "id": 202,
      "name": "Mushrooms",
      "price": 1.50,
      "category": "Toppings"
    }
  ],
  "category": {
    "id": 1,
    "name": "Pizzas",
    "slug": "pizzas"
  },
  "tags": ["popular", "signature"],
  "sort_order": 1,
  "created_at": "2024-01-01T10:00:00Z",
  "updated_at": "2024-01-15T14:30:00Z"
}
```

## Category Object

The category object represents a menu section or grouping.

### Category Structure

```json
{
  "id": 1,
  "name": "Pizzas",
  "slug": "pizzas",
  "description": "Authentic wood-fired pizzas made with fresh ingredients",
  "image_url": "https://example.com/images/pizza-category.jpg",
  "is_active": true,
  "sort_order": 1,
  "parent_id": null,
  "items_count": 12,
  "created_at": "2024-01-01T10:00:00Z",
  "updated_at": "2024-01-15T14:30:00Z"
}
```

## List Menu Items

Retrieve a list of menu items with optional filtering and pagination.

**Endpoint**: `GET /api/menu/items`

### Query Parameters

| Parameter | Type | Description |
|-----------|------|-------------|
| `page` | integer | Page number (default: 1) |
| `per_page` | integer | Items per page (default: 15, max: 100) |
| `category_id` | integer | Filter by category ID |
| `is_available` | boolean | Filter by availability |
| `is_featured` | boolean | Filter featured items |
| `search` | string | Search in name and description |
| `tags` | string | Filter by tags (comma-separated) |
| `allergens` | string | Filter by allergens (comma-separated) |
| `dietary_info` | string | Filter by dietary info (comma-separated) |
| `price_min` | number | Minimum price filter |
| `price_max` | number | Maximum price filter |
| `sort` | string | Sort field (name, price, created_at, sort_order) |
| `order` | string | Sort direction (asc, desc) |
| `include` | string | Include related data (category, modifiers, variants) |

### Example Request

```http
GET /api/menu/items?category_id=1&is_available=true&include=category,modifiers
Authorization: Bearer your_token
```

### Example Response

```json
{
  "success": true,
  "data": {
    "items": [
      {
        "id": 101,
        "name": "Margherita Pizza",
        "description": "Classic pizza with fresh mozzarella, tomato sauce, and basil",
        "price": 18.00,
        "category_id": 1,
        "image_url": "https://example.com/images/margherita-pizza.jpg",
        "is_available": true,
        "preparation_time": 15,
        "category": {
          "id": 1,
          "name": "Pizzas",
          "slug": "pizzas"
        },
        "modifiers": [...]
      }
    ],
    "pagination": {
      "current_page": 1,
      "per_page": 15,
      "total": 45,
      "total_pages": 3,
      "has_next_page": true,
      "has_prev_page": false
    }
  }
}
```

## Get Menu Item

Retrieve a specific menu item by ID.

**Endpoint**: `GET /api/menu/items/{id}`

### Query Parameters

| Parameter | Type | Description |
|-----------|------|-------------|
| `include` | string | Include related data (category, modifiers, variants, nutritional_info) |

### Example Request

```http
GET /api/menu/items/101?include=category,modifiers,variants,nutritional_info
Authorization: Bearer your_token
```

### Example Response

```json
{
  "success": true,
  "data": {
    "item": {
      "id": 101,
      "name": "Margherita Pizza",
      "description": "Classic pizza with fresh mozzarella, tomato sauce, and basil",
      "price": 18.00,
      "category_id": 1,
      "image_url": "https://example.com/images/margherita-pizza.jpg",
      "is_available": true,
      "is_featured": false,
      "preparation_time": 15,
      "calories": 280,
      "allergens": ["gluten", "dairy"],
      "dietary_info": ["vegetarian"],
      "ingredients": [...],
      "nutritional_info": {...},
      "variants": [...],
      "modifiers": [...],
      "category": {...},
      "tags": ["popular", "signature"],
      "created_at": "2024-01-01T10:00:00Z",
      "updated_at": "2024-01-15T14:30:00Z"
    }
  }
}
```

## Create Menu Item

Create a new menu item.

**Endpoint**: `POST /api/menu/items`

### Request Body

```json
{
  "name": "Pepperoni Pizza",
  "description": "Classic pizza with pepperoni and mozzarella cheese",
  "price": 20.00,
  "category_id": 1,
  "image_url": "https://example.com/images/pepperoni-pizza.jpg",
  "is_available": true,
  "is_featured": false,
  "preparation_time": 18,
  "calories": 320,
  "allergens": ["gluten", "dairy"],
  "dietary_info": [],
  "ingredients": [
    "Pizza dough",
    "Tomato sauce",
    "Mozzarella cheese",
    "Pepperoni",
    "Olive oil"
  ],
  "nutritional_info": {
    "calories": 320,
    "protein": 15.2,
    "carbohydrates": 35.8,
    "fat": 12.4,
    "fiber": 2.1,
    "sodium": 720
  },
  "variants": [
    {
      "name": "Small",
      "price": 16.00,
      "size": "9 inch"
    },
    {
      "name": "Large",
      "price": 24.00,
      "size": "14 inch"
    }
  ],
  "modifier_ids": [201, 202, 203],
  "tags": ["popular"],
  "sort_order": 2
}
```

### Required Fields

- `name`: Item name
- `price`: Base price
- `category_id`: Category ID

### Optional Fields

- `description`: Item description
- `image_url`: Image URL
- `is_available`: Availability status (default: true)
- `is_featured`: Featured status (default: false)
- `preparation_time`: Preparation time in minutes
- `calories`: Calorie count
- `allergens`: Array of allergen strings
- `dietary_info`: Array of dietary information
- `ingredients`: Array of ingredient strings
- `nutritional_info`: Nutritional information object
- `variants`: Array of variant objects
- `modifier_ids`: Array of modifier IDs
- `tags`: Array of tag strings
- `sort_order`: Display order

### Example Request

```http
POST /api/menu/items
Authorization: Bearer your_token
Content-Type: application/json

{
  "name": "Hawaiian Pizza",
  "description": "Pizza with ham, pineapple, and mozzarella cheese",
  "price": 19.00,
  "category_id": 1,
  "preparation_time": 16,
  "allergens": ["gluten", "dairy"],
  "tags": ["tropical"]
}
```

### Example Response

```json
{
  "success": true,
  "message": "Menu item created successfully",
  "data": {
    "item": {
      "id": 102,
      "name": "Hawaiian Pizza",
      "description": "Pizza with ham, pineapple, and mozzarella cheese",
      "price": 19.00,
      "category_id": 1,
      "is_available": true,
      "preparation_time": 16,
      "allergens": ["gluten", "dairy"],
      "tags": ["tropical"],
      "created_at": "2024-01-15T15:00:00Z",
      "updated_at": "2024-01-15T15:00:00Z"
    }
  }
}
```

## Update Menu Item

Update an existing menu item.

**Endpoint**: `PUT /api/menu/items/{id}`

### Request Body

```json
{
  "name": "Updated Pizza Name",
  "price": 21.00,
  "is_available": false,
  "preparation_time": 20
}
```

### Example Request

```http
PUT /api/menu/items/101
Authorization: Bearer your_token
Content-Type: application/json

{
  "price": 19.50,
  "is_featured": true,
  "tags": ["popular", "signature", "bestseller"]
}
```

### Example Response

```json
{
  "success": true,
  "message": "Menu item updated successfully",
  "data": {
    "item": {
      "id": 101,
      "name": "Margherita Pizza",
      "price": 19.50,
      "is_featured": true,
      "tags": ["popular", "signature", "bestseller"],
      "updated_at": "2024-01-15T15:30:00Z"
    }
  }
}
```

## Delete Menu Item

Delete a menu item. Items with existing orders cannot be deleted.

**Endpoint**: `DELETE /api/menu/items/{id}`

### Example Request

```http
DELETE /api/menu/items/102
Authorization: Bearer your_token
```

### Example Response

```json
{
  "success": true,
  "message": "Menu item deleted successfully"
}
```

## List Categories

Retrieve a list of menu categories.

**Endpoint**: `GET /api/menu/categories`

### Query Parameters

| Parameter | Type | Description |
|-----------|------|-------------|
| `is_active` | boolean | Filter by active status |
| `parent_id` | integer | Filter by parent category ID |
| `include_items` | boolean | Include items count |
| `sort` | string | Sort field (name, sort_order, created_at) |
| `order` | string | Sort direction (asc, desc) |

### Example Request

```http
GET /api/menu/categories?is_active=true&include_items=true
Authorization: Bearer your_token
```

### Example Response

```json
{
  "success": true,
  "data": {
    "categories": [
      {
        "id": 1,
        "name": "Pizzas",
        "slug": "pizzas",
        "description": "Authentic wood-fired pizzas",
        "image_url": "https://example.com/images/pizza-category.jpg",
        "is_active": true,
        "sort_order": 1,
        "parent_id": null,
        "items_count": 12,
        "created_at": "2024-01-01T10:00:00Z"
      },
      {
        "id": 2,
        "name": "Appetizers",
        "slug": "appetizers",
        "description": "Delicious starters",
        "is_active": true,
        "sort_order": 2,
        "items_count": 8
      }
    ]
  }
}
```

## Get Category

Retrieve a specific category by ID.

**Endpoint**: `GET /api/menu/categories/{id}`

### Query Parameters

| Parameter | Type | Description |
|-----------|------|-------------|
| `include_items` | boolean | Include category items |
| `items_limit` | integer | Limit number of items returned |

### Example Request

```http
GET /api/menu/categories/1?include_items=true&items_limit=10
Authorization: Bearer your_token
```

### Example Response

```json
{
  "success": true,
  "data": {
    "category": {
      "id": 1,
      "name": "Pizzas",
      "slug": "pizzas",
      "description": "Authentic wood-fired pizzas made with fresh ingredients",
      "image_url": "https://example.com/images/pizza-category.jpg",
      "is_active": true,
      "sort_order": 1,
      "parent_id": null,
      "items_count": 12,
      "items": [
        {
          "id": 101,
          "name": "Margherita Pizza",
          "price": 18.00,
          "is_available": true
        }
      ],
      "created_at": "2024-01-01T10:00:00Z",
      "updated_at": "2024-01-15T14:30:00Z"
    }
  }
}
```

## Create Category

Create a new menu category.

**Endpoint**: `POST /api/menu/categories`

### Request Body

```json
{
  "name": "Desserts",
  "slug": "desserts",
  "description": "Sweet treats and desserts",
  "image_url": "https://example.com/images/desserts-category.jpg",
  "is_active": true,
  "sort_order": 5,
  "parent_id": null
}
```

### Required Fields

- `name`: Category name

### Optional Fields

- `slug`: URL-friendly slug (auto-generated if not provided)
- `description`: Category description
- `image_url`: Category image URL
- `is_active`: Active status (default: true)
- `sort_order`: Display order
- `parent_id`: Parent category ID for subcategories

### Example Request

```http
POST /api/menu/categories
Authorization: Bearer your_token
Content-Type: application/json

{
  "name": "Beverages",
  "description": "Hot and cold drinks",
  "sort_order": 3
}
```

### Example Response

```json
{
  "success": true,
  "message": "Category created successfully",
  "data": {
    "category": {
      "id": 3,
      "name": "Beverages",
      "slug": "beverages",
      "description": "Hot and cold drinks",
      "is_active": true,
      "sort_order": 3,
      "parent_id": null,
      "items_count": 0,
      "created_at": "2024-01-15T16:00:00Z",
      "updated_at": "2024-01-15T16:00:00Z"
    }
  }
}
```

## Update Category

Update an existing category.

**Endpoint**: `PUT /api/menu/categories/{id}`

### Example Request

```http
PUT /api/menu/categories/3
Authorization: Bearer your_token
Content-Type: application/json

{
  "name": "Drinks & Beverages",
  "description": "Refreshing hot and cold beverages",
  "sort_order": 4
}
```

### Example Response

```json
{
  "success": true,
  "message": "Category updated successfully",
  "data": {
    "category": {
      "id": 3,
      "name": "Drinks & Beverages",
      "slug": "beverages",
      "description": "Refreshing hot and cold beverages",
      "sort_order": 4,
      "updated_at": "2024-01-15T16:30:00Z"
    }
  }
}
```

## Delete Category

Delete a category. Categories with items cannot be deleted.

**Endpoint**: `DELETE /api/menu/categories/{id}`

### Example Request

```http
DELETE /api/menu/categories/3
Authorization: Bearer your_token
```

### Example Response

```json
{
  "success": true,
  "message": "Category deleted successfully"
}
```

## Modifiers

Manage menu item modifiers (add-ons, customizations).

### List Modifiers

**Endpoint**: `GET /api/menu/modifiers`

### Query Parameters

| Parameter | Type | Description |
|-----------|------|-------------|
| `category` | string | Filter by modifier category |
| `is_active` | boolean | Filter by active status |
| `search` | string | Search in name |

### Example Request

```http
GET /api/menu/modifiers?category=Toppings&is_active=true
Authorization: Bearer your_token
```

### Example Response

```json
{
  "success": true,
  "data": {
    "modifiers": [
      {
        "id": 201,
        "name": "Extra Cheese",
        "price": 2.00,
        "category": "Toppings",
        "is_active": true,
        "sort_order": 1
      },
      {
        "id": 202,
        "name": "Mushrooms",
        "price": 1.50,
        "category": "Toppings",
        "is_active": true,
        "sort_order": 2
      }
    ]
  }
}
```

### Create Modifier

**Endpoint**: `POST /api/menu/modifiers`

### Request Body

```json
{
  "name": "Bacon",
  "price": 3.00,
  "category": "Toppings",
  "is_active": true,
  "sort_order": 5
}
```

### Example Response

```json
{
  "success": true,
  "message": "Modifier created successfully",
  "data": {
    "modifier": {
      "id": 205,
      "name": "Bacon",
      "price": 3.00,
      "category": "Toppings",
      "is_active": true,
      "sort_order": 5,
      "created_at": "2024-01-15T17:00:00Z"
    }
  }
}
```

## Menu Availability

Manage menu item availability and scheduling.

### Update Item Availability

**Endpoint**: `PATCH /api/menu/items/{id}/availability`

### Request Body

```json
{
  "is_available": false,
  "unavailable_reason": "Out of stock",
  "available_from": "2024-01-16T10:00:00Z",
  "available_until": "2024-01-16T22:00:00Z"
}
```

### Example Request

```http
PATCH /api/menu/items/101/availability
Authorization: Bearer your_token
Content-Type: application/json

{
  "is_available": false,
  "unavailable_reason": "Temporarily out of ingredients"
}
```

### Example Response

```json
{
  "success": true,
  "message": "Item availability updated successfully",
  "data": {
    "item": {
      "id": 101,
      "name": "Margherita Pizza",
      "is_available": false,
      "unavailable_reason": "Temporarily out of ingredients",
      "updated_at": "2024-01-15T17:30:00Z"
    }
  }
}
```

### Bulk Update Availability

**Endpoint**: `PATCH /api/menu/items/availability`

### Request Body

```json
{
  "items": [
    {
      "id": 101,
      "is_available": true
    },
    {
      "id": 102,
      "is_available": false,
      "unavailable_reason": "Seasonal item"
    }
  ]
}
```

## Menu Analytics

Get menu performance analytics.

**Endpoint**: `GET /api/menu/analytics`

### Query Parameters

| Parameter | Type | Description |
|-----------|------|-------------|
| `period` | string | Time period (today, week, month, year, custom) |
| `date_from` | date | Start date for custom period |
| `date_to` | date | End date for custom period |
| `category_id` | integer | Filter by category |

### Example Request

```http
GET /api/menu/analytics?period=month&category_id=1
Authorization: Bearer your_token
```

### Example Response

```json
{
  "success": true,
  "data": {
    "summary": {
      "total_items": 45,
      "active_items": 42,
      "total_orders": 1250,
      "total_revenue": 28750.50,
      "average_item_price": 16.75
    },
    "top_selling_items": [
      {
        "id": 101,
        "name": "Margherita Pizza",
        "orders_count": 156,
        "revenue": 3042.00,
        "percentage_of_total": 12.5
      },
      {
        "id": 103,
        "name": "Caesar Salad",
        "orders_count": 98,
        "revenue": 1225.00,
        "percentage_of_total": 7.8
      }
    ],
    "category_performance": [
      {
        "category_id": 1,
        "category_name": "Pizzas",
        "orders_count": 450,
        "revenue": 8100.00,
        "percentage_of_total": 28.2
      }
    ],
    "low_performing_items": [
      {
        "id": 120,
        "name": "Exotic Salad",
        "orders_count": 3,
        "revenue": 45.00,
        "percentage_of_total": 0.2
      }
    ]
  }
}
```

## Error Handling

### Common Error Responses

#### 400 Bad Request

```json
{
  "success": false,
  "error": {
    "code": "INVALID_MENU_DATA",
    "message": "Invalid menu item data provided",
    "details": {
      "name": ["The name field is required"],
      "price": ["The price must be greater than 0"]
    }
  }
}
```

#### 404 Not Found

```json
{
  "success": false,
  "error": {
    "code": "MENU_ITEM_NOT_FOUND",
    "message": "Menu item not found",
    "details": "No menu item found with ID 101"
  }
}
```

#### 409 Conflict

```json
{
  "success": false,
  "error": {
    "code": "CATEGORY_HAS_ITEMS",
    "message": "Cannot delete category with items",
    "details": "Category 'Pizzas' has 12 items. Move or delete items first."
  }
}
```

## SDK Examples

::: tabs

== JavaScript

```javascript
class MenuAPI {
  constructor(baseURL, token) {
    this.baseURL = baseURL;
    this.token = token;
  }

  async getMenuItems(filters = {}) {
    const params = new URLSearchParams(filters);
    const response = await fetch(`${this.baseURL}/api/menu/items?${params}`, {
      headers: {
        'Authorization': `Bearer ${this.token}`,
        'Accept': 'application/json'
      }
    });
    return response.json();
  }

  async createMenuItem(itemData) {
    const response = await fetch(`${this.baseURL}/api/menu/items`, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${this.token}`,
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(itemData)
    });
    return response.json();
  }

  async updateAvailability(itemId, availability) {
    const response = await fetch(`${this.baseURL}/api/menu/items/${itemId}/availability`, {
      method: 'PATCH',
      headers: {
        'Authorization': `Bearer ${this.token}`,
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(availability)
    });
    return response.json();
  }
}

// Usage
const menuAPI = new MenuAPI('https://api.restopos.com', 'your_token');

// Get available pizzas
const pizzas = await menuAPI.getMenuItems({
  category_id: 1,
  is_available: true
});

// Create new item
const newItem = await menuAPI.createMenuItem({
  name: 'Veggie Supreme Pizza',
  price: 22.00,
  category_id: 1,
  allergens: ['gluten', 'dairy']
});

// Mark item as unavailable
const updated = await menuAPI.updateAvailability(101, {
  is_available: false,
  unavailable_reason: 'Out of stock'
});
```

== PHP

```php
class MenuAPI
{
    private $baseUrl;
    private $token;

    public function __construct($baseUrl, $token)
    {
        $this->baseUrl = $baseUrl;
        $this->token = $token;
    }

    public function getMenuItems($filters = [])
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ])->get($this->baseUrl . '/api/menu/items', $filters);

        return $response->json();
    }

    public function createMenuItem($itemData)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ])->post($this->baseUrl . '/api/menu/items', $itemData);

        return $response->json();
    }

    public function updateAvailability($itemId, $availability)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ])->patch($this->baseUrl . "/api/menu/items/{$itemId}/availability", $availability);

        return $response->json();
    }
}

// Usage
$menuAPI = new MenuAPI('https://api.restopos.com', 'your_token');

// Get featured items
$featured = $menuAPI->getMenuItems(['is_featured' => true]);

// Create new category
$newCategory = $menuAPI->createCategory([
    'name' => 'Seasonal Specials',
    'description' => 'Limited time seasonal items'
]);
```

== Python

```python
import requests
from typing import Dict, List, Optional

class MenuAPI:
    def __init__(self, base_url: str, token: str):
        self.base_url = base_url
        self.token = token
        self.headers = {
            'Authorization': f'Bearer {token}',
            'Content-Type': 'application/json'
        }

    def get_menu_items(self, filters: Optional[Dict] = None) -> Dict:
        params = filters or {}
        response = requests.get(
            f'{self.base_url}/api/menu/items',
            params=params,
            headers=self.headers
        )
        return response.json()

    def create_menu_item(self, item_data: Dict) -> Dict:
        response = requests.post(
            f'{self.base_url}/api/menu/items',
            json=item_data,
            headers=self.headers
        )
        return response.json()

    def get_analytics(self, period: str = 'month') -> Dict:
        response = requests.get(
            f'{self.base_url}/api/menu/analytics',
            params={'period': period},
            headers=self.headers
        )
        return response.json()

# Usage
menu_api = MenuAPI('https://api.restopos.com', 'your_token')

# Get vegetarian items
vegetarian_items = menu_api.get_menu_items({
    'dietary_info': 'vegetarian',
    'is_available': True
})

# Get menu analytics
analytics = menu_api.get_analytics('week')
print(f"Top selling item: {analytics['data']['top_selling_items'][0]['name']}")
```

:::

## Best Practices

### Menu Management

1. **Consistent Naming**: Use consistent naming conventions for items and categories
2. **Rich Descriptions**: Provide detailed, appetizing descriptions
3. **High-Quality Images**: Use high-resolution, professional food photography
4. **Accurate Pricing**: Keep prices up-to-date and consistent across channels
5. **Allergen Information**: Always include accurate allergen and dietary information

### Performance Optimization

1. **Image Optimization**: Compress images and use appropriate formats
2. **Caching**: Implement caching for frequently accessed menu data
3. **Pagination**: Use pagination for large menu catalogs
4. **Lazy Loading**: Implement lazy loading for images and non-critical data
5. **CDN Usage**: Use CDN for image delivery

### Inventory Integration

1. **Real-time Updates**: Update availability based on inventory levels
2. **Automatic Notifications**: Set up alerts for low-stock items
3. **Batch Updates**: Use bulk operations for efficiency
4. **Scheduled Availability**: Implement time-based availability rules
5. **Seasonal Menus**: Plan and schedule seasonal menu changes