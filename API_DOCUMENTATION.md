# Inventory Control System - API Documentation

## Base URL
```
http://localhost:8000/api
```

## Authentication
API endpoints tidak memerlukan autentikasi untuk produk dan transaksi dasar. Namun, untuk operasi admin (seperti penyesuaian stok), autentikasi mungkin diperlukan di masa depan.

---

## Endpoints

### 1. Products

#### Get All Products
```http
GET /api/products?per_page=15
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Mie Goreng Instant",
      "sku": "MGI-001",
      "description": "Mie goreng instant berkualitas",
      "price": 3500,
      "stock": 100,
      "barcode": "8991234567890",
      "qr_code": null,
      "category": {
        "id": 1,
        "name": "Makanan & Minuman"
      }
    }
  ],
  "pagination": {
    "current_page": 1,
    "total": 50,
    "per_page": 15,
    "last_page": 4
  }
}
```

#### Get Product by ID
```http
GET /api/products/{product_id}
```

#### Get All Categories
```http
GET /api/categories
```

#### Search Products
```http
GET /api/products/search/{query}
```

**Example:**
```http
GET /api/products/search/mie
```

#### Find by Barcode
```http
POST /api/products/barcode/{barcode}
```

---

### 2. Transactions

#### Create Transaction
```http
POST /api/transactions
```

**Request Body:**
```json
{
  "items": [
    {
      "product_id": 1,
      "quantity": 2
    },
    {
      "product_id": 3,
      "quantity": 1
    }
  ],
  "amount_received": 50000,
  "notes": "Pembayaran cash"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Transaksi berhasil disimpan.",
  "data": {
    "id": 1,
    "transaction_number": "TRX-20260217-00001",
    "total_price": 38000,
    "amount_received": 50000,
    "change": 12000,
    "created_at": "2026-02-17T10:30:00Z"
  }
}
```

#### Get Transaction Detail
```http
GET /api/transactions/{transaction_id}
```

#### Get All Transactions
```http
GET /api/transactions?per_page=20
```

---

### 3. Stock Management

#### Check Product Stock
```http
GET /api/products/{product_id}/stock
```

#### Adjust Stock
```http
POST /api/stock/adjust
```

**Request Body:**
```json
{
  "product_id": 1,
  "type": "in",
  "quantity": 10,
  "reason": "Restock barang"
}
```

**Note:** type dapat berupa "in" (masuk) atau "out" (keluar)

#### Get Stock Adjustment History
```http
GET /api/stock-adjustments?type=in&product_id=1&per_page=50
```

---

## Error Response

```json
{
  "success": false,
  "message": "Error message here"
}
```

---

## Common Error Codes

| Code | Message |
|------|---------|
| 404 | Produk tidak ditemukan |
| 422 | Stok tidak cukup / Validasi gagal |
| 500 | Server error |

---

## Integration Examples

### Using cURL

#### Create Transaction
```bash
curl -X POST http://localhost:8000/api/transactions \
  -H "Content-Type: application/json" \
  -d '{
    "items": [
      {"product_id": 1, "quantity": 2}
    ],
    "amount_received": 50000
  }'
```

#### Search Product
```bash
curl http://localhost:8000/api/products/search/mie
```

### Using JavaScript/Fetch API

```javascript
// Get all products
fetch('/api/products?per_page=15')
  .then(response => response.json())
  .then(data => console.log(data));

// Create transaction
fetch('/api/transactions', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
  },
  body: JSON.stringify({
    items: [
      { product_id: 1, quantity: 2 }
    ],
    amount_received: 50000
  })
})
.then(response => response.json())
.then(data => console.log(data));
```

### Using PHP/Laravel HTTP Client

```php
use Illuminate\Support\Facades\Http;

// Get all products
$response = Http::get('http://localhost:8000/api/products');

// Create transaction
$response = Http::post('http://localhost:8000/api/transactions', [
    'items' => [
        ['product_id' => 1, 'quantity' => 2]
    ],
    'amount_received' => 50000
]);

$data = $response->json();
```

---

## Rate Limiting
Tidak ada rate limiting saat ini. Silakan implementasikan sesuai kebutuhan.

---

## Version
API Version: 1.0
Last Updated: 17 February 2026
