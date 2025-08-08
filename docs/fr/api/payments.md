# API Paiements

L'API Paiements RestoPos gère le traitement des paiements, les remboursements et l'intégration avec diverses passerelles de paiement. Cette API prend en charge plusieurs méthodes de paiement et fournit des fonctionnalités complètes de reporting.

## Objet Paiement

```json
{
  "id": 456,
  "payment_number": "PAY-2024-001",
  "order_id": 123,
  "amount": 31.18,
  "currency": "EUR",
  "status": "completed",
  "method": "card",
  "gateway": "stripe",
  "gateway_transaction_id": "pi_1234567890abcdef",
  "reference_number": "REF-789456",
  "customer": {
    "id": 789,
    "name": "Jean Dupont",
    "email": "jean@example.com"
  },
  "payment_details": {
    "card_last_four": "4242",
    "card_brand": "visa",
    "card_exp_month": 12,
    "card_exp_year": 2025,
    "receipt_url": "https://pay.stripe.com/receipts/abc123"
  },
  "fees": {
    "processing_fee": 0.93,
    "platform_fee": 0.31,
    "total_fees": 1.24
  },
  "refunds": [
    {
      "id": 101,
      "amount": 5.00,
      "reason": "Article non disponible",
      "status": "completed",
      "created_at": "2024-01-15T11:30:00Z"
    }
  ],
  "metadata": {
    "table_number": "5",
    "server_id": "10",
    "tip_amount": "3.50"
  },
  "processed_at": "2024-01-15T10:35:00Z",
  "created_at": "2024-01-15T10:30:00Z",
  "updated_at": "2024-01-15T11:30:00Z"
}
```

### Valeurs de statut

| Statut | Description |
|--------|-------------|
| `pending` | Paiement initié, en attente de traitement |
| `processing` | En cours de traitement par la passerelle |
| `completed` | Paiement réussi et confirmé |
| `failed` | Paiement échoué |
| `cancelled` | Paiement annulé |
| `refunded` | Paiement entièrement remboursé |
| `partially_refunded` | Paiement partiellement remboursé |

### Méthodes de paiement

| Méthode | Description |
|---------|-------------|
| `cash` | Paiement en espèces |
| `card` | Carte de crédit/débit |
| `digital_wallet` | Portefeuille numérique (Apple Pay, Google Pay) |
| `bank_transfer` | Virement bancaire |
| `gift_card` | Carte cadeau |
| `loyalty_points` | Points de fidélité |
| `split` | Paiement partagé (plusieurs méthodes) |

## Points de terminaison

### Lister les paiements

**Point de terminaison** : `GET /api/payments`

**Paramètres de requête** :
- `status` (string) : Filtrer par statut
- `method` (string) : Filtrer par méthode de paiement
- `order_id` (integer) : Filtrer par commande
- `customer_id` (integer) : Filtrer par client
- `date_from` (date) : Date de début (YYYY-MM-DD)
- `date_to` (date) : Date de fin (YYYY-MM-DD)
- `min_amount` (decimal) : Montant minimum
- `max_amount` (decimal) : Montant maximum
- `page` (integer) : Numéro de page (défaut : 1)
- `per_page` (integer) : Éléments par page (défaut : 15, max : 100)
- `sort` (string) : Champ de tri (amount|created_at|processed_at)
- `order` (string) : Direction du tri (asc|desc, défaut : desc)

**Exemple de requête** :
```http
GET /api/payments?status=completed&method=card&date_from=2024-01-01&page=1
Authorization: Bearer votre_jeton
```

**Réponse** :
```json
{
  "success": true,
  "data": {
    "payments": [
      {
        "id": 456,
        "payment_number": "PAY-2024-001",
        "order_id": 123,
        "amount": 31.18,
        "currency": "EUR",
        "status": "completed",
        "method": "card",
        "processed_at": "2024-01-15T10:35:00Z",
        "created_at": "2024-01-15T10:30:00Z"
      }
    ],
    "pagination": {
      "current_page": 1,
      "per_page": 15,
      "total": 250,
      "last_page": 17,
      "from": 1,
      "to": 15
    }
  }
}
```

### Récupérer un paiement

**Point de terminaison** : `GET /api/payments/{id}`

**Exemple de requête** :
```http
GET /api/payments/456
Authorization: Bearer votre_jeton
```

**Réponse** :
```json
{
  "success": true,
  "data": {
    "payment": {
      "id": 456,
      "payment_number": "PAY-2024-001",
      "order_id": 123,
      "amount": 31.18,
      "currency": "EUR",
      "status": "completed",
      "method": "card",
      "gateway": "stripe",
      "gateway_transaction_id": "pi_1234567890abcdef",
      "customer": {
        "id": 789,
        "name": "Jean Dupont",
        "email": "jean@example.com"
      },
      "payment_details": {
        "card_last_four": "4242",
        "card_brand": "visa",
        "receipt_url": "https://pay.stripe.com/receipts/abc123"
      },
      "fees": {
        "processing_fee": 0.93,
        "total_fees": 1.24
      },
      "processed_at": "2024-01-15T10:35:00Z",
      "created_at": "2024-01-15T10:30:00Z"
    }
  }
}
```

### Traiter un paiement

**Point de terminaison** : `POST /api/payments`

**Corps de la requête** :
```json
{
  "order_id": 123,
  "amount": 31.18,
  "currency": "EUR",
  "method": "card",
  "payment_details": {
    "token": "tok_1234567890abcdef",
    "save_card": false
  },
  "customer": {
    "id": 789,
    "email": "jean@example.com"
  },
  "metadata": {
    "table_number": "5",
    "tip_amount": "3.50"
  }
}
```

**Exemple de requête** :
```http
POST /api/payments
Authorization: Bearer votre_jeton
Content-Type: application/json

{
  "order_id": 123,
  "amount": 31.18,
  "method": "card",
  "payment_details": {
    "token": "tok_1234567890abcdef"
  }
}
```

**Réponse** :
```json
{
  "success": true,
  "message": "Paiement traité avec succès",
  "data": {
    "payment": {
      "id": 457,
      "payment_number": "PAY-2024-002",
      "order_id": 123,
      "amount": 31.18,
      "status": "completed",
      "method": "card",
      "gateway_transaction_id": "pi_0987654321fedcba",
      "processed_at": "2024-01-15T12:00:00Z",
      "created_at": "2024-01-15T12:00:00Z"
    }
  }
}
```

### Paiement en espèces

**Corps de la requête pour paiement en espèces** :
```json
{
  "order_id": 124,
  "amount": 25.50,
  "method": "cash",
  "payment_details": {
    "amount_received": 30.00,
    "change_given": 4.50,
    "cashier_id": 10
  }
}
```

### Paiement par portefeuille numérique

**Corps de la requête pour portefeuille numérique** :
```json
{
  "order_id": 125,
  "amount": 18.75,
  "method": "digital_wallet",
  "payment_details": {
    "wallet_type": "apple_pay",
    "device_id": "device_abc123"
  }
}
```

### Paiement partagé

**Point de terminaison** : `POST /api/payments/split`

**Corps de la requête** :
```json
{
  "order_id": 126,
  "total_amount": 45.60,
  "payments": [
    {
      "amount": 22.80,
      "method": "card",
      "payment_details": {
        "token": "tok_card_payment"
      },
      "customer": {
        "name": "Jean Dupont",
        "email": "jean@example.com"
      }
    },
    {
      "amount": 22.80,
      "method": "cash",
      "payment_details": {
        "amount_received": 25.00,
        "change_given": 2.20
      },
      "customer": {
        "name": "Marie Martin",
        "email": "marie@example.com"
      }
    }
  ]
}
```

**Réponse** :
```json
{
  "success": true,
  "message": "Paiement partagé traité avec succès",
  "data": {
    "split_payment": {
      "id": "split_458_459",
      "order_id": 126,
      "total_amount": 45.60,
      "payments": [
        {
          "id": 458,
          "amount": 22.80,
          "method": "card",
          "status": "completed"
        },
        {
          "id": 459,
          "amount": 22.80,
          "method": "cash",
          "status": "completed"
        }
      ],
      "status": "completed",
      "processed_at": "2024-01-15T13:00:00Z"
    }
  }
}
```

### Rembourser un paiement

**Point de terminaison** : `POST /api/payments/{id}/refund`

**Corps de la requête** :
```json
{
  "amount": 10.00,
  "reason": "Article non disponible",
  "refund_method": "original",
  "notify_customer": true
}
```

**Exemple de requête** :
```http
POST /api/payments/456/refund
Authorization: Bearer votre_jeton
Content-Type: application/json

{
  "amount": 10.00,
  "reason": "Article non disponible"
}
```

**Réponse** :
```json
{
  "success": true,
  "message": "Remboursement traité avec succès",
  "data": {
    "refund": {
      "id": 102,
      "payment_id": 456,
      "amount": 10.00,
      "reason": "Article non disponible",
      "status": "completed",
      "gateway_refund_id": "re_1234567890abcdef",
      "processed_at": "2024-01-15T14:00:00Z",
      "created_at": "2024-01-15T14:00:00Z"
    },
    "payment": {
      "id": 456,
      "status": "partially_refunded",
      "refunded_amount": 10.00,
      "remaining_amount": 21.18
    }
  }
}
```

### Annuler un paiement

**Point de terminaison** : `POST /api/payments/{id}/void`

**Corps de la requête** :
```json
{
  "reason": "Commande annulée par le client"
}
```

**Exemple de requête** :
```http
POST /api/payments/457/void
Authorization: Bearer votre_jeton
Content-Type: application/json

{
  "reason": "Commande annulée par le client"
}
```

**Réponse** :
```json
{
  "success": true,
  "message": "Paiement annulé avec succès",
  "data": {
    "payment": {
      "id": 457,
      "status": "cancelled",
      "cancellation_reason": "Commande annulée par le client",
      "cancelled_at": "2024-01-15T14:30:00Z"
    }
  }
}
```

### Gérer les méthodes de paiement

**Point de terminaison** : `GET /api/payments/methods`

**Exemple de requête** :
```http
GET /api/payments/methods
Authorization: Bearer votre_jeton
```

**Réponse** :
```json
{
  "success": true,
  "data": {
    "methods": [
      {
        "id": "cash",
        "name": "Espèces",
        "is_enabled": true,
        "settings": {
          "requires_change_calculation": true,
          "max_amount": 500.00
        }
      },
      {
        "id": "card",
        "name": "Carte",
        "is_enabled": true,
        "gateway": "stripe",
        "settings": {
          "accepts_credit": true,
          "accepts_debit": true,
          "min_amount": 1.00
        }
      },
      {
        "id": "digital_wallet",
        "name": "Portefeuille numérique",
        "is_enabled": true,
        "supported_wallets": ["apple_pay", "google_pay", "samsung_pay"]
      }
    ]
  }
}
```

### Mettre à jour les méthodes de paiement

**Point de terminaison** : `PUT /api/payments/methods/{method_id}`

**Corps de la requête** :
```json
{
  "is_enabled": false,
  "settings": {
    "max_amount": 300.00
  }
}
```

**Réponse** :
```json
{
  "success": true,
  "message": "Méthode de paiement mise à jour avec succès",
  "data": {
    "method": {
      "id": "cash",
      "name": "Espèces",
      "is_enabled": false,
      "settings": {
        "max_amount": 300.00
      },
      "updated_at": "2024-01-15T15:00:00Z"
    }
  }
}
```

### Analytiques des paiements

**Point de terminaison** : `GET /api/payments/analytics`

**Paramètres de requête** :
- `period` (string) : today|week|month|year (défaut : today)
- `date_from` (date) : Date de début personnalisée
- `date_to` (date) : Date de fin personnalisée
- `group_by` (string) : method|status|hour|day (défaut : method)

**Exemple de requête** :
```http
GET /api/payments/analytics?period=month&group_by=method
Authorization: Bearer votre_jeton
```

**Réponse** :
```json
{
  "success": true,
  "data": {
    "analytics": {
      "total_payments": 1250,
      "total_amount": 18750.50,
      "total_fees": 562.25,
      "net_amount": 18188.25,
      "average_payment": 15.00,
      "success_rate": 98.5,
      "by_method": [
        {
          "method": "card",
          "count": 875,
          "amount": 13125.75,
          "percentage": 70.0
        },
        {
          "method": "cash",
          "count": 300,
          "amount": 4500.00,
          "percentage": 24.0
        },
        {
          "method": "digital_wallet",
          "count": 75,
          "amount": 1124.75,
          "percentage": 6.0
        }
      ],
      "by_status": {
        "completed": 1231,
        "failed": 15,
        "cancelled": 4
      },
      "refunds": {
        "total_refunds": 25,
        "total_refund_amount": 375.50,
        "refund_rate": 2.0
      },
      "trends": {
        "daily_average": 604.85,
        "growth_rate": 12.5,
        "peak_hour": 19
      }
    }
  }
}
```

### Tester les passerelles de paiement

**Point de terminaison** : `POST /api/payments/test-gateway`

**Corps de la requête** :
```json
{
  "gateway": "stripe",
  "amount": 1.00,
  "currency": "EUR"
}
```

**Réponse** :
```json
{
  "success": true,
  "message": "Test de passerelle réussi",
  "data": {
    "gateway": "stripe",
    "status": "connected",
    "response_time": 245,
    "test_transaction_id": "test_pi_1234567890",
    "capabilities": [
      "payments",
      "refunds",
      "webhooks"
    ]
  }
}
```

## Webhooks

RestoPos envoie des webhooks pour les événements de paiement :

### payment.completed

```json
{
  "event": "payment.completed",
  "data": {
    "payment": {
      "id": 456,
      "payment_number": "PAY-2024-001",
      "order_id": 123,
      "amount": 31.18,
      "method": "card",
      "status": "completed"
    }
  },
  "timestamp": "2024-01-15T10:35:00Z"
}
```

### payment.failed

```json
{
  "event": "payment.failed",
  "data": {
    "payment": {
      "id": 458,
      "order_id": 124,
      "amount": 25.50,
      "method": "card",
      "status": "failed",
      "failure_reason": "Carte refusée",
      "failure_code": "card_declined"
    }
  },
  "timestamp": "2024-01-15T11:00:00Z"
}
```

### payment.refunded

```json
{
  "event": "payment.refunded",
  "data": {
    "payment": {
      "id": 456,
      "order_id": 123,
      "original_amount": 31.18,
      "refunded_amount": 10.00,
      "status": "partially_refunded"
    },
    "refund": {
      "id": 102,
      "amount": 10.00,
      "reason": "Article non disponible"
    }
  },
  "timestamp": "2024-01-15T14:00:00Z"
}
```

## Gestion des erreurs

### 400 Requête incorrecte

```json
{
  "success": false,
  "error": {
    "code": "INVALID_PAYMENT_DATA",
    "message": "Données de paiement invalides",
    "details": {
      "amount": ["Le montant doit être supérieur à 0"],
      "method": ["Méthode de paiement non prise en charge"]
    }
  }
}
```

### 402 Paiement requis

```json
{
  "success": false,
  "error": {
    "code": "PAYMENT_FAILED",
    "message": "Échec du paiement",
    "details": "Votre carte a été refusée. Veuillez essayer une autre carte.",
    "failure_code": "card_declined",
    "decline_code": "insufficient_funds"
  }
}
```

### 409 Conflit

```json
{
  "success": false,
  "error": {
    "code": "PAYMENT_ALREADY_PROCESSED",
    "message": "Paiement déjà traité",
    "details": "Ce paiement a déjà été traité et ne peut pas être modifié"
  }
}
```

## Exemples SDK

::: tabs

== JavaScript

```javascript
// Traiter un paiement par carte
async function processCardPayment(orderData) {
  try {
    const response = await fetch('/api/payments', {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        order_id: orderData.orderId,
        amount: orderData.amount,
        method: 'card',
        payment_details: {
          token: orderData.cardToken
        },
        customer: orderData.customer
      })
    });
    
    const result = await response.json();
    
    if (result.success) {
      console.log('Paiement réussi:', result.data.payment);
      return result.data.payment;
    } else {
      throw new Error(result.error.message);
    }
  } catch (error) {
    console.error('Erreur paiement:', error);
    throw error;
  }
}

// Rembourser un paiement
async function refundPayment(paymentId, amount, reason) {
  try {
    const response = await fetch(`/api/payments/${paymentId}/refund`, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        amount: amount,
        reason: reason
      })
    });
    
    const result = await response.json();
    return result.data.refund;
  } catch (error) {
    console.error('Erreur remboursement:', error);
    throw error;
  }
}

// Récupérer les analytiques de paiement
async function getPaymentAnalytics(period = 'today') {
  try {
    const response = await fetch(`/api/payments/analytics?period=${period}`, {
      headers: {
        'Authorization': `Bearer ${token}`
      }
    });
    
    const result = await response.json();
    return result.data.analytics;
  } catch (error) {
    console.error('Erreur récupération analytiques:', error);
    throw error;
  }
}
```

== PHP

```php
use Illuminate\Support\Facades\Http;

class PaymentService
{
    private $baseUrl;
    private $token;

    public function __construct($baseUrl, $token)
    {
        $this->baseUrl = $baseUrl;
        $this->token = $token;
    }

    public function processPayment(array $paymentData)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ])->post($this->baseUrl . '/api/payments', $paymentData);

        if ($response->successful()) {
            return $response->json()['data']['payment'];
        }

        throw new Exception('Erreur traitement paiement: ' . $response->body());
    }

    public function refundPayment($paymentId, $amount, $reason = null)
    {
        $data = ['amount' => $amount];
        if ($reason) {
            $data['reason'] = $reason;
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ])->post($this->baseUrl . '/api/payments/' . $paymentId . '/refund', $data);

        if ($response->successful()) {
            return $response->json()['data']['refund'];
        }

        throw new Exception('Erreur remboursement: ' . $response->body());
    }

    public function getPaymentAnalytics($period = 'today')
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ])->get($this->baseUrl . '/api/payments/analytics', [
            'period' => $period
        ]);

        if ($response->successful()) {
            return $response->json()['data']['analytics'];
        }

        throw new Exception('Erreur récupération analytiques: ' . $response->body());
    }

    public function processSplitPayment($orderId, $totalAmount, array $payments)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ])->post($this->baseUrl . '/api/payments/split', [
            'order_id' => $orderId,
            'total_amount' => $totalAmount,
            'payments' => $payments
        ]);

        if ($response->successful()) {
            return $response->json()['data']['split_payment'];
        }

        throw new Exception('Erreur paiement partagé: ' . $response->body());
    }
}

// Utilisation
$paymentService = new PaymentService('https://api.restopos.com', 'votre_jeton');

// Traiter un paiement
$payment = $paymentService->processPayment([
    'order_id' => 123,
    'amount' => 31.18,
    'method' => 'card',
    'payment_details' => [
        'token' => 'tok_1234567890abcdef'
    ]
]);

// Rembourser
$refund = $paymentService->refundPayment(456, 10.00, 'Article non disponible');

// Analytiques
$analytics = $paymentService->getPaymentAnalytics('month');
```

== Python

```python
import requests
from typing import Dict, List, Any, Optional

class PaymentAPI:
    def __init__(self, base_url: str, token: str):
        self.base_url = base_url
        self.headers = {
            'Authorization': f'Bearer {token}',
            'Content-Type': 'application/json'
        }

    def process_payment(self, payment_data: Dict[str, Any]) -> Dict[str, Any]:
        """Traiter un paiement"""
        response = requests.post(
            f'{self.base_url}/api/payments',
            json=payment_data,
            headers=self.headers
        )
        
        if response.status_code == 201:
            return response.json()['data']['payment']
        else:
            raise Exception(f'Erreur traitement paiement: {response.text}')

    def refund_payment(self, payment_id: int, amount: float, reason: Optional[str] = None) -> Dict[str, Any]:
        """Rembourser un paiement"""
        data = {'amount': amount}
        if reason:
            data['reason'] = reason
            
        response = requests.post(
            f'{self.base_url}/api/payments/{payment_id}/refund',
            json=data,
            headers=self.headers
        )
        
        if response.status_code == 200:
            return response.json()['data']['refund']
        else:
            raise Exception(f'Erreur remboursement: {response.text}')

    def get_payment_analytics(self, period: str = 'today') -> Dict[str, Any]:
        """Récupérer les analytiques de paiement"""
        response = requests.get(
            f'{self.base_url}/api/payments/analytics',
            params={'period': period},
            headers=self.headers
        )
        
        if response.status_code == 200:
            return response.json()['data']['analytics']
        else:
            raise Exception(f'Erreur récupération analytiques: {response.text}')

    def process_split_payment(self, order_id: int, total_amount: float, payments: List[Dict[str, Any]]) -> Dict[str, Any]:
        """Traiter un paiement partagé"""
        response = requests.post(
            f'{self.base_url}/api/payments/split',
            json={
                'order_id': order_id,
                'total_amount': total_amount,
                'payments': payments
            },
            headers=self.headers
        )
        
        if response.status_code == 201:
            return response.json()['data']['split_payment']
        else:
            raise Exception(f'Erreur paiement partagé: {response.text}')

    def void_payment(self, payment_id: int, reason: str) -> Dict[str, Any]:
        """Annuler un paiement"""
        response = requests.post(
            f'{self.base_url}/api/payments/{payment_id}/void',
            json={'reason': reason},
            headers=self.headers
        )
        
        if response.status_code == 200:
            return response.json()['data']['payment']
        else:
            raise Exception(f'Erreur annulation paiement: {response.text}')

# Utilisation
api = PaymentAPI('https://api.restopos.com', 'votre_jeton')

# Traiter un paiement par carte
paiement = api.process_payment({
    'order_id': 123,
    'amount': 31.18,
    'method': 'card',
    'payment_details': {
        'token': 'tok_1234567890abcdef'
    },
    'customer': {
        'email': 'jean@example.com'
    }
})

print(f'Paiement traité: {paiement["payment_number"]}')

# Rembourser partiellement
remboursement = api.refund_payment(456, 10.00, 'Article non disponible')
print(f'Remboursement: {remboursement["amount"]}€')

# Récupérer les analytiques
analytics = api.get_payment_analytics('month')
print(f'Total des paiements: {analytics["total_amount"]}€')

# Paiement partagé
paiement_partage = api.process_split_payment(126, 45.60, [
    {
        'amount': 22.80,
        'method': 'card',
        'payment_details': {'token': 'tok_card_payment'}
    },
    {
        'amount': 22.80,
        'method': 'cash',
        'payment_details': {'amount_received': 25.00, 'change_given': 2.20}
    }
])
```

:::

## Meilleures pratiques

### Sécurité des paiements

1. **Conformité PCI** : Respecter les normes PCI DSS pour la manipulation des données de carte
2. **Utilisation de jetons** : Toujours utiliser des jetons pour les données de carte sensibles
3. **HTTPS obligatoire** : Utiliser uniquement des connexions sécurisées
4. **Chiffrement** : Chiffrer toutes les données sensibles au repos et en transit
5. **Audit** : Maintenir des journaux détaillés de toutes les transactions

### Gestion des erreurs

1. **Échecs gracieux** : Gérer les échecs de paiement de manière élégante
2. **Logique de nouvelle tentative** : Implémenter une logique de nouvelle tentative pour les erreurs temporaires
3. **Feedback utilisateur** : Fournir des messages d'erreur clairs aux utilisateurs
4. **Surveillance** : Surveiller les taux d'échec et les tendances

### Optimisation des performances

1. **Traitement asynchrone** : Utiliser le traitement asynchrone pour les opérations longues
2. **Mise en cache** : Mettre en cache les données de configuration des paiements
3. **Limitation du taux** : Implémenter une limitation du taux pour prévenir les abus
4. **Optimisation de la base de données** : Optimiser les requêtes pour les rapports de paiement

### Conformité et reporting

1. **Enregistrements de transaction** : Maintenir des enregistrements détaillés de toutes les transactions
2. **Rapprochement** : Implémenter des processus de rapprochement automatisés
3. **Reporting fiscal** : Générer des rapports pour les exigences fiscales
4. **Audit de conformité** : Effectuer des audits réguliers de conformité