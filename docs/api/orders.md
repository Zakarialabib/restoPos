# Orders API

The Orders API allows you to manage restaurant orders, including creating, updating, retrieving, and processing orders through the RestoPos system.

## Order Object

The order object represents a customer's order with all associated items, payments, and metadata.

### Order Structure

```json
{
  "id": 123,
  "order_number": "ORD-2024-001234",
  "status": "confirmed",
  "type": "dine_in",
  "table_id": 5,
  "customer_id": 456,
  "staff_id": 2,
  "subtotal": 45.50,
  "tax_amount": 4.55,
  "discount_amount": 5.00,
  "total_amount": 45.05,
  "currency": "USD",
  "notes": "Extra spicy, no onions",
  "special_instructions": "Table by the window",
  "estimated_ready_time": "2024-01-15T19:30:00Z",
  "created_at": "2024-01-15T19:00:00Z",
  "updated_at": "2024-01-15T19:05:00Z",
  "items": [
    {
      "id": 789,
      "menu_item_id": 101,
      "name": "Margherita Pizza",
      "quantity": 2,
      "unit_price": 18.00,
      "total_price": 36.00,
      "notes": "Extra cheese",
      "modifiers": [
        {
          "id": 201,
          "name": "Extra Cheese",
          "price": 2.00
        }
      ]
    },
    {
      "id": 790,
      "menu_item_id": 102,
      "name": "Caesar Salad",
      "quantity": 1,
      "unit_price": 12.50,
      "total_price": 12.50,
      "notes": "Dressing on the side"
    }
  ],
  "customer": {
    "id": 456,
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "+1234567890"
  },
  "table": {
    "id": 5,
    "number": "T5",
    "section": "Main Dining",
    "capacity": 4
  },
  "payments": [
    {
      "id": 301,
      "method": "card",
      "amount": 45.05,
      "status": "completed",
      "transaction_id": "txn_abc123",
      "processed_at": "2024-01-15T19:35:00Z"
    }
  ]
}
```

### Order Status Values

| Status | Description |
|--------|-------------|
| `pending` | Order created but not confirmed |
| `confirmed` | Order confirmed and sent to kitchen |
| `preparing` | Kitchen is preparing the order |
| `ready` | Order is ready for pickup/serving |
| `served` | Order has been served to customer |
| `completed` | Order completed and paid |
| `cancelled` | Order was cancelled |
| `refunded` | Order was refunded |

### Order Types

| Type | Description |
|------|-------------|
| `dine_in` | Customer dining in the restaurant |
| `takeaway` | Customer picking up the order |
| `delivery` | Order will be delivered |
| `drive_through` | Drive-through order |

## List Orders

Retrieve a list of orders with optional filtering and pagination.

**Endpoint**: `GET /api/orders`

### Query Parameters

| Parameter | Type | Description |
|-----------|------|-------------|
| `page` | integer | Page number (default: 1) |
| `per_page` | integer | Items per page (default: 15, max: 100) |
| `status` | string | Filter by order status |
| `type` | string | Filter by order type |
| `table_id` | integer | Filter by table ID |
| `customer_id` | integer | Filter by customer ID |
| `staff_id` | integer | Filter by staff member ID |
| `date_from` | date | Filter orders from date (YYYY-MM-DD) |
| `date_to` | date | Filter orders to date (YYYY-MM-DD) |
| `search` | string | Search in order number or customer name |
| `sort` | string | Sort field (id, created_at, total_amount) |
| `order` | string | Sort direction (asc, desc) |

### Example Request

```http
GET /api/orders?status=confirmed&type=dine_in&page=1&per_page=20
Authorization: Bearer your_token
```

### Example Response

```json
{
  "success": true,
  "data": {
    "orders": [
      {
        "id": 123,
        "order_number": "ORD-2024-001234",
        "status": "confirmed",
        "type": "dine_in",
        "table_id": 5,
        "total_amount": 45.05,
        "created_at": "2024-01-15T19:00:00Z",
        "customer": {
          "name": "John Doe"
        }
      }
    ],
    "pagination": {
      "current_page": 1,
      "per_page": 20,
      "total": 150,
      "total_pages": 8,
      "has_next_page": true,
      "has_prev_page": false
    }
  }
}
```

## Get Order

Retrieve a specific order by ID.

**Endpoint**: `GET /api/orders/{id}`

### Example Request

```http
GET /api/orders/123
Authorization: Bearer your_token
```

### Example Response

```json
{
  "success": true,
  "data": {
    "order": {
      "id": 123,
      "order_number": "ORD-2024-001234",
      "status": "confirmed",
      "type": "dine_in",
      "table_id": 5,
      "customer_id": 456,
      "staff_id": 2,
      "subtotal": 45.50,
      "tax_amount": 4.55,
      "discount_amount": 5.00,
      "total_amount": 45.05,
      "currency": "USD",
      "notes": "Extra spicy, no onions",
      "created_at": "2024-01-15T19:00:00Z",
      "updated_at": "2024-01-15T19:05:00Z",
      "items": [...],
      "customer": {...},
      "table": {...},
      "payments": [...]
    }
  }
}
```

## Create Order

Create a new order.

**Endpoint**: `POST /api/orders`

### Request Body

```json
{
  "type": "dine_in",
  "table_id": 5,
  "customer_id": 456,
  "notes": "Customer allergic to nuts",
  "special_instructions": "Rush order",
  "items": [
    {
      "menu_item_id": 101,
      "quantity": 2,
      "notes": "Extra cheese",
      "modifiers": [
        {
          "modifier_id": 201,
          "quantity": 1
        }
      ]
    },
    {
      "menu_item_id": 102,
      "quantity": 1,
      "notes": "Dressing on the side"
    }
  ],
  "discounts": [
    {
      "type": "percentage",
      "value": 10,
      "reason": "Happy hour discount"
    }
  ]
}
```

### Required Fields

- `type`: Order type (dine_in, takeaway, delivery)
- `items`: Array of order items (minimum 1 item)
- `items[].menu_item_id`: Menu item ID
- `items[].quantity`: Item quantity (minimum 1)

### Optional Fields

- `table_id`: Table ID (required for dine_in orders)
- `customer_id`: Existing customer ID
- `customer`: New customer data (if customer_id not provided)
- `notes`: General order notes
- `special_instructions`: Special preparation instructions
- `estimated_ready_time`: Custom ready time
- `discounts`: Array of discount objects
- `items[].notes`: Item-specific notes
- `items[].modifiers`: Array of modifier objects

### Example Request

```http
POST /api/orders
Authorization: Bearer your_token
Content-Type: application/json

{
  "type": "dine_in",
  "table_id": 5,
  "customer": {
    "name": "Jane Smith",
    "email": "jane@example.com",
    "phone": "+1987654321"
  },
  "items": [
    {
      "menu_item_id": 101,
      "quantity": 1,
      "modifiers": [
        {
          "modifier_id": 201,
          "quantity": 1
        }
      ]
    }
  ]
}
```

### Example Response

```json
{
  "success": true,
  "message": "Order created successfully",
  "data": {
    "order": {
      "id": 124,
      "order_number": "ORD-2024-001235",
      "status": "pending",
      "type": "dine_in",
      "table_id": 5,
      "total_amount": 20.00,
      "created_at": "2024-01-15T20:00:00Z",
      "items": [...],
      "customer": {...}
    }
  }
}
```

## Update Order

Update an existing order. Only orders with status `pending` or `confirmed` can be updated.

**Endpoint**: `PUT /api/orders/{id}`

### Request Body

```json
{
  "notes": "Updated notes",
  "special_instructions": "Updated instructions",
  "items": [
    {
      "id": 789,
      "quantity": 3,
      "notes": "Updated item notes"
    },
    {
      "menu_item_id": 103,
      "quantity": 1,
      "notes": "New item added"
    }
  ]
}
```

### Example Request

```http
PUT /api/orders/123
Authorization: Bearer your_token
Content-Type: application/json

{
  "notes": "Customer requested extra napkins",
  "items": [
    {
      "id": 789,
      "quantity": 3
    }
  ]
}
```

### Example Response

```json
{
  "success": true,
  "message": "Order updated successfully",
  "data": {
    "order": {
      "id": 123,
      "order_number": "ORD-2024-001234",
      "status": "confirmed",
      "notes": "Customer requested extra napkins",
      "updated_at": "2024-01-15T20:15:00Z",
      "items": [...]
    }
  }
}
```

## Update Order Status

Update the status of an order.

**Endpoint**: `PATCH /api/orders/{id}/status`

### Request Body

```json
{
  "status": "preparing",
  "notes": "Started cooking"
}
```

### Valid Status Transitions

- `pending` → `confirmed`, `cancelled`
- `confirmed` → `preparing`, `cancelled`
- `preparing` → `ready`, `cancelled`
- `ready` → `served`, `cancelled`
- `served` → `completed`
- `completed` → `refunded`

### Example Request

```http
PATCH /api/orders/123/status
Authorization: Bearer your_token
Content-Type: application/json

{
  "status": "ready",
  "notes": "Order ready for pickup"
}
```

### Example Response

```json
{
  "success": true,
  "message": "Order status updated successfully",
  "data": {
    "order": {
      "id": 123,
      "status": "ready",
      "updated_at": "2024-01-15T20:30:00Z"
    }
  }
}
```

## Cancel Order

Cancel an order. Only orders with status `pending`, `confirmed`, or `preparing` can be cancelled.

**Endpoint**: `DELETE /api/orders/{id}`

### Request Body (Optional)

```json
{
  "reason": "Customer requested cancellation",
  "refund_payment": true
}
```

### Example Request

```http
DELETE /api/orders/123
Authorization: Bearer your_token
Content-Type: application/json

{
  "reason": "Kitchen ran out of ingredients",
  "refund_payment": true
}
```

### Example Response

```json
{
  "success": true,
  "message": "Order cancelled successfully",
  "data": {
    "order": {
      "id": 123,
      "status": "cancelled",
      "cancellation_reason": "Kitchen ran out of ingredients",
      "cancelled_at": "2024-01-15T20:45:00Z"
    }
  }
}
```

## Add Items to Order

Add new items to an existing order.

**Endpoint**: `POST /api/orders/{id}/items`

### Request Body

```json
{
  "items": [
    {
      "menu_item_id": 104,
      "quantity": 2,
      "notes": "Extra sauce",
      "modifiers": [
        {
          "modifier_id": 202,
          "quantity": 1
        }
      ]
    }
  ]
}
```

### Example Request

```http
POST /api/orders/123/items
Authorization: Bearer your_token
Content-Type: application/json

{
  "items": [
    {
      "menu_item_id": 105,
      "quantity": 1,
      "notes": "Customer added dessert"
    }
  ]
}
```

### Example Response

```json
{
  "success": true,
  "message": "Items added to order successfully",
  "data": {
    "order": {
      "id": 123,
      "total_amount": 52.05,
      "updated_at": "2024-01-15T21:00:00Z",
      "items": [...]
    }
  }
}
```

## Remove Items from Order

Remove items from an existing order.

**Endpoint**: `DELETE /api/orders/{id}/items/{item_id}`

### Example Request

```http
DELETE /api/orders/123/items/789
Authorization: Bearer your_token
```

### Example Response

```json
{
  "success": true,
  "message": "Item removed from order successfully",
  "data": {
    "order": {
      "id": 123,
      "total_amount": 32.05,
      "updated_at": "2024-01-15T21:15:00Z",
      "items": [...]
    }
  }
}
```

## Order Statistics

Get order statistics for reporting and analytics.

**Endpoint**: `GET /api/orders/stats`

### Query Parameters

| Parameter | Type | Description |
|-----------|------|-------------|
| `period` | string | Time period (today, week, month, year, custom) |
| `date_from` | date | Start date for custom period |
| `date_to` | date | End date for custom period |
| `group_by` | string | Group results by (hour, day, week, month) |

### Example Request

```http
GET /api/orders/stats?period=month&group_by=day
Authorization: Bearer your_token
```

### Example Response

```json
{
  "success": true,
  "data": {
    "summary": {
      "total_orders": 1250,
      "total_revenue": 28750.50,
      "average_order_value": 23.00,
      "completed_orders": 1180,
      "cancelled_orders": 70,
      "completion_rate": 94.4
    },
    "by_status": {
      "pending": 15,
      "confirmed": 25,
      "preparing": 10,
      "ready": 5,
      "served": 20,
      "completed": 1180,
      "cancelled": 70
    },
    "by_type": {
      "dine_in": 750,
      "takeaway": 350,
      "delivery": 150
    },
    "daily_breakdown": [
      {
        "date": "2024-01-01",
        "orders": 45,
        "revenue": 1035.50
      },
      {
        "date": "2024-01-02",
        "orders": 52,
        "revenue": 1196.00
      }
    ]
  }
}
```

## Kitchen Display Integration

Endpoints for kitchen display system integration.

### Get Kitchen Orders

Retrieve orders for kitchen display.

**Endpoint**: `GET /api/kitchen/orders`

### Query Parameters

| Parameter | Type | Description |
|-----------|------|-------------|
| `status` | string | Filter by status (confirmed, preparing) |
| `priority` | string | Filter by priority (normal, high, urgent) |

### Example Request

```http
GET /api/kitchen/orders?status=confirmed,preparing
Authorization: Bearer your_token
```

### Example Response

```json
{
  "success": true,
  "data": {
    "orders": [
      {
        "id": 123,
        "order_number": "ORD-2024-001234",
        "status": "confirmed",
        "type": "dine_in",
        "table_number": "T5",
        "priority": "normal",
        "estimated_ready_time": "2024-01-15T19:30:00Z",
        "elapsed_time": "00:05:30",
        "items": [
          {
            "name": "Margherita Pizza",
            "quantity": 2,
            "notes": "Extra cheese",
            "modifiers": ["Extra Cheese"],
            "preparation_time": 15
          }
        ],
        "special_instructions": "Rush order"
      }
    ]
  }
}
```

### Update Kitchen Order Status

**Endpoint**: `PATCH /api/kitchen/orders/{id}/status`

```http
PATCH /api/kitchen/orders/123/status
Authorization: Bearer your_token
Content-Type: application/json

{
  "status": "preparing",
  "estimated_ready_time": "2024-01-15T19:35:00Z"
}
```

## Error Handling

### Common Error Responses

#### 400 Bad Request

```json
{
  "success": false,
  "error": {
    "code": "INVALID_ORDER_DATA",
    "message": "Invalid order data provided",
    "details": {
      "items": ["At least one item is required"],
      "table_id": ["Table ID is required for dine-in orders"]
    }
  }
}
```

#### 404 Not Found

```json
{
  "success": false,
  "error": {
    "code": "ORDER_NOT_FOUND",
    "message": "Order not found",
    "details": "No order found with ID 123"
  }
}
```

#### 409 Conflict

```json
{
  "success": false,
  "error": {
    "code": "INVALID_STATUS_TRANSITION",
    "message": "Invalid status transition",
    "details": "Cannot change status from 'completed' to 'preparing'"
  }
}
```

## Webhooks

RestoPos can send webhook notifications for order events.

### Order Events

- `order.created`
- `order.updated`
- `order.status_changed`
- `order.cancelled`
- `order.completed`
- `order.payment_received`

### Webhook Payload Example

```json
{
  "event": "order.status_changed",
  "timestamp": "2024-01-15T19:30:00Z",
  "data": {
    "order": {
      "id": 123,
      "order_number": "ORD-2024-001234",
      "status": "ready",
      "previous_status": "preparing",
      "total_amount": 45.05
    }
  }
}
```

### Setting Up Webhooks

Configure webhook endpoints in your RestoPos admin panel under **Settings > Integrations > Webhooks**.

## SDK Examples

::: tabs

== JavaScript

```javascript
// Create a new order
const createOrder = async (orderData) => {
  try {
    const response = await fetch('/api/orders', {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(orderData)
    });
    
    const result = await response.json();
    
    if (result.success) {
      console.log('Order created:', result.data.order);
      return result.data.order;
    } else {
      throw new Error(result.error.message);
    }
  } catch (error) {
    console.error('Failed to create order:', error);
    throw error;
  }
};

// Usage
const newOrder = await createOrder({
  type: 'dine_in',
  table_id: 5,
  items: [
    {
      menu_item_id: 101,
      quantity: 2
    }
  ]
});
```

== PHP

```php
// Create a new order
function createOrder($orderData) {
    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . config('restopos.api_token'),
        'Accept' => 'application/json',
    ])->post(config('restopos.api_url') . '/api/orders', $orderData);

    if ($response->successful()) {
        return $response->json()['data']['order'];
    }

    throw new Exception('Failed to create order: ' . $response->json()['error']['message']);
}

// Usage
$newOrder = createOrder([
    'type' => 'dine_in',
    'table_id' => 5,
    'items' => [
        [
            'menu_item_id' => 101,
            'quantity' => 2
        ]
    ]
]);
```

== Python

```python
import requests

def create_order(order_data, token):
    headers = {
        'Authorization': f'Bearer {token}',
        'Content-Type': 'application/json'
    }
    
    response = requests.post(
        'https://api.restopos.com/api/orders',
        json=order_data,
        headers=headers
    )
    
    if response.status_code == 201:
        return response.json()['data']['order']
    else:
        raise Exception(f"Failed to create order: {response.json()['error']['message']}")

# Usage
new_order = create_order({
    'type': 'dine_in',
    'table_id': 5,
    'items': [
        {
            'menu_item_id': 101,
            'quantity': 2
        }
    ]
}, 'your_token')
```

:::

## Best Practices

### Order Management

1. **Validate Data**: Always validate order data before submission
2. **Handle Errors**: Implement proper error handling for all API calls
3. **Status Tracking**: Monitor order status changes for workflow management
4. **Idempotency**: Use idempotency keys for critical operations
5. **Real-time Updates**: Implement webhooks or polling for real-time order updates

### Performance Optimization

1. **Pagination**: Use pagination for large order lists
2. **Filtering**: Apply appropriate filters to reduce data transfer
3. **Caching**: Cache frequently accessed order data
4. **Batch Operations**: Use batch endpoints when available
5. **Compression**: Enable gzip compression for API responses

### Security Considerations

1. **Authentication**: Always use proper authentication tokens
2. **HTTPS**: Use HTTPS for all API communications
3. **Input Validation**: Validate all input data on both client and server
4. **Rate Limiting**: Respect API rate limits
5. **Sensitive Data**: Never log sensitive payment information