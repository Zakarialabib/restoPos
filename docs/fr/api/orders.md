# API Commandes

L'API Commandes RestoPos vous permet de gérer les commandes de restaurant, y compris la création, la mise à jour, le suivi et l'intégration avec les systèmes d'affichage cuisine.

## Objet Commande

```json
{
  "id": 123,
  "order_number": "ORD-2024-001",
  "status": "confirmed",
  "type": "dine_in",
  "table_id": 5,
  "customer": {
    "id": 456,
    "name": "Jean Dupont",
    "email": "jean@example.com",
    "phone": "+33123456789"
  },
  "items": [
    {
      "id": 789,
      "menu_item_id": 101,
      "name": "Burger Classique",
      "quantity": 2,
      "unit_price": 12.99,
      "total_price": 25.98,
      "modifiers": [
        {
          "id": 201,
          "name": "Fromage supplémentaire",
          "price": 1.50
        }
      ],
      "special_instructions": "Bien cuit"
    }
  ],
  "subtotal": 25.98,
  "tax_amount": 5.20,
  "discount_amount": 0.00,
  "total_amount": 31.18,
  "payment_status": "paid",
  "payment_method": "card",
  "created_at": "2024-01-15T10:30:00Z",
  "updated_at": "2024-01-15T10:35:00Z",
  "estimated_ready_time": "2024-01-15T10:45:00Z",
  "actual_ready_time": null,
  "served_at": null,
  "staff": {
    "id": 10,
    "name": "Marie Martin",
    "role": "serveur"
  },
  "notes": "Client préfère la table près de la fenêtre"
}
```

### Valeurs de statut

| Statut | Description |
|--------|-------------|
| `pending` | Commande créée, en attente de confirmation |
| `confirmed` | Commande confirmée et envoyée en cuisine |
| `preparing` | En cours de préparation |
| `ready` | Prête à être servie/récupérée |
| `served` | Servie au client |
| `completed` | Commande terminée |
| `cancelled` | Commande annulée |

### Types de commande

| Type | Description |
|------|-------------|
| `dine_in` | Sur place |
| `takeaway` | À emporter |
| `delivery` | Livraison |
| `drive_through` | Drive |

## Points de terminaison

### Lister les commandes

**Point de terminaison** : `GET /api/orders`

**Paramètres de requête** :
- `status` (string) : Filtrer par statut
- `type` (string) : Filtrer par type
- `table_id` (integer) : Filtrer par table
- `date_from` (date) : Date de début (YYYY-MM-DD)
- `date_to` (date) : Date de fin (YYYY-MM-DD)
- `page` (integer) : Numéro de page (défaut : 1)
- `per_page` (integer) : Éléments par page (défaut : 15, max : 100)
- `sort` (string) : Champ de tri (défaut : created_at)
- `order` (string) : Direction du tri (asc|desc, défaut : desc)

**Exemple de requête** :
```http
GET /api/orders?status=confirmed&type=dine_in&page=1&per_page=20
Authorization: Bearer votre_jeton
```

**Réponse** :
```json
{
  "success": true,
  "data": {
    "orders": [
      {
        "id": 123,
        "order_number": "ORD-2024-001",
        "status": "confirmed",
        "type": "dine_in",
        "table_id": 5,
        "total_amount": 31.18,
        "created_at": "2024-01-15T10:30:00Z"
      }
    ],
    "pagination": {
      "current_page": 1,
      "per_page": 20,
      "total": 150,
      "last_page": 8,
      "from": 1,
      "to": 20
    }
  }
}
```

### Récupérer une commande

**Point de terminaison** : `GET /api/orders/{id}`

**Exemple de requête** :
```http
GET /api/orders/123
Authorization: Bearer votre_jeton
```

**Réponse** :
```json
{
  "success": true,
  "data": {
    "order": {
      "id": 123,
      "order_number": "ORD-2024-001",
      "status": "confirmed",
      "type": "dine_in",
      "table_id": 5,
      "customer": {
        "id": 456,
        "name": "Jean Dupont",
        "email": "jean@example.com",
        "phone": "+33123456789"
      },
      "items": [
        {
          "id": 789,
          "menu_item_id": 101,
          "name": "Burger Classique",
          "quantity": 2,
          "unit_price": 12.99,
          "total_price": 25.98,
          "modifiers": [
            {
              "id": 201,
              "name": "Fromage supplémentaire",
              "price": 1.50
            }
          ],
          "special_instructions": "Bien cuit"
        }
      ],
      "subtotal": 25.98,
      "tax_amount": 5.20,
      "total_amount": 31.18,
      "payment_status": "paid",
      "created_at": "2024-01-15T10:30:00Z",
      "updated_at": "2024-01-15T10:35:00Z"
    }
  }
}
```

### Créer une commande

**Point de terminaison** : `POST /api/orders`

**Corps de la requête** :
```json
{
  "type": "dine_in",
  "table_id": 5,
  "customer": {
    "name": "Jean Dupont",
    "email": "jean@example.com",
    "phone": "+33123456789"
  },
  "items": [
    {
      "menu_item_id": 101,
      "quantity": 2,
      "modifiers": [201],
      "special_instructions": "Bien cuit"
    },
    {
      "menu_item_id": 102,
      "quantity": 1
    }
  ],
  "notes": "Client préfère la table près de la fenêtre",
  "discount_code": "WELCOME10"
}
```

**Exemple de requête** :
```http
POST /api/orders
Authorization: Bearer votre_jeton
Content-Type: application/json

{
  "type": "dine_in",
  "table_id": 5,
  "items": [
    {
      "menu_item_id": 101,
      "quantity": 2,
      "modifiers": [201]
    }
  ]
}
```

**Réponse** :
```json
{
  "success": true,
  "message": "Commande créée avec succès",
  "data": {
    "order": {
      "id": 124,
      "order_number": "ORD-2024-002",
      "status": "pending",
      "type": "dine_in",
      "table_id": 5,
      "total_amount": 27.48,
      "created_at": "2024-01-15T11:00:00Z"
    }
  }
}
```

### Mettre à jour une commande

**Point de terminaison** : `PUT /api/orders/{id}`

**Corps de la requête** :
```json
{
  "status": "confirmed",
  "notes": "Commande confirmée par le client",
  "estimated_ready_time": "2024-01-15T11:15:00Z"
}
```

**Exemple de requête** :
```http
PUT /api/orders/124
Authorization: Bearer votre_jeton
Content-Type: application/json

{
  "status": "confirmed"
}
```

**Réponse** :
```json
{
  "success": true,
  "message": "Commande mise à jour avec succès",
  "data": {
    "order": {
      "id": 124,
      "status": "confirmed",
      "updated_at": "2024-01-15T11:05:00Z"
    }
  }
}
```

### Annuler une commande

**Point de terminaison** : `DELETE /api/orders/{id}`

**Corps de la requête** :
```json
{
  "reason": "Demande du client",
  "refund_amount": 27.48
}
```

**Exemple de requête** :
```http
DELETE /api/orders/124
Authorization: Bearer votre_jeton
Content-Type: application/json

{
  "reason": "Demande du client"
}
```

**Réponse** :
```json
{
  "success": true,
  "message": "Commande annulée avec succès",
  "data": {
    "order": {
      "id": 124,
      "status": "cancelled",
      "cancelled_at": "2024-01-15T11:10:00Z",
      "cancellation_reason": "Demande du client"
    }
  }
}
```

### Ajouter des articles à une commande

**Point de terminaison** : `POST /api/orders/{id}/items`

**Corps de la requête** :
```json
{
  "items": [
    {
      "menu_item_id": 103,
      "quantity": 1,
      "modifiers": [202],
      "special_instructions": "Sans oignons"
    }
  ]
}
```

**Réponse** :
```json
{
  "success": true,
  "message": "Articles ajoutés à la commande",
  "data": {
    "order": {
      "id": 123,
      "total_amount": 39.67,
      "items_count": 3
    }
  }
}
```

### Supprimer des articles d'une commande

**Point de terminaison** : `DELETE /api/orders/{id}/items/{item_id}`

**Réponse** :
```json
{
  "success": true,
  "message": "Article supprimé de la commande",
  "data": {
    "order": {
      "id": 123,
      "total_amount": 31.18,
      "items_count": 2
    }
  }
}
```

### Statistiques des commandes

**Point de terminaison** : `GET /api/orders/stats`

**Paramètres de requête** :
- `period` (string) : today|week|month|year (défaut : today)
- `date_from` (date) : Date de début personnalisée
- `date_to` (date) : Date de fin personnalisée

**Exemple de requête** :
```http
GET /api/orders/stats?period=today
Authorization: Bearer votre_jeton
```

**Réponse** :
```json
{
  "success": true,
  "data": {
    "stats": {
      "total_orders": 45,
      "total_revenue": 1250.75,
      "average_order_value": 27.79,
      "orders_by_status": {
        "pending": 3,
        "confirmed": 8,
        "preparing": 5,
        "ready": 2,
        "served": 25,
        "completed": 2
      },
      "orders_by_type": {
        "dine_in": 30,
        "takeaway": 12,
        "delivery": 3
      },
      "peak_hours": [
        {
          "hour": 12,
          "orders": 15
        },
        {
          "hour": 19,
          "orders": 18
        }
      ]
    }
  }
}
```

### Intégration système d'affichage cuisine

**Point de terminaison** : `GET /api/orders/kitchen`

**Paramètres de requête** :
- `status` (string) : confirmed|preparing (défaut : confirmed,preparing)
- `station` (string) : Filtrer par station de cuisine

**Exemple de requête** :
```http
GET /api/orders/kitchen?status=confirmed,preparing
Authorization: Bearer votre_jeton
```

**Réponse** :
```json
{
  "success": true,
  "data": {
    "orders": [
      {
        "id": 123,
        "order_number": "ORD-2024-001",
        "status": "confirmed",
        "type": "dine_in",
        "table_id": 5,
        "items": [
          {
            "id": 789,
            "name": "Burger Classique",
            "quantity": 2,
            "modifiers": ["Fromage supplémentaire"],
            "special_instructions": "Bien cuit",
            "station": "grill"
          }
        ],
        "estimated_ready_time": "2024-01-15T10:45:00Z",
        "order_time": "2024-01-15T10:30:00Z",
        "elapsed_time": "00:15:00"
      }
    ]
  }
}
```

## Gestion des erreurs

### 400 Requête incorrecte

```json
{
  "success": false,
  "error": {
    "code": "INVALID_REQUEST",
    "message": "Données de requête invalides",
    "details": {
      "table_id": ["La table sélectionnée n'est pas disponible"],
      "items": ["Au moins un article est requis"]
    }
  }
}
```

### 404 Non trouvé

```json
{
  "success": false,
  "error": {
    "code": "ORDER_NOT_FOUND",
    "message": "Commande non trouvée",
    "details": "La commande avec l'ID 999 n'existe pas"
  }
}
```

### 409 Conflit

```json
{
  "success": false,
  "error": {
    "code": "ORDER_CONFLICT",
    "message": "Impossible de modifier la commande",
    "details": "La commande ne peut pas être modifiée car elle est déjà en cours de préparation"
  }
}
```

## Événements Webhook

RestoPos envoie des webhooks pour les événements de commande :

### order.created

```json
{
  "event": "order.created",
  "data": {
    "order": {
      "id": 123,
      "order_number": "ORD-2024-001",
      "status": "pending",
      "total_amount": 31.18
    }
  },
  "timestamp": "2024-01-15T10:30:00Z"
}
```

### order.status_changed

```json
{
  "event": "order.status_changed",
  "data": {
    "order": {
      "id": 123,
      "order_number": "ORD-2024-001",
      "old_status": "pending",
      "new_status": "confirmed"
    }
  },
  "timestamp": "2024-01-15T10:35:00Z"
}
```

### order.ready

```json
{
  "event": "order.ready",
  "data": {
    "order": {
      "id": 123,
      "order_number": "ORD-2024-001",
      "table_id": 5,
      "type": "dine_in"
    }
  },
  "timestamp": "2024-01-15T10:45:00Z"
}
```

## Exemples SDK

::: tabs

== JavaScript

```javascript
// Créer une nouvelle commande
async function createOrder(orderData) {
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
      console.log('Commande créée:', result.data.order);
      return result.data.order;
    } else {
      throw new Error(result.error.message);
    }
  } catch (error) {
    console.error('Erreur création commande:', error);
    throw error;
  }
}

// Mettre à jour le statut d'une commande
async function updateOrderStatus(orderId, status) {
  try {
    const response = await fetch(`/api/orders/${orderId}`, {
      method: 'PUT',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ status })
    });
    
    const result = await response.json();
    return result.data.order;
  } catch (error) {
    console.error('Erreur mise à jour commande:', error);
    throw error;
  }
}

// Récupérer les commandes pour la cuisine
async function getKitchenOrders() {
  try {
    const response = await fetch('/api/orders/kitchen', {
      headers: {
        'Authorization': `Bearer ${token}`
      }
    });
    
    const result = await response.json();
    return result.data.orders;
  } catch (error) {
    console.error('Erreur récupération commandes cuisine:', error);
    throw error;
  }
}
```

== PHP

```php
use Illuminate\Support\Facades\Http;

class OrderService
{
    private $baseUrl;
    private $token;

    public function __construct($baseUrl, $token)
    {
        $this->baseUrl = $baseUrl;
        $this->token = $token;
    }

    public function createOrder(array $orderData)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ])->post($this->baseUrl . '/api/orders', $orderData);

        if ($response->successful()) {
            return $response->json()['data']['order'];
        }

        throw new Exception('Erreur création commande: ' . $response->body());
    }

    public function updateOrderStatus($orderId, $status)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ])->put($this->baseUrl . '/api/orders/' . $orderId, [
            'status' => $status
        ]);

        if ($response->successful()) {
            return $response->json()['data']['order'];
        }

        throw new Exception('Erreur mise à jour commande: ' . $response->body());
    }

    public function getKitchenOrders()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ])->get($this->baseUrl . '/api/orders/kitchen');

        if ($response->successful()) {
            return $response->json()['data']['orders'];
        }

        throw new Exception('Erreur récupération commandes: ' . $response->body());
    }
}

// Utilisation
$orderService = new OrderService('https://api.restopos.com', 'votre_jeton');

$newOrder = $orderService->createOrder([
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
from typing import Dict, List, Any

class OrderAPI:
    def __init__(self, base_url: str, token: str):
        self.base_url = base_url
        self.headers = {
            'Authorization': f'Bearer {token}',
            'Content-Type': 'application/json'
        }

    def create_order(self, order_data: Dict[str, Any]) -> Dict[str, Any]:
        """Créer une nouvelle commande"""
        response = requests.post(
            f'{self.base_url}/api/orders',
            json=order_data,
            headers=self.headers
        )
        
        if response.status_code == 201:
            return response.json()['data']['order']
        else:
            raise Exception(f'Erreur création commande: {response.text}')

    def update_order_status(self, order_id: int, status: str) -> Dict[str, Any]:
        """Mettre à jour le statut d'une commande"""
        response = requests.put(
            f'{self.base_url}/api/orders/{order_id}',
            json={'status': status},
            headers=self.headers
        )
        
        if response.status_code == 200:
            return response.json()['data']['order']
        else:
            raise Exception(f'Erreur mise à jour commande: {response.text}')

    def get_kitchen_orders(self) -> List[Dict[str, Any]]:
        """Récupérer les commandes pour la cuisine"""
        response = requests.get(
            f'{self.base_url}/api/orders/kitchen',
            headers=self.headers
        )
        
        if response.status_code == 200:
            return response.json()['data']['orders']
        else:
            raise Exception(f'Erreur récupération commandes: {response.text}')

    def get_order_stats(self, period: str = 'today') -> Dict[str, Any]:
        """Récupérer les statistiques des commandes"""
        response = requests.get(
            f'{self.base_url}/api/orders/stats',
            params={'period': period},
            headers=self.headers
        )
        
        if response.status_code == 200:
            return response.json()['data']['stats']
        else:
            raise Exception(f'Erreur récupération statistiques: {response.text}')

# Utilisation
api = OrderAPI('https://api.restopos.com', 'votre_jeton')

# Créer une commande
nouvelle_commande = api.create_order({
    'type': 'dine_in',
    'table_id': 5,
    'items': [
        {
            'menu_item_id': 101,
            'quantity': 2
        }
    ]
})

print(f'Commande créée: {nouvelle_commande["order_number"]}')

# Mettre à jour le statut
api.update_order_status(nouvelle_commande['id'], 'confirmed')

# Récupérer les statistiques
stats = api.get_order_stats('today')
print(f'Commandes aujourd\'hui: {stats["total_orders"]}')
```

:::

## Meilleures pratiques

### Gestion des commandes

1. **Validation des données** : Toujours valider les données avant de créer une commande
2. **Gestion des stocks** : Vérifier la disponibilité des articles avant confirmation
3. **Notifications temps réel** : Utiliser les webhooks pour les mises à jour en temps réel
4. **Gestion des erreurs** : Implémenter une gestion robuste des erreurs
5. **Journalisation** : Enregistrer toutes les actions importantes sur les commandes

### Performance

1. **Pagination** : Utiliser la pagination pour les listes de commandes
2. **Filtrage** : Appliquer des filtres appropriés pour réduire la charge
3. **Mise en cache** : Mettre en cache les données fréquemment consultées
4. **Requêtes optimisées** : Utiliser les paramètres de requête pour récupérer uniquement les données nécessaires

### Sécurité

1. **Authentification** : Toujours utiliser des jetons d'authentification valides
2. **Autorisation** : Vérifier les permissions avant les opérations sensibles
3. **Validation des entrées** : Valider et assainir toutes les entrées utilisateur
4. **Audit** : Maintenir un journal d'audit pour toutes les modifications de commandes