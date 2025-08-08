# Payments API

The Payments API allows you to process payments, manage payment methods, handle refunds, and track payment transactions through the RestoPos system.

## Payment Object

The payment object represents a financial transaction for an order.

### Payment Structure

```json
{
  "id": 301,
  "order_id": 123,
  "payment_method": "card",
  "payment_provider": "stripe",
  "amount": 45.05,
  "currency": "USD",
  "status": "completed",
  "transaction_id": "txn_1234567890",
  "reference_number": "REF-2024-001234",
  "gateway_response": {
    "id": "pi_1234567890",
    "status": "succeeded",
    "receipt_url": "https://pay.stripe.com/receipts/..."
  },
  "metadata": {
    "tip_amount": 5.00,
    "tax_amount": 4.05,
    "discount_amount": 2.00,
    "card_last_four": "4242",
    "card_brand": "visa"
  },
  "processed_at": "2024-01-15T19:35:00Z",
  "created_at": "2024-01-15T19:34:30Z",
  "updated_at": "2024-01-15T19:35:00Z",
  "order": {
    "id": 123,
    "order_number": "ORD-2024-001234",
    "total_amount": 45.05
  },
  "refunds": [
    {
      "id": 401,
      "amount": 10.00,
      "reason": "Item unavailable",
      "status": "completed",
      "processed_at": "2024-01-15T20:00:00Z"
    }
  ]
}
```

### Payment Status Values

| Status | Description |
|--------|-------------|
| `pending` | Payment initiated but not processed |
| `processing` | Payment being processed by gateway |
| `completed` | Payment successfully completed |
| `failed` | Payment failed |
| `cancelled` | Payment was cancelled |
| `refunded` | Payment was fully refunded |
| `partially_refunded` | Payment was partially refunded |

### Payment Methods

| Method | Description |
|--------|-------------|
| `cash` | Cash payment |
| `card` | Credit/debit card payment |
| `digital_wallet` | Apple Pay, Google Pay, etc. |
| `bank_transfer` | Bank transfer payment |
| `gift_card` | Gift card payment |
| `loyalty_points` | Loyalty points redemption |
| `split` | Split payment across multiple methods |

## List Payments

Retrieve a list of payments with optional filtering and pagination.

**Endpoint**: `GET /api/payments`

### Query Parameters

| Parameter | Type | Description |
|-----------|------|-------------|
| `page` | integer | Page number (default: 1) |
| `per_page` | integer | Items per page (default: 15, max: 100) |
| `status` | string | Filter by payment status |
| `payment_method` | string | Filter by payment method |
| `order_id` | integer | Filter by order ID |
| `date_from` | date | Filter payments from date (YYYY-MM-DD) |
| `date_to` | date | Filter payments to date (YYYY-MM-DD) |
| `amount_min` | number | Minimum payment amount |
| `amount_max` | number | Maximum payment amount |
| `search` | string | Search in reference number or transaction ID |
| `sort` | string | Sort field (id, amount, processed_at) |
| `order` | string | Sort direction (asc, desc) |

### Example Request

```http
GET /api/payments?status=completed&payment_method=card&date_from=2024-01-01
Authorization: Bearer your_token
```

### Example Response

```json
{
  "success": true,
  "data": {
    "payments": [
      {
        "id": 301,
        "order_id": 123,
        "payment_method": "card",
        "amount": 45.05,
        "currency": "USD",
        "status": "completed",
        "transaction_id": "txn_1234567890",
        "processed_at": "2024-01-15T19:35:00Z",
        "order": {
          "order_number": "ORD-2024-001234"
        }
      }
    ],
    "pagination": {
      "current_page": 1,
      "per_page": 15,
      "total": 250,
      "total_pages": 17,
      "has_next_page": true,
      "has_prev_page": false
    }
  }
}
```

## Get Payment

Retrieve a specific payment by ID.

**Endpoint**: `GET /api/payments/{id}`

### Example Request

```http
GET /api/payments/301
Authorization: Bearer your_token
```

### Example Response

```json
{
  "success": true,
  "data": {
    "payment": {
      "id": 301,
      "order_id": 123,
      "payment_method": "card",
      "payment_provider": "stripe",
      "amount": 45.05,
      "currency": "USD",
      "status": "completed",
      "transaction_id": "txn_1234567890",
      "reference_number": "REF-2024-001234",
      "gateway_response": {...},
      "metadata": {...},
      "processed_at": "2024-01-15T19:35:00Z",
      "created_at": "2024-01-15T19:34:30Z",
      "updated_at": "2024-01-15T19:35:00Z",
      "order": {...},
      "refunds": [...]
    }
  }
}
```

## Process Payment

Process a payment for an order.

**Endpoint**: `POST /api/payments`

### Request Body

```json
{
  "order_id": 123,
  "payment_method": "card",
  "amount": 45.05,
  "currency": "USD",
  "payment_details": {
    "card_token": "tok_1234567890",
    "save_card": false,
    "customer_id": 456
  },
  "metadata": {
    "tip_amount": 5.00,
    "tax_amount": 4.05,
    "discount_amount": 2.00
  }
}
```

### Required Fields

- `order_id`: Order ID to process payment for
- `payment_method`: Payment method (card, cash, digital_wallet, etc.)
- `amount`: Payment amount
- `currency`: Payment currency

### Payment Method Specific Fields

#### Card Payments

```json
{
  "payment_method": "card",
  "payment_details": {
    "card_token": "tok_1234567890",
    "save_card": false,
    "customer_id": 456,
    "billing_address": {
      "line1": "123 Main St",
      "city": "New York",
      "state": "NY",
      "postal_code": "10001",
      "country": "US"
    }
  }
}
```

#### Cash Payments

```json
{
  "payment_method": "cash",
  "payment_details": {
    "amount_received": 50.00,
    "change_given": 4.95,
    "register_id": "REG-001"
  }
}
```

#### Digital Wallet Payments

```json
{
  "payment_method": "digital_wallet",
  "payment_details": {
    "wallet_type": "apple_pay",
    "payment_token": "wallet_token_123"
  }
}
```

### Example Request

```http
POST /api/payments
Authorization: Bearer your_token
Content-Type: application/json

{
  "order_id": 123,
  "payment_method": "card",
  "amount": 45.05,
  "currency": "USD",
  "payment_details": {
    "card_token": "tok_1234567890",
    "save_card": false
  },
  "metadata": {
    "tip_amount": 5.00
  }
}
```

### Example Response

```json
{
  "success": true,
  "message": "Payment processed successfully",
  "data": {
    "payment": {
      "id": 302,
      "order_id": 123,
      "payment_method": "card",
      "amount": 45.05,
      "currency": "USD",
      "status": "completed",
      "transaction_id": "txn_1234567891",
      "reference_number": "REF-2024-001235",
      "processed_at": "2024-01-15T20:00:00Z",
      "metadata": {
        "tip_amount": 5.00,
        "card_last_four": "4242",
        "card_brand": "visa"
      }
    }
  }
}
```

## Split Payment

Process a split payment across multiple payment methods.

**Endpoint**: `POST /api/payments/split`

### Request Body

```json
{
  "order_id": 123,
  "payments": [
    {
      "payment_method": "card",
      "amount": 30.00,
      "payment_details": {
        "card_token": "tok_1234567890"
      }
    },
    {
      "payment_method": "cash",
      "amount": 15.05,
      "payment_details": {
        "amount_received": 20.00,
        "change_given": 4.95
      }
    }
  ]
}
```

### Example Request

```http
POST /api/payments/split
Authorization: Bearer your_token
Content-Type: application/json

{
  "order_id": 124,
  "payments": [
    {
      "payment_method": "gift_card",
      "amount": 25.00,
      "payment_details": {
        "gift_card_number": "GC123456789"
      }
    },
    {
      "payment_method": "card",
      "amount": 20.05,
      "payment_details": {
        "card_token": "tok_9876543210"
      }
    }
  ]
}
```

### Example Response

```json
{
  "success": true,
  "message": "Split payment processed successfully",
  "data": {
    "payments": [
      {
        "id": 303,
        "payment_method": "gift_card",
        "amount": 25.00,
        "status": "completed",
        "transaction_id": "gc_1234567890"
      },
      {
        "id": 304,
        "payment_method": "card",
        "amount": 20.05,
        "status": "completed",
        "transaction_id": "txn_1234567892"
      }
    ],
    "total_amount": 45.05,
    "order_status": "paid"
  }
}
```

## Refund Payment

Process a full or partial refund for a payment.

**Endpoint**: `POST /api/payments/{id}/refund`

### Request Body

```json
{
  "amount": 10.00,
  "reason": "Item unavailable",
  "notify_customer": true,
  "metadata": {
    "refunded_items": ["Appetizer"],
    "staff_id": 2
  }
}
```

### Optional Fields

- `amount`: Refund amount (defaults to full payment amount)
- `reason`: Reason for refund
- `notify_customer`: Send refund notification to customer
- `metadata`: Additional refund metadata

### Example Request

```http
POST /api/payments/301/refund
Authorization: Bearer your_token
Content-Type: application/json

{
  "amount": 15.00,
  "reason": "Customer complaint - food quality",
  "notify_customer": true
}
```

### Example Response

```json
{
  "success": true,
  "message": "Refund processed successfully",
  "data": {
    "refund": {
      "id": 401,
      "payment_id": 301,
      "amount": 15.00,
      "reason": "Customer complaint - food quality",
      "status": "completed",
      "transaction_id": "re_1234567890",
      "processed_at": "2024-01-15T20:30:00Z"
    },
    "payment": {
      "id": 301,
      "status": "partially_refunded",
      "refunded_amount": 15.00,
      "remaining_amount": 30.05
    }
  }
}
```

## Void Payment

Void a payment (cancel before settlement).

**Endpoint**: `POST /api/payments/{id}/void`

### Request Body

```json
{
  "reason": "Order cancelled by customer"
}
```

### Example Request

```http
POST /api/payments/302/void
Authorization: Bearer your_token
Content-Type: application/json

{
  "reason": "Duplicate payment"
}
```

### Example Response

```json
{
  "success": true,
  "message": "Payment voided successfully",
  "data": {
    "payment": {
      "id": 302,
      "status": "cancelled",
      "void_reason": "Duplicate payment",
      "voided_at": "2024-01-15T20:45:00Z"
    }
  }
}
```

## Payment Methods Management

Manage available payment methods and their configurations.

### List Payment Methods

**Endpoint**: `GET /api/payment-methods`

### Example Request

```http
GET /api/payment-methods
Authorization: Bearer your_token
```

### Example Response

```json
{
  "success": true,
  "data": {
    "payment_methods": [
      {
        "id": 1,
        "name": "Credit/Debit Card",
        "type": "card",
        "provider": "stripe",
        "is_active": true,
        "configuration": {
          "accepted_cards": ["visa", "mastercard", "amex"],
          "require_cvv": true,
          "save_cards": true
        }
      },
      {
        "id": 2,
        "name": "Cash",
        "type": "cash",
        "is_active": true,
        "configuration": {
          "require_exact_change": false,
          "max_amount": 500.00
        }
      },
      {
        "id": 3,
        "name": "Apple Pay",
        "type": "digital_wallet",
        "provider": "stripe",
        "is_active": true,
        "configuration": {
          "wallet_type": "apple_pay"
        }
      }
    ]
  }
}
```

### Update Payment Method

**Endpoint**: `PUT /api/payment-methods/{id}`

### Request Body

```json
{
  "is_active": false,
  "configuration": {
    "max_amount": 1000.00
  }
}
```

## Payment Analytics

Get payment analytics and reporting data.

**Endpoint**: `GET /api/payments/analytics`

### Query Parameters

| Parameter | Type | Description |
|-----------|------|-------------|
| `period` | string | Time period (today, week, month, year, custom) |
| `date_from` | date | Start date for custom period |
| `date_to` | date | End date for custom period |
| `payment_method` | string | Filter by payment method |
| `group_by` | string | Group results by (hour, day, week, month) |

### Example Request

```http
GET /api/payments/analytics?period=month&group_by=day
Authorization: Bearer your_token
```

### Example Response

```json
{
  "success": true,
  "data": {
    "summary": {
      "total_payments": 1250,
      "total_amount": 28750.50,
      "average_payment": 23.00,
      "successful_payments": 1180,
      "failed_payments": 45,
      "refunded_payments": 25,
      "success_rate": 96.4,
      "total_refunds": 1250.00
    },
    "by_payment_method": {
      "card": {
        "count": 850,
        "amount": 19550.00,
        "percentage": 68.0
      },
      "cash": {
        "count": 300,
        "amount": 6750.00,
        "percentage": 23.5
      },
      "digital_wallet": {
        "count": 100,
        "amount": 2450.50,
        "percentage": 8.5
      }
    },
    "daily_breakdown": [
      {
        "date": "2024-01-01",
        "payments_count": 45,
        "total_amount": 1035.50,
        "average_amount": 23.01
      },
      {
        "date": "2024-01-02",
        "payments_count": 52,
        "total_amount": 1196.00,
        "average_amount": 23.00
      }
    ],
    "failure_analysis": {
      "declined_cards": 25,
      "insufficient_funds": 12,
      "expired_cards": 5,
      "network_errors": 3
    }
  }
}
```

## Payment Gateway Integration

Endpoints for payment gateway management and configuration.

### Test Payment Gateway

**Endpoint**: `POST /api/payment-gateways/test`

### Request Body

```json
{
  "provider": "stripe",
  "test_amount": 1.00,
  "currency": "USD"
}
```

### Example Request

```http
POST /api/payment-gateways/test
Authorization: Bearer your_token
Content-Type: application/json

{
  "provider": "stripe",
  "test_amount": 1.00,
  "currency": "USD"
}
```

### Example Response

```json
{
  "success": true,
  "message": "Payment gateway test successful",
  "data": {
    "provider": "stripe",
    "test_result": {
      "status": "success",
      "transaction_id": "test_txn_123",
      "response_time": 245,
      "gateway_status": "operational"
    }
  }
}
```

## Webhooks

Payment-related webhook events.

### Payment Events

- `payment.processing`
- `payment.completed`
- `payment.failed`
- `payment.refunded`
- `payment.voided`
- `payment.chargeback`

### Webhook Payload Example

```json
{
  "event": "payment.completed",
  "timestamp": "2024-01-15T19:35:00Z",
  "data": {
    "payment": {
      "id": 301,
      "order_id": 123,
      "amount": 45.05,
      "currency": "USD",
      "payment_method": "card",
      "status": "completed",
      "transaction_id": "txn_1234567890"
    }
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
    "code": "INVALID_PAYMENT_DATA",
    "message": "Invalid payment data provided",
    "details": {
      "amount": ["Amount must be greater than 0"],
      "payment_method": ["Payment method is required"]
    }
  }
}
```

#### 402 Payment Required

```json
{
  "success": false,
  "error": {
    "code": "PAYMENT_DECLINED",
    "message": "Payment was declined",
    "details": "Your card was declined. Please try a different payment method.",
    "decline_code": "insufficient_funds"
  }
}
```

#### 409 Conflict

```json
{
  "success": false,
  "error": {
    "code": "PAYMENT_ALREADY_PROCESSED",
    "message": "Payment already processed",
    "details": "This order has already been paid for"
  }
}
```

## SDK Examples

::: tabs

== JavaScript

```javascript
class PaymentsAPI {
  constructor(baseURL, token) {
    this.baseURL = baseURL;
    this.token = token;
  }

  async processPayment(paymentData) {
    try {
      const response = await fetch(`${this.baseURL}/api/payments`, {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${this.token}`,
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(paymentData)
      });
      
      const result = await response.json();
      
      if (result.success) {
        return result.data.payment;
      } else {
        throw new Error(result.error.message);
      }
    } catch (error) {
      console.error('Payment processing failed:', error);
      throw error;
    }
  }

  async refundPayment(paymentId, refundData) {
    const response = await fetch(`${this.baseURL}/api/payments/${paymentId}/refund`, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${this.token}`,
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(refundData)
    });
    
    return response.json();
  }

  async getPaymentAnalytics(period = 'month') {
    const response = await fetch(`${this.baseURL}/api/payments/analytics?period=${period}`, {
      headers: {
        'Authorization': `Bearer ${this.token}`,
        'Accept': 'application/json'
      }
    });
    
    return response.json();
  }
}

// Usage
const paymentsAPI = new PaymentsAPI('https://api.restopos.com', 'your_token');

// Process card payment
const payment = await paymentsAPI.processPayment({
  order_id: 123,
  payment_method: 'card',
  amount: 45.05,
  currency: 'USD',
  payment_details: {
    card_token: 'tok_1234567890'
  }
});

// Process refund
const refund = await paymentsAPI.refundPayment(301, {
  amount: 10.00,
  reason: 'Customer request'
});

// Get analytics
const analytics = await paymentsAPI.getPaymentAnalytics('week');
console.log(`Success rate: ${analytics.data.summary.success_rate}%`);
```

== PHP

```php
class PaymentsAPI
{
    private $baseUrl;
    private $token;

    public function __construct($baseUrl, $token)
    {
        $this->baseUrl = $baseUrl;
        $this->token = $token;
    }

    public function processPayment($paymentData)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ])->post($this->baseUrl . '/api/payments', $paymentData);

        if ($response->successful()) {
            return $response->json()['data']['payment'];
        }

        throw new Exception('Payment failed: ' . $response->json()['error']['message']);
    }

    public function processSplitPayment($orderid, $payments)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ])->post($this->baseUrl . '/api/payments/split', [
            'order_id' => $orderid,
            'payments' => $payments
        ]);

        return $response->json();
    }

    public function refundPayment($paymentId, $refundData)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ])->post($this->baseUrl . "/api/payments/{$paymentId}/refund", $refundData);

        return $response->json();
    }
}

// Usage
$paymentsAPI = new PaymentsAPI('https://api.restopos.com', 'your_token');

// Process cash payment
$payment = $paymentsAPI->processPayment([
    'order_id' => 123,
    'payment_method' => 'cash',
    'amount' => 45.05,
    'currency' => 'USD',
    'payment_details' => [
        'amount_received' => 50.00,
        'change_given' => 4.95
    ]
]);

// Process split payment
$splitPayment = $paymentsAPI->processSplitPayment(124, [
    [
        'payment_method' => 'card',
        'amount' => 30.00,
        'payment_details' => ['card_token' => 'tok_123']
    ],
    [
        'payment_method' => 'cash',
        'amount' => 15.05,
        'payment_details' => ['amount_received' => 20.00]
    ]
]);
```

== Python

```python
import requests
from typing import Dict, List, Optional

class PaymentsAPI:
    def __init__(self, base_url: str, token: str):
        self.base_url = base_url
        self.token = token
        self.headers = {
            'Authorization': f'Bearer {token}',
            'Content-Type': 'application/json'
        }

    def process_payment(self, payment_data: Dict) -> Dict:
        response = requests.post(
            f'{self.base_url}/api/payments',
            json=payment_data,
            headers=self.headers
        )
        
        if response.status_code == 201:
            return response.json()['data']['payment']
        else:
            raise Exception(f"Payment failed: {response.json()['error']['message']}")

    def refund_payment(self, payment_id: int, refund_data: Dict) -> Dict:
        response = requests.post(
            f'{self.base_url}/api/payments/{payment_id}/refund',
            json=refund_data,
            headers=self.headers
        )
        return response.json()

    def get_payment_analytics(self, period: str = 'month') -> Dict:
        response = requests.get(
            f'{self.base_url}/api/payments/analytics',
            params={'period': period},
            headers=self.headers
        )
        return response.json()

    def test_gateway(self, provider: str) -> Dict:
        response = requests.post(
            f'{self.base_url}/api/payment-gateways/test',
            json={
                'provider': provider,
                'test_amount': 1.00,
                'currency': 'USD'
            },
            headers=self.headers
        )
        return response.json()

# Usage
payments_api = PaymentsAPI('https://api.restopos.com', 'your_token')

# Process digital wallet payment
payment = payments_api.process_payment({
    'order_id': 123,
    'payment_method': 'digital_wallet',
    'amount': 45.05,
    'currency': 'USD',
    'payment_details': {
        'wallet_type': 'apple_pay',
        'payment_token': 'wallet_token_123'
    }
})

# Get payment analytics
analytics = payments_api.get_payment_analytics('week')
print(f"Total payments: {analytics['data']['summary']['total_payments']}")
print(f"Success rate: {analytics['data']['summary']['success_rate']}%")

# Test payment gateway
test_result = payments_api.test_gateway('stripe')
print(f"Gateway test: {test_result['data']['test_result']['status']}")
```

:::

## Best Practices

### Payment Security

1. **PCI Compliance**: Ensure PCI DSS compliance for card payments
2. **Token Usage**: Use payment tokens instead of raw card data
3. **HTTPS Only**: Always use HTTPS for payment processing
4. **Data Encryption**: Encrypt sensitive payment data
5. **Audit Logging**: Log all payment transactions for auditing

### Error Handling

1. **Graceful Failures**: Handle payment failures gracefully
2. **Retry Logic**: Implement retry logic for transient failures
3. **User Feedback**: Provide clear error messages to users
4. **Fallback Methods**: Offer alternative payment methods
5. **Timeout Handling**: Set appropriate timeouts for payment requests

### Performance Optimization

1. **Async Processing**: Use asynchronous payment processing where possible
2. **Caching**: Cache payment method configurations
3. **Connection Pooling**: Use connection pooling for gateway requests
4. **Monitoring**: Monitor payment gateway performance
5. **Load Balancing**: Distribute payment processing load

### Compliance and Reporting

1. **Transaction Records**: Maintain detailed transaction records
2. **Reconciliation**: Implement daily payment reconciliation
3. **Reporting**: Generate regular payment reports
4. **Dispute Management**: Handle chargebacks and disputes promptly
5. **Tax Compliance**: Ensure proper tax calculation and reporting