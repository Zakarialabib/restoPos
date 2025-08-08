# Authentification

L'API RestoPos utilise une authentification basée sur des jetons pour sécuriser les points de terminaison de l'API. Ce guide couvre les méthodes d'authentification, la gestion des jetons et les meilleures pratiques de sécurité.

## Méthodes d'authentification

RestoPos prend en charge plusieurs méthodes d'authentification selon votre cas d'usage :

### 1. Authentification par jeton API

Recommandée pour les intégrations serveur-à-serveur et les applications tierces.

#### Obtenir un jeton API

**Point de terminaison** : `POST /api/auth/token`

```http
POST /api/auth/token
Content-Type: application/json

{
  "email": "admin@restopos.com",
  "password": "votre_mot_de_passe",
  "device_name": "Terminal POS 1"
}
```

**Réponse** :
```json
{
  "success": true,
  "data": {
    "token": "1|abc123def456ghi789jkl012mno345pqr678stu901vwx234yz",
    "token_type": "Bearer",
    "expires_at": "2024-12-31T23:59:59.000000Z",
    "user": {
      "id": 1,
      "name": "Utilisateur Admin",
      "email": "admin@restopos.com",
      "role": "admin"
    }
  }
}
```

#### Utiliser le jeton

Incluez le jeton dans l'en-tête `Authorization` :

```http
GET /api/orders
Authorization: Bearer 1|abc123def456ghi789jkl012mno345pqr678stu901vwx234yz
Content-Type: application/json
```

### 2. Authentification par session

Utilisée pour les applications web et les requêtes de même origine.

#### Connexion

**Point de terminaison** : `POST /api/auth/login`

```http
POST /api/auth/login
Content-Type: application/json

{
  "email": "staff@restopos.com",
  "password": "mot_de_passe_staff",
  "remember": true
}
```

**Réponse** :
```json
{
  "success": true,
  "message": "Connexion réussie",
  "data": {
    "user": {
      "id": 2,
      "name": "Membre du personnel",
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

### 3. OAuth 2.0 (Bientôt disponible)

Pour les intégrations tierces nécessitant le consentement de l'utilisateur.

## Gestion des jetons

### Portées des jetons

Les jetons peuvent être créés avec des portées spécifiques pour limiter l'accès :

```http
POST /api/auth/token
Content-Type: application/json

{
  "email": "api@restopos.com",
  "password": "mot_de_passe_api",
  "device_name": "Application d'intégration",
  "scopes": [
    "orders:read",
    "orders:write",
    "menu:read"
  ]
}
```

#### Portées disponibles

| Portée | Description |
|--------|-------------|
| `orders:read` | Voir les commandes |
| `orders:write` | Créer et modifier les commandes |
| `menu:read` | Voir les éléments du menu |
| `menu:write` | Modifier les éléments du menu |
| `customers:read` | Voir les données clients |
| `customers:write` | Modifier les données clients |
| `reports:read` | Accéder aux rapports |
| `settings:read` | Voir les paramètres |
| `settings:write` | Modifier les paramètres |
| `*` | Accès complet (admin uniquement) |

### Expiration des jetons

Les jetons ont des durées d'expiration configurables :

- **Par défaut** : 30 jours
- **Maximum** : 1 an
- **Minimum** : 1 heure

#### Actualiser les jetons

**Point de terminaison** : `POST /api/auth/refresh`

```http
POST /api/auth/refresh
Authorization: Bearer votre_jeton_actuel
```

**Réponse** :
```json
{
  "success": true,
  "data": {
    "token": "2|nouvelle_chaine_de_jeton_ici",
    "expires_at": "2024-12-31T23:59:59.000000Z"
  }
}
```

### Révoquer les jetons

#### Révoquer le jeton actuel

**Point de terminaison** : `POST /api/auth/logout`

```http
POST /api/auth/logout
Authorization: Bearer votre_jeton
```

#### Révoquer tous les jetons

**Point de terminaison** : `POST /api/auth/logout-all`

```http
POST /api/auth/logout-all
Authorization: Bearer votre_jeton
```

#### Révoquer un jeton spécifique

**Point de terminaison** : `DELETE /api/auth/tokens/{token_id}`

```http
DELETE /api/auth/tokens/123
Authorization: Bearer votre_jeton_admin
```

## Rôles utilisateur et permissions

### Hiérarchie des rôles

```
Admin
├── Manager
│   ├── Caissier
│   ├── Serveur
│   └── Personnel de cuisine
└── Utilisateur API
```

### Matrice des permissions

| Permission | Admin | Manager | Caissier | Serveur | Cuisine | API |
|------------|-------|---------|----------|---------|---------|-----|
| Voir commandes | ✅ | ✅ | ✅ | ✅ | ✅ | 🔒 |
| Créer commandes | ✅ | ✅ | ✅ | ✅ | ❌ | 🔒 |
| Modifier commandes | ✅ | ✅ | ✅ | ✅ | ❌ | 🔒 |
| Annuler commandes | ✅ | ✅ | ✅ | ❌ | ❌ | 🔒 |
| Traiter paiements | ✅ | ✅ | ✅ | ❌ | ❌ | 🔒 |
| Voir rapports | ✅ | ✅ | ❌ | ❌ | ❌ | 🔒 |
| Gérer menu | ✅ | ✅ | ❌ | ❌ | ❌ | 🔒 |
| Gérer personnel | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| Paramètres système | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |

🔒 = Dépendant de la portée

## Gestion des erreurs

### Erreurs d'authentification

#### 401 Non autorisé

```json
{
  "success": false,
  "error": {
    "code": "UNAUTHORIZED",
    "message": "Jeton invalide ou expiré",
    "details": "Le jeton d'authentification fourni est invalide ou a expiré"
  }
}
```

#### 403 Interdit

```json
{
  "success": false,
  "error": {
    "code": "FORBIDDEN",
    "message": "Permissions insuffisantes",
    "details": "Votre compte n'a pas la permission d'accéder à cette ressource",
    "required_permission": "orders.create"
  }
}
```

#### 422 Erreur de validation

```json
{
  "success": false,
  "error": {
    "code": "VALIDATION_ERROR",
    "message": "Les données fournies sont invalides",
    "details": {
      "email": ["Le champ email est requis."],
      "password": ["Le champ mot de passe est requis."]
    }
  }
}
```

## Meilleures pratiques de sécurité

### Sécurité des jetons

1. **Stockage sécurisé** : Ne jamais stocker les jetons en texte brut
2. **Utiliser HTTPS** : Toujours utiliser des connexions chiffrées
3. **Rotation régulière** : Implémenter la rotation des jetons
4. **Limiter la portée** : Utiliser les permissions minimales requises
5. **Surveiller l'utilisation** : Suivre les modèles d'utilisation des jetons

### Exemples d'implémentation

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
        // Jeton expiré, actualiser ou se ré-authentifier
        await this.refreshToken();
        return this.request(endpoint, options);
      }

      return await response.json();
    } catch (error) {
      console.error('Échec de la requête API:', error);
      throw error;
    }
  }

  async refreshToken() {
    // Implémenter la logique d'actualisation du jeton
  }
}

// Utilisation
const api = new RestoposAPI('https://api.restopos.com', 'votre_jeton');
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
            // Gérer l'expiration du jeton
            $this->refreshToken();
            return $this->request($endpoint, $method, $data);
        }

        return $response->json();
    }

    private function refreshToken()
    {
        // Implémenter la logique d'actualisation du jeton
    }
}

// Utilisation
$client = new RestoposClient('https://api.restopos.com', 'votre_jeton');
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
                # Jeton expiré, actualiser
                self.refresh_token()
                response = self.session.request(method, url, json=data)
            
            response.raise_for_status()
            return response.json()
            
        except requests.exceptions.RequestException as e:
            print(f"Échec de la requête API: {e}")
            raise

    def refresh_token(self):
        # Implémenter la logique d'actualisation du jeton
        pass

# Utilisation
api = RestoposAPI('https://api.restopos.com', 'votre_jeton')
orders = api.request('/api/orders')
```

== cURL

```bash
#!/bin/bash

# Définir les variables
BASE_URL="https://api.restopos.com"
TOKEN="votre_jeton_ici"

# Fonction pour faire des requêtes authentifiées
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

# Exemples
api_request "/api/orders"
api_request "/api/orders" "POST" '{"table_id": 1, "items": [...]}'
```

:::

## Limitation du taux

Les requêtes API sont limitées en taux pour prévenir les abus :

- **Par défaut** : 60 requêtes par minute
- **Authentifié** : 100 requêtes par minute
- **Premium** : 500 requêtes par minute

### En-têtes de limitation du taux

```http
X-RateLimit-Limit: 100
X-RateLimit-Remaining: 95
X-RateLimit-Reset: 1640995200
```

### Limite de taux dépassée

```json
{
  "success": false,
  "error": {
    "code": "RATE_LIMIT_EXCEEDED",
    "message": "Trop de requêtes",
    "details": "Limite de taux dépassée. Réessayez dans 60 secondes.",
    "retry_after": 60
  }
}
```

## Authentification des webhooks

Pour les points de terminaison webhook, RestoPos utilise des signatures HMAC-SHA256 :

### Vérifier les signatures webhook

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

// Utilisation
const isValid = verifyWebhookSignature(
  req.body,
  req.headers['x-restopos-signature'],
  process.env.WEBHOOK_SECRET
);
```

## Tester l'authentification

Utilisez le point de terminaison de test d'authentification pour vérifier votre configuration :

**Point de terminaison** : `GET /api/auth/user`

```http
GET /api/auth/user
Authorization: Bearer votre_jeton
```

**Réponse** :
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Utilisateur API",
    "email": "api@restopos.com",
    "role": "admin",
    "permissions": ["*"],
    "token_expires_at": "2024-12-31T23:59:59.000000Z"
  }
}
```

Ce point de terminaison confirme que votre jeton est valide et affiche vos permissions actuelles.