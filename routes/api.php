<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ReviewsController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BackendUserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//Public routes
Route::post('/login', [BackendUserController::class, 'login']);
Route::post('/register', [BackendUserController::class, 'store']);
Route::post('/unique', [BackendUserController::class, 'checkUnique']); //validation for unique username
Route::get('/test/test2', [BackendUserController::class, 'getName']);

//Protected routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    //place protected routes here...
    Route::get('/test', [BackendUserController::class, 'test']);
    Route::post('/logout', [BackendUserController::class, 'logout']);
    Route::get('/home', [DashboardController::class, 'index'])->middleware('firstTierRoles');
    

    Route::prefix('/products')->group( function (){
        Route::get('/all', [ProductsController::class, 'getProducts'])->middleware('secondTierRoles');
        Route::get('/show/{id}', [ProductsController::class, 'showProduct'])->middleware('secondTierRoles'); //Admin and Cashier
        Route::post('/store', [ProductsController::class, 'store'])->middleware('firstTierRoles'); //Administrator only
        Route::put('/update/{id}', [ProductsController::class, 'update'])->middleware('firstTierRoles');
        Route::delete('/destroy/{id}', [ProductsController::class, 'destroy'])->middleware('firstTierRoles');
    });

    Route::prefix('/users')->group( function (){
        Route::get('/all', [UsersController::class, 'index'])->middleware('firstTierRoles');
        Route::get('/show/{id}', [UsersController::class, 'show'])->middleware('firstTierRoles');
        Route::put('/update/{id}', [UsersController::class, 'saveUserUpdate'])->middleware('firstTierRoles');
        Route::put('/change/{id}', [UsersController::class, 'changePassword'])->middleware('firstTierRoles');
        Route::delete('/destroy/{id}', [UsersController::class, 'destroy'])->middleware('firstTierRoles');
    });

    Route::prefix('/orders')->group( function (){
        Route::get('/all', [OrdersController::class, 'index'])->middleware('thirdTierRoles'); //admin, cashier and courier
        Route::get('/show/{id}', [OrdersController::class, 'showOrders'])->middleware('thirdTierRoles');
        Route::put('/update/{id}', [OrdersController::class, 'saveOrderUpdate'])->middleware('thirdTierRoles');
        Route::get('/addinfo', [OrdersController::class, 'getAddInfo'])->middleware('secondTierRoles');
        Route::post('/create', [OrdersController::class, 'createOrder'])->middleware('secondTierRoles');
        Route::delete('/destroy/{id}', [OrdersController::class, 'destroyOrder'])->middleware('secondTierRoles');
        Route::get('/print/{id}', [OrdersController::class, 'generateInvoiceAPI'])->middleware('secondTierRoles');
    });

    Route::prefix('/reviews')->group( function (){
        Route::get('/all', [ReviewsController::class, 'index'])->middleware('firstTierRoles');
        Route::get('/show/{id}', [ReviewsController::class, 'show'])->middleware('firstTierRoles');
        Route::delete('/destroy/{id}', [ReviewsController::class, 'destroy'])->middleware('firstTierRoles');
    });

    Route::prefix('/reports')->group( function (){
        Route::post('/orders', [DashboardController::class, 'getOrderReports'])->middleware('firstTierRoles');
        Route::post('/products', [DashboardController::class, 'getProductReports'])->middleware('firstTierRoles');
        Route::post('/orders/export', [DashboardController::class, 'print'])->middleware('firstTierRoles');
    });

    Route::prefix('/notifications')->group( function (){
        Route::get('/all', [DashboardController::class, 'getNotifications']);
        Route::post('/viewed', [DashboardController::class, 'updateNotifications']);
    });

   

});



