<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartsController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ShippingAddressController;
use App\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application.
| These routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['jwt-auth'])->group(function () {
    Route::middleware(['role-user'])->group(function () {
        // Products Routes
        Route::get('products', [ProductController::class, 'read']);
        Route::get('products/{id}', [ProductController::class, 'readById']);
        Route::post('products', [ProductController::class, 'create']);
        Route::put('products/{id}', [ProductController::class, 'update']);
        Route::delete('products/{id}', [ProductController::class, 'delete']);

        // Categories Routes
        Route::get('categories', [CategoryController::class, 'read']);
        Route::get('categories/{id}', [CategoryController::class, 'readById']);

        // Carts Routes
        Route::get('carts', [CartsController::class, 'show']);
        Route::get('carts/{id}', [CartsController::class, 'showById']);
        Route::post('carts', [CartsController::class, 'create']);
        Route::put('carts/{id}', [CartsController::class, 'update']);
        Route::delete('carts/{id}', [CartsController::class, 'delete']);

        // Cart Items Routes
        Route::post('cart-items', [CartItemController::class, 'addToCart']);
        Route::put('cart-items/{id}', [CartItemController::class, 'update']);
        Route::delete('cart-items/{id}', [CartItemController::class, 'delete']);

        // Order Routes
        Route::post('orders', [OrderController::class, 'create']);
        Route::get('orders/user', [OrderController::class, 'userOrders']);
        Route::get('orders/{id}', [OrderController::class, 'show']);

        // Wishlist Routes
        Route::get('wishlists', [WishlistController::class, 'index']);
        Route::post('wishlists', [WishlistController::class, 'create']);
        Route::delete('wishlists/{id}', [WishlistController::class, 'delete']);

        // Shipping Address Routes
        Route::post('shipping-addresses', [ShippingAddressController::class, 'store']);
        Route::delete('shipping-addresses/{id}', [ShippingAddressController::class, 'delete']);
    });

    Route::middleware(['role-admin'])->group(function () {
        // Admin Category Routes (redundant if already defined in role-everyone)
        Route::get('categories', [CategoryController::class, 'read']);
        Route::get('categories/{id}', [CategoryController::class, 'readById']);
        Route::post('categories', [CategoryController::class, 'create']);
        Route::put('categories/{id}', [CategoryController::class, 'update']);
        Route::delete('categories/{id}', [CategoryController::class, 'delete']);

        // Admin Order Routes
        Route::get('orders', [OrderController::class, 'index']);
        Route::put('orders/{id}', [OrderController::class, 'update']);
        Route::delete('orders/{id}', [OrderController::class, 'destroy']);
    });
});

Route::post('/login', [UserController::class, 'login']);
Route::post('/signup', [UserController::class, 'signup']);
Route::post('/createTransaction', [PaymentController::class, 'createTransaction']);