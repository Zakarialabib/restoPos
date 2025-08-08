# واجهة برمجة تطبيقات القائمة

توفر واجهة برمجة تطبيقات القائمة RestoPos إدارة شاملة لعناصر القائمة والفئات والمعدلات. يغطي هذا الدليل جميع نقاط النهاية المتاحة وهياكل البيانات وأمثلة الاستخدام.

## كائن عنصر القائمة

```json
{
  "id": 101,
  "name": "برجر كلاسيكي",
  "description": "برجر لحم بقري طازج مع الخس والطماطم والبصل",
  "price": 25.00,
  "category_id": 1,
  "category": {
    "id": 1,
    "name": "البرجر",
    "slug": "burgers"
  },
  "image_url": "https://example.com/images/classic-burger.jpg",
  "is_available": true,
  "is_featured": false,
  "preparation_time": 15,
  "calories": 650,
  "allergens": ["gluten", "dairy"],
  "dietary_info": ["halal"],
  "ingredients": [
    "لحم بقري",
    "خبز برجر",
    "خس",
    "طماطم",
    "بصل",
    "جبن شيدر"
  ],
  "modifiers": [
    {
      "id": 201,
      "name": "جبن إضافي",
      "price": 3.00,
      "is_available": true
    },
    {
      "id": 202,
      "name": "لحم إضافي",
      "price": 8.00,
      "is_available": true
    }
  ],
  "variants": [
    {
      "id": 301,
      "name": "صغير",
      "price_modifier": -5.00
    },
    {
      "id": 302,
      "name": "كبير",
      "price_modifier": 5.00
    }
  ],
  "nutritional_info": {
    "calories": 650,
    "protein": 35,
    "carbs": 45,
    "fat": 28,
    "fiber": 3,
    "sugar": 8
  },
  "tags": ["مشهور", "جديد", "حار"],
  "sort_order": 1,
  "created_at": "2024-01-15T10:00:00Z",
  "updated_at": "2024-01-15T12:30:00Z"
}
```

## كائن فئة القائمة

```json
{
  "id": 1,
  "name": "البرجر",
  "description": "مجموعة متنوعة من البرجر الطازج",
  "slug": "burgers",
  "image_url": "https://example.com/images/burgers-category.jpg",
  "is_active": true,
  "sort_order": 1,
  "parent_id": null,
  "items_count": 8,
  "created_at": "2024-01-15T10:00:00Z",
  "updated_at": "2024-01-15T12:00:00Z"
}
```

## نقاط النهاية

### عناصر القائمة

#### قائمة عناصر القائمة

**نقطة النهاية**: `GET /api/menu/items`

استرداد قائمة عناصر القائمة مع خيارات التصفية والترقيم.

##### معاملات الاستعلام

| المعامل | النوع | الوصف |
|---------|-------|-------|
| `category_id` | integer | تصفية حسب الفئة |
| `is_available` | boolean | تصفية حسب التوفر |
| `is_featured` | boolean | تصفية حسب العناصر المميزة |
| `search` | string | البحث في الاسم والوصف |
| `tags` | string | تصفية حسب العلامات (مفصولة بفاصلة) |
| `min_price` | decimal | الحد الأدنى للسعر |
| `max_price` | decimal | الحد الأقصى للسعر |
| `allergens` | string | تصفية حسب المواد المسببة للحساسية |
| `dietary_info` | string | تصفية حسب المعلومات الغذائية |
| `page` | integer | رقم الصفحة (افتراضي: 1) |
| `per_page` | integer | عناصر لكل صفحة (افتراضي: 15، أقصى: 100) |
| `sort` | string | ترتيب حسب (name، price، created_at، sort_order) |
| `direction` | string | اتجاه الترتيب (asc، desc) |

##### مثال الطلب

```http
GET /api/menu/items?category_id=1&is_available=true&page=1&per_page=20
Authorization: Bearer رمزك_المميز
```

##### مثال الاستجابة

```json
{
  "success": true,
  "data": {
    "items": [
      {
        "id": 101,
        "name": "برجر كلاسيكي",
        "description": "برجر لحم بقري طازج مع الخس والطماطم والبصل",
        "price": 25.00,
        "category": {
          "id": 1,
          "name": "البرجر"
        },
        "image_url": "https://example.com/images/classic-burger.jpg",
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

#### الحصول على عنصر قائمة

**نقطة النهاية**: `GET /api/menu/items/{id}`

استرداد عنصر قائمة محدد بجميع تفاصيله.

##### مثال الطلب

```http
GET /api/menu/items/101
Authorization: Bearer رمزك_المميز
```

##### مثال الاستجابة

```json
{
  "success": true,
  "data": {
    "id": 101,
    "name": "برجر كلاسيكي",
    "description": "برجر لحم بقري طازج مع الخس والطماطم والبصل",
    "price": 25.00,
    "category_id": 1,
    "category": {
      "id": 1,
      "name": "البرجر",
      "slug": "burgers"
    },
    "image_url": "https://example.com/images/classic-burger.jpg",
    "is_available": true,
    "is_featured": false,
    "preparation_time": 15,
    "calories": 650,
    "allergens": ["gluten", "dairy"],
    "dietary_info": ["halal"],
    "modifiers": [
      {
        "id": 201,
        "name": "جبن إضافي",
        "price": 3.00,
        "is_available": true
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
```

#### إنشاء عنصر قائمة

**نقطة النهاية**: `POST /api/menu/items`

إنشاء عنصر قائمة جديد.

##### مثال الطلب

```http
POST /api/menu/items
Authorization: Bearer رمزك_المميز
Content-Type: application/json

{
  "name": "برجر الدجاج المقرمش",
  "description": "قطعة دجاج مقرمشة مع المايونيز والخس",
  "price": 22.00,
  "category_id": 1,
  "image_url": "https://example.com/images/crispy-chicken-burger.jpg",
  "is_available": true,
  "is_featured": true,
  "preparation_time": 12,
  "calories": 580,
  "allergens": ["gluten", "eggs"],
  "dietary_info": ["halal"],
  "ingredients": [
    "دجاج مقرمش",
    "خبز برجر",
    "مايونيز",
    "خس"
  ],
  "nutritional_info": {
    "calories": 580,
    "protein": 28,
    "carbs": 42,
    "fat": 24
  },
  "tags": ["جديد", "مقرمش"]
}
```

##### مثال الاستجابة

```json
{
  "success": true,
  "message": "تم إنشاء عنصر القائمة بنجاح",
  "data": {
    "id": 102,
    "name": "برجر الدجاج المقرمش",
    "price": 22.00,
    "category_id": 1,
    "is_available": true,
    "created_at": "2024-01-15T14:30:00Z"
  }
}
```

#### تحديث عنصر قائمة

**نقطة النهاية**: `PUT /api/menu/items/{id}`

تحديث عنصر قائمة موجود.

##### مثال الطلب

```http
PUT /api/menu/items/101
Authorization: Bearer رمزك_المميز
Content-Type: application/json

{
  "price": 27.00,
  "is_featured": true,
  "description": "برجر لحم بقري طازج مع الخس والطماطم والبصل - وصفة محسنة!"
}
```

##### مثال الاستجابة

```json
{
  "success": true,
  "message": "تم تحديث عنصر القائمة بنجاح",
  "data": {
    "id": 101,
    "name": "برجر كلاسيكي",
    "price": 27.00,
    "is_featured": true,
    "updated_at": "2024-01-15T14:35:00Z"
  }
}
```

#### حذف عنصر قائمة

**نقطة النهاية**: `DELETE /api/menu/items/{id}`

حذف عنصر قائمة. سيتم حذف العنصر نهائياً من النظام.

##### مثال الطلب

```http
DELETE /api/menu/items/102
Authorization: Bearer رمزك_المميز
```

##### مثال الاستجابة

```json
{
  "success": true,
  "message": "تم حذف عنصر القائمة بنجاح"
}
```

### فئات القائمة

#### قائمة فئات القائمة

**نقطة النهاية**: `GET /api/menu/categories`

استرداد قائمة فئات القائمة.

##### معاملات الاستعلام

| المعامل | النوع | الوصف |
|---------|-------|-------|
| `is_active` | boolean | تصفية حسب الفئات النشطة |
| `parent_id` | integer | تصفية حسب الفئة الأب |
| `include_items` | boolean | تضمين عناصر كل فئة |
| `sort` | string | ترتيب حسب (name، sort_order، created_at) |
| `direction` | string | اتجاه الترتيب (asc، desc) |

##### مثال الطلب

```http
GET /api/menu/categories?is_active=true&include_items=false
Authorization: Bearer رمزك_المميز
```

##### مثال الاستجابة

```json
{
  "success": true,
  "data": {
    "categories": [
      {
        "id": 1,
        "name": "البرجر",
        "description": "مجموعة متنوعة من البرجر الطازج",
        "slug": "burgers",
        "image_url": "https://example.com/images/burgers-category.jpg",
        "is_active": true,
        "sort_order": 1,
        "items_count": 8
      },
      {
        "id": 2,
        "name": "المشروبات",
        "description": "مشروبات باردة وساخنة",
        "slug": "beverages",
        "is_active": true,
        "sort_order": 2,
        "items_count": 15
      }
    ]
  }
}
```

#### الحصول على فئة قائمة

**نقطة النهاية**: `GET /api/menu/categories/{id}`

استرداد فئة قائمة محددة مع عناصرها.

##### مثال الطلب

```http
GET /api/menu/categories/1
Authorization: Bearer رمزك_المميز
```

##### مثال الاستجابة

```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "البرجر",
    "description": "مجموعة متنوعة من البرجر الطازج",
    "slug": "burgers",
    "image_url": "https://example.com/images/burgers-category.jpg",
    "is_active": true,
    "sort_order": 1,
    "items": [
      {
        "id": 101,
        "name": "برجر كلاسيكي",
        "price": 25.00,
        "is_available": true
      },
      {
        "id": 102,
        "name": "برجر الدجاج المقرمش",
        "price": 22.00,
        "is_available": true
      }
    ]
  }
}
```

#### إنشاء فئة قائمة

**نقطة النهاية**: `POST /api/menu/categories`

إنشاء فئة قائمة جديدة.

##### مثال الطلب

```http
POST /api/menu/categories
Authorization: Bearer رمزك_المميز
Content-Type: application/json

{
  "name": "السلطات",
  "description": "سلطات طازجة وصحية",
  "slug": "salads",
  "image_url": "https://example.com/images/salads-category.jpg",
  "is_active": true,
  "sort_order": 3
}
```

##### مثال الاستجابة

```json
{
  "success": true,
  "message": "تم إنشاء فئة القائمة بنجاح",
  "data": {
    "id": 3,
    "name": "السلطات",
    "slug": "salads",
    "is_active": true,
    "created_at": "2024-01-15T14:40:00Z"
  }
}
```

#### تحديث فئة قائمة

**نقطة النهاية**: `PUT /api/menu/categories/{id}`

تحديث فئة قائمة موجودة.

##### مثال الطلب

```http
PUT /api/menu/categories/1
Authorization: Bearer رمزك_المميز
Content-Type: application/json

{
  "description": "أفضل مجموعة برجر في المدينة - طازج ولذيذ!",
  "sort_order": 1
}
```

##### مثال الاستجابة

```json
{
  "success": true,
  "message": "تم تحديث فئة القائمة بنجاح",
  "data": {
    "id": 1,
    "name": "البرجر",
    "description": "أفضل مجموعة برجر في المدينة - طازج ولذيذ!",
    "updated_at": "2024-01-15T14:45:00Z"
  }
}
```

#### حذف فئة قائمة

**نقطة النهاية**: `DELETE /api/menu/categories/{id}`

حذف فئة قائمة. لا يمكن حذف الفئات التي تحتوي على عناصر.

##### مثال الطلب

```http
DELETE /api/menu/categories/3
Authorization: Bearer رمزك_المميز
```

##### مثال الاستجابة

```json
{
  "success": true,
  "message": "تم حذف فئة القائمة بنجاح"
}
```

### المعدلات

#### قائمة المعدلات

**نقطة النهاية**: `GET /api/menu/modifiers`

استرداد قائمة المعدلات المتاحة.

##### مثال الطلب

```http
GET /api/menu/modifiers
Authorization: Bearer رمزك_المميز
```

##### مثال الاستجابة

```json
{
  "success": true,
  "data": {
    "modifiers": [
      {
        "id": 201,
        "name": "جبن إضافي",
        "price": 3.00,
        "is_available": true,
        "category": "إضافات"
      },
      {
        "id": 202,
        "name": "لحم إضافي",
        "price": 8.00,
        "is_available": true,
        "category": "إضافات"
      }
    ]
  }
}
```

#### إنشاء معدل

**نقطة النهاية**: `POST /api/menu/modifiers`

إنشاء معدل جديد.

##### مثال الطلب

```http
POST /api/menu/modifiers
Authorization: Bearer رمزك_المميز
Content-Type: application/json

{
  "name": "صوص حار",
  "price": 1.50,
  "is_available": true,
  "category": "صوصات"
}
```

##### مثال الاستجابة

```json
{
  "success": true,
  "message": "تم إنشاء المعدل بنجاح",
  "data": {
    "id": 203,
    "name": "صوص حار",
    "price": 1.50,
    "is_available": true,
    "created_at": "2024-01-15T14:50:00Z"
  }
}
```

### توفر القائمة

#### تحديث توفر عنصر واحد

**نقطة النهاية**: `PATCH /api/menu/items/{id}/availability`

تحديث حالة توفر عنصر قائمة واحد.

##### مثال الطلب

```http
PATCH /api/menu/items/101/availability
Authorization: Bearer رمزك_المميز
Content-Type: application/json

{
  "is_available": false,
  "reason": "نفد المخزون"
}
```

##### مثال الاستجابة

```json
{
  "success": true,
  "message": "تم تحديث توفر العنصر بنجاح",
  "data": {
    "id": 101,
    "name": "برجر كلاسيكي",
    "is_available": false,
    "updated_at": "2024-01-15T14:55:00Z"
  }
}
```

#### تحديث توفر متعدد

**نقطة النهاية**: `PATCH /api/menu/availability/bulk`

تحديث حالة توفر عناصر متعددة في طلب واحد.

##### مثال الطلب

```http
PATCH /api/menu/availability/bulk
Authorization: Bearer رمزك_المميز
Content-Type: application/json

{
  "items": [
    {
      "id": 101,
      "is_available": true
    },
    {
      "id": 102,
      "is_available": false,
      "reason": "قيد التحضير"
    },
    {
      "id": 103,
      "is_available": true
    }
  ]
}
```

##### مثال الاستجابة

```json
{
  "success": true,
  "message": "تم تحديث توفر العناصر بنجاح",
  "data": {
    "updated_items": 3,
    "items": [
      {
        "id": 101,
        "name": "برجر كلاسيكي",
        "is_available": true
      },
      {
        "id": 102,
        "name": "برجر الدجاج المقرمش",
        "is_available": false
      },
      {
        "id": 103,
        "name": "برجر اللحم المشوي",
        "is_available": true
      }
    ]
  }
}
```

### تحليلات القائمة

**نقطة النهاية**: `GET /api/menu/analytics`

استرداد تحليلات وإحصائيات القائمة.

#### معاملات الاستعلام

| المعامل | النوع | الوصف |
|---------|-------|-------|
| `period` | string | الفترة (today، week، month، year، custom) |
| `date_from` | date | تاريخ البداية للفترة المخصصة |
| `date_to` | date | تاريخ النهاية للفترة المخصصة |
| `category_id` | integer | تصفية حسب الفئة |

#### مثال الطلب

```http
GET /api/menu/analytics?period=week&category_id=1
Authorization: Bearer رمزك_المميز
```

#### مثال الاستجابة

```json
{
  "success": true,
  "data": {
    "summary": {
      "total_items": 45,
      "available_items": 42,
      "featured_items": 8,
      "total_categories": 6,
      "average_price": 18.50
    },
    "popular_items": [
      {
        "id": 101,
        "name": "برجر كلاسيكي",
        "orders_count": 156,
        "revenue": 3900.00
      },
      {
        "id": 105,
        "name": "بيتزا مارجريتا",
        "orders_count": 89,
        "revenue": 2225.00
      }
    ],
    "category_performance": [
      {
        "category_id": 1,
        "category_name": "البرجر",
        "orders_count": 245,
        "revenue": 6125.00,
        "items_count": 8
      }
    ],
    "low_performing_items": [
      {
        "id": 120,
        "name": "سلطة الكينوا",
        "orders_count": 3,
        "revenue": 45.00
      }
    ]
  }
}
```

## معالجة الأخطاء

### 400 طلب خاطئ

```json
{
  "success": false,
  "error": {
    "code": "VALIDATION_ERROR",
    "message": "البيانات المقدمة غير صالحة",
    "details": {
      "name": ["حقل الاسم مطلوب."],
      "price": ["السعر يجب أن يكون أكبر من صفر."],
      "category_id": ["الفئة المحددة غير موجودة."]
    }
  }
}
```

### 404 غير موجود

```json
{
  "success": false,
  "error": {
    "code": "ITEM_NOT_FOUND",
    "message": "عنصر القائمة غير موجود",
    "details": "عنصر القائمة برقم 101 غير موجود أو تم حذفه."
  }
}
```

### 409 تضارب

```json
{
  "success": false,
  "error": {
    "code": "CATEGORY_HAS_ITEMS",
    "message": "لا يمكن حذف الفئة",
    "details": "لا يمكن حذف الفئة لأنها تحتوي على عناصر. قم بحذف أو نقل العناصر أولاً.",
    "items_count": 8
  }
}
```

## أمثلة SDK

::: tabs

== JavaScript

```javascript
class MenuAPI {
  constructor(client) {
    this.client = client;
  }

  // قائمة العناصر
  async getItems(filters = {}) {
    const params = new URLSearchParams(filters);
    return await this.client.request(`/api/menu/items?${params}`);
  }

  // الحصول على عنصر
  async getItem(itemId) {
    return await this.client.request(`/api/menu/items/${itemId}`);
  }

  // إنشاء عنصر
  async createItem(itemData) {
    return await this.client.request('/api/menu/items', {
      method: 'POST',
      body: JSON.stringify(itemData)
    });
  }

  // تحديث عنصر
  async updateItem(itemId, updates) {
    return await this.client.request(`/api/menu/items/${itemId}`, {
      method: 'PUT',
      body: JSON.stringify(updates)
    });
  }

  // تحديث التوفر
  async updateAvailability(itemId, isAvailable, reason = null) {
    return await this.client.request(`/api/menu/items/${itemId}/availability`, {
      method: 'PATCH',
      body: JSON.stringify({ is_available: isAvailable, reason })
    });
  }

  // تحديث توفر متعدد
  async bulkUpdateAvailability(items) {
    return await this.client.request('/api/menu/availability/bulk', {
      method: 'PATCH',
      body: JSON.stringify({ items })
    });
  }

  // قائمة الفئات
  async getCategories(includeItems = false) {
    return await this.client.request(`/api/menu/categories?include_items=${includeItems}`);
  }

  // تحليلات القائمة
  async getAnalytics(period = 'week') {
    return await this.client.request(`/api/menu/analytics?period=${period}`);
  }
}

// الاستخدام
const menu = new MenuAPI(restoposClient);

// الحصول على جميع العناصر المتاحة
const availableItems = await menu.getItems({ is_available: true });

// إنشاء عنصر جديد
const newItem = await menu.createItem({
  name: 'برجر الجبن الثلاثي',
  description: 'برجر بثلاث طبقات من الجبن',
  price: 32.00,
  category_id: 1,
  is_available: true
});

// تحديث توفر عدة عناصر
await menu.bulkUpdateAvailability([
  { id: 101, is_available: true },
  { id: 102, is_available: false, reason: 'نفد المخزون' }
]);

console.log('تم تحديث القائمة بنجاح');
```

== PHP

```php
class MenuAPI
{
    private $client;

    public function __construct($client)
    {
        $this->client = $client;
    }

    // قائمة العناصر
    public function getItems($filters = [])
    {
        $query = http_build_query($filters);
        return $this->client->request("/api/menu/items?{$query}");
    }

    // الحصول على عنصر
    public function getItem($itemId)
    {
        return $this->client->request("/api/menu/items/{$itemId}");
    }

    // إنشاء عنصر
    public function createItem($itemData)
    {
        return $this->client->request('/api/menu/items', 'POST', $itemData);
    }

    // تحديث عنصر
    public function updateItem($itemId, $updates)
    {
        return $this->client->request("/api/menu/items/{$itemId}", 'PUT', $updates);
    }

    // تحديث التوفر
    public function updateAvailability($itemId, $isAvailable, $reason = null)
    {
        $data = ['is_available' => $isAvailable];
        if ($reason) {
            $data['reason'] = $reason;
        }
        
        return $this->client->request("/api/menu/items/{$itemId}/availability", 'PATCH', $data);
    }

    // تحديث توفر متعدد
    public function bulkUpdateAvailability($items)
    {
        return $this->client->request('/api/menu/availability/bulk', 'PATCH', [
            'items' => $items
        ]);
    }

    // قائمة الفئات
    public function getCategories($includeItems = false)
    {
        return $this->client->request("/api/menu/categories?include_items={$includeItems}");
    }

    // تحليلات القائمة
    public function getAnalytics($period = 'week')
    {
        return $this->client->request("/api/menu/analytics?period={$period}");
    }
}

// الاستخدام
$menu = new MenuAPI($restoposClient);

// الحصول على جميع العناصر المتاحة
$availableItems = $menu->getItems(['is_available' => true]);

// إنشاء عنصر جديد
$newItem = $menu->createItem([
    'name' => 'برجر الجبن الثلاثي',
    'description' => 'برجر بثلاث طبقات من الجبن',
    'price' => 32.00,
    'category_id' => 1,
    'is_available' => true
]);

echo "تم إنشاء العنصر: " . $newItem['data']['name'];
```

== Python

```python
class MenuAPI:
    def __init__(self, client):
        self.client = client

    def get_items(self, filters=None):
        """قائمة العناصر"""
        if filters is None:
            filters = {}
        
        params = '&'.join([f"{k}={v}" for k, v in filters.items()])
        return self.client.request(f"/api/menu/items?{params}")

    def get_item(self, item_id):
        """الحصول على عنصر"""
        return self.client.request(f"/api/menu/items/{item_id}")

    def create_item(self, item_data):
        """إنشاء عنصر"""
        return self.client.request('/api/menu/items', 'POST', item_data)

    def update_item(self, item_id, updates):
        """تحديث عنصر"""
        return self.client.request(f"/api/menu/items/{item_id}", 'PUT', updates)

    def update_availability(self, item_id, is_available, reason=None):
        """تحديث التوفر"""
        data = {'is_available': is_available}
        if reason:
            data['reason'] = reason
            
        return self.client.request(f"/api/menu/items/{item_id}/availability", 'PATCH', data)

    def bulk_update_availability(self, items):
        """تحديث توفر متعدد"""
        return self.client.request('/api/menu/availability/bulk', 'PATCH', {
            'items': items
        })

    def get_categories(self, include_items=False):
        """قائمة الفئات"""
        return self.client.request(f"/api/menu/categories?include_items={include_items}")

    def get_analytics(self, period='week'):
        """تحليلات القائمة"""
        return self.client.request(f"/api/menu/analytics?period={period}")

# الاستخدام
menu = MenuAPI(restopos_client)

# الحصول على جميع العناصر المتاحة
available_items = menu.get_items({'is_available': True})

# إنشاء عنصر جديد
new_item = menu.create_item({
    'name': 'برجر الجبن الثلاثي',
    'description': 'برجر بثلاث طبقات من الجبن',
    'price': 32.00,
    'category_id': 1,
    'is_available': True
})

print(f"تم إنشاء العنصر: {new_item['data']['name']}")
```

:::

## أفضل الممارسات

### إدارة القائمة

1. **التسمية المتسقة**: استخدام أسماء واضحة ومتسقة للعناصر والفئات
2. **الوصف الغني**: تقديم أوصاف مفصلة تتضمن المكونات والمواد المسببة للحساسية
3. **معلومات الحساسية**: تحديد جميع المواد المسببة للحساسية بوضوح
4. **التحديث المنتظم**: مراجعة وتحديث القائمة بانتظام

### تحسين الأداء

1. **تحسين الصور**: ضغط صور القائمة لتحسين أوقات التحميل
2. **التخزين المؤقت**: تخزين بيانات القائمة مؤقتاً لتحسين الأداء
3. **الترقيم**: استخدام الترقيم للقوائم الكبيرة
4. **الفهرسة**: فهرسة الحقول المستخدمة في البحث والتصفية

### تكامل المخزون

1. **التحديثات الفورية**: تحديث التوفر فور تغيير مستويات المخزون
2. **التحديثات المجمعة**: استخدام التحديثات المجمعة للعمليات الكبيرة
3. **التنبيهات**: إعداد تنبيهات عند انخفاض المخزون
4. **التتبع التلقائي**: ربط القائمة بنظام إدارة المخزون