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
Route::middleware(['auth', 'userLogin'])->group(function () {
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
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get("/dashboard", [Usercontroller::class, "dashboardpage"])->name('dashboard');

    // Products
    Route::get("/form", [ProductController::class, "create"])->name('form.product');
    Route::get("/table", [ProductController::class, "table"])->name("table.product");
    Route::post("/store", [ProductController::class, "store"])->name("store.product");
    Route::get('/edit/{id}', [ProductController::class, 'edit'])->name('edit.product');
    Route::post('/update/{id}', [ProductController::class, 'update'])->name('update.product');
    Route::get('/delete/{id}', [ProductController::class, 'delete'])->name('delete.product');

    // Users
    Route::get("/authtable", [Usercontroller::class, "table"])->name('authtable');
    Route::get("/admintable", [Usercontroller::class, "admintable"])->name('admintable');
    Route::get('/edituser/{id}', [Usercontroller::class, "edituser"])->name('edit.user');
    Route::post('/user/update/{id}', [Usercontroller::class, 'update'])->name('update.user');
    Route::delete('/delete-user/{id}', [Usercontroller::class, 'deleteuser'])->name('delete.user');

    // Orders
    Route::get('/admin/ordertable', [Ordercontroller::class, 'order'])->name('order.table');
    Route::get('/admin/orderitemtable', [Ordercontroller::class, 'orderitem'])->name('orderitem.table');

    // Carousel
    Route::get("/carform", [CarouselController::class, "create"])->name('form.carousel');
    Route::get("/cartable", [CarouselController::class, "table"])->name("table.car");
    Route::post("/carstore", [CarouselController::class, "store"])->name("store.car");
    Route::get('/cardelete/{id}', [CarouselController::class, 'delete'])->name('delete.car');
});

// Test mail
Route::get('/test-mail', [Ordercontroller::class, 'sendTestEmail'])->name('test.mail');
