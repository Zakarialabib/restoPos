# API Menu

L'API Menu RestoPos vous permet de gérer les éléments du menu, les catégories, les modificateurs et la disponibilité. Cette API prend en charge les opérations CRUD complètes et l'intégration en temps réel avec votre système POS.

## Objet Élément de Menu

```json
{
  "id": 101,
  "name": "Burger Classique",
  "description": "Burger de bœuf juteux avec laitue, tomate, oignon et notre sauce spéciale",
  "price": 12.99,
  "category_id": 1,
  "category": {
    "id": 1,
    "name": "Burgers",
    "slug": "burgers"
  },
  "image_url": "https://cdn.restopos.com/menu/burger-classique.jpg",
  "is_available": true,
  "is_featured": false,
  "preparation_time": 15,
  "calories": 650,
  "allergens": ["gluten", "dairy"],
  "dietary_info": [],
  "ingredients": [
    "Pain brioche",
    "Steak de bœuf 150g",
    "Laitue",
    "Tomate",
    "Oignon rouge",
    "Sauce maison"
  ],
  "modifiers": [
    {
      "id": 201,
      "name": "Fromage supplémentaire",
      "price": 1.50,
      "is_required": false
    },
    {
      "id": 202,
      "name": "Cuisson",
      "is_required": true,
      "options": [
        {
          "id": 301,
          "name": "Saignant",
          "price": 0.00
        },
        {
          "id": 302,
          "name": "À point",
          "price": 0.00
        },
        {
          "id": 303,
          "name": "Bien cuit",
          "price": 0.00
        }
      ]
    }
  ],
  "variants": [
    {
      "id": 401,
      "name": "Taille normale",
      "price": 12.99,
      "is_default": true
    },
    {
      "id": 402,
      "name": "Grande taille",
      "price": 15.99,
      "is_default": false
    }
  ],
  "nutritional_info": {
    "calories": 650,
    "protein": 35,
    "carbs": 45,
    "fat": 28,
    "fiber": 3,
    "sugar": 8,
    "sodium": 1200
  },
  "tags": ["populaire", "signature"],
  "sort_order": 1,
  "created_at": "2024-01-15T10:00:00Z",
  "updated_at": "2024-01-15T10:30:00Z"
}
```

## Objet Catégorie

```json
{
  "id": 1,
  "name": "Burgers",
  "description": "Nos délicieux burgers faits maison",
  "slug": "burgers",
  "image_url": "https://cdn.restopos.com/categories/burgers.jpg",
  "is_active": true,
  "sort_order": 1,
  "items_count": 8,
  "created_at": "2024-01-15T09:00:00Z",
  "updated_at": "2024-01-15T09:30:00Z"
}
```

## Points de terminaison

### Lister les éléments du menu

**Point de terminaison** : `GET /api/menu/items`

**Paramètres de requête** :
- `category_id` (integer) : Filtrer par catégorie
- `is_available` (boolean) : Filtrer par disponibilité
- `is_featured` (boolean) : Filtrer les éléments mis en avant
- `search` (string) : Rechercher dans le nom et la description
- `tags` (string) : Filtrer par tags (séparés par des virgules)
- `min_price` (decimal) : Prix minimum
- `max_price` (decimal) : Prix maximum
- `page` (integer) : Numéro de page (défaut : 1)
- `per_page` (integer) : Éléments par page (défaut : 15, max : 100)
- `sort` (string) : Champ de tri (name|price|created_at|sort_order)
- `order` (string) : Direction du tri (asc|desc)

**Exemple de requête** :
```http
GET /api/menu/items?category_id=1&is_available=true&page=1&per_page=20
Authorization: Bearer votre_jeton
```

**Réponse** :
```json
{
  "success": true,
  "data": {
    "items": [
      {
        "id": 101,
        "name": "Burger Classique",
        "description": "Burger de bœuf juteux avec garnitures fraîches",
        "price": 12.99,
        "category": {
          "id": 1,
          "name": "Burgers"
        },
        "image_url": "https://cdn.restopos.com/menu/burger-classique.jpg",
        "is_available": true,
        "is_featured": false
      }
    ],
    "pagination": {
      "current_page": 1,
      "per_page": 20,
      "total": 45,
      "last_page": 3,
      "from": 1,
      "to": 20
    }
  }
}
```

### Récupérer un élément du menu

**Point de terminaison** : `GET /api/menu/items/{id}`

**Exemple de requête** :
```http
GET /api/menu/items/101
Authorization: Bearer votre_jeton
```

**Réponse** :
```json
{
  "success": true,
  "data": {
    "item": {
      "id": 101,
      "name": "Burger Classique",
      "description": "Burger de bœuf juteux avec laitue, tomate, oignon et notre sauce spéciale",
      "price": 12.99,
      "category_id": 1,
      "category": {
        "id": 1,
        "name": "Burgers",
        "slug": "burgers"
      },
      "image_url": "https://cdn.restopos.com/menu/burger-classique.jpg",
      "is_available": true,
      "modifiers": [
        {
          "id": 201,
          "name": "Fromage supplémentaire",
          "price": 1.50,
          "is_required": false
        }
      ],
      "nutritional_info": {
        "calories": 650,
        "protein": 35,
        "carbs": 45,
        "fat": 28
      }
    }
  }
}
```

### Créer un élément du menu

**Point de terminaison** : `POST /api/menu/items`

**Corps de la requête** :
```json
{
  "name": "Burger Végétarien",
  "description": "Délicieux burger végétarien avec galette de légumes",
  "price": 11.99,
  "category_id": 1,
  "image_url": "https://cdn.restopos.com/menu/burger-vegetarien.jpg",
  "is_available": true,
  "is_featured": false,
  "preparation_time": 12,
  "calories": 520,
  "allergens": ["gluten", "soy"],
  "dietary_info": ["vegetarian"],
  "ingredients": [
    "Pain complet",
    "Galette de légumes",
    "Avocat",
    "Tomate",
    "Laitue",
    "Sauce végétalienne"
  ],
  "modifiers": [201, 202],
  "tags": ["healthy", "vegetarian"],
  "nutritional_info": {
    "calories": 520,
    "protein": 18,
    "carbs": 52,
    "fat": 22,
    "fiber": 8,
    "sugar": 6,
    "sodium": 890
  }
}
```

**Exemple de requête** :
```http
POST /api/menu/items
Authorization: Bearer votre_jeton
Content-Type: application/json

{
  "name": "Burger Végétarien",
  "description": "Délicieux burger végétarien avec galette de légumes",
  "price": 11.99,
  "category_id": 1
}
```

**Réponse** :
```json
{
  "success": true,
  "message": "Élément du menu créé avec succès",
  "data": {
    "item": {
      "id": 102,
      "name": "Burger Végétarien",
      "description": "Délicieux burger végétarien avec galette de légumes",
      "price": 11.99,
      "category_id": 1,
      "is_available": true,
      "created_at": "2024-01-15T11:00:00Z"
    }
  }
}
```

### Mettre à jour un élément du menu

**Point de terminaison** : `PUT /api/menu/items/{id}`

**Corps de la requête** :
```json
{
  "name": "Burger Classique Premium",
  "price": 14.99,
  "description": "Notre burger signature avec des ingrédients premium",
  "is_featured": true
}
```

**Exemple de requête** :
```http
PUT /api/menu/items/101
Authorization: Bearer votre_jeton
Content-Type: application/json

{
  "price": 14.99,
  "is_featured": true
}
```

**Réponse** :
```json
{
  "success": true,
  "message": "Élément du menu mis à jour avec succès",
  "data": {
    "item": {
      "id": 101,
      "name": "Burger Classique Premium",
      "price": 14.99,
      "is_featured": true,
      "updated_at": "2024-01-15T11:15:00Z"
    }
  }
}
```

### Supprimer un élément du menu

**Point de terminaison** : `DELETE /api/menu/items/{id}`

**Exemple de requête** :
```http
DELETE /api/menu/items/102
Authorization: Bearer votre_jeton
```

**Réponse** :
```json
{
  "success": true,
  "message": "Élément du menu supprimé avec succès"
}
```

### Lister les catégories

**Point de terminaison** : `GET /api/menu/categories`

**Paramètres de requête** :
- `is_active` (boolean) : Filtrer par statut actif
- `with_items` (boolean) : Inclure les éléments du menu
- `sort` (string) : Champ de tri (name|sort_order|created_at)
- `order` (string) : Direction du tri (asc|desc)

**Exemple de requête** :
```http
GET /api/menu/categories?is_active=true&with_items=true
Authorization: Bearer votre_jeton
```

**Réponse** :
```json
{
  "success": true,
  "data": {
    "categories": [
      {
        "id": 1,
        "name": "Burgers",
        "description": "Nos délicieux burgers faits maison",
        "slug": "burgers",
        "image_url": "https://cdn.restopos.com/categories/burgers.jpg",
        "is_active": true,
        "sort_order": 1,
        "items_count": 8,
        "items": [
          {
            "id": 101,
            "name": "Burger Classique",
            "price": 12.99,
            "is_available": true
          }
        ]
      }
    ]
  }
}
```

### Créer une catégorie

**Point de terminaison** : `POST /api/menu/categories`

**Corps de la requête** :
```json
{
  "name": "Desserts",
  "description": "Délicieux desserts faits maison",
  "image_url": "https://cdn.restopos.com/categories/desserts.jpg",
  "is_active": true,
  "sort_order": 5
}
```

**Réponse** :
```json
{
  "success": true,
  "message": "Catégorie créée avec succès",
  "data": {
    "category": {
      "id": 6,
      "name": "Desserts",
      "slug": "desserts",
      "description": "Délicieux desserts faits maison",
      "is_active": true,
      "sort_order": 5,
      "created_at": "2024-01-15T11:30:00Z"
    }
  }
}
```

### Mettre à jour une catégorie

**Point de terminaison** : `PUT /api/menu/categories/{id}`

**Corps de la requête** :
```json
{
  "name": "Desserts & Pâtisseries",
  "description": "Nos délicieux desserts et pâtisseries artisanales",
  "sort_order": 4
}
```

**Réponse** :
```json
{
  "success": true,
  "message": "Catégorie mise à jour avec succès",
  "data": {
    "category": {
      "id": 6,
      "name": "Desserts & Pâtisseries",
      "slug": "desserts-patisseries",
      "description": "Nos délicieux desserts et pâtisseries artisanales",
      "sort_order": 4,
      "updated_at": "2024-01-15T11:45:00Z"
    }
  }
}
```

### Supprimer une catégorie

**Point de terminaison** : `DELETE /api/menu/categories/{id}`

**Exemple de requête** :
```http
DELETE /api/menu/categories/6
Authorization: Bearer votre_jeton
```

**Réponse** :
```json
{
  "success": true,
  "message": "Catégorie supprimée avec succès"
}
```

### Gérer les modificateurs

**Point de terminaison** : `GET /api/menu/modifiers`

**Exemple de requête** :
```http
GET /api/menu/modifiers
Authorization: Bearer votre_jeton
```

**Réponse** :
```json
{
  "success": true,
  "data": {
    "modifiers": [
      {
        "id": 201,
        "name": "Fromage supplémentaire",
        "type": "addon",
        "price": 1.50,
        "is_required": false,
        "is_active": true
      },
      {
        "id": 202,
        "name": "Cuisson",
        "type": "choice",
        "is_required": true,
        "is_active": true,
        "options": [
          {
            "id": 301,
            "name": "Saignant",
            "price": 0.00
          },
          {
            "id": 302,
            "name": "À point",
            "price": 0.00
          },
          {
            "id": 303,
            "name": "Bien cuit",
            "price": 0.00
          }
        ]
      }
    ]
  }
}
```

### Mettre à jour la disponibilité du menu

**Point de terminaison** : `PATCH /api/menu/items/{id}/availability`

**Corps de la requête** :
```json
{
  "is_available": false,
  "reason": "Rupture de stock"
}
```

**Exemple de requête** :
```http
PATCH /api/menu/items/101/availability
Authorization: Bearer votre_jeton
Content-Type: application/json

{
  "is_available": false
}
```

**Réponse** :
```json
{
  "success": true,
  "message": "Disponibilité mise à jour avec succès",
  "data": {
    "item": {
      "id": 101,
      "name": "Burger Classique",
      "is_available": false,
      "updated_at": "2024-01-15T12:00:00Z"
    }
  }
}
```

### Mise à jour en lot de la disponibilité

**Point de terminaison** : `PATCH /api/menu/availability/bulk`

**Corps de la requête** :
```json
{
  "items": [
    {
      "id": 101,
      "is_available": false
    },
    {
      "id": 102,
      "is_available": true
    },
    {
      "id": 103,
      "is_available": false
    }
  ]
}
```

**Réponse** :
```json
{
  "success": true,
  "message": "Disponibilité mise à jour pour 3 éléments",
  "data": {
    "updated_items": [
      {
        "id": 101,
        "name": "Burger Classique",
        "is_available": false
      },
      {
        "id": 102,
        "name": "Burger Végétarien",
        "is_available": true
      },
      {
        "id": 103,
        "name": "Burger BBQ",
        "is_available": false
      }
    ]
  }
}
```

### Analytiques du menu

**Point de terminaison** : `GET /api/menu/analytics`

**Paramètres de requête** :
- `period` (string) : today|week|month|year (défaut : week)
- `date_from` (date) : Date de début personnalisée
- `date_to` (date) : Date de fin personnalisée
- `category_id` (integer) : Filtrer par catégorie

**Exemple de requête** :
```http
GET /api/menu/analytics?period=month
Authorization: Bearer votre_jeton
```

**Réponse** :
```json
{
  "success": true,
  "data": {
    "analytics": {
      "total_items": 45,
      "active_items": 42,
      "featured_items": 8,
      "most_popular_items": [
        {
          "id": 101,
          "name": "Burger Classique",
          "orders_count": 156,
          "revenue": 2025.44
        },
        {
          "id": 105,
          "name": "Pizza Margherita",
          "orders_count": 134,
          "revenue": 1876.60
        }
      ],
      "least_popular_items": [
        {
          "id": 143,
          "name": "Salade Exotique",
          "orders_count": 3,
          "revenue": 41.97
        }
      ],
      "category_performance": [
        {
          "category_id": 1,
          "category_name": "Burgers",
          "orders_count": 245,
          "revenue": 3186.55,
          "avg_order_value": 13.01
        }
      ],
      "revenue_by_item": {
        "total_revenue": 15420.75,
        "average_item_price": 13.45
      }
    }
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
      "name": ["Le nom est requis"],
      "price": ["Le prix doit être un nombre positif"],
      "category_id": ["La catégorie sélectionnée n'existe pas"]
    }
  }
}
```

### 404 Non trouvé

```json
{
  "success": false,
  "error": {
    "code": "ITEM_NOT_FOUND",
    "message": "Élément du menu non trouvé",
    "details": "L'élément du menu avec l'ID 999 n'existe pas"
  }
}
```

### 409 Conflit

```json
{
  "success": false,
  "error": {
    "code": "DUPLICATE_ITEM",
    "message": "Élément du menu en double",
    "details": "Un élément avec ce nom existe déjà dans cette catégorie"
  }
}
```

## Exemples SDK

::: tabs

== JavaScript

```javascript
// Récupérer tous les éléments du menu
async function getMenuItems(filters = {}) {
  try {
    const params = new URLSearchParams(filters);
    const response = await fetch(`/api/menu/items?${params}`, {
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
      }
    });
    
    const result = await response.json();
    
    if (result.success) {
      return result.data.items;
    } else {
      throw new Error(result.error.message);
    }
  } catch (error) {
    console.error('Erreur récupération menu:', error);
    throw error;
  }
}

// Créer un nouvel élément du menu
async function createMenuItem(itemData) {
  try {
    const response = await fetch('/api/menu/items', {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(itemData)
    });
    
    const result = await response.json();
    
    if (result.success) {
      console.log('Élément créé:', result.data.item);
      return result.data.item;
    } else {
      throw new Error(result.error.message);
    }
  } catch (error) {
    console.error('Erreur création élément:', error);
    throw error;
  }
}

// Mettre à jour la disponibilité
async function updateItemAvailability(itemId, isAvailable) {
  try {
    const response = await fetch(`/api/menu/items/${itemId}/availability`, {
      method: 'PATCH',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ is_available: isAvailable })
    });
    
    const result = await response.json();
    return result.data.item;
  } catch (error) {
    console.error('Erreur mise à jour disponibilité:', error);
    throw error;
  }
}

// Utilisation
const menuItems = await getMenuItems({ category_id: 1, is_available: true });
const newItem = await createMenuItem({
  name: 'Nouveau Burger',
  description: 'Description du burger',
  price: 13.99,
  category_id: 1
});
await updateItemAvailability(101, false);
```

== PHP

```php
use Illuminate\Support\Facades\Http;

class MenuService
{
    private $baseUrl;
    private $token;

    public function __construct($baseUrl, $token)
    {
        $this->baseUrl = $baseUrl;
        $this->token = $token;
    }

    public function getMenuItems(array $filters = [])
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ])->get($this->baseUrl . '/api/menu/items', $filters);

        if ($response->successful()) {
            return $response->json()['data']['items'];
        }

        throw new Exception('Erreur récupération menu: ' . $response->body());
    }

    public function createMenuItem(array $itemData)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ])->post($this->baseUrl . '/api/menu/items', $itemData);

        if ($response->successful()) {
            return $response->json()['data']['item'];
        }

        throw new Exception('Erreur création élément: ' . $response->body());
    }

    public function updateItemAvailability($itemId, $isAvailable)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ])->patch($this->baseUrl . '/api/menu/items/' . $itemId . '/availability', [
            'is_available' => $isAvailable
        ]);

        if ($response->successful()) {
            return $response->json()['data']['item'];
        }

        throw new Exception('Erreur mise à jour disponibilité: ' . $response->body());
    }

    public function getMenuAnalytics($period = 'week')
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ])->get($this->baseUrl . '/api/menu/analytics', [
            'period' => $period
        ]);

        if ($response->successful()) {
            return $response->json()['data']['analytics'];
        }

        throw new Exception('Erreur récupération analytiques: ' . $response->body());
    }
}

// Utilisation
$menuService = new MenuService('https://api.restopos.com', 'votre_jeton');

$items = $menuService->getMenuItems(['category_id' => 1]);
$newItem = $menuService->createMenuItem([
    'name' => 'Nouveau Burger',
    'description' => 'Description du burger',
    'price' => 13.99,
    'category_id' => 1
]);
$analytics = $menuService->getMenuAnalytics('month');
```

== Python

```python
import requests
from typing import Dict, List, Any, Optional

class MenuAPI:
    def __init__(self, base_url: str, token: str):
        self.base_url = base_url
        self.headers = {
            'Authorization': f'Bearer {token}',
            'Content-Type': 'application/json'
        }

    def get_menu_items(self, filters: Optional[Dict[str, Any]] = None) -> List[Dict[str, Any]]:
        """Récupérer les éléments du menu"""
        params = filters or {}
        response = requests.get(
            f'{self.base_url}/api/menu/items',
            params=params,
            headers=self.headers
        )
        
        if response.status_code == 200:
            return response.json()['data']['items']
        else:
            raise Exception(f'Erreur récupération menu: {response.text}')

    def create_menu_item(self, item_data: Dict[str, Any]) -> Dict[str, Any]:
        """Créer un nouvel élément du menu"""
        response = requests.post(
            f'{self.base_url}/api/menu/items',
            json=item_data,
            headers=self.headers
        )
        
        if response.status_code == 201:
            return response.json()['data']['item']
        else:
            raise Exception(f'Erreur création élément: {response.text}')

    def update_item_availability(self, item_id: int, is_available: bool) -> Dict[str, Any]:
        """Mettre à jour la disponibilité d'un élément"""
        response = requests.patch(
            f'{self.base_url}/api/menu/items/{item_id}/availability',
            json={'is_available': is_available},
            headers=self.headers
        )
        
        if response.status_code == 200:
            return response.json()['data']['item']
        else:
            raise Exception(f'Erreur mise à jour disponibilité: {response.text}')

    def get_menu_analytics(self, period: str = 'week') -> Dict[str, Any]:
        """Récupérer les analytiques du menu"""
        response = requests.get(
            f'{self.base_url}/api/menu/analytics',
            params={'period': period},
            headers=self.headers
        )
        
        if response.status_code == 200:
            return response.json()['data']['analytics']
        else:
            raise Exception(f'Erreur récupération analytiques: {response.text}')

    def bulk_update_availability(self, items: List[Dict[str, Any]]) -> List[Dict[str, Any]]:
        """Mise à jour en lot de la disponibilité"""
        response = requests.patch(
            f'{self.base_url}/api/menu/availability/bulk',
            json={'items': items},
            headers=self.headers
        )
        
        if response.status_code == 200:
            return response.json()['data']['updated_items']
        else:
            raise Exception(f'Erreur mise à jour en lot: {response.text}')

# Utilisation
api = MenuAPI('https://api.restopos.com', 'votre_jeton')

# Récupérer les éléments du menu
items = api.get_menu_items({'category_id': 1, 'is_available': True})

# Créer un nouvel élément
nouvel_item = api.create_menu_item({
    'name': 'Nouveau Burger',
    'description': 'Description du burger',
    'price': 13.99,
    'category_id': 1
})

# Mettre à jour la disponibilité
api.update_item_availability(101, False)

# Récupérer les analytiques
analytics = api.get_menu_analytics('month')
print(f'Articles les plus populaires: {analytics["most_popular_items"]}')

# Mise à jour en lot
api.bulk_update_availability([
    {'id': 101, 'is_available': False},
    {'id': 102, 'is_available': True}
])
```

:::

## Meilleures pratiques

### Gestion du menu

1. **Nommage cohérent** : Utiliser des conventions de nommage cohérentes pour les éléments
2. **Descriptions riches** : Fournir des descriptions détaillées et attrayantes
3. **Informations sur les allergènes** : Toujours inclure les informations sur les allergènes
4. **Catégorisation logique** : Organiser les éléments en catégories logiques
5. **Mise à jour régulière** : Maintenir les prix et la disponibilité à jour

### Performance

1. **Optimisation des images** : Utiliser des images optimisées pour le web
2. **Mise en cache** : Implémenter la mise en cache pour les données du menu
3. **Pagination** : Utiliser la pagination pour les grandes listes
4. **Requêtes sélectives** : Récupérer uniquement les données nécessaires

### Intégration avec l'inventaire

1. **Mises à jour en temps réel** : Synchroniser avec le système d'inventaire
2. **Mises à jour en lot** : Utiliser les endpoints de mise à jour en lot pour l'efficacité
3. **Notifications** : Implémenter des notifications pour les ruptures de stock
4. **Historique** : Maintenir un historique des changements de disponibilité