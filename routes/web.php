<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\API\Customer\ProductController;
use App\Http\Controllers\API\Customer\CartController;
use App\Http\Controllers\API\Customer\OrderController;
use App\Http\Controllers\API\Customer\AddressController;
use App\Http\Controllers\API\Customer\CheckoutController;

/*
|--------------------------------------------------------------------------
| Admin Controllers
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\API\Admin\ProductController as AdminProductController;

use App\Http\Controllers\API\Admin\CategoryController;
use App\Http\Controllers\API\Admin\CollectionController;
use App\Http\Controllers\API\Admin\TagController;
use App\Http\Controllers\API\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\API\Admin\PaymentController;
use App\Http\Controllers\API\Admin\ShipmentController;

/*
|--------------------------------------------------------------------------
| PUBLIC PRODUCT ROUTES
|--------------------------------------------------------------------------
*/
Route::view('/', 'welcome')->name('home');

Route::get('/products', [
    ProductController::class,
    'index'
]);

Route::get('/products/{product}', [
    ProductController::class,
    'show'
]);

/*
|--------------------------------------------------------------------------
| CUSTOMER ROUTES
|--------------------------------------------------------------------------
*/

Route::post('/cart', [
    CartController::class,
    'store'
]);

Route::post('/checkout', [
    CheckoutController::class,
    'store'
]);

Route::get('/orders', [
    OrderController::class,
    'index'
]);

Route::get('/orders/{order}', [
    OrderController::class,
    'show'
]);

Route::resource(
    '/addresses',
    AddressController::class
);

/*
|--------------------------------------------------------------------------
| ADMIN PRODUCT ROUTES
|--------------------------------------------------------------------------
*/


Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::resource(
    '/admin/products',
    AdminProductController::class
);

// Route::resource(
//     '/admin/categories',
//     CategoryController::class
// );

// Route::resource(
//     '/admin/collections',
//     CollectionController::class
// );

// Route::resource(
//     '/admin/tags',
//     TagController::class
// );

// Route::resource(
//     '/admin/orders',
//     AdminOrderController::class
// );

// Route::resource(
//     '/admin/payments',
//     PaymentController::class
// );

// Route::resource(
//     '/admin/shipments',
//     ShipmentController::class
// );
});

require __DIR__.'/settings.php';
