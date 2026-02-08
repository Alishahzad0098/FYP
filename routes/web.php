<?php

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Usercontroller;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CarouselController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Ordercontroller;
use App\Http\Controllers\MailController;

Route::get("/form", [ProductController::class, "create"])->name('form.product');
Route::get("/table", [ProductController::class, "table"])->name("table.product");
Route::post("/store", [ProductController::class, "store"])->name("store.product");
Route::get('/edit/{id}', [ProductController::class, 'edit'])->name('edit.product');
Route::post('/update/{id}', [ProductController::class, 'update'])->name('update.product');
Route::get('/delete/{id}', [ProductController::class, 'delete'])->name('delete.product');
Route::get("/", [ProductController::class, "show"])->name('home');
Route::get('/product/{id}', [ProductController::class, "product"])->name('productshow');
Route::get("/products", [ProductController::class, "productshow"])->name('products');
Route::get('/search', [ProductController::class, 'search'])->name('search');
Route::get('/about', [ProductController::class, 'about'])->name('about');
Route::get('/contact', [ProductController::class, 'contact'])->name('contact');
Route::get('/products/compare/{id}', [ProductController::class, 'compare'])->name('products.compare');
Route::get('/category', [ProductController::class, 'index'])->name('products.index');



Route::get("/carform", [CarouselController::class, "create"])->name('form.carousel');
Route::get("/cartable", [CarouselController::class, "table"])->name("table.car");
Route::post("/carstore", [CarouselController::class, "store"])->name("store.car");
Route::get('/cardelete/{id}', [CarouselController::class, 'delete'])->name('delete.car');

Route::get("/register", [Usercontroller::class, "view"])->name('view');
Route::get("/view", [Usercontroller::class, "page"]);
Route::post("/registersave", [Usercontroller::class, "register"])->name('register');
Route::get("/login", [Usercontroller::class, "loginpage"])->name('loginpage');
Route::post("/loginsave", [Usercontroller::class, "login"])->name('login');
Route::get("/dashboard", [Usercontroller::class, "dashboardpage"])->name('dashboard');


Route::post('/logout', function () {
    Auth::logout();
    return redirect()->route('loginpage');
})->name('logout');

Route::get("/authtable", [Usercontroller::class, "table"])->name('authtable');
Route::get("/admintable", [Usercontroller::class, "admintable"])->name('admintable');
Route::get('/edituser/{id}', [Usercontroller::class, "edituser"])->name('edit.user');
Route::post('/user/update/{id}', [UserController::class, 'update'])->name('update.user');
Route::delete('/delete-user/{id}', [Usercontroller::class, 'deleteuser'])->name('delete.user');
// 
// 
Route::post('/add-to-cart', [CartController::class, 'addToCart'])->name('add.to.cart');
Route::get('/cart', [CartController::class, 'showCart'])->name('cart.show');
Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::put('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/checkout', [OrderController::class, 'placeOrder'])->name('checkout');
Route::get('/checkoutpage', [OrderController::class, 'show'])->name('checkoutpage');
Route::get('/admin/ordertable', [OrderController::class, 'order'])->name('order.table');
Route::get('/admin/orderitemtable', [OrderController::class, 'orderitem'])->name('orderitem.table');

Route::get('/test-mail', [Ordercontroller::class, 'sendTestEmail'])->name('test.mail');

