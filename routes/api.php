<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\TransactionApiController;
use App\Http\Controllers\Api\StockApiController;

Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});

// Authentication Routes (Public)
Route::group(['prefix' => 'auth'], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('refresh', [AuthController::class, 'refresh']);
});

// Protected API Routes (Require JWT Token)
Route::middleware('jwt')->group(function () {
    // Auth routes
    Route::group(['prefix' => 'auth'], function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
        Route::post('change-password', [AuthController::class, 'changePassword']);
    });

    // Product API
    Route::get('products', [ProductApiController::class, 'index']);
    Route::get('products/{product}', [ProductApiController::class, 'show']);
    Route::get('categories', [ProductApiController::class, 'categories']);
    Route::get('products/search/{query}', [ProductApiController::class, 'search']);
    Route::post('products/barcode/{barcode}', [ProductApiController::class, 'findByBarcode']);

    // Transaction API
    Route::post('transactions', [TransactionApiController::class, 'store']);
    Route::get('transactions/{transaction}', [TransactionApiController::class, 'show']);
    Route::get('transactions', [TransactionApiController::class, 'index']);

    // Stock API
    Route::get('products/{product}/stock', [StockApiController::class, 'check']);
    Route::post('stock/adjust', [StockApiController::class, 'adjust']);
    Route::get('stock-adjustments', [StockApiController::class, 'adjustmentHistory']);
});
