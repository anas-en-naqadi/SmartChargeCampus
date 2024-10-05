<?php

use App\Http\Controllers\Admin\BlacklistController;
use App\Http\Controllers\User\ReviewController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\SellController;

use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductImagesController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\User\ShippingController;
use App\Http\Controllers\User\WishListController;
use App\Models\ProductImages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});




Route::resource('wish-list', WishListController::class);
Route::resource('shipping', ShippingController::class);
Route::resource('blacklist', BlacklistController::class);

// Route::resource('review', ReviewController::class);


// Route for admin access
Route::middleware('admin')->group(function () {
});
Route::resource('product', ProductController::class);

Route::resource('order', OrderController::class);
Route::resource('more-images', ProductImagesController::class);
Route::resource('category', CategoryController::class);
Route::resource('invoice', InvoiceController::class);

Route::post('/register', [AuthController::class, 'register']);

Route::post('/multiple-images', [ProductImagesController::class, 'register']);

Route::get('/users', [UserController::class, 'users']);
Route::get('/weeklySales', [SellController::class, 'weeklySalesChart']);
Route::get('/monthlySales', [SellController::class, 'monthlySalesChart']);
Route::get('/customers', [InvoiceController::class, 'customers']);

Route::get('/sells', [SellController::class, 'sells']);

Route::get('/last-users', [UserController::class, 'getLastCustomers']);
Route::get('/last-orders', [OrderController::class, 'getLastOrders']);
Route::get('/dashboard-data', [UserController::class, 'dashboardData']);

Route::get('/status-orders', [OrderController::class, 'orderStatusPieChart']);
Route::get('/notifications', [UserController::class, 'adminNotifications']);
Route::post('/setReadAt', [UserController::class, 'setRead_at']);
Route::post('/toAdmin', [UserController::class, 'setAsAdmin']);


