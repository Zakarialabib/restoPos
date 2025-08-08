# واجهة برمجة تطبيقات الطلبات

توفر واجهة برمجة تطبيقات الطلبات RestoPos إدارة شاملة للطلبات، بما في ذلك إنشاء الطلبات وتحديثها وتتبعها ومعالجتها. يغطي هذا الدليل جميع نقاط النهاية المتاحة وهياكل البيانات وأمثلة الاستخدام.

## كائن الطلب

```json
{
  "id": 12345,
  "order_number": "ORD-2024-001",
  "status": "confirmed",
  "type": "dine_in",
  "table_id": 5,
  "customer": {
    "id": 789,
    "name": "أحمد محمد",
    "email": "ahmed@example.com",
    "phone": "+966501234567"
  },
  "items": [
    {
      "id": 1,
      "menu_item_id": 101,
      "name": "برجر كلاسيكي",
      "quantity": 2,
      "unit_price": 25.00,
      "total_price": 50.00,
      "modifiers": [
        {
          "id": 201,
          "name": "جبن إضافي",
          "price": 3.00
        }
      ],
      "special_instructions": "بدون بصل"
    }
  ],
  "subtotal": 53.00,
  "tax_amount": 7.95,
  "discount_amount": 5.00,
  "total_amount": 55.95,
  "payment_status": "paid",
  "kitchen_status": "preparing",
  "estimated_ready_time": "2024-01-15T14:30:00Z",
  "actual_ready_time": null,
  "notes": "طلب للاحتفال بعيد ميلاد",
  "created_at": "2024-01-15T14:00:00Z",
  "updated_at": "2024-01-15T14:05:00Z",
  "created_by": {
    "id": 10,
    "name": "سارة أحمد",
    "role": "waitress"
  }
}
```

### قيم الحالة

| الحالة | الوصف |
|--------|-------|
| `pending` | في انتظار التأكيد |
| `confirmed` | مؤكد ومرسل للمطبخ |
| `preparing` | قيد التحضير |
| `ready` | جاهز للتقديم/الاستلام |
| `served` | تم التقديم |
| `completed` | مكتمل ومدفوع |
| `cancelled` | ملغي |
| `refunded` | مسترد |

### أنواع الطلبات

| النوع | الوصف |
|-------|-------|
| `dine_in` | تناول في المطعم |
| `takeaway` | طلب خارجي |
| `delivery` | توصيل |
| `drive_through` | خدمة السيارات |

## نقاط النهاية

### قائمة الطلبات

**نقطة النهاية**: `GET /api/orders`

استرداد قائمة الطلبات مع خيارات التصفية والترقيم.

#### معاملات الاستعلام

| المعامل | النوع | الوصف |
|---------|-------|-------|
| `status` | string | تصفية حسب الحالة |
| `type` | string | تصفية حسب النوع |
| `table_id` | integer | تصفية حسب الطاولة |
| `customer_id` | integer | تصفية حسب العميل |
| `date_from` | date | تاريخ البداية (YYYY-MM-DD) |
| `date_to` | date | تاريخ النهاية (YYYY-MM-DD) |
| `page` | integer | رقم الصفحة (افتراضي: 1) |
| `per_page` | integer | عناصر لكل صفحة (افتراضي: 15، أقصى: 100) |
| `sort` | string | ترتيب حسب (created_at، total_amount، status) |
| `direction` | string | اتجاه الترتيب (asc، desc) |

#### مثال الطلب

```http
GET /api/orders?status=confirmed&type=dine_in&page=1&per_page=20
Authorization: Bearer رمزك_المميز
```

#### مثال الاستجابة

```json
{
  "success": true,
  "data": {
    "orders": [
      {
        "id": 12345,
        "order_number": "ORD-2024-001",
        "status": "confirmed",
        "type": "dine_in",
        "table_id": 5,
        "total_amount": 55.95,
        "created_at": "2024-01-15T14:00:00Z"
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

### استرداد طلب

**نقطة النهاية**: `GET /api/orders/{id}`

استرداد طلب محدد بجميع تفاصيله.

#### مثال الطلب

```http
GET /api/orders/12345
Authorization: Bearer رمزك_المميز
```

#### مثال الاستجابة

```json
{
  "success": true,
  "data": {
    "id": 12345,
    "order_number": "ORD-2024-001",
    "status": "confirmed",
    "type": "dine_in",
    "table_id": 5,
    "customer": {
      "id": 789,
      "name": "أحمد محمد",
      "email": "ahmed@example.com",
      "phone": "+966501234567"
    },
    "items": [
      {
        "id": 1,
        "menu_item_id": 101,
        "name": "برجر كلاسيكي",
        "quantity": 2,
        "unit_price": 25.00,
        "total_price": 50.00,
        "modifiers": [
          {
            "id": 201,
            "name": "جبن إضافي",
            "price": 3.00
          }
        ],
        "special_instructions": "بدون بصل"
      }
    ],
    "subtotal": 53.00,
    "tax_amount": 7.95,
    "discount_amount": 5.00,
    "total_amount": 55.95,
    "payment_status": "paid",
    "kitchen_status": "preparing",
    "estimated_ready_time": "2024-01-15T14:30:00Z",
    "notes": "طلب للاحتفال بعيد ميلاد",
    "created_at": "2024-01-15T14:00:00Z",
    "updated_at": "2024-01-15T14:05:00Z"
  }
}
```

### إنشاء طلب

**نقطة النهاية**: `POST /api/orders`

إنشاء طلب جديد.

#### مثال الطلب

```http
POST /api/orders
Authorization: Bearer رمزك_المميز
Content-Type: application/json

{
  "type": "dine_in",
  "table_id": 5,
  "customer": {
    "name": "فاطمة علي",
    "email": "fatima@example.com",
    "phone": "+966501234567"
  },
  "items": [
    {
      "menu_item_id": 101,
      "quantity": 2,
      "modifiers": [201],
      "special_instructions": "بدون بصل"
    },
    {
      "menu_item_id": 102,
      "quantity": 1
    }
  ],
  "notes": "طلب عاجل",
  "discount_code": "WELCOME10"
}
```

#### مثال الاستجابة

```json
{
  "success": true,
  "message": "تم إنشاء الطلب بنجاح",
  "data": {
    "id": 12346,
    "order_number": "ORD-2024-002",
    "status": "pending",
    "type": "dine_in",
    "table_id": 5,
    "total_amount": 48.50,
    "created_at": "2024-01-15T14:15:00Z"
  }
}
```

### تحديث طلب

**نقطة النهاية**: `PUT /api/orders/{id}`

تحديث طلب موجود.

#### مثال الطلب

```http
PUT /api/orders/12345
Authorization: Bearer رمزك_المميز
Content-Type: application/json

{
  "status": "confirmed",
  "notes": "تم تأكيد الطلب - طلب عاجل",
  "estimated_ready_time": "2024-01-15T14:45:00Z"
}
```

#### مثال الاستجابة

```json
{
  "success": true,
  "message": "تم تحديث الطلب بنجاح",
  "data": {
    "id": 12345,
    "status": "confirmed",
    "estimated_ready_time": "2024-01-15T14:45:00Z",
    "updated_at": "2024-01-15T14:20:00Z"
  }
}
```

### إلغاء طلب

**نقطة النهاية**: `DELETE /api/orders/{id}`

إلغاء طلب. يمكن إلغاء الطلبات فقط إذا كانت في حالة `pending` أو `confirmed`.

#### مثال الطلب

```http
DELETE /api/orders/12345
Authorization: Bearer رمزك_المميز
Content-Type: application/json

{
  "reason": "طلب العميل",
  "refund_payment": true
}
```

#### مثال الاستجابة

```json
{
  "success": true,
  "message": "تم إلغاء الطلب بنجاح",
  "data": {
    "id": 12345,
    "status": "cancelled",
    "cancelled_at": "2024-01-15T14:25:00Z",
    "cancellation_reason": "طلب العميل",
    "refund_status": "processing"
  }
}
```

### إضافة عناصر للطلب

**نقطة النهاية**: `POST /api/orders/{id}/items`

إضافة عناصر جديدة لطلب موجود.

#### مثال الطلب

```http
POST /api/orders/12345/items
Authorization: Bearer رمزك_المميز
Content-Type: application/json

{
  "items": [
    {
      "menu_item_id": 103,
      "quantity": 1,
      "special_instructions": "إضافي حار"
    }
  ]
}
```

#### مثال الاستجابة

```json
{
  "success": true,
  "message": "تم إضافة العناصر بنجاح",
  "data": {
    "order_id": 12345,
    "new_items": [
      {
        "id": 2,
        "menu_item_id": 103,
        "name": "أجنحة دجاج حارة",
        "quantity": 1,
        "unit_price": 18.00,
        "total_price": 18.00
      }
    ],
    "updated_total": 73.95
  }
}
```

### إزالة عناصر من الطلب

**نقطة النهاية**: `DELETE /api/orders/{id}/items/{item_id}`

إزالة عنصر من طلب موجود.

#### مثال الطلب

```http
DELETE /api/orders/12345/items/2
Authorization: Bearer رمزك_المميز
```

#### مثال الاستجابة

```json
{
  "success": true,
  "message": "تم إزالة العنصر بنجاح",
  "data": {
    "order_id": 12345,
    "removed_item_id": 2,
    "updated_total": 55.95
  }
}
```

### إحصائيات الطلبات

**نقطة النهاية**: `GET /api/orders/statistics`

استرداد إحصائيات الطلبات للفترة المحددة.

#### معاملات الاستعلام

| المعامل | النوع | الوصف |
|---------|-------|-------|
| `period` | string | الفترة (today، week، month، year، custom) |
| `date_from` | date | تاريخ البداية للفترة المخصصة |
| `date_to` | date | تاريخ النهاية للفترة المخصصة |
| `group_by` | string | تجميع حسب (hour، day، week، month) |

#### مثال الطلب

```http
GET /api/orders/statistics?period=today&group_by=hour
Authorization: Bearer رمزك_المميز
```

#### مثال الاستجابة

```json
{
  "success": true,
  "data": {
    "summary": {
      "total_orders": 45,
      "total_revenue": 1250.75,
      "average_order_value": 27.79,
      "completed_orders": 38,
      "cancelled_orders": 2,
      "pending_orders": 5
    },
    "by_status": {
      "pending": 5,
      "confirmed": 8,
      "preparing": 12,
      "ready": 3,
      "served": 15,
      "completed": 2
    },
    "by_type": {
      "dine_in": 28,
      "takeaway": 12,
      "delivery": 5
    },
    "hourly_breakdown": [
      {
        "hour": "12:00",
        "orders": 8,
        "revenue": 220.50
      },
      {
        "hour": "13:00",
        "orders": 15,
        "revenue": 425.25
      }
    ]
  }
}
```

### تكامل شاشة المطبخ

**نقطة النهاية**: `GET /api/orders/kitchen`

استرداد الطلبات للعرض في شاشة المطبخ.

#### معاملات الاستعلام

| المعامل | النوع | الوصف |
|---------|-------|-------|
| `status` | string | تصفية حسب الحالة (افتراضي: confirmed,preparing) |
| `priority` | string | ترتيب الأولوية (urgent، normal، low) |

#### مثال الطلب

```http
GET /api/orders/kitchen?status=confirmed,preparing
Authorization: Bearer رمزك_المميز
```

#### مثال الاستجابة

```json
{
  "success": true,
  "data": {
    "orders": [
      {
        "id": 12345,
        "order_number": "ORD-2024-001",
        "table_number": "5",
        "type": "dine_in",
        "status": "confirmed",
        "priority": "urgent",
        "estimated_ready_time": "2024-01-15T14:30:00Z",
        "elapsed_time": "00:15:30",
        "items": [
          {
            "name": "برجر كلاسيكي",
            "quantity": 2,
            "modifiers": ["جبن إضافي"],
            "special_instructions": "بدون بصل"
          }
        ]
      }
    ],
    "summary": {
      "total_orders": 8,
      "urgent_orders": 2,
      "average_wait_time": "00:18:45"
    }
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
      "items": ["حقل العناصر مطلوب."],
      "table_id": ["الطاولة المحددة غير متاحة."]
    }
  }
}
```

### 404 غير موجود

```json
{
  "success": false,
  "error": {
    "code": "ORDER_NOT_FOUND",
    "message": "الطلب غير موجود",
    "details": "الطلب برقم 12345 غير موجود أو تم حذفه."
  }
}
```

### 409 تضارب

```json
{
  "success": false,
  "error": {
    "code": "ORDER_CONFLICT",
    "message": "لا يمكن تعديل الطلب",
    "details": "لا يمكن تعديل الطلبات المكتملة أو الملغية.",
    "current_status": "completed"
  }
}
```

## أحداث الـ Webhooks

يرسل RestoPos أحداث webhook للطلبات:

### أحداث الطلبات

- `order.created` - تم إنشاء طلب جديد
- `order.updated` - تم تحديث الطلب
- `order.confirmed` - تم تأكيد الطلب
- `order.ready` - الطلب جاهز
- `order.completed` - تم إكمال الطلب
- `order.cancelled` - تم إلغاء الطلب

### مثال حمولة الـ webhook

```json
{
  "event": "order.confirmed",
  "timestamp": "2024-01-15T14:05:00Z",
  "data": {
    "order": {
      "id": 12345,
      "order_number": "ORD-2024-001",
      "status": "confirmed",
      "total_amount": 55.95
    }
  }
}
```

## أمثلة SDK

::: tabs

== JavaScript

```javascript
class OrdersAPI {
  constructor(client) {
    this.client = client;
  }

  // قائمة الطلبات
  async list(filters = {}) {
    const params = new URLSearchParams(filters);
    return await this.client.request(`/api/orders?${params}`);
  }

  // الحصول على طلب
  async get(orderId) {
    return await this.client.request(`/api/orders/${orderId}`);
  }

  // إنشاء طلب
  async create(orderData) {
    return await this.client.request('/api/orders', {
      method: 'POST',
      body: JSON.stringify(orderData)
    });
  }

  // تحديث طلب
  async update(orderId, updates) {
    return await this.client.request(`/api/orders/${orderId}`, {
      method: 'PUT',
      body: JSON.stringify(updates)
    });
  }

  // إلغاء طلب
  async cancel(orderId, reason) {
    return await this.client.request(`/api/orders/${orderId}`, {
      method: 'DELETE',
      body: JSON.stringify({ reason })
    });
  }

  // إضافة عناصر
  async addItems(orderId, items) {
    return await this.client.request(`/api/orders/${orderId}/items`, {
      method: 'POST',
      body: JSON.stringify({ items })
    });
  }

  // إحصائيات
  async getStatistics(period = 'today') {
    return await this.client.request(`/api/orders/statistics?period=${period}`);
  }
}

// الاستخدام
const orders = new OrdersAPI(restoposClient);

// إنشاء طلب جديد
const newOrder = await orders.create({
  type: 'dine_in',
  table_id: 5,
  items: [
    {
      menu_item_id: 101,
      quantity: 2,
      modifiers: [201]
    }
  ]
});

console.log('تم إنشاء الطلب:', newOrder.data.order_number);
```

== PHP

```php
class OrdersAPI
{
    private $client;

    public function __construct($client)
    {
        $this->client = $client;
    }

    // قائمة الطلبات
    public function list($filters = [])
    {
        $query = http_build_query($filters);
        return $this->client->request("/api/orders?{$query}");
    }

    // الحصول على طلب
    public function get($orderId)
    {
        return $this->client->request("/api/orders/{$orderId}");
    }

    // إنشاء طلب
    public function create($orderData)
    {
        return $this->client->request('/api/orders', 'POST', $orderData);
    }

    // تحديث طلب
    public function update($orderId, $updates)
    {
        return $this->client->request("/api/orders/{$orderId}", 'PUT', $updates);
    }

    // إلغاء طلب
    public function cancel($orderId, $reason)
    {
        return $this->client->request("/api/orders/{$orderId}", 'DELETE', [
            'reason' => $reason
        ]);
    }

    // إضافة عناصر
    public function addItems($orderId, $items)
    {
        return $this->client->request("/api/orders/{$orderId}/items", 'POST', [
            'items' => $items
        ]);
    }

    // إحصائيات
    public function getStatistics($period = 'today')
    {
        return $this->client->request("/api/orders/statistics?period={$period}");
    }
}

// الاستخدام
$orders = new OrdersAPI($restoposClient);

// إنشاء طلب جديد
$newOrder = $orders->create([
    'type' => 'dine_in',
    'table_id' => 5,
    'items' => [
        [
            'menu_item_id' => 101,
            'quantity' => 2,
            'modifiers' => [201]
        ]
    ]
]);

echo "تم إنشاء الطلب: " . $newOrder['data']['order_number'];
```

== Python

```python
class OrdersAPI:
    def __init__(self, client):
        self.client = client

    def list(self, filters=None):
        """قائمة الطلبات"""
        if filters is None:
            filters = {}
        
        params = '&'.join([f"{k}={v}" for k, v in filters.items()])
        return self.client.request(f"/api/orders?{params}")

    def get(self, order_id):
        """الحصول على طلب"""
        return self.client.request(f"/api/orders/{order_id}")

    def create(self, order_data):
        """إنشاء طلب"""
        return self.client.request('/api/orders', 'POST', order_data)

    def update(self, order_id, updates):
        """تحديث طلب"""
        return self.client.request(f"/api/orders/{order_id}", 'PUT', updates)

    def cancel(self, order_id, reason):
        """إلغاء طلب"""
        return self.client.request(f"/api/orders/{order_id}", 'DELETE', {
            'reason': reason
        })

    def add_items(self, order_id, items):
        """إضافة عناصر"""
        return self.client.request(f"/api/orders/{order_id}/items", 'POST', {
            'items': items
        })

    def get_statistics(self, period='today'):
        """إحصائيات"""
        return self.client.request(f"/api/orders/statistics?period={period}")

# الاستخدام
orders = OrdersAPI(restopos_client)

# إنشاء طلب جديد
new_order = orders.create({
    'type': 'dine_in',
    'table_id': 5,
    'items': [
        {
            'menu_item_id': 101,
            'quantity': 2,
            'modifiers': [201]
        }
    ]
})

print(f"تم إنشاء الطلب: {new_order['data']['order_number']}")
```

:::

## أفضل الممارسات

### إدارة الطلبات

1. **التحقق من التوفر**: تحقق دائماً من توفر عناصر القائمة قبل إنشاء الطلبات
2. **معالجة الأخطاء**: تنفيذ معالجة قوية للأخطاء للطلبات الفاشلة
3. **تحديثات الحالة**: تحديث حالات الطلبات في الوقت المناسب
4. **تتبع المخزون**: مراقبة مستويات المخزون وتحديث التوفر

### الأداء

1. **الترقيم**: استخدام الترقيم للقوائم الكبيرة
2. **التصفية**: تطبيق مرشحات مناسبة لتقليل حجم البيانات
3. **التخزين المؤقت**: تخزين البيانات المستخدمة بكثرة مؤقتاً
4. **المعالجة غير المتزامنة**: استخدام المعالجة غير المتزامنة للعمليات الثقيلة

### الأمان

1. **التحقق من الصلاحيات**: التأكد من أن المستخدمين لديهم الصلاحيات المناسبة
2. **التحقق من صحة البيانات**: التحقق من جميع بيانات الإدخال
3. **تسجيل العمليات**: تسجيل جميع عمليات الطلبات للمراجعة
4. **حماية البيانات الحساسة**: حماية معلومات العملاء والدفع