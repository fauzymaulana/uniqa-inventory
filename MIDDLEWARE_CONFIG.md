# Middleware Configuration untuk Kernel.php

Tambahkan di file `app/Http/Kernel.php` pada array `$routeMiddleware`:

```php
protected $routeMiddleware = [
    // ... existing middleware
    'admin' => \App\Http\Middleware\CheckAdminRole::class,
    'cashier' => \App\Http\Middleware\CheckCashierRole::class,
];
```

Atau gunakan inline di routes:

```php
Route::middleware(['auth', 'admin'])->group(function () {
    // Admin routes
});

Route::middleware(['auth', 'cashier'])->group(function () {
    // Cashier routes
});
```
