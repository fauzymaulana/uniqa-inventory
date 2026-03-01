# Sistem Inventory Control Berbasis Website

Sistem inventory control berbasis website yang dapat digunakan oleh kasir untuk mengelola stok barang secara efisien. Sistem ini memungkinkan kasir untuk melakukan transfer stok barang saat proses transaksi berlangsung dan mencatat semua detail transaksi secara real-time.

## 🚀 Fitur Utama

### Inventory Control
- ✅ Memasukkan data barang (nama, harga, kategori)
- ✅ Mengelola stok barang secara real-time
- ✅ Penyesuaian stok manual (in/out)
- ✅ Riwayat perubahan stok lengkap

### Kasir (POS)
- ✅ Interface POS yang user-friendly
- ✅ Pencarian dan scanning produk dengan barcode/QR code
- ✅ Manajemen keranjang belanja interaktif
- ✅ Pemotongan stok otomatis saat transaksi
- ✅ Perhitungan kembalian otomatis

### Data Barang
- ✅ Pencatatan detail barang (nama, harga, kategori)
- ✅ Dukungan barcode dan QR code
- ✅ Manajemen kategori produk

### Transaksi Pembayaran
- ✅ Pencatatan pembayaran cash
- ✅ Nomor transaksi otomatis
- ✅ Detail pembayaran lengkap (total, uang diterima, kembalian)
- ✅ Riwayat transaksi detail

### Real-time Stok Barang
- ✅ Update stok real-time setelah pembayaran
- ✅ Notifikasi stok rendah (< 10 unit)
- ✅ Tracking perubahan stok per user

### Laporan & Analytics
- ✅ Laporan penjualan harian/bulanan
- ✅ Laporan inventory dan nilai stok
- ✅ Riwayat perubahan stok
- ✅ Top products per hari
- ✅ Statistik kasir

### API RESTful
- ✅ API untuk mobile developer
- ✅ Dukungan JSON response
- ✅ Dokumentasi API lengkap

## 💻 Teknologi yang Digunakan

- **Backend**: Laravel 11 (PHP)
- **Database**: PostgreSQL
- **Frontend**: Blade Template + Bootstrap 5
- **API**: RESTful API
- **Authentication**: Laravel Sanctum (ready for implementation)
- **Barcode/QR**: Library untuk scanning

## 📋 Persyaratan Sistem

- PHP 8.1 atau lebih tinggi
- Composer
- PostgreSQL 12 atau lebih tinggi
- Node.js & NPM (untuk asset compilation)

## 🔧 Instalasi & Setup

### 1. Clone Repository
```bash
cd laravel-dasar-125
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Setup Environment
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure Database
Edit file `.env`:
```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=inventory_control
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

### 5. Run Migrations
```bash
php artisan migrate
```

### 6. Seed Database (Optional)
```bash
php artisan db:seed
```

Seeding ini akan membuat:
- **Admin User**: admin@inventory.test (password: password)
- **Cashier 1**: cashier1@inventory.test (password: password)
- **Cashier 2**: cashier2@inventory.test (password: password)
- **4 Kategori** dengan 8 produk sample

### 7. Build Assets
```bash
npm run build
```

Atau untuk development:
```bash
npm run dev
```

### 8. Start Server
```bash
php artisan serve
```

Akses aplikasi di `http://localhost:8000`

## 👥 User Roles & Credentials

### Admin
- Email: `admin@inventory.test`
- Password: `password`
- Akses: Kelola Produk, Kategori, Laporan, Analytics

### Kasir 1
- Email: `cashier1@inventory.test`
- Password: `password`
- Akses: POS, Transaksi, Riwayat

### Kasir 2
- Email: `cashier2@inventory.test`
- Password: `password`
- Akses: POS, Transaksi, Riwayat

## 📁 Struktur Folder

```
├── app/
│   ├── Http/
│   │   ├── Controllers/       # Main controllers
│   │   │   ├── Api/           # API controllers
│   │   │   └── ...
│   │   ├── Middleware/        # Role-based middleware
│   │   ├── Requests/          # Form request validation
│   │   └── Resources/         # API resources
│   ├── Models/                # Eloquent models
│   └── Helpers/               # Helper functions
├── database/
│   ├── migrations/            # Database migrations
│   └── seeders/               # Database seeders
├── routes/
│   ├── web.php                # Web routes
│   └── api.php                # API routes
├── resources/
│   ├── views/
│   │   ├── layouts/           # Layout files
│   │   ├── cashier/           # Cashier views
│   │   ├── products/          # Product views
│   │   ├── categories/        # Category views
│   │   └── reports/           # Report views
│   └── css/
└── public/
    └── storage/               # QR codes & barcodes storage
```

## 🔌 API Endpoints

Dokumentasi lengkap ada di file `API_DOCUMENTATION.md`

### Main Endpoints

**Products**
- `GET /api/products` - Daftar produk
- `GET /api/products/{id}` - Detail produk
- `GET /api/categories` - Daftar kategori
- `GET /api/products/search/{query}` - Cari produk
- `POST /api/products/barcode/{barcode}` - Cari via barcode

**Transactions**
- `POST /api/transactions` - Buat transaksi
- `GET /api/transactions` - Daftar transaksi
- `GET /api/transactions/{id}` - Detail transaksi

**Stock**
- `GET /api/products/{id}/stock` - Cek stok
- `POST /api/stock/adjust` - Sesuaikan stok
- `GET /api/stock-adjustments` - Riwayat stok

## 📊 Fitur Detail

### 1. Point of Sale (POS)
- Interface kasir yang intuitif
- Pencarian produk real-time
- Scan barcode/QR code
- Manajemen keranjang dinamis
- Perhitungan otomatis total dan kembalian

### 2. Inventory Management
- Daftar produk lengkap dengan foto
- Filter berdasarkan kategori
- Penyesuaian stok manual
- Riwayat perubahan stok
- Notifikasi stok rendah

### 3. Laporan Penjualan
- Laporan per hari/bulan/range
- Total penjualan dan jumlah transaksi
- Detail setiap transaksi
- Export data (siap untuk ditambahkan)

### 4. Laporan Inventory
- Nilai total inventory
- Daftar produk dengan stok saat ini
- Produk dengan stok rendah
- Nilai stok per produk

### 5. Riwayat Stok
- Pencatatan setiap perubahan stok
- Tipe perubahan (masuk/keluar)
- Alasan perubahan
- User yang melakukan perubahan

## 🔐 Keamanan

- ✅ Authentication menggunakan Laravel Auth
- ✅ Role-based access control (Admin/Cashier)
- ✅ CSRF Protection
- ✅ SQL Injection Prevention (Prepared Statements)
- ✅ Input Validation
- ✅ Password Hashing (Bcrypt)

## 🎨 UI/UX

- **Responsive Design** - Kompatibel dengan desktop & tablet
- **Modern UI** - Bootstrap 5 dengan custom styling
- **Dark Mode Ready** - CSS yang scalable
- **User-friendly** - Interface intuitif untuk kasir
- **Fast Performance** - Optimized queries & caching ready

## 🚦 Workflow Transaksi

1. **Kasir membuka POS** → Dashboard dengan statistik
2. **Cari/Scan Produk** → Produk ditampilkan
3. **Tambah ke Keranjang** → Jumlah dapat disesuaikan
4. **Masukkan Uang** → Otomatis hitung kembalian
5. **Proses Pembayaran** → Stok otomatis berkurang
6. **Cetak Struk** → Transaksi selesai
7. **Lihat Riwayat** → Detail transaksi tersedia

## 📱 Mobile Integration

Aplikasi ini menyediakan RESTful API yang dapat diintegrasikan dengan aplikasi mobile:

```
Base URL: http://your-domain/api
```

Contoh: Mobile app dapat menampilkan daftar produk, melakukan search, dan membuat transaksi tanpa perlu membuka web browser.

## 🔄 Update & Maintenance

### Database Backup
```bash
pg_dump -U postgres inventory_control > backup.sql
```

### Fresh Migrations
```bash
php artisan migrate:fresh --seed
```

## 📝 Changelog

**v1.0 - 17 February 2026**
- ✅ Initial release
- ✅ Complete POS system
- ✅ Inventory management
- ✅ Transaction tracking
- ✅ Real-time stock updates
- ✅ Comprehensive reporting
- ✅ RESTful API

## 🤝 Support & Kontribusi

Untuk pertanyaan atau kontribusi, silakan buat issue atau pull request.

## 📄 Lisensi

Proyek ini bersifat open source dan dapat digunakan secara gratis.

---

**Developed with ❤️ for efficient inventory management**

Last Updated: 17 February 2026
