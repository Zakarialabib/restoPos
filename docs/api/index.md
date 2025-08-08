# API Reference

Welcome to the RestoPos API documentation. Our comprehensive RESTful API allows you to integrate RestoPos with your applications and build powerful restaurant management solutions.

<div style="display: flex; gap: 12px; margin: 24px 0;">
  <VersionBadge version="2.1.0" type="stable" :show-label="true" />
  <StatusBadge status="stable" />
</div>

## Overview

The RestoPos API is designed with modern REST principles, providing a clean and intuitive interface for managing all aspects of your restaurant operations.

<div class="features-grid">
  <FeatureCard
    title="RESTful Design"
    description="Clean, predictable URLs and HTTP methods following REST conventions"
    icon="🔗"
    size="small"
    :details="[
      'Intuitive URL structure',
      'Standard HTTP methods',
      'Consistent response format',
      'Proper status codes'
    ]"
  />
  
  <FeatureCard
    title="Comprehensive Coverage"
    description="Complete API coverage for all RestoPos features and functionality"
    icon="📋"
    size="small"
    :details="[
      'Order management',
      'Menu operations',
      'Payment processing',
      'Analytics & reporting'
    ]"
  />
  
  <FeatureCard
    title="Developer Friendly"
    description="Extensive documentation with examples and SDKs for popular languages"
    icon="👨‍💻"
    size="small"
    :details="[
      'Interactive examples',
      'Multiple SDKs',
      'Webhook support',
      'Comprehensive guides'
    ]"
  />
</div>

## Base URL

<ApiEndpoint
  method="BASE"
  url="https://api.restopos.com/v1"
  description="All API endpoints are relative to this base URL"
/>

::: tip API Versioning
We use URL versioning to ensure backward compatibility. The current version is `v1`. When we release breaking changes, we'll introduce a new version (e.g., `v2`) while maintaining support for previous versions.
:::

## Authentication

All API requests require authentication using Bearer tokens. Include your API token in the Authorization header:

<CodeTabs>
<template #curl>

```bash
curl -H "Authorization: Bearer YOUR_API_TOKEN" \
     -H "Content-Type: application/json" \
     https://api.restopos.com/v1/orders
```

</template>
<template #javascript>

```javascript
const response = await fetch("/api/orders", {
  headers: {
    "Authorization": "Bearer YOUR_API_TOKEN",
    "Content-Type": "application/json"
  }
});
```

</template>
<template #php>

```php
$client = new GuzzleHttp\Client();

$response = $client->get("/api/orders", [
    "headers" => [
        "Authorization" => "Bearer YOUR_API_TOKEN",
        "Content-Type" => "application/json"
    ]
]);
```

</template>
<template #python>

```python
import requests

headers = {
    "Authorization": "Bearer YOUR_API_TOKEN",
    "Content-Type": "application/json"
}

response = requests.get("/api/orders", headers=headers)
```

</template>
</CodeTabs>

## API Endpoints

### Core Resources

<div class="features-grid">
  <FeatureCard
    title="Authentication"
    description="Token management and user authentication"
    icon="🔐"
    link="/api/authentication"
    size="small"
    :details="[
      'Token generation',
      'Token refresh',
      'User roles & permissions',
      'Session management'
    ]"
    badge="stable"
  />
  
  <FeatureCard
    title="Orders"
    description="Complete order lifecycle management"
    icon="🛒"
    link="/api/orders"
    size="small"
    :details="[
      'Create & update orders',
      'Order status tracking',
      'Kitchen integration',
      'Order analytics'
    ]"
    badge="stable"
  />
  
  <FeatureCard
    title="Menu"
    description="Menu items, categories, and modifiers"
    icon="📋"
    link="/api/menu"
    size="small"
    :details="[
      'Menu item management',
      'Category organization',
      'Modifier groups',
      'Availability control'
    ]"
    badge="stable"
  />
  
  <FeatureCard
    title="Payments"
    description="Payment processing and financial operations"
    icon="💳"
    link="/api/payments"
    size="small"
    :details="[
      'Payment processing',
      'Refund management',
      'Split payments',
      'Payment methods'
    ]"
    badge="stable"
  />
</div>

### Additional Resources

| Resource | Description | Status |
|----------|-------------|--------|
| **Customers** | Customer management and profiles | <StatusBadge status="stable" /> |
| **Inventory** | Stock management and tracking | <StatusBadge status="beta" /> |
| **Analytics** | Reports and business insights | <StatusBadge status="stable" /> |
| **Webhooks** | Event notifications and integrations | <StatusBadge status="stable" /> |
| **Staff** | Employee management and permissions | <StatusBadge status="stable" /> |
| **Tables** | Table management for dine-in orders | <StatusBadge status="beta" /> |

## Response Format

All API responses follow a consistent JSON structure for predictable integration:

<CodeTabs>
<template #success>

```json
{
  "success": true,
  "data": {
    "id": 123,
    "name": "Margherita Pizza",
    "price": 12.99,
    "created_at": "2024-01-15T10:30:00Z"
  },
  "message": "Menu item retrieved successfully",
  "meta": {
    "timestamp": "2024-01-15T10:30:00Z",
    "version": "2.1.0"
  }
}
```

</template>
<template #paginated>

```json
{
  "success": true,
  "data": [
    {"id": 1, "name": "Item 1"},
    {"id": 2, "name": "Item 2"}
  ],
  "message": "Orders retrieved successfully",
  "meta": {
    "pagination": {
      "current_page": 1,
      "total_pages": 10,
      "per_page": 20,
      "total": 200,
      "has_next": true,
      "has_prev": false
    }
  }
}
```

</template>
<template #error>

```json
{
  "success": false,
  "error": {
    "code": "VALIDATION_ERROR",
    "message": "The given data was invalid.",
    "details": {
      "email": ["The email field is required."],
      "price": ["The price must be a positive number."]
    }
  },
  "meta": {
    "timestamp": "2024-01-15T10:30:00Z",
    "request_id": "req_123456789"
  }
}
```

</template>
</CodeTabs>

## HTTP Status Codes

The API uses standard HTTP status codes to indicate the success or failure of requests:

| Status Code | Meaning | Description |
|-------------|---------|-------------|
| **200** | OK | Request successful |
| **201** | Created | Resource created successfully |
| **204** | No Content | Request successful, no content returned |
| **400** | Bad Request | Invalid request parameters |
| **401** | Unauthorized | Authentication required or invalid |
| **403** | Forbidden | Insufficient permissions |
| **404** | Not Found | Resource not found |
| **422** | Unprocessable Entity | Validation errors |
| **429** | Too Many Requests | Rate limit exceeded |
| **500** | Internal Server Error | Server error |

## Rate Limiting

To ensure fair usage and system stability, API requests are rate limited:

- **Standard Plan**: 1,000 requests per hour
- **Premium Plan**: 5,000 requests per hour
- **Enterprise Plan**: 10,000 requests per hour

Rate limit information is included in response headers:

```http
X-RateLimit-Limit: 1000
X-RateLimit-Remaining: 999
X-RateLimit-Reset: 1640995200
X-RateLimit-Window: 3600
```

::: warning Rate Limit Exceeded
When you exceed your rate limit, you'll receive a `429 Too Many Requests` response. Implement exponential backoff in your applications to handle rate limiting gracefully.
:::

## SDKs and Libraries

We provide official SDKs for popular programming languages:

<div class="features-grid">
  <FeatureCard
    title="JavaScript/Node.js"
    description="Official SDK for JavaScript and Node.js applications"
    icon="🟨"
    link="https://github.com/restopos/restopos-js"
    size="small"
    badge="stable"
  />
  
  <FeatureCard
    title="PHP"
    description="Composer package for PHP applications"
    icon="🐘"
    link="https://github.com/restopos/restopos-php"
    size="small"
    badge="stable"
  />
  
  <FeatureCard
    title="Python"
    description="PyPI package for Python applications"
    icon="🐍"
    link="https://github.com/restopos/restopos-python"
    size="small"
    badge="stable"
  />
  
  <FeatureCard
    title="Go"
    description="Go module for Go applications"
    icon="🐹"
    link="https://github.com/restopos/restopos-go"
    size="small"
    badge="beta"
  />
</div>

## Quick Start Example

Here's a complete example showing how to create a new order using the API:

<CodeTabs>
<template #javascript>

```javascript
const restopos = require("restopos");

const client = new restopos.Client({
  apiKey: "your_api_key",
  baseURL: "https://api.restopos.com/v1"
});

const order = await client.orders.create({
  items: [
    {
      menu_item_id: 123,
      quantity: 2,
      special_instructions: "Extra cheese"
    }
  ],
  customer: {
    name: "John Doe",
    phone: "+1234567890"
  },
  order_type: "delivery",
  delivery_address: "123 Main St, City, State 12345"
});

console.log(`Order created: ${order.id}`);
```

</template>
<template #php>

```php
<?php
require_once "vendor/autoload.php";

use RestoPos\Client;

$client = new Client([
    "api_key" => "your_api_key",
    "base_url" => "https://api.restopos.com/v1"
]);

$order = $client->orders->create([
    "items" => [
        [
            "menu_item_id" => 123,
            "quantity" => 2,
            "special_instructions" => "Extra cheese"
        ]
    ],
    "customer" => [
        "name" => "John Doe",
        "phone" => "+1234567890"
    ],
    "order_type" => "delivery",
    "delivery_address" => "123 Main St, City, State 12345"
]);

echo "Order created: {$order->id}";
```

</template>
<template #python>

```python
import restopos

client = restopos.Client(
    api_key="your_api_key",
    base_url="https://api.restopos.com/v1"
)

order = client.orders.create({
    "items": [
        {
            "menu_item_id": 123,
            "quantity": 2,
            "special_instructions": "Extra cheese"
        }
    ],
    "customer": {
        "name": "John Doe",
        "phone": "+1234567890"
    },
    "order_type": "delivery",
    "delivery_address": "123 Main St, City, State 12345"
})

print(f"Order created: {order['id']}")
```

</template>
</CodeTabs>

## API Testing

We provide multiple ways to test and explore the API:

### Interactive API Explorer

Visit our [Interactive API Explorer](https://api.restopos.com/docs) to test endpoints directly from your browser. No setup required!

### Postman Collection

Download our official [Postman collection](https://api.restopos.com/postman-collection.json) to test the API with pre-configured requests and examples.

### Test Environment

Use our sandbox environment for testing:

```bash
curl -H "Authorization: Bearer test_api_key" \
     -H "Content-Type: application/json" \
     https://sandbox-api.restopos.com/v1/orders
```

## Webhooks

Receive real-time notifications about events in your restaurant:

### Supported Events

- `order.created` - New order placed
- `order.updated` - Order status changed
- `order.completed` - Order completed
- `payment.successful` - Payment processed
- `payment.failed` - Payment failed

### Webhook Configuration

Configure webhooks in your [dashboard settings](https://app.restopos.com/settings/webhooks):

```json
{
  "url": "https://your-app.com/webhooks/restopos",
  "events": ["order.created", "order.completed"],
  "secret": "your_webhook_secret"
}
```

## Support & Resources

### Getting Help

- **Documentation**: Comprehensive guides and examples
- **Community**: Join our [Discord community](https://discord.gg/restopos)
- **Support**: Email support@restopos.com or use live chat
- **Status**: Check [system status](https://status.restopos.com)

### Rate Limits & Best Practices

- Implement exponential backoff for rate limiting
- Cache responses when appropriate
- Use webhooks instead of polling
- Validate data before sending requests
- Handle errors gracefully in your applications

### SDK Examples

Check out our [example applications](https://github.com/restopos/examples) for complete integration examples in various programming languages.

---

Ready to get started? [Create your API key](https://app.restopos.com/settings/api) and start building amazing restaurant experiences! 🚀