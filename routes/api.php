<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Usercontroller;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CarouselController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public Authentication Routes (no auth required)
Route::prefix('auth')->group(function () {
    Route::post('/register', [Usercontroller::class, 'apiRegister']);
    Route::post('/login', [Usercontroller::class, 'apiLogin']);
    Route::post('/forgot-password', [Usercontroller::class, 'apiForgotPassword']);
});

// Public Product Routes (no auth required)
Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'apiIndex']);
    Route::get('/{id}', [ProductController::class, 'apiShow']);
    Route::get('/search', [ProductController::class, 'apiSearch']);
    Route::get('/category/{category}', [ProductController::class, 'apiByCategory']);
    Route::get('/compare/{id}', [ProductController::class, 'apiCompare']);
});

// Public Carousel Routes
Route::prefix('carousel')->group(function () {
    Route::get('/', [CarouselController::class, 'apiIndex']);
});

// Protected Routes (require Sanctum authentication)
Route::middleware('auth:sanctum')->group(function () {

    // Authentication Routes
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [Usercontroller::class, 'apiLogout']);
        Route::get('/me', [Usercontroller::class, 'apiGetProfile']);
        Route::put('/profile', [Usercontroller::class, 'apiUpdateProfile']);
        Route::post('/change-password', [Usercontroller::class, 'apiChangePassword']);
    });

    // Cart Routes
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'apiShowCart']);
        Route::post('/add', [CartController::class, 'apiAddToCart']);
        Route::put('/update/{id}', [CartController::class, 'apiUpdate']);
        Route::delete('/remove/{id}', [CartController::class, 'apiRemove']);
        Route::delete('/clear', [CartController::class, 'apiClearCart']);
    });

    // Order Routes
    Route::prefix('orders')->group(function () {
        Route::post('/', [OrderController::class, 'apiPlaceOrder']);
        Route::get('/', [OrderController::class, 'apiGetOrders']);
        Route::get('/{id}', [OrderController::class, 'apiGetOrder']);
        Route::get('/{id}/items', [OrderController::class, 'apiGetOrderItems']);
        Route::put('/{id}/cancel', [OrderController::class, 'apiCancelOrder']);
    });

    // Admin Routes (protected with admin middleware if available)
    Route::middleware(['admin'])->prefix('admin')->group(function () {

        // Product Management
        Route::prefix('products')->group(function () {
            Route::post('/', [ProductController::class, 'apiStore']);
            Route::put('/{id}', [ProductController::class, 'apiUpdate']);
            Route::delete('/{id}', [ProductController::class, 'apiDelete']);
        });

        // Carousel Management
        Route::prefix('carousel')->group(function () {
            Route::post('/', [CarouselController::class, 'apiStore']);
            Route::put('/{id}', [CarouselController::class, 'apiUpdate']);
            Route::delete('/{id}', [CarouselController::class, 'apiDelete']);
        });

        // User Management
        Route::prefix('users')->group(function () {
            Route::get('/', [Usercontroller::class, 'apiGetAllUsers']);
            Route::get('/{id}', [Usercontroller::class, 'apiGetUser']);
            Route::put('/{id}', [Usercontroller::class, 'apiUpdateUser']);
            Route::delete('/{id}', [Usercontroller::class, 'apiDeleteUser']);
        });

        // Order Management
        Route::prefix('orders')->group(function () {
            Route::get('/', [OrderController::class, 'apiGetAllOrders']);
            Route::put('/{id}/status', [OrderController::class, 'apiUpdateOrderStatus']);
        });
    });
});

// Current User Route
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
