# Authentication

RestoPos API uses token-based authentication to secure API endpoints. This guide covers authentication methods, token management, and security best practices.

## Authentication Methods

RestoPos supports multiple authentication methods depending on your use case:

### 1. API Token Authentication

Recommended for server-to-server integrations and third-party applications.

#### Obtaining an API Token

**Endpoint**: `POST /api/auth/token`

```http
POST /api/auth/token
Content-Type: application/json

{
  "email": "admin@restopos.com",
  "password": "your_password",
  "device_name": "POS Terminal 1"
}
```

**Response**:
```json
{
  "success": true,
  "data": {
    "token": "1|abc123def456ghi789jkl012mno345pqr678stu901vwx234yz",
    "token_type": "Bearer",
    "expires_at": "2024-12-31T23:59:59.000000Z",
    "user": {
      "id": 1,
      "name": "Admin User",
      "email": "admin@restopos.com",
      "role": "admin"
    }
  }
}
```

#### Using the Token

Include the token in the `Authorization` header:

```http
GET /api/orders
Authorization: Bearer 1|abc123def456ghi789jkl012mno345pqr678stu901vwx234yz
Content-Type: application/json
```

### 2. Session Authentication

Used for web-based applications and same-origin requests.

#### Login

**Endpoint**: `POST /api/auth/login`

```http
POST /api/auth/login
Content-Type: application/json

{
  "email": "staff@restopos.com",
  "password": "staff_password",
  "remember": true
}
```

**Response**:
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "user": {
      "id": 2,
      "name": "Staff Member",
      "email": "staff@restopos.com",
      "role": "cashier",
      "permissions": [
        "orders.create",
        "orders.view",
        "payments.process"
      ]
    }
  }
}
```

### 3. OAuth 2.0 (Coming Soon)

For third-party integrations requiring user consent.

## Token Management

### Token Scopes

Tokens can be created with specific scopes to limit access:

```http
POST /api/auth/token
Content-Type: application/json

{
  "email": "api@restopos.com",
  "password": "api_password",
  "device_name": "Integration App",
  "scopes": [
    "orders:read",
    "orders:write",
    "menu:read"
  ]
}
```

#### Available Scopes

| Scope | Description |
|-------|-------------|
| `orders:read` | View orders |
| `orders:write` | Create and modify orders |
| `menu:read` | View menu items |
| `menu:write` | Modify menu items |
| `customers:read` | View customer data |
| `customers:write` | Modify customer data |
| `reports:read` | Access reports |
| `settings:read` | View settings |
| `settings:write` | Modify settings |
| `*` | Full access (admin only) |

### Token Expiration

Tokens have configurable expiration times:

- **Default**: 30 days
- **Maximum**: 1 year
- **Minimum**: 1 hour

#### Refreshing Tokens

**Endpoint**: `POST /api/auth/refresh`

```http
POST /api/auth/refresh
Authorization: Bearer your_current_token
```

**Response**:
```json
{
  "success": true,
  "data": {
    "token": "2|new_token_string_here",
    "expires_at": "2024-12-31T23:59:59.000000Z"
  }
}
```

### Revoking Tokens

#### Revoke Current Token

**Endpoint**: `POST /api/auth/logout`

```http
POST /api/auth/logout
Authorization: Bearer your_token
```

#### Revoke All Tokens

**Endpoint**: `POST /api/auth/logout-all`

```http
POST /api/auth/logout-all
Authorization: Bearer your_token
```

#### Revoke Specific Token

**Endpoint**: `DELETE /api/auth/tokens/{token_id}`

```http
DELETE /api/auth/tokens/123
Authorization: Bearer your_admin_token
```

## User Roles and Permissions

### Role Hierarchy

```
Admin
├── Manager
│   ├── Cashier
│   ├── Waiter
│   └── Kitchen Staff
└── API User
```

### Permission Matrix

| Permission | Admin | Manager | Cashier | Waiter | Kitchen | API |
|------------|-------|---------|---------|--------|---------|-----|
| View Orders | ✅ | ✅ | ✅ | ✅ | ✅ | 🔒 |
| Create Orders | ✅ | ✅ | ✅ | ✅ | ❌ | 🔒 |
| Modify Orders | ✅ | ✅ | ✅ | ✅ | ❌ | 🔒 |
| Cancel Orders | ✅ | ✅ | ✅ | ❌ | ❌ | 🔒 |
| Process Payments | ✅ | ✅ | ✅ | ❌ | ❌ | 🔒 |
| View Reports | ✅ | ✅ | ❌ | ❌ | ❌ | 🔒 |
| Manage Menu | ✅ | ✅ | ❌ | ❌ | ❌ | 🔒 |
| Manage Staff | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| System Settings | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |

🔒 = Scope-dependent

## Error Handling

### Authentication Errors

#### 401 Unauthorized

```json
{
  "success": false,
  "error": {
    "code": "UNAUTHORIZED",
    "message": "Invalid or expired token",
    "details": "The provided authentication token is invalid or has expired"
  }
}
```

#### 403 Forbidden

```json
{
  "success": false,
  "error": {
    "code": "FORBIDDEN",
    "message": "Insufficient permissions",
    "details": "Your account does not have permission to access this resource",
    "required_permission": "orders.create"
  }
}
```

#### 422 Validation Error

```json
{
  "success": false,
  "error": {
    "code": "VALIDATION_ERROR",
    "message": "The given data was invalid",
    "details": {
      "email": ["The email field is required."],
      "password": ["The password field is required."]
    }
  }
}
```

## Security Best Practices

### Token Security

1. **Store Securely**: Never store tokens in plain text
2. **Use HTTPS**: Always use encrypted connections
3. **Rotate Regularly**: Implement token rotation
4. **Limit Scope**: Use minimal required permissions
5. **Monitor Usage**: Track token usage patterns

### Implementation Examples

::: tabs

== JavaScript/Node.js

```javascript
class RestoposAPI {
  constructor(baseURL, token) {
    this.baseURL = baseURL;
    this.token = token;
  }

  async request(endpoint, options = {}) {
    const url = `${this.baseURL}${endpoint}`;
    const config = {
      headers: {
        'Authorization': `Bearer ${this.token}`,
        'Content-Type': 'application/json',
        ...options.headers
      },
      ...options
    };

    try {
      const response = await fetch(url, config);
      
      if (response.status === 401) {
        // Token expired, refresh or re-authenticate
        await this.refreshToken();
        return this.request(endpoint, options);
      }

      return await response.json();
    } catch (error) {
      console.error('API request failed:', error);
      throw error;
    }
  }

  async refreshToken() {
    // Implement token refresh logic
  }
}

// Usage
const api = new RestoposAPI('https://api.restopos.com', 'your_token');
const orders = await api.request('/api/orders');
```

== PHP/Laravel

```php
use Illuminate\Support\Facades\Http;

class RestoposClient
{
    private $baseUrl;
    private $token;

    public function __construct($baseUrl, $token)
    {
        $this->baseUrl = $baseUrl;
        $this->token = $token;
    }

    public function request($endpoint, $method = 'GET', $data = [])
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ])->{strtolower($method)}($this->baseUrl . $endpoint, $data);

        if ($response->status() === 401) {
            // Handle token expiration
            $this->refreshToken();
            return $this->request($endpoint, $method, $data);
        }

        return $response->json();
    }

    private function refreshToken()
    {
        // Implement token refresh logic
    }
}

// Usage
$client = new RestoposClient('https://api.restopos.com', 'your_token');
$orders = $client->request('/api/orders');
```

== Python

```python
import requests
from typing import Optional, Dict, Any

class RestoposAPI:
    def __init__(self, base_url: str, token: str):
        self.base_url = base_url
        self.token = token
        self.session = requests.Session()
        self.session.headers.update({
            'Authorization': f'Bearer {token}',
            'Content-Type': 'application/json'
        })

    def request(self, endpoint: str, method: str = 'GET', 
                data: Optional[Dict[str, Any]] = None) -> Dict[str, Any]:
        url = f"{self.base_url}{endpoint}"
        
        try:
            response = self.session.request(method, url, json=data)
            
            if response.status_code == 401:
                # Token expired, refresh
                self.refresh_token()
                response = self.session.request(method, url, json=data)
            
            response.raise_for_status()
            return response.json()
            
        except requests.exceptions.RequestException as e:
            print(f"API request failed: {e}")
            raise

    def refresh_token(self):
        # Implement token refresh logic
        pass

# Usage
api = RestoposAPI('https://api.restopos.com', 'your_token')
orders = api.request('/api/orders')
```

== cURL

```bash
#!/bin/bash

# Set variables
BASE_URL="https://api.restopos.com"
TOKEN="your_token_here"

# Function to make authenticated requests
api_request() {
    local endpoint=$1
    local method=${2:-GET}
    local data=${3:-}
    
    curl -X "$method" \
        -H "Authorization: Bearer $TOKEN" \
        -H "Content-Type: application/json" \
        -H "Accept: application/json" \
        ${data:+-d "$data"} \
        "$BASE_URL$endpoint"
}

# Examples
api_request "/api/orders"
api_request "/api/orders" "POST" '{"table_id": 1, "items": [...]}'
```

:::

## Rate Limiting

API requests are rate-limited to prevent abuse:

- **Default**: 60 requests per minute
- **Authenticated**: 100 requests per minute
- **Premium**: 500 requests per minute

### Rate Limit Headers

```http
X-RateLimit-Limit: 100
X-RateLimit-Remaining: 95
X-RateLimit-Reset: 1640995200
```

### Rate Limit Exceeded

```json
{
  "success": false,
  "error": {
    "code": "RATE_LIMIT_EXCEEDED",
    "message": "Too many requests",
    "details": "Rate limit exceeded. Try again in 60 seconds.",
    "retry_after": 60
  }
}
```

## Webhooks Authentication

For webhook endpoints, RestoPos uses HMAC-SHA256 signatures:

### Verifying Webhook Signatures

```javascript
const crypto = require('crypto');

function verifyWebhookSignature(payload, signature, secret) {
  const expectedSignature = crypto
    .createHmac('sha256', secret)
    .update(payload)
    .digest('hex');
    
  return crypto.timingSafeEqual(
    Buffer.from(signature, 'hex'),
    Buffer.from(expectedSignature, 'hex')
  );
}

// Usage
const isValid = verifyWebhookSignature(
  req.body,
  req.headers['x-restopos-signature'],
  process.env.WEBHOOK_SECRET
);
```

## Testing Authentication

Use the authentication test endpoint to verify your setup:

**Endpoint**: `GET /api/auth/user`

```http
GET /api/auth/user
Authorization: Bearer your_token
```

**Response**:
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "API User",
    "email": "api@restopos.com",
    "role": "admin",
    "permissions": ["*"],
    "token_expires_at": "2024-12-31T23:59:59.000000Z"
  }
}
```

This endpoint confirms your token is valid and shows your current permissions.