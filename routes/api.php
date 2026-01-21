<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\InventoryController; // Pastikan ini sudah di-import
use App\Http\Controllers\Api\ShoppingListController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::put('/user', [AuthController::class, 'update']); // Added for profile/onboarding updates
Route::get('/shopping-list', [ShoppingListController::class, 'index']);
Route::post('/shopping-list', [ShoppingListController::class, 'store']);
Route::put('/shopping-list/{id}/toggle', [ShoppingListController::class, 'toggle']);
Route::delete('/shopping-list/{id}', [ShoppingListController::class, 'destroy']);
    
    // Dashboard
    Route::get('/dashboard-data', [DashboardController::class, 'index']);

    // Inventory
    Route::get('/inventory', [InventoryController::class, 'index']);
    Route::post('/inventory', [InventoryController::class, 'store']);
    Route::get('/master-barang', [InventoryController::class, 'masterData']);
    Route::put('/inventory/{id}', [InventoryController::class, 'update']);

    // --- BARIS INI YANG KEMUNGKINAN HILANG/BELUM ADA ---
    Route::delete('/inventory/{id}', [InventoryController::class, 'destroy']);
    // ---------------------------------------------------
});