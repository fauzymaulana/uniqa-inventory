<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ExpenseController;
use App\Http\Middleware\CheckAdminRole;
use App\Http\Middleware\CheckCashierRole;
use App\Http\Controllers\BarcodeController;

// Authentication Routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function () {
    $credentials = request()->only('email', 'password');
    if (Auth::attempt($credentials)) {
        $user = Auth::user();
        // Redirect based on role
        if ($user && $user->role === 'admin') {
            return redirect()->intended(route('admin.dashboard'));
        }
        return redirect()->intended(route('cashier.dashboard'));
    }
    return back()->with('error', 'Email atau password salah');
})->name('login.post');

Route::post('/logout', function () {
    Auth::logout();
    return redirect()->route('login');
})->name('logout');

Route::get('/', function () {
    if (! Auth::check()) {
        return redirect()->route('login');
    }

    $user = Auth::user();
    if ($user && $user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('cashier.dashboard');
});

// Public Barcode Routes (no auth required for image display)
Route::get('/product/{product}/qrcode', [BarcodeController::class, 'qrcode'])->name('product.qrcode');
Route::get('/product/{product}/barcode', [BarcodeController::class, 'barcode'])->name('product.barcode');
Route::get('/label/{product}', [BarcodeController::class, 'generateLabel'])->name('product.label');
Route::post('/labels/export', [BarcodeController::class, 'exportLabels'])->name('labels.export');

Route::middleware('auth')->group(function () {
    // Admin Routes
    Route::middleware(CheckAdminRole::class)->prefix('admin')->name('admin.')->group(function () {
        // Category Management
        Route::resource('categories', CategoryController::class);

        // Product Management
        Route::resource('products', ProductController::class);
        Route::get('products/{product}/adjust-stock', [ProductController::class, 'adjustStock'])->name('products.adjust-stock');
        Route::post('products/{product}/update-stock', [ProductController::class, 'updateStock'])->name('products.update-stock');
        Route::get('products-import-template', [ProductController::class, 'downloadTemplate'])->name('products.import-template');
        Route::post('products-import', [ProductController::class, 'import'])->name('products.import');

        // Admin Dashboard
        Route::get('dashboard', 'App\\Http\\Controllers\\AdminDashboardController@index')->name('dashboard');
        Route::get('dashboard/daily-data', 'App\\Http\\Controllers\\AdminDashboardController@getDailyData')->name('dashboard.daily-data');
        Route::get('dashboard/daily-payment-data', 'App\\Http\\Controllers\\AdminDashboardController@getDailyPaymentMethodData')->name('dashboard.daily-payment-data');
        Route::get('dashboard/monthly-data', 'App\\Http\\Controllers\\AdminDashboardController@getMonthlyData')->name('dashboard.monthly-data');
        Route::get('dashboard/export/{type}', 'App\\Http\\Controllers\\AdminDashboardController@export')->name('dashboard.export');

        // Cashier account management (admin only)
        Route::resource('cashiers', App\Http\Controllers\Admin\CashierController::class);

        // Reports
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('sales', [ReportController::class, 'sales'])->name('sales');
            Route::get('sales/export', [ReportController::class, 'exportSales'])->name('sales.export');
            Route::get('sales/daily-payment-data', 'App\\Http\\Controllers\\AdminDashboardController@getDailyPaymentMethodData')->name('sales.daily-payment-data');
            Route::get('sales/filtered-payment-data', 'App\\Http\\Controllers\\AdminDashboardController@getFilteredSalesDailyPaymentData')->name('sales.filtered-payment-data');
            Route::get('inventory', [ReportController::class, 'inventory'])->name('inventory');
            Route::get('stock-history', [ReportController::class, 'stockHistory'])->name('stock-history');
            Route::get('daily', [ReportController::class, 'daily'])->name('daily');
            Route::get('transaction-details/{transaction}', [ReportController::class, 'transactionDetails'])->name('transaction-details');
        });

        // Expenses
        Route::resource('expenses', ExpenseController::class);
        Route::get('expenses-daily-data', [ExpenseController::class, 'getDailyExpenseData'])->name('expenses.daily-data');
        Route::get('expenses-export-excel', [ExpenseController::class, 'exportExcel'])->name('expenses.export-excel');
    });

    // Cashier Routes
    Route::middleware(CheckCashierRole::class)->prefix('cashier')->name('cashier.')->group(function () {
        Route::get('dashboard', [CashierController::class, 'dashboard'])->name('dashboard');
        Route::get('dashboard/daily-payment-data', [CashierController::class, 'getDailyPaymentMethodData'])->name('dashboard.daily-payment-data');
        Route::get('pos', [CashierController::class, 'pos'])->name('pos');
        Route::post('store-transaction', [CashierController::class, 'storeTransaction'])->name('store-transaction');
        Route::get('receipt/{transaction}', [CashierController::class, 'receipt'])->name('receipt');
        Route::get('print-receipt/{transaction}', [CashierController::class, 'printReceipt'])->name('print-receipt');
        Route::get('history', [CashierController::class, 'history'])->name('history');
        Route::get('history-export-excel', [CashierController::class, 'exportHistoryExcel'])->name('history.export-excel');
        Route::get('transaction-details/{transaction}', [CashierController::class, 'transactionDetails'])->name('transaction-details');
        
        // Expenses
        Route::resource('expenses', ExpenseController::class);
        Route::get('expenses-daily-data', [ExpenseController::class, 'getDailyExpenseData'])->name('expenses.daily-data');
        Route::get('expenses-export-excel', [ExpenseController::class, 'exportExcel'])->name('expenses.export-excel');
    });

    // API Routes for AJAX
    Route::prefix('api')->name('api.')->group(function () {
        Route::post('get-product', [CashierController::class, 'getProduct'])->name('get-product');
        Route::post('sync-transactions', [CashierController::class, 'syncOfflineTransactions'])->name('sync-transactions');
        // DataTables server-side endpoint for admin products
        Route::get('products/datatables', [App\Http\Controllers\Api\ProductApiController::class, 'datatables'])->name('products.datatables');
    });

    // Profile (available for all authenticated users)
    Route::get('profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});

