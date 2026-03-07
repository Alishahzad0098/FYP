<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Usercontroller;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CarouselController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Ordercontroller;


// Public routes
Route::get("/", [ProductController::class, "show"])->name('home');
Route::get('/product/{id}', [ProductController::class, "product"])->name('productshow');
Route::get("/products", [ProductController::class, "productshow"])->name('products');
Route::get('/search', [ProductController::class, 'search'])->name('search');
Route::get('/about', [ProductController::class, 'about'])->name('about');
Route::get('/contact', [ProductController::class, 'contact'])->name('contact');
Route::get('/products/compare/{id}', [ProductController::class, 'compare'])->name('products.compare');
Route::get('/category', [ProductController::class, 'index'])->name('products.index');

Route::get("/register", [Usercontroller::class, "view"])->name('view');
Route::post("/registersave", [Usercontroller::class, "register"])->name('register');
Route::get("/login", [Usercontroller::class, "loginpage"])->name('loginpage');
Route::post("/loginsave", [Usercontroller::class, "login"])->name('login');

// Authenticated routes
Route::middleware([
    \Illuminate\Auth\Middleware\Authenticate::class,
    \App\Http\Middleware\UserLogin::class,
])->group(function () {
    Route::post('/logout', function () {
        Auth::logout();
        return redirect()->route('loginpage');
    })->name('logout');

    // Cart
    Route::get('/cart', [CartController::class, 'showCart'])->name('cart.show');
    Route::post('/add-to-cart', [CartController::class, 'addToCart'])->name('add.to.cart');
    Route::put('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

    // Checkout
    Route::get('/checkoutpage', [Ordercontroller::class, 'show'])->name('checkoutpage');
    Route::post('/checkout', [Ordercontroller::class, 'placeOrder'])->name('checkout');
});
// Admin-only routes
Route::middleware([
    \Illuminate\Auth\Middleware\Authenticate::class,
    \App\Http\Middleware\CheckRole::class . ':admin',
])->group(function () {

    // Dashboard
    Route::get("/dashboard", [Usercontroller::class, "dashboardpage"])->name('dashboard');

    // Products
    Route::prefix('products')->group(function () {
        Route::get("/form", [ProductController::class, "create"])->name('form.product');
        Route::get("/table", [ProductController::class, "table"])->name("table.product");
        Route::post("/store", [ProductController::class, "store"])->name("store.product");
        Route::get('/edit/{id}', [ProductController::class, 'edit'])->name('edit.product');
        Route::post('/update/{id}', [ProductController::class, 'update'])->name('update.product');
        Route::get('/delete/{id}', [ProductController::class, 'delete'])->name('delete.product');
    });

    // Users
    Route::prefix('users')->group(function () {
        Route::get("/authtable", [Usercontroller::class, "table"])->name('authtable');
        Route::get("/admintable", [Usercontroller::class, "admintable"])->name('admintable');
        Route::get('/edituser/{id}', [Usercontroller::class, "edituser"])->name('edit.user');
        Route::post('/user/update/{id}', [Usercontroller::class, 'update'])->name('update.user');
        Route::delete('/delete-user/{id}', [Usercontroller::class, 'deleteuser'])->name('delete.user');
    });

    // Orders
    Route::prefix('admin')->group(function () {
        Route::get('/ordertable', [Ordercontroller::class, 'order'])->name('order.table');
        Route::get('/orderitemtable', [Ordercontroller::class, 'orderitem'])->name('orderitem.table');
    });

    // Carousel
    Route::prefix('carousel')->group(function () {
        Route::get("/form", [CarouselController::class, "create"])->name('form.carousel');
        Route::get("/table", [CarouselController::class, "table"])->name("table.car");
        Route::post("/store", [CarouselController::class, "store"])->name("store.car");
        Route::get('/delete/{id}', [CarouselController::class, 'delete'])->name('delete.car');
    });
});


// Test mail
Route::get('/test-mail', [Ordercontroller::class, 'sendTestEmail'])->name('test.mail');
