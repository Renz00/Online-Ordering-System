<?php

use App\Events\Hello;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\BackendUserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/broadcast', [App\Http\Controllers\BackendUserController::class, 'btest'])->name('btest');

//testing the phpspreadsheet package
Route::get('/print/test', [App\Http\Controllers\BackendUserController::class, 'print']);

Route::get('/', [App\Http\Controllers\ProductsController::class, 'index'])->name('home');
//Set route to {any?} to prevent 404 error when accessing router components
Route::get('/dashboard/{any?}', [App\Http\Controllers\PagesController::class, 'dashboard'])->name('dashboard');
Route::get('/error', [App\Http\Controllers\PagesController::class, 'error'])->name('error');
Route::get('/contact-us', [App\Http\Controllers\PagesController::class, 'contact'])->name('contact');

//Products Controller
Route::prefix('/products')->group( function (){
    Route::get('/full/menu', [App\Http\Controllers\ProductsController::class, 'showMenu'])->name('products.menu');
    Route::get('/{slug}/favorites', [App\Http\Controllers\ProductsController::class, 'showFavorites'])->name('products.favorites');
});
Route::resource('products', 'App\Http\Controllers\ProductsController', ['except' => ['showMenu']]);
//Put resource routes at the bottom to prevent errors with show method

//Orders Controller
Route::prefix('/orders')->group( function (){
    Route::get('/{order}/d', [App\Http\Controllers\OrdersController::class, 'destroy'])->name('orders.destroy');
    Route::get('/{order_group}/d/g', [App\Http\Controllers\OrdersController::class, 'destroyGroup'])->name('orders.destroy-group');
    Route::get('/{slug}/user-orders', [App\Http\Controllers\OrdersController::class, 'userOrders'])->name('orders.user-orders');
    Route::put('/c/{order_group}', [App\Http\Controllers\OrdersController::class, 'cancelOrder'])->name('orders.cancel');
    Route::get('/{order_group}/details', [App\Http\Controllers\OrdersController::class, 'getOrderDetails'])->name('orders.details');
    Route::get('/{order_group}/invoice', [App\Http\Controllers\OrdersController::class, 'generateInvoice'])->name('orders.invoice');
    Route::post('/review', [App\Http\Controllers\OrdersController::class, 'storeReview'])->name('orders.review');
    Route::get('/{order_group}/a', [App\Http\Controllers\OrdersController::class, 'orderAgain'])->name('orders.again');
});
Route::resource('orders', 'App\Http\Controllers\OrdersController', ['except' => ['destroy']]);
//Put resource routes at the bottom to prevent errors with show method

//Users Controller
Route::get('/user/{slug}/{type}', [App\Http\Controllers\UsersController::class, 'edit'])->name('user.edit');
Route::resource('user', 'App\Http\Controllers\UsersController', ['except' => ['edit']]);

//Address Controller
Route::post('address', [App\Http\Controllers\AddressController::class, 'store'])->name('address.store');

//Laravel UI Authorization scaffold
Auth::routes();

// Route::get('/orders/{order}', function (Orders $order){
//     return $order->usser_id;
// })->name('orders.show');




