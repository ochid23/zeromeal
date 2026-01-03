<?php

use App\Http\Controllers\AuthController;
use App\Http\Middleware\EnsureApiTokenIsValid;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/inventory', [PageController::class, 'inventory'])->name('inventory');
    Route::delete('/inventory/{id}', [PageController::class, 'deleteInventory'])->name('inventory.delete');
    Route::put('/inventory/{id}', [PageController::class, 'updateInventory'])->name('inventory.update');
    Route::get('/recipes', [PageController::class, 'recipes'])->name('recipes');
    Route::get('/shopping-list', [PageController::class, 'shoppingList'])->name('shopping.index');
    Route::post('/shopping-list', [PageController::class, 'storeShoppingItem'])->name('shopping.store');
    Route::get('/shopping-list/{id}/toggle', [PageController::class, 'toggleShoppingItem'])->name('shopping.toggle'); // Pakai GET biar gampang di klik link
    Route::delete('/shopping-list/{id}', [PageController::class, 'deleteShoppingItem'])->name('shopping.delete');
});

// Protected Routes
Route::middleware([EnsureApiTokenIsValid::class])->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/inventory', [PageController::class, 'inventory'])->name('inventory');
    Route::post('/inventory/store', [PageController::class, 'storeInventory'])->name('inventory.store');
});