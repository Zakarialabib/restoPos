# المصادقة

تستخدم واجهة برمجة التطبيقات RestoPos مصادقة قائمة على الرموز المميزة لتأمين نقاط النهاية الخاصة بواجهة برمجة التطبيقات. يغطي هذا الدليل طرق المصادقة وإدارة الرموز المميزة وأفضل الممارسات الأمنية.

## طرق المصادقة

يدعم RestoPos عدة طرق مصادقة حسب حالة الاستخدام الخاصة بك:

### 1. مصادقة رمز واجهة برمجة التطبيقات

موصى بها للتكامل من خادم إلى خادم وتطبيقات الطرف الثالث.

#### الحصول على رمز واجهة برمجة التطبيقات

**نقطة النهاية**: `POST /api/auth/token`

```http
POST /api/auth/token
Content-Type: application/json

{
  "email": "admin@restopos.com",
  "password": "كلمة_المرور_الخاصة_بك",
  "device_name": "محطة نقاط البيع 1"
}
```

**الاستجابة**:
```json
{
  "success": true,
  "data": {
    "token": "1|abc123def456ghi789jkl012mno345pqr678stu901vwx234yz",
    "token_type": "Bearer",
    "expires_at": "2024-12-31T23:59:59.000000Z",
    "user": {
      "id": 1,
      "name": "مستخدم المشرف",
      "email": "admin@restopos.com",
      "role": "admin"
    }
  }
}
```

#### استخدام الرمز المميز

قم بتضمين الرمز المميز في رأس `Authorization`:

```http
GET /api/orders
Authorization: Bearer 1|abc123def456ghi789jkl012mno345pqr678stu901vwx234yz
Content-Type: application/json
```

### 2. مصادقة الجلسة

تُستخدم لتطبيقات الويب والطلبات من نفس المصدر.

#### تسجيل الدخول

**نقطة النهاية**: `POST /api/auth/login`

```http
POST /api/auth/login
Content-Type: application/json

{
  "email": "staff@restopos.com",
  "password": "كلمة_مرور_الموظف",
  "remember": true
}
```

**الاستجابة**:
```json
{
  "success": true,
  "message": "تم تسجيل الدخول بنجاح",
  "data": {
    "user": {
      "id": 2,
      "name": "عضو الطاقم",
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

### 3. OAuth 2.0 (قريباً)

للتكامل مع الطرف الثالث الذي يتطلب موافقة المستخدم.

## إدارة الرموز المميزة

### نطاقات الرموز المميزة

يمكن إنشاء الرموز المميزة بنطاقات محددة لتقييد الوصول:

```http
POST /api/auth/token
Content-Type: application/json

{
  "email": "api@restopos.com",
  "password": "كلمة_مرور_واجهة_برمجة_التطبيقات",
  "device_name": "تطبيق التكامل",
  "scopes": [
    "orders:read",
    "orders:write",
    "menu:read"
  ]
}
```

#### النطاقات المتاحة

| النطاق | الوصف |
|--------|-------|
| `orders:read` | عرض الطلبات |
| `orders:write` | إنشاء وتعديل الطلبات |
| `menu:read` | عرض عناصر القائمة |
| `menu:write` | تعديل عناصر القائمة |
| `customers:read` | عرض بيانات العملاء |
| `customers:write` | تعديل بيانات العملاء |
| `reports:read` | الوصول إلى التقارير |
| `settings:read` | عرض الإعدادات |
| `settings:write` | تعديل الإعدادات |
| `*` | وصول كامل (للمشرف فقط) |

### انتهاء صلاحية الرموز المميزة

الرموز المميزة لها مدد انتهاء صلاحية قابلة للتكوين:

- **افتراضي**: 30 يوماً
- **أقصى حد**: سنة واحدة
- **أدنى حد**: ساعة واحدة

#### تجديد الرموز المميزة

**نقطة النهاية**: `POST /api/auth/refresh`

```http
POST /api/auth/refresh
Authorization: Bearer رمزك_الحالي
```

**الاستجابة**:
```json
{
  "success": true,
  "data": {
    "token": "2|سلسلة_رمز_جديدة_هنا",
    "expires_at": "2024-12-31T23:59:59.000000Z"
  }
}
```

### إلغاء الرموز المميزة

#### إلغاء الرمز المميز الحالي

**نقطة النهاية**: `POST /api/auth/logout`

```http
POST /api/auth/logout
Authorization: Bearer رمزك
```

#### إلغاء جميع الرموز المميزة

**نقطة النهاية**: `POST /api/auth/logout-all`

```http
POST /api/auth/logout-all
Authorization: Bearer رمزك
```

#### إلغاء رمز مميز محدد

**نقطة النهاية**: `DELETE /api/auth/tokens/{token_id}`

```http
DELETE /api/auth/tokens/123
Authorization: Bearer رمز_المشرف_الخاص_بك
```

## أدوار المستخدم والصلاحيات

### تسلسل الأدوار

```
مشرف
├── مدير
│   ├── أمين صندوق
│   ├── نادل
│   └── طاقم المطبخ
└── مستخدم واجهة برمجة التطبيقات
```

### مصفوفة الصلاحيات

| الصلاحية | مشرف | مدير | أمين صندوق | نادل | مطبخ | واجهة برمجة التطبيقات |
|----------|-------|------|-------------|------|------|----------------------|
| عرض الطلبات | ✅ | ✅ | ✅ | ✅ | ✅ | 🔒 |
| إنشاء الطلبات | ✅ | ✅ | ✅ | ✅ | ❌ | 🔒 |
| تعديل الطلبات | ✅ | ✅ | ✅ | ✅ | ❌ | 🔒 |
| إلغاء الطلبات | ✅ | ✅ | ✅ | ❌ | ❌ | 🔒 |
| معالجة المدفوعات | ✅ | ✅ | ✅ | ❌ | ❌ | 🔒 |
| عرض التقارير | ✅ | ✅ | ❌ | ❌ | ❌ | 🔒 |
| إدارة القائمة | ✅ | ✅ | ❌ | ❌ | ❌ | 🔒 |
| إدارة الطاقم | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| إعدادات النظام | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |

🔒 = يعتمد على النطاق

## معالجة الأخطاء

### أخطاء المصادقة

#### 401 غير مصرح

```json
{
  "success": false,
  "error": {
    "code": "UNAUTHORIZED",
    "message": "رمز مميز غير صالح أو منتهي الصلاحية",
    "details": "رمز المصادقة المقدم غير صالح أو منتهي الصلاحية"
  }
}
```

#### 403 محظور

```json
{
  "success": false,
  "error": {
    "code": "FORBIDDEN",
    "message": "صلاحيات غير كافية",
    "details": "حسابك لا يملك الصلاحية للوصول إلى هذا المورد",
    "required_permission": "orders.create"
  }
}
```

#### 422 خطأ في التحقق

```json
{
  "success": false,
  "error": {
    "code": "VALIDATION_ERROR",
    "message": "البيانات المقدمة غير صالحة",
    "details": {
      "email": ["حقل البريد الإلكتروني مطلوب."],
      "password": ["حقل كلمة المرور مطلوب."]
    }
  }
}
```

## أفضل الممارسات الأمنية

### أمان الرموز المميزة

1. **التخزين الآمن**: عدم تخزين الرموز المميزة كنص خام أبداً
2. **استخدام HTTPS**: استخدام الاتصالات المشفرة دائماً
3. **التدوير المنتظم**: تنفيذ تدوير الرموز المميزة
4. **تقييد النطاق**: استخدام أقل الصلاحيات المطلوبة
5. **مراقبة الاستخدام**: تتبع أنماط استخدام الرموز المميزة

### أمثلة التنفيذ

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
        // انتهت صلاحية الرمز المميز، قم بالتجديد أو إعادة المصادقة
        await this.refreshToken();
        return this.request(endpoint, options);
      }

      return await response.json();
    } catch (error) {
      console.error('فشل طلب واجهة برمجة التطبيقات:', error);
      throw error;
    }
  }

  async refreshToken() {
    // تنفيذ منطق تجديد الرمز المميز
  }
}

// الاستخدام
const api = new RestoposAPI('https://api.restopos.com', 'رمزك_المميز');
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
            // معالجة انتهاء صلاحية الرمز المميز
            $this->refreshToken();
            return $this->request($endpoint, $method, $data);
        }

        return $response->json();
    }

    private function refreshToken()
    {
        // تنفيذ منطق تجديد الرمز المميز
    }
}

// الاستخدام
$client = new RestoposClient('https://api.restopos.com', 'رمزك_المميز');
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
                # انتهت صلاحية الرمز المميز، قم بالتجديد
                self.refresh_token()
                response = self.session.request(method, url, json=data)
            
            response.raise_for_status()
            return response.json()
            
        except requests.exceptions.RequestException as e:
            print(f"فشل طلب واجهة برمجة التطبيقات: {e}")
            raise

    def refresh_token(self):
        # تنفيذ منطق تجديد الرمز المميز
        pass

# الاستخدام
api = RestoposAPI('https://api.restopos.com', 'رمزك_المميز')
orders = api.request('/api/orders')
```

== cURL

```bash
#!/bin/bash

# تعيين المتغيرات
BASE_URL="https://api.restopos.com"
TOKEN="رمزك_المميز_هنا"

# دالة لإجراء طلبات مصادقة
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

# أمثلة
api_request "/api/orders"
api_request "/api/orders" "POST" '{"table_id": 1, "items": [...]}'
```

:::

## تحديد المعدل

طلبات واجهة برمجة التطبيقات محدودة المعدل لمنع الإساءة:

- **افتراضي**: 60 طلب في الدقيقة
- **مصادق عليه**: 100 طلب في الدقيقة
- **مميز**: 500 طلب في الدقيقة

### رؤوس تحديد المعدل

```http
X-RateLimit-Limit: 100
X-RateLimit-Remaining: 95
X-RateLimit-Reset: 1640995200
```

### تجاوز حد المعدل

```json
{
  "success": false,
  "error": {
    "code": "RATE_LIMIT_EXCEEDED",
    "message": "طلبات كثيرة جداً",
    "details": "تم تجاوز حد المعدل. حاول مرة أخرى خلال 60 ثانية.",
    "retry_after": 60
  }
}
```

## مصادقة الـ Webhooks

لنقاط نهاية الـ webhook، يستخدم RestoPos توقيعات HMAC-SHA256:

### التحقق من توقيعات الـ webhook

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

// الاستخدام
const isValid = verifyWebhookSignature(
  req.body,
  req.headers['x-restopos-signature'],
  process.env.WEBHOOK_SECRET
);
```

## اختبار المصادقة

استخدم نقطة نهاية اختبار المصادقة للتحقق من إعدادك:

**نقطة النهاية**: `GET /api/auth/user`

```http
GET /api/auth/user
Authorization: Bearer رمزك_المميز
```

**الاستجابة**:
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "مستخدم واجهة برمجة التطبيقات",
    "email": "api@restopos.com",
    "role": "admin",
    "permissions": ["*"],
    "token_expires_at": "2024-12-31T23:59:59.000000Z"
  }
}
```

تؤكد نقطة النهاية هذه أن رمزك المميز صالح وتعرض صلاحياتك الحالية.