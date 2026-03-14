# Uniqa Inventory — API Documentation

> **Version:** 2.0 · **Last Updated:** 14 March 2026  
> Dokumentasi ini menjelaskan seluruh endpoint API yang tersedia untuk diintegrasikan oleh aplikasi front-end.

---

## Daftar Isi

1. [Informasi Umum](#informasi-umum)
2. [Autentikasi](#autentikasi)
3. [Auth Endpoints](#auth-endpoints)
4. [Product Endpoints](#product-endpoints)
5. [Transaction Endpoints](#transaction-endpoints)
6. [Stock Endpoints](#stock-endpoints)
7. [AJAX / Internal Endpoints](#ajax--internal-endpoints)
8. [Offline Sync Endpoints](#offline-sync-endpoints)
9. [Response Format Standar](#response-format-standar)
10. [HTTP Status Codes](#http-status-codes)
11. [Contoh Implementasi](#contoh-implementasi)

---

## Informasi Umum

| Item | Nilai |
|---|---|
| **Base URL (Development)** | `http://localhost:8000` |
| **Base URL (Production)** | Sesuaikan dengan domain server |
| **API Prefix** | `/api` |
| **Format Data** | JSON (`application/json`) |
| **Autentikasi** | JWT Bearer Token |
| **Encoding** | UTF-8 |

---

## Autentikasi

Semua endpoint yang dilindungi menggunakan **JWT (JSON Web Token)**. Sertakan token di header setiap request:

```http
Authorization: Bearer <your_jwt_token>
```

> Token diperoleh dari endpoint `POST /api/auth/login`.  
> Token default **expired dalam 3600 detik (1 jam)**. Gunakan endpoint refresh untuk memperbarui.

---

## Auth Endpoints

### 1. Register

```
POST /api/auth/register
```

> ⚠️ Role default yang dibuat adalah `cashier`. Pembuatan akun `admin` hanya bisa dilakukan secara manual oleh admin.

**Request Body:**

```json
{
  "name": "Budi Santoso",
  "email": "budi@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Validasi:**

| Field | Aturan |
|---|---|
| `name` | required, string, max 255 |
| `email` | required, valid email, unik di sistem |
| `password` | required, min 8 karakter |
| `password_confirmation` | harus sama dengan `password` |

**Response `201 Created`:**

```json
{
  "message": "User registered successfully",
  "user": {
    "id": 5,
    "name": "Budi Santoso",
    "email": "budi@example.com",
    "role": "cashier",
    "created_at": "2026-03-14T08:00:00.000000Z"
  }
}
```

---

### 2. Login

```
POST /api/auth/login
```

**Request Body:**

```json
{
  "email": "admin@example.com",
  "password": "password123"
}
```

**Validasi:**

| Field | Aturan |
|---|---|
| `email` | required, valid email |
| `password` | required, min 6 karakter |

**Response `200 OK`:**

```json
{
  "status_code": 200,
  "success": true,
  "message": "Login berhasil",
  "data": {
    "user": {
      "id": 1,
      "name": "Admin Uniqa",
      "email": "admin@example.com",
      "role": "admin"
    },
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
    "token_type": "Bearer",
    "expires_in": 3600
  }
}
```

**Response `401 Unauthorized`:**

```json
{
  "status_code": 401,
  "success": false,
  "message": "Email atau password salah"
}
```

---

### 3. Get Current User

```
GET /api/auth/me
```

> 🔒 Memerlukan token JWT.

**Response `200 OK`:**

```json
{
  "status_code": 200,
  "success": true,
  "data": {
    "id": 1,
    "name": "Admin Uniqa",
    "email": "admin@example.com",
    "role": "admin",
    "created_at": "2026-01-01T00:00:00.000000Z"
  }
}
```

---

### 4. Refresh Token

```
POST /api/auth/refresh
```

> 🔒 Memerlukan token JWT (bisa token yang sudah expired, tetapi belum melewati batas refresh).

**Response `200 OK`:**

```json
{
  "status_code": 200,
  "success": true,
  "message": "Token refreshed successfully",
  "data": {
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
    "token_type": "Bearer",
    "expires_in": 3600
  }
}
```

---

### 5. Logout

```
POST /api/auth/logout
```

> 🔒 Memerlukan token JWT. Token akan diinvalidasi di server.

**Response `200 OK`:**

```json
{
  "status_code": 200,
  "success": true,
  "message": "Logout berhasil"
}
```

---

### 6. Ganti Password

```
POST /api/auth/change-password
```

> 🔒 Memerlukan token JWT.

**Request Body:**

```json
{
  "current_password": "password_lama",
  "new_password": "password_baru_aman",
  "new_password_confirmation": "password_baru_aman"
}
```

**Validasi:**

| Field | Aturan |
|---|---|
| `current_password` | required |
| `new_password` | required, min 8 karakter |
| `new_password_confirmation` | harus sama dengan `new_password` |

**Response `200 OK`:**

```json
{
  "status_code": 200,
  "success": true,
  "message": "Password berhasil diubah"
}
```

---

## Product Endpoints

> 🔒 Semua endpoint produk memerlukan token JWT.

### 7. Daftar Produk (Paginasi)

```
GET /api/products
```

**Query Parameters:**

| Parameter | Default | Keterangan |
|---|---|---|
| `per_page` | `15` | Jumlah item per halaman |

**Response `200 OK`:**

```json
{
  "status_code": 200,
  "success": true,
  "data": [
    {
      "id": 13,
      "name": "Beras 15 Kg",
      "sku": "BRS-015",
      "description": "Beras medium super",
      "price": 150000.00,
      "stock": 42,
      "barcode": "387952260084",
      "qr_code": "387952260084",
      "category": {
        "id": 2,
        "name": "Sembako"
      }
    }
  ],
  "pagination": {
    "current_page": 1,
    "total": 58,
    "per_page": 15,
    "last_page": 4
  }
}
```

---

### 8. Detail Produk

```
GET /api/products/{product_id}
```

**Response `200 OK`:**

```json
{
  "status_code": 200,
  "success": true,
  "data": {
    "id": 13,
    "name": "Beras 15 Kg",
    "sku": "BRS-015",
    "description": "Beras medium super",
    "price": 150000.00,
    "stock": 42,
    "barcode": "387952260084",
    "qr_code": "387952260084",
    "category": {
      "id": 2,
      "name": "Sembako"
    },
    "last_adjustments": [
      {
        "id": 5,
        "type": "in",
        "quantity": 20,
        "reason": "Restock dari supplier",
        "created_at": "2026-03-10T09:00:00.000000Z"
      }
    ]
  }
}
```

> `last_adjustments` menampilkan maksimal 5 riwayat penyesuaian stok terakhir.

---

### 9. Cari Produk (Lookup via Barcode / QR / ID)

```
POST /api/get-product
```

> ⚠️ Endpoint ini menggunakan session/cookie auth (bukan JWT). Cocok untuk digunakan dari **POS web** yang sudah login. Untuk aplikasi mobile/eksternal, gunakan endpoint JWT di atas.

**Request Body** (kirim salah satu):

```json
{
  "product_id": 13
}
```

```json
{
  "barcode": "387952260084"
}
```

```json
{
  "qr_code": "387952260084"
}
```

**Response `200 OK`:**

```json
{
  "status_code": 200,
  "success": true,
  "data": {
    "id": 13,
    "name": "Beras 15 Kg",
    "sku": "BRS-015",
    "price": "150000.00",
    "stock": 42,
    "category": "Sembako"
  }
}
```

**Response `404 Not Found`:**

```json
{
  "status_code": 404,
  "success": false,
  "message": "Produk tidak ditemukan."
}
```

---

## Transaction Endpoints

> 🔒 Semua endpoint transaksi memerlukan token JWT.

### 10. Buat Transaksi Baru

```
POST /api/transactions
```

**Request Body:**

```json
{
  "items": [
    {
      "product_id": 13,
      "quantity": 2
    },
    {
      "product_id": 5,
      "quantity": 3
    }
  ],
  "amount_received": 500000,
  "notes": "Pembayaran tunai"
}
```

**Validasi:**

| Field | Aturan |
|---|---|
| `items` | required, array, min 1 item |
| `items.*.product_id` | required, harus ada di database |
| `items.*.quantity` | required, integer, min 1 |
| `amount_received` | required, numeric, min 0 |
| `notes` | optional, string, max 255 |

**Response `201 Created`:**

```json
{
  "status_code": 201,
  "success": true,
  "message": "Transaksi berhasil disimpan.",
  "data": {
    "id": 101,
    "transaction_number": "TRX-20260314-00101",
    "total_price": 345000.00,
    "amount_received": 500000.00,
    "change": 155000.00,
    "created_at": "2026-03-14T10:30:00+07:00"
  }
}
```

**Error — Stok tidak cukup `422`:**

```json
{
  "status_code": 422,
  "success": false,
  "message": "Stok Beras 15 Kg tidak cukup. Stok tersedia: 1"
}
```

**Error — Uang kurang `422`:**

```json
{
  "status_code": 422,
  "success": false,
  "message": "Uang yang diberikan tidak cukup."
}
```

---

### 11. Detail Transaksi

```
GET /api/transactions/{transaction_id}
```

**Response `200 OK`:**

```json
{
  "status_code": 200,
  "success": true,
  "data": {
    "id": 101,
    "transaction_number": "TRX-20260314-00101",
    "cashier": {
      "id": 2,
      "name": "Kasir Satu"
    },
    "items": [
      {
        "product_id": 13,
        "product_name": "Beras 15 Kg",
        "quantity": 2,
        "price": 150000.00,
        "subtotal": 300000.00
      },
      {
        "product_id": 5,
        "product_name": "Minyak Goreng 1L",
        "quantity": 3,
        "price": 15000.00,
        "subtotal": 45000.00
      }
    ],
    "total_price": 345000.00,
    "amount_received": 500000.00,
    "change": 155000.00,
    "status": "completed",
    "created_at": "2026-03-14T10:30:00+07:00"
  }
}
```

---

### 12. Daftar Transaksi (Paginasi)

```
GET /api/transactions
```

**Query Parameters:**

| Parameter | Default | Keterangan |
|---|---|---|
| `per_page` | `20` | Jumlah item per halaman |

**Response `200 OK`:**

```json
{
  "status_code": 200,
  "success": true,
  "data": [ /* array of transaction objects */ ],
  "pagination": {
    "current_page": 1,
    "total": 350,
    "per_page": 20,
    "last_page": 18
  }
}
```

---

## Stock Endpoints

> 🔒 Semua endpoint stok memerlukan token JWT.

### 13. Cek Stok Produk

```
GET /api/products/{product_id}/stock
```

**Response `200 OK`:**

```json
{
  "status_code": 200,
  "success": true,
  "data": {
    "product_id": 13,
    "name": "Beras 15 Kg",
    "stock": 42,
    "available": true
  }
}
```

> `available` bernilai `false` jika stok = 0.

---

### 14. Penyesuaian Stok

```
POST /api/stock/adjust
```

**Request Body:**

```json
{
  "product_id": 13,
  "type": "in",
  "quantity": 50,
  "reason": "Restock dari supplier ABC"
}
```

**Validasi:**

| Field | Aturan |
|---|---|
| `product_id` | required, harus ada di database |
| `type` | required, nilai: `in` (stok masuk) atau `out` (stok keluar) |
| `quantity` | required, integer, min 1 |
| `reason` | required, string, max 255 |

**Response `200 OK`:**

```json
{
  "status_code": 200,
  "success": true,
  "message": "Stok berhasil disesuaikan.",
  "data": {
    "product_id": 13,
    "new_stock": 92,
    "type": "in",
    "adjusted_quantity": 50
  }
}
```

**Error — Stok tidak cukup untuk pengurangan `422`:**

```json
{
  "status_code": 422,
  "success": false,
  "message": "Stok tidak cukup untuk pengurangan."
}
```

---

### 15. Riwayat Penyesuaian Stok

```
GET /api/stock-adjustments
```

**Query Parameters:**

| Parameter | Default | Keterangan |
|---|---|---|
| `per_page` | `50` | Jumlah item per halaman |
| `type` | - | Filter: `in` atau `out` |
| `product_id` | - | Filter berdasarkan ID produk |

**Response `200 OK`:**

```json
{
  "status_code": 200,
  "success": true,
  "data": [
    {
      "id": 12,
      "product_id": 13,
      "type": "in",
      "adjustment_value": 50,
      "reason": "Restock dari supplier ABC",
      "created_at": "2026-03-14T09:00:00.000000Z"
    }
  ],
  "pagination": {
    "current_page": 1,
    "total": 120,
    "per_page": 50,
    "last_page": 3
  }
}
```

---

## AJAX / Internal Endpoints

> Endpoint berikut menggunakan **session auth** (cookie `laravel_session`), bukan JWT. Digunakan oleh halaman web internal (POS, offline sync dari browser).

### 16. Health Check

```
GET /api/health
```

> Endpoint publik, tidak perlu autentikasi.

**Response `200 OK`:**

```json
{
  "status": "ok"
}
```

---

## Offline Sync Endpoints

> Endpoint ini dirancang untuk sinkronisasi data yang tersimpan sementara di IndexedDB browser saat offline. Menggunakan **session auth**.

### 17. Sync Transaksi Offline

```
POST /api/sync-transactions
```

**Request Body:**

```json
{
  "transactions": [
    {
      "offline_id": "local-uuid-001",
      "items": [
        { "product_id": 13, "quantity": 1, "price": 150000 }
      ],
      "amount_received": 150000,
      "discount_amount": 0,
      "payment_method": "cash",
      "notes": null
    }
  ]
}
```

**Field `payment_method`:** `cash` | `transfer` | `qris`

**Response `200 OK`:**

```json
{
  "status_code": 200,
  "success": true,
  "synced": [
    {
      "offline_id": "local-uuid-001",
      "transaction_number": "TRX-20260314-00102"
    }
  ],
  "failed": []
}
```

**Jika ada yang gagal:**

```json
{
  "status_code": 200,
  "success": true,
  "synced": [],
  "failed": [
    {
      "offline_id": "local-uuid-002",
      "reason": "Stok Beras 15 Kg tidak cukup"
    }
  ]
}
```

---

### 18. Sync Pengeluaran Offline

```
POST /api/sync-expenses
```

**Request Body:**

```json
{
  "expenses": [
    {
      "offline_id": "local-expense-001",
      "activity": "Bayar listrik bulan Maret",
      "type": "operasional",
      "category_id": null,
      "status": "selesai",
      "amount": 250000,
      "description": "Token PLN"
    }
  ]
}
```

**Field `type`:** `operasional` | `pembelian` | `lainnya`  
**Field `status`:** `selesai` | `pending`

**Response `200 OK`:**

```json
{
  "status_code": 200,
  "success": true,
  "synced": [
    { "offline_id": "local-expense-001", "id": 45 }
  ],
  "failed": []
}
```

---

### 19. Sync Hutang Offline

```
POST /api/sync-debts
```

**Request Body — Penghutang baru:**

```json
{
  "debtor_type": "new",
  "debtor_name": "Pak Agus",
  "debtor_phone": "081234567890",
  "debtor_address": "Jl. Merdeka No. 5",
  "amount": 500000,
  "due_date": "2026-04-01",
  "description": "Hutang belanja sembako"
}
```

**Request Body — Penghutang yang sudah ada:**

```json
{
  "debtor_type": "existing",
  "debtor_id": 3,
  "amount": 200000,
  "due_date": "2026-04-15",
  "description": null
}
```

**Validasi:**

| Field | Aturan |
|---|---|
| `debtor_type` | required, `new` atau `existing` |
| `debtor_id` | required jika `debtor_type=existing` |
| `debtor_name` | required jika `debtor_type=new`, max 255 |
| `debtor_phone` | optional, max 20 |
| `debtor_address` | optional, max 500 |
| `amount` | required, numeric, min 1 |
| `due_date` | required, format tanggal valid |
| `description` | optional, max 1000 |

**Response `200 OK`:**

```json
{
  "status_code": 200,
  "success": true,
  "debt_id": 27,
  "debtor_id": 3
}
```

---

### 20. Sync Cicilan Hutang Offline

```
POST /api/sync-debt-payments/{debt_id}
```

**Request Body:**

```json
{
  "pay_amount": 150000,
  "pay_note": "Cicilan pertama transfer BCA"
}
```

**Validasi:**

| Field | Aturan |
|---|---|
| `pay_amount` | required, numeric, min 1, maks = sisa hutang |
| `pay_note` | optional, string, max 500 |

**Response `200 OK`:**

```json
{
  "status_code": 200,
  "success": true,
  "is_paid": false
}
```

> `is_paid` menjadi `true` jika pembayaran ini melunasi seluruh sisa hutang.

---

## Response Format Standar

### Sukses

```json
{
  "status_code": 200,
  "success": true,
  "message": "Pesan opsional",
  "data": { }
}
```

### Gagal / Error

```json
{
  "status_code": 422,
  "success": false,
  "message": "Deskripsi error",
  "errors": {
    "field_name": ["Pesan validasi"]
  }
}
```

---

## HTTP Status Codes

| Kode | Arti | Contoh Kasus |
|---|---|---|
| `200` | OK | Request berhasil |
| `201` | Created | Data berhasil dibuat |
| `401` | Unauthorized | Token tidak ada / tidak valid / expired |
| `403` | Forbidden | Akses ditolak (role tidak sesuai) |
| `404` | Not Found | Produk / transaksi tidak ditemukan |
| `422` | Unprocessable Entity | Validasi gagal / stok kurang / uang kurang |
| `500` | Internal Server Error | Error server, lihat log |

---

## Contoh Implementasi

### JavaScript (Fetch API)

```javascript
const BASE_URL = 'http://localhost:8000';
let token = localStorage.getItem('jwt_token');

// ── Helper ─────────────────────────────────────────────
const api = async (method, path, body = null) => {
  const headers = {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  };
  if (token) headers['Authorization'] = `Bearer ${token}`;

  const res = await fetch(`${BASE_URL}${path}`, {
    method,
    headers,
    body: body ? JSON.stringify(body) : null,
  });

  if (!res.ok && res.status === 401) {
    // Token expired — coba refresh
    await refreshToken();
  }

  return res.json();
};

// ── Auth ───────────────────────────────────────────────
const login = async (email, password) => {
  const data = await api('POST', '/api/auth/login', { email, password });
  if (data.success) {
    token = data.data.access_token;
    localStorage.setItem('jwt_token', token);
    // Simpan waktu expired
    const expiresAt = Date.now() + data.data.expires_in * 1000;
    localStorage.setItem('jwt_expires_at', expiresAt);
  }
  return data;
};

const refreshToken = async () => {
  const data = await api('POST', '/api/auth/refresh');
  if (data.success) {
    token = data.data.access_token;
    localStorage.setItem('jwt_token', token);
  }
  return data;
};

const logout = () => api('POST', '/api/auth/logout');

// ── Produk ─────────────────────────────────────────────
const getProducts = (page = 1, perPage = 15) =>
  api('GET', `/api/products?page=${page}&per_page=${perPage}`);

const getProduct = (id) =>
  api('GET', `/api/products/${id}`);

const checkStock = (productId) =>
  api('GET', `/api/products/${productId}/stock`);

// ── Transaksi ──────────────────────────────────────────
const createTransaction = (items, amountReceived, notes = '') =>
  api('POST', '/api/transactions', { items, amount_received: amountReceived, notes });

// Contoh pemakaian
(async () => {
  await login('admin@example.com', 'password');

  const products = await getProducts();
  console.log('Total produk:', products.pagination.total);

  const trx = await createTransaction(
    [{ product_id: 13, quantity: 2 }],
    350000
  );
  console.log('No transaksi:', trx.data?.transaction_number);
})();
```

---

### Axios (React / Vue)

```javascript
import axios from 'axios';

const http = axios.create({
  baseURL: 'http://localhost:8000/api',
  headers: { 'Accept': 'application/json' },
});

// Interceptor: tambah token otomatis
http.interceptors.request.use(config => {
  const token = localStorage.getItem('jwt_token');
  if (token) config.headers.Authorization = `Bearer ${token}`;
  return config;
});

// Interceptor: auto-refresh jika 401
http.interceptors.response.use(
  res => res,
  async err => {
    if (err.response?.status === 401) {
      const refresh = await http.post('/auth/refresh');
      localStorage.setItem('jwt_token', refresh.data.data.access_token);
      return http(err.config); // retry request
    }
    return Promise.reject(err);
  }
);

// Contoh penggunaan
const { data } = await http.post('/auth/login', { email, password });
const token = data.data.access_token;

const products = await http.get('/products?per_page=20');
```

---

### cURL (Testing)

```bash
# Login dan simpan token
TOKEN=$(curl -s -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"password"}' \
  | python3 -c "import sys,json; print(json.load(sys.stdin)['data']['access_token'])")

# Daftar produk
curl -s http://localhost:8000/api/products \
  -H "Authorization: Bearer $TOKEN" | python3 -m json.tool

# Buat transaksi
curl -s -X POST http://localhost:8000/api/transactions \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "items": [{"product_id": 13, "quantity": 1}],
    "amount_received": 200000
  }' | python3 -m json.tool

# Cek stok produk ID 13
curl -s http://localhost:8000/api/products/13/stock \
  -H "Authorization: Bearer $TOKEN"
```

---

## Catatan Penting untuk Frontend

| Hal | Keterangan |
|---|---|
| **JWT Storage** | Simpan di `localStorage` atau `httpOnly cookie`. Jangan simpan di state komponen. |
| **Token Expiry** | Default 3600 detik (1 jam). Pantau `expires_in` dan refresh sebelum expired. |
| **QR / Barcode** | QR Code produk berisi plain-text barcode (mis. `387952260084`), bukan JSON. Scan langsung bisa digunakan sebagai input pencarian. |
| **Offline Sync** | Endpoint sync (`/api/sync-*`) menggunakan session auth; harus dipanggil dari browser yang sudah login, bukan dari app eksternal. |
| **Paginasi** | Gunakan `pagination.last_page` untuk menentukan apakah masih ada halaman berikutnya. |
| **CORS** | Pastikan domain front-end ditambahkan ke `config/cors.php` di server. |
| **Format Harga** | Semua harga dalam **Rupiah (IDR)**, tipe `float`, tanpa simbol mata uang. |
