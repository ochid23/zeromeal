<?php

use App\Http\Controllers\AuthController;
use App\Http\Middleware\EnsureApiTokenIsValid;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// NUCLEAR OPTION: Manually register API route in web.php to bypass api.php/prefix issues
// This ensures domain.com/api/save-preferences IS registered no matter what.
Route::any('/api/save-preferences', [App\Http\Controllers\Api\AuthController::class, 'update']);
// MAGIC ROUTE: No /api prefix, pure web route disguised as API. 
// If this works, the /api path is the problem.
// MUST use auth:sanctum middleware otherwise $request->user() is null -> 500 Error
Route::any('/magic-save', [App\Http\Controllers\Api\AuthController::class, 'update'])->middleware('auth:sanctum');

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Inventory & Shopping List (Now accessible without auth for testing, or move to protected if needed later)
    Route::get('/inventory', [PageController::class, 'inventory'])->name('inventory');
    Route::post('/inventory/store', [PageController::class, 'storeInventory'])->name('inventory.store');
    Route::delete('/inventory/{id}', [PageController::class, 'deleteInventory'])->name('inventory.delete'); // Make sure form uses DELETE method
    Route::put('/inventory/{id}', [PageController::class, 'updateInventory'])->name('inventory.update');

    Route::get('/recipes', [PageController::class, 'recipes'])->name('recipes');
    Route::get('/recipes/{id}/details', [PageController::class, 'getRecipeDetails'])->name('recipes.details');

    Route::get('/shopping-list', [PageController::class, 'shoppingList'])->name('shopping.index');
    Route::post('/shopping-list', [PageController::class, 'storeShoppingItem'])->name('shopping.store');
    Route::put('/shopping-list/{id}', [PageController::class, 'updateShoppingItem'])->name('shopping.update');
    Route::get('/shopping-list/{id}/toggle', [PageController::class, 'shoppingToggle'])->name('shopping.toggle');
    Route::delete('/shopping-list/{id}', [PageController::class, 'shoppingDelete'])->name('shopping.delete');

    Route::get('/favorite-recipes', [PageController::class, 'favoriteRecipes'])->name('favorite-recipes');
    Route::get('/finance', [PageController::class, 'finance'])->name('finance');
    Route::post('/toggle-favorite', [PageController::class, 'toggleFavorite'])->name('toggle-favorite');
});

// Protected Routes
Route::middleware([EnsureApiTokenIsValid::class])->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/inventory', [PageController::class, 'inventory'])->name('inventory');
    Route::post('/inventory/store', [PageController::class, 'storeInventory'])->name('inventory.store');
    

    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');

    Route::get('/onboarding', [AuthController::class, 'showOnboarding'])->name('onboarding');
    Route::post('/onboarding', [AuthController::class, 'storePreferences'])->name('onboarding.store');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Redirect /admin/login to main /login
    Route::get('/login', function () {
        return redirect()->route('login');
    })->name('login');

    // Using main AuthController for logout now, or keep specific if needed.
    // However, the dashboard logic used AuthController::logout (Admin version).
    // Let's point logout to the main AuthController logout which handles both.
    Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

    Route::group([], function () {
        Route::get('/dashboard', [App\Http\Controllers\Admin\RecipeController::class, 'index'])->name('dashboard');
        Route::resource('recipes', App\Http\Controllers\Admin\RecipeController::class);
        Route::post('/logout', [App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('logout');
    });
});